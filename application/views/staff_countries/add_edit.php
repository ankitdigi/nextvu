<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link href="<?=base_url()?>assets/dist/css/editor.css" rel="stylesheet">
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						<?php echo $this->lang->line('Countries'); ?>
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active"><?php echo $this->lang->line('Countries'); ?></li>
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
								<?php echo form_open('', array('name'=>'staffCountryForm', 'id'=>'staffCountryForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label><?php echo $this->lang->line('country_name'); ?></label>
													<input type="text" class="form-control" name="name" placeholder="Enter <?php echo $this->lang->line('country_name'); ?>" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('country_code'); ?></label>
													<input type="text" class="form-control" name="code" placeholder="Enter <?php echo $this->lang->line('country_code'); ?>" value="<?php echo set_value('code',isset($data['code']) ? $data['code'] : '');?>" required="">
													<?php echo form_error('code', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('preferred_language'); ?></label>
													<select class="form-control prefer_language" name="prefer_language" id="prefer_language">
														<option value="english" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='english')) echo 'selected="selected"'; ?>>English</option>
														<option value="danish" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='danish')) echo 'selected="selected"'; ?>>Danish</option>
														<option value="french" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='french')) echo 'selected="selected"'; ?>>French</option>
														<option value="german" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='german')) echo 'selected="selected"'; ?>>German</option>
														<option value="italian" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='italian')) echo 'selected="selected"'; ?>>Italian</option>
														<option value="dutch" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='dutch')) echo 'selected="selected"'; ?>>Dutch</option>
														<option value="norwegian" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='norwegian')) echo 'selected="selected"'; ?>>Norwegian</option>
														<option value="spanish" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='spanish')) echo 'selected="selected"'; ?>>Spanish</option>
														<option value="swedish" <?php if(isset($id) && $id > 0 && ($data['prefer_language']=='swedish')) echo 'selected="selected"'; ?>>Swedish</option>
													</select>
													<?php echo form_error('prefer_language', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line('managed_by'); ?></label>
													<select class="form-control selectpicker" data-live-search="true" name="managed_by_id[]" id="managed_by_id" multiple="multiple">
														<?php
														echo '<option value="0">Select Managed By</option>';
														if(!empty($staff_members)){
															foreach($staff_members as $row){
																if(in_array($row['id'],explode(",",$data['managed_by_id']))){
																	echo '<option value="'. $row['id'] .'" selected="selected">'. $row['managed_by_name'] .'</option>';
																}else{
																	echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
																}
															}
														}
														?>
													</select>
													<?php echo form_error('managed_by_id', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label>Invoiced By</label>
													<select class="form-control" name="invoiced_by" id="invoiced_by" required="required">
														<?php
														echo '<option value="">Select Invoiced By</option>';
														if(!empty($staff_members)){
															foreach($staff_members as $row){
																if(in_array($row['id'],explode(",",$data['invoiced_by']))){
																	echo '<option value="'. $row['id'] .'" selected="selected">'. $row['managed_by_name'] .'</option>';
																}else{
																	echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
																}
															}
														}
														?>
													</select>
													<?php echo form_error('invoiced_by', '<div class="error">', '</div>'); ?>
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
		<script type="text/javascript" src="<?=base_url()?>assets/dist/js/editor.js"></script>
		<script>
		$(document).ready(function(){
			$('#staffCountryForm').parsley();
			$(".ckeditor").Editor();
			$(".ckeditor").Editor("setText",'<?php $data['serum_test_address'];?>');
		});
		$('#staffCountryForm').on('submit',function(){
			var editor_html = $(".ckeditor").Editor("getText");
			$(".ckeditor").val(editor_html);
		});
		</script>
	</body>
</html>