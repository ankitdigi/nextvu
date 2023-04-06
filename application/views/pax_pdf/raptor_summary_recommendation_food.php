<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0px 40px;">
	<tr>
		<td>
			<h4 style="margin:0; color:#2a5b74; font-size:20px;"><?php echo $this->lang->line('summary'); ?></h4>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="5"></td></tr></table>
<div style="width: 88%;background-color: #f5fafb;border-radius: 20px;padding: 10px;margin: 0px 40px;font-size:12px;">
	<?php echo !empty($order_details['interpretation_food'])?nl2br($order_details['interpretation_food']):$dummytext; ?>
</div>
<table width="100%"><tr><td height="10"></td></tr></table>
<table width="100%" style="width:100%;margin: 0px 40px;">
	<tr>
		<td>
			<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:16px;"><?php echo $this->lang->line('recommendation_q1_food'); ?></h6>
			<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:14px; line-height:20px;"><?php echo $this->lang->line('recommendation_q2'); ?></p>
			<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:14px; line-height:20px;"><?php echo $this->lang->line('recommendation_q3'); ?></p>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="30"></td></tr></table>
<table width="100%" style="width:100%;margin: 0px 40px;">
	<tr>
		<td>
			<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
			<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('contact1'); ?> <a style="color:currentcolor" href="mailto:<?php echo $this->lang->line('contact_email'); ?>"><?php echo $this->lang->line('contact_email'); ?></a></p>
		</td>
	</tr>
</table>
<table width="100%"><tr><td height="30"></td></tr></table>
