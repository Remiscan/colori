<?php
class Couleur
{
  private const COULEURS_NOMMEES = array(
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
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.')\\)?$/',
        // rgb(100%, 100%, 100%) ou rgb(100%,100%,100%)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.')\\)?$/',
        // rgb(255 255 255)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.')\\)?$/',
        // rgb(100% 100% 100%)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.')\\)?$/'
      )
    ), array(
      'id' => 'RGBA',
      'prefix' => 'rgba(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // rgba(255, 255, 255, .5) ou rgba(255, 255, 255, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNum.'), ?('.self::vNP.')\\)?$/',
        // rgba(100%, 100%, 100%, .5) ou rgba(100%, 100%, 100%, 50%) (espaces optionnels)
        '/^rgba?\\(('.self::vPer.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)?$/',
        // rgba(255 255 255 / 50%) ou rgba(255 255 255 / .5)
        '/^rgba?\\(('.self::vNum.') ('.self::vNum.') ('.self::vNum.') ?\\/ ?('.self::vNP.')\\)?$/',
        // rgba(100% 100% 100% / 50%) ou rgba(100% 100% 100% / .5)
        '/^rgba?\\(('.self::vPer.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)?$/'
      )
    ), array(
      'id' => 'HSL',
      'prefix' => 'hsl(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsl(<angle>, 100%, 100%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)?$/',
        // hsl(<angle> 100% 100%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)?$/'
      )
    ), array(
      'id' => 'HSLA',
      'prefix' => 'hsla(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsla(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
        '/^hsla?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)?$/',
        // hsla(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
        '/^hsla?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)?$/'
      )
    ), array(
      'id' => 'HWB',
      'prefix' => 'hwb(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hwb(<angle>, 100%, 100%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.')\\)?$/',
        // hwb(<angle> 100% 100%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.')\\)?$/'
      )
    ), array(
      'id' => 'HWBA',
      'prefix' => 'hwba(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hwba(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
        '/^hwba?\\(('.self::vAng.'), ?('.self::vPer.'), ?('.self::vPer.'), ?('.self::vNP.')\\)?$/',
        // hwba(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
        '/^hwba?\\(('.self::vAng.') ('.self::vPer.') ('.self::vPer.') ?\\/ ?('.self::vNP.')\\)?$/'
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
    }

    if ($this->a == 1)
    {
      $_name = array_search(str_replace('#', '', $this->hex()), self::COULEURS_NOMMEES) ?: array_search(str_replace('#', '', $this->hexa()), self::COULEURS_NOMMEES);
      if (!$_name)
        $this->name = null;
      else
        $this->name = $_name;
    }
    else
      $this->name = null;
  }

  private static function matchSyntax($couleur) {
    $resultat = false;
    foreach(self::FORMATS as $format) {
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

    // Si n est un pourcentage
    if (preg_match('/^' . self::vPer . '$/', $n))
    {
      $_n = $_n / 100;
      if ($_n < 0)
        $_n = 0;
      elseif ($_n > 1)
        $_n = 1;
      $logged = '%';
    }
    // Si n est un nombre (entre 0 et 255)
    elseif (preg_match('/^' . self::vNum . '$/', $n) && $type != 'angle' && $type != 'alpha')
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
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 100 . '%');
      elseif ($log == '255')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 255);
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->rgb());
    }
    elseif (in_array($propriete, ['h']))
    {
      [$_valeur, $log] = self::parse($valeur, null, true);
      if ($log == 'deg')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 360 . 'deg', 'angle');
      elseif ($log == 'grad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 400 . 'grad', 'angle');
      elseif ($log == 'rad')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 360 * pi() / 180 . 'rad', 'angle');
      elseif ($log == 'turn')
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) . 'turn', 'angle');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hsl());
    }
    elseif (in_array($propriete, ['s', 'l']))
    {
      [$_valeur, $log] = self::parse($valeur, null, true);
      if ($log == '%')
      {
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 100 . '%');
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
        $nouvelleCouleur->$propriete = self::parse((($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur) * 100 . '%');
      }
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hwb());
    }
    elseif (in_array($propriete, ['a']))
    {
      if ($log == 'alpha')
        $nouvelleCouleur->$propriete = self::parse(($remplace ? 0 : $nouvelleCouleur->$propriete) + $_valeur, 'alpha');
      else
        throw new Exception($error);
      return new Couleur($nouvelleCouleur->hsl());
    }
    else
      return new Couleur($nouvelleCouleur->rgb());
  }
}