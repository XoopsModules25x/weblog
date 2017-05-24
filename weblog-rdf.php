<?php
require('header.php');
require_once(XOOPS_ROOT_PATH.'/class/template.php');
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/include/encode_set.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

// privilege check
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
$isAdmin = ( isset($xoopsUser) && is_object($xoopsUser) ) ? $xoopsUser->isAdmin($xoopsModule->mid()) : false ;
if( ! $isAdmin && ! checkprivilege( "read_index" , $xoopsModule->dirname() ) ){
    exit();
}

// get PEAR::XML_Serializer
require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/PEAR/XML/Serializer.php";
$item_data = array() ;
$data = array() ;

// obtain GET/POST parameters
$user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// obtain class instances
$weblog = Weblog::getInstance();

// obtain configuration parameters
$max = $xoopsModuleConfig['rssmax'];

// Limit by user?
if ($user_id > 0) {
  $owner = new XoopsUser($user_id);
  $whosBLog = sprintf(_BL_WHOS_BLOG, $owner->getVar('uname','E'));
  $description = sprintf(_BL_ENTRIES_FOR, $owner->getVar('uname','E'));
  $link = sprintf('%s/modules/%s/index.php?user_id=%d', XOOPS_URL, $xoopsModule->dirname(), $user_id);
} else {
  // No, we must just be getting the last $max entries
  $whosBLog = $xoopsConfig['sitename'];
  $description = $xoopsConfig['slogan'];
  $link = XOOPS_URL.'/';
}

// add weblogs itme
$results = $weblog->getEntries(0, $user_id, 0, $max);
$sign = ( $xoopsConfig['default_TZ'] >= 0 ) ? "+" : "-" ;
foreach($results as $entryObject) {
  $item = array() ;
  $data_excerpt = "" ;
  $dc_creator = $entryObject->getVar('uname') ;
  $item['title'] = encoding_set( $entryObject->getVar('title', 'e') , "UTF-8" );
  $item['link'] = sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $entryObject->getVar('blog_id'));
  $item['dc:date'] = date("Y-m-d" , $entryObject->getVar('created') ) . "T" .date("H:i:s" , $entryObject->getVar('created') ) . $sign . sprintf( "%02d:00",abs($xoopsConfig['default_TZ']) );
  $item['dc:creator'] = $entryObject->getVar('uname') ;
  $description = preg_replace("|<br\s*\/>|i" , "\n" , $entryObject->getVar('contents','s', $entryObject->getVar('blog_id') ,"rss" ) );
  $item['description'] = encoding_set(xoops_substr(htmlspecialchars(strip_tags($description), ENT_QUOTES),0,WEBLOG_TB_EXCERPT_NUM) , 'UTF-8');
  $item["_attributes"] = array( "rdf:about"=> $item["link"]) ;
  $item_data[] = $item ;
}

$options = array(
  "mode" => "simplexml" ,
  "indent"    => "    ",
  "linebreak" => "\n",
  "typeHints" => false,
  "addDecl"   => true,
  "encoding"  => "utf-8",
  "rootName"  => "rdf:RDF",
  "rootAttributes" => array(
       "xmlns" => "http://purl.org/rss/1.0/",
       "xmlns:rdf" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
       "xmlns:dc" => "http://purl.org/dc/elements/1.1/",
       "xmlns:sy" => "http://purl.org/rss/1.0/modules/syndication/",
       "xmlns:admin" => "http://webns.net/mvcb/",
       "xmlns:content" => "http://purl.org/rss/1.0/modules/content/"
       ),
  "defaultTagName" => "item",
  "attributesArray" => "_attributes"
);

$data["channel"] = array(
  "title" => encoding_set( $xoopsModule->getVar("name") , "UTF-8"),
  "link"  => XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/",
  "description" => encoding_set( $xoopsConfig["sitename"] , "UTF-8" ),
  "dc:language" => _LANGCODE ,
  "dc:creator" => encoding_set( $dc_creator, "UTF-8" ),
  "_attributes" => array("rdf:about" => XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/")
);

for( $item_num=0 ; $item_num<count($item_data) ; $item_num++ ){
  $data["channel"]["items"]["rdf:Seq"]["rdf:li"][$item_num]["_attributes"]["rdf:resource"] = $item_data[$item_num]["link"] ;
  $data[] = $item_data[$item_num] ;
}

$Serializer = new XML_Serializer($options);
$status = $Serializer->serialize($data);

if (PEAR::isError($status)){
  die($status->getMessage());
}

header('Content-type: application/xml; charset=utf-8');
echo $Serializer->getSerializedData();
