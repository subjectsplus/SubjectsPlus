# SubjectsPlus v3.1.0

SubjectsPlus is a a LAMP/WAMP application that allows you to manage a number of interrelated parts of a library website:

* Research Guides (i.e., subject, course, etc.)
* Database A-Z List
* Staff List
* FAQs
* Suggestion Box
* Videos (i.e., produced in-library)

It was originally developed at the Ithaca College Library, and primary development is now taking place at the University of Miami Libraries.
It is made available under the GNU GPL.

## Website, Documentation, Support

* [Project Homepage](http://www.subjectsplus.com/)
* [Documentation](http://www.subjectsplus.com/wiki)
* [Mailing List](http://groups.google.com/group/subjectsplus)

## Requirements

* PHP >= to 5.3
* MySQL --anything over 4 should be ok
* Web server -- usually Apache, but some people have SubjectsPlus running on IIS
* JavaScript enabled for the admin to work properly. 

If you run into any missing/weird functionality, check that the following extensions are enabled for PHP:

* cURL
* MySQL
* mbstring (not necessary, but you'll need to tweak header.php without this)
* simplexml (for reading RSS feeds)
* json (some data is stored as json)
* gettext (only if you need internationalization, aka translations)
* gd (image resizing--notably for headshots and generation of video thumbnails) 

## Installation

Check the readme.txt file, or look at the [wiki](http://www.subjectsplus.com/wiki).

## Testing

[http://codeception.com/](Codeception) is a php based testing framework that can be used for acceptance and other kinds of testing. 

To run the codeception tests, you'll need to [http://codeception.com/codecept.phar](download Codeception) to the root directory of the repo.

Then you'll need to [http://phantomjs.org/download.html](download phantomjs) and start it in webdriver mode:

    phantomjs --webdriver=4444

With that up and running you may need to edit details in codeception.yml file to suit your environment and you should switch your database to a testing database.

To run the tests go to the root directory of the repo use the following command:
    
    php codecept.phar run acceptance --steps  