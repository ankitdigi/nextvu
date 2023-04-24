<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'users/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['users/registration-form'] = 'Users/registration_form';

$route['vet_lab_users'] = 'UsersDetails/vetLabUsers';
$route['vet_lab_users/add'] = 'UsersDetails/vlu_addEdit';
$route['vet_lab_users/edit/(:num)'] = 'UsersDetails/vlu_addEdit/$1';

$route['pet_owners'] = 'UsersDetails/petOwners';
$route['pet_owners/add'] = 'UsersDetails/petOwners_addEdit';
$route['pet_owners/edit/(:num)'] = 'UsersDetails/petOwners_addEdit/$1';

$route['pets'] = 'Pets/list';
$route['pets/add'] = 'Pets/addEdit';
$route['pets/edit/(:num)'] = 'Pets/addEdit/$1';

$route['orders'] = 'Orders/list';
$route['orders/(:num)'] = 'Orders/list/$1';
$route['orders/add'] = 'Orders/orderType';
$route['orders/edit/(:num)'] = 'Orders/orderType/$1';
$route['orders/send_mail/(:num)'] = 'Orders/send_mail/$1';
$route['orders/track_order/(:num)'] = 'Orders/track_order/$1';
$route['orders/modify-excel/(:num)'] = 'Orders/modifyExcel/$1';

$route['repeatOrder/repeat_order/(:num)'] = 'RepeatOrder/orderType/$1';

$route['tm_users'] = 'Users/tm_users_list';
$route['tm_users/add'] = 'Users/tm_users_addEdit';
$route['tm_users/edit/(:num)'] = 'Users/tm_users_addEdit/$1';

$route['customer_users'] = 'Users/customer_users_list';
$route['customer_users/add'] = 'Users/customer_users_addEdit';
$route['customer_users/edit/(:num)'] = 'Users/customer_users_addEdit/$1';

$route['labs'] = 'UsersDetails/labs';
$route['labs/add'] = 'UsersDetails/labs_addEdit';
$route['labs/edit/(:num)'] = 'UsersDetails/labs_addEdit/$1';

$route['corporates'] = 'UsersDetails/corporates';
$route['corporates/add'] = 'UsersDetails/corporates_addEdit';
$route['corporates/edit/(:num)'] = 'UsersDetails/corporates_addEdit/$1';

$route['buying_groups'] = 'UsersDetails/buying_groups';
$route['buying_groups/add'] = 'UsersDetails/buying_groups_addEdit';
$route['buying_groups/edit/(:num)'] = 'UsersDetails/buying_groups_addEdit/$1';

$route['referrals'] = 'UsersDetails/referrals';
$route['referrals/add'] = 'UsersDetails/referrals_addEdit';
$route['referrals/edit/(:num)'] = 'UsersDetails/referrals_addEdit/$1';

$route['allergens'] = 'Allergens/list';
$route['allergens/add'] = 'Allergens/addEdit';
$route['allergens/edit/(:num)'] = 'Allergens/addEdit/$1';

$route['sub_allergens'] = 'Allergens/sub_list';
$route['sub_allergens/add'] = 'Allergens/sub_addEdit';
$route['sub_allergens/edit/(:num)'] = 'Allergens/sub_addEdit/$1';

$route['breeds'] = 'Breeds/list';
$route['breeds/add'] = 'Breeds/addEdit';
$route['breeds/edit/(:num)'] = 'Breeds/addEdit/$1';

$route['species'] = 'Species/list';
$route['species/add'] = 'Species/addEdit';
$route['species/edit/(:num)'] = 'Species/addEdit/$1';

$route['recipients'] = 'Recipients/list';
$route['recipients/add'] = 'Recipients/addEdit';
$route['recipients/edit/(:num)'] = 'Recipients/addEdit/$1';

$route['staff_members'] = 'StaffMembers/list';
$route['staff_members/add'] = 'StaffMembers/addEdit';
$route['staff_members/edit/(:num)'] = 'StaffMembers/addEdit/$1';

$route['price_categories'] = 'PriceCategories/list';
$route['price_categories/add'] = 'PriceCategories/addEdit';
$route['price_categories/edit/(:num)'] = 'PriceCategories/addEdit/$1';

$route['price_sub_categories'] = 'PriceCategories/sub_list';
$route['price_sub_categories/add'] = 'PriceCategories/sub_addEdit';
$route['price_sub_categories/edit/(:num)'] = 'PriceCategories/sub_addEdit/$1';

$route['countries'] = 'Countries/list';
$route['countries/add'] = 'Countries/addEdit';
$route['countries/edit/(:num)'] = 'Countries/addEdit/$1';

$route['staff_countries'] = 'staffCountries/list';
$route['staff_countries/add'] = 'staffCountries/addEdit';
$route['staff_countries/edit/(:num)'] = 'staffCountries/addEdit/$1';

$route['admin_users'] = 'Users/admin_users_list';
$route['admin_users/add'] = 'Users/admin_users_addEdit';
$route['admin_users/edit/(:num)'] = 'Users/admin_users_addEdit/$1';

$route['shipping'] = 'ShippingPrice/shipping_list';
$route['shipping/add'] = 'ShippingPrice/shipping_addEdit';
$route['shipping/edit/(:num)'] = 'ShippingPrice/shipping_addEdit/$1';

$route['lims_users'] = 'Users/lims_users_list';
$route['lims_users/add'] = 'Users/lims_users_addEdit';
$route['lims_users/edit/(:num)'] = 'Users/lims_users_addEdit/$1';

$route['ImportOrders']			= 'ImportOrders/import_data';
$route['reportPractices']		= 'Reports/report_practices';
$route['reportDetailPractices']	= 'Reports/report_detail_practices';
$route['reportLabs']			= 'Reports/report_labs';
$route['serumTestsExport']		= 'Reports/serumTestsExport';

$route['upload_pdf_image_head']	= 'Orders/upload_pdf_image_head';
$route['upload_pdf_image']		= 'Orders/upload_pdf_image';
$route['upload_pdf_image2']		= 'Orders/upload_pdf_image2';
$route['upload_pdf_image3']		= 'Orders/upload_pdf_image3';

$route['managed_by'] = 'StaffMembers/managed_by_list';
$route['managed_by/add'] = 'StaffMembers/managed_by_addEdit';
$route['managed_by/edit/(:num)'] = 'StaffMembers/managed_by_addEdit/$1';

$route['country_users'] = 'Users/country_users_list';
$route['country_users/add'] = 'Users/country_users_addEdit';
$route['country_users/edit/(:num)'] = 'Users/country_users_addEdit/$1';

$route['getLIMSResults'] = 'LimsAPI/getLIMSResults';
$route['sendOrderstoLIMS'] = 'LimsAPI/sendOrderstoLIMS';

// $route['ImportOrders'] = 'ImportOrders/import_data';
// $route['ImportCategory'] = 'ImportCategory/import_data';
// $route['ImportSubCategory'] = 'ImportSubCategory/import_data';
// $route['ImportPetOwners'] = 'ImportPetOwners/import_data';
// $route['ImportPets'] = 'ImportPets/import_data';
// $route['ImportFinalOrders'] = 'ImportFinalOrders/import_data';
//$route['ImportPractices'] = 'ImportPractices/import_data';
//$route['ImportPetO'] = 'ImportPetO/import_data';
//$route['ImportP'] = 'ImportP/import_data';
//$route['ImportOrdrs'] = 'ImportOrdrs/import_data';
//$route['ImportPrice'] = 'ImportPrice/import_data';
//$route['ImportPetOVetru'] = 'ImportPetOVetru/import_data';
//$route['ImportAllPet'] = 'ImportAllPet/import_data';
