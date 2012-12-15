<?php
namespace FileD\FileBundle\Manager;

use Symfony\Bridge\Monolog\Logger;

use FileD\Manager\EntityManager;
use FileD\FileBundle\Entity\File;

/**
 * Define the manager of File
 * @author epidoux <eric.pidoux@gmail.com>
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
	
	/**
	 * Find file by hash
	 * @param string the hash
	 * @return the file
	 */
	public function findFileByHash($hash)
	{
		$this->logger->info('[FileManager]find File by hash '.$hash);
		return $this->getRepository()->findByHash($hash);
	}

	/**
	 * Find files which have the given user shared and the given parent file
	 * @param User the user
	 * @param File the parent file
	 * @return the files
	 */
	public function findFilesShared($user,$parent)
	{
	
		$this->logger->info('[UserManager]Find files which are shared with user '.$user.' and child of '.$parent);
		return $this->getRepository()->findFilesShared($user,$parent);
	}
	
}
