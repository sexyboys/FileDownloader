<?php

namespace FileD\FileBundle\Factory;

/**
 * FileD\FileBundle\Factory\FileFactory
 */
class FileFactory {
	
	private static $INSTANCE;
	
	private $mimeTypes;
	
	private function __construct(){
		$this->mimeTypes = array();
		$this->mimeTypes['dir']="0directory";
		$this->mimeTypes['js']="application/javascript";
		$this->mimeTypes['ogg']="application/ogg";
		$this->mimeTypes['pdf']="application/pdf";
		$this->mimeTypes['xhtml+xml']="application/xhtml+xml";
		
/*
		application/EDI-X12 : données EDI ANSI ASC X12 ; défini dans la RFC 1767.
		application/EDIFACT : données EDI EDIFACT ; défini dans la RFC 1767.
		application/javascript : JavaScript ; défini dans la RFC 4329.
		application/octet-stream : flux de données arbitraire. Considéré comme le format « par défaut » dans plusieurs OS, souvent utilisé pour identifier des fichiers exécutables, ou de type inconnu, ou des fichiers qui doivent être téléchargés grâce à des protocoles qui ne fournissent pas de champ « content disposition » dans leur en-tête. La RFC 2046 le décrit comme un recours pour les sous-types et les types non reconnus.
		application/ogg : Ogg, un flux de données multimedia, conteneur ; défini dans la RFC 3534.
		application/pdf: Portable Document Format, PDF, utilisé pour les échanges de documents depuis 1993 ; défini dans la RFC 3778.
		application/xhtml+xml : XHTML ; défini dans la RFC 3236.
		application/x-shockwave-flash : fichier Adobe Flash ; documenté par Adobe TechNote tn_4151 et Adobe TechNote tn_16509.
		application/json : JavaScript Object Notation ; défini dans la RFC 4627.
		application/xml : eXtensible Markup Language ; défini dans la RFC 3023.
		application/zip : fichier ZIP.
		
		Type audio : audio.
		
		audio/mpeg : MP3 ou autres MPEG ; défini dans la RFC 3003 (attention, sur certains navigateurs tels que Chrome / Chromium le content-type est : audio/mp3).
		audio/x-ms-wma : Windows Media Audio ; documenté par Microsoft KB 288102.
		audio/vnd.rn-realaudio : RealAudio ; documenté par RealPlayer Customer Support Answer 2559.
		audio/x-wav : WAV.
		
		Type example.
		Type image.
		
		image/gif : GIF ; défini dans la RFC 2045 et la RFC 2046.
		image/jpeg : JPEG image JFIF ; défini dans la RFC 2045 et la RFC 2046 (attention, sous Internet Explorer[Quoi ?] le type MIME peut être « image/pjpeg » 5).
		image/png : Portable Network Graphics ; enregistré6 (attention, à l'instar du jpeg sous Internet Explorer[Quoi ?] le type MIME peut être « image/x-png »).
    image/tiff : Tagged Image File Format ; défini dans la RFC 3302.
    image/vnd.microsoft.icon : icône ICO ; enregistré. 7
    image/svg+xml : image vectorielle SVG ; défini dans SVG Tiny 1.2 Specification Appendix M.
		
Type message.
Type model : modèle 3D.
Type multipart : archive et autres objets composés de plus d'une seule partie.
		
		multipart/mixed : MIME courriel ; défini dans la RFC 2045 et la RFC 2046.
		multipart/alternative : MIME courriel ; défini dans la RFC 2045 et la RFC 2046.
		multipart/related : MIME courriel ; défini dans la RFC 2387 et utilisé par MHTML (HTML mail).
		
		Type text : texte lisible par un être humain ou code source.
		
		text/css : feuilles de style en cascade ; défini dans la RFC 2318.
		text/csv : comma-separated values ; défini dans la RFC 4180.
		text/html : HTML ; défini dans la RFC 2854.
		text/javascript (obsolète) : JavaScript ; défini et rendu désuet dans la RFC 4329 pour décourager son usage au profit du type application/javascript.
		text/plain : données textuelles ; défini dans la RFC 2046 et la RFC 3676.
		text/xml : Extensible Markup Language ; défini dans la RFC 3023.
		
		Type video : vidéo.
		
		video/mpeg : MPEG-1, vidéo avec son multiplexé ; défini dans la RFC 2045 et la RFC 2046.
		video/mp4 : vidéo MP4 ; défini dans la RFC 4337.
		video/quicktime : vidéo QuickTime ; enregistré. 8
		video/x-ms-wmv : Windows Media Video ; documenté par Microsoft KB 288102.
		video/x-msvideo : vidéo dans un conteneur AVI.
		video/x-flv : Flash Video (FLV) par Adobe Systems.
		
		Type vnd : fichiers spécifiques à certains éditeurs.
		
		application/vnd.oasis.opendocument.text : texte OpenDocument (enregistré 9).
		application/vnd.oasis.opendocument.spreadsheet : feuille de calcul OpenDocument (enregistré 10).
		application/vnd.oasis.opendocument.presentation : présentation OpenDocument (enregistré 11).
		application/vnd.oasis.opendocument.graphics : graphique OpenDocument (enregistré 12).
		application/vnd.ms-excel : fichiers Microsoft Excel.
		application/vnd.openxmlformats-officedocument.spreadsheetml.sheet : fichiers Microsoft Excel 2007.
		application/vnd.ms-powerpoint : fichiers Microsoft Powerpoint.
		application/msword : fichiers Microsoft Word.
		application/vnd.openxmlformats-officedocument.wordprocessingml.document : fichiers Microsoft Word 2007.
		application/vnd.mozilla.xul+xml : fichiers Mozilla XUL.
		*/
		
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
}
