<!DOCTYPE html>
<html class="js">
	<head>
		<title><?php echo $this->lang->line('interpreting_nextlab'); ?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
		<style>
		@page {margin:0mm;}
		body{margin: 0px;}
		*{font-family:'Open Sans',sans-serif}
		table th{text-align: left; font-weight: 400;}
		.filled-checkbox{display: none;}
		</style>
	</head>
	<body bgcolor="#fff">
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
														<p style="font-size: 32px;text-transform: uppercase;color: #ffffff;font-weight: 400; margin: 0; letter-spacing: 2px; line-height: 38px; white-space:nowrap;"><?php echo $this->lang->line('serum_test'); ?><br> <?php echo $this->lang->line('request_form'); ?></p>
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
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 8mm 8mm 2mm;">
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
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('date'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="65%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('veterinary_surgeon'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('Veterinary_practice'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('practice_details'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
												<td width="63%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('city'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="35%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('postcode'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('phone'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('email'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height: 5px; line-height: 5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('results_will_be_delivered_by_email'); ?></p>
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
																<td width="4%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td>
																					<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																					<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
																<td width="2%">&nbsp;</td>
																<td width="94%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td style="color:#1c3642; font-size:12px;">
																				<?php echo $this->lang->line('serum_test_shipping_materials'); ?>	
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
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tbody>
				<tr>
					<td style="height: 25px;"></td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="padding: 0 8mm">
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
													<td style="color:#426e89; letter-spacing:1px; font-size:20px;"><?php echo $this->lang->line('animal_and_owner_details'); ?></td>
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
			<tr>
				<td width="49%" valign="top">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="middle" width="18%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('dog'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="middle" width="18%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('cat'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="middle" width="18%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('horse'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="middle" width="18%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('male'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="33%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="middle">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tr>
																			<td valign="middle" width="18%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('female'); ?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="33%" valign="middle"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:10px"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('Owner_name'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
												<td style="height:10px;"></td>
											</tr>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('animal_name'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
												<td style="height:10px;"></td>
											</tr>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('breed'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
												<td style="height:10px;"></td>
											</tr>
											<tr>
												<td width="57%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('date_of_birth'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width="2%">&nbsp;</td>
												<td width="40%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td width="100%" valign="top">
																	<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('date_serum_drawn'); ?>:</p>
																</td>
															</tr>
															<tr>
																<td style="height: 5px; line-height: 5px;"></td>
															</tr>
															<tr>
																<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
								<td width="100%" style="height: 10px;"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
															<?php echo $this->lang->line('zoonotic_disease'); ?>	
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="33%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="18%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('yes'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="18%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('no'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%" valign="middle"></td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td width="100%" style="height: 5px;">
																
															</td>
														</tr>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" valign="top">
																				<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('if_yes_please_specify'); ?>:</p>
																			</td>
																		</tr>
																		<tr>
																			<td style="height:5px; line-height:5px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"></td>
																		</tr>
																	</tbody>
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
				<td width="2%">&nbsp;</td>
				<td width="49%" valign="top">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
															<?php echo $this->lang->line('what_are_the_major_presenting_symptoms'); ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('pruritus'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="17%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" valign="middle" width="30%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" valign="middle" width="70%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('otitis'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="27%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="18%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="77%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('respiratory'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="36%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="15%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="82%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('gastrointestinal'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td width="100%" style="height: 5px;"></td>
														</tr>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="25%" valign="middle">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" valign="middle">
																								<table width="100%" cellspacing="0" cellpadding="0" border="0">
																									<tr>
																										<td valign="middle" width="20%">
																											<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																											<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																										</td>
																										<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('other'); ?></td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="75%">
																				<table width="100%" cellspacing="0" cellpadding="0" border="0">
																					<tbody>
																						<tr>
																							<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
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
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:10px"></td>
							</tr>
							<tr>
								<td width="100%" valign="middle">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td style="height: 10px;"></td>
											</tr>
											<tr>
												<td width="100%" valign="top">
													<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('at_what_age_did_these_symptoms_first_appear'); ?>:</p>
												</td>
											</tr>
											<tr>
												<td style="height:5px; line-height:5px;"></td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="60%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 25px; padding:0 10px; font-size:13px;"></td>
															<td width="40%">&nbsp;</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:10px"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td style="height: 10px;"></td>
											</tr>
											<tr>
												<td width="100%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
															<?php echo $this->lang->line('symptoms_most_obvious'); ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('spring'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('summer'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('autumn'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('winter'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="20%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('all_year'); ?></td>
																								</tr>
																							</table>
																						</td>
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
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height:10px"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td style="height: 10px;"></td>
											</tr>
											<tr>
												<td width="100%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
															<?php echo $this->lang->line('where_symptoms_most_obvious'); ?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="33%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('indoors'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('outdoors'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td valign="middle" width="20%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td valign="middle" width="75%" style="color:#1c3642; font-size:12px; line-height: 20px;"><?php echo $this->lang->line('no_difference'); ?></td>
																								</tr>
																							</table>
																						</td>
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
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td style="height: 10px;"></td>
							</tr>
							<tr>
								<td width="100%">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td width="100%" valign="middle">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%" style="color:#1c3642; font-size:13px; line-height: 22px;">
															<?php echo $this->lang->line('medication'); ?>	
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td width="100%">
													<table width="100%" cellspacing="0" cellpadding="0" border="0">
														<tr>
															<td width="100%">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="33%" valign="middle">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td width="18%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('yes'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%">
																			<table width="100%" cellspacing="0" cellpadding="0" border="0">
																				<tbody>
																					<tr>
																						<td width="100%" valign="middle">
																							<table width="100%" cellspacing="0" cellpadding="0" border="0">
																								<tr>
																									<td width="18%">
																										<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																										<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																									</td>
																									<td width="82%" style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('no'); ?></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																		<td width="33%" valign="middle"></td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td width="100%" style="height: 5px;"></td>
														</tr>
														<tr>
															<td width="100%" valign="middle">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td width="100%" valign="top">
																				<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('if_yes_please_specify'); ?>:</p>
																			</td>
																		</tr>
																		<tr>
																			<td style="height:5px; line-height:5px;"></td>
																		</tr>
																		<tr>
																			<td width="100%" style="background:#ebeff1; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"></td>
																		</tr>
																	</tbody>
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
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
			<tbody>
				<tr>
					<td style="height: 25px;"></td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="80%" valign="top">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="100%" valign="middle">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0mm 8mm;">
									<tbody>
										<tr>
											<td width="100%" valign="top">
												<p style="color:#1c3642; font-size:13px; line-height: 22px;"><?php echo $this->lang->line('internal_use_only'); ?></p>
											</td>
										</tr>
										<tr>
											<td style="height:5px; line-height:5px;"></td>
										</tr>
										<tr>
											<td width="100%" style="background:#fff; border:1px solid #5b8398; outline:none; height: 75px; padding:0 10px; font-size:13px;"></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td style="height: 20px;"></td>
						</tr>
						<tr>
							<td width="100%" valign="middle">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #e5f4f7;">
									<tbody>
										<tr>
											<td width="100%" style="padding: 2.5mm 0 2.5mm 8mm;">
												<p style="color:#426e89; font-size:15px; line-height: 22px;"><?php echo $this->lang->line('allergy_resources_visit'); ?></p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>
				</td>
				<td width="20%" valign="bottom">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="100%">
								<img src="assets/images/practice-portal-lock-img.png" width="215">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color: #426e89;">
			<tbody>
				<tr>
					<td width="100%">
						<table class="header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="100%" style="height: 25px;"></td>
								</tr>
								<tr>
									<td width="100%" align="center" valign="middle">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 26px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align: center;"><?php echo $this->lang->line('sample_submission_form'); ?></p>
													</td>
											</tr></tbody>
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
														<p style="font-size: 16px;color: #fff;font-weight: 400; margin: 0; text-transform: uppercase; text-align:center;"><?php echo $this->lang->line('individual_test_box'); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="height: 10px;"></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="padding: 0 12mm;">
			<tbody>
				<tr>
					<td width="100%" style="height: 20px; line-height: 20px;"></td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
							<tbody>
								<tr>
									<td width="25%" align="left" valign="top">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<td>
														<p style="font-size: 20px;color: #426e89;font-weight: 600; margin: 0; text-transform: uppercase;"><?php echo $this->lang->line('storage_only'); ?></p>
													</td>
											</tr></tbody>
										</table>
									</td>
									<td width="75%" align="left" valign="top">
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
																					<td width="5%" valign="top">
																						<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																						<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																					</td>
																					<td valign="top" width="95%" style="color:#000; font-size:13px; text-align: left;">
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
					<td width="100%" style="height: 10px;"></td>
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('comp_env_food_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_penal'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																			<td style="background:#7dc1c9; color:#fff; font-size:24px; text-transform: uppercase;"><?php echo $this->lang->line('feline_tests'); ?></td>
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('complete_env_food_panel'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_panel_print_food'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 11px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_screen_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('complete_env_ins_food_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
																			</td>
																			<td valign="top" width="95%" style="font-size: 12px; line-height: 16px; color:#000;"><?php echo $this->lang->line('env_insect_serum_result'); ?></td>
																		</tr>
																		<tr>
																			<td style="height: 5px;"></td>
																		</tr>
																		<tr>
																			<td valign="top" width="5%">
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
																				<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" />
																				<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" />
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
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
			<tr>
				<td width="100%">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="100%" style="height: 10px;"></td>
						</tr>
						<tr>
							<td width="100%" style="font-size: 10px; line-height: 15px; color: #000;">
							<?php echo $this->lang->line('nextmune_laboratories_serum_result'); ?>
							</td>
						</tr>
						<tr>
							<td width="100%" style="height: 5px;"></td>
						</tr>
						<tr>
							<td width="100%" style="font-size: 10px; line-height: 15px; color: #000;">
							<?php echo $this->lang->line('devlopment_purposes_serum_result'); ?>
							</td>
						</tr>
						<tr>
							<td width="100%">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="60%">
											<table width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td width="85%" style="font-size: 10px; line-height: 15px; color: #000;">
													<?php echo $this->lang->line('nextm_labo_utilise'); ?>
													</td>
													<td width="15%">
														<img class="blank-checkbox" src="<?php echo base_url("/assets/images/serum-blank-checkbox.png"); ?>" alt="NextVu" width="8"/>
														<img class="filled-checkbox" src="<?php echo base_url("/assets/images/serum-filled-checkbox.png"); ?>" alt="NextVu" width="8"/>
													</td>
												</tr>
											</table>
										</td>
										<td width="40%"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="100%" style="height: 10px;"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #426e89; padding: 1.5mm 0;">
			<tr>
				<td width="100%" align="center">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
						<tr>
							<td width="100%">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="100%" style="font-size: 12px; line-height: 20px; color: #fff;">
										<?php echo $this->lang->line('nextm_labo_limited_serum_result'); ?>	
										</td>
									</tr>
									<tr>
										<td width="100%">
											<table width="100%" cellspacing="0" cellpadding="0" border="0">
												<tr>
													<td width="49%" style="font-size: 12px; line-height: 20px; color: #fff; text-align: right;">
													<?php echo $this->lang->line('t_0800_3_047_047'); ?>	
													</td>
													<td width="2%">&nbsp;</td>
													<td width="49%" style="font-size: 12px; line-height: 20px; color: #fff; text-align: left;">
														E – <a href="mailto:vetorders.uk@nextmune.com" style="color: #fff; text-decoration: none;"><?php echo $this->lang->line('contact_email'); ?></a>
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
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="100%" style="height: 5px;"></td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="100%" align="center">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 0 8mm;">
						<tr>
							<td width="100%">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="49%" style="font-size: 8px; line-height: 20px; color: #000; text-align: left;">
											<b>©<?php echo $this->lang->line('netxmune_2022'); ?></b>
										</td>
										<td width="2%">&nbsp;</td>
										<td width="49%" style="font-size: 8px; line-height: 20px; color: #000; text-align: right;">
										<?php echo $this->lang->line('nm035_06_22'); ?>	
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>