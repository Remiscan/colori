<?php namespace colori {


  const COLOR_SPACES = array(
    array(
      'id' =>'srgb',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['srgb-linear', 'hsl']
    ), array(
      'id' => 'srgb-linear',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['srgb', 'xyz-d65']
    ), array(
      'id' => 'hsl',
      'gamut' => [ [0.0, 360.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['srgb', 'hwb']
    ), array(
      'id' => 'hwb',
      'gamut' => [ [0.0, 360.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['hsl']
    ), array(
      'id' => 'lab',
      'gamut' => [ [0.0, 4.0], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz-d50', 'lch']
    ), array(
      'id' => 'lch',
      'gamut' => [ [0.0, 4.0], [0.0, +INF], [0.0, 360.0] ],
      'links' => ['lab']
    ), array(
      'id' => 'xyz-d50',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['lab', 'xyz-d65', 'prophoto-rgb-linear']
    ), array(
      'id' => 'xyz-d65',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['xyz-d50', 'srgb-linear', 'display-p3-linear', 'a98-rgb-linear', 'rec2020-linear', 'oklab']
    ), array(
      'id' => 'display-p3',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['display-p3-linear']
    ), array(
      'id' => 'display-p3-linear',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['display-p3', 'xyz-d65']
    ), array(
      'id' => 'a98-rgb',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['a98-rgb-linear']
    ), array(
      'id' => 'a98-rgb-linear',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['a98-rgb', 'xyz-d65']
    ), array(
      'id' => 'prophoto-rgb',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['prophoto-rgb-linear']
    ), array(
      'id' => 'prophoto-rgb-linear',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['prophoto-rgb', 'xyz-d50']
    ), array(
      'id' => 'rec2020',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['rec2020-linear']
    ), array(
      'id' => 'rec2020-linear',
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['rec2020', 'xyz-d65']
    ), array(
      'id' => 'oklab',
      'gamut' => [ [0.0, 4.0], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz-d65', 'oklch']
    ), array(
      'id' => 'oklch',
      'gamut' => [ [0.0, 4.0], [0.0, +INF], [0.0, 360.0] ],
      'links' => ['oklab']
    )
  );


}