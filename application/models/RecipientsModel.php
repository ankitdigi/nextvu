<?php
class RecipientsModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_artuvetrin_recipients';
    }

    public function getRecord($id="") {

        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id); 
        //$query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $this->db->get()->row_array();    
      
    }

    function getRecordAll($order_type='') {
        
        if($order_type!=''){
            $order_type = '["'.$order_type.'"]';
            $where  = "JSON_CONTAINS(order_type, '".$order_type."')";
            $this->db->where($where);
        }

        $this->db->select("ci_artuvetrin_recipients.*");
        $this->db->from($this->_table);
        $this->db->order_by('email','ASC');
        //$query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $this->db->get()->result_array();    
      
    }

    public function add_edit($recipientData = []) {
        
        
        if (isset($recipientData['id']) && is_numeric($recipientData['id'])>0) {
            
            $this->db->where('id', $recipientData['id']);
            $update =  $this->db->update($this->_table,$recipientData);
            
            //echo $this->db->last_query(); exit;
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }

        }else{

            if(isset($recipientData) && count($recipientData)>0){
                $this->db->insert($this->_table,$recipientData);
                return $recipient_id = $this->db->insert_id();
            }else{
                return $recipient_id = null; 
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

            $this->db->or_like('ci_artuvetrin_recipients.email', $postData['search']['value']);
            $this->db->or_like('ci_artuvetrin_recipients.first_name', $postData['search']['value']);
            $this->db->or_like('ci_artuvetrin_recipients.last_name', $postData['search']['value']);
            
        }
        
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        //$this->db->order_by("ci_artuvetrin_recipients.id", "ASC");
        $this->db->order_by('ci_artuvetrin_recipients.'.$columnName, $columnSortOrder);
        
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