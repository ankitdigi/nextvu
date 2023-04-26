<?php
require APPPATH . '/libraries/IDEXX_API_Controller.php';
class Idexx extends IDEXX_API_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->_table = 'ci_orders';
		$this->load->model('AllergensModel');
		$this->load->model('UsersDetailsModel');
		error_reporting(1);
    }

	public function index_post(){
		if($this->uri->segment('3') == 'order_details'){
			$post = $this->input->post();
			if(empty($post)){
				$post = file_get_contents('php://input');
				$post = json_decode($post,true);
			}
			$orderNumber = !empty($post['Nextmune_Order_Number'])?$post['Nextmune_Order_Number']:'';
			$orderNumbers = !empty($post['Order_Numbers'])?$post['Order_Numbers']:'';
			$orderStatus = !empty($post['Order_status'])?$post['Order_status']:'';
			$dateFrom = !empty($post['Order_date_from'])?$post['Order_date_from']:'';
			$dateTo = !empty($post['Order_date_to'])?$post['Order_date_to']:'';
			$Sap_ID = !empty($post['Sap_ID'])?$post['Sap_ID']:'';
			$caseID = !empty($post['Case_ID'])?$post['Case_ID']:'';
			$practice_lab = '24083';
			$this->db->select('ci_orders.id, ci_orders.order_number, ci_orders.allergens, ci_orders.vet_user_id, ci_orders.lab_id, ci_orders.order_can_send_to, ci_orders.delivery_practice_id, ci_orders.reference_number, ci_orders.shipping_cost, ci_orders.unit_price, ci_orders.order_discount, ci_orders.case_ID, ci_orders.sap_lims, ci_orders.batch_number, ci_orders.order_date, ci_orders.is_confirmed, ci_orders.shipping_date, ci_orders.track_number, ci_orders.order_type, ci_orders.is_invoiced, petOwner.name AS pet_owner_name, petOwner.last_name AS pet_owner_lname, ci_pets.name AS pet_name, ci_pets.type,practice.country');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.is_draft', '0');
			//$this->db->where('ci_orders.lab_id', $practice_lab);
			if(!empty($orderNumber)){
			$this->db->where('ci_orders.order_number', $orderNumber);
			}
			if(!empty($orderNumbers)){
			$this->db->where('ci_orders.order_number IN('.$orderNumber.')');
			}
			if(!empty($orderStatus)){
			$this->db->where('ci_orders.is_confirmed', $orderStatus);
			}
			if(!empty($dateFrom) && !empty($dateTo)){
			$this->db->where('(ci_orders.order_date BETWEEN "'.$dateFrom.'" AND "'.$dateTo.'")');
			}elseif(!empty($dateFrom) && empty($dateTo)){
			$this->db->where('ci_orders.order_date', $dateFrom);
			}
			if(!empty($Sap_ID)){
			$this->db->where('ci_orders.sap_lims', $Sap_ID);
			}
			if(!empty($caseID)){
			$this->db->where('ci_orders.case_ID LIKE', $caseID);
			}
			$datas = $this->db->get()->result_array();
			$data = []; $numericArr = []; $alphaArr = [];
			$dataDetails = $userData = $usersDetails =$column_field = [];
			$allergens = [];
			$allergen_name = $order_send_to = $add_1 = $add_2 = $add_3 = $add_4 = $address_2 = $odelivery_address = $allergensCode = $is_status = $ownerName =''; $userArr = array();
			foreach($datas as $data_detail){
				$AnimalType = "";
				if($data_detail['type']==1){
					$AnimalType = "Cat";
				}elseif($data_detail['type']==2){
					$AnimalType = "Dog";
				}elseif($data_detail['type']==3){
					$AnimalType = "Horse";
				}

				if($data_detail['is_confirmed'] == 0){
					$is_status = "New Order";
				}elseif($data_detail['is_confirmed'] == 1){
					$is_status = "Order Confirmed";
				}elseif($data_detail['is_confirmed'] == 2){
					$is_status = "On Hold";
				}elseif($data_detail['is_confirmed'] == 3){
					$is_status = "Cancelled";
				}elseif($data_detail['is_confirmed'] == 4){
					$is_status = "Shipped";
				}elseif($data_detail['is_confirmed'] == 5){
					$is_status = "In Process";
				}elseif($data_detail['is_confirmed'] == 6){
					$is_status = "Error at Exact";
				}elseif($data_detail['is_confirmed'] == 7){
					$is_status = "Order Confirmed";
				}elseif($data_detail['is_confirmed'] == 8){
					$is_status = "Sent to Nextmune";
				}elseif($data_detail['is_invoiced'] == 1){
					$is_status = "Invoiced";
				}

				//allergen details
				$allergens = $this->AllergensModel->orderAPI_allergens($data_detail['allergens']);
				$allergen_name = ( !empty($allergens) ) ? $allergens['name'] : "";

				$order_send_to = ''; $usrID = 0;
				if ($data_detail['order_can_send_to'] == '1') {
					$userData = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'add_1','add_2','add_3','add_4','address_2','address_3','account_ref'");
					$usersDetails = $this->UsersDetailsModel->getColumnField($userData);
					$column_field = explode('|', $usersDetails['column_field']);
					$address_1 = isset($column_field[0]) ? $column_field[0] : '';
					$address_2 = isset($column_field[1]) ? $column_field[1] : '';
					$address_3 = isset($column_field[2]) ? $column_field[2] : '';
					$address_4 = isset($column_field[3]) ? $column_field[3] : '';
					$town = isset($column_field[4]) ? $column_field[4] : '';
					$postcode = isset($column_field[5]) ? $column_field[5] : '';
					$order_send_to = $address_1.' '.$address_2.' '.$address_3.' '.$address_4.' '.$town.' '.$postcode;

					$userData1 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref'");
					$usersDetails1 = $this->UsersDetailsModel->getColumnField($userData1);
					$column_fields = explode('|', $usersDetails1['column_field']);
					$usrID = $data_detail['delivery_practice_id'];
				}else if($data_detail['order_can_send_to'] == '0'){
					if($data_detail['lab_id'] > 0){
						$userData = array("user_id" => $data_detail['lab_id'], "column_name" => "'address_1','address_2','address_3','post_code', 'town_city', 'account_ref'");
						$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
						$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
						$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
						$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
						$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
						$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
						$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
						$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						$usrID = $data_detail['lab_id'];
					}else{
						$usrID = $data_detail['vet_user_id'];
						$brances = $this->AllergensModel->getBranchdetailsById($data_detail['vet_user_id']);
						if(!empty($brances)){
							$address_1 = !empty($brances->address) ? $brances->address : '';
							$address_2 = !empty($brances->address1) ? $brances->address1 : '';
							$address_3 = !empty($brances->address2) ? $brances->address2 : '';
							$town = !empty($brances->town_city) ? $brances->town_city : '';
							$postcode = !empty($brances->postcode) ? $brances->postcode : '';
							$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						}else{
							$userData = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
							$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
							$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
							$address_1 = !empty($LabDetails['add_1']) ? $LabDetails['add_1'] : '';
							$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
							$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
							$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
							$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
							$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						}
					}
				}

				$account_ref = '';
				if($data_detail['lab_id'] > 0){
					$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref','vat_applicable'");
					$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
				}else{
					$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref','vat_applicable'");
					$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
					if($account_ref == ''){
						$this->db->select('id, name, address, address1, address2, address3, town_city, country, postcode, number, customer_number');
						$this->db->from('ci_branches');
						$this->db->where('vet_user_id',$data_detail['vet_user_id']);
						$brances = $this->db->get()->row();
						if(!empty($brances)){
							$account_ref = !empty($brances->customer_number) ? $brances->customer_number : '';
						}
					}
				}

				$this->db->select('country');
				$this->db->from('ci_users');
				$this->db->where('id',$usrID);
				$usrquery = $this->db->get()->row()->country;
				if($usrquery == 1){
					$country = 'UK';
				}else{
					$country = 'Ireland';
				}

				if(!in_array($usrID, $userArr)){
					$userArr[] = $usrID;
					$freightCost = '20';
				}else{
					$freightCost = '0';
				}

				$company = '';
				if($data_detail['pet_owner_name'] == NULL && $data_detail['pet_owner_lname'] == NULL){
					$company = '';
				}else{
					if($data_detail['pet_owner_name'] == NULL){
						$company = $data_detail['pet_owner_lname'];
					}else{
						$company = $data_detail['pet_owner_name'].' '.$data_detail['pet_owner_lname'];
					}
				}

				$allergenArr = explode("|@|",$allergen_name);
				$total_allergen = count($allergenArr);
				if ($total_allergen <= 4) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; $allergensNameArr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
						$allergensNameArr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '7'.implode("",$alphaArr).implode("",$numericArr);
					$data["Nextmune_Order_Number"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["Orderby"] = $account_ref;
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
					$data["Country"] = $country;
					$data["Unit_Price"] = $data_detail['unit_price'];
					$data["Order_Discount"] = $data_detail['order_discount'];
					$data["Freight_Cost"] = $freightCost;
					$data["Allergens"] = json_encode($allergensNameArr);
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Total_Vial"] = '1';
					$data["Order is to be sent to"] = $order_send_to;
					$data["Case_ID"] = $data_detail['case_ID'];
					$data["SAP_ID"] = $data_detail['sap_lims'];
					$data["BILL_TO_SAP"] = '';
					$data["BILL_FROM_SAP"] = '';
					$data["Batch_Number"] = $data_detail['batch_number'];
					$data["Order_date"] = $data_detail['order_date'];
					$data["Order_Status"] = $is_status;
					$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
					if($data_detail['track_number']!=""){
					$data["Tracking_number_Courier"] = $data_detail['track_number'];
					}else{
					$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
					}
					$dataDetails[] = $data;
				} elseif ($total_allergen > 4 && $total_allergen <= 8) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; $allergensNameArr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
						$allergensNameArr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '8'.implode("",$alphaArr).implode("",$numericArr);
					$data["Nextmune_Order_Number"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["Orderby"] = $account_ref;
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
					$data["Country"] = $country;
					$data["Unit_Price"] = $data_detail['unit_price'];
					$data["Order_Discount"] = $data_detail['order_discount'];
					$data["Freight_Cost"] = $freightCost;
					$data["Allergens"] = json_encode($allergensNameArr);
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Total_Vial"] = '1';
					$data["Order is to be sent to"] = $order_send_to;
					$data["Case_ID"] = $data_detail['case_ID'];
					$data["SAP_ID"] = $data_detail['sap_lims'];
					$data["BILL_TO_SAP"] = '';
					$data["BILL_FROM_SAP"] = '';
					$data["Batch_Number"] = $data_detail['batch_number'];
					$data["Order_date"] = $data_detail['order_date'];
					$data["Order_Status"] = $is_status;
					$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
					if($data_detail['track_number']!=""){
					$data["Tracking_number_Courier"] = $data_detail['track_number'];
					}else{
					$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
					}
					$dataDetails[] = $data;
				} elseif ($total_allergen > 8) {
					$quotient = ($total_allergen/8);
					$totalVials = ((round)($quotient));
					$demimal = $quotient-$totalVials;
					if($demimal > 0){
						$totalVials = $totalVials+1;
					}

					$allergensCode = ''; $numericArr = array(); $allergensName1Arr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}
						$allergensName1Arr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					asort($numericArr);

					$allergensCode = ''; $alphaArr = array(); $allergensName2Arr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(!is_numeric($allergensCode)){
							$alphaArr[] = $allergensCode;
						}
						$allergensName2Arr[] = $aleName;
					}
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);

					$testArr = array_merge($alphaArr,$numericArr);
					$$allergensNameArr = array_merge($allergensName1Arr,$allergensName2Arr);
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
						$data["Nextmune_Order_Number"] = $data_detail['order_number'].'V'.$x;
						$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
						$data["Orderby"] = $account_ref;
						$data["AnimalType"] = $AnimalType;
						$data["OwnerName"] = $company;
						$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
						$data["Country"] = $country;
						$data["Unit_Price"] = $data_detail['unit_price'];
						$data["Order_Discount"] = $data_detail['order_discount'];
						if($x ==1){
						$data["Freight_Cost"] = $freightCost;
						}else{
						$data["Freight_Cost"] = "0";
						}
						$data["Allergens"] = json_encode($allergensNameArr);
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
						$data["Total_Vial"] = $totalVials;
						$data["Order is to be sent to"] = $order_send_to;
						$data["Case_ID"] = $data_detail['case_ID'];
						$data["SAP_ID"] = $data_detail['sap_lims'];
						$data["BILL_TO_SAP"] = '';
						$data["BILL_FROM_SAP"] = '';
						$data["Batch_Number"] = $data_detail['batch_number'];
						$data["Order_date"] = $data_detail['order_date'];
						$data["Order_Status"] = $is_status;
						$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
						if($data_detail['track_number']!=""){
						$data["Tracking_number_Courier"] = $data_detail['track_number'];
						}else{
						$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
						}
						$dataDetails[] = $data;
					}
				}
			}
			$this->response($dataDetails, IDEXX_API_Controller::HTTP_OK);
		}elseif($this->uri->segment('3') == 'update_order'){
			$data = $this->input->post();
			if(empty($data)){
				$data = file_get_contents('php://input');
				$data = json_decode($data,true);
			}
			$input = $data['status'];
			$order_number = $data['Order_Number'];
			if (strpos($order_number, 'V') !== false) {
				$chunk = explode("V",$order_number);
				$orderNumber = $chunk[0];
			}else{
				$orderNumber = $order_number;
			}
			/* Exact dev pass below status
			1 = Confirmed
			2 = Hold
			3 = Cancel
			4 = Error on creation
			5 = In Process */

			$updtData = [];
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('order_number', $orderNumber);
			$this->db->where('is_draft', '0');
			$res = $this->db->get();
			if($res->num_rows() == 0 ){
				$this->response(['Order number is not found.'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($input != '1' && $input != '2' && $input != '3' && $input !='4' && $input !='5'){
				$this->response(['Please enter a valid status.'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}else{
				$orderID = $res->row()->id;
				if($input == '1'){
					$orderData['text'] = 'Confirmed';
					$updtData['is_confirmed'] = '1';
				}elseif($input == '2'){
					$orderData['text'] = 'Hold';
					$updtData['is_confirmed'] = '2';
				}elseif($input == '3'){
					$orderData['text'] = 'Cancel';
					$updtData['is_confirmed'] = '3';
				}elseif($input == '4'){
					$orderData['text'] = 'Error on creation';
					$updtData['is_confirmed'] = '6';
				}elseif($input == '5'){
					$orderData['text'] = 'In Process';
					$updtData['is_confirmed'] = '5';
				}
				$this->db->update('ci_orders', $updtData, array('order_number'=>$orderNumber));

				$orderData['order_id'] = $orderID;
				$orderData['created_by'] = '0';
				$orderData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderData);

				$this->response(['Order status has been updated successfully.'], REST_Controller::HTTP_OK);
			}
		}else{
			$data = $this->input->post();
			if(empty($data)){
				$data = file_get_contents('php://input');
				$data = json_decode($data,true);
			}

			if((isset($data['List_of_allergens']) && $data['List_of_allergens'] != "") && (isset($data['Sap_ID']) && $data['Sap_ID'] != "") && (isset($data['Customer_post_code']) && $data['Customer_post_code'] != "") && (isset($data['Order_date']) && $data['Order_date'] != "") && (isset($data['Species']) && $data['Species'] != "") && (isset($data['Patient_name']) && $data['Patient_name'] != "") && (isset($data['Owner_name']) && $data['Owner_name'] != "")){
				$Sap_ID = $data['Sap_ID'];
				$Case_ID = $data['Case_ID'];
				$Barcode_number = $data['Barcode_number'];
				$Ordering_IDEXX_Organisation = $data['Ordering_IDEXX_Organisation'];
				$Ordering_IDEXX_Country = $data['Ordering_IDEXX_Country'];
				$Type_of_immunotherapy = $data['Type_of_immunotherapy'];
				$Qty = $data['Qty'];
				$Accession_number = $data['Accession_number'];
				$Order_date = str_replace("/","-",$data['Order_date']);
				$Order_date = date('Y-m-d', strtotime($Order_date));
				$Recipient_type = $data['Recipient_type'];
				$Sic_document = $data['Sic_document'];
				$Other_license_documents = $data['Other_license_documents'];
				$Integration_to_Pharmacies = $data['Integration_to_Pharmacies'];

				$practiceData['Sap_ID'] = $Sap_ID;
				$practiceData['Customer_name'] = $data['Customer_name'];
				$practiceData['Customer_post_code'] = $data['Customer_post_code'];
				$practiceData['Customer_Address1'] = $data['Customer_Address1'];
				$practiceData['Customer_Address2'] = $data['Customer_Address2'];
				$practiceData['Customer_Address3'] = $data['Customer_Address3'];
				$practiceData['Customer_Address4'] = $data['Customer_Address4'];
				$practiceData['Town_City'] = $data['Town_City'];
				$practiceData['State_County'] = $data['State_County'];
				$practiceData['Country'] = $data['Country'];
				$practiceID = $this->getPracticeinfo($practiceData);
				$petOwnerID = $this->getPetownerinfo($data['Owner_name']);
				$petData['Species'] = $data['Species'];
				$petData['Patient_name'] = $data['Patient_name'];
				$petData['practiceID'] = $practiceID;
				$petData['petOwnerID'] = $petOwnerID;
				$petID = $this->getPetinfo($petData);
				$order_number = $this->get_order_number();
				if($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0){
					$final_order_number = 1001;
				}else{
					$final_order_number = $order_number['order_number'] + 1;
				}
				$practice_lab = '24083';
				$allergensID = $this->getAllergens($data['List_of_allergens']);
				$total_allergen = count(json_decode($allergensID,true));
				$final_price = '0.00';
				$order_discount = '0.00';
				if($total_allergen > 0){
					$artuvetrin_test_price = $this->artuvetrin_test_price($practice_lab);
					if($total_allergen <= 4){
						$order_discount = 0.00;
						/**discount **/
						$artuvetrin_discount = $this->get_discount("16", $practice_lab);
						if(!empty($artuvetrin_discount)){
							$order_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
							$order_discount = sprintf("%.2f", $order_discount);
						}
						/**discount **/
						$final_price = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
						$order_discount = round($order_discount, 2);
					}elseif($total_allergen > 4 && $total_allergen <= 8){
						$order_discount = 0.00;
						/**discount **/
						$artuvetrin_discount = $this->get_discount("17", $practice_lab);
						if (!empty($artuvetrin_discount)) {
							$order_discount = ($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
							$order_discount = sprintf("%.2f", $order_discount);
						}
						/**discount **/
						$final_price = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
						$order_discount = round($order_discount, 2);
					}elseif($total_allergen > 8){
						$final_price = 0.00;
						$order_discount = 0.00;
						$first_range_price = 0.00;
						$order_first_discount = 0.00;
						$order_second_discount = 0.00;
						$quotients = ($total_allergen / 8);
						$quotient = ((int)($total_allergen / 8));
						$remainder = (int)(fmod($total_allergen, 8));

						/**discount **/
						$artuvetrin_second_discount = $this->get_discount("17", $practice_lab);
						$_quotients = $quotients - $quotient;
						$is_update=1;
						if(!empty($artuvetrin_second_discount)){
							if ($_quotients > 0.50) {
								$quotient++;
								$is_update=0;
								$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
								$order_second_discount = sprintf("%.2f", $order_second_discount);
							}else{
								$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
								$order_second_discount = sprintf("%.2f", $order_second_discount);
							}
						}

						if ($_quotients > 0.50) {
							if($is_update){
								$quotient++;
							}
							$second_range_price = ($quotient * ($artuvetrin_test_price[1]['uk_price'])) - $order_second_discount;
						}else{
							$second_range_price = ($quotient * ($artuvetrin_test_price[1]['uk_price'])) - $order_second_discount;
						}

						if($remainder > 0){
							/**discount **/
							$artuvetrin_first_discount = $this->get_discount("16",$practice_lab);
							if(!empty($artuvetrin_first_discount)){
								if($_quotients <= 0.50 && $_quotients != 0){
									$order_first_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_first_discount['uk_discount'] )/100;
									$order_first_discount = sprintf("%.2f", $order_first_discount);
								}
							}
							/**discount **/
						}
						if($_quotients <= 0.50 && $_quotients != 0) {
							$first_range_price = (1 * ($artuvetrin_test_price[0]['uk_price'])) - $order_first_discount;
						}
						$final_price = $first_range_price + $second_range_price; 
						$order_discount = round($order_first_discount + $order_second_discount, 2);
					}

					$orderData['vet_user_id']	= $practiceID;
					$orderData['pet_owner_id']	= $petOwnerID;
					$orderData['pet_id']		= $petID;
					$orderData['lab_id']		= $practice_lab;
					$orderData['name']			= 'IDEXX API Process';
					$orderData['email']			= 'orders@idexx.com';
					$orderData['phone_number']	= '';
					$orderData['order_type']	= '1';
					$orderData['sub_order_type']= '1';
					$orderData['plc_selection']	= '2';
					$orderData['species_selection']= '';
					$orderData['order_number'] = $final_order_number;
					$orderData['order_date']	= $Order_date;
					$orderData['qty_order']		= '0';
					$orderData['unit_price']	= $final_price;
					$orderData['order_discount']= $order_discount;
					$orderData['shipping_cost']	= '';
					$orderData['allergens']		= $allergensID;
					$orderData['batch_number']	= '';
					$orderData['is_mail_sent']	= '0';
					$orderData['sic_document']	= $Sic_document;
					$orderData['reference_number']= '';
					$orderData['purchase_order_number']= '';
					$orderData['is_confirmed']	= '0';
					$orderData['order_can_send_to']= '0';
					$orderData['delivery_practice_id']= '0';
					$orderData['is_repeat_order']= '0';
					$orderData['is_invoiced']	= '0';
					$orderData['is_draft']		= '0';
					$orderData['created_by'] = '24083';
					$orderData['created_at'] = date("Y-m-d H:i:s");
					$orderData['sap_lims']	= $Sap_ID;
					$orderData['case_ID']	= $Case_ID;
					$orderData['barcode_number']	= $Barcode_number;
					$orderData['idexx_Organisation']	= $Ordering_IDEXX_Organisation;
					$orderData['idexx_Country']	= $Ordering_IDEXX_Country;
					$orderData['recipient_type']	= $Recipient_type;
					$orderData['other_license_documents']= $Other_license_documents;
					$orderData['integration_to_Pharmacies']= $Integration_to_Pharmacies;
					$this->db->insert('ci_orders', $orderData);
					$orderID = $this->db->insert_id();

					$dataDetails["message"] = 'Order has been placed successfully.';
					$dataDetails["Nextmune_Order_Number"] = $final_order_number;
				}else{
					$dataDetails["message"] = 'Error in List of Allergens';
					$dataDetails["Nextmune_Order_Number"] = '';
				}
			}else{
				if($data['List_of_allergens'] != ""){
					$dataDetails["message"] = 'Error in List of Allergens';
				}elseif($data['Sap_ID'] != ""){
					$dataDetails["message"] = 'Error in Sap_ID';
				}elseif($data['Customer_post_code'] != ""){
					$dataDetails["message"] = 'Error in Customer_post_code';
				}elseif($data['Order_date'] != ""){
					$dataDetails["message"] = 'Error in Order_date';
				}elseif($data['Species'] != ""){
					$dataDetails["message"] = 'Error in Species';
				}elseif($data['Patient_name'] != ""){
					$dataDetails["message"] = 'Error in Patient_name';
				}elseif($data['Owner_name'] != ""){
					$dataDetails["message"] = 'Error in Owner_name';
				}
				$dataDetails["Nextmune_Order_Number"] = '';
			}
			$this->response($dataDetails, IDEXX_API_Controller::HTTP_OK);
		}
	}

	public function index_get(){
		if($this->uri->segment('3') == 'allergens'){
			$this->db->select('name,code,is_unavailable,due_date');
			$this->db->from('ci_allergens');
			$this->db->where('parent_id !=', '0');
			$datas = $this->db->get()->result_array();
			$dataDetails = []; $datao = [];
			foreach($datas as $data_detail){
				$datao["Allergen_Name"] = $data_detail['name'];
				$datao["Allergen_Code"] = $data_detail['code'];
				if($data_detail['is_unavailable'] == '0'){
				$datao["In_Stock"] = 'Y';
				}else{
				$datao["In_Stock"] = 'N';
				}
				if(!empty($data_detail['due_date']) && $data_detail['due_date'] != '0000-00-00'){
				$datao["Stock_Due_In_Date"] = $data_detail['due_date'];
				}else{
				$datao["Stock_Due_In_Date"] = '';
				}
				$dataDetails[] = $datao;
			}
			$this->response($dataDetails, IDEXX_API_Controller::HTTP_OK);
		}elseif($this->uri->segment('3') == 'order_details'){
			$practice_lab = '24083';
			$this->db->select('ci_orders.id, ci_orders.order_number, ci_orders.allergens, ci_orders.vet_user_id, ci_orders.lab_id, ci_orders.order_can_send_to, ci_orders.delivery_practice_id, ci_orders.reference_number, ci_orders.shipping_cost, ci_orders.unit_price, ci_orders.order_discount, ci_orders.case_ID, ci_orders.sap_lims, ci_orders.batch_number, ci_orders.order_date, ci_orders.is_confirmed, ci_orders.shipping_date, ci_orders.track_number, ci_orders.order_type, ci_orders.is_invoiced, petOwner.name AS pet_owner_name, petOwner.last_name AS pet_owner_lname, ci_pets.name AS pet_name, ci_pets.type,practice.country');
			$this->db->from($this->_table);
			$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
			$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
			$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.lab_id', $practice_lab);
			$datas = $this->db->get()->result_array();
			$data = []; $numericArr = []; $alphaArr = [];
			$dataDetails = $userData = $usersDetails =$column_field = [];
			$allergens = [];
			$allergen_name = $order_send_to = $add_1 = $add_2 = $add_3 = $add_4 = $address_2 = $odelivery_address = $allergensCode =$is_status = $ownerName =''; $userArr = array();
			foreach($datas as $data_detail){
				$AnimalType = "";
				if($data_detail['type']==1){
					$AnimalType = "Cat";
				}elseif($data_detail['type']==2){
					$AnimalType = "Dog";
				}elseif($data_detail['type']==3){
					$AnimalType = "Horse";
				}

				if($data_detail['is_confirmed'] == 0){
					$is_status = "New Order";
				}elseif($data_detail['is_confirmed'] == 1){
					$is_status = "Order Confirmed";
				}elseif($data_detail['is_confirmed'] == 2){
					$is_status = "On Hold";
				}elseif($data_detail['is_confirmed'] == 3){
					$is_status = "Cancelled";
				}elseif($data_detail['is_confirmed'] == 4){
					$is_status = "Shipped";
				}elseif($data_detail['is_confirmed'] == 5){
					$is_status = "In Process";
				}elseif($data_detail['is_confirmed'] == 6){
					$is_status = "Error at Exact";
				}elseif($data_detail['is_confirmed'] == 7){
					$is_status = "Order Confirmed";
				}elseif($data_detail['is_confirmed'] == 8){
					$is_status = "Sent to Nextmune";
				}elseif($data_detail['is_invoiced'] == 1){
					$is_status = "Invoiced";
				}

				//allergen details
				$allergens = $this->AllergensModel->orderAPI_allergens($data_detail['allergens']);
				$allergen_name = ( !empty($allergens) ) ? $allergens['name'] : "";

				$order_send_to = ''; $usrID = 0;
				if ($data_detail['order_can_send_to'] == '1') {
					$userData = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'add_1','add_2','add_3','add_4','address_2','address_3','account_ref'");
					$usersDetails = $this->UsersDetailsModel->getColumnField($userData);
					$column_field = explode('|', $usersDetails['column_field']);
					$address_1 = isset($column_field[0]) ? $column_field[0] : '';
					$address_2 = isset($column_field[1]) ? $column_field[1] : '';
					$address_3 = isset($column_field[2]) ? $column_field[2] : '';
					$address_4 = isset($column_field[3]) ? $column_field[3] : '';
					$town = isset($column_field[4]) ? $column_field[4] : '';
					$postcode = isset($column_field[5]) ? $column_field[5] : '';
					$order_send_to = $address_1.' '.$address_2.' '.$address_3.' '.$address_4.' '.$town.' '.$postcode;

					$userData1 = array("user_id" => $data_detail['delivery_practice_id'], "column_name" => "'account_ref'");
					$usersDetails1 = $this->UsersDetailsModel->getColumnField($userData1);
					$column_fields = explode('|', $usersDetails1['column_field']);
					$usrID = $data_detail['delivery_practice_id'];
				}else if($data_detail['order_can_send_to'] == '0'){
					if($data_detail['lab_id'] > 0){
						$userData = array("user_id" => $data_detail['lab_id'], "column_name" => "'address_1','address_2','address_3','post_code', 'town_city', 'account_ref'");
						$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
						$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
						$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
						$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
						$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
						$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
						$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
						$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						$usrID = $data_detail['lab_id'];
					}else{
						$usrID = $data_detail['vet_user_id'];
						$brances = $this->AllergensModel->getBranchdetailsById($data_detail['vet_user_id']);
						if(!empty($brances)){
							$address_1 = !empty($brances->address) ? $brances->address : '';
							$address_2 = !empty($brances->address1) ? $brances->address1 : '';
							$address_3 = !empty($brances->address2) ? $brances->address2 : '';
							$town = !empty($brances->town_city) ? $brances->town_city : '';
							$postcode = !empty($brances->postcode) ? $brances->postcode : '';
							$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						}else{
							$userData = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
							$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
							$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
							$address_1 = !empty($LabDetails['add_1']) ? $LabDetails['add_1'] : '';
							$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
							$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
							$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
							$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
							$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
						}
					}
				}

				$account_ref = '';
				if($data_detail['lab_id'] > 0){
					$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref','vat_applicable'");
					$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
				}else{
					$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref','vat_applicable'");
					$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
					if($account_ref == ''){
						$this->db->select('id, name, address, address1, address2, address3, town_city, country, postcode, number, customer_number');
						$this->db->from('ci_branches');
						$this->db->where('vet_user_id',$data_detail['vet_user_id']);
						$brances = $this->db->get()->row();
						if(!empty($brances)){
							$account_ref = !empty($brances->customer_number) ? $brances->customer_number : '';
						}
					}
				}

				$this->db->select('country');
				$this->db->from('ci_users');
				$this->db->where('id',$usrID);
				$usrquery = $this->db->get()->row()->country;
				if($usrquery == 1){
					$country = 'UK';
				}else{
					$country = 'Ireland';
				}

				if(!in_array($usrID, $userArr)){
					$userArr[] = $usrID;
					$freightCost = '20';
				}else{
					$freightCost = '0';
				}

				$company = '';
				if($data_detail['pet_owner_name'] == NULL && $data_detail['pet_owner_lname'] == NULL){
					$company = '';
				}else{
					if($data_detail['pet_owner_name'] == NULL){
						$company = $data_detail['pet_owner_lname'];
					}else{
						$company = $data_detail['pet_owner_name'].' '.$data_detail['pet_owner_lname'];
					}
				}

				$allergenArr = explode("|@|",$allergen_name);
				$total_allergen = count($allergenArr);
				if ($total_allergen <= 4) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; $allergensNameArr = []; 
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
						$allergensNameArr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '7'.implode("",$alphaArr).implode("",$numericArr);
					$data["Nextmune_Order_Number"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["Orderby"] = $account_ref;
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
					$data["Country"] = $country;
					$data["Unit_Price"] = $data_detail['unit_price'];
					$data["Order_Discount"] = $data_detail['order_discount'];
					$data["Freight_Cost"] = $freightCost;
					$data["Allergens"] = json_encode($allergensNameArr);
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Total_Vial"] = '1';
					$data["Order is to be sent to"] = $order_send_to;
					$data["Case_ID"] = $data_detail['case_ID'];
					$data["SAP_ID"] = $data_detail['sap_lims'];
					$data["BILL_TO_SAP"] = '';
					$data["BILL_FROM_SAP"] = '';
					$data["Batch_Number"] = $data_detail['batch_number'];
					$data["Order_date"] = $data_detail['order_date'];
					$data["Order_Status"] = $is_status;
					$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
					if($data_detail['track_number']!=""){
					$data["Tracking_number_Courier"] = $data_detail['track_number'];
					}else{
					$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
					}
					$dataDetails[] = $data;
				} elseif ($total_allergen > 4 && $total_allergen <= 8) {
					$allergensCode = ''; $numericArr = []; $alphaArr = []; $allergensNameArr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}else{
							$alphaArr[] = $allergensCode;
						}
						$allergensNameArr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);
					sort($numericArr, SORT_NUMERIC);
					$allergeName = '8'.implode("",$alphaArr).implode("",$numericArr);
					$data["Nextmune_Order_Number"] = $data_detail['order_number'];
					$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
					$data["Orderby"] = $account_ref;
					$data["AnimalType"] = $AnimalType;
					$data["OwnerName"] = $company;
					$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
					$data["Country"] = $country;
					$data["Unit_Price"] = $data_detail['unit_price'];
					$data["Order_Discount"] = $data_detail['order_discount'];
					$data["Freight_Cost"] = $freightCost;
					$data["Allergens"] = json_encode($allergensNameArr);
					$data["Itemcode"] = $allergeName;
					$data["Vialnumber"] = '';
					$data["Total_Vial"] = '1';
					$data["Order is to be sent to"] = $order_send_to;
					$data["Case_ID"] = $data_detail['case_ID'];
					$data["SAP_ID"] = $data_detail['sap_lims'];
					$data["BILL_TO_SAP"] = '';
					$data["BILL_FROM_SAP"] = '';
					$data["Batch_Number"] = $data_detail['batch_number'];
					$data["Order_date"] = $data_detail['order_date'];
					$data["Order_Status"] = $is_status;
					$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
					if($data_detail['track_number']!=""){
					$data["Tracking_number_Courier"] = $data_detail['track_number'];
					}else{
					$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
					}
					$dataDetails[] = $data;
				} elseif ($total_allergen > 8) {
					$quotient = ($total_allergen/8);
					$totalVials = ((round)($quotient));
					$demimal = $quotient-$totalVials;
					if($demimal > 0){
						$totalVials = $totalVials+1;
					}

					$allergensCode = ''; $numericArr = array(); $allergensName1Arr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(is_numeric($allergensCode)){
							$numericArr[] = $allergensCode;
						}
						$allergensName1Arr[] = $aleName;
					}
					$numericArr = array_unique($numericArr);
					asort($numericArr);

					$allergensCode = ''; $alphaArr = array(); $allergensName2Arr = [];
					foreach($allergenArr as $key=>$aleName){
						$allergensCode = $this->AllergensModel->getAllergensCode($aleName);
						if(!is_numeric($allergensCode)){
							$alphaArr[] = $allergensCode;
						}
						$allergensName2Arr[] = $aleName;
					}
					$alphaArr = array_unique($alphaArr);
					sort($alphaArr);

					$testArr = array_merge($alphaArr,$numericArr);
					$allergensNameArr = array_merge($allergensName1Arr,$allergensName2Arr);
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
						$data["Nextmune_Order_Number"] = $data_detail['order_number'].'V'.$x;
						$data["Reference_Number"] = ($data_detail['reference_number']==NULL) ? "" : $data_detail['reference_number'];
						$data["Orderby"] = $account_ref;
						$data["AnimalType"] = $AnimalType;
						$data["OwnerName"] = $company;
						$data["AnimalName"] = ($data_detail['pet_name']==NULL) ? "" : $data_detail['pet_name'];
						$data["Country"] = $country;
						$data["Unit_Price"] = $data_detail['unit_price'];
						$data["Order_Discount"] = $data_detail['order_discount'];
						if($x ==1){
						$data["Freight_Cost"] = $freightCost;
						}else{
						$data["Freight_Cost"] = "0";
						}
						$data["Allergens"] = json_encode($allergensNameArr);
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
						$data["Total_Vial"] = $totalVials;
						$data["Order is to be sent to"] = $order_send_to;
						$data["Case_ID"] = $data_detail['case_ID'];
						$data["SAP_ID"] = $data_detail['sap_lims'];
						$data["BILL_TO_SAP"] = '';
						$data["BILL_FROM_SAP"] = '';
						$data["Batch_Number"] = $data_detail['batch_number'];
						$data["Order_date"] = $data_detail['order_date'];
						$data["Order_Status"] = $is_status;
						$data["Shipping_date"] = !empty($data_detail['shipping_date'])?$data_detail['shipping_date']:'';
						if($data_detail['track_number']!=""){
						$data["Tracking_number_Courier"] = $data_detail['track_number'];
						}else{
						$data["Tracking_number_Courier"] = 'Being evaulated,to be built';
						}
						$dataDetails[] = $data;
					}
				}
			}
			$this->response($dataDetails, IDEXX_API_Controller::HTTP_OK);
		}
    }

	function get_order_number(){
		$this->db->select('MAX(order_number) AS order_number');
		$this->db->from($this->_table);

		return $this->db->get()->row_array();
	}

	function getPracticeinfo($practiceData){
		if(!empty($practiceData['Sap_ID'])){
			$current_date = date("Y-m-d H:i:s");
			$this->db->select('id as user_id');
			$this->db->from('ci_users');
			$this->db->where('SAP_ID', $practiceData['Sap_ID']);
			$results = $this->db->get();
			if($results->num_rows() > 0){
				$userID = $results->row()->user_id;

				$postUserDetails['id'] = $userID;
				$postUserDetails['add_1'] = $practiceData['Customer_Address1'];
                $postUserDetails['add_2'] = $practiceData['Customer_Address2'];
                $postUserDetails['add_3'] = $practiceData['Customer_Address3'];
                $postUserDetails['add_4'] = $practiceData['Customer_Address4'];
                $postUserDetails['address_2'] = $practiceData['Town_City'];
				$postUserDetails['country_code'] = $practiceData['State_County'];
                $postUserDetails['address_3'] = $practiceData['Customer_post_code'];
				$postUserDetails['account_ref'] = $practiceData['Customer_Number'];
				$postUserDetails['vat_applicable'] = '1';
				$postUserDetails['order_can_send_to'] = '1';

				$this->db->select('column_name');
				$this->db->from('ci_user_details');
				$this->db->where('user_id', $userID);
				$existing_fields =  $this->db->get()->result_array();
				$existing_fields_arr = [];
				foreach ($existing_fields as $fkey => $fval) {
					$existing_fields_arr[] = $fval['column_name'];
				}
				$details = [];
				foreach($postUserDetails as $key => $val){
					if($key != 'id'){
						if(in_array($key, $existing_fields_arr)){
							$detail = array(
								"column_name" => $key,
								"column_field" => $val,
								"updated_at" => $current_date
							);
							$details[] = $detail;
						}
					}
				}
				$this->db->update_batch('ci_user_details', $details, 'column_name');

				return $userID;
			}else{
				$postUser['name'] = $practiceData['Customer_name'];
                $postUser['last_name'] = '';
                $postUser['email'] = '';
                $postUser['password'] = '';
				if($practiceData['Country'] == 'UK'){
					$postUser['country'] = '1';
				}else{
					$postUser['country'] = '1';
				}
                $postUser['phone_number'] = '';
                $postUser['role'] = 2;
				$postUser['post_code'] = $practiceData['Customer_post_code'];
				$postUser['created_at'] = date("Y-m-d H:i:s");
				$postUser['created_by'] = '24083';
				$postUser['updated_at'] = date("Y-m-d H:i:s");
				$postUser['updated_by'] = '24083';
				$this->db->insert('ci_users', $postUser);
				$userID = $this->db->insert_id();

				$postUserDetails['id'] = $userID;
				$postUserDetails['add_1'] = $practiceData['Customer_Address1'];
                $postUserDetails['add_2'] = $practiceData['Customer_Address2'];
                $postUserDetails['add_3'] = $practiceData['Customer_Address3'];
                $postUserDetails['add_4'] = $practiceData['Customer_Address4'];
                $postUserDetails['address_2'] = $practiceData['Town_City'];
				$postUserDetails['country_code'] = $practiceData['State_County'];
                $postUserDetails['address_3'] = $practiceData['Customer_post_code'];
				$postUserDetails['account_ref'] = $practiceData['Customer_Number'];
				$postUserDetails['vat_applicable'] = '1';
				$postUserDetails['order_can_send_to'] = '1';
				$ins_details = [];
				foreach($postUserDetails as $key => $val){
					$ins_detail = array(
						"user_id" => $postUserDetails['id'],
						"column_name" => $key,
						"column_field" => $val,
						"created_at" => $current_date
					);
					$ins_details[] = $ins_detail;
				}
				if(!empty($ins_details)){
					$this->db->insert_batch('ci_user_details', $ins_details);
				}
				return $userID;
			}
		}else{
			return 0;
		}
	}

	function getPetownerinfo($ownerData=''){
		if(!empty($ownerData)){
			$this->db->select('id');
			$this->db->from('ci_users');
			$this->db->where('last_name LIKE', $ownerData);
			$results = $this->db->get();
			if($results->num_rows() > 0){
				return $results->row()->id;
			}else{
				$postUser['name'] = '';
                $postUser['last_name'] = $ownerData;
                $postUser['email'] = '';
                $postUser['password'] = '';
                $postUser['age'] = '';
                $postUser['post_code'] = '';
                $postUser['role'] = 3;
                $postUser['country'] = 1;
				$postUser['created_by'] = '24083';
				$postUser['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_users', $postUser);
				$ownerID = $this->db->insert_id();
				return $ownerID;
			}
		}else{
			return 0;
		}
	}

	function getPetinfo($petData){
		if(!empty($petData) && $petData['Patient_name'] != ""){
			if($petData['Species'] == 'Cat'){
				$type = 1;
			}elseif($petData['Species'] == 'Dog'){
				$type = 2;
			}elseif($petData['Species'] == 'Horse'){
				$type = 3;
			}
			$this->db->select('id');
			$this->db->from('ci_pets');
			$this->db->where('vet_user_id', $petData['practiceID']);
			$this->db->where('pet_owner_id', $petData['petOwnerID']);
			$this->db->where('name LIKE', $petData['Patient_name']);
			$this->db->where('type', $type);
			$results = $this->db->get();
			if($results->num_rows() > 0){
				return $results->row()->id;
			}else{
				$postUser['vet_user_id'] = $petData['practiceID'];
                $postUser['branch_id'] = '0';
                $postUser['pet_owner_id'] = $petData['petOwnerID'];
                $postUser['name'] = $petData['Patient_name'];
                $postUser['type'] = $type;
                $postUser['breed_id'] = '0';
                $postUser['allergen_id'] = '0';
                $postUser['age'] = '0';
				$postUser['age_year'] = '0';
				$postUser['created_by'] = '24083';
				$postUser['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_pets', $postUser);
				$petsID = $this->db->insert_id();
				return $petsID;
			}
		}else{
			return 0;
		}
	}

	function getAllergens($allergens){
		if(!empty($allergens) && $allergens != ""){
			$part = explode(",",$allergens);
			$allergenArr = array();
			foreach($part as $row){
				$allergenArr[] = "'".$row."'";
			}
			$allergensStr = implode(",",$allergenArr);
			$this->db->select('id');
			$this->db->from('ci_allergens');
			$this->db->where('code IN('.$allergensStr.')');
			$results = $this->db->get();
			if($results->num_rows() > 0){
				$allergenIDArr = array();
				foreach($results->result_array() as $rowa){
					$allergenIDArr[] = $rowa['id'];
				}
				return json_encode($allergenIDArr);
			}
		}else{
			return NULL;
		}
	}

	function practiceLabCountry($practice_id){
		$this->db->select('user.id,country.name');
		$this->db->from('ci_users AS user');
		$this->db->join('ci_countries AS country', 'country.id=user.country','left');
		$this->db->where('user.id',$practice_id);  
		return $this->db->get()->row_array();
	}

	function artuvetrin_test_price($practice_lab='') {
        $practiceLab = $this->practiceLabCountry($practice_lab);
        if( $practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland' ){
			$this->db->select('id,name,roi_price AS uk_price');
        }else{
			$this->db->select('id,name,uk_price');
        }
        $this->db->from('ci_price');
        $this->db->where('id IN(16,17)');  
        return $this->db->get()->result_array(); 
    }

	function get_discount($id, $practice_id) {
		$this->db->select('id,uk_discount');
		$this->db->from('ci_discount');
		$this->db->where('product_id',$id);  
		$this->db->where('practice_id',$practice_id);  
		$query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
    }

}
?>