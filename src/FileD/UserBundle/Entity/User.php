<?php

namespace FileD\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * FileD\FileBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\UserBundle\Entity\UserRepository")
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
	 * List of downloaded files
	 * @ORM\ManyToMany(targetEntity="FileD\FileBundle\Entity\File", inversedBy="usersDownload")
     * @ORM\JoinTable(name="users_files_downloaded")
	 * 
	 */
	protected $downloadedFiles;

	/**
	 * List of added files
	 * @ORM\OneToMany(targetEntity="FileD\FileBundle\Entity\File", mappedBy="author")
	 */
	protected $addedFiles;

	/**
	 * List of files available
	 * @ORM\ManyToMany(targetEntity="FileD\FileBundle\Entity\File", inversedBy="usersShare")
     * @ORM\JoinTable(name="users_files_shared")
	 */
	protected $files;

	/**
	 * List of files seen/marked as seen
	 * @ORM\ManyToMany(targetEntity="FileD\FileBundle\Entity\File", inversedBy="usersSeen")
     * @ORM\JoinTable(name="users_files_seen")
	 */
	protected $seenFiles;

	public function __construct() {
		parent::__construct();
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
	public function getDownloadedFiles() {
		return $this->downloadedFiles;
	}

	/**
	 * @param unknown_type $downloadedFiles
	 */
	public function setDownloadedFiles($downloadedFiles) {
		$this->downloadedFiles = $downloadedFiles;
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

}
