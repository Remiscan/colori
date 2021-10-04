<?php
 namespace colori {


  const COLOR_SPACES = array(
    array(
      'id' =>'srgb',
      'whitepoint' =>'d65',
      'CSSformat' =>'rgb',
      'gamut' =>[ [0, 1], [0, 1], [0, 1] ],
      'links' =>['lin_srgb', 'hsl']
    ), array(
      'id' => 'lin_srgb',
      'links' => ['srgb', 'd65xyz', 'oklab']
    ), array(
      'id' => 'hsl',
      'whitepoint' => 'd65',
      'CSSformat' => 'hsl',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['srgb', 'hwb']
    ), array(
      'id' => 'hwb',
      'whitepoint' => 'd65',
      'CSSformat' => 'hwb',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['hsl']
    ), array(
      'id' => 'lab',
      'whitepoint' => 'd50',
      'CSSformat' => 'lab',
      'gamut' => [ [0, 4], [-INF, INF], [-INF, INF] ],
      'links' => ['xyz', 'lch']
    ), array(
      'id' => 'lch',
      'whitepoint' => 'd50',
      'CSSformat' => 'lch',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['lab']
    ), array(
      'id' => 'xyz',
      'whitepoint' => 'd50',
      'CSSformat' => 'color',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lab', 'd65xyz', 'lin_prophoto-rgb']
    ), array(
      'id' => 'd65xyz',
      'whitepoint' => 'd65',
      'links' => ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
    ), array(
      'id' => 'display-p3',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_display-p3']
    ), array(
      'id' => 'lin_display-p3',
      'links' => ['display-p3', 'd65xyz']
    ), array(
      'id' => 'a98-rgb',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_a98-rgb']
    ), array(
      'id' => 'lin_a98-rgb',
      'links' => ['a98-rgb', 'd65xyz']
    ), array(
      'id' => 'prophoto-rgb',
      'whitepoint' => 'd50',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_prophoto-rgb']
    ), array(
      'id' => 'lin_prophoto-rgb',
      'links' => ['prophoto-rgb', 'xyz']
    ), array(
      'id' => 'rec2020',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_rec2020']
    ), array(
      'id' => 'lin_rec2020',
      'links' => ['rec2020', 'd65xyz']
    ), array(
      'id' => 'oklab',
      'whitepoint' => 'd65',
      'gamut' => [ [0, 4], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lin_srgb', 'oklch']
    ), array(
      'id' => 'oklch',
      'whitepoint' => 'd65',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['oklab']
    )
  );


} namespace colori\conversions {


  /* srgb */

  function srgb_to_lin_srgb(array $rgb): array {
    return array_map(function($x) {
      $sign = $x < 0 ? -1 : 1;
      return (abs($x) < 0.04045) ? $x / 12.92 : $sign * pow((abs($x) + 0.055) / 1.055, 2.4);
    }, $rgb);
  }

  function lin_srgb_to_srgb(array $rgb): array {
    return array_map(function($x) {
      $sign = $x < 0 ? -1 : 1;
      return (abs($x) > 0.0031308) ? $sign * (1.055 * pow(abs($x), 1 / 2.4) - 0.055) : 12.92 * $x;
    }, $rgb);
  }

  function lin_srgb_to_d65xyz(array $rgb): array {
    [$r, $g, $b] = $rgb;
    return [
      0.41239079926595934 * $r + 0.357584339383878 * $g + 0.1804807884018343 * $b,
      0.21263900587151027 * $r + 0.715168678767756 * $g + 0.07219231536073371 * $b,
      0.01933081871559182 * $r + 0.11919477979462598 * $g + 0.9505321522496607 * $b
    ];
  }

  function d65xyz_to_lin_srgb(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      3.2409699419045226 * $x - 1.537383177570094 * $y - 0.4986107602930034 * $z,
      -0.9692436362808796 * $x + 1.8759675015077202 * $y + 0.04155505740717559 * $z,
      0.05563007969699366 * $x - 0.20397695888897652 * $y + 1.0569715142428786 * $z
    ];
  }


  /* hsl */

  function srgb_to_hsl(array $rgb): array {
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
      while ($h < .0)    $h += 360.0;
      while ($h > 360.0) $h -= 360.0;

    }

    if ($l === .0 || $l === 1.0) $s = .0;
    elseif ($l <= 0.5)           $s = $chroma / (2.0 * $l);
    else                         $s = $chroma / (2.0 - 2.0 * $l);
    
    return [$h, $s, $l];
  }

  function hsl_to_srgb(array $hsl): array {
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


  /* hwb */

  function hsl_to_hwb(array $hsl): array {
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


  function hwb_to_hsl(array $hwb): array {
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


  /* display-p3 */

  function displayp3_to_lin_displayp3(array $rgb): array { return srgb_to_lin_srgb($rgb); }
  function lin_displayp3_to_displayp3(array $rgb): array { return lin_srgb_to_srgb($rgb); }

  function lin_displayp3_to_d65xyz(array $rgb): array {
    [$r, $g, $b] = $rgb;
    return [
      0.4865709486482162 * $r + 0.26566769316909306 * $g + 0.1982172852343625 * $b,
      0.2289745640697488 * $r + 0.6917385218365064 * $g + 0.079286914093745 * $b,
      0.0000000000000000 * $r + 0.04511338185890264 * $g + 1.043944368900976 * $b
    ];
  }

  function d65xyz_to_lin_displayp3(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      2.493496911941425 * $x + -0.9313836179191239 * $y + -0.40271078445071684 * $z,
      -0.8294889695615747 * $x + 1.7626640603183463 * $y +  0.023624685841943577 * $z,
      0.03584583024378447 * $x + -0.07617238926804182 * $y + 0.9568845240076872 * $z
    ];
  }


  /* prophoto-rgb */

  function prophotorgb_to_lin_prophotorgb(array $rgb): array {
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = abs($v) <= 16/512 ? $v / 16 : $sign * $v**1.8;
    }
    return $rgb;
  }

  function lin_prophotorgb_to_prophotorgb(array $rgb): array {
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = abs($v) >= 1/512 ? $sign * abs($v)**(1/1.8) : 16 * $v;
    }
    return $rgb;
  }

  function lin_prophotorgb_to_xyz(array $rgb): array {
    [$r, $g, $b] = $rgb;
    return [
      0.7977604896723027 * $r + 0.13518583717574031 * $g + 0.0313493495815248 * $b,
      0.2880711282292934 * $r + 0.7118432178101014 * $g + 0.00008565396060525902 * $b,
      0.0 * $r + 0.0 * $g + 0.8251046025104601 * $b
    ];
  }

  function xyz_to_lin_prophotorgb(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      1.3457989731028281 * $x + -0.25558010007997534 * $y + -0.05110628506753401 * $z,
      -0.5446224939028347 * $x + 1.5082327413132781 * $y + 0.02053603239147973 * $z,
      0.0 * $x + 0.0 * $y + 1.2119675456389454 * $z
    ];
  }


  /* a98-rgb */

  function a98rgb_to_lin_a98rgb(array $rgb): array {
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = $sign * abs($v)**(563/256);
    }
    return $rgb;
  }

  function lin_a98rgb_to_a98rgb(array $rgb): array {
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = $sign * abs($v)**(256/563);
    }
    return $rgb;
  }

  function lin_a98rgb_to_d65xyz(array $rgb): array {
    [$r, $g, $b] = $rgb;
    return [
      0.5766690429101305 * $r + 0.1855582379065463 * $g + 0.1882286462349947 * $b,
      0.29734497525053605 * $r + 0.6273635662554661 * $g + 0.07529145849399788 * $b,
      0.02703136138641234 * $r + 0.07068885253582723 * $g + 0.9913375368376388 * $b
    ];
  }

  function d65xyz_to_lin_a98rgb(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      2.0415879038107465 * $x + -0.5650069742788596 * $y + -0.34473135077832956 * $z,
      -0.9692436362808795 * $x + 1.8759675015077202 * $y + 0.04155505740717557 * $z,
      0.013444280632031142 * $x + -0.11836239223101838 * $y + 1.0151749943912054 * $z
    ];
  }


  /* rec2020 */

  function rec2020_to_lin_rec2020(array $rgb): array {
    $e = 1.09929682680944;
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = abs($v) < 0.018053968510807 * 4.5 ? $v / 4.5 : $sign * (abs($v) + $e - 1)**(1/0.45);
    }
    return $rgb;
  }

  function lin_rec2020_to_rec2020(array $rgb): array {
    $e = 1.09929682680944;
    foreach($rgb as $k => $v) {
      $sign = $v < 0 ? -1 : 1;
      $rgb[$k] = abs($v) > 0.018053968510807 ? $sign * ($e * abs($v)**(0.45) - ($e - 1)) : 4.5 * $v;
    }
    return $rgb;
  }

  function lin_rec2020_to_d65xyz(array $rgb): array {
    [$r, $g, $b] = $rgb;
    return [
      0.6369580483012914 * $r + 0.14461690358620832 * $g + 0.1688809751641721 * $b,
      0.2627002120112671 * $r + 0.6779980715188708 * $g + 0.05930171646986196 * $b,
      0.000000000000000 * $r + 0.028072693049087428 * $g + 1.060985057710791 * $b
    ];
  }

  function d65xyz_to_lin_rec2020(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      1.7166511879712674 * $x + -0.35567078377639233 * $y + -0.25336628137365974 * $z,
      -0.6666843518324892 * $x + 1.6164812366349395 * $y + 0.01576854581391113 * $z,
      0.017639857445310783 * $x + -0.042770613257808524 * $y + 0.9421031212354738 * $z
    ];
  }


  /* lab */

  function xyz_to_lab(array $xyz): array {
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

  function lab_to_xyz(array $lab): array {
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

  function lab_to_lch(array $lab): array {
    [$ciel, $ciea, $cieb] = $lab;
    $ciec = sqrt($ciea ** 2 + $cieb ** 2);
    $cieh = atan2($cieb, $ciea) * 180 / pi();
    while ($cieh < 0)   $cieh += 360;
    while ($cieh > 360) $cieh -= 360;

    return [$ciel, $ciec, $cieh];
  }


  function lch_to_lab(array $lch): array {
    [$ciel, $ciec, $cieh] = $lch;
    $ciea = $ciec * cos($cieh * pi() / 180);
    $cieb = $ciec * sin($cieh * pi() / 180);

    return [$ciel, $ciea, $cieb];
  }


  /* oklab */

  function lin_srgb_to_oklab(array $rgb): array {
    [$r, $g, $b] = $rgb;

    $l = 0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b;
    $m = 0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b;
    $s = 0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b;
    $l = pow($l, 1/3);
    $m = pow($m, 1/3);
    $s = pow($s, 1/3);
      
    $okl = 0.2104542553 * $l + 0.7936177850 * $m + -0.0040720468 * $s;
    $oka = 1.9779984951 * $l + -2.4285922050 * $m + 0.4505937099 * $s;
    $okb = 0.0259040371 * $l + 0.7827717662 * $m + -0.8086757660 * $s;

    return [$okl, $oka, $okb];
  }

  function oklab_to_lin_srgb(array $lab): array {
    [$okl, $oka, $okb] = $lab;

    $l = $okl + 0.3963377774 * $oka + 0.2158037573 * $okb;
    $m = $okl + -0.1055613458 * $oka + -0.0638541728 * $okb;
    $s = $okl + -0.0894841775 * $oka + -1.2914855480 * $okb;
    $l = $l**3;
    $m = $m**3;
    $s = $s**3;

    $r = 4.0767416621 * $l + -3.3077115913 * $m + 0.2309699292 * $s;
    $g = -1.2684380046 * $l + 2.6097574011 * $m + -0.3413193965 * $s;
    $b = -0.0041960863 * $l + -0.7034186147 * $m + 1.7076147010 * $s;

    return [$r, $g, $b];
  }

  function oklab_to_oklch(array $lab): array { return lab_to_lch($lab); }
  function oklch_to_oklab(array $lch): array { return lch_to_lab($lch); }


  /* Bradford transform */

  function d65xyz_to_xyz(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      1.0479298208405488 * $x + 0.022946793341019088 * $y - 0.05019222954313557 * $z,
      0.029627815688159344 * $x + 0.990434484573249 * $y - 0.01707382502938514 * $z,
      -0.009243058152591178 * $x + 0.015055144896577895 * $y + 0.7518742899580008 * $z
    ];
  }

  function xyz_to_d65xyz(array $xyz): array {
    [$x, $y, $z] = $xyz;
    return [
      0.9554734527042182 * $x - 0.023098536874261423 * $y + 0.0632593086610217 * $z,
      -0.028369706963208136 * $x + 1.0099954580058226 * $y + 0.021041398966943008 * $z,
      0.012314001688319899 * $x - 0.020507696433477912 * $y + 1.3303659366080753 * $z
    ];
  }


} namespace colori\contrasts {


  function luminance(array $rgb): float {
    $rgb = \colori\conversions\srgb_to_lin_srgb($rgb);
    return 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2];
  }


  function WCAG2(array $rgbText, array $rgbBack): float {
    $L1 = luminance($rgbText);
    $L2 = luminance($rgbBack);
    return (max($L1, $L2) + 0.05) / (min($L1, $L2) + 0.05);
  }


  function APCA(array $rgbText, array $rgbBack): float {
    // 1. Compute luminances
    $coeffs = [0.2126729, 0.7151522, 0.0721750];
    $gamma = 2.4;
    $luminance = function($rgb) use ($coeffs, $gamma) {
      $L = 0;
      foreach ($rgb as $k => $v) {
        $L += $v**$gamma * $coeffs[$k];
      }
      return $L;
    };
    $Ltext = $luminance($rgbText);
    $Lback = $luminance($rgbBack);
    
    // 2. Clamp luminances
    $blackClampTrigger = 0.03;
    $blackClampPow = 1.45;
    $clamp = fn($L) => $L > $blackClampTrigger ? $L : $L + ($blackClampTrigger - $L)**$blackClampPow;
    $Ltext = $clamp($Ltext);
    $Lback = $clamp($Lback);

    $δLmin = 0.0005;
    if (abs($Ltext - $Lback) < $δLmin) return .0;

    // 3. Compute contrast
    $scale = 1.25;
    $compute = fn($Lback, $Ltext, $powBack, $powText) => ($Lback**$powBack - $Ltext**$powText) * $scale;
    $lowClip = 0.001; $lowTrigger = 0.078; $lowOffset = 0.06; $invLowTrigger = 12.82051282051282;

    // for dark text on light background
    if ($Lback > $Ltext) {
      $powBack = 0.55; $powText = 0.58;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC < $lowClip) ? 0
              : (($SAPC < $lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC - $lowOffset);
    }

    // for light text on dark background
    else {
      $powBack = 0.62; $powText = 0.57;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC > -$lowClip) ? 0
              : (($SAPC > -$lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC + $lowOffset);
    }

    return (float) $result * 100;
  }


} namespace colori\utils {


  function pad(string $s): string {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  function angleToRange(float $angle): float {
    while ($angle < 0)   $angle += 360;
    while ($angle > 360) $angle -= 360;
    return (float) $angle;
  }

  function pRound(float $number, int $precision = 5): float {
    $x = (float) $number;
    $intDigits = ($x !== 0) ? floor(log10($x > 0 ? $x : -$x) + 1) : 1;
    $precision = (int) ($precision - $intDigits);
    return (float) round($x, $precision);
  }


} namespace colori {


  class GraphNode {
    private string $id;
    private bool $visited;
    private ?string $predecessorID;
    private array $links;

    public function __construct(array $array) {
      $this->id = $array['id'];
      $this->visited = false;
      $this->predecessor = null;
      $this->links = $array['links'];
    }

    public function id(): string { return $this->id; }
    public function visited(): bool { return $this->visited; }
    public function links(): array { return $this->links; }
    public function predecessor(): ?GraphNode { return $this->predecessor; }

    public function visit(mixed $mark = true): void {
      $this->visited = $mark;
    }
    public function unvisit(): void {
      $this->visited = false;
    }

    public function follow(GraphNode $node): void {
      $this->predecessor = $node;
    }
    public function unfollow(): void {
      $this->predecessor = null;
    }
  }


  class Graph {
    private array $nodes;
    private array $shortestPaths;

    public function __construct(array $array) {
      $this->nodes = [];
      foreach ($array as $e) {
        $this->nodes[] = new GraphNode($e);
      }
    }

    public static function array_find(callable $callback, array $array): mixed {
      foreach($array as $k => $v) {
        if ($callback($v, $k)) return $v;
      }
      return null;
    }

    public function getNode(string $id): GraphNode {
      $node = self::array_find(fn($node) => $node->id() === $id, $this->nodes);
      if ($node === null) throw new \Exception("Node ". json_encode($id) ." does not exist");
      return $node;
    }

    public function cleanUp(): void {
      foreach($this->nodes as $node) {
        $node->unvisit();
        $node->unfollow();
      }
    }

    public function shortestPath(string $startID, string $endID): array {
      if ($startID === $endID) return $this->shortestPath = [];

      $start = $this->getNode($startID);
      $end = $this->getNode($endID);

      $queue = [$start];
      $start->visit();

      // Let's build a breadth-first tree until we find the destination.
      $found = false;
      while (count($queue) > 0) {
        $current = array_shift($queue);
        if ($current->id() === $end->id()) {
          $found = true;
          break;
        }

        foreach ($current->links() as $neighbourID) {
          $neighbour = $this->getNode($neighbourID);
          if ($neighbour->visited() === false) {
            $neighbour->visit();
            $neighbour->follow($current);
            $queue[] = $neighbour;
          }
        }
      }

      if (!$found) throw new Exception("No path found from ". json_encode($startID) ." to ". json_encode($endID));

      // Let's backtrack through the tree to find the path.
      $path = [$end];
      $current = $end;
      while ($current->predecessor() != null) {
        $path[] = $current->predecessor();
        $current = $current->predecessor();
      }

      $this->cleanUp();
      return array_reverse($path);
    }

    public function topologicalOrder(): array {
      // Source of the math: https://en.wikipedia.org/wiki/Topological_sorting#Depth-first_search
      $orderedList = [];
      $unvisitedNodes = $this->nodes;

      $visit = function(GraphNode $node) use (&$visit, &$orderedList, &$unvisitedNodes): void {
        if ($node->visited() === true) return;
        if ($node->visited() === 'temp') throw new \Exception("The graph is not a directed acyclic graph");

        $node->visit('temp'); // Mark visit as temporary to detect if we loop back to this node
        foreach ($node->links() as $link) { $visit($this->getNode($link)); }
        $node->visit(true);

        $orderedList[] = $node;
      };

      while (count($unvisitedNodes) > 0) {
        $current = array_shift($unvisitedNodes);
        $visit($current);
      }

      $this->cleanUp();
      return array_reverse($orderedList);
    }
  }


} namespace colori\distances {


  function CIEDE2000(array $lab1, array $lab2): float {
    // Source of the math: http://www2.ece.rochester.edu/~gsharma/ciede2000/ciede2000noteCRNA.pdf
    [$l1, $a1, $b1] = $lab1;
    [$l2, $a2, $b2] = $lab2;

    $L1 = 100 * $l1; $L2 = 100 * $l2;
    $C1 = sqrt($a1 ** 2 + $b1 ** 2);
    $C2 = sqrt($a2 ** 2 + $b2 ** 2);

    $mC = ($C1 + $C2) / 2;
    $G = 0.5 * (1 - sqrt($mC ** 7 / ($mC ** 7 + 25 ** 7)));
    $aa1 = (1 + $G) * $a1;
    $aa2 = (1 + $G) * $a2;
    $CC1 = sqrt($aa1 ** 2 + $b1 ** 2);
    $CC2 = sqrt($aa2 ** 2 + $b2 ** 2);
    $hh1 = $CC1 === 0 ? 0 : atan2($b1, $aa1) * 180 / pi();
    $hh2 = $CC2 === 0 ? 0 : atan2($b2, $aa2) * 180 / pi();
    while ($hh1 < 0) $hh1 += 360; while ($hh1 > 360) $hh1 -= 360;
    while ($hh2 < 0) $hh2 += 360; while ($hh2 > 360) $hh2 -= 360;

    $dL = $L2 - $L1;
    $dC = $CC2 - $CC1;
    $dhh = ($CC1 * $CC2 === 0) ? 0
          : ((abs($hh2 - $hh1) <= 180) ? $hh2 - $hh1
          : (($hh2 - $hh1 > 180) ? $hh2 - $hh1 - 360
          : $hh2 - $hh1 + 360));
    $dH = 2 * sqrt($CC1 * $CC2) * sin((pi() / 180) * ($dhh / 2));

    $mL = ($L1 + $L2) / 2;
    $mCC = ($CC1 + $CC2) / 2;
    $mhh = ($CC1 * $CC2 === 0) ? $hh1 + $hh2
          : ((abs($hh2 - $hh1) <= 180) ? ($hh1 + $hh2) / 2
          : (($hh1 + $hh2 >= 360) ? ($hh1 + $hh2 - 360) / 2
          : ($hh1 + $hh2 + 360) / 2));
    $T = 1 - 0.17 * cos((pi() / 180) * ($mhh - 30))
        + 0.24 * cos((pi() / 180) * (2 * $mhh))
        + 0.32 * cos((pi() / 180) * (3 * $mhh + 6))
        - 0.20 * cos((pi() / 180) * (4 * $mhh - 63));
    $dTH = 30 * exp(-1 * (($mhh - 275) / 25) ** 2);
    $RC = 2 * sqrt($mCC ** 7 / ($mCC ** 7 + 25 ** 7));
    $SL = 1 + (0.015 * ($mL - 50) ** 2) / sqrt(20 + ($mL - 50) ** 2);
    $SC = 1 + 0.045 * $mCC;
    $SH = 1 + 0.015 * $mCC * $T;
    $RT = -1 * sin((pi() / 180) * (2 * $dTH)) * $RC;

    return sqrt(
      ($dL / $SL) ** 2
      + ($dC / $SC) ** 2
      + ($dH / $SH) ** 2
      + $RT * ($dC / $SC) * ($dH / $SH)
    );
  }


} namespace colori\oklab_gamut {


  // Source of the math: https://bottosson.github.io/posts/gamutclipping/

  function maxSaturation(float $a, float $b): float {
    // If red goes negative first
    if (-1.88170328 * $a - 0.80936493 * $b > 1) {
        $k0 = 1.19086277; $k1 = 1.76576728; $k2 = 0.59662641; $k3 = 0.75515197; $k4 = 0.56771245;
        $wl = 4.0767416621; $wm = -3.3077115913; $ws = 0.2309699292;
    }
    // If green goes negative first
    else if (1.81444104 * $a - 1.19445276 * $b > 1) {
        $k0 = 0.73956515; $k1 = -0.45954404; $k2 = 0.08285427; $k3 = 0.12541070; $k4 = 0.14503204;
        $wl = -1.2684380046; $wm = 2.6097574011; $ws = -0.3413193965;
    }
    // If blue goes negative first
    else {
        $k0 = 1.35733652; $k1 = -0.00915799; $k2 = -1.15130210; $k3 = -0.50559606; $k4 = 0.00692167;
        $wl = -0.0041960863; $wm = -0.7034186147; $ws = +1.7076147010;
    }

    // Approximate max saturation
    $S = $k0 + $k1 * $a + $k2 * $b + $k3 * $a * $a + $k4 * $a * $b;

    // Do one step Halley's method to get closer
    $k_l = 0.3963377774 * $a + 0.2158037573 * $b;
    $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
    $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

    $l_ = 1 + $S * $k_l;
    $m_ = 1 + $S * $k_m;
    $s_ = 1 + $S * $k_s;

    $l = $l_ ** 3;
    $m = $m_ ** 3;
    $s = $s_ ** 3;

    $l_dS = 3 * $k_l * $l_ * $l_;
    $m_dS = 3 * $k_m * $m_ * $m_;
    $s_dS = 3 * $k_s * $s_ * $s_;

    $l_dS2 = 6 * $k_l * $k_l * $l_;
    $m_dS2 = 6 * $k_m * $k_m * $m_;
    $s_dS2 = 6 * $k_s * $k_s * $s_;

    $f  = $wl * $l     + $wm * $m     + $ws * $s;
    $f1 = $wl * $l_dS  + $wm * $m_dS  + $ws * $s_dS;
    $f2 = $wl * $l_dS2 + $wm * $m_dS2 + $ws * $s_dS2;

    $S = $S - $f * $f1 / ($f1 * $f1 - 0.5 * $f * $f2);
    return $S;
  }


  function cusp(float $a, float $b): array {
    $Scusp = maxSaturation($a, $b);

    $rgbMax = \colori\conversions\oklab_to_lin_srgb([1, $Scusp * $a, $Scusp * $b]);
    $Lcusp = pow(1 / max($rgbMax), 1/3);
    $Ccusp = $Lcusp * $Scusp;

    return [$Lcusp, $Ccusp];
  }


  function gamutIntersection(float $a, float $b, float $L1, float $C1, float $L0): float {
    [$Lcusp, $Ccusp] = cusp($a, $b);

    if ((($L1 - $L0) * $Ccusp - ($Lcusp - $L0) * $C1) <= 0) {
      $t = $Ccusp * $L0 / ($C1 * $Lcusp + $Ccusp * ($L0 - $L1));
    } else {
      $t = $Ccusp * ($L0 - 1) / ($C1 * ($Lcusp - 1) + $Ccusp * ($L0 - $L1));

      $dL = $L1 - $L0;
      $dC = $C1;
      
      $k_l = 0.3963377774 * $a + 0.2158037573 * $b;
      $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
      $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

      $l_dt = $dL + $dC * $k_l;
      $m_dt = $dL + $dC * $k_m;
      $s_dt = $dL + $dC * $k_s;

      for ($i = 0; $i < 2; $i++) {
        $L = $L0 * (1 - $t) + $t * $L1;
        $C = $t * $C1;

        $l_ = $L + $C * $k_l;
        $m_ = $L + $C * $k_m;
        $s_ = $L + $C * $k_s;

        $l = $l_ ** 3;
        $m = $m_ ** 3;
        $s = $s_ ** 3;

        $ldt = 3 * $l_dt * $l_ * $l_;
        $mdt = 3 * $m_dt * $m_ * $m_;
        $sdt = 3 * $s_dt * $s_ * $s_;

        $ldt2 = 6 * $l_dt * $l_dt * $l_;
        $mdt2 = 6 * $m_dt * $m_dt * $m_;
        $sdt2 = 6 * $s_dt * $s_dt * $s_;

        $term = function(float $v1, float $v2, float $v3) use ($l, $m, $s, $ldt, $mdt, $sdt, $ldt2, $mdt2, $sdt2) {
          $w = $v1 * $l + $v2 * $m + $v3 * $s - 1;
          $w1 = $v1 * $ldt + $v2 * $mdt + $v3 * $sdt;
          $w2 = $v1 * $ldt2 + $v2 * $mdt2 + $v3 * $sdt2;

          $u = $w1 / ($w1 * $w1 - .5 * $w * $w2);
          $t = $u >= 0 ? (-$w * $u) : PHP_FLOAT_MAX;
          return $t;
        };

        $t_r = $term(4.0767416621, -3.3077115913, 0.2309699292);
        $t_g = $term(-1.2684380046, 2.6097574011, -0.3413193965);
        $t_b = $term(-0.0041960863, -0.7034186147, 1.7076147010);

        $t += min($t_r, $t_g, $t_b);
      }
    }

    return $t;
  }


  function clip(array $rgb): array {
    $continue = false;
    foreach ($rgb as $v) {
      if ($v < 0 || $v > 1) { $continue = true; break; }
    }
    if ($continue === false) return $rgb;
    
    [$okl, $oka, $okb] = \colori\conversions\lin_srgb_to_oklab(\colori\conversions\srgb_to_lin_srgb($rgb));
    [$x, $okc, $okh] = \colori\conversions\oklab_to_oklch([$okl, $oka, $okb]);
    
    $τ = .00001;
    $α = .05;
    $C = max($τ, $okc);
    $a = $oka / $C; $b = $okb / $C;
    
    $Ld = $okl - .5;
    $e1 = .5 + abs($Ld) + $α * $C;
    $sign = function(int|float $n): int { return ($n > 0) - ($n < 0); };
    $L0 = .5 * (1 + $sign($Ld) * ($e1 - sqrt($e1 * $e1 - 2 * abs($Ld))));
    
    $t = gamutIntersection($a, $b, $okl, $C, $L0);
    $Lclipped = $L0 * (1 - $t) + $t * $okl;
    $Cclipped = $t * $C;
    
    $clampedValues = \colori\conversions\lin_srgb_to_srgb(\colori\conversions\oklab_to_lin_srgb([$Lclipped, $Cclipped * $a, $Cclipped * $b]));
    return $clampedValues;
  }


} namespace colori {


  const NAMED_COLORS = array(
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


} namespace colori {


  class CSSFormats {
    private const NUMBER_EXP = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';

    public static function RegExp(string $id): string {
      return match($id) {
        'number' => self::NUMBER_EXP,
        'percentage' => self::NUMBER_EXP . '%',
        'numberOrPercentage' => self::NUMBER_EXP . '%?',
        'angle' => self::NUMBER_EXP . '(?:deg|grad|rad|turn)?'
      };
    }

    public static function formats(): array {
      return array(
        array(
          'id' => 'hex',
          'syntaxes' => array(
            // #abc or #ABC
            '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
            // #abcd or #ABCD
            '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
            // #aabbcc or #AABBCC
            '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/',
            // #aabbccdd or #AABBCCDD
            '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
          )
        ), array(
          'id' => 'rgb',
          'syntaxes' => array(
            // rgb(255, 255, 255) (spaces not required)
            '/^rgba?\\(('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('number').')\\)$/',
            // rgba(255, 255, 255, .5) or rgba(255, 255, 255, 50%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(100%, 100%, 100%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // rgba(100%, 100%, 100%, .5) or rgba(100%, 100%, 100%, 50%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(255 255 255)
            '/^rgba?\\(('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
            '/^rgba?\\(('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(100% 100% 100%)
            '/^rgba?\\(('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
            '/^rgba?\\(('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'hsl',
          'syntaxes' => array(
            // hsl(<angle>, 100%, 100%)
            '/^hsla?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
            '/^hsla?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // hsl(<angle> 100% 100%)
            '/^hsla?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
            '/^hsla?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'hwb',
          'syntaxes' => array(
            // hwb(<angle>, 100%, 100%)
            '/^hwba?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // hwba(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
            '/^hwba?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // hwb(<angle> 100% 100%)
            '/^hwba?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
            '/^hwba?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'lab',
          'syntaxes' => array(
            // lab(300% 25 40)
            '/^lab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // lab(300% 25 40 / .5)
            '/^lab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'lch',
          'syntaxes' => array(
            // lch(300% 25 <angle>)
            '/^lch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').')\\)$/',
            // lch(300% 25 <angle> / .5)
            '/^lch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'color',
          'syntaxes' => array(
            // color(display-p3 -0.6112 1.0079 -0.2192)
            '/^color\\(([a-zA-Z0-9_-]+?) ('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
            '/^color\\(([a-zA-Z0-9_-]+?) ('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'name',
          'syntaxes' => array(
            // white or WHITE or WhiTe
            '/^[A-Za-z]+$/'
          )
        )
      );
    }
  }


} namespace colori {


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
            $this->setRgb([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
            break;
          case 'hsl':
            $this->setHsl([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
            break;
          case 'hwb':
            $this->setHwb([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
            break;
          case 'lab':
            $this->setLab([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
            break;
          case 'lch':
            $this->setLch([$format['data'][1], $format['data'][2], $format['data'][3], $format['data'][4] ?? 1]);
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
    private static function makeInstance(self|array|string $color): self {
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
        case 'col': $format = self::formats()[6]; break;
        default:    $format = self::formats()[7];
      }

      // Check if the given string matches any color syntax
      foreach($format['syntaxes'] as $syntaxe) {
        $result = preg_match($syntaxe, $colorString, $matches);
        if ($result === 1 && $matches[0] === $colorString) {
          if ($format['id'] === 'name') {
            if ($colorString === 'transparent') return ['id' => 'rgb', 'data' => [null, 0, 0, 0, 0]];
            $name = strtolower($colorString);
            if (array_key_exists($name, self::COULEURS_NOMMEES))
              return self::matchSyntax('#' . self::COULEURS_NOMMEES[strtolower($colorString)]);
          }
          return $resultat = ['id' => $format['id'], 'data' => $matches];
        }
      }

      throw new \Exception(json_encode($colorString) .' is not a valid color format');
    }


    /** Parses a number / percentage / angle into the correct format to store it. */
    private static function parse(string|float|int $value, ?string $prop = null, bool $clamp = true): float {
      // Alpha values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 1]
      // to [0, 1]
      if ($prop === 'a') {
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
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
      }

      // Red, green, blue values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 255]
      // to [0, 1]
      elseif (in_array($prop, ['r', 'g', 'b'])) {
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
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
      }

      // Hue and CIE hue values:
      // from any angle or any number
      // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
      // to [0, 1]
      elseif (in_array($prop, ['h', 'cieh'])) {
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
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
      }

      // Percentage values:
      // from any %
      // clamped to [0, 100]%
      // to [0, 1]
      elseif (in_array($prop, ['s', 'l', 'w', 'bk', 'ciel'])) {
        // If n is a percentage
        if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
          if ($clamp) return max(0, min(floatval($value) / 100, 1));
          else        return floatval($value) / 100;
        }
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". json_encode($value));
      }

      // CIE axes values:
      // any number
      elseif (in_array($prop, ['ciea', 'cieb'])) {
        // If n is a number
        if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
          return floatval($value);
        }
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". $value);
      }

      // CIE chroma values:
      // from any number
      // clamped to [0, +Inf[
      elseif ($prop === 'ciec') {
        // If n is a number
        if (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
          if ($clamp) return max(0, $value);
          else        return floatval($value);
        }
        else throw new \Exception("Invalid ". json_encode($prop) ." value: ". $value);
      }

      // Arbitrary values
      // from any % or any number
      // to any number (so that 0% becomes 0 and 100% becomes 1)
      else {
        // If n is a percentage
        if (preg_match('/^' . CSSFormats::RegExp('percentage') . '$/', $value)) {
          return floatval($value) / 100;
        }
        // If n is a number
        elseif (preg_match('/^' . CSSFormats::RegExp('number') . '$/', $value)) {
          return floatval($value);
        }
        else throw new \Exception("Invalid arbitrary value: ". json_encode($value));
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
        case 'a':
          $unparsed = $precision === null ? $value : round(10**max($precision, 2) * $value) / (10**max($precision, 2));
          break;
        default:
          $unparsed = $precision === null ? $value : round(10**$precision * $value) / (10**$precision);
      }
      return (string) $unparsed;
    }


    /*****************************************/
    /* Setters and getters for color formats */
    /*****************************************/


    /* GENERAL SETTER */

    /** Will be used by other setters to calculate all color properties. */
    private function set(array $data, array $props, array|string $spaceID): void {
      $space = self::getSpace($spaceID);
      $values = [];
      for ($i = 0; $i < count($props); $i++) {
        $values[] = self::parse($data[$i], $props[$i]);
      }

      [$this->r, $this->g, $this->b] = self::convert($space, 'srgb', $values);
      $this->a = self::parse($data[3] ?? 1, 'a');
    }


    /* GENERAL GETTER */

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
        $allNames = self::COULEURS_NOMMEES;
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
        $name = array_search($hex6, self::COULEURS_NOMMEES);
        return $name ?? null;
      }
      elseif ($this->a === 0)  return 'transparent';
      else                     return null;
    }


    /* RGB (hexadecimal) */

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

      $this->setRgb([$r, $g, $b, $a]);
    }

    /** Alias to the hex setter. */
    private function setHexa(array $hexa): void { $this->setHex($hex); }

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


    /* RGB (functional) */

    /** Calculates all properties of the color from its functional RGB expression. */
    private function setRgb(array $rgba): void { $this->set($rgba, ['r', 'g', 'b'], 'rgb'); }
    private function setRgba(array $rgba): void { $this->setRgb($rgba); }
    public function rgb(): string { return $this->expr('rgb', precision: 2); }
    public function rgba(): string { return $this->rgb(); }


    /* HSL */

    /** Calculates all properties of the color from its HSL expression. */
    private function setHsl(array $hsla): void { $this->set($hsla, ['h', 's', 'l'], 'hsl'); }
    public function hsl(): string { return $this->expr('hsl', precision: 2); }
    public function hsla(): string { return $this->hsl(); }


    /* HWB */

    /** Calculates all properties of the color from its HWB expression. */
    private function setHwb(array $hwba): void { $this->set($hwba, ['h', 'w', 'b'], 'hwb'); }
    public function hwb(): string { return $this->expr('hwb', precision: 2); }


    /* LAB */

    /** Calculates all properties of the color from its LAB expression. */
    private function setLab(array $laba): void { $this->set($laba, ['ciel', 'ciea', 'cieb'], 'lab'); }
    public function lab(): string { return $this->expr('lab', precision: 2); }


    /* LCH */

    /** Calculates all properties of the color from its LCH expression. */
    private function setLch(array $lcha): void { $this->set($lcha, ['ciel', 'ciec', 'cieh'], 'lch'); }
    public function lch(): string { return $this->expr('lch', precision: 2); }


    /* PROFILED COLORS */
    /** Calculates all properties of the color from its functional color() expression. */
    private function setColor(string $spaceID, array $rgba): void {
      $rgb = array_slice($rgba, 0, 3);
      $a = $rgba[3];

      //$clamp = fn($v) => max(0, min($v, 1));
      switch ($spaceID) {
        case 'srgb': case 'display-p3': case 'a98-rgb': case 'prophoto-rgb': case 'rec2020':
          //$rgb = array_map($clamp, $rgb);
        case 'xyz':
          $rgb = self::convert($spaceID, 'srgb', $rgb);
          break;
        default:
          if (str_starts_with($spaceID, '--')) {
            $id = substr($spaceID, 2);
            $rgb = self::convert($id, 'srgb', $rgb);
          }
          else throw new \Exception("The ". json_encode($spaceID) ." color space is not supported");
      }

      $values = $rgb; $rgba[] = $a;
      $this->set($values, [null, null, null], 'srgb');
    }


    /********************************************/
    /* Setters and getters for color properties */
    /********************************************/


    private function setR(float $val): void { $this->r = $val; }
    private function setRed(float $val): void { $this->setR($val); }

    private function setG(float $val): void { $this->g = $val; }
    private function setGreen(float $val): void { $this->setG($val); }

    private function setB(float $val): void { $this->b = $val; }
    private function setBlue(float $val): void { $this->setB($val); }

    private function setA(float $val): void { $this->a = $val; }
    private function setAlpha(float $val): void { $this->setA($val); }
    private function setOpacity(float $val): void { $this->setA($val); }

    private function setH(float $val): void {
      [$x, $s, $l] = $this->valuesTo('hsl');
      $temp = [];
      $props = self::propertiesOf('hsl'); $props[] = 'a';
      foreach([$val, $s, $l, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setHsl($temp);
    }
    private function setHue(float $val): void { $this->setH($val); }

    private function setS(float $val): void {
      [$h, $x, $l] = $this->valuesTo('hsl');
      $temp = [];
      $props = self::propertiesOf('hsl'); $props[] = 'a';
      foreach([$h, $val, $l, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setHsl($temp);
    }
    private function setSaturation(float $val): void { $this->setS($val); }

    private function setL(float $val): void {
      [$h, $s, $x] = $this->valuesTo('hsl');
      $temp = [];
      $props = self::propertiesOf('hsl'); $props[] = 'a';
      foreach([$h, $s, $val, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setHsl($temp);
    }
    private function setLightness(float $val): void { $this->setL($val); }

    private function setW(float $val): void {
      [$h, $x, $bk] = $this->valuesTo('hwb');
      $temp = [];
      $props = self::propertiesOf('hwb'); $props[] = 'a';
      foreach([$h, $val, $bk, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setHwb($temp);
    }
    private function setWhiteness(float $val): void { $this->setW($val); }

    private function setBk(float $val): void {
      [$h, $w, $x] = $this->valuesTo('hwb');
      $temp = [];
      $props = self::propertiesOf('hwb'); $props[] = 'a';
      foreach([$h, $w, $val, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setHwb($temp);
    }
    private function setBlackness(float $val): void { $this->setBk($val); }

    private function setCiel(float $val): void {
      [$x, $ciea, $cieb] = $this->valuesTo('lab');
      $temp = [];
      $props = self::propertiesOf('lab'); $props[] = 'a';
      foreach([$val, $ciea, $cieb, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setLab($temp);
    }
    private function setCIELightness(float $val): void { $this->setCIEL($val); }

    private function setCiea(float $val): void {
      [$ciel, $x, $cieb] = $this->valuesTo('lab');
      $temp = [];
      $props = self::propertiesOf('lab'); $props[] = 'a';
      foreach([$ciel, $val, $cieb, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setLab($temp);
    }

    private function setCieb(float $val): void {
      [$ciel, $ciea, $x] = $this->valuesTo('lab');
      $temp = [];
      $props = self::propertiesOf('lab'); $props[] = 'a';
      foreach([$ciel, $ciea, $val, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setLab($temp);
    }

    private function setCiec(float $val): void {
      [$ciel, $x, $cieh] = $this->valuesTo('lch');
      $temp = [];
      $props = self::propertiesOf('lch'); $props[] = 'a';
      foreach([$ciel, $val, $cieh, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setLch($temp);
    }
    private function setCIEChroma(float $val): void { $this->setCIEC($val); }

    private function setCieh(float $val): void {
      [$ciel, $ciec, $x] = $this->valuesTo('lch');
      $temp = [];
      $props = self::propertiesOf('lch'); $props[] = 'a';
      foreach([$ciel, $ciec, $val, $this->a] as $k => $v) {
        $temp[] = self::unparse($v, $props[$k]);
      }
      $this->setLch($temp);
    }
    private function setCIEHue(float $val): void { $this->setCIEH($val); }

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
        $clampSpace = self::getSpace('oklab');
        $rgb = self::convert($valueSpace, $clampSpace, $values);
        $clampedValues = oklab_gamut\clip($rgb);
      }

      // Let's reduce the chroma until the color is in the color space
      else {
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
          if (!self::inGamut('srgb', [$r, $g, $b], 'srgb', tolerance: 1/255)) return null;
          $clampedValues = self::toGamut('srgb', [$r, $g, $b], 'srgb', method: 'naive');
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
      $backgroundLab = $background->valuesTo('lab');
      $movingLab = $this->valuesTo('lab');

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
      if ($isPossible['lowering'] && !$isPossible['raising'])      $directionCIEL = -1;
      else if ($isPossible['raising'] && !$isPossible['lowering']) $directionCIEL = 1;
      // If desiredContrast can not be reached, return white or black — the one that fits the color scheme.
      else if (!$isPossible['raising'] && !$isPossible['lowering']) {
        if ($colorScheme === 'light') return new self('black');
        else                          return new self('white');
      }
      // If desiredContrast can be reached in both directions
      else {
        // If the background is light and we need to raise the contrast, lower the lightness.
        if ($colorScheme === 'light' && $directionContrast > 0)      $directionCIEL = -1;
        // If the background is light and we need to lower the contrast, raise the lightness.
        else if ($colorScheme === 'light' && $directionContrast < 0) $directionCIEL = 1;
        // If the background is dark and we need to raise the contrast, raise the lightness.
        else if ($colorScheme === 'dark' && $directionContrast > 0)  $directionCIEL = 1;
        // If the background is dark and we need to lower the contrast, lower the lightness.
        else                                                       $directionCIEL = -1;
      }

      $τ = .0001;
      $CIELmin = ($directionCIEL > 0) ? $movingLab[0] : 0;
      $CIELmax = ($directionCIEL > 0) ? 1 : $movingLab[0];

      while ($CIELmax - $CIELmin > $τ) {
        // Let's try to raise contrast by increasing or reducing CIE lightness.
        $ciel = ($CIELmin + $CIELmax) / 2;
        $newValues = $movingLab; $newValues[0] = $ciel;
        $newContrast = self::contrast(self::convert('lab', 'srgb', $newValues), $background, method: $method);

        // If the new contrast hasn't gone over its desired value
        $condition = ($directionContrast > 0) ? (abs($newContrast) < $desiredContrast) : (abs($newContrast) > $desiredContrast);
        if ($condition) {
          if ($directionCIEL > 0) $CIELmin = $ciel;
          else                    $CIELmax = $ciel;
        }
        // If we overshot and the contrast moved further than we want it to
        else {
          if ($directionCIEL > 0) $CIELmax = $ciel;
          else                    $CIELmin = $ciel;
        }

        $movingLab[0] = $ciel;
      }

      $result = new self(self::convert('lab', 'srgb', $movingLab));
      // If the color we find has its contrast slightly below the desired value, push it further.
      if (abs(self::contrast($result, $background, method: $method)) < $desiredContrast) {
        if ($directionCIEL > 0) $movingLab[0] = $CIELmax;
        else                    $movingLab[0] = $CIELmin;
      }

      // We're done!
      return new self(self::convert('lab', 'srgb', $movingLab));
    }


    /** Calculates the distance between two colors in a certain format, by adding the difference between each of their properties. */
    public static function distance(self|array|string $color1, self|array|string $color2, string $method = 'CIEDE2000'): float {
      $color1 = self::makeInstance($color1);
      $color2 = self::makeInstance($color2);
      $lab1 = $color1->valuesTo('lab');
      $lab2 = $color2->valuesTo('lab');

      switch ($method) {
        case 'CIEDE2000':
          return distances\CIEDE2000($lab1, $lab2);
        case 'euclidean':
        default:
          $dist = 0;
          foreach($lab1 as $k => $v) {
            $dist += ($v - $lab2[$k]) ** 2;
          }
          return $dist;
      }
    }


    /** Determines if two colors are the same, with a certain tolerance. */
    public static function same(self|array|string $color1, self|array|string $color2, float $tolerance = 1): bool {
      if (self::distance($color1, $color2) > $tolerance) return false;
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
    private static function propertiesOf(string $format): array {
      switch($format) {
        case 'rgb': case 'rgba': return ['r', 'g', 'b'];
        case 'hsl': case 'hsla': return ['h', 's', 'l'];
        case 'hwb': case 'hwba': return ['h', 'w', 'bk'];
        case 'lab': case 'laba': return ['ciel', 'ciea', 'cieb'];
        case 'lch': case 'lcha': return ['ciel', 'ciec', 'cieh'];
        default: return [];
      }
    }

    /** Array of all property names. */
    private static function properties(): array {
      return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
    }

    public const COLOR_SPACES = COLOR_SPACES;

    public static function getSpace(array|string $spaceID): array {
      if (is_array($spaceID)) return $spaceID;
      $id = match ($spaceID) {
        'rgb', 'rgba' => 'srgb',
        'hsla' => 'hsl',
        default => $spaceID
      };
      return Graph::array_find(fn($e) => $e['id'] === $id, self::COLOR_SPACES);
    }

    /** Array of supported syntaxes. */
    private static function formats() { return CSSFormats::formats(); }

    /** List of named colors in CSS. */
    public const COULEURS_NOMMEES = NAMED_COLORS;
  }


} namespace colori {


  class Palette {
    public array $colors;

    function __construct(float $hue, callable $generator, string $clampSpace = 'srgb') {
      $this->colors = [];
      $colors = $generator($hue);

      foreach ($colors as $color) {
        $nuances = [];
        foreach ($color->lightnesses as $lightness) {
          $rgb = Couleur::convert('oklch', 'srgb', [$lightness, $color->chroma, $color->hue]);
          if ($clampSpace != null) $rgb = Couleur::toGamut($clampSpace, $rgb);
          $rgbString = $rgb.implode(' ');
          $newColor = new Couleur("color(srgb $rgbString)");
          $nuances[] = $newColor;
        }
        $this->colors[] = $nuances;
      }
    }
  }


} namespace {

  class Couleur extends colori\Couleur {}
  class Palette extends colori\Palette {}
}