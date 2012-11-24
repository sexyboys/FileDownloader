<?php

namespace FileD\FileBundle\Controller;


use FileD\FileBundle\Form\Type\ShareFileType;

use FileD\FileBundle\Form\Data\Share;

use Symfony\Component\HttpFoundation\Response;

use FileD\FileBundle\Factory\FileFactory;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FileD\FileBundle\Entity\File;
use FileD\FileBundle\Form\FileType;
use FileD\FileBundle\Manager\UploadManager;

/**
 * File controller.
 *
 */
class FileController extends Controller
{
    /**
     * Lists all File entities.
     *
     * @Route("/", name="file")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FileDFileBundle:File')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a File entity.
     *
     * @Route("/{id}/show", name="file_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileDFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays the view to upload file (modal box)
     *
     * @Route("/new", name="file_new")
     * @Template()
     */
    public function newAction($file_id)
    {
        return array(
            'file_id' => $file_id
        );
    }

    /**
     * Creates a new File entity.
     *
     * @Route("/create", name="file_create")
     * @Method("POST")
     * @Template("FileDFileBundle:File:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new File();
        $form = $this->createForm(new FileType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing File entity.
     *
     * @Route("/{id}/update", name="file_update")
     * @Method("POST")
     * @Template("FileDFileBundle:File:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileDFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FileType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Add files to be uploaded through multiupload plugin
     * @param $_FILES['files']
     * @param $_POST['parent']
     */
    public function addfileAction(){

    	$uploaded_file = new \stdClass();
    	try{
    		
    		$file = $this->container->get('filed_file.file')->create();
    		
    		//upload the file
    		if ($_FILES['files']['tmp_name'][0]!="" && $_FILES['files']['error'][0] == 0) {

    			if(array_key_exists('parent', $_POST) && $_POST['parent']!=0){
    				$parent = $this->container->get('filed_file.file')->load($_POST['parent']);
    				if($parent!=null)$file->setParent($parent);
    				else throw new \Exception("Can't find parent with id "+$_POST['parent']);

    				$path = $parent->getLink()."/".$_FILES['files']['name'][0];
    			}
    			else $path =  __DIR__."/../../../../web/data/uploads/files/".$_FILES['files']['name'][0];
    			 
    			$resu =move_uploaded_file($_FILES['files']['tmp_name'][0], $path);
    			 
		    	//Create the entity and the response file
		    	$file->setName($_FILES['files']['name'][0]);
		    	$file->setSize($_FILES['files']['size'][0]);
		    	$user= $this->get('security.context')->getToken()->getUser();
		    	$file->setAuthor($user);
		    	$file->setMime($_FILES['files']['type'][0]);
		    	$file->setDateCreation(new \DateTime());
		    	$file->addUsersDownload(array($user));
		    	$file->addUsersShare(array($user));
		    	$file->setLink($path);
		    	$this->container->get('filed_file.file')->update($file);
		    	
		    	$user->addFiles(array($file));
		    	$this->container->get('fos_user.user_manager')->updateUser($user);

		    	$uploaded_file->name = $file->getName();
		    	$uploaded_file->size = intval($file->getSize());
		    	$uploaded_file->type = $file->getMime();
            	$uploaded_file->path = $file->getLink();

            	header('Content-type: application/json');
		    	$response = json_encode(array($uploaded_file));
    		}
    		else $response = new Response("false");

    	}
    	catch(\Exception $e){
    		//TODO Exception
    		$response = new Response($e->getMessage());
    	}
    	
    	return new Response($response);
    }


    /**
     * Share a file/repo from the server
     * @param $_POST['id'] id of the parent repo
     * @param $_POST['path'] server path of file/repo to share
     */
    public function addfileserverAction(){
    	try{
    		$path = $_POST['path'];
    		$id = $_POST['id'];
    		$response = "";
    		$this->addFileFromServer($path, $id);
    		
    	}
    	catch(\Exception $e){
    		
    		$response = $this->container->get('translator')->trans('msg.error.addfileserver.wrongrights').' '.$e->getMessage();
    	}
    	return new Response($response);
    }
    
    /**
     * Add a file/repo from the server
     * @param $path the path of the file/repo to add
     * @param $parent the parent id
     */
    private function addFileFromServer($path,$parent){
		//Add the first file
    	if(is_dir($path)){
			$em = $this->container->get('filed_file.directory');
    		$name = basename($path);
    		$mime= FileFactory::getInstance()->getMimeType('dir');
    	}
    	else{
    		//only file
			$em = $this->container->get('filed_file.file');
    	    $name = basename($path);
    	    $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    	}
    	//check if it exists by its path
    	$id_find_file = $this->container->get('filed_file.file')->findIdByPath($path);
    	$file=null;
    	if($id_find_file==null || $id_find_file==0){
	    	$file  = $em->create();
	    	$file->setName($name);
	    	$file->setSize(filesize($path));
	    	$user= $this->get('security.context')->getToken()->getUser();
	    	$file->setAuthor($user);
	    	$file->setMime($mime);
	    	$file->setDateCreation(new \DateTime());
	    	$file->addUsersDownload(array($user));
	    	$file->addUsersShare(array($user));
	    	$file->setLink($path);
	    	
	    	if($parent!=null && $parent!=0){
				$parent_dir = $this->container->get('filed_file.directory')->load($parent);
	    		$file->setParent($parent_dir);
	    	}
	    	$em->update($file);
	    	$user->addFiles(array($file));
	    	$this->container->get('fos_user.user_manager')->updateUser($user);
    	}
    	
    	if(is_dir($path)){
    		//Add file for children
    		$filenames = scandir($path);
    		foreach($filenames as $filename){
    			if($filename!="." && $filename !=".."){
					//reconstruct the path
					if(substr($path,-1) != "/"){
						$path.="/";
					}
    				$this->addFileFromServer($path.$filename, $file!=null?$file->getId():$id_find_file);
    			}
    		}
    	}
    }
    
    /**
     * Add a repository
     * @param $_POST['id'] id of the parent repository
     */
    public function addrepositoryAction(){
    	$id = $_POST['id'];
    	
    	$entity  = $this->container->get('filed_file.directory')->create();
    	$entity->setName("New Folder");
    	$user= $this->get('security.context')->getToken()->getUser();
    	if($user!=null){

    		if($id>0){
    			//find the parent
    			$parent = $this->container->get('filed_file.file')->load($id);
    			$entity->setParent($parent);
    		}
    		
	    	$entity->setAuthor($user);
	    	//enable sharing for the current user
	    	$users = array();
	    	$users[]=$user;
	    	$entity->addUsersShare($users);
	    	$entity->setDateCreation(new \DateTime());
	    	$entity->setSize(0);
	    	$entity->setMime(FileFactory::getInstance()->getMimeType('dir'));
	    	
	    	//create the directory
    	
    		if($entity->getParent()!=null){
    			$path = $entity->getParent()->getLink();
    		}
    		else $path =  __DIR__."/../../../../web/data/uploads/files";
    		
    		
    		$path.="/".date('U');
    		$result = mkdir($path,0755,true);
	    	if($result){
	    		//save the entity
	    		$entity->setLink($path);
	    		$this->container->get('filed_file.file')->update($entity);
		    	$user->addAddedFiles(array($entity));
		    	$user->addFiles(array($entity));
				$this->container->get('fos_user.user_manager')->updateUser($user);
	    	}
	    	else return new Response('false');
	    	return new Response('true');
    	}
    	else return new Response('false');
    }
    
    /**
     * Render files 
     * @param $files the files to be display
     * @param $fileId the parent id of the files displayed to generate the url to get back
     * @param $last_username property used to render login form
     * @param $csrf_token property used to render login form
     * @return the rendering view.html.twig with files loaded
     */
    public function viewFilesAction($files,$fileId,$last_username,$csrf_token){
    	
    	$template = sprintf('FileDFileBundle:File:view.html.%s', $this->container->getParameter('fos_user.template.engine'));
    	
    	//Get the parent id
    	$parent_id=-1;
    	$file_id=0;
    	if($fileId!=0){
    		$file = $this->container->get('filed_file.file')->load($fileId);
    		$file_id=$file->getId();
	    	if($file!=null && is_object($file)){
		    	$parent = $file->getParent();
		    	if($parent!=null && is_object($parent)){
		    		$parent_id = $parent->getId();
		    	}
		    	else $parent_id=null;
	    	}
	    	else $parent_id=0;
    	}
    	
    	
    	
    	return $this->container->get('templating')->renderResponse($template, 
    			array('files' => $files, 
    				  'parent_id' => $parent_id,
    					'file_id' => $file_id,
    				   'last_username' => $last_username,
    					'csrf_token' => $csrf_token));
    }
    


    /**
     * Edit a file (name only) from the list view
     * @param $_POST['id'] the id of the file
     * @param $_POST['name'] the name of the file
     * @return the new name
     */
    public function editAction()
    {
    	try{
	    	$id = $_POST['id'];
	    	$name = $_POST['name'];
	    	$file = $this->container->get('filed_file.file')->load($id);
	    	$file->setName($name);
	    	$this->container->get('filed_file.file')->update($file);
	    	return new Response($name);
    	}
    	catch(\Exception $e){
    		return new Response($e->getMessage());
    	}
    	
    }
    


    /**
     * Deletes a File entity.
     * @param $_POST['id'] the id of the file
     * @return true or false
     */
    public function deleteAction()
    {
    	$resu = null;
    	try{
	    	$id = $_POST['id'];
	    	//Delete the local file/directory
	    	$file = $this->container->get('filed_file.file')->load($id);
	    	if($file->isDirectory()){
	    		$dir = $file->getLink();
    			$this->rrmdir($dir);
	    	}
	    	else $resu = unlink($file->getLink());
    	}
    	catch(\Exception $e){
    		
    	}

    	$this->container->get('filed_file.file')->delete($id);
    	
    	return new Response("true");
    	
    }
    
    /**
     * Get the size of a directory 
     * @param $id the id of the directory
     * @return string the size
     */
    public function getSizeDirectoryAction($id){
		$size=0;
    	$file = $this->container->get('filed_file.file')->load($id);
    	if($file!=null){
    		$size=$this->recursiveSize($file, $size);
    	}
    	if($size < 1000){
    		$response = $size." ".$this->container->get('translator')->trans('file.size.unit');
    	}
    	else if($size < 1000000){
    		$response = ($size/1000)." ".$this->container->get('translator')->trans('file.size.unit.kilo');
    	}
    	else if($size < 1000000000){
    		$response = ($size/1000000)." ".$this->container->get('translator')->trans('file.size.unit.mega');
    	}
    	else if($size < 1000000000000){
    		$response = ($size/1000000000)." ".$this->container->get('translator')->trans('file.size.unit.giga');
    	}
    	else{
    		$response = ($size/1000000000000)." ".$this->container->get('translator')->trans('file.size.unit.tera');
    	}
    	return new Response($response);
    }
    
    /**
     * Define the size recursively of file/dir given
     * @param File $file
     * @param Integer $size
     * @return the size
     */
    private function recursiveSize($file,$size){
    	$size+=$file->getSize();
    	if($file->isDirectory() && $file->getChildren()->count()>0){
    		foreach($file->getChildren() as $child){
    			$size=$this->recursiveSize($child, $size);
    		}
    	}
    	return $size;
    }
    
    /**
     * Download the file/dir
     * (zipped if directory)
     * @param integer $id
     * @return Response download window
     */
    public function downloadAction($id)
    {
		$response = new Response();
    	$file = $this->container->get('filed_file.file')->load($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        //Check file as downloaded by the user
        $file->addUsersDownload(array($user));
        $user->addDownloadedFiles(array($file));
        $this->container->get('filed_file.file')->update($file);
        $this->container->get('fos_user.user_manager')->updateUser($user);
        
    	if($file!=null && FileFactory::getInstance()->isSharedWith($user,$file)){
    		if($file->isDirectory()){
    			//Zip directory and store it locally to process download
    			//First : clean the directory of stored zip files
    			$this->clearDirectory(__DIR__."/../../../../web/data/downloads/zip");
    			//Then add the new one
    			$zip = new \ZipArchive();
    			$name = $file->getName().".zip";
    			$dirname = basename($file->getName());
    			$path = __DIR__."/../../../../web/data/downloads/zip/".$name;
    			$zip->open($path, \ZipArchive::CREATE);
    			$zip = $this->addToZip($zip, $file, "/", true);
    			$zip->close();
    			$mime = "application/zip";
    			
    		}
    		else{
    			 $path = $file->getLink();
    			 $name = $file->getName();
    			 $mime = $file->getMime();
    		}
    		
    		$response = new Response();
    		$response->setStatusCode(200);
    		$response->headers->set('Content-Type', "application/".$mime);
    		$response->headers->set('Content-Disposition', sprintf('attachment;filename="%s"', $name, $mime));
    		$response->setCharset('UTF-8');
    		$response->setContent(file_get_contents($path));
    		// prints the HTTP headers followed by the content
    		$response->send();
    	}
    	return $response;
    }
    
	/**
	 * Add a file/directory to a zip
	 * @param ZipArchive $zip
	 * @param File $file 
	 * @param bool isRoot
	 * @return the zip filled
	 */
    private function addToZip($zip, $file, $filename,$isRoot)
    {
    	if($file->isDirectory()){
			//add an empty dir with the name
			$filename .=$file->getName()."/";
    		$zip->addEmptyDir($filename);
    		foreach($file->getChildren() as $child){
    			$zip = $this->addToZip($zip,$child,$filename,false);
    		}
    	}
    	else{
    		//add the file to the zip
    		$zip->addFile($file->getLink(), $filename.$file->getName());
    	}
    	
    	return $zip;
    }
    
    
    /**
     * Clear the directory content
     * @param string $path the path of the directory
     */
    private function clearDirectory($path)
    {
    
    	$dir=opendir($path);
    	while ($fichier = readdir($dir))
    	{
    		if ($fichier != "." && $fichier != "..")
    		{
    			$this->rrmdir($path."/".$fichier);
    		}
    	}
    	closedir($dir);
    }
    
    /**
     * Remove a directory and its content
     * @param string $dir path to directory
     */
    private function rrmdir($dir) {
    	foreach(glob($dir . '/*') as $file) {
    		if(is_dir($file))
    			rrmdir($file);
    		else
    			unlink($file);
    	}

    	if(is_dir($dir))
    		rrmdir($dir);
    	else
    		unlink($dir);
    }

    /**
     * Render the form of sharing file
     * @param integer $_POST['id'] id of the file
     * @return rendering share.html.twig
     */
    public function rendershareFileAction()
    {
    	$template = sprintf('FileDFileBundle:File:share.html.%s', $this->container->getParameter('fos_user.template.engine'));
    	$file_id = $_POST['id'];
    	 //Get all active users 
    	 $users = $this->container->get('fos_user.user_manager')->findActiveUsers();
    	 $choices = array();
    	 foreach($users as $user){
    	 	$choices[$user->getId()] = $user->getUsername();
    	 }
    	 $shareclass = new Share();
    	 $form = $this->container->get('form.factory')->create(new ShareFileType(), $shareclass,array('choices' => $choices));
    	 
    	 
    	return $this->container->get('templating')->renderResponse($template,
    			array('users' => $users,
    					'file_id' => $file_id,
    					'form' => $form->createView(),
    				));
    }
    

}
