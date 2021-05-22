// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';
import { resolveColor } from './colorResolution.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



let entree;
let lastTry;



/////////////////////////////////////////////////////
// Update the interface with the newly detected color
export async function updateCouleur(couleur, delai = 100) {
  const thisTry = Date.now();
  lastTry = thisTry;

  await new Promise(resolve => setTimeout(resolve, delai));
  if (lastTry != thisTry) return;

  // Hide the numerical result by default
  const donnees = document.getElementById('donnees');
  donnees.classList.remove('valeur', 'gradient');
  
  try {
    entree = resolveColor(couleur);
    if (entree instanceof Couleur) {
      colorInterface(entree);
      populateColorData(entree);
    } else if (entree != null) {
      const valeur = document.querySelector('.format.valeur code');

      // If the result is a number, display it below the input field
      if (typeof entree == 'number') {
        valeur.innerHTML = entree;
        donnees.classList.add('valeur');
      }
      // If the result is an array of colors, display their gradient as the input background
      else if (Array.isArray(entree) && entree.reduce((sum, e) => sum + (e instanceof Couleur), 0)) {
        const gradient = `linear-gradient(to right, ${entree.map(c => c.name || c.rgb).join(', ')})`;
        
        valeur.innerHTML = gradient;
        Prism.highlightElement(valeur);
        donnees.classList.add('valeur', 'gradient');

        document.querySelector('.format.gradient').style.setProperty('--gradient', gradient);
      }
      else console.log(`${couleur} == ${entree}`);
    }
    return;
  }
  catch(error) {
    return console.error(error);
  }
}



///////////////////////
// Colors the interface
export function colorInterface(couleur = entree, fixContrast = true) {
  const element = document.documentElement;
  element.style.setProperty('--user-color', couleur.rgb);
  element.style.setProperty('--user-hue', Math.round(couleur.h * 360));
  element.style.setProperty('--user-saturation', Math.round(couleur.s * 100) + '%');

  const meta = document.querySelector('meta[name=theme-color]');
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

    if (fixContrast) {
      if (theme == 'light') {
        const neutral = new Couleur('white');
        bodyColor = bodyColor.betterContrast(neutral, 1.9);
        sectionColor = sectionColor.betterContrast(neutral, 1.4);
      } else {
        const neutral = new Couleur('black');
        bodyColor = bodyColor.betterContrast(neutral, 1.2);
        sectionColor = sectionColor.betterContrast(neutral, 1.6);
      }
    }
    
    if (theme == 'light')     meta.dataset.light = bodyColor.hsl;
    else if (theme == 'dark') meta.dataset.dark = bodyColor.hsl;
    if (document.documentElement.resolvedTheme == theme)
      document.querySelector('meta[name=theme-color]').content = bodyColor.hsl;

    // Calcul de la couleur des liens
    let linkColor;
    if (theme == 'light')
      linkColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${Math.round(couleur.s * 100)}%, 30%)`);
    else if (theme == 'dark')
      linkColor = new Couleur(`hsl(${Math.round(couleur.h * 360)}, ${0.6 * Math.round(couleur.s * 100)}%, 80%)`);

    if (fixContrast)
      linkColor = linkColor.betterContrast(sectionColor, 4.5);

    // Calcul de la couleur du fond de la démo
    let frameOverlay = new Couleur('rgba(0, 0, 0, .8)');
    const white = new Couleur('white');
    let _couleur = white.blend(`rgba(0, 0, 0, ${frameOverlay.a})`).blend(couleur);
    let frameColor = Couleur.blend(sectionColor, frameOverlay);
    if (fixContrast)
      frameColor = frameColor.betterContrast(_couleur, 1.2);

    let miniFrameColor = frameColor;
    if (fixContrast)
      miniFrameColor = miniFrameColor.betterContrast(_couleur, 2.5);
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
        tokenColor = tokenColor.betterContrast(frameColor, 5);
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