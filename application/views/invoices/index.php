<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<style>
			#invoiced{width:100%!important}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Invoices
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Orders Management</a></li>
						<li class="active">Invoices</li>
					</ol>
				</section>

				<!-- Main content -->
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
						<!--alert msg-->
					<?php } ?>
					<div class="row">
						<div class="col-xs-12">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#tab1" class="box-title">To Be Invoiced</a></li>
								<li><a data-toggle="tab" href="#tab2" class="box-title">Invoiced</a></li>
							</ul>
							<div class="tab-content">
								<div id="tab1" class="tab-pane fade in active">
									<div class="box">
										<div class="box-header">
											<h3 class="box-title">Invoice List</h3>
											<button type="button" class="btn btn-sm btn-primary" id="create_xml" title="Invoice All Selected Orders" style="margin-left:15px">Invoice All Selected Orders</button>
										</div>
										<div class="box-body">
											<table id="orders" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th><input type="checkbox" name="selectall" value="1" id="selectall" class="checkbox_cls"></th>
														<th>Order Number</th>
														<th>Order Date</th>
														<th>Order Type</th>
														<th>Pet Owners Name</th>
														<th>Pet Name</th>
														<th>Batch Number</th>
														<th>Practice/lab Name</th>
														<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>&nbsp;</th>
														<th>Order Number</th>
														<th>Order Date</th>
														<th>Order Type</th>
														<th>Pet Owners Name</th>
														<th>Pet Name</th>
														<th>Batch Number</th>
														<th>Practice/lab Name</th>
														<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
								<div id="tab2" class="tab-pane fade">
									<div class="box">
										<div class="box-header">
											<h3 class="box-title">Invoice List</h3>
										</div>
										<div class="box-body">
											<table id="invoiced" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Order Number</th>
														<th>Order Date</th>
														<th>Order Type</th>
														<th>Pet Owners Name</th>
														<th>Pet Name</th>
														<th>Batch Number</th>
														<th>Practice/lab Name</th>
														<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>Order Number</th>
														<th>Order Date</th>
														<th>Order Type</th>
														<th>Pet Owners Name</th>
														<th>Pet Name</th>
														<th>Batch Number</th>
														<th>Practice/lab Name</th>
														<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
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
		</div>
		<!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<div class="modal fade" id="OrderSummary">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Order Details</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'repeatOrderForm', 'id' => 'repeatOrderForm')); ?>
					<div class="modal-body">
						<span id="message" class="text-danger"></span>
						<div class="repeatOrderDetails"></div>
					</div><!-- /.modal-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<script>
		$(document).ready(function() {
			var target = [0, 3];
			var dataTable = $('#orders').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [
					[1, 'desc']
				],
				"columnDefs": [{
					orderable: false,
					targets: target
				}],
				'rowCallback': function(row, data, dataIndex) {
					if (data.is_confirmed == '1') {
						$(row).css('background-color', '#dbf1db');
					}
					if (data.is_confirmed == '2') {
						$(row).css('background-color', 'rgb(255 202 118)');
					}
					if (data.is_confirmed == '3') {
						$(row).css('background-color', 'rgb(255 135 120)');
					}
					if (data.is_confirmed == '4') {
						$(row).css('background-color', '#ffecb3');
					}
					if (data.is_confirmed == '5') {
						$(row).css('background-color', '#a9e0ff');
					}
					if (data.is_confirmed == '6') {
						$(row).css('background-color', '#ffdcd7');
					}
					if (data.is_confirmed == '7') {
						$(row).css('background-color', '#b8c6d6');
					}
					if (data.is_invoiced == 1) {
						$(row).css('background-color', '#bdd0e1');
					}
				},
				"fixedColumns": true,
				"language": {
					"infoFiltered": ""
				},
				"ajax": {
					"url": "<?php echo base_url('invoices/getToBeInvoicedData'); ?>",
					"type": "POST",
					"async": false,
					"data": {
						formData: function() {
							return $('#filterForm').serialize();
						},
					},
				},
				"columns": [
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_confirmed = '';
							/* if (row.is_confirmed == 1) {
								is_confirmed = 'checked';
							} */
							var checkbox = $("<input type='checkbox' name='check_list[]' value='" + row.id + "' " + is_confirmed + "/>", {

							});
							return checkbox.prop("outerHTML");
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_lab_order = '';
							if (row.plc_selection == 1) {
								is_order = row.order_number;
							}else if (row.plc_selection == 2) {
								is_order = row.reference_number;
							}
							return is_order
						}
					},
					{
						"data": "order_date"
					},
					{
						"data": "order_type"
					},
					{
						"data": "pet_owner_name"
					},
					{
						"data": "pet_name"
					},
					{
						"data": "batch_number"
					},
					{
						"data": "final_name"
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '£'+row.unit_price;
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_status = '';
							var is_btn = '';
							if (row.is_confirmed == '0') {
								is_status = "New Order";
								is_btn = "btn-light";
							} 
							if (row.is_confirmed == '1') {
								is_status = "Confirmed";
								is_btn = "btn-success";
							} 
							if (row.is_confirmed == '2') {
								is_status = "Hold";
								is_btn = "btn-warning";
							} 
							if (row.is_confirmed == '3') {
								is_status = "Cancel";
								is_btn = "btn-danger";
							}
							/* if (row.is_confirmed=='4' || (row.is_confirmed=='1' && row.batch_number!='')) { */
							if (row.is_confirmed=='4') {
								is_status = "Shipped";
								is_btn = "btn-warning";
							}
							if (row.is_confirmed == '5') {
								is_status = "In Process";
								is_btn = "btn-primary";
							}
							if (row.is_confirmed == '6') {
								is_status = "Error on creation";
								is_btn = "btn-danger";
							}
							if (row.is_confirmed == '7') {
								is_status = "Sent to Netherlands";
								is_btn = "btn-light";
							}
							if (row.is_invoiced == '1') {
								is_status = "Invoiced";
								is_btn = "btn-primary";
							}
							var is_status = $("<lable class='btn "+is_btn+"'>" + is_status + "</lable>", {

							});
							// checkbox.attr("checked", "checked");
							return is_status.prop("outerHTML");
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '<a class="btn btn-sm btn-outline-light repeatOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#OrderSummary" title="Order Details"><i class="fa fa-info-circle" style="font-size:initial;"></i></a>';
						}
					}
				]
			});

			$('body').on('change', '#selectall', function(e) {
				if ($(this).prop('checked')) {
					$('input[name="check_list[]"]').each(function() {
						$(this).prop('checked', true);
					});
				} else {
					$('input[name="check_list[]"]').each(function() {
						$(this).prop('checked', false);
					});
				}
			});

			$(document).on('click','#create_xml', function(){
				var invoiceIds = [];
				$('input[name="check_list[]"]').each(function() {
					if ($(this).is(":checked")) {
						invoiceIds.push($(this).val());
					}
				});
				if (invoiceIds.length === 0) {
					alert("Please select/checked invoice first.");
				}else{
					var href = "<?php echo base_url('invoices/generateMergeXml'); ?>";
					$.ajax({
						url: href,
						type: 'POST',
						data : {"invoice_ids": invoiceIds},
						success: function (msg) {
							if(msg == 'failed'){
								alert('Something went wrong!');
							}else if(msg == 'Sucess'){
								alert("XML generated sucessfully!");
								dataTable.ajax.reload();
							}else{
								alert('XML Generation is failed! \nOrder number '+msg+' have either of following issues so please resolve before generate! \n 1. Account Reference \n 2. Company Name \n 3. Invoice Amount (Inc Shipping/Ex VAT) \n 4. Nominal Code');
								dataTable.ajax.reload();
							}
						}
					});
				}
			});
		});
		</script>
		<script>
		$(document).ready(function(){
			var target = [0, 3];
			var dataTable1 = $('#invoiced').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [
					[1, 'desc']
				],
				"columnDefs": [{
					orderable: false,
					targets: target
				}],
				'rowCallback': function(row, data, dataIndex) {
					if (data.is_confirmed == 1) {
						$(row).css('background-color', '#dbf1db');
					}
					if (data.is_invoiced == 1) {
						$(row).css('background-color', '#bdd0e1');
					}
					if (data.is_confirmed=='4' || (data.is_confirmed=='1' && data.batch_number!='')) {
						$(row).css('background-color', '#ffecb3');
					}
				},
				"fixedColumns": true,
				"language": {
					"infoFiltered": ""
				},
				"ajax": {
					"url": "<?php echo base_url('invoices/getInvoicedData'); ?>",
					"type": "POST",
					"async": false,
					"data": {
						formData: function() {
							return $('#filterForm').serialize();
						},
					},
				},
				"columns": [
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_lab_order = '';
							if (row.plc_selection == 1) {
								is_order = row.order_number;
							}else if (row.plc_selection == 2) {
								is_order = row.reference_number;
							}
							return is_order
						}
					},
					{
						"data": "order_date"
					},
					{
						"data": "order_type"
					},
					{
						"data": "pet_owner_name"
					},
					{
						"data": "pet_name"
					},
					{
						"data": "batch_number"
					},
					{
						"data": "final_name"
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '£'+row.unit_price;
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_status = '';
							var is_btn = '';
							if (row.is_confirmed == '0') {
								is_status = "New Order";
								is_btn = "btn-light";
							} 
							if (row.is_confirmed == '1') {
								is_status = "Confirmed";
								is_btn = "btn-success";
							} 
							if (row.is_confirmed == '2') {
								is_status = "Hold";
								is_btn = "btn-warning";
							} 
							if (row.is_confirmed == '3') {
								is_status = "Cancel";
								is_btn = "btn-danger";
							} 
							if (row.is_confirmed=='4' || (row.is_confirmed=='1' && row.batch_number!='')) {
								is_status = "Shipped";
								is_btn = "btn-warning";
							}
							if (row.is_confirmed == '5') {
								is_status = "In Process";
								is_btn = "btn-primary";
							}
							if (row.is_confirmed == '6') {
								is_status = "Error on creation";
								is_btn = "btn-danger";
							}
							if (row.is_confirmed == '7') {
								is_status = "Sent to Netherlands";
								is_btn = "btn-light";
							}
							if (row.is_invoiced == '1') {
								is_status = "Invoiced";
								is_btn = "btn-primary";
							}
							var is_status = $("<lable class='btn "+is_btn+"'>" + is_status + "</lable>", {

							});
							return is_status.prop("outerHTML");
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '<a class="btn btn-sm btn-outline-light repeatOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#OrderSummary" title="Order Summary"><i class="fa fa-info-circle" style="font-size:initial;"></i></a>';
						}
					}
				]
			});

			$(document).on('click', '.repeatOrderModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_modal').val(order_id);
				if (order_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/repeatOrderDetails'); ?>",
						data: {
							'order_id': order_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.repeatOrderDetails').html(data);
							} else {
								$('.repeatOrderDetails').html('Something went wrong!');
							}
						}, //success
					}); //ajax
				}
			});
		});
		</script>
	</body>
</html>