<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
error_reporting(E_ERROR | E_PARSE);
class ExpandOrder extends CI_Controller {

  	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '256M');
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
	    $this->load->model('OrdersModel');
        $this->load->model('UsersModel');
        $this->load->model('PetsModel');
        $this->load->model('UsersDetailsModel');
        $this->load->model('AllergensModel');
        $this->load->model('BreedsModel');
        $this->load->model('SpeciesModel');
        $this->load->model('PriceCategoriesModel');
        $this->_data['fetch_class'] = $this->router->fetch_class();
        $this->_data['fetch_method'] = $this->router->fetch_method();
    }

	function addEdit($id= ''){
		$orderData = [];
		$data = $this->OrdersModel->getRecord($id);
		if($id > 0){
			$orderIndexID = $id;
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$orderOData['id'] = $id;
			$orderOData['is_cep_after_screening'] = 1;
			$this->OrdersModel->add_edit($orderOData);

			$orderData = $data;
			$orderData['cep_id'] = $id;
			$id = "";
			$orderData['id'] = $id;
			$orderData['send_Exact'] = '0';
			$orderData['shipping_date'] = NULL;
			$serumType = $this->OrdersModel->getSerumTestType($data['order_number']);
			$stypeIDArr = array(); $sresultIDArr = array(); 
			foreach($serumType as $stype){
				$stypeIDArr[] = $stype->type_id;
				$sresultIDArr[] = $stype->result_id;
			}

			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			$allergensArr = [];
			if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
				if($data['cutoff_version'] == 1){
					$cutaoff = '5';
					$cutboff = '10';
					$cutcoff = '60';
					$cutdoff = '75';
				}elseif($data['cutoff_version'] == 2){
					$cutaoff = '100';
					$cutboff = '200';
					$cutcoff = '1200';
					$cutdoff = '1500';
				}else{
					$cutaoff = '200';
					$cutboff = '250';
					$cutcoff = '1200';
					$cutdoff = '1500';
				}
				if(!empty($this->input->post()) && $this->input->post('expand_type') == 1){
					$sub_order_type = '';
					if($data['species_selection'] == 1){
						$orderData['product_code_selection'] = '10';
						$sub_order_type = '3';
					}elseif($data['species_selection'] == 2){
						$orderData['product_code_selection'] = '18';
						$sub_order_type = '6';
					}elseif($data['species_selection'] == 3){
						$orderData['product_code_selection'] = '24';
						$sub_order_type = '31';
					}

					/* Start Grasses */
					$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
					foreach($grassesAllergens as $gvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResults = $this->db->get()->row();
						if(!empty($serumResults)){
							if($serumResults->result >= $cutaoff){
								$allergensArr[] = $gvalue['id'];
							}
						}
					}
					/* End Grasses */

					/* Start Weeds */
					$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
					foreach($weedsAllergens as $wvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResultwed = $this->db->get()->row();
						if(!empty($serumResultwed)){
							if($serumResultwed->result >= $cutaoff){
								$allergensArr[] = $wvalue['id'];
							}
						}
					}
					/* End Weeds */

					/* Start Trees */
					$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
					foreach($treesAllergens as $tvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumResulttres = $this->db->get()->row();
						if(!empty($serumResulttres)){
							if($serumResulttres->result >= $cutaoff){
								$allergensArr[] = $tvalue['id'];
							}
						}
					}
					/* End Trees */

					/* Start Crops */
					$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
					foreach($cropsAllergens as $cvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$serumcResultcrp = $this->db->get()->row();
						if(!empty($serumcResultcrp)){
							if($serumcResultcrp->result >= $cutaoff){
								$allergensArr[] = $cvalue['id'];
							}
						}
					}
					/* End Crops */

					/* Start Indoor(Mites/Moulds/Epithelia) */
					$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
					foreach($indoorAllergens as $ivalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$indoorResults = $this->db->get()->row();
						if(!empty($indoorResults)){
							if($ivalue['parent_id'] == '6'){
								if($indoorResults->result >= $cutcoff){
									$allergensArr[] = $ivalue['id'];
								}
							}else{
								if($indoorResults->result >= $cutaoff){
									$allergensArr[] = $ivalue['id'];
								}
							}
						}
					}
					/* End Indoor(Mites/Moulds/Epithelia) */

					/* Start Insects */
					$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
					foreach($insectAllergens as $itvalue){
						$this->db->select('*');
						$this->db->from('ci_serum_result_allergens');
						$this->db->where('result_id IN('.$sresultID.')');
						$this->db->where('type_id IN('.$stypeID.')');
						$this->db->where('(lims_allergens_id = "'.$itvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['equ_allgy_env'].'")');
						$this->db->order_by('id', 'ASC');
						$insectResults = $this->db->get()->row();
						if(!empty($insectResults)){
							if($insectResults->result >= $cutaoff){
								$allergensArr[] = $itvalue['id'];
							}
						}
					}
					/* End Insects */

					$getAllergenParent = $this->AllergensModel->getAllergenParent(json_encode($allergensArr));
					$groupallergensArr = [];
					foreach($getAllergenParent as $apvalue){
						$sql = "SELECT id FROM ci_allergens WHERE parent_id = ".$apvalue['parent_id']."";
						if($sub_order_type != ''){
							$ordertypeArr = explode(",",$sub_order_type);
							$sql .= " AND ("; $i=0;
							foreach($ordertypeArr as $rowa){
								if($i==0){
									$sql .= "JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
								}else{
									$sql .= " OR JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
								}
								$i++;
							}
							$sql .= ")";
						}
						$sql .= " ORDER BY `name` ASC";
						$responce = $this->db->query($sql);
						$sub2Allergens = $responce->result_array();
						foreach($sub2Allergens as $s2value){
							if($s2value['name'] != "N/A"){
								$groupallergensArr[] = $s2value['id'];
							}
						}
					}
					$orderData['allergens'] = json_encode($groupallergensArr);
					$order_number = $this->OrdersModel->get_order_number();
					if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
						$final_order_number = 1001;
					} else {
						$final_order_number = $order_number['order_number'] + 1;
					}
					$orderData['order_number'] = $final_order_number;
					$orderData['order_date'] = date("Y-m-d");
					$orderData['unit_price'] = '0.00';
					$orderData['order_discount'] = '0.00';
					$orderData['shipping_cost'] = '0.00';
					$orderData['is_mail_sent'] = '0';
					$orderData['is_confirmed'] = '0';
					$orderData['is_repeat_order'] = "1";
					$orderData['is_invoiced'] = '0';
					$orderData['is_draft'] = 1;
					$orderData['is_expand'] = '0';
					$orderData['is_cep_after_screening'] = '0';
					$orderData['is_authorised'] = '0';
					$orderData['is_raptor_result'] = '0';
					$orderData['is_serum_result_sent'] = '0';
					$orderData['is_order_completed'] = '0';
					$orderData['created_by'] = $this->user_id;
					$orderData['created_at'] = date("Y-m-d H:i:s");
					if($ins_id = $this->OrdersModel->add_edit($orderData)) {
						unset($orderData['save']);
						unset($orderData['next']);
						redirect('expandOrder/allergens/'. $ins_id);
					}
				}elseif(!empty($this->input->post()) && $this->input->post('expand_type') == 2){
					$sub_order_type = '';
					if($data['species_selection'] == 1){
						$orderData['product_code_selection'] = '8';
						$sub_order_type = '5';
					}elseif($data['species_selection'] == 2){
						$orderData['product_code_selection'] = '20';
						$sub_order_type = '7';
					}elseif($data['species_selection'] == 3){
						$orderData['product_code_selection'] = '6';
						$sub_order_type = '51';
					}

					/* Start Food Proteins */
					$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
					foreach($proteinsAllergens as $fpvalue){
						$allergensArr[] = $fpvalue['id'];
					}
					/* End Food Proteins */

					/* Start Food Carbohydrates */
					$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
					foreach($carbohyAllergens as $fcvalue){
						$allergensArr[] = $fcvalue['id'];
					}
					/* End Food Carbohydrates */

					$getAllergenParent = $this->AllergensModel->getAllergenParent(json_encode($allergensArr));
					$groupallergensArr = [];
					foreach($getAllergenParent as $apvalue){
						$sql = "SELECT id FROM ci_allergens WHERE parent_id = ".$apvalue['parent_id']."";
						if($sub_order_type != ''){
							$ordertypeArr = explode(",",$sub_order_type);
							$sql .= " AND ("; $i=0;
							foreach($ordertypeArr as $rowa){
								if($i==0){
									$sql .= "JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
								}else{
									$sql .= " OR JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
								}
								$i++;
							}
							$sql .= ")";
						}
						$sql .= " ORDER BY `name` ASC";
						$responce = $this->db->query($sql);
						$sub2Allergens = $responce->result_array();
						foreach($sub2Allergens as $s2value){
							if($s2value['name'] != "N/A"){
								$groupallergensArr[] = $s2value['id'];
							}
						}
					}
					$orderData['allergens'] = json_encode($groupallergensArr);
					$order_number = $this->OrdersModel->get_order_number();
					if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
						$final_order_number = 1001;
					} else {
						$final_order_number = $order_number['order_number'] + 1;
					}
					$orderData['order_number'] = $final_order_number;
					$orderData['order_date'] = date("Y-m-d");
					$orderData['unit_price'] = '0.00';
					$orderData['order_discount'] = '0.00';
					$orderData['shipping_cost'] = '0.00';
					$orderData['is_mail_sent'] = '0';
					$orderData['is_confirmed'] = '0';
					$orderData['is_repeat_order'] = "1";
					$orderData['is_invoiced'] = '0';
					$orderData['is_draft'] = 1;
					$orderData['is_expand'] = '0';
					$orderData['is_cep_after_screening'] = '0';
					$orderData['is_authorised'] = '0';
					$orderData['is_raptor_result'] = '0';
					$orderData['is_serum_result_sent'] = '0';
					$orderData['is_order_completed'] = '0';
					$orderData['created_by'] = $this->user_id;
					$orderData['created_at'] = date("Y-m-d H:i:s");
					if($ins_id = $this->OrdersModel->add_edit($orderData)) {
						unset($orderData['save']);
						unset($orderData['next']);
						redirect('expandOrder/allergens/'. $ins_id);
					}
				}elseif(!empty($this->input->post()) && $this->input->post('expand_type') == 3){
					redirect('expandOrder/addEditSingle/'. $orderIndexID);
				}
				$this->_data['id'] = $orderIndexID;
				$this->load->view("orders/expand_type", $this->_data);
			}else{
				redirect('expandOrder/addEditSingle/'. $orderIndexID);
			}
		}
	}

    function addEditSingle($id= ''){
		$orderData = [];
		$data = $this->OrdersModel->getRecord($id);
		if($id > 0){
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$orderOData['id'] = $id;
			$orderOData['is_cep_after_screening'] = 1;
			$this->OrdersModel->add_edit($orderOData);

			$orderData = $data;
			$orderData['cep_id'] = $id;
			$id = "";
			$orderData['id'] = $id;
			$orderData['send_Exact'] = '0';
			$orderData['shipping_date'] = NULL;
			$serumType = $this->OrdersModel->getSerumTestType($data['order_number']);
			$stypeIDArr = array(); $sresultIDArr = array(); 
			foreach($serumType as $stype){
				$stypeIDArr[] = $stype->type_id;
				$sresultIDArr[] = $stype->result_id;
			}
			$stypeID = implode(",",$stypeIDArr);
			$sresultID = implode(",",$sresultIDArr);
			if($data['cutoff_version'] == 1){
				$cutaoff = '5';
				$cutboff = '10';
				$cutcoff = '60';
				$cutdoff = '75';
			}elseif($data['cutoff_version'] == 2){
				$cutaoff = '100';
				$cutboff = '200';
				$cutcoff = '1200';
				$cutdoff = '1500';
			}else{
				$cutaoff = '200';
				$cutboff = '250';
				$cutcoff = '1200';
				$cutdoff = '1500';
			}

			$allergensArr = [];
			if((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bComplete Food\b/', $respnedn->name))){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '10';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '18';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '24';
				}
				$allergensArr = [];
				/* Start Grasses */
				$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
				foreach($grassesAllergens as $gvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResults = $this->db->get()->row();
					if(!empty($serumResults)){
						if($serumResults->result >= $cutaoff){
							$allergensArr[] = $gvalue['id'];
						}
					}
				}
				/* End Grasses */

				/* Start Weeds */
				$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
				foreach($weedsAllergens as $wvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResultwed = $this->db->get()->row();
					if(!empty($serumResultwed)){
						if($serumResultwed->result >= $cutaoff){
							$allergensArr[] = $wvalue['id'];
						}
					}
				}
				/* End Weeds */

				/* Start Trees */
				$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
				foreach($treesAllergens as $tvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResulttres = $this->db->get()->row();
					if(!empty($serumResulttres)){
						if($serumResulttres->result >= $cutaoff){
							$allergensArr[] = $tvalue['id'];
						}
					}
				}
				/* End Trees */

				/* Start Crops */
				$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
				foreach($cropsAllergens as $cvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumcResultcrp = $this->db->get()->row();
					if(!empty($serumcResultcrp)){
						if($serumcResultcrp->result >= $cutaoff){
							$allergensArr[] = $cvalue['id'];
						}
					}
				}
				/* End Crops */

				/* Start Indoor(Mites/Moulds/Epithelia) */
				$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
				foreach($indoorAllergens as $ivalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$indoorResults = $this->db->get()->row();
					if(!empty($indoorResults)){
						if($ivalue['parent_id'] == '6'){
							if($indoorResults->result >= $cutcoff){
								$allergensArr[] = $ivalue['id'];
							}
						}else{
							if($indoorResults->result >= $cutaoff){
								$allergensArr[] = $ivalue['id'];
							}
						}
					}
				}
				/* End Indoor(Mites/Moulds/Epithelia) */

				/* Start Insects */
				$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
				foreach($insectAllergens as $itvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$itvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$insectResults = $this->db->get()->row();
					if(!empty($insectResults)){
						if($insectResults->result >= $cutaoff){
							$allergensArr[] = $itvalue['id'];
						}
					}
				}
				/* End Insects */
			}elseif(preg_match('/\bSCREEN Environmental only\b/', $respnedn->name)){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '10';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '18';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '24';
				}
				$allergensArr = [];
				/* Start Grasses */
				$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
				foreach($grassesAllergens as $gvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResults = $this->db->get()->row();
					if(!empty($serumResults)){
						if($serumResults->result >= $cutaoff){
							$allergensArr[] = $gvalue['id'];
						}
					}
				}
				/* End Grasses */

				/* Start Weeds */
				$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
				foreach($weedsAllergens as $wvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResultwed = $this->db->get()->row();
					if(!empty($serumResultwed)){
						if($serumResultwed->result >= $cutaoff){
							$allergensArr[] = $wvalue['id'];
						}
					}
				}
				/* End Weeds */

				/* Start Trees */
				$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
				foreach($treesAllergens as $tvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResulttres = $this->db->get()->row();
					if(!empty($serumResulttres)){
						if($serumResulttres->result >= $cutaoff){
							$allergensArr[] = $tvalue['id'];
						}
					}
				}
				/* End Trees */

				/* Start Crops */
				$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
				foreach($cropsAllergens as $cvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumcResultcrp = $this->db->get()->row();
					if(!empty($serumcResultcrp)){
						if($serumcResultcrp->result >= $cutaoff){
							$allergensArr[] = $cvalue['id'];
						}
					}
				}
				/* End Crops */

				/* Start Indoor(Mites/Moulds/Epithelia) */
				$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
				foreach($indoorAllergens as $ivalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$indoorResults = $this->db->get()->row();
					if(!empty($indoorResults)){
						if($ivalue['parent_id'] == '6'){
							if($indoorResults->result >= $cutcoff){
								$allergensArr[] = $ivalue['id'];
							}
						}else{
							if($indoorResults->result >= $cutaoff){
								$allergensArr[] = $ivalue['id'];
							}
						}
					}
				}
				/* End Indoor(Mites/Moulds/Epithelia) */

				/* Start Insects */
				$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
				foreach($insectAllergens as $itvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$itvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$insectResults = $this->db->get()->row();
					if(!empty($insectResults)){
						if($insectResults->result >= $cutaoff){
							$allergensArr[] = $itvalue['id'];
						}
					}
				}
				/* End Insects */
			}elseif(preg_match('/\bSCREEN Food only\b/', $respnedn->name)){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '8';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '20';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '6';
				}
				$allergensArr = [];
				/* Start Food Proteins */
				$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
				foreach($proteinsAllergens as $fpvalue){
					$allergensArr[] = $fpvalue['id'];
				}
				/* End Food Proteins */

				/* Start Food Carbohydrates */
				$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
				foreach($carbohyAllergens as $fcvalue){
					$allergensArr[] = $fcvalue['id'];
				}
				/* End Food Carbohydrates */
			}elseif((preg_match('/\bSCREEN Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Positive\b/', $respnedn->name))){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '7';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '21';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '23';
				}
				$allergensArr = [];
				/* Start Grasses */
				$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
				foreach($grassesAllergens as $gvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResults = $this->db->get()->row();
					if(!empty($serumResults)){
						if($serumResults->result >= $cutaoff){
							$allergensArr[] = $gvalue['id'];
						}
					}
				}
				/* End Grasses */

				/* Start Weeds */
				$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
				foreach($weedsAllergens as $wvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResultwed = $this->db->get()->row();
					if(!empty($serumResultwed)){
						if($serumResultwed->result >= $cutaoff){
							$allergensArr[] = $wvalue['id'];
						}
					}
				}
				/* End Weeds */

				/* Start Trees */
				$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
				foreach($treesAllergens as $tvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResulttres = $this->db->get()->row();
					if(!empty($serumResulttres)){
						if($serumResulttres->result >= $cutaoff){
							$allergensArr[] = $tvalue['id'];
						}
					}
				}
				/* End Trees */

				/* Start Crops */
				$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
				foreach($cropsAllergens as $cvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumcResultcrp = $this->db->get()->row();
					if(!empty($serumcResultcrp)){
						if($serumcResultcrp->result >= $cutaoff){
							$allergensArr[] = $cvalue['id'];
						}
					}
				}
				/* End Crops */

				/* Start Indoor(Mites/Moulds/Epithelia) */
				$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
				foreach($indoorAllergens as $ivalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$indoorResults = $this->db->get()->row();
					if(!empty($indoorResults)){
						if($ivalue['parent_id'] == '6'){
							if($indoorResults->result >= $cutcoff){
								$allergensArr[] = $ivalue['id'];
							}
						}else{
							if($indoorResults->result >= $cutaoff){
								$allergensArr[] = $ivalue['id'];
							}
						}
					}
				}
				/* End Indoor(Mites/Moulds/Epithelia) */

				/* Start Insects */
				$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
				foreach($insectAllergens as $itvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$itvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$insectResults = $this->db->get()->row();
					if(!empty($insectResults)){
						if($insectResults->result >= $cutaoff){
							$allergensArr[] = $itvalue['id'];
						}
					}
				}
				/* End Insects */

				/* Start Food Proteins */
				$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
				foreach($proteinsAllergens as $fpvalue){
					$allergensArr[] = $fpvalue['id'];
				}
				/* End Food Proteins */

				/* Start Food Carbohydrates */
				$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
				foreach($carbohyAllergens as $fcvalue){
					$allergensArr[] = $fcvalue['id'];
				}
				/* End Food Carbohydrates */
			}elseif(preg_match('/\bSCREEN Environmental & Insect Screen\b/', $respnedn->name)){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '10';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '18';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '24';
				}
				$allergensArr = [];
				/* Start Grasses */
				$grassesAllergens = $this->AllergensModel->get_subAllergens_dropdown(1, $data['allergens']);
				foreach($grassesAllergens as $gvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$gvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$gvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResults = $this->db->get()->row();
					if(!empty($serumResults)){
						if($serumResults->result >= $cutaoff){
							$allergensArr[] = $gvalue['id'];
						}
					}
				}
				/* End Grasses */

				/* Start Weeds */
				$weedsAllergens = $this->AllergensModel->get_subAllergens_dropdown(2, $data['allergens']);
				foreach($weedsAllergens as $wvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$wvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$wvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResultwed = $this->db->get()->row();
					if(!empty($serumResultwed)){
						if($serumResultwed->result >= $cutaoff){
							$allergensArr[] = $wvalue['id'];
						}
					}
				}
				/* End Weeds */

				/* Start Trees */
				$treesAllergens = $this->AllergensModel->get_subAllergens_dropdown(4, $data['allergens']);
				foreach($treesAllergens as $tvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$tvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$tvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumResulttres = $this->db->get()->row();
					if(!empty($serumResulttres)){
						if($serumResulttres->result >= $cutaoff){
							$allergensArr[] = $tvalue['id'];
						}
					}
				}
				/* End Trees */

				/* Start Crops */
				$cropsAllergens = $this->AllergensModel->get_subAllergens_dropdown(3, $data['allergens']);
				foreach($cropsAllergens as $cvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$cvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$cvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$serumcResultcrp = $this->db->get()->row();
					if(!empty($serumcResultcrp)){
						if($serumcResultcrp->result >= $cutaoff){
							$allergensArr[] = $cvalue['id'];
						}
					}
				}
				/* End Crops */

				/* Start Indoor(Mites/Moulds/Epithelia) */
				$indoorAllergens = $this->AllergensModel->get_subAllergens_Indoor('5,6,8', $data['allergens']);
				foreach($indoorAllergens as $ivalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$ivalue['can_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$ivalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$indoorResults = $this->db->get()->row();
					if(!empty($indoorResults)){
						if($ivalue['parent_id'] == '6'){
							if($indoorResults->result >= $cutcoff){
								$allergensArr[] = $ivalue['id'];
							}
						}else{
							if($indoorResults->result >= $cutaoff){
								$allergensArr[] = $ivalue['id'];
							}
						}
					}
				}
				/* End Indoor(Mites/Moulds/Epithelia) */

				/* Start Insects */
				$insectAllergens = $this->AllergensModel->get_subAllergens_dropdown(7, $data['allergens']);
				foreach($insectAllergens as $itvalue){
					$this->db->select('*');
					$this->db->from('ci_serum_result_allergens');
					$this->db->where('result_id IN('.$sresultID.')');
					$this->db->where('type_id IN('.$stypeID.')');
					$this->db->where('(lims_allergens_id = "'.$itvalue['can_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['fel_allgy_env'].'" OR lims_allergens_id = "'.$itvalue['equ_allgy_env'].'")');
					$this->db->order_by('id', 'ASC');
					$insectResults = $this->db->get()->row();
					if(!empty($insectResults)){
						if($insectResults->result >= $cutaoff){
							$allergensArr[] = $itvalue['id'];
						}
					}
				}
				/* End Insects */
			}elseif((preg_match('/\bComplete Environmental Panel\b/', $respnedn->name)) && (preg_match('/\bFood SCREEN\b/', $respnedn->name))){
				if($data['species_selection'] == 1){
					$orderData['product_code_selection'] = '8';
				}elseif($data['species_selection'] == 2){
					$orderData['product_code_selection'] = '20';
				}elseif($data['species_selection'] == 3){
					$orderData['product_code_selection'] = '6';
				}
				$allergensArr = [];
				/* Start Food Proteins */
				$proteinsAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45960', $data['allergens']);
				foreach($proteinsAllergens as $fpvalue){
					$allergensArr[] = $fpvalue['id'];
				}
				/* End Food Proteins */

				/* Start Food Carbohydrates */
				$carbohyAllergens = $this->AllergensModel->get_subAllergensfood_dropdown('45961', $data['allergens']);
				foreach($carbohyAllergens as $fcvalue){
					$allergensArr[] = $fcvalue['id'];
				}
				/* End Food Carbohydrates */
			}

			$sub_order_type = '';
			if($data['species_selection'] == 1){
				if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '3';
				}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '5';
				}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '3,5';
				}
			}

			if($data['species_selection'] == 2){
				if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '6';
				}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '7';
				}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '6,7';
				}
			}

			if($data['species_selection'] == 3){
				if((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (!preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '31';
				}elseif((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '51';
				}elseif((preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
					$sub_order_type = '31,51';
				}
			}

			$getAllergenParent = $this->AllergensModel->getAllergenParent(json_encode($allergensArr));
			$groupallergensArr = [];
			foreach($getAllergenParent as $apvalue){
				$sql = "SELECT id FROM ci_allergens WHERE parent_id = ".$apvalue['parent_id']."";
				if($sub_order_type != ''){
					$ordertypeArr = explode(",",$sub_order_type);
					$sql .= " AND ("; $i=0;
					foreach($ordertypeArr as $rowa){
						if($i==0){
							$sql .= "JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
						}else{
							$sql .= " OR JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
						}
						$i++;
					}
					$sql .= ")";
				}
				$sql .= " ORDER BY `name` ASC";
				$responce = $this->db->query($sql);
				$sub2Allergens = $responce->result_array();
				foreach($sub2Allergens as $s2value){
					if($s2value['name'] != "N/A"){
						$groupallergensArr[] = $s2value['id'];
					}
				}
			}
			$orderData['allergens'] = json_encode($groupallergensArr);
			$order_number = $this->OrdersModel->get_order_number();
			if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
				$final_order_number = 1001;
			} else {
				$final_order_number = $order_number['order_number'] + 1;
			}
			$orderData['order_number'] = $final_order_number;
			$orderData['order_date'] = date("Y-m-d");
			$orderData['unit_price'] = '0.00';
			$orderData['order_discount'] = '0.00';
			$orderData['shipping_cost'] = '0.00';
			$orderData['is_mail_sent'] = '0';
			$orderData['is_confirmed'] = '0';
			$orderData['is_repeat_order'] = "1";
			$orderData['is_invoiced'] = '0';
			$orderData['is_draft'] = 1;
			$orderData['is_expand'] = '0';
			$orderData['is_cep_after_screening'] = '0';
			$orderData['is_authorised'] = '0';
			$orderData['is_raptor_result'] = '0';
			$orderData['is_serum_result_sent'] = '0';
			$orderData['is_order_completed'] = '0';
			$orderData['created_by'] = $this->user_id;
			$orderData['created_at'] = date("Y-m-d H:i:s");
			if($ins_id = $this->OrdersModel->add_edit($orderData)) {
				unset($orderData['save']);
				unset($orderData['next']);
				redirect('expandOrder/allergens/'. $ins_id);
			}
		}
    }

    function allergens($id= ''){
        $data = $this->OrdersModel->getRecord($id);
        $this->_data['id'] = $id;
        $orderData = [];
		if($data['serum_type'] == '1'){
			redirect('orders/summary/'.$id);
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
			$this->_data['sub_order_type'] = $sub_order_type;
			$this->_data['allergens_group'] = $this->AllergensModel->get_allergens_dropdown($subOrder2TypeArr);
			if((!preg_match('/\bEnvironmental\b/', $respnedn->name)) && (preg_match('/\bFood\b/', $respnedn->name))){
				$allergensGroup = $this->_data['allergens_group'];
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
					$getAllergenParent = $this->AllergensModel->getAllergenParent($orderData['allergens']);
					$parentFCount = 0;
					foreach($getAllergenParent as $rows){
						$parentFCount++;
					}
					$unitPrice = ($parentFCount)*45;
					$orderData['unit_price'] = $unitPrice;
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
				$serumTestdata = $this->OrdersModel->getSerumTestRecord($data['cep_id']);
				$serumData = $serumTestdata;
				$serumData['id'] = '';
				$serumData['order_id'] = $id;
				$serumData['created_by'] = $this->user_id;
				$serumData['created_at'] = date("Y-m-d H:i:s");
				$serumData['updated_by'] = $this->user_id;
				$serumData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->serum_test_add_edit($serumData);
				redirect('orders/summary/'.$id);
			}
		}

		$allergen_total = $this->input->post('allergen_total'); 
		if ( $allergen_total!='') {
			$notAvailAllergens = $this->AllergensModel->getNotAvailAllergens($this->input->post('allergens'));
			if( !empty($notAvailAllergens) &&  $notAvailAllergens['name']!='' ){
				$this->session->set_flashdata('error','Sorry allergen <strong>'.$notAvailAllergens['name'].'</strong> is currently unavailable. The respective expected due date is (<strong>'.$notAvailAllergens['due_date'].'</strong>). Please check back on this date to place your order.');
				$this->session->set_flashdata('info','If you would like to proceed without this allergen please untick the box.');
				$data['allergens'] = json_encode($this->input->post('allergens'));
				$this->_data['data'] = $data;
			}else{
				$orderData['id'] = $id;
				$orderData['allergens'] = ($this->input->post('allergens')[0]!='') ? json_encode($this->input->post('allergens')) : NULL;
				$orderData['practice_lab_comment'] = ($this->input->post('practice_lab_comment')!='')?$this->input->post('practice_lab_comment'):'';
				$orderData['comment_by'] = ($this->input->post('practice_lab_comment')!='')?$this->user_id:0;
				$getAllergenParent = $this->AllergensModel->getAllergenParent(json_encode($this->input->post('allergens')));
				$parentCount = 0; $indoorCount = 0;
				foreach($getAllergenParent as $rows){
					if($rows['parent_id'] == '5' || $rows['parent_id'] == '6' || $rows['parent_id'] == '8'){
						$indoorCount = 1;
					}else{
						$parentCount++;
					}
				}
				$unitPrice = ($parentCount+$indoorCount)*45;
				$orderData['unit_price'] = $unitPrice;
				$orderData['updated_by'] = $this->user_id;
				$orderData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->add_edit($orderData);
				$orderProcess = array(
					'repeat_allergens' => json_encode($this->input->post('allergens'))
				);
				$this->session->set_userdata($orderProcess);
				$serumTestdata = $this->OrdersModel->getSerumTestRecord($data['cep_id']);
				$serumData = $serumTestdata;
				$serumData['id'] = '';
				$serumData['order_id'] = $id;
				$serumData['created_by'] = $this->user_id;
				$serumData['created_at'] = date("Y-m-d H:i:s");
				$serumData['updated_by'] = $this->user_id;
				$serumData['updated_at'] = date("Y-m-d H:i:s");
				$this->OrdersModel->serum_test_add_edit($serumData);
				redirect('orders/summary/'.$id);
			}
		}
		if(!empty($data)){
			$this->_data['data'] = $data;
		}
		$this->load->view("orders/expand_allergens",$this->_data);
    }

	function expandPAX($id= ''){
		$orderData = [];
		$data = $this->OrdersModel->getRecord($id);
		if($id > 0){
			$orderIndexID = $id;
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$orderOData['id'] = $id;
			$orderOData['is_cep_after_screening'] = 1;
			$this->OrdersModel->add_edit($orderOData);

			$orderData = $data;
			$orderData['cep_id'] = $id;
			$orderData['id'] = '';
			$orderData['send_Exact'] = '0';
			$orderData['shipping_date'] = NULL;
			$sub_order_type = ''; $subOrderTypeArr = [];
			if((preg_match('/\bPAX Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Screening\b/', $respnedn->name))){
				if(!empty($this->input->post())){
					if($this->input->post('expand_type') == 1){
						$orderData['product_code_selection'] = '34';
						$sub_order_type = '8';
						$subOrderTypeArr[] = '8';
					}elseif($this->input->post('expand_type') == 2){
						$orderData['product_code_selection'] = '33';
						$sub_order_type = '9';
						$subOrderTypeArr[] = '9';
					}elseif($this->input->post('expand_type') == 3){
						$orderData['product_code_selection'] = '38';
						$sub_order_type = '8,9';
						$subOrderTypeArr = array("0" => "8","1" => "9");
					}

					$allergens_group = $this->AllergensModel->get_pax_allergens_dropdown($subOrderTypeArr);
					$parentCount = 0;
					if(!empty($allergens_group)){
						$allergenslct = array();
						foreach ($allergens_group as $key => $value) {
							if($value['pax_name'] != "N/A"){
								$parentCount++;
							}
							$subAllergens = $this->AllergensModel->getPAXSubAllergensdropdown($value['id'],'',$sub_order_type);
							if(!empty($subAllergens)){
								foreach($subAllergens as $skey => $svalue){
									if($svalue['pax_name'] != "N/A"){
										$allergenslct[] = $svalue['id'];
									}
								}
							}
						}
						if(!empty($allergenslct)){
							$orderData['allergens'] = json_encode($allergenslct);
						}else{
							$orderData['allergens'] = '[""]';
						}
					}else{
						$orderData['allergens'] = '[""]';
					}
					$unitPrice = ($parentCount)*45;
					$order_number = $this->OrdersModel->get_order_number();
					if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
						$final_order_number = 1001;
					} else {
						$final_order_number = $order_number['order_number'] + 1;
					}
					$orderData['order_number'] = $final_order_number;
					$orderData['order_date'] = date("Y-m-d");
					$orderData['unit_price'] = $unitPrice;
					$orderData['order_discount'] = '0.00';
					$orderData['shipping_cost'] = '0.00';
					$orderData['is_mail_sent'] = '0';
					$orderData['is_confirmed'] = '0';
					$orderData['is_repeat_order'] = "1";
					$orderData['is_invoiced'] = '0';
					$orderData['is_draft'] = 1;
					$orderData['is_expand'] = '0';
					$orderData['is_cep_after_screening'] = '0';
					$orderData['is_authorised'] = '0';
					$orderData['is_raptor_result'] = '0';
					$orderData['is_serum_result_sent'] = '0';
					$orderData['is_order_completed'] = '0';
					$orderData['created_by'] = $this->user_id;
					$orderData['created_at'] = date("Y-m-d H:i:s");
					if($ins_id = $this->OrdersModel->add_edit($orderData)) {
						unset($orderData['save']);
						unset($orderData['next']);
						redirect('orders/summary/'.$ins_id);
					}
				}else{
					$this->_data['id'] = $id;
					$this->_data['data'] = $data;
					$this->load->view("orders/expand_type_pax", $this->_data);
				}
			}else{
				redirect('expandOrder/expandPAXSingle/'. $orderIndexID);
			}
		}
    }

	function expandPAXSingle($id= ''){
		$orderData = [];
		$data = $this->OrdersModel->getRecord($id);
		if($id > 0){
			$respnedn = $this->OrdersModel->getProductInfo($data['product_code_selection']);
			$orderOData['id'] = $id;
			$orderOData['is_cep_after_screening'] = 1;
			$this->OrdersModel->add_edit($orderOData);

			$orderData = $data;
			$orderData['cep_id'] = $id;
			$orderData['id'] = '';
			$orderData['send_Exact'] = '0';
			$orderData['shipping_date'] = NULL;
			$sub_order_type = ''; $subOrderTypeArr = [];
			if(preg_match('/\bPAX Environmental Screening\b/', $respnedn->name)){
				$orderData['product_code_selection'] = '34';
				$sub_order_type = '8';
				$subOrderTypeArr[] = '8';
			}elseif(preg_match('/\bPAX Food Screening\b/', $respnedn->name)){
				$orderData['product_code_selection'] = '33';
				$sub_order_type = '9';
				$subOrderTypeArr[] = '9';
			}elseif((preg_match('/\bPAX Environmental\b/', $respnedn->name)) && (preg_match('/\bFood Screening\b/', $respnedn->name))){
				$orderData['product_code_selection'] = '38';
				$sub_order_type = '8,9';
				$subOrderTypeArr = array("0" => "8","1" => "9");
			}

			$allergens_group = $this->AllergensModel->get_pax_allergens_dropdown($subOrderTypeArr);
			$parentCount = 0;
			if(!empty($allergens_group)){
				$allergenslct = array();
				foreach ($allergens_group as $key => $value) {
					if($value['pax_name'] != "N/A"){
						$parentCount++;
					}
					$subAllergens = $this->AllergensModel->getPAXSubAllergensdropdown($value['id'],'',$sub_order_type);
					if(!empty($subAllergens)){
						foreach($subAllergens as $skey => $svalue){
							if($svalue['pax_name'] != "N/A"){
								$allergenslct[] = $svalue['id'];
							}
						}
					}
				}
				if(!empty($allergenslct)){
					$orderData['allergens'] = json_encode($allergenslct);
				}else{
					$orderData['allergens'] = '[""]';
				}
			}else{
				$orderData['allergens'] = '[""]';
			}
			$unitPrice = ($parentCount)*45;
			$order_number = $this->OrdersModel->get_order_number();
			if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
				$final_order_number = 1001;
			} else {
				$final_order_number = $order_number['order_number'] + 1;
			}
			$orderData['order_number'] = $final_order_number;
			$orderData['order_date'] = date("Y-m-d");
			$orderData['unit_price'] = $unitPrice;
			$orderData['order_discount'] = '0.00';
			$orderData['shipping_cost'] = '0.00';
			$orderData['is_mail_sent'] = '0';
			$orderData['is_confirmed'] = '0';
			$orderData['is_repeat_order'] = "1";
			$orderData['is_invoiced'] = '0';
			$orderData['is_draft'] = 1;
			$orderData['is_expand'] = '0';
			$orderData['is_cep_after_screening'] = '0';
			$orderData['is_authorised'] = '0';
			$orderData['is_raptor_result'] = '0';
			$orderData['is_serum_result_sent'] = '0';
			$orderData['is_order_completed'] = '0';
			$orderData['created_by'] = $this->user_id;
			$orderData['created_at'] = date("Y-m-d H:i:s");
			if($ins_id = $this->OrdersModel->add_edit($orderData)) {
				unset($orderData['save']);
				unset($orderData['next']);
				redirect('orders/summary/'.$ins_id);
			}
		}
    }

}
