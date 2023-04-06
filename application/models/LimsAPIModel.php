<?php
class LimsAPIModel extends CI_model{

	public function __construct(){
		parent::__construct();
		$this->_table = 'ci_user_details';
		$this->user_table = 'ci_users';
		$this->allergens_table = 'ci_allergens';
		$this->orders_table = 'ci_orders';
	}

	public function getColumnFieldArray($userData = []){
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where('column_name IN(' . $userData['column_name'] . ')');
		$this->db->where('user_id', $userData['user_id']);
		return $this->db->get()->result_array();
	}

	public function getAllergenParent($alID) {
        if($alID > 0){
            $this->db->select('b.name');
            $this->db->from($this->allergens_table.' AS a');
            $this->db->join($this->allergens_table.' AS b', 'a.parent_id = b.id', 'left');
            $this->db->where('a.id',$alID); 
            $this->db->group_by('a.parent_id'); 

            return $this->db->get()->row_array();
        }else{
            return array();
        }
    }

	public function getAllergensData($alID) {
        if($alID > 0){
            $this->db->select('name,code,can_allgy_env,fel_allgy_env,equ_allgy_env,can_allgy_food_ige,can_allgy_food_igg,fel_allgy_food_ige,fel_allgy_food_igg,equ_allgy_food_ige,equ_allgy_food_igg');
            $this->db->from($this->allergens_table);
            $this->db->where('id',$alID); 

            return $this->db->get()->row_array();
        }else{
            return array();
        }
    }

	function resetNextvuStatusLIMS($limsID){
		if($limsID !=''){
			if($_SERVER['SERVER_NAME'] == 'www.nano-staging-com.stackstaging.com' || $_SERVER['SERVER_NAME'] == 'nano-staging-com.stackstaging.com'){
				$lims_url = 'http://185.151.29.200:7070/Sample/ResetNextvuStatus?Ids='.$limsID.'&Status=1';
			}elseif($_SERVER['SERVER_NAME'] == 'www.nextmunelaboratories.com' || $_SERVER['SERVER_NAME'] == 'nextmunelaboratories.com'){
				$lims_url = 'http://185.151.29.200:7070/LiveSample/ResetNextvuStatus?Ids='.$limsID.'&Status=1';
			}else{
				$lims_url = 'http://185.151.29.200:7070/Sample/ResetNextvuStatus?Ids='.$limsID.'&Status=1';
			}
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
			curl_close($ch);
			if (isset($error_msg)) {
				$this->session->set_flashdata('error', $error_msg);
			}else{
				$results = json_decode($response,true);
			}
			return true;
		}
	}

	function callLIMSTransationAPI(){
		if($_SERVER['SERVER_NAME'] == 'www.nano-staging-com.stackstaging.com' || $_SERVER['SERVER_NAME'] == 'nano-staging-com.stackstaging.com'){
			$lims_url = 'http://185.151.29.200:7070/Sample/Inventory';
		}elseif($_SERVER['SERVER_NAME'] == 'www.nextmunelaboratories.com' || $_SERVER['SERVER_NAME'] == 'nextmunelaboratories.com'){
			$lims_url = 'http://185.151.29.200:7070/LiveSample/Inventory';
		}else{
			$lims_url = 'http://185.151.29.200:7070/Sample/Inventory';
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
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
		$response = curl_exec($curl);
		curl_close($curl);
		if (isset($error_msg)) {
			$this->session->set_flashdata('error', $error_msg);
		}else{
			return true;
		}
		return true;
	}

}