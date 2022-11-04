<?php namespace colori {


  const COLOR_SPACES = array(
    array(
      'id' =>'srgb',
      'aliases' => ['rgb', 'rgba'],
      'properties' => ['r', 'g', 'b'],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['srgb-linear', 'hsl'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'srgb-linear',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['srgb', 'xyz-d65'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'hsl',
      'aliases' => ['hsla'],
      'properties' => ['h', 's', 'l'],
      'gamutSpace' => 'srgb',
      'links' => ['srgb', 'hwb']
    ), array(
      'id' => 'hwb',
      'aliases' => [],
      'properties' => ['h', 'w', 'bk'],
      'gamutSpace' => 'srgb',
      'links' => ['hsl']
    ), array(
      'id' => 'lab',
      'aliases' => [],
      'properties' => ['ciel', 'ciea', 'cieb'],
      'gamut' => [ [0.0, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz-d50', 'lch'],
      'black' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'lch',
      'aliases' => [],
      'properties' => ['ciel', 'ciec', 'cieh'],
      'gamut' => [ [0.0, +INF], [0.0, +INF], [-INF, +INF] ],
      'links' => ['lab'],
      'black' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'xyz-d50',
      'aliases' => [],
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['lab', 'xyz-d65', 'prophoto-rgb-linear']
    ), array(
      'id' => 'xyz-d65',
      'aliases' => ['xyz'],
      'gamut' => [ [-INF, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz-d50', 'srgb-linear', 'display-p3-linear', 'a98-rgb-linear', 'rec2020-linear', 'oklab']
    ), array(
      'id' => 'display-p3',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['display-p3-linear'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'display-p3-linear',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['display-p3', 'xyz-d65'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'a98-rgb',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['a98-rgb-linear'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'a98-rgb-linear',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['a98-rgb', 'xyz-d65'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'prophoto-rgb',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['prophoto-rgb-linear'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'prophoto-rgb-linear',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['prophoto-rgb', 'xyz-d50'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'rec2020',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['rec2020-linear'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'rec2020-linear',
      'aliases' => [],
      'gamut' => [ [0.0, 1.0], [0.0, 1.0], [0.0, 1.0] ],
      'links' => ['rec2020', 'xyz-d65'],
      'black' => [ 0.0, 0.0, 0.0 ],
      'white' => [ 1.0, 1.0, 1.0 ]
    ), array(
      'id' => 'oklab',
      'aliases' => [],
      'properties' => ['okl', 'oka', 'okb'],
      'gamut' => [ [0.0, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['xyz-d65', 'oklch', 'okhsl', 'okhsv', 'oklrab'],
      'blakc' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'oklch',
      'aliases' => [],
      'properties' => ['okl', 'okc', 'okh'],
      'gamut' => [ [0.0, +INF], [0.0, +INF], [-INF, +INF] ],
      'links' => ['oklab', 'oklrch'],
      'black' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'oklrab',
      'aliases' => [],
      'properties' => ['oklr', 'oka', 'okb'],
      'gamut' => [ [0.0, +INF], [-INF, +INF], [-INF, +INF] ],
      'links' => ['oklab'],
      'blakc' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'oklrch',
      'aliases' => [],
      'properties' => ['oklr', 'okc', 'okh'],
      'gamut' => [ [0.0, +INF], [0.0, +INF], [-INF, +INF] ],
      'links' => ['oklch'],
      'black' => [ 0.0, 0.0, 0.0 ]
    ), array(
      'id' => 'okhsl',
      'aliases' => [],
      'properties' => ['okh', 'oksl', 'oklr'],
      'gamutSpace' => 'srgb',
      'links' => ['oklab']
    ), array(
      'id' => 'okhsv',
      'aliases' => [],
      'properties' => ['okh', 'oksv', 'okv'],
      'gamutSpace' => 'srgb',
      'links' => ['oklab']
    )
  );


}