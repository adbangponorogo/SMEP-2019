public function getPrintBastData(){
		if ($this->session->userdata('auth_id') != '') {
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$document = $phpWord->loadTemplate('custom/tpl/bast.docx');
			$document->setValue('name', 'John Doe');
	  //       $section = $phpWord->addSection();
	  //       $section->addText('Hello World');

			// $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(base_url().'custom/tpl/bast.docx');
			// $templateProcessor->setValue(array('City', 'Street'), array('Detroit', '12th Street'));
	  //       $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($document, 'Word2007');
	        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
	        header("Content-Disposition: attachment; filename='myFile.docx'");
	        $document->saveAs('php://output');
		}
		else{
            redirect(base_url());
        }
	}