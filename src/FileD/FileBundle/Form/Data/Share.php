<?php
namespace FileD\FileBundle\Form\Data;

/**
 * Define a class of sharing files using by the form
 * @author Eric Pidoux
 * @version 1.0
 */
class Share {

	/**
	 * Id of the file
	 * @var integer file
	 */
	private $id;

	/**
	 * Array of users
	 * @var array of user
	 */
	private $users;

	/**
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param integer $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the array
	 */
	public function getUsers() {
		return $this->users;
	}

	/**
	 * @param array $users
	 */
	public function setUsers($users) {
		$this->users = $users;
	}

}
