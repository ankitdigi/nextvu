<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Species extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
	    $this->load->model('SpeciesModel');
        
    }

	function list(){

        $this->load->view('species/index');
    }

    function getTableData(){
        
        $Species = $this->SpeciesModel->getTableData(); 
        
            if(!empty($Species)){
                foreach ($Species as $key => $value) {
                    if(!empty($value->name)){
                        $Species[$key]->name = $value->name;
                    }
                }
            }
          
        $total = $this->SpeciesModel->count_all();
        $totalFiltered = $this->SpeciesModel->count_filtered();
        
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Species;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
        
        $specieData = [];
        
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        
        $data = $this->SpeciesModel->getRecord($id);
        
        if ($this->input->post('name')) {
            
            
            //set unique value
            $is_name_unique = "";
            $current_name = $data['name']; 
            
            if($this->input->post('name') != $current_name){
                $is_name_unique = "|is_unique[ci_species.name]";
            }
            

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required'.$is_name_unique);
            

            if ($this->form_validation->run() == FALSE){
                $this->load->view('species/add_edit','',TRUE);
            }else{
                
                $specieData = $this->input->post();
                $specieData['id'] = $id;
                
                

                if(is_numeric($id)>0){
                    $specieData['updated_by'] = $this->user_id;
                    $specieData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->SpeciesModel->add_edit($specieData)>0) {
                        $this->session->set_flashdata('success','specie data has been updated successfully.');
                        redirect('species/list');
                    }
                }else{
                    $specieData['created_by'] = $this->user_id;
                    $specieData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->SpeciesModel->add_edit($specieData)) {
                        $this->session->set_flashdata('success','specie data has been added successfully.');
                        redirect('species/list');
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
  		$this->load->view("species/add_edit", $this->_data);
        
    }

    function delete($id){
        
        if ($id != '' && is_numeric($id)) {

            $dataWhere['id'] = $id;
            $delete = $this->SpeciesModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
    
        }
        echo "failed"; exit;
    }

}

?>