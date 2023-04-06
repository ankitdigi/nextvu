<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" style="width:100%;max-width:100%;background:url(<?php echo base_url(); ?>assets/images/pax-bg.png) center top no-repeat #ffffff;background-size:100%;padding:5px;">
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

			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding:0 30px;">
				<tbody>
					<tr>
						<td>
							<h4 style="margin:0; color:#2a5b74; font-size:24px;"><?php echo $ordeType;?></h4>
							<p style="margin:5px 0 0 0; color:#2a5b74; font-size:18px;"><?php echo $this->lang->line('faq_title'); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>

			<table width="100%">
				<tbody>
					<tr>
						<td style="padding:0 30px;">
							<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="48%">
								<tbody>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q1'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q1'); ?></p></td>
									</tr>
									<?php if($this->session->userdata('site_lang') != 'spanish' && $this->session->userdata('export_site_lang') != 'export_spanish'){ ?>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q2'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><img src="<?php echo base_url(); ?>assets/images/<?php echo $this->lang->line('pax_dosage_faq'); ?>" alt=""></td>
									</tr>
									<?php } ?>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q3'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q3'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q4'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q4'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q5'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q5'); ?></p></td>
									</tr>
								</tbody>
							</table>
							<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="4%"><tr><td height="20"></td></tr></table>
							<table class="" cellspacing="0" cellpadding="0" border="0" align="left" width="48%">
								<tbody>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q6'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q6'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q7'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q7'); ?> </p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q8'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q8'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q9'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_q9'); ?></p></td>
									</tr>
									
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q10'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_a10'); ?></p></td>
									</tr>
									<tr><td height="30"></td></tr>
									<tr>
										<td width="35" style="line-height:0;"><img src="<?php echo base_url(); ?>assets/images/question.png" alt=""></td>
										<td><h6 style="font-size:16px;margin:0;color: #2a5b74;font-weight: bold;"><?php echo $this->lang->line('positive_q11'); ?></h6></td>
									</tr>
									<tr>
										<td colspan="2"><p style="color:#333333; font-size:14px; line-height:21px; margin:10px 0 0 0;"><?php echo $this->lang->line('positive_faq_option5'); ?></p></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%"><tr><td height="20"></td></tr></table>
			<table width="100%">
				<?php
				/* $zonesIds = $this->OrdersModel->checkZones($order_details['id']);
				if(!empty($zonesIds) && in_array("6", $zonesIds)){
				?>
				<tr>
					<td>
						<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;">Please contact our veterinary support team by phone +31 320 783 100 or by email <a style="color:#203548;" href="mailto:info.eu@nextmune.com">info.eu@nextmune.com</a></p>
					</td>
				</tr>
				<?php }else{ ?>
				<tr>
					<td>
						<h6 style="color:#366784; margin:0 0 15px 0; padding:0; font-size:18px;"><?php echo $this->lang->line('positive_q12'); ?></h6>
						<p style="color:#273945; margin:0 0 12px 0; padding:0; font-size:16px; line-height:22px;"><?php echo $this->lang->line('positive_a12'); ?></p>
					</td>
				</tr>
				<?php } */ ?>
			</table>
			<table width="100%"><tr><td height="40"></td></tr></table>
		</td>
	</tr>
</table>