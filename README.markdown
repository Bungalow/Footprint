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

## Useful Data Structures

### User Info Array (from 4sq)

		(
		  [id] => 3773696
		  [firstname] => Zac
		  [lastname] => Clark
		  [friendstatus] => self
		  [homecity] => Boulder, CO
		  [photo] => http://playfoursquare.s3.amazonaws.com/userpix_thumbs/PVIXGAYSBEVU44IT.jpg
		  [gender] => male
		  [email] => hi+foursquare@zacclark.com
		  [types] => Array
		      (
		          [0] => user
		      )
		
		  [settings] => Array
		      (
		          [pings] => off
		          [sendtotwitter] => 
		          [sendtofacebook] => 
		      )
		
		  [status] => Array
		      (
		          [friendrequests] => 0
		      )
		
		  [checkin] => Array
		      (
		          [id] => 268977836
		          [created] => Tue, 16 Nov 10 21:41:14 +0000
		          [timezone] => America/Denver
		          [venue] => Array
		              (
		                  [id] => 102512
		                  [name] => Engineering Center - UCB
		                  [primarycategory] => Array
		                      (
		                          [id] => 78997
		                          [fullpathname] => Arts & Entertainment:Strip Club
		                          [nodename] => Strip Club
		                          [iconurl] => http://foursquare.com/img/categories/arts_entertainment/stripclub.png
		                      )
		
		                  [address] => Engineering Center
		                  [city] => Boulder
		                  [state] => CO
		                  [zip] => 80309
		                  [verified] => 
		                  [geolat] => 40.0070631
		                  [geolong] => -105.262544
		                  [hasTodo] => false
		              )
		
		          [display] => Zac C. @ Engineering Center - UCB
		      )
		
		  [badges] => Array
		      (
		          [0] => Array
		              (
		                  [id] => 1
		                  [name] => Newbie
		                  [icon] => http://foursquare.com/img/badge/newbie.png
		                  [description] => Congrats on your first check-in!
		              )
		
		          [1] => Array
		              (
		                  [id] => 2
		                  [name] => Adventurer
		                  [icon] => http://foursquare.com/img/badge/adventurer.png
		                  [description] => You've checked into 10 different venues!
		              )
		
		          [2] => Array
		              (
		                  [id] => 3
		                  [name] => Explorer
		                  [icon] => http://foursquare.com/img/badge/explorer.png
		                  [description] => You've checked into 25 different venues!
		              )
		
		          [3] => Array
		              (
		                  [id] => 7
		                  [name] => Local
		                  [icon] => http://foursquare.com/img/badge/local.png
		                  [description] => You've been at the same place 3x in one week!
		              )
		
		          [4] => Array
		              (
		                  [id] => 8
		                  [name] => Super User
		                  [icon] => http://foursquare.com/img/badge/superuser.png
		                  [description] => That's 30 check-ins in a month for you!
		              )
		
		      )
		
		  [mayorcount] => 4
		  [mayor] => Array
		      (
		          [0] => Array
		              (
		                  [id] => 10555155
		                  [name] => 2554 Paintbrush
		                  [address] => 
		                  [city] => 
		                  [state] => 
		                  [verified] => 
		                  [geolat] => 40.001458
		                  [geolong] => -105.129957
		                  [hasTodo] => false
		              )
		
		          [1] => Array
		              (
		                  [id] => 10329439
		                  [name] => 500 Manhattan
		                  [primarycategory] => Array
		                      (
		                          [id] => 79132
		                          [fullpathname] => Home / Work / Other:Home
		                          [nodename] => Home
		                          [iconurl] => http://foursquare.com/img/categories/building/home.png
		                      )
		
		                  [address] => 
		                  [city] => 
		                  [state] => 
		                  [verified] => 
		                  [geolat] => 39.996618
		                  [geolong] => -105.22879
		                  [hasTodo] => false
		              )
		
		          [2] => Array
		              (
		                  [id] => 11250505
		                  [name] => Apex Movement Boulder
		                  [address] => 
		                  [city] => 
		                  [state] => 
		                  [verified] => 
		                  [geolat] => 40.015073
		                  [geolong] => -105.21908
		                  [hasTodo] => false
		              )
		
		          [3] => Array
		              (
		                  [id] => 48092
		                  [name] => Whole Foods
		                  [primarycategory] => Array
		                      (
		                          [id] => 79235
		                          [fullpathname] => Shops:Food & Drink:Grocery / Supermarket
		                          [nodename] => Grocery / Supermarket
		                          [iconurl] => http://foursquare.com/img/categories/shops/food_grocery.png
		                      )
		
		                  [address] => 2584 Baseline Rd
		                  [crossstreet] => Broadway
		                  [city] => Boulder
		                  [state] => CO
		                  [zip] => 80302
		                  [verified] => 
		                  [geolat] => 40.0002715
		                  [geolong] => -105.261102
		                  [hasTodo] => false
		              )
		
		      )
		
		)