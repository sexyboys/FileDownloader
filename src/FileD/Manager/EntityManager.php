<?php
namespace FileD\Manager;

/**
 * Define the manager of Entity
  * @author epidoux
  * @version 1.0
  *
  */
abstract class EntityManager{
	
	protected $em;
	
	/**
	 * Load an entity with its id
	 * @param $id
	 * @return an entity
	 */
	public function load($id) {
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
		$this->persistAndFlush($entity);
	}
	
	/**
	 * Delete an entity by its id
	 * @param $id
	 */
	public function delete($id){
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
