<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
	<style> 
	.dropdown-menu{min-width:300px;left: inherit !important;right: 0px;}
	.dropdown-menu li a{text-align: left !important;}
	</style>
	<div class="content-wrapper">
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Search panel</h3>
						</div>
						<div class="box-body">
							<form class="row" style="margin-bottom: 30px;" id="searchPanelFilterForm" method="POST" action="">
								<div class="col-sm-3">
									<div class="form-group">
										<label>Order Date</label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="filter_order_date" name="filter_order_date">
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<a href="javascript:void(0);" class="btn btn-success btn-sm" id="searchPanelFilterBtn">Filter</a>
									<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="searchPanelClearBtn">Clear</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Current Orders</h3>
						</div>
						<div class="box-body">
							<form class="row" style="margin-bottom: 30px;" id="currentOrdersFilterForm" method="POST" action="">
								<input type="hidden" name="dashboard_latest_list" value="yes">
							</form>
							<form id="currentOrdersForm" name="currentOrdersForm" method="POST" action="">
								<table id="currentOrders" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Order Number</th>
											<th>Order Date</th>
											<th>Order Type</th>
											<th>PO Name</th>
											<th>Pet Name</th>
											<th>Breed</th>
											<th>Batch Number</th>
											<th>Practice/lab Name</th>
											<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
											<th>Status</th>
											<th>Status Date</th>
											<th>Notes</th>
											<th><?php echo $this->lang->line("action");?></th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Order Number</th>
											<th>Order Date</th>
											<th>Order Type</th>
											<th>PO Name</th>
											<th>Pet Name</th>
											<th>Breed</th>
											<th>Batch Number</th>
											<th>Practice/lab Name</th>
											<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
											<th>Status</th>
											<th>Status Date</th>
											<th>Notes</th>
											<th><?php echo $this->lang->line("action");?></th>
										</tr>
									</tfoot>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Completed Orders</h3>
						</div>
						<div class="box-body">
							<form class="row" style="margin-bottom: 30px;" id="confirmedOrdersFilterForm" method="POST" action="">
								<input type="hidden" name="dashboard_confirmed_list" value="yes">
							</form>
							<form id="confirmedOrdersForm" name="confirmedOrdersForm" method="POST" action="">
								<table id="confirmedOrders" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Order Number</th>
											<th>Order Date</th>
											<th>Order Type</th>
											<th>PO Name</th>
											<th>Pet Name</th>
											<th>Breed</th>
											<th>Batch Number</th>
											<th>Practice/lab Name</th>
											<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
											<th>Status</th>
											<th>Status Date</th>
											<th>Notes</th>
											<th><?php echo $this->lang->line("action");?></th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Order Number</th>
											<th>Order Date</th>
											<th>Order Type</th>
											<th>PO Name</th>
											<th>Pet Name</th>
											<th>Breed</th>
											<th>Batch Number</th>
											<th>Practice/lab Name</th>
											<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
											<th>Status</th>
											<th>Status Date</th>
											<th>Notes</th>
											<th><?php echo $this->lang->line("action");?></th>
										</tr>
									</tfoot>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php $this->load->view("footer"); ?>
</div>
<?php $this->load->view("script"); ?>
<div class="modal fade" id="OrderSummary">
	<div class="modal-dialog" style="width:65%">
		<div class="modal-content">
			<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $this->lang->line("Order_Summary");?></h4>
			</div><!-- /.modal-header -->
			<?php echo form_open('', array('name' => 'repeatOrderForm', 'id' => 'repeatOrderForm')); ?>
				<div class="modal-body">
					<span id="message" class="text-danger"></span>
					<div class="repeatOrderDetails"></div>
				</div><!-- /.modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
				</div><!-- /.modal-footer -->
			<?php echo form_close(); ?>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal-->
<!--Repeat order modal-->
<div class="modal fade" id="Audittrail">
	<div class="modal-dialog" style="width:65%">
		<div class="modal-content">
			<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $this->lang->line("order_history");?> <b><span class="historyID"></span></b></h4>
			</div>
			<div class="modal-body">
				<span id="message" class="text-danger"></span>
				<div class="orderHistory"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	var user_role = "<?php echo $userData['role']; ?>";
	var user_type = "<?php echo $this->session->userdata('user_type'); ?>";
	var target = [0, 3, 6];
	var currentOrderDataTable = $('#currentOrders').DataTable({
		"processing": true,
		"serverSide": true,
		"pageLength": 5,
		"order": [
			[1, 'desc']
		],
		"columnDefs": [
			{
				orderable: false,
				targets: target
			}
		],
		//fnRowCallback: clickableRow,
		"fixedColumns": true,
		"language": {
			"infoFiltered": ""
		},
        "ajax": {
            "url": "<?php echo base_url('orders/getTableData'); ?>",
            "type": "POST",
            "async" : false,
            "data": {
                formData: function() {
                    return $('#currentOrdersFilterForm').serialize();
                }                
            }
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
					var repeatOrder = '';
					if (row.is_repeat_order == 1) {
						repeatOrder = ' <b>(R)</b>';
					}else {
						repeatOrder = ' <b>(I)</b>';
					}
					return is_order+repeatOrder;
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
				"data": "breed_id"
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
					if (row.is_confirmed == '1' && row.send_Exact == '0') {
						if (row.order_type_id == '2' && row.is_authorised == 1) {
						is_status = "Authorised";
						}else{
						is_status = "Confirmed";
						}
						is_btn = "btn-success";
					}
					if (row.is_confirmed == '1' && row.send_Exact == '1') {
						if (row.order_type_id == '2' && row.is_authorised == 1) {
						is_status = "Authorised";
						}else{
						is_status = "Netherlands Confirmed";
						}
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
					if (row.is_confirmed=='4') {
						if (row.order_type_id == '2' && row.is_authorised == 2) {
						is_status = "Reported";
						}else{
						is_status = "Shipped";
						}
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
				"data": "updated_at"
			},
			{
				"data": "id",
				render: function(data, type, row, meta) {
					if(row.comment==''||row.comment==null||row.comment==undefined){
						var comment =  '<a class="btn btn-sm btn-outline-light ordercommentadd" data-order_id="' + data + '" data-toggle="modal" data-target="#ordercommentadd" title="Order Comment"><i class="fa fa-sticky-note" style="font-size:initial;color:gray"></i></a>';
						return comment;
					}else{
						var comment =  '<a class="btn btn-sm btn-outline-light ordercommentadd" data-order_id="' + data + '" data-toggle="modal" data-target="#ordercommentadd" title="Order Comment"><i class="fa fa-sticky-note" style="font-size:initial;color:red;"></i></a>';
						return comment;
					}
				}
			},
			{
				"data": "id",
				render: function(data, type, row, meta) {
					var lab_order_number = '';
					if (row.plc_selection == 1) {
						lab_order_number = row.order_number;
					}else if (row.plc_selection == 2) {
						lab_order_number = row.reference_number;
					}
					var disable_class = '';
					var envelope_icon = '';
					var email_download = '';
					var email_resend = '';
					var email_customer = '';
					var resend_email_customer = '';
					var preview_NL_email = '';
					var confirm_order = '';
					var hold_order  = '';
					var unhold_order  = '';
					var cancel_order = '';
					var repeat_order = '';
					var CEP_after_screening = '';
					var edit_order = '';
					var email_upload = '';
					var Add_batch_number = '';
					var Add_Lab_Number = '';
					var order_summary = '';
					var order_history = '';
					var track_order = '';
					var authorised_confirmed = '';
					var treatment_options = '';
					var preview_result = '';
					var raptor_result = '';
					var result_recommendation = '';
					var download_Result_List = '';
					var authorised_order = '';

					if (user_role == 1 || user_role == 11) {
						email_customer = '<a class="btn btn-sm btn-outline-light customerMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#customerMailModal" title="Communicate Order Issue to Customer"><i class="fa fa-envelope-o" style="font-size:initial;"></i>Communicate Order Issue to Customer</a>';
						
						cancel_order = '<a class="btn btn-sm btn-outline-light orderCancelMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#orderCancelMailModal" title="Cancel Order"><i class="fa fa-times" style="font-size:initial;"></i>Cancel Order</a>';

						if (row.order_type_id != '2') {
							if (row.is_mail_sent == '1') {
								envelope_icon = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/send_mail/'); ?>' + data + '/'+row.is_confirmed+'" title="Mail" disabled="disabled"><i class="fa fa-envelope-open" style="font-size:initial;"></i>Sent Mail</a>';
							} else {
								envelope_icon = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/send_mail/'); ?>' + data + '/'+row.is_confirmed+'" title="Mail"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Send Mail</a>';
								disable_class = '';
							}

							resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-send Order Confirmation to customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_customer_mail/'); ?>' + data + '" title="Re-send Order Confirmation to customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-send Order Confirmation to customer</a>';

							Add_batch_number = '<a class="btn btn-sm btn-outline-light addBatchNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addBatchNumberModal" title="Add Batch Number"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Batch Number</a>';

							preview_NL_email = '<a class="btn btn-sm btn-outline-light previewNLModal" data-order_id="' + data + '" data-toggle="modal" data-target="#previewNLModal" title="Preview NL Email"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Preview NL Email</a>';
						}
						
						if (row.is_confirmed == '0' && row.is_invoiced == '0') {
							confirm_order = '<a class="btn btn-sm btn-outline-light confirmOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#confirmOrderModal" title="Confirm Order"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Confirm Order</a>';
						}
						
						if (row.serum_type == 2 && row.order_type_id == '2' && row.cep_id > 0 && row.is_authorised == '0' && row.is_confirmed == '1' && row.is_invoiced == '0') {
							authorised_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/authorisedOrder/'); ?>' + data + '" title="Authorised Order"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Authorised Order</a>';
						}

						if (row.is_confirmed == '1' && row.is_invoiced == '0' && row.order_type_id != '2') {
							email_resend = '<a onclick="return confirm(\'are sure you want Re-Send to Holland?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_mail/'); ?>' + data + '" title="Re-Send to Holland"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-Send to Holland</a>';
						}
					}
					if (user_role != 10  && row.is_invoiced == '0' && (row.is_confirmed == '0' || row.is_confirmed == '1' || row.is_confirmed == '2')) {
						if (row.is_confirmed == '2') {
							unhold_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/UnHold/'); ?>' + data + '" title="UnHold Order"><i class="fa fa-stop" style="font-size:initial;"></i>UnHold Order</a>';
						}else{
							hold_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/Hold/'); ?>' + data + '" title="Hold Order"><i class="fa fa-stop" style="font-size:initial;"></i>Hold Order</a>';
						}
					}
					
					if (row.order_type_id != '2') {
						if ((row.is_confirmed == '0' && row.is_invoiced == '0') || user_role == 1 || user_role == 11) {
							edit_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/addEdit/'); ?>' + data + '" title="Edit Order Form"><i class="fa fa-pencil" style="font-size:initial;"></i>Edit Order Form</a>';
						}
					}else{
						if ((row.is_confirmed == '0' && row.is_invoiced == '0') || user_role == 1 || user_role == 11) {
							edit_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/addEdit/'); ?>' + data + '" title="Edit Requisition Form"><i class="fa fa-pencil" style="font-size:initial;"></i>Edit Requisition Form</a>';
						}
					}

					if (row.email_upload != null) {
						email_upload = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url() . EMAIL_UPLOAD_PATH; ?>/' + row.email_upload + '" download title="View Order Email"><i class="fa fa-download" style="font-size:initial;"></i>View Order Email</a>';
					}

					if (user_role != 10) {
						order_summary = '<a class="btn btn-sm btn-outline-light repeatOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#OrderSummary" title="Order Summary"><i class="fa fa-reorder" style="font-size:initial;"></i>Order Summary</a>';

						var horderNumber = '';
						if (row.plc_selection == 1) {
							horderNumber = row.order_number;
						}else if (row.plc_selection == 2) {
							horderNumber = row.reference_number;
						}
						order_history = '<a class="btn btn-sm btn-outline-light orderHistoryModal" data-order_id="' + data + '" data-order_number="' + horderNumber + '" data-toggle="modal" data-target="#Audittrail" title="Order History"><i class="fa fa-history" style="font-size:initial;"></i>Order History</a>';

						track_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/track_order/'); ?>' + data + '" title="Track Order" style="display:none;"><i class="fa fa-map-marker" style="font-size:initial;"></i>Track Order</a>';
					}

					if (user_role == 5 && user_type == 3) {
						email_customer = '<a class="btn btn-sm btn-outline-light customerMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#customerMailModal" title="Communicate Order Issue to Customer"><i class="fa fa-envelope-o" style="font-size:initial;"></i>Communicate Order Issue to Customer</a>';
						preview_NL_email = '<a class="btn btn-sm btn-outline-light previewNLModal" data-order_id="' + data + '" data-toggle="modal" data-target="#previewNLModal" title="Preview NL Email"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Preview NL Email</a>';
						resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-send Order Confirmation to customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_customer_mail/'); ?>' + data + '" title="Re-send Order Confirmation to customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-send Order Confirmation to customer</a>';
					}

					if (row.order_type_id != '2') {
						repeat_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('repeatOrder/addEdit/'); ?>' + data + '" title="Repeat Order"><i class="fa fa-repeat" style="font-size:initial;"></i>Repeat Order</a>';
					}

					if (row.order_type_id == '2') {
						/* if (row.product_code_selection == 5 && row.is_cep_after_screening == 0 && row.is_order_completed == 0) {
							CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('repeatOrder/addEdit/'); ?>' + data + '" title="Complete Environmental Panel after screening"><i class="fa fa-repeat" style="font-size:initial;"></i>CEP after screening</a>';
						} */
						if ((user_role == 1 || user_role == 11) && row.serum_type == 2 && row.is_expand == 1 && row.is_order_completed == 1) {
							if((data > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '57293') || (data == '56766')){
								if(data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904'){
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOldOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
								}else{
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
								}
							}else{
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOldOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
							}
						}

						if ((user_role == 1 || user_role == 11) && row.serum_type == 1 && row.is_expand == 1 && row.cep_id == 0 && row.is_order_completed == 1) {
							CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOrder/expandPAX/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
						}

						if ((user_role == 1 || user_role == 11 || user_role == 10 ) && row.serum_type == '2'){
							Add_Lab_Number = '<a class="btn btn-sm btn-outline-light addLabNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addLabNumberModal" title="Add Lab number/Scan Barcode"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Lab number/Scan Barcode</a>';
						}

						if ((user_role == 1 || user_role == 11 || user_role == 10 ) && row.serum_type == '1' && row.is_confirmed == '1'){
							Add_Lab_Number = '<a class="btn btn-sm btn-outline-light addLabNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addLabNumberModal" title="Add Lab number/Scan Barcode"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Lab number/Scan Barcode</a>';
						}

						if (row.serum_type == '1') {
							if (row.is_confirmed == '1' && row.is_raptor_result == 0 && (user_role == 1 || user_role == 11) && row.lab_order_number != null) {
								raptor_result = '<a class="btn btn-sm btn-outline-light getRaptorResultModal" data-order_id="' + data + '" data-toggle="modal" data-target="#getRaptorResultModal" title="Get Raptor Result"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Get Raptor Result</a>';
							}

							if (row.is_raptor_result == 1) {
								if(user_role == 5 && (user_type == '1' || user_type == '2' || user_type == '3') && row.is_order_completed == 1){
									if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
									result_recommendation = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/interpretation/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i> View results, IM recommendation & send out</a>';
								}

							if (user_role == 1 || user_role == 11) {
									resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-Send Results to Customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/sendPaxResultNotification/'); ?>' + data + '" title="Re-Send Results to Customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-Send Results to Customer</a>';
									result_recommendation = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/interpretation/'); ?>' + data + '" title="Treatment Options"><i class="fa fa-reorder" style="font-size:initial;"></i> View results, IM recommendation & send out</a>';
									if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}
						}

						if (row.serum_type == '2') {
							if (user_role == 1 || user_role == 11) {
								if (row.is_authorised == 1) {
									authorised_confirmed = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/authorisedConfirmed/'); ?>' + data + '" title="Authorised Confirmed"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Authorised Confirmed</a>';
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										}else{
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										}
									}else{
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}
								}
							}
							if (row.is_authorised == 1) {
								if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}
									}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}

							if (row.is_authorised == 2) {
								if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
									if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}else{
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}
								}else{
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
								}
								if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}
									}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}
						}
					}

					/* <li><a class="btn btn-sm btn-outline-light delOrder" href="javascript:void(0);" data-href="<?php echo base_url('orders/delete/'); ?>' + data + '" data-order_number="' + lab_order_number + '" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i>Delete</a></li> */

					let action_dropdown='<div class="btn-group">'+'<button type="button" class="btn btn-secondary action-dropdown dropdown-toggle" data-id="menu'+data+'" data-bs-toggle="dropdown" aria-expanded="true">Action <i class="fa fa-caret-down text-dark" ></i></button>'+'<ul class="dropdown-menu " role="menu" id="menu'+data+'" style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: -100px; will-change: transform;">'+'<li>' + repeat_order + '</li><li>' + CEP_after_screening + '</li><li>' + raptor_result + '</li><li>' + download_Result_List + '</li><li>' + result_recommendation + '</li><li>' + treatment_options + '</li><li>' + order_summary + '</li><li>' + order_history + '</li><li>' + confirm_order + '</li><li>' + authorised_order + '</li><li>' + preview_result + '</li><li>' + authorised_confirmed + '</li><li>' + email_customer + '</li><li>' + preview_NL_email + '</li><li>' + email_resend + '</li><li>' + resend_email_customer + '</li><li>' + track_order + '</li><li>' + Add_batch_number + '</li><li>' + Add_Lab_Number + '</li><li>' + email_upload + '</li><li>' + edit_order + ' </li><li>' + hold_order + '</li><li>' + unhold_order + '</li><li>' + cancel_order + '</li>'+'</ul>'+'</div>';
					return '<div class="btn-group" > ' +action_dropdown+ ' </div>';
				}
			}
		]
	});

	$('#filter_order_date').daterangepicker({
         locale: { format: 'DD/MM/YYYY' }
    }).val('');

    $('#searchPanelFilterBtn').on('click', function(){
        var date_range = $('#filter_order_date').val();
        $.ajax({
            url:"<?php echo base_url('orders/dashboardSeachPanelData'); ?>",
            type:     'POST',
            data:     {'date_range':date_range},
            success:function(data)
            {
              if(data=='success'){
                location.href = "<?php echo base_url(); ?>orders/list";
              }
              
            }
        });
    });

	$(document).on('click', '.action-dropdown',function actionDropdown(){
		var id = $(this).data('id');
		var allAction=$('.action-dropdown');
		allAction.each(function() {
			let ids = $(this).data("id");
			if(id==ids){
				if($('#'+id).hasClass('show')){
					$('#'+id).removeClass('show')
					$(this).removeClass('btn-success');
					$(this).addClass('btn-secondary');
					$(this).closest('tr').removeClass("text-bold");
				}else{
					$(this).closest('tr').addClass("text-bold");
					$(this).addClass('btn-success');
					$(this).removeClass('btn-secondary');
					$('#'+id).addClass('show')
				}
			}else{
				$('#'+ids).removeClass('show')
				$(this).removeClass('btn-success');
				$(this).addClass('btn-secondary');
				$(this).closest('tr').removeClass("text-bold");
			}
		});
	});

	$(document).on('click', '.orderHistoryModal', function() {
		var order_id = $(this).attr('data-order_id');
		var order_number = $(this).attr('data-order_number');
		$('#order_id_modal').val(order_id);
		$('.historyID').text(order_number);
		if (order_id) {
			$.ajax({
				url: "<?php echo base_url('Orders/orderHistoryDetails'); ?>",
				data: {
					'order_id': order_id
				},
				method: "POST",
				success: function(data) {
					if (data != '') {
						$('.orderHistory').html(data);
					} else {
						$('.orderHistory').html('Something went wrong!');
					}
				}, //success
			}); //ajax
		}
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

$(document).ready(function(){
	var user_role = "<?php echo $userData['role']; ?>";
	var user_type = "<?php echo $this->session->userdata('user_type'); ?>";
	var target = [0, 3, 6];
	/* Confirmed order list */
	var confirmedtarget = [0, 3, 6];
    var confirmedOrderDataTable = $('#confirmedOrders').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [
			[1, 'desc']
		],
		"pageLength": 5,
		"columnDefs": [
			{
				orderable: false,
				targets: confirmedtarget
			}
		],
		//fnRowCallback: clickableRow,
		"fixedColumns": true,
		"language": {
			"infoFiltered": ""
		},
        "ajax": {
            "url": "<?php echo base_url('orders/getTableData'); ?>",
            "type": "POST",
            "async" : false,
            "data": {
                formData: function() {
                    return $('#confirmedOrdersFilterForm').serialize();
                }            
            }
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
					var repeatOrder = '';
					if (row.is_repeat_order == 1) {
						repeatOrder = ' <b>(R)</b>';
					}else {
						repeatOrder = ' <b>(I)</b>';
					}
					return is_order+repeatOrder;
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
				"data": "breed_id"
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
					if (row.is_confirmed == '1' && row.send_Exact == '0') {
						if (row.order_type_id == '2' && row.is_authorised == 1) {
						is_status = "Authorised";
						}else{
						is_status = "Confirmed";
						}
						is_btn = "btn-success";
					}
					if (row.is_confirmed == '1' && row.send_Exact == '1') {
						if (row.order_type_id == '2' && row.is_authorised == 1) {
						is_status = "Authorised";
						}else{
						is_status = "Netherlands Confirmed";
						}
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
					if (row.is_confirmed=='4') {
						if (row.order_type_id == '2' && row.is_authorised == 2) {
						is_status = "Reported";
						}else{
						is_status = "Shipped";
						}
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
				"data": "updated_at"
			},
			{
				"data": "id",
				render: function(data, type, row, meta) {
					if(row.comment==''||row.comment==null||row.comment==undefined){
						var comment =  '<a class="btn btn-sm btn-outline-light ordercommentadd" data-order_id="' + data + '" data-toggle="modal" data-target="#ordercommentadd" title="Order Comment"><i class="fa fa-sticky-note" style="font-size:initial;color:gray"></i></a>';
						return comment;
					}else{
						var comment =  '<a class="btn btn-sm btn-outline-light ordercommentadd" data-order_id="' + data + '" data-toggle="modal" data-target="#ordercommentadd" title="Order Comment"><i class="fa fa-sticky-note" style="font-size:initial;color:red;"></i></a>';
						return comment;
					}
				}
			},
			{
				"data": "id",
				render: function(data, type, row, meta) {
					var lab_order_number = '';
					if (row.plc_selection == 1) {
						lab_order_number = row.order_number;
					}else if (row.plc_selection == 2) {
						lab_order_number = row.reference_number;
					}
					var disable_class = '';
					var envelope_icon = '';
					var email_download = '';
					var email_resend = '';
					var email_customer = '';
					var resend_email_customer = '';
					var preview_NL_email = '';
					var confirm_order = '';
					var hold_order  = '';
					var unhold_order  = '';
					var cancel_order = '';
					var repeat_order = '';
					var CEP_after_screening = '';
					var edit_order = '';
					var email_upload = '';
					var Add_batch_number = '';
					var Add_Lab_Number = '';
					var order_summary = '';
					var order_history = '';
					var track_order = '';
					var authorised_confirmed = '';
					var treatment_options = '';
					var preview_result = '';
					var raptor_result = '';
					var result_recommendation = '';
					var download_Result_List = '';
					var authorised_order = '';

					if (user_role == 1 || user_role == 11) {
						email_customer = '<a class="btn btn-sm btn-outline-light customerMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#customerMailModal" title="Communicate Order Issue to Customer"><i class="fa fa-envelope-o" style="font-size:initial;"></i>Communicate Order Issue to Customer</a>';
						
						cancel_order = '<a class="btn btn-sm btn-outline-light orderCancelMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#orderCancelMailModal" title="Cancel Order"><i class="fa fa-times" style="font-size:initial;"></i>Cancel Order</a>';

						if (row.order_type_id != '2') {
							if (row.is_mail_sent == '1') {
								envelope_icon = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/send_mail/'); ?>' + data + '/'+row.is_confirmed+'" title="Mail" disabled="disabled"><i class="fa fa-envelope-open" style="font-size:initial;"></i>Sent Mail</a>';
							} else {
								envelope_icon = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/send_mail/'); ?>' + data + '/'+row.is_confirmed+'" title="Mail"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Send Mail</a>';
								disable_class = '';
							}

							resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-send Order Confirmation to customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_customer_mail/'); ?>' + data + '" title="Re-send Order Confirmation to customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-send Order Confirmation to customer</a>';

							Add_batch_number = '<a class="btn btn-sm btn-outline-light addBatchNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addBatchNumberModal" title="Add Batch Number"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Batch Number</a>';

							preview_NL_email = '<a class="btn btn-sm btn-outline-light previewNLModal" data-order_id="' + data + '" data-toggle="modal" data-target="#previewNLModal" title="Preview NL Email"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Preview NL Email</a>';
						}
						
						if (row.is_confirmed == '0' && row.is_invoiced == '0') {
							confirm_order = '<a class="btn btn-sm btn-outline-light confirmOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#confirmOrderModal" title="Confirm Order"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Confirm Order</a>';
						}
						
						if (row.serum_type == 2 && row.order_type_id == '2' && row.cep_id > 0 && row.is_authorised == '0' && row.is_confirmed == '1' && row.is_invoiced == '0') {
							authorised_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/authorisedOrder/'); ?>' + data + '" title="Authorised Order"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Authorised Order</a>';
						}

						if (row.is_confirmed == '1' && row.is_invoiced == '0' && row.order_type_id != '2') {
							email_resend = '<a onclick="return confirm(\'are sure you want Re-Send to Holland?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_mail/'); ?>' + data + '" title="Re-Send to Holland"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-Send to Holland</a>';
						}
					}
					if (user_role != 10  && row.is_invoiced == '0' && (row.is_confirmed == '0' || row.is_confirmed == '1' || row.is_confirmed == '2')) {
						if (row.is_confirmed == '2') {
							unhold_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/UnHold/'); ?>' + data + '" title="UnHold Order"><i class="fa fa-stop" style="font-size:initial;"></i>UnHold Order</a>';
						}else{
							hold_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/Hold/'); ?>' + data + '" title="Hold Order"><i class="fa fa-stop" style="font-size:initial;"></i>Hold Order</a>';
						}
					}
					
					if (row.order_type_id != '2') {
						if ((row.is_confirmed == '0' && row.is_invoiced == '0') || user_role == 1 || user_role == 11) {
							edit_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/addEdit/'); ?>' + data + '" title="Edit Order Form"><i class="fa fa-pencil" style="font-size:initial;"></i>Edit Order Form</a>';
						}
					}else{
						if ((row.is_confirmed == '0' && row.is_invoiced == '0') || user_role == 1 || user_role == 11) {
							edit_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/addEdit/'); ?>' + data + '" title="Edit Requisition Form"><i class="fa fa-pencil" style="font-size:initial;"></i>Edit Requisition Form</a>';
						}
					}

					if (row.email_upload != null) {
						email_upload = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url() . EMAIL_UPLOAD_PATH; ?>/' + row.email_upload + '" download title="View Order Email"><i class="fa fa-download" style="font-size:initial;"></i>View Order Email</a>';
					}

					if (user_role != 10) {
						order_summary = '<a class="btn btn-sm btn-outline-light repeatOrderModal" data-order_id="' + data + '" data-toggle="modal" data-target="#OrderSummary" title="Order Summary"><i class="fa fa-reorder" style="font-size:initial;"></i>Order Summary</a>';

						var horderNumber = '';
						if (row.plc_selection == 1) {
							horderNumber = row.order_number;
						}else if (row.plc_selection == 2) {
							horderNumber = row.reference_number;
						}
						order_history = '<a class="btn btn-sm btn-outline-light orderHistoryModal" data-order_id="' + data + '" data-order_number="' + horderNumber + '" data-toggle="modal" data-target="#Audittrail" title="Order History"><i class="fa fa-history" style="font-size:initial;"></i>Order History</a>';

						track_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/track_order/'); ?>' + data + '" title="Track Order" style="display:none;"><i class="fa fa-map-marker" style="font-size:initial;"></i>Track Order</a>';
					}

					if (user_role == 5 && user_type == 3) {
						email_customer = '<a class="btn btn-sm btn-outline-light customerMailModal" data-order_id="' + data + '" data-toggle="modal" data-target="#customerMailModal" title="Communicate Order Issue to Customer"><i class="fa fa-envelope-o" style="font-size:initial;"></i>Communicate Order Issue to Customer</a>';
						preview_NL_email = '<a class="btn btn-sm btn-outline-light previewNLModal" data-order_id="' + data + '" data-toggle="modal" data-target="#previewNLModal" title="Preview NL Email"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Preview NL Email</a>';
						resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-send Order Confirmation to customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/resend_customer_mail/'); ?>' + data + '" title="Re-send Order Confirmation to customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-send Order Confirmation to customer</a>';
					}

					if (row.order_type_id != '2') {
						repeat_order = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('repeatOrder/addEdit/'); ?>' + data + '" title="Repeat Order"><i class="fa fa-repeat" style="font-size:initial;"></i>Repeat Order</a>';
					}

					if (row.order_type_id == '2') {
						/* if (row.product_code_selection == 5 && row.is_cep_after_screening == 0 && row.is_order_completed == 0) {
							CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('repeatOrder/addEdit/'); ?>' + data + '" title="Complete Environmental Panel after screening"><i class="fa fa-repeat" style="font-size:initial;"></i>CEP after screening</a>';
						} */
						if ((user_role == 1 || user_role == 11) && row.serum_type == 2 && row.is_expand == 1 && row.is_order_completed == 1) {
							if((data > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '57293') || (data == '56766')){
								if(data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904'){
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOldOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
								}else{
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
								}
							}else{
								CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOldOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
							}
						}

						if ((user_role == 1 || user_role == 11) && row.serum_type == 1 && row.is_expand == 1 && row.cep_id == 0 && row.is_order_completed == 1) {
							CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOrder/expandPAX/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
						}

						if ((user_role == 1 || user_role == 11 || user_role == 10 ) && row.serum_type == '2'){
							Add_Lab_Number = '<a class="btn btn-sm btn-outline-light addLabNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addLabNumberModal" title="Add Lab number/Scan Barcode"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Lab number/Scan Barcode</a>';
						}

						if ((user_role == 1 || user_role == 11 || user_role == 10 ) && row.serum_type == '1' && row.is_confirmed == '1'){
							Add_Lab_Number = '<a class="btn btn-sm btn-outline-light addLabNumberModal" data-order_id="' + data + '" data-toggle="modal" data-target="#addLabNumberModal" title="Add Lab number/Scan Barcode"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Add Lab number/Scan Barcode</a>';
						}

						if (row.serum_type == '1') {
							if (row.is_confirmed == '1' && row.is_raptor_result == 0 && (user_role == 1 || user_role == 11) && row.lab_order_number != null) {
								raptor_result = '<a class="btn btn-sm btn-outline-light getRaptorResultModal" data-order_id="' + data + '" data-toggle="modal" data-target="#getRaptorResultModal" title="Get Raptor Result"><i class="fa fa-plus-circle" style="font-size:initial;"></i>Get Raptor Result</a>';
							}

							if (row.is_raptor_result == 1) {
								if(user_role == 5 && (user_type == '1' || user_type == '2' || user_type == '3') && row.is_order_completed == 1){
									if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
									result_recommendation = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/interpretation/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i> View results, IM recommendation & send out</a>';
								}

							if (user_role == 1 || user_role == 11) {
									resend_email_customer = '<a onclick="return confirm(\'are sure you want Re-Send Results to Customer?\')" class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/sendPaxResultNotification/'); ?>' + data + '" title="Re-Send Results to Customer"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Re-Send Results to Customer</a>';
									result_recommendation = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/interpretation/'); ?>' + data + '" title="Treatment Options"><i class="fa fa-reorder" style="font-size:initial;"></i> View results, IM recommendation & send out</a>';
									if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}
						}

						if (row.serum_type == '2') {
							if (user_role == 1 || user_role == 11) {
								if (row.is_authorised == 1) {
									authorised_confirmed = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/authorisedConfirmed/'); ?>' + data + '" title="Authorised Confirmed"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Authorised Confirmed</a>';
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										}else{
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										}
									}else{
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}
								}
							}
							if (row.is_authorised == 1) {
								if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}
									}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}

							if (row.is_authorised == 2) {
								if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
									if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}else{
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
									}
								}else{
									treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment_old/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
								}
								if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
									if((data > '57338' && row.cep_id == 0) || (row.cep_id > '57338') || (data == '57166') || (data == '57156') || (data == '57152') || (data == '56592') || (data == '56898') || (data == '56766') || (data == '57293') || (data == '57136') || (row.cep_id == '57166') || (row.cep_id == '57156') || (row.cep_id == '57152') || (row.cep_id == '56592') || (row.cep_id == '56898') || (row.cep_id == '56766') || (row.cep_id == '57293')){
										if((data == '57459' || data == '57438' || data == '57434' || data == '57428' || data == '57904') || (row.cep_id == '57459' || row.cep_id == '57438' || row.cep_id == '57434' || row.cep_id == '57428' || row.cep_id == '57904')){
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}
									}else{
										download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getOLDSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
									}
								}
							}
						}
					}

					/* <li><a class="btn btn-sm btn-outline-light delOrder" href="javascript:void(0);" data-href="<?php echo base_url('orders/delete/'); ?>' + data + '" data-order_number="' + lab_order_number + '" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i>Delete</a></li> */

					let action_dropdown='<div class="btn-group">'+'<button type="button" class="btn btn-secondary action-dropdown2 dropdown-toggle2" data-id="menu2'+data+'" data-bs-toggle="dropdown" aria-expanded="true">Action <i class="fa fa-caret-down text-dark" ></i></button>'+'<ul class="dropdown-menu " role="menu" id="menu2'+data+'" style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: -100px; will-change: transform;">'+'<li>' + repeat_order + '</li><li>' + CEP_after_screening + '</li><li>' + raptor_result + '</li><li>' + download_Result_List + '</li><li>' + result_recommendation + '</li><li>' + treatment_options + '</li><li>' + order_summary + '</li><li>' + order_history + '</li><li>' + confirm_order + '</li><li>' + authorised_order + '</li><li>' + preview_result + '</li><li>' + authorised_confirmed + '</li><li>' + email_customer + '</li><li>' + preview_NL_email + '</li><li>' + email_resend + '</li><li>' + resend_email_customer + '</li><li>' + track_order + '</li><li>' + Add_batch_number + '</li><li>' + Add_Lab_Number + '</li><li>' + email_upload + '</li><li>' + edit_order + ' </li><li>' + hold_order + '</li><li>' + unhold_order + '</li><li>' + cancel_order + '</li>'+'</ul>'+'</div>';
					return '<div class="btn-group" > ' +action_dropdown+ ' </div>';
				}
			}
		]
    });

    $('#filter_order_date').daterangepicker({
         locale: { format: 'DD/MM/YYYY' }
    }).val('');

    $('#searchPanelFilterBtn').on('click', function(){
        var date_range = $('#filter_order_date').val();
        $.ajax({
            url:"<?php echo base_url('orders/dashboardSeachPanelData'); ?>",
            type:     'POST',
            data:     {'date_range':date_range},
            success:function(data)
            {
              if(data=='success'){
                location.href = "<?php echo base_url(); ?>orders/list";
              }
              
            }
        });
    });
	
	$(document).on('click', '.action-dropdown2',function actionDropdown(){
		var id = $(this).data('id');
		var allAction=$('.action-dropdown2');
		allAction.each(function() {
			let ids = $(this).data("id");
			if(id==ids){
				if($('#'+id).hasClass('show')){
					$('#'+id).removeClass('show')
					$(this).removeClass('btn-success');
					$(this).addClass('btn-secondary');
					$(this).closest('tr').removeClass("text-bold");
				}else{
					$(this).closest('tr').addClass("text-bold");
					$(this).addClass('btn-success');
					$(this).removeClass('btn-secondary');
					$('#'+id).addClass('show')
				}
			}else{
				$('#'+ids).removeClass('show')
				$(this).removeClass('btn-success');
				$(this).addClass('btn-secondary');
				$(this).closest('tr').removeClass("text-bold");
			}
		});
	});
});

function clickableRow(rowElement, rowData) {
	<?php if ($userData['role'] == '10') { ?>
	return false;
	<?php } ?>
    var order_edit_link = '<?php echo base_url('orders/addEdit'); ?>';
    var order_id = rowData.id;
    var row_redirect = order_edit_link + '/' + order_id;
    $(rowElement).attr('data-redirect', row_redirect);
    $(rowElement).on('click', function(e){
        var currElement = $(this);
        window.location.href = currElement.attr('data-redirect');
    });
}
</script>
</body>
</html>