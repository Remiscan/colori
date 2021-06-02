// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import DefCookie from '/_common/js/cookies.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



export default class Cookie extends DefCookie {
  constructor(name, value, maxAge = null) {
    super(name, value, '/colori', maxAge);
  }

  static consent(bool) {
    super.consent('/colori', bool);
  }

  static delete(name) {
    super.delete('/colori', name);
  }
}