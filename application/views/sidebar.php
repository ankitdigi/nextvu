<?php $userData = logged_in_user_data(); ?>
<div class="dashboard_logo">
	<img class="desktop-logo" src="<?php echo base_url(); ?>assets/images/nextmune-logo.svg" alt="<?php echo SITE_NAME; ?>" />
	<img class="mobile-logo" src="<?php echo base_url(); ?>assets/images/nextmune-small-logo.svg" alt="<?php echo SITE_NAME; ?>" />
</div>
<div class="dashboard_left_navigation">
	<ul>
		<li class="<?php if($this->uri->segment(1)=="dashboard"){ echo 'active'; } ?>">
			<a href="<?php echo site_url('dashboard');?>"><i><img src="<?php echo base_url(); ?>assets/images/ico-dashboard.svg" alt="<?php echo SITE_NAME; ?>" /></i> <span><?php echo $this->lang->line('Dashboard'); ?></span></a>
		</li>
		<?php if($userData['role']==1 || $userData['role']==2 || $userData['role']==5 || $userData['role']==6){ ?>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="orders" || $this->uri->segment(1)=="invoices"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i><img src="<?php echo base_url(); ?>assets/images/ico-orders.svg" alt="Orders Ic6n" /></i> <span><?php echo $this->lang->line('Orders_Management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<li class="<?php if($this->uri->segment(1)=="orders" && $this->uri->segment(2)=="add"){ echo 'active'; } ?>"><a href="<?php echo site_url('orders/add');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Place_Order'); ?></a></li>
					<li class="<?php if($this->uri->segment(1)=="orders" && $this->uri->segment(2)==""){ echo 'active'; } ?>"><a href="<?php echo site_url('orders');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('All_Orders'); ?></a></li>
					<?php if($userData['role']==1){ ?>
					<li class="<?php if($this->uri->segment(1)=="invoices"){ echo 'active'; } ?>"><a href="<?php echo site_url('invoices');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Invoices'); ?></a></li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>
		<?php if($userData['role']==1 && $userData['is_admin']==0){ ?>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="ImportOrders" || $this->uri->segment(1)=="reportPractices" || $this->uri->segment(1)=="reportDetailPractices" || $this->uri->segment(1)=="reportLabs" || $this->uri->segment(1)=="getLIMSResults"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i class="fa fa-flag-checkered" aria-hidden="true"></i> <span><?php echo $this->lang->line('Reports_Management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<li class="<?php if($this->uri->segment(1)=="serumTestsExport"){echo 'active';} ?>">
						<a href="<?php echo site_url('serumTestsExport');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Serum_Tests_Export'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="reportPractices"){echo 'active';} ?>">
						<a href="<?php echo site_url('reportPractices');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices_Report'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="reportDetailPractices"){echo 'active';} ?>">
						<a href="<?php echo site_url('reportDetailPractices');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices_Detail_Report'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="reportLabs"){echo 'active';} ?>">
						<a href="<?php echo site_url('reportLabs');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Labs_Report'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="getLIMSResults"){echo 'active';} ?>">
						<a href="<?php echo site_url('getLIMSResults');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('LIMS_API_Run'); ?></a>
					</li>
				</ul>
			</li>
		<?php } ?>
		<?php if($userData['role']==10){ ?>
		<li class="<?php if($this->uri->segment(1)=="orders" && $this->uri->segment(2)==""){ echo 'active'; } ?>"><a href="<?php echo site_url('orders');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('All_Orders'); ?></a></li>
		<?php /* <li class="<?php if($this->uri->segment(1)=="importPetowners" && $this->uri->segment(2)==""){ echo 'active'; } ?>"><a href="<?php echo site_url('ImportExportExcel/importPetowners');?>"><i class="fa fa-circle-o"></i> Import Petowners</a></li>
		<li class="<?php if($this->uri->segment(1)=="importPets" && $this->uri->segment(2)==""){ echo 'active'; } ?>"><a href="<?php echo site_url('ImportExportExcel/importPets');?>"><i class="fa fa-circle-o"></i> Import Pets</a></li> */ ?>
		<?php } ?>
		<?php if(($userData['role']==1 && $userData['is_admin']==0) || $userData['role']==3 || $userData['role']==5){ ?>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="vet_lab_users" || $this->uri->segment(1)=="pet_owners" || $this->uri->segment(1)=="pets" || $this->uri->segment(1)=="customer_users" || $this->uri->segment(1)=="country_users" || $this->uri->segment(1)=="tm_users" || $this->uri->segment(1)=="lims_users" || $this->uri->segment(1)=="labs" || $this->uri->segment(1)=="corporates" || $this->uri->segment(1)=="buying_groups" || $this->uri->segment(1)=="referrals"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i><img src="<?php echo base_url(); ?>assets/images/ico-users.svg" alt="Users Icon" /></i> <span><?php echo $this->lang->line('Users_management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<?php if($userData['role']==1 && $userData['is_admin']==0){ ?>
					<li class="<?php if($this->uri->segment(1)=="country_users"){ echo 'active'; } ?>">
						<a href="<?php echo site_url('country_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Country_Admin_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="customer_users"){ echo 'active'; } ?>">
						<a href="<?php echo site_url('customer_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Customer_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="tm_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('tm_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Territory_Managers'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="lims_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('lims_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('LIMS_Users'); ?></a>
					</li>
					<?php }elseif($userData['role']==5){ ?>
					<li class="<?php if($this->uri->segment(1)=="customer_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('customer_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Customer_Users'); ?></a>
					</li>
					<?php } ?>
					<?php if($userData['role']==1 && $userData['is_admin']==0 || ($userData['role']==5 && $userData['user_type']==3)){ ?>
					<li class="<?php if($this->uri->segment(1)=="vet_lab_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('vet_lab_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices'); ?></a>
					</li>
					<?php } ?>
					<?php if($userData['role']==1 && $userData['is_admin']==0){ ?>
						<li class="<?php if($this->uri->segment(1)=="labs"){echo 'active';} ?>">
							<a href="<?php echo site_url('labs');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Labs'); ?></a>
						</li>
						<li class="<?php if($this->uri->segment(1)=="corporates"){echo 'active';} ?>">
							<a href="<?php echo site_url('corporates');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Corporates'); ?></a>
						</li>
						<li class="<?php if($this->uri->segment(1)=="buying_groups"){echo 'active';} ?>">
							<a href="<?php echo site_url('buying_groups');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Buying_Groups'); ?></a>
						</li>
						<li class="<?php if($this->uri->segment(1)=="referrals"){echo 'active';} ?>">
							<a href="<?php echo site_url('referrals');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Referral_Practices'); ?></a>
						</li>
					<?php } ?>
					<?php if($userData['role']==1 && $userData['is_admin']==0){ ?>
						<li class="<?php if($this->uri->segment(1)=="pet_owners"){echo 'active';} ?>">
							<a href="<?php echo site_url('pet_owners');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Pet_Owners'); ?></a>
						</li>
					<?php } ?>
					<?php if(($userData['role']==1 && $userData['is_admin']==0) || $userData['role']==3){ ?>
						<li class="<?php if($this->uri->segment(1)=="pets"){echo 'active';} ?>">
							<a href="<?php echo site_url('pets');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Pets'); ?></a>
						</li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>
		<?php if($userData['role']==1 && $userData['is_admin']==0){ ?>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="allergens" || $this->uri->segment(1)=="sub_allergens" || $this->uri->segment(1)=="breeds" || $this->uri->segment(1)=="species" || $this->uri->segment(1)=="recipients" || $this->uri->segment(1)=="staff_members" || $this->uri->segment(1)=="price_categories" || $this->uri->segment(1)=="price_sub_categories" || $this->uri->segment(1)=="shipping" || $this->uri->segment(1)=="countries" || $this->uri->segment(1)=="staff_countries" || $this->uri->segment(1)=="admin_users" || $this->uri->segment(1)=="managed_by"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i><img src="<?php echo base_url(); ?>assets/images/ico-settings.svg" alt="Settings Icon" /></i> <span><?php echo $this->lang->line('Settings'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<li class="<?php if($this->uri->segment(1)=="allergens"){echo 'active';} ?>">
						<a href="<?php echo site_url('allergens');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Allergen_Groups'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="sub_allergens"){echo 'active';} ?>">
						<a href="<?php echo site_url('sub_allergens');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Allergens'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="breeds"){echo 'active';} ?>">
						<a href="<?php echo site_url('breeds');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Breeds'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="species"){echo 'active';} ?>">
						<a href="<?php echo site_url('species');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Species'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="recipients"){echo 'active';} ?>">
						<a href="<?php echo site_url('recipients');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Artuvetrin_Recipients'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="price_categories"){echo 'active';} ?>">
						<a href="<?php echo site_url('price_categories');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Order_Types'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="price_sub_categories"){echo 'active';} ?>">
						<a href="<?php echo site_url('price_sub_categories');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Product_Management'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="countries"){echo 'active';} ?>">
						<a href="<?php echo site_url('countries');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Nextmune_Staff_Countries'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="staff_countries"){echo 'active';} ?>">
						<a href="<?php echo site_url('staff_countries');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Countries'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="admin_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('admin_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Admin_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="managed_by"){echo 'active';} ?>">
						<a href="<?php echo site_url('managed_by');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Zones_Management'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="shipping"){ echo 'active'; } ?>">
						<a href="<?php echo site_url('shipping');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Shipping_Price_Management'); ?></a>
					</li>
				</ul>
			</li>
		<?php } ?>
		
		<?php 
		if(isset($this->zones) && !empty($this->zones)){
			$zoneby = explode(",",$this->zones);
		}else{
			$zoneby = array();
		}
		if($userData['role']==11){ ?>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="orders"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i><img src="<?php echo base_url(); ?>assets/images/ico-orders.svg" alt="Orders Ic6n" /></i> <span><?php echo $this->lang->line('Orders_Management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<li class="<?php if($this->uri->segment(1)=="orders" && $this->uri->segment(2)=="add"){ echo 'active'; } ?>"><a href="<?php echo site_url('orders/add');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Place_Order'); ?></a></li>
					<li class="<?php if($this->uri->segment(1)=="orders" && $this->uri->segment(2)==""){ echo 'active'; } ?>"><a href="<?php echo site_url('orders');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('All_Orders'); ?></a></li>
				</ul>
			</li>
			<li class="has-dropdown <?php if($this->uri->segment(1)=="country_users" || $this->uri->segment(1)=="tm_users" || $this->uri->segment(1)=="vet_lab_users" || $this->uri->segment(1)=="labs" || $this->uri->segment(1)=="customer_users"){ echo 'active'; } ?>">
				<a href="javascript:void(0);"><i><img src="<?php echo base_url(); ?>assets/images/ico-users.svg" alt="Users Icon" /></i> <span> <?php echo $this->lang->line('Users_management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
				<ul class="sub-menus">
					<li class="<?php if($this->uri->segment(1)=="country_users"){ echo 'active'; } ?>">
						<a href="<?php echo site_url('country_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Country_Admin_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="customer_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('customer_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Customer_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="tm_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('tm_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Sales_Users'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="vet_lab_users"){echo 'active';} ?>">
						<a href="<?php echo site_url('vet_lab_users');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices'); ?></a>
					</li>
					<li class="<?php if($this->uri->segment(1)=="labs"){echo 'active';} ?>">
						<a href="<?php echo site_url('labs');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Labs'); ?></a>
					</li>
				</ul>
			</li>
			<?php if(empty($zoneby) || in_array("1", $zoneby)){ ?>
				<li class="has-dropdown <?php if($this->uri->segment(1)=="getLIMSResults" || $this->uri->segment(1)=="reportPractices" || $this->uri->segment(1)=="reportLabs"){ echo 'active'; } ?>">
					<a href="javascript:void(0);"><i class="fa fa-flag-checkered" aria-hidden="true"></i> <span><?php echo $this->lang->line('Reports_Management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
					<ul class="sub-menus">
						<?php
						if (in_array("1", $zoneby) && count($zoneby) == 1) {
						?>
						<li class="<?php if($this->uri->segment(1)=="getLIMSResults"){echo 'active';} ?>">
							<a href="<?php echo site_url('getLIMSResults');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('LIMS_API_Run'); ?></a>
						</li>
						<?php
						}
						?>
						<li class="<?php if($this->uri->segment(1)=="reportPractices"){echo 'active';} ?>">
							<a href="<?php echo site_url('reportPractices');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices_Report'); ?></a>
						</li>
						<li class="<?php if($this->uri->segment(1)=="reportLabs"){echo 'active';} ?>">
							<a href="<?php echo site_url('reportLabs');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Labs_Report'); ?></a>
						</li>
					</ul>
				</li>
			<?php }else{ ?>
				<li class="has-dropdown <?php if($this->uri->segment(1)=="reportPractices" || $this->uri->segment(1)=="reportLabs"){ echo 'active'; } ?>">
					<a href="javascript:void(0);"><i class="fa fa-flag-checkered" aria-hidden="true"></i> <span><?php echo $this->lang->line('Reports_Management'); ?></span><i class="fa-solid fa-chevron-right toggle-child"></i></a>
					<ul class="sub-menus">
						<li class="<?php if($this->uri->segment(1)=="reportPractices"){echo 'active';} ?>">
							<a href="<?php echo site_url('reportPractices');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Practices_Report'); ?></a>
						</li>
						<li class="<?php if($this->uri->segment(1)=="reportLabs"){echo 'active';} ?>">
							<a href="<?php echo site_url('reportLabs');?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line('Labs_Report'); ?></a>
						</li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>
	</ul>
</div>
