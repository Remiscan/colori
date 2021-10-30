<?php
require_once '../dist/colori.php';
?>

<style>
  html { color-scheme: light dark; }
  .yes { background-color: palegreen; }
  .close { background-color: gold; }
  .no { background-color: pink; }
  
  @media (prefers-color-scheme: dark) {
    body {color: white; }
    .yes { background-color: <?php $c = new Couleur('palegreen'); echo $c->replace('a', '.2')->hsl(); ?>; }
    .close { background-color: <?php $c = new Couleur('gold'); echo $c->replace('a', '.2')->hsl(); ?>; }
    .no { background-color: <?php $c = new Couleur('pink'); echo $c->replace('a', '.2')->hsl(); ?>;}
  }

  .palet {
    display: flex;
    flex-direction: row;
  }

  .palet>div {
    width: 4em;
    height: 3em;
    background-color: var(--color);
    margin: 5px;
    border-radius: 10px;
    display: grid;
    place-content: center;
  }
</style>

<h1>Testing colori's Palette generation</h1>

<label for="hue">Hue</label>
<input type="range" id="hue" min="0" max="360" step="1" value="62">
<span id="hue-value"></span>

<script type="module">
  // ▼ ES modules cache-busted grâce à PHP
  /*<?php ob_start();?>*/

  import Couleur, { Palette } from '../dist/colori.js';

  /*<?php $imports = ob_get_clean();
  require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
  echo versionizeFiles($imports, __DIR__); ?>*/



  // Monet-like palet

  const monetGenerator = function(hue) {
    const lightnesses = [
      1,
      .9880873963836093,
      .9551400440214246,
      .9127904082618294,
      .8265622041716898,
      .7412252673769428,
      .653350946076347,
      .5624050605208273,
      .48193149058901036,
      .39417829080418526,
      .3091856317280812,
      .22212874192541768,
      0
    ];
    const baseChroma = 0.1328123146401862;
    const chromas = [
      baseChroma / 12,
      baseChroma / 6,
      baseChroma,
      baseChroma / 3,
      baseChroma * 2 / 3
    ];

    return chromas.map((ch, k) => {
      const h = k < 4 ? hue : (hue + 60);
      return {
        lightnesses: lightnesses,
        chroma: ch,
        hue: h
      };
    });
  };

  class MonetPalette extends Palette {
    constructor(hue) { super(hue, monetGenerator); }
  }



  // Whatever palet

  const whateverGenerator = function(hue) {
    const lightnesses = [];
    let i = 1;
    while (i >= 0) {
      lightnesses.push(i);
      i -= .1;
    }
    const chroma = 0.1;
    const hues = [hue, hue, hue + 180, hue + 180];

    return hues.map((h, k) => {
      return {
        lightnesses,
        chroma: (k % 2 === 1) ? chroma / 3 : chroma,
        hue: h
      };
    });
  };

  class WhateverPalette extends Palette {
    constructor(hue) { super(hue, whateverGenerator); }
  }



  // Contrasted palet

  const contrastedGenerator = function(hue) {
    const grey = new Couleur('color(oklab .5 0 0)');
    const light = [];
    const dark = [];
    const contrasts = [65, 75, 85, 95, 100, 105];
    for (const i of contrasts) {
      light.push(grey.improveContrast('black', i, { lower: true, as: 'background' }));
      dark.push(grey.improveContrast('white', i, { lower: true, as: 'background' }));
    }
    const lightnesses = [...light.reverse(), ...dark].map(c => c.okl);

    const chromas = [0, 0.022, 0.043, 0.13];
    return chromas.map((c, k) => {
      return {
        lightnesses,
        chroma: c,
        hue
      };
    });
  };

  class ContrastedPalette extends Palette {
    constructor(hue) { super(hue, contrastedGenerator); }
  }



  const classes = [ 'MonetPalette', 'ContrastedPalette' ];

  function makePalet(className, h) {
    const hue = parseFloat(h);
    return eval(`new ${className}(${hue})`);
  }



  function updatePalets(hue) {
    document.querySelector('#hue-value').innerHTML = hue;

    const containers = [...document.querySelectorAll('.paletContainer')];
    for (const container of containers) {
      container.innerHTML = '';

      let pal = makePalet(container.dataset.type, hue);

      for (const nuances of pal.colors) {
        let html = `<div class="palet">`;
        for (const color of nuances) {
          const textColor = color.bestColorScheme() === 'dark' ? 'white' : 'black';
          const contrast = Couleur.contrast(textColor, color, { method: 'apca' });
          const otherColor = textColor === 'white' ? 'black' : 'white';
          const otherContrast = Couleur.contrast(otherColor, color, { method: 'apca' });
          const cBlack = Couleur.contrast('black', color, { method: 'apca' });
          const cWhite = Couleur.contrast('white', color, { method: 'apca' });
          html += `<div style="--color: ${color.hsl}; color: ${textColor}; display: grid; place-items: center; font-family: system-ui;"
                        data-values="${color.values.join(' ; ')}"
                        data-rgb="${color.rgb}"
                        data-oklch="${color.valuesTo('oklch').join(' ; ')}">
            <span style="color: ${textColor}; font-weight: 600;">${Math.round(100 * contrast) / 100}</span>
            <span style="color: ${otherColor}; font-size: .8em">${Math.round(100 * otherContrast) / 100}</span>
          </div>`;
        }
        html += `</div>`;
        container.innerHTML += html;
      }
    }
  }



  function initPalets() {
    for (const c of classes) {
      document.body.innerHTML += `
        <h2>Palette: ${c}</h2>
        <div class="paletContainer" data-type="${c}"></div>
      `;
    }
    const input = document.querySelector('#hue');
    input.addEventListener('change', event => {
      updatePalets(input.value);
    });
    updatePalets(input.value);
  }
  initPalets();
</script>