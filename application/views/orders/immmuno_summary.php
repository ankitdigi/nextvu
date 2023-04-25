<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
if($order_details['lab_id'] > 0){
	$practiceLab = $this->UsersModel->practiceLabCountry($order_details['lab_id']);
}else{
	$practiceLab = $this->UsersModel->practiceLabCountry($order_details['vet_user_id']);
}
?>
			<style>
			#canvasDiv{position:relative;border:2px dashed grey;height:300px;width:746px}
			</style>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("summary");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"> <?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content">
					<!--breadcrumb-->
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<!--breadcrumb-->
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
						<h4><i class="icon fa fa-warning"></i> <?php echo $this->lang->line("alert");?></h4>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<!--alert msg-->
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<!-- <h3 class="box-title">Details</h3>
									<p class="pull-right"> -->
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php
									if($practiceLab['name']=='UK'){
										echo '<a class="btn btn-primary attachSICModal" data-order_id="'.$id.'" data-toggle="modal" data-target="#attachSICModal" title="Attach SIC"> Attach SIC</a> &nbsp;&nbsp;&nbsp;&nbsp;';
									}
									?>
									<a class="btn btn-primary changeDAddressModal" data-order_id="<?php echo $id; ?>" data-toggle="modal" data-target="#changeDAddressModal" title="Change Delivery Address"><?php echo $this->lang->line("change_delivery_address");?> </a>
									<!-- </p> -->
								</div><!-- /.box-header -->
								<?php 
								if($order_details['order_type']==1){ 
								$forder_type = 'Immunotherapy';
								}elseif($order_details['order_type']==2){
								$forder_type = 'Serum Testing';
								}elseif($order_details['order_type']==3){
								$forder_type = 'Skin Test';
								}

								if($order_details['sub_order_type']==1){
								$fsub_order_type = 'Artuvetrin immunotherapy';
								}elseif($order_details['sub_order_type']==2){
								$fsub_order_type = 'Sublingual immunotherapy (SLIT)';
								}elseif($order_details['sub_order_type']==3){
								$fsub_order_type = 'Serum Request';
								}elseif($order_details['sub_order_type']==4){
								$fsub_order_type = 'Order form artuvetrin Skin Test';
								}
								?>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<div class="form-group">
												<label></label>
												<dl class="dl-horizontal">
													<dt><?php echo $this->lang->line("order_number");?> </dt>
														<?php if ($order_details['lab_id'] > 0) { ?>
															<dd><?php echo $order_details['reference_number']; ?></dd>
														<?php } else { ?>
															<dd><?php echo $order_details['order_number']; ?></dd>
														<?php } ?>
													<dt><?php echo $this->lang->line("order_date");?></dt>
														<dd><?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?></dd>
														<?php if ($order_details['lab_id'] > 0) { ?>
														<dt><?php echo $this->lang->line("lab_name");?></dt>
														<?php } else {?>
													<dt><?php echo $this->lang->line("practice");?></dt>
													<?php } ?>
													<?php 
													if ($order_details['lab_id'] > 0) {
													$final_name = $order_details['lab_name'];
													} elseif ($order_details['vet_user_id'] > 0) {
													$final_name = $order_details['practice_name'];
													} else {
													$final_name = '';
													}
													?>
													<dd><?php echo $final_name; ?></dd>
													<?php if( $delivery_address_details!='' ){ ?>
													<dt><?php echo $this->lang->line("delivery_address_details");?></dt>
														<?php 
														if ($order_details['lab_id'] > 0) {
															if ($order_details['order_can_send_to'] == '1') {
																$final_name = $order_details['delivery_practice_name'];
															}else{
																$final_name = $order_details['lab_name'];
															}
														} elseif ($order_details['vet_user_id'] > 0) {
															$final_name = '';
														} else {
															$final_name = '';
														}
														?>
														<dd><?php echo ( !empty($final_name) ? $final_name.' - ' : '' ) .$delivery_address_details; ?></dd>
													<?php } ?>
													<?php if($order_details['order_type']!=3){?>
													<dt><?php echo $this->lang->line("pet_owners_name");?></dt>
														<dd><?php echo (!empty($order_details['pet_owner_name'] || !empty($order_details['po_last'])) ? $order_details['pet_owner_name'].' '.$order_details['po_last'] : ''); ?></dd>
													<dt><?php echo $this->lang->line("pet_name");?></dt>
														<?php
														if ($order_details['pet_id'] > 0) {
															$breedData = $this->OrdersModel->getPetbreeds($order_details['pet_id']);
															$breedName = !empty($breedData)?'- '.$breedData:'';
														}else{
															$breedName = '';
														}
														?>
														<dd><?php echo $order_details['pet_name'].' '.$breedName; ?></dd>
													<?php } ?>
													<dt><?php echo $this->lang->line("Order_Type");?></dt>
														<dd><?php echo $forder_type; ?></dd>
													<dt><?php echo $this->lang->line("sub_order_type");?></dt>
														<dd><?php echo $fsub_order_type; ?></dd>
													<dt><?php echo $this->lang->line("Allergens");?></dt>
														<?php
														$getAllergenParent = $this->AllergensModel->getAllergenParent($order_details['allergens']);
														$allergens_html = "";
														foreach ($getAllergenParent as $apkey => $apvalue) {
															$allergens_html .= "<div class='col-sm-6 col-md-6 col-lg-6'><strong>" . $apvalue['name'] . "</strong>";
															$subAllergens = $this->AllergensModel->get_subAllergens_dropdown($apvalue['parent_id'], $order_details['allergens']);
															foreach ($subAllergens as $skey => $svalue) {
																$allergens_html .= "<ul><li>" . $svalue['name'] . "</li></ul>";
															}
															$allergens_html .= "</div>";
														}
														?>
														<dd><?php echo $allergens_html; ?></dd>
													<dt><?php echo $this->lang->line("number_of_allergens");?></dt>
														<dd><?php echo ( $total_allergens >0 ) ? $total_allergens : "0"; ?></dd>
													<?php if($order_details['sic_document']!=''){ ?>
														<dt><?php echo $this->lang->line("sic_document");?></dt>
														<dd>
														<?php 
														$sic_file = $order_details['sic_document'];
														$sic_file_path = base_url().SIC_DOC_PATH.'/'.$data['sic_document']; 
														?> 
														<iframe src="<?php echo $sic_file_path; ?>" width="90%" height="500px"></iframe>
														</dd>
													<?php } ?>
												</dl>
											</div>
											<?php if( ($controller=='repeatOrder' && $userData['role']!=1) || ( $data['signature']=='' && $userData['role']==5 )  || ( $data['signature']=='' && $userData['role']==6 ) || ( $data['signature']=='' && $userData['role']==7 ) || ( $data['signature']=='' && $userData['role']==2 ) ){ ?>
												<!-- <div class="form-group">
													<label>Signature</label>
													<div class="form-control" id="canvasDiv"></div>
													<button type="button" class="btn btn-danger" id="reset-btn">Clear</button>
												</div> -->
											<?php }else{ ?>
												<?php if($data['signature']!='' && $controller!='repeatOrder' ){ ?>
													<div class="form-group">
														<label><?php echo $this->lang->line("signature");?> </label>
														<img src="<?php echo base_url().SIGNATURE_PATH.$data['signature']; ?>" alt="Signature" width="250" height="150">
														<div style="margin-left:100px;"><?php echo $order_details['practice_name']; ?></div>
													</div>
												<?php } ?>
											<?php } ?>
										</div><!-- /.col -->
									</div><!-- /.row -->
								</div><!-- /.box-body -->
							</div><!-- /.box -->

							<!-- form start -->
							<?php echo form_open('', array('name'=>'signatureForm', 'id'=>'signatureForm')); ?>
								<input type="hidden" id="sic_document" value="<?php echo $order_details['sic_document'];?>" required="required">
								<!-- order price and order discount elements -->
								<div class="box box-primary">
									<div class="box-header with-border"><h3 class="box-title"><?php echo $this->lang->line("price_details");?></h3></div><!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<?php if($userData['role']!=1 && $id>0){ $readonly = "readonly"; }else{ $readonly = ""; } ?>
												<div class="col-sm-4 col-md-4 col-lg-4">
													<div class="form-group">
														<label><?php echo $this->lang->line("order_price_£GBP");?></label>
														<div class="input-group">
															<div class="input-group-addon">
																<i class="fa fa-gbp"></i>
															</div>
															<input type="text" class="form-control" name="unit_price" id="unit_price" placeholder="<?php echo $this->lang->line("enter_order_price");?>" value="<?php echo set_value('unit_price',isset($final_price) ? $final_price : '');?>" <?php echo $readonly; ?>>
															<input type="hidden" id="default_unit_price" value="<?php echo ($final_price-$shipping_cost); ?>" >
														</div>
														<?php echo form_error('unit_price', '<div class="error">', '</div>'); ?>
													</div>
												</div><!-- /.col -->
												<div class="col-sm-4 col-md-4 col-lg-4">
													<?php 
													if(($userData['role'] == 1) || ($userData['role'] == 2) || ($id == '') || ($userData['role'] == 5 && $this->session->userdata('user_type') == '3') || ($userData['role'] == 6) || ($userData['role'] == 7)){ ?>
														<div class="form-group">
															<label><?php echo $this->lang->line("order_discount");?></label>
															<div class="input-group">
																<div class="input-group-addon">
																	<i class="fa fa-gbp"></i>
																</div>
																<input type="text" class="form-control" name="order_discount" placeholder="<?php echo $this->lang->line("enter_order_discount");?>" value="<?php echo set_value('order_discount',isset($order_discount) ? $order_discount : '');?>" <?php echo $readonly; ?>>
																<?php echo form_error('order_discount', '<div class="error">', '</div>'); ?>
															</div>
														</div>
													<?php } ?>
												</div><!-- /.col -->
												<div class="col-sm-4 col-md-4 col-lg-4">
													<?php if(($userData['role'] == 1) || ($userData['role'] == 5 && $this->session->userdata('user_type') == '3')){ ?>
														<div class="form-group">
															<label><?php echo $this->lang->line("shipping_cost");?></label>
															<div class="input-group">
																<div class="input-group-addon">
																	<i class="fa fa-gbp"></i>
																</div>
																<input type="text" class="form-control" name="shipping_cost" id="shipping_cost" placeholder="<?php echo $this->lang->line("enter_order_discount");?>" value="<?php echo set_value('shipping_cost',isset($shipping_cost) ? $shipping_cost : '');?>"  <?php echo $readonly; ?>>
																<?php echo form_error('shipping_cost', '<div class="error">', '</div>'); ?>
															</div>
														</div>
													<?php }else{ ?>
														<input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
													<?php } ?>
												</div><!-- /.col -->
											</div><!-- /.row -->
										</div><!-- /.box-body -->
									</div><!-- /.box -->
								<!-- order price and order discount elements -->

								<?php if( ( $controller=='repeatOrder' && $userData['role']!=1 ) || ($userData['role']==2 && $data['signature']=='') || ($userData['role']==5 && $data['signature']=='')  || ($userData['role']==6 && $data['signature']=='') || ($userData['role']==7 && $data['signature']=='') ) { ?>
									<input type="hidden" id="signature" name="signature">
									<input type="hidden" name="signaturesubmit" value="1">
									<div class="box-footer">
										<p class="pull-right">
											<a class="btn btn-primary signatureModal" data-order_id="<?php echo $id; ?>" data-toggle="modal" data-target="#signatureModal" title="Submit" id="btn-save"><?php echo $this->lang->line("submit_order");?></a>
										</p>
									</div>
								<?php }else{ ?>
									<input type="hidden" name="signaturesubmit" value="0">
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line("submit_order");?></button>
										</p>
									</div>
								<?php } ?>
							<?php echo form_close(); ?>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>  
		</div>
		<?php $this->load->view("script"); ?>
		<div class="modal fade" id="signatureModal">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content">
					<input type="hidden" name="order_id_modal" id="order_id_modal" value="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("signature");?></h4>
					</div>
					<?php echo form_open('', array('name'=>'signatureModalForm', 'id'=>'signatureModalForm')); ?>
						<div class="modal-body">
							<label><?php echo $this->lang->line("signature");?></label>
							<div class="form-control" id="canvasDiv"></div>
							<button type="button" class="btn btn-danger" id="reset-btn"><?php echo $this->lang->line("clear");?></button>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("confirm");?></button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		
		<!-- Upload Attach SIC -->
		<div class="modal fade" id="attachSICModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("upload_sic");?></h4>
					</div>
					<?php echo form_open('', array('name' => 'addSICDocumentForm', 'id' => 'addSICDocumentForm')); ?>
						<div class="modal-body">
							<span id="sic_message" class="text-danger"></span>
							<input type="hidden" name="order_id_sic_modal" id="order_id_sic_modal" value="">
							<div class="form-group">
								<label for="sic_document"><?php echo $this->lang->line("sic_document");?> <small><?php echo $this->lang->line("file_name_should_not_include_a_or_symbols");?></small></label>
								<input type="file" name="sic_document" required="" data-parsley-file_extension='pdf'>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("upload");?></button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!-- Upload Attach SIC -->

		<!-- Upload Attach SIC -->
		<div class="modal fade" id="changeDAddressModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php echo $this->lang->line("change_delivery_address");?></h4>
					</div>
					<?php echo form_open('', array('name' => 'addDeliveryAddressForm', 'id' => 'addDeliveryAddressForm')); ?>
						<div class="modal-body">
							<span id="dAddress_message" class="text-danger"></span>
							<input type="hidden" name="order_id_daddress_modal" id="order_id_daddress_modal" value="">
							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("other_practices");?></label><br>
								<?php
								$deli_options = array();
								$deli_options[''] = '-- Select --';
								if (!empty($deliveryPractices)) {
									foreach ($deliveryPractices as $deliveryPractice) {
										$deliveryPracticeID = $deliveryPractice['id'];
										$post_code = ($deliveryPractice['postcode']) ? ' - ' . $deliveryPractice['postcode'] : '';
										$deli_options[$deliveryPracticeID] = $deliveryPractice['name'] . $post_code;
									}
								}
								$attr = 'class="form-control delivery_practice_id" data-live-search="true"';
								echo form_dropdown('delivery_practice_id', $deli_options, set_value('delivery_practice_id', isset($data['delivery_practice_id']) ? $data['delivery_practice_id'] : ''), $attr); ?>
								<?php echo form_error('delivery_practice_id', '<div class="error">', '</div>'); ?>
							</div>
							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("Address_1");?></label>
								<input type="text" class="form-control"  id="address1" name="address1" placeholder="<?php echo $this->lang->line("enter_Address_1");?>" value="<?php echo set_value('address1',isset($data['address1']) ? $data['address1'] : '');?>">
								<?php
								echo form_error('address1', '<div class="error">', '</div>'); ?>
							</div>

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("Address_2");?></label>
								<input type="text" class="form-control" id="address2" name="address2" placeholder="<?php echo $this->lang->line("enter_Address_2");?>" value="<?php echo set_value('address2',isset($data['address2']) ? $data['address2'] : '');?>">
								<?php
								echo form_error('address2', '<div class="error">', '</div>'); ?>
							</div>

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("Address_3");?></label>
								<input type="text" class="form-control" id="address3" name="address3" placeholder="<?php echo $this->lang->line("enter_Address_3");?>" value="<?php echo set_value('address3',isset($data['address3']) ? $data['address3'] : '');?>">
								<?php
								echo form_error('address3', '<div class="error">', '</div>'); ?>
							</div>

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("town_citys");?></label>
								<input type="text" class="form-control" id="town_city" name="town_city" placeholder="<?php echo $this->lang->line("enter_town_city");?>" value="<?php echo set_value('town_city',isset($data['town_city']) ? $data['town_city'] : '');?>">
								<?php
								 echo form_error('town_city', '<div class="error">', '</div>'); ?>
							</div>

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("country");?></label>
								<input type="text" class="form-control" id="county" name="county" placeholder="<?php echo $this->lang->line("enter_country");?>" value="<?php echo set_value('county',isset($data['county']) ? $data['county'] : '');?>">
								<?php 
								echo form_error('county', '<div class="error">', '</div>'); ?>
							</div>

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("post_code");?></label>
								<input type="text" id="postcode" class="form-control" name="postcode" placeholder="<?php echo $this->lang->line("enter_post_code");?>" value="<?php echo set_value('postcode',isset($data['postcode']) ? $data['postcode'] : '');?>">
								<?php
								 echo form_error('postcode', '<div class="error">', '</div>'); ?>
							</div> 

							<div class="form-group other_add_field">
								<label><?php echo $this->lang->line("country");?></label>
								<?php 
								$options = array();
								$options[''] = '-- Select --';
								if(!empty($countries)){
									foreach ($countries as $country) {
										$country_id = $country['id'];
										$options[$country_id] = $country['name'];
									}
								}
								$attr = 'class="form-control country" data-live-search="true"';
								echo form_dropdown('country',$options,set_value('country',isset($data['country']) ? $data['country'] : ''),$attr); ?>
								<?php 
								echo form_error('country', '<div class="error">', '</div>'); ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line("close");?></button>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" value="1"><?php echo $this->lang->line("save");?></button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!-- Upload Attach SIC -->
		<script>
		$(document).ready(function() {
			$(document).on('click', '.changeDAddressModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_daddress_modal').val(order_id);
			});

			$(document).on('change', 'select[name="delivery_practice_id"]',function(){
				$(".country option").removeAttr("selected");
				$(".country").trigger("change");
				onchng();
			});

			$(document).on('submit', '#addDeliveryAddressForm', function(event) {
				event.preventDefault();
				var form = document.getElementById('addDeliveryAddressForm');
				var fdata = new FormData(form);
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('Orders/changeDeliveryAddress'); ?>",
					data: fdata,
					contentType: false,
					cache: false,
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					processData:false,
					success: function(result){
						$('#submit').attr('disabled', false);
						if (result == "error") {
							$('#dAddress_message').text("Error in change delivery Address, Please try again!");
						} else {
							$('#changeDAddressModal').modal('hide');
							location.reload();
						}
					}
				});
			});

			$(document).on('click', '.attachSICModal', function() {
				var order_id = $(this).data('order_id');
				$('#order_id_sic_modal').val(order_id);
			});

			$(document).on('submit', '#addSICDocumentForm', function(event) {
				event.preventDefault();
				var form = document.getElementById('addSICDocumentForm');
				var fdata = new FormData(form);
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('Orders/uploadSICDocument'); ?>",
					data: fdata,
					contentType: false,
					cache: false,
					beforeSend: function() {
						$('#submit').attr('disabled', 'disabled');
					},
					processData:false,
					success: function(result){
						$('#submit').attr('disabled', false);
						if (result == "error") {
							$('#sic_message').text("Error in upload SIC Document, Please try again!");
						} else {
							$('#attachSICModal').modal('hide');
							location.reload();
						}
					}
				});
			});

			$("#shipping_cost").keyup(function(){
				if($("#default_unit_price").val() != ''){
					var price = $("#default_unit_price").val();
					var scost = $(this).val();
					if(parseFloat(scost) > 0){
						var disc = parseFloat(price) + parseFloat(scost);
						$("#unit_price").attr('value',parseFloat(disc.toFixed(2)));
					}else{
						$("#unit_price").attr('value',parseFloat(price));
					}
				}
			});
		});

		function onchng(){
			let vet_user_id = $('select[name="delivery_practice_id"]').val();
			$.ajax({
				url: "<?php echo base_url('UsersDetails/get_practice_address'); ?>",
				type: 'POST',
				data: {
					'vet_user_id': vet_user_id,
				},
				dataType: "json",
				success: function(data) {
					$('#cover-spin').hide();
					$("#address1").val(data.address);
					$("#address2").val(data.address1);
					$("#address3").val(data.address2);
					$("#address4").val(data.address3);
					$("#town_city").val(data.town_city);
					$("#county").val(data.county);
					$("#country").val(data.country);
					$("#postcode").val(data.postcode);
					$(".country option[value=" + data.country + "]").attr("selected", "selected");
				}
			});
		}
		</script>
		<?php if( $userData['role']==2 || $userData['role']==5 || $userData['role']==6 || $userData['role']==7 ) { ?>
		<?php /* <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script> */ ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
		<script>
		$(document).ready(() => {
			var canvasDiv = document.getElementById('canvasDiv');
			var canvas = document.createElement('canvas');
			canvas.setAttribute('id', 'canvas');
			canvasDiv.appendChild(canvas);
			$("#canvas").attr('height', $("#canvasDiv").outerHeight());
			$("#canvas").attr('width', $("#canvasDiv").width());
			if (typeof G_vmlCanvasManager != 'undefined') {
				canvas = G_vmlCanvasManager.initElement(canvas);
			}
			
			context = canvas.getContext("2d");
			$('#canvas').mousedown(function(e) {
				var offset = $(this).offset()
				var mouseX = e.pageX - this.offsetLeft;
				var mouseY = e.pageY - this.offsetTop;

				paint = true;
				addClick(e.pageX - offset.left, e.pageY - offset.top);
				redraw();
			});

			$('#canvas').mousemove(function(e) {
				if (paint) {
					var offset = $(this).offset()
					//addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
					addClick(e.pageX - offset.left, e.pageY - offset.top, true);
					console.log(e.pageX, offset.left, e.pageY, offset.top);
					redraw();
				}
			});

			$('#canvas').mouseup(function(e) {
				paint = false;
			});

			$('#canvas').mouseleave(function(e) {
				paint = false;
			});

			var clickX = new Array();
			var clickY = new Array();
			var clickDrag = new Array();
			var paint;

			function addClick(x, y, dragging) {
				clickX.push(x);
				clickY.push(y);
				clickDrag.push(dragging);
			}

			$("#reset-btn").click(function() {
				context.clearRect(0, 0, window.innerWidth, window.innerWidth);
				clickX = [];
				clickY = [];
				clickDrag = [];
			});

			var drawing = false;
			var mousePos = {
				x: 0,
				y: 0
			};
			var lastPos = mousePos;

			canvas.addEventListener("touchstart", function(e) {
				mousePos = getTouchPos(canvas, e);
				var touch = e.touches[0];
				var mouseEvent = new MouseEvent("mousedown", {
					clientX: touch.clientX,
					clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
			}, false);


			canvas.addEventListener("touchend", function(e) {
				var mouseEvent = new MouseEvent("mouseup", {});
				canvas.dispatchEvent(mouseEvent);
			}, false);


			canvas.addEventListener("touchmove", function(e) {

				var touch = e.touches[0];
				var offset = $('#canvas').offset();
				var mouseEvent = new MouseEvent("mousemove", {
					clientX: touch.clientX,
					clientY: touch.clientY
				});
				canvas.dispatchEvent(mouseEvent);
			}, false);

			// Get the position of a touch relative to the canvas
			function getTouchPos(canvasDiv, touchEvent) {
				var rect = canvasDiv.getBoundingClientRect();
				return {
					x: touchEvent.touches[0].clientX - rect.left,
					y: touchEvent.touches[0].clientY - rect.top
				};
			}

			var elem = document.getElementById("canvas");
			var defaultPrevent = function(e) {
				e.preventDefault();
			}
			elem.addEventListener("touchstart", defaultPrevent);
			elem.addEventListener("touchmove", defaultPrevent);
			function redraw() {
				lastPos = mousePos;
				for (var i = 0; i < clickX.length; i++) {
					context.beginPath();
					if (clickDrag[i] && i) {
						context.moveTo(clickX[i - 1], clickY[i - 1]);
					} else {
						context.moveTo(clickX[i] - 1, clickY[i]);
					}
					context.lineTo(clickX[i], clickY[i]);
					context.closePath();
					context.stroke();
				}
			}

			//signature
			$(document).on('click','.signatureModal', function(){
				if($('#sic_document').val() != ""){
					var order_id = $(this).data('order_id'); 
					$('#order_id_modal').val(order_id);
				}else{
					$('#signatureModal').modal('hide');
					alert('Please upload SIC Document first!');
					return false;
				}
			});

			$(document).on('submit', '#signatureModalForm', function(event) {
				event.preventDefault();
				var mycanvas = document.getElementById('canvas');
				var img = mycanvas.toDataURL("image/png");
				anchor = $("#signature");
				anchor.val(img);
				$("#signatureForm").submit();
			});
			//signature
		});
		</script>
		<?php } ?>
	</body>
</html>