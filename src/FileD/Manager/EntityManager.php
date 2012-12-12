<?php
namespace FileD\Manager;

/**
 * Define the manager of Entity
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
  *
  */
abstract class EntityManager{
	
	/**
	 * Entity manager
	 * @var entity manager
	 */
	protected $em;
	
	/**
	 * Logger
	 * @var Logger
	 */
	protected $logger;
	
	/**
	 * Load an entity with its id
	 * @param $id
	 * @return an entity
	 */
	public function load($id) {
		$this->logger->info('[EntityManager]Loading Entity with id '.$id);
		return $this->getRepository()
		->find($id);
	}
	
	/**
	 * Create an entity
	 * @return the new entity
	 */
	public abstract function create();
	
	/**
	 * Save Entity
	 *
	 * @param $entity
	 */
	public function save($entity)
	{
		$this->logger->info('[EntityManager]Saving '.$entity);
		$this->persistAndFlush($entity);
	}
	
	/**
	 * Delete an entity by its id
	 * @param $id
	 */
	public function delete($id){
		$this->logger->info('[EntityManager]Delete Entity with id '.$id);
		$this->em->remove($this->load($id));
		$this->em->flush();
	}
	
	/**
	 * Persist and flush an entity
	 * @param object $entity
	 */
	protected function persistAndFlush($entity)
	{
		$this->em->persist($entity);
		$this->em->flush();
	}
		
	/**
	 * Return the repository of the entity
	 * @return object the repository
	 */
	public abstract function getRepository();
	
}
