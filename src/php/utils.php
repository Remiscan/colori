<?php namespace colori\utils {


  function pad(string $s): string {
    return (strlen($s) < 2) ? '0' . $s : $s;
  }

  function angleToRange(float $angle): float {
    while ($angle < 0)   $angle += 360;
    while ($angle >= 360) $angle -= 360;
    return (float) $angle;
  }

  function pRound(float $number, int $precision = 5): float {
    $x = (float) $number;
    $intDigits = ($x !== 0) ? floor(log10($x > 0 ? $x : -$x) + 1) : 1;
    $precision = (int) ($precision - $intDigits);
    return (float) round($x, $precision);
  }

  function invRoot(float $number, int $exponent): float {
    $sign = $number < 0 ? -1 : 1;
    if ($sign === -1 && $exponent % 2 === 0) return NAN;
    return $sign * pow(abs($number), 1 / $exponent);
  }

  function toHex(array $rgba): array {
    return array_map(fn($v) => pad(dechex(round($v * 255))), $rgba);
  }

  function fromHex(array $hexa): array {
    foreach ($hexa as $k => $v) {
      $v = (strlen($v) === 1) ? $v.$v : $v;
      $v = intval(hexdec($v)) / 255;
      $hexa[$k] = $v;
    }
    return $hexa;
  }


}