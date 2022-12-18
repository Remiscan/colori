<?php
require_once '../dist/colori.php';
use colori\Couleur as Couleur;



function colorToObject($color) {
  return [
    'r' => $color->r(),
    'g' => $color->g(),
    'b' => $color->b(),
    'a' => $color->a()
  ];
}

class Test {
  const TOLERANCE = .03;
  const DISTANCE_PRECISE = 1;
  const DISTANCE_CLOSE = 5;

  function __construct($func = null, $expected = null, $nophp = false, $order = 0) {
    // Convert JavaScript test to PHP test
    $exGetters = ['name', 'exactName', 'closestName', 'hexa', 'hex', 'rgba', 'rgb', 'hsla', 'hsl', 'hwba', 'hwb', 'laba', 'lab', 'lcha', 'lch', 'luminance', 'values'];
    $f = str_replace(
      array_map(function($x) { return '.' . $x; }, $exGetters), 
      array_map(function($x) { return '->' . $x . '()'; }, $exGetters),
      $func
    );
    $methods = ['blend', 'contrast', 'bestColorScheme', 'improveContrast', 'change', 'replace', 'scale', 'complement', 'invert', 'negative', 'greyscale', 'grayscale', 'sepia', 'interpolate', 'distance', 'same', 'unblend', 'whatToBlend', 'toGamut', 'toString', 'inGamut', 'toGamut'];
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

    $this->function = $f;
    $this->expectedResult = $expected;
    $this->actualResult = null;
    $this->nophp = $nophp;
    $this->order = $order;
    $this->tested = false;
    $this->time = null;
  }

  public function result() {
    $time = microtime(true); // in case the inner test fails
    try {
      if ($this->tested !== true) {
        $time = microtime(true);
        $this->actualResult = eval("use colori\Couleur as Couleur; return " . $this->function . ";");
        $this->time = microtime(true) - $time;
        $this->tested = true;
      }
      return $this->actualResult;
    }
    catch (Throwable $error) {
      $this->time = microtime(true) - $time;
      return ['Error', $error->getMessage()];
    }
  }

  public function name() {
    return $this->function . ';';
  }


  // Checks if the test results fit the function results
  public function validate() {
    $result = $this->result();
    $isError = (is_array($result) && count($result) > 0 && $result[0] === 'Error');

    // If result is an error, check if we function one
    if ($isError === true)
      return $this->expectedResult === 'Error';

    // If result is an array
    elseif (is_array($result)) {
      $res = false;
      try {
        // If the array contains colors / color strings, check if they're all the same
        $res = true;
        foreach($result as $k => $c) {
          $res = $res && (Couleur::same($c, $this->expectedResult[$k], self::DISTANCE_CLOSE));
        }
      } catch (Throwable $e) {
        // If not, just compare them
        $res = true;
        foreach($result as $k => $e) {
          $res = $res && ($e === $this->expectedResult[$k]);
        }
      }
      return $res;
    }
      
    // If function result is an object, check if it has the same properties and values as the result
    elseif (is_a($this->expectedResult, 'stdClass') && $this->expectedResult !== null)
      return self::sameColorObject($result, $this->expectedResult);

    // If function result is a number, check if it's close enough to the result
    elseif (is_float($this->expectedResult) || is_int($this->expectedResult)) {
      if (abs($result - $this->expectedResult) > self::TOLERANCE) return false;
      else return true;
    }

    // Else, try to make colors from the result and function result and check if they're the same
    else {
      $res = false;
      try { $res = Couleur::same($result, $this->expectedResult); }
      catch (Throwable $error) { $res = $result === $this->expectedResult; }
      return $res;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static public function sameColorObject($couleur1, $couleur2) {
    $c1 = ($couleur1 instanceof Couleur) ? colorToObject($couleur1) : [$couleur1->r, $couleur1->g, $couleur1->b, $couleur1->a];
    $c2 = ($couleur2 instanceof Couleur) ? colorToObject($couleur2) : [$couleur2->r, $couleur2->g, $couleur2->b, $couleur2->a];
    return Couleur::same($c1, $c2, self::DISTANCE_CLOSE);
  }


  // Display the results of the test on the page
  public function populate() {
    $validation = $this->validate();
    $result = $this->result();
    $displayedResult = $result;
    if ($result instanceof Couleur) $displayedResult = colorToObject($result);
    else if (is_array($result)) {
      $temp = [];
      foreach ($result as $v) {
        if ($v instanceof Couleur) $temp[] = colorToObject($v);
        else { $temp = false; break; }
      }
      if ($temp) $displayedResult = $temp;
    }

    $isError = is_array($result) && count($result) > 0 && $result[0] === 'Error';
    if ($isError) $result[1] = htmlspecialchars($result[1]);
    $title = $this->name();
    $time = $this->time * 1000;

    $class = $validation ? 'yes' : 'no';
    $validationText = $validation ? '✅ Success' : '❌ Failure';
    $row = $this->order;

    $displayedResult = "\n\n".json_encode($displayedResult, JSON_PRETTY_PRINT);
    $expectedResult = "\n\n".json_encode($this->expectedResult, JSON_PRETTY_PRINT);
    
    $backgroundColor = '';
    $textColor = ''; $gradient = '';
    try {
      if (is_array($result) && !$isError) {
        if (count($result) > 1) $gradient = 'linear-gradient(to right, ' . (implode(', ', array_map(function($c) { return (new Couleur($c))->rgb(); }, $result))) . ')';
        if (count($result) > 0) $backgroundColor = new Couleur($result[0]);
      } else {
        $backgroundColor = new Couleur($result);
      }
    }
    catch (Throwable $error) {}
    
    $textColor = ($backgroundColor instanceof Couleur) ? (
                   Couleur::blend('white', $backgroundColor)->bestColorScheme() === 'light' ? 'black' : 'white'
                 ): 'initial';
    $backgroundColor = ($backgroundColor instanceof Couleur) ? $backgroundColor->rgb() : $backgroundColor;
    $gradientStyle = $gradient ? "--gradient:$gradient" : "";

    echo <<<DIV
    <div class="php $class" style="grid-row: $row" data-validate="$validation">
      <a id="php-$row"></a>
      <h4 class="php" style="--color:$backgroundColor; $gradientStyle; color:$textColor;">$title</h4>
      <span class="php">$validationText in $time ms</span>
      <pre class="php">Result: $displayedResult</pre>
      <pre class="php"">Expected: $expectedResult</pre>
    </div>
    DIV;

    return $validation;
  }
}