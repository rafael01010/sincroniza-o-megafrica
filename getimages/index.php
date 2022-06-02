<?php

// SETTINGS
$ftphost = "u302937.your-storagebox.de";
$ftpuser = "u302937-sub2";
$ftppass = "iGqGOFMvb8m8mt3r";
$source = "/";
$destination = "/var/www/html/getimages/img";
// END SETTINGS

//  CONNECT & LOGIN TO FTP SERVER
$ftp = ftp_connect($ftphost) or die("Failed to connect to $ftphost");
if (!ftp_login($ftp, $ftpuser, $ftppass)) {
  ftp_close($ftp);
  die("Invalid user/password");
}

//  DOWNLOAD EVERYTHING
function ftpGetAll($path = "/")
{
  //  FTP OBJECT & DESTINATION FOLDER
  global $ftp;
  global $destination;

  // CREATE FOLDER ON LOCAL SERVER
  $saveTo = $path == "/" ? $destination : $destination . $path;
  if (!file_exists($saveTo)) {
    if (mkdir($saveTo)) {
      echo "$saveTo created\r\n";
    } else {
      echo "Error creating $saveTo\r\n";
      return false;
    }
  }

  //  GET FILES
  $files = ftp_mlsd($ftp, $path);
  if (count($files) != 0) {
    foreach ($files as $f) {
      // (C4) FOLDER - RECURSIVE LOOP
      if ($f["type"] == "dir") {
        ftpGetAll($path . $f["name"] . "/");
      }

      //  FILE - DOWNLOAD
      else {
        echo ftp_get($ftp, $saveTo . $f["name"], $path . $f["name"], FTP_BINARY)
          ? "Saved to " . $saveTo . $f["name"] . "\r\n"
          : "Error downloading " . $path . $f["name"] . "\r\n";
      }
    }
  }
}
ftpGetAll($source);


//  CLOSE FTP CONNECTION
ftp_close($ftp);

//  FIX WORNG FOLDER NAMES
$dir = $destination;
$files1 = scandir($dir);
foreach ($files1 as $file) {
  if (strpos($file, '.') !== false) {
    rename($destination.'/'.$file,$destination.'/'.str_replace(".","",$file));
  }
  if (strpos($file, '-') !== false) {
    rename($destination.'/'.$file,$destination.'/'.str_replace("-","_",$file));
  }
}
