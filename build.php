<?php
$files = [
  'utils.php',
  'conversion.php',
  'graph.php',
  'contrasts.php',
  'distances.php',
  'oklab-gamut.php',
  'color-spaces.php',
  'named-colors.php',
  'css-formats.php',
  'couleur.php',
  'palette.php',
  'main.php'
];
$destination = './dist/colori.php';

echo "Starting to build colori.php ...<br>";

foreach($files as $k => $file) {
  // Get file contents
  $content = file_get_contents("./src/php/$file");
  echo "File $file opened<br>";

  // Look for namespace declarations and change them to brackets syntax
  /*preg_match('/<\?php namespace (.*?);/', $content, $matches);
  if (count($matches) > 0) {
    echo "Fixing namespace declaration...<br>";
    $content = str_replace($matches[0], 'namespace '.$matches[1].' {', $content);
    $content .= "\n\n\n}\n\n\n\n";
  } else {*/
    $content = str_replace('<?php', '', $content);
    $content .= "\n\n";
  /*}*/

  // Look for require_once and deletes them
  preg_match_all('/require_once (.*?);/', $content, $matches);
  foreach($matches[0] as $match) {
    echo "Removing require_once...<br>";
    $content = str_replace($match, '', $content);
  }

  if ($k === 0) file_put_contents($destination, "<?php\n");
  file_put_contents($destination, $content, FILE_APPEND);
  echo "File $file appended to colori.php<br>";
}

echo "colori.php built !";