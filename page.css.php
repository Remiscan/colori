/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! TYPOGRAPHIE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/



/* lato-regular - latin */
@font-face {
  font-family: 'Lato';
  font-style: normal;
  font-weight: 400;
  src: local(''),
       url('/_common/fonts/lato/lato-v17-latin-regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
       url('/_common/fonts/lato/lato-v17-latin-regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
}

html {
  font-family: system-ui, 'Roboto', Helvetica, Arial, sans-serif;
  --min-font: 1.0; /* rem */
  --max-font: 1.1; /* rem */
  --min-screen: 60; /* 960px si 1rem = 16px */
  --max-screen: 80; /* 1280px si 1rem = 320px */
  font-size: calc(1rem * var(--min-font));
  --mod: 1.250;
}
@media screen and (min-width: 1100px) {
  html {
    font-size: calc(1rem * var(--max-font));
    --mod: 1.333;
  }
}
h1, h2, h3, h4, h5, h6 {
  display: inline;
  margin: 0;
  font-weight: 400;
}
h1, .h1 {
  font-size: calc(var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem);
}
h2, .h2 {
  font-size: calc(var(--mod) * var(--mod) * 1rem);
}
h4, .h4, p.h3 {
  font-size: calc(var(--mod) * 1rem);
}
h5, .h5 {
  font-size: 1rem;
  line-height: 1.6em;
}
h6, .h6 {
  font-size: calc(1rem / var(--mod));
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! STRUCTURE DE LA PAGE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/



* {
  /* Empêche le bleu moche quand on clique sur un truc sous chrome Android */
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  /* Empêche le font scaling auto moche sous chrome Android */
  max-height: 1000000px;
}
*:focus {
  outline: 2px solid var(--link-color);
}
*:focus:not(:focus-visible) {
  outline-style: none;
}
::-moz-focus-inner {
  border: 0;
}



/*
 * Layout
 */
html {
  width: 100%;
  overflow-x: hidden;
  overflow-y: hidden;
  background-color: var(--body-color);
  --easing-standard: cubic-bezier(0.4, 0.0, 0.2, 1);
  --easing-decelerate: cubic-bezier(0.0, 0.0, 0.2, 1);
  --easing-accelerate: cubic-bezier(0.4, 0.0, 1, 1);
  --h-diff: -1;
  --user-saturation: 100%; 
  --text-strong-color: var(--h3-color);
}

/*<?php ob_start();?>*/
html[data-theme="light"] {
  color-scheme: light;
  /* Button colors */
  --button-bg-color: rgba(255, 255, 255, .2);
  --button-border-color: rgba(255, 255, 255, .5);
  --button-hover-bg-color: rgba(255, 255, 255, .4);
  --button-hover-border-color: white;
}

html[data-theme="dark"] {
  color-scheme: dark;
  /* Button colors */
  --button-bg-color: rgba(255, 255, 255, .1);
  --button-border-color: rgba(255, 255, 255, .3);
  --button-hover-bg-color: rgba(255, 255, 255, .2);
  --button-hover-border-color: rgba(255, 255, 255, .5);
}
/*<?php $body = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
echo buildThemesStylesheet($body); ?>*/

html.loaded {
  overflow-y: auto;
}

body { /* Desktop-like */
  display: grid;
  grid-template-columns: 1fr 42rem 4.2rem 42rem 1fr;
  grid-row-gap: 1.2rem;
  width: 100%;
  margin: 0;
  padding: 0;
  background-color: var(--body-color);
  color: var(--text-color);
}

@media screen/*(max-width: 100rem)*/ { /* Narrow desktop-like */
  body {
    grid-template-columns: 1fr 42rem 1fr;
  }
}

@media (max-width: 42rem) { /* Phone-like */
  body {
    grid-template-columns: .6rem calc(100% - 1.2rem) .6rem;
    grid-row-gap: .9rem;
  }
}



/*
 * Titre de section
 */

h1 {
  display: block;
  color: var(--h1-color);
  font-family: 'Lato';
  position: absolute;
  z-index: -1;
  top: 0;
  left: 0;
  transform: translate(1.2rem, calc(-100% + 17.71%));
  user-select: none;
  --shadow-color: var(--section-color);
  text-shadow: 0 0 0 var(--shadow-color),
               1px 1px 0 var(--shadow-color),
               2px 2px 0 var(--shadow-color),
               3px 3px 0 var(--shadow-color),
               4px 4px 0 var(--shadow-color),
               5px 5px 0 var(--shadow-color),
               6px 6px 0 var(--shadow-color),
               7px 7px 0 var(--shadow-color),
               8px 8px 0 var(--shadow-color),
               9px 9px 0 var(--shadow-color),
               10px 10px 0 var(--shadow-color),
               11px 11px 0 var(--shadow-color),
               12px 12px 0 var(--shadow-color),
               13px 13px 0 var(--shadow-color),
               14px 14px 0 var(--shadow-color),
               15px 15px 0 var(--shadow-color),
               16px 16px 0 var(--shadow-color),
               17px 17px 0 var(--shadow-color),
               18px 18px 0 var(--shadow-color),
               19px 19px 0 var(--shadow-color),
               20px 20px 0 var(--shadow-color),
               21px 21px 0 var(--shadow-color),
               22px 22px 0 var(--shadow-color),
               23px 23px 0 var(--shadow-color),
               24px 24px 0 var(--shadow-color),
               25px 25px 0 var(--shadow-color),
               26px 26px 0 var(--shadow-color),
               27px 27px 0 var(--shadow-color),
               28px 28px 0 var(--shadow-color),
               29px 29px 0 var(--shadow-color),
               30px 30px 0 var(--shadow-color),
               31px 31px 0 var(--shadow-color),
               32px 32px 0 var(--shadow-color),
               33px 33px 0 var(--shadow-color),
               34px 34px 0 var(--shadow-color),
               35px 35px 0 var(--shadow-color),
               36px 36px 0 var(--shadow-color),
               37px 37px 0 var(--shadow-color),
               38px 38px 0 var(--shadow-color),
               39px 39px 0 var(--shadow-color),
               40px 40px 0 var(--shadow-color);
}



/*
 * Header
 */

header {
  grid-row: 1 / 2;
  grid-column: 2 / 5;
  display: grid;
  grid-template-columns: auto 1fr [github-start] auto [github-end] 1fr [options-start] auto [options-end];
  justify-content: center;
  align-items: start;
  position: relative;
  /*z-index: 0; WHY??? */
  background: var(--section-color);
  border-radius: 0 0 .6rem .6rem;
}

header>h1 {
  grid-column: 1 / 2;
  grid-row: 1 / 2;
  justify-self: start;
  align-self: end;
  transform: none;
  position: relative;
  --shadow-color: var(--body-color);
  padding: 0 .9rem;
  text-align: center;
  --coupe: 1.2rem;
  height: auto;
  transform: translateY(17.7%);
  z-index: 0;
  font-size: calc(1.2 * var(--mod) * var(--mod) * 1rem);
}

theme-selector {
  width: 1.8em;
  height: 1.8em;
  margin: .3rem;
  --margin-right: .6rem;
  margin-right: var(--margin-right);
  --primary-color: var(--h1-color);
  --secondary-color: var(--h1-color);
}

theme-selector>.selector {
  right: calc(-1 * var(--margin-right));
  background-color: var(--section-color);
  box-shadow: 0 1px .2rem 1px var(--body-color);
  margin-top: .6rem;
  border-radius: .6rem;
  overflow: hidden;
  z-index: 100;
  transform: translateY(-.2rem);
  transition: opacity .2s ease,
              transform .2s ease;
}

theme-selector[open="true"]>.selector {
  transform: translateY(0);
}

theme-selector .selector-title {
  color: var(--h3-color);
}

input[type="radio"] {
  height: 0;
  width: 0;
  opacity: 0;
  margin: 0;
  pointer-events: none;
  position: absolute;
}

.selector-title,
input[type="radio"] + label {
  padding: .6rem .6rem;
}

.selector-title {
  place-self: center;
}

input[type="radio"] + label {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: .6rem;
}

input[type="radio"] + label:hover,
input[type="radio"]:checked + label {
  background: var(--input-bg-color);
}

input[type="radio"] + label::before {
  content: '';
  display: block;
  --size: 1rem;
  width: var(--size);
  height: var(--size);
  border-radius: 50%;
  box-shadow: inset 0 0 0 2px var(--h3-color);
  place-self: center;
  grid-row: 1;
  grid-column: 1;
  /* Rotation forces sub-pixel rendering to make perfect circle,
     and 3D forces anti-aliasing */
  transform: rotate3D(0, 0, 1, 360deg);
}

input[type="radio"]:checked + label::before {
  background-color: var(--h1-color);
  box-shadow: inset 0 0 0 .1rem var(--h1-color),
              inset 0 0 0 .2rem var(--input-bg-color);
}

input[type="radio"] + label>span {
  margin: auto 0;
}

.groupe-langages {
  grid-column: options-start / options-end;
  grid-row: 1;
  justify-self: end;
  align-self: end;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.bouton-langage {
  border: none;
  background-color: transparent;
  -webkit-appearance: none;
  appearance: none;
  color: var(--h1-color);
  font-family: inherit;
  font-size: inherit;
  font-weight: 600;
  font-size: .8em;
  width: fit-content;
  cursor: pointer;
  padding: .3rem;
  text-decoration: underline;
  text-decoration-skip-ink: auto;
  position: relative;
}

.bouton-langage:disabled {
  background-color: transparent;
  text-decoration: none;
  opacity: .5;
  cursor: auto;
}



/*
 * Lien vers GitHub
 */

.lien-github {
  grid-column: github-start / github-end;
  grid-row: 1;
  justify-self: end;
  align-self: center;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: .6rem;
  padding: .2rem;
  font-size: inherit;
}

.lien-github:link,
.lien-github:visited {
  text-decoration: none;
  box-shadow: none;
  color: var(--h1-color);
}

.lien-github:hover,
.lien-github:focus,
.lien-github:active {
  color: var(--section-color);
}

.lien-github svg {
  fill: var(--h1-color);
}

.lien-github:hover svg,
.lien-github:focus svg,
.lien-github:active svg {
  fill: var(--section-color);
}

span[data-string=github] {
  width: auto;
  text-align: right;
}

.github-logo {
  width: 3.6rem;
  height: 1.28rem;
}

.github-cat {
  width: 1.6rem;
  height: 1.6rem;
  display: none;
}

@media (max-width: 30rem) {
  header {
    grid-template-columns: auto 0 [github-start] auto [github-end] 1fr [options-start] auto [options-end];
  }
  
  .lien-github {
    margin: 0 .6rem;
  }

  span[data-string=github],
  .space {
    display: none;
  }

  .groupe-langages {
    justify-self: center;
  }

  .bouton-langage:disabled {
    display: none;
  }
}

@media (max-width: 24rem) {
  .github-logo {
    display: none;
  }
  .github-cat {
    display: block;
  }
}



/*
 * Sections (démo & présentation)
 */

header,
section,
footer {
  grid-column: 2;
}

section {
  background-color: var(--section-color);
  border-radius: .6rem;
  padding: .9rem;
  padding-top: 1.2rem;
  margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem);
  position: relative;
  z-index: 1;
  --coupe: 1.6rem;
}

section::before {
  content: '';
  display: block;
  width: calc(var(--coupe) + 2px);
  height: calc(var(--coupe) + 2px);
  position: absolute;
  top: -2px;
  right: -2px;
  clip-path: polygon(0 0, 100% 0, 100% 100%);
  background-color: var(--body-color);
  display: none;
}

@supports not (clip-path: polygon(0 0, 100% 0, 100% 100%)) {
  section::before {
    display: none;
  }
}

section.no-titre {
  margin-top: 0;
  padding: .9rem;
}

#intro,
footer {
  margin-top: .6rem;
}

#demo {
  height: fit-content;
  position: sticky;
  top: 3.4rem;
}
@supports not (height: fit-content) {
  #demo {
    position: relative;
    top: unset;
  }

  .demo-inside {
    position: sticky;
    top: 1.2rem;
  }
}

.off {
  display: none;
}



/*
 * Switch JS / PHP
 */

.prog-lang-changer {
  display: flex;
  position: absolute;
  --height: 1.6rem;
  top: calc(-1 * var(--height));
  right: .7rem;
}

.switch-js-php {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  border: 0;
  margin: 0;
  padding: 0;
  background: transparent;
  display: grid;
  grid-template-columns: auto auto;
  height: var(--height);
  position: relative;
  cursor: pointer;
  overflow: hidden;
}

.sw-span {
  display: flex;
  justify-content: center;
  align-items: center;
  pointer-events: none;
  user-select: none;
  z-index: 1;
  font-size: .8rem;
  font-weight: 600;
  color: var(--h1-color);
  padding: 0 .4rem;
  height: 100%;
}

.switch-js-php[data-current-tab=js]>#sw-js,
.switch-js-php[data-current-tab=php]>#sw-php {
  background-color: var(--section-color);
  border-radius: .6rem .6rem 0 0;
}



/*
 * Footer
 */

footer {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  background: var(--section-color);
  border-radius: .6rem .6rem 0 0;
  padding: .4em;
}



@media screen/*(max-width: 100rem)*/ { 
  #demo {
    height: auto;
    position: relative;
    top: unset;
  }

  #demo,
  .documentation {
    margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem + .6rem);
  }
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! SECTION DÉMO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/



/*
 * Conteneur
 */

.demo-conteneur {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: .6rem 0;
  position: relative;
}



/*
 * Champ de saisie de couleur
 */

#saisie {
  grid-row: 1 / 2;
  grid-column: 1 / 3;
  gap: .6rem;
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: auto 1fr;
}

label[for=entree] {
  grid-row: 1;
  grid-column: 1;
  width: fit-content;
}

input {
  grid-row: auto;
  grid-column: 1 / 3;
  width: 100%;
  height: 100%;
  border: none;
  box-sizing: border-box;
  padding: .4em .6em;
  padding-right: calc(.6em + var(--padding-right, 0));
  font-family: 'Open Sans';
  color: var(--text-color);
  transition: color .3s ease;
  background-color: var(--input-bg-color);
  border-radius: .6rem;
  box-shadow: 0 0 0 1px var(--body-color);
}

input:hover {
  box-shadow: 0 0 0 2px var(--h3-color);
}

input:active, input:focus {
  outline: none;
  background-color: var(--input-active-bg-color);
  box-shadow: 0 0 0 2px var(--h3-color);
}

::placeholder {
  color: var(--input-placeholder-color);
}



/*
 * Boutons d'exemple
 */

.exemples-saisie {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  gap: .6rem;
  align-items: flex-end;
  overflow-x: auto;
  scrollbar-width: thin;
}

.exemples-saisie>span {
  opacity: .7;
  font-size: .9rem;
  padding: .2em 0;
  border: 1px solid transparent;
  white-space: nowrap;
}

.exemples-valeurs {
  grid-row: 1;
  grid-column: 2;
}

.instructions-exemples-fonctions,
.exemples-fonctions {
  grid-column: 1 / 3;
  font-size: .9em;
  margin: 0;
}

.exemple.exemple {
  -webkit-appearance: none;
  appearance: none;
  font-family: inherit;
  font-size: .9rem;
  color: inherit;
  line-height: inherit;
  margin: 0;
  padding: .2em .6em;
  border: 1px solid var(--button-border-color);
  border-radius: 4px;
  background: var(--button-bg-color);
  cursor: pointer;
  white-space: nowrap;
  text-decoration: none;
}

.exemple:hover {
  border-color: var(--button-hover-border-color);
  background: var(--button-hover-bg-color);
}

.exemple:focus {
  outline: none;
  border-color: var(--link-color);
  box-shadow: inset 0 0 0 1px var(--link-color);
}



/*
 * Affichage des formats de la couleur
 */

#donnees {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
  flex-wrap: wrap;
  margin-left: -.6rem;
}

.format {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  margin-top: .6rem;
  margin-left: .6rem;
  align-self: center;
}

pre[class*="language-"] {
  padding: .4em .5em;
}

pre[class*="language-"].format-donnee {
  margin: 0;
}

.format-donnee {
  padding-left: 2rem;
}

.format.name:not(.oui) {
  display: none;
}

.format.gradient,
.format.couleur {
  display: flex;
  flex-basis: 100%;
  height: 3rem;
  --border-size: 4px;
  align-self: stretch;
  background-color: var(--frame-color);
  border-radius: .6rem;
  position: relative;
  font-size: 0;
}

.format.couleur {
  flex-basis: 3rem;
  height: auto;
}

.format.couleur::before,
.format.gradient::before {
  content: '';
  display: block;
  width: calc(100% - 2 * var(--border-size));
  height: calc(100% - 2 * var(--border-size));
  top: var(--border-size);
  left: var(--border-size);
  background-image: linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%),
                    linear-gradient(45deg, rgba(0, 0, 0, .1) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, .1) 75%);
  background-size: 16px 16px;
  background-position: 0 0, 8px 8px;
  background-color: #ddd;
  border-radius: .49rem;
  position: absolute;
}

.format.couleur::after,
.format.gradient::after {
  content: '';
  display: block;
  width: calc(100% - 2 * var(--border-size));
  height: calc(100% - 2 * var(--border-size));
  top: var(--border-size);
  left: var(--border-size);
  background-color: var(--user-color);
  border-radius: .4rem;
  position: absolute;
}

.format.gradient::after {
  background-color: transparent;
  background-image: var(--gradient);
}

:not(.valeur)>.format.valeur,
:not(.gradient)>.format.gradient,
.valeur>.format:not(.valeur):not(.gradient) {
  display: none;
}

.format.valeur code.language-css {
  white-space: normal;
}

.valeur.whatToBlend>.format.valeur code {
  white-space: pre-wrap;
}
.valeur.whatToBlend>.format.gradient {
  height: 4rem;
}
.valeur.whatToBlend>.format.gradient::after {
  background-image: var(--gradient),
                    linear-gradient(to bottom, transparent 0 50%, var(--bg) 50% 100%);
  background-position: top center, bottom center;
  background-size: 100% 100%;
  background-repeat-y: no-repeat;
  animation: moveBlend linear 4s infinite alternate;
}

@keyframes moveBlend {
  0%    { background-position: top center, bottom center; }
  25%   { background-position: top center, bottom center; }
  100%  { background-position: center calc(-4rem + var(--border-size)), bottom center; }
}

@media (prefers-reduced-motion: reduce) {
  .valeur.whatToBlend>.format.gradient::after {
    animation: none;
  }
}



@media (max-width: 42rem) { /* Phone-like */
  .demo-conteneur {
    grid-template-columns: auto;
  }

  #saisie {
    grid-column: 1 / 2;
  }
}

@media (max-width: 30rem) {
  #saisie {
    grid-template-columns: 1fr;
  }

  #entree {
    grid-row: 2;
  }

  label[for=entree] {
    grid-column: 1;
  }

  .exemples-valeurs {
    grid-row: auto;
    grid-column: 1;
  }
}





/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!! SECTION DOCUMENTATION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/



/*
 * Espacement des paragraphes
 */

h1[data-string=titre-section-documentation] {
  max-width: calc(100vw - 2 * .6rem - 1.2rem - .7rem - 4rem - 1.2rem);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  padding-right: .5em;
}

p {
  margin: 0 0 1em 0;
}

p:last-child,
#intro>p {
  margin-bottom: 0;
}

p, li {
  line-height: 1.6em;
}

.documentation p:last-child,
.documentation div:last-child {
  margin: 0;
}

.titre-partie-docu + p, div + p {
  margin-top: 1em;
}

li + li {
  margin-top: .6rem;
}

input + p {
  margin-bottom: 0;
}

div + p.h3,
ul + p.h3,
p + p.h3 {
  margin-top: 2.4rem;
}

a.anchor-dest + p.h3 {
  margin-top: 2em;
}

h2 + a.anchor-dest + p.h3 {
  margin-top: 1em;
}

p.h3 {
  display: flex;
}

p.h3::after {
  content: '';
  display: flex;
  flex-grow: 1;
  height: 1px;
  border-top: 1px dashed var(--h3-color);
  opacity: .3;
  margin-left: .6rem;
  align-self: center;
}

h2 + a.anchor-dest + p.h3::after {
  border-color: transparent;
}

@media (max-width: 42rem) { /* Phone-like */
  ul {
    padding-left: 20px;
  }
}

a#documentation {
  position: absolute;
  top: -3rem;
}



/*
 * Mise en forme des liens et textes mis en avant
 */

a:link,
a:visited {
  color: var(--link-color);
  font-weight: 600;
  text-decoration: none;
  padding: 0 .1em;
  box-shadow: 0 0.1em 0 0 var(--link-underline-color);
}

a:hover,
a:focus,
a:active {
  background-color: var(--link-color);
  box-shadow: 0 0.1em 0 0 var(--link-color);
  color: var(--section-color);
  text-decoration: none;
  border-radius: .2em;
}
a:focus-visible {
  box-shadow: none;
}

strong, em {
  color: var(--text-strong-color);
  font-weight: 600;
}



/*
 * Menu de navigation rapide
 */

aside.nav-documentation {
  grid-row: 4;
  grid-column: 1;
  align-self: start;
  justify-self: end;
  max-width: 20rem;
  margin-top: calc(0.71625 * var(--mod) * var(--mod) * var(--mod) * var(--mod) * 1rem + .6rem);
  margin-right: .6rem;
  margin-left: .6rem;
  line-height: 1em;
  position: sticky;
  top: 1.8rem;
  background-color: var(--section-color);
  border-radius: .6rem;
  padding: .6rem;
}

.documentation>.nav-rapide {
  display: none;
  padding-bottom: .6rem;
  background-color: var(--section-color);
  position: relative;
  z-index: 2;
}

.documentation>.nav-rapide + .exemple {
  display: none;
  position: sticky;
  top: .3rem;
  margin: 0 auto;
  background: var(--section-color) linear-gradient(to right, var(--button-bg-color) 0% 100%);
  z-index: 1;
  margin-top: var(--button-height, 0);
  width: fit-content;
}

.titre-nav-rapide {
  font-size: calc(var(--mod) * 1.1rem);
  line-height: normal;
}

.nav-rapide ul {
  padding-left: 20px;
  margin: 0;
  font-size: .9em;
}

.nav-rapide li + li {
  margin-top: 0;
}

/*.nav-rapide a {
  text-decoration: none;
  box-shadow: none;
}*/

.nav-documentation > input[type=checkbox] {
  height: 0;
  width: 0;
  margin: 0;
  opacity: 0;
  pointer-events: none;
  position: absolute;
}

.nav-documentation > input[type=checkbox] + label {
  display: none;
  place-items: center start;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

@media (max-width: calc(.6rem + 20rem + .6rem + 42rem + .6rem + 20rem + .6rem + 2.4rem)) {
  aside.nav-documentation {
    display: none;
    grid-row: 3;
    grid-column: 2;
    align-self: end;
    justify-self: start;
    padding: .3rem;
    width: 24px;
    height: 24px;
    z-index: 4;
    top: unset;
    bottom: .45rem;
    background-color: var(--body-color);
    margin-left: .45rem;
    margin-bottom: .45rem;
  }

  aside.nav-documentation.on {
    width: auto;
    height: auto;
  }

  .documentation>.nav-rapide/*,
  .documentation>.nav-rapide + .exemple*/ {
    display: block;
  }

  .nav-documentation ul {
    display: none;
  }
  .nav-documentation.on ul {
    display: block;
  }

  .nav-documentation > input[type=checkbox] + label {
    display: grid;
  }
}



/*
 * Titre de partie de documentation
 */

.titre-partie-demo,
.titre-partie-docu {
  color: var(--h3-color);
  font-family: 'Lato';
  display: block;
  position: relative;
}
.titre-partie-docu {
  --margin-top: 1.2em;
  --separator-width: 100%;
  margin-top: var(--margin-top);
}

/* Séparateur entre parties */
.titre-partie-docu::before { 
  content: '';
  display: block;
  width: var(--separator-width);
  height: 4px;
  border-radius: 6px;
  position: absolute;
  top: calc(-0.5 * var(--margin-top));
  left: 50%;
  transform: translate(-50%, 50%);
  background: var(--h3-color);
  opacity: .15;
}

.titre-partie-docu.no-separator {
  --margin-top: .6em;
  --separator-width: 0;
}



/*
 * Exemple de code avec résultat juxtaposé
 */

.example-code {
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: auto auto;
  grid-gap: .6em;
  margin-bottom: 1em;
}

:not(pre) > code[class*="language-"] {
  white-space: normal;
}

.example-code > pre[class*="language-"] {
  margin: 0;
}

.input {
  grid-column: 1 / 2;
  grid-row: 1 / 2;
}

.output {
  grid-row: 2 / 3;
  grid-column: 1 / 2;
}


/*
 * Personnalisation de la coloration syntaxique
 */

.token.property,
.token.tag,
.token.boolean,
.token.number,
.token.constant,
.token.symbol {
  color: var(--token-number);
}

.token.selector,
.token.attr-name,
.token.string,
.token.char,
.token.builtin,
.token.inserted {
  color: var(--token-string);
}

.token.operator,
.token.entity,
.token.url,
.language-css .token.string,
.style .token.string,
.token.variable {
  color: var(--token-operator);
}

.token.atrule,
.token.attr-value,
.token.keyword {
  color: var(--token-keyword);
}

.token.regex,
.token.important {
  color: #e90;
}

.off {
  display: none;
}


/* Sections not to display based on language and prog language */
html[lang="fr"] [lang="en"],
html[lang="en"] [lang="fr"],
html[data-prog-language="js"] [data-prog-language="php"],
html[data-prog-language="php"] [data-prog-language="js"] {
  display: none;
}


/* If animations disabled */
@media (prefers-reduced-motion: reduce) {
  * {
    transition: none !important;
    animation: none !important;
  }
}