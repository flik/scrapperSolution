<?php
/**
 * Common Functions Class
 *
 * This file is application-wide file. You can put all
 * application-wide methods here.
 *
 * MohammadAshfaq(tm) : Rapid Development Solutions (http://rapidsol.blogspot.com)
 * Copyright (c) Mohammad Ashfaq (http://rapidsol.blogspot.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Mohammad Ashfaq (http://rapidsol.blogspot.com)
 * @link          http://rapidsol.blogspot.com Project
 * @package       ''
 * @since         v 01
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
class CF
{
    private static $anyval = 0;
    private static $defaultInfo      = array();

    public static function setDefault($info)
    {
        self::$defaultInfo = $info;
    }
    
	public static function toXHTML($html)
	{
		$tidy_config = array(
			"clean" => true,
			"output-xhtml" => true,
			"wrap" => 0,
		);

		$tidy = tidy_parse_string($html, $tidy_config);
		$tidy->cleanRepair();
		return $tidy;
	}

	public static function toUTF8($text)
		{
			return iconv("ISO-8859-1", "UTF-8//IGNORE", $text);
		}


		public static function encode_funct($x)
		{
			if ($x=='&amp;') {return $x;}
			if ($x=='&euro;') {return '&#x20AC;';}
			return '&#'.ord(html_entity_decode($x,ENT_NOQUOTES,'UTF-8')).';';
		}
		
	  

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//function for print array
	public static function debug($obj, $e = false)
	{
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		if($e)
		  exit;
		
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//function for echo array
	public static function debugx($obj, $e = false)
	{
		echo "<br />************************<br/>";
		echo $obj;
		echo "<br/>************************<br/>";
		if($e)
		  exit;
		
	}

	/**
	 * Example convert 500.00 to 500 (500,00 to 500)
	 * and 500.000 to 500000 (500,000 to 500000)
	 *
	 * @param string $str
	 * @return integer
	 */
	public static function toNumber($string)
	{
		$number = 0;
		$string = preg_replace("/(,\d{2})$|(\.\d{2})$|\s|\+\/-/", "", $string);
		$string = preg_replace("/,(\d{3})|\.(\d{3})/",  "$1$2", $string);
		if(preg_match("/(-?\d+)/", $string, $match)) $number = intval($match[1]);

		return $number;
	}
	#replacing wrong charachter with space
	public static function strip($string)
	{
		$string = str_replace(chr(194) . chr(160), " ", $string);
		$string = preg_replace("/\s+|\n/", " ", $string);
		return trim($string);
	}

	public static function uniqueId($string)
	{
		$string = crc32($string);
		$uuid = sprintf("%u",$string);
		return $uuId;
	}

	public static function normalize($string)
	{
		$arr = array(
			'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		);

		return strtr($string, $arr);
	}

	public static function contains($haystack, $needle)
	{
		$found = 0;
		if(stripos($haystack, $needle) !== false) $found = 1;

		return $found;
	}

	public static function GetBetween($content,$start,$end){
			$r = explode($start, $content);
			if (isset($r[1])){
				$r = explode($end, $r[1]);
				return $r[0];
			}
			return ''; 
	} 

	public static function removeBrackets($str){
		$str = str_replace('–','',$str);
		$str = str_replace('(','',$str);
		$str = str_replace(')','',$str);
		$str = str_replace('-','',$str);
		return $str;
	}


	//Google related data Lat and Long
	public static function getLatLong($html=''){
		if(!empty($html)){
			$res = $arr = array();
			if (preg_match('!GoogleX=([0-9.]+)&GoogleY=([0-9.]+)&!',$html,$res))
			{
				$arr['lat'] = $res[1];
				$arr['long'] = $res[2];
			}
			return $arr;
		}		
	}
		
	//Get pictures from javascript and return array, in case of slider
	public static function getPictureFromJS($html=''){
		
		if(!empty($html)){
			
			preg_match_all('#Array\((.*?)\)#s', $html, $result);
				
			$pics = $arr = $picUrls = $results = array();
			
			foreach($result[1] as $res){
				$pics[] = str_replace("'","",$res);
			}
			unset($pics[0]);
			if(!empty($html)){
				foreach($pics as $pic){
				   $p = explode(', ',$pic);
				   $picx = 'http://www.av-vastgoed.be/images/panden/'.$p[1];
				   $picUrls[] = array('picture_url' =>  $picx);
				}
			}
			$arr['pictures'] = $picUrls;
			return $arr;
		}
	}

	//Get Between td tags
	public static function getFromTags($html = ''){
		$result = array();
		preg_match_all('#<td>(.*?)</td>#s', $html, $result);
		return $result;
	}

	//Get Between td tags
	public static function getNumberFromUrl($url = ''){
		#example url http://www.abc.com/?record.id=22432&xy=3dd&abc=9
		
		//Getting Just number
		#return regex("/(\d+)$/", $url);
		
		#spcific number
		return regex("/record.id=(\d+)/", $url); 
		
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//function for Regex
	public static function regex($exp, $text, Closure $closure = null){
		if(preg_match($exp, $text, $match))
		{
			if(empty($closure))
			{
			return trim($match[1]);
			}
			else
			{
			return $closure($match);
			}
		}
		
		return "";
	}

	//////Function to convert relative address to absolute address
	public static function rel2abs($rel, $base){
		$path = "";
		/* return if already absolute URL */
		if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
		/* queries and anchors */
		if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
		
		/* parse base URL and convert to local variables:
		$scheme, $host, $path */
		
		extract(parse_url($base));

		/* remove non-directory element from path */
		$path = preg_replace('#/[^/]*$#', '', $path);

		/* destroy path if relative url points to root */
		if ($rel[0] == '/') $path = '';

		/* dirty absolute URL */
		$abs = "$host$path/$rel";

		/* replace '//' or '/./' or '/foo/../' with '/' */
		$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

		/* absolute URL is ready! */
		return $scheme.'://'.$abs;
	}

	//////Function to remove unwanted characters and - inplace of spaces
	public static function slugify($text) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///Clear Empity index
	public static function removeEmptyIndex($yourArray){
	 foreach($yourArray as $k=>$v){
			if(empty($v))
			unset($yourArray[$k]);
			
				if(is_array($v)){ 
					foreach($v as $kx=>$vx){ 
						if(empty($vx)){ 
							unset($v[$kx]);
							unset($yourArray[$k]);
						}
					}
				}	
		}
		return $yourArray; 
	}


	/// Basic checking for 
	public static function clearForLowerCase($str = ''){ 
		$str = strtolower(str_replace(' ','_',$str)); 
		$str = trim(strip_tags($str)); 
		$str = preg_replace('![^a-z0-9_\-\. ]!','',$str); 
		// $str = trim(preg_replace('!nbsp!','',$str)); 
		$str = trim(preg_replace('!m!','',$str)); 
		$str = trim(preg_replace('!ja,!','',$str));
		//$str = trim(preg_replace('!,!','',$str));
		$str = trim(normalize_str($str));
		return $str ;
	 
	}

	public static function toNumberB($str)
	{
		///return $str;
		$value = 0;
		$str = preg_replace("/(,\d{2})$|(\.\d{2})$|\s|\+\/-/", "", $str);
		$str = preg_replace("/,(\d{3})|\.(\d{3})/",  "$1$2", $str);
		if(preg_match("/(-?\d+)/", $str, $match)) $value = intval($match[1]);
		return $value;
	}

	public static function toYear($str){
		$year = toNumber($str);
		if($year > 0 && strlen($year) == 4) return $year;
	}

	 //When having to deal with parsing an IIS4 or IIS5 metabase dump
	public static function hex_decode($string)  {
		for ($i=0; $i < strlen($string); $i)  {
			$decoded .= chr(hexdec(substr($string,$i,2)));
			$i = (float)($i)+2;
		}
		return $decoded;
	} 
 
	/**
	 * Write Json to a file by Array
	 */
	public static function writeJsonToFile($file,$yourArray)
	{
		//Write Json
		$fp = fopen($file, 'w');
		fwrite($fp, json_encode($yourArray));
		fclose($fp);
	}
	
	/**
	 * return Json for datatable by object array
	 */
	public static function jsonDatabale($data)
	{
		
		$json = '{"data": [';
		foreach($data as $k=>$v){

				if($k!=1)
					$json .= ','.urldecode($v);
				else	
					$json .= ','.urldecode($v);
		}
		$json .= ']}';
		
		return $json;
	}
	
	
} //Ending Class Common Functions
