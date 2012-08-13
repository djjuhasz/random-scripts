<?php

// php move_source_to_target.php original_file.xliff translator_file.xliff > output_file.xliff

if (2 > count($argv))
{
  die("You must specify two files: (1) xliff file with the original source strings, and (2) xliff with the translated strings");
}

$docs = array();
foreach (array(1,2) as $i)
{
  $docs[$i-1] = simplexml_load_file($argv[$i]) or die("Couldn't read file {$argv[$i]}");
}

foreach ($docs[0]->file->body->{'trans-unit'} as $key => $unit)
{
  $node = $docs[1]->xpath('//trans-unit[@id='.$unit['id'].']');
  if (0 < count($node))
  {
    $target = (string) $node[0]->source;

    if (0 < strlen($target) && $target != (string) $unit->source)
    {
      $unit->target = $target;
    }
  }
}

echo $docs[0]->asXML();
