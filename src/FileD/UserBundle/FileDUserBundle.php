<?php

namespace FileD\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FileDUserBundle extends Bundle
{
	
	function getParent(){
		
		return "FOSUserBundle";
	}
}
