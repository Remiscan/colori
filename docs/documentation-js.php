<p data-string="documentation-intro-p1"><?=$Textes->getString('documentation-intro-p1')?></p>

<p data-string="documentation-intro-p2"><?=$Textes->getString('documentation-intro-p2')?></p>

<h2 class="titre-partie-docu" data-string="documentation-utiliser-titre"><?=$Textes->getString('documentation-utiliser-titre')?></h2>

<p data-string="documentation-utiliser-p1"><?=$Textes->getString('documentation-utiliser-p1')?></p>

<pre><code class="language-javascript">import Colore from 'colori.js';</code></pre>

<p data-string="documentation-utiliser-p2"><?=$Textes->getString('documentation-utiliser-p2')?></p>

<h2 class="titre-partie-docu" data-string="documentation-creer-titre"><?=$Textes->getString('documentation-creer-titre')?></h2>

<p data-string="documentation-creer-p1"><?=$Textes->getString('documentation-creer-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const rosso = new Colore('red');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosso == {
  "r": 1,
  "g": 0,
  "b": 0,
  "a": 1,
  "h": 0,
  "s": 1,
  "l": 0.5,
  "w": 0,
  "bk": 0,
  "name": "red"
}</code></pre>
</div>

<p data-string="documentation-creer-p2"><?=$Textes->getString('documentation-creer-p2')?></p>

<p data-string="documentation-creer-p3"><?=$Textes->getString('documentation-creer-p3')?></p>

<ul>
  <li data-string="documentation-creer-liste-rgb"><?=$Textes->getString('documentation-creer-liste-rgb')?></li>
  <li data-string="documentation-creer-liste-h"><?=$Textes->getString('documentation-creer-liste-h')?></li>
  <li data-string="documentation-creer-liste-sl"><?=$Textes->getString('documentation-creer-liste-sl')?></li>
  <li data-string="documentation-creer-liste-wbk"><?=$Textes->getString('documentation-creer-liste-wbk')?></li>
  <li data-string="documentation-creer-liste-a"><?=$Textes->getString('documentation-creer-liste-a')?></li>
  <li data-string="documentation-creer-liste-name"><?=$Textes->getString('documentation-creer-liste-name')?></li>
</ul>

<h2 class="titre-partie-docu" data-string="documentation-formats-titre"><?=$Textes->getString('documentation-formats-titre')?></h2>

<p data-string="documentation-formats-p1"><?=$Textes->getString('documentation-formats-p1')?></p>

<pre><code class="language-javascript">rosso.hex == '#ff0000'
rosso.rgb == 'rgb(255, 0, 0)'
rosso.hsl == 'hsl(0, 100%, 50%)'
rosso.hwb == 'hwb(0 0% 0%)'</code></pre>

<p data-string="documentation-formats-p2"><?=$Textes->getString('documentation-formats-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const colore = new Colore('rgb(147 28 45 / .3)');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">colore.hex == '#931c2d4d'
colore.rgb == 'rgb(147, 28, 45, 0.3)'
colore.hsl == 'hsl(351, 68%, 34%, 0.3)'
colore.hwb == 'hwb(351 11% 42% / 0.3)'</code></pre>
</div>

<p data-string="documentation-formats-p3"><?=$Textes->getString('documentation-formats-p3')?></p>

<pre><code class="language-javascript">rosso.hex == '#ff0000'
rosso.hexa == '#ff0000ff'

rosso.rgb == 'rgb(255, 0, 0)'
rosso.rgba == 'rgba(255, 0, 0, 1)'

rosso.hsl == 'hsl(0, 100%, 50%)'
rosso.hsla == 'hsla(0, 100%, 50%, 1)'

rosso.hwb == 'hwb(0 0% 0%)'
rosso.hwba == 'hwb(0 0% 0% / 1)'</code></pre>

<h2 class="titre-partie-docu" data-string="documentation-fusion-titre"><?=$Textes->getString('documentation-fusion-titre')?></h2>

<p data-string="documentation-fusion-p1"><?=$Textes->getString('documentation-fusion-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const trasparente = new Colore('rgba(255, 255, 255, .5)');
const opaco = new Colore('rgb(0, 0, 0)');
const fusione = Colore.blend(trasparente, opaco);</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">fusione.rgb == 'rgb(128, 128, 128)'</code></pre>
</div>

<h2 class="titre-partie-docu" data-string="documentation-contraste-titre"><?=$Textes->getString('documentation-contraste-titre')?></h2>

<p data-string="documentation-contraste-p1"><?=$Textes->getString('documentation-contraste-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const bianco = new Colore('white');
const nero = new Colore('black');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">Colore.contrast(nero, bianco) == 21</code></pre>
</div>

<p data-string="documentation-contraste-p2"><?=$Textes->getString('documentation-contraste-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const sfondo = new Colore('darkred');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">sfondo.contrastedText() == 'white'</code></pre>
</div>

<p data-string="documentation-contraste-p3"><?=$Textes->getString('documentation-contraste-p3')?></p>

<pre><code class="language-javascript">sfondo.luminance() == 0.05488967453113127</code></pre>

<h2 class="titre-partie-docu" data-string="documentation-change-titre"><?=$Textes->getString('documentation-change-titre')?></h2>

<p class="h3"><code class="language-javascript">change</code></p>

<p data-string="documentation-change-p1"><?=$Textes->getString('documentation-change-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const nuovoColore = rosso.change('l', '-10%');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 40%)'</code></pre>
</div>

<p data-string="documentation-change-p2"><?=$Textes->getString('documentation-change-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const nuovoColore = rosso.change('l', '35%', {replace: true});</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 35%)'</code></pre>
</div>

<p class="h3"><code class="language-javascript">darken</code>, <code class="language-javascript">lighten</code>, <code class="language-javascript">desaturate</code>, <code class="language-javascript">saturate</code></p>

<p data-string="documentation-change-p3"><?=$Textes->getString('documentation-change-p3')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const nuovoColore = rosso.darken('10%');</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 40%)'</code></pre>
</div>

<p data-string="documentation-change-p4"><?=$Textes->getString('documentation-change-p4')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const nuovoColore = rosso.darken('10%', {scale: true});</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 45%)'</code></pre>
</div>

<p data-string="documentation-change-p5"><?=$Textes->getString('documentation-change-p5')?></p>

<ul>
  <li><code class="language-javascript">darken</code><span data-string="documentation-change-aliases-darken"><?=$Textes->getString('documentation-change-aliases-darken')?></span></li>
  <li><code class="language-javascript">lighten</code><span data-string="documentation-change-aliases-lighten"><?=$Textes->getString('documentation-change-aliases-lighten')?></span></li>
  <li><code class="language-javascript">desaturate</code><span data-string="documentation-change-aliases-desaturate"><?=$Textes->getString('documentation-change-aliases-desaturate')?></span></li>
  <li><code class="language-javascript">saturate</code><span data-string="documentation-change-aliases-saturate"><?=$Textes->getString('documentation-change-aliases-saturate')?></span></li>
</ul>

<h2 class="titre-partie-docu" data-string="documentation-complement-titre"><?=$Textes->getString('documentation-complement-titre')?></h2>

<p data-string="documentation-complement-p1"><?=$Textes->getString('documentation-complement-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-javascript">const rosa = new Colore('pink');
const complementare = rosa.complement();
const negativo = rosa.negative();</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">rosa.rgb == 'rgb(255, 192, 203)'
complementare.rgb == 'rgb(194, 255, 245)'
negativo.rgb == 'rgb(0, 63, 52)'</code></pre>
</div>