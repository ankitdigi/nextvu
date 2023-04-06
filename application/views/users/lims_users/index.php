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
					<?php echo $this->lang->line("lims_users");?>
						<small><?php echo $this->lang->line("control_panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("home");?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("settings");?></a></li>
						<li class="active"><?php echo $this->lang->line("lims_users");?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> <?php echo $this->lang->line("alert");?></h4>
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php } ?>

					<?php if(!empty($this->session->flashdata('error'))){ ?>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-warning"></i><?php echo $this->lang->line("alert");?></h4>
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
									<h3 class="box-title"><?php echo $this->lang->line("list");?></h3>
									<p class="pull-right">
										<a href="<?php echo base_url('lims_users/add'); ?>" class="btn btn-primary ad-click-event"><?php echo $this->lang->line("add");?></a>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="lims_users" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th><?php echo $this->lang->line("name");?></th>
												<th><?php echo $this->lang->line("email");?></th>
												<th><?php echo $this->lang->line("action");?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php echo $this->lang->line("name");?></th>
												<th><?php echo $this->lang->line("email");?></th>
												<th><?php echo $this->lang->line("action");?></th>
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
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
		<script>
		$(document).ready(function(){
			var dataTable = $('#lims_users').DataTable({
				"processing": true,
				"serverSide": true,
				// "order": [[ 1, 'desc' ]],
				"columnDefs": [
					{ orderable: false, targets: 1 }
				],
				"fixedColumns": true,
				"language": {
					"infoFiltered": ""
				},
				"ajax": {
					"url": "<?php echo base_url('Users/lims_users_getTableData'); ?>",
					"type": "POST",
				},
				"columns": [
					{ "data": "name" },
					{ "data": "email" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('lims_users/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a>  <a class="btn btn-sm btn-outline-light delUser" href="javascript:void(0);" data-href="<?php echo base_url('users/lims_user_delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a>  </div>';
					} }
				]
			});

			$(document).on('click','.delUser', function(){
				if(confirm('Are you sure you want to delete this LIMS User?')){
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