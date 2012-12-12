<?php

namespace FileD\ParamBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Controller which manage Parameters
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 *
 */
class ParameterController extends ContainerAware
{
    public function indexAction()
    {
        return $this->render('FileDParamBundle:Default:index.html.twig', array('name' => $name));
    }
}
