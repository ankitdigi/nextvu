<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$userData = logged_in_user_data();

$fetchClass = $this->router->fetch_class();
$fetchMethod = $this->router->fetch_method();
if($fetchClass == 'Dashboard' || $fetchClass == 'dashboard'){
	$pageTitle = $this->lang->line('Dashboard');
}elseif($fetchClass == 'Orders' || $fetchClass == 'orders'){
	if($fetchMethod == 'orderType'){
		$pageTitle = $this->lang->line('Order_Type');
	}elseif($fetchMethod == 'species_selection'){
		$pageTitle = $this->lang->line('Order_Species');
	}elseif($fetchMethod == 'plc_selection'){
		$pageTitle = $this->lang->line('Order_Practice_Lab');
	}elseif($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Order_Details');
	}elseif($fetchMethod == 'allergens'){
		$pageTitle = $this->lang->line('Order_Allergens');
	}elseif($fetchMethod == 'vials'){
		$pageTitle = $this->lang->line('Order_Vials');
	}elseif($fetchMethod == 'summary'){
		$pageTitle = $this->lang->line('Order_Summary');
	}elseif($fetchMethod == 'product_code_selection'){
		$pageTitle = $this->lang->line('Order_Product_Code');
	}elseif($fetchMethod == 'serum_request'){
		$pageTitle = $this->lang->line('Serum_Request');
	}else{
		$pageTitle = $this->lang->line('Orders');
	}
}elseif($fetchClass == 'Invoices' || $fetchClass == 'invoices'){
	$pageTitle = $this->lang->line('Invoices');
}elseif($fetchClass == 'Users' || $fetchClass == 'users'){
	if($fetchMethod == 'customer_users_list'){
		$pageTitle = $this->lang->line('Customer_Users');
	}elseif($fetchMethod == 'customer_users_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Customer_Users');
	}elseif($fetchMethod == 'tm_users_list'){
		$pageTitle = $this->lang->line('Territory_Managers');
	}elseif($fetchMethod == 'tm_users_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Territory_Managers');
	}elseif($fetchMethod == 'admin_users_list'){
		$pageTitle = $this->lang->line('Admin_Users');
	}elseif($fetchMethod == 'admin_users_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Admin_Users');
	}elseif($fetchMethod == 'profile'){
		$pageTitle = $this->lang->line('Update_Profile');
	}elseif($fetchMethod == 'lims_users_list'){
		$pageTitle = $this->lang->line('LIMS_Users');
	}elseif($fetchMethod == 'lims_users_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('LIMS_Users');
	}elseif($fetchMethod == 'country_users_list'){
		$pageTitle = $this->lang->line('Country_Admin_Users');
	}elseif($fetchMethod == 'country_users_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Country_Admin_Users');
	}else{
		$pageTitle = $this->lang->line('Users');
	}
}elseif($fetchClass == 'UsersDetails' || $fetchClass == 'usersDetails'){
	if($fetchMethod == 'vetLabUsers'){
		$pageTitle = $this->lang->line('Practices');
	}elseif($fetchMethod == 'vlu_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Practices');
	}elseif($fetchMethod == 'labs'){
		$pageTitle = $this->lang->line('Labs');
	}elseif($fetchMethod == 'labs_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Labs');
	}elseif($fetchMethod == 'corporates'){
		$pageTitle = $this->lang->line('Corporates');
	}elseif($fetchMethod == 'corporates_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Corporates');
	}elseif($fetchMethod == 'buying_groups'){
		$pageTitle = $this->lang->line('Buying_Groups');
	}elseif($fetchMethod == 'buying_groups_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Buying_Groups');
	}elseif($fetchMethod == 'referrals'){
		$pageTitle = $this->lang->line('Referral_Practices');
	}elseif($fetchMethod == 'referrals_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Referral_Practices');
	}elseif($fetchMethod == 'petOwners'){
		$pageTitle = $this->lang->line('Pet_Owners');
	}elseif($fetchMethod == 'petOwners_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Pet_Owners');
	}
}elseif($fetchClass == 'Pets' || $fetchClass == 'pets'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Pets');
	}else{
		$pageTitle = $this->lang->line('Pets');
	}
}elseif($fetchClass == 'Breeds' || $fetchClass == 'breeds'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Breeds');
	}else{
		$pageTitle = $this->lang->line('Breeds');
	}
}elseif($fetchClass == 'Species' || $fetchClass == 'species'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Species');
	}else{
		$pageTitle = $this->lang->line('Species');
	}
}elseif($fetchClass == 'Recipients' || $fetchClass == 'recipients'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Artuvetrin_Recipients');
	}else{
		$pageTitle = $this->lang->line('Artuvetrin_Recipients');
	}
}elseif($fetchClass == 'PriceCategories' || $fetchClass == 'priceCategories'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Order_Type');
	}elseif($fetchMethod == 'sub_list'){
		$pageTitle = $this->lang->line('Product_Management');
	}elseif($fetchMethod == 'sub_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Product_Management');
	}else{
		$pageTitle = $this->lang->line('Order_Types');
	}
}elseif($fetchClass == 'Countries' || $fetchClass == 'countries'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Staff_Countries');
	}else{
		$pageTitle = $this->lang->line('Staff_Countries');
	}
}elseif($fetchClass == 'StaffCountries' || $fetchClass == 'staffCountries'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Countries');
	}else{
		$pageTitle = $this->lang->line('Countries');
	}
}elseif($fetchClass == 'ShippingPrice' || $fetchClass == 'shippingPrice'){
	if($fetchMethod == 'shipping_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Shipping_Prices');
	}else{
		$pageTitle = $this->lang->line('Shipping_Price_Management');
	}
}elseif($fetchClass == 'Allergens' || $fetchClass == 'allergens'){
	if($fetchMethod == 'sub_list'){
		$pageTitle = $this->lang->line('Allergens');
	}elseif($fetchMethod == 'sub_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Allergens');
	}elseif($fetchMethod == 'list'){
		$pageTitle = $this->lang->line('Allergen_Groups');
	}elseif($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Allergen_Groups');
	}else{
		$pageTitle = $this->lang->line('Allergens');
	}
}elseif($fetchClass == 'ExpandOrder' || $fetchClass == 'expandOrder'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Expand_Results_Details');
	}elseif($fetchMethod == 'allergens'){
		$pageTitle = $this->lang->line('Expand_Results_Allergens');
	}elseif($fetchMethod == 'summary'){
		$pageTitle = $this->lang->line('Expand_Results_Summary');
	}else{
		$pageTitle = $this->lang->line('Expand_Results');
	}
}elseif($fetchClass == 'RepeatOrder' || $fetchClass == 'repeatOrder'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Repeat_Order_Details');
	}elseif($fetchMethod == 'allergens'){
		$pageTitle = $this->lang->line('Repeat_Order_Allergens');
	}elseif($fetchMethod == 'vials'){
		$pageTitle = $this->lang->line('Repeat_Order_Vials');
	}elseif($fetchMethod == 'summary'){
		$pageTitle = $this->lang->line('Repeat_Order_Summary');
	}elseif($fetchMethod == 'serum_request'){
		$pageTitle = $this->lang->line('Repeat_Order_Serum_Request');
	}else{
		$pageTitle = $this->lang->line('Repeat_Orders');
	}
}elseif($fetchClass == 'Reports' || $fetchClass == 'reports'){
	if($fetchMethod == 'reportPractices'){
		$pageTitle = $this->lang->line('Practices_Report');
	}elseif($fetchMethod == 'reportLabs'){
		$pageTitle = $this->lang->line('Labs_Report');
	}else{
		$pageTitle = $this->lang->line('Reports');
	}
}elseif($fetchClass == 'ReportLabs' || $fetchClass == 'reportLabs'){
	$pageTitle = $this->lang->line('Labs_Report');
}elseif($fetchClass == 'ReportPractices' || $fetchClass == 'reportPractices'){
	$pageTitle = $this->lang->line('Practices_Report');
}elseif($fetchClass == 'StaffMembers' || $fetchClass == 'staffMembers'){
	if($fetchMethod == 'addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Staff_Members');
	}elseif($fetchMethod == 'list'){
		$pageTitle = $this->lang->line('Staff_Members');
	}elseif($fetchMethod == 'managed_by_list'){
		$pageTitle = $this->lang->line('Zones_Management');
	}elseif($fetchMethod == 'managed_by_addEdit'){
		$pageTitle = $this->lang->line('Add').'/'.$this->lang->line('Edit').' '.$this->lang->line('Zones_Management');
	}else{
		$pageTitle = $this->lang->line('Staff_Members');
	}
}elseif($fetchClass == 'ImportOrders' || $fetchClass == 'importOrders'){
	$pageTitle = 'Import Idexx Orders';
}elseif($fetchClass == 'LimsAPI' || $fetchClass == 'LimsAPI'){
	$pageTitle = 'LIMS API\'s Run';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="<?php echo base_url(FAVICON_ICON); ?>" type="image" sizes="16x16"> 
		<title><?php echo FAVICON_NAME; ?><?php echo $pageTitle; ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon_icon.png" type="image" sizes="16x16">
		<?php $this->load->view("style"); ?>
		<style>
		.loader{position:fixed;top:0;height:100%;width:100%;background:rgba(0,0,0,0.5);text-align:center;display:flex;align-items:center; justify-content:center;z-index:99999}
		.loader-ele{border:4px solid #f3f3f3;border-top:4px solid #000;border-radius:50%;width:50px;height:50px;animation:spin 1s linear infinite}
		</style>
	</head>
	<body class="half">
		<div class="loader" style="display: none;">
			<div class="loader-ele"></div>
		</div>
		<div class="dashboard_container">
			<div class="dashboard_top_bar">
				<h1><?php echo $this->lang->line('Control_Panel'); ?></h1>
				<div class="account-area">
					<select onchange="javascript:window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/'+this.value;" class="form-control">
						<option value="english" <?php if($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
						<option value="danish" <?php if($this->session->userdata('site_lang') == 'danish') echo 'selected="selected"'; ?>>Danish</option>
						<option value="french" <?php if($this->session->userdata('site_lang') == 'french') echo 'selected="selected"'; ?>>French</option>
						<option value="german" <?php if($this->session->userdata('site_lang') == 'german') echo 'selected="selected"'; ?>>German</option>
						<option value="italian" <?php if($this->session->userdata('site_lang') == 'italian') echo 'selected="selected"'; ?>>Italian</option>
						<option value="dutch" <?php if($this->session->userdata('site_lang') == 'dutch') echo 'selected="selected"'; ?>>Dutch</option>
						<option value="norwegian" <?php if($this->session->userdata('site_lang') == 'norwegian') echo 'selected="selected"'; ?>>Norwegian</option>
						<option value="spanish" <?php if($this->session->userdata('site_lang') == 'spanish') echo 'selected="selected"'; ?>>Spanish</option>
						<option value="swedish" <?php if($this->session->userdata('site_lang') == 'swedish') echo 'selected="selected"'; ?>>Swedish</option>
					</select>
				</div>
				<div class="account-area">
					<div class="user-account">
						<div class="account-avataar"><img src="<?php echo base_url(); ?>assets/images/ico-users.svg" alt="User Icon" /></div>
						<div class="account-name"><?php echo $userData['name']; ?></div>
					</div>
					<ul>
						<li>
							<?php if( $userData['role'] == '6' ){ ?>
								<a href="<?php echo base_url('labs/edit/').$userData['user_id']; ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php }elseif( $userData['role'] == '2' ){ ?>
								<a href="<?php echo base_url('vet_lab_users/edit/').$userData['user_id']; ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php }elseif( $userData['role'] == '3' ){ ?>
								<a href="<?php echo base_url('pet_owners/edit/').$userData['user_id']; ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php }elseif( $userData['role'] == '7' ){ ?>
								<a href="<?php echo base_url('corporates/edit/').$userData['user_id']; ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php }elseif( $userData['role'] == '5' ){ ?>
								<a href="<?php echo base_url('tm_users/edit/').$userData['user_id']; ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php }else{ ?>
								<a href="<?php echo base_url('users/profile'); ?>"><i class="fa-solid fa-user"></i> <?php echo $this->lang->line('My_Account'); ?></a>
							<?php } ?>
						</li>
						<li><a href="<?php echo base_url('users/logout') ?>"><i class="fa-solid fa-arrow-right-from-bracket"></i> <?php echo $this->lang->line('Logout'); ?></a></li>
					</ul>
				</div>
			</div>
			<div class="dashboard_left_panel">
				<?php $this->load->view("sidebar"); ?>
			</div>
			<div class="dashboard_right_panel">
				<div class="dashboard_header">
					<div class="menu_trigger">
						<span></span>
						<span></span>
						<span></span>
					</div>
					<div class="header-right-area">
						<h1 class="page_title"><img src="<?php echo base_url(); ?>assets/images/ico-dashboard.svg" alt="Dashboard Icon" /> <?php echo $pageTitle; ?></h1>
					</div>
				</div>
				<div class="dashboard_content"></div>
			</div>