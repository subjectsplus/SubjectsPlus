# SP3 Shibboleth

This branch of SP3 is modified to work with Shibboleth. 

## Requirements
* Your organization/sys admin will need to configure your web server for Shibboleth.
* You'll need to have a Shibboleth attribute that includes an email address available.
* The SubjectsPlus admin will need to add users before they can log in.


## Setup 

Modify the isCool function in 'control/includes/header.php' to use the server variable that contains the Shibboleth attribute you are using. 

Example:

		isCool($_SERVER['Shibboleth-mail'],"", true);