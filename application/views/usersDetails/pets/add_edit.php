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
            Pets
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Users Management</a></li>
            <li class="active">Pets</li>
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
                <?php echo form_open('', array('name'=>'petForm', 'id'=>'petForm')); ?>

                <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6">

                        <?php
                        if($userData['role']==1){ ?>
                        <div class="form-group">
                        <label>Practices</label>
                        <?php 
                        $options = array();
                        $options[''] = '-- Select --';
                        if(!empty($vatLabUsers)){
                            foreach ($vatLabUsers as $user) {
                                $user_id = $user['id'];
                                $options[$user_id] = $user['name'];
                            }
                        }
                        $attr = 'class="form-control" data-live-search="true" required=""';
                        echo form_dropdown('vet_user_id',$options,set_value('vet_user_id',isset($data['vet_user_id']) ? $data['vet_user_id'] : ''),$attr); ?>
                        <?php echo form_error('vet_user_id', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Branches</label>
                        <?php 
                        $branches_options = array();
                        $branches_options[''] = '-- Select --';
                        if(!empty($branches)){
                            foreach ($branches as $branch) {
                                $user_id = $branch['id'];
                                $branch_post_code = ($branch['postcode']!='') ? ' - '.$branch['postcode'] : '';
                                $branches_options[$user_id] = $branch['name'].$branch_post_code;
                            }
                        }
                        $branches_attr = 'class="form-control" data-live-search="true"  onChange="getPetOwners();"';
                        echo form_dropdown('branch_id',$branches_options,set_value('branch_id',isset($data['branch_id']) ? $data['branch_id'] : ''),$branches_attr); ?>
                        <?php echo form_error('branch_id', '<div class="error">', '</div>'); ?>
                        </div>
                        <?php } ?>

                        <?php if($userData['role']==1 ||$userData['role']==2){ ?>
                        
                        <div class="form-group">
                        <label>Pet Owners</label>
                        <select class="form-control" name="pet_owner_id" id="pet_owner_id" required="">
                            <option value="">--Select--</option>
                            <?php foreach ( $petOwners as $p_user ){ ?>
                                
                            <option value="<?php echo $p_user['id']; ?>"<?php if(isset($id) && $id>0 && ($p_user['id']==$data['pet_owner_id'])) echo 'selected="selected"'; ?>><?php echo $p_user['last_name']; ?></option>

                            <?php }?>
                        </select>
                        <?php echo form_error('pet_owner_id', '<div class="error">', '</div>'); ?>
                        </div>
                        <?php } ?>

                        <div class="form-group">
                        <label>Pet Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
                        <?php echo form_error('name', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Species</label>
                        <!-- <select class="form-control" name="type" id="type" required="">
                            <option value="">--Select--</option>
                            <option value="1" <?php //if(isset($id) && $id>0 && ($data['type']==1)) echo 'selected="selected"'; ?>>Cat</option>
                            <option value="2" <?php //if(isset($id) && $id>0 && ($data['type']==2)) echo 'selected="selected"'; ?>>Dog</option>
                            <option value="3" <?php //if(isset($id) && $id>0 && ($data['type']==3)) echo 'selected="selected"'; ?>>Bird</option>
                        </select> -->
                        <?php 
                        $toptions = array();
                        $toptions[''] = '-- Select --';
                        if(!empty($species)){
                            foreach ($species as $specie) {
                                $specie_id = $specie['id'];
                                $toptions[$specie_id] = $specie['name'];
                            }
                        }
                        $tattr = 'class="form-control" data-live-search="true" required=""';
                        echo form_dropdown('type',$toptions,set_value('type',isset($data['type']) ? $data['type'] : ''),$tattr); ?>
                        <?php echo form_error('type', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Breeds</label>
                        <?php 
                        
                        $breed_options = array();
                        $breed_options[''] = '-- Select --';
                        if(!empty($breeds)){
                            foreach ($breeds as $breed) {
                                $user_id = $breed['id'];
                                $breed_options[$user_id] = $breed['name'];
                            }
                        }
                        $breed_options[0] = 'Other';
                        $breed_attr = 'class="form-control breed_id" data-live-search="true" required=""';
                        echo form_dropdown('breed_id',$breed_options,set_value('breed_id',isset($data['breed_id']) ? $data['breed_id'] : ''),$breed_attr); ?>
                        <?php echo form_error('breed_id', '<div class="error">', '</div>'); ?>
                        </div>

                        <?php if($id>0 && $data['other_breed']!=''){$hidden_cls = "";}else{$hidden_cls = "hidden";} ?>
                        <div class="form-group other_breed <?php echo $hidden_cls; ?>">
                        <label>Breed Type</label>
                        <input type="text" class="form-control" name="other_breed" placeholder="Enter Breed Type" value="<?php echo set_value('other_breed',isset($data['other_breed']) ? $data['other_breed'] : '');?>">
                        <?php echo form_error('other_breed', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth</label>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Age in Years</label>
                            <input type="number" class="form-control" name="age_year" placeholder="Enter Age in Years" value="<?php echo set_value('age_year',isset($data['age_year']) ? $data['age_year'] : '');?>" maxlength="4" min="0" max="100">
                            <?php echo form_error('age_year', '<div class="error">', '</div>'); ?>
                        </div><!--col-xs-6-->
						<div class="form-group col-xs-6">
                            <label>Age in Months</label>
                            <input type="number" class="form-control" name="age" placeholder="Enter Age in Months" value="<?php echo set_value('age',isset($data['age']) ? $data['age'] : '');?>" maxlength="2" min="1" max="11">
                            <?php echo form_error('age', '<div class="error">', '</div>'); ?>
                        </div><!--col-xs-6-->

                        <div class="form-group">
                            <label>Gender</label>
                            <div class="radio">
                                <label><input type="radio" name="gender" id="gender1" value="1" <?php if($id==""){echo 'checked=""';}elseif(isset($data['gender']) && $data['gender']=='1'){echo 'checked=""';}else{ echo "";} ?>>Male</label>
                                <label><input type="radio" name="gender" id="gender2" value="2" <?php echo (isset($data['gender']) && $data['gender']=='2') ? 'checked=""' : "" ;?>>Female</label>
                            </div>
                            <?php echo form_error('gender', '<div class="error">', '</div>'); ?>
                        </div>

                        <!-- <div class="form-group">
                        <label>Allergens</label>
                        <select class="form-control" name="allergen_id" id="allergen_id" required="">
                            <option value="">--Select--</option>
                            <?php //foreach ( $allergens as $allergen ){ ?>
                                
                            <option value="<?php //echo $allergen['id']; ?>"<?php //if(isset($id) && $id>0 && ($allergen['id']==$data['allergen_id'])) echo 'selected="selected"'; ?>><?php //echo $allergen['name']; ?></option>

                            <?php //}?>
                        </select>
                        <?php //echo form_error('allergen_id', '<div class="error">', '</div>'); ?>
                        </div> -->

                        <div class="form-group">
                        <label>Practice Comments</label>
                        <textarea class="form-control" name="comment" rows="3" placeholder="Enter Comment" required=""><?php echo set_value('comment',isset($data['comment']) ? $data['comment'] : '');?></textarea>
                        <?php echo form_error('comment', '<div class="error">', '</div>'); ?>
                        </div>

                        <?php if($userData['role']==1 ||$userData['role']==2){ ?>
                        <div class="form-group">
                        <label>Nextmune Comments</label>
                        <textarea class="form-control" name="nextmune_comment" rows="3" placeholder="Enter Comment"><?php echo set_value('nextmune_comment',isset($data['nextmune_comment']) ? $data['nextmune_comment'] : '');?></textarea>
                        <?php echo form_error('nextmune_comment', '<div class="error">', '</div>'); ?>
                        </div>
                        <?php }?>

                    </div><!-- /.col -->

                </div><!-- /.row -->
                </div><!-- /.box-body -->
                

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
    $('#petForm').parsley();

    //Date picker
    $('input[name="order_date"]').datepicker({
        format: "dd/mm/yyyy",
        todayHighlight: true,
        autoclose: true,
    });

    $('select[name="vet_user_id"]').on('change', function() {
        var filtered_vet_user_id = [];
        filtered_vet_user_id.push($(this).val());
        
        if(filtered_vet_user_id){
            $.ajax({
                url:      "<?php echo base_url('UsersDetails/get_branch_dropdown'); ?>",
                type:     'POST',
                data:     {'vet_user_id':filtered_vet_user_id},
                dataType: "json",
                success:  function (data) {
                    $('#cover-spin').hide();
                    $('select[name="branch_id"]').empty();
                    $('select[name="branch_id"]').append('<option value="">-- Select --</option>');
                    $.each(data, function(key, value) {
                        var branch_postcode = '';
                        if( value.postcode != '' ){
                            branch_postcode = ' - '+value.postcode;
                        }
                        $('select[name="branch_id"]').append('<option value="'+value.id+'">'+value.name+branch_postcode+'</option>');
                    });
                }
            });
        }else{
            $('select[name="branch_id"]').empty();
        }

        getPetOwners();

    });//select vet_user_id

    //$('select[name="type"]').on('change', function() {
    $(document).on('change', 'select[name="type"]', function(event) {
        var filtered_type = [];
        filtered_type.push($(this).val());
        
        if(filtered_type){
            $.ajax({
                url:      "<?php echo base_url('Breeds/get_breeds_dropdown'); ?>",
                type:     'POST',
                data:     {'species_id':filtered_type},
                dataType: "json",
                success:  function (data) {
                    $('#cover-spin').hide();
                    $('.breed_id').empty();
                    $('.breed_id').append('<option value="">-- Select --</option>');
                    $.each(data, function(key, value) {
                        $('.breed_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                    $('.breed_id').append('<option value="0">Other</option>');
                }
            });
        }else{
            $('.breed_id').empty();
        }

    });//select type

    $('select[name="breed_id"]').on('change', function() {
        var value = $(this).val();
        $('.other_breed').addClass('hidden');
        if(value == 0){
            $('.other_breed').removeClass('hidden');
        }
        
    });//select breed_id
});

function getPetOwners(){
    var filtered_vetUser = $('select[name="vet_user_id"]').val();
    var filtered_branch = $('select[name="branch_id"]').val();

    if(filtered_vetUser){
        $.ajax({
            url:      "<?php echo base_url('Users/get_petOwner_dropdown'); ?>",
            type:     'POST',
            data:     {'vet_user_id':filtered_vetUser,'branch_id':filtered_branch},
            dataType: "json",
            success:  function (data) {
                $('#cover-spin').hide();
                $('select[name="pet_owner_id"]').empty();
                $('select[name="pet_owner_id"]').append('<option value="">-- Select --</option>');
                $.each(data, function(key, value) {
                    $('select[name="pet_owner_id"]').append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }else{
        $('select[name="pet_owner_id"]').empty();
    }
    
}
</script>

</body>
</html>
