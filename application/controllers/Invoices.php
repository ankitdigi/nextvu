<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Invoices extends CI_Controller {

  	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
		}
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('InvoicesModel');
    }

	function index(){
		$this->load->view('invoices/index');
	}

    function invoice_upload(){
		$temp_name = explode(".", $_FILES["invoice"]["name"]);
		$config['upload_path']          = INVOICES_PATH;
		$config['allowed_types']        = 'csv';
		$config['file_name']            = preg_replace('/\s+/',  '_',  strtolower($temp_name[0]).'_'.time().'.'.$temp_name[1]);

		// Load CSV reader library
		$this->load->library('CSVReader');
		$csv_file = $_FILES['invoice']['tmp_name'];
		$file_data = $this->csvreader->parse_csv($csv_file,'');
		$orderData = []; $finalData = []; $error=""; $found=""; $columns = []; $order_number="";

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('invoice')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('error',$error['error']);
			/*$this->load->view('invoices/index.php');*/
			redirect('invoices/index');
		}else{
			$upload_data = array('upload_data' => $this->upload->data());
			$data['uploaded_doc_name'] = $upload_data['upload_data']['file_name'];
			$data['actual_doc_name'] = $_FILES['invoice']['name'];
			$data['job_received'] = '1';
			$data['uploaded_by'] = $this->user_id;
			$data['created_at'] = date("Y-m-d H:i:s");
			if ($id = $this->InvoicesModel->invoice_add($data)) {
				//insert record to ci_invoice_report
				foreach ($file_data as $key => $values) {
					$this->load->model('OrdersModel');
					if($values['order_number']!=''){
						//get order record
						$orderData = $this->OrdersModel->allData('',$values['order_number']);
                        if( empty($orderData) ){
                            $error = 1;
                            $finalData['order_number'] = $values['order_number'];
                            $finalData['doc_name'] = $_FILES["invoice"]["name"];
                            $finalData['columns'] = json_encode(array('order number'));
                            $finalData['invoice_id'] = $id;
                            $finalData['created_at'] = date("Y-m-d H:i:s");
                            $this->InvoicesModel->invoice_report($finalData);
                        }else{
                            if( $orderData['practice_name'] != $values['name'] ){
                              $found = 1;
                              $order_number = $values['order_number'];
                              $columns[] = 'practice name';
                            }
                            if( $orderData['address'] != $values['address_1'] ){
                              $found = 1;
                              $order_number = $values['order_number'];
                              $columns[] = 'address';
                            }
						}
					}
				}

				if($found){
					$error=1;
					$finalData['order_number'] = $order_number;
					$finalData['doc_name'] = $_FILES["invoice"]["name"];
					$finalData['columns'] = json_encode($columns);
					$finalData['invoice_id'] = $id;
					$finalData['created_at'] = date("Y-m-d H:i:s");
					$this->InvoicesModel->invoice_report($finalData);
				}

				$this->session->set_flashdata('success','File has been successfully uploaded.');
				//redirect('invoices/index');
				redirect('invoices/details/'.$id);
			}
		}
    }

	function details($id = ''){
		$this->_data['id'] = $id;
		$this->load->view("invoices/details", $this->_data);
	}

	function report($id = ''){
		$this->_data['id'] = $id;
		$this->load->view("invoices/report", $this->_data);
	}

	function view_details(){
		$postData = $this->input->post();
		$id = $this->input->post('id'); 
		$search_value = $postData['search']['value'];
		$invoice = $this->InvoicesModel->getRecord($id);

		// Load CSV reader library
		$this->load->library('CSVReader');
		// Parse data from CSV file.
		$csv_file = FCPATH.INVOICES_PATH.'/'.$invoice['uploaded_doc_name'];
		$file_data = $this->csvreader->parse_csv($csv_file,$search_value);
		$object_array = [];
		if(!empty($file_data)){
			$counter = 1;
			foreach ($file_data as $values) {
				$values['id'] = $counter;
				$object_array[] = (object)$values;
				$counter++;
			}
		}
		$ajax["recordsTotal"] = count($file_data);
		$ajax["recordsFiltered"]  = count($file_data);
		$ajax['data'] = $object_array;
		echo json_encode($ajax); exit();
	}

    function getTableData(){
		$invoices = $this->InvoicesModel->getTableData(); 
		if(!empty($invoices)){
			foreach ($invoices as $key => $value) {
				if(!empty($value->created_at)){
					$invoices[$key]->created_at = date('d/m/Y h:i A',strtotime($value->created_at));
				}
				$invoices[$key]->actual_doc_name = $value->actual_doc_name;
				$invoices[$key]->uploaded_by = $value->name;
				$invoices[$key]->job_received = ($value->job_received==1) ? "Yes" : "No";
			}
		}
		$total = $this->InvoicesModel->count_all();
		$totalFiltered = $this->InvoicesModel->count_filtered();
		//print_r($invoices); exit;
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $invoices;
		echo json_encode($ajax); exit();
	}

    function getReportTableData(){
		$id = $this->input->post('id'); 
		$invoices = $this->InvoicesModel->getReportTableData($id); 
		if(!empty($invoices)){
			foreach ($invoices as $key => $value) {
				$invoices[$key]->order_number = $value->order_number;
				// $invoices[$key]->doc_name = $value->doc_name;
				$invoices[$key]->columns = implode(", ",json_decode($value->columns));
			}
		}
		$total = $this->InvoicesModel->reportCount_all($id);
		$totalFiltered = $this->InvoicesModel->reportCount_filtered($id);
		//print_r($invoices); exit;
		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $invoices;
		echo json_encode($ajax); exit();
	}

	public function delete($id){
		$doc_name = FCPATH.'uploaded_files/invoices/'.basename($this->input->get('doc_name')); 
		if ($id != '' && is_numeric($id)) {
			$dataWhere['id'] = $id;
			$delete = $this->InvoicesModel->delete($dataWhere);
			if($delete && $doc_name!=''){
				unlink($doc_name);
				echo "success"; exit;
			}
		}
        echo "failed"; exit;
    }

	function getToBeInvoicedData(){
		$Orders = $this->InvoicesModel->getToBeInvoicedData();
		if (!empty($Orders)) {
			foreach ($Orders as $key => $value) {
				$Orders[$key]->pet_owner_name = $value->pet_owner_name .' '. $value->po_last;
				if (!empty($value->order_date)) {
					$Orders[$key]->order_date = date('d/m/Y', strtotime($value->order_date));
				}
				if (!empty($value->sampling_date)) {
					$Orders[$key]->sampling_date = date('d/m/Y', strtotime($value->sampling_date));
				}
				if ($value->order_type == 1) {
					$Orders[$key]->order_type = 'Immunotherapy';
				} elseif ($value->order_type == 2) {
					$Orders[$key]->order_type = 'Serum Testing';
				} else {
					$Orders[$key]->order_type = 'Skin Test';
				}
				if ($value->lab_id > 0) {
					$Orders[$key]->final_name = $value->lab_name;
				} elseif ($value->vet_user_id > 0) {
					$Orders[$key]->final_name = $value->practice_first_name;
				} else {
					$Orders[$key]->final_name = '';
				}
			}
		}

		$total = $this->InvoicesModel->count_tobeinvoiced_all();
		$totalFiltered = $this->InvoicesModel->count_tobeinvoiced_filtered();

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Orders;
		echo json_encode($ajax);
		exit();
	}

	function getInvoicedData(){
		$Orders = $this->InvoicesModel->getInvoicedData();
		if (!empty($Orders)) {
			foreach ($Orders as $key => $value) {
				$Orders[$key]->pet_owner_name = $value->pet_owner_name .' '. $value->po_last;
				if (!empty($value->order_date)) {
					$Orders[$key]->order_date = date('d/m/Y', strtotime($value->order_date));
				}
				if (!empty($value->sampling_date)) {
					$Orders[$key]->sampling_date = date('d/m/Y', strtotime($value->sampling_date));
				}
				if ($value->order_type == 1) {
					$Orders[$key]->order_type = 'Immunotherapy';
				} elseif ($value->order_type == 2) {
					$Orders[$key]->order_type = 'Serum Testing';
				} else {
					$Orders[$key]->order_type = 'Skin Test';
				}
				if ($value->lab_id > 0) {
					$Orders[$key]->final_name = $value->lab_name;
				} elseif ($value->vet_user_id > 0) {
					$Orders[$key]->final_name = $value->practice_first_name;
				} else {
					$Orders[$key]->final_name = '';
				}
			}
		}

		$total = $this->InvoicesModel->count_invoiced_all();
		$totalFiltered = $this->InvoicesModel->count_invoiced_filtered();

		$ajax["recordsTotal"] = $total;
		$ajax["recordsFiltered"]  = $totalFiltered;
		$ajax['data'] = $Orders;
		echo json_encode($ajax);
		exit();
	}

	function generateXml(){
		$this->load->helper('xml');
		$this->load->helper('file');
		$this->load->model('UsersDetailsModel');
		$selectedIds = !empty($this->input->post('invoice_ids'))?$this->input->post('invoice_ids'):0;
		if($selectedIds != 0){
			$xmlorderData['invoice_by'] = $this->user_id;
			$xmlorderData['invoice_date'] = date("Y-m-d");
			$xmlorderData['status'] = 0;
			foreach($selectedIds as $xrow){
				$xmlorderData['order_id'] = $xrow;
				$orderData = $this->InvoicesModel->getOrderNumber($xrow);
				$xmlorderData['order_number'] = $orderData->order_number;
				if($orderData->lab_id > 0){
				$xmlorderData['user_id'] = $orderData->lab_id;
				}else{
				$xmlorderData['user_id'] = $orderData->vet_user_id;
				}
				$this->InvoicesModel->addxmlorderInfo($xmlorderData);

				$updateData['is_confirmed'] = 0;
				$updateData['is_invoiced'] = 1;
				$this->db->where('id', $xrow);
				$this->db->update('ci_orders',$updateData);

				$orderhData['order_id'] = $xrow;
				$orderhData['text'] = 'Invoiced';
				$orderhData['created_by'] = $this->user_id;
				$orderhData['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('ci_order_history', $orderhData);
			}
		}
		$invoiceData = $this->InvoicesModel->getxmlOrderIds();
		$invoiceIdArr = array();
		foreach($invoiceData as $irow){
			$invoiceIdArr[] = $irow->order_id;
		}
		$invoiceIds = !empty($invoiceIdArr)?implode(",",$invoiceIdArr):0;
		$invoices = $this->InvoicesModel->getOrderDetails($invoiceIds);
		$search_value = '';
		if(!empty($invoices)){
			$output  = '<?xml version="1.0" encoding="utf-8"?>';
			$output .= '<Company xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
			$output .= '<Invoices>'; $invalidNumberArr = array();
			$Itemqty = ''; $unitPrice = ''; $order_discount = 0; $nominalCode = ''; $company = ''; $vatApplicable = 0; $shippingPrice = '0.00';
			foreach($invoices as $row){
				$nameArr = explode(" ",$row->name);
				if(!empty($nameArr) && count($nameArr) == 3){
					$fname = !empty($nameArr[0])?$nameArr[0]:'';
					$mname = !empty($nameArr[1])?$nameArr[1]:'';
					$sname = !empty($nameArr[2])?$nameArr[2]:'';
				}elseif(!empty($nameArr) && count($nameArr) == 2){
					$fname = !empty($nameArr[0])?$nameArr[0]:'';
					$mname = !empty($nameArr[1])?$nameArr[1]:'';
					$sname = '';
				}elseif(!empty($nameArr) && count($nameArr) == 1){
					$fname = !empty($nameArr[0])?$nameArr[0]:'';
					$mname = '';
					$sname = '';
				}else{
					$fname = '';
					$mname = '';
					$sname = '';
				}

				if ($row->order_can_send_to == '1') {
					$userId = $row->delivery_practice_id;
					$userData = array("user_id" => $row->delivery_practice_id, "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
					$usersDetails = $this->UsersDetailsModel->getColumnField($userData);

					$column_field = explode('|', $usersDetails['column_field']);
					$address_1 = isset($column_field[3]) ? $column_field[3] : '';
					$address_2 = isset($column_field[4]) ? $column_field[4] : '';
					$address_3 = isset($column_field[5]) ? $column_field[5].$column_field[6] : $column_field[6];
					$town = isset($column_field[0]) ? $column_field[0] : '';
					$postcode = isset($column_field[1]) ? $column_field[1] : '';
					$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
				}else if($row->order_can_send_to == '0'){
					if($row->lab_id > 0){
						$userId = $row->lab_id;
						$userData = array("user_id" => $row->lab_id, "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
						$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
						$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
						$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
						$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
						$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
						$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
						$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
						$country = $this->InvoicesModel->getCountryCode($row->lab_id);
					}else{
						$userId = $row->vet_user_id;
						$brances = $this->InvoicesModel->getBranchdetailsById($row->vet_user_id);
						if(!empty($brances)){
							$address_1 = !empty($brances->address) ? $brances->address : '';
							$address_2 = !empty($brances->address1) ? $brances->address1 : '';
							$address_3 = !empty($brances->address2) ? $brances->address2 : '';
							$town = !empty($brances->town_city) ? $brances->town_city : '';
							$postcode = !empty($brances->postcode) ? $brances->postcode : '';
							$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
						}else{
							$userData = array("user_id" => $row->vet_user_id, "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
							$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
							$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
							$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
							$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
							$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
							$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
							$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
							$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
						}
					}
				}

				if($row->lab_id > 0){
					$userData1 = array("user_id" => $row->lab_id, "column_name" => "'account_ref','vat_applicable'");
					$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
					$refDetails = array_column($refDetails, 'column_field', 'column_name');
					$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
					$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
					$compInfo = $this->InvoicesModel->getUserdetailsById($row->lab_id);
					if($compInfo->name != "" && $compInfo->last_name != ''){
					$company = $compInfo->name .' '. $compInfo->last_name;
					}elseif($compInfo->name != "" && $compInfo->last_name == ''){
					$company = $compInfo->name;
					}elseif($compInfo->name == "" && $compInfo->last_name != ''){
					$company = $compInfo->last_name;
					}
				}else{
					$userData2 = array("user_id" => $row->vet_user_id, "column_name" => "'account_ref','vat_applicable'");
					$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
					$refDatas = array_column($refDatas, 'column_field', 'column_name');
					$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
					$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
					if($account_ref == ''){
						$brances = $this->InvoicesModel->getBranchdetailsById($row->vet_user_id);
						if(!empty($brances)){
							$account_ref = !empty($brances->customer_number) ? $brances->customer_number : '';
						}
					}
					$compInfo = $this->InvoicesModel->getUserdetailsById($row->vet_user_id);
					if($compInfo->name != "" && $compInfo->last_name != ''){
					$company = $compInfo->name .' '. $compInfo->last_name;
					}elseif($compInfo->name != "" && $compInfo->last_name == ''){
					$company = $compInfo->name;
					}elseif($compInfo->name == "" && $compInfo->last_name != ''){
					$company = $compInfo->last_name;
					}
				}

				$selected_allergen = json_decode($row->allergens);
				$total_allergen = ($row->allergens != '') ? count(json_decode($row->allergens)) : 0;
				if($row->lab_id != 0){
					$practice_lab = $row->lab_id;
				}else{
					$practice_lab = $row->vet_user_id;
				}
				if($row->order_date != "0000-00-00" && $row->order_date != "" && $row->order_date != NULL){
					$orderDate =  'Order date '. date("d/m/Y",strtotime($row->order_date));
					$orderDate2 =  ' - Order date '. date("d/m/Y",strtotime($row->order_date));
				}else{
					$orderDate =  ''; $orderDate2 =  '';
				}

				if($row->pet_owner_id > 0){
					$petownInfo = $this->InvoicesModel->getUserdetailsById($row->pet_owner_id);
					if($petownInfo->name != "" && $petownInfo->last_name != ''){
					$petowner = $petownInfo->name .' '. $petownInfo->last_name;
					}elseif($petownInfo->name != "" && $petownInfo->last_name == ''){
					$petowner = $petownInfo->name;
					}elseif($petownInfo->name == "" && $petownInfo->last_name != ''){
					$petowner = $petownInfo->last_name;
					}
				}else{
					$petowner = '';
				}

				if($row->pet_id > 0){
					$petInfo = $this->InvoicesModel->getPetinfoById($row->pet_id);
					$petName = $petInfo->name;
				}else{
					$petName = '';
				}

				if($userId > 0){
					$compInfo = $this->InvoicesModel->getUserdetailsById($userId);
					if($compInfo->name != "" && $compInfo->last_name != ''){
					$send_to = $compInfo->name .' '. $compInfo->last_name;
					}elseif($compInfo->name != "" && $compInfo->last_name == ''){
					$send_to = $compInfo->name;
					}elseif($compInfo->name == "" && $compInfo->last_name != ''){
					$send_to = $compInfo->last_name;
					}
				}else{
					$send_to = '';
				}

				$comment1 = htmlspecialchars($petName).' '.htmlspecialchars($petowner).' '.$orderDate2;
				$comment2 = $send_to;

				if($account_ref != '' && htmlspecialchars($company) != '' && $total_allergen > 0 && $row->unit_price != '' && $row->unit_price > 0){
					$output .= '<Invoice>
						<Id>'. rand(10,10000) .'</Id>
						<AccountReference>'. $account_ref .'</AccountReference>
						<CustomerOrderNumber>'. $row->order_number .'</CustomerOrderNumber>
						<TakenBy>Website</TakenBy>
						<InvoiceDeliveryAddress>
							<Title>Mr</Title>
							<Forename>'.$fname.'</Forename>
							<Middlename>'.$mname.'</Middlename>
							<Surname>'.$sname.'</Surname>
							<Suffix>Jr.</Suffix>
							<Company>'. htmlspecialchars($company) .'</Company>
							<Address1>'. htmlspecialchars($address_1) .'</Address1>
							<Address2>'. htmlspecialchars($address_2) .'</Address2>
							<Address3>'. htmlspecialchars($address_3) .'</Address3>
							<Town>'. $town .'</Town>
							<Postcode>'. $postcode .'</Postcode>
							<County>'. $country .'</County>
							<Telephone>'. $row->phone_number .'</Telephone>
						</InvoiceDeliveryAddress>
						<InvoiceItems>';
						$shippingPrice = '0.00';
						if($row->order_type == '3'){
							$skin_test_Ncode = $this->InvoicesModel->skin_test_price($practice_lab);
							$nominalCode = $skin_test_Ncode[0]['nominal_code'];
							$single_price = $skin_test_Ncode[0]['uk_price'];
							$single_insect_price = $skin_test_Ncode[1]['uk_price'];
							$single_Pcode = $skin_test_Ncode[0]['sage_code'];
							$single_insect_Pcode = $skin_test_Ncode[1]['sage_code'];
							if($row->shipping_cost == '0.00'){
								$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("4", $practice_lab);
								if(!empty($shipUPrice)){
									$shippingPrice = $shipUPrice['uk_discount'];
								}else{
									$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("4");
									$shippingPrice = $shipDPrice['uk_price'];
								}
							}else{
								$shippingPrice = $row->shipping_cost;
							}

							$selected_allergen_ids = implode(",", $selected_allergen);
							$insects_allergen = $this->InvoicesModel->insect_allergen($selected_allergen_ids);
							$single_allergen = $total_allergen - $insects_allergen;
							$single_discount = $this->InvoicesModel->get_discount("14", $practice_lab);
							$single_order_discount = 0;
							if(!empty($single_discount)){
								$single_order_discount = $single_discount['uk_discount'];
								$single_order_discount = sprintf("%.2f", $single_order_discount);
							}
							if($single_allergen > 0){
								$itemName = 'Artuvetrin Test';
								$output .= '<Item>
									<Sku>'.$single_Pcode.'</Sku>
									<Name>'.$itemName.'</Name>
									<Description>'.$orderDate.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$single_allergen.'</QtyOrdered>
									<UnitPrice>'. $single_price .'</UnitPrice>
									<UnitDiscountPercentage>'. $single_order_discount .'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							}

							if ($insects_allergen > 0) {
								$insects_order_discount = 0;
								$insects_discount = $this->InvoicesModel->get_discount("15", $practice_lab);
								if (!empty($insects_discount)) {
									$insects_order_discount = $insects_discount['uk_discount'];
									$insects_order_discount = sprintf("%.2f", $insects_order_discount);
								}
								$itemName = 'Artuvetrin Test - Insect';
								$output .= '<Item>
									<Sku>'.$single_insect_Pcode.'</Sku>
									<Name>'.$itemName.'</Name>
									<Description>'.$orderDate.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$insects_allergen.'</QtyOrdered>
									<UnitPrice>'. $single_insect_price .'</UnitPrice>
									<UnitDiscountPercentage>'.$insects_order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							}
						}

						if ($row->order_type == '2') {
							$itemName = 'Serum Testing';
							if($row->shipping_cost == '0.00'){
								if ($row->species_selection == '2') {
									$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("3", $practice_lab);
								}
								if ($row->species_selection == '1') {
									$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("2", $practice_lab);
								}
								if(!empty($shipUPrice)){
									$shippingPrice = $shipUPrice['uk_discount'];
								}else{
									if ($row->species_selection == '2') {
										$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("3");
										$shippingPrice = $shipDPrice['uk_price'];
									}
									if ($row->species_selection == '1') {
										$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("2");
										$shippingPrice = $shipDPrice['uk_price'];
									}
								}
							}else{
								$shippingPrice = $row->shipping_cost;
							}

							$serum_discount = $this->InvoicesModel->get_discount($row->product_code_selection, $practice_lab);
							if (!empty($serum_discount)) {
								$order_discount = $serum_discount['uk_discount'];
								$order_discount = sprintf("%.2f", $order_discount);
							}
							$serum_test_Ncode = $this->InvoicesModel->serum_test_price($row->product_code_selection, $practice_lab);
							$nominalCode = $serum_test_Ncode[0]['nominal_code'];
							$serum_unitPrice = $serum_test_Ncode[0]['uk_price'];
							$serum_Pcode = $serum_test_Ncode[0]['sage_code'];
							$output .= '<Item>
								<Sku>'.$serum_Pcode.'</Sku>
								<Name>'.$itemName.'</Name>
								<Description>'.$orderDate.'</Description>
								<Comments>'.$comment2.'</Comments>
								<QtyOrdered>'.$total_allergen.'</QtyOrdered>
								<UnitPrice>'. $serum_unitPrice .'</UnitPrice>
								<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
								<NominalCode>'. $nominalCode .'</NominalCode>
								<TaxCode>'.$vatApplicable.'</TaxCode>
								<Department>1</Department>
							</Item>';
						}

						if ($row->order_type == '1' && $row->sub_order_type == '1') {
							if($row->shipping_cost == '0.00'){
								$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("1", $practice_lab);
								if(!empty($shipUPrice)){
									$shippingPrice = $shipUPrice['uk_discount'];
								}else{
									$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("1");
									$shippingPrice = $shipDPrice['uk_price'];
								}
							}else{
								$shippingPrice = $row->shipping_cost;
							}

							$artuvetrin_test_Ncode = $this->InvoicesModel->artuvetrin_test_price($practice_lab);
							if ($total_allergen <= 4) {
								$itemName = 'Artuvetrin Therapy';
								$artuvetrin_discount = $this->InvoicesModel->get_discount("16", $practice_lab);
								if (!empty($artuvetrin_discount)) {
									$order_discount = $artuvetrin_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
								$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
								$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
								$output .= '<Item>
									<Sku>'.$artuvetrin_Pcode.'</Sku>
									<Name>'.$itemName.'</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>1</QtyOrdered>
									<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							} elseif ($total_allergen > 4 && $total_allergen <= 8) {
								$itemName = 'Artuvetrin Therapy Forte';
								$artuvetrin_discount = $this->InvoicesModel->get_discount("17", $practice_lab);
								if (!empty($artuvetrin_discount)) {
									$order_discount = $artuvetrin_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
								$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
								$artuvetrin_Pcode = $artuvetrin_test_Ncode[1]['sage_code'];
								$output .= '<Item>
									<Sku>'.$artuvetrin_Pcode.'</Sku>
									<Name>'.$itemName.'</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>1</QtyOrdered>
									<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							} elseif ($total_allergen > 8) {
								$final_price = 0.00;
								$first_range_price = 0.00;
								$order_first_discount = 0.00;
								$order_second_discount = 0.00;
								$quotients = ($total_allergen / 8);
								$quotient = ((int)($total_allergen / 8));
								$remainder = (int)(fmod($total_allergen, 8));

								/**discount **/
								$artuvetrin_second_discount = $this->InvoicesModel->get_discount("17", $practice_lab);
								$_quotients = $quotients - $quotient;
								$is_update=1;
								if (!empty($artuvetrin_second_discount)) {
									if ($_quotients > 0.50) {
										$quotient++;
										$is_update=0;
										$order_second_discount = $artuvetrin_second_discount['uk_discount'];
										$order_second_discount = sprintf("%.2f", $order_second_discount);
									} else {
										$order_second_discount = $artuvetrin_second_discount['uk_discount'];
										$order_second_discount = sprintf("%.2f", $order_second_discount);
									}
								}
								$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
								$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
								$artuvetrin_PcodeF = $artuvetrin_test_Ncode[1]['sage_code'];
								if ($_quotients > 0.50) {
									if($is_update){
										$quotient++;
									}
								}
								$output .= '<Item>
									<Sku>'.$artuvetrin_PcodeF.'</Sku>
									<Name>Artuvetrin Therapy Forte</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$quotient.'</QtyOrdered>
									<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_second_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
								if($remainder > 0){
									$artuvetrin_first_discount = $this->InvoicesModel->get_discount("16",$practice_lab);
									if( !empty($artuvetrin_first_discount) ){
										if($_quotients <= 0.50 && $_quotients != 0) {
											$order_first_discount = $artuvetrin_first_discount['uk_discount'];
											$order_first_discount = sprintf("%.2f", $order_first_discount);
										}
									}
								}
								if($_quotients <= 0.50 && $_quotients != 0) {
									$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
									$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
									$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
									$output .= '<Item>
										<Sku>'.$artuvetrin_Pcode.'</Sku>
										<Name>Artuvetrin Therapy</Name>
										<Description>'.$comment1.'</Description>
										<Comments>'.$comment2.'</Comments>
										<QtyOrdered>1</QtyOrdered>
										<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
										<UnitDiscountPercentage>'.$order_first_discount.'</UnitDiscountPercentage>
										<NominalCode>'. $nominalCode .'</NominalCode>
										<TaxCode>'.$vatApplicable.'</TaxCode>
										<Department>1</Department>
									</Item>';
								}
								$order_discount = $order_first_discount + $order_second_discount;
							}
						}

						if ($row->order_type == '1' && $row->sub_order_type == '2') {
							$itemName = 'Immunotherapy';
							if($row->shipping_cost == '0.00'){
								$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("1", $practice_lab);
								if(!empty($shipUPrice)){
									$shippingPrice = $shipUPrice['uk_discount'];
								}else{
									$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("1");
									$shippingPrice = $shipDPrice['uk_price'];
								}
							}else{
								$shippingPrice = $row->shipping_cost;
							}

							$selected_allergen_ids = implode(",", $selected_allergen);
							$culicoides_allergen = $this->InvoicesModel->culicoides_allergen($selected_allergen_ids);
							$slit_test_Ncode = $this->InvoicesModel->slit_test_price($practice_lab);
							$nominalCode = $slit_test_Ncode[0]['nominal_code'];
							$single_price = $slit_test_Ncode[0]['uk_price'];
							$single_Pcode = $slit_test_Ncode[0]['sage_code'];
							$double_price = $slit_test_Ncode[1]['uk_price'];
							$double_Pcode = $slit_test_Ncode[1]['sage_code'];
							$single_with_culicoides = $slit_test_Ncode[2]['uk_price'];
							$single_with_culicoides_Pcode = $slit_test_Ncode[2]['sage_code'];
							$double_with_culicoides = $slit_test_Ncode[3]['uk_price'];
							$double_with_culicoides_Pcode = $slit_test_Ncode[3]['sage_code'];
							$single_allergen = $total_allergen - $culicoides_allergen;
							if ($row->single_double_selection == '1' && $culicoides_allergen == 0) {
								$slit_discount = $this->InvoicesModel->get_discount("18", $practice_lab);
								if (!empty($slit_discount)) {
									$order_discount = $slit_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$output .= '<Item>
									<Sku>'.$single_Pcode.'</Sku>
									<Name>Sublingual Single</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$total_allergen.'</QtyOrdered>
									<UnitPrice>'. $single_price .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							} else if ($row->single_double_selection == '2' && $culicoides_allergen == 0) {
								$slit_discount = $this->InvoicesModel->get_discount("19", $practice_lab);
								if (!empty($slit_discount)) {
									$order_discount = $slit_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$output .= '<Item>
									<Sku>'.$double_Pcode.'</Sku>
									<Name>Sublingual Double</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$total_allergen.'</QtyOrdered>
									<UnitPrice>'. $double_price .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							} else if ($row->single_double_selection == '1' && $culicoides_allergen > 0) {
								$slit_discount = $this->InvoicesModel->get_discount("20", $practice_lab);
								if (!empty($slit_discount)) {
									$order_discount = $slit_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$culicoidesPrices = $single_price + $single_with_culicoides;
								$culicoidesQty = $single_allergen + $culicoides_allergen;
								$output .= '<Item>
									<Sku>'.$single_with_culicoides_Pcode.'</Sku>
									<Name>Sublingual Single with culicoides</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$culicoidesQty.'</QtyOrdered>
									<UnitPrice>'. $culicoidesPrices .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							} else if ($row->single_double_selection == '2' && $culicoides_allergen > 0) {
								$slit_discount = $this->InvoicesModel->get_discount("21", $practice_lab);
								if (!empty($slit_discount)) {
									$order_discount = $slit_discount['uk_discount'];
									$order_discount = sprintf("%.2f", $order_discount);
								}
								$sdPrices = $double_price + $double_with_culicoides;
								$sdQty = $single_allergen + $culicoides_allergen;
								$output .= '<Item>
									<Sku>'.$double_with_culicoides_Pcode.'</Sku>
									<Name>Sublingual Double with culicoides</Name>
									<Description>'.$comment1.'</Description>
									<Comments>'.$comment2.'</Comments>
									<QtyOrdered>'.$sdQty.'</QtyOrdered>
									<UnitPrice>'. $sdPrices .'</UnitPrice>
									<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
									<NominalCode>'. $nominalCode .'</NominalCode>
									<TaxCode>'.$vatApplicable.'</TaxCode>
									<Department>1</Department>
								</Item>';
							}
							$order_discount = $order_discount;
						} 
					$output .= '</InvoiceItems>
						<Carriage>
							<Sku>150</Sku>
							<QtyOrdered>1</QtyOrdered>
							<UnitPrice>'. $shippingPrice .'</UnitPrice>
							<NominalCode>4905</NominalCode>
							<TaxCode>'.$vatApplicable.'</TaxCode>
							<Department>1</Department>
						</Carriage>
						<Notes1>'. htmlspecialchars($row->comment) .'</Notes1>
						<Notes2></Notes2>
						<Notes3></Notes3>
					</Invoice>';
				}else{
					$invalidNumberArr[] = $row->order_number;
					$this->db->where('order_id', $row->id);
					$this->db->delete('ci_orders_xml');

					$updateData['is_confirmed'] = 1;
					$updateData['is_invoiced'] = 0;
					$this->db->where('id', $row->id);
					$this->db->update('ci_orders',$updateData);

					$this->db->where('order_id', $row->id);
					$this->db->where('text LIKE', 'Invoiced');
					$this->db->delete('ci_order_history');
				}
			}
			$output .= '</Invoices>';
			$output .= '</Company>';
			$xml = xml_convert($output);
			$file_name = 'uploaded_files/invoice_xml/invoice_import.xml';
			write_file($file_name, $output);
			if(!empty($invalidNumberArr)){
				echo implode(",",$invalidNumberArr);
			}else{
				echo 'Sucess';
			}
		}else{
			echo 'failed';
		}
	}

	function generateMergeXml(){
		$sdate = date('Y-m-26', strtotime(date('Y-m')." -1 month"));
		$this->load->helper('xml');
		$this->load->helper('file');
		$this->load->model('UsersDetailsModel');
		$selectedIds = !empty($this->input->post('invoice_ids'))?$this->input->post('invoice_ids'):0;
		if($selectedIds != 0){
			$xmlorderData['invoice_by'] = $this->user_id;
			$xmlorderData['invoice_date'] = date("Y-m-d");
			$xmlorderData['status'] = 0;
			foreach($selectedIds as $xrow){
				$xmlorderData['order_id'] = $xrow;
				$orderData = $this->InvoicesModel->getOrderNumber($xrow);
				if($orderData->shipping_date < $sdate){
					$xmlorderData['order_number'] = $orderData->order_number;
					if($orderData->lab_id > 0){
					$xmlorderData['user_id'] = $orderData->lab_id;
					}else{
					$xmlorderData['user_id'] = $orderData->vet_user_id;
					}
					$this->InvoicesModel->addxmlorderInfo($xmlorderData);

					$updateData['is_confirmed'] = 0;
					$updateData['is_invoiced'] = 1;
					$this->db->where('id', $xrow);
					$this->db->update('ci_orders',$updateData);

					$orderhData['order_id'] = $xrow;
					$orderhData['text'] = 'Invoiced';
					$orderhData['created_by'] = $this->user_id;
					$orderhData['created_at'] = date("Y-m-d H:i:s");
					$this->db->insert('ci_order_history', $orderhData);
				}
			}
		}

		$useredData = $this->InvoicesModel->getxmlOrderIdbyUser();
		if(!empty($useredData)){
			$output  = '<?xml version="1.0" encoding="utf-8"?>';
			$output .= '<Company xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
				$output .= '<Invoices>';  $type1 = 0; $type2 = 0; $type3 = 0;
					foreach($useredData as $urow){
						$invoiceData = $this->InvoicesModel->getxmlMergeOrderIds($urow->user_id);
						$invoiceIdArr = array();
						foreach($invoiceData as $irow){
							$invoiceIdArr[] = $irow->order_id;
						}
						$invoiceIds = !empty($invoiceIdArr)?implode(",",$invoiceIdArr):0;
						$invoices = $this->InvoicesModel->getOrderDetails($invoiceIds);
						if(!empty($invoices)){
							$ordertyp = $this->InvoicesModel->getOrdersbyType($invoiceIds);
							foreach($ordertyp as $rowtyp){
								if($rowtyp->order_type == 1){
									$type1 = $rowtyp->totalOrder;
								}elseif($rowtyp->order_type == 2){
									$type2 = $rowtyp->totalOrder;
								}elseif($rowtyp->order_type == 3){
									$type3 = $rowtyp->totalOrder;
								}
							}
							$invalidNumberArr = array(); $Itemqty = ''; $unitPrice = ''; $order_discount = 0; $nominalCode = ''; $company = ''; $vatApplicable = ''; $shippingPrice1 = 0; $shippingPrice2 = 0; $shippingPrice3 = 0; $userId = 0; $t1=0; $t2=0; $t3=0;
							foreach($invoices as $row){
								$nameArr = explode(" ",$row->name);
								if(!empty($nameArr) && count($nameArr) == 3){
									$fname = !empty($nameArr[0])?$nameArr[0]:'';
									$mname = !empty($nameArr[1])?$nameArr[1]:'';
									$sname = !empty($nameArr[2])?$nameArr[2]:'';
								}elseif(!empty($nameArr) && count($nameArr) == 2){
									$fname = !empty($nameArr[0])?$nameArr[0]:'';
									$mname = !empty($nameArr[1])?$nameArr[1]:'';
									$sname = '';
								}elseif(!empty($nameArr) && count($nameArr) == 1){
									$fname = !empty($nameArr[0])?$nameArr[0]:'';
									$mname = '';
									$sname = '';
								}else{
									$fname = '';
									$mname = '';
									$sname = '';
								}

								if ($row->order_can_send_to == '1') {
									$userId = $row->delivery_practice_id;
									$userData = array("user_id" => $row->delivery_practice_id, "column_name" => "'address_2','address_3','account_ref','add_1','add_2','add_3','add_4'");
									$usersDetails = $this->UsersDetailsModel->getColumnField($userData);

									$column_field = explode('|', $usersDetails['column_field']);
									$address_1 = isset($column_field[3]) ? $column_field[3] : '';
									$address_2 = isset($column_field[4]) ? $column_field[4] : '';
									$address_3a = isset($column_field[5]) ? $column_field[5]: '';
									$address_3b = isset($column_field[6]) ? $column_field[6] : '';
									$address_3 = $address_3a.$address_3b;
									$town = isset($column_field[0]) ? $column_field[0] : '';
									$postcode = isset($column_field[1]) ? $column_field[1] : '';
									$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
								}else if($row->order_can_send_to == '0'){
									if($row->lab_id > 0){
										$userId = $row->lab_id;
										$userData = array("user_id" => $row->lab_id, "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
										$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
										$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
										$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
										$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
										$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
										$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
										$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
										$country = $this->InvoicesModel->getCountryCode($row->lab_id);
									}else{
										$userId = $row->vet_user_id;
										$brances = $this->InvoicesModel->getBranchdetailsById($row->vet_user_id);
										if(!empty($brances)){
											$address_1 = !empty($brances->address) ? $brances->address : '';
											$address_2 = !empty($brances->address1) ? $brances->address1 : '';
											$address_3 = !empty($brances->address2) ? $brances->address2 : '';
											$town = !empty($brances->town_city) ? $brances->town_city : '';
											$postcode = !empty($brances->postcode) ? $brances->postcode : '';
											$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
										}else{
											$userData = array("user_id" => $row->vet_user_id, "column_name" => "'address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4','post_code', 'town_city'");
											$LabDetails = $this->UsersDetailsModel->getColumnFieldArray($userData);
											$LabDetails = array_column($LabDetails, 'column_field', 'column_name');
											$address_1 = !empty($LabDetails['address_1']) ? $LabDetails['address_1'] : '';
											$address_2 = !empty($LabDetails['address_2']) ? $LabDetails['address_2'] : '';
											$address_3 = !empty($LabDetails['address_3']) ? $LabDetails['address_3'] : '';
											$town = !empty($LabDetails['town_city']) ? $LabDetails['town_city'] : '';
											$postcode = !empty($LabDetails['post_code']) ? $LabDetails['post_code'] : '';
											$country = $this->InvoicesModel->getCountryCode($row->vet_user_id);
										}
									}
								}

								if($row->lab_id > 0){
									$userData1 = array("user_id" => $row->lab_id, "column_name" => "'account_ref','vat_applicable'");
									$refDetails = $this->UsersDetailsModel->getColumnFieldArray($userData1);
									$refDetails = array_column($refDetails, 'column_field', 'column_name');
									$account_ref = !empty($refDetails['account_ref']) ? $refDetails['account_ref'] : '';
									$vatApplicable = !empty($refDetails['vat_applicable']) ? $refDetails['vat_applicable'] : '0';
									$compInfo = $this->InvoicesModel->getUserdetailsById($row->lab_id);
									if($compInfo->name != "" && $compInfo->last_name != ''){
									$company = $compInfo->name .' '. $compInfo->last_name;
									}elseif($compInfo->name != "" && $compInfo->last_name == ''){
									$company = $compInfo->name;
									}elseif($compInfo->name == "" && $compInfo->last_name != ''){
									$company = $compInfo->last_name;
									}
								}else{
									$userData2 = array("user_id" => $row->vet_user_id, "column_name" => "'account_ref','vat_applicable'");
									$refDatas = $this->UsersDetailsModel->getColumnFieldArray($userData2);
									$refDatas = array_column($refDatas, 'column_field', 'column_name');
									$account_ref = !empty($refDatas['account_ref']) ? $refDatas['account_ref'] : '';
									$vatApplicable = !empty($refDatas['vat_applicable']) ? $refDatas['vat_applicable'] : '0';
									if($account_ref == ''){
										$brances = $this->InvoicesModel->getBranchdetailsById($row->vet_user_id);
										if(!empty($brances)){
											$account_ref = !empty($brances->customer_number) ? $brances->customer_number : '';
										}
									}
									$compInfo = $this->InvoicesModel->getUserdetailsById($row->vet_user_id);
									if($compInfo->name != "" && $compInfo->last_name != ''){
									$company = $compInfo->name .' '. $compInfo->last_name;
									}elseif($compInfo->name != "" && $compInfo->last_name == ''){
									$company = $compInfo->name;
									}elseif($compInfo->name == "" && $compInfo->last_name != ''){
									$company = $compInfo->last_name;
									}
								}

								$selected_allergen = json_decode($row->allergens);
								$total_allergen = ($row->allergens != '') ? count(json_decode($row->allergens)) : 0;
								if($row->lab_id != 0){
									$practice_lab = $row->lab_id;
								}else{
									$practice_lab = $row->vet_user_id;
								}
								if($row->order_date != "0000-00-00" && $row->order_date != "" && $row->order_date != NULL){
									$orderDate =  'Order date '. date("d/m/Y",strtotime($row->order_date));
									$orderDate2 =  '- Order date '. date("d/m/Y",strtotime($row->order_date));
								}else{
									$orderDate =  ''; $orderDate2 =  '';
								}

								if($row->plc_selection == 1){
									$orderNo =  'Order Number '. $row->order_number;
								}elseif($row->plc_selection == 2){
									$orderNo =  'Order Number '. $row->reference_number;
								}

								if($row->pet_owner_id > 0){
									$petownInfo = $this->InvoicesModel->getUserdetailsById($row->pet_owner_id);
									if($petownInfo->name != "" && $petownInfo->last_name != ''){
									$petowner = $petownInfo->name .' '. $petownInfo->last_name;
									}elseif($petownInfo->name != "" && $petownInfo->last_name == ''){
									$petowner = $petownInfo->name;
									}elseif($petownInfo->name == "" && $petownInfo->last_name != ''){
									$petowner = $petownInfo->last_name;
									}
								}else{
									$petowner = '';
								}

								if($row->pet_id > 0){
									$petInfo = $this->InvoicesModel->getPetinfoById($row->pet_id);
									$petName = $petInfo->name;
								}else{
									$petName = '';
								}

								if($userId > 0){
									$compInfo = $this->InvoicesModel->getUserdetailsById($userId);
									if(!empty($compInfo)){
										if($compInfo->name != "" && $compInfo->last_name != ''){
										$send_to = '- '.$compInfo->name .' '. $compInfo->last_name;
										}elseif($compInfo->name != "" && $compInfo->last_name == ''){
										$send_to = '- '.$compInfo->name;
										}elseif($compInfo->name == "" && $compInfo->last_name != ''){
										$send_to = '- '.$compInfo->last_name;
										}
									}else{
										$send_to = '';
									}
								}else{
									$send_to = '';
								}

								$comment1 = $petName.' '.$petowner.' '.$orderDate2;
								$comment2 = $orderNo.' '.htmlspecialchars($send_to);
								if($account_ref != '' && htmlspecialchars($company) != '' && $total_allergen > 0 && $row->unit_price != '' && $row->unit_price > 0){
									if($row->order_type == '3'){
										if($t3 == 0){
										$output .= '<Invoice>
											<Id>'. rand(10,10000) .'</Id>
											<AccountReference>'. $account_ref .'</AccountReference>
											<CustomerOrderNumber>'. $row->order_number .'</CustomerOrderNumber>
											<TakenBy>Website</TakenBy>
											<InvoiceDeliveryAddress>
												<Title>Mr</Title>
												<Forename>'.$fname.'</Forename>
												<Middlename>'.$mname.'</Middlename>
												<Surname>'.$sname.'</Surname>
												<Suffix>Jr.</Suffix>
												<Company>'. htmlspecialchars($company) .'</Company>
												<Address1>'. htmlspecialchars($address_1) .'</Address1>
												<Address2>'. htmlspecialchars($address_2) .'</Address2>
												<Address3>'. htmlspecialchars($address_3) .'</Address3>
												<Town>'. $town .'</Town>
												<Postcode>'. $postcode .'</Postcode>
												<County>'. $country .'</County>
												<Telephone>'. $row->phone_number .'</Telephone>
											</InvoiceDeliveryAddress>
											<InvoiceItems>';
										}

										$skin_test_Ncode = $this->InvoicesModel->skin_test_price($practice_lab);
										$nominalCode = $skin_test_Ncode[0]['nominal_code'];
										$single_price = $skin_test_Ncode[0]['uk_price'];
										$single_insect_price = $skin_test_Ncode[1]['uk_price'];
										$single_Pcode = $skin_test_Ncode[0]['sage_code'];
										$single_insect_Pcode = $skin_test_Ncode[1]['sage_code'];
										$shippingPrice3 += $row->shipping_cost;
										/* if($row->shipping_cost == '0.00'){
											$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("4", $practice_lab);
											if(!empty($shipUPrice)){
												$shippingPrice = $shipUPrice['uk_discount'];
											}else{
												$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("4");
												$shippingPrice = $shipDPrice['uk_price'];
											}
										}else{
											$shippingPrice = $row->shipping_cost;
										} */
										$selected_allergen_ids = implode(",", $selected_allergen);
										$insects_allergen = $this->InvoicesModel->insect_allergen($selected_allergen_ids);
										$single_allergen = $total_allergen - $insects_allergen;
										$single_discount = $this->InvoicesModel->get_discount("14", $practice_lab);
										$single_order_discount = 0;
										if(!empty($single_discount)){
											$single_order_discount = $single_discount['uk_discount'];
											$single_order_discount = sprintf("%.2f", $single_order_discount);
										}
										if($single_allergen > 0){
											$itemName = 'Artuvetrin Test';
											$output .= '<Item>
												<Sku>'.$single_Pcode.'</Sku>
												<Name>'.$itemName.'</Name>
												<Description>'.$orderDate.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$single_allergen.'</QtyOrdered>
												<UnitPrice>'. $single_price .'</UnitPrice>
												<UnitDiscountPercentage>'. $single_order_discount .'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}

										if ($insects_allergen > 0) {
											$insects_order_discount = 0;
											$insects_discount = $this->InvoicesModel->get_discount("15", $practice_lab);
											if (!empty($insects_discount)) {
												$insects_order_discount = $insects_discount['uk_discount'];
												$insects_order_discount = sprintf("%.2f", $insects_order_discount);
											}
											$itemName = 'Artuvetrin Test - Insect';
											$output .= '<Item>
												<Sku>'.$single_insect_Pcode.'</Sku>
												<Name>'.$itemName.'</Name>
												<Description>'.$orderDate.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$insects_allergen.'</QtyOrdered>
												<UnitPrice>'. $single_insect_price .'</UnitPrice>
												<UnitDiscountPercentage>'.$insects_order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}

										$t3++;
										if($type3 == $t3){
										$output .= '</InvoiceItems>
										<Carriage>
											<Sku>150</Sku>
											<QtyOrdered>1</QtyOrdered>
											<UnitPrice>'. $shippingPrice3 .'</UnitPrice>
											<NominalCode>4905</NominalCode>
											<TaxCode>'.$vatApplicable.'</TaxCode>
											<Department>1</Department>
										</Carriage>
										<Notes1>'. htmlspecialchars($row->comment) .'</Notes1>
										<Notes2></Notes2>
										<Notes3></Notes3>
										</Invoice>';
										}
									}elseif($row->order_type == '2'){
										if($t2 == 0){
										$output .= '<Invoice>
										<Id>'. rand(10,10000) .'</Id>
										<AccountReference>'. $account_ref .'</AccountReference>
										<CustomerOrderNumber>'. $row->order_number .'</CustomerOrderNumber>
										<TakenBy>Website</TakenBy>
										<InvoiceDeliveryAddress>
											<Title>Mr</Title>
											<Forename>'.$fname.'</Forename>
											<Middlename>'.$mname.'</Middlename>
											<Surname>'.$sname.'</Surname>
											<Suffix>Jr.</Suffix>
											<Company>'. htmlspecialchars($company) .'</Company>
											<Address1>'. htmlspecialchars($address_1) .'</Address1>
											<Address2>'. htmlspecialchars($address_2) .'</Address2>
											<Address3>'. htmlspecialchars($address_3) .'</Address3>
											<Town>'. $town .'</Town>
											<Postcode>'. $postcode .'</Postcode>
											<County>'. $country .'</County>
											<Telephone>'. $row->phone_number .'</Telephone>
										</InvoiceDeliveryAddress>
										<InvoiceItems>';
										}
										$itemName = 'Serum Testing';
										/* if($row->shipping_cost == '0.00'){
											if ($row->species_selection == '2') {
												$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("3", $practice_lab);
											}
											if ($row->species_selection == '1') {
												$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("2", $practice_lab);
											}
											if(!empty($shipUPrice)){
												$shippingPrice = $shipUPrice['uk_discount'];
											}else{
												if ($row->species_selection == '2') {
													$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("3");
													$shippingPrice = $shipDPrice['uk_price'];
												}
												if ($row->species_selection == '1') {
													$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("2");
													$shippingPrice = $shipDPrice['uk_price'];
												}
											}
										}else{
											$shippingPrice = $row->shipping_cost;
										} */
										$shippingPrice2 += $row->shipping_cost;
										$serum_discount = $this->InvoicesModel->get_discount($row->product_code_selection, $practice_lab);
										if (!empty($serum_discount)) {
											$order_discount = $serum_discount['uk_discount'];
											$order_discount = sprintf("%.2f", $order_discount);
										}
										$serum_test_Ncode = $this->InvoicesModel->serum_test_price($row->product_code_selection, $practice_lab);
										$nominalCode = $serum_test_Ncode[0]['nominal_code'];
										$serum_unitPrice = $serum_test_Ncode[0]['uk_price'];
										$serum_Pcode = $serum_test_Ncode[0]['sage_code'];
										$output .= '<Item>
											<Sku>'.$serum_Pcode.'</Sku>
											<Name>'.$itemName.'</Name>
											<Description>'.$orderDate.'</Description>
											<Comments>'.$comment2.'</Comments>
											<QtyOrdered>'.$total_allergen.'</QtyOrdered>
											<UnitPrice>'. $serum_unitPrice .'</UnitPrice>
											<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
											<NominalCode>'. $nominalCode .'</NominalCode>
											<TaxCode>'.$vatApplicable.'</TaxCode>
											<Department>1</Department>
										</Item>';
										$t2++;
										if($type2 == $t2){
										$output .= '</InvoiceItems>
											<Carriage>
												<Sku>150</Sku>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $shippingPrice2 .'</UnitPrice>
												<NominalCode>4905</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Carriage>
											<Notes1>'. htmlspecialchars($row->comment) .'</Notes1>
											<Notes2></Notes2>
											<Notes3></Notes3>
										</Invoice>';
										}
									}elseif($row->order_type == '1'){
										if($t1 == 0){
										$output .= '<Invoice>
										<Id>'. rand(10,10000) .'</Id>
										<AccountReference>'. $account_ref .'</AccountReference>
										<CustomerOrderNumber>'. $row->order_number .'</CustomerOrderNumber>
										<TakenBy>Website</TakenBy>
										<InvoiceDeliveryAddress>
											<Title>Mr</Title>
											<Forename>'.$fname.'</Forename>
											<Middlename>'.$mname.'</Middlename>
											<Surname>'.$sname.'</Surname>
											<Suffix>Jr.</Suffix>
											<Company>'. htmlspecialchars($company) .'</Company>
											<Address1>'. htmlspecialchars($address_1) .'</Address1>
											<Address2>'. htmlspecialchars($address_2) .'</Address2>
											<Address3>'. htmlspecialchars($address_3) .'</Address3>
											<Town>'. $town .'</Town>
											<Postcode>'. $postcode .'</Postcode>
											<County>'. $country .'</County>
											<Telephone>'. $row->phone_number .'</Telephone>
										</InvoiceDeliveryAddress>
										<InvoiceItems>';
										}
										if($row->sub_order_type == '1'){
											/* if($row->shipping_cost == '0.00'){
												$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("1", $practice_lab);
												if(!empty($shipUPrice)){
													$shippingPrice = $shipUPrice['uk_discount'];
												}else{
													$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("1");
													$shippingPrice = $shipDPrice['uk_price'];
												}
											}else{
												$shippingPrice = $row->shipping_cost;
											} */
											$shippingPrice1 += $row->shipping_cost;

											$artuvetrin_test_Ncode = $this->InvoicesModel->artuvetrin_test_price($practice_lab);
											if ($total_allergen <= 4) {
												$itemName = 'Artuvetrin Therapy';
												$artuvetrin_discount = $this->InvoicesModel->get_discount("16", $practice_lab);
												if (!empty($artuvetrin_discount)) {
													$order_discount = $artuvetrin_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
												$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
												$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
												$output .= '<Item>
													<Sku>'.$artuvetrin_Pcode.'</Sku>
													<Name>'.$itemName.'</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>1</QtyOrdered>
													<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											} elseif ($total_allergen > 4 && $total_allergen <= 8) {
												$itemName = 'Artuvetrin Therapy Forte';
												$artuvetrin_discount = $this->InvoicesModel->get_discount("17", $practice_lab);
												if (!empty($artuvetrin_discount)) {
													$order_discount = $artuvetrin_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
												$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
												$artuvetrin_Pcode = $artuvetrin_test_Ncode[1]['sage_code'];
												$output .= '<Item>
													<Sku>'.$artuvetrin_Pcode.'</Sku>
													<Name>'.$itemName.'</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>1</QtyOrdered>
													<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											} elseif ($total_allergen > 8) {
												$final_price = 0.00;
												$first_range_price = 0.00;
												$order_first_discount = 0.00;
												$order_second_discount = 0.00;
												$quotients = ($total_allergen / 8);
												$quotient = ((int)($total_allergen / 8));
												$remainder = (int)(fmod($total_allergen, 8));

												$artuvetrin_second_discount = $this->InvoicesModel->get_discount("17", $practice_lab);
												$_quotients = $quotients - $quotient;
												$is_update=1;
												if (!empty($artuvetrin_second_discount)) {
													if ($_quotients > 0.50) {
														$quotient++;
														$is_update=0;
														$order_second_discount = $artuvetrin_second_discount['uk_discount'];
														$order_second_discount = sprintf("%.2f", $order_second_discount);
													} else {
														$order_second_discount = $artuvetrin_second_discount['uk_discount'];
														$order_second_discount = sprintf("%.2f", $order_second_discount);
													}
												}
												$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
												$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
												$artuvetrin_PcodeF = $artuvetrin_test_Ncode[1]['sage_code'];
												if ($_quotients > 0.50) {
													if($is_update){
														$quotient++;
													}
												}
												$output .= '<Item>
													<Sku>'.$artuvetrin_PcodeF.'</Sku>
													<Name>Artuvetrin Therapy Forte</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>'.$quotient.'</QtyOrdered>
													<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_second_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
												if($remainder > 0){
													$artuvetrin_first_discount = $this->InvoicesModel->get_discount("16",$practice_lab);
													if( !empty($artuvetrin_first_discount) ){
														if($_quotients <= 0.50 && $_quotients != 0) {
															$order_first_discount = $artuvetrin_first_discount['uk_discount'];
															$order_first_discount = sprintf("%.2f", $order_first_discount);
														}
													}
												}
												if($_quotients <= 0.50 && $_quotients != 0) {
													$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
													$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
													$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
													$output .= '<Item>
														<Sku>'.$artuvetrin_Pcode.'</Sku>
														<Name>Artuvetrin Therapy</Name>
														<Description>'.$comment1.'</Description>
														<Comments>'.$comment2.'</Comments>
														<QtyOrdered>1</QtyOrdered>
														<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
														<UnitDiscountPercentage>'.$order_first_discount.'</UnitDiscountPercentage>
														<NominalCode>'. $nominalCode .'</NominalCode>
														<TaxCode>'.$vatApplicable.'</TaxCode>
														<Department>1</Department>
													</Item>';
												}
												$order_discount = $order_first_discount + $order_second_discount;
											}
										}

										if($row->sub_order_type == '2'){
											$itemName = 'Immunotherapy';
											/* if($row->shipping_cost == '0.00'){
												$shipUPrice = $this->InvoicesModel->getShippingCostbyUser("1", $practice_lab);
												if(!empty($shipUPrice)){
													$shippingPrice = $shipUPrice['uk_discount'];
												}else{
													$shipDPrice = $this->InvoicesModel->getDefaultShippingCost("1");
													$shippingPrice = $shipDPrice['uk_price'];
												}
											}else{
												$shippingPrice = $row->shipping_cost;
											} */
											$shippingPrice1 += $row->shipping_cost;

											$selected_allergen_ids = implode(",", $selected_allergen);
											$culicoides_allergen = $this->InvoicesModel->culicoides_allergen($selected_allergen_ids);
											$slit_test_Ncode = $this->InvoicesModel->slit_test_price($practice_lab);
											$nominalCode = $slit_test_Ncode[0]['nominal_code'];
											$single_price = $slit_test_Ncode[0]['uk_price'];
											$single_Pcode = $slit_test_Ncode[0]['sage_code'];
											$double_price = $slit_test_Ncode[1]['uk_price'];
											$double_Pcode = $slit_test_Ncode[1]['sage_code'];
											$single_with_culicoides = $slit_test_Ncode[2]['uk_price'];
											$single_with_culicoides_Pcode = $slit_test_Ncode[2]['sage_code'];
											$double_with_culicoides = $slit_test_Ncode[3]['uk_price'];
											$double_with_culicoides_Pcode = $slit_test_Ncode[3]['sage_code'];
											$single_allergen = $total_allergen - $culicoides_allergen;
											if ($row->single_double_selection == '1' && $culicoides_allergen == 0) {
												$slit_discount = $this->InvoicesModel->get_discount("18", $practice_lab);
												if (!empty($slit_discount)) {
													$order_discount = $slit_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$output .= '<Item>
													<Sku>'.$single_Pcode.'</Sku>
													<Name>Sublingual Single</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>'.$total_allergen.'</QtyOrdered>
													<UnitPrice>'. $single_price .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											} else if ($row->single_double_selection == '2' && $culicoides_allergen == 0) {
												$slit_discount = $this->InvoicesModel->get_discount("19", $practice_lab);
												if (!empty($slit_discount)) {
													$order_discount = $slit_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$output .= '<Item>
													<Sku>'.$double_Pcode.'</Sku>
													<Name>Sublingual Double</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>'.$total_allergen.'</QtyOrdered>
													<UnitPrice>'. $double_price .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											} else if ($row->single_double_selection == '1' && $culicoides_allergen > 0) {
												$slit_discount = $this->InvoicesModel->get_discount("20", $practice_lab);
												if (!empty($slit_discount)) {
													$order_discount = $slit_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$culicoidesPrices = $single_price + $single_with_culicoides;
												$culicoidesQty = $single_allergen + $culicoides_allergen;
												$output .= '<Item>
													<Sku>'.$single_with_culicoides_Pcode.'</Sku>
													<Name>Sublingual Single with culicoides</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>'.$culicoidesQty.'</QtyOrdered>
													<UnitPrice>'. $culicoidesPrices .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											} else if ($row->single_double_selection == '2' && $culicoides_allergen > 0) {
												$slit_discount = $this->InvoicesModel->get_discount("21", $practice_lab);
												if (!empty($slit_discount)) {
													$order_discount = $slit_discount['uk_discount'];
													$order_discount = sprintf("%.2f", $order_discount);
												}
												$sdPrices = $double_price + $double_with_culicoides;
												$sdQty = $single_allergen + $culicoides_allergen;
												$output .= '<Item>
													<Sku>'.$double_with_culicoides_Pcode.'</Sku>
													<Name>Sublingual Double with culicoides</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>'.$sdQty.'</QtyOrdered>
													<UnitPrice>'. $sdPrices .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											}
											$order_discount = $order_discount;
										}
										$t1++;
										if($type1 == $t1){
										$output .= '</InvoiceItems>
											<Carriage>
												<Sku>150</Sku>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $shippingPrice1 .'</UnitPrice>
												<NominalCode>4905</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Carriage>
											<Notes1>'. htmlspecialchars($row->comment) .'</Notes1>
											<Notes2></Notes2>
											<Notes3></Notes3>
										</Invoice>';
										}
									}
								}else{
									if($row->order_type == 1){ $type1--; }elseif($row->order_type == 2){ $type2--; }elseif($row->order_type == 3){ $type3--; }
									$invalidNumberArr[] = $row->order_number;
									$this->db->where('order_id', $row->id);
									$this->db->delete('ci_orders_xml');

									$updateData['is_confirmed'] = 4;
									$updateData['is_invoiced'] = 0;
									$this->db->where('id', $row->id);
									$this->db->update('ci_orders',$updateData);

									$this->db->where('order_id', $row->id);
									$this->db->where('text LIKE', 'Invoiced');
									$this->db->delete('ci_order_history');
								}
							}
						}
					}
				$output .= '</Invoices>';
			$output .= '</Company>';
			$xml = xml_convert($output);
			$file_name = 'uploaded_files/invoice_xml/invoice_import.xml';
			write_file($file_name, $output);
			if(!empty($invalidNumberArr)){
				echo implode(",",$invalidNumberArr);
			}else{
				echo 'Sucess';
			}
		}else{
			echo 'failed';
		}
	}

}
?>