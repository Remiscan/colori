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

<div class="maximums">{"s":1,"l":1,"w":1,"bk":1,"ciel":1,"ciea":93.55,"cieb":93.397,"ciec":131.2}</div>
<div class="duree">99159</div>

<script type="module">
  import Couleur from '../colori--<?=$version?>.js';

  const max = { s: 0, l: 0, w: 0, bk: 0, ciel: 0, ciea: 0, cieb: 0, ciec: 0 };
  let compteur = 0;
  const start = Date.now();

  for (let h = 0; h <= 360; h++) {
    for (let s = 0; s <= 100; s++) {
      for (let l = 0; l <= 100; l++) {
        const couleur = new Couleur(`hsl(${h}, ${s}%, ${l}%)`);
        compteur++;
        for (const prop of Object.keys(max)) {
          max[prop] = Math.max(max[prop], couleur[prop]);
        }
      }
    }
  }

  const end = Date.now();

  document.querySelector('.maximums').innerHTML = JSON.stringify(max);
  document.querySelector('.duree').innerHTML = end - start;
</script>