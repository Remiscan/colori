<?php namespace colori\conversions {


  require_once __DIR__ . '/utils.php';
  require_once __DIR__ . '/ext/w3-conversion.php';
  require_once __DIR__ . '/ext/oklab-conversion.php';



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


}