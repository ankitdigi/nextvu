<?php
error_reporting(E_ERROR | E_PARSE);
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportPractices extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->load->model('PracticeModel');
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/skintest_orders.xlsx';
        
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
            
            $practice_details = [];
            $sheet_data = [];
            $sheet_all_data = [];
            foreach ($allDataInSheet as $value) {
                if($flag){
                $flag =false;
                continue;
                }
                
                //get vet user id from name
                $vetlab_name = trim($value['G']);
                $country = trim($value['K']);
                if($country=='GB'){
                    $final_country = 1;
                }else if($country=='IE'){
                    $final_country = 2;
                }else{
                    $final_country = 1;
                }
                $practice_details = $this->PracticeModel->getRecord($vetlab_name,$final_country);

                $sheet_data['practice_id'] = $practice_details['id'];
                $sheet_data['account_ref'] = NULL;
                $sheet_data['practice_name'] = trim($value['D']);
                $sheet_data['address_1'] = trim($value['H']);
                $sheet_data['address_2'] = trim($value['J']);
                $sheet_data['address_3'] = NULL;
                $sheet_data['address_4'] = NULL;
                $sheet_data['post_code'] = trim($value['I']);
                $sheet_data['country'] = trim($value['K']);
                
                $sheet_all_data[] = $sheet_data;
                
                
                
                
                
                $i++;

            }//foreach  
              
            
            
            $sheet_all_data = array_map("unserialize", array_unique(array_map("serialize", $sheet_all_data)));
            //echo "<pre>";
            //print_r($sheet_all_data); exit;
               
            foreach ($sheet_all_data as $record) {
                
                $practice_id = $record['practice_id'];
                //$this->PracticeModel->delete_user_details($practice_id);
                $this->PracticeModel->add_user_details($record);

            }           
            
        } catch (Exception $e) {
            echo "ERROR !";
        }
                

    }

}

?>