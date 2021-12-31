<?php namespace colori {


  const COLOR_SPACES = array(
    array(
      'id' =>'srgb',
      'whitepoint' =>'d65',
      'CSSformat' =>'rgb',
      'gamut' =>[ [0, 1], [0, 1], [0, 1] ],
      'links' =>['lin_srgb', 'hsl']
    ), array(
      'id' => 'lin_srgb',
      'links' => ['srgb', 'd65xyz', 'oklab']
    ), array(
      'id' => 'hsl',
      'whitepoint' => 'd65',
      'CSSformat' => 'hsl',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['srgb', 'hwb']
    ), array(
      'id' => 'hwb',
      'whitepoint' => 'd65',
      'CSSformat' => 'hwb',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['hsl']
    ), array(
      'id' => 'lab',
      'whitepoint' => 'd50',
      'CSSformat' => 'lab',
      'gamut' => [ [0, 4], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz', 'lch']
    ), array(
      'id' => 'lch',
      'whitepoint' => 'd50',
      'CSSformat' => 'lch',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['lab']
    ), array(
      'id' => 'xyz',
      'whitepoint' => 'd50',
      'CSSformat' => 'color',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lab', 'd65xyz', 'lin_prophoto-rgb']
    ), array(
      'id' => 'd65xyz',
      'whitepoint' => 'd65',
      'links' => ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
    ), array(
      'id' => 'display-p3',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_display-p3']
    ), array(
      'id' => 'lin_display-p3',
      'links' => ['display-p3', 'd65xyz']
    ), array(
      'id' => 'a98-rgb',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_a98-rgb']
    ), array(
      'id' => 'lin_a98-rgb',
      'links' => ['a98-rgb', 'd65xyz']
    ), array(
      'id' => 'prophoto-rgb',
      'whitepoint' => 'd50',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_prophoto-rgb']
    ), array(
      'id' => 'lin_prophoto-rgb',
      'links' => ['prophoto-rgb', 'xyz']
    ), array(
      'id' => 'rec2020',
      'whitepoint' => 'd65',
      'CSSformat' => 'color',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_rec2020']
    ), array(
      'id' => 'lin_rec2020',
      'links' => ['rec2020', 'd65xyz']
    ), array(
      'id' => 'oklab',
      'whitepoint' => 'd65',
      'gamut' => [ [0, 4], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lin_srgb', 'oklch']
    ), array(
      'id' => 'oklch',
      'whitepoint' => 'd65',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['oklab']
    )
  );


}