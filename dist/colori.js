/** Pads a string of length 1 with a zero. */
function pad(s) { return (s.length < 2) ? `0${s}` : s; }

/** Brings an angle in degrees to [0, 360]. */
function angleToRange(angle) {
  let h = angle;
  while (h < 0)   h += 360;
  while (h > 360) h -= 360;
  return h;
}

// All of these functions take an array of parsed color values (without alpha),
// and return an array of parsed color values (without alpha) converted into the 
// desired color space or CSS format.

// Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
//                   & https://drafts.csswg.org/css-color/#predefined-to-predefined
//                   & https://github.com/w3c/csswg-drafts/blob/main/css-color-4/utilities.js
//                   & https://github.com/w3c/csswg-drafts/blob/main/css-color-4/conversions.js


/* srgb */

function srgb_to_lin_srgb(rgb) {
  return rgb.map(x => (Math.abs(x) < 0.04045) ? x / 12.92 : (Math.sign(x) || 1) * Math.pow((Math.abs(x) + 0.055) / 1.055, 2.4));
}

function lin_srgb_to_srgb(rgb) {
  return rgb.map(x => (Math.abs(x) > 0.0031308) ? (Math.sign(x) || 1) * (1.055 * Math.pow(Math.abs(x), 1 / 2.4) - 0.055) : 12.92 * x);
}

function lin_srgb_to_d65xyz(rgb) {
  const [r, g, b] = rgb;
  return [
    0.41239079926595934 * r + 0.357584339383878 * g + 0.1804807884018343 * b,
    0.21263900587151027 * r + 0.715168678767756 * g + 0.07219231536073371 * b,
    0.01933081871559182 * r + 0.11919477979462598 * g + 0.9505321522496607 * b
  ];
}

function d65xyz_to_lin_srgb(xyz) {
  const [x, y, z] = xyz;
  return [
    3.2409699419045226 * x + -1.537383177570094 * y + -0.4986107602930034 * z,
    -0.9692436362808796 * x + 1.8759675015077202 * y + 0.04155505740717559 * z,
    0.05563007969699366 * x + -0.20397695888897652 * y + 1.0569715142428786 * z
  ];
}



/* hsl */

function srgb_to_hsl(rgb) {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach
  const [r, g, b] = rgb; // all in [0, 1]

  const max = Math.max(r, g, b);
  const min = Math.min(r, g, b);
  const chroma = max - min;

  const l = (max + min) / 2;

  let h;
  if (chroma === 0) h = 0;
  else switch (max) {
    case r: h = (g - b) / chroma; break;
    case g: h = (b - r) / chroma + 2; break;
    case b: h = (r - g) / chroma + 4; break;
  }
  h = 60 * h;
  while (h < 0)   h += 360;
  while (h > 360) h -= 360;

  let s;
  if (l === 0 || l === 1) s = 0;
  else if (l <= 0.5)      s = chroma / (2 * l);
  else                    s = chroma / (2 - 2 * l);

  return [h, s, l]; // h in [0, 360], s & l in [0, 1]
}

function hsl_to_srgb(hsl) {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
  const [h, s, l] = hsl; // h in [0, 360], s & l in [0, 1]

  const m = s * Math.min(l, 1 - l);
  const k = n => (n + h / 30) % 12;
  const f = n => l - m * Math.max(Math.min(k(n) - 3, 9 - k(n), 1), -1);

  const r = f(0);
  const g = f(8);
  const b = f(4);

  return [r, g, b]; // all in [0, 1]
}



/* hwb */

function hsl_to_hwb(hsl) {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
  //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
  const [h, s, l] = hsl; // h in [0, 360], s & l in [0, 1]

  let _s;
  const v = l + s * Math.min(l, 1 - l);
  if (v === 0) _s = 0;
  else         _s = 2 - 2 * l / v;

  const w = (1 - _s) * v;
  const bk = 1 - v;

  return [h, w, bk]; // h in [0, 360], w & bk in [0, 1]
}

function hwb_to_hsl(hwb) {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
  //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
  const [h, w, bk] = hwb; // h in [0, 360], w & bk in [0, 1]

  let _w = w, _bk = bk;
  if (w + bk > 1) {
    _w = w / (w + bk);
    _bk = bk / (w + bk);
  }

  let _s;
  const v = 1 - _bk;
  if (_bk === 1) _s = 0;
  else           _s = 1 - _w / v;

  let s;
  const l = v - v * _s / 2;
  if (l === 0 || l === 1) s = 0;
  else                    s = (v - l) / Math.min(l, 1 - l);

  return [h, s, l]; // h in [0, 360], s & l in [0, 1]
}



/* display-p3 */

function displayp3_to_lin_displayp3(rgb) { return srgb_to_lin_srgb(rgb); }
function lin_displayp3_to_displayp3(rgb) { return lin_srgb_to_srgb(rgb); }

function lin_displayp3_to_d65xyz(rgb) {
  const [r, g, b] = rgb;
  return [
    0.4865709486482162 * r + 0.26566769316909306 * g + 0.1982172852343625 * b,
    0.2289745640697488 * r + 0.6917385218365064 * g + 0.079286914093745 * b,
    0.0000000000000000 * r + 0.04511338185890264 * g + 1.043944368900976 * b
  ];
}

function d65xyz_to_lin_displayp3(xyz) {
  const [x, y, z] = xyz;
  return [
    2.493496911941425 * x + -0.9313836179191239 * y + -0.40271078445071684 * z,
    -0.8294889695615747 * x + 1.7626640603183463 * y +  0.023624685841943577 * z,
    0.03584583024378447 * x + -0.07617238926804182 * y + 0.9568845240076872 * z
  ];
}



/* prophoto-rgb */

function prophotorgb_to_lin_prophotorgb(rgb) {
  return rgb.map(v => Math.abs(v) <= 16/512 ? v / 16 : (Math.sign(v) || 1) * Math.pow(v, 1.8));
}

function lin_prophotorgb_to_prophotorgb(rgb) {
  return rgb.map(v => Math.abs(v) >= 1/512 ? (Math.sign(v) || 1) * Math.pow(Math.abs(v), 1/1.8) : 16 * v);
}

function lin_prophotorgb_to_xyz(rgb) {
  const [r, g, b] = rgb;
  return [
    0.7977604896723027 * r + 0.13518583717574031 * g + 0.0313493495815248 * b,
    0.2880711282292934 * r + 0.7118432178101014 * g + 0.00008565396060525902 * b,
    0.0 * r + 0.0 * g + 0.8251046025104601 * b
  ];
}

function xyz_to_lin_prophotorgb(xyz) {
  const [x, y, z] = xyz;
  return [
    1.3457989731028281 * x + -0.25558010007997534 * y + -0.05110628506753401 * z,
    -0.5446224939028347 * x + 1.5082327413132781 * y + 0.02053603239147973 * z,
    0.0 * x + 0.0 * y + 1.2119675456389454 * z
  ];
}



/* a98-rgb */

function a98rgb_to_lin_a98rgb(rgb) {
  return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 563/256));
}

function lin_a98rgb_to_a98rgb(rgb) {
  return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 256/563));
}

function lin_a98rgb_to_d65xyz(rgb) {
  const [r, g, b] = rgb;
  return [
    0.5766690429101305 * r + 0.1855582379065463 * g + 0.1882286462349947 * b,
    0.29734497525053605 * r + 0.6273635662554661 * g + 0.07529145849399788 * b,
    0.02703136138641234 * r + 0.07068885253582723 * g + 0.9913375368376388 * b
  ];
}

function d65xyz_to_lin_a98rgb(xyz) {
  const [x, y, z] = xyz;
  return [
    2.0415879038107465 * x + -0.5650069742788596 * y + -0.34473135077832956 * z,
    -0.9692436362808795 * x + 1.8759675015077202 * y + 0.04155505740717557 * z,
    0.013444280632031142 * x + -0.11836239223101838 * y + 1.0151749943912054 * z
  ];
}



/* rec2020 */

function rec2020_to_lin_rec2020(rgb) {
  const e = 1.09929682680944;
  return rgb.map(v => Math.abs(v) < 0.018053968510807 * 4.5 ? v / 4.5 : (Math.sign(v) || 1) * Math.pow(Math.abs(v) + e - 1, 1/0.45));
}

function lin_rec2020_to_rec2020(rgb) {
  const e = 1.09929682680944;
  return rgb.map(v => Math.abs(v) > 0.018053968510807 ? (Math.sign(v) || 1) * (e * Math.pow(Math.abs(v), 0.45) - (e - 1)) : 4.5 * v);
}

function lin_rec2020_to_d65xyz(rgb) {
  const [r, g, b] = rgb;
  return [
    0.6369580483012914 * r + 0.14461690358620832 * g + 0.1688809751641721 * b,
    0.2627002120112671 * r + 0.6779980715188708 * g + 0.05930171646986196 * b,
    0.000000000000000 * r + 0.028072693049087428 * g + 1.060985057710791 * b
  ];
}

function d65xyz_to_lin_rec2020(xyz) {
  const [x, y, z] = xyz;
  return [
    1.7166511879712674 * x + -0.35567078377639233 * y + -0.25336628137365974 * z,
    -0.6666843518324892 * x + 1.6164812366349395 * y + 0.01576854581391113 * z,
    0.017639857445310783 * x + -0.042770613257808524 * y + 0.9421031212354738 * z
  ];
}



/* lab */

function xyz_to_lab(xyz) {
  const ε = 216/24389;
  const κ = 24389/27;
  const w = [0.96422, 1, 0.82521];

  const [x, y, z] = xyz.map((v, k) => v / w[k]);
  const f = x => (x > ε) ? Math.cbrt(x) : (κ * x + 16) / 116;
  const [f0, f1, f2] = [x, y, z].map(v => f(v));
  return [
    (116 * f1 - 16) / 100,
    500 * (f0 - f1),
    200 * (f1 - f2)
  ];
}

function lab_to_xyz(lab) {
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

function lab_to_lch(lab) {
  const [ciel, ciea, cieb] = lab;
  const ciec = Math.sqrt(ciea ** 2 + cieb ** 2);
  let cieh = Math.atan2(cieb, ciea) * 180 / Math.PI;
  while (cieh < 0)   cieh += 360;
  while (cieh > 360) cieh -= 360;

  return [ciel, ciec, cieh];
}

function lch_to_lab(lch) {
  const [ciel, ciec, cieh] = lch;
  const ciea = ciec * Math.cos(cieh * Math.PI / 180);
  const cieb = ciec * Math.sin(cieh * Math.PI / 180);

  return [ciel, ciea, cieb];
}



/* oklab */
// Source of the math: https://bottosson.github.io/posts/gamutclipping/

function lin_srgb_to_oklab(rgb) {
  const [r, g, b] = rgb;

  let l = 0.4122214708 * r + 0.5363325363 * g + 0.0514459929 * b;
  let m = 0.2119034982 * r + 0.6806995451 * g + 0.1073969566 * b;
  let s = 0.0883024619 * r + 0.2817188376 * g + 0.6299787005 * b;
  l = Math.cbrt(l);
  m = Math.cbrt(m);
  s = Math.cbrt(s);
  
  const okl = 0.2104542553 * l + 0.7936177850 * m + -0.0040720468 * s;
  const oka = 1.9779984951 * l + -2.4285922050 * m + 0.4505937099 * s;
  const okb = 0.0259040371 * l + 0.7827717662 * m + -0.8086757660 * s;

  return [okl, oka, okb];
}

function oklab_to_lin_srgb(lab) {
  const [okl, oka, okb] = lab;

  let l = okl + 0.3963377774 * oka + 0.2158037573 * okb;
  let m = okl + -0.1055613458 * oka + -0.0638541728 * okb;
  let s = okl + -0.0894841775 * oka + -1.2914855480 * okb;
  l = l**3;
  m = m**3;
  s = s**3;

  const r = 4.0767416621 * l + -3.3077115913 * m + 0.2309699292 * s;
  const g = -1.2684380046 * l + 2.6097574011 * m + -0.3413193965 * s;
  const b = -0.0041960863 * l + -0.7034186147 * m + 1.7076147010 * s;

  return [r, g, b];
}

function oklab_to_oklch(lab) { return lab_to_lch(lab); }
function oklch_to_oklab(lch) { return lch_to_lab(lch); }



/* Bradford transform */

function d65xyz_to_xyz(xyz) {
  const [x, y, z] = xyz;
  return [
    1.0479298208405488 * x + 0.022946793341019088 * y + -0.05019222954313557 * z,
    0.029627815688159344 * x + 0.990434484573249 * y + -0.01707382502938514 * z,
    -0.009243058152591178 * x + 0.015055144896577895 * y + 0.7518742899580008 * z
  ];
}

function xyz_to_d65xyz(xyz) {
  const [x, y, z] = xyz;
  return [
    0.9554734527042182 * x + -0.023098536874261423 * y + 0.0632593086610217 * z,
    -0.028369706963208136 * x + 1.0099954580058226 * y + 0.021041398966943008 * z,
    0.012314001688319899 * x + -0.020507696433477912 * y + 1.3303659366080753 * z
  ];
}

const Conversions = /*#__PURE__*/Object.freeze({
  __proto__: null,
  srgb_to_lin_srgb: srgb_to_lin_srgb,
  lin_srgb_to_srgb: lin_srgb_to_srgb,
  lin_srgb_to_d65xyz: lin_srgb_to_d65xyz,
  d65xyz_to_lin_srgb: d65xyz_to_lin_srgb,
  srgb_to_hsl: srgb_to_hsl,
  hsl_to_srgb: hsl_to_srgb,
  hsl_to_hwb: hsl_to_hwb,
  hwb_to_hsl: hwb_to_hsl,
  displayp3_to_lin_displayp3: displayp3_to_lin_displayp3,
  lin_displayp3_to_displayp3: lin_displayp3_to_displayp3,
  lin_displayp3_to_d65xyz: lin_displayp3_to_d65xyz,
  d65xyz_to_lin_displayp3: d65xyz_to_lin_displayp3,
  prophotorgb_to_lin_prophotorgb: prophotorgb_to_lin_prophotorgb,
  lin_prophotorgb_to_prophotorgb: lin_prophotorgb_to_prophotorgb,
  lin_prophotorgb_to_xyz: lin_prophotorgb_to_xyz,
  xyz_to_lin_prophotorgb: xyz_to_lin_prophotorgb,
  a98rgb_to_lin_a98rgb: a98rgb_to_lin_a98rgb,
  lin_a98rgb_to_a98rgb: lin_a98rgb_to_a98rgb,
  lin_a98rgb_to_d65xyz: lin_a98rgb_to_d65xyz,
  d65xyz_to_lin_a98rgb: d65xyz_to_lin_a98rgb,
  rec2020_to_lin_rec2020: rec2020_to_lin_rec2020,
  lin_rec2020_to_rec2020: lin_rec2020_to_rec2020,
  lin_rec2020_to_d65xyz: lin_rec2020_to_d65xyz,
  d65xyz_to_lin_rec2020: d65xyz_to_lin_rec2020,
  xyz_to_lab: xyz_to_lab,
  lab_to_xyz: lab_to_xyz,
  lab_to_lch: lab_to_lch,
  lch_to_lab: lch_to_lab,
  lin_srgb_to_oklab: lin_srgb_to_oklab,
  oklab_to_lin_srgb: oklab_to_lin_srgb,
  oklab_to_oklch: oklab_to_oklch,
  oklch_to_oklab: oklch_to_oklab,
  d65xyz_to_xyz: d65xyz_to_xyz,
  xyz_to_d65xyz: xyz_to_d65xyz
});

/** Graph node in a graph that will be traversed by a path finding algorithm. */
class GraphNode {
  /**
   * Builds a graph node from an object.
   * @param {object} object - An { id, links } object.
   */
  constructor(object) {
    this.id = object.id;
    this.links = object.links;
    this.visited = false;
    this.predecessor = null;
  }

  visit(mark = true) { this.visited = mark; }
  unvisit() { this.visited = false; }
  follow(node) { this.predecessor = node; }
  unfollow() { this.predecessor = null; }
}



/** Graph that will be traversed by a path finding algorithm. */
class Graph {
  /**
   * Builds a graph from an array.
   * @param {object[]} array - Array of { id, links } objects.
   */
  constructor(array) {
    this.nodes = array.map(e => new GraphNode(e));
  }

  /**
   * Finds a node.
   * @param {string} id - Identifier of the desired node.
   * @returns {GraphNode} The corresponding node.
   */
  getNode(id) {
    const node = this.nodes.find(node => node.id === id);
    if (typeof node === 'undefined') throw `Node ${JSON.stringify(id)} does not exist`;
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
   * @param {string} startID - Identifier of the first node.
   * @param {string} endID - Identifier of the last node.
   * @returns {GraphNode[]} An array of node IDs, ordered from first to last along the shortest path.
   */
  shortestPath(startID, endID) {
    // Source of the math: https://en.wikipedia.org/wiki/Breadth-first_search  
    if (startID === endID) return [];
  
    const start = this.getNode(startID);
    const end = this.getNode(endID);

    const queue = [start];
    start.visit();
    
    // Let's build a breadth-first tree until we find the destination.
    let found = false;
    walk: while (queue.length > 0) {
      const current = queue.shift();
      if (current.id === end.id) {
        found = true;
        break walk;
      }
  
      for (const neighbourID of current.links) {
        const neighbour = this.getNode(neighbourID);
        if (neighbour.visited === false) {
          neighbour.visit();
          neighbour.follow(current);
          queue.push(neighbour);
        }
      }
    }
  
    if (!found) throw `No path found from ${JSON.stringify(start.id)} to ${JSON.stringify(end.id)}`;
  
    // Let's backtrack through the tree to find the path.
    const path = [end];
    let current = end;
    while (current.predecessor != null) {
      path.push(current.predecessor);
      current = current.predecessor;
    }

    this.cleanUp();
    return path.reverse();
  }

  /**
   * Lists the graph nodes in a topological order.
   * @returns {GraphNode[]} The array of ordered graph nodes.
   */
  topologicalOrder() {
    // Source of the math: https://en.wikipedia.org/wiki/Topological_sorting#Depth-first_search
    const orderedList = [];
    const unvisitedNodes = [...this.nodes];

    const visit = node => {
      if (node.visited === true) return;
      if (node.visited === 'temp') throw 'The graph is not a directed acyclic graph';

      node.visit('temp'); // Mark visit as temporary to detect if we loop back to this node
      for (const link of node.links) { visit(link); }
      node.visit(true);

      orderedList.push(node);
    };

    while (unvisitedNodes.length > 0) {
      const current = unvisitedNodes.shift();
      visit(current);
    }

    this.cleanUp();
    return orderedList.reverse();
  }
}

/** @returns {number} Luminance of the color. */
// Source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
function luminance(rgb) {
  const linrgb = srgb_to_lin_srgb(rgb);
  return 0.2126729 * linrgb[0] + 0.7151522 * linrgb[1] + 0.0721750 * linrgb[2];
}


/**
 * Computes the contrast between two colors as defined by WCAG2.
 * @param {number[]} rgbText - Array of r, g, b values of the text.
 * @param {number[]} rgbBack - Array of r, g, b values of the background.
 * @returns {number} Contrast between the two colors, in [1, 21].
 */
// Source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef
function WCAG2(rgbText, rgbBack) {
  const L1 = luminance(rgbText);
  const L2 = luminance(rgbBack);
  return (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
}


/**
 * Computes the SAPC/APCA contrast between two colors as defined by WCAG3.
 * @param {number[]} rgbText - Array of r, g, b values of the text.
 * @param {number[]} rgbBack - Array of r, g, b values of the background.
 * @returns {number} Contrast between the two colors.
 */
// Source of the math: https://github.com/Myndex/SAPC-APCA
function APCA(rgbText, rgbBack) {
  // 1. Compute luminances
  const coeffs = [0.2126729, 0.7151522, 0.0721750];
  const gamma = 2.4;
  const luminance = rgb => rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
  let [Ltext, Lback] = [rgbText, rgbBack].map(rgb => luminance(rgb));

  // 2. Clamp luminances
  const blackClampTrigger = 0.03;
  const blackClampPow = 1.45;
  [Ltext, Lback] = [Ltext, Lback].map(L => L > blackClampTrigger ? L : L + Math.pow(blackClampTrigger - L, blackClampPow));

  const δLmin = 0.0005;
  if (Math.abs(Ltext - Lback) < δLmin) return 0;

  // 3. Compute contrast
  let result;
  const scale = 1.25;
  const compute = (Lback, Ltext, powBack, powText) => (Math.pow(Lback, powBack) - Math.pow(Ltext, powText)) * scale;
  const lowClip = 0.001, lowTrigger = 0.078, lowOffset = 0.06, invLowTrigger = 12.82051282051282;

  // for dark text on light background
  if (Lback > Ltext) {
    const powBack = 0.55, powText = 0.58;
    const SAPC = compute(Lback, Ltext, powBack, powText);
    result = (SAPC < lowClip) ? 0
            : (SAPC < lowTrigger) ? SAPC * (1 - lowOffset * invLowTrigger)
            : SAPC - lowOffset;
  }

  // for light text on dark background
  else {
    const powBack = 0.62, powText = 0.57;
    const SAPC = compute(Lback, Ltext, powBack, powText);
    result = (SAPC > -lowClip) ? 0
            : (SAPC > -lowTrigger) ? SAPC * (1 - lowOffset * invLowTrigger)
            : SAPC + lowOffset;
  }

  return result * 100;
}

/**
 * Computes the CIEDE2000 distance between two colors.
 * @param {number[]} - Array of parsed LAB values of the first color (i.e. l in [0, 1]).
 * @param {number[]} - Array of parsed LAB values of the second color (i.e. l in [0, 1]).
 * @returns {number} Distance between the cwo colors.
 */
function CIEDE2000([l1, a1, b1], [l2, a2, b2]) {
  // Source of the math: http://www2.ece.rochester.edu/~gsharma/ciede2000/ciede2000noteCRNA.pdf
  const L1 = 100 * l1, L2 = 100 * l2;
  const C1 = Math.sqrt(a1 ** 2 + b1 ** 2);
  const C2 = Math.sqrt(a2 ** 2 + b2 ** 2);

  const mC = (C1 + C2) / 2,
        G = 0.5 * (1 - Math.sqrt(mC ** 7 / (mC ** 7 + 25 ** 7))),
        aa1 = (1 + G) * a1,
        aa2 = (1 + G) * a2,
        CC1 = Math.sqrt(aa1 ** 2 + b1 ** 2),
        CC2 = Math.sqrt(aa2 ** 2 + b2 ** 2);
  let hh1 = CC1 === 0 ? 0 : Math.atan2(b1, aa1) * 180 / Math.PI,
      hh2 = CC2 === 0 ? 0 : Math.atan2(b2, aa2) * 180 / Math.PI;
  while (hh1 < 0) hh1 += 360; while (hh1 > 360) hh1 -= 360;
  while (hh2 < 0) hh2 += 360; while (hh2 > 360) hh2 -= 360;

  const dL = L2 - L1,
        dC = CC2 - CC1;
  const dhh = (CC1 * CC2 === 0) ? 0
            : (Math.abs(hh2 - hh1) <= 180) ? hh2 - hh1
            : (hh2 - hh1 > 180) ? hh2 - hh1 - 360
            : hh2 - hh1 + 360;
  const dH = 2 * Math.sqrt(CC1 * CC2) * Math.sin((Math.PI / 180) * (dhh / 2));

  const mL = (L1 + L2) / 2,
        mCC = (CC1 + CC2) / 2;
  const mhh = (CC1 * CC2 === 0) ? hh1 + hh2
            : (Math.abs(hh2 - hh1) <= 180) ? (hh1 + hh2) / 2
            : (hh1 + hh2 >= 360) ? (hh1 + hh2 - 360) / 2
            : (hh1 + hh2 + 360) / 2;
  const T = 1 - 0.17 * Math.cos((Math.PI / 180) * (mhh - 30))
              + 0.24 * Math.cos((Math.PI / 180) * (2 * mhh))
              + 0.32 * Math.cos((Math.PI / 180) * (3 * mhh + 6))
              - 0.20 * Math.cos((Math.PI / 180) * (4 * mhh - 63)),
        dTH = 30 * Math.exp(-1 * ((mhh - 275) / 25) ** 2),
        RC = 2 * Math.sqrt(mCC ** 7 / (mCC ** 7 + 25 ** 7)),
        SL = 1 + (0.015 * (mL - 50) ** 2) / Math.sqrt(20 + (mL - 50) ** 2),
        SC = 1 + 0.045 * mCC,
        SH = 1 + 0.015 * mCC * T,
        RT = -1 * Math.sin((Math.PI / 180) * (2 * dTH)) * RC;

  return Math.sqrt(
    (dL / SL) ** 2
    + (dC / SC) ** 2
    + (dH / SH) ** 2
    + RT * (dC / SC) * (dH / SH)
  );
}

// Source of the math: https://bottosson.github.io/posts/gamutclipping/


function maxSaturation(a, b) {
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


function cusp(a, b) {
  const Scusp = maxSaturation(a, b);

  const rgbMax = oklab_to_lin_srgb([1, Scusp * a, Scusp * b]);
  const Lcusp = Math.cbrt(1 / Math.max(...rgbMax));
  const Ccusp = Lcusp * Scusp;

  return [Lcusp, Ccusp];
}


function gamutIntersection(a, b, L1, C1, L0) {
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


function clip(rgb) {
  if (rgb.every(v => v > 0 && v < 1)) return rgb;
  
  const [okl, oka, okb] = lin_srgb_to_oklab(srgb_to_lin_srgb(rgb));
  const [x, okc, okh] = oklab_to_oklch([okl, oka, okb]);
  
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
  
  const clampedValues = lin_srgb_to_srgb(oklab_to_lin_srgb([Lclipped, Cclipped * a, Cclipped * b]));
  return clampedValues;
}

const colorSpaces = [
  {
    id: 'srgb',
    whitepoint: 'd65',
    CSSformat: 'rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_srgb', 'hsl']
  }, {
    id: 'lin_srgb',
    links: ['srgb', 'd65xyz', 'oklab']
  }, {
    id: 'hsl',
    whitepoint: 'd65',
    CSSformat: 'hsl',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    whitepoint: 'd65',
    CSSformat: 'hwb',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['hsl']
  }, {
    id: 'lab',
    whitepoint: 'd50',
    CSSformat: 'lab',
    gamut: [ [0, 4], [-Infinity, Infinity], [-Infinity, Infinity] ],
    links: ['xyz', 'lch']
  }, {
    id: 'lch',
    whitepoint: 'd50',
    CSSformat: 'lch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['lab']
  }, {
    id: 'xyz',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab', 'd65xyz', 'lin_prophoto-rgb']
  }, {
    id: 'd65xyz',
    whitepoint: 'd65',
    links: ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
  }, {
    id: 'display-p3',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_display-p3']
  }, {
    id: 'lin_display-p3',
    links: ['display-p3', 'd65xyz']
  }, {
    id: 'a98-rgb',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_a98-rgb']
  }, {
    id: 'lin_a98-rgb',
    links: ['a98-rgb', 'd65xyz']
  }, {
    id: 'prophoto-rgb',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_prophoto-rgb']
  }, {
    id: 'lin_prophoto-rgb',
    links: ['prophoto-rgb', 'xyz']
  }, {
    id: 'rec2020',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_rec2020']
  }, {
    id: 'lin_rec2020',
    links: ['rec2020', 'd65xyz']
  }, {
    id: 'oklab',
    whitepoint: 'd65',
    gamut: [ [0, 4], [-Infinity, Infinity], [-Infinity, Infinity] ],
    links: ['lin_srgb', 'oklch']
  }, {
    id: 'oklch',
    whitepoint: 'd65',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['oklab']
  }
];

const namedColors = {
  aliceblue: 'f0f8ff',
  antiquewhite: 'faebd7',
  aqua: '00ffff',
  aquamarine: '7fffd4',
  azure: 'f0ffff',
  beige: 'f5f5dc',
  bisque: 'ffe4c4',
  black: '000000',
  blanchedalmond: 'ffebcd',
  blue: '0000ff',
  blueviolet: '8a2be2',
  brown: 'a52a2a',
  burlywood: 'deb887',
  cadetblue: '5f9ea0',
  chartreuse: '7fff00',
  chocolate: 'd2691e',
  coral: 'ff7f50',
  cornflowerblue: '6495ed',
  cornsilk: 'fff8dc',
  crimson: 'dc143c',
  cyan: '00ffff',
  darkblue: '00008b',
  darkcyan: '008b8b',
  darkgoldenrod: 'b8860b',
  darkgray: 'a9a9a9',
  darkgrey: 'a9a9a9',
  darkgreen: '006400',
  darkkhaki: 'bdb76b',
  darkmagenta: '8b008b',
  darkolivegreen: '556b2f',
  darkorange: 'ff8c00',
  darkorchid: '9932cc',
  darkred: '8b0000',
  darksalmon: 'e9967a',
  darkseagreen: '8fbc8f',
  darkslateblue: '483d8b',
  darkslategray: '2f4f4f',
  darkslategrey: '2f4f4f',
  darkturquoise: '00ced1',
  darkviolet: '9400d3',
  deeppink: 'ff1493',
  deepskyblue: '00bfff',
  dimgray: '696969',
  dimgrey: '696969',
  dodgerblue: '1e90ff',
  firebrick: 'b22222',
  floralwhite: 'fffaf0',
  forestgreen: '228b22',
  fuchsia: 'ff00ff',
  gainsboro: 'dcdcdc',
  ghostwhite: 'f8f8ff',
  gold: 'ffd700',
  goldenrod: 'daa520',
  gray: '808080',
  grey: '808080',
  green: '008000',
  greenyellow: 'adff2f',
  honeydew: 'f0fff0',
  hotpink: 'ff69b4',
  indianred: 'cd5c5c',
  indigo: '4b0082',
  ivory: 'fffff0',
  khaki: 'f0e68c',
  lavender: 'e6e6fa',
  lavenderblush: 'fff0f5',
  lawngreen: '7cfc00',
  lemonchiffon: 'fffacd',
  lightblue: 'add8e6',
  lightcoral: 'f08080',
  lightcyan: 'e0ffff',
  lightgoldenrodyellow: 'fafad2',
  lightgray: 'd3d3d3',
  lightgrey: 'd3d3d3',
  lightgreen: '90ee90',
  lightpink: 'ffb6c1',
  lightsalmon: 'ffa07a',
  lightseagreen: '20b2aa',
  lightskyblue: '87cefa',
  lightslategray: '778899',
  lightslategrey: '778899',
  lightsteelblue: 'b0c4de',
  lightyellow: 'ffffe0',
  lime: '00ff00',
  limegreen: '32cd32',
  linen: 'faf0e6',
  magenta: 'ff00ff',
  maroon: '800000',
  mediumaquamarine: '66cdaa',
  mediumblue: '0000cd',
  mediumorchid: 'ba55d3',
  mediumpurple: '9370d8',
  mediumseagreen: '3cb371',
  mediumslateblue: '7b68ee',
  mediumspringgreen: '00fa9a',
  mediumturquoise: '48d1cc',
  mediumvioletred: 'c71585',
  midnightblue: '191970',
  mintcream: 'f5fffa',
  mistyrose: 'ffe4e1',
  moccasin: 'ffe4b5',
  navajowhite: 'ffdead',
  navy: '000080',
  oldlace: 'fdf5e6',
  olive: '808000',
  olivedrab: '6b8e23',
  orange: 'ffa500',
  orangered: 'ff4500',
  orchid: 'da70d6',
  palegoldenrod: 'eee8aa',
  palegreen: '98fb98',
  paleturquoise: 'afeeee',
  palevioletred: 'd87093',
  papayawhip: 'ffefd5',
  peachpuff: 'ffdab9',
  peru: 'cd853f',
  pink: 'ffc0cb',
  plum: 'dda0dd',
  powderblue: 'b0e0e6',
  purple: '800080',
  rebeccapurple: '663399',
  red: 'ff0000',
  rosybrown: 'bc8f8f',
  royalblue: '4169e1',
  saddlebrown: '8b4513',
  salmon: 'fa8072',
  sandybrown: 'f4a460',
  seagreen: '2e8b57',
  seashell: 'fff5ee',
  sienna: 'a0522d',
  silver: 'c0c0c0',
  skyblue: '87ceeb',
  slateblue: '6a5acd',
  slategray: '708090',
  slategrey: '708090',
  snow: 'fffafa',
  springgreen: '00ff7f',
  steelblue: '4682b4',
  tan: 'd2b48c',
  teal: '008080',
  thistle: 'd8bfd8',
  tomato: 'ff6347',
  turquoise: '40e0d0',
  violet: 'ee82ee',
  wheat: 'f5deb3',
  white: 'ffffff',
  whitesmoke: 'f5f5f5',
  yellow: 'ffff00',
  yellowgreen: '9acd32'
};

// Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
const numberExp = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';
const RegExps = {
  number: numberExp,
  percentage: numberExp + '%',
  numberOrPercentage: numberExp + '%?',
  angle: numberExp + '(?:deg|grad|rad|turn)?'
};



const Formats = [
  {
    id: 'hex',
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
  }, {
    id: 'rgb',
    syntaxes: [
      // rgb(255, 255, 255) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number})\\)$`),
      // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(100%, 100%, 100%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(255 255 255)
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(100% 100% 100%)
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hsl',
    syntaxes: [
      // hsl(<angle>, 100%, 100%)
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      // hsl(<angle> 100% 100%)
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hwb',
    syntaxes: [
      // hwb(<angle> 100% 100%)
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lab',
    syntaxes: [
      // lab(300% 25 40)
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // lab(300% 25 40 / .5)
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lch',
    syntaxes: [
      // lch(300% 25 <angle>)
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle})\\)$`),
      // lch(300% 25 <angle> / .5)
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'color',
    syntaxes: [
      // color(display-p3 -0.6112 1.0079 -0.2192)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'name',
    syntaxes: [
      // white or WHITE or WhiTe
      /^[A-Za-z]+$/
    ]
  }
];

const cssFormats = /*#__PURE__*/Object.freeze({
  __proto__: null,
  RegExps: RegExps,
  Formats: Formats
});

/**
 * Colori module
 * @author Remiscan <https://remiscan.fr>
 * @module colori.js
 */

class Couleur {
  /* TYPE DEFINITIONS */

  /** 
   * A valid color expression.
   * Supported formats are hexadecimal, RGB, HSL, HWB, LAB, LCH and the color() function.
   * @typedef {string} colorString
   */

  /**
   * An array of parsed r, g, b, a color values.
   * @typedef {Array.<number>} colorArray
   */

  /**
   * A Couleur, or a valid color expression. @see colorString
   * @typedef {(Couleur|colorArray|colorString)} color
   */

  /**
   * The name of a property of a Couleur object.
   * @typedef {('r'|'g'|'b'|'a'|'h'|'s'|'l'|'w'|'bk'|'ciel'|'ciea'|'cieb'|'ciec'|'cieh')} colorProperty
   */



  /**
   * Creates a new Couleur object that contains r, g, b, a properties of the color.
   * These properties will take their values from sRGB color space, even if they're out of bounds.
   * (This means values <0 or >1 can be stored — they can be clamped to a specific color space when needed.)
   * @param {colorString|number[]|Couleur} color - Color expression in a supported format.
   * @throws {string} when the parameter isn't a valid color string.
   */
  constructor(color) {
    /** @property {number} r - Red value (from 0 to 1) */
    this.r = null;
    /** @property {number} g - Green value (from 0 to 1) */
    this.g = null;
    /** @property {number} b - Blue value (from 0 to 1) */
    this.b = null;
    /** @property {number} a - Opacity (from 0 to 1) */
    this.a = null;

    const isAlpha = (val, def = 1) => !!val ? val : (val === 0) ? 0 : def;

    if (color instanceof Couleur) {
      this.r = color.r;
      this.g = color.g;
      this.b = color.b;
      this.a = color.a;
    }

    else if (Array.isArray(color) && (color.length == 3 || color.length == 4)) {
      [this.r, this.g, this.b] = Couleur.toGamut('srgb', color.slice(0, 3), 'srgb', { method: 'naive' });
      this.a = Math.max(0, Math.min(isAlpha(color[3]), 1));
    }

    else if (typeof color === 'string') {
      const format = Couleur.matchSyntax(color.trim());

      switch (format.id) {
        case 'hex':
          this.hex = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4], 'ff')];
          break;
        case 'rgb':
          this.rgb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'hsl':
          this.hsl = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'hwb':
          this.hwb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'lab':
          this.lab = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'lch':
          this.lch = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'color':
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], isAlpha(format.data[5])]);
          break;
        default:
          throw `${JSON.stringify(color)} is not a valid color format`;
      }
    }

    else throw `Couleur objects can only be created from a string, an array of parsed values, or another Couleur object ; this is not one: ${JSON.stringify(color)}`;
  }


  /**
   * Makes a Couleur from the argument if it's not one already.
   * @param {color} color
   * @returns {Couleur}
   */
  static makeInstance(color) {
    if (color instanceof Couleur) return color;
    else                          return new Couleur(color);
  }


  /**
   * Matches the user input with supported color formats.
   * @param {string} colorString - Color expression in a supported format.
   * @returns {Object} Recognized syntax.
   * @throws {string} when {couleur} is not in a valid format.
   */
  static matchSyntax(colorString) {
    const tri = colorString.slice(0, 3);
    
    // Predetermine the format, to save regex-matching time
    let format;
    if (tri.slice(0, 1) === '#') format = Couleur.formats[0];
    else switch (tri) {
      case 'rgb': format = Couleur.formats[1]; break;
      case 'hsl': format = Couleur.formats[2]; break;
      case 'hwb': format = Couleur.formats[3]; break;
      case 'lab': format = Couleur.formats[4]; break;
      case 'lch': format = Couleur.formats[5]; break;
      case 'col': format = Couleur.formats[6]; break;
      default:    format = Couleur.formats[7];
    }

    // Check if the given string matches any color syntax
    for (const syntaxe of format.syntaxes) {
      const result = colorString.match(syntaxe);
      if (result != null && result[0] === colorString) {
        if (format.id === 'name') {
          if (colorString === 'transparent') return { id: 'rgb', data: [null, 0, 0, 0, 0] };
          const allNames = Couleur.couleursNommees;
          const name = colorString.toLowerCase();
          if (name in allNames) return Couleur.matchSyntax('#' + allNames[name]);
        }
        return { id: format.id, data: result };
      }
    }

    throw `${JSON.stringify(colorString)} is not a valid color format`;
  }


  /**
   * Parses a number / percentage / angle into the correct format to store it.
   * @param {(string|number)} value - The value to parse.
   * @param {?string} prop - The color property that has n as its value.
   * @param {object} options
   * @param {boolean} options.clamp - Whether the value should de clamped to its color space bounds.
   * @returns {number} The properly parsed number.
   */
  static parse(value, prop = null, { clamp = true } = {}) {
    // Alpha values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 1]
    // to [0, 1]
    if (prop === 'a') {
      // If n is a percentage
      if (new RegExp('^' + RegExps.percentage + '$').test(value)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(value) / 100, 1));
        else        return parseFloat(value) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + RegExps.number + '$').test(value)) {
        if (clamp)  return Math.max(0, Math.min(value, 1));
        else        return parseFloat(value);
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // Red, green, blue values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 255]
    // to [0, 1]
    else if (['r', 'g', 'b'].includes(prop)) {
      // If n is a percentage
      if (new RegExp('^' + RegExps.percentage + '$').test(value)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(value) / 100, 1));
        else        return parseFloat(value) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + RegExps.number + '$').test(value)) {
        if (clamp)  return Math.max(0, Math.min(value / 255, 1));
        else        return value / 255;
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // Hue and CIE hue values:
    // from any angle or any number
    // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
    // to [0, 360]
    else if(['h', 'cieh'].includes(prop)) {
      let h = parseFloat(value);
      // If n is a number
      if (new RegExp('^' + RegExps.number + '$').test(value)) {
        return angleToRange(h);
      }
      // If n is an angle
      if ((new RegExp('^' + RegExps.angle + '$').test(value))) {
        if (String(value).slice(-3) === 'deg') ; // necessary to accept deg values
        else if (String(value).slice(-4) === 'grad')
          h = h * 360 / 400;
        else if (String(value).slice(-3) === 'rad')
          h = h * 180 / Math.PI;
        else if (String(value).slice(-4) === 'turn')
          h = h * 360;
        else throw `Invalid angle value: ${JSON.stringify(value)}`;
        return angleToRange(h);
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // Percentage values:
    // from any %
    // clamped to [0, 100]%
    // to [0, 1]
    else if(['s', 'l', 'w', 'bk', 'ciel'].includes(prop)) {
      // If n is a percentage
      if (new RegExp('^' + RegExps.percentage + '$').test(value)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(value) / 100, 1));
        else        return parseFloat(value) / 100;
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // CIE axes values:
    // any number
    else if(['ciea', 'cieb'].includes(prop)) {
      // If n is a number
      if (new RegExp('^' + RegExps.number + '$').test(value)) {
        return parseFloat(value);
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // CIE chroma values:
    // from any number
    // clamped to [0, +Inf[
    else if (prop === 'ciec') {
      // If n is a number
      if (new RegExp('^' + RegExps.number + '$').test(value)) {
        if (clamp)  return Math.max(0, value);
        else        return parseFloat(value);
      }
      else throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
    }

    // Arbitrary values
    // from any % or any number
    // to any number (so that 0% becomes 0 and 100% becomes 1)
    else {
      // If n is a percentage
      if (new RegExp('^' + RegExps.percentage + '$').test(value)) {
        return parseFloat(value) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + RegExps.number + '$').test(value)) {
        return parseFloat(value);
      }
      else throw `Invalid arbitrary value: ${JSON.stringify(value)}`;
    }
  }


  /**
   * Unparses a value to the format that would be used in a CSS expression.
   * @param {number} value - Value to unparse.
   * @param {string} prop - Name of the property that has the value.
   * @param {object} options
   * @param {number} options.precision - How many decimals to display.
   * @returns {string} The unparsed value, ready to insert in a CSS expression.
   */
  static unparse(value, prop, { precision = 0 } = {}) {
    switch (prop) {
      case 'r': case 'g': case 'b':
        return precision === null ? `${255 * value}` : `${Math.round(10**precision * 255 * value) / (10**precision)}`;
      case 's': case 'l': case 'w': case 'bk': case 'ciel':
        return precision === null ? `${100 * value}%` : `${Math.round(10**precision * 100 * value) / (10**precision)}%`;
      case 'a':
        return precision === null ? `${value}` : `${Math.round(10**Math.max(precision, 2) * value) / (10**Math.max(precision, 2))}`;
      default:
        return precision === null ? `${value}` : `${Math.round(10**precision * value) / (10**precision)}`;
    }
  }



  /*****************************************/
  /* Setters and getters for color formats */
  /*****************************************/


  /* GENERAL SETTER */

  /**
   * Will be used by other setters to calculate all color properties.
   * @param {Array.<string|number>} data - Array of unparsed values.
   * @param {string[]} props - Array of color property names the values correspond to.
   * @param {object|string} spaceID - Color space of the values, or its identifier.
   */
  set(data, props, spaceID) {
    const space = Couleur.getSpace(spaceID);
    const values = props.map((p, i) => Couleur.parse(data[i], p));
    [this.r, this.g, this.b] = Couleur.convert(space, 'srgb', values);

    const isAlpha = (val, def = 1) => !!val ? val : (val === 0) ? 0 : def;
    this.a = Couleur.parse(isAlpha(data[3]), 'a');
  }


  /* GENERAL GETTER */

  /**
   * Creates a string containing the CSS expression of a color.
   * @param {object|string} format - Color space of the requested CSS expression, or its identifier.
   * @param {object} options
   * @param {number} options.precision - How many decimals to display.
   * @param {string} options.clamp - Which color space the values should be clamped to.
   * @returns {string} The expression of the color in the requested format.
   */
  expr(format, { precision = 0, clamp = true } = {}) {
    const spaceID = typeof format === 'string' ? format.replace('color-', '') : format;
    const space = Couleur.getSpace(spaceID);
    let values = this.valuesTo(space);
    if (clamp) values = Couleur.toGamut(space, values, space);
    const a = Couleur.unparse(this.a, 'a', { precision });
    values = [...values, a];

    // If the requested expression is of the color(space, ...) type
    if (format.slice(0, 5) === 'color') {
      let string = `color(${space.id}`;
      for (const [k, v] of Object.entries(values)) {
        if (Number(k) === values.length - 1) {
          if (a >= 1) break;
          string += ` / ${a}`;
        } else {
          string += ` ${precision === null ? v : Math.round(10**precision * v) / (10**precision)}`;
        }
      } 
      string += `)`;
      return string;
    }

    // If the requested expression is of the ${format}(...) type
    const props = Couleur.propertiesOf(format);
    const [x, y, z] = props.map((p, k) => Couleur.unparse(values[k], p, { precision }));

    switch (format) {
      case 'rgb': case 'rgba': case 'hsl': case 'hsla': {
        if ((format.length > 3 && format.slice(-1) === 'a') || a < 1)
          return `${format}(${x}, ${y}, ${z}, ${a})`;
        else
          return `${format}(${x}, ${y}, ${z})`;
      }
      default: {
        if (a < 1) return `${format}(${x} ${y} ${z} / ${a})`;
        else       return `${format}(${x} ${y} ${z})`;
      }
    }
  }

  /**
   * Creates a string containing the CSS expression of a color from a list of values.
   * @param {object|string} format - Color space of the requested CSS expression, or its identifier.
   * @param {number[]} values - The values of the r, g, b, a properties.
   * @param {object|string} valueSpaceID - Color space of the given values, or its identifier.
   * @param {object} options - @see Couleur.expr
   * @returns {string} The expression of the color in the requested format.
   */
  static makeExpr(format, values, valueSpaceID, options) {
    const spaceID = typeof format === 'string' ? format.replace('color-', '') : format;
    const rgba = [...Couleur.convert(valueSpaceID, spaceID, values.slice(0, 3)), values[3]];
    return (new Couleur(rgba)).expr(format, options);
  }


  /* ALL VALUES (r, g, b) */

  /** @returns {number[]} The array of r, g, b values of the color in sRGB color space. */
  get values() { return [this.r, this.g, this.b]; }


  /* NAME */

  /** @returns {?string} The approximate name of the color. */
  get name() {
    if (this.a === 1) {
      const allNames = Couleur.couleursNommees;
      const [r, g, b] = [255 * this.r, 255 * this.g, 255 * this.b];
      const tolerance = 255 * .02;
      for (const [name, hex] of Object.entries(allNames)) {
        const [r2, g2, b2] = [parseInt(`${hex[0]}${hex[1]}`, 16), parseInt(`${hex[2]}${hex[3]}`, 16), parseInt(`${hex[4]}${hex[5]}`, 16)];
        if (Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b) < tolerance) return name;
      }
      return null;
    }
    else if (this.a === 0) return 'transparent';
    else                   return null;
  }

  /** @returns {?string} The exact name of the color. */
  get exactName() {
    if (this.a === 1) {
      const allNames = Couleur.couleursNommees;
      const hex6 = this.hex.slice(1);
      for (const [name, hex] of Object.entries(allNames)) {
        if (hex === hex6) return name;
      }
      return null;
    }
    else if (this.a === 0) return 'transparent';
    else                   return null;
  }


  /* RGB (hexadecimal) */
  
  /**
   * Calculates all properties of the color from its hexadecimal expression.
   * @param {string[]} - The hexadecimal values of the r, g, b, a properties.
   */
  set hex(hexa) {
    let r, g, b, a;
    
    r = hexa[0];
    r = (r.length === 1) ? r.repeat(2) : r;
    r = parseInt(r, 16);
    
    g = hexa[1];
    g = (g.length === 1) ? g.repeat(2) : g;
    g = parseInt(g, 16);
    
    b = hexa[2];
    b = (b.length === 1) ? b.repeat(2) : b;
    b = parseInt(b, 16);
    
    a = hexa[3] || 'ff';
    a = (a.length === 1) ? a.repeat(2) : a;
    a = parseInt(a, 16) / 255;

    this.rgb = [r, g, b, a];
  }
  set hexa(hexa) { this.hex = hexa; }

  /** @returns {string} Hexadecimal expression of the color. */
  get hex() {
    const values = Couleur.toGamut('srgb', this.values);
    const rgb = [...values, this.a].map(v => pad(Math.round(v * 255).toString(16)));
    if (this.a < 1) return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
    else            return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }


  /* RGB (functional) */
  
  /**
   * Calculates all properties of the color from its functional RGB expression.
   * @param {Array.<string|number>} rgba - The unparsed values of the r, g, b, a properties.
   */
  set rgb(rgba) { this.set(rgba, ['r', 'g', 'b'], 'srgb'); }
  set rgba(rgba) { this.rgb = rgba; }

  /** @returns {string} RGB expression of the color. */
  get rgb() { return this.expr('rgb', { precision: 2 }); }
  get rgba() { return this.rgb; }


  /* HSL */

  /**
   * Calculates all properties of the color from its HSL expression.
   * @param {Array.<string|number>} hsla - The unparsed values of the h, s, l, a properties.
   */
  set hsl(hsla) { this.set(hsla, ['h', 's', 'l'], 'hsl'); }
  set hsla(hsla) { this.hsl = hsla; }

  /** @returns {string} HSL expression of the color. */
  get hsl() { return this.expr('hsl', { precision: 2 }); }
  get hsla() { return this.hsl; }


  /* HWB */

  /**
   * Calculates all properties of the color from its HWB expression.
   * @param {Array.<string|number>} hwba - The unparsed values of the h, w, bk, a properties.
   */
  set hwb(hwba) { this.set(hwba, ['h', 'w', 'bk'], 'hwb'); }

  /** @returns {string} HWB expression of the color. */
  get hwb() { return this.expr('hwb', { precision: 2 }); }


  /* LAB */

  /**
   * Calculates all properties of the color from its LAB expression.
   * @param {Array.<string|number>} laba - The unparsed values of the ciel, ciea, cieb, a properties.
   */
  set lab(laba) { this.set(laba, ['ciel', 'ciea', 'cieb'], 'lab'); }

  /** @returns {string} LAB expression of the color. */
  get lab() { return this.expr('lab', { precision: 2 }); }


  /* LCH */

  /**
   * Calculates all properties of the color from its LCH expression.
   * @param {Array.<string|number>} - The unparsed values of the ciel, ciec, cieh, a properties.
   */
  set lch(lcha) { this.set(lcha, ['ciel', 'ciec', 'cieh'], 'lch'); }

  /** @returns {string} LCH expression of the color. */
  get lch() { return this.expr('lch', { precision: 2 }); }


  /* PROFILED COLORS */

  /**
   * Calculates all properties of the color from its functional color() expression.
   * @param {string} spaceID - 
   * @param {Array.<string|number>} rgba - The parsed values of the r, g, b, a properties.
   */
  setColor(spaceID, rgba) {
    let rgb = rgba.slice(0, 3).map(v => Couleur.parse(v));
    const a = Couleur.parse(rgba[3]);

    //const clamp = v => Math.max(0, Math.min(v, 1));
    switch (spaceID) {
      case 'srgb': case 'display-p3': case 'a98-rgb': case 'prophoto-rgb': case 'rec2020':
        //rgb = rgb.map(v => clamp(v));
      case 'xyz':
        rgb = Couleur.convert(spaceID, 'srgb', rgb);
        break;
      default:
        if (spaceID.startsWith('--')) {
          const id = spaceID.substring(2);
          rgb = Couleur.convert(id, 'srgb', rgb);
        }
        else throw `The ${JSON.stringify(spaceID)} color space is not supported`;
    }

    const values = [...rgb, a];
    return this.set(values, [null, null, null], 'srgb');
  }



  /********************************************/
  /* Setters and getters for color properties */
  /********************************************/

  
  /**
   * Recalculates the r, g, b properties of the color after modifying one of its other properties.
   * @param {number} val - The parsed new value of the property.
   */
  set h(val) {
    const [x, s, l] = this.valuesTo('hsl');
    const props = [...Couleur.propertiesOf('hsl'), 'a'];
    this.hsl = [val, s, l, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set hue(val) { this.h = val; }

  set s(val) {
    const [h, x, l] = this.valuesTo('hsl');
    const props = [...Couleur.propertiesOf('hsl'), 'a'];
    this.hsl = [h, val, l, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set saturation(val) { this.s = val; }

  set l(val) {
    const [h, s, x] = this.valuesTo('hsl');
    const props = [...Couleur.propertiesOf('hsl'), 'a'];
    this.hsl = [h, s, val, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set lightness(val) { this.l = val; }

  set w(val) {
    const [h, x, bk] = this.valuesTo('hwb');
    const props = [...Couleur.propertiesOf('hwb'), 'a'];
    this.hwb = [h, val, bk, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set whiteness(val) { this.w = val; }

  set bk(val) {
    const [h, w, x] = this.valuesTo('hwb');
    const props = [...Couleur.propertiesOf('hwb'), 'a'];
    this.hwb = [h, w, val, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set blackness(val) { this.bk = val; }

  set ciel(val) {
    const [x, ciea, cieb] = this.valuesTo('lab');
    const props = [...Couleur.propertiesOf('lch'), 'a'];
    this.lab = [val, ciea, cieb, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set CIElightness(val) { this.ciel = val; }

  set ciea(val) {
    const [ciel, x, cieb] = this.valuesTo('lab');
    const props = [...Couleur.propertiesOf('lab'), 'a'];
    this.lab = [ciel, val, cieb, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }

  set cieb(val) {
    const [ciel, ciea, x] = this.valuesTo('lab');
    const props = [...Couleur.propertiesOf('lab'), 'a'];
    this.lab = [ciel, ciea, val, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }

  set ciec(val) {
    const [ciel, x, cieh] = this.valuesTo('lch');
    const props = [...Couleur.propertiesOf('lch'), 'a'];
    this.lch = [ciel, val, cieh, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set CIEchroma(val) { this.ciec = val; }

  set cieh(val) {
    const [ciel, ciec, x] = this.valuesTo('lch');
    const props = [...Couleur.propertiesOf('lch'), 'a'];
    this.lch = [ciel, ciec, val, this.a].map((v, k) => Couleur.unparse(v, props[k]));
  }
  set CIEhue(val) { this.cieh = val; }

  /** @returns {number} Gets the parsed value of one of the color properties. */
  get red() { return this.r; }
  get green() { return this.g; }
  get blue() { return this.b; }
  get alpha() { return this.a; }
  get opacity() { return this.a; }
  get h() { return this.valuesTo('hsl')[0]; }
  get hue() { return this.h; }
  get s() { return this.valuesTo('hsl')[1]; }
  get saturation() { return this.s; }
  get l() { return this.valuesTo('hsl')[2]; }
  get lightness() { return this.l; }
  get w() { return this.valuesTo('hwb')[1]; }
  get whiteness() { return this.w; }
  get bk() { return this.valuesTo('hwb')[2]; }
  get whiteness() { return this.w; }
  get ciel() { return this.valuesTo('lab')[0]; }
  get CIElightness() { return this.ciel; }
  get ciea() { return this.valuesTo('lab')[1]; }
  get cieb() { return this.valuesTo('lab')[2]; }
  get ciec() { return this.valuesTo('lch')[1]; }
  get CIEchroma() { return this.ciec; }
  get cieh() { return this.valuesTo('lch')[2]; }
  get CIEhue() { return this.cieh; }

  get luminance() {
    if (this.a < 1) throw `The luminance of a transparent color would be meaningless`;
    return luminance(this.values);
  }



  /***********************************/
  /* Conversion between color spaces */
  /***********************************/


  /**
   * Converts the color values from one color space to another.
   * @param {object|string} startSpaceID - Starting color space, or its identifier.
   * @param {object|string} endSpaceID - Color space to convert to, or its identifier.
   * @param {*} values - Array of color values (without alpha) in startSpaceID color space.
   * @returns {number[]} Array of values in the new color space.
   */
  static convert(startSpaceID, endSpaceID, values) {
    if ((startSpaceID.id || startSpaceID) === (endSpaceID.id || endSpaceID)) return values;
    const startSpace = Couleur.getSpace(startSpaceID);
    const endSpace = Couleur.getSpace(endSpaceID);

    // Find the shortest sequence of functions to convert between color spaces
    let path;
    const graph = new Graph(Couleur.colorSpaces);
    try { path = graph.shortestPath(startSpace.id, endSpace.id).map(node => node.id); }
    catch (error) {
      switch (error) {
        case `Node ${startSpace.id} does not exist`: throw `${JSON.stringify(startSpace.id)} is not a supported color space`;
        case `Node ${endSpace.id} does not exist`: throw `${JSON.stringify(endSpace.id)} is not a supported color space`;
        case `No path found from ${startSpace.id} to ${endSpace.id}`: throw `Conversion from ${JSON.stringify(startSpace.id)} space to ${JSON.stringify(endSpace.id)} space is impossible`;
        default: throw error;
      }
    }

    // Apply these functions to the color values.
    let result = values;
    while (path.length > 1) {
      const start = path.shift();
      const end = path[0];
      const functionName = `${start}_to_${end}`.replace(/-/g, '');
      if (!Conversions[functionName]) console.log(functionName);
      result = Conversions[functionName](result);
    }

    return result;
  }


  /**
   * Converts the r, g, b values of the color to another color space.
   * @param {object|string} spaceID - Desired color space, or its identifier.
   * @param {object} options
   * @param {boolean} options.clamp - Whether to clamp the values to their new color space.
   * @returns {number[]} The array of converted values.
   */
  valuesTo(spaceID, { clamp = false } = {}) {
    const space = Couleur.getSpace(spaceID);
    let values = Couleur.convert('srgb', space, this.values);
    if (clamp) values = Couleur.toGamut(space, values);
    return values;
  }


  /* Clamping to a color space */


  /**
   * Checks whether parsed values in valueSpaceID color space correspond to a color in the spaceID color space.
   * @param {object|string} spaceID - Color space whose gamut will be checked, or its identifier.
   * @param {number[]} values - Array of parsed values.
   * @param {object|string} valueSpaceID - Color space of the given values, or its identifier.
   * @returns {boolean} Whether the corresponding color is in gamut.
   */
  static inGamut(spaceID, values, valueSpaceID = 'srgb', { tolerance = .0001 } = {}) {
    const space = Couleur.getSpace(spaceID);
    const convertedValues = Couleur.convert(valueSpaceID, space, values);
    return convertedValues.every((v, k) => v >= (space.gamut[k][0] - tolerance) && v <= (space.gamut[k][1] + tolerance));
  }

  /** @see Couleur.inGamut - Non-static version. */
  inGamut(spaceID, options) { return Couleur.inGamut(spaceID, this.values, 'srgb', options); }

  /**
   * Clamps parsed values in valueSpaceID color space to the spaceID color space.
   * @param {object|string} spaceID - Color space whose gamut will be used, or its identifier.
   * @param {number[]} values - Array of parsed values.
   * @param {object|string} valueSpaceID - Color space of the given values, or its identifier.
   * @returns {number[]} The array of values in valueSpaceID color space, after clamping the color to spaceID color space.
   */
  static toGamut(spaceID, values, valueSpaceID = 'srgb', { method = 'oklab' } = {}) {
    const space = Couleur.getSpace(spaceID);
    const valueSpace = Couleur.getSpace(valueSpaceID);
    if (Couleur.inGamut(space, values, valueSpace, { tolerance: 0 })) return values;
    let clampedValues, clampSpace;

    // Naively clamp the values
    if (method === 'naive') {
      clampSpace = space;
      const convertedValues = Couleur.convert(valueSpace, clampSpace, values);
      clampedValues = convertedValues.map((v, k) => Math.max(space.gamut[k][0], Math.min(v, space.gamut[k][1])));
    }

    // OKLab gamut clipping
    else if (method === 'oklab') {
      clampSpace = Couleur.getSpace('srgb');
      const rgb = Couleur.convert(valueSpace, clampSpace, values);
      clampedValues = clip(rgb);
    }
    
    // Let's reduce the LCH chroma until the color is in the color space.
    else if (method === 'chroma') {
      // Source of the math: https://github.com/LeaVerou/color.js/blob/master/src/color.js
      clampSpace = Couleur.getSpace('lch');
      let lch = Couleur.convert(valueSpace, clampSpace, values);

      const τ = .01;
      let Cmin = 0;
      let Cmax = lch[1];
      lch[1] = lch[1] / 2;

      while (Cmax - Cmin > τ) {
        const naive = Couleur.toGamut(space, lch, clampSpace, { method: 'naive' });

        // If the color is close to the color space border
        if (Couleur.distance(naive, lch, { method: 'CIEDE2000' }) < 2 + τ)
          Cmin = lch[1];
        else
          Cmax = lch[1];
        lch[1] = (Cmin + Cmax) / 2;
      }

      clampedValues = lch;
    }

    // Final naive clamp to get in the color space if the color is still just outside the border
    if (method !== 'naive') clampedValues = Couleur.toGamut(space, clampedValues, clampSpace, { method: 'naive' });

    // Send the values back in the same color space we found them in
    return Couleur.convert(clampSpace, valueSpace, clampedValues);
  }

  /** @see Couleur.toGamut - Non-static version. */
  toGamut(spaceID) { return Couleur.toGamut(spaceID, this.values, 'srgb'); }



  /********************************/
  /* Color manipulation functions */
  /********************************/


  /* Color modification */


  /**
   * Modifies a color by changing a specific property.
   * @param {colorProperty} prop - The color property that will be changed.
   * @param {(string|number)} value - The value that will be added to the property.
   * @param {Object} options
   * @param {boolean} options.action - 'replace' if the value should replace the previous value of the property,
   *                                   'scale' if the value should be multiplied to the previous value of the property,
   *                                   null if the value should be added to the previous value of the property.
   * @returns {Couleur} The modified color.
   */
   change(prop, value, { action = null } = {}) {
    const replace = action === 'replace';
    const scale = action === 'scale';
    const val = scale ? Couleur.parse(value) : Couleur.parse(value, prop, { clamp: false });
    const changedColor = new Couleur(this);

    const oldVal = this[prop];
    const newVal = replace ? val : scale ? oldVal * val : oldVal + val;
    changedColor[prop] = newVal;
    return changedColor;
  }

  /**
   * Modifies a color by replacing the value of a specific property.
   * This is an alias to change() with options.replace = true
   * @param {colorProperty} prop - The color property that will be changed.
   * @param {(string|number)} value - The value that will replace the previous value of the property.
   * @returns {Couleur} The modified color.
   */
  replace(prop, value) {
    return this.change(prop, value, { action: 'replace' });
  }

  /**
   * Modifies a color by scaling the value of a specific property by a percentage.
   * This is an alias to change() with options.scale = true
   * @param {colorProperty} prop - The color property that will be changed.
   * @param {(string|number)} value - The percentage that will be multiplied to the previous value of the property.
   * @returns {Couleur} The modified color.
   */
  scale(prop, value) {
    return this.change(prop, value, { action: 'scale' });
  }

  /** @returns {Couleur} The complementary color. */
  complement() { return this.change('h', 180); }

  /** @returns {Couleur} The inverse color. */
  negative() { return new Couleur(`rgb(${255 * (1 - this.r)}, ${255 * (1 - this.g)}, ${255 * (1 - this.b)}, ${this.a})`); }
  invert() { return this.negative(); }

  /** @returns {Couleur} The shade of grey of the color. */
  greyscale() {
    const L = 255 * this.replace('a', 1).luminance;
    return new Couleur(`rgb(${L}, ${L}, ${L}, ${this.a})`);
  }
  grayscale() { return this.greyscale(); }

  /** @returns {Couleur} The sepia tone of the color. */
  sepia() {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${this.a})`);
  }


  /* Color blending */


  /**
   * Blends two colors together.
   * @param {color} backgroundColor - Background color.
   * @param {color} overlayColor - Overlay color.
   * @param {number|string|null} alpha - Alpha value that will replace overlay's.
   * @returns {Couleur} The resulting color.
   */
   static blend(backgroundColor, overlayColor, alpha) {
    const background = Couleur.makeInstance(backgroundColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null) // if alpha isn't null or undefined
      overlay.a = Couleur.parse(alpha, 'a');

    if (overlay.a === 0) return background;
    else if (overlay.a === 1) return overlay;

    const a = overlay.a + background.a * (1 - overlay.a);
    const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
    const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
    const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
    return new Couleur([r, g, b, a]);
  }
  
  /**
   * Blends colors together, in the order they were given.
   * @param  {...color} colors - Colors to blend.
   * @returns {Couleur} The resulting color.
   */
  static blendAll(...colors) {
    if (colors.length < 2) throw `You need at least 2 colors to blend`;
    const background = colors.shift();
    const overlay = colors.shift();
    
    const mix = Couleur.blend(background, overlay);

    if (colors.length === 0) return mix;
    else                       return Couleur.blendAll(mix, ...colors);
  }

  /** @see Couleur.blend - Non-static version. */
  blend(overlayColor, alpha) { return Couleur.blend(this, overlayColor, alpha); }

  /** @see Couleur.blendAll - Non-static version. */
  blendAll(...colors) { return Couleur.blendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with background unknown.
   * @param  {color} mixColor - The result of the blend.
   * @param {color} overlayColor - Color that was mixed with background to create mix.
   * @returns {?Couleur} The background that is solution to the equation, if it has one.
   * @throws if the equation has an infinite amount of solutions.
   */
   static unblend(mixColor, overlayColor, alpha) {
    const mix = Couleur.makeInstance(mixColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null) // if alpha isn't null or undefined
      overlay.a = Couleur.parse(alpha, 'a');

    if (overlay.a === 1) {
      throw `Overlay color ${JSON.stringify(overlay.rgb)} isn't transparent, so the background it was blended onto could have been any color`;
    }
    else if (overlay.a === 0)           return mix;
    else {
      if (mix.a < overlay.a)            return null;
      else if (mix.a === overlay.a) {
        if (Couleur.same(mix, overlay)) return new Couleur([0, 0, 0, 0]);
        else                            return null;
      }
      else {
        const a = (mix.a - overlay.a) / (1 - overlay.a);
        const r = (mix.r * mix.a - overlay.r * overlay.a) / (a * (1 - overlay.a));
        const g = (mix.g * mix.a - overlay.g * overlay.a) / (a * (1 - overlay.a));
        const b = (mix.b * mix.a - overlay.b * overlay.a) / (a * (1 - overlay.a));
        if (!Couleur.inGamut('srgb', [r, g, b], 'srgb', { tolerance: 1/255 })) return null;
        const clampedValues = Couleur.toGamut('srgb', [r, g, b], 'srgb', { method: 'naive' });
        return new Couleur([...clampedValues, a]);
      }
    }
  }

  /**
   * Solves the equation mix = blendAll(background, ...overlays) with background unknown.
   * @param  {...color} colors - Colors to unblend.
   * @returns {?Couleur} The solution to the equation, if it has one.
   * @throws if the equation has an infinite amount of solutions.
   */
  static unblendAll(...colors) {
    if (colors.length < 2) throw `You need at least 2 colors to unblend`;
    const mix = colors.shift();
    const overlay = colors.shift();
    
    const background = Couleur.unblend(mix, overlay);

    if (colors.length === 0) return background;
    else                     return Couleur.unblendAll(background, ...colors);
  }

  /** @see Couleur.unblend - Non-static version. */
  unblend(overlayColor, alpha) { return Couleur.unblend(this, overlayColor, alpha); }
  
  /** @see Couleur.unblendAll - Non-static version. */
  unblendAll(...colors) { return Couleur.unblendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with overlay unknown.
   * @param {color} backgroundColor - The background color.
   * @param {color} mixColor - The result of the blend.
   * @param {?number|number[]} alpha - The alpha value(s) you want the solution(s) to have.
   * @param {object} options
   * @param {boolean} options.ignoreTransparent - Whether to return the color 'transparent' when it's a solution.
   * @returns {(Couleur|Couleur[]|null)} The solution(s) to the equation.
   */
  static whatToBlend(backgroundColor, mixColor, alphas = [], { ignoreTransparent = false } = {}) {
    const background = Couleur.makeInstance(backgroundColor);
    const mix = Couleur.makeInstance(mixColor);
    let overlays = [];

    const calculateSolution = a => {
      const r = (mix.r * mix.a - background.r * background.a * (1 - a)) / a;
      const g = (mix.g * mix.a - background.g * background.a * (1 - a)) / a;
      const b = (mix.b * mix.a - background.b * background.a * (1 - a)) / a;
      if (!Couleur.inGamut('srgb', [r, g, b], 'srgb', { tolerance: 1/255 })) throw `This color doesn't exist`;
      const clampedValues = Couleur.toGamut('srgb', [r, g, b], 'srgb', { method: 'naive' });
      return new Couleur([...clampedValues, a]);
    };

    const requestedAlphas = [alphas].flat();
    const computedAlphas = requestedAlphas.length > 0 ? requestedAlphas.filter(a => a > 0 && a < 1) // can't divide by 0
                                                      : Array.from({ length: 9 }, (v, k) => (k + 1) / 10);

    // The mix can't have lower opacity than the background
    if (mix.a < background.a)      return null;
    // If the mix is more opaque than the background...
    else if (mix.a > background.a) {
      // If the background is partially transparent and the mix is opaque, the mix is the only solution
      // (any partially transparent overlay would have mixed with the background to make a partially transparent mix)
      if (mix.a === 1)             overlays.push(mix);
      // If the background is totally transparent and the mix is partially transparent, the mix is the only solution
      // (any other color mixed with nothing would make itself)
      else if (background.a === 0) overlays.push(mix);
      // If the background is partially transparent and the mis is too, but more opaque, then there exists a unique solution
      else {
        const a = (mix.a - background.a) / (1 - background.a);
        try { overlays.push(calculateSolution(a)); }
        catch (error) { return null; }
      }
    }
    // If the mix is as opaque as the background...
    else if (mix.a === background.a) {
      // If both the mix and the background are totally transparent, 'transparent' is the only solution
      // (any other color would have raised the opacity)
      if (mix.a === 0) overlays.push(new Couleur('transparent'));
      // If both the mix and the background are partially transparent with the same opacity, then
      // if they're the same color, 'transparent' is solution. If not, there is no solution.
      else if (mix.a < 1) {
        if (Couleur.same(mix, background)) overlays.push(new Couleur('transparent'));
        else                               return null;
      }
      // If both mix and background are totally opaque, then there is an infinity of solutions
      // (one per alpha value from 0 (included only if same color) to 1). Let's calculate the ones
      // whose alpha value was passed in the alphas argument, or those in [0, 0.1, 0.2, ..., 0.9, 1]
      // if alphas === null.
      else {
        if (Couleur.same(mix, background)) overlays.push(new Couleur('transparent'));
        for (const a of computedAlphas) {
          try { overlays.push(calculateSolution(a)); }
          catch (error) { continue; }
        }
        overlays.push(mix);
      }
    }

    let result = requestedAlphas.length > 0 ? overlays.filter(c => requestedAlphas.includes(c.a))
                                              : overlays;
    if (ignoreTransparent) result = result.filter(a => a > 0);

    return result.length === 0 ? null
         : result.length === 1 ? result[0]
         : result;
  }

  /** @see Couleur.whatToBlend - Non-static version. */
  whatToBlend(mixColor, alphas) { return Couleur.whatToBlend(this, mixColor, alphas); }


  /* Color comparison */


  /**
   * Computes the contrast between two colors as defined by WCAG2 or 3.
   * @param {color} textColor - First color (for text over a background, this is the text color).
   * @param {color} backgroundColor - Second color (for text over a background, this is the background color).
   * @param {object} options
   * @param {string} options.method - Whether to use the new APCA or the old WCAG2 method.
   * @returns {number} Contrast between the two colors.
   */
  static contrast(textColor, backgroundColor, { method = 'WCAG2' } = {}) {
    const background = Couleur.makeInstance(backgroundColor);
    if (background.a < 1) throw `The contrast with a transparent background color would be meaningless`;
    let text = Couleur.makeInstance(textColor);

    // If the text is transparent, blend it to the background to get its actual visible color
    if (text.a < 1) text = Couleur.blend(background, text);

    switch (method.toLowerCase()) {
      case 'wcag3': case 'sapc': case 'apca':
        return APCA(text.values, background.values);
      case 'wcag2':
      default:
        return WCAG2(text.values, background.values);
    }
  }

  /** @see Couleur.contrast - Non-static version. */
  contrast(backgroundColor, options) {
    return Couleur.contrast(this, backgroundColor, options);
  }


  /** 
   * Determines which color scheme ('light' or 'dark') would lead to a better contrast with the color.
   * The 'light' color scheme means a light background with dark text.
   * The 'dark' color scheme means a dark background with light text.
   * @param {('background'|'text')} as - Whether the color is the background or the text color.
   * @returns {('light'|'dark')}
   */
  bestColorScheme(as = 'background') {
    if (as === 'text') {
      const Cblack = Math.abs(Couleur.contrast(this, 'black', { method: 'apca' }));
      const Cwhite = Math.abs(Couleur.contrast(this, 'white', { method: 'apca' }));
      return (Cblack >= Cwhite) ? 'dark' : 'light';
    } else {
      const Cblack = Math.abs(Couleur.contrast('black', this, { method: 'apca' }));
      const Cwhite = Math.abs(Couleur.contrast('white', this, { method: 'apca' }));
      return (Cblack >= Cwhite) ? 'light' : 'dark';
    }
  }


  /**
   * Modifies the CIE lightness of a color to give it better contrast with a background color.
   * @param {color} backgroundColor - The color with which contrast will be measured and improved.
   * @param {number} desiredContrast - The contrast value to reach.
   * @param {Object} options
   * @param {boolean?} options.lower - Whether contrast should be lowered if it's already bigger than desiredContrast.
   *                                   If true, and contrast is higher from the start, it will be lowered until it reaches desiredContrast.
   *                                   If false, and contrast is higher from the start, nothing will be done.
   * @param {string?} options.colorScheme - Whether the color should be darker than the background color (colorScheme = 'light')
   *                                        or lighter than the background color (colorScheme = 'dark').
   *                                        If null, the starting color scheme will be preserved (i.e. if the color starts darker
   *                                        than the background color, it will stay darker.)
   * @param {string?} options.method - The method to use to compute the contrast.
   * @returns {Couleur} The modified color which verifies Couleur.contrast(color, referenceColor) === desiredContrast.
   */
  improveContrast(backgroundColor, desiredContrast, { lower = false, colorScheme = null, method = 'WCAG2' } = {}) {
    const background = Couleur.makeInstance(backgroundColor);
    const backgroundLab = background.valuesTo('lab');
    const movingLab = this.valuesTo('lab');

    // Let's measure the initial contrast
    // and decide if we want it to go up or down.
    let startContrast = Couleur.contrast(this, background, method);
    let directionContrast;
    if (startContrast > desiredContrast)      directionContrast = -1;
    else if (startContrast < desiredContrast) directionContrast = 1;
    else                                      directionContrast = 0;
    // If the contrast is already higher than desired, and lowering it is not allowed, return the color as is.
    if ((directionContrast < 0 && lower === false) || (directionContrast === 0)) return this;

    // Let's detect the color scheme if it isn't given.
    const _colorScheme = colorScheme || ((backgroundLab[0] < movingLab[0]) ? 'dark' : 'light');

    // Let's measure the contrast of the background with black and white to know if
    // desiredContrast can be reached by lowering or raising the color's CIE lightness.
    const cBlack = Couleur.contrast(background, 'black', method);
    const cWhite = Couleur.contrast(background, 'white', method);
    const isPossible = {
      lowering: (directionContrast > 0) ? Math.abs(cBlack) >= desiredContrast : Math.abs(cBlack) <= desiredContrast,
      raising: (directionContrast > 0) ? Math.abs(cWhite) >= desiredContrast : Math.abs(cWhite) <= desiredContrast
    };

    // Let's decide which direction to move the lightness in.
    let directionCIEL;
    if (isPossible.lowering && !isPossible.raising)      directionCIEL = -1;
    else if (isPossible.raising && !isPossible.lowering) directionCIEL = 1;
    // If desiredContrast can not be reached, return white or black — the one that fits the color scheme.
    else if (!isPossible.raising && !isPossible.lowering) {
      if (_colorScheme === 'light') return new Couleur('black');
      else                          return new Couleur('white');
    }
    // If desiredContrast can be reached in both directions
    else {
      // If the background is light and we need to raise the contrast, lower the lightness.
      if (_colorScheme === 'light' && directionContrast > 0)      directionCIEL = -1;
      // If the background is light and we need to lower the contrast, raise the lightness.
      else if (_colorScheme === 'light' && directionContrast < 0) directionCIEL = 1;
      // If the background is dark and we need to raise the contrast, raise the lightness.
      else if (_colorScheme === 'dark' && directionContrast > 0)  directionCIEL = 1;
      // If the background is dark and we need to lower the contrast, lower the lightness.
      else                                                       directionCIEL = -1;
    }

    const τ = .0001;
    let CIELmin = (directionCIEL > 0) ? movingLab[0] : 0;
    let CIELmax = (directionCIEL > 0) ? 1 : movingLab[0];

    while (CIELmax - CIELmin > τ) {
      // Let's try to raise contrast by increasing or reducing CIE lightness.
      const ciel = (CIELmin + CIELmax) / 2;
      const newValues = movingLab; newValues[0] = ciel;
      const newContrast = Couleur.contrast(Couleur.convert('lab', 'srgb', newValues), background, method);

      // If the new contrast hasn't gone over its desired value
      const condition = (directionContrast > 0) ? (Math.abs(newContrast) < desiredContrast) : (Math.abs(newContrast) > desiredContrast);
      if (condition) {
        if (directionCIEL > 0) CIELmin = ciel;
        else                   CIELmax = ciel;
      }
      // If we overshot and the contrast moved further than we want it to
      else {
        if (directionCIEL > 0) CIELmax = ciel;
        else                   CIELmin = ciel;
      }

      movingLab[0] = ciel;
    }

    let result = new Couleur(Couleur.convert('lab', 'srgb', movingLab));
    // If the color we find has its contrast slightly below the desired value, push it further.
    if (Math.abs(Couleur.contrast(result, background, method)) < desiredContrast) {
      if (directionCIEL > 0) movingLab[0] = CIELmax;
      else                   movingLab[0] = CIELmin;
    }

    // We're done!
    return new Couleur(Couleur.convert('lab', 'srgb', movingLab));
  }


  /**
   * Computes the distance between two colors.
   * @param {color} color1 
   * @param {color} color2 
   * @param {object} options
   * @param {string} options.method - The method to use to compute the distance.
   * @returns {number} The distance between the two colors in sRGB space.
   */
  static distance(color1, color2, { method = 'CIEDE2000' } = {}) { 
    const colore1 = Couleur.makeInstance(color1);
    const colore2 = Couleur.makeInstance(color2);
    const [lab1, lab2] = [colore1, colore2].map(c => c.valuesTo('lab'));

    switch (method) {
      case 'CIEDE2000':
        return CIEDE2000(lab1, lab2);
      case 'euclidean':
      default: {
        return lab1.reduce((sum, v, k) => sum + (v - lab2[k]) ** 2);
      }
    }
  }

  /** @see Couleur.distance - Non-static version. */
  distance(color, options) { return Couleur.distance(this, color, options); }


  /**
   * Determines if two colors are the same, with a certain tolerance.
   * @param {color} color1 
   * @param {color} color2 
   * @param {number} tolerance - The minimum distance between the two colors to consider them different.
   * @returns {boolean} Whether the two colors are considered the same.
   */
  static same(color1, color2, tolerance = 1) {
    if (Couleur.distance(color1, color2) > tolerance) return false;
    else return true;
  }

  /** @see Couleur.same - Non-static version. */
  same(color) { return Couleur.same(this, color); }


  /* Other functions */


  /**
   * Calculates the intermediate colors a gradient should use to go from one color to another without passing through the "desaturated zone".
   * @param {color} startColor - The starting color of the gradient.
   * @param {color} endColor - The ending color of the gradient.
   * @param {number} steps - The number of steps in the gradient to go from start to end.
   * @param {object|string} spaceID - Color space in which to compute the gradient, or its identifier.
   * @returns {Couleur[]} The array of (steps + 1) colors in the gradient.
   */
  static gradient(startColor, endColor, steps = 5, spaceID = 'lch') {
    const start = Couleur.makeInstance(startColor);
    const end = Couleur.makeInstance(endColor);
    const _steps = Math.max(1, steps);
    const props = [...Couleur.propertiesOf(spaceID), 'a'];
    const space = Couleur.getSpace(spaceID);
    const startValues = [...start.valuesTo(space), start.a];
    const endValues = [...end.valuesTo(space), end.a];

    // Calculate by how much each property will be changed at each steap
    const stepList = props.map((prop, k) => {
      let step;
      switch (prop) {
        case 'h':
        case 'cieh':
        case 'okh':
          // Minimize the distance to travel through hues
          const stepUp = ((endValues[k] - startValues[k]) % 360 + 360) % 360;
          const stepDown = ((startValues[k] - endValues[k]) % 360 + 360) % 360;
          step = ((stepUp <= stepDown) ? stepUp : -stepDown) / _steps;
          break;
        default:
          step = (endValues[k] - startValues[k]) / _steps;
      }
      return step;
    });

    // Calculate all colors of the gradient
    const intermediateColors = [startValues];
    for (let i = 1; i < _steps; i++) {
      let previous = intermediateColors[i - 1];
      let next = props.map((prop, k) => {
        let v = previous[k] + stepList[k];
        if (['h', 'cieh'].includes(prop)) return angleToRange(v);
        else return v;
      });
      const a = next[3];
      next = Couleur.toGamut(space, next.slice(0, 3), space);
      next = [...next, a];
      intermediateColors.push(next);
    }

    return [...intermediateColors.map(c => new Couleur(Couleur.convert(space, 'srgb', c))), end];
  }

  /** @see Couleur.gradient - Non-static version. */
  gradient(color, steps, format) { return Couleur.gradient(this, color, steps, format); }



  /**************/
  /* Color data */
  /**************/

  /**
   * Gets the list of the short names of color properties used in a given CSS format.
   * @param {string} format - Name of the color format.
   * @returns {string[]} Array of color property names.
   */
  static propertiesOf(format) {
    switch(format) {
      case 'rgb': case 'rgba': return ['r', 'g', 'b'];
      case 'hsl': case 'hsla': return ['h', 's', 'l'];
      case 'hwb': case 'hwba': return ['h', 'w', 'bk'];
      case 'lab': case 'laba': return ['ciel', 'ciea', 'cieb'];
      case 'lch': case 'lcha': return ['ciel', 'ciec', 'cieh'];
      case 'oklab':            return ['okl', 'oka', 'okb'];
      case 'oklch':            return ['okl', 'okc', 'okh'];
      default: return [];
    }
  }

  /** @returns {string[]} Array of all color property short names. */
  static get properties() {
    return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
  }

  /** @returns {{id: string, whitepoint: string, prefix: string, otherFormat: string[]}} Supported color spaces. */
  static get colorSpaces() { return colorSpaces; }

  /**
   * Gets a color space.
   * @param {string|object} spaceID - Identifier of a color space, or a color space itself.
   * @returns {object} The corresponding color space object.
   */
  static getSpace(spaceID) {
    if (typeof spaceID.id === 'string') return spaceID;
    const id = spaceID === 'rgb' ? 'srgb'
             : spaceID === 'rgba' ? 'srgb'
             : spaceID === 'hsla' ? 'hsl'
             : spaceID;
    return Couleur.colorSpaces.find(sp => sp.id == id);
  }

  /** @returns {{id: string, syntaxes: RegExp[]}[]} Array of supported syntaxes. */
  static get formats() { return Formats; }

  /** @returns {Object} List of named colors in CSS. */
  static get couleursNommees() { return namedColors; }
}

class Palette {
  /**
   * Creates a palette from a hue.
   * @param {number} hue - The hue of the main color of the palette in OKLAB color space. 
   * @param {function} generator - A function that generates an array of { lightnesses, chroma, hue } objects.
   * @param {object} options
   * @param {string?} options.clampSpace - Color space to which the generated colors will be clamped. Null to disable clamping.
   */
  constructor(hue, generator = () => [], { clampSpace = 'srgb' } = {}) {
    this.colors = []; // Will be an array of arrays of color nuances.
    const colors = generator(hue);

    // Create the nuances of each color.
    for (const color of colors) {
      const nuances = [];
      for (const lightness of color.lightnesses) {
        let rgb = Couleur.convert('oklch', 'srgb', [lightness, color.chroma, color.hue]);
        if (clampSpace != null) rgb = Couleur.toGamut(clampSpace, rgb);
        const newColor = new Couleur(`color(srgb ${rgb.join(' ')})`);
        nuances.push(newColor);
      }
      this.colors.push(nuances);
    }
  }
}

export default Couleur;
export { cssFormats as CSSFormats, Conversions, Palette };
