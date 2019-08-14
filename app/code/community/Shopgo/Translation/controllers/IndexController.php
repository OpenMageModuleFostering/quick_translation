<?php
class Shopgo_Translation_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout ();
		$block = $this->getLayout ()->createBlock ( 'core/template' )->setTemplate ( 'shopgo/translation/index.phtml' );
		$this->getLayout ()->getBlock ( 'content' )->append ( $block );
		$this->renderLayout ();
	}
	public function getLangAction() {
		$locale_dir = Mage::getBaseDir () . DS . 'app' . DS . 'locale' . DS;
		$data = array ();
		if ($handle = opendir ( $locale_dir )) {
			$blacklist = array (
					'.',
					'..' 
			);
			while ( false !== ($file = readdir ( $handle )) ) {
				if (! in_array ( $file, $blacklist )) {
					$data [] = $file;
				}
			}
			closedir ( $handle );
		}
		
		echo json_encode ( $data );
	}
	public function getCSVAction() {
		$lang = $this->getRequest ()->getParam ( 'lang' );
		$lang_locale_dir = Mage::getBaseDir () . DS . 'app' . DS . 'locale' . DS . $lang . DS;
		$payload = array ();
		$csv = array ();
		foreach ( glob ( $lang_locale_dir . "*.csv" ) as $filename ) {
			$csv [] = basename ( $filename );
			if (($handle = fopen ( $filename, "r" )) !== FALSE) {
				while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
					$payload [basename ( $filename )] [] = $data;
				}
			}
			fclose ( $handle );
		}
		// var_dump($payload);
		// echo json_encode($payload);
		echo json_encode ( $csv );
		return;
	}
	public function getCSVDataAction() {
		$csv_file = $this->getRequest ()->getParam ( 'csv' );
		$lang = $this->getRequest ()->getParam ( 'lang' );
		$lang_locale_dir = Mage::getBaseDir () . DS . 'app' . DS . 'locale' . DS . $lang . DS;
		$payload = array ();
		$csv = array ();
		$filename = $lang_locale_dir . $csv_file;
		
		/*
		 * foreach (glob($lang_locale_dir . "*.csv") as $filename) { $csv[] = basename($filename); if(($handle = fopen($filename, "r")) !== FALSE) { while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){ $payload[basename($filename)][] = $data; } } fclose($handle); }
		 */
		if (($handle = fopen ( $filename, "r" )) !== FALSE) {
			// while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
			while ( ($data = fgets ( $handle, 1000 )) !== FALSE ) {
				$payload [basename ( $filename )] [] = $data;
			}
		}
		fclose ( $handle );
		// var_dump($payload);
		echo json_encode ( $payload );
		// echo json_encode($csv);
		return;
	}
	public function saveCSVAction() {
		$csv_file = $this->getRequest ()->getParam ( 'csv' );
		$lang = $this->getRequest ()->getParam ( 'lang' );
		
		$csv_file_content = $this->getRequest ()->getParam ( 'csv_file_content' );
		//var_dump($csv_file_content);
		//return;
		
		$lang_locale_dir = Mage::getBaseDir () . DS . 'app' . DS . 'locale' . DS . $lang . DS;
		
		$filename = $lang_locale_dir . $csv_file;
		//var_dump($filename);
		//return;
		try {
			file_put_contents ( $filename, $csv_file_content );
			
			Mage::app ()->getCache ()->clean ();
			$data = array();
			$data['status'] = 'success';
			$data['message'] = 'csv saved !';
			echo json_encode($data);
			return;
		} catch ( Mage_Exception $e ) {
			$data = array();
			$data['status'] = 'error';
			$data['message'] = $e->getMessage();
			echo json_encode($data);
			return;
		}
	}
	public function testAction() {
		// var_dump(Mage::getBaseDir());
		
		// $en_locale_dir = Mage::getBaseDir() . DS . 'app' . DS . 'locale' . DS . 'en_US' . DS;
		
		// foreach (glob($en_locale_dir . "*.csv") as $filename) {
		// var_dump($filename);
		// return ;
		/*
		 * $i = 0; if(($handle = fopen("$filename", "r")) !== FALSE) { while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){ if($i>0 && count($data)>1){ var_dump($data); } $i++; } }
		 */
		// return ;
		// }
	}
	protected function rsearch($folder, $pattern) {
		$dir = new RecursiveDirectoryIterator ( $folder );
		$ite = new RecursiveIteratorIterator ( $dir );
		$files = new RegexIterator ( $ite, $pattern, RegexIterator::GET_MATCH );
		$fileList = array ();
		foreach ( $files as $file ) {
			$fileList = array_merge ( $fileList, $file );
		}
		return $fileList;
	}
}