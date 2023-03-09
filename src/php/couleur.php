<?php namespace colori {


  require_once __DIR__ . '/utils.php';
  require_once __DIR__ . '/conversion.php';
  require_once __DIR__ . '/graph.php';
  require_once __DIR__ . '/contrasts.php';
  require_once __DIR__ . '/distances.php';
  require_once __DIR__ . '/color-spaces.php';
  require_once __DIR__ . '/named-colors.php';
  require_once __DIR__ . '/css-formats.php';


  /** Graph with added cache for shortestPath() results. */
  class GraphWithCachedPaths extends Graph {
    private $cache = array();

    public function shortestPath(string|int $startID, string|int $endID): array {
      $id = $startID."_to_".$endID;
      $cachedPath = $this->cache[$id] ?? null;

      // If the path from startID to endID isn't cached, check if the reverse path
      // from endID to startID is cached. Since every conversion path is reversible,
      // we only need to store half of them in cache!
      if (!$cachedPath) {
        $reversedPath = $this->cache[$endID."_to_".$startID] ?? null;
        $cachedPath = $reversedPath ? array_reverse([...$reversedPath]) : null;
      }
      
      if ($cachedPath) return $cachedPath;

      $path = parent::shortestPath($startID, $endID);
      $this->cache[$id] = $path;
      return $path;
    }
  }


  const COLOR_SPACES_GRAPH = new GraphWithCachedPaths(COLOR_SPACES);

  class Couleur {
    private float $_r = 0.0;
    private float $_g = 0.0;
    private float $_b = 0.0;
    private float $_a = 0.0;
    private array $cache = [];
    
    function __construct(self|\stdClass|array|string $color) {
      if ($color instanceof self) {
        $this->_r = $color->r();
        $this->_g = $color->g();
        $this->_b = $color->b();
        $this->_a = $color->a() ?? 1;
      }

      // If object with r, g, b properties
      else if (is_object($color) && property_exists($color, 'r') && property_exists($color, 'g') && property_exists($color, 'b')) {
        $this->_r = $color->r;
        $this->_g = $color->g;
        $this->_b = $color->b;
        $this->_a = $color->a ?? 1;
      }

      // If associative array with r, g, b keys
      else if (is_array($color) && array_keys($color) !== range(0, count($color) - 1) && isset($color['r']) && isset($color['g']) && isset($color['b'])) {
        $values = [$color['r'], $color['g'], $color['b']];
        [$this->_r, $this->_g, $this->_b] = $values;
        $this->_a = $color['a'] ?? 1;
      }

      // If sequential array with 3 or 4 values
      else if (is_array($color) && (count($color) === 3 || count($color) === 4)) {
        $values = array_slice($color, 0, 3);
        [$this->_r, $this->_g, $this->_b] = $values;
        $this->_a = $color[3] ?? 1;
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

      else throw new \Exception(__CLASS__ . ' objects can only be created from a string, an array with r, g, b keys, or an object with r, g, b properties ; this is not one: ' . json_encode($color));
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
      if (substr($tri, 0, 1) === '#') $format = self::cssFormats()[0];
      else switch ($tri) {
        case 'rgb': $format = self::cssFormats()[1]; break;
        case 'hsl': $format = self::cssFormats()[2]; break;
        case 'hwb': $format = self::cssFormats()[3]; break;
        case 'lab': $format = self::cssFormats()[4]; break;
        case 'lch': $format = self::cssFormats()[5]; break;
        case 'okl':
          if (str_starts_with($colorString, 'oklab')) { $format = self::cssFormats()[6]; break; }
          if (str_starts_with($colorString, 'oklch')) { $format = self::cssFormats()[7]; break; }
          break;
        case 'col': $format = self::cssFormats()[8]; break;
        default:    $format = self::cssFormats()[9];
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
          } else {
            return $resultat = ['id' => $format['id'], 'data' => $matches];
          }
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
          case 'r':
          case 'g':
          case 'b':
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
          case 'h':
          case 'cieh':
          case 'okh':
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
              else throw new \Exception('angle');
              return (float) utils\angleToRange($h);
            }
            else throw new \Exception('invalid');

          // Percentage values:
          // from any %
          // clamped to [0, 100]%
          // to [0, 1]
          case 's':
          case 'l':
          case 'w':
          case 'bk':
          case 'ciel':
          case 'okl':
            // If n is a percentage
            if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
              if ($clamp) return max(0, min(floatval($value) / 100, 1));
              else        return floatval($value) / 100;
            }
            else throw new \Exception('invalid');

          // CIE axes values
          // and OKLAB axes values
          // and OKLCH chroma value:
          // any number
          case 'ciea':
          case 'cieb':
          case 'oka':
          case 'okb':
          case 'okc':
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
        if ($error === 'invalid')   throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
        elseif ($error === 'angle') throw new \Exception("Invalid angle value: ". json_encode($value));
        else                        throw new \Exception("Invalid arbitrary value: ". json_encode($value));
      }
    }

    
    /** Unparses a value to the format that would be used in a CSS expression. */
    private static function unparse(float $value, string $prop, ?int $precision = 0): string {
      $v = $value;
      switch ($prop) {
        case 'r':
        case 'g':
        case 'b':
          $unparsed = $precision === null ? (255 * $value) : round(10**$precision * 255 * $value) / (10**$precision);
          break;
        case 's':
        case 'l':
        case 'w':
        case 'bk':
        case 'ciel':
        case 'okl':
          $unparsed = $precision === null ? (100 * $value).'%' : (round(10**$precision * 100 * $value) / (10**$precision)).'%';
          break;
        case 'oka':
        case 'okb':
        case 'okc':
          $unparsed = $precision === null ? $value : round(10**max($precision, 4) * $value) / (10**max($precision, 4));
          break;
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

      [$this->_r, $this->_g, $this->_b] = self::convert($space, 'srgb', $values);
      $this->_a = self::parse($data[3] ?? 1, 'a');
    }


    /** Calculates all properties of the color from its hexadecimal expression. */
    private function setHex(array $hexa): void {
      $hexa[3] = $hexa[3] ?? 'ff';
      $rgba = utils\fromHex($hexa);
      foreach($rgba as $k => $v) {
        if ($k !== 3) $rgba[$k] = $v * 255;
      }

      $this->set($rgba, ['r', 'g', 'b'], 'srgb');
    }


    /** Calculates all properties of the color from its functional color() expression. */
    private function setColor(string $spaceID, array $values): void {
      $vals = array_slice($values, 0, 3);
      $a = $values[3];

      $vals = self::convert($spaceID, 'srgb', $vals);
      $rgba = $vals; $rgba[] = $a;

      $this->set($rgba, [null, null, null], 'srgb');
    }



    /*****************************/
    /* Getters for color formats */
    /*****************************/


    /* GENERAL EXPRESSION GETTER */

    /** Creates a string containing the CSS expression of a color. */
    public function toString(string $format = 'rgb', ?int $precision = 2, bool $clamp = false): string {
      $format = strtolower($format);
      $destinationSpaceID = str_replace('color-', '', $format);
      $destinationSpace = self::getSpace($destinationSpaceID);
      $props = self::propertiesOf($destinationSpace['id']);
      $values = $this->valuesTo($destinationSpace, clamp: $clamp);
      foreach ($values as $k => $v) { $values[$k] = $this->isPowerless($props[$k]) ? 0 : $v; }
      $values[] = $this->a();
      return self::makeString($format, $values, precision: $precision);
    }

    /** Creates a string containing the CSS expression of a color from a list of values. */
    public static function makeString(string $format, array $values, ?int $precision = 2): string {
      $format = strtolower($format);
      $destinationSpaceID = str_replace('color-', '', $format);
      $destinationSpace = self::getSpace($destinationSpaceID);

      $a = (float) self::unparse($values[3] ?? 1, 'a', precision: $precision);
      $values = array_slice($values, 0, 3);
      $values[] = $a;

      // If the requested expression is of the color(space, ...) type
      if (substr($format, 0, 5) === 'color') {
        $id = $destinationSpace['id'];
        $vals = [];
        foreach($values as $v) {
          $vals[] = $precision === null ? $v : round(10**$precision * $v) / (10**$precision);
        }
        $string = join(' ', array_slice($vals, 0, 3));

        if ($a < 1)
          return "color($id $string / $a)";
        else
          return "color($id $string)";
      }

      // If the requested expression is of the {$format}(...) type
      else {
        $props = self::propertiesOf($format);
        if (count($props) === 0) return self::makeString("color-$format", $values, precision: $precision);
        $unparsedValues = [];
        foreach($props as $k => $p) {
          $unparsedValues[] = self::unparse($values[$k], $p, precision: $precision);
        }

        switch ($format) {
          case 'rgb':
          case 'rgba':
          case 'hsl':
          case 'hsla':
            $string = join(', ', $unparsedValues);
            if ((strlen($format) > 3 && substr($format, -1) === 'a') || $a < 1.0)
              return "{$format}({$string}, {$a})";
            else
              return "{$format}({$string})";
          default:
            $string = join(' ', $unparsedValues);
            if ($a < 1.0) return "{$format}({$string} / {$a})";
            else          return "{$format}({$string})";
        }
      }
    }


    /* ALL VALUES (r, g, b) */

    public function values() { return [$this->r(), $this->g(), $this->b()]; }


    /* NAME */

    /** The approximate name of the color. */
    public function name(): ?string {
      if ($this->a() === 1.0) {
        [$r, $g, $b] = $this->values();
        $tolerance = .02;
        foreach (self::NAMED_COLORS as $name => $hex) {
          [$r2, $g2, $b2] = utils\fromHex([$hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]]);
          if (abs($r2 - $r) + abs($g2 - $g) + abs($b2 - $b) < $tolerance) return $name;
        }
        return null;
      }
      else if ($this->a() === 0.0) return 'transparent';
      else                       return null;
    }

    /** The exact name of the color. */
    public function exactName(): ?string {
      if ($this->a() === 1.0) {
        $hex6 = substr($this->hex(), 1);
        $name = array_search($hex6, self::NAMED_COLORS);
        return $name ?: null;
      }
      else if ($this->a() === 0.0) return 'transparent';
      else                       return null;
    }

    /** The name of the closest named color. */
    public function closestName(): string {
      if ($this->a() < 0.5) return 'transparent';
      [$r, $g, $b] = $this->values();
      $closest = '';
      $lastDistance = INF;
      foreach (self::NAMED_COLORS as $name => $hex) {
        [$r2, $g2, $b2] = utils\fromHex([$hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]]);
        $distance = abs($r2 - $r) + abs($g2 - $g) + abs($b2 - $b);
        if ($distance < $lastDistance) {
          $lastDistance = $distance;
          $closest = $name;
        }
      }
      return $closest;
    }


    /* CSS FORMATS */

    /** Hexadecimal expression of the color. */
    public function hex(): string {
      $values = self::valuesToGamut('srgb', $this->values());
      $values[] = $this->a();
      [$r, $g, $b, $a] = utils\toHex($values);
      if ($this->a() < 1) return '#'.$r.$g.$b.$a;
      else              return '#'.$r.$g.$b;
    }

    public function rgb(): string { return $this->toString('rgb', precision: 2, clamp: true); }
    public function rgba(): string { return $this->rgb(); }

    public function hsl(): string { return $this->toString('hsl', precision: 2, clamp: true); }
    public function hsla(): string { return $this->hsl(); }

    public function hwb(): string { return $this->toString('hwb', precision: 2, clamp: true); }

    public function lab(): string { return $this->toString('lab', precision: 2, clamp: true); }

    public function lch(): string { return $this->toString('lch', precision: 2, clamp: true); }

    public function oklab(): string { return $this->toString('oklab', precision: 2, clamp: true); }

    public function oklch(): string { return $this->toString('oklch', precision: 2, clamp: true); }



    /********************************************/
    /* Setters and getters for color properties */
    /********************************************/


    /** Recalculates the r, g, b properties of the color after modifying one of its other properties. */
    private function recompute(float | string $val, string $prop, string $format): void {
      $props = self::propertiesOf($format); $props[] = 'a';
      if (!in_array($prop, $props))
        throw new \Exception("Format $format does not have a property called $prop");

      $parsedVal = is_string($val) ? self::parse($val, $prop) : $val;
      $oldValues = $this->valuesTo($format); $oldValues[] = $this->a();
      $newValues = [];
      foreach($props as $k => $p) {
        if ($p === $prop) $newValues[] = $parsedVal;
        else              $newValues[] = $oldValues[$k];
      }
      $this->set($newValues, $props, $format, parsed: true);
      $this->cache = array();
    }


    private function setR(float | string $val): void { $this->recompute($val, 'r', 'rgb'); }
    private function setRed(float | string $val): void { $this->setR($val); }

    private function setG(float | string $val): void { $this->recompute($val, 'g', 'rgb'); }
    private function setGreen(float | string $val): void { $this->setG($val); }

    private function setB(float | string $val): void { $this->recompute($val, 'b', 'rgb'); }
    private function setBlue(float | string $val): void { $this->setB($val); }

    private function setA(float | string $val): void { $this->recompute($val, 'a', 'rgb'); }
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
    public function r(): float { return $this->_r; }
    public function red(): float { return $this->r(); }
    public function g(): float { return $this->_g; }
    public function green(): float { return $this->g(); }
    public function b(): float { return $this->_b; }
    public function blue(): float { return $this->b(); }
    public function a(): float { return $this->_a; }
    public function alpha(): float { return $this->a(); }
    public function opacity(): float { return $this->a(); }
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
        $this->setR($newLum);
        $this->setG($newLum);
        $this->setB($newLum);
      } else {
        $ratio = $newLum / $oldLum;
        $this->setR($ratio * $r);
        $this->setG($ratio * $g);
        $this->setB($ratio * $b);
      }
    }

    public function luminance(): float {
      if ($this->a() < 1) throw new \Exception('The luminance of a transparent color would be meaningless');
      return contrasts\luminance($this->values());
    }

    /** Returns whether a color property is powerless, i.e. has no effect on the color because of other properties. */
    public function isPowerless(string $prop, float $tolerance = .0001): bool {
      switch ($prop) {
        case 'h':
          return $this->s() <= 0 + $tolerance || $this->l() <= 0 + $tolerance || $this->l() >= 1 - $tolerance;
        case 's':
          return $this->l() <= 0 + $tolerance || $this->l() >= 1 - $tolerance;
        case 'ciea':
        case 'cieb':
        case 'cieh':
          return $this->ciel() <= 0 + $tolerance || $this->ciel() >= 1 - $tolerance;
        case 'oka':
        case 'okb':
        case 'okh':
          return $this->okl() <= 0 + $tolerance || $this->okl() >= 1 - $tolerance;
        case 'oksl':
          return $this->valuesTo('okhsl')[2] <= 0 + $tolerance;
        case 'oksv':
          return $this->valuesTo('okhsv')[2] <= 0 + $tolerance;
        default:
          return false;
      }
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
      $graph = self::COLOR_SPACES_GRAPH;
      try {
        $path = $graph->shortestPath($startSpace['id'], $endSpace['id']);
        $path = array_map(function ($node) { return $node->id; }, $path);
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
        $functionName = str_replace('-', '', "\\colori\\conversions\\{$start}_to_{$end}");
        $result = $functionName($result);
      }

      return $result;
    }


    /** Converts the r, g, b values of the color to another color space or CSS format. */
    public function valuesTo(array|string $destinationSpaceID, bool $clamp = false): array {
      $destinationSpace = self::getSpace($destinationSpaceID);
      $values = $this->cache[$destinationSpace['id']] ?? null;
      if (!$values) {
        $values = self::convert('srgb', $destinationSpace, $this->values());
        $this->cache[$destinationSpace['id']] = $values;
      }
      if ($clamp) $values = self::valuesToGamut($destinationSpace, $values);
      return $values;
    }


    /* Clamping to a color space */


    /** Checks whether parsed values in destinationSpaceID color space are in destinationSpace gamut. */
    public static function valuesInGamut(array|string $destinationSpaceID, array|self $values, float $tolerance = .0001): bool {
      $destinationSpace = self::getSpace($destinationSpaceID);
      $gamutSpace = isset($destinationSpace['gamutSpace']) ? self::getSpace($destinationSpace['gamutSpace']) : $destinationSpace;
      $convertedValues = $values instanceof self ? $values->valuesTo($gamutSpace)
                                                 :self::convert($destinationSpace, $gamutSpace, $values);
      foreach($convertedValues as $k => $v) {
        if ($v < ($gamutSpace['gamut'][$k][0] - $tolerance) || $v > $gamutSpace['gamut'][$k][1] + $tolerance) return false;
      }
      return true;
    }

    public function inGamut(array|string $destinationSpaceID, float $tolerance = .0001): bool {
      return self::valuesInGamut($destinationSpaceID, $this, tolerance: $tolerance);
    }


    /** Clamps parsed values in destinationSpaceID color space to destinationSpace gamut. */
    public static function valuesToGamut(array|string $destinationSpaceID, array|self $values, string $method = 'okchroma'): array {
      $destinationSpace = self::getSpace($destinationSpaceID);
      $gamutSpace = isset($destinationSpace['gamutSpace']) ? self::getSpace($destinationSpace['gamutSpace']) : $destinationSpace;
      $method = strtolower($method);

      if ($values instanceof self) {
        if ($values->inGamut($destinationSpace, tolerance: 0.0)) return $values->valuesTo($destinationSpace);
        $values = $values->valuesTo($destinationSpace);
      } else {
        if (self::valuesInGamut($destinationSpace, $values, tolerance: 0.0)) return $values;
      }

      switch ($method) {
        case 'okchroma':
          // OKLCH chroma gamut clipping
          $clampSpace = self::getSpace('oklch');
          $oklch = self::convert($destinationSpace, $clampSpace, $values);
  
          $τ = .000001;
          $δ = .02;
  
          if ($oklch[0] >= 1 - $τ) {
            return self::convert($gamutSpace, $destinationSpace, $gamutSpace['white'] ?? [1.0, 1.0, 1.0]);
          } elseif ($oklch[0] <= 0 + $τ) {
            return self::convert($gamutSpace, $destinationSpace, $gamutSpace['black'] ?? [0.0, 0.0, 0.0]);
          }
  
          $Cmin = 0;
          $Cmax = $oklch[1];
          $oklch[1] = $oklch[1] / 2;
  
          while ($Cmax - $Cmin > $τ) {
            $gamutValues = self::convert($clampSpace, $gamutSpace, $oklch);
            if (self::valuesInGamut($gamutSpace, $gamutValues, tolerance: 0.0)) {
              $Cmin = $oklch[1];
            } else {
              $naiveOklch = self::convert($gamutSpace, $clampSpace, self::valuesToGamut(
                $gamutSpace, $gamutValues, method: 'naive'
              ));
              $naiveOklab = self::convert($clampSpace, 'oklab', $naiveOklch);
              $oklab = self::convert($clampSpace, 'oklab', $oklch);
  
              if (distances\euclidean($naiveOklab, $oklab) < $δ) {
                $oklch = $naiveOklch;
                break;
              }
              $Cmax = $oklch[1];
            }
            $oklch[1] = ($Cmin + $Cmax) / 2;
          }
  
          $clampedValues = self::convert($clampSpace, $gamutSpace, $oklch);
          break;

        case 'naive':
          // Naively clamp the values
          $gamutValues = self::convert($destinationSpace, $gamutSpace, $values);
          $clampedValues = [];
          foreach($gamutValues as $k => $v) {
            $clampedValues[] = max($gamutSpace['gamut'][$k][0], min($v, $gamutSpace['gamut'][$k][1]));
          }
          break;

        default:
          throw new \Exception("$method is not a supported method for gamut mapping");
      }

      // Final naive clamp to get in the color space if the color is still just outside the border
      if ($method !== 'naive') {
        $clampedValues = self::valuesToGamut($gamutSpace, $clampedValues, method: 'naive');
      }

      // Send the values back in the same color space we found them in (in case destinationSpace != gamutSpace)
      return self::convert($gamutSpace, $destinationSpace, $clampedValues);
    }

    public function toGamut(object|string $destinationSpaceID, string $method = 'okchroma'): self {
      $destinationSpace = self::getSpace($destinationSpaceID);
      $destClampedValues = self::valuesToGamut($destinationSpace, $this, method: $method);
      $rgbClampedValues = self::convert($destinationSpace, 'srgb', $destClampedValues);
      $rgbClampedValues[] = $this->a();
      return new self($rgbClampedValues);
    }



    /********************************/
    /* Color manipulation functions */
    /********************************/


    /* Color modification */


    /** Modifies a color by changing a specific property. */
    public function change(string $prop, string|float|int $value, ?string $action = null): self {
      $action = is_string($action) ? strtolower($action) : $action;
      $replace = $action === 'replace';
      $scale = $action === 'scale';
      $val = $scale ? self::parse($value) : self::parse($value, $prop, clamp: false);
      $changedColor = new self($this);

      $oldVal = $this->{$prop}();
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
      return new self([1 - $this->r(), 1 - $this->g(), 1 - $this->b(), $this->a()]);
    }
    public function invert(): self { return $this->negative(); }

    /** The shade of grey of the color. */
    public function greyscale(): self {
      $L = $this->replace('a', 1)->luminance();
      return new self([$L, $L, $L, $this->a()]);
    }
    public function grayscale(): self { return $this->greyscale(); }

    /** The sepia tone of the color. */
    public function sepia(): self {
      $r = min(0.393 * $this->r() + 0.769 * $this->g() + 0.189 * $this->b(), 1);
      $g = min(0.349 * $this->r() + 0.686 * $this->g() + 0.168 * $this->b(), 1);
      $b = min(0.272 * $this->r() + 0.534 * $this->g() + 0.131 * $this->b(), 1);
      return new self([$r, $g, $b, $this->a()]);
    }


    /* Color interpolation */


    /** Linearly interpolates between two colors. */
    public static function interpolate(self|array|string $color1, self|array|string $color2, float $ratio = .5, array|string $interpolationSpace = 'oklab', string $hueInterpolationMethod = 'shorter'): self {
      $start = self::makeInstance($color1);
      $end = self::makeInstance($color2);
      $ratio = self::parse($ratio, null);

      $destinationSpace = self::getSpace($interpolationSpace);
      $props = self::propertiesOf($destinationSpace['id']);

      $startValues = $start->valuesTo($destinationSpace);
      $endValues = $end->valuesTo($destinationSpace);

      for ($k = 0; $k < count($startValues); $k++) {
        $prop = $props[$k];
        if ($start->isPowerless($prop) && $end->isPowerless($prop)) {
          $startValues[$k] = .0;
          $endValues[$k] = .0;
        } else if ($start->isPowerless($prop)) {
          $startValues[$k] = $endValues[$k];
        } else if ($end->isPowerless($prop)) {
          $endValues[$k] = $startValues[$k];
        }
      }

      // Premultiply alpha values
      $premultiply = function(array $values, float $a) use ($props): array {
        $newValues = [];
        foreach ($values as $k => $v) {
          switch ($props[$k]) {
            case 'h':
            case 'cieh':
            case 'okh':
              $newValues[] = $v;
              break;

            default:
              $newValues[] = $a * $v;
          }
        }
        return $newValues;
      };
      $startValues = $premultiply($startValues, $start->a());
      $endValues = $premultiply($endValues, $end->a());

      // Calculate the interpolated color
      $interpolatedValues = [];
      foreach ($startValues as $k => $v) {
        $prop = $props[$k];
        switch ($prop) {
          case 'h':
          case 'cieh':
          case 'okh':
            $diff = $endValues[$k] - $startValues[$k];

            switch ($hueInterpolationMethod) {
              case 'shorter':
                if ($diff > 180) $startValues[$k] += 360;
                else if ($diff < -180) $endValues[$k] += 360;
                break;
  
              case 'longer':
                if (0 < $diff && $diff < 180) $startValues[$k] += 360;
                else if (-180 < $diff && $diff < 0) $endValues[$k] += 360;
                break;
  
              case 'increasing':
                if ($diff < 0) $endValues[$k] += 360;
                break;
  
              case 'decreasing':
                if (0 < $diff) $startValues[$k] += 360;
                break;

              default:
                throw new \Exception("$method is not a supported method for hue interpolation");
            } // don't break: the value is computed in the default case

          default:
            $interpolatedValues[] = $startValues[$k] + $ratio * ($endValues[$k] - $startValues[$k]);
        }
      }
      $interpolatedAlpha = $start->a() + $ratio * ($end->a() - $start->a());

      // Undo alpha premultiplication
      $undoPremultiply = function(array $values) use ($interpolatedAlpha, $props): array {
        $newValues = [];
        foreach ($values as $k => $v) {
          switch ($props[$k]) {
            case 'h':
            case 'cieh':
            case 'okh':
              $newValues[] = $v;
              break;

            default:
              $newValues[] = $v / $interpolatedAlpha;
          }
        }
        return $newValues;
      };
      $interpolatedValues = $undoPremultiply($interpolatedValues);
      return new self([
        ...self::convert($destinationSpace, 'srgb', $interpolatedValues),
        $interpolatedAlpha
      ]);
    }


    /** Interpolates a given number of steps between two colors. */
    public static function interpolateInSteps(self|array|string $color1, self|array|string $color2, int $steps, array|string $interpolationSpace = 'oklab', string $hueInterpolationMethod = 'shorter'): array {
      $color1 = self::makeInstance($color1);
      $color2 = self::makeInstance($color2);
      $steps = max(0, round($steps));
      $interpolatedColors = [];
      for ($k = 1; $k <= $steps; $k++) {
        $ratio = $k / ($steps + 1);
        $interpolatedColors[] = self::interpolate($color1, $color2, ratio: $ratio, interpolationSpace: $interpolationSpace, hueInterpolationMethod: $hueInterpolationMethod);
      }
      return [$color1, ...$interpolatedColors, $color2];
    }


    /** Mixes two colors. */
    public static function mix(self|array|string $color1, float|string|null $pct1 = null, self|array|string|null $color2 = null, float|string|null $pct2 = null, array|string $interpolationSpace = 'oklab', string $hueInterpolationMethod = 'shorter'): self {
      $color1 = self::makeInstance($color1);
      $color2 = self::makeInstance($color2);
      $pct1 = $pct1 ? self::parse($pct1, null) : null;
      $pct2 = $pct2 ? self::parse($pct2, null) : null;

      // Normalize percentages (part 1/2)
      if (!$pct1 && !$pct2) {
        $pct1 = .5;
        $pct2 = .5;
      } else if ($pct1 && !$pct2) {
        $pct2 = 1.0 - $pct1;
      } else if (!$pct1 && $pct2) {
        $pct1 = 1.0 - $pct2;
      }

      // Normalize percentages (part 2/2)
      $alphaMultiplier = 1.0;
      $sum = $pct1 + $pct2;
      if ($sum === 0.0) throw new Error('The percentages passed as arguments add up to zero; that is invalid');
      else if ($sum < 1.0) {
        $alphaMultiplier = $sum;
      }
      $pct1 = $pct1 / $sum;
      $pct2 = $pct2 / $sum;

      $interpolatedColor = self::interpolate($color1, $color2, ratio: $pct2, interpolationSpace: $interpolationSpace, hueInterpolationMethod: $hueInterpolationMethod);
      return $interpolatedColor->replace('a', $alphaMultiplier * $interpolatedColor->a());
    }


    /* Color blending */


    /** Blends two colors together. */
    public static function blend(self|array|string $backgroundColor, self|array|string $overlayColor, ?float $alpha = null): self {
      $background = self::makeInstance($backgroundColor);
      $overlay = self::makeInstance($overlayColor);
      if ($alpha != null) // if alpha isn't null or undefined
        $overlay->setA(self::parse($alpha, 'a'));

      if ($overlay->a() === .0) return $background;
      else if ($overlay->a() === .1) return $overlay;

      $a = $overlay->a() + $background->a() * (1 - $overlay->a());
      $r = ($overlay->r() * $overlay->a() + $background->r() * $background->a() * (1 - $overlay->a())) / $a;
      $g = ($overlay->g() * $overlay->a() + $background->g() * $background->a() * (1 - $overlay->a())) / $a;
      $b = ($overlay->b() * $overlay->a() + $background->b() * $background->a() * (1 - $overlay->a())) / $a;
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
        $overlay->setA(self::parse($alpha, 'a'));

      if ($overlay->a() === 1.0) {
        throw new \Exception("Overlay color ". json_encode($overlay->rgb()) ." isn't transparent, so the background it was blended onto could have been any color");
      }
      elseif ($overlay->a() === .0)           return $mix;
      else {
        if ($mix->a() < $overlay->a())         return null;
        elseif ($mix->a() === $overlay->a()) {
          if (self::same($mix, $overlay))  return new self('transparent');
          else                             return null;
        }
        else {
          $a = ($mix->a() - $overlay->a()) / (1 - $overlay->a());
          $r = ($mix->r() * $mix->a() - $overlay->r() * $overlay->a()) / ($a * (1 - $overlay->a()));
          $g = ($mix->g() * $mix->a() - $overlay->g() * $overlay->a()) / ($a * (1 - $overlay->a()));
          $b = ($mix->b() * $mix->a() - $overlay->b() * $overlay->a()) / ($a * (1 - $overlay->a()));
          $clampedValues = self::valuesToGamut('srgb', [$r, $g, $b]);
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
    public static function whatToBlend(self|array|string $backgroundColor, self|array|string $overlayColor, array|float $alphas = [], bool $ignoreTransparent = false): array {
      $background = self::makeInstance($backgroundColor);
      $mix = self::makeInstance($overlayColor);
      $overlays = [];

      $calculateSolution = function($a) use ($mix, $background) {
        $r = ($mix->r() * $mix->a() - $background->r() * $background->a() * (1 - $a)) / $a;
        $g = ($mix->g() * $mix->a() - $background->g() * $background->a() * (1 - $a)) / $a;
        $b = ($mix->b() * $mix->a() - $background->b() * $background->a() * (1 - $a)) / $a;
        if (!self::valuesInGamut('srgb', [$r, $g, $b], tolerance: 1/255)) throw new \Exception("This color doesn't exist");
        $clampedValues = self::valuesToGamut('srgb', [$r, $g, $b], method: 'naive');
        $clampedValues[] = $a;
        return new self($clampedValues);
      };

      $defaultAlphas = [];
      for ($i = .1; $i <= .9; $i += 0.1) { $defaultAlphas[] = $i; }
      $requestedAlphas = is_array($alphas) ? $alphas : [$alphas];
      $computedAlphas = count($requestedAlphas) > 0 ? array_filter($requestedAlphas, fn($a) => $a > .0 && $a < 1.0)
                                                    : $defaultAlphas;

      // The mix can't have lower opacity than the background
      if ($mix->a() < $background->a())    return [];
      // If the mix is more opaque than the background...
      elseif ($mix->a() > $background->a()) {
        // If the background is partially transparent and the mix is opaque, the mix is the only solution
        // (any partially transparent overlay would have mixed with the background to make a partially transparent mix)
        if ($mix->a() === 1.0)           $overlays[] = $mix;
        // If the background is totally transparent and the mix is partially transparent, the mix is the only solution
        // (any other color mixed with nothing would make itself)
        elseif ($background->a() === .0) $overlays[] = $mix;
        // If the background is partially transparent and the mis is too, but more opaque, then there exists a unique solution
        else {
          $a = ($mix->a() - $background->a()) / (1.0 - $background->a());
          try { $overlays[] = $calculateSolution($a); }
          catch (\Throwable $error) { return []; }
        }
      }
      // If the mix is as opaque as the background...
      elseif ($mix->a() === $background->a()) {
        // If both the mix and the background are totally transparent, 'transparent' is the only solution
        // (any other color would have raised the opacity)
        if ($mix->a() === .0) $overlays[] = new self('transparent');
        // If both the mix and the background are partially transparent with the same opacity, then
        // if they're the same color, 'transparent' is solution. If not, there is no solution.
        else if ($mix->a() < 1.0) {
          if (self::same($mix, $background)) $overlays[] = new self('transparent');
          else                               return [];
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

      $result = count($requestedAlphas) > 0 ? array_filter($overlays, fn($c) => in_array($c->a(), $requestedAlphas))
                                            : $overlays;
      if ($ignoreTransparent) $result = array_filter($result, fn($a) => $a > .0);

      return $result;
    }


    /* Color comparison */


    /** Computes the contrast between two colors as defined by WCAG2 or 3. */
    public static function contrast(self|array|string $textColor, self|array|string $backgroundColor, string $method = 'APCA'): float {
      $background = self::makeInstance($backgroundColor);
      if ($background->a() < 1) throw new \Exception('The contrast with a transparent background color would be meaningless');
      $text = self::makeInstance($textColor);

      // If the text is transparent, blend it to the background to get its actual visible color
      if ($text->a() < 1) $text = self::blend($background, $text);

      switch (strtolower($method)) {
        case 'apca':
          return contrasts\APCA($text->values(), $background->values());
        case 'wcag2':
          return contrasts\WCAG2($text->values(), $background->values());
        default:
          throw new \Exception("$method is not a supported method for contrast calculations");
      }
    }
    

    /** Determines which color scheme ('light' or 'dark') would lead to a better contrast with the color. */
    public function bestColorScheme(string $as = 'background'): string {
      $rgba = $this->toGamut('srgb')->values();
      $rgba[] = $this->a();
      switch ($as) {
        case 'text':
          $Cblack = abs(self::contrast($rgba, 'black', method: 'apca'));
          $Cwhite = abs(self::contrast($rgba, 'white', method: 'apca'));
          return ($Cblack >= $Cwhite) ? 'dark' : 'light';
        case 'background':
          $Cblack = abs(self::contrast('black', $rgba, method: 'apca'));
          $Cwhite = abs(self::contrast('white', $rgba, method: 'apca'));
          return ($Cblack >= $Cwhite) ? 'light' : 'dark';
        default:
          throw new \Exception('Argument must be "background" or "text"');
      }
    }


    /** Modifies the OK lightness of a color to give it better contrast with a background color. */
    public function improveContrast(self|array|string $referenceColor, float $desiredContrast, string $as = 'text', bool $lower = false, ?string $colorScheme = null, string $method = 'APCA'): self {
      $background = $as === 'text' ? self::makeInstance($referenceColor) : $this;
      $text =       $as === 'text' ? $this : self::makeInstance($referenceColor);
      $backgroundLab = $background->valuesTo('oklab');
      $textLab = $text->valuesTo('oklab');
      $movingLab = $as === 'text' ? $textLab : $backgroundLab;

      // Let's measure the initial contrast
      // and decide if we want it to go up or down.
      $startContrast = abs(self::contrast($text, $background, method: $method));
      if ($startContrast > $desiredContrast)     $directionContrast = -1;
      elseif ($startContrast < $desiredContrast) $directionContrast = 1;
      else                                       $directionContrast = 0;
      // If the contrast is already higher than desired, and lowering it is not allowed, return the color as is.
      if (($directionContrast < 0 && $lower === false) || ($directionContrast === 0)) return $this;

      // Let's detect the color scheme if it isn't given.
      $colorScheme = $colorScheme ?? in_array(strtolower($method), ['wcag3', 'sapc', 'apca'])
                                   ? ($startContrast < 0 ? 'dark' : 'light')
                                   : ($backgroundLab[0] < $movingLab[0] ? 'dark' : 'light');

      // Let's measure the contrast of refColor with black and white to know if
      // desiredContrast can be reached by lowering or raising the color's CIE lightness.
      $cBlack = self::contrast($background, 'black', method: $method);
      $cWhite = self::contrast($background, 'white', method: $method);
      $cBlack = abs(
        $as === 'text' ? self::contrast($background, 'black', method: $method)
                       : self::contrast('black', $text, method: $method)
      );
      $cWhite = abs(
        $as === 'text' ? self::contrast($background, 'white', method: $method)
                       : self::contrast('white', $text, method: $method)
      );
      $isPossible = [
        'lowering' => ($directionContrast > 0) ? abs($cBlack) >= $desiredContrast : abs($cBlack) <= $desiredContrast,
        'raising' => ($directionContrast > 0) ? abs($cWhite) >= $desiredContrast : abs($cWhite) <= $desiredContrast
      ];
      
      // Let's decide which direction to move the lightness in.
      if ($isPossible['lowering'] && !$isPossible['raising'])      $directionOKL = -1;
      else if ($isPossible['raising'] && !$isPossible['lowering']) $directionOKL = 1;
      // If desiredContrast can not be reached, return white or black — the one that fits the color scheme.
      else if (!$isPossible['raising'] && !$isPossible['lowering']) {
        if ($as === 'text') {
          if ($colorScheme === 'light') return new self('black');
          else                          return new self('white');
        } else {
          if ($colorScheme === 'light') return new self('white');
          else                          return new self('black');
        }
      }
      // If desiredContrast can be reached in both directions
      else {
        // If we're changing the text color:
        // If the background is light and we need to raise the contrast, lower the lightness.
        if ($colorScheme === 'light' && $directionContrast > 0)      $directionOKL = -1;
        // If the background is light and we need to lower the contrast, raise the lightness.
        else if ($colorScheme === 'light' && $directionContrast < 0) $directionOKL = 1;
        // If the background is dark and we need to raise the contrast, raise the lightness.
        else if ($colorScheme === 'dark' && $directionContrast > 0)  $directionOKL = 1;
        // If the background is dark and we need to lower the contrast, lower the lightness.
        else                                                         $directionOKL = -1;

        // Else if we're changing the background color:
          if ($as === 'background') $directionOKL = -$directionOKL;
      }

      $τ = .0001;
      $OKLmin = ($directionOKL > 0) ? $movingLab[0] : 0;
      $OKLmax = ($directionOKL > 0) ? 1 : $movingLab[0];

      while ($OKLmax - $OKLmin > $τ) {
        // Let's try to raise contrast by increasing or reducing CIE lightness.
        $okl = ($OKLmin + $OKLmax) / 2;
        $newValues = $movingLab; $newValues[0] = $okl;
        $newContrast = abs(
          $as === 'text' ? self::contrast(self::convert('oklab', 'srgb', $newValues), $background, method: $method)
                         : self::contrast($text, self::convert('oklab', 'srgb', $newValues), method: $method)
        );

        // If the new contrast hasn't gone over its desired value
        $condition = ($directionContrast > 0) ? ($newContrast < $desiredContrast) : ($newContrast > $desiredContrast);
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
      $lastContrast = abs(
        $as === 'text' ? self::contrast($result, $background, method: $method)
                       : self::contrast($text, $result, method: $method)
      );
      if ($lastContrast < $desiredContrast) {
        if ($as === 'text') {
          if ($colorScheme === 'light') $movingLab[0] = $OKLmin;
          else                          $movingLab[0] = $OKLmax;
        } else {
          if ($colorScheme === 'light') $movingLab[0] = $OKLmax;
          else                          $movingLab[0] = $OKLmin;
        }
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

      switch (strtolower($method)) {
        case 'ciede2000':
        case 'deltae2000':
          $lab1 = $color1->valuesTo('lab');
          $lab2 = $color2->valuesTo('lab');
          $opaqueDist = distances\CIEDE2000($lab1, $lab2);
          $alphaCoeff = 50.0;
          break;
        case 'deltaeok':
          $oklab1 = $color1->valuesTo('oklab');
          $oklab2 = $color2->valuesTo('oklab');
          $opaqueDist = distances\euclidean($oklab1, $oklab2);
          break;
        case 'euclidean':
          $rgb1 = $colors1->values();
          $rgb2 = $colors2->values();
          $opaqueDist = distances\euclidean($rgb1, $rgb2);
          break;
        default:
          throw new \Exception("$method is not a supported method for distance calculations");
      }

      $alphaDist = $alpha ? distances\euclidean([$color1->a()], [$color2->a()]) : 0;
      return $opaqueDist + $alphaCoeff * $alphaDist;
    }


    /** Determines if two colors are the same, with a certain tolerance. */
    public static function same(self|array|string $color1, self|array|string $color2, float $tolerance = 1, string $method = 'deltaE2000'): bool {
      if (self::distance($color1, $color2, method: $method) > $tolerance) return false;
      else return true;
    }



    /**************/
    /* Color data */
    /**************/

    /** Gets the names of the properties of a color used in a certain format. */
    public static function propertiesOf(string $format): array {
      return self::getSpace(strtolower($format))['properties'] ?? [];
    }

    /** Array of all property names. */
    public static function properties(): array {
      $props = [];
      foreach (self::COLOR_SPACES as $space) {
        foreach (($space['properties'] ?? []) as $p) {
          if (!in_array($p, $props)) $props[] = $p;
        }
      }
      $props[] = 'a';
      return $props;
    }

    /** Gets a color space from its id. */
    protected static function getSpace(array|string $spaceID): array {
      $result = null;
      if (is_array($spaceID)) return $spaceID;
      $id = strtolower($spaceID);
      $result = Graph::array_find(fn($sp) => $sp['id'] === $id || in_array($id, $sp['aliases']), self::COLOR_SPACES);

      if ($result == null) throw new \Exception("");
      return $result;
    }

    /** Array of supported color spaces. */
    public const COLOR_SPACES = COLOR_SPACES;

    /** Graph of supported color spaces and the links (i.e. conversion functions) between them. */
    public const COLOR_SPACES_GRAPH = COLOR_SPACES_GRAPH;

    /** Array of supported syntaxes. */
    public static function cssFormats() { return CSSFormats::formats(); }

    /** List of named colors in CSS. */
    public const NAMED_COLORS = NAMED_COLORS;
  }


}