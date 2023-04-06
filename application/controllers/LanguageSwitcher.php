<?php if ( ! defined('BASEPATH')) exit('Direct access allowed');
class LanguageSwitcher extends CI_Controller{
	public function __construct() {
		parent::__construct();
	}

	function switchLang($language = "") {
		$language = ($language != "") ? $language : "english";
		$part = explode("_",$language);
		if($part[0] == 'export'){
			$this->session->set_userdata('site_lang', 'english');
			$this->session->set_userdata('export_site_lang', $language);
		}else{
			$this->session->set_userdata('site_lang', $language);
			$this->session->set_userdata('export_site_lang', $language);
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
}