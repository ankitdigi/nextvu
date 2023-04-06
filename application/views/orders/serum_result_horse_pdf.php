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

$serumResults = $this->OrdersModel->getSerumTestResult($serumType->result_id,$serumType->type_id);
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
		.bargraph li{position:relative;height:21px;margin-bottom:6px;transition:width 2s;-webkit-transition:width 2s;background:#abd084}
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
										<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;">IgE Level</th>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px;"></td>
										<td align="right" style="padding:5px 15px 5px 10px;"></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Meadow Grass</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Meadow Fescue</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Orchard Grass</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Perennial Rye Grass</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Redtop</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Sweet Vernal</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Timothy Grass</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Oils Seed Rape</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Dandelion</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Dock</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Lamb's Quarter</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Mugwort</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Nettle</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Daisy</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Plantain</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Ragweed</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Alder</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Ash</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Beech</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Birch</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">34</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Hazel</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Oak</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Privet</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Willow</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Culicoides</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Horse Fly</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">House Fly</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Mosquito</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">A.siro</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">L.destructor</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">T.putrescentiae</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">D.farinae</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">D.pteronyssinus</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Alternaria Alternata</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Aspergillus Mix</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;">Penicillium Mix</td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;">0</td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"></td>
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
														<ul class="bargraph" style="margin-top:15px;">
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
															<li style="width:34%;" class=""><span></span></li>
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
															<li style="width:34%;" class=""><span></span></li>
															<li style="width:0%;" class=""><span></span></li>
														</ul>
													</td>
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
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;">here there is a suspicion of atopic dermatitis elevated levels of mould-specific IgE may not be clinically relevant unless they are very high. An Additional borderline parameter is used for this group.</p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;">Results are reported as an AU (Arbitrary Units) value between 0 and 100.</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>