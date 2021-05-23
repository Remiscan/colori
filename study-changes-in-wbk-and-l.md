If I change lightness by 5%, what's the effect on whiteness and blackness ?

l

v = l + s + Math.min(l, 1 - l);

if min = l
  v = l + s * l
  if (v == 0)
    _s = 0
    w = (1 - _s) * v
      = 1 * v
      = l + s * l !!
      = l * (1 + s)
    bk = 1 - v
       = 1 - l - s * l !!
       = 1 - l * (1 + s)
  if (v != 0)
    _s = 2 - 2 * l / v
    w = (1 - _s) * v
      = (1 - 2 + 2l / v) * v
      = v - 2v + 2l
      = 2l - v
      = 2l - l - s * l
      = l * (2 - 1 - s)
      = l * (1 - s) !!
    bk = 1 - v
       = 1 - l * (1 - s)
       = 1 + l * (s - 1) !!
if min = 1 - l
  v = l + s * (1 - l)
    = l + s - s * l
    = l * (1 - s) + s
  if (v == 0)
    _s = 0
    w = (1 - _s) * v
      = 1 * v
      = l * (1 - s) + s !!
    bk = 1 - v
       = 1 - s + l * (1 - s)
       = (l + 1) * (1 - s) !!
  if (v != 0)
    _s = 2 - 2 * l / v
    w = (1 - _s) * v
      = v - (_s * v)
      = v - (2v - 2l)
      = 2l - v
      = 2l - l*(1 - s) - s
      = 2l - l + ls - s
      = l + ls - s
    bk = 1 - v
       = (l + 1) * (1 - s) !!

si L = l + x
alors
if min = l
  if v = 0
    W = L * (1 + s)
      = (l + x) * (1 + s)
      = l * (1 + s) + x * (1 + s)
      = w + x * (1 + s)
    BK = 1 - L * (1 + s)
       = 1 - (l + x) * (1 + s)
       = 1 - l * (1 + s) - x * (1 + s)
       = bk - x * (1 + s)
    => AUGMENTER l de x : AUGMENTE w de x(1 + s)
                        : DIMINUE bk de x(1 + s)

  if v != 0
    W = L * (1 - s)
      = (l + x) * (1 - s)
      = l * (1 - s) + x * (1 - s)
      = w + x * (1 - s)
    BK = 1 - L * (1 - s)
       = 1 - (l + x) * (1 - s)
       = 1 - l * (1 - s) - x * (1 - s)
       = bk - x * (1 - s)
    => AUGMENTER l de x : AUGMENTE w de x(1 - s)
                          DIMINUE bk de x(1 - s)

if min = 1 - l
  if v = 0
    W = L * (1 - s) + s
      = (l + x) * (1 - s) + s
      = l * (1 - s) + s + x * (1 - s)
      = w + x(1 - s)
    BK = (L + 1) * (1 - s)
       = (l + x + 1) * (1 - s)
       = bk + x * (1 - s)
    => AUGMENTER l de x : AUGMENTE w de x(1 - s)
                          AUGMENTE bk de x(1 - s)
  
  if (v != 0)
    W = L + Ls - s
      = (l + x) + (l + x)s - s
      = l + ls - s + x + xs
      = w + x(1 + s)
    BK = (L + 1) * (1 - s)
       = bk + x * (1 - s)
    => AUGMENTER l de x : AUGMENTE w de x(1 + s)
                          AUGMENTE bk de x(1 + s)