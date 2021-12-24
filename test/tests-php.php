<?php
require_once '../dist/colori.php';



class Test {
  const TOLERANCE = .03;
  const DISTANCE_PRECISE = 1;
  const DISTANCE_PROCHE = 5;

  function __construct($fonction = null, $resultatAttendu = null, $nophp = false, $ordre = 0) {
    // Convert JavaScript text to PHP test
    $exGetters = ['name', 'exactName', 'hexa', 'hex', 'rgba', 'rgb', 'hsla', 'hsl', 'hwba', 'hwb', 'laba', 'lab', 'lcha', 'lch', 'luminance', 'values'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $exGetters), 
      array_map(function($x) { return '->' . $x . '()'; }, $exGetters),
      $fonction
    );
    $methods = ['blend', 'contrast', 'bestColorScheme', 'improveContrast', 'change', 'replace', 'scale', 'complement', 'invert', 'negative', 'greyscale', 'grayscale', 'sepia', 'gradient', 'distance', 'same', 'unblend', 'whatToBlend', 'toGamut'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $methods), 
      array_map(function($x) { return '->' . $x; }, $methods),
      $f
    );
    $f = str_replace(
      ['Colour->', 'Colour'], 
      ['Couleur::', 'Couleur'],
      $f
    );
    $f = preg_replace('/\{ ([A-Za-z0-9_-]+?): (.+?) \}/', '$1: $2', $f);

    $this->fonction = $f;
    $this->resultatAttendu = $resultatAttendu;
    $this->resultatReel = null;
    $this->nophp = $nophp;
    $this->ordre = $ordre;
    $this->tested = false;
    $this->time = null;
  }

  public function resultat() {
    $time = microtime(true); // in case the inner test fails
    try {
      if ($this->tested !== true) {
        $time = microtime(true);
        $this->resultatReel = eval("return " . $this->fonction . ";");
        $this->time = microtime(true) - $time;
        $this->tested = true;
      }
      return $this->resultatReel;
    }
    catch (Throwable $error) {
      $this->time = microtime(true) - $time;
      return ['Error', $error];
    }
  }

  public function nom() {
    return $this->fonction . ';';
  }


  // Checks if the test results fit the expected results
  public function validate() {
    $resultat = $this->resultat();
    $isError = (is_array($resultat) && count($resultat) > 0 && $resultat[0] === 'Error');

    // If result is an error, check if we expected one
    if ($isError === true)
      return $this->resultatAttendu === 'Error';

    // If result is an array
    elseif (is_array($resultat)) {
      $res = false;
      try {
        // If the array contains colors / color strings, check if they're all the same
        $res = true;
        foreach($resultat as $k => $c) {
          $res = $res && (Couleur::same($c, $this->resultatAttendu[$k], self::DISTANCE_PROCHE));
        }
      } catch (Throwable $e) {
        // If not, just compare them
        $res = true;
        foreach($resultat as $k => $e) {
          $res = $res && ($e === $this->resultatAttendu[$k]);
        }
      }
      return $res;
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
      $res = false;
      try { $res = Couleur::same($resultat, $this->resultatAttendu); }
      catch (Throwable $error) { $res = $resultat === $this->resultatAttendu; }
      return $res;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static public function sameColorObject($couleur1, $couleur2) {
    $c1 = [$couleur1->r, $couleur1->g, $couleur1->b, $couleur1->a];
    $c2 = [$couleur2->r, $couleur2->g, $couleur2->b, $couleur2->a];
    return Couleur::same($c1, $c2, self::DISTANCE_PROCHE);
  }


  // Display the results of the test on the page
  public function populate() {
    $validation = $this->validate();
    $resultat = $this->resultat();

    $isError = is_array($resultat) && count($resultat) > 0 && $resultat[0] === 'Error';
    if ($isError) $resultat[1] = htmlspecialchars($resultat[1]);
    $titre = $this->nom();
    $time = $this->time * 1000;

    $class = $validation ? 'yes' : 'no';
    $texte = $validation ? '✅ Success' : '❌ Failure';
    $row = $this->ordre + 3;

    $recu = "\n\n".json_encode($resultat, JSON_PRETTY_PRINT);
    $attendu = "\n\n".json_encode($this->resultatAttendu, JSON_PRETTY_PRINT);
    
    $backgroundColor = '';
    $textColor = ''; $gradient = '';
    try {
      if (is_array($resultat) && !$isError) {
        if (count($resultat) > 1) $gradient = 'linear-gradient(to right, ' . (implode(', ', array_map(function($c) { return (new Couleur($c))->rgb(); }, $resultat))) . ')';
        if (count($resultat) > 0) $backgroundColor = new Couleur($resultat[0]);
      } else {
        $backgroundColor = new Couleur($resultat);
      }
    }
    catch (Exception $error) {}
    catch (Error $error) {}

    $textColor = ($backgroundColor instanceof Couleur) ? (
                   Couleur::blend('white', $backgroundColor)->bestColorScheme() === 'light' ? 'black' : 'white'
                 ): 'initial';
    $backgroundColor = ($backgroundColor instanceof Couleur) ? $backgroundColor->rgb() : $backgroundColor;

    echo <<<DIV
    <div class="php ${class}" style="grid-row: ${row}" data-validate="${validation}">
      <a id="php-${row}"></a>
      <h3 class="php" style="--color:${backgroundColor}; --gradient:${gradient}; color:${textColor};">${titre}</h3>
      <span class="php">${texte} in ${time} ms</span>
      <pre class="php">Reçu : ${recu}</pre>
      <pre class="php"">Attendu : ${attendu}</pre>
    </div>
    DIV;
  }
}