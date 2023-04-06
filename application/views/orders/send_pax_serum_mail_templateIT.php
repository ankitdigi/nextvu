<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $serumTypes; ?> <?php echo $this->lang->line('results_from_nextVu'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<style type="text/css">
		body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}
		img{-ms-interpolation-mode:bicubic;border:0;height:auto;line-height:100%;outline:none;text-decoration:none}
		table{border-collapse:collapse!important}
		body{height:100%!important;margin:0!important;padding:0!important;width:100%!important}
		a[x-apple-data-detectors]{color:inherit!important;text-decoration:none!important;font-size:inherit!important; font-family:inherit!important;font-weight:inherit!important;line-height:inherit!important}
		@media screen and (max-width: 480px) {
		.mobile-hide{display:none!important}
		.mobile-center{text-align:center!important}
		}
		div[style*="margin: 16px 0;"]{margin:0!important}
		.align_class{padding-left:2.7em}
		</style>
	</head>
	<body style="margin: 0 !important; padding: 0 !important; background-color: #fff;" bgcolor="#fff">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
						<tr>
							<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
								<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
									<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px;font-weight: 800;line-height:48px;text-align: center;" class="mobile-center">
												<img class="logo-img" src="<?php echo base_url("/assets/images/Nextmune_Email_Logo.png"); ?>" alt="NextVu" style="height: 41px !important;max-width:180px !important;width:180px !important;">
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<?php
						$petOWFName = !empty($order_details['pet_owner_name'])?$order_details['pet_owner_name']:' ';
						$petOWLName = !empty($order_details['po_last'])?$order_details['po_last']:'';
						$petownerName = $petOWFName.' '.$petOWLName;
						?>
						<tr>
							<td align="center" style="padding: 0px 35px 10px 35px; background-color: #ffffff;" bgcolor="#ffffff">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 20px;">
											<h2 style="font-size:24px; font-weight: 800; line-height:28px; color: #333333; margin: 0;"><?php echo $this->lang->line('pax_result_title1'); ?> <?php echo $order_details['pet_name']; ?> <?php echo $petownerName; ?> <?php echo $this->lang->line('pax_result_title2'); ?></h2>
										</td>
									</tr>
									<tr>
										<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height:20px; padding-top: 10px;">
											<p style="font-size: 18px; font-weight: 400; line-height: 20px; color: #777777;">
											<?php echo $this->lang->line('pax_result_attached1'); ?> <?php echo $order_details['pet_name']; ?> <?php echo $petownerName; ?>
											</p>
										</td>
									</tr>
									<tr>
										<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
											<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;margin: 0px;"><?php echo $this->lang->line('pax_result_attached2'); ?></p>
										</td>
									</tr>
									<tr>
										<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
											<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;margin: 0px;"><?php echo $this->lang->line('nextmune_offers'); ?></p>
										</td>
									</tr>
									<tr>
										<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height:20px; padding-top: 10px;">
											<p style="font-size: 16px; font-weight: 400; line-height: 20px; color: #777777;margin: 0px;"><?php echo $this->lang->line('coming_soon'); ?></p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top" style="font-size:0; padding:10px 35px" bgcolor="#3c8dbc">
								<div style="display:inline-block; max-width:55%; min-width:100px; vertical-align:top; width:100%;">
									<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;font-size:32px;font-weight: 800;line-height:42px;color: #fff;text-align: center;" class="mobile-center">
													<?php echo $this->lang->line('thank_you'); ?><br>
													<?php echo $this->lang->line('email_team'); ?>.
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>