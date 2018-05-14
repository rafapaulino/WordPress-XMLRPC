<?php
require_once 'vendor/autoload.php';

use BlogConnection\Connection;

$conn = new Connection(
    'http://sonytv.com.br',
    'rafaclasses@gmail.com',
    'NyN6KN8iXdEtmc(ucor8@$$F'
);

$conn2 = new Connection(
    'https://dsomeluz.com.br',
    'tereza',
    'QB@&22K#$N%4M&1@6&5L'
);

$conn3 = new Connection(
    'https://dsomeluz43756874365.com.br',
    'tereza',
    'QB@&22K#$N%4M&1@6&5L'
);

if ($conn3->getConnectError()) {
    echo $conn3->getErrorMessage();
}


/*
http://docs.guzzlephp.org/en/stable/overview.html#installation
https://github.com/php-curl-class/php-curl-class
https://github.com/Seldaek/monolog


xmlrpc:
https://code.tutsplus.com/articles/xml-rpc-in-wordpress--wp-25467
https://linuxprograms.wordpress.com/2010/07/16/wordpress-xml-rpc/
https://linuxprograms.wordpress.com/2010/08/11/wordpress-xmlrpc-metaweblog-newpost/
https://www.tutorialspoint.com/xml-rpc/index.htm

a requisição sempre terá um xml-enconde-request

passos que precisam ser realizados

se conectar ao blog - tem que retornar um sucesso ou não para a conexão
em caso de falha informar no log os motivos

enviar uma imagem para a biblioteca e informar o id da imagem para ser utilizado depois

enviar um post para o site e vincular a imagem com o post + a categoria e tags informadas

pegar a url do post

deletar um post através do id -- caso eu queira, porém menos importante


segunda etapa
criar os posts agendados - não precisa ter o retorno da url

utlizar os exemplos da pdo de conexao e update para escrever as chamadas da classe


exemplos de conexões:
https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html
http://php.net/manual/pt_BR/pdo.connections.php
https://knpuniversity.com/screencast/symfony3-doctrine/insert-object
https://stackoverflow.com/questions/24114367/how-to-write-an-insert-query-in-doctrine
http://www.diogomatheus.com.br/blog/php/trabalhando-com-pdo-no-php/
https://phpunit.de/getting-started/phpunit-6.html
http://docs.guzzlephp.org/en/stable/

*/