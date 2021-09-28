<?php namespace colori\oklab_gamut {


  require_once __DIR__ . '/conversion.php';


  // Source of the math: https://bottosson.github.io/posts/gamutclipping/

  function maxSaturation(float $a, float $b): float {
    // If red goes negative first
    if (-1.88170328 * $a - 0.80936493 * $b > 1) {
        $k0 = 1.19086277; $k1 = 1.76576728; $k2 = 0.59662641; $k3 = 0.75515197; $k4 = 0.56771245;
        $wl = 4.0767416621; $wm = -3.3077115913; $ws = 0.2309699292;
    }
    // If green goes negative first
    else if (1.81444104 * $a - 1.19445276 * $b > 1) {
        $k0 = 0.73956515; $k1 = -0.45954404; $k2 = 0.08285427; $k3 = 0.12541070; $k4 = 0.14503204;
        $wl = -1.2684380046; $wm = 2.6097574011; $ws = -0.3413193965;
    }
    // If blue goes negative first
    else {
        $k0 = 1.35733652; $k1 = -0.00915799; $k2 = -1.15130210; $k3 = -0.50559606; $k4 = 0.00692167;
        $wl = -0.0041960863; $wm = -0.7034186147; $ws = +1.7076147010;
    }

    // Approximate max saturation
    $S = $k0 + $k1 * $a + $k2 * $b + $k3 * $a * $a + $k4 * $a * $b;

    // Do one step Halley's method to get closer
    $k_l = 0.3963377774 * $a + 0.2158037573 * $b;
    $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
    $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

    $l_ = 1 + $S * $k_l;
    $m_ = 1 + $S * $k_m;
    $s_ = 1 + $S * $k_s;

    $l = $l_ ** 3;
    $m = $m_ ** 3;
    $s = $s_ ** 3;

    $l_dS = 3 * $k_l * $l_ * $l_;
    $m_dS = 3 * $k_m * $m_ * $m_;
    $s_dS = 3 * $k_s * $s_ * $s_;

    $l_dS2 = 6 * $k_l * $k_l * $l_;
    $m_dS2 = 6 * $k_m * $k_m * $m_;
    $s_dS2 = 6 * $k_s * $k_s * $s_;

    $f  = $wl * $l     + $wm * $m     + $ws * $s;
    $f1 = $wl * $l_dS  + $wm * $m_dS  + $ws * $s_dS;
    $f2 = $wl * $l_dS2 + $wm * $m_dS2 + $ws * $s_dS2;

    $S = $S - $f * $f1 / ($f1 * $f1 - 0.5 * $f * $f2);
    return $S;
  }


  function cusp(float $a, float $b): array {
    $Scusp = maxSaturation($a, $b);

    $rgbMax = \colori\conversions\oklab_to_lin_srgb([1, $Scusp * $a, $Scusp * $b]);
    $Lcusp = pow(1 / max($rgbMax), 1/3);
    $Ccusp = $Lcusp * $Scusp;

    return [$Lcusp, $Ccusp];
  }


  function gamutIntersection(float $a, float $b, float $L1, float $C1, float $L0): float {
    [$Lcusp, $Ccusp] = cusp($a, $b);

    if ((($L1 - $L0) * $Ccusp - ($Lcusp - $L0) * $C1) <= 0) {
      $t = $Ccusp * $L0 / ($C1 * $Lcusp + $Ccusp * ($L0 - $L1));
    } else {
      $t = $Ccusp * ($L0 - 1) / ($C1 * ($Lcusp - 1) + $Ccusp * ($L0 - $L1));

      $dL = $L1 - $L0;
      $dC = $C1;
      
      $k_l = 0.3963377774 * $a + 0.2158037573 * $b;
      $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
      $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

      $l_dt = $dL + $dC * $k_l;
      $m_dt = $dL + $dC * $k_m;
      $s_dt = $dL + $dC * $k_s;

      for ($i = 0; $i < 2; $i++) {
        $L = $L0 * (1 - $t) + $t * $L1;
        $C = $t * $C1;

        $l_ = $L + $C * $k_l;
        $m_ = $L + $C * $k_m;
        $s_ = $L + $C * $k_s;

        $l = $l_ ** 3;
        $m = $m_ ** 3;
        $s = $s_ ** 3;

        $ldt = 3 * $l_dt * $l_ * $l_;
        $mdt = 3 * $m_dt * $m_ * $m_;
        $sdt = 3 * $s_dt * $s_ * $s_;

        $ldt2 = 6 * $l_dt * $l_dt * $l_;
        $mdt2 = 6 * $m_dt * $m_dt * $m_;
        $sdt2 = 6 * $s_dt * $s_dt * $s_;

        $term = function(float $v1, float $v2, float $v3) use ($l, $m, $s, $ldt, $mdt, $sdt, $ldt2, $mdt2, $sdt2) {
          $w = $v1 * $l + $v2 * $m + $v3 * $s - 1;
          $w1 = $v1 * $ldt + $v2 * $mdt + $v3 * $sdt;
          $w2 = $v1 * $ldt2 + $v2 * $mdt2 + $v3 * $sdt2;

          $u = $w1 / ($w1 * $w1 - .5 * $w * $w2);
          $t = $u >= 0 ? (-$w * $u) : PHP_FLOAT_MAX;
          return $t;
        };

        $t_r = $term(4.0767416621, -3.3077115913, 0.2309699292);
        $t_g = $term(-1.2684380046, 2.6097574011, -0.3413193965);
        $t_b = $term(-0.0041960863, -0.7034186147, 1.7076147010);

        $t += min($t_r, $t_g, $t_b);
      }
    }

    return $t;
  }


  function clip(array $rgb): array {
    $continue = false;
    foreach ($rgb as $v) {
      if ($v < 0 || $v > 1) { $continue = true; break; }
    }
    if ($continue === false) return $rgb;
    
    [$okl, $oka, $okb] = \colori\conversions\lin_srgb_to_oklab(\colori\conversions\srgb_to_lin_srgb($rgb));
    [$x, $okc, $okh] = \colori\conversions\oklab_to_oklch([$okl, $oka, $okb]);
    
    $τ = .00001;
    $α = .05;
    $C = max($τ, $okc);
    $a = $oka / $C; $b = $okb / $C;
    
    $Ld = $okl - .5;
    $e1 = .5 + abs($Ld) + $α * $C;
    $sign = function(int|float $n): int { return ($n > 0) - ($n < 0); };
    $L0 = .5 * (1 + $sign($Ld) * ($e1 - sqrt($e1 * $e1 - 2 * abs($Ld))));
    
    $t = gamutIntersection($a, $b, $okl, $C, $L0);
    $Lclipped = $L0 * (1 - $t) + $t * $okl;
    $Cclipped = $t * $C;
    
    $clampedValues = \colori\conversions\lin_srgb_to_srgb(\colori\conversions\oklab_to_lin_srgb([$Lclipped, $Cclipped * $a, $Cclipped * $b]));
    return $clampedValues;
  }


}