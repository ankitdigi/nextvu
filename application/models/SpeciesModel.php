<?php
class SpeciesModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_species';
    }

    public function getRecord($id="") {

        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id); 
        //$query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $this->db->get()->row_array();    
      
    }

    function getRecordAll() {
        
        $this->db->select("ci_species.*");
        $this->db->from($this->_table);
        $this->db->order_by('name','ASC');
        //$query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $this->db->get()->result_array();    
      
    }

    public function add_edit($specieData = []) {
        
        
        if (isset($specieData['id']) && is_numeric($specieData['id'])>0) {
            
            $this->db->where('id', $specieData['id']);
            $update =  $this->db->update($this->_table,$specieData);
            
            //echo $this->db->last_query(); exit;
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }

        }else{

            if(isset($specieData) && count($specieData)>0){
                $this->db->insert($this->_table,$specieData);
                return $specie_id = $this->db->insert_id();
            }else{
                return $specie_id = null; 
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
    public function getTableData()
    {
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

            $this->db->or_like('ci_species.name', $postData['search']['value']);
            
        }
        
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        //$this->db->order_by("ci_species.id", "ASC");
        $this->db->order_by('ci_species.'.$columnName, $columnSortOrder);
        
    }

    public function count_all()
    {
        return $this->db->count_all_results($this->_table);
    }
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
       //echo $this->db->last_query(); exit;
        return $query->num_rows();
    }
    //datatable functions


}