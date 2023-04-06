<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:10px 40px;padding:0px;border:none;">
	<tr>
		<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?> </b> <?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $account_ref; ?></th>
		<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
	</tr>
</table>
<table width="100%" style="margin:0 40px;"><tr><td><h5 style="text-transform:uppercase; font-size:16px; margin:0;"><?php echo $this->lang->line('canine_veterinary_diets'); ?></h5></td></tr></table>
<table width="100%" style="margin:0 40px;">
	<tr>
		<td valign="middle" width="20"><img src="<?php echo base_url(); ?>assets/images/blank.png" alt="" style="width: 18px;" /></td>
		<td valign="middle" style="line-height:0; font-size:11px;"> <span><?php echo $this->lang->line('ingredient_present'); ?><span></td>
	</tr>
	<?php if($this->session->userdata('site_lang') != 'italian'){ ?>
	<tr>
		<td valign="middle" width="20"><img src="<?php echo base_url(); ?>assets/images/h.png" alt="" style="width: 18px;" /></td>
		<td valign="middle" style="line-height:0; font-size:11px;"> <span><?php echo $this->lang->line('hydrolysed'); ?><span></td>
	</tr>
	<tr>
		<td valign="middle" width="20"><img src="<?php echo base_url(); ?>assets/images/s.png" alt="" style="width: 18px;" /></td>
		<td valign="middle" style="line-height:0; font-size:11px;"> <span><?php echo $this->lang->line('starch_only'); ?><span></td>
	</tr>
	<?php } ?>
	<?php if($this->session->userdata('export_site_lang') == 'export_spanish' || $this->session->userdata('site_lang') == 'spanish'){ ?>
	<tr>
		<td valign="middle" width="20"><img src="<?php echo base_url(); ?>assets/images/p.png" alt="" style="width: 18px;" /></td>
		<td valign="middle" style="line-height:0; font-size:11px;"> <span><?php echo $this->lang->line('purified_oil'); ?><span></td>
	</tr>
	<?php } ?>
</table>