<?php
/**
 * Ktai library, supports Japanese mobile phone sites coding.
 * It provides many functions such as a carrier check to use Referer or E-mail, 
 * conversion of an Emoji, and more.
 *
 * PHP versions 4 and 5
 *
 * Ktai Library for CakePHP1.2
 * Copyright 2009-2010, ECWorks.
 
 * Licensed under The GNU General Public Licence
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2009-2010, ECWorks.
 * @link			http://www.ecworks.jp/ ECWorks.
 * @version			0.3.0
 * @lastmodified	$Date: 2010-04-27 12:00:00 +0900 (Thu, 27 Apr 2010) $
 * @license			http://www.gnu.org/licenses/gpl.html The GNU General Public Licence
 */

App::import('Vendor', 'Lib3gkHtml');

class TestLib3gkHtml extends CakeTestCase {

	var $Lib3gkHtml = null;
	
	function start(){
		$this->Lib3gkHtml = new Lib3gkHtml();
		$this->Lib3gkHtml->initialize();
	}
	
	function stop(){
		$this->Lib3gkHtml->shutdown();
	}
	
	function testUrl(){
		$str = './" /><script language="javascript">alert("test");</script><img src="./';
		$check = htmlspecialchars($str);
		$result = $this->Lib3gkHtml->url($str);
		$this->assertTrue($check, $result);
	}
	
	function testImage(){
	
		$str = './img/cake.icon.png';
		$check = $str;
		$result = $this->Lib3gkHtml->image($str, array('width' => 20, 'height' => 20));
		$check = '<img src="./img/cake.icon.png" width="20" height="20">';
		$this->assertEqual($check, $result);
		
		$carrier = Lib3gkCarrier::get_instance();
		$carrier->get_carrier('SoftBank/1.0/940SH/SHJ001[/Serial] Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1', true);
		$check = '<img src="./img/cake.icon.png" width="40" height="40">';
		$result = $this->Lib3gkHtml->image($str, array('width' => 20, 'height' => 20));
		$this->assertEqual($check, $result);
	}
	
	function testStretchImageSize(){
	
		$carrier = Lib3gkCarrier::get_instance();
		
		$width  = 20;
		$height = 40;
		$default_width  = 240;
		$default_height = 320;
		
		$carrier->get_carrier('', true);
		list($result_width, $result_height) = $this->Lib3gkHtml->stretch_image_size($width, $height, $default_width, $default_height);
		$this->assertEqual($result_width,  20);
		$this->assertEqual($result_height, 40);
		
		$carrier->get_carrier('SoftBank/1.0/940SH/SHJ001[/Serial] Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1', true);
		list($result_width, $result_height) = $this->Lib3gkHtml->stretch_image_size($width, $height, $default_width, $default_height);
		$this->assertEqual($result_width,  40);
		$this->assertEqual($result_height, 80);
	}
	function testStyle(){
		$style = 'color: #ffffff;';
		$this->Lib3gkHtml->_params['style']['test'] = $style;
		$result = $this->Lib3gkHtml->style('test', false);
		$this->assertEqual($result, $style);
	}
	
	function testGetQrcode(){
		$str = 'Ktai Library';
		$result = $this->Lib3gkHtml->get_qrcode($str);
		$this->assertTrue(preg_match('/Ktai Library/', $result));
	}
	
	function testGetStaticMaps(){
		
		$carrier = Lib3gkCarrier::get_instance();
		$carrier->get_carrier('', true);
		
		$lat = '-12.3456';
		$lon = '12.3456';
		$options = array(
			'markers' => array(
				array('-12.3456', '12.3456', 'mid', 'red', '1'), 
				array('-34.5678', '34.5678', 'tiny', 'blue', 'a'), 
				array('-56.7890', '56.7890', 'green', null), 
			), 
			'path' => array(
				'rgb'    => '0xff0000', 
				'weight' => '1', 
				'points' => array(
					array('-12.3456', '12.3456'), 
					array('-34.5678', '34.5678'), 
					array('-56.7890', '56.7890'), 
				), 
			), 
			'span' => array(100, 100), 
		);
		$this->Lib3gkHtml->_params['google_api_key'] = '0123456789';
		$result = $this->Lib3gkHtml->get_static_maps($lat, $lon, $options);
//		var_dump($result);
	}
	
}