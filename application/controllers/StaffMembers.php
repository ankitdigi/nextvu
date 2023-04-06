<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class StaffMembers extends CI_Controller {
  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
		$this->zones = $this->session->userdata('managed_by_id');
	    $this->load->model('StaffMembersModel');
    }

	function list(){
        $this->load->view('staff_members/index');
    }

    function getTableData(){
        $Staff_members = $this->StaffMembersModel->getTableData(); 
		if(!empty($Staff_members)){
			foreach ($Staff_members as $key => $value) {
				if(!empty($value->email)){
					$Staff_members[$key]->first_name = $value->first_name." ".$value->last_name;
					$Staff_members[$key]->email = $value->email;
				}
			}
		}
        $total = $this->StaffMembersModel->count_all();
        $totalFiltered = $this->StaffMembersModel->count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Staff_members;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
        $staffMemberData = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data = $this->StaffMembersModel->getRecord($id);
        if ($this->input->post('email')) {
            //set unique value
            $is_email_unique = "";
            $current_email = $data['email']; 
            if($this->input->post('email') != $current_email){
                $is_email_unique = "|is_unique[ci_staff_members.email]";
            }

            //set rules
            $this->form_validation->set_rules('first_name', 'first_name', 'required');
            $this->form_validation->set_rules('last_name', 'last_name', 'required');
            $this->form_validation->set_rules('email', 'email', 'required'.$is_email_unique);
            if ($this->form_validation->run() == FALSE){
                $this->load->view('staff_members/add_edit','',TRUE);
            }else{
                $staffMemberData = $this->input->post();
                $staffMemberData['id'] = $id;
                if(is_numeric($id)>0){
                    $staffMemberData['updated_by'] = $this->user_id;
                    $staffMemberData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->StaffMembersModel->add_edit($staffMemberData)>0) {
                        $this->session->set_flashdata('success','staff member data has been updated successfully.');
                        redirect('staffMembers/list');
                    }
                }else{
                    $staffMemberData['created_by'] = $this->user_id;
                    $staffMemberData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->StaffMembersModel->add_edit($staffMemberData)) {
                        $this->session->set_flashdata('success','staff member data has been added successfully.');
                        redirect('staffMembers/list');
                    }
                }
            }
        }

        //load data edit time
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
        //print_r($this->_data); exit;
  		$this->load->view("staff_members/add_edit", $this->_data);
    }

    function delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->StaffMembersModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

	function managed_by_list(){
        $this->load->view('managed_by/index');
    }

	function managedby_getTableData(){
        $zones_members = $this->StaffMembersModel->getManagedbyTableData();
		if(!empty($zones_members)){
			foreach ($zones_members as $key => $value) {
				$zones_members[$key]->serum_test_address = $value->serum_test_address." ".$value->serum_test_address_2;
			}
		}
        $total = $this->StaffMembersModel->managed_by_count_all();
        $totalFiltered = $this->StaffMembersModel->managed_by_count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $zones_members;
        echo json_encode($ajax); exit();
    }

	function managed_by_addEdit($id= ''){
        $staffMemberData = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data = $this->StaffMembersModel->getManagedbyRecord($id);
        if ($this->input->post('managed_by_name')) {
            $this->form_validation->set_rules('managed_by_name', 'managed_by_name', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('managed_by/add_edit','',TRUE);
            }else{
                $staffMemberData = $this->input->post();
                $staffMemberData['id'] = $id;
                if(is_numeric($id)>0){
                    $staffMemberData['updated_by'] = $this->user_id;
                    $staffMemberData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->StaffMembersModel->add_editManagedby($staffMemberData)>0) {
                        $this->session->set_flashdata('success','Managed By data has been updated successfully.');
                        redirect('staffMembers/managed_by_list');
                    }
                }else{
                    $staffMemberData['created_by'] = $this->user_id;
                    $staffMemberData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->StaffMembersModel->add_editManagedby($staffMemberData)) {
                        $this->session->set_flashdata('success','Managed By data has been added successfully.');
                        redirect('staffMembers/managed_by_list');
                    }
                }
            }
        }

        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}

  		$this->load->view("managed_by/add_edit", $this->_data);
    }

}
?>