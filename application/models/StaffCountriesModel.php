<?php
class StaffCountriesModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_staff_countries';
    }

	public function getRecord($id="") {
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $id);
		return $this->db->get()->row_array();
    }

	function getRecordAll() {
		$this->db->select("id,name,code,prefer_language,managed_by_id");
		$this->db->from($this->_table);
		$this->db->order_by('name','ASC');
		return $this->db->get()->result_array();
    }

    public function add_edit($staffCountryData = []) {
        if (isset($staffCountryData['id']) && is_numeric($staffCountryData['id'])>0) {
            $this->db->where('id', $staffCountryData['id']);
            $update =  $this->db->update($this->_table,$staffCountryData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($staffCountryData) && count($staffCountryData)>0){
                $this->db->insert($this->_table,$staffCountryData);
                return $country_id = $this->db->insert_id();
            }else{
                return $country_id = null; 
            }
        }
    }

    function delete($data = []) {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            return $this->db->delete($this->_table);
        }
    }

    //data table functions
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
            $this->db->or_like('ci_staff_countries.name', $postData['search']['value']);
        }

        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_staff_countries.'.$columnName, $columnSortOrder);
    }

    public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered(){
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
    }

}