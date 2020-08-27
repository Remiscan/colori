// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Cookie from './cookies.js.php';

/*<?php $imports = ob_get_clean();
require_once dirname(__DIR__, 2).'/_common/php/versionize-js-imports.php';
echo versionizeImports($imports, __DIR__); ?>*/

const template = document.createElement('template');
template.innerHTML = `
<button>
  <svg viewBox="0 0 24 24" style="width: 100%; height: 100%; fill: var(--h1-color);">
    <defs>
      <g id="auto">
        <path d="M0 0h24v24H0z" fill="none"/>
        <path d="M10.85 12.65h2.3L12 9l-1.15 3.65zM20 8.69V4h-4.69L12 .69 8.69 4H4v4.69L.69 12 4 15.31V20h4.69L12 23.31 15.31 20H20v-4.69L23.31 12 20 8.69zM14.3 16l-.7-2h-3.2l-.7 2H7.8L11 7h2l3.2 9h-1.9z"/>
      </g>

      <g id="auto2">
        <path d="M0 0h24v24H0z" fill="none"/>
        <path d="M20 8.69V4h-4.69L12 .69 8.69 4H4v4.69L.69 12 4 15.31V20h4.69L12 23.31 15.31 20H20v-4.69L23.31 12 20 8.69zM12 18c-.89 0-1.74-.2-2.5-.55C11.56 16.5 13 14.42 13 12s-1.44-4.5-3.5-5.45C10.26 6.2 11.11 6 12 6c3.31 0 6 2.69 6 6s-2.69 6-6 6z"/>
      </g>

      <g id="light">
        <path d="M0 0h24v24H0z" fill="none"/>
        <path d="M20 8.69V4h-4.69L12 .69 8.69 4H4v4.69L.69 12 4 15.31V20h4.69L12 23.31 15.31 20H20v-4.69L23.31 12 20 8.69zM12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
      </g>

      <g id="dark">
        <path d="M0 0h24v24H0z" fill="none"/>
        <path d="M10 2c-1.82 0-3.53.5-5 1.35C7.99 5.08 10 8.3 10 12s-2.01 6.92-5 8.65C6.47 21.5 8.18 22 10 22c5.52 0 10-4.48 10-10S15.52 2 10 2z"/>
      </g>
    </defs>

    <use href="#auto"/>
  </svg>
</button>
`;

export default class Theme extends HTMLElement {
  constructor() {
    super();
  }

  static get observedAttributes() {
    return ['theme', 'data-tolabel'];
  }

  update(attributes = Theme.observedAttributes) {
    if (!this.ready) return;
    const use = this.querySelector('use');

    theme: {
      if (!attributes.includes('theme')) break theme;
      use.setAttribute('href', `#${this.getAttribute('theme')}`);
    }

    label: {
      if (!attributes.includes('data-tolabel')) break label;
      this.querySelector('button').setAttribute('aria-label', this.getAttribute('data-tolabel'));
    }
  }

  connectedCallback() {
    this.appendChild(template.content.cloneNode(true));
    this.ready = true;
    this.addEventListener('click', Theme.toggle);
    this.update();
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (oldValue == newValue) return;
    this.update([name]);
  }

  static toggle() {
    const themes = Theme.supportedThemes;
    const k = themes.findIndex(t => t == Theme.get());
    Theme.set(themes[(k + 1) % 3]);
  }

  static set(requestedTheme = Theme.get()) {
    let theme = Theme.resolve(requestedTheme);

    const html = document.documentElement;
    html.classList.remove('light', 'dark');
    html.classList.add(theme);

    // Set meta theme-color here
  
    Array.from(document.querySelectorAll('theme-selector')).forEach(sel => sel.setAttribute('theme', requestedTheme));
    new Cookie('theme', requestedTheme);
    new Cookie('resolvedTheme', theme);

    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: requestedTheme } }));
  }

  static resolve(theme) {
    // Is the theme supported - if not, default to 'auto'
    let t = (Theme.supportedThemes.includes(theme)) ? theme : 'auto';
    // If 'auto', resolve to osTheme (or defaultTheme if osTheme isn't defined)
    return (t == 'auto') ? Theme.osTheme || Theme.defaultTheme : t;
  }

  static get active() {
    return Theme.resolve(Theme.userTheme);
  }

  static get() {
    // Does not resolve 'auto'
    const theme = Theme.userTheme;
    return (Theme.supportedThemes.includes(theme)) ? theme : 'auto';
  }

  static get userTheme() {
    return Cookie.get('theme');
  }

  static get osTheme() {
    let osTheme;
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) osTheme = 'dark';
    else if (window.matchMedia('(prefers-color-scheme: light)').matches) osTheme = 'light';
    return osTheme;
  }

  static get defaultTheme() {
    return 'light';
  }

  static get supportedThemes() {
    return ['auto', 'dark', 'light'];
  }
}
customElements.define("theme-selector", Theme);