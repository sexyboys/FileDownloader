<?php

namespace FileD\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FileD\UserBundle\Entity\User;

/**
 * User controller.
 *
 * @Route("/account")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="account")
     * @Template()
     */
    public function indexAction()
    {
    	$this->get('session')->set("page","Accueil");
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FileDUserBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="account_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileDUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
