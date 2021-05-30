- [Utiliser colori.php](#utiliser-coloriphp)
- [Créer une couleur](#créer-une-couleur)
- [Propriétés d'une couleur](#propriétés-dune-couleur)
  - [Propriétés précalculées](#propriétés-précalculées)
  - [```name```](#name)
  - [```luminance```](#luminance)
- [Exprimer une couleur dans différents formats](#exprimer-une-couleur-dans-différents-formats)
- [Modifier une couleur](#modifier-une-couleur)
  - [```change```](#change)
  - [```replace```](#replace)
  - [```scale```](#scale)
  - [```greyscale``` / ```grayscale```](#greyscale--grayscale)
  - [```sepia```](#sepia)
  - [```complement```](#complement)
  - [```negative``` / ```invert```](#negative--invert)
- [Fusionner des couleurs](#fusionner-des-couleurs)
  - [```blend```](#blend)
  - [```unblend```](#unblend)
  - [```whatToBlend```](#whattoblend)
- [Comparer deux couleurs](#comparer-deux-couleurs)
  - [```contrast```](#contrast)
  - [```contrastedText```](#contrastedtext)
  - [```improveContrast```](#improvecontrast)
  - [```distance```](#distance)
  - [```same```](#same)
- [Autres fonctions](#autres-fonctions)
  - [```gradient```](#gradient)

(Note : _**colori**_ signifie _**couleurs**_ en italien. Le singulier est _**colore**_.)

# Utiliser colori.php

Il suffit de télécharger le fichier [colori.php](https://github.com/Remiscan/colori/releases/latest/download/colori.php), puis de l'importer dans votre code PHP comme suit :

```php
require_once 'colori.php';
```

Le fichier colori.php commence par ```class Couleur```. Vous pouvez remplacer ```Couleur``` par un autre nom pour nommer la classe différemment. Dans cette documentation, nous utiliserons ```Colore```.

# Créer une couleur

Pour avoir accès aux fonctions de colori.php, vous devez créer un objet de classe ```Colore``` :

```php
$rosso = new Colore('red');

// Ce qui aura pour résultat :

$rosso == object(Colore)[1]
  public 'r' => float 1,
  public 'g' => float 0,
  public 'b' => float 0,
  public 'a' => float 1,
  public 'h' => float 0,
  public 's' => float 1,
  public 'l' => float 0.5,
  public 'w' => float 0,
  public 'b' => float 0,
  public 'c' => float 0.54291,
  public 'c' => float 80.815,
  public 'c' => float 69.893,
  public 'c' => float 106.85,
  public 'c' => float 0.11349
```

Le paramètre à fournir en entrée de ```new Colore(parametre)``` doit être une chaîne de caractères dans un format valide selon [la spécification CSS des formats de couleurs](https://drafts.csswg.org/css-color/#colorunits).

#### Nom

Une couleur peut être créée à partir de son nom dans [la spécification CSS](https://drafts.csswg.org/css-color/#named-colors) :

```php
$rosso = new Colore('red');
```

#### Format RGB (hexadécimal)

Une couleur peut être créée à partir de son expression au format hexadécimal, par exemple ```#FF0000``` ou ```#F00``` pour le rouge :

```php
$rosso = new Colore('#FF0000');
// ou
$rosso = new Colore('#F00');
```

Pour les couleurs transparentes, vous pouvez ajouter un ou deux caractères à la fin de l'expression de la couleur. Par exemple, pour un rouge d'opacité ```0.6``` (ce qui correspond à ```9``` ou ```99``` en hexadécimal) :

```php
$rossoTrasparente = new Colore('#FF000099');
// ou
$rossoTrasparente = new Colore('#F009');
```

#### Format RGB (fonctionnel)

Une couleur peut être créée à partir de son expression au format RGB, par exemple ```rgb(255, 0, 0)``` pour le rouge :

```php
$rosso = new Colore('rgb(255, 0, 0)');
// ou
$rosso = new Colore('rgb(255 0 0)');

// et avec le 4è paramètre de transparence :

$rossoTrasparente = new Colore('rgb(255, 0, 0, 0.6)');
// ou
$rossoTrasparente = new Colore('rgb(255 0 0 / 0.6)');
```

Vous pouvez remplacer ```rgb``` par ```rgba```, cela aura le même effet.

#### Format HSL

Une couleur peut être créée à partir de son expression au format HSL, par exemple ```hsl(0, 100%, 50%)``` pour le rouge :

```php
$rosso = new Colore('hsl(0, 100%, 50%)');
// ou
$rosso = new Colore('hsl(0 100% 50%)');

// et avec le 4è paramètre de transparence :

$rossoTrasparente = new Colore('hsl(0, 100%, 50%, 0.6)');
// ou
$rossoTrasparente = new Colore('hsl(0 100% 50% / 0.6)');
```

Vous pouvez remplacer ```hsl``` par ```hsla```, cela aura le même effet.

#### Format HWB

Une couleur peut être créée à partir de son expression au format HWB, par exemple ```hwb(0 0% 0%)``` pour le rouge :

```php
$rosso = new Colore('hwb(0 0% 0%)');

// et avec le 4è paramètre de transparence :

$rossoTrasparente = new Colore('hwb(0 0% 0% / 0.6)');
```

#### Format LAB

Une couleur peut être créée à partir de son expression au format LAB, par exemple ```lab(54% 81 70)``` pour le rouge :

```php
$rosso = new Colore('lab(54% 81 70)');

// et avec le 4è paramètre de transparence :

$rossoTrasparente = new Colore('lab(54% 81 70 / 0.6)');
```

L'espace de couleurs LAB contient des couleurs qui ne sont pas dans l'espace de couleurs sRGB, utilisé pour les formats RGB, HSL et HWB. Puisque CSS ne supporte pas encore nativement les couleurs au format LAB, passer une telle couleur à ```new Colore()``` la convertira en la couleur la plus proche dans l'espace sRGB.

Pour cette raison, vous pourriez remarquer certaines contradictions, par exemple :

```php
$a = new Colore('lab(50% 118 43)');
$a->rgb() == 'rgb(232, 0, 78)'
// mais :
$b = new Colore('rgb(232, 0, 78)')
$b->lch() == 'lab(50% 76 28)'
```

#### Format LCH

Une couleur peut être créée à partir de son expression au format LCH, par exemple ```lch(54% 107 41)``` pour le rouge :

```php
$rosso = new Colore('lch(54% 107 41)');

// et avec le 4è paramètre de transparence :

$rossoTrasparente = new Colore('lch(54% 107 41 / 0.6)');
```

L'espace de couleurs LCH contient des couleurs qui ne sont pas dans l'espace de couleurs sRGB, utilisé pour les formats RGB, HSL et HWB. Puisque CSS ne supporte pas encore nativement les couleurs au format LCH, passer une telle couleur à ```new Colore()``` la convertira en la couleur la plus proche dans l'espace sRGB.

Pour cette raison, vous pourriez remarquer certaines contradictions, par exemple :

```php
$a = new Colore('lch(50% 125 20)');
$a->rgb() == 'rgb(232, 0, 78)'
// mais :
$b = new Colore('rgb(232, 0, 78)')
$b->lch() == 'lch(50% 81 20)'
```

# Propriétés d'une couleur

## Propriétés précalculées

Lorsque un objet de classe ```Colore``` est créé en utilisant ```new Colore(expression)```, 14 propriétés différentes de la couleur sont calculées. Elles correspondent à tous les paramètres des différents formats de couleurs :

- Les propriétés ```r```, ```g``` et ```b``` sont les valeurs de rouge, vert et bleu de la couleur lorsqu'elle est exprimée au format ```rgb(r, g, b)```. Ces valeurs sont habituellement données entre 0 et 255 ou en pourcentages.

- La propriété ```h``` est la valeur de teinte de la couleur lorsqu'elle est exprimée au format ```hsl(h, s, l)``` ou ```hwb(h, w, bk)```. Cette valeur est un angle, habituellement donné en degrés (de 0 à 360), grades (de 0 à 400), radians (de 0 à 2π) ou tours (de 0 à 1).

- Les propriétés ```s``` et ```l``` sont respectivement les valeurs de saturation et de luminosité de la couleur lorsqu'elle est exprimée au format ```hsl(h, s, l)```. Ces valeurs sont données en pourcentages.

- Les propriétés ```w``` et ```bk``` sont les valeurs de blancheur et noirceur de la couleur lorsqu'elle est exprimée au format ```hwb(h w bk)```. Ces valeurs sont données en pourcentages.

- La propriété ```ciel``` est la luminosité de la couleur dans l'espace de couleurs CIELAB, lorsqu'elle est exprimée aux formats ```lab(ciel ciea cieb)``` ou ```lch(ciel ciec cieh)```. Cette valeur est donnée en pourcentage. La spécification autorise des valeurs supérieures à 100% pour une compatibilité avec le standard HDR.

- Les propriétés ```ciea``` et ```cieb``` sont respectivement les valeurs de la couleur sur les axes "a" et "b" de l'espace de couleurs CIELAB, lorsqu'elle est exprimée au format ```lab(ciel ciea cieb)```. Elles peuvent prendre n'importe quelle valeur numérique, mais sont en général contenues dans l'intervalle [-160, 160].

- La propriété ```ciec``` est la valeur de chroma de la couleur losqu'elle est exprimée au format ```lch(ciel ciec cieh)```. Elle peut prendre n'importe quelle valeur numérique positive, mais est en général contenue dans l'intervalle [0, 230].

- La propriété ```cieh``` est la valeur de teinte de la couleur lorsqu'elle est exprimée au format ```lch(ciel ciec cieh)```. Cette valeur est un angle, habituellement donné en degrés (de 0 à 360), grades (de 0 à 400), radians (de 0 à 2π) ou tours (de 0 à 1). Elle est similaire à la propriété ```h``` du format HSL, mais sa valeur est définie d'une manière légèrement différente.

- La propriété ```a``` est l'opacité de la couleur. C'est le 4ème paramètre optionnel dans chaque format de couleur vu précédemment. Cette valeur est habituellement donnée dans l'intervalle [0, 1] ou en pourcentage.

## ```name```

Certaines couleurs [ont un nom selon la spécification CSS](https://drafts.csswg.org/css-color/#named-colors). Pour ces couleurs-là, le paramètre ```name``` renverra leur nom :

```php
(new Colore('red'))->name() == 'red'
```

Note : si une couleur n'est pas exactement égale à une couleur nommée (par exemple à cause d'un arrondi dans un calcul), la propriété ```name``` renverra quand même le nom de la couleur dont elle est proche :

```php
(new Colore('red'))->rgb() == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)'))->name() == 'red'
```

La propriété ```exactName``` récupère le nom uniquement quand la couleur y correspond avec exactitude :

```php
(new Colore('red'))->rgb() == 'rgb(255, 0, 0)'
(new Colore('rgb(255, 0.1, 0.1)'))->exactName() == null
```

## ```luminance```

La propriété ```luminance``` renvoie la valeur de luminance de la couleur, [telle que définie par le W3C](https://www.w3.org/TR/WCAG20-TECHS/G18.html#G18-procedure).

```php
rosso.luminance == 0.2126
```

# Exprimer une couleur dans différents formats

Les propriétés ```hex```, ```rgb```, ```hsl```, ```hwb```, ```lab``` et ```lch``` permettent de récupérer l'expression de la couleur au format correspondant :

```php
$rosso = new Colore('red');
$rosso->hex() == '#ff0000'
$rosso->rgb() == 'rgb(255, 0, 0)'
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$rosso->hwb() == 'hwb(0 0% 0%)'
$rosso->lab() == 'lab(54% 81 70)'
$rosso->lch() == 'lch(54% 107 41)'
```

Les couleurs transparentes (```a < 1```) sont aussi supportées :

```php
$rossoTrasparente = new Colore('rgb(255, 0, 0, 0.6)');
$rossoTrasparente->hex() == '#ff000099'
$rossoTrasparente->rgb() == 'rgb(255, 0, 0, 0.6)'
$rossoTrasparente->hsl() == 'hsl(0, 100%, 50%, 0.6)'
$rossoTrasparente->hwb() == 'hwb(0 0% 0% / 0.6)'
$rossoTrasparente->lab() == 'lab(54% 81 70 / 0.6)'
$rossoTrasparente->lch() == 'lch(54% 107 41 / 0.6)'
```

Ces propriétés masquent l'opacité quand elle est égale à 1. Les propriétés ```hexa```, ```rgba```, ```hsla```, ```hwba```, ```laba``` et ```lcha``` permettent de l'afficher dans tous les cas :

```php
$rosso = new Colore('red');
$rosso->hexa() == '#ff0000ff'
$rosso->rgba() == 'rgb(255, 0, 0, 1)'
$rosso->hsla() == 'hsl(0, 100%, 50%, 1)'
$rosso->hwba() == 'hwb(0 0% 0% / 1)'
$rosso->laba() == 'lab(54% 81 70 / 1)'
$rosso->lcha() == 'lch(54% 107 41 / 1)'
```

# Modifier une couleur

## ```change```

La méthode ```change``` permet de modifier n'importe quelle propriété d'une couleur, et renvoie un objet de classe ```Colore``` dont toutes les propriétés ont été recalculées suite à la modification.

### Comment l'utiliser :

```php
$result = $color->change($prop, $val, $options = (object)['replace' => <Boolean>, 'scale' => <Boolean>]);
```

Elle s'applique à un objet de classe ```Colore```, ici ```$color```.

Elle prend comme arguments :

- ```$prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```$val``` : un nombre ou pourcentage correspondant à la valeur qui sera **additionnée** à la valeur précédente de la propriété.

- ```$options``` : un objet contenant les propriétés suivantes :

  - ```replace``` (défaut = ```false```) : un booléen. Si ```true```, la valeur de ```$val``` **remplacera** la valeur précédente de la propriété, au lieu de s'y additionner.

  - ```scale``` (défaut = ```false```) : un booléen. Si ```true```, la valeur de ```$val``` sera **multipliée** à la valeur précédente de la propriété, au lieu de s'y additionner.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une copie de ```$color``` dont la propriété ```$prop``` a été modifiée.

### Exemples :

```php
// Réduisons la luminosité du rouge de 10% :
$rosso = new Colore('red');
$nuovoColore = $rosso->change('l', '-10%');
// ce qui donne :
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$nuovoColore->hsl() == 'hsl(0, 100%, 40%)'
```
```php
// Remplaçons la luminosité du rouge par 10% :
$rosso = new Colore('red');
$nuovoColore = $rosso->change('l', '10%', (object)['replace' => true ]);
// ce qui donne :
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$nuovoColore->hsl() == 'hsl(0, 100%, 10%)';
```
```php
// Augmentons la luminosité du rouge de 50% de sa valeur actuelle :
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$nuovoColore = $rosso->change('l', '150%', (object)['scale' => true]);
// ce qui donne :
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$nuovoColore->hsl() == 'hsl(0, 100%, 75%)';
```

## ```replace```

La méthode ```replace``` est équivalente à ```change``` avec l'option ```{ replace: true }```.

### Comment l'utiliser :

```php
$result = $color->replace($prop, $val);
```

Elle s'applique à un objet de classe ```Colore```, ici ```$color```.

Elle prend comme arguments :

- ```$prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```$val``` : un nombre ou pourcentage correspondant à la valeur qui **remplacera** la valeur précédente de la propriété.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une copie de ```$color``` dont la propriété ```$prop``` a été remplacée.

### Exemple :

```php
// Remplaçons la luminosité du rouge par 10% :
$rosso = new Colore('red');
$nuovoColore = $rosso->replace('l', '10%');
// ce qui donne :
$nuovoColore->hsl() == 'hsl(0, 100%, 10%)';
```

## ```scale```

La méthode ```scale``` est équivalente à ```change``` avec l'option ```(object)["scale" => true]```.

### Comment l'utiliser :

```php
$result = $color->scale($prop, $val);
```

Elle s'applique à un objet de classe ```Colore```, ici ```$color```.

Elle prend comme arguments :

- ```$prop``` : une chaîne de caractères correspondant au nom de la propriété à modifier, par exemple ```'r'```, ```'g'```, ```'b'```, etc.

- ```$val``` : un nombre ou pourcentage correspondant à la valeur qui sera **multipliée** à la valeur précédente de la propriété.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une copie de ```$color``` dont la propriété ```$prop``` a été modifiée.

### Exemple :

```php
// Augmentons la luminosité du rouge de 50% de sa valeur actuelle :
$rosso = new Colore('red');
$nuovoColore = $rosso->scale('l', '150%');
// ce qui donne :
$rosso->hsl() == 'hsl(0, 100%, 50%)'
$nuovoColore->hsl() == 'hsl(0, 100%, 75%)'
```

## ```greyscale``` / ```grayscale```

La méthode ```greyscale``` (ou ```grayscale```) transforme une couleur en un gris ayant la même luminosité qu'elle.

### Comment l'utiliser :

```php
$result = $color->greyscale();
```

Elle s'applique à un objet de class ```Colore```, ici ```$color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une copie de ```$color``` dont la saturation a été réduite à 0.

### Exemple :

```php
// Prenons du bleu
$blu = new Colore('blue');
$blu->hsl() == 'hsl(240, 100%, 50%)'

// Appliquons sepia au bleu
$grigio = $blu->greyscale();
$grigio->hsl() == 'hsl(240, 0%, 50%)'
```

## ```sepia```

La méthode ```sepia``` transforme une couleur en un ton sépia.

### Comment l'utiliser :

```php
$result = $color->sepia();
```

Elle s'applique à un objet de class ```Colore```, ici ```$color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une modification de ```$color``` vers les tons sépia.

### Exemple :

```php
// Prenons du bleu
$blu = new Colore('blue');
$blu->rgb() == 'rgb(0, 0, 255)'

// Appliquons greyscale au bleu
$seppia = $blu->sepia();
$seppia->rgb() == 'rgb(48, 43, 33)'
```

## ```complement```

La méthode ```complement``` calcule la couleur complémentaire.

### Comment l'utiliser :

```php
$result = $color->complement();
```

Elle s'applique à un objet de classe ```Colore```, ici ```$color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est la couleur complémentaire de ```$color```.

### Exemples :

```php
// La couleur complémentaire du rouge est le cyan / aqua
$rosso = new Colore('red');
$rosso->complement()->name() == 'aqua'
```
```php
// La couleur complémentaire du blanc est le blanc lui-même
$bianco = new Couleur('white');
$bianco->complement()->name() == 'white'
```

## ```negative``` / ```invert```

La méthode ```negative``` (ou ```ìnvert```) calcule la couleur négative.

### Comment l'utiliser :

```php
$result = $color->negative();
```

Elle s'applique à un objet de classe ```Colore```, ici ```$color```.

Elle ne prend aucun argument.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est la couleur négative de ```$color```.

### Exemples :

```php
// La couleur négative du blanc est le noir
$bianco = new Colore('white');
$bianco->negative()->name() == 'black'
```
```php
// La couleur négative du rouge est le cyan
$rosso = new Colore('red');
$rosso->negative()->name() == 'aqua'
```

# Fusionner des couleurs

## ```blend```

La méthode statique ```blend``` permet de fusionner plusieurs couleurs. En d'autres termes, elle calcule la couleur que vous verriez à l'écran si vous superposiez plusieurs couleurs.

### Comment l'utiliser :

```php
$result = Colore::blend($color1, $color2, $color3, ...);
```

Elle prend comme arguments :

- un nombre arbitraire d'objets de type ```Colore``` ou de chaînes de caractères dans un format compatible avec ```new Colore()```. Chaque couleur sera superposée à la précédente.

Elle renvoie un objet de type ```Colore``` qui est la couleur que l'on voit en superposant toutes les couleurs fournies en argument l'une après l'autre.

>Attention, si vous superposez une couleur opaque (telle que ```$color->a == 1```) à une autre couleur, alors le résultat sera cette même couleur opaque ; puisqu'elle n'est pas transparente, elle ne laisse pas entrevoir la couleur en-dessous. ```blend``` est donc principalement utile pour superposer des couleurs transparentes (telles que ```$color->a < 1```).

### Exemples :

```php
// Prenons du rouge et superposons du bleu avec opacité 0.5 par-dessus
$bluTrasparente = (new Colore('blue'))->replace('a', .5);
$result = Colore::blend('red', $bluTrasparente);

// Le résultat est violet, comme on s'y attend
$result->name() == 'purple' 
```
```php
// Superposons toujours du rouge et du bleu, mais ajoutons-y du vert avec opacité 0.4
$verdeTrasparente = (new Colore('green'))->replace('a', .4);
$result = Colore::blend('red', $bluTrasparente, $verdeTrasparente);

// Le résultat est un violet désaturé, presque gris
$result->rgb() == 'rgb(77, 51, 77)' 
```

## ```unblend```

La méthode ```blend``` permettait de résoudre l'égalité suivante pour obtenir ```$result``` :

```php
$result = Colore::blend($background, $overlay);
```

La méthode statique ```unblend```, quant à elle, permet résoudre la même équation lorsqu'on veut trouver ```$background```. Autrement dit, elle prend une couleur ```$result``` et dé-fusionne ```$overlay``` d'avec elle.

```php
$background = Colore::unblend($result, $overlay);
```

### Comment l'utiliser :

```php
$result = Colore::unblend($color1, $color2, $color3, ...);
```

Elle prend comme arguments :

- un nombre arbitraire d'objets de type ```Colore``` ou de chaînes de caractères dans un format compatible avec ```new Colore()```. Chaque couleur sera retirée à la précédente.

Elle renvoie un objet de type ```Colore``` qui est la couleur obtenue après avoir dé-fusionné toutes les couleurs fournies en argument une par une.

### Exemple :

```php
// Avec blend, nous avons vu que fusionner du rouge et du bleu transparent donnait du violet. En toute logique, retirer le bleu transparent du violet devrait donc nous donner du rouge à nouveau.
$bluTrasparente = (new Colore('blue'))->replace('a', .5);
$result = Colore::unblend('purple', $bluTrasparente);

// C'est le cas !
$result->name() == 'red'
```

## ```whatToBlend```

La méthode ```blend``` permettait de résoudre l'égalité suivante pour obtenir ```$result``` :

```php
$result = Colore::blend($background, $overlay)
```

La méthode statique ```whatToBlend```, quant à elle, permet re résoudre la même équation lorsqu'on veut trouver ```$overlay```. Autrement dit, elle prend deux couleurs ```$background``` et ```$result``` et calcule quelle couleur ```$overlay``` devrait être fusionnée avec ```$background``` pour obtenir la couleur ```$result```.

```php
$overlay = Colore::whatToBlend($background, $result)
```

### Comment l'utiliser :

```php
$overlay = Colore::whatToBlend($background, $result, $alpha, $alphaStep);
```

Elle prend comme arguments :

- ```$background``` et ```$result``` : deux objets de type ```Colore``` ou chaînes de caractères dans un format compatible avec ```new Colore()```.

- ```$alpha``` : un nombre, la valeur (optionnelle) d'opacité que vous souhaitez obtenir pour ```$overlay```. En effet, plusieurs couleurs différentes peuvent être solutions de cette équation. Si tel est le cas, fournir cette valeur ```$alpha``` permet d'obtenir une solution unique.

- ```$alphaStep``` (défaut = ```0.1```) : un nombre, la valeur (optionnelle) d'écart entre l'opacité des solutions données par ```whatToBlend```, dans le cas où il en existe plusieurs.

### Exemples :

```php
// whatToBlend donne la liste des couleurs qui peuvent être superposées au rouge pour obtenir du violet :
$result = Colore::whatToBlend('red', 'purple');
array_map($result, fn($c) => $c->rgb()) == [
  'rgb(0, 0, 255, 0.5)',
  'rgb(43, 0, 213, 0.6)',
  'rgb(74, 0, 183, 0.7)',
  'rgb(96, 0, 160, 0.8)',
  'rgb(114, 0, 142, 0.9)',
  'purple'
];
```
```php
// Si on précise qu'on veut une solution d'opacité 0.5, on obtient bien du bleu d'opacité 0.5. C'est bien la valeur de bluTrasparente dans les exemples de blend et unblend !
$result = Colore::whatToBlend('red', 'purple', 0.5);
$result->rgb() == 'rgb(0, 0, 255, 0.5)'
```

# Comparer deux couleurs

## ```contrast```

La méthode statique ```contrast``` calcule le contraste entre deux couleurs.

### Comment l'utiliser :

```php
$result = Colore::contrast($color1, $color2);
```

Elle prend comme arguments :

- ```$color1``` et ```$color2``` : deux objets de type ```Colore``` ou chaînes de caractères dans un format compatible avec ```new Colore()```.

Elle renvoie un nombre entre 1 et 21.

### Exemples :

```php
Colore::contrast('white', 'black') == 21
Colore::contrast('skyblue', 'darkblue') == 8.7835
```

Pour qu'un texte soit facilement lisible sur une surface colorée, il est nécessaire que le contraste entre la couleur de cette surface et la couleur du texte soit suffisamment élevé. La méthode ```contrast``` peut par exemple être pratique pour vérifier si deux couleurs vérifient les [recommandations du WCAG](https://www.w3.org/TR/WCAG20/#visual-audio-contrast-contrast).

## ```contrastedText```

La méthode ```contrastedText``` détermine si du texte noir ou blanc serait le plus visible sur une surface colorée.

### Comment l'utiliser :

```php
$result = $color->contrastedText();
```

Elle s'applique à un objet de type ```Colore```, ici ```$color```.

Elle ne prend aucun argument.

Elle renvoie la chaîne de caractère ```'white'``` ou ```'black'```.

### Exemples :

```php
// Sur une surface bleue ciel, le texte noir est plus lisible que le texte blanc
$bluCielo = new Colore('skyblue');
$bluCielo->contrastedText() == 'black'
```
```php
// Sur une surface bleue foncé, le texte blanc est plus lisible que le texte noir
$bluScuro = new Colore('darkblue');
$bluScuro->contrastedText() == 'white'
```

## ```improveContrast```

La méthode ```improveContrast``` modifie la couleur à laquelle elle est appliquée, plus particulièrement en augmentant ou diminuant sa luminosité CIE ```ciel```, pour lui donner un meilleur contraste avec une autre couleur.

### Comment l'utiliser :

```php
$result = $color->improveContrast($referenceColor, $desiredContrast, $step, $options = (object)["lower" => <Boolean>, "towards" => <Boolean>, "maxIterations" => <Boolean>]);
```

Elle s'applique à un objet de type ```Colore``` ; dans cet exemple, il s'agit de ```$color```.

Elle prend comme arguments :

- ```$referenceColor``` : un objet de type ```Colore``` ou une chaîne de caractères dans un format compatible avec ```new Colore()```, utilisé comme couleur de référence : la méthode cherche à améliorer la valeur de ```Colore::contrast($color, $referenceColor)```.

- ```$desiredContrast``` : un nombre utilisé comme la valeur de contraste que la méthode essaiera d'atteindre.

- ```$step``` (défaut = ```2```) : un nombre utilisé comme la quantité qui sera ajoutée ou retirée de la luminosité CIE de ```$color``` ; c'est-à-dire qu'à chaque étape, ```$color->ciel``` augmente ou diminue de ```$step```%.

- ```$options``` : un objet contenant les propriétés suivantes :

  - ```lower``` (défaut = ```false```): peut prendre les valeurs suivantes : 
    - ```false``` : ```improveContrast``` ne fera rien si ```Colore::contrast($color, $referenceColor) > $desiredContrast```.
    - ```true``` : quand ```Colore::contrast($color, $referenceColor) > $desiredContrast```, ```$color``` sera modifiée pour réduire le contraste afin qu'il se rapproche le plus possible de ```$desiredContrast```.

  - ```towards``` (défaut = ```null```) : détermine si ```improveContrast``` doit augmenter le contraste en éclaircissant ou en assombrissant ```$color```. Peut prendre les valeurs suivantes :
    - ```'black'``` : ```improveContrast``` essaiera uniquement d'améliorer le contraste et assombrissant ```$color```.
    - ```'white'``` : ```improveContrast``` essaiera uniquement d'améliorer le contraste et éclaircissant ```$color```.
    - ```null``` : ```improveContrast``` essaiera de déterminer automatiquement s'il vaut mieux assombrir ou éclaircir ```$color```. Si elle n'y parvient pas, la valeur choisie sera ```'black'```.

  - ```maxIterations``` (défaut = ```100```) : le nombre maximum de fois que ```improveContrast``` modifiera la couleur pour améliorer le contraste.

Elle renvoie un objet de classe ```Colore```, ici ```$result```, qui est une copie de ```$color``` à laquelle ont été appliquées les modifications de la propriété ```ciel``` telles que ```Colore::contrast($result, $referenceColor) > $desiredContrast```.

### Exemple :

```php
// Prenons l'exemple d'un fond bleu clair, sur lequel on veut placer du texte bleu ciel.
$bluChiaro = new Couleur('lightblue');
$bluCielo = new Couleur('skyblue');
Colore::contrast($bluChiaro, $bluCielo) == 1.1396

// Le contraste entre les couleurs bleu ciel et bleu clair est trop faible pour être bien lisible. Le WCAG recommande un contraste d'au moins 4.5. Modifions la couleur bleu ciel pour atteindre cette valeur.
$nuovoBlue = $bluCielo->improveContrast($bluChiaro, 4.5);

// On peut voir que le nouveau bleu est beaucoup plus sombre que le bleu ciel dont on est parti.
$bluCielo->hsl() == 'hsl(197, 71%, 73%)'
$nuovoBlu->hsl() == 'hsl(193, 100%, 24%)'

// Le contraste souhaité est atteint !
Colore::contrast($bluChiaro, $nuovoBlue) == 4.6554
```

## ```distance```

La méthode statique ```distance``` mesure à quel point deux couleurs sont différentes.

### Comment l'utiliser :

```php
$result = Colore::distance($color1, $color2, $format, $tolerance);
```

Elle prend comme arguments :

- ```$color1``` et ```$color2``` : deux objets de type ```Colore``` ou chaînes de caractères dans un format compatible avec ```new Colore()```.

- ```$format``` : une chaîne de caractères, détermine quel format des couleurs sera utilisé pour mesurer leur distance. Peut prendre les valeurs suivantes :
  - ```'rgb'```, ```'hsl'```, ```'hwb'```, ```'lab'``` ou ```'lch'``` : la distance entre les deux couleurs sera calculée en additionnant la différence des valeurs de chaque propriété du format choisi.
  - ```null``` (défaut) : la distance sera calculée en faisant la moyenne des distances pour tous les formats.

- ```$tolerance``` (défaut = ```0.02```) : un nombre qui représente une certaine tolérance pour ignorer certaines propriétés quand elles n'ont aucun effet. Par exemple, dans le format HSL, si L = 0, alors la couleur est noire indépendament des valeurs de H et S. Pour pouvoir tenir compte de ce fait même quand des arrondis ont rendu la valeur L des couleurs légèrement supérieure à 0, la méthode ```distance``` ignore H et S quand ```$color1->l < $tolerance && $color2->l < $tolerance```.

Elle renvoie un nombre positif.

### Exemples :

```php
// Ce sont des couleurs identiques
Colore::distance('red', 'red') == 0
```
```php
// Ce sont des couleurs bien différentes
Colore::distance('red', 'blue') == 1.3467999999999998
```
```php
// Malgré des valeurs très différentes de H et S, les deux couleurs sont du noir
Colore::distance('hsl(200, 50%, 0%)', 'hsl(50, 35%, 0%)') == 0
```

## ```same```

La méthode statique ```same``` détermine si deux couleurs sont identiques.

### Comment l'utiliser :

```php
$result = Colore::same($color1, $color2, $tolerance);
```

Elle prend comme arguments :

- ```$color1``` et ```$color2``` : deux objets de type ```Colore``` ou chaînes de caractères dans un format compatible avec ```new Colore()```.

- ```$tolerance``` (défaut = ```0.02```) : un nombre qui représente la distance minimale entre deux couleurs pour qu'elles soient considérées différentes.

Elle renvoie ```true``` si les couleurs sont considérées identiques, ```false``` sinon.

### Exemples :

```php
// Ce sont des couleurs identiques
Colore::same('red', 'red') == true
```
```php
// Ce sont des couleurs différentes
Colore::same('red', 'blue') == false
```
```php
// Malgré des valeurs très différentes de H et S, les deux couleurs sont du noir
Colore::same('hsl(200, 50%, 0%)', 'hsl(50, 35%, 0%)') == true
```
```php
// Les deux couleurs sont tellement proches qu'elles sont considérées identiques
Colore::same('rgb(0, 0, 255)', 'rgb(0, 0, 254)') == true
```

# Autres fonctions

## ```gradient```

La méthode statique ```gradient``` génère un dégradé entre deux couleurs qui évite la zone grise (voir [cet article](https://css-tricks.com/the-gray-dead-zone-of-gradients/)).

### Comment l'utiliser :

```php
$result = Colore::gradient($from, $to, $steps);
```

Elle prend comme arguments :

- ```$from``` et ```$to``` : deux objets de type ```Colore``` ou chaînes de caractères dans un format compatible avec ```new Colore()```.

- ```$steps``` (défaut = ```5```) : le nombre d'étapes - c'est-à-dire le nombre de couleurs qui seront calculées - pour passer de ```$from``` à ```$to```. Plus ce nombre est élevé, plus le dégradé sera fluide et évitera la zone grise.

Elle renvoie un ```Array``` de longueur ```$steps + 1``` d'objets de type ```Colore```, de la forme ```[$from, $color2, $color3, ..., $to]```.

### Exemple :

```php
$colori = Colore::gradient('indigo', 'orange');

// Pour utiliser le dégradé en CSS, plaçons les couleurs dans la syntaxe de dégradé CSS :
$gradiente = `linear-gradient(to right, ${colori.map(c => c->name() || c->rgb()).join(', ')})`;

// En plaçant ceci comme valeur de background-image en CSS, le dégradé serait affiché
$$gradiente == 'linear-gradient(to right, indigo, rgb(137, 0, 116), rgb(192, 0, 105), rgb(238, 42, 80), rgb(255, 109, 52), orange)'
```