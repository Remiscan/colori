export function makeNav(language = 'js') {
  // Get nav listing
  const anchors = Array.from(document.querySelectorAll(`#documentation-${language} h2, #documentation-${language} p.h3`));
  const getAnchorUrl = a => a.textContent.toLowerCase()
                              .replace(/\ \/\ /g, '--')
                              .replace(/\ |\,\ |\./g, '-');

  // Reset nav link destinations
  const anchorDests = Array.from(document.querySelectorAll('.anchor-dest'));
  anchorDests.forEach(a => a.remove());

  // Create nav links
  let navHtml = '<ul>';
  let previousDepth = 0;
  let depth = 0;
  for (const a of anchors) {
    depth = (a.tagName == 'H2') ? 0 : 1;
    if (depth > previousDepth) navHtml += '\n<ul>';
    else if (depth < previousDepth) navHtml += '\n</ul>';
    navHtml += `\n<li><a href="#${getAnchorUrl(a)}">${a.textContent}</a></li>`;
    previousDepth = depth;

    a.outerHTML = `<a id="${getAnchorUrl(a)}" class="anchor-dest"></a>\n${a.outerHTML}`;
  }
  while (depth >= 0) {
    navHtml += '\n</ul>';
    depth--;
  }

  // Populate links on page
  /*const buttonHtml = `
    <input type="checkbox" id="enable-nav">
    <label for="enable-nav"><svg height="24" width="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" style="fill: var(--h1-color);"/></svg></label>
  `;*/
  const aside = document.querySelector('aside.nav-documentation');
  const titre = `<h1 class="titre-nav-rapide">${aside.dataset.titre}</h1>`;
  aside.innerHTML = titre + navHtml/* + buttonHtml*/;

  // Enable nav button for mobile
  /*const navButton = document.querySelector('input#enable-nav');
  navButton.addEventListener('change', () => {
    if (navButton.checked) aside.classList.add('on');
    else aside.classList.remove('on');
  });*/

  return navHtml;
}