/************************************************************************************************
 * Derived from https://www.w3.org/TR/css-color-4/#rgb-to-lab                                   *
 *            & https://drafts.csswg.org/css-color/#predefined-to-predefined                    *
 *            & https://github.com/w3c/csswg-drafts/blob/main/css-color-4/utilities.js          *
 *            & https://github.com/w3c/csswg-drafts/blob/main/css-color-4/conversions.js        *
 * under W3C License (https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document) *
 ************************************************************************************************/



/* srgb */

export function srgb_to_lin_srgb(rgb: number[]): number[] {
  return rgb.map(x => (Math.abs(x) < 0.04045) ? x / 12.92 : (Math.sign(x) || 1) * Math.pow((Math.abs(x) + 0.055) / 1.055, 2.4));
}

export function lin_srgb_to_srgb(rgb: number[]): number[] {
  return rgb.map(x => (Math.abs(x) > 0.0031308) ? (Math.sign(x) || 1) * (1.055 * Math.pow(Math.abs(x), 1 / 2.4) - 0.055) : 12.92 * x);
}

export function lin_srgb_to_d65xyz(rgb: number[]): number[] {
  const [r, g, b] = rgb;
  return [
    0.41239079926595934 * r + 0.357584339383878 * g + 0.1804807884018343 * b,
    0.21263900587151027 * r + 0.715168678767756 * g + 0.07219231536073371 * b,
    0.01933081871559182 * r + 0.11919477979462598 * g + 0.9505321522496607 * b
  ];
}

export function d65xyz_to_lin_srgb(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    3.2409699419045226 * x + -1.537383177570094 * y + -0.4986107602930034 * z,
    -0.9692436362808796 * x + 1.8759675015077202 * y + 0.04155505740717559 * z,
    0.05563007969699366 * x + -0.20397695888897652 * y + 1.0569715142428786 * z
  ];
}



/* display-p3 */

export function displayp3_to_lin_displayp3(rgb: number[]): number[] { return srgb_to_lin_srgb(rgb); }
export function lin_displayp3_to_displayp3(rgb: number[]): number[] { return lin_srgb_to_srgb(rgb); }

export function lin_displayp3_to_d65xyz(rgb: number[]): number[] {
  const [r, g, b] = rgb;
  return [
    0.4865709486482162 * r + 0.26566769316909306 * g + 0.1982172852343625 * b,
    0.2289745640697488 * r + 0.6917385218365064 * g + 0.079286914093745 * b,
    0.0000000000000000 * r + 0.04511338185890264 * g + 1.043944368900976 * b
  ];
}

export function d65xyz_to_lin_displayp3(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    2.493496911941425 * x + -0.9313836179191239 * y + -0.40271078445071684 * z,
    -0.8294889695615747 * x + 1.7626640603183463 * y +  0.023624685841943577 * z,
    0.03584583024378447 * x + -0.07617238926804182 * y + 0.9568845240076872 * z
  ];
}



/* prophoto-rgb */

export function prophotorgb_to_lin_prophotorgb(rgb: number[]): number[] {
  return rgb.map(v => Math.abs(v) <= 16/512 ? v / 16 : (Math.sign(v) || 1) * Math.pow(v, 1.8));
}

export function lin_prophotorgb_to_prophotorgb(rgb: number[]): number[] {
  return rgb.map(v => Math.abs(v) >= 1/512 ? (Math.sign(v) || 1) * Math.pow(Math.abs(v), 1/1.8) : 16 * v);
}

export function lin_prophotorgb_to_xyz(rgb: number[]): number[] {
  const [r, g, b] = rgb;
  return [
    0.7977604896723027 * r + 0.13518583717574031 * g + 0.0313493495815248 * b,
    0.2880711282292934 * r + 0.7118432178101014 * g + 0.00008565396060525902 * b,
    0.0 * r + 0.0 * g + 0.8251046025104601 * b
  ];
}

export function xyz_to_lin_prophotorgb(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    1.3457989731028281 * x + -0.25558010007997534 * y + -0.05110628506753401 * z,
    -0.5446224939028347 * x + 1.5082327413132781 * y + 0.02053603239147973 * z,
    0.0 * x + 0.0 * y + 1.2119675456389454 * z
  ];
}



/* a98-rgb */

export function a98rgb_to_lin_a98rgb(rgb: number[]): number[] {
  return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 563/256));
}

export function lin_a98rgb_to_a98rgb(rgb: number[]): number[] {
  return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 256/563));
}

export function lin_a98rgb_to_d65xyz(rgb: number[]): number[] {
  const [r, g, b] = rgb;
  return [
    0.5766690429101305 * r + 0.1855582379065463 * g + 0.1882286462349947 * b,
    0.29734497525053605 * r + 0.6273635662554661 * g + 0.07529145849399788 * b,
    0.02703136138641234 * r + 0.07068885253582723 * g + 0.9913375368376388 * b
  ];
}

export function d65xyz_to_lin_a98rgb(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    2.0415879038107465 * x + -0.5650069742788596 * y + -0.34473135077832956 * z,
    -0.9692436362808795 * x + 1.8759675015077202 * y + 0.04155505740717557 * z,
    0.013444280632031142 * x + -0.11836239223101838 * y + 1.0151749943912054 * z
  ];
}



/* rec2020 */

export function rec2020_to_lin_rec2020(rgb: number[]): number[] {
  const e = 1.09929682680944;
  return rgb.map(v => Math.abs(v) < 0.018053968510807 * 4.5 ? v / 4.5 : (Math.sign(v) || 1) * Math.pow(Math.abs(v) + e - 1, 1/0.45));
}

export function lin_rec2020_to_rec2020(rgb: number[]): number[] {
  const e = 1.09929682680944;
  return rgb.map(v => Math.abs(v) > 0.018053968510807 ? (Math.sign(v) || 1) * (e * Math.pow(Math.abs(v), 0.45) - (e - 1)) : 4.5 * v);
}

export function lin_rec2020_to_d65xyz(rgb: number[]): number[] {
  const [r, g, b] = rgb;
  return [
    0.6369580483012914 * r + 0.14461690358620832 * g + 0.1688809751641721 * b,
    0.2627002120112671 * r + 0.6779980715188708 * g + 0.05930171646986196 * b,
    0.000000000000000 * r + 0.028072693049087428 * g + 1.060985057710791 * b
  ];
}

export function d65xyz_to_lin_rec2020(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    1.7166511879712674 * x + -0.35567078377639233 * y + -0.25336628137365974 * z,
    -0.6666843518324892 * x + 1.6164812366349395 * y + 0.01576854581391113 * z,
    0.017639857445310783 * x + -0.042770613257808524 * y + 0.9421031212354738 * z
  ];
}



/* lab */

export function xyz_to_lab(xyz: number[]): number[] {
  const ε = 216/24389;
  const κ = 24389/27;
  const w = [0.96422, 1, 0.82521];

  const [x, y, z] = xyz.map((v, k) => v / w[k]);
  const f = (x: number) => (x > ε) ? Math.cbrt(x) : (κ * x + 16) / 116;
  const [f0, f1, f2] = [x, y, z].map(v => f(v));
  return [
    (116 * f1 - 16) / 100,
    500 * (f0 - f1),
    200 * (f1 - f2)
  ];
}

export function lab_to_xyz(lab: number[]): number[] {
  const ε = 216/24389;
  const κ = 24389/27;
  const w = [0.96422, 1, 0.82521];

  let [ciel, ciea, cieb] = lab;
  ciel = 100 * ciel;
  const f1 = (ciel + 16) / 116;
  const f0 = ciea / 500 + f1;
  const f2 = f1 - cieb / 200;

  const x = (f0 ** 3 > ε) ? f0 ** 3 : (116 * f0 - 16) / κ;
  const y = (ciel > κ * ε) ? ((ciel + 16) / 116) ** 3 : ciel / κ;
  const z = (f2 ** 3 > ε) ? f2 ** 3 : (116 * f2 - 16) / κ;
  return [x, y, z].map((v, k) => v * w[k]);
}

export function lab_to_lch(lab: number[]): number[] {
  const [ciel, ciea, cieb] = lab;
  const ciec = Math.sqrt(ciea ** 2 + cieb ** 2);
  let cieh = Math.atan2(cieb, ciea) * 180 / Math.PI;
  while (cieh < 0)   cieh += 360;
  while (cieh > 360) cieh -= 360;

  return [ciel, ciec, cieh];
}

export function lch_to_lab(lch: number[]): number[] {
  const [ciel, ciec, cieh] = lch;
  const ciea = ciec * Math.cos(cieh * Math.PI / 180);
  const cieb = ciec * Math.sin(cieh * Math.PI / 180);

  return [ciel, ciea, cieb];
}



/* Bradford transform */

export function d65xyz_to_xyz(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    1.0479298208405488 * x + 0.022946793341019088 * y + -0.05019222954313557 * z,
    0.029627815688159344 * x + 0.990434484573249 * y + -0.01707382502938514 * z,
    -0.009243058152591178 * x + 0.015055144896577895 * y + 0.7518742899580008 * z
  ];
}

export function xyz_to_d65xyz(xyz: number[]): number[] {
  const [x, y, z] = xyz;
  return [
    0.9554734527042182 * x + -0.023098536874261423 * y + 0.0632593086610217 * z,
    -0.028369706963208136 * x + 1.0099954580058226 * y + 0.021041398966943008 * z,
    0.012314001688319899 * x + -0.020507696433477912 * y + 1.3303659366080753 * z
  ];
}