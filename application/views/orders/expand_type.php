<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/radio_box.css"); ?>' />
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("expand_type");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
					<!--breadcrumb-->
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<!--breadcrumb-->
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
								</div><!-- /.box-header -->

								<!-- form start -->
								<?php echo form_open('', array('name'=>'orderType', 'id'=>'orderType')); ?>
									<!--Order Type-->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="middle">
													<label>
														<input type="radio" name="expand_type" value="1"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("nextlab_scr_env_panel_exp");?></span>
														</div>
													</label>
													<label>
														<input type="radio" name="expand_type" value="2"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("nextlab_scr_food_Panel_exp");?></span>
														</div>
													</label>
													<label>
														<input type="radio" name="expand_type" value="3"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("nextlab_scr_env_food_panel_exp");?></span>
														</div>
													</label>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<!--Order Type-->
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
									</div>
								<?php echo form_close(); ?>
							</div><!-- /.box -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>  
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
	</body>
</html>