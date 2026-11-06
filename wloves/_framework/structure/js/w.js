// Version: 0.0.1 wbFn for developer
const wbFn = () => {
  var arr = [];
  var reqEvent = [];

  var slice = arr.slice;

  var flat = arr.flat
    ? function (array) {
        return arr.flat.call(array);
      }
    : function (array) {
        return arr.concat.apply([], array);
      };

  var push = arr.push;

  var indexOf = arr.indexOf;

  var class2type = {};

  var toString = class2type.toString;

  var support = {};

  var isFunction = function isFunction(obj) {
    return (
      typeof obj === "function" &&
      typeof obj.nodeType !== "number" &&
      typeof obj.item !== "function"
    );
  };

  var isWindow = function isWindow(obj) {
    return obj != null && obj === obj.window;
  };

  var document = window.document;

  function toType(obj) {
    if (obj == null) {
      return obj + "";
    }

    return typeof obj === "object" || typeof obj === "function"
      ? class2type[toString.call(obj)] || "object"
      : typeof obj;
  }

  // w query
  var version = "0.0.1",
    w = function (selector, context) {
      // The w object is actually just the init constructor 'enhanced'
      // Need init if w is called (just allow error to be thrown if not included)
      return new w.fn.init(selector, context);
    };

  w.ready = function (fn) {
    if (
      document.readyState === "complete" ||
      document.readyState !== "loading"
    ) {
      w.requestEvent();
    } else {
      document.addEventListener("DOMContentLoaded", fn);
    }
  };

  w.fn = w.prototype = {
    // The current version of w being used
    wv: version,

    constructor: w,

    // The default length of a w object is 0
    length: 0,

    toArray: function () {
      return slice.call(this);
    },

    // Get the Nth element in the matched element set OR
    // Get the whole matched element set as a clean array
    get: function (num) {
      // Return all the elements in a clean array
      if (num == null) {
        return slice.call(this);
      }

      // Return just the one element from the set
      return num < 0 ? this[num + this.length] : this[num];
    },

    // Take an array of elements and push it onto the stack
    // (returning the new matched element set)
    pushStack: function (elems) {
      // Build a new w matched element set
      var ret = w.merge(this.constructor(), elems);

      // Add the old object onto the stack (as a reference)
      ret.prevObject = this;

      // Return the newly-formed element set
      return ret;
    },

    // Execute a callback for every element in the matched set.
    each: function (callback) {
      return w.each(this, callback);
    },

    map: function (callback) {
      return this.pushStack(
        w.map(this, function (elem, i) {
          return callback.call(elem, i, elem);
        })
      );
    },

    slice: function () {
      return this.pushStack(slice.apply(this, arguments));
    },

    first: function () {
      return this.eq(0);
    },

    last: function () {
      return this.eq(-1);
    },

    even: function () {
      return this.pushStack(
        w.grep(this, function (_elem, i) {
          return (i + 1) % 2;
        })
      );
    },

    odd: function () {
      return this.pushStack(
        w.grep(this, function (_elem, i) {
          return i % 2;
        })
      );
    },

    eq: function (i) {
      var len = this.length,
        j = +i + (i < 0 ? len : 0);
      return this.pushStack(j >= 0 && j < len ? [this[j]] : []);
    },

    end: function () {
      return this.prevObject || this.constructor();
    },

    // For internal use only.
    // Behaves like an Array's method, not like a w method.
    push: push,
    sort: arr.sort,
    splice: arr.splice,
  };

  w.extend = w.fn.extend = function () {
    var options,
      name,
      src,
      copy,
      copyIsArray,
      clone,
      target = arguments[0] || {},
      i = 1,
      length = arguments.length,
      deep = false;

    // Handle a deep copy situation
    if (typeof target === "boolean") {
      deep = target;

      // Skip the boolean and the target
      target = arguments[i] || {};
      i++;
    }

    // Handle case when target is a string or something (possible in deep copy)
    if (typeof target !== "object" && !isFunction(target)) {
      target = {};
    }

    // Extend w itself if only one argument is passed
    if (i === length) {
      target = this;
      i--;
    }

    for (; i < length; i++) {
      // Only deal with non-null/undefined values
      if ((options = arguments[i]) != null) {
        // Extend the base object
        for (name in options) {
          copy = options[name];

          // Prevent Object.prototype pollution
          // Prevent never-ending loop
          if (name === "__proto__" || target === copy) {
            continue;
          }

          // Recurse if we're merging plain objects or arrays
          if (
            deep &&
            copy &&
            (w.isPlainObject(copy) || (copyIsArray = Array.isArray(copy)))
          ) {
            src = target[name];

            // Ensure proper type for the source value
            if (copyIsArray && !Array.isArray(src)) {
              clone = [];
            } else if (!copyIsArray && !w.isPlainObject(src)) {
              clone = {};
            } else {
              clone = src;
            }
            copyIsArray = false;

            // Never move original objects, clone them
            target[name] = w.extend(deep, clone, copy);

            // Don't bring in undefined values
          } else if (copy !== undefined) {
            target[name] = copy;
          }
        }
      }
    }

    // Return the modified object
    return target;
  };

  w.extend({
    // Unique for each copy of w on the page
    expando: "w" + (version + Math.random()).replace(/\D/g, ""),

    // Assume w is ready without the ready module
    isReady: true,

    each: function (obj, callback) {
      var length,
        i = 0;

      if (isArrayLike(obj)) {
        length = obj.length;
        for (; i < length; i++) {
          if (callback.call(obj[i], i, obj[i]) === false) {
            break;
          }
        }
      } else {
        for (i in obj) {
          if (callback.call(obj[i], i, obj[i]) === false) {
            break;
          }
        }
      }

      return obj;
    },

    // results is for internal usage only
    makeArray: function (arr, results) {
      var ret = results || [];

      if (arr != null) {
        if (isArrayLike(Object(arr))) {
          w.merge(ret, typeof arr === "string" ? [arr] : arr);
        } else {
          push.call(ret, arr);
        }
      }

      return ret;
    },

    inArray: function (elem, arr, i) {
      return arr == null ? -1 : indexOf.call(arr, elem, i);
    },

    merge: function (first, second) {
      var len = +second.length,
        j = 0,
        i = first.length;

      for (; j < len; j++) {
        first[i++] = second[j];
      }

      first.length = i;

      return first;
    },

    grep: function (elems, callback, invert) {
      var callbackInverse,
        matches = [],
        i = 0,
        length = elems.length,
        callbackExpect = !invert;

      // Go through the array, only saving the items
      for (; i < length; i++) {
        callbackInverse = !callback(elems[i], i);
        if (callbackInverse !== callbackExpect) {
          matches.push(elems[i]);
        }
      }

      return matches;
    },

    // arg is for internal usage only
    map: function (elems, callback, arg) {
      var length,
        value,
        i = 0,
        ret = [];

      // Go through the array, translating each of the items to their new values
      if (isArrayLike(elems)) {
        length = elems.length;
        for (; i < length; i++) {
          value = callback(elems[i], i, arg);

          if (value != null) {
            ret.push(value);
          }
        }

        // Go through every key on the object,
      } else {
        for (i in elems) {
          value = callback(elems[i], i, arg);

          if (value != null) {
            ret.push(value);
          }
        }
      }

      // Flatten any nested arrays
      return flat(ret);
    },

    // A global GUID counter for objects
    guid: 1,

    // w.support is not used in Core but other projects attach their
    // properties to it so it needs to exist.
    support: support,
  });

  if (typeof Symbol === "function") {
    w.fn[Symbol.iterator] = arr[Symbol.iterator];
  }

  // Populate the class2type map
  w.each(
    "Boolean Number String Function Array Date RegExp Object Error Symbol".split(
      " "
    ),
    function (_i, name) {
      class2type["[object " + name + "]"] = name.toLowerCase();
    }
  );

  function isArrayLike(obj) {
    var length = !!obj && "length" in obj && obj.length,
      type = toType(obj);

    if (isFunction(obj) || isWindow(obj)) {
      return false;
    }

    return (
      type === "array" ||
      length === 0 ||
      (typeof length === "number" && length > 0 && length - 1 in obj)
    );
  }
  var Sizzle = (function (window) {
    var i,
      support,
      Expr,
      getText,
      isXML,
      tokenize,
      compile,
      select,
      sortInput,
      hasDuplicate,
      // Local document vars
      setDocument,
      document,
      docElem,
      documentIsHTML,
      rbuggyQSA,
      rbuggyMatches,
      matches,
      contains,
      // Instance-specific data
      expando = "sizzle" + 1 * new Date(),
      preferredDoc = window.document,
      dirruns = 0,
      done = 0,
      classCache = createCache(),
      tokenCache = createCache(),
      compilerCache = createCache(),
      nonnativeSelectorCache = createCache(),
      sortOrder = function (a, b) {
        if (a === b) {
          hasDuplicate = true;
        }
        return 0;
      },
      // Instance methods
      hasOwn = {}.hasOwnProperty,
      arr = [],
      pushNative = arr.push,
      push = arr.push,
      slice = arr.slice,
      // Use a stripped-down indexOf as it's faster than native
      // https://jsperf.com/thor-indexof-vs-for/5
      indexOf = function (list, elem) {
        var i = 0,
          len = list.length;
        for (; i < len; i++) {
          if (list[i] === elem) {
            return i;
          }
        }
        return -1;
      },
      booleans =
        "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|" +
        "ismap|loop|multiple|open|readonly|required|scoped",
      // Regular expressions

      // http://www.w3.org/TR/css3-selectors/#whitespace
      whitespace = "[\\x20\\t\\r\\n\\f]",
      // https://www.w3.org/TR/css-syntax-3/#ident-token-diagram
      identifier =
        "(?:\\\\[\\da-fA-F]{1,6}" +
        whitespace +
        "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",
      // Attribute selectors: http://www.w3.org/TR/selectors/#attribute-selectors
      attributes =
        "\\[" +
        whitespace +
        "*(" +
        identifier +
        ")(?:" +
        whitespace +
        // Operator (capture 2)
        "*([*^$|!~]?=)" +
        whitespace +
        // "Attribute values must be CSS identifiers [capture 5]
        // or strings [capture 3 or capture 4]"
        "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" +
        identifier +
        "))|)" +
        whitespace +
        "*\\]",
      pseudos =
        ":(" +
        identifier +
        ")(?:\\((" +
        // To reduce the number of selectors needing tokenize in the preFilter, prefer arguments:
        // 1. quoted (capture 3; capture 4 or capture 5)
        "('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" +
        // 2. simple (capture 6)
        "((?:\\\\.|[^\\\\()[\\]]|" +
        attributes +
        ")*)|" +
        // 3. anything else (capture 2)
        ".*" +
        ")\\)|)",
      // Leading and non-escaped trailing whitespace, capturing some non-whitespace characters preceding the latter
      rwhitespace = new RegExp(whitespace + "+", "g"),
      rtrim = new RegExp(
        "^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + whitespace + "+$",
        "g"
      ),
      rcomma = new RegExp("^" + whitespace + "*," + whitespace + "*"),
      rcombinators = new RegExp(
        "^" + whitespace + "*([>+~]|" + whitespace + ")" + whitespace + "*"
      ),
      rdescend = new RegExp(whitespace + "|>"),
      rpseudo = new RegExp(pseudos),
      ridentifier = new RegExp("^" + identifier + "$"),
      matchExpr = {
        ID: new RegExp("^#(" + identifier + ")"),
        CLASS: new RegExp("^\\.(" + identifier + ")"),
        TAG: new RegExp("^(" + identifier + "|[*])"),
        ATTR: new RegExp("^" + attributes),
        PSEUDO: new RegExp("^" + pseudos),
        CHILD: new RegExp(
          "^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" +
            whitespace +
            "*(even|odd|(([+-]|)(\\d*)n|)" +
            whitespace +
            "*(?:([+-]|)" +
            whitespace +
            "*(\\d+)|))" +
            whitespace +
            "*\\)|)",
          "i"
        ),
        bool: new RegExp("^(?:" + booleans + ")$", "i"),

        // For use in libraries implementing .is()
        // We use this for POS matching in `select`
        needsContext: new RegExp(
          "^" +
            whitespace +
            "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" +
            whitespace +
            "*((?:-\\d)?\\d*)" +
            whitespace +
            "*\\)|)(?=[^-]|$)",
          "i"
        ),
      },
      rhtml = /HTML$/i,
      rinputs = /^(?:input|select|textarea|button)$/i,
      rheader = /^h\d$/i,
      rnative = /^[^{]+\{\s*\[native \w/,
      // Easily-parseable/retrievable ID or TAG or CLASS selectors
      rquickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
      rsibling = /[+~]/,
      // CSS escapes
      // http://www.w3.org/TR/CSS21/syndata.html#escaped-characters
      runescape = new RegExp(
        "\\\\[\\da-fA-F]{1,6}" + whitespace + "?|\\\\([^\\r\\n\\f])",
        "g"
      ),
      funescape = function (escape, nonHex) {
        var high = "0x" + escape.slice(1) - 0x10000;

        return nonHex
          ? // Strip the backslash prefix from a non-hex escape sequence
            nonHex
          : // Replace a hexadecimal escape sequence with the encoded Unicode code point
          // Support: IE <=11+
          // For values outside the Basic Multilingual Plane (BMP), manually construct a
          // surrogate pair
          high < 0
          ? String.fromCharCode(high + 0x10000)
          : String.fromCharCode((high >> 10) | 0xd800, (high & 0x3ff) | 0xdc00);
      },
      // CSS string/identifier serialization
      // https://drafts.csswg.org/cssom/#common-serializing-idioms
      rcssescape = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
      fcssescape = function (ch, asCodePoint) {
        if (asCodePoint) {
          // U+0000 NULL becomes U+FFFD REPLACEMENT CHARACTER
          if (ch === "\0") {
            return "\uFFFD";
          }

          // Control characters and (dependent upon position) numbers get escaped as code points
          return (
            ch.slice(0, -1) +
            "\\" +
            ch.charCodeAt(ch.length - 1).toString(16) +
            " "
          );
        }

        // Other potentially-special ASCII characters get backslash-escaped
        return "\\" + ch;
      },
      // Used for iframes
      // See setDocument()
      // Removing the function wrapper causes a "Permission Denied"
      // error in IE
      unloadHandler = function () {
        setDocument();
      };

    // Optimize for push.apply( _, NodeList )
    try {
      push.apply(
        (arr = slice.call(preferredDoc.childNodes)),
        preferredDoc.childNodes
      );

      // Support: Android<4.0
      // Detect silently failing push.apply
      // eslint-disable-next-line no-unused-expressions
      arr[preferredDoc.childNodes.length].nodeType;
    } catch (e) {
      push = {
        apply: arr.length
          ? // Leverage slice if possible
            function (target, els) {
              pushNative.apply(target, slice.call(els));
            }
          : // Support: IE<9
            // Otherwise append directly
            function (target, els) {
              var j = target.length,
                i = 0;

              // Can't trust NodeList.length
              while ((target[j++] = els[i++])) {}
              target.length = j - 1;
            },
      };
    }

    function Sizzle(selector, context, results, seed) {
      var m,
        i,
        elem,
        nid,
        match,
        groups,
        newSelector,
        newContext = context && context.ownerDocument,
        // nodeType defaults to 9, since context defaults to document
        nodeType = context ? context.nodeType : 9;

      results = results || [];

      // Return early from calls with invalid selector or context
      if (
        typeof selector !== "string" ||
        !selector ||
        (nodeType !== 1 && nodeType !== 9 && nodeType !== 11)
      ) {
        return results;
      }

      // Try to shortcut find operations (as opposed to filters) in HTML documents
      if (!seed) {
        setDocument(context);
        context = context || document;

        if (documentIsHTML) {
          // If the selector is sufficiently simple, try using a "get*By*" DOM method
          // (excepting DocumentFragment context, where the methods don't exist)
          if (nodeType !== 11 && (match = rquickExpr.exec(selector))) {
            // ID selector
            if ((m = match[1])) {
              // Document context
              if (nodeType === 9) {
                if ((elem = context.getElementById(m))) {
                  // Support: IE, Opera, Webkit
                  // TODO: identify versions
                  // getElementById can match elements by name instead of ID
                  if (elem.id === m) {
                    results.push(elem);
                    return results;
                  }
                } else {
                  return results;
                }

                // Element context
              } else {
                // Support: IE, Opera, Webkit
                // TODO: identify versions
                // getElementById can match elements by name instead of ID
                if (
                  newContext &&
                  (elem = newContext.getElementById(m)) &&
                  contains(context, elem) &&
                  elem.id === m
                ) {
                  results.push(elem);
                  return results;
                }
              }

              // Type selector
            } else if (match[2]) {
              push.apply(results, context.getElementsByTagName(selector));
              return results;

              // Class selector
            } else if (
              (m = match[3]) &&
              support.getElementsByClassName &&
              context.getElementsByClassName
            ) {
              push.apply(results, context.getElementsByClassName(m));
              return results;
            }
          }

          // Take advantage of querySelectorAll
          if (
            support.qsa &&
            !nonnativeSelectorCache[selector + " "] &&
            (!rbuggyQSA || !rbuggyQSA.test(selector)) &&
            // Support: IE 8 only
            // Exclude object elements
            (nodeType !== 1 || context.nodeName.toLowerCase() !== "object")
          ) {
            newSelector = selector;
            newContext = context;

            // qSA considers elements outside a scoping root when evaluating child or
            // descendant combinators, which is not what we want.
            // In such cases, we work around the behavior by prefixing every selector in the
            // list with an ID selector referencing the scope context.
            // The technique has to be used as well when a leading combinator is used
            // as such selectors are not recognized by querySelectorAll.
            // Thanks to Andrew Dupont for this technique.
            if (
              nodeType === 1 &&
              (rdescend.test(selector) || rcombinators.test(selector))
            ) {
              // Expand context for sibling selectors
              newContext =
                (rsibling.test(selector) && testContext(context.parentNode)) ||
                context;

              // We can use :scope instead of the ID hack if the browser
              // supports it & if we're not changing the context.
              if (newContext !== context || !support.scope) {
                // Capture the context ID, setting it first if necessary
                if ((nid = context.getAttribute("id"))) {
                  nid = nid.replace(rcssescape, fcssescape);
                } else {
                  context.setAttribute("id", (nid = expando));
                }
              }

              // Prefix every selector in the list
              groups = tokenize(selector);
              i = groups.length;
              while (i--) {
                groups[i] =
                  (nid ? "#" + nid : ":scope") + " " + toSelector(groups[i]);
              }
              newSelector = groups.join(",");
            }

            try {
              // `qSA` may not throw for unrecognized parts using forgiving parsing:
              // https://drafts.csswg.org/selectors/#forgiving-selector
              // like the `:has()` pseudo-class:
              // https://drafts.csswg.org/selectors/#relational
              // `CSS.supports` is still expected to return `false` then:
              // https://drafts.csswg.org/css-conditional-4/#typedef-supports-selector-fn
              // https://drafts.csswg.org/css-conditional-4/#dfn-support-selector
              if (
                support.cssSupportsSelector &&
                // eslint-disable-next-line no-undef
                !CSS.supports("selector(:is(" + newSelector + "))")
              ) {
                // Support: IE 11+
                // Throw to get to the same code path as an error directly in qSA.
                // Note: once we only support browser supporting
                // `CSS.supports('selector(...)')`, we can most likely drop
                // the `try-catch`. IE doesn't implement the API.
                throw new Error();
              }

              push.apply(results, newContext.querySelectorAll(newSelector));
              return results;
            } catch (qsaError) {
              nonnativeSelectorCache(selector, true);
            } finally {
              if (nid === expando) {
                context.removeAttribute("id");
              }
            }
          }
        }
      }

      // All others
      return select(selector.replace(rtrim, "$1"), context, results, seed);
    }
    function createCache() {
      var keys = [];

      function cache(key, value) {
        // Use (key + " ") to avoid collision with native prototype properties (see Issue #157)
        if (keys.push(key + " ") > Expr.cacheLength) {
          // Only keep the most recent entries
          delete cache[keys.shift()];
        }
        return (cache[key + " "] = value);
      }
      return cache;
    }
    /**
     * Mark a function for special use by Sizzle
     * @param {Function} fn The function to mark
     */
    function markFunction(fn) {
      fn[expando] = true;
      return fn;
    }

    /**
     * Support testing using an element
     * @param {Function} fn Passed the created element and returns a boolean result
     */
    function assert(fn) {
      var el = document.createElement("fieldset");

      try {
        return !!fn(el);
      } catch (e) {
        return false;
      } finally {
        // Remove from its parent by default
        if (el.parentNode) {
          el.parentNode.removeChild(el);
        }

        // release memory in IE
        el = null;
      }
    }

    /**
     * Checks document order of two siblings
     * @param {Element} a
     * @param {Element} b
     * @returns {Number} Returns less than 0 if a precedes b, greater than 0 if a follows b
     */
    function siblingCheck(a, b) {
      var cur = b && a,
        diff =
          cur &&
          a.nodeType === 1 &&
          b.nodeType === 1 &&
          a.sourceIndex - b.sourceIndex;

      // Use IE sourceIndex if available on both nodes
      if (diff) {
        return diff;
      }

      // Check if b follows a
      if (cur) {
        while ((cur = cur.nextSibling)) {
          if (cur === b) {
            return -1;
          }
        }
      }

      return a ? 1 : -1;
    }

    /**
     * Returns a function to use in pseudos for input types
     * @param {String} type
     */
    function createInputPseudo(type) {
      return function (elem) {
        var name = elem.nodeName.toLowerCase();
        return name === "input" && elem.type === type;
      };
    }

    /**
     * Returns a function to use in pseudos for buttons
     * @param {String} type
     */
    function createButtonPseudo(type) {
      return function (elem) {
        var name = elem.nodeName.toLowerCase();
        return (name === "input" || name === "button") && elem.type === type;
      };
    }

    /**
     * Returns a function to use in pseudos for :enabled/:disabled
     * @param {Boolean} disabled true for :disabled; false for :enabled
     */
    function createDisabledPseudo(disabled) {
      // Known :disabled false positives: fieldset[disabled] > legend:nth-of-type(n+2) :can-disable
      return function (elem) {
        // Only certain elements can match :enabled or :disabled
        if ("form" in elem) {
          // Check for inherited disabledness on relevant non-disabled elements:
          if (elem.parentNode && elem.disabled === false) {
            // Option elements defer to a parent optgroup if present
            if ("label" in elem) {
              if ("label" in elem.parentNode) {
                return elem.parentNode.disabled === disabled;
              } else {
                return elem.disabled === disabled;
              }
            }

            // Use the isDisabled shortcut property to check for disabled fieldset ancestors
            return (
              elem.isDisabled === disabled ||
              // Where there is no isDisabled, check manually
              (elem.isDisabled !== !disabled &&
                inDisabledFieldset(elem) === disabled)
            );
          }

          return elem.disabled === disabled;

          // Try to winnow out elements that can't be disabled before trusting the disabled property.
          // Some victims get caught in our net (label, legend, menu, track), but it shouldn't
          // even exist on them, let alone have a boolean value.
        } else if ("label" in elem) {
          return elem.disabled === disabled;
        }

        // Remaining elements are neither :enabled nor :disabled
        return false;
      };
    }

    /**
     * Returns a function to use in pseudos for positionals
     * @param {Function} fn
     */
    function createPositionalPseudo(fn) {
      return markFunction(function (argument) {
        argument = +argument;
        return markFunction(function (seed, matches) {
          var j,
            matchIndexes = fn([], seed.length, argument),
            i = matchIndexes.length;

          // Match elements found at the specified indexes
          while (i--) {
            if (seed[(j = matchIndexes[i])]) {
              seed[j] = !(matches[j] = seed[j]);
            }
          }
        });
      });
    }

    /**
     * Checks a node for validity as a Sizzle context
     * @param {Element|Object=} context
     * @returns {Element|Object|Boolean} The input node if acceptable, otherwise a falsy value
     */
    function testContext(context) {
      return (
        context &&
        typeof context.getElementsByTagName !== "undefined" &&
        context
      );
    }

    // Expose support vars for convenience
    support = Sizzle.support = {};

    /**
     * Detects XML nodes
     * @param {Element|Object} elem An element or a document
     * @returns {Boolean} True iff elem is a non-HTML XML node
     */
    isXML = Sizzle.isXML = function (elem) {
      var namespace = elem && elem.namespaceURI,
        docElem = elem && (elem.ownerDocument || elem).documentElement;

      return !rhtml.test(namespace || (docElem && docElem.nodeName) || "HTML");
    };

    /**
     * Sets document-related variables once based on the current document
     * @param {Element|Object} [doc] An element or document object to use to set the document
     * @returns {Object} Returns the current document
     */
    setDocument = Sizzle.setDocument = function (node) {
      var hasCompare,
        subWindow,
        doc = node ? node.ownerDocument || node : preferredDoc;

      if (doc == document || doc.nodeType !== 9 || !doc.documentElement) {
        return document;
      }

      // Update global variables
      document = doc;
      docElem = document.documentElement;
      documentIsHTML = !isXML(document);

      if (
        preferredDoc != document &&
        (subWindow = document.defaultView) &&
        subWindow.top !== subWindow
      ) {
        if (subWindow.addEventListener) {
          subWindow.addEventListener("unload", unloadHandler, false);
        } else if (subWindow.attachEvent) {
          subWindow.attachEvent("onunload", unloadHandler);
        }
      }

      support.scope = assert(function (el) {
        docElem.appendChild(el).appendChild(document.createElement("div"));
        return (
          typeof el.querySelectorAll !== "undefined" &&
          !el.querySelectorAll(":scope fieldset div").length
        );
      });

      // Make sure forgiving mode is not used in `CSS.supports( "selector(...)" )`.

      // `:is()` uses a forgiving selector list as an argument and is widely
      // implemented, so it's a good one to test against.
      support.cssSupportsSelector = assert(function () {
        return (
          CSS.supports("selector(*)") &&
          // In old Firefox, `:is()` didn't use forgiving parsing. In that case,
          // fail this test as there's no selector to test against that.
          // `CSS.supports` uses unforgiving parsing
          document.querySelectorAll(":is(:jqfake)") &&
          // `*` is needed as Safari & newer Chrome implemented something in between
          // for `:has()` - it throws in `qSA` if it only contains an unsupported
          // argument but multiple ones, one of which is supported, are fine.
          // We want to play safe in case `:is()` gets the same treatment.
          !CSS.supports("selector(:is(*,:jqfake))")
        );
      });

      /* Attributes
    ---------------------------------------------------------------------- */

      // Verify that getAttribute really returns attributes and not properties
      support.attributes = assert(function (el) {
        el.className = "i";
        return !el.getAttribute("className");
      });

      /* getElement(s)By*
    ---------------------------------------------------------------------- */

      // Check if getElementsByTagName("*") returns only elements
      support.getElementsByTagName = assert(function (el) {
        el.appendChild(document.createComment(""));
        return !el.getElementsByTagName("*").length;
      });

      support.getElementsByClassName = rnative.test(
        document.getElementsByClassName
      );

      // Check if getElementById returns elements by name
      // The broken getElementById methods don't pick up programmatically-set names,
      // so use a roundabout getElementsByName test
      support.getById = assert(function (el) {
        docElem.appendChild(el).id = expando;
        return (
          !document.getElementsByName ||
          !document.getElementsByName(expando).length
        );
      });

      // ID filter and find
      if (support.getById) {
        Expr.filter["ID"] = function (id) {
          var attrId = id.replace(runescape, funescape);
          return function (elem) {
            return elem.getAttribute("id") === attrId;
          };
        };
        Expr.find["ID"] = function (id, context) {
          if (typeof context.getElementById !== "undefined" && documentIsHTML) {
            var elem = context.getElementById(id);
            return elem ? [elem] : [];
          }
        };
      } else {
        Expr.filter["ID"] = function (id) {
          var attrId = id.replace(runescape, funescape);
          return function (elem) {
            var node =
              typeof elem.getAttributeNode !== "undefined" &&
              elem.getAttributeNode("id");
            return node && node.value === attrId;
          };
        };

        // getElementById is not reliable as a find shortcut
        Expr.find["ID"] = function (id, context) {
          if (typeof context.getElementById !== "undefined" && documentIsHTML) {
            var node,
              i,
              elems,
              elem = context.getElementById(id);

            if (elem) {
              // Verify the id attribute
              node = elem.getAttributeNode("id");
              if (node && node.value === id) {
                return [elem];
              }

              // Fall back on getElementsByName
              elems = context.getElementsByName(id);
              i = 0;
              while ((elem = elems[i++])) {
                node = elem.getAttributeNode("id");
                if (node && node.value === id) {
                  return [elem];
                }
              }
            }

            return [];
          }
        };
      }

      // Tag
      Expr.find["TAG"] = support.getElementsByTagName
        ? function (tag, context) {
            if (typeof context.getElementsByTagName !== "undefined") {
              return context.getElementsByTagName(tag);

              // DocumentFragment nodes don't have gEBTN
            } else if (support.qsa) {
              return context.querySelectorAll(tag);
            }
          }
        : function (tag, context) {
            var elem,
              tmp = [],
              i = 0,
              // By happy coincidence, a (broken) gEBTN appears on DocumentFragment nodes too
              results = context.getElementsByTagName(tag);

            // Filter out possible comments
            if (tag === "*") {
              while ((elem = results[i++])) {
                if (elem.nodeType === 1) {
                  tmp.push(elem);
                }
              }

              return tmp;
            }
            return results;
          };

      // Class
      Expr.find["CLASS"] =
        support.getElementsByClassName &&
        function (className, context) {
          if (
            typeof context.getElementsByClassName !== "undefined" &&
            documentIsHTML
          ) {
            return context.getElementsByClassName(className);
          }
        };

      /* QSA/matchesSelector
    ---------------------------------------------------------------------- */

      // QSA and matchesSelector support
      rbuggyMatches = [];

      // We allow this because of a bug in IE8/9 that throws an error
      // whenever `document.activeElement` is accessed on an iframe
      // So, we allow :focus to pass through QSA all the time to avoid the IE error
      rbuggyQSA = [];

      if ((support.qsa = rnative.test(document.querySelectorAll))) {
        // Build QSA regex
        // Regex strategy adopted from Diego Perini
        assert(function (el) {
          var input;

          // Select is set to empty string on purpose
          // This is to test IE's treatment of not explicitly
          // setting a boolean content attribute,
          // since its presence should be enough
          // https://bugs.wv.com/ticket/12359
          docElem.appendChild(el).innerHTML =
            "<a id='" +
            expando +
            "'></a>" +
            "<select id='" +
            expando +
            "-\r\\' msallowcapture=''>" +
            "<option selected=''></option></select>";

          // Support: IE8, Opera 11-12.16
          // Nothing should be selected when empty strings follow ^= or $= or *=
          // The test attribute must be unknown in Opera but "safe" for WinRT
          // https://msdn.microsoft.com/en-us/library/ie/hh465388.aspx#attribute_section
          if (el.querySelectorAll("[msallowcapture^='']").length) {
            rbuggyQSA.push("[*^$]=" + whitespace + "*(?:''|\"\")");
          }

          // Boolean attributes and "value" are not treated correctly
          if (!el.querySelectorAll("[selected]").length) {
            rbuggyQSA.push("\\[" + whitespace + "*(?:value|" + booleans + ")");
          }

          if (!el.querySelectorAll("[id~=" + expando + "-]").length) {
            rbuggyQSA.push("~=");
          }

          // Adding a temporary attribute to the document before the selection works
          input = document.createElement("input");
          input.setAttribute("name", "");
          el.appendChild(input);
          if (!el.querySelectorAll("[name='']").length) {
            rbuggyQSA.push(
              "\\[" +
                whitespace +
                "*name" +
                whitespace +
                "*=" +
                whitespace +
                "*(?:''|\"\")"
            );
          }

          if (!el.querySelectorAll(":checked").length) {
            rbuggyQSA.push(":checked");
          }

          // In-page `selector#id sibling-combinator selector` fails
          if (!el.querySelectorAll("a#" + expando + "+*").length) {
            rbuggyQSA.push(".#.+[+~]");
          }

          // Old Firefox doesn't throw on a badly-escaped identifier.
          el.querySelectorAll("\\\f");
          rbuggyQSA.push("[\\r\\n\\f]");
        });

        assert(function (el) {
          el.innerHTML =
            "<a href='' disabled='disabled'></a>" +
            "<select disabled='disabled'><option/></select>";

          // The type and name attributes are restricted during .innerHTML assignment
          var input = document.createElement("input");
          input.setAttribute("type", "hidden");
          el.appendChild(input).setAttribute("name", "D");

          // Enforce case-sensitivity of name attribute
          if (el.querySelectorAll("[name=d]").length) {
            rbuggyQSA.push("name" + whitespace + "*[*^$|!~]?=");
          }

          if (el.querySelectorAll(":enabled").length !== 2) {
            rbuggyQSA.push(":enabled", ":disabled");
          }

          // IE's :disabled selector does not pick up the children of disabled fieldsets
          docElem.appendChild(el).disabled = true;
          if (el.querySelectorAll(":disabled").length !== 2) {
            rbuggyQSA.push(":enabled", ":disabled");
          }

          el.querySelectorAll("*,:x");
          rbuggyQSA.push(",.*:");
        });
      }

      if (
        (support.matchesSelector = rnative.test(
          (matches =
            docElem.matches ||
            docElem.webkitMatchesSelector ||
            docElem.mozMatchesSelector ||
            docElem.oMatchesSelector ||
            docElem.msMatchesSelector)
        ))
      ) {
        assert(function (el) {
          // Check to see if it's possible to do matchesSelector
          support.disconnectedMatch = matches.call(el, "*");

          // This should fail with an exception
          // Gecko does not error, returns false instead
          matches.call(el, "[s!='']:x");
          rbuggyMatches.push("!=", pseudos);
        });
      }

      if (!support.cssSupportsSelector) {
        // `:has()` uses a forgiving selector list as an argument so our regular
        // `try-catch` mechanism fails to catch `:has()` with arguments not supported
        // natively like `:has(:contains("Foo"))`. Where supported & spec-compliant,
        // we now use `CSS.supports("selector(:is(SELECTOR_TO_BE_TESTED))")`, but
        // outside that we mark `:has` as buggy.
        rbuggyQSA.push(":has");
      }

      rbuggyQSA = rbuggyQSA.length && new RegExp(rbuggyQSA.join("|"));
      rbuggyMatches =
        rbuggyMatches.length && new RegExp(rbuggyMatches.join("|"));

      /* Contains
    ---------------------------------------------------------------------- */
      hasCompare = rnative.test(docElem.compareDocumentPosition);

      // Element contains another
      // Purposefully self-exclusive
      // As in, an element does not contain itself
      contains =
        hasCompare || rnative.test(docElem.contains)
          ? function (a, b) {
              // IE doesn't have `contains` on `document` so we need to check for
              // `documentElement` presence.
              // We need to fall back to `a` when `documentElement` is missing
              // as `ownerDocument` of elements within `<template/>` may have
              // a null one - a default behavior of all modern browsers.
              var adown = (a.nodeType === 9 && a.documentElement) || a,
                bup = b && b.parentNode;
              return (
                a === bup ||
                !!(
                  bup &&
                  bup.nodeType === 1 &&
                  (adown.contains
                    ? adown.contains(bup)
                    : a.compareDocumentPosition &&
                      a.compareDocumentPosition(bup) & 16)
                )
              );
            }
          : function (a, b) {
              if (b) {
                while ((b = b.parentNode)) {
                  if (b === a) {
                    return true;
                  }
                }
              }
              return false;
            };

      /* Sorting
    ---------------------------------------------------------------------- */

      // Document order sorting
      sortOrder = hasCompare
        ? function (a, b) {
            // Flag for duplicate removal
            if (a === b) {
              hasDuplicate = true;
              return 0;
            }

            // Sort on method existence if only one input has compareDocumentPosition
            var compare =
              !a.compareDocumentPosition - !b.compareDocumentPosition;
            if (compare) {
              return compare;
            }

            // Calculate position if both inputs belong to the same document
            // two documents; shallow comparisons work.
            // eslint-disable-next-line eqeqeq
            compare =
              (a.ownerDocument || a) == (b.ownerDocument || b)
                ? a.compareDocumentPosition(b)
                : // Otherwise we know they are disconnected
                  1;

            // Disconnected nodes
            if (
              compare & 1 ||
              (!support.sortDetached &&
                b.compareDocumentPosition(a) === compare)
            ) {
              // Choose the first element that is related to our preferred document
              if (
                a == document ||
                (a.ownerDocument == preferredDoc && contains(preferredDoc, a))
              ) {
                return -1;
              }

              // two documents; shallow comparisons work.
              if (
                b == document ||
                (b.ownerDocument == preferredDoc && contains(preferredDoc, b))
              ) {
                return 1;
              }

              // Maintain original order
              return sortInput
                ? indexOf(sortInput, a) - indexOf(sortInput, b)
                : 0;
            }

            return compare & 4 ? -1 : 1;
          }
        : function (a, b) {
            // Exit early if the nodes are identical
            if (a === b) {
              hasDuplicate = true;
              return 0;
            }

            var cur,
              i = 0,
              aup = a.parentNode,
              bup = b.parentNode,
              ap = [a],
              bp = [b];

            // Parentless nodes are either documents or disconnected
            if (!aup || !bup) {
              return a == document
                ? -1
                : b == document
                ? 1
                : aup
                ? -1
                : bup
                ? 1
                : sortInput
                ? indexOf(sortInput, a) - indexOf(sortInput, b)
                : 0;

              // If the nodes are siblings, we can do a quick check
            } else if (aup === bup) {
              return siblingCheck(a, b);
            }

            // Otherwise we need full lists of their ancestors for comparison
            cur = a;
            while ((cur = cur.parentNode)) {
              ap.unshift(cur);
            }
            cur = b;
            while ((cur = cur.parentNode)) {
              bp.unshift(cur);
            }

            // Walk down the tree looking for a discrepancy
            while (ap[i] === bp[i]) {
              i++;
            }

            return i
              ? // Do a sibling check if the nodes have a common ancestor
                siblingCheck(ap[i], bp[i])
              : // Otherwise nodes in our document sort first
              ap[i] == preferredDoc
              ? -1
              : bp[i] == preferredDoc
              ? 1
              : 0;
          };

      return document;
    };

    Sizzle.matches = function (expr, elements) {
      return Sizzle(expr, null, null, elements);
    };

    Sizzle.matchesSelector = function (elem, expr) {
      setDocument(elem);

      if (
        support.matchesSelector &&
        documentIsHTML &&
        !nonnativeSelectorCache[expr + " "] &&
        (!rbuggyMatches || !rbuggyMatches.test(expr)) &&
        (!rbuggyQSA || !rbuggyQSA.test(expr))
      ) {
        try {
          var ret = matches.call(elem, expr);

          if (
            ret ||
            support.disconnectedMatch ||
            // As well, disconnected nodes are said to be in a document
            (elem.document && elem.document.nodeType !== 11)
          ) {
            return ret;
          }
        } catch (e) {
          nonnativeSelectorCache(expr, true);
        }
      }

      return Sizzle(expr, document, null, [elem]).length > 0;
    };

    Sizzle.contains = function (context, elem) {
      // Set document vars if needed
      if ((context.ownerDocument || context) != document) {
        setDocument(context);
      }
      return contains(context, elem);
    };

    Sizzle.attr = function (elem, name) {
      // Set document vars if needed
      if ((elem.ownerDocument || elem) != document) {
        setDocument(elem);
      }

      var fn = Expr.attrHandle[name.toLowerCase()],
        // Don't get fooled by Object.prototype properties (w #13807)
        val =
          fn && hasOwn.call(Expr.attrHandle, name.toLowerCase())
            ? fn(elem, name, !documentIsHTML)
            : undefined;

      return val !== undefined
        ? val
        : support.attributes || !documentIsHTML
        ? elem.getAttribute(name)
        : (val = elem.getAttributeNode(name)) && val.specified
        ? val.value
        : null;
    };

    Sizzle.escape = function (sel) {
      return (sel + "").replace(rcssescape, fcssescape);
    };

    Sizzle.error = function (msg) {
      throw new Error("Syntax error, unrecognized expression: " + msg);
    };

    /**
     * Document sorting and removing duplicates
     * @param {ArrayLike} results
     */
    Sizzle.uniqueSort = function (results) {
      var elem,
        duplicates = [],
        j = 0,
        i = 0;

      // Unless we *know* we can detect duplicates, assume their presence
      hasDuplicate = !support.detectDuplicates;
      sortInput = !support.sortStable && results.slice(0);
      results.sort(sortOrder);

      if (hasDuplicate) {
        while ((elem = results[i++])) {
          if (elem === results[i]) {
            j = duplicates.push(i);
          }
        }
        while (j--) {
          results.splice(duplicates[j], 1);
        }
      }

      // Clear input after sorting to release objects
      sortInput = null;

      return results;
    };

    /**
     * Utility function for retrieving the text value of an array of DOM nodes
     * @param {Array|Element} elem
     */
    getText = Sizzle.getText = function (elem) {
      var node,
        ret = "",
        i = 0,
        nodeType = elem.nodeType;

      if (!nodeType) {
        // If no nodeType, this is expected to be an array
        while ((node = elem[i++])) {
          // Do not traverse comment nodes
          ret += getText(node);
        }
      } else if (nodeType === 1 || nodeType === 9 || nodeType === 11) {
        // Use textContent for elements
        // innerText usage removed for consistency of new lines (w #11153)
        if (typeof elem.textContent === "string") {
          return elem.textContent;
        } else {
          // Traverse its children
          for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
            ret += getText(elem);
          }
        }
      } else if (nodeType === 3 || nodeType === 4) {
        return elem.nodeValue;
      }

      // Do not include comment or processing instruction nodes

      return ret;
    };

    Expr = Sizzle.selectors = {
      // Can be adjusted by the user
      cacheLength: 50,

      createPseudo: markFunction,

      match: matchExpr,

      attrHandle: {},

      find: {},

      relative: {
        ">": { dir: "parentNode", first: true },
        " ": { dir: "parentNode" },
        "+": { dir: "previousSibling", first: true },
        "~": { dir: "previousSibling" },
      },

      preFilter: {
        ATTR: function (match) {
          match[1] = match[1].replace(runescape, funescape);

          // Move the given value to match[3] whether quoted or unquoted
          match[3] = (match[3] || match[4] || match[5] || "").replace(
            runescape,
            funescape
          );

          if (match[2] === "~=") {
            match[3] = " " + match[3] + " ";
          }

          return match.slice(0, 4);
        },

        CHILD: function (match) {
          /* matches from matchExpr["CHILD"]
          1 type (only|nth|...)
          2 what (child|of-type)
          3 argument (even|odd|\d*|\d*n([+-]\d+)?|...)
          4 xn-component of xn+y argument ([+-]?\d*n|)
          5 sign of xn-component
          6 x of xn-component
          7 sign of y-component
          8 y of y-component
        */
          match[1] = match[1].toLowerCase();

          if (match[1].slice(0, 3) === "nth") {
            // nth-* requires argument
            if (!match[3]) {
              Sizzle.error(match[0]);
            }

            // numeric x and y parameters for Expr.filter.CHILD
            // remember that false/true cast respectively to 0/1
            match[4] = +(match[4]
              ? match[5] + (match[6] || 1)
              : 2 * (match[3] === "even" || match[3] === "odd"));
            match[5] = +(match[7] + match[8] || match[3] === "odd");

            // other types prohibit arguments
          } else if (match[3]) {
            Sizzle.error(match[0]);
          }

          return match;
        },

        PSEUDO: function (match) {
          var excess,
            unquoted = !match[6] && match[2];

          if (matchExpr["CHILD"].test(match[0])) {
            return null;
          }

          // Accept quoted arguments as-is
          if (match[3]) {
            match[2] = match[4] || match[5] || "";

            // Strip excess characters from unquoted arguments
          } else if (
            unquoted &&
            rpseudo.test(unquoted) &&
            // Get excess from tokenize (recursively)
            (excess = tokenize(unquoted, true)) &&
            // advance to the next closing parenthesis
            (excess =
              unquoted.indexOf(")", unquoted.length - excess) - unquoted.length)
          ) {
            // excess is a negative index
            match[0] = match[0].slice(0, excess);
            match[2] = unquoted.slice(0, excess);
          }

          // Return only captures needed by the pseudo filter method (type and argument)
          return match.slice(0, 3);
        },
      },

      filter: {
        TAG: function (nodeNameSelector) {
          var nodeName = nodeNameSelector
            .replace(runescape, funescape)
            .toLowerCase();
          return nodeNameSelector === "*"
            ? function () {
                return true;
              }
            : function (elem) {
                return (
                  elem.nodeName && elem.nodeName.toLowerCase() === nodeName
                );
              };
        },

        CLASS: function (className) {
          var pattern = classCache[className + " "];

          return (
            pattern ||
            ((pattern = new RegExp(
              "(^|" + whitespace + ")" + className + "(" + whitespace + "|$)"
            )) &&
              classCache(className, function (elem) {
                return pattern.test(
                  (typeof elem.className === "string" && elem.className) ||
                    (typeof elem.getAttribute !== "undefined" &&
                      elem.getAttribute("class")) ||
                    ""
                );
              }))
          );
        },

        ATTR: function (name, operator, check) {
          return function (elem) {
            var result = Sizzle.attr(elem, name);

            if (result == null) {
              return operator === "!=";
            }
            if (!operator) {
              return true;
            }

            result += "";

            /* eslint-disable max-len */

            return operator === "="
              ? result === check
              : operator === "!="
              ? result !== check
              : operator === "^="
              ? check && result.indexOf(check) === 0
              : operator === "*="
              ? check && result.indexOf(check) > -1
              : operator === "$="
              ? check && result.slice(-check.length) === check
              : operator === "~="
              ? (" " + result.replace(rwhitespace, " ") + " ").indexOf(check) >
                -1
              : operator === "|="
              ? result === check ||
                result.slice(0, check.length + 1) === check + "-"
              : false;
            /* eslint-enable max-len */
          };
        },

        CHILD: function (type, what, _argument, first, last) {
          var simple = type.slice(0, 3) !== "nth",
            forward = type.slice(-4) !== "last",
            ofType = what === "of-type";

          return first === 1 && last === 0
            ? // Shortcut for :nth-*(n)
              function (elem) {
                return !!elem.parentNode;
              }
            : function (elem, _context, xml) {
                var cache,
                  uniqueCache,
                  outerCache,
                  node,
                  nodeIndex,
                  start,
                  dir = simple !== forward ? "nextSibling" : "previousSibling",
                  parent = elem.parentNode,
                  name = ofType && elem.nodeName.toLowerCase(),
                  useCache = !xml && !ofType,
                  diff = false;

                if (parent) {
                  // :(first|last|only)-(child|of-type)
                  if (simple) {
                    while (dir) {
                      node = elem;
                      while ((node = node[dir])) {
                        if (
                          ofType
                            ? node.nodeName.toLowerCase() === name
                            : node.nodeType === 1
                        ) {
                          return false;
                        }
                      }

                      // Reverse direction for :only-* (if we haven't yet done so)
                      start = dir = type === "only" && !start && "nextSibling";
                    }
                    return true;
                  }

                  start = [forward ? parent.firstChild : parent.lastChild];

                  // non-xml :nth-child(...) stores cache data on `parent`
                  if (forward && useCache) {
                    // Seek `elem` from a previously-cached index

                    // ...in a gzip-friendly way
                    node = parent;
                    outerCache = node[expando] || (node[expando] = {});

                    // Support: IE <9 only
                    // Defend against cloned attroperties (w gh-1709)
                    uniqueCache =
                      outerCache[node.uniqueID] ||
                      (outerCache[node.uniqueID] = {});

                    cache = uniqueCache[type] || [];
                    nodeIndex = cache[0] === dirruns && cache[1];
                    diff = nodeIndex && cache[2];
                    node = nodeIndex && parent.childNodes[nodeIndex];

                    while (
                      (node =
                        (++nodeIndex && node && node[dir]) ||
                        // Fallback to seeking `elem` from the start
                        (diff = nodeIndex = 0) ||
                        start.pop())
                    ) {
                      // When found, cache indexes on `parent` and break
                      if (node.nodeType === 1 && ++diff && node === elem) {
                        uniqueCache[type] = [dirruns, nodeIndex, diff];
                        break;
                      }
                    }
                  } else {
                    // Use previously-cached element index if available
                    if (useCache) {
                      // ...in a gzip-friendly way
                      node = elem;
                      outerCache = node[expando] || (node[expando] = {});

                      // Support: IE <9 only
                      // Defend against cloned attroperties (w gh-1709)
                      uniqueCache =
                        outerCache[node.uniqueID] ||
                        (outerCache[node.uniqueID] = {});

                      cache = uniqueCache[type] || [];
                      nodeIndex = cache[0] === dirruns && cache[1];
                      diff = nodeIndex;
                    }

                    // xml :nth-child(...)
                    // or :nth-last-child(...) or :nth(-last)?-of-type(...)
                    if (diff === false) {
                      // Use the same loop as above to seek `elem` from the start
                      while (
                        (node =
                          (++nodeIndex && node && node[dir]) ||
                          (diff = nodeIndex = 0) ||
                          start.pop())
                      ) {
                        if (
                          (ofType
                            ? node.nodeName.toLowerCase() === name
                            : node.nodeType === 1) &&
                          ++diff
                        ) {
                          // Cache the index of each encountered element
                          if (useCache) {
                            outerCache = node[expando] || (node[expando] = {});

                            // Support: IE <9 only
                            // Defend against cloned attroperties (w gh-1709)
                            uniqueCache =
                              outerCache[node.uniqueID] ||
                              (outerCache[node.uniqueID] = {});

                            uniqueCache[type] = [dirruns, diff];
                          }

                          if (node === elem) {
                            break;
                          }
                        }
                      }
                    }
                  }

                  // Incorporate the offset, then check against cycle size
                  diff -= last;
                  return (
                    diff === first || (diff % first === 0 && diff / first >= 0)
                  );
                }
              };
        },
        PSEUDO: function (pseudo, argument) {
          // pseudo-class names are case-insensitive
          // http://www.w3.org/TR/selectors/#pseudo-classes
          // Prioritize by case sensitivity in case custom pseudos are added with uppercase letters
          // Remember that setFilters inherits from pseudos
          var args,
            fn =
              Expr.pseudos[pseudo] ||
              Expr.setFilters[pseudo.toLowerCase()] ||
              Sizzle.error("unsupported pseudo: " + pseudo);

          // The user may use createPseudo to indicate that
          // arguments are needed to create the filter function
          // just as Sizzle does
          if (fn[expando]) {
            return fn(argument);
          }

          // But maintain support for old signatures
          if (fn.length > 1) {
            args = [pseudo, pseudo, "", argument];
            return Expr.setFilters.hasOwnProperty(pseudo.toLowerCase())
              ? markFunction(function (seed, matches) {
                  var idx,
                    matched = fn(seed, argument),
                    i = matched.length;
                  while (i--) {
                    idx = indexOf(seed, matched[i]);
                    seed[idx] = !(matches[idx] = matched[i]);
                  }
                })
              : function (elem) {
                  return fn(elem, 0, args);
                };
          }

          return fn;
        },
      },
      pseudos: {
        // Potentially complex pseudos
        not: markFunction(function (selector) {
          // Trim the selector passed to compile
          // to avoid treating leading and trailing
          // spaces as combinators
          var input = [],
            results = [],
            matcher = compile(selector.replace(rtrim, "$1"));

          return matcher[expando]
            ? markFunction(function (seed, matches, _context, xml) {
                var elem,
                  unmatched = matcher(seed, null, xml, []),
                  i = seed.length;

                // Match elements unmatched by `matcher`
                while (i--) {
                  if ((elem = unmatched[i])) {
                    seed[i] = !(matches[i] = elem);
                  }
                }
              })
            : function (elem, _context, xml) {
                input[0] = elem;
                matcher(input, null, xml, results);

                // Don't keep the element (issue #299)
                input[0] = null;
                return !results.pop();
              };
        }),

        has: markFunction(function (selector) {
          return function (elem) {
            return Sizzle(selector, elem).length > 0;
          };
        }),

        contains: markFunction(function (text) {
          text = text.replace(runescape, funescape);
          return function (elem) {
            return (elem.textContent || getText(elem)).indexOf(text) > -1;
          };
        }),

        // "Whether an element is represented by a :lang() selector
        // is based solely on the element's language value
        // being equal to the identifier C,
        // or beginning with the identifier C immediately followed by "-".
        // The matching of C against the element's language value is performed case-insensitively.
        // The identifier C does not have to be a valid language name."
        // http://www.w3.org/TR/selectors/#lang-pseudo
        lang: markFunction(function (lang) {
          // lang value must be a valid identifier
          if (!ridentifier.test(lang || "")) {
            Sizzle.error("unsupported lang: " + lang);
          }
          lang = lang.replace(runescape, funescape).toLowerCase();
          return function (elem) {
            var elemLang;
            do {
              if (
                (elemLang = documentIsHTML
                  ? elem.lang
                  : elem.getAttribute("xml:lang") || elem.getAttribute("lang"))
              ) {
                elemLang = elemLang.toLowerCase();
                return elemLang === lang || elemLang.indexOf(lang + "-") === 0;
              }
            } while ((elem = elem.parentNode) && elem.nodeType === 1);
            return false;
          };
        }),

        // Miscellaneous
        target: function (elem) {
          var hash = window.location && window.location.hash;
          return hash && hash.slice(1) === elem.id;
        },

        root: function (elem) {
          return elem === docElem;
        },

        focus: function (elem) {
          return (
            elem === document.activeElement &&
            (!document.hasFocus || document.hasFocus()) &&
            !!(elem.type || elem.href || ~elem.tabIndex)
          );
        },

        // Boolean properties
        enabled: createDisabledPseudo(false),
        disabled: createDisabledPseudo(true),

        checked: function (elem) {
          // In CSS3, :checked should return both checked and selected elements
          // http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#checked
          var nodeName = elem.nodeName.toLowerCase();
          return (
            (nodeName === "input" && !!elem.checked) ||
            (nodeName === "option" && !!elem.selected)
          );
        },

        selected: function (elem) {
          // Accessing this property makes selected-by-default
          // options in Safari work properly
          if (elem.parentNode) {
            // eslint-disable-next-line no-unused-expressions
            elem.parentNode.selectedIndex;
          }

          return elem.selected === true;
        },

        // Contents
        empty: function (elem) {
          // http://www.w3.org/TR/selectors/#empty-pseudo
          // :empty is negated by element (1) or content nodes (text: 3; cdata: 4; entity ref: 5),
          //   but not by others (comment: 8; processing instruction: 7; etc.)
          // nodeType < 6 works because attributes (2) do not appear as children
          for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
            if (elem.nodeType < 6) {
              return false;
            }
          }
          return true;
        },

        parent: function (elem) {
          return !Expr.pseudos["empty"](elem);
        },

        // Element/input types
        header: function (elem) {
          return rheader.test(elem.nodeName);
        },

        input: function (elem) {
          return rinputs.test(elem.nodeName);
        },

        button: function (elem) {
          var name = elem.nodeName.toLowerCase();
          return (
            (name === "input" && elem.type === "button") || name === "button"
          );
        },

        text: function (elem) {
          var attr;
          return (
            elem.nodeName.toLowerCase() === "input" &&
            elem.type === "text" &&
            // Support: IE <10 only
            // New HTML5 attribute values (e.g., "search") appear with elem.type === "text"
            ((attr = elem.getAttribute("type")) == null ||
              attr.toLowerCase() === "text")
          );
        },

        // Position-in-collection
        first: createPositionalPseudo(function () {
          return [0];
        }),

        last: createPositionalPseudo(function (_matchIndexes, length) {
          return [length - 1];
        }),

        eq: createPositionalPseudo(function (_matchIndexes, length, argument) {
          return [argument < 0 ? argument + length : argument];
        }),

        even: createPositionalPseudo(function (matchIndexes, length) {
          var i = 0;
          for (; i < length; i += 2) {
            matchIndexes.push(i);
          }
          return matchIndexes;
        }),

        odd: createPositionalPseudo(function (matchIndexes, length) {
          var i = 1;
          for (; i < length; i += 2) {
            matchIndexes.push(i);
          }
          return matchIndexes;
        }),

        lt: createPositionalPseudo(function (matchIndexes, length, argument) {
          var i =
            argument < 0
              ? argument + length
              : argument > length
              ? length
              : argument;
          for (; --i >= 0; ) {
            matchIndexes.push(i);
          }
          return matchIndexes;
        }),

        gt: createPositionalPseudo(function (matchIndexes, length, argument) {
          var i = argument < 0 ? argument + length : argument;
          for (; ++i < length; ) {
            matchIndexes.push(i);
          }
          return matchIndexes;
        }),
      },
    };

    tokenize = Sizzle.tokenize = function (selector, parseOnly) {
      var matched,
        match,
        tokens,
        type,
        soFar,
        groups,
        preFilters,
        cached = tokenCache[selector + " "];

      if (cached) {
        return parseOnly ? 0 : cached.slice(0);
      }

      soFar = selector;
      groups = [];
      preFilters = Expr.preFilter;

      while (soFar) {
        // Comma and first run
        if (!matched || (match = rcomma.exec(soFar))) {
          if (match) {
            // Don't consume trailing commas as valid
            soFar = soFar.slice(match[0].length) || soFar;
          }
          groups.push((tokens = []));
        }

        matched = false;

        // Combinators
        if ((match = rcombinators.exec(soFar))) {
          matched = match.shift();
          tokens.push({
            value: matched,

            // Cast descendant combinators to space
            type: match[0].replace(rtrim, " "),
          });
          soFar = soFar.slice(matched.length);
        }

        // Filters
        for (type in Expr.filter) {
          if (
            (match = matchExpr[type].exec(soFar)) &&
            (!preFilters[type] || (match = preFilters[type](match)))
          ) {
            matched = match.shift();
            tokens.push({
              value: matched,
              type: type,
              matches: match,
            });
            soFar = soFar.slice(matched.length);
          }
        }

        if (!matched) {
          break;
        }
      }

      // Return the length of the invalid excess
      // if we're just parsing
      // Otherwise, throw an error or return tokens
      return parseOnly
        ? soFar.length
        : soFar
        ? Sizzle.error(selector)
        : // Cache the tokens
          tokenCache(selector, groups).slice(0);
    };

    function toSelector(tokens) {
      var i = 0,
        len = tokens.length,
        selector = "";
      for (; i < len; i++) {
        selector += tokens[i].value;
      }
      return selector;
    }

    // Initialize against the default document
    setDocument();

    return Sizzle;
  })(window);

  w.find = Sizzle;
  w.expr = Sizzle.selectors;

  // Deprecated
  w.expr[":"] = w.expr.pseudos;
  w.uniqueSort = w.unique = Sizzle.uniqueSort;
  w.text = Sizzle.getText;
  w.isXMLDoc = Sizzle.isXML;
  w.contains = Sizzle.contains;
  w.escapeSelector = Sizzle.escape;

  var rneedsContext = w.expr.match.needsContext;

  var rsingleTag =
    /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;

  // Implement the identical functionality for filter and not
  function winnow(elements, qualifier, not) {
    if (isFunction(qualifier)) {
      return w.grep(elements, function (elem, i) {
        return !!qualifier.call(elem, i, elem) !== not;
      });
    }

    // Single element
    if (qualifier.nodeType) {
      return w.grep(elements, function (elem) {
        return (elem === qualifier) !== not;
      });
    }

    // Arraylike of elements (w, arguments, Array)
    if (typeof qualifier !== "string") {
      return w.grep(elements, function (elem) {
        return indexOf.call(qualifier, elem) > -1 !== not;
      });
    }

    // Filtered directly for both simple and complex selectors
    return w.filter(qualifier, elements, not);
  }

  w.filter = function (expr, elems, not) {
    var elem = elems[0];

    if (not) {
      expr = ":not(" + expr + ")";
    }

    if (elems.length === 1 && elem.nodeType === 1) {
      return w.find.matchesSelector(elem, expr) ? [elem] : [];
    }

    return w.find.matches(
      expr,
      w.grep(elems, function (elem) {
        return elem.nodeType === 1;
      })
    );
  };

  w.fn.extend({
    find: function (selector) {
      let i,
        ret,
        len = this.length,
        self = this;

      if (typeof selector !== "string") {
        return this.pushStack(
          w(selector).filter(function () {
            for (i = 0; i < len; i++) {
              if (w.contains(self[i], this)) {
                return true;
              }
            }
          })
        );
      }

      ret = this.pushStack([]);

      for (i = 0; i < len; i++) {
        w.find(selector, self[i], ret);
      }

      return len > 1 ? w.uniqueSort(ret) : ret;
    },
    filter: function (selector) {
      return this.pushStack(winnow(this, selector || [], false));
    },
    not: function (selector) {
      return this.pushStack(winnow(this, selector || [], true));
    },
    is: function (selector) {
      return !!winnow(
        this,

        // If this is a positional/relative selector, check membership in the returned set
        // so $("p:first").is("p:last") won't return true for a doc with two "p".
        typeof selector === "string" && rneedsContext.test(selector)
          ? w(selector)
          : selector || [],
        false
      ).length;
    },
  });

  // extend function for w
  w.fn.extend({
    val: function (value) {
      elem = this;
      if (!elem || elem.length == 0) {
        return;
      }
      if (value || value == "") {
        for (var i = 0; i < elem.length; i++) {
          elem[i].value = value;
        }
        w.requestEvent();
      } else {
        elem = this[0];
        return elem.value;
      }
    },
    attr: function (attr, value) {
      elem = this;
      if (!elem || elem.length == 0) {
        return;
      }
      if (value || value == "") {
        for (var i = 0; i < elem.length; i++) {
          elem[i].setAttribute(attr, value);
        }
        w.requestEvent();
      } else {
        elem = this[0];
        return elem.getAttribute(attr);
      }
    },
    removeAttr: function (attr) {
      elem = this;
      if (!elem || elem.length == 0) {
        return;
      }
      for (var i = 0; i < elem.length; i++) {
        elem[i].removeAttribute(attr);
      }
      w.requestEvent();
    },
    prop: function (prop, value) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (value) {
        elem[prop] = value;
        w.requestEvent();
      } else {
        return elem[prop];
      }
    },
    removeProp: function (prop) {
      elem = this[0];
      if (!elem) {
        return;
      }
      delete elem[prop];
      w.requestEvent();
    },
    addClass: function (class_name) {
      elem = this;
      if (!elem) {
        return;
      }
      // loop add class elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].classList.add(class_name);
      }
      w.requestEvent();
    },
    removeClass: function (class_name) {
      elem = this;
      if (!elem) {
        return;
      }
      // loop remove class elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].classList.remove(class_name);
      }
      w.requestEvent();
    },
    hasClass: function (class_name) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (class_name) {
        return elem.classList.contains(class_name);
      }
    },
    toggleClass: function (class_name) {
      elem = this;
      if (!elem) {
        return;
      }
      // loop toggle class elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].classList.toggle(class_name);
      }
      w.requestEvent();
    },
    css: function (css, value) {
      elem = this;
      if (!elem) {
        return;
      }
      // loop set style elem
      if (css && value != undefined) {
        for (var i = 0; i < elem.length; i++) {
          elem[i].style[css] = value;
        }
      } else if (css) {
        return getComputedStyle(elem[0])[css];
      }
      w.requestEvent();
    },
    parents: function (selector) {
      var elem = this[0];
      if (elem) {
        return w(elem.closest(selector));
      }
    },
    append: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("beforeend", html);
        w.requestEvent();
      }
    },
    prepend: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("afterbegin", html);
        w.requestEvent();
      }
    },
    before: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("beforebegin", html);
        w.requestEvent();
      }
    },
    after: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("afterend", html);
        w.requestEvent();
      }
    },
    remove: function () {
      elem = this;
      if (!elem) {
        return;
      }
      // loop remove elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].remove();
      }
      w.requestEvent();
    },
    text: function (text) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (text) {
        elem.textContent = text;
        w.requestEvent();
      } else {
        return elem.textContent;
      }
    },
    trigger: function (event) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (event) {
        elem.dispatchEvent(new Event(event));
        w.requestEvent();
      }
    },
    html: function (html) {
      elem = this;
      if (!elem || elem.length == 0) {
        return;
      }

      if (html || html == "") {
        for (var i = 0; i < elem.length; i++) {
          elem[i].innerHTML = html;
        }
        w.requestEvent();
      } else {
        elem = this[0];
        return elem.innerHTML;
      }
    },
    show: function () {
      elem = this;
      if (!elem) {
        return;
      }
      // loop show elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].style.display = "block";
      }
      w.requestEvent();
    },
    hide: function () {
      elem = this;
      if (!elem) {
        return;
      }
      // loop hide elem
      for (var i = 0; i < elem.length; i++) {
        elem[i].style.display = "none";
      }
      w.requestEvent();
    },
    on: function (type, elem, callback) {
      if (type && elem && callback) {
        reqEvent.push({ type: type, elem: elem, callback: callback });
      }
      elem = w(document).find(elem);
      if (!elem) {
        return;
      }
      // loop add event elem
      privateMethod.handler(type, elem, callback);
    },
    width: function (width) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (width) {
        elem.style.width = width + "px";
        w.requestEvent();
      } else {
        return elem.offsetWidth;
      }
    },
    height: function (height) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (height) {
        elem.style.height = height + "px";
        w.requestEvent();
      } else {
        return elem.offsetHeight;
      }
    },
    offset: function () {
      elem = this[0];
      if (!elem) {
        return;
      }
      var rect = elem.getBoundingClientRect();
      return {
        top: rect.top + document.body.scrollTop,
        left: rect.left + document.body.scrollLeft,
      };
    },
    outerWidth: function (width) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (width) {
        elem.style.width = width + "px";
        w.requestEvent();
      } else {
        return elem.offsetWidth;
      }
    },
    outerHeight: function (height) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (height) {
        elem.style.height = height + "px";
        w.requestEvent();
      } else {
        return elem.offsetHeight;
      }
    },
    focus: function () {
      elem = this[0];
      if (!elem) {
        return;
      }
      elem.focus();
      w.requestEvent();
    },
    click: function () {
      elem = this[0];
      if (!elem) {
        return;
      }
      elem.click();
      w.requestEvent();
    },
    submit: function () {
      elem = this[0];
      if (!elem) {
        return;
      }
      elem.submit();
      w.requestEvent();
    },
    insertAfter: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("afterend", html);
        w.requestEvent();
      }
    },
    insertBefore: function (html) {
      elem = this[0];
      if (!elem) {
        return;
      }
      if (html) {
        elem.insertAdjacentHTML("beforebegin", html);
        w.requestEvent();
      }
    },
    modal: function (action) {
      elem = this[0];
      var myModal;
      if (action == "show") {
        myModal = new bootstrap.Modal(elem, {
          keyboard: false,
        });
        myModal.show();
      } else if (action == "hide") {
        myModal = bootstrap.Modal.getInstance(elem);
        myModal.hide();
      }
    },
    slideUp: function (duration) {
      elem = this[0];
      w.slideUp(elem, duration);
    },
    slideDown: function (duration) {
      elem = this[0];
      w.slideDown(elem, duration);
    },
    slideToggle: function (duration) {
      elem = this[0];
      w.slideToggle(elem, duration);
    },
    fadeOut: function (duration) {
      elem = this[0];
      w.fadeOut(elem, duration);
    },
    fadeIn: function (duration) {
      elem = this[0];
      w.fadeIn(elem, duration);
    },
    fadeToggle: function (duration) {
      elem = this[0];
      w.fadeToggle(elem, duration);
    },
    animate: function (css, duration, callback) {
      elem = this[0];
      w.animate(elem, css, duration, callback);
    },
    // serializeJSON: function() {
    //   elem = this[ 0 ];
    //   console.log(elem);
    //   return elem.serializeJSON();
    // }
    scrollTop: function () {
      elem = this[0];
      return elem.scrollTop;
    },
  });

  // A central reference to the root w(document)
  var rootw,
    // A simple way to check for HTML strings
    // Prioritize #id over <tag> to avoid XSS via location.hash (trac-9521)
    // Strict HTML recognition (trac-11290: must start with <)
    // Shortcut simple #id case for speed
    rquickExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,
    init = (w.fn.init = function (selector, context, root) {
      var match, elem;

      // HANDLE: $(""), $(null), $(undefined), $(false)
      if (!selector) {
        return this;
      }

      // Method init() accepts an alternate rootw
      // so migrate can support w.sub (gh-2101)
      root = root || rootw;

      // Handle HTML strings
      if (typeof selector === "string") {
        if (
          selector[0] === "<" &&
          selector[selector.length - 1] === ">" &&
          selector.length >= 3
        ) {
          // Assume that strings that start and end with <> are HTML and skip the regex check
          match = [null, selector, null];
        } else {
          match = rquickExpr.exec(selector);
        }

        // Match html or make sure no context is specified for #id
        if (match && (match[1] || !context)) {
          // HANDLE: $(html) -> $(array)
          if (match[1]) {
            context = context instanceof w ? context[0] : context;

            // Option to run scripts is true for back-compat
            // Intentionally let the error be thrown if parseHTML is not present
            w.merge(
              this,
              w.parseHTML(
                match[1],
                context && context.nodeType
                  ? context.ownerDocument || context
                  : document,
                true
              )
            );

            // HANDLE: $(html, props)
            if (rsingleTag.test(match[1]) && w.isPlainObject(context)) {
              for (match in context) {
                // Properties of context are called as methods if possible
                if (isFunction(this[match])) {
                  this[match](context[match]);

                  // ...and otherwise set as attributes
                } else {
                  this.attr(match, context[match]);
                }
              }
            }

            return this;

            // HANDLE: $(#id)
          } else {
            elem = document.getElementById(match[2]);

            if (elem) {
              // Inject the element directly into the w object
              this[0] = elem;
              this.length = 1;
            }
            return this;
          }

          // HANDLE: $(expr, $(...))
        } else if (!context || context.wv) {
          return (context || root).find(selector);

          // HANDLE: $(expr, context)
          // (which is just equivalent to: $(context).find(expr)
        } else {
          return this.constructor(context).find(selector);
        }

        // HANDLE: $(DOMElement)
      } else if (selector.nodeType) {
        this[0] = selector;
        this.length = 1;
        return this;

        // HANDLE: $(function)
        // Shortcut for document ready
      } else if (isFunction(selector)) {
        return root.ready !== undefined
          ? root.ready(selector)
          : // Execute immediately if ready is not present
            selector(w);
      }

      return w.makeArray(selector, this);
    });

  requestEvent = function () {
    check_update = true;
    for (let i = 0; i < reqEvent.length; i++) {
      let ele = [...document.querySelectorAll(reqEvent[i].elem)];
      if (ele == null) continue;

      if (ele.length == 1) {
        ele = ele[0];
        ele.removeEventListener(reqEvent[i].type, reqEvent[i].callback);
        ele.addEventListener(reqEvent[i].type, reqEvent[i].callback);
      } else {
        for (let j = 0; j < ele.length; j++) {
          ele[j].removeEventListener(reqEvent[i].type, reqEvent[i].callback);
          ele[j].addEventListener(reqEvent[i].type, reqEvent[i].callback);
        }
      }
    }
    check_update = false;
  };
  w.extend({ requestEvent });

  w.extend({
    slideUp: (target, duration = 500) => {
      target.style.transitionProperty = "height, margin, padding";
      target.style.transitionDuration = duration + "ms";
      target.style.boxSizing = "border-box";
      target.style.height = target.offsetHeight + "px";
      target.offsetHeight;
      target.style.overflow = "hidden";
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      window.setTimeout(() => {
        target.style.display = "none";
        target.style.removeProperty("height");
        target.style.removeProperty("padding-top");
        target.style.removeProperty("padding-bottom");
        target.style.removeProperty("margin-top");
        target.style.removeProperty("margin-bottom");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
        //alert("!");
      }, duration);
    },

    slideDown: (target, duration = 500) => {
      target.style.removeProperty("display");
      let display = window.getComputedStyle(target).display;

      if (display === "none") display = "block";

      target.style.display = display;
      let height = target.offsetHeight;
      target.style.overflow = "hidden";
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      target.offsetHeight;
      target.style.boxSizing = "border-box";
      target.style.transitionProperty = "height, margin, padding";
      target.style.transitionDuration = duration + "ms";
      target.style.height = height + "px";
      target.style.removeProperty("padding-top");
      target.style.removeProperty("padding-bottom");
      target.style.removeProperty("margin-top");
      target.style.removeProperty("margin-bottom");
      window.setTimeout(() => {
        target.style.removeProperty("height");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
      }, duration);
    },

    slideToggle: (target, duration = 500) => {
      if (window.getComputedStyle(target).display === "none") {
        return w.slideDown(target, duration);
      } else {
        return w.slideUp(target, duration);
      }
    },

    fadeOut: (target, duration = 500) => {
      target.style.opacity = 1;
      let currentTime = 0;

      function animate() {
        currentTime += 16;

        const newOpacity = Math.max(0, 1 - currentTime / duration);
        target.style.opacity = newOpacity;

        if (newOpacity <= 0) {
          target.style.display = "none";
        }
        if (currentTime < duration) {
          requestAnimationFrame(animate);
        }
      }
      animate();
    },

    fadeIn: (target, duration = 500) => {
      target.style.opacity = 0;
      target.style.display = "block";
      let currentTime = 0;

      function animate() {
        currentTime += 16;

        const newOpacity = Math.min(1, currentTime / duration);
        target.style.opacity = newOpacity;

        if (currentTime < duration) {
          requestAnimationFrame(animate);
        }
      }
      animate();
    },

    fadeToggle: (target, duration = 500) => {
      if (window.getComputedStyle(target).display === "none") {
        return w.fadeIn(target, duration);
      } else {
        return w.fadeOut(target, duration);
      }
    },

    animate: (target, style = {}, duration = 500, callback) => {
      const startStyle = {}; // object to store the starting style values
      const startTime = Date.now(); // record the start time
      for (let prop in style) {
        startStyle[prop] = parseInt(getComputedStyle(target)[prop]);
      }

      function animation() {
        const currentTime = Date.now() - startTime; // calculate the current time elapsed
        const progress = Math.min(1, currentTime / duration); // calculate the progress of the animation

        for (let prop in style) {
          const startValue = startStyle[prop];
          const endValue = style[prop];
          const currentValue =
            parseFloat(startValue) +
            (parseFloat(endValue) - parseFloat(startValue)) *
              parseFloat(progress);
          target.style[prop] = currentValue + "px";
        }

        if (currentTime < duration) {
          requestAnimationFrame(animation);
        } else {
          // execute the callback function if one was provided
          if (callback) {
            callback();
          }
        }
      }
      animation(); // start the animation
    },
  });

  // Give the init function the w prototype for later instantiation
  init.prototype = w.fn;

  // Initialize central reference
  rootw = w(document);

  // private method get element
  let privateMethod = {
    p_id: (elem) => {
      return document.getElementById(elem);
    },
    p_class: (elem) => {
      return document.getElementsByClassName(elem);
    },
    p_tag: (elem) => {
      return document.getElementsByTagName(elem);
    },
    p_ele: (src) => {
      let ele = [...document.querySelectorAll(src)];
      if (ele.length == 1) {
        ele = ele[0];
      }
      return ele;
    },
    p_name: (elem) => {
      return [...document.getElementsByName(elem)];
    },
    p_selectOption: (elem) => {
      return elem.options[elem.selectedIndex];
    },
    handler: (type, element, callback) => {
      let ele = w(element);
      if (!ele[0] && ele !== undefined) {
        return false;
      }

      // loop call function
      if (ele.length > 1) {
        for (let i = 0; i < ele.length; i++) {
          ele[i].addEventListener(type, callback);
        }
      } else {
        ele[0].addEventListener(type, callback);
      }
    },
    xhr_get: (url, callback) => {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          callback(xhr.responseText);
        }
      };
      xhr.open("GET", url, true);
      xhr.send();
    },
    xhr_post: (url, data, callback) => {
      data = JSON.stringify(data);
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          callback(xhr.responseText);
        }
      };
      xhr.open("POST", url, true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send(data);
    },
    xhr_base: (url, method, data, callback) => {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          callback(xhr.responseText);
        }
      };
      xhr.open(method, url, true);
      xhr.send(data);
    },
    xhr_formData: (url, form_id, callback) => {
      var data = new FormData(w.query.id(form_id));
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          callback(xhr.responseText);
        }
      };
      xhr.open("POST", url, true);
      xhr.send(data);
    },
  };

  // public method get element
  w.query = {
    id: (src) => privateMethod.p_id(src),
    class: (src) => privateMethod.p_class(src),
    tag: (src) => privateMethod.p_tag(src),
    ele: (src) => privateMethod.p_ele(src),
    name: (element) => privateMethod.p_name(element),
    selectOption: (element) => privateMethod.p_selectOption(element),
  };

  // XML HTTP REQUEST
  w.xml = {
    get: (url, callback) => privateMethod.xhr_get(url, callback),
    post: (url, data, callback) => privateMethod.xhr_post(url, data, callback),
    base: (url, method, data, callback) =>
      privateMethod.xhr_base(url, method, data, callback),
    formData: (url, form_id, callback) =>
      privateMethod.xhr_formData(url, form_id, callback),
  };

  // event handler
  w.on = (type, element, callback) => {
    if (type && element && callback) {
      reqEvent.push({ type: type, elem: element, callback: callback });
    }
    element = w(document).find(element);

    privateMethod.handler(type, element, callback);
  };
  return w;
};

if (typeof module === "object" && module.exports) {
  module.exports = wbFn();
} else if (typeof define === "function" && define.amd) {
  define("wbFn", [], () => {
    return wbFn();
  });
} else {
  window.w = wbFn();
}

const wq = w.query;
const wx = w.xml;