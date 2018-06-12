<?php
namespace BlogConnection;

use BlogConnection\WPImage;

class WPObject
{
	private $_website;
	private $_login;
	private $_password;
    private $_post;
    private $_image;
    
    public function __construct( $website, $login, $password )
	{
		$this->_website = $website;
		$this->_login = $login;
        $this->_password = $password;
        $this->_image = array();
		
		$this->_post = array(
			'mt_allow_comments' => 0, // 1 to allow comments
            'mt_allow_pings' => 0, // 1 to allow trackbacks
            'post_type' => 'post',
            'post_status' => 'publish',
		);

		return $this;
    }
    
    public function setPostType($type)
    {
        $this->_post['post_type'] = $type;

        return $this;
    }

    public function setTitle( $title )
	{
		$this->_post['title'] = trim(htmlentities(strip_tags($title),ENT_NOQUOTES,'UTF-8'));

		return $this;
	}

	public function setContent( $content )
	{
		$this->_post['description'] = $this->correctContent( $content );

		return $this;
	}

	public function setStatus( $status )
	{
		$this->_post['post_status'] = $status;

		return $this;
	}

	public function setCategories( array $categories )
	{
		$this->_post['categories'] = $categories;

		return $this;
    }

    public function setTags( array $tags )
	{
		$this->_post['mt_keywords'] = $tags;

		return $this;
    }

    public function setDate( $date )
    {
        $this->_post['post_date'] = $date;
    }
    
    private function correctContent( $content )
	{
		$content = preg_replace('/\t/', '', trim($content));
		$content = preg_replace( "/\r|\n/", "", $content );
		//return iconv(mb_detect_encoding($content, mb_detect_order(), true), "UTF-8", $content);
		$content = mb_convert_encoding(trim($content),'ISO-8859-1','UTF-8');
		//$content = substr($content, 5,strlen($content));  
		return trim($content);
    }
    
    public function setImage( $image )
    {
        $wpImg = new WPImage( $image );
        
        if ( $wpImg->getFileExists() ) {
            $this->_image = $wpImg->getContents();
        }
    }

    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->_website;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->_post;
    }
}