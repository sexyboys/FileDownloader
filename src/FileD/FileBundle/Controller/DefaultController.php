<?php

namespace FileD\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Handle default access
 * @author epidoux
 * @version 1.0
 *
 */
class DefaultController extends Controller
{
	/**
	 * Access to the generate nav link
	 * 
	 */
    public function indexAction()
    {
    	$page = "Generate";
    	
        return $this->render('FileDFileBundle:Default:index.html.twig', array('page' => $page));
    }
}
