<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class StaffCountries extends CI_Controller {
  	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
		}
		$this->user_id = $this->session->userdata('user_id');
		$this->zones = $this->session->userdata('managed_by_id');
		$this->load->model('StaffCountriesModel');
		$this->load->model('StaffMembersModel');
    }

	function list(){
		$this->load->view('staff_countries/index');
    }

	function getTableData(){
		$StaffCountries = $this->StaffCountriesModel->getTableData(); 
		if(!empty($StaffCountries)){
			$zoneNames = [];
			foreach ($StaffCountries as $key => $value) {
				$StaffCountries[$key]->name = ucfirst($value->name);
				$StaffCountries[$key]->prefer_language = ucfirst($value->prefer_language);
				if($value->managed_by_id != ""){
					$this->db->select('managed_by_name');
					$this->db->from('ci_managed_by_members');
					$this->db->where('id IN('.$value->managed_by_id.')');
					$zoneinfo = $this->db->get()->result_array();
					if(!empty($zoneinfo)){
						$zoneNames = [];
						foreach ($zoneinfo as $row) {
							$zoneNames[] = $row['managed_by_name'];
						}
						$StaffCountries[$key]->managed_by = implode(", ",$zoneNames);
					}else{
						$StaffCountries[$key]->managed_by = '';
					}
				}else{
					$StaffCountries[$key]->managed_by = '';
				}
				if($value->invoiced_by != ""){
					$this->db->select('managed_by_name');
					$this->db->from('ci_managed_by_members');
					$this->db->where('id IN('.$value->invoiced_by.')');
					$zoneinfo = $this->db->get()->result_array();
					if(!empty($zoneinfo)){
						$zoneNames = [];
						foreach ($zoneinfo as $row) {
							$zoneNames[] = $row['managed_by_name'];
						}
						$StaffCountries[$key]->invoiced_by = implode(", ",$zoneNames);
					}else{
						$StaffCountries[$key]->invoiced_by = '';
					}
				}else{
					$StaffCountries[$key]->invoiced_by = '';
				}
			}
		}
		$total = $this->StaffCountriesModel->count_all();
		$totalFiltered = $this->StaffCountriesModel->count_filtered();

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $StaffCountries;
		echo json_encode($ajax); exit();
	}

	function addEdit($id= ''){
		$staffCountryData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$data = $this->StaffCountriesModel->getRecord($id);
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		if ($this->input->post('name')) {
			$is_name_unique = "";
			$current_name = !empty($data['name'])?$data['name']:'';
			if($this->input->post('name') != $current_name){
				$is_name_unique = "|is_unique[ci_staff_countries.name]";
			}
			$this->form_validation->set_rules('name', 'name', 'required'.$is_name_unique);
            if ($this->form_validation->run() == FALSE){
                $this->load->view('staff_countries/add_edit','',TRUE);
			}else{
				$staffCountryData = $this->input->post();
				$staffCountryData['id'] = $id;
				$staffCountryData['managed_by_id'] = !empty($this->input->post('managed_by_id'))?implode(",",$this->input->post('managed_by_id')):'';
				if(is_numeric($id)>0){
					$staffCountryData['updated_by'] = $this->user_id;
					$staffCountryData['updated_at'] = date("Y-m-d H:i:s");
					if ($upid = $this->StaffCountriesModel->add_edit($staffCountryData)>0) {
						$this->db->select('id,country');
						$this->db->from('ci_users');
						$this->db->where('role', '2');
						$this->db->where('country', $staffCountryData['id']);
						$this->db->order_by('id', 'ASC');
						$datas = $this->db->get()->result_array();
						$practiceArr = [];
						foreach($datas as $row){
							if($row['country'] > 0){
								$practiceArr['managed_by_id'] = $staffCountryData['managed_by_id'];
								$practiceArr['invoiced_by'] = $staffCountryData['invoiced_by'];
								$this->db->where('id', $row['id']);
								$this->db->update('ci_users',$practiceArr);
							}
						}
						$this->session->set_flashdata('success','Staff country data has been updated successfully.');
						redirect('staffCountries/list');
					}
				}else{
					$staffCountryData['created_by'] = $this->user_id;
					$staffCountryData['created_at'] = date("Y-m-d H:i:s");
					if ($insid = $this->StaffCountriesModel->add_edit($staffCountryData)) {
						$this->db->select('id,country');
						$this->db->from('ci_users');
						$this->db->where('role', '2');
						$this->db->where('country', $insid);
						$this->db->order_by('id', 'ASC');
						$datas = $this->db->get()->result_array();
						$practiceArr = [];
						foreach($datas as $row){
							if($row['country'] > 0){
								$practiceArr['managed_by_id'] = $staffCountryData['managed_by_id'];
								$practiceArr['invoiced_by'] = $staffCountryData['invoiced_by'];
								$this->db->where('id', $row['id']);
								$this->db->update('ci_users',$practiceArr);
							}
						}
						$this->session->set_flashdata('success','Staff country data has been added successfully.');
						redirect('staffCountries/list');
					}
				}
			}
		}

        if(is_numeric($id)>0){
			if(!empty($data)){
				$this->_data['data'] = $data;
            }
  		}
  		$this->load->view("staff_countries/add_edit", $this->_data);
    }

	function delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->StaffCountriesModel->delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

}
?>