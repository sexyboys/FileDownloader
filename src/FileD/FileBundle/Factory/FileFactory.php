<?php

namespace FileD\FileBundle\Factory;

/**
 * FileD\FileBundle\Factory\FileFactory
 * Factory of File
 * 
 * @author epidoux <eric.pidoux@gmail.com>
 * @version 1.0
 */
class FileFactory {
	/**
	 * Constant define a non-managed file
	 * @var integer
	 */
	const NONE = 0;
	
	/**
	 * Constant define a managed audio file
	 * @var integer
	 */
	const AUDIO = 1;
	
	/**
	 * Constant defining a managed video file
	 * @var integer
	 */
	const VIDEO = 2;
	
	/**
	 * Constant defining a managed img file
	 * @var integer
	 */
	const IMG = 3;
	
	/**
	 * Instance of FileFactory
	 * @var FileFactory
	 */
	private static $INSTANCE;
	
	/**
	 * Array of mime types
	 * @var array
	 */
	private $mimeTypes;
	
	/**
	 * Array of types with integer if handle or not
	 */
	private $types;
	
	private function __construct(){
		$this->mimeTypes = array();
		$this->mimeTypes['dir']="0directory";
		$this->mimeTypes['app_js']="application/javascript";
		$this->mimeTypes['app_ogg']="application/ogg";
		$this->mimeTypes['app_pdf']="application/pdf";
		$this->mimeTypes['app_xhtml+xml']="application/xhtml+xml";
		$this->mimeTypes['app_x-shockwave-flash']="application/x-shockwave-flash";
		$this->mimeTypes['app_edi-X12']="application/EDI-X12";
		$this->mimeTypes['app_edifact']="application/EDIFACT";
		$this->mimeTypes['app_octet-stream']="application/octet-stream";
		$this->mimeTypes['app_json']="application/json";
		$this->mimeTypes['app_xml']="application/xml";
		$this->mimeTypes['app_zip']="application/zip";
		$this->mimeTypes['audio_mpeg']="audio/mpeg";
		$this->mimeTypes['audio_x-ms-wma']="audio/x-ms-wma";
		$this->mimeTypes['audio_vnd.rn-realaudio']="audio/vnd.rn-realaudio";
		$this->mimeTypes['audio_x-wav']="audio/x-wav";
		$this->mimeTypes['img_gif']="image/gif";
		$this->mimeTypes['img_jpeg']="image/jpeg";
		$this->mimeTypes['img_png']="image/png";
		$this->mimeTypes['img_tiff']="image/tiff";
		$this->mimeTypes['img_vnd.microsoft.icon']="image/vnd.microsoft.icon";
		$this->mimeTypes['img_svg+xml']="image/svg+xml";
		$this->mimeTypes['mpart_mixed']="multipart/mixed";
		$this->mimeTypes['mpart_alternative']="multipart/alternative";
		$this->mimeTypes['mpart_related']="multipart/related";
		$this->mimeTypes['text_css']="text/css";
		$this->mimeTypes['text_csv']="text/csv";
		$this->mimeTypes['text_html']="text/html";
		$this->mimeTypes['text_plain']="text/plain";
		$this->mimeTypes['text_xml']="text/xml";
		$this->mimeTypes['text_css']="text/css";
		$this->mimeTypes['video_mpeg']="video/mpeg";
		$this->mimeTypes['video_mp4']="video/mp4";
		$this->mimeTypes['video_quicktime']="video/quicktime";
		$this->mimeTypes['video_x-ms-wmv']="video/x-ms-wmv";
		$this->mimeTypes['video_x-msvideo']="video/x-msvideo";
		$this->mimeTypes['video_x-flv']="video/x-flv";
		$this->mimeTypes['vnd_oasis-text']="application/vnd.oasis.opendocument.text";
		$this->mimeTypes['vnd_oasis-spread']="application/vnd.oasis.opendocument.spreadsheet";
		$this->mimeTypes['vnd_oasis-presentation']="application/vnd.oasis.opendocument.presentation";
		$this->mimeTypes['vnd_oasis-graphics']="application/vnd.oasis.opendocument.graphics";
		$this->mimeTypes['vnd_ms-excel']="application/vnd.ms-excel";
		$this->mimeTypes['vnd_openxml']="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
		$this->mimeTypes['vnd_ms-ppt']="application/vnd.ms-powerpoint";
		$this->mimeTypes['vnd_ms-word']="application/vnd.msword";
		$this->mimeTypes['vnd_openxml']="application/vnd.openxmlformats-officedocument.wordprocessingml.document";
		$this->mimeTypes['vnd_mozilla']="application/vnd.mozilla.xul+xml";

		$this->types = array();
		$this->types['dir']=FileFactory::NONE;
		$this->types['app_js']=FileFactory::NONE;
		$this->types['app_ogg']=FileFactory::NONE;
		$this->types['app_pdf']=FileFactory::NONE;
		$this->types['app_xhtml+xml']=FileFactory::NONE;
		$this->types['app_x-shockwave-flash']=FileFactory::NONE;
		$this->types['app_edi-X12']=FileFactory::NONE;
		$this->types['app_edifact']=FileFactory::NONE;
		$this->types['app_octet-stream']=FileFactory::NONE;
		$this->types['app_json']=FileFactory::NONE;
		$this->types['app_xml']=FileFactory::NONE;
		$this->types['app_zip']=FileFactory::NONE;
		$this->types['audio_mpeg']=FileFactory::AUDIO;
		$this->types['audio_x-ms-wma']=FileFactory::NONE;
		$this->types['audio_vnd.rn-realaudio']=FileFactory::NONE;
		$this->types['audio_x-wav']=FileFactory::AUDIO;
		$this->types['img_gif']=FileFactory::IMG;
		$this->types['img_jpeg']=FileFactory::IMG;
		$this->types['img_png']=FileFactory::IMG;
		$this->types['img_tiff']=FileFactory::IMG;
		$this->types['img_vnd.microsoft.icon']=FileFactory::IMG;
		$this->types['img_svg+xml']=FileFactory::IMG;
		$this->types['mpart_mixed']=FileFactory::NONE;
		$this->types['mpart_alternative']=FileFactory::NONE;
		$this->types['mpart_related']=FileFactory::NONE;
		$this->types['text_css']=FileFactory::NONE;
		$this->types['text_csv']=FileFactory::NONE;
		$this->types['text_html']=FileFactory::NONE;
		$this->types['text_plain']=FileFactory::NONE;
		$this->types['text_xml']=FileFactory::NONE;
		$this->types['text_css']=FileFactory::NONE;
		$this->types['video_mpeg']=FileFactory::NONE;
		$this->types['video_mp4']=FileFactory::NONE;
		$this->types['video_quicktime']=FileFactory::NONE;
		$this->types['video_x-ms-wmv']=FileFactory::NONE;
		$this->types['video_x-msvideo']=FileFactory::NONE;
		$this->types['video_x-flv']=FileFactory::NONE;
		$this->types['vnd_oasis-text']=FileFactory::NONE;
		$this->types['vnd_oasis-spread']=FileFactory::NONE;
		$this->types['vnd_oasis-presentation']=FileFactory::NONE;
		$this->types['vnd_oasis-graphics']=FileFactory::NONE;
		$this->types['vnd_ms-excel']=FileFactory::NONE;
		$this->types['vnd_openxml']=FileFactory::NONE;
		$this->types['vnd_ms-ppt']=FileFactory::NONE;
		$this->types['vnd_ms-word']=FileFactory::NONE;
		$this->types['vnd_openxml']=FileFactory::NONE;
		$this->types['vnd_mozilla']=FileFactory::NONE;
	}
	
	public static function getInstance(){
		if(FileFactory::$INSTANCE == null){
			FileFactory::$INSTANCE = new FileFactory();
		}
		return FileFactory::$INSTANCE;
	}
	
	public function getMimeType($key){
		return $this->mimeTypes[$key];
	}
	
	/**
	 * Define if the file is a directory or not
	 * @param File $file
	 * @return true or false
	 */
	public function isDirectory($file){
		if($file->getMime() == $this->getMimeType('dir')){
			return true;
		}
		else return false;
	}

	
	/**
	 * Define if the file is shared with the given user
	 * @param User $user
	 * @param File $file
	 * @return true or false
	 */
	public function isSharedWith($user,$file)
	{
		$hasRight=false;
		if(!$user->hasRole('ROLE_ADMIN')){
			foreach($file->getUsersShare() as $userShare){
	        	if($userShare->equals($user)){
	        		$hasRight = true;
	        	}
	        }
		}
		else $hasRight = true;
        
        return $hasRight;
	}
	
	/**
	 * Define if the file is marked as seen by the given user
	 * @param User $user
	 * @param File $file
	 * @return true or false
	 */
	public function isMarkedAsSeenBy($user,$file)
	{
		$hasRight=false;
		foreach($file->getUsersSeen() as $userSeen){
        	if($userSeen->equals($user)){
        		$hasRight = true;
        	}
        }
		
        
        return $hasRight;
	}
	
	
	/**
	 * Define if the type of file is handled to view content of file
	 * @param File $file
	 * @return constant class (NONE,AUDIO,VIDEO,IMG)
	 */
	public function isTypeHandle($file){
		return $this->types[array_search($file->getMime(), $this->mimeTypes)];
	}
	
	/**
	 * Get the format of the given audio file
	 * @param File $file
	 * @return string format
	 */
	public function getAudioFormat($file){

		$format['audio_mpeg']="mp3";
		$format['audio_x-wav']="wav";
		if(array_key_exists($file->getMime(), $format)){
			return $format[$file->getMime()];
		}
		else return "mp3";
	}
	
	/**
	 * Get the format of the given video file
	 * @param File $file
	 * @return string format
	 */
	public function getVideoFormat($file){

		$format['video_mpeg']="mpg";
		$format['video_mp4']="mp4";
		$format['video_x-flv']="flv";
		if(array_key_exists($file->getMime(), $format)){
			return $format[$file->getMime()];
		}
		else return "flv";
	}
}
