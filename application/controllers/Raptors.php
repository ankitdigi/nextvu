<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Raptors extends CI_Controller {

  	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '256M');
		if ($this->session->userdata('logged_in') !== TRUE) {
			redirect('users/index');
		}
		$this->user_id = $this->session->userdata('user_id');
		$this->user_role = $this->session->userdata('role');
		$this->user_country = $this->session->userdata('country');
		$this->zones = $this->session->userdata('managed_by_id');
		$this->load->model('OrdersModel');
    }

	function getRaptorResult(){
		$responce = array();
		$msg_data = $this->input->post();
		$id = $msg_data['order_id_raptor_modal'];
		$zonesIds = $this->checkZones($id);
		$barcode = $msg_data['measurement_id'];
		if(!empty($zonesIds) && in_array("6", $zonesIds)){
			/* Netherlands API-Key */
			$measurementIdn = $this->getMeasurementId($barcode,'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy');
			if($measurementIdn == '0'){
				/* UK API-Key */
				$measurementIdu = $this->getMeasurementId($barcode,'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a');
				if($measurementIdu == '0'){
					/* Spain API-Key */
					$measurementIds = $this->getMeasurementId($barcode,'46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw');
					$measurementId = $measurementIds;
					$apiKey = '46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw';
				}else{
					$apiKey = 'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a';
					$measurementId = $measurementIdu;
				}
			}else{
				$apiKey = 'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy';
				$measurementId = $measurementIdn;
			}
		}elseif(!empty($zonesIds) && in_array("8", $zonesIds)){
			/* Spain API-Key */
			$measurementIds = $this->getMeasurementId($barcode,'46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw');
			if($measurementIds == '0'){
				/* UK API-Key */
				$measurementIdu = $this->getMeasurementId($barcode,'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a');
				if($measurementIdu == '0'){
					/* Netherlands API-Key */
					$measurementIdn = $this->getMeasurementId($barcode,'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy');
					$measurementId = $measurementIdn;
					$apiKey = 'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy';
				}else{
					$apiKey = 'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a';
					$measurementId = $measurementIdu;
				}
			}else{
				$apiKey = '46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw';
				$measurementId = $measurementIds;
			}
		}else{
			/* UK API-Key */
			$measurementIdu = $this->getMeasurementId($barcode,'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a');
			if($measurementIdu == '0'){
				/* Spain API-Key */
				$measurementIds = $this->getMeasurementId($barcode,'46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw');
				if($measurementIds == '0'){
					/* Netherlands API-Key */
					$measurementIdn = $this->getMeasurementId($barcode,'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy');
					$measurementId = $measurementIdn;
					$apiKey = 'UjURR8hqRIt4nul11L3vgroS2IDU1pRPgtd3Okj1OrD7EjOrwy';
				}else{
					$measurementId = $measurementIds;
					$apiKey = '46HpwsnR9yRbHjq4WYIULSwu8HPnA8wbrggYFcDNx7feXxXydw';
				}
			}else{
				$apiKey = 'Wv9drVdBUvJ7qDwVpsSCOiRo0S0NsdcOwDQnletPEYaUDKrY1a';
				$measurementId = $measurementIdu;
			}
		}
		if($measurementId != '0'){
			$measurement_id = $measurementId;
		}else{
			$measurement_id = $barcode;
		}
		if(!empty($measurement_id)){
			$data = $this->OrdersModel->getRecord($id);
			$order_details = $this->OrdersModel->allData($data['id'], "");
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.raptor-server.com/measurement/'.$measurement_id.'',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'API-Key: '. $apiKey .''
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$resultL = json_decode($response,true);
			if(!empty($resultL) && !empty($resultL['id'])){
				$mid = $resultL['id'];
				$barcode			= !empty($resultL['barcode'])?$resultL['barcode']:'';
				$sampling_date		= !empty($resultL['sampling_date'])?$resultL['sampling_date']:'';
				$assay_date			= !empty($resultL['assay_date'])?$resultL['assay_date']:'';
				$processed_date		= !empty($resultL['processed_date'])?$resultL['processed_date']:'';
				$analyzed_date		= !empty($resultL['analyzed_date'])?$resultL['analyzed_date']:'';
				$approved_date		= !empty($resultL['approved_date'])?$resultL['approved_date']:'';
				$sample_code		= !empty($resultL['sample_code'])?$resultL['sample_code']:'';
				$patient_birthdate	= !empty($resultL['patient_birthdate'])?$resultL['patient_birthdate']:'';
				$patient_code		= !empty($resultL['patient_code'])?$resultL['patient_code']:'';
				$patient_name		= !empty($resultL['patient_name'])?$resultL['patient_name']:'';
				$sample_doctorinfo	= !empty($resultL['sample_doctorinfo'])?$resultL['sample_doctorinfo']:'';
				$sample_info		= !empty($resultL['sample_info'])?$resultL['sample_info']:'';
				$requisition_code	= !empty($resultL['requisition_code'])?$resultL['requisition_code']:'';
				$mod				= !empty($resultL['mod'])?$resultL['mod']:'';
				$qc					= !empty($resultL['qc'])?$resultL['qc']:'';
				$resultData['nextvu_id']		= $data['order_number'];
				$resultData['measurement_id']	= $mid;
				$resultData['barcode']			= $barcode;
				$resultData['sampling_date']	= $sampling_date;
				$resultData['assay_date']		= $assay_date;
				$resultData['processed_date']	= $processed_date;
				$resultData['analyzed_date'] 	= $analyzed_date;
				$resultData['approved_date'] 	= $approved_date;
				$resultData['sample_code'] 		= $sample_code;
				$resultData['patient_birthdate']= $patient_birthdate;
				$resultData['patient_code'] 	= $patient_code;
				$resultData['patient_name'] 	= $patient_name;
				$resultData['sample_doctorinfo']= $sample_doctorinfo;
				$resultData['sample_info']		= $sample_info;
				$resultData['requisition_code']	= $requisition_code;
				$resultData['result_mod'] 		= $mod;
				$resultData['qc']				= $qc;

				$this->db->select('result_id');
				$this->db->from('ci_raptor_serum_result');
				$this->db->where('nextvu_id', $data['order_number']);
				$this->db->where('measurement_id', $mid);
				$res1 = $this->db->get();
				if($res1->num_rows() == 0){
					$allergensArr			= !empty($resultL['allergens'])?$resultL['allergens']:array();
					$insrtResult = $this->db->insert('ci_raptor_serum_result',$resultData);
					if(!empty($insrtResult) && !empty($allergensArr)){
						$resultID = $this->db->insert_id();
						foreach($allergensArr as $rowt){
							$typeData['result_id'] = $resultID;
							$typeData['name'] = $rowt['name'];
							$typeData['code'] = '';
							$typeData['result_value']	= $rowt['value'];
							$typeData['value_intensity']= $rowt['value_intensity'];
							$this->db->insert('ci_raptor_result_allergens',$typeData);
						}
						$this->session->set_flashdata('sucess', 'Result data save successfully.');
					}else{
						$this->session->set_flashdata('error', 'Result data are empty.');
					}
				}else{
					$allergensArr	= !empty($resultL['allergens'])?$resultL['allergens']:array();
					$resultID = $res1->row()->result_id;
					$this->db->where('result_id', $resultID);
					$update = $this->db->update('ci_raptor_serum_result', $resultData);
					if(!empty($update) && !empty($allergensArr)){
						foreach($allergensArr as $rowt){
							$this->db->select('id');
							$this->db->from('ci_raptor_result_allergens');
							$this->db->where('result_id', $resultID);
							$this->db->where('name LIKE', $rowt['name']);
							$res2 = $this->db->get();
							if($res2->num_rows() == 0){
								$typeData['result_id'] = $resultID;
								$typeData['name'] = $rowt['name'];
								$typeData['code'] = '';
								$typeData['result_value']	= $rowt['value'];
								$typeData['value_intensity']= $rowt['value_intensity'];
								$this->db->insert('ci_raptor_result_allergens',$typeData);
							}else{
								$typeID = $res2->row()->type_id;
								$typeData['result_value']	= $rowt['value'];
								$typeData['value_intensity']= $rowt['value_intensity'];
								$this->db->where('id', $typeID);
								$this->db->update('ci_raptor_result_allergens', $typeData);
							}
						}
					}
				}
				$orderData['id'] = $id;
				$orderData['is_raptor_result'] = 1;
				$orderData['measurement_id'] = $measurement_id;
				$this->OrdersModel->add_edit($orderData);
				$this->session->set_flashdata("success", "Raptor Result data added successfully.");
				$responce['sucess'] = "Result data added successfully.";
			}else{
				//$responce['error'] = $resultL['errors']['id'][0];
				$responce['error'] = 'The Measurement Id which you entered '.$barcode.' is not valid';
			}
		}else{
			$responce['error'] = "You have not enter Measurement ID";
		}
		echo json_encode($responce); exit();
	}

	function getMeasurementId($barcode,$apiKey){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.allergy-explorer.com/measurements?filterSampleCode='.$barcode.'',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'API-Key: '. $apiKey .''
			),
		));
		$responsem = curl_exec($curl);
		curl_close($curl);
		$resultM = json_decode($responsem,true);
		if(!empty($resultM) && !empty($resultM['measurements'])){
			return $resultM['measurements'][0]['id'];
		}else{
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_URL => 'https://api.allergy-explorer.com/measurements?filterBarcode='.$barcode.'',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'API-Key: '. $apiKey .''
				),
			));
			$responsem2 = curl_exec($ch);
			curl_close($ch);
			$resultM2 = json_decode($responsem2,true);
			if(!empty($resultM2) && !empty($resultM2['measurements'])){
				return $resultM2['measurements'][0]['id'];
			}else{
				return '0';
			}
		}
	}

	function getZoneEmail($id){
		$this->db->select('managed_by_email');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.implode(",",$id).')');
		$zoneData = $this->db->get()->row();
		return !empty($zoneData->managed_by_email)?$zoneData->managed_by_email:'';
	}

	function getZoneFromEmail($id){
		$this->db->select('managed_by_from_email');
		$this->db->from('ci_managed_by_members');
		$this->db->where('id IN('.implode(",",$id).')');
		$zoneData = $this->db->get()->row();
		return !empty($zoneData->managed_by_from_email)?$zoneData->managed_by_from_email:'';
	}

	function checkZones($id){
		$this->db->select('vet_user_id,lab_id');
		$this->db->from('ci_orders');
		$this->db->where('id', $id);
		$ordrData = $this->db->get()->row();
		if($ordrData->lab_id > 0){
			$userID = $ordrData->lab_id;
		}else{
			$userID = $ordrData->vet_user_id;
		}

		$this->db->select('managed_by_id');
		$this->db->from('ci_users');
		$this->db->where('id', $userID);
		$userData = $this->db->get()->row();
		if($userData->managed_by_id != ''){
			return explode(",",$userData->managed_by_id);
		}else{
			return '0';
		}
	}

}
?>