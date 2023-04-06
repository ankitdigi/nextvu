<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('logged_in_user_name')) {
    function logged_in_user_data(){
        $userData = [];
        $CI =& get_instance();
        $CI->load->model('UsersModel');  
        $current_user['id'] = $CI->session->userdata('user_id');
        $data = $CI->UsersModel->getUser($current_user);
        $userData['user_id'] = $data->id;
        $userData['name'] = ucwords($data->name);
        $userData['role'] = $data->role;
		$userData['email'] = $data->email;
        $userData['country'] = $data->country;
		$userData['is_admin'] = $data->is_admin;
		$userData['user_type'] = $data->user_type;
		$userData['managed_by_id'] = $data->managed_by_id;
		$userData['preferred_language'] = $data->preferred_language;

        return $userData;
    }
}

if(!function_exists('seo_friendly_url')) {
	function seo_friendly_url($string){
		$string = str_replace(array('[\', \']'), '', $string);
		$string = preg_replace('/\[.*\]/U', '', $string);
		$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_', $string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
		$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '_', $string);
		return ucfirst(trim($string, '_'));
	}
}