<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Word { 
    var $docFile  = ''; 
    var $title    = ''; 
    var $htmlHead = ''; 
    var $htmlBody = ''; 

    public function __construct() {
        $this->title = ''; 
        $this->htmlHead = ''; 
        $this->htmlBody = ''; 
    }

    function setDocFileName($docfile){ 
        $this->docFile = $docfile; 
        if(!preg_match("/\.doc$/i",$this->docFile) && !preg_match("/\.docx$/i",$this->docFile)){ 
            $this->docFile .= '.doc'; 
        } 
        return;  
    }

    function setTitle($title){ 
        $this->title = $title; 
    }

    function getHeader(){ 
        $return ='<html><body>';
		return $return; 
	} 

    function getFotter(){ 
        return "</body></html>"; 
    }

    function createDoc($html, $file, $download = false){ 
        if(is_file($html)){ 
            $html = @file_get_contents($html); 
        } 

        $this->_parseHtml($html); 
        $this->setDocFileName($file); 
        $doc = $this->getHeader(); 
        $doc .= $this->htmlBody; 
        $doc .= $this->getFotter(); 
        if($download){ 
			// To save word file on server
			$temp_file_name = $file;
			$filename = FCPATH ."uploaded_files/word_files/".$temp_file_name.".doc";
			$content = $html;
			file_put_contents($filename, $content);
			// To download word file
            return true;
        }else {
            return $this->write_file($this->docFile, $doc); 
        }
    }

    function _parseHtml($html){ 
        $html = preg_replace("/<!DOCTYPE((.|\n)*?)>/ims", "", $html); 
        $html = preg_replace("/<script((.|\n)*?)>((.|\n)*?)<\/script>/ims", "", $html); 
        preg_match("/<head>((.|\n)*?)<\/head>/ims", $html, $matches); 
        $head = !empty($matches[1])?$matches[1]:''; 
        preg_match("/<title>((.|\n)*?)<\/title>/ims", $head, $matches); 
        $this->title = !empty($matches[1])?$matches[1]:''; 
        $html = preg_replace("/<head>((.|\n)*?)<\/head>/ims", "", $html); 
        $head = preg_replace("/<title>((.|\n)*?)<\/title>/ims", "", $head); 
        $head = preg_replace("/<\/?head>/ims", "", $head); 
        $html = preg_replace("/<\/?body((.|\n)*?)>/ims", "", $html); 
        $this->htmlHead = $head; 
        $this->htmlBody = $html; 
        return; 
    }

    function write_file($file, $content, $mode = "w"){ 
        $fp = @fopen($file, $mode); 
        if(!is_resource($fp)){ 
            return false; 
        } 
        fwrite($fp, $content); 
        fclose($fp); 
        return true; 
    }
}