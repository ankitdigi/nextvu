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
						Product Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Product Management</li>
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
									<h3 class="box-title">List</h3>
									<p class="pull-right">
										<a href="<?php echo base_url('price_sub_categories/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="price_sub_categories" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Name</th>
												<th>Order Type</th>
												<th>UK Price</th>
												<th>IE Price</th>
												<th>DK Price</th>
												<th>FR Price</th>
												<th>DE Price</th>
												<th>IT Price</th>
												<th>NL Price</th>
												<th>NO Price</th>
												<th>ES Price</th>
												<th>SE Price</th>
												<th>Default Price</th>
												<th>Nominal Code</th>
												<th>Action</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Order Type</th>
												<th>UK Price</th>
												<th>IE Price</th>
												<th>DK Price</th>
												<th>FR Price</th>
												<th>DE Price</th>
												<th>IT Price</th>
												<th>NL Price</th>
												<th>NO Price</th>
												<th>ES Price</th>
												<th>SE Price</th>
												<th>Default Price</th>
												<th>Nominal Code</th>
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
			var target = [ 0, 2 ];  
			var dataTable = $('#price_sub_categories').DataTable({
				"processing": true,
				"serverSide": true,
				// "order": [[ 1, 'desc' ]],
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
					"url": "<?php echo base_url('priceCategories/sub_getTableData'); ?>",
					"type": "POST",
					"async" : false,
				},
				"columns": [  
					{ "data": "name" },
					{ "data": "parent_name" },
					{ "data": "uk_price" },
					{ "data": "roi_price" },
					{ "data": "dk_price" },
					{ "data": "fr_price" },
					{ "data": "de_price" },
					{ "data": "it_price" },
					{ "data": "nl_price" },
					{ "data": "no_price" },
					{ "data": "es_price" },
					{ "data": "se_price" },
					{ "data": "default_price" },
					{ "data": "nominal_code" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('price_sub_categories/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a> </div>';
					} }
				]
			});

			$(document).on('click','.delSubCategory', function(){
				if(confirm('Are you sure you want to delete this sub category?')){
					var href = $(this).data('href');
					$('#cover-spin').show();
					$.ajax({
						url: href,
						type: 'GET',
						success: function (data) {
							$('#cover-spin').hide();
							if(data == 'failed'){
								alert('Something went wrong!');
							}else{
								dataTable.ajax.reload();
							}
						}
					});
				}
			});

		});
		</script>
	</body>
</html>