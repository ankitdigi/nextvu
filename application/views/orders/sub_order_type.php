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
					<?php echo $this->lang->line('Order_Type'); ?>
						<small><?php echo $this->lang->line('Control_Panel'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"><?php echo $this->lang->line('Orders_Management'); ?> </a></li>
						<li class="active"><?php echo $this->lang->line('Orders'); ?></li>
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
								<!-- <h3 class="box-title"><?php //echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?></h3>
								<p class="pull-right"> -->
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line('back'); ?></a>
								<!-- </p> -->
							</div><!-- /.box-header -->

							
							<!-- form start -->
							<?php echo form_open('', array('name'=>'orderType', 'id'=>'orderType')); ?>
							<!--Order Type-->
							<div class="box-body">
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12">

								<div class="middle">
									<?php if($this->session->userdata('order_type') === '1'){ ?>
									<label>
									<input type="radio" name="sub_order_type" value="1" <?php if($id==""){echo 'checked=""';}elseif(isset($data['sub_order_type']) && $data['sub_order_type']=='1'){echo 'checked=""';} ?>/>
									<div class="front-end box">
										<span><?php echo $this->lang->line('artuvetrin_immunotherapy'); ?></span>
									</div>
									</label>

									<label>
									<input type="radio" name="sub_order_type" value="2" <?php echo (isset($data['sub_order_type']) && $data['sub_order_type']=='2') ? 'checked=""' : "" ;?>/>
									<div class="front-end box">
										<span><?php echo $this->lang->line('sublingual_immunotherapy_slit'); ?></span>
									</div>
									</label>
									<?php } ?>

									<?php if($this->session->userdata('order_type') === '2'){ ?>
									<label>
									<input type="radio" name="sub_order_type" value="3" <?php if($id==""){echo 'checked=""';}elseif(isset($data['sub_order_type']) && $data['sub_order_type']=='3'){echo 'checked=""';} ?>/>
									<div class="front-end box">
										<span><?php echo $this->lang->line('Serum_Request'); ?></span>
									</div>
									</label>
									<?php } ?>

									<?php if($this->session->userdata('order_type') === '3'){ ?>
									<label>
									<input type="radio" name="sub_order_type" value="4" <?php if($id==""){echo 'checked=""';}elseif(isset($data['sub_order_type']) && $data['sub_order_type']=='4'){echo 'checked=""';} ?>/>
									<div class="front-end box">
										<span><?php echo $this->lang->line('Artuvetrin_Skin_Test'); ?></span>
									</div>
									</label>
									<?php } ?>
									
								</div>

								</div><!-- /.col -->

							</div><!-- /.row -->
							</div><!-- /.box-body -->
							</div><!-- /.box -->
							<!--Order Type-->
							
							<div class="box-footer">
							<p class="pull-right">
								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('next'); ?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
							</p>
							</div>
							<?php echo form_close(); ?>
							
							

						
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