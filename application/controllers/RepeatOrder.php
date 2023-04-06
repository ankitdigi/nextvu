<?php
require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf as Dompdf;
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
error_reporting(E_ERROR | E_PARSE);
class RepeatOrder extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
	    $this->load->model('OrdersModel');
        $this->load->model('UsersModel');
        $this->load->model('PetsModel');
        $this->load->model('UsersDetailsModel');
        $this->load->model('AllergensModel');
        $this->load->model('BreedsModel');
        $this->load->model('SpeciesModel');
        $this->load->model('PriceCategoriesModel');
		$this->load->model('StaffCountriesModel');
        $this->_data['fetch_class'] = $this->router->fetch_class();
        $this->_data['fetch_method'] = $this->router->fetch_method();
		$this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
    }

	function orderType($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('order_type')) {
            $orderProcess = array(
                'order_type'    => $this->input->post('order_type')
            );
            $this->session->set_userdata($orderProcess); 
            if( $this->input->post('order_type')==1 ){
                redirect('repeatOrder/sub_order_type/'.$id);
            }else if( $this->input->post('order_type')==2 ){
                redirect('repeatOrder/species_selection/'.$id);
            }else if( $this->user_role==2 ){
                redirect('repeatOrder/addEdit/'.$id);
            }else{
                redirect('repeatOrder/plc_selection/'.$id);
            }
        }

        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/order_type", $this->_data);
    }

    function screening($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('screening')) {
            $orderProcess = array(
                'screening'    => $this->input->post('screening')
            );
            $this->session->set_userdata($orderProcess); 
            if( $this->user_role==2 ){
                redirect('repeatOrder/addEdit/'.$id);
            }else{
                redirect('repeatOrder/plc_selection/'.$id);
            }
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/screening", $this->_data);
    }

    function sub_order_type($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('sub_order_type')) {
            $orderProcess = array(
                'sub_order_type'    => $this->input->post('sub_order_type')
            );
            $this->session->set_userdata($orderProcess); 
            if( $this->input->post('sub_order_type')==2 ){
                redirect('repeatOrder/single_double_selection/'.$id);
            }else if( $this->user_role==2 ){
                redirect('repeatOrder/addEdit/'.$id);
            }else{
                redirect('repeatOrder/plc_selection/'.$id);
            }
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/sub_order_type", $this->_data);
    }

    function species_selection($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('species_selection')) {
            $orderProcess = array(
                'species_selection'    => $this->input->post('species_selection')
            );
            $this->session->set_userdata($orderProcess); 
            redirect('repeatOrder/product_code_selection/'.$id);
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/species_selection", $this->_data);
    }

    function product_code_selection($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        $species_selection = $this->session->userdata('species_selection');
        $this->_data['product_codes'] = $this->PriceCategoriesModel->getRecordAll($species_selection);
        if ($this->input->post('product_code_selection')) {
            $orderProcess = array(
                'product_code_selection'    => $this->input->post('product_code_selection')
            );
            $this->session->set_userdata($orderProcess); 
            if( $this->user_role==2 ){
                redirect('repeatOrder/addEdit/'.$id);
            }else{
                redirect('repeatOrder/plc_selection/'.$id);
            }
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/product_code_selection", $this->_data);
    }

    function single_double_selection($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('single_double_selection')) {
            $orderProcess = array(
                'single_double_selection'    => $this->input->post('single_double_selection')
            );
            $this->session->set_userdata($orderProcess); 
            if( $this->user_role==2 ){
                redirect('repeatOrder/addEdit/'.$id);
            }else{
                redirect('repeatOrder/plc_selection/'.$id);
            }
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/single_double_selection", $this->_data);
    }

    function plc_selection($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        if ($this->input->post('plc_selection')) {
            $orderProcess = array(
                'plc_selection'    => $this->input->post('plc_selection')
            );
            $this->session->set_userdata($orderProcess); 
            redirect('repeatOrder/addEdit/'.$id);
        }
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        $this->load->view("orders/plc_selection", $this->_data);
    }

    function addEdit($id= ''){
		$orderData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$this->_data['controller'] = $this->router->fetch_class();
		$role_id = "2";
		$data = $this->OrdersModel->getRecord($id);
		if($id > 0){
			if($data['order_type'] == 2){
				if($data['species_selection'] == 2){
					$pc_selection = '12';
				}else{
					$pc_selection = '6';
				}
				$orderProcess = array(
					'order_type'    => $data['order_type'],
					'sub_order_type' => $data['sub_order_type'],
					'plc_selection' => $data['plc_selection'],
					'species_selection' => $data['species_selection'],
					'product_code_selection' => $pc_selection,
					'single_double_selection' => $data['single_double_selection']  
				);
			}else{
				$orderProcess = array(
					'order_type'    => $data['order_type'],
					'sub_order_type' => $data['sub_order_type'],
					'plc_selection' => $data['plc_selection'],
					'species_selection' => $data['species_selection'],
					'product_code_selection' => $data['product_code_selection'],
					'single_double_selection' => $data['single_double_selection']  
				);
			}
			$this->session->set_userdata($orderProcess); 
		}
        //set session for edit page

        if($this->user_role=='2'){
            $vetUserData['vet_user_id'] = $this->user_id;
            $orderProcess = array(
                'plc_selection'    => '1'
            );
            $this->session->set_userdata($orderProcess);
        }else{
            $vetUserData['vet_user_id'] = $data['vet_user_id'];
        }

        $vetUserData['branch_id'] = $data['branch_id'];
		$vetUserData['pet_owner_id'] = $data['pet_owner_id'];
        if($data['pet_owner_id'] > 0){
            $petOwnerData['is_petOwner'] = true;
            $petOwnerData['pet_owner_id'] = $data['pet_owner_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
        }else{
            $petOwnerData['is_petOwner'] = false;
            $petOwnerData['pet_owner_id'] = $data['vet_user_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
        }

        $labsData['vet_user_id'] = $data['lab_id'];
        $corporatesData['vet_user_id'] = $data['corporate_id'];
        if($this->user_role=='5'){
            $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id,$this->user_id,$this->user_role,"practices");
            $this->_data['labs'] = $this->UsersModel->getRecordAll("6",$this->user_id,$this->user_role,"labs");
            $this->_data['corporates'] = $this->UsersModel->getRecordAll("7",$this->user_id,$this->user_role,"corporates");
        }else{
            $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
            $this->_data['labs'] = $this->UsersModel->getRecordAll("6");
            $this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
        }

        $this->_data['branches'] = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
        $this->_data['pet_owners'] = $this->UsersModel->get_petOwner_dropdown($vetUserData);
        $this->_data['pets'] = $this->PetsModel->get_pets_dropdown($petOwnerData);
        $this->_data['lab_branches'] = $this->UsersDetailsModel->get_branch_dropdown($labsData);
        $this->_data['corporate_branches'] = $this->UsersDetailsModel->get_branch_dropdown($corporatesData);
        $this->_data['species'] = $this->SpeciesModel->getRecordAll();

        if( $this->session->userdata('order_type')=='2' ){
            $sub_order_type = '3';
        }elseif( $this->session->userdata('order_type')=='3' ){
            $sub_order_type = '4';
        }else{
            $sub_order_type = $this->session->userdata('sub_order_type');
        }
        $this->_data['sub_order_type'] = $sub_order_type;
        $this->_data['order_type'] = $this->session->userdata('order_type');
        $this->_data['deliveryPractices'] = $this->UsersModel->getRecordAll("2");
        if (!empty($this->input->post())) {
			if ($this->session->userdata('order_type') == '2') {
				$orderOData['id'] = $id;
				$orderOData['is_cep_after_screening'] = 1;
				$this->OrdersModel->add_edit($orderOData);
			}
			$orderData = $this->input->post();
			$orderData['cep_id'] = $id;
			$id = "";
			$orderData['id'] = $id;
			$orderData['is_repeat_order'] = "1";
			$orderData['batch_number'] = "";
			$orderData['send_Exact'] = "0";
			if ($this->session->userdata('order_type') == '2') {
				$sub_order_type = '3';
				$orderData['shipping_materials'] = ($this->input->post('shipping_materials') != '') ? '1' : '0';
			} elseif ($this->session->userdata('order_type') == '3') {
				$sub_order_type = '4';
			} else {
				$sub_order_type = $this->session->userdata('sub_order_type');
			}

			if ($sub_order_type == '2' || $this->session->userdata('order_type') == '2') {
				$orderData['active_in_uk'] = $this->input->post('active_in_uk');
				$orderData['vials'] = $this->input->post('vials');
				$orderData['next_serum_test_envelope'] = $this->input->post('next_serum_test_envelope');
				$orderData['flow_chart'] = $this->input->post('flow_chart');
				$orderData['prod_range'] = $this->input->post('prod_range');
				$orderData['equine_allergies'] = $this->input->post('equine_allergies');
				$orderData['atopic'] = $this->input->post('atopic');
				$orderData['food_allergies'] = $this->input->post('food_allergies');
				$orderData['pet_allergies'] = $this->input->post('pet_allergies');
				$orderData['horse_allergies'] = $this->input->post('horse_allergies');
				$orderData['allergen_guide'] = $this->input->post('allergen_guide');
				$orderData['treatment_diary_dogs_cats'] = $this->input->post('treatment_diary_dogs_cats');
				$orderData['treatment_diary_horses'] = $this->input->post('treatment_diary_horses');
				$orderData['flyer'] = $this->input->post('flyer');
			}

			if ($this->user_role == '2') {
				$orderData['vet_user_id'] = $this->user_id;
			}
			if ($this->user_role == '5') {
				$orderData['vet_user_id'] = !empty($tm_vet_user)?$tm_vet_user:$this->input->post('vet_user_id');
			}
			$orderData['branch_id'] = ($this->input->post('branch_id') > 0) ? $this->input->post('branch_id') : NULL;
			$orderData['order_type'] = $this->session->userdata('order_type');
			$orderData['sub_order_type'] = $sub_order_type;
			$orderData['species_selection'] = $this->session->userdata('species_selection');
			$orderData['single_double_selection'] = $this->session->userdata('single_double_selection');
			$orderData['product_code_selection'] = $this->session->userdata('product_code_selection');
			$orderData['plc_selection'] = $this->session->userdata('plc_selection');
			$replaced_sampling_date = str_replace('/', '-', $this->input->post('sampling_date'));
			$orderData['sampling_date'] = ($this->input->post('sampling_date') != '') ? date("Y-m-d", strtotime($replaced_sampling_date)) : NULL;
			$orderData['delivery_practice_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_id') : 0;
			$orderData['delivery_practice_branch_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_branch_id') : 0;

			//lab other delivery address
			$orderData['address1'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address1') : NULL;
			$orderData['address2'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address2') : NULL;
			$orderData['address3'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address3') : NULL;
			$orderData['address4'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address4') : NULL;
			$orderData['town_city'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('town_city') : NULL;
			$orderData['county'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('county') : NULL;
			$orderData['country'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('country') : NULL;
			$orderData['postcode'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('postcode') : NULL;
			$orderData['is_draft'] = 1;

			if ($this->session->userdata('order_type') != '2' && $_FILES["sic_document"]["name"] != '') {
				$temp_name = explode(".", $_FILES["sic_document"]["name"]);
				$config['upload_path']          = SIC_DOC_PATH;
				$config['allowed_types']        = 'pdf';
				$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('sic_document')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect('orders/addEdit/');
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['sic_document'] = $upload_data['upload_data']['file_name'];
				}
			}

            if(empty($orderData['sic_document'])){
                $orderData['sic_document']=$data['sic_document'];
            }

			if ($_FILES["email_upload"]["name"] != '') {
				$config = array();
				$temp_name = explode(".", $_FILES["email_upload"]["name"]);

				$config['upload_path']          = EMAIL_UPLOAD_PATH;
				$config['allowed_types']        = 'msg|eml';
				//$config['max_filename']        = '60';
				$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);

				// Load core upload library.
				$this->load->library('upload');

				// Initialize upload configurations.
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('email_upload')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect('repeatOrder/addEdit/');
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['email_upload'] = $upload_data['upload_data']['file_name'];
				}
			}

            if(empty($orderData['email_upload'])){
                $orderData['email_upload']=$data['email_upload'];
            }

            if(empty($orderData['allergens'])){
                $orderData['allergens']=$data['allergens'];
            }

			if (is_numeric($id) > 0) {
				if ($this->user_role == 1) {
					$replaced_date = str_replace('/', '-', $this->input->post('order_date'));
					$orderData['order_date'] = date("Y-m-d", strtotime($replaced_date));
				}

				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");

				//unset key from array edit time
				unset($orderData["order_type"]);
				unset($orderData["sub_order_type"]);
				unset($orderData["plc_selection"]);
				unset($orderData["species_selection"]);
				unset($orderData["product_code_selection"]);
				unset($orderData["single_double_selection"]);
				unset($orderData['save']);
				unset($orderData['next']);
				//unset key from array edit time

				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					//$this->session->set_flashdata('success','Order data has been updated successfully.');
					redirect('repeatOrder/allergens/' . $id);
				}
			} else {
				unset($orderData['save']);
				unset($orderData['next']);
				$order_number = $this->OrdersModel->get_order_number();
				if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
					$final_order_number = 1001;
				} else {
					$final_order_number = $order_number['order_number'] + 1;
				}
				$orderData['order_number'] = $final_order_number;

				//editable for Admin only
				if ($this->user_role == 1) {
					$replaced_date = str_replace('/', '-', $this->input->post('order_date'));
					$orderData['order_date'] = date("Y-m-d", strtotime($replaced_date));
				} else {
					$orderData['order_date'] = date("Y-m-d");
				}
				$orderData['created_by'] = $this->user_id;
				$orderData['created_at'] = date("Y-m-d H:i:s");
				if ($ins_id = $this->OrdersModel->add_edit($orderData)) {
					//$this->session->set_flashdata('success','Order data has been added successfully.');
					redirect('repeatOrder/allergens/' . $ins_id);
				}
			}
		}

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
  		$this->load->view("orders/add_edit", $this->_data);
    }

	function mergeaddEdit($id= ''){
		$orderIdArr = explode("01999960",$id);
		if(!empty($orderIdArr)){
			$mainID = $orderIdArr[0];
			foreach (array_keys($orderIdArr, $mainID) as $key) {
				unset($orderIdArr[$key]);
			}
			$orderIdstr = implode(",",$orderIdArr);
			if($orderIdstr != ""){
				$mergeID = $mainID.','.$orderIdstr;
			}else{
				$mergeID = $mainID;
			}
			$id = $mainID;
			$orderData = [];
			$this->_data['data'] = [];
			$this->_data['id'] = $id;
			$this->_data['controller'] = $this->router->fetch_class();
			$role_id = "2";
			$data = $this->OrdersModel->getRecord($id);
			if($data['order_type'] == 2){
				if($data['species_selection'] == 2){
					$pc_selection = '12';
				}else{
					$pc_selection = '6';
				}
				$orderProcess = array(
					'order_type'    => $data['order_type'],
					'sub_order_type' => $data['sub_order_type'],
					'plc_selection' => $data['plc_selection'],
					'species_selection' => $data['species_selection'],
					'product_code_selection' => $pc_selection,
					'single_double_selection' => $data['single_double_selection']  
				);
			}else{
				$orderProcess = array(
					'order_type'    => $data['order_type'],
					'sub_order_type' => $data['sub_order_type'],
					'plc_selection' => $data['plc_selection'],
					'species_selection' => $data['species_selection'],
					'product_code_selection' => $data['product_code_selection'],
					'single_double_selection' => $data['single_double_selection']  
				);
			}
			$this->session->set_userdata($orderProcess); 
		}
        //set session for edit page

        if($this->user_role=='2'){
            $vetUserData['vet_user_id'] = $this->user_id;
            $orderProcess = array(
                'plc_selection'    => '1'
            );
            $this->session->set_userdata($orderProcess);
        }else{
            $vetUserData['vet_user_id'] = $data['vet_user_id'];
        }

        $vetUserData['branch_id'] = $data['branch_id'];
		$vetUserData['pet_owner_id'] = $data['pet_owner_id'];
        if($data['pet_owner_id'] > 0){
            $petOwnerData['is_petOwner'] = true;
            $petOwnerData['pet_owner_id'] = $data['pet_owner_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
        }else{
            $petOwnerData['is_petOwner'] = false;
            $petOwnerData['pet_owner_id'] = $data['vet_user_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
        }

        $labsData['vet_user_id'] = $data['lab_id'];
        $corporatesData['vet_user_id'] = $data['corporate_id'];
        if($this->user_role=='5'){
            $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id,$this->user_id,$this->user_role,"practices");
            $this->_data['labs'] = $this->UsersModel->getRecordAll("6",$this->user_id,$this->user_role,"labs");
            $this->_data['corporates'] = $this->UsersModel->getRecordAll("7",$this->user_id,$this->user_role,"corporates");
        }else{
            $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
            $this->_data['labs'] = $this->UsersModel->getRecordAll("6");
            $this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
        }

        $this->_data['branches'] = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
        $this->_data['pet_owners'] = $this->UsersModel->get_petOwner_dropdown($vetUserData);
        $this->_data['pets'] = $this->PetsModel->get_pets_dropdown($petOwnerData);
        $this->_data['lab_branches'] = $this->UsersDetailsModel->get_branch_dropdown($labsData);
        $this->_data['corporate_branches'] = $this->UsersDetailsModel->get_branch_dropdown($corporatesData);
        $this->_data['species'] = $this->SpeciesModel->getRecordAll();

        if( $this->session->userdata('order_type')=='2' ){
            $sub_order_type = '3';
        }elseif( $this->session->userdata('order_type')=='3' ){
            $sub_order_type = '4';
        }else{
            $sub_order_type = $this->session->userdata('sub_order_type');
        }
        $this->_data['sub_order_type'] = $sub_order_type;
        $this->_data['order_type'] = $this->session->userdata('order_type');
        $this->_data['deliveryPractices'] = $this->UsersModel->getRecordAll("2");
        if (!empty($this->input->post())) {
			if ($this->session->userdata('order_type') == '2') {
				$orderOData['id'] = $id;
				$orderOData['is_cep_after_screening'] = 1;
				$this->OrdersModel->add_edit($orderOData);
			}
			$orderData = $this->input->post();
			$orderData['cep_id'] = $id;
			$id = "";
			$orderData['id'] = $id;
			$orderData['is_repeat_order'] = "1";
			if ($this->session->userdata('order_type') == '2') {
				$sub_order_type = '3';
				$orderData['shipping_materials'] = ($this->input->post('shipping_materials') != '') ? '1' : '0';
			} elseif ($this->session->userdata('order_type') == '3') {
				$sub_order_type = '4';
			} else {
				$sub_order_type = $this->session->userdata('sub_order_type');
			}

			if ($sub_order_type == '2' || $this->session->userdata('order_type') == '2') {
				$orderData['active_in_uk'] = $this->input->post('active_in_uk');
				$orderData['vials'] = $this->input->post('vials');
				$orderData['next_serum_test_envelope'] = $this->input->post('next_serum_test_envelope');
				$orderData['flow_chart'] = $this->input->post('flow_chart');
				$orderData['prod_range'] = $this->input->post('prod_range');
				$orderData['equine_allergies'] = $this->input->post('equine_allergies');
				$orderData['atopic'] = $this->input->post('atopic');
				$orderData['food_allergies'] = $this->input->post('food_allergies');
				$orderData['pet_allergies'] = $this->input->post('pet_allergies');
				$orderData['horse_allergies'] = $this->input->post('horse_allergies');
				$orderData['allergen_guide'] = $this->input->post('allergen_guide');
				$orderData['treatment_diary_dogs_cats'] = $this->input->post('treatment_diary_dogs_cats');
				$orderData['treatment_diary_horses'] = $this->input->post('treatment_diary_horses');
				$orderData['flyer'] = $this->input->post('flyer');
			}

			if ($this->user_role == '2') {
				$orderData['vet_user_id'] = $this->user_id;
			}
			if ($this->user_role == '5') {
				$orderData['vet_user_id'] = !empty($tm_vet_user)?$tm_vet_user:$this->input->post('vet_user_id');
			}
			$orderData['branch_id'] = ($this->input->post('branch_id') > 0) ? $this->input->post('branch_id') : NULL;
			$orderData['order_type'] = $this->session->userdata('order_type');
			$orderData['sub_order_type'] = $sub_order_type;
			$orderData['species_selection'] = $this->session->userdata('species_selection');
			$orderData['single_double_selection'] = $this->session->userdata('single_double_selection');
			$orderData['product_code_selection'] = $this->session->userdata('product_code_selection');
			$orderData['plc_selection'] = $this->session->userdata('plc_selection');
			$replaced_sampling_date = str_replace('/', '-', $this->input->post('sampling_date'));
			$orderData['sampling_date'] = ($this->input->post('sampling_date') != '') ? date("Y-m-d", strtotime($replaced_sampling_date)) : NULL;
			$orderData['delivery_practice_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_id') : 0;
			$orderData['delivery_practice_branch_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_branch_id') : 0;

			//lab other delivery address
			$orderData['address1'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address1') : NULL;
			$orderData['address2'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address2') : NULL;
			$orderData['address3'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address3') : NULL;
			$orderData['address4'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address4') : NULL;
			$orderData['town_city'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('town_city') : NULL;
			$orderData['county'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('county') : NULL;
			$orderData['country'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('country') : NULL;
			$orderData['postcode'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('postcode') : NULL;
			$orderData['is_draft'] = 1;

			if ($this->session->userdata('order_type') != '2' && $_FILES["sic_document"]["name"] != '') {
				$temp_name = explode(".", $_FILES["sic_document"]["name"]);
				$config['upload_path']          = SIC_DOC_PATH;
				$config['allowed_types']        = 'pdf';
				$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('sic_document')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect('orders/addEdit/');
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['sic_document'] = $upload_data['upload_data']['file_name'];
				}
			}

            if(empty($orderData['sic_document'])){
                $orderData['sic_document']=$data['sic_document'];
            }

			if ($_FILES["email_upload"]["name"] != '') {
				$config = array();
				$temp_name = explode(".", $_FILES["email_upload"]["name"]);

				$config['upload_path']          = EMAIL_UPLOAD_PATH;
				$config['allowed_types']        = 'msg|eml';
				//$config['max_filename']        = '60';
				$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);

				// Load core upload library.
				$this->load->library('upload');

				// Initialize upload configurations.
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('email_upload')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect('repeatOrder/addEdit/');
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['email_upload'] = $upload_data['upload_data']['file_name'];
				}
			}

            if(empty($orderData['email_upload'])){
                $orderData['email_upload']=$data['email_upload'];
            }

            if(empty($orderData['allergens'])){
				$this->db->select('allergens');
				$this->db->from('ci_orders');
				$this->db->where('id IN('.$orderIdstr.')');
				$res3 = $this->db->get();
				if($res3->num_rows() == 0){
					$orderData['allergens']=$data['allergens'];
				}else{
					$allenArr = array();
					foreach ($res3->result() as $arow){
						foreach (json_decode($arow->allergens) as $parts){
							$allenArr[] = $parts;
						}
					}
					$alleNewArr = array_merge(json_decode($data['allergens']),$allenArr);
					$alleNewArr = array_unique($alleNewArr);
					$jsntostr = implode(",",$alleNewArr);
					$strtoarr = explode(",",$jsntostr);
					$orderData['allergens'] = json_encode($strtoarr);
				}
            }
			if (is_numeric($id) > 0) {
				if ($this->user_role == 1) {
					$replaced_date = str_replace('/', '-', $this->input->post('order_date'));
					$orderData['order_date'] = date("Y-m-d", strtotime($replaced_date));
				}

				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");

				//unset key from array edit time
				unset($orderData["order_type"]);
				unset($orderData["sub_order_type"]);
				unset($orderData["plc_selection"]);
				unset($orderData["species_selection"]);
				unset($orderData["product_code_selection"]);
				unset($orderData["single_double_selection"]);
				unset($orderData['save']);
				unset($orderData['next']);
				//unset key from array edit time

				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					//$this->session->set_flashdata('success','Order data has been updated successfully.');
					redirect('repeatOrder/allergens/' . $id);
				}
			} else {
				unset($orderData['save']);
				unset($orderData['next']);
				$order_number = $this->OrdersModel->get_order_number();
				if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
					$final_order_number = 1001;
				} else {
					$final_order_number = $order_number['order_number'] + 1;
				}
				$orderData['order_number'] = $final_order_number;

				//editable for Admin only
				if ($this->user_role == 1) {
					$replaced_date = str_replace('/', '-', $this->input->post('order_date'));
					$orderData['order_date'] = date("Y-m-d", strtotime($replaced_date));
				} else {
					$orderData['order_date'] = date("Y-m-d");
				}
				$orderData['mergeIDs'] = $mergeID;
				$orderData['created_by'] = $this->user_id;
				$orderData['created_at'] = date("Y-m-d H:i:s");
				if ($ins_id = $this->OrdersModel->add_edit($orderData)) {
					//$this->session->set_flashdata('success','Order data has been added successfully.');
					redirect('repeatOrder/allergens/' . $ins_id);
				}
			}
		}

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
  		$this->load->view("orders/add_edit", $this->_data);
    }

    function allergens($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $orderTypeData = array("0" => $data['sub_order_type']);
        $this->_data['allergens_group'] = $this->AllergensModel->get_allergens_dropdown($orderTypeData);
        $this->_data['id'] = $id;
        $orderData = [];
		if($data['order_type'] == '2') {
			$this->db->select('allergens');
			$this->db->from('ci_orders');
			$this->db->where('id', $data['cep_id']);
			$cepInfo = $this->db->get()->row_array();
			if(!empty($cepInfo)){
				$orderData['id'] = $id;
				$orderData['allergens'] = $cepInfo['allergens'];
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->add_edit($orderData);
			}else{
				$orderData['id'] = $id;
				$orderData['allergens'] = '[]';
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->add_edit($orderData);
			}
			redirect('repeatOrder/serum_request/' . $id);
		}else{
			$allergen_total = $this->input->post('allergen_total'); 
			if ( $allergen_total!='') {
				//check allergens is available or not
				$notAvailAllergens = $this->AllergensModel->getNotAvailAllergens($this->input->post('allergens'));
				if( !empty($notAvailAllergens) &&  $notAvailAllergens['name']!='' ){
					$this->session->set_flashdata('error','Sorry allergen <strong>'.$notAvailAllergens['name'].'</strong> is currently unavailable. The respective expected due date is (<strong>'.$notAvailAllergens['due_date'].'</strong>). Please check back on this date to place your order.');
					$this->session->set_flashdata('info','If you would like to proceed without this allergen please untick the box.');
					$data['allergens'] = json_encode($this->input->post('allergens'));
					$this->_data['data'] = $data;
					//$this->load->view("orders/allergens",$this->_data); 
				}else{
					$orderData['id'] = $id;
					$orderData['allergens'] = ($this->input->post('allergens')[0]!='') ? json_encode($this->input->post('allergens')) : NULL;
					$orderData['practice_lab_comment'] = ($this->input->post('practice_lab_comment')!='')?$this->input->post('practice_lab_comment'):'';
					$orderData['comment_by'] = ($this->input->post('practice_lab_comment')!='')?$this->user_id:0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");

					$this->OrdersModel->add_edit($orderData);
					$orderProcess = array(
						'repeat_allergens'    => json_encode($this->input->post('allergens'))
					);
					$this->session->set_userdata($orderProcess);
					if ($id>0) {
						$sql = "SELECT * FROM ci_user_details WHERE column_name IN('labs') AND user_id = '". $this->user_id ."'";
						$responce = $this->db->query($sql);
						$userIds = $responce->result_array();
						$LabDetails = array_column($userIds, 'column_field', 'column_name');
						$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : array();
						if($data['sub_order_type']=='3'){
							redirect('repeatOrder/serum_request/'.$id);
						}/* elseif(count($this->input->post('allergens')) > 8 && in_array("13786", $labs) && $data['order_type'] == 1){
							redirect('repeatOrder/vials/' . $id);
						} */else{
							redirect('repeatOrder/summary/'.$id);
						}
					}
				}//else
			}//allergen_total

			if(!empty($data)){
				$this->_data['data'] = $data;
			}
			$this->load->view("orders/allergens",$this->_data);
		}
    }

    function serum_request($order_id= ''){
		$this->_data['data'] = [];
		$this->db->select('order_type,cep_id');
		$this->db->from('ci_orders');
		$this->db->where('id', $order_id);
		$odrinfo = $this->db->get()->row_array();
		if(!empty($odrinfo)){
			$data = $this->OrdersModel->getSerumTestRecord($odrinfo['cep_id']);
			$id = "";
		}else{
			$data = $this->OrdersModel->getSerumTestRecord($order_id);
			$id = ( !empty($data) && $data['id']>0 ) ? $data['id'] : "";
		}
        $this->_data['id'] = $id;
        $orderData = [];
        if($this->input->post()){
            $orderData['id'] = $id;
            $orderData['order_id'] = $order_id;
            $replaced_date = str_replace ( '/', '-', $this->input->post('date') );
            $orderData['date'] = ($this->input->post('date')!='') ? date("Y-m-d",strtotime($replaced_date)) : NULL;
            $orderData['veterinary_surgeon'] = ($this->input->post('veterinary_surgeon')!='') ? $this->input->post('veterinary_surgeon') : NULL;
            $orderData['veterinary_practice'] = ($this->input->post('veterinary_practice')!='') ? $this->input->post('veterinary_practice') : NULL;
            $orderData['practice_details'] = ($this->input->post('practice_details')!='') ? $this->input->post('practice_details') : NULL;
            $orderData['city'] = ($this->input->post('city')!='') ? $this->input->post('city') : NULL;
            $orderData['postcode'] = ($this->input->post('postcode')!='') ? $this->input->post('postcode') : NULL;
            $orderData['phone'] = ($this->input->post('phone')!='') ? $this->input->post('phone') : NULL;
            $orderData['email'] = ($this->input->post('email')!='') ? $this->input->post('email') : NULL;
            $orderData['receive_results_by'] = ($this->input->post('receive_results_by')[0]!='') ? implode(',',$this->input->post('receive_results_by')) : NULL;
            $orderData['order_more_serum'] = ($this->input->post('order_more_serum')!='') ? '1' : '0'; 
            $orderData['species'] = ($this->input->post('species')[0]!='') ? implode(',',$this->input->post('species')) : NULL;
            $orderData['species_gender'] = ($this->input->post('species_gender')!='') ? '1' : '0'; 
            $orderData['owner_name'] = ($this->input->post('owner_name')!='') ? $this->input->post('owner_name') : '';
            $orderData['animal_name'] = ($this->input->post('animal_name')!='') ? $this->input->post('animal_name') : '';
            $orderData['breed'] = ($this->input->post('breed')!='') ? $this->input->post('breed') : '';
            //$orderData['email'] = $this->input->post('email');
            $birth_replaced_date = str_replace ( '/', '-', $this->input->post('birth_date') );
            $orderData['birth_date'] = ($birth_replaced_date!='') ? date("Y-m-d",strtotime($birth_replaced_date)) : NULL;
            $serum_drawn_replaced_date = str_replace ( '/', '-', $this->input->post('serum_drawn_date') );
            $orderData['serum_drawn_date'] = ($serum_drawn_replaced_date!='') ? date("Y-m-d",strtotime($serum_drawn_replaced_date)) : NULL;
            $orderData['major_symptoms'] = ($this->input->post('major_symptoms')[0]!='') ? implode(',',$this->input->post('major_symptoms')) : NULL;
            $orderData['other_symptom'] = $this->input->post('other_symptom');
            $orderData['symptom_appear_age'] = $this->input->post('symptom_appear_age');
            $orderData['symptom_appear_age_month'] = $this->input->post('symptom_appear_age_month');
            $orderData['when_obvious_symptoms'] = ($this->input->post('when_obvious_symptoms')[0]!='') ? implode(',',$this->input->post('when_obvious_symptoms')) : NULL;
            $orderData['where_obvious_symptoms'] = ($this->input->post('where_obvious_symptoms')[0]!='') ? implode(',',$this->input->post('where_obvious_symptoms')) : NULL;
            $orderData['medication'] = ($this->input->post('medication') != '') ? $this->input->post('zoonotic_disease') : '0';
			$orderData['medication_desc'] = $this->input->post('medication_desc');
			$orderData['zoonotic_disease'] = ($this->input->post('zoonotic_disease') != '') ? $this->input->post('zoonotic_disease') : '0';
			$orderData['zoonotic_disease_dec'] = $this->input->post('zoonotic_disease_dec');
            if (is_numeric($id) > 0) {
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($id = $this->OrdersModel->serum_test_add_edit($orderData) > 0) {
					redirect('orders/summary/' . $order_id);
				}
			} else {
				$orderData['created_by'] = $this->user_id;
				$orderData['created_at'] = date("Y-m-d H:i:s");
				if ($id = $this->OrdersModel->serum_test_add_edit($orderData)) {
					redirect('orders/summary/' . $order_id);
				}
			}
        }

        if(!empty($data)){
            $this->_data['data'] = $data;
        }
        $this->load->view("orders/serum_request",$this->_data);
    }

	function vials($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$order_details = $this->OrdersModel->allData($data['id'], "");
		if (!empty($data)) {
			$this->_data['data'] = $data;
		}
		$allergens = $this->AllergensModel->order_allergens($order_details['allergens']);
		$this->_data['order_details'] = $order_details;
		$this->_data['allergens'] = $allergens;
		$this->_data['total_allergens'] = ($order_details['allergens'] != '') ? count(json_decode($order_details['allergens'])) : 0;
		$this->_data['id'] = $id;
		$this->_data['totalVialsdb'] = $this->OrdersModel->Totalvials($id);
		$orderData = [];
		if (!empty($this->input->post()) && !empty($this->input->post('vials'))){
			$vialsArr = !empty($this->input->post('vials'))?$this->input->post('vials'):array();
			if($this->_data['totalVialsdb'] == 0){
				foreach($vialsArr as $key=>$value){
					$orderData['order_id'] = $id;
					$orderData['vials_order'] = $key;
					$orderData['allergens'] = implode(",",$value);
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit_vials($orderData);
				}
			}else{
				foreach($vialsArr as $key=>$value){
					$orderData['vial_id'] = $this->input->post('vial_id')[$key][0];
					$orderData['order_id'] = $id;
					$orderData['vials_order'] = $key;
					$orderData['allergens'] = implode(",",$value);
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit_vials($orderData);
				}
			}
			redirect('orders/summary/' . $id);
		}
		$this->load->view("orders/vials", $this->_data);
	}

	function summary($id= ''){
		$this->_data['data'] = [];
		$data = $this->OrdersModel->getRecord($id);
		$order_details = $this->OrdersModel->allData($data['id'],"");
		$allergens = $this->AllergensModel->order_allergens($this->session->userdata('repeat_allergens'));
		$controller = $this->router->fetch_class();

		$this->_data['order_details'] = $order_details;
		$this->_data['allergens'] = $allergens;
		$this->_data['total_allergens'] = ($this->session->userdata('repeat_allergens')!='') ? count(json_decode($this->session->userdata('repeat_allergens'))) : 0;
		$this->_data['id'] = $id;
		$this->_data['controller'] = $controller;
		$this->_data['order_type'] = $order_details['order_type'];
		$this->_data['sub_order_type'] = $order_details['sub_order_type'];
		$this->_data['final_price'] = '0.00';
		$this->_data['order_discount'] = '0.00';

		/*****delivery address details */
		$this->_data['delivery_address_details'] = '';
		if ($order_details['order_can_send_to'] == '1') {
			// Same Address
			$delivery_practice = $order_details['delivery_practice_id'];
			$userData = array("user_id" => $delivery_practice, "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
			$usersDetails = $this->UsersDetailsModel->getColumnField($userData);

			$column_field = explode('|', $usersDetails['column_field']);
			$address_2 = isset($column_field[0]) ? $column_field[0] : NULL;
			$address_3 = isset($column_field[1]) ? $column_field[1] : NULL;
			$account_ref = isset($column_field[2]) ? $column_field[2] : NULL;
			$add_1 = isset($column_field[3]) ? $column_field[3] : NULL;
			$add_2 = isset($column_field[4]) ? $column_field[4] : NULL;
			$add_3 = isset($column_field[5]) ? $column_field[5] : NULL;
			$add_4 = isset($column_field[6]) ? $column_field[6] : NULL;
			$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $address_3;
			$this->_data['delivery_address_details'] = $order_send_to;
		}else if($order_details['order_can_send_to'] == '0'){
			if($order_details['lab_id'] > 0){
				// Lab address
				$userData = array("user_id" => $order_details['lab_id'], "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");

				$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
				$LabDetails = array_column($LabDetails, 'column_field', 'column_name');

				$l_address_1 = $LabDetails['address_1'] ? $LabDetails['address_1'] : NULL;
				$l_address_2 = $LabDetails['address_2'] ? $LabDetails['address_2'] : NULL;
				$l_address_3 = $LabDetails['address_3'] ? $LabDetails['address_3'] : NULL;
				$l_town_city = $LabDetails['town_city'] ? $LabDetails['town_city'] : NULL;
				$l_post_code = $LabDetails['post_code'] ? $LabDetails['post_code'] : NULL;

				$order_send_to = $l_address_1 . " " . $l_address_2 . " " . $l_address_3 . " " . $l_town_city . " " . $l_post_code;
				$this->_data['delivery_address_details'] = $order_send_to;
			}else{
				// Branch address
				$address_2 =  $order_details['branch_county'] ??  NULL;
				$address_3 = $order_details['branch_postcode'] ??  NULL;
				$account_ref = isset($column_field[2]) ? $column_field[2] : NULL;
				$add_1 = $order_details['branch_address'] ??  NULL;
				$add_2 = $order_details['branch_address1'] ??  NULL;
				$add_3 = $order_details['branch_address2'] ??  NULL;
				$add_4 = $order_details['branch_address3'] ??  NULL;
				$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $address_3;
				$this->_data['delivery_address_details'] = $order_send_to;
			}
		}

        //Pricing
		$selected_allergen = json_decode($this->session->userdata('repeat_allergens'));
		$total_allergen = count( $selected_allergen );
		if($data['lab_id']!=0){
			$practice_lab = $data['lab_id'];
		}else{
			$practice_lab = $data['vet_user_id'];
		}

		$final_price = 0.00;
		if( $total_allergen > 0 ){
			//Skin Test Pricing
			if( $data['order_type']=='3' ){
				$single_order_discount = 0.00;
				$insects_order_discount = 0.00;
				$selected_allergen_ids = implode(",",$selected_allergen);
				$insects_allergen = $this->AllergensModel->insect_allergen($selected_allergen_ids);
				$skin_test_price = $this->PriceCategoriesModel->skin_test_price($practice_lab);
				$single_price = $skin_test_price[0]['uk_price'];
				$single_insect_price = $skin_test_price[1]['uk_price'];
				$single_allergen = $total_allergen - $insects_allergen;

                /**single allergen discount **/
                $single_discount = $this->PriceCategoriesModel->get_discount("14",$practice_lab);
                if( !empty($single_discount) ){
                    $single_order_discount = ( $skin_test_price[0]['uk_price'] * $single_discount['uk_discount'] )/100;
                    $single_order_discount = sprintf("%.2f", $single_order_discount);
                }
                /**single allergen discount **/

                /**insects allergen discount **/
                if($insects_allergen > 0){
                    $insects_discount = $this->PriceCategoriesModel->get_discount("15",$practice_lab);
                    if( !empty($insects_discount) ){
                        $insects_order_discount = ( $skin_test_price[1]['uk_price'] * $insects_discount['uk_discount'] )/100;
                        $insects_order_discount = sprintf("%.2f", $insects_order_discount);
                    }
                }
                /**insects allergen discount **/

                $final_price = ($single_price * $single_allergen) + ($single_insect_price * $insects_allergen);
                $this->_data['final_price'] = $final_price - ($single_order_discount + $insects_order_discount);
                $this->_data['order_discount'] = round($single_order_discount + $insects_order_discount, 2);
				$this->_data['price_currency'] = $skin_test_price[0]['price_currency'];
            } 

            //Serum Test Pricing 
            if( $data['order_type']=='2' ){
                $order_discount = 0.00;
                $product_code_id = $this->session->userdata('product_code_selection');
                $serum_test_price = $this->PriceCategoriesModel->serum_test_price($product_code_id,$practice_lab);
                $final_price = $total_allergen * ($serum_test_price[0]['uk_price']);

                /**discount **/
                $serum_discount = $this->PriceCategoriesModel->get_discount($data['product_code_selection'],$practice_lab);
                if( !empty($serum_discount) ){
                    $order_discount = ( $serum_test_price[0]['uk_price'] * $serum_discount['uk_discount'] )/100;
                    $order_discount = sprintf("%.2f", $order_discount);
                }
                /**discount **/

                $this->_data['final_price'] = $final_price - $order_discount;
                $this->_data['order_discount'] = round($order_discount, 2);
				$this->_data['price_currency'] = $serum_test_price[0]['price_currency'];
            }

            //Immunotherapy Artuvetrin Test Pricing
            if( $data['order_type']=='1' && $data['sub_order_type']=='1' ){
                $artuvetrin_test_price = $this->PriceCategoriesModel->artuvetrin_test_price($practice_lab);

                //Artuvetrin Therapy 1 – 4 allergens
                if( $total_allergen <=4 ){
                    $order_discount = 0.00;
                    /**discount **/
                    $artuvetrin_discount = $this->PriceCategoriesModel->get_discount("16",$practice_lab);
                    if( !empty($artuvetrin_discount) ){
                        $order_discount = ( $artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/

                    $this->_data['final_price'] = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
                    $this->_data['order_discount'] = round($order_discount, 2);
					$this->_data['price_currency'] = $artuvetrin_test_price[0]['price_currency'];
				//Artuvetrin Therapy 5 – 8 allergens
				}elseif( $total_allergen >4 && $total_allergen <=8 ){
                    $order_discount = 0.00;
                    /**discount **/
                    $artuvetrin_discount = $this->PriceCategoriesModel->get_discount("17",$practice_lab);
                    if( !empty($artuvetrin_discount) ){
                        $order_discount = ( $artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/
                    $this->_data['final_price'] = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
                    $this->_data['order_discount'] = round($order_discount, 2);
					$this->_data['price_currency'] = $artuvetrin_test_price[1]['price_currency'];
				//Artuvetrin Therapy more than 8 allergens
				}elseif( $total_allergen >8 ){
					$final_price = 0.00;
					$first_range_price = 0.00;
					$order_first_discount = 0.00;
					$order_second_discount = 0.00;
					$quotients = ($total_allergen / 8);
					$quotient = ((int)($total_allergen / 8));
					$remainder = (int)(fmod($total_allergen, 8));

					/**discount **/
					$artuvetrin_second_discount = $this->PriceCategoriesModel->get_discount("17", $practice_lab);
					$_quotients = $quotients - $quotient;
					$is_update=1;
					if (!empty($artuvetrin_second_discount)) {
						if ($_quotients > 0.50) {
							$quotient++;
							$is_update=0;
							$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
							$order_second_discount = sprintf("%.2f", $order_second_discount);
						} else {
							$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
							$order_second_discount = sprintf("%.2f", $order_second_discount);
						}
					}
					/**discount **/

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
					    $artuvetrin_first_discount = $this->PriceCategoriesModel->get_discount("16",$practice_lab);
					    if( !empty($artuvetrin_first_discount) ){
							if($_quotients <= 0.50 && $_quotients != 0) {
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
					$this->_data['final_price'] = $final_price;
					$this->_data['order_discount'] = round($order_first_discount + $order_second_discount, 2);
					$this->_data['price_currency'] = $artuvetrin_test_price[0]['price_currency'];
                }
            }//if

            //Sublingual Immunotherapy (SLIT) Pricing
            if( $data['order_type']=='1' && $data['sub_order_type']=='2' ){
                //Sublingual Single Price
                $selected_allergen_ids = implode(",",$selected_allergen);
                $culicoides_allergen = $this->AllergensModel->culicoides_allergen($selected_allergen_ids);
                $slit_test_price = $this->PriceCategoriesModel->slit_test_price($practice_lab);
                $single_price = $slit_test_price[0]['uk_price'];
                $double_price = $slit_test_price[1]['uk_price'];
                $single_with_culicoides = $slit_test_price[2]['uk_price'];
                $double_with_culicoides = $slit_test_price[3]['uk_price'];
                $single_allergen = $total_allergen - $culicoides_allergen;
                $order_discount = 0.00;

                if( $data['single_double_selection']=='1' && $culicoides_allergen==0 ){
                    /**discount **/
                    $slit_discount = $this->PriceCategoriesModel->get_discount("18",$practice_lab);
                    if( !empty($slit_discount) ){
                        $order_discount = ( $slit_test_price[0]['uk_price'] * $slit_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/
    
                    $final_price = $total_allergen * $single_price;
                    $final_price = $final_price - $order_discount;
    
                }else if( $data['single_double_selection']=='2' && $culicoides_allergen==0 ){
    
                    /**discount **/
                    $slit_discount = $this->PriceCategoriesModel->get_discount("19",$practice_lab);
                    if( !empty($slit_discount) ){
                        $order_discount = ( $slit_test_price[1]['uk_price'] * $slit_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/
    
                    $final_price = $total_allergen * $double_price;
                    $final_price = $final_price - $order_discount;
    
                }else if( $data['single_double_selection']=='1' && $culicoides_allergen>0 ){
    
                    /**discount **/
                    $slit_discount = $this->PriceCategoriesModel->get_discount("20",$practice_lab);
                    if( !empty($slit_discount) ){
                        $order_discount = ( $slit_test_price[2]['uk_price'] * $slit_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/
    
                    $final_price = ($single_price * $single_allergen) + ($single_with_culicoides * $culicoides_allergen);
                    $final_price = $final_price - $order_discount;
    
                }else if( $data['single_double_selection']=='2' && $culicoides_allergen>0 ){
    
                    /**discount **/
                    $slit_discount = $this->PriceCategoriesModel->get_discount("21",$practice_lab);
                    //print_r($slit_discount);
                    if( !empty($slit_discount) ){
                        $order_discount = ( $slit_test_price[3]['uk_price'] * $slit_discount['uk_discount'] )/100;
                        $order_discount = sprintf("%.2f", $order_discount);
                    }
                    /**discount **/
    
                    $final_price = ($double_price * $single_allergen) + ($double_with_culicoides * $culicoides_allergen);
                    $final_price = $final_price - $order_discount;
                }
                $this->_data['final_price'] = $final_price;
                $this->_data['order_discount'] = $order_discount;
				$this->_data['price_currency'] = $slit_test_price[0]['price_currency'];
            }
        } 

		if($data['lab_id'] == '13788' || $data['lab_id'] == '13786'){
			$this->_data['shipping_cost'] = '0.00';
		}else{
			$this->_data['shipping_cost'] = '0.00';
			if($data['order_can_send_to'] == '1'){
				$countOdr = $this->OrdersModel->checkDeliveryUserOrderToday($data['delivery_practice_id']);
			}else{
				if($data['lab_id'] != 0){
					$countOdr = $this->OrdersModel->checkLabUserOrderToday($data['lab_id']);
				}else{
					$countOdr = $this->OrdersModel->checkVetUserOrderToday($data['vet_user_id']);
				}
			}
			$countOdr = $this->OrdersModel->checkUserOrderToday($practice_lab);
			if($countOdr == 0){
				//Skin Test Shipping Price
				if ($data['order_type'] == '3') {
					$shipUPrice = $this->OrdersModel->getShippingCostbyUser("4", $practice_lab);
					if(!empty($shipUPrice)){
						$this->_data['final_price'] = $this->_data['final_price']+$shipUPrice['uk_discount'];
						$this->_data['shipping_cost'] = $shipUPrice['uk_discount'];
					}else{
						$shipDPrice = $this->OrdersModel->getDefaultShippingCost("4");
						$this->_data['final_price'] = $this->_data['final_price']+$shipDPrice['uk_price'];
						$this->_data['shipping_cost'] = $shipDPrice['uk_price'];
					}
				}

				//Serum Test Shipping Price 
				if ($data['order_type'] == '2') {
					if ($data['species_selection'] == '2') {
						$shipUPrice = $this->OrdersModel->getShippingCostbyUser("3", $practice_lab);
					}
					if ($data['species_selection'] == '1') {
						$shipUPrice = $this->OrdersModel->getShippingCostbyUser("2", $practice_lab);
					}
					if(!empty($shipUPrice)){
						$this->_data['final_price'] = $this->_data['final_price']+$shipUPrice['uk_discount'];
						$this->_data['shipping_cost'] = $shipUPrice['uk_discount'];
					}else{
						if ($data['species_selection'] == '2') {
							$shipDPrice = $this->OrdersModel->getDefaultShippingCost("3");
							$this->_data['final_price'] = $this->_data['final_price']+$shipDPrice['uk_price'];
							$this->_data['shipping_cost'] = $shipDPrice['uk_price'];
						}
						if ($data['species_selection'] == '1') {
							$shipDPrice = $this->OrdersModel->getDefaultShippingCost("2");
							$this->_data['final_price'] = $this->_data['final_price']+$shipDPrice['uk_price'];
							$this->_data['shipping_cost'] = $shipDPrice['uk_price'];
						}
					}
				}

				//Immunotherapy Shipping Price 
				if ($data['order_type'] == '1') {
					$shipUPrice = $this->OrdersModel->getShippingCostbyUser("1", $practice_lab);
					if(!empty($shipUPrice)){
						$this->_data['final_price'] = $this->_data['final_price']+$shipUPrice['uk_discount'];
						$this->_data['shipping_cost'] = $shipUPrice['uk_discount'];
					}else{
						$shipDPrice = $this->OrdersModel->getDefaultShippingCost("1");
						$this->_data['final_price'] = $this->_data['final_price']+$shipDPrice['uk_price'];
						$this->_data['shipping_cost'] = $shipDPrice['uk_price'];
					}
				}
			}else{
				$existCost = $this->OrdersModel->getexistShippingCost($id);
				$this->_data['shipping_cost'] = !empty($existCost)?$existCost:'0.00';
			}
		}

        $orderData = [];
        if(!empty($this->input->post())){
			if($data['cep_id'] > 0){
				$this->db->select('vial_id');
				$this->db->from('ci_allergens_vials');
				$this->db->where('order_id', $id);
				$res1 = $this->db->get();
				if($res1->num_rows() == 0){
					$vialdata = $this->OrdersModel->getVialsRecord($data['cep_id']);
					if(!empty($vialdata)){
						$vialsData = [];
						foreach($vialdata as $vrow){
							$vialsData['order_id'] = $id;
							$vialsData['vials_order'] = $vrow->vials_order;
							$vialsData['allergens'] = $vrow->allergens;
							$vialsData['updated_by'] = $this->user_id;
							$vialsData['updated_at'] = date("Y-m-d H:i:s");
							$this->OrdersModel->add_edit_vials($vialsData);
						}
					}
				}
			}
			if($this->user_role!='1'){
                //store signature
                $signature = $this->input->post('signature');
                $signatureFileName = time().'.png';
                $signature = str_replace('data:image/png;base64,', '', $signature);
                $signature = str_replace(' ', '+', $signature);
                $data = base64_decode($signature);
                $file = FCPATH.SIGNATURE_PATH.$signatureFileName;
                file_put_contents($file, $data);
                $orderProcess = array(
                    'signature'    => $signatureFileName
                );
                $orderData['id'] = $id;
				$orderData['signature'] = $signatureFileName;
				$orderData['price_currency'] = $this->input->post('price_currency');
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
            }else{
                $orderData['id'] = $id;
				$orderData['price_currency'] = $this->input->post('price_currency');
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
                $orderProcess = array(
                    'signature'    => NULL
                );
            }
            $this->OrdersModel->add_edit($orderData);
            $this->session->set_userdata($orderProcess);
			$orderhData['order_id'] = $id;
			$orderhData['text'] = 'Repeat Order';
			$orderhData['created_by'] = $this->user_id;
			$orderhData['created_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->addOrderHistory($orderhData);
            $this->session->set_flashdata('success','Repeat Order has been placed successfully.');
            $this->send_mail($id);
            redirect('orders');            
        }
        if(!empty($data)){
            $this->_data['data'] = $data;
        }
		if ($order_details['order_type'] == '2') {
			$this->load->view("orders/serum_summary", $this->_data);
		}else{
			$this->load->view("orders/summary", $this->_data);
		}
    }

    public function send_mail($id= '') {
		$this->load->model('RecipientsModel');
		$data = $this->OrdersModel->allData($id);
		$this->OrdersModel->IsDraftUpdate($id);

		//if Order Delivery Address is there then
		$email_upload = FCPATH . EMAIL_UPLOAD_PATH . '/' . $data['email_upload'];
		$account_number_label = 'Practice Account number';

		$total_allergen = ($data['allergens'] != '') ? count(json_decode($data['allergens'])) : 0;
		if ($data['order_can_send_to'] == '1') {
			$delivery_practice = $data['delivery_practice_id'];
		} else {
			$delivery_practice = $data['vet_user_id'];
		}

		$userData = array("user_id" => $delivery_practice, "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
		$usersDetails = $this->UsersDetailsModel->getColumnField($userData);
		$column_field = explode('|', $usersDetails['column_field']);
		$address_2 = isset($column_field[0]) ? $column_field[0] : NULL;
		$address_3 = isset($column_field[1]) ? $column_field[1] : NULL;
		$account_ref = isset($column_field[2]) ? $column_field[2] : NULL;
		$add_1 = isset($column_field[3]) ? $column_field[3] : NULL;
		$add_2 = isset($column_field[4]) ? $column_field[4] : NULL;
		$add_3 = isset($column_field[5]) ? $column_field[5] : NULL;
		$add_4 = isset($column_field[6]) ? $column_field[6] : NULL;

		if ($data['order_can_send_to'] == 1 || $data['order_can_send_to'] == '') {
			$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
		}
		$recipientArr = $this->RecipientsModel->getRecordAll($data['sub_order_type']);

		$toemailArr = [];
		$to_email = '';
		$zonesIds = $this->checkZones($id);
		if(!empty($zonesIds) && !in_array("1", $zonesIds)){
			$zoneFEmail = $this->getZoneFromEmail($zonesIds);
			if(!empty($zoneFEmail)){
				$from_email = $zoneFEmail;
			}
		}else{
			$from_email = "Noreply@nextmune.com";
		}

		$order_date = date('d/m/Y', strtotime($data['order_date']));
		$total = ($data['unit_price'] * $data['qty_order']) - $data['order_discount'];
		$practice_country = '';
		if ($data['practice_country'] == 1) {
			$practice_country = 'UK';
		} else if ($data['practice_country'] == 2) {
			$practice_country = 'Ireland';
		}
		$active_uk = '';
		if ($data['active_in_uk'] == 1 || $data['active_in_uk'] == 2) {
			$active_uk = 'Yes';
		} else if ($data['active_in_uk'] == 3) {
			$active_uk = 'No';
		}

		$allergens_html = "";
		$totalVialsdb = $this->OrdersModel->Totalvials($id);
		$totalAllergens = count(json_decode($data['allergens']));
		if($totalAllergens > 8 && $totalVialsdb > 0 && $data['order_type'] == 1){
			$quotient = ($totalAllergens/8);
			$totalVials = ((round)($quotient));
			$demimal = $quotient-$totalVials;
			if($demimal > 0){
				$totalVials = $totalVials+1;
			}

			for ($x = 1; $x <= $totalVials; $x++) {
				$vialsList = $this->OrdersModel->getVialslist($x,$id);
				$vialsAllenges = explode(",",$vialsList['allergens']);
				$allergens_html .= '<tr>
					<td>
						<p><strong>Vial '.$x.'</strong></p>
						<ul>';
						foreach($vialsAllenges as $row){
							$this->db->select('name,code');
							$this->db->from("ci_allergens");
							$this->db->where("id",$row);
							$responce = $this->db->get();
							$allergensName = $responce->row();
							$allergens_html .= '<li>'.$allergensName->name .' ['.$allergensName->code .']</li>';
						}
						$allergens_html .= '</ul>
					</td>
				</tr>';
			}
		}else{
			$getAllergenParent = $this->AllergensModel->getAllergenParent($data['allergens']);
			foreach ($getAllergenParent as $apkey => $apvalue) {
				$allergens_html .= "<tr><td><p><strong>" . $apvalue['name'] . "</strong></p>";
				$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
				foreach ($subAllergens as $skey => $svalue) {
					$allergens_html .= "<ul><li>" . $svalue['name'] . "</li></ul>";
				}
				$allergens_html .= "</td></tr>";
			}
		}
		if ($allergens_html == '') {
			$allergens_html = "<tr><td><strong>None</strong></td></tr>";
		}

		/**if delivery address and name should be the branch details selected or if no branches use the practice */
		$display_name = '';
		$display_address = '';
		$postal_code = '';
		$full_address = '';
		//if lab order
		if ($data['order_can_send_to'] == '0' && $data['lab_id'] > 0 && $data['plc_selection'] == '2') {
			$l_userData = array("user_id" => $data['lab_id'], "column_name" => "'address_1','address_2','address_3','address_4','town_city','post_code','account_ref'");
			$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($l_userData);
			$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
			$l_address_1 = $LabDetails['address_1'] ? $LabDetails['address_1'] : NULL;
			$l_address_2 = $LabDetails['address_2'] ? $LabDetails['address_2'] : NULL;
			$l_address_3 = $LabDetails['address_3'] ? $LabDetails['address_3'] : NULL;
			$l_address_4 = $LabDetails['address_4'] ? $LabDetails['address_4'] : NULL;
			$l_town_city = $LabDetails['town_city'] ? $LabDetails['town_city'] : NULL;
			$l_account_ref = $LabDetails['account_ref'] ? $LabDetails['account_ref'] : NULL;
			$l_post_code = $LabDetails['post_code'] ? $LabDetails['post_code'] : NULL;
			$display_name = $data['lab_name'];
			$full_address =  $display_name . " " . $l_address_1 . " " . $l_address_2 . " " . $l_address_3 . " " . $l_address_4 . " " . $l_town_city . " " . $l_post_code;
		} else if ($data['lab_id'] > 0) {
			$l_userData = array("user_id" => $data['lab_id'], "column_name" => "'address_1','address_2','address_3','address_4','town_city','post_code','account_ref'");
			$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($l_userData);
			$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
			$l_address_1 = $LabDetails['address_1'] ? $LabDetails['address_1'] : NULL;
			$l_address_2 = $LabDetails['address_2'] ? $LabDetails['address_2'] : NULL;
			$l_address_3 = $LabDetails['address_3'] ? $LabDetails['address_3'] : NULL;
			$l_address_4 = $LabDetails['address_4'] ? $LabDetails['address_4'] : NULL;
			$l_town_city = $LabDetails['town_city'] ? $LabDetails['town_city'] : NULL;
			$l_account_ref = $LabDetails['account_ref'] ? $LabDetails['account_ref'] : NULL;
			$l_post_code = $LabDetails['post_code'] ? $LabDetails['post_code'] : NULL;

			$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($l_userData);
			$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
			$l_account_ref = $LabDetails['account_ref'] ? $LabDetails['account_ref'] : NULL;
		}

		if ($data['order_can_send_to'] == '1' && $data['delivery_practice_branch_id'] > 0) {
			$display_name = $data['delivery_branch_name'];
			$full_address =  $display_name . " " . $data['delivery_branch_address'] . " " . $data['delivery_branch_address1'] . " " . $data['delivery_branch_address2'] . " " . $data['delivery_branch_address3'] . " " . $data['delivery_branch_town_city'] . " " . $data['delivery_branch_county'] . " " . $data['delivery_branch_postcode'];
		} else if ($data['order_can_send_to'] == '1' && $data['delivery_practice_id'] > 0) {
			$display_name = $data['delivery_practice_name'] . " " . $data['delivery_practice_last_name'];
			$full_address = $display_name . " " . $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
		} else if ($data['order_can_send_to'] == '1' &&  $data['branch_id'] > 0) {
			$display_name = $data['branch_name'];
			$full_address =  $display_name . " " . $data['branch_address'] . " " . $data['branch_address1'] . " " . $data['branch_address2'] . " " . $data['branch_address3'] . " " . $data['town_city'] . $data['county'] . " " . $data['branch_postcode'];
		} else if($data['order_can_send_to'] == '1') {
			$display_name = $data['practice_name'] . " " . $data['practice_last_name'];
			$full_address = $display_name . " " . $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
		}

		/**Practice name and address details */
		$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
		$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
		$p_column_field = explode('|', $p_usersDetails['column_field']);
		$p_address_2 = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
		$p_address_3 = isset($p_column_field[1]) ? $p_column_field[1] : NULL;
		$p_account_ref = isset($p_column_field[2]) ? $p_column_field[2] : NULL;
		$p_add_1 = isset($p_column_field[3]) ? $p_column_field[3] : NULL;
		$p_add_2 = isset($p_column_field[4]) ? $p_column_field[4] : NULL;
		$p_add_3 = isset($p_column_field[5]) ? $p_column_field[5] : NULL;
		$p_add_4 = isset($p_column_field[6]) ? $p_column_field[6] : NULL;

		$order_type =$data['order_type'];
		if ($data['lab_id'] > 0 && $data['plc_selection'] == '2') {
			$account_number_label = 'Lab Account number';
			$client_id = !empty($l_account_ref) ? $l_account_ref : null;
			$p_account_ref = $data['reference_number'];
			$p_display_name = $data['lab_name'];

			$display_address = $l_address_1;
			$display_address_1 = $l_address_2;
			$display_address_2 = $l_address_3;
			$display_address_3 = $l_address_4;
			$display_address_town_city = $l_town_city;
			$display_address_county = $data['country_name'];
			$display_address_postcode = $l_post_code;

			$lab_order = 'Lab';
			$postal_code = $l_post_code;
		} else if ($data['branch_id'] > 0) {
			$p_display_name = $data['branch_name'];
			$client_id = !empty($p_account_ref) ? $p_account_ref : $data['branch_customer_number'];
			$p_account_ref = $data['reference_number'];
			$display_address =  $data['branch_address'];
			$display_address_1 = $data['branch_address1'];
			$display_address_2 = $data['branch_address2'];
			$display_address_3 = $data['branch_address3'];
			$display_address_town_city = $data['town_city'];
			$display_address_county = $data['county'];
			$display_address_postcode = $data['branch_postcode'];
			$postal_code = $data['branch_postcode'];
		} else {
			$p_display_name = $data['practice_name'] . " " . $data['practice_last_name'];
			$client_id = $p_account_ref;
			$p_account_ref = $data['reference_number'];
			$display_address = $p_add_1;
			$display_address_1 = $p_add_2;
			$display_address_2 = $p_add_3;
			$display_address_3 = $p_add_4;
			$display_address_town_city = $p_address_2;
			$display_address_county = $data['country_name'];
			$display_address_postcode = $p_address_3;
			$postal_code = $p_address_3;
		}
		/**Practice name and address details */

        //email content
		if($data['plc_selection']=='1'){
			$content_data['order_number'] = $data['order_number'];
		}else{
			$content_data['order_number'] = $data['reference_number'];
		}

		$send_to_account_ref = '';
		if($data['lab_id'] > 0){
			if($data['order_can_send_to'] == '1'){
				$userData2 = array("user_id" => $data['delivery_practice_id'], "column_name" => "'account_ref'");
				$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
			}else{
				$userData1 = array("user_id" => $data['lab_id'], "column_name" => "'account_ref'");
				$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
				$refDetails = array_column($refDetails, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
			}
		}else{
			if($data['order_can_send_to'] == '1'){
				$userData2 = array("user_id" => $data['delivery_practice_id'], "column_name" => "'account_ref'");
				$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
			}else{
				$userData2 = array("user_id" => $data['vet_user_id'], "column_name" => "'account_ref'");
				$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
			}
		}

		$content_data = array(
			'order_type'=> $order_type, 'account_number_label' => $account_number_label, 'client_id' => $client_id, 
			'order_number' => $data['order_number'], 'account_ref' => $p_account_ref, 'qty_order' => $data['qty_order'],
			'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'],
			'pet_name' => $data['pet_name'], 'total' => $total, 'active_uk' => $active_uk,
			'veterinarian_first' => $data['practice_name'], 'veterinarian_last' => $data['practice_last_name'], 'veterinarian_email' => $data['practice_email'],
			'veterinarian_phone' => $data['branch_number'], 'clinic_name' => $p_display_name, 'clinic_add' => $full_address, 
			'postal_code' => $postal_code, 'city' => $address_2, 'country' => $practice_country, 'order_sent_to' => $full_address, 
			'invoice_sent_to' => 'The clinic address above',
			'po_first' => $data['pet_owner_name'], 'po_last' => $data['po_last'], 'animal_name' => $data['pet_name'],
			'species' => $data['species_name'], 'treatment' => $treatment_txt, 'allergens' => $allergens_html, 
			'signature' => $data['signature'],
			'your_name' => $data['name'], 'your_email' => $data['email'], 'your_number' => $data['phone_number'],
			'customer_number' => $data['customer_number'],
			'branch_customer_number'=>$data['branch_customer_number'], 
			'total_allergens' => $total_allergen, 'display_address' => $display_address,
			'display_address_1' => $display_address_1, 'display_address_2' => $display_address_2, 'display_address_3' => $display_address_3,
			'display_address_town_city' => $display_address_town_city, 'display_address_county' => $display_address_county,
			'display_address_postcode' => $display_address_postcode, 'lab_order' => $lab_order,
			'plc_selection'=>$data['plc_selection'],'practice_lab_comment'=>$data['practice_lab_comment'],'send_to_account_ref'	=> $send_to_account_ref
		);
        //save pdf
        $dompdf = new Dompdf(array('enable_remote' => true));
        $html = $this->load->view('orders/order_mail_template',$content_data,true);
        $html = trim($html); 

		// echo $html;die;
		if($data['plc_selection']=='1'){
			$content_data['order_number'] = $data['order_number'];
		}else{
			$content_data['order_number'] = $data['reference_number'];
		}

        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4', 'Portrait');
        $dompdf->render();
        $pdf = $dompdf->output();

        $file = FCPATH.ORDERS_PDF_PATH."order_".$content_data['order_number'].".pdf";
        file_put_contents($file, $pdf);
        $sicdoc = FCPATH . SIC_DOC_PATH . "/" . $data['sic_document'];
        $attach_pdf = base_url().ORDERS_PDF_PATH."order_".$content_data['order_number'].".pdf";
        $this->load->view('orders/order_mail_content_template', $content_data); //no exit for view template

        $config = array(
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);

        //Load email library 
        $this->load->library('email',$config); 
		$this->email->from($from_email, 'Nextmune');
		$content_data['recipient_name'] = "Dear " . $data['name'];
		$content_data['content_body'] = 'Thank you for your order.<br><br>
		This will now be processed and we will be in touch if we have any further questions. You will find a summary of your order in the attached PDF.<br><br>
		Please allow 10-14 working days to receive your order.';
		if($data['lab_id'] > 0 && $data['lab_id'] == '13786'){
			$to_email = 'immunotherapy@axiomvetlab.co.uk';
		}elseif($data['lab_id'] > 0 && $data['lab_id'] == '13788'){
			$to_email = 'admin@nwlabs.co.uk';
		}else{
			$to_email = $data['email'];
		}

		$this->email->to($to_email);
		$this->email->subject('Order Details - '.$content_data['order_number']); 
		$msg_content = $this->load->view('orders/order_mail_content_template',$content_data,true);
		$this->email->message($msg_content);
		$this->email->set_mailtype("html");
		$this->email->attach($file);
		if ($data['sic_document'] != '') {
			$this->email->attach($sicdoc);
		}
		$is_send = $this->email->send();
        //Send mail 
        if($is_send) {
			$zonesIds = $this->checkZones($id);
			if(!empty($zonesIds) && !in_array("1", $zonesIds)){
				$zoneEmail = $this->getZoneEmail($zonesIds);
				if(!empty($zoneEmail)){
					$this->email->from($from_email, 'Nextmune');
					$this->email->to($zoneEmail);
					$this->email->subject('Order Details - '.$content_data['order_number']); 
					$msgContent = $this->load->view('orders/order_mail_content_template',$content_data,true);
					$this->email->message($msgContent);
					$this->email->set_mailtype("html");
					$this->email->send();
				}
			}
			
            $orderData['id'] = $id;
            $orderData['is_mail_sent'] = 1;
            $this->OrdersModel->add_edit($orderData);
            $this->session->set_flashdata("success","Email sent successfully."); 
            redirect('orders');
        }else{ 
            $this->session->set_flashdata("error", $this->email->print_debugger());
            redirect('orders'); 
        }
        exit;
    }

	function getZoneEmail($id){
		$this->db->select('managed_by_email');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.implode(",",$id).')');
		$zoneData = $this->db->get()->row();
		return !empty($zoneData->managed_by_email)?$zoneData->managed_by_email:'';
	}

	function getZoneFromEmail($id){
		$this->db->select('managed_by_from_email');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.implode(",",$id).')');
		$zoneData = $this->db->get()->row();
		return !empty($zoneData->managed_by_from_email)?$zoneData->managed_by_from_email:'';
	}

	function checkZones($id){
		$this->db->select('vet_user_id,lab_id');
		$this->db->from('ci_orders');
		$this->db->where('id', $id);
		$ordrData = $this->db->get()->row();
		if($ordrData->lab_id > 0){
			$userID = $ordrData->lab_id;
		}else{
			$userID = $ordrData->vet_user_id;
		}

		$this->db->select('managed_by_id,country');
		$this->db->from('ci_users');
		$this->db->where('id', $userID);
		$userData = $this->db->get()->row();
		if($userData->managed_by_id != ''){
			if(count(explode(",",$userData->managed_by_id)) > 1){
				$this->db->select('managed_by_id');
				$this->db->from('ci_staff_countries');
				$this->db->where('id', $userData->country);
				$cuntryData = $this->db->get()->row();
				if($cuntryData->managed_by_id != ''){
					return explode(",",$cuntryData->managed_by_id);
				}else{
					return '0';
				}
			}else{
				return explode(",",$userData->managed_by_id);
			}
		}else{
			return '0';
		}
	}

}
