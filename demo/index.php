<?php
require_once '../colori.php';

$commonDir = dirname(__DIR__, 2).'/_common';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/version.php';
require_once $commonDir.'/php/getStrings.php';
$version = version(__DIR__);

$lang = $_COOKIE['lang'] ?? httpLanguage() ?? 'en';
$Textes = new Textes('colori/demo', $lang);

require_once './ext/Parsedown.php';
$Parsedown = new Parsedown();

$namedColors = array_keys(Couleur::COULEURS_NOMMEES);
$r = mt_rand(0, count($namedColors) - 1);
$startColor = new Couleur($namedColors[$r]);

// Adapte l'interface (en attendant que JavaScript s'en charge)
$bodyColor = new Couleur("lch(75% $startColor->ciec ".round($startColor->cieh * 360).")");
$bodyColorDark = new Couleur("lch(8% ".(.6 * min(.3 * $startColor->ciec, 10))." ".round($startColor->cieh * 360).")");
?>
<!doctype html>
<html lang="<?=$lang?>"
      data-version="<?=$version?>"
      data-http-lang="<?=httpLanguage()?>"
      data-prog-language="<?=$_COOKIE['progLang'] ?? 'js'?>"
      data-theme="<?=$_COOKIE['theme'] ?? 'auto'?>"
      data-resolved-theme="<?=$_COOKIE['resolvedTheme'] ?? 'light'?>"
      data-start-color="<?=$startColor->name()?>"
      style="--user-hue: <?=round($startColor->h*360)?>;
             --user-color: <?=$startColor->name()?>;
             --user-saturation: <?=round($startColor->s*360)?>;
            ">
  <head>
    <meta charset="utf-8">
    <title>Colori</title>

    <meta name="description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:title" content="Colori">
    <meta property="og:description" content="<?=$Textes->getString('description-site')?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://remiscan.fr/colori/">
    <meta property="og:image" content="https://remiscan.fr/mon-portfolio/projets/colori/og-preview.png">
    <link rel="canonical" href="https://remiscan.fr/colori/">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="theme-color" content="<?=($_COOKIE['resolvedTheme'] == 'dark' ? $bodyColorDark->hsl() : $bodyColor->hsl())?>" data-light="<?=$bodyColor->hsl()?>" data-dark="<?=$bodyColorDark->hsl()?>">
    <meta name="color-scheme" content="light dark">

    <link rel="icon" type="image/png" href="/colori/demo/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/colori/demo/icons/apple-touch-icon.png">
    <link rel="manifest" href="/colori/demo/manifest.json">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <link rel="preload" as="script" href="/colori/colori.js" crossorigin>
    <link rel="preload" as="fetch" href="/colori/demo/strings.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">
    <!-- Préchargement des modules -->
    <link rel="modulepreload" href="/_common/js/traduction.js">
    <?php $mods = preg_filter('/(.+)\.(js\.php)/', '$1', scandir(__DIR__.'/modules'));
    foreach($mods as $mod) { ?>
    <link rel="modulepreload" href="/colori/demo/modules/<?=$mod?>.js.php">
    <?php } ?>

    <link rel="stylesheet" href="/colori/demo/ext/prism.css">
    <link rel="stylesheet" href="/colori/demo/page.css.php">

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

    <style id="theme-variables">
      <?php ob_start();?>
      <?php
      $cieh = $startColor->cieh * 360;
      $colorPreview = Couleur::blend('white', $startColor);
      /* Définition des couleurs du thème clair */
      $ciec = min($startColor->ciec, 60);
      $sectionColor = new Couleur('lch(85% '. (0.6 * $ciec) .' '. $cieh .')');
      $codeColor = new Couleur('lch(90% '. (0.3 * $ciec) .' '. $cieh .')');
      ?>
      :root[data-theme="light"] {
        /* Background colors */
        --body-color: <?=$bodyColor->hsl()?>;
        --section-color: <?=$sectionColor->hsl()?>;
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5)->hsl()?>;
        --code-color: <?=$codeColor->hsl()?>;
        /* Text colors */
        --h1-color: <?= (new Couleur('lch(30% '. (0.6 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --h3-color: <?= (new Couleur('lch(45% '. $ciec .' '. $cieh .')'))->hsl() ?>;
        --text-color: black;
        --link-color: <?= (new Couleur('lch(30% '. $ciec .' '. $cieh .')'))->hsl() ?>;
        --link-underline-color: <?= (new Couleur('lch(30% '. (2 * $ciec) .' '. $cieh .' / .5)'))->hsl() ?>;
        /* Input colors */
        --input-bg-color: <?= (new Couleur('lch(95% '. (0.3 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --input-active-bg-color: <?= (new Couleur('lch(99% '. (0.1 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --input-placeholder-color: <?= (new Couleur('lch(25% '. (0.5 * $ciec) .' '. $cieh .' / .5)'))->hsl() ?>;
        /* Syntax coloring colors */
        --token-number: <?= (new Couleur('lch(50% 70 '. ($cieh - 90) .')'))->hsl() ?>;
        --token-string: <?= (new Couleur('lch(50% 70 '. ($cieh + 45) .')'))->hsl() ?>;
        --token-operator: <?= (new Couleur('lch(50% 70 '. ($cieh - 45) .')'))->hsl() ?>;
        --token-keyword: <?= (new Couleur('lch(50% 70 '. ($cieh + 135) .')'))->hsl() ?>;
      }

      <?php
      /* Définition des couleurs du thème sombre */
      $ciec = min(.3 * $startColor->ciec, 10);
      $sectionColor = new Couleur('lch(20% '. $ciec .' '. $cieh .')');
      $codeColor = $bodyColorDark;
      ?>
      :root[data-theme="dark"] {
        /* Background colors */
        --body-color: <?=$bodyColorDark->hsl()?>;
        --section-color: <?=$sectionColor->hsl()?>;
        --frame-color: <?=$codeColor->improveContrast($colorPreview, 2.5)->hsl()?>;
        --code-color: <?=$codeColor->hsl()?>;
        /* Text colors */
        --h1-color: <?= (new Couleur('lch(80% '. $ciec .' '. $cieh .')'))->hsl() ?>;
        --h3-color: <?= (new Couleur('lch(70% '. (1.7 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --text-color: <?= (new Couleur('lch(90% '. (0.2 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --link-color: <?= (new Couleur('lch(80% '. (1.7 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --link-underline-color: <?= (new Couleur('lch(80% '. (2 * 1.7 * $ciec) .' '. $cieh .' / .5)'))->hsl() ?>;
        /* Input colors */
        --input-bg-color: <?= (new Couleur('lch(30% '. (1.5 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --input-active-bg-color: <?= (new Couleur('lch(10% '. (0.6 * $ciec) .' '. $cieh .')'))->hsl() ?>;
        --input-placeholder-color: <?= (new Couleur('lch(90% '. (0.5 * $ciec) .' '. $cieh .' / .5)'))->hsl() ?>;
        /* Syntax coloring colors */
        --token-number: <?= (new Couleur('lch(80% 70 '. ($cieh - 90) .')'))->hsl() ?>;
        --token-string: <?= (new Couleur('lch(80% 70 '. ($cieh + 45) .')'))->hsl() ?>;
        --token-operator: <?= (new Couleur('lch(80% 70 '. ($cieh - 45) .')'))->hsl() ?>;
        --token-keyword: <?= (new Couleur('lch(80% 70 '. ($cieh + 135) .')'))->hsl() ?>;
      }
      <?php $body = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
      echo buildThemesStylesheet($body); ?>
    </style>

    <noscript>
      <link rel="stylesheet" href="/colori/demo/style-noscript.css">
    </noscript>
  </head>

  <body>

    <svg version="1.1" style="display: none">
      <defs>
        <g id="github-cat">
          <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path>
        </g>
      </defs>
    </svg>

    <header class="intro">
      <h1>colori.<span data-prog-language="js">js</span><span data-prog-language="php">php</span></h1>
      <script>
        const isPhp = localStorage.getItem('colori/lang-php');
        if (isPhp == 'true')
          document.querySelector('header>h1').innerHTML = 'colori.php';
      </script>

      <a href="https://github.com/Remiscan/colori" class="lien-github"
         data-label="github" aria-label="<?=$Textes->getString('github')?>">
        <i class="github-cat"><svg viewBox="0 0 16 16"><use href="#github-cat" /></svg></i>
        <span data-string="github"><?=$Textes->getString('github')?></span>
        <span class="space">&nbsp;</span>
        <span>GitHub</span>
      </a>

      <div class="groupe-langages">
        <button type="button" class="bouton-langage" data-lang="fr" <?=($lang == 'fr' ? 'disabled' : '')?>>Français</button>
        <button type="button" class="bouton-langage" data-lang="en" <?=($lang == 'en' ? 'disabled' : '')?>>English</button>
        <theme-selector position="bottom" icon="reverse"></theme-selector>
      </div>
    </header>

    <section id="intro" class="no-titre">
      <p data-string="documentation-intro-p1"><?=$Textes->getString('documentation-intro-p1')?></p>
    </section>

    <section id="demo">
      <h1 data-string="titre-section-demo"><?=$Textes->getString('titre-section-demo')?></h1>

      <div class="demo-inside">
        <div class="demo-conteneur calced">
          <div id="saisie">
            <label for="entree" class="h2 titre-partie-demo" data-string="demo-input-label"><?=$Textes->getString('demo-input-label')?></label>
            <div class="exemples-saisie exemples-valeurs">
              <span data-string="exemple-abbr"><?=$Textes->getString('exemple-abbr')?></span>
              <button type="button" class="exemple">pink</button>
              <button type="button" class="exemple">#4169E1</button>
              <button type="button" class="exemple">rgb(255, 127, 80)</button>
              <button type="button" class="exemple" data-label="more-examples" aria-label="<?=$Textes->getString('more-examples')?>">+</button>
            </div>
            <p class="instructions-exemples-fonctions inst-hidden off" data-string="instructions-demo"><?=$Textes->getString('instructions-demo')?></p>
            <div class="exemples-saisie exemples-fonctions inst-hidden off">
              <span data-string="exemple-abbr"><?=$Textes->getString('exemple-abbr')?></span>
              <button type="button" class="exemple">pink.invert()</button>
              <button type="button" class="exemple">#4169E1.scale(l, .5)</button>
              <button type="button" class="exemple">black.contrast(white)</button>
              <button type="button" class="exemple">orchid.gradient(palegreen, 5)</button>
              <button type="button" class="exemple">rgb(255, 127, 80).scale(s, .5).blend(red.replace(a, .2))</button>
              <!--<button type="button" class="exemple">aqua.blend(red.replace(a, .5), white.scale(l, .2).replace(a, .8))</button>-->
            </div>
            <input id="entree" class="h4" type="text" data-abbr="<?=$Textes->getString('exemple-abbr')?>"
                   autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                   placeholder="<?=$startColor->name()?>">
          </div>
        </div>

        <h2 class="titre-partie-docu no-separator" data-string="demo-resultats-titre"><?=$Textes->getString('demo-resultats-titre')?></h2>

        <div id="donnees">
          <div class="format couleur" data-string="apercu-couleur"><?=$Textes->getString('apercu-couleur')?></div>

          <div class="format gradient" data-string="apercu-gradient"><?=$Textes->getString('apercu-gradient')?></div>

          <div class="format valeur">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format hex">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->hex()?></code></pre>
          </div>

          <div class="format rgb">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->rgb()?></code></pre>
          </div>

          <div class="format hsl">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->hsl()?></code></pre>
          </div>

          <div class="format hwb">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->hwb()?></code></pre>
          </div>

          <div class="format lab">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->lab()?></code></pre>
          </div>

          <div class="format lch">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->lch()?></code></pre>
          </div>

          <div class="format name oui">
            <pre class="format-donnee language-css"><code class="language-css"><?=$startColor->name()?></code></pre>
          </div>
        </div>
      </div>
    </section>

    <?php
    // Préparons la DOCUMENTATION

    function anchorLink($matches) {
      $title = $matches[2];
      $h = $matches[1];
      $link = preg_replace(['/\<code(?:.+?)?\>/', '/\<\/code\>/'], '', $title);
      $link = strtolower($link);
      $link = str_replace([' ', '.', '\'', '/'], ['-', '', '', ''], $link);
      return '<a id="'. $link .'"></a><h'. $h .'>'. $title .'</h'. $h .'>';
    }

    function prepareDocumentation($docu, $lang, $progLang) {
      $docu = str_replace(['h3', 'h2', 'h1', '<code>', '<pre>'], ['h4', 'h3', 'h2', '<code class="language-javascript">', '<pre class="language-javascript">'], $docu);
      $docu = preg_replace('/\<ul\>/', '<div class="nav-rapide"><ul>', $docu, 1);
      $docu = preg_replace('/\<\/ul\>\n\<p\>/', '</ul></div><p>', $docu, 1);
      $docu = preg_replace_callback('/\<h(2|3)\>(.+)?\<\/h(?:2|3)\>/', 'anchorLink', $docu);
      $docu = preg_replace('/\<a id=\"(.+?)\">/', "<a id=\"$1-$lang-$progLang\">", $docu);
      $docu = preg_replace('/\<a href=\"(.+?)\">/', "<a href=\"$1-$lang-$progLang\">", $docu);
      return $docu;
    }

    function prepareNav($docu) {
      $menu = preg_match('/\<div class=\"nav-rapide\"\>((?:.|\n|\r)+?)\<\/div\>/', $docu, $matches);
      $menu = $matches[1];
      return $menu;
    }

    $docuJsFr = file_get_contents('../wiki/Documentation-pour-JavaScript-(Français).md');
    $docuJsFr = prepareDocumentation($Parsedown->text($docuJsFr), 'fr', 'js');
    $quicknavJsFr = prepareNav($docuJsFr);

    $docuJsEn = file_get_contents('../wiki/Documentation-for-JavaScript-(English).md');
    $docuJsEn = prepareDocumentation($Parsedown->text($docuJsEn), 'en', 'js');
    $quicknavJsEn = prepareNav($docuJsEn);

    $docuPhpFr = file_get_contents('../wiki/Documentation-pour-PHP-(Français).md');
    $docuPhpFr = prepareDocumentation($Parsedown->text($docuPhpFr), 'fr', 'php');
    $quicknavPhpFr = prepareNav($docuPhpFr);

    $docuPhpEn = file_get_contents('../wiki/Documentation-for-PHP-(English).md');
    $docuPhpEn = prepareDocumentation($Parsedown->text($docuPhpEn), 'en', 'php');
    $quicknavPhpEn = prepareNav($docuPhpEn);
    ?>

    <aside class="nav-documentation nav-rapide" data-label="nav-documentation">
      <h1 class="titre-nav-rapide" data-string="nav-documentation"><?=$Textes->getString('nav-documentation')?></h1>
      <div lang="fr" data-prog-language="js"><?=$quicknavJsFr?></div>
      <div lang="en" data-prog-language="js"><?=$quicknavJsEn?></div>
      <div lang="fr" data-prog-language="php"><?=$quicknavPhpFr?></div>
      <div lang="en" data-prog-language="php"><?=$quicknavPhpEn?></div>
    </aside>

    <section class="documentation">
      <a id="documentation" aria-hidden="true"></a>
      <h1 data-string="titre-section-documentation"><?=$Textes->getString('titre-section-documentation')?></h1>

      <div class="prog-lang-changer">
        <button type="button" class="switch-js-php">
          <span id="sw-js" class="sw-span">.js</span>
          <span id="sw-php" class="sw-span">.php</span>
        </button>
      </div>

      <!--<a class="exemple" href="#documentation">▲ Navigation rapide</a>-->

      <!-- DOCUMENTATION JavaScript -->
      <article data-prog-language="js" lang="fr"><?=$docuJsFr?></article>
      <article data-prog-language="js" lang="en"><?=$docuJsEn?></article>
      <!-- DOCUMENTATION PHP -->
      <article data-prog-language="php" lang="fr"><?=$docuPhpFr?></article>
      <article data-prog-language="php" lang="en"><?=$docuPhpEn?></article>
    </section>

    <footer><span><span data-string="syntax-highlighting-source"><?=$Textes->getString('syntax-highlighting-source')?></span> <a href="https://parsedown.org/">Parsedown</a> & <a href="https://prismjs.com/">Prism.js</a></span></footer>

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <!-- SCRIPTS -->
    <script src="/colori/demo/ext/prism.js" data-manual></script>
    <script src="/_common/js/test-support.js" id="test-support-script"></script>
    <script id="test-support-script-exe">
      TestSupport.getSupportResults([
        { name: 'CSS custom properties', priority: 1 },
        { name: 'ES const & let', priority: 1 },
        { name: 'ES class', priority: 1 },
        { name: 'ES template literals', priority: 1 },
        { name: 'ES modules', priority: 1 }
      ]);
    </script>
    <script src="/colori/demo/script.js.php" type="module"></script>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->
  </body>
</html>