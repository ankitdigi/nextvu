<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ImportPets extends CI_Controller {

  	public function __construct(){
		parent::__construct();
        if($this->session->userdata('logged_in') !== TRUE){
            redirect('users/index');
        }
    }

	
    function import_data(){
        
        $inputFileName = FCPATH.'uploaded_files/orderData/Book2.xlsx';
        
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

                //get pet id from name
                $pet_name = trim($value['R']);
                if($pet_name!=''){
                    $pet_name = trim($pet_name);
                    $this->db->select('id,name');
                    $this->db->from("ci_pets");
                    $this->db->where('name', $pet_name); 
                    //echo $this->db->last_query(); 
                    //$pet_details = $this->db->get()->row_array();
                    $pet_exists = $this->db->get()->num_rows();
                    
                    if( $pet_exists==0 ){

                        //get practice id
                        $practice_name = trim($value['D']);
                        $this->db->select('id,name');
                        $this->db->from("ci_users");
                        $this->db->where('name', $practice_name); 
                        $this->db->where('role', '2');  
                        //echo $this->db->last_query(); 
                        $practice_details = $this->db->get()->row_array();
                        //$practice_exists = $this->db->get()->num_rows();

                        //get petowner id
                        $petowner_name = trim($value['S']);
                        $this->db->select('id,last_name');
                        $this->db->from("ci_users");
                        $this->db->where('name', $petowner_name); 
                        $this->db->or_where('last_name', $petowner_name);
                        $this->db->where('role', '3');  
                        //echo $this->db->last_query(); 
                        $petowner_details = $this->db->get()->row_array();
                        //$practice_exists = $this->db->get()->num_rows();


                        if( !empty($practice_details) ){

                            $petData['vet_user_id'] = $practice_details['id']; 
                        }

                        if( !empty($petowner_details) ){

                            $petData['pet_owner_id'] = $petowner_details['id']; 
                        }

                            
                        $petData['name'] = trim($value['R']);
                        $type = NULL;
                        $type = trim($value['P']);
                        if ( $type=='Dog' || $type=='D' || $type=='DOG' ){
                            $type = 2;
                        }elseif ( $type=='C' || $type=='Cat' || $type=='CAT' ) {
                            $type = 1;
                        }elseif ( $type=='H' || $type=='Horse' || $type=='HORSE' ) {
                            $type = 3;
                        }
                        $petData['type'] = $type;
                        $petData['created_by'] = '1';
                        $petData['created_at'] = $date;
    
                        $this->db->insert("ci_pets",$petData);
                        $pet_id = $this->db->insert_id();

                        

                        

                    }
                    

                }
                
                
                
                $i++;

            }//foreach  
              
             
               
            if($pet_id){
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