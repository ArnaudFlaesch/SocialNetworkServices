<?php

/**
 * Description of Post
 *
 * @author Arnaud
 */
class Post {
    private $_contentPost;
    private $_datePost;
    
    public function __construct($contentPost, $datePost) {
        $this->_contentPost = $contentPost;
        $this->_datePost = $datePost;
    }
    
    public function getPostContent() {
        return $this->_contentPost;
    }
    
    public function getPostDate() {
        return $this->_datePost;
    }
    
}
