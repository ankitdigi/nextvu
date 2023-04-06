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
						Shipping Price Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Shipping Price Management</li>
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
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Shipping Price List</h3>
									<p class="pull-right">
										<a href="<?php echo base_url('shipping/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="shipping_price" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Name</th>
												<th>Order Type</th>
												<th>Price(£)</th>
												<th>Price(€)</th>
												<th>Action</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Order Type</th>
												<th>Price(£)</th>
												<th>Price(€)</th>
												<th>Action</th>
											</tr>
										</tfoot>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
						</div>
						<!--/.col (left) -->
					</div>
					<!-- /.row -->
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var target = [ 0, 3 ];
			var dataTable = $('#shipping_price').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [
					{ orderable: false, targets: target }
				],
				"fixedColumns": true,
				"fnDrawCallback": function() {
					$(".due_date").datepicker({
						format: "dd/mm/yyyy",
						todayHighlight: true,
						autoclose: true,
					})
				},
				"ajax": {
					"url": "<?php echo base_url('shippingPrice/getTableData'); ?>",
					"type": "POST",
					"async" : false,
				},
				"columns": [  
					{ "data": "name" },
					{ "data": "parent_name" },
					{ "data": "uk_price" },
					{ "data": "roi_price" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('shipping/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a> </div>';
					} }
				]
			});
		});
		</script>
	</body>
</html>