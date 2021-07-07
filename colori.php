<?php
class Couleur
{
  public $r;
  public $g;
  public $b;
  public $a;
  
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



  /** Matches the user input with supported color formats. */
  private static function matchSyntax($couleur) {
    $tri = substr($couleur, 0, 3);
    if (substr($tri, 0, 1) === '#')
      $formats = [self::FORMATS[0], self::FORMATS[1]];
    elseif ($tri === 'rgb')
      $formats = [self::FORMATS[2], self::FORMATS[3]];
    elseif ($tri === 'hsl')
      $formats = [self::FORMATS[4], self::FORMATS[5]];
    elseif ($tri === 'hwb')
      $formats = [self::FORMATS[6], self::FORMATS[7]];
    elseif ($tri === 'lab')
      $formats = [self::FORMATS[8]];
    elseif ($tri === 'lch')
      $formats = [self::FORMATS[9]];
    else
      $formats = [self::FORMATS[10]];

    foreach($formats as $format) {
      foreach($format['syntaxes'] as $k => $syntaxe) {
        $matches = array();
        $_resultat = preg_match($syntaxe, $couleur, $matches);
        if ($_resultat === 1 && $matches[0] === $couleur) {
          if (($format['id'] === 'NAME') && array_key_exists(strtolower($couleur), self::COULEURS_NOMMEES)) {
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


  /** Calculates all the color properties from the already defined ones. */
  private function compute($knownFormat, $values, $clamp = self::MUST_CLAMP_TO_SRGB) {
    switch ($knownFormat) {
      case 'rgb': $rgb = $values; break;
      case 'hsl': $rgb = self::hsl2rgb($values); break;
      case 'hwb': $rgb = self::hsl2rgb(self::hwb2hsl($values)); break;
      case 'lab':
        if ($clamp) $rgb = self::clampToSRGB(self::lab2rgb($values));
        else        $rgb = self::lab2rgb($values);
        break;
      case 'lch':
        if ($clamp) $rgb = self::clampToSRGB(self::lab2rgb(self::lch2lab($values)));
        else        $rgb = self::lab2rgb(self::lch2lab($values));
        break;
    }
    $this->r = self::pRound($rgb[0]);
    $this->g = self::pRound($rgb[1]);
    $this->b = self::pRound($rgb[2]);
  }


  /** Returns a float precise to the nth decimal. */
  private static function pRound($_x, $n = 5) {
    $x = (float) $_x;
    $intDigits = ($x !== 0) ? floor(log10($x > 0 ? $x : -$x) + 1) : 1;
    $precision = (int) ($n - $intDigits);
    return round($x, $precision);
  }


  /** Checks if a variable is a Couleur object, or if it can be made into one. */
  public static function check($color) {
    if ($color instanceof self) return $color;
    try { return new self($color); }
    catch (Exception $error) {
      throw new Exception('Argument should be an instance of the ' . __CLASS__ . ' class, or a valid color string ; this isn\'t: ' . $color);
    }
  }


  /** Parses a number / percentage / angle into the correct format to store it. */
  private static function parse($n, $type = null, $clamp = true) {
    // Alpha values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 1]
    // to [0, 1]
    if ($type === 'a') {
      // If n is a percentage
      if (preg_match('/^' . self::vPer . '$/', $n)) {
        if ($clamp) return max(.0, min(floatval($n) / 100, 1.0));
        else        return floatval($n) / 100;
      }
      // If n is a number
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        if ($clamp) return max(.0, min($n, 1.0));
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
        if ($clamp) return max(.0, min(floatval($n) / 100, 1.0));
        else        return floatval($n) / 100;
      }
      // If n is a number
      elseif (preg_match('/^' . self::vNum . '$/', $n)) {
        if ($clamp) return max(.0, min($n / 255.0, 1.0));
        else        return $n / 255.0;
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
        return (float) $_n;
      }
      // If n is an angle
      elseif (preg_match('/^' . self::vAng . '$/', $n)) {
        if (substr($n, -3) === 'deg') {
          while ($_n < 0) $_n += 360;
          while ($_n > 360) $_n -= 360;
          return (float) $_n;
        } elseif (substr($n, -3) === 'grad') {
          while ($_n < 0) $_n += 400;
          while ($_n > 400) $_n -= 400;
          return (float) $_n * 360 / 400;
        } elseif (substr($n, -3) === 'rad') {
          $_n = $_n * 180 / pi();
          while ($_n < 0) $_n += 360;
          while ($_n > 360) $_n -= 360;
          return (float) $_n;
        } elseif (substr($n, -3) === 'turn') {
          while ($_n < 0) $_n += 1;
          while ($_n > 1) $_n -= 1;
          return (float) $_n * 360;
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
    elseif ($type === 'ciec') {
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

  
  /** Unparses a value to the format that would be used in a CSS expression. */
  private static function unparse($prop, $value, $round = true) {
    switch ($prop) {
      case 'r': case 'g': case 'b':
        $unparsed = $round ? round(255 * $value) : 255 * $value;
        break;
      case 's': case 'l': case 'w': case 'bk': case 'ciel':
        $unparsed = $round ? round(100 * $value).'%' : (100 * $value).'%';
        break;
      case 'a':
        $unparsed = $round ? round(100 * $value) / 100 : $value;
        break;
      default:
        $unparsed = $round ? round($value) : $value;
    }
    return (string) $unparsed;
  }



  /*****************************************/
  /* Setters and getters for color formats */
  /*****************************************/


  /* GENERAL SETTER */

  /** Will be used by other setters. */
  private function set($data, $props, $format) {
    $values = [];
    for ($i = 0; $i < count($props); $i++) {
      $values[] = self::parse($data[$i], $props[$i]);
    }
    $this->a = self::pRound(self::parse($data[3] ?? 1, 'a'));
    $this->compute($format, $values);
  }


  /* GENERAL GETTER */

  /** Gets the CSS expression of a color. */
  public static function expr($format, $values, $round = true) {
    $props = self::propertiesOf($format);
    $a = self::unparse('a', $values[3], $round);
    $unparsed = [];
    foreach($props as $k => $p) {
      $unparsed[] = self::unparse($p, $values[$k], $round);
    }
    [$x, $y, $z] = $unparsed;

    switch ($format) {
      case 'rgb': case 'rgba': case 'hsl': case 'hsla':
        if ((strlen($format) > 3 && substr($format, -1) === 'a') || $a < 1.0)
          return "${format}(${x}, ${y}, ${z}, ${a})";
        else
          return "${format}(${x}, ${y}, ${z})";
      default:
        if ((strlen($format) > 3 && substr($format, -1) === 'a') || $a < 1.0)
          return "${format}(${x} ${y} ${z} / ${a})";
        else
          return "${format}(${x} ${y} ${z})";
    }
  }


  /* ALL VALUES (r, g, b) */
  public function values() {
    return [$this->r, $this->g, $this->b, $this->a];
  }


  /* NAME */

  /** The approximate name of the color. */
  public function name() {
    if ($this->a === 1.0) {
      $allNames = self::COULEURS_NOMMEES;
      [$r, $g, $b] = [255 * $this->r, 255 * $this->g, 255 * $this->b];
      $tolerance = 255 * self::TOLERANCE;
      foreach($allNames as $name => $hex) {
        [$r2, $g2, $b2] = [intval(hexdec($hex[0].$hex[1])), intval(hexdec($hex[2].$hex[3])), intval(hexdec($hex[4].$hex[5]))];
        if (abs($r2 - $r) + abs($g2 - $g) + abs($b2 - $b) < $tolerance) return $name;
      }
    }
    return null;
  }

  /** The exact name of the color. */
  public function exactName() {
    if ($this->a === 1) {
      $hex6 = substr($this->hex(), 1);
      $name = array_search($hex6, self::COULEURS_NOMMEES);
      return $name ? $name : null;
    }
    elseif ($this->a === 0)  return 'transparent';
    else                     return null;
  }


  /* RGB (hexadecimal) */

  /** Adds a zero before a string of length 1. */
  private static function pad($s) {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  /** Calculates all properties of the color from its hexadecimal expression. */
  private function setHex($hexa) {
    $r = $hexa[0];
    $r = (strlen($r) === 1) ? $r.$r : $r;
    $r = intval(hexdec($r));

    $g = $hexa[1];
    $g = (strlen($g) === 1) ? $g.$g : $g;
    $g = intval(hexdec($g));

    $b = $hexa[2];
    $b = (strlen($b) === 1) ? $b.$b : $b;
    $b = intval(hexdec($b));

    $a = $hexa[3] ?? 'ff';
    $a = (strlen($a) === 1) ? $a.$a : $a;
    $a = intval(hexdec($a)) / 255;

    $this->setRgb([$r, $g, $b, $a]);
  }

  /** Hexadecimal expression of the color. */
  public function hex() {
    if ($this->a < 1)
      return $this->hexa();
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    return '#'.$r.$g.$b;
  }

  /** Hexadecimal (+ a) expression of the color. */
  public function hexa() {
    $r = self::pad(dechex(round($this->r * 255)));
    $g = self::pad(dechex(round($this->g * 255)));
    $b = self::pad(dechex(round($this->b * 255)));
    $a = self::pad(dechex(round($this->a * 255)));
    return '#'.$r.$g.$b.$a;
  }


  /* RGB (functional) */

  /** Calculates all properties of the color from its functional RGB expression. */
  private function setRgb($rgba) {
    $this->set($rgba, ['r', 'g', 'b'], 'rgb');
  }

  /** RGB expression of the color. */
  public function rgb() {  
    return self::expr('rgb', $this->values());
  }

  /** RGBA expression of the color. */
  public function rgba() {  
    return self::expr('rgba', $this->values());
  }


  /* HSL */

  /** Calculates all properties of the color from its HSL expression. */
  private function setHsl($hsla) {
    $this->set($hsla, ['h', 's', 'l'], 'hsl');
  }

  /** HSL expression of the color. */
  public function hsl() {  
    $hsl = self::rgb2hsl($this->values());
    return self::expr('hsl', [$hsl[0], $hsl[1], $hsl[2], $this->a]);
  }

  /** HSLA expression of the color. */
  public function hsla() {  
    $hsl = self::rgb2hsl($this->values());
    return self::expr('hsla', [$hsl[0], $hsl[1], $hsl[2], $this->a]);
  }


  /* HWB */

  /** Calculates all properties of the color from its HWB expression. */
  private function setHwb($hwba) {
    $this->set($hwba, ['h', 'w', 'b'], 'hwb');
  }

  /** HWB expression of the color. */
  public function hwb() {
    $hwb = self::hsl2hwb(self::rgb2hsl($this->values()));
    return self::expr('hwb', [$hwb[0], $hwb[1], $hwb[2], $this->a]);
  }

  /** HWB expression of the color, but always with the alpha value. */
  public function hwba() {
    $hwb = self::hsl2hwb(self::rgb2hsl($this->values()));
    return self::expr('hwba', [$hwb[0], $hwb[1], $hwb[2], $this->a]);
  }


  /* LAB */

  /** Calculates all properties of the color from its LAB expression. */
  private function setLab($laba) {
    $this->set($laba, ['ciel', 'ciea', 'cieb'], 'lab');
  }

  /** LAB expression of the color. */
  public function lab() {
    $lab = self::rgb2lab($this->values());
    return self::expr('lab', [$lab[0], $lab[1], $lab[2], $this->a]);
  }

  /** LAB expression of the color, but always with the alpha value. */
  public function laba() {
    $lab = self::rgb2lab($this->values());
    return self::expr('laba', [$lab[0], $lab[1], $lab[2], $this->a]);
  }


  /* LCH */

  /** Calculates all properties of the color from its LCH expression. */
  private function setLch($lcha) {
    $this->set($lcha, ['ciel', 'ciec', 'cieh'], 'lch');
  }

  /** LCH expression of the color. */
  public function lch() {
    $lch = self::lab2lch(self::rgb2lab($this->values()));
    return self::expr('lch', [$lch[0], $lch[1], $lch[2], $this->a]);
  }

  /** LCH expression of the color, but always with the alpha value. */
  public function lcha() {
    $lch = self::lab2lch(self::rgb2lab($this->values()));
    return self::expr('lcha', [$lch[0], $lch[1], $lch[2], $this->a]);
  }



  /********************************************/
  /* Setters and getters for color properties */
  /********************************************/


  private function setR($val) {
    $this->r = $val;
  }

  private function setG($val) {
    $this->g = $val;
  }

  private function setB($val) {
    $this->b = $val;
  }

  private function setA($val) {
    $this->a = $val;
  }

  private function setH($val) {
    [$x, $s, $l] = self::rgb2hsl($this->values());
    $temp = [];
    $props = self::propertiesOf('hsl'); $props[] = 'a';
    foreach([$val, $s, $l, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setHsl($temp);
  }

  private function setS($val) {
    [$h, $x, $l] = self::rgb2hsl($this->values());
    $temp = [];
    $props = self::propertiesOf('hsl'); $props[] = 'a';
    foreach([$h, $val, $l, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setHsl($temp);
  }

  private function setL($val) {
    [$h, $s, $x] = self::rgb2hsl($this->values());
    $temp = [];
    $props = self::propertiesOf('hsl'); $props[] = 'a';
    foreach([$h, $s, $val, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setHsl($temp);
  }

  private function setW($val) {
    [$h, $x, $bk] = self::hsl2hwb(self::rgb2hsl($this->values()));
    $temp = [];
    $props = self::propertiesOf('hwb'); $props[] = 'a';
    foreach([$h, $val, $bk, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setHwb($temp);
  }

  private function setBK($val) {
    [$h, $w, $x] = self::hsl2hwb(self::rgb2hsl($this->values()));
    $temp = [];
    $props = self::propertiesOf('hwb'); $props[] = 'a';
    foreach([$h, $w, $val, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setHwb($temp);
  }

  private function setCIEL($val) {
    [$x, $ciea, $cieb] = self::rgb2lab($this->values());
    $temp = [];
    $props = self::propertiesOf('lab'); $props[] = 'a';
    foreach([$val, $ciea, $cieb, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setLab($temp);
  }

  private function setCIEA($val) {
    [$ciel, $x, $cieb] = self::rgb2lab($this->values());
    $temp = [];
    $props = self::propertiesOf('lab'); $props[] = 'a';
    foreach([$ciel, $val, $cieb, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setLab($temp);
  }

  private function setCIEB($val) {
    [$ciel, $ciea, $x] = self::rgb2lab($this->values());
    $temp = [];
    $props = self::propertiesOf('lab'); $props[] = 'a';
    foreach([$ciel, $ciea, $val, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setLab($temp);
  }

  private function setCIEC($val) {
    [$ciel, $x, $cieh] = self::lab2lch(self::rgb2lab($this->values()));
    $temp = [];
    $props = self::propertiesOf('lch'); $props[] = 'a';
    foreach([$ciel, $val, $cieh, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setLch($temp);
  }

  private function setCIEH($val) {
    [$ciel, $ciec, $x] = self::lab2lch(self::rgb2lab($this->values()));
    $temp = [];
    $props = self::propertiesOf('lch'); $props[] = 'a';
    foreach([$ciel, $ciec, $val, $this->a] as $k => $v) {
      $temp[] = self::unparse($props[$k], $v);
    }
    $this->setLch($temp);
  }

  public function h() { return self::rgb2hsl($this->values())[0]; }

  public function s() { return self::rgb2hsl($this->values())[1]; }

  public function l() { return self::rgb2hsl($this->values())[2]; }

  public function w() { return self::hsl2hwb(self::rgb2hsl($this->values()))[1]; }

  public function bk() { return self::hsl2hwb(self::rgb2hsl($this->values()))[2]; }

  public function ciel() { return self::rgb2lab($this->values())[0]; }

  public function ciea() { return self::rgb2lab($this->values())[1]; }

  public function cieb() { return self::rgb2lab($this->values())[2]; }

  public function ciec() { return self::lab2lch(self::rgb2lab($this->values()))[1]; }

  public function cieh() { return self::lab2lch(self::rgb2lab($this->values()))[2]; }



  /************************************/
  /* Conversion between color formats */
  /************************************/


  /* Utility functions for conversion */
  // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
  //                   & https://drafts.csswg.org/css-color-4/utilities.js
  //                   & https://drafts.csswg.org/css-color-4/conversions.js

  private static function gamRGB_linRGB($rgb) {
    return array_map(function($x) {
      $sign = $x < 0 ? -1 : 1;
      return (abs($x) < 0.04045) ? $x / 12.92 : $sign * pow((abs($x) + 0.055) / 1.055, 2.4);
    }, $rgb);
  }

  private static function linRGB_gamRGB($rgb) {
    return array_map(function($x) {
      $sign = $x < 0 ? -1 : 1;
      return (abs($x) > 0.0031308) ? $sign * (1.055 * pow(abs($x), 1 / 2.4) - 0.055) : 12.92 * $x;
    }, $rgb);
  }

  private static function linRGB_d65XYZ($rgb) {
    [$r, $g, $b] = $rgb;
    return [
      0.41239079926595934 * $r + 0.357584339383878 * $g + 0.1804807884018343 * $b,
      0.21263900587151027 * $r + 0.715168678767756 * $g + 0.07219231536073371 * $b,
      0.01933081871559182 * $r + 0.11919477979462598 * $g + 0.9505321522496607 * $b
    ];
  }

  private static function d65XYZ_linRGB($xyz) {
    [$x, $y, $z] = $xyz;
    return [
      3.2409699419045226 * $x - 1.537383177570094 * $y - 0.4986107602930034 * $z,
      -0.9692436362808796 * $x + 1.8759675015077202 * $y + 0.04155505740717559 * $z,
      0.05563007969699366 * $x - 0.20397695888897652 * $y + 1.0569715142428786 * $z
    ];
  }

  private static function d65XYZ_d50XYZ($xyz) {
    [$x, $y, $z] = $xyz;
    return [
      1.0479298208405488 * $x + 0.022946793341019088 * $y - 0.05019222954313557 * $z,
      0.029627815688159344 * $x + 0.990434484573249 * $y - 0.01707382502938514 * $z,
      -0.009243058152591178 * $x + 0.015055144896577895 * $y + 0.7518742899580008 * $z
    ];
  }

  private static function d50XYZ_d65XYZ($xyz) {
    [$x, $y, $z] = $xyz;
    return [
      0.9554734527042182 * $x - 0.023098536874261423 * $y + 0.0632593086610217 * $z,
      -0.028369706963208136 * $x + 1.0099954580058226 * $y + 0.021041398966943008 * $z,
      0.012314001688319899 * $x - 0.020507696433477912 * $y + 1.3303659366080753 * $z
    ];
  }

  private static function d50XYZ_LAB($xyz) {
    $ε = 216/24389;
    $κ = 24389/27;
    $w = [0.96422, 1, 0.82521];

    $x = $xyz[0] / $w[0];
    $y = $xyz[1] / $w[1];
    $z = $xyz[2] / $w[2];
    
    $f = function($x) use ($ε, $κ) { return ($x > $ε) ? pow($x, 1/3) : ($κ * $x + 16) / 116; };
    $f0 = $f($x); $f1 = $f($y); $f2 = $f($z);
    return [
      (116 * $f1 - 16) / 100,
      500 * ($f0 - $f1),
      200 * ($f1 - $f2)
    ];
  }

  private static function LAB_d50XYZ($lab) {
    $ε = 216/24389;
    $κ = 24389/27;
    $w = [0.96422, 1, 0.82521];

    [$ciel, $ciea, $cieb] = $lab;
    $ciel = 100 * $ciel;
    $f1 = ($ciel + 16) / 116;
    $f0 = $ciea / 500 + $f1;
    $f2 = $f1 - $cieb / 200;

    $x = ($f0 ** 3 > $ε) ? $f0 ** 3 : (116 * $f0 - 16) / $κ;
    $y = ($ciel > $κ * $ε) ? (($ciel + 16) / 116) ** 3 : $ciel / $κ;
    $z = ($f2 ** 3 > $ε) ? $f2 ** 3 : (116 * $f2 - 16) / $κ;
    return [$x * $w[0], $y * $w[1], $z * $w[2]];
  }

  /** Checks whether the given r, g and b values would make a color in sRGB space. */
  private static function inSRGB($rgb) {
    $ε = .00001;
    foreach($rgb as $v) {
      if ($v > (0 - $ε) && $v < (1 + $ε)) continue;
      return false;
    }
    return true;
  }

  /** Clamps parsed r, g and b values to sRGB space by clamping their chroma. */
  private static function clampToSRGB($rgb) {
    // Source of the math: https://css.land/lch/lch.js
    if (self::inSRGB($rgb)) return $rgb;
    $lch = self::lab2lch(self::rgb2lab($rgb));

    $τ = .0001;
    $Cmin = 0;
    $Cmax = $lch[1];
    $lch[1] = $lch[1] / 2;

    $condition = function($_lch) { return self::inSRGB(self::lab2rgb(self::lch2lab($_lch))); };
    while ($Cmax - $Cmin > $τ) {
      if ($condition($lch)) $Cmin = $lch[1];
      else                  $Cmax = $lch[1];
      $lch[1] = ($Cmin + $Cmax) / 2;
    }

    return self::lab2rgb(self::lch2lab($lch));
  }


  /* Actual conversion functions */


  private static function rgb2hsl($rgb) {
    // (source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
    [$r, $g, $b] = $rgb;
    
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;
    
    $l = ($max + $min) / 2;
    
    if ($chroma === .0) $h = .0;
    else {
      switch($max) {
        case $r: $h = ($g - $b) / $chroma; break;
        case $g: $h = ($b - $r) / $chroma + 2; break;
        case $b: $h = ($r - $g) / $chroma + 4; break;
      }
      $h = 60.0 * $h;
      if ($h < .0) $h += 360.0;
    }

    if ($l === .0 || $l === 1.0) $s = .0;
    elseif ($l <= 0.5)           $s = $chroma / (2.0 * $l);
    else                         $s = $chroma / (2.0 - 2.0 * $l);
    
    return [$h, $s, $l];
  }


  private static function hsl2rgb($hsl) {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    [$h, $s, $l] = $hsl;

    $m = $s * min($l, 1.0 - $l);

    $rgb = [0, 8, 4];
    for ($i = 0; $i <= 2; $i++) {
      $k = fmod($rgb[$i] + $h / 30, 12);
      $rgb[$i] = $l - $m * max(min($k - 3, 9 - $k, 1), -1);
    }

    return $rgb;
  }


  private static function hsl2hwb($hsl) {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    [$h, $s, $l] = $hsl;

    $v = $l + $s * min($l, 1.0 - $l);
    if ($v === .0) $_s = .0;
    else           $_s = 2.0 - 2.0 * $l / $v;

    $w = (1.0 - $_s) * $v;
    $bk = 1.0 - $v;

    return [$h, $w, $bk];
  }


  private static function hwb2hsl($hwb) {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    [$h, $w, $bk] = $hwb;

    $_w = $w; $_bk = $bk;
    if ($w + $bk > 1.0) {
      $_w = $w / ($w + $bk);
      $_bk = $bk / ($w + $bk);
    }

    $v = 1.0 - $_bk;
    if ($_bk === 1.0) $_s = .0;
    else              $_s = 1.0 - $_w / $v;

    $l = $v - $v * $_s / 2;
    if ($l === .0 || $l === 1.0) $s = .0;
    else                         $s = ($v - $l) / min($l, 1.0 - $l);

    return [$h, $s, $l];
  }


  private static function rgb2lab($rgb) {
    // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    return self::d50XYZ_LAB(self::d65XYZ_d50XYZ(self::linRGB_d65XYZ(self::gamRGB_linRGB($rgb))));
  }


  private static function lab2rgb($lab) {
    // Source of the math: https://css.land/lch/lch.js
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    return self::linRGB_gamRGB(self::d65XYZ_linRGB(self::d50XYZ_d65XYZ(self::LAB_d50XYZ($lab))));
  }


  private static function lab2lch($lab) {
    [$ciel, $ciea, $cieb] = $lab;
    $ciec = sqrt($ciea ** 2 + $cieb ** 2);
    $cieh = self::parse(atan2($cieb, $ciea) * 180 / pi(), 'cieh');

    return [$ciel, $ciec, $cieh];
  }


  private static function lch2lab($lch) {
    [$ciel, $ciec, $cieh] = $lch;
    $ciea = $ciec * cos($cieh * pi() / 180);
    $cieb = $ciec * sin($cieh * pi() / 180);

    return [$ciel, $ciea, $cieb];
  }



  /********************************/
  /* Color manipulation functions */
  /********************************/


  /* Color modification */


  /** Modifies a color by changing a specific property. */
  public function change($propriete, $valeur, $options = null) {
    if ($options === null) $options = new stdClass();
    $replace = ($options === true) ? true : (isset($options->replace) ? $options->replace : false);
    $scale = isset($options->scale) ? $options->scale : false;
    $val = $scale ? self::parse($valeur) : self::parse($valeur, $propriete, false);
    $changedColor = new self($this->rgb());

    switch ($propriete) {
      case 'r': case 'g': case 'b': case 'a': $oldVal = $this->{$propriete}; break;
      default:                                $oldVal = $this->{$propriete}();
    }
    $newVal = $replace ? $val : ($scale ? $oldVal * $val : $oldVal + $val);
    $methodName = "set" . strtoupper($propriete);
    $changedColor->{$methodName}($newVal);
    return $changedColor;
  }

  /** Modifies a color by replacing the value of a specific property. */
  public function replace($propriete, $valeur) {
    $options = new stdClass();
    $options->replace = true;
    $options->scale = false;
    return $this->change($propriete, $valeur, $options);
  }

  /** Modifies a color by scaling the value of a specific property by a percentage. */
  public function scale($propriete, $valeur) {
    $options = new stdClass();
    $options->replace = false;
    $options->scale = true;
    return $this->change($propriete, $valeur, $options);
  }

  /** The complementary color. */
  public function complement() { return $this->change('h', 180); }

  /** The inverse color. */
  public function negative() {
    return new self('rgb(' . 255 * (1 - $this->r) . ', ' . 255 * (1 - $this->g) . ', ' . 255 * (1 - $this->b) . ', ' . $this->a . ')');
  }
  public function invert() { return $this->negative(); }

  /** The shade of grey of the color. */
  public function greyscale() { return $this->replace('s', '0%'); }
  public function grayscale() { return $this->greyscale(); }

  /** The sepia tone of the color. */
  public function sepia() {
    $r = min(0.393 * $this->r + 0.769 * $this->g + 0.189 * $this->b, 1);
    $g = min(0.349 * $this->r + 0.686 * $this->g + 0.168 * $this->b, 1);
    $b = min(0.272 * $this->r + 0.534 * $this->g + 0.131 * $this->b, 1);
    return new Couleur('rgb('. 255 * $r .', '. 255 * $g .', '. 255 * $b .', '. $this->a .')');
  }


  /* Color blending */


  /** Blends colors together, in the order they were given. */
  public static function blend(...$couleurs)
  {
    if (count($couleurs) < 2) throw new Exception("You need at least 2 colors to blend");
    $background = self::check(array_shift($couleurs));
    $overlay = self::check(array_shift($couleurs));

    if ($overlay->a === 0) {
      $result = $background;
    } else {
      $a = $overlay->a + $background->a * (1 - $overlay->a);
      $r = ($overlay->r * $overlay->a + $background->r * $background->a * (1 - $overlay->a)) / $a;
      $g = ($overlay->g * $overlay->a + $background->g * $background->a * (1 - $overlay->a)) / $a;
      $b = ($overlay->b * $overlay->a + $background->b * $background->a * (1 - $overlay->a)) / $a;
      $result = new self(self::expr('rgb', [$r, $g, $b, $a]));
    }

    if (count($couleurs) === 0)  return $result;
    else                         return self::blend($result, ...$couleurs);
  }


  /** Solves the equation result = blend(background, ...overlays) with background unknown. */
  public static function unblend(...$couleurs) {
    if (count($couleurs) < 2) throw new Exception("You need at least 2 colors to blend");
    $result = self::check(array_shift($couleurs));
    $overlay = self::check(array_shift($couleurs));

    if ($overlay->a === 1) {
      throw new Exception("Overlay color ". $overlay->rgb() ." isn't transparent, so the background it was blended onto could have been any color");
    }
    elseif ($overlay->a === 0)              $background = $result;
    else {
      if ($result->a < $overlay->a)         return null;
      elseif ($result->a === $overlay->a) {
        if (self::same($result, $overlay))  $background = new self('transparent');
        else                                return null;
      }
      else {
        $a = self::pRound(($result->a - $overlay->a) / (1 - $overlay->a), 3);
        $r = self::pRound(($result->r * $result->a - $overlay->r * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        $g = self::pRound(($result->g * $result->a - $overlay->g * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        $b = self::pRound(($result->b * $result->a - $overlay->b * $overlay->a) / ($a * (1 - $overlay->a)), 3);
        foreach([$r, $g, $b] as $x) {
          if ($x < 0 - self::TOLERANCE || $x > 1 + self::TOLERANCE) return null;
        }
        $background = new self(self::expr('rgb', [$r, $g, $b, $a]));
      }
    }

    if (count($couleurs) === 0) return $background;
    else return self::unblend($background, ...$couleurs);
  }


  /** Solves the equation result = blend(background, overlay) with overlay unknown. */
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
      return new self(self::expr('rgb', [$r, $g, $b, $a]));
    };

    // If alpha is known, we can find at most one solution
    if (is_numeric($alpha) && $alpha >= 0 && $alpha <= 1) {
      if  ($alpha === 0) {
        if (self::same($background, $result)) return new self('transparent');
        else                                  return null;
      }
      elseif ($alpha === 1)                   return $result;
      elseif ($result->a < $alpha)            return null;
      elseif ($result->a === $alpha) {
        if ($background->a > 0)               return null;
        else                                  return $result;
      }
    }

    // If alpha isn't known, we can find at most one solution per possible alpha value
    if ($result->a < $background->a)          return null;
    elseif ($result->a > $background->a) {
      if ($result->a === 1)                   $overlay = $result;
      elseif ($background->a === 0)           $overlay = $result;
      // If 0 < background.a < result.a < 1, we can find a unique solution
      else {
        $a = self::pRound(($result->a - $background->a) / (1 - $background->a), 3);
        if (is_numeric($alpha) && abs($a - $alpha) > self::TOLERANCE) return null;
        try { $overlay = $calculateSolution($a); }
        catch (Exception $error) { return null; }
      }
    }
    elseif ($result->a === $background->a) {
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

    if (is_numeric($alpha)) return ($overlay->a === $alpha) ? $overlay : null;
    else                    return $overlay;
  }


  /* Color comparison */


  /** Luminance of the color. */
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  public function luminance() {
    if ($this->a < 1) throw new Exception('The luminance of a transparent color would be meaningless');
    $rgb = self::gamRGB_linRGB($this->values());
    return 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2];
  }


  /** Computes the contrast between two colors as defined by WCAG2. */
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  public static function WCAG2contrast($_couleur1, $_couleur2) {
    $couleur1 = self::check($_couleur1);
    $couleur2 = self::check($_couleur2);
    
    $L1 = $couleur1->luminance();
    $L2 = $couleur2->luminance();
    $Lmax = max($L1, $L2);
    $Lmin = min($L1, $L2);
    return ($Lmax + 0.05) / ($Lmin + 0.05);
  }


  /** Computes the SAPC/APCA contrast between two colors as defined by WCAG3. */
  // (Source of the math: https://github.com/Myndex/SAPC-APCA)
  public static function APCAcontrast($_text, $_background) {
    $background = self::check($_background);
    if ($background->a < 1.0) throw new Exception('The contrast with a transparent background color would be meaningless');

    // If the text is transparent, blend it to the background to get its actual visible color
    $text = self::check($_text);
    if ($text->a < 1.0) $text = self::blend($background, $text);

    $rgbText = [$text->r, $text->g, $text->b];
    $rgbBack = [$background->r, $background->g, $background->b];

    // 1. Compute luminances
    $coeffs = [0.2126729, 0.7151522, 0.0721750];
    $gamma = 2.4;
    $Ltext = .0; $Lback = .0;
    foreach($coeffs as $i => $coeff) {
      $Ltext += pow($rgbText[$i], $gamma) * $coeff;
      $Lback += pow($rgbBack[$i], $gamma) * $coeff;
    }
    
    // 2. Clamp luminances
    $blackClampTrigger = 0.03;
    $blackClampPow = 1.45;
    $clamp = function($L) use ($blackClampTrigger, $blackClampPow) { return $L > $blackClampTrigger ? $L : $L + ($blackClampTrigger - $L) ** $blackClampPow; };
    $Ltext = $clamp($Ltext);
    $Lback = $clamp($Lback);

    $δLmin = 0.0005;
    if (abs($Ltext - $Lback) < $δLmin) return 0;

    // 3. Compute contrast
    $scale = 1.25;
    $compute = function($_Lback, $_Ltext, $_powBack, $_powText) use ($scale) { return (pow($_Lback, $_powBack) - pow($_Ltext, $_powText)) * $scale; };
    $lowClip = 0.001; $lowTrigger = 0.078; $lowOffset = 0.06; $invLowTrigger = 12.82051282051282;

    // for dark text on light background
    if ($Lback > $Ltext) {
      $powBack = 0.55; $powText = 0.58;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC < $lowClip) ? 0
              : ($SAPC < $lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC - $lowOffset;
    }

    // for light text on dark background
    else {
      $powBack = 0.62; $powText = 0.57;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC > -$lowClip) ? 0
              : ($SAPC > -$lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC + $lowOffset;
    }

    return $result * 100;
  }


  /** Computes the contrast between two colors as defined by WCAG2 or 3. */
  public static function contrast($text, $background, $method = 'WCAG2') {
    switch (strtolower($method)) {
      case 'wcag3': case 'sapc': case 'apca':
        return self::APCAcontrast($text, $background);
      default:
        return self::WCAG2contrast($text, $background);
    }
  }
  

  /** Checks if black or white text would have better contrast with {this}. */
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


  /** Modifies the CIE lightness of a color to give it better contrast with referenceColor. */
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
    if (($direction < 0 && $options->lower === false) || ($direction === 0)) return $this;

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
    if ($towardsWhite && !$towardsBlack)            $towards = 'white';
    elseif ($towardsBlack && !$towardsWhite)        $towards = 'black';
    elseif (!$towardsWhite && !$towardsBlack) {
      if ($options->towards !== null)               $towards = $options->towards;
      elseif ($contrastWhite > $contrastBlack)      return new self('white');
      else                                          return new self('black');
    }
    elseif ($towardsWhite && $towardsBlack)         $towards = $options->towards;
    if ($towards === null) {
      if ($refColor->ciel() < $movingColor->ciel())     $towards = 'white';
      elseif ($refColor->ciel() > $movingColor->ciel()) $towards = 'black';
      else $towards = 'black';
    }


    // We keep going as long as contrast is still below / over desiredContrast.
    $i = 0;
    while(($direction > 0) ? ($contrast < $desiredContrast) : ($contrast > $desiredContrast) && $i < $options->maxIterations) {
      $i++;

      // If movingColor is totally black (if towards black) or white (if towards white),
      // i.e. there's no way to go, stop.
      if (($towards === 'black' && $movingColor->ciel() === 0) || ($towards === 'white' && $movingColor->ciel() === 1))
        break;

      // Let's try to raise contrast by increasing or reducing CIE lightness.
      $sign = ($towards === 'white') ? 1 : -1;
      $newColor = $movingColor->replace('ciel', self::unparse('ciel', $movingColor->ciel() + $sign * .01 * $step));
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


  /** Calculates the distance between two colors in a certain format, by adding the difference between each of their properties. */
  public static function distance($_couleur1, $_couleur2) {
    $couleur1 = self::check($_couleur1);
    $couleur2 = self::check($_couleur2);

    $dist = 0;
    foreach(['r', 'g', 'b'] as $prop) {
      $dist += abs($couleur1->{$prop} - $couleur2->{$prop});
    }
    return $dist;
  }


  /** Determines if two colors are the same, with a certain tolerance. */
  public static function same($couleur1, $couleur2, $tolerance = self::TOLERANCE) {
    if (self::distance($couleur1, $couleur2) > $tolerance) return false;
    else return true;
  }


  /* Other functions */


  /** Calculates the intermediate colors a gradient should use to go from one color to another without passing through the "desaturated zone". */
  public static function gradient($_start, $_end, $_steps = 5, $format = 'lch') {
    $start = self::check($_start);
    $end = self::check($_end);
    $steps = min(max(1, $_steps), 100);
    $props = self::propertiesOf($format); $props[] = 'a';

    // Calculate by how much each property will be changed at each steap
    $stepList = array_map(function($prop) use ($start, $end, $steps) {
      switch ($prop) {
        case 'h': case 'cieh':
          // Minimize the distance to travel through hues
          $stepUp = (($end->{$prop}() - $start->{$prop}()) % 360 + 360) % 360;
          $stepDown = (($start->{$prop}() - $end->{$prop}()) % 360 + 360) % 360;
          $step = (($stepUp <= $stepDown) ? $stepUp : -$stepDown) / $steps;
          break;
        case 'r': case 'g': case 'b': case 'a':
          $step = ($end->{$prop} - $start->{$prop}) / $steps;
          break;
        default:
          $step = ($end->{$prop}() - $start->{$prop}()) / $steps;
      }
      return $step;
    }, $props);

    // Calculate all colors of the gradient
    $intermediateColors = [];
    switch ($format) {
      case 'rgb': $intermediateColors[] = [$start->{$props[0]}, $start->{$props[1]}, $start->{$props[2]}, $start->{$props[3]}]; break;
      default:    $intermediateColors[] = [$start->{$props[0]}(), $start->{$props[1]}(), $start->{$props[2]}(), $start->{$props[3]}];
    }
    for ($i = 1; $i < $steps; $i++) {
      $previous = $intermediateColors[$i - 1];
      $next = [];
      foreach($previous as $k => $prop) {
        $next[] = $prop + $stepList[$k];
      }
      $intermediateColors[] = $next;
      $previous = $next;
    }

    $gradient = [];
    foreach($intermediateColors as $c) {
      $gradient[] = new self(self::expr($format, $c));
    }
    $gradient[] = $end;
    return $gradient;
  }



  /**************/
  /* Color data */
  /**************/

  /** Gets the names of the properties of a color used in a certain format. */
  private static function propertiesOf($format) {
    switch($format) {
      case 'rgb': case 'rgba': return ['r', 'g', 'b'];
      case 'hsl': case 'hsla': return ['h', 's', 'l'];
      case 'hwb': case 'hwba': return ['h', 'w', 'bk'];
      case 'lab': case 'laba': return ['ciel', 'ciea', 'cieb'];
      case 'lch': case 'lcha': return ['ciel', 'ciec', 'cieh'];
      default: return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
    }
  }

  /** Array of all property names. */
  private static function properties() {
    return self::propertiesOf();
  }

  /** Tolerance value. Used for example as the maximum distance before two colors are considered different. */
  private const TOLERANCE = .02;

  /** Whether colors should be clamped to sRGB space. */
  public const MUST_CLAMP_TO_SRGB = true;

  /** Array of supported syntaxes. */
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

  // Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
  private const vNum = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?'; // number (r, g, b)
  private const vPer = self::vNum . '%'; // percent (r, g, b, s, l, w, bk)
  private const vNP = self::vNum . '%?'; // number or percent (a)
  private const vAng = self::vNum . '(?:deg|grad|rad|turn)?'; // angle (h)

  /** List of named colors in CSS. */
  public const COULEURS_NOMMEES = array('transparent' => '00000000', 'aliceblue' => 'f0f8ff', 'antiquewhite' => 'faebd7', 'aqua' => '00ffff', 'aquamarine' => '7fffd4', 'azure' => 'f0ffff', 'beige' => 'f5f5dc', 'bisque' => 'ffe4c4', 'black' => '000000', 'blanchedalmond' => 'ffebcd', 'blue' => '0000ff', 'blueviolet' => '8a2be2', 'brown' => 'a52a2a', 'burlywood' => 'deb887', 'cadetblue' => '5f9ea0', 'chartreuse' => '7fff00', 'chocolate' => 'd2691e', 'coral' => 'ff7f50', 'cornflowerblue' => '6495ed', 'cornsilk' => 'fff8dc', 'crimson' => 'dc143c', 'cyan' => '00ffff', 'darkblue' => '00008b', 'darkcyan' => '008b8b', 'darkgoldenrod' => 'b8860b', 'darkgray' => 'a9a9a9', 'darkgrey' => 'a9a9a9', 'darkgreen' => '006400', 'darkkhaki' => 'bdb76b', 'darkmagenta' => '8b008b', 'darkolivegreen' => '556b2f', 'darkorange' => 'ff8c00', 'darkorchid' => '9932cc', 'darkred' => '8b0000', 'darksalmon' => 'e9967a', 'darkseagreen' => '8fbc8f', 'darkslateblue' => '483d8b', 'darkslategray' => '2f4f4f', 'darkslategrey' => '2f4f4f', 'darkturquoise' => '00ced1', 'darkviolet' => '9400d3', 'deeppink' => 'ff1493', 'deepskyblue' => '00bfff', 'dimgray' => '696969', 'dimgrey' => '696969', 'dodgerblue' => '1e90ff', 'firebrick' => 'b22222', 'floralwhite' => 'fffaf0', 'forestgreen' => '228b22', 'fuchsia' => 'ff00ff', 'gainsboro' => 'dcdcdc', 'ghostwhite' => 'f8f8ff', 'gold' => 'ffd700', 'goldenrod' => 'daa520', 'gray' => '808080', 'grey' => '808080', 'green' => '008000', 'greenyellow' => 'adff2f', 'honeydew' => 'f0fff0', 'hotpink' => 'ff69b4', 'indianred' => 'cd5c5c', 'indigo' => '4b0082', 'ivory' => 'fffff0', 'khaki' => 'f0e68c', 'lavender' => 'e6e6fa', 'lavenderblush' => 'fff0f5', 'lawngreen' => '7cfc00', 'lemonchiffon' => 'fffacd', 'lightblue' => 'add8e6', 'lightcoral' => 'f08080', 'lightcyan' => 'e0ffff', 'lightgoldenrodyellow' => 'fafad2', 'lightgray' => 'd3d3d3', 'lightgrey' => 'd3d3d3', 'lightgreen' => '90ee90', 'lightpink' => 'ffb6c1', 'lightsalmon' => 'ffa07a', 'lightseagreen' => '20b2aa', 'lightskyblue' => '87cefa', 'lightslategray' => '778899', 'lightslategrey' => '778899', 'lightsteelblue' => 'b0c4de', 'lightyellow' => 'ffffe0', 'lime' => '00ff00', 'limegreen' => '32cd32', 'linen' => 'faf0e6', 'magenta' => 'ff00ff', 'maroon' => '800000', 'mediumaquamarine' => '66cdaa', 'mediumblue' => '0000cd', 'mediumorchid' => 'ba55d3', 'mediumpurple' => '9370d8', 'mediumseagreen' => '3cb371', 'mediumslateblue' => '7b68ee', 'mediumspringgreen' => '00fa9a', 'mediumturquoise' => '48d1cc', 'mediumvioletred' => 'c71585', 'midnightblue' => '191970', 'mintcream' => 'f5fffa', 'mistyrose' => 'ffe4e1', 'moccasin' => 'ffe4b5', 'navajowhite' => 'ffdead', 'navy' => '000080', 'oldlace' => 'fdf5e6', 'olive' => '808000', 'olivedrab' => '6b8e23', 'orange' => 'ffa500', 'orangered' => 'ff4500', 'orchid' => 'da70d6', 'palegoldenrod' => 'eee8aa', 'palegreen' => '98fb98', 'paleturquoise' => 'afeeee', 'palevioletred' => 'd87093', 'papayawhip' => 'ffefd5', 'peachpuff' => 'ffdab9', 'peru' => 'cd853f', 'pink' => 'ffc0cb', 'plum' => 'dda0dd', 'powderblue' => 'b0e0e6', 'purple' => '800080', 'rebeccapurple' => '663399', 'red' => 'ff0000', 'rosybrown' => 'bc8f8f', 'royalblue' => '4169e1', 'saddlebrown' => '8b4513', 'salmon' => 'fa8072', 'sandybrown' => 'f4a460', 'seagreen' => '2e8b57', 'seashell' => 'fff5ee', 'sienna' => 'a0522d', 'silver' => 'c0c0c0', 'skyblue' => '87ceeb', 'slateblue' => '6a5acd', 'slategray' => '708090', 'slategrey' => '708090', 'snow' => 'fffafa', 'springgreen' => '00ff7f', 'steelblue' => '4682b4', 'tan' => 'd2b48c', 'teal' => '008080', 'thistle' => 'd8bfd8', 'tomato' => 'ff6347', 'turquoise' => '40e0d0', 'violet' => 'ee82ee', 'wheat' => 'f5deb3', 'white' => 'ffffff', 'whitesmoke' => 'f5f5f5', 'yellow' => 'ffff00', 'yellowgreen' => '9acd32');  
}