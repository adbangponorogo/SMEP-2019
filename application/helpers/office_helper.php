<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| PHP OFFICE HELPER
| -------------------------------------------------------------------------
| Usage:
| - Autoload by modify \application\config\autoload.php
| 
| 		$autoload['helper'] = array('office_helper');
| 
| - Or can be in controller, model or view (not preferable)
| 
| 		$this->load->helper('office_helper');
| 
| Other helper use tip:
| 
| 	function new_helper(){
| 		$ci=& get_instance();
| 		$ci->load->database();
| 
| 		$q = "select * from table";
| 		$rs = $ci->db->query($q);
| 		$d = $rs->result();
|		}
*/

	if ( !function_exists('xl_borderall') )
	{
		function xl_borderall($xl, $range)
		{
			$style = array(
			'borders' => array(
				'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			);
			$xl->getStyle($range)->applyFromArray($style); 
		}
	}

	if ( !function_exists('xl_align') )
	{
		// Default align center
		function xl_align($xl, $range, $align = 'center')
		{
			$xl_align = $xl->getStyle($range)->getAlignment();
			
			switch ($align)
			{
				case 'right':
					$xl_align->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					break;
				case 'top':
					$xl_align->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					break;
				case 'middle':
					$xl_align->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					break;
				default: // horizontal center
					$xl_align->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		}
	}

	if ( !function_exists('xl_font') )
	{
		function xl_font($xl, $range, $size, $style = false)
		{
			$xl_font = $xl->getStyle($range)->getFont();
			
			switch ($style){
				case 'bold':
					$xl_font->applyFromArray(array('size' => $size, 'bold' => true));
					break;
				case 'underline':
					$xl_font->applyFromArray(array('size' => $size, 'underline' => true));
					break;
				case 'both':
					$xl_font->applyFromArray(array('size' => $size, 'bold' => true, 'underline' => true));
					break;
				default:
					$xl_font->applyFromArray(array('size' => $size));
			}
		}
	}

	if ( !function_exists('xl_autoheight') )
	{
		function xl_autoheight($xl, $range)
		{
			$xl->getRowDimension($range)->setRowHeight(-1);
		}
	}

	if ( !function_exists('xl_wrap') )
	{
		function xl_wrap($xl, $range)
		{
			$xl->getStyle($range)->getAlignment()->applyFromArray(array('wrap'=>TRUE));
			$xl->getRowDimension($range)->setRowHeight(-1);
		}
	}

	if ( !function_exists('xl_number_format') )
	{
		function xl_number_format($xl, $range, $dec=false)
		{
			if ($dec)
			{
				for ($i=0; $i<$dec; $i++)
					$zeros.='0';
				$xl->getStyle($range)->getNumberFormat()->setFormatCode('0.'.$zeros);
			}
			else
			{
				$xl->getStyle($range)->getNumberFormat()->setFormatCode('#,##0');
			}
		}
	}

	if ( !function_exists('xl_persen') )
	{
		function xl_persen($xl, $range)
		{
			$xl->getStyle($range)->getNumberFormat()->applyFromArray( 
				array( 
						'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
				)
			);
		}
	}

	if ( !function_exists('xl_footer') )
	{
		function xl_footer($xl, $footerlap, $form, $nama_skpd)
		{
			//http://www.dianagung.com/blog/kode-warna-css-lengkap
			$xl->getHeaderFooter()->setOddFooter('&L&B&KFFA500 '.$footerlap.'&C Form '.$form.' | '.$nama_skpd.'&R&P');
		}
	}

	if ( !function_exists('export2xl') )
	{
		function export2xl($p, $xlFileName)
		{
			$xl = PHPExcel_IOFactory::createWriter($p, 'Excel2007');

			$xlFileName = str_replace(',', '', $xlFileName);
			$xlFileName = str_replace('---', '-', $xlFileName);
			$xlFileName = str_replace('--', '-', $xlFileName);
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$xlFileName.'.xlsx"'); 
			header('Cache-Control: max-age=0');
			
			$xl->save('php://output'); // download file
		}
	}

	if ( !function_exists('getPenanggungJawab') )
	{
		function getPenanggungJawab($x, $row, $kota, $tgl_cetak, $ka_skpd, $cka_skpd)
		{
			$ci =& get_instance();
			$auth = $ci->session->userdata('auth_id');
			$sts = $ci->main_model->getDataUser($auth)->row()->status;
			
			if ($sts > 1) {
				for ($i=0; $i<2; $i++) ++$row;
				
				$mulai = $row;
				
				$x->setCellValue($cka_skpd.$row, ucwords(strtolower($kota)).', '.tgl_indo($tgl_cetak));
				++$row;
				$x->setCellValue($cka_skpd.$row, $ka_skpd->jabatan);
				
				for ($i=0; $i<5; $i++) ++$row;
				
				xl_font($x, $cka_skpd.$row,'11','both');
				
				$x->setCellValue($cka_skpd.$row, $ka_skpd->nama);
				++$row;
				$x->setCellValue($cka_skpd.$row, ucwords(strtolower($ka_skpd->pangkat)));
				++$row;
				$x->setCellValue($cka_skpd.$row, 'NIP. '.$ka_skpd->nip);

				xl_align($x, $cka_skpd.$mulai.':'.$cka_skpd.$row);
			}
		}
	}

?>