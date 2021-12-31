<?php namespace colori\conversions {


  require_once __DIR__ . '/utils.php';
  require_once __DIR__ . '/ext/w3-conversion.php';
  require_once __DIR__ . '/ext/oklab-conversion.php';


  /* srgb */

  function srgb_to_lin_srgb(array $rgb): array {
    return \colori\ext\srgb_to_lin_srgb($rgb);
  }

  function lin_srgb_to_srgb(array $rgb): array {
    return \colori\ext\lin_srgb_to_srgb($rgb);
  }

  function lin_srgb_to_d65xyz(array $rgb): array {
    return \colori\ext\lin_srgb_to_d65xyz($rgb);
  }

  function d65xyz_to_lin_srgb(array $xyz): array {
    return \colori\ext\d65xyz_to_lin_srgb($xyz);
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

  function displayp3_to_lin_displayp3(array $rgb): array { return \colori\ext\displayp3_to_lin_displayp3($rgb); }
  function lin_displayp3_to_displayp3(array $rgb): array { return \colori\ext\lin_displayp3_to_displayp3($rgb); }

  function lin_displayp3_to_d65xyz(array $rgb): array {
    return \colori\ext\lin_displayp3_to_d65xyz($rgb);
  }

  function d65xyz_to_lin_displayp3(array $xyz): array {
    return \colori\ext\d65xyz_to_lin_displayp3($xyz);
  }


  /* prophoto-rgb */

  function prophotorgb_to_lin_prophotorgb(array $rgb): array {
    return \colori\ext\prophotorgb_to_lin_prophotorgb($rgb);
  }

  function lin_prophotorgb_to_prophotorgb(array $rgb): array {
    return \colori\ext\lin_prophotorgb_to_prophotorgb($rgb);
  }

  function lin_prophotorgb_to_xyz(array $rgb): array {
    return \colori\ext\lin_prophotorgb_to_xyz($rgb);
  }

  function xyz_to_lin_prophotorgb(array $xyz): array {
    return \colori\ext\xyz_to_lin_prophotorgb($xyz);
  }


  /* a98-rgb */

  function a98rgb_to_lin_a98rgb(array $rgb): array {
    return \colori\ext\a98rgb_to_lin_a98rgb($rgb);
  }

  function lin_a98rgb_to_a98rgb(array $rgb): array {
    return \colori\ext\lin_a98rgb_to_a98rgb($rgb);
  }

  function lin_a98rgb_to_d65xyz(array $rgb): array {
    return \colori\ext\lin_a98rgb_to_d65xyz($rgb);
  }

  function d65xyz_to_lin_a98rgb(array $xyz): array {
    return \colori\ext\d65xyz_to_lin_a98rgb($xyz);
  }


  /* rec2020 */

  function rec2020_to_lin_rec2020(array $rgb): array {
    return \colori\ext\rec2020_to_lin_rec2020($rgb);
  }

  function lin_rec2020_to_rec2020(array $rgb): array {
    return \colori\ext\lin_rec2020_to_rec2020($rgb);
  }

  function lin_rec2020_to_d65xyz(array $rgb): array {
    return \colori\ext\lin_rec2020_to_d65xyz($rgb);
  }

  function d65xyz_to_lin_rec2020(array $xyz): array {
    return \colori\ext\d65xyz_to_lin_rec2020($xyz);
  }


  /* lab */

  function xyz_to_lab(array $xyz): array {
    return \colori\ext\xyz_to_lab($xyz);
  }

  function lab_to_xyz(array $lab): array {
    return \colori\ext\lab_to_xyz($lab);
  }

  function lab_to_lch(array $lab): array {
    return \colori\ext\lab_to_lch($lab);
  }


  function lch_to_lab(array $lch): array {
    return \colori\ext\lch_to_lab($lch);
  }


  /* oklab */

  function lin_srgb_to_oklab(array $rgb): array {
    return \colori\ext\lin_srgb_to_oklab($rgb);
  }

  function oklab_to_lin_srgb(array $lab): array {
    return \colori\ext\oklab_to_lin_srgb($lab);
  }

  function oklab_to_oklch(array $lab): array { return lab_to_lch($lab); }
  function oklch_to_oklab(array $lch): array { return lch_to_lab($lch); }



  /* Bradford transform */

  function d65xyz_to_xyz(array $xyz): array {
    return \colori\ext\d65xyz_to_xyz($xyz);
  }

  function xyz_to_d65xyz(array $xyz): array {
    return \colori\ext\xyz_to_d65xyz($xyz);
  }


}