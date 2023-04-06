<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportCategory extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/SBook2.xlsx';
        
        require_once APPPATH . "/libraries/PHPExcel-1.8/Classes/PHPExcel.php";
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $flag = true;
            
            // echo "<pre>";
            // print_r($allDataInSheet);
            $result = [];
            $i=2;
            $date = date("Y-m-d H:i:s");
            $allergenData = [];
            $allergen_id = '';
            foreach ($allDataInSheet as $value) {
                if($flag){
                $flag =false;
                continue;
                }

                //get allergen id from name
                $allergen_name = trim($value['N']);
                if($allergen_name!=''){
                    $this->db->select('id,name');
                    $this->db->from("ci_allergens");
                    $this->db->where('name', $allergen_name); 
                    $this->db->where('parent_id', '0'); 
                    echo $this->db->last_query(); 
                    echo "<br>";
                    $allergen_exists = $this->db->get()->num_rows();

                    if( $allergen_exists==0 ){

                        $allergenData['name'] = trim($value['N']);
                        $allergenData['code'] = NULL;
                        $allergenData['parent_id'] = '0';
                        $allergenData['order_type'] = '["1"]';
                        $allergenData['created_by'] = '1';
                        $allergenData['created_at'] = $date;
    
                        $this->db->insert("ci_allergens",$allergenData);
                        $allergen_id = $this->db->insert_id();
    
                        
    
                    }//if allergen_exists
                }
                
                

                
                
                
                $i++;

            }//foreach  
              
             
               
            if($allergen_id){
              echo "Imported successfully";
            }else{
              echo "ERROR1 !";
            }             
            
        } catch (Exception $e) {
            echo "ERROR2 !";
        }
                

    }

}

?>