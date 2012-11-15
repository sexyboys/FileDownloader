<?php

namespace FileD\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileD\FileBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\FileBundle\Entity\FileRepository")
 */
class File {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * List of users who have downloaded this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="downloadedFiles")
	 */
	protected $usersDownload;

	/**
	 * User who have uploaded this file
	 * @ORM\ManyToOne(targetEntity="FileD\UserBundle\Entity\User", inversedBy="addedFiles")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
	 */
	protected $author;

	/**
	 * List of users who have access to this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="files")
	 */
	protected $usersShare;

	/**
	 * List of users who have marked as seen this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="seenFiles")
	 */
	protected $usersSeen;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the unknown_type
	 */
	public function getUsersDownload() {
		return $this->usersDownload;
	}

	/**
	 * @param unknown_type $usersDownload
	 */
	public function setUsersDownload($usersDownload) {
		$this->usersDownload = $usersDownload;
	}

	/**
	 * @return the unknown_type
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @param unknown_type $author
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * @return the unknown_type
	 */
	public function getUsersShare() {
		return $this->usersShare;
	}

	/**
	 * @param unknown_type $usersShare
	 */
	public function setUsersShare($usersShare) {
		$this->usersShare = $usersShare;
	}

	/**
	 * @return the unknown_type
	 */
	public function getUsersSeen() {
		return $this->usersSeen;
	}

	/**
	 * @param unknown_type $usersSeen
	 */
	public function setUsersSeen($usersSeen) {
		$this->usersSeen = $usersSeen;
	}

}
