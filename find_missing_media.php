#!/usr/bin/php

<?php

if ($argc != 3) {
  $help = <<<EOL
Loop through all entries in <importfile>, search for a match in <logfile> and write
all unmatched entries to stdout

  Usage:
  find_missing_media.php <importfile> <logfile>

<?php
EOL;

  // Output help text and exit
  file_put_contents('php://stderr', $help);

  exit(1);
}

$handle = fopen($argv[1], "r") or die('Could not open '.$argv[1].' for reading');
$stdout = fopen('php://stdout', 'w');
$importLog = file($argv[2], FILE_IGNORE_NEW_LINES) or die('Could not read '.$argv[2]);

// Skip header
$header = fgetcsv($handle, 1000, ",");

while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
  $match = false;

  // Search on the filename without path
  $filename = basename($row[0]);

  // Search import log for match
  foreach ($importLog as $value) {
    // If filename found in import log, exit loop and don't do anything
    if (strpos($value, $filename) !== FALSE) {
      $match = true;

      break;
    }
  }

  // Echo unmatched files
  if (!$match) {
    echo fputcsv($stdout, $row);
  }
}
?>
