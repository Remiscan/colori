<?php namespace colori\contrasts {


  require_once __DIR__ . '/conversion.php';


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
    $blackClampTrigger = 0.022;
    $blackClampPow = 1.414;
    $clamp = fn($L) => $L > $blackClampTrigger ? $L : $L + ($blackClampTrigger - $L)**$blackClampPow;
    $Ltext = $clamp($Ltext);
    $Lback = $clamp($Lback);

    $δLmin = 0.0005;
    if (abs($Ltext - $Lback) < $δLmin) return .0;

    // 3. Compute contrast
    $scale = 1.14;
    $compute = fn($Lback, $Ltext, $powBack, $powText) => ($Lback**$powBack - $Ltext**$powText) * $scale;
    $lowClip = 0.001; $lowTrigger = 0.035991; $lowOffset = 0.027; $invLowTrigger = 27.7847239587675;

    // for dark text on light background
    if ($Lback > $Ltext) {
      $powBack = 0.56; $powText = 0.57;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC < $lowClip) ? 0
              : (($SAPC < $lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC - $lowOffset);
    }

    // for light text on dark background
    else {
      $powBack = 0.65; $powText = 0.62;
      $SAPC = $compute($Lback, $Ltext, $powBack, $powText);
      $result = ($SAPC > -$lowClip) ? 0
              : (($SAPC > -$lowTrigger) ? $SAPC * (1 - $lowOffset * $invLowTrigger)
              : $SAPC + $lowOffset);
    }

    return (float) $result * 100;
  }


}