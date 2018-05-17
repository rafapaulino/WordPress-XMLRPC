<?php
namespace BlogConnection;

class WPImage
{
    private $_img;
    private $_contents;
    private $_path;
    private $_mime;
    private $_name;
    private $_fileExists;

    public function __construct( $image )
    {
        if ( file_exists($image) ) {
            
            $this->_path = $image;
            $this->_img =  file_get_contents( $image );
            xmlrpc_set_type( $this->_img, 'base64' );
            $this->setImageMimeType();
            $this->setName();
            $this->setContents();
            $this->_fileExists = true;

        } else {
            $this->_fileExists = false;
            $this->_contents = array();
        }
    }

    private function setName()
    {
        var_dump($this->_path);
        $tmp = explode(DIRECTORY_SEPARATOR, $this->_path);
        $name = $tmp[count($tmp) -1];
        $this->_name = $name;
    }

    private function setImageMimeType()
    {
        $this->_mime = image_type_to_mime_type( exif_imagetype( $this->_path ) );
    }

    private function setContents()
    {
        $this->_contents = array(
            'name' => $this->_name,
            'type' => $this->_mime,
            'bits' => $this->_img,
            'overwrite' => false
        );
    }

    public function getContents()
    {
        return $this->_contents;
    }

    public function getFileExists()
    {
        return (bool) $this->_fileExists;
    }
}