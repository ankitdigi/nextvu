<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
if(isset($this->zones) && !empty($this->zones)){
	$zoneby = explode(",",$this->zones);
}else{
	$zoneby = array();
}
?>
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/radio_box.css"); ?>' />
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("Order_Type");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>

				<section class="content">
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<div class="row">
						<div class="col-xs-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
								</div>
								<?php echo form_open('', array('name'=>'orderType', 'id'=>'orderType')); ?>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-4 col-md-4 col-lg-4">
												<div class="form-group">   
													<label><?php echo $this->lang->line("Order_Type");?> <span class="required">*</span></label>
													<select id="order_type" name="order_type" class="form-control" required="required">
														<option value="">-- Select --</option>
														<option value="1" <?php if(isset($data['order_type']) && $data['order_type']=='1'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line("Immunotherapy");?></option>
														<option value="2" <?php echo (isset($data['order_type']) && $data['order_type']=='2') ? 'selected="selected"' : "" ;?>><?php echo $this->lang->line("serum_test");?></option>
														<option value="3" <?php echo (isset($data['order_type']) && $data['order_type']=='3') ? 'selected="selected"' : "" ;?>><?php echo $this->lang->line("skin_test");?></option>
													</select>
													<?php echo form_error('order_type', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group serumType" style="display:none">
													<label><?php echo $this->lang->line("serum_test_type");?> <span class="required">*</span></label>
													<select id="serum_type" name="serum_type" class="form-control">
														<option value="">-- Select --</option>
														<option value="1" <?php if(isset($data['serum_type']) && $data['serum_type']=='1'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line("pax");?></option>
														<?php if(empty($zoneby) || in_array("1", $zoneby)){ ?>
														<option value="2" <?php echo (isset($data['serum_type']) && $data['serum_type']=='2') ? 'selected="selected"' : "" ;?>><?php echo $this->lang->line("nextLab");?></option>
														<?php } ?>
													</select>
													<?php echo form_error('serum_type', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group Species" style="display:none">
													<label><?php echo $this->lang->line("Species");?> <span class="required">*</span></label>
													<select id="species_selection" name="species_selection" class="form-control">
														<option value="">-- Select --</option>
														<option value="1" <?php if(isset($data['species_selection']) && $data['species_selection']=='1'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line("dog");?></option>
														<option value="3" <?php if(isset($data['species_selection']) && $data['species_selection']=='3'){ echo 'selected="selected"'; } ?>><?php echo $this->lang->line("cat");?></option>
														<option value="2" <?php echo (isset($data['species_selection']) && $data['species_selection']=='2') ? 'selected="selected"' : "" ;?>><?php echo $this->lang->line("horse");?></option>
													</select>
													<?php echo form_error('species_selection', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group Screening" style="display:none">
													<label><?php echo $this->lang->line("panal");?>  <span class="required">*</span></label>
													<select id="screening" name="screening" class="form-control">
														<option value="">-- Select --</option>
														<?php
														if(!empty($screenings)){ 
															foreach($screenings as $srow){
																if(in_array("1", $zoneby)){
																	if(!preg_match('/\bExpanded\b/', $srow['name']) && !preg_match('/\bPAX Environmental Screening\b/', $srow['name']) && !preg_match('/\bPAX Food Screening\b/', $srow['name'])){
																		if(isset($data['product_code_selection']) && $data['product_code_selection'] == $srow['id']){
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'" selected="selected">'.$srow['name'].'</option>';
																		}else{
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'">'.$srow['name'].'</option>';
																		}
																	}
																}elseif(isset($this->zones) && !empty($this->zones) && $this->zones == '5'){
																	if(!preg_match('/\bExpanded\b/', $srow['name']) && !preg_match('/\bScreening\b/', $srow['name'])){
																		if(isset($data['product_code_selection']) && $data['product_code_selection'] == $srow['id']){
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'" selected="selected">'.$srow['name'].'</option>';
																		}else{
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'">'.$srow['name'].'</option>';
																		}
																	}
																}else{
																	if(!preg_match('/\bExpanded\b/', $srow['name'])){
																		if(isset($data['product_code_selection']) && $data['product_code_selection'] == $srow['id']){
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'" selected="selected">'.$srow['name'].'</option>';
																		}else{
																			echo '<option value="'.$srow['id'].'" data-ptype="'.$srow['display_order'].'">'.$srow['name'].'</option>';
																		}
																	}
																}
															}
														}
														?>
													</select>
													<?php echo form_error('screening', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group pcSelection" style="display:none">
													<label><?php echo $this->lang->line("panal");?> <span class="required">*</span></label>
													<select id="product_code_selection" name="product_code_selection" class="form-control">
														<option value="">-- Select --</option>
													</select>
													<?php echo form_error('product_code_selection', '<div class="error">', '</div>'); ?>
												</div>
												<div class="form-group plcSelection" style="display:none">
													<label><?php echo $this->lang->line("practice_lab");?> <span class="required">*</span></label>
													<select id="plc_selection" name="plc_selection" class="form-control">
														<option value="">-- Select --</option>
														<option value="1" <?php if(isset($data['plc_selection']) && $data['plc_selection']=='1'){echo 'selected="selected"';} ?>><?php echo $this->lang->line("practice");?></option>
														<option value="2" <?php echo (isset($data['plc_selection']) && $data['plc_selection']=='2') ? 'selected="selected"' : "" ;?>><?php echo $this->lang->line("lab");?></option>
													</select>
													<?php echo form_error('plc_selection', '<div class="error">', '</div>'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
										<input type="hidden" id="pax_type" name="pax_type" value="<?php echo isset($data['screening']) ? $data['screening'] : ''; ?>"/>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>
		</div>
		<?php $this->load->view("script"); ?>
		<?php if($this->session->userdata('lab_order_number') != ''){ ?>
		<script>
		$(document).ready(function() {
			setTimeout(function(){
				$('#order_type').val('2').trigger('change');
				$('#serum_type').val('1').trigger('change');
			}, 800);
		});
		</script>
		<?php } ?>
		<script>
		$(document).ready(function() {
			$('#orderType').parsley();
			$('select[id="order_type"]').on('change', function() {
				var slctedId = $(this).val();
				if(slctedId != '' && slctedId == 1){
					$('.plcSelection').show();
					$('#plc_selection').attr('required','required');
					$('.Species').hide();
					$('#species_selection').removeAttr('required');
					$('.serumType').hide();
					$('#serum_type').removeAttr('required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.Screening').hide();
					$('#screening').removeAttr('required');
				} else if(slctedId != '' && slctedId == 2){
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
					$('.serumType').show();
					$('#serum_type').attr('required','required');
					$('.Screening').hide();
					$('#screening').removeAttr('required');
				}else if(slctedId != '' && slctedId == 3){
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
					$('.Species').hide();
					$('#species_selection').removeAttr('required');
					$('.serumType').hide();
					$('#serum_type').removeAttr('required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.Screening').hide();
					$('#screening').removeAttr('required');
				}else{
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
					$('.Species').hide();
					$('#species_selection').removeAttr('required');
					$('.serumType').hide();
					$('#serum_type').removeAttr('required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.Screening').hide();
					$('#screening').removeAttr('required');
				}
			});

			$('select[id="serum_type"]').on('change', function() {
				var slctedtId = $(this).val();
				if(slctedtId != '' && slctedtId == 1){
					$('.Screening').show();
					$('#screening').attr('required','required');
					$('.plcSelection').show();
					$('#plc_selection').attr('required','required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.Species').hide();
					$('#species_selection').removeAttr('required');
				} else if(slctedtId != '' && slctedtId == 2){
					$('.Species').show();
					$('#species_selection').attr('required','required');
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
					$('.Screening').hide();
					$('#screening').removeAttr('required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
				}else{
					$('.Screening').hide();
					$('#screening').removeAttr('required');
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.Species').hide();
					$('#species_selection').removeAttr('required');
				}
			});

			$('select[id="species_selection"]').on('change', function() {
				var slctedsId = $(this).val();
				if(slctedsId != '' && slctedsId == 1){
					$.ajax({
						url:      "<?php echo base_url('orders/getProductCode'); ?>",
						type:     'POST',
						data:     {'species_selection':slctedsId},
						dataType: "json",
						success:  function (data) {
							$('.pcSelection').show();
							$('#product_code_selection').attr('required','required');
							$('.plcSelection').show();
							$('#plc_selection').attr('required','required');
							$('select[name="product_code_selection"]').empty();
							$('select[name="product_code_selection"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var pName = value.name;
								if(pName.match(/Expansion/) != "Expansion"){
									$('select[name="product_code_selection"]').append('<option value="'+value.id+'">'+value.name+'</option>');
								}
							});
						}
					});
				} else if(slctedsId != '' && slctedsId == 2){
					$.ajax({
						url:      "<?php echo base_url('orders/getProductCode'); ?>",
						type:     'POST',
						data:     {'species_selection':slctedsId},
						dataType: "json",
						success:  function (data) {
							$('.pcSelection').show();
							$('#product_code_selection').attr('required','required');
							$('.plcSelection').show();
							$('#plc_selection').attr('required','required');
							$('select[name="product_code_selection"]').empty();
							$('select[name="product_code_selection"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var pName = value.name;
								if(pName.match(/Expansion/) != "Expansion"){
									$('select[name="product_code_selection"]').append('<option value="'+value.id+'">'+value.name+'</option>');
								}
							});
						}
					});
				} else if(slctedsId != '' && slctedsId == 3){
					$.ajax({
						url:      "<?php echo base_url('orders/getProductCode'); ?>",
						type:     'POST',
						data:     {'species_selection':slctedsId},
						dataType: "json",
						success:  function (data) {
							$('.pcSelection').show();
							$('#product_code_selection').attr('required','required');
							$('.plcSelection').show();
							$('#plc_selection').attr('required','required');
							$('select[name="product_code_selection"]').empty();
							$('select[name="product_code_selection"]').append('<option value="">-- Select --</option>');
							$.each(data, function(key, value) {
								var pName = value.name;
								if(pName.match(/Expansion/) != "Expansion"){
									$('select[name="product_code_selection"]').append('<option value="'+value.id+'">'+value.name+'</option>');
								}
							});
						}
					});
				}else{
					$('.pcSelection').hide();
					$('#product_code_selection').removeAttr('required');
					$('.plcSelection').hide();
					$('#plc_selection').removeAttr('required');
				}
			});
		
			$('select[id="screening"]').on('change', function() {
				var paxID = $('select[id="screening"] :selected').data("ptype");
				if(paxID > 0){
					$('#pax_type').attr("value",paxID);
					$('#pax_type').val(paxID);
				}
			});
		});
		</script>
	</body>
</html>