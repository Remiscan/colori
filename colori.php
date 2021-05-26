<?php
class Couleur
{
  public const COULEURS_NOMMEES = array(
    'transparent' => '00000000',
    'aliceblue' => 'f0f8ff',
    'antiquewhite' => 'faebd7',
    'aqua' => '00ffff',
    'aquamarine' => '7fffd4',
    'azure' => 'f0ffff',
    'beige' => 'f5f5dc',
    'bisque' => 'ffe4c4',
    'black' => '000000',
    'blanchedalmond' => 'ffebcd',
    'blue' => '0000ff',
    'blueviolet' => '8a2be2',
    'brown' => 'a52a2a',
    'burlywood' => 'deb887',
    'cadetblue' => '5f9ea0',
    'chartreuse' => '7fff00',
    'chocolate' => 'd2691e',
    'coral' => 'ff7f50',
    'cornflowerblue' => '6495ed',
    'cornsilk' => 'fff8dc',
    'crimson' => 'dc143c',
    'cyan' => '00ffff',
    'darkblue' => '00008b',
    'darkcyan' => '008b8b',
    'darkgoldenrod' => 'b8860b',
    'darkgray' => 'a9a9a9',
    'darkgrey' => 'a9a9a9',
    'darkgreen' => '006400',
    'darkkhaki' => 'bdb76b',
    'darkmagenta' => '8b008b',
    'darkolivegreen' => '556b2f',
    'darkorange' => 'ff8c00',
    'darkorchid' => '9932cc',
    'darkred' => '8b0000',
    'darksalmon' => 'e9967a',
    'darkseagreen' => '8fbc8f',
    'darkslateblue' => '483d8b',
    'darkslategray' => '2f4f4f',
    'darkslategrey' => '2f4f4f',
    'darkturquoise' => '00ced1',
    'darkviolet' => '9400d3',
    'deeppink' => 'ff1493',
    'deepskyblue' => '00bfff',
    'dimgray' => '696969',
    'dimgrey' => '696969',
    'dodgerblue' => '1e90ff',
    'firebrick' => 'b22222',
    'floralwhite' => 'fffaf0',
    'forestgreen' => '228b22',
    'fuchsia' => 'ff00ff',
    'gainsboro' => 'dcdcdc',
    'ghostwhite' => 'f8f8ff',
    'gold' => 'ffd700',
    'goldenrod' => 'daa520',
    'gray' => '808080',
    'grey' => '808080',
    'green' => '008000',
    'greenyellow' => 'adff2f',
    'honeydew' => 'f0fff0',
    'hotpink' => 'ff69b4',
    'indianred' => 'cd5c5c',
    'indigo' => '4b0082',
    'ivory' => 'fffff0',
    'khaki' => 'f0e68c',
    'lavender' => 'e6e6fa',
    'lavenderblush' => 'fff0f5',
    'lawngreen' => '7cfc00',
    'lemonchiffon' => 'fffacd',
    'lightblue' => 'add8e6',
    'lightcoral' => 'f08080',
    'lightcyan' => 'e0ffff',
    'lightgoldenrodyellow' => 'fafad2',
    'lightgray' => 'd3d3d3',
    'lightgrey' => 'd3d3d3',
    'lightgreen' => '90ee90',
    'lightpink' => 'ffb6c1',
    'lightsalmon' => 'ffa07a',
    'lightseagreen' => '20b2aa',
    'lightskyblue' => '87cefa',
    'lightslategray' => '778899',
    'lightslategrey' => '778899',
    'lightsteelblue' => 'b0c4de',
    'lightyellow' => 'ffffe0',
    'lime' => '00ff00',
    'limegreen' => '32cd32',
    'linen' => 'faf0e6',
    'magenta' => 'ff00ff',
    'maroon' => '800000',
    'mediumaquamarine' => '66cdaa',
    'mediumblue' => '0000cd',
    'mediumorchid' => 'ba55d3',
    'mediumpurple' => '9370d8',
    'mediumseagreen' => '3cb371',
    'mediumslateblue' => '7b68ee',
    'mediumspringgreen' => '00fa9a',
    'mediumturquoise' => '48d1cc',
    'mediumvioletred' => 'c71585',
    'midnightblue' => '191970',
    'mintcream' => 'f5fffa',
    'mistyrose' => 'ffe4e1',
    'moccasin' => 'ffe4b5',
    'navajowhite' => 'ffdead',
    'navy' => '000080',
    'oldlace' => 'fdf5e6',
    'olive' => '808000',
    'olivedrab' => '6b8e23',
    'orange' => 'ffa500',
    'orangered' => 'ff4500',
    'orchid' => 'da70d6',
    'palegoldenrod' => 'eee8aa',
    'palegreen' => '98fb98',
    'paleturquoise' => 'afeeee',
    'palevioletred' => 'd87093',
    'papayawhip' => 'ffefd5',
    'peachpuff' => 'ffdab9',
    'peru' => 'cd853f',
    'pink' => 'ffc0cb',
    'plum' => 'dda0dd',
    'powderblue' => 'b0e0e6',
    'purple' => '800080',
    'rebeccapurple' => '663399',
    'red' => 'ff0000',
    'rosybrown' => 'bc8f8f',
    'royalblue' => '4169e1',
    'saddlebrown' => '8b4513',
    'salmon' => 'fa8072',
    'sandybrown' => 'f4a460',
    'seagreen' => '2e8b57',
    'seashell' => 'fff5ee',
    'sienna' => 'a0522d',
    'silver' => 'c0c0c0',
    'skyblue' => '87ceeb',
    'slateblue' => '6a5acd',
    'slategray' => '708090',
    'slategrey' => '708090',
    'snow' => 'fffafa',
    'springgreen' => '00ff7f',
    'steelblue' => '4682b4',
    'tan' => 'd2b48c',
    'teal' => '008080',
    'thistle' => 'd8bfd8',
    'tomato' => 'ff6347',
    'turquoise' => '40e0d0',
    'violet' => 'ee82ee',
    'wheat' => 'f5deb3',
    'white' => 'ffffff',
    'whitesmoke' => 'f5f5f5',
    'yellow' => 'ffff00',
    'yellowgreen' => '9acd32'
  );

  // Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
  private const vNum = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?'; // number (r, g, b)
  private const vPer = self::vNum . '%'; // percent (r, g, b, s, l, w, bk)
  private const vNP = self::vNum . '%?'; // number or percent (a)
  private const vAng = self::vNum . '(?:deg|grad|rad|turn)?'; // angle (h)

  private const FORMATS = array(
    array(
      'id' => 'HEX',
      'syntaxes' => array(
        // #abc or #ABC
        '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
        // #aabbcc or #AABBCC
        '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
      )
    ), array(
      'id' => 'HEXA',
      'syntaxes' => array(
        // #abcd or #ABCD
        '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
        // #aabbccdd or #AABBCCDD
        '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
      )
    ), array(
      'id' => 'RGB',
      'syntaxes' => array(
        // rgb(255, 255, 255) or rgb(255,255,255)
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.')\\)$/',
        // rgb(100%, 100%, 100%) or rgb(100%,100%,100%)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // rgb(255 255 255)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.')\\)$/',
        // rgb(100% 100% 100%)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'RGBA',
      'syntaxes' => array(
        // rgba(255, 255, 255, .5) or rgba(255, 255, 255, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNP.')\\)$/',
        // rgba(100%, 100%, 100%, .5) or rgba(100%, 100%, 100%, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.') ?\\/ ?('.self::vNP.')\\)$/',
        // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'HSL',
      'syntaxes' => array(
        // hsl(<angle>, 100%, 100%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // hsl(<angle> 100% 100%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'HSLA',
      'syntaxes' => array(
        // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'HWB',
      'syntaxes' => array(
        // hwb(<angle>, 100%, 100%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // hwb(<angle> 100% 100%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'HWBA',
      'syntaxes' => array(
        // hwba(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'LAB',
      'syntaxes' => array(
        // lab(300% 25 40)
        '/^lab\\(('.self::vPer.') ('.self::vNum.') ('.self::vNum.')\\)$/',
        // lab(300% 25 40 / .5)
        '/^lab\\(('.self::vPer.') ('.self::vNum.') ('.self::vNum.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'LCH',
      'syntaxes' => array(
        // lch(300% 25 <angle>)
        '/^lch\\(('.self::vPer.') ('.self::vNum.') ('.self::vAng.')\\)$/',
        // lch(300% 25 <angle> / .5)
        '/^lch\\(('.self::vPer.') ('.self::vNum.') ('.self::vAng.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'NAME',
      'syntaxes' => array(
        // white or WHITE or WhiTe
        '/^[A-Za-z]+$/'
      )
    )
  );

  private const TOLERANCE = .02;
  
  public $r;
  public $g;
  public $b;
  public $h;
  public $s;
  public $l;
  public $w;
  public $bk;
  public $a;
  public $ciel;
  public $ciea;
  public $cieb;
  public $ciec;
  public $cieh;
  
  function __construct($couleur)
  {
    if ($couleur instanceof self)
      throw new Exception('Already an instance of ' . __CLASS__);
    else if (!is_string($couleur))
      throw new Exception(__CLASS__ . ' objects can only be created from a String ; this is not one: ' . $couleur);

    $format = self::matchSyntax(trim($couleur));

    switch ($format['id']) {
      case 'HEX':
      case 'HEXA':
        $this->setHex([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 'ff']);
        break;
      case 'RGB':
      case 'RGBA':
        $this->setRgb([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
        break;
      case 'HSL':
      case 'HSLA':
        $this->setHsl([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
        break;
      case 'HWB':
      case 'HWBA':
        $this->setHwb([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
        break;
      case 'LAB':
        $this->setLab([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
        break;
      case 'LCH':
        $this->setLch([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
        break;
      default:
      throw new Exception($couleur . 'is not a valid color format');
    }
  }


  ////////////////////////////////////////////////////////////////////
  // Gets the names of the properties of a color used in a certain format
  private static function propertiesOf($format) {
    switch($format) {
      case 'rgb': return ['r', 'g', 'b'];
      case 'hsl': return ['h', 's', 'l'];
      case 'hwb': return ['h', 'w', 'bk'];
      case 'lab': return ['ciel', 'ciea', 'cieb'];
      case 'lch': return ['ciel', 'ciec', 'cieh'];
      default: return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
    }
  }


  //////////////////////////////////////////////////////
  // Matches the user input with supported color formats
  private static function matchSyntax($couleur) {
    $tri = substr($couleur, 0, 3);
    if (substr($tri, 0, 1) == '#')
      $formats = [self::FORMATS[0], self::FORMATS[1]];
    elseif ($tri == 'rgb')
      $formats = [self::FORMATS[2], self::FORMATS[3]];
    elseif ($tri == 'hsl')
      $formats = [self::FORMATS[4], self::FORMATS[5]];
    elseif ($tri == 'hwb')
      $formats = [self::FORMATS[6], self::FORMATS[7]];
    elseif ($tri == 'lab')
      $formats = [self::FORMATS[8]];
    elseif ($tri == 'lch')
      $formats = [self::FORMATS[9]];
    else
      $formats = [self::FORMATS[10]];

    foreach($formats as $format) {
      foreach($format['syntaxes'] as $k => $syntaxe) {
        $matches = array();
        $_resultat = preg_match($syntaxe, $couleur, $matches);
        if ($_resultat === 1 && $matches[0] === $couleur) {
          if ($format['id'] == 'NAME' && array_key_exists(strtolower($couleur), self::COULEURS_NOMMEES)) {
            return self::matchSyntax('#' . self::COULEURS_NOMMEES[strtolower($couleur)]);
          }
          return $resultat = array (
            'id' => $format['id'],
            'syntaxe' => $k,
            'data' => $matches
          );
        }
      }
    }

    throw new Exception($couleur . 'is not a valid color format');
  }


  ////////////////////////////////////////////////////////////////////
  // Calculates all the color properties from the already defined ones
  private function compute($from = null) {
    if ($from == 'rgb') {
      $this->rgb2hsl();
      $this->hsl2hwb();
      $this->rgb2lab();
      $this->lab2lch();
    } else if ($from == 'hsl') {
      $this->hsl2rgb();
      $this->hsl2hwb();
      $this->rgb2lab();
      $this->lab2lch();
    } else if ($from == 'hwb') {
      $this->hwb2hsl();
      $this->hsl2rgb();
      $this->rgb2lab();
      $this->lab2lch();
    } else if ($from == 'lab') {
      $this->lab2lch();
      $this->lch2rgb();
      $this->rgb2hsl();
      $this->hsl2hwb();
    } else if ($from == 'lch') {
      $this->lch2lab();
      $this->lch2rgb();
      $this->rgb2hsl();
      $this->hsl2hwb();
    }
  }

  // Returns a float precise to the nth decimal
  private static function pRound($_x, $n = 5) {
    $x = (float) $_x;
    $intDigits = ($x !== 0) ? floor(log10($x > 0 ? $x : -1 * $x) + 1) : 1;
    $precision = (int) ($n - $intDigits);
    return round($x, $precision);
  }


  //////////////////////////////////////////////////////////////////////////////
  // Checks if a variable is a Couleur object, or if it can be made into one
  private static function check($color) {
    if ($color instanceof self) return $color;
    try { return new self($color); }
    catch (Exception $error) {
      throw new Exception('Argument should be an instance of the ' . __CLASS__ . ' class, or a valid color string ; this isn\'t: ' . $color);
    }
  }


  //////////////////////////////////////////////////////////////////////////////
  // Parses a number / percentage / angle into the correct format to store it
  private static function parse($n, $type = null, $clamp = true) {
    // Alpha values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 1]
    // to [0, 1]
    if ($type == 'a') {
      // If n is a percentage
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        if ($clamp) return max(0, min(floatval($n) / 100, 1));
        else        return floatval($n) / 100;
      }
      // If n is a number
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        if ($clamp) return max(0, min($n, 1));
        else        return floatval($n);
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // Red, green, blue values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 255]
    // to [0, 1]
    elseif (in_array($type, ['r', 'g', 'b'])) {
      // If n is a percentage
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        if ($clamp) return max(0, min(floatval($n) / 100, 1));
        else        return floatval($n) / 100;
      }
      // If n is a number
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        if ($clamp) return max(0, min($n / 255, 1));
        else        return $n / 255;
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // Hue and CIE hue values:
    // from any angle or any number
    // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
    // to [0, 1]
    elseif (in_array($type, ['h', 'cieh'])) {
      $_n = floatval($n);
      // If n is a number
      if (preg_match('/^' . self::vNum . '$/', $n)) {
        while ($_n < 0) $_n += 360;
        while ($_n > 360) $_n -= 360;
        return $_n / 360;
      }
      // If n is an angle
      elseif (preg_match('/^' . self::vAng . '$/', $n)) {
        if (substr($n, -3) == 'deg') {
          while ($_n < 0) $_n += 360;
          while ($_n > 360) $_n -= 360;
          return $_n / 360;
        } elseif (substr($n, -3) == 'grad') {
          while ($_n < 0) $_n += 400;
          while ($_n > 400) $_n -= 400;
          return $_n / 400;
        } elseif (substr($n, -3) == 'rad') {
          $_n = $_n * 180 / pi();
          while ($_n < 0) $_n += 360;
          while ($_n > 360) $_n -= 360;
          return $_n / 360;
        } elseif (substr($n, -3) == 'turn') {
          while ($_n < 0) $_n += 1;
          while ($_n > 1) $_n -= 1;
          return $_n;
        } else throw new Exception("Invalid angle value: $n");
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // Percentage values:
    // from any %
    // clamped to [0, 100]%
    // to [0, 1]
    elseif (in_array($type, ['s', 'l', 'w', 'bk', 'ciel'])) {
      // If n is a percentage
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        if ($clamp) return max(0, min(floatval($n) / 100, 1));
        else        return floatval($n) / 100;
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // CIE axes values:
    // any number
    elseif (in_array($type, ['ciea', 'cieb'])) {
      // If n is a number
      if (preg_match('/^' . self::vNum . '$/', $n)) {
        return floatval($n);
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // CIE chroma values:
    // from any number
    // clamped to [0, +Inf[
    elseif ($type == 'ciec') {
      // If n is a number
      if (preg_match('/^' . self::vNum . '$/', $n)) {
        if ($clamp) return max(0, $n);
        else        return floatval($n);
      }
      else throw new Exception("Invalid $type value: $n");
    }

    // Arbitrary values
    // from any % or any number
    // to any number (so that 0% becomes 0 and 100% becomes 1)
    else {
      // If n is a percentage
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        return floatval($n) / 100;
      }
      // If n is a number
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        return floatval($n);
      }
      else throw new Exception("Invalid arbitrary value: $n");
    }
  }

  



  /*****************************************/
  /* Setters and getters for color formats */
  /*****************************************/


  /* GENERAL SETTER */

  // Will be used by other setters
  private function set($data, $props, $format) {
    for ($i = 0; $i < count($props); $i++) {
      $this->{$props[$i]} = self::pRound(self::parse($data[$i], $props[$i]));
    }
    $this->a = self::pRound(self::parse($data[3] ?? 1, 'a'));
    $this->compute($format);
  }


  /* NAME */

  public function name() {
    $name = $this->exactName();
    if ($name === null && $this->a == 1) {
      foreach(self::COULEURS_NOMMEES as $name => $hex) {
        if (self::same($this, '#'.$hex)) return $name;
      }
    }
    return $name;
  }

  public function exactName() {
    if ($this->a == 1) {
      $hex6 = substr($this->hex(), 1);
      $name = array_search($hex6, self::COULEURS_NOMMEES);
      return $name ? $name : null;
    }
    elseif ($this->a == 0)  return 'transparent';
    else                    return null;
  }


  /* RGB (hexadecimal) */

  // Adds a zero before a string of length 1
  private static function pad($s) {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  public function setHex($hexa) {
    $r = $hexa[0];
    $r = (strlen($r) == 1) ? $r.$r : $r;
    $r = intval(hexdec($r));

    $g = $hexa[1];
    $g = (strlen($g) == 1) ? $g.$g : $g;
    $g = intval(hexdec($g));

    $b = $hexa[2];
    $b = (strlen($b) == 1) ? $b.$b : $b;
    $b = intval(hexdec($b));

    $a = $hexa[3] ?? 'ff';
    $a = (strlen($a) == 1) ? $a.$a : $a;
    $a = intval(hexdec($a)) / 255;

    $this->setRgb([$r, $g, $b, $a]);
  }

  public function hex() {
    if ($this->a < 1)
      return $this->hexa();
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    return '#'.$r.$g.$b;
  }

  public function hexa() {
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    $a = self::pad(dechex(round($this->a * 255)));
    return '#'.$r.$g.$b.$a;
  }


  /* RGB (functional) */

  public function setRgb($rgba) {
    $this->set($rgba, ['r', 'g', 'b'], 'rgb');
  }

  public function rgb() {  
    $r = round($this->r * 255);
    $g = round($this->g * 255);
    $b = round($this->b * 255);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return 'rgb('.$r.', '.$g.', '.$b.', '.$a.')';
    else
      return 'rgb('.$r.', '.$g.', '.$b.')';
  }

  public function rgba() {  
    $r = round($this->r * 255);
    $g = round($this->g * 255);
    $b = round($this->b * 255);
    $a = round($this->a * 100) / 100;
    return 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
  }


  /* HSL */

  public function setHsl($hsla) {
    $this->set($hsla, ['h', 's', 'l'], 'hsl');
  }

  public function hsl() {  
    $h = round($this->h * 360);
    $s = round($this->s * 100);
    $l = round($this->l * 100);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return 'hsl('.$h.', '.$s.'%, '.$l.'%, '.$a.')';
    else
      return 'hsl('.$h.', '.$s.'%, '.$l.'%)';
  }

  public function hsla() {  
    $h = round($this->h * 360);
    $s = round($this->s * 100);
    $l = round($this->l * 100);
    $a = round($this->a * 100) / 100;
    return 'hsla('.$h.', '.$s.'%, '.$l.'%, '.$a.')';
  }


  /* HWB */

  public function setHwb($hwba) {
    $this->set($hwba, ['h', 'w', 'b'], 'hwb');
  }

  public function hwb() {
    $h = round($this->h * 360);
    $w = round($this->w * 100);
    $bk = round($this->bk * 100);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return 'hwb('.$h.' '.$w.'% '.$bk.'% / '.$a.')';
    else
      return 'hwb('.$h.' '.$w.'% '.$bk.'%)';
  }

  public function hwba() {
    $h = round($this->h * 360);
    $w = round($this->w * 100);
    $bk = round($this->bk * 100);
    $a = round($this->a * 100) / 100;
    return 'hwb('.$h.' '.$w.'% '.$bk.'% / '.$a.')';
  }


  /* LAB */

  public function setLab($laba) {
    $this->set($laba, ['ciel', 'ciea', 'cieb'], 'lab');
  }

  public function lab() {
    $ciel = round($this->ciel * 100);
    $ciea = round($this->ciea);
    $cieb = round($this->cieb);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return "lab(${ciel}% ${ciea} ${cieb} / ${a})";
    else
      return "lab(${ciel}% ${ciea} ${cieb})";
  }

  public function laba() {
    $ciel = round($this->ciel * 100);
    $ciea = round($this->ciea);
    $cieb = round($this->cieb);
    $a = round($this->a * 100) / 100;
    return "lab(${ciel}% ${ciea} ${cieb} / ${a})";
  }


  /* LCH */

  public function setLch($lcha) {
    $this->set($lcha, ['ciel', 'ciec', 'cieh'], 'lch');
  }

  public function lch() {
    $ciel = round($this->ciel * 100);
    $ciec = round($this->ciec);
    $cieh = round($this->cieh * 360);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return "lch(${ciel}% ${ciec} ${cieh} / ${a})";
    else
      return "lch(${ciel}% ${ciec} ${cieh})";
  }

  public function lcha() {
    $ciel = round($this->ciel * 100);
    $ciec = round($this->ciec);
    $cieh = round($this->cieh * 360);
    $a = round($this->a * 100) / 100;
    if ($this->a < 1)
      return "lch(${ciel}% ${ciec} ${cieh} / ${a})";
    else
      return "lch(${ciel}% ${ciec} ${cieh})";
  }



  /************************************/
  /* Conversion between color formats */
  /************************************/

  private function rgb2hsl() {
    $r = $this->r;
    $g = $this->g;
    $b = $this->b;
    
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;
    
    // Luminosity (l)
    $l = ($max + $min) / 2;
    
    // If chroma == 0, the color is grey
    if ($chroma == 0)
      $h = 0;
    // If not, we calculate the hue h
    // (source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
    else
    {
      switch($max) {
        case $r:
          $h = ($g - $b) / $chroma;
          break;
        case $g:
          $h = ($b - $r) / $chroma + 2;
          break;
        case $b:
          $h = ($r - $g) / $chroma + 4;
          break;
      }
      $h = 60 * $h;
      if ($h < 0)
        $h += 360;
    }

    if ($l == 0 || $l == 1)
      $s = 0;
    elseif ($l <= 0.5)
      $s = $chroma / (2 * $l);
    else
      $s = $chroma / (2 - 2 * $l);
    
    $this->h = self::pRound($h / 360);
    $this->s = self::pRound($s);
    $this->l = self::pRound($l);
  }


  private function hsl2rgb() {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    $h = $this->h * 360;
    $s = $this->s;
    $l = $this->l;

    $m = $s * min($l, 1 - $l);

    $arr = [0, 8, 4];
    for ($i = 0; $i <= 2; $i++) {
      $k = fmod($arr[$i] + $h / 30, 12);
      $arr[$i] = $l - $m * max(min($k - 3, 9 - $k, 1), -1);
    }
    
    $r = $arr[0];
    $g = $arr[1];
    $b = $arr[2];
    
    $this->r = self::pRound($r);
    $this->g = self::pRound($g);
    $this->b = self::pRound($b);
  }


  private function hsl2hwb() {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    $s = $this->s;
    $l = $this->l;

    $v = $l + $s * min($l, 1 - $l);
    if ($v == 0)
      $_s = 0;
    else
      $_s = 2 - 2 * $l / $v;

    $w = (1 - $_s) * $v;
    $bk = 1 - $v;

    $this->w = self::pRound($w);
    $this->bk = self::pRound($bk);
  }


  private function hwb2hsl() {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    $w = $this->w;
    $bk = $this->bk;
    $_w = $w; $_bk = $bk;

    if ($w + $bk > 1) {
      $_w = $w / ($w + $bk);
      $_bk = $bk / ($w + $bk);
    }

    $v = 1 - $_bk;
    if ($_bk == 1)
      $_s = 0;
    else
      $_s = 1 - $_w / $v;

    $l = $v - $v * $_s / 2;
    if ($l == 0 || $l == 1)
      $s = 0;
    else
      $s = ($v - $l) / min($l, 1 - $l);

    $this->s = self::pRound($s);
    $this->l = self::pRound($l);
  }


  private function rgb2lab() {
    // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    $linRGB = function($x) {
      $sign = ($x < 0) ? -1 : 1;
      return (abs($x) < 0.04045) ? ($x / 12.92) : $sign * (($x + 0.055) / 1.055) ** 2.4;
    };
    $r = $linRGB($this->r);
    $g = $linRGB($this->g);
    $b = $linRGB($this->b);

    $x = 0.41239079926595934 * $r + 0.357584339383878 * $g + 0.1804807884018343 * $b;
    $y = 0.21263900587151027 * $r + 0.715168678767756 * $g + 0.07219231536073371 * $b;
    $z = 0.01933081871559182 * $r + 0.11919477979462598 * $g + 0.9505321522496607 * $b;

    $x50 = 1.0479298208405488 * $x + 0.022946793341019088 * $y - 0.05019222954313557 * $z;
    $y50 = 0.029627815688159344 * $x + 0.990434484573249 * $y - 0.01707382502938514 * $z;
    $z50 = -0.009243058152591178 * $x + 0.015055144896577895 * $y + 0.7518742899580008 * $z;

    $w = [0.96422, 1, 0.82521];

    $x = $x50 / $w[0];
    $y = $y50 / $w[1];
    $z = $z50 / $w[2];

    $f = function($x) { $ε = 216/24389; $κ = 24389/27; return ($x > $ε) ? $x ** (1/3) : ($κ * $x + 16) / 116; };

    $ciel = (116 * $f($y) - 16) / 100;
    $ciea = 500 * ($f($x) - $f($y));
    $cieb = 200 * ($f($y) - $f($z));

    $this->ciel = self::pRound($ciel);
    $this->ciea = self::pRound($ciea);
    $this->cieb = self::pRound($cieb);
  }


  private function lch2rgb() {
    // Source of the math: https://css.land/lch/lch.js
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    $conversion = function($ciel, $ciec, $cieh) {
      $ciea = $ciec * cos($cieh * pi() / 180);
      $cieb = $ciec * sin($cieh * pi() / 180);

      $ε = 216/24389;
      $κ = 24389/27;
      $w = [0.96422, 1, 0.82521];

      $f1 = ($ciel + 16) / 116;
      $f0 = $ciea / 500 + $f1;
      $f2 = $f1 - $cieb / 200;

      $x50 = ($f0 ** 3 > $ε) ? $f0 ** 3 : (116 * $f0 - 16) / $κ;
      $y50 = ($ciel > $κ * $ε) ? (($ciel + 16) / 116) ** 3 : $ciel / $κ;
      $z50 = ($f2 ** 3 > $ε) ? $f2 ** 3 : (116 * $f2 - 16) / $κ;

      $x50 = $x50 * $w[0];
      $y50 = $y50 * $w[1];
      $z50 = $z50 * $w[2];

      $x = 0.9554734527042182 * $x50 - 0.023098536874261423 * $y50 + 0.0632593086610217 * $z50;
      $y = -0.028369706963208136 * $x50 + 1.0099954580058226 * $y50 + 0.021041398966943008 * $z50;
      $z = 0.012314001688319899 * $x50 - 0.020507696433477912 * $y50 + 1.3303659366080753 * $z50;

      $r = 3.2409699419045226 * $x - 1.537383177570094 * $y - 0.4986107602930034 * $z;
      $g = -0.9692436362808796 * $x + 1.8759675015077202 * $y + 0.04155505740717559 * $z;
      $b = 0.05563007969699366 * $x - 0.20397695888897652 * $y + 1.0569715142428786 * $z;

      $gamRGB = function($x) {
        $sign = ($x < 0) ? -1 : 1;
        return (abs($x) > 0.0031308) ? $sign * (1.055 * pow($x, 1 / 2.4) - 0.055) : 12.92 * $x;
      };

      $r = $gamRGB($r);
      $g = $gamRGB($g);
      $b = $gamRGB($b);

      return [$r, $g, $b];
    };

    $forceIntoGamut = function($ciel, $ciec, $cieh) use ($conversion) {
      $condition = function($l, $c, $h) use ($conversion) {
        $array = $conversion($l, $c, $h);
        return array_reduce($array, function($sum, $x) {
          $ε1 = .000005;
          return ($sum && $x >= (-1 * $ε1) && $x <= (1 + $ε1));
        }, true);
      };

      if ($condition($ciel, $ciec, $cieh)) return [$ciel, $ciec, $cieh];

      $ε2 = .0001;
      $Cmin = 0;
      $Cmax = $ciec;
      $_ciec = $ciec / 2;

      while ($Cmax - $Cmin > $ε2) {
        if ($condition($ciel, $_ciec, $cieh)) $Cmin = $_ciec;
        else $Cmax = $_ciec;
        $_ciec = ($Cmin + $Cmax) / 2;
      }

      return [$ciel, $_ciec, $cieh];
    };

    $lch = $forceIntoGamut($this->ciel * 100, $this->ciec, $this->cieh * 360);
    $rgb = $conversion(...$lch);

    $this->r = self::pRound($rgb[0]);
    $this->g = self::pRound($rgb[1]);
    $this->b = self::pRound($rgb[2]);
  }


  private function lab2lch() {
    $ciec = sqrt($this->ciea ** 2 + $this->cieb ** 2);
    $cieh = self::parse(atan2($this->cieb, $this->ciea) * 180 / pi(), 'cieh');

    $this->ciec = self::pRound($ciec);
    $this->cieh = self::pRound($cieh);
  }


  private function lch2lab() {
    $cieh = $this->cieh * 360;
    $ciea = $this->ciec * cos($cieh * pi() / 180);
    $cieb = $this->ciec * sin($cieh * pi() / 180);

    $this->ciea = self::pRound($ciea);
    $this->cieb = self::pRound($cieb);
  }



  /********************************/
  /* Color manipulation functions */
  /********************************/


  ///////////////////////////////////////////////////////
  // Blends colors together, in the order they were given
  public static function blend(...$couleurs)
  {
    if (count($couleurs) < 2) throw new Exception("You need at least 2 colors to blend");
    $background = self::check(array_shift($couleurs));
    $overlay = self::check(array_shift($couleurs));

    if ($overlay->a == 0) {
      $result = $background;
    } else {
      $a = $overlay->a + $background->a * (1 - $overlay->a);
      $r = ($overlay->r * $overlay->a + $background->r * $background->a * (1 - $overlay->a)) / $a;
      $g = ($overlay->g * $overlay->a + $background->g * $background->a * (1 - $overlay->a)) / $a;
      $b = ($overlay->b * $overlay->a + $background->b * $background->a * (1 - $overlay->a)) / $a;
      $result = new self('rgb('. 255 * $r .', '. 255 * $g .', '. 255 * $b .', '. $a .')');
    }

    if (count($couleurs) == 0)  return $result;
    else                        return self::blend($result, ...$couleurs);
  }


  ///////////////////////////////////////////////////////////////////////////////////////
  // Solves the equation result = blend(background, ...overlays) with background unknown.
  // It will:
  // - return a Couleur object if the equation has a solution
  // - return null if the equation has no solution
  // - throw if the equation has an infinite amount of solutions
  public static function unblend(...$couleurs) {
    if (count($couleurs) < 2) throw new Exception("You need at least 2 colors to blend");
    $result = self::check(array_shift($couleurs));
    $overlay = self::check(array_shift($couleurs));

    if ($overlay->a == 1) {
      throw new Exception("Overlay color ". $overlay->rgb() ." isn't transparent, so the background it was blended onto could have been any color");
    }
    elseif ($overlay->a == 0) $background = $result;
    else {
      if ($result->a < $overlay->a) return null;
      elseif ($result->a == $overlay->a) {
        if (self::same($result, $overlay)) $background = new self('transparent');
        else return null;
      }
      else {
        $a = self::pRound(($result->a - $overlay->a) / (1 - $overlay->a), 3);
        $r = self::pRound(($result->r * $result->a - $overlay->r * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        $g = self::pRound(($result->g * $result->a - $overlay->g * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        $b = self::pRound(($result->b * $result->a - $overlay->b * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        foreach([$r, $g, $b] as $x) {
          if ($x < 0 - self::TOLERANCE || $x > 1 + self::TOLERANCE) return null;
        }
        $background = new self('rgb('. 255 * $r .', '. 255 * $g .', '. 255 * $b .', '. $a .')');
      }
    }

    if (count($couleurs) == 0) return $background;
    else return self::unblend($background, ...$couleurs);
  }


  ////////////////////////////////////////////////////////////////////////////////
  // Solves the equation result = blend(background, overlay) with overlay unknown.
  // It will:
  // - return a Couleur object if the equation has one solution
  // - return an array of Couleur objects if the equation has a finite number of solutions
  // - return null if the equation has no solution
  // - throw if the equation has an infinite amount of solutions
  public static function whatToBlend($_couleur1, $_couleur2, $alpha = null, $alphaStep = .1) {
    $background = self::check($_couleur1);
    $result = self::check($_couleur2);

    $calculateSolution = function($a) use ($result, $background) {
      $r = self::pRound(($result->r * $result->a - $background->r * $background->a * (1 - $a)) / $a, 3);
      $g = self::pRound(($result->g * $result->a - $background->g * $background->a * (1 - $a)) / $a, 3);
      $b = self::pRound(($result->b * $result->a - $background->b * $background->a * (1 - $a)) / $a, 3);
      foreach([$r, $g, $b] as $x) {
        if ($x < 0 - self::TOLERANCE || $x > 1 + self::TOLERANCE) throw new Exception("This color doesn't exist");
      }
      return new self('rgb('. 255 * $r .', '. 255 * $g .', '. 255 * $b .', '. $a .')');
    };

    // If alpha is known, we can find at most one solution
    if (is_numeric($alpha) && $alpha >= 0 && $alpha <= 1) {
      if  ($alpha === 0) {
        if (self::same($background, $result)) return new self('transparent');
        else                                  return null;
      }
      elseif ($alpha === 1)                   return $result;
      elseif ($result->a < $alpha)            return null;
      elseif ($result->a == $alpha) {
        if ($background->a > 0)               return null;
        else                                  return $result;
      }
    }

    // If alpha isn't known, we can find at most one solution per possible alpha value
    if ($result->a < $background->a)          return null;
    elseif ($result->a > $background->a) {
      if ($result->a == 1)                    $overlay = $result;
      elseif ($background->a == 0)            $overlay = $result;
      // If 0 < background.a < result.a < 1, we can find a unique solution
      else {
        $a = self::pRound(($result->a - $background->a) / (1 - $background->a), 3);
        if (is_numeric($alpha) && abs($a - $alpha) > self::TOLERANCE) return null;
        try { $overlay = $calculateSolution($a); }
        catch (Exception $error) { return null; }
      }
    }
    elseif ($result->a == $background->a) {
      if (self::same($result, $background)) $overlay = new self('transparent');
      elseif ($background->a < 1)             return null;
      // If both result and background are opaque, there are multiple solutions (one per alpha value).
      // Let's calculate some of them.
      else {
        $solutions = [];
        if (is_numeric($alpha)) {
          try { $overlay = $calculateSolution($alpha); }
          catch (Exception $error) { return null; }
        }
        else {
          for ($a = $alphaStep; $a < 1; $a += $alphaStep) {
            try {
              $solutions[] = $calculateSolution($a);
            } catch (Exception $error) { continue; }
          }
          switch (count($solutions)) {
            case 0: $overlay = null; break;
            case 1: $overlay = $solutions[0]; break;
            default: $overlay = $solutions;
          }
        }
      }
    }

    if (is_numeric($alpha)) return ($overlay->a == $alpha) ? $overlay : null;
    else return $overlay;
  }


  ////////////////////////////////////
  // Computes the luminance of a color
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  public function luminance() {
    if ($this->a < 1)
      throw new Exception('Can\'t calculate the luminance of a transparent color');

    $arr = array(1 * $this->r, 1 * $this->g, 1 * $this->b);
    for ($i = 0; $i <= 2; $i++) {
      $e = $arr[$i];
      if ($e <= 0.03928)
        $e = $e / 12.92;
      else
        $e = pow(($e + 0.055) / 1.055, 2.4);
      $arr[$i] = $e;
    }
    
    return self::pRound(0.2126 * $arr[0] + 0.7152 * $arr[1] + 0.0722 * $arr[2]);
  }


  ///////////////////////////////////////////
  // Computes the contrast between two colors
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  public static function contrast($_couleur1, $_couleur2) {
    $couleur1 = self::check($_couleur1);
    $couleur2 = self::check($_couleur2);
    
    $L1 = $couleur1->luminance();
    $L2 = $couleur2->luminance();
    $Lmax = max($L1, $L2);
    $Lmin = min($L1, $L2);
    return self::pRound(($Lmax + 0.05) / ($Lmin + 0.05));
  }
  

  ////////////////////////////////////////////////////////////////////////////
  // Checks if black or white text would have better contrast with this color
  public function contrastedText() {
    $L = $this->luminance();
    $LB = 1; // luminance of white
    $LN = 0; // luminance of black
    $contrastes = array(
      ($L + 0.05) / ($LN + 0.05), // contrast between this and black
      ($LB + 0.05) / ($L + 0.05)  // contrast between white and this
    );
    if ($contrastes[0] > $contrastes[1])  return 'black'; // if contrast is higher with black
    else                                  return 'white';
  }


  ///////////////////////////////////////////////////////////
  // Modifies the color (without changing its hue) to give it
  // better contrast (= closer to desiredContrast) with referenceColor
  // The options argument supports these properties:
  // - lower: - true will lower the contrast if it's higher than desired
  //          - false will stop if contrast is higher than desired
  // - towards: if desiredContrast can be reached BOTH by raising or
  //            lowering CIE lightness, then this option will be used to
  //            determine which way to go :
  //            - null (default) to choose automatically*
  //            - 'black' to lower CIE lightness
  //            - 'white' to raise CIE lightness
  //      * 'black' will be chosen if movingColor is darker than refColor,
  //      and 'white' if it's lighter than refColor.
  // - maxIterations: the maximum number of times the color will be altered
  public function improveContrast($referenceColor, $desiredContrast, $step = 2, $options = null) {
    if ($options === null) $options = new stdClass();
    if (!isset($options->lower)) $options->lower = false;
    if (!isset($options->maxIterations)) $options->maxIterations = 1000;
    if (!isset($options->towards)) $options->towards = null;

    $movingColor = new self($this->rgb());
    $refColor = self::check($referenceColor);

    // Let's measure the initial contrast
    // and decide if we want it to go up or down.
    $contrast = self::contrast($movingColor, $refColor);
    if ($contrast > $desiredContrast)     $direction = -1;
    elseif ($contrast < $desiredContrast) $direction = 1;
    else                                  $direction = 0;
    if (($direction < 0 && $options->lower === false) || ($direction == 0)) return $this;

    // Let's measure the contrast of refColor with black and white to know if
    // desiredContrast can be reached by blackening or whitening movingColor.
    $contrastWhite = self::contrast($refColor, 'white');
    $contrastBlack = self::contrast($refColor, 'black');
    $towardsWhite = ($direction > 0) ? ($contrastWhite >= $desiredContrast)
                                     : ($contrastWhite <= $desiredContrast);
    $towardsBlack = ($direction > 0) ? ($contrastBlack >= $desiredContrast)
                                     : ($contrastBlack <= $desiredContrast);

    // Let's decide if we're going to raise blackness or whiteness
    // to reach desiredContrast.
    if ($towardsWhite && !$towardsBlack) $towards = 'white';
    elseif ($towardsBlack && !$towardsWhite) $towards = 'black';
    elseif (!$towardsWhite && !$towardsBlack) {
      if ($options->towards !== null) $towards = $options->towards;
      elseif ($contrastWhite > $contrastBlack) return new self('white');
      else                                      return new self('black');
    }
    elseif ($towardsWhite && $towardsBlack) $towards = $options->towards;
    if ($towards === null) {
      if ($refColor->ciel < $movingColor->ciel) $towards = 'white';
      elseif ($refColor->ciel > $movingColor->ciel) $towards = 'black';
      else $towards = 'black';
    }


    // We keep going as long as contrast is still below / over desiredContrast.
    $i = 0;
    while(($direction > 0) ? ($contrast < $desiredContrast) : ($contrast > $desiredContrast) && $i < $options->maxIterations) {
      $i++;

      // If movingColor is totally black (if towards black) or white (if towards white),
      // i.e. there's no way to go, stop.
      if (($towards == 'black' && $movingColor->ciel == 0) || ($towards == 'white' && $movingColor->ciel == 1))
        break;

      // Let's try to raise contrast by increasing or reducing CIE lightness.
      $sign = ($towards == 'white') ? 1 : -1;
      $newColor = new self('lch(' . (100 * $movingColor->ciel + $sign * $step) . '% ' . $movingColor->ciec . ' ' . 360 * $movingColor->cieh . ')');
      $newContrast = self::contrast($newColor, $refColor);

      // If we overshot a little, stop.
      // (We want to overshoot when we're raising contrast, but not when we're lowering it!)
      $overshot = (($direction < 0) && ($contrast > $desiredContrast) && ($newContrast < $desiredContrast));
      if ($overshot) break;

      // We went the right way, let's keep going!
      $contrast = $newContrast;
      $movingColor = $newColor;
    }

    // We're done!
    return $movingColor;
  }


  //////////////////////////////////
  // Changes a property of the color
  public function change($propriete, $valeur, $options = null) {
    if ($options === null) $options = new stdClass();
    $replace = ($options === true) ? true : (isset($options->replace) ? $options->replace : false);
    $scale = isset($options->scale) ? $options->scale : false;
    $val = $scale ? self::parse($valeur) : self::parse($valeur, $propriete, false);
    $changedColor = new self($this->rgb());

    if (in_array($propriete, ['r', 'g', 'b', 'a'])) {
      $rgba = [$this->r, $this->g, $this->b, $this->a];
      switch ($propriete) {
        case 'r': $rgba[0] = $replace ? $val : ($scale ? $this->r * $val : $this->r + $val); break;
        case 'g': $rgba[1] = $replace ? $val : ($scale ? $this->g * $val : $this->g + $val); break;
        case 'b': $rgba[2] = $replace ? $val : ($scale ? $this->b * $val : $this->b + $val); break;
        case 'a': $rgba[3] = $replace ? $val : ($scale ? $this->a * $val : $this->a + $val); break;
      }
      $changedColor->setRgb([255 * $rgba[0], 255 * $rgba[1], 255 * $rgba[2], $rgba[3]]);
    } elseif (in_array($propriete, ['h', 's', 'l'])) {
      $hsla = [$this->h, $this->s, $this->l, $this->a];
      switch ($propriete) {
        case 'h': $hsla[0] = $replace ? $val : ($scale ? $this->h * $val : $this->h + $val); break;
        case 's': $hsla[1] = $replace ? $val : ($scale ? $this->s * $val : $this->s + $val); break;
        case 'l': $hsla[2] = $replace ? $val : ($scale ? $this->l * $val : $this->l + $val); break;
      }
      $changedColor->setHsl([360 * $hsla[0], 100 * $hsla[1] . '%', 100 * $hsla[2] . '%', $hsla[3]]);
    } elseif (in_array($propriete, ['w', 'bk'])) {
      $hwba = [$this->h, $this->w, $this->bk, $this->a];
      switch ($propriete) {
        case 'w': $hwba[1] = $replace ? $val : ($scale ? $this->w * $val : $this->w + $val); break;
        case 'bk': $hwba[2] = $replace ? $val : ($scale ? $this->bk * $val : $this->bk + $val); break;
      }
      $changedColor->setHwb([360 * $hwba[0], 100 * $hwba[1] . '%', 100 * $hwba[2] . '%', $hwba[3]]);
    } elseif (in_array($propriete, ['ciel', 'ciea', 'cieb'])) {
      $laba = [$this->ciel, $this->ciea, $this->cieb, $this->a];
      switch ($propriete) {
        case 'ciel': $laba[0] = $replace ? $val : ($scale ? $this->ciel * $val : $this->ciel + $val); break;
        case 'ciea': $laba[1] = $replace ? $val : ($scale ? $this->ciea * $val : $this->ciea + $val); break;
        case 'cieb': $laba[2] = $replace ? $val : ($scale ? $this->cieb * $val : $this->cieb + $val); break;
      }
      $changedColor->setLab([100 * $laba[0] . '%', $laba[1], $laba[2], $laba[3]]);
    } elseif (in_array($propriete, ['ciec', 'cieh'])) {
      $lcha = [$this->ciel, $this->ciec, $this->cieh, $this->a];
      switch ($propriete) {
        case 'ciec': $lcha[1] = $replace ? $val : ($scale ? $this->ciec * $val : $this->ciec + $val); break;
        case 'cieh': $lcha[2] = $replace ? $val : ($scale ? $this->cieh * $val : $this->cieh + $val); break;
      }
      $changedColor->setLch([100 * $lcha[0] . '%', $lcha[1], 360 * $lcha[2], $lcha[3]]);
    }

    return $changedColor;
  }

  // Replaces a property of the color
  public function replace($propriete, $valeur) {
    $options = new stdClass();
    $options->replace = true;
    $options->scale = false;
    return $this->change($propriete, $valeur, $options);
  }

  // Replaces a property of the color by a percentage of its initial value
  public function scale($propriete, $valeur) {
    $options = new stdClass();
    $options->replace = false;
    $options->scale = true;
    return $this->change($propriete, $valeur, $options);
  }

  // Complementary color
  public function complement() { return $this->change('h', 180); }

  // Negative / inverse color
  public function negative() {
    return new self('rgba(' . 255 * (1 - $this->r) . ', ' . 255 * (1 - $this->g) . ', ' . 255 * (1 - $this->b) . ', ' . $this->a . ')');
  }
  public function invert() { return $this->negative(); }

  // options: {scale: true/false}
  public function darken($value, $options = null) {
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (isset($options->scale) ? $options->{'scale'} : true);
    $val = self::parse($value);
    $val = $scale ? ($this->l * (1 - $val)) : ($this->l - $val);
    return $this->replace('l', 100 * $val . '%');
  }

  public function lighten($value, $options = null) {
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (isset($options->scale) ? $options->{'scale'} : true);
    $val = self::parse($value);
    $val = $scale ? ($this->l * (1 + $val)) : ($this->l + $val);
    return $this->replace('l', 100 * $val . '%');
  }

  public function desaturate($value, $options = null) {
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (isset($options->scale) ? $options->{'scale'} : true);
    $val = self::parse($value);
    $val = $scale ? ($this->s * (1 - $val)) : ($this->s - $val);
    return $this->replace('s', 100 * $val . '%');
  }

  public function saturate($value, $options = null) {
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (isset($options->scale) ? $options->{'scale'} : true);
    $val = self::parse($value);
    $val = $scale ? ($this->s * (1 + $val)) : ($this->s + $val);
    return $this->replace('s', 100 * $val . '%');
  }

  public function greyscale() { return $this->desaturate('100%'); }
  public function grayscale() { return $this->greyscale(); }


  /////////////////////////////////////////////////////////////////////////////////////////
  // Computes the values of intermediate colors to make a gradient that avoids "grey zone"
  public static function gradient($_from, $_to, $_steps = 5) {
    $from = self::check($_from);
    $to = self::check($_to);
    $steps = min(max(1, $_steps), 100);

    $intermediateColors = array($from);
    $stepL = ($to->ciel - $from->ciel) / $steps;
    $stepC = ($to->ciec - $from->ciec) / $steps;
    // Minimize the distance to travel through hues
    $stepHup = (360 * ($to->cieh - $from->cieh) % 360 + 360) % 360 / 360;
    $stepHdown = (360 * ($from->cieh - $to->cieh) % 360 + 360) % 360 / 360;
    $stepH = (($stepHup <= $stepHdown) ? $stepHup : (-1 * $stepHdown)) / $steps;

    for ($i = 1; $i < $steps; $i++) {
      $previous = $intermediateColors[$i - 1];
      $L = $previous->ciel + $stepL;
      $C = $previous->ciec + $stepC;
      $H = $previous->cieh + $stepH;

      $next = new self('lch('. $L * 100 .'% '. $C .' '. $H * 360 .')');
      $intermediateColors[] = $next;
      $previous = $next;
    }

    $intermediateColors[] = $to;
    return $intermediateColors;
  }


  //////////////////////////////////////////////////////////////////
  // Calculates the distance between two colors in a certain format,
  // by adding the difference between each of its properties.
  // If no format is given, return the average of the distances for all formats.
  public static function distance($_couleur1, $_couleur2, $format = null, $tolerance = self::TOLERANCE) {
    $couleur1 = self::check($_couleur1);
    $couleur2 = self::check($_couleur2);

    $formats = ['rgb', 'hsl', 'hwb', 'lab', 'lch'];
    if (in_array($format, $formats)) {
      $properties = self::propertiesOf($format);
      $properties[] = 'a';

      // Let's add the difference for each property
      $distance = array_reduce($properties, function($sum, $prop) use ($couleur1, $couleur2, $tolerance) {
        // cieh has no effect when ciec is 0,
        // h and s have no effect when l is 0 or 1,
        // h has no effect when s is 0,
        // h has no effect when bk + w is 1
        if (
          ($prop == 'cieh' && $couleur1->ciec <= $tolerance && $couleur2->ciec <= $tolerance)
          || (in_array($prop, ['s', 'h']) && $couleur1->l >= 1 - $tolerance && $couleur2->l >= 1 - $tolerance)
          || (in_array($prop, ['s', 'h']) && $couleur1->l <= $tolerance && $couleur2->l <= $tolerance)
          || ($prop == 'h' && $couleur1->s <= $tolerance && $couleur2->s <= $tolerance)
          || ($prop == 'h' && $couleur1->bk + $couleur1->w >= 1 - $tolerance && $couleur2->bk + $couleur2->w >= 1 - $tolerance)
        ) return $sum;

        // All properties are between 0 and 1, except ciea and cieb who are roughly 100 times bigger
        $coefficient = in_array($prop, ['ciea', 'cieb', 'ciec']) ? .01 : 1;
        return $sum + $coefficient * abs($couleur1->{$prop} - $couleur2->{$prop});
      }, 0);
      return self::pRound($distance, 3);
    } else {
      return array_reduce($formats,
        function($sum, $format) use ($couleur1, $couleur2) { return $sum + self::distance($couleur1, $couleur2, $format); }, 0
      ) / count($formats);
    }
  }


  ////////////////////////////////////////////////
  // Determines if two colors are almost identical
  public static function same($_couleur1, $_couleur2, $tolerance = self::TOLERANCE) {
    $couleur1 = self::check($_couleur1);
    $couleur2 = self::check($_couleur2);

    if (self::distance($couleur1, $couleur2, null, $tolerance) > $tolerance) return false;
    else return true;
  }
}