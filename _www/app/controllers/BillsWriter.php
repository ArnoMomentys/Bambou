<?php


/**
 *
 */
class BillsWriter
{

/*
    private $path;
    private $mime;
    private $throttle = 2048; // throttle to around 256 KB /s
    private $force_download = TRUE; // force a dialog box on download

    public function getSample()
    {
    	$params = F3::get('PARAMS');
    	if(isset($params['dwtype']) && isset($params['eid']))
    	{
    		switch ($params['dwtype']) {
    			case 'contacts':
    				$this->path = F3::get('DOWNLOADS').'/contacts.csv';
    				$this->mime = 'text/csv; charset=utf-8';
    				break;
       			case 'bill':
    				$this->path = F3::get('ATTACH').'/facture'.$params['eid'].'.xls';
    				$this->mime = 'application/vnd.ms-excel ; charset=utf-8';
    				break;
    		}
			// send() method returns FALSE if file doesn't exist
    		if (!Web::instance()->send($this->path, $this->mime, $this->throttle, $this->force_download))
    		    // Generate an HTTP 404
    		    F3::error(404);
    	}
    	else
    	{
    		// Generate an HTTP 404
            F3::error(404);
    	}
    }

    public function exportContacts()
    {
    	$this->mime = 'text/csv; charset=utf-8';
    	$db=new DB\SQL(
    	    F3::get('db_dns') . F3::get('db_name'),
    	    F3::get('db_user'),
    	    F3::get('db_pass')
    	);
        $type = F3::get('POST.ex');
        $eid = F3::get('POST.eid');
        $o_session = json_decode(Encrypt::load()->invert(F3::get('POST.l')));

        // depending on session level,
        // if the request has been loaded by an admin
        // or if the request has been loaded by a host
        if($o_session->lvl<=3 && ($type=='guest' || $type=='host'))
        {
            // retrieve guests ids
            if($type=='guest')
            {
	            $eventGuests = new viewEventsEventGuests($db);
	            if($o_session->lvl==3)
	            {
    	        	$list = $eventGuests->getGuestsIdsByHost($o_session->uid, $eid);
	            }
	            else
	            {
	            	$list = $eventGuests->getGuestsIdsByHost(null, $eid);
	            }
            }
            // retrieve hosts ids
            if($type=='host')
            {
	            $eventHosts = new viewEventHostsInfos($db);
	        	$list = $eventHosts->getHostsIdsByEventId($eid);
            }
            // retrieve complete profiles
            $profiles = new viewUserCompleteProfile($db);
            $results = $profiles->getUsersMinimumProfileAliasedByUidsIn_Raw($list);
            $datas = array(
            	'params'=>array('_type'=>$type, 'eid'=>$eid),
            	'results'=>json_encode($results)
            );
            echo json_encode($datas);
        }
        else
        {
            //error
        }
    }
*/

    private $path;
    private $f3;

    public function __construct($f3) {
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
        date_default_timezone_set('Europe/Paris');
        $this->f3 = $f3;
    }


    public function defautDataOnCreate($xls, $event, $eventOptions) {

        require_once dirname(__FILE__) . '/../../../_lib/PHPExcel/IOFactory.php';
        $this->path = $xls;
        $options = $eventOptions[0];
        // echo date('H:i:s') , " Load from Excel5 template" , EOL;
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($this->path);
        // echo date('H:i:s') , " Add new data to the template" , EOL;
        /*
        $data = array(array('title'     => 'Excel for dummies',
                            'price'     => 17.99,
                            'quantity'  => 2
                           ),
                      array('title'     => 'PHP for dummies',
                            'price'     => 15.99,
                            'quantity'  => 1
                           ),
                      array('title'     => 'Inside OOP',
                            'price'     => 12.95,
                            'quantity'  => 1
                           )
                     );
        */

        // $objPHPExcel->getActiveSheet()->setCellValue('G8', PHPExcel_RichText $pValuestrftime("%A %d %B %G", strtotime($event->fin)));
        $objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E27')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

        $objPHPExcel->getActiveSheet()->setCellValue('G8', strftime("%A %d %B %G", strtotime($event->fin)));
        $objPHPExcel->getActiveSheet()->setCellValue('A27', $event->nom."\nNb Invités confirmés");
        $objPHPExcel->getActiveSheet()->setCellValue('E27', $this->f3->get('TVA'));

        if(!empty($options->eotp)) $objPHPExcel->getActiveSheet()->setCellValue('G27', $options->eotp);

        $objPHPExcel->getActiveSheet()->mergeCells('A27:B27');

        /*
        $baseRow = 5;
        foreach($data as $r => $dataRow) {
            $row = $baseRow + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
                                          ->setCellValue('B'.$row, $dataRow['title'])
                                          ->setCellValue('C'.$row, $dataRow['price'])
                                          ->setCellValue('D'.$row, $dataRow['quantity'])
                                          ->setCellValue('E'.$row, '=C'.$row.'*D'.$row);
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
        */

        // echo date('H:i:s') , " Write to Excel5 format" , EOL;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($this->path);

        // echo date('H:i:s') , " File written to " , pathinfo($this->path, PATHINFO_BASENAME) , EOL;
        // Echo memory peak usage
        // echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;
        // Echo done
        // echo date('H:i:s') , " Done writing file" , EOL;
        // echo 'File has been created in ' , getcwd() , EOL;

        // exit;
    }

}