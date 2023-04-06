<?php
if($order_details['vet_user_id']>0){
	$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
	$refDatas = array_column($refDatas, 'column_field', 'column_name');
	$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
	$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
	$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
	$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
	$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
	$fulladdress = $add_1.$add_2.$add_3.$city.$postcode;
}else{
	$fulladdress = '';
}

if(!empty($order_details['product_code_selection'])){
	$this->db->select('name');
	$this->db->from('ci_price');
	$this->db->where('id', $order_details['product_code_selection']);
	$ordeType = $this->db->get()->row()->name;
}else{
	$ordeType = 'Serum Testing';
}
?>
<!DOCTYPE html>
<html class="js">
	<head>
		<title>Serum Test Result</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		*{font-family:'Open Sans',sans-serif}
		.header th{text-align:left}
		.bargraph{list-style:none;width:300px;position:relative;margin:0;padding:0}
		.bargraph li{position:relative;height:16px;margin-bottom:6px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
		.bargraph li span{display:block}
		</style>
	</head>
	<body bgcolor="#cccccc">
		<table class="main_container" cellspacing="0" cellpadding="0" border="0" align="center" width="1040px" style="width:100%; max-width:1040px; background:#ffffff;">
			<tr>
				<td>
					<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; background:url(<?php echo base_url("/assets/images/next-header.jpg"); ?>) left center no-repeat; background-size:cover;">
						<tr>
							<td valign="middle" width="430" style="padding:60px 50px 60px 60px;">
								<img src="<?php echo base_url("/assets/images/nextlab-logo.jpg"); ?>" alt="Logo" style="max-height:100px; max-width:280px; border-radius:4px;" />
								<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;">Serum Test results</h5>
							</td>
							<td valign="middle">
								<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
									<tr>
										<th style="color:#1e3743;">Owner name:</th>
										<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].''.$order_details['po_last'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Animal Name:</th>
										<td style="color:#000000;"><?php echo $order_details['pet_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Species:</th>
										<td style="color:#000000;"><?php echo $order_details['species_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Veterinary surgeon:</th>
										<td style="color:#000000;"><?php echo $order_details['veterinary_surgeon'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Veterinary practice:</th>
										<td style="color:#000000;"><?php echo $order_details['practice_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Practice details:</th>
										<td style="color:#000000;"><?php echo $fulladdress;?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Phone:</th>
										<td style="color:#000000;"><?php echo $order_details['phone_number'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Email:</th>
										<td style="color:#000000;"><?php echo $order_details['email'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Test type:</th>
										<td style="color:#000000;"><?php echo $ordeType;?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Date tested:</th>
										<td style="color:#000000;"><?php echo date('d/m/Y', strtotime($order_details['order_date']));?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Laboratory number:</th>
										<td style="color:#000000;"><?php echo $order_details['lab_order_number'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;">Order number:</th>
										<td style="color:#000000;"><?php echo $order_details['order_number'];?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>			
					<table width="100%"><tr><td height="30"></td></tr></table>
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" border="0" width="30%" align="left">
									<tr>
										<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;">Allergen</th>
										<th align="right" style="background:#326883;font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
										<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"></th>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px;"></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Beef</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">5</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">5</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Chicken</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Lamb</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Pork</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Turkey</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Venison</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Duck</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Rabbit</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">White fish</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Sal man</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Milk</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Egg</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Soyabean</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Corn</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Potato</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Wheat</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Rice</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Oats</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;">Barley</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgE</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 0px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">IgG</td>
										<td align="right" style="padding:5px 15px 0px 10px; font-size:12px;">0</td>
									</tr>
									<tr>
										<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;">All IgE antibody binding to CCDs has been prevented with our CCD blocker to help eliminate false positives and provide a more reliable test result.</th>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0" border="0" width="70%" align="left">
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" width="100%" border="0">
												<tr>
													<th height="35" width="30%"></th>
													<th width="10%" style="color:#326883; font-size:15px;">5</th>
													<th width="10%" style="color:#326883; font-size:15px;">10</th>
													<th></th>
												</tr>
												<tr bgcolor="#f2fafa" style="background-image:url(<?php echo base_url("/assets/images/watermark.png"); ?>); background-position:center center; background-repeat:no-repeat;">
													<td>
														<ul class="bargraph" style="margin-top:10px;">
															<li style="width:5%;" class=""><span></span></li>
															<li style="width:5%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
														</ul>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td style="background:url(<?php echo base_url("/assets/images/dot.jpg"); ?>) center center repeat-y;"></td>
													<td></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="20"></td></tr></table>
					<table class="" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width:100%; padding:0 30px;">
						<tr>
							<td width="33.33%" style="background:#eaf6f7; border-radius:10px 0 0 10px; padding:20px 25px;">
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>< 5</strong> </p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of less than 5 should be considered questionable and only be included if the allergens are found in the pet’s environment and they relate to the clinical history.</p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>5-10</strong></p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores in this range should be considered significant if the allergens are found in the pet’s environment and they relate to the clinical history</p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> 10</strong></p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;">Scores of 10 or above are unusually high. These allergens are significant especially if they are found in the pet’s environment and relate to the clinical history.</p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">The magnitude of the signal does not necessarily correlate with the severity of the disease but does reflect the pet’s immune response to allergens.</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>