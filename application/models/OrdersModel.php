<?php
class OrdersModel extends CI_model{

	public function __construct(){
		parent::__construct();
		$this->_table = 'ci_orders';
		$this->serum_test_table = 'ci_serum_test';
		$this->userData = logged_in_user_data();
	}

	public function getRecord($id = ""){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $id);

		return $this->db->get()->row_array();
	}

	public function getRecordByvet($vet_user_id = ""){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('vet_user_id', $vet_user_id);

		return $this->db->get()->result_array();
	}

	public function getRecordByLab($lab_id = ""){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('lab_id', $lab_id);

		return $this->db->get()->result_array();
	}

	public function get_order_number(){
		$this->db->select('MAX(order_number) AS order_number');
		$this->db->from($this->_table);

		return $this->db->get()->row_array();
	}

	public function allData($id = "", $order_number = ""){
		$this->db->select('ci_orders.*, petOwner.name AS pet_owner_name, petOwner.last_name AS po_last, 
		ci_pets.name AS pet_name,
		practice.name AS practice_name,
		practice.last_name AS practice_last_name,
		practice.email AS practice_email,
		practice.country AS practice_country,
		ci_species.name AS species_name,
		branch.number AS branch_number,
		branch.address AS branch_address,
		branch.address1 AS branch_address1,
		branch.address2 AS branch_address2,
		branch.address3 AS branch_address3,
		branch.town_city AS branch_town_city,
		branch.county AS branch_county,
		branch.postcode AS branch_postcode,
		branch.name AS branch_name,
		branch.customer_number As branch_customer_number,
		lab.name AS lab_name,
		lab.email AS lab_email,
		corporate.name AS corporate_name,
		corporate.email AS corporate_email,
		delivery_practice.name AS delivery_practice_name,
		delivery_practice.last_name AS delivery_practice_last_name,
		delivery_branch.number AS delivery_branch_number,
		delivery_branch.address AS delivery_branch_address,
		delivery_branch.address1 AS delivery_branch_address1,
		delivery_branch.address2 AS delivery_branch_address2,
		delivery_branch.address3 AS delivery_branch_address3,
		delivery_branch.town_city AS delivery_branch_town_city,
		delivery_branch.county AS delivery_branch_county,
		delivery_branch.postcode AS delivery_branch_postcode,
		delivery_branch.name AS delivery_branch_name,
		delivery_branch.customer_number');

		$this->db->from($this->_table);
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id', 'left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		$this->db->join('ci_users as corporate', 'ci_orders.corporate_id = practice.id', 'left');
		$this->db->join('ci_users as delivery_practice', 'ci_orders.delivery_practice_id = delivery_practice.id', 'left');
		$this->db->join('ci_branches as branch', 'ci_orders.branch_id = branch.id', 'left');
		$this->db->join('ci_branches as delivery_branch', 'ci_orders.delivery_practice_branch_id = delivery_branch.id', 'left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id', 'left');
		$this->db->join('ci_species', 'ci_species.id = ci_pets.type', 'left');
		if ($id) {
			$this->db->where('ci_orders.id', $id);
		} else if ($order_number) {
			$this->db->where('ci_orders.order_number', $order_number);
		}

		return $this->db->get()->row_array();
	}

	public function getSerumTestRecord($id = ""){
		$this->db->select('*');
		$this->db->from($this->serum_test_table);
		$this->db->where('order_id', $id);

		return $this->db->get()->row_array();
	}

	public function add_edit($orderData = []){
		if (isset($orderData['id']) && is_numeric($orderData['id']) > 0) {
			$this->db->where('id', $orderData['id']);
			$update =  $this->db->update($this->_table, $orderData);
			if ($update) {
				return $this->db->affected_rows();
			} else {
				return false;
			}
		} else {
			if (isset($orderData) && count($orderData) > 0) {
				$this->db->insert($this->_table, $orderData);
				return $order_id = $this->db->insert_id();
			} else {
				return $order_id = null;
			}
		}
	}

	public function add_editinterpretation($interpretationData = []){
		$this->db->select('order_id');
        $this->db->from('ci_order_interpretation');
        $this->db->where('order_id',$interpretationData['order_id']);
        $query = $this->db->get();
        if($query->num_rows() > 0){
			$this->db->where('order_id', $interpretationData['order_id']);
			$this->db->update('ci_order_interpretation', $interpretationData);
		}else{
			if (isset($interpretationData) && count($interpretationData) > 0) {
				$this->db->insert('ci_order_interpretation', $interpretationData);
				$this->db->insert_id();
			}
		}
	}

	public function add_comment($orderData = []){
		if (isset($orderData['id']) && is_numeric($orderData['id']) > 0) {
			$this->db->where('id' , $orderData['id']);
			$update = $this->db->update($this->_table, $orderData);
			if ($update) {
				return $this->db->affected_rows();
			} else {
				return false;
			}
		}
	}

	public function get_comment($id = ''){
		if (isset($id) && is_numeric($id) > 0) {
			$this->db->select('*');
			$this->db->from($this->_table);
			$this->db->where('id' , $id);
			return $this->db->get()->row_array();
		}
	}

	public function IsDraftUpdate($id){
		$orderData = array('is_draft' => 0);    
		$this->db->where('id' , $id);
		$update = $this->db->update($this->_table, $orderData);
		if ($update) {
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}

	public function serum_test_add_edit($orderData = []){
		if (isset($orderData['id']) && is_numeric($orderData['id']) > 0) {
			$this->db->where('id', $orderData['id']);
			$update =  $this->db->update($this->serum_test_table, $orderData);
			if ($update) {
				return $this->db->affected_rows();
			} else {
				return false;
			}
		} else {
			if (isset($orderData) && count($orderData) > 0) {
				$this->db->insert($this->serum_test_table, $orderData);
				return $order_id = $this->db->insert_id();
			} else {
				return $order_id = null;
			}
		}
	}

	function delete($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete($this->_table);
		}
	}

	public function Hold($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$confirmData['is_confirmed'] = '2';
			$this->db->where('id', $data['id']);
			return $this->db->update($this->_table, $confirmData); 
		}
	}

	public function UnHold($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$confirmData['is_confirmed'] = '0';
			$this->db->where('id', $data['id']);
			return $this->db->update($this->_table, $confirmData); 
		}
	}

	public function Cancel($data = []){
		if (isset($data['id']) && $data['id'] != '') {
			$confirmData['is_confirmed'] = '3';
			$this->db->where('id', $data['id']);
			return $this->db->update($this->_table, $confirmData); 
		}
	}

	function repeatOrder($order_id){
		$this->db->where('id', $order_id);
		$query = $this->db->get($this->_table);
		foreach ($query->result() as $row) {
			foreach ($row as $key => $val) {
				if ($key != 'id') {
					if ($key == 'order_number') {
						$order_number = $this->get_order_number();
						if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
							$final_order_number = 1001;
						} else {
							$final_order_number = $order_number['order_number'] + 1;
						}
						$val = $final_order_number;
					}
					if ($key == 'sic_document') {
						$val = $this->session->userdata('sic_document');
					}
					if ($key == 'email_upload') {
						$val = $this->session->userdata('repeat_email_upload');
					}
					if ($key == 'signature') {
						$val = $this->session->userdata('signature');
					}
					if ($key == 'batch_number') {
						$val = $this->session->userdata('batch_number');
					}
					if ($key == 'final_price') {
						$val = $this->session->userdata('final_price');
					}
					if ($key == 'is_confirmed') {
						$val = 0;
					}
					if ($key == 'name') {
						$val = $this->session->userdata('repeat_your_name');
					}
					if ($key == 'email') {
						$val = $this->session->userdata('repeat_your_email');
					}
					if ($key == 'phone_number') {
						$val = $this->session->userdata('repeat_your_phone_number');
					}
					if ($key == 'order_can_send_to') {
						$val = $this->session->userdata('repeat_order_can_send_to');
					}
					if ($key == 'delivery_practice_id') {
						$val = $this->session->userdata('repeat_delivery_practice_id');
					}
					if ($key == 'delivery_practice_branch_id') {
						$val = $this->session->userdata('repeat_delivery_practice_branch_id');
					}
					if ($key == 'address1') {
						$val = $this->session->userdata('repeat_address1');
					}
					if ($key == 'address2') {
						$val = $this->session->userdata('repeat_address2');
					}
					if ($key == 'address3') {
						$val = $this->session->userdata('repeat_address3');
					}
					if ($key == 'address4') {
						$val = $this->session->userdata('repeat_address4');
					}
					if ($key == 'town_city') {
						$val = $this->session->userdata('repeat_town_city');
					}
					if ($key == 'county') {
						$val = $this->session->userdata('repeat_county');
					}
					if ($key == 'country') {
						$val = $this->session->userdata('repeat_country');
					}
					if ($key == 'postcode') {
						$val = $this->session->userdata('repeat_postcode');
					}
					if ($key == 'pet_owner_id') {
						$val = $this->session->userdata('repeat_pet_owner_id');
					}
					if ($key == 'pet_id') {
						$val = $this->session->userdata('repeat_pet_id');
					}
					if ($key == 'is_repeat_order') {
						$val = '1';
					}
					if ($key == 'allergens') {
						$val = $this->session->userdata('repeat_allergens');
					}
					if ($key == 'is_invoiced') {
						$val = '0';
					}
					if ($key == 'order_date') {
						$val = date("Y-m-d");
					}
					if ($key == 'created_at') {
						$val = date("Y-m-d H:i:s");
					}
					if ($key == 'updated_by' || $key == 'updated_at') {
						$val = NULL;
					}
					$this->db->set($key, $val);
				} //endif 
			} //endforeach 
		} //endforeach 
		$this->db->insert($this->_table);
		return $this->db->insert_id();
	}

	public function confirmOrder($order_id){
		$confirmData = [];
		if (isset($order_id) && $order_id != '') {
			$confirmData['is_confirmed'] = '1';
			$this->db->where('id IN(' . $order_id . ')');
			$update =  $this->db->update($this->_table, $confirmData);
			return true;
		}
	}

	public function freeField($order_id){
		$data = [];
		if (isset($order_id) && $order_id != '') {
			$data['sic_document'] = NULL;
			$this->db->where('id', $order_id);
			$update =  $this->db->update($this->_table, $data);
			return true;
		}
	}

	/* data table functions */
	public function getTableData(){
		$this->_get_datatables_query();
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		if(!empty($rowperpage)){
			$this->db->limit($rowperpage, $row);
		}
		$query = $this->db->get()->result();
		return $query;
	}

	private function _get_datatables_query(){
		$postData = $this->input->post();
		$this->db->select('ci_orders.*,petOwner.name AS pet_owner_name, petOwner.last_name AS po_last,ci_pets.name as pet_name,ci_pets.breed_id,ci_pets.other_breed,is_mail_sent,ci_branches.name as practice_name,practice.name AS practice_first_name,practice.last_name AS practice_last_name,practice.country AS practice_country,lab.name AS lab_name,lab.country AS lab_country');
		$this->db->from($this->_table);	
		$this->db->where('ci_orders.is_draft' , 0);
		$this->db->join('ci_users', 'ci_orders.pet_owner_id = ci_users.id', 'left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id', 'left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id', 'left');
		$this->db->join('ci_branches', 'ci_orders.branch_id = ci_branches.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		if(!empty($postData['formData'])){
			parse_str($postData['formData'], $filterData);
		}

		if($this->zones != ""){
			$this->db->where('(CONCAT(",", practice.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .')," OR CONCAT(",", lab.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),")');
		}
		//sorting
		if (!empty($postData['search']['value'])) {
			if (strstr($postData['search']['value'], '/')) {
				$date_val = str_replace("/", "-", $postData['search']['value']);
				$date_val = date("Y-m-d", strtotime($date_val));
				$this->db->where('ci_orders.order_date', $date_val);
			} else {
				$this->db->group_start();
				$this->db->like('ci_orders.order_type', $postData['search']['value']);
				$this->db->or_like('ci_orders.order_number', $postData['search']['value']);
				$this->db->or_like('ci_orders.reference_number', $postData['search']['value']);
				$this->db->or_like('ci_orders.purchase_order_number', $postData['search']['value']);
				if ($postData['search']['value'] == "Immunotherapy") {
					$this->db->or_like('ci_orders.order_type', 1);
				} elseif ($postData['search']['value'] == "Serum Testing") {
					$this->db->or_like('ci_orders.order_type', 2);
				} elseif ($postData['search']['value'] == "Skin Test") {
					$this->db->or_like('ci_orders.order_type', 3);
				}
				$this->db->or_like('ci_orders.unit_price', $postData['search']['value']);
				$this->db->or_like('ci_pets.name', $postData['search']['value']);
				$this->db->or_like('petOwner.name', $postData['search']['value']);
				$this->db->or_like('petOwner.last_name', $postData['search']['value']);
				$this->db->or_like('practice.name', $postData['search']['value']);
				$this->db->or_like('ci_orders.batch_number', $postData['search']['value']);
				$this->db->or_like('ci_orders.lab_order_number', $postData['search']['value']);
				$this->db->group_end();
			}
		}

		if ($this->session->userdata('orderFilterId')) {
			$this->db->where('ci_orders.vet_user_id', $this->session->userdata('orderFilterId'));
		}

		if (!empty($filterData['is_confirmed'])) {
			$this->db->where('ci_orders.is_confirmed', '0');
		}

		if (!empty($filterData['order_type'])) {
			$this->db->where('ci_orders.order_type', $filterData['order_type']);
		}

		if (!empty($filterData['serum_type'])) {
			$this->db->where('ci_orders.serum_type', $filterData['serum_type']);
		}

		if (!empty($filterData['order_status'])) {
			if($filterData['order_status'] == '1'){
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.send_Exact', 0);
			}elseif($filterData['order_status'] == '101'){
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.send_Exact', 1);
			}elseif($filterData['order_status'] == '102'){
				$this->db->where('ci_orders.is_raptor_result', 0);
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.lab_order_number !=', '');
			}elseif($filterData['order_status'] == '111'){
				$this->db->where('ci_orders.is_raptor_result', 0);
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.lab_order_number', '');
			}elseif($filterData['order_status'] == '99'){
				$this->db->where('ci_orders.is_confirmed', 0);
				$this->db->where('ci_orders.is_invoiced', 0);
			}elseif($filterData['order_status'] == '8'){
				$this->db->where('ci_orders.is_invoiced', 1);
			}elseif($filterData['order_status'] == '11'){
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.is_raptor_result', 1);
			}elseif($filterData['order_status'] == '12'){
				$this->db->where('ci_orders.is_confirmed', 1);
				$this->db->where('ci_orders.is_authorised', 1);
			}elseif($filterData['order_status'] == '42'){
				$this->db->where('ci_orders.is_confirmed', 4);
				$this->db->where('ci_orders.is_authorised', 2);
				$this->db->where('ci_orders.is_invoiced', 0);
			}else{
				$this->db->where('ci_orders.is_confirmed', $filterData['order_status']);
			}
		}

		if (!empty($filterData['managed_by_id'])){
			$this->db->where('(CONCAT(",", practice.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$filterData['managed_by_id']) .')," OR CONCAT(",", lab.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$filterData['managed_by_id']) .'),")');
		}

		if ($this->userData['role'] == '2') {
			$this->db->where('ci_orders.vet_user_id', $this->userData['user_id']);
		}

		if ($this->userData['role'] == '5' || $this->userData['role'] == '6' || $this->userData['role'] == '7') {
			$userIds = $this->getAlluserIdbyrole($this->userData['user_id']);
			$LabDetails = array_column($userIds, 'column_field', 'column_name');
			$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
			$branches = !empty($LabDetails['branches']) ? json_decode($LabDetails['branches']) : 0;
			$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;
			if($practices != 0){
				$this->db->where('ci_orders.vet_user_id IN('.implode(",",$practices).')');
				$this->db->where('ci_orders.lab_id',0);
			}elseif($branches != 0){
				$this->db->where('ci_orders.branch_id IN('.implode(",",$branches).')');
			}elseif($labs != 0){
				$this->db->where('ci_orders.lab_id IN('.implode(",",$labs).')');
			}else{
				$this->db->where('ci_orders.created_by', $this->userData['user_id']);
			}
		}

		if (!empty($filterData['dashboard_confirmed_list']) && $filterData['dashboard_confirmed_list'] == 'yes') {
			$this->db->where('ci_orders.is_confirmed', '1');
		}

		if ((!empty($filterData['filter_order_date']) && $filterData['filter_order_date'] != '') || ($this->session->userdata('filter_order_date') != '')) {
			if (!empty($filterData['filter_order_date']) && $filterData['filter_order_date'] != '') {
				$request_str = $filterData['filter_order_date'];
			} else if ($this->session->userdata('filter_order_date') != '') {
				$request_str = $this->session->userdata('filter_order_date');
			}

			$filter_order_date = explode(' - ', $request_str);
			$start_date_str = str_replace('/', '-', $filter_order_date[0]);
			$start_date = date('Y-m-d', strtotime($start_date_str));
			$end_date_str = str_replace('/', '-', $filter_order_date[1]);
			$end_date = date('Y-m-d', strtotime($end_date_str));

			$this->db->where('ci_orders.order_date >=', $start_date);
			$this->db->where('ci_orders.order_date <=', $end_date);

			if ($this->session->userdata('filter_order_date') != '') {
				$this->session->unset_userdata('filter_order_date');
			}
		}

		if ($this->userData['role'] == '10') {
			$this->db->where('ci_orders.order_type', '2');
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.lab_order_number', '');
		}
		$columnName = '';
		if(!empty($postData)){
			$columnIndex = $postData['order'][0]['column'];
			$columnName = $postData['columns'][$columnIndex]['data'];
			$columnSortOrder = $postData['order'][0]['dir'];
		}

		if($columnName == 'pet_owner_name'){
			$this->db->order_by('petOwner.last_name', $columnSortOrder);
		}elseif($columnName == 'pet_name'){
			$this->db->order_by('ci_pets.name', $columnSortOrder);
		}elseif($columnName == 'final_name'){
			$this->db->order_by('practice.name', $columnSortOrder);
		}else{
			if(!empty($columnName)){
				$this->db->order_by('ci_orders.' . $columnName, $columnSortOrder);
			}else{
				$this->db->order_by("ci_orders.id", "DESC");
			}
		}

		if ((!empty($filterData['dashboard_latest_list']) && $filterData['dashboard_latest_list'] == 'yes') || (!empty($filterData['dashboard_confirmed_list']) && $filterData['dashboard_confirmed_list'] == 'yes')) {
			$this->db->limit(5);
		}
	}

	public function getTableDataOrder($id,$otype,$sdate,$edate){
		$this->_get_datatables_query_all_orders($id,$otype,$sdate,$edate);
		$query = $this->db->get()->result_array();
		return $query;
	}

	private function _get_datatables_query_all_orders($mid,$otype,$sdate,$edate){
		$postData = $this->input->post();
		$this->db->select('ci_orders.id,ci_orders.lab_id,ci_orders.vet_user_id,ci_orders.order_type,ci_orders.unit_price,ci_orders.shipping_cost,ci_orders.pet_id,ci_orders.pet_owner_id,ci_orders.order_number,ci_orders.order_date,ci_orders.is_confirmed,ci_orders.is_invoiced,ci_orders.is_authorised,ci_orders.is_raptor_result,ci_orders.send_Exact,ci_orders.updated_at,ci_orders.shipping_date,ci_orders.product_code_selection,ci_orders.cep_id,ci_orders.serum_type,ci_orders.species_selection,ci_orders.lab_order_number,petOwner.name AS pet_owner_name, petOwner.last_name AS po_last,ci_pets.name as pet_name,ci_pets.breed_id,ci_pets.other_breed,is_mail_sent,practice.name AS practice_first_name,practice.last_name AS practice_last_name,practice.managed_by_id AS practice_managed_by,practice.country AS practice_country,lab.name AS lab_name,lab.country AS lab_country,lab.managed_by_id AS lab_managed_by,ci_breeds.name as breed_name,ci_price.name as product_type,ci_order_history.created_at as created_history');
		$this->db->from($this->_table);	
		$this->db->where('ci_orders.is_draft' , 0);
		$this->db->join('ci_users', 'ci_orders.pet_owner_id = ci_users.id', 'left');
		$this->db->join('ci_price', 'ci_orders.product_code_selection = ci_price.id', 'left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id', 'left');
		$this->db->join('ci_breeds', 'ci_pets.breed_id = ci_breeds.id', 'left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		$this->db->join('ci_order_history', 'ci_orders.id = ci_order_history.order_id', 'left');
		if($this->zones != ""){
			$this->db->where('(CONCAT(",", practice.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .')," OR CONCAT(",", lab.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$this->zones) .'),")');
		}
		if($mid > 0){
			$this->db->where('(CONCAT(",", practice.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$mid) .')," OR CONCAT(",", lab.managed_by_id, ",") REGEXP ",('. str_replace(",","|",$mid) .'),")');
		}
		if($otype != ''){
			$this->db->where('ci_orders.order_type', $otype);
		}
		if ($this->session->userdata('orderFilterId')) {
			$this->db->where('ci_orders.vet_user_id', $this->session->userdata('orderFilterId'));
		}
		if ($this->userData['role'] == '2') {
			$this->db->where('ci_orders.vet_user_id', $this->userData['user_id']);
		}

		if ($this->userData['role'] == '5' || $this->userData['role'] == '6' || $this->userData['role'] == '7') {
			$userIds = $this->getAlluserIdbyrole($this->userData['user_id']);
			$LabDetails = array_column($userIds, 'column_field', 'column_name');
			$practices = !empty($LabDetails['practices']) ? json_decode($LabDetails['practices']) : 0;
			$branches = !empty($LabDetails['branches']) ? json_decode($LabDetails['branches']) : 0;
			$labs = !empty($LabDetails['labs']) ? json_decode($LabDetails['labs']) : 0;
			if($practices != 0){
				$this->db->where('ci_orders.vet_user_id IN('.implode(",",$practices).')');
				$this->db->where('ci_orders.lab_id',0);
			}elseif($branches != 0){
				$this->db->where('ci_orders.branch_id IN('.implode(",",$branches).')');
			}elseif($labs != 0){
				$this->db->where('ci_orders.lab_id IN('.implode(",",$labs).')');
			}else{
				$this->db->where('ci_orders.created_by', $this->userData['user_id']);
			}
		}

		if (!empty($filterData['dashboard_confirmed_list']) && $filterData['dashboard_confirmed_list'] == 'yes') {
			$this->db->where('ci_orders.is_confirmed', '1');
		}

		if ((!empty($filterData['filter_order_date']) && $filterData['filter_order_date'] != '') || ($this->session->userdata('filter_order_date') != '')) {
			if (!empty($filterData['filter_order_date']) && $filterData['filter_order_date'] != '') {
				$request_str = $filterData['filter_order_date'];
			} else if ($this->session->userdata('filter_order_date') != '') {
				$request_str = $this->session->userdata('filter_order_date');
			}

			$filter_order_date = explode(' - ', $request_str);
			$start_date_str = str_replace('/', '-', $filter_order_date[0]);
			$start_date = date('Y-m-d', strtotime($start_date_str));
			$end_date_str = str_replace('/', '-', $filter_order_date[1]);
			$end_date = date('Y-m-d', strtotime($end_date_str));

			$this->db->where('ci_orders.order_date >=', $start_date);
			$this->db->where('ci_orders.order_date <=', $end_date);

			if ($this->session->userdata('filter_order_date') != '') {
				$this->session->unset_userdata('filter_order_date');
			}
		}

		if ($this->userData['role'] == '10') {
			$this->db->where('ci_orders.order_type', '2');
			$this->db->where('ci_orders.is_confirmed', '1');
			$this->db->where('ci_orders.lab_order_number', '');
		}
		$columnName = '';
		if(!empty($postData)){
			$columnIndex = $postData['order'][0]['column'];
			$columnName = $postData['columns'][$columnIndex]['data'];
			$columnSortOrder = $postData['order'][0]['dir'];
		}
		if($sdate != '' && $edate != ''){
			$this->db->where('DATE(ci_order_history.created_at) >=', $sdate);
			$this->db->where('DATE(ci_order_history.created_at) <=', $edate);
		}
		if($otype == '1' || $otype == '3'){
			$this->db->where('ci_order_history.text LIKE', 'Shipped');
		}
		if($otype == '2'){
			$this->db->group_start();
			$this->db->like('ci_order_history.text', 'Reported');
			$this->db->or_like('ci_order_history.text', 'Authorised Confirmed');
			$this->db->group_end();
		}
		$this->db->group_by('ci_orders.id');
		if($columnName == 'pet_owner_name'){
			$this->db->order_by('petOwner.last_name', $columnSortOrder);
		}elseif($columnName == 'pet_name'){
			$this->db->order_by('ci_pets.name', $columnSortOrder);
		}elseif($columnName == 'final_name'){
			$this->db->order_by('practice.name', $columnSortOrder);
		}else{
			if(!empty($columnName)){
				$this->db->order_by('ci_orders.' . $columnName, $columnSortOrder);
			}else{
				$this->db->order_by("ci_orders.id", "DESC");
			}
		}
	}

	public function getAlluserIdbyrole($role) {
		$sql = "SELECT * FROM ci_user_details WHERE column_name IN('practices','branches','labs','lab_branches','corporates','corporate_branches') AND user_id = '". $role ."'";
        $responce = $this->db->query($sql);
		$result = $responce->result_array();

		return $result;
	}

	public function count_all(){
		return $this->db->count_all_results($this->_table);
	}

	public function count_filtered(){
		$this->_get_datatables_query();
		$query = $this->db->get();

		return $query->num_rows();
	}
	//datatable functions

	public function getexistShippingCost($order_id) {
		$sql = "SELECT shipping_cost FROM ci_orders WHERE id = '".$order_id."'";
        $responce = $this->db->query($sql);
		$result = $responce->row()->shipping_cost;

		return $result;
	}

	public function checkUserOrderToday($practice_id) {
		$sql = "SELECT COUNT(id) as TotalOrder FROM ci_orders WHERE order_date = '". date("Y-m-d") ."' AND (lab_id = '".$practice_id."' OR vet_user_id = '".$practice_id."') AND is_draft = 0";
        $responce = $this->db->query($sql);
		$result = $responce->row()->TotalOrder;

		return $result;
	}

	public function checkDeliveryUserOrderToday($practice_id) {
		$sql = "SELECT COUNT(id) as TotalOrder FROM ci_orders WHERE order_date = '". date("Y-m-d") ."' AND delivery_practice_id = '".$practice_id."' AND is_draft = 0";
        $responce = $this->db->query($sql);
		$result = $responce->row()->TotalOrder;

		return $result;
	}

	public function checkLabUserOrderToday($practice_id) {
		$sql = "SELECT COUNT(id) as TotalOrder FROM ci_orders WHERE order_date = '". date("Y-m-d") ."' AND lab_id = '".$practice_id."' AND is_draft = 0";
        $responce = $this->db->query($sql);
		$result = $responce->row()->TotalOrder;

		return $result;
	}

	public function checkVetUserOrderToday($practice_id) {
		$sql = "SELECT COUNT(id) as TotalOrder FROM ci_orders WHERE order_date = '". date("Y-m-d") ."' AND vet_user_id = '".$practice_id."' AND is_draft = 0";
        $responce = $this->db->query($sql);
		$result = $responce->row()->TotalOrder;

		return $result;
	}

	public function getShippingCostbyUser($id, $practice_id) {
		$this->db->select('id,uk_discount,roi_discount');
        $this->db->from('ci_user_shipping');
        $this->db->where('shipping_id',$id);  
        $this->db->where('practice_id',$practice_id);  
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
	}

	public function getDefaultShippingCost($id) {
		$this->db->select('id,uk_price,roi_price');
        $this->db->from('ci_shipping_price');
        $this->db->where('id',$id);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
	}

	public function add_edit_vials($orderData = []){
		if (isset($orderData['vial_id']) && is_numeric($orderData['vial_id']) > 0) {
			$this->db->where('vial_id', $orderData['vial_id']);
			$update =  $this->db->update('ci_allergens_vials', $orderData);
			if ($update) {
				return $this->db->affected_rows();
			} else {
				return false;
			}
		} else {
			if (isset($orderData) && count($orderData) > 0) {
				$this->db->insert('ci_allergens_vials', $orderData);
				return $vial_id = $this->db->insert_id();
			} else {
				return $vial_id = null;
			}
		}
	}

	public function Totalvials($id) {
		$sql = "SELECT COUNT(vial_id) as totalVials FROM ci_allergens_vials WHERE order_id = '". $id ."'";
        $responce = $this->db->query($sql);
		$result = $responce->row()->totalVials;

		return $result;
	}

	public function getVialslist($vid,$id) {
		$this->db->select('*');
        $this->db->from('ci_allergens_vials');
        $this->db->where('order_id',$id);  
        $this->db->where('vials_order',$vid);  
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
	}

	public function getVialslistAllenges($id) {
		$this->db->select('GROUP_CONCAT(allergens) as allergens');
        $this->db->from('ci_allergens_vials');
        $this->db->where('order_id',$id);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row_array();
        }else{
            return array();
        }
	}

	public function addOrderHistory($orderData = []){
		if (isset($orderData) && count($orderData) > 0) {
			$this->db->insert('ci_order_history', $orderData);
			return $order_id = $this->db->insert_id();
		} else {
			return $order_id = null;
		}
	}

	public function getPetbreeds($petId = ""){
		$this->db->select('ci_breeds.name AS breed_name');
		$this->db->from('ci_pets');
		$this->db->join('ci_breeds', 'ci_pets.breed_id = ci_breeds.id', 'left');
		$this->db->where('ci_pets.id', $petId);

		return $this->db->get()->row()->breed_name;
	}

	public function getOrderHistory($id){
		$this->db->select('text,created_by,created_at');
		$this->db->from('ci_order_history');
		$this->db->where('order_id', $id);

		return $this->db->get()->result();
	}

	public function getLastOrderHistory($id){
		$this->db->select('created_at');
		$this->db->from('ci_order_history');
		$this->db->where('order_id', $id);
		$this->db->order_by("created_at", "DESC");
		$this->db->limit(1, 0);
		return $this->db->get()->row();
	}

	public function getOrderHistoryforReport($id){
		$this->db->select('created_at');
		$this->db->from('ci_order_history');
		$this->db->where('order_id', $id);
		$this->db->where('text LIKE', 'Shipped');
		$this->db->or_where('text LIKE', 'Reported');
		$this->db->order_by("created_at", "DESC");
		$this->db->limit(1, 0);
		return $this->db->get()->row();
	}

	public function getUserDatabyId($uId){
		$this->db->select('name');
		$this->db->from('ci_users');
		$this->db->where('id', $uId);
		return $this->db->get()->row()->name;
	}

	public function getProductInfo($pId){
		$this->db->select('id,name,product_info');
		$this->db->from('ci_price');
		$this->db->where('id', $pId);
		return $this->db->get()->row();
	}

	public function getSerumTestType($id){
		$this->db->select('sr.result_id, sr.limsId, rt.type_id, rt.limsTestCode, rt.testName');
		$this->db->from('ci_serum_result as sr');
		$this->db->join('ci_serum_result_type as rt', 'sr.result_id = rt.result_id', 'left');
		$this->db->where('nextVuId', $id);
		$this->db->where('sr.sampleStatus', 'Authorised');
		$this->db->where('rt.testStatus', 'Authorised');
		$this->db->where("rt.limsTestCode NOT IN('HAEMOLYSED','LIPOLYSED','OTHER_QC')");
		return $this->db->get()->result();
	}

	public function getAllergensName($limsIdid,$allergensData = []){
		$allergensArr = json_decode($allergensData);
		$allergens = implode(",",$allergensArr);
		if($allergens != ""){
			$sql = "SELECT id, name, parent_id FROM ci_allergens WHERE id IN(".$allergens.") AND (can_allgy_env = '".$limsIdid."' OR fel_allgy_env = '".$limsIdid."' OR equ_allgy_env = '".$limsIdid."' OR can_allgy_food_ige = '".$limsIdid."' OR can_allgy_food_igg = '".$limsIdid."' OR fel_allgy_food_ige = '".$limsIdid."' OR fel_allgy_food_igg = '".$limsIdid."' OR equ_allgy_food_ige = '".$limsIdid."' OR equ_allgy_food_igg = '".$limsIdid."')";
		}else{
			$sql = "SELECT id, name, parent_id FROM ci_allergens WHERE (can_allgy_env = '".$limsIdid."' OR fel_allgy_env = '".$limsIdid."' OR equ_allgy_env = '".$limsIdid."' OR can_allgy_food_ige = '".$limsIdid."' OR can_allgy_food_igg = '".$limsIdid."' OR fel_allgy_food_ige = '".$limsIdid."' OR fel_allgy_food_igg = '".$limsIdid."' OR equ_allgy_food_ige = '".$limsIdid."' OR equ_allgy_food_igg = '".$limsIdid."')";
		}
        $responce = $this->db->query($sql);
		$results = $responce->row();

		return $results;
	}

	public function getSerumTestResult($rID,$tID){
		$this->db->select('*');
		$this->db->from('ci_serum_result_allergens');
		$this->db->where('result_id IN('.$rID.')');
		$this->db->where('type_id IN('.$tID.')');
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result();
	}

	public function getSerumTestResultEnv($rID,$tID){
		$this->db->select('*');
		$this->db->from('ci_serum_result_allergens');
		$this->db->where('result_id IN('.$rID.')');
		$this->db->where('type_id IN('.$tID.')');
		$this->db->where('limsTestCode NOT LIKE','%FOOD%');
		$this->db->where('category NOT LIKE','%FOOD%');
		$this->db->where('category !=','');
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result();
	}

	public function getSerumTestResultFood($rID,$tID){
		$sql = "SELECT name, lims_allergens_id FROM ci_serum_result_allergens WHERE result_id IN(".$rID.") AND type_id IN(".$tID.") AND (limsTestCode LIKE '%FOOD_IGE%' OR limsTestCode LIKE '%FOOD_IGG%') GROUP BY `name` ORDER BY `name` ASC";
        $responce = $this->db->query($sql);
		$results = $responce->result();

		return $results;
	}

	public function getSerumTestResultFoodIGE($name,$rID,$tID){
		$sql = 'SELECT * FROM ci_serum_result_allergens WHERE result_id IN('.$rID.') AND type_id IN('.$tID.') AND name LIKE "'.$name.'" AND (limsTestCode LIKE "%FOOD_IGE%" OR category LIKE "FOOD" OR category LIKE "2227")';
		$responce = $this->db->query($sql);
		$results = $responce->row();

		return $results;
	}

	public function getSerumTestResultFoodIGG($name,$rID,$tID){
		$this->db->select('*');
		$this->db->from('ci_serum_result_allergens ');
		$this->db->where('result_id IN('.$rID.')');
		$this->db->where('type_id IN('.$tID.')');
		$this->db->where('name LIKE',$name);
		$this->db->where('limsTestCode LIKE','%FOOD_IGG%');
		return $this->db->get()->row();
	}

	function serumOrderforImmuno($order_id){
		$this->db->where('id', $order_id);
		$query = $this->db->get($this->_table);
		$results = $query->row();
		$orderData['cep_id'] = $results->id;
		$orderData['vet_user_id'] = $results->vet_user_id;
		$orderData['pet_owner_id'] = $results->pet_owner_id;
		$orderData['pet_id'] = $results->pet_id;
		$orderData['lab_id'] = $results->lab_id;
		$orderData['name'] = $results->name;
		$orderData['email'] = $results->email;
		$orderData['phone_number'] = $results->phone_number;
		$orderData['order_type'] = 1;
		$orderData['sub_order_type'] = 1;
		if($orderData['lab_id']>0){
			$orderData['plc_selection'] = 2;
		}else{
			$orderData['plc_selection'] = 1;
		}
		$orderData['species_selection'] = $results->species_selection;
		$order_number = $this->get_order_number();
		if ($order_number['order_number'] == '' || $order_number['order_number'] == NULL || $order_number['order_number'] == 0) {
			$final_order_number = 1001;
		} else {
			$final_order_number = $order_number['order_number'] + 1;
		}
		$orderData['order_number'] = $final_order_number;
		$orderData['order_date'] = date("Y-m-d");
		$orderData['is_mail_sent'] = 0;
		$orderData['is_confirmed'] = 0;
		$orderData['order_can_send_to'] = 0;
		$orderData['delivery_practice_id'] = 0;
		$orderData['is_draft'] = 1;
		$orderData['is_invoiced'] = 0;
		$orderData['created_by'] = $this->user_id;
		$orderData['created_at'] = date("Y-m-d H:i:s");
		$orderData['updated_by'] = NULL;
		$orderData['updated_at'] = NULL;
		$this->db->insert($this->_table, $orderData);
		return $order_id = $this->db->insert_id();
	}

	public function getAllergensData($allergensName){
        $this->db->select('id, code');
		$this->db->from('ci_allergens');
		$this->db->where("name LIKE '%".$allergensName."%'");
		$responce = $this->db->get();
		$result = $responce->row();

		return $result;
    }

	public function getRaptorData($id){
		$this->db->select('*');
		$this->db->from('ci_raptor_serum_result');
		$this->db->where('nextvu_id', $id);
		return $this->db->get()->row();
	}

	public function getRaptorValue($rcode,$rid){
		$this->db->select('name,result_value,value_intensity');
		$this->db->from('ci_raptor_result_allergens');
		if(!empty($rcode)){
			$allergens = explode(", ",$rcode);
			$codArr = array();
			foreach($allergens as $row){
				if(!empty($row)){
				$codArr[] = "'".$row."'";
				}
			}
			$this->db->where('name IN('.implode(",",$codArr).')');
		}
		$this->db->where('result_id', $rid);
		$this->db->order_by("result_value", "DESC");
		$respnce = $this->db->get();
		return $respnce->row();
	}

	public function getsubAllergensCode($aid){
		$this->db->select('GROUP_CONCAT(raptor_code ORDER BY raptor_code SEPARATOR ",") AS raptor_code');
		$this->db->from('ci_allergens_raptor');
		$this->db->where('allergens_id', $aid);
		$this->db->order_by("raptor_code", "ASC");
		$this->db->order_by("em_allergen", "DESC");
		return $this->db->get()->row();
	}

	public function getsubAllergensforPanel($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead.", rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND (ar.em_allergen != '1' || ar.em_allergen IS NULL) ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretation($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead.", rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen IN(1,2) ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretationED($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead.", rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen NOT IN(1,2) ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretationDescription($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead." FROM `ci_allergens_raptor` as ar WHERE ar.allergens_id = '".$aid."' AND ar.em_allergen = 1 ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretationComponents($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead.", rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen = 3 ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretationExtract($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead.", rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen = 2 ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorInterpretationExtractNew($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$raptorhead = "ar.raptor_header_".$this->session->userdata('site_lang')." as raptor_header";
		}else{
			$raptorhead = "ar.raptor_header";
		}
		$sql = "SELECT ar.id, ar.raptor_code, ar.raptor_function, ar.em_allergen, ".$raptorhead." FROM `ci_allergens_raptor` as ar WHERE ar.allergens_id = '".$aid."' AND ar.em_allergen = 2 ORDER BY ar.id ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorValueByCode($rcode,$rid){
		$this->db->select('name,result_value,value_intensity');
		$this->db->from('ci_raptor_result_allergens');
		if(!empty($rcode)){
			$allergens = explode(", ",$rcode);
			$codArr = array();
			foreach($allergens as $row){
				$codArr[] = "'".$row."'";
			}
			$this->db->where('name IN('.implode(",",$codArr).')');
		}
		$this->db->where('result_id', $rid);
		$this->db->order_by("id", "ASC");
		return $this->db->get()->result();
	}

	public function getVialsRecord($id = ""){
		$this->db->select('vials_order,allergens');
		$this->db->from('ci_allergens_vials');
		$this->db->where('order_id', $id);

		return $this->db->get()->result();
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

		$this->db->select('managed_by_id,country');
		$this->db->from('ci_users');
		$this->db->where('id', $userID);
		$userData = $this->db->get()->row();
		if($userData->managed_by_id != ''){
			if(count(explode(",",$userData->managed_by_id)) > 1){
				$this->db->select('managed_by_id');
				$this->db->from('ci_staff_countries');
				$this->db->where('id', $userData->country);
				$cuntryData = $this->db->get()->row();
				if($cuntryData->managed_by_id != ''){
					return explode(",",$cuntryData->managed_by_id);
				}else{
					return '0';
				}
			}else{
				return explode(",",$userData->managed_by_id);
			}
		}else{
			return '0';
		}
	}

	public function getOrderInterpretation($orderID){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$interpretation = "interpretation_".$this->session->userdata('site_lang')." as interpretation";
			$interpretation_food = "interpretation_food_".$this->session->userdata('site_lang')." as interpretation_food";
			$vet_interpretation = "vet_interpretation_".$this->session->userdata('site_lang')." as vet_interpretation";
		}else{
			$interpretation = "interpretation";
			$interpretation_food = "interpretation_food";
			$vet_interpretation = "vet_interpretation";
		}
		$sql = "SELECT ".$interpretation.", ".$interpretation_food.", ".$vet_interpretation." FROM ci_order_interpretation WHERE order_id = '".$orderID."'";
        $responce = $this->db->query($sql);
		$result = $responce->row();

		return $result;
	}

}