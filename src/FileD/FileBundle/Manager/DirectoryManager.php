<?php
namespace FileD\FileBundle\Manager;

use FileD\FileBundle\Manager\FileManager;
use FileD\FileBundle\Entity\Directory;

/**
 * Define the manager of Directory
  * @author epidoux
  * @version 1.0
  *
  */
class DirectoryManager extends FileManager{
	
	
	public function __construct($em)
	{
		$this->em = $em;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDFileBundle:Directory');
	}
	
	public function update($file){
		$this->persistAndFlush($file);
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public function create(){
		return new Directory();
	}
	
}
