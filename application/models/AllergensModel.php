<?php
class AllergensModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_allergens';
    }

    public function getRecord($id="") {
        $this->db->select('*');
        $this->db->from($this->_table);
        $this->db->where('id', $id); 

        return $this->db->get()->row_array();    
    }

    public function get_allergens_dropdown($orderTypeData = []) {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.','.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', '0'); 
        if(!empty($orderTypeData)){
            $order_type = '["'.$orderTypeData[0].'"]';
            $where  = "(JSON_CONTAINS(order_type, '".$order_type."')";
            foreach ($orderTypeData as $key => $value) {
                if($key!=0){
                    $order_type = '["'.$value.'"]';
                    $where  .= " OR JSON_CONTAINS(order_type, '".$order_type."')";
                }
            }
            $where  .= ")";
            $this->db->where($where);
        }
        return $this->db->get()->result_array(); 
    }

	public function get_pax_allergens_dropdown($orderTypeData = []) {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.','.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('pax_parent_id', '0'); 
        if(!empty($orderTypeData)){
            $order_type = '["'.$orderTypeData[0].'"]';
            $where  = "(JSON_CONTAINS(order_type, '".$order_type."')";
            foreach ($orderTypeData as $key => $value) {
                if($key!=0){
                    $order_type = '["'.$value.'"]';
                    $where  .= " OR JSON_CONTAINS(order_type, '".$order_type."')";
                }
            }
            $where  .= ")";
            $this->db->where($where);
        }
        return $this->db->get()->result_array(); 
    }

    public function order_allergens($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				$this->db->select('GROUP_CONCAT(name ORDER BY name SEPARATOR ", ") AS name, GROUP_CONCAT(pax_name ORDER BY pax_name SEPARATOR ", ") AS pax_name');
				$this->db->from($this->_table);
				$this->db->where('id IN('.$allergens.')'); 

				return $this->db->get()->row_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function orderAPI_allergens($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				$this->db->select('GROUP_CONCAT(name ORDER BY name SEPARATOR "|@|") AS name');
				$this->db->from($this->_table);
				$this->db->where('id IN('.$allergens.')'); 

				return $this->db->get()->row_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function getAllergensCode($allergensName){
        $this->db->select('code');
		$this->db->from($this->_table);
		$this->db->where('name LIKE "%'.$allergensName.'%"');
		$responce = $this->db->get();
		$result = $responce->row()->code;

		return $result;
    }

    public function getAllergenParent($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
					$name = "b.name_".$this->session->userdata('site_lang')." as name";
					$paxname = "b.pax_name_".$this->session->userdata('site_lang')." as pax_name";
				}else{
					$name = "b.name";
					$paxname = "b.pax_name";
				}
				$this->db->select('a.parent_id,a.pax_parent_id,'.$name.','.$paxname.',b.pax_latin_name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')'); 
				$this->db->group_by('a.parent_id'); 

				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function getAllergenParentbyName($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
					$name = "b.name_".$this->session->userdata('site_lang')." as name";
					$paxname = "b.pax_name_".$this->session->userdata('site_lang')." as pax_name";
				}else{
					$name = "b.name as name";
					$paxname = "b.pax_name as pax_name";
				}
				$this->db->select('a.parent_id,a.pax_parent_id,'.$name.',b.is_mixtures,'.$paxname.',b.pax_latin_name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.pax_parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')'); 
				$this->db->group_by('a.pax_parent_id'); 
				/* $this->db->order_by("b.pax_name", "asc"); */
				$this->db->order_by("a.id", "asc");
				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

    public function getNotAvailAllergens($allergensData = []) {
        if( !empty($allergensData!='') ){
            $allergens = implode(",",$allergensData);
			if($allergens != ""){
				$this->db->select('GROUP_CONCAT(name ORDER BY name  SEPARATOR ", ") AS name,GROUP_CONCAT( DATE_FORMAT(due_date,"%d/%m/%Y") SEPARATOR ", " ) AS due_date');
				$this->db->from($this->_table);
				$this->db->where('id IN('.$allergens.') AND is_unavailable="1"'); 

				return $this->db->get()->row_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function get_subAllergens_Indoor($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,parent_id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name,can_allgy_env,fel_allgy_env,equ_allgy_env');
        $this->db->from($this->_table);
        $this->db->where('parent_id IN('.$parent_id.')'); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("".$name."", "asc");
        }
        if($sub_order_type!=''){
            $order_type = '["'.$sub_order_type.'"]';
            $this->db->where("JSON_CONTAINS(order_type, '".$order_type."')");
			$this->db->order_by("".$name."", "asc");
        }
        return $this->db->get()->result_array(); 
    }

    public function get_subAllergens_dropdown($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name,can_allgy_env,fel_allgy_env,equ_allgy_env');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $parent_id); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("name", "asc");
        }
        if($sub_order_type!=''){
            $order_type = '["'.$sub_order_type.'"]';
            $this->db->where("JSON_CONTAINS(order_type, '".$order_type."')");
			$this->db->order_by("name", "asc");
        }
        return $this->db->get()->result_array(); 
    }

	public function getSubAllergensdropdown($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
		$sql = "SELECT id, ".$name.", ".$paxname.", pax_latin_name FROM ". $this->_table ." WHERE ";
		$sql .= "parent_id = ".$parent_id."";
		if($allergensData != ''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			$sql .= " AND id IN(".$allergens.")";
        }
		if($sub_order_type != ''){
            $ordertypeArr = explode(",",$sub_order_type);
			$sql .= " AND ("; $i=0;
			foreach($ordertypeArr as $rowa){
				if($i==0){
					$sql .= "JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
				}else{
					$sql .= " OR JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
				}
				$i++;
			}
			$sql .= ")";
        }
		$sql .= " ORDER BY `name` ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result_array();
        return $result;
    }

	public function getAllergenParentPax($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
				$name = "b.name_".$this->session->userdata('site_lang')." as name";
				$paxname = "b.pax_name_".$this->session->userdata('site_lang')." as pax_name";
			}else{
				$name = "b.name";
				$paxname = "b.pax_name";
			}
            $this->db->select('a.pax_parent_id,'.$name.','.$paxname.',b.pax_latin_name');
            $this->db->from($this->_table.' AS a');
            $this->db->join($this->_table.' AS b', 'a.pax_parent_id = b.id', 'left');
            $this->db->where('a.id IN('.$allergens.')'); 
            $this->db->group_by('a.pax_parent_id'); 

            return $this->db->get()->result_array();
        }else{
            return array();
        }
    }

	public function getPAXSubAllergensdropdown($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
		$sql = "SELECT id, ".$name.", ".$paxname.", pax_latin_name FROM ". $this->_table ." WHERE ";
		$sql .= "pax_parent_id = ".$parent_id."";
		if($allergensData != ''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			$sql .= " AND id IN(".$allergens.")";
        }
		if($sub_order_type != ''){
            $ordertypeArr = explode(",",$sub_order_type);
			$sql .= " AND ("; $i=0;
			foreach($ordertypeArr as $rowa){
				if($i==0){
					$sql .= "JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
				}else{
					$sql .= " OR JSON_CONTAINS(order_type, '[\"".$rowa."\"]')";
				}
				$i++;
			}
			$sql .= ")";
        }
		$sql .= " ORDER BY `name` ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result_array();
        return $result;
    }

	public function get_pax_subAllergens_dropdown($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name as name";
			$paxname = "pax_name as pax_name";
		}
        $this->db->select('id,pax_parent_id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('pax_parent_id', $parent_id); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
        }
		$this->db->where('id !=', '459674');
        if($sub_order_type!=''){
            $order_type = '["'.$sub_order_type.'"]';
            $this->db->where("JSON_CONTAINS(order_type, '".$order_type."')");
        }
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$this->db->order_by("".$this->_table.".pax_name_".$this->session->userdata('site_lang')."", "asc");
		}else{
			$this->db->order_by("".$this->_table.".pax_name", "asc");
		}
        return $this->db->get()->result_array(); 
    }

	function getMCPaxnameById($id){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$this->db->select('pax_name_'.$this->session->userdata('site_lang').' AS pax_name');
		}else{
			$this->db->select('pax_name AS pax_name');
		}
		$this->db->from('ci_allergens');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	public function get_subAllergens_serum($parent_id,$allergensData = '',$sub_order_type='',$stype) {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $parent_id);
		if($stype > 0){
			$this->db->where('serum_type',$stype); 
		}
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("name", "asc");
        }
        if($sub_order_type!=''){
            $order_type = '["'.$sub_order_type.'"]';
            $this->db->where("JSON_CONTAINS(order_type, '".$order_type."')");
			$this->db->order_by("name", "asc");
        }
        return $this->db->get()->result_array(); 
    }

    public function insect_allergen($allergen_ids='') {
        //get insect category id
        $this->db->select('id');
        $this->db->from($this->_table);
        $this->db->where('parent_id', '0'); 
        $this->db->where('name', 'Insects'); 
        $result =  $this->db->get()->row_array();

        //check insect allergens
        $this->db->select('id,name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $result['id']); 
        $this->db->where('id IN('.$allergen_ids.')'); 
        return $this->db->get()->num_rows();
    }

    public function culicoides_allergen($allergen_ids='') {
        //get culicoides category id
        $this->db->select('id');
        $this->db->from($this->_table);
        $this->db->where('parent_id', '0'); 
        $this->db->where('name', 'Culicoides'); 
        $result =  $this->db->get()->row_array();

        //check culicoides allergens
        $this->db->select('id,name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $result['id']); 
        $this->db->where('id IN('.$allergen_ids.')'); 
        return $this->db->get()->num_rows();
    }

    public function add_edit($allergenData = []) {
        if (isset($allergenData['id']) && is_numeric($allergenData['id'])>0) {
            $this->db->where('id', $allergenData['id']);
            $update =  $this->db->update($this->_table,$allergenData);
            if($update){
                return $this->db->affected_rows();
            }else{
                return false;    
            }
        }else{
            if(isset($allergenData) && count($allergenData)>0){
                $this->db->insert($this->_table,$allergenData);
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

    function unavailable($data = []) {
        $allergenData = [];
        $unsetAllergenData = [];
        $update = $replaced_date= $final_date= $unsetUpdate= '';
        if( !empty($data['due_date_array']) ){
            foreach ($data['due_date_array'] as $key => $value) {
                $replaced_date = strtotime( str_replace ( '/', '-', $value['due_date'] ) ); 
                $final_date = date("Y-m-d",$replaced_date);
                $allergenData['due_date'] = $final_date;
                $allergenData['unavailable_for'] = $data['order_type'];
                $allergenData['is_unavailable'] = '1';
                $this->db->where('id', $value['id']);
                $update =  $this->db->update($this->_table,$allergenData);
            }
        }

        //unset allergens
        if( isset($data['rtest']) && $data['rtest']!='' ){
            $unsetAllergenData['due_date'] = NULL;
            $unsetAllergenData['unavailable_for'] = '0';
            $unsetAllergenData['is_unavailable'] = '0';

            $this->db->where('id IN('.$data['rtest'].')');
            $unsetUpdate =  $this->db->update($this->_table,$unsetAllergenData);
        }
        if($update || $unsetUpdate){
            return true;
        }else{
            return false;    
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
            $this->db->or_like('ci_allergens.name', $postData['search']['value']);
        }
        $this->db->where('ci_allergens.parent_id', "0");
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('ci_allergens.'.$columnName, $columnSortOrder);
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

    //sub allergens datatable functions
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
        $this->db->join('ci_allergens AS b', 'a.parent_id=b.id');
        parse_str($postData['formData'], $filterData);
        //sorting
        if(!empty($postData['search']['value'])) {
            $this->db->or_like('a.name', $postData['search']['value']);
			$this->db->or_like('a.pax_name', $postData['search']['value']);
        }
        if(!empty($filterData['order_type'])) {
            //echo $filterData['order_type']; 
            $order_type = '["'.$filterData['order_type'].'"]';
            $where = "(JSON_CONTAINS(a.order_type, '".$order_type."'))";
            $this->db->where($where);
        }
        $this->db->where('a.parent_id!=0');
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $this->db->order_by('a.'.$columnName, $columnSortOrder);
    }

    public function sub_count_all(){
        return $this->db->count_all_results($this->_table);
    }

    public function sub_count_filtered(){
        $this->sub_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    //sub allergens datatable functions

	function getBranchdetailsById($id){
		$this->db->select('id, name, address, address1, address2, address3, town_city, country, postcode, number, customer_number');
		$this->db->from('ci_branches');
		$this->db->where('vet_user_id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	function getUserdetailsById($id){
		$this->db->select('name,last_name');
		$this->db->from('ci_users');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();

		return $query;
	}
	
	function getPaxnameById($id){
		$this->db->select('pax_name AS pax_parent_name');
		$this->db->from('ci_allergens');
		$this->db->where('id',$id);
		$query = $this->db->get()->row();

		return $query;
	}

	function getAllergennameById($id){
		$this->db->select('name');
		$this->db->from('ci_allergens');
		$this->db->where('id',$id);
		$query = $this->db->get()->row()->name;

		return $query;
	}

	public function Totalvials($id) {
		$sql = "SELECT COUNT(vial_id) as totalVials FROM ci_allergens_vials WHERE order_id = '". $id ."'";
        $responce = $this->db->query($sql);
		$result = $responce->row()->totalVials;

		return $result;
	}

	public function getVialslist($vid,$id) {
		$this->db->select('vials_order,allergens');
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

	public function getAllergensCodeByID($allergensId){
        $this->db->select('code');
		$this->db->from($this->_table);
		$this->db->where("id IN(".$allergensId.")");
		$responce = $this->db->get();
		$result = $responce->result_array();

		return $result;
    }

	public function getAllergensByID($allergensId){
		$result = array();
		if($allergensId != '' && $allergensId != '[]'){
            $allergensArr = json_decode($allergensId);
            $allergens = implode(",",$allergensArr);
			if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
				$name = "name_".$this->session->userdata('site_lang')." as name";
				$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
			}else{
				$name = "name";
				$paxname = "pax_name";
			}
			$this->db->select('id,'.$name.',code,'.$paxname.',pax_latin_name');
			$this->db->from($this->_table);
            $this->db->where("id IN(".$allergens.")");
			$this->db->order_by("name", "asc");
			$responce = $this->db->get();
			$result = $responce->result_array();
        }
		return $result;
    }

	public function getNextlabAllergensByID($allergensId='',$allergensData2=''){
		$result = array();
		if($allergensId != '' && $allergensId != '[]'){
            $allergensArr = json_decode($allergensId);
            $allergens = implode(",",$allergensArr);
			if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
				$name = "name_".$this->session->userdata('site_lang')." as name";
				$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
			}else{
				$name = "name";
				$paxname = "pax_name";
			}
			$this->db->select('id,'.$name.',code,'.$paxname.',pax_latin_name');
			$this->db->from($this->_table);
            $this->db->where("id IN(".$allergens.")");
			if($allergensData2 !='' && $allergensData2 != '[]'){
				$allergens1Arr = json_decode($allergensData2);
				$allergened = implode(",",$allergens1Arr);
				$this->db->where('id NOT IN('.$allergened.')'); 
			}
			$this->db->order_by("name", "asc");
			$responce = $this->db->get();
			$result = $responce->result_array();
        }
		return $result;
    }

	public function getGroupMixturesbyParent($pID) {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
		$this->db->select('id, parent_id, pax_parent_id, '.$name.', mixture_allergens, '.$paxname.', pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('parent_id',$pID);
		$this->db->where('is_mixtures',1);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->result_array();
        }else{
            return array();
        }
	}

	public function getsubAllergensCode($aid){
		$this->db->select('GROUP_CONCAT(raptor_code ORDER BY raptor_code SEPARATOR ",") AS raptor_code');
		$this->db->from('ci_allergens_raptor');
		$this->db->where('allergens_id', $aid);
		$this->db->where('raptor_code !=', "");
		return $this->db->get()->row();
	}

	public function get_subAllergens_dropdown2($parent_id,$allergensData = '',$allergensData2='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $parent_id); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("name", "asc");
        }
		if($allergensData2 !='' && $allergensData2 != '[]'){
            $allergens1Arr = json_decode($allergensData2);
            $allergened = implode(",",$allergens1Arr);
            $this->db->where('id NOT IN('.$allergened.')'); 
        }
        return $this->db->get()->result_array(); 
    }

	public function get_subAllergens_dropdown_empty($parent_id,$allergensData = '',$allergensData2='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
        $this->db->select('id,'.$name.',is_unavailable,'.$paxname.',pax_latin_name');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $parent_id); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("name", "asc");
        }
		if(!empty($allergensData2)){
			$allergened = implode(",",$allergensData2);
            $this->db->where('id NOT IN('.$allergened.')'); 
        }
        return $this->db->get()->result_array(); 
    }
	
	public function get_subAllergens_recommendation($parent_id,$allergensData = '',$sub_order_type='') {
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "name_".$this->session->userdata('site_lang')." as name";
			$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
		}else{
			$name = "name";
			$paxname = "pax_name";
		}
		$sql = "SELECT id, ".$name.", ".$paxname.", pax_latin_name FROM ". $this->_table ." WHERE ";
		$sql .= "parent_id = ".$parent_id."";
		if($allergensData != ''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			$sql .= " AND id IN(".$allergens.")";
        }
		if($sub_order_type != ''){
			$sql .= " AND (JSON_CONTAINS(order_type, '".$sub_order_type."') OR JSON_CONTAINS(order_type, '[\"1\"]'))";
        }
		$sql .= " ORDER BY name ASC";
        $responce = $this->db->query($sql);
		$result = $responce->result_array();
        return $result;
    }

	public function getSerumTestType($type){
		$this->db->select('GROUP_CONCAT(id ORDER BY id SEPARATOR ",") AS id');
		$this->db->from('ci_price');
		$this->db->where('parent_id !=', 0);
		$this->db->where('name LIKE', '%'.$type.'%');
		return $this->db->get()->row()->id;
	}

	public function checkforArtuveterinallergen($allergen_id) {
        //get culicoides category id
        $this->db->select('id');
        $this->db->from($this->_table);
        $this->db->where('parent_id !=', '0');
		$this->db->where("JSON_CONTAINS(order_type, '[\"1\"]')");
        $this->db->where('id IN('.$allergen_id.')'); 
        return $this->db->get()->num_rows();
    }

	public function getallergensENVcatgory($allergensData){
		if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				$this->db->select('a.parent_id,b.name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')');
				$this->db->where("(JSON_CONTAINS(a.order_type, '[\"3\"]') OR JSON_CONTAINS(a.order_type, '[\"31\"]') OR JSON_CONTAINS(a.order_type, '[\"6\"]'))");
				$this->db->group_by('a.parent_id'); 
				$this->db->order_by("b.id", "asc");
				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
	}

	public function getallergensFoodcatgory($allergensData){
		if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				$this->db->select('a.parent_id,b.name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')');
				$this->db->where("(JSON_CONTAINS(a.order_type, '[\"5\"]') OR JSON_CONTAINS(a.order_type, '[\"51\"]') OR JSON_CONTAINS(a.order_type, '[\"7\"]'))");
				$this->db->where("!JSON_CONTAINS(a.order_type, '[\"6\"]')");
				
				$this->db->group_by('a.parent_id'); 
				$this->db->order_by("b.id", "asc");
				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
	}

	public function get_subAllergensfood_dropdown($parent_id,$allergensData = '',$sub_order_type='') {
        $this->db->select('id,name,can_allgy_food_ige,can_allgy_food_igg,fel_allgy_food_ige,fel_allgy_food_igg,equ_allgy_food_ige,equ_allgy_food_igg');
        $this->db->from($this->_table);
        $this->db->where('parent_id', $parent_id); 
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
            $this->db->where('id IN('.$allergens.')'); 
			$this->db->order_by("name", "asc");
        }
        if($sub_order_type!=''){
            $order_type = '["'.$sub_order_type.'"]';
            $this->db->where("JSON_CONTAINS(order_type, '".$order_type."')");
			$this->db->order_by("name", "asc");
        }
        return $this->db->get()->result_array(); 
    }

	public function getEnvAllergenParentbyName($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
					$name = "b.name_".$this->session->userdata('site_lang')." as name";
					$paxname = "b.pax_name_".$this->session->userdata('site_lang')." as pax_name";
				}else{
					$name = "b.name as name";
					$paxname = "b.pax_name as pax_name";
				}
				$this->db->select('a.parent_id,a.pax_parent_id,'.$name.',b.is_mixtures,'.$paxname.',b.pax_latin_name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.pax_parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')');
				$this->db->where('a.id !=', '459674');
				$this->db->where('JSON_CONTAINS(b.order_type, \'["8"]\')');
				$this->db->group_by('a.pax_parent_id');
				/* $this->db->order_by("b.pax_name", "asc"); */
				$this->db->order_by("a.id", "asc");
				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function getFoodAllergenParentbyName($allergensData = []) {
        if($allergensData!=''){
            $allergensArr = json_decode($allergensData);
            $allergens = implode(",",$allergensArr);
			if($allergens != ""){
				if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
					$name = "b.name_".$this->session->userdata('site_lang')." as name";
					$paxname = "b.pax_name_".$this->session->userdata('site_lang')." as pax_name";
				}else{
					$name = "b.name as name";
					$paxname = "b.pax_name as pax_name";
				}
				$this->db->select('a.parent_id,a.pax_parent_id,'.$name.',b.is_mixtures,'.$paxname.',b.pax_latin_name');
				$this->db->from($this->_table.' AS a');
				$this->db->join($this->_table.' AS b', 'a.pax_parent_id = b.id', 'left');
				$this->db->where('a.id IN('.$allergens.')');
				$this->db->where('JSON_CONTAINS(b.order_type, \'["9"]\')');
				$this->db->group_by('a.pax_parent_id');
				/* $this->db->order_by("b.pax_name", "asc"); */
				$this->db->order_by("a.id", "asc");
				return $this->db->get()->result_array();
			}else{
				return array();
			}
        }else{
            return array();
        }
    }

	public function getEnvAllergensByID($allergensId){
		$result = array();
		if($allergensId != '' && $allergensId != '[]'){
            $allergensArr = json_decode($allergensId);
            $allergens = implode(",",$allergensArr);
			if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
				$name = "name_".$this->session->userdata('site_lang')." as name";
				$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
			}else{
				$name = "name";
				$paxname = "pax_name";
			}
			$this->db->select('id,'.$name.',code,'.$paxname.',pax_latin_name');
			$this->db->from($this->_table);
            $this->db->where("id IN(".$allergens.")");
			$this->db->where('id !=', '459674');
			$this->db->where('JSON_CONTAINS(order_type, \'["8"]\')');
			$this->db->order_by("name", "asc");
			$responce = $this->db->get();
			$result = $responce->result_array();
        }
		return $result;
    }

	public function getFoodAllergensByID($allergensId){
		$result = array();
		if($allergensId != '' && $allergensId != '[]'){
            $allergensArr = json_decode($allergensId);
            $allergens = implode(",",$allergensArr);
			if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
				$name = "name_".$this->session->userdata('site_lang')." as name";
				$paxname = "pax_name_".$this->session->userdata('site_lang')." as pax_name";
			}else{
				$name = "name";
				$paxname = "pax_name";
			}
			$this->db->select('id,'.$name.',code,'.$paxname.',pax_latin_name');
			$this->db->from($this->_table);
            $this->db->where("id IN(".$allergens.")");
			$this->db->where('JSON_CONTAINS(order_type, \'["9"]\')');
			$this->db->order_by("name", "asc");
			$responce = $this->db->get();
			$result = $responce->result_array();
        }
		return $result;
    }

	public function getPostiveComponents($aid,$rid){
		$sql = "SELECT ar.id, ar.allergens_id, ar.raptor_code, rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen = 3 ORDER BY rr.result_value DESC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getPostiveExtract($aid,$rid){
		$sql = "SELECT ar.id, ar.allergens_id, ar.raptor_code, rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.allergens_id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen = 2 ORDER BY rr.result_value DESC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getRaptorComponentsGroupBy($aid,$rid){
		if(!empty($this->session->userdata('site_lang')) && $this->session->userdata('site_lang') != 'english'){
			$name = "cr.name_".$this->session->userdata('site_lang')." as name";
		}else{
			$name = "cr.name";
		}
		$sql = "SELECT ar.allergens_id, ".$name.", cr.pax_parent_id, ar.raptor_function, rr.result_value, ar.raptor_code FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name LEFT JOIN `ci_allergens` as cr ON ar.allergens_id = cr.id WHERE ar.id IN(".implode(",",$aid).") AND rr.result_id = '".$rid."' AND cr.parent_id != '0' AND JSON_CONTAINS(cr.order_type, '[\"1\"]') ORDER BY rr.result_value DESC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function checkforExtract($aid,$rid){
		$sql = "SELECT ar.id, ar.allergens_id, ar.raptor_code FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name WHERE ar.id = '".$aid."' AND rr.result_id = '".$rid."' AND ar.em_allergen = 2 ORDER BY rr.result_value DESC";
        $responce = $this->db->query($sql);
		$result = $responce->row();

		return $result;
	}

	public function checkCodeType($rcode){
		$sql = "SELECT id,em_allergen FROM `ci_allergens_raptor` WHERE raptor_code LIKE '".$rcode."'";
        $responce = $this->db->query($sql);
		$result = $responce->row();

		return $result;
	}

	public function getRaptorSameComponents($rfun,$aid,$rid){
		$sql = "SELECT rr.result_value FROM `ci_allergens_raptor` as ar LEFT JOIN `ci_raptor_result_allergens` as rr ON ar.raptor_code = rr.name LEFT JOIN `ci_allergens` as cr ON ar.allergens_id = cr.id WHERE ar.raptor_function LIKE '%".$rfun."%' AND ar.id IN(".implode(",",$aid).") AND rr.result_id = '".$rid."' AND cr.parent_id != '0' AND JSON_CONTAINS(cr.order_type, '[\"1\"]') ORDER BY rr.result_value DESC";
        $responce = $this->db->query($sql);
		$result = $responce->result();

		return $result;
	}

	public function getsubAllergensCodeForSecondHigherValue($aid,$rcode){
		$this->db->select('GROUP_CONCAT(raptor_code ORDER BY raptor_code SEPARATOR ",") AS raptor_code');
		$this->db->from('ci_allergens_raptor');
		$this->db->where('allergens_id', $aid);
		$this->db->where('raptor_code NOT LIKE', $rcode);
		$this->db->order_by("raptor_code", "ASC");
		$this->db->order_by("em_allergen", "DESC");
		return $this->db->get()->row();
	}

}