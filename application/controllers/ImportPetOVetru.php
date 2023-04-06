<?php
error_reporting(E_ERROR | E_PARSE);
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportPetOVetru extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
        $this->load->model('PracticeModel');
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/orders_artuvetrin_lab_5.xlsx';
        
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
                echo $petO_name = trim($value['Q']);
                echo "<br>";
                
                $practice_name = trim($value['G']);
                
                echo $practice_details = $this->PracticeModel->getPetOwnerVetru($petO_name,$practice_name);
                //print_r($practice_details);
                // $sheet_data['practice_id'] = $practice_details['id'];
                
                
                // $sheet_all_data[] = $sheet_data;
                
                
                
                
                
                $i++;

            }//foreach  
              
            
            
            $sheet_all_data = array_map("unserialize", array_unique(array_map("serialize", $sheet_all_data)));
            //echo "<pre>";
            //print_r($sheet_all_data); exit;
               
            // foreach ($sheet_all_data as $record) {
                
            //     $practice_id = $record['practice_id'];

                
            //     //$this->PracticeModel->delete_user_details($practice_id);
            //     //$this->PracticeModel->add_user_details($record);

            // }           
            
        } catch (Exception $e) {
            echo "ERROR !";
        }
                

    }

}

?>