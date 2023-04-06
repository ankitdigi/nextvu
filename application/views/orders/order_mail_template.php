<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<style type="text/css">
		body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}
		img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
		body{height:100%!important;margin:0!important;padding:0!important;width:100%!important}
		a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important;font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
		@media screen and (max-width: 480px) {
		.mobile-hide{display:none!important}
		.mobile-center{text-align:center!important}
		}
		div[style*="margin: 16px 0;"]{margin:0!important}
		.align_class{padding-left:2.7em}
		.d_table tbody tr{height:18px}
		.d_table tbody th{width:100%;height:35px;background-color:#366784;text-align:center}
		.d_table tbody th span{color:#fff;font-size:large}
		.d_table tbody tr:nth-child(odd){background-color:#9acfdb}
		.d_table tbody tr td{width:100%;height:15px}
		.d_table tbody tr td p{margin:0;font-size:15px}
		.d_table tbody tr td p:last-child{font-size:smaller}
		.d_table ul{margin:0}
		.d_table ul li{font-size:smaller}
		</style>
	</head>
	<body style="margin: 0 !important; padding: 0 !important; background-color: #fff; font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;" bgcolor="#fff">
		<table align="center" border="0" cellpadding="7" cellspacing="5" width="100%" style="max-width:600px;" class="d_table">
			<tbody>
				<tr style="background-color: #fff;">
					<td>
						<img class="logo-img" src='<?php echo base_url("/assets/images/Nextmune_H-Logo_CMYK.png"); ?>' alt="NextVu" style="height: 41px;width: 210px;">
					</td>
				</tr>
				<tr>
					<th><span>	<?php echo $this->lang->line("artuvetrin");?>&reg; 
					<?php if($order_type == '3'){?>
						<?php echo $this->lang->line("skin_test_order");?>
					<?php }else{?>
						<?php echo $this->lang->line("therapy_order_form");?>
					<?php } ?>
					</span></th>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $account_number_label; ?></strong></p>
						<p><?php echo $client_id; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong>	<?php echo $this->lang->line("order_reference");?></strong></p>
						<p><?php  
						if($plc_selection=='1'){
							echo $order_number;
						}else{
							echo $account_ref;
						}
						?></p>
					</td>
				</tr>
				<tr>
					<td>
						<?php if($lab_order == 'Lab'){?>
							<p><strong>	<?php echo $this->lang->line("ordered_by_name");?></strong></p>
						<?php }else{?>
							<p><strong>	<?php echo $this->lang->line("veterinary_surgeon");?>&rsquo;	<?php echo $this->lang->line("s_name");?></strong></p>
						<?php } ?>
						<p><?php echo $your_name; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<?php if($lab_order == 'Lab'){?>
							<p><strong>	<?php echo $this->lang->line("lab_email");?></strong></p>
						<?php }else{?>
							<p><strong>	<?php echo $this->lang->line("veterinary_surgeon");?>&rsquo;	<?php echo $this->lang->line("s_e_mail");?></strong></p>
						<?php } ?>
						
						<p><?php echo $your_email; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<?php if($lab_order == 'Lab'){?>
							<p><strong>	<?php echo $this->lang->line("lab_phone_number");?></strong></p>
						<?php }else{?>
							<p><strong>	<?php echo $this->lang->line("practice_phone_number");?></strong></p>
						<?php } ?>
						<p><?php echo $your_number; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<?php if($lab_order == 'Lab'){?>
							<p><strong><?php echo $this->lang->line("lab_name");?></strong></p>
						<?php }else{?>
							<p><strong><?php echo $this->lang->line("practice_name");?></strong></p>
						<?php } ?>
						<p><?php echo $clinic_name; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Address_1");?></strong></p>
						<p><?php echo $display_address; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Address_2");?></strong></p>
						<p><?php echo $display_address_1; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Address_3");?></strong></p>
						<p><?php echo $display_address_2; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Address_4");?></strong></p>
						<p><?php echo $display_address_3; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("town");?></strong></p>
						<p><?php echo $display_address_town_city; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("postal_code");?></strong></p>
						<p><?php echo $postal_code; ?></p>
						<?php if($lab_order == 'Lab'){?>
						<?php }else{?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("country");?></strong></p>
						<p><?php echo $country; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("order_sent_to_account_ref");?></strong></p>
						<p><?php echo $send_to_account_ref; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("order_is_to_be_sent_to");?></strong></p>
						<p><?php echo $order_sent_to; ?></p>
					</td>
				</tr>
				<tr>
					<td>
					<?php if($lab_order == 'Lab'){?>
						<p><strong><?php echo $this->lang->line("invoice_to_lab");?></strong></p>
						<?php }else{ ?>
							<p><strong><?php echo $this->lang->line("invoice_to_be_sent_to");?></strong></p>
						<?php } ?>	
						<p><?php echo $invoice_sent_to; ?></p>
					</td>
				</tr>
				<?php if($order_type != '3'){?>
				<tr>
					<th><span><?php echo $this->lang->line("pet_and_pet_owner_details");?></span></th>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("pet_owner");?>&rsquo;<?php echo $this->lang->line("s_last_name");?></strong></p>
						<p><?php echo $po_first.' '.$po_last; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Animal_Name");?></strong></p>
						<p><?php echo $animal_name; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("Species");?></strong></p>
						<p><?php echo $species; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $this->lang->line("is_this_order_for_an_initial_or_maintenance_treatment");?></strong></p>
						<p><?php echo $treatment; ?></p>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<th><span><?php echo $this->lang->line("Choose_allergens");?></span></th>
				</tr>
				<?php echo $allergens; ?>
				<tr>
					<th><span><?php echo $this->lang->line("total_allergens");?></span></th>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $total_allergens; ?></strong></p>
					</td>
				</tr>
				<tr>
					<th><span><?php echo $this->lang->line("practice_comments");?></span></th>
				</tr>
				<tr>
					<td>
						<p><strong><?php echo $practice_lab_comment; ?></strong></p>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
