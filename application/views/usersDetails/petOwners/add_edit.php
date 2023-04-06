<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pet Owners
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Users Management</a></li>
            <li class="active">Pet Owners</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!--alert msg-->
        <?php if(!empty($this->session->flashdata('success'))){ ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>

        <?php if(!empty($this->session->flashdata('error'))){ ?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>
        <!--alert msg-->

        <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div><!-- /.box-header -->

                <!-- form start -->
                <?php echo form_open('', array('name'=>'petOwnerForm', 'id'=>'petOwnerForm')); ?>

                <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6">

                        <div class="form-group">
                        <label>Pet Owner First Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
                        <?php echo form_error('name', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Pet Owner Last Name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" value="<?php echo set_value('last_name',isset($data['last_name']) ? $data['last_name'] : '');?>" required="">
                        <?php echo form_error('last_name', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>">
                        <?php echo form_error('email', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php echo set_value('password');?>">
                        <?php echo form_error('password', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Pet Owner Age</label>
                        <input type="text" class="form-control" name="age" placeholder="Age in years" value="<?php echo set_value('age',isset($data['age']) ? $data['age'] : '');?>">
                        <?php echo form_error('age', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Pet Owner Post Code</label>
                        <input type="text" class="form-control" name="post_code" placeholder="Post Code" value="<?php echo set_value('post_code',isset($data['post_code']) ? $data['post_code'] : '');?>" required="">
                        <?php echo form_error('post_code', '<div class="error">', '</div>'); ?>
                        </div>

                        <?php if( $userData['role']==1 || $userData['role']==3 ){ ?>
                        
                        <div class="form-group">
                            <div class="radio">
                                
                                <label><input type="radio" name="user_type" id="user_type1" value="2" <?php if($id==""){echo 'checked=""';}elseif(isset($data['user_type']) && $data['user_type']=='2'){echo 'checked=""';}else{ echo "";} ?>>Practice</label>
                                
                                <label><input type="radio" name="user_type" id="user_type2" value="8" <?php echo (isset($data['user_type']) && $data['user_type']=='8') ? 'checked=""' : "" ;?>>Referral Practice</label>
                                
                            </div>
                            <?php echo form_error('user_type', '<div class="error">', '</div>'); ?>
                        </div>
                        
                        <div class="form-group">
                        <?php
                        $options = array();
                        if(!empty($vatLabUsers)){
                            $selected_data = [];
                            foreach ($vatLabUsers as $user) {
                                //print_r($user);
                                $user_id = $user['id'];
                                $options[$user_id] = $user['name'];
                            }
                        }
                       
                        if(!empty($ids['ids'])){
                            $selected = explode(",",$ids['ids']);
                        }else{
                            $selected = "";
                        }
                        
                        $attr = 'class="form-control parent_id selectpicker" required="" data-live-search="true" multiple="" ';
                        echo form_multiselect('parent_id[]', $options, $selected, $attr);
                        ?>
                        
                        <?php echo form_error('parent_id[]', '<div class="error">', '</div>'); ?>
                        </div>
                        
                        <?php if($id=="" || ( isset($branch_ids['branch_id']) && $branch_ids['branch_id']!='')){$hidden_cls = "";}else{$hidden_cls = "hidden";} ?>
                        <div class="form-group branch_cls <?php echo $hidden_cls; ?>">
                        <label>Branches</label>
                        <?php
                        $options = array();
                        if(!empty($branches)){
                            $selected_data = [];
                            foreach ($branches as $branch) {
                                //print_r($user);
                                $branch_id = $branch['id'];
                                $branch_post_code = ($branch['postcode']!='') ? ' - '.$branch['postcode'] : '';
                                $options[$branch_id] = $branch['name'].$branch_post_code;
                            }
                        }
                       
                        if(!empty($branch_ids['branch_id'])){
                            $selected = explode(",",$branch_ids['branch_id']);
                        }else{
                            $selected = "";
                        }
                        
                        $attr = 'class="form-control branch_id selectpicker" data-live-search="true" multiple="" ';
                        echo form_multiselect('branch_id[]', $options, $selected, $attr);
                        ?>
                        
                        <?php echo form_error('branch_id[]', '<div class="error">', '</div>'); ?>
                        </div>
                        <?php } ?>

                    </div><!-- /.col -->

                </div><!-- /.row -->
                </div><!-- /.box-body -->
                

                <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary" value="1">Submit</button>
                </div>
                <?php echo form_close(); ?>
                <!-- form end -->

            </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div><!-- /.row -->
      
    
    </section><!-- /.content -->
    
</div>
<!-- /.content-wrapper -->
 
<?php $this->load->view("footer"); ?>  
  
</div><!-- ./wrapper -->

<?php $this->load->view("script"); ?>

<script>
$(document).ready(function(){
    $('#petOwnerForm').parsley();

    //Date picker
    $('input[name="order_date"]').datepicker({
        format: "dd/mm/yyyy",
        todayHighlight: true,
        autoclose: true,
    });

    $('input[type=radio][name=user_type]').change(function() {

        user_type = this.value;
        if(user_type==2){
            $('.branch_cls').removeClass('hidden');
        }else{
            $('.branch_cls').addClass('hidden');
        }
        if (user_type) {
            $.ajax({
                url:      "<?php echo base_url('Users/get_users_dropdown'); ?>",
                type:     'POST',
                data:     {'user_type':user_type},
                dataType: "json",
                success:  function (data) {
                    
                    $('#cover-spin').hide();
                    $('.parent_id').selectpicker('destroy');
                    $('.parent_id').empty();
                    $('.parent_id').append('<option value="">-- Select --</option>');
                    $.each(data, function(key, value) {
                        $('.parent_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                    $('.parent_id').addClass('selectpicker').selectpicker('refresh');
                    
                }
            });
        }else{
            $('.parent_id').selectpicker('destroy');
            $('.parent_id').empty();
        } 
        
    });

    $('select[name="parent_id[]"]').on('change', function() {
        var filtered_vet_user_id = $(this).val();
        //console.log(filtered_vet_user_id); return false;
        
        if(filtered_vet_user_id){
            $.ajax({
                url:      "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
                type:     'POST',
                data:     {'vet_user_id':filtered_vet_user_id},
                dataType: "json",
                success:  function (data) {
                    
                    $('#cover-spin').hide();
                    $('.branch_id').selectpicker('destroy');
                    $('.branch_id').empty();
                    $('.branch_id').append('<option value="">-- Select --</option>');
                    $.each(data, function(key, value) {
                        var branch_postcode = '';
                        if( value.postcode != '' ){
                            branch_postcode = ' - '+value.postcode;
                        }
                        $('.branch_id').append('<option value="'+value.id+'">'+value.name+branch_postcode+'</option>');
                    });
                    $('.branch_id').addClass('selectpicker').selectpicker('refresh');
                    
                }
            });
        }else{
            $('.branch_id').selectpicker('destroy');
            $('.branch_id').empty();
        }
        
    });//select vet_user_id
});
</script>

</body>
</html>
