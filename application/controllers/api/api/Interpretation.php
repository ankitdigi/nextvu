<?php
require APPPATH . '/libraries/REST_Controller.php';

class Interpretation extends REST_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->_table = 'ci_allergens';
		$this->load->model('AllergensModel');
		error_reporting(0);
    }

	public function index_get($id = 0){
		if($this->uri->segment('1') == 'api' || $this->uri->segment('1') == 'API'){
			$id = $this->uri->segment('4');
		}

		if(!empty($id)){
			$sql = "SELECT id, pax_parent_id, pax_name, pax_latin_name FROM ". $this->_table ." WHERE pax_parent_id != '0' AND (JSON_CONTAINS(order_type, '[\"11\"]') OR JSON_CONTAINS(order_type, '[\"12\"]')) AND pax_parent_id = '". $id ."'";
			$responce = $this->db->query($sql);
			$datas = $responce->result_array();
		}else{
			$sql = "SELECT id, pax_parent_id, pax_name, pax_latin_name FROM ". $this->_table ." WHERE pax_parent_id != '0' AND (JSON_CONTAINS(order_type, '[\"11\"]') OR JSON_CONTAINS(order_type, '[\"12\"]'))";
			$responce = $this->db->query($sql);
			$datas = $responce->result_array();
		}

		$data = $data_details = []; 
		foreach($datas as $data_detail){
			$this->db->select('pax_name');
			$this->db->from($this->_table);
			$this->db->where('id',$data_detail['pax_parent_id']);
			$groupName = $this->db->get()->row()->pax_name;

			$this->db->select('raptor_code,raptor_function,em_allergen,raptor_header');
			$this->db->from('ci_allergens_raptor');
			$this->db->where('allergens_id',$data_detail['id']);
			$allegrslt = $this->db->get()->result();
			
			if(!empty($allegrslt)){
				foreach($allegrslt as $row){
					$data["API_Grouping_Number"]	= $data_detail['pax_parent_id'];
					$data["Allergen_Group"]			= $groupName;
					$data["Allergen_ID"]			= $data_detail['id'];
					$data["Allergen_PAX_Name"]		= $data_detail['pax_name'];
					$data["Allergen_PAX_Latin_Name"]= $data_detail['pax_latin_name'];
					if($row->em_allergen == 1){
						$data["E_M_Allergen"]		= 'Allergen Description';
					}elseif($row->em_allergen == 2){
						$data["E_M_Allergen"]		= 'Allergen Extract';
					}elseif($row->em_allergen == 3){
						$data["E_M_Allergen"]		= 'Molecular Allergen';
					}
					$data["Code"]					= $row->raptor_code;
					$data["Allergen_Family"]		= $row->raptor_function;
					if($row->raptor_header != "" && $row->raptor_header != '[""]'){
						$detaildArr = json_decode($row->raptor_header);
						if(!empty($detaildArr)){
							$i=1;
							foreach($detaildArr as $row1d){
								$data["Interpretation_Line_".$i] = $row1d;
								$i++;
							}
						}else{
							$data["Interpretation"] = '';
						}
					}else{
						$data["Interpretation"] = '';
					}
					$data_details[] = $data;
				}
			}else{
				$data["API_Grouping_Number"]	= $data_detail['pax_parent_id'];
				$data["Allergen_Group"]			= $groupName;
				$data["Allergen_ID"]			= $data_detail['id'];
				$data["Allergen_PAX_Name"]		= $data_detail['pax_name'];
				$data["Allergen_PAX_Latin_Name"]= $data_detail['pax_latin_name'];
				$data["E_M_Allergen"]			= '';
				$data["Code"]					= '';
				$data["Allergen_Family"]		= '';
				$data["Interpretation"] = '';
				$data_details[] = $data;
			}
		}
		$this->response($data_details, REST_Controller::HTTP_OK);
	}

}
?>