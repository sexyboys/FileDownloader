<?php

namespace FileD\FileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FileD\FileBundle\Entity\File;
use FileD\FileBundle\Form\FileType;

/**
 * File controller.
 *
 * @Route("/file")
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
     * Displays a form to create a new File entity.
     *
     * @Route("/new", name="file_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new File();
        $form   = $this->createForm(new FileType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{id}/edit", name="file_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FileDFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $editForm = $this->createForm(new FileType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

    /**
     * Deletes a File entity.
     *
     * @Route("/{id}/delete", name="file_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FileDFileBundle:File')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('file'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Add a single file shot
     */
    public function addfileAction(){
    	// Settings
    	$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
    	//$targetDir = 'uploads';
    	
    	$cleanupTargetDir = true; // Remove old files
    	$maxFileAge = 5 * 3600; // Temp file age in seconds
    	
    	// 5 minutes execution time
    	@set_time_limit(5 * 60);
    	
    	// Uncomment this one to fake upload time
    	// usleep(5000);
    	
    	// Get parameters
    	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
    	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
    	
    	// Clean the fileName for security reasons
    	$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
    	
    	// Make sure the fileName is unique but only if chunking is disabled
    	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
    		$ext = strrpos($fileName, '.');
    		$fileName_a = substr($fileName, 0, $ext);
    		$fileName_b = substr($fileName, $ext);
    	
    		$count = 1;
    		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
    			$count++;
    	
    		$fileName = $fileName_a . '_' . $count . $fileName_b;
    	}
    	
    	$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
    	
    	// Create target dir
    	if (!file_exists($targetDir))
    		@mkdir($targetDir);
    	
    	// Remove old temp files
    	if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
    		while (($file = readdir($dir)) !== false) {
    			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
    	
    			// Remove temp file if it is older than the max age and is not the current file
    			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
    				@unlink($tmpfilePath);
    			}
    		}
    	
    		closedir($dir);
    	} else
    		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    	
    	
    	// Look for the content type header
    	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
    		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
    	
    	if (isset($_SERVER["CONTENT_TYPE"]))
    		$contentType = $_SERVER["CONTENT_TYPE"];
    	
    	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
    	if (strpos($contentType, "multipart") !== false) {
    		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
    			// Open temp file
    			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
    			if ($out) {
    				// Read binary input stream and append it to temp file
    				$in = fopen($_FILES['file']['tmp_name'], "rb");
    	
    				if ($in) {
    					while ($buff = fread($in, 4096))
    						fwrite($out, $buff);
    				} else
    					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    				fclose($in);
    				fclose($out);
    				@unlink($_FILES['file']['tmp_name']);
    			} else
    				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    		} else
    			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    	} else {
    		// Open temp file
    		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
    		if ($out) {
    			// Read binary input stream and append it to temp file
    			$in = fopen("php://input", "rb");
    	
    			if ($in) {
    				while ($buff = fread($in, 4096))
    					fwrite($out, $buff);
    			} else
    				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    	
    			fclose($in);
    			fclose($out);
    		} else
    			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    	}
    	
    	// Check if file has been uploaded
    	if (!$chunks || $chunk == $chunks - 1) {
    		// Strip the temp .part suffix off
    		rename("{$filePath}.part", $filePath);
    	}
    	
    	
    	// Return JSON-RPC response
    	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}
