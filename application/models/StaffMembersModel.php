<?php
class StaffMembersModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_staff_members';
		$this->_table_managed_by = 'ci_managed_by_members';
    }

    public function getRecord($id="") {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    function getRecordAll() {
        $this->db->select("ci_staff_members.*");
        $this->db->from($this->_table);
        $this->db->order_by('first_name','ASC');
        return $this->db->get()->result_array();
    }

    public function add_edit($staffMemberData = []) {
        if (isset($staffMemberData['id']) && is_numeric($staffMemberData['id'])>0) {
            $this->db->where('id', $staffMemberData['id']);
            $update =  $this->db->update($this->_table,$staffMemberData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($staffMemberData) && count($staffMemberData)>0){
                $this->db->insert($this->_table,$staffMemberData);
                return $staffMember_id = $this->db->insert_id();
            }else{
                return $staffMember_id = null; 
            }
        }
    }

    function delete($data = []) {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            return $this->db->delete($this->_table);
        }
    }

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
        $this->db->select('*');
        $this->db->from($this->_table);
        if(!empty($postData['search']['value'])) {
            $this->db->or_like('ci_staff_members.email', $postData['search']['value']);
            $this->db->or_like('ci_staff_members.first_name', $postData['search']['value']);
            $this->db->or_like('ci_staff_members.last_name', $postData['search']['value']);
        }
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_staff_members.'.$columnName, $columnSortOrder);
    }

    public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

	public function getManagedbyRecord($id="") {
        $this->db->select('*');
        $this->db->from($this->_table_managed_by);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    function getManagedbyRecordAll() {
        $this->db->select("ci_managed_by_members.*");
        $this->db->from($this->_table_managed_by);
		if($this->zones != ""){
			$this->db->where('ci_managed_by_members.id IN('.$this->zones.')');
		}
        $this->db->order_by('managed_by_name','ASC');
        return $this->db->get()->result_array();
    }

    public function add_editManagedby($staffMemberData = []) {
        if (isset($staffMemberData['id']) && is_numeric($staffMemberData['id'])>0) {
            $this->db->where('id', $staffMemberData['id']);
            $update =  $this->db->update($this->_table_managed_by,$staffMemberData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($staffMemberData) && count($staffMemberData)>0){
                $this->db->insert($this->_table_managed_by,$staffMemberData);
                return $staffMember_id = $this->db->insert_id();
            }else{
                return $staffMember_id = null; 
            }
        }
    }

    public function getManagedbyTableData(){
        $this->_get_managed_by_datatables_query();
        $row = $this->input->post('start');
        $rowperpage = $this->input->post('length');
        $this->db->limit($rowperpage, $row);
        $query = $this->db->get()->result();
        return $query;
    }

    private function _get_managed_by_datatables_query(){
        $postData = $this->input->post();
        $this->db->select('*');
        $this->db->from($this->_table_managed_by);
        if(!empty($postData['search']['value'])) {
            $this->db->or_like('managed_by_name', $postData['search']['value']);
        }
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by(''.$columnName, $columnSortOrder);
    }

    public function managed_by_count_all(){
        return $this->db->count_all_results($this->_table_managed_by);
    }

    public function managed_by_count_filtered(){
        $this->_get_managed_by_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }


}