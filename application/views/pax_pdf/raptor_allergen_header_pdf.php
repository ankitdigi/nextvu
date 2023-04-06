<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:10px 40px;padding:0px;border:none;">
	<tr>
		<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?> </b> <?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $account_ref; ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
	</tr>
</table>
<div style="background-color: #9acfdb;color:#ffffff; font-size:14px; border-radius:30px;margin: 0px 30px;width: 90%;padding: 0px;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0px;border:none;">
		<tr>
			<th width="21%" align="left" style="color:#ffffff; font-size:14px; padding:10px;"><?php echo $this->lang->line('Common_Name'); ?></th>
			<th width="21%" align="left" style="color:#ffffff; font-size:14px; padding:10px;"><i style="font-weight:normal;"><?php echo $this->lang->line('Scientific_name'); ?></i></th>
			<th width="15%" align="left" style="color:#ffffff; font-size:14px; padding:10px;"><?php echo $this->lang->line('EM_Allergen'); ?></th>
			<th width="22%" align="left" style="color:#ffffff; font-size:14px; padding:10px;"><?php echo $this->lang->line('function'); ?></th>
			<th width="15%" align="right" style="color:#ffffff; font-size:14px; padding:10px;"><?php echo $this->lang->line('ng_mL'); ?></th>
		</tr>
	</table>
</div>
