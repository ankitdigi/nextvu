<?php
class PriceCategoriesModel extends CI_model{
    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_price';
    }

	public function getRecord($id="") {
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('id', $id); 
		return $this->db->get()->row_array();
	}

	function getRecordAll($parent_id = '') {
		$this->db->select("id,name,product_info,display_order");
		$this->db->from($this->_table);
		if($parent_id!=''){
			$this->db->where('parent_id', $parent_id);
		}
		$this->db->order_by('display_order','ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * @param int $parentId
	 * @param array $exceptId
	 * @return mixed
	 */
	function getRecordAllExcept($parentId = 0, $exceptId = []) {
		$this->db->select("id,name,product_info,display_order");
		$this->db->from($this->_table);
		if($parentId!=''){
			$this->db->where('parent_id', $parentId);
		}
		$this->db->where_not_in('id', $exceptId);
		$this->db->not_like('name', 'Expansion');
		$this->db->order_by('display_order','ASC');

		return $this->db->get()->result_array();
		//die($this->db->last_query());
	}

	public function get_price_categories_dropdown() {
		$this->db->select('id,name');
		$this->db->from($this->_table);
		$this->db->where('parent_id', '0'); 
		return $this->db->get()->result_array(); 
	}

	public function vetgoid_petslit($type= 0, $practice_lab='') {
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
		if ($type == 1) {
			$this->db->where('id IN(65)');
		} else {
			$this->db->where('id IN(63)');
		}

		return $this->db->get()->row_array();
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
		$this->db->from($this->_table);
		$this->db->where('id IN(14,15)'); 

		return $this->db->get()->result_array(); 
    }

    public function slit_test_price($practice_lab='') {
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
		$this->db->from($this->_table);
		$this->db->where('id',$product_code_id);

		return $this->db->get()->result_array();
    }

    public function artuvetrin_test_price($practice_lab='') {
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
		$this->db->where('id IN(16,17)'); 

		return $this->db->get()->result_array(); 
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

	public function add_edit($categoryData = []) {
		if (isset($categoryData['id']) && is_numeric($categoryData['id'])>0) {
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

	function delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			$this->db->or_where('parent_id', $data['id']);
			return $this->db->delete($this->_table);
		}
    }

	function discount_delete($data = []) {
		if (isset($data['id']) && $data['id'] != '') {
			$this->db->where('id', $data['id']);
			return $this->db->delete('ci_discount'); 
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
			$this->db->or_like('ci_price.name', $postData['search']['value']);
		}
		$this->db->where('ci_price.parent_id', "0");
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		$this->db->order_by('ci_price.'.$columnName, $columnSortOrder);
    }

	public function count_all(){
		return $this->db->count_all_results($this->_table);
	}

	public function count_filtered(){
		$this->_get_datatables_query();
		$query = $this->db->get();

		return $query->num_rows();
    }

	/* sub category datatable functions */
    public function sub_getTableData(){
		$this->sub_get_datatables_query();
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
	}

	private function sub_get_datatables_query(){
        $postData = $this->input->post();
        $this->db->select('a.*,b.name AS parent_name');
        $this->db->from($this->_table.' AS a');
        $this->db->join('ci_price AS b', 'a.parent_id=b.id');
        $this->db->where('a.parent_id!=0');
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
		if($columnName == 'parent_name'){
        $this->db->order_by($columnName, $columnSortOrder);
		}else{
		$this->db->order_by('a.'.$columnName, $columnSortOrder);
		}
    }

    public function sub_count_all(){
		return $this->db->count_all_results($this->_table);
    }

    public function sub_count_filtered(){
		$this->sub_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	/* sub category datatable functions */

	/* discounts datatble functions */
    public function discount_getTableData($id,$product_id){
		$this->discount_get_datatables_query($id,$product_id);
		$row = $this->input->post('start');
		$rowperpage = $this->input->post('length');
		//$this->db->limit($rowperpage, $row);
		$query = $this->db->get()->result();
		return $query;
	}

    private function discount_get_datatables_query($id,$product_id){
		$postData = $this->input->post();
		$this->db->select('disc.*,user.name AS practice_name');
		$this->db->from('ci_discount as disc');
		$this->db->join('ci_users AS user', 'disc.practice_id=user.id','left');
		$this->db->where('disc.practice_id',$id);
		$this->db->where('disc.product_id',$product_id);
		$columnIndex = $postData['order'][0]['column'];
		$columnName = $postData['columns'][$columnIndex]['data'];
		$columnSortOrder = $postData['order'][0]['dir'];
		$this->db->order_by("disc.id", "DESC");
		//$this->db->order_by('disc.'.$columnName, $columnSortOrder);
	}

    public function discount_count_all($id,$product_id){
        return $this->db->count_all_results($this->_table);
    }

    public function discount_count_filtered($id,$product_id){
		$this->discount_get_datatables_query($id,$product_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function save_discount($data = []) {
		foreach ($data['discount_arr'] as $key => $value) {
            $this->db->where('id', $value['discount_id']);
            $disc = $this->db->get('ci_discount');
            $this->db->reset_query();
            $discData['product_id'] = $value['product_id'];
            $discData['practice_id'] = $value['practice_id'];
            $discData['uk_discount'] = $value['uk_discount'];
            if ( $disc->num_rows() > 0 ) {
                $this->db->where('id', $value['discount_id'])->update('ci_discount', $discData);
            } else {
                $this->db->insert('ci_discount', $discData);
            }
        }
        return true;
    }

	public function getSelectedPractices($id) {
        if( $id>0 ){
            $this->db->select('GROUP_CONCAT(practice_id ORDER BY practice_id SEPARATOR ", ") AS practice_id');
            $this->db->from('ci_discount');
            $this->db->where('product_id',$id); 
            return $this->db->get()->row_array();
        }else{
            return array();
        }
    }

}
