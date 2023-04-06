<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/radio_box.css"); ?>' />
			<style>
			ol.progtrckr{margin:0;padding:10%}
			ol.progtrckr li{display:inline-block;text-align:center;line-height:3.5em}
			ol.progtrckr[data-progtrckr-steps="2"] li{width:49%}
			ol.progtrckr[data-progtrckr-steps="3"] li{width:33%}
			ol.progtrckr[data-progtrckr-steps="4"] li{width:24%}
			ol.progtrckr[data-progtrckr-steps="5"] li{width:19%}
			ol.progtrckr[data-progtrckr-steps="6"] li{width:16%}
			ol.progtrckr[data-progtrckr-steps="7"] li{width:14%}
			ol.progtrckr[data-progtrckr-steps="8"] li{width:12%}
			ol.progtrckr[data-progtrckr-steps="9"] li{width:11%}
			ol.progtrckr li.progtrckr-done{color:#000;border-bottom:4px solid #9acd32}
			ol.progtrckr li.progtrckr-todo{color:silver;border-bottom:4px solid silver}
			ol.progtrckr li:after{content:"\00a0\00a0"}
			ol.progtrckr li:before{position:relative;bottom:-2.5em;float:left;left:50%;line-height:1em}
			ol.progtrckr li.progtrckr-done:before{content:"\2713";color:#fff;background-color:#9acd32;height:2.2em;width:2.2em;line-height:2.2em;border:none;border-radius:2.2em}
			ol.progtrckr li.progtrckr-todo:before{content:"\039F";color:silver;background-color:#fff;font-size:2.2em;bottom:-1.2em}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line('track_order'); ?>
						<small>	<?php echo $this->lang->line('Control_Panel'); ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> 	<?php echo $this->lang->line('Orders_Management'); ?></a></li>
						<li class="active">	<?php echo $this->lang->line('Orders'); ?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><?php //echo (isset($id) && $id>0) ? 'Edit' : 'Add' ?>	<?php echo $this->lang->line('time_line'); ?></h3>
									<p class="pull-right">
										<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i>	<?php echo $this->lang->line('back'); ?></a>
									</p>
								</div><!-- /.box-header -->

								<!--Track Order-->
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<div class="middle">
												<?php 
												$order_placed = $in_progress = $shipped = $completed = '';
												if( $data['status'] =='1' ) {
													$order_placed = "progtrckr-done";
													$in_progress = $shipped = $completed = "progtrckr-todo";
												}elseif( $data['status'] =='2' ){
													$order_placed = $in_progress = "progtrckr-done";
													$shipped = $completed = "progtrckr-todo";
												}elseif( $data['status'] =='3' ){
													$order_placed = $in_progress = $shipped = "progtrckr-done";
													$completed = "progtrckr-todo";
												}elseif( $data['status'] =='4' ){
													$order_placed = $in_progress = $shipped = $completed = "progtrckr-done";
												}else{
													$order_placed = "progtrckr-done";
													$in_progress = $shipped = $completed = "progtrckr-todo";
												}
												?>   
												<ol class="progtrckr" data-progtrckr-steps="5">
													<li class="<?php echo $order_placed; ?>">
													<?php echo $this->lang->line('order_placed'); ?></li>
													<li class="<?php echo $in_progress; ?>">
													<?php echo $this->lang->line('in_progress'); ?></li>
													<li class="<?php echo $shipped; ?>">
													<?php echo $this->lang->line('shipped'); ?></li>
													<li class="<?php echo $completed; ?>"><?php echo $this->lang->line('completed'); ?></li>
												</ol>
											</div>
										</div><!-- /.col -->
									</div><!-- /.row -->
								</div><!-- /.box-body -->
							</div><!-- /.box -->
							<!--Track Order-->
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