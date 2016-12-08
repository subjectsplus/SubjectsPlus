---
title: SubjectsPlus 4 New Installation
tags: [installation, getting_started]
keywords: sp4, install 
last_updated: Dec 2, 2016
summary: How to install SubjectsPlus 4
sidebar: sp4_sidebar
permalink: sp4_new_installation.html
folder: sp4
---


<figure class="video_container">
  <iframe src="https://www.youtube.com/embed/DtbQiVuQ15M" width="640" height="360" frameborder="0" allowfullscreen="true"> </iframe>
</figure>


* Download the files from GitHub -- as of right now they are available at <https://github.com/subjectsplus/SubjectsPlus/releases https://github.com/subjectsplus/SubjectsPlus/releases>.  You can either download a zip (look for download button), or use Git to clone to your server.
* Create a database on your server, note it's name. Also note the name of the MySQL user and their password
* Check your MySQL user permissions--you will need SELECT, INSERT, UPDATE, ALTER, DELETE, CREATE and DROP privileges
* Point your browser to yoursite.edu/sp/control/  and follow the instructions



## Folder Permissions

For everything to work smoothly, some folders need special permissions which they might not have by default.  (The first two probably were OK 
or else the installer would have conked out.)

````
/control/includes/config-default.php     --- readable by php to make config.php

/control/includes/                       --- writable by php to write config.php, css.php, js.php

/subjects/.htaccess                      --- writable by php to write .htaccess

/api/.htaccess                      	 --- writable by php to write .htaccess

/assests/users/                          --- writable by php to make new user folders

/assests/cache/                          --- writable by php to compile css and js

/assets/images/video_thumbs              --- writable by php to add new video thumbnails
````