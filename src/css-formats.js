// Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
const numberExp = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';
const RegExps = {
  number: numberExp,
  percentage: numberExp + '%',
  numberOrPercentage: numberExp + '%?',
  angle: numberExp + '(?:deg|grad|rad|turn)?'
};



const CSSformats = [
  {
    id: 'hex',
    syntaxes: [
      // #abc or #ABC
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #abcd or #ABCD
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #aabbcc or #AABBCC
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/,
      // #aabbccdd or #AABBCCDD
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
    ]
  }, {
    id: 'rgb',
    syntaxes: [
      // rgb(255, 255, 255) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number})\\)$`),
      // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.number}), ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(100%, 100%, 100%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(255 255 255)
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
      new RegExp(`^rgba?\\((${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`),
      // rgb(100% 100% 100%)
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
      new RegExp(`^rgba?\\((${RegExps.percentage}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hsl',
    syntaxes: [
      // hsl(<angle>, 100%, 100%)
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage})\\)$`),
      // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
      new RegExp(`^hsla?\\((${RegExps.angle}), ?(${RegExps.percentage}), ?(${RegExps.percentage}), ?(${RegExps.numberOrPercentage})\\)$`),
      // hsl(<angle> 100% 100%)
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hsla?\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hwb',
    syntaxes: [
      // hwb(<angle> 100% 100%)
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage})\\)$`),
      // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hwb\\((${RegExps.angle}) (${RegExps.percentage}) (${RegExps.percentage}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lab',
    syntaxes: [
      // lab(300% 25 40)
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // lab(300% 25 40 / .5)
      new RegExp(`^lab\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lch',
    syntaxes: [
      // lch(300% 25 <angle>)
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle})\\)$`),
      // lch(300% 25 <angle> / .5)
      new RegExp(`^lch\\((${RegExps.percentage}) (${RegExps.number}) (${RegExps.angle}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'color',
    syntaxes: [
      // color(display-p3 -0.6112 1.0079 -0.2192)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number})\\)$`),
      // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${RegExps.number}) (${RegExps.number}) (${RegExps.number}) ?\\/ ?(${RegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'name',
    syntaxes: [
      // white or WHITE or WhiTe
      /^[A-Za-z]+$/
    ]
  }
];



export { RegExps, CSSformats };