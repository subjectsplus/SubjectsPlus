// SubjectsPlus Version 2.0 (Beta) //

SubjectsPlus is based on "Pirate Source," developed by the Joyner Library at East
Carolina University.  No license was attached to the initial distribution of the
code, but with their permission, the rechristened "SubjectsPlus" is available under the GNU GPL.

// LICENSE //

Copyright (C) 2002 East Carolina University (Joyner Library)
Copyright (C) 2007-2013 Andrew Darby

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

// ICONS //

The icons used in the admin and some for the public interface are from the famfamfam silk icon set,
http://www.famfamfam.com/lab/icons/silk/, licensed under a Creative Commons
Attribution 2.5 License, as well as the Tango Icon Library
(http://tango.freedesktop.org/Tango_Icon_Library).

Other icons and graphics on public pages are by Andrew Darby and dan taylor.

//////////////////
// INSTALLATION //
//////////////////

Additional instructions & troubleshooting tips may be found on the wiki:

http://www.subjectsplus.com/wiki/

1. Download
* Download the zip file and ftp it to the public_html or www folder of your webserver.
* Unzip. You should now have a folder called "sp".  You can rename it whatever.

2. Prepare MySQL
	a.  Fresh install:  Create a new database for subsplus, say, "sp", and a user with SELECT, INSERT,
UPDATE, ALTER and DELETE privileges. Note the name of the database, user, and user
password as you will need this information later.

	b.  Update existing install:  BACKUP YOUR DATA!!!  Save it somewhere nice.  Maybe run the updater
	on a copy of the data, rather than the original.  For the updater, your MySQL user will need SELECT, INSERT,
UPDATE, ALTER, DELETE, CREATE and DROP privileges.

3. Visit the SubjectsPlus Control Panel with your browser.

	Go to the root of the control folder (with your browser).  It will be something
	like www.mysite.edu/sp/control/ and follow the instructions

	Once you are done, you should be dropped into the login page.

4. In the Control Panel, visit admin > config site and see if anything needs tweaking.  For instance, there is now
an enlarged set of info about staff users; you might want to cut it back and/or decide which fields are required.

5.  Everything went okay?  Hooray!  Now make sure these folder permissions are okay.  (The first two probably were
or else the installer would have conked out.)

/control/includes/config-default.php     --- readable by php to make config.php
/control/includes/                       --- writable by php to write config.php
/subjects/.htaccess                      --- writable by php to write .htaccess
/api/.htaccess                      	 --- writable by php to write .htaccess
/assests/users/                          --- writable by php to make new user folders

6. You will need to customize some of the Special pluslet types to match your
local environment:

control/includes/classes/sp_Pluslet_5.php -- Catalog Search Box -- localize it

7. Existing users:  It's a bummer, it's a drag, but you will need to copy over all your user files.  Go to
assets/users and haul over all the existing folders that begin with an underscore (e.g., _jsmith).  That will
bring over headshots and any uploaded images.  ALSO if you have any custom pluslets or other customizations,
you'll need to bring them over, too.  Ditto for any fresh css, especially default.css which controls the public
pages.

7.  Play around.  There is information available on the subjectsplus.com site and
the wiki (http://www.subjectsplus.com/wiki/).



/////////////////////////////////////////
// Revision History (starting at 0.3) //
////////////////////////////////////////

2.0
-- New installer, updater, configurer
-- Database reorganized, referential integrity imposed!
-- API to ingest data (xml or json) into other applications
-- Upgrade WYSIWYG editor from FCKeditor to CKeditor (don't scoff, it was a big job!)
-- Site configuration available via admin interface
-- New Videos module, for 3rd party video management
-- Talkback -- now has "multi-site" capability
-- Staff table -- more data options, staff-supervisor relationships
-- Optional internal staff map (for disaster planning, say)
-- Alternate title possible for databases/resources
-- Additional guide metadata (description, keywords)
-- Beta integration into Serials Solution's Summon
-- 3 Column guide layout
-- Boxes without titlebars!  Tip:  give it a title of "notitle"
-- New "Lost Password" functionality
-- On upgrade from 1.x, chchchanges table is cleared, so only original insert and last update data retained

1.0.1
-- proxy string not being prepended to database list--fix to sp_DbHandler.php

1.0
--Finally, a commitment to 1.0.  Everything is very different.  If you are upgrading,
you will need to look for instructions on the wiki about how to upgrade.
--Database structure changed significantly
--Improved guide construction
--Chchchanges table works more efficiently; stores original and last revision of
a guide by a given author (rather than /each/ revision by an author)
--user files now uploaded to user folder
--working file management
--prettier default public pages
--public pages code organized a little better
--internationalization now using gettext (although as of 1.0 beta, no language files
yet)
--More flexible/extendable permissions

//// 0.9 ////////

0.9.1.9
-- Security fix to sp/assets/fixed_pluslets/all_items.php

0.9.1.8
-- French translation completed!  Thanks Benoit Hamel!

0.9.1.7
-- tiny bug fixed -- an IC only query left in at line 290 of subjects/staff.php which resulted
in the associated subjects not showing

0.9.1.6
--small bug fixed whereby the metadata for a guide had two Yes options when you set it to active.
fixed.

0.9.1.5
--fixed some small bugs in assets/fixed_pluslets/all_items.php and control/records/edit_functions.php

0.9.1.4
--problem in IIS with name of jquery file; renamed to jquery-ui-1.7.1.core_interactions.min.js,
and functions.php updated.

0.9.1.3
--addded missing close div back into subjects/includes/header.php
--fixed note override which flies out in records/record.php
--fixed stripP function in control/includes/function.php -- Gavin's corrected preg_replace
--guides/fck_get_faqs.php fixed some bugs with layout/display
--guides/guide_functions.php updated to include "override" notes

0.9.1.2
--fix issue with ezproxy string not appearing in subjects/index.php "Newest Resources"
--same file, now searches for items that have a type other than web, but which show URLs nonetheless.  Now
they're linked, rather than displaying URL.

0.9.1.1
--another bug fix, removed some stray jquery from subjects/guide.php

0.9.1 Oct 2009
--tweaks to guides/guide_functions.php to fix display of print items +
remove breaks from tokenized description fields
--bug fix to /subjects/ .htaccess file

0.9 August-Sept 2009

Major changes.

--Reworked how the subject guides are created and modified:
	-- Librarians can create guides of different types (i.e., Subject, Course, Topic + anything you'd like to add)
	-- New drag and drop interface
	-- Much greater control over layout (by subject specialists)
	-- One subject/course, multiple librarians
	-- Shareable content (aka pluslets)
	-- 2.0 bling
--Sidebar tab replaced with Guides tab.
--More comprehensive "recent activity"
--Automatic newsletter of changes (optional!)
--Files and Folders reorganized.
--Revised table structure
--Translations begun

///////// 0.8 /////////////

0.8.2 Feb 27, 2009
-- Fixed variable name in control/rxs/feed.php so feed would show
-- Made target="_blank" for header items (thanks Diane Z) so they'd open new window

0.8.1 Jan 23, 2009
--two small bug fixes

0.8 Dec/Jan 2008-2009

Major changes.

General:
--Tables and pages now unicode-friendly (thanks Adam F for an excellent overview of what to do)
--Additional database fields in some tables
--Now course guides can be easily (?) added (thanks Diane Z and Amy B)
--Some javascript changed to JQuery
--FCKEditor tweaked to fix whitespace issues

Public Side:
--New splash page (subjects/index.php) for public side (+ old splash page + three column splash page)
--New search page, to search across all subject guides
--Reorganized sidebar for subject results display page
--page that "reads" delicious tags (subjects/delish_feed.php) based on paramaters set in
control/rxs/delish_url.php (found under SP::Sidebar)

Admin Side:
--New "look and feel"
--Recent activity listings on user's home tab
--RSS feed page has some instructions on getting feeds (thanks Ron G)
--New drag and drop ranking of items in subjects
--Redesigned SP::Records section
     --New splash page with search
     --Reorganized search structure
     --Reorganized (under the hood) pagination
     --Description override (thanks Julia B)
     --New Browse items functionality
     --Some bug fixes with new record creation (thanks Diane Z)
--Tweaked admin section
--New ability to set a "type of guide"; i.e., class guides now possible

0.7 Jan/Feb/Mar . . . Dec 2008

Major changes.
-- New RSS feeder option (requires php 5); older version still avail
-- More config variables!
-- FAQ management module!
-- Enhanced staff list!
-- TalkBack module!
-- Fewer html tables!
-- Email option in display.php is gone (no one used it, and you can just email the URL now)!
-- subjects/index.php and subjects/display.php substantially tweaked under the hood
-- More exclamation points in readme!
-- New user-friendly URLs--if you use mod_rewrite
-- URLs have changed in subjects/display from display.php?id=1 to display.php?subject=art  -- this means
changes throughout

0.6 (Sept - Oct 2007)

Major changes.
--Code cleanup.
--Added password authentication.
--tried to generalize away from ithaca college-specific
--Fckeditor vs. textarea option
--Helpful (?) query debugging option

0.5 (August-Sept 2007)

-- Made subjects/display.php have a bookmarkable url
-- changed tables.sql to fix some things for MySQL 5 compat.
-- Added some debugging stuff
-- Added note about Adding/Updating/Removing items to readme.txt
-- Added "help" page to SP:Records

0.4 (July 2007)

-- Some housekeeping changes to the code, tidied up the readme

0.3 (July 2007)

-- Check that the record title doesn't already exist before submission, and that you can't
change a record title so that it's the same as something already present
 (control/rxs_records/index.php, control/rxs_records/new_added.php).

