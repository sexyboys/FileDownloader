#FileDownloader
==============
FileDownloader is a file management system which allow registered user to view files (only those shared with him) and can view it (if it's supported), download it etc...


## Demo
//[Not yet](http://)

## Setup
	### Server configuration
	* You have to install & configure a webserver, a mysql database and php 5 of course!
	* In php, you have to enable php5-zip and set the upload_max_filesize parameter in php.ini
	### Install FileDownloader
	* Clone the repository into your web directory
	* Import vendor files with composer "php composer.phar install"
	* If you are on linux system, check rights on filedownloader (check Symfony 2 settings)
	### Setup FileDownloader
	* Copy .app/config/parameter.yml.default into ./app/config/parameter.yml
	* Edit this file and configure database (sql file is in ./app/config/filedownloader.sql)
	* You only need to open a terminal, go in the webserver directory and execute the command : php app/console fos:user:create your_username --super-admin

## Modules

FileDownloader use as you might guess Symfony 2 and several bundles or modules to satisfy the features such as:
	* Php Modules :
		** php5-zip
	* Symfony 2 bundles :
		** FOSUserBundle
		** Sonata Admin Bundle
	
## Features

	* Files List for each user with actions as Add files/directory, share to other user, refresh directory content, delete, view image/mp3
	* Navigation into directories
	* For administrator : Add files/directories which are in the server
	* Administrator panel to manage users
	
## License
Released under the [MIT license](http://www.opensource.org/licenses/MIT).