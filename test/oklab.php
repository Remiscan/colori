<!doctype html>

<!-- ▼ Fichiers cache-busted grâce à PHP -->
<!--<?php ob_start();?>-->

<!-- Import map -->
<script defer src="/_common/polyfills/es-module-shims.js"></script>
<script type="importmap">
{
  "imports": {
    "colori": "/colori/dist/colori.min.js"
  }
}
</script>

<link rel="stylesheet" href="./styles.css">

<!--<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>-->

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

<script type="module">
  import Couleur from 'colori';



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
    let successDistance = 0.0005;
    let closeDistance = 0.01;

    const ignoreLCH = (oklch, k) => (
      ((oklch[0] < successDistance || oklch[0] > 1 - successDistance) && k > 0) // chroma and hue don't matter for black or white
      || (oklch[1] < successDistance && k === 2) // hue doesn't matter for greys
    );

    const ignoreLAB = (oklab, k) => (
      ((oklab[0] < successDistance || oklab[0] > 1 - successDistance) && k > 0) // A and B don't matter for black or white
    );

    // XYZ to oklab tests
    for (const [k, test] of Object.entries(tests)) {
      const xyz = test;
      const oklab = Couleur.convert('xyz-d65', 'oklab', xyz);
      
      const expected = expectedResults[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < successDistance);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < closeDistance);

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
      const verif = oklab.every((e, k) => ignoreLAB(oklab, k) || Math.abs(e - expected[k]) < successDistance);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < closeDistance);

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
      const oklch = c.valuesTo('oklch');
      
      const expected = expectedNamesLCH[Number(k)];
      const verif = oklch.every((e, k) => ignoreLCH(oklch, k) || Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * successDistance);
      const verif2 = oklch.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * closeDistance);

      document.querySelector('table.namesLCH').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${test}</td>
          <td>${oklch.join(' ')}</td>
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
      const verif = oklch.every((e, k) => ignoreLCH(oklch, k) || Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * successDistance);
      const verif2 = oklch.every((e, k) => Math.abs(e - expected[k]) < (k == 2 ? 360 : 1) * closeDistance);

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
      const verif = oklab.every((e, k) => ignoreLAB(oklab, k) || Math.abs(e - expected[k]) < successDistance);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < closeDistance);

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
</script>