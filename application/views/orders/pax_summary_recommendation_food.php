<table width="100%"><tr><td height="20"></td></tr></table>
<table class="main_container optiontbl" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
	<tr>
		<td style="padding: 5px;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<th valign="bottom" align="left" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><img src="<?php echo base_url(); ?>assets/images/pax-logo.png" alt="" style="max-height:40px;"></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $order_details['pet_name']; ?></b><?php echo $order_details['pet_owner_name'].' '.$order_details['po_last']; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('customer'); ?>:</b> <?php echo $account_ref; ?></th>
					<th valign="bottom" style="padding:0 0 5px 0; border-bottom:2px solid #3a6a86; font-weight:400;"><b><?php echo $this->lang->line('test'); ?>:</b> <?php echo $order_details['order_number']; ?></th>
				</tr>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="">
				<tr>
					<td>
						<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $this->lang->line('summary'); ?></h4>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="3"></td></tr></table>

			<table width="100%">
				<tr>
					<td>
						<textarea class="form-control treatment_comment" name="treatment_comment" rows="15" style="background:#f2f5f8;border-radius:10px;" readonly><?php echo !empty($order_details['interpretation_food'])?$order_details['interpretation_food']:$dummyFtext; ?></textarea>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="30"></td></tr></table>
			<table width="100%">
				<tr>
					<td>
						<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('recommendation_q1_food'); ?></h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('recommendation_q2'); ?></p>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('recommendation_q3'); ?></p>
					</td>
				</tr>
			</table>
			<table width="100%"><tr><td height="50"></td></tr></table>
			<table width="100%">
				<tr>
					<td>
						<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('contact1'); ?> <a style="color:currentcolor" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"><?php echo $this->lang->line('contact_email'); ?></a>.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="20"></td></tr></table>