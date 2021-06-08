// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';
import '/_common/components/theme-selector/theme-selector.js.php';
import Cookie from '/colori/demo/modules/cookies.js.php';
import { Traduction } from '/colori/demo/modules/traduction.js.php';
import { updateCouleur } from '/colori/demo/modules/colorDetection.js.php';

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
function switchBetweenJsPhp(language) {
  if (language == 'php') {
    document.documentElement.dataset.progLanguage = 'php';
    //new Cookie('progLang', language);
  } 
  else {
    document.documentElement.dataset.progLanguage = 'js';
    //Cookie.delete('progLang');
  }
}


//////////////////
// On theme change
window.addEventListener('themechange', event => {
  document.documentElement.dataset.resolvedTheme = event.detail.resolvedTheme;

  const meta = document.querySelector('meta[name=theme-color]');
  meta.content = meta.dataset[event.detail.resolvedTheme];

  if (event.detail.theme != 'auto') {
    new Cookie('theme', event.detail.theme);
    new Cookie('resolvedTheme', event.detail.resolvedTheme);
  } else {
    Cookie.delete('theme');
    Cookie.delete('resolvedTheme');
  }
});


///////////////
// On page load
window.addEventListener('DOMContentLoaded', async () => {
  await Traduction.traduire();

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

  // Customize theme-selector
  document.querySelector('theme-selector .selector-title').classList.add('h4');
  document.querySelector('theme-selector .selector-cookie-notice').classList.add('h6');

  Prism.highlightAll();
});