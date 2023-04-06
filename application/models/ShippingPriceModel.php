<?php
class ShippingPriceModel extends CI_model{
    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_shipping_price';
    }

	public function getRecord($id="") {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id); 
        return $this->db->get()->row_array();    
    }

	public function getTableData(){
        $this->get_datatables_query();
        $row = $this->input->post('start');
        $rowperpage = $this->input->post('length');
        $this->db->limit($rowperpage, $row);
        $query = $this->db->get()->result();
        return $query;
    }

    private function get_datatables_query(){
        $postData = $this->input->post();
        $this->db->select('a.*,type.name AS practice_name');
        $this->db->from($this->_table.' AS a');
		$this->db->join('ci_price AS type', 'a.parent_id = type.id','left');
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('a.'.$columnName, $columnSortOrder);
    }

    public function count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function count_filtered(){
		$this->get_datatables_query();
		$query = $this->db->get();

		return $query->num_rows();
	}

	public function get_shipping_price_dropdown() {
		$this->db->select('id,name');
		$this->db->from("ci_price");
		$this->db->where('parent_id', '0');

		return $this->db->get()->result_array(); 
	}

	public function getSelectedPractices($id) {
        if( $id>0 ){
            $this->db->select('GROUP_CONCAT(practice_id ORDER BY practice_id SEPARATOR ", ") AS practice_id');
            $this->db->from('ci_user_shipping');
            $this->db->where('shipping_id',$id); 

            return $this->db->get()->row_array();
        }else{
            return array();
        }
    }

	public function add_edit($categoryData = []){
        if(isset($categoryData['id']) && is_numeric($categoryData['id'])>0){
            $this->db->where('id', $categoryData['id']);
            $update =  $this->db->update($this->_table,$categoryData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($categoryData) && count($categoryData)>0){
                $this->db->insert($this->_table,$categoryData);
                return $user_id = $this->db->insert_id();
            }else{
                return $user_id = null; 
            }
        }
    }

	public function discount_getTableData($id,$product_id){
        $this->discount_get_datatables_query($id,$product_id);
        $row = $this->input->post('start');
        $rowperpage = $this->input->post('length');
        $query = $this->db->get()->result();

        return $query;
    }

    private function discount_get_datatables_query($id,$product_id){
        $postData = $this->input->post();

        $this->db->select('disc.*,user.name AS practice_name');
        $this->db->from('ci_user_shipping as disc');
        $this->db->join('ci_users AS user', 'disc.practice_id=user.id','left');
        $this->db->where('disc.practice_id',$id);
        $this->db->where('disc.shipping_id',$product_id);
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by("disc.id", "DESC");
    }

    public function discount_count_all($id,$product_id){
        return $this->db->count_all_results($this->_table);
    }

    public function discount_count_filtered($id,$product_id){
        $this->discount_get_datatables_query($id,$product_id);
        $query = $this->db->get();

        return $query->num_rows();
    }

    function save_discount($data = []){
        foreach ($data['discount_arr'] as $key => $value) {
			$this->db->where('id', $value['discount_id']);
			$disc = $this->db->get('ci_user_shipping');
			$this->db->reset_query();

			$discData['shipping_id'] = $value['shipping_id'];
			$discData['practice_id'] = $value['practice_id'];
			$discData['uk_discount'] = $value['uk_discount'];
			$discData['roi_discount'] = $value['roi_discount'];
            if($disc->num_rows() > 0){
                $this->db->where('id', $value['discount_id'])->update('ci_user_shipping', $discData);
            } else {
                $this->db->insert('ci_user_shipping', $discData);
            }
        }
        return true;
    }

	function discount_delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete('ci_user_shipping'); 
		}
    }

}