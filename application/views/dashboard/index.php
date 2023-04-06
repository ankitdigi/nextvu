<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
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
		fnRowCallback: clickableRow,
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
			}
		]
	});

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
		fnRowCallback: clickableRow,
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