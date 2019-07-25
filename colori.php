<?php
class Couleur
{
  private const COULEURS_NOMMEES = array(
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
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\)?$/',
        // rgb(100%, 100%, 100%) ou rgb(100%,100%,100%)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        //rgb(255 255 255)
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\)?$/',
        // rgb(100% 100% 100%)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/'
      )
    ), array(
      'id' => 'RGBA',
      'prefix' => 'rgba(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // rgba(255, 255, 255, .5) (espaces optionnels)
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]|0?\.[0-9]+)\)?$/',
        // rgba(255, 255, 255, 50%) (espaces optionnels)
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // rgba(255 255 255 / 50%)
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // rgba(255 255 255 / .5)
        '/^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) \/ ([01]|0?\.[0-9]+)\)?$/',
        // rgba(100%, 100%, 100%, .5) (espaces optionnels)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/',
        // rgba(100%, 100%, 100%, 50%) (espaces optionnels)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // rgba(100% 100% 100% / 50%)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // rgba(100% 100% 100% / .5)
        '/^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/'
      )
    ), array(
      'id' => 'HSL',
      'prefix' => 'hsl(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsl(360, 100%, 100%) ou  hsl(360deg, 100%, 100%) (espaces optionnels)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsl(360 100% 100%) ou hsl(360deg 100% 100%)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsl(1.25rad, 100%, 100%) (espaces optionnels)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsl(1.25rad 100% 100%)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsl(.25turn, 100%, 100%) (espaces optionnels)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsl(.25turn 100% 100%)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/'
      )
    ), array(
      'id' => 'HSLA',
      'prefix' => 'hsla(',
      'separator' => ', ',
      'suffix' => ')',
      'syntaxes' => array(
        // hsla(360, 100%, 100%, .5) ou  hsl(360deg, 100%, 100%, .5) (espaces optionnels)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/',
        // hsla(360 100% 100% / .5) ou hsl(360deg 100% 100% / .5)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/',
        // hsla(1.25rad, 100%, 100%, .5) (espaces optionnels)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/',
        // hsla(1.25rad 100% 100% / .5)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/',
        // hsla(.25turn, 100%, 100%, .5) (espaces optionnels)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/',
        // hsla(.25turn 100% 100% / .5)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/',
        // hsla(360, 100%, 100%, 50%) ou  hsl(360deg, 100%, 100%, 50%) (espaces optionnels)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsla(360 100% 100% / 50%) ou hsl(360deg 100% 100% / 50%)
        '/^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsla(1.25rad, 100%, 100%, 50%) (espaces optionnels)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsla(1.25rad 100% 100% / 50%)
        '/^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsla(.25turn, 100%, 100%, 50%) (espaces optionnels)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/',
        // hsla(.25turn 100% 100% / 50%)
        '/^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/'
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
  public $a;
  public $name;
  
  function __construct($couleur)
  {
    $format = $this->matchSyntax(trim($couleur));
    if (in_array($format['id'], array('HEX', 'HEXA')))
    {
      $r = $format['data'][1];
      $r = (strlen($r) == 1) ? $r . '' . $r : $r;
      $g = $format['data'][2];
      $g = (strlen($g) == 1) ? $g . '' . $g : $g;
      $b = $format['data'][3];
      $b = (strlen($b) == 1) ? $b . '' . $b : $b;
      if ($format['id'] === 'HEXA')
      {
        $a = $format['data'][4];
        $a = (strlen($a) == 1) ? $a . '' . $a : $a;
      }
      else
        $a = 'ff';
      $this->r = intval(hexdec($r));
      $this->g = intval(hexdec($g));
      $this->b = intval(hexdec($b));
      $this->a = floatval(hexdec($a) / 255);
      $this->rgba2hsla();
    }
    elseif (in_array($format['id'], array('RGB', 'RGBA')))
    {
      $r = $format['data'][1];
      $g = $format['data'][2];
      $b = $format['data'][3];
      // Si les couleurs sont en pourcentage, on les met sur 255
      if (substr($r, -1) == '%')
      {
        $r = round(floatval($r) / 100 * 255);
        $g = round(floatval($g) / 100 * 255);
        $b = round(floatval($b) / 100 * 255);
      }
      if ($format['id'] === 'RGBA')
        $a = $format['data'][4];
      else
        $a = 1;
      // Si alpha est en pourcentage, on le met sur 1
      if (substr($a, -1) == '%')
        $a = floatval($a) / 100;
      $this->r = intval($r);
      $this->g = intval($g);
      $this->b = intval($b);
      $this->a = floatval($a);
      $this->rgba2hsla();
    }
    elseif (in_array($format['id'], array('HSL', 'HSLA')))
    {
      $h = $format['data'][1];
      if (substr($h, -3) == 'rad')
        $h = round(floatval($h) * 180 / pi());
      elseif (substr($h, -4) == 'turn')
        $h = round(floatval($h) * 360);
      while ($h > 360) {
        $h -= 360;
      }
      while ($h < 0) {
        $h += 360;
      }
      $s = floatval($format['data'][2]);
      $l = floatval($format['data'][3]);
      if ($format['id'] === 'HSLA')
        $a = $format['data'][4];
      else
        $a = 1;
      // Si alpha est en pourcentage, on le met sur 1
      if (substr($a, -1) == '%')
        $a = floatval($a) / 100;
      $this->h = intval($h);
      $this->s = intval($s);
      $this->l = intval($l);
      $this->a = floatval($a);
      $this->hsla2rgba();
    }
    if ($this->a == 1)
    {
      $_name = array_search(str_replace('#', '', $this->get_hex()), self::COULEURS_NOMMEES);
      if (!$_name)
        $this->name = null;
      else
        $this->name = $_name;
    }
    else
      $this->name = null;
  }

  private function matchSyntax($couleur) {
    $resultat = false;
    try {
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
              $resultat = $this->matchSyntax('#' . self::COULEURS_NOMMEES[strtolower($couleur)]);
            }
            break 2;
          }
        }
      }

      if ($resultat !== false)
        return $resultat;
      else
        throw new Exception('Invalid format');
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  // Ajoute un zéro avant une chaîne d'un seul caractère
  private function pad($s) {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  public function get_hexa()
  {
    $a = round($this->a * 255);
    $valeur = '#'
            . $this->pad(dechex($this->r))
            . $this->pad(dechex($this->g))
            . $this->pad(dechex($this->b))
            . $this->pad(dechex($a));
    return $valeur;
  }

  public function get_hex()
  {
    $valeur = '#'
            . $this->pad(dechex($this->r))
            . $this->pad(dechex($this->g))
            . $this->pad(dechex($this->b));
    return $valeur;
  }

  public function get_rgba()
  {  
    $valeur = 'rgba('
            . $this->r
            . ', '
            . $this->g
            . ', '
            . $this->b
            . ', '
            . $this->a
            . ')';
    return $valeur;
  }

  public function get_rgb()
  {  
    $valeur = 'rgb('
            . $this->r
            . ', '
            . $this->g
            . ', '
            . $this->b
            . ')';
    return $valeur;
  }

  public function get_hsla()
  {  
    $valeur = 'hsla('
            . $this->h
            . ', '
            . $this->s
            . '%, '
            . $this->l
            . '%, '
            . $this->a
            . ')';
    return $valeur;
  }

  public function get_hsl()
  {  
    $valeur = 'hsl('
            . $this->h
            . ', '
            . $this->s
            . '%, '
            . $this->l
            . '%)';
    return $valeur;
  }

  private function rgba2hsla()
  {
    $r = $this->r;
    $g = $this->g;
    $b = $this->b;
    $r = $r / 255;
    $g = $g / 255;
    $b = $b / 255;
    
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;
    
    // Luminosité (l)
    $l = ($max + $min) / 2;
    
    // Si chroma == 0, la couleur est grise
    if ($chroma == 0)
    {
        $h = 0;
    }
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
    
    $h = round($h);
      
    $s = $s * 100;
    $s = round($s);
    
    $l = $l * 100;
    $l = round($l);
    
    $this->h = intval($h);
    $this->s = intval($s);
    $this->l = intval($l);
  }

  private function hsla2rgba()
  {
    // source des maths : https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    $h = $this->h;
    $s = $this->s / 100;
    $l = $this->l / 100;

    $m = $s * min($l, 1 - $l);

    $arr = [0, 8, 4];
    for ($i = 0; $i <= 2; $i++) {
      $k = fmod($arr[$i] + $h / 30, 12);
      $arr[$i] = $l - $m * max(min($k - 3, 9 - $k, 1), -1);
    }
    
    $r = round($arr[0] * 255);
    $g = round($arr[1] * 255);
    $b = round($arr[2] * 255);
    
    $this->r = intval($r);
    $this->g = intval($g);
    $this->b = intval($b);
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
    $r = round($overlay->a * $overlay->r + (1 - $overlay->a) * $background->r);
    $g = round($overlay->a * $overlay->g + (1 - $overlay->a) * $background->g);
    $b = round($overlay->a * $overlay->b + (1 - $overlay->a) * $background->b);
    return new Couleur('rgb(' . $r . ', ' . $g . ', ' . $b . ')');
  }

  // Calcule la luminance d'une couleur
  // (source des maths : https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  public function luminance() {
    $couleur = new Couleur($this->get_rgba());
    if ($this->a < 1)
      throw new Exception('Can\'t calculate luminance of transparent color');

    $arr = array($couleur->r, $couleur->g, $couleur->b);
    for ($i = 0; $i <= 2; $i++) {
      $e = $arr[$i];
      $e = $e / 255;
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
      return new Couleur('black'); // le texte noir ira mieux sur le fond de couleur
    else
      return new Couleur('white'); // le texte blanc ira mieux sur le fond de couleur
  }

  // Change une propriété d'une couleur
  public function change($propriete, $valeur, $remplace = false) {
    $nouvelleCouleur = new Couleur($this->get_rgba());
    if (in_array($propriete, ['r', 'g', 'b']))
    {
      $nouvelleCouleur->$propriete = max(0, min(255, ($remplace ? 0 : $nouvelleCouleur->$propriete) + $valeur));
      return new Couleur($nouvelleCouleur->get_rgba());
    }
    else if (in_array($propriete, ['h']))
    {
      $nouvelleCouleur->$propriete = ($remplace ? 0 : $nouvelleCouleur->$propriete) + $valeur;
      return new Couleur($nouvelleCouleur->get_hsla());
    }
    else if (in_array($propriete, ['s', 'l']))
    {
      $nouvelleCouleur->$propriete = max(0, min(100, ($remplace ? 0 : $nouvelleCouleur->$propriete) + $valeur));
      return new Couleur($nouvelleCouleur->get_hsla());
    }
    else if (in_array($propriete, ['a']))
    {
      $nouvelleCouleur->$propriete = max(0, min(1, ($remplace ? 0 : $nouvelleCouleur->$propriete) + $valeur));
      return new Couleur($nouvelleCouleur->get_hsla());
    }
    else
      return new Couleur($nouvelleCouleur->get_rgba());
  }
}