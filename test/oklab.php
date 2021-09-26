<?php
require_once '../colori.php';
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

<h1>Testing colori's implementation of oklab and oklch color spaces</h1>

<h2>Testing d65XYZ to oklab conversion</h2>

<table class="xyz">
  <thead>
    <tr>
      <td>d65 xyz</td>
      <td>oklab (result)</td>
      <td>oklab (expected)</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing name to oklab conversion</h2>

<table class="names">
  <thead>
    <tr>
      <td>name</td>
      <td>oklab (result)</td>
      <td>oklab (expected)</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing name to oklch conversion</h2>

<table class="namesLCH">
  <thead>
    <tr>
      <td>name</td>
      <td>oklch (result)</td>
      <td>oklch (expected)</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing oklab to oklch conversion</h2>

<table class="lab_to_lch">
  <thead>
    <tr>
      <td>oklab</td>
      <td>oklch (result)</td>
      <td>oklch (expected)</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing oklch to oklab conversion</h2>

<table class="lch_to_lab">
  <thead>
    <tr>
      <td>oklch</td>
      <td>oklab (result)</td>
      <td>oklab (expected)</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing Palette generation</h2>

<label for="hue">Hue (<span id="hue-value"></span>)</label>
<input type="range" id="hue" min="0" max="360" step="1" value="62">

<div class="paletContainer"></div>

<script type="module">
  // ▼ ES modules cache-busted grâce à PHP
  /*<?php ob_start();?>*/

  import { default as Couleur, Utils as Utils, Palette as DefPalette } from '../colori.js';

  /*<?php $imports = ob_get_clean();
  require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
  echo versionizeFiles($imports, __DIR__); ?>*/



  const tests = [
    [ 0.950, 1.000, 1.089 ],
    [ 1.000, 0.000, 0.000 ],
    [ 0.000, 1.000, 0.000 ],
    [ 0.000, 0.000, 1.000]
  ];
  const expectedResults = [
    [ 1.000, 0.000, 0.000 ],
    [ 0.450, 1.236, -0.019 ],
    [ 0.922, -0.671, 0.263 ],
    [ 0.153, -1.415, -0.449 ]
  ];

  const testsNames = ['white', 'red', 'blue', 'lime'];
  const expectedNames = [
    [ 1, 0, 0 ],
    [ 0.627955, 0.224863, 0.125846 ],
    [ 0.452014, -0.032457, -0.311528 ],
    [ 0.86644, -0.233888, 0.179498 ]
  ];
  const expectedNamesLCH = [
    [ 1, 0, 0 ],
    [ 0.627954, 0.257627, 29.2271 ],
    [ 0.452013, 0.313319, 264.058541 ],
    [ 0.866439, 0.294803, 142.5112 ]
  ];


  
  performTest();



  /* Perform the test */
  function performTest() {
    // XYZ to oklab tests
    for (const [k, test] of Object.entries(tests)) {
      const xyz = test;
      const oklab = Couleur.convert('d65xyz', 'oklab', xyz);
      
      const expected = expectedResults[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.0005);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.01);

      document.querySelector('table.xyz').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${`d65xyz(${test[0]} ${test[1]} ${test[2]})`}</td>
          <td>${oklab.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }

    // name to oklab tests
    for (const [k, test] of Object.entries(testsNames)) {
      const xyz = test;
      const c = new Couleur(test);
      const oklab = c.valuesTo('oklab');
      
      const expected = expectedNames[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.0005);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.01);

      document.querySelector('table.names').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${test}</td>
          <td>${oklab.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }

    // name to oklch tests
    for (const [k, test] of Object.entries(testsNames)) {
      const xyz = test;
      const c = new Couleur(test);
      const oklab = c.valuesTo('oklch');
      
      const expected = expectedNamesLCH[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * 0.0005);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * 0.01);

      document.querySelector('table.namesLCH').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${test}</td>
          <td>${oklab.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }

    //oklab to oklch tests
    for (const [k, test] of Object.entries(expectedNames)) {
      const oklab = test;
      const oklch = Couleur.convert('oklab', 'oklch', oklab);
      
      const expected = expectedNamesLCH[Number(k)];
      const verif = oklch.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * 0.0005);
      const verif2 = oklch.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * 0.01);

      document.querySelector('table.lab_to_lch').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${oklab}</td>
          <td>${oklch.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }

    //oklch to oklab tests
    for (const [k, test] of Object.entries(expectedNamesLCH)) {
      const oklch = test;
      const oklab = Couleur.convert('oklch', 'oklab', oklch);
      
      const expected = expectedNames[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.0005);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.01);

      document.querySelector('table.lch_to_lab').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${oklch}</td>
          <td>${oklab.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }
  }





  const generator = function(hue) {
    const lightnesses = [
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
      .22212874192541768
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
      console.log(h);
      return {
        lightnesses: lightnesses,
        chroma: ch,
        hue: h
      };
    });
  };



  class Palette extends DefPalette {
    constructor(hue) {
      super(hue, generator);
    }
  }



  function updatePalet(hue) {
    const container = document.querySelector('.paletContainer');
    container.innerHTML = '';
    document.querySelector('#hue-value').innerHTML = hue;

    let pal = new Palette(parseFloat(hue));

    for (const nuances of pal.colors) {
      let html = `<div class="palet">`;
      for (const color of nuances) {
        const textColor = color.bestColorScheme() === 'dark' ? 'white' : 'black';
        html += `<div style="--color: ${color.hsl}; color: ${textColor}" data-values="${color.values.join(' ; ')}" data-rgb="${color.rgb}" data-oklch="${color.valuesTo('oklch').join(' ; ')}">Text</div>`;
      }
      html += `</div>`;
      container.innerHTML += html;
    }
  }

  const range = document.querySelector('#hue');
  range.addEventListener('change', event => {
    updatePalet(range.value);
  });
  updatePalet(range.value); // init
  

  const white = new Couleur('color(--oklch 1 0.08854157553403602 121.99998679095636)');
  const quasiwhite = new Couleur('color(--oklch 0.9999999938584008 0.08854157553403602 121.99998679095636)');

  console.log(white.valuesTo('oklch'), quasiwhite.valuesTo('oklch'));

  console.log(Couleur.convert('oklch', 'lin_srgb', [1, 0.08854157553403602, 121.99998679095636]));
  console.log(Couleur.convert('oklch', 'lin_srgb', [1, 0, 121.99998679095636]));
</script>