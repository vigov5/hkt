<?php
/**
 * @author Tran Duc Thang
 * @date 4/6/14
 *
 */

 class FileHelper
 {
     private $_path;
     private $_path_info;
     private $_img_formats = ['png', 'jpg', 'jpeg', 'gif'];

     public function __construct($path)
     {
         $this->setPath($path);
     }

     public function setPath($path)
     {
         $this->_path = $path;
         $this->_path_info = pathinfo($path);
     }

     public function getPath()
     {
         return $this->_path;
     }

     public function getDirname()
     {
         return $this->_path_info['dirname'];
     }

     public function getBasename()
     {
         return $this->_path_info['basename'];
     }

     public function getExtension()
     {
         return isset($this->_path_info['extension']) ? $this->_path_info['extension'] : null;
     }

     public function getFilename()
     {
         return $this->_path_info['filename'];
     }

     public function isValidImage()
     {
         return in_array($this->getExtension(), $this->_img_formats);
     }

     public function isValidUrl()
     {
         return filter_var($this->_path, FILTER_VALIDATE_URL);
     }

     public function uploadImage($file)
     {
         if ((($file["type"] == "image/gif")
              || ($file["type"] == "image/jpeg")
              || ($file["type"] == "image/jpg")
              || ($file["type"] == "image/pjpeg")
              || ($file["type"] == "image/x-png")
              || ($file["type"] == "image/png"))
             && ($file["size"] < Config::MAX_IMG_FILE_SIZE)
             && in_array($this->getExtension(), $this->_img_formats))
         {
             if ($file["error"] > 0) {
                 return false;
             } else {
                if (file_exists($this->_path)) {
                    $this->setPath($this->generateFileName());
                }
                move_uploaded_file($file["tmp_name"], $this->_path);
                return true;
             }
         } else {
             return false;
         }
     }

     public function generateFileName()
     {
         return $this->getDirname() . '/' . $this->getFilename() . '_' . md5(time()) . '.' . $this->getExtension();
     }
 }