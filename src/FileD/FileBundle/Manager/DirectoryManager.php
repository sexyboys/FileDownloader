<?php
namespace FileD\FileBundle\Manager;

use FileD\FileBundle\Manager\FileManager;
use FileD\FileBundle\Entity\Directory;
use Symfony\Bridge\Monolog\Logger;

/**
 * Define the manager of Directory
  * @author epidoux
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
	
}
