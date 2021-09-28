<?php namespace colori\conversions;


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