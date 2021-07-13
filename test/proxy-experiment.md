RESULT: Caching values works, but it's slower than recalculating them each time anyway.

---

Add this at the end of the constructor to enable a caching system of computed values via Proxy:

```javascript
    return new Proxy(this, {
      get: function(target, prop, receiver) {
        const proxy = this;
        if (typeof proxy.cache === 'undefined') proxy.resetCache(); // init cache
    
        const intercept = ['valuesTo'];
        if (typeof target[prop] === 'function') {
          return function(...args) {
            if (intercept.includes(prop)) {
              if (prop === 'valuesTo') {
                const spaceID = typeof args[0] === 'string' ? args[0] : typeof args[0].id !== 'undefined' ? args[0].id : null;
                const shouldCache = spaceID != null && !['rgb', 'srgb'].includes(spaceID);
                const cachedValues = proxy.cache.values[spaceID];
                if (shouldCache) {
                  if (cachedValues !== null) {
                    //console.log(`[proxy] Returning ${spaceID} values from cache`, cachedValues);
                    return cachedValues;
                  } else {
                    const values = Reflect.get(target, prop, receiver).apply(this, args);
                    //console.log(`[proxy] Adding ${spaceID} values to cache`, values);
                    proxy.cache.values[spaceID] = values;
                    return values;
                  }
                }
              }
            }
            return Reflect.get(target, prop, receiver).apply(this, args);
          }
        } else {
          return Reflect.get(target, prop, receiver);
        }
      },
    
      set: function(target, prop, value, receiver) {
        const proxy = this;
        const intercept = ['r', 'g', 'b', 'a'];
        if (intercept.includes(prop)) {
          proxy.resetCache();
        }
        Reflect.set(target, prop, value, receiver);
        return true;
      },

      resetCache: function() {
        const proxy = this;
        proxy.cache = {
          values: {}
        };
        for (const space of Couleur.colorSpaces) {
          proxy.cache.values[space.id] = null;
        }
      },
    });
```