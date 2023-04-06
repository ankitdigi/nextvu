<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pets extends CI_Controller {
  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
	    $this->load->model('PetsModel');
        $this->load->model('UsersDetailsModel');
        $this->load->model('UsersModel');
        $this->load->model('AllergensModel');
        $this->load->model('BreedsModel');
        $this->load->model('SpeciesModel');
    }

	function list(){
        $this->load->view('usersDetails/pets/index');
    }

    function getTableData(){
        $Pets = $this->PetsModel->getTableData($this->user_id,$this->user_role); 
        if(!empty($Pets)){
            foreach ($Pets as $key => $value) {
                if(!empty($value->pet_name)){
                    $Pets[$key]->pet_name = $value->pet_name;
                    $Pets[$key]->pet_owner = $value->name;
                }
            }
        }

        $total = $this->PetsModel->count_all();
        $totalFiltered = $this->PetsModel->count_filtered($this->user_id,$this->user_role);
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Pets;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
        $petData = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $role_id = "3";
        $data = $this->PetsModel->getRecord($id);
        if($this->user_role==1){
            $this->_data['vatLabUsers'] = $this->UsersModel->getRecordAll("2");
            $vetUserData['vet_user_id'] = ($id>0) ? $data['vet_user_id'] : '';
            $this->_data['branches'] = $this->UsersDetailsModel->get_branch_dropdown($vetUserData);
            $this->_data['petOwners'] = $this->UsersModel->getRecordAll($role_id);
        }else{
            $this->_data['petOwners'] = $this->UsersModel->getRecordAll($role_id,$this->user_id,$this->user_role);
        }
        $this->_data['allergens'] = $this->AllergensModel->get_allergens_dropdown();
        $speciesData['species_id'] = ($id>0) ? $data['type'] : '';
        $this->_data['breeds'] = $this->BreedsModel->get_breeds_dropdown($speciesData);
        $this->_data['species'] = $this->SpeciesModel->getRecordAll();
  		if ($this->input->post('name')!='') {
            //set rules
            if($this->user_role==1){
                $this->form_validation->set_rules('vet_user_id', 'vet_user_id', 'required');
            }
            if($this->user_role==1 || $this->user_role==2){
                $this->form_validation->set_rules('pet_owner_id', 'pet_owner_id', 'required');
            }
            
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('type', 'type', 'required');
            if ($this->form_validation->run() == FALSE){
                $error = validation_errors();
                if( $this->input->post('is_modal')=='1'){
                    $output = array(
                        'error'	=>	strip_tags($error),
                        'status' => 'fail',
                    );
                    echo json_encode($output); exit;
                }else{
                    $this->load->view('usersDetails/pets/add_edit','',TRUE);
                }
            }else{
                if($this->user_role==1){
                    $petData['vet_user_id'] = $this->input->post('vet_user_id');
                    $petData['branch_id'] = $this->input->post('branch_id');
                }
                if($this->user_role==1 || $this->user_role==2){
                    $petData['pet_owner_id'] = $this->input->post('pet_owner_id');
                }else{
                    $petData['pet_owner_id'] = $this->user_id;
                }
                $petData['name'] = $this->input->post('name');
                $petData['type'] = $this->input->post('type');
                $petData['breed_id'] = $this->input->post('breed_id');
                $petData['comment'] = $this->input->post('comment');
                $petData['nextmune_comment'] = !empty($this->input->post('nextmune_comment'))?$this->input->post('nextmune_comment'):'';
                $petData['gender'] = $this->input->post('gender');
                $petData['age'] = $this->input->post('age');
                $petData['age_year'] = $this->input->post('age_year');
                $petData['id'] = $id;
                $is_from_modal = 0;
                if( $this->input->post('is_modal')=='1' ){
                    $is_from_modal = 1;
                }
                if(is_numeric($id)>0){
                    $petData['updated_by'] = $this->user_id;
                    $petData['updated_at'] = date("Y-m-d H:i:s");
                    if( $is_from_modal=='1'){
                        unset($petData["vet_user_id"]);
                        unset($petData["branch_id"]);
                        unset($petData["pet_owner_id"]);
                        unset($petData["allergen_id"]);
                        //unset($petData["gender"]);
                    }
                    if ($id = $this->PetsModel->add_edit($petData)>0) {
                        if( $this->input->post('is_modal')=='1'){
                            $output = array(
                                'status' => 'success',
                                'petId' => $id
                            );
                            echo json_encode($output); exit;
                        }
                        $this->session->set_flashdata('success','Pet data has been updated successfully.');
                        redirect('pets/list');
                    }
                }else{
					$petData['other_breed'] = $this->input->post('other_breed');
                    $petData['created_by'] = $this->user_id;
                    $petData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->PetsModel->add_edit($petData)) {
                        if( $this->input->post('is_modal')=='1'){
                            $output = array(
                                'status' => 'success',
                                'petId' => $id
                            );
                            echo json_encode($output); exit;
                        }else{
                            $this->session->set_flashdata('success','Pet data has been added successfully.');
                            redirect('pets/list');
                        }
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
  		$this->load->view("usersDetails/pets/add_edit", $this->_data);
        
    }

    function delete($id){
        
        if ($id != '' && is_numeric($id)) {

            $dataWhere['id'] = $id;
            $delete = $this->PetsModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
    
        }
        echo "failed"; exit;
    }

    function get_pets_dropdown(){
        $petOwnerData = $this->input->post();
        $petData = $this->PetsModel->get_pets_dropdown($petOwnerData);
        echo json_encode($petData); exit();
    }

    function getPetDetails(){
        $petData = $this->input->post();
        $petID = $petData['pet_id'];
        $getPetData = $this->PetsModel->getRecord($petID);
        echo json_encode($getPetData); exit();
    }
}

?>
