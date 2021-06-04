<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;

class Home extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}


public function convertPdf()
	{
		// initiate FPDI
		$pdf = new Fpdi();

		// add a page
		$pdf->AddPage();

		// set the source file
		$pageCount = $pdf->setSourceFile(FCPATH."/uploads/pdfs/240b403fef6cc920307ab23905bf40c5.pdf");

		
		// import page 1
		$tplId = $pdf->importPage(1);

		// use the imported page and place it at point 10,10 with a width of 100 mm
		$pdf->useTemplate($tplId,2, 10, 208);

		
		for ($i=2; $i <= $pageCount; $i++) {
		    $pdf->AddPage();
		    $tplId = $pdf->importPage($i);
		    $pdf->useTemplate($tplId,2, 10, 208);
		}

		//see the results
		$pdf->Output(); 
		die;
	}

	public function index()
	{

		

		if($this->input->post()){
			if(!empty($_FILES['pdfs']['name']) && count(array_filter($_FILES['pdfs']['name'])) > 0){
				
				$filesCount = count($_FILES['pdfs']['name']); 
				
				for($i = 0; $i < $filesCount; $i++){ 
					$_FILES['file']['name']     = $_FILES['pdfs']['name'][$i]; 
                    $_FILES['file']['type']     = $_FILES['pdfs']['type'][$i]; 
                    $_FILES['file']['tmp_name'] = $_FILES['pdfs']['tmp_name'][$i]; 
                    $_FILES['file']['error']     = $_FILES['pdfs']['error'][$i]; 
                    $_FILES['file']['size']     = $_FILES['pdfs']['size'][$i];
                    $uploadPath = 'uploads/pdfs/'; 
                    $config['upload_path'] = $uploadPath; 
                    $config['allowed_types'] = 'pdf'; 
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 
                    if($this->upload->do_upload('file')){ 
                    	$fileData = $this->upload->data(); 
                    	echo '<pre>';
                    	print_r($fileData);die;
                        $uploadData[$i]['file_name'] = $fileData['file_name']; 
                        $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 
                    }else{
                    	 $errorUploadType .= $_FILES['file']['name'].' | ';  
                    	 die( $this->upload->display_errors());
                    }
				}
			}
			$this->load->view('home',['error'=>$errorUploadType,'success'=>'Pdf uploaded successfully']);
		}else{
			$this->load->view('home',['error'=>'']);
		}
		
		

	}
	

	
}
