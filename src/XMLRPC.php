<?php

namespace BlogConnection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class XMLRPC
{
	private $_wordpress;
    private $_encode;
    private $_conn;
    private $_connectError;
    private $_errorMessage;
    private $_statusCode;
    private $_debug;
    private $_log;
    private $_logPath;
    private $_logName;
    private $_request;

	public function __construct( $wp, $debug = false, $timeout = 60, $verifyssl = false )
	{
		$this->_encode = array(
			'encoding' => 'UTF-8'
        );
        
        $this->_wordpress = $wp;
        $this->_statusCode = 200;

        $this->_conn = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->_wordpress->getWebsite(),
            // You can set any number of default request options.
            'timeout'  => $timeout,
            //verify certificate false
            'verify' => $verifyssl
        ]);
        
        //set default path
        $this->setPath();

        //set default log name
        $this->setLog('xmlrpc');

        return $this;
    }

    public function setLog($name)
    {
        $this->_logName = date("Ymd") . '-' . $name . '.log';

        // create a log channel
        $this->_log = new Logger($name);
        $this->_log->pushHandler(
            new StreamHandler(
                $this->getPath() . $this->getLogName(), 
                Logger::WARNING
            )
        );
    }

    public function setPath($path = "") 
    {
        if (trim($path) == "")
            $this->_logPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        else 
            $this->_logPath = $path;
        
        @chmod($this->_logPath, 0777);
    }

    public function getPath()
    {
        return $this->_logPath;
    }

    public function getLogName()
    {
        return $this->_logName;
    }

    public function insertPost()
	{
        $response = array(
			'error' => true
        );
        $result = array();
        $this->_request = xmlrpc_encode_request('metaWeblog.newPost', array(
				1,
				$this->_wordpress->getLogin(), 
				$this->_wordpress->getPassword(), 
				$this->_wordpress->getPost(), 
				true
			),
			$this->_encode
        );

        $output = $this->connect();
        if ( is_numeric($output) ) {
            $result = $this->getPostUrl( $output );
        }

        return array_merge($response, $result);
    }
    
    private function connect()
    {
        $output = null;
        try {

            $url = $this->_wordpress->getWebsite() . '/xmlrpc.php';
            $response = $this->_conn->request('POST', $url, [
                'body' => $this->_request,
                'allow_redirects' => false,
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Content-Length' => strlen($this->_request),
                ]
            ]);

            $this->_statusCode = $response->getStatusCode();
            $output = xmlrpc_decode($response->getBody()->getContents());
            
            if ($this->_statusCode !== 200) {
                $message = "Unable to access website, url( " . $this->_wordpress->getWebsite() ." ) access returned code: " . $this->_statusCode;
                $this->_connectError = true;
                $this->setErrorMessage($message);
            } 
        } catch (ConnectException $e) {
            $this->_connectError = true;
            $message = $e->getMessage();
            $this->setErrorMessage($message);
            $this->_statusCode = 500;
        }
        return $output;
    }

    private function setErrorMessage($value)
    {
        $this->_errorMessage = $value;
        $this->setException($value);
    }

    private function setException($value)
    {
        if ($this->_debug === true) {
            throw new \Exception($value);
        } else {
            $this->_log->error($value);
        }
    }

    private function getPostUrl( $id )
	{
        $response = array();
        if ( is_numeric($id) ) {

			$this->_request = xmlrpc_encode_request('metaWeblog.getPost', array(
					$id,
					$this->_wordpress->getLogin(), 
					$this->_wordpress->getPassword()
				),
				$this->_encode
			);
			$post = $this->connect();

			if (is_array($post)) {
				$response = array(
					'id' => $post['postid'],
					'url' => $post['permaLink'],
					'error' => false
				);
			}
		}
		return $response;
    }
    
    public function getConnectError()
    {
        return $this->_connectError;
    }

    public function getErrorMessage()
    {
        return (string) $this->_errorMessage;
    }
}