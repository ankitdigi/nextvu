<?php
class ReportsModel extends CI_model{

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

	public function getColumnAllArray($id){
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where('user_id', $id);
		return $this->db->get()->result_array();
	}

	public function getPracticeTableData(){
		$postData = $this->input->post();
		$this->db->select('ci_user_details.user_id AS id, ci_users.name, ci_users.last_name');
		$this->db->from($this->user_table);
		$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}
		$this->db->where('ci_users.role', 2);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.'. $columnName, $columnSortOrder);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();

		return $query;
	}

	public function getPracticeDetailTableData(){
		$postData = $this->input->post();
		$this->db->select('ci_user_details.user_id AS id, ci_users.name, ci_users.last_name');
		$this->db->from($this->user_table);
		$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		//$this->db->join('ci_orders', 'ci_orders.vet_user_id = ci_users.id','left');
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}
		if(!empty($postData['select_zones'])){
			$this->db->where_in('ci_users.managed_by_id', $postData['select_zones']);
		}
		$this->db->where('ci_users.role', 2);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.'. $columnName, $columnSortOrder);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();

		return $query;
	}

	public function count_practices_filtered(){
		$postData = $this->input->post();
		$this->db->select('ci_user_details.user_id AS id,GROUP_CONCAT(column_field) as column_field,ci_users.name, ci_user_details.column_field as postal_code, ci_users.last_name,ci_users.email');
		$this->db->from($this->user_table);
		$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}
		$this->db->where('ci_users.role', 2);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.'. $columnName, $columnSortOrder);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all(){
		return $this->db->count_all_results($this->_table);
	}

	public function getLabTableData(){
		$postData = $this->input->post();
		$this->db->select('ci_user_details.user_id AS id, ci_users.name, ci_users.last_name');
		$this->db->from($this->user_table);
		$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}
		$this->db->where('ci_users.role', 6);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.'. $columnName, $columnSortOrder);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();

		return $query;
	}

	public function count_labs_filtered(){
		$postData = $this->input->post();
		$this->db->select('ci_user_details.user_id AS id,GROUP_CONCAT(column_field) as column_field,ci_users.name, ci_user_details.column_field as postal_code, ci_users.last_name,ci_users.email');
		$this->db->from($this->user_table);
		$this->db->join($this->_table, 'ci_user_details.user_id = ci_users.id', 'left');
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if (!empty($postData['search']['value'])) {
			$this->db->group_start();
			$this->db->like('ci_users.name', $postData['search']['value']);
			$this->db->or_like('ci_users.last_name', $postData['search']['value']);
			$this->db->or_like('ci_user_details.column_field', $postData['search']['value']);
			$this->db->group_end();
		}
		$this->db->where('ci_users.role', 6);
		$this->db->group_by('ci_user_details.user_id');
		$this->db->order_by('ci_users.'. $columnName, $columnSortOrder);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getPracticeOrdernumber($id,$order_type){
		$order_type = !empty($order_type)?implode(',',$order_type):'1,2,3';
		$sql1 = "SELECT group_concat(distinct order_number) as order_numbers FROM ".$this->orders_table." WHERE vet_user_id = ".$id." AND is_confirmed IN(0,1,2,4)  AND order_type IN(".$order_type.") GROUP By vet_user_id";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->row()->order_numbers;

		return $result1;
	}

	public function getPracticeOrderTypeExcel($id,$order_type){
		$order_type = !empty($order_type)?implode(',',$order_type):'1,2,3';
		$sql1 = "SELECT  id,order_type,sub_order_type,product_code_selection FROM ".$this->orders_table." WHERE vet_user_id = ".$id." AND is_confirmed IN(0,1,2,4) AND order_type IN(".$order_type.")";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->result();
		$order_type_str = '';
		if(!empty($result1)){
			foreach($result1 as $value){
				$str = '';
				if ($value->order_type == 1) {
					$str = 'Immunotherapy';
				} elseif ($value->order_type == 2) {
					if(!empty($value->product_code_selection)){
						$this->db->select('name');
						$this->db->from('ci_price');
						$this->db->where('id', $value->product_code_selection);
						$ordeType = $this->db->get()->row()->name;
						$str = 'Serum Testing <b>('.$ordeType.')</b>';
					}else{
						$str = 'Serum Testing';
					}
				} else {
					$str = 'Skin Test';
				}
				$order_type_str .= $str.',';
			}
		}
		return $order_type_str;
	}

	public function getPracticeTotalSpent($id){
		$sql1 = "SELECT SUM(unit_price) as totalSpent FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '1' AND delivery_practice_id = '".$id."'";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->row()->totalSpent;

		$sql2 = "SELECT SUM(unit_price) as totalSpent FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '0' AND lab_id = 0 AND vet_user_id = '".$id."'";
		$query2 = $this->db->query($sql2);
		$result2 = $query1->row()->totalSpent;

		return $result1+$result2;
	}
	
	public function getPracticeOrderAllergens($id){
		$sql1 = "SELECT allergens FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '1' AND delivery_practice_id = '".$id."'";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->result();

		$sql2 = "SELECT allergens FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '0' AND lab_id = 0 AND vet_user_id = '".$id."'";
		$query2 = $this->db->query($sql2);
		$result2 = $query2->result();
		if(empty($result1) && empty($result2)){
			return '';
		}else{
			$allergensArr = array_merge($result1,$result2);
			$allergenIDArr = ''; $i=0;
			foreach($allergensArr as $row){
				$allergens = json_decode($row->allergens);
				if(!empty($allergens)){
					if($i==0){
						$allergenIDArr .= implode(",",$allergens);
					}else{
						$allergenIDArr .= ','.implode(",",$allergens);
					}
					$i++;
				}
			}
			$subArr = array_unique(explode(",",$allergenIDArr));
			sort($subArr);
			if(!empty($subArr)){
				$finalArr = implode(",",$subArr);
				$this->db->select('GROUP_CONCAT(name ORDER BY name SEPARATOR ", ") AS name');
				$this->db->from($this->allergens_table);
				$this->db->where('id IN('.$finalArr.')'); 

				return $this->db->get()->row()->name;
			}else{
				return '';
			}
		}
	}

	public function getPracticeOrderType($id){
		$sql1 = "SELECT order_type FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '1' AND delivery_practice_id = '".$id."'";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->result();

		$sql2 = "SELECT order_type FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND order_can_send_to = '0' AND lab_id = 0 AND vet_user_id = '".$id."'";
		$query2 = $this->db->query($sql2);
		$result2 = $query2->result();
		if(empty($result1) && empty($result2)){
			return '';
		}else{
			$allergensArr = array_merge($result1,$result2);
			if(!empty($allergensArr)){
				$typeArr = array();
				foreach($allergensArr as $row){
					$typeArr[] = $row->order_type;
				}
				$subArr = array_unique($typeArr);
				if(!empty($subArr)){
					$nameArr = array();
					if(in_array('1',$subArr)){
						$nameArr[] = 'Immunotherapy';
					}elseif(in_array('2',$subArr)){
						$nameArr[] = 'Serum Testing';
					}elseif(in_array('3',$subArr)){
						$nameArr[] = 'Artuvetrin® Skin Test';
					}
					if(!empty($nameArr)){
						return implode(", ",$nameArr);
					}else{
						return '';
					}
				}else{
					return '';
				}
			}else{
				return '';
			}
		}
	}

	public function getLabTotalSpent($id){
		$sql1 = "SELECT SUM(unit_price) as totalSpent FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND lab_id = ".$id." AND order_can_send_to = '0'";
		$query1 = $this->db->query($sql1);
		$result1 = $query1->row()->totalSpent;

		return $result1;
	}

	public function getLabOrderAllergens($id){
		$sql = "SELECT allergens FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND lab_id = ".$id." AND order_can_send_to = '0'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if(!empty($result)){
			$allergenIDArr = ''; $i=0;
			foreach($result as $row){
				$allergens = json_decode($row->allergens);
				if(!empty($allergens)){
					if($i==0){
						$allergenIDArr .= implode(",",$allergens);
					}else{
						$allergenIDArr .= ','.implode(",",$allergens);
					}
					$i++;
				}
			}
			$subArr = array_unique(explode(",",$allergenIDArr));
			sort($subArr);
			if(!empty($subArr)){
				$finalArr = implode(",",$subArr);
				$this->db->select('GROUP_CONCAT(name ORDER BY name SEPARATOR ", ") AS name');
				$this->db->from($this->allergens_table);
				$this->db->where('id IN('.$finalArr.')'); 

				return $this->db->get()->row()->name;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function getLabOrderType($id){
		$sql = "SELECT order_type FROM ".$this->orders_table." WHERE is_draft = '0' AND is_confirmed IN(0,1,2,4) AND lab_id = ".$id." AND order_can_send_to = '0'";
		$query = $this->db->query($sql);
		$result = $query->result();
		if(!empty($result)){
			$typeArr = array();
			foreach($result as $row){
				$typeArr[] = $row->order_type;
			}
			$subArr = array_unique($typeArr);
			if(!empty($subArr)){
				$nameArr = array();
				if(in_array('1',$subArr)){
					$nameArr[] = 'Immunotherapy';
				}elseif(in_array('2',$subArr)){
					$nameArr[] = 'Serum Testing';
				}elseif(in_array('3',$subArr)){
					$nameArr[] = 'Artuvetrin® Skin Test';
				}
				if(!empty($nameArr)){
					return implode(", ",$nameArr);
				}else{
					return '';
				}
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	function resetNextvuStatusLIMS($limsID){
		if($limsID !=''){
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => 'http://185.151.29.200:7070/LiveSample/ResetNextvuStatus?Ids='.$limsID.'&Status=1',
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

}