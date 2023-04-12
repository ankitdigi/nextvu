<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Breeds extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
	    $this->load->model('BreedsModel');
	    $this->load->model('SpeciesModel');
    }

	function list(){
        $this->load->view('breeds/index');
    }

    function getTableData(){
		$Breeds = $this->BreedsModel->getTableData(); 
		if(!empty($Breeds)){
			foreach ($Breeds as $key => $value) {
				if(!empty($value->name)){
					$Breeds[$key]->name = $value->name;
					$Breeds[$key]->species = ($value->species_id==1) ? "Cat" : "Dog";
				}
			}
		}

        $total = $this->BreedsModel->count_all();
        $totalFiltered = $this->BreedsModel->count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Breeds;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
        $breedData = [];

        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data = $this->BreedsModel->getRecord($id);
        $this->_data['species'] = $this->SpeciesModel->getRecordAll();

        if ($this->input->post('name')) {
            //set unique value
            $is_name_unique = "";
            $current_name = $data['name']; 
            if($this->input->post('name') != $current_name){
                $is_name_unique = "|is_unique[ci_breeds.name]";
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('breeds/add_edit','',TRUE);
            }else{
                $breedData = $this->input->post();
                $breedData['id'] = $id;
                if(is_numeric($id)>0){
					$uniqueName = $this->unique_name();
					$uniqueName = !empty($uniqueName) ? $uniqueName['id'] : 0;
					if ($uniqueName != $data['id'] && !empty($uniqueName)) {
						$this->session->set_flashdata('error','Breed Name is already available, kindly add different name.');
						redirect('breeds/edit/'.$id);
					}
                    $breedData['updated_by'] = $this->user_id;
                    $breedData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->BreedsModel->add_edit($breedData)>0) {
                        $this->session->set_flashdata('success','Breed data has been updated successfully.');
                        redirect('breeds/list');
                    }
                }else{
                    $breedData['created_by'] = $this->user_id;
                    $breedData['created_at'] = date("Y-m-d H:i:s");
					if ($this->unique_name()) {
						$this->session->set_flashdata('error','Breed Name is already available, kindly add different name.');
						redirect('breeds/add');
					}
                    else if ($id = $this->BreedsModel->add_edit($breedData)) {
                        $this->session->set_flashdata('success','Breed data has been added successfully.');
                        redirect('breeds/list');
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
  		$this->load->view("breeds/add_edit", $this->_data);
    }

	function unique_name() {
		$speciesId = $this->input->post('species_id');
		$name = $this->input->post('name');
		$this->db->select('id');
		$this->db->from('ci_breeds');
		$this->db->where('species_id', $speciesId);
		$this->db->where('name', $name);
		$data = $this->db->get()->row_array();

		if (!empty($data['id'])) {
			return $data;
		} else {
			return FALSE;
		}
	}

    function delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->BreedsModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

    function get_breeds_dropdown(){
        $speciesData = $this->input->post();
        $speciesData['species_id'] = implode(",", $speciesData['species_id']);
        $breedsData = $this->BreedsModel->get_breeds_dropdown($speciesData);
        echo json_encode($breedsData); exit();
    }

}
?>
