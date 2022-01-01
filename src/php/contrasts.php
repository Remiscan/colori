<?php namespace colori\contrasts {


  require_once __DIR__ . '/conversion.php';
  require_once __DIR__ . '/ext/apca-contrast.php';


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
    return APCAcontrast($rgbText, $rgbBack);
  }


}