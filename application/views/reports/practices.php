<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Practice
						<small>Report panel</small>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?php echo base_url('Reports/exportPracticesReport'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export All Practices With Order Type</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url('Reports/exportPracticesReportAllergens'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export All Practices With Allergens</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" class="btn btn-primary exportPracticeManagedby"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Invoice Data Export</a>
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
					<div class="row">
						<div class="col-xs-12">
							<div class="box">
								<div class="box-body">
									<?php if($userData['role'] == 1 || count(explode(",",$this->zones)) > 1){ ?>
									<form class="row" id="filterForm" method="POST" action="">
										<div class="col-sm-3">
											<div class="form-group">
												<label>Managed By</label>
												<select class="form-control form-control-sm" name="managed_by_id" id="managed_by_id">
													<option value="">--Select--</option>
													<?php
													$this->db->select("ci_managed_by_members.*");
													$this->db->from('ci_managed_by_members');
													if($this->zones != ""){
														$this->db->where('ci_managed_by_members.id IN('.$this->zones.')');
													}
													$this->db->order_by('managed_by_name','ASC');
													$zones_by = $this->db->get()->result_array();
													if(!empty($zones_by)){
														foreach($zones_by as $row){
															echo '<option value="'. $row['id'] .'">'. $row['managed_by_name'] .'</option>';
														}
													}
													?>
												</select>
											</div>
										</div>
										<?php /* <div class="col-sm-3">
											<div class="form-group">
												<label>&nbsp;</label><br>
												<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn"><?php echo $this->lang->line("filter");?></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clearBtn"><?php echo $this->lang->line("clear");?></a>
											</div>
										</div> */ ?>
									</form>
									<?php }else{
										$this->db->select("ci_managed_by_members.*");
										$this->db->from('ci_managed_by_members');
										if($this->zones != ""){
											$this->db->where('ci_managed_by_members.id IN('.$this->zones.')');
										}
										$this->db->order_by('managed_by_name','ASC');
										$zones_by = $this->db->get()->row();
										?>
										<input type="hidden" id="managed_by_id" value="<?php echo $zones_by->id; ?>">
										<input type="hidden" id="managed_by_name" value="<?php echo $zones_by->managed_by_name; ?>">
									<?php } ?>
									<table id="practice_users" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Name</th>
												<th>Postal Code</th>
												<th>Total Spent</th>
												<th>Action</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Postal Code</th>
												<th>Total Spent</th>
												<th>Action</th>
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
		<div class="modal fade" id="daterangeModal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Please enter the date range for invoice data download for Managed By <span id="mname" style="font-weight: bold;"></span></h4>
					</div>
					<?php echo form_open('', array('name' => 'daterangeForm', 'id' => 'daterangeForm')); ?>
						<div class="modal-body" style="border: 0px;padding: 0px;clear: both;display: inline-block;">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<span id="daterangemessage" class="text-danger"></span>
							</div>
							<input type="hidden" name="managed_by_id_modal" id="managed_by_id_modal" value="">
							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="form-group" style="margin-top: 15px;">
									<label>From Date</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right" id="from_date" value="<?php echo date("d/m/Y",strtotime("-1 month")); ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="form-group" style="margin-top: 15px;">
									<label>To Date</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right" id="to_date" value="<?php echo date('d/m/Y'); ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="form-group" style="margin-top: 15px;">
									<label><?php echo $this->lang->line("Order_Type");?></label>
									<select class="form-control form-control-sm" name="order_type" id="order_type">
										<option value="">--Select--</option>
										<option value="1"><?php echo $this->lang->line("Immunotherapy");?></option>
										<option value="2"><?php echo $this->lang->line("serum_testing");?></option>
										<option value="3" <?php if($this->user_role == 5){ echo 'style="display:none"'; } ?>><?php echo $this->lang->line("skin_test");?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="button" id="exportData" class="btn btn-primary">Export Invoice Data</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var dataTable = $('#practice_users').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [{
					orderable: false,
					targets: [1,3]
				}],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('Reports/getPracticeTableData'); ?>",
					"type": "POST",
					"async": false,
					"data": {
						formData: function() {
							return $('#filterForm').serialize();
						},
					},
				},
				"columns": [
					{ "data": "name" },
					{ "data": "address_3" },
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '£'+row.total_spent;
						}
					},
					{ 
						"data": "id", render : function ( data, type, row, meta ) {
							return '<div class="btn-group"><a class="btn btn-sm btn-outline-light order_list" href="<?php echo base_url('orders/');?>'+data+'" title="View Orders"><i class="fa fa-eye" style="font-size:initial;"></i></a></div>';
						} 
					}
				]
			});
			
			$('#filterBtn').on('click', function() {
				dataTable.search('').draw();
				dataTable.ajax.reload();
			});

			$('.exportPracticeManagedby').on('click', function() {
				var managedBy = $("#managed_by_id").val();
				if(managedBy > 0){
					$('#daterangemessage').text('');
					$('#managed_by_id_modal').val('');
					$('#managed_by_id_modal').val(managedBy);
					<?php if($userData['role'] == 1 || count(explode(",",$this->zones)) > 1){ ?>
					$('#mname').text($('#managed_by_id').find('option:selected').text());
					<?php }else{ ?>
					$('#mname').text($('#managed_by_name').val());
					<?php } ?>
					$('#daterangeModal').modal('show');
				} else {
					alert('Please select a Managed By zone, then click the Export button again.');
				}
			});

			$('#exportData').on('click', function() {
				event.preventDefault();
				var fromDate = $("#from_date").val();
				var toDate = $("#to_date").val();
				var order_type = $("#order_type").val();
				if(fromDate != '' && toDate != '' && order_type > 0){
					var managedBy = $('#managed_by_id_modal').val();
					var parts = fromDate.split('/');
					var newsdate = parts[2]+'-'+parts[1]+'-'+parts[0];
					var partt = toDate.split('/');
					var newtdate = partt[2]+'-'+partt[1]+'-'+partt[0];
					var startDate = new Date(newsdate);
					var endDate = new Date(newtdate);
					var days = showDays(newsdate,newtdate);
					if(Date.parse(startDate) <= Date.parse(endDate) && days <= '365'){
						var url = '<?php echo base_url().'Reports/exportAllOrders'?>';
						url = ''+url+'/'+managedBy+'/'+order_type+'/'+newsdate+'/'+newtdate+'';
						window.open(url, '_blank');
						$('#daterangemessage').text('');
						$('#managed_by_id_modal').val('');
						$('#mname').text('');
						$('#daterangeModal').modal('hide');
					} else {
						if(days > '365'){
							$('#daterangemessage').text('You can only export 1 year at a time. Please ensure the From and To dates are no more than 1 year (365 days) apart');
						}else{
							$('#daterangemessage').text('Please select To date is the same or greater date than the From date');
						}
					}
				} else {
					if(fromDate == '' && toDate == ''){
						$('#daterangemessage').text('Please select Date Range you wish to Export');
					}else if(fromDate == ''){
						$('#daterangemessage').text('Please enter a FROM date, then click the Export button again.');
					}else if(toDate == ''){
						$('#daterangemessage').text('Please enter a TO Date, then click the Export button again.');
					}else if(order_type == '' || order_type == '0'){
						$('#daterangemessage').text('Please select a Order Type, then click the Export button again.');
					}
				}
			});

			/* $('.exportPracticeManagedby').on('click', function() {
				var fromDate = $("#from_date").val();
				var toDate = $("#to_date").val();
				var managedBy = $("#managed_by_id").val();
				if(fromDate != '' && toDate != ''){
					var parts = fromDate.split('/');
					var newsdate = parts[2]+'-'+parts[1]+'-'+parts[0];
					var partt = toDate.split('/');
					var newtdate = partt[2]+'-'+partt[1]+'-'+partt[0];
					var startDate = new Date(newsdate);
					var endDate = new Date(newtdate);
					var days = showDays(newsdate,newtdate);
					if(Date.parse(startDate) <= Date.parse(endDate) && days <= '365' && managedBy > 0){
						var url = '<?php echo base_url().'Reports/exportAllOrders'?>';
						url = ''+url+'/'+managedBy+'/'+newsdate+'/'+newtdate+'';
						window.open(url, '_blank');
					} else {
						if(days > '365'){
							alert('You can only export 1 year at a time. Please ensure the From and To dates are no more than 1 year (365 days) apart');
						}else if(managedBy == ''){
							alert('Please select a Managed By zone, then click the Export button again.');
						}else{
							alert('Please select To date is the same or greater date than the From date');
						}
					}
				} else {
					if(fromDate == '' && toDate == ''){
						alert('Please select Date Range you wish to Export');
					}else if(fromDate == ''){
						alert('Please enter a FROM date, then click the Export button again.');
					}else if(toDate == ''){
						alert('Please enter a TO Date, then click the Export button again.');
					}
				}
			}); */

			/* $('#managed_by_id').on('change', function() {
				if($(this).val() > 0){
					url = '<?php echo base_url().'Reports/exportAllOrders'?>';
					$(".exportPracticeManagedby").attr("href", ""+url+"/"+$(this).val()+"");
				}else{
					url = '<?php echo base_url().'Reports/exportAllOrders'?>';
					$(".exportPracticeManagedby").attr("href", ""+url+"");
				}
			}); */

			$('#clearBtn').on('click', function() {
				$('#filterForm')[0].reset();
				dataTable.ajax.reload();
			});

			$('#from_date').datepicker({
				format: 'dd/mm/yyyy'
			}).val('<?php echo date("d/m/Y",strtotime("-1 month")); ?>');
			
			$('#to_date').datepicker({
				format: 'dd/mm/yyyy'
			}).val('<?php echo date('d/m/Y'); ?>');
		});

		function showDays(start,end){
			var startDay = new Date(start);
			var endDay = new Date(end);
			var millisecondsPerDay = 1000 * 60 * 60 * 24;
			var millisBetween = endDay.getTime() - startDay.getTime();
			var days = millisBetween / millisecondsPerDay;
			return Math.floor(days);
		}
		</script>
	</body>
</html>