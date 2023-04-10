<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Allergens extends CI_Controller {
  	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
		}
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('AllergensModel');
	}

	function list(){
        $this->load->view('allergens/index');
    }

    function sub_list(){
        $this->load->view('allergens/sub_index');
    }

    function getTableData(){
		$Allergens = $this->AllergensModel->getTableData(); 
		if(!empty($Allergens)){
			foreach ($Allergens as $key => $value) {
				if(!empty($value->name)){
					$Allergens[$key]->name = $value->name;
				}
			}
		}
        $total = $this->AllergensModel->count_all();
        $totalFiltered = $this->AllergensModel->count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $Allergens;
        echo json_encode($ajax); exit();
    }

	function sub_getTableData(){
		$SubAllergens = $this->AllergensModel->sub_getTableData(); 
		if(!empty($SubAllergens)){
			foreach ($SubAllergens as $key => $value) {
				if(!empty($value->name)){
					$SubAllergens[$key]->name = $value->name;
					$SubAllergens[$key]->parent_name = $value->parent_name;
					$SubAllergens[$key]->due_date = ($value->due_date!='') ? date("d/m/Y",strtotime($value->due_date)) : "";
					if($value->pax_parent_id > 0){
						$getpaxName = $this->AllergensModel->getPaxnameById($value->pax_parent_id);
						$SubAllergens[$key]->pax_parent_name = $getpaxName->pax_parent_name;
					}else{
						$SubAllergens[$key]->pax_parent_name = '';
					}
				}
			}
		}
        $total = $this->AllergensModel->sub_count_all();
        $totalFiltered = $this->AllergensModel->sub_count_filtered();

        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $SubAllergens;
        echo json_encode($ajax); exit();
    }

	function addEdit($id= ''){
		$allergenData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$data = $this->AllergensModel->getRecord($id);
		if ($this->input->post('name')) {
			//set unique value
			$is_name_unique = "";
			if( isset($id) && $id>0 ){
				$current_name = $data['name']; 
                if($this->input->post('name') != $current_name){
                    $is_name_unique = "|is_unique[ci_allergens.name]";
                }
            }

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required'.$is_name_unique);
            if ($this->form_validation->run() == FALSE){
                $this->load->view('allergens/add_edit','',TRUE);
            }else{
				$allergenData = $this->input->post();
				$allergenData['order_type'] = ( !empty($this->input->post('order_type')) && $this->input->post('order_type')[0]!='') ? json_encode($this->input->post('order_type')) : NULL;
				$allergenData['id'] = $id;
                if(is_numeric($id)>0){
                    $allergenData['updated_by'] = $this->user_id;
                    $allergenData['updated_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->AllergensModel->add_edit($allergenData)>0) {
						$this->session->set_flashdata('success','Allergen data has been updated successfully.');
						redirect('allergens/list');
                    }
                }else{
                    $allergenData['created_by'] = $this->user_id;
                    $allergenData['created_at'] = date("Y-m-d H:i:s");
                    if ($id = $this->AllergensModel->add_edit($allergenData)) {
                        $this->session->set_flashdata('success','Allergen data has been added successfully.');
                        redirect('allergens/list');
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
  		$this->load->view("allergens/add_edit", $this->_data);
    }

	function sub_addEdit($id= ''){
		$sub_allergenData = [];
		$this->_data['data'] = [];
		$this->_data['id'] = $id;
		$data = $this->AllergensModel->getRecord($id);
		if( isset($id) && $id>0 ){
			$orderTypeData = json_decode($data['order_type']);
			if(!empty($orderTypeData)){
				$type1 = array(); $type2 = array();
				foreach($orderTypeData as $key=>$avalue){
					if($avalue == '8' || $avalue == '9' || $avalue == '11'){
						$type2[] = $avalue;
					}else{
						$type1[] = $avalue;
					}
				}
				if(!empty($type1)){
					$this->_data['allergens'] = $this->AllergensModel->get_allergens_dropdown($type1);
				}else{
					$this->_data['allergens'] = $this->AllergensModel->get_allergens_dropdown($orderTypeData);
				}
				if(!empty($type2)){
					$this->_data['paxallergens'] = $this->AllergensModel->get_allergens_dropdown($type2);
				}else{
					$this->_data['paxallergens'] = array();
				}
			}else{
				$this->_data['allergens'] = array();
				$this->_data['paxallergens'] = array();
			}
		}else{
			$this->_data['allergens'] = array();
			$this->_data['paxallergens'] = array();
		}

		if ($this->input->post('name')) {
            //set unique value
			$is_name_unique = "";
			$is_code_unique = "";
			if( isset($id) && $id>0 ){
				$current_name = $data['name']; 
				if($this->input->post('name') != $current_name){
					$is_name_unique = "|is_unique[ci_allergens.name]";
				}
				$current_code = $data['code']; 
				if($this->input->post('code') != $current_code){
					$is_code_unique = "|is_unique[ci_allergens.code]";
				}
			}

            //set rules
            $this->form_validation->set_rules('name', 'name', 'required');
			if(in_array("1",$this->input->post('order_type'))){
				$this->form_validation->set_rules('code', 'code', 'required'.$is_code_unique);
			}
			if(in_array("8",$this->input->post('order_type')) || in_array("9",$this->input->post('order_type')) || in_array("11",$this->input->post('order_type'))){
				$this->form_validation->set_rules('pax_parent_id', 'pax_parent_id', 'required');
			}else{
				$this->form_validation->set_rules('parent_id', 'parent_id', 'required');
			}
            if ($this->form_validation->run() == FALSE){
                $this->load->view('allergens/sub_add_edit','',TRUE);
            }else{
				$sub_allergenData['order_type'] = ( !empty($this->input->post('order_type')) && $this->input->post('order_type')[0]!='') ? json_encode($this->input->post('order_type')) : NULL;
				$sub_allergenData['parent_id'] = !empty($this->input->post('parent_id'))?$this->input->post('parent_id'):0;
				$sub_allergenData['pax_parent_id'] = !empty($this->input->post('pax_parent_id'))?$this->input->post('pax_parent_id'):0;
				$sub_allergenData['name'] = $this->input->post('name');
				$sub_allergenData['pax_name'] = $this->input->post('pax_name');
				$sub_allergenData['pax_latin_name'] = $this->input->post('pax_latin_name');
				$sub_allergenData['code'] = !empty($this->input->post('code'))?$this->input->post('code'):NULL;
				if(in_array("3",$this->input->post('order_type'))){
					$sub_allergenData['can_allgy_env'] = !empty($this->input->post('can_allgy_env'))?$this->input->post('can_allgy_env'):NULL;
				}else{
					$sub_allergenData['can_allgy_env'] = NULL;
				}
				if(in_array("31",$this->input->post('order_type'))){
					$sub_allergenData['fel_allgy_env'] = !empty($this->input->post('fel_allgy_env'))?$this->input->post('fel_allgy_env'):NULL;
				}else{
					$sub_allergenData['fel_allgy_env'] = NULL;
				}
				if(in_array("6",$this->input->post('order_type'))){
					$sub_allergenData['equ_allgy_env'] = !empty($this->input->post('equ_allgy_env'))?$this->input->post('equ_allgy_env'):NULL;
				}else{
					$sub_allergenData['equ_allgy_env'] = NULL;
				}
				if(in_array("5",$this->input->post('order_type'))){
					$sub_allergenData['can_allgy_food_ige'] = !empty($this->input->post('can_allgy_food_ige'))?$this->input->post('can_allgy_food_ige'):NULL;
					$sub_allergenData['can_allgy_food_igg'] = !empty($this->input->post('can_allgy_food_igg'))?$this->input->post('can_allgy_food_igg'):NULL;
				}else{
					$sub_allergenData['can_allgy_food_ige'] = NULL;
					$sub_allergenData['can_allgy_food_igg'] = NULL;
				}
				if(in_array("51",$this->input->post('order_type'))){
					$sub_allergenData['fel_allgy_food_ige'] = !empty($this->input->post('fel_allgy_food_ige'))?$this->input->post('fel_allgy_food_ige'):NULL;
					$sub_allergenData['fel_allgy_food_igg'] = !empty($this->input->post('fel_allgy_food_igg'))?$this->input->post('fel_allgy_food_igg'):NULL;
				}else{
					$sub_allergenData['fel_allgy_food_ige'] = NULL;
					$sub_allergenData['fel_allgy_food_igg'] = NULL;
				}
				if(in_array("7",$this->input->post('order_type'))){
					$sub_allergenData['equ_allgy_food_ige'] = !empty($this->input->post('equ_allgy_food_ige'))?$this->input->post('equ_allgy_food_ige'):NULL;
					$sub_allergenData['equ_allgy_food_igg'] = !empty($this->input->post('equ_allgy_food_igg'))?$this->input->post('equ_allgy_food_igg'):NULL;
				}else{
					$sub_allergenData['equ_allgy_food_ige'] = NULL;
					$sub_allergenData['equ_allgy_food_igg'] = NULL;
				}
				$sub_allergenData['is_mixtures'] = !empty($this->input->post('is_mixtures'))?$this->input->post('is_mixtures'):0;
				$sub_allergenData['available_as_mixtures'] = !empty($this->input->post('available_as_mixtures'))?$this->input->post('available_as_mixtures'):0;
                $sub_allergenData['id'] = $id;
				if($sub_allergenData['is_mixtures'] == 1){
					$sub_allergenData['mixture_allergens'] = json_encode($this->input->post('mixture_allergens'));
				}else{
					$sub_allergenData['mixture_allergens'] = '';
				}
				if($sub_allergenData['available_as_mixtures'] == 1){
					$sub_allergenData['mixture_order_type'] = ( !empty($this->input->post('mixture_order_type')) && $this->input->post('mixture_order_type')[0]!='') ? json_encode($this->input->post('mixture_order_type')) : NULL;
				}else{
					$sub_allergenData['mixture_order_type'] = '';
				}
                if(is_numeric($id)>0){
					$this->db->where('allergens_id IN('. $id.')');
					$this->db->delete('ci_allergens_raptor');
                    $sub_allergenData['updated_by'] = $this->user_id;
                    $sub_allergenData['updated_at'] = date("Y-m-d H:i:s");
                    if ($uid = $this->AllergensModel->add_edit($sub_allergenData)>0) {
						if($sub_allergenData['is_mixtures'] == 0){
							$raptorCodeArr = !empty($this->input->post('raptor_code')) ? $this->input->post('raptor_code') : array();
							$raptorFunction = !empty($this->input->post('raptor_function'))?$this->input->post('raptor_function'):array();
							$emAllergen = !empty($this->input->post('em_allergen'))?$this->input->post('em_allergen'):array();
							$raptorHeader = !empty($this->input->post('raptor_header')) ? $this->input->post('raptor_header') : array();
							if(!empty($raptorCodeArr)){
								foreach($raptorCodeArr as $key=>$value){
									if($value != "" || ($value == "" && $emAllergen[$key] == '1')){
										$rFunction = !empty($raptorFunction[$key])?$raptorFunction[$key]:'';
										$rAllergen = !empty($emAllergen[$key])?$emAllergen[$key]:NULL;
										$rHeader = !empty($raptorHeader[$key])?$raptorHeader[$key]:array();
										$codeInfo = array('allergens_id'=>$id, 'raptor_code'=>$value, 'raptor_function'=>$rFunction, 'em_allergen'=>$rAllergen, 'raptor_header'=>json_encode($rHeader));
										$this->db->insert('ci_allergens_raptor',$codeInfo);
									}
								}
							}
						}
                        $this->session->set_flashdata('success','Sub allergen data has been updated successfully.');
                        redirect('sub_allergens');
                    }
                }else{
                    $sub_allergenData['created_by'] = $this->user_id;
                    $sub_allergenData['created_at'] = date("Y-m-d H:i:s");
                    if ($insrtid = $this->AllergensModel->add_edit($sub_allergenData)) {
						if($sub_allergenData['is_mixtures'] == 0){
							$raptorCodeArr = !empty($this->input->post('raptor_code')) ? $this->input->post('raptor_code') : array();
							$raptorFunction = !empty($this->input->post('raptor_function'))?$this->input->post('raptor_function'):array();
							$emAllergen = !empty($this->input->post('em_allergen'))?$this->input->post('em_allergen'):array();
							$raptorHeader = !empty($this->input->post('raptor_header')) ? $this->input->post('raptor_header') : array();
							if(!empty($raptorCodeArr)){
								foreach($raptorCodeArr as $key=>$value){
									if($value != "" || ($value == "" && $emAllergen[$key] == '1')){
										$rFunction = !empty($raptorFunction[$key])?$raptorFunction[$key]:'';
										$rAllergen = !empty($emAllergen[$key])?$emAllergen[$key]:NULL;
										$rHeader = !empty($raptorHeader[$key])?$raptorHeader[$key]:array();
										$codeInfo = array('allergens_id'=>$insrtid, 'raptor_code'=>$value, 'raptor_function'=>$rFunction, 'em_allergen'=>$rAllergen, 'raptor_header'=>json_encode($rHeader));
										$this->db->insert('ci_allergens_raptor',$codeInfo);
									}
								}
							}
						}
                        $this->session->set_flashdata('success','Sub allergen data has been added successfully.');
                        redirect('sub_allergens');
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
		$this->load->view("allergens/sub_add_edit", $this->_data);
	}

    function delete($id){
        if ($id != '' && is_numeric($id)) {
            $dataWhere['id'] = $id;
            $delete = $this->AllergensModel->delete($dataWhere);
            if($delete){
                echo "success"; exit;
            }
        }
        echo "failed"; exit;
    }

    function get_allergens_dropdown(){
        $orderTypeData = $this->input->post();
		if(!empty($orderTypeData['order_type'])){
			$type1 = array();
			foreach($orderTypeData['order_type'] as $key=>$avalue){
				if($avalue != '8' || $avalue != '9' || $avalue != '11'){
					$type1[] = $avalue;
				}
			}
			$allergensData = $this->AllergensModel->get_allergens_dropdown($type1);
		}else{
			$allergensData = array();
		}
		echo json_encode($allergensData); exit();
    }

	function get_allergens_dropdown_pax(){
        $orderTypeData = $this->input->post();
		if(!empty($orderTypeData['order_type'])){
			$type2 = array();
			foreach($orderTypeData['order_type'] as $key=>$avalue){
				if($avalue == '8' || $avalue == '9' || $avalue == '11'){
					$type2[] = $avalue;
				}
			}
			if(!empty($type2)){
				$allergensData = $this->AllergensModel->get_allergens_dropdown($type2);
			}else{
				$allergensData = array();
			}
		}else{
			$allergensData = array();
		}
		echo json_encode($allergensData); exit();
    }

	function get_sub_allergens_dropdown(){
        $postData = $this->input->post();
        $allergensData = $this->AllergensModel->get_subAllergens_dropdown($postData['parent_id']);
        echo json_encode($allergensData); exit();
    }

    function unavailable(){
        $allergenData = $this->input->post();
        $status="success";
        if( isset($allergenData['due_date_array']) || isset($allergenData['rtest']) ){
            $update = $this->AllergensModel->unavailable($allergenData);
            if($update){
                $status="success";
            }else{
                $status="fail";
            }
        }
        $returnArr = array(
            'status' => $status,
        );
        echo json_encode($returnArr);
        exit();
    }

}
?>