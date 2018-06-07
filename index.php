<?php
header('Content-Type: text/html; charset=utf-8');
define('CURRENT_DIR', dirname(__FILE__));

require_once 'vendor/autoload.php';
use BlogConnection\WPObject;
use BlogConnection\Spintax;
use BlogConnection\XMLRPC;

$spintax = new Spintax();

$wordpress = new WPObject(
    'https://loucosmalucos.com.br',
    'jorjao',
    'jorjaoSLRDS@P$'
);

$title = '{{Inglês} É Bom? Vale {A} Pena? Bônus Extras |Carol Me Ensina {Como} Aprender {Inglês} }';
$title = $spintax->process($title);
$wordpress->setTitle( $title );

$content = file_get_contents( CURRENT_DIR . '/txt/' . rand(1,12) . '.txt');
$content = $spintax->process($content);

$wordpress->setContent( $content );
$wordpress->setImage( CURRENT_DIR . DIRECTORY_SEPARATOR . 'image.jpg' );

$wordpress->setCategories(array(
    'categoria',
    'teste de categoria',
    'ninja'
));

$wordpress->setTags(array(
    'tag',
    'teste de tag',
    'tag ninja'
));

//print_r($wordpress->getPost());

$xml = new XMLRPC( $wordpress, true );
$response = $xml->insertPost();
var_dump($response);

if ( $xml->getConnectError() ) {
    echo $xml->getErrorMessage();
} else {
    $img_response = $xml->insertImage($response['id']);
    var_dump($img_response);
}

$taxonomy = $xml->setTaxonomy('teste');
