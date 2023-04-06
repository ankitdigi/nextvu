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
						Corporate
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Users Management</a></li>
						<li class="active">Corporate</li>
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
							<!-- form start -->
							<?php echo form_open('', array('name'=>'corporateForm', 'id'=>'corporateForm')); ?>
								<!-- Corporate form elements start-->
								<div class="box box-primary">
									<div class="box-header with-border"><h3 class="box-title"><?php echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3></div><!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="form-group">
													<label>Practices</label>
													<?php
													$options = array();
													$options[''] = "Nothing selected";
													if(!empty($practices)){
														foreach ($practices as $key => $value) {
															$options[$value['id']] = $value['name'];
														}
													}
													$attr = 'class="form-control selectpicker" data-live-search="true"';
													echo form_dropdown('practices[]', $options,  isset($data['practices']) ? json_decode($data['practices']) : '', $attr);
													?>
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
													<label>Address 1</label>
													<input type="text" class="form-control" name="address_1" placeholder="Address 1" value="<?php echo set_value('address_1',isset($data['address_1']) ? $data['address_1'] : '');?>" required="">
													<?php echo form_error('address_1', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label>Address 2</label>
													<input type="text" class="form-control" name="address_2" placeholder="Address 2" value="<?php echo set_value('address_2',isset($data['address_2']) ? $data['address_2'] : '');?>">
													<?php echo form_error('address_2', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label>Address 3</label>
													<input type="text" class="form-control" name="address_3" placeholder="Address 3" value="<?php echo set_value('address_3',isset($data['address_3']) ? $data['address_3'] : '');?>">
													<?php echo form_error('address_3', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label>Address 4</label>
													<input type="text" class="form-control" name="address_4" placeholder="Address 4" value="<?php echo set_value('address_4',isset($data['address_4']) ? $data['address_4'] : '');?>">
													<?php echo form_error('address_4', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label>Town/City</label>
													<input type="text" class="form-control" name="town_city" placeholder="Town/City" value="<?php echo set_value('town_city',isset($data['town_city']) ? $data['town_city'] : '');?>">
													<?php echo form_error('town_city', '<div class="error">', '</div>'); ?>
												</div>

												<div class="form-group">
													<label>Country</label>
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
													<label>Post Code</label>
													<input type="text" class="form-control" name="post_code" placeholder="Post Code" value="<?php echo set_value('post_code',isset($data['post_code']) ? $data['post_code'] : '');?>">
													<?php echo form_error('post_code', '<div class="error">', '</div>'); ?>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<!-- Corporate form elements end-->
									<?php if( $userData['role']==1 || $userData['role']==7 ){ ?>
									<div class="box-footer">
										<button type="submit" name="submit" class="btn btn-primary" value="1">Submit</button>
									</div>
									<?php } ?>
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
			$('#corporateForm').parsley();
			var count = ($('.multi-field').length)+1;
			var branch_ids = [];
			$('.multi-field-wrapper').each(function() {
				var $wrapper = $('.multi-fields', this);
				$(".add-field", $(this)).click(function(e) {
					$('.remove-field').removeClass('hidden');
					$('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input, textarea').val('').focus();
					count++;
				});

				$('.multi-field .remove-field', $wrapper).click(function() {
					branch_ids.push( $(this).parents('.multi-field').find('.branch_id').val() );
					$('input[name="deleted_branch_id"]').val(branch_ids);
					if ($('.multi-field', $wrapper).length > 1){
						$(this).parents('.multi-field').remove();
					}else{
						$('.remove-field').addClass('hidden');
					}
				});
			});
		});
		</script>
	</body>
</html>