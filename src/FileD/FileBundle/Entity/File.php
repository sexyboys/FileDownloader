<?php

namespace FileD\FileBundle\Entity;
use FileD\FileBundle\Factory\FileFactory;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileD\FileBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\FileBundle\Entity\FileRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"file" = "File", "directory" = "Directory"})
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
	 * Name of the file 
	 * @var string $name
	 * @ORM\Column(name="name", type="string")
	 */
	protected $name;

	/**
	 * Size of the file
	 * @var string $size
	 * @ORM\Column(name="size", type="string", nullable=true)
	 */
	protected $size;

	/**
	 * Link of the file
	 * @var string $link
	 * @ORM\Column(name="link", type="string", nullable=true)
	 */
	protected $link;

	/**
	 * Creation date of the file
	 * @var DateTime $dateCreation
	 * @ORM\Column(name="date", type="datetime")
	 */
	protected $dateCreation;

	/**
	 * Mime type of the file
	 * @var string mime
	 * @ORM\Column(name="mime", type="string")
	 */
	protected $mime;

	/**
	 * Parent 
	 * @var FileD\FileBundle\Entity\File $parent 
	 * @ORM\ManyToOne(targetEntity="File", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
	 * 
	 */
	protected $parent;

	/**
	 * Children
	 * @var array of FileD\FileBundle\Entity\File $children
	 * @ORM\OneToMany(targetEntity="File", mappedBy="parent", cascade={"remove", "persist"})
	 * @ORM\OrderBy({"mime" = "ASC", "name" = "ASC"})
	 */
	protected $children;

	/**
	 * List of users who have downloaded this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="downloadedFiles")
	 */
	protected $usersDownload;

	/**
	 * User who have uploaded this file
	 * @ORM\ManyToOne(targetEntity="FileD\UserBundle\Entity\User", inversedBy="addedFiles")
	 * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $author;

	/**
	 * List of users who have access to this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="files", cascade={"persist"})
	 */
	protected $usersShare;

	/**
	 * List of users who have marked as seen this file
	 * @ORM\ManyToMany(targetEntity="FileD\UserBundle\Entity\User", mappedBy="seenFiles")
	 */
	protected $usersSeen;

	public function __construct() {
		$this->children = new \Doctrine\Common\Collections\ArrayCollection();
		$this->usersSeen = new \Doctrine\Common\Collections\ArrayCollection();
		$this->usersShare = new \Doctrine\Common\Collections\ArrayCollection();
		$this->usersDownload = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Add Users to usersDownload
	 * @param array of User $users
	 */
	public function addUsersDownload($users) {
		foreach ($users as $user) {
			$this->usersDownload->add($user);
		}
	}

	/**
	 * Remove Users to usersDownload
	 * @param array of User $users
	 */
	public function removeUsersDownload($users) {
		foreach ($users as $user) {
			$this->usersDownload->removeElement($user);
		}
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
	 * Add Users to usersShare
	 * @param array of User $users
	 */
	public function addUsersShare($users) {
		foreach ($users as $user) {
			$this->usersShare->add($user);
		}
	}

	/**
	 * Remove Users to usersShare
	 * @param array of User $users
	 */
	public function removeUsersShare($users) {
		foreach ($users as $user) {
			$this->usersShare->removeElement($user);
		}
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

	/**
	 * Add Users to usersSeen 
	 * @param array of User $users
	 */
	public function addUsersSeen($users) {
		foreach ($users as $user) {
			$this->usersSeen->add($user);
		}
	}

	/**
	 * Remove Users to usersSeen
	 * @param array of User $users
	 */
	public function removeUsersSeen($users) {
		foreach ($users as $user) {
			$this->usersSeen->removeElement($user);
		}
	}

	/**
	 * @param  $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return the string
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * @param string $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * @return the DateTime
	 */
	public function getDateCreation() {
		return $this->dateCreation;
	}

	/**
	 * @param DateTime $dateCreation
	 */
	public function setDateCreation(\DateTime $dateCreation) {
		$this->dateCreation = $dateCreation;
	}

	/**
	 * @return the string
	 */
	public function getMime() {
		return $this->mime;
	}

	/**
	 * @param string $mime
	 */
	public function setMime($mime) {
		$this->mime = $mime;
	}

	/**
	 * @return the string
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param string $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * @return the File
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param File $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * @return the array
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param array $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * Add a child
	 * @param array of File $files
	 */
	public function addChildren($files) {
		foreach ($files as $file) {
			$this->children->add($file);
		}
	}

	/**
	 * Remove a child
	 * @param array of File $files
	 */
	public function removeChildren($files) {
		foreach ($files as $file) {
			$this->children->removeElement($file);
		}
	}

	/**
	 * Define if it's a directory
	 * @return true or false
	 */
	public function isDirectory() {
		return FileFactory::getInstance()->isDirectory($this);
	}

	/**
	 * @return the unknown_type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param unknown_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * String representation of the entity
	 * @return string the representation
	 */
	public function __toString(){
		return "File ( id=".$this->id." , name=".$this->name." )";
	}

}
