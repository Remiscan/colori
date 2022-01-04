<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../dist/colori.php';
require_once 'tests-php.php';

$tests_json = file_get_contents('tests.json');
$testList = json_decode($tests_json);
$ordreMin = 4;
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>colori tests</title>

    <link rel="stylesheet" href="./styles.css">
    <style>
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

      .lists {
        display: flex;
      }

      h2 { grid-row: 2; }
      h3 {
        font-size: 1.3rem;
      }
      h3.php, h3.js {
        text-align: center;
      }

      div.js, div.php {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        overflow: hidden;
      }
      
      .php { grid-column: 1; }
      .js { grid-column: 2; }

      h4 {
        background-image: var(--gradient, linear-gradient(to right, var(--color, white) 0 100%)),
                          var(--echiquier-transparence);
        background-size: 100% 100%, 16px 16px, 16px 16px;
        background-position: 0 0, 0 0, 8px 8px;
        background-repeat: no-repeat, repeat, repeat;
        font-size: 1.17rem;
        padding: .5em;
      }

      span, pre { padding: 0 1rem; }
      pre { white-space: pre-wrap; }

      .no>pre:nth-of-type(1) { color: darkred; }

      aside {
        grid-row: 1;
        grid-column: 2;
      }

      @media (prefers-color-scheme: dark) {
        h4 {
          background-image: var(--gradient, linear-gradient(to right, var(--color, #333) 0 100%)),
                            var(--echiquier-transparence);
        }

        .no>pre:nth-of-type(1) { color: pink; }
      }
    </style>
  </head>

  <body>
    <div class="lists">
      <div>
        <h2>Categories</h2>
        <ul>
          <?php foreach($testList as $category => $tests) { ?>
            <li><a href="#<?=$category?>"><?=$category?></a>
          <?php } ?>
        </ul>
      </div>

      <div>
        <h2>Other tests</h2>
        <ul>
          <li><a href="CIEDE2000.php">CIEDE2000 distance</a>
          <li><a href="graph-conversion.php">Path-finding conversion</a>
          <li><a href="oklab.php">oklab color space</a>
          <li><a href="palette.php">Palette generation</a>
        </ul>
      </div>
    </div>

    <h2 class="php">colori.php tests</h2>

    <?php
    $failsList = [];

    $ordre = $ordreMin;

    foreach($testList as $category => $tests) {
      ?>
      <h3 class="php" style="grid-row: <?=$ordre?>;">
        <a id="<?=$category?>"></a>
        <?=$category?>
      </h3>
      <?php
      $ordre++;

      foreach($tests as $t) {
        try {
          $test = new Test($t->fonctionphp ?? $t->fonction, $t->resultatAttendu, property_exists($t, 'nophp'), $ordre);
          if (!$test->nophp) {
            $valid = $test->populate();
            if (!$valid) {
              $failsList[] = '<li><a href="#php-'. ($test->ordre + 3) .'">'. $test->nom() .'</a></li>';
            }
          }
          $ordre++;
        }
        catch (Throwable $error) { var_dump($error); }
      }
    }
    ?>

    <aside>
      <h2>Failed tests</h2>
      <h3>PHP (<span class="failed-php-count"><?php echo count($failsList); ?></span>)</h3>
      <ul class="failed-php">
        <?php echo implode('', $failsList); ?>
      </ul>

      <h3>JavaScript (<span class="failed-js-count">0</span>)</h3>
      <ul class="failed-js">
      </ul>
    </aside>

    <h2 class="js">colori.js tests</h2>

    <script type="module">
      // ▼ ES modules cache-busted grâce à PHP
      /*<?php ob_start();?>*/

      import Colour from '../dist/colori.js';
      import Test from './tests-javascript.js.php';

      /*<?php $imports = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
      echo versionizeFiles($imports, __DIR__); ?>*/



      const failsList = document.querySelector('.failed-js');
      let fails = 0;

      const tests_json = `<?=$tests_json?>`;
      const testList = JSON.parse(tests_json);
      let ordre = <?=$ordreMin?>;

      for (const [category, tests] of Object.entries(testList)) {
        const h3 = document.createElement('h3');
        h3.classList.add('js');
        h3.style.setProperty('grid-row', ordre);
        h3.innerHTML = category;
        document.body.appendChild(h3);
        ordre++;

        for (const t of tests) {
          const test = new Test(t.fonction, t.resultatAttendu, ordre);
          const valid = test.populate();
          if (!valid) {
            failsList.innerHTML += `<li><a href="#js-${ordre}">${test.nom}</a></li>`;
            fails++;
          }
          ordre++;
        }
      }

      document.querySelector('.failed-js-count').innerHTML = fails;
      const totalErrors = fails + <?=count($failsList)?>;
      document.querySelector('title').innerHTML = `colori tests (${totalErrors} error${totalErrors.length > 1 ? 's' : ''})`;
    </script>
  </body>
</html>