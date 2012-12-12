<?php

namespace FileD\ParamBundle\Manager;


use Symfony\Bridge\Monolog\Logger;

use FileD\Manager\EntityManager;
use FileD\ParamBundle\Entity\Parameter;

/**
 * Define the manager of Parameter
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 *
 */
class ParameterManager extends EntityManager
{
	
	/**
	 * Constant which define the key parameter of register enable
	 */
	const ENABLE_REGISTER = "enable_register";
	
	/**
	 * Constant which define the key parameter of upload enable
	 */
	const ENABLE_UPLOAD = "enable_upload";
	
	/**
	 * Constant which define the key parameter of share enable
	 */
	const ENABLE_SHARE = "enable_share";
	
	/**
	 * Default values of parameters
	 * @var array values
	 */
	private $DEFAULT_VALUES = array( 
				ParameterManager::ENABLE_REGISTER => "1",
				ParameterManager::ENABLE_UPLOAD => '1',
				ParameterManager::ENABLE_SHARE => "1"
			);
	
	

	public function __construct($em,Logger $logger)
	{
		$this->em = $em;
		$this->logger=$logger;
	}
		
	public function getRepository()
	{
		return $this->em->getRepository('FileDParamBundle:Parameter');
	}
	
	public function update($parameter){
		$this->persistAndFlush($parameter);
		$this->logger->info('[ParameterManager]Updating '.$parameter);
	}
	
   /**
    * Find all parameters
    * @return array of parameters
    */
	public function findParameters()
	{
		$this->logger->info('[ParameterManager]Find active parameters');
		return $this->getRepository()->findActives();
		
	}
	
   /**
    * Find parameter by id
    * @param integer the key of the param
    * @return Parameter
    */
	public function findParameterByKey($key)
	{
		$this->logger->info('[ParameterManager]Find parameter by key '.$key);
		$param = $this->getRepository()->findByKey($key); 
		if($param == null){
			//The parameter doesn't exist, add it with default value
			$param = $this->create();
			$param->setKey($key);
			$param->setValue($this->DEFAULT_VALUES[$key]);
			$param = $this->update($param);
		}
		
		return $param;
	}
	
	/**
	 * Create a new parameter
	 */
	public function create(){
		return new Parameter();
	}
}
