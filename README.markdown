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