<?php
// Calcule la version du site
function version()
{
  $listeFichiers = ['../dist/colori.js'];
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

<!doctype html>
<body>

<button type="button" id="run-test">Run test again</button>

<pre class="maximums">{
  "s": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 100%)",
    "maxValue": 1,
    "maxColor": "hsl(0, 100%, 98%)"
  },
  "l": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 1,
    "maxColor": "hsl(0, 0%, 100%)"
  },
  "w": {
    "minValue": 0,
    "minColor": "hsl(0, 100%, 50%)",
    "maxValue": 1,
    "maxColor": "hsl(0, 0%, 100%)"
  },
  "bk": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 100%)",
    "maxValue": 1,
    "maxColor": "hsl(0, 0%, 0%)"
  },
  "ciel": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 1.0000000139649632,
    "maxColor": "hsl(0, 0%, 100%)"
  },
  "ciea": {
    "minValue": -79.2614492214137,
    "minColor": "hsl(120, 100%, 50%)",
    "maxValue": 93.55063152191018,
    "maxColor": "hsl(300, 100%, 50%)"
  },
  "cieb": {
    "minValue": -112.02160321595204,
    "minColor": "hsl(240, 100%, 50%)",
    "maxValue": 93.39797483583392,
    "maxColor": "hsl(60, 100%, 50%)"
  },
  "ciec": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 131.19815281229833,
    "maxColor": "hsl(240, 100%, 50%)"
  },
  "okl": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 0.9999999934735462,
    "maxColor": "hsl(0, 0%, 100%)"
  },
  "oka": {
    "minValue": -0.23388757418790818,
    "minColor": "hsl(120, 100%, 50%)",
    "maxValue": 0.276003610210549,
    "maxColor": "hsl(310, 100%, 50%)"
  },
  "okb": {
    "minValue": -0.3115281476783751,
    "minColor": "hsl(240, 100%, 50%)",
    "maxValue": 0.19856975465179516,
    "maxColor": "hsl(60, 100%, 50%)"
  },
  "okc": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 0.32249096477516437,
    "maxColor": "hsl(300, 100%, 50%)"
  },
  "okh": {
    "minValue": 0,
    "minColor": "hsl(0, 0%, 0%)",
    "maxValue": 359.99773270884543,
    "maxColor": "hsl(340, 44%, 10%)"
  }
}</pre>
<div class="duree"><span class="number">96237</span> colors checked in <span class="time">18011</span> ms</div>

<script type="module">
  import Couleur from '../dist/colori--<?=$version?>.js';

  function findExtremums() {
    const props = ['s', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'okl', 'oka', 'okb', 'okc', 'okh'];
    const max = {};
    for (const prop of props) {
      max[prop] = { minValue: 0, minColor: null, maxValue: 0, maxColor: null };
    }
    let compteur = 0;
    const start = Date.now();

    for (let h = 0; h <= 360; h = h + 10) {
      for (let s = 0; s <= 100; s = s + 2) {
        for (let l = 0; l <= 100; l = l + 2) {
          const couleur = new Couleur(`hsl(${h}, ${s}%, ${l}%)`);
          compteur++;
          for (const prop of props) {
            const p = couleur[prop];
            if (p >= max[prop].maxValue) {
              max[prop].maxValue = p;
              max[prop].maxColor = couleur.hsl;
            }
            else if (p <= max[prop].minValue) {
              max[prop].minValue = p;
              max[prop].minColor = couleur.hsl;
            }
          }
        }
      }
    }

    const end = Date.now();
    return [max, compteur, end - start];
  }

  const button = document.getElementById('run-test');
  button.addEventListener('click', event => {
    const [max, compteur, time] = findExtremums();
    document.querySelector('.maximums').innerHTML = JSON.stringify(max, null, 2);
    document.querySelector('.number').innerHTML = compteur;
    document.querySelector('.time').innerHTML = time;
  });
</script>