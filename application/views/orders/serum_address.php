<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("header");
$userData = logged_in_user_data();
if($order_details['serum_type'] == 1){
	$serumTypes = 'PAX';
}else{
	$serumTypes = 'NEXTLAB';
}
?>
			<div class="content-wrapper">
				<section class="content-header">
					<h1><?php echo $this->lang->line('serum_test_requisition_form'); ?></h1>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<!-- Serum Test Address details -->
							<div class="box box-primary">
								<div class="box-header with-border" style="text-align: center;">
									<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<h2 class="box-title" style="font-size: 30px;"><?php echo $serumTypes; ?><?php echo $this->lang->line('serum_test'); ?> </h2>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
										<div class="col-sm-6 col-md-6 col-lg-6">
											<p style="font-size: 16px;margin: 0px;text-align: center;"><?php echo $this->lang->line('print_the_completed_form_and_send_it_together_with_the_serum_sample'); ?></p> 
											<p style="font-size: 16px;text-align: center;"><b><?php echo $this->lang->line('2ml_canine_feline_3ml_equine'); ?></b>,<?php echo $this->lang->line('in_the_pre_paid_postal_boxes_provided'); ?> </p>
											<div style="background-color:#376984;padding: 15px;border-radius: 15px;">
												<p style="font-size: 16px;text-align: center;padding: 10px;color: #FFF;">
													<img src="<?php echo base_url(); ?>assets/images/nextlab-logo.jpg" alt="NEXTMUNE Logo" style="max-height:100px; max-width:280px; border-radius:4px;">
												</p>
												<p style="font-size: 16px;margin: 0px;text-align: center;color: #FFF;">
												<?php echo $this->lang->line('if_you_do_not_have_a_pre_paid_postal_box_please_email'); ?> <b><?php echo $this->lang->line('contact_email'); ?></b>
												</p>
												<p style="font-size: 16px;margin: 0px;text-align: center;color: #FFF;">
												<?php echo $this->lang->line('or_phone_and_we_will_send_one_to_you_straight_away'); ?>
												</p>
												<p></p>
												<p style="font-size: 16px;margin: 0px;text-align: center;color: #FFF;">
													<b><?php echo $this->lang->line('uk_address'); ?>:</b><?php echo $this->lang->line('nextmune_laboratories_Ltd_unit_651_street_5'); ?> 
												</p>
												<p style="font-size: 16px;margin: 0px;text-align: center;color: #FFF;">
												<?php echo $this->lang->line('thorpe_arch_trading_estate_wetherby_LS23_7FZ'); ?>	
												</p>
												<p></p>
												<p style="font-size: 16px;margin: 0px;text-align: center;color: #FFF;">
													<b><?php echo $this->lang->line('phone'); ?>:</b> <?php echo $this->lang->line('01494_649979'); ?>
												</p>
											</div>
										</div>
										<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
									</div>
								</div>
								<div class="box-footer" style=" text-align:center;">
									<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<p><i><b><?php echo $this->lang->line('you_must_print_this_order_requisition_form_and_send_it_with_your_sample'); ?></b></i></p>
										<a class="btn btn-primary" target="_blank" href="<?php echo base_url('orders/print_form/'.$order_details['id']); ?>" title="Submit" id="btn-save"><?php echo $this->lang->line('print_requisition_form'); ?></a>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">&nbsp;</div>
								</div>
							</div>
							<!-- Serum Test Address details -->
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view("footer"); ?>
		</div>
		<?php $this->load->view("script"); ?>
		<script>
		function printPageArea(areaID){
			$("#print_content").show();
			var printContent = document.getElementById(areaID);
			var WinPrint = window.open('', '', 'width=900,height=650');
			WinPrint.document.write(printContent.outerHTML);
			WinPrint.document.close();
			WinPrint.focus();
			WinPrint.print();
			WinPrint.close();
			$("#print_content").hide();
		}
		</script>
	</body>
</html>