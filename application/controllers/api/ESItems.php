<?php
require APPPATH . '/libraries/REST_Controller.php';

class ESItems extends REST_Controller {

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
        $this->response($dataDetails, REST_Controller::HTTP_OK);
    }

	public function index_get($id = 0){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$id = $this->uri->segment('4');
		}

		if(!empty($id)){
			$this->db->select('id, lab_id, vet_user_id, pet_owner_id, pet_id, order_number, order_date, order_type, product_code_selection, updated_at, lab_order_number, product_code_selection, order_can_send_to, comment, practice_lab_comment');
			$this->db->from($this->_table);
			$this->db->where('ci_orders.id', $id);
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$datas = $this->db->get()->result_array();
		}else{
			$this->db->select('id, lab_id, vet_user_id, pet_owner_id, pet_id, order_number, order_date, order_type, product_code_selection, updated_at, lab_order_number, product_code_selection, order_can_send_to, comment, practice_lab_comment');
			$this->db->from($this->_table);
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.is_draft', '0');
			$this->db->where('ci_orders.send_Exact', '0');
			$datas = $this->db->get()->result_array();
		}

		$data = $data_details = []; $sendId = 0; $practiceLab = '';
		foreach($datas as $data_detail){
			if($data_detail['lab_id'] > 0){
				$sqluk = "SELECT name, managed_by_id FROM `ci_users` WHERE id = '". $data_detail['lab_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				$practiceLab = $resultuk->name;
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}else{
				$sqluk = "SELECT name, managed_by_id FROM `ci_users` WHERE id = '". $data_detail['vet_user_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				$practiceLab = $resultuk->name;
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}

			if(!empty($zoneby) && in_array("8", $zoneby)){
				$this->db->select('type');
				$this->db->from('ci_pets');
				$this->db->where('id',$data_detail['pet_id']);
				$petuery = $this->db->get()->row();

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

				$account_ref = ''; $order_send_to = ''; $results_to_practice = 0; $invoice_to_practice = 0; 
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

				$data["Order_number"] = $data_detail['order_number'];
				$data["Order_Date"] = date('d/m/Y', strtotime($data_detail['order_date']));
				if($data_detail['order_type'] == '1'){
					$data["Order_Type"] = 'Immunotherpathy';
				}elseif($data_detail['order_type'] == '2'){
					$data["Order_Type"] = 'Serum testing';
				}elseif($data_detail['order_type'] == '3'){
					$data["Order_Type"] = 'Skin Test';
				}
				$data["Owner_Name"] = $petOwner;
				$data["Pet_Name"] = ($petuery->pet_name==NULL) ? "" : preg_replace('/\s+/', '', $petuery->pet_name);
				$data["Batch_number_Lab"] = $data_detail['lab_order_number'];
				$data["Practice_Lab"] = $practiceLab;

				$this->db->select('created_at');
				$this->db->from('ci_order_history');
				$this->db->where('order_id', $data_detail['id']);
				$this->db->order_by("created_at", "DESC");
				$this->db->limit(1, 0);
				$orderHistory = $this->db->get()->row();
				if(!empty($orderHistory)){
					$data["Status_Date"] = date('d/m/Y H:i:s', strtotime($orderHistory->created_at));
				}else{
					$data["Status_Date"] = date('d/m/Y H:i:s', strtotime($data_detail['updated_at']));
				}
				$data["Client_id"] = $account_ref;
				$data["Notes_Customer_Suport_Comment"] = $data_detail['comment'];
				$data["Notes_Lab_Comment"] = $data_detail['practice_lab_comment'];

				$data_details[] = $data;
			}
		}
		$this->response($data_details, REST_Controller::HTTP_OK);
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
				$this->response(['Order number is not found.'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
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

				$this->response(['Exact Order Number has been updated successfully.'], REST_Controller::HTTP_OK);
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
				$this->response(['Order number is not found.'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
			}elseif($input != '0' && $input != '1' && $input != '2' && $input != '3' && $input !='4'){
				$this->response(['Please enter a valid status.'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
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

				$this->response(['Order status has been updated successfully.'], REST_Controller::HTTP_OK);
			}
		}
    }

}
?>