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
		<title><?php echo $this->lang->line('serum_test_result'); ?></title>
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
								<h5 style="margin:30px 0 0 0; font-weight:700; font-size:28px; color:#ffffff;"><?php echo $this->lang->line('serum_test_results_2'); ?></h5>
							</td>
							<td valign="middle">
								<table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size:13px; line-height:20px;">
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Owner_name'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['pet_owner_name'].''.$order_details['po_last'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Animal_Name'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['pet_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Species'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['species_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('veterinary_surgeon'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['veterinary_surgeon'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['practice_name'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('practice_details'); ?>:</th>
										<td style="color:#000000;"><?php echo $fulladdress;?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('phone'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['phone_number'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('email'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['email'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Test_type'); ?>:</th>
										<td style="color:#000000;"><?php echo $ordeType;?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('Date_tested'); ?>:</th>
										<td style="color:#000000;"><?php echo date('d/m/Y', strtotime($order_details['order_date']));?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('laboratory_number'); ?>:</th>
										<td style="color:#000000;"><?php echo $order_details['lab_order_number'];?></td>
									</tr>
									<tr>
										<th style="color:#1e3743;"><?php echo $this->lang->line('order_number'); ?>:</th>
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
										<th align="left" style="background:#326883; border-radius:10px 0 0 0; font-size:14px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('Allergen'); ?></th>
										<th align="right" style="background:#326883; border-radius:0 10px 0 0; font-size:13px; font-weight:400; color:#ffffff; padding:8px 15px 8px 10px;"><?php echo $this->lang->line('ige_level'); ?></th>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px;"></td>
										<td align="right" style="padding:5px 15px 5px 10px;"></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('meadow_grass'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('meadow_fescue'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('orchard_grass'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('perennial_rye_grass'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('redtop'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('sweet_vernal'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('timothy_grass'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('oils_seed_rape'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('dandelion'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('dock'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('lambs_quarter'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('mugwort'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('nettle'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('daisy'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('plantain'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('ragweed'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('alder'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('ash'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('beech'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('birch'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('34'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('hazel'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('oak'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('privet'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('willow'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('culicoides'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('horse_fly'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('house_fly'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('mosquito'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('a_siro'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('l_destructor'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('t_putrescentiae'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('d_farinae'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('d_pteronyssinus'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('alternaria_alternata'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('aspergillus_mix'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#eaf6f7">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"><?php echo $this->lang->line('penicillium_mix'); ?></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"><?php echo $this->lang->line('0'); ?></td>
									</tr>
									<tr bgcolor="#d0ebef">
										<td align="left" style="padding:5px 10px 5px 15px; font-size:12px;"></td>
										<td align="right" style="padding:5px 15px 5px 10px; font-size:12px;"></td>
									</tr>
									<tr>
										<th align="left" colspan="2" style="background:#326883; border-radius:0 0 10px 10px; font-size:13px; color:#ffffff; padding:8px 10px 8px 15px;"><?php echo $this->lang->line('ige_antibody'); ?></th>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0" border="0" width="70%" align="left">
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" width="100%" border="0">
												<tr>
													<th height="35" width="30%"></th>
													<th width="10%" style="color:#326883; font-size:15px;"><?php echo $this->lang->line('5_0'); ?></th>
													<th width="10%" style="color:#326883; font-size:15px;"><?php echo $this->lang->line('10_0'); ?></th>
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
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>< <?php echo $this->lang->line('5_0'); ?></strong> </p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment'); ?></p>
							</td>
							<td width="33.33%" style="background:#dbf0f3; padding:20px 25px;">
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong><?php echo $this->lang->line('5_10'); ?></strong></p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('pets_environment_2'); ?></p>
							</td>
							<td width="33.33%" style="background:#cbe9ed; border-radius:0 10px 10px 0; padding:20px 25px;">
								<p style="font-size:14px; line-height:19px; margin:0 0 5px 0; padding:0;"> <strong>> <?php echo $this->lang->line('10_0'); ?></strong></p>
								<p style="font-size:12px; line-height:18px; margin:0; padding:0;"><?php echo $this->lang->line('additional_borderline'); ?></p>
							</td>
						</tr>
					</table>
					<table width="100%"><tr><td height="10"></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:0 50px 30px 50px;">
						<tr>
							<td>
								<p style="font-size:11px; line-height:16px; margin:0; padding:0;"><?php echo $this->lang->line('arbitrary_units'); ?></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>