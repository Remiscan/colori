<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../dist/colori.php';
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
      html { color-scheme: light dark; }
      .yes { background-color: palegreen; }
      .close { background-color: gold; }
      .no { background-color: pink; }
      
      @media (prefers-color-scheme: dark) {
        body {color: white; }
        .yes { background-color: <?php $c = new Couleur('palegreen'); echo $c->replace('a', '.2')->hsl(); ?>; }
        .close { background-color: <?php $c = new Couleur('gold'); echo $c->replace('a', '.2')->hsl(); ?>; }
        .no { background-color: <?php $c = new Couleur('pink'); echo $c->replace('a', '.2')->hsl(); ?>;}
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

      h2 { grid-row: 2; }
      h3 { padding: .5em; }

      div.js, div.php {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        overflow: hidden;
      }
      
      .php { grid-column: 1; }
      .js { grid-column: 2; }

      h3 {
        background-image: var(--gradient, linear-gradient(to right, var(--color, white) 0 100%)),
                          var(--echiquier-transparence);
        background-size: 100% 100%, 16px 16px, 16px 16px;
        background-position: 0 0, 0 0, 8px 8px;
        background-repeat: no-repeat, repeat, repeat;
      }

      span, pre { padding: 0 1rem; }
      pre { white-space: pre-wrap; }

      .no>pre:nth-of-type(1) { color: darkred; }

      aside {
        grid-row: 1;
        grid-column: 2;
      }

      @media (prefers-color-scheme: dark) {
        h3 {
          background-image: var(--gradient, linear-gradient(to right, var(--color, #333) 0 100%)),
                            var(--echiquier-transparence);
        }

        .no>pre:nth-of-type(1) { color: pink; }
      }

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
      <li><a href="oklab.php">oklab color space</a>
      <li><a href="palette.php">Palette generation</a>
    </ul>

    <h2 class="php">Tests de colori.php</h2>

    <?php
    $failsList = [];
    foreach($tests as $k => $test) {
      if ($test->nophp) continue;
      try {
        $test->populate();
        if (!$test->validate()) $failsList[] = '<li><a href="#php-'. ($test->ordre + 3) .'">'. $test->nom() .'</a></li>';
      }
      catch (Error $error) { var_dump($error); }
      catch (Exception $error) { var_dump($error); }
      catch (SyntaxError $error) { var_dump($error); }
    }
    ?>

    <aside>
      <h2>Failed tests</h2>
      <ul class="failed-php">
        <li style="list-style: none">PHP (<span class="failed-php-count"><?php echo count($failsList); ?></span>)</li>
        <?php echo implode('', $failsList); ?>
      </ul>

      <ul class="failed-js">
        <li style="list-style: none">JavaScript (<span class="failed-js-count">0</span>)</li>
      </ul>
    </aside>

    <h2 class="js">Tests de colori.js</h2>

    <script type="module">
      // ▼ ES modules cache-busted grâce à PHP
      /*<?php ob_start();?>*/

      import Colour from '../dist/colori.js';
      import Test from './tests-javascript.js.php';

      /*<?php $imports = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
      echo versionizeFiles($imports, __DIR__); ?>*/



      const tests_json = <?=$tests_json?>;
      const tests = tests_json.map((test, k) => new Test(test.fonction, test.resultatAttendu, k));
      const failsList = document.querySelector('.failed-js');
      let fails = 0;
      for (const test of tests) {
        const valid = test.populate();
        if (!valid) {   
          failsList.innerHTML += `<li><a href="#js-${test.ordre + 3}">${test.nom}</a></li>`;
          fails++;
        }
      }
      document.querySelector('.failed-js-count').innerHTML = fails;
    </script>
  </body>
</html>