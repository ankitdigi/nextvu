<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('pax_order_req_form'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		body{margin: 0px;}
		*{font-family:'Open Sans',sans-serif}
		table th{text-align: left; font-weight: 400;}
		.filled-checkbox,.filled-radio{display: none;}
		</style>
	</head>
	<body bgcolor="#fff">
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
		
		$this->db->select('id, name');
		$this->db->from('ci_price');
		$this->db->where('id', $order_details['product_code_selection']);
		$respned = $this->db->get()->row();
		?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
		        <td width="100%">
		            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: url(<?php echo base_url(); ?>assets/images/banner-img-new.jpg); background-position: center; background-size: auto; background-repeat: no-repeat;">
		                <tbody>
		                	<tr>
		                    	<td width="100%">
		                        	<table width="100%" align="center" valign="bottom" cellspacing="0" cellpadding="0" border="0" style="padding: 8mm 12mm;">
		                            	<tbody>
		                            		<tr>
		                                		<td width="50%" align="middle">
		                                    		<img src="<?php echo base_url(); ?>assets/images/pax-logo.png" width="300">
		                                		</td>
		                                		<td width="50%" valign="middle">
		                                			<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                                				<tr>
		                                					<td width="100%">
		                                						<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                                							<tr>
					                                					<td width="30%">&nbsp;</td>
					                                					<td width="70%" align="right">
			                            									<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
			                            										<tr>
			                            											<td style="color: #366784; font-size:12px;">
					                                									<b><?php echo $this->lang->line('lab_barcode_nu'); ?></b>
					                                								</td>
			                            										</tr>
			                            										<tr>
			                            											<td style="height: 5px;"></td>
			                            										</tr>
			                            										<tr>
					                                								<td width="100%">
					                                									<table width="100%" cellpadding="0" cellspacing="0" border="0">
					                                										<tr>
					                                											<td width="100%">
					                                												<img src="<?php echo base_url(); ?>assets/images/qr-code-img.png" width="60">
					                                											</td>
					                                										</tr>
					                                									</table>
					                                								</td>
					                                							</tr>
			                            									</table>
			                            								</td>
		                                							</tr>
		                                						</table>
		                                					</td>
		                                				</tr>
		                                				<tr>
		                                					<td width="100%" style="height: 15px;"></td>
		                                				</tr>
		                                				<tr>
		                                					<td width="100%" align="right">
		                                						<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                                							<tr>
		                                								<td style="color: #366784; font-size: 28px;">
		                                									<b><?php echo $this->lang->line('request_form'); ?></b>						
		                                								</td>
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
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 5mm 12mm;">
		    <tr>
		    	<td width="48%" valign="top">
		    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                <tbody>
		                	<tr>
		                    	<td width="100%">
		                        	<table width="100%" align="center" valign="top" cellspacing="0" cellpadding="0" border="0">
		                            	<tbody>
		                            		<tr>
									    		<td width="100%" style="font-size: 14px; color: #366784;">
									        		<b><?php echo $this->lang->line('animal_and_owner_details'); ?>:</b>
									    		</td>
											</tr>
											<tr>
									    		<td width="100%" style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('owners_name'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['pet_owner_name']; ?> <?php echo $order_details['po_last']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('Animals_Name'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['pet_name']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('breed'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $breedinfo['name']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('age_month_and_year'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $years.$months; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('date_serum_drawn'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo !empty($serumdata['serum_drawn_date'])?date('d/m/Y',strtotime($serumdata['serum_drawn_date'])):''; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
									    		<td width="100%" style="height: 2px; line-height: 2px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('age_oneset_pru'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $serumdata['symptom_appear_age'].' years '.$serumdata['symptom_appear_age_month'].' months'; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
									    		<td width="100%" style="height: 2px; line-height: 2px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('nextview_order_no'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['order_number']; ?></td>
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
		    	<td align="top" style="width:2%"></td>
		    	<td width="48%" valign="top">
		    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                <tbody>
		                	<tr>
		                    	<td width="100%">
		                        	<table width="100%" align="center" valign="top" cellspacing="0" cellpadding="0" border="0">
		                            	<tbody>
		                            		<tr>
									    		<td width="100%" style="font-size: 14px; color: #366784;">
									        		<b><?php echo $this->lang->line('practice_details'); ?>:</b>
									    		</td>
											</tr>
											<tr>
									    		<td width="100%" style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('Veterinarian'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['name']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['practice_name']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="top" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('practice_details'); ?>:</td>
																<td width="200" valign="top" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $fulladdress; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('postcode_city'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $postcode; ?>, <?php echo $city; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('country'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['order_country']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
									    		<td width="100%" style="height: 2px; line-height: 2px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 2mm;">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('phone'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['phone_number']; ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellpadding="0" cellspacing="0" border="0">
														<tbody>
															<tr>
																<td width="150" valign="middle" style="color: #336585; font-size: 13px;"><?php echo $this->lang->line('email'); ?>:</td>
																<td width="200" valign="middle" style="color: #336585; border-bottom: 1px solid #336585; font-size: 13px;"><?php echo $order_details['email']; ?></td>
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
		<table width="100%" cellspacing="0" cellpadding="0" border="0" >
		    <tr>
		    	<td width="10%" valign="top">
		    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                <tbody>
		                	<tr>
					    		<td width="100%">
					    			<img src="<?php echo base_url(); ?>assets/images/section-img-new.png" style="height: 60px;">
					    		</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="90%" valign="top" style="padding-top: 3px;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#336584;">
						<tbody>
							<tr>
								<td width="100%" style="height: 8px;"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tbody>
											<tr>
												<td width="100%" style="height: 11px;"></td>
											</tr>
											<tr>
									    		<td width="100%" style="color: #fff; font-size: 13px; padding-left: 4mm;">
													<b><?php echo $this->lang->line('select_test_s'); ?>:</b>
												</td>
											</tr>
											<tr>
												<td width="100%" style="height: 12px;"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td width="100%" style="height: 7px;"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" >
		    <tr>
		    	<td width="100%">
		    		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e6f3f6; padding: 6mm 12mm;">
		    			<tbody>
		    				<tr>
	    						<td width="100%">
	    							<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
	    								<tbody>
	    									<tr>
	    										<td width="100%">
	    											<table valign="center" cellpadding="0" cellspacing="0" border="0" style="background-image: url(<?php echo base_url(); ?>assets/images/rounded-bg-img.png); background-position: cente; background-repeat: no-repeat; background-size: cover;">
	    												<tbody>
	    													<tr>
					                                            <td align="center" style="padding: 3mm 3mm 3mm 5mm; line-height: 1;">
					                                            	<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 30px;">
					                                            </td>
					                                            <td align="center" style="padding: 3mm 3mm 3mm 0mm; line-height: 1;"><img src="<?php echo base_url(); ?>assets/images/dog-img.png"  style="max-height: 35px;"></td>
					                                            <td align="center" style="font-size: 28px; line-height: 38px; color: #336584; text-transform: uppercase; padding: 3mm 20mm 3mm 0mm;"><b><?php echo $this->lang->line('dog'); ?></b></td>
					                                        </tr>
	    												</tbody>
	    											</table>
	    										</td>
	    									</tr>
	    									<tr>
	    										<td width="100%">
	    											<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    												<tr>
	    													<td width="100%" style="height: 10px;"></td>
	    												</tr>
	    											</table>
	    										</td>
	    									</tr>
	    									<tr>
	    										<td width="100%">
	    											<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    												<tr>
	    													<td width="100%">
	    														<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    															<tr>
	    																<td width="49%">
	    																	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    																		<tr>
	    																			<td width="100%">
	    																				<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    																					<tr>
																								<td width="100%">
																									<table align="left" cellpadding="0" cellspacing="0" border="0">
																										<tbody>
																											<tr>
																												<td width="100%">
																													<table width="100%" cellpadding="0" cellspacing="0" border="0">
																														<tr>
																															<td style="font-size:24px; line-height: 28px; color: #366784; text-transform: uppercase;"><b><?php echo $this->lang->line('pax_screening'); ?></b></td>				
																														</tr>
																														<tr>
																															<td style="font-size:12px; line-height: 16px; color: #366784;"><?php echo $this->lang->line('pos_neg_result_print_order_form'); ?></td>
																														</tr>
																													</table>
																												</td>
																											</tr>
																										</tbody>
																									</table>
																								</td>
																							</tr>
																							<tr>
																								<td width="100%" valign="middle">
																									<table valign="middle" cellpadding="0" cellspacing="0" border="0">
																										<tbody>
																											<tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																													<?php if($respned->id == "37" || $respned->name == "PAX Environmental + Food Screening"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584; padding: 3mm 0mm;"><?php echo $this->lang->line('env_food_print_order_form'); ?></td>
																							                </tr>
																							                <tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																							                    	<?php if($respned->id == "35" || $respned->name == "PAX Environmental Screening"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584; padding: 3mm 0mm;"><?php echo $this->lang->line('env'); ?></td>
																							                </tr>
																							                <tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																							                    	<?php if($respned->id == "36" || $respned->name == "PAX Food Screening"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584;; padding: 3mm 0mm;"><?php echo $this->lang->line('food_2'); ?></td>
																							                </tr>
																										</tbody>
																									</table>
																								</td>
																							</tr>
	    																				</table>
	    																			</td>
	    																		</tr>
	    																	</table>
	    																</td>
	    																<td width="2%">&nbsp;</td>
	    																<td width="49%">
	    																	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    																		<tr>
	    																			<td width="100%">
	    																				<table width="100%" cellpadding="0" cellspacing="0" border="0">
	    																					<tr>
																								<td width="100%">
																									<table align="left" cellpadding="0" cellspacing="0" border="0">
																										<tbody>
																											<tr>
																												<td width="100%">
																													<table width="100%" cellpadding="0" cellspacing="0" border="0">
																														<tr>
																															<td style="font-size:24px; line-height: 28px; color: #366784; text-transform: uppercase;"><b><?php echo $this->lang->line('pax_complete'); ?></b></td>				
																														</tr>
																														<tr>
																															<td style="font-size:12px; line-height: 16px; color: #366784;"><?php echo $this->lang->line('com_res_aller_test_pri_ord'); ?></td>				
																														</tr>
																													</table>
																												</td>
																											</tr>
																										</tbody>
																									</table>
																								</td>
																							</tr>
																							<tr>
																								<td width="100%" valign="middle">
																									<table valign="middle" cellpadding="0" cellspacing="0" border="0">
																										<tbody>
																											<tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																							                    	<?php if($respned->id == "38" || $respned->name == "PAX Environmental + Food"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584; padding: 3mm 0mm;"><?php echo $this->lang->line('env_food_print_order_form'); ?></td>
																							                </tr>
																							                <tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																							                    	<?php if($respned->id == "34" || $respned->name == "PAX Environmental"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584; padding: 3mm 0mm;"><?php echo $this->lang->line('env'); ?></td>
																							                </tr>
																							                <tr>
																							                    <td valign="middle" align="left" style="padding: 3mm 0mm; line-height: 1;">
																							                    	<?php if($respned->id == "33" || $respned->name == "PAX Food"){ ?>
																														<img class="filled" src="<?php echo base_url(); ?>assets/images/filled-radio-img.png" style="max-height: 20px;">
																													<?php }else{ ?>
																														<img src="<?php echo base_url(); ?>assets/images/radio-img.png" style="max-height: 20px;">
																													<?php } ?>
																							                    </td>
																							                    <td valign="middle" align="left" width="2%" style="line-height: 1;">&nbsp;</td>
																							                    <td valign="middle" align="left" style="font-size: 16px; line-height: 24px; color: #336584; padding: 3mm 0mm;"><?php echo $this->lang->line('food_2'); ?></td>
																							                </tr>
																										</tbody>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
	    																</td>
	    															</tr>
	    														</table>	
	    													</td>
	    												</tr>
	    											</table>
	    										</td>
	    									</tr>
	    									<tr>
	    										<td width="100%">
	    											<table cellpadding="0" cellspacing="0" border="0" style="background-image: url(<?php echo base_url(); ?>assets/images/required-rounded-bg-img.png); background-position: cente; background-repeat: no-repeat; background-size: cover;">
	    												<tr>
	    													<td style="color: #fff; padding: 2mm 8mm; font-weight: 500; font-size: 14px;"><?php echo $this->lang->line('req_ser_vol'); ?></td>
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
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td width="100%" style="height: 20px;"></td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		    <tr>
		    	<td width="10%" valign="top">
		    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		                <tbody>
		                	<tr>
					    		<td width="100%">
					    			<img src="<?php echo base_url(); ?>assets/images/section-img-2-new.png" style="height: 60px;">
					    		</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="90%" valign="top" style="padding-top: 2px;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#336584;">
						<tbody>
							<tr>
								<td width="100%" style="height: 20px;"></td>
							</tr>
							<tr>
					    		<td width="100%" style="color: #fff; font-size: 13px; padding-left: 4mm;">
									<b><?php echo $this->lang->line('clinical_history'); ?>:</b>
								</td>
							</tr>
							<tr>
								<td width="100%" style="height: 20px;"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>

		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding:5mm 8mm;">
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('symptoms_most_obvious'); ?></p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('symptoms_most_obvious'); ?></p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('clinical_diagnosis'); ?></p>
																	</td>
																</tr>
																<tr>
																	<td style="height: 10px; line-height: 10px;"></td>
																</tr>
																<tr>
																	<td width="100%" valign="top">
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('food'); ?>:</p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('hymenoptera_stings'); ?>:</p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('exposed_following_animals'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('malassezia_infections'); ?></p>
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
										<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('receiving_drugs'); ?></p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('treatment_ectoparasites'); ?></p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('zoonotic_disease'); ?></p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('additional_information'); ?></p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('elimination_diet'); ?></p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('medication'); ?></p>
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
																														<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('specify_which_one_s_if_known'); ?>:</p>
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
																		<p style="color:#346a7e; font-size:14px; line-height: 24px;"><?php echo $this->lang->line('internal_use_only'); ?></p>
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
														<p style="font-size: 18px;color: #426e89;font-weight: 400; margin: 0;"><?php echo $this->lang->line('alle_visit_co_uk'); ?></p>
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

		<table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid #5b8398">
			<tbody>
				<tr>
			        <td width="100%">
			            <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0" style="padding: 5mm 12mm;">
			                <tbody>
			                	<tr>
				                    <td width="100%">
				                        <table width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
				                            <tbody>
				                            	<tr>
				                            		<td width="32%" align="left">
					                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
									    					<tbody>
									    						<tr>
									    							<td width="100%" style="padding-bottom: 2mm;">
									    								<img src="<?php echo base_url(); ?>assets/images/footer-logo.png" width="150">
									    							</td>
									    						</tr>
									    						<tr>
									    							<td width="100%" style="color: #7cc1c7; font-size: 13px; padding-left: 8mm;"><b><?php echo $this->lang->line('eye_patient_pri_order'); ?>,</b></td>
									    						</tr>
									    						<tr>
									    							<td width="100%" style="color: #7cc1c7; font-size: 13px; padding-left: 8mm;"><b><?php echo $this->lang->line('min_on_innovation'); ?></b></td>
									    						</tr>
									    					</tbody>
									    				</table>
					                                </td>
					                                 <td width="25%" align="left">
					                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
									    					<tbody>
									    						<tr>
									    							<td style="color: #366784; font-size: 12px;"><b><?php echo $this->lang->line('nextmune_labo_ltd'); ?></b></td>
									    						</tr>
									    						<tr>
									    							<td style="line-height: 5px; height: 5px;"></td>
									    						</tr>
									    						<tr>
									    							<td style="color: #366784; font-size: 11px;"><?php echo $this->lang->line('unit_street_pri_order'); ?></td>
									    						</tr>
									    						<tr>
									    							<td style="height: 3px; line-height: 3px;"></td>
									    						</tr>
									    						<tr>
									    							<td style="color: #366784; font-size: 11px;"><?php echo $this->lang->line('throp_arch_tra_estate'); ?></td>
									    						</tr>
									    						<tr>
									    							<td style="height: 3px; line-height: 3px;"></td>
									    						</tr>
									    						<tr>
									    							<td style="color: #366784; font-size: 11px;"><?php echo $this->lang->line('wetherby_ls232'); ?>.</td>
									    						</tr>
									    					</tbody>
									    				</table>
					                                </td>
					                                <td width="25%" valign="bottom" align="left">
					                                	<table width="100%" cellspacing="0" cellpadding="0" border="0">
									    					<tbody>
									    						<tr>
									    							<td style="color: #366784; font-size: 11px;"><?php echo $this->lang->line('01494_649979'); ?></td>
									    						</tr>
									    						<tr>
									    							<td style="height: 5px; line-height: 5px;"></td>
									    						</tr>
									    						<tr>
									    							<td style="color: #366784; font-size: 11px;"><?php echo $this->lang->line('contact_email'); ?></td>
									    						</tr>
									    					</tbody>
									    				</table>
					                                </td>
					                                <td width="18%" valign="bottom" align="center">
					                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
									    					<tbody>
									    						<tr>
									    							<td width="100%">
									    								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									    									<tr>
									    										<td width="33%">
									    											<img src="<?php echo base_url(); ?>assets/images/footer-social-link-1.png" width="20">
									    										</td>
									    										<td width="33%">
									    											<img src="<?php echo base_url(); ?>assets/images/footer-social-link-2.png" width="20">
									    										</td>
									    										<td width="33%">
									    											<img src="<?php echo base_url(); ?>assets/images/footer-social-link-3.png" width="20">
									    										</td>
									    									</tr>
									    								</table>
									    							</td>
									    						</tr>
									    						<tr>
									    							<td style="line-height: 15px; height: 15px;"></td>
									    						</tr>
									    						<tr>
									    							<td style="font-size:15px; color: #366784;"><b><?php echo $this->lang->line('nextmune_com'); ?></b></td>
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
	</body>
</html>