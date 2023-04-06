<?php
class LanguageLoader{
	function initialize() {
		$ci =& get_instance();
		$ci->load->helper('language');
		$siteLang = $ci->session->userdata('export_site_lang');
		if ($siteLang) {
			$ci->lang->load($siteLang,$siteLang);
		} else {
			$ci->lang->load('english','english');
		}
	}
}