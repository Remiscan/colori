<!--JS-->
# Utiliser colori.js

Il suffit de télécharger le fichier [colori.js](https://github.com/Remiscan/colori/releases/latest/download/colori.js), puis de l'importer dans votre code JavaScript comme suit :

```javascript
import Colore from 'colori.js';
```

Vous pouvez remplacer ```Colore``` par un autre nom pour nommer la classe différemment.
<!------>

# Créer une couleur

Pour avoir accès aux fonctions de colori.js, vous devez créer un objet de classe ```Colore``` :

```javascript
const rosso = new Colore('red');

// Ce qui aura pour résultat :

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

Le paramètre à fournir en entrée de ```new Colore(parametre)``` doit être une chaîne de caractères dans un format valide selon [la spécification CSS des formats de couleurs](https://drafts.csswg.org/css-color/#colorunits).

## Nom

Une couleur peut être créée à partir de son nom dans [la spécification CSS](https://drafts.csswg.org/css-color/#named-colors) :

```javascript
const rosso = new Colore('red');
```

## Format RGB (hexadécimal)

Une couleur peut être créée à partir de son expression au format hexadécimal, par exemple ```#FF0000``` ou ```#F00``` pour le rouge :

```javascript
const rosso = new Colore('#FF0000');
// ou
const rosso = new Colore('#F00');
```

Pour les couleurs transparentes, vous pouvez ajouter un ou deux caractères à la fin de l'expression de la couleur. Par exemple, pour un rouge d'opacité ```0.6``` (ce qui correspond à ```9``` ou ```99``` en hexadécimal) :

```javascript
const rossoTrasparente = new Colore('#FF000099');
// ou
const rossoTrasparente = new Colore('#F009');
```

## Format RGB (fonctionnel)

Une couleur peut être créée à partir de son expression au format RGB, par exemple ```rgb(255, 0, 0)``` pour le rouge :

```javascript
const rosso = new Colore('rgb(255, 0, 0)');
// ou
const rosso = new Colore('rgb(255 0 0)');

// et avec le 4è paramètre de transparence :

const rossoTrasparente = new Colore('rgb(255, 0, 0, 0.6)');
// ou
const rossoTrasparente = new Colore('rgb(255 0 0 / 0.6)');
```

Vous pouvez remplacer ```rgb``` par ```rgba```, cela aura le même effet.

## Format HSL

Une couleur peut être créée à partir de son expression au format HSL, par exemple ```hsl(0, 100%, 50%)``` pour le rouge :

```javascript
const rosso = new Colore('hsl(0, 100%, 50%)');
// ou
const rosso = new Colore('hsl(0 100% 50%)');

// et avec le 4è paramètre de transparence :

const rossoTrasparente = new Colore('hsl(0, 100%, 50%, 0.6)');
// ou
const rossoTrasparente = new Colore('hsl(0 100% 50% / 0.6)');
```

Vous pouvez remplacer ```hsl``` par ```hsla```, cela aura le même effet.

## Format HWB

Une couleur peut être créée à partir de son expression au format HWB, par exemple ```hwb(0 0% 0%)``` pour le rouge :

```javascript
const rosso = new Colore('hwb(0 0% 0%)');

// et avec le 4è paramètre de transparence :

const rossoTrasparente = new Colore('hwb(0 0% 0% / 0.6)');
```

## Format LAB

Une couleur peut être créée à partir de son expression au format LAB, par exemple ```lab(54% 81 70)``` pour le rouge :

```javascript
const rosso = new Colore('lab(54% 81 70)');

// et avec le 4è paramètre de transparence :

const rossoTrasparente = new Colore('lab(54% 81 70 / 0.6)');
```

L'espace de couleurs LAB contient des couleurs qui ne sont pas dans l'espace de couleurs sRGB, utilisé pour les formats RGB, HSL et HWB. Puisque CSS ne supporte pas encore nativement les couleurs au format LAB, passer une telle couleur à ```new Colore()``` la convertira en la couleur la plus proche dans l'espace sRGB.

Pour cette raison, vous pourriez remarquer certaines contradictions, par exemple :

```javascript
const a = new Colore('lab(50% 118 43)');
a.rgb == 'rgb(232, 0, 78)'
// mais :
const b = new Colore('rgb(232, 0, 78)')
b.lch == 'lab(50% 76 28)'
```

## Format LCH

Une couleur peut être créée à partir de son expression au format LCH, par exemple ```lch(54% 107 41)``` pour le rouge :

```javascript
const rosso = new Colore('lch(54% 107 41)');

// et avec le 4è paramètre de transparence :

const rossoTrasparente = new Colore('lch(54% 107 41 / 0.6)');
```

L'espace de couleurs LCH contient des couleurs qui ne sont pas dans l'espace de couleurs sRGB, utilisé pour les formats RGB, HSL et HWB. Puisque CSS ne supporte pas encore nativement les couleurs au format LCH, passer une telle couleur à ```new Colore()``` la convertira en la couleur la plus proche dans l'espace sRGB.

Pour cette raison, vous pourriez remarquer certaines contradictions, par exemple :

```javascript
const a = new Colore('lch(50% 125 20)');
a.rgb == 'rgb(232, 0, 78)'
// mais :
const b = new Colore('rgb(232, 0, 78)')
b.lch == 'lch(50% 81 20)'
```

# Propriétés d'une couleur
## Propriétés précalculées

Lorsque un objet de classe ```Colore``` est créé en utilisant ```new Colore(expression)```, 14 propriétés différentes de la couleur sont calculées. Elles correspondent à tous les paramètres des différents formats de couleurs :

- Les propriétés ```r```, ```g``` et ```b``` sont les valeurs de rouge, bleu et vert de la couleur lorsqu'elle est exprimée au format ```rgb(r, g, b)```. Ces valeurs sont habituellement données entre 0 et 255 ou en pourcentages.

- La propriété ```h``` est la valeur de teinte de la couleur lorsqu'elle est exprimée au format ```hsl(h, s, l)``` ou ```hwb(h, w, bk)```. Cette valeur est un angle, habituellement donné en degrés (de 0 à 360), grades (de 0 à 400), radians (de 0 à 2π) ou tours (de 0 à 1).

- Les propriétés ```s``` et ```l``` sont respectivement les valeurs de saturation et de luminosité de la couleur lorsqu'elle est exprimée au format ```hsl(h, s, l)```. Ces valeurs sont données en pourcentages.

- Les propriétés ```w``` et ```bk``` sont les valeurs de blancheur et noirceur de la couleur lorsqu'elle est exprimée au format ```hwb(h w bk)```. Ces valeurs sont données en pourcentages.

- La propriété ```ciel``` est la luminosité de la couleur dans l'espace de couleurs CIELAB, lorsqu'elle est exprimée aux formats ```lab(ciel ciea cieb)``` ou ```lch(ciel ciec cieh)```. Cette valeur est donnée en pourcentage. La spécification autorise des valeurs supérieures à 100% pour une compatibilité avec le standard HDR.

- Les propriétés ```ciea``` et ```cieb``` sont respectivement les valeurs de la couleur sur les axes "a" et "b" de l'espace de couleurs CIELAB, lorsqu'elle est exprimée au format ```lab(ciel ciea cieb)```. Elles peuvent prendre n'importe quelle valeur numérique, mais sont en général contenues dans l'intervalle [-160, 160].

- La propriété ```ciec``` est la valeur de chroma de la couleur losqu'elle est exprimée au format ```lch(ciel ciec cieh)```. Elle peut prendre n'importe quelle valeur numérique positive, mais est en général contenue dans l'intervalle [0, 230].

- La propriété ```cieh``` est la valeur de teinte de la couleur lorsqu'elle est exprimée au format ```lch(ciel ciec cieh)```. Cette valeur est un angle, habituellement donné en degrés (de 0 à 360), grades (de 0 à 400), radians (de 0 à 2π) ou tours (de 0 à 1). Elle est similaire à la propriété ```h``` du format HSL, mais sa valeur est définie d'une manière légèrement différente.

- La propriété ```a``` est l'opacité de la couleur. C'est le 4ème paramètre optionnel dans chaque format de couleur vu précédemment. Cette valeur est habituellement donnée dans l'intervalle [0, 1] ou en pourcentage.

## name

Certaines couleurs [ont un nom selon la spécification CSS](https://drafts.csswg.org/css-color/#named-colors). Pour ces couleurs-là, le paramètre ```name``` renverra leur nom :

```javascript
rosso.name == 'red'
```

Note : si une couleur n'est pas exactement égale à une couleur nommée (par exemple à cause d'un arrondi dans un calcul), la propriété ```name``` renverra quand même le nom de la couleur dont elle est proche :

```javascript
(new Colore('red')).rgb == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)')).name == 'red'
```

La propriété ```exactName``` récupère le nom uniquement quand la couleur y correspond avec exactitude :

```javascript
(new Colore('red')).rgb == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)')).exactName == null
```

## luminance

La propriété ```luminance``` renvoie la valeur de luminance de la couleur, [telle que définie par le W3C](https://www.w3.org/TR/WCAG20-TECHS/G18.html#G18-procedure).

```javascript
rosso.luminance == 0.2126
```

# Exprimer une couleur dans différents formats

Les propriétés ```hex```, ```rgb```, ```hsl```, ```hwb```, ```lab``` et ```lch``` permettent de récupérer l'expression de la couleur au format correspondant :

```javascript
rosso.hex == '#ff0000'
rosso.rgb == 'rgb(255, 0, 0)'
rosso.hsl == 'hsl(0, 100%, 50%)'
rosso.hwb == 'hwb(0 0% 0%)'
rosso.lab == 'lab(54% 81 70)'
rosso.lch == 'lch(54% 107 41)'
```

Les couleurs transparentes (```a < 1```) sont aussi supportées :

```javascript
rossoTrasparente.hex == '#ff000099'
rossoTrasparente.rgb == 'rgb(255, 0, 0, 0.6)'
rossoTrasparente.hsl == 'hsl(0, 100%, 50%, 0.6)'
rossoTrasparente.hwb == 'hwb(0 0% 0% / 0.6)'
rossoTrasparente.lab == 'lab(54% 81 70 / 0.6)'
rossoTrasparente.lch == 'lch(54% 107 41 / 0.6)'
```

Ces propriétés masquent l'opacité quand elle est égale à 1. Les propriétés ```hexa```, ```rgba```, ```hsla```, ```hwba```, ```laba``` et ```lcha``` permettent de l'afficher dans tous les cas :

```javascript
rosso.hexa == '#ff0000ff'
rosso.rgba == 'rgb(255, 0, 0, 1)'
rosso.hsla == 'hsl(0, 100%, 50%, 1)'
rosso.hwba == 'hwb(0 0% 0% / 1)'
rosso.laba == 'lab(54% 81 70 / 1)'
rosso.lcha == 'lch(54% 107 41 / 1)'
```

# Modifier une couleur

## change

La méthode ```change``` permet de modifier n'importe quelle propriété d'une couleur, et renvoie un objet de classe ```Colore``` dont toutes les propriétés ont été recalculées suite à la modification.

### Comment l'utiliser :

```javascript
const result = color.change(prop, val, options = { replace, scale });
```

Elle s'applique à un objet de classe ```Colore```, ici ```color```.

Elle prend comme arguments :

- ```prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```val``` : un nombre ou pourcentage correspondant à la valeur qui sera **additionnée** à la valeur précédente de la propriété.

- ```options``` : un objet contenant les propriétés suivantes :

  - ```replace``` : un booléen. Si ```true```, la valeur de ```val``` **remplacera** la valeur précédente de la propriété, au lieu de s'y additionner.

  - ```scale``` : un booléen. Si ```true```, la valeur de ```val``` sera **multipliée** à la valeur précédente de la propriété, au lieu de s'y additionner.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est une copie de ```color``` dont la propriété ```prop``` a été modifiée.

### Exemples :

```javascript
let nuovoColore;

// Pour réduire la luminosité du rouge de 10% :
nuovoColore = rosso.change('l', '-10%');
// ce qui donne :
rosso.hsl == 'hsl(0, 100%, 50%)'
nuovoColore.hsl == 'hsl(0, 100%, 40%)'

// Pour remplacer la luminosité du rouge par 10% :
nuovoColore = rosso.change('l', '10%', { replace: true });
// ce qui donne :
nuovoColore.hsl == 'hsl(0, 100%, 10%)';

// Pour augmenter la luminosité du rouge de 50% de sa valeur actuelle:
nuovoColore = rosso.change('l', '150%', { scale: true });
// ce qui donne :
nuovoColore.hsl == 'hsl(0, 100%, 75%)';
```

## replace

La méthode ```replace``` est équivalente à ```change``` avec l'option ```{ replace: true }```.

### Comment l'utiliser :

```javascript
const result = color.replace(prop, val);
```

Elle s'applique à un objet de classe ```Colore```, ici ```color```.

Elle prend comme arguments :

- ```prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```val``` : un nombre ou pourcentage correspondant à la valeur qui **remplacera** la valeur précédente de la propriété.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est une copie de ```color``` dont la propriété ```prop``` a été remplacée.

### Exemple :

```javascript
// Pour remplacer la luminosité du rouge par 10% :
nuovoColore = rosso.replace('l', '10%');
// ce qui donne :
nuovoColore.hsl == 'hsl(0, 100%, 10%)';
```

## scale

La méthode ```scale``` est équivalente à ```change``` avec l'option ```{ scale: true }```.

### Comment l'utiliser :

```javascript
const result = color.scale(prop, val);
```

Elle s'applique à un objet de classe ```Colore```, ici ```color```.

Elle prend comme arguments :

- ```prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```val``` : un nombre ou pourcentage correspondant à la valeur qui sera **multipliée** à la valeur précédente de la propriété.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est une copie de ```color``` dont la propriété ```prop``` a été modifiée.

### Exemple :

```javascript
// Pour remplacer la luminosité du rouge par 10% :
nuovoColore = rosso.scale('l', '150%');
// ce qui donne :
nuovoColore.hsl == 'hsl(0, 100%, 75%)';
```

## darken, lighten
> option colorSpace qui change l (hsl) ou ciel (lab, lch)
## desaturate, saturate
> options colorSpace qui change s (hsl) ou ciec (lch)
## greyscale / grayscale
> options colorSpace qui change s (hsl) ou ciec (lch)

## complement

La méthode ```complement``` calcule la couleur complémentaire.

### Comment l'utiliser :

```javascript
const result = color.complement();
```

Elle s'applique à un objet de classe ```Colore```, ici ```color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est la couleur complémentaire de ```color```.

### Exemples :

```javascript
// La couleur complémentaire du rouge est le cyan / aqua
rosso.complement().name == 'aqua'

// La couleur complémentaire du blanc est le blanc lui-même
const white = new Couleur('white');
white.complement().name == 'white'
```

## negative / invert

La méthode ```negative``` (ou ```ìnvert```) calcule la couleur négative.

### Comment l'utiliser :

```javascript
const result = color.negative();
```

Elle s'applique à un objet de classe ```Colore```, ici ```color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est la couleur négative de ```color```.

### Exemples :

```javascript
// La couleur négative du blanc est le noir
const white = new Colore('white');
white.negative().name == 'black'

// La couleur négative du rouge est le cyan
rosso.negative().name == 'aqua'
```

# Fusionner des couleurs

## blend

La méthode statique ```blend``` permet de fusionner plusieurs couleurs. En d'autres termes, elle calcule la couleur que vous verriez à l'écran si vous superposiez plusieurs couleurs.

### Comment l'utiliser :

```javascript
const result = Colore.blend(color1, color2, color3, ...);
```

Elle prend comme arguments :

- un nombre arbitraire d'objets de type ```Colore``` ou de chaînes de caractères dans un format compatible avec ```new Colore()```. Chaque couleur sera superposée à la précédente.

Elle renvoie un objet de type ```Colore``` qui est la couleur que l'on voit en superposant toutes les couleurs fournies en argument l'une après l'autre.

> ```blend``` peut aussi être utilisée comme méthode non-statique appliquée à la première couleur :
>
>```javascript
>const result = color1.blend(color2, color3, ...)
>```

>Attention, si vous superposez une couleur opaque (telle que ```color.a == 1```) à une autre couleur, alors le résultat sera cette même couleur opaque ; puisqu'elle n'est pas transparente, elle ne laisse pas entrevoir la couleur en-dessous. ```blend``` est donc principalement utile pour superposer des couleurs transparentes (telles que ```color.a < 1```).

### Exemples :

```javascript
// Prenons du rouge et superposons du bleu avec opacité 0.5 par-dessus
const bluTrasparente = (new Colore('blue')).replace('a', .5);

Colore.blend('red', bluTrasparente).name == 'purple' // Le résultat est violet, comme on s'y attend

/***/

// Superposons toujours du rouge et du bleu, mais ajoutons-y du vert avec opacité 0.4
const verdeTrasparente = (new Colore('green')).replace('a', .4);

Colore.blend('red', bluTrasparente, verdeTrasparente).rgb == 'rgb(77, 51, 77)' // Le résultat est un violet désaturé, presque gris
```

## unblend

La méthode ```blend``` permettait de résoudre l'égalité suivant pour obtenir ```result``` :

```javascript
const result = Colore.blend(background, overlay);
```

La méthode statique ```unblend```, quant à elle, permet résoudre la même équation lorsqu'on veut trouver ```background```. Autrement dit, elle prend une couleur ```result``` et dé-fusionne ```overlay``` d'avec elle.

```javascript
const background = Colore.unblend(result, overlay);
```

### Comment l'utiliser :

```javascript
const result = Colore.blend(color1, color2, color3, ...);
```

Elle prend comme arguments :

- un nombre arbitraire d'objets de type ```Colore``` ou de chaînes de caractères dans un format compatible avec ```new Colore()```. Chaque couleur sera retirée à la précédente.

Elle renvoie un objet de type ```Colore``` qui est la couleur obtenur après avoir dé-fusionné toutes les couleurs fournies en argument une par une.

>```unblend``` peut aussi être utilisée comme méthode non-statique appliquée à la première couleur en tant qu'objet de type ```Colore``` :
>
>```javascript
>const result = color1.unblend(color2, color3, ...);
>```

### Exemple :

```javascript
// Avec blend, nous avons vu que fusionner du rouge et du bleu transparent donnait du violet. En toute logique, retirer le bleu transparent du violet devrait donc nous donner du rouge à nouveau.
const bluTrasparente = (new Colore('blue')).replace('a', .5);

Colore.unblend('purple', bluTrasparente).name == 'red' // C'est le cas !
```

## whatToBlend

La méthode ```blend``` permettait de résoudre l'égalité suivant pour obtenir ```result``` :

```javascript
result = Colore.blend(background, overlay)
```

La méthode statique ```whatToBlend```, quant à elle, permet re résoudre la même équation lorsqu'on veut trouver ```overlay```. Autrement dit, elle prend deux couleurs ```background``` et ```result``` et calcule quelle couleur ```overlay``` devrait être fusionnée avec ```background``` pour obtenir la couleur ```result```.

```javascript
overlay = Colore.whatToBlend(background, result)
```

### Comment l'utiliser :

```javascript
const overlay = Colore.whatToBlend(background, result, alpha, alphaStep);
```

- ```background``` et ```result``` sont soit un objet de type ```Colore```, soit une chaîne de caractères dans [un format compatible](link here) avec ```new Colore()```.

- ```alpha``` est la valeur (optionnelle) d'opacité que vous souhaitez obtenir pour ```overlay```. En effet, plusieurs couleurs différentes peuvent être solutions de cette équation. Si tel est le cas, fournir cette valeur ```alpha``` permet d'obtenir une solution unique.

- ```alphaStep``` est la valeur (optionnelle) d'écart entre l'opacité des solutions données par ```whatToBlend```, dans le cas où il en existe plusieurs. Par défaut, ```alphaStep = 0.1```.

### Exemples :

```javascript
// whatToBlend donne la liste des couleurs qui peuvent être superposées au rouge pour obtenir du violet :
Colore.whatToBlend('red', 'purple')
.map(c => c.rgb) == [
  'rgb(0, 0, 255, 0.5'),
  'rgb(43, 0, 213, 0.6'),
  'rgb(74, 0, 183, 0.7)',
  'rgb(96, 0, 160, 0.8)',
  'rgb(114, 0, 142, 0.9)',
  'purple'
];

// Si on précise qu'on veut une solution d'opacité 0.5, on obtient bien du bleu d'opacité 0.5. C'est bien la valeur de bluTrasparente dans les exemples de blend et unblend !
Colore.whatToBlend('red', 'purple', 0.5).rgb == 'rgb(0, 0, 255, 0.5)'
```

```whatToBlend``` peut aussi être utilisée comme méthode non-statique appliquée à l'objet ```background``` de type ```Colore``` : ```background.whatToBlend(result, alpha, alphaStep)```.

# Comparer deux couleurs

## contrast

La méthode statique ```contrast``` calcule le contraste entre deux couleurs.

Elle s'utilise comme ceci : ```Colore.contrast(color1, color2)```, où chaque argument est soit un objet de type ```Colore```, soit une chaîne de caractères dans [un format compatible](link here) avec ```new Colore()```.

Le résultat est un nombre entre 1 et 21.

```javascript
Colore.contrast('white', 'black') == 21
Colore.contrast('skyblue', 'darkblue') == 8.7835
```

Pour qu'un texte soit facilement lisible sur une surface colorée, il est nécessaire que le contraste entre la couleur de cette surface et la couleur du texte soit suffisamment élevé. La méthode ```contrast``` peut par exemple être pratique pour vérifier si deux couleurs vérifient les [recommandations du WCAG](https://www.w3.org/TR/WCAG20/#visual-audio-contrast-contrast).

```contrast``` peut aussi être utilisée comme une méthode non-statique appliquée à un objet de type ```Colore``` : ```color1.contrast(color2)```.

## contrastedText

La méthode ```contrastedText``` détermine si du texte noir ou blanc serait le plus visible sur une surface colorée.

### Comment l'utiliser :

```javascript
const result = color.contrastedText();
```

Elle s'applique à un objet de type ```Colore``` ; dans cet exemple, il s'agit de ```color```.

Elle ne prend aucun argument.

Elle renvoie la chaîne de caractère ```'white'``` ou ```'black'```.

### Exemples :

```javascript
// Sur une surface bleue ciel, le texte noir est plus lisible que le texte blanc
const skyBlue = new Colore('skyblue');
skyBlue.contrastedText() == 'black'

// Sur une surface bleue foncé, le texte blanc est plus lisible que le texte noir
const darkBlue = new Colore('darkblue');
darkBlue.contrastedText() == 'white'
```

## improveContrast

La méthode ```improveContrast``` modifie la couleur à laquelle elle est appliquée, plus particulièrement en augmentant ou diminuant sa luminosité CIE ```ciel```, pour lui donner un meilleur contraste avec une autre couleur.

### Comment l'utiliser :

```javascript
const result = color.improveContrast(referenceColor, desiredContrast, step, options = { lower, towards, maxIterations });
```

Elle s'applique à un objet de type ```Colore``` ; dans cet exemple, il s'agit de ```color```.

Elle prend comme arguments :

- un objet de type ```Colore``` ou une chaîne de caractères dans [un format compatible](link here) avec ```new Colore()```, ici ```referenceColor```, utilisé comme couleur de référence : la méthode cherche à améliorer la valeur de ```Colore.contrast(color, referenceColor)```.

- un nombre, ici ```desiredContrast```, qui est la valeur de contraste que la méthode essaiera d'atteindre.

- un nombre, ici ```step```, qui est la quantité qui sera ajoutée ou retirée de la luminosité CIE de ```color```. Par défaut, ```step = 2```, c'est-à-dire qu'à chaque étape, le pourcentage de luminosité CIE de ```colore``` augmente ou diminue de 2.

- un objet ```options``` contenant les propriétés suivantes :

  - ```lower``` : peut valoir ```true``` ou ```false```. Si ```true```, alors ```improveContrast``` ne cherchera pas uniquement à augmenter le contraste s'il est inférieur à ```desiredContrast```, mais elle pourra aussi le diminuer s'il y est supérieur. Par défaut, ```lower = false```.

  - ```towards``` : peut valoir ```'black'``` ou ```'white'```. Détermine si ```improveContrast``` doit augmenter le contraste en éclaircissant ou en assombrissant ```color```. Par défaut, le choix est fait automatiquement.

  - ```maxIterations``` : le nombre maximum de fois que ```improveContrast``` modifiera la couleur pour améliorer le contraste. Par défaut, ```improveContrast = 100```.

Elle renvoie un objet de classe ```Colore```, ici ```result```, qui est une copie de ```color``` à laquelle ont été appliquées les modifications de la propriété ```ciel``` telles que ```Colore.contrast(result, referenceColor) > desiredContrast```.

### Exemple :

```javascript
// Prenons l'exemple d'un fond bleu clair, sur lequel on veut placer du texte bleu ciel.
const lightblue = new Couleur('lightblue');
const skyblue = new Couleur('skyblue');
Colore.contrast(lightblue, skyblue) == 1.1396

// Le contraste entre les couleurs bleu ciel et bleu clair est trop faible pour être bien lisible. Le WCAG recommande un contraste d'au moins 4.5. Modifions skyblue pour atteindre cette valeur.
const newBlue = skyblue.improveContrast(lightblue, 4.5);

// On peut voir que le nouveau bleu est beaucoup plus sombre que le bleu ciel dont on est parti.
skyblue.hsl == 'hsl(197, 71%, 73%)'
newBlue.hsl == 'hsl(193, 100%, 24%)'

// Le contraste souhaité est atteint !
Colore.contrast(lightblue, newBlue) == 4.6554
```

## distance

La méthode statique ```distance``` mesure à quel point deux couleurs sont différentes.

## same

La méthode ```same``` détermine si deux couleurs sont identiques.

# Autres fonctions

## gradient