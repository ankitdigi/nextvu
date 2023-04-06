<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Practice Details
						<small>Report panel</small>&nbsp;&nbsp;&nbsp;&nbsp;
						
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="javascript:void(0);"> Reports Management</a></li>
						<li class="active">Practices Report</li>
					</ol>
				</section>
				<section class="content">
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

					<?php $userData = logged_in_user_data(); ?>
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<div class="box-body">
									<!--filter form-->
									<form class="row" style="margin-bottom: 30px;" id="filterForm" method="POST" action="<?php echo base_url('Reports/exportPracticesDetailReport'); ?>">
										<div class="col-sm-3">
											<div class="form-group">
												<label>Date</label>
												<div class="input-group">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input type="text" class="form-control pull-right" id="filter_order_date" name="filter_order_date">
												</div>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="form-group">
												<label>Order Type</label>
												<select class="form-control form-control-sm select2" name="order_type[]" multiple id="order_type">
													<option value="">--Select--</option>
													<option value="1">Immunotherapy</option>
													<option value="2">Serum Testing</option>
													<option value="3" <?php if($this->user_role == 5){ echo 'style="display:none"'; } ?>>Skin Test</option>
												</select>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label>TM Users</label>
												<select class="form-control form-control-sm select2" name="select_tm_user[]" multiple id="select_tm_user">
													<option value="">--Select--</option>
													<?php 
													if(!empty($tm_users))
													{
														foreach($tm_users as $trow)
														{ ?>

															<option value="<?=$trow->id?>"><?=$trow->name?></option>
															<?php
														}
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label>Zones</label>
												<select class="form-control form-control-sm select2" name="select_zones[]" multiple id="select_zones">
													<option value="">--Select--</option>
													<?php 
													if(!empty($zones))
													{
														foreach($zones as $trow)
														{ ?>

															<option value="<?=$trow->id?>"><?=$trow->managed_by_name?></option>
															<?php
														}
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-sm-12">
											<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Filter</a>
											<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clearBtn">Clear</a>
											<button type="submit" class="btn btn-primary ad-click-event"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export All Practices With Detail</button>
										</div>
									</form>
									<table id="practice_users" class="table table-bordered table-striped">
										<thead>
											<tr>
												
												<th>Name</th>
												<th>Practice Code</th>
												<th>Postcode</th>
												<th>Buying Group</th>
												<th>Name of TM</th>
												
											</tr>
										</thead>
										<tfoot>
											<tr>
												
												<th>Name</th>
												<th>Practice Code</th>
												<th>Postcode</th>
												<th>Buying Group</th>
												<th>Name of TM</th>
												
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
		<?php $this->load->view("script"); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
		<script>

		$(document).ready(function(){
			$(".select2").select2();
			//Date range picker
			$('#filter_order_date').daterangepicker({
				locale: {
					format: 'DD/MM/YYYY'
				}
			}).val('');

			$('#shipping_date').datepicker({
				locale: {
					format: 'DD/MM/YYYY'
				}
			}).val('');
			var dataTable = $('#practice_users').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [{
					orderable: false,
					targets: [1,3]
				}],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('Reports/getPracticeDetailTableData'); ?>",
					"type": "POST",
					
					 "data": function ( d ) {
				        d.filter_order_date = $('#filter_order_date').val();
				        d.order_type = $('#order_type').val();
				        d.select_tm_user = $('#select_tm_user').val();
				        d.select_zones = $('#select_zones').val();
				    },
					/*"data":{data:'aad',filter_order_date:$('#filter_order_date').val(),order_type:$('#order_type').val(),select_tm_user:$('#select_tm_user').val(),select_zones:$('#select_zones').val()}*/
				},
				"columns": [
					
					{ "data": "name" },
					{ "data": "account_ref" },
					{ "data": "address_3" },
					{ "data": "buying_groups" },
					{ "data": "tm_user_id" }/*,
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '£'+row.total_spent;
						}
					}*/
				]
			});
			$('#filterBtn').on('click', function() {
				$('#sub_allergens_filter input').val('');
				dataTable.search('').draw();
				dataTable.ajax.reload();
			});
		});
		</script>
	</body>
</html>