<!doctype html>

<!-- ▼ Fichiers cache-busted grâce à PHP -->
<!--<?php ob_start();?>-->

<!-- Import map -->
<script defer src="/_common/polyfills/es-module-shims.js"></script>
<script type="importmap">
{
  "imports": {
    "colori": "/colori/lib/dist/colori.min.js"
  }
}
</script>

<link rel="stylesheet" href="./styles.css">

<!--<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>-->

<h1>Testing colori's implementation of CIEDE2000 color distance</h1>

<h2>Testing formula directly on sample data</h2>

<table class="direct">
  <thead>
    <tr>
      <td>Color 1 (string)</td>
      <td>Color 1 (colori)
      <td>Color 2 (string)</td>
      <td>Color 2 (colori)</td>
      <td>Result</td>
      <td>Expected result</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<h2>Testing colori's distance function on Couleur objects created from sample data*</h2>

<p>* I expect imprecision because colors are stored as pairs of sRGB values, so colori will convert LAB to RGB on object construction, then back again to LAB when computing the distance.</p>

<table class="colori">
  <thead>
    <tr>
      <td>Color 1 (string)</td>
      <td>Color 1 (colori)
      <td>Color 2 (string)</td>
      <td>Color 2 (colori)</td>
      <td>Result</td>
      <td>Expected result</td>
      <td>Passed?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<p>Looks close enough to me?</p>

<script type="module">
  import Couleur, { Distances } from 'colori';



  const tests = JSON.parse("[[[50,2.6772,-79.7751],[50,0,-82.7485]],[[50,3.1571,-77.2803],[50,0,-82.7485]],[[50,2.8361,-74.02],[50,0,-82.7485]],[[50,-1.3802,-84.2814],[50,0,-82.7485]],[[50,-1.1848,-84.8006],[50,0,-82.7485]],[[50,-0.9009,-85.5211],[50,0,-82.7485]],[[50,0,0],[50,-1,2]],[[50,-1,2],[50,0,0]],[[50,2.49,-0.001],[50,-2.49,0.0009]],[[50,2.49,-0.001],[50,-2.49,0.001]],[[50,2.49,-0.001],[50,-2.49,0.0011]],[[50,2.49,-0.001],[50,-2.49,0.0012]],[[50,-0.001,2.49],[50,0.0009,-2.49]],[[50,-0.001,2.49],[50,0.0001,-2.49]],[[50,-0.001,2.49],[50,0.0011,-2.49]],[[50,2.5,0],[50,0,-2.5]],[[50,2.5,0],[73,25,-18]],[[50,2.5,0],[61,-5,29]],[[50,2.5,0],[56,-27,-3]],[[50,2.5,0],[58,24,15]],[[50,2.5,0],[50,3.1736,0.5854]],[[50,2.5,0],[50,3.2972,0]],[[50,2.5,0],[50,1.8634,0.5757]],[[50,2.5,0],[50,3.2592,0.335]],[[60.2574,-34.0099,36.2677],[60.4626,-34.1751,39.4387]],[[63.0109,-31.0961,-5.8663],[62.8187,-29.7946,-4.0864]],[[61.2901,3.7196,-5.3901],[61.4292,2.248,-4.962]],[[35.0831,-44.1164,3.7933],[35.0232,-40.0716,1.5901]],[[22.7233,20.0904,-46.694],[23.0331,14.973,-42.5619]],[[36.4612,47.858,18.3852],[36.2715,50.5065,21.2231]],[[90.8027,-2.0831,1.441],[91.1528,-1.6435,0.0447]],[[90.9257,-0.5406,-0.9208],[88.6381,-0.8985,-0.7239]],[[6.7747,-0.2908,-2.4247],[5.8714,-0.0985,-2.2286]],[[2.0776,0.0795,-1.135],[0.9033,-0.0636,-0.5514]]]");
  const expectedResults = JSON.parse("[2.0425,2.8615,3.4412,1,1,1,2.3669,2.3669,7.1792,7.1792,7.2195,7.2195,4.8045,4.8045,4.7461,4.3065,27.1492,22.8977,31.903,19.4535,1,1,1,1,1.2644,1.263,1.8731,1.8645,2.0373,1.4146,1.4441,1.5381,0.6377,0.9082]");

  
  performTest(document.querySelector('.direct tbody'), 'direct');
  performTest(document.querySelector('.colori tbody'), 'colori');



  /* Perform the test */
  function performTest(table, method) {
    for (const [k, test] of Object.entries(tests)) {
      const c1 = new Couleur(`lab(${test[0][0]}% ${test[0][1]} ${test[0][2]})`);
      const c2 = new Couleur(`lab(${test[1][0]}% ${test[1][1]} ${test[1][2]})`);
      let result;
      if (method === 'direct')
        result = Math.round(10**4 * Distances.CIEDE2000([test[0][0] / 100, test[0][1], test[0][2]], [test[1][0] / 100, test[1][1], test[1][2]])) / 10**4;
      else
        result = Math.round(10**4 * Couleur.distance(c1, c2, { method: 'CIEDE2000' })) / 10**4;
      const expected = expectedResults[Number(k)];
      const verif = Math.abs(result - expected) < 0.00005;
      const verif2 = Math.abs(result - expected) < 0.1;

      table.innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${`lab(${test[0][0]}% ${test[0][1]} ${test[0][2]})`}</td>
          <td>${c1.valuesTo('lab').map(v => Math.round(10**4 * v) / 10**4).join(', ')}</td>
          <td>${`lab(${test[1][0]}% ${test[1][1]} ${test[1][2]})`}</td>
          <td>${c2.valuesTo('lab').map(v => Math.round(10**4 * v) / 10**4).join(', ')}</td>
          <td>${result}</td>
          <td>${expected}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }
  }
</script>