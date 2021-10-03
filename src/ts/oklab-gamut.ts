import * as Conversions from './conversion.js';



// Source of the math: https://bottosson.github.io/posts/gamutclipping/


export function maxSaturation(a: number, b: number): number {
  let k0, k1, k2, k3, k4, wl, wm, ws;

  // If red goes negative first
  if (-1.88170328 * a - 0.80936493 * b > 1) {
      k0 = 1.19086277; k1 = 1.76576728; k2 = 0.59662641; k3 = 0.75515197; k4 = 0.56771245;
      wl = 4.0767416621; wm = -3.3077115913; ws = 0.2309699292;
  }
  // If green goes negative first
  else if (1.81444104 * a - 1.19445276 * b > 1) {
      k0 = 0.73956515; k1 = -0.45954404; k2 = 0.08285427; k3 = 0.12541070; k4 = 0.14503204;
      wl = -1.2684380046; wm = 2.6097574011; ws = -0.3413193965;
  }
  // If blue goes negative first
  else {
      k0 = 1.35733652; k1 = -0.00915799; k2 = -1.15130210; k3 = -0.50559606; k4 = 0.00692167;
      wl = -0.0041960863; wm = -0.7034186147; ws = +1.7076147010;
  }

  // Approximate max saturation
  let S = k0 + k1 * a + k2 * b + k3 * a * a + k4 * a * b;

  // Do one step Halley's method to get closer
  const k_l = 0.3963377774 * a + 0.2158037573 * b;
  const k_m = -0.1055613458 * a - 0.0638541728 * b;
  const k_s = -0.0894841775 * a - 1.2914855480 * b;

  const [l_, m_, s_] = [k_l, k_m, k_s].map(v => 1 + S * v);
  const [l, m, s] = [l_, m_, s_].map(v => v ** 3);

  const l_dS = 3 * k_l * l_ * l_,
        m_dS = 3 * k_m * m_ * m_,
        s_dS = 3 * k_s * s_ * s_;

  const l_dS2 = 6 * k_l * k_l * l_,
        m_dS2 = 6 * k_m * k_m * m_,
        s_dS2 = 6 * k_s * k_s * s_;

  const f  = wl * l     + wm * m     + ws * s,
        f1 = wl * l_dS  + wm * m_dS  + ws * s_dS,
        f2 = wl * l_dS2 + wm * m_dS2 + ws * s_dS2;

  S = S - f * f1 / (f1*f1 - 0.5 * f * f2);
  return S;
}


export function cusp(a: number, b: number): number[] {
  const Scusp = maxSaturation(a, b);

  const rgbMax = Conversions.oklab_to_lin_srgb([1, Scusp * a, Scusp * b]);
  const Lcusp = Math.cbrt(1 / Math.max(...rgbMax));
  const Ccusp = Lcusp * Scusp;

  return [Lcusp, Ccusp];
}


export function gamutIntersection(a: number, b: number, L1: number, C1: number, L0: number): number {
  const [Lcusp, Ccusp] = cusp(a, b);

  let t;
  if (((L1 - L0) * Ccusp - (Lcusp - L0) * C1) <= 0) {
    t = Ccusp * L0 / (C1 * Lcusp + Ccusp * (L0 - L1));
  } else {
    t = Ccusp * (L0 - 1) / (C1 * (Lcusp - 1) + Ccusp * (L0 - L1));

    const dL = L1 - L0,
          dC = C1;
    
    const k_l = 0.3963377774 * a + 0.2158037573 * b,
          k_m = -0.1055613458 * a - 0.0638541728 * b,
          k_s = -0.0894841775 * a - 1.2914855480 * b;

    const [l_dt, m_dt, s_dt] = [k_l, k_m, k_s].map(v => dL + dC * v);

    for (let i = 0; i < 2; i++) {
      const L = L0 * (1 - t) + t * L1;
      const C = t * C1;

      const [l_, m_, s_] = [k_l, k_m, k_s].map(v => L + C * v);
      const [l, m, s] = [l_, m_, s_].map(v => v ** 3);
      const ldt = 3 * l_dt * l_ * l_,
            mdt = 3 * m_dt * m_ * m_,
            sdt = 3 * s_dt * s_ * s_;
      const ldt2 = 6 * l_dt * l_dt * l_,
            mdt2 = 6 * m_dt * m_dt * m_,
            sdt2 = 6 * s_dt * s_dt * s_;

      const term = (v1, v2, v3) => {
        const w = v1 * l + v2 * m + v3 * s - 1,
              w1 = v1 * ldt + v2 * mdt + v3 * sdt,
              w2 = v1 * ldt2 + v2 * mdt2 + v3 * sdt2;

        const u = w1 / (w1 * w1 - .5 * w * w2);
        const t = u >= 0 ? (-w * u) : Number.MAX_VALUE;
        return t;
      };

      const t_r = term(4.0767416621, -3.3077115913, 0.2309699292);
      const t_g = term(-1.2684380046, 2.6097574011, -0.3413193965);
      const t_b = term(-0.0041960863, -0.7034186147, 1.7076147010);

      t += Math.min(t_r, t_g, t_b);
    }
  }

  return t;
}


export function clip(rgb: number[]): number[] {
  if (rgb.every(v => v > 0 && v < 1)) return rgb;
  
  const [okl, oka, okb] = Conversions.lin_srgb_to_oklab(Conversions.srgb_to_lin_srgb(rgb));
  const [x, okc, okh] = Conversions.oklab_to_oklch([okl, oka, okb]);
  
  const τ = .00001;
  const α = .05;
  const C = Math.max(τ, okc);
  const a = oka / C, b = okb / C;
  
  const Ld = okl - .5;
  const e1 = .5 + Math.abs(Ld) + α * C;
  const L0 = .5 * (1 + Math.sign(Ld) * (e1 - Math.sqrt(e1 * e1 - 2 * Math.abs(Ld))));
  
  const t = gamutIntersection(a, b, okl, C, L0);
  const Lclipped = L0 * (1 - t) + t * okl;
  const Cclipped = t * C;
  
  const clampedValues = Conversions.lin_srgb_to_srgb(Conversions.oklab_to_lin_srgb([Lclipped, Cclipped * a, Cclipped * b]));
  return clampedValues;
}