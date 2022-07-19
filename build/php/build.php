<?php
$dir = dirname(__DIR__, 2);
require_once $dir.'/src/php/graph.php';


$source = $dir.'/src/php';
$destination = $dir.'/dist/colori.php';
$files = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator(
    $source,
    RecursiveDirectoryIterator::SKIP_DOTS
  ),
  RecursiveIteratorIterator::SELF_FIRST
);

// Build a list of modules and their dependencies
$modules = [];
foreach($files as $k => $file) {
  if (is_dir($k)) continue;

  // Get file contents
  $content = file_get_contents($file->getPathname());

  // Get dependencies list
  $links = [];
  preg_match_all('/require_once (.*?);/', $content, $matches);
  foreach($matches[1] as $path) {
    preg_match('/\/([^\/]*?)\.php/', $path, $idMatches);
    $links[] = $idMatches[1];
  }
  $modules[] = [
    'id' => str_replace('.php', '', $file->getFilename()),
    'links' => $links,
    'data' => $file
  ];
}

// Build a graph with these modules, and get a topological order
$modulesGraph = new colori\Graph($modules);
$orderedModules = array_reverse(
  $modulesGraph->topologicalOrder()
);

// Build colori.php by bundling the modules together
echo "Starting to build $destination ...\n";

foreach($orderedModules as $k => $module) {
  // Get file contents
  $file = $module->data->getFilename();
  $content = file_get_contents($module->data->getPathname());
  echo "File $file opened\n";

  // Remove php tags
  $content = str_replace('<?php', '', $content);

  // Remove all occurrences of require_once
  preg_match_all('/(?: *?)require_once (.*?);\r?\n/', $content, $matches);
  foreach($matches[0] as $match) {
    echo "Removing require_once...\n";
    $content = str_replace($match, '', $content);
  }
  // Remove excessive new lines
  $content = preg_replace('/((?:\r?\n){3})(\r?\n)*/', '$1', $content);

  // Write the content to the destination file
  if ($k === 0) file_put_contents($destination, "<?php\n");
  file_put_contents($destination, $content, FILE_APPEND);
  echo "File $file appended to colori.php\n";
}

echo "$destination built !\n";