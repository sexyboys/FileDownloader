<?php
namespace FileD\FileBundle\Manager;

use Symfony\Bridge\Monolog\Logger;

use FileD\Manager\EntityManager;
use FileD\FileBundle\Entity\File;

/**
 * Define the manager of File
  * @author epidoux
  * @version 1.0
  *
  */
class FileManager extends EntityManager{
	
	
	public function __construct($em,Logger $logger)
	{
		$this->em = $em;
		$this->logger=$logger;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDFileBundle:File');
	}
	
	public function update($file){
		$this->persistAndFlush($file);
		$this->logger->info('[FileManager]Update '.$file);
	
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public function create(){
		$this->logger->info('[FileManager]Create new File');
		return new File();
	}
	
	/**
	 * Find file id by path
	 * @param $path the path to match
	 * @return the id of the matching entity
	 */
	public function findIdByPath($path)
	{
		$this->logger->info('[FileManager]find File id by path '.$path);
		return $this->getRepository()->findIdByPath($path);
	}
	
}
