<table>
  <thead>
    <tr>
      <td>Début</td>
      <td>Fin</td>
      <td>Chemin trouvé</td>
      <td>Chemin attendu</td>
      <td>Réussi ?</td>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script>
const colorSpaces = [
  {
    id: 'srgb',
    whitepoint: 'd65',
    CSSformat: 'rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    functionsTo: ['lin_srgb', 'hsl']
  }, {
    id: 'lin_srgb',
    functionsTo: ['srgb', 'd65xyz']
  }, {
    id: 'hsl',
    whitepoint: 'd65',
    CSSformat: 'hsl',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    functionsTo: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    whitepoint: 'd65',
    CSSformat: 'hwb',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    functionsTo: ['hsl']
  }, {
    id: 'lab',
    whitepoint: 'd50',
    CSSformat: 'lab',
    gamut: [ [0, 4], [-Infinity, Infinity], [-Infinity, Infinity] ],
    functionsTo: ['xyz', 'lch']
  }, {
    id: 'lch',
    whitepoint: 'd50',
    CSSformat: 'lch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    functionsTo: ['lab']
  }, {
    id: 'xyz',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    functionsTo: ['lab', 'd65xyz', 'lin_prophoto-rgb']
  }, {
    id: 'd65xyz',
    whitepoint: 'd65',
    functionsTo: ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
  }, {
    id: 'display-p3',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    functionsTo: ['lin_display-p3']
  }, {
    id: 'lin_display-p3',
    functionsTo: ['display-p3', 'd65xyz']
  }, {
    id: 'a98-rgb',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    functionsTo: ['lin_a98-rgb']
  }, {
    id: 'lin_a98-rgb',
    functionsTo: ['a98-rgb', 'd65xyz']
  }, {
    id: 'prophoto-rgb',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    functionsTo: ['lin_prophoto-rgb', 'xyz']
  }, {
    id: 'lin_prophoto-rgb',
    functionsTo: ['prophoto-rgb']
  }, {
    id: 'rec2020',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    functionsTo: ['lin_rec2020']
  }, {
    id: 'lin_rec2020',
    functionsTo: ['rec2020', 'd65xyz']
  }
];


function findPath(startID, endID) {
  // Source of the math: https://en.wikipedia.org/wiki/Breadth-first_search
  const startTime = Date.now();
  const graph = colorSpaces.map(space => {
    return {
      id: space.id,
      links: space.functionsTo,
      visited: false,
      predecessorID: null
    };
  });

  if (startID === endID) return [];

  const [start, end] = [startID, endID].map(e => graph.find(s => s.id === e));
  const queue = [];

  start.visited = true;
  queue.push(start);
  
  let found = false;
  walk: while (queue.length > 0) {
    const current = queue.shift();
    if (current.id === end.id) {
      found = true;
      break walk;
    }

    for (const neighbourID of current.links) {
      const neighbour = graph.find(s => s.id === neighbourID);
      if (neighbour.visited === false) {
        neighbour.visited = true;
        neighbour.predecessorID = current.id;
        queue.push(neighbour);
      }
    }
  }

  if (!found) throw 'No path';

  const path = [];
  path.push(end.id);
  let current = end;
  while (current.predecessorID != null) {
    path.push(current.predecessorID);
    current = graph.find(s => s.id === current.predecessorID)
  }

  console.log(`Conversion path search took ${Date.now() - startTime} ms`);
  return path.reverse();
}

const tests = [
  { ids: ['srgb', 'lab'], expected: ['srgb', 'lin_srgb', 'd65xyz', 'xyz', 'lab'] },
  { ids: ['hsl', 'hwb'], expected: ['hsl', 'hwb'] },
  { ids: ['lab', 'lch'], expected: ['lab', 'lch'] },
  { ids: ['hwb', 'display-p3'], expected: ['hwb', 'hsl', 'srgb', 'lin_srgb', 'd65xyz', 'lin_display-p3', 'display-p3'] },
  { ids: ['prophoto-rgb', 'lab'], expected: ['prophoto-rgb', 'xyz', 'lab'] },
  { ids: ['lab', 'lab'], expected: [] }
];

const table = document.querySelector('tbody');
for (const test of tests) {
  const tr = document.createElement('tr');
  let result;
  try {
    result = findPath(...test.ids);
  } catch (error) {
    result = [];
  }
  tr.innerHTML = `
    <td>${test.ids[0]}</td>
    <td>${test.ids[1]}</td>
    <td>${JSON.stringify(result)}</td>
    <td>${JSON.stringify(test.expected)}</td>
    <td>${JSON.stringify(test.expected.every((e, k) => e === result[k]))}</td>
  `;
  table.appendChild(tr);
}
</script>