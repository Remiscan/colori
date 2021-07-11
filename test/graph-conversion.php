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
  html { color-scheme: dark; }
  .yes {
    background-color: <?php $c = new Couleur('palegreen'); echo $c->replace('a', '.2')->hsl(); ?>;
  }
  .no {
    background-color: <?php $c = new Couleur('pink'); echo $c->replace('a', '.2')->hsl(); ?>;
  }
</style>

<h1>Testing colori's pathfinding conversion</h1>

<h2>Comparison with expected results</h2>

<table>
  <thead>
    <tr>
      <td>From</td>
      <td>To</td>
      <td>Found path</td>
      <td>Expected path</td>
      <td>Passed?</td>
      <td>Duration</td>
    </tr>
  </thead>
  <tbody class="manual">
  </tbody>
</table>

<h2>Automatic test of pathfinding performance (max: <span class='max'></span>)</h2>

<table>
  <thead>
    <tr>
      <td>From</td>
      <td>To</td>
      <td>Found path</td>
      <td></td>
      <td></td>
      <td>Duration</td>
    </tr>
  </thead>
  <tbody class="auto">
  </tbody>
</table>

<script type="module">
  import { default as Couleur, Utils as Utils, Graph as Graph } from '../colori--<?=$version?>.js';

  const colorSpaces = Couleur.colorSpaces;
  let max = 0;

  const tests = [
    { ids: ['srgb', 'lab'], expected: ['srgb', 'lin_srgb', 'd65xyz', 'xyz', 'lab'] },
    { ids: ['hsl', 'hwb'], expected: ['hsl', 'hwb'] },
    { ids: ['lab', 'lch'], expected: ['lab', 'lch'] },
    { ids: ['hwb', 'display-p3'], expected: ['hwb', 'hsl', 'srgb', 'lin_srgb', 'd65xyz', 'lin_display-p3', 'display-p3'] },
    { ids: ['prophoto-rgb', 'lab'], expected: ['prophoto-rgb', 'lin_prophoto-rgb', 'xyz', 'lab'] },
    { ids: ['lab', 'lab'], expected: [] }
  ];
  performTest(tests, document.querySelector('tbody.manual'));

  const allTests = [];
  for (const space1 of colorSpaces) {
    for (const space2 of colorSpaces) {
      allTests.push({ ids: [space1.id, space2.id] });
    }
  }
  performTest(allTests, document.querySelector('tbody.auto'));


  function performTest(tests, table) {
    for (const test of tests) {
      const graph = new Graph(Couleur.colorSpaces);

      const tr = document.createElement('tr');
      let result;
      const start = performance.now();
      try {
        result = graph.shortestPath(...test.ids);
      } catch (error) {
        console.log(error);
      }
      const duration = performance.now() - start;
      let verif = '';
      if (test.expected) verif = test.expected.every((e, k) => e === result[k]);

      if (duration > max) { max = duration; document.querySelector('.max').innerHTML = `${max} ms`; }
      if (test.expected) tr.classList.add(`${verif ? 'yes' : 'no'}`);

      tr.innerHTML = `
        <td>${test.ids[0]}</td>
        <td>${test.ids[1]}</td>
        <td>${JSON.stringify(result)}</td>
        <td>${(test.expected) ? JSON.stringify(test.expected) : ''}</td>
        <td>${(test.expected) ? JSON.stringify(verif) : ''}</td>
        <td>${duration} ms</td>
      `;
      table.appendChild(tr);
    }
  }
</script>