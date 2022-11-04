// Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
const numberExp = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';
export const unitRegExps = {
  number: numberExp,
  percentage: numberExp + '%',
  numberOrPercentage: numberExp + '%?',
  angle: numberExp + '(?:deg|grad|rad|turn)?'
};



export interface CSSFormat {
  id: string,
  syntaxes: RegExp[]
};

export const allFormats: CSSFormat[] = [
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
      new RegExp(`^rgba?\\((${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.number})\\)$`),
      // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.number}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(100%, 100%, 100%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage})\\)$`),
      // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(255 255 255)
      new RegExp(`^rgba?\\((${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
      new RegExp(`^rgba?\\((${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`),
      // rgb(100% 100% 100%)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
      new RegExp(`^rgba?\\((${unitRegExps.percentage}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hsl',
    syntaxes: [
      // hsl(<angle>, 100%, 100%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage})\\)$`),
      // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}), ?(${unitRegExps.percentage}), ?(${unitRegExps.percentage}), ?(${unitRegExps.numberOrPercentage})\\)$`),
      // hsl(<angle> 100% 100%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hsla?\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hwb',
    syntaxes: [
      // hwb(<angle> 100% 100%)
      new RegExp(`^hwb\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage})\\)$`),
      // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hwb\\((${unitRegExps.angle}) (${unitRegExps.percentage}) (${unitRegExps.percentage}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lab',
    syntaxes: [
      // lab(300% 25 40)
      new RegExp(`^lab\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // lab(300% 25 40 / .5)
      new RegExp(`^lab\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lch',
    syntaxes: [
      // lch(300% 25 <angle>)
      new RegExp(`^lch\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.angle})\\)$`),
      // lch(300% 25 <angle> / .5)
      new RegExp(`^lch\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.angle}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'oklab',
    syntaxes: [
      // oklab(50% -25 40)
      new RegExp(`^oklab\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // oklab(50% -25 40 / .5)
      new RegExp(`^oklab\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'oklch',
    syntaxes: [
      // oklch(50% 25 <angle>)
      new RegExp(`^oklch\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.angle})\\)$`),
      // oklch(50% 25 <angle> / .5)
      new RegExp(`^oklch\\((${unitRegExps.percentage}) (${unitRegExps.number}) (${unitRegExps.angle}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'color',
    syntaxes: [
      // color(display-p3 -0.6112 1.0079 -0.2192)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number})\\)$`),
      // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${unitRegExps.number}) (${unitRegExps.number}) (${unitRegExps.number}) ?\\/ ?(${unitRegExps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'name',
    syntaxes: [
      // white or WHITE or WhiTe
      /^[A-Za-z]+$/
    ]
  }
];