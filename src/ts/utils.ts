/** Pads a string of length 1 with a zero. */
export function pad(s: string): string { return (s.length < 2) ? `0${s}` : s; }

/** Brings an angle in degrees to [0, 360]. */
export function angleToRange(angle: number): number {
  let h = angle;
  while (h < 0)   h += 360;
  while (h > 360) h -= 360;
  return h;
}

/** Returns a float precise to the nth decimal. */
export function pRound(number: number, precision: number = 5): number {
  let x = (typeof number === 'number') ? number : Number(number);
  return Number(parseFloat(x.toPrecision(precision)));
}