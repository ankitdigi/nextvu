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
						Admin User
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li class="active">Admin User</li>
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
							<div class="box-header with-border"><h3 class="box-title">Edit</h3></div><!-- /.box-header -->

							<!-- form start -->
							<?php echo form_open('', array('name'=>'profileForm', 'id'=>'profileForm')); ?>

							<div class="box-body">
							<div class="row">
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="form-group">
										<label>Role</label>
										<select class="form-control" name="role" id="role">
											<option value="1" <?php if($data['role']==1){ echo 'selected="selected"'; } ?>>Super Admin</option>
											<option value="10" <?php if($data['role']==1 && $data['is_admin']==1){ echo 'selected="selected"'; } ?>>Admin</option>
										</select>
									</div>
									<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
									<?php echo form_error('name', '<div class="error">', '</div>'); ?>
									</div>

									<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control" name="email" placeholder="Enter Email" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>" required="">
									<?php echo form_error('email', '<div class="error">', '</div>'); ?>
									</div>

									<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php echo set_value('password');?>" <?php echo (isset($id) && $id>0) ? '' : 'required=""' ?>>
									<?php echo form_error('password', '<div class="error">', '</div>'); ?>
									</div>

									<div class="form-group">
									<label>Nextmune Country</label>
									<?php 
									$options = array();
									$options[''] = '-- Select --';
									if(!empty($staff_countries)){
										foreach ($staff_countries as $staff_country) {
											$staff_country_id = $staff_country['id'];
											$options[$staff_country_id] = $staff_country['name'];
										}
									}
									$attr = 'class="form-control" data-live-search="true" required=""';
									echo form_dropdown('country',$options,set_value('country',isset($data['country']) ? $data['country'] : ''),$attr); ?>
									<?php echo form_error('country', '<div class="error">', '</div>'); ?>
									</div>

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
			$('#profileForm').parsley();
		});
		</script>
	</body>
</html>
