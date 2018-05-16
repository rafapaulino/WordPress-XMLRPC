<?php
header('Content-Type: text/html; charset=utf-8');
define('CURRENT_DIR', dirname(__FILE__));

require_once 'vendor/autoload.php';
use BlogConnection\WPObject;
use BlogConnection\Spintax;
use BlogConnection\XMLRPC;

$spintax = new Spintax();

$wordpress = new WPObject(
    'https://mercosurmujeres.org',
    '435346df',
    'warrerewrwr'
);

$title = '{{Inglês} É Bom? Vale {A} Pena? Bônus Extras |Carol Me Ensina {Como} Aprender {Inglês} }';
$title = $spintax->process($title);
$wordpress->setTitle( $title );

$content = file_get_contents( CURRENT_DIR . '/txt/' . rand(1,12) . '.txt');
$content = $spintax->process($content);

$wordpress->setContent( $content );
//print_r($wordpress->getPost());

$xml = new XMLRPC( $wordpress, true );
$response = $xml->insertPost();
var_dump($response);

if ($xml->getConnectError()) {
    echo $xml->getErrorMessage();
}





/*
http://docs.guzzlephp.org/en/stable/overview.html#installation
https://github.com/php-curl-class/php-curl-class
https://github.com/Seldaek/monolog

http://docs.guzzlephp.org/en/stable/request-options.html#progress
https://gist.github.com/39ff/80065cf080c87d4bbb30

xmlrpc:
https://code.tutsplus.com/articles/xml-rpc-in-wordpress--wp-25467
https://linuxprograms.wordpress.com/2010/07/16/wordpress-xml-rpc/
https://linuxprograms.wordpress.com/2010/08/11/wordpress-xmlrpc-metaweblog-newpost/
https://www.tutorialspoint.com/xml-rpc/index.htm

a requisição sempre terá um xml-enconde-request

passos que precisam ser realizados

enviar uma imagem para a biblioteca e informar o id da imagem para ser utilizado depois
deletar um post através do id -- caso eu queira, porém menos importante


segunda etapa
criar os posts agendados - não precisa ter o retorno da url


*/