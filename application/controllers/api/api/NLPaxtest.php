<?php
require APPPATH . '/libraries/PAX_API_Controller.php';

class NLPaxtest extends PAX_API_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->_table = 'ci_orders';
		$this->load->model('AllergensModel');
		$this->load->model('UsersDetailsModel');
		error_reporting(0);
    }

	public function index_get($id = 0){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$id = $this->uri->segment('4');
		}

		if(!empty($id)){
			$this->db->select('id, lab_id, vet_user_id, pet_owner_id, pet_id, order_number, lab_order_number, species_selection, product_code_selection, order_can_send_to');
			$this->db->from($this->_table);
			$this->db->where('ci_orders.id', $id);
			$this->db->where('ci_orders.is_confirmed', '4');
			$this->db->where('ci_orders.serum_type', '1');
			$this->db->where('ci_orders.is_raptor_result', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$this->db->where('ci_orders.order_type', '2');
			$datas = $this->db->get()->result_array();
		}else{
			$date7day = date('Y-m-d', strtotime('-7 days'));
			$datetoday = date('Y-m-d');
			$this->db->select('id, lab_id, vet_user_id, pet_owner_id, pet_id, order_number, lab_order_number, species_selection, product_code_selection, order_can_send_to');
			$this->db->from($this->_table);
			$this->db->where('ci_orders.is_confirmed', '4');
			$this->db->where('ci_orders.serum_type', '1');
			$this->db->where('ci_orders.is_raptor_result', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$this->db->where('ci_orders.order_type', '2');
			$this->db->where('ci_orders.shipping_date BETWEEN "'.$date7day.'" AND "'.$datetoday.'"');
			$datas = $this->db->get()->result_array();
		}

		$data = $data_details = []; $sendId = 0; $barCode = $sampleCode = $articlecode = $countryCode = ''; 
		foreach($datas as $data_detail){
			if($data_detail['lab_id'] > 0){
				$sqluk = "SELECT managed_by_id FROM `ci_users` WHERE id = '". $data_detail['lab_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}else{
				$sqluk = "SELECT managed_by_id FROM `ci_users` WHERE id = '". $data_detail['vet_user_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}

			if(!empty($zoneby) && in_array("6", $zoneby)){
				$AnimalType = "";
				if($data_detail['species_selection']==1){
					$AnimalType = "dog";
				}elseif($data_detail['species_selection']==2){
					$AnimalType = "horse";
				}elseif($data_detail['species_selection']==3){
					$AnimalType = "cat";
				}
				
				$this->db->select('name as pet_owner_name,last_name as pet_owner_lname');
				$this->db->from('ci_users');
				$this->db->where('id',$data_detail['pet_owner_id']);
				$wusruery = $this->db->get()->row();
				$petOwner = '';
				if($wusruery->pet_owner_name == NULL && $wusruery->pet_owner_lname == NULL){
					$petOwner = '';
				}else{
					if($wusruery->pet_owner_name == NULL || preg_replace('/\s+/', '', $wusruery->pet_owner_name) == ""){
						$petOwner = preg_replace('/\s+/', '', $wusruery->pet_owner_lname);
					}else{
						$petOwner = preg_replace('/\s+/', '', $wusruery->pet_owner_name) .' '. preg_replace('/\s+/', '', $wusruery->pet_owner_lname);
					}
				}

				$this->db->select('name as pet_name');
				$this->db->from('ci_pets');
				$this->db->where('id',$data_detail['pet_id']);
				$petuery = $this->db->get()->row();

				$account_ref = ''; $accountRef = ''; $order_send_to = ''; $results_to_practice = 0; $invoice_to_practice = 0; 
				if($data_detail['lab_id'] > 0){
					$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref', 'vat_applicable', 'address_1', 'address_2', 'address_3', 'post_code', 'town_city', 'results_to_practice', 'invoice_to_practice'");
					$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
					$address_1 = !empty($refDetails['address_1']) ? $refDetails['address_1'] : '';
					$address_2 = !empty($refDetails['address_2']) ? $refDetails['address_2'] : '';
					$address_3 = !empty($refDetails['address_3']) ? $refDetails['address_3'] : '';
					$town = !empty($refDetails['town_city']) ? $refDetails['town_city'] : '';
					$postcode = !empty($refDetails['post_code']) ? $refDetails['post_code'] : '';
					$results_to_practice = !empty($refDetails['results_to_practice']) ? $refDetails['results_to_practice'] : '0';
					$invoice_to_practice = !empty($refDetails['invoice_to_practice']) ? $refDetails['invoice_to_practice'] : '0';

					$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
					$sendId = $data_detail['lab_id'];
				}else{
					$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref', 'vat_applicable', 'add_1', 'add_2', 'add_3', 'add_4', 'address_3'");
					$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
					$address_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'] : '';
					$address_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'] : '';
					$address_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
					$town = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
					$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
					$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
					$sendId = $data_detail['vet_user_id'];
				}

				$this->db->select('barcode, sample_code');
				$this->db->from('ci_raptor_serum_result');
				$this->db->where('nextvu_id LIKE',$data_detail['order_number']);
				$serumquery = $this->db->get()->row();
				$barCode = !empty($serumquery->barcode)?$serumquery->barcode:'';
				$sampleCode = !empty($serumquery->sample_code)?$serumquery->sample_code:'';

				$this->db->select('name');
				$this->db->from('ci_price');
				$this->db->where('id', $data_detail['product_code_selection']);
				$respnedn = $this->db->get()->row();
				if($respnedn->name == 'PAX Environmental Screening'){
					$articlecode = 'PAX1';
				}elseif($respnedn->name == 'PAX Food Screening'){
					$articlecode = 'PAX2';
				}elseif($respnedn->name == 'PAX Environmental + Food Screening'){
					$articlecode = 'PAX3';
				}elseif($respnedn->name == 'PAX Environmental after screening'){
					$articlecode = 'PAX4';
				}elseif($respnedn->name == 'PAX Food after screening'){
					$articlecode = 'PAX5';
				}elseif($respnedn->name == 'PAX Environmental + Food after screening'){
					$articlecode = 'PAX6';
				}elseif($respnedn->name == 'PAX Environmental'){
					$articlecode = 'PAX7';
				}elseif($respnedn->name == 'PAX Food'){
					$articlecode = 'PAX8';
				}elseif($respnedn->name == 'PAX Environmental + Food'){
					$articlecode = 'PAX9';
				}

				$sqlc = "SELECT u.country, c.name FROM `ci_users` as u LEFT JOIN `ci_staff_countries` as c ON u.country = c.id WHERE u.id = '".$sendId."'";
				$responc = $this->db->query($sqlc);
				$cuntyquery = $responc->row();
				$country = !empty($cuntyquery->name)?$cuntyquery->name:'UK';
				$countryID = !empty($cuntyquery->country)?$cuntyquery->country:'0';

				$data["UniqueIdentifier"] = $data_detail['order_number'];
				$data["Lab_Number"] = $data_detail['lab_order_number'];
				$data["Species"] = $AnimalType;
				$data["OwnerName"] = $petOwner;
				$data["AnimalName"] = ($petuery->pet_name==NULL) ? "" : preg_replace('/\s+/', '', $petuery->pet_name);
				//$data["Country"] = $country;
				if($data_detail['lab_id'] > 0){
					$data["Orderby"] = $account_ref;
					if($results_to_practice > 0){
						$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
						$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
						$refDatap = array_column($refDatap, 'column_field', 'column_name');
						$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
						$data["Ship_To"] = $accountRef;
					}else{
						$data["Ship_To"] = $account_ref;
					}
					if($invoice_to_practice > 0){
						$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
						$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
						$refDatap = array_column($refDatap, 'column_field', 'column_name');
						$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
						$data["Invoice_To"] = $accountRef;
					}else{
						$data["Invoice_To"] = $account_ref;
					}
				}else{
					$data["Orderby"] = $account_ref;
					$data["Ship_To"] = $account_ref;
					$data["Invoice_To"] = $account_ref;
				}
				$data["Reference_Number"] = $sampleCode;
				if($country == 'UK'){
					$data["Article_Number"] = $articlecode;
					$countryCode = 'UK';
				}elseif($country == 'Denmark'){
					$data["Article_Number"] = $articlecode.'DK';
				}elseif($country == 'France'){
					$data["Article_Number"] = $articlecode.'F';
				}elseif($country == 'Germany'){
					$data["Article_Number"] = $articlecode.'D';
				}elseif($country == 'Italy'){
					$data["Article_Number"] = $articlecode.'I';
				}elseif($country == 'Netherlands'){
					$data["Article_Number"] = $articlecode;
				}elseif($country == 'Norway'){
					$data["Article_Number"] = $articlecode.'No';
				}elseif($country == 'Spain'){
					$data["Article_Number"] = $articlecode.'E';
				}elseif($country == 'Sweden'){
					$data["Article_Number"] = $articlecode.'S';
				}else{
					$data["Article_Number"] = $articlecode;
				}
				$data["Amount"] = 1;
				//$data["Order is to be sent to"] = $order_send_to;
				$data_details[] = $data;
			}
		}
		$this->response($data_details, PAX_API_Controller::HTTP_OK);
	}

    public function index_post($order_number){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$order_number = $this->uri->segment('4');
		}
		$orderNumber = $order_number;

		$resData = $this->input->post();
		$input = $this->input->post('status');
		$exactorderNumber = !empty($this->input->post('ExactOrdernumber'))?$this->input->post('ExactOrdernumber'):'';
		if(!isset($input)){
			$data = file_get_contents('php://input');
			$data = json_decode($data,true);
			$resData = $data;
			$input = $data['status'];
			$exactorderNumber = !empty($data['ExactOrdernumber'])?$data['ExactOrdernumber']:'';
		}

		/* Exact dev pass below status
		6 = Order Completed
		1 = Error on creation */

		$updtData = [];
		$this->db->select('id,batch_number');
		$this->db->from('ci_orders');
		$this->db->where('order_number', $orderNumber);
		$this->db->where('is_draft', '0');
		$res = $this->db->get();
		if($res->num_rows() == 0 ){
			$this->response(['Order number is not found.'], PAX_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
		}elseif($input != '6' && $input != '1'){
			$this->response(['Please enter a valid status.'], PAX_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
		}else{
			$responce = $res->row();
			$orderID = $responce->id;
			$existbatchNumber = $responce->batch_number;
			if($input == '6'){
				$orderData['text'] = 'Invoiced';
				$updtData['is_confirmed'] = '0';
				$updtData['is_invoiced'] = '1';
			}else{
				$orderData['text'] = 'Error on creation';
				$updtData['is_confirmed'] = '6';
			}

			$updtData['send_Exact'] = 1;
			if(!empty($exactorderNumber)){
				$updtData['exact_order_number'] = $exactorderNumber;
				if($input == '1'){
					if(!empty($existbatchNumber)){
						$updtData['batch_number'] = $existbatchNumber.' & '.$exactorderNumber;
					}else{
						$updtData['batch_number'] = $exactorderNumber;
					}
				}
			}
			$this->db->update('ci_orders', $updtData, array('id'=>$orderID));

			$orderData['order_id'] = $orderID;
			$orderData['created_by'] = '0';
			$orderData['created_at'] = date("Y-m-d H:i:s");
			$this->db->insert('ci_order_history', $orderData);

			$this->response(['Order status has been updated successfully.'], PAX_API_Controller::HTTP_OK);
		}
    }

}
?>