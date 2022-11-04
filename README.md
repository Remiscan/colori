> This repository is for the **colori** module. There's a [separate one for the colori-demo website](https://github.com/Remiscan/colori-demo).

# colori

**colori** is a module to manipulate colors. It started as a way for me to use color formats that were not supported in CSS yet (like LCH and OKLAB), but grew as I added more features.

I mostly wrote it with my own needs in mind, but if you think there's something for you here, have fun with it!

## Features

- **color conversion**: supported CSS formats are RGB (hexadecimal or functional), HSL, HWB, LAB, LCH, OKLAB, OKLCH and the color() function. Convert any color from any of these formats to any other with ease.
- **color blending**: overlay multiple colors over each other and compute the resulting visible color with alpha blending. Colors can also be *un*blended.
- **contrast computing**: calculate the contrast between two colors. Automatically modify a color to improve its contrast with another.
- **gradients**: create beautiful gradients in any supported color space, avoiding the *desaturated zone* of native CSS gradients.
- **and more**: clamp a color to a color space, compute the distance between two colors...

[Check out the wiki](https://github.com/Remiscan/colori/wiki) to discover all of **colori**'s capabilities!

## How to use

### JavaScript

Import the [colori.min.js](https://github.com/Remiscan/colori/releases/latest/download/colori.min.js) file at the top of your module script, and you're good to go!

```javascript
import Couleur from './colori.min.js';

// Let's find the RGB expression of blue:
const color = new Couleur('blue');
console.log(color.rgb); // logs 'rgb(0, 0, 255)'
```

### PHP

Include the [colori.php](https://github.com/Remiscan/colori/releases/latest/download/colori.php) file in your script, import the ```colori\Couleur``` class, and that's it!

```php
require_once 'colori.php';
use colori\Couleur as Couleur;

// Let's find the RGB expression of blue:
$color = new Couleur('blue');
echo $color->rgb(); // prints 'rgb(0, 0, 255)'
```

## How to build

### JavaScript

To build **colori.js** and **colori.min.js**, install [deno](https://deno.land/#installation) and run this in a terminal:

```shell
deno run --allow-run --allow-read --allow-write=./dist --allow-net=deno.land --allow-env build/js/build.js
```

If the `[auto] bundle colori.js on file change` task is running in VS Code, any change to any file in `src/ts` will automatically trigger a build.

### PHP

To build **colori.php**, install [PHP 8.1+](https://www.php.net/downloads) and run this in a terminal:

```shell
php build/php/build.php
```

If the `[auto] bundle colori.php on file change` task is running in VS Code and [deno](https://deno.land/#installation) is installed, any change to any file in `src/php` will automatically trigger a build.