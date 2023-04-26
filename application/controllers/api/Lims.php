<?php
require APPPATH . '/libraries/LIMS_API_Controller.php';
class Lims extends LIMS_API_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->_table = 'ci_orders';
		$this->load->model('LimsAPIModel');
		error_reporting(1);
    }

	public function index_get($id = 0){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$id = $this->uri->segment('4');
		}

		if(!empty($id)){
			$this->db->select('ci_orders.id, ci_orders.order_number,ci_orders.allergens,ci_orders.vet_user_id,ci_orders.lab_id,ci_orders.order_can_send_to,ci_orders.delivery_practice_id,ci_orders.reference_number,ci_orders.species_selection,ci_orders.product_code_selection,ci_orders.pet_id,ci_orders.veterinary_surgeon,ci_orders.lab_order_number,ci_orders.sample_volume,petOwner.name AS pet_owner_name,petOwner.last_name AS pet_owner_lname,ci_pets.name AS pet_name,ci_pets.type,practice.country,practice.name AS practice_name');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.id', $id);
			$this->db->where('ci_orders.order_type', '2');
			$this->db->where('ci_orders.serum_type', '2');
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_lims_sample', '0');
			$this->db->where('ci_orders.cep_id', '0');

			$datas = $this->db->get()->result_array();
		}else{
			$this->db->select('ci_orders.id, ci_orders.order_number,ci_orders.allergens,ci_orders.vet_user_id,ci_orders.lab_id,ci_orders.order_can_send_to,ci_orders.delivery_practice_id,ci_orders.reference_number,ci_orders.species_selection,ci_orders.product_code_selection,ci_orders.pet_id,ci_orders.veterinary_surgeon,ci_orders.lab_order_number,ci_orders.sample_volume,petOwner.name AS pet_owner_name,petOwner.last_name AS pet_owner_lname,ci_pets.name AS pet_name,ci_pets.type,practice.country,practice.name AS practice_name');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.order_type', '2');
			$this->db->where('ci_orders.serum_type', '2');
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_lims_sample', '0');
			$this->db->where('ci_orders.cep_id', '0');
			$this->db->where('ci_orders.order_number >', '44053');
			$datas = $this->db->get()->result_array();
		}

		$data = []; $data_details = []; $sendId = 0; $animalType = ""; $petName = ""; $petOwnerName = ""; $account_ref = ''; $sampleVolume = ''; $testRequested = ''; $testComponents = ''; $testLIMSComponents = '';
		foreach($datas as $data_detail){
			/* Start Species */
			if($data_detail['type']==1){
				$animalType = "Feline";
				$animalTemplate = "FELINE_SERUM";
			}elseif($data_detail['type']==2){
				$animalType = "Canine";
				$animalTemplate = "CANINE_SERUM";
			}elseif($data_detail['type']==3){
				$animalType = "Equine";
				$animalTemplate = "EQUINE_SERUM";
			}
			/* End Species */

			/* Start Animal Name */
			$petName = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']);
			/* End Animal Name */

			/* Start Owner Surname */
			if($data_detail['pet_owner_name'] == NULL && $data_detail['pet_owner_lname'] == NULL){
				$petOwnerName = '';
			}else{
				if($data_detail['pet_owner_name'] == NULL || preg_replace('/\s+/', '', $data_detail['pet_owner_name']) == ""){
					$petOwnerName = preg_replace('/\s+/', '', $data_detail['pet_owner_lname']);
				}else{
					$petOwnerName = preg_replace('/\s+/', '', $data_detail['pet_owner_name']) .' '. preg_replace('/\s+/', '', $data_detail['pet_owner_lname']);
				}
			}
			/* End Owner Surname */

			/* Start Customer Reference */
			if($data_detail['lab_id'] > 0){
				if($data_detail['order_can_send_to'] == '1'){
					$userData2 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref','address_3'");
					$refDatas = $this->LimsAPIModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$postCode = !empty($refDatas['address_3']) ? ' ('.$refDatas['address_3'].')' : '';
					$sendId = $data_detail['delivery_practice_id'];
				}else{
					$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref','address_3'");
					$refDetails = $this->LimsAPIModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$postCode = !empty($refDetails['address_3']) ? ' ('.$refDatas['address_3'].')' : '';
					$sendId = $data_detail['lab_id'];
				}
			}else{
				if($data_detail['order_can_send_to'] == '1'){
					$userData2 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref','address_3'");
					$refDatas = $this->LimsAPIModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$postCode = !empty($refDatas['address_3']) ? ' ('.$refDatas['address_3'].')' : '';
					$sendId = $data_detail['delivery_practice_id'];
				}else{
					$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref','address_3'");
					$refDatas = $this->LimsAPIModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$postCode = !empty($refDatas['address_3']) ? ' ('.$refDatas['address_3'].')' : '';
					$sendId = $data_detail['vet_user_id'];
				}
			}
			$practiceName = $data_detail['practice_name'].$postCode;
			/* End Customer Reference */

			/* Start Test(s) requested */
			if(!empty($data_detail['product_code_selection'])){
				$this->db->select('name,lims_name,lims_code,lims_test_code');
				$this->db->from('ci_price');
				$this->db->where('id', $data_detail['product_code_selection']);
				$resuled = $this->db->get()->row();
				$productName = !empty($resuled->name)?$resuled->name:$resuled->name;
				$testRequested = !empty($resuled->lims_name)?$resuled->lims_name:$resuled->name;
				if(preg_match('/\bAcute Phase Proteins\b/', $productName)){
					$testRequestedCode = 'FEL_AGP';
				}else{
					$testRequestedCode = !empty($resuled->lims_code)?$resuled->lims_code:'';
				}
				$limsTestCode = !empty($resuled->lims_test_code)?$resuled->lims_test_code:'';
			}else{
				$productName = '';
				$testRequested = 'Serum Testing';
				$testRequestedCode = '';
				$limsTestCode = '';
			}
			/* End Test(s) requested */

			/* Start Sample Volume */
			$malasseziaID = '';
			if((preg_match('/\bEnvironmental\b/', $productName)) && (!preg_match('/\bFood\b/', $productName))){
				if($data_detail['type']==1){
					$sampleVolume = $data_detail['sample_volume']-600;
				}elseif($data_detail['type']==2){
					$sampleVolume = $data_detail['sample_volume']-600;
				}elseif($data_detail['type']==3){
					$sampleVolume = $data_detail['sample_volume']-1000;
				}
				if($data_detail['type']==1){
					$malasseziaID = ',2247';
				}elseif($data_detail['type']==2){
					$malasseziaID = ',1904';
				}
			}elseif((!preg_match('/\bEnvironmental\b/', $productName)) && (preg_match('/\bFood\b/', $productName))){
				if($data_detail['type']==1){
					$sampleVolume = $data_detail['sample_volume']-400;
				}elseif($data_detail['type']==2){
					$sampleVolume = $data_detail['sample_volume']-400;
				}elseif($data_detail['type']==3){
					$sampleVolume = $data_detail['sample_volume']-1000;
				}
			}elseif((preg_match('/\bEnvironmental\b/', $productName)) && (preg_match('/\bFood\b/', $productName))){
				if($data_detail['type']==1){
					$sampleVolume = $data_detail['sample_volume']-1000;
				}elseif($data_detail['type']==2){
					$sampleVolume = $data_detail['sample_volume']-1000;
				}elseif($data_detail['type']==3){
					$sampleVolume = $data_detail['sample_volume']-1000;
				}
				if($data_detail['type']==1){
					$malasseziaID = ',2247';
				}elseif($data_detail['type']==2){
					$malasseziaID = ',1904';
				}
			}elseif(preg_match('/\bAcute Phase Proteins\b/', $productName)){
				$sampleVolume = $data_detail['sample_volume']-50;
			}
			/* End Sample Volume */

			/* Start Pet's Infomation */
			$petSex = ''; $petAgeYear = ''; $petAgeMonth = ''; $breedName = '';
			if($data_detail['pet_id'] > 0){
				$this->db->select('type,breed_id,other_breed,gender,age,age_year');
				$this->db->from('ci_pets');
				$this->db->where('id', $data_detail['pet_id']);
				$petinfo = $this->db->get()->row_array();
				if($petinfo['breed_id'] > 0){
					$this->db->select('name');
					$this->db->from('ci_breeds');
					$this->db->where('id', $petinfo['breed_id']);
					$breedinfo = $this->db->get()->row_array();
					if($breedinfo['name'] != ''){
						$breedName = $breedinfo['name'];
					}
				}else{
					if($petinfo['other_breed']!=""){
						$breedName = $petinfo['other_breed'];
					}
				}

				if($petinfo['gender'] == '1'){
					$petSex = 'Male';
				}elseif($petinfo['gender'] == '2'){
					$petSex = 'Female';
				}

				if($petinfo['age_year'] != ''){
					$petAgeYear = $petinfo['age_year'];
				}

				if($petinfo['age'] != ''){
					$petAgeMonth = $petinfo['age'];
				}
			}
			/* End Pet's Infomation */

			/* Start Allergens */
			$allergensArr = json_decode($data_detail['allergens']);
			$tempArr = $testCompArr = []; $tempLIMSArr = [];
			if(!empty($allergensArr)){
				foreach($allergensArr as $rowa){
					$alParents = $this->LimsAPIModel->getAllergenParent($rowa);
					$tempArr['category'] = $alParents['name'];
					$alInfo = $this->LimsAPIModel->getAllergensData($rowa);
					$tempArr['Nextvu_name'] = $alInfo['name'];
					$tempArr['Nextvu_code'] = $alInfo['code'];
					if((preg_match('/\bEnvironmental\b/', $productName)) && (!preg_match('/\bFood\b/', $productName))){
						if($data_detail['species_selection'] == 1 && $alInfo['can_allgy_env'] > 0){
							$tempLIMSArr[] = $alInfo['can_allgy_env'];
						}
						if($data_detail['species_selection'] == 2 && $alInfo['equ_allgy_env'] > 0){
							$tempLIMSArr[] = $alInfo['equ_allgy_env'];
						}
						if($data_detail['species_selection'] == 3 && $alInfo['fel_allgy_env'] > 0){
							$tempLIMSArr[] = $alInfo['fel_allgy_env'];
						}
					}elseif((!preg_match('/\bEnvironmental\b/', $productName)) && (preg_match('/\bFood\b/', $productName))){
						if($data_detail['species_selection'] == 1){
							if($alInfo['can_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['can_allgy_food_ige']; }
							if($alInfo['can_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['can_allgy_food_igg']; }
						}
						if($data_detail['species_selection'] == 2){
							if($alInfo['equ_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['equ_allgy_food_ige']; }
							if($alInfo['equ_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['equ_allgy_food_igg']; }
						}
						if($data_detail['species_selection'] == 3){
							if($alInfo['fel_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['fel_allgy_food_ige']; }
							if($alInfo['fel_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['fel_allgy_food_igg']; }
						}
					}elseif((preg_match('/\bEnvironmental\b/', $productName)) && (preg_match('/\bFood\b/', $productName))){
						if($data_detail['species_selection'] == 1){
							if($alInfo['can_allgy_env'] > 0){ $tempLIMSArr[] = $alInfo['can_allgy_env']; }
							if($alInfo['can_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['can_allgy_food_ige']; }
							if($alInfo['can_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['can_allgy_food_igg']; }
						}
						if($data_detail['species_selection'] == 2){
							if($alInfo['equ_allgy_env'] > 0){ $tempLIMSArr[] = $alInfo['equ_allgy_env']; }
							if($alInfo['equ_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['equ_allgy_food_ige']; }
							if($alInfo['equ_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['equ_allgy_food_igg']; }
						}
						if($data_detail['species_selection'] == 3){
							if($alInfo['fel_allgy_env'] > 0){ $tempLIMSArr[] = $alInfo['fel_allgy_env']; }
							if($alInfo['fel_allgy_food_ige'] > 0){ $tempLIMSArr[] = $alInfo['fel_allgy_food_ige']; }
							if($alInfo['fel_allgy_food_igg'] > 0){ $tempLIMSArr[] = $alInfo['fel_allgy_food_igg']; }
						}
					}
					$testCompArr[] = $tempArr;
				}
			}
			if(!empty($testCompArr)){
				$testComponents = json_encode($testCompArr);
			}
			if(!empty($tempLIMSArr)){
				$testLIMSComponents = implode(",",$tempLIMSArr);
			}
			/* End Allergens */

			if($sampleVolume > 0){
				$data["NextVuOrderId"]		= $data_detail['order_number'];
				$data["Lab_Number"]			= $data_detail['lab_order_number'];
				$data["PracticeName"]		= $practiceName;
				$data["CustomerReference"]	= $account_ref;
				$data["Species"]			= $animalType;
				$data["Template_Species"]	= $animalTemplate;
				$data["AnimalName"]			= $petName;
				$data["OwnerSurname"]		= $petOwnerName;
				$data["Sex"]				= $petSex;
				$data["AgeYears"]			= $petAgeYear;
				$data["AgeMonth"]			= $petAgeMonth;
				$data["Breed"]				= $breedName;
				$data["SubmittedVet"]		= $data_detail['veterinary_surgeon'];
				$data["SampleVolume"]		= $sampleVolume;
				$data["TestRequested"]		= $testRequested;
				$data["TestRequestedCode"]	= $testRequestedCode;
				$data["LIMSTestCode"]		= $limsTestCode;
				$data["Allergens"]			= $testComponents;
				if(preg_match('/\bAcute Phase Proteins\b/', $productName)){
					$data["LIMSAllergensIDs"]	= '1386';
				}else{
					if($malasseziaID != ""){
						$data["LIMSAllergensIDs"]	= $testLIMSComponents.$malasseziaID;
					}else{
						$data["LIMSAllergensIDs"]	= $testLIMSComponents;
					}
				}
				$data_details[] = $data;
			}
		}
		$this->response($data_details, LIMS_API_Controller::HTTP_OK);
	}

	public function index_post($order_number){
		if($this->uri->segment('3') == 'authorised'){
			if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
				$order_number = $this->uri->segment('4');
			}
			$orderNumber = $order_number;
			$input = $this->input->post('authorised_status');
			if(!isset($input)){
				$data = file_get_contents('php://input');
				$data = json_decode($data,true);
				$input = $data['authorised_status'];
			}

			$updtData = [];
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('order_number', $orderNumber);
			$this->db->where('is_draft', '0');
			$res = $this->db->get();
			if($res->num_rows() == 0 ){
				$this->response(['Order number is not found.'], LIMS_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($input == ''){
				$this->response(['Please enter a valid status.'], LIMS_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}else{
				$orderID = $res->row()->id;
				if($input == 'Authorised' || $input == 'authorised'){
				$updtData['is_authorised'] = 1;
				}else{
				$updtData['is_authorised'] = 0;
				}
				$this->db->update('ci_orders', $updtData, array('id'=>$orderID));

				$orderData['text'] = $input;
				$orderData['order_id'] = $orderID;
				$orderData['created_by'] = '99999';
				$orderData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderData);

				$this->response(['Order Authorised Successfully.'], LIMS_API_Controller::HTTP_OK);
			}
		}else{
			if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
				$order_number = $this->uri->segment('4');
			}
			$orderNumber = $order_number;
			$input = $this->input->post('sampleStatus');
			$labNumber = $this->input->post('Lab_Number');
			if(!isset($input)){
				$data = file_get_contents('php://input');
				$data = json_decode($data,true);
				$input = $data['sampleStatus'];
				$labNumber = $data['Lab_Number'];
			}

			$updtData = [];
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('order_number', $orderNumber);
			$this->db->where('is_draft', '0');
			$res = $this->db->get();
			if($res->num_rows() == 0 ){
				$this->response(['Order number is not found.'], LIMS_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($input == ''){
				$this->response(['Please enter a valid status.'], LIMS_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}else{
				$orderID = $res->row()->id;
				if(!empty($labNumber)){
					$updtData['lab_order_number'] = $labNumber;
				}
				$updtData['lims_status'] = $input;
				if($input == 1){
					$updtData['send_lims_sample'] = 1;
				}else{
					$updtData['send_lims_sample'] = 0;
				}
				$this->db->update('ci_orders', $updtData, array('id'=>$orderID));

				$orderData['text'] = 'Send to LIMS Sample';
				$orderData['order_id'] = $orderID;
				$orderData['created_by'] = '99999';
				$orderData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderData);

				$this->response(['Order status has been updated successfully.'], LIMS_API_Controller::HTTP_OK);
			}
		}
    }

}
?>