<?php namespace colori {


  require_once __DIR__ . '/couleur.php';


  class Palette {
    public array $colors;

    function __construct(Couleur $color, callable $generator, string $clampSpace = 'srgb') {
      $this->colors = [];
      $colors = $generator($color);

      foreach ($colors as $color) {
        $nuances = [];
        foreach ($color->lightnesses as $lightness) {
          $rgb = Couleur::convert('oklch', 'srgb', [$lightness, $color->chroma, $color->hue]);
          if ($clampSpace != null) $rgb = Couleur::toGamut($clampSpace, $rgb);
          $rgbString = $rgb.implode(' ');
          $newColor = new Couleur("color(srgb $rgbString)");
          $nuances[] = $newColor;
        }
        $this->colors[] = $nuances;
      }
    }
  }


}