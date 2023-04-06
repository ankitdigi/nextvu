<?php
class InvoicesModel extends CI_model{
    public function __construct() { 
		parent::__construct();
		$this->_table = 'ci_invoices';
		$this->invoice_report_table = 'ci_invoice_report';
		$this->userData = logged_in_user_data();
    }

    public function getRecord($id) {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();    
    }

	public function getMultipleRecord($invoiceIds) {
        $this->db->select('*');
        $this->db->from($this->_table);
		$this->db->where_in('id', "$invoiceIds", false);
        
        return $this->db->get()->result();    
    }

    public function invoice_add($data = []) {
		if (isset($data['uploaded_doc_name']) && $data['uploaded_doc_name'] != '') {
			$insert = $this->db->insert($this->_table,$data);
			if($insert){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}
	}

    public function invoice_report($data = []) {
        $insert = $this->db->insert($this->invoice_report_table,$data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

	/* data table functions */
    public function getTableData(){
        $this->_get_datatables_query();
        $row = $this->input->post('start');
        $rowperpage = $this->input->post('length');
        $this->db->limit($rowperpage, $row);
        $query = $this->db->get()->result();
        return $query;
    }

    private function _get_datatables_query(){
        $postData = $this->input->post();
        $this->db->select('ci_invoices.*,ci_users.name');
        $this->db->from($this->_table);
        $this->db->join('ci_users', 'ci_invoices.uploaded_by = ci_users.id'); 

        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by($columnName, $columnSortOrder);
    }

	public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	/* datatable functions */

	/* report datatable functions */
	public function getReportTableData($id=''){
		$this->_get_reportdatatables_query($id);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
    }

    private function _get_reportdatatables_query($id){
        $postData = $this->input->post();
        $this->db->select('ci_invoice_report.*');
        $this->db->from($this->invoice_report_table);
        //sorting
        if(!empty($postData['search']['value'])) {
			$this->db->or_like('ci_invoice_report.order_number', $postData['search']['value']);
        }
        $this->db->where('invoice_id', $id);
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_invoice_report.'.$columnName, $columnSortOrder);
    }

    public function reportCount_all($id){
        return $this->db->count_all_results($this->_table);
    }

    public function reportCount_filtered($id){
        $this->_get_reportdatatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }
	/* report datatable functions */

    public function delete($data = []) {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
        }
        return $this->db->delete($this->_table); 
    }

	public function getToBeInvoicedData(){
		$this->_get_tobeinvoiced_query();
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);

		$query = $this->db->get()->result();

		return $query;
	}

	private function _get_tobeinvoiced_query(){
		$postData = $this->input->post();
		$this->db->select('ci_orders.*,petOwner.name AS pet_owner_name, petOwner.last_name AS po_last,ci_pets.name as pet_name,is_mail_sent,ci_branches.name as practice_name,practice.name AS practice_first_name,practice.last_name AS practice_last_name,lab.name AS lab_name');
		$this->db->from('ci_orders');
		$this->db->where('ci_orders.is_draft' , 0);
		$this->db->where('ci_orders.is_invoiced' , 0);
		$this->db->join('ci_users', 'ci_orders.pet_owner_id = ci_users.id', 'left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id', 'left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id', 'left');
		$this->db->join('ci_branches', 'ci_orders.branch_id = ci_branches.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		parse_str($postData['formData'], $filterData);
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
		if ($this->userData['role'] == '2') {
			$this->db->where('ci_orders.vet_user_id', $this->userData['user_id']);
		}
		if ($this->userData['role'] == '5' || $this->userData['role'] == '6' || $this->userData['role'] == '7') {
			$this->db->where('ci_orders.created_by', $this->userData['user_id']);
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

		//$this->db->where('ci_orders.order_date >=', '2022-05-01');
		//$this->db->where('ci_orders.order_date <=', '2022-05-31');

		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if($columnName == 'pet_owner_name'){
			$this->db->order_by('petOwner.last_name', $columnSortOrder);
		}elseif($columnName == 'pet_name'){
			$this->db->order_by('ci_pets.name', $columnSortOrder);
		}elseif($columnName == 'final_name'){
			$this->db->order_by('practice.name', $columnSortOrder);
		}else{
			$this->db->order_by('ci_orders.' . $columnName, $columnSortOrder);
		}
		if ((!empty($filterData['dashboard_latest_list']) && $filterData['dashboard_latest_list'] == 'yes') || (!empty($filterData['dashboard_confirmed_list']) && $filterData['dashboard_confirmed_list'] == 'yes')) {
			$this->db->limit(5);
		}
	}

	public function count_tobeinvoiced_all(){
		return $this->db->count_all_results('ci_orders');
	}

	public function count_tobeinvoiced_filtered(){
		$this->_get_tobeinvoiced_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getInvoicedData(){
		$this->_get_invoiced_query();
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);

		$query = $this->db->get()->result();

		return $query;
	}

	private function _get_invoiced_query(){
		$postData = $this->input->post();
		$this->db->select('ci_orders.*,petOwner.name AS pet_owner_name, petOwner.last_name AS po_last,ci_pets.name as pet_name,is_mail_sent,ci_branches.name as practice_name,practice.name AS practice_first_name,practice.last_name AS practice_last_name,lab.name AS lab_name');
		$this->db->from('ci_orders');
		$this->db->where('ci_orders.is_draft' , 0);
		$this->db->where('ci_orders.is_invoiced' , 1);
		$this->db->join('ci_users', 'ci_orders.pet_owner_id = ci_users.id', 'left');
		$this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id', 'left');
		$this->db->join('ci_users as practice', 'ci_orders.vet_user_id = practice.id', 'left');
		$this->db->join('ci_users as petOwner', 'ci_orders.pet_owner_id = petOwner.id', 'left');
		$this->db->join('ci_branches', 'ci_orders.branch_id = ci_branches.id', 'left');
		$this->db->join('ci_users as lab', 'ci_orders.lab_id = lab.id', 'left');
		parse_str($postData['formData'], $filterData);
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
		if ($this->userData['role'] == '2') {
			$this->db->where('ci_orders.vet_user_id', $this->userData['user_id']);
		}
		if ($this->userData['role'] == '5' || $this->userData['role'] == '6' || $this->userData['role'] == '7') {
			$this->db->where('ci_orders.created_by', $this->userData['user_id']);
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

		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		if($columnName == 'pet_owner_name'){
			$this->db->order_by('petOwner.last_name', $columnSortOrder);
		}elseif($columnName == 'pet_name'){
			$this->db->order_by('ci_pets.name', $columnSortOrder);
		}elseif($columnName == 'final_name'){
			$this->db->order_by('practice.name', $columnSortOrder);
		}else{
			$this->db->order_by('ci_orders.' . $columnName, $columnSortOrder);
		}
		if ((!empty($filterData['dashboard_latest_list']) && $filterData['dashboard_latest_list'] == 'yes') || (!empty($filterData['dashboard_confirmed_list']) && $filterData['dashboard_confirmed_list'] == 'yes')) {
			$this->db->limit(5);
		}
	}

	public function count_invoiced_all(){
		return $this->db->count_all_results('ci_orders');
	}

	public function count_invoiced_filtered(){
		$this->_get_invoiced_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getxmlOrderIdbyUser(){
		$this->db->select('user_id');
		$this->db->from('ci_orders_xml');
		$this->db->where('status', 0);
		$this->db->group_by('user_id');
		$this->db->order_by('user_id' , 'ASC');
		$query = $this->db->get()->result();

		return $query;
	}

	function getxmlMergeOrderIds($userId){
		$this->db->select('order_id');
		$this->db->from('ci_orders_xml');
		$this->db->where('status', 0);
		$this->db->where('user_id', $userId);
		$this->db->order_by('id' , 'ASC');
		$query = $this->db->get()->result();

		return $query;
	}

	function getxmlOrderIds(){
		$this->db->select('order_id');
		$this->db->from('ci_orders_xml');
		$this->db->where('status', 0);
		$this->db->order_by('id' , 'ASC');
		$query = $this->db->get()->result();

		return $query;
	}

	public function addxmlorderInfo($xmlorderData = []){
		if(isset($xmlorderData) && count($xmlorderData)>0){
			$this->db->insert('ci_orders_xml',$xmlorderData);
			return $user_id = $this->db->insert_id();
		}else{
			return $user_id = null; 
		}
    }

	function getOrderDetails($ids){
		$this->db->select('id, vet_user_id, branch_id, lab_id, lab_branch_id, name, email, phone_number, unit_price, order_type, shipping_cost, order_discount, sub_order_type, order_can_send_to, order_number, reference_number, comment, delivery_practice_id, allergens, product_code_selection, single_double_selection, order_date, batch_number, pet_owner_id, pet_id, plc_selection');
		$this->db->from('ci_orders');
		$this->db->where('is_draft', 0);
		$this->db->where('id IN('.$ids.')');
		$query = $this->db->get()->result();

		return $query;
	}

	function getUserdetailsById($id){
		$this->db->select('name,last_name');
		$this->db->from('ci_users');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	function getPetinfoById($id){
		$this->db->select('name');
		$this->db->from('ci_pets');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	function getBranchdetailsById($id){
		$this->db->select('id, name, address, address1, address2, address3, town_city, country, postcode, number, customer_number');
		$this->db->from('ci_branches');
		$this->db->where('vet_user_id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	function getCountryCode($id){
		$this->db->select('country');
		$this->db->from('ci_users');
		$this->db->where('id',$id);
		$query = $this->db->get()->row()->country;
		if($query == 1){
			return 'Netherlands';
		}else{
			return 'Ireland';
		}
	}

	public function get_discount($id, $practice_id) {
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

	public function insect_allergen($allergen_ids='') {
        $this->db->select('id');
        $this->db->from('ci_allergens');
        $this->db->where('parent_id', '0'); 
        $this->db->where('name', 'Insects'); 
        $result =  $this->db->get()->row_array();

        $this->db->select('id,name');
        $this->db->from('ci_allergens');
        $this->db->where('parent_id', $result['id']); 
        $this->db->where('id IN('.$allergen_ids.')'); 
        return $this->db->get()->num_rows();
    }

	public function culicoides_allergen($allergen_ids='') {
        $this->db->select('id');
        $this->db->from('ci_allergens');
        $this->db->where('parent_id', '0'); 
        $this->db->where('name', 'Culicoides'); 
        $result =  $this->db->get()->row_array();

        $this->db->select('id,name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $result['id']); 
        $this->db->where('id IN('.$allergen_ids.')'); 
        return $this->db->get()->num_rows();
    }

	public function skin_test_price($practice_lab='') {
		$practiceLab = $this->UsersModel->practiceLabCountry($practice_lab);
		if($practiceLab['name']=='UK' || $practiceLab['name']=='uk' || $practiceLab['name']=='UK'){
			$this->db->select('id,name,uk_price,uk_currency AS price_currency');
		}elseif($practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland' || $practiceLab['name']=='IE'){
			$this->db->select('id,name,roi_price AS uk_price,roi_currency AS price_currency');
		}elseif($practiceLab['name']=='Denmark' || $practiceLab['name']=='denmark' || $practiceLab['name']=='DK'){
			$this->db->select('id,name,dk_price AS uk_price,dk_currency AS price_currency');
		}elseif($practiceLab['name']=='France' || $practiceLab['name']=='france' || $practiceLab['name']=='FR'){
			$this->db->select('id,name,fr_price AS uk_price,fr_currency AS price_currency');
		}elseif($practiceLab['name']=='Germany' || $practiceLab['name']=='germany' || $practiceLab['name']=='DE'){
			$this->db->select('id,name,de_price AS uk_price,de_currency AS price_currency');
		}elseif($practiceLab['name']=='Italy' || $practiceLab['name']=='italy' || $practiceLab['name']=='IT'){
			$this->db->select('id,name,it_price AS uk_price,it_currency AS price_currency');
		}elseif($practiceLab['name']=='Netherlands' || $practiceLab['name']=='netherlands' || $practiceLab['name']=='NL'){
			$this->db->select('id,name,nl_price AS uk_price,nl_currency AS price_currency');
		}elseif($practiceLab['name']=='Norway' || $practiceLab['name']=='norway' || $practiceLab['name']=='NO'){
			$this->db->select('id,name,no_price AS uk_price,no_currency AS price_currency');
		}elseif($practiceLab['name']=='Spain' || $practiceLab['name']=='spain' || $practiceLab['name']=='ES'){
			$this->db->select('id,name,es_price AS uk_price,es_currency AS price_currency');
		}elseif($practiceLab['name']=='Sweden' || $practiceLab['name']=='sweden' || $practiceLab['name']=='SE'){
			$this->db->select('id,name,se_price AS uk_price,se_currency AS price_currency');
		}else{
			$this->db->select('id,name,default_price AS uk_price,default_currency AS price_currency');
		}
        $this->db->from('ci_price');
        $this->db->where('id IN(14,15)'); 

        return $this->db->get()->result_array(); 
    }

	public function serum_test_price($product_code_id='',$practice_lab='') {
		$practiceLab = $this->UsersModel->practiceLabCountry($practice_lab);
        if($practiceLab['name']=='UK' || $practiceLab['name']=='uk' || $practiceLab['name']=='UK'){
			$this->db->select('id,name,uk_price,uk_currency AS price_currency');
		}elseif($practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland' || $practiceLab['name']=='IE'){
			$this->db->select('id,name,roi_price AS uk_price,roi_currency AS price_currency');
		}elseif($practiceLab['name']=='Denmark' || $practiceLab['name']=='denmark' || $practiceLab['name']=='DK'){
			$this->db->select('id,name,dk_price AS uk_price,dk_currency AS price_currency');
		}elseif($practiceLab['name']=='France' || $practiceLab['name']=='france' || $practiceLab['name']=='FR'){
			$this->db->select('id,name,fr_price AS uk_price,fr_currency AS price_currency');
		}elseif($practiceLab['name']=='Germany' || $practiceLab['name']=='germany' || $practiceLab['name']=='DE'){
			$this->db->select('id,name,de_price AS uk_price,de_currency AS price_currency');
		}elseif($practiceLab['name']=='Italy' || $practiceLab['name']=='italy' || $practiceLab['name']=='IT'){
			$this->db->select('id,name,it_price AS uk_price,it_currency AS price_currency');
		}elseif($practiceLab['name']=='Netherlands' || $practiceLab['name']=='netherlands' || $practiceLab['name']=='NL'){
			$this->db->select('id,name,nl_price AS uk_price,nl_currency AS price_currency');
		}elseif($practiceLab['name']=='Norway' || $practiceLab['name']=='norway' || $practiceLab['name']=='NO'){
			$this->db->select('id,name,no_price AS uk_price,no_currency AS price_currency');
		}elseif($practiceLab['name']=='Spain' || $practiceLab['name']=='spain' || $practiceLab['name']=='ES'){
			$this->db->select('id,name,es_price AS uk_price,es_currency AS price_currency');
		}elseif($practiceLab['name']=='Sweden' || $practiceLab['name']=='sweden' || $practiceLab['name']=='SE'){
			$this->db->select('id,name,se_price AS uk_price,se_currency AS price_currency');
		}else{
			$this->db->select('id,name,default_price AS uk_price,default_currency AS price_currency');
		}
        $this->db->from('ci_price');
        $this->db->where('id',$product_code_id); 

        return $this->db->get()->result_array(); 
    }

	public function artuvetrin_test_price($practice_lab=''){
		$practiceLab = $this->UsersModel->practiceLabCountry($practice_lab);
        if($practiceLab['name']=='UK' || $practiceLab['name']=='uk' || $practiceLab['name']=='UK'){
			$this->db->select('id,name,uk_price,uk_currency AS price_currency');
		}elseif($practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland' || $practiceLab['name']=='IE'){
			$this->db->select('id,name,roi_price AS uk_price,roi_currency AS price_currency');
		}elseif($practiceLab['name']=='Denmark' || $practiceLab['name']=='denmark' || $practiceLab['name']=='DK'){
			$this->db->select('id,name,dk_price AS uk_price,dk_currency AS price_currency');
		}elseif($practiceLab['name']=='France' || $practiceLab['name']=='france' || $practiceLab['name']=='FR'){
			$this->db->select('id,name,fr_price AS uk_price,fr_currency AS price_currency');
		}elseif($practiceLab['name']=='Germany' || $practiceLab['name']=='germany' || $practiceLab['name']=='DE'){
			$this->db->select('id,name,de_price AS uk_price,de_currency AS price_currency');
		}elseif($practiceLab['name']=='Italy' || $practiceLab['name']=='italy' || $practiceLab['name']=='IT'){
			$this->db->select('id,name,it_price AS uk_price,it_currency AS price_currency');
		}elseif($practiceLab['name']=='Netherlands' || $practiceLab['name']=='netherlands' || $practiceLab['name']=='NL'){
			$this->db->select('id,name,nl_price AS uk_price,nl_currency AS price_currency');
		}elseif($practiceLab['name']=='Norway' || $practiceLab['name']=='norway' || $practiceLab['name']=='NO'){
			$this->db->select('id,name,no_price AS uk_price,no_currency AS price_currency');
		}elseif($practiceLab['name']=='Spain' || $practiceLab['name']=='spain' || $practiceLab['name']=='ES'){
			$this->db->select('id,name,es_price AS uk_price,es_currency AS price_currency');
		}elseif($practiceLab['name']=='Sweden' || $practiceLab['name']=='sweden' || $practiceLab['name']=='SE'){
			$this->db->select('id,name,se_price AS uk_price,se_currency AS price_currency');
		}else{
			$this->db->select('id,name,default_price AS uk_price,default_currency AS price_currency');
		}
        $this->db->from('ci_price');
        $this->db->where('id IN(16,17)');  

        return $this->db->get()->result_array(); 
    }

	public function slit_test_price($practice_lab=''){
		$practiceLab = $this->UsersModel->practiceLabCountry($practice_lab);
        if($practiceLab['name']=='UK' || $practiceLab['name']=='uk' || $practiceLab['name']=='UK'){
			$this->db->select('id,name,uk_price,uk_currency AS price_currency');
		}elseif($practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland' || $practiceLab['name']=='IE'){
			$this->db->select('id,name,roi_price AS uk_price,roi_currency AS price_currency');
		}elseif($practiceLab['name']=='Denmark' || $practiceLab['name']=='denmark' || $practiceLab['name']=='DK'){
			$this->db->select('id,name,dk_price AS uk_price,dk_currency AS price_currency');
		}elseif($practiceLab['name']=='France' || $practiceLab['name']=='france' || $practiceLab['name']=='FR'){
			$this->db->select('id,name,fr_price AS uk_price,fr_currency AS price_currency');
		}elseif($practiceLab['name']=='Germany' || $practiceLab['name']=='germany' || $practiceLab['name']=='DE'){
			$this->db->select('id,name,de_price AS uk_price,de_currency AS price_currency');
		}elseif($practiceLab['name']=='Italy' || $practiceLab['name']=='italy' || $practiceLab['name']=='IT'){
			$this->db->select('id,name,it_price AS uk_price,it_currency AS price_currency');
		}elseif($practiceLab['name']=='Netherlands' || $practiceLab['name']=='netherlands' || $practiceLab['name']=='NL'){
			$this->db->select('id,name,nl_price AS uk_price,nl_currency AS price_currency');
		}elseif($practiceLab['name']=='Norway' || $practiceLab['name']=='norway' || $practiceLab['name']=='NO'){
			$this->db->select('id,name,no_price AS uk_price,no_currency AS price_currency');
		}elseif($practiceLab['name']=='Spain' || $practiceLab['name']=='spain' || $practiceLab['name']=='ES'){
			$this->db->select('id,name,es_price AS uk_price,es_currency AS price_currency');
		}elseif($practiceLab['name']=='Sweden' || $practiceLab['name']=='sweden' || $practiceLab['name']=='SE'){
			$this->db->select('id,name,se_price AS uk_price,se_currency AS price_currency');
		}else{
			$this->db->select('id,name,default_price AS uk_price,default_currency AS price_currency');
		}
        $this->db->from($this->_table);
        $this->db->where('id IN(18,19,20,21)'); 

        return $this->db->get()->result_array(); 
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

	function getOrderNumber($id){
		$this->db->select('order_number,vet_user_id,lab_id,shipping_date');
		$this->db->from('ci_orders');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();
		if($query != ''){
			return $query;
		}else{
			return '';
		}
	}

	public function getColumnField($userData = []){
		$this->db->select("GROUP_CONCAT(IFNULL(column_field ,'') ORDER BY ci_user_details.id separator '|') AS column_field");
		$this->db->from('ci_user_details');
		$this->db->where('column_name IN(' . $userData['column_name'] . ')');
		$this->db->where('user_id', $userData['user_id']);

		return $this->db->get()->row_array();
	}

	public function getColumnFieldArray($userData = []){
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where('column_name IN(' . $userData['column_name'] . ')');
		$this->db->where('user_id', $userData['user_id']);

		return $this->db->get()->result_array();
	}

	function getOrdersbyType($ids){
		$this->db->select('COUNT(id) as totalOrder,order_type');
		$this->db->from('ci_orders');
		$this->db->where('is_draft', 0);
		$this->db->where('id IN('.$ids.')');
		$this->db->where('unit_price >', 0);
		$this->db->where('unit_price !=', '');
		$this->db->group_by('order_type');
		$query = $this->db->get()->result();

		return $query;
	}

}
?>