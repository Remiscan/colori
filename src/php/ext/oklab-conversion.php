<?php namespace colori\conversions {


  require_once __DIR__ . '/ext/w3-conversion.php';



  /*****************************************************************
   * Derived from https://bottosson.github.io/posts/gamutclipping/ *
   * under MIT license (Copyright (c) 2021 Björn Ottosson)         *
   *****************************************************************/



  function lin_srgb_to_oklab(array $rgb): array {
    [$r, $g, $b] = $rgb;

    $l = 0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b;
    $m = 0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b;
    $s = 0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b;
    $l = \colori\utils\invRoot($l, 3);
    $m = \colori\utils\invRoot($m, 3);
    $s = \colori\utils\invRoot($s, 3);
      
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
    $l = $l ** 3;
    $m = $m ** 3;
    $s = $s ** 3;

    $r = 4.0767416621 * $l + -3.3077115913 * $m + 0.2309699292 * $s;
    $g = -1.2684380046 * $l + 2.6097574011 * $m + -0.3413193965 * $s;
    $b = -0.0041960863 * $l + -0.7034186147 * $m + 1.7076147010 * $s;

    return [$r, $g, $b];
  }

  function oklab_to_oklch(array $lab): array { return lab_to_lch($lab); }
  function oklch_to_oklab(array $lch): array { return lch_to_lab($lch); }


}