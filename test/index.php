<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../colori.php';
require_once 'tests-php.php';



$tests_json = file_get_contents('tests.json');
$_tests = json_decode($tests_json);
$tests = [];
foreach($_tests as $k => $test) {
  $tests[] = new Test($test->fonctionphp ?? $test->fonction, $test->resultatAttendu, property_exists($test, 'nophp'), $k);
}
?>
<!doctype html>
<html>
  <head>
    <style>
      html {
        color-scheme: dark;
      }
      body {
        width: 100%;
        height: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px 10px;
        margin: 0;
        padding: 10px;
        font-size: 1rem;
        box-sizing: border-box;
        --echiquier-transparence: linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
          linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
          linear-gradient(to right, #ddd 0% 100%);
      }
      h2 {
        grid-row: 2;
      }
      h3 {
        padding: .5em;
      }
      div.js, div.php {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        overflow: hidden;
      }
      .yes {
        background-color: <?php $c = new Couleur('palegreen'); echo $c->replace('a', '.2')->hsl(); ?>;
      }
      .no {
        background-color: <?php $c = new Couleur('pink'); echo $c->replace('a', '.2')->hsl(); ?>;
      }
      .php {
        grid-column: 1;
      }
      .js {
        grid-column: 2;
      }
      h3 {
        background-image: var(--gradient, linear-gradient(to right, var(--color, white) 0 100%)),
                          var(--echiquier-transparence);
        background-size: 100% 100%, 16px 16px, 16px 16px;
        background-position: 0 0, 0 0, 8px 8px;
        background-repeat: no-repeat, repeat, repeat;
      }

      span, pre {
        padding: 0 1rem;
      }

      pre {
        white-space: pre-wrap;
      }
      .no>pre:nth-of-type(1) {
        color: darkred;
      }
      @media (prefers-color-scheme: dark) {
        h3 {
          background-image: var(--gradient, linear-gradient(to right, var(--color, #333) 0 100%)),
                            var(--echiquier-transparence);
        }
        .no>pre:nth-of-type(1) {
          color: pink;
        }
      }
      /*pre:nth-of-type(2) {
        display: none;
      }
      .no>pre:nth-of-type(2) {
        display: block;
      }*/

      ul {
        grid-column: 1 / -1;
        grid-row: 1;
      }
    </style>
  </head>

  <body>
    <ul>
      <li style="list-style: none">Other tests</li>
      <li><a href="CIEDE2000.php">CIEDE2000 distance</a>
      <li><a href="graph-conversion.php">Path-finding conversion</a>
    </ul>

    <h2 class="php">Tests de colori.php</h2>

    <?php

    foreach($tests as $k => $test) {
      if ($test->nophp) continue;
      try {
        $test->populate();
      }
      catch (Error $error) { var_dump($error); }
      catch (Exception $error) { var_dump($error); }
      catch (SyntaxError $error) { var_dump($error); }
    }
    ?>

    <h2 class="js">Tests de colori.js</h2>

    <script type="module">
      // ▼ ES modules cache-busted grâce à PHP
      /*<?php ob_start();?>*/

      import Colour from '../colori.js';
      import Test from './tests-javascript.js.php';

      /*<?php $imports = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
      echo versionizeFiles($imports, __DIR__); ?>*/



      const tests_json = <?=$tests_json?>;
      const tests = tests_json.map((test, k) => new Test(test.fonction, test.resultatAttendu, k));
      tests.forEach(test => test.populate());
    </script>
  </body>
</html>