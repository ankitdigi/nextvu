<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("lims_users");?>
						<small><?php echo $this->lang->line("control_panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("home");?></a></li>
						<li class="active"><?php echo $this->lang->line("lims_users");?></li>
					</ol>
				</section>

				<section class="content">
					<?php if(!empty($this->session->flashdata('success'))){ ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i><?php echo $this->lang->line("alert");?></h4>
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

					<div class="row">
						<div class="col-xs-12">
							<div class="box box-primary">
								<div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line("edit");?></h3></div>
								<?php echo form_open('', array('name'=>'profileForm', 'id'=>'profileForm')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-6 col-md-6 col-lg-6">
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
											</div>
										</div>
									</div>
									<div class="box-footer">
										<button type="submit" name="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("submit");?></button>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			$('#profileForm').parsley();
		});
		</script>
	</body>
</html>
