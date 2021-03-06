<?php

namespace FileD\FileBundle\Entity;
use FileD\FileBundle\Factory\FileFactory;

use Doctrine\ORM\Mapping as ORM;
use FileD\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileD\FileBundle\Entity\Directory
 * @ORM\Entity(repositoryClass="FileD\FileBundle\Entity\DirectoryRepository")
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class Directory extends File {
	

	public function __construct() {
		$this->mime = FileFactory::getInstance()->getMimeType('dir');
		parent::__construct();
	}
		
	/**
	 * Define if it's a directory
	 * @return true or false
	 */
	public function isDirectory(){
		return true;
	}
	
	/**
	 * String representation of the entity
	 * @return string the representation
	 */
	public function __toString(){
		return "Directory ( id=".$this->id." , name=".$this->name." )";
	}

}
