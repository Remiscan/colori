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
      'prefix' => '#',
      'separator' => '',
      'suffix' => '',
      'syntaxes' => array(
        // #abc ou #ABC
        '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
        // #aabbcc ou #AABBCC
        '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
      )
    ), array(
      'id' => 'HEXA',
      'prefix' => '#',
      'separator' => '',
      'suffix' => '',
      'syntaxes' => array(
        // #abcd ou #ABCD
        '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
        // #aabbccdd ou #AABBCCDD
        '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
      )
    ), array(
      'id' => 'RGB',
      'prefix' => 'rgb(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // rgb(255, 255, 255) ou rgb(255,255,255)
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.')\\)$/',
        // rgb(100%, 100%, 100%) ou rgb(100%,100%,100%)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // rgb(255 255 255)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.')\\)$/',
        // rgb(100% 100% 100%)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'RGBA',
      'prefix' => 'rgba(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // rgba(255, 255, 255, .5) ou rgba(255, 255, 255, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNP.')\\)$/',
        // rgba(100%, 100%, 100%, .5) ou rgba(100%, 100%, 100%, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // rgba(255 255 255 / 50%) ou rgba(255 255 255 / .5)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.') ?\\/ ?('.self::vNP.')\\)$/',
        // rgba(100% 100% 100% / 50%) ou rgba(100% 100% 100% / .5)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'HSL',
      'prefix' => 'hsl(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsl(<angle>, 100%, 100%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // hsl(<angle> 100% 100%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'HSLA',
      'prefix' => 'hsla(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsla(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // hsla(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'HWB',
      'prefix' => 'hwb(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hwb(<angle>, 100%, 100%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)$/',
        // hwb(<angle> 100% 100%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)$/'
      )
    ), array(
      'id' => 'HWBA',
      'prefix' => 'hwba(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hwba(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)$/',
        // hwba(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'LAB',
      'prefix' => 'lab(',
      'separator' => ' ',
      'suffix' => ')',
      'syntaxes' => array(
        // lab(300% 25 40)
        '/^lab\\(('.self::vPer.') ('.self::vNum.') ('.self::vNum.')\\)$/',
        // lab(300% 25 40 / .5)
        '/^lab\\(('.self::vPer.') ('.self::vNum.') ('.self::vNum.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'LCH',
      'prefix' => 'lch(',
      'separator' => ' ',
      'suffix' => ')',
      'syntaxes' => array(
        // lch(300% 25 <angle>)
        '/^lch\\(('.self::vPer.') ('.self::vNum.') ('.self::vAng.')\\)$/',
        // lch(300% 25 <angle> / .5)
        '/^lch\\(('.self::vPer.') ('.self::vNum.') ('.self::vAng.') ?\\/ ?('.self::vNP.')\\)$/'
      )
    ), array(
      'id' => 'NAME',
      'prefix' => '',
      'separator' => '',
      'suffix' => '',
      'syntaxes' => array(
        // white ou WHITE ou WhiTe
        '/^[A-Za-z]+$/'
      )
    )
  );
  
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
  public $name;
  
  function __construct($couleur)
  {
    if ($couleur instanceof Couleur)
      throw new Exception('Already an instance of Couleur');
    else if (!is_string($couleur))
      throw 'Couleur objects can only be created from a String';

    $format = self::matchSyntax(trim($couleur));

    if (in_array($format['id'], array('HEX', 'HEXA')))
    {
      $r = $format['data'][1];
      $r = (strlen($r) == 1) ? str_repeat($r, 2) : $r;
      $g = $format['data'][2];
      $g = (strlen($g) == 1) ? str_repeat($g, 2) : $g;
      $b = $format['data'][3];
      $b = (strlen($b) == 1) ? str_repeat($b, 2) : $b;
      if ($format['id'] === 'HEXA')
      {
        $a = $format['data'][4];
        $a = (strlen($a) == 1) ? str_repeat($a, 2) : $a;
      }
      else
        $a = 'ff';
      $this->r = intval(hexdec($r)) / 255;
      $this->g = intval(hexdec($g)) / 255;
      $this->b = intval(hexdec($b)) / 255;
      $this->a = floatval(hexdec($a) / 255);
      $this->rgb2hsl();
      $this->hsl2hwb();
      $this->rgb2lab();
      $this->lab2lch();
    }
    elseif (in_array($format['id'], array('RGB', 'RGBA')))
    {
      $r = self::parse($format['data'][1]);
      $g = self::parse($format['data'][2]);
      $b = self::parse($format['data'][3]);
      if ($format['id'] === 'RGBA')
        $a = self::parse($format['data'][4], 'alpha');
      else
        $a = 1;
      $this->r = $r;
      $this->g = $g;
      $this->b = $b;
      $this->a = $a;
      $this->rgb2hsl();
      $this->hsl2hwb();
      $this->rgb2lab();
      $this->lab2lch();
    }
    elseif (in_array($format['id'], array('HSL', 'HSLA')))
    {
      $h = self::parse($format['data'][1], 'angle');
      $s = self::parse($format['data'][2]);
      $l = self::parse($format['data'][3]);
      if ($format['id'] === 'HSLA')
        $a = self::parse($format['data'][4], 'alpha');
      else
        $a = 1;
      $this->h = $h;
      $this->s = $s;
      $this->l = $l;
      $this->a = $a;
      $this->hsl2rgb();
      $this->hsl2hwb();
      $this->rgb2lab();
      $this->lab2lch();
    }
    elseif (in_array($format['id'], array('HWB', 'HWBA')))
    {
      $h = self::parse($format['data'][1], 'angle');
      $w = self::parse($format['data'][2]);
      $bk = self::parse($format['data'][3]);
      if ($format['id'] === 'HWBA')
        $a = self::parse($format['data'][4], 'alpha');
      else
        $a = 1;
      $this->h = $h;
      $this->w = $w;
      $this->bk = $bk;
      $this->a = $a;
      $this->hwb2hsl();
      $this->hsl2rgb();
      $this->rgb2lab();
      $this->lab2lch();
    }
    elseif (in_array($format['id'], array('LAB')))
    {
      $ciel = Couleur::parse($format['data'][1], 'cie');
      $ciea = $format['data'][2];
      $cieb = $format['data'][3];
      if (array_key_exists(4, $format['data']))
        $a = self::parse($format['data'][4], 'alpha');
      else
        $a = 1;
      $this->ciel = $ciel;
      $this->ciea = $ciea;
      $this->cieb = $cieb;
      $this->a = $a;
      $this->lab2lch();
      $this->lab2rgb();
      $this->rgb2hsl();
      $this->hsl2hwb();
    }
    elseif (in_array($format['id'], array('LCH')))
    {
      $ciel = Couleur::parse($format['data'][1], 'cie');
      $ciec = Couleur::parse($format['data'][2], 'cie');
      $cieh = Couleur::parse($format['data'][3], 'angle');
      if (array_key_exists(4, $format['data']))
        $a = self::parse($format['data'][4], 'alpha');
      else
        $a = 1;
      $this->ciel = $ciel;
      $this->ciec = $ciec;
      $this->cieh = $cieh;
      $this->a = $a;
      $this->lch2lab();
      $this->lab2rgb();
      $this->rgb2hsl();
      $this->hsl2hwb();
    }

    if ($this->a == 1)
    {
      $hex6 = substr($this->hex(), 1);
      $_name = array_search($hex6, self::COULEURS_NOMMEES);
      if (!$_name)
        $this->name = null;
      else
        $this->name = $_name;
    }
    else
    {
      if ($this->a == 0) $this->name = 'transparent';
      else $this->name = null;
    }
  }

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

    $resultat = false;
    foreach($formats as $format) {
      foreach($format['syntaxes'] as $k => $syntaxe) {
        $matches = array();
        $_resultat = preg_match($syntaxe, $couleur, $matches);
        if ($_resultat === 1 && $matches[0] === $couleur) {
          if ($format['id'] != 'NAME') {
            $resultat = array (
              'id' => $format['id'],
              'syntaxe' => $k,
              'data' => $matches
            );
          } elseif ($format['id'] == 'NAME' && array_key_exists(strtolower($couleur), self::COULEURS_NOMMEES)) {
            $resultat = self::matchSyntax('#' . self::COULEURS_NOMMEES[strtolower($couleur)]);
          }
          break 2;
        }
      }
    }

    if ($resultat !== false)
      return $resultat;
    else
      throw new Exception('Invalid format');
  }

  // Parses a number / percentage / angle into correct format to store it
  private static function parse($n, $type = null, $log = false) {
    $_n = floatval($n);
    $logged;
    $error = 'Error';

    if ($type == 'arbitrary')
    {
      // Si n est un pourcentage (n'importe lequel)
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        $_n = $_n / 100;
        $logged = 'arbitrary%';
      }
      // Si n est un nombre (n'importe lequel)
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        $logged = 'arbitraryN';
      }
    }
    // Si n est un pourcentage
    elseif (preg_match('/^' . self::vPer . '$/', $n))
    {
      $_n = $_n / 100;
      if ($_n < 0)
        $_n = 0;
      elseif ($_n > 1 && $type != 'cie')
        $_n = 1;
      $logged = '%';
    }
    // Si n est un nombre (entre 0 et 255)
    elseif (preg_match('/^' . self::vNum . '$/', $n) && $type != 'angle' && $type != 'alpha' && $type != 'cie')
    {
      $_n = $_n / 255;
      if ($_n < 0)
        $_n = 0;
      elseif ($_n > 1)
        $_n = 1;
      $logged = '255';
    }
    // Si n est un nombre (entre 0 et 1)
    elseif (preg_match('/^' . self::vNum . '$/', $n) && $type == 'alpha')
    {
      if ($_n < 0)
        $_n = 0;
      elseif ($_n > 1)
        $_n = 1;
      $logged = 'alpha';
    }
    // Si n est un nombre (entre 0 et +Infinity)
    elseif (preg_match('/^' . self::vNum . '$/', $n) && $type == 'cie')
    {
      if ($_n < 0)
        $_n = 0;
      $logged = 'cieC';
    }
    // Si n est un angle (donc un nombre ou un nombre avec unité d'angle)
    elseif (preg_match('/^' . self::vAng . '$/', $n) && $type == 'angle')
    {
      // en deg
      if (substr($n, -3) == 'deg' || preg_match('/^' . self::vNum . '$/', $n))
      {
        while ($_n < 0) {
          $_n += 360;
        }
        while ($_n > 360) {
          $_n -= 360;
        }
        $_n = $_n / 360;
        $logged = 'deg';
      }
      // en grad
      elseif (substr($n, -4) == 'grad')
      {
        while ($_n < 0) {
          $_n += 400;
        }
        while ($_n > 400) {
          $_n -= 400;
        }
        $_n = $_n / 400;
        $logged = 'grad';
      }
      // en rad
      elseif (substr($n, -3) == 'rad')
      {
        $_n = $_n * 180 / pi();
        while ($_n < 0) {
          $_n += 360;
        }
        while ($_n > 360) {
          $_n -= 360;
        }
        $_n = $_n / 360;
        $logged = 'rad';
      }
      // en turn
      elseif (substr($n, -4) == 'turn')
      {
        while ($_n < 0) {
          $_n += 1;
        }
        while ($_n > 1) {
          $_n -= 1;
        }
        $logged = 'turn';
      }
      else
      {
        $_n = null;
        $error = 'Invalid angle format';
      }
    }
    else
    {
      $_n = null;
      $error = 'Invalid value format';
    }

    if ($_n === null)
      throw new Exception($error);

    if ($log)
      return [$_n, $logged];
    else
      return $_n;
  }

  // Ajoute un zéro avant une chaîne d'un seul caractère
  private static function pad($s) {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  public function hexa() {
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    $a = self::pad(dechex(round($this->a * 255)));
    return '#'.$r.$g.$b.$a;
  }

  public function hex() {
    if ($this->a < 1)
      return $this->hexa();
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    return '#'.$r.$g.$b;
  }

  public function rgba() {  
    $r = round($this->r * 255);
    $g = round($this->g * 255);
    $b = round($this->b * 255);
    $a = round($this->a * 100) / 100;
    return 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
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

  public function hsla() {  
    $h = round($this->h * 360);
    $s = round($this->s * 100);
    $l = round($this->l * 100);
    $a = round($this->a * 100) / 100;
    return 'hsla('.$h.', '.$s.'%, '.$l.'%, '.$a.')';
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

  public function hwba() {
    $h = round($this->h * 360);
    $w = round($this->w * 100);
    $bk = round($this->bk * 100);
    $a = round($this->a * 100) / 100;
    return 'hwb('.$h.' '.$w.'% '.$bk.'% / '.$a.')';
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

  private function rgb2hsl() {
    $r = $this->r;
    $g = $this->g;
    $b = $this->b;
    
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;
    
    // Luminosité (l)
    $l = ($max + $min) / 2;
    
    // Si chroma == 0, la couleur est grise
    if ($chroma == 0)
      $h = 0;
    // Sinon, on calcule la teinte h
    // (source des maths : https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
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
    
    $this->h = $h / 360;
    $this->s = $s;
    $this->l = $l;
  }

  private function hsl2rgb() {
    // source des maths : https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
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
    
    $this->r = $r;
    $this->g = $g;
    $this->b = $b;
  }

  private function hsl2hwb() {
    $s = $this->s;
    $l = $this->l;

    $v = $l + $s * min($l, 1 - $l);
    if ($v == 0)
      $_s = 0;
    else
      $_s = 2 - 2 * $l / $v;

    $w = (1 - $_s) * $v;
    $bk = 1 - $v;

    $this->w = $w;
    $this->bk = $bk;
  }

  private function hwb2hsl() {
    $w = $this->w;
    $bk = $this->bk;

    if ($w + $bk > 1)
    {
      $w = $w / ($w + $bk);
      $bk = $bk / ($w + $bk);
    }

    $v = 1 - $bk;
    $_s = 1 - $w / $v;

    $l = $v - $v * $_s / 2;
    if ($l == 0 || $l == 1)
      $s = 0;
    else
      $s = ($v - $l) / min($l, 1 - $l);

    $this->s = $s;
    $this->l = $l;
  }

  private function rgb2lab() {
    // Source des maths : https://www.w3.org/TR/css-color-4/#rgb-to-lab
    $linRGB = function($x) { return ($x < 0.04045) ? ($x / 12.92) : (($x + 0.055) / 1.055) ** 2.4; };
    $r = $linRGB($this->r);
    $g = $linRGB($this->g);
    $b = $linRGB($this->b);

    $x = 0.4124564 * $r + 0.3575761 * $g + 0.1804375 * $b;
    $y = 0.2126729 * $r + 0.7151522 * $g + 0.0721750 * $b;
    $z = 0.0193339 * $r + 0.1191920 * $g + 0.9503041 * $b;

    $x50 = 1.0478112 * $x + 0.0228866 * $y - 0.0501270 * $z;
    $y50 = 0.0295424 * $x + 0.9904844 * $y - 0.0170491 * $z;
    $z50 = -0.0092345 * $x + 0.0150436 * $y + 0.7521316 * $z;

    $ε = 216/24389;
    $κ = 24389/27;
    $w = [0.96422, 1, 0.82521];

    $x = $x50 / $w[0];
    $y = $y50 / $w[1];
    $z = $z50 / $w[2];

    $f = function($x) { return ($x > $ε) ? $x ** (1/3) : ($κ * $x + 16) / 116; };

    $this->ciel = (116 * $f($y) - 16) / 100;
    $this->ciea = 500 * ($f($x) - $f($y));
    $this->cieb = 200 * ($f($y) - $f($z));
  }

  private function lab2rgb() {
    // Source des maths : https://www.w3.org/TR/css-color-4/#lab-to-rgb
    $ε = 216/24389;
    $κ = 24389/27;
    $w = [0.96422, 1, 0.82521];

    $l = $this->ciel * 100;

    $f1 = ($l + 16) / 116;
    $f0 = $this->ciea / 500 + $f1;
    $f2 = $f1 - $this->cieb / 200;

    $x50 = ($f0 ** 3 > $ε) ? $f0 ** 3 : (116 * $f0 - 16) / $κ;
    $y50 = ($l > $κ * $ε) ? (($l + 16) / 116) ** 3 : $l / $κ;
    $z50 = ($f2 ** 3 > $ε) ? $f2 ** 3 : (116 * $f2 - 16) / $κ;

    $x50 = $x50 * $w[0];
    $y50 = $y50 * $w[1];
    $z50 = $z50 * $w[2];

    $x = 0.9555766 * $x50 - 0.0230393 * $y50 + 0.0631636 * $z50;
    $y = -0.0282895 * $x50 + 1.0099416 * $y50 + 0.0210077 * $z50;
    $z = 0.0122982 * $x50 - 0.0204830 * $y50 + 1.3299098 * $z50;

    $r = 3.2404542 * $x - 1.5371385 * $y - 0.4985341 * $z;
    $g = -0.9692660 * $x + 1.8760108 * $y + 0.0415560 * $z;
    $b = 0.0556434 * $x - 0.2040259 * $y + 1.0572252 * $z;

    $gamRGB = function($x) { return ($x > 0.0031308) ? 1.055 * pow($x, 1 / 2.4) - 0.055 : 12.92 * $x; };

    $this->r = $gamRGB($r);
    $this->g = $gamRGB($g);
    $this->b = $gamRGB($b);
  }

  private function lab2lch() {
    $this->ciec = sqrt($this->ciea ** 2 + $this->cieb ** 2);
    $this->cieh = self::parse(atan2($this->cieb, $this->ciea) * 180 / pi(), 'angle');
  }

  private function lch2lab() {
    $cieh = $this->cieh * 360;
    $this->ciea = $this->ciec * cos($cieh * pi() / 180);
    $this->cieb = $this->ciec * sin($cieh * pi() / 180);
  }

  // Fusionne la couleur et une couleur de fond "background"
  public static function blend(Couleur $couleur1, Couleur $couleur2)
  {
    if ($couleur1->a < 1 && $couleur2->a < 1)
      throw new Exception('At least one of the arguments needs to be an opaque Couleur');
    else if ($couleur1->a < 1 && $couleur2->a == 1)
    {
      $background = $couleur2;
      $overlay = $couleur1;
    }
    else
    {
      $background = $couleur1;
      $overlay = $couleur2;
    }
    $r = round(255 * ($overlay->a * $overlay->r + (1 - $overlay->a) * $background->r));
    $g = round(255 * ($overlay->a * $overlay->g + (1 - $overlay->a) * $background->g));
    $b = round(255 * ($overlay->a * $overlay->b + (1 - $overlay->a) * $background->b));
    return new Couleur('rgb('.$r.', '.$g.', '.$b.')');
  }

  // Raccourci pour blend impossible

  // Calcule la luminance d'une couleur
  // (source des maths : https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  public function luminance() {
    $couleur = new Couleur($this->rgb());
    if ($this->a < 1)
      throw new Exception('Can\'t calculate luminance of transparent color');

    $arr = array($couleur->r, $couleur->g, $couleur->b);
    for ($i = 0; $i <= 2; $i++) {
      $e = $arr[$i];
      if ($e <= 0.03928)
        $e = $e / 12.92;
      else
        $e = pow(($e + 0.055) / 1.055, 2.4);
      $arr[$i] = $e;
    }
    
    return 0.2126 * $arr[0] + 0.7152 * $arr[1] + 0.0722 * $arr[2];
  }

  // Calcule le contraste entre deux couleurs
  // (source des maths : https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  public static function contrast($couleur1, $couleur2) {
    if (!($couleur1 instanceof Couleur) || !($couleur2 instanceof Couleur))
      throw new Exception('Arguments should be two instances of the Couleur class');
    $L1 = $couleur1->luminance();
    $L2 = $couleur2->luminance();
    $Lmax = max($L1, $L2);
    $Lmin = min($L1, $L2);
    return ($Lmax + 0.05) / ($Lmin + 0.05);
  }

  // Raccourci pour contrast impossible
  
  // Vérifie si un texte blanc ou noir aurait meilleur contraste avec cette couleur
  // (source des maths : https://www.w3.org/TR/WCAG20-TECHS/G18.html)
  public function contrastedText() {
    $L = $this->luminance(); // luminance de la couleur entrée
    $LB = 1; // luminance du blanc
    $LN = 0; // luminance du noir
    $contrastes = array(
      ($L + 0.05) / ($LN + 0.05), // contraste entre la couleur entrée et le noir
      ($LB + 0.05) / ($L + 0.05)  // contraste entre le blanc et la couleur entrée
    );
    if ($contrastes[0] > $contrastes[1]) // contraste plus fort avec le noir
      return 'black'; // le texte noir ira mieux sur le fond de couleur
    else
      return 'white'; // le texte blanc ira mieux sur le fond de couleur
  }

  // Change une propriété d'une couleur
  public function change($propriete, $valeur, $remplace = false) {
    $nouvelleCouleur = new Couleur($this->rgb());
    $error = 'Incorrect value format for ' . $propriete;

    if (in_array($propriete, ['r', 'g', 'b']))
    {
      [$_valeur, $log] = self::parse($valeur, null, true);
      if ($log == '%')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 100) * 100 . '%');
      elseif ($log == '255')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 255) * 255);
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->rgb());
    }
    elseif (in_array($propriete, ['h']))
    {
      [$_valeur, $log] = self::parse($valeur, 'angle', true);
      if ($log == 'deg')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 360) * 360 . 'deg', 'angle');
      elseif ($log == 'grad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 400) * 400 . 'grad', 'angle');
      elseif ($log == 'rad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / (360 * pi() / 180)) * 360 * pi() / 180 . 'rad', 'angle');
      elseif ($log == 'turn')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur)) . 'turn', 'angle');
      else
        throw new Exception($error . ' - ' . $log);
      return new Couleur($nouvelleCouleur->hsl());
    }
    elseif (in_array($propriete, ['s', 'l']))
    {
      [$_valeur, $log] = self::parse($valeur, null, true);
      if ($log == '%')
      {
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 100) * 100 . '%');
      }
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hsl());
    }
    elseif (in_array($propriete, ['w', 'bk']))
    {
      [$_valeur, $log] = self::parse($valeur, null, true);
      if ($log == '%')
      {
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 100) * 100 . '%');
      }
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hwb());
    }
    elseif (in_array($propriete, ['a']))
    {
      [$_valeur, $log] = self::parse($valeur, 'alpha', true);
      if ($log == 'alpha')
        $nouvelleCouleur->$propriete = self::parse(($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur), 'alpha');
      elseif ($log == '%')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 100) * 100 . '%', 'alpha');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hsl());
    }
    elseif (in_array($propriete, ['ciel']))
    {
      [$_valeur, $log] = self::parse($valeur, 'cie', true);
      if ($log == '%')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 100) * 100 . '%', 'cie');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->lab());
    }
    elseif (in_array($propriete, ['ciea', 'cieb']))
    {
      [$_valeur, $log] = self::parse($valeur, 'arbitrary', true);
      if ($log == 'arbitraryN')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur)), 'arbitrary');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->lab());
    }
    elseif (in_array($propriete, ['ciec']))
    {
      [$_valeur, $log] = self::parse($valeur, 'cie', true);
      if ($log == 'cieC')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur)), 'cie');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->lch());
    }
    elseif (in_array($propriete, ['cieh']))
    {
      [$_valeur, $log] = self::parse($valeur, 'angle', true);
      if ($log == 'deg')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 360) * 360 . 'deg', 'angle');
      elseif ($log == 'grad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / 400) * 400 . 'grad', 'angle');
      elseif ($log == 'rad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + floatval($valeur) / (360 * (pi() / 180))) * 360 * (pi() / 180) . 'rad', 'angle');
      elseif ($log == 'turn')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleu->$propriete) + $_valeur) . 'turn', 'angle');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->lch());
    }
    else
      return new Couleur($nouvelleCouleur->rgb());
  }

  // Remplace une propriété d'une couleur
  public function replace($propriete, $valeur, $options = null) {
    if ($options === null) $options = new stdClass();
    $options->replace = true;
    return $this->change($propriete, $valeur, $options);
  }

  // Remplace une propriété d'une couleur par un pourcentage de sa valeur initiale
  public function scale($propriete, $valeur, $options = null) {
    if ($options === null) $options = new stdClass();
    $options->replace = true;

    [$_valeur, $log] = self::parse($valeur, 'alpha', true);
    if (!in_array($log, ['%', 'alpha']))
      throw new Exception('Second parameter should be a percentage or a number between 0 and 1');

    $nouvelleCouleur = new Couleur($this->rgb());
    $nouvelleCouleur->{$propriete} = $_valeur * $nouvelleCouleur->{$propriete};

    if (in_array($propriete, ['r', 'g', 'b']))
      return new Couleur($nouvelleCouleur->rgb());
    elseif (in_array($propriete, ['h']))
      return new Couleur($nouvelleCouleur->hsl());
    elseif (in_array($propriete, ['s', 'l']))
      return new Couleur($nouvelleCouleur->hsl());
    elseif (in_array($propriete, ['w', 'bk']))
      return new Couleur($nouvelleCouleur->hwb());
    elseif (in_array($propriete, ['a']))
      return new Couleur($nouvelleCouleur->hsl());
    elseif (in_array($propriete, ['ciel']))
      return new Couleur($nouvelleCouleur->lab());
    elseif (in_array($propriete, ['ciea', 'cieb']))
      return new Couleur($nouvelleCouleur->lab());
    elseif (in_array($propriete, ['ciec', 'cieh']))
      return new Couleur($nouvelleCouleur->lab());
    else
      return new Couleur($nouvelleCouleur->rgb());
  }

  // change() aliases
  public function complement() {
    return $this->change('h', 180);
  }

  public function negative() {
    return new Couleur('rgba(' . 255 * (1 - $this->r) . ', ' . 255 * (1 - $this->g) . ', ' . 255 * (1 - $this->b) . ', ' . $this->a . ')');
  }

  public function invert() {
    return $this->negative();
  }

  // options : {scale: true/false}
  public function darken($_value, $options = null) {
    $value = self::parse($_value, 'arbitrary');
    $value = $value * 100;
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (property_exists($options, 'scale') ? $options['scale'] : true);
    $newValue = ($scale == true) ? ($this->l * (100 - floatval($value))) . '%'
                                 : -1 * floatval($value) . '%';
    return $this->change('l', $newValue, $scale);
  }

  public function lighten($_value, $options = null) {
    $value = self::parse($_value, 'arbitrary');
    $value = $value * 100;
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (property_exists($options, 'scale') ? $options['scale'] : true);
    $newValue = ($scale == true) ? ($this->l * (100 + floatval($value))) . '%'
                                 : floatval($value) . '%';
    return $this->change('l', $newValue, $scale);
  }

  public function desaturate($_value, $options = null) {
    $value = self::parse($_value, 'arbitrary');
    $value = $value * 100;
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (property_exists($options, 'scale') ? $options['scale'] : true);
    $newValue = ($scale == true) ? ($this->s * (100 - floatval($value))) . '%'
                                 : -1 * floatval($value) . '%';
    return $this->change('s', $newValue, $scale);
  }

  public function saturate($_value, $options = null) {
    $value = self::parse($_value, 'arbitrary');
    $value = $value * 100;
    if ($options === null) $options = new stdClass();
    $scale = ($options === true || $options === false) ? $options : (property_exists($options, 'scale') ? $options['scale'] : true);
    $newValue = ($scale == true) ? ($this->s * (100 + floatval($value))) . '%'
                                 : floatval($value) . '%';
    return $this->change('s', $newValue, $scale);
  }

  public function greyscale() {
    return $this->desaturate('100%');
  }

  public function grayscale() {
    return $this->greyscale();
  }
}