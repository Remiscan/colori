<?php
require_once 'colori.php';

// Calcule la version du site
function version()
{
  $listeFichiers = array_diff(scandir(__DIR__), array('..', '.'));
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

$themecolor = new Couleur('rgba(58, 184, 74, 0.4)');
$colorh = new Couleur('hsla(128, 52%, 47%, 0.4)');
$testContraste = new Couleur('hsl(200, 10%, 10%)');
$fuchsia = new Couleur('fuchsia');
$testChangeHue = new Couleur('hsl(350, 25%, 52%)');
?>
<!doctype html>
<html>
  <head> 
    <style>
      body {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }
    </style>
  </head>

  <body>
    <section id="php">
      <h2>Tests de colori.php</h2>

      <?php
      $tests = [
        '$fuchsia = new Couleur("fuchsia"); var_dump($fuchsia);',
        '$color = new Couleur("#e5b"); var_dump($color);',
        '$color = new Couleur("#e5bd"); var_dump($color);',
        '$color = new Couleur("#e5b32c"); var_dump($color);',
        '$color = new Couleur("#e5b32cbd"); var_dump($color);',
        '$themecolor = new Couleur("rgba(58, 184, 74, 0.4)"); var_dump($themecolor);',
        '$colorh = new Couleur("hsla(128, 52%, 47%, 0.4)"); var_dump($colorh);',
        'echo $themecolor->hex();',
        'echo $themecolor->rgb();',
        'echo $themecolor->hsl();',
        'echo $themecolor->hwb();',
        'echo $themecolor->lab();',
        'echo $themecolor->lch();',
        'var_dump($themecolor->change("l", "20%", true));',
        'var_dump($themecolor->change("l", "20%", true)->rgb());',
        'var_dump(Couleur::blend($themecolor, new Couleur("white")));',
        'var_dump(Couleur::blend($themecolor, new Couleur("white"))->rgb());',
        'var_dump($testContraste);',
        'var_dump($testContraste->rgb());',
        'var_dump($testContraste->luminance());',
        'var_dump(Couleur::contrast($testContraste, new Couleur("rgb(58, 184, 74)")));',
        'var_dump($testContraste->contrastedText());',
        'var_dump($testChangeHue->complement()->hsl());',
        'var_dump($testChangeHue->negative()->rgb());',
        'var_dump($testChangeHue->darken("10%")->hsl());',
        'var_dump($testChangeHue->darken("10%", true)->hsl());',
        'var_dump($testChangeHue->darken("10%", false)->hsl());',
        'var_dump($testChangeHue->lighten("10%")->hsl());',
        'var_dump($testChangeHue->lighten("10%", false)->hsl());',
        'var_dump($testChangeHue->desaturate("10%")->hsl());',
        'var_dump($testChangeHue->desaturate("10%", false)->hsl());',
        'var_dump($testChangeHue->desaturate("0.1")->hsl());',
        'var_dump($testChangeHue->desaturate(".1", false)->hsl());',
        'var_dump($testChangeHue->saturate("10%")->hsl());',
        'var_dump($testChangeHue->saturate("10%", false)->hsl());',
        'var_dump($testChangeHue->saturate("0.1")->hsl());',
        'var_dump($testChangeHue->saturate(".1", false)->hsl());',
        'var_dump($themecolor->greyscale()->hsl());',
        'var_dump($themecolor->grayscale()->hsl());',
        'var_dump($themecolor->replace("l", "20%")->hsl());',
        'var_dump($themecolor->scale("l", "20%")->hsl());',
        'var_dump($themecolor->scale("r", "20%")->rgb());',
        'var_dump($themecolor->scale("h", "20%")->hsl());',
        'var_dump($themecolor->scale("bk", "20%")->hwb());',
        'var_dump($themecolor->scale("a", "20%")->hsla());',
        '$testLab = new Couleur("lab(92% -45 9)"); var_dump($color);',
        '$color = new Couleur("lch(92% 46 168)"); var_dump($color);',
        'var_dump($testLab->lab());',
        'var_dump($testLab->change("ciel", "-10%")->lab());',
        'var_dump($testLab->change("ciea", "20")->lab());',
        'var_dump($testLab->change("cieb", "20")->lab());',
        'var_dump($testLab->lch());',
        'var_dump($testLab->change("ciec", "20")->lch());',
        'var_dump($testLab->change("cieh", "20deg")->lch());'
      ];

      foreach($tests as $t) {
        ?>
          <h3><?=$t?></h3>
          <p><?=eval($t)?></p>
        <?php
      }
      ?>
    </section>

    <section id="js">
      <h2>Tests de colori.js</h2>

    </section>

    <script type="module">
      import Colour from './colori--<?=$version?>.js';

      console.log(JSON.stringify(new Colour('red'), null, 2));

      const p = document.getElementById('js').querySelectorAll('pre');
      const couleur = new Colour('rgba(58, 184, 74, 0.4)');
      const couleurh = new Colour('hsla(128, 52%, 47%, 0.4)');
      const testContraste = new Colour('hsl(200, 10%, 10%)');
      const testChangeHue = new Colour('hsl(350, 25%, 52%)');
      const testLab = new Colour('lab(92% -45 9)');

      const tests = [
        `JSON.stringify(new Colour('fuchsia'), null, 2)`,
        `JSON.stringify(new Colour('#e5b'), null, 2)`,
        `JSON.stringify(new Colour('#e5bd'), null, 2)`,
        `JSON.stringify(new Colour('#e5b32c'), null, 2)`,
        `JSON.stringify(new Colour('#e5b32cbd'), null, 2)`,
        `JSON.stringify(new Colour('rgba(58, 184, 74, 0.4)'), null, 2)`,
        `JSON.stringify(new Colour('hsla(128, 52%, 47%, 0.4)'), null, 2)`,
        `couleur.hex`,
        `couleur.rgb`,
        `couleur.hsl`,
        `couleur.hwb`,
        `couleur.lab`,
        `couleur.lch`,
        `JSON.stringify(couleur.change('l', '20%', {replace: true}), null, 2)`,
        `JSON.stringify(couleur.change('l', '20%', true).rgb, null, 2)`,
        `JSON.stringify(Colour.blend(couleur, new Colour('white')), null, 2)`,
        `JSON.stringify(Colour.blend(couleur, new Colour('white')).rgb, null, 2)`,
        `JSON.stringify(couleur.blend(new Colour('white')).rgb, null, 2)`,
        `JSON.stringify(testContraste, null, 2)`,
        `JSON.stringify(testContraste.rgb, null, 2)`,
        `JSON.stringify(testContraste.luminance, null, 2)`,
        `JSON.stringify(Colour.contrast(testContraste, new Colour('rgb(58, 184, 74)')), null, 2)`,
        `JSON.stringify(testContraste.contrast(new Colour('rgb(58, 184, 74)')), null, 2)`,
        `JSON.stringify(testContraste.contrastedText(), null, 2)`,
        `JSON.stringify(testChangeHue.complement().hsl, null, 2)`,
        `JSON.stringify(testChangeHue.negative().rgb, null, 2)`,
        `JSON.stringify(testChangeHue.darken('10%').hsl, null, 2)`,
        `JSON.stringify(testChangeHue.darken('10%', true).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.darken('10%', { scale: true }).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.darken('10%', false).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.darken('10%', { scale: false}).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.lighten('10%').hsl, null, 2)`,
        `JSON.stringify(testChangeHue.lighten('10%', {scale: false}).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.desaturate('10%').hsl, null, 2)`,
        `JSON.stringify(testChangeHue.desaturate('10%', false).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.desaturate(0.1).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.desaturate(.1, false).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.saturate('10%').hsl, null, 2)`,
        `JSON.stringify(testChangeHue.saturate('10%', {scale: false}).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.saturate(0.1).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.saturate(.1, {scale: false}).hsl, null, 2)`,
        `JSON.stringify(testChangeHue.saturate(0).hsl, null, 2)`,
        `JSON.stringify(couleur.greyscale().hsl, null, 2)`,
        `JSON.stringify(couleur.grayscale().hsl, null, 2)`,
        `JSON.stringify(couleur.replace('l', '20%').hsl, null, 2)`,
        `JSON.stringify(couleur.scale('l', '20%').hsl, null, 2)`,
        `JSON.stringify(couleur.scale('r', '20%').rgb, null, 2)`,
        `JSON.stringify(couleur.scale('h', '20%').hsl, null, 2)`,
        `JSON.stringify(couleur.scale('bk', '20%').hwb, null, 2)`,
        `JSON.stringify(couleur.scale('a', '20%').hsla, null, 2)`,
        `JSON.stringify(new Colour('lab(92% -45 9)'), null, 2)`,
        `JSON.stringify(new Colour('lch(92% 46 168)'), null, 2)`,
        `JSON.stringify(testLab.lab, null, 2)`,
        `JSON.stringify(testLab.change('ciel', '-10%').lab, null, 2)`,
        `JSON.stringify(testLab.change('ciea', '20').lab, null, 2)`,
        `JSON.stringify(testLab.change('cieb', '20').lab, null, 2)`,
        `JSON.stringify(testLab.lch, null, 2)`,
        `JSON.stringify(testLab.change('ciec', '20').lch, null, 2)`,
        `JSON.stringify(testLab.change('cieh', '20deg').lch, null, 2)`,
      ];
      const section = document.getElementById('js');

      tests.forEach((test, k) => {
        const h3 = document.createElement('h3');
        h3.innerHTML = tests[k].replace(/^JSON\.stringify\(|, null, 2\)$/g, '');
        const pre = document.createElement('pre');
        pre.innerHTML = eval(tests[k]);
        section.appendChild(h3);
        section.appendChild(pre);
      });
    </script>
  </body>
</html>