export function prepareNav() {
  const articles = [...document.querySelectorAll('.documentation>article')];
  const aside = document.querySelector('aside.nav-rapide');
  for (const article of articles) {
    const lang = article.getAttribute('lang');
    const progLang = article.getAttribute('data-prog-language');

    // Fix anchor links IDs
    {
      const anchors = [...article.querySelectorAll('a[id]')];
      for (const a of anchors) {
        a.id = `${a.id}-${lang}-${progLang}`;
      }
    }

    // Fix anchor links in table of contents
    const navUl = article.querySelector('ul');
    {
      const anchors = [...navUl.querySelectorAll('a[href]')];
      for (const a of anchors) {
        a.href = `${a.href}-${lang}-${progLang}`;
      }
    }
    

    // Copy table of contents into <aside>
    const navAside = document.createElement('div');
    navAside.setAttribute('lang', lang);
    navAside.setAttribute('data-prog-language', progLang);
    navAside.appendChild(navUl.cloneNode(true));
    aside.appendChild(navAside);
  }
}