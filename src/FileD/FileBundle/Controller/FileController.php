<?php

namespace FileD\FileBundle\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;

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
use FileD\ParamBundle\Manager\ParameterManager;

/**
 * File controller.
 *
 */
class FileController extends Controller
{

	/**
	 * Constant path of the upload dir
	 * @var string
	 */
	const UPLOAD_DIR = "/../../../../web/data/uploads/files/";
	
	/**
	 * Constant path of the download dir for zip
	 * @var string
	 */
	const DOWNLOAD_DIR = "/../../../../web/data/downloads/zip";
   
    /**
     * Displays the view to upload file (modal box)
     *
     * @Route("/new", name="file_new")
     * @Template()
     */
    public function newAction($file_id)
    {
		$this->container->get('logger')->info('[FileController] Displaying view for upload file with id '.$file_id);
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
        	try{
	            $em = $this->getDoctrine()->getManager();
	            $em->persist($entity);
	            $em->flush();
			
	            $this->container->get('logger')->info('[FileController] Create new '.$entity);
            	//add flash msg to user
	            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.new'));
        	}
        	catch(\Exception $e){

        		//add flash msg to user
        		$this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.new'));
        		
        		$this->container->get('logger')->err('[FileController] Error while updating  '.$entity);
        	}
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
        	try{
	            $em->persist($entity);
	            $em->flush();

	            //add flash msg to user
	            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.edit'));
	            $this->container->get('logger')->info('[FileController] Updating '.$entity);
	            
            }
            catch(\Exception $e){
        		//add flash msg to user
        		$this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.edit'));
            
            	$this->container->get('logger')->err('[FileController] Error while updating  '.$entity);
            }
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
    		
    		$file = $this->container->get('filed_file.file')->create();
    		//upload the file
    		if ($_FILES['files']['tmp_name'][0]!="" && $_FILES['files']['error'][0] == 0) {
				try{
	    			if(array_key_exists('parent', $_POST) && $_POST['parent']!=0){
	    				$parent = $this->container->get('filed_file.file')->load($_POST['parent']);
	    				if($parent!=null)$file->setParent($parent);
	    				else throw new \Exception("Can't find parent with id "+$_POST['parent']);
	
	    				$path = $parent->getLink()."/".$_FILES['files']['name'][0];
	    			}
	    			else $path =  __DIR__.FileController::UPLOAD_DIR.$_FILES['files']['name'][0];
	    			 
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
	            	$this->container->get('logger')->info('[FileController] Adding '.$file);

	            	//add flash msg to user
	            	$this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.add'));
            
	        	}
	        	catch(\Exception $e){

	        		//add flash msg to user
	        		$this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.add'));
	        		
	        		$this->container->get('logger')->err('[FileController] Error while adding  '.$file);
    				$response = new Response($e->getMessage());
	        	}
    		}
    		else{
    			if($_FILES['files']['error'][0] != 0){
	        		$this->container->get('logger')->err('[FileController] Error while uploading  file with error code '.$_FILES['files']['error'][0]);
    				
    			}
    			$response = new Response("false");
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
    		
            //add flash msg to user
            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.add'));
    		
    	}
    	catch(\Exception $e){
            //add flash msg to user
            $this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.add'));
        	
    		$this->container->get('logger')->err('[FileController] Error while adding file from server '.$path);
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

				//add flash msg to user
				$this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.repository.new'));
	    	}
	    	else{

	    		//add flash msg to user
	    		$this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.repository.new'));
	    		return new Response('false');
	    	}
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
    	try{
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
	    	
	    	//Map an array idfile=>usernames
	    	//format id:username1,username2;id2:username1
	    	$txt_array_usershare = "";
	    	$j=1;
	    	foreach($files as $file){
	    		$usersshare=$file->getId().":";
	    		$i=1;
	    		foreach($file->getUsersShare() as $user){
	    			$usersshare.=$user->getUsername();
	    			if($i<count($file->getUsersShare()))$usersshare.=",";
	    			
	    			$i++;
	    		}
	    		$txt_array_usershare.=$usersshare;
	    		if($j<count($files)) $txt_array_usershare.=";";
	
	    		$j++;
	    	} 

	    	$this->container->get('logger')->info('[FileController] Viewing files of  '.$last_username);
	    	
    	}
    	catch(\Exception $e){
    	
    		$this->container->get('logger')->err('[FileController] Error while viewing files of '.$last_username.' : '.$e->getMessage());
    		
    	}
    	
    	
    	return $this->container->get('templating')->renderResponse($template, 
    			array('files' => $files, 
    				  'parent_id' => $parent_id,
    				  'file_id' => $file_id,
    				   'last_username' => $last_username,
    					'txt_usershare'=> $txt_array_usershare,
    					'csrf_token' => $csrf_token,
    					'enable_upload' => $this->container->get('filed_user.param')->findParameterByKey(ParameterManager::ENABLE_UPLOAD),
    					'enable_share' => $this->container->get('filed_user.param')->findParameterByKey(ParameterManager::ENABLE_SHARE)));
    }
    


    /**
     * Render file link to view file content
     * @param $id the file id
     * @return the rendering of display view depending of file type
     */
    public function viewFileAction($id){
		try{
    		$file = $this->container->get('filed_file.file')->load($id);
    		$handle = FileFactory::getInstance()->isTypeHandle($file);
    		//Generate web link of the file
    		$links = explode("/web/", $file->getLink());
    		$weblink = $links[1];
    		if($handle == FileFactory::AUDIO){
    			$template = 'FileDFileBundle:View:audio.html.twig';
    			$response=$this->container->get('templating')->renderResponse($template,
    					array("link" => $weblink,
    							"name" => $file->getName(),
    							"id" => $file->getId(),
    							"format"=> FileFactory::getInstance()->getAudioFormat($file)));
    		}
    		else if($handle == FileFactory::IMG){
    			$template = 'FileDFileBundle:View:image.html.twig';
    			$response=$this->container->get('templating')->renderResponse($template,
    					array("link" => $weblink,
    							"name" => $file->getName()));
    		}
    		else if($handle == FileFactory::VIDEO){
    			$template = 'FileDFileBundle:View:video.html.twig';
    			$response=$this->container->get('templating')->renderResponse($template,
    					array("link" => $weblink,
    							"name" => $file->getName(),
    							"id" => $file->getId(),
    							"format"=> FileFactory::getInstance()->getVideoFormat($file)));
    		}
    		else $response = new Response("");	    	
    	}
    	catch(\Exception $e){
    		$response = new Response("");
    		$this->container->get('logger')->err('[FileController] Error while viewing file of '.$id.' : '.$e->getMessage());
    		
    	}
    	
    	
    	return $response;
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
	    	$this->container->get('logger')->info('[FileController] Edit '.$file);
	            //add flash msg to user
	            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.edit'));
	    	return new Response($name);
    	}
    	catch(\Exception $e){
	            //add flash msg to user
	            $this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.edit'));

    		$this->container->get('logger')->err('[FileController] Error while editing file '.$e->getCode().': '.$e->getMessage());
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
	    	$this->container->get('logger')->info('[FileController] Delete file with id '.$_POST['id']);
            //add flash msg to user
            $this->container->get('session')->setFlash('success', $this->container->get('translator')->trans('msg.success.file.delete'));
    	}
    	catch(\Exception $e){
            //add flash msg to user
            $this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.delete'));
	    	$this->container->get('logger')->err('[FileController] Error while deleting file with id '.$_POST['id']);
    		
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
        try{
	    	if($file!=null && FileFactory::getInstance()->isSharedWith($user,$file)){
	    		if($file->isDirectory()){
	    			//Zip directory and store it locally to process download
	    			//First : clean the directory of stored zip files
	    			$this->clearDirectory(__DIR__.FileController::DOWNLOAD_DIR);
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
        	$this->container->get('logger')->info('[FileController] Downloading file with id '.$id);
        }
        catch(\Exception $e){
            //add flash msg to user
            $this->container->get('session')->setFlash('error', $this->container->get('translator')->trans('msg.error.file.download'));

        	$this->container->get('logger')->err('[FileController] Error while downloading file with id '.$id);
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
    			$this->rrmdir($file);
    		else
    			unlink($file);
    	}

    	if(is_dir($dir))
    		rmdir($dir);
    	else
    		unlink($dir);
    }

    /**
     * Share a file/repository to a list of users
     * @param Request the request
     * @return redirection to index
     */
    public function shareFileAction(Request $request)
    {
    	$template = sprintf('FileDFileBundle:File:share.html.%s', $this->container->getParameter('fos_user.template.engine'));
    	
    	
    	$users = $this->container->get('filed_user.user')->findActiveUsers();
    	$choices = array();
    	foreach($users as $user){
    		$choices[$user->getId()] = $user->getUsername();
    	}
    	$shareclass = new Share();
    	$form = $this->container->get('form.factory')->create(new ShareFileType($choices), $shareclass,array('label'=>$this->container->get('translator')->trans('file.share.list.label')));
    	try{
	    	if ($request->getMethod() == 'POST') { 
	    		$form->bindRequest($request); 
	    	
	    		if ($form->isValid()) {
	    			$file_id = $shareclass->getId();
	    			$users = $shareclass->getUsers();
	    			$file = $this->container->get('filed_user.file')->load($file_id);
	    			//Reset sharing option
	    			//$file->setUsersShare(new \Doctrine\Common\Collections\ArrayCollection());
	        		$this->container->get('filed_file.file')->update($file);
	    			$usershare = $file->getUsersShare();
	    			$users_added = array();
	    			foreach($users as $id_user){
	    				$users_added[$id_user]=$users;
						//Foreach user add it to file if not already in there
	    				$user = $this->container->get('filed_user.user')->load($id_user);
	    				$p = function($key, $element) use ($id_user){
	    					return $element->getId() == $id_user;
	    				};
	    				//do not add it if the user is already shared
	    				if($usershare==null || !$usershare->exists($p)){
		    				$user->addFiles(array($file));
		    				$file->addUsersShare(array($user));
		    				$this->container->get('filed_user.user')->update($user);
    						$this->container->get('logger')->info('[FileController] Sharing file '.$file->getId().' to user id'.$id_user);
	    				}
	    			}
	    			
	    			//TODO need better algorithm...
	    			if(count($users_added) != $file->getUsersShare()->count()){
		    			//Find the ones to remove
		    			foreach($file->getUsersShare() as $user){
		    				if(!array_key_exists($user->getId(),$users_added)){
		    					//remove it
    							$this->container->get('logger')->info('[FileController] Unsharing file '.$file->getId().' to user id'.$user->getId());
    							$file->removeUsersShare(array($user));
    							$user->removeFiles(array($file));
		    					$this->container->get('filed_user.user')->update($user);
    							
		    				}
		    			}
	    			}
	        		$this->container->get('filed_file.file')->update($file);
            		$this->container->get('session')->setFlash('success',$this->container->get('translator')->trans('msg.success.file.share'));
	    			return new RedirectResponse($this->container->get('router')->generate('user_index'));
	    		}
	    	}
    	}
    	catch(\Exception $e){

    		$this->container->get('session')->setFlash('error',$this->container->get('translator')->trans('msg.error.file.share'));
    		$this->container->get('logger')->err('[FileController] Error while sharing file with id '.$shareclass->getId()." : ".$e->getMessage());
    	}
    	return $this->container->get('templating')->renderResponse($template,
    			array(
    					'form' => $form->createView()
    				));
    }
    
    /**
     * View the part link dealing with mark as seen functionality in the table of files
     * @param integer the file id 
     * @return response
     */
    public function isMarkAsSeenFileAction($id){
    	$file = $this->container->get('filed_file.file')->load($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $exists = false;

        $title = $this->container->get('translator')->trans('file.list.name.mark');
        $i=1;
        foreach($file->getUsersSeen() as $userSeen){
        	if($i==1)$title.=" ".$this->container->get('translator')->trans('file.list.name.mark.by');
        	$title.=" ".$userSeen->getUsername();
        	if($i< $file->getUsersSeen()->count()) $title.=",";
	        if($userSeen->getId() == $user->getId()){
	        	$exists=true;
	        }
	        $i++;
        }  						
        if($exists){
        	//Marked as seen
        	$response = '<a href="" title="'.$title.'" ><i title="'.$title.'" class="icon-ok"></i></a>';
        }
        else{
        	//didn't mark as seen
        	$response = '<a href="" title="'.$title.'" onclick="markAsSeen('.$id.')"><i class="icon-ok-circle"></i></a>';
        }
        
        return new Response($response);
    }
    
    /**
     * Mark a file as seen
     * @param POST integer the file id
     * @return response
     */
    public function markAsSeenAction()
    {
    	$id = $_POST['id'];
    	$file = $this->container->get('filed_file.file')->load($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $this->markAsSeen($file,$user);
        return new Response('');
    }
    
    /**
     * Mark a file or directory of sub file
     * @param $file
     * @param $user
     */
    private function markAsSeen($file,$user)
    {
    	if($file->isDirectory())
    	{
    		foreach($file->getChildren() as $child)
    		{
    			$this->markAsSeen($child, $user);		
    		}
    	}
    	
        $file->addUsersSeen(array($user));
        $user->addSeenFiles(array($file));
    	$this->container->get('filed_user.user')->update($user);
    	$this->container->get('filed_file.file')->update($file);
    	
    }

}