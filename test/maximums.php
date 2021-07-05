<?php
// Calcule la version du site
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

<body>

<pre class="maximums">{
  "s": {
    "value": 1,
    "color": "hsl(0, 100%, 0%)"
  },
  "l": {
    "value": 1,
    "color": "hsl(0, 0%, 100%)"
  },
  "w": {
    "value": 1,
    "color": "hsl(0, 0%, 100%)"
  },
  "bk": {
    "value": 1,
    "color": "hsl(0, 0%, 0%)"
  },
  "ciel": {
    "value": 1,
    "color": "hsl(0, 0%, 100%)"
  },
  "ciea": {
    "value": 93.55,
    "color": "hsl(300, 100%, 50%)"
  },
  "cieb": {
    "value": 93.397,
    "color": "hsl(60, 100%, 50%)"
  },
  "ciec": {
    "value": 131.2,
    "color": "hsl(240, 100%, 50%)"
  }
}</pre>
<div class="duree">3682561 colors checked in 94764 ms</div>

<script type="module">
  import Couleur from '../colori--<?=$version?>.js';

  const props = ['s', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec'];
  const max = {};
  for (const prop of props) {
    max[prop] = { minValue: 0, minColor: null, maxValue: 0, maxColor: null };
  }
  let compteur = 0;
  const start = Date.now();

  for (let h = 0; h <= 360; h++) {
    for (let s = 0; s <= 100; s++) {
      for (let l = 0; l <= 100; l++) {
        const couleur = new Couleur(`hsl(${h}, ${s}%, ${l}%)`);
        compteur++;
        for (const prop of props) {
          if (couleur[prop] > max[prop].maxValue) {
            max[prop].maxValue = couleur[prop];
            max[prop].maxColor = couleur.hsl;
          }
          else if (couleur[prop] < max[prop].minValue) {
            max[prop].minValue = couleur[prop];
            max[prop].minColor = couleur.hsl;
          }
        }
      }
    }
  }

  const end = Date.now();

  document.querySelector('.maximums').innerHTML = JSON.stringify(max, null, 2);
  document.querySelector('.duree').innerHTML = `${compteur} colors checked in ${end - start} ms`;
</script>