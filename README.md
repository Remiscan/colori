# colori

**colori** is a module to convert (between CSS-supported formats) and manipulate colors. It is available in JavaScript or in PHP.

My main goal with **colori** is to make it easy to use for everyone. You just download the **colori.js** or **colori.php** file – available on the [Releases page](https://github.com/Remiscan/colori/releases) – and import it in your code as shown in the [documentation](https://github.com/Remiscan/colori/wiki). There are no dependencies, the module is fully contained in the **colori.js** or **colori.php** file.

* * *

**colori**'s capabilities are:

  - **color conversion**: supported CSS formats are RGB (hexadecimal or functional), HSL, HWB, LAB, LCH and the color() function (+ OKLAB and OKLCH, but their syntax may change). Convert any color from any of these formats to any other with ease.
  - **color blending**: overlay multiple colors over each other and compute the resulting visible color with alpha blending. Colors can also be *un*blended.
  - **contrast computing**: calculate the contrast between two colors. Automatically modify a color to improve its contrast with another.
  - **gradients**: create beautiful gradients in any supported color space, avoiding the *desaturated zone* of native CSS gradients.
  - **and more**: clamp a color to a color space, compute the distance between two colors...

[Check out the wiki](https://github.com/Remiscan/colori/wiki) to discover all of **colori**'s capabilities!