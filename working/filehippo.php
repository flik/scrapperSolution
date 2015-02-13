<?php
header('Content-Type: text/html; charset=utf-8');
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

const HOST  = 'http://www.filehippo.com';

if (PHP_SAPI == 'cli')
	die('This program should only be run from a Web Browser');

/** Include Scrapping Library  */
require_once dirname(__FILE__) . '/../Classes/simple_html_dom.php';

/** Include ORM Database Library http://redbeanphp.com/ */
require_once dirname(__FILE__) . '/../Classes/RedBeanPHP4_1_3/rb.php';

/** Include PHPExcel Library PHPExcel (http://www.codeplex.com/PHPExcel) */
#require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

/** Include Common functions Library */
require_once dirname(__FILE__) . '/../Classes/CF.php';

R::setup('mysql:host=localhost;dbname=xyzabc', 'root','root'); //for both mysql or mariaDB
 
$data = array();
     
/*
$id = R::store($post);       //Create or Update
$post = R::load('post',$id); //Retrieve
R::trash($post);             //Delete

R::exec( 'UPDATE page SET title="test" WHERE id=1' );

R::getAll( 'SELECT * FROM page' );

for more information : http://redbeanphp.com/querying
    
*/
 
// get DOM from URL or file
$html = file_get_html('http://www.filehippo.com/');

$datab = $datac = array();

$title = 'www.filehippo.com';
$i=2;

#Getting Categories
foreach($html->find('ul#categories-list li a.internal-link') as $e){ 

    $CategoryName = str_replace(' ', '_', trim($e->plaintext)); 
    $CategoryLink = HOST.'/'.$e->href;
     
    #Getting Category Items
    $htmlB = file_get_html($CategoryLink);
    
    #Getting all pages
    $pages = '';
    foreach($htmlB->find('a.pager-page-link') as $pe){
        $pages[] = HOST.$pe->href;
    }
    $pages = '';
    $entryDetail = trim($htmlB->find('div.program-entry-details',0)->plaintext);
    $shortDetail = trim($htmlB->find('div.program-entry-description',0)->plaintext);
    
    foreach($htmlB->find('div.program-entry-header a') as $eb){ 
        $ItemName = str_replace(' ', '_', trim($eb->plaintext));
        $ItemLink = HOST.$eb->href;
	
		#$datab[$cname][$xname] = $ilink; 
            
        #Getting Items Detail
        $htmlC = file_get_html($ItemLink);
	
        $download = HOST.$htmlC->find('a.program-header-download-link',0)->href;
        $img = $htmlC->find('img[style=display:none]',0)->src;
        $all_versions_link = @$htmlC->find('div.version-history-all-link',0)->href;

		$direct_download_link = $categories_and_license = '';

		#$description = trim($htmlC->find('div#program-description-text-column',0)->plaintext);
        #$features = '';// trim($htmlC->find('section#project-features',0)->plaintext);
		#$reviews_n_ratings = '';//trim($htmlC->find('section#reviews-n-ratings',0)->plaintext);
        #$additional_trove = '';//trim($htmlC->find('section#project-additional-trove',0)->plaintext);
	
		# SAVING DATA INTO DB
		$data = R::dispense('data');  
		//////////////////////////////
		$data['uuid'] = $i;
		$data['category'] = $CategoryName;
		$data['category_link'] = $CategoryLink;
		$data['name'] = $ItemName;
		$data['download_link'] = $all_versions_link;
		$data['direct_download_link'] = $download;
		$data['features'] = $entryDetail;
		$data['image'] = $img;
		
		//UNCOMMENT IF YOU WANT ALL THE INFORMATION INTO YOUR DATABASE
		#$data['description'] = $description;
		#$data['categories_and_license'] = $categories_and_license;
		#$data['reviews_n_ratings'] = $reviews_n_ratings;
		#$data['additional_trove'] = $shortDetail;

		$datab[$ItemName] = $data;
		$id = R::store( $data );
		$data->refresh;
		$i++;
		
		#CF::debug($datab);
		#exit;
	
    }
    
    $datac[$CategoryName][] = $datab;

    $i = 1;
    # Getting Data by all pages 
    if(!empty($pages)){
		foreach($pages as $page){
			 
			//Getting Category Items
			$htmlB = file_get_html($page);
			$entryDetail = trim($htmlB->find('div.program-entry-details',0)->plaintext);
			$shortDetail = trim($htmlB->find('div.program-entry-description',0)->plaintext);
			
			foreach($htmlB->find('div.program-entry-header a') as $eb){ 
				$ItemName = str_replace(' ', '_', trim($eb->plaintext));
				$ItemLink = HOST.$eb->href;
				
				#$datab[$cname][$xname] = $ilink; 
					
				#Getting Items Detail
				$htmlC = file_get_html($ItemLink);
				
				$download = HOST.$htmlC->find('a.program-header-download-link',0)->href;
				$img = $htmlC->find('img[style=display:none]',0)->src;
				$all_versions_link = @$htmlC->find('div.version-history-all-link',0)->href;
			
				$direct_download_link = $categories_and_license = '';
			
				//$description = trim($htmlC->find('div#program-description-text-column',0)->plaintext);
				//$features = '';// trim($htmlC->find('section#project-features',0)->plaintext);
				//$reviews_n_ratings = '';//trim($htmlC->find('section#reviews-n-ratings',0)->plaintext);
				//$additional_trove = '';//trim($htmlC->find('section#project-additional-trove',0)->plaintext);
				
				
				$data = R::dispense('data');  //It will create table data if it is not there :)
				//////////////////////////////
				# SAVING DATA INTO DB
				$data = R::dispense('data');  
				//////////////////////////////
				$data['uid'] = $i;
				$data['category'] = $CategoryName;
				$data['category_link'] = $CategoryLink;
				$data['name'] = $ItemName;
				$data['download_link'] = $all_versions_link;
				$data['direct_download_link'] = $download;
				$data['features'] = $entryDetail;
				$data['image'] = $img;
				
				//UNCOMMENT IF YOU WANT ALL THE INFORMATION INTO YOUR DATABASE
				#$data['description'] = $description;
				#$data['categories_and_license'] = $categories_and_license;
				#$data['reviews_n_ratings'] = $reviews_n_ratings;
				#$data['additional_trove'] = $shortDetail;

				 
				$datab[$ItemName] = $data;
				$id = R::store( $data );
				$data->refresh;
				$i++;
			}
			$datac[$CategoryName][] = $datab;
			 
		}
	 
    }
    
}
 
/********************************************************/ 
//Write xml
/*
$xml = new SimpleXMLElement('<root/>');
array_walk_recursive($datac, array ($xml, 'addChild'));
//print $xml->asXML();
////////////////////////////////////////////
/*
$fp = fopen('results.xml', 'w');
	fwrite($fp, $xml->asXML());
	fclose($fp);
*/

/********************************************************/ 
//Write Json to file
CF::writeJsonToFile('results.json',$datac);



CF::debug($datac);
exit; 
