<p data-string="documentation-intro-p1-php"><?=$Textes->getString('documentation-intro-p1-php')?></p>

<p data-string="documentation-intro-p2"><?=$Textes->getString('documentation-intro-p2')?></p>

<h2 class="titre-partie-docu" data-string="documentation-utiliser-titre-php"><?=$Textes->getString('documentation-utiliser-titre-php')?></h2>

<p data-string="documentation-utiliser-p1-php"><?=$Textes->getString('documentation-utiliser-p1-php')?></p>

<pre><code class="language-php">require_once 'colori.php';</code></pre>

<p data-string="documentation-utiliser-p2-php"><?=$Textes->getString('documentation-utiliser-p2-php')?></p>

<p data-string="documentation-utiliser-p3-php"><?=$Textes->getString('documentation-utiliser-p3-php')?></p>

<h2 class="titre-partie-docu" data-string="documentation-creer-titre"><?=$Textes->getString('documentation-creer-titre')?></h2>

<p data-string="documentation-creer-p1-php"><?=$Textes->getString('documentation-creer-p1-php')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$rouge = new Couleur('red');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge == object(Couleur)[1]
  public 'r' => float 1
  public 'g' => float 0
  public 'b' => float 0
  public 'h' => float 0
  public 's' => float 1
  public 'l' => float 0.5
  public 'w' => float 0
  public 'bk' => float 0
  public 'a' => float 1
  public 'ciel' => float 0.5429173546502365
  public 'ciea' => float 80.81243175889557
  public 'cieb' => float 69.8850744848609
  public 'ciec' => float 106.83900393835908
  public 'cieh' => float 0.11347954138219268
  public 'name' => string 'red'</code></pre>
</div>

<p data-string="documentation-creer-p2-php"><?=$Textes->getString('documentation-creer-p2-php')?></p>

<p data-string="documentation-creer-p3"><?=$Textes->getString('documentation-creer-p3')?></p>

<ul>
  <li data-string="documentation-creer-liste-rgb"><?=$Textes->getString('documentation-creer-liste-rgb')?></li>
  <li data-string="documentation-creer-liste-h"><?=$Textes->getString('documentation-creer-liste-h')?></li>
  <li data-string="documentation-creer-liste-sl"><?=$Textes->getString('documentation-creer-liste-sl')?></li>
  <li data-string="documentation-creer-liste-wbk"><?=$Textes->getString('documentation-creer-liste-wbk')?></li>
  <li data-string="documentation-creer-liste-a"><?=$Textes->getString('documentation-creer-liste-a')?></li>
  <li data-string="documentation-creer-liste-ciel"><?=$Textes->getString('documentation-creer-liste-ciel')?></li>
  <li data-string="documentation-creer-liste-cieab"><?=$Textes->getString('documentation-creer-liste-cieab')?></li>
  <li data-string="documentation-creer-liste-ciec"><?=$Textes->getString('documentation-creer-liste-ciec')?></li>
  <li data-string="documentation-creer-liste-cieh"><?=$Textes->getString('documentation-creer-liste-cieh')?></li>
  <li data-string="documentation-creer-liste-name"><?=$Textes->getString('documentation-creer-liste-name')?></li>
</ul>

<h2 class="titre-partie-docu" data-string="documentation-formats-titre"><?=$Textes->getString('documentation-formats-titre')?></h2>

<p data-string="documentation-formats-p1-php"><?=$Textes->getString('documentation-formats-p1-php')?></p>

<pre><code class="language-php">$rouge->hex() == '#ff0000'
$rouge->rgb() == 'rgb(255, 0, 0)'
$rouge->hsl() == 'hsl(0, 100%, 50%)'
$rouge->hwb() == 'hwb(0 0% 0%)'
$rouge->lab() == 'lab(54% 81 70)'
$rouge->lch() == 'lch(54% 107 41)'</code></pre>

<p data-string="documentation-formats-p2"><?=$Textes->getString('documentation-formats-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$couleur = new Couleur('rgb(147 28 45 / .3)');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$couleur->hex() == '#931c2d4d'
$couleur->rgb() == 'rgb(147, 28, 45, 0.3)'
$couleur->hsl() == 'hsl(351, 68%, 34%, 0.3)'
$couleur->hwb() == 'hwb(351 11% 42% / 0.3)'
$couleur->lab() == 'lab(33% 49 23 / 0.3)'
$couleur->lch() == 'lch(33% 54 25 / 0.3)'</code></pre>
</div>

<p data-string="documentation-formats-p3"><?=$Textes->getString('documentation-formats-p3')?></p>

<pre><code class="language-php">$rouge->hex() == '#ff0000'
$rouge->hexa() == '#ff0000ff'

$rouge->rgb() == 'rgb(255, 0, 0)'
$rouge->rgba() == 'rgba(255, 0, 0, 1)'

$rouge->hsl() == 'hsl(0, 100%, 50%)'
$rouge->hsla() == 'hsla(0, 100%, 50%, 1)'

$rouge->hwb() == 'hwb(0 0% 0%)'
$rouge->hwba() == 'hwb(0 0% 0% / 1)'

$rouge->lab() == 'lab(54% 81 70)'
$rouge->laba() == 'lab(54% 81 70 / 1)'

$rouge->lch() == 'lch(54% 107 41)'
$rouge->lcha() == 'lch(54% 107 41 / 1)'</code></pre>

<h2 class="titre-partie-docu" data-string="documentation-change-titre"><?=$Textes->getString('documentation-change-titre')?></h2>

<p class="h3"><code class="language-php">change</code></p>

<p data-string="documentation-change-p1"><?=$Textes->getString('documentation-change-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->change('l', '-10%');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 40%)'</code></pre>
</div>

<p data-string="documentation-change-p2"><?=$Textes->getString('documentation-change-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->change('l', '35%', {replace: true});</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 35%)'</code></pre>
</div>

<p class="h3"><code class="language-php">replace</code>, <code class="language-php">scale</code></p>

<p data-string="documentation-change-p6"><?=$Textes->getString('documentation-change-p6')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->replace('l', '20%');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 20%)'</code></pre>
</div>

<p data-string="documentation-change-p7"><?=$Textes->getString('documentation-change-p7')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->scale('l', '20%');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 10%)'</code></pre>
</div>

<p class="h3"><code class="language-php">darken</code>, <code class="language-php">lighten</code>, <code class="language-php">desaturate</code>, <code class="language-php">saturate</code></p>

<p data-string="documentation-change-p3"><?=$Textes->getString('documentation-change-p3')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->darken('10%');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 45%)'</code></pre>
</div>

<p data-string="documentation-change-p4"><?=$Textes->getString('documentation-change-p4')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->darken('10%', {scale: false});</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 100%, 40%)'</code></pre>
</div>

<p data-string="documentation-change-p5"><?=$Textes->getString('documentation-change-p5')?></p>

<ul>
  <li><code class="language-php">darken</code><span data-string="documentation-change-aliases-darken"><?=$Textes->getString('documentation-change-aliases-darken')?></span></li>
  <li><code class="language-php">lighten</code><span data-string="documentation-change-aliases-lighten"><?=$Textes->getString('documentation-change-aliases-lighten')?></span></li>
  <li><code class="language-php">desaturate</code><span data-string="documentation-change-aliases-desaturate"><?=$Textes->getString('documentation-change-aliases-desaturate')?></span></li>
  <li><code class="language-php">saturate</code><span data-string="documentation-change-aliases-saturate"><?=$Textes->getString('documentation-change-aliases-saturate')?></span></li>
</ul>

<p class="h3"><code class="language-javascript">greyscale</code> / <code class="language-javascript">grayscale</code></p>

<p data-string="documentation-change-p8"><?=$Textes->getString('documentation-change-p8')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$nouvelleCouleur = $rouge->greyscale();</code></pre>

  <pre class="output"><code slot="output" class="language-javascript">$rouge->hsl() == 'hsl(0, 100%, 50%)'
$nouvelleCouleur->hsl() == 'hsl(0, 0%, 50%)'</code></pre>
</div>

<p class="h3"><code class="language-javascript">complement</code>, <code class="language-javascript">negative</code> / <code class="language-javascript">invert</code></p>

<p data-string="documentation-complement-p1"><?=$Textes->getString('documentation-complement-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$rose = new Couleur('pink');
$complementaire = $rose->complement();
$negative = $rose->negative();</code></pre>

  <pre class="output"><code slot="output" class="language-php">$rose->rgb() == 'rgb(255, 192, 203)'
$complementare->rgb() == 'rgb(194, 255, 245)'
$negative->rgb() == 'rgb(0, 63, 52)'</code></pre>
</div>

<p class="h3"><code class="language-javascript">blend</code></p>

<p data-string="documentation-fusion-p1"><?=$Textes->getString('documentation-fusion-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$transparente = new Couleur('rgba(255, 255, 255, .5)');
$opaque = new Couleur('rgb(0, 0, 0)');
$fusion = Couleur::blend($transparente, $opaque);</code></pre>

  <pre class="output"><code slot="output" class="language-php">$fusion->rgb() == 'rgb(128, 128, 128)'</code></pre>
</div>

<h2 class="titre-partie-docu" data-string="documentation-comparer-titre"><?=$Textes->getString('documentation-comparer-titre')?></h2>

<p class="h3"><code class="language-javascript">contrast</code></p>

<p data-string="documentation-contraste-p1"><?=$Textes->getString('documentation-contraste-p1')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$blanc = new Couleur('white');
$noir = new Couleur('black');</code></pre>

  <pre class="output"><code slot="output" class="language-php">Couleur::contrast($noir, $blanc) == 21</code></pre>
</div>

<p data-string="documentation-contraste-p2"><?=$Textes->getString('documentation-contraste-p2')?></p>

<div class="example-code">
  <pre class="input"><code class="language-php">$fond = new Couleur('darkred');</code></pre>

  <pre class="output"><code slot="output" class="language-php">$fond->contrastedText() == 'white'</code></pre>
</div>

<p data-string="documentation-contraste-p3"><?=$Textes->getString('documentation-contraste-p3')?></p>

<pre><code class="language-php">$fond->luminance() == 0.05488967453113127</code></pre>