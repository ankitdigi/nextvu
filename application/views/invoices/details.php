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
						Invoice Details
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<!-- <li><a href="<?php //echo site_url('invoices/index'); ?>"><i class="fa fa-file-excel-o"></i> Invoices</a></li> -->
						<li><a href="#"> Orders Management</a></li>
						<li class="active">Invoice Details</li>
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
						<!-- left column -->
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">Invoice Details</h3>
								</div>
								<!-- /.box-header -->
								<?php echo form_open('',array('name'=>'invoiceDetailForm', 'id'=>'invoiceDetailForm')); ?>
									<div class="box-body">
										<table id="invoice_list" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th><input type="checkbox" name="select_all" value="1" id="select_all" class="checkbox_cls"></th>
													<th>ACCOUNT_REF</th>
													<th>ORDER_TYPE</th>
													<th>ORDER_NUMBER</th>
													<th>ORDER_DATE</th>
													<th>STOCK_CODE</th>
													<th>QTY_ORDER</th>
													<th>UNIT_PRICE</th>
													<th>TAX_CODE</th>
													<th>NAME</th>
													<th>ADDRESS_1</th>
													<th>ADDRESS_2</th>
													<th>ADDRESS_5</th>
													<th>CUST_EMAIL</th>
													<th>CUST_VAT_REG</th>
													<th>CUST_COUNTRY_CODE</th>
													<th>Comment_1</th>
													<th>Comment_2</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>&nbsp;</th>
													<th>ACCOUNT_REF</th>
													<th>ORDER_TYPE</th>
													<th>ORDER_NUMBER</th>
													<th>ORDER_DATE</th>
													<th>STOCK_CODE</th>
													<th>QTY_ORDER</th>
													<th>UNIT_PRICE</th>
													<th>TAX_CODE</th>
													<th>NAME</th>
													<th>ADDRESS_1</th>
													<th>ADDRESS_2</th>
													<th>ADDRESS_5</th>
													<th>CUST_EMAIL</th>
													<th>CUST_VAT_REG</th>
													<th>CUST_COUNTRY_CODE</th>
													<th>Comment_1</th>
													<th>Comment_2</th>
												</tr>
											</tfoot>
										</table>
									</div>
									<!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Send to Sage</button>
									</div>
								<?php echo form_close(); ?>
								<!-- <pre id="example-console-rows"></pre> -->
							</div>
							<!-- /.box -->
						</div>
						<!--/.col -->
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
		<script>
		function updateDataTableSelectAllCtrl(table){
			var $table             = table.table().node();
			var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
			var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
			var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

			// If none of the checkboxes are checked
			if($chkbox_checked.length === 0){
				chkbox_select_all.checked = false;
				if('indeterminate' in chkbox_select_all){
					chkbox_select_all.indeterminate = false;
				}
				// If all of the checkboxes are checked
			} else if ($chkbox_checked.length === $chkbox_all.length){
				chkbox_select_all.checked = true;
				if('indeterminate' in chkbox_select_all){
					chkbox_select_all.indeterminate = false;
				}
				// If some of the checkboxes are checked
			} else {
				chkbox_select_all.checked = true;
				if('indeterminate' in chkbox_select_all){
					chkbox_select_all.indeterminate = true;
				}
			}
		}

		$(function () {
			var rows_selected = [];
			var id = <?php echo $id ?>;
			$('.checkbox_cls').prop('checked', true);
			var dataTable = $('#invoice_list').DataTable({
				"processing": true,
				"serverSide": true,
				"scrollX": true,
				"bSearchable": true,
				"columnDefs" : [
					{
					targets: 0,
					sortable: false,
					orderable:false,
					}
				],
				'rowCallback': function(row, data, dataIndex){
					// Get row ID
					var rowId = data[0];
					// If row ID is in the list of selected row IDs
					if($.inArray(rowId, rows_selected) !== -1){
						$(row).find('input[type="checkbox"]').prop('checked', true);
						$(row).addClass('selected');
					}
				},
				initComplete: function (settings, json) {
					this.api().columns().header().each(function (th) {
						$(th).removeClass("sorting_asc");
						$(th).removeClass("sorting");
					})
				},
				"ajax": {
					"url": "<?php echo base_url('invoices/view_details'); ?>",
					"type": "POST",
					"data" : {"id": id}
				},
				"columns": [
					{ "data": "id", render : function ( data, type, row, meta ) {
					var checkbox = $("<input type='checkbox' name='id[]' value='" + row.id + "'/>",{});
					checkbox.attr("checked", "checked");
					return  checkbox.prop("outerHTML");
					} },
					{ "data": "account_ref" },
					{ "data": "order_type" },
					{ "data": "order_number" },
					{ "data": "order_date" },
					{ "data": "stock_code" },
					{ "data": "qty_order" },
					{ "data": "unit_price" },
					{ "data": "tax_code" },
					{ "data": "name" },
					{ "data": "address_1" },
					{ "data": "address_2" },
					{ "data": "address_5" },
					{ "data": "cust_email" },
					{ "data": "cust_vat_reg" },
					{ "data": "cust_country_code" },
					{ "data": "comment_1" },
					{ "data": "comment_2" }
				]
			});

			// Handle click on checkbox
			$('#invoice_list tbody').on('click', 'input[type="checkbox"]', function(e){
			   var $row = $(this).closest('tr');
		 
			   // Get row data
			   var data = dataTable.row($row).data();
		 
			   // Get row ID
			   var rowId = data[0];
		 
			   // Determine whether row ID is in the list of selected row IDs 
			   var index = $.inArray(rowId, rows_selected);
		 
			   // If checkbox is checked and row ID is not in list of selected row IDs
			   if(this.checked && index === -1){
				  rows_selected.push(rowId);
		 
			   // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
			   } else if (!this.checked && index !== -1){
				  rows_selected.splice(index, 1);
			   }
		 
			   if(this.checked){
				  $row.addClass('selected');
			   } else {
				  $row.removeClass('selected');
			   }
		 
			   // Update state of "Select all" control
			   updateDataTableSelectAllCtrl(dataTable);
		 
			   // Prevent click event from propagating to parent
			   e.stopPropagation();
			});

			// Handle click on table cells with checkboxes
			$('#invoice_list').on('click', 'tbody td, thead th:first-child', function(e){
			   $(this).parent().find('input[type="checkbox"]').trigger('click');
			});

			// Handle click on "Select all" control
			$('thead input[name="select_all"]', dataTable.table().container()).on('click', function(e){
			   if(this.checked){
				  $('#invoice_list tbody input[type="checkbox"]:not(:checked)').trigger('click');
			   } else {
				  $('#invoice_list tbody input[type="checkbox"]:checked').trigger('click');
			   }
		 
			   // Prevent click event from propagating to parent
			   e.stopPropagation();
			});

			dataTable.on('draw', function(){
			   // Update state of "Select all" control
			   updateDataTableSelectAllCtrl(dataTable);
			});

			// Handle form submission event 
			$('#invoiceDetailForm').on('submit', function(e){
			   var form = this;
		 
			   // Iterate over all selected checkboxes
			   $.each(rows_selected, function(index, rowId){
				  // Create a hidden element 
				  $(form).append(
					  $('<input>')
						 .attr('type', 'hidden')
						 .attr('name', 'id[]')
						 .val(rowId)
				  );
			   });
		 
			   // FOR DEMONSTRATION ONLY     
			   
			   // Output form data to a console     
			   //$('#example-console-rows').text($(form).serialize());
			   console.log("Form submission", $(form).serialize());
				
			   // Remove added elements
			   $('input[name="id\[\]"]', form).remove();
				
			   // Prevent actual form submission
			   e.preventDefault();
			   dataTable.ajax.reload();
			});
		});
		</script>
	</body>
</html>