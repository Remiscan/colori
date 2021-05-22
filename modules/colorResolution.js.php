// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Couleur from '/colori/colori.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



// RegExp du séparateur entre arguments : virgule puis espace(s) optionnel(s)
const vSep = '\\,(?: +)?';
// RegExp des options d'une méthode
const vOpt = 'true|false|\\{(?:.+)?\\}';
// RegExp des arguments d'une méthode qui prend un nombre ou pourcentage et des options
const vNPandOptions = `(${Couleur.vNP})(?:${vSep}(${vOpt}))?`;
// RegExp des arguments d'une méthode qui prend un nom de propriété, une valeur (en pourcentage) et des options
const vPropNPandOptions = `(${Couleur.vProp})${vSep}(${Couleur.vNP})(?:${vSep}(${vOpt}))?`;

// Liste des méthodes supportées par le champ
const methodes = [
  {
    name: 'change',
    args: new RegExp(vPropNPandOptions)
  }, {
    name: 'replace',
    args: new RegExp(vPropNPandOptions)
  }, {
    name: 'scale',
    args: new RegExp(vPropNPandOptions)
  }, {
    name: 'complement',
    args: null
  }, {
    name: 'negative',
    args: null
  }, {
    name: 'invert',
    args: null
  }, {
    name: 'darken',
    args: new RegExp(vNPandOptions)
  }, {
    name: 'lighten',
    args: new RegExp(vNPandOptions)
  }, {
    name: 'desaturate',
    args: new RegExp(vNPandOptions)
  }, {
    name: 'saturate',
    args: new RegExp(vNPandOptions)
  }, {
    name: 'greyscale',
    args: null
  }, {
    name: 'grayscale',
    args: null
  }, {
    name: 'blend',
    args: [
      new RegExp(`^(.+)${vSep}(${Couleur.vNP})$`),
      new RegExp(`^(.+)$`)
    ],
    argIsColor: [true, false]
  }, {
    name: 'betterContrast',
    args: [
      new RegExp(`^(.+)${vSep}(${Couleur.vNum})${vSep}(${Couleur.vNum})$`),
      new RegExp(`^(.+)${vSep}(${Couleur.vNum})$`)
    ],
    argIsColor: [true, false, false]
  }, {
    name: 'contrast',
    args: new RegExp(`^(.+)$`),
    argIsColor: [true]
  }
];

const methodesSimples = methodes.filter(method => !(method.argIsColor || []).reduce((sum, a) => sum + a, 0));
const methodesRecursives = methodes.filter(method => !!(method.argIsColor || []).reduce((sum, a) => sum + a, 0));

// Expression régulières pour détecter les méthodes supportées et leurs arguments
const regexps = {
  simple: new RegExp(`(.+)\\.(${ methodesSimples.map(method => method.name).join('|') })\\((.+)?\\)$`),
  recursive: new RegExp(`^((?:[^\\(\\)]+(?:\\(.+\\))?)+)\\.(${ methodesRecursives.map(method => method.name).join('|') })\\((.+)\\)$`)
};



///////////////////////////////////////////////////
// Calcule une couleur à partir du string en entrée
export function resolveColor(input) {
  // premCouleur sera égale à la première couleur entrée en input (de forme premCouleur.resteDeLInput),
  // elle commence égale à input et sera réduite si nécessaire par la boucle suivante
  let premCouleur = input;
  const methodesAppliquees = [];

  while (true) {
    // On vérifie si la valeur de l'input est de la forme couleur.methodeRecursive() ou couleur.methodeNonRecursive()
    const match = premCouleur.match(regexps.recursive) || premCouleur.match(regexps.simple);

    // Si la valeur de l'input ne vérifie couleur.methode() pour aucune methode de acceptedMethods,
    // on passe à l'étape suivante (vérifier si la valeur de l'input est une expression valide de couleur)
    if (match === null) break;

    // On détermine quelle méthode est appliquée à la couleur
    const k = methodesRecursives.findIndex(m => m.name == match[2]);
    const method = (k > -1) ? methodesRecursives[k]
                            : methodesSimples[methodesSimples.findIndex(m => m.name == match[2])];

    // On détermine les arguments passés à la méthode
    let args = [];
    for (const regex of [method.args].flat()) {
      const temp = (match[3] || '').match(regex);
      if (temp == null) continue;
      args = [...(temp || [])].slice(1);
      break;
    }

    // On ajoute cette méthode (et ses arguments) à la liste de méthodes appliquées à la couleur
    // (on recrée l'objet méthode plutôt que de le modifier, sinon on corrompt methodes)
    methodesAppliquees.push({
      name: method.name,
      args: args,
      argIsColor: method.argIsColor || Array(args.length).fill(false),
      resultIsValue: method.resultIsValue
    });

    // On récupère le reste de l'input pour le tester (boucle)
    premCouleur = match[1];
  }

  // Si la valeur restante de l'input est une expression valide de couleur, on pourra continuer.
  // Sinon, la valeur est invalide.
  try {
    try { premCouleur = new Couleur(premCouleur); }
    catch (error) { throw 'ignore'; }

    // On se prépare à appliquer les méthodes dans l'ordre à premCouleur
    methodesAppliquees.reverse();
    let couleur = premCouleur;
    // On boucle sur les méthodes pour les appliquer successivement à premCouleur
    for (const method of methodesAppliquees) {
      // On boucle sur les arguments de la méthode pour les résoudre si ce sont des couleurs
      for (const [i, arg] of method.args.entries()) {
        if (!!(method.argIsColor || [])[i]) {
          try           { method.args[i] = resolveColor(arg); }
          catch (error) { throw 'Couleur en argument invalide'; }
        }
      }

      // On applique la méthode (avec ses arguments résolus)
      couleur = Couleur.prototype[method.name]
                       .call(couleur, ...method.args.map(arg => arg === 'true' ? true : arg === 'false' ? false : arg));
      if (!couleur instanceof Couleur) break;
    }

    // On a notre résultat : la couleur après application de toutes les méthodes !!
    return couleur;
  }
  catch (error) {
    if (error != 'ignore') console.error(error);
    return null;
  }
}