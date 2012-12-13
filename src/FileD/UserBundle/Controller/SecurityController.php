<?php

namespace FileD\UserBundle\Controller;

use FileD\ParamBundle\Manager\ParameterManager;

use FileD\FileBundle\Factory\FileFactory;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends ContainerAware
{
	/**
	 * Sign in action rendering form login or action
	 * @param GET file the file id
	 * @param GET seen if we have to render only seen files or by default the new ones
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 */
    public function loginAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->container->get('session')->setFlash('fos_error',$this->container->get('translator')->trans('user.login.flash.error'));
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            $this->container->get('session')->setFlash('fos_error',$this->container->get('translator')->trans('user.login.flash.error'));
        } else {
            $error = '';    
            $this->container->get('session')->setFlash('fos_error',$this->container->get('translator')->trans('user.login.flash.error'));
        
        }

        if ($error) {
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        
        //Get the parent given through GET of files displayed
		$fileId = array_key_exists('file', $_GET)?$_GET['file']:null;
		$fileParent = null;
		if($fileId!=null) $fileParent = $this->container->get('filed_user.file')->load($fileId);
				
        //Loading files children of the given parent file only iff the file is shared to the user
        $user = $this->container->get('security.context')->getToken()->getUser();
        $hasRight = $fileParent!=null && FileFactory::getInstance()->isSharedWith($user,$fileParent)?true:false;
        $files = null;
        if($fileParent != null && $hasRight){
			//Getting children and only those with rights on it
			$files = array();
			$needSeen = false;
			if(array_key_exists('seen', $_GET) && $_GET['seen']!=null && $_GET['seen']!="") $needSeen=$_GET['seen'];
        	foreach($fileParent->getChildren() as $child){
				if(FileFactory::getInstance()->isSharedWith($user,$child)){
					//check with the seen option and add it if we need it
					$mark = FileFactory::getInstance()->isMarkedAsSeenBy($user,$child);
					if(!$needSeen && !$mark){//only not seen yet
						$files[] = $child;		
					}
					else if($needSeen && $mark){//all seen only
						$files[] = $child;
					}
				}
			}
        }
        else{
        	//Default root option
			$needSeen = false;
			if(array_key_exists('seen', $_GET) && $_GET['seen']!=null && $_GET['seen']!="") $needSeen=$_GET['seen'];
        	if($user !=null && is_object($user)){
	        	$userFiles = $user->getFiles();
	        	//We get only root files (with no parent)
	        	foreach($userFiles as $file){
	        		$mark = FileFactory::getInstance()->isMarkedAsSeenBy($user,$file);

	        		if($file->getParent()==null){
		        		if(!$needSeen && !$mark){//only not seen yet
		        			$files[] = $file;
		        		}
		        		else if($needSeen && $mark){//all seen only
		        			$files[] = $file;
		        		}
	        			
	        		}
        		}
        	}
        	$fileId=0;
        }
        
        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        	'files' => $files,
        	'fileId' => $fileId,
        	'showMarkedAsSeen'=> $needSeen,
        	"enable_register" => $this->container->get('filed_user.param')->findParameterByKey(ParameterManager::ENABLE_REGISTER)
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf('FileDUserBundle:User:index.html.%s', $this->container->getParameter('fos_user.template.engine'));
	
        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
