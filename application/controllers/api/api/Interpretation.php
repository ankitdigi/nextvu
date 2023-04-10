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

			$this->db->select('raptor_code,raptor_function,em_allergen,raptor_header,raptor_header_danish,raptor_header_french,raptor_header_german,raptor_header_italian,raptor_header_dutch,raptor_header_norwegian,raptor_header_spanish,raptor_header_swedish');
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
								$data["Interpretation_English_Line_".$i] = $row1d;
								$i++;
							}
						}else{
							$data["Interpretation_English"] = '';
						}
					}else{
						$data["Interpretation_English"] = '';
					}
					if($row->raptor_header_danish != "" && $row->raptor_header_danish != '[""]'){
						$detailddArr = json_decode($row->raptor_header_danish);
						if(!empty($detailddArr)){
							$d=1;
							foreach($detailddArr as $row2d){
								$data["Interpretation_Danish_Line_".$d] = $row2d;
								$d++;
							}
						}else{
							$data["Interpretation_Danish"] = '';
						}
					}else{
						$data["Interpretation_Danish"] = '';
					}
					if($row->raptor_header_french != "" && $row->raptor_header_french != '[""]'){
						$detaildfArr = json_decode($row->raptor_header_french);
						if(!empty($detaildfArr)){
							$f=1;
							foreach($detaildfArr as $row3d){
								$data["Interpretation_French_Line_".$f] = $row3d;
								$f++;
							}
						}else{
							$data["Interpretation_French"] = '';
						}
					}else{
						$data["Interpretation_French"] = '';
					}
					if($row->raptor_header_german != "" && $row->raptor_header_german != '[""]'){
						$detaildgArr = json_decode($row->raptor_header_german);
						if(!empty($detaildgArr)){
							$g=1;
							foreach($detaildgArr as $row4d){
								$data["Interpretation_German_Line_".$g] = $row4d;
								$g++;
							}
						}else{
							$data["Interpretation_German"] = '';
						}
					}else{
						$data["Interpretation_German"] = '';
					}
					if($row->raptor_header_italian != "" && $row->raptor_header_italian != '[""]'){
						$detaildiArr = json_decode($row->raptor_header_italian);
						if(!empty($detaildiArr)){
							$x=1;
							foreach($detaildiArr as $row5d){
								$data["Interpretation_Italian_Line_".$x] = $row5d;
								$x++;
							}
						}else{
							$data["Interpretation_Italian"] = '';
						}
					}else{
						$data["Interpretation_Italian"] = '';
					}
					if($row->raptor_header_dutch != "" && $row->raptor_header_dutch != '[""]'){
						$detailddArr = json_decode($row->raptor_header_dutch);
						if(!empty($detailddArr)){
							$d=1;
							foreach($detailddArr as $row6d){
								$data["Interpretation_Dutch_Line_".$d] = $row6d;
								$d++;
							}
						}else{
							$data["Interpretation_Dutch"] = '';
						}
					}else{
						$data["Interpretation_Dutch"] = '';
					}
					if($row->raptor_header_norwegian != "" && $row->raptor_header_norwegian != '[""]'){
						$detaildnArr = json_decode($row->raptor_header_norwegian);
						if(!empty($detaildnArr)){
							$n=1;
							foreach($detaildnArr as $row7d){
								$data["Interpretation_Norwegian_Line_".$n] = $row7d;
								$n++;
							}
						}else{
							$data["Interpretation_Norwegian"] = '';
						}
					}else{
						$data["Interpretation_Norwegian"] = '';
					}
					if($row->raptor_header_spanish != "" && $row->raptor_header_spanish != '[""]'){
						$detaildsArr = json_decode($row->raptor_header_spanish);
						if(!empty($detaildsArr)){
							$s=1;
							foreach($detaildsArr as $row8d){
								$data["Interpretation_Spanish_Line_".$s] = $row8d;
								$s++;
							}
						}else{
							$data["Interpretation_Spanish"] = '';
						}
					}else{
						$data["Interpretation_Spanish"] = '';
					}
					if($row->raptor_header_swedish != "" && $row->raptor_header_swedish != '[""]'){
						$detailds1Arr = json_decode($row->raptor_header_swedish);
						if(!empty($detailds1Arr)){
							$y=1;
							foreach($detailds1Arr as $row9d){
								$data["Interpretation_Swedish_Line_".$y] = $row9d;
								$y++;
							}
						}else{
							$data["Interpretation_Swedish"] = '';
						}
					}else{
						$data["Interpretation_Swedish"] = '';
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
				$data["Interpretation_English"] = '';
				$data["Interpretation_Danish"] = '';
				$data["Interpretation_French"] = '';
				$data["Interpretation_German"] = '';
				$data["Interpretation_Italian"] = '';
				$data["Interpretation_Dutch"] = '';
				$data["Interpretation_Norwegian"] = '';
				$data["Interpretation_Spanish"] = '';
				$data["Interpretation_Swedish"] = '';
				$data_details[] = $data;
			}
		}
		$this->response($data_details, REST_Controller::HTTP_OK);
	}

}
?>