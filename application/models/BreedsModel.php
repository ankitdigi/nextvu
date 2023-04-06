<?php
class BreedsModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_breeds';
    }

    public function getRecord($id="") {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id);

        return $this->db->get()->row_array();
    }

    public function get_breeds_dropdown($speciesData = []) {
        $this->db->select('id,name');
        $this->db->from($this->_table);
        $this->db->where('species_id', $speciesData['species_id']);
		$this->db->order_by('name', 'ASC');

        return $this->db->get()->result_array(); 
    }

    public function add_edit($breedData = []) {
        if (isset($breedData['id']) && is_numeric($breedData['id'])>0) {
            $this->db->where('id', $breedData['id']);
            $update =  $this->db->update($this->_table,$breedData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($breedData) && count($breedData)>0){
                $this->db->insert($this->_table,$breedData);
                return $breed_id = $this->db->insert_id();
            }else{
                return $breed_id = null; 
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

        //sorting
        if(!empty($postData['search']['value'])) {
            $this->db->or_like('ci_breeds.name', $postData['search']['value']);
        }

        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];

        $this->db->order_by('ci_breeds.'.$columnName, $columnSortOrder);
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