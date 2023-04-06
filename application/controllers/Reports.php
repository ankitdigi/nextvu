<?php
if(!defined('BASEPATH')) exit('No direct script access allowed'); 
class Reports extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('excel');
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
        }
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('ReportsModel');
		$this->load->model('OrdersModel');
		$this->load->model('UsersModel');
    }

	function report_practices(){
		$this->load->view('reports/practices');
    }

	function getPracticeTableData(){
		$practiceData = $this->ReportsModel->getPracticeTableData();
		if(!empty($practiceData)){
            foreach ($practiceData as $key => $value) {
				$practiceData[$key]->name = $value->name;
				$refDatas = $this->ReportsModel->getColumnAllArray($value->id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$practiceData[$key]->address_3 = !empty($refDatas['address_3']) ? $refDatas['address_3']:'';
				//$practiceData[$key]->allergens = $this->ReportsModel->getPracticeOrderAllergens($row['id']);
				$practiceData[$key]->total_spent = number_format($this->ReportsModel->getPracticeTotalSpent($value->id),2);
			}
		}

		$total = $this->ReportsModel->count_all();
        $totalFiltered = $this->ReportsModel->count_practices_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $practiceData;
        echo json_encode($ajax); exit();
	}

	function report_labs(){
		$this->load->view('reports/labs');
	}

	function getLabTableData(){
		$labsData = $this->ReportsModel->getLabTableData();
		if(!empty($labsData)){
            foreach ($labsData as $key => $value) {
				$labsData[$key]->name = $value->name;
				$refDatas = $this->ReportsModel->getColumnAllArray($value->id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$labsData[$key]->post_code = !empty($refDatas['post_code']) ? $refDatas['post_code']:'';
				//$labsData[$key]->allergens = $this->ReportsModel->getLabOrderAllergens($row['id']);
				$labsData[$key]->total_spent = number_format($this->ReportsModel->getLabTotalSpent($value->id),2);
			}
		}

		$total = $this->ReportsModel->count_all();
        $totalFiltered = $this->ReportsModel->count_labs_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $labsData;
        echo json_encode($ajax); exit();
	}

	function exportLabReport(){
		error_reporting(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Lab Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Postcode');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Order Type');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Order Sub-Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Spent');

        $this->db->select('u.id, u.name');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '6');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'account_ref','post_code'");
			$labDetails = $this->ReportsModel->getColumnFieldArray($userData);
			$labDetails = array_column($labDetails, 'column_field', 'column_name');
			$account_ref= !empty($labDetails['account_ref']) ? $labDetails['account_ref'] : '';
			$postcode	= !empty($labDetails['post_code']) ? $labDetails['post_code'] : '';
			$totalSpent	= $this->ReportsModel->getLabTotalSpent($row['id']);
			$ordertype	= $this->ReportsModel->getLabOrderType($row['id']);
			//$ordersubtype	= $this->ReportsModel->getLabOrderSubType($row['id']);
			$ordersubtype	= '';

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $ordertype);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $ordersubtype);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $totalSpent);
            $rowCount++;
		}
		$fileName = 'NextVu_Labs_Report_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output'); 
    }

	function exportPracticesReport(){
		error_reporting(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Postcode');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Order Type');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Order Sub-Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Spent');

        $this->db->select('u.id, u.name');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'address_3', 'account_ref'");
			$practDetails = $this->ReportsModel->getColumnFieldArray($userData);
			$practDetails = array_column($practDetails, 'column_field', 'column_name');
			$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
			$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
			$totalSpent = $this->ReportsModel->getPracticeTotalSpent($row['id']);
			$ordertype	= $this->ReportsModel->getPracticeOrderType($row['id']);
			//$ordersubtype	= $this->ReportsModel->getPracticeOrderSubType($row['id']);
			$ordersubtype	= '';

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $ordertype);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $ordersubtype);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $totalSpent);
            $rowCount++;
		}
		$fileName = 'NextVu_Practices_Report_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function exportLabReportAllergens(){
		error_reporting(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Lab Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Postcode');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Ordered Allergens');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total Spent');

        $this->db->select('u.id, u.name');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '6');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'account_ref','post_code'");
			$labDetails = $this->ReportsModel->getColumnFieldArray($userData);
			$labDetails = array_column($labDetails, 'column_field', 'column_name');
			$account_ref= !empty($labDetails['account_ref']) ? $labDetails['account_ref'] : '';
			$postcode	= !empty($labDetails['post_code']) ? $labDetails['post_code'] : '';
			$totalSpent	= $this->ReportsModel->getLabTotalSpent($row['id']);
			$totalAllergens	= $this->ReportsModel->getLabOrderAllergens($row['id']);

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $totalAllergens);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $totalSpent);
            $rowCount++;
		}
		$fileName = 'NextVu_Labs_Report_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output'); 
    }

	function exportPracticesReportAllergens(){
		error_reporting(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Postcode');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Ordered Allergens');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total Spent');

        $this->db->select('u.id, u.name');
		$this->db->from('ci_users as u');
		$this->db->where('u.role', '2');
		$this->db->order_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$userData = array("user_id" => $row['id'], "column_name" => "'address_3', 'account_ref'");
			$practDetails = $this->ReportsModel->getColumnFieldArray($userData);
			$practDetails = array_column($practDetails, 'column_field', 'column_name');
			$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
			$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
			$totalSpent = $this->ReportsModel->getPracticeTotalSpent($row['id']);
			$totalAllergens	= $this->ReportsModel->getPracticeOrderAllergens($row['id']);

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $first_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $postcode);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $totalAllergens);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $totalSpent);
            $rowCount++;
		}
		$fileName = 'NextVu_Practices_Report_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function exportAllOrders($id,$otype,$sdate,$edate){
		error_reporting(E_ALL);
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Product type');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Price invoiced');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Shipping');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Lab number');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Animal Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Surname');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Species');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Breed');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Practice name');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Address');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Country');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Account ref');
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TM User');
        $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Practice or Lab');
        $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Corporate Group');
        $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Buying Group');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Nextmune Zone');
        $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Order number');
        $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Order Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Status');
		$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Status date');
		$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Sage 200 A/C Ref');

		$datas = $this->OrdersModel->getTableDataOrder($id,$otype,$sdate,$edate);

		$rowCount = 2; $vetData = $labData = []; $address	= $postcode	= $account_ref = $tm_user_id  = ''; $panelcunt = 0;
		foreach($datas as $row){
			if ($row["lab_id"] > 0) {
				//get country
				$order_country = '';
				if(!empty($row["lab_country"])){
					$sqlc = "SELECT name as order_country FROM `ci_staff_countries` WHERE id = '".$row["lab_country"]."'";
					$responc = $this->db->query($sqlc);
					$order_country = $responc->row()->order_country;
				}
				//get address
				$labData = array("user_id" => $row['lab_id'], "column_name" => "'address_1','address_2','address_3','town_city', 'post_code', 'account_ref', 'uk_sage_code'");
				$labsDetails = $this->ReportsModel->getColumnFieldArray($labData);
				$labsDetails = array_column($labsDetails, 'column_field', 'column_name');
				$address	= !empty($labsDetails['address_1']) ? $labsDetails['address_1'].' '.$labsDetails['address_2'].' '.$labsDetails['address_3'].' '.$labsDetails['town_city'] : '';
				$postcode	= !empty($labsDetails['post_code']) ? $labsDetails['post_code'] : '';
				$account_ref = !empty($labsDetails['account_ref']) ? $labsDetails['account_ref'] : '';
				$labAccountNum = !empty($labsDetails['uk_sage_code']) ? $labsDetails['uk_sage_code'] : '';
				$tm_user_id  = NULL;
				$practice_name = $row["lab_name"];
			} else{
				//get country
				if(!empty($row["practice_country"])){
					$sqlc = "SELECT name as order_country FROM `ci_staff_countries` WHERE id = '".$row["practice_country"]."'";
					$responc = $this->db->query($sqlc);
					$order_country = $responc->row()->order_country;
				}

				//get address
				$vetData = array("user_id" => $row['vet_user_id'], "column_name" => "'add_1','add_2','add_3','address_2','address_3', 'account_ref', 'tm_user_id', 'uk_sage_code'");
				$practDetails = $this->ReportsModel->getColumnFieldArray($vetData);
				$practDetails = array_column($practDetails, 'column_field', 'column_name');
				$address	= !empty($practDetails['add_1']) ? $practDetails['add_1'].' '.$practDetails['add_2'].' '.$practDetails['add_3'].' '.$practDetails['address_2'] : '';
				$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
				$account_ref = !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
				$tm_user_id  = !empty($practDetails['tm_user_id']) ? $practDetails['tm_user_id'] : NULL;
				$labAccountNum = !empty($practDetails['uk_sage_code']) ? $practDetails['uk_sage_code'] : '';
				$practice_name = $row["practice_first_name"];
			}

			$tm_user = '';
			if($tm_user_id != "" && $tm_user_id != "[]" && $tm_user_id != '[""]' && $tm_user_id != NULL){
				$tmuserArr = json_decode($tm_user_id);
				$this->db->select('name');
				$this->db->from('ci_users');
				$this->db->where('id', $tmuserArr[0]);
				$tminfo = $this->db->get()->row_array();
				if(!empty($tminfo)){
					$tm_user	= !empty($tminfo['name']) ? $tminfo['name'] : '';
				}
			}

			if($row['order_type'] == 1){
				$product_type	= 'Immunotherapy';
			}elseif($row['order_type'] == 3){
				$product_type	= 'Skin Test';
			}else{
				if($row['cep_id'] > 0 && $row['serum_type'] == 1){
					if($row['product_code_selection'] == '34'){
						$product_type = 'PAX Environmental Screening Expanded';
					}elseif($row['product_code_selection'] == '33'){
						$product_type = 'Pax Food Screening Expanded';
					}elseif($row['product_code_selection'] == '38'){
						$product_type = 'PAX Environmental & Food Screening Expanded';
					}else{
						$product_type = !empty($row['product_type']) ? $row['product_type'] : '';
					}
				}elseif($row['cep_id'] > 0 && $row['serum_type'] == 2){
					if($row['species_selection'] == 1){
						if($row['product_code_selection'] == '10'){
							$panelcunt = round($row['unit_price']/45);
							$product_type = 'NEXTLAB SCREEN Environmental Panel Expansion - '.$panelcunt.' Panels';
						}elseif($row['product_code_selection'] == '8'){
							$product_type = 'NEXTLAB SCREEN Food Panel Expansion';
						}elseif($row['product_code_selection'] == '7'){
							$product_type = 'NEXTLAB SCREEN Environmental & Food Panel Expansion';
						}
					}elseif($row['species_selection'] == 2){
						if($row['product_code_selection'] == '18'){
							$panelcunt = round($row['unit_price']/45);
							$product_type = 'NEXTLAB SCREEN Environmental Panel Expansion - '.$panelcunt.' Panels';
						}elseif($row['product_code_selection'] == '20'){
							$product_type = 'NEXTLAB SCREEN Food Panel Expansion';
						}elseif($row['product_code_selection'] == '21'){
							$product_type = 'NEXTLAB SCREEN Environmental & Food Panel Expansion';
						}
					}elseif($row['species_selection'] == 3){
						if($row['product_code_selection'] == '24'){
							$panelcunt = round($row['unit_price']/45);
							$product_type = 'NEXTLAB SCREEN Environmental Panel Expansion - '.$panelcunt.' Panels';
						}elseif($row['product_code_selection'] == '6'){
							$product_type = 'NEXTLAB SCREEN Food Panel Expansion';
						}elseif($row['product_code_selection'] == '23'){
							$product_type = 'NEXTLAB SCREEN Environmental & Food Panel Expansion';
						}
					}else{
						$product_type = !empty($row['product_type']) ? $row['product_type'] : '';
					}
				}else{
					$product_type	= !empty($row['product_type']) ? $row['product_type'] : '';
				}
			}
			$unit_price	= !empty($row['unit_price']) ? $row['unit_price'] : '';
			$shipping_cost	= !empty($row['shipping_cost']) ? $row['shipping_cost'] : '';
			$pet_name	= ''; $breed_name	= '';
			if($row['pet_id'] > 0){
				$this->db->select('name,breed_id,other_breed');
				$this->db->from('ci_pets');
				$this->db->where('id', $row['pet_id']);
				$petinfo = $this->db->get()->row_array();
				if(!empty($petinfo)){
					$pet_name	= !empty($petinfo['name']) ? $petinfo['name'] : '';
					if($petinfo['breed_id'] > 0){
						$this->db->select('name');
						$this->db->from('ci_breeds');
						$this->db->where('id', $petinfo['breed_id']);
						$breedinfo = $this->db->get()->row_array();
						$breed_name	= !empty($breedinfo['name']) ? $breedinfo['name'] : '';
					}else{
						if($petinfo['other_breed']!=""){
							$breed_name	= $petinfo['other_breed'];
						}
					}
				}
			}
			if($row['species_selection'] == 1){
				$species_name = 'Dog';
			}elseif($row['species_selection'] == 2){
				$species_name = 'Horse';
			}elseif($row['species_selection'] == 3){
				$species_name = 'Cat';
			}
			$surname	= '';
			if($row['pet_owner_id'] > 0){
				$this->db->select('name,last_name');
				$this->db->from('ci_users');
				$this->db->where('id', $row['pet_owner_id']);
				$petoinfo = $this->db->get()->row_array();
				if(!empty($petoinfo)){
					$surname	= !empty($petoinfo['last_name']) ? $petoinfo['last_name'] : '';
				}
			}else{
				$surname	= !empty($row['pet_owner_name']) ? $row['pet_owner_name'] : '';
			}
			$practice_or_lab	= !empty($row['lab_id']) ? 'Lab' : 'Practice';
			$lab_order	= !empty($row['lab_order_number']) ? $row['lab_order_number'] : '';

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $product_type);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $unit_price);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $shipping_cost);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $lab_order);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $pet_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $surname);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $species_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $breed_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $practice_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $address.' '.$postcode);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $order_country);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $account_ref);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $tm_user);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $practice_or_lab);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, '');
			if ($row["lab_id"] > 0) {
				if(!empty($row['lab_managed_by']) && $row['lab_managed_by'] != 0){
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $this->getManagedbyName($row['lab_managed_by']));
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, '');
				}
			}else{
				if(!empty($row['practice_managed_by']) && $row['practice_managed_by'] != 0){
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $this->getManagedbyName($row['practice_managed_by']));
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, '');
				}
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $row['order_number']);
			$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, date('d/m/Y', strtotime($row['order_date'])));
			if($row['order_type'] == '2'){
				$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, 'Results Reported');
			}else{
				$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, 'Shipped');
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, date('d/m/Y H:i:s', strtotime($row['created_history'])));
			$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $labAccountNum);
			$rowCount++;
		}
		$fileName = 'Invoice_Data_Export_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

	function getManagedbyName($ids){
		$this->db->select('GROUP_CONCAT(managed_by_name) as managedby_name');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.$ids.')');
		$datas = $this->db->get()->row_array();
		return $datas['managedby_name'];
	}

	function serumTestsExport(){
		$this->load->view('reports/serumTestsExport');
	}

	function getSerumTestsExport(){
		error_reporting(0);
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Animal Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Owner name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Lab number');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Practice name & postcode');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Nominal code');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Sage name');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Test name');

		$this->db->select('ci_orders.id, ci_orders.order_number, ci_orders.lab_order_number, ci_orders.vet_user_id, ci_orders.lab_id, ci_orders.purchase_order_number, ci_orders.product_code_selection, petOwner.name AS pet_owner_name, petOwner.last_name AS pet_owner_lname, ci_pets.name AS pet_name, ci_pets.type, practice.name as practice_name');
		$this->db->from('ci_orders');
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id','left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id','left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
		$this->db->where('ci_orders.is_confirmed', '4');
		$this->db->where('ci_orders.is_draft', '0');
		$this->db->where('ci_orders.order_type', '2');
		$datas = $this->db->get()->result_array();
		$rowCount = 2; $ownerName = $animalName = $labNumber = $postCode = $ordeType = $sageCode = $nominalCode = '';
		foreach($datas as $row){
			$ownerName = '';
			if($row['pet_owner_name'] == NULL && $row['pet_owner_lname'] == NULL){
				$ownerName = '';
			}else{
				if($row['pet_owner_name'] == NULL || preg_replace('/\s+/', '', $row['pet_owner_name']) == ""){
					$ownerName = preg_replace('/\s+/', '', $row['pet_owner_lname']);
				}else{
					$ownerName = preg_replace('/\s+/', '', $row['pet_owner_name']) .' '. preg_replace('/\s+/', '', $row['pet_owner_lname']);
				}
			}
			$animalName = ($row['pet_name']==NULL) ? "" : preg_replace('/\s+/', '', $row['pet_name']);
			$labNumber = !empty($row['lab_order_number'])?$row['lab_order_number']:'';
			$practiceName = !empty($row['practice_name'])?$row['practice_name']:'';

			if($row['lab_id'] > 0){
				$this->db->select("*");
				$this->db->from("ci_user_details");
				$this->db->where("column_name IN('post_code')");
				$this->db->where("user_id", $row['lab_id']);
				$refDetails = $this->db->get()->result_array();
				$refDetails = array_column($refDetails, 'column_field', 'column_name');
				$postCode = !empty($refDetails['post_code']) ? $refDetails['post_code'] : '';
			}else{
				$this->db->select("*");
				$this->db->from('ci_user_details');
				$this->db->where("column_name IN('address_3')");
				$this->db->where('user_id', $row['vet_user_id']);
				$refDetails = $this->db->get()->result_array();
				$refDetails = array_column($refDetails, 'column_field', 'column_name');
				$postCode = !empty($refDetails['address_3']) ? $refDetails['address_3'] : '';
			}

			if(!empty($row['product_code_selection'])){
				$this->db->select('id, name, sage_code, nominal_code');
				$this->db->from('ci_price');
				$this->db->where('id', $row['product_code_selection']);
				$respned = $this->db->get()->row();
				$ordeType = $respned->name;
				$sageCode = $respned->sage_code;
				$nominalCode = $respned->nominal_code;
			}else{
				$ordeType = 'Serum Testing';
				$sageCode = '';
				$nominalCode = '';
			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $animalName);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $ownerName);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $labNumber);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $practiceName.' - '.$postCode);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $nominalCode);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $sageCode);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $ordeType);
            $rowCount++;
		}
		$fileName = 'Nextmune_Serum Tests_Orders_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
	}

	function report_detail_practices(){
		$role = '5';
		$tm_users = $this->UsersModel->getTmUsersTableData($role); 
		$this->db->select('z.id, z.managed_by_name');
		$this->db->from('ci_managed_by_members as z');
		$this->db->order_by('z.id', 'ASC');
		$zones = $this->db->get()->result();
		$this->load->view('reports/practicesDetails',compact('tm_users','zones'));
	}

	function getPracticeDetailTableData(){
		$practiceData = $this->ReportsModel->getPracticeDetailTableData();
		if(!empty($practiceData)){
            foreach ($practiceData as $key => $value) {
            	$refDatas = $this->ReportsModel->getColumnAllArray($value->id);
				$refDatas = array_column($refDatas, 'column_field', 'column_name');
				$practiceData[$key]->name = $value->name;
				$practiceData[$key]->account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref']:'';
				$address1 = !empty($refDatas['address_1'])?$refDatas['address_1'].', ':'';
				$address2 = !empty($refDatas['address_2'])?$refDatas['address_2'].', ':'';
				$practiceData[$key]->address_3 = !empty($refDatas['address_3']) ?$refDatas['address_3']:'';
				$practiceData[$key]->buying_groups = !empty($refDatas['buying_groups']) ?$refDatas['buying_groups']:'';
				if(!empty($refDatas['tm_user_id'])){
					$tmid = json_decode($refDatas['tm_user_id']);
					$udata = $this->UsersModel->getUser(array('id'=>$tmid[0]));
				}
				$practiceData[$key]->tm_user_id = !empty($udata->name) ?$udata->name:'';
				//$practiceData[$key]->allergens = $this->ReportsModel->getPracticeOrderAllergens($row['id']);
				//$practiceData[$key]->total_spent = number_format($this->ReportsModel->getPracticeTotalSpent($value->id),2);
			}
		}

		$total = $this->ReportsModel->count_all();
        $totalFiltered = $this->ReportsModel->count_practices_filtered();
        $ajax["recordsTotal"] = $total;
        $ajax["recordsFiltered"]  = $totalFiltered;
        $ajax['data'] = $practiceData;
        echo json_encode($ajax); exit();
	}

	function exportPracticesDetailReport(){
		error_reporting(0);
		$daterange = $this->input->post('filter_order_date');
		$order_type = $this->input->post('order_type');
		$select_tm_user = $this->input->post('select_tm_user');
		$select_zones = $this->input->post('select_zones');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Practice code');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Practice Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Address');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Name of TM');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'What product purchased');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Quantity ordered');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Value of order shipping separate');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Nextvu Order Number');

        $this->db->select('u.id, u.name,count(ci_orders.vet_user_id) as total_order');
		$this->db->from('ci_users as u');
		$this->db->join('ci_orders', 'ci_orders.vet_user_id = u.id','left');
		$this->db->where('u.role', '2');
		if(!empty($daterange)){
			$request_str = $daterange;
			$filter_order_date = explode(' - ', $request_str);
			$start_date_str = str_replace('/', '-', $filter_order_date[0]);
			$start_date = date('Y-m-d', strtotime($start_date_str));
			$end_date_str = str_replace('/', '-', $filter_order_date[1]);
			$end_date = date('Y-m-d', strtotime($end_date_str));
			$this->db->where('ci_orders.order_date >=', $start_date);
			$this->db->where('ci_orders.order_date <=', $end_date);
		}
		if(!empty($select_zones)){
			$this->db->where_in('u.managed_by_id', $select_zones);
		}
		if(!empty($order_type)){
			$this->db->where_in('ci_orders.order_type', $order_type);
		}
		$this->db->order_by('u.id', 'ASC');
		$this->db->group_by('u.id', 'ASC');
		$datas = $this->db->get()->result_array();

		set_time_limit(500); 
		$rowCount = 2;
		foreach($datas as $row){
			$first_name	= !empty($row['name']) ? $row['name'] : '';
			$total_order	= !empty($row['total_order']) ? $row['total_order'] : '';
			$practDetails = $this->ReportsModel->getColumnAllArray($row['id']);
			$practDetails = array_column($practDetails, 'column_field', 'column_name');
			$address1 = !empty($practDetails['address_1'])?$practDetails['address_1'].', ':'';
			$address2 = !empty($practDetails['address_2'])?$practDetails['address_2'].', ':'';
			$postcode = !empty($practDetails['address_3']) ?$address1.$address2. $practDetails['address_3']:'';
			$postcode	= !empty($practDetails['address_3']) ? $practDetails['address_3'] : '';
			$account_ref= !empty($practDetails['account_ref']) ? $practDetails['account_ref'] : '';
			$tm_cnt = 0;
			if(!empty($practDetails['tm_user_id'])){
				$tmid = json_decode($practDetails['tm_user_id']);
				$udata = $this->UsersModel->getUser(array('id'=>$tmid[0]));
				if(!empty($select_tm_user)){
					if(in_array($tmid[0],$select_tm_user)){
						$tm_cnt = 0;
					}else{
						$tm_cnt = 1;
					}
				}
			}
			if($tm_cnt == 0){
				$tm_user = !empty($udata->name)?$udata->name:'';
				if(!empty($total_order)){
					$PracticeOrderTypeExcel = $this->ReportsModel->getPracticeOrderTypeExcel($row['id'],$order_type);
					$totalSpent = $this->ReportsModel->getPracticeTotalSpent($row['id']);
					$PracticeOrdernumber = $this->ReportsModel->getPracticeOrdernumber($row['id'],$order_type);
				}
				$PracticeOrdernumber = !empty($PracticeOrdernumber)?$PracticeOrdernumber:'';
				$PracticeOrderTypeExcel = !empty($PracticeOrderTypeExcel)?$PracticeOrderTypeExcel:'';
				$totalSpent = !empty($totalSpent)?$totalSpent:'';
				//$ordertype	= $this->ReportsModel->getPracticeOrderType($row['id']);
				//$ordersubtype	= $this->ReportsModel->getPracticeOrderSubType($row['id']);
				//$ordersubtype	= '';

				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $account_ref);
	            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $first_name);
	            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $postcode);
	            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $tm_user);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $PracticeOrderTypeExcel);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $total_order);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $totalSpent);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $PracticeOrdernumber);
	            $rowCount++;
			}
		}
		$fileName = 'NextVu_PracticesDetail_Report_'.time().'.csv';
		header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
		exit;
    }

}
?>