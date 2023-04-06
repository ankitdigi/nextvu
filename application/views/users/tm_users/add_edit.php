<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
if($userData['role']==1){
	$pageName = $this->lang->line("territory_managers");
}elseif($userData['role']==11){
	$pageName = $this->lang->line("sales_users");
}else{
	$pageName = $this->lang->line("users");
}
?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?=$pageName;?>
					<small><?php echo $this->lang->line("control_panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i><?php echo $this->lang->line("home");?></a></li>
						<li><a href="#"><?php echo $this->lang->line("users_management");?></a></li>
						<li class="active"><?=$pageName;?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line("alert");?></h4>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
					<div class="alert alert-warning alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert");?></h4>
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
								<?php echo form_open('', array('name'=>'tmUsersForm', 'id'=>'tmUsersForm')); ?>
									<input type="hidden" id="selected_branches" value="<?php echo isset($data['branches']) ? implode(",",json_decode($data['branches'])):''; ?>">
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<input type="hidden" name="user_type" value="3">
												<div class="form-group tmpractices">
													<label><?php echo $this->lang->line("practices");?></label>
													<?php
													$options = array();
													$options['0'] = "Nothing selected";
													if(!empty($practices)){
														foreach ($practices as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker" data-live-search="true" multiple=""';
													echo form_dropdown('tmpractices[]', $options,  isset($data['practices']) ? json_decode($data['practices']) : '', $attr);
													?>
												</div>
												<div class="form-group corporates">
													<label><?php echo $this->lang->line("Corporates");?></label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($corporates)){
														foreach ($corporates as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker" data-live-search="true"';
													echo form_dropdown('corporates[]', $options,  isset($data['corporates']) ? json_decode($data['corporates']) : '', $attr);
													?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("name");?></label>
													<input type="text" class="form-control" name="name" placeholder="<?php echo $this->lang->line("enter_name");?>" value="<?php echo set_value('name',isset($data['name']) ? $data['name'] : '');?>" required="">
													<?php echo form_error('name', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("email");?></label>
													<input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line("enter_email");?>" value="<?php echo set_value('email',isset($data['email']) ? $data['email'] : '');?>" required="">
													<?php echo form_error('email', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("password");?></label>
													<input type="password" class="form-control" name="password" placeholder="<?php echo $this->lang->line("enter_password");?>" value="<?php echo set_value('password');?>" <?php echo (isset($id) && $id>0) ? '' : 'required=""' ?>>
													<?php echo form_error('password', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("country");?></label>
													<?php 
													$options = array();
													$options[''] = '-- Select --';
													if(!empty($countries)){
														foreach ($countries as $country) {
															$country_id = $country['id'];
															$options[$country_id] = $country['name'];
														}
													}
													$attr = 'class="form-control" data-live-search="true" required=""';
													echo form_dropdown('country',$options,set_value('country',isset($data['country']) ? $data['country'] : ''),$attr); ?>
													<?php echo form_error('country', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label><?php echo $this->lang->line("zones");?></label>
													<select class="form-control selectpicker" data-live-search="true" name="managed_by_id[]" id="managed_by_id" multiple="multiple" required="required">
														<?php
														echo '<option value="0">Select Zones</option>';
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
													<label><?php echo $this->lang->line('preferred_language'); ?></label>
													<select class="form-control preferred_language" name="preferred_language" id="preferred_language">
														<option value="english" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='english')) echo 'selected="selected"'; ?>>English</option>
														<option value="danish" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='danish')) echo 'selected="selected"'; ?>>Danish</option>
														<option value="french" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='french')) echo 'selected="selected"'; ?>>French</option>
														<option value="german" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='german')) echo 'selected="selected"'; ?>>German</option>
														<option value="italian" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='italian')) echo 'selected="selected"'; ?>>Italian</option>
														<option value="dutch" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='dutch')) echo 'selected="selected"'; ?>>Dutch</option>
														<option value="norwegian" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='norwegian')) echo 'selected="selected"'; ?>>Norwegian</option>
														<option value="spanish" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='spanish')) echo 'selected="selected"'; ?>>Spanish</option>
														<option value="swedish" <?php if(isset($id) && $id > 0 && ($data['preferred_language']=='swedish')) echo 'selected="selected"'; ?>>Swedish</option>
													</select>
													<?php echo form_error('preferred_language', '<div class="error">', '</div>'); ?>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" name="submit" class="btn btn-primary" value="1" <?php if(!isset($id)){ echo 'disabled="disabled"'; } ?>><?php echo $this->lang->line("submit");?></button>
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
			$('#tmUsersForm').parsley();
		});
		</script>
	</body>
</html>