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
						<?php echo $this->lang->line("practice");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line("home");?></a></li>
						<li><a href="#"><?php echo $this->lang->line("Users_management");?></a></li>
						<li class="active"><?php echo $this->lang->line("practice");?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--alert msg-->
					<?php if(!empty($this->session->flashdata('success'))){ ?>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i><?php echo $this->lang->line("alert");?></h4>
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php } ?>
					<?php if(!empty($this->session->flashdata('error'))){ ?>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert");?></h4>
							<?php echo $this->session->flashdata('error'); ?>
						</div>
					<?php } ?>
					<!--alert msg-->
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box">
								<div class="box-header" style="padding-bottom: 0px;">
									<h3 class="box-title"><?php echo $this->lang->line("practice");?> <?php echo $this->lang->line("list");?></h3>
									<?php if($userData['role'] == 1 || $userData['role'] == 11){ ?>
										<p class="pull-right">
											<a href="<?php echo base_url('vet_lab_users/add'); ?>" class="btn btn-primary ad-click-event"><?php echo $this->lang->line("add");?></a>
											<?php if($userData['role']==1){ ?>
											<a href="<?php echo base_url('UsersDetails/exportPractice'); ?>" class="btn btn-primary ad-click-event"><?php echo $this->lang->line("export_all_practices");?></a>
											<?php } ?>
										</p>
									<?php } ?>
								</div>
								<!-- /.box-header -->
								<div class="box-body" style="padding-top: 0px;">
									<?php if($userData['role']==1){ ?>
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
										<div class="col-sm-3">
											<div class="form-group">
												<label>&nbsp;</label><br>
												<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn"><?php echo $this->lang->line("filter");?></a>
												<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clearBtn"><?php echo $this->lang->line("clear");?></a>
												<a href="<?php echo base_url('UsersDetails/exportPractice'); ?>" class="btn btn-primary exportPracticeManagedby" style="display:none;">Export Managed By Practices</a>
											</div>
										</div>
									</form>
									<?php } ?>
									<table id="vet_lab_users" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th><?php echo $this->lang->line("name");?></th>
												<th><?php echo $this->lang->line("postal_code");?></th>
												<th><?php echo $this->lang->line("account_ref");?></th>
												<th><?php echo $this->lang->line("vat_applicable");?></th>
												<th><?php echo $this->lang->line("action");?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php echo $this->lang->line("name");?></th>
												<th><?php echo $this->lang->line("postal_code");?></th>
												<th><?php echo $this->lang->line("account_ref");?></th>
												<th><?php echo $this->lang->line("vat_applicable");?></th>
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
		<style>
		table{font-family:arial,sans-serif;border-collapse:collapse;width:100%}
		td,th{border:1px solid #ddd;text-align:left;padding:8px}
		tr:nth-child(even){background-color:#ddd}
		</style>
		<!--pet modal-->
		<div class="modal fade" id="deleteDataList">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Pet owners/Pets/Orders  List</h4>
					</div><!-- /.modal-header -->
					<div class="modal-body">
					   <div id="orders"></div>
					   <div id="pets"></div>
					   <div id="petsOwner"></div>
					</div><!-- /.modal-body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					</div><!-- /.modal-footer -->
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal-->
		<!--pet modal-->
		<script>
		$(document).ready(function(){
			$(".exportPracticeManagedby").hide();
			var dataTable = $('#vet_lab_users').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [{
					orderable: false,
					targets: [1,2,3,4]
				}],
				"fixedColumns": true,
				"ajax": {
					"url": "<?php echo base_url('UsersDetails/vlu_getTableData'); ?>",
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
					{ "data": "account_ref" },
					{
						"data": "vat_applicable",
						render: function(data, type, row, meta) {
							var is_vat = '';
							if (row.vat_applicable == '1') {
								is_vat = '<i class="fa fa-check" aria-hidden="true"></i>';
							}
							if (row.vat_applicable == '0') {
								is_vat = '<i class="fa fa-times" aria-hidden="true"></i>';
							} 
							var is_vat = $(is_vat, {

							});
							// checkbox.attr("checked", "checked");
							return is_vat.prop("outerHTML");
						}
					},
					<?php if($userData['role']==1){ ?>
					{ 
						"data": "id", render : function ( data, type, row, meta ) {
							return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light order_list"  href="<?php echo base_url('orders/'); //base_url('vet_lab_users/orderList/'); ?>'+data+'"  title="View"><i class="fa fa-eye" style="font-size:initial;"></i></a><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('vet_lab_users/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a>  <a class="btn btn-sm btn-outline-light delVatUser" href="javascript:void(0);" data-href="<?php echo base_url('UsersDetails/delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a>  </div>';
						} 
					}
					<?php }elseif($userData['role']==11){ ?>
					{ 
						"data": "id", render : function ( data, type, row, meta ) {
							return '<div class="btn-group"><a class="btn btn-sm btn-outline-light order_list" href="<?php echo base_url('orders/'); ?>'+data+'"  title="View"><i class="fa fa-eye" style="font-size:initial;"></i></a><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('vet_lab_users/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a></div>';
						} 
					}
					<?php }else{ ?>
					{ 
						"data": "id", render : function ( data, type, row, meta ) {
						return  '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('vet_lab_users/edit/'); ?>'+data+'" title="View"><i class="fa fa-eye" style="font-size:initial;"></i></a>  </div>';
						}
					}
					<?php } ?>
				]
			});

			$(document).on('click','.delVatUser', function(){
				if(confirm('Are you sure you want to delete this Vet/Lab User?')){
					var role = 2;
					var href = $(this).data('href');
					$('#cover-spin').show();
					$.ajax({
						url: href,
						type: 'GET',
						data : {"role": role,"type":"vetLabUser"},
						success: function (data) {
							data = JSON.parse(data);
							$('#cover-spin').hide();
							if(data.status==1){
								let orders=data.orders;
								let pets=data.pets;
								let petsOwner=data.petsOwner;
								if(orders.length > 0){
									let orders_html= "<h5>You can not delete this Practice at the moment because you have the following Orders assigned to the Practice</h5>"+
									"<table>"+
									"<tr>"+
									"<th>Order Id</th>"+
									"<th>Order Num</th>"+
									"<th>Name</th>"+
									"</tr>";
									for (let i = 0; i < orders.length; i++) {
										let ordersRow = orders[i];
										orders_html+="<tr>"+
											"<td>"+ordersRow.id +"</td>"+
											"<td>"+ordersRow.order_number +"</td>"+
											"<td>"+ordersRow.name +"</td>"+
										"</tr>"
									}
									orders_html+="</table>";
									$('#orders').html(orders_html);
								}

								if(pets.length > 0){
									let pets_html= "<h5>You can not delete this Practice at the moment because you have the following Pet assigned to the Practice</h5>"+
									"<table>"+
										"<tr>"+
											"<th>Pets Id</th>"+
											"<th>Pets Name</th>"+
											"<th>Pets Age</th>"+
										"</tr>";
										for (let i = 0; i < pets.length; i++) {
											let petsRow = pets[i];
											pets_html+="<tr>"+
												"<td>"+petsRow.id+"</td>"+
												"<td>"+petsRow.name+"</td>"+
												"<td>"+petsRow.age+"</td>"+
											"</tr>";
										}
									pets_html+="</table>";
									$('#pets').html(pets_html);
								}

								if(petsOwner.length > 0){
									let petsOwner_html= "<h5>You can not delete this Practice at the moment because you have the following Pet Owners assigned to the Practice</h5>"+
									"<table>"+
										"<tr>"+
											"<th>Name</th>"+
											"<th>Email</th>"+
										"</tr>";
										for (let i = 0; i < petsOwner.length; i++) {
											let petsOwnerRow = petsOwner[i];
											let a = ''
											if(petsOwnerRow.name!==null){
												a = 'y'
											}else{
												a = 'n'
											}

											petsOwnerRow.name = (petsOwnerRow.name===null) ? '' : petsOwnerRow.name; 
											petsOwnerRow.last_name = (petsOwnerRow.last_name===null) ? '' : petsOwnerRow.last_name; 
											petsOwnerRow.email = (petsOwnerRow.email===null) ? '' : petsOwnerRow.email; 
											petsOwner_html+= "<tr>"+
												"<td>"+petsOwnerRow.name+""+petsOwnerRow.last_name+"</td>"+
												"<td>"+petsOwnerRow.email+"</td>"+
											"</tr>";
										}
									petsOwner_html+="</table>";
									$('#petsOwner').html(petsOwner_html);
								}
								$('#deleteDataList').modal('show');
							}

							if(data.status==0){
								dataTable.ajax.reload();
							}
						}
					});
				}
			});

			$('#filterBtn').on('click', function() {
				dataTable.search('').draw();
				dataTable.ajax.reload();
			});

			$('#managed_by_id').on('change', function() {
				if($(this).val() > 0){
					url = '<?php echo base_url().'UsersDetails/exportPractice'?>';
					$(".exportPracticeManagedby").attr("href", ""+url+"/"+$(this).val()+"");
					$(".exportPracticeManagedby").show();
				}else{
					$(".exportPracticeManagedby").hide();
				}
			});

			$('#clearBtn').on('click', function() {
				$('#filterForm')[0].reset();
				dataTable.ajax.reload();
			});
		});
		</script>
	</body>
</html>