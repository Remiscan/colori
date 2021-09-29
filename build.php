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

echo "Starting to build $destination ...<br>";

foreach($files as $k => $file) {
  // Get file contents
  $content = file_get_contents("./src/php/$file");
  echo "File $file opened<br>";

  // Remove php tags
  $content = str_replace('<?php', '', $content);

  // Remove all occurrences of require_once
  preg_match_all('/(?: *?)require_once (.*?);\r?\n/', $content, $matches);
  foreach($matches[0] as $match) {
    echo "Removing require_once...<br>";
    $content = str_replace($match, '', $content);
  }
  // Remove excessive new lines
  $content = preg_replace('/((?:\r?\n){3})(\r?\n)*/', '$1', $content);

  // Write the content to the destination file
  if ($k === 0) file_put_contents($destination, "<?php\n");
  file_put_contents($destination, $content, FILE_APPEND);
  echo "File $file appended to colori.php<br>";
}

echo "$destination built !";