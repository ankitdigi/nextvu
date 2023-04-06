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
						Zones Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Zones Management</li>
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
								<div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div>
								<?php echo form_open('', array('name'=>'staffMemberForm', 'id'=>'staffMemberForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label>Zone Name</label>
													<input type="text" class="form-control" name="managed_by_name" placeholder="Enter Zone Name" value="<?php echo set_value('managed_by_name',isset($data['managed_by_name']) ? $data['managed_by_name'] : '');?>" required="">
													<?php echo form_error('managed_by_name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Nextmune Email Address for Managed By</label>
													<input type="email" class="form-control required" name="managed_by_email" placeholder="Enter Zone Email" value="<?php echo set_value('managed_by_email',isset($data['managed_by_email']) ? $data['managed_by_email'] : '');?>" required="">
													<?php echo form_error('managed_by_email', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>From Email address for Managed By</label>
													<input type="email" class="form-control required" name="managed_by_from_email" placeholder="Enter Zone Email" value="<?php echo set_value('managed_by_from_email',isset($data['managed_by_from_email']) ? $data['managed_by_from_email'] : '');?>" required="">
													<?php echo form_error('managed_by_from_email', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Serum Test Address 1</label>
													<input type="text" class="form-control" name="serum_test_address" placeholder="Enter Serum Test Address 1" value="<?php echo set_value('serum_test_address',isset($data['serum_test_address']) ? $data['serum_test_address'] : '');?>" required="">
													<?php echo form_error('serum_test_address', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Serum Test Address 2</label>
													<input type="text" class="form-control" name="serum_test_address_2" placeholder="Enter Serum Test Address 2" value="<?php echo set_value('serum_test_address_2',isset($data['serum_test_address_2']) ? $data['serum_test_address_2'] : '');?>" required="">
													<?php echo form_error('serum_test_address_2', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Serum Test Phone</label>
													<input type="text" class="form-control" name="serum_test_phone" placeholder="Enter Zone Phone" value="<?php echo set_value('serum_test_phone',isset($data['serum_test_phone']) ? $data['serum_test_phone'] : '');?>" required="">
													<?php echo form_error('serum_test_phone', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Serum Test Email</label>
													<input type="email" class="form-control required" name="serum_test_email" placeholder="Enter Zone Email" value="<?php echo set_value('serum_test_email',isset($data['serum_test_email']) ? $data['serum_test_email'] : '');?>" required="">
													<?php echo form_error('serum_test_email', '<div class="error">', '</div>'); ?>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<div class="checkbox">
														<label><input type="checkbox" name="auto_send_serum_results" value="1" <?php if(isset($data['auto_send_serum_results']) && $data['auto_send_serum_results'] == 1){ echo 'checked="checked"'; } ?>> Auto send out Serum results</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</section>
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