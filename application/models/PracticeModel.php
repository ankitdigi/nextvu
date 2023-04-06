<?php
class PracticeModel extends CI_model{

    public function __construct() { 
        parent::__construct();
        $this->_table = 'ci_users';
    }

    public function getRecord($vetlab_name="",$final_country="") {

        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $vetlab_name); 
        $this->db->where('role', '2'); 
        //return $this->db->get()->row_array();    
        $total = $this->db->get()->num_rows();   
        
        if($total==0){
            $practiceDetailsData['name'] = $vetlab_name;
            $practiceDetailsData['last_name'] = NULL;
            $practiceDetailsData['email'] = NULL;
            $practiceDetailsData['password'] = NULL;
            $practiceDetailsData['role'] = 2;
            $practiceDetailsData['country'] = $final_country;
            $practiceDetailsData['age'] = NULL;
            $practiceDetailsData['post_code'] = NULL;
            $practiceDetailsData['phone_number'] = NULL;
            $practiceDetailsData['practice_code'] = NULL;
            $practiceDetailsData['created_by'] = 1;
            $practiceDetailsData['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert("ci_users",$practiceDetailsData);
            $practice_details_id = $this->db->insert_id();

            if($practice_details_id>0){
                $this->db->select('id,name');
                $this->db->from("ci_users");
                $this->db->where('id', $practice_details_id); 
                $this->db->where('role', '2'); 
                return $this->db->get()->row_array();

            }
        }   
      
    }

    public function getLabRecord($lab_name="") {

        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $lab_name); 
        $this->db->where('role', '6'); 
        //return $this->db->get()->row_array();    
        return $this->db->get()->num_rows();    
      
    }

    public function allRecord() {

        $this->db->select('*');
        $this->db->from("ci_orders");
        $this->db->where('order_type=3'); 
        //$this->db->where('lab_id!=0'); 
        //return $this->db->get()->row_array();    
        return $this->db->get()->result_array();
      
    }

    
    public function allRecordAddrs($arr = []) {
        
        $this->db->select('*');
        $this->db->from("ci_branches");
        if(isset($arr['id']) && $arr['id'] != ''){
        $this->db->where('id',$arr['id']);
		}else{
		$this->db->where('vet_user_id',$arr['vet_user_id']);
		} 
        $this->db->where('vet_user_id',$arr['vet_user_id']); 
  
        return $this->db->get()->row_array();
      
    }

    public function allPets() {

        $this->db->select('ci_orders.*,ci_pets.name AS pet_name,ci_pets.type AS pet_type');
        $this->db->from("ci_orders");
        $this->db->join('ci_pets', 'ci_orders.pet_id = ci_pets.id','left');
        $this->db->where('order_type=1'); 
        //$this->db->where('plc_selection=1'); 
        $this->db->where('plc_selection=2'); 
        //return $this->db->get()->row_array();    
        $orders_data = $this->db->get()->result_array();  
        
        $petData = [];
        $update_orderData = [];
        foreach ($orders_data as $key => $order) {

            // echo $order_id = $order['id'];
            // echo "::";
            // echo $pet_id = $order['pet_id'];
            // echo "<br>";
            $order_id = $order['id'];
            $practice_id = $order['vet_user_id'];
            $petOwner_id = $order['pet_owner_id'];
            $pet_id = $order['pet_id'];
            $pet_name = $order['pet_name'];
            $pet_type = $order['pet_type'];
            
            
            if(  $pet_id > 0 && $petOwner_id > 0 && $practice_id>0  ){
                
                //get pet id
                $this->db->select('*');
                $this->db->from("ci_pets");
                $this->db->where('id', $pet_id); 
                $this->db->where('pet_owner_id', $petOwner_id); 
                $this->db->where('vet_user_id', $practice_id);
                $pet_query = $this->db->get();
                $pet_details = $pet_query->row_array(); 
                $pet_total = $pet_query->num_rows(); 
                //print_r($pet_details);

                if( $pet_total == 0){
                    // echo $order_id = $order['id'];
                    // echo "::";  
                    // echo $pet_total;
                    // echo "<br>"; 

                    //insert new pet
                    $petData['vet_user_id'] = $practice_id; 
                    $petData['pet_owner_id'] = $petOwner_id;
                    $petData['name'] = $pet_name; 
                    $petData['type'] = $pet_type;
                    $petData['created_by'] = '1';
                    $petData['created_at'] = date("Y-m-d H:i:s"); 
                    // echo "<pre>";
                    // print_r($petData);
                    
                    $this->db->insert("ci_pets",$petData);
                    $new_pet_id = $this->db->insert_id();

                    //update order table
                    $update_orderData['pet_id'] = $new_pet_id;
                    $this->db->where('id', $order_id);
                    $update =  $this->db->update("ci_orders",$update_orderData);
                    echo $this->db->last_query(); 
                    echo "<br>"; 

                }//if pet_total

            }//if pet_id, petOwner_id and practice_id
            
             
            
        }//foreach
        
      
    }

    public function getAllergens($aller_arr=[]) {
        $aller = [];
        $arr_u = [];
        $allergens = '';
        foreach ($aller_arr as $key => $value) {
            $this->db->select('id');
            $this->db->from("ci_allergens");
            $this->db->where('code',$value); 
            $all = $this->db->get()->row_array();
            if($all!='' && $all!=NULL && $all!=null){
                $aller[] = $all['id'];
            }
            
        }
        $arr_u = array_unique($aller);
        //print_r($arr_u);
        if( !empty($arr_u) ){
            $allergens  = json_encode($arr_u);
        }
        
        //echo "<br>"; exit;
        return $allergens;
        
    }

    public function getCategory($allergen_name="") {

        $this->db->select('id,name');
        $this->db->from("ci_allergens");
        $this->db->where('name', $allergen_name); 
        $this->db->where('parent_id', '0'); 
        //return $this->db->get()->row_array();    
        return $this->db->get()->num_rows();    
      
    }

    public function getPetOwner($petO_name="", $country="",$practice_name="") {

        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('role', '3'); 
        $this->db->where('last_name', $petO_name);
        //return $this->db->get()->row_array();    
        echo $is_avail =  $this->db->get()->num_rows();    
        echo "<br>";
        $petOwnerData = [];
        $petownersToVetUsersData = [];
        if($is_avail == 0){
            $petOwnerData['last_name'] = $petO_name;
            $petOwnerData['role'] = '3';
            $petOwnerData['country'] = $country;
            $petOwnerData['post_code'] = NULL;
            $petOwnerData['created_by'] = '1';
            $petOwnerData['created_at'] = date("Y-m-d H:i:s");

            $this->db->insert("ci_users",$petOwnerData);
            echo $petOwner_id = $this->db->insert_id();
            echo "<br>";
            //get practice id
            $this->db->select('id,name');
            $this->db->from("ci_users");
            $this->db->where('name', $practice_name); 
            $this->db->where('role', '2');  
            echo $this->db->last_query(); 
            $practice_details = $this->db->get()->row_array();

            if( !empty($practice_details) ){

                //insert to ci_petowners_to_vetusers
                $petownersToVetUsersData['pet_owner_id'] = $petOwner_id;
                $petownersToVetUsersData['vet_user_id'] = $practice_details['id'];
                $petownersToVetUsersData['user_type'] = '2';

                $this->db->insert("ci_petowners_to_vetusers",$petownersToVetUsersData);
                $petownersToVetUsers_id = $this->db->insert_id();
            }

        }
        echo "====";
      
    }

    public function getPetOwnerVetru($petO_name="", $practice_name="") {

        //get petowner id
        $this->db->select('id,last_name');
        $this->db->from("ci_users");
        $this->db->where('last_name', $petO_name); 
        $this->db->where('role', '3');  
        $petowner_details = $this->db->get()->row_array();

        //get practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $practice_name); 
        $this->db->where('role', '2');  
        $practice_details = $this->db->get()->row_array();

        //check it is availabel
        $petOwner_id = $petowner_details['id'];
        $practice_id = $practice_details['id'];
        $this->db->select('id');
        $this->db->from("ci_petowners_to_vetusers_test");
        $this->db->where('pet_owner_id', $petOwner_id); 
        $this->db->where('vet_user_id', $practice_id);
        $petowners_to_vetusers_avail = $this->db->get()->num_rows();    
        
        $petownersToVetUsersData = [];
        if($petowners_to_vetusers_avail == 0){

            //insert to ci_petowners_to_vetusers
            $petownersToVetUsersData['pet_owner_id'] = $petOwner_id;
            $petownersToVetUsersData['vet_user_id'] = $practice_id;
            $petownersToVetUsersData['user_type'] = '2';

            $this->db->insert("ci_petowners_to_vetusers_test",$petownersToVetUsersData);
            $petownersToVetUsers_id = $this->db->insert_id();
        }
      
    }

    public function getPets($pet_name="", $petO_name="", $practice_name="",$type="") {

        $this->db->select('id,name');
        $this->db->from("ci_pets");
        $this->db->where('name', $pet_name);
        //return $this->db->get()->row_array();    
        echo $is_avail =  $this->db->get()->num_rows();    
        echo "<br>";
        $petData = [];
        if( $is_avail==0 && $pet_name!=''){

            //get practice id
            $this->db->select('id,name');
            $this->db->from("ci_users");
            $this->db->where('name', $practice_name); 
            $this->db->where('role', '2');  
            $practice_details = $this->db->get()->row_array();

            //get petowner id
            $this->db->select('id,last_name');
            $this->db->from("ci_users");
            $this->db->where('last_name', $petO_name);
            $this->db->where('role', '3');  
            $petowner_details = $this->db->get()->row_array();

            if( !empty($practice_details) ){

                $petData['vet_user_id'] = $practice_details['id']; 
            }

            if( !empty($petowner_details) ){

                $petData['pet_owner_id'] = $petowner_details['id']; 
            }

            $petData['name'] = $pet_name;
            
            if ( $type=='Dog' || $type=='D' || $type=='DOG' ){
                $type = 2;
            }elseif ( $type=='C' || $type=='Cat' || $type=='CAT' ) {
                $type = 1;
            }elseif ( $type=='H' || $type=='Horse' || $type=='HORSE' ) {
                $type = 3;
            }else{
                $type=NULL;
            }

            $petData['type'] = $type;
            $petData['created_by'] = '1';
            $petData['created_at'] = date("Y-m-d H:i:s");

            $this->db->insert("ci_pets",$petData);
            $pet_id = $this->db->insert_id();

        }
        echo "====";
    }

    public function getOrders($pet_name="", $petO_name="", $practice_name="",$allergens="",$delivery_practice="",$order_date='',$batch_number='',$ocr='',$delivery_cust_ref='',$customer_order_ref='') {

        $orderData = [];

        //get practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $practice_name); 
        $this->db->where('role', '2');  
        $practice_details = $this->db->get()->row_array();

        //get petowner id
        $this->db->select('id,last_name');
        $this->db->from("ci_users");
        $this->db->where('last_name', $petO_name); 
        $this->db->where('role', '3');  
        $petowner_details = $this->db->get()->row_array();

        //get pet id
        $this->db->select('id,name');
        $this->db->from("ci_pets");
        $this->db->where('name', $pet_name); 
        $pet_details = $this->db->get()->row_array();

        if( !empty($practice_details) ){
            $orderData['vet_user_id'] = $practice_details['id']; 
        }else{
            $orderData['vet_user_id'] = 0; 
        }

        if( !empty($petowner_details) ){
            $orderData['pet_owner_id'] = $petowner_details['id']; 
        }else{
            $orderData['pet_owner_id'] = 0;
        }

        if( !empty($pet_details) ){
            $orderData['pet_id'] = $pet_details['id']; 
        }else{
            $orderData['pet_id'] = 0; 
        }

        $orderData['allergens'] = $allergens;

        //auto generate order number
        $this->db->select('MAX(order_number) AS order_number');
        $this->db->from("ci_orders");
        $order_number = $this->db->get()->row_array(); 
        
        if($order_number['order_number']=='' || $order_number['order_number']==NULL || $order_number['order_number']==0){
            $final_order_number = 1001;
        }else{
            $final_order_number = $order_number['order_number']+1;
        }

        //get delivery practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $delivery_practice); 
        $this->db->where('role', '2'); 
        $practice_d = $this->db->get()->row_array();

        $orderData['batch_number'] = $batch_number;
        $orderData['order_date'] = $order_date;
        $orderData['reference_number'] = $ocr;
        $orderData['order_type'] = '1';
        $orderData['sub_order_type'] = '1';
        $orderData['plc_selection'] = '1';
        $orderData['order_number'] = $final_order_number;
        $orderData['delivery_cust_ref'] = $delivery_cust_ref;
        $orderData['customer_order_ref'] = $customer_order_ref;
        $orderData['delivery_practice_id'] = (!empty($practice_d)) ? $practice_d['id'] : 0;
        $orderData['order_can_send_to'] = (!empty($practice_d)) ? 1 : 0;
        $orderData['created_by'] = '1';
        $orderData['created_at'] = date("Y-m-d H:i:s");

        // echo "<pre>";
        // print_r($orderData);
        $this->db->insert("ci_orders",$orderData);
        return $order_id = $this->db->insert_id();
        
    }

    public function getLabOrders($pet_name="", $petO_name="", $lab_name="",$allergens="",$delivery_practice="",$order_date='',$batch_number='',$ocr='',$delivery_cust_ref='',$customer_order_ref='',$practice_name='') {

        $orderData = [];

        //get lab id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $lab_name); 
        $this->db->where('role', '6');  
        $lab_details = $this->db->get()->row_array();

        //get petowner id
        $this->db->select('id,last_name');
        $this->db->from("ci_users");
        $this->db->where('last_name', $petO_name); 
        $this->db->where('role', '3');  
        $petowner_details = $this->db->get()->row_array();

        //get pet id
        $this->db->select('id,name');
        $this->db->from("ci_pets");
        $this->db->where('name', $pet_name); 
        $pet_details = $this->db->get()->row_array();

        //get practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $practice_name); 
        $this->db->where('role', '2');  
        $practice_details = $this->db->get()->row_array();

        if( !empty($practice_details) ){
            $orderData['vet_user_id'] = $practice_details['id']; 
        }else{
            $orderData['vet_user_id'] = 0; 
        }

        if( !empty($lab_details) ){
            $orderData['lab_id'] = $lab_details['id']; 
        }else{
            $orderData['lab_id'] = 0; 
        }

        if( !empty($petowner_details) ){
            $orderData['pet_owner_id'] = $petowner_details['id']; 
        }else{
            $orderData['pet_owner_id'] = 0;
        }

        if( !empty($pet_details) ){
            $orderData['pet_id'] = $pet_details['id']; 
        }else{
            $orderData['pet_id'] = 0; 
        }

        $orderData['allergens'] = $allergens;

        //auto generate order number
        $this->db->select('MAX(order_number) AS order_number');
        $this->db->from("ci_orders");
        $order_number = $this->db->get()->row_array(); 
        
        if($order_number['order_number']=='' || $order_number['order_number']==NULL || $order_number['order_number']==0){
            $final_order_number = 1001;
        }else{
            $final_order_number = $order_number['order_number']+1;
        }

        //get delivery practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $delivery_practice); 
        $this->db->where('role', '2'); 
        $practice_d = $this->db->get()->row_array();

        //check if lab and practice is same or not
        $delivery_practice_id = 0;
        $order_can_send_to = 0;
        $labd_id = $lab_details['id'];
        $pracd_id = $practice_details['id'];
        if( $labd_id!=$pracd_id ){
            $delivery_practice_id = $pracd_id;
            $order_can_send_to = 1;
        }

        $orderData['batch_number'] = $batch_number;
        $orderData['order_date'] = $order_date;
        $orderData['reference_number'] = $ocr;
        $orderData['order_type'] = '1';
        $orderData['sub_order_type'] = '1';
        $orderData['plc_selection'] = '2';
        $orderData['order_number'] = $final_order_number;
        $orderData['delivery_cust_ref'] = $delivery_cust_ref;
        $orderData['customer_order_ref'] = $customer_order_ref;
        $orderData['delivery_practice_id'] = $delivery_practice_id;
        $orderData['order_can_send_to'] = $order_can_send_to;
        $orderData['created_by'] = '1';
        $orderData['created_at'] = date("Y-m-d H:i:s");

        // echo "<pre>";
        // print_r($orderData);
        $this->db->insert("ci_orders",$orderData);
        return $order_id = $this->db->insert_id();
        
    }

    public function getSkinOrders($pet_name="", $petO_name="", $lab_name="",$allergens="",$delivery_practice="",$order_date='',$batch_number='',$ocr='',$delivery_cust_ref='',$customer_order_ref='',$practice_name='') {

        $orderData = [];

        //get practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $practice_name); 
        $this->db->where('role', '2');  
        $practice_details = $this->db->get()->row_array();

        if( !empty($practice_details) ){
            $orderData['vet_user_id'] = $practice_details['id']; 
        }else{
            $orderData['vet_user_id'] = 0; 
        }

        
        $orderData['lab_id'] = 0; 
        $orderData['pet_owner_id'] = 0;
        $orderData['pet_id'] = 0; 
        

        $orderData['allergens'] = $allergens;

        //auto generate order number
        $this->db->select('MAX(order_number) AS order_number');
        $this->db->from("ci_orders");
        $order_number = $this->db->get()->row_array(); 
        
        if($order_number['order_number']=='' || $order_number['order_number']==NULL || $order_number['order_number']==0){
            $final_order_number = 1001;
        }else{
            $final_order_number = $order_number['order_number']+1;
        }

        //get delivery practice id
        $this->db->select('id,name');
        $this->db->from("ci_users");
        $this->db->where('name', $delivery_practice); 
        $this->db->where('role', '2'); 
        $practice_d = $this->db->get()->row_array();

        //check if lab and practice is same or not
        $delivery_practice_id = 0;
        $order_can_send_to = 0;
        $prac_id = $practice_details['id'];
        $pracd_id = $practice_d['id'];
        if( $prac_id!=$pracd_id ){
            $delivery_practice_id = $pracd_id;
            $order_can_send_to = 1;
        }

        $orderData['batch_number'] = $batch_number;
        $orderData['order_date'] = $order_date;
        $orderData['reference_number'] = $ocr;
        $orderData['order_type'] = '3';
        $orderData['sub_order_type'] = '4';
        $orderData['plc_selection'] = '1';
        $orderData['order_number'] = $final_order_number;
        $orderData['delivery_cust_ref'] = $delivery_cust_ref;
        $orderData['customer_order_ref'] = $customer_order_ref;
        $orderData['delivery_practice_id'] = $delivery_practice_id;
        $orderData['order_can_send_to'] = $order_can_send_to;
        $orderData['created_by'] = '1';
        $orderData['created_at'] = date("Y-m-d H:i:s");

        // echo "<pre>";
        // print_r($orderData);
        $this->db->insert("ci_orders",$orderData);
        return $order_id = $this->db->insert_id();
        
    }

    public function order_edit($price_data = [],$id='') {
        if (isset($id) && is_numeric($id)>0) {
            $this->db->where('id', $id);
            $update =  $this->db->update("ci_orders",$price_data);
        }
    }

    public function delete_user_details($practice_id="") {

        //delete from user_details
        $this->db->where('user_id', $practice_id);
        $this->db->delete('ci_user_details'); 
      
    }

    public function add_user_details($record=[]) {
        
        $practiceDetailsData = [];
        for ($j=1; $j <=24 ; $j++) { 

            
            $practiceDetailsData['user_id'] = $record['practice_id'];
            if($j==1){
                $practiceDetailsData['column_name'] = 'address_1';
                $practiceDetailsData['column_field'] = NULL;
            }if($j==2){
                $practiceDetailsData['column_name'] = 'address_2';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==3){
                $practiceDetailsData['column_name'] = 'address_3';
                $practiceDetailsData['column_field'] = $record['post_code']; 
            }if($j==4){
                $practiceDetailsData['column_name'] = 'account_ref';
                $practiceDetailsData['column_field'] = $record['account_ref']; 
            }if($j==5){
                $practiceDetailsData['column_name'] = 'tax_code';
                $practiceDetailsData['column_field'] = NULL;
            }if($j==6){
                $practiceDetailsData['column_name'] = 'vat_reg';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==7){
                $practiceDetailsData['column_name'] = 'country_code';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==8){
                $practiceDetailsData['column_name'] = 'comment';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==9){
                $practiceDetailsData['column_name'] = 'corporates';
                $practiceDetailsData['column_field'] = NULL;
            }if($j==10){
                $practiceDetailsData['column_name'] = 'labs';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==11){
                $practiceDetailsData['column_name'] = 'referrals';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==12){
                $practiceDetailsData['column_name'] = 'rcds_number';
                $practiceDetailsData['column_field'] = NULL; 
            }if($j==13){
                $practiceDetailsData['column_name'] = 'add_1';
                $practiceDetailsData['column_field'] = $record['address_1']; 
            }if($j==14){
                $practiceDetailsData['column_name'] = 'add_2';
                $practiceDetailsData['column_field'] = $record['address_2'];  
            }if($j==15){
                $practiceDetailsData['column_name'] = 'add_3';
                $practiceDetailsData['column_field'] = $record['address_3'];  
            }if($j==16){
                $practiceDetailsData['column_name'] = 'add_4';
                $practiceDetailsData['column_field'] = $record['address_4'];  
            }if($j==17){
                $practiceDetailsData['column_name'] = 'order_can_send_to';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==18){
                $practiceDetailsData['column_name'] = 'odelivery_address';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==19){
                $practiceDetailsData['column_name'] = 'opostal_code';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==20){
                $practiceDetailsData['column_name'] = 'ocity';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==21){
                $practiceDetailsData['column_name'] = 'ocountry';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==22){
                $practiceDetailsData['column_name'] = 'buying_groups';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==23){
                $practiceDetailsData['column_name'] = 'delivery_practice_id';
                $practiceDetailsData['column_field'] = NULL;  
            }if($j==24){
                $practiceDetailsData['column_name'] = 'delivery_branch_id';
                $practiceDetailsData['column_field'] = NULL;  
            }
            
            $practiceDetailsData['created_at'] = date("Y-m-d H:i:s");

            $this->db->insert("ci_user_details",$practiceDetailsData);
            $practice_details_id = $this->db->insert_id();
            echo $this->db->last_query(); 
            echo "<br>";
        }
      
    }

    


}