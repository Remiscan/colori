// ▼ ES modules cache-busted grâce à PHP
/*<?php ob_start();?>*/

import { default as Colour, default as Couleur } from '../dist/colori.js';

/*<?php $imports = ob_get_clean();
require_once $_SERVER['DOCUMENT_ROOT'] . '/_common/php/versionize-files.php';
echo versionizeFiles($imports, __DIR__); ?>*/



const tolerance = .03;
const distancePrecise = 1;
const distanceProche = 5;

export default class Test {
  constructor(fonction = null, resultatAttendu = {}, ordre = 0) {
    this.fonction = fonction;
    this.resultatAttendu = resultatAttendu;
    this.resultatReel = null;
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
      this.time = performance.now() - time;
      return ['Error', error];
    }
  }

  get nom() {
    return this.fonction;
  }


  // Checks if the test results fit the expected results
  validate() {
    let resultat = this.resultat;
    const isError = (typeof resultat === 'object' && resultat !== null && resultat[0] === 'Error');
    
    // If result is an error, check if we expected one
    if (isError === true)
      return this.resultatAttendu === 'Error';

    // If result is an array
    else if (Array.isArray(resultat)) {
      let res = false;
      try {
        // If the array contains colors / color strings, check if they're all the same
        res = resultat.every((co, k) => Colour.same(co, this.resultatAttendu[k], distanceProche));
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
      try { res = Colour.same(resultat, this.resultatAttendu, distanceProche); }
      catch (error) { res = resultat === this.resultatAttendu; }
      return res;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static sameColorObject(couleur1, couleur2) {
    const c1 = [couleur1.r, couleur1.g, couleur1.b, couleur1.a];
    const c2 = [couleur2.r, couleur2.g, couleur2.b, couleur2.a];
    return Couleur.same(c1, c2, distanceProche);
  }


  // Display the results of the test on the page
  populate() {
    const validation = this.validate();
    const resultat = this.resultat;
    
    // Container
    const div = document.createElement('div');
    div.classList.add('js');
    div.classList.add(validation ? 'yes' : 'no');
    div.style.setProperty('grid-row', this.ordre);

    // Title background color (= visual test results)
    let backgroundColor = '';
    let textColor= '', gradient = '';
    try {
      if (Array.isArray(resultat)) {
        if (resultat.length > 1) gradient = `linear-gradient(to right, ${resultat.map(c => (new Colour(c)).rgb).join(', ')})`;
        if (resultat.length > 0) backgroundColor = new Colour(resultat[0]);
      } else {
        backgroundColor = new Colour(resultat);
      }
    } catch(e) {}
    
    textColor = (backgroundColor instanceof Colour) ? (
                  Colour.blend('white', backgroundColor).bestColorScheme('background') == 'light' ? 'black' : 'white'
                ) : 'initial';
    backgroundColor = (backgroundColor instanceof Colour) ? backgroundColor.rgb : backgroundColor;

    div.innerHTML = `
      <a id="js-${this.ordre}"></a>
      <h4 class="js" style="--color:${backgroundColor || ''}; --gradient:${gradient}; color:${textColor};">${this.nom}</h4>
      <span class="js">${validation ? '✅ Success' : '❌ Failure'} in ${this.time} ms</span>
      <pre class="js">${'Reçu :\n\n' + JSON.stringify(resultat, null, 2)}</pre>
      <pre class="js">${'Attendu :\n\n' + JSON.stringify(this.resultatAttendu, null, 2)}</pre>
    `;
    
    document.body.appendChild(div);

    return validation;
  }
}