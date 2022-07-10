var __defProp = Object.defineProperty;
var __pow = Math.pow;
var __export = (target, all) => {
  for (var name in all)
    __defProp(target, name, { get: all[name], enumerable: true });
};

// src/ts/color-spaces.ts
var colorSpaces = [
  {
    id: "srgb",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["srgb-linear", "hsl"]
  },
  {
    id: "srgb-linear",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["srgb", "xyz-d65"]
  },
  {
    id: "hsl",
    gamut: [[0, 360], [0, 1], [0, 1]],
    links: ["srgb", "hwb"]
  },
  {
    id: "hwb",
    gamut: [[0, 360], [0, 1], [0, 1]],
    links: ["hsl"]
  },
  {
    id: "lab",
    gamut: [[0, 4], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["xyz-d50", "lch"]
  },
  {
    id: "lch",
    gamut: [[0, 4], [0, Infinity], [0, 360]],
    links: ["lab"]
  },
  {
    id: "xyz-d50",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["lab", "xyz-d65", "prophoto-rgb-linear"]
  },
  {
    id: "xyz-d65",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["xyz-d50", "srgb-linear", "display-p3-linear", "a98-rgb-linear", "rec2020-linear", "oklab"]
  },
  {
    id: "display-p3",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["display-p3-linear"]
  },
  {
    id: "display-p3-linear",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["display-p3", "xyz-d65"]
  },
  {
    id: "a98-rgb",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["a98-rgb-linear"]
  },
  {
    id: "a98-rgb-linear",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["a98-rgb", "xyz-d65"]
  },
  {
    id: "prophoto-rgb",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["prophoto-rgb-linear"]
  },
  {
    id: "prophoto-rgb-linear",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["prophoto-rgb", "xyz-d50"]
  },
  {
    id: "rec2020",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["rec2020-linear"]
  },
  {
    id: "rec2020-linear",
    gamut: [[0, 1], [0, 1], [0, 1]],
    links: ["rec2020", "xyz-d65"]
  },
  {
    id: "oklab",
    gamut: [[0, 4], [-Infinity, Infinity], [-Infinity, Infinity]],
    links: ["xyz-d65", "oklch"]
  },
  {
    id: "oklch",
    gamut: [[0, 4], [0, Infinity], [0, 360]],
    links: ["oklab"]
  }
];
var color_spaces_default = colorSpaces;

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
  oklab_to_oklch: () => oklab_to_oklch,
  oklab_to_xyzd65: () => oklab_to_xyzd65,
  oklch_to_oklab: () => oklch_to_oklab,
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
  while (cieh < 0)
    cieh += 360;
  while (cieh > 360)
    cieh -= 360;
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

// src/ts/conversion.ts
function srgb_to_hsl(rgb) {
  const [r, g, b] = rgb;
  const max = Math.max(r, g, b);
  const min = Math.min(r, g, b);
  const chroma = max - min;
  const l = (max + min) / 2;
  let h;
  if (chroma === 0)
    h = 0;
  else
    switch (max) {
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
  while (h < 0)
    h += 360;
  while (h > 360)
    h -= 360;
  let s;
  if (l === 0 || l === 1)
    s = 0;
  else if (l <= 0.5)
    s = chroma / (2 * l);
  else
    s = chroma / (2 - 2 * l);
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
  if (v === 0)
    _s = 0;
  else
    _s = 2 - 2 * l / v;
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
  if (_bk === 1)
    _s = 0;
  else
    _s = 1 - _w / v;
  let s;
  const l = v - v * _s / 2;
  if (l === 0 || l === 1)
    s = 0;
  else
    s = (v - l) / Math.min(l, 1 - l);
  return [h, s, l];
}

// src/ts/ext/apca-contrast.ts
function luminance(rgb) {
  const coeffs = [0.2126729, 0.7151522, 0.072175];
  const gamma = 2.4;
  return rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
}
function APCAcontrast(rgbText, rgbBack) {
  let [Ytext, Yback] = [rgbText, rgbBack].map((rgb) => luminance(rgb));
  if (isNaN(Ytext) || isNaN(Yback) || Math.min(Ytext, Yback) < 0 || Math.max(Ytext, Yback) > 1.1)
    return 0;
  const normBG = 0.56, normTXT = 0.57, revTXT = 0.62, revBG = 0.65;
  const blkThrs = 0.022, blkClmp = 1.414, scaleBoW = 1.14, scaleWoB = 1.14, loBoWoffset = 0.027, loWoBoffset = 0.027, loClip = 0.1, deltaYmin = 5e-4;
  [Ytext, Yback] = [Ytext, Yback].map((Y) => Y > blkThrs ? Y : Y + Math.pow(blkThrs - Y, blkClmp));
  if (Math.abs(Ytext - Yback) < deltaYmin)
    return 0;
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

// src/ts/css-formats.ts
var css_formats_exports = {};
__export(css_formats_exports, {
  Formats: () => Formats,
  RegExps: () => RegExps
});
var numberExp = "(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?";
var RegExps = {
  number: numberExp,
  percentage: numberExp + "%",
  numberOrPercentage: numberExp + "%?",
  angle: numberExp + "(?:deg|grad|rad|turn)?"
};
var Formats = [
  {
    id: "hex",
    syntaxes: [
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/,
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
    ]
  },
  {
    id: "rgb",
    syntaxes: [
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.numberOrPercentage})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "hsl",
    syntaxes: [
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "hwb",
    syntaxes: [
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "lab",
    syntaxes: [
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number})\\)$`),
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "lch",
    syntaxes: [
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle})\\)$`),
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "oklab",
    syntaxes: [
      new RegExp(`^oklab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number})\\)$`),
      new RegExp(`^oklab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "oklch",
    syntaxes: [
      new RegExp(`^oklch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle})\\)$`),
      new RegExp(`^oklch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "color",
    syntaxes: [
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  },
  {
    id: "name",
    syntaxes: [
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
  while (hh1 < 0)
    hh1 += 360;
  while (hh1 > 360)
    hh1 -= 360;
  while (hh2 < 0)
    hh2 += 360;
  while (hh2 > 360)
    hh2 -= 360;
  const dL = L2 - L1, dC = CC2 - CC1;
  const dhh = CC1 * CC2 === 0 ? 0 : Math.abs(hh2 - hh1) <= 180 ? hh2 - hh1 : hh2 - hh1 > 180 ? hh2 - hh1 - 360 : hh2 - hh1 + 360;
  const dH = 2 * Math.sqrt(CC1 * CC2) * Math.sin(Math.PI / 180 * (dhh / 2));
  const mL = (L1 + L2) / 2, mCC = (CC1 + CC2) / 2;
  const mhh = CC1 * CC2 === 0 ? hh1 + hh2 : Math.abs(hh2 - hh1) <= 180 ? (hh1 + hh2) / 2 : hh1 + hh2 >= 360 ? (hh1 + hh2 - 360) / 2 : (hh1 + hh2 + 360) / 2;
  const T = 1 - 0.17 * Math.cos(Math.PI / 180 * (mhh - 30)) + 0.24 * Math.cos(Math.PI / 180 * (2 * mhh)) + 0.32 * Math.cos(Math.PI / 180 * (3 * mhh + 6)) - 0.2 * Math.cos(Math.PI / 180 * (4 * mhh - 63)), dTH = 30 * Math.exp(-1 * __pow((mhh - 275) / 25, 2)), RC = 2 * Math.sqrt(__pow(mCC, 7) / (__pow(mCC, 7) + __pow(25, 7))), SL = 1 + 0.015 * __pow(mL - 50, 2) / Math.sqrt(20 + __pow(mL - 50, 2)), SC = 1 + 0.045 * mCC, SH = 1 + 0.015 * mCC * T, RT = -1 * Math.sin(Math.PI / 180 * (2 * dTH)) * RC;
  return Math.sqrt(__pow(dL / SL, 2) + __pow(dC / SC, 2) + __pow(dH / SH, 2) + RT * (dC / SC) * (dH / SH));
}

// src/ts/graph.ts
var GraphNode = class {
  constructor(object) {
    this.visited = false;
    this.predecessor = null;
    this.data = null;
    var _a;
    this.id = object.id;
    this.links = object.links;
    this.data = (_a = object.data) != null ? _a : null;
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
var Graph = class {
  constructor(array) {
    this.nodes = array.map((e) => new GraphNode(e));
  }
  getNode(id) {
    const node = this.nodes.find((node2) => node2.id === id);
    if (node == null)
      throw `Node ${JSON.stringify(id)} does not exist`;
    return node;
  }
  cleanUp() {
    for (const node of this.nodes) {
      node.unvisit();
      node.unfollow();
    }
  }
  shortestPath(startID, endID) {
    if (startID === endID)
      return [];
    try {
      const start = this.getNode(startID);
      const end = this.getNode(endID);
      const queue = [start];
      start.visit();
      let found = false;
      walk:
        while (queue.length > 0) {
          const current2 = queue.shift();
          if (current2.id === end.id) {
            found = true;
            break walk;
          }
          for (const neighbourID of current2.links) {
            const neighbour = this.getNode(neighbourID);
            if (neighbour.visited === false) {
              neighbour.visit();
              neighbour.follow(current2);
              queue.push(neighbour);
            }
          }
        }
      if (!found)
        throw `No path found from ${JSON.stringify(start.id)} to ${JSON.stringify(end.id)}`;
      const path = [end];
      let current = end;
      while (current.predecessor != null) {
        path.push(current.predecessor);
        current = current.predecessor;
      }
      this.cleanUp();
      return path.reverse();
    } catch (error) {
      this.cleanUp();
      throw error;
    }
  }
  topologicalOrder() {
    const orderedList = [];
    const unvisitedNodes = [...this.nodes];
    const visit = (node) => {
      if (node.visited === true)
        return;
      if (node.visited === "temp")
        throw "The graph is not a directed acyclic graph";
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
var utils_exports = {};
__export(utils_exports, {
  angleToRange: () => angleToRange,
  fromHex: () => fromHex,
  pRound: () => pRound,
  pad: () => pad,
  toHex: () => toHex,
  toUnparsedAlpha: () => toUnparsedAlpha
});
function pad(s) {
  return s.length < 2 ? `0${s}` : s;
}
function angleToRange(angle) {
  let h = angle;
  while (h < 0)
    h += 360;
  while (h > 360)
    h -= 360;
  return h;
}
function pRound(number, precision = 5) {
  let x = typeof number === "number" ? number : Number(number);
  return Number(parseFloat(x.toPrecision(precision)));
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
var Couleur = class {
  constructor(color) {
    this.r = 0;
    this.g = 0;
    this.b = 0;
    this.a = 0;
    if (color instanceof Couleur || typeof color === "object" && "r" in color && "g" in color && "b" in color) {
      this.r = color.r;
      this.g = color.g;
      this.b = color.b;
      this.a = typeof color.a === "number" ? color.a : 1;
    } else if (Array.isArray(color) && (color.length == 3 || color.length == 4)) {
      [this.r, this.g, this.b] = Couleur.toGamut("srgb", color.slice(0, 3), "srgb", { method: "naive" });
      this.a = Math.max(0, Math.min(Number(toUnparsedAlpha(color[3])), 1));
    } else if (typeof color === "string") {
      const format = Couleur.matchSyntax(color.trim());
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
            const props = [...Couleur.propertiesOf(format.id), "a"];
            const space = Couleur.getSpace(format.id);
            this.set(values, props, space);
          }
          break;
        case "color":
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], toUnparsedAlpha(format.data[5])]);
          break;
        default:
          throw `${JSON.stringify(color)} is not a valid color format`;
      }
    } else
      throw `Couleur objects can only be created from a string, an array of parsed values, or another Couleur object ; this is not one: ${JSON.stringify(color)}`;
  }
  static makeInstance(color) {
    if (color instanceof Couleur)
      return color;
    else
      return new Couleur(color);
  }
  static matchSyntax(colorString) {
    const tri = colorString.slice(0, 3);
    let format;
    if (tri.slice(0, 1) === "#")
      format = Couleur.formats[0];
    else
      switch (tri) {
        case "rgb":
          format = Couleur.formats[1];
          break;
        case "hsl":
          format = Couleur.formats[2];
          break;
        case "hwb":
          format = Couleur.formats[3];
          break;
        case "lab":
          format = Couleur.formats[4];
          break;
        case "lch":
          format = Couleur.formats[5];
          break;
        case "okl":
          {
            if (colorString.startsWith("oklab")) {
              format = Couleur.formats[6];
            } else if (colorString.startsWith("oklch")) {
              format = Couleur.formats[7];
            }
          }
          break;
        case "col":
          format = Couleur.formats[8];
          break;
        default:
          format = Couleur.formats[9];
      }
    if (format == null)
      throw "No matching format";
    for (const syntaxe of format.syntaxes) {
      const result = colorString.match(syntaxe);
      if (result != null && result[0] === colorString) {
        if (format.id === "name") {
          if (colorString === "transparent")
            return { id: "rgb", data: ["", "0", "0", "0", "0"] };
          const allNames = Couleur.namedColors;
          const hex = allNames.get(colorString.toLowerCase()) || null;
          if (hex)
            return Couleur.matchSyntax(`#${hex}`);
        } else {
          return { id: format.id, data: result };
        }
      }
    }
    throw `${JSON.stringify(colorString)} is not a valid color format`;
  }
  static parse(value, prop = null, { clamp = true } = {}) {
    const val = String(value);
    const nval = parseFloat(val);
    try {
      switch (prop) {
        case "a": {
          if (new RegExp("^" + RegExps.percentage + "$").test(val)) {
            if (clamp)
              return Math.max(0, Math.min(nval / 100, 1));
            else
              return nval / 100;
          } else if (new RegExp("^" + RegExps.number + "$").test(val)) {
            if (clamp)
              return Math.max(0, Math.min(nval, 1));
            else
              return nval;
          } else
            throw "invalid";
        }
        case "r":
        case "g":
        case "b": {
          if (new RegExp("^" + RegExps.percentage + "$").test(val)) {
            if (clamp)
              return Math.max(0, Math.min(nval / 100, 1));
            else
              return nval / 100;
          } else if (new RegExp("^" + RegExps.number + "$").test(val)) {
            if (clamp)
              return Math.max(0, Math.min(nval / 255, 1));
            else
              return nval / 255;
          } else
            throw "invalid";
        }
        case "h":
        case "cieh":
        case "okh": {
          let h = nval;
          if (new RegExp("^" + RegExps.number + "$").test(val)) {
            return angleToRange(h);
          } else if (new RegExp("^" + RegExps.angle + "$").test(val)) {
            if (val.slice(-3) === "deg") {
            } else if (val.slice(-4) === "grad")
              h = h * 360 / 400;
            else if (val.slice(-3) === "rad")
              h = h * 180 / Math.PI;
            else if (val.slice(-4) === "turn")
              h = h * 360;
            else
              throw "angle";
            return angleToRange(h);
          } else
            throw "invalid";
        }
        case "s":
        case "l":
        case "w":
        case "bk":
        case "ciel":
        case "okl": {
          if (new RegExp("^" + RegExps.percentage + "$").test(val)) {
            if (clamp)
              return Math.max(0, Math.min(nval / 100, 1));
            else
              return nval / 100;
          } else
            throw "invalid";
        }
        case "ciea":
        case "cieb":
        case "oka":
        case "okb":
        case "okc": {
          if (new RegExp("^" + RegExps.number + "$").test(val)) {
            return nval;
          } else
            throw "invalid";
        }
        case "ciec": {
          if (new RegExp("^" + RegExps.number + "$").test(val)) {
            if (clamp)
              return Math.max(0, nval);
            else
              return nval;
          } else
            throw "invalid";
        }
        default: {
          if (new RegExp("^" + RegExps.percentage + "$").test(val)) {
            return nval / 100;
          } else if (new RegExp("^" + RegExps.number + "$").test(val)) {
            return nval;
          } else
            throw "invalidest";
        }
      }
    } catch (error) {
      if (error === "invalid")
        throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
      else if (error === "angle")
        throw `Invalid angle value: ${JSON.stringify(value)}`;
      else
        throw `Invalid arbitrary value: ${JSON.stringify(value)}`;
    }
  }
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
      case "oka":
      case "okb":
      case "okc":
        return precision === null ? `${value}` : `${Math.round(__pow(10, Math.max(precision, 4)) * value) / __pow(10, Math.max(precision, 4))}`;
      case "a":
        return precision === null ? `${value}` : `${Math.round(__pow(10, Math.max(precision, 2)) * value) / __pow(10, Math.max(precision, 2))}`;
      default:
        return precision === null ? `${value}` : `${Math.round(__pow(10, precision) * value) / __pow(10, precision)}`;
    }
  }
  set(data, props, spaceID, { parsed = false } = {}) {
    const space = Couleur.getSpace(spaceID);
    const values = parsed ? data.map((v) => Number(v)) : props.map((p, i) => Couleur.parse(data[i], p));
    [this.r, this.g, this.b] = Couleur.convert(space, "srgb", values);
    this.a = Couleur.parse(toUnparsedAlpha(data[3]), "a");
  }
  setHex(hexa) {
    let [r, g, b] = hexa.map((v) => String(v));
    let a = String(hexa[3]) || "ff";
    const vals = fromHex([r, g, b, a]).map((v, k) => k === 3 ? v : v * 255);
    this.set(vals, ["r", "g", "b"], "srgb");
  }
  setColor(spaceID, values) {
    let vals = values.slice(0, 3).map((v) => Couleur.parse(v));
    const a = Couleur.parse(values[3]);
    switch (spaceID.toLowerCase()) {
      case "srgb":
      case "srgb-linear":
      case "display-p3":
      case "a98-rgb":
      case "prophoto-rgb":
      case "rec2020":
      case "xyz-d50":
      case "xyz-d65":
        vals = Couleur.convert(spaceID, "srgb", vals);
        break;
      default:
        if (spaceID.startsWith("--")) {
          const id = spaceID.substring(2);
          vals = Couleur.convert(id, "srgb", vals);
        } else
          throw `The ${JSON.stringify(spaceID)} color space is not supported`;
    }
    const rgba = [...vals, a];
    return this.set(rgba, [null, null, null], "srgb");
  }
  expr(format, { precision = 0, clamp = true } = {}) {
    const _format = format.toLowerCase();
    const spaceID = _format.replace("color-", "");
    const space = Couleur.getSpace(spaceID);
    let values = this.valuesTo(space);
    if (clamp)
      values = Couleur.toGamut(space, values, space);
    const a = Number(Couleur.unparse(this.a, "a", { precision }));
    values = [...values, a];
    if (_format.toLowerCase().slice(0, 5) === "color") {
      let string = `color(${space.id}`;
      for (const [k, v] of Object.entries(values)) {
        if (Number(k) === values.length - 1) {
          if (a >= 1)
            break;
          string += ` / ${a}`;
        } else {
          string += ` ${precision === null ? v : Math.round(__pow(10, precision) * v) / __pow(10, precision)}`;
        }
      }
      string += `)`;
      return string;
    }
    const props = Couleur.propertiesOf(_format);
    const [x, y, z] = props.map((p, k) => Couleur.unparse(values[k], p, { precision }));
    switch (_format.toLowerCase()) {
      case "rgb":
      case "rgba":
      case "hsl":
      case "hsla": {
        if (_format.length > 3 && _format.slice(-1) === "a" || a < 1)
          return `${_format}(${x}, ${y}, ${z}, ${a})`;
        else
          return `${_format}(${x}, ${y}, ${z})`;
      }
      default: {
        if (a < 1)
          return `${_format}(${x} ${y} ${z} / ${a})`;
        else
          return `${_format}(${x} ${y} ${z})`;
      }
    }
  }
  static makeExpr(format, values, valueSpaceID, options = {}) {
    const _format = format.toLowerCase();
    const spaceID = _format.replace("color-", "");
    const rgba = [...Couleur.convert(valueSpaceID, spaceID, values.slice(0, 3)), values[3]];
    return new Couleur(rgba).expr(_format, options);
  }
  get values() {
    return [this.r, this.g, this.b];
  }
  get name() {
    if (this.a === 1) {
      const allNames = Couleur.namedColors;
      const [r, g, b] = this.values;
      const tolerance = 0.02;
      for (const [name, hex] of allNames.entries()) {
        const [r2, g2, b2] = fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
        if (Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b) < tolerance)
          return name;
      }
      return null;
    } else if (this.a === 0)
      return "transparent";
    else
      return null;
  }
  get exactName() {
    if (this.a === 1) {
      const allNames = Couleur.namedColors;
      const hex6 = this.hex.slice(1);
      for (const [name, hex] of allNames.entries()) {
        if (hex === hex6)
          return name;
      }
      return null;
    } else if (this.a === 0)
      return "transparent";
    else
      return null;
  }
  get closestName() {
    if (this.a < 0.5)
      return "transparent";
    const allNames = Couleur.namedColors;
    const [r, g, b] = this.values;
    let closest = "";
    let lastDistance = Infinity;
    for (const [name, hex] of allNames.entries()) {
      const [r2, g2, b2] = fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
      const distance = Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b);
      if (distance < lastDistance) {
        lastDistance = distance;
        closest = name;
      }
    }
    return closest;
  }
  get hex() {
    const values = Couleur.toGamut("srgb", this.values);
    const rgb = toHex([...values, this.a]);
    if (this.a < 1)
      return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
    else
      return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }
  get rgb() {
    return this.expr("rgb", { precision: 2 });
  }
  get rgba() {
    return this.rgb;
  }
  get hsl() {
    return this.expr("hsl", { precision: 2 });
  }
  get hsla() {
    return this.hsl;
  }
  get hwb() {
    return this.expr("hwb", { precision: 2 });
  }
  get lab() {
    return this.expr("lab", { precision: 2 });
  }
  get lch() {
    return this.expr("lch", { precision: 2 });
  }
  get oklab() {
    return this.expr("oklab", { precision: 2 });
  }
  get oklch() {
    return this.expr("oklch", { precision: 2 });
  }
  recompute(val, prop, format) {
    const props = [...Couleur.propertiesOf(format), "a"];
    if (!props.includes(prop))
      throw `Format ${format} does not have a property called ${prop}`;
    const parsedVal = typeof val === "string" ? Couleur.parse(val, prop) : val;
    const oldValues = [...this.valuesTo(format), this.a];
    const newValues = props.map((p, k) => {
      if (p === prop)
        return parsedVal;
      else
        return oldValues[k];
    });
    this.set(newValues, props, format, { parsed: true });
  }
  set red(val) {
    this.recompute(val, "r", "rgb");
  }
  set green(val) {
    this.recompute(val, "g", "rgb");
  }
  set blue(val) {
    this.recompute(val, "b", "rgb");
  }
  set alpha(val) {
    this.recompute(val, "a", "rgb");
  }
  set opacity(val) {
    this.recompute(val, "a", "rgb");
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
  get red() {
    return this.r;
  }
  get green() {
    return this.g;
  }
  get blue() {
    return this.b;
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
    const newLum = Couleur.parse(val, "a", { clamp: true });
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
    if (this.a < 1)
      throw `The luminance of a transparent color would be meaningless`;
    return luminance2(this.values);
  }
  static convert(startSpaceID, endSpaceID, values) {
    if (typeof startSpaceID === typeof endSpaceID && startSpaceID === endSpaceID || typeof startSpaceID === "string" && typeof endSpaceID !== "string" && startSpaceID === endSpaceID.id || typeof startSpaceID !== "string" && typeof endSpaceID === "string" && startSpaceID.id === endSpaceID)
      return values;
    const startSpace = Couleur.getSpace(startSpaceID);
    const endSpace = Couleur.getSpace(endSpaceID);
    let path;
    const graph = new Graph(Couleur.colorSpaces);
    try {
      path = graph.shortestPath(startSpace.id, endSpace.id).map((node) => node.id);
    } catch (error) {
      switch (error) {
        case `Node ${startSpace.id} does not exist`:
          throw `${JSON.stringify(startSpace.id)} is not a supported color space`;
        case `Node ${endSpace.id} does not exist`:
          throw `${JSON.stringify(endSpace.id)} is not a supported color space`;
        case `No path found from ${startSpace.id} to ${endSpace.id}`:
          throw `Conversion from ${JSON.stringify(startSpace.id)} space to ${JSON.stringify(endSpace.id)} space is impossible`;
        default:
          throw error;
      }
    }
    let result = values;
    while (path.length > 1) {
      const start = path.shift();
      const end = path[0];
      const functionName = `${start}_to_${end}`.replace(/-/g, "");
      const func = conversion_exports[functionName];
      if (typeof func !== "function")
        throw `Conversion function ${functionName} does not exist`;
      result = func(result);
    }
    return result;
  }
  valuesTo(spaceID, { clamp = false } = {}) {
    const space = Couleur.getSpace(spaceID);
    let values = Couleur.convert("srgb", space, this.values);
    if (clamp)
      values = Couleur.toGamut(space, values);
    return values;
  }
  static inGamut(spaceID, values, valueSpaceID = "srgb", { tolerance = 1e-4 } = {}) {
    const space = Couleur.getSpace(spaceID);
    const convertedValues = Couleur.convert(valueSpaceID, space, values);
    return convertedValues.every((v, k) => v >= space.gamut[k][0] - tolerance && v <= space.gamut[k][1] + tolerance);
  }
  inGamut(spaceID, options = {}) {
    return Couleur.inGamut(spaceID, this.values, "srgb", options);
  }
  static toGamut(spaceID, values, valueSpaceID = "srgb", { method = "okchroma" } = {}) {
    const space = Couleur.getSpace(spaceID);
    const valueSpace = Couleur.getSpace(valueSpaceID);
    const _method = method.toLowerCase();
    if (Couleur.inGamut(space, values, valueSpace, { tolerance: 0 }))
      return values;
    let clampedValues, clampSpace;
    switch (_method) {
      case "okchroma":
        {
          clampSpace = Couleur.getSpace("oklch");
          let oklch = Couleur.convert(valueSpace, clampSpace, values);
          oklch = Couleur.toGamut(clampSpace, oklch, clampSpace, { method: "naive" });
          const \u03C4 = 1e-6;
          const \u03B4 = 0.02;
          let Cmin = 0;
          let Cmax = oklch[1];
          oklch[1] = oklch[1] / 2;
          while (Cmax - Cmin > \u03C4) {
            if (Couleur.inGamut(space, oklch, clampSpace, { tolerance: 0 })) {
              Cmin = oklch[1];
            } else {
              const naiveOklch = Couleur.toGamut(space, oklch, clampSpace, { method: "naive" });
              const naiveOklab = Couleur.convert(clampSpace, "oklab", naiveOklch);
              const oklab = Couleur.convert(clampSpace, "oklab", oklch);
              if (euclidean(naiveOklab, oklab) < \u03B4) {
                oklch = naiveOklch;
                break;
              }
              Cmax = oklch[1];
            }
            oklch[1] = (Cmin + Cmax) / 2;
          }
          clampedValues = oklch;
        }
        break;
      default: {
        clampSpace = space;
        const convertedValues = Couleur.convert(valueSpace, clampSpace, values);
        clampedValues = convertedValues.map((v, k) => Math.max(space.gamut[k][0], Math.min(v, space.gamut[k][1])));
      }
    }
    if (_method !== "naive")
      clampedValues = Couleur.toGamut(space, clampedValues, clampSpace, { method: "naive" });
    return Couleur.convert(clampSpace, valueSpace, clampedValues);
  }
  toGamut(spaceID) {
    return new Couleur([...Couleur.toGamut(spaceID, this.values, "srgb"), this.a]);
  }
  change(prop, value, { action } = {}) {
    const replace = (action == null ? void 0 : action.toLowerCase()) === "replace";
    const scale = (action == null ? void 0 : action.toLowerCase()) === "scale";
    const val = scale ? Couleur.parse(value) : Couleur.parse(value, prop, { clamp: false });
    const changedColor = new Couleur(this);
    const oldVal = this[prop];
    const newVal = replace ? val : scale ? oldVal * val : oldVal + val;
    changedColor[prop] = newVal;
    return changedColor;
  }
  replace(prop, value) {
    return this.change(prop, value, { action: "replace" });
  }
  scale(prop, value) {
    return this.change(prop, value, { action: "scale" });
  }
  complement() {
    return this.change("h", 180);
  }
  negative() {
    return new Couleur([1 - this.r, 1 - this.g, 1 - this.b, this.a]);
  }
  invert() {
    return this.negative();
  }
  greyscale() {
    const L = this.replace("a", 1).luminance;
    return new Couleur([L, L, L, this.a]);
  }
  grayscale() {
    return this.greyscale();
  }
  sepia() {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new Couleur([r, g, b, this.a]);
  }
  static blend(backgroundColor, overlayColor, alpha) {
    const background = Couleur.makeInstance(backgroundColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null)
      overlay.a = Couleur.parse(alpha, "a");
    if (overlay.a === 0)
      return background;
    else if (overlay.a === 1)
      return overlay;
    const a = overlay.a + background.a * (1 - overlay.a);
    const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
    const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
    const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
    return new Couleur([r, g, b, a]);
  }
  static blendAll(...colors) {
    if (colors.length < 2)
      throw `You need at least 2 colors to blend`;
    const background = colors.shift();
    const overlay = colors.shift();
    if (background == null || overlay == null)
      throw "Cannot blend undefined color";
    const mix = Couleur.blend(background, overlay);
    if (colors.length === 0)
      return mix;
    else
      return Couleur.blendAll(mix, ...colors);
  }
  blend(overlayColor, alpha) {
    return Couleur.blend(this, overlayColor, alpha);
  }
  blendAll(...colors) {
    return Couleur.blendAll(this, ...colors);
  }
  static unblend(mixColor, overlayColor, alpha) {
    const mix = Couleur.makeInstance(mixColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null)
      overlay.a = Couleur.parse(alpha, "a");
    if (overlay.a === 1) {
      throw `Overlay color ${JSON.stringify(overlay.rgb)} isn't transparent, so the background it was blended onto could have been any color`;
    } else if (overlay.a === 0)
      return mix;
    else {
      if (mix.a < overlay.a)
        return null;
      else if (mix.a === overlay.a) {
        if (Couleur.same(mix, overlay))
          return new Couleur([0, 0, 0, 0]);
        else
          return null;
      } else {
        const a = (mix.a - overlay.a) / (1 - overlay.a);
        const r = (mix.r * mix.a - overlay.r * overlay.a) / (a * (1 - overlay.a));
        const g = (mix.g * mix.a - overlay.g * overlay.a) / (a * (1 - overlay.a));
        const b = (mix.b * mix.a - overlay.b * overlay.a) / (a * (1 - overlay.a));
        const clampedValues = Couleur.toGamut("srgb", [r, g, b], "srgb");
        return new Couleur([...clampedValues, a]);
      }
    }
  }
  static unblendAll(...colors) {
    if (colors.length < 2)
      throw `You need at least 2 colors to unblend`;
    const mix = colors.shift();
    const overlay = colors.shift();
    if (mix == null || overlay == null)
      throw "Cannot unblend undefined color";
    const background = Couleur.unblend(mix, overlay);
    if (background == null)
      return null;
    else if (colors.length === 0)
      return background;
    else
      return Couleur.unblendAll(background, ...colors);
  }
  unblend(overlayColor, alpha) {
    return Couleur.unblend(this, overlayColor, alpha);
  }
  unblendAll(...colors) {
    return Couleur.unblendAll(this, ...colors);
  }
  static whatToBlend(backgroundColor, mixColor, alphas = [], { ignoreTransparent = false } = {}) {
    const background = Couleur.makeInstance(backgroundColor);
    const mix = Couleur.makeInstance(mixColor);
    let overlays = [];
    const calculateSolution = (a) => {
      const r = (mix.r * mix.a - background.r * background.a * (1 - a)) / a;
      const g = (mix.g * mix.a - background.g * background.a * (1 - a)) / a;
      const b = (mix.b * mix.a - background.b * background.a * (1 - a)) / a;
      if (!Couleur.inGamut("srgb", [r, g, b], "srgb", { tolerance: 1 / 255 }))
        throw `This color doesn't exist`;
      const clampedValues = Couleur.toGamut("srgb", [r, g, b], "srgb", { method: "naive" });
      return new Couleur([...clampedValues, a]);
    };
    const requestedAlphas = [alphas].flat();
    const computedAlphas = requestedAlphas.length > 0 ? requestedAlphas.filter((a) => a > 0 && a < 1) : Array.from({ length: 9 }, (v, k) => (k + 1) / 10);
    if (mix.a < background.a)
      return [];
    else if (mix.a > background.a) {
      if (mix.a === 1)
        overlays.push(mix);
      else if (background.a === 0)
        overlays.push(mix);
      else {
        const a = (mix.a - background.a) / (1 - background.a);
        try {
          overlays.push(calculateSolution(a));
        } catch (error) {
          return [];
        }
      }
    } else if (mix.a === background.a) {
      if (mix.a === 0)
        overlays.push(new Couleur("transparent"));
      else if (mix.a < 1) {
        if (Couleur.same(mix, background))
          overlays.push(new Couleur("transparent"));
        else
          return [];
      } else {
        if (Couleur.same(mix, background))
          overlays.push(new Couleur("transparent"));
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
    if (ignoreTransparent)
      result = result.filter((r) => r.a > 0);
    return result;
  }
  whatToBlend(mixColor, alphas) {
    return Couleur.whatToBlend(this, mixColor, alphas);
  }
  static contrast(textColor, backgroundColor, { method = "apca" } = {}) {
    const background = Couleur.makeInstance(backgroundColor);
    if (background.a < 1)
      throw `The contrast with a transparent background color would be meaningless`;
    let text = Couleur.makeInstance(textColor);
    if (text.a < 1)
      text = Couleur.blend(background, text);
    switch (method.toLowerCase()) {
      case "apca":
        return APCAcontrast(text.values, background.values);
      case "wcag2":
      default:
        return WCAG2(text.values, background.values);
    }
  }
  contrast(backgroundColor, options = {}) {
    return Couleur.contrast(this, backgroundColor, options);
  }
  bestColorScheme(as = "background") {
    const rgba = [...this.toGamut("srgb").values, this.a];
    switch (as) {
      case "text": {
        const Cblack = Math.abs(Couleur.contrast(rgba, "black", { method: "apca" }));
        const Cwhite = Math.abs(Couleur.contrast(rgba, "white", { method: "apca" }));
        return Cblack >= Cwhite ? "dark" : "light";
      }
      case "background": {
        const Cblack = Math.abs(Couleur.contrast("black", rgba, { method: "apca" }));
        const Cwhite = Math.abs(Couleur.contrast("white", rgba, { method: "apca" }));
        return Cblack >= Cwhite ? "light" : "dark";
      }
    }
  }
  improveContrast(referenceColor, desiredContrast, { as = "text", lower = false, colorScheme, method = "apca" } = {}) {
    const background = as === "text" ? Couleur.makeInstance(referenceColor) : this;
    const text = as === "text" ? this : Couleur.makeInstance(referenceColor);
    const backgroundLab = background.valuesTo("oklab");
    const textLab = text.valuesTo("oklab");
    const movingLab = as === "text" ? textLab : backgroundLab;
    const startContrast = Math.abs(Couleur.contrast(text, background, { method }));
    let directionContrast;
    if (startContrast > desiredContrast)
      directionContrast = -1;
    else if (startContrast < desiredContrast)
      directionContrast = 1;
    else
      directionContrast = 0;
    if (directionContrast < 0 && lower === false || directionContrast === 0)
      return this;
    const _colorScheme = colorScheme || (backgroundLab[0] < textLab[0] ? "dark" : "light");
    const cBlack = Math.abs(as === "text" ? Couleur.contrast(background, "black", { method }) : Couleur.contrast("black", text, { method }));
    const cWhite = Math.abs(as === "text" ? Couleur.contrast(background, "white", { method }) : Couleur.contrast("white", text, { method }));
    const isPossible = {
      lowering: directionContrast > 0 ? cBlack >= desiredContrast : cBlack <= desiredContrast,
      raising: directionContrast > 0 ? cWhite >= desiredContrast : cWhite <= desiredContrast
    };
    let directionOKL;
    if (isPossible.lowering && !isPossible.raising)
      directionOKL = -1;
    else if (isPossible.raising && !isPossible.lowering)
      directionOKL = 1;
    else if (!isPossible.raising && !isPossible.lowering) {
      if (as === "text") {
        if (_colorScheme === "light")
          return new Couleur("black");
        else
          return new Couleur("white");
      } else {
        if (_colorScheme === "light")
          return new Couleur("white");
        else
          return new Couleur("black");
      }
    } else {
      if (_colorScheme === "light" && directionContrast > 0)
        directionOKL = -1;
      else if (_colorScheme === "light" && directionContrast < 0)
        directionOKL = 1;
      else if (_colorScheme === "dark" && directionContrast > 0)
        directionOKL = 1;
      else
        directionOKL = -1;
      if (as === "background")
        directionOKL = -directionOKL;
    }
    const \u03C4 = 1e-4;
    let OKLmin = directionOKL > 0 ? movingLab[0] : 0;
    let OKLmax = directionOKL > 0 ? 1 : movingLab[0];
    while (OKLmax - OKLmin > \u03C4) {
      const okl = (OKLmin + OKLmax) / 2;
      const newValues = movingLab;
      newValues[0] = okl;
      const newContrast = Math.abs(as === "text" ? Couleur.contrast(Couleur.convert("oklab", "srgb", newValues), background, { method }) : Couleur.contrast(text, Couleur.convert("oklab", "srgb", newValues), { method }));
      const condition = directionContrast > 0 ? newContrast < desiredContrast : newContrast > desiredContrast;
      if (condition) {
        if (directionOKL > 0)
          OKLmin = okl;
        else
          OKLmax = okl;
      } else {
        if (directionOKL > 0)
          OKLmax = okl;
        else
          OKLmin = okl;
      }
      movingLab[0] = okl;
    }
    let result = new Couleur(Couleur.convert("oklab", "srgb", movingLab));
    const lastContrast = Math.abs(as === "text" ? Couleur.contrast(result, background, { method }) : Couleur.contrast(text, result, { method }));
    if (lastContrast < desiredContrast) {
      if (as === "text") {
        if (_colorScheme === "light")
          movingLab[0] = OKLmin;
        else
          movingLab[0] = OKLmax;
      } else {
        if (_colorScheme === "light")
          movingLab[0] = OKLmax;
        else
          movingLab[0] = OKLmin;
      }
    }
    return new Couleur(Couleur.convert("oklab", "srgb", movingLab));
  }
  static distance(color1, color2, { method = "deltae2000", alpha = true } = {}) {
    const colore1 = Couleur.makeInstance(color1);
    const colore2 = Couleur.makeInstance(color2);
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
      default: {
        const [rgb1, rgb2] = [colore1, colore2].map((c) => c.values);
        opaqueDist = euclidean(rgb1, rgb2);
      }
    }
    const alphaDist = alpha ? euclidean([colore1.a], [colore2.a]) : 0;
    return opaqueDist + alphaCoeff * alphaDist;
  }
  distance(color, options = {}) {
    return Couleur.distance(this, color, options);
  }
  static same(color1, color2, { tolerance = 1, method = "deltae2000" } = {}) {
    if (Couleur.distance(color1, color2, { method }) > tolerance)
      return false;
    else
      return true;
  }
  same(color, options = {}) {
    return Couleur.same(this, color, options);
  }
  static interpolate(color1, color2, steps, spaceID, { hueInterpolationMethod = "shorter", premultiplyAlpha = true } = {}) {
    const start = Couleur.makeInstance(color1);
    const end = Couleur.makeInstance(color2);
    const _steps = Math.max(0, steps);
    const space = Couleur.getSpace(spaceID);
    const props = Couleur.propertiesOf(space.id);
    let startValues = start.valuesTo(space);
    let endValues = end.valuesTo(space);
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
    if (premultiplyAlpha) {
      startValues = premultiply(startValues, start.a);
      endValues = premultiply(endValues, end.a);
    }
    const stepList = props.map((prop, k) => {
      switch (prop) {
        case "h":
        case "cieh":
        case "okh": {
          const diff = endValues[k] - startValues[k];
          switch (hueInterpolationMethod) {
            case "shorter":
              if (diff > 180)
                startValues[k] += 360;
              else if (diff < -180)
                endValues[k] += 360;
              break;
            case "longer":
              if (0 < diff && diff < 180)
                startValues[k] += 360;
              else if (-180 < diff && diff < 0)
                endValues[k] += 360;
              break;
            case "increasing":
              if (diff < 0)
                endValues[k] += 360;
              break;
            case "decreasing":
              if (0 < diff)
                startValues[k] += 360;
              break;
          }
        }
        default:
          return (endValues[k] - startValues[k]) / (_steps + 1);
      }
    });
    const stepAlpha = (end.a - start.a) / (_steps + 1);
    let intermediateColors = [startValues];
    for (let i = 1; i <= _steps; i++) {
      const previous = intermediateColors[i - 1];
      let next = props.map((prop, k) => {
        const v = previous[k] + stepList[k];
        switch (prop) {
          case "h":
          case "cieh":
          case "okh":
            return angleToRange(v);
          default:
            return v;
        }
      });
      intermediateColors.push(next);
    }
    intermediateColors.push(endValues);
    const undoPremultiply = (values, stepIndex) => values.map((v, k) => {
      const a = start.a + stepIndex * stepAlpha;
      switch (props[k]) {
        case "h":
        case "cieh":
        case "okh":
          return v;
        default:
          return v / a;
      }
    });
    if (premultiplyAlpha) {
      intermediateColors = intermediateColors.map((values, k) => undoPremultiply(values, k));
    }
    return intermediateColors.map((values, k) => new Couleur([
      ...Couleur.convert(space, "srgb", values),
      start.a + k * stepAlpha
    ]));
  }
  interpolate(color, steps, spaceID, options = {}) {
    return Couleur.interpolate(this, color, steps, spaceID, options);
  }
  static propertiesOf(format) {
    switch (format.toLowerCase()) {
      case "srgb":
      case "rgb":
      case "rgba":
        return ["r", "g", "b"];
      case "hsl":
      case "hsla":
        return ["h", "s", "l"];
      case "hwb":
        return ["h", "w", "bk"];
      case "lab":
        return ["ciel", "ciea", "cieb"];
      case "lch":
        return ["ciel", "ciec", "cieh"];
      case "oklab":
        return ["okl", "oka", "okb"];
      case "oklch":
        return ["okl", "okc", "okh"];
      default:
        return [];
    }
  }
  static get properties() {
    return ["a", "r", "g", "b", "h", "s", "l", "w", "bk", "ciel", "ciea", "cieb", "ciec", "cieh", "okl", "oka", "okb", "okc", "okh"];
  }
  static getSpace(spaceID) {
    let result;
    if (typeof spaceID !== "string")
      result = spaceID;
    else {
      let id = spaceID.toLowerCase();
      switch (id) {
        case "rgb":
        case "rgba":
          id = "srgb";
          break;
        case "hsla":
          id = "hsl";
          break;
        case "xyz":
          id = "xyz-d65";
          break;
      }
      result = Couleur.colorSpaces.find((sp) => sp.id == id);
    }
    if (result == null)
      throw `${spaceID} is not a supported color space`;
    return result;
  }
  static get colorSpaces() {
    return color_spaces_default;
  }
  static get formats() {
    return Formats;
  }
  static get namedColors() {
    return named_colors_default;
  }
};
export {
  css_formats_exports as CSSFormats,
  color_spaces_default as ColorSpaces,
  contrasts_exports as Contrasts,
  conversion_exports as Conversions,
  distances_exports as Distances,
  Graph,
  utils_exports as Utils,
  Couleur as default,
  named_colors_default as namedColors
};
