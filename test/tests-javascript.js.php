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
    this.tested = false;
    this.time = null;
  }

  get resultat() {
    const time = performance.now(); // in case the inner test fails
    try {
      if (!this.tested) {
        const time = performance.now();
        this.resultatReel = eval(this.fonction);
        this.time = performance.now() - time;
        this.tested = true;
      }
      return this.resultatReel;
    }
    catch (error) {
      if (this.resultatAttendu !== 'Error') console.error(error);
      this.time = performance.now() - time;
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

    // If result is an array
    else if (Array.isArray(resultat)) {
      let res = false;
      try {
        // If the array contains colors / color strings, check if they're all the same
        res = resultat.every((co, k) => Colour.same(co, this.resultatAttendu[k]));
      } catch (error) {
        // If not, just compare them
        res = resultat.every((e, k) => e === this.resultatAttendu[k]);
      }
      return res;
    }
    
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
      let res = false;
      try { res = Colour.same(resultat, this.resultatAttendu); }
      catch (error) { res = resultat === this.resultatAttendu; }
      return res;
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
    div.style.setProperty('grid-row', this.ordre + 3);

    // Title background color (= visual test results)
    let backgroundColor = '';
    let textColor= '', gradient = '';
    try {
      if (Array.isArray(resultat)) {
        gradient = `linear-gradient(to right, ${resultat.map(c => (new Colour(c)).rgb).join(', ')})`;
        backgroundColor = new Colour(resultat[0]);
      } else {
        backgroundColor = new Colour(resultat);
      }
    } catch(e) {}
    
    textColor = (backgroundColor instanceof Colour) ? Colour.blend('white', backgroundColor).contrastedText()
              : 'initial';
    backgroundColor = (backgroundColor instanceof Colour) ? backgroundColor.rgb : backgroundColor;

    div.innerHTML = `
      <h3 class="js" style="--color:${backgroundColor || ''}; --gradient:${gradient}; color:${textColor};">${this.nom}</h3>
      <span class="js">${validation ? '✅ Success' : '❌ Failure'} in ${this.time} ms</span>
      <pre class="js">${'Reçu :\n\n' + JSON.stringify(resultat, null, 2)}</pre>
      <pre class="js">${'Attendu :\n\n' + JSON.stringify(this.resultatAttendu, null, 2)}</pre>
    `;
    
    document.body.appendChild(div);
  }
}