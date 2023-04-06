<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						Allergen Groups
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Allergen Groups</li>
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
									<h3 class="box-title">Allergen Group List</h3>
									<p class="pull-right">
										<a href="<?php echo base_url('allergens/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="allergens" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Group Name</th>
												<th>PAX Group Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Group Name</th>
												<th>PAX Group Name</th>
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
		</div>
		<!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var dataTable = $('#allergens').DataTable({
				"processing": true,
				"serverSide": true,
				// "order": [[ 1, 'desc' ]],
				"columnDefs": [
					{ orderable: false, targets: 1 }
				],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('allergens/getTableData'); ?>",
					"type": "POST",
				},
				"columns": [
					{ "data": "name" },
					{ "data": "pax_name" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('allergens/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a>  <!-- <a class="btn btn-sm btn-outline-light delAllergen" href="javascript:void(0);" data-href="<?php echo base_url('allergens/delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a> -->  </div>'; }
					}
				]
			});

			$(document).on('click','.delAllergen', function(){
				if(confirm('Are you sure you want to delete this Allergen?')){
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