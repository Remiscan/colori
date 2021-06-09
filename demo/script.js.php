// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import '/_common/components/theme-selector/theme-selector.js.php';
import Cookie from '/colori/demo/modules/cookies.js.php';
import { Traduction } from '/colori/demo/modules/traduction.js.php';
import { updateCouleur } from '/colori/demo/modules/colorDetection.js.php';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



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
const progLangSwitch = document.querySelector('fieldset.prog-language-choice');
progLangSwitch.addEventListener('change', event => {
  switch (event.target.value) {
    case 'php': document.documentElement.dataset.progLanguage = 'php'; break;
    case 'js': document.documentElement.dataset.progLanguage = 'js'; break;
  }
});


/////////////////////
// On language change
window.addEventListener('langchange', event => {
  // Check the correct prog-language-choice tab
  const lang = event.detail.lang;
  new Cookie('lang', lang);
  const progLang = document.querySelector('input[name="prog-language-choice"]:checked').value;
  document.querySelector(`#prog-language-choice-${progLang}-${lang}`).checked = true;
});


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

  // Customize theme-selector
  document.querySelector('theme-selector .selector-title').classList.add('h4');
  document.querySelector('theme-selector .selector-cookie-notice').classList.add('h6');

  Prism.highlightAll();
});