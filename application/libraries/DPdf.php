<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf as Dompdf;

class DPdf
{
    function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait'){
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->set_option('isRemoteEnabled', TRUE);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        if($download)
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
    }
}
?>