import { default as Colour, default as Couleur } from 'colori';



function colorToObject(color) {
  return {
    r: color.r,
    g: color.g,
    b: color.b,
    a: color.a
  };
}

export default class Test {
  static tolerance = .03;
  static okJND = .02;

  constructor(func = null, expectedResult = {}, order = 0) {
    this.function = func;
    this.expectedResult = expectedResult;
    this.actualResult = null;
    this.order = order;
    this.tested = false;
    this.time = null;
  }

  get result() {
    const time = performance.now(); // in case the inner test fails
    try {
      if (!this.tested) {
        const time = performance.now();
        this.actualResult = eval(this.function);
        this.time = performance.now() - time;
        this.tested = true;
      }
      return this.actualResult;
    }
    catch (error) {
      this.time = performance.now() - time;
      return ['Error', error.message];
    }
  }

  get name() {
    return this.function;
  }


  // Checks if the test results fit the expected results
  validate() {
    let result = this.result;
    const isError = (typeof result === 'object' && result !== null && result[0] === 'Error');
    
    // If result is an error, check if we expected one
    if (isError === true)
      return this.expectedResult === 'Error';

    // If result is an array
    else if (Array.isArray(result)) {
      let res = false;
      try {
        // If the array contains colors / color strings, check if they're all the same
        res = result.every((co, k) => Colour.same(co, this.expectedResult[k], { method: 'deltaeok', tolerance: Test.okJND }));
      } catch (error) {
        // If not, just compare them
        res = result.every((e, k) => e === this.expectedResult[k]);
      }
      return res;
    }
    
    // If expected result is an object, check if it has the same properties and values as the result
    else if (typeof this.expectedResult === 'object' && this.expectedResult !== null)
      return Test.sameColorObject(result, this.expectedResult);
    
    // If expected result is a number, check if it's close enough to the result
    else if (typeof this.expectedResult === 'number') {
      if (Math.abs(result - this.expectedResult) > Test.tolerance) return false;
      else return true;
    }

    // Else, try to make colors from the result and expected result and check if they're the same
    else {
      let res = false;
      try { res = Colour.same(result, this.expectedResult, { method: 'deltaeok', tolerance: Test.okJND }); }
      catch (error) { res = result === this.expectedResult; }
      return res;
    }
  }


  // Checks if two objects with a similar structure to a Colour are the same
  static sameColorObject(couleur1, couleur2) {
    const c1 = colorToObject(couleur1);
    const c2 = colorToObject(couleur2);
    return Couleur.same(c1, c2, { method: 'deltaeok', tolerance: Test.okJND });
  }


  // Display the results of the test on the page
  populate() {
    const validation = this.validate();
    const result = this.result;
    let displayedResult = result;
    if (result instanceof Couleur) displayedResult = colorToObject(result);
    else if (Array.isArray(result) && result.every(v => v instanceof Couleur)) displayedResult = result.map(v => colorToObject(v));
    
    // Container
    const div = document.createElement('div');
    div.classList.add('js');
    div.classList.add(validation ? 'yes' : 'no');
    div.style.setProperty('grid-row', this.order);

    // Title background color (= visual test results)
    let backgroundColor = '';
    let textColor= '', gradient = '';
    try {
      if (Array.isArray(result)) {
        if (result.length > 1) gradient = `linear-gradient(to right, ${result.map(c => (new Colour(c)).rgb).join(', ')})`;
        if (result.length > 0) backgroundColor = new Colour(result[0]);
      } else {
        backgroundColor = new Colour(result);
      }
    } catch(e) {}
    
    textColor = (backgroundColor instanceof Colour) ? (
                  Colour.blend('white', backgroundColor).bestColorScheme('background') == 'light' ? 'black' : 'white'
                ) : 'initial';
    backgroundColor = (backgroundColor instanceof Colour) ? backgroundColor.rgb : backgroundColor;

    div.innerHTML = `
      <a id="js-${this.order}"></a>
      <h4 class="js" style="--color:${backgroundColor || ''}; ${gradient ? `--gradient:${gradient}` : ''}; color:${textColor};">${this.name}</h4>
      <span class="js">${validation ? '✅ Success' : '❌ Failure'} in ${this.time} ms</span>
      <pre class="js">${'Result:\n\n' + JSON.stringify(displayedResult, null, 2)}</pre>
      <pre class="js">${'Expected:\n\n' + JSON.stringify(this.expectedResult, null, 2)}</pre>
    `;
    
    document.body.appendChild(div);

    return validation;
  }
}