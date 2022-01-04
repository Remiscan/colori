<?php
require_once '../dist/colori.php';
?>

<link rel="stylesheet" href="./styles.css">
<style>
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

  const monetGenerator = function(color) {
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
    const [x, chroma, hue] = color.valuesTo('oklch');
    const chromas = [
      chroma / 12,
      chroma / 6,
      chroma,
      chroma / 3,
      chroma * 2 / 3
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
    constructor(hue) {
      const color = new Couleur(`color(oklch .5 0.1328123146401862 ${hue})`);
      super(color, monetGenerator);
    }
  }



  // Whatever palet

  const whateverGenerator = function(color) {
    const lightnesses = [];
    let i = 1;
    while (i >= 0) {
      lightnesses.push(i);
      i -= .1;
    }
    const [x, chroma, hue] = color.valuesTo('oklch');
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
    constructor(hue) {
      const color = new Couleur(`color(oklch .5 0.1 ${hue})`);
      super(color, whateverGenerator);
    }
  }



  // Contrasted palet

  const contrastedGenerator = function(color) {
    /*const grey = new Couleur('color(oklab .5 0 0)');
    const light = [];
    const dark = [];
    const contrasts = [65, 75, 85, 95, 100, 105];
    for (const i of contrasts) {
      light.push(grey.improveContrast('black', i, { lower: true, as: 'background' }));
      dark.push(grey.improveContrast('white', i, { lower: true, as: 'background' }));
    }
    const lightnesses = [...light.reverse(), ...dark].map(c => c.okl);
    console.log(JSON.stringify(lightnesses));*/

    // Lightnesses computed with the previous commented code
    const lightnesses = [0.9948730403485463,0.969787591129796,0.9442748958172962,0.8917236262860462,0.8368530208172963,0.7792968684735463,0.6503906184735461,0.5809936458172961,0.5017700130047963,0.4064331001636162,0.3482665970166082,0.2636718715583154];
    const [x, chroma, hue] = color.valuesTo('oklch');
    const chromas = [0, chroma / 6, chroma / 3, 0.13];
    return chromas.map((c, k) => {
      return {
        lightnesses,
        chroma: c,
        hue
      };
    });
  };

  // improveContrast after generating each color
  // (way slower, more precise, maybe not worth it)
  // (contrasts may still be very slightly lower than requested because of the clamping to srgb in Palette constructor)
  /*const contrastedGenerator2 = function(color) {
    const [x, chroma, hue] = color.valuesTo('oklch');
    const contrasts = [65, 75, 85, 95, 100, 105];
    const chromas = [0, chroma / 6, chroma / 3, chroma];
    return chromas.map((ch, k) => {
      const base = (new Couleur(`color(oklch .5 ${ch} ${hue})`)).toGamut('srgb');
      const light = [];
      const dark = [];
      for (const c of contrasts) {
        light.push(base.improveContrast('black', c, { lower: true, as: 'background' }));
        dark.push(base.improveContrast('white', c, { lower: true, as: 'background' }));
      }
      const lightnesses = [...light.reverse(), ...dark].map(e => e.okl);
      return {
        lightnesses,
        chroma: ch,
        hue
      };
    });
  };*/

  class ContrastedPalette extends Palette {
    constructor(hue) {
      const color = new Couleur(`color(oklch .5 0.13 ${hue})`);
      super(color, contrastedGenerator);
    }
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