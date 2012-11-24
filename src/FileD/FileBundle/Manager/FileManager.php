<?php
namespace FileD\FileBundle\Manager;

use FileD\Manager\EntityManager;
use FileD\FileBundle\Entity\File;

/**
 * Define the manager of File
  * @author epidoux
  * @version 1.0
  *
  */
class FileManager extends EntityManager{
	
	
	public function __construct($em)
	{
		$this->em = $em;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDFileBundle:File');
	}
	
	public function update($file){
		$this->persistAndFlush($file);
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public function create(){
		return new File();
	}
	
	/**
	 * Find file id by path
	 * @param $path the path to match
	 * @return the id of the matching entity
	 */
	public function findIdByPath($path)
	{
		return $this->getRepository()->findIdByPath($path);
	}
	
}
