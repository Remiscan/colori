// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import Colour from '../colori.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const tolerance = .03;

export default class Test {
  constructor(fonction = null, resultatAttendu = {}, ordre = 0) {
    this.fonction = fonction;
    this.resultatAttendu = resultatAttendu;
    this.ordre = ordre;
  }

  get resultat() {
    try {
      if (typeof this.resultatReel === 'undefined')
        this.resultatReel = eval(this.fonction);
      return this.resultatReel;
    }
    catch(error) {
      return ['Error', error];
    }
  }

  get nom() {
    return this.fonction;
  }


  // Checks if the test results fit the expected results
  validate() {
    const resultat = (typeof this.resultat === 'object' && this.resultat !== null && this.resultat[0] === 'Error') ? this.resultat[0]
                                                                                                                : this.resultat;
    
    // If result is an error, check if we expected one
    if (resultat === 'Error')
      return this.resultatAttendu === 'Error';

    // If result is an array of colors, check if they're all the same
    else if (Array.isArray(resultat))
      return resultat.every((co, k) => Colour.same(co, this.resultatAttendu[k]));
    
    // If expected result is an object, check if it has the same properties and values as the result
    else if (typeof this.resultatAttendu === 'object' && this.resultatAttendu !== null)
      return Test.sameColorObject(resultat, this.resultatAttendu);
    
    // If expected result is a number, check if it's close enough to the result
    else if (typeof this.resultatAttendu === 'number') {
      if (Math.abs(resultat - this.resultatAttendu) > tolerance) return false;
      else return true;
    }

    // Else, try to make colors from the result and expected result and check if they're the same
    else {
      let tempResult;
      try { tempResult = Colour.same(resultat, this.resultatAttendu); }
      catch (error) { }
      return tempResult || resultat === this.resultatAttendu;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static sameColorObject(couleur1, couleur2) {
    var c1Props = Object.getOwnPropertyNames(couleur1);
    var c2Props = Object.getOwnPropertyNames(couleur2);
  
    if (c1Props.length != c2Props.length) return false;
  
    for (var i = 0; i < c1Props.length; i++) {
      var prop = c1Props[i];
      if (!['r', 'g', 'b'].includes(prop)) continue;
      if (Math.abs(couleur1[prop] - couleur2[prop]) > tolerance) return false;
    }
  
    return true;
  }


  // Display the results of the test on the page
  populate() {
    const validation = this.validate();
    const resultat = this.resultat;
    
    // Container
    const div = document.createElement('div');
    div.classList.add('js');
    div.classList.add(validation ? 'yes' : 'no');
    div.style.setProperty('grid-row', this.ordre + 2);

    // Title that displayed the tested function
    const h3 = document.createElement('h3');
    h3.classList.add('js');
    h3.innerHTML = this.nom;

    // Title background color (= visual test results)
    let backgroundColor;
    try {
      if (resultat instanceof Colour) backgroundColor = resultat;
      else if (Array.isArray(resultat)) {
        const gradient = `linear-gradient(to right, ${resultat.map(c => (new Colour(c)).rgb).join(', ')})`;
        h3.style.setProperty('background-image', gradient);
        backgroundColor = new Colour(resultat[0]);
      }
      else backgroundColor = new Colour(this.resultatAttendu);
  
      h3.style.setProperty('background-color', backgroundColor.rgba || 'transparent');
      h3.style.setProperty('color', backgroundColor.name != 'transparent' ? backgroundColor.replace('a', 1).contrastedText() : 'black');
    }
    catch(error) { console.log(error); }
  
    // Boolean result of test validation
    const span = document.createElement('span');
    span.classList.add('js');
    span.innerHTML = validation ? '✅ Success' : '❌ Failure';

    // Display the test results
    const pre = document.createElement('pre');
    pre.classList.add('js');
    pre.innerHTML = 'Reçu :\n\n' + JSON.stringify(resultat, null, 2);

    // Displat the expected test results
    const pre2 = document.createElement('pre');
    pre2.innerHTML = 'Attendu :\n\n' + JSON.stringify(this.resultatAttendu, null, 2);
    pre2.classList.add('js');

    if (validation) pre2.style.display = 'none';
    else pre2.style.setProperty('color', 'red');
    
    div.appendChild(h3);
    div.appendChild(span);
    div.appendChild(pre);
    div.appendChild(pre2);
    document.body.appendChild(div);
  }
}