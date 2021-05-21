// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



let entree;
let lastTry;



/////////////////////////////////////////////////////
// Update the interface with the newly detected color
export async function updateCouleur(couleur, delai = 100, adaptPage = true) {
  const thisTry = Date.now();
  lastTry = thisTry;

  await new Promise(resolve => setTimeout(resolve, delai));
  if (lastTry != thisTry) return;

  entree = interpreterCouleur(couleur);
  if (entree == null) return;

  // On colore l'interface selon la couleur obtenue
  try {
    if (adaptPage) colorInterface(entree);

    // Peuplage des données de la couleur dans la démo
    populateColorData(entree);
  }
  catch(error) {
    console.error(error);
  }
}



///////////////////////////////////////////////////////////////////////
// Reads the input content and detects colors and their applied methods
export function interpreterCouleur(couleur)
{
  // RegExp du séparateur entre arguments : virgule puis espace(s) optionnel(s)
  const vSep = '\\,(?: +)?';
  // RegExp des options d'une méthode
  const vOpt = 'true|false|\\{(?:.+)?\\}';
  // RegExp des arguments d'une méthode qui prend un nombre ou pourcentage et des options
  const vNPandOptions = `(${Couleur.vNP})(?:${vSep}(${vOpt}))?`;
  // RegExp des arguments d'une méthode qui prend un nom de propriété, une valeur (en pourcentage) et des options
  const vPropNPandOptions = `(${Couleur.vProp})${vSep}(${Couleur.vNP})(?:${vSep}(${vOpt}))?`;

  const acceptedMethods = [
    {
      name: 'change',
      args: new RegExp(vPropNPandOptions)
    }, {
      name: 'replace',
      args: new RegExp(vPropNPandOptions)
    }, {
      name: 'scale',
      args: new RegExp(vPropNPandOptions)
    }, {
      name: 'complement',
      args: null
    }, {
      name: 'negative',
      args: null
    }, {
      name: 'invert',
      args: null
    }, {
      name: 'darken',
      args: new RegExp(vNPandOptions)
    }, {
      name: 'lighten',
      args: new RegExp(vNPandOptions)
    }, {
      name: 'desaturate',
      args: new RegExp(vNPandOptions)
    }, {
      name: 'saturate',
      args: new RegExp(vNPandOptions)
    }, {
      name: 'greyscale',
      args: null
    }, {
      name: 'grayscale',
      args: null
    }
  ];
  const recursiveMethods = [
    {
      name: 'blend',
      args: [
        new RegExp(`^(.+)${vSep}(${Couleur.vNP})$`),
        new RegExp(`^(.+)$`)
      ]
    }, {
      name: 'betterContrast',
      args: [
        new RegExp(`^(.+)${vSep}(${Couleur.vNum})${vSep}(${Couleur.vNum})$`),
        new RegExp(`^(.+)${vSep}(${Couleur.vNum})$`)
      ]
    }, {
      name: 'contrast',
      args: new RegExp(`^(.+)$`),
      resultIsNoColor: true
    }
  ];

  let value = couleur;
  
  const methodsRegex = acceptedMethods.map(method => method.name).join('|');
  const regex = new RegExp(`(.+)\\.(${methodsRegex})\\((.+)?\\)$`);
  const recursiveMethodsRegex = recursiveMethods.map(method => method.name).join('|');
  const recursiveRegex = new RegExp(`^((?:[^\\(\\)]+(?:\\(.+\\))?)+)\\.(${recursiveMethodsRegex})\\((.+)\\)$`);

  // On cherche la liste de méthodes successivement appliquées à la couleur
  let methods = [];
  while (true) {
    // On vérifie si la valeur de l'input est de la forme couleur.methodeRecursive() ou couleur.methodeNonRecursive()
    const match = value.match(recursiveRegex) || value.match(regex);

    // Si la valeur de l'input ne vérifie couleur.methode() pour aucune methode de acceptedMethods,
    // on passe à l'étape suivante (vérifier si la valeur de l'input est une expression valide de couleur)
    if (match === null) break;

    // On détermine quelle méthode est appliquée à la couleur
    let method;
    method = recursiveMethods[recursiveMethods.findIndex(m => m.name == match[2])];
    const isRecursive = (method != null);
    method = method || acceptedMethods[acceptedMethods.findIndex(m => m.name == match[2])];

    // On détermine les arguments passés à la méthode
    let args = [];
    for (const regex of [method.args].flat()) {
      const temp = (match[3] || '').match(regex);
      if (temp == null) continue;
      args = [...(temp || [])].slice(1);
      break;
    }
    //console.log('args:', args);

    // On ajoute cette méthode à la liste de méthodes appliquées à la couleur
    const nextMethod = {
      name: method.name,
      args: args,
      recursive: isRecursive,
      resultIsNoColor: method.resultIsNoColor
    };
    value = match[1];

    methods.push(nextMethod);
  }
  //console.log('Methods:', methods);

  // Si la valeur restante de l'input est une expression valide de couleur, on pourra continuer.
  // Sinon, la valeur est invalide.
  let _couleur;
  try {
    _couleur = new Couleur(value);

    methods.reverse();
    let coul = _couleur;
    let coul2 = null;
    for (const method of methods) {
      if (method.recursive) {
        try {
          coul2 = interpreterCouleur(method.args[0]);
          method.args[0] = coul2;
        }
        catch (error) { throw 'Couleur en argument invalide'; }
      }
      else coul2 = null;

      if (method.resultIsNoColor) {
        try {
          const res = Couleur.prototype[method.name].call(coul, ...method.args.map(arg => arg === 'true' ? true : arg === 'false' ? false : arg));
          console.log(`${coul.name || coul.hsl}.${method.name}(${method.args.map(arg => (arg instanceof Couleur) ? (arg.name || arg.hsl) : arg).join(', ')}) = ${res}`);
        } catch (error) {}
      } else {
        try {
            coul = Couleur.prototype[method.name].call(coul, ...method.args.map(arg => arg === 'true' ? true : arg === 'false' ? false : arg));
          }
        catch (error) { console.error(error); }
      }
    }

    _couleur = coul;
    return _couleur;
  }
  catch (error) {
    // La valeur de l'input est invalide, ne rien faire.
    return null;
  }
}



///////////////////////
// Colors the interface
export function colorInterface(couleur = entree, fixContrast = true) {
  const element = document.documentElement;
  element.style.setProperty('--user-color', couleur.rgb);
  element.style.setProperty('--user-hue', Math.round(couleur.h * 360));
  element.style.setProperty('--user-saturation', Math.round(couleur.s * 100) + '%');

  const themes = ['light', 'dark'];
  let cssLight, cssDark;
  for (const theme of themes) {
    // Calcul des couleurs du body et des sections selon le contraste de la couleur d'entrée
    let bodyColor, sectionColor;
    let css = '';
    if (theme == 'light') {
      sectionColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${Math.round(couleur.s * 100)}%, 80%)`);
      bodyColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${Math.round(couleur.s * 100)}%, 70%)`);
    } else if (theme == 'dark') {
      sectionColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${0.2 * Math.round(couleur.s * 100)}%, 20%)`);
      bodyColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${0.2 * Math.round(couleur.s * 100)}%, 10%)`);
    }

    if (fixContrast)
      [bodyColor, sectionColor] = betterContrast(bodyColor, sectionColor, 1.2, true);
    
    if (document.documentElement.resolvedTheme == theme)
      document.querySelector('meta[name=theme-color]').content = bodyColor.hsl;

    // Calcul de la couleur des liens
    let linkColor;
    if (theme == 'light')
      linkColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${Math.round(couleur.s * 100)}%, 30%)`);
    else if (theme == 'dark')
      linkColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${0.6 * Math.round(couleur.s * 100)}%, 80%)`);

    if (fixContrast)
      linkColor = betterContrast(linkColor, sectionColor, 4.5);

    // Calcul de la couleur du fond de la démo
    let frameOverlay = new Couleur('rgba(0, 0, 0, .8)');
    //let _couleur = couleur.change('a', '1', true);
    const white = new Couleur('white');
    let _couleur = white.blend(`rgba(0, 0, 0, ${frameOverlay.a})`).blend(couleur);
    let frameColor = Couleur.blend(sectionColor, frameOverlay);
    if (fixContrast)
      frameColor = betterContrast(frameColor, _couleur, 1.2);

    let miniFrameColor = frameColor;
    if (fixContrast)
      miniFrameColor = betterContrast(miniFrameColor, _couleur, 1.8);
    css += `
      --body-color: ${bodyColor.hsl};
      --section-color: ${sectionColor.hsl};
      --link-color: ${linkColor.hsl};
      --frame-color: ${frameColor.hsl};
      --frame-color-mini: ${miniFrameColor.hsl};
    `;

    // Calcul de la coloration syntaxique selon le contraste
    const steps = ['-90', '+45', '-45', '+135'];
    const tokenTypes = ['number', 'string', 'operator', 'keyword'];
    steps.forEach((e, k) => {
      let tokenColor = new Couleur('hsl(' + Math.round(couleur.h * 360) + ', 70%, 60%)');
      tokenColor = tokenColor.change('h', steps[k]);
      if (fixContrast)
        tokenColor = betterContrast(tokenColor, frameColor, 5);
      css += `--token-${tokenTypes[k]} : ${tokenColor.hsl};
      `;
    });

    if (theme == 'dark') cssDark = css;
    else                 cssLight = css;
  }

  const style = document.getElementById('theme-variables');
  style.innerHTML = `
    :root {
      ${cssLight}
    }

    @media (prefers-color-scheme: light) {
      :root {
        ${cssLight}
      }
    }

    @media (prefers-color-scheme: dark) {
      :root {
        ${cssDark}
      }
    }

    :root[data-theme="light"] {
      ${cssLight}
    }

    :root[data-theme="dark"] {
      ${cssDark}
    }
  `;
}



//////////////////////////////////////////////////////
// Adds data about the selected color to the interface
export function populateColorData(couleur) {
  const objet = document.querySelector('#objet>pre>code');
  objet.innerHTML = JSON.stringify(couleur, null, 2);
  Prism.highlightElement(objet);

  let code;

  code = document.querySelector('.hex>.format-donnee>code');
  code.innerHTML = couleur.hex;
  Prism.highlightElement(code);

  code = document.querySelector('.rgb>.format-donnee>code');
  code.innerHTML = couleur.rgb;
  Prism.highlightElement(code);

  code = document.querySelector('.hsl>.format-donnee>code');
  code.innerHTML = couleur.hsl;
  Prism.highlightElement(code);

  code = document.querySelector('.hwb>.format-donnee>code');
  code.innerHTML = couleur.hwb;
  Prism.highlightElement(code);

  code = document.querySelector('.lab>.format-donnee>code');
  code.innerHTML = couleur.lab;
  Prism.highlightElement(code);

  code = document.querySelector('.lch>.format-donnee>code');
  code.innerHTML = couleur.lch;
  Prism.highlightElement(code);

  code = document.querySelector('.name>.format-donnee>code');
  if (couleur.name == null)
  {
    document.querySelector('.name').classList.remove('oui');
    code.innerHTML = '';
  }
  else
  {
    document.querySelector('.name').classList.add('oui');
    code.innerHTML = couleur.name;
  }
  Prism.highlightElement(code);

  const champ = document.getElementById('entree');
  champ.placeholder = couleur.name || couleur.hex;

  document.querySelector('.demo-conteneur').classList.add('calced');
}



///////////////////////////////////////////////////////////////////////////////
// Changes a color to augment its contrast with another color above a threshold
function betterContrast(_couleur1, _couleur2, threshold, adaptSecondColorToo = false) {
  let couleur1, couleur2;
  let contrast;
  
  const init = () => {
    couleur1 = _couleur1;
    if (!(couleur1 instanceof Couleur)) {
      try {
        couleur1 = new Couleur(_couleur1);
      }
      catch(error) {
        throw 'First argument should be an instance of the Couleur class, or a valid color string';
      }
    }

    couleur2 = _couleur2;
    if (!(couleur2 instanceof Couleur)) {
      try {
        couleur2 = new Couleur(_couleur2);
      }
      catch(error) {
        throw 'Second argument should be an instance of the Couleur class, or a valid color string';
      }
    }

    contrast = couleur1.contrast(couleur2);
  }

  init();

  let lower = 'bk', higher = 'w';
  let initialLightness = couleur2.l;
  while (contrast < threshold)
  {
    couleur1 = couleur1.change(lower, '-5%')
                       .change(higher, '+5%');
    if (adaptSecondColorToo)
      couleur2 = couleur1.replace('l', `${initialLightness * 100}%`);
    if (couleur1[higher] > 0.95 && couleur1[lower] < 0.05)
      break;
    const newContrast = Couleur.contrast(couleur1, couleur2);

    // If we're going the wrong way, reverse
    if (newContrast < contrast && lower == 'bk') {
      lower = 'w';
      higher = 'bk';
      init();
    }
    else contrast = newContrast;
  }

  if (adaptSecondColorToo)
    return [couleur1, couleur2];
  else
    return couleur1;
}