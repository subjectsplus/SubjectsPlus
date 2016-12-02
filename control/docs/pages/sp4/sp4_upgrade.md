---
title: SubjectsPlus Upgrade
tags: [getting_started, installation]
keywords: upgrade SubjectsPlus 
last_updated: Dec 2, 2016
summary: How to upgrade from an earlier version of SubjectsPlus
sidebar: sp4_sidebar
permalink: sp4_upgrade.html
folder: sp4
---


## Upgrade (from v1 up)

* Download the files from GitHub -- as of right now they are available at <https://github.com/subjectsplus/SubjectsPlus/releases https://github.com/subjectsplus/SubjectsPlus/releases>.  
You can either download a zip (look for download button), or use Git to clone to your server.

<img src="{{ "images/Github-download.jpg" }}" alt="Download SubjectsPlus from Github"/>

### Tips

* Don't overwrite your existing SP folder!
* Create a fresh database on your server.  Copy over your production SQL to this fresh database.
* Backup your SP database files!  Make a copy of your files for migration purposes, too.
* Make sure your users aren't adding content while you upgrade
* If you are logged in to SubjectsPlus, log out.  Close the browser.  If you still have a session live when you try to do the migration, things can get weird.
* Point your browser to yoursite.edu/sp/control/  and follow the instructions
* Update the Admin > Config Site file.  (If you want, you could also copy in '''sections''' of your old/live SP config.php file to the new one, but make sure you know what you're doing!)
* Move over the user folders from your old site, i.e., everything under assets/users/ and put them in the same location in sp4
* Check that your content still looks okay
* Style your front end.  See [[customization 4.0]].
* There will likely be at least some weirdness if you are coming from a much earlier version.  Be brave.
* Things works differently in v4 than in some earlier versions, so make sure your users are aware of the differences/new ways of doing things