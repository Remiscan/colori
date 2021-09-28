<?php namespace colori\utils {


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


}