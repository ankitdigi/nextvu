<?php
require APPPATH . '/libraries/NL_API_Controller.php';

class NLItems extends NL_API_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->_table = 'ci_orders';
		$this->load->model('AllergensModel');
		$this->load->model('UsersDetailsModel');
		error_reporting(0);
    }

	public function order_details(){
		$this->db->select('ci_orders.id, ci_orders.order_number, ci_orders.lab_id, ci_orders.vet_user_id, ci_orders.is_confirmed, practice.name AS practice_first_name, practice.last_name AS practice_last_name, lab.name AS lab_name');
		$this->db->from($this->_table);
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		$this->db->where('ci_orders.is_confirmed', '1');
		$this->db->where('ci_orders.batch_number', '');
		$this->db->where('ci_orders.is_draft', '0');
		$this->db->where('ci_orders.order_type', '1');
		$datas = $this->db->get()->result_array();
		$dataDetails = []; $datao = [];
        foreach($datas as $data_detail){
			$datao["order_id"] = $data_detail['id'];
			if ($data_detail['lab_id'] > 0) {
				$datao["practice_name"] = $data_detail['lab_name'];
			} elseif ($data_detail['vet_user_id'] > 0) {
				$datao["practice_name"] = $data_detail['practice_first_name'].' '.$data_detail['practice_last_name'];
			} else {
				$datao["practice_name"] = '';
			}
			$datao["status"] = 'Confirmed';

			$dataDetails[] = $datao;
		}
        $this->response($dataDetails, NL_API_Controller::HTTP_OK);
    }

	public function index_get($id = 0){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$id = $this->uri->segment('4');
		}

		if(!empty($id)){
			$this->db->select('ci_orders.id, ci_orders.order_number,ci_orders.allergens,ci_orders.vet_user_id,ci_orders.lab_id,ci_orders.order_can_send_to,ci_orders.delivery_practice_id,ci_orders.reference_number,ci_orders.shipping_cost,petOwner.name AS pet_owner_name,petOwner.last_name AS pet_owner_lname,ci_pets.name AS pet_name,ci_pets.type,practice.country');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.id', $id);
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$this->db->where('ci_orders.order_type', '1');
			$datas = $this->db->get()->result_array();
		}else{
			$this->db->select('ci_orders.id, ci_orders.order_number,ci_orders.allergens,ci_orders.vet_user_id,ci_orders.lab_id,ci_orders.order_can_send_to,ci_orders.delivery_practice_id,ci_orders.reference_number,ci_orders.shipping_cost,petOwner.name AS pet_owner_name,petOwner.last_name AS pet_owner_lname,ci_pets.name AS pet_name,ci_pets.type,practice.country');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.batch_number', '');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$this->db->where('ci_orders.order_number >', '36805');
			$this->db->where('ci_orders.order_type', '1');
			$datas = $this->db->get()->result_array();
		}

		$data = []; $numericArr = []; $alphaArr = [];
		$data_details = $userData = $usersDetails =$column_field = [];
		$allergens = [];
		$allergen_name = $order_send_to = $add_1 = $add_2 = $add_3 = $add_4 = $address_2 = $odelivery_address = $allergensCode =''; $ownerName =''; $userArr = array();  $sendId = 0; $freightCost = '0';
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
				if($data_detail['type']==1){
					$AnimalType = "cat";
				}elseif($data_detail['type']==2){
					$AnimalType = "dog";
				}elseif($data_detail['type']==3){
					$AnimalType = "horse";
				}

				//allergen details
				$allergens = $this->AllergensModel->orderAPI_allergens($data_detail['allergens']);
				$allergen_name = ( !empty($allergens) ) ? $allergens['name'] : "";

				$order_send_to = ''; $deliverTo = ''; $usrID = 0;
				if ($data_detail['order_can_send_to'] == '1') {
					$userData = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'add_1', 'add_2', 'add_3', 'add_4', 'address_2', 'address_3', 'account_ref'");
					$usersDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
					$usersDetails = array_column($usersDetails, 'column_field', 'column_name');
					$address_1 = !empty($usersDetails['add_1']) ? $usersDetails['add_1'] : '';
					$address_2 = !empty($usersDetails['add_2']) ? $usersDetails['add_2'] : '';
					$address_3 = !empty($usersDetails['add_3']) ? $usersDetails['add_3'] : '';
					$address_4 = !empty($usersDetails['add_4']) ? $usersDetails['add_4'] : '';
					$town = !empty($usersDetails['address_2']) ? $usersDetails['address_2'] : '';
					$postcode = !empty($usersDetails['address_3']) ? $usersDetails['address_3'] : '';
					$deliverTo = !empty($usersDetails['account_ref']) ? $usersDetails['account_ref'] : '';
					$order_send_to = $address_1.' '.$address_2.' '.$address_3.' '.$address_4.' '.$town.' '.$postcode;
					$usrID = $data_detail['delivery_practice_id'];
				}else if($data_detail['order_can_send_to'] == '0'){
					if($data_detail['lab_id'] > 0){
						$userData = array("user_id" => $data_detail['lab_id'], "column_name" => "'address_1','address_2','address_3','address_4','post_code', 'town_city', 'account_ref'");
						$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
						$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
						$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
						$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
						$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
						$address_4 = !empty($LabDetails['address_4']) ? $LabDetails['address_4'] : '';
						$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
						$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
						$deliverTo = !empty($LabDetails['account_ref']) ? $LabDetails['account_ref'] : '';
						$order_send_to = $address_1." ".$address_2." ".$address_3." ".$address_4." ".$town." ".$postcode;
						$usrID = $data_detail['lab_id'];
					}else{
						$usrID = $data_detail['vet_user_id'];
						$userData = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'add_1', 'add_2', 'add_3', 'add_4', 'address_2', 'address_3', 'account_ref'");
						$usersDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
						$usersDetails = array_column($usersDetails, 'column_field', 'column_name');
						$address_1 = !empty($usersDetails['add_1']) ? $usersDetails['add_1'] : '';
						$address_2 = !empty($usersDetails['add_2']) ? $usersDetails['add_2'] : '';
						$address_3 = !empty($usersDetails['add_3']) ? $usersDetails['add_3'] : '';
						$address_4 = !empty($usersDetails['add_4']) ? $usersDetails['add_4'] : '';
						$town = !empty($usersDetails['address_2']) ? $usersDetails['address_2'] : '';
						$postcode = !empty($usersDetails['address_3']) ? $usersDetails['address_3'] : '';
						$deliverTo = !empty($usersDetails['account_ref']) ? $usersDetails['account_ref'] : '';
						$order_send_to = $address_1.' '.$address_2.' '.$address_3.' '.$address_4.' '.$town.' '.$postcode;
					}
				}

				$account_ref = ''; $order_by = ''; $invoice_to_practice = 0;
				if($data_detail['lab_id'] > 0){
					$userimData = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref','invoice_to_practice_immu'");
					$refimDetails = $this->UsersDetailsModel->getColumnFieldArray($userimData);
					$refimDetails = array_column($refimDetails, 'column_field', 'column_name');
					$order_by = !empty($refimDetails['account_ref']) ? $refimDetails['account_ref'] : '';
					$invoice_to_practice = !empty($refimDetails['invoice_to_practice_immu']) ? $refimDetails['invoice_to_practice_immu'] : '0';

					if($data_detail['order_can_send_to'] == '1'){
						$userData2 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref','vat_applicable'");
						$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
						$refDatas = array_column($refDatas, 'column_field', 'column_name');
						$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
						$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
						$sendId = $data_detail['delivery_practice_id'];
					}elseif($data_detail['order_can_send_to'] == '0' && $data_detail['vet_user_id'] > 0 && ($data_detail['lab_id'] == '108' || $data_detail['lab_id'] == '13788')){
						$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref','vat_applicable'");
						$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
						$refDatas = array_column($refDatas, 'column_field', 'column_name');
						$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
						$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
						$sendId = $data_detail['vet_user_id'];
					}else{
						$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref','vat_applicable'");
						$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
						$refDetails = array_column($refDetails, 'column_field', 'column_name');
						$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
						$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
						$sendId = $data_detail['lab_id'];
					}
				}else{
					$userimData = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
					$refimDetails = $this->UsersDetailsModel->getColumnFieldArray($userimData);
					$refimDetails = array_column($refimDetails, 'column_field', 'column_name');
					$order_by = !empty($refimDetails['account_ref']) ? $refimDetails['account_ref'] : '';
					if($data_detail['order_can_send_to'] == '1'){
						$userData2 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref','vat_applicable'");
						$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
						$refDatas = array_column($refDatas, 'column_field', 'column_name');
						$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
						$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
						$sendId = $data_detail['delivery_practice_id'];
					}else{
						$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref','vat_applicable'");
						$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
						$refDatas = array_column($refDatas, 'column_field', 'column_name');
						$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
						$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
						$sendId = $data_detail['vet_user_id'];
					}
				}

				if(!in_array($sendId, $userArr)){
					$userArr[] = $sendId;
					$freightCost = '20';
				}else{
					$freightCost = '0';
				}

				$sqlc = "SELECT u.country, c.name FROM `ci_users` as u LEFT JOIN `ci_staff_countries` as c ON u.country = c.id WHERE u.id = '".$usrID."'";
				$responc = $this->db->query($sqlc);
				$cuntyquery = $responc->row();
				$country = !empty($cuntyquery->name)?$cuntyquery->name:'UK';

				$company = '';
				if($data_detail['pet_owner_name'] == NULL && $data_detail['pet_owner_lname'] == NULL){
					$company = '';
				}else{
					if($data_detail['pet_owner_name'] == NULL || preg_replace('/\s+/', '', $data_detail['pet_owner_name']) == ""){
						$company = preg_replace('/\s+/', '', $data_detail['pet_owner_lname']);
					}else{
						$company = preg_replace('/\s+/', '', $data_detail['pet_owner_name']) .' '. preg_replace('/\s+/', '', $data_detail['pet_owner_lname']);
					}
				}

				$allergenArr = explode("|@|",$allergen_name);
				$total_allergen = count($allergenArr);
				if ($total_allergen <= 4) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; 
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '7'.implode("",$alphaArr).implode("",$numericArr);
					$data["UniqueIdentifier"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']);
					$data["Pet_Owner"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']).' '.$company;
					$data["Country"] = $country;
					$data["Orderby"] = $account_ref;
					if($invoice_to_practice > 0){
						$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
						$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
						$refDatap = array_column($refDatap, 'column_field', 'column_name');
						$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
						$data["Invoice_To"] = $accountRef;
					}else{
						$data["Invoice_To"] = $order_by;
					}
					$data["Deliver_To"] = $deliverTo;
					if($country == "UK" || $account_ref == '113898'){
						$data["Freight_Cost"] = "0";
					}else{
						$data["Freight_Cost"] = $freightCost;
					}
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Order is to be sent to"] = $order_send_to;
					//$data["Invoice to be sent to"] = "The clinic adress above";
					$data_details[] = $data;
				} elseif ($total_allergen > 4 && $total_allergen <= 8) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; 
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '8'.implode("",$alphaArr).implode("",$numericArr);
					$data["UniqueIdentifier"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']);
					$data["Pet_Owner"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']).' '.$company;
					$data["Country"] = $country;
					$data["Orderby"] = $account_ref;
					if($invoice_to_practice > 0){
						$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
						$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
						$refDatap = array_column($refDatap, 'column_field', 'column_name');
						$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
						$data["Invoice_To"] = $accountRef;
					}else{
						$data["Invoice_To"] = $order_by;
					}
					$data["Deliver_To"] = $deliverTo;
					if($country == "UK" || $account_ref == '113898'){
						$data["Freight_Cost"] = "0";
					}else{
						$data["Freight_Cost"] = $freightCost;
					}
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Order is to be sent to"] = $order_send_to;
					//$data["Invoice to be sent to"] = "The clinic adress above";
					$data_details[] = $data;
				} elseif ($total_allergen > 8) {
					$totalVialsdb = $this->AllergensModel->Totalvials($data_detail['id']);
					if($totalVialsdb > 0){
						$codes1Arr = array();
						for ($x = 1; $x <= $totalVialsdb; $x++) {
							$vialsList = $this->AllergensModel->getVialslist($x,$data_detail['id']);
							if(!empty($vialsList['allergens'])){
								$allergensArr = $this->AllergensModel->getAllergensCodeByID($vialsList['allergens']);
							}else{
								$allergensArr = array();
							}
							$numericArr = array();
							foreach($allergensArr as $aleCode){
								if(is_numeric($aleCode['code'])){
									$numericArr[] = $aleCode['code'];
								}
							}
							$numericArr = array_unique($numericArr);
							asort($numericArr);

							$alphaArr = array();
							foreach($allergensArr as $aleCode){
								if(!is_numeric($aleCode['code'])){
									$alphaArr[] = $aleCode['code'];
								}
							}
							$alphaArr = array_unique($alphaArr);
							sort($alphaArr);

							$codes1Arr = array_merge($alphaArr,$numericArr);

							$data["UniqueIdentifier"] = $data_detail['order_number'].'V'.$x;
							$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
							$data["AnimalType"] = $AnimalType;
							$data["OwnerName"] = $company;
							$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']);
							$data["Pet_Owner"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']).$x.'of'.$totalVialsdb.' '.$company;
							$data["Country"] = $country;
							$data["Orderby"] = $account_ref;
							if($invoice_to_practice > 0){
								$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
								$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
								$refDatap = array_column($refDatap, 'column_field', 'column_name');
								$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
								$data["Invoice_To"] = $accountRef;
							}else{
								$data["Invoice_To"] = $order_by;
							}
							$data["Deliver_To"] = $deliverTo;
							if($x ==1){
								if($country == "UK" || $account_ref == '113898'){
								$data["Freight_Cost"] = "0";
								}else{
								$data["Freight_Cost"] = $freightCost;
								}
							}else{
							$data["Freight_Cost"] = "0";
							}
							if(count($allergensArr) == 8){
							$data["Itemcode"] = '8'.implode("",$codes1Arr);
							}else{
								if(count($allergensArr) <= 4){
								$data["Itemcode"] = '7'.implode("",$codes1Arr);
								}else{
								$data["Itemcode"] = '8'.implode("",$codes1Arr);
								}
							}
							$data["Vialnumber"] = $x;
							$data["Order is to be sent to"] = $order_send_to;
							//$data["Invoice to be sent to"] = "The clinic adress above";
							$data_details[] = $data;
						}
					}else{
						$quotient = ($total_allergen/8);
						$totalVials = ((round)($quotient));
						$demimal = $quotient-$totalVials;
						if($demimal > 0){
							$totalVials = $totalVials+1;
						}

						$allergensCode = ''; $numericArr = array();
						foreach($allergenArr as $key=>$aleName){
							$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
							if(is_numeric($allergensCode)){
								$numericArr[] = $allergensCode;
							}
						}
						$numericArr = array_unique($numericArr);
						asort($numericArr);

						$allergensCode = ''; $alphaArr = array();
						foreach($allergenArr as $key=>$aleName){
							$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
							if(!is_numeric($allergensCode)){
								$alphaArr[] = $allergensCode;
							}
						}
						$alphaArr = array_unique($alphaArr);
						sort($alphaArr);

						$testArr = array_merge($alphaArr,$numericArr);
						$a=1; $b = 1; $testedArr = array();
						foreach($testArr as $key=>$value){
							if($a == 9){
								$b++;
								$a=1;
							}
							$testedArr[$b][] = $value;
							$a++;
						}
						$allergeName = $testedArr;
						for ($x = 1; $x <= $totalVials; $x++) {
							$data["UniqueIdentifier"] = $data_detail['order_number'].'V'.$x;
							$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
							$data["AnimalType"] = $AnimalType;
							$data["OwnerName"] = $company;
							$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']);
							$data["Pet_Owner"] = ($data_detail['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $data_detail['pet_name']).$x.'of'.$totalVials.' '.$company;
							$data["Country"] = $country;
							$data["Orderby"] = $account_ref;
							if($invoice_to_practice > 0){
								$userData3 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref'");
								$refDatap = $this->UsersDetailsModel->getColumnFieldArray($userData3);
								$refDatap = array_column($refDatap, 'column_field', 'column_name');
								$accountRef = !empty($refDatap['account_ref']) ? $refDatap['account_ref'] : '';
								$data["Invoice_To"] = $accountRef;
							}else{
								$data["Invoice_To"] = $order_by;
							}
							$data["Deliver_To"] = $deliverTo;
							if($x ==1){
								if($country == "UK" || $account_ref == '113898'){
								$data["Freight_Cost"] = "0";
								}else{
								$data["Freight_Cost"] = $freightCost;
								}
							}else{
							$data["Freight_Cost"] = "0";
							}
							if(count($allergeName[$x]) == 8){
							$data["Itemcode"] = '8'.implode("",$allergeName[$x]);
							}else{
								if(count($allergeName[$x]) <= 4){
								$data["Itemcode"] = '7'.implode("",$allergeName[$x]);
								}else{
								$data["Itemcode"] = '8'.implode("",$allergeName[$x]);
								}
							}
							$data["Vialnumber"] = $x;
							$data["Order is to be sent to"] = $order_send_to;
							//$data["Invoice to be sent to"] = "The clinic adress above";
							$data_details[] = $data;
						}
					}
				}
			}
		}
		$this->response($data_details, NL_API_Controller::HTTP_OK);
	}

    public function index_post($order_number){
		if($this->uri->segment('3') == 'exact_order_number'){
			if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
				$order_number = $this->uri->segment('4');
			}
			if (strpos($order_number, 'V') !== false) {
				$chunk = explode("V",$order_number);
				$orderNumber = $chunk[0];
			}else{
				$orderNumber = $order_number;
			}
			$exactorderNumber = !empty($this->input->post('ExactOrdernr'))?$this->input->post('ExactOrdernr'):'';
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('order_number', $orderNumber);
			$this->db->where('is_draft', '0');
			$res = $this->db->get();
			if($res->num_rows() == 0 ){
				$this->response(['Order number is not found.'], NL_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}else{
				$orderID = $res->row()->id;
				if(!empty($exactorderNumber)){
				$this->db->update('ci_orders', array('exact_order_number'=>$exactorderNumber), array('id'=>$orderID));
				}
				$orderData['text'] = 'Update Exact Order Number';
				$orderData['order_id'] = $orderID;
				$orderData['created_by'] = '0';
				$orderData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderData);

				$this->response(['Exact Order Number has been updated successfully.'], NL_API_Controller::HTTP_OK);
			}
		}else{
			if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
				$order_number = $this->uri->segment('4');
			}
			if (strpos($order_number, 'V') !== false) {
				$chunk = explode("V",$order_number);
				$orderNumber = $chunk[0];
			}else{
				$orderNumber = $order_number;
			}

			$resData = $this->input->post();
			$input = $this->input->post('status');
			$batchNumber = !empty($this->input->post('batch_number'))?$this->input->post('batch_number'):'';
			$exactorderNumber = !empty($this->input->post('ExactOrdernr'))?$this->input->post('ExactOrdernr'):'';
			if(!isset($input)){
				$data = file_get_contents('php://input');
				$data = json_decode($data,true);
				$resData = $data;
				$input = $data['status'];
				$batchNumber = !empty($data['batch_number'])?$data['batch_number']:'';
				$exactorderNumber = !empty($data['ExactOrdernr'])?$data['ExactOrdernr']:'';
			}

			/* Exact dev pass below status
			0 = Sent to Netherlands
			1 = Confirmed
			2 = In process
			3 = Shipped (and final status)
			4 = Error on creation */

			$updtData = [];
			$this->db->select('id,batch_number');
			$this->db->from('ci_orders');
			$this->db->where('order_number', $orderNumber);
			$this->db->where('is_draft', '0');
			$res = $this->db->get();
			if($res->num_rows() == 0 ){
				$this->response(['Order number is not found.'], NL_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($input != '0' && $input != '1' && $input != '2' && $input != '3' && $input !='4'){
				$this->response(['Please enter a valid status.'], NL_API_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}else{
				$responce = $res->row();
				$orderID = $responce->id;
				$existbatchNumber = $responce->batch_number;
				if($input == '0'){
					$orderData['text'] = 'Sent to Netherlands';
					$updtData['is_confirmed'] = '7';
				}elseif($input == '2'){
					$orderData['text'] = 'In process';
					$updtData['is_confirmed'] = '5';
				}elseif($input == '3'){
					$orderData['text'] = 'Shipped';
					$updtData['is_confirmed'] = '4';
					$updtData['shipping_date'] = date("Y-m-d");
				}elseif($input == '4'){
					$orderData['text'] = 'Error on creation';
					$updtData['is_confirmed'] = '6';
				}else{
					$orderData['text'] = 'Confirmed';
					$updtData['is_confirmed'] = '1';
				}

				$updtData['send_Exact'] = 1;
				if(!empty($batchNumber)){
					if(!empty($existbatchNumber)){
						$updtData['batch_number'] = $existbatchNumber.' & '.$batchNumber;
					}else{
						$updtData['batch_number'] = $batchNumber;
					}
				}
				if(!empty($exactorderNumber)){
					$updtData['exact_order_number'] = $exactorderNumber;
					if($input == '3'){
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

				$this->response(['Order status has been updated successfully.'], NL_API_Controller::HTTP_OK);
			}
		}
    }

}
?>