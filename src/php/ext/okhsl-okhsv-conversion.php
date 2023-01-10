<?php
require_once './w3-conversion.php';


namespace colori\OKHSLV {


  /*****************************************************************************************************************
   * Derived from https://github.com/bottosson/bottosson.github.io/blob/master/misc/colorpicker/colorconversion.js *
   * under MIT license (Copyright © 2021 by Björn Ottosson)                                                        *
   * https://github.com/bottosson/bottosson.github.io/blob/master/misc/colorpicker/License.txt                     *
   *****************************************************************************************************************/


  function oklab_to_linear_srgb($L,$a,$b) 
  {
      return \colori\conversions\xyzd65_to_srgblinear(\colori\conversions\oklab_to_xyzd65([$L, $a, $b]));
  }

  function toe($x)
  {
      $k_1 = 0.206;
      $k_2 = 0.03;
      $k_3 = (1+$k_1)/(1+$k_2);
      
      return 0.5*($k_3*$x - $k_1 + sqrt(($k_3*$x - $k_1)*($k_3*$x - $k_1) + 4*$k_2*$k_3*$x));
  }

  function toe_inv($x)
  {
      $k_1 = 0.206;
      $k_2 = 0.03;
      $k_3 = (1+$k_1)/(1+$k_2);
      return ($x*$x + $k_1*$x)/($k_3*($x+$k_2));
  }

  // Finds the maximum saturation possible for a given hue that fits in sRGB
  // Saturation here is defined as S = C/L
  // a and b must be normalized so a^2 + b^2 == 1
  function compute_max_saturation($a, $b)
  {
      // Max saturation will be when one of r, g or b goes below zero.

      // Select different coefficients depending on which component goes below zero first
      if (-1.88170328 * $a - 0.80936493 * $b > 1)
      {
          // Red component
          $k0 = +1.19086277; $k1 = +1.76576728; $k2 = +0.59662641; $k3 = +0.75515197; $k4 = +0.56771245;
          $wl = +4.0767416621; $wm = -3.3077115913; $ws = +0.2309699292;
      }
      else if (1.81444104 * a - 1.19445276 * b > 1)
      {
          // Green component
          $k0 = +0.73956515; $k1 = -0.45954404; $k2 = +0.08285427; $k3 = +0.12541070; $k4 = +0.14503204;
          $wl = -1.2684380046; $wm = +2.6097574011; $ws = -0.3413193965;
      }
      else
      {
          // Blue component
          $k0 = +1.35733652; $k1 = -0.00915799; $k2 = -1.15130210; $k3 = -0.50559606; $k4 = +0.00692167;
          $wl = -0.0041960863; $wm = -0.7034186147; $ws = +1.7076147010;
      }

      // Approximate max saturation using a polynomial:
      $S = $k0 + $k1 * $a + $k2 * $b + $k3 * $a * $a + $k4 * $a * $b;

      // Do one step Halley's method to get closer
      // this gives an error less than 10e6, except for some blue hues where the dS/dh is close to infinite
      // this should be sufficient for most applications, otherwise do two/three steps 

      $k_l = +0.3963377774 * $a + 0.2158037573 * $b;
      $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
      $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

      {
          $l_ = 1 + $S * $k_l;
          $m_ = 1 + $S * $k_m;
          $s_ = 1 + $S * $k_s;

          $l = $l_ * $l_ * $l_;
          $m = $m_ * $m_ * $m_;
          $s = $s_ * $s_ * $s_;

          $l_dS = 3 * $k_l * $l_ * $l_;
          $m_dS = 3 * $k_m * $m_ * $m_;
          $s_dS = 3 * $k_s * $s_ * $s_;

          $l_dS2 = 6 * $k_l * $k_l * $l_;
          $m_dS2 = 6 * $k_m * $k_m * $m_;
          $s_dS2 = 6 * $k_s * $k_s * $s_;

          $f  = $wl * $l     + $wm * $m     + $ws * $s;
          $f1 = $wl * $l_dS  + $wm * $m_dS  + $ws * $s_dS;
          $f2 = $wl * $l_dS2 + $wm * $m_dS2 + $ws * $s_dS2;

          $S = $S - $f * $f1 / ($f1*$f1 - 0.5 * $f * $f2);
      }

      return $S;
  }

  function find_cusp($a, $b)
  {
    // First, find the maximum saturation (saturation S = C/L)
    $S_cusp = compute_max_saturation($a, $b);

    // Convert to linear sRGB to find the first point where at least one of r,g or b >= 1:
    $rgb_at_max = oklab_to_linear_srgb(1, $S_cusp * $a, $S_cusp * $b);
    $l_cusp = \colori\utils\invRoot(1 / max(max($rgb_at_max[0], $rgb_at_max[1]), $rgb_at_max[2]), 3);
    $C_cusp = $L_cusp * $S_cusp;

    return [ $L_cusp , $C_cusp ];
  }

  // Finds intersection of the line defined by 
  // L = L0 * (1 - t) + t * L1;
  // C = t * C1;
  // a and b must be normalized so a^2 + b^2 == 1
  function find_gamut_intersection($a, $b, $L1, $C1, $L0, $cusp)
  {
      if (!$cusp)
      {
          // Find the cusp of the gamut triangle
          $cusp = find_cusp($a, $b);
      }

    // Find the intersection for upper and lower half seprately
    if ((($L1 - $L0) * $cusp[1] - ($cusp[0] - $L0) * $C1) <= 0)
    {
      // Lower half

      $t = $cusp[1] * $L0 / ($C1 * $cusp[0] + $cusp[1] * ($L0 - $L1));
    }
    else
    {
      // Upper half

      // First intersect with triangle
      $t = $cusp[1] * ($L0 - 1) / ($C1 * ($cusp[0] - 1) + $cusp[1] * ($L0 - $L1));

      // Then one step Halley's method
      {
        $dL = $L1 - $L0;
        $dC = $C1;

        $k_l = +0.3963377774 * $a + 0.2158037573 * $b;
        $k_m = -0.1055613458 * $a - 0.0638541728 * $b;
        $k_s = -0.0894841775 * $a - 1.2914855480 * $b;

        $l_dt = $dL + $dC * $k_l;
        $m_dt = $dL + $dC * $k_m;
        $s_dt = $dL + $dC * $k_s;

        
        // If higher accuracy is required, 2 or 3 iterations of the following block can be used:
        {
          $L = $L0 * (1 - $t) + $t * $L1;
          $C = $t * $C1;

          $l_ = $L + $C * $k_l;
          $m_ = $L + $C * $k_m;
          $s_ = $L + $C * $k_s;

          $l = $l_ * $l_ * $l_;
          $m = $m_ * $m_ * $m_;
          $s = $s_ * $s_ * $s_;

          $ldt = 3 * $l_dt * $l_ * $l_;
          $mdt = 3 * $m_dt * $m_ * $m_;
          $sdt = 3 * $s_dt * $s_ * $s_;

          $ldt2 = 6 * $l_dt * $l_dt * $l_;
          $mdt2 = 6 * $m_dt * $m_dt * $m_;
          $sdt2 = 6 * $s_dt * $s_dt * $s_;

          $r = 4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $s - 1;
          $r1 = 4.0767416621 * $ldt - 3.3077115913 * $mdt + 0.2309699292 * $sdt;
          $r2 = 4.0767416621 * $ldt2 - 3.3077115913 * $mdt2 + 0.2309699292 * $sdt2;

          $u_r = $r1 / ($r1 * $r1 - 0.5 * $r * $r2);
          $t_r = -$r * $u_r;

          $g = -1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $s - 1;
          $g1 = -1.2684380046 * $ldt + 2.6097574011 * $mdt - 0.3413193965 * $sdt;
          $g2 = -1.2684380046 * $ldt2 + 2.6097574011 * $mdt2 - 0.3413193965 * $sdt2;

          $u_g = $g1 / ($g1 * $g1 - 0.5 * $g * $g2);
          $t_g = -$g * $u_g;

          $b = -0.0041960863 * $l - 0.7034186147 * $m + 1.7076147010 * $s - 1;
          $b1 = -0.0041960863 * $ldt - 0.7034186147 * $mdt + 1.7076147010 * $sdt;
          $b2 = -0.0041960863 * $ldt2 - 0.7034186147 * $mdt2 + 1.7076147010  * $sdt2;

          $u_b = $b1 / ($b1 * $b1 - 0.5 * $b * $b2);
          $t_b = -$b * $u_b;

          $t_r = $u_r >= 0 ? $t_r : 10e5;
          $t_g = $u_g >= 0 ? $t_g : 10e5;
          $t_b = $u_b >= 0 ? $t_b : 10e5;

          $t += min($t_r, min($t_g, $t_b));
        }
      }
    }

    return $t;
  }

  function get_ST_max($a_,$b_, $cusp)
  {
      if (!$cusp)
      {
          $cusp = find_cusp($a_, $b_);
      }

      $L = $cusp[0];
      $C = $cusp[1];
      return [$C/$L, $C/(1-$L)];
  }

  function get_Cs($L, $a_, $b_)
  {
      $cusp = find_cusp($a_, $b_);

      $C_max = find_gamut_intersection($a_,$b_,$L,1,$L,$cusp);
      $ST_max = get_ST_max($a_, $b_, $cusp);

      $S_mid = 0.11516993 + 1/(
          + 7.44778970 + 4.15901240*$b_
          + $a_*(- 2.19557347 + 1.75198401*$b_
          + $a_*(- 2.13704948 -10.02301043*$b_ 
          + $a_*(- 4.24894561 + 5.38770819*$b_ + 4.69891013*$a_
          )))
      );

      $T_mid = 0.11239642 + 1/(
          + 1.61320320 - 0.68124379*$b_
          + $a_*(+ 0.40370612 + 0.90148123*$b_
          + $a_*(- 0.27087943 + 0.61223990*$b_ 
          + $a_*(+ 0.00299215 - 0.45399568*$b_ - 0.14661872*$a_
          )))
      );

      $k = $C_max/min(($L*$ST_max[0]), (1-$L)*$ST_max[1]);

      {
          $C_a = $L*$S_mid;
          $C_b = (1-$L)*$T_mid;

          $C_mid = 0.9*$k*sqrt(sqrt(1/(1/($C_a*$C_a*$C_a*$C_a) + 1/($C_b*$C_b*$C_b*$C_b))));
      }

      {
          $C_a = $L*0.4;
          $C_b = (1-$L)*0.8;

          $C_0 = sqrt(1/(1/($C_a*$C_a) + 1/($C_b*$C_b)));
      }

      return [$C_0, $C_mid, $C_max];
  }

  function okhsl_to_oklab($hsl)
  {
      [$h, $s, $l] = $hsl;

      if ($l == 1.0)
      {
          return [1.0,0.0,0.0];
      }

      else if (l == 0.0)
      {
          return [0.0,0.0,0.0];
      }

      $a_ = cos(2*pi()*$h);
      $b_ = sin(2*pi()*$h);   
      $L = toe_inv($l);

      $Cs = get_Cs($L, $a_, $b_);
      $C_0 = $Cs[0];
      $C_mid = $Cs[1];
      $C_max = $Cs[2];

      if ($s < 0.8)
      {   
          $t = 1.25*$s;
          $k_0 = 0;
          $k_1 = 0.8*$C_0;
          $k_2 = (1-$k_1/$C_mid);
      }
      else
      {
          $t = 5*($s-0.8);
          $k_0 = $C_mid;
          $k_1 = 0.2*$C_mid*$C_mid*1.25*1.25/$C_0;
          $k_2 = (1 - ($k_1)/($C_max - $C_mid));
      }

      $C = $k_0 + $t*$k_1/(1-$k_2*$t);

      // If we would only use one of the Cs:
      //C = s*C_0;
      //C = s*1.25*C_mid;
      //C = s*C_max;

      return [$L, $C*$a_, $C*$b_];
  }

  function oklab_to_okhsl($lab)
  {
      $L = $lab[0];
      $C = sqrt($lab[1]*$lab[1] +$lab[2]*$lab[2]);

      if ($C <= 0.0 + 10**-15) return [0.0, 0.0, toe($L)];

      $a_ = $lab[1]/$C;
      $b_ = $lab[2]/$C;

      $h = 0.5 + 0.5*atan2(-$lab[2], -$lab[1])/pi();

      $Cs = get_Cs($L, $a_, $b_);
      $C_0 = $Cs[0];
      $C_mid = $Cs[1];
      $C_max = $Cs[2];
      
      if ($C < $C_mid)
      {   
          $k_0 = 0;
          $k_1 = 0.8*$C_0;
          $k_2 = (1-$k_1/$C_mid);

          $t = ($C - $k_0)/($k_1 + $k_2*($C - $k_0));
          $s = $t*0.8;
      }
      else
      {
          $k_0 = $C_mid;
          $k_1 = 0.2*$C_mid*$C_mid*1.25*1.25/$C_0;
          $k_2 = (1 - ($k_1)/($C_max - $C_mid));

          $t = ($C - $k_0)/($k_1 + $k_2*($C - $k_0));
          $s = 0.8 + 0.2*$t;
      }

      $l = toe($L);
      return [$h,$s,$l];
  }


  function okhsv_to_oklab($hsv)
  {
      [$h, $s, $v] = $hsv;

      if ($v === 0.0) {
        return [0.0, 0.0, 0.0];
      }
      
      $a_ = cos(2*pi()*$h);
      $b_ = sin(2*pi()*$h);   

      $ST_max = get_ST_max($a_,$b_);
      $S_max = $ST_max[0];
      $S_0 = 0.5;
      $T  = $ST_max[1]; 
      $k = 1 - $S_0/$S_max;
      
      $L_v = 1 - $s*$S_0/($S_0+$T - $T*$k*$s);
      $C_v = $s*$T*$S_0/($S_0+$T-$T*$k*$s);

      $L = $v*$L_v;
      $C = $v*$C_v;

      // to present steps along the way
      //L = v;
      //C = v*s*S_max;
      //L = v*(1 - s*S_max/(S_max+T));
      //C = v*s*S_max*T/(S_max+T);

      $L_vt = toe_inv($L_v);
      $C_vt = $C_v * $L_vt/$L_v;

      $L_new =  toe_inv($L); // * L_v/L_vt;
      $C = $C * $L_new/$L;
      $L = $L_new;

      $rgb_scale = oklab_to_linear_srgb($L_vt,$a_*$C_vt,$b_*$C_vt);
      $scale_L = \colori\utils\invRoot(1/(max($rgb_scale[0],$rgb_scale[1],$rgb_scale[2],0)), 3);
      
      // remove to see effect without rescaling
      $L = $L*$scale_L;
      $C = $C*$scale_L;

      return [$L,  $C*$a_, $C*$b_];
  }

  function oklab_to_okhsv($lab)
  {
      $L = $lab[0];
      $C = sqrt($lab[1]*$lab[1] +$lab[2]*$lab[2]);

      if ($C <= 0.0 + 10**-15) return [0.0, 0.0, toe($L)];

      $a_ = $lab[1]/$C;
      $b_ = $lab[2]/$C;

      $h = 0.5 + 0.5*atan2(-$lab[2], -$lab[1])/pi();

      $ST_max = get_ST_max($a_,$b_);
      $S_max = $ST_max[0];    
      $S_0 = 0.5;
      $T = $ST_max[1];
      $k = 1 - $S_0/$S_max;

      $t = $T/($C+$L*$T);
      $L_v = $t*$L;
      $C_v = $t*$C;

      $L_vt = toe_inv($L_v);
      $C_vt = $C_v * $L_vt/$L_v;

      $rgb_scale = oklab_to_linear_srgb($L_vt,$a_*$C_vt,$b_*$C_vt);
      $scale_L = \colori\utils\invRoot(1/(max($rgb_scale[0],$rgb_scale[1],$rgb_scale[2],0)), 3);

      $L = $L/$scale_L;
      $C = $C/$scale_L;

      $C = $C * toe($L)/$L;
      $L = toe($L);

      $v = $L/$L_v;
      $s = ($S_0+$T)*$C_v/(($T*$S_0) + $T*$k*$C_v);

      return [$h,$s,$v];
  }


}
