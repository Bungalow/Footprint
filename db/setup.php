<?php
// This file drops and re-creates the the database schema. Use with caution.

require_once('../config.php');

if (mysql_connect($db_host, $db_user, $db_pass)) {
  if (mysql_selectdb($db_name)) {
    $queries = array(
      array(
        "DROP TABLE IF EXISTS `footprint`.`destinations`;",
        "Dropped old destinations table if it existed."
      ),
      array(
        "CREATE TABLE  `footprint`.`destinations` (
        `userid` int(10) unsigned NOT NULL DEFAULT '0',
        `foursquarevid` int(10) unsigned NOT NULL DEFAULT '0',
        `checkintime` varchar(45) NOT NULL DEFAULT '0',
        `mileage` float unsigned NOT NULL DEFAULT '0',
        `transportmode` varchar(10) DEFAULT '0',
        `ignorecheckin` tinyint(1) NOT NULL DEFAULT '0',
        `foursquareurl` varchar(60) DEFAULT NULL,
        `geolat` float NOT NULL DEFAULT '0',
        `geolong` float NOT NULL DEFAULT '0',
        `foursquarecheckinid` int(10) unsigned NOT NULL DEFAULT '0',
        `venuename` varchar(255) NOT NULL DEFAULT '0',
        `laststop` tinyint(1) NOT NULL DEFAULT '0',
        `startpoint` varchar(10) NOT NULL DEFAULT '0',
        PRIMARY KEY (`userid`,`checkintime`,`foursquarevid`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;", 
        "Created destinations table."
      ),
      array(
        "DROP TABLE IF EXISTS `footprint`.`homebases`;",
        "Dropped old homebases table if it existed."
      ),
      array(
        "CREATE TABLE  `footprint`.`homebases` (
          `homebaseid` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `userid` int(10) unsigned NOT NULL,
          `foursquarevid` int(10) unsigned NOT NULL DEFAULT '0',
          `geolat` float NOT NULL DEFAULT '0',
          `geolong` float NOT NULL DEFAULT '0',
          `displayname` varchar(255) NOT NULL DEFAULT 'Homebase',
          PRIMARY KEY (`homebaseid`,`userid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
        "Created homebases table."
      ),
      array(
        "DROP TABLE IF EXISTS `footprint`.`transports`;",
        "Dropped old transports table if it existed."
      ),
      array(
        "CREATE TABLE  `footprint`.`transports` (
          `transportid` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `transporttype` varchar(45) NOT NULL,
          `transportdesc` varchar(100) NOT NULL,
          PRIMARY KEY (`transportid`,`transportdesc`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;",
        "Created transports table."
      ),
      array(
        "DROP TABLE IF EXISTS `footprint`.`users`;",
        "Dropped old users table if it existed."
      ),
      array(
        "CREATE TABLE  `footprint`.`users` (
          `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `username` varchar(255) NOT NULL,
          `footprintpass` varchar(255) NOT NULL,
          `foursquareoauth` varchar(255) NOT NULL,
          PRIMARY KEY (`userid`)
        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;",
        "Created users table."
      ),
      array(
        "DROP TABLE IF EXISTS `footprint`.`usertransports`;",
        "Dropped old usertransports table if it existed."
      ),
      array(
        "CREATE TABLE  `footprint`.`usertransports` (
          `userid` int(10) unsigned NOT NULL,
          `transportid` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `transporttype` varchar(60) NOT NULL,
          `transportdesc` varchar(60) DEFAULT NULL,
          PRIMARY KEY (`transportid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;",
        "Created usertransports table."
      )
    );
    
    $passing = 0;
    foreach ($queries as $query) {
      if (mysql_query($query[0])) { echo $query[1] . "<br />"; $passing++; } else { echo "Error: " . mysql_error(); }    
    }
    echo "<br />";
    echo $passing . "/" . count($queries) . " queries passed!";
  }else{
    die("Cannot find db " . $db_name);
  }
}else{
  die("Cannot connect to mysql");
}
?>