<?php

namespace FileD\FileBundle\Entity;
use FileD\FileBundle\Factory\FileFactory;

use Doctrine\ORM\Mapping as ORM;
use FileD\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileD\FileBundle\Entity\Directory
 * @ORM\Entity(repositoryClass="FileD\FileBundle\Entity\DirectoryRepository")
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

}
