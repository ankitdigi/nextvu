<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Labs
						<small>Report panel</small>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?php echo base_url('Reports/exportLabReport'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export All Labs With Order Type</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url('Reports/exportLabReportAllergens'); ?>" class="btn btn-primary ad-click-event"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export All Labs With Allergens</a>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Reports Management</a></li>
						<li class="active">Labs Report</li>
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
									<table id="lab_users" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Name</th>
												<th>Postal Code</th>
												<th>Total Spent</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Name</th>
												<th>Postal Code</th>
												<th>Total Spent</th>
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
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var dataTable = $('#lab_users').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [{
					orderable: false,
					targets: [1]
				}],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('Reports/getLabTableData'); ?>",
					"type": "POST",
				},
				"columns": [
					{ "data": "name" },
					{ "data": "post_code" },
					{
						"data": "id",
						render: function(data, type, row, meta) {
							return '£'+row.total_spent;
						}
					},
				]
			});
		});
		</script>
	</body>
</html>