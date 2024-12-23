var __defProp = Object.defineProperty;
var __defProps = Object.defineProperties;
var __getOwnPropDescs = Object.getOwnPropertyDescriptors;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __typeError = (msg) => {
  throw TypeError(msg);
};
var __pow = Math.pow;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};
var __spreadProps = (a, b) => __defProps(a, __getOwnPropDescs(b));
var __export = (target, all) => {
  for (var name in all)
    __defProp(target, name, { get: all[name], enumerable: true });
};
var __publicField = (obj, key, value) => __defNormalProp(obj, typeof key !== "symbol" ? key + "" : key, value);
var __accessCheck = (obj, member, msg) => member.has(obj) || __typeError("Cannot " + msg);
var __privateGet = (obj, member, getter) => (__accessCheck(obj, member, "read from private field"), getter ? getter.call(obj) : member.get(obj));
var __privateAdd = (obj, member, value) => member.has(obj) ? __typeError("Cannot add the same private member more than once") : member instanceof WeakSet ? member.add(obj) : member.set(obj, value);
var __privateSet = (obj, member, value, setter) => (__accessCheck(obj, member, "write to private field"), setter ? setter.call(obj, value) : member.set(obj, value), value);
var __privateWrapper = (obj, member, setter, getter) => ({
  set _(value) {
    __privateSet(obj, member, value, setter);
  },
  get _() {
    return __privateGet(obj, member, getter);
  }
});

// src/ts/contrasts.ts
var contrasts_exports = {};
__export(contrasts_exports, {
  APCA: () => APCAcontrast,
  WCAG2: () => WCAG2,
  luminance: () => luminance2
});

// src/ts/conversion.ts
var conversion_exports = {};
__export(conversion_exports, {
  a98rgb_to_a98rgblinear: () => a98rgb_to_a98rgblinear,
  a98rgblinear_to_a98rgb: () => a98rgblinear_to_a98rgb,
  a98rgblinear_to_xyzd65: () => a98rgblinear_to_xyzd65,
  displayp3_to_displayp3linear: () => displayp3_to_displayp3linear,
  displayp3linear_to_displayp3: () => displayp3linear_to_displayp3,
  displayp3linear_to_xyzd65: () => displayp3linear_to_xyzd65,
  hsl_to_hwb: () => hsl_to_hwb,
  hsl_to_srgb: () => hsl_to_srgb,
  hwb_to_hsl: () => hwb_to_hsl,
  lab_to_lch: () => lab_to_lch,
  lab_to_xyzd50: () => lab_to_xyzd50,
  lch_to_lab: () => lch_to_lab,
  okhsl_to_oklab: () => okhsl_to_oklab2,
  okhsv_to_oklab: () => okhsv_to_oklab2,
  oklab_to_okhsl: () => oklab_to_okhsl2,
  oklab_to_okhsv: () => oklab_to_okhsv2,
  oklab_to_oklch: () => oklab_to_oklch,
  oklab_to_oklrab: () => oklab_to_oklrab,
  oklab_to_xyzd65: () => oklab_to_xyzd65,
  oklch_to_oklab: () => oklch_to_oklab,
  oklch_to_oklrch: () => oklch_to_oklrch,
  oklrab_to_oklab: () => oklrab_to_oklab,
  oklrch_to_oklch: () => oklrch_to_oklch,
  prophotorgb_to_prophotorgblinear: () => prophotorgb_to_prophotorgblinear,
  prophotorgblinear_to_prophotorgb: () => prophotorgblinear_to_prophotorgb,
  prophotorgblinear_to_xyzd50: () => prophotorgblinear_to_xyzd50,
  rec2020_to_rec2020linear: () => rec2020_to_rec2020linear,
  rec2020linear_to_rec2020: () => rec2020linear_to_rec2020,
  rec2020linear_to_xyzd65: () => rec2020linear_to_xyzd65,
  srgb_to_hsl: () => srgb_to_hsl,
  srgb_to_srgblinear: () => srgb_to_srgblinear,
  srgblinear_to_srgb: () => srgblinear_to_srgb,
  srgblinear_to_xyzd65: () => srgblinear_to_xyzd65,
  xyzd50_to_lab: () => xyzd50_to_lab,
  xyzd50_to_prophotorgblinear: () => xyzd50_to_prophotorgblinear,
  xyzd50_to_xyzd65: () => xyzd50_to_xyzd65,
  xyzd65_to_a98rgblinear: () => xyzd65_to_a98rgblinear,
  xyzd65_to_displayp3linear: () => xyzd65_to_displayp3linear,
  xyzd65_to_oklab: () => xyzd65_to_oklab,
  xyzd65_to_rec2020linear: () => xyzd65_to_rec2020linear,
  xyzd65_to_srgblinear: () => xyzd65_to_srgblinear,
  xyzd65_to_xyzd50: () => xyzd65_to_xyzd50
});

// src/ts/ext/w3-conversion.ts
function srgb_to_srgblinear(rgb) {
  return rgb.map((x) => Math.abs(x) < 0.04045 ? x / 12.92 : (Math.sign(x) || 1) * Math.pow((Math.abs(x) + 0.055) / 1.055, 2.4));
}
function srgblinear_to_srgb(rgb) {
  return rgb.map((x) => Math.abs(x) > 31308e-7 ? (Math.sign(x) || 1) * (1.055 * Math.pow(Math.abs(x), 1 / 2.4) - 0.055) : 12.92 * x);
}
function srgblinear_to_xyzd65(rgb) {
  const [r, g, b] = rgb;
  return [
    0.41239079926595934 * r + 0.357584339383878 * g + 0.1804807884018343 * b,
    0.21263900587151027 * r + 0.715168678767756 * g + 0.07219231536073371 * b,
    0.01933081871559182 * r + 0.11919477979462598 * g + 0.9505321522496607 * b
  ];
}
function xyzd65_to_srgblinear(xyz) {
  const [x, y, z] = xyz;
  return [
    3.2409699419045226 * x + -1.537383177570094 * y + -0.4986107602930034 * z,
    -0.9692436362808796 * x + 1.8759675015077202 * y + 0.04155505740717559 * z,
    0.05563007969699366 * x + -0.20397695888897652 * y + 1.0569715142428786 * z
  ];
}
function displayp3_to_displayp3linear(rgb) {
  return srgb_to_srgblinear(rgb);
}
function displayp3linear_to_displayp3(rgb) {
  return srgblinear_to_srgb(rgb);
}
function displayp3linear_to_xyzd65(rgb) {
  const [r, g, b] = rgb;
  return [
    0.4865709486482162 * r + 0.26566769316909306 * g + 0.1982172852343625 * b,
    0.2289745640697488 * r + 0.6917385218365064 * g + 0.079286914093745 * b,
    0 * r + 0.04511338185890264 * g + 1.043944368900976 * b
  ];
}
function xyzd65_to_displayp3linear(xyz) {
  const [x, y, z] = xyz;
  return [
    2.493496911941425 * x + -0.9313836179191239 * y + -0.40271078445071684 * z,
    -0.8294889695615747 * x + 1.7626640603183463 * y + 0.023624685841943577 * z,
    0.03584583024378447 * x + -0.07617238926804182 * y + 0.9568845240076872 * z
  ];
}
function prophotorgb_to_prophotorgblinear(rgb) {
  return rgb.map((v) => Math.abs(v) <= 16 / 512 ? v / 16 : (Math.sign(v) || 1) * Math.pow(Math.abs(v), 1.8));
}
function prophotorgblinear_to_prophotorgb(rgb) {
  return rgb.map((v) => Math.abs(v) >= 1 / 512 ? (Math.sign(v) || 1) * Math.pow(Math.abs(v), 1 / 1.8) : 16 * v);
}
function prophotorgblinear_to_xyzd50(rgb) {
  const [r, g, b] = rgb;
  return [
    0.7977604896723027 * r + 0.13518583717574031 * g + 0.0313493495815248 * b,
    0.2880711282292934 * r + 0.7118432178101014 * g + 8565396060525902e-20 * b,
    0 * r + 0 * g + 0.8251046025104601 * b
  ];
}
function xyzd50_to_prophotorgblinear(xyz) {
  const [x, y, z] = xyz;
  return [
    1.3457989731028281 * x + -0.25558010007997534 * y + -0.05110628506753401 * z,
    -0.5446224939028347 * x + 1.5082327413132781 * y + 0.02053603239147973 * z,
    0 * x + 0 * y + 1.2119675456389454 * z
  ];
}
function a98rgb_to_a98rgblinear(rgb) {
  return rgb.map((v) => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 563 / 256));
}
function a98rgblinear_to_a98rgb(rgb) {
  return rgb.map((v) => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 256 / 563));
}
function a98rgblinear_to_xyzd65(rgb) {
  const [r, g, b] = rgb;
  return [
    0.5766690429101305 * r + 0.1855582379065463 * g + 0.1882286462349947 * b,
    0.29734497525053605 * r + 0.6273635662554661 * g + 0.07529145849399788 * b,
    0.02703136138641234 * r + 0.07068885253582723 * g + 0.9913375368376388 * b
  ];
}
function xyzd65_to_a98rgblinear(xyz) {
  const [x, y, z] = xyz;
  return [
    2.0415879038107465 * x + -0.5650069742788596 * y + -0.34473135077832956 * z,
    -0.9692436362808795 * x + 1.8759675015077202 * y + 0.04155505740717557 * z,
    0.013444280632031142 * x + -0.11836239223101838 * y + 1.0151749943912054 * z
  ];
}
function rec2020_to_rec2020linear(rgb) {
  const e = 1.09929682680944;
  return rgb.map((v) => Math.abs(v) < 0.018053968510807 * 4.5 ? v / 4.5 : (Math.sign(v) || 1) * Math.pow(Math.abs(v) + e - 1, 1 / 0.45));
}
function rec2020linear_to_rec2020(rgb) {
  const e = 1.09929682680944;
  return rgb.map((v) => Math.abs(v) > 0.018053968510807 ? (Math.sign(v) || 1) * (e * Math.pow(Math.abs(v), 0.45) - (e - 1)) : 4.5 * v);
}
function rec2020linear_to_xyzd65(rgb) {
  const [r, g, b] = rgb;
  return [
    0.6369580483012914 * r + 0.14461690358620832 * g + 0.1688809751641721 * b,
    0.2627002120112671 * r + 0.6779980715188708 * g + 0.05930171646986196 * b,
    0 * r + 0.028072693049087428 * g + 1.060985057710791 * b
  ];
}
function xyzd65_to_rec2020linear(xyz) {
  const [x, y, z] = xyz;
  return [
    1.7166511879712674 * x + -0.35567078377639233 * y + -0.25336628137365974 * z,
    -0.6666843518324892 * x + 1.6164812366349395 * y + 0.01576854581391113 * z,
    0.017639857445310783 * x + -0.042770613257808524 * y + 0.9421031212354738 * z
  ];
}
function xyzd50_to_lab(xyz) {
  const \u03B5 = 216 / 24389;
  const \u03BA = 24389 / 27;
  const w = [0.96422, 1, 0.82521];
  const [x, y, z] = xyz.map((v, k) => v / w[k]);
  const f = (x2) => x2 > \u03B5 ? Math.cbrt(x2) : (\u03BA * x2 + 16) / 116;
  const [f0, f1, f2] = [x, y, z].map((v) => f(v));
  return [
    (116 * f1 - 16) / 100,
    500 * (f0 - f1),
    200 * (f1 - f2)
  ];
}
function lab_to_xyzd50(lab) {
  const \u03B5 = 216 / 24389;
  const \u03BA = 24389 / 27;
  const w = [0.96422, 1, 0.82521];
  let [ciel, ciea, cieb] = lab;
  ciel = 100 * ciel;
  const f1 = (ciel + 16) / 116;
  const f0 = ciea / 500 + f1;
  const f2 = f1 - cieb / 200;
  const x = __pow(f0, 3) > \u03B5 ? __pow(f0, 3) : (116 * f0 - 16) / \u03BA;
  const y = ciel > \u03BA * \u03B5 ? __pow((ciel + 16) / 116, 3) : ciel / \u03BA;
  const z = __pow(f2, 3) > \u03B5 ? __pow(f2, 3) : (116 * f2 - 16) / \u03BA;
  return [x, y, z].map((v, k) => v * w[k]);
}
function lab_to_lch(lab) {
  const [ciel, ciea, cieb] = lab;
  const ciec = Math.sqrt(__pow(ciea, 2) + __pow(cieb, 2));
  let cieh = Math.atan2(cieb, ciea) * 180 / Math.PI;
  while (cieh < 0) cieh += 360;
  while (cieh > 360) cieh -= 360;
  return [ciel, ciec, cieh];
}
function lch_to_lab(lch) {
  const [ciel, ciec, cieh] = lch;
  const ciea = ciec * Math.cos(cieh * Math.PI / 180);
  const cieb = ciec * Math.sin(cieh * Math.PI / 180);
  return [ciel, ciea, cieb];
}
function xyzd65_to_oklab(xyz) {
  const [x, y, z] = xyz;
  const lms = [
    0.8190224432164319 * x + 0.3619062562801221 * y + -0.12887378261216414 * z,
    0.0329836671980271 * x + 0.9292868468965546 * y + 0.03614466816999844 * z,
    0.048177199566046255 * x + 0.26423952494422764 * y + 0.6335478258136937 * z
  ];
  const [l, m, s] = lms.map((v) => Math.cbrt(v));
  return [
    0.2104542553 * l + 0.793617785 * m + -0.0040720468 * s,
    1.9779984951 * l + -2.428592205 * m + 0.4505937099 * s,
    0.0259040371 * l + 0.7827717662 * m + -0.808675766 * s
  ];
}
function oklab_to_xyzd65(oklab) {
  const [okl, oka, okb] = oklab;
  const lms = [
    0.9999999984505198 * okl + 0.39633779217376786 * oka + 0.2158037580607588 * okb,
    1.0000000088817609 * okl + -0.10556134232365635 * oka + -0.06385417477170591 * okb,
    1.0000000546724108 * okl + -0.08948418209496575 * oka + -1.2914855378640917 * okb
  ];
  const [l, m, s] = lms.map((v) => __pow(v, 3));
  return [
    1.2268798733741557 * l + -0.5578149965554813 * m + 0.28139105017721583 * s,
    -0.04057576262431372 * l + 1.1122868293970594 * m + -0.07171106666151701 * s,
    -0.07637294974672142 * l + -0.4214933239627914 * m + 1.5869240244272418 * s
  ];
}
function oklab_to_oklch(lab) {
  return lab_to_lch(lab);
}
function oklch_to_oklab(lch) {
  return lch_to_lab(lch);
}
function xyzd65_to_xyzd50(xyz) {
  const [x, y, z] = xyz;
  return [
    1.0479298208405488 * x + 0.022946793341019088 * y + -0.05019222954313557 * z,
    0.029627815688159344 * x + 0.990434484573249 * y + -0.01707382502938514 * z,
    -0.009243058152591178 * x + 0.015055144896577895 * y + 0.7518742899580008 * z
  ];
}
function xyzd50_to_xyzd65(xyz) {
  const [x, y, z] = xyz;
  return [
    0.9554734527042182 * x + -0.023098536874261423 * y + 0.0632593086610217 * z,
    -0.028369706963208136 * x + 1.0099954580058226 * y + 0.021041398966943008 * z,
    0.012314001688319899 * x + -0.020507696433477912 * y + 1.3303659366080753 * z
  ];
}

// src/ts/ext/okhsl-okhsv-conversion.ts
function oklab_to_linear_srgb(L, a, b) {
  return xyzd65_to_srgblinear(oklab_to_xyzd65([L, a, b]));
}
function toe(x) {
  const k_1 = 0.206;
  const k_2 = 0.03;
  const k_3 = (1 + k_1) / (1 + k_2);
  return 0.5 * (k_3 * x - k_1 + Math.sqrt((k_3 * x - k_1) * (k_3 * x - k_1) + 4 * k_2 * k_3 * x));
}
function toe_inv(x) {
  const k_1 = 0.206;
  const k_2 = 0.03;
  const k_3 = (1 + k_1) / (1 + k_2);
  return (x * x + k_1 * x) / (k_3 * (x + k_2));
}
function compute_max_saturation(a, b) {
  let k0, k1, k2, k3, k4, wl, wm, ws;
  if (-1.88170328 * a - 0.80936493 * b > 1) {
    k0 = 1.19086277;
    k1 = 1.76576728;
    k2 = 0.59662641;
    k3 = 0.75515197;
    k4 = 0.56771245;
    wl = 4.0767416621;
    wm = -3.3077115913;
    ws = 0.2309699292;
  } else if (1.81444104 * a - 1.19445276 * b > 1) {
    k0 = 0.73956515;
    k1 = -0.45954404;
    k2 = 0.08285427;
    k3 = 0.1254107;
    k4 = 0.14503204;
    wl = -1.2684380046;
    wm = 2.6097574011;
    ws = -0.3413193965;
  } else {
    k0 = 1.35733652;
    k1 = -915799e-8;
    k2 = -1.1513021;
    k3 = -0.50559606;
    k4 = 692167e-8;
    wl = -0.0041960863;
    wm = -0.7034186147;
    ws = 1.707614701;
  }
  let S = k0 + k1 * a + k2 * b + k3 * a * a + k4 * a * b;
  let k_l = 0.3963377774 * a + 0.2158037573 * b;
  let k_m = -0.1055613458 * a - 0.0638541728 * b;
  let k_s = -0.0894841775 * a - 1.291485548 * b;
  {
    let l_ = 1 + S * k_l;
    let m_ = 1 + S * k_m;
    let s_ = 1 + S * k_s;
    let l = l_ * l_ * l_;
    let m = m_ * m_ * m_;
    let s = s_ * s_ * s_;
    let l_dS = 3 * k_l * l_ * l_;
    let m_dS = 3 * k_m * m_ * m_;
    let s_dS = 3 * k_s * s_ * s_;
    let l_dS2 = 6 * k_l * k_l * l_;
    let m_dS2 = 6 * k_m * k_m * m_;
    let s_dS2 = 6 * k_s * k_s * s_;
    let f = wl * l + wm * m + ws * s;
    let f1 = wl * l_dS + wm * m_dS + ws * s_dS;
    let f2 = wl * l_dS2 + wm * m_dS2 + ws * s_dS2;
    S = S - f * f1 / (f1 * f1 - 0.5 * f * f2);
  }
  return S;
}
function find_cusp(a, b) {
  let S_cusp = compute_max_saturation(a, b);
  let rgb_at_max = oklab_to_linear_srgb(1, S_cusp * a, S_cusp * b);
  let L_cusp = Math.cbrt(1 / Math.max(Math.max(rgb_at_max[0], rgb_at_max[1]), rgb_at_max[2]));
  let C_cusp = L_cusp * S_cusp;
  return [L_cusp, C_cusp];
}
function find_gamut_intersection(a, b, L1, C1, L0, cusp) {
  if (!cusp) {
    cusp = find_cusp(a, b);
  }
  let t;
  if ((L1 - L0) * cusp[1] - (cusp[0] - L0) * C1 <= 0) {
    t = cusp[1] * L0 / (C1 * cusp[0] + cusp[1] * (L0 - L1));
  } else {
    t = cusp[1] * (L0 - 1) / (C1 * (cusp[0] - 1) + cusp[1] * (L0 - L1));
    {
      let dL = L1 - L0;
      let dC = C1;
      let k_l = 0.3963377774 * a + 0.2158037573 * b;
      let k_m = -0.1055613458 * a - 0.0638541728 * b;
      let k_s = -0.0894841775 * a - 1.291485548 * b;
      let l_dt = dL + dC * k_l;
      let m_dt = dL + dC * k_m;
      let s_dt = dL + dC * k_s;
      {
        let L = L0 * (1 - t) + t * L1;
        let C = t * C1;
        let l_ = L + C * k_l;
        let m_ = L + C * k_m;
        let s_ = L + C * k_s;
        let l = l_ * l_ * l_;
        let m = m_ * m_ * m_;
        let s = s_ * s_ * s_;
        let ldt = 3 * l_dt * l_ * l_;
        let mdt = 3 * m_dt * m_ * m_;
        let sdt = 3 * s_dt * s_ * s_;
        let ldt2 = 6 * l_dt * l_dt * l_;
        let mdt2 = 6 * m_dt * m_dt * m_;
        let sdt2 = 6 * s_dt * s_dt * s_;
        let r = 4.0767416621 * l - 3.3077115913 * m + 0.2309699292 * s - 1;
        let r1 = 4.0767416621 * ldt - 3.3077115913 * mdt + 0.2309699292 * sdt;
        let r2 = 4.0767416621 * ldt2 - 3.3077115913 * mdt2 + 0.2309699292 * sdt2;
        let u_r = r1 / (r1 * r1 - 0.5 * r * r2);
        let t_r = -r * u_r;
        let g = -1.2684380046 * l + 2.6097574011 * m - 0.3413193965 * s - 1;
        let g1 = -1.2684380046 * ldt + 2.6097574011 * mdt - 0.3413193965 * sdt;
        let g2 = -1.2684380046 * ldt2 + 2.6097574011 * mdt2 - 0.3413193965 * sdt2;
        let u_g = g1 / (g1 * g1 - 0.5 * g * g2);
        let t_g = -g * u_g;
        let b2 = -0.0041960863 * l - 0.7034186147 * m + 1.707614701 * s - 1;
        let b1 = -0.0041960863 * ldt - 0.7034186147 * mdt + 1.707614701 * sdt;
        let b22 = -0.0041960863 * ldt2 - 0.7034186147 * mdt2 + 1.707614701 * sdt2;
        let u_b = b1 / (b1 * b1 - 0.5 * b2 * b22);
        let t_b = -b2 * u_b;
        t_r = u_r >= 0 ? t_r : 1e6;
        t_g = u_g >= 0 ? t_g : 1e6;
        t_b = u_b >= 0 ? t_b : 1e6;
        t += Math.min(t_r, Math.min(t_g, t_b));
      }
    }
  }
  return t;
}
function get_ST_max(a_, b_, cusp) {
  if (!cusp) {
    cusp = find_cusp(a_, b_);
  }
  let L = cusp[0];
  let C = cusp[1];
  return [C / L, C / (1 - L)];
}
function get_Cs(L, a_, b_) {
  const cusp = find_cusp(a_, b_);
  let C_max = find_gamut_intersection(a_, b_, L, 1, L, cusp);
  let ST_max = get_ST_max(a_, b_, cusp);
  let S_mid = 0.11516993 + 1 / (7.4477897 + 4.1590124 * b_ + a_ * (-2.19557347 + 1.75198401 * b_ + a_ * (-2.13704948 - 10.02301043 * b_ + a_ * (-4.24894561 + 5.38770819 * b_ + 4.69891013 * a_))));
  let T_mid = 0.11239642 + 1 / (1.6132032 - 0.68124379 * b_ + a_ * (0.40370612 + 0.90148123 * b_ + a_ * (-0.27087943 + 0.6122399 * b_ + a_ * (299215e-8 - 0.45399568 * b_ - 0.14661872 * a_))));
  let k = C_max / Math.min(L * ST_max[0], (1 - L) * ST_max[1]);
  let C_mid;
  {
    let C_a = L * S_mid;
    let C_b = (1 - L) * T_mid;
    C_mid = 0.9 * k * Math.sqrt(Math.sqrt(1 / (1 / (C_a * C_a * C_a * C_a) + 1 / (C_b * C_b * C_b * C_b))));
  }
  let C_0;
  {
    let C_a = L * 0.4;
    let C_b = (1 - L) * 0.8;
    C_0 = Math.sqrt(1 / (1 / (C_a * C_a) + 1 / (C_b * C_b)));
  }
  return [C_0, C_mid, C_max];
}
function okhsl_to_oklab([h, s, l]) {
  if (l == 1) {
    return [1, 0, 0];
  } else if (l == 0) {
    return [0, 0, 0];
  }
  let a_ = Math.cos(2 * Math.PI * h);
  let b_ = Math.sin(2 * Math.PI * h);
  let L = toe_inv(l);
  let Cs = get_Cs(L, a_, b_);
  let C_0 = Cs[0];
  let C_mid = Cs[1];
  let C_max = Cs[2];
  let C, t, k_0, k_1, k_2;
  if (s < 0.8) {
    t = 1.25 * s;
    k_0 = 0;
    k_1 = 0.8 * C_0;
    k_2 = 1 - k_1 / C_mid;
  } else {
    t = 5 * (s - 0.8);
    k_0 = C_mid;
    k_1 = 0.2 * C_mid * C_mid * 1.25 * 1.25 / C_0;
    k_2 = 1 - k_1 / (C_max - C_mid);
  }
  C = k_0 + t * k_1 / (1 - k_2 * t);
  return [L, C * a_, C * b_];
}
function oklab_to_okhsl(lab) {
  let L = lab[0];
  let C = Math.sqrt(lab[1] * lab[1] + lab[2] * lab[2]);
  if (C <= __pow(10, -15)) return [0, 0, toe(L)];
  let a_ = lab[1] / C;
  let b_ = lab[2] / C;
  let h = 0.5 + 0.5 * Math.atan2(-lab[2], -lab[1]) / Math.PI;
  let Cs = get_Cs(L, a_, b_);
  let C_0 = Cs[0];
  let C_mid = Cs[1];
  let C_max = Cs[2];
  let s;
  if (C < C_mid) {
    let k_0 = 0;
    let k_1 = 0.8 * C_0;
    let k_2 = 1 - k_1 / C_mid;
    let t = (C - k_0) / (k_1 + k_2 * (C - k_0));
    s = t * 0.8;
  } else {
    let k_0 = C_mid;
    let k_1 = 0.2 * C_mid * C_mid * 1.25 * 1.25 / C_0;
    let k_2 = 1 - k_1 / (C_max - C_mid);
    let t = (C - k_0) / (k_1 + k_2 * (C - k_0));
    s = 0.8 + 0.2 * t;
  }
  let l = toe(L);
  return [h, s, l];
}
function okhsv_to_oklab([h, s, v]) {
  if (v === 0) {
    return [0, 0, 0];
  }
  let a_ = Math.cos(2 * Math.PI * h);
  let b_ = Math.sin(2 * Math.PI * h);
  let ST_max = get_ST_max(a_, b_);
  let S_max = ST_max[0];
  let S_0 = 0.5;
  let T = ST_max[1];
  let k = 1 - S_0 / S_max;
  let L_v = 1 - s * S_0 / (S_0 + T - T * k * s);
  let C_v = s * T * S_0 / (S_0 + T - T * k * s);
  let L = v * L_v;
  let C = v * C_v;
  let L_vt = toe_inv(L_v);
  let C_vt = C_v * L_vt / L_v;
  let L_new = toe_inv(L);
  C = C * L_new / L;
  L = L_new;
  let rgb_scale = oklab_to_linear_srgb(L_vt, a_ * C_vt, b_ * C_vt);
  let scale_L = Math.cbrt(1 / Math.max(rgb_scale[0], rgb_scale[1], rgb_scale[2], 0));
  L = L * scale_L;
  C = C * scale_L;
  return [L, C * a_, C * b_];
}
function oklab_to_okhsv(lab) {
  let L = lab[0];
  let C = Math.sqrt(lab[1] * lab[1] + lab[2] * lab[2]);
  if (C <= __pow(10, -15)) return [0, 0, toe(L)];
  let a_ = lab[1] / C;
  let b_ = lab[2] / C;
  let h = 0.5 + 0.5 * Math.atan2(-lab[2], -lab[1]) / Math.PI;
  let ST_max = get_ST_max(a_, b_);
  let S_max = ST_max[0];
  let S_0 = 0.5;
  let T = ST_max[1];
  let k = 1 - S_0 / S_max;
  const t = T / (C + L * T);
  let L_v = t * L;
  let C_v = t * C;
  const L_vt = toe_inv(L_v);
  const C_vt = C_v * L_vt / L_v;
  const rgb_scale = oklab_to_linear_srgb(L_vt, a_ * C_vt, b_ * C_vt);
  const scale_L = Math.cbrt(1 / Math.max(rgb_scale[0], rgb_scale[1], rgb_scale[2], 0));
  L = L / scale_L;
  C = C / scale_L;
  C = C * toe(L) / L;
  L = toe(L);
  const v = L / L_v;
  const s = (S_0 + T) * C_v / (T * S_0 + T * k * C_v);
  return [h, s, v];
}

// src/ts/conversion.ts
function srgb_to_hsl(rgb) {
  const [r, g, b] = rgb;
  const max = Math.max(r, g, b);
  const min = Math.min(r, g, b);
  const chroma = max - min;
  const l = (max + min) / 2;
  let h;
  if (chroma === 0) h = 0;
  else switch (max) {
    case r:
      h = (g - b) / chroma;
      break;
    case g:
      h = (b - r) / chroma + 2;
      break;
    default:
      h = (r - g) / chroma + 4;
  }
  h = 60 * h;
  while (h < 0) h += 360;
  while (h > 360) h -= 360;
  let s;
  if (l === 0 || l === 1) s = 0;
  else if (l <= 0.5) s = chroma / (2 * l);
  else s = chroma / (2 - 2 * l);
  return [h, s, l];
}
function hsl_to_srgb(hsl) {
  const [h, s, l] = hsl;
  const m = s * Math.min(l, 1 - l);
  const k = (n) => (n + h / 30) % 12;
  const f = (n) => l - m * Math.max(Math.min(k(n) - 3, 9 - k(n), 1), -1);
  const r = f(0);
  const g = f(8);
  const b = f(4);
  return [r, g, b];
}
function hsl_to_hwb(hsl) {
  const [h, s, l] = hsl;
  let _s;
  const v = l + s * Math.min(l, 1 - l);
  if (v === 0) _s = 0;
  else _s = 2 - 2 * l / v;
  const w = (1 - _s) * v;
  const bk = 1 - v;
  return [h, w, bk];
}
function hwb_to_hsl(hwb) {
  const [h, w, bk] = hwb;
  let _w = w, _bk = bk;
  if (w + bk > 1) {
    _w = w / (w + bk);
    _bk = bk / (w + bk);
  }
  let _s;
  const v = 1 - _bk;
  if (_bk === 1) _s = 0;
  else _s = 1 - _w / v;
  let s;
  const l = v - v * _s / 2;
  if (l === 0 || l === 1) s = 0;
  else s = (v - l) / Math.min(l, 1 - l);
  return [h, s, l];
}
function oklab_to_oklrab(lab) {
  const [l, a, b] = lab;
  return [toe(l), a, b];
}
function oklrab_to_oklab(lab) {
  const [l, a, b] = lab;
  return [toe_inv(l), a, b];
}
function oklch_to_oklrch(lch) {
  return oklab_to_oklrab(lch);
}
function oklrch_to_oklch(lch) {
  return oklrab_to_oklab(lch);
}
function oklab_to_okhsl2(lab) {
  const [h, s, l] = oklab_to_okhsl(lab);
  return [360 * h, s, l];
}
function okhsl_to_oklab2(hsl) {
  const [h, s, l] = hsl;
  return okhsl_to_oklab([h / 360, s, l]);
}
function oklab_to_okhsv2(lab) {
  const [h, s, v] = oklab_to_okhsv(lab);
  return [360 * h, s, v];
}
function okhsv_to_oklab2(hsv) {
  const [h, s, v] = hsv;
  return okhsv_to_oklab([h / 360, s, v]);
}

// src/ts/ext/apca-contrast.ts
function luminance(rgb) {
  const coeffs = [0.2126729, 0.7151522, 0.072175];
  const gamma = 2.4;
  return rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
}
function APCAcontrast(rgbText, rgbBack) {
  let [Ytext, Yback] = [rgbText, rgbBack].map((rgb) => luminance(rgb));
  if (isNaN(Ytext) || isNaN(Yback) || Math.min(Ytext, Yback) < 0 || Math.max(Ytext, Yback) > 1.1) return 0;
  const normBG = 0.56, normTXT = 0.57, revTXT = 0.62, revBG = 0.65;
  const blkThrs = 0.022, blkClmp = 1.414, scaleBoW = 1.14, scaleWoB = 1.14, loBoWoffset = 0.027, loWoBoffset = 0.027, loClip = 0.1, deltaYmin = 5e-4;
  [Ytext, Yback] = [Ytext, Yback].map((Y) => Y > blkThrs ? Y : Y + Math.pow(blkThrs - Y, blkClmp));
  if (Math.abs(Ytext - Yback) < deltaYmin) return 0;
  let SAPC = 0;
  let output = 0;
  if (Yback > Ytext) {
    SAPC = (Math.pow(Yback, normBG) - Math.pow(Ytext, normTXT)) * scaleBoW;
    output = SAPC < loClip ? 0 : SAPC - loBoWoffset;
  } else {
    SAPC = (Math.pow(Yback, revBG) - Math.pow(Ytext, revTXT)) * scaleWoB;
    output = SAPC > -loClip ? 0 : SAPC + loWoBoffset;
  }
  return output * 100;
}

// src/ts/contrasts.ts
function luminance2(rgb) {
  const linrgb = srgb_to_srgblinear(rgb);
  return 0.2126729 * linrgb[0] + 0.7151522 * linrgb[1] + 0.072175 * linrgb[2];
}
function WCAG2(rgbText, rgbBack) {
  const L1 = luminance2(rgbText);
  const L2 = luminance2(rgbBack);
  return (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
}

// src/ts/color-spaces.ts
var colorSpaces = [
  {
    id: "srgb",
    aliases: ["rgb", "rgba"],
    properties: ["r", "g", "b"],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["srgb-linear", "hsl"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "srgb-linear",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["srgb", "xyz-d65"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "hsl",
    aliases: ["hsla"],
    properties: ["h", "s", "l"],
    gamutSpace: "srgb",
    links: ["srgb", "hwb"]
  },
  {
    id: "hwb",
    aliases: [],
    properties: ["h", "w", "bk"],
    gamutSpace: "srgb",
    links: ["hsl"]
  },
  {
    id: "lab",
    aliases: [],
    properties: ["ciel", "ciea", "cieb"],
    gamut: [[0, Infinity], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["xyz-d50", "lch"],
    black: [0, 0, 0]
  },
  {
    id: "lch",
    aliases: [],
    properties: ["ciel", "ciec", "cieh"],
    gamut: [[0, Infinity], [0, Infinity], [-Infinity, Infinity]],
    links: ["lab"],
    black: [0, 0, 0]
  },
  {
    id: "xyz-d50",
    aliases: [],
    gamut: [[-Infinity, Infinity], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["lab", "xyz-d65", "prophoto-rgb-linear"]
  },
  {
    id: "xyz-d65",
    aliases: ["xyz"],
    gamut: [[-Infinity, Infinity], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["xyz-d50", "srgb-linear", "display-p3-linear", "a98-rgb-linear", "rec2020-linear", "oklab"]
  },
  {
    id: "display-p3",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["display-p3-linear"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "display-p3-linear",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["display-p3", "xyz-d65"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "a98-rgb",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["a98-rgb-linear"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "a98-rgb-linear",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["a98-rgb", "xyz-d65"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "prophoto-rgb",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["prophoto-rgb-linear"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "prophoto-rgb-linear",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["prophoto-rgb", "xyz-d50"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "rec2020",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["rec2020-linear"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "rec2020-linear",
    aliases: [],
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["rec2020", "xyz-d65"],
    black: [0, 0, 0],
    white: [1, 1, 1]
  },
  {
    id: "oklab",
    aliases: [],
    properties: ["okl", "oka", "okb"],
    gamut: [[0, Infinity], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["xyz-d65", "oklch", "okhsl", "okhsv", "oklrab"],
    black: [0, 0, 0]
  },
  {
    id: "oklch",
    aliases: [],
    properties: ["okl", "okc", "okh"],
    gamut: [[0, Infinity], [0, Infinity], [-Infinity, Infinity]],
    links: ["oklab", "oklrch"],
    black: [0, 0, 0]
  },
  {
    id: "oklrab",
    aliases: [],
    properties: ["oklr", "oka", "okb"],
    gamut: [[0, Infinity], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["oklab"],
    black: [0, 0, 0]
  },
  {
    id: "oklrch",
    aliases: [],
    properties: ["oklr", "okc", "okh"],
    gamut: [[0, Infinity], [0, Infinity], [-Infinity, Infinity]],
    links: ["oklch"],
    black: [0, 0, 0]
  },
  {
    id: "okhsl",
    aliases: [],
    properties: ["okh", "oksl", "oklr"],
    gamutSpace: "srgb",
    links: ["oklab"]
  },
  {
    id: "okhsv",
    aliases: [],
    properties: ["okh", "oksv", "okv"],
    gamutSpace: "srgb",
    links: ["oklab"]
  }
];
var color_spaces_default = colorSpaces;

// src/ts/css-formats.ts
var css_formats_exports = {};
__export(css_formats_exports, {
  allFormats: () => allFormats,
  unitRegExps: () => unitRegExps
});
var numberExp = "(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?";
var unitRegExps = {
  number: numberExp,
  percentage: numberExp + "%",
  numberOrPercentage: numberExp + "%?",
  angle: numberExp + "(?:deg|grad|rad|turn)?"
};
var allFormats = [
  {
    id: "hex",
    syntaxes: [
      // #abc or #ABC
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #abcd or #ABCD
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #aabbcc or #AABBCC
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/,
      // #aabbccdd or #AABBCCDD
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
    ]
  },
  {
    id: "rgb",
    syntaxes: [
      // rgb(255, 255, 255) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.number})\\)$`),
      // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(100%, 100%, 100%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage})\\)$`),
      // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(255 255 255)
      new RegExp(`^rgba?\\((${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
      new RegExp(`^rgba?\\((${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(100% 100% 100%)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "hsl",
    syntaxes: [
      // hsl(<angle>, 100%, 100%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage})\\)$`),
      // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // hsl(<angle> 100% 100%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "hwb",
    syntaxes: [
      // hwb(<angle> 100% 100%)
      new RegExp(`^hwb\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hwb\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "lab",
    syntaxes: [
      // lab(300% 25 40)
      new RegExp(`^lab\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage})\\)$`),
      // lab(300% 25 40 / .5)
      new RegExp(`^lab\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "lch",
    syntaxes: [
      // lch(300% 25 <angle>)
      new RegExp(`^lch\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.angle})\\)$`),
      // lch(300% 25 <angle> / .5)
      new RegExp(`^lch\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.angle}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "oklab",
    syntaxes: [
      // oklab(50% -25 40)
      new RegExp(`^oklab\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage})\\)$`),
      // oklab(50% -25 40 / .5)
      new RegExp(`^oklab\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "oklch",
    syntaxes: [
      // oklch(50% 25 <angle>)
      new RegExp(`^oklch\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.angle})\\)$`),
      // oklch(50% 25 <angle> / .5)
      new RegExp(`^oklch\\((${unitRegExps.numberOrPercentage}) (${unitRegExps.numberOrPercentage}) (${unitRegExps.angle}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "color",
    syntaxes: [
      // color(display-p3 -0.6112 1.0079 -0.2192)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "name",
    syntaxes: [
      // white or WHITE or WhiTe
      /^[A-Za-z]+$/
    ]
  }
];

// src/ts/distances.ts
var distances_exports = {};
__export(distances_exports, {
  CIEDE2000: () => CIEDE2000,
  euclidean: () => euclidean
});
function euclidean(vals1, vals2) {
  return vals1.reduce((sum, v, k) => sum + __pow(v - vals2[k], 2), 0);
}
function CIEDE2000([l1, a1, b1], [l2, a2, b2]) {
  const L1 = 100 * l1, L2 = 100 * l2;
  const C1 = Math.sqrt(__pow(a1, 2) + __pow(b1, 2));
  const C2 = Math.sqrt(__pow(a2, 2) + __pow(b2, 2));
  const mC = (C1 + C2) / 2, G = 0.5 * (1 - Math.sqrt(__pow(mC, 7) / (__pow(mC, 7) + __pow(25, 7)))), aa1 = (1 + G) * a1, aa2 = (1 + G) * a2, CC1 = Math.sqrt(__pow(aa1, 2) + __pow(b1, 2)), CC2 = Math.sqrt(__pow(aa2, 2) + __pow(b2, 2));
  let hh1 = CC1 === 0 ? 0 : Math.atan2(b1, aa1) * 180 / Math.PI, hh2 = CC2 === 0 ? 0 : Math.atan2(b2, aa2) * 180 / Math.PI;
  while (hh1 < 0) hh1 += 360;
  while (hh1 > 360) hh1 -= 360;
  while (hh2 < 0) hh2 += 360;
  while (hh2 > 360) hh2 -= 360;
  const dL = L2 - L1, dC = CC2 - CC1;
  const dhh = CC1 * CC2 === 0 ? 0 : Math.abs(hh2 - hh1) <= 180 ? hh2 - hh1 : hh2 - hh1 > 180 ? hh2 - hh1 - 360 : hh2 - hh1 + 360;
  const dH = 2 * Math.sqrt(CC1 * CC2) * Math.sin(Math.PI / 180 * (dhh / 2));
  const mL = (L1 + L2) / 2, mCC = (CC1 + CC2) / 2;
  const mhh = CC1 * CC2 === 0 ? hh1 + hh2 : Math.abs(hh2 - hh1) <= 180 ? (hh1 + hh2) / 2 : hh1 + hh2 >= 360 ? (hh1 + hh2 - 360) / 2 : (hh1 + hh2 + 360) / 2;
  const T = 1 - 0.17 * Math.cos(Math.PI / 180 * (mhh - 30)) + 0.24 * Math.cos(Math.PI / 180 * (2 * mhh)) + 0.32 * Math.cos(Math.PI / 180 * (3 * mhh + 6)) - 0.2 * Math.cos(Math.PI / 180 * (4 * mhh - 63)), dTH = 30 * Math.exp(-1 * __pow((mhh - 275) / 25, 2)), RC = 2 * Math.sqrt(__pow(mCC, 7) / (__pow(mCC, 7) + __pow(25, 7))), SL = 1 + 0.015 * __pow(mL - 50, 2) / Math.sqrt(20 + __pow(mL - 50, 2)), SC = 1 + 0.045 * mCC, SH = 1 + 0.015 * mCC * T, RT = -1 * Math.sin(Math.PI / 180 * (2 * dTH)) * RC;
  return Math.sqrt(
    __pow(dL / SL, 2) + __pow(dC / SC, 2) + __pow(dH / SH, 2) + RT * (dC / SC) * (dH / SH)
  );
}

// src/ts/graph.ts
var GraphNode = class {
  /**
   * Builds a graph node from an object.
   * @param object An { id, links } object.
   */
  constructor(object) {
    __publicField(this, "id");
    __publicField(this, "links");
    __publicField(this, "data");
    __publicField(this, "visited", false);
    __publicField(this, "predecessor", null);
    var _a2;
    this.id = object.id;
    this.links = object.links;
    this.data = (_a2 = object.data) != null ? _a2 : null;
  }
  getVisitedState() {
    return this.visited;
  }
  getPredecessor() {
    return this.predecessor;
  }
  visit(mark = true) {
    this.visited = mark;
  }
  unvisit() {
    this.visited = false;
  }
  follow(node) {
    this.predecessor = node;
  }
  unfollow() {
    this.predecessor = null;
  }
};
var UndefinedNodeError = class extends Error {
  constructor(id) {
    super(`Node ${JSON.stringify(id)} does not exist`);
    __publicField(this, "id");
    this.id = id;
  }
};
var PathNotFoundError = class extends Error {
  constructor(startID, endID) {
    super(`No path found from ${JSON.stringify(startID)} to ${JSON.stringify(endID)}`);
    __publicField(this, "startID");
    __publicField(this, "endID");
    this.startID = startID;
    this.endID = endID;
  }
};
var CyclicGraphError = class extends Error {
  constructor() {
    super(`The graph is not a directed acyclic graph`);
  }
};
var Graph = class {
  /**
   * Builds a graph from an array.
   * @param array Array of { id, links, data? } objects.
   */
  constructor(array) {
    __publicField(this, "nodes");
    this.nodes = array.map((e) => new GraphNode(e));
  }
  /**
   * Finds a node.
   * @param id Identifier of the desired node.
   * @returns The corresponding node.
   */
  getNode(id) {
    const node = this.nodes.find((node2) => node2.id === id);
    if (node == null) throw new UndefinedNodeError(id);
    return node;
  }
  /** Resets the nodes to their starting state. */
  cleanUp() {
    for (const node of this.nodes) {
      node.unvisit();
      node.unfollow();
    }
  }
  /**
   * Finds the shortest path between two nodes.
   * @param startID Identifier of the first node.
   * @param endID Identifier of the last node.
   * @returns An array of graph nodes, ordered from first to last along the shortest path.
   */
  shortestPath(startID, endID) {
    if (startID === endID) return [];
    try {
      const start = this.getNode(startID);
      const end = this.getNode(endID);
      const queue = [start];
      start.visit();
      let found = false;
      walk: while (queue.length > 0) {
        const current2 = queue.shift();
        if (current2.id === end.id) {
          found = true;
          break walk;
        }
        for (const neighbourID of current2.links) {
          const neighbour = this.getNode(neighbourID);
          if (neighbour.getVisitedState() === false) {
            neighbour.visit();
            neighbour.follow(current2);
            queue.push(neighbour);
          }
        }
      }
      if (!found) throw new PathNotFoundError(start.id, end.id);
      const path = [end];
      let current = end;
      let predecessor = current.getPredecessor();
      while (predecessor != null) {
        path.push(predecessor);
        current = predecessor;
        predecessor = current.getPredecessor();
      }
      this.cleanUp();
      return path.reverse();
    } catch (error) {
      this.cleanUp();
      throw error;
    }
  }
  /**
   * Lists the graph nodes in a topological order.
   * @returns The array of ordered graph nodes.
   */
  topologicalOrder() {
    const orderedList = [];
    const unvisitedNodes = [...this.nodes];
    const visit = (node) => {
      if (node.getVisitedState() === true) return;
      if (node.getVisitedState() === "temp") throw new CyclicGraphError();
      node.visit("temp");
      for (const link of node.links) {
        const destination = this.getNode(link);
        visit(destination);
      }
      node.visit(true);
      orderedList.push(node);
    };
    try {
      while (unvisitedNodes.length > 0) {
        const current = unvisitedNodes.shift();
        visit(current);
      }
      this.cleanUp();
      return orderedList.reverse();
    } catch (error) {
      this.cleanUp();
      throw error;
    }
  }
};

// src/ts/named-colors.ts
var namedColors = /* @__PURE__ */ new Map([
  ["aliceblue", "f0f8ff"],
  ["antiquewhite", "faebd7"],
  ["aqua", "00ffff"],
  ["aquamarine", "7fffd4"],
  ["azure", "f0ffff"],
  ["beige", "f5f5dc"],
  ["bisque", "ffe4c4"],
  ["black", "000000"],
  ["blanchedalmond", "ffebcd"],
  ["blue", "0000ff"],
  ["blueviolet", "8a2be2"],
  ["brown", "a52a2a"],
  ["burlywood", "deb887"],
  ["cadetblue", "5f9ea0"],
  ["chartreuse", "7fff00"],
  ["chocolate", "d2691e"],
  ["coral", "ff7f50"],
  ["cornflowerblue", "6495ed"],
  ["cornsilk", "fff8dc"],
  ["crimson", "dc143c"],
  ["cyan", "00ffff"],
  ["darkblue", "00008b"],
  ["darkcyan", "008b8b"],
  ["darkgoldenrod", "b8860b"],
  ["darkgray", "a9a9a9"],
  ["darkgrey", "a9a9a9"],
  ["darkgreen", "006400"],
  ["darkkhaki", "bdb76b"],
  ["darkmagenta", "8b008b"],
  ["darkolivegreen", "556b2f"],
  ["darkorange", "ff8c00"],
  ["darkorchid", "9932cc"],
  ["darkred", "8b0000"],
  ["darksalmon", "e9967a"],
  ["darkseagreen", "8fbc8f"],
  ["darkslateblue", "483d8b"],
  ["darkslategray", "2f4f4f"],
  ["darkslategrey", "2f4f4f"],
  ["darkturquoise", "00ced1"],
  ["darkviolet", "9400d3"],
  ["deeppink", "ff1493"],
  ["deepskyblue", "00bfff"],
  ["dimgray", "696969"],
  ["dimgrey", "696969"],
  ["dodgerblue", "1e90ff"],
  ["firebrick", "b22222"],
  ["floralwhite", "fffaf0"],
  ["forestgreen", "228b22"],
  ["fuchsia", "ff00ff"],
  ["gainsboro", "dcdcdc"],
  ["ghostwhite", "f8f8ff"],
  ["gold", "ffd700"],
  ["goldenrod", "daa520"],
  ["gray", "808080"],
  ["grey", "808080"],
  ["green", "008000"],
  ["greenyellow", "adff2f"],
  ["honeydew", "f0fff0"],
  ["hotpink", "ff69b4"],
  ["indianred", "cd5c5c"],
  ["indigo", "4b0082"],
  ["ivory", "fffff0"],
  ["khaki", "f0e68c"],
  ["lavender", "e6e6fa"],
  ["lavenderblush", "fff0f5"],
  ["lawngreen", "7cfc00"],
  ["lemonchiffon", "fffacd"],
  ["lightblue", "add8e6"],
  ["lightcoral", "f08080"],
  ["lightcyan", "e0ffff"],
  ["lightgoldenrodyellow", "fafad2"],
  ["lightgray", "d3d3d3"],
  ["lightgrey", "d3d3d3"],
  ["lightgreen", "90ee90"],
  ["lightpink", "ffb6c1"],
  ["lightsalmon", "ffa07a"],
  ["lightseagreen", "20b2aa"],
  ["lightskyblue", "87cefa"],
  ["lightslategray", "778899"],
  ["lightslategrey", "778899"],
  ["lightsteelblue", "b0c4de"],
  ["lightyellow", "ffffe0"],
  ["lime", "00ff00"],
  ["limegreen", "32cd32"],
  ["linen", "faf0e6"],
  ["magenta", "ff00ff"],
  ["maroon", "800000"],
  ["mediumaquamarine", "66cdaa"],
  ["mediumblue", "0000cd"],
  ["mediumorchid", "ba55d3"],
  ["mediumpurple", "9370d8"],
  ["mediumseagreen", "3cb371"],
  ["mediumslateblue", "7b68ee"],
  ["mediumspringgreen", "00fa9a"],
  ["mediumturquoise", "48d1cc"],
  ["mediumvioletred", "c71585"],
  ["midnightblue", "191970"],
  ["mintcream", "f5fffa"],
  ["mistyrose", "ffe4e1"],
  ["moccasin", "ffe4b5"],
  ["navajowhite", "ffdead"],
  ["navy", "000080"],
  ["oldlace", "fdf5e6"],
  ["olive", "808000"],
  ["olivedrab", "6b8e23"],
  ["orange", "ffa500"],
  ["orangered", "ff4500"],
  ["orchid", "da70d6"],
  ["palegoldenrod", "eee8aa"],
  ["palegreen", "98fb98"],
  ["paleturquoise", "afeeee"],
  ["palevioletred", "d87093"],
  ["papayawhip", "ffefd5"],
  ["peachpuff", "ffdab9"],
  ["peru", "cd853f"],
  ["pink", "ffc0cb"],
  ["plum", "dda0dd"],
  ["powderblue", "b0e0e6"],
  ["purple", "800080"],
  ["rebeccapurple", "663399"],
  ["red", "ff0000"],
  ["rosybrown", "bc8f8f"],
  ["royalblue", "4169e1"],
  ["saddlebrown", "8b4513"],
  ["salmon", "fa8072"],
  ["sandybrown", "f4a460"],
  ["seagreen", "2e8b57"],
  ["seashell", "fff5ee"],
  ["sienna", "a0522d"],
  ["silver", "c0c0c0"],
  ["skyblue", "87ceeb"],
  ["slateblue", "6a5acd"],
  ["slategray", "708090"],
  ["slategrey", "708090"],
  ["snow", "fffafa"],
  ["springgreen", "00ff7f"],
  ["steelblue", "4682b4"],
  ["tan", "d2b48c"],
  ["teal", "008080"],
  ["thistle", "d8bfd8"],
  ["tomato", "ff6347"],
  ["turquoise", "40e0d0"],
  ["violet", "ee82ee"],
  ["wheat", "f5deb3"],
  ["white", "ffffff"],
  ["whitesmoke", "f5f5f5"],
  ["yellow", "ffff00"],
  ["yellowgreen", "9acd32"]
]);
var named_colors_default = namedColors;

// src/ts/utils.ts
function pad(s) {
  return s.length < 2 ? `0${s}` : s;
}
function angleToRange(angle) {
  let h = angle;
  while (h < 0) h += 360;
  while (h >= 360) h -= 360;
  return h;
}
function toUnparsedAlpha(val, def = "1") {
  return !!val ? String(val) : val === 0 ? "0" : def;
}
function toHex(rgba) {
  return rgba.map((v) => pad(Math.round(v * 255).toString(16)));
}
function fromHex(hexa) {
  return hexa.map((v) => v.length === 1 ? v.repeat(2) : v).map((v) => parseInt(v, 16)).map((v) => v / 255);
}

// src/ts/couleur.ts
var InvalidColorStringError = class extends Error {
  constructor(color) {
    super(`${JSON.stringify(color)} is not a valid color format`);
  }
};
var InvalidColorPropValueError = class extends Error {
  constructor(prop, value) {
    super(`Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`);
  }
};
var InvalidColorAngleValueError = class extends Error {
  constructor(value) {
    super(`Invalid angle value: ${JSON.stringify(value)}`);
  }
};
var InvalidColorArbitraryValueError = class extends Error {
  constructor(value) {
    super(`Invalid arbitrary value: ${JSON.stringify(value)}`);
  }
};
var ColorFormatHasNoSuchPropertyError = class extends Error {
  constructor(format, prop) {
    super(`Format ${format} does not have a property called ${prop}`);
  }
};
var ImpossibleColorConversionError = class extends Error {
  constructor(startSpace, endSpace) {
    super(`Conversion from ${JSON.stringify(startSpace.id)} space to ${JSON.stringify(endSpace.id)} space is impossible`);
  }
};
var UnsupportedColorSpaceError = class extends Error {
  constructor(id) {
    super(`${JSON.stringify(id)} is not a supported color space`);
  }
};
var UndefinedConversionError = class extends Error {
  constructor(functionName) {
    super(`Conversion function ${functionName} does not exist`);
  }
};
var UnsupportedMethodError = class extends Error {
  constructor(methodName, action) {
    super(`${methodName} is not a supported method for ${action}`);
  }
};
var _cache;
var GraphWithCachedPaths = class extends Graph {
  constructor() {
    super(...arguments);
    __privateAdd(this, _cache, /* @__PURE__ */ new Map());
  }
  shortestPath(startID, endID) {
    const id = `${startID}_to_${endID}`;
    let cachedPath = __privateGet(this, _cache).get(id);
    if (!cachedPath) {
      const reversedPath = __privateGet(this, _cache).get(`${endID}_to_${startID}`);
      cachedPath = reversedPath ? [...reversedPath].reverse() : null;
    }
    if (cachedPath) return cachedPath;
    const path = super.shortestPath(startID, endID);
    __privateGet(this, _cache).set(id, path);
    return path;
  }
};
_cache = new WeakMap();
var colorSpacesGraph = new GraphWithCachedPaths(color_spaces_default);
var _r, _g, _b, _a, _cache2;
var _Couleur = class _Couleur {
  /**
   * Creates a new Couleur object that contains r, g, b, a properties of the color.
   * These properties will take their values from sRGB color space, even if they're out of bounds.
   * (This means values <0 or >1 can be stored — they can be clamped to a specific color space when needed.)
   * @param color Color expression in a supported type.
   * @throws When the parameter is not of a supported type.
   */
  constructor(color) {
    __privateAdd(this, _r, 0);
    __privateAdd(this, _g, 0);
    __privateAdd(this, _b, 0);
    __privateAdd(this, _a, 0);
    __privateAdd(this, _cache2, /* @__PURE__ */ new Map());
    if (color instanceof _Couleur || typeof color === "object" && "r" in color && "g" in color && "b" in color) {
      __privateSet(this, _r, color.r);
      __privateSet(this, _g, color.g);
      __privateSet(this, _b, color.b);
      __privateSet(this, _a, typeof color.a === "number" ? color.a : 1);
    } else if (Array.isArray(color) && (color.length == 3 || color.length == 4)) {
      [__privateWrapper(this, _r)._, __privateWrapper(this, _g)._, __privateWrapper(this, _b)._] = color.slice(0, 3);
      __privateSet(this, _a, Math.max(0, Math.min(Number(toUnparsedAlpha(color[3])), 1)));
    } else if (typeof color === "string") {
      const format = _Couleur.matchSyntax(color.trim());
      switch (format.id) {
        case "hex":
          this.setHex([format.data[1], format.data[2], format.data[3], toUnparsedAlpha(format.data[4], "ff")]);
          break;
        case "rgb":
        case "hsl":
        case "hwb":
        case "lab":
        case "lch":
        case "oklab":
        case "oklch":
          {
            const values = [format.data[1], format.data[2], format.data[3], toUnparsedAlpha(format.data[4])];
            const props = [..._Couleur.propertiesOf(format.id), "a"];
            const space = _Couleur.getSpace(format.id);
            this.set(values, props, space);
          }
          break;
        case "color":
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], toUnparsedAlpha(format.data[5])]);
          break;
        default:
          throw new InvalidColorStringError(color);
      }
    } else throw new Error(`Couleur objects can only be created from a string, an array of parsed values, or another Couleur object ; this is not one: ${JSON.stringify(color)}`);
  }
  /**
   * Makes a Couleur from the argument if it's not one already.
   * @param color
   * @returns
   */
  static makeInstance(color) {
    if (color instanceof _Couleur) return color;
    else return new _Couleur(color);
  }
  /**
   * Matches the user input with supported color formats.
   * @param colorString Color expression in a supported format.
   * @returns Recognized syntax.
   * @throws When colorString is not in a valid format.
   */
  static matchSyntax(colorString) {
    const tri = colorString.slice(0, 3);
    let format;
    if (tri.slice(0, 1) === "#") format = allFormats[0];
    else switch (tri) {
      case "rgb":
        format = allFormats[1];
        break;
      case "hsl":
        format = allFormats[2];
        break;
      case "hwb":
        format = allFormats[3];
        break;
      case "lab":
        format = allFormats[4];
        break;
      case "lch":
        format = allFormats[5];
        break;
      case "okl":
        {
          if (colorString.startsWith("oklab")) {
            format = allFormats[6];
          } else if (colorString.startsWith("oklch")) {
            format = allFormats[7];
          }
        }
        break;
      case "col":
        format = allFormats[8];
        break;
      default:
        format = allFormats[9];
    }
    if (format == null) throw new Error("No matching format");
    for (const syntaxe of format.syntaxes) {
      const result = colorString.match(syntaxe);
      if (result != null && result[0] === colorString) {
        if (format.id === "name") {
          if (colorString === "transparent") return { id: "rgb", data: ["", "0", "0", "0", "0"] };
          const allNames = _Couleur.namedColors;
          const hex = allNames.get(colorString.toLowerCase()) || null;
          if (hex) return _Couleur.matchSyntax(`#${hex}`);
        } else {
          return { id: format.id, data: result };
        }
      }
    }
    throw new InvalidColorStringError(colorString);
  }
  /**
   * Parses a number / percentage / angle into the correct format to store it.
   * @param value The value to parse.
   * @param prop The color property that has n as its value.
   * @param options
   * @param options.clamp Whether the value should de clamped to its color space bounds.
   * @returns The properly parsed number.
   * @throws When the value isn't in a supported format for the corresponding property.
   */
  static parse(value, prop = null, { clamp = true } = {}) {
    const val = String(value);
    const nval = parseFloat(val);
    switch (prop) {
      // Alpha values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 1]
      // to [0, 1]
      case "a": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval / 100, 1));
          else return nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval, 1));
          else return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // Red, green, blue values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 255]
      // to [0, 1]
      case "r":
      case "g":
      case "b": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval / 100, 1));
          else return nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval / 255, 1));
          else return nval / 255;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // Hue and CIE hue values:
      // from any angle or any number
      // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
      // to [0, 360]
      case "h":
      case "cieh":
      case "okh": {
        let h = nval;
        if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          return angleToRange(h);
        } else if (new RegExp("^" + unitRegExps.angle + "$").test(val)) {
          if (val.slice(-3) === "deg") {
          } else if (val.slice(-4) === "grad")
            h = h * 360 / 400;
          else if (val.slice(-3) === "rad")
            h = h * 180 / Math.PI;
          else if (val.slice(-4) === "turn")
            h = h * 360;
          else throw new InvalidColorAngleValueError(value);
          return angleToRange(h);
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // CIE and OK luminosity values:
      // from any number or %
      // to [0, 1]
      case "ciel":
      case "okl": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval / 100, 1));
          else return nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval, 1));
          else return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // CIE A and B axis values:
      // from any number or %
      // to any number (so that -100% becomes -125 and 100% becomes 125)
      case "ciea":
      case "cieb": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          return 125 * nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // CIE chroma values:
      // from any number or %
      // to any number (so that 0% becomes 0 and 100% becomes 150)
      case "ciec": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, 150 * nval / 100);
          else return 150 * nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          if (clamp) return Math.max(0, nval);
          else return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // OK A and B axis values:
      // from any number or %
      // to any number (so that -100% becomes -0.4 and 100% becomes 0.4)
      case "oka":
      case "okb": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          return 0.4 * nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // OK chroma values:
      // from any number or %
      // to any number (so that 0% becomes 0 and 100% becomes 0.4)
      case "okc": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, 0.4 * nval / 100);
          else return 0.4 * nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          if (clamp) return Math.max(0, nval);
          else return nval;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // Percentage values:
      // from any %
      // clamped to [0, 100]%
      // to [0, 1]
      case "s":
      case "l":
      case "w":
      case "bk": {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          if (clamp) return Math.max(0, Math.min(nval / 100, 1));
          else return nval / 100;
        } else throw new InvalidColorPropValueError(prop, value);
      }
      // Arbitrary values
      // from any % or any number
      // to any number (so that 0% becomes 0 and 100% becomes 1)
      default: {
        if (new RegExp("^" + unitRegExps.percentage + "$").test(val)) {
          return nval / 100;
        } else if (new RegExp("^" + unitRegExps.number + "$").test(val)) {
          return nval;
        } else throw new InvalidColorArbitraryValueError(value);
      }
    }
  }
  /**
   * Unparses a value to the format that would be used in a CSS expression.
   * @param value Value to unparse.
   * @param prop Name of the property that has the value.
   * @param options
   * @param options.precision How many decimals to display.
   * @returns The unparsed value, ready to insert in a CSS expression.
   */
  static unparse(value, prop, { precision = 0 } = {}) {
    switch (prop) {
      case "r":
      case "g":
      case "b":
        return precision === null ? `${255 * value}` : `${Math.round(__pow(10, precision) * 255 * value) / __pow(10, precision)}`;
      case "s":
      case "l":
      case "w":
      case "bk":
      case "ciel":
      case "okl":
        return precision === null ? `${100 * value}%` : `${Math.round(__pow(10, precision) * 100 * value) / __pow(10, precision)}%`;
      case "ciea":
      case "cieb":
        return precision === null ? `${100 * value / 125}%` : `${Math.round(__pow(10, precision) * 100 * value / 125) / __pow(10, precision)}%`;
      case "ciec":
        return precision === null ? `${100 * value / 150}%` : `${Math.round(__pow(10, precision) * 100 * value / 150) / __pow(10, precision)}%`;
      case "oka":
      case "okb":
      case "okc":
        return precision === null ? `${100 * value / 0.4}%` : `${Math.round(__pow(10, precision) * 100 * value / 0.4) / __pow(10, precision)}%`;
      case "a":
        return precision === null ? `${value}` : `${Math.round(__pow(10, Math.max(precision, 2)) * value) / __pow(10, Math.max(precision, 2))}`;
      default:
        return precision === null ? `${value}` : `${Math.round(__pow(10, precision) * value) / __pow(10, precision)}`;
    }
  }
  /**
   * Calculates all properties of a color from given unparsed values in a given color space.
   * @param data Array of unparsed values.
   * @param props Array of color property names the values correspond to.
   * @param sourceSpaceID Color space of the values, or its identifier.
   * @param options
   * @param options.parsed Whether the provided values are already parsed.
   */
  set(data, props, sourceSpaceID, { parsed = false } = {}) {
    const sourceSpace = _Couleur.getSpace(sourceSpaceID);
    const values = parsed ? data.map((v) => Number(v)) : props.map((p, i) => _Couleur.parse(data[i], p));
    [__privateWrapper(this, _r)._, __privateWrapper(this, _g)._, __privateWrapper(this, _b)._] = _Couleur.convert(sourceSpace, "srgb", values);
    __privateSet(this, _a, _Couleur.parse(toUnparsedAlpha(data[3]), "a"));
  }
  /**
   * Calculates all properties of the color from its hexadecimal expression.
   * @param hexa The hexadecimal values of the r, g, b, a properties.
   */
  setHex(hexa) {
    let [r, g, b] = hexa.map((v) => String(v));
    let a = String(hexa[3]) || "ff";
    const vals = fromHex([r, g, b, a]).map((v, k) => k === 3 ? v : v * 255);
    this.set(vals, ["r", "g", "b"], "srgb");
  }
  /**
   * Calculates all properties of the color from its functional color() expression.
   * @param sourceSpaceID Identifier of the color space.
   * @param values The parsed values of the color's properties.
   * @throws When the color space is not supported.
   */
  setColor(sourceSpaceID, values) {
    let vals = values.slice(0, 3).map((v) => _Couleur.parse(v));
    const a = _Couleur.parse(values[3]);
    vals = _Couleur.convert(sourceSpaceID, "srgb", vals);
    const rgba = [...vals, a];
    return this.set(rgba, [null, null, null], "srgb");
  }
  /*****************************/
  /* Getters for color formats */
  /*****************************/
  /* GENERAL EXPRESSION MAKER */
  /**
   * Creates a string containing the CSS expression of a color.
   * @param format Identifier of the color space of the requested CSS expression.
   * @param options @see Couleur.makeString
   */
  toString(format = "rgb", { precision = 2, clamp = false } = {}) {
    format = format.toLowerCase();
    const destinationSpaceID = format.replace("color-", "");
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    const props = _Couleur.propertiesOf(destinationSpace.id);
    let values = this.valuesTo(destinationSpace, { clamp }).map((v, k) => this.isPowerless(props[k]) ? 0 : v);
    return _Couleur.makeString(format, [...values, this.a], { precision });
  }
  /**
   * Creates a string containing the CSS expression of a color from a list of values.
   * @param format Identifier of the color space of the requested CSS expression.
   * @param values The values of the properties in the given format.
   * @param options
   * @param options.precision How many decimals to display.
   * @param options.clamp Which color space the values should be clamped to.
   * @returns The expression of the color in the requested format.
   */
  static makeString(format, values, { precision = 2 } = {}) {
    var _a2;
    format = format.toLowerCase();
    const destinationSpaceID = format.replace("color-", "");
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    const a = Number(_Couleur.unparse((_a2 = values[3]) != null ? _a2 : 1, "a", { precision }));
    values = [...values.slice(0, 3), a];
    if (format.toLowerCase().slice(0, 5) === "color") {
      values = values.map((v) => precision === null ? v : Math.round(__pow(10, precision) * v) / __pow(10, precision));
      if (a < 1)
        return `color(${destinationSpace.id} ${values.slice(0, -1).join(" ")} / ${a})`;
      else
        return `color(${destinationSpace.id} ${values.slice(0, -1).join(" ")})`;
    } else {
      const props = _Couleur.propertiesOf(format);
      if (props.length === 0) return _Couleur.makeString(`color-${format}`, values, { precision });
      const unparsedValues = props.map((p, k) => _Couleur.unparse(values[k], p, { precision }));
      switch (format.toLowerCase()) {
        case "rgb":
        case "rgba":
        case "hsl":
        case "hsla": {
          if (format.length > 3 && format.slice(-1) === "a" || a < 1)
            return `${format}(${unparsedValues.join(", ")}, ${a})`;
          else
            return `${format}(${unparsedValues.join(", ")})`;
        }
        default: {
          if (a < 1) return `${format}(${unparsedValues.join(" ")} / ${a})`;
          else return `${format}(${unparsedValues.join(" ")})`;
        }
      }
    }
  }
  /* ALL VALUES (r, g, b) */
  /** @returns The array of r, g, b values of the color in sRGB color space. */
  get values() {
    return [this.r, this.g, this.b];
  }
  /* NAME */
  /** @returns The approximate name of the color. */
  get name() {
    if (this.a === 1) {
      const allNames = _Couleur.namedColors;
      const rgb1 = this.values;
      const tolerance = 4e-4;
      for (const [name, hex] of allNames.entries()) {
        const rgb2 = fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
        if (euclidean(rgb1, rgb2) < tolerance) return name;
      }
      return null;
    } else if (this.a === 0) return "transparent";
    else return null;
  }
  /** @returns The exact name of the color. */
  get exactName() {
    if (this.a === 1) {
      const allNames = _Couleur.namedColors;
      const hex6 = this.hex.slice(1);
      for (const [name, hex] of allNames.entries()) {
        if (hex === hex6) return name;
      }
      return null;
    } else if (this.a === 0) return "transparent";
    else return null;
  }
  /** @returns The name of the closest named color. */
  get closestName() {
    if (this.a < 0.5) return "transparent";
    const allNames = _Couleur.namedColors;
    const rgb1 = this.values;
    let closest = "";
    let lastDistance = Infinity;
    for (const [name, hex] of allNames.entries()) {
      const rgb2 = fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
      const distance = euclidean(rgb1, rgb2);
      if (distance < lastDistance) {
        lastDistance = distance;
        closest = name;
      }
    }
    return closest;
  }
  /* CSS FORMATS */
  /** @returns Hexadecimal expression of the color. */
  get hex() {
    const values = _Couleur.valuesToGamut("srgb", this.values);
    const rgb = toHex([...values, this.a]);
    if (this.a < 1) return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
    else return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }
  /** @returns RGB expression of the color. */
  get rgb() {
    return this.toString("rgb", { precision: 2, clamp: true });
  }
  get rgba() {
    return this.rgb;
  }
  /** @returns HSL expression of the color. */
  get hsl() {
    return this.toString("hsl", { precision: 2, clamp: true });
  }
  get hsla() {
    return this.hsl;
  }
  /** @returns HWB expression of the color. */
  get hwb() {
    return this.toString("hwb", { precision: 2, clamp: true });
  }
  /** @returns LAB expression of the color. */
  get lab() {
    return this.toString("lab", { precision: 2, clamp: true });
  }
  /** @returns LCH expression of the color. */
  get lch() {
    return this.toString("lch", { precision: 2, clamp: true });
  }
  /** @returns OKLAB expression of the color. */
  get oklab() {
    return this.toString("oklab", { precision: 2, clamp: true });
  }
  /** @returns OKLCH expression of the color. */
  get oklch() {
    return this.toString("oklch", { precision: 2, clamp: true });
  }
  /********************************************/
  /* Setters and getters for color properties */
  /********************************************/
  /**
   * Recalculates the r, g, b properties of the color after modifying one of its other properties.
   * @param val The parsed new value of the property.
   * @param prop The property to change.
   * @param format The id of the CSS format the property belongs to.
   * @throws When the CSS format doesn't have the requested property.
   */
  recompute(val, prop, format) {
    const props = [..._Couleur.propertiesOf(format), "a"];
    if (!props.includes(prop))
      throw new ColorFormatHasNoSuchPropertyError(format, prop);
    const parsedVal = typeof val === "string" ? _Couleur.parse(val, prop) : val;
    const oldValues = [...this.valuesTo(format), this.a];
    const newValues = props.map((p, k) => {
      if (p === prop) return parsedVal;
      else return oldValues[k];
    });
    this.set(newValues, props, format, { parsed: true });
    __privateSet(this, _cache2, /* @__PURE__ */ new Map());
  }
  set r(val) {
    this.recompute(val, "r", "rgb");
  }
  set red(val) {
    this.r = val;
  }
  set g(val) {
    this.recompute(val, "g", "rgb");
  }
  set green(val) {
    this.g = val;
  }
  set b(val) {
    this.recompute(val, "b", "rgb");
  }
  set blue(val) {
    this.b = val;
  }
  set a(val) {
    this.recompute(val, "a", "rgb");
  }
  set alpha(val) {
    this.a = val;
  }
  set opacity(val) {
    this.a = val;
  }
  set h(val) {
    this.recompute(val, "h", "hsl");
  }
  set hue(val) {
    this.h = val;
  }
  set s(val) {
    this.recompute(val, "s", "hsl");
  }
  set saturation(val) {
    this.s = val;
  }
  set l(val) {
    this.recompute(val, "l", "hsl");
  }
  set lightness(val) {
    this.l = val;
  }
  set w(val) {
    this.recompute(val, "w", "hwb");
  }
  set whiteness(val) {
    this.w = val;
  }
  set bk(val) {
    this.recompute(val, "bk", "hwb");
  }
  set blackness(val) {
    this.bk = val;
  }
  set ciel(val) {
    this.recompute(val, "ciel", "lab");
  }
  set CIElightness(val) {
    this.ciel = val;
  }
  set ciea(val) {
    this.recompute(val, "ciea", "lab");
  }
  set cieb(val) {
    this.recompute(val, "cieb", "lab");
  }
  set ciec(val) {
    this.recompute(val, "ciec", "lch");
  }
  set CIEchroma(val) {
    this.ciec = val;
  }
  set cieh(val) {
    this.recompute(val, "cieh", "lch");
  }
  set CIEhue(val) {
    this.cieh = val;
  }
  set okl(val) {
    this.recompute(val, "okl", "oklab");
  }
  set OKlightness(val) {
    this.okl = val;
  }
  set oka(val) {
    this.recompute(val, "oka", "oklab");
  }
  set okb(val) {
    this.recompute(val, "okb", "oklab");
  }
  set okc(val) {
    this.recompute(val, "okc", "oklch");
  }
  set OKchroma(val) {
    this.okc = val;
  }
  set okh(val) {
    this.recompute(val, "okh", "oklch");
  }
  set OKhue(val) {
    this.okh = val;
  }
  /** @returns Gets the parsed value of one of the color properties. */
  get r() {
    return __privateGet(this, _r);
  }
  get red() {
    return this.r;
  }
  get g() {
    return __privateGet(this, _g);
  }
  get green() {
    return this.g;
  }
  get b() {
    return __privateGet(this, _b);
  }
  get blue() {
    return this.b;
  }
  get a() {
    return __privateGet(this, _a);
  }
  get alpha() {
    return this.a;
  }
  get opacity() {
    return this.a;
  }
  get h() {
    return this.valuesTo("hsl")[0];
  }
  get hue() {
    return this.h;
  }
  get s() {
    return this.valuesTo("hsl")[1];
  }
  get saturation() {
    return this.s;
  }
  get l() {
    return this.valuesTo("hsl")[2];
  }
  get lightness() {
    return this.l;
  }
  get w() {
    return this.valuesTo("hwb")[1];
  }
  get whiteness() {
    return this.w;
  }
  get bk() {
    return this.valuesTo("hwb")[2];
  }
  get blackness() {
    return this.bk;
  }
  get ciel() {
    return this.valuesTo("lab")[0];
  }
  get CIElightness() {
    return this.ciel;
  }
  get ciea() {
    return this.valuesTo("lab")[1];
  }
  get cieb() {
    return this.valuesTo("lab")[2];
  }
  get ciec() {
    return this.valuesTo("lch")[1];
  }
  get CIEchroma() {
    return this.ciec;
  }
  get cieh() {
    return this.valuesTo("lch")[2];
  }
  get CIEhue() {
    return this.cieh;
  }
  get okl() {
    return this.valuesTo("oklab")[0];
  }
  get OKlightness() {
    return this.okl;
  }
  get oka() {
    return this.valuesTo("oklab")[1];
  }
  get okb() {
    return this.valuesTo("oklab")[2];
  }
  get okc() {
    return this.valuesTo("oklch")[1];
  }
  get OKchroma() {
    return this.okc;
  }
  get okh() {
    return this.valuesTo("oklch")[2];
  }
  get OKhue() {
    return this.okh;
  }
  set luminance(val) {
    const [r, g, b] = this.values;
    const oldLum = this.luminance;
    const newLum = _Couleur.parse(val, "a", { clamp: true });
    if (oldLum === 0) {
      this.r = newLum;
      this.g = newLum;
      this.b = newLum;
    } else {
      const ratio = newLum / oldLum;
      this.r = ratio * r;
      this.g = ratio * g;
      this.b = ratio * b;
    }
  }
  get luminance() {
    if (this.a < 1) throw new Error(`The luminance of a transparent color would be meaningless`);
    return luminance2(this.values);
  }
  /**
   * Returns whether a color property is powerless,
   * i.e. has no effect on the color because of other properties.
   * @param prop The color property to check.
   * @param tolerance A safety margin.
   */
  isPowerless(prop, { tolerance = 1e-4 } = {}) {
    switch (prop) {
      case "h":
        return this.s <= 0 + tolerance || this.l <= 0 + tolerance || this.l >= 1 - tolerance;
      case "s":
        return this.l <= 0 + tolerance || this.l >= 1 - tolerance;
      case "ciea":
      case "cieb":
      case "cieh":
        return this.ciel <= 0 + tolerance || this.ciel >= 1 - tolerance;
      case "oka":
      case "okb":
      case "okh":
        return this.okl <= 0 + tolerance || this.okl >= 1 - tolerance;
      case "oksl":
        return this.valuesTo("okhsl")[2] <= 0 + tolerance;
      case "oksv":
        return this.valuesTo("okhsv")[2] <= 0 + tolerance;
      default:
        return false;
    }
  }
  /***********************************/
  /* Conversion between color spaces */
  /***********************************/
  /**
   * Converts the color values from one color space to another.
   * @param startSpaceID Starting color space, or its identifier.
   * @param endSpaceID Color space to convert to, or its identifier.
   * @param values Array of color values (without alpha) in startSpaceID color space.
   * @returns Array of values in the new color space.
   * @throws When one of the color spaces is not supported.
   */
  static convert(startSpaceID, endSpaceID, values) {
    if (typeof startSpaceID === typeof endSpaceID && startSpaceID === endSpaceID || typeof startSpaceID === "string" && typeof endSpaceID !== "string" && startSpaceID === endSpaceID.id || typeof startSpaceID !== "string" && typeof endSpaceID === "string" && startSpaceID.id === endSpaceID) return values;
    const startSpace = _Couleur.getSpace(startSpaceID);
    const endSpace = _Couleur.getSpace(endSpaceID);
    let path;
    const graph = colorSpacesGraph;
    try {
      path = graph.shortestPath(startSpace.id, endSpace.id).map((node) => node.id);
    } catch (error) {
      if (error instanceof PathNotFoundError) {
        throw new ImpossibleColorConversionError(startSpace, endSpace);
      } else if (error instanceof UndefinedNodeError) {
        if (error.id === startSpace.id) throw new UnsupportedColorSpaceError(startSpace.id);
        else if (error.id === endSpace.id) throw new UnsupportedColorSpaceError(endSpace.id);
        else throw error;
      } else throw error;
    }
    let result = values;
    while (path.length > 1) {
      const start = path.shift();
      const end = path[0];
      const functionName = `${start}_to_${end}`.replace(/-/g, "");
      const func = conversion_exports[functionName];
      if (typeof func !== "function") throw new UndefinedConversionError(functionName);
      result = func(result);
    }
    return result;
  }
  /**
   * Converts the r, g, b values of the color to another color space.
   * @param destinationSpaceID Desired color space, or its identifier.
   * @param options
   * @param options.clamp Whether to clamp the values to their new color space.
   * @returns The array of converted values.
   */
  valuesTo(destinationSpaceID, { clamp = false } = {}) {
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    let values = __privateGet(this, _cache2).get(destinationSpace.id);
    if (!values) {
      values = _Couleur.convert("srgb", destinationSpace, this.values);
      __privateGet(this, _cache2).set(destinationSpace.id, values);
    }
    if (clamp) values = _Couleur.valuesToGamut(destinationSpace, values);
    return values;
  }
  /* Clamping to a color space */
  /**
   * Checks whether parsed values in destinationSpaceID color space are in destinationSpace gamut.
   * @param destinationSpaceID Color space whose gamut will be checked, or its identifier.
   * @param values Array of parsed values.
   * @returns Whether the corresponding color is in gamut.
   */
  static valuesInGamut(destinationSpaceID, values, { tolerance = 1e-4 } = {}) {
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    const gamutSpace = destinationSpace.gamutSpace ? _Couleur.getSpace(destinationSpace.gamutSpace) : destinationSpace;
    const convertedValues = values instanceof _Couleur ? values.valuesTo(gamutSpace) : _Couleur.convert(destinationSpace, gamutSpace, values);
    return convertedValues.every((v, k) => v >= gamutSpace.gamut[k][0] - tolerance && v <= gamutSpace.gamut[k][1] + tolerance);
  }
  /** @see Couleur.valuesInGamut - Non-static version. */
  inGamut(destinationSpaceID, options = {}) {
    return _Couleur.valuesInGamut(destinationSpaceID, this, options);
  }
  /**
   * Clamps parsed values in destinationSpaceID color space to destinationSpace gamut.
   * @param destinationSpaceID Color space whose gamut will be used, or its identifier.
   * @param values Array of parsed values.
   * @returns The array of values in destinationSpace color space, after clamping the color to its gamut.
   */
  static valuesToGamut(destinationSpaceID, values, { method = "okchroma" } = {}) {
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    const gamutSpace = destinationSpace.gamutSpace ? _Couleur.getSpace(destinationSpace.gamutSpace) : destinationSpace;
    const _method = method.toLowerCase();
    if (values instanceof _Couleur) {
      if (values.inGamut(destinationSpace, { tolerance: 0 })) return values.valuesTo(destinationSpace);
      values = values.valuesTo(destinationSpace);
    } else {
      if (_Couleur.valuesInGamut(destinationSpace, values, { tolerance: 0 })) return values;
    }
    let clampedValues;
    switch (_method) {
      // OKLCH chroma gamut clipping
      // Source of the math: https://www.w3.org/TR/css-color-4/#gamut-mapping
      case "okchroma":
        {
          const clampSpace = _Couleur.getSpace("oklch");
          let oklch = _Couleur.convert(destinationSpace, clampSpace, values);
          const \u03C4 = 1e-6;
          const \u03B4 = 0.02;
          if (oklch[0] >= 1 - \u03C4) {
            return _Couleur.convert(gamutSpace, destinationSpace, gamutSpace.white || [1, 1, 1]);
          } else if (oklch[0] <= 0 + \u03C4) {
            return _Couleur.convert(gamutSpace, destinationSpace, gamutSpace.black || [0, 0, 0]);
          }
          let Cmin = 0;
          let Cmax = oklch[1];
          oklch[1] = oklch[1] / 2;
          while (Cmax - Cmin > \u03C4) {
            const gamutValues = _Couleur.convert(clampSpace, gamutSpace, oklch);
            if (_Couleur.valuesInGamut(gamutSpace, gamutValues, { tolerance: 0 })) {
              Cmin = oklch[1];
            } else {
              const naiveOklch = _Couleur.convert(gamutSpace, clampSpace, _Couleur.valuesToGamut(
                gamutSpace,
                gamutValues,
                { method: "naive" }
              ));
              const naiveOklab = _Couleur.convert(clampSpace, "oklab", naiveOklch);
              const oklab = _Couleur.convert(clampSpace, "oklab", oklch);
              if (euclidean(naiveOklab, oklab) < \u03B4) {
                oklch = naiveOklch;
                break;
              }
              Cmax = oklch[1];
            }
            oklch[1] = (Cmin + Cmax) / 2;
          }
          clampedValues = _Couleur.convert(clampSpace, gamutSpace, oklch);
        }
        break;
      // Naively clamp the values
      case "naive":
        {
          const gamutValues = _Couleur.convert(destinationSpace, gamutSpace, values);
          clampedValues = gamutValues.map((v, k) => Math.max(gamutSpace.gamut[k][0], Math.min(v, gamutSpace.gamut[k][1])));
        }
        break;
      default:
        throw new UnsupportedMethodError(_method, "gamut mapping");
    }
    if (_method !== "naive") {
      clampedValues = _Couleur.valuesToGamut(gamutSpace, clampedValues, { method: "naive" });
    }
    return _Couleur.convert(gamutSpace, destinationSpace, clampedValues);
  }
  /** @see Couleur.valuesToGamut - Non-static version. */
  toGamut(destinationSpaceID, options = {}) {
    const destinationSpace = _Couleur.getSpace(destinationSpaceID);
    const destClampedValues = _Couleur.valuesToGamut(destinationSpace, this, options);
    const rgbClampedValues = _Couleur.convert(destinationSpace, "srgb", destClampedValues);
    return new _Couleur([...rgbClampedValues, this.a]);
  }
  /********************************/
  /* Color manipulation functions */
  /********************************/
  /* Color modification */
  /**
   * Modifies a color by changing a specific property.
   * @param prop The color property that will be changed.
   * @param value The value that will be added to the property.
   * @param options
   * @param options.action 'replace' if the value should replace the previous value of the property,
   *                                   'scale' if the value should be multiplied to the previous value of the property,
   *                                   null if the value should be added to the previous value of the property.
   * @returns The modified color.
   */
  change(prop, value, { action } = {}) {
    const replace = (action == null ? void 0 : action.toLowerCase()) === "replace";
    const scale = (action == null ? void 0 : action.toLowerCase()) === "scale";
    const val = scale ? _Couleur.parse(value) : _Couleur.parse(value, prop, { clamp: false });
    const changedColor = new _Couleur(this);
    const oldVal = this[prop];
    const newVal = replace ? val : scale ? oldVal * val : oldVal + val;
    changedColor[prop] = newVal;
    return changedColor;
  }
  /**
   * Modifies a color by replacing the value of a specific property.
   * This is an alias to change() with options.replace = true
   * @param prop The color property that will be changed.
   * @param value The value that will replace the previous value of the property.
   * @returns The modified color.
   */
  replace(prop, value) {
    return this.change(prop, value, { action: "replace" });
  }
  /**
   * Modifies a color by scaling the value of a specific property by a percentage.
   * This is an alias to change() with options.scale = true
   * @param prop The color property that will be changed.
   * @param value The percentage that will be multiplied to the previous value of the property.
   * @returns The modified color.
   */
  scale(prop, value) {
    return this.change(prop, value, { action: "scale" });
  }
  /** @returns The complementary color. */
  complement() {
    return this.change("h", 180);
  }
  /** @returns The inverse color. */
  negative() {
    return new _Couleur([1 - this.r, 1 - this.g, 1 - this.b, this.a]);
  }
  invert() {
    return this.negative();
  }
  /** @returns The shade of grey of the color. */
  greyscale() {
    const L = this.replace("a", 1).luminance;
    return new _Couleur([L, L, L, this.a]);
  }
  grayscale() {
    return this.greyscale();
  }
  /** @returns The sepia tone of the color. */
  sepia() {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new _Couleur([r, g, b, this.a]);
  }
  /* Color interpolation */
  /**
   * Linearly interpolates between two colors.
   * @param color1 The first color.
   * @param color2 The second color.
   * @param ratio The distance of the result color on the [color1, color2] segment.
   * @param interpolationSpace The color space in which to interpolate.
   * @param hueInterpolationMethod The method used to interpolate hues.
   * @returns The interpolated color.
   */
  static interpolate(color1, color2, { ratio = 0.5, interpolationSpace = "oklab", hueInterpolationMethod = "shorter" } = {}) {
    const start = _Couleur.makeInstance(color1);
    const end = _Couleur.makeInstance(color2);
    const _ratio = _Couleur.parse(ratio, null);
    const destinationSpace = _Couleur.getSpace(interpolationSpace);
    const props = _Couleur.propertiesOf(destinationSpace.id);
    let startValues = start.valuesTo(destinationSpace);
    let endValues = end.valuesTo(destinationSpace);
    for (let k = 0; k < startValues.length; k++) {
      const prop = props[k];
      if (start.isPowerless(prop) && end.isPowerless(prop)) {
        startValues[k] = 0;
        endValues[k] = 0;
      } else if (start.isPowerless(prop)) {
        startValues[k] = endValues[k];
      } else if (end.isPowerless(prop)) {
        endValues[k] = startValues[k];
      }
    }
    const premultiply = (values, a) => values.map((v, k) => {
      switch (props[k]) {
        case "h":
        case "cieh":
        case "okh":
          return v;
        default:
          return a * v;
      }
    });
    startValues = premultiply(startValues, start.a);
    endValues = premultiply(endValues, end.a);
    let interpolatedValues = startValues.map((v, k) => {
      const prop = props[k];
      switch (prop) {
        case "h":
        case "cieh":
        case "okh": {
          const diff = endValues[k] - startValues[k];
          switch (hueInterpolationMethod) {
            case "shorter":
              if (diff > 180) startValues[k] += 360;
              else if (diff < -180) endValues[k] += 360;
              break;
            case "longer":
              if (0 < diff && diff < 180) startValues[k] += 360;
              else if (-180 < diff && diff <= 0) endValues[k] += 360;
              break;
            case "increasing":
              if (diff < 0) endValues[k] += 360;
              break;
            case "decreasing":
              if (0 < diff) startValues[k] += 360;
              break;
            default:
              throw new UnsupportedMethodError(hueInterpolationMethod, "hue interpolation");
          }
        }
        // don't break: the value is computed in the default case
        default:
          return startValues[k] + _ratio * (endValues[k] - startValues[k]);
      }
    });
    const interpolatedAlpha = start.a + _ratio * (end.a - start.a);
    const undoPremultiply = (values) => values.map((v, k) => {
      switch (props[k]) {
        case "h":
        case "cieh":
        case "okh":
          return v;
        default:
          return v / interpolatedAlpha;
      }
    });
    interpolatedValues = undoPremultiply(interpolatedValues);
    return new _Couleur([
      ..._Couleur.convert(destinationSpace, "srgb", interpolatedValues),
      interpolatedAlpha
    ]);
  }
  /** @see Couleur.interpolate - Non-static version. */
  interpolate(color, options = {}) {
    return _Couleur.interpolate(this, color, options);
  }
  /**
   * Interpolates a given number of steps between two colors.
   * @param color1 The first color.
   * @param color2 The second color.
   * @param steps The number of colors to interpolate between the two colors.
   * @param options @see mixOptions
   * @returns An array containing the first color, the interpolated colors, and the second color.
   */
  static interpolateInSteps(color1, color2, steps, options) {
    color1 = _Couleur.makeInstance(color1);
    color2 = _Couleur.makeInstance(color2);
    steps = Math.max(0, Math.round(steps));
    const ratios = Array.from(Array(steps).keys()).map((v) => (v + 1) / (steps + 1));
    const interpolatedColors = ratios.map((ratio) => _Couleur.interpolate(color1, color2, __spreadProps(__spreadValues({}, options), { ratio })));
    return [color1, ...interpolatedColors, color2];
  }
  /** @see Couleur.interpolateInSteps - Non-static version. */
  interpolateInSteps(color, steps, options = {}) {
    return _Couleur.interpolateInSteps(this, color, steps, options);
  }
  static mix(...args) {
    var _a2, _b2, _c;
    let _color1, _pct1, _color2, _pct2, _options;
    _color1 = _Couleur.makeInstance(args[0]);
    try {
      _color2 = _Couleur.makeInstance(args[1]);
    } catch (error) {
      _pct1 = _Couleur.parse(args[1], null);
    }
    try {
      if (_color2) throw "Second color already defined";
      _color2 = _Couleur.makeInstance(args[2]);
    } catch (error) {
      try {
        _pct2 = _Couleur.parse(args[2], null);
      } catch (error2) {
        _options = args[2];
      }
    }
    try {
      if (_pct2) throw "Second percentage already defined";
      _pct2 = _Couleur.parse(args[3], null);
    } catch (error) {
      if (!_options) _options = args[3];
    }
    if (!_options) _options = (_a2 = args[4]) != null ? _a2 : {};
    if (!_pct1 && !_pct2) {
      _pct1 = 0.5;
      _pct2 = 0.5;
    } else if (_pct1 && !_pct2) {
      _pct2 = 1 - _pct1;
    } else if (!_pct1 && _pct2) {
      _pct1 = 1 - _pct2;
    }
    if (!_pct1 || !_pct2 || !_color1 || !_color2) {
      throw new Error("Some arguments are invalid");
    }
    let alphaMultiplier = 1;
    const sum = _pct1 + _pct2;
    if (sum === 0) throw new Error("The percentages passed as arguments add up to zero; that is invalid");
    else if (sum < 1) {
      alphaMultiplier = sum;
    }
    _pct1 = _pct1 / sum;
    _pct2 = _pct2 / sum;
    const appliedOptions = {};
    appliedOptions.interpolationSpace = (_b2 = _options.interpolationSpace) != null ? _b2 : "oklab";
    appliedOptions.hueInterpolationMethod = (_c = _options.hueInterpolationMethod) != null ? _c : "shorter";
    appliedOptions.ratio = _pct2;
    const interpolatedColor = _Couleur.interpolate(_color1, _color2, appliedOptions);
    return interpolatedColor.replace("a", alphaMultiplier * interpolatedColor.a);
  }
  mix(...args) {
    return _Couleur.mix(this, ...args);
  }
  /* Color blending */
  /**
   * Blends two colors together.
   * @param backgroundColor Background color.
   * @param overlayColor Overlay color.
   * @param alpha Alpha value that will replace overlay's.
   * @returns The resulting color.
   */
  static blend(backgroundColor, overlayColor, alpha) {
    const background = _Couleur.makeInstance(backgroundColor);
    const overlay = _Couleur.makeInstance(overlayColor);
    if (alpha != null)
      overlay.a = _Couleur.parse(alpha, "a");
    if (overlay.a === 0) return background;
    else if (overlay.a === 1) return overlay;
    const a = overlay.a + background.a * (1 - overlay.a);
    const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
    const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
    const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
    return new _Couleur([r, g, b, a]);
  }
  /**
   * Blends colors together, in the order they were given.
   * @param colors List of colors to successively blend.
   * @returns The resulting color.
   * @throws When there are less than two colors.
   */
  static blendAll(...colors) {
    if (colors.length < 2) throw new Error(`You need at least 2 colors to blend`);
    const background = colors.shift();
    const overlay = colors.shift();
    if (background == null || overlay == null) throw new Error("Cannot blend undefined color");
    const mix = _Couleur.blend(background, overlay);
    if (colors.length === 0) return mix;
    else return _Couleur.blendAll(mix, ...colors);
  }
  /** @see Couleur.blend - Non-static version. */
  blend(overlayColor, alpha) {
    return _Couleur.blend(this, overlayColor, alpha);
  }
  /** @see Couleur.blendAll - Non-static version. */
  blendAll(...colors) {
    return _Couleur.blendAll(this, ...colors);
  }
  /**
   * Solves the equation mix = blend(background, overlay) with background unknown.
   * @param mixColor The result of the blend.
   * @param overlayColor Color that was mixed with background to create mix.
   * @returns The background that is solution to the equation, if it has one.
   * @throws If the equation has an infinite amount of solutions.
   */
  static unblend(mixColor, overlayColor, alpha) {
    const mix = _Couleur.makeInstance(mixColor);
    const overlay = _Couleur.makeInstance(overlayColor);
    if (alpha != null)
      overlay.a = _Couleur.parse(alpha, "a");
    if (overlay.a === 1) {
      throw new Error(`Overlay color ${JSON.stringify(overlay.rgb)} isn't transparent, so the background it was blended onto could have been any color`);
    } else if (overlay.a === 0) return mix;
    else {
      if (mix.a < overlay.a) return null;
      else if (mix.a === overlay.a) {
        if (_Couleur.same(mix, overlay)) return new _Couleur([0, 0, 0, 0]);
        else return null;
      } else {
        const a = (mix.a - overlay.a) / (1 - overlay.a);
        const r = (mix.r * mix.a - overlay.r * overlay.a) / (a * (1 - overlay.a));
        const g = (mix.g * mix.a - overlay.g * overlay.a) / (a * (1 - overlay.a));
        const b = (mix.b * mix.a - overlay.b * overlay.a) / (a * (1 - overlay.a));
        const clampedValues = _Couleur.valuesToGamut("srgb", [r, g, b]);
        return new _Couleur([...clampedValues, a]);
      }
    }
  }
  /**
   * Solves the equation mix = blendAll(background, ...overlays) with background unknown.
   * @param  {...color} colors - Colors to unblend.
   * @returns The solution to the equation, if it has one.
   * @throws If the equation has an infinite amount of solutions.
   */
  static unblendAll(...colors) {
    if (colors.length < 2) throw new Error(`You need at least 2 colors to unblend`);
    const mix = colors.shift();
    const overlay = colors.shift();
    if (mix == null || overlay == null) throw new Error("Cannot unblend undefined color");
    const background = _Couleur.unblend(mix, overlay);
    if (background == null) return null;
    else if (colors.length === 0) return background;
    else return _Couleur.unblendAll(background, ...colors);
  }
  /** @see Couleur.unblend - Non-static version. */
  unblend(overlayColor, alpha) {
    return _Couleur.unblend(this, overlayColor, alpha);
  }
  /** @see Couleur.unblendAll - Non-static version. */
  unblendAll(...colors) {
    return _Couleur.unblendAll(this, ...colors);
  }
  /**
   * Solves the equation mix = blend(background, overlay) with overlay unknown.
   * @param backgroundColor The background color.
   * @param mixColor The result of the blend.
   * @param alphas The alpha value(s) you want the solution(s) to have.
   * @param options
   * @param options.ignoreTransparent Whether to return the color 'transparent' when it's a solution.
   * @returns The solution(s) to the equation.
   */
  static whatToBlend(backgroundColor, mixColor, alphas = [], { ignoreTransparent = false } = {}) {
    const background = _Couleur.makeInstance(backgroundColor);
    const mix = _Couleur.makeInstance(mixColor);
    let overlays = [];
    const calculateSolution = (a) => {
      const r = (mix.r * mix.a - background.r * background.a * (1 - a)) / a;
      const g = (mix.g * mix.a - background.g * background.a * (1 - a)) / a;
      const b = (mix.b * mix.a - background.b * background.a * (1 - a)) / a;
      if (!_Couleur.valuesInGamut("srgb", [r, g, b], { tolerance: 1 / 255 })) throw new Error(`This color doesn't exist`);
      const clampedValues = _Couleur.valuesToGamut("srgb", [r, g, b], { method: "naive" });
      return new _Couleur([...clampedValues, a]);
    };
    const requestedAlphas = [alphas].flat();
    const computedAlphas = requestedAlphas.length > 0 ? requestedAlphas.filter((a) => a > 0 && a < 1) : Array.from({ length: 9 }, (v, k) => (k + 1) / 10);
    if (mix.a < background.a) return [];
    else if (mix.a > background.a) {
      if (mix.a === 1) overlays.push(mix);
      else if (background.a === 0) overlays.push(mix);
      else {
        const a = (mix.a - background.a) / (1 - background.a);
        try {
          overlays.push(calculateSolution(a));
        } catch (error) {
          return [];
        }
      }
    } else if (mix.a === background.a) {
      if (mix.a === 0) overlays.push(new _Couleur("transparent"));
      else if (mix.a < 1) {
        if (_Couleur.same(mix, background)) overlays.push(new _Couleur("transparent"));
        else return [];
      } else {
        if (_Couleur.same(mix, background)) overlays.push(new _Couleur("transparent"));
        for (const a of computedAlphas) {
          try {
            overlays.push(calculateSolution(a));
          } catch (error) {
            continue;
          }
        }
        overlays.push(mix);
      }
    }
    let result = requestedAlphas.length > 0 ? overlays.filter((c) => requestedAlphas.includes(c.a)) : overlays;
    if (ignoreTransparent) result = result.filter((r) => r.a > 0);
    return result;
  }
  /** @see Couleur.whatToBlend - Non-static version. */
  whatToBlend(mixColor, alphas) {
    return _Couleur.whatToBlend(this, mixColor, alphas);
  }
  /* Color comparison */
  /**
   * Computes the contrast between two colors as defined by WCAG2 or 3.
   * @param textColor First color (for text over a background, this is the text color).
   * @param backgroundColor Second color (for text over a background, this is the background color).
   * @param options
   * @param options.method Whether to use the new APCA or the old WCAG2 method.
   * @returns Contrast between the two colors.
   * @throws When the background color is transparent, as contrast can't be measured with it.
   */
  static contrast(textColor, backgroundColor, { method = "apca" } = {}) {
    const background = _Couleur.makeInstance(backgroundColor);
    if (background.a < 1) throw new Error(`The contrast with a transparent background color would be meaningless`);
    let text = _Couleur.makeInstance(textColor);
    if (text.a < 1) text = _Couleur.blend(background, text);
    switch (method.toLowerCase()) {
      case "apca":
        return APCAcontrast(text.values, background.values);
      case "wcag2":
        return WCAG2(text.values, background.values);
      default:
        throw new UnsupportedMethodError(method, "contrast calculations");
    }
  }
  /** @see Couleur.contrast - Non-static version. */
  contrast(backgroundColor, options = {}) {
    return _Couleur.contrast(this, backgroundColor, options);
  }
  /** 
   * Determines which color scheme ('light' or 'dark') would lead to a better contrast with the color.
   * The 'light' color scheme means a light background with dark text.
   * The 'dark' color scheme means a dark background with light text.
   * @param as Whether the color is the background or the text color.
   * @returns
   */
  bestColorScheme(as = "background") {
    const rgba = [...this.toGamut("srgb").values, this.a];
    switch (as) {
      case "text": {
        const Cblack = Math.abs(_Couleur.contrast(rgba, "black", { method: "apca" }));
        const Cwhite = Math.abs(_Couleur.contrast(rgba, "white", { method: "apca" }));
        return Cblack >= Cwhite ? "dark" : "light";
      }
      case "background": {
        const Cblack = Math.abs(_Couleur.contrast("black", rgba, { method: "apca" }));
        const Cwhite = Math.abs(_Couleur.contrast("white", rgba, { method: "apca" }));
        return Cblack >= Cwhite ? "light" : "dark";
      }
      default:
        throw new Error('Argument must be "background" or "text"');
    }
  }
  /**
     * Modifies the OK lightness of a color to give it better contrast with another color.
     * @param referenceColor The color with which contrast will be measured and improved.
     * @param desiredContrast The absolute value of the contrast to reach.
     * @param options
     * @param options.as Whether the color this function is applied to is used as 'text' or 'background' color.
     * @param options.lower Whether contrast should be lowered if it's already bigger than desiredContrast.
  *                         If true, and contrast is higher from the start, it will be lowered until it reaches desiredContrast.
  *                         If false, and contrast is higher from the start, nothing will be done.
     * @param options.colorScheme Whether the text color should be darker than the background color (colorScheme = 'light')
     *                            or lighter than the background color (colorScheme = 'dark').
     *                            If null, the starting color scheme will be preserved (i.e. if the text color starts darker
     *                            than the background color, it will stay darker.)
     * @param options.method The method to use to compute the contrast.
     * @returns The modified color which verifies Couleur.contrast(color, referenceColor) >= desiredContrast.
     */
  improveContrast(referenceColor, desiredContrast, { as = "text", lower = false, colorScheme, method = "apca" } = {}) {
    const background = as === "text" ? _Couleur.makeInstance(referenceColor) : this;
    const text = as === "text" ? this : _Couleur.makeInstance(referenceColor);
    const backgroundLab = background.valuesTo("oklab");
    const textLab = text.valuesTo("oklab");
    const movingLab = as === "text" ? textLab : backgroundLab;
    const startContrast = Math.abs(_Couleur.contrast(text, background, { method }));
    let directionContrast;
    if (startContrast > desiredContrast) directionContrast = -1;
    else if (startContrast < desiredContrast) directionContrast = 1;
    else directionContrast = 0;
    if (directionContrast < 0 && lower === false || directionContrast === 0) return this;
    const _colorScheme = colorScheme || (backgroundLab[0] < textLab[0] ? "dark" : "light");
    const cBlack = Math.abs(
      as === "text" ? _Couleur.contrast(background, "black", { method }) : _Couleur.contrast("black", text, { method })
    );
    const cWhite = Math.abs(
      as === "text" ? _Couleur.contrast(background, "white", { method }) : _Couleur.contrast("white", text, { method })
    );
    const isPossible = {
      lowering: directionContrast > 0 ? cBlack >= desiredContrast : cBlack <= desiredContrast,
      raising: directionContrast > 0 ? cWhite >= desiredContrast : cWhite <= desiredContrast
    };
    let directionOKL;
    if (isPossible.lowering && !isPossible.raising) directionOKL = -1;
    else if (isPossible.raising && !isPossible.lowering) directionOKL = 1;
    else if (!isPossible.raising && !isPossible.lowering) {
      if (as === "text") {
        if (_colorScheme === "light") return new _Couleur("black");
        else return new _Couleur("white");
      } else {
        if (_colorScheme === "light") return new _Couleur("white");
        else return new _Couleur("black");
      }
    } else {
      if (_colorScheme === "light" && directionContrast > 0) directionOKL = -1;
      else if (_colorScheme === "light" && directionContrast < 0) directionOKL = 1;
      else if (_colorScheme === "dark" && directionContrast > 0) directionOKL = 1;
      else directionOKL = -1;
      if (as === "background") directionOKL = -directionOKL;
    }
    const \u03C4 = 1e-4;
    let OKLmin = directionOKL > 0 ? movingLab[0] : 0;
    let OKLmax = directionOKL > 0 ? 1 : movingLab[0];
    while (OKLmax - OKLmin > \u03C4) {
      const okl = (OKLmin + OKLmax) / 2;
      const newValues = movingLab;
      newValues[0] = okl;
      const newContrast = Math.abs(
        as === "text" ? _Couleur.contrast(_Couleur.convert("oklab", "srgb", newValues), background, { method }) : _Couleur.contrast(text, _Couleur.convert("oklab", "srgb", newValues), { method })
      );
      const condition = directionContrast > 0 ? newContrast < desiredContrast : newContrast > desiredContrast;
      if (condition) {
        if (directionOKL > 0) OKLmin = okl;
        else OKLmax = okl;
      } else {
        if (directionOKL > 0) OKLmax = okl;
        else OKLmin = okl;
      }
      movingLab[0] = okl;
    }
    let result = new _Couleur(_Couleur.convert("oklab", "srgb", movingLab));
    const lastContrast = Math.abs(
      as === "text" ? _Couleur.contrast(result, background, { method }) : _Couleur.contrast(text, result, { method })
    );
    if (lastContrast < desiredContrast) {
      if (as === "text") {
        if (_colorScheme === "light") movingLab[0] = OKLmin;
        else movingLab[0] = OKLmax;
      } else {
        if (_colorScheme === "light") movingLab[0] = OKLmax;
        else movingLab[0] = OKLmin;
      }
    }
    return new _Couleur(_Couleur.convert("oklab", "srgb", movingLab));
  }
  /**
   * Computes the distance between two colors.
   * @param color1 
   * @param color2 
   * @param options
   * @param options.method The method to use to compute the distance.
   * @param options.alpha Whether to add the distance between alpha values.
   * @returns The distance between the two colors in sRGB space.
   */
  static distance(color1, color2, { method = "deltae2000", alpha = true } = {}) {
    const colore1 = _Couleur.makeInstance(color1);
    const colore2 = _Couleur.makeInstance(color2);
    let opaqueDist = Infinity;
    let alphaCoeff = 1;
    switch (method.toLowerCase()) {
      case "ciede2000":
      case "deltae2000":
        {
          const [lab1, lab2] = [colore1, colore2].map((c) => c.valuesTo("lab"));
          opaqueDist = CIEDE2000(lab1, lab2);
          alphaCoeff = 50;
        }
        break;
      case "deltaeok":
        {
          const [oklab1, oklab2] = [colore1, colore2].map((c) => c.valuesTo("oklab"));
          opaqueDist = euclidean(oklab1, oklab2);
        }
        break;
      case "euclidean":
        {
          const [rgb1, rgb2] = [colore1, colore2].map((c) => c.values);
          opaqueDist = euclidean(rgb1, rgb2);
        }
        break;
      default:
        throw new UnsupportedMethodError(method, "distance calculations");
    }
    const alphaDist = alpha ? euclidean([colore1.a], [colore2.a]) : 0;
    return opaqueDist + alphaCoeff * alphaDist;
  }
  /** @see Couleur.distance - Non-static version. */
  distance(color, options = {}) {
    return _Couleur.distance(this, color, options);
  }
  /**
   * Determines if two colors are the same, with a certain tolerance.
   * @param color1 
   * @param color2 
   * @param tolerance The minimum distance between the two colors to consider them different.
   * @returns Whether the two colors are considered the same.
   */
  static same(color1, color2, { tolerance = 1, method = "deltae2000" } = {}) {
    if (_Couleur.distance(color1, color2, { method }) > tolerance) return false;
    else return true;
  }
  /** @see Couleur.same - Non-static version. */
  same(color, options = {}) {
    return _Couleur.same(this, color, options);
  }
  /**************/
  /* Color data */
  /**************/
  /**
   * Gets the list of the short names of color properties used in a given CSS format.
   * @param format Name of the color format.
   * @returns Array of color property names.
   */
  static propertiesOf(format) {
    var _a2;
    return (_a2 = _Couleur.getSpace(format.toLowerCase()).properties) != null ? _a2 : [];
  }
  /** @returns Array of all color property short names. */
  static get properties() {
    var _a2;
    const props = /* @__PURE__ */ new Set();
    for (const space of _Couleur.colorSpaces) {
      (_a2 = space.properties) == null ? void 0 : _a2.map((p) => props.add(p));
    }
    return [...props, "a"];
  }
  /**
   * Gets a color space from its id.
   * @param spaceID Identifier of a color space, or a color space itself.
   * @returns The corresponding color space object.
   * @throws When the color space is not supported.
   */
  static getSpace(spaceID) {
    let result;
    if (typeof spaceID !== "string") {
      if (spaceID == null) {
        throw new UnsupportedColorSpaceError("null");
      } else {
        return spaceID;
      }
    } else {
      let id = spaceID.toLowerCase();
      result = _Couleur.colorSpaces.find((sp) => sp.id === id || sp.aliases.includes(id));
      if (result == null) throw new UnsupportedColorSpaceError(spaceID);
      return result;
    }
  }
  /** @returns Array of supported color spaces. */
  static get colorSpaces() {
    return color_spaces_default;
  }
  /** @returns Graph of supported color spaces and the links (i.e. conversion functions) between them. */
  static get colorSpacesGraph() {
    return colorSpacesGraph;
  }
  /** @returns List of named colors in CSS. */
  static get namedColors() {
    return named_colors_default;
  }
};
_r = new WeakMap();
_g = new WeakMap();
_b = new WeakMap();
_a = new WeakMap();
_cache2 = new WeakMap();
var Couleur = _Couleur;
export {
  contrasts_exports as Contrasts,
  conversion_exports as Conversions,
  distances_exports as Distances,
  css_formats_exports as cssFormats,
  Couleur as default
};
