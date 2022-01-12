<?php namespace colori\contrasts {


  /******************************************************************
   * Derived from https://github.com/Myndex/apca-w3                 *
   * under W3C license (Copyright © 2019-2021 by Andrew Somers)     *
   ******************************************************************/


  function APCAcontrast(array $rgbText, array $rgbBack): float {
    // Compute luminances (slightly different from luminances used in WCAG2 contrast)
    $coeffs = [0.2126729, 0.7151522, 0.0721750];
    $gamma = 2.4;
    $luminance = function($rgb) use ($coeffs, $gamma) {
      $L = 0;
      foreach ($rgb as $k => $v) {
        $L += $v**$gamma * $coeffs[$k];
      }
      return $L;
    };
    $Ytext = $luminance($rgbText);
    $Yback = $luminance($rgbBack);

    // APCA 0.0.98 G - 4g Constants
    $normBG = 0.56;
    $normTXT = 0.57;
    $revTXT = 0.62;
    $revBG = 0.65;
    $blkThrs = 0.022;
    $blkClmp = 1.414;
    $scaleBoW = 1.14;
    $scaleWoB = 1.14;
    $loBoWoffset = 0.027;
    $loWoBoffset = 0.027;
    $loClip = 0.1;
    $deltaYmin = 0.0005;
    
    // Clamp luminances
    $clamp = fn($Y) => $Y > $blkThrs ? $Y : $Y + ($blkThrs - $Y) ** $blkClmp;
    $Ytext = $clamp($Ytext);
    $Yback = $clamp($Yback);

    // Compute contrast
    if (abs($Ytext - $Yback) < $deltaYmin) return .0;
    $SAPC = .0;
    $output = .0;

    // - for dark text on light background
    if ($Yback > $Ytext) {
      $SAPC = ($Yback ** $normBG - $Ytext ** $normTXT) * $scaleBoW;
      $output = ($SAPC < $loClip) ? .0 : $SAPC - $loBoWoffset;
    }

    // - for light text on dark background
    else {
      $SAPC = ($Yback ** $revBG - $Ytext ** $revTXT) * $scaleWoB;
      $output = ($SAPC > -$loClip) ? .0 : $SAPC + $loWoBoffset;
    }

    return $output * 100.0;
  }
  

}