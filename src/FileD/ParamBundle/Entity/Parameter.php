<?php

namespace FileD\ParamBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * FileD\ParamBundle\Entity\Parameter
 * Entity describing Internal app parameter
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\ParamBundle\Entity\ParameterRepository")
 */
class Parameter {
	/**
	 * @var key string
	 *
	 * @ORM\Column(name="id", type="string")
	 * @ORM\Id
	 */
	protected $key;

	/**
	 * @var value string
	 * @ORM\Column(name="value",type="string")
	 */
	protected $value;

	/**
	 * Define if the parameter given is the same as the current
	 * @param Parameter the param to match
	 * @return true or false 
	 */
	public function equals($param) {
		return $param->getKey() == $this->key;
	}

	/**
	 * String representation of the entity
	 * @return string the representation
	 */
	public function __toString() {
		return "Parameter ( key=" . $this->key . " , value=" . $this->value
				. " )";
	}

	/**
	 * @return the key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @param  $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * @return the value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

}
