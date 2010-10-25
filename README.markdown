# Footprint

Welcome to the Footprint project! The aim of this project is to build an easy-to-use system for quantifying a user's carbon emissions based on their foursquare checkins. The system (ideally) will allow users to simply sign in using Foursquare's OAuth implementation and will then immediately pull over their latest checkins to do calculations from.

## Installation

Installing and running Footprint takes a few steps. First off you'll need to set up a database. This varies by system, so look up instructions for installing mysql for your OS on google. You will also need a webserver. The easiest is probably apache, since it is pre-installed on some systems. Look for *AMP packages (like MAMP, LAMP, WAMP, etc) which are pre-packaged OS-Apache-Mysql-PHP binaries.

Now you're going to need your api key and secret, so follow the directions in that tutorial Jason linked to. Next, you'll need to create a config file to load in your keys and database credentials. The format is as follows:

    <?php
      $consumer_key = "KEY";
      $consumer_secret = "SECRET";
      $loginurl = ""; // leave blank
      $homebase_vid = YOUR_HOMEBASE_ID; // this may be built into the app later
      $db_host = "localhost"; // change if using another host
      $db_user = ""; // set to your database user
      $db_pass = ""; // duh
      $db_name = "footprint"; // DO NOT CHANGE THIS
    ?>
    
Fill in the appropriate details and save in the root directory as 'config.php' so it gets recognized by the other scripts. Use the footprint folder as the document root for the server and then start apache. Visit this URL to get started (change 8888 to a different port depending on apache's installation):

    http://localhost:8888/db/setup.php
    
If mysql is properly installed and you put in the correct credentials, the script's output should be the following:

    Dropped old destinations table if it existed.
    Created destinations table.
    Dropped old homebases table if it existed.
    Created homebases table.
    Dropped old transports table if it existed.
    Created transports table.
    Dropped old users table if it existed.
    Created users table.
    Dropped old usertransports table if it existed.
    Created usertransports table.

    10/10 queries passed!

You should now be able to use the app normally by visiting:

    http://localhost:8888/              
    
## Updating the Application (and working with githhub)

As changes are made to the app, you'll obviously want these updates. To pull updates from the main repo `spyyddir/Footprint` you'll have to start by adding it as a "remote repo". You will only have to do this once:

    # Assuming you're in the root directory of your footprint project (the directory you got when you cloned your fork)
    $ git remote add zac git@github.com:spyyddir/Footprint.git
    
You can change `zac` in that line to anything you want, it is simply the name used to refer to my main repo. Now you'll be able to pull from changes down using `git pull zac <branch>`. Before you do this you should make sure to have all your work staged and committed. This command will do a merge of the remote branch you specify with your current working branch. You may want to create a new branch just for this merge if you think it might cause problems. The workflow for that would be similar to the following:

    # Again, you should be in the project directory
    $ git status
    
    # On branch master
    nothing to commit (working directory clean)
    
    $ git checkout -b zac-master                          # the -b creates the branch while checking out its code
    $ git status
    
    # On branch zac-master
    nothing to commit (working directory clean)
    
    $ git pull zac master
    
At this point you might get conflicts. If you do, there will be a list of conflicting files. Open these individually and they will have sections looking like this (I may have the wrong number of < or > marks):

    <<<<<<<<<<<<<<<<<HEAD
      some file contents
    =================
      some other contents
    >>>>>>>>>>>>>>>>>3294820938402834029 (the sha1 hash of the commit, will be different every time)
    
Figure out what should be in that place (it might be a mix of both) and remove the added lines, then add and commit as if you had been making normal changes. This will finish the commit successfully. You should now test the app to make sure nothing broke. Once that is done, you can merge this into your main code base:

    # assuming we're still on zac-master (you can check with git status)
    $ git checkout master
    $ git merge --no-ff zac-master
    $ git branch -d zac-master
    
Your master branch now has updated code.
    
## Cleaned Up Re-Structuring

All "library" files (such as those used to talk to foursquare) live in `/lib`. Main reachable pages (such as the index or the callback) live in the root `/`. Functions only called by ajax within other pages live in `/api`. Files included for templating and layout purposes live in `/template`. Unused older code (such as the non-implemented login system) is in `/unused`.