<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Zones Management
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Zones Management</li>
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
						<div class="col-xs-12">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">List</h3>
									<p class="pull-right">
										<a href="<?php echo base_url('managed_by/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
									</p>
								</div>
								<div class="box-body">
									<table id="managed_by" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Zone Name</th>
												<th>Nextmune Email Address for Managed By</th>
												<th>From Email address for Managed By</th>
												<th>Serum Test Address</th>
												<th>Serum Test Phone</th>
												<th>Serum Test Email</th>
												<th>Action</th>
											</tr>
										</thead>

										<tfoot>
											<tr>
												<th>Zone Name</th>
												<th>Nextmune Email Address for Managed By</th>
												<th>From Email address for Managed By</th>
												<th>Serum Test Address</th>
												<th>Serum Test Phone</th>
												<th>Serum Test Email</th>
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
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var dataTable = $('#managed_by').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [
					{ orderable: false, targets: 1 }
				],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('staffMembers/managedby_getTableData'); ?>",
					"type": "POST",
				},
				"columns": [
					{ "data": "managed_by_name" },
					{ "data": "managed_by_email" },
					{ "data": "managed_by_from_email" },
					{ "data": "serum_test_address" },
					{ "data": "serum_test_phone" },
					{ "data": "serum_test_email" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('managed_by/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a></div>';
					} }
				]
			});
		});
		</script>
	</body>
</html>