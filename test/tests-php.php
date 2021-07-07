<?php
require_once '../colori.php';



class Test {
  const TOLERANCE = .03;

  function __construct($fonction = null, $resultatAttendu = null, $nophp = false, $ordre = 0) {
    // Convert JavaScript text to PHP test
    $exGetters = ['name', 'exactName', 'hexa', 'hex', 'rgba', 'rgb', 'hsla', 'hsl', 'hwba', 'hwb', 'laba', 'lab', 'lcha', 'lch', 'luminance'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $exGetters), 
      array_map(function($x) { return '->' . $x . '()'; }, $exGetters),
      $fonction
    );
    $methods = ['blend', 'contrast', 'contrastedText', 'improveContrast', 'change', 'replace', 'scale', 'complement', 'invert', 'negative', 'greyscale', 'grayscale', 'sepia', 'gradient', 'distance', 'same', 'unblend', 'whatToBlend', 'APCAcontrast'];
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
    $this->ordre = $ordre;
  }

  public function resultat() {
    try {
      return eval("return " . $this->fonction . ";");
    }
    catch (Error $error) { return ['Error', $error]; }
    catch (Exception $error) { return ['Error', $error]; }
  }

  public function nom() {
    return $this->fonction . ';';
  }


  // Checks if the test results fit the expected results
  public function validate() {
    try {
      $resultat = $this->resultat();
      $resultat = (is_array($resultat) && $resultat[0] === 'Error') ? $resultat[0] : $resultat;
    } catch(ParseError $error) {
      return false;
    }

    // If result is an error, check if we expected one
    if ($resultat === 'Error')
      return $this->resultatAttendu === 'Error';

    // If result is an array
    elseif (is_array($resultat)) {
      $res = true;
      try {
        // If the array contains colors / color strings, check if they're all the same
        foreach($resultat as $k => $c) {
          $res = $res && (Couleur::same($c, $this->resultatAttendu[$k]));
        }
      } catch (Exception $e) {
        // If not, just compare them
        foreach($resultat as $k => $e) {
          $res = $res && ($e === $this->resultatAttendu[$k]);
        }
      }
      return true;
    }
      
    // If expected result is an object, check if it has the same properties and values as the result
    elseif (is_a($this->resultatAttendu, 'stdClass') && $this->resultatAttendu !== null)
      return self::sameColorObject($resultat, $this->resultatAttendu);

    // If expected result is a number, check if it's close enough to the result
    elseif (is_float($this->resultatAttendu) || is_int($this->resultatAttendu)) {
      if (abs($resultat - $this->resultatAttendu) > self::TOLERANCE) return false;
      else return true;
    }

    // Else, try to make colors from the result and expected result and check if they're the same
    else {
      try { $tempResult = Couleur::same($resultat, $this->resultatAttendu); }
      catch (Exception $error) {}
      catch (Error $error) {}
      return $tempResult ?? $resultat === $this->resultatAttendu;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static public function sameColorObject($couleur1, $couleur2) {
    $c1 = get_object_vars($couleur1);
    $c2 = get_object_vars($couleur2);
    foreach($c1 as $p => $v) {
      $v1 = (float) $v;
      $v2 = (float) $c2[$p];
      if (abs($v1 - $v2) > self::TOLERANCE) return false;
    }
    return true;
  }


  // Display the results of the test on the page
  public function populate() {
    $validation = $this->validate();
    $resultat = $this->resultat();
    if (is_array($resultat) && $resultat[0] === 'Error') $resultat[1] = htmlspecialchars($resultat[1]);
    $titre = $this->nom();

    $class = $validation ? 'yes' : 'no';
    $texte = $validation ? '✅ Success' : '❌ Failure';
    $displayPre2 = $validation ? 'none' : 'block';
    $row = $this->ordre + 2;

    $recu = "\n\n".json_encode($resultat, JSON_PRETTY_PRINT);
    $attendu = "\n\n".json_encode($this->resultatAttendu, JSON_PRETTY_PRINT);
    
    $backgroundColor = new Couleur('white');
    $gradient = '';
    try {
      if ($resultat instanceof Couleur) $backgroundColor = $resultat;
      elseif (is_array($resultat)) {
        $gradient = 'linear-gradient(to right, ' . (implode(', ', array_map(function($c) { return (new Couleur($c))->rgb(); }, $resultat))) . ')';
        $backgroundColor = new Couleur($resultat[0]);
      }
      elseif (is_string($this->resultatAttendu)) $backgroundColor = new Couleur($this->resultatAttendu);
    }
    catch (Exception $error) {}
    catch (Error $error) {}

    $textColor = 'black';
    try {
      $textColor = ($backgroundColor->name() !== 'transparent') ? $backgroundColor->replace('a', 1)->contrastedText() : 'black';
    } catch (Exception $error) {
      $textColor = 'black';
    }
    $backgroundColor = $backgroundColor->rgb() ?? 'white';

    echo <<<DIV
    <div class="php $class" style="grid-row: $row" data-validate="$validation">
      <h3 class="php" style="--color:$backgroundColor; --gradient:$gradient; color:$textColor;">$titre</h3>
      <span class="php">$texte</span>
      <pre class="php">Reçu : $recu</pre>
      <pre class="php" style="display: $displayPre2; color: darkred;">Attendu : $attendu</pre>
    </div>
    DIV;
  }
}