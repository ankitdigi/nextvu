<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class ImportExportExcel extends CI_Controller {
  	public function __construct(){
		parent::__construct();
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

	function getPracticeName($id){
		$this->db->select('name,last_name');
		$this->db->from('ci_users');
		$this->db->where('id', $id);
		$datas = $this->db->get()->row_array();
		if($datas['last_name']!=''){
			return $datas['name'].''.$datas['last_name'];
		}else{
			return $datas['name'];
		}
	}

	function importPracticeBranches(){
		exit;
        $this->db->select('id, vet_user_id, customer_number, name, address, address1, address2, address3, town_city, country, postcode, number, email, acc_contact, acc_email, acc_number, ivc_clinic_number');
		$this->db->from('ci_branches');
		$this->db->order_by('vet_user_id', 'ASC');
		$datas = $this->db->get()->result_array();
		$postUser = []; $postUserDetails = [];
		foreach($datas as $row){
			$branchID	= $row['id'];
			$practiceID	= $row['vet_user_id'];

			$this->db->select('branch_counter');
			$this->db->from('ci_users');
			$this->db->where('id', $practiceID);
			$branches = $this->db->get()->row_array();
			$branchCounter = $branches['branch_counter'];
			$branchCounterNew = $branchCounter+1;
			$this->db->where('id', $practiceID);
            $this->db->update('ci_users',array("branch_counter" =>$branchCounterNew));

			$postUser['name'] = !empty($row['name']) ? $row['name'].' - Branch '.$branchCounterNew : '';
			$postUser['last_name'] = '';
			$postUser['email'] = !empty($row['email']) ? $row['email'] : '';
			$postUser['country'] = !empty($row['country']) ? $row['country'] : '1';
			$postUser['phone_number'] = !empty($row['number']) ? $row['number'] : '';
			$postUser['role'] = 2;
			$postUser['created_at'] = date("Y-m-d H:i:s");
			$postUser['created_by'] = $this->user_id;
			$this->db->insert('ci_users', $postUser);
			$userID = $this->db->insert_id();

			$postUserDetails['address_1'] = NULL;
			$postUserDetails['address_2'] = !empty($row['town_city']) ? $row['town_city'] : '';
			$postUserDetails['address_3'] = !empty($row['postcode']) ? $row['postcode'] : '';
			$postUserDetails['account_ref'] = !empty($row['customer_number']) ? $row['customer_number'] : '';
			$postUserDetails['tax_code'] = '';
			$postUserDetails['vat_reg'] = '';
			$postUserDetails['country_code'] = '';
			$postUserDetails['comment'] = '';
			$postUserDetails['corporates'] = NULL;
			$postUserDetails['labs'] = NULL;
			$postUserDetails['referrals'] = NULL;
			$postUserDetails['rcds_number'] = '';
			$postUserDetails['add_1'] = !empty($row['address']) ? $row['address'] : '';
			$postUserDetails['add_2'] = !empty($row['address1']) ? $row['address1'] : '';
			$postUserDetails['add_3'] = !empty($row['address2']) ? $row['address2'] : '';
			$postUserDetails['add_4'] = !empty($row['address3']) ? $row['address3'] : '';
			$postUserDetails['order_can_send_to'] = 1;
			$postUserDetails['odelivery_address'] = NULL;
			$postUserDetails['opostal_code'] = NULL;
			$postUserDetails['ocity'] = NULL;
			$postUserDetails['ocountry'] = NULL;
			$postUserDetails['buying_groups'] = NULL;
			$postUserDetails['vat_applicable'] = 1;
			$postUserDetails['acc_contact'] = !empty($row['acc_contact']) ? $row['acc_contact'] : '';
			$postUserDetails['acc_email'] = !empty($row['acc_email']) ? $row['acc_email'] : '';
			$postUserDetails['acc_number'] = !empty($row['acc_number']) ? $row['acc_number'] : '';
			$postUserDetails['ivc_clinic_number'] = !empty($row['ivc_clinic_number']) ? $row['ivc_clinic_number'] : '';
			$ins_detail = array();
			foreach($postUserDetails as $key => $val){
				$ins_detail = array(
					"user_id" => $userID,
					"column_name" => $key,
					"column_field" => $val,
					"created_at" => date("Y-m-d H:i:s")
				);
				$this->db->insert('ci_user_details', $ins_detail);
			}

			$this->db->select('id,order_number');
			$this->db->from('ci_orders');
			$this->db->where('vet_user_id', $practiceID);
			$this->db->where('branch_id', $branchID);
			$this->db->where('is_draft', 0);
			$orderData = $this->db->get()->result_array();
			if(!empty($orderData)){
				foreach($orderData as $rowo){
					$orderId	= $rowo['id'];
					$orderNumber= $rowo['order_number'];
					$this->db->where('id', $orderId);
					$this->db->update('ci_orders', array("vet_user_id"=>$userID));
				}
			}
		}
		echo 'Import Completed';
    }

	function importPracticeofexcel(){
		$inputFileName = FCPATH.'uploaded_files/orderData/Nano_Master_New_PracticeNEW.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $details = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = $value['A'];
				if($practiceID != ''){
					$this->db->select("id");
					$this->db->from('ci_users');
					$this->db->where('id',$practiceID);
					$practiceData = $this->db->get()->row_array();
					if(!empty($practiceData)){
						$newID='';
						foreach($practiceData as $roe){
							$newID = $roe;
						}
						$countryName = !empty($value['K']) ? $value['K'] : '';
						if($countryName == 'Ireland'){
							$postUser['country'] = '2';
						}else{
							$postUser['country'] = '1';
						}

						$postUser['name'] = !empty($value['C']) ? $value['C'] : '';
						$postUser['email'] = !empty($value['D']) ? $value['D'] : '';
						$postUser['phone_number'] = !empty($value['E']) ? $value['E'] : '';
						$postUser['role'] = 2;
						$postUser['updated_at'] = date("Y-m-d H:i:s");
						$postUser['updated_by'] = $this->user_id;
						$this->db->where('id', $practiceID);
						$this->db->update('ci_users', $postUser);

						$postUserDetails['add_1'] = !empty($value['F']) ? $value['F'] : NULL;
						$postUserDetails['add_2'] = !empty($value['G']) ? $value['G'] : NULL;
						$postUserDetails['add_3'] = !empty($value['H']) ? $value['H'] : NULL;
						$postUserDetails['add_4'] = !empty($value['I']) ? $value['I'] : NULL;
						$postUserDetails['address_2'] = !empty($value['J']) ? $value['J'] : NULL;
						$postUserDetails['address_3'] = !empty($value['L']) ? $value['L'] : NULL;
						$postUserDetails['account_ref'] = !empty($value['B']) ? $value['B'] : NULL;
						$postUserDetails['vat_applicable'] = !empty($value['M']) ? $value['M'] : 0;

						$this->db->select('column_name');
						$this->db->from('ci_user_details');
						$this->db->where('user_id', $practiceID);
						$existing_fields =  $this->db->get()->result_array();
						$existing_fields_arr = [];
						foreach ($existing_fields as $fkey => $fval) {
							$existing_fields_arr[] = $fval['column_name'];
						}
						$details = [];
						foreach($postUserDetails as $key => $val){
							if(in_array($key, $existing_fields_arr)){
								$detail = array(
									"column_name" => $key,
									"column_field" => htmlspecialchars(str_replace("'","/",$val)),
									"updated_at" => date("Y-m-d H:i:s")
								);
								$details[] = $detail;
							}
						}
						$this->db->where('user_id', $practiceID);
						$this->db->update_batch('ci_user_details', $details, 'column_name');
						$x++;
					}
				}
			}
			$i++;
		}
		echo $x.' IMport.';
		exit;
	}

	function importPracticeofexcelDelete(){
		$inputFileName = FCPATH.'uploaded_files/orderData/Nano_Master_New_Practice_DELETE.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = $value['A'];
				$practiceAccount = $value['B'];
                if($practiceAccount != ''){
					$this->db->select("user_id");
					$this->db->from('ci_user_details');
					$this->db->where('column_field',$practiceAccount);
					$this->db->where('column_field !=',$practiceID);
					$practiceData = $this->db->get()->row_array();
					if(!empty($practiceData)){
						$newID='';
						foreach($practiceData as $roe){
							$newID = $roe;
						}
						$this->updateBranchesTable($practiceID,$newID);
						$this->updateDiscountTable($practiceID,$newID);
						$this->updateOrdersTable($practiceID,$newID);
						$this->updateOrdersTable2($practiceID,$newID);
						$this->updatePetsTable($practiceID,$newID);
						$this->deleteUserTable($practiceID);
						$this->deleteUserDetailTable($practiceID);
					}
				}else{
					$this->deleteUserTable($practiceID);
					$this->deleteUserDetailTable($practiceID);
				}
			}
			$i++;
		}
		echo $i.' Completed';
		exit;
	}

	function importPracticeofexcelDeleteError(){
		$inputFileName = FCPATH.'uploaded_files/orderData/Nano_Master_New_Practice_DELETE_ERROR.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1;
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = $value['A'];
				$practiceAccount = $value['C'];
                if($practiceAccount != ''){
					$newID = $value['B'];
					$this->updateBranchesTable($practiceID,$newID);
					$this->updateDiscountTable($practiceID,$newID);
					$this->updateOrdersTable($practiceID,$newID);
					$this->updateOrdersTable2($practiceID,$newID);
					$this->updatePetsTable($practiceID,$newID);
					$this->deleteUserTable($practiceID);
					$this->deleteUserDetailTable($practiceID);
				}else{
					$this->deleteUserTable($practiceID);
					$this->deleteUserDetailTable($practiceID);
				}
			}
			$i++;
		}
		echo $i.' Completed';
		exit;
	}

	function updateBranchesTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_branches', array("vet_user_id"=>$newID));
	}
	
	function updateDiscountTable($oldID,$newID){
		$this->db->where('practice_id', $oldID);
		$this->db->update('ci_discount', array("practice_id"=>$newID));
	}
	
	function updateOrdersTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_orders', array("vet_user_id"=>$newID));
	}
	
	function updateOrdersTable2($oldID,$newID){
		$this->db->where('delivery_practice_id', $oldID);
		$this->db->update('ci_orders', array("delivery_practice_id"=>$newID));
	}
	
	function updatePetsTable($oldID,$newID){
		$this->db->where('vet_user_id', $oldID);
		$this->db->update('ci_pets', array("vet_user_id"=>$newID));
	}

	function deleteUserTable($id){
		$this->db->where('id', $id);
		$delete = $this->db->delete('ci_users');
	}
	
	function deleteUserDetailTable($id){
		$this->db->where('user_id', $id);
		$delete = $this->db->delete('ci_user_details');
	}

	function importPracticebyCountry(){
		echo 'D'; exit;
		ini_set('memory_limit', '256M');
		error_reporting(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/230217_Italy_Customer_List_for_upload_to_Nextview_v2.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $w=0; $x=0; $y=0; $z=0; $postUser = []; $postUserDetails = []; $branchDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$account_ref = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$practiceEmail = !empty($value['C'])?$value['C']:'';
				$phone_number = !empty($value['D'])?$value['D']:'';
				$add_1 = !empty($value['E'])?$value['E']:'';
				$add_2 = '';
				$add_3 = '';
				$city = !empty($value['F'])?$value['F']:'';
				$county = !empty($value['G'])?$value['G']:'';
				$country = '6';
				$managed_by_id = '5';
				$postcode = !empty($value['I'])?$value['I']:'';
				$tm_user_id = '';
				$vat_applicable = '1';
				$vat_reg = !empty($value['J'])?$value['J']:'';
				$rcds_number = !empty($value['K'])?$value['K']:'';
				$corporateGroup = '';
				$userType = !empty($value['L'])?$value['L']:'';
				$country_code = !empty($value['H'])?$value['H']:'';

				if($practiceName != '' && $account_ref != '' && $userType == "Practice"){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $account_ref);
					$res1 = $this->db->get();
					if($res1->num_rows() == 0){
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 2;
						$postUser['country'] = $country;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$postUser['created_by'] = $this->user_id;

						$postUserDetails['id'] = '';
						$postUserDetails['address_1'] = '';
						$postUserDetails['address_2'] = $county;
						$postUserDetails['address_3'] = $postcode;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = $vat_reg;
						$postUserDetails['country_code'] = $country_code;
						$postUserDetails['comment'] = '';
						$postUserDetails['corporates'] = NULL;
						$postUserDetails['labs'] = NULL;
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['rcds_number'] = $rcds_number;
						$postUserDetails['add_1'] = $add_1;
						$postUserDetails['add_2'] = $add_2;
						$postUserDetails['add_3'] = $add_3;
						$postUserDetails['add_4'] = $city;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['vat_applicable'] = $vat_applicable;
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

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails);
						$x++;
					}else{
						$existPracUser = $res1->row()->user_id;
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 2;
						$postUser['country'] = $country;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$postUser['created_by'] = $this->user_id;

						$postUserDetails['id'] = $existPracUser;
						$postUserDetails['address_1'] = '';
						$postUserDetails['address_2'] = $county;
						$postUserDetails['address_3'] = $postcode;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = $vat_reg;
						$postUserDetails['country_code'] = $country_code;
						$postUserDetails['comment'] = '';
						$postUserDetails['corporates'] = NULL;
						$postUserDetails['labs'] = NULL;
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['rcds_number'] = $rcds_number;
						$postUserDetails['add_1'] = $add_1;
						$postUserDetails['add_2'] = $add_2;
						$postUserDetails['add_3'] = $add_3;
						$postUserDetails['add_4'] = $city;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['vat_applicable'] = $vat_applicable;
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

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails);
						$w++;
					}
				}

				if($practiceName != '' && $account_ref != '' && $userType == "Laboratory"){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $account_ref);
					$res2 = $this->db->get();
					if($res2->num_rows() == 0){
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 6;
						$postUser['country'] = $country;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$postUser['created_by'] = $this->user_id;

						$postUserDetails['id'] = '';
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['address_1'] = $add_1;
						$postUserDetails['address_2'] = $add_2;
						$postUserDetails['address_3'] = $add_3;
						$postUserDetails['address_4'] = '';
						$postUserDetails['add_1'] = '';
						$postUserDetails['add_2'] = '';
						$postUserDetails['add_3'] = '';
						$postUserDetails['add_4'] = '';
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['comment'] = '';
						$postUserDetails['corporates'] = NULL;
						$postUserDetails['country_code'] = $country_code;
						$postUserDetails['deliver_to_practice'] = '0';
						$postUserDetails['invoice_to_practice'] = '0';
						$postUserDetails['invoice_to_practice_immu'] = '0';
						$postUserDetails['labs'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['post_code'] = $postcode;
						$postUserDetails['practices'] = NULL;
						$postUserDetails['rcds_number'] = $rcds_number;
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['results_to_practice'] = '0';
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = $vat_reg;
						$postUserDetails['town_city'] = $city;
						$postUserDetails['vat_applicable'] = $vat_applicable;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails);
						$y++;
					}else{
						$existLabUser = $res2->row()->user_id;
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 6;
						$postUser['country'] = $country;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['updated_at'] = date("Y-m-d H:i:s");
						$postUser['updated_by'] = $this->user_id;

						$postUserDetails['id'] = $existLabUser;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['address_1'] = $add_1;
						$postUserDetails['address_2'] = $add_2;
						$postUserDetails['address_3'] = $add_3;
						$postUserDetails['address_4'] = '';
						$postUserDetails['add_1'] = '';
						$postUserDetails['add_2'] = '';
						$postUserDetails['add_3'] = '';
						$postUserDetails['add_4'] = '';
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['comment'] = '';
						$postUserDetails['corporates'] = NULL;
						$postUserDetails['country_code'] = $country_code;
						$postUserDetails['deliver_to_practice'] = '0';
						$postUserDetails['invoice_to_practice'] = '0';
						$postUserDetails['invoice_to_practice_immu'] = '0';
						$postUserDetails['labs'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['post_code'] = $postcode;
						$postUserDetails['practices'] = NULL;
						$postUserDetails['rcds_number'] = $rcds_number;
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['results_to_practice'] = '0';
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = $vat_reg;
						$postUserDetails['town_city'] = $city;
						$postUserDetails['vat_applicable'] = $vat_applicable;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails);
						$z++;
					}
				}
			}
			$i++;
		}
		echo $x.' Practice Added & '.$w.' Practice Updated & '.$y.' Lab Added & '.$z.' Lab Updated';
		exit;
	}

	function importCountries(){
		exit;
		$inputFileName = FCPATH.'uploaded_files/orderData/Netherlands_practice_country.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $staffCountryData = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$countryCode = !empty($value['A'])?$value['A']:'';
				$countryName = !empty($value['B'])?$value['B']:'';
				if($countryCode != '' && $countryName != ''){
					$this->db->select('code');
					$this->db->from('ci_staff_countries');
					$this->db->where('code LIKE', $countryCode);
					$res2 = $this->db->get();
					if($res2->num_rows() == 0){
						$staffCountryData['name'] = $countryName;
						$staffCountryData['code'] = $countryCode;
						$staffCountryData['prefer_language'] = 'english';
						$staffCountryData['serum_test_address'] = $countryName.' Serum Test Address';
						$staffCountryData['managed_by_id'] = '7';
						$staffCountryData['created_by'] = $this->user_id;
						$staffCountryData['created_at'] = date("Y-m-d H:i:s");
						$this->db->insert('ci_staff_countries',$staffCountryData);
						$x++;
					}
				}
			}
			$i++;
		}
		echo $x.' IMport.';
		exit;
	}

	function importCAUsers(){
		exit;
		$inputFileName = FCPATH.'uploaded_files/orderData/NextView_CA_Users.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $postUser = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$fname = !empty($value['A'])?$value['A']:'';
				$lname = !empty($value['B'])?$value['B']:'';
				$postUser['name'] = $fname.' '.$lname;
				$postUser['email'] = !empty($value['C'])?$value['C']:'';
				$postUser['password'] = !empty($value['D'])?md5($value['D']):'';
				$postUser['managed_by_id'] = !empty($value['F'])?$value['F']:'';
				$postUser['role'] = '11';
				$postUser['created_by'] = $this->user_id;
				$postUser['created_at'] = date("Y-m-d H:i:s");

				$this->db->select('id');
				$this->db->from('ci_staff_countries');
				$this->db->where('code LIKE', $value['E']);
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$postUser['country'] = $res2->row()->id;
				}else{
					$postUser['country'] = '1';
				}
				$this->UsersModel->countryUsers_add_edit($postUser,'');
				$x++;
			}
			$i++;
		}
		echo $x.' IMport.';
		exit;
	}

	function exportAllergensHeader(){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PAX Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'PAX Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Raptor Code');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Raptor Function');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'E/M Allergen');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Raptor Header Line 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Raptor Header Line 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Raptor Header Line 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Raptor Header Line 4');

		$sqls = "SELECT id,parent_id,pax_parent_id,name,pax_name FROM `ci_allergens` WHERE `parent_id` != 0";
        $responce = $this->db->query($sqls);
		$results = $responce->result_array();
		$rowCount = 2; 
		foreach($results as $row){
			$sql2s = "SELECT raptor_code,raptor_function,em_allergen,raptor_header FROM `ci_allergens_raptor` WHERE `allergens_id` = ".$row['id']."";
			$resp2 = $this->db->query($sql2s);
			$datag = $resp2->result_array();
			if(!empty($datag)){
				foreach($datag as $agrow){
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['parent_id']);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pax_parent_id']);
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['pax_name']);
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $agrow['raptor_code']);
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $agrow['raptor_function']);
					if($agrow['em_allergen'] == 1){
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Allergen Description');
					}elseif($agrow['em_allergen'] == 2){
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Allergen Extract');
					}elseif($agrow['em_allergen'] == 3){
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Molecular Allergen');
					}else{
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, '');
					}
					if($agrow['raptor_header'] != "" && $agrow['raptor_header'] != '[]' && $agrow['raptor_header'] != '[""]' && $agrow['raptor_header'] != '["","",""]' && $agrow['raptor_header'] != '["","","",""]' && $agrow['raptor_header'] != '["","","","",""]'){
						$detaildArr = json_decode($agrow['raptor_header']);
						if(!empty($detaildArr) && isset($detaildArr[0]) && $detaildArr[0] != ''){
							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $detaildArr[0]);
						}else{
							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, '');
						}
						if(!empty($detaildArr) && isset($detaildArr[1]) &&  $detaildArr[1] != ''){
							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $detaildArr[1]);
						}else{
							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, '');
						}
						if(!empty($detaildArr) && isset($detaildArr[2]) &&  $detaildArr[2] != ''){
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $detaildArr[2]);
						}else{
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, '');
						}
						if(!empty($detaildArr) && isset($detaildArr[3]) &&  $detaildArr[3] != ''){
							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $detaildArr[3]);
						}else{
							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, '');
						}
					}else{
						$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, '');
					}
					$rowCount++;
				}
			}/* else{
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['parent_id']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pax_parent_id']);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['pax_name']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, '');
				$rowCount++;
			} */
		}
		$fileName = 'Live_Nextvu_Allergens_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
    }

	function importCustomerUsers(){
		exit;
		$inputFileName = FCPATH.'uploaded_files/orderData/2Custmers.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $postUser = []; $postUserDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$postCode = !empty($value['C'])?$value['C']:'';
				$fname = !empty($value['D'])?$value['D']:'';
				$lname = !empty($value['E'])?$value['E']:'';
				$name = !empty($lname)?$fname.' '.$lname:$fname;
				$email = !empty($value['F'])?$value['F']:'';
				$password = 'customeres@123';
				if($practiceID != '' && $email != ''){
					$this->db->select('id');
					$this->db->from('ci_users');
					$this->db->where('email LIKE', $email);
					$this->db->where('role', '5');
					$this->db->where('user_type', '1');
					$res = $this->db->get();
					if($res->num_rows() == 0){
						$this->db->select('user_id');
						$this->db->from('ci_user_details');
						$this->db->where('column_name LIKE', 'account_ref');
						$this->db->where('column_field LIKE', $practiceID);
						$res1 = $this->db->get();
						if($res1->num_rows() > 0){
							$existUser = $res1->row();
							$postUser['id'] = '';
							$postUser['name'] = $name;
							$postUser['email'] = $email;
							$postUser['country'] = 9;
							$postUser['password'] = md5($password);
							$postUser['user_type'] = 1;
							$postUser['managed_by_id'] = 8;
							$postUser['role'] = '5';
							$postUser['created_by'] = $this->user_id;
							$postUser['created_at'] = date("Y-m-d H:i:s");

							$postUserDetails['id'] = '';
							$postUserDetails['practices'] = '["'.$existUser->user_id.'"]';
							$postUserDetails['branches'] = NULL;
							$postUserDetails['labs'] = NULL;
							$postUserDetails['lab_branches'] = NULL;
							$postUserDetails['corporates'] = NULL;
							$postUserDetails['corporate_branches'] = NULL;

							$this->UsersModel->customerUsers_add_edit($postUser,$postUserDetails);
						}
					}
					$x++;
				}
			}
			$i++;
		}
		echo $x.' IMport.';
		exit;
	}

	function importBreeds(){
		exit;
		ini_set('memory_limit', '256M');
		error_reporting(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/Breed_Template.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $postUser = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$species = !empty($value['A'])?preg_replace('/\d+/', '', $value['A']):'';
				$breed = !empty($value['B'])?$value['B']:'';
				if($species != '' && ($breed != '' || $gender != '')){
					$this->db->select('id');
					$this->db->from('ci_breeds');
					$this->db->where('name LIKE', $breed);
					$res1 = $this->db->get();
					if($res1->num_rows() == 0){
						if($species == 'G'){
							$postUser['species_id'] = '1';
						}elseif($species == 'E'){
							$postUser['species_id'] = '3';
						}elseif($species == 'P'){
							$postUser['species_id'] = '2';
						}else{
							$postUser['species_id'] = '2';
						}
						$postUser['name'] = $breed;
						$postUser['created_by'] = $this->user_id;
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$this->db->insert('ci_breeds', $postUser);
						$x++;
					}
				}
			}
			$i++;
		}
		echo $x.' IMport.';
		exit;
	}

	function importPetowners(){
		$this->load->view('usersDetails/importPetowners');
	}

	function insertPetowners(){
		//exit;
		ini_set('memory_limit', '256M');
		error_reporting(0);
		$file_directory		= 'uploaded_files/orderData/';
		$new_file_name		= date("dmYHis").rand(000000, 999999).$_FILES["result_file"]["name"];
		move_uploaded_file($_FILES["result_file"]["tmp_name"], $file_directory . $new_file_name);
		$objPHPExcel = new PHPExcel();
		$inputFileType	= PHPExcel_IOFactory::identify($file_directory . $new_file_name);
		$objReader	= PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($file_directory . $new_file_name);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $postUser = []; $petowners_to_vetusers = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$postCode = !empty($value['C'])?$value['C']:'';
				$fname = !empty($value['D'])?$value['D']:'';
				$lname = !empty($value['E'])?$value['E']:'';
				$email = !empty($value['F'])?$value['F']:'';
				$password = 'petowneres@123';
				if($practiceID != '' && ($fname != '' || $lname != '')){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $practiceID);
					$res1 = $this->db->get();
					if($res1->num_rows() > 0){
						$existUser = $res1->row();

						$petowners_to_vetusers['parent_id'] = array($existUser->user_id);
						$petowners_to_vetusers['user_type'] = '2';

						$postUser['id'] = '';
						$postUser['name'] = $fname;
						$postUser['last_name'] = $lname;
						$postUser['email'] = $email;
						$postUser['password'] = md5($password);
						$postUser['role'] = '3';
						$postUser['country'] = '9';
						$postUser['created_by'] = '1';
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$is_from_modal = 0;

						$this->UsersModel->petOwners_add_edit($postUser,$petowners_to_vetusers,$this->user_id,$this->user_role,$is_from_modal);
						$x++;
					}
				}
			}
			$i++;
		}
		$this->session->set_flashdata('success',$x.' Pet Owners Import.');
		redirect('UsersDetails/importPetowners');
	}

	function importPets(){
		$this->load->view('usersDetails/importPets');
	}

	function insertPets(){
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
		$i=1; $x=0; $petData = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$account_ref = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$postCode = !empty($value['C'])?$value['C']:'';
				$pofname = !empty($value['D'])?$value['D']:'';
				$polname = !empty($value['E'])?$value['E']:'';
				$petname = !empty($value['F'])?$value['F']:'';
				$species = !empty($value['G'])?preg_replace('/\d+/', '', $value['G']):'';
				$breed = !empty($value['H'])?$value['H']:'';
				$ayears = !empty($value['I'])?$value['I']:'';
				$amonths = !empty($value['J'])?$value['J']:'';
				$gender = !empty($value['K'])?$value['K']:'';
				if($account_ref != '' && $practiceName != '' && $petname != '' && $polname != '' && $polname != 'X' && $polname != 'x' && $polname != ',' && $polname != '.'){
					$this->db->select("ci_user_details.user_id");
					$this->db->from('ci_user_details');
					$this->db->join('ci_users', 'ci_user_details.user_id = ci_users.id');
					$this->db->where('ci_user_details.column_name LIKE', 'account_ref');
					$this->db->where('ci_user_details.column_field LIKE', $account_ref);
					$this->db->where('ci_users.name LIKE', $practiceName);
					$this->db->where('ci_users.role', '2');
					$this->db->where('ci_users.country', '9');
					$this->db->where('ci_users.managed_by_id', '8');
					$res1 = $this->db->get();
					if($res1->num_rows() > 0){
						$practiceID = $res1->row()->user_id;
					}else{
						$practiceID = 0;
					}

					$this->db->select('id');
					$this->db->from('ci_users');
					$this->db->where('role', '3');
					$this->db->where('country', '9');
					$this->db->where('last_name LIKE', $petname);
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$ownerID = $res2->row()->id;
					}else{
						$ownerID = 0;
					}

					if($practiceID > 0 && $ownerID > 0){
						$petData['id'] = '';
						$petData['vet_user_id'] = $practiceID;
						$petData['branch_id'] = 0;
						$petData['pet_owner_id'] = $ownerID;
						$petData['name'] = $petname;
						if($species == 'G'){
							$petData['type'] = '1';
						}elseif($species == 'E'){
							$petData['type'] = '3';
						}elseif($species == 'P'){
							$petData['type'] = '2';
						}else{
							$petData['type'] = '2';
						}
						if($breed != ''){
							$this->db->select('id');
							$this->db->from('ci_breeds');
							$this->db->where('name LIKE', $breed);
							$this->db->where('species_id', $petData['type']);
							$res3 = $this->db->get();
							if($res3->num_rows() > 0){
								$petData['breed_id'] = $res3->row()->id;
							}else{
								$petData['breed_id'] = 0;
							}
						}else{
							$petData['breed_id'] = 0;
						}
						$petData['comment'] = '';
						$petData['nextmune_comment'] = '';
						if($gender == 'H'){
							$petData['gender'] = '2';
						}elseif($gender == 'V'){
							$petData['gender'] = '1';
						}else{
							$petData['gender'] = '1';
						}
						$petData['age'] = $amonths;
						$petData['age_year'] = $ayears;
						$petData['other_breed'] = '';
						$is_from_modal = 0;
						$petData['created_by'] = '1';
						$petData['created_at'] = date("Y-m-d H:i:s");
						$this->PetsModel->add_edit($petData);
						$x++;
					}
				}
			}
			$i++;
		}
		$this->session->set_flashdata('success',$x.' Pets Import.');
		redirect('UsersDetails/importPets');
	}

	function exportPracticeNotAccountRef(){
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Account Ref');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Practice Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Phone Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Postcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Country');

        $this->db->select('u.id, u.name, u.email, u.country, u.phone_number');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$email		= !empty($row['email']) ? $row['email'] : '';
			$country	= !empty($row['country']) ? $row['country'] : '';
			/* Get country ID */
			if($country != "" && $country != "NULL"){
				$this->db->select('name');
				$this->db->from('ci_staff_countries');
				$this->db->where('id', $country);
				$res2 = $this->db->get();
				if($res2->num_rows() > 0){
					$countryName = $res2->row()->name;
				}else{
					$countryName = '';
				}
			}else{
				$countryName = '';
			}

			$phoneNumber= !empty($row['phone_number']) ? $row['phone_number'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'address_3', 'account_ref'");
			$practDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
			$practDetails = array_column($practDetails, 'column_field', 'column_name');
			$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
			$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
			
			if($account_ref==""){
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $account_ref);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $first_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $email);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $phoneNumber);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $postcode);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $countryName);

				$rowCount++;
			}
		}
		$fileName = 'Nextmune_Practice_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function exportSpainOrders(){
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Order number');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Order Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Order Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Owner Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Pet Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Batch number Lab');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Practice Lab');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Status Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Client id');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Notes, Custome Suport Comment');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Notes, Lab Comment');

		$this->db->select('id, lab_id, vet_user_id, pet_owner_id, pet_id, order_number, order_date, order_type, product_code_selection, updated_at, lab_order_number, order_can_send_to, comment, practice_lab_comment, serum_type, is_repeat_order, cep_id');
		$this->db->from('ci_orders');
		$this->db->where('ci_orders.is_confirmed', '1');
		$this->db->where('ci_orders.is_draft', '0');
		$this->db->where('ci_orders.send_Exact', '0');
		$datas = $this->db->get()->result_array();

		$rowCount = 2;
		foreach($datas as $data_detail){
			if($data_detail['lab_id'] > 0){
				$sqluk = "SELECT name, managed_by_id FROM `ci_users` WHERE id = '". $data_detail['lab_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				$practiceLab = $resultuk->name;
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}else{
				$sqluk = "SELECT name, managed_by_id FROM `ci_users` WHERE id = '". $data_detail['vet_user_id'] ."'";
				$responuk = $this->db->query($sqluk);
				$resultuk = $responuk->row();
				$practiceLab = $resultuk->name;
				if(isset($resultuk->managed_by_id) && !empty($resultuk->managed_by_id)){
					$zoneby = explode(",",$resultuk->managed_by_id);
				}else{
					$zoneby = array();
				}
			}

			if(!empty($zoneby) && in_array("8", $zoneby)){
				$this->db->select('type');
				$this->db->from('ci_pets');
				$this->db->where('id',$data_detail['pet_id']);
				$petuery = $this->db->get()->row();

				$this->db->select('name as pet_owner_name,last_name as pet_owner_lname');
				$this->db->from('ci_users');
				$this->db->where('id',$data_detail['pet_owner_id']);
				$wusruery = $this->db->get()->row();
				$petOwner = '';
				if($wusruery->pet_owner_name == NULL && $wusruery->pet_owner_lname == NULL){
					$petOwner = '';
				}else{
					if($wusruery->pet_owner_name == NULL || preg_replace('/\s+/', '', $wusruery->pet_owner_name) == ""){
						$petOwner = preg_replace('/\s+/', '', $wusruery->pet_owner_lname);
					}else{
						$petOwner = preg_replace('/\s+/', '', $wusruery->pet_owner_name) .' '. preg_replace('/\s+/', '', $wusruery->pet_owner_lname);
					}
				}

				$this->db->select('name as pet_name');
				$this->db->from('ci_pets');
				$this->db->where('id',$data_detail['pet_id']);
				$petuery = $this->db->get()->row();

				$account_ref = ''; $order_send_to = ''; $results_to_practice = 0; $invoice_to_practice = 0; 
				if($data_detail['lab_id'] > 0){
					$userData1 = array("user_id" => $data_detail['lab_id'], "column_name" => "'account_ref', 'vat_applicable', 'address_1', 'address_2', 'address_3', 'post_code', 'town_city', 'results_to_practice', 'invoice_to_practice'");
					$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
					$address_1 = !empty($refDetails['address_1']) ? $refDetails['address_1'] : '';
					$address_2 = !empty($refDetails['address_2']) ? $refDetails['address_2'] : '';
					$address_3 = !empty($refDetails['address_3']) ? $refDetails['address_3'] : '';
					$town = !empty($refDetails['town_city']) ? $refDetails['town_city'] : '';
					$postcode = !empty($refDetails['post_code']) ? $refDetails['post_code'] : '';
					$results_to_practice = !empty($refDetails['results_to_practice']) ? $refDetails['results_to_practice'] : '0';
					$invoice_to_practice = !empty($refDetails['invoice_to_practice']) ? $refDetails['invoice_to_practice'] : '0';
					$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
					$sendId = $data_detail['lab_id'];
				}else{
					$userData2 = array("user_id" => $data_detail['vet_user_id'], "column_name" => "'account_ref', 'vat_applicable', 'add_1', 'add_2', 'add_3', 'add_4', 'address_3'");
					$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
					$address_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'] : '';
					$address_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'] : '';
					$address_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
					$town = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
					$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
					$order_send_to = $address_1." ".$address_2." ".$address_3." ".$town." ".$postcode;
					$sendId = $data_detail['vet_user_id'];
				}

				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $data_detail['order_number']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, date('d/m/Y', strtotime($data_detail['order_date'])));
				if($data_detail['order_type'] == '1'){
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Immunotherpathy');
				}elseif($data_detail['order_type'] == '2'){
					$this->db->select('name');
					$this->db->from('ci_price');
					$this->db->where('id', $data_detail['product_code_selection']);
					$respnedn = $this->db->get()->row();
					$articlecode = "";
					if($data_detail['is_repeat_order'] == 1 && $data_detail['cep_id'] > 0){
						if($respnedn->name == 'PAX Environmental'){
							$articlecode = 'PAX4';
						}elseif($respnedn->name == 'PAX Food'){
							$articlecode = 'PAX5';
						}elseif($respnedn->name == 'PAX Environmental + Food'){
							$articlecode = 'PAX6';
						}
					}else{
						if($respnedn->name == 'PAX Environmental Screening'){
							$articlecode = 'PAX1';
						}elseif($respnedn->name == 'PAX Food Screening'){
							$articlecode = 'PAX2';
						}elseif($respnedn->name == 'PAX Environmental + Food Screening'){
							$articlecode = 'PAX3';
						}elseif($respnedn->name == 'PAX Environmental after screening'){
							$articlecode = 'PAX4';
						}elseif($respnedn->name == 'PAX Food after screening'){
							$articlecode = 'PAX5';
						}elseif($respnedn->name == 'PAX Environmental + Food after screening'){
							$articlecode = 'PAX6';
						}elseif($respnedn->name == 'PAX Environmental'){
							$articlecode = 'PAX7';
						}elseif($respnedn->name == 'PAX Food'){
							$articlecode = 'PAX8';
						}elseif($respnedn->name == 'PAX Environmental + Food'){
							$articlecode = 'PAX9';
						}
					}
					if($data_detail['serum_type'] == '1'){
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $articlecode.'E');
					}else{
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $respnedn->name);
					}
				}elseif($data_detail['order_type'] == '3'){
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Skin Test');
				}
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $petOwner);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $petuery->pet_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $data_detail['lab_order_number']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $practiceLab);

				$this->db->select('created_at');
				$this->db->from('ci_order_history');
				$this->db->where('order_id', $data_detail['id']);
				$this->db->order_by("created_at", "DESC");
				$this->db->limit(1, 0);
				$orderHistory = $this->db->get()->row();
				if(!empty($orderHistory)){
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, date('d/m/Y H:i:s', strtotime($orderHistory->created_at)));
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, date('d/m/Y H:i:s', strtotime($data_detail['updated_at'])));
				}
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $account_ref);
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $data_detail['comment']);
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $data_detail['practice_lab_comment']);

				$rowCount++;
			}
		}
		$fileName = 'Nextmune_Spain_Orders_Live.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function exportAllergens(){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PAX Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Name EN');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Danish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'French Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'German Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Italian Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Dutch Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Norwegian Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Spanish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Swedish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PAX Name EN');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Danish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'French PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'German PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Italian PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Dutch PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Norwegian PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Spanish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Swedish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'PAX Latin Name');

		$sqls = "SELECT id,parent_id,pax_parent_id,name,name_danish,name_french,name_german,name_italian,name_dutch,name_norwegian, name_spanish,name_swedish,pax_name,pax_name_danish,pax_name_french,pax_name_german,pax_name_italian,pax_name_dutch, pax_name_norwegian,pax_name_spanish,pax_name_swedish,pax_latin_name FROM `ci_allergens` WHERE `parent_id` != 0";
        $responce = $this->db->query($sqls);
		$results = $responce->result_array();
		$rowCount = 2; 
		foreach($results as $row){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['parent_id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pax_parent_id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['name_danish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['name_french']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['name_german']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['name_italian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['name_dutch']);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['name_norwegian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['name_spanish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['name_swedish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['pax_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['pax_name_danish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['pax_name_french']);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $row['pax_name_german']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $row['pax_name_italian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $row['pax_name_dutch']);
			$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $row['pax_name_norwegian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $row['pax_name_spanish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $row['pax_name_swedish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $row['pax_latin_name']);

			$rowCount++;
		}
		$fileName = 'Live_Nextvu_Allergens_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
    }

	function exportAllergensGroups(){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PAX Parent ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Name EN');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Danish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'French Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'German Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Italian Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Dutch Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Norwegian Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Spanish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Swedish Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PAX Name EN');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Danish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'French PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'German PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Italian PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Dutch PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Norwegian PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Spanish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Swedish PAX Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'PAX Latin Name');

		$sqls = "SELECT id,parent_id,pax_parent_id,name,name_danish,name_french,name_german,name_italian,name_dutch,name_norwegian, name_spanish,name_swedish,pax_name,pax_name_danish,pax_name_french,pax_name_german,pax_name_italian,pax_name_dutch, pax_name_norwegian,pax_name_spanish,pax_name_swedish,pax_latin_name FROM `ci_allergens` WHERE `parent_id` = 0";
        $responce = $this->db->query($sqls);
		$results = $responce->result_array();
		$rowCount = 2; 
		foreach($results as $row){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['parent_id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['pax_parent_id']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['name_danish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['name_french']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['name_german']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['name_italian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['name_dutch']);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['name_norwegian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['name_spanish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['name_swedish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['pax_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['pax_name_danish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['pax_name_french']);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $row['pax_name_german']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $row['pax_name_italian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $row['pax_name_dutch']);
			$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $row['pax_name_norwegian']);
			$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $row['pax_name_spanish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $row['pax_name_swedish']);
			$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $row['pax_latin_name']);

			$rowCount++;
		}
		$fileName = 'Live_Nextvu_Allergens_Groups.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
    }

	function importRaptorHeaders(){
		echo 'D'; exit;
		ini_set('memory_limit', '256M');
		error_reporting(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/Sweden_Nextvu_SE_Allergens_Header.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $y=0; $headers = $headerd = []; $postData = []; $post2Data = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$allergensID = !empty($value['A'])?$value['A']:'';
				$allergensName = !empty($value['D'])?$value['D']:'';
				$paxName = !empty($value['E'])?$value['E']:'';
				$raptorCode = !empty($value['F'])?$value['F']:'';
				$raptorFunction = !empty($value['G'])?$value['G']:'';
				$emAllergen = !empty($value['H'])?$value['H']:'';
				$header1 = !empty($value['I'])?$value['I']:'';
				$header2 = !empty($value['J'])?$value['J']:'';
				$header3 = !empty($value['K'])?$value['K']:'';
				$header4 = !empty($value['L'])?$value['L']:'';
				if($raptorCode != '' && $emAllergen != ''){
					$this->db->select('id');
					$this->db->from('ci_allergens_raptor');
					$this->db->where('allergens_id', $allergensID);
					$this->db->where('raptor_code', $raptorCode);
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$raptorID = $res2->row()->id;
						if($header1 != "" && $header2 != "" && $header3 != "" && $header4 != ""){
							$headers = array('0'=> $header1,'1'=> $header2,'2'=> $header3,'3'=> $header4);
						}elseif($header1 != "" && $header2 != "" && $header3 != ""){
							$headers = array('0'=> $header1,'1'=> $header2,'2'=> $header3);
						}elseif($header1 != "" && $header2 != ""){
							$headers = array('0'=> $header1,'1'=> $header2);
						}elseif($header1 != ""){
							$headers = array('0'=> $header1);
						}elseif($header1 == ""){
							$headers = array();
						}

						if(!empty($headers)){
							//$postData['raptor_header_dutch'] = json_encode($headers);
							$postData['raptor_header_swedish'] = json_encode($headers);
							//$postData['raptor_header_spanish'] = json_encode($headers);
							//$postData['raptor_header_norwegian'] = json_encode($headers);
							//$postData['raptor_header_italian'] = json_encode($headers);
							$this->db->where('id', $raptorID);
							$this->db->update('ci_allergens_raptor',$postData);
							$x++;
						}
					}
				}

				if($raptorCode == '' && $emAllergen == 'Allergen Description'){
					$this->db->select('id');
					$this->db->from('ci_allergens_raptor');
					$this->db->where('allergens_id', $allergensID);
					$this->db->where('em_allergen', '1');
					$res3 = $this->db->get();
					if($res3->num_rows() > 0){
						$raptors2ID = $res3->row()->id;
						if($header1 != "" && $header2 != "" && $header3 != "" && $header4 != ""){
							$headerd = array('0'=> $header1,'1'=> $header2,'2'=> $header3,'3'=> $header4);
						}elseif($header1 != "" && $header2 != "" && $header3 != ""){
							$headerd = array('0'=> $header1,'1'=> $header2,'2'=> $header3);
						}elseif($header1 != "" && $header2 != ""){
							$headerd = array('0'=> $header1,'1'=> $header2);
						}elseif($header1 != ""){
							$headerd = array('0'=> $header1);
						}elseif($header1 == ""){
							$headerd = array();
						}

						if(!empty($headerd)){
							//$post2Data['raptor_header_dutch'] = json_encode($headerd);
							$post2Data['raptor_header_swedish'] = json_encode($headerd);
							//$post2Data['raptor_header_spanish'] = json_encode($headerd);
							//$post2Data['raptor_header_norwegian'] = json_encode($headerd);
							//$post2Data['raptor_header_italian'] = json_encode($headerd);
							$this->db->where('id', $raptors2ID);
							$this->db->update('ci_allergens_raptor',$post2Data);
							$y++;
						}
					}
				}
			}
			$i++;
		}
		echo $x.' Exist & '.$y.' Added';
		exit;
	}

	function importUpdateAllergens(){
		echo 'D'; exit;
		ini_set('memory_limit', '256M');
		error_reporting(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/Sweden_Nextvu_SE_Allergens.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $y=0; $headers = []; $postData = []; $paxData = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$allergensID = !empty($value['A'])?$value['A']:'';
				$parentID = !empty($value['B'])?$value['B']:'';
				$paxparentID = !empty($value['C'])?$value['C']:'';
				$allergensName = !empty($value['D'])?$value['D']:'';
				$paxName = !empty($value['E'])?$value['E']:'';
				if($allergensName != ''){
					$this->db->select('id');
					$this->db->from('ci_allergens');
					$this->db->where('id', $allergensID);
					$this->db->where('parent_id', $parentID);
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$raptorID = $res2->row()->id;
						$postData['name_swedish'] = $allergensName;
						$this->db->where('id', $raptorID);
						$this->db->update('ci_allergens',$postData);
						$x++;
					}
				}

				if($paxName != ''){
					$this->db->select('id');
					$this->db->from('ci_allergens');
					$this->db->where('id', $allergensID);
					$this->db->where('pax_parent_id', $paxparentID);
					$res3 = $this->db->get();
					if($res3->num_rows() > 0){
						$paxraptorsID = $res3->row()->id;
						$paxData['pax_name_swedish'] = $paxName;
						$this->db->where('id', $paxraptorsID);
						$this->db->update('ci_allergens',$paxData);
						$y++;
					}
				}
			}
			$i++;
		}
		echo $x.' Name & '.$y.' PAX Name Updated.';
		exit;
	}

	function updatePracticesTM(){
		ini_set('memory_limit', '256M');
        $this->db->select('id');
		$this->db->from('ci_users');
		$this->db->where('role', '2');
		$this->db->order_by('id', 'ASC');
		$datas = $this->db->get()->result_array();
		$totalupdated = 0; $postData = []; $newpracticeIDs = [];
		foreach($datas as $row){
			$this->db->select('column_field');
			$this->db->from('ci_user_details');
			$this->db->where('column_name LIKE', 'tm_user_id');
			$this->db->where('user_id', $row['id']);
			$res1 = $this->db->get();
			if($res1->num_rows() > 0){
				$existTM = $res1->row();
				if(!empty($existTM) && $existTM->column_field != ""){
					$tmID = json_decode($existTM->column_field);
					$this->db->select('column_field');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'practices');
					$this->db->where('user_id', $tmID[0]);
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$existPR = $res2->row();
						if(!empty($existPR) && $existPR->column_field != "" && $existPR->column_field != "[]" && $existPR->column_field != '[""]'){
							$newpracticeIDs = [];
							foreach(json_decode($existPR->column_field) as $pval){
								if($pval != $row['id']){
									$newpracticeIDs[] = $pval;
								}
							}
							$newpracticeIDs[] = $row['id'];
						}else{
							$newpracticeIDs[] = $row['id'];
						}
						$newtmuserArr = json_encode($newpracticeIDs);
						$this->db->where('column_name','practices');
						$this->db->where('user_id',$tmID[0]);
						$this->db->update("ci_user_details", array("column_field" => $newtmuserArr));
						$totalupdated++;
					}
				}
			}
		}
		echo $totalupdated. 'Practices TM updated.';
		exit;
    }

	function updatePracticesSage200(){
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$inputFileName = FCPATH.'uploaded_files/orderData/Nextmune_Practice_1679670171.xls';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $postUser = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$practiceID = $value['A'];
				if($practiceID != ''){
					$this->db->select('id');
					$this->db->from('ci_users');
					$this->db->where('id', $practiceID);
					$this->db->where('role', '2');
					$res2 = $this->db->get();
					if($res2->num_rows() > 0){
						$invoicedBy = !empty($value['Q']) ? $value['Q'] : '';
						$sageCode = !empty($value['R']) ? $value['R'] : '';
						$corporates = !empty($value['U']) ? $value['U'] : '';
						$buyingGroups = !empty($value['V']) ? $value['V'] : '';

						if($sageCode != ''){
							if($invoicedBy != "" && $invoicedBy == 'Nextmune UK'){
								$postUser['phone_number'] = !empty($value['E']) ? $value['E'] : '';
								$postUser['invoiced_by'] = '1';
								$postUser['updated_at'] = date("Y-m-d H:i:s");
								$postUser['updated_by'] = $this->user_id;
								$this->db->where('id', $practiceID);
								$this->db->update('ci_users', $postUser);
							}

							$this->db->select('column_field');
							$this->db->from('ci_user_details');
							$this->db->where('user_id', $practiceID);
							$this->db->where('column_name', 'uk_sage_code');
							$existing_fields =  $this->db->get()->result_array();
							if(!empty($existing_fields)){
								$this->db->where('column_name','uk_sage_code');
								$this->db->where('user_id',$practiceID);
								$this->db->update("ci_user_details", array("column_field" => $sageCode));
							}else{
								$sqlins = "INSERT INTO `ci_user_details` (`id`, `user_id`, `column_name`, `column_field`, `created_at`, `updated_at`) VALUES (NULL, '".$practiceID."', 'uk_sage_code', '".$sageCode."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."');";
								$this->db->query($sqlins);
							}
							$x++;
						}

						if($corporates != ''){
							$this->db->select('id');
							$this->db->from('ci_users');
							$this->db->where('name LIKE', $corporates);
							$this->db->where('role', '7');
							$res = $this->db->get();
							if($res->num_rows() > 0){
								$existCorp = $res->row()->id;
								$this->db->where('column_name','practices');
								$this->db->where('user_id',$existCorp);
								$this->db->update("ci_user_details", array("column_field" => '["'.$practiceID.'"]'));
							}
						}

						if($buyingGroups != ''){
							$this->db->select('id');
							$this->db->from('ci_users');
							$this->db->where('name LIKE', $buyingGroups);
							$this->db->where('role', '9');
							$res1 = $this->db->get();
							if($res1->num_rows() > 0){
								$existBG = $res1->row()->id;
								$this->db->where('column_name','practices');
								$this->db->where('user_id',$existBG);
								$this->db->update("ci_user_details", array("column_field" => '["'.$practiceID.'"]'));
							}
						}
					}
				}
			}
			$i++;
		}
		echo $x.' Updated.';
		exit;
	}

	function updateLabsSage200(){
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/Nextmune_Lab_1679670208.xls';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $x=0; $details = $postUser = $postUserDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$labID = $value['A'];
				if($labID != ''){
					$invoicedBy = !empty($value['P']) ? $value['P'] : '';
					$this->db->select("id");
					$this->db->from('ci_users');
					$this->db->where('id',$labID);
					$this->db->where('role',6);
					$labData = $this->db->get()->row_array();
					if(!empty($labData)){
						$postUser['phone_number'] = !empty($value['E']) ? $value['E'] : '';
						if($invoicedBy != "" && $invoicedBy == 'Nextmune UK'){
							$postUser['invoiced_by'] = '1';
						}
						$postUser['updated_at'] = date("Y-m-d H:i:s");
						$postUser['updated_by'] = $this->user_id;
						$this->db->where('id', $labID);
						$this->db->update('ci_users', $postUser);

						$uk_sage_code = !empty($value['Q']) ? $value['Q'] : NULL;

						$this->db->select('column_field');
						$this->db->from('ci_user_details');
						$this->db->where('user_id', $labID);
						$this->db->where('column_name', 'uk_sage_code');
						$existing_fields =  $this->db->get()->result_array();
						if(!empty($existing_fields)){
							$this->db->where('column_name','uk_sage_code');
							$this->db->where('user_id',$labID);
							$this->db->update("ci_user_details", array("column_field" => $uk_sage_code));
						}else{
							$sqlins = "INSERT INTO `ci_user_details` (`id`, `user_id`, `column_name`, `column_field`, `created_at`, `updated_at`) VALUES (NULL, '".$labID."', 'uk_sage_code', '".$uk_sage_code."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."');";
							$this->db->query($sqlins);
						}
						$x++;
					}
				}
			}
			$i++;
		}
		echo $x.' Updated.';
		exit;
	}

	function updateLabsData(){
		echo 'D'; exit;
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/SpainLabs.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $y=0; $z=0; $postUser = []; $postUserDetails = []; $branchDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$account_ref = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$practiceEmail = !empty($value['C'])?$value['C']:'';
				$phone_number = !empty($value['D'])?$value['D']:'';
				$add_1 = !empty($value['E'])?$value['E']:'';
				$city = !empty($value['F'])?$value['F']:'';
				$country = !empty($value['G'])?$value['G']:'';
				$postcode = !empty($value['H'])?$value['H']:'';
				$monthly_invoice = !empty($value['I'])?$value['I']:'';
				$sage_account = !empty($value['J'])?$value['J']:'';
				$corporates = !empty($value['K'])?$value['K']:'';
				$comments = !empty($value['M'])?$value['M']:'';
				$vat_applicable = '0';
				$managed_by_id = '8';
				$invoiced_by = '8';
				$discount_serum = !empty($value['N'])?$value['N']:'';
				$discount_artuvin = !empty($value['O'])?$value['O']:'';
				$discount_inmunotek = !empty($value['P'])?$value['P']:'';
				if($country != ""){
					$this->db->select('id');
					$this->db->from('ci_staff_countries');
					$this->db->where('name LIKE', $country);
					$res4 = $this->db->get();
					if($res4->num_rows() > 0){
						$country_id = $res4->row()->id;
					}else{
						$country_id = '9';
					}
				}else{
					$country_id = '9';
				}
				$corporat_id = '';
				if($corporates != ""){
					$this->db->select('id');
					$this->db->from('ci_users');
					$this->db->where('name LIKE', $corporates);
					$res5 = $this->db->get();
					if($res5->num_rows() > 0){
						$corporat_id = $res5->row()->id;
					}
				}

				if($practiceName != '' && $account_ref != ''){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $account_ref);
					$res2 = $this->db->get();
					if($res2->num_rows() == 0){
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 6;
						$postUser['country'] = $country_id;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['invoiced_by'] = $invoiced_by;
						$postUser['preferred_language'] = 'spanish';
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$postUser['created_by'] = $this->user_id;

						$postUserDetails['id'] = '';
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['address_1'] = $add_1;
						$postUserDetails['address_2'] = '';
						$postUserDetails['address_3'] = '';
						$postUserDetails['address_4'] = '';
						$postUserDetails['add_1'] = '';
						$postUserDetails['add_2'] = '';
						$postUserDetails['add_3'] = '';
						$postUserDetails['add_4'] = '';
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['comment'] = $comments;
						if($corporat_id != "" && $corporat_id > 0){
							$postUserDetails['corporates'] = '["'.$corporat_id.'"]';
						}else{
							$postUserDetails['corporates'] = NULL;
						}
						$postUserDetails['country_code'] = NULL;
						$postUserDetails['deliver_to_practice'] = '0';
						$postUserDetails['invoice_to_practice'] = '0';
						$postUserDetails['invoice_to_practice_immu'] = '0';
						$postUserDetails['labs'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['post_code'] = $postcode;
						$postUserDetails['practices'] = NULL;
						$postUserDetails['rcds_number'] = '';
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['results_to_practice'] = '0';
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = '';
						$postUserDetails['town_city'] = $city;
						$postUserDetails['vat_applicable'] = $vat_applicable;
						$postUserDetails['sage_account'] = $sage_account;
						$postUserDetails['labsuite_entidad_code'] = $account_ref;
						$postUserDetails['intercompany'] = '';
						$postUserDetails['monthly_invoice'] = $monthly_invoice;
						$postUserDetails['discount_testing'] = $discount_serum;
						$postUserDetails['discount_artuvetrin'] = $discount_artuvin;
						$postUserDetails['discount_inmunotek'] = $discount_inmunotek;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails);
						$y++;
					}else{
						$existLabUser = $res2->row()->user_id;
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 6;
						$postUser['country'] = $country;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['invoiced_by'] = $invoiced_by;
						$postUser['preferred_language'] = 'spanish';
						$postUser['updated_at'] = date("Y-m-d H:i:s");
						$postUser['updated_by'] = $this->user_id;

						$postUserDetails['id'] = $existLabUser;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['address_1'] = $add_1;
						$postUserDetails['address_2'] = '';
						$postUserDetails['address_3'] = '';
						$postUserDetails['address_4'] = '';
						$postUserDetails['add_1'] = '';
						$postUserDetails['add_2'] = '';
						$postUserDetails['add_3'] = '';
						$postUserDetails['add_4'] = '';
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['comment'] = $comments;
						if($corporat_id != "" && $corporat_id > 0){
							$postUserDetails['corporates'] = '["'.$corporat_id.'"]';
						}else{
							$postUserDetails['corporates'] = NULL;
						}
						$postUserDetails['country_code'] = NULL;
						$postUserDetails['labs'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['post_code'] = $postcode;
						$postUserDetails['practices'] = NULL;
						$postUserDetails['rcds_number'] = '';
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = '';
						$postUserDetails['town_city'] = $city;
						$postUserDetails['vat_applicable'] = $vat_applicable;
						$postUserDetails['sage_account'] = $sage_account;
						$postUserDetails['labsuite_entidad_code'] = $account_ref;
						$postUserDetails['monthly_invoice'] = $monthly_invoice;
						$postUserDetails['discount_testing'] = $discount_serum;
						$postUserDetails['discount_artuvetrin'] = $discount_artuvin;
						$postUserDetails['discount_inmunotek'] = $discount_inmunotek;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails);
						$z++;
					}
				}
			}
			$i++;
		}
		echo $y.' Lab Added & '.$z.' Lab Updated';
		exit;
	}

	function updatePracticesData(){
		echo 'D'; exit;
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/SpainPractices4.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $w=0; $x=0; $y=0; $z=0; $postUser = []; $postUserDetails = []; $branchDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$account_ref = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$practiceEmail = !empty($value['C'])?$value['C']:'';
				$phone_number = !empty($value['D'])?$value['D']:'';
				$add_1 = !empty($value['E'])?$value['E']:'';
				$city = !empty($value['F'])?$value['F']:'';
				$country = !empty($value['G'])?$value['G']:'';
				$postcode = !empty($value['H'])?$value['H']:'';
				$monthly_invoice = !empty($value['I'])?$value['I']:'';
				$sage_account = !empty($value['J'])?$value['J']:'';
				$corporates = !empty($value['K'])?$value['K']:'';
				$comments = !empty($value['M'])?$value['M']:'';
				$vat_applicable = '0';
				$managed_by_id = '8';
				$invoiced_by = '8';
				$discount_serum = !empty($value['N'])?$value['N']:'';
				$discount_artuvin = !empty($value['O'])?$value['O']:'';
				$discount_inmunotek = !empty($value['P'])?$value['P']:'';
				if($country != ""){
					$this->db->select('id');
					$this->db->from('ci_staff_countries');
					$this->db->where('name LIKE', $country);
					$res4 = $this->db->get();
					if($res4->num_rows() > 0){
						$country_id = $res4->row()->id;
					}else{
						$country_id = '9';
					}
				}else{
					$country_id = '9';
				}
				$corporat_id = '';
				if($corporates != ""){
					$this->db->select('id');
					$this->db->from('ci_users');
					$this->db->where('name LIKE', $corporates);
					$res5 = $this->db->get();
					if($res5->num_rows() > 0){
						$corporat_id = $res5->row()->id;
					}
				}

				if($practiceName != '' && $account_ref != ''){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $account_ref);
					$res1 = $this->db->get();
					if($res1->num_rows() == 0){
						$postUser['name'] = $practiceName;
						$postUser['last_name'] = '';
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['role'] = 2;
						$postUser['country'] = $country_id;
						$postUser['managed_by_id'] = $managed_by_id;
						$postUser['invoiced_by'] = $invoiced_by;
						$postUser['preferred_language'] = 'spanish';
						$postUser['created_at'] = date("Y-m-d H:i:s");
						$postUser['created_by'] = $this->user_id;

						$postUserDetails['id'] = '';
						$postUserDetails['address_1'] = '';
						$postUserDetails['address_2'] = '';
						$postUserDetails['address_3'] = $postcode;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['tax_code'] = '';
						$postUserDetails['vat_reg'] = '';
						$postUserDetails['country_code'] = '';
						$postUserDetails['comment'] = $comments;
						if($corporat_id != "" && $corporat_id > 0){
							$postUserDetails['corporates'] = '["'.$corporat_id.'"]';
						}else{
							$postUserDetails['corporates'] = NULL;
						}
						$postUserDetails['labs'] = NULL;
						$postUserDetails['referrals'] = NULL;
						$postUserDetails['rcds_number'] = '';
						$postUserDetails['add_1'] = $add_1;
						$postUserDetails['add_2'] = '';
						$postUserDetails['add_3'] = '';
						$postUserDetails['add_4'] = $city;
						$postUserDetails['order_can_send_to'] = NULL;
						$postUserDetails['odelivery_address'] = NULL;
						$postUserDetails['opostal_code'] = NULL;
						$postUserDetails['ocity'] = NULL;
						$postUserDetails['ocountry'] = NULL;
						$postUserDetails['buying_groups'] = NULL;
						$postUserDetails['vat_applicable'] = $vat_applicable;
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
						$postUserDetails['sage_account'] = $sage_account;
						$postUserDetails['labsuite_entidad_code'] = $account_ref;
						$postUserDetails['intercompany'] = '';
						$postUserDetails['monthly_invoice'] = $monthly_invoice;
						$postUserDetails['discount_testing'] = $discount_serum;
						$postUserDetails['discount_artuvetrin'] = $discount_artuvin;
						$postUserDetails['discount_inmunotek'] = $discount_inmunotek;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails);
						$x++;
					}else{
						$existPracUser = $res1->row()->user_id;
						$postUser['name'] = $practiceName;
						$postUser['email'] = $practiceEmail;
						$postUser['phone_number'] = $phone_number;
						$postUser['updated_at'] = date("Y-m-d H:i:s");
						$postUser['updated_by'] = $this->user_id;

						$postUserDetails['id'] = $existPracUser;
						$postUserDetails['address_3'] = $postcode;
						$postUserDetails['account_ref'] = $account_ref;
						$postUserDetails['add_1'] = $add_1;
						$postUserDetails['add_4'] = $city;
						$postUserDetails['comment'] = $comments;
						if($corporat_id != "" && $corporat_id > 0){
							$postUserDetails['corporates'] = '["'.$corporat_id.'"]';
						}else{
							$postUserDetails['corporates'] = NULL;
						}
						$postUserDetails['vat_applicable'] = $vat_applicable;
						$postUserDetails['sage_account'] = $sage_account;
						$postUserDetails['labsuite_entidad_code'] = $account_ref;
						$postUserDetails['monthly_invoice'] = $monthly_invoice;
						$postUserDetails['discount_testing'] = $discount_serum;
						$postUserDetails['discount_artuvetrin'] = $discount_artuvin;
						$postUserDetails['discount_inmunotek'] = $discount_inmunotek;

						$this->UsersDetailsModel->add_edit($postUser,$postUserDetails,$branchDetails);
						$w++;
					}
				}
			}
			$i++;
		}
		echo $x.' Practice Added & '.$w.' Practice Updated';
		exit;
	}

	function updateArtuvetrinDiscounts(){
		ini_set('memory_limit', '256M');
		$this->db->select('user_id,column_field');
		$this->db->from('ci_user_details');
		$this->db->where('column_name LIKE', 'discount_artuvetrin');
		$this->db->where('column_field !=', '');
		$this->db->order_by('user_id', 'ASC');
		$datas = $this->db->get()->result_array();
		foreach($datas as $row){
			$sqlins = "INSERT INTO `ci_discount` (`product_id`, `practice_id`, `sage_code`, `uk_discount`) VALUES ('16', '".$row['user_id']."', NULL, '".$row['column_field']."'),('17', '".$row['user_id']."', NULL, '".$row['column_field']."');";
			//$this->db->query($sqlins);
		}
	}

	function updateSerumDiscounts(){
		ini_set('memory_limit', '256M');
		$this->db->select('user_id,column_field');
		$this->db->from('ci_user_details');
		$this->db->where('column_name LIKE', 'discount_testing');
		$this->db->where('column_field !=', '');
		$this->db->order_by('user_id', 'ASC');
		$datas = $this->db->get()->result_array();
		foreach($datas as $row){
			$sqlins = "INSERT INTO `ci_discount` (`product_id`, `practice_id`, `sage_code`, `uk_discount`) VALUES ('33', '".$row['user_id']."', NULL, '".$row['column_field']."'),('34', '".$row['user_id']."', NULL, '".$row['column_field']."'),('38', '".$row['user_id']."', NULL, '".$row['column_field']."');";
			//$this->db->query($sqlins);
		}
	}

	function getPracticesData(){
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$inputFileName = FCPATH.'uploaded_files/orderData/SpainPractices2.xlsx';
		$objPHPExcel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$i=1; $w=0; $x=0; $y=0; $z=0; $postUser = []; $postUserDetails = []; $branchDetails = [];
		foreach ($allDataInSheet as $value) {
			if($i!=1){
				$account_ref = !empty($value['A'])?$value['A']:'';
				$practiceName = !empty($value['B'])?$value['B']:'';
				$managed_by_id = '8';
				$invoiced_by = '8';
				if($practiceName != '' && $account_ref != ''){
					$this->db->select('user_id');
					$this->db->from('ci_user_details');
					$this->db->where('column_name LIKE', 'account_ref');
					$this->db->where('column_field LIKE', $account_ref);
					$res1 = $this->db->get();
					if($res1->num_rows() > 0){
						$branchDetails[] = $res1->row()->user_id;
					}
				}
			}
			$i++;
		}
		echo implode(",",$branchDetails);
		//echo $x.' Practice Added & '.$w.' Practice Updated';
		exit;
	}

	function exportDuplicatePracticesReport(){
		ini_set('memory_limit', '256M');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice ID');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Account Ref');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Practice Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Phone Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Address 1');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Address 2');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Address 3');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Town/City');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'County');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Country');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Postcode');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'VAT Applicable?');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'RCVS Number');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Preferred Language');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Managed By');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Invoiced By');

        $this->db->select('COUNT(id) as Total, name');
		$this->db->from('ci_users');
		$this->db->where('role', '2');
		$this->db->where('CONCAT(",", managed_by_id, ",") REGEXP ",(8),"');
		$this->db->group_by('name');
		$this->db->order_by('Total', 'ASC');
		$dupData = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($dupData as $drow){
			if($drow['Total'] > 1){
				$this->db->select('u.id, u.name, u.email, u.country, u.phone_number, u.managed_by_id, u.invoiced_by, u.preferred_language');
				$this->db->from('ci_users as u');
				$this->db->where('u.name LIKE', $drow['name']);
				$this->db->where('u.role', '2');
				$this->db->order_by('u.id', 'ASC');
				$datas = $this->db->get()->result_array();
				foreach($datas as $row){
					$first_name	= !empty($row['name']) ? $row['name'] : '';
					$email		= !empty($row['email']) ? $row['email'] : '';
					$country	= !empty($row['country']) ? $row['country'] : '';
					$preferredLanguage	= !empty($row['preferred_language']) ? ucfirst($row['preferred_language']) : '';
					$countryName	= $this->getCountryName($country);
					if(!empty($row['managed_by_id']) && $row['managed_by_id'] != 0){
						$managed_by = $this->getManagedbyName($row['managed_by_id']);
					}else{
						$managed_by = '';
					}
					if(!empty($row['invoiced_by']) && $row['invoiced_by'] != 0){
						$invoiced_by = $this->getManagedbyName($row['invoiced_by']);
					}else{
						$invoiced_by = '';
					}
					$phoneNumber= !empty($row['phone_number']) ? $row['phone_number'] : '';
					$userData = array("user_id" => $row['id'], "column_name" => "'add_1', 'add_2', 'add_3', 'add_4', 'address_2', 'address_3', 'vat_applicable', 'account_ref', 'tax_code', 'vat_reg', 'country_code', 'rcds_number'");
					$practDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
					$practDetails = array_column($practDetails, 'column_field', 'column_name');
					$address_1	= !empty($practDetails['add_1']) ? $practDetails['add_1'] : '';
					$address_2	= !empty($practDetails['add_2']) ? $practDetails['add_2'] : '';
					$address_3	= !empty($practDetails['add_3']) ? $practDetails['add_3'] : '';
					$address_4	= !empty($practDetails['add_4']) ? $practDetails['add_4'] : '';
					$town_city	= !empty($practDetails['address_2']) ? $practDetails['address_2'] : '';
					$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
					$vatApplicable	= !empty($practDetails['vat_applicable']) ? $practDetails['vat_applicable'] : '';
					$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
					$rcds_number= !empty($practDetails['rcds_number']) ? $practDetails['rcds_number'] : '';

					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id']);
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $account_ref);
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $first_name);
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $email);
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $phoneNumber);
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $address_1);
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $address_2);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $address_3);
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $address_4);
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $town_city);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $countryName);
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $postcode);
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $vatApplicable);
					$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $rcds_number);
					$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $preferredLanguage);
					$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $managed_by);
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $invoiced_by);
					$rowCount++;
				}
			}
		}
		$fileName = 'Duplicate_Practices_Nextmune_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }
	
	function getCountryName($id){
		$this->db->select('name');
		$this->db->from('ci_staff_countries');
		$this->db->where('id', $id);
		$datas = $this->db->get()->row_array();
		return $datas['name'];
	}

	function getManagedbyName($ids){
		$this->db->select('GROUP_CONCAT(managed_by_name) as managedby_name');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.$ids.')');
		$datas = $this->db->get()->row_array();
		return $datas['managedby_name'];
	}

}
?>