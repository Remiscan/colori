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

/** Transforms a value into an unparsed alpha value. */
export function toUnparsedAlpha(val: string | number, def: string = '1'): string {
  return !!val ? String(val) : (val === 0) ? '0' : def;
}

/** Casts numbers in [0, 1] to [00, ff]. */
export function toHex(rgba: number[]): string[] {
  return rgba.map(v => pad(Math.round(v * 255).toString(16)));
}

/** Casts values in [00, ff] to [0, 1]. */
export function fromHex(hexa: string[]): number[] {
  return hexa.map(v => v.length === 1 ? v.repeat(2) : v)
             .map(v => parseInt(v, 16))
             .map(v => v / 255);
}