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

<h2>Automatic test of pathfinding performance (graph creation: <span class='init'></span>, longest path search: <span class='max'></span>)</h2>

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
  import Couleur, { Graph } from 'colori';



  const colorSpaces = Couleur.colorSpaces;
  let max = 0;

  const tests = [
    { ids: ['srgb', 'lab'], expected: ['srgb', 'srgb-linear', 'xyz-d65', 'xyz-d50', 'lab'] },
    { ids: ['hsl', 'hwb'], expected: ['hsl', 'hwb'] },
    { ids: ['lab', 'lch'], expected: ['lab', 'lch'] },
    { ids: ['hwb', 'display-p3'], expected: ['hwb', 'hsl', 'srgb', 'srgb-linear', 'xyz-d65', 'display-p3-linear', 'display-p3'] },
    { ids: ['prophoto-rgb', 'lab'], expected: ['prophoto-rgb', 'prophoto-rgb-linear', 'xyz-d50', 'lab'] },
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
      let start = performance.now();
      const graph = new Graph(Couleur.colorSpaces);
      let duration = performance.now() - start;
      document.querySelector('.init').innerHTML = `${duration} ms`;

      const tr = document.createElement('tr');
      let result;
      start = performance.now();
      try {
        result = graph.shortestPath(...test.ids).map(node => node.id);
      } catch (error) {
        console.log(error);
      }
      duration = performance.now() - start;
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

  // Topological order test
  // https://en.wikipedia.org/wiki/Topological_sorting#Examples
  /*const exampleGraph = new Graph([
    { id: 5, links: [11] },
    { id: 11, links: [2, 9, 10] },
    { id: 2, links: [] },
    { id: 7, links: [11, 8] },
    { id: 8, links: [9] },
    { id: 9, links: [] },
    { id: 3, links: [8, 10] },
    { id: 10, links: [] }
  ]);
  console.log(exampleGraph.topologicalOrder()); // correct! :)
  */
</script>