<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();

if($data['pax_cutoff_version'] == 1){
	$cutoffs = '30';
}else{
	$cutoffs = '28';
}

$reExpandedData = $this->OrdersModel->getRecordCepId($this->_data['data']['id']);
$productCodeSelection = 0;
if (!empty($reExpandedData)) {
	if($reExpandedData['product_code_selection'] == 56){
		$productCodeSelection = 1;
	}elseif($reExpandedData['product_code_selection'] == 57){
		$productCodeSelection = 2;
	}elseif($reExpandedData['product_code_selection'] == 58){
		$productCodeSelection = 3;
	}
}

$raptorData = $this->OrdersModel->getRaptorData($data['order_number']);
$getEAllergenParent = $this->AllergensModel->getEnvAllergenParentbyName($data['allergens']);
$envtotal = 0;
foreach ($getEAllergenParent as $apkey => $apvalue){
	$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $data['allergens']);
	foreach ($subAllergens as $skey => $svalue) {
		$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
		if(!empty($subVlu->raptor_code)){
			$raptrVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
			if(!empty($raptrVlu) && floor($raptrVlu->result_value) >= $cutoffs){
				$envtotal++;
			}
		}
	}
}

$getFAllergenParent = $this->AllergensModel->getFoodAllergenParentbyName($data['allergens']);
$foodtotal = 0;
foreach ($getFAllergenParent as $apkey => $apvalue){
	$subAllergens = $this->AllergensModel->get_pax_subAllergens_dropdown($apvalue['pax_parent_id'], $data['allergens']);
	foreach ($subAllergens as $skey => $svalue) {
		$subVlu = $this->OrdersModel->getsubAllergensCode($svalue['id']);
		if(!empty($subVlu->raptor_code)){
			$raptrfVlu = $this->OrdersModel->getRaptorValue($subVlu->raptor_code,$raptorData->result_id);
			if(!empty($raptrfVlu) && floor($raptrfVlu->result_value) >= $cutoffs){
				$foodtotal++;
			}
		}
	}
}
?>
			<link rel="stylesheet" href='<?php echo base_url("assets/dist/css/radio_box.css"); ?>' />
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
					<?php echo $this->lang->line("expand_type");?>
						<small><?php echo $this->lang->line("Control_Panel");?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo HOME_LINK_LABEL; ?></a></li>
						<li><a href="#"> <?php echo $this->lang->line("Orders_Management");?></a></li>
						<li class="active"><?php echo $this->lang->line("Orders");?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">
					<!--breadcrumb-->
					<?php $this->load->view("orders/breadcrumbs"); ?>
					<!--breadcrumb-->
					<div class="row">
						<!-- left column -->
						<div class="col-xs-12">
							<!-- general form elements -->
							<div class="box box-primary">
								<div class="box-header with-border">
									<a href="javascript:window.history.go(-1);" class="btn btn-primary ad-click-event"><i class="fa fa-long-arrow-left back-btn-cls" style="font-size:initial;"></i><?php echo $this->lang->line("back");?></a>
								</div><!-- /.box-header -->

								<!-- form start -->
								<?php echo form_open('', array('name'=>'orderType', 'id'=>'orderType')); ?>
									<?php
									if (!empty($this->_data['data']['is_expanded'])) {
										?>
										<input type="hidden" name="is_expanded" value="1" >
										<?php
									}
									?>
									<!--Order Type-->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="middle">
													<?php if($envtotal > 0){ ?>
													<label>
														<input type="radio" <?= ($productCodeSelection == 1) ? "checked" : "";  ?> name="expand_type" value="1"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("pax_env_scr_expanded");?></span>
														</div>
													</label>
													<?php } ?>
													<?php if($foodtotal > 0){ ?>
													<label>
														<input type="radio" <?= ($productCodeSelection == 2) ? "checked" : "";  ?> name="expand_type" value="2"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("pax_food_scr_expanded");?></span>
														</div>
													</label>
													<?php } ?>
													<?php if($envtotal > 0 && $foodtotal > 0){ ?>
													<label>
														<input type="radio" <?= ($productCodeSelection == 3) ? "checked" : "";  ?> name="expand_type" value="3"/>
														<div class="front-end box">
															<span><?php echo $this->lang->line("pax_env_food_scr_expanded");?></span>
														</div>
													</label>
													<?php } ?>
												</div>
											</div><!-- /.col -->
										</div><!-- /.row -->
									</div><!-- /.box-body -->
									<!--Order Type-->
									<div class="box-footer">
										<p class="pull-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line("next");?><i class="fa fa-long-arrow-right next-btn-cls" style="font-size:initial;"></i></button>
										</p>
									</div>
								<?php echo form_close(); ?>
							</div><!-- /.box -->
						</div><!--/.col (left) -->
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<?php $this->load->view("footer"); ?>  
		</div><!-- ./wrapper -->
		<?php $this->load->view("script"); ?>
	</body>
</html>
