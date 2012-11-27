<?php

namespace FileD\UserBundle\Manager;


use Symfony\Bridge\Monolog\Logger;

use FileD\Manager\EntityManager;
use FileD\UserBundle\Entity\User;

/**
 * Define the manager of User
 * @author epidoux
 * @version 1.0
 *
 */
class UserManager extends EntityManager
{

	public function __construct($em,Logger $logger)
	{
		$this->em = $em;
		$this->logger=$logger;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDUserBundle:User');
	}
	
	public function update($user){
		$this->persistAndFlush($user);
		$this->logger->info('[UserManager]Updating '.$user);
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public function create(){
		$this->logger->info('[UserManager]Create new user');
		return new User();
	}
	
   /**
    * Find all active users
    * @return array of users
    */
	public function findActiveUsers()
	{
		$this->logger->info('[UserManager]Find active users');
		return $this->getRepository()->findActiveUsers();
		
	}
}
