// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import DefTraduction from '../../_common/js/traduction.js';
import { makeNav } from './quickNav.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/



class ExtTraduction extends DefTraduction {
  constructor() {
    const version = document.querySelector('link#strings').dataset.version || document.documentElement.dataset.version || 0;
    const path = `/colori/strings--${version}.json`;
    super('colori', path, 'fr');
  }

  async traduire(element = document) {
    await super.traduire(element);
    await this.initLanguageButtons();
    if (element == document) {
      document.querySelector('.nav-documentation').dataset.titre = getString('nav-documentation');
      document.querySelector('theme-selector').dataset.tolabel = getString('change-theme');
    }
    const langSwitch = document.querySelector('.switch-js-php');
    makeNav(langSwitch.dataset.currentTab);
    setTimeout(() => Prism.highlightAll());
  }
}

export const Traduction = new ExtTraduction();
export const getString = Traduction.getString.bind(Traduction);