<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'colori.php';

// Calcule la version du site
function version()
{
  $listeFichiers = array_diff(scandir(__DIR__), array('..', '.'));
  $versionFichiers = 0;
  foreach($listeFichiers as $fichier)
  {
    $date_fichier = filemtime($fichier);
    if ($date_fichier > $versionFichiers)
      $versionFichiers = $date_fichier;
  }
  $versionFichiers = date('Y.m.d_H.i.s', $versionFichiers);
  return $versionFichiers;
}
$version = version();

class Test {
  function __construct($fonction = null, $resultatAttendu = null, $nophp = false) {
    $exGetters = ['name', 'hexa', 'hex', 'rgba', 'rgb', 'hsla', 'hsl', 'hwba', 'hwb', 'laba', 'lab', 'lcha', 'lch', 'luminance'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $exGetters), 
      array_map(function($x) { return '->' . $x . '()'; }, $exGetters),
      $fonction
    );
    $methods = ['blend', 'contrast', 'contrastedText', 'betterContrast', 'change', 'replace', 'scale', 'complement', 'invert', 'negative', 'darken', 'lighten', 'desaturate', 'saturate', 'greyscale', 'grayscale', 'gradient', 'distance', 'same', 'unblend'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $methods), 
      array_map(function($x) { return '->' . $x; }, $methods),
      $f
    );
    $vars = ['couleur', 'testContraste', 'testChangeHue', 'testLab'];
    $f = str_replace(
      array_map(function($x) { return $x; }, $vars),
      array_map(function($x) { return '$' . $x; }, $vars),
      $f
    );
    $f = str_replace(
      ['Colour->', 'Colour'], 
      ['Couleur::', 'Couleur'],
      $f
    );
    $f = str_replace(
      ['{ scale: true }', '{ scale: false }', '{ replace: true }', '{ replace: false }', '{ lower: true }'],
      ['(object)["scale"=>true]', '(object)["scale"=>false]', '(object)["replace"=>true]', '(object)["replace"=>false]', '(object)["lower"=>true]'],
      $f
    );
    $this->fonction = $f;
    $this->resultatAttendu = $resultatAttendu;
    $this->nophp = $nophp;
  }

  public function resultat() {
    try {
      $vars = '
        $couleur = new Couleur(\'rgba(58, 184, 74, 0.4)\');
        $couleurh = new Couleur(\'hsla(128, 52%, 47%, 0.4)\');
        $testContraste = new Couleur(\'hsl(200, 10%, 10%)\');
        $testChangeHue = new Couleur(\'hsl(350, 25%, 52%)\');
        $testLab = new Couleur(\'lab(92% -45 9)\');
      ';
      return eval($vars . "return " . $this->fonction . ";");
    }
    catch (Error $error) {
      return ['Error', $error];
    }
    catch (Exception $error) {
      return ['Error', $error];
    }
  }

  public function nom() {
    return $this->fonction . ';';
  }

  public function validate() {
    try {
      $resultat = $this->resultat();
      $resultat = (is_array($resultat) && $resultat[0] == 'Error') ? $resultat[0] : $resultat;
    } catch(ParseError $error) {
      return false;
    }

    if (is_a($this->resultatAttendu, 'stdClass')) {
      if ($resultat == 'Error') return false;

      $c1Props = get_object_vars($resultat);
      $c2Props = get_object_vars($this->resultatAttendu);

      foreach($c2Props as $prop => $value) {
        $tolerance = .02;
        if (
          ($prop == 'cieh' && $c1Props['ciec'] <= $tolerance && $c2Props['ciec'] <= $tolerance)
          || (in_array($prop, ['s', 'h']) && $c1Props['l'] >= 1 - $tolerance && $c2Props['l'] >= 1 - $tolerance)
          || (in_array($prop, ['s', 'h']) && $c1Props['l'] <= $tolerance && $c2Props['l'] <= $tolerance)
          || ($prop == 'h' && $c1Props['s'] <= $tolerance && $c2Props['s'] <= $tolerance)
          || ($prop == 'h' && $c1Props['bk'] + $c1Props['w'] >= 1 - $tolerance && $c2Props['bk'] + $c2Props['w'] >= 1 - $tolerance)
        ) continue;
        if (in_array($prop, ['ciea', 'cieb', 'ciec'])) $tolerance *= 100;
        if (
          $value != $c1Props[$prop]
          && abs($value - $c1Props[$prop]) > $tolerance // fix float rounding error
        ) return false;
      }

      return true;
    }

    elseif (is_array($resultat) && is_array($this->resultatAttendu)) {
      foreach($resultat as $k => $co) {
        if (!Couleur::same($co, $this->resultatAttendu[$k])) return false;
      }
      return true;
    }

    elseif (is_numeric($this->resultatAttendu)) {
      if (($resultat - $this->resultatAttendu) > .02) return false;
      else return true;
    }

    else {
      try {
        $tempResult = Couleur::same($resultat, $this->resultatAttendu);
        return $tempResult;
      }
      catch (Exception $error) {}
      catch (Error $error) {}
      return $resultat == $this->resultatAttendu;
    }
  }
}

$tests_json = file_get_contents('tests.json');
$tests = json_decode($tests_json);
$tests = array_map(function($test) { return new Test($test->fonctionphp ?? $test->fonction, $test->resultatAttendu, property_exists($test, 'nophp')); }, $tests);
?>
<!doctype html>
<html>
  <head> 
    <style>
      body {
        display: grid;
        grid-template-columns: 50vw 50vw;
        gap: 2px 0;
      }
      h2 {
        grid-row: 1;
      }
      h3 {
        padding: .5em;
      }
      div.js, div.php {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
      }
      .yes {
        background-color: <?php $c = new Couleur('palegreen'); echo $c->replace('a', '.2')->hsl(); ?>;
      }
      .no {
        background-color: pink;
      }
      .php {
        grid-column: 1;
      }
      .js {
        grid-column: 2;
      }
    </style>
  </head>

  <body>
    <h2 class="php">Tests de colori.php</h2>

    <?php

    foreach($tests as $k => $test) {
      if ($test->nophp) continue;
      $c = null;
      try {
        $resultat = $test->resultat();
        if (is_a($resultat, 'Couleur')) $c = $resultat;
        elseif (is_array($resultat))    $c = $resultat;
        else $c = new Couleur($test->resultatAttendu);
      }
      catch(Exception $error) {
        //continue;
      }
      $resultat = (is_array($resultat) && $resultat[0] == 'Error') ? [$resultat[0], $resultat[1]->getMessage()] : $resultat;
      ?>
      <div class="php <?=$test->validate() ? 'yes' : 'no'?>" style="grid-row: <?=$k+2?>">
        <h3 class="php" style="<?php
          if (is_array($c)) {
            try {
              $gradientString = implode(',', array_map(function($co) { return (new Couleur($co))->rgb(); }, $c));
              echo 'background-image: linear-gradient(to right, ' . $gradientString . '); color: ' . (new Couleur($c[0]))->replace('a', 1)->contrastedText();
            } catch (Exception $err) {}
          }
          elseif ($c != null) echo 'background-color: ' . $c->rgba() . '; color: ' . $c->replace('a', 1)->contrastedText();
        ?>"><?=$test->nom()?></h3>
        <span class="php"><?php
          echo $test->validate() ? '✅ Success' : '❌ Failure';
        ?></span>
        <pre class="php">Reçu : <?="\n\n".json_encode($resultat, JSON_PRETTY_PRINT)?></pre>
        <?php if (!$test->validate()) { ?>
          <pre class="php" style="color: red;">Attendu : <?="\n\n".json_encode($test->resultatAttendu, JSON_PRETTY_PRINT)?></pre>
        <?php } ?>
      </div>
      <?php
    }
    ?>

    <h2 class="js">Tests de colori.js</h2>

    <script type="module">
      import Colour from './colori--<?=$version?>.js';

      const couleur = new Colour('rgba(58, 184, 74, 0.4)');
      const couleurh = new Colour('hsla(128, 52%, 47%, 0.4)');
      const testContraste = new Colour('hsl(200, 10%, 10%)');
      const testChangeHue = new Colour('hsl(350, 25%, 52%)');
      const testLab = new Colour('lab(92% -45 9)');

      const tolerance = .03;

      function sameColor(couleur1, couleur2) {
        var c1Props = Object.getOwnPropertyNames(couleur1);
        var c2Props = Object.getOwnPropertyNames(couleur2);

        if (c1Props.length != c2Props.length) return false;

        for (var i = 0; i < c1Props.length; i++) {
          let tempTolerance = tolerance;
          var prop = c1Props[i];
          if (
            (prop == 'cieh' && couleur1.ciec <= tolerance && couleur2.ciec <= tolerance)
            || (['s', 'h'].includes(prop) && couleur1.l >= 1 - tolerance && couleur2.l >= 1 - tolerance)
            || (['s', 'h'].includes(prop) && couleur1.l <= tolerance && couleur2.l <= tolerance)
            || (prop == 'h' && couleur1.s <= tolerance && couleur2.s <= tolerance)
            || (prop == 'h' && couleur1.bk + couleur1.w >= 1 - tolerance && couleur2.bk + couleur2.w >= 1 - tolerance)
          ) continue;
          if (['ciea', 'cieb', 'ciec'].includes(prop)) tempTolerance *= 100;
          if (Math.abs(couleur1[prop] - couleur2[prop]) > tempTolerance) return false;
        }

        return true;
      }

      class Test {
        constructor(fonction = null, resultatAttendu = {}) {
          this.fonction = fonction;
          this.resultatAttendu = resultatAttendu;
          this.resultatReel = null;
        }

        get resultat() {
          try {
            if (!this.resultatReel) this.resultatReel = eval(this.fonction);
            return this.resultatReel;
          }
          catch(error) {
            return ['Error', error];
          }
        }

        get nom() {
          return this.fonction;
        }

        validate() {
          const resultat = (typeof this.resultat == 'object' && this.resultat != null && this.resultat[0] == 'Error') ? this.resultat[0]
                                                                                                                      : this.resultat;
          if (resultat == 'Error') return this.resultatAttendu == 'Error';
          else if (Array.isArray(resultat)) return resultat.every((co, k) => co == this.resultatAttendu[k]);
          else if (typeof this.resultatAttendu === 'object') return sameColor(resultat, this.resultatAttendu);
          else if (typeof this.resultatAttendu === 'number') {
            if ((resultat - this.resultatAttendu) > tolerance) return false;
            else return true;
          }
          else {
            let tempResult;
            try { tempResult = Colour.same(resultat, this.resultatAttendu); }
            catch (error) { }
            return tempResult || resultat == this.resultatAttendu;
          }
        }
      }

      const tests_json = <?=$tests_json?>;
      const tests = tests_json.map(test => new Test(test.fonction, test.resultatAttendu));

      tests.forEach((test, k) => {
        const div = document.createElement('div');
        div.classList.add('js');
        div.classList.add(test.validate() ? 'yes' : 'no');
        div.style.setProperty('grid-row', k + 2);
        const h3 = document.createElement('h3');
        h3.classList.add('js');
        const pre = document.createElement('pre');
        pre.classList.add('js');
        let span, pre2;

        span = document.createElement('span');
        span.classList.add('js');
        h3.innerHTML = test.nom;
        span.innerHTML = test.validate() ? '✅ Success' : '❌ Failure';
        const resultat = test.resultat;
        pre.innerHTML = 'Reçu :\n\n' + JSON.stringify(resultat, null, 2);
        if (!test.validate()) {
          pre2 = document.createElement('pre');
          pre2.innerHTML = 'Attendu :\n\n' + JSON.stringify(test.resultatAttendu, null, 2);
          pre2.style.setProperty('color', 'red');
          pre2.classList.add('js');
        }

        let c;
        try {
          if (resultat instanceof Colour) c = resultat;
          else if (Array.isArray(resultat)) {
            c = resultat;
            const gradient = `linear-gradient(to right, ${c.map(co => (new Colour(co)).rgb).join(', ')})`;
            console.log(gradient);
            h3.style.setProperty('background-image', gradient);
            c = new Colour(c[0]);
          }
          else c = new Colour(test.resultatAttendu);

          h3.style.setProperty('background-color', c.rgba);
          h3.style.setProperty('color', c.replace('a', 1).contrastedText());
        }
        catch(error) {console.log(error)}
        
        div.appendChild(h3);
        if (span) div.appendChild(span);
        div.appendChild(pre);
        if (pre2) div.appendChild(pre2);
        document.body.appendChild(div);
      });
    </script>
  </body>
</html>