<?php namespace colori\distances;


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