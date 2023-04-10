<?php
require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf as Dompdf;
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
class Orders extends CI_Controller{

	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '256M');
		if ($this->session->userdata('logged_in') !== TRUE) {
			redirect('users/index');
		}
		$this->lang->load('serum_result', $this->session->userdata('site_lang'));
		$this->user_id = $this->session->userdata('user_id');
		$this->user_role = $this->session->userdata('role');
		$this->user_country = $this->session->userdata('country');
		$this->load->model('OrdersModel');
		$this->load->model('UsersModel');
		$this->load->model('PetsModel');
		$this->load->model('UsersDetailsModel');
		$this->load->model('AllergensModel');
		$this->load->model('BreedsModel');
		$this->load->model('SpeciesModel');
		$this->load->model('PriceCategoriesModel');
		$this->load->model('CountriesModel');
		$this->_data['fetch_class'] = $this->router->fetch_class();
		$this->_data['fetch_method'] = $this->router->fetch_method();
		$this->_data['countries'] = $this->CountriesModel->getRecordAll();
	}

	function list(){
		if(!empty($this->uri->segment(2))){
			$this->session->set_userdata('orderFilterId',$this->uri->segment(2));
		}else{
			$this->session->set_userdata('orderFilterId',"");
		}

		$this->load->view('orders/index');
	}

	function track_order($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/track_order", $this->_data);
	}

	function getTableData(){
		$Orders = $this->OrdersModel->getTableData();
		if (!empty($Orders)) {
			foreach ($Orders as $key => $value) {
				$Orders[$key]->pet_owner_name = $value->pet_owner_name .' '. $value->po_last;
				if($value->pet_id > 0){
					$this->db->select('type,gender');
					$this->db->from('ci_pets');
					$this->db->where('id', $value->pet_id);
					$petinfo = $this->db->get()->row_array();
					$petType = '';
					if($petinfo['type'] == 1){
					$petType = ' <b>(C)</b>';
					}elseif($petinfo['type'] == 2){
					$petType = ' <b>(D)</b>';
					}elseif($petinfo['type'] == 3){
					$petType = ' <b>(H)</b>';
					}
					$Orders[$key]->pet_name = $value->pet_name.$petType;
				}else{
				$Orders[$key]->pet_name = $value->pet_name;	
				}
				if (!empty($value->order_date)) {
					$Orders[$key]->order_date = date('d/m/Y', strtotime($value->order_date));
				}
				if (!empty($value->sampling_date)) {
					$Orders[$key]->sampling_date = date('d/m/Y', strtotime($value->sampling_date));
				}
				
				if (!empty($value->batch_number)) {
					$Orders[$key]->batch_number = $value->batch_number;
				}else{
					$Orders[$key]->batch_number = $value->lab_order_number;
				}
				$Orders[$key]->order_type_id = $value->order_type;
				if ($value->order_type == 1) {
					$Orders[$key]->order_type = 'Immunotherapy';
				} elseif ($value->order_type == 2) {
					if(!empty($value->product_code_selection)){
						$this->db->select('name');
						$this->db->from('ci_price');
						$this->db->where('id', $value->product_code_selection);
						$ordeType = $this->db->get()->row()->name;
						$Orders[$key]->order_type = 'Serum Testing <b>('.$ordeType.')</b>';
					}else{
						$Orders[$key]->order_type = 'Serum Testing';
					}
				} else {
					$Orders[$key]->order_type = 'Skin Test';
				}
				if ($value->lab_id > 0) {
					$Orders[$key]->final_name = $value->lab_name;
				} elseif ($value->vet_user_id > 0) {
					$Orders[$key]->final_name = $value->practice_first_name;
				} else {
					$Orders[$key]->final_name = '';
				}
				$orderHistory = $this->OrdersModel->getLastOrderHistory($value->id);
				if(!empty($orderHistory)){
					$Orders[$key]->updated_at = date('d/m/Y H:i:s', strtotime($orderHistory->created_at));
				}else{
					$Orders[$key]->updated_at = date('d/m/Y H:i:s', strtotime($value->updated_at));
				}
				if ($this->user_role == 1 || ($this->user_role == '5' && $this->session->userdata('user_type') == '3')){
					$Orders[$key]->comment = !empty($value->comment)?$value->comment:$value->practice_lab_comment;
				}else if($value->comment_by == $this->user_id){
					$Orders[$key]->comment = $value->practice_lab_comment;
				}else{
					$Orders[$key]->comment = '';
				}
				if ($value->pet_id > 0) {
					$breedData = $this->OrdersModel->getPetbreeds($value->pet_id);
					$breedName = !empty($breedData)?$breedData:$value->other_breed;
					$Orders[$key]->breed_id = $breedName;
				}else{
					$Orders[$key]->breed_id = '';
				}
			}
		}

		$total = $this->OrdersModel->count_all();
		$totalFiltered = $this->OrdersModel->count_filtered();

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Orders;
		echo json_encode($ajax);
		exit();
	}

	function orderType($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if($this->user_role == 1){
			$this->_data['screenings'] = $this->PriceCategoriesModel->getRecordAll("32");
			unset($orderData["order_type"]);
			unset($orderData["sub_order_type"]);
			unset($orderData["plc_selection"]);
			unset($orderData["species_selection"]);
			unset($orderData["product_code_selection"]);
			unset($orderData["single_double_selection"]);
			unset($orderData["screening"]);
			unset($orderData["serum_type"]);
			if (!empty($this->input->post())) {
				$species = '';
				if($this->input->post('species_selection') == 1){
					$species = 'Dog';
				}elseif($this->input->post('species_selection') == 2){
					$species = 'Horse';
				}elseif($this->input->post('species_selection') == 3){
					$species = 'Cat';
				}
				if($this->input->post('order_type') == 1){
					$orderProcess = array(
						'order_type'		=> $this->input->post('order_type'),
						'plc_selection'		=> $this->input->post('plc_selection'),
						'sub_order_type'	=> 1,
						'product_code_selection'	=> $this->input->post('product_code_selection'),
						'single_double_selection'	=> ''
					);
				}elseif($this->input->post('order_type') == 2){
					if($this->input->post('serum_type') == 1){
						$orderProcess = array(
							'order_type'		=> $this->input->post('order_type'),
							'species_selection'	=> 1,
							'species'			=> 'Dog',
							'plc_selection'		=> $this->input->post('plc_selection'),
							'sub_order_type'	=> 3,
							'serum_type'		=> $this->input->post('serum_type'),
							'screening'			=> $this->input->post('pax_type'),
							'product_code_selection'	=> $this->input->post('screening'),
							'single_double_selection'	=> $this->input->post('single_double_selection')
						);
					}else{
						$orderProcess = array(
							'order_type'		=> $this->input->post('order_type'),
							'species_selection'	=> $this->input->post('species_selection'),
							'species'			=> $species,
							'plc_selection'		=> $this->input->post('plc_selection'),
							'sub_order_type'	=> 3,
							'serum_type'		=> $this->input->post('serum_type'),
							'screening'			=> $this->input->post('screening'),
							'product_code_selection'	=> $this->input->post('product_code_selection'),
							'single_double_selection'	=> $this->input->post('single_double_selection')
						);
					}
				}elseif($this->input->post('order_type') == 3){
					$orderProcess = array(
						'order_type'		=> $this->input->post('order_type'),
						'species_selection'	=> $this->input->post('species_selection'),
						'species'			=> $species,
						'plc_selection'		=> $this->input->post('plc_selection'),
						'sub_order_type'	=> 4,
						'serum_type'		=> $this->input->post('serum_type'),
						'screening'			=> $this->input->post('screening'),
						'product_code_selection'	=> $this->input->post('product_code_selection'),
						'single_double_selection'	=> $this->input->post('single_double_selection')
					);
				}
				$this->session->set_userdata($orderProcess);
				redirect('orders/addEdit/'. $id);
			}
		}else{
			if ($this->input->post('order_type')) {
				$orderProcess = array(
					'order_type'    => $this->input->post('order_type')
				);
				$this->session->set_userdata($orderProcess);

				if($this->input->post('order_type') != 2 && ($this->user_role == 2 || $this->user_role == 5 || $this->user_role == 6 || $this->user_role == 7)){
					$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','branches','labs','lab_branches','corporates','corporate_branches') AND user_id = '". $this->user_id ."'";
					$responce = $this->db->query($sql);
					$userIds = $responce->result_array();
					$LabDetails = array_column($userIds, 'column_field', 'column_name');
					$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
					$branches = !empty($LabDetails['branches']) ? json_decode($LabDetails['branches']) : 0;
					$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;
					if($practices != 0){
						$orderProcess = array('plc_selection' => 1,'sub_order_type'=> 1);
						$this->session->set_userdata($orderProcess);
					}elseif($branches != 0){
						$orderProcess = array('plc_selection' => 1,'sub_order_type'=> 1);
						$this->session->set_userdata($orderProcess);
					}elseif($labs != 0){
						$orderProcess = array('plc_selection' => 2,'sub_order_type'=> 1);
						$this->session->set_userdata($orderProcess);
					}
					redirect('orders/addEdit/'. $id);
				}else{
					if($this->input->post('order_type') == 1){
						redirect('orders/sub_order_type/'. $id);
					}elseif($this->input->post('order_type') == 2){
						if($this->user_role == 1){
							redirect('orders/serum_type/'. $id);
						}else{
							if($this->user_country != "1"){
								$orderProcess = array(
									'serum_type'    => 1
								);
								$this->session->set_userdata($orderProcess);
								redirect('orders/screening/'. $id);
							}else{
								redirect('orders/serum_type/'. $id);
							}
						}
					}elseif($this->input->post('order_type') == 3){
						redirect('orders/addEdit/'. $id);
					}else{
						redirect('orders/plc_selection/' . $id);
					}
				}
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}

		if($this->user_role == 1){
			$this->load->view("orders/order_type_admin", $this->_data);
		}else{
			$this->load->view("orders/order_type", $this->_data);
		}
	}

	function serum_type($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if ($this->input->post('serum_type')) {
			$orderProcess = array(
				'serum_type'    => $this->input->post('serum_type')
			);
			$this->session->set_userdata($orderProcess);
			if($this->input->post('serum_type') == 1){
				redirect('orders/screening/'. $id);
			}else{
				redirect('orders/species_selection/' . $id);
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}

		$this->load->view("orders/serum_type", $this->_data);
	}

	function screening($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		$this->_data['product_codes'] = $this->PriceCategoriesModel->getRecordAll("32");
		if ($this->input->post('screening')) {
			$orderProcess = array(
				'screening'    => $this->input->post('screening'),
				'species_selection' => 1,
				'species'    => 'Dog',
				'product_code_selection' => $this->input->post('product_code_selection')
			);
			$this->session->set_userdata($orderProcess);
			if($this->user_role == 2 || $this->user_role == 5 || $this->user_role == 6 || $this->user_role == 7){
				$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','labs') AND user_id = '". $this->user_id ."'";
				$responce = $this->db->query($sql);
				$userIds = $responce->result_array();
				$LabDetails = array_column($userIds, 'column_field', 'column_name');
				$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
				$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;
				if($practices != 0){
					$orderProcess = array('plc_selection' => 1,'sub_order_type'=> 1);
					$this->session->set_userdata($orderProcess);
					redirect('orders/addEdit/'. $id);
				}elseif($labs != 0){
					$orderProcess = array('plc_selection' => 2,'sub_order_type'=> 1);
					$this->session->set_userdata($orderProcess);
					redirect('orders/addEdit/'. $id);
				}else{
					redirect('orders/plc_selection/' . $id);
				}
			}else{
				redirect('orders/plc_selection/' . $id);
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/screening", $this->_data);
	}

	function sub_order_type($id = ''){
		// new code 11-03-2022
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		$orderProcess = array(
			'sub_order_type'=> 1
		);
		$this->session->set_userdata($orderProcess);
		redirect('orders/plc_selection/' . $id);

		// old code
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if ($this->input->post('sub_order_type')) {
			$orderProcess = array(
				'sub_order_type'    => $this->input->post('sub_order_type')
			);
			$this->session->set_userdata($orderProcess);
			if ($this->input->post('sub_order_type') == 2) {
				redirect('orders/single_double_selection/' . $id);
			} else if ($this->user_role == 2 || $this->user_role == 5 || $this->user_role == 6 || $this->user_role == 7) {
				redirect('orders/addEdit/' . $id);
			} else {
				redirect('orders/plc_selection/' . $id);
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/sub_order_type", $this->_data);
	}

	function species_selection($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if ($this->input->post('species_selection')) {
			if($this->input->post('species_selection') == 3){
				$orderProcess = array(
					'species_selection' => $this->input->post('species_selection'),
					'species'    => 'Cat'
				);
			}elseif($this->input->post('species_selection') == 2){
				$orderProcess = array(
					'species_selection' => $this->input->post('species_selection'),
					'species'    => 'Horse'
				);
			}elseif($this->input->post('species_selection') == 1){
				$orderProcess = array(
					'species_selection'    => $this->input->post('species_selection'),
					'species'    => 'Dog'
				);
			}else{
				$orderProcess = array(
					'species_selection'    => $this->input->post('species_selection'),
					'species'    => ''
				);
			}
			$this->session->set_userdata($orderProcess);
			redirect('orders/product_code_selection/' . $id);
		}
		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/species_selection", $this->_data);
	}

	function getProductCode(){
		$speciesData = $this->input->post();
		if($speciesData['species_selection'] == '3'){
			$species_selection = '22';
		}elseif($speciesData['species_selection'] == '1'){
			$species_selection = '1';
		}elseif($speciesData['species_selection'] == '2'){
			$species_selection = '2';
		}
		$product_codes = $this->PriceCategoriesModel->getRecordAll($species_selection);
        echo json_encode($product_codes); exit();
	}

	function product_code_selection($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if($this->session->userdata('species_selection') == '3' && $this->session->userdata('species') == 'Cat'){
			$species_selection = '22';
		}elseif($this->session->userdata('species_selection') == '1' && $this->session->userdata('species') == 'Dog'){
			$species_selection = '1';
		}elseif($this->session->userdata('species_selection') == '2' && $this->session->userdata('species') == 'Horse'){
			$species_selection = '2';
		}else{
			$species_selection = $this->session->userdata('species_selection');
		}
		$this->_data['product_codes'] = $this->PriceCategoriesModel->getRecordAll($species_selection);
		if ($this->input->post('product_code_selection')) {
			$orderProcess = array(
				'product_code_selection'    => $this->input->post('product_code_selection')
			);

			$this->session->set_userdata($orderProcess);
			if ($this->user_role == 2 || $this->user_role == 5 || $this->user_role == 6 || $this->user_role == 7) {
				$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','labs') AND user_id = '". $this->user_id ."'";
				$responce = $this->db->query($sql);
				$userIds = $responce->result_array();
				$LabDetails = array_column($userIds, 'column_field', 'column_name');
				$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
				$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;
				if($practices != 0){
					$orderProcess = array('plc_selection' => 1,'sub_order_type'=> 1);
					$this->session->set_userdata($orderProcess);
					redirect('orders/addEdit/'. $id);
				}elseif($labs != 0){
					$orderProcess = array('plc_selection' => 2,'sub_order_type'=> 1);
					$this->session->set_userdata($orderProcess);
					redirect('orders/addEdit/'. $id);
				}else{
					redirect('orders/plc_selection/' . $id);
				}
			} else {
				redirect('orders/plc_selection/' . $id);
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/product_code_selection", $this->_data);
	}

	function single_double_selection($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if ($this->input->post('single_double_selection')) {
			$orderProcess = array(
				'single_double_selection'    => $this->input->post('single_double_selection')
			);
			$this->session->set_userdata($orderProcess);
			if ($this->user_role == 2 || $this->user_role == 5 || $this->user_role == 6 || $this->user_role == 7) {
				redirect('orders/addEdit/' . $id);
			} else {
				redirect('orders/plc_selection/' . $id);
			}
		}

		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/single_double_selection", $this->_data);
	}

	function plc_selection($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['id'] = $id;
		if ($this->input->post('plc_selection')) {
			$orderProcess = array(
				'plc_selection'    => $this->input->post('plc_selection')
			);
			$this->session->set_userdata($orderProcess);
			redirect('orders/addEdit/' . $id);
		}
		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/plc_selection", $this->_data);
	}

	function addEdit($id = ''){
		$orderData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$this->_data['controller'] = $this->router->fetch_class();
		$this->_data['tm_vet_user'] = '';
		$role_id = "2";
		$data = $this->OrdersModel->getRecord($id);

		/* set session for edit page */
		if ($id > 0) {
			$orderProcess = array(
				'order_type'    => $data['order_type'],
				'sub_order_type' => $data['sub_order_type'],
				'plc_selection' => $data['plc_selection'],
				'species_selection' => $data['species_selection'],
				'product_code_selection' => $data['product_code_selection'],
				'single_double_selection' => $data['single_double_selection']
			);
			$this->session->set_userdata($orderProcess);
		}
		/* set session for edit page */

		/* when skin test selected */
		if ($this->session->userdata('order_type') == '3') {
			$orderProcess = array(
				'plc_selection'    => '1'
			);
			$this->session->set_userdata($orderProcess);
		}

		if ($this->user_role == '2') {
			$vetUserData['vet_user_id'] = $this->user_id;
			$orderProcess = array(
				'plc_selection'    => '1'
			);
			$this->session->set_userdata($orderProcess);
		} else if ($this->user_role == '6') {
			$vetUserData['lab_id'] = $this->user_id;
			$orderProcess = array(
				'plc_selection'    => '2'
			);
			$this->session->set_userdata($orderProcess);
		} else if ($this->user_role == '7') {
			$vetUserData['corporate_id'] = $this->user_id;
			$orderProcess = array(
				'plc_selection'    => '3'
			);
			$this->session->set_userdata($orderProcess);
		} else if ($this->user_role == '5') {
			$this->db->select("*");
			$this->db->from('ci_user_details');
			$this->db->where("column_name IN('labs','practices','corporates')");
			$this->db->where('user_id', $this->user_id);
			$refDetails = $this->db->get()->result_array();
			$columnField = array_column($refDetails, 'column_field', 'column_name');
			$tm_vet_user = isset($columnField['practices']) ? $columnField['practices'] : NULL;
			$tm_labs = isset($columnField['labs']) ? $columnField['labs'] : NULL;
			$tm_corporates = isset($columnField['corporates']) ? $columnField['corporates'] : NULL;

			$tm_vet_user = ($tm_vet_user != NULL) ? implode(",", json_decode($tm_vet_user)) : 0;
			$tm_labs = ($tm_labs != NULL) ? implode(",", json_decode($tm_labs)) : 0;
			$tm_corporates = ($tm_corporates != NULL) ? implode(",", json_decode($tm_corporates)) : 0;
			if ($tm_vet_user != 0) {
				$orderProcess = array(
					'plc_selection'    => '1'
				);
			} else if ($tm_labs != 0) {
				$orderProcess = array(
					'plc_selection'    => '2'
				);
			} else if ($tm_corporates != 0) {
				$orderProcess = array(
					'plc_selection'    => '3'
				);
			}
			$this->session->set_userdata($orderProcess);
			$vetUserData['vet_user_id'] = $tm_vet_user;
			$this->_data['tm_vet_user'] = $tm_vet_user;
		} else {
			$vetUserData['vet_user_id'] = $data['vet_user_id'];
		}

		$vetUserData['branch_id'] = $data['branch_id'];
		if ($data['pet_owner_id'] > 0) {
			$petOwnerData['is_petOwner'] = true;
			$petOwnerData['pet_owner_id'] = $data['pet_owner_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
		} else {
			$petOwnerData['is_petOwner'] = false;
			$petOwnerData['pet_owner_id'] = $data['vet_user_id'];
			$petOwnerData['pet_id'] = $data['pet_id'];
		}

		$labsData['vet_user_id'] = $data['lab_id'];
		$corporatesData['vet_user_id'] = $data['corporate_id'];
		if ($this->user_role == '5') {
			if ($tm_vet_user != 0) {
				$this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id, $this->user_id, $this->user_role, "practices");
				$this->_data['branches'] = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
			}
			if ($tm_labs != 0) {
				$this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
				$this->_data['labs'] = $this->UsersModel->getRecordAll("6", $this->user_id, $this->user_role, "labs");
			}
			if ($tm_corporates != 0) {
				$this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
				$this->_data['corporates'] = $this->UsersModel->getRecordAll("7", $this->user_id, $this->user_role, "corporates");
			}
		} else {
			$this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll($role_id);
			$this->_data['branches'] = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
			$this->_data['labs'] = $this->UsersModel->getRecordAll("6");
			$this->_data['corporates'] = $this->UsersModel->getRecordAll("7");
		}

		$this->_data['pet_owners'] = $this->UsersModel->get_petOwner_dropdown($vetUserData);
		$this->_data['pets'] = $this->PetsModel->get_pets_dropdown($petOwnerData);
		$this->_data['lab_branches'] = $this->UsersDetailsModel->get_branch_dropdown($labsData);
		$this->_data['corporate_branches'] = $this->UsersDetailsModel->get_branch_dropdown($corporatesData);
		//$this->_data['breeds'] = $this->BreedsModel->get_breeds_dropdown();
		$this->_data['species'] = $this->SpeciesModel->getRecordAll();
		if ($this->session->userdata('order_type') == '2') {
			$sub_order_type = '3';
		} elseif ($this->session->userdata('order_type') == '3') {
			$sub_order_type = '4';
		} else {
			$sub_order_type = $this->session->userdata('sub_order_type');
		}
		$this->_data['sub_order_type'] = $sub_order_type;
		$this->_data['order_type'] = $this->session->userdata('order_type');
		$this->load->model('StaffMembersModel');
		$this->_data['staff_members'] = $this->StaffMembersModel->getRecordAll();
		$this->_data['deliveryPractices'] = $this->UsersModel->getDeliveryPractices("2");

		if (!empty($this->input->post())) {
			$orderData = $this->input->post();
			$orderData['id'] = $id;
			$this->db->select('user.id,country.name');
			$this->db->from('ci_users AS user');
			$this->db->join('ci_staff_countries AS country', 'country.id=user.country','left');
			if($orderData['lab_id'] > 0){
				$this->db->where('user.id',$orderData['lab_id']);
			}else{
				$this->db->where('user.id',$orderData['vet_user_id']);
			}
			$orderCountry = $this->db->get()->row_array();
			$orderData['order_country'] = $orderCountry['name'];
			if ($this->session->userdata('order_type') == '2') {
				$sub_order_type = '3';
				$orderData['screening'] = !empty($this->session->userdata('screening'))?$this->session->userdata('screening'):NULL;
				$orderData['serum_type'] = !empty($this->session->userdata('serum_type'))?$this->session->userdata('serum_type'):NULL;
				$orderData['shipping_materials'] = ($this->input->post('shipping_materials') != '') ? '1' : '0';
				$orderData['qty_order'] = ($this->input->post('qty_order') != '')?$this->input->post('qty_order'):'0';
				$orderData['lab_order_number'] = $this->input->post('lab_order_number');
				$replaced_sampling_date = str_replace('/', '-', $this->input->post('sampling_date'));
				$orderData['sampling_date'] = ($this->input->post('sampling_date') != '') ? date("Y-m-d", strtotime($replaced_sampling_date)) : NULL;
				$orderData['veterinary_surgeon'] = ($this->input->post('veterinary_surgeon') != '') ? $this->input->post('veterinary_surgeon') : '';
				$orderData['samples_storage'] = ($this->input->post('samples_storage') != '') ? '1' : '0';
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
				$orderData['vet_user_id'] = ($this->input->post('vet_user_id') > 0) ? $this->input->post('vet_user_id') : $tm_vet_user;
			}
			$orderData['branch_id'] = ($this->input->post('branch_id') > 0) ? $this->input->post('branch_id') : NULL;
			$orderData['order_type'] = $this->session->userdata('order_type');
			$orderData['sub_order_type'] = $sub_order_type;
			$orderData['species_selection'] = $this->session->userdata('species_selection');
			$orderData['single_double_selection'] = $this->session->userdata('single_double_selection');
			$orderData['product_code_selection'] = $this->session->userdata('product_code_selection');
			$orderData['plc_selection'] = $this->session->userdata('plc_selection');

			$orderData['delivery_practice_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_id') : 0;
			$orderData['delivery_practice_branch_id'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('delivery_practice_branch_id') : 0;

			/* lab other delivery address */
			$orderData['address1'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address1') : NULL;
			$orderData['address2'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address2') : NULL;
			$orderData['address3'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address3') : NULL;
			$orderData['address4'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('address4') : NULL;
			$orderData['town_city'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('town_city') : NULL;
			$orderData['county'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('county') : NULL;
			$orderData['country'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('country') : NULL;
			$orderData['postcode'] = ($this->input->post('order_can_send_to') == '1') ? $this->input->post('postcode') : NULL;
			/* lab other delivery address */

			/* upload sic_document start */
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
			/* upload sic_document end */

			/* upload email_upload start */
			if ($_FILES["email_upload"]["name"] != '') {
				$config = array();
				$temp_name = explode(".", $_FILES["email_upload"]["name"]);

				$config['upload_path']          = EMAIL_UPLOAD_PATH;
				$config['allowed_types']        = 'msg|eml';
				$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);

				$this->load->library('upload');

				$this->upload->initialize($config);
				if (!$this->upload->do_upload('email_upload')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect('orders/addEdit/');
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['email_upload'] = $upload_data['upload_data']['file_name'];
				}
			}
			/* upload email upload end */

			/* upload requisition_form start */
			if ($this->user_role == '1' || $this->session->userdata('order_type') == '2') {
				if($_FILES["requisition_form"]["name"] != ''){
					$temp_name = explode(".", $_FILES["requisition_form"]["name"]);
					$config['upload_path']	= REQUISITION_FORM_PATH;
					$config['allowed_types']= 'pdf';
					$config['file_name']	= preg_replace('/\s+/',  '_',  strtolower($temp_name[0]) . '_' . time() . '.' . $temp_name[1]);

					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('requisition_form')) {
						$error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('error', $error['error']);
						redirect('orders/addEdit/'.$id);
					} else {
						$upload_data = array('upload_data' => $this->upload->data());
						$orderData['requisition_form'] = $upload_data['upload_data']['file_name'];
					}
				}
			}
			/* upload requisition_form end */

			if (is_numeric($id) > 0) {
				if ($this->user_role == 1) {
					$replaced_date = str_replace('/', '-', $this->input->post('order_date'));
					$orderData['order_date'] = date("Y-m-d", strtotime($replaced_date));
				}

				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");

				/* unset key from array edit time */
				unset($orderData["order_type"]);
				unset($orderData["sub_order_type"]);
				unset($orderData["plc_selection"]);
				unset($orderData["species_selection"]);
				unset($orderData["product_code_selection"]);
				unset($orderData["single_double_selection"]);
				unset($orderData["lab_order_number"]);
				unset($orderData["screening"]);
				unset($orderData["serum_type"]);
				unset($orderData['save']);
				unset($orderData['next']);
				/* unset key from array edit time */

				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0 && $this->input->post('save')=='save') {
					$this->updatOrderPDF($id);
					redirect('orders');
				}else {
					redirect('orders/allergens/' . $id);
				}
			} else {
				$orderData['is_draft'] = 1;
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
				unset($orderData['save']);
				unset($orderData['next']);
				if ($ins_id = $this->OrdersModel->add_edit($orderData) && $this->input->post('save')) {
					$this->updatOrderPDF($ins_id);
					//$this->session->set_flashdata('success','Order data has been added successfully.');
					redirect('orders');
				}else if($ins_id = $this->OrdersModel->add_edit($orderData)){
					//$this->session->set_flashdata('success','Order data has been added successfully.');
					redirect('orders/allergens/' . $ins_id);
				}
			}
		}

		/* load data edit time */
		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$deliveryData['vet_user_id'] = $data['delivery_practice_id'];
				$this->_data['practice_branches'] = $this->UsersDetailsModel->get_branch_dropdown($deliveryData);
				$this->_data['data'] = $data;
			}
		}
		$this->load->view("orders/add_edit", $this->_data);
	}

	public function updatOrderPDF($id = ''){
		$this->load->model('RecipientsModel');
		$data = $this->OrdersModel->allData($id);
		$this->OrdersModel->IsDraftUpdate($id);
		if ($data['order_type'] == '2'){
			redirect('orders');
			exit;
		}

		$email_upload = FCPATH . EMAIL_UPLOAD_PATH . '/' . $data['email_upload'];
		$account_number_label = 'Practice Account number';

		$total_allergen = ($data['allergens'] != '') ? count(json_decode($data['allergens'])) : 0;
		if ($data['order_can_send_to'] == '1') {
			$delivery_practice = $data['delivery_practice_id'];
		} else {
			$delivery_practice = $data['vet_user_id'];
		}

		//is repeat order
		if ($data['is_repeat_order'] == '1') {
			$treatment_txt = "Maintenance Order";
		} else {
			$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
		// die($account_ref);

		if ($data['order_can_send_to'] == 1 || $data['order_can_send_to'] == '') {
			$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
		}
		$recipientArr = $this->RecipientsModel->getRecordAll($data['sub_order_type']);

		$toemailArr = [];
		$to_email = '';
		$from_email = "Noreply@nextmune.com";

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

		/**if delivery address and name should be the branch details selected or if no branches use the practice */
		/**Practice name and address details */
		$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
		$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
		$p_column_field = explode('|', $p_usersDetails['column_field']);
		$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
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
				$userData1 = array("user_id" => $data['vet_user_id'], "column_name" => "'account_ref'");
				$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData1);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
			}
		}

		$content_data = array('order_type'=> $order_type, 'account_number_label' => $account_number_label, 'client_id' => $client_id, 
			'order_number' => $data['order_number'], 
			'account_ref' => $p_account_ref,
			'qty_order' => $data['qty_order'],
			'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'], 'pet_name' => $data['pet_name'],
			'total' => $total, 'active_uk' => $active_uk, 'veterinarian_first' => $data['practice_name'],
			'veterinarian_last' => $data['practice_last_name'], 'veterinarian_email' => $data['practice_email'],
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

		$html = $this->load->view('orders/order_mail_template', $content_data, true);
		$html = trim($html);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$pdf = $dompdf->output();
		if($data['plc_selection']=='1'){
			$content_data['order_number'] = $data['order_number'];
		}else{
			$content_data['order_number'] = $data['reference_number'];
		}
		$file = FCPATH . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";
		file_put_contents($file, $pdf);
		return '';
	}

	function previewNLOrderDetails(){
		$post = $this->input->post();
		$html = '';
		if($post['order_id'] > 0){
			$id = $post['order_id'];
			$this->load->model('RecipientsModel');
			$data = $this->OrdersModel->allData($id);

			/* if Order Delivery Address is there then */
			$email_upload = FCPATH . EMAIL_UPLOAD_PATH . '/' . $data['email_upload'];
			$account_number_label = 'Practice Account number';

			$total_allergen = ($data['allergens'] != '') ? count(json_decode($data['allergens'])) : 0;
			if($data['order_can_send_to'] == '1'){
				$delivery_practice = $data['delivery_practice_id'];
			}else{
				$delivery_practice = $data['vet_user_id'];
			}

			/* is repeat order */
			if($data['is_repeat_order'] == '1'){
				$treatment_txt = "Maintenance Order";
			}else{
				$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
			$from_email = "Noreply@nextmune.com";

			$order_date = date('d/m/Y', strtotime($data['order_date']));
			$total = ($data['unit_price'] * $data['qty_order']) - $data['order_discount'];
			$practice_country = '';
			if ($data['practice_country'] == 1) {
				$practice_country = 'UK';
			} elseif ($data['practice_country'] == 2) {
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

			/* if lab order */
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

			/**if delivery address and name should be the branch details selected or if no branches use the practice */
			/**Practice name and address details */
			$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
			$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
			$p_column_field = explode('|', $p_usersDetails['column_field']);
			$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
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

			/* email content */
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
				'order_type'			=> $order_type,
				'account_number_label'	=> $account_number_label,
				'client_id'				=> $client_id, 
				'order_number'			=> $data['order_number'], 
				'account_ref'			=> $p_account_ref,
				'qty_order'				=> $data['qty_order'],
				'unit_price'			=> $data['unit_price'],
				'order_date'			=> $order_date,
				'order_discount'		=> $data['order_discount'],
				'pet_name'				=> $data['pet_name'],
				'total'					=> $total,
				'active_uk'				=> $active_uk,
				'veterinarian_first'	=> $data['practice_name'],
				'veterinarian_last'		=> $data['practice_last_name'],
				'veterinarian_email'	=> $data['practice_email'],
				'veterinarian_phone'	=> $data['branch_number'],
				'clinic_name'			=> $p_display_name,
				'clinic_add'			=> $full_address, 
				'postal_code'			=> $postal_code,
				'city'					=> $address_2,
				'country'				=> $practice_country,
				'order_sent_to'			=> $full_address, 
				'invoice_sent_to'		=> 'The clinic address above',
				'po_first'				=> $data['pet_owner_name'],
				'po_last'				=> $data['po_last'],
				'animal_name'			=> $data['pet_name'],
				'species'				=> $data['species_name'],
				'treatment'				=> $treatment_txt,
				'allergens'				=> $allergens_html, 
				'signature'				=> $data['signature'],
				'your_name'				=> $data['name'],
				'your_email'			=> $data['email'],
				'your_number'			=> $data['phone_number'],
				'customer_number'		=> $data['customer_number'],
				'branch_customer_number'=>$data['branch_customer_number'], 
				'total_allergens'		=> $total_allergen,
				'display_address'		=> $display_address,
				'display_address_1'		=> $display_address_1,
				'display_address_2'		=> $display_address_2,
				'display_address_3'		=> $display_address_3,
				'display_address_town_city' => $display_address_town_city,
				'display_address_county'	=> $display_address_county,
				'display_address_postcode'	=> $display_address_postcode,
				'lab_order'				=> $lab_order,
				'plc_selection'			=> $data['plc_selection'],
				'practice_lab_comment'	=> $data['practice_lab_comment'],
				'send_to_account_ref'	=> $send_to_account_ref
			);
			if($data['plc_selection']=='1'){
				$content_data['order_number'] = $data['order_number'];
			}else{
				$content_data['order_number'] = $data['reference_number'];
			}
			$content_data['recipient_name'] = "Hello Netherlands";
			$content_data['content_body'] = 'Please proceed with the attached order.';
			$to_email = RECIEVER_EMAIL;
			$html .= '<dt>Email From</dt>';
			$html .= '<dd>'. $from_email .', Nextmune</dd>';
			$html .= '<dt>Email To</dt>';
			$html .= '<dd>'. $to_email .'</dd>';
			$html .= '<dt>Email Subject</dt>';
			$html .= '<dd>Order Details - '. $content_data['order_number'] .'</dd>';
			$html .= '<hr>';
			$html .= '<dt>Order Message Content</dt>';
			$html .= '<dd>'. $this->load->view('orders/order_mail_content_template', $content_data, true) .'</dd>';
			$html .= '<hr>';
			$html .= '<dt>Order Detail PDF</dt>';
			$html .= '<dd>'. $this->load->view('orders/order_mail_template', $content_data, true) .'</dd>';
			$html .= '<hr>';
			if ($data['sic_document'] != '') {
				$sic_file_path = base_url() . SIC_DOC_PATH . '/' . $data['sic_document'];
				$html .= '<dt>SIC Document</dt>';
				$html .= '<dd><iframe src="' . $sic_file_path . '" width="90%" height="500px">
                </iframe></dd>';
			}
			echo $html;
			exit;
		}else{
			echo $html;
			exit();
		}
	}

	public function send_mail($id = '', $is_confirmed = 0){
		$this->load->model('RecipientsModel');
		$data = $this->OrdersModel->allData($id);
		$this->OrdersModel->IsDraftUpdate($id);
		if ($is_confirmed == 1 && $data['order_type'] == '2'){
			redirect('orders');
			exit;
		}

		//if Order Delivery Address is there then
		$email_upload = FCPATH . EMAIL_UPLOAD_PATH . '/' . $data['email_upload'];
		$account_number_label = 'Practice Account number';

		$total_allergen = ($data['allergens'] != '') ? count(json_decode($data['allergens'])) : 0;
		if ($data['order_can_send_to'] == '1') {
			$delivery_practice = $data['delivery_practice_id'];
		} else {
			$delivery_practice = $data['vet_user_id'];
		}

		//is repeat order
		if ($data['is_repeat_order'] == '1') {
			$treatment_txt = "Maintenance Order";
		} else {
			$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
		// die($account_ref);

		if ($data['order_can_send_to'] == 1 || $data['order_can_send_to'] == '') {
			$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
		}
		$recipientArr = $this->RecipientsModel->getRecordAll($data['sub_order_type']);

		$toemailArr = [];
		$to_email = '';
		$from_email = "Noreply@nextmune.com";

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

		/**if delivery address and name should be the branch details selected or if no branches use the practice */
		/**Practice name and address details */
		$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
		$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
		$p_column_field = explode('|', $p_usersDetails['column_field']);
		$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
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
				$userData1 = array("user_id" => $data['vet_user_id'], "column_name" => "'account_ref'");
				$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData1);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$send_to_account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
			}
		}

		$content_data = array('order_type'=> $order_type, 'account_number_label' => $account_number_label, 'client_id' => $client_id, 
			'order_number' => $data['order_number'], 
			'account_ref' => $p_account_ref,
			'qty_order' => $data['qty_order'],
			'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'], 'pet_name' => $data['pet_name'],
			'total' => $total, 'active_uk' => $active_uk, 'veterinarian_first' => $data['practice_name'],
			'veterinarian_last' => $data['practice_last_name'], 'veterinarian_email' => $data['practice_email'],
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

		$html = $this->load->view('orders/order_mail_template', $content_data, true);
		$html = trim($html);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		// write pdf to a file
		$pdf = $dompdf->output();
		// die(FCPATH.ORDERS_PDF_PATH);
		if($data['plc_selection']=='1'){
			$content_data['order_number'] = $data['order_number'];
		}else{
			$content_data['order_number'] = $data['reference_number'];
		}
		$file = FCPATH . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";
		file_put_contents($file, $pdf);

		$sicdoc = FCPATH . SIC_DOC_PATH . "/" . $data['sic_document'];
		$attach_pdf = base_url() . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";

		$this->load->view('orders/order_mail_content_template', $content_data); //no exit for view template

		$config = array(
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);

		//Load email library 
		$this->load->library('email', $config);
		if ($is_confirmed == 1) {
			if($data['order_type'] == '2'){
				/* $content_data['recipient_name'] = "Dear " . $data['name'];
				$content_data['content_body'] = 'Your order is Confirmed.';
				$to_email = $data['email']; */
				redirect('orders');
			}else{
				$content_data['recipient_name'] = "Hello Netherlands";
				$content_data['content_body'] = 'Please proceed with the attached order.';
				$to_email = RECIEVER_EMAIL;
			}
		} else {
			$content_data['recipient_name'] = "Dear " . $data['name'];
			$content_data['content_body'] = 'Thank you for your order.<br><br>
                This will now be processed and we will be in touch if we have any further questions. You will find a summary of your order in the attached PDF.<br><br>
                Please allow 10-14 working days to receive your order.';
			if($data['lab_id'] > 0 && $data['lab_id'] == '13786'){
				$to_email = 'immunotherapy@axiomvetlab.co.uk';
			}else{
				$to_email = $data['email'];
			}
		}

		$this->email->from($from_email, "Nextmune");
		$this->email->to($to_email);
		/* $this->email->set_header('Content-Type', 'application/pdf');
		$this->email->set_header('Content-Disposition', 'attachment');
		$this->email->set_header('Content-Transfer-Encoding', 'base64'); */
		$this->email->subject('Order Details - '.$content_data['order_number']);
		$msg_content = $this->load->view('orders/order_mail_content_template', $content_data, true);
		$this->email->message($msg_content);
		$this->email->set_mailtype("html");
		$this->email->attach($file);
		if ($sicdoc != '') {
			$this->email->attach($sicdoc);
		}
		$is_send = $this->email->send();

		/* Send mail */ 
		if ($is_send) {
			$orderData['id'] = $id;
			if($data['order_type'] == '2'){
				$orderData['is_mail_sent'] = 0;
			}else{
				$orderData['is_mail_sent'] = 1;
			}
			$this->OrdersModel->add_edit($orderData);
			if($data['order_type'] == '2' && $this->user_role != 1) {
				$this->session->set_flashdata("success", "Order has successfully been sent to Nextmune, you can view your order and its status under the Track Order tab.");
				redirect('orders/serum_address/'. $id);
			}else{
				$this->session->set_flashdata("success", "Order has successfully been sent to Nextmune, you can view your order and its status under the Track Order tab.");
				redirect('orders');
			}
		} else {
			$this->session->set_flashdata("error", $this->email->print_debugger());
			redirect('orders');
		}
		exit;
	}

	function resend_customer_mail($id){
		if($id > 0){
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

			//is repeat order
			if ($data['is_repeat_order'] == '1') {
				$treatment_txt = "Maintenance Order";
			} else {
				$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
			$from_email = "Noreply@nextmune.com";

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

			/**if delivery address and name should be the branch details selected or if no branches use the practice */
			/**Practice name and address details */
			$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
			$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
			$p_column_field = explode('|', $p_usersDetails['column_field']);
			$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
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
				'order_type'=> $order_type,
				'account_number_label' => $account_number_label,
				'client_id' => $client_id,
				'order_number' => $data['order_number'],
				'account_ref' => $p_account_ref,
				'qty_order' => $data['qty_order'],
				'unit_price' => $data['unit_price'],
				'order_date' => $order_date,
				'order_discount' => $data['order_discount'],
				'pet_name' => $data['pet_name'],
				'total' => $total,
				'active_uk' => $active_uk,
				'veterinarian_first' => $data['practice_name'],
				'veterinarian_last' => $data['practice_last_name'],
				'veterinarian_email' => $data['practice_email'],
				'veterinarian_phone' => $data['branch_number'],
				'clinic_name' => $p_display_name,
				'clinic_add' => $full_address, 
				'postal_code' => $postal_code,
				'city' => $address_2,
				'country' => $practice_country,
				'order_sent_to' => $full_address, 
				'invoice_sent_to' => 'The clinic address above',
				'po_first' => $data['pet_owner_name'],
				'po_last' => $data['po_last'],
				'animal_name' => $data['pet_name'],
				'species' => $data['species_name'],
				'treatment' => $treatment_txt,
				'allergens' => $allergens_html, 
				'signature' => $data['signature'],
				'your_name' => $data['name'],
				'your_email' => $data['email'],
				'your_number' => $data['phone_number'],
				'customer_number' => $data['customer_number'],
				'branch_customer_number'=>$data['branch_customer_number'], 
				'total_allergens' => $total_allergen,
				'display_address' => $display_address,
				'display_address_1' => $display_address_1,
				'display_address_2' => $display_address_2,
				'display_address_3' => $display_address_3,
				'display_address_town_city' => $display_address_town_city,
				'display_address_county' => $display_address_county,
				'display_address_postcode' => $display_address_postcode,
				'lab_order' => $lab_order,
				'plc_selection'=>$data['plc_selection'],
				'practice_lab_comment'=>$data['practice_lab_comment'],
				'send_to_account_ref'	=> $send_to_account_ref
			);

			//save pdf
			$dompdf = new Dompdf(array('enable_remote' => true));

			$html = $this->load->view('orders/order_mail_template', $content_data, true);
			$html = trim($html);

			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'Portrait');
			$dompdf->render();
			// write pdf to a file
			$pdf = $dompdf->output();
			if($data['plc_selection']=='1'){
				$content_data['order_number'] = $data['order_number'];
			}else{
				$content_data['order_number'] = $data['reference_number'];
			}
			$file = FCPATH . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";
			file_put_contents($file, $pdf);

			$sicdoc = FCPATH . SIC_DOC_PATH . "/" . $data['sic_document'];
			$attach_pdf = base_url() . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";
			$this->load->view('orders/order_mail_content_template', $content_data); //no exit for view template
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);

			//Load email library 
			$this->load->library('email', $config);
			if($data['plc_selection']=='1'){
				$content_data['order_number'] = $data['order_number'];
			}else{
				$content_data['order_number'] = $data['reference_number'];
			}

			$content_data['recipient_name'] = "Dear " . $data['name'];
			$content_data['content_body'] = 'Thank you for your order.<br><br>
				This will now be processed and we will be in touch if we have any further questions. You will find a summary of your order in the attached PDF.<br><br>
				Please allow 10-14 working days to receive your order.';
			if($data['lab_id'] > 0 && $data['lab_id'] == '13786'){
				$to_email = 'immunotherapy@axiomvetlab.co.uk';
			}else{
				$to_email = $data['email'];
			}

			$this->email->from($from_email, "Nextmune");
			$this->email->to($to_email);
			$this->email->subject('Order Details - '.$content_data['order_number']);
			$msg_content = $this->load->view('orders/order_mail_content_template', $content_data, true);
			// echo $msg_content; die();
			$this->email->message($msg_content);
			$this->email->set_mailtype("html");
			$this->email->attach($file);
			if ($sicdoc != '') {
				$this->email->attach($sicdoc);
			}
			$is_send = $this->email->send();

			//Send mail 
			if ($is_send) {
				$orderData['id'] = $id;
				$orderData['is_mail_sent'] = 1;

				$this->OrdersModel->add_edit($orderData);
				$this->session->set_flashdata("success", "Email sent successfully.");
				redirect('orders');
			} else {
				//$this->session->set_flashdata("error", "Error in sending Email.");
				$this->session->set_flashdata("error", $this->email->print_debugger());
				redirect('orders');
			}
		} else {
			$this->session->set_flashdata("error", "Error in sending Email.");
			redirect('orders');
		}
		exit;
	}

	function add_batch_number(){
		$msg_data = $this->input->post();
		$id = $msg_data['order_id_batch_modal'];
		$batch_number = $msg_data['batch_number'];
		$remove_shipping = isset($msg_data['remove_shipping'])?1:0;
		if($msg_data['shipping_date'] != ""){
		$shipping_date = date("Y-m-d", strtotime($msg_data['shipping_date']));
		}else{
		$shipping_date = date("Y-m-d");
		}

		if($remove_shipping == 1){
			$orderData['id'] = $id;
			$orderData['is_confirmed'] = '1';
			$orderData['batch_number'] = '';
			$orderData['shipping_date'] = NULL;
			$this->OrdersModel->add_edit($orderData);
		}else{
			$orderData['id'] = $id;
			$orderData['is_confirmed'] = '4';
			$orderData['batch_number'] = $batch_number;
			$orderData['shipping_date'] = $shipping_date;
			$this->OrdersModel->add_edit($orderData);
		}

		$orderhData['order_id'] = $id;
		$orderhData['text'] = 'Shipped';
		$orderhData['created_by'] = $this->user_id;
		$orderhData['created_at'] = date("Y-m-d H:i:s");
		$this->OrdersModel->addOrderHistory($orderhData);
		echo 'success';
		exit;
	}

	function add_lab_number(){
		$msg_data = $this->input->post();
		$id = $msg_data['order_id_lab_modal'];
		$lab_order_number = $msg_data['lab_order_number'];
		if(!empty($lab_order_number)){
			$this->db->select('order_number');
			$this->db->from('ci_orders');
			$this->db->where('lab_order_number LIKE', $lab_order_number);
			$this->db->where('is_confirmed !=', '3');
			$this->db->where('is_draft', 0);
			$res1 = $this->db->get();
			if($res1->num_rows() == 0){
				$orderData['id'] = $id;
				$orderData['lab_order_number'] = $lab_order_number;
				$this->OrdersModel->add_edit($orderData);
				$this->session->set_flashdata('success', 'Bar code scanned and Saved Successfully.');
				echo 'success';
			}else{
				echo 'exist';
			}
		}else{
			$this->session->set_flashdata('error', 'Sorry! Bar code scanned have an error.');
			echo 'error';
		}
		exit;
	}

	function getlabNumber(){
		$msg_data = $this->input->post();
		$id = $msg_data['order_id'];
		if(!empty($id)){
			$this->db->select('lab_order_number');
			$this->db->from('ci_orders');
			$this->db->where('is_draft', 0);
			$this->db->where('id', $id);
			$lnumbe = $this->db->get()->row()->lab_order_number;
			echo $lnumbe;
		}else{
			echo '';
		}
		exit;
	}

	function getOrderSummery(){
		$msg_data = $this->input->post();
		$labNumber = $msg_data['lab_number'];
		if(!empty($labNumber)){
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('is_draft', 0);
			$this->db->where('lab_order_number LIKE', $labNumber);
			$this->db->where('is_confirmed !=', '3');
			$this->db->where('is_draft', '0');
			$lnumbe = $this->db->get()->row()->id;
			echo $lnumbe;
		}else{
			echo '';
		}
		exit;
	}

	function setBarcodeInsession(){
		$msg_data = $this->input->post();
		$scanedlabNumber = $msg_data['scan_lab_number'];
		if(!empty($scanedlabNumber)){
			$this->db->select('id');
			$this->db->from('ci_orders');
			$this->db->where('lab_order_number LIKE', $scanedlabNumber);
			$this->db->where('is_confirmed !=', '3');
			$this->db->where('is_draft', 0);
			$res2 = $this->db->get();
			if($res2->num_rows() > 0){
				echo 'Exist';
			}else{
				echo 'PAX';
				$this->session->set_userdata(array('lab_order_number' => $scanedlabNumber));
			}
		}else{
			echo 'Empty';
		}
		exit;
	}

	function customer_mail(){
		$msg_data = $this->input->post();
		$order_id = $msg_data['order_id_cust_modal'];

		//get all order data
		$data = $this->OrdersModel->allData($order_id);

		$from_email = "Noreply@nextmune.com";
		$to_email = '';
		$cust_name = '';
		if ($data['vet_user_id'] != 0) {
			$to_email = $data['practice_email'];
			$cust_name = $data['practice_name'] . ' ' . $data['practice_last_name'];
		} else if ($data['lab_id'] != 0) {
			$to_email = $data['lab_email'];
			$cust_name = $data['lab_name'];
		} else if ($data['corporate_id'] != 0) {
			$to_email = $data['corporate_email'];
			$cust_name = $data['corporate_name'];
		}

		$html = "";
		$html .= "Hello " . $cust_name . ",<br><br>";
		$html .= trim($msg_data['customer_mail']) . "<br><br>";
		$html .= "Thank You <br>";
		$html .= "Nextmune.";
		$config = array(
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);

		//Load email library 
		$this->load->library('email', $config);
		$this->email->from($from_email, "Nextmune");
		$this->email->to($to_email);
		$this->email->subject('Order Corrections');
		$this->email->message($html);
		$this->email->set_mailtype("html");
		$is_send = $this->email->send();
		//Send mail 
		if ($is_send) {
			$orderhData['order_id'] = $order_id;
			$orderhData['text'] = 'Email Customer';
			$orderhData['created_by'] = $this->user_id;
			$orderhData['created_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->addOrderHistory($orderhData);
			$this->session->set_flashdata("success", "Email has been sent to customer successfully.");
			echo 'success';
		} else {
			//$this->session->set_flashdata("error", "Error in sending Email.");
			$this->session->set_flashdata("error", $this->email->print_debugger());
			echo 'fail';
		}
		exit;
	}

	function download_mail($id = ''){
		$data = $this->OrdersModel->allData($id);

		$order_date = date('d/m/Y', strtotime($data['order_date']));
		$total = $data['unit_price'] - $data['order_discount'];
		//email content
		$data = array('order_number' => $data['order_number'], 'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'], 'pet_owner_name' => $data['pet_owner_name'], 'pet_name' => $data['pet_name'], 'total' => $total);

		//Load dompdf library 
		$this->load->library('DPdf');
		$html = $this->load->view('orders/order_mail_template', $data, true);
		$html = trim($html);

		$this->dpdf->createPDF($html, 'order_' . $data['order_number'], false);
		redirect('orders/list');
	}

	function orderCancelMail(){
		$msg_data = $this->input->post();
		$order_id = $msg_data['order_id_cancel_modal'];
		if ($order_id != '' && is_numeric($order_id)){
			$dataWhere['id'] = $order_id;
			$Cancel = $this->OrdersModel->Cancel($dataWhere);
			if($Cancel){
				$orderhData['order_id'] = $order_id;
				$orderhData['text'] = 'Cancel';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->addOrderHistory($orderhData);
			}
			$orderData['id'] = $order_id;
			$orderData['cancel_comment'] = trim($msg_data['cancel_comment']);
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);
		}

		$from_email = "Noreply@nextmune.com";
		$to_email = RECIEVER_EMAIL;

		$html = "";
		$html .= "Hello Netherlands,<br><br>";
		$html .= trim($msg_data['cancel_comment']) . "<br><br>";
		$html .= "Thank You <br>";
		$html .= "Nextmune.";
		$config = array(
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);

		//Load email library 
		$this->load->library('email', $config);
		$this->email->from($from_email, "Nextmune");
		$this->email->to($to_email);
		$this->email->subject('Order Cancel');
		$this->email->message($html);
		$this->email->set_mailtype("html");
		$is_send = $this->email->send();
		//Send mail 
		if ($is_send) {
			$this->session->set_flashdata("success", "Order Cancel and Email has been sent to Netherlands successfully.");
			echo 'success';
		} else {
			//$this->session->set_flashdata("error", "Error in sending Email.");
			$this->session->set_flashdata("error", $this->email->print_debugger());
			echo 'fail';
		}
		exit;
	}

	function orderHistoryDetails(){
		$data = $this->input->post();
		$order_details = $this->OrdersModel->getOrderHistory($data['order_id']);
		$html = "";
		if(!empty($order_details)){
			$html .= '<table class="table table-bordered">
				<thead>
					<tr>
						<th>Status</th>
						<th>User name</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>';
					foreach($order_details as $row){
						$userData = '';
						if($row->created_by > 0){
							if($row->created_by == '99999'){
								$html .= '<tr>
									<td>'. $row->text .'</td>
									<td>Update using LIMS API.</td>
									<td>'. date('d/m/Y H:i:s', strtotime($row->created_at)) .'</td>
								</tr>';
							}else{
								$userData = $this->OrdersModel->getUserDatabyId($row->created_by);
								$html .= '<tr>
									<td>'. $row->text .'</td>
									<td>'. $userData .'</td>
									<td>'. date('d/m/Y H:i:s', strtotime($row->created_at)) .'</td>
								</tr>';
							}
						}else{
							$html .= '<tr>
								<td>'. $row->text .'</td>
								<td>Update using API.</td>
								<td>'. date('d/m/Y H:i:s', strtotime($row->created_at)) .'</td>
							</tr>';
						}
					}
				$html .= '</tbody>
			</table>';
		}else{
			$html .= '<p>Order history not available.</p>';
		}
		echo $html;
		exit();
	}

	function repeatOrderDetails(){
		$data = $this->input->post();
		$html = "";
		$order_details = $this->OrdersModel->allData($data['order_id'], "");
		$allergens = $this->AllergensModel->order_allergens($order_details['allergens']);
		$allergens_str = (!empty($allergens)) ? $allergens['name'] : "";

		$allergens_html = "";
		$totalVialsdb = $this->OrdersModel->Totalvials($data['order_id']);
		$totalAllergens = count(json_decode($order_details['allergens']));
		if($totalAllergens > 8 && $totalVialsdb > 0 && $order_details['order_type'] == 1){
			$quotient = ($totalAllergens/8);
			$totalVials = ((round)($quotient));
			$demimal = $quotient-$totalVials;
			if($demimal > 0){
				$totalVials = $totalVials+1;
			}

			for ($x = 1; $x <= $totalVials; $x++) {
				$vialsList = $this->OrdersModel->getVialslist($x,$data['order_id']);
				$vialsAllenges = explode(",",$vialsList['allergens']);
				$allergens_html .= '<div class="col-sm-6 col-md-6 col-lg-6" style="padding:5px;">
					<strong style="padding-left:15%;font-size:16px;">Vial '.$x.'</strong>
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
				</div>';
			}
		}elseif($order_details['order_type'] == '2' && $order_details['serum_type'] == '1'){
			$getAllergenParent = $this->AllergensModel->getAllergenParentPax($order_details['allergens']);
			foreach ($getAllergenParent as $apkey => $apvalue){
				$allergens_html .= "<div class='col-sm-6 col-md-6 col-lg-6'><strong>" . $apvalue['pax_name'] . "</strong>";
				$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
				foreach ($subAllergens as $skey => $svalue) {
					$allergens_html .= "<ul><li>" . $svalue['pax_name'] . "</li></ul>";
				}
				$allergens_html .= "</div>";
			}
		}else{
			$getAllergenParent = $this->AllergensModel->getAllergenParent($order_details['allergens']);
			foreach ($getAllergenParent as $apkey => $apvalue) {
				$allergens_html .= "<div class='col-sm-6 col-md-6 col-lg-6'><strong style='padding-left:15%;font-size:16px;'>" . $apvalue['name'] . "</strong>";
				$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
				foreach ($subAllergens as $skey => $svalue) {
					$allergens_html .= "<ul><li>" . $svalue['name'] . "</li></ul>";
				}
				$allergens_html .= "</div>";
			}
		}

		/*****delivery address details */
		$delivery_address_details = '';
		if ($order_details['order_can_send_to'] == '1') {
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
			$delivery_address_details = $order_send_to;
		}else if($order_details['order_can_send_to'] == '0'){
			if($order_details['lab_id'] > 0){
				$userData = array("user_id" => $order_details['lab_id'], "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");

				$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
				$LabDetails = array_column($LabDetails, 'column_field', 'column_name');

				$l_address_1 = $LabDetails['address_1'] ? $LabDetails['address_1'] : NULL;
				$l_address_2 = $LabDetails['address_2'] ? $LabDetails['address_2'] : NULL;
				$l_address_3 = $LabDetails['address_3'] ? $LabDetails['address_3'] : NULL;
				$l_town_city = $LabDetails['town_city'] ? $LabDetails['town_city'] : NULL;
				$l_post_code = $LabDetails['post_code'] ? $LabDetails['post_code'] : NULL;

				$order_send_to = $l_address_1 . " " . $l_address_2 . " " . $l_address_3 . " " . $l_town_city . " " . $l_post_code;
				$delivery_address_details = $order_send_to;
			}else{
				$address_2 =  $order_details['branch_county'] ??  NULL;
				$address_3 = $order_details['branch_postcode'] ??  NULL;
				$account_ref = isset($column_field[2]) ? $column_field[2] : NULL;
				$add_1 = $order_details['branch_address'] ??  NULL;
				$add_2 = $order_details['branch_address1'] ??  NULL;
				$add_3 = $order_details['branch_address2'] ??  NULL;
				$add_4 = $order_details['branch_address3'] ??  NULL;
				$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $address_3;
				$delivery_address_details = $order_send_to;
			}
		}

		$deliver_add_html = '';
		if ($delivery_address_details != '') {
			if ($order_details['lab_id'] > 0) {
				if ($order_details['order_can_send_to'] == '1') {
					$final_name = $order_details['delivery_practice_name'];
				}else{
					$final_name = $order_details['lab_name'];
				}
			} elseif ($order_details['vet_user_id'] > 0) {
				$final_name = '';
			} else {
				$final_name = '';
			}
			$this->db->select('name');
			$this->db->from('ci_staff_countries');
			$this->db->where('id',$order_details['country']);
			$addrs = $this->db->get()->row()->name;
			$deliver_add_html = '<dt>Delivery Address Details</dt>
                                <dd>' . ( !empty($final_name) ? $final_name.' - ' : '' ) . $delivery_address_details.' '.$addrs .'</dd>';
		}
		/***** delivery address details*/

		/***** Practice or Lab Name */
		if ($order_details['lab_id'] > 0) {
			$final_name = $order_details['lab_name'];
		} elseif ($order_details['vet_user_id'] > 0) {
			$final_name = $order_details['practice_name'];
		} else {
			$final_name = '';
		}
		/***** Practice or Lab Name */

		if (count($order_details) > 0) {
			if ($order_details['pet_id'] > 0) {
				$breedData = $this->OrdersModel->getPetbreeds($order_details['pet_id']);
				$breedName = !empty($breedData)?'- '.$breedData:'';
			}else{
				$breedName = '';
			}

			if ($order_details['order_type'] == 1) {
				$order_type = 'Immunotherapy';
			} elseif ($order_details['order_type'] == 2) {
				$order_type = 'Serum Testing';
			} elseif ($order_details['order_type'] == 3) {
				$order_type = 'Skin Test';
			}

			if ($order_details['sub_order_type'] == 1) {
				$sub_order_type = 'Artuvetrin immunotherapy';
			} elseif ($order_details['sub_order_type'] == 2) {
				$sub_order_type = 'Sublingual immunotherapy (SLIT)';
			} elseif ($order_details['sub_order_type'] == 3) {
				$sub_order_type = 'Serum Request';
			} elseif ($order_details['sub_order_type'] == 4) {
				$sub_order_type = 'Order form artuvetrin Skin Test';
			}

			$sic_doc = '';
			if ($order_details['sic_document'] != '') {
				$sic_file_path = base_url() . SIC_DOC_PATH . '/' . $order_details['sic_document'];
				$sic_doc = '<dt>SIC Document</dt>';
				$sic_doc .= '<dd><iframe src="' . $sic_file_path . '" width="90%" height="500px">
                </iframe></dd>';
			}
			$html .=    '<dl class="dl-horizontal">';
				if($this->user_role == 1 && $order_details['order_type'] == 2){
					$html .= ' <dt>Lab Number</dt>
					<dd>'. $order_details['lab_order_number'] . '</dd>';
				}
				if ($order_details['plc_selection'] == '1') {
					$html .= ' <dt>Order Number</dt>
					<dd>' . $order_details['order_number'] . '</dd>';
				}else{
					$html .= ' <dt>Order Number</dt>
					<dd>' . $order_details['reference_number'] . '</dd>';
				}
				$html .= '<dt>Order Date</dt>
					<dd>' . $order_details['order_date'] . '</dd>
					<dt>Practice/Lab Name</dt>
					<dd>' . $final_name . '</dd>
					' . $deliver_add_html ;
				if($order_details['order_type'] != 3){
					$html .= '<dt>Pet Owners Name</dt>
					<dd>' . $order_details['po_last'] . '</dd>
					<dt>Pet Name</dt>
					<dd>' . $order_details['pet_name'] . ' '. $breedName .'</dd>';
				}
				$html .= '  <dt>Order Type</dt>
				<dd>' . $order_type . '</dd>
				<dt>Sub Order Type</dt>
				<dd>' . $sub_order_type . '</dd>
				<dt>Unit Price</dt>
				<dd>' . $order_details['unit_price'] . '</dd>
				<dt>Order Discount</dt>
				<dd>' . $order_details['order_discount'] . '</dd>
				<dt>Shipping Cost</dt>
				<dd>' . $order_details['shipping_cost'] . '</dd>
				<dt>Allergens</dt>
				<dd>' . $allergens_html . '</dd>
				' . $sic_doc . '
			</dl>';
		}
		echo $html;
		exit();
	}

	function confirmOrderDetails(){
		$data = $this->input->post();
		$html = "";
		$order_details = $this->OrdersModel->allData($data['order_id'], "");
		$allergens = $this->AllergensModel->order_allergens($order_details['allergens']);
		$totalVialsdb = $this->OrdersModel->Totalvials($data['order_id']);
		$totalAllergens = count(json_decode($order_details['allergens']));
		$allergens_html = "";
		if($totalAllergens > 8 && $this->user_role == 1 && $order_details['order_type'] == 1){
			$allergens_html .= '<div class="row select">';
				$quotient = ($totalAllergens/8);
				$totalVials = ((round)($quotient));
				$demimal = $quotient-$totalVials;
				if($demimal > 0){
					$totalVials = $totalVials+1;
				}
				$allergensIds = json_decode($order_details['allergens']);
				if($totalVialsdb > 0){
					$vialsListAllenges = $this->OrdersModel->getVialslistAllenges($data['order_id']);
					$selectedAllenge = explode(",",$vialsListAllenges['allergens']);
				}
				$allergens_html .= '<input type="hidden" name="total_vials" value="'.$totalVials.'">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<p><b>Selected '.$totalAllergens.' Allergens, please select which Vial each Allergen belongs to.</b></p>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-6 col-md-6 col-lg-6" style="padding:5px;">
						<select class="form-control required" name="mainList" multiple="multiple" style="min-height: 200px;height: 100%;">';
						if($totalVialsdb == 0){
							$allergensName = '';
							foreach($allergensIds as $row){
								$this->db->select('name,code');
								$this->db->from("ci_allergens");
								$this->db->where("id",$row);
								$responce = $this->db->get();
								$allergensName = $responce->row();
								$allergens_html .= '<option value="'.$row.'">'.$allergensName->name .' ['.$allergensName->code .']</option>';
							}
						}else{
							$allergensName = '';
							foreach($allergensIds as $row){
								if(!in_array($row, $selectedAllenge)){
									$this->db->select('name,code');
									$this->db->from("ci_allergens");
									$this->db->where("id",$row);
									$responce = $this->db->get();
									$allergensName = $responce->row();
									$allergens_html .= '<option value="'.$row.'">'.$allergensName->name .' ['.$allergensName->code .']</option>';
								}
							}
						}
						$allergens_html .= '</select>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6" style="padding:5px;">';
						for ($x = 1; $x <= $totalVials; $x++) {
							$allergens_html .= '<button type="button" class="btn btn-primary" onclick="movetoVials('.$x.')">Move to Vial '.$x.'</button> &nbsp;&nbsp;';
						}
					$allergens_html .= '</div>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12">';
					if($totalVialsdb == 0){
						for ($x = 1; $x <= $totalVials; $x++) {
							$allergens_html .= '<div class="col-sm-4 col-md-4 col-lg-4" style="padding:5px;">
								<div class="form-group">
									<label>Vial '.$x.'</label>
									<select name="vials['.$x.'][]" id="vials_'.$x.'" multiple="multiple" class="form-control vialsSelect" style="min-height: 200px;height: 100%;">
									</select>
								</div>
								<button type="button" class="btn btn-primary" onclick="movetoMain('.$x.')">Reset selected</button>
							</div>';
						}
					}else{
						for ($x = 1; $x <= $totalVials; $x++) {
							$vialsList = $this->OrdersModel->getVialslist($x,$data['order_id']);
							$vialsAllenges = explode(",",$vialsList['allergens']);
							$allergens_html .= '<input type="hidden" name="vial_id['.$x.'][]" value="'.$vialsList['vial_id'].'">
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding:5px;">
								<div class="form-group">
									<label>Vial '.$x.'</label>
									<select name="vials['.$x.'][]" id="vials_'.$x.'" multiple="multiple" class="form-control vialsSelect" style="min-height: 200px;height: 100%;">';
									foreach($vialsAllenges as $row){
										if(in_array($row, $allergensIds)){
											$this->db->select('name,code');
											$this->db->from("ci_allergens");
											$this->db->where("id",$row);
											$responce = $this->db->get();
											$allergensName = $responce->row();
											$allergens_html .= '<option value="'.$row.'" selected="selected">'.$allergensName->name .' ['.$allergensName->code .']</option>';
										}
									}
									$allergens_html .= '</select>
								</div>
								<button type="button" class="btn btn-primary" onclick="movetoMain('.$x.')">Reset selected</button>
							</div>';
						}
					}
				$allergens_html .= '</div>';
			$allergens_html .= "</div>";
		}elseif($order_details['order_type'] == '2' && $order_details['serum_type'] == '1'){
			$getAllergenParent = $this->AllergensModel->getAllergenParentPax($order_details['allergens']);
			foreach ($getAllergenParent as $apkey => $apvalue){
				$allergens_html .= "<div class='col-sm-6 col-md-6 col-lg-6'><strong>" . $apvalue['pax_name'] . "</strong>";
				$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $order_details['allergens']);
				foreach ($subAllergens as $skey => $svalue) {
					$allergens_html .= "<ul><li>" . $svalue['pax_name'] . "</li></ul>";
				}
				$allergens_html .= "</div>";
			}
		}else{
			$getAllergenParent = $this->AllergensModel->getAllergenParent($order_details['allergens']);
			foreach ($getAllergenParent as $apkey => $apvalue) {
				$allergens_html .= "<div class='col-sm-6 col-md-6 col-lg-6'><strong>" . $apvalue['name'] . "</strong>";
				$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
				foreach ($subAllergens as $skey => $svalue) {
					$allergens_html .= "<ul><li>" . $svalue['name'] . "</li></ul>";
				}
				$allergens_html .= "</div>";
			}
		}

		/*****delivery address details */
		$delivery_address_details = '';
		if ($order_details['order_can_send_to'] == '1') {
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
			$delivery_address_details = $order_send_to;
		}else if($order_details['order_can_send_to'] == '0'){
			if($order_details['lab_id'] > 0){
				$userData = array("user_id" => $order_details['lab_id'], "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");

				$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
				$LabDetails = array_column($LabDetails, 'column_field', 'column_name');

				$l_address_1 = $LabDetails['address_1'] ? $LabDetails['address_1'] : NULL;
				$l_address_2 = $LabDetails['address_2'] ? $LabDetails['address_2'] : NULL;
				$l_address_3 = $LabDetails['address_3'] ? $LabDetails['address_3'] : NULL;
				$l_town_city = $LabDetails['town_city'] ? $LabDetails['town_city'] : NULL;
				$l_post_code = $LabDetails['post_code'] ? $LabDetails['post_code'] : NULL;

				$order_send_to = $l_address_1 . " " . $l_address_2 . " " . $l_address_3 . " " . $l_town_city . " " . $l_post_code;
				$delivery_address_details = $order_send_to;
			}else{
				$address_2 =  $order_details['branch_county'] ??  NULL;
				$address_3 = $order_details['branch_postcode'] ??  NULL;
				$account_ref = isset($column_field[2]) ? $column_field[2] : NULL;
				$add_1 = $order_details['branch_address'] ??  NULL;
				$add_2 = $order_details['branch_address1'] ??  NULL;
				$add_3 = $order_details['branch_address2'] ??  NULL;
				$add_4 = $order_details['branch_address3'] ??  NULL;
				$order_send_to = $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $address_3;
				$delivery_address_details = $order_send_to;
			}
		}

		$deliver_add_html = '';
		if ($delivery_address_details != '') {
			if ($order_details['lab_id'] > 0) {
				if ($order_details['order_can_send_to'] == '1') {
					$final_name = $order_details['delivery_practice_name'];
				}else{
					$final_name = $order_details['lab_name'];
				}
			} elseif ($order_details['vet_user_id'] > 0) {
				$final_name = '';
			} else {
				$final_name = '';
			}
			$deliver_add_html = '<dt>Delivery Address Details</dt>
                                <dd>' . ( !empty($final_name) ? $final_name.' - ' : '' ) . $delivery_address_details . '</dd>';
		}
		/***** delivery address details*/

		/***** Practice or Lab Name */
		if ($order_details['lab_id'] > 0) {
			$final_name = $order_details['lab_name'];
		} elseif ($order_details['vet_user_id'] > 0) {
			$final_name = $order_details['practice_name'];
		} else {
			$final_name = '';
		}
		/***** Practice or Lab Name */

		if (count($order_details) > 0) {
			if ($order_details['pet_id'] > 0) {
				$breedData = $this->OrdersModel->getPetbreeds($order_details['pet_id']);
				$breedName = !empty($breedData)?'- '.$breedData:'';
			}else{
				$breedName = '';
			}

			if ($order_details['order_type'] == 1) {
				$order_type = 'Immunotherapy';
			} elseif ($order_details['order_type'] == 2) {
				$order_type = 'Serum Testing';
			} elseif ($order_details['order_type'] == 3) {
				$order_type = 'Skin Test';
			}

			if ($order_details['sub_order_type'] == 1) {
				$sub_order_type = 'Artuvetrin immunotherapy';
			} elseif ($order_details['sub_order_type'] == 2) {
				$sub_order_type = 'Sublingual immunotherapy (SLIT)';
			} elseif ($order_details['sub_order_type'] == 3) {
				$sub_order_type = 'Serum Request';
			} elseif ($order_details['sub_order_type'] == 4) {
				$sub_order_type = 'Order form artuvetrin Skin Test';
			}

			$sic_doc = '';
			if ($order_details['sic_document'] != '') {
				$sic_file_path = base_url() . SIC_DOC_PATH . '/' . $order_details['sic_document'];
				$sic_doc = '<dt>SIC Document</dt>';
				$sic_doc .= '<dd><iframe src="' . $sic_file_path . '" width="90%" height="500px"></iframe></dd>';
			}
			$html .=    '<dl class="dl-horizontal">';
				if($this->user_role == 1 && $order_details['order_type'] == 2){
					$html .= ' <dt>Lab Number</dt>
					<dd>'. $order_details['lab_order_number'] . '</dd>';
				}
				if ($order_details['plc_selection'] == '1') {
					$html .= ' <dt>Order Number</dt>
					<dd>' . $order_details['order_number'] . '</dd>';
				}else{
					$html .= ' <dt>Order Number</dt>
					<dd>' . $order_details['reference_number'] . '</dd>';
				}
				$html .= '<dt>Order Date</dt>
					<dd>' . $order_details['order_date'] . '</dd>
					<dt>Practice/Lab Name</dt>
					<dd>' . $final_name . '</dd>
					' . $deliver_add_html ;
				if($order_details['order_type'] != 3){
					$html .= '<dt>Pet Owners Name</dt>
					<dd>' . $order_details['po_last'] . '</dd>
					<dt>Pet Name</dt>
					<dd>' . $order_details['pet_name'] . ' '. $breedName .'</dd>';
				}
				$html .= '  <dt>Order Type</dt>
				<dd>' . $order_type . '</dd>
				<dt>Sub Order Type</dt>
				<dd>' . $sub_order_type . '</dd>
				<dt>Unit Price</dt>
				<dd>' . $order_details['unit_price'] . '</dd>
				<dt>Order Discount</dt>
				<dd>' . $order_details['order_discount'] . '</dd>
				<dt>Shipping Cost</dt>
				<dd>' . $order_details['shipping_cost'] . '</dd>
				<dt>Allergens</dt>
				<dd>' . $allergens_html . '</dd>
				' . $sic_doc . '
			</dl>';
		}
		echo $html;
		exit();
	}

	function repeatOrder(){
		$data = $this->input->post();
		if ($data['order_id'] > 0) {
			$result = $this->OrdersModel->repeatOrder($data['order_id']);
			if ($result) {
				$this->session->set_flashdata('success', 'Repeat Order has been placed successfully.');
				echo 'success';
			} else {
				echo 'fail';
			}
		}
	}

	function mergeRepeatOrder(){
		$data = $this->input->post();
		if(!empty($data['order_id'])){
			$orderIdArr = explode(",",$data['order_id']);
			$mainID = $orderIdArr[0];
			foreach (array_keys($orderIdArr, $mainID) as $key) {
				unset($orderIdArr[$key]);
			}
			$orderIdstr = implode(",",$orderIdArr);

			$this->db->select('vet_user_id,lab_id,pet_owner_id,order_type');
			$this->db->from('ci_orders');
			$this->db->where('id', $mainID);
			$res1 = $this->db->get();
			$orderData = $res1->row();
			if(!empty($orderData)){
				$this->db->select('id');
				$this->db->from('ci_orders');
				$this->db->where('id IN('.$orderIdstr.')');
				$this->db->where('vet_user_id', $orderData->vet_user_id);
				$this->db->where('lab_id', $orderData->lab_id);
				$this->db->where('pet_owner_id', $orderData->pet_owner_id);
				$this->db->where('order_type', $orderData->order_type);
				$res2 = $this->db->get();
				if($res2->num_rows() == count($orderIdArr)){
					echo 'success';
				}else{
					echo 'fail';
				}
			}else{
				echo 'fail';
			}
		}
	}

	function confirm_order(){
		$order_data = $this->input->post();
		if ($order_data['order_id'] != '') {
			$result = $this->OrdersModel->confirmOrder($order_data['order_id']);
			if ($result) {
				if (isset($order_data['total_vials']) && $order_data['total_vials']>0){
					$vialsArr = !empty($order_data['vials'])?$order_data['vials']:array();
					$vialsIDArr = !empty($order_data['vial_id'])?$order_data['vial_id']:array();
					if(!empty($vialsIDArr)){
						foreach($vialsArr as $key=>$value){
							$orderData['vial_id'] = $vialsIDArr[$key][0];
							$orderData['order_id'] = $order_data['order_id'];
							$orderData['vials_order'] = $key;
							$orderData['allergens'] = implode(",",$value);
							$orderData['updated_by'] = $this->user_id;
							$orderData['updated_at'] = date("Y-m-d H:i:s");
							$this->OrdersModel->add_edit_vials($orderData);
						}
					}else{
						foreach($vialsArr as $key=>$value){
							$orderData['order_id'] = $order_data['order_id'];
							$orderData['vials_order'] = $key;
							$orderData['allergens'] = implode(",",$value);
							$orderData['updated_by'] = $this->user_id;
							$orderData['updated_at'] = date("Y-m-d H:i:s");
							$this->OrdersModel->add_edit_vials($orderData);
						}
					}
				}
				$orderhData['order_id'] = $order_data['order_id'];
				$orderhData['text'] = 'Confirmed';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->addOrderHistory($orderhData);
				$this->session->set_flashdata('success', 'Order has been confirmed successfully.');
				$this->send_mail($order_data['order_id'], 1);
				echo 'success';
			} else {
				echo 'fail';
			}
		}
	}

	function resend_mail($id){
		$this->send_mail($id, 1);
	}

	function allergens($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$orderTypeData = array("0" => $data['sub_order_type']);
		$this->_data['allergens_group'] = $this->AllergensModel->get_allergens_dropdown($orderTypeData);
		$this->_data['id'] = $id;
		$orderData = [];
		if($data['order_type'] == '2') {
			if($data['serum_type'] == '1'){
				$respned = $this->OrdersModel->getProductInfo($data['product_code_selection']);
				$subOrderTypeArr = [];
				if(!empty($respned)){
					if($respned->id == "34" || $respned->name == "PAX Environmental" || $respned->id == "35" || $respned->name == "PAX Environmental Screening"){
						$sub_order_type = '8';
						$subOrderTypeArr[] = '8';
					}elseif($respned->id == "33" || $respned->name == "PAX Food" || $respned->id == "36" || $respned->name == "PAX Food Screening"){
						$sub_order_type = '9';
						$subOrderTypeArr[] = '9';
					}elseif($respned->id == "37" || $respned->name == "PAX Environmental + Food Screening" || $respned->id == "38" || $respned->name == "PAX Environmental + Food"){
						$sub_order_type = '8,9';
						$subOrderTypeArr = array("0" => "8","1" => "9");
					}else{
						$sub_order_type = $data['sub_order_type'];
						$subOrderTypeArr = array("0" => "8","1" => "9","2" => "10");
					}
				}else{
					$sub_order_type = $data['sub_order_type'];
					$subOrderTypeArr = array("0" => "8","1" => "9","2" => "10");
				}
				$allergens_group = $this->AllergensModel->get_pax_allergens_dropdown($subOrderTypeArr);
				if(!empty($allergens_group)){
					$allergenslct = array();
					foreach ($allergens_group as $key => $value) {
						$subAllergens = $this->AllergensModel->getPAXSubAllergensdropdown($value['id'],'',$sub_order_type);
						if(!empty($subAllergens)){
							foreach($subAllergens as $skey => $svalue){
								if($svalue['pax_name'] != "N/A"){
								$allergenslct[] = $svalue['id'];
								}
							}
						}
					}
					$orderData['id'] = $id;
					if(!empty(json_decode($data['allergens']))){
						$orderData['allergens'] = $data['allergens'];
					}else{
						$orderData['allergens'] = json_encode($allergenslct);
					}
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
				}else{
					$orderData['id'] = $id;
					$orderData['allergens'] = '[""]';
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
				}
			}else{
				$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
				$subOrder2TypeArr = [];
				if(!empty($respnedn)){
					if($data['species_selection'] == 1){
						if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '3';
							$subOrder2TypeArr = array("0" => "3");
						}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '5';
							$subOrder2TypeArr = array("0" => "5");
						}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '3,5';
							$subOrder2TypeArr = array("0" => "3", "1" => "5");
						}else{
							$sub_order_type = '';
							$subOrder2TypeArr = array("0" => "0");
						}
					}

					if($data['species_selection'] == 2){
						if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '6';
							$subOrder2TypeArr = array("0" => "6");
						}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '7';
							$subOrder2TypeArr = array("0" => "7");
						}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '6,7';
							$subOrder2TypeArr = array("0" => "6", "1" => "7");
						}else{
							$sub_order_type = '';
							$subOrder2TypeArr = array("0" => "0");
						}
					}

					if($data['species_selection'] == 3){
						if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '31';
							$subOrder2TypeArr = array("0" => "31");
						}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '51';
							$subOrder2TypeArr = array("0" => "51");
						}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
							$sub_order_type = '31,51';
							$subOrder2TypeArr = array("0" => "31", "1" => "51");
						}else{
							$sub_order_type = '';
							$subOrder2TypeArr = array("0" => "0");
						}
					}
				}

				$allergensGroup = $this->AllergensModel->get_allergens_dropdown($subOrder2TypeArr);
				if(!empty($allergensGroup)){
					$allergenslct = array();
					foreach ($allergensGroup as $key => $value) {
						$subAllergens = $this->AllergensModel->getSubAllergensdropdown($value['id'],'',$sub_order_type);
						if(!empty($subAllergens)){
							foreach($subAllergens as $skey => $svalue){
								if($svalue['name'] != "N/A"){
								$allergenslct[] = $svalue['id'];
								}
							}
						}
					}
					$orderData['id'] = $id;
					if(!empty(json_decode($data['allergens']))){
						$orderData['allergens'] = $data['allergens'];
					}else{
						$orderData['allergens'] = json_encode($allergenslct);
					}
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
				}else{
					$orderData['id'] = $id;
					$orderData['allergens'] = '[""]';
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
				}
			}
			redirect('orders/serum_request/'. $id);
		}else{
			$allergen_total = $this->input->post('allergen_total');

			//check allergens is available or not
			$notAvailAllergens = $this->AllergensModel->getNotAvailAllergens($this->input->post('allergens'));
			if ($allergen_total == 0) {
				$this->session->set_flashdata('error', 'Please select atleast one allergen.');
			} elseif (!empty($notAvailAllergens) &&  $notAvailAllergens['name'] != '') {
				// $this->session->set_flashdata('error','Sorry allergen <strong>'.$notAvailAllergens['name'].'</strong> is currently unavailable. The respective expected due date is (<strong>'.$notAvailAllergens['due_date'].'</strong>). Please check back on this date to place your order.');
				$this->session->set_flashdata('error', 'Sorry allergen <strong>' . $notAvailAllergens['name'] . '</strong> is currently unavailable. The respective expected due date is (<strong>' . $notAvailAllergens['due_date'] . '</strong>).');

				$this->session->set_flashdata('info', 'If you would like to proceed without this allergen please untick the box.');

				$data['allergens'] = json_encode($this->input->post('allergens'));
				$this->_data['data'] = $data;
				//$this->load->view("orders/allergens",$this->_data); 
			} else {
				$orderData['id'] = $id;
				$orderData['allergens'] = ($this->input->post('allergens')[0] != '') ? json_encode($this->input->post('allergens')) : NULL;
				$orderData['practice_lab_comment'] = ($this->input->post('practice_lab_comment')!='')?$this->input->post('practice_lab_comment'):'';
				$orderData['comment_by'] = ($this->input->post('practice_lab_comment')!='')?$this->user_id:0;
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$sql = "SELECT * FROM ci_user_details WHERE column_name IN('labs') AND user_id = '". $this->user_id ."'";
					$responce = $this->db->query($sql);
					$userIds = $responce->result_array();
					$LabDetails = array_column($userIds, 'column_field', 'column_name');
					$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : array();
					$totalVialsdb = $this->OrdersModel->Totalvials($id);
					if ($data['sub_order_type'] == '3') {
						redirect('orders/serum_request/' . $id);
					}elseif((count($this->input->post('allergens')) > 8 && $data['is_repeat_order'] == '1' && in_array("13786", $labs) && $data['order_type'] == 1) || ($id > 0 && $totalVialsdb > 0)){
						redirect('orders/vials/' . $id);
					}elseif($data['is_repeat_order'] == '0' && $data['cep_id'] > 0){
						redirect('orders/immmuno_summary/' . $id);
					}else{
						redirect('orders/summary/' . $id);
					}
				}
			} //else

			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
			$this->load->view("orders/allergens", $this->_data);
		}
	}

	function serum_request($order_id = ''){
		$this->_data['data'] = [];
		$data = $this->OrdersModel->getSerumTestRecord($order_id);
		$id = (!empty($data) && $data['id'] > 0) ? $data['id'] : "";
		$this->_data['id'] = $id;
		$this->_data['order_details'] = $this->OrdersModel->allData($order_id, "");
		$orderData = [];
		if ($this->input->post()) {
			$orderData['id'] = $id;
			$orderData['order_id'] = $order_id;

			$replaced_date = str_replace('/', '-', $this->input->post('date'));
			$orderData['date'] = ($this->input->post('date') != '') ? date("Y-m-d", strtotime($replaced_date)) : NULL;
			$orderData['veterinary_surgeon'] = ($this->input->post('veterinary_surgeon') != '') ? $this->input->post('veterinary_surgeon') : NULL;
			$orderData['veterinary_practice'] = ($this->input->post('veterinary_practice') != '') ? $this->input->post('veterinary_practice') : NULL;
			$orderData['practice_details'] = ($this->input->post('practice_details') != '') ? $this->input->post('practice_details') : NULL;
			$orderData['city'] = ($this->input->post('city') != '') ? $this->input->post('city') : NULL;
			$orderData['postcode'] = ($this->input->post('postcode') != '') ? $this->input->post('postcode') : NULL;
			$orderData['phone'] = ($this->input->post('phone') != '') ? $this->input->post('phone') : NULL;
			$orderData['email'] = ($this->input->post('email') != '') ? $this->input->post('email') : NULL;
			$orderData['receive_results_by'] = ($this->input->post('receive_results_by')[0] != '') ? implode(',', $this->input->post('receive_results_by')) : NULL;
			$orderData['order_more_serum'] = ($this->input->post('order_more_serum') != '') ? '1' : '0';
			$orderData['species'] = ($this->input->post('species')[0] != '') ? implode(',', $this->input->post('species')) : NULL;
			$orderData['species_gender'] = ($this->input->post('species_gender') != '') ? '1' : '0';
			$orderData['owner_name'] = ($this->input->post('owner_name') != '') ? $this->input->post('owner_name') : '';
			$orderData['animal_name'] = ($this->input->post('animal_name') != '') ? $this->input->post('animal_name') : '';
			$orderData['breed'] = ($this->input->post('breed') != '') ? $this->input->post('breed') : '';
			//$orderData['email'] = $this->input->post('email');
			$birth_replaced_date = str_replace('/', '-', $this->input->post('birth_date'));
			$orderData['birth_date'] = ($birth_replaced_date != '') ? date("Y-m-d", strtotime($birth_replaced_date)) : NULL;
			$serum_drawn_replaced_date = str_replace('/', '-', $this->input->post('serum_drawn_date'));
			$orderData['serum_drawn_date'] = ($serum_drawn_replaced_date != '') ? date("Y-m-d", strtotime($serum_drawn_replaced_date)) : NULL;
			$orderData['major_symptoms'] = ($this->input->post('major_symptoms')[0] != '') ? implode(',', $this->input->post('major_symptoms')) : NULL;
			$orderData['other_symptom'] = $this->input->post('other_symptom');
			$orderData['symptom_appear_age'] = $this->input->post('symptom_appear_age');
			$orderData['symptom_appear_age_month'] = $this->input->post('symptom_appear_age_month');
			$orderData['when_obvious_symptoms'] = ($this->input->post('when_obvious_symptoms')[0] != '') ? implode(',', $this->input->post('when_obvious_symptoms')) : NULL;
			$orderData['where_obvious_symptoms'] = ($this->input->post('where_obvious_symptoms')[0] != '') ? implode(',', $this->input->post('where_obvious_symptoms')) : NULL;
			$orderData['medication'] = ($this->input->post('medication') != '') ? $this->input->post('medication') : '0';
			$orderData['medication_desc'] = $this->input->post('medication_desc');
			$orderData['zoonotic_disease'] = ($this->input->post('zoonotic_disease') != '') ? $this->input->post('zoonotic_disease'):'0';
			$orderData['zoonotic_disease_dec'] = $this->input->post('zoonotic_disease_dec');
			$orderData['diagnosis_food'] = ($this->input->post('diagnosis_food') != '') ? $this->input->post('diagnosis_food'):'0';
			$orderData['other_diagnosis_food'] = $this->input->post('other_diagnosis_food');
			$orderData['food_challenge'] = ($this->input->post('food_challenge')[0] != '') ? implode(',', $this->input->post('food_challenge')) : NULL;
			$orderData['diagnosis_hymenoptera'] = ($this->input->post('diagnosis_hymenoptera') != '') ? $this->input->post('diagnosis_hymenoptera'):'0';
			$orderData['other_diagnosis_hymenoptera'] = $this->input->post('other_diagnosis_hymenoptera');
			$orderData['diagnosis_other'] = ($this->input->post('diagnosis_other') != '') ? $this->input->post('diagnosis_other'):'0';
			$orderData['other_diagnosis'] = $this->input->post('other_diagnosis');
			$orderData['regularly_exposed'] = ($this->input->post('regularly_exposed')[0] != '') ? implode(',', $this->input->post('regularly_exposed')) : NULL;
			$orderData['other_exposed'] = $this->input->post('other_exposed');
			$orderData['malassezia_infections'] = ($this->input->post('malassezia_infections')[0] != '') ? implode(',', $this->input->post('malassezia_infections')) : NULL;
			$orderData['receiving_drugs'] = ($this->input->post('receiving_drugs')[0] != '') ? implode(',', $this->input->post('receiving_drugs')) : NULL;
			$orderData['receiving_drugs_1'] = ($this->input->post('receiving_drugs_1') != '') ? $this->input->post('receiving_drugs_1'):'0';
			$orderData['receiving_drugs_2'] = ($this->input->post('receiving_drugs_2') != '') ? $this->input->post('receiving_drugs_2'):'0';
			$orderData['receiving_drugs_3'] = ($this->input->post('receiving_drugs_3') != '') ? $this->input->post('receiving_drugs_3'):'0';
			$orderData['receiving_drugs_4'] = ($this->input->post('receiving_drugs_4') != '') ? $this->input->post('receiving_drugs_4'):'0';
			$orderData['receiving_drugs_5'] = ($this->input->post('receiving_drugs_5') != '') ? $this->input->post('receiving_drugs_5'):'0';
			$orderData['receiving_drugs_6'] = ($this->input->post('receiving_drugs_6') != '') ? $this->input->post('receiving_drugs_6'):'0';
			$orderData['treatment_ectoparasites'] = ($this->input->post('treatment_ectoparasites') != '') ? $this->input->post('treatment_ectoparasites'):'0';
			$orderData['other_ectoparasites'] = $this->input->post('other_ectoparasites');
			$orderData['elimination_diet'] = ($this->input->post('elimination_diet') != '') ? $this->input->post('elimination_diet'):'0';
			$orderData['other_elimination'] = $this->input->post('other_elimination');
			$orderData['additional_information'] = $this->input->post('additional_information');
			$orderData['immunotherapy_recommendation'] = ($this->input->post('immunotherapy_recommendation') != '') ? '1' : '0';
			if (is_numeric($id) > 0) {
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($id = $this->OrdersModel->serum_test_add_edit($orderData) > 0) {
					$updtData['internal_comment'] = $this->input->post('internal_comment');
					$this->db->update('ci_orders', $updtData, array('id'=>$order_id));
					redirect('orders/summary/' . $order_id);
				}
			} else {
				$orderData['created_by'] = $this->user_id;
				$orderData['created_at'] = date("Y-m-d H:i:s");
				if ($id = $this->OrdersModel->serum_test_add_edit($orderData)) {
					$updtData['internal_comment'] = $this->input->post('internal_comment');
					$this->db->update('ci_orders', $updtData, array('id'=>$order_id));
					redirect('orders/summary/' . $order_id);
				}
			}
		}

		if (!empty($data)) {
			$this->_data['data'] = $data;
		}
		$this->load->view("orders/serum_request", $this->_data);
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
		$this->_data['total_allergens'] = ($order_details['allergens'] != '')?count(json_decode($order_details['allergens'])):0;
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

	function summary($id = ''){
		$this->_data['data'] = [];
		$data = $this->OrdersModel->getRecord($id);
		$order_details = $this->OrdersModel->allData($data['id'], "");

		/*****delivery address details */
		$this->_data['delivery_address_details'] = '';
		if ($order_details['order_can_send_to'] == '1') {
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
			// Different Address
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
		/***** delivery address details*/

		/***** Practice or Lab Name */
		if ($order_details['lab_id'] > 0) {
			$final_name = $order_details['lab_name'];
		} elseif ($order_details['vet_user_id'] > 0) {
			$final_name = $order_details['practice_name'];
		} else {
			$final_name = '';
		}
		$this->_data['final_name'] = $final_name;
		/***** Practice or Lab Name */

		$allergens = $this->AllergensModel->order_allergens($order_details['allergens']);
		$this->_data['order_details'] = $order_details;
		$this->_data['allergens'] = $allergens;
		$this->_data['total_allergens'] = ($order_details['allergens'] != '') ? count(json_decode($order_details['allergens'])) : 0;
		$this->_data['id'] = $id;
		$this->_data['controller'] = $this->router->fetch_class();
		$this->_data['order_type'] = $order_details['order_type'];
		$this->_data['sub_order_type'] = $order_details['sub_order_type'];
		$this->_data['final_price'] = '0.00';
		$this->_data['order_discount'] = '0.00';

		//Pricing
		$selected_allergen = json_decode($order_details['allergens']);
		$total_allergen = ($order_details['allergens'] != '') ? count(json_decode($order_details['allergens'])) : 0;
		if ($data['lab_id'] != 0) {
			$practice_lab = $data['lab_id'];
		} else {
			$practice_lab = $data['vet_user_id'];
		}

		if ($total_allergen > 0) {
			//Skin Test Pricing
			if ($data['order_type'] == '3') {
				$single_order_discount = 0.00;
				$insects_order_discount = 0.00;
				$selected_allergen_ids = implode(",", $selected_allergen);
				$insects_allergen = $this->AllergensModel->insect_allergen($selected_allergen_ids);
				$skin_test_price = $this->PriceCategoriesModel->skin_test_price($practice_lab);
				$single_price = $skin_test_price[0]['uk_price'];
				$single_insect_price = $skin_test_price[1]['uk_price'];
				$single_allergen = $total_allergen - $insects_allergen;

				/**single allergen discount **/
				$single_discount = $this->PriceCategoriesModel->get_discount("14", $practice_lab);
				if (!empty($single_discount)) {
					$single_order_discount = ($skin_test_price[0]['uk_price'] * $single_discount['uk_discount']) / 100;
					$single_order_discount = sprintf("%.2f", $single_order_discount);
				}
				/**single allergen discount **/

				/**insects allergen discount **/
				if ($insects_allergen > 0) {
					$insects_discount = $this->PriceCategoriesModel->get_discount("15", $practice_lab);
					if (!empty($insects_discount)) {
						$insects_order_discount = ($skin_test_price[1]['uk_price'] * $insects_discount['uk_discount']) / 100;
						$insects_order_discount = sprintf("%.2f", $insects_order_discount);
					}
				}
				/**insects allergen discount **/

				$final_price = ($single_price * $single_allergen) + ($single_insect_price * $insects_allergen);
				$this->_data['final_price'] = $final_price - ($single_order_discount + $insects_order_discount);
				$this->_data['order_discount'] = $single_order_discount + $insects_order_discount;
			}

			//Serum Test Pricing 
			if ($data['order_type'] == '2') {
				$order_discount = 0.00;
				if($data['cep_id'] > 0){
					$final_price  = $data['unit_price'];
				}else{
					$product_code_id = $this->session->userdata('product_code_selection');
					$serum_test_price = $this->PriceCategoriesModel->serum_test_price($product_code_id, $practice_lab);
					//$final_price = $total_allergen * ($serum_test_price[0]['uk_price']);
					$final_price = $serum_test_price[0]['uk_price'];

					/**discount **/
					$serum_discount = $this->PriceCategoriesModel->get_discount($data['product_code_selection'], $practice_lab);
					//print_r($serum_discount);
					if (!empty($serum_discount)) {
						$order_discount = ($serum_test_price[0]['uk_price'] * $serum_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/
				}

				$this->_data['final_price'] = $final_price - $order_discount;
				$this->_data['order_discount'] = $order_discount;
			}

			//Immunotherapy Artuvetrin Test Pricing
			if ($data['order_type'] == '1' && $data['sub_order_type'] == '1') {
				$artuvetrin_test_price = $this->PriceCategoriesModel->artuvetrin_test_price($practice_lab);

				//Artuvetrin Therapy 1 – 4 allergens
				if ($total_allergen <= 4) {
					$order_discount = 0.00;
					/**discount **/
					$artuvetrin_discount = $this->PriceCategoriesModel->get_discount("16", $practice_lab);
					if (!empty($artuvetrin_discount)) {
						$order_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/

					$this->_data['final_price'] = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
					$this->_data['order_discount'] = round($order_discount, 2);

					//Artuvetrin Therapy 5 – 8 allergens
				} elseif ($total_allergen > 4 && $total_allergen <= 8) {
					$order_discount = 0.00;
					/**discount **/
					$artuvetrin_discount = $this->PriceCategoriesModel->get_discount("17", $practice_lab);
					if (!empty($artuvetrin_discount)) {
						$order_discount = ($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/

					$this->_data['final_price'] = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
					$this->_data['order_discount'] = round($order_discount, 2);

					//Artuvetrin Therapy more than 8 allergens

				} elseif ($total_allergen > 8) {
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
				}
			} //if

			//Sublingual Immunotherapy (SLIT) Pricing
			if ($data['order_type'] == '1' && $data['sub_order_type'] == '2') {
				//Sublingual Single Price
				$selected_allergen_ids = implode(",", $selected_allergen);
				$culicoides_allergen = $this->AllergensModel->culicoides_allergen($selected_allergen_ids);
				$slit_test_price = $this->PriceCategoriesModel->slit_test_price($practice_lab);
				$single_price = $slit_test_price[0]['uk_price'];
				$double_price = $slit_test_price[1]['uk_price'];
				$single_with_culicoides = $slit_test_price[2]['uk_price'];
				$double_with_culicoides = $slit_test_price[3]['uk_price'];
				$single_allergen = $total_allergen - $culicoides_allergen;
				$order_discount = 0.00;
				if ($data['single_double_selection'] == '1' && $culicoides_allergen == 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("18", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[0]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/
					$final_price = $total_allergen * $single_price;
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '2' && $culicoides_allergen == 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("19", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[1]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/** discount **/
					$final_price = $total_allergen * $double_price;
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '1' && $culicoides_allergen > 0) {
					/** discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("20", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[2]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/** discount **/
					$final_price = ($single_price * $single_allergen) + ($single_with_culicoides * $culicoides_allergen);
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '2' && $culicoides_allergen > 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("21", $practice_lab);
					//print_r($slit_discount);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[3]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/**discount **/
					$final_price = ($double_price * $single_allergen) + ($double_with_culicoides * $culicoides_allergen);
					$final_price = $final_price - $order_discount;
				}
				$this->_data['final_price'] = $final_price;
				$this->_data['order_discount'] = $order_discount;
			} //if
		}

		/* if($data['lab_id'] == '13788' || $data['lab_id'] == '13786'){ */
		if($data['lab_id'] == '13786'){
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

		$orderData = []; $serumData = [];
		if (!empty($this->input->post())) {
			if ($this->input->post('signaturesubmit') == 1) {
				$signature = $this->input->post('signature');
				$signatureFileName = time() . '.png';
				$signature = str_replace('data:image/png;base64,', '', $signature);
				$signature = str_replace(' ', '+', $signature);
				$data = base64_decode($signature);
				$file = FCPATH . SIGNATURE_PATH . $signatureFileName;
				file_put_contents($file, $data);

				$orderData['id'] = $id;
				$orderData['is_draft'] = 0;
				$orderData['signature'] = $signatureFileName;
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($order_details['order_type'] == '2' && $this->user_role == 1) {
					$orderData['is_confirmed'] = 1;
				}
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$orderhData['order_id'] = $id;
					$orderhData['text'] = 'New Order';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->addOrderHistory($orderhData);
					$this->session->set_flashdata('success', 'Order data has been saved successfully.');
					if (IS_LIVE == 'yes' && ($this->user_role == 1 || $this->user_role == 5) && $order_details['is_mail_sent'] == '0' && $order_details['order_type'] != '2') {
						$this->send_mail($id);
					}
					if ($order_details['order_type'] == '2' && $this->user_role != 1) {
						redirect('orders/serum_address/'. $id);
					}elseif($order_details['order_type'] == '2' && $this->user_role == 1 && $order_details['cep_id'] > 0 && $order_details['is_authorised'] == 0){
						if($order_details['serum_type'] == '1'){
							$this->authorisedPAXOrder($id);
						}else{
							$this->authorisedOrder($id);
						}
					}else{
						redirect('orders');
					}
				}
			}elseif($this->input->post('edit_summery') == 1) {
				$sdata = $this->OrdersModel->getSerumTestRecord($id);
				$sid = (!empty($sdata) && $sdata['id'] > 0) ? $sdata['id'] : "";
				$serumData['id'] = $sid;
				$serumData['order_id'] = $id;
				$serumData['major_symptoms'] = ($this->input->post('major_symptoms')[0] != '') ? implode(',', $this->input->post('major_symptoms')) : NULL;
				$serumData['other_symptom'] = $this->input->post('other_symptom');
				$appearPart = explode("/",$this->input->post('symptom_appear'));
				$serumData['symptom_appear_age'] = !empty($appearPart[1])?$appearPart[1]:'';
				$serumData['symptom_appear_age_month'] = !empty($appearPart[0])?$appearPart[0]:'';
				$serum_drawn_replaced_date = str_replace('/', '-', $this->input->post('serum_drawn_date'));
				$serumData['serum_drawn_date'] = ($serum_drawn_replaced_date != '') ? date("Y-m-d", strtotime($serum_drawn_replaced_date)) : NULL;
				$serumData['when_obvious_symptoms'] = ($this->input->post('when_obvious_symptoms')[0] != '') ? implode(',', $this->input->post('when_obvious_symptoms')) : NULL;
				$serumData['where_obvious_symptoms'] = ($this->input->post('where_obvious_symptoms')[0] != '') ? implode(',', $this->input->post('where_obvious_symptoms')) : NULL;
				$serumData['medication'] = ($this->input->post('medication') != '') ? $this->input->post('zoonotic_disease') : '0';
				$serumData['medication_desc'] = $this->input->post('medication_desc');
				$serumData['zoonotic_disease'] = ($this->input->post('zoonotic_disease') != '') ? $this->input->post('zoonotic_disease') : '0';
				$serumData['zoonotic_disease_dec'] = $this->input->post('zoonotic_disease_dec');
				$serumData['diagnosis_food'] = ($this->input->post('diagnosis_food') != '') ? $this->input->post('diagnosis_food'):'0';
				$serumData['other_diagnosis_food'] = $this->input->post('other_diagnosis_food');
				$serumData['food_challenge'] = ($this->input->post('food_challenge')[0] != '') ? implode(',', $this->input->post('food_challenge')) : NULL;
				$serumData['diagnosis_hymenoptera'] = ($this->input->post('diagnosis_hymenoptera') != '') ? $this->input->post('diagnosis_hymenoptera'):'0';
				$serumData['other_diagnosis_hymenoptera'] = $this->input->post('other_diagnosis_hymenoptera');
				$serumData['diagnosis_other'] = ($this->input->post('diagnosis_other') != '') ? $this->input->post('diagnosis_other'):'0';
				$serumData['other_diagnosis'] = $this->input->post('other_diagnosis');
				$serumData['regularly_exposed'] = ($this->input->post('regularly_exposed')[0] != '') ? implode(',', $this->input->post('regularly_exposed')) : NULL;
				$serumData['other_exposed'] = $this->input->post('other_exposed');
				$serumData['malassezia_infections'] = ($this->input->post('malassezia_infections')[0] != '') ? implode(',', $this->input->post('malassezia_infections')) : NULL;
				$serumData['receiving_drugs'] = ($this->input->post('receiving_drugs')[0] != '') ? implode(',', $this->input->post('receiving_drugs')) : NULL;
				$serumData['receiving_drugs_1'] = ($this->input->post('receiving_drugs_1') != '') ? $this->input->post('receiving_drugs_1'):'0';
				$serumData['receiving_drugs_2'] = ($this->input->post('receiving_drugs_2') != '') ? $this->input->post('receiving_drugs_2'):'0';
				$serumData['receiving_drugs_3'] = ($this->input->post('receiving_drugs_3') != '') ? $this->input->post('receiving_drugs_3'):'0';
				$serumData['receiving_drugs_4'] = ($this->input->post('receiving_drugs_4') != '') ? $this->input->post('receiving_drugs_4'):'0';
				$serumData['receiving_drugs_5'] = ($this->input->post('receiving_drugs_5') != '') ? $this->input->post('receiving_drugs_5'):'0';
				$serumData['receiving_drugs_6'] = ($this->input->post('receiving_drugs_6') != '') ? $this->input->post('receiving_drugs_6'):'0';
				$serumData['treatment_ectoparasites'] = ($this->input->post('treatment_ectoparasites') != '') ? $this->input->post('treatment_ectoparasites'):'0';
				$serumData['other_ectoparasites'] = $this->input->post('other_ectoparasites');
				$serumData['elimination_diet'] = ($this->input->post('elimination_diet') != '') ? $this->input->post('elimination_diet'):'0';
				$serumData['other_elimination'] = $this->input->post('other_elimination');
				$serumData['additional_information'] = $this->input->post('additional_information');
				$serumData['updated_by'] = $this->user_id;
				$serumData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->serum_test_add_edit($serumData);

				$orderData['id'] = $id;
				$orderData['is_draft'] = 0;
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				$orderData['veterinary_surgeon'] = ($this->input->post('veterinary_surgeon')!='') ? $this->input->post('veterinary_surgeon') : '';
				$orderData['phone_number'] = ($this->input->post('phone_number')!='') ? $this->input->post('phone_number') : '';
				$orderData['email'] = ($this->input->post('email')!='') ? $this->input->post('email') : '';
				$orderData['shipping_materials'] = ($this->input->post('shipping_materials') != '') ? '1' : '0';
				$orderData['allergens'] = ($this->input->post('allergens')[0] != '') ? json_encode($this->input->post('allergens')) : NULL;
				if ($order_details['order_type'] == '2' && $this->user_role == 1) {
					$orderData['is_confirmed'] = 1;
				}
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$orderhData['order_id'] = $id;
					$orderhData['text'] = 'New Order';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->addOrderHistory($orderhData);
					$this->session->set_flashdata('success', 'Order data has been saved successfully.');
					if (IS_LIVE == 'yes' && $this->user_role == 1 && $order_details['is_mail_sent'] == '0' && $order_details['order_type'] != '2') {
						$this->send_mail($id);
					}
					if ($order_details['order_type'] == '2' && $this->user_role != 1) {
						redirect('orders/serum_address/'. $id);
					}elseif($order_details['order_type'] == '2' && $this->user_role == 1 && $order_details['cep_id'] > 0 && $order_details['is_authorised'] == 0){
						if($order_details['serum_type'] == '1'){
							$this->authorisedPAXOrder($id);
						}else{
							$this->authorisedOrder($id);
						}
					}else{
						redirect('orders');
					}
				}
			} else {
				$orderData['id'] = $id;
				$orderData['is_draft'] = 0;
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($order_details['order_type'] == '2' && $this->user_role == 1) {
					$orderData['is_confirmed'] = 1;
				}
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$orderhData['order_id'] = $id;
					$orderhData['text'] = 'New Order';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->addOrderHistory($orderhData);
					$this->session->set_flashdata('success', 'Order data has been saved successfully.');
					if (IS_LIVE == 'yes' && $this->user_role == 1 && $order_details['is_mail_sent'] == '0' && $order_details['order_type'] != '2') {
						$this->send_mail($id);
					}
					if ($order_details['order_type'] == '2' && $this->user_role != 1) {
						redirect('orders/serum_address/'. $id);
					}elseif($order_details['order_type'] == '2' && $this->user_role == 1 && $order_details['cep_id'] > 0 && $order_details['is_authorised'] == 0){
						if($order_details['serum_type'] == '1'){
							$this->authorisedPAXOrder($id);
						}else{
							$this->authorisedOrder($id);
						}
					}else{
						redirect('orders');
					}
				}
			}
		}
		if (!empty($data)) {
			$this->_data['data'] = $data;
		}
		if ($order_details['order_type'] == '2') {
			$this->load->view("orders/serum_summary", $this->_data);
		}else{
			$this->load->view("orders/summary", $this->_data);
		}
	}

	function authorisedOrder($id = ''){
		if($id > 0){
			$data = $this->OrdersModel->getRecord($id);
			$cepData = $this->OrdersModel->getRecord($data['cep_id']);

			$resultData = $typeData = $allergenData = [];
			$this->db->select('*');
			$this->db->from('ci_serum_result');
			$this->db->where('nextVuId LIKE', $cepData['order_number']);
			$resultData = $this->db->get()->row();
			$lastrID = $resultData->result_id;
			$resultData->result_id = '';
			$resultData->nextVuId = $data['order_number'];
			$this->db->insert('ci_serum_result', $resultData);
			$newrID = $this->db->insert_id();

			$this->db->select('*');
			$this->db->from('ci_serum_result_type');
			$this->db->where('result_id',$lastrID);
			$this->db->where('limsTestCode NOT LIKE','HAEMOLYSED');
			$this->db->where('limsTestCode NOT LIKE','LIPOLYSED');
			$this->db->where('limsTestCode NOT LIKE','OTHER_QC');
			$stypeData = $this->db->get()->result();
			$typeData = $allergenData = [];
			foreach($stypeData as $stype){
				$typeData = $stype;
				$lasttID = $typeData->type_id;
				$typeData->type_id = '';
				$typeData->result_id = $newrID;
				$this->db->insert('ci_serum_result_type', $typeData);
				$newtID = $this->db->insert_id();

				$this->db->select('*');
				$this->db->from('ci_serum_result_allergens');
				$this->db->where('result_id',$lastrID);
				$this->db->where('type_id',$lasttID);
				$this->db->order_by('id', 'ASC');
				$salrgsData = $this->db->get()->result();
				$allergenData = [];
				foreach($salrgsData as $sresult){
					$allergenData = $sresult;
					$allergenData->id = '';
					$allergenData->result_id = $newrID;
					$allergenData->type_id = $newtID;
					$this->db->insert('ci_serum_result_allergens', $allergenData);
				}
			}

			$orderData['id'] = $id;
			$orderData['is_authorised'] = 1;
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);
			redirect('orders');
		}
	}

	function authorisedPAXOrder($id = ''){
		if($id > 0){
			$data = $this->OrdersModel->getRecord($id);
			$cepData = $this->OrdersModel->getRecord($data['cep_id']);

			$resultData = $allergenData = [];
			$this->db->select('*');
			$this->db->from('ci_raptor_serum_result');
			$this->db->where('nextvu_id LIKE', $cepData['order_number']);
			$resultData = $this->db->get()->row();
			$lastrID = $resultData->result_id;
			$resultData->result_id = '';
			$resultData->nextvu_id = $data['order_number'];
			$this->db->insert('ci_raptor_serum_result', $resultData);
			$newrID = $this->db->insert_id();

			$this->db->select('*');
			$this->db->from('ci_raptor_result_allergens');
			$this->db->where('result_id',$lastrID);
			$stypeData = $this->db->get()->result();
			$allergenData = [];
			foreach($stypeData as $stype){
				$allergenData = $stype;
				$allergenData->id = '';
				$allergenData->result_id = $newrID;
				$this->db->insert('ci_raptor_result_allergens', $allergenData);
			}

			$orderData['id'] = $id;
			$orderData['is_confirmed'] = 1;
			$orderData['is_raptor_result'] = 1;
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);
			redirect('orders');
		}
	}

	function delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->OrdersModel->delete($dataWhere);
			if ($delete) {
				echo "success";
				exit;
			}
		}
		echo "failed";
		exit;
	}

	public function Hold($id){
		if ($id != '' && is_numeric($id)){
			$dataWhere['id'] = $id;
			$hold = $this->OrdersModel->Hold($dataWhere);
			if($hold){
				$orderhData['order_id'] = $id;
				$orderhData['text'] = 'Hold';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->addOrderHistory($orderhData);
				redirect('orders');
			}
		}
	}

	public function UnHold($id){
		if ($id != '' && is_numeric($id)){
			$dataWhere['id'] = $id;
			$hold = $this->OrdersModel->UnHold($dataWhere);
			if($hold){
				$orderhData['order_id'] = $id;
				$orderhData['text'] = 'UnHold';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->addOrderHistory($orderhData);
				redirect('orders');
			}
		}
	}

	public function Cancel($id){
		if ($id != '' && is_numeric($id)){
			$dataWhere['id'] = $id;
			$Cancel = $this->OrdersModel->Cancel($dataWhere);
			if($Cancel){
				$orderhData['order_id'] = $id;
				$orderhData['text'] = 'Cancel';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->addOrderHistory($orderhData);
				redirect('orders');
			}
		}
	}

	public function comment(){
		$id = $this->input->post('order_id_commnet_modal');
		if($id != '' && is_numeric($id)){
			$ordercomment['id'] = $id;
			if ($this->user_role == 1){
				$ordercomment['comment'] = $this->input->post('comment');
				$ordercomment['internal_comment'] = !empty($this->input->post('internal_comment'))?$this->input->post('internal_comment'):'';
				$ordercomment['practice_lab_comment'] = $this->input->post('practice_lab_comment');
			}else{
				$ordercomment['comment'] = '';
				$ordercomment['internal_comment'] = !empty($this->input->post('internal_comment'))?$this->input->post('internal_comment'):'';
				$ordercomment['practice_lab_comment'] = $this->input->post('comment');
				$ordercomment['comment_by'] = $this->user_id;
			}
			$ordercomment['updated_by'] = $this->user_id;
			$ordercomment['updated_at'] = date("Y-m-d H:i:s");
			$update = $this->OrdersModel->add_comment($ordercomment);
			if ($update) {
				$ajax["status"] = 'success';
				echo json_encode($ajax);
				exit;
			}
		}else{
			$ajax["status"] = 'faill';
				echo json_encode($ajax);
				exit;
		}
	}

	public function comment_get(){
		$id = $this->input->post('id');
		if($id != '' && is_numeric($id)){
			$update = $this->OrdersModel->get_comment($id);
			if ($update) {
				$ajax["status"] = 'success';
				if ($this->user_role == 1 || ($this->user_role == '5' && $this->session->userdata('user_type') == '3')){
					$ajax["comment_order"] = $update['comment'];
					$ajax["internal_comment"] = $update['internal_comment'];
					$ajax["practice_lab_comment"] = $update['practice_lab_comment'];
				}elseif($update['comment_by'] == $this->user_id){
					$ajax["comment_order"] = $update['practice_lab_comment'];
					$ajax["internal_comment"] = $update['internal_comment'];
					$ajax["practice_lab_comment"] = '';
				}else{
					$ajax["comment_order"] = '';
					$ajax["internal_comment"] = '';
					$ajax["practice_lab_comment"] = '';
				}
				echo json_encode($ajax);
				exit;
			}
		}else{
			$ajax["status"] = 'faill';
			echo json_encode($ajax);
			exit;
		}
	}

	function removeDoc(){
		$data = $this->input->post();
		if ($data['order_id'] != '' && is_numeric($data['order_id'])) {
			$delete = $this->OrdersModel->freeField($data['order_id']);
			unlink(FCPATH . SIC_DOC_PATH . '/' . $data['doc_name']);
			$this->session->set_flashdata("success", "SIC document has been removed successfully.");
			echo "success";
			exit;
		}
		echo "failed";
		exit;
	}

	function serum_address($id = ''){
		$data = $this->OrdersModel->allData($id);
		$this->_data['order_details'] = $data;
		$this->load->view("orders/serum_address", $this->_data);
	}

	function print_form($id = ''){
		$data = $this->OrdersModel->allData($id);
		$this->_data['order_details'] = $data;

		if($data['serum_type']=='1'){
			$html = $this->load->view('orders/print_pax_order_requisition_form', $this->_data, true);
		}else{
			$html = $this->load->view('orders/print_nextlab_order_requisition_form', $this->_data, true);
		}
		$html = trim($html);

		/* Start Using MPDF library */
		ob_end_flush();
		require_once(FCPATH.'vendor_pdf/autoload.php');
		$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
		$mpdf->SetTitle('Serum Request Form');
		$file_name = FCPATH . SERUM_REQUEST_PDF_PATH . "serum_request_form_" . $data['order_number'] . ".pdf";
		$mpdf->WriteHTML($html);
		$mpdf->Output($file_name,'F');
		//$mpdf->Output($file_name,'I');
		/* End Using MPDF library */
		redirect(SERUM_REQUEST_PDF_PATH.'serum_request_form_'.$data['order_number'].'.pdf');
		exit;
	}

	function getProductInfo(){
		$data = $this->input->post();
		$prod_details = $this->OrdersModel->getProductInfo($data['product_id']);
		$html = '<div class="product_title" style="text-align:center"><h3>'.$prod_details->name.'</h3></div><div class="product_infom"><p>'.$prod_details->product_info.'</p></div>';
		echo $html;
		exit();
	}

	function dashboardSeachPanelData(){
		$data = $this->input->post();
		$dashboardSearch = array(
			'filter_order_date'    => $data['date_range']
		);
		$this->session->set_userdata($dashboardSearch);
		echo "success";
		exit();
	}

	public function sync($id = ''){
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

		//is repeat order
		if ($data['is_repeat_order'] == '1') {
			$treatment_txt = "Maintenance Order";
		} else {
			$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
		$from_email = "Noreply@nextmune.com";

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

		$getAllergenParent = $this->AllergensModel->getAllergenParent($data['allergens']);
		$allergens_html = "";
		foreach ($getAllergenParent as $apkey => $apvalue) {
			$allergens_html .= "<tr><td><p><strong>" . $apvalue['name'] . "</strong></p>";

			$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
			foreach ($subAllergens as $skey => $svalue) {
				$allergens_html .= "<ul><li>" . $svalue['name'] . "</li></ul>";
			}
			$allergens_html .= "</td></tr>";
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
		if ($data['order_can_send_to'] == '1' && $data['lab_id'] > 0 && $data['plc_selection'] == '2') {
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
			// $display_address =  $data['delivery_branch_address'];
			// $display_address_1 = $data['delivery_branch_address1'];
			// $display_address_2 = $data['delivery_branch_address2'];
			// $display_address_3 = $data['delivery_branch_address3'];
			// $display_address_town_city = $data['delivery_branch_town_city'];
			// $display_address_county = $data['delivery_branch_county'];
			// $display_address_postcode = $data['delivery_branch_postcode'];
			// $postal_code = $data['delivery_branch_postcode'];
		} else if ($data['order_can_send_to'] == '1' && $data['delivery_practice_id'] > 0) {
			$display_name = $data['delivery_practice_name'] . " " . $data['delivery_practice_last_name'];
			$full_address = $display_name . " " . $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
			// $display_address = $add_1;
			// $display_address_1 = $add_2;
			// $display_address_2 = $add_3;
			// $display_address_3 = $add_4;
			// $display_address_town_city = $address_2;
			// $display_address_county = $data['country_name'];
			// $display_address_postcode = $address_3;
			// $postal_code = $address_3;
		} else if ($data['branch_id'] > 0) {
			$display_name = $data['branch_name'];
			$full_address =  $display_name . " " . $data['branch_address'] . " " . $data['branch_address1'] . " " . $data['branch_address2'] . " " . $data['branch_address3'] . " " . $data['town_city'] . $data['county'] . " " . $data['branch_postcode'];
			// $display_address =  $data['branch_address'];
			// $display_address_1 = $data['branch_address1'];
			// $display_address_2 = $data['branch_address2'];
			// $display_address_3 = $data['branch_address3'];
			// $display_address_town_city = $data['town_city'];
			// $display_address_county = $data['county'];
			// $display_address_postcode = $data['branch_postcode'];
			// $postal_code = $data['branch_postcode'];
		} else {
			$display_name = $data['practice_name'] . " " . $data['practice_last_name'];
			$full_address = $display_name . " " . $add_1 . " " . $add_2 . " " . $add_3 . " " . $add_4 . " " . $address_2 . " " . $data['country_name'] . " " . $address_3;
			// $display_address = $add_1;
			// $display_address_1 = $add_2;
			// $display_address_2 = $add_3;
			// $display_address_3 = $add_4;
			// $display_address_town_city = $address_2;
			// $display_address_county = $data['country_name'];
			// $display_address_postcode = $address_3;
			// $postal_code = $address_3;
		}

		/**if delivery address and name should be the branch details selected or if no branches use the practice */
		/**Practice name and address details */
		$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
		$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
		$p_column_field = explode('|', $p_usersDetails['column_field']);
		$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
		$p_address_2 = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
		$p_address_3 = isset($p_column_field[1]) ? $p_column_field[1] : NULL;
		$p_account_ref = isset($p_column_field[2]) ? $p_column_field[2] : NULL;
		$p_add_1 = isset($p_column_field[3]) ? $p_column_field[3] : NULL;
		$p_add_2 = isset($p_column_field[4]) ? $p_column_field[4] : NULL;
		$p_add_3 = isset($p_column_field[5]) ? $p_column_field[5] : NULL;
		$p_add_4 = isset($p_column_field[6]) ? $p_column_field[6] : NULL;
		// if( $data['lab_id'] > 0 && $data['plc_selection']=='2' ){
		//     $p_account_ref = $data['reference_number'];
		//     $account_number_label = 'Lab Account number';
		//     $p_display_name = $data['lab_name'];
		//     $display_address =  $l_address_1;
		//     $display_address_1 = $l_address_2;
		//     $display_address_2 = $l_address_3;
		//     $display_address_3 = $l_address_4;
		//     $display_address_town_city = $l_town_city;
		//     $display_address_county = '';
		//     $display_address_postcode = $l_post_code;
		//     $postal_code = $l_post_code;
		// }else if( $data['branch_id'] > 0 ){

		$order_type =$data['order_type'];
		if ($data['lab_id'] > 0 && $data['plc_selection'] == '2') {
			$account_number_label = 'Lab Account number';

			// $client_id = $data['lab_id'];
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

			// if($data['lab_id'] > 0 )
            // {
            //     $client_id = $data['lab_id'];
            // }else{
            //     $client_id = $data['vet_user_id'];
            // }
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
		
		$content_data = array('order_type'=> $order_type, 'account_number_label' => $account_number_label, 'client_id' => $client_id, 
			'order_number' => $data['order_number'], 
			'account_ref' => $p_account_ref,
			'qty_order' => $data['qty_order'],
			'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'], 'pet_name' => $data['pet_name'],
			'total' => $total, 'active_uk' => $active_uk, 'veterinarian_first' => $data['practice_name'],
			'veterinarian_last' => $data['practice_last_name'], 'veterinarian_email' => $data['practice_email'],
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
			'plc_selection'=>$data['plc_selection'], 'Unit_Price' => $data['unit_price'], 'Discount' => $data['order_discount']
		);
		$config = array (
			'root'    => 'root',
			'element' => 'element',
			'newline' => "\n",
			'tab'     => "\t"
		);

		$xml = new DOMDocument("1.0");
 
		// It will format the output in xml format otherwise
		// the output will be in a single row
		$xml->formatOutput=true;
		$Invoices=$xml->createElement("Invoices");
		$xml->appendChild($Invoices);
		$Invoice=$xml->createElement("Invoice");
		$Invoices->appendChild($Invoice);

		if($content_data['plc_selection']=='1'){
			$idorder =  $content_data['order_number'];
		}else{
			$idorder =  $content_data['account_ref'];
		}
		
		$id=$xml->createElement("Id", $id);
		$Invoice->appendChild($id);

		$accountReference=$xml->createElement("AccountReference", $content_data['account_ref']);
		$Invoice->appendChild($accountReference);

		$InvoiceNumber=$xml->createElement("InvoiceNumber", $idorder);
		$Invoice->appendChild($InvoiceNumber);

		$InvoiceDate=$xml->createElement("InvoiceDate", date('y-m-d h:m:s'));
		$Invoice->appendChild($InvoiceDate);

		$CustomerOrderNumber=$xml->createElement("CustomerOrderNumber", $idorder);
		$Invoice->appendChild($CustomerOrderNumber);

		$InvoiceAddress=$xml->createElement("InvoiceAddress");
		$Invoice->appendChild($InvoiceAddress);

		$name=$xml->createElement("Name", $content_data['your_name']);
		$InvoiceAddress->appendChild($name);

		$Address1=$xml->createElement("Address1", $content_data['display_address']);
		$InvoiceAddress->appendChild($Address1);

		$Address2=$xml->createElement("Address2", $content_data['display_address_1']);
		$InvoiceAddress->appendChild($Address2);

		$Address3=$xml->createElement("Address3", $content_data['display_address_2']);
		$InvoiceAddress->appendChild($Address3);

		$Address4=$xml->createElement("Address4", $content_data['display_address_3']);
		$InvoiceAddress->appendChild($Address4);

		$town=$xml->createElement("town", $content_data['display_address_town_city']);
		$InvoiceAddress->appendChild($town);

		$postal_code=$xml->createElement("postal_code", $content_data['postal_code']);
		$InvoiceAddress->appendChild($postal_code);

		$country=$xml->createElement("country", $content_data['country']);
		$InvoiceAddress->appendChild($country);

		$PetowenerName=$xml->createElement("PetowenerName" , $content_data['po_last']);
		$Invoice->appendChild($PetowenerName);

		$PetName=$xml->createElement("PetName" , $content_data['animal_name']);
		$Invoice->appendChild($PetName);

		$Species=$xml->createElement("Species" , $content_data['species']);
		$Invoice->appendChild($Species);

		$AllProduct=$xml->createElement("AllProduct");
		$Invoice->appendChild($AllProduct);

		$getAllergenParent = $this->AllergensModel->getAllergenParent($data['allergens']);
		$allergens_html = "";
		foreach ($getAllergenParent as $apkey => $apvalue) {
			$allergens = $apvalue['name'];

			$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
			foreach ($subAllergens as $skey => $svalue) {
				$allergens_html = $svalue['name'];
			}
			$products=$xml->createElement("Product" , $allergens_html);
			$AllProduct->appendChild($products);
		}

		$TotalProduct=$xml->createElement("TotalProduct" , $content_data['total_allergens']);
		$Invoice->appendChild($TotalProduct);

		$UnitPrice=$xml->createElement("UnitPrice" , $content_data['Unit_Price']);
		$Invoice->appendChild($UnitPrice);

		$DiscountPrice=$xml->createElement("DiscountPrice" , $content_data['Discount']);
		$Invoice->appendChild($DiscountPrice);
			
		$xml->save("report.xml");
		return redirect('orders/');
	}

	function updateOrdersStatusNL(){
		exit;
		/* $sql = "SELECT id FROM `ci_allergens` WHERE `order_type` LIKE '[\"5\",\"3\",\"6\"]'";
        $responce = $this->db->query($sql);
		$result = $responce->result_array();
		foreach($result as $arow){
			$updtData['order_type'] = '["1","3","6","5","7","8","9"]';
			$this->db->update('ci_allergens', $updtData, array('id'=>$arow['id']));
		}
		echo 'Done'; exit; */

		/* $this->db->select('oh.order_id, o.order_date, o.order_number, o.reference_number, o.is_confirmed, oh.text, oh.created_at, oh.created_by');
		$this->db->from('ci_orders as o');
		$this->db->join('ci_order_history as oh', 'oh.order_id = o.id');
		$this->db->where("oh.created_at >",'2022-09-14 01:00:00');
		$this->db->where("oh.created_by",0);
		$this->db->order_by("oh.id", "asc");
		$responce = $this->db->get();
		$result = $responce->result_array();
		foreach($result as $row){
			if($row['text'] == 'Sent to Netherlands'){
				$updtData['is_confirmed'] = '7';
			}elseif($row['text'] == 'In process'){
				$updtData['is_confirmed'] = '5';
			}elseif($row['text'] == 'Shipped'){
				$updtData['is_confirmed'] = '4';
			}elseif($row['text'] == 'Error on creation'){
				$updtData['is_confirmed'] = '6';
			}elseif($row['text'] == 'Confirmed'){
				$updtData['is_confirmed'] = '1';
			}
			$this->db->update('ci_orders', $updtData, array('id'=>$row['order_id']));
		} */

		/* $this->db->select('oh.order_id');
		$this->db->from('ci_orders as o');
		$this->db->join('ci_order_history as oh', 'oh.order_id = o.id');
		$this->db->where("oh.created_at >",'2022-09-27 01:00:00');
		$this->db->where("oh.text LIKE",'Shipped');
		$this->db->where("oh.created_by",0);
		$this->db->order_by("oh.id", "asc");
		$responce = $this->db->get();
		$result = $responce->result_array();
		foreach($result as $row){
			$this->db->select('order_id,text');
			$this->db->from('ci_order_history');
			$this->db->where("created_by",0);
			$this->db->where("text NOT LIKE",'Shipped');
			$this->db->where("order_id",$row['order_id']);
			$this->db->order_by("created_at", "DESC");
			$this->db->limit(1, 0);
			$responce = $this->db->get();
			$respnce = $responce->row();
			if($respnce->text == 'Sent to Netherlands'){
				$updtData['is_confirmed'] = '7';
			}elseif($respnce->text == 'In process'){
				$updtData['is_confirmed'] = '5';
			}elseif($respnce->text == 'Shipped'){
				$updtData['is_confirmed'] = '4';
			}elseif($respnce->text == 'Error on creation'){
				$updtData['is_confirmed'] = '6';
			}elseif($respnce->text == 'Confirmed'){
				$updtData['is_confirmed'] = '1';
			}
			$this->db->update('ci_orders', $updtData, array('id'=>$row['order_id']));
		} */
		exit;
	}

	function updateOrdersTable($oldID,$newID){
		$this->db->where('id', $oldID);
		$this->db->update('ci_orders', array("allergens"=>$newID,"updated_at"=>date("Y-m-d H:i:s")));
	}

	public function importAllergensRaptorCode(){
		exit;
		ini_set('memory_limit', '256M');
		$this->load->library('excel');
		$inputFileName = FCPATH.'uploaded_files/orderData/Updated_raptor_allergens.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$allergensId = !empty($value['A'])?$value['A']:'';
				$raptorCode = !empty($value['E'])?$value['E']:'';
				$raptorFunction = !empty($value['D'])?$value['D']:'';
				if($allergensId != ''){
					$this->db->select('id');
					$this->db->from('ci_allergens_raptor');
					$this->db->where('allergens_id', $allergensId);
					$this->db->where('raptor_code LIKE', $raptorCode);
					$res2 = $this->db->get();
					if($res2->num_rows() == 0){
						$insrtData['allergens_id'] = $allergensId;
						$insrtData['raptor_code'] = $raptorCode;
						$insrtData['raptor_function'] = $raptorFunction;
						$this->db->insert('ci_allergens_raptor',$insrtData);
					}else{
						$rID = $res2->row()->id;
						$updtData['raptor_code'] = $raptorCode;
						$updtData['raptor_function'] = $raptorFunction;
						$this->db->where('id', $rID);
						$this->db->update('ci_allergens_raptor', $updtData);
					}
				}
			}
			$i++;
		}
		echo $i .' Updated.';
		exit;
	}

	public function importAllergenswithRCode(){
		exit;
		ini_set('memory_limit', '256M');
		$this->load->library('excel');
		$inputFileName = FCPATH.'uploaded_files/orderData/NOT_IN_NEXTVU_Updated_raptor_allergens.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$groupId = !empty($value['A'])?$value['A']:'';
				$aName = !empty($value['B'])?$value['B']:'';
				$raptorCode = !empty($value['E'])?$value['E']:'';
				$raptorFunction = !empty($value['D'])?$value['D']:'';
				if($groupId != '' && $aName != ''){
					$this->db->select('id');
					$this->db->from('ci_allergens');
					$this->db->where('parent_id', $groupId);
					$this->db->where('name LIKE', $aName);
					$res1 = $this->db->get();
					if($res1->num_rows() == 0){
						$insrtaData['parent_id'] = $groupId;
						$insrtaData['name'] = $aName;
						$insrtaData['order_type'] = '["3","4","5"]';
						$insrtaData['code'] = NULL;
						$insrtaData['is_unavailable'] = 0;
						$insrtaData['unavailable_for'] = 0;
						$insrtaData['due_date'] = NULL;
						$insrtaData['is_mixtures'] = 0;
						$insrtaData['created_by'] = $this->user_id;
						$insrtaData['created_at'] = date("Y-m-d H:i:s");
						$this->db->insert('ci_allergens',$insrtaData);
						$allergensId = $this->db->insert_id();
					}else{
						$allergensId = $res1->row()->id;
					}
					$this->db->select('id');
					$this->db->from('ci_allergens_raptor');
					$this->db->where('allergens_id', $allergensId);
					$this->db->where('raptor_code LIKE', $raptorCode);
					$res2 = $this->db->get();
					if($res2->num_rows() == 0){
						$insrtData['allergens_id'] = $allergensId;
						$insrtData['raptor_code'] = $raptorCode;
						$insrtData['raptor_function'] = $raptorFunction;
						$this->db->insert('ci_allergens_raptor',$insrtData);
					}else{
						$rID = $res2->row()->id;
						$updtData['raptor_code'] = $raptorCode;
						$updtData['raptor_function'] = $raptorFunction;
						$this->db->where('id', $rID);
						$this->db->update('ci_allergens_raptor', $updtData);
					}
				}
			}
			$i++;
		}
		echo $i .' Updated.';
		exit;
	}

	public function importPAXInterpretation(){
		exit;
		/* ini_set('memory_limit', '512M');
		$this->load->library('excel');
		$inputFileName = FCPATH.'uploaded_files/orderData/221010_RAVEN_PAX1_EN_TO_no_to_nospecial.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $missingArr = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$raptorCode = !empty($value['A'])?$value['A']:'';
				$raptorInterpre = !empty($value['B'])?$value['B']:'';
				if($raptorCode != "" && $raptorInterpre != ""){
					$chnk1Arr = explode("_header",$raptorCode);
					$chnk2Arr = explode("_gc",$raptorCode);
					$chnk3Arr = explode("_sy",$raptorCode);
					$chnk4Arr = explode("_cr",$raptorCode);
					$chnk5Arr = explode("_to",$raptorCode);
					$chnk6Arr = explode("_ai",$raptorCode);
					if(isset($chnk1Arr[1]) && $chnk1Arr[1] == ""){
						$raptorCodeNew = $chnk1Arr[0];
						$addColumn = 'raptor_header';
					}elseif(isset($chnk2Arr[1]) && $chnk2Arr[1] == ""){
						$raptorCodeNew = $chnk2Arr[0];
						$addColumn = 'raptor_comments';
					}elseif(isset($chnk3Arr[1]) && $chnk3Arr[1] == ""){
						$raptorCodeNew = $chnk3Arr[0];
						$addColumn = 'raptor_symptoms';
					}elseif(isset($chnk4Arr[1]) && $chnk4Arr[1] == ""){
						$raptorCodeNew = $chnk4Arr[0];
						$addColumn = 'raptor_reactivity';
					}elseif(isset($chnk5Arr[1]) && $chnk5Arr[1] == ""){
						$raptorCodeNew = $chnk5Arr[0];
						$addColumn = 'raptor_option';
					}elseif(isset($chnk6Arr[1]) && $chnk6Arr[1] == ""){
						$raptorCodeNew = $chnk6Arr[0];
						$addColumn = 'raptor_information';
					}else{
						$raptorCodeNew = $chnk6Arr[0];
						$addColumn = 'raptor_header';
					}
					$this->db->select('id');
					$this->db->from('ci_allergens_raptor');
					$this->db->where('raptor_code LIKE', $raptorCodeNew);
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$updtData = [];
						$rID = $res2->row()->id;
						$updtData[''.$addColumn.''] = $raptorInterpre;
						$this->db->where('id', $rID);
						$this->db->update('ci_allergens_raptor', $updtData);
					}else{
						$missingArr[] = $raptorCode;
					}
				}
			}
			$i++;
		}
		echo '<prE>';
		print_r($missingArr);
		echo $i .' Updated.';
		exit; */
	}

	public function send_mail_NL(){
		exit;
		$this->load->model('RecipientsModel');

		$this->db->select('id,order_date,order_number,reference_number,is_confirmed,is_invoiced');
		$this->db->from('ci_orders');
		$this->db->where('lab_id', '13786');
		$this->db->where('order_date >', '2022-06-16');
		$this->db->where('order_number >', '38512');
		$this->db->where('is_confirmed', '1');
		$this->db->where('is_draft', '0');
		$this->db->limit('40', '0');
		$datas = $this->db->get()->result_array();
		foreach($datas as $data_detail){
			$id = $data_detail['id'];
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

			//is repeat order
			if ($data['is_repeat_order'] == '1') {
				$treatment_txt = "Maintenance Order";
			} else {
				$treatment_txt = "Initial treatment = the first immunotherapy treatment for the patient";
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
			$from_email = "Noreply@nextmune.com";

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

			/**if delivery address and name should be the branch details selected or if no branches use the practice */
			/**Practice name and address details */
			$p_userData = array("user_id" => $data['vet_user_id'], "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
			$p_usersDetails = $this->UsersDetailsModel->getColumnField($p_userData);
			$p_column_field = explode('|', $p_usersDetails['column_field']);
			$client_id = isset($p_column_field[0]) ? $p_column_field[0] : NULL;
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
		
			$content_data = array(
				'order_type'=> $order_type, 'account_number_label' => $account_number_label, 'client_id' => $client_id, 
				'order_number' => $data['order_number'], 
				'account_ref' => $p_account_ref,
				'qty_order' => $data['qty_order'],
				'unit_price' => $data['unit_price'], 'order_date' => $order_date, 'order_discount' => $data['order_discount'], 'pet_name' => $data['pet_name'],
				'total' => $total, 'active_uk' => $active_uk, 'veterinarian_first' => $data['practice_name'],
				'veterinarian_last' => $data['practice_last_name'], 'veterinarian_email' => $data['practice_email'],
				'veterinarian_phone' => $data['branch_number'], 'clinic_name' => $p_display_name, 'clinic_add' => $full_address,
				'postal_code' => $postal_code, 'city' => $address_2, 'country' => $practice_country,
				'order_sent_to' => $full_address, 'invoice_sent_to' => 'The clinic address above',
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
				'plc_selection'=>$data['plc_selection'],'practice_lab_comment'=>$data['practice_lab_comment']
			);

			//save pdf
			$dompdf = new Dompdf(array('enable_remote' => true));

			$html = $this->load->view('orders/order_mail_template', $content_data, true);
			$html = trim($html);

			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'Portrait');
			$dompdf->render();
			// write pdf to a file
			$pdf = $dompdf->output();
			if($data['plc_selection']=='1'){
				$content_data['order_number'] = $data['order_number'];
			}else{
				$content_data['order_number'] = $data['reference_number'];
			}
			$file = FCPATH . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";
			file_put_contents($file, $pdf);

			$sicdoc = FCPATH . SIC_DOC_PATH . "/" . $data['sic_document'];
			$attach_pdf = base_url() . ORDERS_PDF_PATH . "order_" . $content_data['order_number'] . ".pdf";

			$this->load->view('orders/order_mail_content_template', $content_data); //no exit for view template
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);

			//Load email library 
			$this->load->library('email', $config);
			$content_data['recipient_name'] = "Hello Netherlands";
			$content_data['content_body'] = 'Please proceed with the attached order.';
			$to_email = RECIEVER_EMAIL;

			$this->email->from($from_email, "Nextmune");
			$this->email->to($to_email);
			/* $this->email->set_header('Content-Type', 'application/pdf');
			$this->email->set_header('Content-Disposition', 'attachment');
			$this->email->set_header('Content-Transfer-Encoding', 'base64'); */
			$this->email->subject('Order Details - '.$content_data['order_number']);
			$msg_content = $this->load->view('orders/order_mail_content_template', $content_data, true);
			$this->email->message($msg_content);
			$this->email->set_mailtype("html");
			$this->email->attach($file);
			if ($sicdoc != '') {
				$this->email->attach($sicdoc);
			}
			$is_send = $this->email->send();
			//Send mail 
			if ($is_send) {
				echo $id. "Email sent successfully."; echo '<br>';
			} else {
				echo $id. $this->email->print_debugger(); echo '<br>';
			}
		}
	}

	public function changeOrderAllergens(){
		exit;
		ini_set('memory_limit', '256M');
		$this->load->library('excel');
		$inputFileName = FCPATH.'uploaded_files/orderData/Aspergillus_issue.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$batchNumber = $value['A'];
				if($batchNumber != ''){
					$this->db->select('id,order_date,batch_number,order_number,reference_number,allergens');
					$this->db->from('ci_orders');
					$this->db->where('batch_number', $batchNumber);
					$this->db->where('is_draft', '0');
					$row = $this->db->get()->row_array();
					if(!empty($row)){
						$newID = str_replace('"67"','"44687"',$row['allergens']);
						$this->updateOrdersTable($row['id'],$newID);
						$x++;
					}
				}
			}
			$i++;
		}
		//echo $i-2 .' IMport.';
		echo $x .' Updated.';
		exit;
	}

	public function authorisedConfirmed($id){
		if ($id != '' && is_numeric($id)){
			$data = $this->OrdersModel->allData($id);
			$order_number = $data['order_number'];
			$serumType = $this->OrdersModel->getSerumTestType($order_number);
			if(!empty($serumType)){
				$stypeIDArr = array(); $sresultIDArr = array(); 
				foreach($serumType as $stype){
					$stypeIDArr[] = $stype->type_id;
					$sresultIDArr[] = $stype->result_id;
				}

				$stypeID = implode(",",$stypeIDArr);
				$sresultID = implode(",",$sresultIDArr);
				$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
				if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$grassesP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 5){
								$grassesP++;
							}
						}
					}
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$weedsP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResultwed = $this->db->get()->row();
						if(!empty($serumResultwed)){
							if($serumResultwed->result >= 5){
								$weedsP++;
							}
						}
					}
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$treesP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResulttres = $this->db->get()->row();
						if(!empty($serumResulttres)){
							if($serumResulttres->result >= 5){
								$treesP++;
							}
						}
					}
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$cropsP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResultcrp = $this->db->get()->row();
						if(!empty($serumcResultcrp)){
							if($serumcResultcrp->result >= 5){
								$cropsP++;
							}
						}
					}
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$indoorP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$indoorResults = $this->db->get()->row();
						if(!empty($indoorResults)){
							if($indoorResults->result >= 5){
								$indoorP++;
							}
						}
					}
					/* End Indoor(Mites/Moulds/Epithelia) */

					if($grassesP > 0 || $weedsP > 0 || $treesP > 0 || $cropsP > 0 || $indoorP > 0){
						$orderData['is_expand'] = '1';
					}else{
						$orderData['is_expand'] = '0';
					}
				}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$grassesP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 5){
								$grassesP++;
							}
						}
					}
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$weedsP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResultwed = $this->db->get()->row();
						if(!empty($serumResultwed)){
							if($serumResultwed->result >= 5){
								$weedsP++;
							}
						}
					}
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$treesP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResulttres = $this->db->get()->row();
						if(!empty($serumResulttres)){
							if($serumResulttres->result >= 5){
								$treesP++;
							}
						}
					}
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$cropsP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResultcrp = $this->db->get()->row();
						if(!empty($serumcResultcrp)){
							if($serumcResultcrp->result >= 5){
								$cropsP++;
							}
						}
					}
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$indoorP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$indoorResults = $this->db->get()->row();
						if(!empty($indoorResults)){
							if($indoorResults->result >= 5){
								$indoorP++;
							}
						}
					}
					/* End Indoor(Mites/Moulds/Epithelia) */
					if($grassesP > 0 || $weedsP > 0 || $treesP > 0 || $cropsP > 0 || $indoorP > 0){
						$orderData['is_expand'] = '1';
					}else{
						$orderData['is_expand'] = '0';
					}
				}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$protnFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 5){
								$protnFPP++;
							}
						}
					}
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$carboFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 5){
								$carboFCP++;
							}
						}
					}
					/* End Food Carbohydrates */
					if($protnFPP > 0 || $carboFCP > 0){
						$orderData['is_expand'] = '1';
					}else{
						$orderData['is_expand'] = '0';
					}
				}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$grassesP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 5){
								$grassesP++;
							}
						}
					}
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$weedsP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResultwed = $this->db->get()->row();
						if(!empty($serumResultwed)){
							if($serumResultwed->result >= 5){
								$weedsP++;
							}
						}
					}
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$treesP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResulttres = $this->db->get()->row();
						if(!empty($serumResulttres)){
							if($serumResulttres->result >= 5){
								$treesP++;
							}
						}
					}
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$cropsP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResultcrp = $this->db->get()->row();
						if(!empty($serumcResultcrp)){
							if($serumcResultcrp->result >= 5){
								$cropsP++;
							}
						}
					}
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$indoorP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$indoorResults = $this->db->get()->row();
						if(!empty($indoorResults)){
							if($indoorResults->result >= 5){
								$indoorP++;
							}
						}
					}
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$protnFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 5){
								$protnFPP++;
							}
						}
					}
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$carboFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 5){
								$carboFCP++;
							}
						}
					}
					/* End Food Carbohydrates */
					if($grassesP > 0 || $weedsP > 0 || $treesP > 0 || $cropsP > 0 || $indoorP > 0 || $protnFPP > 0 || $carboFCP > 0){
						$orderData['is_expand'] = '1';
					}else{
						$orderData['is_expand'] = '0';
					}
				}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$grassesP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 5){
								$grassesP++;
							}
						}
					}
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$weedsP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResultwed = $this->db->get()->row();
						if(!empty($serumResultwed)){
							if($serumResultwed->result >= 5){
								$weedsP++;
							}
						}
					}
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$treesP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResulttres = $this->db->get()->row();
						if(!empty($serumResulttres)){
							if($serumResulttres->result >= 5){
								$treesP++;
							}
						}
					}
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$cropsP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResultcrp = $this->db->get()->row();
						if(!empty($serumcResultcrp)){
							if($serumcResultcrp->result >= 5){
								$cropsP++;
							}
						}
					}
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$indoorP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$indoorResults = $this->db->get()->row();
						if(!empty($indoorResults)){
							if($indoorResults->result >= 5){
								$indoorP++;
							}
						}
					}
					/* End Indoor(Mites/Moulds/Epithelia) */
					if($grassesP > 0 || $weedsP > 0 || $treesP > 0 || $cropsP > 0 || $indoorP > 0){
						$orderData['is_expand'] = '1';
					}else{
						$orderData['is_expand'] = '0';
					}
				}
			}else{
				$orderData['is_expand'] = '0';
			}

			$orderData['id'] = $id;
			$orderData['is_confirmed'] = '4';
			$orderData['is_authorised'] = '2';
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);

			$orderhData['order_id'] = $id;
			$orderhData['text'] = 'Authorised Confirmed';
			$orderhData['created_by'] = $this->user_id;
			$orderhData['created_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->addOrderHistory($orderhData);
			$this->session->set_flashdata('success', 'Order has been confirmed successfully.');
			$this->send_serum_mail($id, 1);
		}
	}

	public function send_serum_mail($id = '', $is_confirmed = 0){
		ini_set('memory_limit', '256M');
		$this->_data['data'] = [];
		$data = $this->OrdersModel->allData($id);
		$order_number = $data['order_number'];
		$product_type = $data['product_code_selection'];
		$species_name = $data['species_name'];
		$this->_data['serumData'] = $this->OrdersModel->getSerumTestRecord($id);
		$this->_data['serumType'] = $this->OrdersModel->getSerumTestType($order_number);
		$this->_data['order_details'] = $data;
		$this->_data['id'] = $id;

		if($data['serum_type'] == 1){
			$this->_data['serumTypes'] = 'PAX';
		}else{
			$this->_data['serumTypes'] = 'NextLab';
		}
		$emailBody = $this->load->view('orders/send_serum_mail_template', $this->_data, true);
		$emailBody = trim($emailBody);

		if($data['lab_id']>0 && ($data['lab_id']=='13401' || $data['lab_id']=='13789' || $data['lab_id']=='28995' || $data['lab_id']=='29164' || $data['lab_id']=='28994')){
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$dochtml = ''; $fileName = ''; $redirectlink = '';
			$redirectlink = 'orders/treatment/';
			$order_number = $data['order_number'];
			$serumType = $this->OrdersModel->getSerumTestType($order_number);
			if(!empty($serumType)){
				$stypeIDArr = array(); $sresultIDArr = array(); 
				foreach($serumType as $stype){
					$stypeIDArr[] = $stype->type_id;
					$sresultIDArr[] = $stype->result_id;
				}
			}
			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			if(!empty($respnedn)){
				$dochtml .= '<p><b>Animal Name:</b> '.$data['pet_name'].'</p>';
				$dochtml .= '<p><b>Owner name:</b> '.$data['pet_owner_name'].' '.$data['po_last'].'</p>';
				$dochtml .= '<p><b>Species:</b> '.$data['species_name'].'</p>';
				$dochtml .= '<p><b>Synlab Lab Ref:</b> '.$data['reference_number'].'</p>';
				$dochtml .= '<p><b>Nextmune Ref:</b> '.$data['lab_order_number'].'</p>';
				$dochtml .= '<p></p>';
				if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
					$fileName = 'NextLab_SCREEN_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					$dochtml .= '<p><b>Complete Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if(!empty($serumfResults)){
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Environmental_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */
				}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental_Panel_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */
				}elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){
					$fileName = 'NextLab_Complete_Food_Panel_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if(!empty($serumfResults)){
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */

					$dochtml .= '<p><b>Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if($serumfResults->result >= 10){
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">POSITIVE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">BORDER LINE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}else{
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">NEGATIVE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental+Food_SCREEN_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */

					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
					$fileName = 'NextLab_SCREEN_Environmental+Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */
					
					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
					$fileName = 'NextLab_Complete_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */

					if(preg_match('/\bFood\b/', $respnedn->name)){
						$dochtml .= '<p><b>Food Panel</b></p>';
						$dochtml .= '<p></p>';
						$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
						foreach($getAllergenFParent as $rowf){
							$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
							$dochtml .= '<p></p>';
							$dochtml .= '<table><tbody>';
							$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
							foreach($subfAllergens as $sfvalue){
								$this->db->select('*');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
								$this->db->order_by('id', 'ASC');
								$serumfResults = $this->db->get()->row();
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
							$dochtml .= '</tbody></table>';
							$dochtml .= '<p></p>';
						}
					}
				}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */
					
					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					if(preg_match('/\bFood\b/', $respnedn->name)){
						$dochtml .= '<p><b>Food Panel</b></p>';
						$dochtml .= '<p></p>';
						$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
						foreach($getAllergenFParent as $rowf){
							$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
							$dochtml .= '<p></p>';
							$dochtml .= '<table><tbody>';
							$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
							foreach($subfAllergens as $sfvalue){
								$this->db->select('*');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
								$this->db->order_by('id', 'ASC');
								$serumfResults = $this->db->get()->row();
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
							$dochtml .= '</tbody></table>';
							$dochtml .= '<p></p>';
						}
					}
				}
			}
			if($dochtml != ''){
				$this->load->library('word');
				$htd = new Word();
				$htd->createDoc($dochtml,$fileName,true);
			}
			$file1 = "uploaded_files/word_files/".$fileName.".doc";
		}elseif($data['lab_id'] > 0 && $data['lab_id']=='13788'){
			$this->_data['order_details'] = $data;
			$this->_data['data'] = $data;
			$serumType = $this->OrdersModel->getSerumTestType($data['order_number']);
			if(!empty($serumType)){
				$stypeIDArr = array(); $sresultIDArr = array(); 
				foreach($serumType as $stype){
					$stypeIDArr[] = $stype->type_id;
					$sresultIDArr[] = $stype->result_id;
				}
			}
			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			$this->_data['stypeID'] = $stypeID;
			$this->_data['sresultID'] = $sresultID;

			$this->_data['breedinfo'] = [];
			if($data['pet_id'] > 0){
				$this->db->select('type,breed_id,other_breed,gender,age,age_year');
				$this->db->from('ci_pets');
				$this->db->where('id', $data['pet_id']);
				$petinfo = $this->db->get()->row_array();
				if($petinfo['breed_id']>0){
					$this->db->select('name');
					$this->db->from('ci_breeds');
					$this->db->where('id', $petinfo['breed_id']);
					$this->_data['breedinfo'] = $this->db->get()->row_array();
				}else{
					if($petinfo['other_breed']!=""){
						$this->_data['breedinfo'] = array("name"=>$petinfo['other_breed']);
					}else{
						$this->_data['breedinfo'] = array("name"=>'');
					}
				}
			}else{
				$this->_data['breedinfo'] = array("name"=>'');
			}

			$raptorData = $this->OrdersModel->getRaptorData($data['order_number']);
			$file_name = FCPATH . SERUM_REQUEST_PDF_PATH . "Nextlab_Serum_Test_Result_". $data['order_number'] .".pdf";
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$this->_data['respnedn'] = $respnedn;
			$this->_data['ordeType'] = $respnedn->name;
			$this->_data['ordeTypeID'] = $respnedn->id;
			ob_end_flush();
			require_once(FCPATH.'vendor_pdf/autoload.php');
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4',]);
			if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);

				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);
			}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);
			}elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenfood', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_food_pdf = $this->load->view('nwl_pdf/nwl_screen_food', $this->_data, true);
				$mpdf->WriteHTML($screen_food_pdf);
			}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenfood', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_food_pdf = $this->load->view('nwl_pdf/nwl_screen_food', $this->_data, true);
				$mpdf->WriteHTML($screen_food_pdf);
			}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);
				if(preg_match('/\bFood\b/', $respnedn->name)){
					$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLHeader($header_food_pdf);
					$mpdf->AddPage('','','','','',20,0,65,35,5,5);
					$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
					$mpdf->WriteHTML($complete_food_pdf);
				}
			}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);
				if(preg_match('/\bFood\b/', $respnedn->name)){
					$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLHeader($header_food_pdf);
					$mpdf->AddPage('','','','','',20,0,65,35,5,5);
					$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
					$mpdf->WriteHTML($complete_food_pdf);
				}
			}

			$mpdf->Output($file_name,'F');
			$file1 = SERUM_REQUEST_PDF_PATH . "Nextlab_Serum_Test_Result_". $order_number .".pdf";
		}else{
			$html1 = $this->load->view('orders/serum_result_pdf', $this->_data, true);
			$html1 = trim($html1);

			ob_end_flush();
			require_once(FCPATH.'vendor_pdf/autoload.php');
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf->SetTitle('Serum Test Results');

			$files = FCPATH . SERUM_REQUEST_PDF_PATH . "Nextlab_Serum_Test_Result_". $order_number .".pdf";

			$mpdf->WriteHTML($html1);
			$mpdf->Output($files,'F');
			$file1 = SERUM_REQUEST_PDF_PATH . "Nextlab_Serum_Test_Result_". $order_number .".pdf";
		}
		$from_email = "vetorders.uk@nextmune.com";
		$content_data['order_number'] = $order_number;
		$content_data['recipient_name'] = "Dear ". $data['name'];
		$content_data['content_body'] = 'These treatment options are available for you on the ordering platform Nextvu.<br><br>
			Click this <a href="'. FCPATH .'orders/treatment/'.$id.'" title="">link</a> to order your vaccine.';
		$to_email = $data['email'];
		//$to_email = 'stewart_nextmune@yopmail.com';
		$config = array(
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		$this->email->from($from_email, "Nextmune");
		$this->email->to($to_email);
		$this->email->subject('Serum Test Result - '.$order_number);
		$this->email->message($emailBody);
		$this->email->set_mailtype("html");
		$this->email->attach($file1);

		$is_send = $this->email->send();
		if ($is_send) {
			$orderData['id'] = $id;
			$orderData['is_mail_sent'] = 1;
			$orderData['is_serum_result_sent'] = 1;
			$orderData['is_order_completed'] = 1;
			$this->OrdersModel->add_edit($orderData);
		} else {
			$this->session->set_flashdata("error", $this->email->print_debugger());
		}
		redirect('orders');
		exit;
	}

	public function getLIMSSerumResultPDF($id = ''){
		ini_set('memory_limit', '256M');
		$this->_data['data'] = [];
		$data = $this->OrdersModel->allData($id);
		$order_number = $data['order_number'];
		$product_type = $data['product_code_selection'];
		$species_name = $data['species_name'];
		$this->_data['serumData'] = $this->OrdersModel->getSerumTestRecord($id);
		$this->_data['serumType'] = $this->OrdersModel->getSerumTestType($order_number);
		$this->_data['order_details'] = $data;
		$this->_data['id'] = $id;

		if($data['serum_type'] == 1){
			$this->_data['serumTypes'] = 'PAX';
		}else{
			$this->_data['serumTypes'] = 'NextLab';
		}

		if($data['lab_id'] > 0 && $data['lab_id']=='13788'){
			$this->_data['order_details'] = $data;
			$this->_data['data'] = $data;
			$serumType = $this->OrdersModel->getSerumTestType($data['order_number']);
			if(!empty($serumType)){
				$stypeIDArr = array(); $sresultIDArr = array(); 
				foreach($serumType as $stype){
					$stypeIDArr[] = $stype->type_id;
					$sresultIDArr[] = $stype->result_id;
				}
			}
			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			$this->_data['stypeID'] = $stypeID;
			$this->_data['sresultID'] = $sresultID;

			$this->_data['breedinfo'] = [];
			if($data['pet_id'] > 0){
				$this->db->select('type,breed_id,other_breed,gender,age,age_year');
				$this->db->from('ci_pets');
				$this->db->where('id', $data['pet_id']);
				$petinfo = $this->db->get()->row_array();
				if($petinfo['breed_id']>0){
					$this->db->select('name');
					$this->db->from('ci_breeds');
					$this->db->where('id', $petinfo['breed_id']);
					$this->_data['breedinfo'] = $this->db->get()->row_array();
				}else{
					if($petinfo['other_breed']!=""){
						$this->_data['breedinfo'] = array("name"=>$petinfo['other_breed']);
					}else{
						$this->_data['breedinfo'] = array("name"=>'');
					}
				}
			}else{
				$this->_data['breedinfo'] = array("name"=>'');
			}

			$raptorData = $this->OrdersModel->getRaptorData($data['order_number']);
			$file_name = FCPATH . SERUM_REQUEST_PDF_PATH . "Nextlab_Serum_Test_Result_". $data['order_number'] .".pdf";
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$this->_data['respnedn'] = $respnedn;
			$this->_data['ordeType'] = $respnedn->name;
			$this->_data['ordeTypeID'] = $respnedn->id;
			ob_end_flush();
			require_once(FCPATH.'vendor_pdf/autoload.php');
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4',]);
			if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);

				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);
			}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);
			}elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
				$mpdf->WriteHTML($complete_food_pdf);
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenfood', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_food_pdf = $this->load->view('nwl_pdf/nwl_screen_food', $this->_data, true);
				$mpdf->WriteHTML($screen_food_pdf);
			}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);

				$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenfood', $this->_data, true);
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetHTMLHeader($header_food_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_food_pdf = $this->load->view('nwl_pdf/nwl_screen_food', $this->_data, true);
				$mpdf->WriteHTML($screen_food_pdf);
			}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_env', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$complete_environmental_pdf = $this->load->view('nwl_pdf/nwl_complete_environmental', $this->_data, true);
				$mpdf->WriteHTML($complete_environmental_pdf);
				if(preg_match('/\bFood\b/', $respnedn->name)){
					$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLHeader($header_food_pdf);
					$mpdf->AddPage('','','','','',20,0,65,35,5,5);
					$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
					$mpdf->WriteHTML($complete_food_pdf);
				}
			}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
				$main_header_pdf = $this->load->view('nwl_pdf/nwl_pdf_main_header', $this->_data, true);
				$header_env_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_screenenv', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('Nextlab Serum Test Result');
				$mpdf->SetHTMLHeader($main_header_pdf);
				$mpdf->SetHTMLHeader($header_env_pdf);
				$mpdf->AddPage('','','','','',20,0,65,35,5,5);
				$screen_environmental_pdf = $this->load->view('nwl_pdf/nwl_screen_environmental', $this->_data, true);
				$mpdf->WriteHTML($screen_environmental_pdf);
				if(preg_match('/\bFood\b/', $respnedn->name)){
					$header_food_pdf = $this->load->view('nwl_pdf/nwl_serum_result_pdf_header_food', $this->_data, true);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLHeader($header_food_pdf);
					$mpdf->AddPage('','','','','',20,0,65,35,5,5);
					$complete_food_pdf = $this->load->view('nwl_pdf/nwl_complete_food', $this->_data, true);
					$mpdf->WriteHTML($complete_food_pdf);
				}
			}

			$fileName = "Nextlab_Serum_Test_Result_". $order_number .".pdf";
			$mpdf->Output($fileName,'D');
		}else{
			$html1 = $this->load->view('orders/serum_result_pdf', $this->_data, true);
			$html1 = trim($html1);

			ob_end_flush();
			require_once(FCPATH.'vendor_pdf/autoload.php');
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf->SetTitle('Serum Test Results');

			$fileName = "Nextlab_Serum_Test_Result_". $order_number .".pdf";

			$mpdf->WriteHTML($html1);
			$mpdf->Output($fileName,'D');
		}
	}

	function createSerumFoodPDF($data=[]){
		$dompdf = new Dompdf(array('enable_remote' => true));
		$html3 = $this->load->view('orders/serum_food_result_pdf', $data, true);
		$html3 = trim($html3);
		$dompdf->loadHtml($html3);
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$pdf3 = $dompdf->output();
		$file3 = FCPATH . SERUM_REQUEST_PDF_PATH . "serum_test_result_food_". $order_number .".pdf";
		file_put_contents($file3, $pdf3);
		return $file3;
	}

	function createTreatmentPDF($data=[]){
		$dompdf = new Dompdf(array('enable_remote' => true));
		$html2 = $this->load->view('orders/serum_result_options', $data, true);
		$html2 = trim($html2);
		$dompdf->loadHtml($html2);
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$pdfs = $dompdf->output();
		$file2 = FCPATH . SERUM_REQUEST_PDF_PATH ."serum_treatment_". $order_number .".pdf";
		file_put_contents($file2, $pdfs);
		return $file2;
	}

	function treatment($id=''){
		$this->_data['data'] = [];
		$data = $this->OrdersModel->allData($id);
		$order_number = $data['order_number'];
		$product_type = $data['product_code_selection'];
		$species_name = $data['species_name'];
		$this->_data['serumData'] = $this->OrdersModel->getSerumTestRecord($id);
		$this->_data['serumType'] = $this->OrdersModel->getSerumTestType($order_number);
		$this->_data['order_details'] = $data;
		$this->_data['result_link'] = SERUM_REQUEST_PDF_PATH ."serum_test_result_". $order_number .".pdf";
		$this->_data['food_result_link'] = SERUM_REQUEST_PDF_PATH . "serum_test_result_food_". $order_number .".pdf";
		$this->_data['id'] = $id;

		if (!empty($this->input->post())) {
			$orderData['id'] = $id;
			if($this->user_role == '5' && $this->session->userdata('user_type') == '1' && $this->_data['order_details']['is_order_completed'] == 1){
				$orderData['internal_practice_comment'] = $this->input->post('internal_practice_comment');
				$orderData['vet_interpretation'] = $this->input->post('vet_interpretation');
			}else{
				$orderData['internal_comment'] = $this->input->post('internal_comment');
				$orderData['interpretation'] = $this->input->post('interpretation');
				$orderData['annotated_by'] = $this->input->post('annotated_by');
			}
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);
			redirect('orders/treatment/'.$id);
			/* $result = $this->OrdersModel->serumOrderforImmuno($id);
			if ($result) {
				//$this->session->set_flashdata('success', 'Immunotherpathy Order has been placed successfully.');
				$newData = $this->OrdersModel->allData($result);
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
				if($postData['treatment'] == "3"){
					redirect('orders/allergens/'. $newData['id']);
				}else{
					$orderData['id'] = $newData['id'];
					if($postData['treatment'] == "2"){
					$allergens = $postData['allergens2'];
					}else{
					$allergens = $postData['allergens1'];
					}
					if(!empty($allergens)){
						$allergensArr = [];
						foreach($allergens as $ar){
							$allens = $this->OrdersModel->getAllergensData($ar);
							$allergensArr[] = $allens->id;
						}
						if(!empty($allergensArr)){
							$orderData['allergens'] = json_encode($allergensArr);
						}else{
							$orderData['allergens'] = '[]';
						}
					}else{
						$orderData['allergens'] = '[]';
					}
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
					redirect('orders/immmuno_summary/'. $newData['id']);
				}
			}else{
				$this->session->set_flashdata('error', 'Sorry! Immunotherpathy Order have an error.');
			} */
		}
		$this->load->view("orders/treatment", $this->_data);
	}

	public function previewSerumResult(){
		$post = $this->input->post();
		$id = $post['order_id'];
		$html = "";
		if($id > 0){
			$this->_data['data'] = [];
			$data = $this->OrdersModel->allData($id);
			$order_number = $data['order_number'];
			$product_type = $data['product_code_selection'];
			$species_name = $data['species_name'];
			$this->_data['serumData'] = $this->OrdersModel->getSerumTestRecord($id);
			$this->_data['serumType'] = $this->OrdersModel->getSerumTestType($order_number);
			$this->_data['order_details'] = $data;

			$environment = array(5,10,12,18,24);
			$environmentNfood = array(7,9,13,21,23,27,29);
			$food = array(6,8,11,19,20);
			$proteins = array(25,26);
			if(in_array($product_type,$environment)){
				if($species_name == 'Horse'){
					$html .= $this->load->view('orders/serum_result_horse_pdf', $this->_data, true);
				}else{
					$html .= $this->load->view('orders/serum_result_pdf', $this->_data, true);
				}
			}elseif(in_array($product_type,$food)){
				$html .= $this->load->view('orders/serum_food_result_pdf', $this->_data, true);
			}elseif(in_array($product_type,$proteins)){
				if($species_name == 'Horse'){
					$html .= $this->load->view('orders/serum_result_horse_pdf', $this->_data, true);
				}else{
					$html .= $this->load->view('orders/serum_result_pdf', $this->_data, true);
				}
			}else{
				if($species_name == 'Horse'){
					$html .= $this->load->view('orders/serum_result_horse_pdf', $this->_data, true);
				}else{
					$html .= $this->load->view('orders/serum_result_pdf', $this->_data, true);
				}
				$html .= '<br/>';
				$html .= $this->load->view('orders/serum_food_result_pdf', $this->_data, true);
			}
			$html .= '<br/>';
			$html .= $this->load->view('orders/serum_result_options', $this->_data, true);
		}
		echo $html;
		exit;
	}

	function sendBloodReceivedNotification($orderId){
		if($orderId > 0){
			$data = $this->OrdersModel->allData($orderId, "");
			$emailBody = '<!DOCTYPE html>
			<html>
				<head>
					<title>Blood Received email to veterinarian.</title>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
					<style type="text/css">
					body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
					table,td{mso-table-lspace:0;mso-table-rspace:0}
					img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
					table{border-collapse:collapse!important}
					body{height:100%!important;margin:0!important;padding:0!important;width:100%!important}
					a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important; font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
					@media screen and (max-width: 480px) {
					.mobile-hide{display:none!important}
					.mobile-center{text-align:center!important}
					}
					div[style*="margin: 16px 0;"]{margin:0!important}
					.align_class{padding-left:2.7em}
					</style>
				</head>
				<body style="margin: 0 !important; padding: 0 !important; background-color: #fff;" bgcolor="#fff">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
															<img class="logo-img" src="'. base_url("/assets/images/Nextmune_H-Logo_White.png") .'" alt="NextVu" style="height: 41px;max-width:180px;width:auto;">
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td align="center" style="padding: 0px 35px 10px 35px; background-color: #ffffff;" bgcolor="#ffffff">
											<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 20px;">
														<h2 style="font-size:24px; font-weight: 800; line-height:28px; color: #333333; margin: 0;"> PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' received </h2>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															The serum sample for your patient '.$data['pet_name'].' '.$data['po_last'].' has been received at Nextmune’s testing facility. You can expect your results in 10-14 business days. You can view the live status of your sample in our online portal NextView.
														</p>
													</td>
												</tr>
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;text-align: center;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															<a href="'. base_url() .'" style="background-color: #3c8dbc;border-color: #367fa9;border-radius: 0px;box-shadow: none;color: #fff;display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;text-decoration: none;">Open Nextview</a>
														</p>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 18px; font-weight: bold; line-height: 20px; color: #777777;">Our allergy portal NextView</p>
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">When placing an allergy test order with Nextmune we automatically create an account for you in NextView.</p>
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															NextView allows you to place orders for allergy tests and immunotherapy. You can also easily see where in the analysis/production process your order is. The system also allows you full visibility of your order history, gives immunotherapy refill reminders and more.
														</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tbody><tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 36px;font-weight: 800;line-height: 48px;color: #fff;text-align: center;" class="mobile-center">
															Thank you,<br>
															Nextmune.
														</td>
													</tr>
												</tbody></table>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>';
			$practice_email = $data['practice_email'];
			$to_email = $data['email'];
			//$to_email = 'stewart@webbagency.co.uk';
			$from_email = "vetorders.uk@nextmune.com";
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->from($from_email, "Nextmune");
			$this->email->to($to_email);
			/* $this->email->set_header('Content-Type', 'application/pdf');
			$this->email->set_header('Content-Disposition', 'attachment');
			$this->email->set_header('Content-Transfer-Encoding', 'base64'); */
			$this->email->subject('PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' received.');
			$this->email->message($emailBody);
			$this->email->set_mailtype("html");
			$is_send = $this->email->send();
			if ($is_send) {
				$this->session->set_flashdata("success", "Email sent successfully.");
			} else {
				$this->session->set_flashdata("error", $this->email->print_debugger());
			}
		}
	}

	function sendBloodanalyzedNotification($orderId){
		if($orderId > 0){
			$data = $this->OrdersModel->allData($orderId, "");
			$emailBody = '<!DOCTYPE html>
			<html>
				<head>
					<title>Blood analyzed and to be interpreted email to veterinarian TBD.</title>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
					<style type="text/css">
					body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
					table,td{mso-table-lspace:0;mso-table-rspace:0}
					img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
					table{border-collapse:collapse!important}
					body{height:100%!important;margin:0!important;padding:0!important;width:100%!important}
					a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important; font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
					@media screen and (max-width: 480px) {
					.mobile-hide{display:none!important}
					.mobile-center{text-align:center!important}
					}
					div[style*="margin: 16px 0;"]{margin:0!important}
					.align_class{padding-left:2.7em}
					</style>
				</head>
				<body style="margin: 0 !important; padding: 0 !important; background-color: #fff;" bgcolor="#fff">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
															<img class="logo-img" src="'. base_url("/assets/images/Nextmune_H-Logo_White.png") .'" alt="NextVu" style="height: 41px;max-width:180px;width:auto;">
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td align="center" style="padding: 0px 35px 10px 35px; background-color: #ffffff;" bgcolor="#ffffff">
											<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 20px;">
														<h2 style="font-size:24px; font-weight: 800; line-height:28px; color: #333333; margin: 0;"> PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' analyzed </h2>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															The serum sample for your patient '.$data['pet_name'].' '.$data['po_last'].' has been analysed at Nextmune’s testing facility and will be interpreted shortly. You can expect your results in 10-14 business days. You can view the live status of your sample in our online portal NextView.
														</p>
													</td>
												</tr>
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;text-align: center;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															<a href="'. base_url() .'" style="background-color: #3c8dbc;border-color: #367fa9;border-radius: 0px;box-shadow: none;color: #fff;display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;text-decoration: none;">Open Nextview</a>
														</p>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 18px; font-weight: bold; line-height: 20px; color: #777777;">Our allergy portal NextView</p>
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">When placing an allergy test order with Nextmune we automatically create an account for you in NextView.</p>
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															NextView allows you to place orders for allergy tests and immunotherapy. You can also easily see where in the analysis/production process your order is. The system also allows you full visibility of your order history, gives immunotherapy refill reminders and more.
														</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tbody><tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 36px;font-weight: 800;line-height: 48px;color: #fff;text-align: center;" class="mobile-center">
															Thank you,<br>
															Nextmune.
														</td>
													</tr>
												</tbody></table>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>';
			$practice_email = $data['practice_email'];
			$to_email = $data['email'];
			$from_email = "vetorders.uk@nextmune.com";
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->from($from_email, "Nextmune");
			$this->email->to($to_email);
			/* $this->email->set_header('Content-Type', 'application/pdf');
			$this->email->set_header('Content-Disposition', 'attachment');
			$this->email->set_header('Content-Transfer-Encoding', 'base64'); */
			$this->email->subject('PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' received.');
			$this->email->message($emailBody);
			$this->email->set_mailtype("html");
			$is_send = $this->email->send();
			if ($is_send) {
				$this->session->set_flashdata("success", "Email sent successfully.");
			} else {
				$this->session->set_flashdata("error", $this->email->print_debugger());
			}
		}
	}

	function getMeasurementId($barcode){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.allergy-explorer.com/measurements?filterBarcode='.$barcode.'',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'API-Key: 46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw'
			),
		));
		$responsem = curl_exec($curl);
		curl_close($curl);
		$resultM = json_decode($responsem,true);
		if(!empty($resultM) && !empty($resultM['measurements'])){
			return $resultM['measurements'][0]['id'];
		}else{
			return '0';
		}
	}

	function getRaptorResult(){
		$responce = array();
		$msg_data = $this->input->post();
		$id = $msg_data['order_id_raptor_modal'];
		$barcode = $msg_data['measurement_id'];
		$measurementId = $this->getMeasurementId($barcode);
		if($measurementId != '0'){
			$measurement_id = $measurementId;
		}else{
			$measurement_id = $barcode;
		}
		if(!empty($measurement_id)){
			$data = $this->OrdersModel->getRecord($id);
			$order_details = $this->OrdersModel->allData($data['id'], "");
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.raptor-server.com/measurement/'.$measurement_id.'',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'API-Key: 46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			//$file = FCPATH . "uploaded_files/raptor_result_file/raptor_result_". $data['order_number'] .".txt";
			//file_put_contents($file, $response);
			$resultL = json_decode($response,true);
			if(!empty($resultL) && !empty($resultL['id'])){
				$mid = $resultL['id'];
				$barcode			= !empty($resultL['barcode'])?$resultL['barcode']:'';
				$sampling_date		= !empty($resultL['sampling_date'])?$resultL['sampling_date']:'';
				$assay_date			= !empty($resultL['assay_date'])?$resultL['assay_date']:'';
				$processed_date		= !empty($resultL['processed_date'])?$resultL['processed_date']:'';
				$analyzed_date		= !empty($resultL['analyzed_date'])?$resultL['analyzed_date']:'';
				$approved_date		= !empty($resultL['approved_date'])?$resultL['approved_date']:'';
				$sample_code		= !empty($resultL['sample_code'])?$resultL['sample_code']:'';
				$patient_birthdate	= !empty($resultL['patient_birthdate'])?$resultL['patient_birthdate']:'';
				$patient_code		= !empty($resultL['patient_code'])?$resultL['patient_code']:'';
				$patient_name		= !empty($resultL['patient_name'])?$resultL['patient_name']:'';
				$sample_doctorinfo	= !empty($resultL['sample_doctorinfo'])?$resultL['sample_doctorinfo']:'';
				$sample_info		= !empty($resultL['sample_info'])?$resultL['sample_info']:'';
				$requisition_code	= !empty($resultL['requisition_code'])?$resultL['requisition_code']:'';
				$mod				= !empty($resultL['mod'])?$resultL['mod']:'';
				$qc					= !empty($resultL['qc'])?$resultL['qc']:'';
				$resultData['nextvu_id']		= $data['order_number'];
				$resultData['measurement_id']	= $mid;
				$resultData['barcode']			= $barcode;
				$resultData['sampling_date']	= $sampling_date;
				$resultData['assay_date']		= $assay_date;
				$resultData['processed_date']	= $processed_date;
				$resultData['analyzed_date'] 	= $analyzed_date;
				$resultData['approved_date'] 	= $approved_date;
				$resultData['sample_code'] 		= $sample_code;
				$resultData['patient_birthdate']= $patient_birthdate;
				$resultData['patient_code'] 	= $patient_code;
				$resultData['patient_name'] 	= $patient_name;
				$resultData['sample_doctorinfo']= $sample_doctorinfo;
				$resultData['sample_info']		= $sample_info;
				$resultData['requisition_code']	= $requisition_code;
				$resultData['result_mod'] 		= $result_mod;
				$resultData['qc']				= $qc;

				$this->db->select('result_id');
				$this->db->from('ci_raptor_serum_result');
				$this->db->where('nextvu_id', $data['order_number']);
				$this->db->where('measurement_id', $mid);
				$res1 = $this->db->get();
				if($res1->num_rows() == 0){
					$allergensArr			= !empty($resultL['allergens'])?$resultL['allergens']:array();
					$insrtResult = $this->db->insert('ci_raptor_serum_result',$resultData);
					if(!empty($insrtResult) && !empty($allergensArr)){
						$resultID = $this->db->insert_id();
						foreach($allergensArr as $rowt){
							$typeData['result_id'] = $resultID;
							$typeData['name'] = $rowt['name'];
							$typeData['code'] = '';
							$typeData['result_value']	= $rowt['value'];
							$typeData['value_intensity']= $rowt['value_intensity'];
							$this->db->insert('ci_raptor_result_allergens',$typeData);
						}
						$this->session->set_flashdata('sucess', 'Result data save successfully.');
					}else{
						$this->session->set_flashdata('error', 'Result data are empty.');
					}
				}else{
					$allergensArr	= !empty($resultL['allergens'])?$resultL['allergens']:array();
					$resultID = $res1->row()->result_id;
					$this->db->where('result_id', $resultID);
					$update = $this->db->update('ci_raptor_serum_result', $resultData);
					if(!empty($update) && !empty($allergensArr)){
						foreach($allergensArr as $rowt){
							$this->db->select('id');
							$this->db->from('ci_raptor_result_allergens');
							$this->db->where('result_id', $resultID);
							$this->db->where('name LIKE', $rowt['name']);
							$res2 = $this->db->get();
							if($res2->num_rows() == 0){
								$typeData['result_id'] = $resultID;
								$typeData['name'] = $rowt['name'];
								$typeData['code'] = '';
								$typeData['result_value']	= $rowt['value'];
								$typeData['value_intensity']= $rowt['value_intensity'];
								$this->db->insert('ci_raptor_result_allergens',$typeData);
							}else{
								$typeID = $res2->row()->type_id;
								$typeData['result_value']	= $rowt['value'];
								$typeData['value_intensity']= $rowt['value_intensity'];
								$this->db->where('id', $typeID);
								$this->db->update('ci_raptor_result_allergens', $typeData);
							}
						}
					}
				}
				$orderData['id'] = $id;
				$orderData['is_raptor_result'] = 1;
				$orderData['measurement_id'] = $measurement_id;
				$this->OrdersModel->add_edit($orderData);
				$this->session->set_flashdata("success", "Raptor Result data added successfully.");
				$responce['sucess'] = "Result data added successfully.";
			}else{
				//$responce['error'] = $resultL['errors']['id'][0];
				$responce['error'] = 'The Measurement Id which you entered '.$barcode.' is not valid';
			}
		}else{
			$responce['error'] = "You have not enter Measurement ID";
		}
		echo json_encode($responce); exit();
	}

	public function previewRaptorResult(){
		$post = $this->input->post();
		$id = $post['order_id'];
		$html = "";
		if($id > 0){
			$this->_data['data'] = [];
			$data = $this->OrdersModel->allData($id);
			$order_number = $data['order_number'];
			$product_type = $data['product_code_selection'];
			$species_name = $data['species_name'];
			$this->_data['order_details'] = $data;

			$html .= $this->load->view('orders/raptor_serum_result_pdf', $this->_data, true);
		}
		echo $html;
		exit;
	}

	function immmuno_summary($id = ''){
		$this->_data['data'] = [];
		$data = $this->OrdersModel->getRecord($id);
		$order_details = $this->OrdersModel->allData($data['id'], "");

		/*****delivery address details */
		$this->_data['delivery_address_details'] = '';
		if ($order_details['order_can_send_to'] == '1') {
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
			// Different Address
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
		/***** delivery address details*/

		/***** Practice or Lab Name */
		if ($order_details['lab_id'] > 0) {
			$final_name = $order_details['lab_name'];
		} elseif ($order_details['vet_user_id'] > 0) {
			$final_name = $order_details['practice_name'];
		} else {
			$final_name = '';
		}
		$this->_data['final_name'] = $final_name;
		/***** Practice or Lab Name */

		$allergens = $this->AllergensModel->order_allergens($order_details['allergens']);
		$this->_data['order_details'] = $order_details;
		$this->_data['allergens'] = $allergens;
		$this->_data['total_allergens'] = ($order_details['allergens'] != '') ? count(json_decode($order_details['allergens'])) : 0;
		$this->_data['id'] = $id;
		$this->_data['controller'] = $this->router->fetch_class();
		$this->_data['order_type'] = $order_details['order_type'];
		$this->_data['sub_order_type'] = $order_details['sub_order_type'];
		$this->_data['final_price'] = '0.00';
		$this->_data['order_discount'] = '0.00';
		$this->_data['deliveryPractices'] = $this->UsersModel->getDeliveryPractices("2");

		//Pricing
		$selected_allergen = json_decode($order_details['allergens']);
		$total_allergen = ($order_details['allergens'] != '') ? count(json_decode($order_details['allergens'])) : 0;
		if ($data['lab_id'] != 0) {
			$practice_lab = $data['lab_id'];
		} else {
			$practice_lab = $data['vet_user_id'];
		}

		if ($total_allergen > 0) {
			//Skin Test Pricing
			if ($data['order_type'] == '3') {
				$single_order_discount = 0.00;
				$insects_order_discount = 0.00;
				$selected_allergen_ids = implode(",", $selected_allergen);
				$insects_allergen = $this->AllergensModel->insect_allergen($selected_allergen_ids);
				$skin_test_price = $this->PriceCategoriesModel->skin_test_price($practice_lab);
				$single_price = $skin_test_price[0]['uk_price'];
				$single_insect_price = $skin_test_price[1]['uk_price'];
				$single_allergen = $total_allergen - $insects_allergen;

				/**single allergen discount **/
				$single_discount = $this->PriceCategoriesModel->get_discount("14", $practice_lab);
				if (!empty($single_discount)) {
					$single_order_discount = ($skin_test_price[0]['uk_price'] * $single_discount['uk_discount']) / 100;
					$single_order_discount = sprintf("%.2f", $single_order_discount);
				}
				/**single allergen discount **/

				/**insects allergen discount **/
				if ($insects_allergen > 0) {
					$insects_discount = $this->PriceCategoriesModel->get_discount("15", $practice_lab);
					if (!empty($insects_discount)) {
						$insects_order_discount = ($skin_test_price[1]['uk_price'] * $insects_discount['uk_discount']) / 100;
						$insects_order_discount = sprintf("%.2f", $insects_order_discount);
					}
				}
				/**insects allergen discount **/

				$final_price = ($single_price * $single_allergen) + ($single_insect_price * $insects_allergen);
				$this->_data['final_price'] = $final_price - ($single_order_discount + $insects_order_discount);
				$this->_data['order_discount'] = $single_order_discount + $insects_order_discount;
			}

			//Serum Test Pricing 
			if ($data['order_type'] == '2') {
				$order_discount = 0.00;
				$product_code_id = $this->session->userdata('product_code_selection');
				$serum_test_price = $this->PriceCategoriesModel->serum_test_price($product_code_id, $practice_lab);
				$final_price = $total_allergen * ($serum_test_price[0]['uk_price']);

				/**discount **/
				$serum_discount = $this->PriceCategoriesModel->get_discount($data['product_code_selection'], $practice_lab);
				//print_r($serum_discount);
				if (!empty($serum_discount)) {
					$order_discount = ($serum_test_price[0]['uk_price'] * $serum_discount['uk_discount']) / 100;
					$order_discount = sprintf("%.2f", $order_discount);
				}
				/**discount **/

				$this->_data['final_price'] = $final_price - $order_discount;
				$this->_data['order_discount'] = $order_discount;
			}

			//Immunotherapy Artuvetrin Test Pricing
			if ($data['order_type'] == '1' && $data['sub_order_type'] == '1') {
				$artuvetrin_test_price = $this->PriceCategoriesModel->artuvetrin_test_price($practice_lab);

				//Artuvetrin Therapy 1 – 4 allergens
				if ($total_allergen <= 4) {
					$order_discount = 0.00;
					/**discount **/
					$artuvetrin_discount = $this->PriceCategoriesModel->get_discount("16", $practice_lab);
					if (!empty($artuvetrin_discount)) {
						$order_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/

					$this->_data['final_price'] = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
					$this->_data['order_discount'] = round($order_discount, 2);

					//Artuvetrin Therapy 5 – 8 allergens
				} elseif ($total_allergen > 4 && $total_allergen <= 8) {
					$order_discount = 0.00;
					/**discount **/
					$artuvetrin_discount = $this->PriceCategoriesModel->get_discount("17", $practice_lab);
					if (!empty($artuvetrin_discount)) {
						$order_discount = ($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/

					$this->_data['final_price'] = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
					$this->_data['order_discount'] = round($order_discount, 2);

					//Artuvetrin Therapy more than 8 allergens

				} elseif ($total_allergen > 8) {
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
				}
			} //if

			//Sublingual Immunotherapy (SLIT) Pricing
			if ($data['order_type'] == '1' && $data['sub_order_type'] == '2') {
				//Sublingual Single Price
				$selected_allergen_ids = implode(",", $selected_allergen);
				$culicoides_allergen = $this->AllergensModel->culicoides_allergen($selected_allergen_ids);
				$slit_test_price = $this->PriceCategoriesModel->slit_test_price($practice_lab);
				$single_price = $slit_test_price[0]['uk_price'];
				$double_price = $slit_test_price[1]['uk_price'];
				$single_with_culicoides = $slit_test_price[2]['uk_price'];
				$double_with_culicoides = $slit_test_price[3]['uk_price'];
				$single_allergen = $total_allergen - $culicoides_allergen;
				$order_discount = 0.00;
				if ($data['single_double_selection'] == '1' && $culicoides_allergen == 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("18", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[0]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}
					/**discount **/
					$final_price = $total_allergen * $single_price;
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '2' && $culicoides_allergen == 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("19", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[1]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/** discount **/
					$final_price = $total_allergen * $double_price;
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '1' && $culicoides_allergen > 0) {
					/** discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("20", $practice_lab);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[2]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/** discount **/
					$final_price = ($single_price * $single_allergen) + ($single_with_culicoides * $culicoides_allergen);
					$final_price = $final_price - $order_discount;
				} else if ($data['single_double_selection'] == '2' && $culicoides_allergen > 0) {
					/**discount **/
					$slit_discount = $this->PriceCategoriesModel->get_discount("21", $practice_lab);
					//print_r($slit_discount);
					if (!empty($slit_discount)) {
						$order_discount = ($slit_test_price[3]['uk_price'] * $slit_discount['uk_discount']) / 100;
						$order_discount = sprintf("%.2f", $order_discount);
					}

					/**discount **/
					$final_price = ($double_price * $single_allergen) + ($double_with_culicoides * $culicoides_allergen);
					$final_price = $final_price - $order_discount;
				}
				$this->_data['final_price'] = $final_price;
				$this->_data['order_discount'] = $order_discount;
			} //if
		}

		if($data['lab_id'] == '13786'){
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

		$orderData = []; $serumData = [];
		if (!empty($this->input->post())) {
			if ($this->input->post('signaturesubmit') == 1) {
				$signature = $this->input->post('signature');
				$signatureFileName = time() . '.png';
				$signature = str_replace('data:image/png;base64,', '', $signature);
				$signature = str_replace(' ', '+', $signature);
				$data = base64_decode($signature);
				$file = FCPATH . SIGNATURE_PATH . $signatureFileName;
				file_put_contents($file, $data);

				$orderData['id'] = $id;
				$orderData['is_draft'] = 0;
				$orderData['signature'] = $signatureFileName;
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$orderhData['order_id'] = $id;
					$orderhData['text'] = 'New Order';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->addOrderHistory($orderhData);
					$this->session->set_flashdata('success', 'Order data has been saved successfully.');
					if (IS_LIVE == 'yes' && ($this->user_role == 1 || $this->user_role == 5) && $order_details['is_mail_sent'] == '0') {
						$this->send_mail($id);
					}
					redirect('orders');
				}
			}else {
				$orderData['id'] = $id;
				$orderData['unit_price'] = $this->input->post('unit_price');
				$orderData['order_discount'] = $this->input->post('order_discount');
				$orderData['shipping_cost'] = $this->input->post('shipping_cost');
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				if ($up_id = $this->OrdersModel->add_edit($orderData) > 0) {
					$orderhData['order_id'] = $id;
					$orderhData['text'] = 'New Order';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->addOrderHistory($orderhData);
					$this->session->set_flashdata('success', 'Order data has been saved successfully.');
					if (IS_LIVE == 'yes' && $this->user_role == 1 && $order_details['is_mail_sent'] == '0') {
						$this->send_mail($id);
					}
					redirect('orders');
				}
			}
		}

		if (!empty($data)) {
			$this->_data['data'] = $data;
		}
		$this->load->view("orders/immmuno_summary", $this->_data);
	}

	function interpretation($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$this->_data['order_details'] = $this->OrdersModel->allData($data['id'], "");
		$this->_data['raptorData'] = $this->OrdersModel->getRaptorData($data['order_number']);
		$this->_data['id'] = $id;
		$orderData = [];
		if (!empty($this->input->post())) {
			$orderData['id'] = $id;
			if($this->user_role == '5' && $this->session->userdata('user_type') == '1' && $this->_data['order_details']['is_order_completed'] == 1){
				$orderData['internal_practice_comment'] = $this->input->post('internal_practice_comment');
				$orderData['vet_interpretation'] = $this->input->post('vet_interpretation');
			}else{
				$orderData['internal_comment'] = $this->input->post('internal_comment');
				$orderData['interpretation'] = $this->input->post('interpretation');
				$orderData['annotated_by'] = $this->input->post('annotated_by');
			}
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			$this->OrdersModel->add_edit($orderData);
			redirect('orders/interpretation/'.$id);
		}
		if (is_numeric($id) > 0) {
			if (!empty($data)) {
				$this->_data['data'] = $data;
			}
		}
		
		if($this->user_role == '5' && $this->session->userdata('user_type') == '1'){
			if($this->_data['order_details']['is_order_completed'] == 1){
				$this->load->view("orders/vet_dashboard", $this->_data);
			}else{
				redirect('orders');
			}
		}else{
			$this->load->view("orders/result_interpretation", $this->_data);
		}
	}

	function sendPaxResultNotification($orderId){
		ini_set('memory_limit', '256M');
		if($orderId > 0){
			$data = $this->OrdersModel->allData($orderId, "");
			$order_details = $data;
			$this->_data['order_details'] = $data;
			$raptorData = $this->OrdersModel->getRaptorData($data['order_number']);
			$this->_data['raptorData'] = $raptorData;

			if($data['serum_type'] == 1){
				$this->_data['serumTypes'] = 'PAX';
			}else{
				$this->_data['serumTypes'] = 'NextLab';
			}
			$emailBody = $this->load->view('orders/send_serum_mail_template', $this->_data, true);
			$emailBody = trim($emailBody);

			$file_name = FCPATH . SERUM_REQUEST_PDF_PATH . "serum_result_". $data['order_number'] .".pdf";

			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			if(preg_match('/\bScreening\b/', $respnedn->name)){
				ob_end_flush();
				require_once(FCPATH.'vendor_pdf/autoload.php');
				$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4',]);
				$raptor_footer_pdf = $this->load->view('orders/pax_pdf/raptor_footer', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('PAX Serum Request Notification');
				$mpdf->SetHTMLFooter($raptor_footer_pdf );
				$mpdf->AddPage('','', '', '', '', 0, 0, 0, 5, 0, 5);
				$screening_header = $this->load->view('orders/raptor_screening_pdf', $this->_data, true);
				$mpdf->SetHTMLFooter($raptor_footer_pdf);
				$mpdf->SetHTMLFooter($raptor_footer_pdf );
				$mpdf->WriteHTML($screening_header);
				$mpdf->Output($file_name,'F');
			}else{
				$this->_data['fulladdress'] = '';
				if($data['vet_user_id']>0){
					$refDatas = $this->UsersDetailsModel->getColumnAllArray($data['vet_user_id']);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
					$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
					$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
					$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
					$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
					$this->_data['fulladdress'] = $add_1.$add_2.$add_3.$city.$postcode;
				}
				$this->_data['serumdata'] = $this->OrdersModel->getSerumTestRecord($orderId);
				$this->_data['respnedn'] = $respnedn;
				$this->_data['ordeType'] = $respnedn->name;
				$this->_data['ordeTypeID'] = $respnedn->id;

				$getAllergenParent = $this->AllergensModel->getAllergenParentbyName($data['allergens']);
				$totalGroup0 = count($getAllergenParent);
				$totalGroup2 = $totalGroup0/2;
				$partA = ((round)($totalGroup2));
				$partB = $partA-1;
				$this->_data['getAllergenParent'] = $getAllergenParent;
				$this->_data['partB'] = $partB;

				$block1 = [];
				$subAllergnArr = $this->AllergensModel->getAllergensByID($data['allergens']);
				if(!empty($subAllergnArr)){
					foreach ($subAllergnArr as $svalue){
						$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
						if(!empty($subVlu->raptor_code)){
							$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
							if(!empty($raptrVlu)){
								if($raptrVlu->result_value >= 30){
									if($svalue['name'] != "N/A"){
										$block1[$svalue['id']] = $svalue['name'];
									}
								}
							}
						}
					}
				}
				if($data['treatment_1'] != "" && $data['treatment_1'] != "[]"){
					$subAllergnArr = $this->AllergensModel->getAllergensByID($data['treatment_1']);
					if(!empty($subAllergnArr)){
						foreach ($subAllergnArr as $svalue){
							$block1[$svalue['id']] = $svalue['name'];
						}
					}
				}
				asort($block1);
				$this->_data['block1'] = $block1;
				
				$block2 = [];
				foreach($getAllergenParent as $apvalue){
					$getGroupMixtures = $this->AllergensModel->getGroupMixturesbyParent($apvalue['parent_id']);
					if(!empty($getGroupMixtures)){
						$parentIdArr = [];
						foreach($getGroupMixtures as $mvalue){
							if($mvalue['mixture_allergens'] != "" && $mvalue['mixture_allergens'] != "null"){
								$parentIdArr[] = $mvalue['id'];
							}
						}
						if(!empty($parentIdArr)){
							if(count($parentIdArr) > 1){
								$emptyArr = [];
								foreach($parentIdArr as $makey=>$mavalue){
									$allergenArr = json_decode($getGroupMixtures[$makey]['mixture_allergens']);
									$testingArr = [];
									foreach($allergenArr as $amid){
										$rmcodes = $this->OrdersModel->getsubAllergensCode($amid);
										if(!empty($rmcodes->raptor_code)){
											$raptrmVlu = $this->OrdersModel->getRaptorValue($rmcodes->raptor_code,$raptorData->result_id);
											if(!empty($raptrmVlu)){
												if($raptrmVlu->result_value >= 30){
													$testingArr[$mavalue] += 1;
												}
											}
										}
									}

									if($testingArr[$mavalue] >= 2){
										if($getGroupMixtures[$makey]['name'] != "N/A"){
											$block2[$getGroupMixtures[$makey]['id']] = $getGroupMixtures[$makey]['name'];
										}
										foreach(json_decode($getGroupMixtures[$makey]['mixture_allergens']) as $emtrow){
											$emptyArr[$apvalue['parent_id']][] = $emtrow;
										}
									}
								}

								if(!empty($emptyArr[$apvalue['parent_id']])){
									$sub1Allergens = $this->AllergensModel->get_subAllergens_dropdown_empty($apvalue['parent_id'],$data['allergens'], $emptyArr[$apvalue['parent_id']]);
									foreach($sub1Allergens as $s1value){
										$sub1Vlu = $this->OrdersModel->getsubAllergensCode($s1value['id']);
										if(!empty($sub1Vlu->raptor_code)){
											$raptr1Vlu = $this->OrdersModel->getRaptorValue($sub1Vlu->raptor_code,$raptorData->result_id);
											if(!empty($raptr1Vlu)){
												if($raptr1Vlu->result_value >= 30){
													if($s1value['name'] != "N/A"){
														$block2[$s1value['id']] = $s1value['name'];
													}
												}
											}
										}
									}
								}else{
									$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
									foreach($sub2Allergens as $s2value){
										$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
										if(!empty($sub2Vlu->raptor_code)){
											$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
											if(!empty($raptr2Vlu)){
												if($raptr2Vlu->result_value >= 30){
													if($s2value['name'] != "N/A"){
													$block2[$s2value['id']] = $s2value['name'];
													}
												}
											}
										}
									}
								}
							}else{
								$allergensArr = json_decode($getGroupMixtures[0]['mixture_allergens']);
								$tested = 0;
								foreach($allergensArr as $aid){
									$rcodes = $this->OrdersModel->getsubAllergensCode($aid);
									if(!empty($rcodes->raptor_code)){
										$raptrVlu = $this->OrdersModel->getRaptorValue($rcodes->raptor_code,$raptorData->result_id);
										if(!empty($raptrVlu)){
											if($raptrVlu->result_value >= 30){
												$tested++;
											}
										}
									}
								}

								if($tested >= 2){
									if($getGroupMixtures[0]['name'] != "N/A"){
									$block2[$getGroupMixtures[0]['id']] = $getGroupMixtures[0]['name'];
									}
									$sub1Allergens = $this->AllergensModel->get_subAllergens_dropdown2($getGroupMixtures[0]['parent_id'],$data['allergens'], $getGroupMixtures[0]['mixture_allergens']);
									foreach($sub1Allergens as $s1value){
										$sub1Vlu = $this->OrdersModel->getsubAllergensCode($s1value['id']);
										if(!empty($sub1Vlu->raptor_code)){
											$raptr1Vlu = $this->OrdersModel->getRaptorValue($sub1Vlu->raptor_code,$raptorData->result_id);
											if(!empty($raptr1Vlu)){
												if($raptr1Vlu->result_value >= 30){
													if($s1value['name'] != "N/A"){
													$block2[$s1value['id']] = $s1value['name'];
													}
												}
											}
										}
									}
								}else{
									$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
									foreach($sub2Allergens as $s2value){
										$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
										if(!empty($sub2Vlu->raptor_code)){
											$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
											if(!empty($raptr2Vlu)){
												if($raptr2Vlu->result_value >= 30){
													if($s2value['name'] != "N/A"){
													$block2[$s2value['id']] = $s2value['name'];
													}
												}
											}
										}
									}
								}
							}
						}else{
							$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $data['allergens']);
							foreach($sub2Allergens as $s2value){
								$sub2Vlu = $this->OrdersModel->getsubAllergensCode($s2value['id']);
								if(!empty($sub2Vlu->raptor_code)){
									$raptr2Vlu = $this->OrdersModel->getRaptorValue($sub2Vlu->raptor_code,$raptorData->result_id);
									if(!empty($raptr2Vlu)){
										if($raptr2Vlu->result_value >= 30){
											if($s2value['name'] != "N/A"){
											$block2[$s2value['id']] = $s2value['name'];
											}
										}
									}
								}
							}
						}
					}else{
						$sub3Allergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $data['allergens']);
						foreach($sub3Allergens as $s3value){
							$sub3Vlu = $this->OrdersModel->getsubAllergensCode($s3value['id']);
							if(!empty($sub3Vlu->raptor_code)){
								$raptr3Vlu = $this->OrdersModel->getRaptorValue($sub3Vlu->raptor_code,$raptorData->result_id);
								if(!empty($raptr3Vlu)){
									if($raptr3Vlu->result_value >= 30){
										if($s3value['name'] != "N/A"){
										$block2[$s3value['id']] = $s3value['name'];
										}
									}
								}
							}
						}
					}
				}
				if($data['treatment_2'] != "" && $data['treatment_2'] != "[]"){
					$subAllergnArr = $this->AllergensModel->getAllergensByID($data['treatment_2']);
					if(!empty($subAllergnArr)){
						foreach ($subAllergnArr as $svalue){
							if($svalue['name'] != "N/A"){
							$block2[$svalue['id']] = $svalue['name'];
							}
						}
					}
				}
				asort($block2);
				$this->_data['block2'] = $block2;

				$foodtotal = 0;
				foreach ($getAllergenParent as $apkey => $apvalue) {
					$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $data['allergens']);
					foreach ($subAllergens as $skey => $svalue) {
						$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
						if(!empty($subVlu->raptor_code)){
							$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
							if(!empty($raptrVlu)){
								if($raptrVlu->result_value >= 30){
									$foodtotal++;
								}
							}
						}
					}
				}
				$this->_data['foodtotal'] = $foodtotal;
				
				
				$allengesIDsArr = array(); $allengesArr = []; $dummytext = "";
				$subAllergnArr = $this->AllergensModel->getAllergensByID($data['allergens']);
				if(!empty($subAllergnArr)){
					$allengesArr = []; $allenges3Arr = []; $allenges4Arr = [];
					foreach ($subAllergnArr as $svalue){
						$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
						if(!empty($subVlu->raptor_code)){
							$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
							if(!empty($raptrVlu)){
								if($raptrVlu->result_value >= 30){
									if($svalue['name'] != "N/A"){
										$allenges3Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
									}else{
										$allenges4Arr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
									}
									$allengesArr[] = !empty($svalue['pax_name'])?$svalue['pax_name']:$svalue['name'];
									$allengesIDsArr[] = $svalue['id'];
								}
							}
						}
					}

					if(count($allengesArr) > 1){
						$lastchnk = end($allengesArr);
						$lastchnkName = ', '.$lastchnk;
						$lastchnkCng = ' and '.$lastchnk;
						$allengesStr = implode(", ",$allengesArr);
						$allengeStr = str_replace($lastchnkName,$lastchnkCng,$allengesStr);
					}else{
						$allengeStr = implode(", ",$allengesArr);
					}

					if(count($allenges3Arr) > 1){
						$last3chnk = end($allenges3Arr);
						$lastchnk3Name = ', '.$last3chnk;
						$lastchnk3Cng = ' and '.$last3chnk;
						$allenges3Str = implode(", ",$allenges3Arr);
						$allenge3Str = str_replace($lastchnk3Name,$lastchnk3Cng,$allenges3Str);
					}else{
						$allenge3Str = implode(", ",$allenges3Arr);
					}

					if(count($allenges4Arr) > 1){
						$last4chnk = end($allenges4Arr);
						$lastchnk4Name = ', '.$last4chnk;
						$lastchnk4Cng = ' and '.$last4chnk;
						$allenges4Str = implode(", ",$allenges4Arr);
						$allenge4Str = str_replace($lastchnk4Name,$lastchnk4Cng,$allenges4Str);
					}else{
						$allenge4Str = implode(", ",$allenges4Arr);
					}

					$dummytext = "This patient is sensitized to ". $allengeStr .".";
					$dummytext .= "<p style='padding:0px;margin:0px;'>&nbsp;</p>";
					$dummytext .= "If the corresponding clinical signs occur, allergen-specific immunotherapy is recommended for: ". $allenge3Str .".";
					$dummytext .= "<p style='padding:0px;margin:0px;'>&nbsp;</p>";
					$dummytext .= "Allergen-specific immunotherapy is currently not available for ". $allenge4Str ."; the treatment is symptomatic.";
					$dummytext .= "<p style='padding:0px;margin:0px;'>&nbsp;</p>";
					$dummytext .= "Please find full interpretation and detailed results per allergen extract and component in the following pages.";
					$dummytext .= "<p style='padding:0px;margin:0px;'>&nbsp;</p>";
					$dummytext .= "Based on these results we recommend the following immunotherapy composition(s):";
					$dummytext .= "<p style='padding:0px;margin:0px;'>&nbsp;</p>";
				}
				$this->_data['allengesIDsArr'] = $allengesIDsArr;
				$this->_data['allengesArr'] = $allengesArr;
				$this->_data['dummytext'] = $dummytext;

				ob_end_flush();
				require_once(FCPATH.'vendor_pdf/autoload.php');
				$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4',]);
				$pageno_footer_pdf = $this->load->view('pax_pdf/raptor_footer', $this->_data, true);
				$mpdf->use_kwt = true; 
				$mpdf->SetDisplayMode('fullpage');
				$mpdf->SetTitle('PAX Serum Request Notification');
				$mpdf->SetHTMLFooter($pageno_footer_pdf );
				$mpdf->AddPage('','', '', '', '', 0, 0, 0, 5, 0, 5);
				$main_header_pdf = $this->load->view('pax_pdf/main_header', $this->_data, true);
				$allergen_header_pdf = $this->load->view('pax_pdf/raptor_allergen_header_pdf', $this->_data, true);
				$html_header = $this->load->view('pax_pdf/raptor_header_pdf', $this->_data, true);
				$mpdf->WriteHTML($main_header_pdf);
				if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name)) && (!preg_match('/\bScreening\b/', $respnedn->name))){
					if(empty($block1)){
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->SetHTMLFooter($pageno_footer_pdf );
						$mpdf->AddPage('','','','','',0,0,22,22,5,5);
						$negative_faq_pdf = $this->load->view('pax_pdf/raptor_negative_faq', $this->_data, true);
						$mpdf->WriteHTML($negative_faq_pdf);
					}
					if(!empty($block1)){
						$summary_footer_pdf = $this->load->view('pax_pdf/raptor_summary_footer', $this->_data, true);
						$summary_recommendation_pdf = $this->load->view('pax_pdf/raptor_summary_recommendation', $this->_data, true);
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->AddPage('','','','','',0,0,20,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($summary_footer_pdf);
						$mpdf->WriteHTML($summary_recommendation_pdf);
					}
					$allergens_footer_pdf = $this->load->view('pax_pdf/raptor_allergens_footer_pdf', $this->_data, true);
					$result_panel_pdf = $this->load->view('pax_pdf/raptor_result_panel', $this->_data, true);
					$mpdf->SetHTMLHeader($allergen_header_pdf);
					$mpdf->AddPage('','','','','',0,0,35,30,5,5);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLFooter($allergens_footer_pdf);
					$mpdf->WriteHTML($result_panel_pdf);
					if(!empty($block1)){
						$interpretation_footer_pdf = $this->load->view('pax_pdf/raptor_footer_interpretation', $this->_data, true);
						$interpretation_support_pdf = $this->load->view('pax_pdf/raptor_interpretation_support', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($interpretation_footer_pdf);
						$mpdf->WriteHTML($interpretation_support_pdf);

						$positive_faq_pdf = $this->load->view('pax_pdf/raptor_positive_faq', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($pageno_footer_pdf);
						$mpdf->WriteHTML($positive_faq_pdf);
					}
				}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name)) && (!preg_match('/\bScreening\b/', $respnedn->name))){
					if($foodtotal == 0){
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->SetHTMLFooter($pageno_footer_pdf );
						$mpdf->AddPage('','','','','',0,0,22,22,5,5);
						$negative_faq_pdf = $this->load->view('pax_pdf/raptor_negative_faq', $this->_data, true);
						$mpdf->WriteHTML($negative_faq_pdf);
					}
					if($foodtotal > 0){
						$summary_footer_pdf = $this->load->view('pax_pdf/raptor_summary_footer', $this->_data, true);
						$summary_recommendation_pdf = $this->load->view('pax_pdf/raptor_summary_recommendation', $this->_data, true);
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->AddPage('','','','','',0,0,20,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($summary_footer_pdf);
						$mpdf->WriteHTML($summary_recommendation_pdf);
					}
					$allergens_footer_pdf = $this->load->view('pax_pdf/raptor_allergens_footer_pdf', $this->_data, true);
					$result_panel_pdf = $this->load->view('pax_pdf/raptor_result_panel', $this->_data, true);
					$mpdf->SetHTMLHeader($allergen_header_pdf);
					$mpdf->AddPage('','','','','',0,0,35,30,5,5);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLFooter($allergens_footer_pdf);
					$mpdf->WriteHTML($result_panel_pdf);
					if($foodtotal > 0){
						$interpretation_footer_pdf = $this->load->view('pax_pdf/raptor_footer_interpretation', $this->_data, true);
						$interpretation_support_pdf = $this->load->view('pax_pdf/raptor_interpretation_support', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($interpretation_footer_pdf);
						$mpdf->WriteHTML($interpretation_support_pdf);

						$diet_chart_pdf = $this->load->view('pax_pdf/raptor_diet_chart', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($pageno_footer_pdf);
						$mpdf->WriteHTML($diet_chart_pdf);
					}
				}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name)) && (!preg_match('/\bScreening\b/', $respnedn->name))){
					if(empty($block1)){
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->SetHTMLFooter($pageno_footer_pdf );
						$mpdf->AddPage('','','','','',0,0,22,22,5,5);
						$negative_faq_pdf = $this->load->view('pax_pdf/raptor_negative_faq', $this->_data, true);
						$mpdf->WriteHTML($negative_faq_pdf);
					}
					if(!empty($block1)){
						$summary_footer_pdf = $this->load->view('pax_pdf/raptor_summary_footer', $this->_data, true);
						$summary_recommendation_pdf = $this->load->view('pax_pdf/raptor_summary_recommendation', $this->_data, true);
						$mpdf->SetHTMLHeader($html_header);
						$mpdf->AddPage('','','','','',0,0,20,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($summary_footer_pdf);
						$mpdf->WriteHTML($summary_recommendation_pdf);
					}
					$allergens_footer_pdf = $this->load->view('pax_pdf/raptor_allergens_footer_pdf', $this->_data, true);
					$result_panel_pdf = $this->load->view('pax_pdf/raptor_result_panel', $this->_data, true);
					$mpdf->SetHTMLHeader($allergen_header_pdf);
					$mpdf->AddPage('','','','','',0,0,35,30,5,5);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->SetHTMLFooter($allergens_footer_pdf);
					$mpdf->WriteHTML($result_panel_pdf);
					if(!empty($block1)){
						$interpretation_footer_pdf = $this->load->view('pax_pdf/raptor_footer_interpretation', $this->_data, true);
						$interpretation_support_pdf = $this->load->view('pax_pdf/raptor_interpretation_support', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($interpretation_footer_pdf);
						$mpdf->WriteHTML($interpretation_support_pdf);

						$positive_faq_pdf = $this->load->view('pax_pdf/raptor_positive_faq', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($pageno_footer_pdf);
						$mpdf->WriteHTML($positive_faq_pdf);
					}
					if($foodtotal > 0){
						$diet_chart_pdf = $this->load->view('pax_pdf/raptor_diet_chart', $this->_data, true);
						$mpdf->SetHTMLHeader($allergen_header_pdf);
						$mpdf->AddPage('','','','','',0,0,35,35,5,5);
						$mpdf->SetDisplayMode('fullpage');
						$mpdf->SetHTMLFooter($pageno_footer_pdf);
						$mpdf->WriteHTML($diet_chart_pdf);
					}
				}
				$mpdf->Output($file_name,'F');
			}
			$atcfileName = SERUM_REQUEST_PDF_PATH . "serum_result_". $data['order_number'] .".pdf";
			$practice_email = $data['practice_email'];
			$to_email = $data['email'];
			//$to_email = "stewart_nextmune@yopmail.com";
			$from_email = "vetorders.uk@nextmune.com";
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->from($from_email, "Nextmune");
			$this->email->to($to_email);
			$this->email->subject('PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' received.');
			$this->email->message($emailBody);
			$this->email->set_mailtype("html");
			$this->email->attach($atcfileName);
			$is_send = $this->email->send();
			if ($is_send) {
				$orderData['id'] = $orderId;
				$orderData['is_serum_result_sent'] = 1;
				$orderData['is_authorised'] = 2;
				$orderData['is_confirmed'] = 4;
				$orderData['is_order_completed'] = 1;
				if(preg_match('/\bScreening\b/', $respnedn->name)){
				$orderData['is_expand'] = 1;
				}
				$this->OrdersModel->add_edit($orderData);

				$orderhData['text'] = 'Reported';
				$orderhData['order_id'] = $orderId;
				$orderhData['created_by'] = $this->user_id;;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderhData);

				$this->session->set_flashdata("success", "Email sent successfully.");
			} else {
				$this->session->set_flashdata("error", $this->email->print_debugger());
			}
			redirect('orders');
		}else{
			redirect('orders');
		}
	}

	function getSerumResultdoc($orderId){
		ini_set('memory_limit', '256M');
		if($orderId > 0){
			$dochtml = ''; $fileName = ''; $redirectlink = '';
			$data = $this->OrdersModel->allData($orderId, "");
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);

			$redirectlink = 'orders/treatment/';
			$order_number = $data['order_number'];
			$serumType = $this->OrdersModel->getSerumTestType($order_number);
			if(!empty($serumType)){
				$stypeIDArr = array(); $sresultIDArr = array(); 
				foreach($serumType as $stype){
					$stypeIDArr[] = $stype->type_id;
					$sresultIDArr[] = $stype->result_id;
				}
			}
			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			if(!empty($respnedn)){
				$dochtml .= '<p><b>Animal Name:</b> '.$data['pet_name'].'</p>';
				$dochtml .= '<p><b>Owner name:</b> '.$data['pet_owner_name'].' '.$data['po_last'].'</p>';
				$dochtml .= '<p><b>Species:</b> '.$data['species_name'].'</p>';
				$dochtml .= '<p><b>Synlab Lab Ref:</b> '.$data['reference_number'].'</p>';
				$dochtml .= '<p><b>Nextmune Ref:</b> '.$data['lab_order_number'].'</p>';
				$dochtml .= '<p></p>';
				if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
					$fileName = 'NextLab_SCREEN_Environmental+Complete_Food_Serum_result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					$dochtml .= '<p><b>Complete Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if(!empty($serumfResults)){
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Environmental_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */
				}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (!preg_match('/\bFood Panel\b/', $respnedn->name)) && (!preg_match('/\bFood SCREEN\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental_Panel_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */
				}elseif(preg_match('/\bComplete Food Panel\b/', $respnedn->name)){
					$fileName = 'NextLab_Complete_Food_Panel_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if(!empty($serumfResults)){
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood Panel\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */

					$dochtml .= '<p><b>Food Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
					foreach($getAllergenFParent as $rowf){
						$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
						foreach($subfAllergens as $sfvalue){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
							$this->db->order_by('id', 'ASC');
							$serumfResults = $this->db->get()->row();
							if($serumfResults->result >= 10){
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">POSITIVE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">BORDER LINE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}else{
								$dochtml .= '<tr>
									<td width="250">'.$serumfResults->name.'</td>
									<td width="50">'.$serumfResults->result.'</td>
									<td width="200">NEGATIVE</td>
								</tr>
								<tr><td colspan="3"></td></tr>';
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}
				}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
					$fileName = 'NextLab_Complete_Environmental+Food_SCREEN_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
					$fileName = 'NextLab_SCREEN_Environmental+Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					$dochtml .= '<p><b>SCREEN Food Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					$counterFPN = $counterFPB = $counterFPP = 0;
					foreach($proteinsAllergens as $fpvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fpvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fpvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fpResults = $this->db->get()->row();
						if(!empty($fpResults)){
							if($fpResults->result >= 10){
								$counterFPP++;
							}elseif($fpResults->result >= 5 && $fpResults->result < 10){
								$counterFPB++;
							}else{
								$counterFPN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Proteins</td>';
								if($counterFPP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFPB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					$counterFCN = $counterFCB = $counterFCP = 0;
					foreach($carbohyAllergens as $fcvalue){
						$this->db->select('result');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$fcvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$fcvalue['equ_allgy_food_igg'].'")');
						$this->db->order_by('result', 'DESC');
						$fcResults = $this->db->get()->row();
						if(!empty($fcResults)){
							if($fcResults->result >= 10){
								$counterFCP++;
							}elseif($fcResults->result >= 5 && $fcResults->result < 10){
								$counterFCB++;
							}else{
								$counterFCN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Food Carbohydrates</td>';
								if($counterFCP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterFCB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Food Carbohydrates */
				}elseif(preg_match('/\bComplete Environmental and Insect Panel\b/', $respnedn->name)){
					$fileName = 'NextLab_Complete_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					$getAllergenParent = $this->AllergensModel->getallergensENVcatgory($data['allergens']);
					foreach($getAllergenParent as $row1){
						$dochtml .= '<p><b>'.$row1['name'].'</b></p>';
						$dochtml .= '<p></p>';
						$dochtml .= '<table><tbody>';
						$sub2Allergens = $this->AllergensModel->get_subAllergens_dropdown($row1['parent_id'], $data['allergens']);
						foreach($sub2Allergens as $s2value){
							$this->db->select('*');
							$this->db->from('ci_serum_result_allergens');
							$this->db->where('result_id IN('.$sresultID.')');
							$this->db->where('type_id IN('.$stypeID.')');
							$this->db->where('(lims_allergens_id = "'.$s2value['can_allgy_env'].'" OR lims_allergens_id = "'.$s2value['fel_allgy_env'].'" OR lims_allergens_id = "'.$s2value['equ_allgy_env'].'")');
							$this->db->order_by('id', 'ASC');
							$serumResults = $this->db->get()->row();
							if(!empty($serumResults)){
								if($serumResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumResults->result >= 5 && $serumResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumResults->name.'</td>
										<td width="50">'.$serumResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
						}
						$dochtml .= '</tbody></table>';
						$dochtml .= '<p></p>';
					}

					/* Start Malassezia */
					$dochtml .= '<p></p>';
					$dochtml .= '<table>
						<tbody>
							<tr>';
								$dochtml .= '<td width="250"><b>Malassezia</b></td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('result', 'DESC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="50">'.$malasseziaResults->result.'</td>';
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="50">0</td>';
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					/* End Malassezia */

					if(preg_match('/\bFood\b/', $respnedn->name)){
						$dochtml .= '<p><b>Food Panel</b></p>';
						$dochtml .= '<p></p>';
						$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
						foreach($getAllergenFParent as $rowf){
							$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
							$dochtml .= '<p></p>';
							$dochtml .= '<table><tbody>';
							$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
							foreach($subfAllergens as $sfvalue){
								$this->db->select('*');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
								$this->db->order_by('id', 'ASC');
								$serumfResults = $this->db->get()->row();
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
							$dochtml .= '</tbody></table>';
							$dochtml .= '<p></p>';
						}
					}
				}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
					$fileName = 'NextLab_SCREEN_Environmental+Complete_Food_Serum_Result_'.$data['order_number'].'';
					$dochtml .= '<p><b>SCREEN Environmental Panel</b></p>';
					$dochtml .= '<p></p>';
					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					$countergN = $countergB = $countergP = 0;
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countergP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countergB++;
							}else{
								$countergN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Grasses</td>';
								if($countergP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countergB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					$counterwN = $counterwB = $counterwP = 0;
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counterwP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counterwB++;
							}else{
								$counterwN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Weeds</td>';
								if($counterwP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counterwB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					$countertN = $countertB = $countertP = 0;
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$countertP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$countertB++;
							}else{
								$countertN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Trees</td>';
								if($countertP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countertB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					$countercN = $countercB = $countercP = 0;
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResults = $this->db->get()->row();
						if(!empty($serumcResults)){
							if($serumcResults->result >= 10){
								$countercP++;
							}elseif($serumcResults->result >= 5 && $serumcResults->result < 10){
								$countercB++;
							}else{
								$countercN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Crops</td>';
								if($countercP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($countercB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					$counteriN = $counteriB = $counteriP = 0;
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= 10){
								$counteriP++;
							}elseif($serumResults->result >= 5 && $serumResults->result < 10){
								$counteriB++;
							}else{
								$counteriN++;
							}
						}
					}
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Indoor</td>';
								if($counteriP > 0){
									$dochtml .= '<td width="200">POSITIVE</td>';
								}elseif($counteriB > 0){
									$dochtml .= '<td width="200">BORDER LINE</td>';
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Indoor(Mites/Moulds/Epithelia) */
					
					/* Start Flea */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Flea</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1900" OR lims_allergens_id = "2243")');
								$this->db->order_by('id', 'ASC');
								$fleaResults = $this->db->get()->row();
								if(!empty($fleaResults)){
									if($fleaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($fleaResults->result >= 5 && $fleaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p>';
					/* End Flea */

					/* Start Malassezia */
					$dochtml .= '<table>
						<tbody>
							<tr>
								<td width="300">Malassezia</td>';
								$this->db->select('result');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "1904" OR lims_allergens_id = "2247")');
								$this->db->order_by('id', 'ASC');
								$malasseziaResults = $this->db->get()->row();
								if(!empty($malasseziaResults)){
									if($malasseziaResults->result >= 10){
										$dochtml .= '<td width="200">POSITIVE</td>';
									}elseif($malasseziaResults->result >= 5 && $malasseziaResults->result < 10){
										$dochtml .= '<td width="200">BORDER LINE</td>';
									}else{
										$dochtml .= '<td width="200">NEGATIVE</td>';
									}
								}else{
									$dochtml .= '<td width="200">NEGATIVE</td>';
								}
							$dochtml .= '</tr>
						</tbody>
					</table>';
					$dochtml .= '<p></p><p></p>';
					/* End Malassezia */

					if(preg_match('/\bFood\b/', $respnedn->name)){
						$dochtml .= '<p><b>Food Panel</b></p>';
						$dochtml .= '<p></p>';
						$getAllergenFParent = $this->AllergensModel->getallergensFoodcatgory($data['allergens']);
						foreach($getAllergenFParent as $rowf){
							$dochtml .= '<p><b>'.$rowf['name'].'</b></p>';
							$dochtml .= '<p></p>';
							$dochtml .= '<table><tbody>';
							$subfAllergens = $this->AllergensModel->get_subAllergensfood_dropdown($rowf['parent_id'], $data['allergens']);
							foreach($subfAllergens as $sfvalue){
								$this->db->select('*');
								$this->db->from('ci_serum_result_allergens');
								$this->db->where('result_id IN('.$sresultID.')');
								$this->db->where('type_id IN('.$stypeID.')');
								$this->db->where('(lims_allergens_id = "'.$sfvalue['can_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['can_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['fel_allgy_food_igg'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_ige'].'" OR lims_allergens_id = "'.$sfvalue['equ_allgy_food_igg'].'")');
								$this->db->order_by('id', 'ASC');
								$serumfResults = $this->db->get()->row();
								if($serumfResults->result >= 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">POSITIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}elseif($serumfResults->result >= 5 && $serumfResults->result < 10){
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">BORDER LINE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}else{
									$dochtml .= '<tr>
										<td width="250">'.$serumfResults->name.'</td>
										<td width="50">'.$serumfResults->result.'</td>
										<td width="200">NEGATIVE</td>
									</tr>
									<tr><td colspan="3"></td></tr>';
								}
							}
							$dochtml .= '</tbody></table>';
							$dochtml .= '<p></p>';
						}
					}
				}
			}

			if($dochtml != ''){
				$this->load->library('wordDownload');
				$htd = new WordDownload();
				$htd->createDoc($dochtml,$fileName,true);
				$this->session->set_flashdata("success", "Serum Test Result Download successfully.");
				redirect('orders');
			}else{
				$this->session->set_flashdata("error", "Error On Serum Test Result Downloading.");
				redirect('orders');
			}
		}else{
			$this->session->set_flashdata("error", "Error On Serum Test Result Downloading.");
			redirect('orders');
		}
		exit;
	}

	function recommendation($id = ''){
		$data = $this->OrdersModel->getRecord($id);
		$slected = $this->input->post('treatment');
		if($slected == 1){
			$this->_data['allergen_total'] = count($this->input->post('allergens1'));
			$data['allergens'] = json_encode($this->input->post('allergens1'));
		}else{
			$this->_data['allergen_total'] = count($this->input->post('allergens2'));
			$data['allergens'] = json_encode($this->input->post('allergens2'));
		}
		$this->_data['allergens_group'] = $this->AllergensModel->get_allergens_dropdown(array("0" => "1"));
		$this->_data['id'] = $id;
		$this->_data['slected_treatment'] = $slected;

		$allergenTotal = !empty($this->input->post('allergen_total'))?$this->input->post('allergen_total'):0;
		if($allergenTotal>0){
			$allergenArr = json_decode($this->input->post('allergenArr'));
			$slectedTreatment = $this->input->post('slected_treatment');
			$newAllergens = $this->input->post('allergens');
			if(!empty($newAllergens)){
				$orderData['id'] = $id;
				if($slectedTreatment == 1){
					$orderData['treatment_1'] = json_encode($newAllergens);
				}elseif($slectedTreatment == 2){
					$orderData['treatment_2'] = json_encode($newAllergens);
				}else{
					$orderData['treatment_3'] = json_encode($newAllergens);
				}
				$this->OrdersModel->add_edit($orderData);
				$this->session->set_flashdata("success", "New recommendation saved successfully and Result Interpretation Updated.");
			}
			if($data['serum_type'] == 1){
				redirect('orders/interpretation/'.$id);
			}else{
				redirect('orders/treatment/'.$id);
			}
		}

		if (!empty($data)) {
			$this->_data['data'] = $data;
		}
		$this->load->view("orders/serum_allergens", $this->_data);
	}

	function sendPaxResultNotificationforPetOwner($orderId){
		if($orderId > 0){
			$data = $this->OrdersModel->allData($orderId, "");
			$this->_data['order_details'] = $data;
			$this->_data['raptorData'] = $this->OrdersModel->getRaptorData($data['order_number']);

			$emailBody = '<!DOCTYPE html>
			<html>
				<head>
					<title>Blood analyzed and to be interpreted email to veterinarian TBD.</title>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
					<style type="text/css">
					body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
					table,td{mso-table-lspace:0;mso-table-rspace:0}
					img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
					table{border-collapse:collapse!important}
					body{height:100%!important;margin:0!important;padding:0!important;width:100%!important}
					a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important; font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
					@media screen and (max-width: 480px) {
					.mobile-hide{display:none!important}
					.mobile-center{text-align:center!important}
					}
					div[style*="margin: 16px 0;"]{margin:0!important}
					.align_class{padding-left:2.7em}
					</style>
				</head>
				<body style="margin: 0 !important; padding: 0 !important; background-color: #fff;" bgcolor="#fff">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
															<img class="logo-img" src="'. base_url("/assets/images/Nextmune_H-Logo_White.png") .'" alt="NextVu" style="height: 41px;max-width:180px;width:auto;">
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td align="center" style="padding: 0px 35px 10px 35px; background-color: #ffffff;" bgcolor="#ffffff">
											<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 20px;">
														<h2 style="font-size:24px; font-weight: 800; line-height:28px; color: #333333; margin: 0;"> PAX Results for '.$data['pet_name'].' '.$data['po_last'].'</h2>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															The test results for your companion animal, '.$data['pet_name'].' '.$data['po_last'].', have been completed. Please view results by downloading the result booklet.
														</p>
													</td>
												</tr>
												<tr>
													<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;text-align: center;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															<a target="_blank" href="'. base_url("PaxResult/interpretation/".$data['id']."") .'" style="background-color: #3c8dbc;border-color: #367fa9;border-radius: 0px;box-shadow: none;color: #fff;display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;text-decoration: none;">Result Booklet</a>
														</p>
													</td>
												</tr>
												<tr>
													<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
														<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;">
															Other relevant allergy related information or link to our pet parent web-shop / product promotions / etc.
														</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
											<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
												<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
													<tbody><tr>
														<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;font-size: 36px;font-weight: 800;line-height: 48px;color: #fff;text-align: center;" class="mobile-center">
															Thank you,<br>
															Nextmune.
														</td>
													</tr>
												</tbody></table>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>';
			$html = $this->load->view('orders/raptor_serum_result_pdf', $this->_data, true);
			$html = trim($html);

			ob_end_flush();
			require_once(FCPATH.'vendor_pdf/autoload.php');
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
			$mpdf->SetTitle('PAX Serum Result');

			$file_name = FCPATH . SERUM_REQUEST_PDF_PATH . "serum_result_" . $data['order_number'] . ".pdf";

			$mpdf->WriteHTML($html);
			$mpdf->Output($file_name,'F');

			$atcdfileName = SERUM_REQUEST_PDF_PATH . "serum_result_" . $data['order_number'] . ".pdf";

			$practice_email = $data['practice_email'];
			//$to_email = $data['email'];
			//$to_email = "stewart_nextmune@yopmail.com";
			$to_email = "stewart@webbagency.co.uk";
			$from_email = "vetorders.uk@nextmune.com";
			$config = array(
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->from($from_email, "Nextmune");
			$this->email->to($practice_email);
			/* $this->email->set_header('Content-Type', 'application/pdf');
			$this->email->set_header('Content-Disposition', 'attachment');
			$this->email->set_header('Content-Transfer-Encoding', 'base64'); */
			$this->email->subject('PAX Serum Sample for '.$data['pet_name'].' '.$data['po_last'].' received.');
			$this->email->message($emailBody);
			$this->email->set_mailtype("html");
			$this->email->attach($atcdfileName);
			$is_send = $this->email->send();
			if ($is_send) {
				$this->session->set_flashdata("success", "Email sent successfully.");
			} else {
				$this->session->set_flashdata("error", $this->email->print_debugger());
			}
			redirect('orders/interpretation/'.$orderId);
		}else{
			redirect('orders');
		}
	}

	function serum_treatment($id=''){
		$postData = $this->input->post();
		$postData['id'] = $id;
		if(!empty($postData)){
			$data = $this->OrdersModel->allData($id);
			$order_number = $data['order_number'];
			$product_type = $data['product_code_selection'];
			$species_name = $data['species_name'];
			$result = $this->OrdersModel->serumOrderforImmuno($id);
			if($result){
				$updtData['is_order_completed'] = 1;
				$this->db->update('ci_orders', $updtData, array('id'=>$id));
				//$this->session->set_flashdata('success', 'Immunotherpathy Order has been placed successfully.');
				$newData = $this->OrdersModel->allData($result);
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
				if($postData['treatment'] == "3"){
					redirect('orders/allergens/'. $newData['id']);
				}else{
					$orderData['id'] = $newData['id'];
					if($postData['treatment'] == "2"){
						$allergens = $postData['allergens2'];
					}else{
						$allergens = $postData['allergens1'];
					}
					if(!empty($allergens)){
						$orderData['allergens'] = json_encode($allergens);
					}else{
						$orderData['allergens'] = '[]';
					}
					$orderData['practice_lab_comment'] = '';
					$orderData['comment_by'] = 0;
					$orderData['updated_by'] = $this->user_id;
					$orderData['updated_at'] = date("Y-m-d H:i:s");
					$this->OrdersModel->add_edit($orderData);
					redirect('orders/immmuno_summary/'. $newData['id']);
				}
			}else{
				$this->session->set_flashdata('error', 'Sorry! Immunotherpathy Order have an error.');
				redirect('orders/interpretation/'. $id);
			}
		}else{
			$this->session->set_flashdata('error', 'Sorry! Immunotherpathy Order have an error.');
			redirect('orders/interpretation/'. $id);
		}
	}

	function uploadSICDocument(){
		$postdata = $this->input->post();
		$id = $postdata['order_id_sic_modal'];
		if(!empty($id)){
			/* upload sic_document start */
			if ($_FILES["sic_document"]["name"] != '') {
				$temp_name = explode(".", $_FILES["sic_document"]["name"]);
				$config['upload_path']	= SIC_DOC_PATH;
				$config['allowed_types']= 'pdf';
				$config['file_name']	= preg_replace('/\s+/', '_', strtolower($temp_name[0]) .'_'. time() .'.'. $temp_name[1]);

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('sic_document')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					echo 'error';
				} else {
					$upload_data = array('upload_data' => $this->upload->data());
					$orderData['id'] = $id;
					$orderData['sic_document'] = $upload_data['upload_data']['file_name'];
					$this->OrdersModel->add_edit($orderData);
					echo $upload_data['upload_data']['file_name'];
				}
			}
			/* upload sic_document end */
		}
	}

	function changeDeliveryAddress(){
		$postdata = $this->input->post();
		$id = $postdata['order_id_daddress_modal'];
		if(!empty($id) && $postdata['delivery_practice_id'] > 0){
			$orderData['id'] = $id;
			$orderData['order_can_send_to'] = '1';
			$orderData['delivery_practice_id'] = !empty($postdata['delivery_practice_id'])?$postdata['delivery_practice_id']:0;
			$orderData['address1'] = !empty($postdata['address1'])?$postdata['address1']:NULL;
			$orderData['address2'] = !empty($postdata['address2'])?$postdata['address2']:NULL;
			$orderData['address3'] = !empty($postdata['address3'])?$postdata['address3']:NULL;
			$orderData['town_city'] = !empty($postdata['town_city'])?$postdata['town_city']:NULL;
			$orderData['county'] = !empty($postdata['county'])?$postdata['county']:NULL;
			$orderData['country'] = !empty($postdata['country'])?$postdata['country']:NULL;
			$orderData['postcode'] = !empty($postdata['postcode'])?$postdata['postcode']:NULL;
			$orderData['updated_by'] = $this->user_id;
			$orderData['updated_at'] = date("Y-m-d H:i:s");
			if($up_id = $this->OrdersModel->add_edit($orderData) > 0 ){
				$this->session->set_flashdata('success', 'Delivery Address changed successfully.');
				echo 'success';
			}else {
				$this->session->set_flashdata('error', 'Error in change delivery Address, Please try again!');
				echo 'error';
			}
		}else{
			$this->session->set_flashdata('error', 'Error in change delivery Address, Please try again!');
			echo 'error';
		}
	}

	public function importLIMSID(){
		exit;
		ini_set('memory_limit', '256M');
		$this->load->library('excel');
		$inputFileName = FCPATH.'uploaded_files/orderData/Updated_raptor_allergens.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$allergensId = !empty($value['A'])?$value['A']:'';
				$can_allgy_env = !empty($value['B'])?$value['B']:'';
				$fel_allgy_env = !empty($value['C'])?$value['C']:'';
				$equ_allgy_env = !empty($value['D'])?$value['D']:'';
				$can_allgy_food_ige = !empty($value['G'])?$value['G']:'';
				$can_allgy_food_igg = !empty($value['E'])?$value['E']:'';
				$fel_allgy_food_ige = !empty($value['H'])?$value['H']:'';
				$fel_allgy_food_igg = !empty($value['F'])?$value['F']:'';
				$equ_allgy_food_ige = !empty($value['J'])?$value['J']:'';
				$equ_allgy_food_igg = !empty($value['I'])?$value['I']:'';

				if($can_allgy_env != ""){
					$updtData['can_allgy_env'] = $can_allgy_env;
				}
				if($fel_allgy_env != ""){
					$updtData['fel_allgy_env'] = $fel_allgy_env;
				}
				if($equ_allgy_env != ""){
					$updtData['equ_allgy_env'] = $equ_allgy_env;
				}
				if($can_allgy_food_ige != ""){
					$updtData['can_allgy_food_ige'] = $can_allgy_food_ige;
				}
				if($can_allgy_food_igg != ""){
					$updtData['can_allgy_food_igg'] = $can_allgy_food_igg;
				}
				if($fel_allgy_food_ige != ""){
					$updtData['fel_allgy_food_ige'] = $fel_allgy_food_ige;
				}
				if($fel_allgy_food_igg != ""){
					$updtData['fel_allgy_food_igg'] = $fel_allgy_food_igg;
				}
				if($equ_allgy_food_ige != ""){
					$updtData['equ_allgy_food_ige'] = $equ_allgy_food_ige;
				}
				if($equ_allgy_food_igg != ""){
					$updtData['equ_allgy_food_igg'] = $equ_allgy_food_igg;
				}
				if($can_allgy_env != "" || $fel_allgy_env != "" || $equ_allgy_env != "" || $can_allgy_food_ige != "" || $can_allgy_food_igg != "" || $fel_allgy_food_ige != "" || $fel_allgy_food_igg != "" || $equ_allgy_food_ige != "" || $equ_allgy_food_igg != ""){
					//$this->db->where('id', $allergensId);
					//$this->db->update('ci_allergens', $updtData);
				}
			}
			$i++;
		}
		echo $i .' Updated.';
		exit;
	}

	public function upload_pdf_image_head(){
		$post = $this->input->post();
		$img = $_POST['imgBase64'];
		$orderID = $_POST['order_id'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = PDF_IMAGE_PATH.'pdf_image_head_'.$orderID .'.png';
		$success = file_put_contents($file, $data);
		print $success ? $file : 'Unable to save the file.';
	}

	public function upload_pdf_image(){
		$post = $this->input->post();
		$img = $_POST['imgBase64'];
		$orderID = $_POST['order_id'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = PDF_IMAGE_PATH.'pdf_image_env_'.$orderID .'.png';
		$success = file_put_contents($file, $data);  
		print $success ? $file : 'Unable to save the file.';
	}

	public function upload_pdf_image2(){
		$post = $this->input->post();
		$img = $_POST['imgBase64'];
		$orderID = $_POST['order_id'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = PDF_IMAGE_PATH.'pdf_image_module_'.$orderID .'.png';
		$success = file_put_contents($file, $data);  
		print $success ? $file : 'Unable to save the file.';
	}

	public function upload_pdf_image3(){
		$post = $this->input->post();
		$img = $_POST['imgBase64'];
		$orderID = $_POST['order_id'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = PDF_IMAGE_PATH.'pdf_image_food_'.$orderID .'.png';
		$success = file_put_contents($file, $data);  
		print $success ? $file : 'Unable to save the file.';
	}

}