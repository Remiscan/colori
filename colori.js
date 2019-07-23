export class Couleur {
  constructor(couleur) {
    const format = Couleur.matchSyntax(couleur.trim());
    if (['HEX', 'HEXA'].includes(format.id))
    {
      let r, g, b, a;
      r = format.data[1];
      r = (r.length == 1) ? r + '' + r : r;
      g = format.data[2];
      g = (g.length == 1) ? g + '' + g : g;
      b = format.data[3];
      b = (b.length == 1) ? b + '' + b : b;
      if (format.id === 'HEXA')
      {
        a = format.data[4];
        a = (a.length == 1) ? a + '' + a : a;
      }
      else
        a = 'ff';
      this.r = parseInt(r, 16);
      this.g = parseInt(g, 16);
      this.b = parseInt(b, 16);
      this.a = parseInt(a, 16) / 255;
      this.rgba2hsla();
    }
    else if (['RGB', 'RGBA'].includes(format.id))
    {
      let r, g, b, a;
      r = format.data[1];
      g = format.data[2];
      b = format.data[3];
      // Si les couleurs sont en pourcentage, on les met sur 255
      const percentTo255 = n => {
        let _n = n;
        _n = Number(_n.replace('%', ''));
        _n = Math.round(_n / 100 * 255);
        return _n;
      };
      if (String(r).slice(-1) == '%')
      {
        r = percentTo255(r);
        g = percentTo255(g);
        b = percentTo255(b);
      }
      if (format.id === 'RGBA')
        a = format.data[4];
      else
        a = 1;
      // Si alpha est en pourcentage, on le met sur 1
      if (String(a).slice(-1) == '%')
        a = Number(a.replace('%', '')) / 100;
      this.r = Number(r);
      this.g = Number(g);
      this.b = Number(b);
      this.a = Number(a);
      this.rgba2hsla();
    }
    else if (['HSL', 'HSLA'].includes(format.id))
    {
      let h, s, l, a;
      h = format.data[1];
      if (String(h).slice(-3) == 'rad')
      {
        h = Number(h.replace('rad', ''));
        h = Math.round(h * 180 / Math.PI);
      }
      else if (String(h).slice(-4) == 'turn')
      {
        h = Number(h.replace('turn', ''));
        h = Math.round(h * 360);
      }
      while (h > 360) {
        h -= 360;
      }
      while (h < 0) {
        h += 360;
      }
      const dePercent = n => {
        let _n = n;
        _n = Number(_n.replace('%', ''));
        return _n;
      };
      s = format.data[2];
      s = dePercent(s);
      l = format.data[3];
      l = dePercent(l);
      if (format.id === 'HSLA')
        a = format.data[4];
      else
        a = 1;
      // Si alpha est en pourcentage, on le met sur 1
      if (String(a).slice(-1) == '%')
        a = Number(a.replace('%', '')) / 100;
      this.h = Number(h);
      this.s = Number(s);
      this.l = Number(l);
      this.a = Number(a);
      this.hsla2rgba();
    }
    if (this.a == 1)
    {
      let _nom = Object.keys(Couleur.couleursNommees).find(k => Couleur.couleursNommees[k] == this.hex.replace('#', ''));
      if (typeof _nom === 'undefined')
        this.nom = null;
      else
        this.nom = _nom;
    }
    else
      this.nom = null;
  }

  static matchSyntax(couleur) {
    let resultat = false;
    try {
      boucle:
      for (const format of Couleur.formats) {
        for (const [k, syntaxe] of format.syntaxes.entries()) {
          const result = couleur.match(syntaxe);
          if (result != null && result[0] === couleur)
          {
            if (format.id != 'NAME')
              resultat = {
                id: format.id,
                syntaxe: k,
                data: result
              };
            else if (format.id == 'NAME' && couleur.toLowerCase() in Couleur.couleursNommees)
              resultat = Couleur.matchSyntax('#' + Couleur.couleursNommees[couleur.toLowerCase()]);
            break boucle;
          }
        }
      }

      if (resultat)
        return resultat;
      else
        throw 'Format invalide';
    } catch (e) {
      console.error(e);
    }
  }

  // Utile dans hsla2rgba
  static hueToRgb(t1, t2, h) {
    let _h = h;
    if (_h < 0)
      _h += 6;
    if (_h >= 6)
      _h -= 6;
    if (_h < 1)
      return (t2 - t1) * _h + t1;
    else if (_h < 3)
      return t2;
    else if (_h < 4)
      return (t2 - t1) * (4 - _h) + t1;
    else
      return t1;
  }

  // Ajoute un zéro avant une chaîne d'un seul caractère
  static pad(s) {
    return (s.length < 2) ? '0' + String(s) : s;
  }

  get hexa() {
    const a = Math.round(this.a * 255);
    const valeur = '#'
                 + Couleur.pad(this.r.toString(16))
                 + Couleur.pad(this.g.toString(16))
                 + Couleur.pad(this.b.toString(16))
                 + Couleur.pad(a.toString(16));
    return valeur;
  }

  get hex() {
    const valeur = '#'
                 + Couleur.pad(this.r.toString(16))
                 + Couleur.pad(this.g.toString(16))
                 + Couleur.pad(this.b.toString(16));
    return valeur;
  }

  get rgba() {
    return `rgba(${this.r}, ${this.g}, ${this.b}, ${this.a})`;
  }

  get rgb() {
    return `rgb(${this.r}, ${this.g}, ${this.b})`;
  }

  get hsla() {
    return `hsla(${this.h}, ${this.s}%, ${this.l}%, ${this.a})`;
  }

  get hsl() {
    return `hsl(${this.h}, ${this.s}%, ${this.l}%)`;
  }

  rgba2hsla() {
    let r = this.r;
    let g = this.g;
    let b = this.b;
    r = r / 255;
    g = g / 255;
    b = b / 255;

    let h, s, l;

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const chroma = max - min;

    // Luminosité (l)
    l = (max + min) / 2;

    // Si chrome == 0, la couleur est grise, donc n'a aucune teinte (h) ni saturation (s)
    if (chroma == 0)
      h = 0, s = 0;
    // Sinon, on calcule h et s - cf https://www.w3schools.com/lib/w3color.js
    else
    {
      switch (max) {
        case r:
          h = (g - b) / chroma;
          break;
        case g:
          h = (b - r) / chroma + 2;
          break;
        case b:
          h = (r - g) / chroma + 4;
          break;
      }
      h = 60 * h;
      if (h < 0)
        h += 360;

      if (l < 0.5)
        s = chroma / (2 * l);
      else
        s = chroma / (2 - 2 * l);
    }

    h = Math.round(h);

    s = s * 100;
    s = Math.round(s);

    l = l * 100;
    l = Math.round(l);

    this.h = Number(h);
    this.s = Number(s);
    this.l = Number(l);
  }

  hsla2rgba() {
    let h = this.h;
    let s = this.s;
    let l = this.l;
    let r, g, b, t1, t2;

    h = h / 60;
    s = s / 100;
    l = l / 100;

    if (l <= 0.5)
      t2 = l * (s + 1);
    else
      t2 = l + s - (l * s);

    t1 = 2 * l - t2;

    r = Math.round(255 * Couleur.hueToRgb(t1, t2, h + 2));
    g = Math.round(255 * Couleur.hueToRgb(t1, t2, h));
    b = Math.round(255 * Couleur.hueToRgb(t1, t2, h - 2));

    this.r = Number(r);
    this.g = Number(g);
    this.b = Number(b);
  }

  // Fusionne la couleur et une couleur de fond "background"
  blend(background = new Couleur('white'))
  {
    if (background.a < 1)
      throw 'La couleur de fond ne doit pas être transparente';
    const r = Math.round(this.a * this.r + (1 - this.a) * background.r);
    const g = Math.round(this.a * this.g + (1 - this.a) * background.g);
    const b = Math.round(this.a * this.b + (1 - this.a) * background.b);
    return new Couleur(`rgb(${r}, ${g}, ${b})`);
  }

  // Vérifie si un texte blanc ou noir aurait meilleur contraste avec cette couleur
  meilleurContraste() {
    let couleur = this;
    if (this.a < 1)
      couleur = this.blend(new Couleur('white'));
    let arr = [couleur.r, couleur.g, couleur.b];
    for (let i = 0; i <= 2; i++) {
      let e = arr[i];
      e = e / 255;
      if (e <= 0.03928)
        e = e / 12.92;
      else
        e = Math.pow((e + 0.055) / 1.055, 2.4);
      arr[i] = e;
    }
    const [r, g, b] = arr;
    const L = 0.2126 * r + 0.7152 * g + 0.0722 * b; // luminance de la couleur entrée
    const LB = 1; // luminance du blanc
    const LN = 0; // luminance du noir
    // On calcule le contraste entre couleur claire / couleur sombre :
    const contrastes = [
      (L + 0.05) / (LN + 0.05), // contraste entre la couleur entrée et le noir
      (LB + 0.05) / (L + 0.05)  // contraste entre le blanc et la couleur entrée
    ];
    if (contrastes[0] > contrastes[1]) // contraste plus fort avec le noir
      return 0; // le texte noir ira mieux sur le fond de couleur
    else
      return 1; // le texte blanc ira mieux sur le fond de couleur
  }

  // Change une propriété d'une couleur
  change(propriete, valeur, remplace = false) {
    let nouvelleCouleur = new Couleur(`rgba(${this.r}, ${this.g}, ${this.b}, ${this.a})`);
    if (['r', 'g', 'b'].includes(propriete))
    {
      nouvelleCouleur[propriete] = Math.max(0, Math.min(255, (remplace ? 0 : nouvelleCouleur[propriete]) + valeur));
      return new Couleur(nouvelleCouleur.rgba);
    }
    else if (['h'].includes(propriete))
    {
      nouvelleCouleur[propriete] = (remplace ? 0 : nouvelleCouleur[propriete]) + valeur;
      return new Couleur(nouvelleCouleur.hsla);
    }
    else if (['s', 'l'].includes(propriete))
    {
      nouvelleCouleur[propriete] = Math.max(0, Math.min(100, (remplace ? 0 : nouvelleCouleur[propriete]) + valeur));
      return new Couleur(nouvelleCouleur.hsla);
    }
    else if (['a'].includes(propriete))
    {
      nouvelleCouleur[propriete] = Math.max(0, Math.min(1, (remplace ? 0 : nouvelleCouleur[propriete]) + valeur));
      return new Couleur(nouvelleCouleur.hsla);
    }
    else
      return new Couleur(nouvelleCouleur.rgba);
  }

  static get formats() {
    return [
      {
        id: 'HEX',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abc ou #ABC
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbcc ou #AABBCC
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'HEXA',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abcd ou #ABCD
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbccdd ou #AABBCCDD
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'RGB',
        prefix: 'rgb(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgb(255, 255, 255) ou rgb(255,255,255)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\)?$/,
          // rgb(100%, 100%, 100%) ou rgb(100%,100%,100%)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          //rgb(255 255 255)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\)?$/,
          // rgb(100% 100% 100%)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/
        ]
      }, {
        id: 'RGBA',
        prefix: 'rgba(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgba(255, 255, 255, .5) (espaces optionnels)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]|0?\.[0-9]+)\)?$/,
          // rgba(255, 255, 255, 50%) (espaces optionnels)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // rgba(255 255 255 / 50%)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // rgba(255 255 255 / .5)
          /^rgba?\(([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) ([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5]) \/ ([01]|0?\.[0-9]+)\)?$/,
          // rgba(100%, 100%, 100%, .5) (espaces optionnels)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/,
          // rgba(100%, 100%, 100%, 50%) (espaces optionnels)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // rgba(100% 100% 100% / 50%)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // rgba(100% 100% 100% / .5)
          /^rgba?\(([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/
        ]
      }, {
        id: 'HSL',
        prefix: 'hsl(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsl(360, 100%, 100%) ou  hsl(360deg, 100%, 100%) (espaces optionnels)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsl(360 100% 100%) ou hsl(360deg 100% 100%)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsl(1.25rad, 100%, 100%) (espaces optionnels)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsl(1.25rad 100% 100%)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsl(.25turn, 100%, 100%) (espaces optionnels)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsl(.25turn 100% 100%)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%)\)?$/
        ]
      }, {
        id: 'HSLA',
        prefix: 'hsla(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsla(360, 100%, 100%, .5) ou  hsl(360deg, 100%, 100%, .5) (espaces optionnels)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/,
          // hsla(360 100% 100% / .5) ou hsl(360deg 100% 100% / .5)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/,
          // hsla(1.25rad, 100%, 100%, .5) (espaces optionnels)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/,
          // hsla(1.25rad 100% 100% / .5)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/,
          // hsla(.25turn, 100%, 100%, .5) (espaces optionnels)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([01]|0?\.[0-9]+)\)?$/,
          // hsla(.25turn 100% 100% / .5)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([01]|0?\.[0-9]+)\)?$/,
          // hsla(360, 100%, 100%, 50%) ou  hsl(360deg, 100%, 100%, 50%) (espaces optionnels)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)?, ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsla(360 100% 100% / 50%) ou hsl(360deg 100% 100% / 50%)
          /^hsla?\(([0-2]?[0-9]?[0-9]|3[0-5][0-9]|360)(?:deg)? ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsla(1.25rad, 100%, 100%, 50%) (espaces optionnels)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsla(1.25rad 100% 100% / 50%)
          /^hsla?\(((?:(?:[0-9]+)(?:\.[0-9]+)?|(?:[0-9]+)?(?:\.[0-9]+))(?:rad)) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsla(.25turn, 100%, 100%, 50%) (espaces optionnels)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%), ?([0]?[0-9]?[0-9]%|100%)\)?$/,
          // hsla(.25turn 100% 100% / 50%)
          /^hsla?\(((?:[01]|0?\.[0-9]+)turn) ([0]?[0-9]?[0-9]%|100%) ([0]?[0-9]?[0-9]%|100%) \/ ([0]?[0-9]?[0-9]%|100%)\)?$/
        ]
      }, {
        id: 'NAME',
        prefix: '',
        separator: '',
        suffix: '',
        syntaxes: [
          // white ou WHITE ou WhiTe
          /^[A-Za-z]+$/
        ]
      }
    ];
  }

  static get couleursNommees() {
    return {
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
  }
}