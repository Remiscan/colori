// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '../colori.js';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



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
      args: new RegExp(`^(.+)${vSep}(${Couleur.vNP})?|(.+)$`)
    }
  ];

  let done = false;
  let value = couleur;
  let methods = [];
  const methodsRegex = acceptedMethods.map(method => method.name).join('|');
  const regex = new RegExp(`(.+)\\.(${methodsRegex})\\((.+)?\\)$`);
  const recursiveMethodsRegex = recursiveMethods.map(method => method.name).join('|');
  const recursiveRegex = new RegExp(`^((?:[^\\(\\)]+(?:\\(.+\\))?)+)\\.(${recursiveMethodsRegex})\\((.+)\\)$`);

  while (true) {
    let nextMethod = null;

    // On vérifie si la valeur de l'input vérifie couleur.methodeRecursive()
    const match = value.match(recursiveRegex);
    if (match !== null) {
      const method = recursiveMethods[recursiveMethods.findIndex(method => method.name == match[2])];
      const args = Array.from((match[3] || '').match(method.args) || []);
      console.log(args);

      const _couleur = args[1] || args[3];
      const alpha = args[1] ? args[2] : null;

      nextMethod = {
        name: method.name,
        args: [_couleur, alpha],
        recursive: true
      };
      value = match[1];
    }

    else {
      // On vérifie si la valeur de l'input vérifie couleur.methodeNonRecursive()
      const match = value.match(regex);
      if (match !== null) {
        const method = acceptedMethods[acceptedMethods.findIndex(method => method.name == match[2])];
        const args = Array.from((match[3] || '').match(method.args) || []).slice(1);

        nextMethod = {
          name: method.name,
          args: args,
          resursive: false
        };
        value = match[1];
      }
    }

    // Si la valeur de l'input ne vérifie couleur.methode() pour aucune methode de acceptedMethods,
    // on passe à l'étape suivante (vérifier si la valeur de l'input est une expression valide de couleur)
    if (nextMethod == null)
      break;
    else
      methods.push(nextMethod);
  }

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
        catch(error) { throw 'Couleur en argument invalide'; }
      }
      else coul2 = null;

      try {
        coul = Couleur.prototype[method.name].call(coul, ...method.args.map(arg => arg === 'true' ? true : arg === 'false' ? false : arg));
      }
      catch(error) { console.error(error); }
    }

    _couleur = coul;
    return _couleur;
  }
  catch(error) {
    // La valeur de l'input est invalide, ne rien faire.
    return null;
  }
}



///////////////////////
// Colors the interface
export function colorInterface(entree, fixContrast = true) {
  document.documentElement.style.setProperty('--user-color', entree.rgb);
  document.documentElement.style.setProperty('--user-hue', Math.round(entree.h * 360));
  document.documentElement.style.setProperty('--user-saturation', Math.round(entree.s * 100) + '%');

  // Calcul des couleurs du body et des sections selon le contraste de la couleur d'entrée
  let sectionColor = new Couleur(`hsl(${Math.round(entree.h * 360)}, ${Math.round(entree.s * 100)}%, 80%)`);
  let bodyColor = new Couleur(`hsl(${Math.round(entree.h * 360)}, ${Math.round(entree.s * 100)}%, 70%)`);
  if (fixContrast) {
    while (Couleur.contrast(sectionColor, bodyColor) < 1.2) {
      bodyColor = bodyColor.change('bk', '+5%').change('w', '-5%');
      sectionColor = bodyColor.change('l', '80%', true);
      if (bodyColor.w < 0.05 && bodyColor.bk > 0.95) break;
    }
  }
  document.body.style.setProperty('--body-color', bodyColor.hsl);
  document.body.style.setProperty('--section-color', sectionColor.hsl);
  document.querySelector('meta[name=theme-color]').content = bodyColor.hsl;

  // Calcul de la couleur des liens
  let linkColor = new Couleur(`hsl(${Math.round(entree.h * 360)}, ${Math.round(entree.s * 100)}%, 30%)`);
  if (fixContrast) {
    while (Couleur.contrast(linkColor, sectionColor) < 4.5) {
      linkColor = linkColor.change('bk', '+5%').change('w', '-5%');
      if (linkColor.w < 0.05 && linkColor.bk > 0.95) break;
    }
  }
  document.body.style.setProperty('--link-color', linkColor.hsl);

  // Calcul de la couleur du fond de la démo
  let frameOverlay = new Couleur('rgba(0, 0, 0, .8)');
  let _entree = entree.change('a', '1', true);
  let frameColor = Couleur.blend(sectionColor, frameOverlay);
  if (fixContrast) {
    while (Couleur.contrast(frameColor, _entree) < 1.2) {
      frameColor = frameColor.change('bk', '-5%').change('w', '+5%');
      if (frameColor.w > 0.95 && frameColor.bk < 0.05) break;
    }
  }
  document.querySelector('.demo-inside').style.setProperty('--frame-color', frameColor.hsl);
  if (fixContrast) {
    while (Couleur.contrast(frameColor, _entree) < 1.8) {
      frameColor = frameColor.change('bk', '-5%').change('w', '+5%');
      if (frameColor.w > 0.95 && frameColor.bk < 0.05) break;
    }
  }
  document.querySelector('.demo-inside').style.setProperty('--frame-color-mini', frameColor.hsl);

  // Calcul de la coloration syntaxique selon le contraste
  const steps = ['-90', '+45', '-45', '+135'];
  const tokenTypes = ['number', 'string', 'operator', 'keyword'];
  steps.forEach((e, k) => {
    let tokenColor = new Couleur('hsl(' + Math.round(entree.h * 360) + ', 70%, 60%)');
    tokenColor = tokenColor.change('h', steps[k]);
    if (fixContrast) {
      while (Couleur.contrast(tokenColor, frameColor) < 5) {
        tokenColor = tokenColor.change('bk', '-5%').change('w', '+5%');
        if (tokenColor.w > 0.95 && tokenColor.bk < 0.05) break;
      }
    }
    document.body.style.setProperty('--token-' + tokenTypes[k], tokenColor.hsl);
  });
}



//////////////////////////////////////////////////////
// Adds data about the selected color to the interface
export function populateColorData(entree) {
  const objet = document.querySelector('#objet>pre>code');
  objet.innerHTML = JSON.stringify(entree, null, 2);
  Prism.highlightElement(objet);

  let code;

  code = document.querySelector('.hex>.format-donnee>code');
  code.innerHTML = entree.hex;
  Prism.highlightElement(code);

  code = document.querySelector('.rgb>.format-donnee>code');
  code.innerHTML = entree.rgb;
  Prism.highlightElement(code);

  code = document.querySelector('.hsl>.format-donnee>code');
  code.innerHTML = entree.hsl;
  Prism.highlightElement(code);

  code = document.querySelector('.hwb>.format-donnee>code');
  code.innerHTML = entree.hwb;
  Prism.highlightElement(code);

  code = document.querySelector('.lab>.format-donnee>code');
  code.innerHTML = entree.lab;
  Prism.highlightElement(code);

  code = document.querySelector('.lch>.format-donnee>code');
  code.innerHTML = entree.lch;
  Prism.highlightElement(code);

  code = document.querySelector('.name>.format-donnee>code');
  if (entree.name == null)
  {
    document.querySelector('.name').classList.remove('oui');
    code.innerHTML = '';
  }
  else
  {
    document.querySelector('.name').classList.add('oui');
    code.innerHTML = entree.name;
  }
  Prism.highlightElement(code);

  const champ = document.getElementById('entree');
  champ.placeholder = entree.name || entree.hex;

  document.querySelector('.demo-conteneur').classList.add('calced');
}