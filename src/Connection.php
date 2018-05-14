<?php

namespace BlogConnection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Connection
{
    private $_website;
    private $_login;
    private $_password;
    private $_connectError;
    private $_errorMessage;
    private $_statusCode;
    private $_debug;
    private $_client;
    private $_log;
    private $_logPath;
    private $_logName;

    public function __construct($website, $login, $password, $debug = false)
    {
        $this->_website = $website;
        $this->_login = $login;
        $this->_password = $password;
        $this->_connectError = false;
        $this->_debug = $debug;
        $this->_statusCode = 200;
        
        //set default log name and path
        $this->setPath();
        $path = $this->getLogPath();
        $this->setLog('xmlrpc', $path);

        $this->_client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $website,
            // You can set any number of default request options.
            'timeout'  => 120,
            //verify certificate false
            'verify' => false
        ]);
        
        //verify if website is accessible
        $this->verifyConnection();
    }

    private function verifyConnection()
    {
        try {
            $response = $this->_client->get($this->_website);
            $code = $response->getStatusCode();
            $this->setStatusCode($code);
        
            if ($code !== 200) {
                $message = __("Unable to access website, url( " .$this->_website." ) access returned code: " . $code, "wpxmlrpc");
                $this->_connectError = true;
                $this->setErrorMessage($message);
            }
        } catch (ConnectException $e) {
            $this->_connectError = true;
            $message = $e->getMessage();
            $this->setErrorMessage($message);
            $this->setStatusCode(500);
        }       
    }

    public function getConnectError()
    {
        return $this->_connectError;
    }

    private function setErrorMessage($value)
    {
        $this->_errorMessage = $value;
        $this->setException($value);
    }

    public function getErrorMessage()
    {
        return (string) $this->_errorMessage;
    }

    private function setException($value)
    {
        if ($this->_debug === true) {
            throw new \Exception($value);
        } else {
            $this->_log->error($value);
        }
    }

    public function getWebsite()
    {
        return $this->_website;
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    private function setStatusCode($code)
    {
        $this->_statusCode = intval($code);
    }

    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    public function setLog($name,$path)
    {
        $this->setPath($path);
        $path = $this->getLogPath();
        $this->setLogName($name);
        $logName = $this->getLogName();
        // create a log channel
        $this->_log = new Logger($name);
        $this->_log->pushHandler(new StreamHandler($path . $logName, Logger::WARNING));
    }

    public function getLogName()
    {
        return $this->_logName;
    }

    private function setLogName($name)
    {
        $this->_logName = date("Ymd") . '-' . $name . '.log';
    }

    private function setPath($path = "") 
    {
        if (trim($path) == "") {
            $this->_logPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        } else {
            $this->_logPath = $path;
        }
        @chmod($this->_logPath,0777);
    }

    public function getLogPath()
    {
        return $this->_logPath;
    }
}