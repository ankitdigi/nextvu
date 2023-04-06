<?php
if(!defined('BASEPATH')) exit('No direct script access allowed'); 
class LimsAPI extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') !== TRUE){
			redirect('users/index');
        }
        $this->user_role = $this->session->userdata('role');
		$this->zones = $this->session->userdata('managed_by_id');
		$this->user_id = $this->session->userdata('user_id');
		$this->load->model('LimsAPIModel');
		$this->load->model('OrdersModel');
		$this->load->model('UsersModel');
    }

	function sendOrderstoLIMS(){
		if($_SERVER['SERVER_NAME'] == 'www.nano-staging-com.stackstaging.com' || $_SERVER['SERVER_NAME'] == 'nano-staging-com.stackstaging.com'){
			$lims_url = 'http://185.151.29.200:7070/Sample';
		}elseif($_SERVER['SERVER_NAME'] == 'www.nextmunelaboratories.com' || $_SERVER['SERVER_NAME'] == 'nextmunelaboratories.com'){
			$lims_url = 'http://185.151.29.200:7070/LiveSample';
		}else{
			$lims_url = 'http://185.151.29.200:7070/Sample';
		}
		ini_set('memory_limit', '256M');
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $lims_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
			'Content-Length: 0',
			'X-API-KEY: Lims@123',
			'Authorization: Basic TGltczoxMjM0'
			),
		));
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
		}
		curl_close($ch);
		if(isset($error_msg)) {
			$this->session->set_flashdata('error', $error_msg);
		}else{
			$results = json_decode($response,true);
			if(!empty($results)){
				if($results['message'] == 'Completed'){
					$this->LimsAPIModel->callLIMSTransationAPI();
					$this->session->set_flashdata('success', 'Orders sent to LIMS successfully.');
				}else{
					$this->session->set_flashdata('success', 'There are no orders to send to LIMS at this time.');
				}
			}else{
				$this->session->set_flashdata('error', 'There are no orders to send to LIMS at this time.');
			}
		}
		redirect('getLIMSResults');
	}

	function getLIMSResults(){
		$this->load->view('limsApi/getresult');
	}

	function getLIMSResult(){
		if($_SERVER['SERVER_NAME'] == 'www.nano-staging-com.stackstaging.com' || $_SERVER['SERVER_NAME'] == 'nano-staging-com.stackstaging.com'){
			$lims_url = 'http://185.151.29.200:7070/Sample';
		}elseif($_SERVER['SERVER_NAME'] == 'www.nextmunelaboratories.com' || $_SERVER['SERVER_NAME'] == 'nextmunelaboratories.com'){
			$lims_url = 'http://185.151.29.200:7070/LiveSample';
		}else{
			$lims_url = 'http://185.151.29.200:7070/Sample';
		}
		ini_set('memory_limit', '256M');
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $lims_url,
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
					$this->db->order_by('id', "DESC");
					$resord = $this->db->get();
					$resuld = $resord->row();
					if(!empty($resuld)){
						$orderID = $resuld->id;
						$orderNumber = $resuld->order_number;
						$this->db->select('result_id');
						$this->db->from('ci_serum_result');
						$this->db->where('nextVuId', $orderNumber);
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
						$this->LimsAPIModel->resetNextvuStatusLIMS($resultL['limsId']);
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
		redirect('getLIMSResults');
	}

}
?>