<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<style> 
			.dropdown-menu{min-width:300px;left: inherit !important;right: 0px;}
			.dropdown-menu li a{text-align: left !important;}
			.merge_orders{display:none}
			#message_success{display:none}
			#message_error{display:none}
			#scanmessage_error{display:none}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Orders
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#">Orders Management</a></li>
						<li class="active">Orders</li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if (!empty($this->session->flashdata('success'))) { ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							<?php echo $this->session->flashdata('success');
								  $this->session->set_flashdata('success', '');	
							?>
							<p id="barcodemsg"></p>
						</div>
					<?php } ?>
					<?php if (!empty($this->session->flashdata('error'))) { ?>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-warning"></i> Alert!</h4>
							<?php echo $this->session->flashdata('error'); 
								  $this->session->set_flashdata('success', '');	
							?>
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
										<?php if($this->user_role == 1 || $this->user_role == 11){ ?>
										<a href="javascript:void(0);" class="btn btn-primary ad-click-event addNextlabOrder">Add</a>
										<?php }else{ ?>
										<a href="<?php echo base_url('orders/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
										<?php } ?>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<!--filter form-->
									<form class="row" style="margin-bottom: 30px;" id="filterForm" method="POST" action="">
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
										<div class="col-sm-2">
											<div class="form-group">
												<label>&nbsp;</label>
												<input type="checkbox" name="is_confirmed" class="custom-control-input" value="1" style="margin-top: 35px;"><span class="custom-control-label"> Unconfirmed Orders</span>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label>Order Type</label>
												<select class="form-control form-control-sm" name="order_type" id="order_type">
													<option value="">--Select--</option>
													<option value="1">Immunotherapy</option>
													<option value="2">Serum Testing</option>
													<option value="3" <?php if($this->user_role == 5){ echo 'style="display:none"'; } ?>>Skin Test</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label>Serum Test Type</label>
												<select class="form-control form-control-sm" name="serum_type" id="serum_type">
													<option value="">--Select--</option>
													<option value="1">PAX</option>
													<option value="2">NEXTLAB</option>
												</select>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label>Order Status</label>
												<select class="form-control form-control-sm" name="order_status" id="order_status">
													<option value="">--Select--</option>
													<option value="99">New Order</option>
													<option value="1">Confirmed</option>
													<option value="2">Hold</option>
													<option value="3">Cancel</option>
													<option value="4">Shipped</option>
													<option value="5">In Process</option>
													<option value="6">Error on creation</option>
													<option value="7">Sent to Netherlands</option>
													<option value="8">Invoiced</option>
												</select>
											</div>
										</div>
										<div class="col-sm-12">
											<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Filter</a>
											<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clearBtn">Clear</a>
										</div>
									</form>
									<!--filter form-->
									<form id="ordersForm" name="ordersForm" method="POST" action="">
										<?php if($this->user_role == 1 || $this->user_role == 11){ ?>
										<p class="pull-right"><button type="button" class="btn btn-primary getBarCodeOrder">Find Order using Barcode</button></p>
										<?php } ?>
										<input type="hidden" id="check_counter" value='0'>
										<input type="hidden" name="ltest" id="ltest">
										<input type="hidden" name="lNumber" id="lNumber">
										<table id="orders" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th><input type="checkbox" name="selectall" value="1" id="selectall" class="checkbox_cls"></th>
													<th>Order Number</th>
													<th>Order Date</th>
													<th>Order Type</th>
													<th>Owner Name</th>
													<th>Pet Name</th>
													<th>Breed</th>
													<th>Batch/Lab Number</th>
													<th>Practice/lab Name</th>
													<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
													<th>Status</th>
													<th>Status Date</th>
													<th>Notes</th>
													<th>Action</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>&nbsp;</th>
													<th>Order Number</th>
													<th>Order Date</th>
													<th>Order Type</th>
													<th>Owner Name</th>
													<th>Pet Name</th>
													<th>Breed</th>
													<th>Batch/Lab Number</th>
													<th>Practice/lab Name</th>
													<th style="width: 9%;">Invoice Amount (Inc Shipping/Ex VAT)</th>
													<th>Status</th>
													<th>Status Date</th>
													<th>Notes</th>
													<th>Action</th>
												</tr>
											</tfoot>
										</table>
										<!-- /.box-body -->
										<?php if ($userData['role'] == '1' || $userData['role'] == '11') { ?>
											<div class="box-footer">
												<p class="pull-right">
													<button type="submit" class="btn btn-primary">Confirm All Orders</button>
												</p>
												<p class="pull-left merge_orders">
													<button type="button" id="merge_repeat_orders" class="btn btn-primary">Merge Repeat Orders</button>
													<span id="existchecked" style="color: #000;font-weight: bold;"></span>
												</p>
											</div>
										<?php }else{ ?>
											<div class="box-footer">
												<p class="pull-left merge_orders">
													<button type="button" id="merge_repeat_orders" class="btn btn-primary">Merge Repeat Orders</button>
													<span id="existchecked" style="color: #000;font-weight: bold;"></span>
												</p>
											</div>
										<?php } ?>
									</form>
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
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<!--Repeat order modal-->
		<div class="modal fade" id="confirmOrderModal">
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
							<div class="confirmOrderDetails"></div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1">Confirm Order</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->

		<div class="modal fade" id="previewNLModal">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Preview NL Email</h4>
					</div>
					<div class="modal-body">
						<span id="message" class="text-danger"></span>
						<div class="previewNLOrderDetails" style="height:600px;overflow-x: scroll;"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="previewSerumResultModal">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Preview Serum Test Result</h4>
					</div>
					<div class="modal-body">
						<span id="message" class="text-danger"></span>
						<div class="previewSerumResult" style="height:600px;overflow-x: scroll;"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="OrderSummary">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Order Summary</h4>
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
		<!--Repeat order modal-->

		<!--Customer Mail modal-->
		<div class="modal fade" id="customerMailModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Email Order Issue to Customer</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'customerMailForm', 'id' => 'customerMailForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<input type="hidden" name="order_id_cust_modal" id="order_id_cust_modal" value="">
							<div class="form-group">
								<label>Write your email to the customer</label>
								<textarea class="form-control" name="customer_mail" rows="3" placeholder="Enter customer mail" required=""></textarea>
								<?php echo form_error('customer_mail', '<div class="error">', '</div>'); ?>
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1">Send Mail</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--Customer Mail modal-->

		<!--Order Commnet modal-->
		<div class="modal fade" id="ordercommentadd">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Comments</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'ordercommentaddForm', 'id' => 'ordercommentaddForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<input type="hidden" name="order_id_commnet_modal" id="order_id_commnet_modal" value="">
							<div class="form-group">
								<label>Customer Support comment</label>
								<textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Enter Customer Support comment" required=""></textarea>
								<?php echo form_error('comment', '<div class="error">', '</div>'); ?>
							</div>
							<?php if($userData['role'] == 1 || $userData['role'] == 11 || ($this->user_role == '5' && $this->session->userdata('user_type') == '3')){ ?>
							<div class="form-group">
								<label>Laboratory comment</label>
								<textarea class="form-control" name="internal_comment" id="internal_comment" rows="3" placeholder="Enter Customer Support comment" required=""></textarea>
								<?php echo form_error('internal_comment', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group lab_comment" style="display:none">
								<label>Practice/Lab Comment</label>
								<textarea class="form-control" name="practice_lab_comment" id="practice_lab_comment" rows="3" placeholder="Enter Order Comment"></textarea>
								<?php echo form_error('comment', '<div class="error">', '</div>'); ?>
							</div>
							<?php } ?>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--Customer Mail modal-->

		<!--add Batch Number Modal -->
		<div class="modal fade" id="addBatchNumberModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Add Batch Number</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'addBatchNumberForm', 'id' => 'addBatchNumberForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<input type="hidden" name="order_id_batch_modal" id="order_id_batch_modal" value="">
							<div class="form-group">
								<label>Batch Number</label>
								<input type="text" class="form-control" name="batch_number" id="batch_number" placeholder="Enter Batch Number" required="">
								<?php echo form_error('batch_number', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label>Shipping date</label>
								<input type="text" class="form-control" name="shipping_date" id="shipping_date" placeholder="Enter Shipping date" required="">
								<?php echo form_error('shipping_date', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group">
								<label></label>
								<input type="checkbox" name="remove_shipping" id="remove_shipping"> Remove Batch Number & Shipping date?
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1">Save</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--add Batch Number Modal-->

		<!--add Lab Number Modal -->
		<div class="modal fade" id="addLabNumberModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Please enter the Lab Number below or Scan the Order Barcode</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'addLabNumberForm', 'id' => 'addLabNumberForm')); ?>
						<div class="modal-body">
							<span id="message_lab_number" class="text-danger"></span>
							<input type="hidden" name="order_id_lab_modal" id="order_id_lab_modal" value="">
							<div class="form-group">
								<label>Lab Number / Scan Barcode</label>
								<input type="text" class="form-control" name="lab_order_number" id="lab_order_number" required="required">
								<?php echo form_error('lab_order_number', '<div class="error">', '</div>'); ?>
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="lab_submit" class="btn btn-primary" value="1">Save</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--add Lab Number Modal-->

		<div class="modal fade" id="Audittrail">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Order History: <b><span class="historyID"></span></b></h4>
					</div>
					<div class="modal-body">
						<span id="message" class="text-danger"></span>
						<div class="orderHistory"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!--Order Cancel Mail modal-->
		<div class="modal fade" id="orderCancelMailModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Cancel Order</h4>
					</div>
					<?php echo form_open('', array('name' => 'orderCancelMailForm', 'id' => 'orderCancelMailForm')); ?>
						<div class="modal-body">
							<span id="message" class="text-danger"></span>
							<input type="hidden" name="order_id_cancel_modal" id="order_id_cancel_modal" value="">
							<div class="form-group">
								<label>Please enter the reason why you wish to cancel this order</label>
								<textarea class="form-control" name="cancel_comment" rows="3" placeholder="Enter Please enter the reason why you wish to cancel this order..." required=""></textarea>
								<?php echo form_error('cancel_comment', '<div class="error">', '</div>'); ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1">Cancel Order and Send Reason</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!--Order Cancel Mail modal-->

		<!-- get Raptor Result Modal -->
		<div class="modal fade" id="getRaptorResultModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Add Barcode/Sample ID</h4>
					</div>
					<?php echo form_open('', array('name' => 'addMeasurementIDForm', 'id' => 'addMeasurementIDForm')); ?>
						<div class="modal-body">
							<span id="Raptor_message" class="text-danger"></span>
							<input type="hidden" name="order_id_raptor_modal" id="order_id_raptor_modal" value="">
							<div class="form-group">
								<label>Barcode/Sample ID</label>
								<input type="text" class="form-control" name="measurement_id" id="measurement_id" placeholder="Add Barcode/Sample ID" required="">
								<?php echo form_error('measurement_id', '<div class="error">', '</div>'); ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1">Get</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!-- get Raptor Result Modal -->

		<!-- get Lab Number Modal -->
		<div class="modal fade" id="getBarCodeOrder">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Find Order using Barcode</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'getLabNumberForm', 'id' => 'getLabNumberForm')); ?>
						<div class="modal-body">
							<div id="message_error" class="alert alert-warning alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-warning"></i> Alert!</h4>
								Sorry no order found for this Barcode.
							</div>
							<div class="form-group">
								<label>Scan Barcode</label>
								<input type="text" class="form-control" name="lab_number" id="lab_number" required="required">
								<?php echo form_error('lab_number', '<div class="error">', '</div>'); ?>
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" class="btn btn-primary" value="1">Find</button>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--add Lab Number Modal-->

		<!-- get Lab Number Modal -->
		<div class="modal fade" id="scanBarCodeOrder">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Do you have a barcode to scan?</h4>
					</div><!-- /.modal-header -->
					<?php echo form_open('', array('name' => 'scanBarCodeForm', 'id' => 'scanBarCodeForm')); ?>
						<div class="modal-body">
							<div id="scanmessage_error" class="alert alert-warning alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-warning"></i> Alert!</h4>
								<span id="scan_error_msg"></span>
							</div>
							<div class="form-group">
								<label>Scan Barcode</label>
								<input type="text" class="form-control" name="scan_lab_number" id="scan_lab_number" required="required">
								<?php echo form_error('scan_lab_number', '<div class="error">', '</div>'); ?>
							</div>
						</div><!-- /.modal-body -->
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" class="btn btn-primary" value="1">Yes</button>
							<a href="<?php echo base_url('orders/add'); ?>" class="btn btn-danger">No</a>
						</div><!-- /.modal-footer -->
					<?php echo form_close(); ?>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--add Lab Number Modal-->

		<script>
		$(document).ready(function() {
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

			var user_role = "<?php echo $userData['role']; ?>";
			var user_type = "<?php echo $this->session->userdata('user_type'); ?>";
			var target = [0, 3, 6];
			var dataTable = $('#orders').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [
					[1, 'desc']
				],
				"columnDefs": [
					{
						orderable: false,
						targets: target
					}
				],
				'rowCallback': function(row, data, dataIndex) {
					if (data.is_confirmed == '1') {
						$(row).css('background-color', '#dbf1db');
					}
					if (data.is_confirmed == '2') {
						$(row).css('background-color', 'rgb(255 202 118)');
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
					if (data.is_authorised == 1) {
						$(row).css('background-color', 'rgba(25, 106, 164, 0.51)');
					}
					if (data.is_authorised == 2 && data.is_invoiced == 0) {
						$(row).css('background-color', 'rgb(231 179 23)');
					}
					if (data.is_confirmed == '3') {
						$(row).css('background-color', 'rgb(255 135 120)');
					}
				},
				"fixedColumns": true,
				"language": {
					"infoFiltered": ""
				},
				"ajax": {
					"url": "<?php echo base_url('orders/getTableData'); ?>",
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
							var orderNumber = '';
							if (row.plc_selection == 1) {
								orderNumber = row.order_number;
							}else if (row.plc_selection == 2) {
								orderNumber = row.reference_number;
							}

							var lNumbers = $('#lNumber').val();
							if(lNumbers != ""){
								var lNumberArr = lNumbers.split(',');
								var is_confirmed = '';
								if (row.is_confirmed == 1) {
									is_confirmed = 'checked';
								} else if( $.inArray(orderNumber, lNumberArr) !== -1 ) {
									is_confirmed = 'checked';
								}
								var checkbox = $("<input type='checkbox' name='check_list[]' data-Number='" + orderNumber + "' value='" + row.id + "' " + is_confirmed + "/>", { });
								$('#existchecked').text("You have selected orders : "+lNumbers);
							}else{
								var is_confirmed = '';
								if (row.is_confirmed == 1) {
									is_confirmed = 'checked';
								}
								var checkbox = $("<input type='checkbox' name='check_list[]' data-Number='" + orderNumber + "' value='" + row.id + "' " + is_confirmed + "/>", { });
							}
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
								if(row.reference_number != ''){
									is_order = row.reference_number;
								} else {
									is_order = row.order_number;
								}
							}
							var repeatOrder = '';
							if (row.is_repeat_order == 1) {
								if (row.order_type_id == 2) {
									repeatOrder = ' <b>(E)</b>';
								}else{
									repeatOrder = ' <b>(R)</b>';
								}
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
							return row.price_currency+row.unit_price;
						}
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							var is_status = '';
							var is_btn = '';
							if (row.is_confirmed == '0') {
								if(user_role == 11){
								is_status = "to be checked";
								}else{
								is_status = "New Order";
								}
								is_btn = "btn-light";
							} 
							if (row.is_confirmed == '1' && row.send_Exact == '0') {
								if (row.order_type_id == '2' && row.is_authorised == 1) {
								is_status = "Authorised";
								}else if (row.order_type_id == '2' && row.is_raptor_result == 0 && user_role != 1) {
								is_status = "Sample received";
								}else if (row.order_type_id == '2' && row.is_raptor_result == 1 && (user_role == 1 || user_role == 11)) {
								is_status = "Results Available";
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
							/* if (row.is_confirmed=='4' || (row.is_confirmed=='1' && row.batch_number!='')) { */
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
							// checkbox.attr("checked", "checked");
							return is_status.prop("outerHTML");
						}
					},
					{
						"data": "updated_at"
					},
					{
						"data": "id",
						render: function(data, type, row, meta) {
							if((row.comment==''||row.comment==null||row.comment==undefined) && (row.internal_comment==''||row.internal_comment==null||row.internal_comment==undefined)){
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
									CEP_after_screening = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('expandOrder/addEdit/'); ?>' + data + '" title="Expand Results"><i class="fa fa-repeat" style="font-size:initial;"></i>Expand Results</a>';
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
											treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										}
									}
									if (row.is_authorised == 1) {
										if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
											download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
										}
									}

									if (row.is_authorised == 2) {
										/* preview_result = '<a class="btn btn-sm btn-outline-light previewSerumResultModal" data-order_id="' + data + '" data-toggle="modal" data-target="#previewSerumResultModal" title="Preview Serum Result"><i class="fa fa-check-circle-o" style="font-size:initial;"></i>Preview Serum Result</a>'; */
										treatment_options = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/treatment/'); ?>' + data + '" title="View results, IM recommendation & send out"><i class="fa fa-reorder" style="font-size:initial;"></i>View results, IM recommendation & send out</a>';
										if(row.lab_id > 0 && (row.lab_id == '13401' || row.lab_id == '13789' || row.lab_id == '28995' || row.lab_id == '29164' || row.lab_id == '28994' || row.lab_id == '13788')){
											download_Result_List = '<a class="btn btn-sm btn-outline-light" href="<?php echo base_url('orders/getSerumResultdoc/'); ?>' + data + '" title="Download Result List"><i class="fa fa-envelope-square" style="font-size:initial;"></i>Download Result List</a>';
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
			
			$(document).on('click', '.getRaptorResultModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_raptor_modal').val(order_id);
			});

			$(document).on('submit', '#addMeasurementIDForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var measurement_id = $('#measurement_id').val();
				$.ajax({
					url: "<?php echo base_url('Orders/getRaptorResult'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						var rtrn = JSON.parse(data);
						if (rtrn.error) {
							$('#Raptor_message').text(rtrn.error);
						} else {
							$('#getRaptorResultModal').modal('hide');
							location.reload();
						}
					},
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

			$('#filterBtn').on('click', function() {
				$('#sub_allergens_filter input').val('');
				dataTable.search('').draw();
				dataTable.ajax.reload();
			});

			$('#clearBtn').on('click', function() {
				$('#filterForm')[0].reset();
				dataTable.ajax.reload();
			});

			$(document).on('click', '.customerMailModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_cust_modal').val(order_id);
			});

			$(document).on('submit', '#customerMailForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var customer_mail = $('#customer_mail').val();
				$.ajax({
					url: "<?php echo base_url('Orders/customer_mail'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						//console.log(data);
						$('#submit').attr('disabled', false);
						if (data == 'fail') {
							$('#message').text(data.error);
						} else {
							$('#customerMailModal').modal('hide');
							location.reload();
						}
					}, //success
				});
			});

			$(document).on('click', '.ordercommentadd', function() {
				var userTypes = "<?php echo $this->session->userdata('user_type'); ?>";
				var order_id = $(this).data('order_id');
				$('#order_id_commnet_modal').val(order_id);
				$.ajax({
					url: "<?php echo base_url('Orders/comment_get'); ?>",
					method: "POST",
					data: {
						id : order_id
					},
					success: function(datas) {
						let data = JSON.parse(datas)
						if (data.status == 'faill') {
							$('#message').text(data.error);
						} else {
							$('#comment').text(data.comment_order);
							$('#internal_comment').text(data.internal_comment);
							if(data.practice_lab_comment!=""){
								$(".lab_comment").show();
								$('#practice_lab_comment').text(data.practice_lab_comment);
							}else if(userTypes == 3){
								$(".lab_comment").show();
							}
						}
					}, //success
				});
			});

			$(document).on('submit', '#ordercommentaddForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				$.ajax({
					url: "<?php echo base_url('Orders/comment'); ?>",
					method: "POST",
					data: $(this).serialize(),
					dataType: "JSON",
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						if (data.status == 'faill') {
							$('#message').text(data.error);
						} else {
							$('#ordercommentadd').modal('hide');
							location.reload();
						}
					}, //success
				});
			});

			$(document).on('click', '.addBatchNumberModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_batch_modal').val(order_id);
			});

			$('#remove_shipping').change(function() {
				if($(this).is(":checked")) {
					$("#batch_number").attr("required",false);
					$("#shipping_date").attr("required",false);
				}else{
					$("#batch_number").attr("required",true);
					$("#shipping_date").attr("required",true);
				}
			});

			$(document).on('submit', '#addBatchNumberForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var batch_number = $('#batch_number').val();
				$.ajax({
					url: "<?php echo base_url('Orders/add_batch_number'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						if (data == 'fail') {
							$('#message').text(data.error);
						} else {
							$('#addBatchNumberModal').modal('hide');
							location.reload();
						}
					}, //success
				});
			});

			$(document).on('click', '.addLabNumberModal', function() {
				$('#lab_order_number').empty();
				var order_id = $(this).data('order_id');
				$('#order_id_lab_modal').val(order_id);
				if(order_id > 0){
					$.ajax({
						url: "<?php echo base_url('Orders/getlabNumber'); ?>",
						method: "POST",
						data: {'order_id':order_id},
						beforeSend: function() {
							$('#submit').attr('disabled', 'disabled');
						},
						success: function(data) {
							$('#submit').attr('disabled', false);
							$('#lab_order_number').val(data);
						},
					});
				}
			});

			$(document).on('shown.bs.modal', '#addLabNumberModal', function(){
				$('#lab_order_number').focus();
			});

			$(document).on('submit', '#addLabNumberForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var lab_order_number = $('#lab_order_number').val();
				$.ajax({
					url: "<?php echo base_url('Orders/add_lab_number'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						if (data == 'success'){
							$('#addLabNumberModal').modal('hide');
							location.reload();
						} else if (data == 'exist'){
							$('#lab_order_number').val('');
							$('#message_lab_number').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alert!</h4>Barcode '+lab_order_number+' already exists in the system. Please try again.</div>');
						} else if (data == 'error'){
							$('#lab_order_number').val('');
							$('#message_lab_number').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alert!</h4>Sorry! Bar code scanned have an error.</div>');
						}
					},
				});
			});

			$(document).on('click', '.getBarCodeOrder', function() {
				$('#lab_number').empty();
				$('#getBarCodeOrder').modal('show');
			});

			$(document).on('shown.bs.modal', '#getBarCodeOrder', function(){
				$('#lab_number').focus();
			});

			$(document).on('submit', '#getLabNumberForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var lab_number = $('#lab_number').val();
				$.ajax({
					url: "<?php echo base_url('Orders/getOrderSummery'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						if (data > 0) {
							$('#orders_filter input[type=search]').val(lab_number);
							dataTable.ajax.reload();
							$("a.repeatOrderModal[data-order_id="+data+"]").click();
							$('#getBarCodeOrder').modal('hide');
						} else {
							$('#message_error').show();
						}
					},
				});
			});

			$(document).on('click', '.delOrder', function() {
				var order_number = $(this).data('order_number');
				if (confirm('Are you sure you want to delete Order Number: ' + order_number + '?')) {
					var href = $(this).data('href');
					var role = 3;
					$('#cover-spin').show();
					$.ajax({
						url: href,
						type: 'GET',
						data: {
							"role": role
						},
						success: function(data) {
							$('#cover-spin').hide();
							if (data == 'failed') {
								alert('Something went wrong!');
							} else {
								dataTable.ajax.reload();
							}
						}
					});
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

			$(document).on('click', '.confirmOrderModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_modal').val(order_id);
				if (order_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/confirmOrderDetails'); ?>",
						data: {
							'order_id': order_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.confirmOrderDetails').html(data);
							} else {
								$('.confirmOrderDetails').html('Something went wrong!');
							}
						}, //success
					}); //ajax
				}
			});

			$(document).on('shown.bs.modal', '#confirmOrderModal', function(){
				$('#lab_orderNumber').focus();
			});

			$(document).on('click', '.previewNLModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_modal').val(order_id);
				if (order_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/previewNLOrderDetails'); ?>",
						data: {
							'order_id': order_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.previewNLOrderDetails').html(data);
							} else {
								$('.previewNLOrderDetails').html('Something went wrong!');
							}
						}, //success
					}); //ajax
				}
			});

			$(document).on('click', '.previewSerumResultModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_modal').val(order_id);
				if (order_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/previewSerumResult'); ?>",
						data: {
							'order_id': order_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.previewSerumResult').html(data);
							} else {
								$('.previewSerumResult').html('Something went wrong!');
							}
						}, //success
					}); //ajax
				}
			});

			$(document).on('click', '.previewRaptorResultModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_modal').val(order_id);
				if (order_id) {
					$.ajax({
						url: "<?php echo base_url('Orders/previewRaptorResult'); ?>",
						data: {
							'order_id': order_id
						},
						method: "POST",
						success: function(data) {
							if (data != '') {
								$('.previewSerumResult').html(data);
							} else {
								$('.previewSerumResult').html('Something went wrong!');
							}
						}, //success
					}); //ajax
				}
			});

			$(document).on('submit', '#repeatOrderForm', function(event) {
				event.preventDefault();
				var slctboxlength = $('select[name=mainList]').length;
				if(slctboxlength == 0){
					var order_id_modal = $('#order_id_modal').val();
					$.ajax({
						url: "<?php echo base_url('Orders/confirm_order'); ?>",
						method: "POST",
						data: $(this).serialize() + '&order_id=' + order_id_modal,
						beforeSend: function() {
							$('#submit').val('wait...');
							$('#submit').attr('disabled', 'disabled');
						},
						success: function(data) {
							$('#submit').attr('disabled', false);
							if (data == 'fail') {
								$('#message').text("Something went wrong!");
							} else {
								$('#repeatOrderModal').modal('hide');
								location.reload();
							}
						}, //success
					});
				}else{
					var slctlength = $('select[name=mainList]').find('option').length;
					if(slctboxlength == 1 && slctlength == 0){
						$('.vialsSelect option').prop('selected', true);
						var order_id_modal = $('#order_id_modal').val();
						$.ajax({
							url: "<?php echo base_url('Orders/confirm_order'); ?>",
							method: "POST",
							data: $(this).serialize() + '&order_id=' + order_id_modal,
							beforeSend: function() {
								$('#submit').val('wait...');
								$('#submit').attr('disabled', 'disabled');
							},
							success: function(data) {
								$('#submit').attr('disabled', false);
								if (data == 'fail') {
									$('#message').text("Something went wrong!");
								} else {
									$('#repeatOrderModal').modal('hide');
									location.reload();
								}
							}, //success
						});
					}else{
						alert("please select which Vial each Allergen belongs to?");
						return false;
					}
				}
			});

			$(document).on('click', '.orderCancelMailModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_cancel_modal').val(order_id);
			});

			$(document).on('submit', '#orderCancelMailForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var cancel_comment = $('#cancel_comment').val();
				$.ajax({
					url: "<?php echo base_url('Orders/orderCancelMail'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').val('wait...');
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						$('#submit').attr('disabled', false);
						if (data == 'fail') {
							$('#message').text(data.error);
						} else {
							$('#orderCancelMailModal').modal('hide');
							location.reload();
						}
					}, //success
				});
			});

			//confirm order start
			var check_counter = $('#check_counter').val();
			$('body').on('change', 'input[name="check_list[]"]', function(e) {
				if ($(this).is(":checked")) {
					var testid = $('#ltest').val();
					var tickid = $(this).val();
					if(testid != ""){
						$('#ltest').val(testid+','+tickid);
					}else{
						$('#ltest').val(tickid);
					}

					var testnbr = $('#lNumber').val();
					var ticknbr = $(this).data("number")
					if(testnbr != ""){
						$('#lNumber').val(testnbr+','+ticknbr);
						$('#existchecked').text("You have selected orders : "+testnbr+","+ticknbr+"");
					}else{
						$('#lNumber').val(ticknbr);
						$('#existchecked').text("You have selected orders : "+ticknbr+"");
					}
					check_counter++;
				} else {
					$(this).attr("checked",false);
					var ltest1 = $('#ltest').val();
					var unticknmbr1 = $(this).val();
					var ltestArr = ltest1.split(',');
					var ltestNew = [];
					ltestArr.forEach(function(itemd) {
						if(itemd != unticknmbr1){
							ltestNew.push(itemd);
						}
					});
					$('#ltest').val(ltestNew);

					var lntest = $('#lNumber').val();
					var unticknmbr = $(this).data("number");
					var lNumberdArr = lntest.split(',');
					var lNumberNew = [];
					lNumberdArr.forEach(function(item) {
						if(item != unticknmbr){
							lNumberNew.push(item);
						}
					});
					$('#lNumber').val(lNumberNew);
					$('#existchecked').text("You have selected orders : "+lNumberNew+"");
					if (check_counter != 0) {
						check_counter--;
					}
				}
				if(check_counter > 1){
					$(".merge_orders").show();
				}else{
					$(".merge_orders").hide();
				}
				$('#check_counter').val(check_counter);
			});

			var ltest = []; lNumber = [];
			$('body').on('change', '#selectall', function(e) {
				if ($(this).prop('checked')) {
					$('input[name="check_list[]"]').each(function() {
						$(this).prop('checked', true);
						ltest.push($(this).val());
						lNumber.push($(this).data("number"));
						check_counter++;
					});
				} else {
					$('input[name="check_list[]"]').each(function() {
						$(this).prop('checked', false);
						ltest.pop($(this).val());
						lNumber.pop($(this).data("number"));
						check_counter++;
						if (check_counter != 0) {
							check_counter--;
						}
					});
				}
				$('#check_counter').val(check_counter);
				$('#ltest').val(ltest);
				$('#lNumber').val(lNumber);
			});

			$('#ordersForm').submit(function(e) {
				e.preventDefault();
				var check_counter = $('#check_counter').val();
				var ltest = $('#ltest').val();
				if (check_counter == 0) {
					alert("Please select atleast one order!");
					return false;
				} else {
					$.ajax({
						"type": "POST",
						"url": "<?php echo base_url('orders/confirm_order'); ?>",
						"data": {
							"order_id": ltest
						},
						success: function(data) {
							if (data == 'success') {
								alert('Order has been confirmed successfully.');
							} else if (data == 'fail') {
								alert('Something went wrong! please try again.');

							}
						}
					});
				}
			});
			//confirm order end

			$(document).on('click', '#merge_repeat_orders', function() {
				var check_counter = $('#check_counter').val();
				var ltest = $('#ltest').val();
				if (check_counter < 1) {
					alert("Please select more then one order!");
					return false;
				} else {
					$.ajax({
						"type": "POST",
						"url": "<?php echo base_url('orders/mergeRepeatOrder'); ?>",
						"data": {
							"order_id": ltest
						},
						success: function(data) {
							if (data == 'fail') {
								alert('Your selected orders do not match. NextVu can only merge orders that match. Please try again');
							} else if (data == 'success') {
								ltest = ltest.replace(",", "01999960");
								window.location.href = '<?php echo base_url('repeatOrder/mergeaddEdit/'); ?>' + ltest + '';
							}  
						}
					});
				}
			});
		});

		function movetoVials(id){
			var slctlength = $('select[name=mainList]').find('option:selected').length;
			var length = $('#vials_'+id+' > option').length;
			var totllength = parseFloat(slctlength)+parseFloat(length);
			if(slctlength <= 8 && slctlength > 0){
				if(totllength <= 8){
					$('select[name=mainList]').find('option:selected').each(function() {
						$('<option value="'+$(this).val()+'" selected="selected">'+$(this).text()+'</option>').appendTo('#vials_'+id);
						$(this).remove();
					});
				}else{
					var remain = 8 - parseFloat(length);
					alert("Please select only "+ remain +" allergens for Vial "+id+".");
				}
			}else{
				alert("Please select below or equal 8 allergens for any vials.");
			}
		}

		function movetoMain(id){
			var slctlength = $('#vials_'+id).find('option:selected').length;
			if(slctlength > 0){
				$('#vials_'+id).find('option:selected').each(function() {
					$('<option value="'+$(this).val()+'">'+$(this).text()+'</option>').appendTo('select[name=mainList]');
					$(this).remove();
				});
			}else{
				alert("Please select allergens first, Which are you want to changed.");
			}
		}
		</script>
		<script type="text/javascript">
		$(document).ready(function() {
			$(document).on("click", ".addNextlabOrder", function(){
				$('#scan_lab_number').empty();
				$('#scanBarCodeOrder').modal('show');
			});

			$(document).on('shown.bs.modal', '#scanBarCodeOrder', function(){
				$('#scan_lab_number').focus();
			});

			$(document).on('submit', '#scanBarCodeForm', function(event) {
				event.preventDefault();
				$(this).parsley();
				var scan_lab_number = $('#scan_lab_number').val();
				$.ajax({
					url: "<?php echo base_url('Orders/setBarcodeInsession'); ?>",
					method: "POST",
					data: $(this).serialize(),
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					success: function(data) {
						if (data == 'PAX') {
							$('#scan_lab_number').empty();
							location.href = 'orders/add';
						}else if (data == 'Exist') {
							$('#scan_error_msg').text('');
							$('#scan_error_msg').text('Sorry Scaned Barcode is already exist.');
							$('#scanmessage_error').show();
						}else if (data == 'Empty') {
							$('#scan_error_msg').text('');
							$('#scan_error_msg').text('Sorry Barcode not scanned any number.');
							$('#scanmessage_error').show();
						}
					},
				});
			});
		});
		</script>
	</body>
</html>