/**
 * Computes the CIEDE2000 distance between two colors.
 * @param {number[]} - Array of parsed LAB values of the first color (i.e. l in [0, 1]).
 * @param {number[]} - Array of parsed LAB values of the second color (i.e. l in [0, 1]).
 * @returns {number} Distance between the cwo colors.
 */
export function CIEDE2000([l1, a1, b1]: number[], [l2, a2, b2]: number[]): number {
  // Source of the math: http://www2.ece.rochester.edu/~gsharma/ciede2000/ciede2000noteCRNA.pdf
  const L1 = 100 * l1, L2 = 100 * l2;
  const C1 = Math.sqrt(a1 ** 2 + b1 ** 2);
  const C2 = Math.sqrt(a2 ** 2 + b2 ** 2);

  const mC = (C1 + C2) / 2,
        G = 0.5 * (1 - Math.sqrt(mC ** 7 / (mC ** 7 + 25 ** 7))),
        aa1 = (1 + G) * a1,
        aa2 = (1 + G) * a2,
        CC1 = Math.sqrt(aa1 ** 2 + b1 ** 2),
        CC2 = Math.sqrt(aa2 ** 2 + b2 ** 2);
  let hh1 = CC1 === 0 ? 0 : Math.atan2(b1, aa1) * 180 / Math.PI,
      hh2 = CC2 === 0 ? 0 : Math.atan2(b2, aa2) * 180 / Math.PI;
  while (hh1 < 0) hh1 += 360; while (hh1 > 360) hh1 -= 360;
  while (hh2 < 0) hh2 += 360; while (hh2 > 360) hh2 -= 360;

  const dL = L2 - L1,
        dC = CC2 - CC1;
  const dhh = (CC1 * CC2 === 0) ? 0
            : (Math.abs(hh2 - hh1) <= 180) ? hh2 - hh1
            : (hh2 - hh1 > 180) ? hh2 - hh1 - 360
            : hh2 - hh1 + 360;
  const dH = 2 * Math.sqrt(CC1 * CC2) * Math.sin((Math.PI / 180) * (dhh / 2));

  const mL = (L1 + L2) / 2,
        mCC = (CC1 + CC2) / 2;
  const mhh = (CC1 * CC2 === 0) ? hh1 + hh2
            : (Math.abs(hh2 - hh1) <= 180) ? (hh1 + hh2) / 2
            : (hh1 + hh2 >= 360) ? (hh1 + hh2 - 360) / 2
            : (hh1 + hh2 + 360) / 2;
  const T = 1 - 0.17 * Math.cos((Math.PI / 180) * (mhh - 30))
              + 0.24 * Math.cos((Math.PI / 180) * (2 * mhh))
              + 0.32 * Math.cos((Math.PI / 180) * (3 * mhh + 6))
              - 0.20 * Math.cos((Math.PI / 180) * (4 * mhh - 63)),
        dTH = 30 * Math.exp(-1 * ((mhh - 275) / 25) ** 2),
        RC = 2 * Math.sqrt(mCC ** 7 / (mCC ** 7 + 25 ** 7)),
        SL = 1 + (0.015 * (mL - 50) ** 2) / Math.sqrt(20 + (mL - 50) ** 2),
        SC = 1 + 0.045 * mCC,
        SH = 1 + 0.015 * mCC * T,
        RT = -1 * Math.sin((Math.PI / 180) * (2 * dTH)) * RC;

  return Math.sqrt(
    (dL / SL) ** 2
    + (dC / SC) ** 2
    + (dH / SH) ** 2
    + RT * (dC / SC) * (dH / SH)
  );
}