<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Countries extends CI_Controller {
  	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
		}
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('CountriesModel');
    }

	function list(){
		$this->load->view('countries/index');
	}

    function getTableData(){
		$Countries = $this->CountriesModel->getTableData(); 
		if(!empty($Countries)){
			foreach ($Countries as $key => $value) {
				if(!empty($value->name)){
					$Countries[$key]->name = $value->name;
					$Countries[$key]->currency = ($value->currency_id==1) ? "Pound(£)" : "Euro(€)";
				}
			}
		}
        $total = $this->CountriesModel->count_all();
        $totalFiltered = $this->CountriesModel->count_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Countries;
        echo json_encode($ajax); exit();
    }

	function addEdit($id= ''){
		$countryData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$data = $this->CountriesModel->getRecord($id);
		if ($this->input->post('name')) {
			$is_name_unique = "";
			$current_name = $data['name']; 
			if($this->input->post('name') != $current_name){
				$is_name_unique = "|is_unique[ci_countries.name]";
			}
			$this->form_validation->set_rules('name', 'name', 'required'.$is_name_unique);
			if ($this->form_validation->run() == FALSE){
				$this->load->view('countries/add_edit','',TRUE);
			}else{
				$countryData = $this->input->post();
				$countryData['id'] = $id;
				if(is_numeric($id)>0){
					$countryData['updated_by'] = $this->user_id;
					$countryData['updated_at'] = date("Y-m-d H:i:s");
					if ($id = $this->CountriesModel->add_edit($countryData)>0) {
						$this->session->set_flashdata('success','Country data has been updated successfully.');
						redirect('countries/list');
					}
				}else{
					$countryData['created_by'] = $this->user_id;
					$countryData['created_at'] = date("Y-m-d H:i:s");
					if ($id = $this->CountriesModel->add_edit($countryData)) {
						$this->session->set_flashdata('success','Country data has been added successfully.');
						redirect('countries/list');
					}
				}
			}
		}
        if(is_numeric($id)>0){
			if(!empty($data)){
                $this->_data['data'] = $data;
            }
  		}
		$this->load->view("countries/add_edit", $this->_data);
    }

	function delete($id){
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->CountriesModel->delete($dataWhere);
			if($delete){
				echo "success"; exit;
			}
		}
		echo "failed"; exit;
	}

}
?>