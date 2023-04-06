<?php
if(! defined('BASEPATH')) exit('No direct script access allowed'); 
class ShippingPrice extends CI_Controller {
  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
	    $this->load->model('ShippingPriceModel');
        $this->load->model('UsersModel');
    }

    function shipping_list(){
        $this->load->view('shipping_price/index');
    }

    function getTableData(){
		$shippingPrices = $this->ShippingPriceModel->getTableData(); 
		if(!empty($shippingPrices)){
			foreach ($shippingPrices as $key => $value) {
				if(!empty($value->name)){
					$shippingPrices[$key]->name = $value->name;
					$shippingPrices[$key]->parent_name = $value->practice_name;
				}
			}
		}
        $total = $this->ShippingPriceModel->count_all();
        $totalFiltered = $this->ShippingPriceModel->count_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $shippingPrices;
        echo json_encode($ajax);
    }

	function shipping_addEdit($id= ''){
        $shippingPriceData = [];
        $this->_data['data'] = [];
  		$this->_data['id'] = $id;
        $data = $this->ShippingPriceModel->getRecord($id);
        $this->_data['shipping_prices'] = $this->ShippingPriceModel->get_shipping_price_dropdown();
        $this->_data['vatLabUsers'] = $this->UsersModel->getPracticeLab();
        $this->_data['sel_practice_id'] = array();
        if($id > 0){
            $this->_data['sel_practice_id'] = $this->ShippingPriceModel->getSelectedPractices($id);
        }

        if ($this->input->post('name')) {
			$is_name_unique = "";
			$current_name = !empty($data['name'])?$data['name']:''; 
			if($this->input->post('name') != $current_name){
				$is_name_unique = "|is_unique[ci_shipping_price.name]";
			}

			//set rules
			$this->form_validation->set_rules('parent_id', 'parent_id', 'required');
			$this->form_validation->set_rules('name', 'name', 'required');
            if ($this->form_validation->run() == FALSE){
                $this->load->view('shipping_price/add_edit','',TRUE);
            }else{
                $shippingPriceData = $this->input->post();
                $shippingPriceData['id'] = $id;
                if(is_numeric($id)>0){
                    $shippingPriceData['updated_by'] = $this->user_id;
                    $shippingPriceData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->ShippingPriceModel->add_edit($shippingPriceData)>0) {
                        $this->session->set_flashdata('success','Shipping Price has been updated successfully.');
                        redirect('shipping');
                    }
                }else{
                    $shippingPriceData['created_by'] = $this->user_id;
                    $shippingPriceData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->ShippingPriceModel->add_edit($shippingPriceData)) {
                        $this->session->set_flashdata('success','Shipping Price has been added successfully.');
                        redirect('shipping');
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
  		$this->load->view("shipping_price/add_edit", $this->_data);
    }

	function discount_getTableData($product_id){
		$postData = $this->input->post();
		parse_str($postData['formData'], $filterData);
		$filData = array();
		$discountDetails = array();
		if(!empty($filterData['vet_user_id'])){
			foreach ($filterData['vet_user_id'] as $key => $value) {
				$discountDetails = $this->ShippingPriceModel->discount_getTableData($value,$product_id); 
				if( !empty($discountDetails) ){
					$first_coulmn = "<div class='input-group'>".$discountDetails[0]->practice_name."</div>";
					$sec_coulmn = "<div class='input-group'><input type='text' name='uk_discount[]' id='uk_discount' class='uk_discount form-control pull-right' value=".$discountDetails[0]->uk_discount." data-practice_id='".$discountDetails[0]->practice_id."' data-discount_id='".$discountDetails[0]->id."'></div>";
					$third_coulmn = "<div class='input-group'><input type='text' name='roi_discount[]' id='roi_discount_".$discountDetails[0]->practice_id."' class='roi_discount form-control pull-right' value=".$discountDetails[0]->roi_discount." data-practice_id='".$discountDetails[0]->practice_id."' data-discount_id='".$discountDetails[0]->id."'></div>";
					$disc_id = $discountDetails[0]->id;
				}else{
					$practice_name = $this->UsersModel->getRecord($value,"2"); 
					$first_coulmn = "<div class='input-group'>".$practice_name['name']."</div>";
					$sec_coulmn = "<div class='input-group'><input type='text' name='uk_discount[]' id='uk_discount' class='uk_discount form-control pull-right' value='' data-practice_id='".$value."' data-discount_id=''></div>";
					$third_coulmn = "<div class='input-group'><input type='text' name='roi_discount[]' id='roi_discount_".$value."' class='roi_discount form-control pull-right' value='' data-practice_id='".$value."' data-discount_id=''></div>";
					$disc_id = '';
				}
				$filData[$key]['id'] = $disc_id;
				$filData[$key]['first_column'] = $first_coulmn;
				$filData[$key]['second_column'] = $sec_coulmn;
				$filData[$key]['third_column'] = $third_coulmn;
				$total = $this->ShippingPriceModel->discount_count_all($value,$product_id);
				$totalFiltered = $this->ShippingPriceModel->discount_count_filtered($value,$product_id);
			}
		}
        $ajax["recordsTotal"] = isset($filterData['vet_user_id']) ? count($filterData['vet_user_id']) : 0;
        $ajax["recordsFiltered"]  = isset($filterData['vet_user_id']) ? count($filterData['vet_user_id']) : 0;
        $ajax['data'] = $filData;
        echo json_encode($ajax); exit();
    }

	function discount_delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->ShippingPriceModel->discount_delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

	function save_discount(){
		$discountData = $this->input->post();
		if( isset($discountData['discount_arr']) ){
			$update = $this->ShippingPriceModel->save_discount($discountData);
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