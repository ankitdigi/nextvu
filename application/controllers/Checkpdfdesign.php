<?php
require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf as Dompdf;
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR | E_PARSE);
class Checkpdfdesign extends CI_Controller{

	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '256M');
	}

	function checkusingDom(){
		$dompdf = new Dompdf(array('enable_remote' => true));

		$html = $this->load->view('design/print_form_template', '', true);
		$html = trim($html);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'Portrait');
		$dompdf->render();
		$pdf = $dompdf->output();
		$file = FCPATH . SERUM_REQUEST_PDF_PATH . "serum_request_form_" . time() . ".pdf";
		file_put_contents($file, $pdf);
	}
	
	function checkusingMpdf(){
		$html = $this->load->view('design/print_form_template', '', true);
		$html = trim($html);

		require_once('vendor_pdf/autoload.php');
		$arr = array('P', 'mm', 'A4', true, 'UTF-8', false);
		$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
		$mpdf->SetTitle('Serum Request Form');

		$file_name = "m_serum_request_form_".time().".pdf";
		$file_path = FCPATH . SERUM_REQUEST_PDF_PATH;

		$mpdf->WriteHTML($html);
		$mpdf->Output($file_path.$file_name,'D');
	}
}