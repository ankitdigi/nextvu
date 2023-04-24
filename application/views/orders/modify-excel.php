<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();

?>
<style>
	#canvasDiv {
		position: relative;
		border: 2px dashed grey;
		height: 300px;
		width: 746px
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo "Modify/Download Excel"; ?>
			<small><?php echo $this->lang->line("Control_Panel"); ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('dashboard'); ?>"><i
							class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
			<li><a href="#"> <?php echo $this->lang->line("Orders_Management"); ?></a></li>
			<li class="active"> <?php echo $this->lang->line("Orders"); ?></li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!--breadcrumb-->
		<?php $this->load->view("orders/breadcrumbs"); ?>
		<!--breadcrumb-->
		<!--alert msg-->
		<?php if (!empty($this->session->flashdata('success'))) { ?>
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line("alert"); ?></h4>
				<?php echo $this->session->flashdata('success'); ?>
			</div>
		<?php } ?>
		<?php if (!empty($this->session->flashdata('error'))) { ?>
			<div class="alert alert-warning alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert"); ?></h4>
				<?php echo $this->session->flashdata('error'); ?>
			</div>
		<?php } ?>
		<!--alert msg-->
		<div class="row">
			<!-- left column -->
			<div class="col-xs-12">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<!-- <h3 class="box-title">Details</h3>
						<p class="pull-right"> -->
						 &nbsp;&nbsp;&nbsp;&nbsp;
						<!-- </p> -->
					</div><!-- /.box-header -->

					<div class="box-body">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">

									<?= form_open('orders/modify-excel/' . $orderId . '', array('name' => 'modifyExcelForm', 'id' => 'modifyExcelForm')); ?>
									<input type="hidden" name="orderId"
										   value="<?= !empty($orderId) ? $orderId : ''; ?>">
									<table style="width: 80%;" border="1">
										<?php
										foreach ($data as $key => $val) {
											?>
											<tr>
												<td style="width: 25%">
													<?= !empty($val['column1']) ? $val['column1'] : '&nbsp;'; ?>
												</td>
												<td style="width: 25%">
													<?= !empty($val['column2']) ? $val['column2'] : '&nbsp;'; ?>
												</td>
												<td style="width: 25%">
													<?= !empty($val['column3']) ? $val['column3'] : '&nbsp;'; ?>
												</td>
												<td style="width: 25%">
													<input style="width: 100%; background-color: #e3e0e0;" type="text" name="column4[<?= !empty($val['id']) ? $val['id'] : ''; ?>]"
														   value="<?= !empty($val['column4']) ? $val['column4'] : ''; ?>">
												</td>
											</tr>
											<?php
										}
										?>
									</table>
									<br><br>
									<input class="btn btn-primary pull-right" type="submit" name="submit" value="Update and Download" />
									<?= form_close(); ?>
								</div>

							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.box-body -->
				</div><!-- /.box -->

			</div>
		</div>
	</section>
</div>
<?php $this->load->view("footer"); ?>
</div>
<?php $this->load->view("script"); ?>


</body>
</html>
