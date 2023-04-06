<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportSubCategory extends CI_Controller {

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
            $practiceData = [];
            $practiceDetailsData = [];
            $subAllerString = '';
            foreach ($allDataInSheet as $value) {
                if($flag){
                $flag =false;
                continue;
                }

                //get allergen id from name
                $allergen_name = $value['N'];
                if($allergen_name!=''){
                    $allergen_name = trim($allergen_name);
                    $this->db->select('id,name');
                    $this->db->from("ci_allergens");
                    $this->db->where('name', $allergen_name); 
                    $this->db->where('parent_id', '0'); 
                    //echo $this->db->last_query(); 
                    $allergen_details = $this->db->get()->row_array();
                    //$allergen_exists = $this->db->get()->num_rows();
                    //print_r($allergen_details);
                    //exit;
                    if( !empty($allergen_details) ){

                        //echo $value['A'];
                        $subAllerString = trim($value['O']);
                        $subAllergens = preg_split('/(?=[A-Z])/', $subAllerString);
                        //print_r($subAllergens);
                        //echo "<br>";


                        foreach ($subAllergens as $skey => $svalue) {
                            $subAllergen_exists  = '';
                            if($svalue!=''){
                                $svalue = trim($svalue);

                                $this->db->select('id,name');
                                $this->db->from("ci_allergens");
                                $this->db->where('name', $svalue); 
                                $this->db->where('parent_id!=0'); 
                                echo $this->db->last_query(); 
                                echo "<br>";
                                $subAllergen_exists = $this->db->get()->num_rows();

                                if( $subAllergen_exists==0 ){

                                    $subAllergensData['name'] = $svalue;
                                    $subAllergensData['parent_id'] = $allergen_details['id'];
                                    $subAllergensData['order_type'] = '["1"]';
                                    $subAllergensData['created_by'] = '1';
                                    $subAllergensData['created_at'] = $date;
                
                                    $this->db->insert("ci_allergens",$subAllergensData);
                                    $subAllergen_id = $this->db->insert_id();
                                    
                                }

                                
                            }
                            
                        }

                        
                        
                    }
                    

                }
                
                
                
                $i++;

            }//foreach  
              
             
               
            if($subAllergen_id){
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