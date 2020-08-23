<?php include '../index.php'; ?>

<script src="/colori/ext/turndown.js"></script>
<script type="module">
  import { traduire, getString, switchLangage, getLangage } from '../../_common/js/traduction--<?=version($commonDir.'/js', 'traduction.js')?>.js';
  function wait(time) { return new Promise(resolve => setTimeout(resolve, time)); }

  const currentLanguage = localStorage.getItem('colori/langage');

  wait(1000)
  .then(() => switchLangage('en'))
  .then(() => traduire('colori'))
  .then(() => {
    const turndownService = new TurndownService({headingStyle: 'atx', codeBlockStyle: 'fenced'});
    const files = ['documentation-js', 'documentation-php'];
    const filenames = ['Documentation-(JavaScript).md', 'Documentation-(PHP).md'];
    files.forEach((e, k) => {
      const code = document.getElementById(e);
      Array.from(code.querySelectorAll('p.h3')).forEach(p => {
        let newHtml = '';
        if (p.previousElementSibling.tagName != 'H2') newHtml += '<hr>';
        newHtml += '<h3>' + p.innerHTML + '</h3>';
        p.outerHTML = newHtml;
      });
      //code.innerHTML = code.innerHTML.replace(new RegExp('<p class="h3">(.+)</p>', 'g'), '<hr><h3>$1</h3>');
      let markdown = turndownService.turndown(document.getElementById(e));
      markdown = markdown.replace('[colori.js](https://github.com/Remiscan/colori/releases/latest/download/colori.js)', 'colori.js');
      markdown = markdown.replace('[colori.php](https://github.com/Remiscan/colori/releases/latest/download/colori.php)', 'colori.php');
      markdown = markdown.replace(new RegExp('##(.+)\*\ \*\ \*', 'g'), '##$1');

      const filename = filenames[k];
      const file = new File([markdown], filename, {type: 'text/markdown'});
      const lien = URL.createObjectURL(file);

      const a = document.createElement('a');
      a.href = lien;
      a.download = filename;
      document.body.appendChild(a);

      const clic = new MouseEvent('click', {
        bubbles: true,
        cancelable: true,
        view: window,
        clientX: 0,
        clientY: 0
      });
      a.dispatchEvent(clic); // Lance le téléchargement de l'image

      a.remove();
    })
  })
  .then(() => wait(1000))
  .then(() => switchLangage(currentLanguage))
  .then(() => traduire('colori'));;
</script>