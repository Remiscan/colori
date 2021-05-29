- [How to use colori.js](#how-to-use-colorijs)
- [Create a color](#create-a-color)
  - [Name](#name)
  - [RGB format (hexadecimal)](#rgb-format-hexadecimal)
  - [RGB format (functional)](#rgb-format-functional)
  - [HSL format](#hsl-format)
  - [HWB format](#hwb-format)
  - [LAB format](#lab-format)
  - [LCH format](#lch-format)
- [Color properties](#color-properties)
  - [Precalculated properties](#precalculated-properties)
  - [name](#name-1)
  - [luminance](#luminance)
- [Express a color in different formats](#express-a-color-in-different-formats)
- [Modify a color](#modify-a-color)
  - [change](#change)
  - [replace](#replace)
  - [scale](#scale)
  - [greyscale / grayscale](#greyscale--grayscale)
  - [sepia](#sepia)
  - [complement](#complement)
  - [negative / invert](#negative--invert)
- [Mix colors together](#mix-colors-together)
  - [blend](#blend)
  - [unblend](#unblend)
  - [whatToBlend](#whattoblend)
- [Compare two colors](#compare-two-colors)
  - [contrast](#contrast)
  - [contrastedText](#contrastedtext)
  - [improveContrast](#improvecontrast)
  - [distance](#distance)
  - [same](#same)
- [Other functions](#other-functions)
  - [gradient](#gradient)

# How to use colori.js

You need to download the file [colori.js](https://github.com/Remiscan/colori/releases/latest/download/colori.js), then just import it into your JavaScript code like this:

```javascript
import Colore from 'colori.js';
```

You can replace ```Colore``` by any other name you want to give to the class. In this documentation, we will be using ```Colore```, which means ***color*** in italian. The plural is ***colori***.

# Create a color

To be able to use functions from colori.js, you need to create a ```Colore```-cass object:

```javascript
const rosso = new Colore('red');

// Which results in:

rosso == {
  r: 1,
  g: 0,
  b: 0,
  a: 1,
  h: 0,
  s: 1,
  l: 0.5,
  w: 0,
  bk: 0,
  ciel: 0.54291,
  ciea: 80.815,
  cieb: 69.893,
  ciec: 106.85,
  cieh: 0.11349
}
```

The parameter in ```new Colore(parameter)``` must be a string in a supported format according to [the CSS specification about color formats](https://drafts.csswg.org/css-color/#colorunits).

## Name

A color can be created from its name in [the CSS specification](https://drafts.csswg.org/css-color/#named-colors):

```javascript
const rosso = new Colore('red');
```

## RGB format (hexadecimal)

A color can be created from its expression in hexadecimal format, for example ```#FF0000``` or ```#F00``` for red:

```javascript
const rosso = new Colore('#FF0000');
// or
const rosso = new Colore('#F00');
```

For transparent colors, you can add one or two characters at then end of the expression. For example, to create a red with opacity ```0.6``` (which corresponds to ```9``` or ```99``` in hexadecimal format):

```javascript
const rossoTrasparente = new Colore('#FF000099');
// or
const rossoTrasparente = new Colore('#F009');
```

## RGB format (functional)

A color can be created from its expression in RGB format, for example ```rgb(255, 0, 0)``` for red:

```javascript
const rosso = new Colore('rgb(255, 0, 0)');
// or
const rosso = new Colore('rgb(255 0 0)');

// and with the 4th parameter for opacity:

const rossoTrasparente = new Colore('rgb(255, 0, 0, 0.6)');
// or
const rossoTrasparente = new Colore('rgb(255 0 0 / 0.6)');
```

You can replace  ```rgb``` by ```rgba```, it won't make any difference.

## HSL format

A color can be created from its expression in HSL format, for example ```hsl(0, 100%, 50%)``` for red:

```javascript
const rosso = new Colore('hsl(0, 100%, 50%)');
// or
const rosso = new Colore('hsl(0 100% 50%)');

// and with the 4th parameter for opacity:

const rossoTrasparente = new Colore('hsl(0, 100%, 50%, 0.6)');
// or
const rossoTrasparente = new Colore('hsl(0 100% 50% / 0.6)');
```

You can replace ```hsl``` by ```hsla```, it won't make any difference.

## HWB format

A color can be created from its expression in HWB format, for example ```hwb(0 0% 0%)``` for red:

```javascript
const rosso = new Colore('hwb(0 0% 0%)');

// and with the 4th parameter for opacity:

const rossoTrasparente = new Colore('hwb(0 0% 0% / 0.6)');
```

## LAB format

A color can be created from its expression in LAB format, for example ```lab(54% 81 70)``` for red:

```javascript
const rosso = new Colore('lab(54% 81 70)');

// and with the 4th parameter for opacity:

const rossoTrasparente = new Colore('lab(54% 81 70 / 0.6)');
```

The LAB color space contains colors which are not in the sRGB color space, used for RGB, HSL and HWB formats. Since CSS doesn't natively support LAB colors yet, using such a color in ```new Colore()``` will convert it into the closest color in sRGB space.

For that reason, you could notice some inconsistencies, for example:

```javascript
const a = new Colore('lab(50% 118 43)');
a.rgb == 'rgb(232, 0, 78)'
// but:
const b = new Colore('rgb(232, 0, 78)')
b.lch == 'lab(50% 76 28)'
```

## LCH format

A color can be created from its expression in LCH format, for example ```lch(54% 107 41)``` for red:

```javascript
const rosso = new Colore('lch(54% 107 41)');

// and with the 4th parameter for opacity:

const rossoTrasparente = new Colore('lch(54% 107 41 / 0.6)');
```

The LCH color space contains colors which are not in the sRGB color space, used for RGB, HSL and HWB formats. Since CSS doesn't natively support LCH colors yet, using such a color in ```new Colore()``` will convert it into the closest color in sRGB space.

For that reason, you could notice some inconsistencies, for example:

```javascript
const a = new Colore('lch(50% 125 20)');
a.rgb == 'rgb(232, 0, 78)'
// but:
const b = new Colore('rgb(232, 0, 78)')
b.lch == 'lch(50% 81 20)'
```

# Color properties

## Precalculated properties

When a ```Colore```-class object is created by using ```new Colore(expression)```, 14 different color properties are calculated. They correspond to every parameter from the different supported color formats:

- The ```r```, ```g``` and ```b``` properties are the red, green and blue values of the color when it's expressed in ```rgb(r, g, b)``` format. These values are usually given between 0 and 255 or as percentages.

- The ```h``` property is the hue value of the color when it's expressed in ```hsl(h, s, l)``` or ```hwb(h, w, bk)``` format. This value is an angle, usually given in degrees (from 0 to 360), gradians (from 0 to 400), radians (from 0 to 2π) or turns (from 0 to 1).

- The ```s``` and ```l``` properties are the saturation and luminosity values of the color when it's expressed in ```hsl(h, s, l)``` format. These values are percentages.

- The ```w``` and ```bk``` properties are the whiteness and blackness values of the color when it's given in ```hwb(h w bk)``` format. These values are percentages.

- The ```ciel``` property is the luminosity of the color in CIELAB color space, when it's expressed in ```lab(ciel ciea cieb)``` or ```lch(ciel ciec cieh)``` format. This value is a percentage. The specification allows values over 100% for compatibility with the HDR standard.

- The ```ciea``` and ```cieb``` properties are the values of the color on the "a" and "b" axes of CIELAB color space, when it's expressed in ```lab(ciel ciea cieb)``` format. They can be any numerical value, but they are generally in the [-160, 160] interval.

- The ```ciec``` property is the chroma value of the color when it's expressed in ```lch(ciel ciec cieh)``` format. It can be any numerical value, but it's generally in the [0, 230] interval.

- The ```cieh``` property is the hue value of the color when it's expressed in ```lch(ciel ciec cieh)``` format. This value is an angle, usually given in degrees (from 0 to 360), gradians (from 0 to 400), radians (from 0 to 2π) or turns (from 0 to 1). It is similar to the ```h``` property in HSL format, but its value is calculated in a slightly different way.

- The ```a``` property is the opacity of the color. It's the 4th optional parameter in every previous color format. This value is usually given in the [0, 1] interval or as a percentage.

## name

Some colors [have a name in the CSS specification](https://drafts.csswg.org/css-color/#named-colors). For these colors, the ```name``` parameter gives their name:

```javascript
(new Colore('red')).name == 'red'
```

Note: if a color isn't exactly equal to a named color (for example because of a rounded value in a calculation), the ```name``` property will still recognize it as the named color it's close to:

```javascript
(new Colore('red')).rgb == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)')).name == 'red'
```

The ```exactName``` property only gives the name when the color is exactly equal to it:

```javascript
(new Colore('red')).rgb == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)')).exactName == null
```

## luminance

The ```luminance``` property gives the luminance of the color, [as defined by W3C](https://www.w3.org/TR/WCAG20-TECHS/G18.html#G18-procedure).

```javascript
rosso.luminance == 0.2126
```

# Express a color in different formats

The ```hex```, ```rgb```, ```hsl```, ```hwb```, ```lab``` and ```lch``` properties give the expression of the color in the corresponding format:

```javascript
const rosso = new Colore('red');
rosso.hex == '#ff0000'
rosso.rgb == 'rgb(255, 0, 0)'
rosso.hsl == 'hsl(0, 100%, 50%)'
rosso.hwb == 'hwb(0 0% 0%)'
rosso.lab == 'lab(54% 81 70)'
rosso.lch == 'lch(54% 107 41)'
```

Transparent colors (```a < 1```) are supported:

```javascript
const rossoTrasparente = new Colore('rgb(255, 0, 0, 0.6)');
rossoTrasparente.hex == '#ff000099'
rossoTrasparente.rgb == 'rgb(255, 0, 0, 0.6)'
rossoTrasparente.hsl == 'hsl(0, 100%, 50%, 0.6)'
rossoTrasparente.hwb == 'hwb(0 0% 0% / 0.6)'
rossoTrasparente.lab == 'lab(54% 81 70 / 0.6)'
rossoTrasparente.lch == 'lch(54% 107 41 / 0.6)'
```

These properties hide opacity when it's equal to 1. The ```hexa```, ```rgba```, ```hsla```, ```hwba```, ```laba``` and ```lcha``` properties will display opacity in any case:

```javascript
const rosso = new Colore('red');
rosso.hexa == '#ff0000ff'
rosso.rgba == 'rgb(255, 0, 0, 1)'
rosso.hsla == 'hsl(0, 100%, 50%, 1)'
rosso.hwba == 'hwb(0 0% 0% / 1)'
rosso.laba == 'lab(54% 81 70 / 1)'
rosso.lcha == 'lch(54% 107 41 / 1)'
```

# Modify a color

## change

The ```change``` method lets you modify any property of a color, and returns a new ```Colore```-class object of which all properties have been recalculated following the change.

### How to use:

```javascript
const result = color.change(prop, val, options = { replace, scale });
```

It applies to a ```Colore```-class object – ```color``` here.

It takes these arguments:

- ```prop```: a string which contains the name of the property you want to change, for example ```'r'```, ```'g'```, ```'b'```, etc.

- ```val```: a number or percentage used as the value which will **be added** to the previous value of the property.

- ```options```: an object containing the following properties:

  - ```replace``` (default = ```false```): a boolean. If ```true```, the value of ```val``` will **replace** the previous value of the property, instead of being added to it.

  - ```scale``` (default = ```false```): a boolean. If ```true```, the value of ```val``` will **be multiplied** to the previous value of the property, instead of being added to it.

It returns a ```Colore```-class object – ```result``` here – which is a copy of ```color``` with a modified ```prop``` property.

### Examples:

```javascript
// Let's reduce the luminosity of red by 10%:
const rosso = new Colore('red');
const nuovoColore = rosso.change('l', '-10%');
// which gives:
rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 40%)'
```
```javascript
// Let's replace the luminosity of red by 10%:
const rosso = new Colore('red');
const nuovoColore = rosso.change('l', '10%', { replace: true });
// which gives:
rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 10%)';
```
```javascript
// Let's raise the luminosity of red by 50% of its current value:
const rosso = new Colore('red');
const nuovoColore = rosso.change('l', '150%', { scale: true });
// which gives:
rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 75%)';
```

## replace

The ```replace``` method is equivalent to ```change``` with option ```{ replace: true }```.

### How to use:

```javascript
const result = color.replace(prop, val);
```

It applies to a ```Colore```-class object – ```color``` here.

It takes these arguments:

- ```prop```: a string containing the name of the property you want to change, for example ```'r'```, ```'g'```, ```'b'```, etc.

- ```val```: a number or percentage used as the value that will **replace** the previous value of the property.

It returns a ```Colore```-class object – ```result``` here – which is a copy of ```color``` with a modified ```prop``` property.

### Example:

```javascript
// Let's replace the luminosity of red by 10%:
const rosso = new Colore('red');
const nuovoColore = rosso.replace('l', '10%');
// which gives:
nuovoColore.hsl == 'hsl(0, 100%, 10%)';
```

## scale

The ```scale``` method is equivalent to ```change``` with option ```{ scale: true }```.

### How to use:

```javascript
const result = color.scale(prop, val);
```

It applies to a ```Colore```-class object – ```color``` here.

It takes these arguments:

- ```prop```: a string containing the name of the property you want to change, for example ```'r'```, ```'g'```, ```'b'```, etc.

- ```val```: a number or percentage used as the value that will **be multiplied** to the previous value of the property.

It returns a ```Colore```-class object – ```result``` here – which is a copy of ```color``` with a modified ```prop``` property.

### Example:

```javascript
// Let's raise the luminosity of red by 50% of its current value:
const rosso = new Colore('red');
const nuovoColore = rosso.scale('l', '150%');
// which gives:
rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 75%)'
```

## greyscale / grayscale

The ```greyscale``` (or ```grayscale```) method transforms a color into the shade of grey with the same luminosity.

### How to use:

```javascript
const result = color.greyscale();
```

It applies to a ```Colore```-class object – ```color``` here.

It takes no argument.

It returns a ```Colore```-class object – ```result``` here – which is a copy of ```color``` with its saturation reduced to 0.

### Example:

```javascript
// Let's take some blue
const blu = new Colore('blue');
blu.hsl == 'hsl(240, 100%, 50%)'

// Let's apply greyscale to that blue
const grigio = blu.greyscale();
grigio.hsl == 'hsl(240, 0%, 50%)'
```

## sepia

The ```sepia``` method transforms a color into its sepia tone.

### How to use:

```javascript
const result = color.sepia();
```

It applies to a ```Colore```-class object – ```color``` here.

It takes no argument.

It returns a ```Colore```-class object – ```result``` here – which is a modification of ```color``` towards sepia tones.

### Example:

```javascript
// Let's take some blue
const blu = new Colore('blue');
blu.rgb == 'rgb(0, 0, 255)'

// Let's apply sepia to that blue
const seppia = blu.sepia();
seppia.rgb == 'rgb(48, 43, 33)'
```

## complement

The ```complement``` method calculates the complementary.

### How to use:

```javascript
const result = color.complement();
```

It applies to a ```Colore```-class object – ```color``` here.

It takes no argument.

It returns a ```Colore```-class object – ```result``` here – which is the complementary color to ```color```.

### Examples:

```javascript
// The complementary color to red is cyan / aqua
const rosso = new Colore('red');
rosso.complement().name == 'aqua'
```
```javascript
// The complementary color to white is white itself
const bianco = new Couleur('white');
bianco.complement().name == 'white'
```

## negative / invert

The ```negative``` (or ```ìnvert```) calculates the inverse color.

### How to use:

```javascript
const result = color.negative();
```

It applies to a ```Colore```-class object – ```color``` here.

It takes no argument.

It returns a ```Colore```-class object – ```result``` here – qui est la couleur négative de ```color```.

### Examples:

```javascript
// La couleur négative du blanc est le noir
const bianco = new Colore('white');
bianco.negative().name == 'black'
```
```javascript
// La couleur négative du rouge est le cyan
const rosso = new Colore('red');
rosso.negative().name == 'aqua'
```

# Mix colors together

## blend

The static method ```blend``` can blend multiple colors. In other words, it calculates the color you would see on the screen if you displayed multiple colors over each other.

### How to use:

```javascript
const result = Colore.blend(color1, color2, color3, ...);
```

It takes these arguments:

- an arbitrary number of ```Colore```-class objects or strings in a format compatible with ```new Colore()```. Each color will be overlayed over the previous one.

It returns a ```Colore```-class object which is the color you see after overlaying every color it took as arguments one by one.

> ```blend``` can also be used as a non-static method applied to the first color:
>
>```javascript
>const result = color1.blend(color2, color3, ...)
>```

>Note: if you overlay an opaque color (such that ```color.a == 1```) over another color, then the result will be that same opaque color ; since it's not transparent, it doesn't let you see the color below at all. ```blend``` is thus mainly useful to blend transparent colors (such that ```color.a < 1```).

### Examples:

```javascript
// Let's take some red and blend some blue with opacity 0.5 over it
const bluTrasparente = (new Colore('blue')).replace('a', .5);
const result = Colore.blend('red', bluTrasparente);

// The result is purple, as we would expect
result.name == 'purple' 
```
```javascript
// Let's blend some red and blue again, but let's also add some green with opacity 0.4
const verdeTrasparente = (new Colore('green')).replace('a', .4);
const result = Colore.blend('red', bluTrasparente, verdeTrasparente);

// The result is a desaturated purple, almost grey
result.rgb == 'rgb(77, 51, 77)' 
```

## unblend

The ```blend``` method solved the following equality to calculate ```result```:

```javascript
const result = Colore.blend(background, overlay);
```

The static method ```unblend```, on the other hand, solves the same equation when it's ```background``` you want to calculate. In other words, it takes a color ```result``` and unblends ```overlay``` from it.

```javascript
const background = Colore.unblend(result, overlay);
```

### How to use:

```javascript
const result = Colore.unblend(color1, color2, color3, ...);
```

It takes these arguments:

- an arbitrary number of ```Colore```-class objects or strings in a format compatible with ```new Colore()```. Each color will be overlayed over the previous one.

It returns a ```Colore```-class object which is the color you obtain after unmixing every color it took as arguments one by one.

>```unblend``` can also be used as a non-static method applied to the first color:
>
>```javascript
>const result = color1.unblend(color2, color3, ...);
>```

### Example:

```javascript
// With blend, we saw that blending red and transparent blue made purple. So we would expect that unblending that same transparent blue from purple would make red again.
const bluTrasparente = (new Colore('blue')).replace('a', .5);

// It works!
Colore.unblend('purple', bluTrasparente).name == 'red' 
```

## whatToBlend

The ```blend``` method solved the following equality to calculate ```result```:

```javascript
result = Colore.blend(background, overlay)
```

The static method ```whatToBlend```, on the other hand, solves the same equation when it's ```overlay``` you want to calculate. In other words, it takes two colors ```background``` and ```result``` and calculates which color ```overlay``` should be blended with ```background``` to obtain the color ```result```.

```javascript
overlay = Colore.whatToBlend(background, result)
```

### How to use:

```javascript
const overlay = Colore.whatToBlend(background, result, alpha, alphaStep);
```

It takes these arguments:

- ```background``` and ```result```: two ```Colore```-class objects or strings in a format compatible with ```new Colore()```.

- ```alpha```: a number, the (optional) opacity value you want ```overlay``` to have. Indeed, multiple colors can be solutions to that equation. If that is the case, choosing an ```alpha``` value lets you find a unique solution.

- ```alphaStep``` (default = ```0.1```): a number, the (optional) value of the opacity difference between the different solutions returned by ```whatToBlend```, when multiple solutions exist.

>```whatToBlend``` can also be used as a non-static method applied to ```background```:
>
>```javascript
>const overlay = background.whatToBlend(result, alpha, alphaStep);
>```

### Examples:

```javascript
// whatToBlend returns the list of colors that can be blended with red to make purple:
const result = Colore.whatToBlend('red', 'purple');
result.map(c => c.rgb) == [
  'rgb(0, 0, 255, 0.5'),
  'rgb(43, 0, 213, 0.6'),
  'rgb(74, 0, 183, 0.7)',
  'rgb(96, 0, 160, 0.8)',
  'rgb(114, 0, 142, 0.9)',
  'purple'
];
```
```javascript
// If we ask for a solution with opacity 0.5, it returns blue with opacity 0.5. That's the actual value bluTrasparente had in the blend and unblend examples!
const result = Colore.whatToBlend('red', 'purple', 0.5);
result.rgb == 'rgb(0, 0, 255, 0.5)'
```

# Compare two colors

## contrast

The static method ```contrast``` calculates the contrast between two colors.

### How to use:

```javascript
const result = Colore.contrast(color1, color2);
```

It takes these arguments:

- ```color1``` and ```color2```: two ```Colore```-class objects or strings in a format compatible with ```new Colore()```.

It returns a number between 1 and 21.

>```contrast``` can also be used as a non-static method applied to a ```Colore```-class object:
>
>```javascript
>const result = color1.contrast(color2);
>```

### Examples:

```javascript
Colore.contrast('white', 'black') == 21
Colore.contrast('skyblue', 'darkblue') == 8.7835
```

For a text to be easily readable on a colored surface, the contrast between the colors of that surface and the text must be high enough. The ```contrast``` method can for example be used to check if two colors meet the [WCAG recommandations](https://www.w3.org/TR/WCAG20/#visual-audio-contrast-contrast).

## contrastedText

The ```contrastedText``` method determines if black or white text would be more readable on a colored surface.

### How to use:

```javascript
const result = color.contrastedText();
```

It applies to a ```Colore```-type object –```color``` here.

It takes no argument.

It returns the string ```'white'``` or ```'black'```.

### Examples:

```javascript
// On a sky blue surface, black text is more readable than white text
const bluCielo = new Colore('skyblue');
bluCielo.contrastedText() == 'black'
```
```javascript
// On a dark blue surface, white text is more readable than black text
const bluScuro = new Colore('darkblue');
bluScuro.contrastedText() == 'white'
```

## improveContrast

The ```improveContrast``` method changes the color it's applied to, more specifically its CIE luminosity ```ciel```, to give it better contrast with another color.

### How to use:

```javascript
const result = color.improveContrast(referenceColor, desiredContrast, step, options = { lower, towards, maxIterations });
```

It applies to a ```Colore```-class object – ```color``` here.

It takes these arguments:

- ```referenceColor```: a ```Colore```-class object or a string in a format compatible with ```new Colore()```, used as a reference color: the method is trying to improve the value of ```Colore.contrast(color, referenceColor)```.

- ```desiredContrast```: a number used as the value you want to contrast to be.

- ```step``` (default = ```2```): a number used as the quantity that will be added or removed from the CIE luminosity of ```color``` ; in other words, at each step of the contrast optimization, ```color.ciel``` grows or decreases by ```step```%.

- ```options```: an object containing the following properties:

  - ```lower``` (default = ```false```): can take the following values:
    - ```false```: ```improveContrast``` will not do anything if ```Colore.contrast(color, referenceColor) > desiredContrast```.
    - ```true```: when ```Colore.contrast(color, referenceColor) > desiredContrast```, ```color``` will be modified to reduce contrast so that it gets as close as possible to ```desiredContrast```.

  - ```towards``` (default = ```null```): determines if ```improveContrast``` should improve the contrast by making the color lighter or darker. It can take the following values:
    - ```'black'```: ```improveContrast``` will only try to improve the contrast by making ```color``` darker.
    - ```'white'```: ```improveContrast```  will only try to improve the contrast by making ```color``` lighter.
    - ```null```: ```improveContrast``` will try to guess if making ```color``` darker or lighter would lead to a better contrast. If it can't guess, it will default to the value ```'black'```.

  - ```maxIterations``` (default = ```100```): the maximum number of times ```improveContrast``` will change the color to improve contrast.

It returns a ```Colore```-class object – ```result``` here – which is a copy of ```color``` to which the needed modifications of its ```ciel``` property have been applied so that ```Colore.contrast(result, referenceColor) > desiredContrast```.

### Example:

```javascript
// Let's take the example of a light blue background, over which we want to display some sky blue text.
const bluChiaro = new Couleur('lightblue');
const bluCielo = new Couleur('skyblue');
Colore.contrast(bluChiaro, bluCielo) == 1.1396

// The contrast between sky blue and light blue is too low for the text to be readable. WCAG recommands a contrast of at least 4.5. Let's change the sky blue color to reach that value.
const nuovoBlue = bluCielo.improveContrast(bluChiaro, 4.5);

// The new blue is darker than the sky blue we started with.
bluCielo.hsl == 'hsl(197, 71%, 73%)'
nuovoBlu.hsl == 'hsl(193, 100%, 24%)'

// The desired contrast has been reached!
Colore.contrast(bluChiaro, nuovoBlue) == 4.6554
```

## distance

The static method ```distance``` measures how much difference there is between two colors.

### How to use:

```javascript
const result = Colore.distance(color1, color2, format, tolerance);
```

It takes these arguments:

- ```color1``` and ```color2```: two ```Colore```-class objects or strings in a format compatible with ```new Colore()```.

- ```format```: a string, determines which color format will be used to calculate their distance. It can take the following values:
  - ```'rgb'```, ```'hsl'```, ```'hwb'```, ```'lab'``` or ```'lch'```: the distance between the two colors will be calculated by adding the difference in value between each property of the chosen format.
  - ```null``` (default): the calculated distance will be the average of the distance for each color format.

- ```tolerance``` (default = ```0.02```): a number, used as a tolerance value to ignore certain color properties when they have no effect on the color. For example, in HSL format, when L = 0, the color is black no matter the value of H or S. To account for that even when some rounding errors made L slightly superior to 0, the ```distance``` method ignores H and S when ```color1.l < tolerance && color2.l < tolerance```.

It returns a positive number.

>```distance``` can also be used as a non-static method applied to a ```Colore```-class object:
>
>```javascript
>const result = color1.distance(color2, format, tolerance);
>```

### Examples:

```javascript
// Red and red are identical colors
Colore.distance('red', 'red') == 0
```
```javascript
// Red and blue are different colors
Colore.distance('red', 'blue') == 1.3467999999999998
```
```javascript
// Both colors are black despite different H and S values
Colore.distance('hsl(200, 50%, 0%)', 'hsl(50, 35%, 0%)') == 0
```

## same

The static method ```same``` determines if two colors are the same.

### How to use:

```javascript
const result = Colore.same(color1, color2, tolerance);
```

It takes these arguments:

- ```color1``` and ```color2```: two ```Colore```-class objects or strings in a format compatible with ```new Colore()```.

- ```tolerance``` (default = ```0.02```): a number, represents the minimal distance between two colors for them to be considered different.

It returns ```true``` if the colors are considered identical, ```false``` if they are not.

>```same``` can also be used as a non-static method applied to a ```Colore```-class object:
>
>```javascript
>const result = color1.same(color2, tolerance);
>```

### Examples:

```javascript
// Red and red are identical colors
Colore.same('red', 'red') == true
```
```javascript
// Red and blue are different colors
Colore.same('red', 'blue') == false 
```
```javascript
// Both colors are black despite different H and S values
Colore.same('hsl(200, 50%, 0%)', 'hsl(50, 35%, 0%)') == true
```
```javascript
// These two colors are so close that they're considered identical
Colore.same('rgb(0, 0, 255)', 'rgb(0, 0, 254)') == true 
```

# Other functions

## gradient

The static method ```gradient``` generates a gradient between two colors that avoids the grey zone (see [this article](https://css-tricks.com/the-gray-dead-zone-of-gradients/)).

### How to use:

```javascript
const result = Colore.gradient(from, to, steps);
```

It takes these arguments:

- ```from``` and ```to```: two ```Colore```-class objects or strings in a format compatible with ```new Colore()```.

- ```steps``` (default = ```5```): the number of steps – meaning the number of calculated colors – to go from ```from``` to ```to```. The higher this number, the smoother the gradient and the more it avoids the grey zone.

It returns an ```Array``` of length ```steps + 1``` containing ```Colore```-class objects: ```[from, color2, color3, ..., to]```.

>```gradient``` can also be used as a non-static method applied to a ```Colore```-class object:
>
>```javascript
>const result = from.gradient(to, steps);
>```

### Example:

```javascript
const colori = Colore.gradient('indigo', 'orange');

// To use this gradient in CSS, let's use the calculated colors in the linear-gradient CSS function:
const gradiente = `linear-gradient(to right, ${colori.map(c => c.name || c.rgb).join(', ')})`;

// By using this as a background-image in CSS, the gradient would be displayed
gradiente == 'linear-gradient(to right, indigo, rgb(137, 0, 116), rgb(192, 0, 105), rgb(238, 42, 80), rgb(255, 109, 52), orange)' 
```