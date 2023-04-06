<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class PriceCategories extends CI_Controller {
  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
	    $this->load->model('PriceCategoriesModel');
        $this->load->model('UsersModel');
    }

	function list(){
        $this->load->view('price_categories/index');
    }

    function sub_list(){
        $this->load->view('price_categories/sub_index');
    }

    function getTableData(){
        $PriceCategories = $this->PriceCategoriesModel->getTableData(); 
		if(!empty($PriceCategories)){
			foreach ($PriceCategories as $key => $value) {
				if(!empty($value->name)){
					$PriceCategories[$key]->name = $value->name;
				}
			}
		}
        $total = $this->PriceCategoriesModel->count_all();
        $totalFiltered = $this->PriceCategoriesModel->count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $PriceCategories;
        echo json_encode($ajax); exit();
    }

    function sub_getTableData(){
        $SubPriceCategories = $this->PriceCategoriesModel->sub_getTableData(); 
		if(!empty($SubPriceCategories)){
			foreach ($SubPriceCategories as $key => $value) {
				if(!empty($value->name)){
					$SubPriceCategories[$key]->name = $value->name;
					$SubPriceCategories[$key]->parent_name = $value->parent_name;
				}
			}
		}
        $total = $this->PriceCategoriesModel->sub_count_all();
        $totalFiltered = $this->PriceCategoriesModel->sub_count_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $SubPriceCategories;
        echo json_encode($ajax); exit();
    }

    function discount_getTableData($product_id){
		$postData = $this->input->post();
		parse_str($postData['formData'], $filterData);
		$filData = array();
		$discountDetails = array();
		if(!empty($filterData['vet_user_id'])){
			foreach ($filterData['vet_user_id'] as $key => $value) {
				$discountDetails = $this->PriceCategoriesModel->discount_getTableData($value,$product_id); 
				if( !empty($discountDetails) ){
					$first_coulmn = "<div class='input-group'>".$discountDetails[0]->practice_name."</div>";
					$sec_coulmn = "<div class='input-group'><input type='text' name='sage_code[]' id='sage_code' class='sage_code form-control pull-right' value='".$discountDetails[0]->sage_code."'></div>";
					$third_coulmn = "<div class='input-group'><input type='text' name='uk_discount[]' id='uk_discount' class='uk_discount form-control pull-right' value=".$discountDetails[0]->uk_discount." data-practice_id='".$discountDetails[0]->practice_id."' data-discount_id='".$discountDetails[0]->id."'></div>";
					$disc_id = $discountDetails[0]->id;
				}else{
					$practice_name = $this->UsersModel->getRecord($value,"2"); 
					$first_coulmn = "<div class='input-group'>".$practice_name['name']."</div>";
					$sec_coulmn = "<div class='input-group'><input type='text' name='sage_code[]' id='sage_code' class='sage_code form-control pull-right' value='' ></div>";
					$third_coulmn = "<div class='input-group'><input type='text' name='uk_discount[]' id='uk_discount' class='uk_discount form-control pull-right' value='' data-practice_id='".$value."' data-discount_id=''></div>";
					$disc_id = '';
				}
				$filData[$key]['id'] = $disc_id;
				$filData[$key]['first_column'] = $first_coulmn;
				$filData[$key]['second_column'] = $sec_coulmn;
				$filData[$key]['third_column'] = $third_coulmn;
				$total = $this->PriceCategoriesModel->discount_count_all($value,$product_id);
				$totalFiltered = $this->PriceCategoriesModel->discount_count_filtered($value,$product_id);
			}
		}
        $ajax["recordsTotal"] = isset($filterData['vet_user_id']) ? count($filterData['vet_user_id']) : 0;
        $ajax["recordsFiltered"]  = isset($filterData['vet_user_id']) ? count($filterData['vet_user_id']) : 0;
        $ajax['data'] = $filData;
        echo json_encode($ajax); exit();
    }

    function addEdit($id= ''){
		$priceCategoriesData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$data = $this->PriceCategoriesModel->getRecord($id);
		if ($this->input->post('name')) {
            //set unique value
            $is_name_unique = "";
            $current_name = $data['name']; 
            if($this->input->post('name') != $current_name){
                $is_name_unique = "|is_unique[ci_price.name]";
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required'.$is_name_unique);
            if ($this->form_validation->run() == FALSE){
                $this->load->view('price_categories/add_edit','',TRUE);
            }else{
                $priceCategoriesData = $this->input->post();
                $priceCategoriesData['id'] = $id;
                if(is_numeric($id)>0){
                    $priceCategoriesData['updated_by'] = $this->user_id;
                    $priceCategoriesData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->PriceCategoriesModel->add_edit($priceCategoriesData)>0) {
                        $this->session->set_flashdata('success','Category data has been updated successfully.');
                        redirect('priceCategories/list');
                    }
                }else{
                    $priceCategoriesData['created_by'] = $this->user_id;
                    $priceCategoriesData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->PriceCategoriesModel->add_edit($priceCategoriesData)) {
                        $this->session->set_flashdata('success','Category data has been added successfully.');
                        redirect('priceCategories/list');
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
  		$this->load->view("price_categories/add_edit", $this->_data);
    }

    function sub_addEdit($id= ''){
        $sub_priceCategoriesData = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data = $this->PriceCategoriesModel->getRecord($id);
        $this->_data['price_categories'] = $this->PriceCategoriesModel->get_price_categories_dropdown();
        $this->_data['vatLabUsers'] = $this->UsersModel->getPracticeLab();
        $this->_data['sel_practice_id'] = array();
        if($id > 0){
            $this->_data['sel_practice_id'] = $this->PriceCategoriesModel->getSelectedPractices($id);
        }

        if ($this->input->post('name')) {
			//set unique value
			$is_name_unique = "";
			$current_name = !empty($data['name'])?$data['name']:'';
			if($this->input->post('name') != $current_name){
				$is_name_unique = "|is_unique[ci_price.name]";
			}

			//set rules
			$this->form_validation->set_rules('parent_id', 'parent_id', 'required');
			$this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('price_categories/sub_add_edit','',TRUE);
            }else{
                $sub_priceCategoriesData = $this->input->post();
                $sub_priceCategoriesData['id'] = $id;
				$priceCategoriesData['nominal_code'] = !empty($this->input->post('nominal_code'))?$this->input->post('nominal_code'):0;
                if(is_numeric($id)>0){
                    $sub_priceCategoriesData['updated_by'] = $this->user_id;
                    $sub_priceCategoriesData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->PriceCategoriesModel->add_edit($sub_priceCategoriesData)>0) {
                        $this->session->set_flashdata('success','Sub category data has been updated successfully.');
                        redirect('priceCategories/sub_list');
                    }
                }else{
                    $sub_priceCategoriesData['created_by'] = $this->user_id;
                    $sub_priceCategoriesData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->PriceCategoriesModel->add_edit($sub_priceCategoriesData)) {
                        $this->session->set_flashdata('success','Sub category data has been added successfully.');
                        redirect('priceCategories/sub_list');
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
  		$this->load->view("price_categories/sub_add_edit", $this->_data);
    }

    function delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->PriceCategoriesModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

    function discount_delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->PriceCategoriesModel->discount_delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

    function get_price_categories_dropdown(){
        $priceCategoriesData = $this->PriceCategoriesModel->get_price_categories_dropdown();
        echo json_encode($priceCategoriesData); exit();
    }

    function save_discount(){
        $discountData = $this->input->post();
        //print_r($discountData); exit;
        if( isset($discountData['discount_arr']) ){
            $update = $this->PriceCategoriesModel->save_discount($discountData);
            if($update){
                $status="success";
            }else{
                $status="fail";
            }
        }else{
            $status="nothing_selected";
        }

        $returnArr = array(
            'status' => $status,
        );
        echo json_encode($returnArr);
        exit();
    }

}
?>