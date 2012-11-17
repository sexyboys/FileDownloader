<?php

namespace FileD\FileBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
	 * Name of the file
	 * @var string $name
	 * @ORM\Column(name="name", type="string")
	 */
	protected $name;

	/**
	 * Size of the file
	 * @var string $size
	 * @ORM\Column(name="size", type="string")
	 */
	protected $size;

	/**
	 * Link of the file
	 * @var string $link
	 * @ORM\Column(name="link", type="string")
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
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
	 */
	protected $parent;
	
	/**
	 * Children
	 * @var array of FileD\FileBundle\Entity\File $children
	 * @ORM\OneToMany(targetEntity="File", mappedBy="parent")
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
	
	public function __construct() {
		$this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
	public function setName(string $name) {
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
	public function setLink(string $link) {
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
	public function setDateCreation(DateTime $dateCreation) {
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
	public function setMime(string $mime) {
		$this->mime = $mime;
	}

}
