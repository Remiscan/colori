<?php
require_once './src/php/graph.php';


$source = './src/php';
$destination = './dist/colori.php';
$files = array_diff(scandir($source), array('.', '..'));

// Build a list of modules and their dependencies
$modules = [];
foreach($files as $k => $file) {
  // Get file contents
  $content = file_get_contents("./src/php/$file");

  // Get dependencies list
  $links = [];
  preg_match_all('/require_once (.*?);/', $content, $matches);
  foreach($matches[1] as $path) {
    preg_match('/\/([^\/]*?)\.php/', $path, $idMatches);
    $links[] = $idMatches[1];
  }
  $modules[] = [
    'id' => str_replace('.php', '', $file),
    'links' => $links
  ];
}

// Build a graph with these modules, and get a topological order
$modulesGraph = new colori\Graph($modules);
$orderedModules = $modulesGraph->topologicalOrder();
$orderedModules = array_reverse(array_map(function ($mod) { return $mod->id(); }, $orderedModules));

// Build colori.php by bundling the modules together
echo "Starting to build $destination ...<br>";

foreach($orderedModules as $k => $module) {
  // Get file contents
  $file = $module.'.php';
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