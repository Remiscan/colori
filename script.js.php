// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';
import '/_common/components/theme-selector/theme-selector.js.php';
import Cookie from '/colori/modules/cookies.js.php';
import { Traduction } from '/colori/modules/traduction.js.php';
import { makeNav } from '/colori/modules/quickNav.js.php';
import { updateCouleur } from '/colori/modules/colorDetection.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const langSwitch = document.querySelector('.switch-js-php');
let initialColor;



/////////////////////////////////////////////////
// Detects user input in the "type a color" field
const champ = document.getElementById('entree');
champ.addEventListener('input', event => {
  let evt = event || window.event;
  document.querySelector('.demo-conteneur').classList.remove('calced');
  updateCouleur(evt.target.value.replace(/'/g, ''), 50)
  .catch(error => {});
});


////////////////////////////////////////////////
// Switch between js and php version of the page
async function switchBetweenJsPhp(language) {
  const langSwitch = document.querySelector('.switch-js-php');
  new Cookie('progLang', language);

  return new Promise(resolve => {
    setTimeout(() => {
      if (language == 'php') {
        document.documentElement.dataset.progLanguage = 'php';
      } 
      else {
        document.documentElement.dataset.progLanguage = 'js';
      } 
      //makeNav(langSwitch.dataset.currentTab);
      resolve();
    }, 20);
  });
}


//////////////////
// On theme change
window.addEventListener('themechange', event => {
  document.documentElement.dataset.resolvedTheme = event.detail.resolvedTheme;

  const meta = document.querySelector('meta[name=theme-color]');
  meta.content = meta.dataset[event.detail.resolvedTheme];

  new Cookie('theme', event.detail.theme);
  new Cookie('resolvedTheme', event.detail.resolvedTheme);
});


///////////////
// On page load
window.addEventListener('DOMContentLoaded', async () => {
  // Update interface with the random initial color
  try {
    initialColor = new Couleur(document.documentElement.dataset.startColor);
    await updateCouleur(initialColor.name, 10);
  }
  catch(error) {
    console.error('Erreur (couleur aléatoire)', error);
  }

  // Translate the page
  await Traduction.traduire();

  // Replace Colore by Couleur in PHP documentation
  for (const element of [...document.querySelectorAll('#documentation-php code.language-javascript')]) {
    if (element.innerHTML == 'Colore') element.outerHTML = '<code class="language-php">Couleur</code>';
  }

  // Detect clicks on example buttons
  for (const exemple of [...document.querySelectorAll('button.exemple')]) {
    exemple.addEventListener('click', () => {
      if (exemple.textContent == '+') {
        for (const hiddenElement of [...document.querySelectorAll('.inst-hidden')]) {
          hiddenElement.classList.toggle('off');
        }
      } else {
        champ.value = exemple.textContent;
        champ.dispatchEvent(new Event('input'), { bubbles: true });
      }
    })
  }

  // Detect clicks on JS <=> PHP toggle
  langSwitch.addEventListener('click', () => switchBetweenJsPhp(document.documentElement.dataset.progLanguage == 'js' ? 'php' : 'js'));

  // Toggle JS or PHP version of the page based on last visit
  /*if (isPhp == 'true')  await switchBetweenJsPhp('php');
  else                  makeNav('js');*/

  // Customize theme-selector
  document.querySelector('theme-selector .selector-title').classList.add('h4');

  // Remove loading screen
  document.documentElement.classList.add('loaded');
});