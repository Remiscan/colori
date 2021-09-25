<?php
require_once '../colori.php';
function version()
{
  $listeFichiers = ['../colori.js'];
  $versionFichiers = 0;
  foreach($listeFichiers as $fichier)
  {
    $date_fichier = filemtime($fichier);
    if ($date_fichier > $versionFichiers)
      $versionFichiers = $date_fichier;
  }
  $versionFichiers = date('Y.m.d_H.i.s', $versionFichiers);
  return $versionFichiers;
}
$version = version();
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
</style>

<h1>Testing colori's implementation of oklab and oklch color spaces</h1>

<h2>Testing formula directly on sample data</h2>

<table class="direct">
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

<script type="module">
  import { default as Couleur, Utils as Utils } from '../colori--<?=$version?>.js';

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


  
  performTest();



  /* Perform the test */
  function performTest() {
    for (const [k, test] of Object.entries(tests)) {
      const xyz = test;
      const oklab = Couleur.convert('d65xyz', 'oklab', xyz);
      
      const expected = expectedResults[Number(k)];
      const verif = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.0005);
      const verif2 = oklab.every((e, k) => Math.abs(e - expected[k]) < 0.01);

      document.querySelector('table').innerHTML += `
        <tr class="${verif ? 'yes' : verif2 ? 'close' : 'no'}">
          <td>${`d65xyz(${test[0]} ${test[1]} ${test[2]})`}</td>
          <td>${oklab.join(' ')}</td>
          <td>${expected.join(' ')}</td>
          <td>${verif ? 'yes' : verif2 ? 'close' : 'no'}</td>
        </tr>
      `;
    }
  }

  const c = new Couleur('color(--oklab 1.000 0.000 0.000)');
  console.log(c.valuesTo('d65xyz'));
</script>