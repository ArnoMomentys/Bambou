<?php

/**
 * Class pour l'upload du csv des contacts
 * a été remanié pour que le reste de l'opération soit éxécuté via node.js avec le fichier watch.js
 *
 * importer fichier csv
 * renommer fichier csv
 * vérifier l'integrité des données
 * renommer csv en [name].validated.csv | essage d'erreur et supprimer fichier
 *
 * cas 1 : le reste est traité par nodejs avec le fichier watch.js :
 * cas 2 : le reste est traité par ImportContactsController
 * 		créer table temporaire
 * 		inserer données dans table
 *		recupérer les mails de la nouvelle table
 *
 */
class UploadContactsController extends AuthController
{

	private $event;
	private $status;
	private $file;
	private $defaultPassword = "Rn2ipxLMCnj8AkV/gw5bww=="; // Yp9tzgZs6t4= >> momentys  // Rn2ipxLMCnj8AkV/gw5bww== >> Louvre2014
	private $defaultLevel = 3;
	private $fileExtensionAllowed = array("csv", "xls", "xlsx", "xlsm");
	
	private $offset = 0;
	
	private $offsetAutoRGBColor = "FF0000";
	
	private $offsetAutoMaxRow = 100;
	
	// Pour toute modification ci-dessous, modifier la function checkData
	private $meta = array(																									// The meta description of columns
		"civilite" 					=> array("mandatory" => true, 	"titleFile" => "Civilité", 							"titleUser" => "Civilité"),
		"nom" 						=> array("mandatory" => true, 	"titleFile" => "Nom", 								"titleUser" => "Nom"),
		"prenom" 					=> array("mandatory" => true, 	"titleFile" => "Prénom", 							"titleUser" => "Prénom"),
		"fonction" 					=> array("mandatory" => true, 	"titleFile" => "Fonction", 							"titleUser" => "Fonction"),
		"branche" 					=> array("mandatory" => false, 	"titleFile" => "Branche", 							"titleUser" => "Branche"),
		"bu" 						=> array("mandatory" => false, 	"titleFile" => "BU", 								"titleUser" => "BU"),
		"societe" 					=> array("mandatory" => true, 	"titleFile" => "Société/Organisme/Collectivité", 	"titleUser" => "Société/Organisme/Collectivité"),
		"adresse" 					=> array("mandatory" => true, 	"titleFile" => "Adresse", 							"titleUser" => "Adresse"),
		"code_postal" 				=> array("mandatory" => true, 	"titleFile" => "Code Postal", 						"titleUser" => "Code Postal"),
		"ville" 					=> array("mandatory" => true, 	"titleFile" => "Ville", 							"titleUser" => "Ville"),
		"pays" 						=> array("mandatory" => false, 	"titleFile" => "Pays", 								"titleUser" => "Pays"),
		"tel_fixe" 					=> array("mandatory" => false, 	"titleFile" => "Telephone Fixe", 					"titleUser" => "Telephone Fixe"),
		"tel_portable" 				=> array("mandatory" => false, 	"titleFile" => "Telephone portable", 				"titleUser" => "Telephone portable"),
		"adresse_mail" 				=> array("mandatory" => false, 	"titleFile" => "email", 							"titleUser" => "email")
	);	// "adresse_mail" 				=> array("mandatory" => false for guests | true for hosts
	
	// meta key >> numCol
	private $metaDynamic = array(
		//"civilite" 					=> "numCol"
		);	// Only the present 
									
	private $data = array();		// CSV or XLS data
	
	private $dataFile;
	
	private $dataError = array();	
	
	private $sheet;
	
		// [iRow][erros] 
	
	/**
	 * public method of the class
	 * retrieve params and cast them to object
	 * instanciate the asociated event
	 * process the $_FILES variable
	 * upload the csv file
	 * check if can create data
	 * validate datas
	 * return errors if not validated
	 * display the view
	 */
	public function processUpLoad()
	{
		$params = (object) array_map('trim', $this->f3->get('PARAMS'));					// params object
		$eventsHosted = json_decode($this->f3->get('SESSION.events'));			// events I host(ed)
		$ev = new Events($this->db);											// event object
		$this->event = $ev->load(array('eid=?', $params->eid));						// event to process

		// I can import :
		// . if event is in the future
		// . and for guests :
		//		. if I am super admin
		// 		. or if level 1,2,3 and already a host to current event
		// . and for hosts :
		//		. if I am super admin
		// 		. or if level 1,2

		if(
			$this->event->limitA > date('Y-m-d')
			&&
			(
				(
					$params->status=='guests'
					&&
					(
						$this->f3->get('SESSION.lvl') == 1
						||
						(
							$this->f3->get('SESSION.lvl') <= 3
							&&
							in_array($params->eid, $eventsHosted)
						)
					)
				)
				||
				(
					$params->status=='hosts'
					&&
					(
						$this->f3->get('SESSION.lvl') == 1
						||
						$this->f3->get('SESSION.lvl') <= 2
					)
				)
			)
		)

		{
			if( $this->f3->get('SESSION.lvl') <= 3 ) 							// if credential is in 1,2,3
			{
				$this->status = $params->status;								// guests OR hosts
				if($this->f3->exists('FILES.csv')) 								// if there's an uploaded file in form
				{
					// UPLOAD FILE (CSV or XLS)
					$upload = $this->uploadFile();								// execute the upload action
					if(!$upload) 												// on upload error
					{
						$this->errors($this->T('cant_create_data'));			// set creation error
						$this->render();										// render upload response

						return;
					}
					
					// IMPORT FILE (CSV or XLS) TO ARRAY
					$parse = $this->parseIncomingData();						// feed $this->data
					if ($parse instanceof Exception || !$parse)
					{
						$this->errors($parse->getMessage());				// set creation error
						$this->render();										// render upload response

						return;					
					}

					// Check incoming data (CSV or XLS)
					$valid = $this->checkData();								// check validity of $this->data
					if(!$valid)													// on validation error
					{
						$this->errors($this->T('invalid_file'));	// set validity error
						$this->render();										// render validity response

						return;
					}
					
					// CREATE TEMPORARY 
					$tempDBOK = $this->createTemporaryDB();
					if (!$tempDBOK)
					{
						$this->errors($this->T('error_system'));				// set validity error
						$this->render();										// render validity response

						return;
					}
					
					// IMPORT FROM ARRAY TO TEMPORARY TABLE...
					$importTempDBOK = $this->importArrayIntoTempDB();
					if (!$importTempDBOK)
					{
						$this->errors($this->T('error_system'));				// set validity error
						$this->render();										// render validity response

						return;
					}
					
					// IMPORT FROM TEMPORARY TO FINAL TABLE...
					$importDataOK = $this->importDataFromTemporaryDB();
					if (!$importDataOK)
					{
						$this->errors($this->T('error_system'));				// set validity error
						$this->render();										// render validity response

						return;
					}

					$deleteTempDBOK = $this->deleteTemporaryDB();
					if (!$deleteTempDBOK)
					{
						$this->errors($this->T('error_system'));				// set validity error
						$this->render();										// render validity response

						return;
					}
					
					// Delete uploaded file
					$deleteUploadedFile = $this->removeUploadedFile();
					if (!$deleteUploadedFile)
					{
						$this->errors($this->T('error_system'));				// set validity error
						$this->render();										// render validity response

						return;
					}
					
					// If don't continue. reroute
					$continue = $this->f3->get('REQUEST.continue');
					
					if ($continue != "true")
					{
						// Re route
						$this->f3->reroute('/event/' . $params->eid . '/show/' . $params->status);		// goto imported list
					}					

				}
				$this->render();												// render upload success view with message
			}
		}
		else 
		{
	 	   $this->f3->reroute('/events');
		}
	}
	
	public function processDownLoad()
	{
		$params = (object) array_map('trim', $this->f3->get('PARAMS'));		
		$this->status = $params->status;				// params object
		$eventsHosted = json_decode($this->f3->get('SESSION.events'));			// events I host(ed)
		$ev = new Events($this->db);											// event object
		$this->event = $ev->load(array('eid=?', $params->eid));	
		$list = null;
		$filename = '';
		
		// email obligatoire si liste des invitants
		if ($this->status == "hosts")
		{
			$this->meta["adresse_mail"]["mandatory"] = true;
		}
		
		if ($this->f3->get('SESSION.lvl') <= 3 && ($params->status=='guests' || $params->status=='hosts')) {			
			// retrieve guests ids
			if ($params->status=='guests') {
				$eventGuests = new viewEventsEventGuests($this->db);
				if ($this->f3->get('SESSION.lvl') == 3) {
					$list = $eventGuests->getGuestsIdsByHost($this->f3->get('SESSION.uid'), $params->eid);
				}
				else {
					$list = $eventGuests->getGuestsIdsByHost(null, $params->eid);
				}
			}
			// retrieve hosts ids
			if ($params->status=='hosts') {
				$eventHosts = new viewEventHostsInfos($this->db);
				$list = $eventHosts->getHostsIdsByEventId($params->eid);
			}
			// retrieve complete profiles
			$profiles = new viewUserCompleteProfile($this->db);
			$results = $profiles->getUsersMinimumProfileExportByUidsIn_Raw($list);
			$pUser = $profiles->getUserExportProfileByUid_Raw($this->f3->get('SESSION.uid'))[0];
			
			if ($params->status=='guests') {
				if ($this->f3->get('SESSION.lvl') == 3) {
					$filename = $pUser["nom"].'_'.$pUser["prenom"].'_';
				} else {
					$filename = 'Liste_Invités_';
				}
			} 
			if ($params->status=='hosts') {
				$filename = 'Liste_Invitants_';
			}
			
			// Ajout du nom de l'événement
			$eventOpt = new viewEventOptions($this->db);
			$pEvent = $eventOpt->getEventNameByEidIn_Raw($params->eid)[0];
			$filename .= str_replace(" ", "_", $pEvent["nom"]);
		
			// Require
			require_once(dirname(__FILE__).'/../../../_lib/PHPExcel.php');
			require_once(dirname(__FILE__).'/../../../_lib/PHPExcel/Writer/Excel5.php');
			
			// Init PHP XLS

			$oExcel = new PHPExcel();
			
			$oExcel->getProperties()->setCreator("Bambou");
			$oExcel->getProperties()->setLastModifiedBy("Bambou");
			if ($params->status=='guests') {
				$oExcel->getProperties()->setTitle("Liste des invités");
				$oExcel->getProperties()->setSubject("Liste des invités");
				$oExcel->getProperties()->setDescription("Liste des invités pour l'événement ...");
			}
			if ($params->status=='hosts') {
				$oExcel->getProperties()->setTitle("Liste des invitants");
				$oExcel->getProperties()->setSubject("Liste des invitants");
				$oExcel->getProperties()->setDescription("Liste d'invitants (extraction Bambou)");
			}
			
			$styleArray = array(
					'alignment' => array(
							'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					),
					'font'  => array(
							'size'  => 12,
        					'name'  => 'Calibri'
					)
			);
			$oExcel->getDefaultStyle()->applyFromArray($styleArray);
			$oExcel->setActiveSheetIndex(0);
			$sheet = $oExcel->getActiveSheet();
			
			// Ecriture des lignes d'en-tête
			$styleArray = array(
					'font'  => array(
							'size'  => 16,
							'bold' => true,
							'color' => array('rgb' => 'FF0000'),
					)
			);
			if ($params->status=='guests') {
				$sheet->setCellValueByColumnAndRow(0, 1, 'Liste des invités');
			}
			if ($params->status=='hosts') {
				$sheet->setCellValueByColumnAndRow(0, 1, 'Liste des invitants');
			}
			$sheet->getStyleByColumnAndRow(0, 1)->applyFromArray($styleArray);
			
			$row = 2;
			if ($this->f3->get('SESSION.lvl') == 3 && $params->status=='guests') {

				$styleArray1 = array(
						'font'  => array(
								'size'  => 16,
								'bold' => true,
								'color' => array('rgb' => 'FF0000'),
						)
				);
				$sheet->setCellValueByColumnAndRow(0, $row + 1, 'INVITANT');
				$sheet->getStyleByColumnAndRow(0, $row + 1)->applyFromArray($styleArray1);
				$sheet->setCellValueByColumnAndRow(0, $row + 12, 'LISTE DE VOS INVITÉS :');
				$sheet->getStyleByColumnAndRow(0, $row + 12)->applyFromArray($styleArray1);
				$styleArray1 = array(
						'font'  => array(
								'size'  => 13,
								'bold' => true,
						)
				);
				$styleArray2 = array(
					'borders' => array(
							'left' => array(
									'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							),
							'right' => array(
									'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							),
							'bottom' => array(
									'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							),
							'top' => array(
									'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							),
					),
				);
				
				$sheet->setCellValueByColumnAndRow(0, $row + 3, 'NOM');
				$sheet->getStyleByColumnAndRow(0, $row + 3)->applyFromArray($styleArray1);
				$sheet->setCellValueByColumnAndRow(1, $row + 3, $pUser["nom"]);
				$sheet->getStyleByColumnAndRow(1, $row + 3)->applyFromArray($styleArray2);
				$sheet->setCellValueByColumnAndRow(0, $row + 5, 'PRÉNOM');
				$sheet->getStyleByColumnAndRow(0, $row + 5)->applyFromArray($styleArray1);
				$sheet->setCellValueByColumnAndRow(1, $row + 5, $pUser["prenom"]);
				$sheet->getStyleByColumnAndRow(1, $row + 5)->applyFromArray($styleArray2);
				$sheet->setCellValueByColumnAndRow(0, $row + 7, 'ENTITE');
				$sheet->getStyleByColumnAndRow(0, $row + 7)->applyFromArray($styleArray1);
				$sheet->setCellValueByColumnAndRow(1, $row + 7, $pUser["societe"]);
				$sheet->getStyleByColumnAndRow(1, $row + 7)->applyFromArray($styleArray2);
				$sheet->setCellValueByColumnAndRow(0, $row + 9, 'BRANCHE');
				$sheet->getStyleByColumnAndRow(0, $row + 9)->applyFromArray($styleArray1);
				$sheet->setCellValueByColumnAndRow(1, $row + 9, $pUser["branche"]);
				$sheet->getStyleByColumnAndRow(1, $row + 9)->applyFromArray($styleArray2);

				$row += 13;
			}
			$row++;
			
			// Ecriture colonnes obligatoires
			$styleArray = array(
							'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							),
					        'fill' => array(
					            'type' => PHPExcel_Style_Fill::FILL_SOLID,
					            'color' => array('rgb' => 'FF0000')
					        ),
							'font'  => array(
									'color' => array('rgb' => 'FFFFFF'),
									'size'  => 12
							)
					    );
			$index = 0;
			foreach($this->meta as $key => $metData) {
				$index = array_search($key, array_keys($this->meta));
				if ($metData["mandatory"]) {
					$sheet->setCellValueByColumnAndRow($index, $row, Controller::toLatin1('OBLIGATOIRE'));
					$sheet->getStyleByColumnAndRow($index, $row)->applyFromArray($styleArray);
				}
			}
			$row++;
			
			// Ecriture des colonnes de titre
			$styleArray = array(
					'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
					'borders' => array(
							'left' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'right' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'bottom' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'top' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
					),
					'font'  => array(
							'size'  => 12,
							'bold' => true,
					)
			);
			foreach($this->meta as $key => $metaData) {
				$index = array_search($key, array_keys($this->meta));
				$sheet->setCellValueByColumnAndRow($index, $row, $metaData["titleFile"]);
				$sheet->getStyleByColumnAndRow($index, $row)->applyFromArray($styleArray);
			}
			$row++;
			
			// Ecriture des données
			$styleArray = array(
					'borders' => array(
							'left' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'right' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'bottom' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
							'top' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
					)
			);
			foreach ($results as $rowValues) {
				foreach ($this->meta as $key => $metaData) {
					$index = array_search($key, array_keys($this->meta));
					if ($rowValues[$key] != null && $rowValues[$key] != "") {
						$sheet->setCellValueByColumnAndRow($index, $row, $rowValues[$key]);
					}
					$sheet->getStyleByColumnAndRow($index, $row)->applyFromArray($styleArray);
				}
				$row++;
			}
			// redimensionnement des colonnes
			foreach ($this->meta as $key => $metaData) {
				$index = array_search($key, array_keys($this->meta));
				$aCol = PHPExcel_Cell::stringFromColumnIndex($index);
				$sheet->getColumnDimension($aCol)->setAutoSize(true);
			}
			
			// Prêt au téléchargement
			$oWriter = new PHPExcel_Writer_Excel5($oExcel);
			//$oWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel2007');
			
			// [START] Buffer
			ob_start();
			$oWriter->save('php://output');
			// [END] Buffer
			$excelOutput = ob_get_clean();
			$size = strlen($excelOutput);
				
			// Pose des en-tête pour l'envoie
			header_remove();
			header('Pragma: public');
			header("Expires: 0" );
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private', false);
			//header('Content-length: ');
			header('Content-type: application/vnd.ms-excel');
			//header('Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Type: application/force-download");
			header('Content-Length: '.$size);
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
			header('Content-Transfer-Encoding: binary');
				
			echo $excelOutput;
				
			exit();
			
			$this->render();
		}
	}


	/**
	 * render the final view
	 * set f3 params necessary for the view
	 */
	private function render()
	{
		$this->f3->mset(
			array(
				'event' => $this->event,
				'page_subheader' => $this->T('event_'.$this->status.'_import'),
				'view' => 'import.htm'
			)
		);
	}


	/**
	 * upload the csv file to BASE./tmp/uploads
	 * instanciate a filesystem object to store upload folder path
	 * instanciate file type and pass the storage value to it
	 * rename the file to H_[user id]_E_[event id]_T_[timestamp] (Host Event Timestamp)
	 * store file object into the current class attribute file
	 * Validate file uploaded (mimetype, size)
	 * 	// MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
	 * 	// Ensure file is no larger than 5M (use "B", "K", M", or "G")
	 * process the real upload
	 *
	 */
	private function uploadFile()
	{
		$storage = new \Upload\Storage\FileSystem($this->f3->get('UPLOADS').'/'.$this->status);
		$file = new \Upload\File('csv', $storage);
		$file->setName($this->status{0}.$this->f3->get('SESSION.uid').'e'.$this->event->eid.'t'.time());
		$this->file = $file;

		$maxFileSize = ini_get('upload_max_filesize');
		
		$file->addValidations(array(
		    new \Upload\Validation\Extension($this->fileExtensionAllowed),
		    new \Upload\Validation\Size($maxFileSize)
		));
		try
		{
		    $file_uploaded = $file->upload();
			if(!$file_uploaded)
		    {
		    	$this->errors($this->T('cant_process_csv'));
		    	return false;
		    }
	    	$msg = '<b>'.$this->T('upload_success').'</b>. '.$this->T('processing_contact_file').'. '.$this->T('goback_to_'.$this->status.'_list');
	    	$this->setMessage($msg);
	    	return $file_uploaded;
		}
		catch (\Exception $e)
		{
		    $this->errors($file->getErrors());
		    return false;
		}
	}

	/*
	Read uploaded file and feed the $this->data attribute
	*/
	private function parseIncomingData()
	{
		// Rename uploaded file 
		$this->renameUploadedFile(); 
	
		// Check if uploaded file is readable ?
		if (!is_readable($this->dataFile))
		{
		    $this->errors($this->T('cant_process_csv'));
		   	return false;
		}
		
		// switch status
		if ($this->status == "hosts")
		{
			$this->meta['adresse_mail']['mandatory'] = true;
		}
		
		// Init $this->data array
		$this->data = array();
		
		//var_dump(strtolower($this->file->getExtension()));die('ici');
		try
		{
			switch (strtolower($this->file->getExtension()))
			{
				case 'csv':
					return $this->readCSVFileToArray();
					break;
					
				case 'xls':
				case 'xlsx':
				case 'xlsm':
					// Require
					require_once(dirname(__FILE__).'/../../../_lib/PHPExcel/IOFactory.php');
						
					// Init PHP XLS
					$xls = PHPExcel_IOFactory::load($this->dataFile);
					$this->sheet = $xls->getSheet(0);
					
					// Checking data...
					$this->setOffsetExcelSheet();
					$this->controlExcelSheet();
					
					// feed $this->data
					return $this->readExcelSheetToArray();
					break;
			}
		}
		catch (Exception $e)
		{
			return $e;
		}
		
		return true;
	}
	
	/**
	INPUT Data : $this->dataFile (file path)
	*/
	private function readCSVFileToArray()
	{
		$this->offset = 1;
	
		$fd = fopen($this->dataFile, "r");
		$iRow = 0;
		while (($dataCols = fgetcsv($fd, 0, ";")) !== false) // 1 row
		{
			//echo 'iRow: '.$iRow."<br>";
			$nbCol = count($dataCols);
			foreach ($dataCols as $iCol => $dataCol) // n cols
			{
				if ($iRow == 0)
				{
					// Get the header
					foreach ($this->meta as $keyMeta => $currentMetaData)
					{
						$dataCol = Controller::sanitizeDatas($dataCol);
						if ($currentMetaData['titleFile'] == $dataCol)
						{
							$this->metaDynamic[$keyMeta] = (int) $iCol;
						}
					}
					
					if ($iCol + 1 == $nbCol)
					{
						// Check if mandatory filed are missing
						foreach ($this->meta as $keyMeta => $currentMetaData)
						{
							if ($currentMetaData['mandatory'] && !array_key_exists($keyMeta, $this->metaDynamic))
							{
								throw new Exception('Missing mandatory field : '.$currentMetaData['titleUser']);
							}
						}
					}
					
					
					
				}// END meta row
				else
				{
					$currentDataCols = array();;
					foreach ($this->metaDynamic as $keyMeta => $iColDyn)
					{
						$currentDataCols[] = $dataCols[$iColDyn];
					}
					
					// Get the rows 
					$this->data[] = $currentDataCols;
					
					break;
				}// END data row
			}
			
			//var_dump($this->data); die();
			
			$iRow++;
			//echo '<hr>';
		}
			
		fclose($fd);
		
		return true;
	}
	
	// Lecture de la feuille Excel
	private function readExcelSheetToArray()
	{
	  // On boucle sur les lignes
        $i = $this->offset;
        $lignesVides = 0;
        $controleLigne = $this->sheet->getCellByColumnAndRow(0, $i)->getValue();
		$i++; // the meta row
		while ($lignesVides <= 10) {
			// Contrôle ligne
			$colonneVide = 0;
			$colonneRenseigne = 0;
			foreach ($this->metaDynamic as $key => $numCol) {
				$colonneRenseigne += ($this->sheet->getCellByColumnAndRow($numCol, $i)->getValue() != "") ? 1 : 0;
			}
			if ($colonneRenseigne > 0) {
				$lignesVides = 0;
				// intégration de la ligne dans la variable tableau
				$valColumns = array();
				foreach ($this->metaDynamic as $key => $numCol) {
					$valColumns[] = $valeur = $this->sheet->getCellByColumnAndRow($numCol, $i)->getValue();
				}
				$this->data[] = $valColumns;
			} else {
				$lignesVides++;
			}
			$i++;
		}
		
		return true;
	}
	
	/**
	 * check $data attribut
	 * rename it to valid file if correct
	 * OR return an error and remove the file
	 */
	private function checkData()
	{
		$row = $this->offset + 1;
		
		foreach($this->data as $iRow => $arrayValue){
	
		
			foreach($this->metaDynamic as $key => $iCol) {
				$index = array_search($key, array_keys($this->metaDynamic));
				$value = $arrayValue[$index];
				
				// Contrôles génériques
					// Champs obligatoires
					if ($this->meta[$key]["mandatory"] && ($value == "" || $value == null)) {
						$this->dataError[] = $this->T('error_line').' '.$row.' - '.$this->T('error_column').' '.$this->meta[$key]["titleUser"].' : '.$this->T('the_field').' '.$this->T('is_required');
					}
				
				// Contrôles spécifiques
				switch($key) {
					case "civilite":
						
					case "nom":
						break;
					case "prenom":
						break;
					case "fonction":
						break;
					case "branche":
						break;
					case "bu":
						break;
					case "societe":
						break;
					case "adresse":
						break;
					case "code_postal":
						// la longueur du champ doit être inférieure ou égale à 20 caractères
						if (strlen($value) > 20) {
							$this->dataError[] = $this->T('error_line').' '.$row.' - '.$this->T('error_column').' '.$this->meta[$key]["titleUser"].' : '.$this->T('user_cp_format');
						}
						break;
					case "ville":
						break;
					case "pays":
						break;
					case "tel_fixe":
						// la longueur du champ doit être inférieure ou égale à 30 caractères
						if (strlen($value) > 20) {
							$this->dataError[] = $this->T('error_line').' '.$row.' - '.$this->T('error_column').' '.$this->meta[$key]["titleUser"].' : '.$this->T('user_fixe_format');
						}
						break;
					case "tel_portable":
						// la longueur du champ doit être inférieure ou égale à 30 caractères
						if (strlen($value) > 20) {
							$this->dataError[] = $this->T('error_line').' '.$row.' - '.$this->T('error_column').' '.$this->meta[$key]["titleUser"].' : '.$this->T('user_portable_format');
						}
						break;
					case "adresse_mail":
					// var_dump($value);
						// le format de l'email doit être correct
						if(filter_var($value, FILTER_VALIDATE_EMAIL)===false && $value != null && $value != "")
						{
							$this->dataError[] = $this->T('error_line').' '.$row.' - '.$this->T('error_column').' '.$this->meta[$key]["titleUser"].' : '.$this->T('user_email_format');
						}
				}
			}
			$row++;
		}
		
		// Partie à réintégrer dans les vues ...
		for ($i = 0; $i < min(500, count($this->dataError)); $i++) {
			$this->errors($this->dataError[$i]);
		}
		if (count($this->dataError) > 500) {
			$this->errors("... au total, ".count($this->dataError)." erreurs dans le fichier.");
		}
		
		if (count($this->dataError) >= 1)
		{
			return false;
		}
		
		return true;
	}


	/**
	 * rename the csv file to a validated one
	 */
	private function renameUploadedFile()
	{
		sleep(1);
		$oldName = $this->f3->get('UPLOADS').'/'.$this->status.'/'.$this->file->getNameWithExtension();
		$newName = $this->f3->get('UPLOADS').'/'.$this->status.'/'.$this->file->getName().'.fileok';
		
		$this->dataFile = $newName;
		
		rename($oldName, $newName);
	}


	/**
	 * remove incorrect uploaded file
	 */
	private function removeUploadedFile()
	{
		//$file = $this->f3->get('UPLOADS').'/'.$this->status.'/'.$this->file->getNameWithExtension()
		$file = $this->dataFile;
		
		return unlink($file);
	}

	private function createTemporaryDB()
	{		
		// CREATE TABLE
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->file->getName()."` (id int(10) NOT NULL AUTO_INCREMENT, civilite varchar(8) COLLATE utf8_general_ci NOT NULL, nom varchar(50) COLLATE utf8_general_ci NOT NULL, prenom varchar(50) COLLATE utf8_general_ci NOT NULL, fonction varchar(100) COLLATE utf8_general_ci DEFAULT NULL, branche varchar(100) COLLATE utf8_general_ci DEFAULT NULL, bu varchar(100) COLLATE utf8_general_ci DEFAULT NULL, societe varchar(100) COLLATE utf8_general_ci NOT NULL, adresse varchar(150) COLLATE utf8_general_ci DEFAULT NULL, code_postal varchar(20) COLLATE utf8_general_ci DEFAULT NULL, ville varchar(200) COLLATE utf8_general_ci DEFAULT NULL, pays varchar(50) COLLATE utf8_general_ci DEFAULT NULL, tel_fixe varchar(30) COLLATE utf8_general_ci DEFAULT NULL, tel_portable varchar(30) COLLATE utf8_general_ci DEFAULT NULL, adresse_mail varchar(100) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		$this->db->exec($sql);
		
		return true;
	}
	
	private function importArrayIntoTempDB() {
		$sql_load_temp = "INSERT IGNORE INTO `".$this->file->getName()."` (";
		
		//for ($k = 0; $k < count($this->metaDynamic); $k++) {
		foreach ($this->metaDynamic as $key => $iCol) {
			$k = array_search($key, array_keys($this->metaDynamic));
			$sql_load_temp .= ($k == 0 ? "" : ", ").$key;
		}
		$sql_load_temp .= ") VALUES (";
		foreach ($this->data as $array_value) {
			$sql_load = $sql_load_temp;
			foreach ($this->metaDynamic as $key => $iCol) {
				$k = array_search($key, array_keys($this->metaDynamic));
				$valeur = $array_value[$k];
				if ($key == 'adresse_mail' && ($valeur == "" || $valeur == null)) {
					$nom = $array_value[array_search("nom", array_keys($this->metaDynamic))];
					$prenom = $array_value[array_search("prenom", array_keys($this->metaDynamic))];
					$sql_load .= ($k == 0 ? "" : ", ").$this->db->quote(Controller::sanitizeDatas(str_replace(" ", "_", $nom), true).".".Controller::sanitizeDatas(str_replace(" ", "_", $prenom), true)."@nielsy.com");
				} else if ($valeur == "" || $valeur == null) {
					$sql_load .= ($k == 0 ? "" : ", ")."null";
				} else if ($key == 'ville') {
					$ville = $array_value[array_search("ville", array_keys($this->metaDynamic))];
					$sql_load .= ($k == 0 ? "" : ", ").$this->db->quote(Controller::utf8_strtoupper(Controller::sanitizeDatas($ville)), \PDO::PARAM_STR);
				} else {
					$sql_load .= ($k == 0 ? "" : ", ").$this->db->quote(Controller::sanitizeDatas($valeur), \PDO::PARAM_STR);
				}
			}
			$sql_load .= ")";
			
			$this->db->exec($sql_load);
		}
		
		return true;
	}
	
	private function importDataFromTemporaryDB()
	{
		$sql = "SELECT * FROM `".$this->file->getName()."`";

		$dataCSV = $this->db->exec($sql);

		if (!is_array($dataCSV))
		{
			return false;
		}

		$sessionUid = $this->f3->get('SESSION.uid');

		foreach ($dataCSV as $index => $rowCSV)
		{
			$currentId = $rowCSV['id'];
			$currentCivilite = $rowCSV['civilite'];
			$currentNom = $rowCSV['nom'];
			$currentPrenom = $rowCSV['prenom'];
			$currentFonction = $rowCSV['fonction'];
			$currentBranche = $rowCSV['branche'];
			$currentBu = $rowCSV['bu'];
			$currentSociete = $rowCSV['societe'];
			$currentAdresse = $rowCSV['adresse'];
			$currentCodePostal = $rowCSV['code_postal'];
			$currentVille = $rowCSV['ville'];
			$currentPays = $rowCSV['pays'];
			$currentTelFixe = $rowCSV['tel_fixe'];
			$currentTelPortable = $rowCSV['tel_portable'];
			$currentAdresseMail = $rowCSV['adresse_mail'];

			$currentHash = MyMapper::getUserHash($rowCSV);
			
			$currentDept = Controller::cpToDept($currentCodePostal);
			$currentRegion = Controller::cpToRegion($currentCodePostal);
			
			$sqls = array();

			// INSERT (or not) a new user
			$insertedUserId = MyMapper::saveUserData($sessionUid, $rowCSV, true);
			
			// création du contact invité - invitant
			$sql= "INSERT IGNORE INTO usercontacts (hostID, contactID) VALUES (:hostID, :contactID)";
			$value = array(':hostID' => $sessionUid, ':contactID' => $insertedUserId);
			$this->db->exec($sql, $value);
			
			// != en fonction du status
			if ($this->status == 'hosts')
			{
				// création du event host
				$sqls[] = array("INSERT INTO eventhosts (hostID, eventID) VALUES (?, ?) ON DUPLICATE KEY UPDATE eventhosts_index=concat(eventID,hostID)", array(1 => $insertedUserId, 2 => $this->event->eid));
			}

			// Many SQL
			if( count($sqls) > 0 )
			{
				foreach ($sqls as $currentSql)
				{
					$fsfds = $this->db->exec($currentSql[0], $currentSql[1]);
				}
			}

			// création de l'invitation
			// création de la relation invitation - invité
			if ($this->status == 'guests')
			{
				$sql = array("INSERT IGNORE INTO invitations (guestID, hostID, eventID) VALUES (?, ?, ?)", array(1 => $insertedUserId, 2 => $sessionUid, 3 => $this->event->eid));
				$this->db->exec($sql[0], $sql[1]);

				$insertedInvitationId = $this->db->lastInsertId(); //($this->db->exec("SELECT LAST_INSERT_ID()"));

				if ($insertedInvitationId > 0)
				{
					$sql = array("INSERT IGNORE INTO invitationguests (invitationID) VALUES (?)", array(1 => $insertedInvitationId));
					$this->db->exec($sql[0], $sql[1]);
				}
			}
		}

		return true;
	}

	private function deleteTemporaryDB()
	{
		$sql = "DROP TABLE `".$this->file->getName()."`";

		$this->db->exec($sql);

		return true;
	}
	
	// [XLS] Mise en place de l'offset
	private function setOffsetExcelSheet() {
		$i = 1;
		
		while (($color = $this->sheet->getStyle('A'.$i)->getFill()->getStartColor()->getRGB()) != $this->offsetAutoRGBColor)
		{
			// Find the color ($color)...
			 
			// END loop	
			if ($i > $this->offsetAutoMaxRow)
			{
				break;
			}
			
			$i++;
		}
		
		if ($color == $this->offsetAutoRGBColor) {
			$this->offset = $i + 1;
		} else {
			$this->offset = 1;
		}
	}

	// [XLS] Contrôle du fichier Excel (ordre des colonnes)
	private function controlExcelSheet() {
		for ($j = 0; $j < count($this->meta); $j++) {
			$nomColonne = $this->sheet->getCellByColumnAndRow($j, $this->offset)->getValue();
			$nomColonne = Controller::sanitizeDatas($nomColonne);
			foreach ($this->meta as $key => $array_value) {
				if ($array_value["titleFile"] == $nomColonne) {
					$this->metaDynamic[$key] = $j;
					break;
				}
			}
		}
		
		// Retourne faux si une colonne mandatory n'a pas été trouvée
		$complet = true;
		foreach ($this->meta as $key => $array_value) {
			if ($array_value["mandatory"] && !array_key_exists($key, $this->metaDynamic)) {
				throw new Exception('Missing mandatory field : '.$array_value['titleUser']);
			}
		}
	}
}