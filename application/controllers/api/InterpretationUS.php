<?php
require APPPATH . '/libraries/REST_Controller.php';

class InterpretationUS extends REST_Controller {

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
				$detaildArr = $detailddArr = $detaildfArr = $detaildgArr = $detaildiArr = $detailduArr = $detaildnArr = $detaildsArr = $detailds1Arr = [];
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
					if($row->raptor_header != "" && $row->raptor_header != '[]' && $row->raptor_header != '[""]' && $row->raptor_header != '["","",""]' && $row->raptor_header != '["","","",""]' && $row->raptor_header != '["","","","",""]'){
						$detaildArr = json_decode($row->raptor_header);
						if(!empty($detaildArr) && isset($detaildArr[0]) && $detaildArr[0] != ''){
							$data["Interpretation_English_Line_1"] = $detaildArr[0];
						}else{
							$data["Interpretation_English_Line_1"] = '';
						}
						if(!empty($detaildArr) && isset($detaildArr[1]) && $detaildArr[1] != ''){
							$data["Interpretation_English_Line_2"] = $detaildArr[1];
						}else{
							$data["Interpretation_English_Line_2"] = '';
						}
						if(!empty($detaildArr) && isset($detaildArr[2]) && $detaildArr[2] != ''){
							$data["Interpretation_English_Line_3"] = $detaildArr[2];
						}else{
							$data["Interpretation_English_Line_3"] = '';
						}
						if(!empty($detaildArr) && isset($detaildArr[3]) && $detaildArr[3] != ''){
							$data["Interpretation_English_Line_4"] = $detaildArr[3];
						}else{
							$data["Interpretation_English_Line_4"] = '';
						}
					}else{
						$data["Interpretation_English_Line_1"] = '';
						$data["Interpretation_English_Line_2"] = '';
						$data["Interpretation_English_Line_3"] = '';
						$data["Interpretation_English_Line_4"] = '';
					}

					if($row->raptor_header_danish != "" && $row->raptor_header_danish != '[]' && $row->raptor_header_danish != '[""]' && $row->raptor_header_danish != '["","",""]' && $row->raptor_header_danish != '["","","",""]' && $row->raptor_header_danish != '["","","","",""]'){
						$detailddArr = json_decode($row->raptor_header_danish);
						if(!empty($detailddArr) && isset($detailddArr[0]) && $detailddArr[0] != ''){
							$data["Interpretation_Danish_Line_1"] = $detailddArr[0];
						}else{
							$data["Interpretation_Danish_Line_1"] = '';
						}
						if(!empty($detailddArr) && isset($detailddArr[1]) && $detailddArr[1] != ''){
							$data["Interpretation_Danish_Line_2"] = $detailddArr[1];
						}else{
							$data["Interpretation_Danish_Line_2"] = '';
						}
						if(!empty($detailddArr) && isset($detailddArr[2]) && $detailddArr[2] != ''){
							$data["Interpretation_Danish_Line_3"] = $detailddArr[2];
						}else{
							$data["Interpretation_Danish_Line_3"] = '';
						}
						if(!empty($detailddArr) && isset($detailddArr[3]) && $detailddArr[3] != ''){
							$data["Interpretation_Danish_Line_4"] = $detailddArr[3];
						}else{
							$data["Interpretation_Danish_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Danish_Line_1"] = '';
						$data["Interpretation_Danish_Line_2"] = '';
						$data["Interpretation_Danish_Line_3"] = '';
						$data["Interpretation_Danish_Line_4"] = '';
					}

					if($row->raptor_header_french != "" && $row->raptor_header_french != '[]' && $row->raptor_header_french != '[""]' && $row->raptor_header_french != '["","",""]' && $row->raptor_header_french != '["","","",""]' && $row->raptor_header_french != '["","","","",""]'){
						$detaildfArr = json_decode($row->raptor_header_french);
						if(!empty($detaildfArr) && isset($detaildfArr[0]) && $detaildfArr[0] != ''){
							$data["Interpretation_French_Line_1"] = $detaildfArr[0];
						}else{
							$data["Interpretation_French_Line_1"] = '';
						}
						if(!empty($detaildfArr) && isset($detaildfArr[1]) && $detaildfArr[1] != ''){
							$data["Interpretation_French_Line_2"] = $detaildfArr[1];
						}else{
							$data["Interpretation_French_Line_2"] = '';
						}
						if(!empty($detaildfArr) && isset($detaildfArr[2]) && $detaildfArr[2] != ''){
							$data["Interpretation_French_Line_3"] = $detaildfArr[2];
						}else{
							$data["Interpretation_French_Line_3"] = '';
						}
						if(!empty($detaildfArr) && isset($detaildfArr[3]) && $detaildfArr[3] != ''){
							$data["Interpretation_French_Line_4"] = $detaildfArr[3];
						}else{
							$data["Interpretation_French_Line_4"] = '';
						}
					}else{
						$data["Interpretation_French_Line_1"] = '';
						$data["Interpretation_French_Line_2"] = '';
						$data["Interpretation_French_Line_3"] = '';
						$data["Interpretation_French_Line_4"] = '';
					}

					if($row->raptor_header_german != "" && $row->raptor_header_german != '[]' && $row->raptor_header_german != '[""]' && $row->raptor_header_german != '["","",""]' && $row->raptor_header_german != '["","","",""]' && $row->raptor_header_german != '["","","","",""]'){
						$detaildgArr = json_decode($row->raptor_header_german);
						if(!empty($detaildgArr) && isset($detaildgArr[0]) && $detaildgArr[0] != ''){
							$data["Interpretation_German_Line_1"] = $detaildgArr[0];
						}else{
							$data["Interpretation_German_Line_1"] = '';
						}
						if(!empty($detaildgArr) && isset($detaildgArr[1]) && $detaildgArr[1] != ''){
							$data["Interpretation_German_Line_2"] = $detaildgArr[1];
						}else{
							$data["Interpretation_German_Line_2"] = '';
						}
						if(!empty($detaildgArr) && isset($detaildgArr[2]) && $detaildgArr[2] != ''){
							$data["Interpretation_German_Line_3"] = $detaildgArr[2];
						}else{
							$data["Interpretation_German_Line_3"] = '';
						}
						if(!empty($detaildgArr) && isset($detaildgArr[3]) && $detaildgArr[3] != ''){
							$data["Interpretation_German_Line_4"] = $detaildgArr[3];
						}else{
							$data["Interpretation_German_Line_4"] = '';
						}
					}else{
						$data["Interpretation_German_Line_1"] = '';
						$data["Interpretation_German_Line_2"] = '';
						$data["Interpretation_German_Line_3"] = '';
						$data["Interpretation_German_Line_4"] = '';
					}

					if($row->raptor_header_italian != "" && $row->raptor_header_italian != '[]' && $row->raptor_header_italian != '[""]' && $row->raptor_header_italian != '["","",""]' && $row->raptor_header_italian != '["","","",""]' && $row->raptor_header_italian != '["","","","",""]'){
						$detaildiArr = json_decode($row->raptor_header_italian);
						if(!empty($detaildiArr) && isset($detaildiArr[0]) && $detaildiArr[0] != ''){
							$data["Interpretation_Italian_Line_1"] = $detaildiArr[0];
						}else{
							$data["Interpretation_Italian_Line_1"] = '';
						}
						if(!empty($detaildiArr) && isset($detaildiArr[1]) && $detaildiArr[1] != ''){
							$data["Interpretation_Italian_Line_2"] = $detaildiArr[1];
						}else{
							$data["Interpretation_Italian_Line_2"] = '';
						}
						if(!empty($detaildiArr) && isset($detaildiArr[2]) && $detaildiArr[2] != ''){
							$data["Interpretation_Italian_Line_3"] = $detaildiArr[2];
						}else{
							$data["Interpretation_Italian_Line_3"] = '';
						}
						if(!empty($detaildiArr) && isset($detaildiArr[3]) && $detaildiArr[3] != ''){
							$data["Interpretation_Italian_Line_4"] = $detaildiArr[3];
						}else{
							$data["Interpretation_Italian_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Italian_Line_1"] = '';
						$data["Interpretation_Italian_Line_2"] = '';
						$data["Interpretation_Italian_Line_3"] = '';
						$data["Interpretation_Italian_Line_4"] = '';
					}

					if($row->raptor_header_dutch != "" && $row->raptor_header_dutch != '[]' && $row->raptor_header_dutch != '[""]' && $row->raptor_header_dutch != '["","",""]' && $row->raptor_header_dutch != '["","","",""]' && $row->raptor_header_dutch != '["","","","",""]'){
						$detailduArr = json_decode($row->raptor_header_dutch);
						if(!empty($detailduArr) && isset($detailduArr[0]) && $detailduArr[0] != ''){
							$data["Interpretation_Dutch_Line_1"] = $detailduArr[0];
						}else{
							$data["Interpretation_Dutch_Line_1"] = '';
						}
						if(!empty($detailduArr) && isset($detailduArr[1]) && $detailduArr[1] != ''){
							$data["Interpretation_Dutch_Line_2"] = $detailduArr[1];
						}else{
							$data["Interpretation_Dutch_Line_2"] = '';
						}
						if(!empty($detailduArr) && isset($detailduArr[2]) && $detailduArr[2] != ''){
							$data["Interpretation_Dutch_Line_3"] = $detailduArr[2];
						}else{
							$data["Interpretation_Dutch_Line_3"] = '';
						}
						if(!empty($detailduArr) && isset($detailduArr[3]) && $detailduArr[3] != ''){
							$data["Interpretation_Dutch_Line_4"] = $detailduArr[3];
						}else{
							$data["Interpretation_Dutch_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Dutch_Line_1"] = '';
						$data["Interpretation_Dutch_Line_2"] = '';
						$data["Interpretation_Dutch_Line_3"] = '';
						$data["Interpretation_Dutch_Line_4"] = '';
					}

					if($row->raptor_header_norwegian != "" && $row->raptor_header_norwegian != '[]' && $row->raptor_header_norwegian != '[""]' && $row->raptor_header_norwegian != '["","",""]' && $row->raptor_header_norwegian != '["","","",""]' && $row->raptor_header_norwegian != '["","","","",""]'){
						$detaildnArr = json_decode($row->raptor_header_norwegian);
						if(!empty($detaildnArr) && isset($detaildnArr[0]) && $detaildnArr[0] != ''){
							$data["Interpretation_Norwegian_Line_1"] = $detaildnArr[0];
						}else{
							$data["Interpretation_Norwegian_Line_1"] = '';
						}
						if(!empty($detaildnArr) && isset($detaildnArr[1]) && $detaildnArr[1] != ''){
							$data["Interpretation_Norwegian_Line_2"] = $detaildnArr[1];
						}else{
							$data["Interpretation_Norwegian_Line_2"] = '';
						}
						if(!empty($detaildnArr) && isset($detaildnArr[2]) && $detaildnArr[2] != ''){
							$data["Interpretation_Norwegian_Line_3"] = $detaildnArr[2];
						}else{
							$data["Interpretation_Norwegian_Line_3"] = '';
						}
						if(!empty($detaildnArr) && isset($detaildnArr[3]) && $detaildnArr[3] != ''){
							$data["Interpretation_Norwegian_Line_4"] = $detaildnArr[3];
						}else{
							$data["Interpretation_Norwegian_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Norwegian_Line_1"] = '';
						$data["Interpretation_Norwegian_Line_2"] = '';
						$data["Interpretation_Norwegian_Line_3"] = '';
						$data["Interpretation_Norwegian_Line_4"] = '';
					}

					if($row->raptor_header_spanish != "" && $row->raptor_header_spanish != '[]' && $row->raptor_header_spanish != '[""]' && $row->raptor_header_spanish != '["","",""]' && $row->raptor_header_spanish != '["","","",""]' && $row->raptor_header_spanish != '["","","","",""]'){
						$detaildsArr = json_decode($row->raptor_header_spanish);
						if(!empty($detaildsArr) && isset($detaildsArr[0]) && $detaildsArr[0] != ''){
							$data["Interpretation_Spanish_Line_1"] = $detaildsArr[0];
						}else{
							$data["Interpretation_Spanish_Line_1"] = '';
						}
						if(!empty($detaildsArr) && isset($detaildsArr[1]) && $detaildsArr[1] != ''){
							$data["Interpretation_Spanish_Line_2"] = $detaildsArr[1];
						}else{
							$data["Interpretation_Spanish_Line_2"] = '';
						}
						if(!empty($detaildsArr) && isset($detaildsArr[2]) && $detaildsArr[2] != ''){
							$data["Interpretation_Spanish_Line_3"] = $detaildsArr[2];
						}else{
							$data["Interpretation_Spanish_Line_3"] = '';
						}
						if(!empty($detaildsArr) && isset($detaildsArr[3]) && $detaildsArr[3] != ''){
							$data["Interpretation_Spanish_Line_4"] = $detaildsArr[3];
						}else{
							$data["Interpretation_Spanish_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Spanish_Line_1"] = '';
						$data["Interpretation_Spanish_Line_2"] = '';
						$data["Interpretation_Spanish_Line_3"] = '';
						$data["Interpretation_Spanish_Line_4"] = '';
					}

					if($row->raptor_header_swedish != "" && $row->raptor_header_swedish != '[]' && $row->raptor_header_swedish != '[""]' && $row->raptor_header_swedish != '["","",""]' && $row->raptor_header_swedish != '["","","",""]' && $row->raptor_header_swedish != '["","","","",""]'){
						$detailds1Arr = json_decode($row->raptor_header_swedish);
						if(!empty($detailds1Arr) && isset($detailds1Arr[0]) && $detailds1Arr[0] != ''){
							$data["Interpretation_Swedish_Line_1"] = $detailds1Arr[0];
						}else{
							$data["Interpretation_Swedish_Line_1"] = '';
						}
						if(!empty($detailds1Arr) && isset($detailds1Arr[1]) && $detailds1Arr[1] != ''){
							$data["Interpretation_Swedish_Line_2"] = $detailds1Arr[1];
						}else{
							$data["Interpretation_Swedish_Line_2"] = '';
						}
						if(!empty($detailds1Arr) && isset($detailds1Arr[2]) && $detailds1Arr[2] != ''){
							$data["Interpretation_Swedish_Line_3"] = $detailds1Arr[2];
						}else{
							$data["Interpretation_Swedish_Line_3"] = '';
						}
						if(!empty($detailds1Arr) && isset($detailds1Arr[3]) && $detailds1Arr[3] != ''){
							$data["Interpretation_Swedish_Line_4"] = $detailds1Arr[3];
						}else{
							$data["Interpretation_Swedish_Line_4"] = '';
						}
					}else{
						$data["Interpretation_Swedish_Line_1"] = '';
						$data["Interpretation_Swedish_Line_2"] = '';
						$data["Interpretation_Swedish_Line_3"] = '';
						$data["Interpretation_Swedish_Line_4"] = '';
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