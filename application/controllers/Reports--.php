<?php
if(!defined('BASEPATH')) exit('No direct script access allowed'); 
class Reports extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('excel');
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
        }
		$this->load->model('ReportsModel');
		$this->load->model('OrdersModel');
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

	function limsApiRun(){
		$this->load->view('reports/limsApi');
	}

	function getLIMSResult(){
		ini_set('memory_limit', '256M');
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://185.151.29.200:7070/LiveSample',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'X-API-KEY: Lims@123',
				'Authorization: Basic TGltczoxMjM0'
			),
		));
		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
		}
		curl_close($curl);
		if (isset($error_msg)) {
			$this->session->set_flashdata('error', $error_msg);
		}else{
			$results = json_decode($response,true);
			if(!empty($results)){
				$limsResult = array(); $count = 0;
				foreach($results as $resultL){
					$limsResult = json_encode($resultL,true);
					$this->db->select('id,order_number');
					$this->db->from('ci_orders');
					$this->db->where('lab_order_number LIKE', $resultL['limsId']);
					$this->db->where('is_draft', '0');
					$this->db->where('is_confirmed !=', '3');
					$this->db->where('cep_id', '0');
					//$this->db->where('is_authorised', '0');
					$resord = $this->db->get();
					$resuld = $resord->row();
					if(!empty($resuld)){
						$orderID = $resuld->id;
						$orderNumber = $resuld->order_number;
						$this->db->select('result_id');
						$this->db->from('ci_serum_result');
						/* $this->db->where('nextVuId', $resultL['nextVuId']); */
						$this->db->where('limsId', $resultL['limsId']);
						$res1 = $this->db->get();
						if($res1->num_rows() == 0){
							$resultData['results'] = $limsResult;
							$resultData['created_at'] = date("Y-m-d H:i:s");
							$resultData['limsId'] = $resultL['limsId'];
							$resultData['nextVuId'] = $orderNumber;
							$resultData['sampleStatus'] = $resultL['sampleStatus'];
							$tests = $resultL['tests'];
							$insrtResult = $this->db->insert('ci_serum_result',$resultData);
							if(!empty($insrtResult) && !empty($tests)){
								$resultID = $this->db->insert_id();
								$testComponents = array();
								foreach($tests as $rowt){
									$typeData['result_id'] = $resultID;
									$typeData['limsTestCode'] = $rowt['limsTestCode'];
									$typeData['testName'] = $rowt['testName'];
									$typeData['testStatus'] = $rowt['testStatus'];
									$testComponents = $rowt['testComponents'];
									$insrtType = $this->db->insert('ci_serum_result_type',$typeData);
									if(!empty($insrtType) && !empty($testComponents)){
										$typeID = $this->db->insert_id();
										foreach($testComponents as $rowc){
											$insrtData['result_id'] = $resultID;
											$insrtData['type_id'] = $typeID;
											$insrtData['limsTestCode'] = $rowt['limsTestCode'];
											$insrtData['lims_allergens_id'] = (isset($rowc['id']) && !empty($rowc['id']))?$rowc['id']:'';
											$insrtData['name'] = (isset($rowc['name']) && !empty($rowc['name']))?$rowc['name']:'';
											$insrtData['category'] = (isset($rowc['category']) && !empty($rowc['category']))?$rowc['category']:'';
											$insrtData['result'] = (isset($rowc['result']) && !empty($rowc['result']))?$rowc['result']:'';
											$insrtData['isMould'] = (isset($rowc['isMould']) && !empty($rowc['isMould']))?$rowc['name']:'';
											$insrtData['cutoff'] = (isset($rowc['cutoff']) && !empty($rowc['cutoff']))?$rowc['cutoff']:'';
											$insrtData['outcome'] = (isset($rowc['outcome']) && !empty($rowc['outcome']))?$rowc['outcome']:'';
											$this->db->insert('ci_serum_result_allergens',$insrtData);
										}
									}
								}
								$orderData['text'] = "Serum Test Result Arrived";
								$orderData['order_id'] = $orderID;
								$orderData['created_by'] = '99999';
								$orderData['created_at'] = date("Y-m-d H:i:s");
								$this->db->insert('ci_order_history', $orderData);
							}
						}else{
							$resultID = $res1->row()->result_id;
							$resultData['results'] = $limsResult;
							$resultData['limsId'] = $resultL['limsId'];
							$resultData['nextVuId'] = $orderNumber;
							$resultData['sampleStatus'] = $resultL['sampleStatus'];
							$this->db->where('result_id', $resultID);
							$update = $this->db->update('ci_serum_result', $resultData);
							$tests = $resultL['tests'];
							if(!empty($update) && !empty($tests)){
								$testComponents = array();
								foreach($tests as $rowt){
									$testComponents = $rowt['testComponents'];
									$this->db->select('type_id');
									$this->db->from('ci_serum_result_type');
									$this->db->where('result_id', $resultID);
									$this->db->where('limsTestCode LIKE', $rowt['limsTestCode']);
									$this->db->where('testName LIKE', $rowt['testName']);
									$res2 = $this->db->get();
									if($res2->num_rows() == 0){
										$typeData['result_id'] = $resultID;
										$typeData['limsTestCode'] = $rowt['limsTestCode'];
										$typeData['testName'] = $rowt['testName'];
										$typeData['testStatus'] = $rowt['testStatus'];
										$this->db->insert('ci_serum_result_type',$typeData);
										$typeID = $this->db->insert_id();
									}else{
										$typeID = $res2->row()->type_id;
										$typeData['limsTestCode'] = $rowt['limsTestCode'];
										$typeData['testName'] = $rowt['testName'];
										$typeData['testStatus'] = $rowt['testStatus'];
										$this->db->where('type_id', $typeID);
										$this->db->update('ci_serum_result_type', $typeData);
									}
									if(!empty($testComponents)){
										foreach($testComponents as $rowc){
											$insrtData['limsTestCode'] = $rowt['limsTestCode'];
											$insrtData['lims_allergens_id'] = (isset($rowc['id']) && !empty($rowc['id']))?$rowc['id']:'';
											$insrtData['name'] = (isset($rowc['name']) && !empty($rowc['name']))?$rowc['name']:'';
											$insrtData['category'] = (isset($rowc['category']) && !empty($rowc['category']))?$rowc['category']:'';
											$insrtData['result'] = (isset($rowc['result']) && !empty($rowc['result']))?$rowc['result']:'';
											$insrtData['isMould'] = (isset($rowc['isMould']) && !empty($rowc['isMould']))?$rowc['name']:'';
											$insrtData['cutoff'] = (isset($rowc['cutoff']) && !empty($rowc['cutoff']))?$rowc['cutoff']:'';
											$insrtData['outcome'] = (isset($rowc['outcome']) && !empty($rowc['outcome']))?$rowc['outcome']:'';
											$this->db->select('id');
											$this->db->from('ci_serum_result_allergens');
											$this->db->where('result_id', $resultID);
											$this->db->where('type_id', $typeID);
											$this->db->where('name LIKE', $insrtData['name']);
											$this->db->where('category LIKE', $insrtData['category']);
											$res3 = $this->db->get();
											if($res3->num_rows() == 0){
												$insrtData['result_id'] = $resultID;
												$insrtData['type_id'] = $typeID;
												$this->db->insert('ci_serum_result_allergens',$insrtData);
											}else{
												$aID = $res3->row()->id;
												$this->db->where('id', $aID);
												$this->db->update('ci_serum_result_allergens', $insrtData);
											}
										}
									}
								}
							}
						}

						$ordersData['id'] = $orderID;
						$ordersData['is_authorised'] = '1';
						$ordersData['updated_by'] = $this->session->userdata('user_id');
						$ordersData['updated_at'] = date("Y-m-d H:i:s");
						$this->OrdersModel->add_edit($ordersData);
						$count++;
					}else{
						$this->resetNextvuStatusLIMS($resultL['limsId']);
					}
				}

				if($count > 0){
					$this->session->set_flashdata('success', 'LIMS Result data added successfully.');
				}else{
					$this->session->set_flashdata('error', 'LIMS Result data are empty.');
				}
			}else{
				$this->session->set_flashdata('error', 'LIMS Result data are empty.');
			}
		}
		redirect('limsApiRun');
	}

	function resetNextvuStatusLIMS($limsID){
		if($limsID !=''){
			ini_set('memory_limit', '256M');
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://185.151.29.200:7070/LiveSample/ResetNextvuStatus?Ids='.$limsID.'',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					'X-API-KEY: Lims@123',
					'Authorization: Basic TGltczoxMjM0'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
		}
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

}
?>