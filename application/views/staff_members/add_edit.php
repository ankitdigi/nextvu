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
            Staff Members
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Settings</a></li>
            <li class="active">Staff Members</li>
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
                <?php echo form_open('', array('name'=>'staffMemberForm', 'id'=>'staffMemberForm')); ?>

                <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6">

                        <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" placeholder="Enter First Name" value="<?php echo set_value('first_name',isset($data['first_name']) ? $data['first_name'] : '');?>" required="">
                        <?php echo form_error('first_name', '<div class="error">', '</div>'); ?>
                        </div>
                        

                        <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" value="<?php echo set_value('last_name',isset($data['last_name']) ? $data['last_name'] : '');?>" required="">
                        <?php echo form_error('last_name', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>" required="">
                        <?php echo form_error('email', '<div class="error">', '</div>'); ?>
                        </div>
                        

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
			$('#staffMemberForm').parsley();
		});
		</script>
	</body>
</html>