<?php

namespace FileD\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileD\FileBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FileD\FileBundle\Entity\FileRepository")
 */
class File
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
