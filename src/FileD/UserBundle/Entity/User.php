<?php

namespace FileD\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * FileD\FileBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\UserBundle\Entity\UserRepository")
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class User extends BaseUser {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * List of added files
	 * @ORM\OneToMany(targetEntity="FileD\FileBundle\Entity\File", mappedBy="author")
	 * @ORM\OrderBy({"mime" = "ASC", "name" = "ASC"})
	 */
	protected $addedFiles;

	/**
	 * List of files available
	 * @ORM\ManyToMany(targetEntity="FileD\FileBundle\Entity\File", inversedBy="usersShare")
     * @ORM\JoinTable(name="users_files_shared")
	 * @ORM\OrderBy({"mime" = "ASC", "name" = "ASC"})
	 */
	protected $files;

	/**
	 * List of files seen/marked as seen
	 * @ORM\ManyToMany(targetEntity="FileD\FileBundle\Entity\File", inversedBy="usersSeen")
     * @ORM\JoinTable(name="users_files_seen")
	 * @ORM\OrderBy({"mime" = "ASC", "name" = "ASC"})
	 */
	protected $seenFiles;

	public function __construct() {
		parent::__construct();
		$this->seenFiles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->files = new \Doctrine\Common\Collections\ArrayCollection();
		$this->addedFiles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->downloadedFiles = new \Doctrine\Common\Collections\ArrayCollection();
	}

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
	public function getAddedFiles() {
		return $this->addedFiles;
	}

	/**
	 * @param unknown_type $addedFiles
	 */
	public function setAddedFiles($addedFiles) {
		$this->addedFiles = $addedFiles;
	}
	
	/**
	 * Add Files to addedFiles
	 * @param array of File $files
	 */
	public function addAddedFiles($files){
		foreach($files as $file){
			$this->addedFiles->add($file);
		}
	}
	
	/**
	 * Remove Files to addedFiles
	 * @param array of File $files
	 */
	public function removeAddedFiles($files){
		foreach($files as $file){
			$this->addedFiles->removeElement($file);
		}
	}

	/**
	 * @return the unknown_type
	 */
	public function getFiles() {
		return $this->files;
	}

	/**
	 * @param unknown_type $files
	 */
	public function setFiles($files) {
		$this->files = $files;
	}
	
	/**
	 * Add Files to files
	 * @param array of File $files
	 */
	public function addFiles($files){
		foreach($files as $file){
			$this->files->add($file);
		}
	}
	
	/**
	 * Remove Files to Files
	 * @param array of File $files
	 */
	public function removeFiles($files){
		foreach($files as $file){
			$this->files->removeElement($file);
		}
	}

	/**
	 * @return the unknown_type
	 */
	public function getSeenFiles() {
		return $this->seenFiles;
	}

	/**
	 * @param unknown_type $seenFiles
	 */
	public function setSeenFiles($seenFiles) {
		$this->seenFiles = $seenFiles;
	}
	
	/**
	 * Add Files to seenFiles
	 * @param array of File $files
	 */
	public function addSeenFiles($files){
		foreach($files as $file){
			$this->seenFiles->add($file);
		}
	}
	
	/**
	 * Remove Files to seenFiles
	 * @param array of File $files
	 */
	public function removeSeenFiles($files){
		foreach($files as $file){
			$this->seenFiles->removeElement($file);
		}
	}
	
	/**
	 * Define if the user given is the same as the current
	 * @param User the user to match
	 * @return true or false 
	 */
	public function equals($user)
	{
		return $user->getId() == $this->id;	
	}
	
	/**
	 * String representation of the entity
	 * @return string the representation
	 */
	public function __toString(){
		return "User ( id=".$this->id." , username=".$this->username." )";
	}

}
