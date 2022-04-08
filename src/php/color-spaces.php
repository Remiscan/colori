<?php namespace colori {


  const COLOR_SPACES = array(
    array(
      'id' =>'srgb',
      'gamut' =>[ [0, 1], [0, 1], [0, 1] ],
      'links' =>['lin_srgb', 'hsl']
    ), array(
      'id' => 'lin_srgb',
      'gamut' =>[ [0, 1], [0, 1], [0, 1] ],
      'links' => ['srgb', 'd65xyz']
    ), array(
      'id' => 'hsl',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['srgb', 'hwb']
    ), array(
      'id' => 'hwb',
      'gamut' => [ [0, 360], [0, 1], [0, 1] ],
      'links' => ['hsl']
    ), array(
      'id' => 'lab',
      'gamut' => [ [0, 4], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz', 'lch']
    ), array(
      'id' => 'lch',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['lab']
    ), array(
      'id' => 'xyz',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lab', 'd65xyz', 'lin_prophoto-rgb']
    ), array(
      'id' => 'd65xyz',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020', 'oklab']
    ), array(
      'id' => 'display-p3',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_display-p3']
    ), array(
      'id' => 'lin_display-p3',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['display-p3', 'd65xyz']
    ), array(
      'id' => 'a98-rgb',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_a98-rgb']
    ), array(
      'id' => 'lin_a98-rgb',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['a98-rgb', 'd65xyz']
    ), array(
      'id' => 'prophoto-rgb',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_prophoto-rgb']
    ), array(
      'id' => 'lin_prophoto-rgb',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['prophoto-rgb', 'xyz']
    ), array(
      'id' => 'rec2020',
      'gamut' => [ [0, 1], [0, 1], [0, 1] ],
      'links' => ['lin_rec2020']
    ), array(
      'id' => 'lin_rec2020',
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['rec2020', 'd65xyz']
    ), array(
      'id' => 'oklab',
      'gamut' => [ [0, 4], [-INF, +INF], [-INF, +INF] ],
      'links' => ['d65xyz', 'oklch']
    ), array(
      'id' => 'oklch',
      'gamut' => [ [0, 4], [0, +INF], [0, 360] ],
      'links' => ['oklab']
    )
  );


}