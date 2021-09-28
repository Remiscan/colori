const Utils = {
  /** Pads a string of length 1 with a zero. */
  pad: function(s) { return (s.length < 2) ? `0${s}` : s; },

  /** Brings an angle in degrees to [0, 360]. */
  angleToRange: function(angle) {
    let h = angle;
    while (h < 0)   h += 360;
    while (h > 360) h -= 360;
    return h;
  },

  /** Returns a float precise to the nth decimal. */
  pRound: function(number, precision = 5) {
    let x = (typeof number === 'number') ? number : Number(number);
    return Number(parseFloat(x.toPrecision(precision)));
  },
};



export default Utils;