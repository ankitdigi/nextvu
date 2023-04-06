<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('nextlab_ord_req_form'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		body{margin: 0px;}
		*{font-family:'Open Sans',sans-serif}
		</style>
	</head>
	<body bgcolor="#cccccc">
		<?php 
		if($order_details['pet_id']>0){
			$this->db->select('type,breed_id,other_breed,gender,age,age_year');
			$this->db->from('ci_pets');
			$this->db->where('id', $order_details['pet_id']);
			$petinfo = $this->db->get()->row_array();

			if($petinfo['breed_id']>0){
				$this->db->select('name');
				$this->db->from('ci_breeds');
				$this->db->where('id', $petinfo['breed_id']);
				$breedinfo = $this->db->get()->row_array();
			}else{
				$breedinfo = array();
			}
		}else{
			$petinfo = array();
			$breedinfo = array();
		}

		if($order_details['vet_user_id']>0){
			$refDatas = $this->UsersDetailsModel->getColumnAllArray($order_details['vet_user_id']);
			$refDatas = array_column($refDatas, 'column_field', 'column_name');
			$add_1 = !empty($refDatas['add_1']) ? $refDatas['add_1'].', ' : '';
			$add_2 = !empty($refDatas['add_2']) ? $refDatas['add_2'].', ' : '';
			$add_3 = !empty($refDatas['add_3']) ? $refDatas['add_3'] : '';
			$city = !empty($refDatas['add_4']) ? $refDatas['add_4'] : '';
			$postcode = !empty($refDatas['address_3']) ? $refDatas['address_3'] : '';
			$fulladdress = $add_1.$add_2.$add_3;
		}else{
			$fulladdress = '';
			$city = '';
			$postcode = '';
		}

		if(!empty($order_details['product_code_selection'])){
			$this->db->select('name');
			$this->db->from('ci_price');
			$this->db->where('id', $order_details['product_code_selection']);
			$ordeType = $this->db->get()->row()->name;
		}else{
			$ordeType = 'Serum Testing';
		}
		$serumdata = $this->OrdersModel->getSerumTestRecord($order_details['id']);
		$years = !empty($petinfo['age_year'])?$petinfo['age_year'].'Year, ':'';
		$months = !empty($petinfo['age'])?$petinfo['age'].'Month':'';
		?>
		<table class="main_container" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
			<tbody>
				<tr>
					<td width="100%">
						<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td align="left" valign="middle" style="background:#426e89; padding: 5mm 0mm 5mm 8mm;">
														<p style="font-size: 32px;text-transform: uppercase;color: #ffffff;font-weight: 400; margin: 0; letter-spacing: 2px; line-height: 38px; white-space:nowrap;"><?php echo $this->lang->line('serum_test'); ?><br><?php echo $this->lang->line('request_form'); ?> </p>
													</td>
													<td valign="top"><img src="<?php echo base_url("/assets/images/aqua-corner-shape.png"); ?>" alt="NextVu" height="180" /></td>
													<td width="7%">&nbsp;</td>
													<td align="right" style="padding: 0 8mm 0 0;">
														<img src="<?php echo base_url("/assets/images/nextmune-uk.png"); ?>" height="55" alt="NextVu" />
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php /* <table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 8mm 8mm 2mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="color:#426e89; letter-spacing:1px; font-size:16px;"><b>Order Type:</b> <?php echo $ordeType; ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table> */ ?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="color:#426e89; letter-spacing:1px; font-size:20px;"><?php echo $this->lang->line('practice_details'); ?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
			<tr>
				<td width="49%" valign="top">
					<table width="100%">
						<tbody>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="49%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('date'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																	<?php echo date('d/m/Y',strtotime($order_details['order_date'])); ?>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="49%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('veterinary_surgeon'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																	<?php echo $order_details['name']; ?>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
													<?php echo $order_details['practice_name']; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:5px; line-height:5px;"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('practice_details'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
													<?php echo $fulladdress; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:5px; line-height:5px;"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="49%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('city'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																	<?php echo $city; ?>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="49%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('postcode'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																	<?php echo $postcode; ?>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="2%">&nbsp;</td>
				<td width="49%" valign="top">
					<table width="100%">
						<tbody>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('phone'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
													<?php echo $order_details['phone_number']; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:5px; line-height:5px;"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('email'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
													<?php echo $order_details['email']; ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:5px; line-height:5px;"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('results_will_be_delivered_by_email'); ?></p>
												</td>
											</tr>
											<tr>
												<td style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="6%" valign="top">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
																<td width="2%">&nbsp;</td>
																<td width="92%" valign="top">
																	<?php /* <table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td style="color:#346a7e; font-size:14px;">
																					I would like to order more serum test shipping materials
																				</td>
																			</tr>
																		</tbody>
																	</table> */ ?>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tbody>
				<tr>
					<td style="height: 25px;"></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:0mm 8mm">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="color:#426e89; letter-spacing:1px; font-size:20px;"><?php echo $this->lang->line('pet_and_pet_owner_details'); ?></td>
												</tr>
												<tr>
													<td style="height: 10px; line-height: 10px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
			<tbody>
				<tr>
					<td width="49%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="49%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('pet_owners_first_name'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																		<?php echo $order_details['pet_owner_name']; ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="2%">&nbsp;</td>
													<td width="49%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('pet_owners_last_name'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																		<?php echo $order_details['po_last']; ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('Species'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if($order_details['species_name'] == 'Dog'){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('dog'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if($order_details['species_name'] == 'Cat'){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('cat'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if($order_details['species_name'] == 'Horse'){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('horse'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="49%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('breed'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																		<?php echo $breedinfo['name']; ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="2%">&nbsp;</td>
													<td width="49%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('age_month_and_year'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																		<?php echo $years.$months; ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="2%">&nbsp;</td>
					<td width="49%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('animal_name'); ?>:</p>
													</td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
														<?php echo $order_details['pet_name']; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:5px; line-height:5px;"></td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('gender'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="15%">
																										<?php if($petinfo['gender'] == '1'){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="85%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('male'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="15%">
																										<?php if($petinfo['gender'] == '2'){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="85%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('female'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('date_serum_drawn'); ?>:</p>
													</td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
														<?php echo !empty($serumdata['serum_drawn_date'])?date('d/m/Y',strtotime($serumdata['serum_drawn_date'])):''; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tbody>
				<tr>
					<td style="height: 25px;"></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:0mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="color:#426e89; letter-spacing:1px; font-size:20px;"><?php echo $this->lang->line('medical_history'); ?></td>
												</tr>
												<tr>
													<td style="height: 10px; line-height: 10px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('patient_affected'); ?></p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '1' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('pruritus_itch'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '5' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('skin_lesions'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '2' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('otitis'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '3' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('respiratory_signs'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '6' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('ocular_signs'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td width="100%" style="height: 10px; line-height:10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '7' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('anaphylaxis'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="20%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="15%" valign="top">
																														<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '4' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td valign="top" width="85%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('gastro_intestinal_signs'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="60%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="30%" valign="middle">
																														<table width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
																															<tbody>
																																<tr>
																																	<td width="15%" valign="middle">
																																		<?php if( isset($serumdata['major_symptoms']) && (strpos( $serumdata['major_symptoms'], '0' ) !== false) ){ ?>
																																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																																		<?php }else{ ?>
																																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																																		<?php } ?>
																																	</td>
																																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px;">
																																	<?php echo $this->lang->line('other'); ?>
																																	</td>
																																</tr>
																															</tbody>
																														</table>
																													</td>
																													<td valign="top" width="70%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_symptom']) ? $serumdata['other_symptom'] : ''; ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height:5px; line-height:5px;"></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="height:10px; line-height: 10px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?></p>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
														<?php echo $serumdata['symptom_appear_age'].' years '.$serumdata['symptom_appear_age_month'].' months'; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="height:10px; line-height: 10px;"></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('symptoms_most_obvious'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('spring'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '2' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('summer'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '3' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('fall'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '4' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('winter'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['when_obvious_symptoms']) && (strpos( $serumdata['when_obvious_symptoms'], '5' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('year_round'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:10px; line-height:10px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="4%">&nbsp;</td>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('symptoms_most_obvious'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('indoors'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '2' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('outdoors'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="33%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['where_obvious_symptoms']) && (strpos( $serumdata['where_obvious_symptoms'], '3' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('no_difference'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('clinical_diagnosis'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('food'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['diagnosis_food']) && (strpos( $serumdata['diagnosis_food'], '2' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_diagnosis_food']) ? $serumdata['other_diagnosis_food'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('hymenoptera_stings'); ?>:</p>
																	</td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['diagnosis_hymenoptera']) && (strpos( $serumdata['diagnosis_hymenoptera'], '2' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">	<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_diagnosis_hymenoptera']) ? $serumdata['other_diagnosis_hymenoptera'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="4%">&nbsp;</td>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td style="height: 60px; line-height: 60px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="top">
										<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('food_challenge'); ?>:</p>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="33%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																		&lt;<?php echo $this->lang->line('3_h'); ?> 
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																	<?php echo $this->lang->line('3_12_h'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="33%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																	<?php echo $this->lang->line('12_24_h'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="height:5px; line-height:5px;"></td>
												</tr>
												<tr>
													<td width="50%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '4' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																	<?php echo $this->lang->line('24_48_h'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="50%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['food_challenge']) && (strpos( $serumdata['food_challenge'], '5' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																		&gt; <?php echo $this->lang->line('48_h'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height: 10px; line-height: 10px;"></td>
								</tr>
								<tr>
									<td width="100%" valign="top">
										<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('other_s'); ?>:</p>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="20%">
																		<?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td width="80%" style="color:#346a7e; font-size:14px;">
																	<?php echo $this->lang->line('yes'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="75%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="30%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="20%">
																						<?php if( isset($serumdata['diagnosis_other']) && (strpos( $serumdata['diagnosis_other'], '2' ) !== false) ){ ?>
																						<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																						<?php }else{ ?>
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																						<?php } ?>
																					</td>
																					<td width="80%" style="color:#346a7e; font-size:14px;">
																					<?php echo $this->lang->line('no'); ?>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																	<td width="70%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%">
																						<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																					</td>
																				</tr>
																				<tr>
																					<td width="100%" style="height:5px; line-height:5px;"></td>
																				</tr>
																				<tr>
																					<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																						<?php echo isset($serumdata['other_diagnosis']) ? $serumdata['other_diagnosis'] : '';?>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>	
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%" valign="middle">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" style="height: 10px; line-height:10px;"></td>
												</tr>
												<tr>
													<td width="100%" valign="top">
														<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('exposed_following_animals'); ?>:</p>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" style="height: 10px; line-height:10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="middle">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="25%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '1' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('cats'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="25%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '2' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('dogs'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="25%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '3' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('horses'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="25%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '4' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('cattle'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																				<tr>
																					<td style="height:5px; line-height: 5px;"></td>
																				</tr>
																				<tr>
																					<td width="100%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="33%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '5' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('mice'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="33%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '6' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('guinea_pigs'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="33%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '7' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('rabbits'); ?>	
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="100%" style="height: 10px; line-height:10px;"></td>
																				</tr>
																				<tr>
																					<td width="100%" valign="middle">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="100%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="25%" valign="middle">
																														<table width="100%" cellspacing="0" cellpadding="0" border="0">
																															<tbody>
																																<tr>
																																	<td valign="middle" width="10%">
																																		<?php if( isset($serumdata['regularly_exposed']) && (strpos( $serumdata['regularly_exposed'], '0' ) !== false) ){ ?>
																																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																																		<?php }else{ ?>
																																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																																		<?php } ?>
																																	</td>
																																	<td valign="middle" width="90%" style="color:#346a7e; font-size:14px; text-align: left;">
																																	<?php echo $this->lang->line('other'); ?>
																																	</td>
																																</tr>
																															</tbody>
																														</table>
																													</td>
																													<td width="75%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_exposed']) ? $serumdata['other_exposed'] : ''; ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td width="100%" style="height: 10px; line-height:10px;"></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="100%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('malassezia_infections'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 5px; line-height: 5px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="15%" valign="middle">
																										<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																									<?php echo $this->lang->line('malassezia_otitis'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="50%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td valign="middle" width="15%">
																										<?php if( isset($serumdata['malassezia_infections']) && (strpos( $serumdata['malassezia_infections'], '2' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																									<?php echo $this->lang->line('malassezia_dermatitis'); ?>	
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="100%" style="height: 10px; line-height: 10px;"></td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('receiving_drugs'); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" style="height: 10px; line-height: 10px;"></td>
				</tr>
				<tr>
					<td width="100%" valign="middle">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('glucocorticoids_oral_topical_injectable'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_1']) && (strpos( $serumdata['receiving_drugs_1'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('ciclosporin'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_2']) && (strpos( $serumdata['receiving_drugs_2'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('oclacitinib'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_3']) && (strpos( $serumdata['receiving_drugs_3'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '4' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('lokivetmab'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_4']) && (strpos( $serumdata['receiving_drugs_4'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '5' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('antibiotics'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_5']) && (strpos( $serumdata['receiving_drugs_5'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs']) && (strpos( $serumdata['receiving_drugs'], '6' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('antifungals'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '1' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('no_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '2' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('fair_response'); ?>	
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
													<td width="25%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" width="15%">
																		<?php if( isset($serumdata['receiving_drugs_6']) && (strpos( $serumdata['receiving_drugs_6'], '3' ) !== false) ){ ?>
																		<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																		<?php }else{ ?>
																		<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																		<?php } ?>
																	</td>
																	<td valign="middle" width="85%" style="color:#346a7e; font-size:14px; text-align: left;">
																	<?php echo $this->lang->line('good_to_excellent_response'); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:2mm 8mm;">
			<tbody>
				<tr>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('treatment_ectoparasites'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['treatment_ectoparasites']) && (strpos( $serumdata['treatment_ectoparasites'], '1' ) !== false) ){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['treatment_ectoparasites']) && (strpos( $serumdata['treatment_ectoparasites'], '2' ) !== false) ){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_ectoparasites']) ? $serumdata['other_ectoparasites'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('zoonotic_disease'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==1){ ?>
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if(isset($serumdata['zoonotic_disease']) && $serumdata['zoonotic_disease']==0){ ?>
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['zoonotic_disease_dec']) ? $serumdata['zoonotic_disease_dec'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('additional_information'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 100px; padding:0 10px; font-size:14px;">
																		<?php echo isset($serumdata['additional_information']) ? $serumdata['additional_information'] : '';?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td width="4%">&nbsp;</td>
					<td width="48%" valign="top">
						<table width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td width="100%" valign="middle">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('elimination_diet'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['elimination_diet']) && (strpos( $serumdata['elimination_diet'], '1' ) !== false) ){ ?> 
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>	
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['elimination_diet']) && (strpos( $serumdata['elimination_diet'], '2' ) !== false) ){ ?> 
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['other_elimination']) ? $serumdata['other_elimination'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('medication'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="25%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="20%">
																										<?php if( isset($serumdata['medication']) && (strpos( $serumdata['medication'], '1' ) !== false) ){ ?> 
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																										<?php }else{ ?>
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																										<?php } ?>
																									</td>
																									<td width="80%" style="color:#346a7e; font-size:14px;">
																									<?php echo $this->lang->line('yes'); ?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																					<td width="75%">
																						<table width="100%" cellspacing="0" cellpadding="0" border="0">
																							<tbody>
																								<tr>
																									<td width="30%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="20%">
																														<?php if( isset($serumdata['medication']) && (strpos( $serumdata['medication'], '0' ) !== false) ){ ?> 
																														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/filled-checkbox.png"); ?>" alt="NextVu" />
																														<?php }else{ ?>
																														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																														<?php } ?>
																													</td>
																													<td width="80%" style="color:#346a7e; font-size:14px;">
																													<?php echo $this->lang->line('no'); ?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																									<td width="70%">
																										<table width="100%" cellspacing="0" cellpadding="0" border="0">
																											<tbody>
																												<tr>
																													<td width="100%">
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
																													</td>
																												</tr>
																												<tr>
																													<td width="100%" style="height:5px; line-height:5px;"></td>
																												</tr>
																												<tr>
																													<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 32px; padding:0 10px; font-size:14px;">
																														<?php echo isset($serumdata['medication_desc']) ? $serumdata['medication_desc'] : '';?>
																													</td>
																												</tr>
																											</tbody>
																										</table>
																									</td>
																								</tr>
																							</tbody>	
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;">		<?php echo $this->lang->line('internal_use_only'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 100px; padding:0 10px; font-size:14px;">
																	</td>
																</tr>
																<tr>
																	<td style="height: 30px; line-height: 30px;"></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="40" border="0" align="center" width="100%" style="padding: 0mm 8mm;background-color: #e5f2f5;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="50%" align="left" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 18px;color: #426e89;font-weight: 400; margin: 0;">		<?php echo $this->lang->line('alle_visit_co_uk'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td width="50%" align="right" valign="middle">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<img src="<?php echo base_url("/assets/images/lock.png"); ?>" height="80" alt="NextVu" />
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="20" border="0" align="center" width="100%" style="padding: 0mm 8mm;background-color: #426e89;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%" align="center" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 24px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align: center;">		<?php echo $this->lang->line('sample_submission_form_2'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="height: 5px; line-height: 5px;"></td>
								</tr>
								<tr>
									<td width="100%" align="center" valign="middle">
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 16px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align:center;">		<?php echo $this->lang->line('individual_test_box_2'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
			<tbody>
				<tr>
					<td width="100%" style="height: 20px; line-height: 20px;"></td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="35%" align="left" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="font-size: 20px;color: #426e89;font-weight: 600; margin: 0; text-transform: uppercase;">
													<?php echo $this->lang->line('storage_only'); ?>	
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td width="65%" align="left" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td width="100%">
																		<table width="100%" cellspacing="0" cellpadding="0" border="0">
																			<tbody>
																				<tr>
																					<td width="7%" valign="top">
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/blank-checkbox.png"); ?>" alt="NextVu" />
																					</td>
																					<td valign="top" width="93%" style="color:#346a7e; font-size:14px; text-align: left;">
																					<?php echo $this->lang->line('charge_for_3_months'); ?>	
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" style="height: 40px; line-height: 40px;"></td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
			<tbody>
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="25%" align="left" valign="top">
										<img src="<?php echo base_url("/assets/images/dog.png"); ?>" width="200" alt="CANINE TEST" />
									</td>
									<td width="75%" align="left" valign="top">
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:17px 0 0 0;">
											<tbody>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="50%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="background:#cee8ee; color:#426e89; font-size:24px; text-transform: uppercase;"><?php echo $this->lang->line('canine_tests'); ?></td>
																			<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail1.png"); ?>"  height="51" alt="CANINE" /></td>
																		</tr>
																	</table>
																</td>
																<td width="50%">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 10px;"></td>
															</tr>
															<tr>
																<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b><?php echo $this->lang->line('nextLab'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE) & Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Dog'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('comp_env_food_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE)' && $order_details['species_name'] == 'Dog'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_penal'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Dog'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('food_penal_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;"><?php echo $this->lang->line('nextlab_screens'); ?></b><b><?php echo $this->lang->line('posi_neg_serum_result'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB SCREEN Environmental only, 4 Panels: Grasses, Weeds, Trees and Indoor' && $order_details['species_name'] == 'Dog'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB SCREEN Food only, single result Positive/Negative' && $order_details['species_name'] == 'Dog'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('food_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;"><?php echo $this->lang->line('phase_apps'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if(preg_match('/\bAcute Phase Proteins\b/', $respnedn->name) && ($order_details['species_name'] == 'Dog')){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('c_crp_sample'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
			<tbody>
				<tr>
					<td width="100%" style="height: 5px;"></td>
				</tr>
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="25%" align="left" valign="top">
										<img src="<?php echo base_url("/assets/images/cat.png"); ?>" width="200" alt="Feline TEST" />
									</td>
									<td width="75%" align="left" valign="top">
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:27px 0 0 0;">
											<tbody>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="50%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="background:#7dc1c9; color:#fff; font-size:24px; text-transform: uppercase;"><?php echo $this->lang->line('feline_tests_2'); ?></td>
																			<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail2.png"); ?>"  height="51" alt="Feline" /></td>
																		</tr>
																	</table>
																</td>
																<td width="50%">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 10px;"></td>
															</tr>
															<tr>
																<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b><?php echo $this->lang->line('nextLab'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE) & Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Cat'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('complete_env_food_panel'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental Panel (IgE)' && $order_details['species_name'] == 'Cat'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_panel_print_food'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Food Panel (IgE & IgG)' && $order_details['species_name'] == 'Cat'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('food_penal_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;"><?php echo $this->lang->line('nextlab_screens'); ?></b><b><?php echo $this->lang->line('posi_neg_serum_result'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB SCREEN Environmental only, 4 Panels: Grasses, Weeds, Trees and Indoor' && $order_details['species_name'] == 'Cat'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB SCREEN Food only, single result Positive/Negative' && $order_details['species_name'] == 'Cat'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('food_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;"><?php echo $this->lang->line('phase_apps'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if(preg_match('/\bAcute Phase Proteins\b/', $respnedn->name) && ($order_details['species_name'] == 'Cat')){ ?>
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('glycoprotein_agp_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0 8mm 0 0;">
			<tbody>
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr>
									<td width="25%" align="left" valign="top">
										<img src="<?php echo base_url("/assets/images/horse.png"); ?>" width="200" alt="Equine TEST" />
									</td>
									<td width="75%" align="left" valign="top">
										<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:17px 0 0 0;">
											<tbody>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="50%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td style="background:#b8c6d6; color:#426e89; font-size:24px; text-transform: uppercase;"><?php echo $this->lang->line('equine_tests'); ?></td>
																			<td style="line-height:0;" valign="top"><img src="<?php echo base_url("/assets/images/tail3.png"); ?>"  height="51" alt="Equine" /></td>
																		</tr>
																	</table>
																</td>
																<td width="50%">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 10px;"></td>
															</tr>
															<tr>
																<td width="100%" style="text-transform: uppercase; font-size: 16px; color:#426e89;"><b><?php echo $this->lang->line('nextLab'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental and Insect Panel (IgE) & Food (IgE & IgG)' && $order_details['species_name'] == 'Horse'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('complete_env_ins_food_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Environmental and Insect Panel (IgE)' && $order_details['species_name'] == 'Horse'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_insect_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB Complete Food (IgE & IgG)' && $order_details['species_name'] == 'Horse'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('food_panel_print_food'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="font-size: 11px; color:#000;"><b style="text-transform: uppercase; font-size: 16px; color:#426e89;"><?php echo $this->lang->line('nextlab_screens'); ?></b><b><?php echo $this->lang->line('posi_neg_serum_result'); ?></b></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td width="100%">
														<table width="100%" cellspacing="0" cellpadding="0" border="0">
															<tr>
																<td width="100%" style="height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="top" width="5%">
																				<?php if($ordeType == 'NEXTLAB SCREEN Environmental & Insect Screen (IgE)' && $order_details['species_name'] == 'Horse'){ ?>
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				<?php }else{ ?>
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<?php } ?>
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_ins_screen_serum_result'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<div style='page-break-after:always'></div>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:5mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td style="color: #333333; font-size:12px;"><?php echo $this->lang->line('nextmune_laboratories_serum_result'); ?></td>
												</tr>
												<tr>
													<td style="height: 20px; line-height: 20px;"></td>
												</tr>
												<tr>
													<td style="color: #333333; font-size:12px;"><?php echo $this->lang->line('devlopment_purposes_serum_result'); ?></td>
												</tr>
												<tr>
													<td style="height: 5px; line-height: 5px;"></td>
												</tr>
												<tr>
													<td style="color: #333333; font-size:12px;"><?php echo $this->lang->line('nextm_labo_utilise'); ?></td>
												</tr>
												<tr>
													<td style="height: 10px; line-height: 10px;"></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tbody>
				<tr>
					<td style="height: 25px;"></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;background-color: #426e89;">
			<tbody>
				<tr>
					<td width="100%">
						<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%" align="center" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 13px;color: #fff;font-weight: 400; margin: 0; text-align: center;"><?php echo $this->lang->line('nextm_labo_limited_serum_result'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="height: 5px; line-height: 5px;"></td>
								</tr>
								<tr>
									<td width="100%" align="center" valign="middle">
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 13px;color: #fff;font-weight: 400; margin: 0; text-align: center;"> <?php echo $this->lang->line('t_0800_e'); ?> – <a href="mailto:vetorders.uk@nextmune.com" style="color: #fff; text-decoration: underline;"><?php echo $this->lang->line('contact_email'); ?></a></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0mm 8mm;">
			<tbody>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="50%" align="left" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 12px;color: #333333;font-weight: 700; margin: 0;">© <?php echo $this->lang->line('netxmune_2022'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td width="50%" align="right" valign="middle">
										<table cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 12px;color: #333333;font-weight: 700; margin: 0;"><?php echo $this->lang->line('nm035_06_22'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>