<?php namespace colori {


  require_once __DIR__ . '/utils.php';
  require_once __DIR__ . '/conversion.php';
  require_once __DIR__ . '/graph.php';
  require_once __DIR__ . '/contrasts.php';
  require_once __DIR__ . '/distances.php';
  require_once __DIR__ . '/oklab-gamut.php';
  require_once __DIR__ . '/color-spaces.php';
  require_once __DIR__ . '/named-colors.php';
  require_once __DIR__ . '/css-formats.php';


  class Couleur
  {
    public float $r;
    public float $g;
    public float $b;
    public float $a;
    
    function __construct(self|array|string $color)
    {
      if ($color instanceof self) {
        $this->r = $color->r;
        $this->g = $color->g;
        $this->b = $color->b;
        $this->a = $color->a;
      }

      else if (is_array($color) && (count($color) === 3 || count($color) === 4)) {
        [$this->r, $this->g, $this->b] = self::toGamut('srgb', array_slice($color, 0, 3), 'srgb', method: 'naive');
        $this->a = $color[3] ?? 1;
      }

      else if (is_string($color)) {
        $format = self::matchSyntax(trim($color));

        switch ($format['id']) {
          case 'hex':
            $this->setHex([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 'ff']);
            break;
          case 'rgb':
          case 'hsl':
          case 'hwb':
          case 'lab':
          case 'lch':
          case 'oklab':
          case 'oklch':
            $values = [$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1];
            $props = self::propertiesOf($format['id']);
            $props[] = 'a';
            $space = self::getSpace($format['id']);
            $this->set($values, $props, $space);
            break;
          case 'color':
            $this->setColor($format['data'][1], [$format['data'][2], $format['data'][3], $format['data'][4], $format['data'][5] ?? 1]);
            break;
          default:
            throw new \Exception(json_encode($color) . 'is not a valid color format');
        }
      }

      else throw new \Exception(__CLASS__ . ' objects can only be created from a String ; this is not one: ' . json_encode($color));
    }


    /** Makes a Couleur from the argument if it's not one already. */
    protected static function makeInstance(self|array|string $color): self {
      if ($color instanceof self) return $color;
      else                        return new self($color);
    }



    /** Matches the user input with supported color formats. */
    private static function matchSyntax(string $colorString): array {
      $tri = substr($colorString, 0, 3);

      // Predetermine the format, to save regex-matching time
      if (substr($tri, 0, 1) === '#') $format = self::formats()[0];
      else switch ($tri) {
        case 'rgb': $format = self::formats()[1]; break;
        case 'hsl': $format = self::formats()[2]; break;
        case 'hwb': $format = self::formats()[3]; break;
        case 'lab': $format = self::formats()[4]; break;
        case 'lch': $format = self::formats()[5]; break;
        case 'okl':
          if (str_starts_with($colorString, 'oklab')) { $format = self::formats()[6]; break; }
          if (str_starts_with($colorString, 'oklch')) { $format = self::formats()[7]; break; }
          break;
        case 'col': $format = self::formats()[8]; break;
        default:    $format = self::formats()[9];
      }

      // Check if the given string matches any color syntax
      foreach($format['syntaxes'] as $syntaxe) {
        $result = preg_match($syntaxe, $colorString, $matches);
        if ($result === 1 && $matches[0] === $colorString) {
          if ($format['id'] === 'name') {
            if ($colorString === 'transparent') return ['id' => 'rgb', 'data' => ['', '0', '0', '0', '0']];
            $name = strtolower($colorString);
            if (array_key_exists($name, self::NAMED_COLORS))
              return self::matchSyntax('#' . self::NAMED_COLORS[strtolower($colorString)]);
          }
          return $resultat = ['id' => $format['id'], 'data' => $matches];
        }
      }

      throw new \Exception(json_encode($colorString) .' is not a valid color format');
    }


    /** Parses a number / percentage / angle into the correct format to store it. */
    private static function parse(string|float|int $value, ?string $prop = null, bool $clamp = true): float {
      $val = (string) $value;
      $nval = floatval($val);

      try {
        switch ($prop) {
          // Alpha values:
          // from any % or any number
          // clamped to [0, 100]% or [0, 1]
          // to [0, 1]
          case 'a':
            // If n is a percentage
            if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
              if ($clamp) return max(.0, min(floatval($value) / 100, 1.0));
              else        return floatval($value) / 100;
            }
            // If n is a number
            elseif (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              if ($clamp) return max(.0, min($value, 1.0));
              else        return floatval($value);
            }
            else throw new \Exception('invalid');

          // Red, green, blue values:
          // from any % or any number
          // clamped to [0, 100]% or [0, 255]
          // to [0, 1]
          case 'r': case 'g': case 'b':
            // If n is a percentage
            if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
              if ($clamp) return max(.0, min(floatval($value) / 100, 1.0));
              else        return floatval($value) / 100;
            }
            // If n is a number
            elseif (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              if ($clamp) return max(.0, min($value / 255.0, 1.0));
              else        return $value / 255.0;
            }
            else throw new \Exception('invalid');

          // Hue and CIE hue values:
          // from any angle or any number
          // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
          // to [0, 1]
          case 'h': case 'cieh': case 'okh':
            $h = floatval($value);
            // If n is a number
            if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              return (float) utils\angleToRange($h);
            }
            // If n is an angle
            elseif (preg_match('/^' . CSSFormats::RegExp('angle') . '$/', $value)) {
              if (substr($value, -3) === 'deg') {} // necessary to accept deg values
              elseif (substr($value, -3) === 'grad')
                $h = $h * 360 / 400;
              elseif (substr($value, -3) === 'rad')
                $h = $h * 180 / pi();
              elseif (substr($value, -3) === 'turn')
                $h = $h * 360;
              else throw new \Exception("Invalid angle value: ". json_encode($value));
              return (float) utils\angleToRange($h);
            }
            else throw new \Exception('invalid');

          // Percentage values:
          // from any %
          // clamped to [0, 100]%
          // to [0, 1]
          case 's': case 'l': case 'w': case 'bk': case 'ciel': case 'okl':
            // If n is a percentage
            if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
              if ($clamp) return max(0, min(floatval($value) / 100, 1));
              else        return floatval($value) / 100;
            }
            else throw new \Exception('invalid');

          // CIE axes values:
          // any number
          case 'ciea': case 'cieb':
            // If n is a number
            if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              return floatval($value);
            }
            else throw new \Exception('invalid');

          // CIE chroma values:
          // from any number
          // clamped to [0, +Inf[
          case 'ciec':
            // If n is a number
            if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              if ($clamp) return max(0, $value);
              else        return floatval($value);
            }
            else throw new \Exception('invalid');

          // OKLAB axes & chroma values:
          // any number
          case 'oka': case 'okb': case 'okc':
            // If n is a number
            if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              return floatval($value) / 100;
            }
            else throw new \Exception('invalid');

          // Arbitrary values
          // from any % or any number
          // to any number (so that 0% becomes 0 and 100% becomes 1)
          default:
            // If n is a percentage
            if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
              return floatval($value) / 100;
            }
            // If n is a number
            elseif (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
              return floatval($value);
            }
            else throw new \Exception('invalidest');
        }
      } catch (\Exception $error) {
        if ($error === 'invalid') throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
        else                      throw new \Exception("Invalid arbitrary value: ". json_encode($value));
      }
    }

    
    /** Unparses a value to the format that would be used in a CSS expression. */
    private static function unparse(float $value, string $prop, ?int $precision = 0): string {
      $v = $value;
      switch ($prop) {
        case 'r': case 'g': case 'b':
          $unparsed = $precision === null ? (255 * $value) : round(10**$precision * 255 * $value) / (10**$precision);
          break;
        case 's': case 'l': case 'w': case 'bk': case 'ciel':
          $unparsed = $precision === null ? (100 * $value).'%' : (round(10**$precision * 100 * $value) / (10**$precision)).'%';
          break;
        case 'oka': case 'okb': case 'okc':
          $unparsed = $precision === null ? (100 * $value) : round(10**$precision * 100 * $value) / (10**$precision);
        case 'a':
          $unparsed = $precision === null ? $value : round(10**max($precision, 2) * $value) / (10**max($precision, 2));
          break;
        default:
          $unparsed = $precision === null ? $value : round(10**$precision * $value) / (10**$precision);
      }
      return (string) $unparsed;
    }


    /** Calculates all properties of a color from given unparsed values in a given color space. */
    private function set(array $data, array $props, array|string $spaceID, bool $parsed = false): void {
      $space = self::getSpace($spaceID);
      $values = [];
      for ($i = 0; $i < count($props); $i++) {
        $values[] = $parsed ? $data[$i] : self::parse($data[$i], $props[$i]);
      }

      [$this->r, $this->g, $this->b] = self::convert($space, 'srgb', $values);
      $this->a = self::parse($data[3] ?? 1, 'a');
    }


    /** Calculates all properties of the color from its hexadecimal expression. */
    private function setHex(array $hexa): void {
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

      $this->set([$r, $g, $b, $a], ['r', 'g', 'b'], 'srgb');
    }


    /** Calculates all properties of the color from its functional color() expression. */
    private function setColor(string $spaceID, array $values): void {
      $vals = array_slice($values, 0, 3);
      $a = $values[3];

      switch ($spaceID) {
        case 'srgb': case 'display-p3': case 'a98-rgb': case 'prophoto-rgb': case 'rec2020':
        case 'oklab': case 'oklch':
        case 'xyz':
          $vals = self::convert($spaceID, 'srgb', $vals);
          break;
        default:
          if (str_starts_with($spaceID, '--')) {
            $id = substr($spaceID, 2);
            $vals = self::convert($id, 'srgb', $vals);
          }
          else throw new \Exception("The ". json_encode($spaceID) ." color space is not supported");
      }

      $rgba = $vals; $rgba[] = $a;
      $this->set($rgba, [null, null, null], 'srgb');
    }



    /*****************************/
    /* Getters for color formats */
    /*****************************/


    /* GENERAL EXPRESSION GETTER */

    /** Creates a string containing the CSS expression of a color. */
    public function expr(array|string $format, ?int $precision = 0, bool $clamp = true): string {
      $spaceID = is_string($format) ? str_replace('color-', '', $format) : $format;
      $space = self::getSpace($spaceID);
      $values = $this->valuesTo($space);
      if ($clamp) $values = self::toGamut($space, $values, $space);
      $a = self::unparse($this->a, 'a', precision: $precision);
      $values[] = $a;

      // If the requested expression is of the color(space, ...) type
      if (substr($format, 0, 5) === 'color') {
        $string = "color(".$space['id'];
        foreach($values as $k => $v) {
          if ((float) $k === count($values) - 1) {
            if ($a >= 1) break;
            $string .= " / $a";
          } else {
            $string .= " ". ($precision === null ? $v : round(10**$precision * $v) / (10**$precision));
          }
        }
        $string .= ")";
        return $string;
      }

      // If the requested expression is of the ${format}(...) type
      $props = self::propertiesOf($format);
      $unparsed = [];
      foreach($props as $k => $p) {
        $unparsed[] = self::unparse($values[$k], $p, precision: $precision);
      }
      [$x, $y, $z] = $unparsed;

      switch ($format) {
        case 'rgb': case 'rgba': case 'hsl': case 'hsla':
          if ((strlen($format) > 3 && substr($format, -1) === 'a') || $a < 1.0)
            return "${format}(${x}, ${y}, ${z}, ${a})";
          else
            return "${format}(${x}, ${y}, ${z})";
        default:
          if ($a < 1.0) return "${format}(${x} ${y} ${z} / ${a})";
          else          return "${format}(${x} ${y} ${z})";
      }
    }

    /** Creates a string containing the CSS expression of a color from a list of values. */
    public static function makeExpr(string $format, array $values, array|string $valueSpaceID, ?int $precision = 0, bool $clamp = true): string {
      $spaceID = is_string($format) ? str_replace('color-', '', $format) : $format;
      $rgba = self::convert($valueSpaceID, $spaceID, array_slice($values, 0, 3)); $rgba[] = $this->a;
      return (new self($rgba))->expr($format, precision: $precision, clamp: $clamp);
    }


    /* ALL VALUES (r, g, b) */

    public function values() { return [$this->r, $this->g, $this->b]; }


    /* NAME */

    /** The approximate name of the color. */
    public function name(): ?string {
      if ($this->a === 1.0) {
        $allNames = self::NAMED_COLORS;
        [$r, $g, $b] = [255 * $this->r, 255 * $this->g, 255 * $this->b];
        $tolerance = 255 * .02;
        foreach($allNames as $name => $hex) {
          [$r2, $g2, $b2] = [intval(hexdec($hex[0].$hex[1])), intval(hexdec($hex[2].$hex[3])), intval(hexdec($hex[4].$hex[5]))];
          if (abs($r2 - $r) + abs($g2 - $g) + abs($b2 - $b) < $tolerance) return $name;
        }
        return null;
      }
      else if ($this->a === 0) return 'transparent';
      else                     return null;
    }

    /** The exact name of the color. */
    public function exactName(): ?string {
      if ($this->a === 1) {
        $hex6 = substr($this->hex(), 1);
        $name = array_search($hex6, self::NAMED_COLORS);
        return $name ?? null;
      }
      elseif ($this->a === 0)  return 'transparent';
      else                     return null;
    }


    /* CSS FORMATS */

    /** Hexadecimal expression of the color. */
    public function hex(): string {
      $values = self::toGamut('srgb', $this->values());
      $values[] = $this->a;
      foreach($values as $k => $v) {
        $values[$k] = utils\pad(dechex(round($v * 255)));
      }
      [$r, $g, $b, $a] = $values;
      if ($this->a < 1) return '#'.$r.$g.$b.$a;
      else              return '#'.$r.$g.$b;
    }

    public function rgb(): string { return $this->expr('rgb', precision: 2); }
    public function rgba(): string { return $this->rgb(); }

    public function hsl(): string { return $this->expr('hsl', precision: 2); }
    public function hsla(): string { return $this->hsl(); }

    public function hwb(): string { return $this->expr('hwb', precision: 2); }

    public function lab(): string { return $this->expr('lab', precision: 2); }

    public function lch(): string { return $this->expr('lch', precision: 2); }

    public function oklab(): string { return $this->expr('oklab', precision: 2); }

    public function oklch(): string { return $this->expr('oklch', precision: 2); }



    /********************************************/
    /* Setters and getters for color properties */
    /********************************************/


    /** Recalculates the r, g, b properties of the color after modifying one of its other properties. */
    private function recompute(float | string $val, string $prop, string $format): void {
      $props = self::propertiesOf($format); $props[] = 'a';
      if (!in_array($prop, $props))
        throw new \Exception("Format $format does not have a property called $prop");

      $parsedVal = is_string($val) ? self::parse($val, $prop) : $val;
      $oldValues = $this->valuesTo($format); $oldValues[] = $this->a;
      $newValues = [];
      foreach($props as $k => $p) {
        if ($p === $prop) $newValues[] = $parsedVal;
        else              $newValues[] = $oldValues[$k];
      }
      $this->set($newValues, $props, $format, parsed: true);
    }


    private function setR(float | string $val): void { $this->r = $val; }
    private function setRed(float | string $val): void { $this->setR($val); }

    private function setG(float | string $val): void { $this->g = $val; }
    private function setGreen(float | string $val): void { $this->setG($val); }

    private function setB(float | string $val): void { $this->b = $val; }
    private function setBlue(float | string $val): void { $this->setB($val); }

    private function setA(float | string $val): void { $this->a = $val; }
    private function setAlpha(float | string $val): void { $this->setA($val); }
    private function setOpacity(float | string $val): void { $this->setA($val); }

    private function setH(float | string $val): void { $this->recompute($val, 'h', 'hsl'); }
    private function setHue(float | string $val): void { $this->setH($val); }

    private function setS(float | string $val): void { $this->recompute($val, 's', 'hsl'); }
    private function setSaturation(float | string $val): void { $this->setS($val); }

    private function setL(float | string $val): void { $this->recompute($val, 'l', 'hsl'); }
    private function setLightness(float | string $val): void { $this->setL($val); }

    private function setW(float | string $val): void { $this->recompute($val, 'w', 'hwb'); }
    private function setWhiteness(float | string $val): void { $this->setW($val); }

    private function setBk(float | string $val): void { $this->recompute($val, 'bk', 'hwb'); }
    private function setBlackness(float | string $val): void { $this->setBk($val); }

    private function setCiel(float | string $val): void { $this->recompute($val, 'ciel', 'lab'); }
    private function setCIELightness(float | string $val): void { $this->setCiel($val); }

    private function setCiea(float | string $val): void { $this->recompute($val, 'ciea', 'lab'); }

    private function setCieb(float | string $val): void { $this->recompute($val, 'cieb', 'lab'); }

    private function setCiec(float | string $val): void { $this->recompute($val, 'ciec', 'lch'); }
    private function setCIEChroma(float | string $val): void { $this->setCiec($val); }

    private function setCieh(float | string $val): void { $this->recompute($val, 'cieh', 'lch'); }
    private function setCIEHue(float | string $val): void { $this->setCieh($val); }

    private function setOkl(float | string $val): void { $this->recompute($val, 'okl', 'oklab'); }
    private function setOKLightness(float | string $val): void { $this->setOkl($val); }

    private function setOka(float | string $val): void { $this->recompute($val, 'oka', 'oklab'); }

    private function setOkb(float | string $val): void { $this->recompute($val, 'okb', 'oklab'); }

    private function setOkc(float | string $val): void { $this->recompute($val, 'okc', 'oklch'); }
    private function setOKChroma(float | string $val): void { $this->setOkc($val); }

    private function setOkh(float | string $val): void { $this->recompute($val, 'okh', 'oklch'); }
    private function setOKHue(float | string $val): void { $this->setOkh($val); }

    /** Gets the parsed value of one of the color properties. */
    public function red(): float { return $this->r; }
    public function green(): float { return $this->g; }
    public function blue(): float { return $this->b; }
    public function alpha(): float { return $this->a; }
    public function opacity(): float { return $this->a; }
    public function h(): float { return $this->valuesTo('hsl')[0]; }
    public function hue(): float { return $this->h(); }
    public function s(): float { return $this->valuesTo('hsl')[1]; }
    public function saturation(): float { return $this->s(); }
    public function l(): float { return $this->valuesTo('hsl')[2]; }
    public function lightness(): float { return $this->l(); }
    public function w(): float { return $this->valuesTo('hwb')[1]; }
    public function whiteness(): float { return $this->w(); }
    public function bk(): float { return $this->valuesTo('hwb')[2]; }
    public function blackness(): float { return $this->bk(); }
    public function ciel(): float { return $this->valuesTo('lab')[0]; }
    public function CIElightness(): float { return $this->ciel(); }
    public function ciea(): float { return $this->valuesTo('lab')[1]; }
    public function cieb(): float { return $this->valuesTo('lab')[2]; }
    public function ciec(): float { return $this->valuesTo('lch')[1]; }
    public function CIEchroma(): float { return $this->ciec(); }
    public function cieh(): float { return $this->valuesTo('lch')[2]; }
    public function CIEhue(): float { return $this->cieh(); }
    public function okl(): float { return $this->valuesTo('oklab')[0]; }
    public function OKlightness(): float { return $this->okl(); }
    public function oka(): float { return $this->valuesTo('oklab')[1]; }
    public function okb(): float { return $this->valuesTo('oklab')[2]; }
    public function okc(): float { return $this->valuesTo('oklch')[1]; }
    public function OKchroma(): float { return $this->okc(); }
    public function okh(): float { return $this->valuesTo('oklch')[2]; }
    public function OKhue(): float { return $this->okh(); }

    public function setLuminance(float | string $val): void {
      // Scale r, g, b to reach the desired luminance value
      [$r, $g, $b] = $this->values();
      $oldLum = $this->luminance();
      $newLum = self::parse($val, 'a', clamp: true);

      if ($oldLum === 0) {
        $this->r = $newLum;
        $this->g = $newLum;
        $this->b = $newLum;
      } else {
        $ratio = $newLum / $oldLum;
        $this->r = $ratio * $r;
        $this->g = $ratio * $g;
        $this->b = $ratio * $b;
      }
    }

    public function luminance(): float {
      if ($this->a < 1) throw new \Exception('The luminance of a transparent color would be meaningless');
      return contrasts\luminance($this->values());
    }



    /***********************************/
    /* Conversion between color spaces */
    /***********************************/


    /** Converts the color values from one color space to another. */
    public static function convert(array|string $startSpaceID, array|string $endSpaceID, array $values): array {
      if ((is_string($startSpaceID) ? $startSpaceID : $startSpaceID['id']) === (is_string($endSpaceID) ? $endSpaceID : $endSpaceID['id'])) return $values;
      $startSpace = self::getSpace($startSpaceID);
      $endSpace = self::getSpace($endSpaceID);

      // Find the shortest sequence of functions to convert between color spaces
      $graph = new Graph(self::COLOR_SPACES);
      try {
        $path = $graph->shortestPath($startSpace['id'], $endSpace['id']);
        $path = array_map(function ($node) { return $node->id(); }, $path);
      }
      catch (\Exception $error) {
        switch ($error) {
          case 'No path found': throw new \Exception("Conversion from ". json_encode($startSpaceID) ." space to ". json_encode($endSpaceID) ." space is impossible");
          case 'start does not exist': throw new \Exception(json_encode($startSpaceID) ." is not a supported color space");
          case 'end does not exist': throw new \Exception(json_encode($endSpaceID) ." is not a supported color space");
          default: throw new \Exception($error);
        }
      }

      // Apply these functions to the color values.
      $result = $values;
      while (count($path) > 1) {
        $start = array_shift($path);
        $end = $path[0];
        $functionName = str_replace('-', '', "\\colori\\conversions\\${start}_to_${end}");
        $result = $functionName($result);
      }

      return $result;
    }


    /** Converts the r, g, b values of the color to another color space or CSS format. */
    public function valuesTo(array|string $spaceID, bool $clamp = false): array {
      $space = self::getSpace($spaceID);
      $values = self::convert('srgb', $space, $this->values());
      if ($clamp) $values = self::toGamut($space, $values);
      return $values;
    }


    /* Clamping to a color space */


    /** Checks whether parsed values in valueSpace color space correspond to a color in the spaceID color space. */
    public static function inGamut(array|string $spaceID, array $values, array|string $valueSpaceID = 'srgb', float $tolerance = .0001): bool {
      $space = self::getSpace($spaceID);
      $convertedValues = self::convert($valueSpaceID, $space, $values);
      foreach($convertedValues as $k => $v) {
        if ($v < ($space['gamut'][$k][0] - $tolerance) || $v > $space['gamut'][$k][1] + $tolerance) return false;
      }
      return true;
    }


    /** Clamps parsed values in valueSpaceID color space to the spaceID color space. */
    public static function toGamut(array|string $spaceID, array $values, array|string $valueSpaceID = 'srgb', string $method = 'oklab'): array {
      $space = self::getSpace($spaceID);
      $valueSpace = self::getSpace($valueSpaceID);
      if (self::inGamut($space, $values, $valueSpace, tolerance: 0)) return $values;

      // Naively clamp the values
      if ($method === 'naive') {
        $clampSpace = $space;
        $convertedValues = self::convert($valueSpace, $clampSpace, $values);
        $clampedValues = [];
        foreach($convertedValues as $k => $v) {
          $clampedValues[] = max($space['gamut'][$k][0], min($v, $space['gamut'][$k][1]));
        }
      }

      // OKLab gamut clipping
      elseif ($method === 'oklab') {
        $clampSpace = self::getSpace('srgb');
        $rgb = self::convert($valueSpace, $clampSpace, $values);
        $clampedValues = oklab_gamut\clip($rgb);
      }

      // Let's reduce the chroma until the color is in the color space
      elseif ($method === 'chroma') {
        $clampSpace = self::getSpace('lch');
        $lch = self::convert($valueSpace, $clampSpace, $values);

        $τ = .01;
        $Cmin = 0;
        $Cmax = $lch[1];
        $lch[1] = $lch[1] / 2;

        while($Cmax - $Cmin > $τ) {
          $naive = self::toGamut($space, $lch, $clampSpace, method: 'naive');

          // If the color is close to the color space border
          if (self::distance($naive, $lch, method: 'CIEDE2000') < 2 + $τ)
            $Cmin = $lch[1];
          else
            $Cmax = $lch[1];
          $lch[1] = ($Cmin + $Cmax) / 2;
        }

        $clampedValues = $lch;
      }

      // Final naive clamp to get in the color space if the color is still just outside the border
      if ($method !== 'naive') $clampedValues = self::toGamut($space, $clampedValues, $clampSpace, method: 'naive');

      // Send the values back in the same color space we found them in
      return self::convert($clampSpace, $valueSpace, $clampedValues);
    }



    /********************************/
    /* Color manipulation functions */
    /********************************/


    /* Color modification */


    /** Modifies a color by changing a specific property. */
    public function change(string $prop, string|float|int $value, ?string $action = null): self {
      $replace = $action === 'replace';
      $scale = $action === 'scale';
      $val = $scale ? self::parse($value) : self::parse($value, $prop, clamp: false);
      $changedColor = new self($this);

      $oldVal = match ($prop) {
        'r', 'g', 'b', 'a' => $this->{$prop},
        default => $this->{$prop}()
      };
      $newVal = $replace ? $val : ($scale ? $oldVal * $val : $oldVal + $val);
      $methodName = "set".ucfirst($prop);
      $changedColor->{$methodName}($newVal);
      return $changedColor;
    }

    /** Modifies a color by replacing the value of a specific property. */
    public function replace(string $prop, string|float|int $value): self {
      return $this->change($prop, $value, action: 'replace');
    }

    /** Modifies a color by scaling the value of a specific property by a percentage. */
    public function scale(string $prop, string|float|int $value): self {
      return $this->change($prop, $value, action: 'scale');
    }

    /** The complementary color. */
    public function complement(): self { return $this->change('h', 180); }

    /** The inverse color. */
    public function negative(): self {
      return new self('rgb(' . 255 * (1 - $this->r) . ', ' . 255 * (1 - $this->g) . ', ' . 255 * (1 - $this->b) . ', ' . $this->a . ')');
    }
    public function invert(): self { return $this->negative(); }

    /** The shade of grey of the color. */
    public function greyscale(): self {
      $L = 255 * $this->replace('a', 1)->luminance();
      $a = $this->a;
      return new self("rgb(${L}, ${L}, ${L}, ${a})");
    }
    public function grayscale(): self { return $this->greyscale(); }

    /** The sepia tone of the color. */
    public function sepia(): self {
      $r = min(0.393 * $this->r + 0.769 * $this->g + 0.189 * $this->b, 1);
      $g = min(0.349 * $this->r + 0.686 * $this->g + 0.168 * $this->b, 1);
      $b = min(0.272 * $this->r + 0.534 * $this->g + 0.131 * $this->b, 1);
      return new self('rgb('. 255 * $r .', '. 255 * $g .', '. 255 * $b .', '. $this->a .')');
    }


    /* Color blending */


    /** Blends two colors together. */
    public static function blend(self|array|string $backgroundColor, self|array|string $overlayColor, ?float $alpha = null): self {
      $background = self::makeInstance($backgroundColor);
      $overlay = self::makeInstance($overlayColor);
      if ($alpha != null) // if alpha isn't null or undefined
        $overlay->a = self::parse($alpha, 'a');

      if ($overlay->a === .0) return $background;
      else if ($overlay->a === .1) return $overlay;

      $a = $overlay->a + $background->a * (1 - $overlay->a);
      $r = ($overlay->r * $overlay->a + $background->r * $background->a * (1 - $overlay->a)) / $a;
      $g = ($overlay->g * $overlay->a + $background->g * $background->a * (1 - $overlay->a)) / $a;
      $b = ($overlay->b * $overlay->a + $background->b * $background->a * (1 - $overlay->a)) / $a;
      return new self([$r, $g, $b, $a]);
    }

    /** Blends colors together, in the order they were given. */
    public static function blendAll(self|array|string ...$colors): self {
      if (count($colors) < 2) throw new \Exception("You need at least 2 colors to blend");
      $background = new self(array_shift($colors));
      $overlay = new self(array_shift($colors));

      $mix = self::blend($background, $overlay);

      if (count($colors) === 0)  return $mix;
      else                         return self::blendAll($mix, ...$colors);
    }


    /** Solves the equation mix = blend(background, overlay) with background unknown. */
    public static function unblend(self|array|string $mixColor, self|array|string $overlayColor, ?float $alpha = null): ?self {
      $mix = self::makeInstance($mixColor);
      $overlay = self::makeInstance($overlayColor);
      if ($alpha != null) // if alpha isn't null or undefined
        $overlay->a = self::parse($alpha, 'a');

      if ($overlay->a === 1.0) {
        throw new \Exception("Overlay color ". json_encode($overlay->rgb()) ." isn't transparent, so the background it was blended onto could have been any color");
      }
      elseif ($overlay->a === .0)           return $mix;
      else {
        if ($mix->a < $overlay->a)         return null;
        elseif ($mix->a === $overlay->a) {
          if (self::same($mix, $overlay))  return new self('transparent');
          else                             return null;
        }
        else {
          $a = ($mix->a - $overlay->a) / (1 - $overlay->a);
          $r = ($mix->r * $mix->a - $overlay->r * $overlay->a) / ($a * (1 - $overlay->a));
          $g = ($mix->g * $mix->a - $overlay->g * $overlay->a) / ($a * (1 - $overlay->a));
          $b = ($mix->b * $mix->a - $overlay->b * $overlay->a) / ($a * (1 - $overlay->a));
          $clampedValues = self::toGamut('srgb', [$r, $g, $b], 'srgb');
          $clampedValues[] = $a;
          return new self($clampedValues);
        }
      }
    }


    /** Solves the equation mix = blend(background, ...overlays) with background unknown. */
    public static function unblendAll(self|array|string ...$colors): ?self {
      if (count($colors) < 2) throw new \Exception("You need at least 2 colors to blend");
      $mix = new self(array_shift($colors));
      $overlay = new self(array_shift($colors));

      $background = self::unblend($mix, $overlay);

      if (count($colors) === 0) return $background;
      else return self::unblendAll($background, ...$colors);
    }


    /** Solves the equation mix = blend(background, overlay) with overlay unknown. */
    public static function whatToBlend(self|array|string $backgroundColor, self|array|string $overlayColor, array|float $alphas = [], bool $ignoreTransparent = false): self|array|null {
      $background = self::makeInstance($backgroundColor);
      $mix = self::makeInstance($overlayColor);
      $overlays = [];

      $calculateSolution = function($a) use ($mix, $background) {
        $r = ($mix->r * $mix->a - $background->r * $background->a * (1 - $a)) / $a;
        $g = ($mix->g * $mix->a - $background->g * $background->a * (1 - $a)) / $a;
        $b = ($mix->b * $mix->a - $background->b * $background->a * (1 - $a)) / $a;
        if (!self::inGamut('srgb', [$r, $g, $b], 'srgb', tolerance: 1/255)) throw new \Exception("This color doesn't exist");
        $clampedValues = self::toGamut('srgb', [$r, $g, $b], 'srgb', method: 'naive');
        $clampedValues[] = $a;
        return new self($clampedValues);
      };

      $defaultAlphas = [];
      for ($i = .1; $i <= .9; $i += 0.1) { $defaultAlphas[] = $i; }
      $requestedAlphas = is_array($alphas) ? $alphas : [$alphas];
      $computedAlphas = count($requestedAlphas) > 0 ? array_filter($requestedAlphas, fn($a) => $a > .0 && $a < 1.0)
                                                    : $defaultAlphas;

      // The mix can't have lower opacity than the background
      if ($mix->a < $background->a)    return null;
      // If the mix is more opaque than the background...
      elseif ($mix->a > $background->a) {
        // If the background is partially transparent and the mix is opaque, the mix is the only solution
        // (any partially transparent overlay would have mixed with the background to make a partially transparent mix)
        if ($mix->a === 1.0)           $overlays[] = $mix;
        // If the background is totally transparent and the mix is partially transparent, the mix is the only solution
        // (any other color mixed with nothing would make itself)
        elseif ($background->a === .0) $overlays[] = $mix;
        // If the background is partially transparent and the mis is too, but more opaque, then there exists a unique solution
        else {
          $a = ($mix->a - $background->a) / (1.0 - $background->a);
          try { $overlays[] = $calculateSolution($a); }
          catch (\Throwable $error) { return null; }
        }
      }
      // If the mix is as opaque as the background...
      elseif ($mix->a === $background->a) {
        // If both the mix and the background are totally transparent, 'transparent' is the only solution
        // (any other color would have raised the opacity)
        if ($mix->a === .0) $overlays[] = new self('transparent');
        // If both the mix and the background are partially transparent with the same opacity, then
        // if they're the same color, 'transparent' is solution. If not, there is no solution.
        else if ($mix->a < 1.0) {
          if (self::same($mix, $background)) $overlays[] = new self('transparent');
          else                               return null;
        }
        // If both mix and background are totally opaque, then there is an infinity of solutions
        // (one per alpha value from 0 (included only if same color) to 1). Let's calculate the ones
        // whose alpha value was passed in the alphas argument, or those in [0, 0.1, 0.2, ..., 0.9, 1]
        // if alphas === null.
        else {
          if (self::same($mix, $background)) $overlays[] = new self('transparent');
          foreach ($computedAlphas as $a) {
            try { $overlays[] = $calculateSolution($a); }
            catch (\Throwable $error) { continue; }
          }
          $overlays[] = $mix;
        }
      }

      $result = count($requestedAlphas) > 0 ? array_filter($overlays, fn($c) => in_array($c->a, $requestedAlphas))
                                            : $overlays;
      if ($ignoreTransparent) $result = array_filter($result, fn($a) => $a > .0);

      return count($result) === 0 ? null
          : (count($result) === 1 ? $result[0]
          : $result);
    }


    /* Color comparison */


    /** Computes the contrast between two colors as defined by WCAG2 or 3. */
    public static function contrast(self|array|string $textColor, self|array|string $backgroundColor, string $method = 'APCA'): float {
      $background = self::makeInstance($backgroundColor);
      if ($background->a < 1) throw new \Exception('The contrast with a transparent background color would be meaningless');
      $text = self::makeInstance($textColor);

      // If the text is transparent, blend it to the background to get its actual visible color
      if ($text->a < 1) $text = self::blend($background, $text);

      switch (strtolower($method)) {
        case 'wcag3': case 'sapc': case 'apca':
          return contrasts\APCA($text->values(), $background->values());
        default:
          return contrasts\WCAG2($text->values(), $background->values());
      }
    }
    

    /** Determines which color scheme ('light' or 'dark') would lead to a better contrast with the color. */
    public function bestColorScheme(string $as = 'background'): string {
      $rgba = self::toGamut('srgb', $this->values()); $rgba[] = $this->a;
      if ($as === 'text') {
        $Cblack = abs(self::contrast($rgba, 'black', method: 'apca'));
        $Cwhite = abs(self::contrast($rgba, 'white', method: 'apca'));
        return ($Cblack >= $Cwhite) ? 'dark' : 'light';
      } else {
        $Cblack = abs(self::contrast('black', $rgba, method: 'apca'));
        $Cwhite = abs(self::contrast('white', $rgba, method: 'apca'));
        return ($Cblack >= $Cwhite) ? 'light' : 'dark';
      }
    }


    /** Modifies the CIE lightness of a color to give it better contrast with a background color. */
    public function improveContrast(self|array|string $backgroundColor, float $desiredContrast, bool $lower = false, ?string $colorScheme = null, string $method = 'APCA'): self {
      $background = self::makeInstance($backgroundColor);
      $values = $this->values(); $values[] = $this->a;
      $backgroundLab = $background->valuesTo('oklab');
      $movingLab = $this->valuesTo('oklab');

      // Let's measure the initial contrast
      // and decide if we want it to go up or down.
      $contrast = self::contrast($this, $background, method: $method);
      if ($contrast > $desiredContrast)     $directionContrast = -1;
      elseif ($contrast < $desiredContrast) $directionContrast = 1;
      else                                  $directionContrast = 0;
      // If the contrast is already higher than desired, and lowering it is not allowed, return the color as is.
      if (($directionContrast < 0 && $lower === false) || ($directionContrast === 0)) return $this;

      // Let's detect the color scheme if it isn't given.
      $colorScheme = $colorScheme ?? (($backgroundLab[0] < $movingLab[0]) ? 'dark' : 'light');

      // Let's measure the contrast of refColor with black and white to know if
      // desiredContrast can be reached by lowering or raising the color's CIE lightness.
      $cBlack = self::contrast($background, 'black', method: $method);
      $cWhite = self::contrast($background, 'white', method: $method);
      $isPossible = [
        'lowering' => ($directionContrast > 0) ? abs($cBlack) >= $desiredContrast : abs($cBlack) <= $desiredContrast,
        'raising' => ($directionContrast > 0) ? abs($cWhite) >= $desiredContrast : abs($cWhite) <= $desiredContrast
      ];
      
      // Let's decide which direction to move the lightness in.
      if ($isPossible['lowering'] && !$isPossible['raising'])      $directionOKL = -1;
      else if ($isPossible['raising'] && !$isPossible['lowering']) $directionOKL = 1;
      // If desiredContrast can not be reached, return white or black — the one that fits the color scheme.
      else if (!$isPossible['raising'] && !$isPossible['lowering']) {
        if ($colorScheme === 'light') return new self('black');
        else                          return new self('white');
      }
      // If desiredContrast can be reached in both directions
      else {
        // If the background is light and we need to raise the contrast, lower the lightness.
        if ($colorScheme === 'light' && $directionContrast > 0)      $directionOKL = -1;
        // If the background is light and we need to lower the contrast, raise the lightness.
        else if ($colorScheme === 'light' && $directionContrast < 0) $directionOKL = 1;
        // If the background is dark and we need to raise the contrast, raise the lightness.
        else if ($colorScheme === 'dark' && $directionContrast > 0)  $directionOKL = 1;
        // If the background is dark and we need to lower the contrast, lower the lightness.
        else                                                         $directionOKL = -1;
      }

      $τ = .0001;
      $OKLmin = ($directionOKL > 0) ? $movingLab[0] : 0;
      $OKLmax = ($directionOKL > 0) ? 1 : $movingLab[0];

      while ($OKLmax - $OKLmin > $τ) {
        // Let's try to raise contrast by increasing or reducing CIE lightness.
        $okl = ($OKLmin + $OKLmax) / 2;
        $newValues = $movingLab; $newValues[0] = $okl;
        $newContrast = self::contrast(self::convert('oklab', 'srgb', $newValues), $background, method: $method);

        // If the new contrast hasn't gone over its desired value
        $condition = ($directionContrast > 0) ? (abs($newContrast) < $desiredContrast) : (abs($newContrast) > $desiredContrast);
        if ($condition) {
          if ($directionOKL > 0) $OKLmin = $okl;
          else                   $OKLmax = $okl;
        }
        // If we overshot and the contrast moved further than we want it to
        else {
          if ($directionOKL > 0) $OKLmax = $okl;
          else                   $OKLmin = $okl;
        }

        $movingLab[0] = $okl;
      }

      $result = new self(self::convert('oklab', 'srgb', $movingLab));
      // If the color we find has its contrast slightly below the desired value, push it further.
      if (abs(self::contrast($result, $background, method: $method)) < $desiredContrast) {
        if ($directionOKL > 0) $movingLab[0] = $OKLmax;
        else                   $movingLab[0] = $OKLmin;
      }

      // We're done!
      return new self(self::convert('oklab', 'srgb', $movingLab));
    }


    /** Calculates the distance between two colors in a certain format, by adding the difference between each of their properties. */
    public static function distance(self|array|string $color1, self|array|string $color2, string $method = 'deltaE2000', bool $alpha = true): float {
      $color1 = self::makeInstance($color1);
      $color2 = self::makeInstance($color2);
      $opaqueDist = +INF;
      $alphaCoeff = 1.0;

      switch ($method) {
        case 'CIEDE2000': case 'deltaE2000':
          $lab1 = $color1->valuesTo('lab');
          $lab2 = $color2->valuesTo('lab');
          $opaqueDist = distances\CIEDE2000($lab1, $lab2);
          $alphaCoeff = 50.0;
          break;
        case 'deltaEOK':
          $oklab1 = $color1->valuesTo('oklab');
          $oklab2 = $color2->valuesTo('oklab');
          $opaqueDist = distances\euclidean($oklab1, $oklab2);
          break;
        case 'euclidean':
        default:
          $rgb1 = $colors1->values();
          $rgb2 = $colors2->values();
          $opaqueDist = distances\euclidean($rgb1, $rgb2);
      }

      $alphaDist = $alpha ? distances\euclidean([$color1->a], [$color2->a]) : 0;
      return $opaqueDist + $alphaCoeff * $alphaDist;
    }


    /** Determines if two colors are the same, with a certain tolerance. */
    public static function same(self|array|string $color1, self|array|string $color2, float $tolerance = 1, string $method = 'deltaE2000'): bool {
      if (self::distance($color1, $color2, method: $method) > $tolerance) return false;
      else return true;
    }


    /* Other functions */


    /** Calculates the intermediate colors a gradient should use to go from one color to another without passing through the "desaturated zone". */
    public static function gradient(self|array|string $startColor, self|array|string $endColor, int $steps = 5, array|string $spaceID = 'lch'): array {
      $start = self::makeInstance($startColor);
      $end = self::makeInstance($endColor);
      $steps = max(1, $steps);
      $props = self::propertiesOf($spaceID); $props[] = 'a';
      $space = self::getSpace($spaceID);
      $startValues = $start->valuesTo($space); $startValues[] = $start->a;

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
      $intermediateColors = [$startValues];
      for ($i = 1; $i < $steps; $i++) {
        $previous = $intermediateColors[$i - 1];
        $next = [];
        foreach($props as $k => $prop) {
          $v = $previous[$k] + $stepList[$k];
          if (in_array($prop, ['h', 'cieh'])) $next[] = utils\angleToRange($v);
          else $next[] = $v;
        }
        $a = $next[3];
        $next = self::toGamut($space, array_slice($next, 0, 3), $space);
        $next[] = $a;
        $intermediateColors[] = $next;
      }

      $gradient = [];
      foreach($intermediateColors as $c) {
        $gradient[] = new self(self::convert($space, 'srgb', $c));
      }
      $gradient[] = $end;
      return $gradient;
    }



    /**************/
    /* Color data */
    /**************/

    /** Gets the names of the properties of a color used in a certain format. */
    protected static function propertiesOf(string $format): array {
      switch($format) {
        case 'rgb': case 'rgba': return ['r', 'g', 'b'];
        case 'hsl': case 'hsla': return ['h', 's', 'l'];
        case 'hwb':              return ['h', 'w', 'bk'];
        case 'lab':              return ['ciel', 'ciea', 'cieb'];
        case 'lch':              return ['ciel', 'ciec', 'cieh'];
        case 'oklab':            return ['okl', 'oka', 'okb'];
        case 'oklch':            return ['okl', 'okc', 'okh'];
        default: return [];
      }
    }

    /** Array of all property names. */
    protected static function properties(): array {
      return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh', 'oka', 'okb', 'okl', 'okc', 'okh'];
    }

    /** Gets a color space from its id. */
    protected static function getSpace(array|string $spaceID): array {
      if (is_array($spaceID)) return $spaceID;
      $id = match ($spaceID) {
        'rgb', 'rgba' => 'srgb',
        'hsla' => 'hsl',
        default => $spaceID
      };
      return Graph::array_find(fn($e) => $e['id'] === $id, self::COLOR_SPACES);
    }

    /** Array of supported color spaces. */
    public const COLOR_SPACES = COLOR_SPACES;

    /** Array of supported syntaxes. */
    private static function formats() { return CSSFormats::formats(); }

    /** List of named colors in CSS. */
    public const NAMED_COLORS = NAMED_COLORS;
  }


}