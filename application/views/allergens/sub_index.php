<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
?>
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Allergens
						<small>Control panel</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#"> Settings</a></li>
						<li class="active">Allergens</li>
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
									<h3 class="box-title">Allergens List</h3>
									<p class="pull-right">
										<a href="<?php echo base_url('sub_allergens/add'); ?>" class="btn btn-primary ad-click-event">Add</a>
									</p>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<!--filter form-->
									<form class="row" style="margin-bottom: 30px;" id="filterForm" method="POST" action="">
										<div class="col-sm-3">
											<div class="form-group">
												<label>Order Type</label>
												<select class="form-control form-control-sm" name="order_type" id="order_type">
													<option value="">--Select--</option>
													<option value="1">Artuvetrin immunotherapy</option>
													<option value="2">Sublingual immunotherapy (SLIT)</option>
													<option value="3">NextLab - Dog - Environmental</option>
													<option value="31">NextLab - Cat - Environmental</option>
													<option value="6">NextLab - Horse - Environmental</option>
													<option value="5">NextLab - Dog - Food</option>
													<option value="51">NextLab - Cat - Food</option>
													<option value="7">NextLab - Horse - Food</option>
													<option value="8">PAX - Environmental - Dog</option>
													<option value="9">PAX - Food - Dog</option>
													<option value="81">PAX - Environmental - Cat</option>
													<option value="91">PAX - Food - Cat</option>
													<option value="82">PAX - Environmental - Horse</option>
													<option value="92">PAX - Food - Horse</option>
													<option value="11">PAX - Environmental - US</option>
													<option value="12">PAX - Food - US</option>
													<option value="4">Skin Test</option>
													<option value="13">Vet-Goid</option>
													<option value="14">Pet SLIT</option>
												</select>
											</div>
										</div>
										<div class="col-sm-12">
											<a href="javascript:void(0);" class="btn btn-success btn-sm" id="filterBtn">Filter</a>
											<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clearBtn">Clear</a>
										</div>
									</form>
									<!--filter form-->
									<form id="subAllergensForm" name="subAllergensForm" method="POST" action="">
										<input type="hidden" id="check_counter" value='0'>
										<input type="hidden" name="ltest" id="ltest">
										<input type="hidden" name="rtest" id="rtest">
										<table id="sub_allergens" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th><input type="checkbox" name="selectall" value="1" id="selectall" class="checkbox_cls"></th>
													<th>Date</th>
													<th>Code</th>
													<th>Allergen Name</th>
													<th>Allergen Group</th>
													<th>PAX Name</th>
													<th>PAX Group Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>&nbsp;</th>
													<th>Date</th>
													<th>Code</th>
													<th>Allergen Name</th>
													<th>Allergen Group</th>
													<th>PAX Name</th>
													<th>PAX Group Name</th>
													<th>Action</th>
												</tr>
											</tfoot>
										</table>
										<div class="box-footer">
											<button type="submit" class="btn btn-primary">Save</button>
										</div>
									</form>
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
			var target = [ 0, 3 ];
			var dataTable = $('#sub_allergens').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [[ 2, 'asc' ]],
				"columnDefs": [
					{ orderable: false, targets: target }
				],
				"fixedColumns": true,
				"fnDrawCallback": function() {
					$(".due_date").datepicker({
						format: "dd/mm/yyyy",
						todayHighlight: true,
						autoclose: true,
					})
				},
				"ajax": {
					"url": "<?php echo base_url('allergens/sub_getTableData'); ?>",
					"type": "POST",
					"async" : false,
					"data": {
						formData: function() {
							return $('#filterForm').serialize();
						},
					},
				},
				"columns": [   
					{ "data": "id", render : function ( data, type, row, meta ) {
						var is_unavailable='';
						if(row.is_unavailable==1){
							is_unavailable = 'checked';
						}
						var checkbox = $("<input type='checkbox' name='check_list[]' value='" + row.id + "' "+is_unavailable+"/>",{ });
						//checkbox.attr("checked", "checked");
						return checkbox.prop("outerHTML");
						} 
					},
					{ "data": "id", render : function ( data, type, row, meta ) {
						var due_date = $("<div class='input-group date'><div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' name='due_date[]' id='due_date' class='due_date form-control pull-right' value='"+row.due_date+"' data-allergen_id='"+data+"' autocomplete='off'/></div>",{ });
						//checkbox.attr("checked", "checked");
						return due_date.prop("outerHTML");
						} 
					},
					{ "data": "code" },
					{ "data": "name" },
					{ "data": "parent_name" },
					{ "data": "pax_name" },
					{ "data": "pax_parent_name" },
					{ "data": "id", render : function ( data, type, row, meta ) {
						return '<div class="btn-group"><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('sub_allergens/edit/'); ?>'+data+'" title="Edit"><i class="fa fa-pencil" style="font-size:initial;"></i></a> <!-- <a class="btn btn-sm btn-outline-light delSubAllergen" href="javascript:void(0);" data-href="<?php echo base_url('allergens/delete/'); ?>'+data+'" title="Delete"><i class="fa fa-trash" style="font-size:initial;"></i></a> -->  </div>';
						}
					}
				]
			});

			dataTable.columns(1).visible(true);

			$('#filterBtn').on('click', function(){
				$('#sub_allergens_filter input').val('');
				dataTable.search('').draw(); 
				dataTable.ajax.reload();
			});

			$('#clearBtn').on('click', function(){
				$('#filterForm')[0].reset();
				$('.selectpicker').selectpicker('refresh');
				dataTable.ajax.reload();
			});

			$(document).on('click','.delSubAllergen', function(){
				if(confirm('Are you sure you want to delete this sub allergen?')){
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

			var check_counter = $('#check_counter').val();
			var ltest = [];
			var rtest = [];
			$('body').on('change','input[name="check_list[]"]',function(e){
				if($(this).is(":checked")){
					ltest.push( $(this).val() );
					rtest.pop( $(this).val() );
					//console.log(ltest);
					check_counter++;
				}else{
					ltest.pop( $(this).val() );
					rtest.push( $(this).val() );
					if(check_counter!=0){
						check_counter--;
					}
				}
				$('#check_counter').val(check_counter);
				$('#ltest').val(ltest);
				$('#rtest').val(rtest);
			});

			$('body').on('change','#selectall',function(e){
				if($(this).prop('checked')){
					dataTable.columns(1).visible(true);
					$('input[name="check_list[]"]').each(function(){
						$(this).prop('checked',true);
						ltest.push($(this).val());
						check_counter++;
					});
				}else{
					dataTable.columns(1).visible(false);
					$('input[name="check_list[]"]').each(function(){
						$(this).prop('checked',false);
						ltest.pop($(this).val());
						check_counter++;
						if(check_counter!=0){
							check_counter--;
						}
					});
				}
				$('#check_counter').val(check_counter);
				$('#ltest').val(ltest);
			});

			$('#subAllergensForm').submit(function(e){
				e.preventDefault();

				var check_counter = $('#check_counter').val();
				var order_type = $('#order_type').val();
				var ltest = $('#ltest').val();
				var rtest = $('#rtest').val();

				var allergen_id = '';
				var due_date_arr = [];
				$('.due_date:visible').each(function(){
					allergen_id = $(this).data('allergen_id');
					if($(this).val()!=''){
						due_date_arr.push({
							'id' : allergen_id,
							'due_date': $(this).val()
						});
					}
				});
				$.ajax({
					"type":"POST",
					"url":"<?php echo base_url('allergens/unavailable'); ?>",
					"data" : {"order_type": order_type,"due_date_array":due_date_arr,"rtest":rtest},
					"dataType": "json",
					"async":false,
					success:function(data){
						if(data.status=='success'){
							alert('Allergens have been set.');
							dataTable.ajax.reload();
						}else if(data.status.trim()=='fail'){
							alert('Something went wrong! please try again.');
						}else if(data.status.trim()=='nothing_selected'){
							alert('Please select atleast one allergen!');
						}
					}
				});
			});
		});
		</script>
	</body>
</html>