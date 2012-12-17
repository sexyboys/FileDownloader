<?php
namespace FileD\FileBundle\Manager;

use FileD\FileBundle\Manager\FileManager;
use FileD\FileBundle\Entity\Directory;
use Symfony\Bridge\Monolog\Logger;

/**
 * Define the manager of Directory
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
  *
  */
class DirectoryManager extends FileManager{
	
	
	public function __construct($em,Logger $logger)
	{
		$this->em = $em;
		$this->logger=$logger;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDFileBundle:Directory');
	}
	
	public function update($file){
		$this->logger->info('[DirectoryManager]Updating '.$file);
		$this->persistAndFlush($file);
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public function create(){
		$this->logger->info('[DirectoryManager]Create new Directory');
		return new Directory();
	}

	/**
	 * Find directories which have the given user shared and the given parent file
	 * @param User the user
	 * @param File the parent file
	 * @return the files
	 */
	public function findDirectoriesShared($user,$parent)
	{
	
		//Then get others sorted by name only
		$array = $this->getRepository()->findDirectoriesShared($user,$parent);
		$this->logger->info('[DirectoryManager]Find '.count($array).' directories which are shared with user '.$user.' and child of '.$parent);
		return $array;
	}
	
	/**
	 * Reset directories parents size from the given directory
	 * @param Directory the dir
	 */
	public function resetSize($dir)
	{
		if($dir!=null){
			$dir->setSize("0");
			$this->update($dir);
			if($dir->getParent()!=null) $this->resetSize($dir->getParent());
			
			$this->logger->info('[DirectoryManager]Reset size of directory '.$dir->getId());
		}
	}
	
	
	
}
