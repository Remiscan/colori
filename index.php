<?php
require_once 'colori.php';

$commonDir = dirname(__DIR__, 1).'/_common';
require_once $commonDir.'/php/httpLanguage.php';
require_once $commonDir.'/php/version.php';
require_once $commonDir.'/php/getStrings.php';
$version = version(__DIR__);
$Textes = new Textes('colori');

$namedColors = array_keys(Couleur::COULEURS_NOMMEES);
$r = mt_rand(0, count($namedColors) - 1);
$startColor = new Couleur($namedColors[$r]);

// Adapte l'interface (en attendant que JavaScript s'en charge)
$bodyColor = new Couleur("lch(75% $startColor->ciec ".round($startColor->cieh * 360).")");
$bodyColorDark = new Couleur("lch(8% ".(.6 * min(.3 * $startColor->ciec, 10))." ".round($startColor->cieh * 360).")");
?>
<!doctype html>
<html lang="fr" data-version="<?=$version?>" data-http-lang="<?=httpLanguage()?>"
      data-theme="<?=$_COOKIE['theme'] ?? 'auto'?>" data-resolved-theme="<?=$_COOKIE['resolvedTheme'] ?? 'light'?>"
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
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0">
    <meta name="theme-color" content="<?=($_COOKIE['resolvedTheme'] == 'dark' ? $bodyColorDark->hsl() : $bodyColor->hsl())?>" data-light="<?=$bodyColor->hsl()?>" data-dark="<?=$bodyColorDark->hsl()?>">
    <meta name="color-scheme" content="light dark">

    <link rel="icon" type="image/png" href="/colori/icons/icon-192.png">
    <link rel="apple-touch-icon" href="/colori/icons/apple-touch-icon.png">
    <link rel="manifest" href="/colori/manifest.json">

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <link rel="preload" as="script" href="/colori/colori.js" crossorigin>
    <link rel="preload" as="fetch" href="/colori/strings.json" crossorigin
          id="strings" data-version="<?=version(__DIR__, 'strings.json')?>">
    <!-- Préchargement des modules -->
    <link rel="modulepreload" href="/_common/js/traduction.js">
    <?php $mods = preg_filter('/(.+)\.(js\.php)/', '$1', scandir(__DIR__.'/modules'));
    foreach($mods as $mod) { ?>
    <link rel="modulepreload" href="/colori/modules/<?=$mod?>.js.php">
    <?php } ?>

    <link rel="stylesheet" href="/colori/ext/prism.css">
    <link rel="stylesheet" href="/colori/page.css.php">

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->

    <style id="theme-variables">
      <?php ob_start();?>
      :root[data-theme="light"] {
        --body-color: <?=$bodyColor->hsl()?>;
      }

      :root[data-theme="dark"] {
        --body-color: <?=$bodyColorDark->hsl()?>;
      }
      <?php $body = ob_get_clean();
      require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/components/theme-selector/build-css.php';
      echo buildThemesStylesheet($body); ?>
    </style>

    <style>
      .loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background: var(--body-color);
        transition: opacity .15s ease;
      }
      .loaded .loading {
        opacity: 0;
        pointer-events: none;
      }
    </style>

    <noscript>
      <link rel="stylesheet" href="/colori/style-noscript.css">
    </noscript>
  </head>

  <body>

    <svg version="1.1" style="display: none">
      <defs>
        <g id="github-logo">
          <path fill-rule="evenodd" d="M18.53 12.03h-.02c.009 0 .015.01.024.011h.006l-.01-.01zm.004.011c-.093.001-.327.05-.574.05-.78 0-1.05-.36-1.05-.83V8.13h1.59c.09 0 .16-.08.16-.19v-1.7c0-.09-.08-.17-.16-.17h-1.59V3.96c0-.08-.05-.13-.14-.13h-2.16c-.09 0-.14.05-.14.13v2.17s-1.09.27-1.16.28c-.08.02-.13.09-.13.17v1.36c0 .11.08.19.17.19h1.11v3.28c0 2.44 1.7 2.69 2.86 2.69.53 0 1.17-.17 1.27-.22.06-.02.09-.09.09-.16v-1.5a.177.177 0 0 0-.146-.18zM42.23 9.84c0-1.81-.73-2.05-1.5-1.97-.6.04-1.08.34-1.08.34v3.52s.49.34 1.22.36c1.03.03 1.36-.34 1.36-2.25zm2.43-.16c0 3.43-1.11 4.41-3.05 4.41-1.64 0-2.52-.83-2.52-.83s-.04.46-.09.52c-.03.06-.08.08-.14.08h-1.48c-.1 0-.19-.08-.19-.17l.02-11.11c0-.09.08-.17.17-.17h2.13c.09 0 .17.08.17.17v3.77s.82-.53 2.02-.53l-.01-.02c1.2 0 2.97.45 2.97 3.88zm-8.72-3.61h-2.1c-.11 0-.17.08-.17.19v5.44s-.55.39-1.3.39-.97-.34-.97-1.09V6.25c0-.09-.08-.17-.17-.17h-2.14c-.09 0-.17.08-.17.17v5.11c0 2.2 1.23 2.75 2.92 2.75 1.39 0 2.52-.77 2.52-.77s.05.39.08.45c.02.05.09.09.16.09h1.34c.11 0 .17-.08.17-.17l.02-7.47c0-.09-.08-.17-.19-.17zm-23.7-.01h-2.13c-.09 0-.17.09-.17.2v7.34c0 .2.13.27.3.27h1.92c.2 0 .25-.09.25-.27V6.23c0-.09-.08-.17-.17-.17zm-1.05-3.38c-.77 0-1.38.61-1.38 1.38 0 .77.61 1.38 1.38 1.38.75 0 1.36-.61 1.36-1.38 0-.77-.61-1.38-1.36-1.38zm16.49-.25h-2.11c-.09 0-.17.08-.17.17v4.09h-3.31V2.6c0-.09-.08-.17-.17-.17h-2.13c-.09 0-.17.08-.17.17v11.11c0 .09.09.17.17.17h2.13c.09 0 .17-.08.17-.17V8.96h3.31l-.02 4.75c0 .09.08.17.17.17h2.13c.09 0 .17-.08.17-.17V2.6c0-.09-.08-.17-.17-.17zM8.81 7.35v5.74c0 .04-.01.11-.06.13 0 0-1.25.89-3.31.89-2.49 0-5.44-.78-5.44-5.92S2.58 1.99 5.1 2c2.18 0 3.06.49 3.2.58.04.05.06.09.06.14L7.94 4.5c0 .09-.09.2-.2.17-.36-.11-.9-.33-2.17-.33-1.47 0-3.05.42-3.05 3.73s1.5 3.7 2.58 3.7c.92 0 1.25-.11 1.25-.11v-2.3H4.88c-.11 0-.19-.08-.19-.17V7.35c0-.09.08-.17.19-.17h3.74c.11 0 .19.08.19.17z"></path>
        </g>

        <g id="github-cat">
          <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path>
        </g>
      </defs>
    </svg>

    <div class="loading" aria-hidden="true"></div>

    <header class="intro">
      <h1>colori.js</h1>
      <script>
        const isPhp = localStorage.getItem('colori/lang-php');
        if (isPhp == 'true')
          document.querySelector('header>h1').innerHTML = 'colori.php';
      </script>

      <a href="https://github.com/Remiscan/colori" target="_blank" rel="noopener" class="lien-github"
         data-label="github" aria-label="<?=$Textes->getString('github')?>">
        <span data-string="github"><?=$Textes->getString('github')?></span>
        <span class="space">&nbsp;</span>
        <i class="github-logo"><svg viewBox="0 0 45 16"><use href="#github-logo" /></svg></i>
        <i class="github-cat"><svg viewBox="0 0 16 16"><use href="#github-cat" /></svg></i>
      </a>

      <div class="groupe-langages">
        <button type="button" class="bouton-langage" data-lang="fr">Français</button>
        <button type="button" class="bouton-langage" data-lang="en" disabled>English</button>
        <theme-selector position="bottom" icon="reverse"></theme-selector>
      </div>
    </header>

    <section id="intro" class="no-titre">
      <p data-string="documentation-intro-p1"><?=$Textes->getString('documentation-intro-p1')?></p>
      <p data-string="documentation-intro-p1-php" class="off"><?=$Textes->getString('documentation-intro-p1-php')?></p>
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
              <button type="button" class="exemple">#4169E1.darken(50%)</button>
              <button type="button" class="exemple">black.contrast(white)</button>
              <button type="button" class="exemple">indigo.gradient(orange)</button>
              <button type="button" class="exemple">rgb(255, 127, 80).desaturate(50%).blend(red, .2)</button>
              <button type="button" class="exemple">aqua.blend(red.blend(white.darken(.8), .8), .5)</button>
            </div>
            <input id="entree" class="h4" type="text" data-abbr="<?=$Textes->getString('exemple-abbr')?>"
                   autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
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
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format rgb">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format hsl">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format hwb">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format lab">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format lch">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>

          <div class="format name">
            <pre class="format-donnee"><code class="language-css"></code></pre>
          </div>
        </div>
      </div>
    </section>

    <aside class="nav-documentation nav-rapide" data-label="nav-documentation"></aside>

    <section class="documentation">
      <a id="documentation" aria-hidden="true"></a>
      <h1 data-string="titre-section-documentation"><?=$Textes->getString('titre-section-documentation')?></h1>

      <div class="prog-lang-changer">
        <button type="button" class="switch-js-php" data-current-tab="js">
          <span id="sw-js" class="sw-span">.js</span>
          <span id="sw-php" class="sw-span">.php</span>
        </button>
      </div>

      <div class="nav-rapide"></div>
      <a class="exemple" href="#documentation">▲ Navigation rapide</a>

      <!-- DOCUMENTATION JavaScript -->
      <article id="documentation-js">
        <?php include 'docs/documentation-js.php'; ?>
      </article>

      <!-- DOCUMENTATION PHP -->
      <article id="documentation-php" class="off">
        <?php include 'docs/documentation-php.php'; ?>
      </article>

      <script>
        if (isPhp == 'true')
        {
          document.getElementById('documentation-js').classList.add('off');
          document.getElementById('documentation-php').classList.remove('off');
        }
      </script>
    </section>

    <footer><span><span data-string="syntax-highlighting-source"><?=$Textes->getString('syntax-highlighting-source')?></span> <a href="https://prismjs.com/" target="_blank" rel="noopener">prism.js</a></span></footer>

    <!-- ▼ Fichiers cache-busted grâce à PHP -->
    <!--<?php ob_start();?>-->

    <!-- SCRIPTS -->
    <script src="/colori/ext/prism.js" data-manual></script>
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
    <script src="/colori/script.js.php" type="module"></script>

    <!--<?php $imports = ob_get_clean();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
    echo versionizeFiles($imports, __DIR__); ?>-->
  </body>
</html>