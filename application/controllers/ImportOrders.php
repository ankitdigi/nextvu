<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class ImportOrders extends CI_Controller {
	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '500M');
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->user_id = $this->session->userdata('user_id');
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
        $this->load->model('UsersDetailsModel');
        $this->load->model('UsersModel');
        $this->load->model('OrdersModel');
        $this->load->model('PetsModel');
        $this->load->model('PracticeModel');
        $this->load->model('StaffCountriesModel');
		$this->load->model('StaffMembersModel');
        $this->_data['countries'] = $this->StaffCountriesModel->getRecordAll();
		$this->_data['staff_members'] = $this->StaffMembersModel->getManagedbyRecordAll();
		$this->load->library('excel');
    }

	function import_data(){
		$this->load->view('usersDetails/importIdexxOrders');
	}

    function insertOrders(){
		ini_set('memory_limit', '500M');
		error_reporting(0);
		$file_directory		= 'uploaded_files/orderData/';
		$new_file_name		= date("dmYHis").rand(000000, 999999).$_FILES["result_file"]["name"];
		move_uploaded_file($_FILES["result_file"]["tmp_name"], $file_directory . $new_file_name);
		$objPHPExcel = new PHPExcel();
		$inputFileType	= PHPExcel_IOFactory::identify($file_directory . $new_file_name);
		$objReader	= PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($file_directory . $new_file_name);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value){
			if($i!=1){
				$this->db->select('id');
				$this->db->from('ci_orders');
				$this->db->where('case_ID LIKE', $value['A']);
				$results = $this->db->get();
				if($results->num_rows() == 0){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $value['I']);
					$this->db->order_by("user_id", "DESC");
					$res4 = $this->db->get();
					if($res4->num_rows() > 0){
						$practice_lab = $res4->row()->user_id;
						$sqluk = "SELECT country,managed_by_id FROM `ci_users` WHERE id = '". $practice_lab ."'";
						$responuk = $this->db->query($sqluk);
						$resultuk = $responuk->row();
						$country = $resultuk->country;
						$managed_by_id = $resultuk->managed_by_id;
					}else{
						$country = '1';
						$practice_lab = '24083';
						$managed_by_id = '1';
					}

					$ordernr = !empty($value['A'])?$value['A']:'';
					$orderdate = !empty($value['B'])?$value['B']:'';
					$order_date = date('Y-m-d', strtotime($orderdate));
					$debcode = !empty($value['C'])?$value['C']:'';
					$debtorname = !empty($value['D'])?$value['D']:'';
					$address = !empty($value['E'])?$value['E']:'';
					$postCode = !empty($value['F'])?$value['F']:'';
					$city = !empty($value['G'])?$value['G']:'';
					$invoicedebcode = !empty($value['I'])?$value['I']:'';
					$invoicedebtor = !empty($value['J'])?$value['J']:'';
					$orderReference = !empty($value['K'])?$value['K']:'';
					$itemcode = !empty($value['L'])?$value['L']:'';
					$item = !empty($value['M'])?$value['M']:'';
					$recepe = !empty($value['N'])?$value['N']:'';
					$animalSpecies = !empty($value['O'])?$value['O']:'';
					$qty = !empty($value['P'])?$value['P']:'';
					$batch = !empty($value['Q'])?$value['Q']:'';
					$animalName = !empty($value['R'])?$value['R']:'';
					$ownerName = !empty($value['S'])?$value['S']:'';

					$practiceData['Customer_name'] = $debtorname;
					$practiceData['Customer_post_code'] = $postCode;
					$practiceData['Customer_Address1'] = $address;
					$practiceData['Customer_Address2'] = '';
					$practiceData['Customer_Address3'] = '';
					$practiceData['Customer_Address4'] = '';
					$practiceData['Town_City'] = $city;
					$practiceData['State_County'] = '';
					$practiceData['Country'] = $country;
					$practiceData['country_code'] = !empty($value['H'])?$value['H']:'';
					$practiceData['account_ref'] = $debcode;
					$practiceData['managed_by_id'] = $managed_by_id;
					$practiceID = $this->getPracticeinfo($practiceData);
					if($practiceID > 0){
						$petOwnerID = $this->getPetownerinfo($practiceID,$ownerName);
						if($petOwnerID > 0){
							$petData['Species'] = $animalSpecies;
							$petData['Patient_name'] = $animalName;
							$petData['practiceID'] = $practiceID;
							$petData['petOwnerID'] = $petOwnerID;
							$petID = $this->getPetinfo($petData);
						}else{
							$petID = '0';
						}
					}else{
						$petOwnerID = '0';
						$petID = '0';
					}
					$order_number = $this->get_order_number();
					if($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0){
						$final_order_number = 1001;
					}else{
						$final_order_number = $order_number['order_number'] + 1;
					}
					$speciestype = '';
					if($animalSpecies == 'Cat' || $animalSpecies == 'C'){
						$speciestype = 3;
					}elseif($animalSpecies == 'Dog' || $animalSpecies == 'D'){
						$speciestype = 1;
					}elseif($animalSpecies == 'Horse' || $animalSpecies == 'H'){
						$speciestype = 2;
					}

					$allergensID = $this->getAllergens(substr($itemcode,1));
					$total_allergen = count(json_decode($allergensID,true));
					$final_price = '0.00';
					$order_discount = '0.00';
					if($total_allergen > 0){
						$artuvetrin_test_price = $this->artuvetrin_test_price($practice_lab);
						if($total_allergen <= 4){
							$order_discount = 0.00;
							/**discount **/
							$artuvetrin_discount = $this->get_discount("16", $practice_lab);
							if(!empty($artuvetrin_discount)){
								$order_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
								$order_discount = sprintf("%.2f", $order_discount);
							}
							/**discount **/
							$final_price = $artuvetrin_test_price[0]['uk_price'] - $order_discount;
							$order_discount = round($order_discount, 2);
						}elseif($total_allergen > 4 && $total_allergen <= 8){
							$order_discount = 0.00;
							/**discount **/
							$artuvetrin_discount = $this->get_discount("17", $practice_lab);
							if (!empty($artuvetrin_discount)) {
								$order_discount = ($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_discount['uk_discount']) / 100;
								$order_discount = sprintf("%.2f", $order_discount);
							}
							/**discount **/
							$final_price = $artuvetrin_test_price[1]['uk_price'] - $order_discount;
							$order_discount = round($order_discount, 2);
						}elseif($total_allergen > 8){
							$final_price = 0.00;
							$order_discount = 0.00;
							$first_range_price = 0.00;
							$order_first_discount = 0.00;
							$order_second_discount = 0.00;
							$quotients = ($total_allergen / 8);
							$quotient = ((int)($total_allergen / 8));
							$remainder = (int)(fmod($total_allergen, 8));

							/**discount **/
							$artuvetrin_second_discount = $this->get_discount("17", $practice_lab);
							$_quotients = $quotients - $quotient;
							$is_update=1;
							if(!empty($artuvetrin_second_discount)){
								if ($_quotients > 0.50) {
									$quotient++;
									$is_update=0;
									$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
									$order_second_discount = sprintf("%.2f", $order_second_discount);
								}else{
									$order_second_discount = ($quotient*($artuvetrin_test_price[1]['uk_price'] * $artuvetrin_second_discount['uk_discount'])) / 100;
									$order_second_discount = sprintf("%.2f", $order_second_discount);
								}
							}

							if ($_quotients > 0.50) {
								if($is_update){
									$quotient++;
								}
								$second_range_price = ($quotient * ($artuvetrin_test_price[1]['uk_price'])) - $order_second_discount;
							}else{
								$second_range_price = ($quotient * ($artuvetrin_test_price[1]['uk_price'])) - $order_second_discount;
							}

							if($remainder > 0){
								/**discount **/
								$artuvetrin_first_discount = $this->get_discount("16",$practice_lab);
								if(!empty($artuvetrin_first_discount)){
									if($_quotients <= 0.50 && $_quotients != 0){
										$order_first_discount = ($artuvetrin_test_price[0]['uk_price'] * $artuvetrin_first_discount['uk_discount'] )/100;
										$order_first_discount = sprintf("%.2f", $order_first_discount);
									}
								}
								/**discount **/
							}
							if($_quotients <= 0.50 && $_quotients != 0) {
								$first_range_price = (1 * ($artuvetrin_test_price[0]['uk_price'])) - $order_first_discount;
							}
							$final_price = $first_range_price + $second_range_price; 
							$order_discount = round($order_first_discount + $order_second_discount, 2);
						}

						$orderData['vet_user_id']	= $practiceID;
						$orderData['pet_owner_id']	= $petOwnerID;
						$orderData['pet_id']		= $petID;
						$orderData['lab_id']		= $practice_lab;
						$orderData['name']			= 'IDEXX API Process';
						$orderData['email']			= 'orders@idexx.com';
						$orderData['phone_number']	= '';
						$orderData['order_type']	= '1';
						$orderData['sub_order_type']= '1';
						$orderData['plc_selection']	= '2';
						$orderData['species_selection']= $speciestype;
						$orderData['order_number'] = $final_order_number;
						$orderData['order_date']	= $order_date;
						$orderData['qty_order']		= $qty;
						$orderData['unit_price']	= $final_price;
						$orderData['order_discount']= $order_discount;
						$orderData['shipping_cost']	= '';
						$orderData['allergens']		= $allergensID;
						$orderData['batch_number']	= '';
						$orderData['is_mail_sent']	= '0';
						$orderData['sic_document']	= '';
						$orderData['reference_number']= '';
						$orderData['purchase_order_number']= '';
						$orderData['is_confirmed']	= '0';
						$orderData['order_can_send_to']= '0';
						$orderData['delivery_practice_id']= '0';
						$orderData['is_repeat_order']= '0';
						$orderData['is_invoiced']	= '0';
						$orderData['is_draft']		= '0';
						$orderData['created_by'] = '24083';
						$orderData['created_at'] = date("Y-m-d H:i:s");
						$orderData['sap_lims']	= '';
						$orderData['case_ID']	= $ordernr;
						$orderData['batch_number']	= $batch;
						$orderData['idexx_Organisation']	= '';
						$orderData['idexx_Country']	= 'UK';
						$orderData['recipient_type']	= '';
						$orderData['other_license_documents']= '';
						$orderData['integration_to_Pharmacies']= '';
						$this->db->insert('ci_orders', $orderData);
						$orderID = $this->db->insert_id();
					}
				}
			}
			$i++;
		}
		echo ($i-1) .' Idexx Orders Imported successfully';
		exit;
	}

	function get_order_number(){
		$this->db->select('MAX(order_number) AS order_number');
		$this->db->from('ci_orders');

		return $this->db->get()->row_array();
	}

	function getPracticeinfo($practiceData){
		if($practiceData['Customer_name'] != "" && $practiceData['account_ref'] != ""){
			$current_date = date("Y-m-d H:i:s");
			$this->db->select('user_id');
			$this->db->from('ci_user_details');
			$this->db->where('column_name LIKE', 'account_ref');
			$this->db->where('column_field LIKE', $practiceData['account_ref']);
			$res1 = $this->db->get();
			if($res1->num_rows() == 0){
				$postUser['name'] = $practiceData['Customer_name'];
				$postUser['last_name'] = '';
				$postUser['email'] = '';
				$postUser['phone_number'] = '';
				$postUser['role'] = 2;
				$postUser['country'] = $practiceData['Country'];
				$postUser['managed_by_id'] = $practiceData['managed_by_id'];
				$postUser['preferred_language'] = 'english';
				$postUser['created_at'] = date("Y-m-d H:i:s");
				$postUser['created_by'] = '1';

				$postUserDetails['id'] = '';
				$postUserDetails['address_1'] = '';
				$postUserDetails['address_2'] = $practiceData['Country'];
				$postUserDetails['address_3'] = $practiceData['Customer_post_code'];
				$postUserDetails['account_ref'] = $practiceData['account_ref'];
				$postUserDetails['tax_code'] = '';
				$postUserDetails['vat_reg'] = '';
				$postUserDetails['country_code'] = $practiceData['country_code'];
				$postUserDetails['comment'] = '';
				$postUserDetails['corporates'] = NULL;
				$postUserDetails['labs'] = NULL;
				$postUserDetails['referrals'] = NULL;
				$postUserDetails['rcds_number'] = '';
				$postUserDetails['add_1'] = $practiceData['Customer_Address1'];
				$postUserDetails['add_2'] = $practiceData['Customer_Address2'];
				$postUserDetails['add_3'] = $practiceData['Customer_Address3'];
				$postUserDetails['add_4'] = $practiceData['Town_City'];
				$postUserDetails['order_can_send_to'] = NULL;
				$postUserDetails['odelivery_address'] = NULL;
				$postUserDetails['opostal_code'] = NULL;
				$postUserDetails['ocity'] = NULL;
				$postUserDetails['ocountry'] = NULL;
				$postUserDetails['buying_groups'] = NULL;
				$postUserDetails['vat_applicable'] = 0;
				$postUserDetails['tm_user_id'] = NULL;
				$postUserDetails['invoice_address_1'] = '';
				$postUserDetails['invoice_address_2'] = '';
				$postUserDetails['invoice_address_3'] = '';
				$postUserDetails['invoice_city'] = '';
				$postUserDetails['invoice_country'] = '';
				$postUserDetails['invoice_postcode'] = '';
				$postUserDetails['invoicing_account'] = '';
				$postUserDetails['invoicing_email'] = '';
				$postUserDetails['parentCode'] = '';
				$postUserDetails['parentCompany'] = '';

				$userID = $this->UsersDetailsModel->add_edit($postUser,$postUserDetails);
				return $userID;
			}else{
				return $res1->row()->user_id;
			}
		}else{
			return 0;
		}
	}

	function getPetownerinfo($practiceID,$ownerData=''){
		if(!empty($ownerData)){
			$this->db->select('id');
			$this->db->from('ci_users');
			$this->db->where('last_name LIKE', $ownerData);
			$results = $this->db->get();
			if($results->num_rows() > 0){
				return $results->row()->id;
			}else{
				$petowners_to_vetusers['parent_id'] = array($practiceID);
				$petowners_to_vetusers['user_type'] = '2';

				$postUser['id'] = '';
				$postUser['name'] = '';
				$postUser['last_name'] = $ownerData;
				$postUser['email'] = '';
				$postUser['password'] = '';
				$postUser['role'] = '3';
				$postUser['country'] = '1';
				$postUser['created_by'] = '1';
				$postUser['created_at'] = date("Y-m-d H:i:s");
				$is_from_modal = 0;

				$ownerID = $this->UsersModel->petOwners_add_edit($postUser,$petowners_to_vetusers,$this->user_id,$this->user_role,$is_from_modal);
				return $ownerID;
			}
		}else{
			return 0;
		}
	}

	function getPetinfo($petData){
		if(!empty($petData) && $petData['Patient_name'] != ""){
			if($petData['Species'] == 'Cat' || $petData['Species'] == 'C'){
				$type = 1;
			}elseif($petData['Species'] == 'Dog' || $petData['Species'] == 'D'){
				$type = 2;
			}elseif($petData['Species'] == 'Horse' || $petData['Species'] == 'H'){
				$type = 3;
			}
			$this->db->select('id');
			$this->db->from('ci_pets');
			$this->db->where('vet_user_id', $petData['practiceID']);
			$this->db->where('pet_owner_id', $petData['petOwnerID']);
			$this->db->where('name LIKE', $petData['Patient_name']);
			$this->db->where('type', $type);
			$results = $this->db->get();
			if($results->num_rows() > 0){
				return $results->row()->id;
			}else{
				$petsData['id'] = '';
				$petsData['vet_user_id'] = $petData['practiceID'];
				$petsData['branch_id'] = 0;
				$petsData['pet_owner_id'] = $petData['petOwnerID'];
				$petsData['name'] = $petData['Patient_name'];
				$petsData['type'] = $type;
				$petsData['breed_id'] = 0;
				$petsData['comment'] = '';
				$petsData['nextmune_comment'] = '';
				$petsData['gender'] = '1';
				$petsData['age'] = NULL;
				$petsData['age_year'] = NULL;
				$petsData['other_breed'] = '';
				$petsData['created_by'] = '24083';
				$petsData['created_at'] = date("Y-m-d H:i:s");
				$petsID = $this->PetsModel->add_edit($petsData);
				return $petsID;
			}
		}else{
			return 0;
		}
	}

	function getAllergens($allergens){
		if(!empty($allergens) && $allergens != ""){
			$allergenArr = array();
			$numeric = preg_replace("/[^0-9]/", "", $allergens);
			$alphabet = preg_replace('/\d+/', '', $allergens);
			if(!empty($numeric)){
				$chunks = str_split($numeric, 3);
				foreach($chunks as $ch){
					$allergenArr[] = "'".$ch."'";
				}
			}

			if(!empty($alphabet)){
				$part = str_split($alphabet);
				foreach($part as $pr){
					$allergenArr[] = "'".$pr."'";
				}
			}

			$allergensStr = implode(",",$allergenArr);
			$this->db->select('id');
			$this->db->from('ci_allergens');
			$this->db->where('code IN('.$allergensStr.')');
			$results = $this->db->get();
			if($results->num_rows() > 0){
				$allergenIDArr = array();
				foreach($results->result_array() as $rowa){
					$allergenIDArr[] = $rowa['id'];
				}
				return json_encode($allergenIDArr);
			}
		}else{
			return NULL;
		}
	}

	function practiceLabCountry($practice_id){
		$this->db->select('user.id,country.name');
		$this->db->from('ci_users AS user');
		$this->db->join('ci_staff_countries AS country', 'country.id=user.country','left');
		$this->db->where('user.id',$practice_id);  
		return $this->db->get()->row_array();
	}

	function artuvetrin_test_price($practice_lab='') {
        $practiceLab = $this->practiceLabCountry($practice_lab);
        if( $practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland'){
			$this->db->select('id,name,roi_price AS uk_price');
        }else{
			$this->db->select('id,name,uk_price');
        }
        $this->db->from('ci_price');
        $this->db->where('id IN(16,17)');  
        return $this->db->get()->result_array(); 
    }

	function get_discount($id, $practice_id) {
		$this->db->select('id,uk_discount');
		$this->db->from('ci_discount');
		$this->db->where('product_id',$id);  
		$this->db->where('practice_id',$practice_id);  
		$query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
    }

}
?>