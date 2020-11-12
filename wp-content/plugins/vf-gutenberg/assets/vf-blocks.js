(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('@wordpress/blocks'), require('react'), require('@wordpress/i18n'), require('@wordpress/block-editor'), require('@wordpress/components'), require('@wordpress/data'), require('@wordpress/hooks')) :
	typeof define === 'function' && define.amd ? define(['@wordpress/blocks', 'react', '@wordpress/i18n', '@wordpress/block-editor', '@wordpress/components', '@wordpress/data', '@wordpress/hooks'], factory) :
	(global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.wp.blocks, global.React, global.wp.i18n, global.wp.blockEditor, global.wp.components, global.wp.data, global.wp.hooks));
}(this, (function (blocks, React, i18n, blockEditor, components, data$1, hooks) { 'use strict';

	function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

	var React__default = /*#__PURE__*/_interopDefaultLegacy(React);

	var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

	function getDefaultExportFromCjs (x) {
		return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x['default'] : x;
	}

	function createCommonjsModule(fn, basedir, module) {
		return module = {
			path: basedir,
			exports: {},
			require: function (path, base) {
				return commonjsRequire(path, (base === undefined || base === null) ? module.path : base);
			}
		}, fn(module, module.exports), module.exports;
	}

	function commonjsRequire () {
		throw new Error('Dynamic requires are not currently supported by @rollup/plugin-commonjs');
	}

	var check = function (it) {
	  return it && it.Math == Math && it;
	};

	// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
	var global_1 =
	  // eslint-disable-next-line no-undef
	  check(typeof globalThis == 'object' && globalThis) ||
	  check(typeof window == 'object' && window) ||
	  check(typeof self == 'object' && self) ||
	  check(typeof commonjsGlobal == 'object' && commonjsGlobal) ||
	  // eslint-disable-next-line no-new-func
	  Function('return this')();

	var fails = function (exec) {
	  try {
	    return !!exec();
	  } catch (error) {
	    return true;
	  }
	};

	// Thank's IE8 for his funny defineProperty
	var descriptors = !fails(function () {
	  return Object.defineProperty({}, 1, { get: function () { return 7; } })[1] != 7;
	});

	var nativePropertyIsEnumerable = {}.propertyIsEnumerable;
	var getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;

	// Nashorn ~ JDK8 bug
	var NASHORN_BUG = getOwnPropertyDescriptor && !nativePropertyIsEnumerable.call({ 1: 2 }, 1);

	// `Object.prototype.propertyIsEnumerable` method implementation
	// https://tc39.github.io/ecma262/#sec-object.prototype.propertyisenumerable
	var f = NASHORN_BUG ? function propertyIsEnumerable(V) {
	  var descriptor = getOwnPropertyDescriptor(this, V);
	  return !!descriptor && descriptor.enumerable;
	} : nativePropertyIsEnumerable;

	var objectPropertyIsEnumerable = {
		f: f
	};

	var createPropertyDescriptor = function (bitmap, value) {
	  return {
	    enumerable: !(bitmap & 1),
	    configurable: !(bitmap & 2),
	    writable: !(bitmap & 4),
	    value: value
	  };
	};

	var toString = {}.toString;

	var classofRaw = function (it) {
	  return toString.call(it).slice(8, -1);
	};

	var split = ''.split;

	// fallback for non-array-like ES3 and non-enumerable old V8 strings
	var indexedObject = fails(function () {
	  // throws an error in rhino, see https://github.com/mozilla/rhino/issues/346
	  // eslint-disable-next-line no-prototype-builtins
	  return !Object('z').propertyIsEnumerable(0);
	}) ? function (it) {
	  return classofRaw(it) == 'String' ? split.call(it, '') : Object(it);
	} : Object;

	// `RequireObjectCoercible` abstract operation
	// https://tc39.github.io/ecma262/#sec-requireobjectcoercible
	var requireObjectCoercible = function (it) {
	  if (it == undefined) throw TypeError("Can't call method on " + it);
	  return it;
	};

	// toObject with fallback for non-array-like ES3 strings



	var toIndexedObject = function (it) {
	  return indexedObject(requireObjectCoercible(it));
	};

	var isObject = function (it) {
	  return typeof it === 'object' ? it !== null : typeof it === 'function';
	};

	// `ToPrimitive` abstract operation
	// https://tc39.github.io/ecma262/#sec-toprimitive
	// instead of the ES6 spec version, we didn't implement @@toPrimitive case
	// and the second argument - flag - preferred type is a string
	var toPrimitive = function (input, PREFERRED_STRING) {
	  if (!isObject(input)) return input;
	  var fn, val;
	  if (PREFERRED_STRING && typeof (fn = input.toString) == 'function' && !isObject(val = fn.call(input))) return val;
	  if (typeof (fn = input.valueOf) == 'function' && !isObject(val = fn.call(input))) return val;
	  if (!PREFERRED_STRING && typeof (fn = input.toString) == 'function' && !isObject(val = fn.call(input))) return val;
	  throw TypeError("Can't convert object to primitive value");
	};

	var hasOwnProperty = {}.hasOwnProperty;

	var has = function (it, key) {
	  return hasOwnProperty.call(it, key);
	};

	var document$1 = global_1.document;
	// typeof document.createElement is 'object' in old IE
	var EXISTS = isObject(document$1) && isObject(document$1.createElement);

	var documentCreateElement = function (it) {
	  return EXISTS ? document$1.createElement(it) : {};
	};

	// Thank's IE8 for his funny defineProperty
	var ie8DomDefine = !descriptors && !fails(function () {
	  return Object.defineProperty(documentCreateElement('div'), 'a', {
	    get: function () { return 7; }
	  }).a != 7;
	});

	var nativeGetOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;

	// `Object.getOwnPropertyDescriptor` method
	// https://tc39.github.io/ecma262/#sec-object.getownpropertydescriptor
	var f$1 = descriptors ? nativeGetOwnPropertyDescriptor : function getOwnPropertyDescriptor(O, P) {
	  O = toIndexedObject(O);
	  P = toPrimitive(P, true);
	  if (ie8DomDefine) try {
	    return nativeGetOwnPropertyDescriptor(O, P);
	  } catch (error) { /* empty */ }
	  if (has(O, P)) return createPropertyDescriptor(!objectPropertyIsEnumerable.f.call(O, P), O[P]);
	};

	var objectGetOwnPropertyDescriptor = {
		f: f$1
	};

	var anObject = function (it) {
	  if (!isObject(it)) {
	    throw TypeError(String(it) + ' is not an object');
	  } return it;
	};

	var nativeDefineProperty = Object.defineProperty;

	// `Object.defineProperty` method
	// https://tc39.github.io/ecma262/#sec-object.defineproperty
	var f$2 = descriptors ? nativeDefineProperty : function defineProperty(O, P, Attributes) {
	  anObject(O);
	  P = toPrimitive(P, true);
	  anObject(Attributes);
	  if (ie8DomDefine) try {
	    return nativeDefineProperty(O, P, Attributes);
	  } catch (error) { /* empty */ }
	  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported');
	  if ('value' in Attributes) O[P] = Attributes.value;
	  return O;
	};

	var objectDefineProperty = {
		f: f$2
	};

	var createNonEnumerableProperty = descriptors ? function (object, key, value) {
	  return objectDefineProperty.f(object, key, createPropertyDescriptor(1, value));
	} : function (object, key, value) {
	  object[key] = value;
	  return object;
	};

	var setGlobal = function (key, value) {
	  try {
	    createNonEnumerableProperty(global_1, key, value);
	  } catch (error) {
	    global_1[key] = value;
	  } return value;
	};

	var SHARED = '__core-js_shared__';
	var store = global_1[SHARED] || setGlobal(SHARED, {});

	var sharedStore = store;

	var functionToString = Function.toString;

	// this helper broken in `3.4.1-3.4.4`, so we can't use `shared` helper
	if (typeof sharedStore.inspectSource != 'function') {
	  sharedStore.inspectSource = function (it) {
	    return functionToString.call(it);
	  };
	}

	var inspectSource = sharedStore.inspectSource;

	var WeakMap = global_1.WeakMap;

	var nativeWeakMap = typeof WeakMap === 'function' && /native code/.test(inspectSource(WeakMap));

	var shared = createCommonjsModule(function (module) {
	(module.exports = function (key, value) {
	  return sharedStore[key] || (sharedStore[key] = value !== undefined ? value : {});
	})('versions', []).push({
	  version: '3.6.5',
	  mode:  'global',
	  copyright: '© 2020 Denis Pushkarev (zloirock.ru)'
	});
	});

	var id = 0;
	var postfix = Math.random();

	var uid = function (key) {
	  return 'Symbol(' + String(key === undefined ? '' : key) + ')_' + (++id + postfix).toString(36);
	};

	var keys = shared('keys');

	var sharedKey = function (key) {
	  return keys[key] || (keys[key] = uid(key));
	};

	var hiddenKeys = {};

	var WeakMap$1 = global_1.WeakMap;
	var set, get, has$1;

	var enforce = function (it) {
	  return has$1(it) ? get(it) : set(it, {});
	};

	var getterFor = function (TYPE) {
	  return function (it) {
	    var state;
	    if (!isObject(it) || (state = get(it)).type !== TYPE) {
	      throw TypeError('Incompatible receiver, ' + TYPE + ' required');
	    } return state;
	  };
	};

	if (nativeWeakMap) {
	  var store$1 = new WeakMap$1();
	  var wmget = store$1.get;
	  var wmhas = store$1.has;
	  var wmset = store$1.set;
	  set = function (it, metadata) {
	    wmset.call(store$1, it, metadata);
	    return metadata;
	  };
	  get = function (it) {
	    return wmget.call(store$1, it) || {};
	  };
	  has$1 = function (it) {
	    return wmhas.call(store$1, it);
	  };
	} else {
	  var STATE = sharedKey('state');
	  hiddenKeys[STATE] = true;
	  set = function (it, metadata) {
	    createNonEnumerableProperty(it, STATE, metadata);
	    return metadata;
	  };
	  get = function (it) {
	    return has(it, STATE) ? it[STATE] : {};
	  };
	  has$1 = function (it) {
	    return has(it, STATE);
	  };
	}

	var internalState = {
	  set: set,
	  get: get,
	  has: has$1,
	  enforce: enforce,
	  getterFor: getterFor
	};

	var redefine = createCommonjsModule(function (module) {
	var getInternalState = internalState.get;
	var enforceInternalState = internalState.enforce;
	var TEMPLATE = String(String).split('String');

	(module.exports = function (O, key, value, options) {
	  var unsafe = options ? !!options.unsafe : false;
	  var simple = options ? !!options.enumerable : false;
	  var noTargetGet = options ? !!options.noTargetGet : false;
	  if (typeof value == 'function') {
	    if (typeof key == 'string' && !has(value, 'name')) createNonEnumerableProperty(value, 'name', key);
	    enforceInternalState(value).source = TEMPLATE.join(typeof key == 'string' ? key : '');
	  }
	  if (O === global_1) {
	    if (simple) O[key] = value;
	    else setGlobal(key, value);
	    return;
	  } else if (!unsafe) {
	    delete O[key];
	  } else if (!noTargetGet && O[key]) {
	    simple = true;
	  }
	  if (simple) O[key] = value;
	  else createNonEnumerableProperty(O, key, value);
	// add fake Function#toString for correct work wrapped methods / constructors with methods like LoDash isNative
	})(Function.prototype, 'toString', function toString() {
	  return typeof this == 'function' && getInternalState(this).source || inspectSource(this);
	});
	});

	var path = global_1;

	var aFunction = function (variable) {
	  return typeof variable == 'function' ? variable : undefined;
	};

	var getBuiltIn = function (namespace, method) {
	  return arguments.length < 2 ? aFunction(path[namespace]) || aFunction(global_1[namespace])
	    : path[namespace] && path[namespace][method] || global_1[namespace] && global_1[namespace][method];
	};

	var ceil = Math.ceil;
	var floor = Math.floor;

	// `ToInteger` abstract operation
	// https://tc39.github.io/ecma262/#sec-tointeger
	var toInteger = function (argument) {
	  return isNaN(argument = +argument) ? 0 : (argument > 0 ? floor : ceil)(argument);
	};

	var min = Math.min;

	// `ToLength` abstract operation
	// https://tc39.github.io/ecma262/#sec-tolength
	var toLength = function (argument) {
	  return argument > 0 ? min(toInteger(argument), 0x1FFFFFFFFFFFFF) : 0; // 2 ** 53 - 1 == 9007199254740991
	};

	var max = Math.max;
	var min$1 = Math.min;

	// Helper for a popular repeating case of the spec:
	// Let integer be ? ToInteger(index).
	// If integer < 0, let result be max((length + integer), 0); else let result be min(integer, length).
	var toAbsoluteIndex = function (index, length) {
	  var integer = toInteger(index);
	  return integer < 0 ? max(integer + length, 0) : min$1(integer, length);
	};

	// `Array.prototype.{ indexOf, includes }` methods implementation
	var createMethod = function (IS_INCLUDES) {
	  return function ($this, el, fromIndex) {
	    var O = toIndexedObject($this);
	    var length = toLength(O.length);
	    var index = toAbsoluteIndex(fromIndex, length);
	    var value;
	    // Array#includes uses SameValueZero equality algorithm
	    // eslint-disable-next-line no-self-compare
	    if (IS_INCLUDES && el != el) while (length > index) {
	      value = O[index++];
	      // eslint-disable-next-line no-self-compare
	      if (value != value) return true;
	    // Array#indexOf ignores holes, Array#includes - not
	    } else for (;length > index; index++) {
	      if ((IS_INCLUDES || index in O) && O[index] === el) return IS_INCLUDES || index || 0;
	    } return !IS_INCLUDES && -1;
	  };
	};

	var arrayIncludes = {
	  // `Array.prototype.includes` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.includes
	  includes: createMethod(true),
	  // `Array.prototype.indexOf` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.indexof
	  indexOf: createMethod(false)
	};

	var indexOf = arrayIncludes.indexOf;


	var objectKeysInternal = function (object, names) {
	  var O = toIndexedObject(object);
	  var i = 0;
	  var result = [];
	  var key;
	  for (key in O) !has(hiddenKeys, key) && has(O, key) && result.push(key);
	  // Don't enum bug & hidden keys
	  while (names.length > i) if (has(O, key = names[i++])) {
	    ~indexOf(result, key) || result.push(key);
	  }
	  return result;
	};

	// IE8- don't enum bug keys
	var enumBugKeys = [
	  'constructor',
	  'hasOwnProperty',
	  'isPrototypeOf',
	  'propertyIsEnumerable',
	  'toLocaleString',
	  'toString',
	  'valueOf'
	];

	var hiddenKeys$1 = enumBugKeys.concat('length', 'prototype');

	// `Object.getOwnPropertyNames` method
	// https://tc39.github.io/ecma262/#sec-object.getownpropertynames
	var f$3 = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
	  return objectKeysInternal(O, hiddenKeys$1);
	};

	var objectGetOwnPropertyNames = {
		f: f$3
	};

	var f$4 = Object.getOwnPropertySymbols;

	var objectGetOwnPropertySymbols = {
		f: f$4
	};

	// all object keys, includes non-enumerable and symbols
	var ownKeys = getBuiltIn('Reflect', 'ownKeys') || function ownKeys(it) {
	  var keys = objectGetOwnPropertyNames.f(anObject(it));
	  var getOwnPropertySymbols = objectGetOwnPropertySymbols.f;
	  return getOwnPropertySymbols ? keys.concat(getOwnPropertySymbols(it)) : keys;
	};

	var copyConstructorProperties = function (target, source) {
	  var keys = ownKeys(source);
	  var defineProperty = objectDefineProperty.f;
	  var getOwnPropertyDescriptor = objectGetOwnPropertyDescriptor.f;
	  for (var i = 0; i < keys.length; i++) {
	    var key = keys[i];
	    if (!has(target, key)) defineProperty(target, key, getOwnPropertyDescriptor(source, key));
	  }
	};

	var replacement = /#|\.prototype\./;

	var isForced = function (feature, detection) {
	  var value = data[normalize(feature)];
	  return value == POLYFILL ? true
	    : value == NATIVE ? false
	    : typeof detection == 'function' ? fails(detection)
	    : !!detection;
	};

	var normalize = isForced.normalize = function (string) {
	  return String(string).replace(replacement, '.').toLowerCase();
	};

	var data = isForced.data = {};
	var NATIVE = isForced.NATIVE = 'N';
	var POLYFILL = isForced.POLYFILL = 'P';

	var isForced_1 = isForced;

	var getOwnPropertyDescriptor$1 = objectGetOwnPropertyDescriptor.f;






	/*
	  options.target      - name of the target object
	  options.global      - target is the global object
	  options.stat        - export as static methods of target
	  options.proto       - export as prototype methods of target
	  options.real        - real prototype method for the `pure` version
	  options.forced      - export even if the native feature is available
	  options.bind        - bind methods to the target, required for the `pure` version
	  options.wrap        - wrap constructors to preventing global pollution, required for the `pure` version
	  options.unsafe      - use the simple assignment of property instead of delete + defineProperty
	  options.sham        - add a flag to not completely full polyfills
	  options.enumerable  - export as enumerable property
	  options.noTargetGet - prevent calling a getter on target
	*/
	var _export = function (options, source) {
	  var TARGET = options.target;
	  var GLOBAL = options.global;
	  var STATIC = options.stat;
	  var FORCED, target, key, targetProperty, sourceProperty, descriptor;
	  if (GLOBAL) {
	    target = global_1;
	  } else if (STATIC) {
	    target = global_1[TARGET] || setGlobal(TARGET, {});
	  } else {
	    target = (global_1[TARGET] || {}).prototype;
	  }
	  if (target) for (key in source) {
	    sourceProperty = source[key];
	    if (options.noTargetGet) {
	      descriptor = getOwnPropertyDescriptor$1(target, key);
	      targetProperty = descriptor && descriptor.value;
	    } else targetProperty = target[key];
	    FORCED = isForced_1(GLOBAL ? key : TARGET + (STATIC ? '.' : '#') + key, options.forced);
	    // contained in target
	    if (!FORCED && targetProperty !== undefined) {
	      if (typeof sourceProperty === typeof targetProperty) continue;
	      copyConstructorProperties(sourceProperty, targetProperty);
	    }
	    // add a flag to not completely full polyfills
	    if (options.sham || (targetProperty && targetProperty.sham)) {
	      createNonEnumerableProperty(sourceProperty, 'sham', true);
	    }
	    // extend global
	    redefine(target, key, sourceProperty, options);
	  }
	};

	var aFunction$1 = function (it) {
	  if (typeof it != 'function') {
	    throw TypeError(String(it) + ' is not a function');
	  } return it;
	};

	// optional / simple context binding
	var functionBindContext = function (fn, that, length) {
	  aFunction$1(fn);
	  if (that === undefined) return fn;
	  switch (length) {
	    case 0: return function () {
	      return fn.call(that);
	    };
	    case 1: return function (a) {
	      return fn.call(that, a);
	    };
	    case 2: return function (a, b) {
	      return fn.call(that, a, b);
	    };
	    case 3: return function (a, b, c) {
	      return fn.call(that, a, b, c);
	    };
	  }
	  return function (/* ...args */) {
	    return fn.apply(that, arguments);
	  };
	};

	// `ToObject` abstract operation
	// https://tc39.github.io/ecma262/#sec-toobject
	var toObject = function (argument) {
	  return Object(requireObjectCoercible(argument));
	};

	// `IsArray` abstract operation
	// https://tc39.github.io/ecma262/#sec-isarray
	var isArray = Array.isArray || function isArray(arg) {
	  return classofRaw(arg) == 'Array';
	};

	var nativeSymbol = !!Object.getOwnPropertySymbols && !fails(function () {
	  // Chrome 38 Symbol has incorrect toString conversion
	  // eslint-disable-next-line no-undef
	  return !String(Symbol());
	});

	var useSymbolAsUid = nativeSymbol
	  // eslint-disable-next-line no-undef
	  && !Symbol.sham
	  // eslint-disable-next-line no-undef
	  && typeof Symbol.iterator == 'symbol';

	var WellKnownSymbolsStore = shared('wks');
	var Symbol$1 = global_1.Symbol;
	var createWellKnownSymbol = useSymbolAsUid ? Symbol$1 : Symbol$1 && Symbol$1.withoutSetter || uid;

	var wellKnownSymbol = function (name) {
	  if (!has(WellKnownSymbolsStore, name)) {
	    if (nativeSymbol && has(Symbol$1, name)) WellKnownSymbolsStore[name] = Symbol$1[name];
	    else WellKnownSymbolsStore[name] = createWellKnownSymbol('Symbol.' + name);
	  } return WellKnownSymbolsStore[name];
	};

	var SPECIES = wellKnownSymbol('species');

	// `ArraySpeciesCreate` abstract operation
	// https://tc39.github.io/ecma262/#sec-arrayspeciescreate
	var arraySpeciesCreate = function (originalArray, length) {
	  var C;
	  if (isArray(originalArray)) {
	    C = originalArray.constructor;
	    // cross-realm fallback
	    if (typeof C == 'function' && (C === Array || isArray(C.prototype))) C = undefined;
	    else if (isObject(C)) {
	      C = C[SPECIES];
	      if (C === null) C = undefined;
	    }
	  } return new (C === undefined ? Array : C)(length === 0 ? 0 : length);
	};

	var push = [].push;

	// `Array.prototype.{ forEach, map, filter, some, every, find, findIndex }` methods implementation
	var createMethod$1 = function (TYPE) {
	  var IS_MAP = TYPE == 1;
	  var IS_FILTER = TYPE == 2;
	  var IS_SOME = TYPE == 3;
	  var IS_EVERY = TYPE == 4;
	  var IS_FIND_INDEX = TYPE == 6;
	  var NO_HOLES = TYPE == 5 || IS_FIND_INDEX;
	  return function ($this, callbackfn, that, specificCreate) {
	    var O = toObject($this);
	    var self = indexedObject(O);
	    var boundFunction = functionBindContext(callbackfn, that, 3);
	    var length = toLength(self.length);
	    var index = 0;
	    var create = specificCreate || arraySpeciesCreate;
	    var target = IS_MAP ? create($this, length) : IS_FILTER ? create($this, 0) : undefined;
	    var value, result;
	    for (;length > index; index++) if (NO_HOLES || index in self) {
	      value = self[index];
	      result = boundFunction(value, index, O);
	      if (TYPE) {
	        if (IS_MAP) target[index] = result; // map
	        else if (result) switch (TYPE) {
	          case 3: return true;              // some
	          case 5: return value;             // find
	          case 6: return index;             // findIndex
	          case 2: push.call(target, value); // filter
	        } else if (IS_EVERY) return false;  // every
	      }
	    }
	    return IS_FIND_INDEX ? -1 : IS_SOME || IS_EVERY ? IS_EVERY : target;
	  };
	};

	var arrayIteration = {
	  // `Array.prototype.forEach` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.foreach
	  forEach: createMethod$1(0),
	  // `Array.prototype.map` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.map
	  map: createMethod$1(1),
	  // `Array.prototype.filter` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.filter
	  filter: createMethod$1(2),
	  // `Array.prototype.some` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.some
	  some: createMethod$1(3),
	  // `Array.prototype.every` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.every
	  every: createMethod$1(4),
	  // `Array.prototype.find` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.find
	  find: createMethod$1(5),
	  // `Array.prototype.findIndex` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.findIndex
	  findIndex: createMethod$1(6)
	};

	var arrayMethodIsStrict = function (METHOD_NAME, argument) {
	  var method = [][METHOD_NAME];
	  return !!method && fails(function () {
	    // eslint-disable-next-line no-useless-call,no-throw-literal
	    method.call(null, argument || function () { throw 1; }, 1);
	  });
	};

	var defineProperty = Object.defineProperty;
	var cache = {};

	var thrower = function (it) { throw it; };

	var arrayMethodUsesToLength = function (METHOD_NAME, options) {
	  if (has(cache, METHOD_NAME)) return cache[METHOD_NAME];
	  if (!options) options = {};
	  var method = [][METHOD_NAME];
	  var ACCESSORS = has(options, 'ACCESSORS') ? options.ACCESSORS : false;
	  var argument0 = has(options, 0) ? options[0] : thrower;
	  var argument1 = has(options, 1) ? options[1] : undefined;

	  return cache[METHOD_NAME] = !!method && !fails(function () {
	    if (ACCESSORS && !descriptors) return true;
	    var O = { length: -1 };

	    if (ACCESSORS) defineProperty(O, 1, { enumerable: true, get: thrower });
	    else O[1] = 1;

	    method.call(O, argument0, argument1);
	  });
	};

	var $forEach = arrayIteration.forEach;



	var STRICT_METHOD = arrayMethodIsStrict('forEach');
	var USES_TO_LENGTH = arrayMethodUsesToLength('forEach');

	// `Array.prototype.forEach` method implementation
	// https://tc39.github.io/ecma262/#sec-array.prototype.foreach
	var arrayForEach = (!STRICT_METHOD || !USES_TO_LENGTH) ? function forEach(callbackfn /* , thisArg */) {
	  return $forEach(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
	} : [].forEach;

	// `Array.prototype.forEach` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.foreach
	_export({ target: 'Array', proto: true, forced: [].forEach != arrayForEach }, {
	  forEach: arrayForEach
	});

	var defineProperty$1 = objectDefineProperty.f;

	var FunctionPrototype = Function.prototype;
	var FunctionPrototypeToString = FunctionPrototype.toString;
	var nameRE = /^\s*function ([^ (]*)/;
	var NAME = 'name';

	// Function instances `.name` property
	// https://tc39.github.io/ecma262/#sec-function-instances-name
	if (descriptors && !(NAME in FunctionPrototype)) {
	  defineProperty$1(FunctionPrototype, NAME, {
	    configurable: true,
	    get: function () {
	      try {
	        return FunctionPrototypeToString.call(this).match(nameRE)[1];
	      } catch (error) {
	        return '';
	      }
	    }
	  });
	}

	// iterable DOM collections
	// flag - `iterable` interface - 'entries', 'keys', 'values', 'forEach' methods
	var domIterables = {
	  CSSRuleList: 0,
	  CSSStyleDeclaration: 0,
	  CSSValueList: 0,
	  ClientRectList: 0,
	  DOMRectList: 0,
	  DOMStringList: 0,
	  DOMTokenList: 1,
	  DataTransferItemList: 0,
	  FileList: 0,
	  HTMLAllCollection: 0,
	  HTMLCollection: 0,
	  HTMLFormElement: 0,
	  HTMLSelectElement: 0,
	  MediaList: 0,
	  MimeTypeArray: 0,
	  NamedNodeMap: 0,
	  NodeList: 1,
	  PaintRequestList: 0,
	  Plugin: 0,
	  PluginArray: 0,
	  SVGLengthList: 0,
	  SVGNumberList: 0,
	  SVGPathSegList: 0,
	  SVGPointList: 0,
	  SVGStringList: 0,
	  SVGTransformList: 0,
	  SourceBufferList: 0,
	  StyleSheetList: 0,
	  TextTrackCueList: 0,
	  TextTrackList: 0,
	  TouchList: 0
	};

	for (var COLLECTION_NAME in domIterables) {
	  var Collection = global_1[COLLECTION_NAME];
	  var CollectionPrototype = Collection && Collection.prototype;
	  // some Chrome versions have non-configurable methods on DOMTokenList
	  if (CollectionPrototype && CollectionPrototype.forEach !== arrayForEach) try {
	    createNonEnumerableProperty(CollectionPrototype, 'forEach', arrayForEach);
	  } catch (error) {
	    CollectionPrototype.forEach = arrayForEach;
	  }
	}

	// `Object.keys` method
	// https://tc39.github.io/ecma262/#sec-object.keys
	var objectKeys = Object.keys || function keys(O) {
	  return objectKeysInternal(O, enumBugKeys);
	};

	var propertyIsEnumerable = objectPropertyIsEnumerable.f;

	// `Object.{ entries, values }` methods implementation
	var createMethod$2 = function (TO_ENTRIES) {
	  return function (it) {
	    var O = toIndexedObject(it);
	    var keys = objectKeys(O);
	    var length = keys.length;
	    var i = 0;
	    var result = [];
	    var key;
	    while (length > i) {
	      key = keys[i++];
	      if (!descriptors || propertyIsEnumerable.call(O, key)) {
	        result.push(TO_ENTRIES ? [key, O[key]] : O[key]);
	      }
	    }
	    return result;
	  };
	};

	var objectToArray = {
	  // `Object.entries` method
	  // https://tc39.github.io/ecma262/#sec-object.entries
	  entries: createMethod$2(true),
	  // `Object.values` method
	  // https://tc39.github.io/ecma262/#sec-object.values
	  values: createMethod$2(false)
	};

	var $entries = objectToArray.entries;

	// `Object.entries` method
	// https://tc39.github.io/ecma262/#sec-object.entries
	_export({ target: 'Object', stat: true }, {
	  entries: function entries(O) {
	    return $entries(O);
	  }
	});

	function _arrayWithHoles(arr) {
	  if (Array.isArray(arr)) return arr;
	}

	var arrayWithHoles = _arrayWithHoles;

	function _iterableToArrayLimit(arr, i) {
	  if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return;
	  var _arr = [];
	  var _n = true;
	  var _d = false;
	  var _e = undefined;

	  try {
	    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
	      _arr.push(_s.value);

	      if (i && _arr.length === i) break;
	    }
	  } catch (err) {
	    _d = true;
	    _e = err;
	  } finally {
	    try {
	      if (!_n && _i["return"] != null) _i["return"]();
	    } finally {
	      if (_d) throw _e;
	    }
	  }

	  return _arr;
	}

	var iterableToArrayLimit = _iterableToArrayLimit;

	function _arrayLikeToArray(arr, len) {
	  if (len == null || len > arr.length) len = arr.length;

	  for (var i = 0, arr2 = new Array(len); i < len; i++) {
	    arr2[i] = arr[i];
	  }

	  return arr2;
	}

	var arrayLikeToArray = _arrayLikeToArray;

	function _unsupportedIterableToArray(o, minLen) {
	  if (!o) return;
	  if (typeof o === "string") return arrayLikeToArray(o, minLen);
	  var n = Object.prototype.toString.call(o).slice(8, -1);
	  if (n === "Object" && o.constructor) n = o.constructor.name;
	  if (n === "Map" || n === "Set") return Array.from(o);
	  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
	}

	var unsupportedIterableToArray = _unsupportedIterableToArray;

	function _nonIterableRest() {
	  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
	}

	var nonIterableRest = _nonIterableRest;

	function _slicedToArray(arr, i) {
	  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();
	}

	var slicedToArray = _slicedToArray;

	/**
	 * Return VF Gutenberg settings provided by the `vfGutenberg` global object
	 * `wp_localize_script` defines this when enqueueing "vf-blocks.js"
	 */
	// Default properties
	var vfGutenberg = {
	  renderPrefix: '',
	  renderSuffix: '',
	  postId: 0,
	  nonce: ''
	};

	var useVFGutenberg = function useVFGutenberg() {
	  var vf = window.vfGutenberg || {};

	  for (var _i = 0, _Object$entries = Object.entries(vfGutenberg); _i < _Object$entries.length; _i++) {
	    var _Object$entries$_i = slicedToArray(_Object$entries[_i], 2),
	        key = _Object$entries$_i[0],
	        value = _Object$entries$_i[1];

	    if (!vf.hasOwnProperty(key)) {
	      vf[key] = value;
	    }
	  }

	  return vf;
	};

	// `Object.defineProperties` method
	// https://tc39.github.io/ecma262/#sec-object.defineproperties
	var objectDefineProperties = descriptors ? Object.defineProperties : function defineProperties(O, Properties) {
	  anObject(O);
	  var keys = objectKeys(Properties);
	  var length = keys.length;
	  var index = 0;
	  var key;
	  while (length > index) objectDefineProperty.f(O, key = keys[index++], Properties[key]);
	  return O;
	};

	var html = getBuiltIn('document', 'documentElement');

	var GT = '>';
	var LT = '<';
	var PROTOTYPE = 'prototype';
	var SCRIPT = 'script';
	var IE_PROTO = sharedKey('IE_PROTO');

	var EmptyConstructor = function () { /* empty */ };

	var scriptTag = function (content) {
	  return LT + SCRIPT + GT + content + LT + '/' + SCRIPT + GT;
	};

	// Create object with fake `null` prototype: use ActiveX Object with cleared prototype
	var NullProtoObjectViaActiveX = function (activeXDocument) {
	  activeXDocument.write(scriptTag(''));
	  activeXDocument.close();
	  var temp = activeXDocument.parentWindow.Object;
	  activeXDocument = null; // avoid memory leak
	  return temp;
	};

	// Create object with fake `null` prototype: use iframe Object with cleared prototype
	var NullProtoObjectViaIFrame = function () {
	  // Thrash, waste and sodomy: IE GC bug
	  var iframe = documentCreateElement('iframe');
	  var JS = 'java' + SCRIPT + ':';
	  var iframeDocument;
	  iframe.style.display = 'none';
	  html.appendChild(iframe);
	  // https://github.com/zloirock/core-js/issues/475
	  iframe.src = String(JS);
	  iframeDocument = iframe.contentWindow.document;
	  iframeDocument.open();
	  iframeDocument.write(scriptTag('document.F=Object'));
	  iframeDocument.close();
	  return iframeDocument.F;
	};

	// Check for document.domain and active x support
	// No need to use active x approach when document.domain is not set
	// see https://github.com/es-shims/es5-shim/issues/150
	// variation of https://github.com/kitcambridge/es5-shim/commit/4f738ac066346
	// avoid IE GC bug
	var activeXDocument;
	var NullProtoObject = function () {
	  try {
	    /* global ActiveXObject */
	    activeXDocument = document.domain && new ActiveXObject('htmlfile');
	  } catch (error) { /* ignore */ }
	  NullProtoObject = activeXDocument ? NullProtoObjectViaActiveX(activeXDocument) : NullProtoObjectViaIFrame();
	  var length = enumBugKeys.length;
	  while (length--) delete NullProtoObject[PROTOTYPE][enumBugKeys[length]];
	  return NullProtoObject();
	};

	hiddenKeys[IE_PROTO] = true;

	// `Object.create` method
	// https://tc39.github.io/ecma262/#sec-object.create
	var objectCreate = Object.create || function create(O, Properties) {
	  var result;
	  if (O !== null) {
	    EmptyConstructor[PROTOTYPE] = anObject(O);
	    result = new EmptyConstructor();
	    EmptyConstructor[PROTOTYPE] = null;
	    // add "__proto__" for Object.getPrototypeOf polyfill
	    result[IE_PROTO] = O;
	  } else result = NullProtoObject();
	  return Properties === undefined ? result : objectDefineProperties(result, Properties);
	};

	var nativeGetOwnPropertyNames = objectGetOwnPropertyNames.f;

	var toString$1 = {}.toString;

	var windowNames = typeof window == 'object' && window && Object.getOwnPropertyNames
	  ? Object.getOwnPropertyNames(window) : [];

	var getWindowNames = function (it) {
	  try {
	    return nativeGetOwnPropertyNames(it);
	  } catch (error) {
	    return windowNames.slice();
	  }
	};

	// fallback for IE11 buggy Object.getOwnPropertyNames with iframe and window
	var f$5 = function getOwnPropertyNames(it) {
	  return windowNames && toString$1.call(it) == '[object Window]'
	    ? getWindowNames(it)
	    : nativeGetOwnPropertyNames(toIndexedObject(it));
	};

	var objectGetOwnPropertyNamesExternal = {
		f: f$5
	};

	var f$6 = wellKnownSymbol;

	var wellKnownSymbolWrapped = {
		f: f$6
	};

	var defineProperty$2 = objectDefineProperty.f;

	var defineWellKnownSymbol = function (NAME) {
	  var Symbol = path.Symbol || (path.Symbol = {});
	  if (!has(Symbol, NAME)) defineProperty$2(Symbol, NAME, {
	    value: wellKnownSymbolWrapped.f(NAME)
	  });
	};

	var defineProperty$3 = objectDefineProperty.f;



	var TO_STRING_TAG = wellKnownSymbol('toStringTag');

	var setToStringTag = function (it, TAG, STATIC) {
	  if (it && !has(it = STATIC ? it : it.prototype, TO_STRING_TAG)) {
	    defineProperty$3(it, TO_STRING_TAG, { configurable: true, value: TAG });
	  }
	};

	var $forEach$1 = arrayIteration.forEach;

	var HIDDEN = sharedKey('hidden');
	var SYMBOL = 'Symbol';
	var PROTOTYPE$1 = 'prototype';
	var TO_PRIMITIVE = wellKnownSymbol('toPrimitive');
	var setInternalState = internalState.set;
	var getInternalState = internalState.getterFor(SYMBOL);
	var ObjectPrototype = Object[PROTOTYPE$1];
	var $Symbol = global_1.Symbol;
	var $stringify = getBuiltIn('JSON', 'stringify');
	var nativeGetOwnPropertyDescriptor$1 = objectGetOwnPropertyDescriptor.f;
	var nativeDefineProperty$1 = objectDefineProperty.f;
	var nativeGetOwnPropertyNames$1 = objectGetOwnPropertyNamesExternal.f;
	var nativePropertyIsEnumerable$1 = objectPropertyIsEnumerable.f;
	var AllSymbols = shared('symbols');
	var ObjectPrototypeSymbols = shared('op-symbols');
	var StringToSymbolRegistry = shared('string-to-symbol-registry');
	var SymbolToStringRegistry = shared('symbol-to-string-registry');
	var WellKnownSymbolsStore$1 = shared('wks');
	var QObject = global_1.QObject;
	// Don't use setters in Qt Script, https://github.com/zloirock/core-js/issues/173
	var USE_SETTER = !QObject || !QObject[PROTOTYPE$1] || !QObject[PROTOTYPE$1].findChild;

	// fallback for old Android, https://code.google.com/p/v8/issues/detail?id=687
	var setSymbolDescriptor = descriptors && fails(function () {
	  return objectCreate(nativeDefineProperty$1({}, 'a', {
	    get: function () { return nativeDefineProperty$1(this, 'a', { value: 7 }).a; }
	  })).a != 7;
	}) ? function (O, P, Attributes) {
	  var ObjectPrototypeDescriptor = nativeGetOwnPropertyDescriptor$1(ObjectPrototype, P);
	  if (ObjectPrototypeDescriptor) delete ObjectPrototype[P];
	  nativeDefineProperty$1(O, P, Attributes);
	  if (ObjectPrototypeDescriptor && O !== ObjectPrototype) {
	    nativeDefineProperty$1(ObjectPrototype, P, ObjectPrototypeDescriptor);
	  }
	} : nativeDefineProperty$1;

	var wrap = function (tag, description) {
	  var symbol = AllSymbols[tag] = objectCreate($Symbol[PROTOTYPE$1]);
	  setInternalState(symbol, {
	    type: SYMBOL,
	    tag: tag,
	    description: description
	  });
	  if (!descriptors) symbol.description = description;
	  return symbol;
	};

	var isSymbol = useSymbolAsUid ? function (it) {
	  return typeof it == 'symbol';
	} : function (it) {
	  return Object(it) instanceof $Symbol;
	};

	var $defineProperty = function defineProperty(O, P, Attributes) {
	  if (O === ObjectPrototype) $defineProperty(ObjectPrototypeSymbols, P, Attributes);
	  anObject(O);
	  var key = toPrimitive(P, true);
	  anObject(Attributes);
	  if (has(AllSymbols, key)) {
	    if (!Attributes.enumerable) {
	      if (!has(O, HIDDEN)) nativeDefineProperty$1(O, HIDDEN, createPropertyDescriptor(1, {}));
	      O[HIDDEN][key] = true;
	    } else {
	      if (has(O, HIDDEN) && O[HIDDEN][key]) O[HIDDEN][key] = false;
	      Attributes = objectCreate(Attributes, { enumerable: createPropertyDescriptor(0, false) });
	    } return setSymbolDescriptor(O, key, Attributes);
	  } return nativeDefineProperty$1(O, key, Attributes);
	};

	var $defineProperties = function defineProperties(O, Properties) {
	  anObject(O);
	  var properties = toIndexedObject(Properties);
	  var keys = objectKeys(properties).concat($getOwnPropertySymbols(properties));
	  $forEach$1(keys, function (key) {
	    if (!descriptors || $propertyIsEnumerable.call(properties, key)) $defineProperty(O, key, properties[key]);
	  });
	  return O;
	};

	var $create = function create(O, Properties) {
	  return Properties === undefined ? objectCreate(O) : $defineProperties(objectCreate(O), Properties);
	};

	var $propertyIsEnumerable = function propertyIsEnumerable(V) {
	  var P = toPrimitive(V, true);
	  var enumerable = nativePropertyIsEnumerable$1.call(this, P);
	  if (this === ObjectPrototype && has(AllSymbols, P) && !has(ObjectPrototypeSymbols, P)) return false;
	  return enumerable || !has(this, P) || !has(AllSymbols, P) || has(this, HIDDEN) && this[HIDDEN][P] ? enumerable : true;
	};

	var $getOwnPropertyDescriptor = function getOwnPropertyDescriptor(O, P) {
	  var it = toIndexedObject(O);
	  var key = toPrimitive(P, true);
	  if (it === ObjectPrototype && has(AllSymbols, key) && !has(ObjectPrototypeSymbols, key)) return;
	  var descriptor = nativeGetOwnPropertyDescriptor$1(it, key);
	  if (descriptor && has(AllSymbols, key) && !(has(it, HIDDEN) && it[HIDDEN][key])) {
	    descriptor.enumerable = true;
	  }
	  return descriptor;
	};

	var $getOwnPropertyNames = function getOwnPropertyNames(O) {
	  var names = nativeGetOwnPropertyNames$1(toIndexedObject(O));
	  var result = [];
	  $forEach$1(names, function (key) {
	    if (!has(AllSymbols, key) && !has(hiddenKeys, key)) result.push(key);
	  });
	  return result;
	};

	var $getOwnPropertySymbols = function getOwnPropertySymbols(O) {
	  var IS_OBJECT_PROTOTYPE = O === ObjectPrototype;
	  var names = nativeGetOwnPropertyNames$1(IS_OBJECT_PROTOTYPE ? ObjectPrototypeSymbols : toIndexedObject(O));
	  var result = [];
	  $forEach$1(names, function (key) {
	    if (has(AllSymbols, key) && (!IS_OBJECT_PROTOTYPE || has(ObjectPrototype, key))) {
	      result.push(AllSymbols[key]);
	    }
	  });
	  return result;
	};

	// `Symbol` constructor
	// https://tc39.github.io/ecma262/#sec-symbol-constructor
	if (!nativeSymbol) {
	  $Symbol = function Symbol() {
	    if (this instanceof $Symbol) throw TypeError('Symbol is not a constructor');
	    var description = !arguments.length || arguments[0] === undefined ? undefined : String(arguments[0]);
	    var tag = uid(description);
	    var setter = function (value) {
	      if (this === ObjectPrototype) setter.call(ObjectPrototypeSymbols, value);
	      if (has(this, HIDDEN) && has(this[HIDDEN], tag)) this[HIDDEN][tag] = false;
	      setSymbolDescriptor(this, tag, createPropertyDescriptor(1, value));
	    };
	    if (descriptors && USE_SETTER) setSymbolDescriptor(ObjectPrototype, tag, { configurable: true, set: setter });
	    return wrap(tag, description);
	  };

	  redefine($Symbol[PROTOTYPE$1], 'toString', function toString() {
	    return getInternalState(this).tag;
	  });

	  redefine($Symbol, 'withoutSetter', function (description) {
	    return wrap(uid(description), description);
	  });

	  objectPropertyIsEnumerable.f = $propertyIsEnumerable;
	  objectDefineProperty.f = $defineProperty;
	  objectGetOwnPropertyDescriptor.f = $getOwnPropertyDescriptor;
	  objectGetOwnPropertyNames.f = objectGetOwnPropertyNamesExternal.f = $getOwnPropertyNames;
	  objectGetOwnPropertySymbols.f = $getOwnPropertySymbols;

	  wellKnownSymbolWrapped.f = function (name) {
	    return wrap(wellKnownSymbol(name), name);
	  };

	  if (descriptors) {
	    // https://github.com/tc39/proposal-Symbol-description
	    nativeDefineProperty$1($Symbol[PROTOTYPE$1], 'description', {
	      configurable: true,
	      get: function description() {
	        return getInternalState(this).description;
	      }
	    });
	    {
	      redefine(ObjectPrototype, 'propertyIsEnumerable', $propertyIsEnumerable, { unsafe: true });
	    }
	  }
	}

	_export({ global: true, wrap: true, forced: !nativeSymbol, sham: !nativeSymbol }, {
	  Symbol: $Symbol
	});

	$forEach$1(objectKeys(WellKnownSymbolsStore$1), function (name) {
	  defineWellKnownSymbol(name);
	});

	_export({ target: SYMBOL, stat: true, forced: !nativeSymbol }, {
	  // `Symbol.for` method
	  // https://tc39.github.io/ecma262/#sec-symbol.for
	  'for': function (key) {
	    var string = String(key);
	    if (has(StringToSymbolRegistry, string)) return StringToSymbolRegistry[string];
	    var symbol = $Symbol(string);
	    StringToSymbolRegistry[string] = symbol;
	    SymbolToStringRegistry[symbol] = string;
	    return symbol;
	  },
	  // `Symbol.keyFor` method
	  // https://tc39.github.io/ecma262/#sec-symbol.keyfor
	  keyFor: function keyFor(sym) {
	    if (!isSymbol(sym)) throw TypeError(sym + ' is not a symbol');
	    if (has(SymbolToStringRegistry, sym)) return SymbolToStringRegistry[sym];
	  },
	  useSetter: function () { USE_SETTER = true; },
	  useSimple: function () { USE_SETTER = false; }
	});

	_export({ target: 'Object', stat: true, forced: !nativeSymbol, sham: !descriptors }, {
	  // `Object.create` method
	  // https://tc39.github.io/ecma262/#sec-object.create
	  create: $create,
	  // `Object.defineProperty` method
	  // https://tc39.github.io/ecma262/#sec-object.defineproperty
	  defineProperty: $defineProperty,
	  // `Object.defineProperties` method
	  // https://tc39.github.io/ecma262/#sec-object.defineproperties
	  defineProperties: $defineProperties,
	  // `Object.getOwnPropertyDescriptor` method
	  // https://tc39.github.io/ecma262/#sec-object.getownpropertydescriptors
	  getOwnPropertyDescriptor: $getOwnPropertyDescriptor
	});

	_export({ target: 'Object', stat: true, forced: !nativeSymbol }, {
	  // `Object.getOwnPropertyNames` method
	  // https://tc39.github.io/ecma262/#sec-object.getownpropertynames
	  getOwnPropertyNames: $getOwnPropertyNames,
	  // `Object.getOwnPropertySymbols` method
	  // https://tc39.github.io/ecma262/#sec-object.getownpropertysymbols
	  getOwnPropertySymbols: $getOwnPropertySymbols
	});

	// Chrome 38 and 39 `Object.getOwnPropertySymbols` fails on primitives
	// https://bugs.chromium.org/p/v8/issues/detail?id=3443
	_export({ target: 'Object', stat: true, forced: fails(function () { objectGetOwnPropertySymbols.f(1); }) }, {
	  getOwnPropertySymbols: function getOwnPropertySymbols(it) {
	    return objectGetOwnPropertySymbols.f(toObject(it));
	  }
	});

	// `JSON.stringify` method behavior with symbols
	// https://tc39.github.io/ecma262/#sec-json.stringify
	if ($stringify) {
	  var FORCED_JSON_STRINGIFY = !nativeSymbol || fails(function () {
	    var symbol = $Symbol();
	    // MS Edge converts symbol values to JSON as {}
	    return $stringify([symbol]) != '[null]'
	      // WebKit converts symbol values to JSON as null
	      || $stringify({ a: symbol }) != '{}'
	      // V8 throws on boxed symbols
	      || $stringify(Object(symbol)) != '{}';
	  });

	  _export({ target: 'JSON', stat: true, forced: FORCED_JSON_STRINGIFY }, {
	    // eslint-disable-next-line no-unused-vars
	    stringify: function stringify(it, replacer, space) {
	      var args = [it];
	      var index = 1;
	      var $replacer;
	      while (arguments.length > index) args.push(arguments[index++]);
	      $replacer = replacer;
	      if (!isObject(replacer) && it === undefined || isSymbol(it)) return; // IE8 returns string on undefined
	      if (!isArray(replacer)) replacer = function (key, value) {
	        if (typeof $replacer == 'function') value = $replacer.call(this, key, value);
	        if (!isSymbol(value)) return value;
	      };
	      args[1] = replacer;
	      return $stringify.apply(null, args);
	    }
	  });
	}

	// `Symbol.prototype[@@toPrimitive]` method
	// https://tc39.github.io/ecma262/#sec-symbol.prototype-@@toprimitive
	if (!$Symbol[PROTOTYPE$1][TO_PRIMITIVE]) {
	  createNonEnumerableProperty($Symbol[PROTOTYPE$1], TO_PRIMITIVE, $Symbol[PROTOTYPE$1].valueOf);
	}
	// `Symbol.prototype[@@toStringTag]` property
	// https://tc39.github.io/ecma262/#sec-symbol.prototype-@@tostringtag
	setToStringTag($Symbol, SYMBOL);

	hiddenKeys[HIDDEN] = true;

	var createProperty = function (object, key, value) {
	  var propertyKey = toPrimitive(key);
	  if (propertyKey in object) objectDefineProperty.f(object, propertyKey, createPropertyDescriptor(0, value));
	  else object[propertyKey] = value;
	};

	var engineUserAgent = getBuiltIn('navigator', 'userAgent') || '';

	var process$1 = global_1.process;
	var versions = process$1 && process$1.versions;
	var v8 = versions && versions.v8;
	var match, version;

	if (v8) {
	  match = v8.split('.');
	  version = match[0] + match[1];
	} else if (engineUserAgent) {
	  match = engineUserAgent.match(/Edge\/(\d+)/);
	  if (!match || match[1] >= 74) {
	    match = engineUserAgent.match(/Chrome\/(\d+)/);
	    if (match) version = match[1];
	  }
	}

	var engineV8Version = version && +version;

	var SPECIES$1 = wellKnownSymbol('species');

	var arrayMethodHasSpeciesSupport = function (METHOD_NAME) {
	  // We can't use this feature detection in V8 since it causes
	  // deoptimization and serious performance degradation
	  // https://github.com/zloirock/core-js/issues/677
	  return engineV8Version >= 51 || !fails(function () {
	    var array = [];
	    var constructor = array.constructor = {};
	    constructor[SPECIES$1] = function () {
	      return { foo: 1 };
	    };
	    return array[METHOD_NAME](Boolean).foo !== 1;
	  });
	};

	var IS_CONCAT_SPREADABLE = wellKnownSymbol('isConcatSpreadable');
	var MAX_SAFE_INTEGER = 0x1FFFFFFFFFFFFF;
	var MAXIMUM_ALLOWED_INDEX_EXCEEDED = 'Maximum allowed index exceeded';

	// We can't use this feature detection in V8 since it causes
	// deoptimization and serious performance degradation
	// https://github.com/zloirock/core-js/issues/679
	var IS_CONCAT_SPREADABLE_SUPPORT = engineV8Version >= 51 || !fails(function () {
	  var array = [];
	  array[IS_CONCAT_SPREADABLE] = false;
	  return array.concat()[0] !== array;
	});

	var SPECIES_SUPPORT = arrayMethodHasSpeciesSupport('concat');

	var isConcatSpreadable = function (O) {
	  if (!isObject(O)) return false;
	  var spreadable = O[IS_CONCAT_SPREADABLE];
	  return spreadable !== undefined ? !!spreadable : isArray(O);
	};

	var FORCED = !IS_CONCAT_SPREADABLE_SUPPORT || !SPECIES_SUPPORT;

	// `Array.prototype.concat` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.concat
	// with adding support of @@isConcatSpreadable and @@species
	_export({ target: 'Array', proto: true, forced: FORCED }, {
	  concat: function concat(arg) { // eslint-disable-line no-unused-vars
	    var O = toObject(this);
	    var A = arraySpeciesCreate(O, 0);
	    var n = 0;
	    var i, k, length, len, E;
	    for (i = -1, length = arguments.length; i < length; i++) {
	      E = i === -1 ? O : arguments[i];
	      if (isConcatSpreadable(E)) {
	        len = toLength(E.length);
	        if (n + len > MAX_SAFE_INTEGER) throw TypeError(MAXIMUM_ALLOWED_INDEX_EXCEEDED);
	        for (k = 0; k < len; k++, n++) if (k in E) createProperty(A, n, E[k]);
	      } else {
	        if (n >= MAX_SAFE_INTEGER) throw TypeError(MAXIMUM_ALLOWED_INDEX_EXCEEDED);
	        createProperty(A, n++, E);
	      }
	    }
	    A.length = n;
	    return A;
	  }
	});

	var $filter = arrayIteration.filter;



	var HAS_SPECIES_SUPPORT = arrayMethodHasSpeciesSupport('filter');
	// Edge 14- issue
	var USES_TO_LENGTH$1 = arrayMethodUsesToLength('filter');

	// `Array.prototype.filter` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.filter
	// with adding support of @@species
	_export({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT || !USES_TO_LENGTH$1 }, {
	  filter: function filter(callbackfn /* , thisArg */) {
	    return $filter(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	var HAS_SPECIES_SUPPORT$1 = arrayMethodHasSpeciesSupport('slice');
	var USES_TO_LENGTH$2 = arrayMethodUsesToLength('slice', { ACCESSORS: true, 0: 0, 1: 2 });

	var SPECIES$2 = wellKnownSymbol('species');
	var nativeSlice = [].slice;
	var max$1 = Math.max;

	// `Array.prototype.slice` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.slice
	// fallback for not array-like ES3 strings and DOM objects
	_export({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT$1 || !USES_TO_LENGTH$2 }, {
	  slice: function slice(start, end) {
	    var O = toIndexedObject(this);
	    var length = toLength(O.length);
	    var k = toAbsoluteIndex(start, length);
	    var fin = toAbsoluteIndex(end === undefined ? length : end, length);
	    // inline `ArraySpeciesCreate` for usage native `Array#slice` where it's possible
	    var Constructor, result, n;
	    if (isArray(O)) {
	      Constructor = O.constructor;
	      // cross-realm fallback
	      if (typeof Constructor == 'function' && (Constructor === Array || isArray(Constructor.prototype))) {
	        Constructor = undefined;
	      } else if (isObject(Constructor)) {
	        Constructor = Constructor[SPECIES$2];
	        if (Constructor === null) Constructor = undefined;
	      }
	      if (Constructor === Array || Constructor === undefined) {
	        return nativeSlice.call(O, k, fin);
	      }
	    }
	    result = new (Constructor === undefined ? Array : Constructor)(max$1(fin - k, 0));
	    for (n = 0; k < fin; k++, n++) if (k in O) createProperty(result, n, O[k]);
	    result.length = n;
	    return result;
	  }
	});

	var nativeGetOwnPropertyDescriptor$2 = objectGetOwnPropertyDescriptor.f;


	var FAILS_ON_PRIMITIVES = fails(function () { nativeGetOwnPropertyDescriptor$2(1); });
	var FORCED$1 = !descriptors || FAILS_ON_PRIMITIVES;

	// `Object.getOwnPropertyDescriptor` method
	// https://tc39.github.io/ecma262/#sec-object.getownpropertydescriptor
	_export({ target: 'Object', stat: true, forced: FORCED$1, sham: !descriptors }, {
	  getOwnPropertyDescriptor: function getOwnPropertyDescriptor(it, key) {
	    return nativeGetOwnPropertyDescriptor$2(toIndexedObject(it), key);
	  }
	});

	// `Object.getOwnPropertyDescriptors` method
	// https://tc39.github.io/ecma262/#sec-object.getownpropertydescriptors
	_export({ target: 'Object', stat: true, sham: !descriptors }, {
	  getOwnPropertyDescriptors: function getOwnPropertyDescriptors(object) {
	    var O = toIndexedObject(object);
	    var getOwnPropertyDescriptor = objectGetOwnPropertyDescriptor.f;
	    var keys = ownKeys(O);
	    var result = {};
	    var index = 0;
	    var key, descriptor;
	    while (keys.length > index) {
	      descriptor = getOwnPropertyDescriptor(O, key = keys[index++]);
	      if (descriptor !== undefined) createProperty(result, key, descriptor);
	    }
	    return result;
	  }
	});

	var FAILS_ON_PRIMITIVES$1 = fails(function () { objectKeys(1); });

	// `Object.keys` method
	// https://tc39.github.io/ecma262/#sec-object.keys
	_export({ target: 'Object', stat: true, forced: FAILS_ON_PRIMITIVES$1 }, {
	  keys: function keys(it) {
	    return objectKeys(toObject(it));
	  }
	});

	function _arrayWithoutHoles(arr) {
	  if (Array.isArray(arr)) return arrayLikeToArray(arr);
	}

	var arrayWithoutHoles = _arrayWithoutHoles;

	function _iterableToArray(iter) {
	  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
	}

	var iterableToArray = _iterableToArray;

	function _nonIterableSpread() {
	  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
	}

	var nonIterableSpread = _nonIterableSpread;

	function _toConsumableArray(arr) {
	  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
	}

	var toConsumableArray = _toConsumableArray;

	function _toArray(arr) {
	  return arrayWithHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableRest();
	}

	var toArray = _toArray;

	var _extends_1 = createCommonjsModule(function (module) {
	function _extends() {
	  module.exports = _extends = Object.assign || function (target) {
	    for (var i = 1; i < arguments.length; i++) {
	      var source = arguments[i];

	      for (var key in source) {
	        if (Object.prototype.hasOwnProperty.call(source, key)) {
	          target[key] = source[key];
	        }
	      }
	    }

	    return target;
	  };

	  return _extends.apply(this, arguments);
	}

	module.exports = _extends;
	});

	function _defineProperty(obj, key, value) {
	  if (key in obj) {
	    Object.defineProperty(obj, key, {
	      value: value,
	      enumerable: true,
	      configurable: true,
	      writable: true
	    });
	  } else {
	    obj[key] = value;
	  }

	  return obj;
	}

	var defineProperty$4 = _defineProperty;

	/**
	 * Icon (component)
	 * VF Gutenberg block icon
	 */
	var Icon = wp.element.createElement(components.SVG, {
	  viewBox: "0 0 24 24",
	  xmlns: "http://www.w3.org/2000/svg"
	}, wp.element.createElement(components.Path, {
	  d: "M19 7h-1V5h-4v2h-4V5H6v2H5c-1.1 0-2 .9-2 2v10h18V9c0-1.1-.9-2-2-2zm0 10H5V9h14v8z"
	}), wp.element.createElement(components.Path, {
	  d: "M10.943 16c.522-1.854.972-3.726 1.386-5.58h-1.323a126.482 126.482 0 01-.918 4.383h-.081c-.342-1.44-.657-2.988-.936-4.428l-1.35.09c.414 1.854.873 3.681 1.395 5.535h1.827zm5.161-2.169H14.16V16h-1.332v-5.58h3.636v1.098H14.16v1.296h1.944v1.017z"
	}));

	/**
	 * Return default Gutenberg block settings for a VF block
	 */

	var useVFDefaults = function useVFDefaults() {
	  return {
	    icon: Icon,
	    keywords: [i18n.__('VF'), i18n.__('Visual Framework'), i18n.__('EMBL')],
	    attributes: {
	      ver: {
	        type: 'string'
	      }
	    },
	    supports: {
	      align: false,
	      className: false,
	      customClassName: false,
	      html: false
	    },
	    edit: function edit() {
	      return null;
	    },
	    save: function save() {
	      return null;
	    }
	  };
	};

	var UNSCOPABLES = wellKnownSymbol('unscopables');
	var ArrayPrototype = Array.prototype;

	// Array.prototype[@@unscopables]
	// https://tc39.github.io/ecma262/#sec-array.prototype-@@unscopables
	if (ArrayPrototype[UNSCOPABLES] == undefined) {
	  objectDefineProperty.f(ArrayPrototype, UNSCOPABLES, {
	    configurable: true,
	    value: objectCreate(null)
	  });
	}

	// add a key to Array.prototype[@@unscopables]
	var addToUnscopables = function (key) {
	  ArrayPrototype[UNSCOPABLES][key] = true;
	};

	var $includes = arrayIncludes.includes;



	var USES_TO_LENGTH$3 = arrayMethodUsesToLength('indexOf', { ACCESSORS: true, 1: 0 });

	// `Array.prototype.includes` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.includes
	_export({ target: 'Array', proto: true, forced: !USES_TO_LENGTH$3 }, {
	  includes: function includes(el /* , fromIndex = 0 */) {
	    return $includes(this, el, arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	// https://tc39.github.io/ecma262/#sec-array.prototype-@@unscopables
	addToUnscopables('includes');

	var $map = arrayIteration.map;



	var HAS_SPECIES_SUPPORT$2 = arrayMethodHasSpeciesSupport('map');
	// FF49- issue
	var USES_TO_LENGTH$4 = arrayMethodUsesToLength('map');

	// `Array.prototype.map` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.map
	// with adding support of @@species
	_export({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT$2 || !USES_TO_LENGTH$4 }, {
	  map: function map(callbackfn /* , thisArg */) {
	    return $map(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	// `RegExp.prototype.flags` getter implementation
	// https://tc39.github.io/ecma262/#sec-get-regexp.prototype.flags
	var regexpFlags = function () {
	  var that = anObject(this);
	  var result = '';
	  if (that.global) result += 'g';
	  if (that.ignoreCase) result += 'i';
	  if (that.multiline) result += 'm';
	  if (that.dotAll) result += 's';
	  if (that.unicode) result += 'u';
	  if (that.sticky) result += 'y';
	  return result;
	};

	// babel-minify transpiles RegExp('a', 'y') -> /a/y and it causes SyntaxError,
	// so we use an intermediate function.
	function RE(s, f) {
	  return RegExp(s, f);
	}

	var UNSUPPORTED_Y = fails(function () {
	  // babel-minify transpiles RegExp('a', 'y') -> /a/y and it causes SyntaxError
	  var re = RE('a', 'y');
	  re.lastIndex = 2;
	  return re.exec('abcd') != null;
	});

	var BROKEN_CARET = fails(function () {
	  // https://bugzilla.mozilla.org/show_bug.cgi?id=773687
	  var re = RE('^r', 'gy');
	  re.lastIndex = 2;
	  return re.exec('str') != null;
	});

	var regexpStickyHelpers = {
		UNSUPPORTED_Y: UNSUPPORTED_Y,
		BROKEN_CARET: BROKEN_CARET
	};

	var nativeExec = RegExp.prototype.exec;
	// This always refers to the native implementation, because the
	// String#replace polyfill uses ./fix-regexp-well-known-symbol-logic.js,
	// which loads this file before patching the method.
	var nativeReplace = String.prototype.replace;

	var patchedExec = nativeExec;

	var UPDATES_LAST_INDEX_WRONG = (function () {
	  var re1 = /a/;
	  var re2 = /b*/g;
	  nativeExec.call(re1, 'a');
	  nativeExec.call(re2, 'a');
	  return re1.lastIndex !== 0 || re2.lastIndex !== 0;
	})();

	var UNSUPPORTED_Y$1 = regexpStickyHelpers.UNSUPPORTED_Y || regexpStickyHelpers.BROKEN_CARET;

	// nonparticipating capturing group, copied from es5-shim's String#split patch.
	var NPCG_INCLUDED = /()??/.exec('')[1] !== undefined;

	var PATCH = UPDATES_LAST_INDEX_WRONG || NPCG_INCLUDED || UNSUPPORTED_Y$1;

	if (PATCH) {
	  patchedExec = function exec(str) {
	    var re = this;
	    var lastIndex, reCopy, match, i;
	    var sticky = UNSUPPORTED_Y$1 && re.sticky;
	    var flags = regexpFlags.call(re);
	    var source = re.source;
	    var charsAdded = 0;
	    var strCopy = str;

	    if (sticky) {
	      flags = flags.replace('y', '');
	      if (flags.indexOf('g') === -1) {
	        flags += 'g';
	      }

	      strCopy = String(str).slice(re.lastIndex);
	      // Support anchored sticky behavior.
	      if (re.lastIndex > 0 && (!re.multiline || re.multiline && str[re.lastIndex - 1] !== '\n')) {
	        source = '(?: ' + source + ')';
	        strCopy = ' ' + strCopy;
	        charsAdded++;
	      }
	      // ^(? + rx + ) is needed, in combination with some str slicing, to
	      // simulate the 'y' flag.
	      reCopy = new RegExp('^(?:' + source + ')', flags);
	    }

	    if (NPCG_INCLUDED) {
	      reCopy = new RegExp('^' + source + '$(?!\\s)', flags);
	    }
	    if (UPDATES_LAST_INDEX_WRONG) lastIndex = re.lastIndex;

	    match = nativeExec.call(sticky ? reCopy : re, strCopy);

	    if (sticky) {
	      if (match) {
	        match.input = match.input.slice(charsAdded);
	        match[0] = match[0].slice(charsAdded);
	        match.index = re.lastIndex;
	        re.lastIndex += match[0].length;
	      } else re.lastIndex = 0;
	    } else if (UPDATES_LAST_INDEX_WRONG && match) {
	      re.lastIndex = re.global ? match.index + match[0].length : lastIndex;
	    }
	    if (NPCG_INCLUDED && match && match.length > 1) {
	      // Fix browsers whose `exec` methods don't consistently return `undefined`
	      // for NPCG, like IE8. NOTE: This doesn' work for /(.?)?/
	      nativeReplace.call(match[0], reCopy, function () {
	        for (i = 1; i < arguments.length - 2; i++) {
	          if (arguments[i] === undefined) match[i] = undefined;
	        }
	      });
	    }

	    return match;
	  };
	}

	var regexpExec = patchedExec;

	_export({ target: 'RegExp', proto: true, forced: /./.exec !== regexpExec }, {
	  exec: regexpExec
	});

	// TODO: Remove from `core-js@4` since it's moved to entry points







	var SPECIES$3 = wellKnownSymbol('species');

	var REPLACE_SUPPORTS_NAMED_GROUPS = !fails(function () {
	  // #replace needs built-in support for named groups.
	  // #match works fine because it just return the exec results, even if it has
	  // a "grops" property.
	  var re = /./;
	  re.exec = function () {
	    var result = [];
	    result.groups = { a: '7' };
	    return result;
	  };
	  return ''.replace(re, '$<a>') !== '7';
	});

	// IE <= 11 replaces $0 with the whole match, as if it was $&
	// https://stackoverflow.com/questions/6024666/getting-ie-to-replace-a-regex-with-the-literal-string-0
	var REPLACE_KEEPS_$0 = (function () {
	  return 'a'.replace(/./, '$0') === '$0';
	})();

	var REPLACE = wellKnownSymbol('replace');
	// Safari <= 13.0.3(?) substitutes nth capture where n>m with an empty string
	var REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE = (function () {
	  if (/./[REPLACE]) {
	    return /./[REPLACE]('a', '$0') === '';
	  }
	  return false;
	})();

	// Chrome 51 has a buggy "split" implementation when RegExp#exec !== nativeExec
	// Weex JS has frozen built-in prototypes, so use try / catch wrapper
	var SPLIT_WORKS_WITH_OVERWRITTEN_EXEC = !fails(function () {
	  var re = /(?:)/;
	  var originalExec = re.exec;
	  re.exec = function () { return originalExec.apply(this, arguments); };
	  var result = 'ab'.split(re);
	  return result.length !== 2 || result[0] !== 'a' || result[1] !== 'b';
	});

	var fixRegexpWellKnownSymbolLogic = function (KEY, length, exec, sham) {
	  var SYMBOL = wellKnownSymbol(KEY);

	  var DELEGATES_TO_SYMBOL = !fails(function () {
	    // String methods call symbol-named RegEp methods
	    var O = {};
	    O[SYMBOL] = function () { return 7; };
	    return ''[KEY](O) != 7;
	  });

	  var DELEGATES_TO_EXEC = DELEGATES_TO_SYMBOL && !fails(function () {
	    // Symbol-named RegExp methods call .exec
	    var execCalled = false;
	    var re = /a/;

	    if (KEY === 'split') {
	      // We can't use real regex here since it causes deoptimization
	      // and serious performance degradation in V8
	      // https://github.com/zloirock/core-js/issues/306
	      re = {};
	      // RegExp[@@split] doesn't call the regex's exec method, but first creates
	      // a new one. We need to return the patched regex when creating the new one.
	      re.constructor = {};
	      re.constructor[SPECIES$3] = function () { return re; };
	      re.flags = '';
	      re[SYMBOL] = /./[SYMBOL];
	    }

	    re.exec = function () { execCalled = true; return null; };

	    re[SYMBOL]('');
	    return !execCalled;
	  });

	  if (
	    !DELEGATES_TO_SYMBOL ||
	    !DELEGATES_TO_EXEC ||
	    (KEY === 'replace' && !(
	      REPLACE_SUPPORTS_NAMED_GROUPS &&
	      REPLACE_KEEPS_$0 &&
	      !REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE
	    )) ||
	    (KEY === 'split' && !SPLIT_WORKS_WITH_OVERWRITTEN_EXEC)
	  ) {
	    var nativeRegExpMethod = /./[SYMBOL];
	    var methods = exec(SYMBOL, ''[KEY], function (nativeMethod, regexp, str, arg2, forceStringMethod) {
	      if (regexp.exec === regexpExec) {
	        if (DELEGATES_TO_SYMBOL && !forceStringMethod) {
	          // The native String method already delegates to @@method (this
	          // polyfilled function), leasing to infinite recursion.
	          // We avoid it by directly calling the native @@method method.
	          return { done: true, value: nativeRegExpMethod.call(regexp, str, arg2) };
	        }
	        return { done: true, value: nativeMethod.call(str, regexp, arg2) };
	      }
	      return { done: false };
	    }, {
	      REPLACE_KEEPS_$0: REPLACE_KEEPS_$0,
	      REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE: REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE
	    });
	    var stringMethod = methods[0];
	    var regexMethod = methods[1];

	    redefine(String.prototype, KEY, stringMethod);
	    redefine(RegExp.prototype, SYMBOL, length == 2
	      // 21.2.5.8 RegExp.prototype[@@replace](string, replaceValue)
	      // 21.2.5.11 RegExp.prototype[@@split](string, limit)
	      ? function (string, arg) { return regexMethod.call(string, this, arg); }
	      // 21.2.5.6 RegExp.prototype[@@match](string)
	      // 21.2.5.9 RegExp.prototype[@@search](string)
	      : function (string) { return regexMethod.call(string, this); }
	    );
	  }

	  if (sham) createNonEnumerableProperty(RegExp.prototype[SYMBOL], 'sham', true);
	};

	// `String.prototype.{ codePointAt, at }` methods implementation
	var createMethod$3 = function (CONVERT_TO_STRING) {
	  return function ($this, pos) {
	    var S = String(requireObjectCoercible($this));
	    var position = toInteger(pos);
	    var size = S.length;
	    var first, second;
	    if (position < 0 || position >= size) return CONVERT_TO_STRING ? '' : undefined;
	    first = S.charCodeAt(position);
	    return first < 0xD800 || first > 0xDBFF || position + 1 === size
	      || (second = S.charCodeAt(position + 1)) < 0xDC00 || second > 0xDFFF
	        ? CONVERT_TO_STRING ? S.charAt(position) : first
	        : CONVERT_TO_STRING ? S.slice(position, position + 2) : (first - 0xD800 << 10) + (second - 0xDC00) + 0x10000;
	  };
	};

	var stringMultibyte = {
	  // `String.prototype.codePointAt` method
	  // https://tc39.github.io/ecma262/#sec-string.prototype.codepointat
	  codeAt: createMethod$3(false),
	  // `String.prototype.at` method
	  // https://github.com/mathiasbynens/String.prototype.at
	  charAt: createMethod$3(true)
	};

	var charAt = stringMultibyte.charAt;

	// `AdvanceStringIndex` abstract operation
	// https://tc39.github.io/ecma262/#sec-advancestringindex
	var advanceStringIndex = function (S, index, unicode) {
	  return index + (unicode ? charAt(S, index).length : 1);
	};

	// `RegExpExec` abstract operation
	// https://tc39.github.io/ecma262/#sec-regexpexec
	var regexpExecAbstract = function (R, S) {
	  var exec = R.exec;
	  if (typeof exec === 'function') {
	    var result = exec.call(R, S);
	    if (typeof result !== 'object') {
	      throw TypeError('RegExp exec method returned something other than an Object or null');
	    }
	    return result;
	  }

	  if (classofRaw(R) !== 'RegExp') {
	    throw TypeError('RegExp#exec called on incompatible receiver');
	  }

	  return regexpExec.call(R, S);
	};

	// @@match logic
	fixRegexpWellKnownSymbolLogic('match', 1, function (MATCH, nativeMatch, maybeCallNative) {
	  return [
	    // `String.prototype.match` method
	    // https://tc39.github.io/ecma262/#sec-string.prototype.match
	    function match(regexp) {
	      var O = requireObjectCoercible(this);
	      var matcher = regexp == undefined ? undefined : regexp[MATCH];
	      return matcher !== undefined ? matcher.call(regexp, O) : new RegExp(regexp)[MATCH](String(O));
	    },
	    // `RegExp.prototype[@@match]` method
	    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@match
	    function (regexp) {
	      var res = maybeCallNative(nativeMatch, regexp, this);
	      if (res.done) return res.value;

	      var rx = anObject(regexp);
	      var S = String(this);

	      if (!rx.global) return regexpExecAbstract(rx, S);

	      var fullUnicode = rx.unicode;
	      rx.lastIndex = 0;
	      var A = [];
	      var n = 0;
	      var result;
	      while ((result = regexpExecAbstract(rx, S)) !== null) {
	        var matchStr = String(result[0]);
	        A[n] = matchStr;
	        if (matchStr === '') rx.lastIndex = advanceStringIndex(S, toLength(rx.lastIndex), fullUnicode);
	        n++;
	      }
	      return n === 0 ? null : A;
	    }
	  ];
	});

	var $indexOf = arrayIncludes.indexOf;



	var nativeIndexOf = [].indexOf;

	var NEGATIVE_ZERO = !!nativeIndexOf && 1 / [1].indexOf(1, -0) < 0;
	var STRICT_METHOD$1 = arrayMethodIsStrict('indexOf');
	var USES_TO_LENGTH$5 = arrayMethodUsesToLength('indexOf', { ACCESSORS: true, 1: 0 });

	// `Array.prototype.indexOf` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.indexof
	_export({ target: 'Array', proto: true, forced: NEGATIVE_ZERO || !STRICT_METHOD$1 || !USES_TO_LENGTH$5 }, {
	  indexOf: function indexOf(searchElement /* , fromIndex = 0 */) {
	    return NEGATIVE_ZERO
	      // convert -0 to +0
	      ? nativeIndexOf.apply(this, arguments) || 0
	      : $indexOf(this, searchElement, arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	// `Array.prototype.{ reduce, reduceRight }` methods implementation
	var createMethod$4 = function (IS_RIGHT) {
	  return function (that, callbackfn, argumentsLength, memo) {
	    aFunction$1(callbackfn);
	    var O = toObject(that);
	    var self = indexedObject(O);
	    var length = toLength(O.length);
	    var index = IS_RIGHT ? length - 1 : 0;
	    var i = IS_RIGHT ? -1 : 1;
	    if (argumentsLength < 2) while (true) {
	      if (index in self) {
	        memo = self[index];
	        index += i;
	        break;
	      }
	      index += i;
	      if (IS_RIGHT ? index < 0 : length <= index) {
	        throw TypeError('Reduce of empty array with no initial value');
	      }
	    }
	    for (;IS_RIGHT ? index >= 0 : length > index; index += i) if (index in self) {
	      memo = callbackfn(memo, self[index], index, O);
	    }
	    return memo;
	  };
	};

	var arrayReduce = {
	  // `Array.prototype.reduce` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.reduce
	  left: createMethod$4(false),
	  // `Array.prototype.reduceRight` method
	  // https://tc39.github.io/ecma262/#sec-array.prototype.reduceright
	  right: createMethod$4(true)
	};

	var $reduce = arrayReduce.left;



	var STRICT_METHOD$2 = arrayMethodIsStrict('reduce');
	var USES_TO_LENGTH$6 = arrayMethodUsesToLength('reduce', { 1: 0 });

	// `Array.prototype.reduce` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.reduce
	_export({ target: 'Array', proto: true, forced: !STRICT_METHOD$2 || !USES_TO_LENGTH$6 }, {
	  reduce: function reduce(callbackfn /* , initialValue */) {
	    return $reduce(this, callbackfn, arguments.length, arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	var TO_STRING_TAG$1 = wellKnownSymbol('toStringTag');
	var test = {};

	test[TO_STRING_TAG$1] = 'z';

	var toStringTagSupport = String(test) === '[object z]';

	var TO_STRING_TAG$2 = wellKnownSymbol('toStringTag');
	// ES3 wrong here
	var CORRECT_ARGUMENTS = classofRaw(function () { return arguments; }()) == 'Arguments';

	// fallback for IE11 Script Access Denied error
	var tryGet = function (it, key) {
	  try {
	    return it[key];
	  } catch (error) { /* empty */ }
	};

	// getting tag from ES6+ `Object.prototype.toString`
	var classof = toStringTagSupport ? classofRaw : function (it) {
	  var O, tag, result;
	  return it === undefined ? 'Undefined' : it === null ? 'Null'
	    // @@toStringTag case
	    : typeof (tag = tryGet(O = Object(it), TO_STRING_TAG$2)) == 'string' ? tag
	    // builtinTag case
	    : CORRECT_ARGUMENTS ? classofRaw(O)
	    // ES3 arguments fallback
	    : (result = classofRaw(O)) == 'Object' && typeof O.callee == 'function' ? 'Arguments' : result;
	};

	// `Object.prototype.toString` method implementation
	// https://tc39.github.io/ecma262/#sec-object.prototype.tostring
	var objectToString = toStringTagSupport ? {}.toString : function toString() {
	  return '[object ' + classof(this) + ']';
	};

	// `Object.prototype.toString` method
	// https://tc39.github.io/ecma262/#sec-object.prototype.tostring
	if (!toStringTagSupport) {
	  redefine(Object.prototype, 'toString', objectToString, { unsafe: true });
	}

	var TO_STRING = 'toString';
	var RegExpPrototype = RegExp.prototype;
	var nativeToString = RegExpPrototype[TO_STRING];

	var NOT_GENERIC = fails(function () { return nativeToString.call({ source: 'a', flags: 'b' }) != '/a/b'; });
	// FF44- RegExp#toString has a wrong name
	var INCORRECT_NAME = nativeToString.name != TO_STRING;

	// `RegExp.prototype.toString` method
	// https://tc39.github.io/ecma262/#sec-regexp.prototype.tostring
	if (NOT_GENERIC || INCORRECT_NAME) {
	  redefine(RegExp.prototype, TO_STRING, function toString() {
	    var R = anObject(this);
	    var p = String(R.source);
	    var rf = R.flags;
	    var f = String(rf === undefined && R instanceof RegExp && !('flags' in RegExpPrototype) ? regexpFlags.call(R) : rf);
	    return '/' + p + '/' + f;
	  }, { unsafe: true });
	}

	var _typeof_1 = createCommonjsModule(function (module) {
	function _typeof(obj) {
	  "@babel/helpers - typeof";

	  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
	    module.exports = _typeof = function _typeof(obj) {
	      return typeof obj;
	    };
	  } else {
	    module.exports = _typeof = function _typeof(obj) {
	      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
	    };
	  }

	  return _typeof(obj);
	}

	module.exports = _typeof;
	});

	/**
	 * Object Hashsum
	 * https://github.com/bevacqua/hash-sum
	 */
	function pad(hash, len) {
	  while (hash.length < len) {
	    hash = '0' + hash;
	  }

	  return hash;
	}

	function fold(hash, text) {
	  var i;
	  var chr;
	  var len;

	  if (text.length === 0) {
	    return hash;
	  }

	  for (i = 0, len = text.length; i < len; i++) {
	    chr = text.charCodeAt(i);
	    hash = (hash << 5) - hash + chr;
	    hash |= 0;
	  }

	  return hash < 0 ? hash * -2 : hash;
	}

	function foldObject(hash, o, seen) {
	  return Object.keys(o).sort().reduce(foldKey, hash);

	  function foldKey(hash, key) {
	    return foldValue(hash, o[key], key, seen);
	  }
	}

	function foldValue(input, value, key, seen) {
	  var hash = fold(fold(fold(input, key), toString$2(value)), _typeof_1(value));

	  if (value === null) {
	    return fold(hash, 'null');
	  }

	  if (value === undefined) {
	    return fold(hash, 'undefined');
	  }

	  if (_typeof_1(value) === 'object' || typeof value === 'function') {
	    if (seen.indexOf(value) !== -1) {
	      return fold(hash, '[Circular]' + key);
	    }

	    seen.push(value);
	    var objHash = foldObject(hash, value, seen);

	    if (!('valueOf' in value) || typeof value.valueOf !== 'function') {
	      return objHash;
	    }

	    try {
	      return fold(objHash, String(value.valueOf()));
	    } catch (err) {
	      return fold(objHash, '[valueOf exception]' + (err.stack || err.message));
	    }
	  }

	  return fold(hash, value.toString());
	}

	function toString$2(o) {
	  return Object.prototype.toString.call(o);
	}

	function sum(o) {
	  return pad(foldValue(0, o, '', []).toString(16), 8);
	}

	/**
	 * Return a unique hash of any object
	 */

	var useHashsum = function useHashsum(obj) {
	  return sum(obj);
	};
	/**
	 * Return a unique ID for Gutenberg block instance
	 */

	var idStore = {};
	var useUniqueId = function useUniqueId(_ref) {
	  var clientId = _ref.clientId,
	      name = _ref.name;

	  var _useState = React.useState(null),
	      _useState2 = slicedToArray(_useState, 2),
	      uniqueId = _useState2[0],
	      setUniqueId = _useState2[1];

	  if (!uniqueId) {
	    if (!idStore.hasOwnProperty(name)) {
	      idStore[name] = 0;
	    }

	    idStore[name]++;
	    setUniqueId(useHashsum([clientId, idStore[name]]));
	  }

	  return uniqueId;
	};
	/**
	 * Return the block style name from the class attribute
	 */

	var useStyleName = function useStyleName(className) {
	  var match = (className || '').match(/is-style-([^\s"]+)/);
	  return match ? match[1] : '';
	};

	function _objectWithoutPropertiesLoose(source, excluded) {
	  if (source == null) return {};
	  var target = {};
	  var sourceKeys = Object.keys(source);
	  var key, i;

	  for (i = 0; i < sourceKeys.length; i++) {
	    key = sourceKeys[i];
	    if (excluded.indexOf(key) >= 0) continue;
	    target[key] = source[key];
	  }

	  return target;
	}

	var objectWithoutPropertiesLoose = _objectWithoutPropertiesLoose;

	function _objectWithoutProperties(source, excluded) {
	  if (source == null) return {};
	  var target = objectWithoutPropertiesLoose(source, excluded);
	  var key, i;

	  if (Object.getOwnPropertySymbols) {
	    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);

	    for (i = 0; i < sourceSymbolKeys.length; i++) {
	      key = sourceSymbolKeys[i];
	      if (excluded.indexOf(key) >= 0) continue;
	      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
	      target[key] = source[key];
	    }
	  }

	  return target;
	}

	var objectWithoutProperties = _objectWithoutProperties;

	var ButtonControl = function ButtonControl(props) {
	  var _props$field = props.field,
	      help = _props$field.help,
	      label = _props$field.label,
	      buttonProps = objectWithoutProperties(_props$field, ["help", "label"]);

	  return wp.element.createElement(components.BaseControl, {
	    help: help
	  }, wp.element.createElement("div", {
	    className: "components-base-control__button"
	  }, wp.element.createElement(components.Button, buttonProps, label)));
	};

	var MATCH = wellKnownSymbol('match');

	// `IsRegExp` abstract operation
	// https://tc39.github.io/ecma262/#sec-isregexp
	var isRegexp = function (it) {
	  var isRegExp;
	  return isObject(it) && ((isRegExp = it[MATCH]) !== undefined ? !!isRegExp : classofRaw(it) == 'RegExp');
	};

	var notARegexp = function (it) {
	  if (isRegexp(it)) {
	    throw TypeError("The method doesn't accept regular expressions");
	  } return it;
	};

	var MATCH$1 = wellKnownSymbol('match');

	var correctIsRegexpLogic = function (METHOD_NAME) {
	  var regexp = /./;
	  try {
	    '/./'[METHOD_NAME](regexp);
	  } catch (e) {
	    try {
	      regexp[MATCH$1] = false;
	      return '/./'[METHOD_NAME](regexp);
	    } catch (f) { /* empty */ }
	  } return false;
	};

	// `String.prototype.includes` method
	// https://tc39.github.io/ecma262/#sec-string.prototype.includes
	_export({ target: 'String', proto: true, forced: !correctIsRegexpLogic('includes') }, {
	  includes: function includes(searchString /* , position = 0 */) {
	    return !!~String(requireObjectCoercible(this))
	      .indexOf(notARegexp(searchString), arguments.length > 1 ? arguments[1] : undefined);
	  }
	});

	var CheckboxesControl = function CheckboxesControl(props) {
	  var attrs = props.attrs,
	      field = props.field,
	      label = props.label,
	      name = props.name,
	      _onChange = props.onChange;
	  return (// Markup similar to `RadioControl` with multiple options
	    wp.element.createElement(components.BaseControl, {
	      label: label,
	      className: "components-radio-control"
	    }, field.options.map(function (option) {
	      return wp.element.createElement("div", {
	        key: useHashsum(option),
	        className: "components-radio-control__option"
	      }, wp.element.createElement(components.CheckboxControl, {
	        label: option.label,
	        checked: (attrs[name] || []).includes(option.value),
	        onChange: function onChange(checked) {
	          // Remove checkbox value from attribute array
	          var attr = (attrs[name] || []).filter(function (v) {
	            return v !== option.value;
	          }); // Re-append value if checked

	          if (checked) {
	            attr.push(option.value);
	          }

	          _onChange(name, attr);
	        }
	      }));
	    }))
	  );
	};

	// `Array.prototype.fill` method implementation
	// https://tc39.github.io/ecma262/#sec-array.prototype.fill
	var arrayFill = function fill(value /* , start = 0, end = @length */) {
	  var O = toObject(this);
	  var length = toLength(O.length);
	  var argumentsLength = arguments.length;
	  var index = toAbsoluteIndex(argumentsLength > 1 ? arguments[1] : undefined, length);
	  var end = argumentsLength > 2 ? arguments[2] : undefined;
	  var endPos = end === undefined ? length : toAbsoluteIndex(end, length);
	  while (endPos > index) O[index++] = value;
	  return O;
	};

	// `Array.prototype.fill` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.fill
	_export({ target: 'Array', proto: true }, {
	  fill: arrayFill
	});

	// https://tc39.github.io/ecma262/#sec-array.prototype-@@unscopables
	addToUnscopables('fill');

	var ColumnsControl = function ColumnsControl(props) {
	  var value = props.value,
	      min = props.min,
	      max = props.max,
	      onChange = props.onChange;
	  var control = {
	    label: i18n.__('Number of Columns'),
	    className: 'components-vf-control'
	  };

	  if (props.help) {
	    control.help = props.help;
	  }

	  var isPressed = function isPressed(i) {
	    return i + min === value;
	  };

	  return wp.element.createElement(components.BaseControl, control, wp.element.createElement(components.ButtonGroup, {
	    "aria-label": control.label
	  }, Array(max - min + 1).fill().map(function (x, i) {
	    return wp.element.createElement(components.Button, {
	      key: i,
	      isPrimary: isPressed(i),
	      "aria-pressed": isPressed(i),
	      onClick: function onClick() {
	        return onChange(i + min);
	      }
	    }, i + min);
	  })));
	};

	/**
	 * DateControl (component)
	 * Wrapper for `DateControl`
	 */

	var DateControl = function DateControl(props) {
	  return wp.element.createElement(components.BaseControl, {
	    label: props.label
	  }, wp.element.createElement(components.DatePicker, {
	    currentDate: props.currentDate,
	    onChange: props.onChange
	  }));
	};

	var runtime_1 = createCommonjsModule(function (module) {
	/**
	 * Copyright (c) 2014-present, Facebook, Inc.
	 *
	 * This source code is licensed under the MIT license found in the
	 * LICENSE file in the root directory of this source tree.
	 */

	var runtime = (function (exports) {

	  var Op = Object.prototype;
	  var hasOwn = Op.hasOwnProperty;
	  var undefined$1; // More compressible than void 0.
	  var $Symbol = typeof Symbol === "function" ? Symbol : {};
	  var iteratorSymbol = $Symbol.iterator || "@@iterator";
	  var asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator";
	  var toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";

	  function define(obj, key, value) {
	    Object.defineProperty(obj, key, {
	      value: value,
	      enumerable: true,
	      configurable: true,
	      writable: true
	    });
	    return obj[key];
	  }
	  try {
	    // IE 8 has a broken Object.defineProperty that only works on DOM objects.
	    define({}, "");
	  } catch (err) {
	    define = function(obj, key, value) {
	      return obj[key] = value;
	    };
	  }

	  function wrap(innerFn, outerFn, self, tryLocsList) {
	    // If outerFn provided and outerFn.prototype is a Generator, then outerFn.prototype instanceof Generator.
	    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator;
	    var generator = Object.create(protoGenerator.prototype);
	    var context = new Context(tryLocsList || []);

	    // The ._invoke method unifies the implementations of the .next,
	    // .throw, and .return methods.
	    generator._invoke = makeInvokeMethod(innerFn, self, context);

	    return generator;
	  }
	  exports.wrap = wrap;

	  // Try/catch helper to minimize deoptimizations. Returns a completion
	  // record like context.tryEntries[i].completion. This interface could
	  // have been (and was previously) designed to take a closure to be
	  // invoked without arguments, but in all the cases we care about we
	  // already have an existing method we want to call, so there's no need
	  // to create a new function object. We can even get away with assuming
	  // the method takes exactly one argument, since that happens to be true
	  // in every case, so we don't have to touch the arguments object. The
	  // only additional allocation required is the completion record, which
	  // has a stable shape and so hopefully should be cheap to allocate.
	  function tryCatch(fn, obj, arg) {
	    try {
	      return { type: "normal", arg: fn.call(obj, arg) };
	    } catch (err) {
	      return { type: "throw", arg: err };
	    }
	  }

	  var GenStateSuspendedStart = "suspendedStart";
	  var GenStateSuspendedYield = "suspendedYield";
	  var GenStateExecuting = "executing";
	  var GenStateCompleted = "completed";

	  // Returning this object from the innerFn has the same effect as
	  // breaking out of the dispatch switch statement.
	  var ContinueSentinel = {};

	  // Dummy constructor functions that we use as the .constructor and
	  // .constructor.prototype properties for functions that return Generator
	  // objects. For full spec compliance, you may wish to configure your
	  // minifier not to mangle the names of these two functions.
	  function Generator() {}
	  function GeneratorFunction() {}
	  function GeneratorFunctionPrototype() {}

	  // This is a polyfill for %IteratorPrototype% for environments that
	  // don't natively support it.
	  var IteratorPrototype = {};
	  IteratorPrototype[iteratorSymbol] = function () {
	    return this;
	  };

	  var getProto = Object.getPrototypeOf;
	  var NativeIteratorPrototype = getProto && getProto(getProto(values([])));
	  if (NativeIteratorPrototype &&
	      NativeIteratorPrototype !== Op &&
	      hasOwn.call(NativeIteratorPrototype, iteratorSymbol)) {
	    // This environment has a native %IteratorPrototype%; use it instead
	    // of the polyfill.
	    IteratorPrototype = NativeIteratorPrototype;
	  }

	  var Gp = GeneratorFunctionPrototype.prototype =
	    Generator.prototype = Object.create(IteratorPrototype);
	  GeneratorFunction.prototype = Gp.constructor = GeneratorFunctionPrototype;
	  GeneratorFunctionPrototype.constructor = GeneratorFunction;
	  GeneratorFunction.displayName = define(
	    GeneratorFunctionPrototype,
	    toStringTagSymbol,
	    "GeneratorFunction"
	  );

	  // Helper for defining the .next, .throw, and .return methods of the
	  // Iterator interface in terms of a single ._invoke method.
	  function defineIteratorMethods(prototype) {
	    ["next", "throw", "return"].forEach(function(method) {
	      define(prototype, method, function(arg) {
	        return this._invoke(method, arg);
	      });
	    });
	  }

	  exports.isGeneratorFunction = function(genFun) {
	    var ctor = typeof genFun === "function" && genFun.constructor;
	    return ctor
	      ? ctor === GeneratorFunction ||
	        // For the native GeneratorFunction constructor, the best we can
	        // do is to check its .name property.
	        (ctor.displayName || ctor.name) === "GeneratorFunction"
	      : false;
	  };

	  exports.mark = function(genFun) {
	    if (Object.setPrototypeOf) {
	      Object.setPrototypeOf(genFun, GeneratorFunctionPrototype);
	    } else {
	      genFun.__proto__ = GeneratorFunctionPrototype;
	      define(genFun, toStringTagSymbol, "GeneratorFunction");
	    }
	    genFun.prototype = Object.create(Gp);
	    return genFun;
	  };

	  // Within the body of any async function, `await x` is transformed to
	  // `yield regeneratorRuntime.awrap(x)`, so that the runtime can test
	  // `hasOwn.call(value, "__await")` to determine if the yielded value is
	  // meant to be awaited.
	  exports.awrap = function(arg) {
	    return { __await: arg };
	  };

	  function AsyncIterator(generator, PromiseImpl) {
	    function invoke(method, arg, resolve, reject) {
	      var record = tryCatch(generator[method], generator, arg);
	      if (record.type === "throw") {
	        reject(record.arg);
	      } else {
	        var result = record.arg;
	        var value = result.value;
	        if (value &&
	            typeof value === "object" &&
	            hasOwn.call(value, "__await")) {
	          return PromiseImpl.resolve(value.__await).then(function(value) {
	            invoke("next", value, resolve, reject);
	          }, function(err) {
	            invoke("throw", err, resolve, reject);
	          });
	        }

	        return PromiseImpl.resolve(value).then(function(unwrapped) {
	          // When a yielded Promise is resolved, its final value becomes
	          // the .value of the Promise<{value,done}> result for the
	          // current iteration.
	          result.value = unwrapped;
	          resolve(result);
	        }, function(error) {
	          // If a rejected Promise was yielded, throw the rejection back
	          // into the async generator function so it can be handled there.
	          return invoke("throw", error, resolve, reject);
	        });
	      }
	    }

	    var previousPromise;

	    function enqueue(method, arg) {
	      function callInvokeWithMethodAndArg() {
	        return new PromiseImpl(function(resolve, reject) {
	          invoke(method, arg, resolve, reject);
	        });
	      }

	      return previousPromise =
	        // If enqueue has been called before, then we want to wait until
	        // all previous Promises have been resolved before calling invoke,
	        // so that results are always delivered in the correct order. If
	        // enqueue has not been called before, then it is important to
	        // call invoke immediately, without waiting on a callback to fire,
	        // so that the async generator function has the opportunity to do
	        // any necessary setup in a predictable way. This predictability
	        // is why the Promise constructor synchronously invokes its
	        // executor callback, and why async functions synchronously
	        // execute code before the first await. Since we implement simple
	        // async functions in terms of async generators, it is especially
	        // important to get this right, even though it requires care.
	        previousPromise ? previousPromise.then(
	          callInvokeWithMethodAndArg,
	          // Avoid propagating failures to Promises returned by later
	          // invocations of the iterator.
	          callInvokeWithMethodAndArg
	        ) : callInvokeWithMethodAndArg();
	    }

	    // Define the unified helper method that is used to implement .next,
	    // .throw, and .return (see defineIteratorMethods).
	    this._invoke = enqueue;
	  }

	  defineIteratorMethods(AsyncIterator.prototype);
	  AsyncIterator.prototype[asyncIteratorSymbol] = function () {
	    return this;
	  };
	  exports.AsyncIterator = AsyncIterator;

	  // Note that simple async functions are implemented on top of
	  // AsyncIterator objects; they just return a Promise for the value of
	  // the final result produced by the iterator.
	  exports.async = function(innerFn, outerFn, self, tryLocsList, PromiseImpl) {
	    if (PromiseImpl === void 0) PromiseImpl = Promise;

	    var iter = new AsyncIterator(
	      wrap(innerFn, outerFn, self, tryLocsList),
	      PromiseImpl
	    );

	    return exports.isGeneratorFunction(outerFn)
	      ? iter // If outerFn is a generator, return the full iterator.
	      : iter.next().then(function(result) {
	          return result.done ? result.value : iter.next();
	        });
	  };

	  function makeInvokeMethod(innerFn, self, context) {
	    var state = GenStateSuspendedStart;

	    return function invoke(method, arg) {
	      if (state === GenStateExecuting) {
	        throw new Error("Generator is already running");
	      }

	      if (state === GenStateCompleted) {
	        if (method === "throw") {
	          throw arg;
	        }

	        // Be forgiving, per 25.3.3.3.3 of the spec:
	        // https://people.mozilla.org/~jorendorff/es6-draft.html#sec-generatorresume
	        return doneResult();
	      }

	      context.method = method;
	      context.arg = arg;

	      while (true) {
	        var delegate = context.delegate;
	        if (delegate) {
	          var delegateResult = maybeInvokeDelegate(delegate, context);
	          if (delegateResult) {
	            if (delegateResult === ContinueSentinel) continue;
	            return delegateResult;
	          }
	        }

	        if (context.method === "next") {
	          // Setting context._sent for legacy support of Babel's
	          // function.sent implementation.
	          context.sent = context._sent = context.arg;

	        } else if (context.method === "throw") {
	          if (state === GenStateSuspendedStart) {
	            state = GenStateCompleted;
	            throw context.arg;
	          }

	          context.dispatchException(context.arg);

	        } else if (context.method === "return") {
	          context.abrupt("return", context.arg);
	        }

	        state = GenStateExecuting;

	        var record = tryCatch(innerFn, self, context);
	        if (record.type === "normal") {
	          // If an exception is thrown from innerFn, we leave state ===
	          // GenStateExecuting and loop back for another invocation.
	          state = context.done
	            ? GenStateCompleted
	            : GenStateSuspendedYield;

	          if (record.arg === ContinueSentinel) {
	            continue;
	          }

	          return {
	            value: record.arg,
	            done: context.done
	          };

	        } else if (record.type === "throw") {
	          state = GenStateCompleted;
	          // Dispatch the exception by looping back around to the
	          // context.dispatchException(context.arg) call above.
	          context.method = "throw";
	          context.arg = record.arg;
	        }
	      }
	    };
	  }

	  // Call delegate.iterator[context.method](context.arg) and handle the
	  // result, either by returning a { value, done } result from the
	  // delegate iterator, or by modifying context.method and context.arg,
	  // setting context.delegate to null, and returning the ContinueSentinel.
	  function maybeInvokeDelegate(delegate, context) {
	    var method = delegate.iterator[context.method];
	    if (method === undefined$1) {
	      // A .throw or .return when the delegate iterator has no .throw
	      // method always terminates the yield* loop.
	      context.delegate = null;

	      if (context.method === "throw") {
	        // Note: ["return"] must be used for ES3 parsing compatibility.
	        if (delegate.iterator["return"]) {
	          // If the delegate iterator has a return method, give it a
	          // chance to clean up.
	          context.method = "return";
	          context.arg = undefined$1;
	          maybeInvokeDelegate(delegate, context);

	          if (context.method === "throw") {
	            // If maybeInvokeDelegate(context) changed context.method from
	            // "return" to "throw", let that override the TypeError below.
	            return ContinueSentinel;
	          }
	        }

	        context.method = "throw";
	        context.arg = new TypeError(
	          "The iterator does not provide a 'throw' method");
	      }

	      return ContinueSentinel;
	    }

	    var record = tryCatch(method, delegate.iterator, context.arg);

	    if (record.type === "throw") {
	      context.method = "throw";
	      context.arg = record.arg;
	      context.delegate = null;
	      return ContinueSentinel;
	    }

	    var info = record.arg;

	    if (! info) {
	      context.method = "throw";
	      context.arg = new TypeError("iterator result is not an object");
	      context.delegate = null;
	      return ContinueSentinel;
	    }

	    if (info.done) {
	      // Assign the result of the finished delegate to the temporary
	      // variable specified by delegate.resultName (see delegateYield).
	      context[delegate.resultName] = info.value;

	      // Resume execution at the desired location (see delegateYield).
	      context.next = delegate.nextLoc;

	      // If context.method was "throw" but the delegate handled the
	      // exception, let the outer generator proceed normally. If
	      // context.method was "next", forget context.arg since it has been
	      // "consumed" by the delegate iterator. If context.method was
	      // "return", allow the original .return call to continue in the
	      // outer generator.
	      if (context.method !== "return") {
	        context.method = "next";
	        context.arg = undefined$1;
	      }

	    } else {
	      // Re-yield the result returned by the delegate method.
	      return info;
	    }

	    // The delegate iterator is finished, so forget it and continue with
	    // the outer generator.
	    context.delegate = null;
	    return ContinueSentinel;
	  }

	  // Define Generator.prototype.{next,throw,return} in terms of the
	  // unified ._invoke helper method.
	  defineIteratorMethods(Gp);

	  define(Gp, toStringTagSymbol, "Generator");

	  // A Generator should always return itself as the iterator object when the
	  // @@iterator function is called on it. Some browsers' implementations of the
	  // iterator prototype chain incorrectly implement this, causing the Generator
	  // object to not be returned from this call. This ensures that doesn't happen.
	  // See https://github.com/facebook/regenerator/issues/274 for more details.
	  Gp[iteratorSymbol] = function() {
	    return this;
	  };

	  Gp.toString = function() {
	    return "[object Generator]";
	  };

	  function pushTryEntry(locs) {
	    var entry = { tryLoc: locs[0] };

	    if (1 in locs) {
	      entry.catchLoc = locs[1];
	    }

	    if (2 in locs) {
	      entry.finallyLoc = locs[2];
	      entry.afterLoc = locs[3];
	    }

	    this.tryEntries.push(entry);
	  }

	  function resetTryEntry(entry) {
	    var record = entry.completion || {};
	    record.type = "normal";
	    delete record.arg;
	    entry.completion = record;
	  }

	  function Context(tryLocsList) {
	    // The root entry object (effectively a try statement without a catch
	    // or a finally block) gives us a place to store values thrown from
	    // locations where there is no enclosing try statement.
	    this.tryEntries = [{ tryLoc: "root" }];
	    tryLocsList.forEach(pushTryEntry, this);
	    this.reset(true);
	  }

	  exports.keys = function(object) {
	    var keys = [];
	    for (var key in object) {
	      keys.push(key);
	    }
	    keys.reverse();

	    // Rather than returning an object with a next method, we keep
	    // things simple and return the next function itself.
	    return function next() {
	      while (keys.length) {
	        var key = keys.pop();
	        if (key in object) {
	          next.value = key;
	          next.done = false;
	          return next;
	        }
	      }

	      // To avoid creating an additional object, we just hang the .value
	      // and .done properties off the next function object itself. This
	      // also ensures that the minifier will not anonymize the function.
	      next.done = true;
	      return next;
	    };
	  };

	  function values(iterable) {
	    if (iterable) {
	      var iteratorMethod = iterable[iteratorSymbol];
	      if (iteratorMethod) {
	        return iteratorMethod.call(iterable);
	      }

	      if (typeof iterable.next === "function") {
	        return iterable;
	      }

	      if (!isNaN(iterable.length)) {
	        var i = -1, next = function next() {
	          while (++i < iterable.length) {
	            if (hasOwn.call(iterable, i)) {
	              next.value = iterable[i];
	              next.done = false;
	              return next;
	            }
	          }

	          next.value = undefined$1;
	          next.done = true;

	          return next;
	        };

	        return next.next = next;
	      }
	    }

	    // Return an iterator with no values.
	    return { next: doneResult };
	  }
	  exports.values = values;

	  function doneResult() {
	    return { value: undefined$1, done: true };
	  }

	  Context.prototype = {
	    constructor: Context,

	    reset: function(skipTempReset) {
	      this.prev = 0;
	      this.next = 0;
	      // Resetting context._sent for legacy support of Babel's
	      // function.sent implementation.
	      this.sent = this._sent = undefined$1;
	      this.done = false;
	      this.delegate = null;

	      this.method = "next";
	      this.arg = undefined$1;

	      this.tryEntries.forEach(resetTryEntry);

	      if (!skipTempReset) {
	        for (var name in this) {
	          // Not sure about the optimal order of these conditions:
	          if (name.charAt(0) === "t" &&
	              hasOwn.call(this, name) &&
	              !isNaN(+name.slice(1))) {
	            this[name] = undefined$1;
	          }
	        }
	      }
	    },

	    stop: function() {
	      this.done = true;

	      var rootEntry = this.tryEntries[0];
	      var rootRecord = rootEntry.completion;
	      if (rootRecord.type === "throw") {
	        throw rootRecord.arg;
	      }

	      return this.rval;
	    },

	    dispatchException: function(exception) {
	      if (this.done) {
	        throw exception;
	      }

	      var context = this;
	      function handle(loc, caught) {
	        record.type = "throw";
	        record.arg = exception;
	        context.next = loc;

	        if (caught) {
	          // If the dispatched exception was caught by a catch block,
	          // then let that catch block handle the exception normally.
	          context.method = "next";
	          context.arg = undefined$1;
	        }

	        return !! caught;
	      }

	      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
	        var entry = this.tryEntries[i];
	        var record = entry.completion;

	        if (entry.tryLoc === "root") {
	          // Exception thrown outside of any try block that could handle
	          // it, so set the completion value of the entire function to
	          // throw the exception.
	          return handle("end");
	        }

	        if (entry.tryLoc <= this.prev) {
	          var hasCatch = hasOwn.call(entry, "catchLoc");
	          var hasFinally = hasOwn.call(entry, "finallyLoc");

	          if (hasCatch && hasFinally) {
	            if (this.prev < entry.catchLoc) {
	              return handle(entry.catchLoc, true);
	            } else if (this.prev < entry.finallyLoc) {
	              return handle(entry.finallyLoc);
	            }

	          } else if (hasCatch) {
	            if (this.prev < entry.catchLoc) {
	              return handle(entry.catchLoc, true);
	            }

	          } else if (hasFinally) {
	            if (this.prev < entry.finallyLoc) {
	              return handle(entry.finallyLoc);
	            }

	          } else {
	            throw new Error("try statement without catch or finally");
	          }
	        }
	      }
	    },

	    abrupt: function(type, arg) {
	      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
	        var entry = this.tryEntries[i];
	        if (entry.tryLoc <= this.prev &&
	            hasOwn.call(entry, "finallyLoc") &&
	            this.prev < entry.finallyLoc) {
	          var finallyEntry = entry;
	          break;
	        }
	      }

	      if (finallyEntry &&
	          (type === "break" ||
	           type === "continue") &&
	          finallyEntry.tryLoc <= arg &&
	          arg <= finallyEntry.finallyLoc) {
	        // Ignore the finally entry if control is not jumping to a
	        // location outside the try/catch block.
	        finallyEntry = null;
	      }

	      var record = finallyEntry ? finallyEntry.completion : {};
	      record.type = type;
	      record.arg = arg;

	      if (finallyEntry) {
	        this.method = "next";
	        this.next = finallyEntry.finallyLoc;
	        return ContinueSentinel;
	      }

	      return this.complete(record);
	    },

	    complete: function(record, afterLoc) {
	      if (record.type === "throw") {
	        throw record.arg;
	      }

	      if (record.type === "break" ||
	          record.type === "continue") {
	        this.next = record.arg;
	      } else if (record.type === "return") {
	        this.rval = this.arg = record.arg;
	        this.method = "return";
	        this.next = "end";
	      } else if (record.type === "normal" && afterLoc) {
	        this.next = afterLoc;
	      }

	      return ContinueSentinel;
	    },

	    finish: function(finallyLoc) {
	      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
	        var entry = this.tryEntries[i];
	        if (entry.finallyLoc === finallyLoc) {
	          this.complete(entry.completion, entry.afterLoc);
	          resetTryEntry(entry);
	          return ContinueSentinel;
	        }
	      }
	    },

	    "catch": function(tryLoc) {
	      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
	        var entry = this.tryEntries[i];
	        if (entry.tryLoc === tryLoc) {
	          var record = entry.completion;
	          if (record.type === "throw") {
	            var thrown = record.arg;
	            resetTryEntry(entry);
	          }
	          return thrown;
	        }
	      }

	      // The context.catch method must only be called with a location
	      // argument that corresponds to a known catch block.
	      throw new Error("illegal catch attempt");
	    },

	    delegateYield: function(iterable, resultName, nextLoc) {
	      this.delegate = {
	        iterator: values(iterable),
	        resultName: resultName,
	        nextLoc: nextLoc
	      };

	      if (this.method === "next") {
	        // Deliberately forget the last sent value so that we don't
	        // accidentally pass it on to the delegate.
	        this.arg = undefined$1;
	      }

	      return ContinueSentinel;
	    }
	  };

	  // Regardless of whether this script is executing as a CommonJS module
	  // or not, return the runtime object so that we can declare the variable
	  // regeneratorRuntime in the outer scope, which allows this module to be
	  // injected easily by `bin/regenerator --include-runtime script.js`.
	  return exports;

	}(
	  // If this script is executing as a CommonJS module, use module.exports
	  // as the regeneratorRuntime namespace. Otherwise create a new empty
	  // object. Either way, the resulting object will be used to initialize
	  // the regeneratorRuntime variable at the top of this file.
	   module.exports 
	));

	try {
	  regeneratorRuntime = runtime;
	} catch (accidentalStrictMode) {
	  // This module should not be running in strict mode, so the above
	  // assignment should always work unless something is misconfigured. Just
	  // in case runtime.js accidentally runs in strict mode, we can escape
	  // strict mode using a global Function call. This could conceivably fail
	  // if a Content Security Policy forbids using Function, but in that case
	  // the proper solution is to fix the accidental strict mode problem. If
	  // you've misconfigured your bundler to force strict mode and applied a
	  // CSP to forbid Function, and you're not willing to fix either of those
	  // problems, please detail your unique predicament in a GitHub issue.
	  Function("r", "regeneratorRuntime = r")(runtime);
	}
	});

	var regenerator = runtime_1;

	function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
	  try {
	    var info = gen[key](arg);
	    var value = info.value;
	  } catch (error) {
	    reject(error);
	    return;
	  }

	  if (info.done) {
	    resolve(value);
	  } else {
	    Promise.resolve(value).then(_next, _throw);
	  }
	}

	function _asyncToGenerator(fn) {
	  return function () {
	    var self = this,
	        args = arguments;
	    return new Promise(function (resolve, reject) {
	      var gen = fn.apply(self, args);

	      function _next(value) {
	        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
	      }

	      function _throw(err) {
	        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
	      }

	      _next(undefined);
	    });
	  };
	}

	var asyncToGenerator = _asyncToGenerator;

	var taxonomyStore = {};

	var useVFTerms = function useVFTerms(taxonomy) {
	  var _useState = React.useState([]),
	      _useState2 = slicedToArray(_useState, 2),
	      terms = _useState2[0],
	      setTerms = _useState2[1];

	  var fetchData = /*#__PURE__*/function () {
	    var _ref = asyncToGenerator( /*#__PURE__*/regenerator.mark(function _callee() {
	      var _useVFGutenberg, postId, nonce, data;

	      return regenerator.wrap(function _callee$(_context) {
	        while (1) {
	          switch (_context.prev = _context.next) {
	            case 0:
	              if (!taxonomyStore.hasOwnProperty(taxonomy)) {
	                _context.next = 3;
	                break;
	              }

	              setTerms(taxonomyStore[taxonomy]);
	              return _context.abrupt("return");

	            case 3:
	              _useVFGutenberg = useVFGutenberg(), postId = _useVFGutenberg.postId, nonce = _useVFGutenberg.nonce;
	              _context.prev = 4;
	              _context.next = 7;
	              return wp.ajax.post('vf/gutenberg/fetch_terms', {
	                taxonomy: taxonomy,
	                postId: postId,
	                nonce: nonce
	              });

	            case 7:
	              data = _context.sent;

	              if (data && data.hasOwnProperty('terms')) {
	                taxonomyStore[taxonomy] = data.terms;
	                setTerms(data.terms);
	              }

	              _context.next = 13;
	              break;

	            case 11:
	              _context.prev = 11;
	              _context.t0 = _context["catch"](4);

	            case 13:
	            case "end":
	              return _context.stop();
	          }
	        }
	      }, _callee, null, [[4, 11]]);
	    }));

	    return function fetchData() {
	      return _ref.apply(this, arguments);
	    };
	  }();

	  React.useEffect(function () {
	    fetchData();
	  }, [taxonomy]);
	  return terms;
	};

	var TaxonomyControl = function TaxonomyControl(props) {
	  var options = [{
	    label: i18n.__('Loading…'),
	    value: props.value
	  }]; // Set terms once loaded

	  var terms = useVFTerms(props.taxonomy);

	  if (terms.length) {
	    options = terms.map(function (term) {
	      return {
	        label: term.name,
	        value: parseInt(term.term_id)
	      };
	    });
	    options.unshift({
	      label: i18n.__('Select…'),
	      value: ''
	    });
	  } // Reset to default value if no term ID is selected


	  var onChange = function onChange(value) {
	    var intValue = parseInt(value);
	    value = isNaN(intValue) ? '' : intValue;
	    props.onChange(value);
	  };

	  return wp.element.createElement(components.SelectControl, _extends_1({}, props, {
	    onChange: onChange,
	    options: options
	  }));
	};

	/**
	 * URLControl (component)
	 * Wrapper for `URLInput`
	 */

	var URLControl = function URLControl(props) {
	  var className = '';

	  if (!props.disableSuggestions) {
	    className += 'has-suggestions';
	  }

	  return wp.element.createElement(components.BaseControl, {
	    label: props.label
	  }, wp.element.createElement(blockEditor.URLInput, {
	    autoFocus: false,
	    className: className,
	    disableSuggestions: props.disableSuggestions,
	    onChange: props.onChange,
	    value: props.value
	  }));
	};

	/**
	 * RichControl (component)
	 * Wrapper for `RichText`
	 */

	var RichControl = function RichControl(props) {
	  return wp.element.createElement(components.BaseControl, {
	    label: props.label
	  }, wp.element.createElement("div", {
	    className: "components-base-control__rich-text"
	  }, wp.element.createElement(blockEditor.RichText, {
	    tagName: props.tag,
	    value: props.value,
	    placeholder: props.placeholder,
	    onChange: props.onChange
	  })));
	};

	function ownKeys$1(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$1(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$1(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var DATE_CONTROLS = ['date', 'date_picker'];
	var RICH_CONTROLS = ['rich', 'wysiwyg'];
	var TEXT_CONTROLS = ['text', 'email'];
	var BOOL_CONTROLS = ['bool', 'boolean', 'toggle', 'true_false']; // Fields component

	var VFBlockFields = function VFBlockFields(props) {
	  var attrs = props.attributes,
	      setAttributes = props.setAttributes,
	      fields = props.fields; // Generic event handler to update an attribute

	  var handleChange = function handleChange(name, value) {
	    var attr = {};
	    attr[name] = value;
	    setAttributes(_objectSpread({}, attr));
	  }; // Add any initial controls from children


	  var controls = [];

	  if (props.children) {
	    controls.push(props.children);
	  } // Map fields and add array of controls


	  controls.push(fields.map(function (field) {
	    var control = field.control,
	        help = field.help,
	        label = field.label,
	        name = field.name,
	        _onChange = field.onChange;
	    var key = useHashsum(field); // Fallback to default handler

	    _onChange = typeof _onChange === 'function' ? _onChange : handleChange; // The ACF "checkbox" field returns an array of one or more checked
	    // values whereas "true_false" (here "toggle") uses a boolean value

	    if (control === 'button') {
	      return wp.element.createElement(ButtonControl, {
	        key: key,
	        field: field,
	        label: label
	      });
	    } // The ACF "checkbox" field returns an array of one or more checked
	    // values whereas "true_false" (here "toggle") uses a boolean value


	    if (control === 'checkbox') {
	      return wp.element.createElement(CheckboxesControl, {
	        key: key,
	        name: name,
	        attrs: attrs,
	        field: field,
	        label: label,
	        onChange: _onChange
	      });
	    } // Custom control to manage number of grid columns


	    if (control === 'columns') {
	      // const min = parseInt(field.min) || 1;
	      // const max = parseInt(field.max) || 6;
	      // const value = parseInt(field.value) || 0;
	      var min = isNaN(field.min) ? 1 : parseInt(field.min);
	      var max = isNaN(field.max) ? 6 : parseInt(field.max);
	      var value = isNaN(field.value) ? 0 : parseInt(field.value);
	      return wp.element.createElement(ColumnsControl, {
	        key: key,
	        min: min,
	        max: max,
	        help: help,
	        value: value,
	        onChange: field.onChange
	      });
	    }

	    if (DATE_CONTROLS.includes(control)) {
	      var date = new Date(attrs[name]);

	      if (isNaN(date.getTime())) {
	        date = Date.now();
	      }

	      return wp.element.createElement(DateControl, {
	        key: key,
	        label: label,
	        currentDate: date,
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    }

	    if (control === 'number') {
	      var _min = parseInt(field['min']) || undefined;

	      var _max = parseInt(field['max']) || undefined;

	      return wp.element.createElement(components.TextControl, {
	        key: key,
	        help: help,
	        label: label,
	        type: "number",
	        value: parseInt(attrs[name]) || _min,
	        onChange: function onChange(value) {
	          return _onChange(name, parseInt(value));
	        },
	        min: _min,
	        max: _max
	      });
	    }

	    if (control === 'radio') {
	      return wp.element.createElement(components.RadioControl, {
	        key: key,
	        label: label,
	        selected: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        },
	        options: toConsumableArray(field.options)
	      });
	    }

	    if (control === 'range') {
	      var allowReset = !!field.allowReset;

	      var _min2 = isNaN(field.min) ? 1 : parseInt(field.min);

	      var _max2 = isNaN(field.max) ? 10 : parseInt(field.max);

	      var step = isNaN(field.step) ? 1 : parseInt(field.step);
	      return wp.element.createElement(components.RangeControl, {
	        key: key,
	        help: help,
	        label: label,
	        value: parseInt(attrs[name]) || _min2,
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        },
	        allowReset: allowReset,
	        step: step,
	        min: _min2,
	        max: _max2
	      });
	    }

	    if (RICH_CONTROLS.includes(control)) {
	      var tag = field.tag || 'p';

	      var placeholder = field.placeholder || i18n.__('Type content…');

	      return wp.element.createElement(RichControl, {
	        key: key,
	        label: label,
	        value: attrs[name],
	        tag: tag,
	        placeholder: placeholder,
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    }

	    if (control === 'select') {
	      return wp.element.createElement(components.SelectControl, {
	        key: key,
	        label: label,
	        value: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        },
	        options: [{
	          label: i18n.__('Select…'),
	          value: ''
	        }].concat(toConsumableArray(field.options))
	      });
	    }

	    if (control === 'taxonomy') {
	      return wp.element.createElement(TaxonomyControl, {
	        key: key,
	        taxonomy: field.taxonomy,
	        label: label,
	        value: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    }

	    if (TEXT_CONTROLS.includes(control)) {
	      return wp.element.createElement(components.TextControl, {
	        key: key,
	        type: "text",
	        label: label,
	        value: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    }

	    if (control === 'textarea') {
	      return wp.element.createElement(components.TextareaControl, {
	        key: key,
	        label: label,
	        value: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    } // Return integer value to match ACF field instead of boolean


	    if (BOOL_CONTROLS.includes(control)) {
	      return wp.element.createElement(components.ToggleControl, {
	        key: key,
	        help: help,
	        label: label,
	        checked: attrs[name],
	        onChange: function onChange(value) {
	          return _onChange(name, value ? 1 : 0);
	        }
	      });
	    }

	    if (control === 'url') {
	      return wp.element.createElement(URLControl, {
	        key: key,
	        label: label,
	        value: attrs[name],
	        disableSuggestions: field.disableSuggestions === true,
	        onChange: function onChange(value) {
	          return _onChange(name, value);
	        }
	      });
	    }
	  }));
	  return controls;
	};

	var max$2 = Math.max;
	var min$2 = Math.min;
	var floor$1 = Math.floor;
	var SUBSTITUTION_SYMBOLS = /\$([$&'`]|\d\d?|<[^>]*>)/g;
	var SUBSTITUTION_SYMBOLS_NO_NAMED = /\$([$&'`]|\d\d?)/g;

	var maybeToString = function (it) {
	  return it === undefined ? it : String(it);
	};

	// @@replace logic
	fixRegexpWellKnownSymbolLogic('replace', 2, function (REPLACE, nativeReplace, maybeCallNative, reason) {
	  var REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE = reason.REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE;
	  var REPLACE_KEEPS_$0 = reason.REPLACE_KEEPS_$0;
	  var UNSAFE_SUBSTITUTE = REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE ? '$' : '$0';

	  return [
	    // `String.prototype.replace` method
	    // https://tc39.github.io/ecma262/#sec-string.prototype.replace
	    function replace(searchValue, replaceValue) {
	      var O = requireObjectCoercible(this);
	      var replacer = searchValue == undefined ? undefined : searchValue[REPLACE];
	      return replacer !== undefined
	        ? replacer.call(searchValue, O, replaceValue)
	        : nativeReplace.call(String(O), searchValue, replaceValue);
	    },
	    // `RegExp.prototype[@@replace]` method
	    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@replace
	    function (regexp, replaceValue) {
	      if (
	        (!REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE && REPLACE_KEEPS_$0) ||
	        (typeof replaceValue === 'string' && replaceValue.indexOf(UNSAFE_SUBSTITUTE) === -1)
	      ) {
	        var res = maybeCallNative(nativeReplace, regexp, this, replaceValue);
	        if (res.done) return res.value;
	      }

	      var rx = anObject(regexp);
	      var S = String(this);

	      var functionalReplace = typeof replaceValue === 'function';
	      if (!functionalReplace) replaceValue = String(replaceValue);

	      var global = rx.global;
	      if (global) {
	        var fullUnicode = rx.unicode;
	        rx.lastIndex = 0;
	      }
	      var results = [];
	      while (true) {
	        var result = regexpExecAbstract(rx, S);
	        if (result === null) break;

	        results.push(result);
	        if (!global) break;

	        var matchStr = String(result[0]);
	        if (matchStr === '') rx.lastIndex = advanceStringIndex(S, toLength(rx.lastIndex), fullUnicode);
	      }

	      var accumulatedResult = '';
	      var nextSourcePosition = 0;
	      for (var i = 0; i < results.length; i++) {
	        result = results[i];

	        var matched = String(result[0]);
	        var position = max$2(min$2(toInteger(result.index), S.length), 0);
	        var captures = [];
	        // NOTE: This is equivalent to
	        //   captures = result.slice(1).map(maybeToString)
	        // but for some reason `nativeSlice.call(result, 1, result.length)` (called in
	        // the slice polyfill when slicing native arrays) "doesn't work" in safari 9 and
	        // causes a crash (https://pastebin.com/N21QzeQA) when trying to debug it.
	        for (var j = 1; j < result.length; j++) captures.push(maybeToString(result[j]));
	        var namedCaptures = result.groups;
	        if (functionalReplace) {
	          var replacerArgs = [matched].concat(captures, position, S);
	          if (namedCaptures !== undefined) replacerArgs.push(namedCaptures);
	          var replacement = String(replaceValue.apply(undefined, replacerArgs));
	        } else {
	          replacement = getSubstitution(matched, S, position, captures, namedCaptures, replaceValue);
	        }
	        if (position >= nextSourcePosition) {
	          accumulatedResult += S.slice(nextSourcePosition, position) + replacement;
	          nextSourcePosition = position + matched.length;
	        }
	      }
	      return accumulatedResult + S.slice(nextSourcePosition);
	    }
	  ];

	  // https://tc39.github.io/ecma262/#sec-getsubstitution
	  function getSubstitution(matched, str, position, captures, namedCaptures, replacement) {
	    var tailPos = position + matched.length;
	    var m = captures.length;
	    var symbols = SUBSTITUTION_SYMBOLS_NO_NAMED;
	    if (namedCaptures !== undefined) {
	      namedCaptures = toObject(namedCaptures);
	      symbols = SUBSTITUTION_SYMBOLS;
	    }
	    return nativeReplace.call(replacement, symbols, function (match, ch) {
	      var capture;
	      switch (ch.charAt(0)) {
	        case '$': return '$';
	        case '&': return matched;
	        case '`': return str.slice(0, position);
	        case "'": return str.slice(tailPos);
	        case '<':
	          capture = namedCaptures[ch.slice(1, -1)];
	          break;
	        default: // \d\d?
	          var n = +ch;
	          if (n === 0) return match;
	          if (n > m) {
	            var f = floor$1(n / 10);
	            if (f === 0) return match;
	            if (f <= m) return captures[f - 1] === undefined ? ch.charAt(1) : captures[f - 1] + ch.charAt(1);
	            return match;
	          }
	          capture = captures[n - 1];
	      }
	      return capture === undefined ? '' : capture;
	    });
	  }
	});

	var defineProperty$5 = objectDefineProperty.f;


	var NativeSymbol = global_1.Symbol;

	if (descriptors && typeof NativeSymbol == 'function' && (!('description' in NativeSymbol.prototype) ||
	  // Safari 12 bug
	  NativeSymbol().description !== undefined
	)) {
	  var EmptyStringDescriptionStore = {};
	  // wrap Symbol constructor for correct work with undefined description
	  var SymbolWrapper = function Symbol() {
	    var description = arguments.length < 1 || arguments[0] === undefined ? undefined : String(arguments[0]);
	    var result = this instanceof SymbolWrapper
	      ? new NativeSymbol(description)
	      // in Edge 13, String(Symbol(undefined)) === 'Symbol(undefined)'
	      : description === undefined ? NativeSymbol() : NativeSymbol(description);
	    if (description === '') EmptyStringDescriptionStore[result] = true;
	    return result;
	  };
	  copyConstructorProperties(SymbolWrapper, NativeSymbol);
	  var symbolPrototype = SymbolWrapper.prototype = NativeSymbol.prototype;
	  symbolPrototype.constructor = SymbolWrapper;

	  var symbolToString = symbolPrototype.toString;
	  var native = String(NativeSymbol('test')) == 'Symbol(test)';
	  var regexp = /^Symbol\((.*)\)[^)]+$/;
	  defineProperty$5(symbolPrototype, 'description', {
	    configurable: true,
	    get: function description() {
	      var symbol = isObject(this) ? this.valueOf() : this;
	      var string = symbolToString.call(symbol);
	      if (has(EmptyStringDescriptionStore, symbol)) return '';
	      var desc = native ? string.slice(7, -1) : string.replace(regexp, '$1');
	      return desc === '' ? undefined : desc;
	    }
	  });

	  _export({ global: true, forced: true }, {
	    Symbol: SymbolWrapper
	  });
	}

	// `Symbol.iterator` well-known symbol
	// https://tc39.github.io/ecma262/#sec-symbol.iterator
	defineWellKnownSymbol('iterator');

	// call something on iterator step with safe closing on error
	var callWithSafeIterationClosing = function (iterator, fn, value, ENTRIES) {
	  try {
	    return ENTRIES ? fn(anObject(value)[0], value[1]) : fn(value);
	  // 7.4.6 IteratorClose(iterator, completion)
	  } catch (error) {
	    var returnMethod = iterator['return'];
	    if (returnMethod !== undefined) anObject(returnMethod.call(iterator));
	    throw error;
	  }
	};

	var iterators = {};

	var ITERATOR = wellKnownSymbol('iterator');
	var ArrayPrototype$1 = Array.prototype;

	// check on default Array iterator
	var isArrayIteratorMethod = function (it) {
	  return it !== undefined && (iterators.Array === it || ArrayPrototype$1[ITERATOR] === it);
	};

	var ITERATOR$1 = wellKnownSymbol('iterator');

	var getIteratorMethod = function (it) {
	  if (it != undefined) return it[ITERATOR$1]
	    || it['@@iterator']
	    || iterators[classof(it)];
	};

	// `Array.from` method implementation
	// https://tc39.github.io/ecma262/#sec-array.from
	var arrayFrom = function from(arrayLike /* , mapfn = undefined, thisArg = undefined */) {
	  var O = toObject(arrayLike);
	  var C = typeof this == 'function' ? this : Array;
	  var argumentsLength = arguments.length;
	  var mapfn = argumentsLength > 1 ? arguments[1] : undefined;
	  var mapping = mapfn !== undefined;
	  var iteratorMethod = getIteratorMethod(O);
	  var index = 0;
	  var length, result, step, iterator, next, value;
	  if (mapping) mapfn = functionBindContext(mapfn, argumentsLength > 2 ? arguments[2] : undefined, 2);
	  // if the target is not iterable or it's an array with the default iterator - use a simple case
	  if (iteratorMethod != undefined && !(C == Array && isArrayIteratorMethod(iteratorMethod))) {
	    iterator = iteratorMethod.call(O);
	    next = iterator.next;
	    result = new C();
	    for (;!(step = next.call(iterator)).done; index++) {
	      value = mapping ? callWithSafeIterationClosing(iterator, mapfn, [step.value, index], true) : step.value;
	      createProperty(result, index, value);
	    }
	  } else {
	    length = toLength(O.length);
	    result = new C(length);
	    for (;length > index; index++) {
	      value = mapping ? mapfn(O[index], index) : O[index];
	      createProperty(result, index, value);
	    }
	  }
	  result.length = index;
	  return result;
	};

	var ITERATOR$2 = wellKnownSymbol('iterator');
	var SAFE_CLOSING = false;

	try {
	  var called = 0;
	  var iteratorWithReturn = {
	    next: function () {
	      return { done: !!called++ };
	    },
	    'return': function () {
	      SAFE_CLOSING = true;
	    }
	  };
	  iteratorWithReturn[ITERATOR$2] = function () {
	    return this;
	  };
	  // eslint-disable-next-line no-throw-literal
	  Array.from(iteratorWithReturn, function () { throw 2; });
	} catch (error) { /* empty */ }

	var checkCorrectnessOfIteration = function (exec, SKIP_CLOSING) {
	  if (!SKIP_CLOSING && !SAFE_CLOSING) return false;
	  var ITERATION_SUPPORT = false;
	  try {
	    var object = {};
	    object[ITERATOR$2] = function () {
	      return {
	        next: function () {
	          return { done: ITERATION_SUPPORT = true };
	        }
	      };
	    };
	    exec(object);
	  } catch (error) { /* empty */ }
	  return ITERATION_SUPPORT;
	};

	var INCORRECT_ITERATION = !checkCorrectnessOfIteration(function (iterable) {
	  Array.from(iterable);
	});

	// `Array.from` method
	// https://tc39.github.io/ecma262/#sec-array.from
	_export({ target: 'Array', stat: true, forced: INCORRECT_ITERATION }, {
	  from: arrayFrom
	});

	var correctPrototypeGetter = !fails(function () {
	  function F() { /* empty */ }
	  F.prototype.constructor = null;
	  return Object.getPrototypeOf(new F()) !== F.prototype;
	});

	var IE_PROTO$1 = sharedKey('IE_PROTO');
	var ObjectPrototype$1 = Object.prototype;

	// `Object.getPrototypeOf` method
	// https://tc39.github.io/ecma262/#sec-object.getprototypeof
	var objectGetPrototypeOf = correctPrototypeGetter ? Object.getPrototypeOf : function (O) {
	  O = toObject(O);
	  if (has(O, IE_PROTO$1)) return O[IE_PROTO$1];
	  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
	    return O.constructor.prototype;
	  } return O instanceof Object ? ObjectPrototype$1 : null;
	};

	var ITERATOR$3 = wellKnownSymbol('iterator');
	var BUGGY_SAFARI_ITERATORS = false;

	var returnThis = function () { return this; };

	// `%IteratorPrototype%` object
	// https://tc39.github.io/ecma262/#sec-%iteratorprototype%-object
	var IteratorPrototype, PrototypeOfArrayIteratorPrototype, arrayIterator;

	if ([].keys) {
	  arrayIterator = [].keys();
	  // Safari 8 has buggy iterators w/o `next`
	  if (!('next' in arrayIterator)) BUGGY_SAFARI_ITERATORS = true;
	  else {
	    PrototypeOfArrayIteratorPrototype = objectGetPrototypeOf(objectGetPrototypeOf(arrayIterator));
	    if (PrototypeOfArrayIteratorPrototype !== Object.prototype) IteratorPrototype = PrototypeOfArrayIteratorPrototype;
	  }
	}

	if (IteratorPrototype == undefined) IteratorPrototype = {};

	// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
	if ( !has(IteratorPrototype, ITERATOR$3)) {
	  createNonEnumerableProperty(IteratorPrototype, ITERATOR$3, returnThis);
	}

	var iteratorsCore = {
	  IteratorPrototype: IteratorPrototype,
	  BUGGY_SAFARI_ITERATORS: BUGGY_SAFARI_ITERATORS
	};

	var IteratorPrototype$1 = iteratorsCore.IteratorPrototype;





	var returnThis$1 = function () { return this; };

	var createIteratorConstructor = function (IteratorConstructor, NAME, next) {
	  var TO_STRING_TAG = NAME + ' Iterator';
	  IteratorConstructor.prototype = objectCreate(IteratorPrototype$1, { next: createPropertyDescriptor(1, next) });
	  setToStringTag(IteratorConstructor, TO_STRING_TAG, false);
	  iterators[TO_STRING_TAG] = returnThis$1;
	  return IteratorConstructor;
	};

	var aPossiblePrototype = function (it) {
	  if (!isObject(it) && it !== null) {
	    throw TypeError("Can't set " + String(it) + ' as a prototype');
	  } return it;
	};

	// `Object.setPrototypeOf` method
	// https://tc39.github.io/ecma262/#sec-object.setprototypeof
	// Works with __proto__ only. Old v8 can't work with null proto objects.
	/* eslint-disable no-proto */
	var objectSetPrototypeOf = Object.setPrototypeOf || ('__proto__' in {} ? function () {
	  var CORRECT_SETTER = false;
	  var test = {};
	  var setter;
	  try {
	    setter = Object.getOwnPropertyDescriptor(Object.prototype, '__proto__').set;
	    setter.call(test, []);
	    CORRECT_SETTER = test instanceof Array;
	  } catch (error) { /* empty */ }
	  return function setPrototypeOf(O, proto) {
	    anObject(O);
	    aPossiblePrototype(proto);
	    if (CORRECT_SETTER) setter.call(O, proto);
	    else O.__proto__ = proto;
	    return O;
	  };
	}() : undefined);

	var IteratorPrototype$2 = iteratorsCore.IteratorPrototype;
	var BUGGY_SAFARI_ITERATORS$1 = iteratorsCore.BUGGY_SAFARI_ITERATORS;
	var ITERATOR$4 = wellKnownSymbol('iterator');
	var KEYS = 'keys';
	var VALUES = 'values';
	var ENTRIES = 'entries';

	var returnThis$2 = function () { return this; };

	var defineIterator = function (Iterable, NAME, IteratorConstructor, next, DEFAULT, IS_SET, FORCED) {
	  createIteratorConstructor(IteratorConstructor, NAME, next);

	  var getIterationMethod = function (KIND) {
	    if (KIND === DEFAULT && defaultIterator) return defaultIterator;
	    if (!BUGGY_SAFARI_ITERATORS$1 && KIND in IterablePrototype) return IterablePrototype[KIND];
	    switch (KIND) {
	      case KEYS: return function keys() { return new IteratorConstructor(this, KIND); };
	      case VALUES: return function values() { return new IteratorConstructor(this, KIND); };
	      case ENTRIES: return function entries() { return new IteratorConstructor(this, KIND); };
	    } return function () { return new IteratorConstructor(this); };
	  };

	  var TO_STRING_TAG = NAME + ' Iterator';
	  var INCORRECT_VALUES_NAME = false;
	  var IterablePrototype = Iterable.prototype;
	  var nativeIterator = IterablePrototype[ITERATOR$4]
	    || IterablePrototype['@@iterator']
	    || DEFAULT && IterablePrototype[DEFAULT];
	  var defaultIterator = !BUGGY_SAFARI_ITERATORS$1 && nativeIterator || getIterationMethod(DEFAULT);
	  var anyNativeIterator = NAME == 'Array' ? IterablePrototype.entries || nativeIterator : nativeIterator;
	  var CurrentIteratorPrototype, methods, KEY;

	  // fix native
	  if (anyNativeIterator) {
	    CurrentIteratorPrototype = objectGetPrototypeOf(anyNativeIterator.call(new Iterable()));
	    if (IteratorPrototype$2 !== Object.prototype && CurrentIteratorPrototype.next) {
	      if ( objectGetPrototypeOf(CurrentIteratorPrototype) !== IteratorPrototype$2) {
	        if (objectSetPrototypeOf) {
	          objectSetPrototypeOf(CurrentIteratorPrototype, IteratorPrototype$2);
	        } else if (typeof CurrentIteratorPrototype[ITERATOR$4] != 'function') {
	          createNonEnumerableProperty(CurrentIteratorPrototype, ITERATOR$4, returnThis$2);
	        }
	      }
	      // Set @@toStringTag to native iterators
	      setToStringTag(CurrentIteratorPrototype, TO_STRING_TAG, true);
	    }
	  }

	  // fix Array#{values, @@iterator}.name in V8 / FF
	  if (DEFAULT == VALUES && nativeIterator && nativeIterator.name !== VALUES) {
	    INCORRECT_VALUES_NAME = true;
	    defaultIterator = function values() { return nativeIterator.call(this); };
	  }

	  // define iterator
	  if ( IterablePrototype[ITERATOR$4] !== defaultIterator) {
	    createNonEnumerableProperty(IterablePrototype, ITERATOR$4, defaultIterator);
	  }
	  iterators[NAME] = defaultIterator;

	  // export additional methods
	  if (DEFAULT) {
	    methods = {
	      values: getIterationMethod(VALUES),
	      keys: IS_SET ? defaultIterator : getIterationMethod(KEYS),
	      entries: getIterationMethod(ENTRIES)
	    };
	    if (FORCED) for (KEY in methods) {
	      if (BUGGY_SAFARI_ITERATORS$1 || INCORRECT_VALUES_NAME || !(KEY in IterablePrototype)) {
	        redefine(IterablePrototype, KEY, methods[KEY]);
	      }
	    } else _export({ target: NAME, proto: true, forced: BUGGY_SAFARI_ITERATORS$1 || INCORRECT_VALUES_NAME }, methods);
	  }

	  return methods;
	};

	var ARRAY_ITERATOR = 'Array Iterator';
	var setInternalState$1 = internalState.set;
	var getInternalState$1 = internalState.getterFor(ARRAY_ITERATOR);

	// `Array.prototype.entries` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.entries
	// `Array.prototype.keys` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.keys
	// `Array.prototype.values` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.values
	// `Array.prototype[@@iterator]` method
	// https://tc39.github.io/ecma262/#sec-array.prototype-@@iterator
	// `CreateArrayIterator` internal method
	// https://tc39.github.io/ecma262/#sec-createarrayiterator
	var es_array_iterator = defineIterator(Array, 'Array', function (iterated, kind) {
	  setInternalState$1(this, {
	    type: ARRAY_ITERATOR,
	    target: toIndexedObject(iterated), // target
	    index: 0,                          // next index
	    kind: kind                         // kind
	  });
	// `%ArrayIteratorPrototype%.next` method
	// https://tc39.github.io/ecma262/#sec-%arrayiteratorprototype%.next
	}, function () {
	  var state = getInternalState$1(this);
	  var target = state.target;
	  var kind = state.kind;
	  var index = state.index++;
	  if (!target || index >= target.length) {
	    state.target = undefined;
	    return { value: undefined, done: true };
	  }
	  if (kind == 'keys') return { value: index, done: false };
	  if (kind == 'values') return { value: target[index], done: false };
	  return { value: [index, target[index]], done: false };
	}, 'values');

	// argumentsList[@@iterator] is %ArrayProto_values%
	// https://tc39.github.io/ecma262/#sec-createunmappedargumentsobject
	// https://tc39.github.io/ecma262/#sec-createmappedargumentsobject
	iterators.Arguments = iterators.Array;

	// https://tc39.github.io/ecma262/#sec-array.prototype-@@unscopables
	addToUnscopables('keys');
	addToUnscopables('values');
	addToUnscopables('entries');

	var nativeJoin = [].join;

	var ES3_STRINGS = indexedObject != Object;
	var STRICT_METHOD$3 = arrayMethodIsStrict('join', ',');

	// `Array.prototype.join` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.join
	_export({ target: 'Array', proto: true, forced: ES3_STRINGS || !STRICT_METHOD$3 }, {
	  join: function join(separator) {
	    return nativeJoin.call(toIndexedObject(this), separator === undefined ? ',' : separator);
	  }
	});

	var min$3 = Math.min;
	var nativeLastIndexOf = [].lastIndexOf;
	var NEGATIVE_ZERO$1 = !!nativeLastIndexOf && 1 / [1].lastIndexOf(1, -0) < 0;
	var STRICT_METHOD$4 = arrayMethodIsStrict('lastIndexOf');
	// For preventing possible almost infinite loop in non-standard implementations, test the forward version of the method
	var USES_TO_LENGTH$7 = arrayMethodUsesToLength('indexOf', { ACCESSORS: true, 1: 0 });
	var FORCED$2 = NEGATIVE_ZERO$1 || !STRICT_METHOD$4 || !USES_TO_LENGTH$7;

	// `Array.prototype.lastIndexOf` method implementation
	// https://tc39.github.io/ecma262/#sec-array.prototype.lastindexof
	var arrayLastIndexOf = FORCED$2 ? function lastIndexOf(searchElement /* , fromIndex = @[*-1] */) {
	  // convert -0 to +0
	  if (NEGATIVE_ZERO$1) return nativeLastIndexOf.apply(this, arguments) || 0;
	  var O = toIndexedObject(this);
	  var length = toLength(O.length);
	  var index = length - 1;
	  if (arguments.length > 1) index = min$3(index, toInteger(arguments[1]));
	  if (index < 0) index = length + index;
	  for (;index >= 0; index--) if (index in O && O[index] === searchElement) return index || 0;
	  return -1;
	} : nativeLastIndexOf;

	// `Array.prototype.lastIndexOf` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.lastindexof
	_export({ target: 'Array', proto: true, forced: arrayLastIndexOf !== [].lastIndexOf }, {
	  lastIndexOf: arrayLastIndexOf
	});

	var HAS_SPECIES_SUPPORT$3 = arrayMethodHasSpeciesSupport('splice');
	var USES_TO_LENGTH$8 = arrayMethodUsesToLength('splice', { ACCESSORS: true, 0: 0, 1: 2 });

	var max$3 = Math.max;
	var min$4 = Math.min;
	var MAX_SAFE_INTEGER$1 = 0x1FFFFFFFFFFFFF;
	var MAXIMUM_ALLOWED_LENGTH_EXCEEDED = 'Maximum allowed length exceeded';

	// `Array.prototype.splice` method
	// https://tc39.github.io/ecma262/#sec-array.prototype.splice
	// with adding support of @@species
	_export({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT$3 || !USES_TO_LENGTH$8 }, {
	  splice: function splice(start, deleteCount /* , ...items */) {
	    var O = toObject(this);
	    var len = toLength(O.length);
	    var actualStart = toAbsoluteIndex(start, len);
	    var argumentsLength = arguments.length;
	    var insertCount, actualDeleteCount, A, k, from, to;
	    if (argumentsLength === 0) {
	      insertCount = actualDeleteCount = 0;
	    } else if (argumentsLength === 1) {
	      insertCount = 0;
	      actualDeleteCount = len - actualStart;
	    } else {
	      insertCount = argumentsLength - 2;
	      actualDeleteCount = min$4(max$3(toInteger(deleteCount), 0), len - actualStart);
	    }
	    if (len + insertCount - actualDeleteCount > MAX_SAFE_INTEGER$1) {
	      throw TypeError(MAXIMUM_ALLOWED_LENGTH_EXCEEDED);
	    }
	    A = arraySpeciesCreate(O, actualDeleteCount);
	    for (k = 0; k < actualDeleteCount; k++) {
	      from = actualStart + k;
	      if (from in O) createProperty(A, k, O[from]);
	    }
	    A.length = actualDeleteCount;
	    if (insertCount < actualDeleteCount) {
	      for (k = actualStart; k < len - actualDeleteCount; k++) {
	        from = k + actualDeleteCount;
	        to = k + insertCount;
	        if (from in O) O[to] = O[from];
	        else delete O[to];
	      }
	      for (k = len; k > len - actualDeleteCount + insertCount; k--) delete O[k - 1];
	    } else if (insertCount > actualDeleteCount) {
	      for (k = len - actualDeleteCount; k > actualStart; k--) {
	        from = k + actualDeleteCount - 1;
	        to = k + insertCount - 1;
	        if (from in O) O[to] = O[from];
	        else delete O[to];
	      }
	    }
	    for (k = 0; k < insertCount; k++) {
	      O[k + actualStart] = arguments[k + 2];
	    }
	    O.length = len - actualDeleteCount + insertCount;
	    return A;
	  }
	});

	var freezing = !fails(function () {
	  return Object.isExtensible(Object.preventExtensions({}));
	});

	var internalMetadata = createCommonjsModule(function (module) {
	var defineProperty = objectDefineProperty.f;



	var METADATA = uid('meta');
	var id = 0;

	var isExtensible = Object.isExtensible || function () {
	  return true;
	};

	var setMetadata = function (it) {
	  defineProperty(it, METADATA, { value: {
	    objectID: 'O' + ++id, // object ID
	    weakData: {}          // weak collections IDs
	  } });
	};

	var fastKey = function (it, create) {
	  // return a primitive with prefix
	  if (!isObject(it)) return typeof it == 'symbol' ? it : (typeof it == 'string' ? 'S' : 'P') + it;
	  if (!has(it, METADATA)) {
	    // can't set metadata to uncaught frozen object
	    if (!isExtensible(it)) return 'F';
	    // not necessary to add metadata
	    if (!create) return 'E';
	    // add missing metadata
	    setMetadata(it);
	  // return object ID
	  } return it[METADATA].objectID;
	};

	var getWeakData = function (it, create) {
	  if (!has(it, METADATA)) {
	    // can't set metadata to uncaught frozen object
	    if (!isExtensible(it)) return true;
	    // not necessary to add metadata
	    if (!create) return false;
	    // add missing metadata
	    setMetadata(it);
	  // return the store of weak collections IDs
	  } return it[METADATA].weakData;
	};

	// add metadata on freeze-family methods calling
	var onFreeze = function (it) {
	  if (freezing && meta.REQUIRED && isExtensible(it) && !has(it, METADATA)) setMetadata(it);
	  return it;
	};

	var meta = module.exports = {
	  REQUIRED: false,
	  fastKey: fastKey,
	  getWeakData: getWeakData,
	  onFreeze: onFreeze
	};

	hiddenKeys[METADATA] = true;
	});

	var iterate_1 = createCommonjsModule(function (module) {
	var Result = function (stopped, result) {
	  this.stopped = stopped;
	  this.result = result;
	};

	var iterate = module.exports = function (iterable, fn, that, AS_ENTRIES, IS_ITERATOR) {
	  var boundFunction = functionBindContext(fn, that, AS_ENTRIES ? 2 : 1);
	  var iterator, iterFn, index, length, result, next, step;

	  if (IS_ITERATOR) {
	    iterator = iterable;
	  } else {
	    iterFn = getIteratorMethod(iterable);
	    if (typeof iterFn != 'function') throw TypeError('Target is not iterable');
	    // optimisation for array iterators
	    if (isArrayIteratorMethod(iterFn)) {
	      for (index = 0, length = toLength(iterable.length); length > index; index++) {
	        result = AS_ENTRIES
	          ? boundFunction(anObject(step = iterable[index])[0], step[1])
	          : boundFunction(iterable[index]);
	        if (result && result instanceof Result) return result;
	      } return new Result(false);
	    }
	    iterator = iterFn.call(iterable);
	  }

	  next = iterator.next;
	  while (!(step = next.call(iterator)).done) {
	    result = callWithSafeIterationClosing(iterator, boundFunction, step.value, AS_ENTRIES);
	    if (typeof result == 'object' && result && result instanceof Result) return result;
	  } return new Result(false);
	};

	iterate.stop = function (result) {
	  return new Result(true, result);
	};
	});

	var anInstance = function (it, Constructor, name) {
	  if (!(it instanceof Constructor)) {
	    throw TypeError('Incorrect ' + (name ? name + ' ' : '') + 'invocation');
	  } return it;
	};

	// makes subclassing work correct for wrapped built-ins
	var inheritIfRequired = function ($this, dummy, Wrapper) {
	  var NewTarget, NewTargetPrototype;
	  if (
	    // it can work only with native `setPrototypeOf`
	    objectSetPrototypeOf &&
	    // we haven't completely correct pre-ES6 way for getting `new.target`, so use this
	    typeof (NewTarget = dummy.constructor) == 'function' &&
	    NewTarget !== Wrapper &&
	    isObject(NewTargetPrototype = NewTarget.prototype) &&
	    NewTargetPrototype !== Wrapper.prototype
	  ) objectSetPrototypeOf($this, NewTargetPrototype);
	  return $this;
	};

	var collection = function (CONSTRUCTOR_NAME, wrapper, common) {
	  var IS_MAP = CONSTRUCTOR_NAME.indexOf('Map') !== -1;
	  var IS_WEAK = CONSTRUCTOR_NAME.indexOf('Weak') !== -1;
	  var ADDER = IS_MAP ? 'set' : 'add';
	  var NativeConstructor = global_1[CONSTRUCTOR_NAME];
	  var NativePrototype = NativeConstructor && NativeConstructor.prototype;
	  var Constructor = NativeConstructor;
	  var exported = {};

	  var fixMethod = function (KEY) {
	    var nativeMethod = NativePrototype[KEY];
	    redefine(NativePrototype, KEY,
	      KEY == 'add' ? function add(value) {
	        nativeMethod.call(this, value === 0 ? 0 : value);
	        return this;
	      } : KEY == 'delete' ? function (key) {
	        return IS_WEAK && !isObject(key) ? false : nativeMethod.call(this, key === 0 ? 0 : key);
	      } : KEY == 'get' ? function get(key) {
	        return IS_WEAK && !isObject(key) ? undefined : nativeMethod.call(this, key === 0 ? 0 : key);
	      } : KEY == 'has' ? function has(key) {
	        return IS_WEAK && !isObject(key) ? false : nativeMethod.call(this, key === 0 ? 0 : key);
	      } : function set(key, value) {
	        nativeMethod.call(this, key === 0 ? 0 : key, value);
	        return this;
	      }
	    );
	  };

	  // eslint-disable-next-line max-len
	  if (isForced_1(CONSTRUCTOR_NAME, typeof NativeConstructor != 'function' || !(IS_WEAK || NativePrototype.forEach && !fails(function () {
	    new NativeConstructor().entries().next();
	  })))) {
	    // create collection constructor
	    Constructor = common.getConstructor(wrapper, CONSTRUCTOR_NAME, IS_MAP, ADDER);
	    internalMetadata.REQUIRED = true;
	  } else if (isForced_1(CONSTRUCTOR_NAME, true)) {
	    var instance = new Constructor();
	    // early implementations not supports chaining
	    var HASNT_CHAINING = instance[ADDER](IS_WEAK ? {} : -0, 1) != instance;
	    // V8 ~ Chromium 40- weak-collections throws on primitives, but should return false
	    var THROWS_ON_PRIMITIVES = fails(function () { instance.has(1); });
	    // most early implementations doesn't supports iterables, most modern - not close it correctly
	    // eslint-disable-next-line no-new
	    var ACCEPT_ITERABLES = checkCorrectnessOfIteration(function (iterable) { new NativeConstructor(iterable); });
	    // for early implementations -0 and +0 not the same
	    var BUGGY_ZERO = !IS_WEAK && fails(function () {
	      // V8 ~ Chromium 42- fails only with 5+ elements
	      var $instance = new NativeConstructor();
	      var index = 5;
	      while (index--) $instance[ADDER](index, index);
	      return !$instance.has(-0);
	    });

	    if (!ACCEPT_ITERABLES) {
	      Constructor = wrapper(function (dummy, iterable) {
	        anInstance(dummy, Constructor, CONSTRUCTOR_NAME);
	        var that = inheritIfRequired(new NativeConstructor(), dummy, Constructor);
	        if (iterable != undefined) iterate_1(iterable, that[ADDER], that, IS_MAP);
	        return that;
	      });
	      Constructor.prototype = NativePrototype;
	      NativePrototype.constructor = Constructor;
	    }

	    if (THROWS_ON_PRIMITIVES || BUGGY_ZERO) {
	      fixMethod('delete');
	      fixMethod('has');
	      IS_MAP && fixMethod('get');
	    }

	    if (BUGGY_ZERO || HASNT_CHAINING) fixMethod(ADDER);

	    // weak collections should not contains .clear method
	    if (IS_WEAK && NativePrototype.clear) delete NativePrototype.clear;
	  }

	  exported[CONSTRUCTOR_NAME] = Constructor;
	  _export({ global: true, forced: Constructor != NativeConstructor }, exported);

	  setToStringTag(Constructor, CONSTRUCTOR_NAME);

	  if (!IS_WEAK) common.setStrong(Constructor, CONSTRUCTOR_NAME, IS_MAP);

	  return Constructor;
	};

	var redefineAll = function (target, src, options) {
	  for (var key in src) redefine(target, key, src[key], options);
	  return target;
	};

	var SPECIES$4 = wellKnownSymbol('species');

	var setSpecies = function (CONSTRUCTOR_NAME) {
	  var Constructor = getBuiltIn(CONSTRUCTOR_NAME);
	  var defineProperty = objectDefineProperty.f;

	  if (descriptors && Constructor && !Constructor[SPECIES$4]) {
	    defineProperty(Constructor, SPECIES$4, {
	      configurable: true,
	      get: function () { return this; }
	    });
	  }
	};

	var defineProperty$6 = objectDefineProperty.f;








	var fastKey = internalMetadata.fastKey;


	var setInternalState$2 = internalState.set;
	var internalStateGetterFor = internalState.getterFor;

	var collectionStrong = {
	  getConstructor: function (wrapper, CONSTRUCTOR_NAME, IS_MAP, ADDER) {
	    var C = wrapper(function (that, iterable) {
	      anInstance(that, C, CONSTRUCTOR_NAME);
	      setInternalState$2(that, {
	        type: CONSTRUCTOR_NAME,
	        index: objectCreate(null),
	        first: undefined,
	        last: undefined,
	        size: 0
	      });
	      if (!descriptors) that.size = 0;
	      if (iterable != undefined) iterate_1(iterable, that[ADDER], that, IS_MAP);
	    });

	    var getInternalState = internalStateGetterFor(CONSTRUCTOR_NAME);

	    var define = function (that, key, value) {
	      var state = getInternalState(that);
	      var entry = getEntry(that, key);
	      var previous, index;
	      // change existing entry
	      if (entry) {
	        entry.value = value;
	      // create new entry
	      } else {
	        state.last = entry = {
	          index: index = fastKey(key, true),
	          key: key,
	          value: value,
	          previous: previous = state.last,
	          next: undefined,
	          removed: false
	        };
	        if (!state.first) state.first = entry;
	        if (previous) previous.next = entry;
	        if (descriptors) state.size++;
	        else that.size++;
	        // add to index
	        if (index !== 'F') state.index[index] = entry;
	      } return that;
	    };

	    var getEntry = function (that, key) {
	      var state = getInternalState(that);
	      // fast case
	      var index = fastKey(key);
	      var entry;
	      if (index !== 'F') return state.index[index];
	      // frozen object case
	      for (entry = state.first; entry; entry = entry.next) {
	        if (entry.key == key) return entry;
	      }
	    };

	    redefineAll(C.prototype, {
	      // 23.1.3.1 Map.prototype.clear()
	      // 23.2.3.2 Set.prototype.clear()
	      clear: function clear() {
	        var that = this;
	        var state = getInternalState(that);
	        var data = state.index;
	        var entry = state.first;
	        while (entry) {
	          entry.removed = true;
	          if (entry.previous) entry.previous = entry.previous.next = undefined;
	          delete data[entry.index];
	          entry = entry.next;
	        }
	        state.first = state.last = undefined;
	        if (descriptors) state.size = 0;
	        else that.size = 0;
	      },
	      // 23.1.3.3 Map.prototype.delete(key)
	      // 23.2.3.4 Set.prototype.delete(value)
	      'delete': function (key) {
	        var that = this;
	        var state = getInternalState(that);
	        var entry = getEntry(that, key);
	        if (entry) {
	          var next = entry.next;
	          var prev = entry.previous;
	          delete state.index[entry.index];
	          entry.removed = true;
	          if (prev) prev.next = next;
	          if (next) next.previous = prev;
	          if (state.first == entry) state.first = next;
	          if (state.last == entry) state.last = prev;
	          if (descriptors) state.size--;
	          else that.size--;
	        } return !!entry;
	      },
	      // 23.2.3.6 Set.prototype.forEach(callbackfn, thisArg = undefined)
	      // 23.1.3.5 Map.prototype.forEach(callbackfn, thisArg = undefined)
	      forEach: function forEach(callbackfn /* , that = undefined */) {
	        var state = getInternalState(this);
	        var boundFunction = functionBindContext(callbackfn, arguments.length > 1 ? arguments[1] : undefined, 3);
	        var entry;
	        while (entry = entry ? entry.next : state.first) {
	          boundFunction(entry.value, entry.key, this);
	          // revert to the last existing entry
	          while (entry && entry.removed) entry = entry.previous;
	        }
	      },
	      // 23.1.3.7 Map.prototype.has(key)
	      // 23.2.3.7 Set.prototype.has(value)
	      has: function has(key) {
	        return !!getEntry(this, key);
	      }
	    });

	    redefineAll(C.prototype, IS_MAP ? {
	      // 23.1.3.6 Map.prototype.get(key)
	      get: function get(key) {
	        var entry = getEntry(this, key);
	        return entry && entry.value;
	      },
	      // 23.1.3.9 Map.prototype.set(key, value)
	      set: function set(key, value) {
	        return define(this, key === 0 ? 0 : key, value);
	      }
	    } : {
	      // 23.2.3.1 Set.prototype.add(value)
	      add: function add(value) {
	        return define(this, value = value === 0 ? 0 : value, value);
	      }
	    });
	    if (descriptors) defineProperty$6(C.prototype, 'size', {
	      get: function () {
	        return getInternalState(this).size;
	      }
	    });
	    return C;
	  },
	  setStrong: function (C, CONSTRUCTOR_NAME, IS_MAP) {
	    var ITERATOR_NAME = CONSTRUCTOR_NAME + ' Iterator';
	    var getInternalCollectionState = internalStateGetterFor(CONSTRUCTOR_NAME);
	    var getInternalIteratorState = internalStateGetterFor(ITERATOR_NAME);
	    // add .keys, .values, .entries, [@@iterator]
	    // 23.1.3.4, 23.1.3.8, 23.1.3.11, 23.1.3.12, 23.2.3.5, 23.2.3.8, 23.2.3.10, 23.2.3.11
	    defineIterator(C, CONSTRUCTOR_NAME, function (iterated, kind) {
	      setInternalState$2(this, {
	        type: ITERATOR_NAME,
	        target: iterated,
	        state: getInternalCollectionState(iterated),
	        kind: kind,
	        last: undefined
	      });
	    }, function () {
	      var state = getInternalIteratorState(this);
	      var kind = state.kind;
	      var entry = state.last;
	      // revert to the last existing entry
	      while (entry && entry.removed) entry = entry.previous;
	      // get next entry
	      if (!state.target || !(state.last = entry = entry ? entry.next : state.state.first)) {
	        // or finish the iteration
	        state.target = undefined;
	        return { value: undefined, done: true };
	      }
	      // return step by kind
	      if (kind == 'keys') return { value: entry.key, done: false };
	      if (kind == 'values') return { value: entry.value, done: false };
	      return { value: [entry.key, entry.value], done: false };
	    }, IS_MAP ? 'entries' : 'values', !IS_MAP, true);

	    // add [@@species], 23.1.2.2, 23.2.2.2
	    setSpecies(CONSTRUCTOR_NAME);
	  }
	};

	// `Map` constructor
	// https://tc39.github.io/ecma262/#sec-map-objects
	var es_map = collection('Map', function (init) {
	  return function Map() { return init(this, arguments.length ? arguments[0] : undefined); };
	}, collectionStrong);

	// a string of all valid unicode whitespaces
	// eslint-disable-next-line max-len
	var whitespaces = '\u0009\u000A\u000B\u000C\u000D\u0020\u00A0\u1680\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u202F\u205F\u3000\u2028\u2029\uFEFF';

	var whitespace = '[' + whitespaces + ']';
	var ltrim = RegExp('^' + whitespace + whitespace + '*');
	var rtrim = RegExp(whitespace + whitespace + '*$');

	// `String.prototype.{ trim, trimStart, trimEnd, trimLeft, trimRight }` methods implementation
	var createMethod$5 = function (TYPE) {
	  return function ($this) {
	    var string = String(requireObjectCoercible($this));
	    if (TYPE & 1) string = string.replace(ltrim, '');
	    if (TYPE & 2) string = string.replace(rtrim, '');
	    return string;
	  };
	};

	var stringTrim = {
	  // `String.prototype.{ trimLeft, trimStart }` methods
	  // https://tc39.github.io/ecma262/#sec-string.prototype.trimstart
	  start: createMethod$5(1),
	  // `String.prototype.{ trimRight, trimEnd }` methods
	  // https://tc39.github.io/ecma262/#sec-string.prototype.trimend
	  end: createMethod$5(2),
	  // `String.prototype.trim` method
	  // https://tc39.github.io/ecma262/#sec-string.prototype.trim
	  trim: createMethod$5(3)
	};

	var getOwnPropertyNames = objectGetOwnPropertyNames.f;
	var getOwnPropertyDescriptor$2 = objectGetOwnPropertyDescriptor.f;
	var defineProperty$7 = objectDefineProperty.f;
	var trim = stringTrim.trim;

	var NUMBER = 'Number';
	var NativeNumber = global_1[NUMBER];
	var NumberPrototype = NativeNumber.prototype;

	// Opera ~12 has broken Object#toString
	var BROKEN_CLASSOF = classofRaw(objectCreate(NumberPrototype)) == NUMBER;

	// `ToNumber` abstract operation
	// https://tc39.github.io/ecma262/#sec-tonumber
	var toNumber = function (argument) {
	  var it = toPrimitive(argument, false);
	  var first, third, radix, maxCode, digits, length, index, code;
	  if (typeof it == 'string' && it.length > 2) {
	    it = trim(it);
	    first = it.charCodeAt(0);
	    if (first === 43 || first === 45) {
	      third = it.charCodeAt(2);
	      if (third === 88 || third === 120) return NaN; // Number('+0x1') should be NaN, old V8 fix
	    } else if (first === 48) {
	      switch (it.charCodeAt(1)) {
	        case 66: case 98: radix = 2; maxCode = 49; break; // fast equal of /^0b[01]+$/i
	        case 79: case 111: radix = 8; maxCode = 55; break; // fast equal of /^0o[0-7]+$/i
	        default: return +it;
	      }
	      digits = it.slice(2);
	      length = digits.length;
	      for (index = 0; index < length; index++) {
	        code = digits.charCodeAt(index);
	        // parseInt parses a string to a first unavailable symbol
	        // but ToNumber should return NaN if a string contains unavailable symbols
	        if (code < 48 || code > maxCode) return NaN;
	      } return parseInt(digits, radix);
	    }
	  } return +it;
	};

	// `Number` constructor
	// https://tc39.github.io/ecma262/#sec-number-constructor
	if (isForced_1(NUMBER, !NativeNumber(' 0o1') || !NativeNumber('0b1') || NativeNumber('+0x1'))) {
	  var NumberWrapper = function Number(value) {
	    var it = arguments.length < 1 ? 0 : value;
	    var dummy = this;
	    return dummy instanceof NumberWrapper
	      // check on 1..constructor(foo) case
	      && (BROKEN_CLASSOF ? fails(function () { NumberPrototype.valueOf.call(dummy); }) : classofRaw(dummy) != NUMBER)
	        ? inheritIfRequired(new NativeNumber(toNumber(it)), dummy, NumberWrapper) : toNumber(it);
	  };
	  for (var keys$1 = descriptors ? getOwnPropertyNames(NativeNumber) : (
	    // ES3:
	    'MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,' +
	    // ES2015 (in case, if modules with ES2015 Number statics required before):
	    'EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,' +
	    'MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger'
	  ).split(','), j = 0, key; keys$1.length > j; j++) {
	    if (has(NativeNumber, key = keys$1[j]) && !has(NumberWrapper, key)) {
	      defineProperty$7(NumberWrapper, key, getOwnPropertyDescriptor$2(NativeNumber, key));
	    }
	  }
	  NumberWrapper.prototype = NumberPrototype;
	  NumberPrototype.constructor = NumberWrapper;
	  redefine(global_1, NUMBER, NumberWrapper);
	}

	// `Number.isNaN` method
	// https://tc39.github.io/ecma262/#sec-number.isnan
	_export({ target: 'Number', stat: true }, {
	  isNaN: function isNaN(number) {
	    // eslint-disable-next-line no-self-compare
	    return number != number;
	  }
	});

	var nativeGetOwnPropertyNames$2 = objectGetOwnPropertyNamesExternal.f;

	var FAILS_ON_PRIMITIVES$2 = fails(function () { return !Object.getOwnPropertyNames(1); });

	// `Object.getOwnPropertyNames` method
	// https://tc39.github.io/ecma262/#sec-object.getownpropertynames
	_export({ target: 'Object', stat: true, forced: FAILS_ON_PRIMITIVES$2 }, {
	  getOwnPropertyNames: nativeGetOwnPropertyNames$2
	});

	var FAILS_ON_PRIMITIVES$3 = fails(function () { objectGetPrototypeOf(1); });

	// `Object.getPrototypeOf` method
	// https://tc39.github.io/ecma262/#sec-object.getprototypeof
	_export({ target: 'Object', stat: true, forced: FAILS_ON_PRIMITIVES$3, sham: !correctPrototypeGetter }, {
	  getPrototypeOf: function getPrototypeOf(it) {
	    return objectGetPrototypeOf(toObject(it));
	  }
	});

	var nativeApply = getBuiltIn('Reflect', 'apply');
	var functionApply = Function.apply;

	// MS Edge argumentsList argument is optional
	var OPTIONAL_ARGUMENTS_LIST = !fails(function () {
	  nativeApply(function () { /* empty */ });
	});

	// `Reflect.apply` method
	// https://tc39.github.io/ecma262/#sec-reflect.apply
	_export({ target: 'Reflect', stat: true, forced: OPTIONAL_ARGUMENTS_LIST }, {
	  apply: function apply(target, thisArgument, argumentsList) {
	    aFunction$1(target);
	    anObject(argumentsList);
	    return nativeApply
	      ? nativeApply(target, thisArgument, argumentsList)
	      : functionApply.call(target, thisArgument, argumentsList);
	  }
	});

	// `Reflect.ownKeys` method
	// https://tc39.github.io/ecma262/#sec-reflect.ownkeys
	_export({ target: 'Reflect', stat: true }, {
	  ownKeys: ownKeys
	});

	var defineProperty$8 = objectDefineProperty.f;
	var getOwnPropertyNames$1 = objectGetOwnPropertyNames.f;





	var setInternalState$3 = internalState.set;



	var MATCH$2 = wellKnownSymbol('match');
	var NativeRegExp = global_1.RegExp;
	var RegExpPrototype$1 = NativeRegExp.prototype;
	var re1 = /a/g;
	var re2 = /a/g;

	// "new" should create a new object, old webkit bug
	var CORRECT_NEW = new NativeRegExp(re1) !== re1;

	var UNSUPPORTED_Y$2 = regexpStickyHelpers.UNSUPPORTED_Y;

	var FORCED$3 = descriptors && isForced_1('RegExp', (!CORRECT_NEW || UNSUPPORTED_Y$2 || fails(function () {
	  re2[MATCH$2] = false;
	  // RegExp constructor can alter flags and IsRegExp works correct with @@match
	  return NativeRegExp(re1) != re1 || NativeRegExp(re2) == re2 || NativeRegExp(re1, 'i') != '/a/i';
	})));

	// `RegExp` constructor
	// https://tc39.github.io/ecma262/#sec-regexp-constructor
	if (FORCED$3) {
	  var RegExpWrapper = function RegExp(pattern, flags) {
	    var thisIsRegExp = this instanceof RegExpWrapper;
	    var patternIsRegExp = isRegexp(pattern);
	    var flagsAreUndefined = flags === undefined;
	    var sticky;

	    if (!thisIsRegExp && patternIsRegExp && pattern.constructor === RegExpWrapper && flagsAreUndefined) {
	      return pattern;
	    }

	    if (CORRECT_NEW) {
	      if (patternIsRegExp && !flagsAreUndefined) pattern = pattern.source;
	    } else if (pattern instanceof RegExpWrapper) {
	      if (flagsAreUndefined) flags = regexpFlags.call(pattern);
	      pattern = pattern.source;
	    }

	    if (UNSUPPORTED_Y$2) {
	      sticky = !!flags && flags.indexOf('y') > -1;
	      if (sticky) flags = flags.replace(/y/g, '');
	    }

	    var result = inheritIfRequired(
	      CORRECT_NEW ? new NativeRegExp(pattern, flags) : NativeRegExp(pattern, flags),
	      thisIsRegExp ? this : RegExpPrototype$1,
	      RegExpWrapper
	    );

	    if (UNSUPPORTED_Y$2 && sticky) setInternalState$3(result, { sticky: sticky });

	    return result;
	  };
	  var proxy = function (key) {
	    key in RegExpWrapper || defineProperty$8(RegExpWrapper, key, {
	      configurable: true,
	      get: function () { return NativeRegExp[key]; },
	      set: function (it) { NativeRegExp[key] = it; }
	    });
	  };
	  var keys$2 = getOwnPropertyNames$1(NativeRegExp);
	  var index = 0;
	  while (keys$2.length > index) proxy(keys$2[index++]);
	  RegExpPrototype$1.constructor = RegExpWrapper;
	  RegExpWrapper.prototype = RegExpPrototype$1;
	  redefine(global_1, 'RegExp', RegExpWrapper);
	}

	// https://tc39.github.io/ecma262/#sec-get-regexp-@@species
	setSpecies('RegExp');

	// `Set` constructor
	// https://tc39.github.io/ecma262/#sec-set-objects
	var es_set = collection('Set', function (init) {
	  return function Set() { return init(this, arguments.length ? arguments[0] : undefined); };
	}, collectionStrong);

	var charAt$1 = stringMultibyte.charAt;



	var STRING_ITERATOR = 'String Iterator';
	var setInternalState$4 = internalState.set;
	var getInternalState$2 = internalState.getterFor(STRING_ITERATOR);

	// `String.prototype[@@iterator]` method
	// https://tc39.github.io/ecma262/#sec-string.prototype-@@iterator
	defineIterator(String, 'String', function (iterated) {
	  setInternalState$4(this, {
	    type: STRING_ITERATOR,
	    string: String(iterated),
	    index: 0
	  });
	// `%StringIteratorPrototype%.next` method
	// https://tc39.github.io/ecma262/#sec-%stringiteratorprototype%.next
	}, function next() {
	  var state = getInternalState$2(this);
	  var string = state.string;
	  var index = state.index;
	  var point;
	  if (index >= string.length) return { value: undefined, done: true };
	  point = charAt$1(string, index);
	  state.index += point.length;
	  return { value: point, done: false };
	});

	// `String.prototype.repeat` method implementation
	// https://tc39.github.io/ecma262/#sec-string.prototype.repeat
	var stringRepeat = ''.repeat || function repeat(count) {
	  var str = String(requireObjectCoercible(this));
	  var result = '';
	  var n = toInteger(count);
	  if (n < 0 || n == Infinity) throw RangeError('Wrong number of repetitions');
	  for (;n > 0; (n >>>= 1) && (str += str)) if (n & 1) result += str;
	  return result;
	};

	// `String.prototype.repeat` method
	// https://tc39.github.io/ecma262/#sec-string.prototype.repeat
	_export({ target: 'String', proto: true }, {
	  repeat: stringRepeat
	});

	var SPECIES$5 = wellKnownSymbol('species');

	// `SpeciesConstructor` abstract operation
	// https://tc39.github.io/ecma262/#sec-speciesconstructor
	var speciesConstructor = function (O, defaultConstructor) {
	  var C = anObject(O).constructor;
	  var S;
	  return C === undefined || (S = anObject(C)[SPECIES$5]) == undefined ? defaultConstructor : aFunction$1(S);
	};

	var arrayPush = [].push;
	var min$5 = Math.min;
	var MAX_UINT32 = 0xFFFFFFFF;

	// babel-minify transpiles RegExp('x', 'y') -> /x/y and it causes SyntaxError
	var SUPPORTS_Y = !fails(function () { return !RegExp(MAX_UINT32, 'y'); });

	// @@split logic
	fixRegexpWellKnownSymbolLogic('split', 2, function (SPLIT, nativeSplit, maybeCallNative) {
	  var internalSplit;
	  if (
	    'abbc'.split(/(b)*/)[1] == 'c' ||
	    'test'.split(/(?:)/, -1).length != 4 ||
	    'ab'.split(/(?:ab)*/).length != 2 ||
	    '.'.split(/(.?)(.?)/).length != 4 ||
	    '.'.split(/()()/).length > 1 ||
	    ''.split(/.?/).length
	  ) {
	    // based on es5-shim implementation, need to rework it
	    internalSplit = function (separator, limit) {
	      var string = String(requireObjectCoercible(this));
	      var lim = limit === undefined ? MAX_UINT32 : limit >>> 0;
	      if (lim === 0) return [];
	      if (separator === undefined) return [string];
	      // If `separator` is not a regex, use native split
	      if (!isRegexp(separator)) {
	        return nativeSplit.call(string, separator, lim);
	      }
	      var output = [];
	      var flags = (separator.ignoreCase ? 'i' : '') +
	                  (separator.multiline ? 'm' : '') +
	                  (separator.unicode ? 'u' : '') +
	                  (separator.sticky ? 'y' : '');
	      var lastLastIndex = 0;
	      // Make `global` and avoid `lastIndex` issues by working with a copy
	      var separatorCopy = new RegExp(separator.source, flags + 'g');
	      var match, lastIndex, lastLength;
	      while (match = regexpExec.call(separatorCopy, string)) {
	        lastIndex = separatorCopy.lastIndex;
	        if (lastIndex > lastLastIndex) {
	          output.push(string.slice(lastLastIndex, match.index));
	          if (match.length > 1 && match.index < string.length) arrayPush.apply(output, match.slice(1));
	          lastLength = match[0].length;
	          lastLastIndex = lastIndex;
	          if (output.length >= lim) break;
	        }
	        if (separatorCopy.lastIndex === match.index) separatorCopy.lastIndex++; // Avoid an infinite loop
	      }
	      if (lastLastIndex === string.length) {
	        if (lastLength || !separatorCopy.test('')) output.push('');
	      } else output.push(string.slice(lastLastIndex));
	      return output.length > lim ? output.slice(0, lim) : output;
	    };
	  // Chakra, V8
	  } else if ('0'.split(undefined, 0).length) {
	    internalSplit = function (separator, limit) {
	      return separator === undefined && limit === 0 ? [] : nativeSplit.call(this, separator, limit);
	    };
	  } else internalSplit = nativeSplit;

	  return [
	    // `String.prototype.split` method
	    // https://tc39.github.io/ecma262/#sec-string.prototype.split
	    function split(separator, limit) {
	      var O = requireObjectCoercible(this);
	      var splitter = separator == undefined ? undefined : separator[SPLIT];
	      return splitter !== undefined
	        ? splitter.call(separator, O, limit)
	        : internalSplit.call(String(O), separator, limit);
	    },
	    // `RegExp.prototype[@@split]` method
	    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@split
	    //
	    // NOTE: This cannot be properly polyfilled in engines that don't support
	    // the 'y' flag.
	    function (regexp, limit) {
	      var res = maybeCallNative(internalSplit, regexp, this, limit, internalSplit !== nativeSplit);
	      if (res.done) return res.value;

	      var rx = anObject(regexp);
	      var S = String(this);
	      var C = speciesConstructor(rx, RegExp);

	      var unicodeMatching = rx.unicode;
	      var flags = (rx.ignoreCase ? 'i' : '') +
	                  (rx.multiline ? 'm' : '') +
	                  (rx.unicode ? 'u' : '') +
	                  (SUPPORTS_Y ? 'y' : 'g');

	      // ^(? + rx + ) is needed, in combination with some S slicing, to
	      // simulate the 'y' flag.
	      var splitter = new C(SUPPORTS_Y ? rx : '^(?:' + rx.source + ')', flags);
	      var lim = limit === undefined ? MAX_UINT32 : limit >>> 0;
	      if (lim === 0) return [];
	      if (S.length === 0) return regexpExecAbstract(splitter, S) === null ? [S] : [];
	      var p = 0;
	      var q = 0;
	      var A = [];
	      while (q < S.length) {
	        splitter.lastIndex = SUPPORTS_Y ? q : 0;
	        var z = regexpExecAbstract(splitter, SUPPORTS_Y ? S : S.slice(q));
	        var e;
	        if (
	          z === null ||
	          (e = min$5(toLength(splitter.lastIndex + (SUPPORTS_Y ? 0 : q)), S.length)) === p
	        ) {
	          q = advanceStringIndex(S, q, unicodeMatching);
	        } else {
	          A.push(S.slice(p, q));
	          if (A.length === lim) return A;
	          for (var i = 1; i <= z.length - 1; i++) {
	            A.push(z[i]);
	            if (A.length === lim) return A;
	          }
	          q = p = e;
	        }
	      }
	      A.push(S.slice(p));
	      return A;
	    }
	  ];
	}, !SUPPORTS_Y);

	var non = '\u200B\u0085\u180E';

	// check that a method works with the correct list
	// of whitespaces and has a correct name
	var stringTrimForced = function (METHOD_NAME) {
	  return fails(function () {
	    return !!whitespaces[METHOD_NAME]() || non[METHOD_NAME]() != non || whitespaces[METHOD_NAME].name !== METHOD_NAME;
	  });
	};

	var $trim = stringTrim.trim;


	// `String.prototype.trim` method
	// https://tc39.github.io/ecma262/#sec-string.prototype.trim
	_export({ target: 'String', proto: true, forced: stringTrimForced('trim') }, {
	  trim: function trim() {
	    return $trim(this);
	  }
	});

	var ITERATOR$5 = wellKnownSymbol('iterator');
	var TO_STRING_TAG$3 = wellKnownSymbol('toStringTag');
	var ArrayValues = es_array_iterator.values;

	for (var COLLECTION_NAME$1 in domIterables) {
	  var Collection$1 = global_1[COLLECTION_NAME$1];
	  var CollectionPrototype$1 = Collection$1 && Collection$1.prototype;
	  if (CollectionPrototype$1) {
	    // some Chrome versions have non-configurable methods on DOMTokenList
	    if (CollectionPrototype$1[ITERATOR$5] !== ArrayValues) try {
	      createNonEnumerableProperty(CollectionPrototype$1, ITERATOR$5, ArrayValues);
	    } catch (error) {
	      CollectionPrototype$1[ITERATOR$5] = ArrayValues;
	    }
	    if (!CollectionPrototype$1[TO_STRING_TAG$3]) {
	      createNonEnumerableProperty(CollectionPrototype$1, TO_STRING_TAG$3, COLLECTION_NAME$1);
	    }
	    if (domIterables[COLLECTION_NAME$1]) for (var METHOD_NAME in es_array_iterator) {
	      // some Chrome versions have non-configurable methods on DOMTokenList
	      if (CollectionPrototype$1[METHOD_NAME] !== es_array_iterator[METHOD_NAME]) try {
	        createNonEnumerableProperty(CollectionPrototype$1, METHOD_NAME, es_array_iterator[METHOD_NAME]);
	      } catch (error) {
	        CollectionPrototype$1[METHOD_NAME] = es_array_iterator[METHOD_NAME];
	      }
	    }
	  }
	}

	var engineIsIos = /(iphone|ipod|ipad).*applewebkit/i.test(engineUserAgent);

	var location = global_1.location;
	var set$1 = global_1.setImmediate;
	var clear = global_1.clearImmediate;
	var process$2 = global_1.process;
	var MessageChannel = global_1.MessageChannel;
	var Dispatch = global_1.Dispatch;
	var counter = 0;
	var queue = {};
	var ONREADYSTATECHANGE = 'onreadystatechange';
	var defer, channel, port;

	var run = function (id) {
	  // eslint-disable-next-line no-prototype-builtins
	  if (queue.hasOwnProperty(id)) {
	    var fn = queue[id];
	    delete queue[id];
	    fn();
	  }
	};

	var runner = function (id) {
	  return function () {
	    run(id);
	  };
	};

	var listener = function (event) {
	  run(event.data);
	};

	var post = function (id) {
	  // old engines have not location.origin
	  global_1.postMessage(id + '', location.protocol + '//' + location.host);
	};

	// Node.js 0.9+ & IE10+ has setImmediate, otherwise:
	if (!set$1 || !clear) {
	  set$1 = function setImmediate(fn) {
	    var args = [];
	    var i = 1;
	    while (arguments.length > i) args.push(arguments[i++]);
	    queue[++counter] = function () {
	      // eslint-disable-next-line no-new-func
	      (typeof fn == 'function' ? fn : Function(fn)).apply(undefined, args);
	    };
	    defer(counter);
	    return counter;
	  };
	  clear = function clearImmediate(id) {
	    delete queue[id];
	  };
	  // Node.js 0.8-
	  if (classofRaw(process$2) == 'process') {
	    defer = function (id) {
	      process$2.nextTick(runner(id));
	    };
	  // Sphere (JS game engine) Dispatch API
	  } else if (Dispatch && Dispatch.now) {
	    defer = function (id) {
	      Dispatch.now(runner(id));
	    };
	  // Browsers with MessageChannel, includes WebWorkers
	  // except iOS - https://github.com/zloirock/core-js/issues/624
	  } else if (MessageChannel && !engineIsIos) {
	    channel = new MessageChannel();
	    port = channel.port2;
	    channel.port1.onmessage = listener;
	    defer = functionBindContext(port.postMessage, port, 1);
	  // Browsers with postMessage, skip WebWorkers
	  // IE8 has postMessage, but it's sync & typeof its postMessage is 'object'
	  } else if (
	    global_1.addEventListener &&
	    typeof postMessage == 'function' &&
	    !global_1.importScripts &&
	    !fails(post) &&
	    location.protocol !== 'file:'
	  ) {
	    defer = post;
	    global_1.addEventListener('message', listener, false);
	  // IE8-
	  } else if (ONREADYSTATECHANGE in documentCreateElement('script')) {
	    defer = function (id) {
	      html.appendChild(documentCreateElement('script'))[ONREADYSTATECHANGE] = function () {
	        html.removeChild(this);
	        run(id);
	      };
	    };
	  // Rest old browsers
	  } else {
	    defer = function (id) {
	      setTimeout(runner(id), 0);
	    };
	  }
	}

	var task = {
	  set: set$1,
	  clear: clear
	};

	var FORCED$4 = !global_1.setImmediate || !global_1.clearImmediate;

	// http://w3c.github.io/setImmediate/
	_export({ global: true, bind: true, enumerable: true, forced: FORCED$4 }, {
	  // `setImmediate` method
	  // http://w3c.github.io/setImmediate/#si-setImmediate
	  setImmediate: task.set,
	  // `clearImmediate` method
	  // http://w3c.github.io/setImmediate/#si-clearImmediate
	  clearImmediate: task.clear
	});

	var nunjucksSlim = createCommonjsModule(function (module, exports) {
	  /*! Browser bundle of nunjucks 3.2.0 (slim, only works with precompiled templates) */
	  (function webpackUniversalModuleDefinition(root, factory) {
	    module.exports = factory();
	  })(typeof self !== 'undefined' ? self : commonjsGlobal, function () {
	    return (
	      /******/
	      function (modules) {
	        // webpackBootstrap

	        /******/
	        // The module cache

	        /******/
	        var installedModules = {};
	        /******/

	        /******/
	        // The require function

	        /******/

	        function __webpack_require__(moduleId) {
	          /******/

	          /******/
	          // Check if module is in cache

	          /******/
	          if (installedModules[moduleId]) {
	            /******/
	            return installedModules[moduleId].exports;
	            /******/
	          }
	          /******/
	          // Create a new module (and put it into the cache)

	          /******/


	          var module = installedModules[moduleId] = {
	            /******/
	            i: moduleId,

	            /******/
	            l: false,

	            /******/
	            exports: {}
	            /******/

	          };
	          /******/

	          /******/
	          // Execute the module function

	          /******/

	          modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
	          /******/

	          /******/
	          // Flag the module as loaded

	          /******/

	          module.l = true;
	          /******/

	          /******/
	          // Return the exports of the module

	          /******/

	          return module.exports;
	          /******/
	        }
	        /******/

	        /******/

	        /******/
	        // expose the modules object (__webpack_modules__)

	        /******/


	        __webpack_require__.m = modules;
	        /******/

	        /******/
	        // expose the module cache

	        /******/

	        __webpack_require__.c = installedModules;
	        /******/

	        /******/
	        // define getter function for harmony exports

	        /******/

	        __webpack_require__.d = function (exports, name, getter) {
	          /******/
	          if (!__webpack_require__.o(exports, name)) {
	            /******/
	            Object.defineProperty(exports, name, {
	              /******/
	              configurable: false,

	              /******/
	              enumerable: true,

	              /******/
	              get: getter
	              /******/

	            });
	            /******/
	          }
	          /******/

	        };
	        /******/

	        /******/
	        // getDefaultExport function for compatibility with non-harmony modules

	        /******/


	        __webpack_require__.n = function (module) {
	          /******/
	          var getter = module && module.__esModule ?
	          /******/
	          function getDefault() {
	            return module['default'];
	          } :
	          /******/
	          function getModuleExports() {
	            return module;
	          };
	          /******/

	          __webpack_require__.d(getter, 'a', getter);
	          /******/


	          return getter;
	          /******/
	        };
	        /******/

	        /******/
	        // Object.prototype.hasOwnProperty.call

	        /******/


	        __webpack_require__.o = function (object, property) {
	          return Object.prototype.hasOwnProperty.call(object, property);
	        };
	        /******/

	        /******/
	        // __webpack_public_path__

	        /******/


	        __webpack_require__.p = "";
	        /******/

	        /******/
	        // Load entry module and return exports

	        /******/

	        return __webpack_require__(__webpack_require__.s = 6);
	        /******/
	      }(
	      /************************************************************************/

	      /******/
	      [
	      /* 0 */

	      /***/
	      function (module, exports) {
	        /***/
	      },
	      /* 1 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var ArrayProto = Array.prototype;
	        var ObjProto = Object.prototype;
	        var escapeMap = {
	          '&': '&amp;',
	          '"': '&quot;',
	          '\'': '&#39;',
	          '<': '&lt;',
	          '>': '&gt;'
	        };
	        var escapeRegex = /[&"'<>]/g;
	        var exports = module.exports = {};

	        function hasOwnProp(obj, k) {
	          return ObjProto.hasOwnProperty.call(obj, k);
	        }

	        exports.hasOwnProp = hasOwnProp;

	        function lookupEscape(ch) {
	          return escapeMap[ch];
	        }

	        function _prettifyError(path, withInternals, err) {
	          if (!err.Update) {
	            // not one of ours, cast it
	            err = new exports.TemplateError(err);
	          }

	          err.Update(path); // Unless they marked the dev flag, show them a trace from here

	          if (!withInternals) {
	            var old = err;
	            err = new Error(old.message);
	            err.name = old.name;
	          }

	          return err;
	        }

	        exports._prettifyError = _prettifyError;

	        function TemplateError(message, lineno, colno) {
	          var err;
	          var cause;

	          if (message instanceof Error) {
	            cause = message;
	            message = cause.name + ": " + cause.message;
	          }

	          if (Object.setPrototypeOf) {
	            err = new Error(message);
	            Object.setPrototypeOf(err, TemplateError.prototype);
	          } else {
	            err = this;
	            Object.defineProperty(err, 'message', {
	              enumerable: false,
	              writable: true,
	              value: message
	            });
	          }

	          Object.defineProperty(err, 'name', {
	            value: 'Template render error'
	          });

	          if (Error.captureStackTrace) {
	            Error.captureStackTrace(err, this.constructor);
	          }

	          var getStack;

	          if (cause) {
	            var stackDescriptor = Object.getOwnPropertyDescriptor(cause, 'stack');

	            getStack = stackDescriptor && (stackDescriptor.get || function () {
	              return stackDescriptor.value;
	            });

	            if (!getStack) {
	              getStack = function getStack() {
	                return cause.stack;
	              };
	            }
	          } else {
	            var stack = new Error(message).stack;

	            getStack = function getStack() {
	              return stack;
	            };
	          }

	          Object.defineProperty(err, 'stack', {
	            get: function get() {
	              return getStack.call(err);
	            }
	          });
	          Object.defineProperty(err, 'cause', {
	            value: cause
	          });
	          err.lineno = lineno;
	          err.colno = colno;
	          err.firstUpdate = true;

	          err.Update = function Update(path) {
	            var msg = '(' + (path || 'unknown path') + ')'; // only show lineno + colno next to path of template
	            // where error occurred

	            if (this.firstUpdate) {
	              if (this.lineno && this.colno) {
	                msg += " [Line " + this.lineno + ", Column " + this.colno + "]";
	              } else if (this.lineno) {
	                msg += " [Line " + this.lineno + "]";
	              }
	            }

	            msg += '\n ';

	            if (this.firstUpdate) {
	              msg += ' ';
	            }

	            this.message = msg + (this.message || '');
	            this.firstUpdate = false;
	            return this;
	          };

	          return err;
	        }

	        if (Object.setPrototypeOf) {
	          Object.setPrototypeOf(TemplateError.prototype, Error.prototype);
	        } else {
	          TemplateError.prototype = Object.create(Error.prototype, {
	            constructor: {
	              value: TemplateError
	            }
	          });
	        }

	        exports.TemplateError = TemplateError;

	        function escape(val) {
	          return val.replace(escapeRegex, lookupEscape);
	        }

	        exports.escape = escape;

	        function isFunction(obj) {
	          return ObjProto.toString.call(obj) === '[object Function]';
	        }

	        exports.isFunction = isFunction;

	        function isArray(obj) {
	          return ObjProto.toString.call(obj) === '[object Array]';
	        }

	        exports.isArray = isArray;

	        function isString(obj) {
	          return ObjProto.toString.call(obj) === '[object String]';
	        }

	        exports.isString = isString;

	        function isObject(obj) {
	          return ObjProto.toString.call(obj) === '[object Object]';
	        }

	        exports.isObject = isObject;

	        function groupBy(obj, val) {
	          var result = {};
	          var iterator = isFunction(val) ? val : function (o) {
	            return o[val];
	          };

	          for (var i = 0; i < obj.length; i++) {
	            var value = obj[i];
	            var key = iterator(value, i);
	            (result[key] || (result[key] = [])).push(value);
	          }

	          return result;
	        }

	        exports.groupBy = groupBy;

	        function toArray(obj) {
	          return Array.prototype.slice.call(obj);
	        }

	        exports.toArray = toArray;

	        function without(array) {
	          var result = [];

	          if (!array) {
	            return result;
	          }

	          var length = array.length;
	          var contains = toArray(arguments).slice(1);
	          var index = -1;

	          while (++index < length) {
	            if (indexOf(contains, array[index]) === -1) {
	              result.push(array[index]);
	            }
	          }

	          return result;
	        }

	        exports.without = without;

	        function repeat(char_, n) {
	          var str = '';

	          for (var i = 0; i < n; i++) {
	            str += char_;
	          }

	          return str;
	        }

	        exports.repeat = repeat;

	        function each(obj, func, context) {
	          if (obj == null) {
	            return;
	          }

	          if (ArrayProto.forEach && obj.forEach === ArrayProto.forEach) {
	            obj.forEach(func, context);
	          } else if (obj.length === +obj.length) {
	            for (var i = 0, l = obj.length; i < l; i++) {
	              func.call(context, obj[i], i, obj);
	            }
	          }
	        }

	        exports.each = each;

	        function map(obj, func) {
	          var results = [];

	          if (obj == null) {
	            return results;
	          }

	          if (ArrayProto.map && obj.map === ArrayProto.map) {
	            return obj.map(func);
	          }

	          for (var i = 0; i < obj.length; i++) {
	            results[results.length] = func(obj[i], i);
	          }

	          if (obj.length === +obj.length) {
	            results.length = obj.length;
	          }

	          return results;
	        }

	        exports.map = map;

	        function asyncIter(arr, iter, cb) {
	          var i = -1;

	          function next() {
	            i++;

	            if (i < arr.length) {
	              iter(arr[i], i, next, cb);
	            } else {
	              cb();
	            }
	          }

	          next();
	        }

	        exports.asyncIter = asyncIter;

	        function asyncFor(obj, iter, cb) {
	          var keys = keys_(obj || {});
	          var len = keys.length;
	          var i = -1;

	          function next() {
	            i++;
	            var k = keys[i];

	            if (i < len) {
	              iter(k, obj[k], i, len, next);
	            } else {
	              cb();
	            }
	          }

	          next();
	        }

	        exports.asyncFor = asyncFor;

	        function indexOf(arr, searchElement, fromIndex) {
	          return Array.prototype.indexOf.call(arr || [], searchElement, fromIndex);
	        }

	        exports.indexOf = indexOf;

	        function keys_(obj) {
	          /* eslint-disable no-restricted-syntax */
	          var arr = [];

	          for (var k in obj) {
	            if (hasOwnProp(obj, k)) {
	              arr.push(k);
	            }
	          }

	          return arr;
	        }

	        exports.keys = keys_;

	        function _entries(obj) {
	          return keys_(obj).map(function (k) {
	            return [k, obj[k]];
	          });
	        }

	        exports._entries = _entries;

	        function _values(obj) {
	          return keys_(obj).map(function (k) {
	            return obj[k];
	          });
	        }

	        exports._values = _values;

	        function extend(obj1, obj2) {
	          obj1 = obj1 || {};
	          keys_(obj2).forEach(function (k) {
	            obj1[k] = obj2[k];
	          });
	          return obj1;
	        }

	        exports._assign = exports.extend = extend;

	        function inOperator(key, val) {
	          if (isArray(val) || isString(val)) {
	            return val.indexOf(key) !== -1;
	          } else if (isObject(val)) {
	            return key in val;
	          }

	          throw new Error('Cannot use "in" operator to search for "' + key + '" in unexpected types.');
	        }

	        exports.inOperator = inOperator;
	        /***/
	      },
	      /* 2 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var lib = __webpack_require__(1);

	        var arrayFrom = Array.from;
	        var supportsIterators = typeof Symbol === 'function' && Symbol.iterator && typeof arrayFrom === 'function'; // Frames keep track of scoping both at compile-time and run-time so
	        // we know how to access variables. Block tags can introduce special
	        // variables, for example.

	        var Frame = /*#__PURE__*/function () {
	          function Frame(parent, isolateWrites) {
	            this.variables = {};
	            this.parent = parent;
	            this.topLevel = false; // if this is true, writes (set) should never propagate upwards past
	            // this frame to its parent (though reads may).

	            this.isolateWrites = isolateWrites;
	          }

	          var _proto = Frame.prototype;

	          _proto.set = function set(name, val, resolveUp) {
	            // Allow variables with dots by automatically creating the
	            // nested structure
	            var parts = name.split('.');
	            var obj = this.variables;
	            var frame = this;

	            if (resolveUp) {
	              if (frame = this.resolve(parts[0], true)) {
	                frame.set(name, val);
	                return;
	              }
	            }

	            for (var i = 0; i < parts.length - 1; i++) {
	              var id = parts[i];

	              if (!obj[id]) {
	                obj[id] = {};
	              }

	              obj = obj[id];
	            }

	            obj[parts[parts.length - 1]] = val;
	          };

	          _proto.get = function get(name) {
	            var val = this.variables[name];

	            if (val !== undefined) {
	              return val;
	            }

	            return null;
	          };

	          _proto.lookup = function lookup(name) {
	            var p = this.parent;
	            var val = this.variables[name];

	            if (val !== undefined) {
	              return val;
	            }

	            return p && p.lookup(name);
	          };

	          _proto.resolve = function resolve(name, forWrite) {
	            var p = forWrite && this.isolateWrites ? undefined : this.parent;
	            var val = this.variables[name];

	            if (val !== undefined) {
	              return this;
	            }

	            return p && p.resolve(name);
	          };

	          _proto.push = function push(isolateWrites) {
	            return new Frame(this, isolateWrites);
	          };

	          _proto.pop = function pop() {
	            return this.parent;
	          };

	          return Frame;
	        }();

	        function makeMacro(argNames, kwargNames, func) {
	          var _this = this;

	          return function () {
	            for (var _len = arguments.length, macroArgs = new Array(_len), _key = 0; _key < _len; _key++) {
	              macroArgs[_key] = arguments[_key];
	            }

	            var argCount = numArgs(macroArgs);
	            var args;
	            var kwargs = getKeywordArgs(macroArgs);

	            if (argCount > argNames.length) {
	              args = macroArgs.slice(0, argNames.length); // Positional arguments that should be passed in as
	              // keyword arguments (essentially default values)

	              macroArgs.slice(args.length, argCount).forEach(function (val, i) {
	                if (i < kwargNames.length) {
	                  kwargs[kwargNames[i]] = val;
	                }
	              });
	              args.push(kwargs);
	            } else if (argCount < argNames.length) {
	              args = macroArgs.slice(0, argCount);

	              for (var i = argCount; i < argNames.length; i++) {
	                var arg = argNames[i]; // Keyword arguments that should be passed as
	                // positional arguments, i.e. the caller explicitly
	                // used the name of a positional arg

	                args.push(kwargs[arg]);
	                delete kwargs[arg];
	              }

	              args.push(kwargs);
	            } else {
	              args = macroArgs;
	            }

	            return func.apply(_this, args);
	          };
	        }

	        function makeKeywordArgs(obj) {
	          obj.__keywords = true;
	          return obj;
	        }

	        function isKeywordArgs(obj) {
	          return obj && Object.prototype.hasOwnProperty.call(obj, '__keywords');
	        }

	        function getKeywordArgs(args) {
	          var len = args.length;

	          if (len) {
	            var lastArg = args[len - 1];

	            if (isKeywordArgs(lastArg)) {
	              return lastArg;
	            }
	          }

	          return {};
	        }

	        function numArgs(args) {
	          var len = args.length;

	          if (len === 0) {
	            return 0;
	          }

	          var lastArg = args[len - 1];

	          if (isKeywordArgs(lastArg)) {
	            return len - 1;
	          } else {
	            return len;
	          }
	        } // A SafeString object indicates that the string should not be
	        // autoescaped. This happens magically because autoescaping only
	        // occurs on primitive string objects.


	        function SafeString(val) {
	          if (typeof val !== 'string') {
	            return val;
	          }

	          this.val = val;
	          this.length = val.length;
	        }

	        SafeString.prototype = Object.create(String.prototype, {
	          length: {
	            writable: true,
	            configurable: true,
	            value: 0
	          }
	        });

	        SafeString.prototype.valueOf = function valueOf() {
	          return this.val;
	        };

	        SafeString.prototype.toString = function toString() {
	          return this.val;
	        };

	        function copySafeness(dest, target) {
	          if (dest instanceof SafeString) {
	            return new SafeString(target);
	          }

	          return target.toString();
	        }

	        function markSafe(val) {
	          var type = _typeof_1(val);

	          if (type === 'string') {
	            return new SafeString(val);
	          } else if (type !== 'function') {
	            return val;
	          } else {
	            return function wrapSafe(args) {
	              var ret = val.apply(this, arguments);

	              if (typeof ret === 'string') {
	                return new SafeString(ret);
	              }

	              return ret;
	            };
	          }
	        }

	        function suppressValue(val, autoescape) {
	          val = val !== undefined && val !== null ? val : '';

	          if (autoescape && !(val instanceof SafeString)) {
	            val = lib.escape(val.toString());
	          }

	          return val;
	        }

	        function ensureDefined(val, lineno, colno) {
	          if (val === null || val === undefined) {
	            throw new lib.TemplateError('attempted to output null or undefined value', lineno + 1, colno + 1);
	          }

	          return val;
	        }

	        function memberLookup(obj, val) {
	          if (obj === undefined || obj === null) {
	            return undefined;
	          }

	          if (typeof obj[val] === 'function') {
	            return function () {
	              for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
	                args[_key2] = arguments[_key2];
	              }

	              return obj[val].apply(obj, args);
	            };
	          }

	          return obj[val];
	        }

	        function callWrap(obj, name, context, args) {
	          if (!obj) {
	            throw new Error('Unable to call `' + name + '`, which is undefined or falsey');
	          } else if (typeof obj !== 'function') {
	            throw new Error('Unable to call `' + name + '`, which is not a function');
	          }

	          return obj.apply(context, args);
	        }

	        function contextOrFrameLookup(context, frame, name) {
	          var val = frame.lookup(name);
	          return val !== undefined ? val : context.lookup(name);
	        }

	        function handleError(error, lineno, colno) {
	          if (error.lineno) {
	            return error;
	          } else {
	            return new lib.TemplateError(error, lineno, colno);
	          }
	        }

	        function asyncEach(arr, dimen, iter, cb) {
	          if (lib.isArray(arr)) {
	            var len = arr.length;
	            lib.asyncIter(arr, function iterCallback(item, i, next) {
	              switch (dimen) {
	                case 1:
	                  iter(item, i, len, next);
	                  break;

	                case 2:
	                  iter(item[0], item[1], i, len, next);
	                  break;

	                case 3:
	                  iter(item[0], item[1], item[2], i, len, next);
	                  break;

	                default:
	                  item.push(i, len, next);
	                  iter.apply(this, item);
	              }
	            }, cb);
	          } else {
	            lib.asyncFor(arr, function iterCallback(key, val, i, len, next) {
	              iter(key, val, i, len, next);
	            }, cb);
	          }
	        }

	        function asyncAll(arr, dimen, func, cb) {
	          var finished = 0;
	          var len;
	          var outputArr;

	          function done(i, output) {
	            finished++;
	            outputArr[i] = output;

	            if (finished === len) {
	              cb(null, outputArr.join(''));
	            }
	          }

	          if (lib.isArray(arr)) {
	            len = arr.length;
	            outputArr = new Array(len);

	            if (len === 0) {
	              cb(null, '');
	            } else {
	              for (var i = 0; i < arr.length; i++) {
	                var item = arr[i];

	                switch (dimen) {
	                  case 1:
	                    func(item, i, len, done);
	                    break;

	                  case 2:
	                    func(item[0], item[1], i, len, done);
	                    break;

	                  case 3:
	                    func(item[0], item[1], item[2], i, len, done);
	                    break;

	                  default:
	                    item.push(i, len, done);
	                    func.apply(this, item);
	                }
	              }
	            }
	          } else {
	            var keys = lib.keys(arr || {});
	            len = keys.length;
	            outputArr = new Array(len);

	            if (len === 0) {
	              cb(null, '');
	            } else {
	              for (var _i = 0; _i < keys.length; _i++) {
	                var k = keys[_i];
	                func(k, arr[k], _i, len, done);
	              }
	            }
	          }
	        }

	        function fromIterator(arr) {
	          if (_typeof_1(arr) !== 'object' || arr === null || lib.isArray(arr)) {
	            return arr;
	          } else if (supportsIterators && Symbol.iterator in arr) {
	            return arrayFrom(arr);
	          } else {
	            return arr;
	          }
	        }

	        module.exports = {
	          Frame: Frame,
	          makeMacro: makeMacro,
	          makeKeywordArgs: makeKeywordArgs,
	          numArgs: numArgs,
	          suppressValue: suppressValue,
	          ensureDefined: ensureDefined,
	          memberLookup: memberLookup,
	          contextOrFrameLookup: contextOrFrameLookup,
	          callWrap: callWrap,
	          handleError: handleError,
	          isArray: lib.isArray,
	          keys: lib.keys,
	          SafeString: SafeString,
	          copySafeness: copySafeness,
	          markSafe: markSafe,
	          asyncEach: asyncEach,
	          asyncAll: asyncAll,
	          inOperator: lib.inOperator,
	          fromIterator: fromIterator
	        };
	        /***/
	      },
	      /* 3 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        function _inheritsLoose(subClass, superClass) {
	          subClass.prototype = Object.create(superClass.prototype);
	          subClass.prototype.constructor = subClass;
	          subClass.__proto__ = superClass;
	        }

	        var Loader = __webpack_require__(4);

	        var PrecompiledLoader = /*#__PURE__*/function (_Loader) {
	          _inheritsLoose(PrecompiledLoader, _Loader);

	          function PrecompiledLoader(compiledTemplates) {
	            var _this;

	            _this = _Loader.call(this) || this;
	            _this.precompiled = compiledTemplates || {};
	            return _this;
	          }

	          var _proto = PrecompiledLoader.prototype;

	          _proto.getSource = function getSource(name) {
	            if (this.precompiled[name]) {
	              return {
	                src: {
	                  type: 'code',
	                  obj: this.precompiled[name]
	                },
	                path: name
	              };
	            }

	            return null;
	          };

	          return PrecompiledLoader;
	        }(Loader);

	        module.exports = {
	          PrecompiledLoader: PrecompiledLoader
	        };
	        /***/
	      },
	      /* 4 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        function _inheritsLoose(subClass, superClass) {
	          subClass.prototype = Object.create(superClass.prototype);
	          subClass.prototype.constructor = subClass;
	          subClass.__proto__ = superClass;
	        }

	        var path = __webpack_require__(0);

	        var _require = __webpack_require__(5),
	            EmitterObj = _require.EmitterObj;

	        module.exports = /*#__PURE__*/function (_EmitterObj) {
	          _inheritsLoose(Loader, _EmitterObj);

	          function Loader() {
	            return _EmitterObj.apply(this, arguments) || this;
	          }

	          var _proto = Loader.prototype;

	          _proto.resolve = function resolve(from, to) {
	            return path.resolve(path.dirname(from), to);
	          };

	          _proto.isRelative = function isRelative(filename) {
	            return filename.indexOf('./') === 0 || filename.indexOf('../') === 0;
	          };

	          return Loader;
	        }(EmitterObj);
	        /***/

	      },
	      /* 5 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        function _defineProperties(target, props) {
	          for (var i = 0; i < props.length; i++) {
	            var descriptor = props[i];
	            descriptor.enumerable = descriptor.enumerable || false;
	            descriptor.configurable = true;
	            if ("value" in descriptor) descriptor.writable = true;
	            Object.defineProperty(target, descriptor.key, descriptor);
	          }
	        }

	        function _createClass(Constructor, protoProps, staticProps) {
	          if (protoProps) _defineProperties(Constructor.prototype, protoProps);
	          if (staticProps) _defineProperties(Constructor, staticProps);
	          return Constructor;
	        }

	        function _inheritsLoose(subClass, superClass) {
	          subClass.prototype = Object.create(superClass.prototype);
	          subClass.prototype.constructor = subClass;
	          subClass.__proto__ = superClass;
	        }

	        var EventEmitter = __webpack_require__(13);

	        var lib = __webpack_require__(1);

	        function parentWrap(parent, prop) {
	          if (typeof parent !== 'function' || typeof prop !== 'function') {
	            return prop;
	          }

	          return function wrap() {
	            // Save the current parent method
	            var tmp = this.parent; // Set parent to the previous method, call, and restore

	            this.parent = parent;
	            var res = prop.apply(this, arguments);
	            this.parent = tmp;
	            return res;
	          };
	        }

	        function extendClass(cls, name, props) {
	          props = props || {};
	          lib.keys(props).forEach(function (k) {
	            props[k] = parentWrap(cls.prototype[k], props[k]);
	          });

	          var subclass = /*#__PURE__*/function (_cls) {
	            _inheritsLoose(subclass, _cls);

	            function subclass() {
	              return _cls.apply(this, arguments) || this;
	            }

	            _createClass(subclass, [{
	              key: "typename",
	              get: function get() {
	                return name;
	              }
	            }]);

	            return subclass;
	          }(cls);

	          lib._assign(subclass.prototype, props);

	          return subclass;
	        }

	        var Obj = /*#__PURE__*/function () {
	          function Obj() {
	            // Unfortunately necessary for backwards compatibility
	            this.init.apply(this, arguments);
	          }

	          var _proto = Obj.prototype;

	          _proto.init = function init() {};

	          Obj.extend = function extend(name, props) {
	            if (_typeof_1(name) === 'object') {
	              props = name;
	              name = 'anonymous';
	            }

	            return extendClass(this, name, props);
	          };

	          _createClass(Obj, [{
	            key: "typename",
	            get: function get() {
	              return this.constructor.name;
	            }
	          }]);

	          return Obj;
	        }();

	        var EmitterObj = /*#__PURE__*/function (_EventEmitter) {
	          _inheritsLoose(EmitterObj, _EventEmitter);

	          function EmitterObj() {
	            var _this2;

	            var _this;

	            _this = _EventEmitter.call(this) || this; // Unfortunately necessary for backwards compatibility

	            (_this2 = _this).init.apply(_this2, arguments);

	            return _this;
	          }

	          var _proto2 = EmitterObj.prototype;

	          _proto2.init = function init() {};

	          EmitterObj.extend = function extend(name, props) {
	            if (_typeof_1(name) === 'object') {
	              props = name;
	              name = 'anonymous';
	            }

	            return extendClass(this, name, props);
	          };

	          _createClass(EmitterObj, [{
	            key: "typename",
	            get: function get() {
	              return this.constructor.name;
	            }
	          }]);

	          return EmitterObj;
	        }(EventEmitter);

	        module.exports = {
	          Obj: Obj,
	          EmitterObj: EmitterObj
	        };
	        /***/
	      },
	      /* 6 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var lib = __webpack_require__(1);

	        var _require = __webpack_require__(7),
	            Environment = _require.Environment,
	            Template = _require.Template;

	        var Loader = __webpack_require__(4);

	        var loaders = __webpack_require__(3);

	        var precompile = __webpack_require__(0);

	        var compiler = __webpack_require__(0);

	        var parser = __webpack_require__(0);

	        var lexer = __webpack_require__(0);

	        var runtime = __webpack_require__(2);

	        var nodes = __webpack_require__(0);

	        var installJinjaCompat = __webpack_require__(17); // A single instance of an environment, since this is so commonly used


	        var e;

	        function configure(templatesPath, opts) {
	          opts = opts || {};

	          if (lib.isObject(templatesPath)) {
	            opts = templatesPath;
	            templatesPath = null;
	          }

	          var TemplateLoader;

	          if (loaders.FileSystemLoader) {
	            TemplateLoader = new loaders.FileSystemLoader(templatesPath, {
	              watch: opts.watch,
	              noCache: opts.noCache
	            });
	          } else if (loaders.WebLoader) {
	            TemplateLoader = new loaders.WebLoader(templatesPath, {
	              useCache: opts.web && opts.web.useCache,
	              async: opts.web && opts.web.async
	            });
	          }

	          e = new Environment(TemplateLoader, opts);

	          if (opts && opts.express) {
	            e.express(opts.express);
	          }

	          return e;
	        }

	        module.exports = {
	          Environment: Environment,
	          Template: Template,
	          Loader: Loader,
	          FileSystemLoader: loaders.FileSystemLoader,
	          NodeResolveLoader: loaders.NodeResolveLoader,
	          PrecompiledLoader: loaders.PrecompiledLoader,
	          WebLoader: loaders.WebLoader,
	          compiler: compiler,
	          parser: parser,
	          lexer: lexer,
	          runtime: runtime,
	          lib: lib,
	          nodes: nodes,
	          installJinjaCompat: installJinjaCompat,
	          configure: configure,
	          reset: function reset() {
	            e = undefined;
	          },
	          compile: function compile(src, env, path, eagerCompile) {
	            if (!e) {
	              configure();
	            }

	            return new Template(src, env, path, eagerCompile);
	          },
	          render: function render(name, ctx, cb) {
	            if (!e) {
	              configure();
	            }

	            return e.render(name, ctx, cb);
	          },
	          renderString: function renderString(src, ctx, cb) {
	            if (!e) {
	              configure();
	            }

	            return e.renderString(src, ctx, cb);
	          },
	          precompile: precompile ? precompile.precompile : undefined,
	          precompileString: precompile ? precompile.precompileString : undefined
	        };
	        /***/
	      },
	      /* 7 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        function _inheritsLoose(subClass, superClass) {
	          subClass.prototype = Object.create(superClass.prototype);
	          subClass.prototype.constructor = subClass;
	          subClass.__proto__ = superClass;
	        }

	        var asap = __webpack_require__(8);

	        var _waterfall = __webpack_require__(11);

	        var lib = __webpack_require__(1);

	        var compiler = __webpack_require__(0);

	        var filters = __webpack_require__(12);

	        var _require = __webpack_require__(3),
	            FileSystemLoader = _require.FileSystemLoader,
	            WebLoader = _require.WebLoader,
	            PrecompiledLoader = _require.PrecompiledLoader;

	        var tests = __webpack_require__(14);

	        var globals = __webpack_require__(15);

	        var _require2 = __webpack_require__(5),
	            Obj = _require2.Obj,
	            EmitterObj = _require2.EmitterObj;

	        var globalRuntime = __webpack_require__(2);

	        var handleError = globalRuntime.handleError,
	            Frame = globalRuntime.Frame;

	        var expressApp = __webpack_require__(16); // If the user is using the async API, *always* call it
	        // asynchronously even if the template was synchronous.


	        function callbackAsap(cb, err, res) {
	          asap(function () {
	            cb(err, res);
	          });
	        }
	        /**
	         * A no-op template, for use with {% include ignore missing %}
	         */


	        var noopTmplSrc = {
	          type: 'code',
	          obj: {
	            root: function root(env, context, frame, runtime, cb) {
	              try {
	                cb(null, '');
	              } catch (e) {
	                cb(handleError(e, null, null));
	              }
	            }
	          }
	        };

	        var Environment = /*#__PURE__*/function (_EmitterObj) {
	          _inheritsLoose(Environment, _EmitterObj);

	          function Environment() {
	            return _EmitterObj.apply(this, arguments) || this;
	          }

	          var _proto = Environment.prototype;

	          _proto.init = function init(loaders, opts) {
	            var _this = this; // The dev flag determines the trace that'll be shown on errors.
	            // If set to true, returns the full trace from the error point,
	            // otherwise will return trace starting from Template.render
	            // (the full trace from within nunjucks may confuse developers using
	            //  the library)
	            // defaults to false


	            opts = this.opts = opts || {};
	            this.opts.dev = !!opts.dev; // The autoescape flag sets global autoescaping. If true,
	            // every string variable will be escaped by default.
	            // If false, strings can be manually escaped using the `escape` filter.
	            // defaults to true

	            this.opts.autoescape = opts.autoescape != null ? opts.autoescape : true; // If true, this will make the system throw errors if trying
	            // to output a null or undefined value

	            this.opts.throwOnUndefined = !!opts.throwOnUndefined;
	            this.opts.trimBlocks = !!opts.trimBlocks;
	            this.opts.lstripBlocks = !!opts.lstripBlocks;
	            this.loaders = [];

	            if (!loaders) {
	              // The filesystem loader is only available server-side
	              if (FileSystemLoader) {
	                this.loaders = [new FileSystemLoader('views')];
	              } else if (WebLoader) {
	                this.loaders = [new WebLoader('/views')];
	              }
	            } else {
	              this.loaders = lib.isArray(loaders) ? loaders : [loaders];
	            } // It's easy to use precompiled templates: just include them
	            // before you configure nunjucks and this will automatically
	            // pick it up and use it


	            if (typeof window !== 'undefined' && window.nunjucksPrecompiled) {
	              this.loaders.unshift(new PrecompiledLoader(window.nunjucksPrecompiled));
	            }

	            this._initLoaders();

	            this.globals = globals();
	            this.filters = {};
	            this.tests = {};
	            this.asyncFilters = [];
	            this.extensions = {};
	            this.extensionsList = [];

	            lib._entries(filters).forEach(function (_ref) {
	              var name = _ref[0],
	                  filter = _ref[1];
	              return _this.addFilter(name, filter);
	            });

	            lib._entries(tests).forEach(function (_ref2) {
	              var name = _ref2[0],
	                  test = _ref2[1];
	              return _this.addTest(name, test);
	            });
	          };

	          _proto._initLoaders = function _initLoaders() {
	            var _this2 = this;

	            this.loaders.forEach(function (loader) {
	              // Caching and cache busting
	              loader.cache = {};

	              if (typeof loader.on === 'function') {
	                loader.on('update', function (name, fullname) {
	                  loader.cache[name] = null;

	                  _this2.emit('update', name, fullname, loader);
	                });
	                loader.on('load', function (name, source) {
	                  _this2.emit('load', name, source, loader);
	                });
	              }
	            });
	          };

	          _proto.invalidateCache = function invalidateCache() {
	            this.loaders.forEach(function (loader) {
	              loader.cache = {};
	            });
	          };

	          _proto.addExtension = function addExtension(name, extension) {
	            extension.__name = name;
	            this.extensions[name] = extension;
	            this.extensionsList.push(extension);
	            return this;
	          };

	          _proto.removeExtension = function removeExtension(name) {
	            var extension = this.getExtension(name);

	            if (!extension) {
	              return;
	            }

	            this.extensionsList = lib.without(this.extensionsList, extension);
	            delete this.extensions[name];
	          };

	          _proto.getExtension = function getExtension(name) {
	            return this.extensions[name];
	          };

	          _proto.hasExtension = function hasExtension(name) {
	            return !!this.extensions[name];
	          };

	          _proto.addGlobal = function addGlobal(name, value) {
	            this.globals[name] = value;
	            return this;
	          };

	          _proto.getGlobal = function getGlobal(name) {
	            if (typeof this.globals[name] === 'undefined') {
	              throw new Error('global not found: ' + name);
	            }

	            return this.globals[name];
	          };

	          _proto.addFilter = function addFilter(name, func, async) {
	            var wrapped = func;

	            if (async) {
	              this.asyncFilters.push(name);
	            }

	            this.filters[name] = wrapped;
	            return this;
	          };

	          _proto.getFilter = function getFilter(name) {
	            if (!this.filters[name]) {
	              throw new Error('filter not found: ' + name);
	            }

	            return this.filters[name];
	          };

	          _proto.addTest = function addTest(name, func) {
	            this.tests[name] = func;
	            return this;
	          };

	          _proto.getTest = function getTest(name) {
	            if (!this.tests[name]) {
	              throw new Error('test not found: ' + name);
	            }

	            return this.tests[name];
	          };

	          _proto.resolveTemplate = function resolveTemplate(loader, parentName, filename) {
	            var isRelative = loader.isRelative && parentName ? loader.isRelative(filename) : false;
	            return isRelative && loader.resolve ? loader.resolve(parentName, filename) : filename;
	          };

	          _proto.getTemplate = function getTemplate(name, eagerCompile, parentName, ignoreMissing, cb) {
	            var _this3 = this;

	            var that = this;
	            var tmpl = null;

	            if (name && name.raw) {
	              // this fixes autoescape for templates referenced in symbols
	              name = name.raw;
	            }

	            if (lib.isFunction(parentName)) {
	              cb = parentName;
	              parentName = null;
	              eagerCompile = eagerCompile || false;
	            }

	            if (lib.isFunction(eagerCompile)) {
	              cb = eagerCompile;
	              eagerCompile = false;
	            }

	            if (name instanceof Template) {
	              tmpl = name;
	            } else if (typeof name !== 'string') {
	              throw new Error('template names must be a string: ' + name);
	            } else {
	              for (var i = 0; i < this.loaders.length; i++) {
	                var loader = this.loaders[i];
	                tmpl = loader.cache[this.resolveTemplate(loader, parentName, name)];

	                if (tmpl) {
	                  break;
	                }
	              }
	            }

	            if (tmpl) {
	              if (eagerCompile) {
	                tmpl.compile();
	              }

	              if (cb) {
	                cb(null, tmpl);
	                return undefined;
	              } else {
	                return tmpl;
	              }
	            }

	            var syncResult;

	            var createTemplate = function createTemplate(err, info) {
	              if (!info && !err && !ignoreMissing) {
	                err = new Error('template not found: ' + name);
	              }

	              if (err) {
	                if (cb) {
	                  cb(err);
	                  return;
	                } else {
	                  throw err;
	                }
	              }

	              var newTmpl;

	              if (!info) {
	                newTmpl = new Template(noopTmplSrc, _this3, '', eagerCompile);
	              } else {
	                newTmpl = new Template(info.src, _this3, info.path, eagerCompile);

	                if (!info.noCache) {
	                  info.loader.cache[name] = newTmpl;
	                }
	              }

	              if (cb) {
	                cb(null, newTmpl);
	              } else {
	                syncResult = newTmpl;
	              }
	            };

	            lib.asyncIter(this.loaders, function (loader, i, next, done) {
	              function handle(err, src) {
	                if (err) {
	                  done(err);
	                } else if (src) {
	                  src.loader = loader;
	                  done(null, src);
	                } else {
	                  next();
	                }
	              } // Resolve name relative to parentName


	              name = that.resolveTemplate(loader, parentName, name);

	              if (loader.async) {
	                loader.getSource(name, handle);
	              } else {
	                handle(null, loader.getSource(name));
	              }
	            }, createTemplate);
	            return syncResult;
	          };

	          _proto.express = function express(app) {
	            return expressApp(this, app);
	          };

	          _proto.render = function render(name, ctx, cb) {
	            if (lib.isFunction(ctx)) {
	              cb = ctx;
	              ctx = null;
	            } // We support a synchronous API to make it easier to migrate
	            // existing code to async. This works because if you don't do
	            // anything async work, the whole thing is actually run
	            // synchronously.


	            var syncResult = null;
	            this.getTemplate(name, function (err, tmpl) {
	              if (err && cb) {
	                callbackAsap(cb, err);
	              } else if (err) {
	                throw err;
	              } else {
	                syncResult = tmpl.render(ctx, cb);
	              }
	            });
	            return syncResult;
	          };

	          _proto.renderString = function renderString(src, ctx, opts, cb) {
	            if (lib.isFunction(opts)) {
	              cb = opts;
	              opts = {};
	            }

	            opts = opts || {};
	            var tmpl = new Template(src, this, opts.path);
	            return tmpl.render(ctx, cb);
	          };

	          _proto.waterfall = function waterfall(tasks, callback, forceAsync) {
	            return _waterfall(tasks, callback, forceAsync);
	          };

	          return Environment;
	        }(EmitterObj);

	        var Context = /*#__PURE__*/function (_Obj) {
	          _inheritsLoose(Context, _Obj);

	          function Context() {
	            return _Obj.apply(this, arguments) || this;
	          }

	          var _proto2 = Context.prototype;

	          _proto2.init = function init(ctx, blocks, env) {
	            var _this4 = this; // Has to be tied to an environment so we can tap into its globals.


	            this.env = env || new Environment(); // Make a duplicate of ctx

	            this.ctx = lib.extend({}, ctx);
	            this.blocks = {};
	            this.exported = [];
	            lib.keys(blocks).forEach(function (name) {
	              _this4.addBlock(name, blocks[name]);
	            });
	          };

	          _proto2.lookup = function lookup(name) {
	            // This is one of the most called functions, so optimize for
	            // the typical case where the name isn't in the globals
	            if (name in this.env.globals && !(name in this.ctx)) {
	              return this.env.globals[name];
	            } else {
	              return this.ctx[name];
	            }
	          };

	          _proto2.setVariable = function setVariable(name, val) {
	            this.ctx[name] = val;
	          };

	          _proto2.getVariables = function getVariables() {
	            return this.ctx;
	          };

	          _proto2.addBlock = function addBlock(name, block) {
	            this.blocks[name] = this.blocks[name] || [];
	            this.blocks[name].push(block);
	            return this;
	          };

	          _proto2.getBlock = function getBlock(name) {
	            if (!this.blocks[name]) {
	              throw new Error('unknown block "' + name + '"');
	            }

	            return this.blocks[name][0];
	          };

	          _proto2.getSuper = function getSuper(env, name, block, frame, runtime, cb) {
	            var idx = lib.indexOf(this.blocks[name] || [], block);
	            var blk = this.blocks[name][idx + 1];
	            var context = this;

	            if (idx === -1 || !blk) {
	              throw new Error('no super block available for "' + name + '"');
	            }

	            blk(env, context, frame, runtime, cb);
	          };

	          _proto2.addExport = function addExport(name) {
	            this.exported.push(name);
	          };

	          _proto2.getExported = function getExported() {
	            var _this5 = this;

	            var exported = {};
	            this.exported.forEach(function (name) {
	              exported[name] = _this5.ctx[name];
	            });
	            return exported;
	          };

	          return Context;
	        }(Obj);

	        var Template = /*#__PURE__*/function (_Obj2) {
	          _inheritsLoose(Template, _Obj2);

	          function Template() {
	            return _Obj2.apply(this, arguments) || this;
	          }

	          var _proto3 = Template.prototype;

	          _proto3.init = function init(src, env, path, eagerCompile) {
	            this.env = env || new Environment();

	            if (lib.isObject(src)) {
	              switch (src.type) {
	                case 'code':
	                  this.tmplProps = src.obj;
	                  break;

	                case 'string':
	                  this.tmplStr = src.obj;
	                  break;

	                default:
	                  throw new Error("Unexpected template object type " + src.type + "; expected 'code', or 'string'");
	              }
	            } else if (lib.isString(src)) {
	              this.tmplStr = src;
	            } else {
	              throw new Error('src must be a string or an object describing the source');
	            }

	            this.path = path;

	            if (eagerCompile) {
	              try {
	                this._compile();
	              } catch (err) {
	                throw lib._prettifyError(this.path, this.env.opts.dev, err);
	              }
	            } else {
	              this.compiled = false;
	            }
	          };

	          _proto3.render = function render(ctx, parentFrame, cb) {
	            var _this6 = this;

	            if (typeof ctx === 'function') {
	              cb = ctx;
	              ctx = {};
	            } else if (typeof parentFrame === 'function') {
	              cb = parentFrame;
	              parentFrame = null;
	            } // If there is a parent frame, we are being called from internal
	            // code of another template, and the internal system
	            // depends on the sync/async nature of the parent template
	            // to be inherited, so force an async callback


	            var forceAsync = !parentFrame; // Catch compile errors for async rendering

	            try {
	              this.compile();
	            } catch (e) {
	              var err = lib._prettifyError(this.path, this.env.opts.dev, e);

	              if (cb) {
	                return callbackAsap(cb, err);
	              } else {
	                throw err;
	              }
	            }

	            var context = new Context(ctx || {}, this.blocks, this.env);
	            var frame = parentFrame ? parentFrame.push(true) : new Frame();
	            frame.topLevel = true;
	            var syncResult = null;
	            var didError = false;
	            this.rootRenderFunc(this.env, context, frame, globalRuntime, function (err, res) {
	              if (didError) {
	                // prevent multiple calls to cb
	                if (cb) {
	                  return;
	                } else {
	                  throw err;
	                }
	              }

	              if (err) {
	                err = lib._prettifyError(_this6.path, _this6.env.opts.dev, err);
	                didError = true;
	              }

	              if (cb) {
	                if (forceAsync) {
	                  callbackAsap(cb, err, res);
	                } else {
	                  cb(err, res);
	                }
	              } else {
	                if (err) {
	                  throw err;
	                }

	                syncResult = res;
	              }
	            });
	            return syncResult;
	          };

	          _proto3.getExported = function getExported(ctx, parentFrame, cb) {
	            // eslint-disable-line consistent-return
	            if (typeof ctx === 'function') {
	              cb = ctx;
	              ctx = {};
	            }

	            if (typeof parentFrame === 'function') {
	              cb = parentFrame;
	              parentFrame = null;
	            } // Catch compile errors for async rendering


	            try {
	              this.compile();
	            } catch (e) {
	              if (cb) {
	                return cb(e);
	              } else {
	                throw e;
	              }
	            }

	            var frame = parentFrame ? parentFrame.push() : new Frame();
	            frame.topLevel = true; // Run the rootRenderFunc to populate the context with exported vars

	            var context = new Context(ctx || {}, this.blocks, this.env);
	            this.rootRenderFunc(this.env, context, frame, globalRuntime, function (err) {
	              if (err) {
	                cb(err, null);
	              } else {
	                cb(null, context.getExported());
	              }
	            });
	          };

	          _proto3.compile = function compile() {
	            if (!this.compiled) {
	              this._compile();
	            }
	          };

	          _proto3._compile = function _compile() {
	            var props;

	            if (this.tmplProps) {
	              props = this.tmplProps;
	            } else {
	              var source = compiler.compile(this.tmplStr, this.env.asyncFilters, this.env.extensionsList, this.path, this.env.opts);
	              var func = new Function(source); // eslint-disable-line no-new-func

	              props = func();
	            }

	            this.blocks = this._getBlocks(props);
	            this.rootRenderFunc = props.root;
	            this.compiled = true;
	          };

	          _proto3._getBlocks = function _getBlocks(props) {
	            var blocks = {};
	            lib.keys(props).forEach(function (k) {
	              if (k.slice(0, 2) === 'b_') {
	                blocks[k.slice(2)] = props[k];
	              }
	            });
	            return blocks;
	          };

	          return Template;
	        }(Obj);

	        module.exports = {
	          Environment: Environment,
	          Template: Template
	        };
	        /***/
	      },
	      /* 8 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var rawAsap = __webpack_require__(9); // RawTasks are recycled to reduce GC churn.


	        var freeTasks = []; // We queue errors to ensure they are thrown in right order (FIFO).
	        // Array-as-queue is good enough here, since we are just dealing with exceptions.

	        var pendingErrors = [];
	        var requestErrorThrow = rawAsap.makeRequestCallFromTimer(throwFirstError);

	        function throwFirstError() {
	          if (pendingErrors.length) {
	            throw pendingErrors.shift();
	          }
	        }
	        /**
	         * Calls a task as soon as possible after returning, in its own event, with priority
	         * over other events like animation, reflow, and repaint. An error thrown from an
	         * event will not interrupt, nor even substantially slow down the processing of
	         * other events, but will be rather postponed to a lower priority event.
	         * @param {{call}} task A callable object, typically a function that takes no
	         * arguments.
	         */


	        module.exports = asap;

	        function asap(task) {
	          var rawTask;

	          if (freeTasks.length) {
	            rawTask = freeTasks.pop();
	          } else {
	            rawTask = new RawTask();
	          }

	          rawTask.task = task;
	          rawAsap(rawTask);
	        } // We wrap tasks with recyclable task objects.  A task object implements
	        // `call`, just like a function.


	        function RawTask() {
	          this.task = null;
	        } // The sole purpose of wrapping the task is to catch the exception and recycle
	        // the task object after its single use.


	        RawTask.prototype.call = function () {
	          try {
	            this.task.call();
	          } catch (error) {
	            if (asap.onerror) {
	              // This hook exists purely for testing purposes.
	              // Its name will be periodically randomized to break any code that
	              // depends on its existence.
	              asap.onerror(error);
	            } else {
	              // In a web browser, exceptions are not fatal. However, to avoid
	              // slowing down the queue of pending tasks, we rethrow the error in a
	              // lower priority turn.
	              pendingErrors.push(error);
	              requestErrorThrow();
	            }
	          } finally {
	            this.task = null;
	            freeTasks[freeTasks.length] = this;
	          }
	        };
	        /***/

	      },
	      /* 9 */

	      /***/
	      function (module, exports, __webpack_require__) {
	        /* WEBPACK VAR INJECTION */

	        (function (global) {
	          // Use the fastest means possible to execute a task in its own turn, with
	          // priority over other events including IO, animation, reflow, and redraw
	          // events in browsers.
	          //
	          // An exception thrown by a task will permanently interrupt the processing of
	          // subsequent tasks. The higher level `asap` function ensures that if an
	          // exception is thrown by a task, that the task queue will continue flushing as
	          // soon as possible, but if you use `rawAsap` directly, you are responsible to
	          // either ensure that no exceptions are thrown from your task, or to manually
	          // call `rawAsap.requestFlush` if an exception is thrown.
	          module.exports = rawAsap;

	          function rawAsap(task) {
	            if (!queue.length) {
	              requestFlush();
	            } // Equivalent to push, but avoids a function call.


	            queue[queue.length] = task;
	          }

	          var queue = []; // Once a flush has been requested, no further calls to `requestFlush` are
	          // off a `flush` event as quickly as possible. `flush` will attempt to exhaust
	          // the event queue before yielding to the browser's own event loop.

	          var requestFlush; // The position of the next task to execute in the task queue. This is
	          // preserved between calls to `flush` so that it can be resumed if
	          // a task throws an exception.

	          var index = 0; // If a task schedules additional tasks recursively, the task queue can grow
	          // unbounded. To prevent memory exhaustion, the task queue will periodically
	          // truncate already-completed tasks.

	          var capacity = 1024; // The flush function processes all tasks that have been scheduled with
	          // `rawAsap` unless and until one of those tasks throws an exception.
	          // If a task throws an exception, `flush` ensures that its state will remain
	          // consistent and will resume where it left off when called again.
	          // However, `flush` does not make any arrangements to be called again if an
	          // exception is thrown.

	          function flush() {
	            while (index < queue.length) {
	              var currentIndex = index; // Advance the index before calling the task. This ensures that we will
	              // begin flushing on the next task the task throws an error.

	              index = index + 1;
	              queue[currentIndex].call(); // Prevent leaking memory for long chains of recursive calls to `asap`.
	              // If we call `asap` within tasks scheduled by `asap`, the queue will
	              // grow, but to avoid an O(n) walk for every task we execute, we don't
	              // shift tasks off the queue after they have been executed.
	              // Instead, we periodically shift 1024 tasks off the queue.

	              if (index > capacity) {
	                // Manually shift all values starting at the index back to the
	                // beginning of the queue.
	                for (var scan = 0, newLength = queue.length - index; scan < newLength; scan++) {
	                  queue[scan] = queue[scan + index];
	                }

	                queue.length -= index;
	                index = 0;
	              }
	            }

	            queue.length = 0;
	            index = 0;
	          } // `requestFlush` is implemented using a strategy based on data collected from
	          // every available SauceLabs Selenium web driver worker at time of writing.
	          // https://docs.google.com/spreadsheets/d/1mG-5UYGup5qxGdEMWkhP6BWCz053NUb2E1QoUTU16uA/edit#gid=783724593
	          // Safari 6 and 6.1 for desktop, iPad, and iPhone are the only browsers that
	          // have WebKitMutationObserver but not un-prefixed MutationObserver.
	          // Must use `global` or `self` instead of `window` to work in both frames and web
	          // workers. `global` is a provision of Browserify, Mr, Mrs, or Mop.

	          /* globals self */


	          var scope = typeof global !== "undefined" ? global : self;
	          var BrowserMutationObserver = scope.MutationObserver || scope.WebKitMutationObserver; // MutationObservers are desirable because they have high priority and work
	          // reliably everywhere they are implemented.
	          // They are implemented in all modern browsers.
	          //
	          // - Android 4-4.3
	          // - Chrome 26-34
	          // - Firefox 14-29
	          // - Internet Explorer 11
	          // - iPad Safari 6-7.1
	          // - iPhone Safari 7-7.1
	          // - Safari 6-7

	          if (typeof BrowserMutationObserver === "function") {
	            requestFlush = makeRequestCallFromMutationObserver(flush); // MessageChannels are desirable because they give direct access to the HTML
	            // task queue, are implemented in Internet Explorer 10, Safari 5.0-1, and Opera
	            // 11-12, and in web workers in many engines.
	            // Although message channels yield to any queued rendering and IO tasks, they
	            // would be better than imposing the 4ms delay of timers.
	            // However, they do not work reliably in Internet Explorer or Safari.
	            // Internet Explorer 10 is the only browser that has setImmediate but does
	            // not have MutationObservers.
	            // Although setImmediate yields to the browser's renderer, it would be
	            // preferrable to falling back to setTimeout since it does not have
	            // the minimum 4ms penalty.
	            // Unfortunately there appears to be a bug in Internet Explorer 10 Mobile (and
	            // Desktop to a lesser extent) that renders both setImmediate and
	            // MessageChannel useless for the purposes of ASAP.
	            // https://github.com/kriskowal/q/issues/396
	            // Timers are implemented universally.
	            // We fall back to timers in workers in most engines, and in foreground
	            // contexts in the following browsers.
	            // However, note that even this simple case requires nuances to operate in a
	            // broad spectrum of browsers.
	            //
	            // - Firefox 3-13
	            // - Internet Explorer 6-9
	            // - iPad Safari 4.3
	            // - Lynx 2.8.7
	          } else {
	            requestFlush = makeRequestCallFromTimer(flush);
	          } // `requestFlush` requests that the high priority event queue be flushed as
	          // soon as possible.
	          // This is useful to prevent an error thrown in a task from stalling the event
	          // queue if the exception handled by Node.js’s
	          // `process.on("uncaughtException")` or by a domain.


	          rawAsap.requestFlush = requestFlush; // To request a high priority event, we induce a mutation observer by toggling
	          // the text of a text node between "1" and "-1".

	          function makeRequestCallFromMutationObserver(callback) {
	            var toggle = 1;
	            var observer = new BrowserMutationObserver(callback);
	            var node = document.createTextNode("");
	            observer.observe(node, {
	              characterData: true
	            });
	            return function requestCall() {
	              toggle = -toggle;
	              node.data = toggle;
	            };
	          } // The message channel technique was discovered by Malte Ubl and was the
	          // original foundation for this library.
	          // http://www.nonblocking.io/2011/06/windownexttick.html
	          // Safari 6.0.5 (at least) intermittently fails to create message ports on a
	          // page's first load. Thankfully, this version of Safari supports
	          // MutationObservers, so we don't need to fall back in that case.
	          // function makeRequestCallFromMessageChannel(callback) {
	          //     var channel = new MessageChannel();
	          //     channel.port1.onmessage = callback;
	          //     return function requestCall() {
	          //         channel.port2.postMessage(0);
	          //     };
	          // }
	          // For reasons explained above, we are also unable to use `setImmediate`
	          // under any circumstances.
	          // Even if we were, there is another bug in Internet Explorer 10.
	          // It is not sufficient to assign `setImmediate` to `requestFlush` because
	          // `setImmediate` must be called *by name* and therefore must be wrapped in a
	          // closure.
	          // Never forget.
	          // function makeRequestCallFromSetImmediate(callback) {
	          //     return function requestCall() {
	          //         setImmediate(callback);
	          //     };
	          // }
	          // Safari 6.0 has a problem where timers will get lost while the user is
	          // scrolling. This problem does not impact ASAP because Safari 6.0 supports
	          // mutation observers, so that implementation is used instead.
	          // However, if we ever elect to use timers in Safari, the prevalent work-around
	          // is to add a scroll event listener that calls for a flush.
	          // `setTimeout` does not call the passed callback if the delay is less than
	          // approximately 7 in web workers in Firefox 8 through 18, and sometimes not
	          // even then.


	          function makeRequestCallFromTimer(callback) {
	            return function requestCall() {
	              // We dispatch a timeout with a specified delay of 0 for engines that
	              // can reliably accommodate that request. This will usually be snapped
	              // to a 4 milisecond delay, but once we're flushing, there's no delay
	              // between events.
	              var timeoutHandle = setTimeout(handleTimer, 0); // However, since this timer gets frequently dropped in Firefox
	              // workers, we enlist an interval handle that will try to fire
	              // an event 20 times per second until it succeeds.

	              var intervalHandle = setInterval(handleTimer, 50);

	              function handleTimer() {
	                // Whichever timer succeeds will cancel both timers and
	                // execute the callback.
	                clearTimeout(timeoutHandle);
	                clearInterval(intervalHandle);
	                callback();
	              }
	            };
	          } // This is for `asap.js` only.
	          // Its name will be periodically randomized to break any code that depends on
	          // its existence.


	          rawAsap.makeRequestCallFromTimer = makeRequestCallFromTimer; // ASAP was originally a nextTick shim included in Q. This was factored out
	          // into this ASAP package. It was later adapted to RSVP which made further
	          // amendments. These decisions, particularly to marginalize MessageChannel and
	          // to capture the MutationObserver implementation in a closure, were integrated
	          // back into ASAP proper.
	          // https://github.com/tildeio/rsvp.js/blob/cddf7232546a9cf858524b75cde6f9edf72620a7/lib/rsvp/asap.js

	          /* WEBPACK VAR INJECTION */
	        }).call(exports, __webpack_require__(10));
	        /***/
	      },
	      /* 10 */

	      /***/
	      function (module, exports) {
	        var g; // This works in non-strict mode

	        g = function () {
	          return this;
	        }();

	        try {
	          // This works if eval is allowed (see CSP)
	          g = g || Function("return this")() || (1, eval)("this");
	        } catch (e) {
	          // This works if the window reference is available
	          if ((typeof window === "undefined" ? "undefined" : _typeof_1(window)) === "object") g = window;
	        } // g can still be undefined, but nothing to do about it...
	        // We return undefined, instead of nothing here, so it's
	        // easier to handle this case. if(!global) { ...}


	        module.exports = g;
	        /***/
	      },
	      /* 11 */

	      /***/
	      function (module, exports, __webpack_require__) {
	        var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__; // MIT license (by Elan Shanker).


	        (function (globals) {

	          var executeSync = function executeSync() {
	            var args = Array.prototype.slice.call(arguments);

	            if (typeof args[0] === 'function') {
	              args[0].apply(null, args.splice(1));
	            }
	          };

	          var executeAsync = function executeAsync(fn) {
	            if (typeof setImmediate === 'function') {
	              setImmediate(fn);
	            } else if (typeof process !== 'undefined' && process.nextTick) {
	              process.nextTick(fn);
	            } else {
	              setTimeout(fn, 0);
	            }
	          };

	          var makeIterator = function makeIterator(tasks) {
	            var makeCallback = function makeCallback(index) {
	              var fn = function fn() {
	                if (tasks.length) {
	                  tasks[index].apply(null, arguments);
	                }

	                return fn.next();
	              };

	              fn.next = function () {
	                return index < tasks.length - 1 ? makeCallback(index + 1) : null;
	              };

	              return fn;
	            };

	            return makeCallback(0);
	          };

	          var _isArray = Array.isArray || function (maybeArray) {
	            return Object.prototype.toString.call(maybeArray) === '[object Array]';
	          };

	          var waterfall = function waterfall(tasks, callback, forceAsync) {
	            var nextTick = forceAsync ? executeAsync : executeSync;

	            callback = callback || function () {};

	            if (!_isArray(tasks)) {
	              var err = new Error('First argument to waterfall must be an array of functions');
	              return callback(err);
	            }

	            if (!tasks.length) {
	              return callback();
	            }

	            var wrapIterator = function wrapIterator(iterator) {
	              return function (err) {
	                if (err) {
	                  callback.apply(null, arguments);

	                  callback = function callback() {};
	                } else {
	                  var args = Array.prototype.slice.call(arguments, 1);
	                  var next = iterator.next();

	                  if (next) {
	                    args.push(wrapIterator(next));
	                  } else {
	                    args.push(callback);
	                  }

	                  nextTick(function () {
	                    iterator.apply(null, args);
	                  });
	                }
	              };
	            };

	            wrapIterator(makeIterator(tasks))();
	          };

	          {
	            !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = function () {
	              return waterfall;
	            }.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)); // RequireJS
	          }
	        })();
	        /***/

	      },
	      /* 12 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var lib = __webpack_require__(1);

	        var r = __webpack_require__(2);

	        var exports = module.exports = {};

	        function normalize(value, defaultValue) {
	          if (value === null || value === undefined || value === false) {
	            return defaultValue;
	          }

	          return value;
	        }

	        exports.abs = Math.abs;

	        function isNaN(num) {
	          return num !== num; // eslint-disable-line no-self-compare
	        }

	        function batch(arr, linecount, fillWith) {
	          var i;
	          var res = [];
	          var tmp = [];

	          for (i = 0; i < arr.length; i++) {
	            if (i % linecount === 0 && tmp.length) {
	              res.push(tmp);
	              tmp = [];
	            }

	            tmp.push(arr[i]);
	          }

	          if (tmp.length) {
	            if (fillWith) {
	              for (i = tmp.length; i < linecount; i++) {
	                tmp.push(fillWith);
	              }
	            }

	            res.push(tmp);
	          }

	          return res;
	        }

	        exports.batch = batch;

	        function capitalize(str) {
	          str = normalize(str, '');
	          var ret = str.toLowerCase();
	          return r.copySafeness(str, ret.charAt(0).toUpperCase() + ret.slice(1));
	        }

	        exports.capitalize = capitalize;

	        function center(str, width) {
	          str = normalize(str, '');
	          width = width || 80;

	          if (str.length >= width) {
	            return str;
	          }

	          var spaces = width - str.length;
	          var pre = lib.repeat(' ', spaces / 2 - spaces % 2);
	          var post = lib.repeat(' ', spaces / 2);
	          return r.copySafeness(str, pre + str + post);
	        }

	        exports.center = center;

	        function default_(val, def, bool) {
	          if (bool) {
	            return val || def;
	          } else {
	            return val !== undefined ? val : def;
	          }
	        } // TODO: it is confusing to export something called 'default'


	        exports['default'] = default_; // eslint-disable-line dot-notation

	        function dictsort(val, caseSensitive, by) {
	          if (!lib.isObject(val)) {
	            throw new lib.TemplateError('dictsort filter: val must be an object');
	          }

	          var array = []; // deliberately include properties from the object's prototype

	          for (var k in val) {
	            // eslint-disable-line guard-for-in, no-restricted-syntax
	            array.push([k, val[k]]);
	          }

	          var si;

	          if (by === undefined || by === 'key') {
	            si = 0;
	          } else if (by === 'value') {
	            si = 1;
	          } else {
	            throw new lib.TemplateError('dictsort filter: You can only sort by either key or value');
	          }

	          array.sort(function (t1, t2) {
	            var a = t1[si];
	            var b = t2[si];

	            if (!caseSensitive) {
	              if (lib.isString(a)) {
	                a = a.toUpperCase();
	              }

	              if (lib.isString(b)) {
	                b = b.toUpperCase();
	              }
	            }

	            return a > b ? 1 : a === b ? 0 : -1; // eslint-disable-line no-nested-ternary
	          });
	          return array;
	        }

	        exports.dictsort = dictsort;

	        function dump(obj, spaces) {
	          return JSON.stringify(obj, null, spaces);
	        }

	        exports.dump = dump;

	        function escape(str) {
	          if (str instanceof r.SafeString) {
	            return str;
	          }

	          str = str === null || str === undefined ? '' : str;
	          return r.markSafe(lib.escape(str.toString()));
	        }

	        exports.escape = escape;

	        function safe(str) {
	          if (str instanceof r.SafeString) {
	            return str;
	          }

	          str = str === null || str === undefined ? '' : str;
	          return r.markSafe(str.toString());
	        }

	        exports.safe = safe;

	        function first(arr) {
	          return arr[0];
	        }

	        exports.first = first;

	        function forceescape(str) {
	          str = str === null || str === undefined ? '' : str;
	          return r.markSafe(lib.escape(str.toString()));
	        }

	        exports.forceescape = forceescape;

	        function groupby(arr, attr) {
	          return lib.groupBy(arr, attr);
	        }

	        exports.groupby = groupby;

	        function indent(str, width, indentfirst) {
	          str = normalize(str, '');

	          if (str === '') {
	            return '';
	          }

	          width = width || 4; // let res = '';

	          var lines = str.split('\n');
	          var sp = lib.repeat(' ', width);
	          var res = lines.map(function (l, i) {
	            return i === 0 && !indentfirst ? l + "\n" : "" + sp + l + "\n";
	          }).join('');
	          return r.copySafeness(str, res);
	        }

	        exports.indent = indent;

	        function join(arr, del, attr) {
	          del = del || '';

	          if (attr) {
	            arr = lib.map(arr, function (v) {
	              return v[attr];
	            });
	          }

	          return arr.join(del);
	        }

	        exports.join = join;

	        function last(arr) {
	          return arr[arr.length - 1];
	        }

	        exports.last = last;

	        function lengthFilter(val) {
	          var value = normalize(val, '');

	          if (value !== undefined) {
	            if (typeof Map === 'function' && value instanceof Map || typeof Set === 'function' && value instanceof Set) {
	              // ECMAScript 2015 Maps and Sets
	              return value.size;
	            }

	            if (lib.isObject(value) && !(value instanceof r.SafeString)) {
	              // Objects (besides SafeStrings), non-primative Arrays
	              return lib.keys(value).length;
	            }

	            return value.length;
	          }

	          return 0;
	        }

	        exports.length = lengthFilter;

	        function list(val) {
	          if (lib.isString(val)) {
	            return val.split('');
	          } else if (lib.isObject(val)) {
	            return lib._entries(val || {}).map(function (_ref) {
	              var key = _ref[0],
	                  value = _ref[1];
	              return {
	                key: key,
	                value: value
	              };
	            });
	          } else if (lib.isArray(val)) {
	            return val;
	          } else {
	            throw new lib.TemplateError('list filter: type not iterable');
	          }
	        }

	        exports.list = list;

	        function lower(str) {
	          str = normalize(str, '');
	          return str.toLowerCase();
	        }

	        exports.lower = lower;

	        function nl2br(str) {
	          if (str === null || str === undefined) {
	            return '';
	          }

	          return r.copySafeness(str, str.replace(/\r\n|\n/g, '<br />\n'));
	        }

	        exports.nl2br = nl2br;

	        function random(arr) {
	          return arr[Math.floor(Math.random() * arr.length)];
	        }

	        exports.random = random;

	        function rejectattr(arr, attr) {
	          return arr.filter(function (item) {
	            return !item[attr];
	          });
	        }

	        exports.rejectattr = rejectattr;

	        function selectattr(arr, attr) {
	          return arr.filter(function (item) {
	            return !!item[attr];
	          });
	        }

	        exports.selectattr = selectattr;

	        function replace(str, old, new_, maxCount) {
	          var originalStr = str;

	          if (old instanceof RegExp) {
	            return str.replace(old, new_);
	          }

	          if (typeof maxCount === 'undefined') {
	            maxCount = -1;
	          }

	          var res = ''; // Output
	          // Cast Numbers in the search term to string

	          if (typeof old === 'number') {
	            old = '' + old;
	          } else if (typeof old !== 'string') {
	            // If it is something other than number or string,
	            // return the original string
	            return str;
	          } // Cast numbers in the replacement to string


	          if (typeof str === 'number') {
	            str = '' + str;
	          } // If by now, we don't have a string, throw it back


	          if (typeof str !== 'string' && !(str instanceof r.SafeString)) {
	            return str;
	          } // ShortCircuits


	          if (old === '') {
	            // Mimic the python behaviour: empty string is replaced
	            // by replacement e.g. "abc"|replace("", ".") -> .a.b.c.
	            res = new_ + str.split('').join(new_) + new_;
	            return r.copySafeness(str, res);
	          }

	          var nextIndex = str.indexOf(old); // if # of replacements to perform is 0, or the string to does
	          // not contain the old value, return the string

	          if (maxCount === 0 || nextIndex === -1) {
	            return str;
	          }

	          var pos = 0;
	          var count = 0; // # of replacements made

	          while (nextIndex > -1 && (maxCount === -1 || count < maxCount)) {
	            // Grab the next chunk of src string and add it with the
	            // replacement, to the result
	            res += str.substring(pos, nextIndex) + new_; // Increment our pointer in the src string

	            pos = nextIndex + old.length;
	            count++; // See if there are any more replacements to be made

	            nextIndex = str.indexOf(old, pos);
	          } // We've either reached the end, or done the max # of
	          // replacements, tack on any remaining string


	          if (pos < str.length) {
	            res += str.substring(pos);
	          }

	          return r.copySafeness(originalStr, res);
	        }

	        exports.replace = replace;

	        function reverse(val) {
	          var arr;

	          if (lib.isString(val)) {
	            arr = list(val);
	          } else {
	            // Copy it
	            arr = lib.map(val, function (v) {
	              return v;
	            });
	          }

	          arr.reverse();

	          if (lib.isString(val)) {
	            return r.copySafeness(val, arr.join(''));
	          }

	          return arr;
	        }

	        exports.reverse = reverse;

	        function round(val, precision, method) {
	          precision = precision || 0;
	          var factor = Math.pow(10, precision);
	          var rounder;

	          if (method === 'ceil') {
	            rounder = Math.ceil;
	          } else if (method === 'floor') {
	            rounder = Math.floor;
	          } else {
	            rounder = Math.round;
	          }

	          return rounder(val * factor) / factor;
	        }

	        exports.round = round;

	        function slice(arr, slices, fillWith) {
	          var sliceLength = Math.floor(arr.length / slices);
	          var extra = arr.length % slices;
	          var res = [];
	          var offset = 0;

	          for (var i = 0; i < slices; i++) {
	            var start = offset + i * sliceLength;

	            if (i < extra) {
	              offset++;
	            }

	            var end = offset + (i + 1) * sliceLength;
	            var currSlice = arr.slice(start, end);

	            if (fillWith && i >= extra) {
	              currSlice.push(fillWith);
	            }

	            res.push(currSlice);
	          }

	          return res;
	        }

	        exports.slice = slice;

	        function sum(arr, attr, start) {
	          if (start === void 0) {
	            start = 0;
	          }

	          if (attr) {
	            arr = lib.map(arr, function (v) {
	              return v[attr];
	            });
	          }

	          return start + arr.reduce(function (a, b) {
	            return a + b;
	          }, 0);
	        }

	        exports.sum = sum;
	        exports.sort = r.makeMacro(['value', 'reverse', 'case_sensitive', 'attribute'], [], function (arr, reversed, caseSens, attr) {
	          // Copy it
	          var array = lib.map(arr, function (v) {
	            return v;
	          });
	          array.sort(function (a, b) {
	            var x = attr ? a[attr] : a;
	            var y = attr ? b[attr] : b;

	            if (!caseSens && lib.isString(x) && lib.isString(y)) {
	              x = x.toLowerCase();
	              y = y.toLowerCase();
	            }

	            if (x < y) {
	              return reversed ? 1 : -1;
	            } else if (x > y) {
	              return reversed ? -1 : 1;
	            } else {
	              return 0;
	            }
	          });
	          return array;
	        });

	        function string(obj) {
	          return r.copySafeness(obj, obj);
	        }

	        exports.string = string;

	        function striptags(input, preserveLinebreaks) {
	          input = normalize(input, '');
	          var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>|<!--[\s\S]*?-->/gi;
	          var trimmedInput = trim(input.replace(tags, ''));
	          var res = '';

	          if (preserveLinebreaks) {
	            res = trimmedInput.replace(/^ +| +$/gm, '') // remove leading and trailing spaces
	            .replace(/ +/g, ' ') // squash adjacent spaces
	            .replace(/(\r\n)/g, '\n') // normalize linebreaks (CRLF -> LF)
	            .replace(/\n\n\n+/g, '\n\n'); // squash abnormal adjacent linebreaks
	          } else {
	            res = trimmedInput.replace(/\s+/gi, ' ');
	          }

	          return r.copySafeness(input, res);
	        }

	        exports.striptags = striptags;

	        function title(str) {
	          str = normalize(str, '');
	          var words = str.split(' ').map(function (word) {
	            return capitalize(word);
	          });
	          return r.copySafeness(str, words.join(' '));
	        }

	        exports.title = title;

	        function trim(str) {
	          return r.copySafeness(str, str.replace(/^\s*|\s*$/g, ''));
	        }

	        exports.trim = trim;

	        function truncate(input, length, killwords, end) {
	          var orig = input;
	          input = normalize(input, '');
	          length = length || 255;

	          if (input.length <= length) {
	            return input;
	          }

	          if (killwords) {
	            input = input.substring(0, length);
	          } else {
	            var idx = input.lastIndexOf(' ', length);

	            if (idx === -1) {
	              idx = length;
	            }

	            input = input.substring(0, idx);
	          }

	          input += end !== undefined && end !== null ? end : '...';
	          return r.copySafeness(orig, input);
	        }

	        exports.truncate = truncate;

	        function upper(str) {
	          str = normalize(str, '');
	          return str.toUpperCase();
	        }

	        exports.upper = upper;

	        function urlencode(obj) {
	          var enc = encodeURIComponent;

	          if (lib.isString(obj)) {
	            return enc(obj);
	          } else {
	            var keyvals = lib.isArray(obj) ? obj : lib._entries(obj);
	            return keyvals.map(function (_ref2) {
	              var k = _ref2[0],
	                  v = _ref2[1];
	              return enc(k) + "=" + enc(v);
	            }).join('&');
	          }
	        }

	        exports.urlencode = urlencode; // For the jinja regexp, see
	        // https://github.com/mitsuhiko/jinja2/blob/f15b814dcba6aa12bc74d1f7d0c881d55f7126be/jinja2/utils.py#L20-L23

	        var puncRe = /^(?:\(|<|&lt;)?(.*?)(?:\.|,|\)|\n|&gt;)?$/; // from http://blog.gerv.net/2011/05/html5_email_address_regexp/

	        var emailRe = /^[\w.!#$%&'*+\-\/=?\^`{|}~]+@[a-z\d\-]+(\.[a-z\d\-]+)+$/i;
	        var httpHttpsRe = /^https?:\/\/.*$/;
	        var wwwRe = /^www\./;
	        var tldRe = /\.(?:org|net|com)(?:\:|\/|$)/;

	        function urlize(str, length, nofollow) {
	          if (isNaN(length)) {
	            length = Infinity;
	          }

	          var noFollowAttr = nofollow === true ? ' rel="nofollow"' : '';
	          var words = str.split(/(\s+)/).filter(function (word) {
	            // If the word has no length, bail. This can happen for str with
	            // trailing whitespace.
	            return word && word.length;
	          }).map(function (word) {
	            var matches = word.match(puncRe);
	            var possibleUrl = matches ? matches[1] : word;
	            var shortUrl = possibleUrl.substr(0, length); // url that starts with http or https

	            if (httpHttpsRe.test(possibleUrl)) {
	              return "<a href=\"" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
	            } // url that starts with www.


	            if (wwwRe.test(possibleUrl)) {
	              return "<a href=\"http://" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
	            } // an email address of the form username@domain.tld


	            if (emailRe.test(possibleUrl)) {
	              return "<a href=\"mailto:" + possibleUrl + "\">" + possibleUrl + "</a>";
	            } // url that ends in .com, .org or .net that is not an email address


	            if (tldRe.test(possibleUrl)) {
	              return "<a href=\"http://" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
	            }

	            return word;
	          });
	          return words.join('');
	        }

	        exports.urlize = urlize;

	        function wordcount(str) {
	          str = normalize(str, '');
	          var words = str ? str.match(/\w+/g) : null;
	          return words ? words.length : null;
	        }

	        exports.wordcount = wordcount;

	        function float(val, def) {
	          var res = parseFloat(val);
	          return isNaN(res) ? def : res;
	        }

	        exports.float = float;

	        function int(val, def) {
	          var res = parseInt(val, 10);
	          return isNaN(res) ? def : res;
	        }

	        exports.int = int; // Aliases

	        exports.d = exports.default;
	        exports.e = exports.escape;
	        /***/
	      },
	      /* 13 */

	      /***/
	      function (module, exports, __webpack_require__) {
	        //
	        // Permission is hereby granted, free of charge, to any person obtaining a
	        // copy of this software and associated documentation files (the
	        // "Software"), to deal in the Software without restriction, including
	        // without limitation the rights to use, copy, modify, merge, publish,
	        // distribute, sublicense, and/or sell copies of the Software, and to permit
	        // persons to whom the Software is furnished to do so, subject to the
	        // following conditions:
	        //
	        // The above copyright notice and this permission notice shall be included
	        // in all copies or substantial portions of the Software.
	        //
	        // THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
	        // OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	        // MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
	        // NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
	        // DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
	        // OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
	        // USE OR OTHER DEALINGS IN THE SOFTWARE.

	        var R = (typeof Reflect === "undefined" ? "undefined" : _typeof_1(Reflect)) === 'object' ? Reflect : null;
	        var ReflectApply = R && typeof R.apply === 'function' ? R.apply : function ReflectApply(target, receiver, args) {
	          return Function.prototype.apply.call(target, receiver, args);
	        };
	        var ReflectOwnKeys;

	        if (R && typeof R.ownKeys === 'function') {
	          ReflectOwnKeys = R.ownKeys;
	        } else if (Object.getOwnPropertySymbols) {
	          ReflectOwnKeys = function ReflectOwnKeys(target) {
	            return Object.getOwnPropertyNames(target).concat(Object.getOwnPropertySymbols(target));
	          };
	        } else {
	          ReflectOwnKeys = function ReflectOwnKeys(target) {
	            return Object.getOwnPropertyNames(target);
	          };
	        }

	        function ProcessEmitWarning(warning) {
	          if (console && console.warn) console.warn(warning);
	        }

	        var NumberIsNaN = Number.isNaN || function NumberIsNaN(value) {
	          return value !== value;
	        };

	        function EventEmitter() {
	          EventEmitter.init.call(this);
	        }

	        module.exports = EventEmitter; // Backwards-compat with node 0.10.x

	        EventEmitter.EventEmitter = EventEmitter;
	        EventEmitter.prototype._events = undefined;
	        EventEmitter.prototype._eventsCount = 0;
	        EventEmitter.prototype._maxListeners = undefined; // By default EventEmitters will print a warning if more than 10 listeners are
	        // added to it. This is a useful default which helps finding memory leaks.

	        var defaultMaxListeners = 10;
	        Object.defineProperty(EventEmitter, 'defaultMaxListeners', {
	          enumerable: true,
	          get: function get() {
	            return defaultMaxListeners;
	          },
	          set: function set(arg) {
	            if (typeof arg !== 'number' || arg < 0 || NumberIsNaN(arg)) {
	              throw new RangeError('The value of "defaultMaxListeners" is out of range. It must be a non-negative number. Received ' + arg + '.');
	            }

	            defaultMaxListeners = arg;
	          }
	        });

	        EventEmitter.init = function () {
	          if (this._events === undefined || this._events === Object.getPrototypeOf(this)._events) {
	            this._events = Object.create(null);
	            this._eventsCount = 0;
	          }

	          this._maxListeners = this._maxListeners || undefined;
	        }; // Obviously not all Emitters should be limited to 10. This function allows
	        // that to be increased. Set to zero for unlimited.


	        EventEmitter.prototype.setMaxListeners = function setMaxListeners(n) {
	          if (typeof n !== 'number' || n < 0 || NumberIsNaN(n)) {
	            throw new RangeError('The value of "n" is out of range. It must be a non-negative number. Received ' + n + '.');
	          }

	          this._maxListeners = n;
	          return this;
	        };

	        function $getMaxListeners(that) {
	          if (that._maxListeners === undefined) return EventEmitter.defaultMaxListeners;
	          return that._maxListeners;
	        }

	        EventEmitter.prototype.getMaxListeners = function getMaxListeners() {
	          return $getMaxListeners(this);
	        };

	        EventEmitter.prototype.emit = function emit(type) {
	          var args = [];

	          for (var i = 1; i < arguments.length; i++) {
	            args.push(arguments[i]);
	          }

	          var doError = type === 'error';
	          var events = this._events;
	          if (events !== undefined) doError = doError && events.error === undefined;else if (!doError) return false; // If there is no 'error' event listener then throw.

	          if (doError) {
	            var er;
	            if (args.length > 0) er = args[0];

	            if (er instanceof Error) {
	              // Note: The comments on the `throw` lines are intentional, they show
	              // up in Node's output if this results in an unhandled exception.
	              throw er; // Unhandled 'error' event
	            } // At least give some kind of context to the user


	            var err = new Error('Unhandled error.' + (er ? ' (' + er.message + ')' : ''));
	            err.context = er;
	            throw err; // Unhandled 'error' event
	          }

	          var handler = events[type];
	          if (handler === undefined) return false;

	          if (typeof handler === 'function') {
	            ReflectApply(handler, this, args);
	          } else {
	            var len = handler.length;
	            var listeners = arrayClone(handler, len);

	            for (var i = 0; i < len; ++i) {
	              ReflectApply(listeners[i], this, args);
	            }
	          }

	          return true;
	        };

	        function _addListener(target, type, listener, prepend) {
	          var m;
	          var events;
	          var existing;

	          if (typeof listener !== 'function') {
	            throw new TypeError('The "listener" argument must be of type Function. Received type ' + _typeof_1(listener));
	          }

	          events = target._events;

	          if (events === undefined) {
	            events = target._events = Object.create(null);
	            target._eventsCount = 0;
	          } else {
	            // To avoid recursion in the case that type === "newListener"! Before
	            // adding it to the listeners, first emit "newListener".
	            if (events.newListener !== undefined) {
	              target.emit('newListener', type, listener.listener ? listener.listener : listener); // Re-assign `events` because a newListener handler could have caused the
	              // this._events to be assigned to a new object

	              events = target._events;
	            }

	            existing = events[type];
	          }

	          if (existing === undefined) {
	            // Optimize the case of one listener. Don't need the extra array object.
	            existing = events[type] = listener;
	            ++target._eventsCount;
	          } else {
	            if (typeof existing === 'function') {
	              // Adding the second element, need to change to array.
	              existing = events[type] = prepend ? [listener, existing] : [existing, listener]; // If we've already got an array, just append.
	            } else if (prepend) {
	              existing.unshift(listener);
	            } else {
	              existing.push(listener);
	            } // Check for listener leak


	            m = $getMaxListeners(target);

	            if (m > 0 && existing.length > m && !existing.warned) {
	              existing.warned = true; // No error code for this since it is a Warning
	              // eslint-disable-next-line no-restricted-syntax

	              var w = new Error('Possible EventEmitter memory leak detected. ' + existing.length + ' ' + String(type) + ' listeners ' + 'added. Use emitter.setMaxListeners() to ' + 'increase limit');
	              w.name = 'MaxListenersExceededWarning';
	              w.emitter = target;
	              w.type = type;
	              w.count = existing.length;
	              ProcessEmitWarning(w);
	            }
	          }

	          return target;
	        }

	        EventEmitter.prototype.addListener = function addListener(type, listener) {
	          return _addListener(this, type, listener, false);
	        };

	        EventEmitter.prototype.on = EventEmitter.prototype.addListener;

	        EventEmitter.prototype.prependListener = function prependListener(type, listener) {
	          return _addListener(this, type, listener, true);
	        };

	        function onceWrapper() {
	          var args = [];

	          for (var i = 0; i < arguments.length; i++) {
	            args.push(arguments[i]);
	          }

	          if (!this.fired) {
	            this.target.removeListener(this.type, this.wrapFn);
	            this.fired = true;
	            ReflectApply(this.listener, this.target, args);
	          }
	        }

	        function _onceWrap(target, type, listener) {
	          var state = {
	            fired: false,
	            wrapFn: undefined,
	            target: target,
	            type: type,
	            listener: listener
	          };
	          var wrapped = onceWrapper.bind(state);
	          wrapped.listener = listener;
	          state.wrapFn = wrapped;
	          return wrapped;
	        }

	        EventEmitter.prototype.once = function once(type, listener) {
	          if (typeof listener !== 'function') {
	            throw new TypeError('The "listener" argument must be of type Function. Received type ' + _typeof_1(listener));
	          }

	          this.on(type, _onceWrap(this, type, listener));
	          return this;
	        };

	        EventEmitter.prototype.prependOnceListener = function prependOnceListener(type, listener) {
	          if (typeof listener !== 'function') {
	            throw new TypeError('The "listener" argument must be of type Function. Received type ' + _typeof_1(listener));
	          }

	          this.prependListener(type, _onceWrap(this, type, listener));
	          return this;
	        }; // Emits a 'removeListener' event if and only if the listener was removed.


	        EventEmitter.prototype.removeListener = function removeListener(type, listener) {
	          var list, events, position, i, originalListener;

	          if (typeof listener !== 'function') {
	            throw new TypeError('The "listener" argument must be of type Function. Received type ' + _typeof_1(listener));
	          }

	          events = this._events;
	          if (events === undefined) return this;
	          list = events[type];
	          if (list === undefined) return this;

	          if (list === listener || list.listener === listener) {
	            if (--this._eventsCount === 0) this._events = Object.create(null);else {
	              delete events[type];
	              if (events.removeListener) this.emit('removeListener', type, list.listener || listener);
	            }
	          } else if (typeof list !== 'function') {
	            position = -1;

	            for (i = list.length - 1; i >= 0; i--) {
	              if (list[i] === listener || list[i].listener === listener) {
	                originalListener = list[i].listener;
	                position = i;
	                break;
	              }
	            }

	            if (position < 0) return this;
	            if (position === 0) list.shift();else {
	              spliceOne(list, position);
	            }
	            if (list.length === 1) events[type] = list[0];
	            if (events.removeListener !== undefined) this.emit('removeListener', type, originalListener || listener);
	          }

	          return this;
	        };

	        EventEmitter.prototype.off = EventEmitter.prototype.removeListener;

	        EventEmitter.prototype.removeAllListeners = function removeAllListeners(type) {
	          var listeners, events, i;
	          events = this._events;
	          if (events === undefined) return this; // not listening for removeListener, no need to emit

	          if (events.removeListener === undefined) {
	            if (arguments.length === 0) {
	              this._events = Object.create(null);
	              this._eventsCount = 0;
	            } else if (events[type] !== undefined) {
	              if (--this._eventsCount === 0) this._events = Object.create(null);else delete events[type];
	            }

	            return this;
	          } // emit removeListener for all listeners on all events


	          if (arguments.length === 0) {
	            var keys = Object.keys(events);
	            var key;

	            for (i = 0; i < keys.length; ++i) {
	              key = keys[i];
	              if (key === 'removeListener') continue;
	              this.removeAllListeners(key);
	            }

	            this.removeAllListeners('removeListener');
	            this._events = Object.create(null);
	            this._eventsCount = 0;
	            return this;
	          }

	          listeners = events[type];

	          if (typeof listeners === 'function') {
	            this.removeListener(type, listeners);
	          } else if (listeners !== undefined) {
	            // LIFO order
	            for (i = listeners.length - 1; i >= 0; i--) {
	              this.removeListener(type, listeners[i]);
	            }
	          }

	          return this;
	        };

	        function _listeners(target, type, unwrap) {
	          var events = target._events;
	          if (events === undefined) return [];
	          var evlistener = events[type];
	          if (evlistener === undefined) return [];
	          if (typeof evlistener === 'function') return unwrap ? [evlistener.listener || evlistener] : [evlistener];
	          return unwrap ? unwrapListeners(evlistener) : arrayClone(evlistener, evlistener.length);
	        }

	        EventEmitter.prototype.listeners = function listeners(type) {
	          return _listeners(this, type, true);
	        };

	        EventEmitter.prototype.rawListeners = function rawListeners(type) {
	          return _listeners(this, type, false);
	        };

	        EventEmitter.listenerCount = function (emitter, type) {
	          if (typeof emitter.listenerCount === 'function') {
	            return emitter.listenerCount(type);
	          } else {
	            return listenerCount.call(emitter, type);
	          }
	        };

	        EventEmitter.prototype.listenerCount = listenerCount;

	        function listenerCount(type) {
	          var events = this._events;

	          if (events !== undefined) {
	            var evlistener = events[type];

	            if (typeof evlistener === 'function') {
	              return 1;
	            } else if (evlistener !== undefined) {
	              return evlistener.length;
	            }
	          }

	          return 0;
	        }

	        EventEmitter.prototype.eventNames = function eventNames() {
	          return this._eventsCount > 0 ? ReflectOwnKeys(this._events) : [];
	        };

	        function arrayClone(arr, n) {
	          var copy = new Array(n);

	          for (var i = 0; i < n; ++i) {
	            copy[i] = arr[i];
	          }

	          return copy;
	        }

	        function spliceOne(list, index) {
	          for (; index + 1 < list.length; index++) {
	            list[index] = list[index + 1];
	          }

	          list.pop();
	        }

	        function unwrapListeners(arr) {
	          var ret = new Array(arr.length);

	          for (var i = 0; i < ret.length; ++i) {
	            ret[i] = arr[i].listener || arr[i];
	          }

	          return ret;
	        }
	        /***/

	      },
	      /* 14 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        var SafeString = __webpack_require__(2).SafeString;
	        /**
	         * Returns `true` if the object is a function, otherwise `false`.
	         * @param { any } value
	         * @returns { boolean }
	         */


	        function callable(value) {
	          return typeof value === 'function';
	        }

	        exports.callable = callable;
	        /**
	         * Returns `true` if the object is strictly not `undefined`.
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function defined(value) {
	          return value !== undefined;
	        }

	        exports.defined = defined;
	        /**
	         * Returns `true` if the operand (one) is divisble by the test's argument
	         * (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function divisibleby(one, two) {
	          return one % two === 0;
	        }

	        exports.divisibleby = divisibleby;
	        /**
	         * Returns true if the string has been escaped (i.e., is a SafeString).
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function escaped(value) {
	          return value instanceof SafeString;
	        }

	        exports.escaped = escaped;
	        /**
	         * Returns `true` if the arguments are strictly equal.
	         * @param { any } one
	         * @param { any } two
	         */

	        function equalto(one, two) {
	          return one === two;
	        }

	        exports.equalto = equalto; // Aliases

	        exports.eq = exports.equalto;
	        exports.sameas = exports.equalto;
	        /**
	         * Returns `true` if the value is evenly divisible by 2.
	         * @param { number } value
	         * @returns { boolean }
	         */

	        function even(value) {
	          return value % 2 === 0;
	        }

	        exports.even = even;
	        /**
	         * Returns `true` if the value is falsy - if I recall correctly, '', 0, false,
	         * undefined, NaN or null. I don't know if we should stick to the default JS
	         * behavior or attempt to replicate what Python believes should be falsy (i.e.,
	         * empty arrays, empty dicts, not 0...).
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function falsy(value) {
	          return !value;
	        }

	        exports.falsy = falsy;
	        /**
	         * Returns `true` if the operand (one) is greater or equal to the test's
	         * argument (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function ge(one, two) {
	          return one >= two;
	        }

	        exports.ge = ge;
	        /**
	         * Returns `true` if the operand (one) is greater than the test's argument
	         * (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function greaterthan(one, two) {
	          return one > two;
	        }

	        exports.greaterthan = greaterthan; // alias

	        exports.gt = exports.greaterthan;
	        /**
	         * Returns `true` if the operand (one) is less than or equal to the test's
	         * argument (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function le(one, two) {
	          return one <= two;
	        }

	        exports.le = le;
	        /**
	         * Returns `true` if the operand (one) is less than the test's passed argument
	         * (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function lessthan(one, two) {
	          return one < two;
	        }

	        exports.lessthan = lessthan; // alias

	        exports.lt = exports.lessthan;
	        /**
	         * Returns `true` if the string is lowercased.
	         * @param { string } value
	         * @returns { boolean }
	         */

	        function lower(value) {
	          return value.toLowerCase() === value;
	        }

	        exports.lower = lower;
	        /**
	         * Returns `true` if the operand (one) is less than or equal to the test's
	         * argument (two).
	         * @param { number } one
	         * @param { number } two
	         * @returns { boolean }
	         */

	        function ne(one, two) {
	          return one !== two;
	        }

	        exports.ne = ne;
	        /**
	         * Returns true if the value is strictly equal to `null`.
	         * @param { any }
	         * @returns { boolean }
	         */

	        function nullTest(value) {
	          return value === null;
	        }

	        exports.null = nullTest;
	        /**
	         * Returns true if value is a number.
	         * @param { any }
	         * @returns { boolean }
	         */

	        function number(value) {
	          return typeof value === 'number';
	        }

	        exports.number = number;
	        /**
	         * Returns `true` if the value is *not* evenly divisible by 2.
	         * @param { number } value
	         * @returns { boolean }
	         */

	        function odd(value) {
	          return value % 2 === 1;
	        }

	        exports.odd = odd;
	        /**
	         * Returns `true` if the value is a string, `false` if not.
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function string(value) {
	          return typeof value === 'string';
	        }

	        exports.string = string;
	        /**
	         * Returns `true` if the value is not in the list of things considered falsy:
	         * '', null, undefined, 0, NaN and false.
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function truthy(value) {
	          return !!value;
	        }

	        exports.truthy = truthy;
	        /**
	         * Returns `true` if the value is undefined.
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function undefinedTest(value) {
	          return value === undefined;
	        }

	        exports.undefined = undefinedTest;
	        /**
	         * Returns `true` if the string is uppercased.
	         * @param { string } value
	         * @returns { boolean }
	         */

	        function upper(value) {
	          return value.toUpperCase() === value;
	        }

	        exports.upper = upper;
	        /**
	         * If ES6 features are available, returns `true` if the value implements the
	         * `Symbol.iterator` method. If not, it's a string or Array.
	         *
	         * Could potentially cause issues if a browser exists that has Set and Map but
	         * not Symbol.
	         *
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function iterable(value) {
	          if (typeof Symbol !== 'undefined') {
	            return !!value[Symbol.iterator];
	          } else {
	            return Array.isArray(value) || typeof value === 'string';
	          }
	        }

	        exports.iterable = iterable;
	        /**
	         * If ES6 features are available, returns `true` if the value is an object hash
	         * or an ES6 Map. Otherwise just return if it's an object hash.
	         * @param { any } value
	         * @returns { boolean }
	         */

	        function mapping(value) {
	          // only maps and object hashes
	          var bool = value !== null && value !== undefined && _typeof_1(value) === 'object' && !Array.isArray(value);

	          if (Set) {
	            return bool && !(value instanceof Set);
	          } else {
	            return bool;
	          }
	        }

	        exports.mapping = mapping;
	        /***/
	      },
	      /* 15 */

	      /***/
	      function (module, exports, __webpack_require__) {

	        function _cycler(items) {
	          var index = -1;
	          return {
	            current: null,
	            reset: function reset() {
	              index = -1;
	              this.current = null;
	            },
	            next: function next() {
	              index++;

	              if (index >= items.length) {
	                index = 0;
	              }

	              this.current = items[index];
	              return this.current;
	            }
	          };
	        }

	        function _joiner(sep) {
	          sep = sep || ',';
	          var first = true;
	          return function () {
	            var val = first ? '' : sep;
	            first = false;
	            return val;
	          };
	        } // Making this a function instead so it returns a new object
	        // each time it's called. That way, if something like an environment
	        // uses it, they will each have their own copy.


	        function globals() {
	          return {
	            range: function range(start, stop, step) {
	              if (typeof stop === 'undefined') {
	                stop = start;
	                start = 0;
	                step = 1;
	              } else if (!step) {
	                step = 1;
	              }

	              var arr = [];

	              if (step > 0) {
	                for (var i = start; i < stop; i += step) {
	                  arr.push(i);
	                }
	              } else {
	                for (var _i = start; _i > stop; _i += step) {
	                  // eslint-disable-line for-direction
	                  arr.push(_i);
	                }
	              }

	              return arr;
	            },
	            cycler: function cycler() {
	              return _cycler(Array.prototype.slice.call(arguments));
	            },
	            joiner: function joiner(sep) {
	              return _joiner(sep);
	            }
	          };
	        }

	        module.exports = globals;
	        /***/
	      },
	      /* 16 */

	      /***/
	      function (module, exports, __webpack_require__) {
	        var path = __webpack_require__(0);

	        module.exports = function express(env, app) {
	          function NunjucksView(name, opts) {
	            this.name = name;
	            this.path = name;
	            this.defaultEngine = opts.defaultEngine;
	            this.ext = path.extname(name);

	            if (!this.ext && !this.defaultEngine) {
	              throw new Error('No default engine was specified and no extension was provided.');
	            }

	            if (!this.ext) {
	              this.name += this.ext = (this.defaultEngine[0] !== '.' ? '.' : '') + this.defaultEngine;
	            }
	          }

	          NunjucksView.prototype.render = function render(opts, cb) {
	            env.render(this.name, opts, cb);
	          };

	          app.set('view', NunjucksView);
	          app.set('nunjucksEnv', env);
	          return env;
	        };
	        /***/

	      },
	      /* 17 */

	      /***/
	      function (module, exports, __webpack_require__) {
	        function installCompat() {
	          /* eslint-disable camelcase */
	          // This must be called like `nunjucks.installCompat` so that `this`
	          // references the nunjucks instance

	          var runtime = this.runtime;
	          var lib = this.lib; // Handle slim case where these 'modules' are excluded from the built source

	          var Compiler = this.compiler.Compiler;
	          var Parser = this.parser.Parser;
	          var nodes = this.nodes;
	          var lexer = this.lexer;
	          var orig_contextOrFrameLookup = runtime.contextOrFrameLookup;
	          var orig_memberLookup = runtime.memberLookup;
	          var orig_Compiler_assertType;
	          var orig_Parser_parseAggregate;

	          if (Compiler) {
	            orig_Compiler_assertType = Compiler.prototype.assertType;
	          }

	          if (Parser) {
	            orig_Parser_parseAggregate = Parser.prototype.parseAggregate;
	          }

	          function uninstall() {
	            runtime.contextOrFrameLookup = orig_contextOrFrameLookup;
	            runtime.memberLookup = orig_memberLookup;

	            if (Compiler) {
	              Compiler.prototype.assertType = orig_Compiler_assertType;
	            }

	            if (Parser) {
	              Parser.prototype.parseAggregate = orig_Parser_parseAggregate;
	            }
	          }

	          runtime.contextOrFrameLookup = function contextOrFrameLookup(context, frame, key) {
	            var val = orig_contextOrFrameLookup.apply(this, arguments);

	            if (val !== undefined) {
	              return val;
	            }

	            switch (key) {
	              case 'True':
	                return true;

	              case 'False':
	                return false;

	              case 'None':
	                return null;

	              default:
	                return undefined;
	            }
	          };

	          function sliceLookup(obj, start, stop, step) {
	            obj = obj || [];

	            if (start === null) {
	              start = step < 0 ? obj.length - 1 : 0;
	            }

	            if (stop === null) {
	              stop = step < 0 ? -1 : obj.length;
	            } else if (stop < 0) {
	              stop += obj.length;
	            }

	            if (start < 0) {
	              start += obj.length;
	            }

	            var results = [];

	            for (var i = start;; i += step) {
	              if (i < 0 || i > obj.length) {
	                break;
	              }

	              if (step > 0 && i >= stop) {
	                break;
	              }

	              if (step < 0 && i <= stop) {
	                break;
	              }

	              results.push(runtime.memberLookup(obj, i));
	            }

	            return results;
	          }

	          function hasOwnProp(obj, key) {
	            return Object.prototype.hasOwnProperty.call(obj, key);
	          }

	          var ARRAY_MEMBERS = {
	            pop: function pop(index) {
	              if (index === undefined) {
	                return this.pop();
	              }

	              if (index >= this.length || index < 0) {
	                throw new Error('KeyError');
	              }

	              return this.splice(index, 1);
	            },
	            append: function append(element) {
	              return this.push(element);
	            },
	            remove: function remove(element) {
	              for (var i = 0; i < this.length; i++) {
	                if (this[i] === element) {
	                  return this.splice(i, 1);
	                }
	              }

	              throw new Error('ValueError');
	            },
	            count: function count(element) {
	              var count = 0;

	              for (var i = 0; i < this.length; i++) {
	                if (this[i] === element) {
	                  count++;
	                }
	              }

	              return count;
	            },
	            index: function index(element) {
	              var i;

	              if ((i = this.indexOf(element)) === -1) {
	                throw new Error('ValueError');
	              }

	              return i;
	            },
	            find: function find(element) {
	              return this.indexOf(element);
	            },
	            insert: function insert(index, elem) {
	              return this.splice(index, 0, elem);
	            }
	          };
	          var OBJECT_MEMBERS = {
	            items: function items() {
	              return lib._entries(this);
	            },
	            values: function values() {
	              return lib._values(this);
	            },
	            keys: function keys() {
	              return lib.keys(this);
	            },
	            get: function get(key, def) {
	              var output = this[key];

	              if (output === undefined) {
	                output = def;
	              }

	              return output;
	            },
	            has_key: function has_key(key) {
	              return hasOwnProp(this, key);
	            },
	            pop: function pop(key, def) {
	              var output = this[key];

	              if (output === undefined && def !== undefined) {
	                output = def;
	              } else if (output === undefined) {
	                throw new Error('KeyError');
	              } else {
	                delete this[key];
	              }

	              return output;
	            },
	            popitem: function popitem() {
	              var keys = lib.keys(this);

	              if (!keys.length) {
	                throw new Error('KeyError');
	              }

	              var k = keys[0];
	              var val = this[k];
	              delete this[k];
	              return [k, val];
	            },
	            setdefault: function setdefault(key, def) {
	              if (def === void 0) {
	                def = null;
	              }

	              if (!(key in this)) {
	                this[key] = def;
	              }

	              return this[key];
	            },
	            update: function update(kwargs) {
	              lib._assign(this, kwargs);

	              return null; // Always returns None
	            }
	          };
	          OBJECT_MEMBERS.iteritems = OBJECT_MEMBERS.items;
	          OBJECT_MEMBERS.itervalues = OBJECT_MEMBERS.values;
	          OBJECT_MEMBERS.iterkeys = OBJECT_MEMBERS.keys;

	          runtime.memberLookup = function memberLookup(obj, val, autoescape) {
	            if (arguments.length === 4) {
	              return sliceLookup.apply(this, arguments);
	            }

	            obj = obj || {}; // If the object is an object, return any of the methods that Python would
	            // otherwise provide.

	            if (lib.isArray(obj) && hasOwnProp(ARRAY_MEMBERS, val)) {
	              return ARRAY_MEMBERS[val].bind(obj);
	            }

	            if (lib.isObject(obj) && hasOwnProp(OBJECT_MEMBERS, val)) {
	              return OBJECT_MEMBERS[val].bind(obj);
	            }

	            return orig_memberLookup.apply(this, arguments);
	          };

	          return uninstall;
	        }

	        module.exports = installCompat;
	        /***/
	      }
	      /******/
	      ])
	    );
	  });
	});
	var nunjucks = /*@__PURE__*/getDefaultExportFromCjs(nunjucksSlim);

	/**
	 * Nunjucks {% spaceless %} extension
	 * from `vf-core`
	 */
	function SpacelessExtension() {
	  this.tags = ['spaceless'];

	  this.parse = function (parser, nodes, lexer) {
	    var tok = parser.nextToken();
	    var args = parser.parseSignature(null, true);
	    parser.advanceAfterBlockEnd(tok.value);
	    var body = parser.parseUntilBlocks('error', 'endspaceless');
	    var errorBody = null;

	    if (parser.skipSymbol('error')) {
	      parser.skip(lexer.TOKEN_BLOCK_END);
	      errorBody = parser.parseUntilBlocks('endremote');
	    }

	    parser.advanceAfterBlockEnd();
	    return new nodes.CallExtension(this, 'run', args, [body, errorBody]);
	  };

	  this.run = function (context, body) {
	    return body().replace(/\s+/g, ' ').replace(/>\s</g, '><');
	  };
	} // export default SpacelessExtension;


	var nunjucksSpaceless = SpacelessExtension;

	/**
	 * Return Nunjucks environment object
	 */

	var useNunjucks = function useNunjucks() {
	  // initialise on first request to ensure precompiled templates exist
	  if (!window.vfNunjucks) {
	    var env = new nunjucks.Environment(null, {
	      lstripBlocks: true,
	      trimBlocks: true,
	      autoescape: false
	    });
	    env.addExtension('spaceless', new nunjucksSpaceless());
	    window.vfNunjucks = env;
	  }

	  return window.vfNunjucks;
	};

	function ownKeys$2(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$1(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$2(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$2(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	// import useVFRenderTemplate from './use-vf-render-template';

	var useVFRenderTemplate = function useVFRenderTemplate(name, attrs) {
	  try {
	    var nunjucks = useNunjucks();
	    var html = nunjucks.render(name.replace(/^vf\//, 'vf-'), attrs);
	    return {
	      html: html,
	      hash: useHashsum(html)
	    };
	  } catch (err) {
	    console.log(err);
	    return null;
	  }
	};

	var renderStore = {};

	var useVFRender = function useVFRender(props) {
	  var _useState = React.useState(null),
	      _useState2 = slicedToArray(_useState, 2),
	      data = _useState2[0],
	      setData = _useState2[1];

	  var _useState3 = React.useState(false),
	      _useState4 = slicedToArray(_useState3, 2),
	      isLoading = _useState4[0],
	      setLoading = _useState4[1];

	  if (props.isRenderable === false) {
	    return [data, false];
	  }

	  var hasTemplate = ('render' in props.attributes); // extract attributes and remove protected

	  var renderAttrs = _objectSpread$1(_objectSpread$1({}, props.attributes), props.transient || {});

	  delete renderAttrs['ver'];
	  delete renderAttrs['mode'];
	  delete renderAttrs['render'];
	  var renderHash = useHashsum([props.name, renderAttrs]);

	  var fetchData = /*#__PURE__*/function () {
	    var _ref = asyncToGenerator( /*#__PURE__*/regenerator.mark(function _callee() {
	      var newData;
	      return regenerator.wrap(function _callee$(_context) {
	        while (1) {
	          switch (_context.prev = _context.next) {
	            case 0:
	              if (!renderStore.hasOwnProperty(renderHash)) {
	                _context.next = 3;
	                break;
	              }

	              setData(renderStore[renderHash]);
	              return _context.abrupt("return");

	            case 3:
	              newData = null;

	              if (!hasTemplate) {
	                _context.next = 8;
	                break;
	              }

	              newData = useVFRenderTemplate(props.name, renderAttrs);
	              _context.next = 9;
	              break;

	            case 8:
	              return _context.abrupt("return");

	            case 9:
	              renderStore[renderHash] = newData;
	              setData(newData);

	            case 11:
	            case "end":
	              return _context.stop();
	          }
	        }
	      }, _callee);
	    }));

	    return function fetchData() {
	      return _ref.apply(this, arguments);
	    };
	  }(); // provide attributes hash to avoid rerenders


	  React.useEffect(function () {
	    fetchData();
	  }, [renderHash, props.isEditing]);

	  if (data && hasTemplate) {
	    props.setAttributes({
	      render: data.html
	    });
	  }

	  return [data, isLoading];
	};

	/**
	 * VFBlockControls (component)
	 * Toolbar to toggle between "edit" and "view" modes.
	 */

	var EditButton = function EditButton(_ref) {
	  var onClick = _ref.onClick;
	  return wp.element.createElement(components.Button, {
	    label: i18n.__('Edit'),
	    icon: "edit",
	    onClick: onClick
	  });
	}; // The togglable "View" button added to `BlockControls`


	var ViewButton = function ViewButton(_ref2) {
	  var onClick = _ref2.onClick;
	  return wp.element.createElement(components.Button, {
	    label: i18n.__('Preview'),
	    icon: "visibility",
	    onClick: onClick
	  });
	};

	var VFBlockControls = function VFBlockControls(props) {
	  var isEditing = props.isEditing,
	      onToggle = props.onToggle;
	  return wp.element.createElement(blockEditor.BlockControls, null, wp.element.createElement(components.Toolbar, null, isEditing ? wp.element.createElement(ViewButton, {
	    onClick: onToggle
	  }) : wp.element.createElement(EditButton, {
	    onClick: onToggle
	  })));
	};

	/**
	 * BlockEdit (component)
	 * In "edit" mode render the editing controls. This component is sparse
	 * because blocks will provide their own children, or use `VFBlockFields`
	 * to automatically generate controls.
	 */

	var UpdateButton = function UpdateButton(_ref) {
	  var onClick = _ref.onClick;
	  return wp.element.createElement(components.Button, {
	    isSecondary: true,
	    onClick: onClick
	  }, i18n.__('Preview'));
	};

	var VFBlockEdit = function VFBlockEdit(props) {
	  var onToggle = props.onToggle;
	  return wp.element.createElement("div", {
	    className: "vf-block__edit"
	  }, props.children, onToggle && wp.element.createElement(UpdateButton, {
	    onClick: onToggle
	  }));
	};

	/**
	 * Return `onLoad` and `onUnload` functions for an iframe.
	 * Adjust iframe height automatically whilst mounted.
	 */
	var useVFIFrame = function useVFIFrame(iframe, iframeHTML, onHeight) {
	  // TOTO: handle by global in `vf-blocks.jsx` - is onHeight necessary?
	  // update iframe height from `postMessage` event
	  var onMessage = function onMessage(_ref) {
	    var data = _ref.data;

	    if (data !== Object(data) || data.id !== iframe.id) {
	      return;
	    }

	    window.requestAnimationFrame(function () {
	      // TODO: now handled by global
	      // iframe.style.height = `${data.height}px`;
	      onHeight(data.height);
	    });
	  };

	  var onLoad = function onLoad() {
	    if (!iframe.vfActive) {
	      window.addEventListener('message', onMessage);
	    }

	    iframe.vfActive = true; // set HTML content for block

	    var body = iframe.contentWindow.document.body;

	    if (iframeHTML) {
	      body.innerHTML = iframeHTML;
	    } // create and append script to handle automatic iframe resize
	    // this cannot be inline of `html` for browser security


	    var script = document.createElement('script');
	    script.type = 'text/javascript';
	    script.innerHTML = "\nif (ResizeObserver) {\n  const observer = new ResizeObserver(entries => {\n    entries.forEach(entry => {\n      window.parent.postMessage({\n          id: '".concat(iframe.id, "',\n          height: entry.contentRect.height\n        }, '*'\n      );\n    });\n  });\n  observer.observe(document.body);\n} else {\n  const vfResize = () => {\n    window.parent.postMessage({\n        id: '").concat(iframe.id, "',\n        height: document.documentElement.scrollHeight\n      }, '*'\n    );\n  };\n  window.addEventListener('resize', vfResize);\n  setTimeout(vfResize, 100);\n  vfResize();\n}\n    ");
	    body.appendChild(script);
	  }; // cleanup function for dismount


	  var onUnload = function onUnload() {
	    window.removeEventListener('message', onMessage);
	    iframe.vfActive = false;
	  };

	  return {
	    onLoad: onLoad,
	    onUnload: onUnload
	  };
	};

	var VFBlockView = function VFBlockView(_ref) {
	  var render = _ref.render,
	      uniqueId = _ref.uniqueId,
	      onHeight = _ref.onHeight;

	  var _useVFGutenberg = useVFGutenberg(),
	      renderPrefix = _useVFGutenberg.renderPrefix,
	      renderSuffix = _useVFGutenberg.renderSuffix; // Use existing iframe appended to the DOM


	  var iframeId = "vfwp_".concat(uniqueId);
	  var iframeHTML = render.html ? "".concat(renderPrefix).concat(render.html).concat(renderSuffix) : '';
	  var iframe = document.getElementById(iframeId); // or create a new iframe element

	  if (!iframe) {
	    iframe = document.createElement('iframe');
	    iframe.id = iframeId;
	    iframe.className = 'vf-block__iframe';
	    iframe.setAttribute('scrolling', 'no');

	    if (render.src) {
	      iframe.src = render.src;
	    }
	  } // Use an asynchronous hook to fetch the iframe content via WordPress API


	  var rootEl = React.useRef();

	  var _useVFIFrame = useVFIFrame(iframe, iframeHTML, onHeight),
	      onLoad = _useVFIFrame.onLoad,
	      onUnload = _useVFIFrame.onUnload; // Append the iframe element on mount - we cannot use `<iframe onLoad={} />`
	  // in React, this does not fire properly in Webkit browsers for
	  // iframe elements when `src` is empty


	  React.useEffect(function () {
	    iframe.addEventListener('load', function (ev) {
	      return onLoad(ev);
	    });
	    rootEl.current.appendChild(iframe); // Cleanup after dismount

	    return function () {
	      onUnload();
	    };
	  }, [render.hash]);
	  return wp.element.createElement("div", {
	    className: "vf-block__view",
	    ref: rootEl
	  });
	}; // Memoize to avoid unnecessary heavy updates


	var VFBlockView$1 = /*#__PURE__*/React__default['default'].memo(VFBlockView);

	var VFBlock = function VFBlock(props) {
	  var isEditing = props.isEditing,
	      isEditable = props.isEditable,
	      isPlugin = props.isPlugin,
	      isRenderable = props.isRenderable,
	      isSelected = props.isSelected;
	  var uniqueId = useUniqueId(props);

	  var _useVFRender = useVFRender(props),
	      _useVFRender2 = slicedToArray(_useVFRender, 2),
	      render = _useVFRender2[0],
	      isLoading = _useVFRender2[1];

	  var _useState = React.useState(100),
	      _useState2 = slicedToArray(_useState, 2),
	      minHeight = _useState2[0],
	      setMinHeight = _useState2[1]; // ensure version is encoded in post content


	  if (!props.attributes.ver) {
	    var newAttr = {
	      ver: props.ver || '1.0.0'
	    }; // use defaults for VF plugin blocks

	    if (isPlugin) {
	      newAttr.defaults = 1;
	    }

	    props.setAttributes(newAttr);
	  } // callback to toggle block mode


	  var onToggle = function onToggle() {
	    props.setAttributes({
	      mode: isEditing ? 'view' : 'edit'
	    });
	  }; // show block controls if both modes exist


	  var hasControls = isEditable && isRenderable; // show "edit" mode when edit state is active

	  var hasEdit = isEditable && isEditing; // show "view" mode when not editing and render is available

	  var hasView = !hasEdit && !isLoading && render; // height change callback

	  var onHeight = function onHeight(height) {
	    return height !== minHeight && setMinHeight(height);
	  };

	  if (hasEdit) {
	    onHeight(100);
	  } // add DOM attributes for styling


	  var rootAttrs = {
	    className: "vf-block ".concat(props.className),
	    'data-ver': props.attributes.ver,
	    'data-name': props.name,
	    'data-editing': isEditing,
	    'data-loading': isLoading,
	    'data-selected': isSelected,
	    style: {
	      minHeight: "".concat(minHeight, "px")
	    }
	  };
	  return wp.element.createElement(React.Fragment, null, hasControls && wp.element.createElement(VFBlockControls, {
	    isEditing: isEditing,
	    onToggle: onToggle
	  }), wp.element.createElement("div", rootAttrs, hasEdit && wp.element.createElement(VFBlockEdit, {
	    onToggle: isRenderable ? onToggle : null,
	    children: props.children
	  }), hasView && wp.element.createElement(VFBlockView$1, {
	    render: render,
	    uniqueId: uniqueId,
	    onHeight: onHeight
	  }), isLoading && wp.element.createElement(components.Spinner, null)));
	};

	function ownKeys$3(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$2(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$3(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$3(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var useVFCoreSettings = function useVFCoreSettings(settings) {
	  var defaults = useVFDefaults(); // get block settings

	  var attributes = settings.attributes,
	      example = settings.example,
	      fields = settings.fields,
	      styles = settings.styles,
	      allowedBlocks = settings.allowedBlocks; // block options

	  var hasBlocks = Array.isArray(allowedBlocks);
	  var hasFields = !!(Array.isArray(fields) && fields.length);
	  var hasStyles = !!(Array.isArray(styles) && styles.length); // Assume true unless specifically opted out

	  var isRenderable = settings.isRenderable !== false; // Setup block attributes

	  attributes = _objectSpread$2(_objectSpread$2(_objectSpread$2({}, defaults.attributes), attributes || {}), {}, {
	    __isExample: {
	      type: 'integer',
	      default: 0
	    }
	  }); // Setup example attributes

	  example = _objectSpread$2({}, example);
	  example.attributes = _objectSpread$2(_objectSpread$2({}, example.attributes), {}, {
	    mode: 'view',
	    __isExample: 1
	  }); // Enable `render` attribute for Nunjucks template

	  if (isRenderable) {
	    attributes.render = {
	      type: 'string',
	      default: ''
	    };
	  } // Enable `mode` attribute


	  if (hasFields || hasBlocks) {
	    attributes.mode = {
	      type: 'string',
	      default: 'edit'
	    };
	  } // Sort the fields into their locations


	  var blockFields = [];
	  var inspectorFields = [];

	  if (hasFields) {
	    fields.forEach(function (field) {
	      if (field.inspector) {
	        inspectorFields.push(field);
	      } else {
	        blockFields.push(field);
	      }
	    });
	  }

	  var Save = function Save() {
	    return hasBlocks ? wp.element.createElement(React.Fragment, null, wp.element.createElement(blockEditor.InnerBlocks.Content, null)) : null;
	  };

	  var Edit = function Edit(props) {
	    var ver = settings.ver;
	    var isEditable = !!(props.attributes.mode && props.attributes.__isExample !== 1);
	    var isEditing = props.attributes.mode === 'edit';
	    return wp.element.createElement(React.Fragment, null, wp.element.createElement(VFBlock, _extends_1({}, props, {
	      ver: ver,
	      isRenderable: isRenderable,
	      isEditable: isEditable,
	      isEditing: isEditing
	    }), !!blockFields.length && wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	      fields: blockFields
	    })), hasBlocks && wp.element.createElement(blockEditor.InnerBlocks, {
	      allowedBlocks: allowedBlocks
	    })), !!inspectorFields.length && wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	      title: i18n.__('Settings'),
	      initialOpen: false
	    }, wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	      fields: inspectorFields
	    })))));
	  }; // Wrap higher-order components


	  if (Array.isArray(settings.withHOC)) {
	    settings.withHOC.forEach(function (_ref) {
	      var _ref2 = toArray(_ref),
	          HoC = _ref2[0],
	          args = _ref2.slice(1);

	      return Edit = HoC.apply(void 0, [Edit].concat(toConsumableArray(args)));
	    });
	  } // Return the Gutenberg settings


	  return _objectSpread$2(_objectSpread$2({}, defaults), {}, {
	    name: settings.name,
	    title: settings.title,
	    category: 'vf/core',
	    parent: settings.parent || null,
	    description: i18n.__('Visual Framework (core)'),
	    keywords: toConsumableArray(defaults.keywords),
	    attributes: attributes,
	    example: example,
	    styles: hasStyles ? styles : [],
	    supports: _objectSpread$2(_objectSpread$2({}, defaults.supports), {}, {
	      customClassName: hasStyles,
	      inserter: settings.isInsertable !== false
	    }),
	    transforms: _objectSpread$2({}, settings.transforms || null),
	    edit: Edit,
	    save: Save
	  });
	};

	/**
	Block Name: Activity List Item
	*/
	var vfActivityItem = useVFCoreSettings({
	  name: 'vf/activity-item',
	  title: i18n.__('Activity Item'),
	  parent: ['vf/activity-list'],
	  isRenderable: false,
	  attributes: {
	    text: {
	      type: 'string',
	      default: '<strong>Author</strong> published <a href="#">\'Article Title\'</a> on <a href="#">Source</a>.'
	    }
	  },
	  fields: [{
	    name: 'text',
	    control: 'rich',
	    label: '',
	    tag: 'p',
	    placeholder: i18n.__('Type activity…')
	  }]
	});

	function ownKeys$4(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$3(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$4(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$4(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	/**
	 * Wrap block edit function to add transient property
	 * assigned to custom attribute.
	 */

	var withTransientAttribute = function withTransientAttribute(Edit, attr) {
	  return function (props) {
	    return Edit(_objectSpread$3(_objectSpread$3({}, props), {}, {
	      transient: _objectSpread$3(_objectSpread$3({}, props.transient || {}), {}, defineProperty$4({}, attr.key, attr.value || props.attributes[attr.key]))
	    }));
	  };
	};
	/**
	 * Wrap block edit function to add block style as transient property
	 * Optionally use BEM notation
	 */

	var withTransientStyle = function withTransientStyle(Edit, opts) {
	  return function (props) {
	    var isBEM = ('BEM' in opts);
	    var style = useStyleName(props.className);
	    var name = props.name.replace(/^vf\//, 'vf-');
	    var value = isBEM ? "".concat(name, "--").concat(style) : style;

	    if (isBEM && !style) {
	      return Edit(props);
	    }

	    return withTransientAttribute(Edit, {
	      key: opts.key,
	      value: value
	    })(props);
	  };
	};
	/**
	 * Wrap the Gutenberg block settings `edit` function.
	 * Map block attributes to transient ones to support potential compatibility
	 * changes to the Nunjucks template; example:

	  settings.edit = withTransientAttributeMap(settings.edit, [
	    {from: 'text', to: 'vf_lede_text'}
	  ]);

	 */

	var withTransientAttributeMap = function withTransientAttributeMap(Edit, map) {
	  return function (props) {
	    // props.transient = {
	    //   ...(props.transient || {})
	    // };
	    var transient = _objectSpread$3({}, props.transient || {});

	    map.forEach(function (item) {
	      if (props.attributes.hasOwnProperty(item.from)) {
	        transient[item.to] = props.attributes[item.from];
	      }
	    });
	    return Edit(_objectSpread$3(_objectSpread$3({}, props), {}, {
	      transient: transient
	    }));
	  };
	};
	/**
	 * Wrap the Gutenberg block settings `edit` function.
	 * Add `<InnerBlocks.Content />` content as a transient property.
	 */

	var withTransientInnerBlocks = function withTransientInnerBlocks(Edit) {
	  return function (props) {
	    var innerBlocks = data$1.select('core/block-editor').getBlocks(props.clientId);

	    var transient = _objectSpread$3(_objectSpread$3({}, props.transient || {}), {}, {
	      innerBlocks: []
	    });

	    innerBlocks.forEach(function (block) {
	      return transient.innerBlocks.push({
	        name: block.name,
	        attributes: _objectSpread$3({}, block.attributes)
	      });
	    });
	    return Edit(_objectSpread$3(_objectSpread$3({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var fromCore = function fromCore() {
	  return {
	    type: 'block',
	    blocks: ['core/list'],
	    transform: function transform(attributes) {
	      var innerBlocks = []; // Only transform browser-side via DOM to parse HTML in `value` attribute

	      if ((typeof window === "undefined" ? "undefined" : _typeof_1(window)) !== 'object') {
	        return blocks.createBlock('vf/activity-list');
	      }

	      var list = window.document.createElement('ul');
	      list.innerHTML = attributes.values;
	      list.children.forEach(function (el) {
	        if (el.nodeName.toLowerCase() === 'li') {
	          innerBlocks.push(blocks.createBlock('vf/activity-item', {
	            text: el.innerHTML
	          }));
	        }
	      });
	      return blocks.createBlock('vf/activity-list', {}, innerBlocks);
	    }
	  };
	};

	/**
	 * Precompiled Nunjucks template: vf-activity-list.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-activity-list"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	output += "<div class=\"vf-activity\">\n\n  <p class=\"vf-activity__date\">";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "date"), env.opts.autoescape);
	output += "</p>\n\n  <ul class=\"vf-activity__list | vf-list\">\n\n";
	frame = frame.push();
	var t_3 = runtime.contextOrFrameLookup(context, frame, "list");
	if(t_3) {t_3 = runtime.fromIterator(t_3);
	var t_2 = t_3.length;
	for(var t_1=0; t_1 < t_3.length; t_1++) {
	var t_4 = t_3[t_1];
	frame.set("item", t_4);
	frame.set("loop.index", t_1 + 1);
	frame.set("loop.index0", t_1);
	frame.set("loop.revindex", t_2 - t_1);
	frame.set("loop.revindex0", t_2 - t_1 - 1);
	frame.set("loop.first", t_1 === 0);
	frame.set("loop.last", t_1 === t_2 - 1);
	frame.set("loop.length", t_2);
	output += "\n    <li class=\"vf-activity__item\">\n      ";
	output += runtime.suppressValue(t_4, env.opts.autoescape);
	output += "\n    </li>\n\n";
	;
	}
	}
	frame = frame.pop();
	output += "\n  </ul>\n</div>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	function ownKeys$5(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$4(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$5(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$5(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var withActivityItems = function withActivityItems(Edit) {
	  return function (props) {
	    var transient = _objectSpread$4({}, props.transient || {});

	    transient.list = [];

	    if (Array.isArray(transient.innerBlocks)) {
	      transient.innerBlocks.forEach(function (block) {
	        if (block.name === 'vf/activity-item') {
	          transient.list.push(block.attributes.text);
	        }
	      });
	    }

	    return Edit(_objectSpread$4(_objectSpread$4({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var vfActivityList = useVFCoreSettings({
	  name: 'vf/activity-list',
	  title: i18n.__('Activity List'),
	  attributes: {
	    heading: {
	      type: 'string',
	      default: i18n.__('Activity List')
	    }
	  },
	  fields: [{
	    name: 'heading',
	    control: 'text',
	    label: i18n.__('Heading')
	  }],
	  allowedBlocks: ['vf/activity-item'],
	  transforms: {
	    from: [fromCore()]
	  },
	  withHOC: [[withTransientAttributeMap, [{
	    from: 'heading',
	    to: 'date'
	  }]], [withActivityItems], [withTransientInnerBlocks]]
	});

	/**
	 * Precompiled Nunjucks template: vf-badge.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-badge"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	if(runtime.contextOrFrameLookup(context, frame, "context")) {
	output += "\n";
	var t_1;
	t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"badge_href");
	frame.set("badge_href", t_1, true);
	if(frame.topLevel) {
	context.setVariable("badge_href", t_1);
	}
	if(frame.topLevel) {
	context.addExport("badge_href", t_1);
	}
	var t_2;
	t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
	frame.set("theme", t_2, true);
	if(frame.topLevel) {
	context.setVariable("theme", t_2);
	}
	if(frame.topLevel) {
	context.addExport("theme", t_2);
	}
	var t_3;
	t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
	frame.set("text", t_3, true);
	if(frame.topLevel) {
	context.setVariable("text", t_3);
	}
	if(frame.topLevel) {
	context.addExport("text", t_3);
	}
	var t_4;
	t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"style");
	frame.set("style", t_4, true);
	if(frame.topLevel) {
	context.setVariable("style", t_4);
	}
	if(frame.topLevel) {
	context.addExport("style", t_4);
	}
	var t_5;
	t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"html");
	frame.set("html", t_5, true);
	if(frame.topLevel) {
	context.setVariable("html", t_5);
	}
	if(frame.topLevel) {
	context.addExport("html", t_5);
	}
	output += "\n";
	;
	}
	output += runtime.suppressValue(env.getExtension("spaceless")["run"](context,function(cb) {
	if(!cb) { cb = function(err) { if(err) { throw err; }};}
	var t_6 = "";t_6 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "badge_href")) {
	var t_7;
	t_7 = "a";
	frame.set("tags", t_7, true);
	if(frame.topLevel) {
	context.setVariable("tags", t_7);
	}
	if(frame.topLevel) {
	context.addExport("tags", t_7);
	}
	;
	}
	else {
	var t_8;
	t_8 = "span";
	frame.set("tags", t_8, true);
	if(frame.topLevel) {
	context.setVariable("tags", t_8);
	}
	if(frame.topLevel) {
	context.addExport("tags", t_8);
	}
	;
	}
	t_6 += "\n";
	t_6 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "theme")) {
	var t_9;
	t_9 = runtime.contextOrFrameLookup(context, frame, "theme");
	frame.set("theme_class", t_9, true);
	if(frame.topLevel) {
	context.setVariable("theme_class", t_9);
	}
	if(frame.topLevel) {
	context.addExport("theme_class", t_9);
	}
	;
	}
	t_6 += "\n\n";
	t_6 += "\n<";
	t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
	t_6 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
	t_6 += " href=\"";
	t_6 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "badge_href")?runtime.contextOrFrameLookup(context, frame, "badge_href"):"#"), env.opts.autoescape);
	t_6 += "\"";
	;
	}
	t_6 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "id")) {
	t_6 += " id=\"";
	t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
	t_6 += "\"";
	;
	}
	t_6 += "\nclass=\"vf-badge";
	if(runtime.contextOrFrameLookup(context, frame, "theme_class")) {
	t_6 += " vf-badge--";
	t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme_class"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "style")) {
	frame = frame.push();
	var t_12 = runtime.contextOrFrameLookup(context, frame, "style");
	if(t_12) {t_12 = runtime.fromIterator(t_12);
	var t_11 = t_12.length;
	for(var t_10=0; t_10 < t_12.length; t_10++) {
	var t_13 = t_12[t_10];
	frame.set("styles", t_13);
	frame.set("loop.index", t_10 + 1);
	frame.set("loop.index0", t_10);
	frame.set("loop.revindex", t_11 - t_10);
	frame.set("loop.revindex0", t_11 - t_10 - 1);
	frame.set("loop.first", t_10 === 0);
	frame.set("loop.last", t_10 === t_11 - 1);
	frame.set("loop.length", t_11);
	t_6 += " vf-badge--";
	t_6 += runtime.suppressValue(t_13, env.opts.autoescape);
	;
	}
	}
	frame = frame.pop();
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
	t_6 += " | ";
	t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
	;
	}
	t_6 += "\">";
	t_6 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "html")?env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "html")):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
	t_6 += "</";
	t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
	t_6 += ">\n";
	cb(null, t_6);
	;
	return t_6;
	}
	,null), true && env.opts.autoescape);
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	function ownKeys$6(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$5(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$6(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$6(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var withBEMModifiers = function withBEMModifiers(Edit) {
	  return function (props) {
	    var transient = _objectSpread$5({}, props.transient || {});

	    transient.style = [];

	    if (props.attributes.outline) {
	      transient.style.push('outline');
	    }

	    if (props.attributes.rounded) {
	      transient.style.push('rounded');
	    }

	    if (props.attributes.pill) {
	      transient.style.push('pill');
	    }

	    return Edit(_objectSpread$5(_objectSpread$5({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var vfBadge = useVFCoreSettings({
	  name: 'vf/badge',
	  title: i18n.__('Badge'),
	  attributes: {
	    text: {
	      type: 'string',
	      default: i18n.__('Badge')
	    },
	    outline: {
	      type: 'integer'
	    },
	    rounded: {
	      type: 'integer'
	    },
	    pill: {
	      type: 'integer'
	    }
	  },
	  fields: [{
	    name: 'text',
	    control: 'text',
	    label: i18n.__('Text')
	  }, {
	    name: 'outline',
	    control: 'true_false',
	    label: i18n.__('Outline'),
	    inspector: true
	  }, {
	    name: 'rounded',
	    control: 'true_false',
	    label: i18n.__('Rounded'),
	    inspector: true
	  }, {
	    name: 'pill',
	    control: 'true_false',
	    label: i18n.__('Pill'),
	    inspector: true
	  }],
	  styles: [{
	    name: 'default',
	    label: i18n.__('Default'),
	    isDefault: true
	  }, {
	    name: 'primary',
	    label: i18n.__('Primary')
	  }, {
	    name: 'secondary',
	    label: i18n.__('Secondary')
	  }, {
	    name: 'tertiary',
	    label: i18n.__('Tertiary')
	  }],
	  withHOC: [[withBEMModifiers], [withTransientStyle, {
	    key: 'theme_class'
	  }]]
	});

	/**
	 * Block transforms for: `vf/blockquote`
	 */
	var fromParagraph = function fromParagraph() {
	  return {
	    type: 'block',
	    blocks: ['core/paragraph'],
	    transform: function transform(attributes) {
	      return blocks.createBlock('vf/blockquote', {
	        html: attributes.content
	      });
	    }
	  };
	};
	var fromQuote = function fromQuote() {
	  return {
	    type: 'block',
	    blocks: ['core/quote'],
	    transform: function transform(attributes) {
	      var citation = attributes.citation,
	          value = attributes.value;

	      if (/^\s*$/.test(citation)) {
	        citation = '';
	      }

	      return blocks.createBlock('vf/blockquote', {
	        html: value + citation
	      });
	    }
	  };
	};

	/**
	 * Precompiled Nunjucks template: vf-blockquote.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-blockquote"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	output += "<blockquote\n";
	output += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "id")) {
	output += " id=\"";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
	output += "\"";
	;
	}
	output += "\nclass=\"vf-blockquote";
	if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
	output += " | ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
	;
	}
	output += "\">";
	output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "html")?env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "html")):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
	output += "</blockquote>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	/**
	Block Name: Blockquote
	*/
	var vfBlockquote = useVFCoreSettings({
	  name: 'vf/blockquote',
	  title: i18n.__('Blockquote'),
	  attributes: {
	    html: {
	      type: 'string'
	    }
	  },
	  fields: [{
	    name: 'html',
	    control: 'rich',
	    default: '',
	    label: '',
	    tag: 'p',
	    placeholder: i18n.__('Type blockquote…')
	  }],
	  transforms: {
	    from: [fromParagraph(), fromQuote()]
	  }
	});

	/**
	 * Precompiled Nunjucks template: vf-box.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-box"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	if(runtime.contextOrFrameLookup(context, frame, "context")) {
	var t_1;
	t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"deprecated_text");
	frame.set("deprecated_text", t_1, true);
	if(frame.topLevel) {
	context.setVariable("deprecated_text", t_1);
	}
	if(frame.topLevel) {
	context.addExport("deprecated_text", t_1);
	}
	var t_2;
	t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_href");
	frame.set("box_href", t_2, true);
	if(frame.topLevel) {
	context.setVariable("box_href", t_2);
	}
	if(frame.topLevel) {
	context.addExport("box_href", t_2);
	}
	var t_3;
	t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
	frame.set("id", t_3, true);
	if(frame.topLevel) {
	context.setVariable("id", t_3);
	}
	if(frame.topLevel) {
	context.addExport("id", t_3);
	}
	var t_4;
	t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"variant");
	frame.set("variant", t_4, true);
	if(frame.topLevel) {
	context.setVariable("variant", t_4);
	}
	if(frame.topLevel) {
	context.addExport("variant", t_4);
	}
	var t_5;
	t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
	frame.set("theme", t_5, true);
	if(frame.topLevel) {
	context.setVariable("theme", t_5);
	}
	if(frame.topLevel) {
	context.addExport("theme", t_5);
	}
	var t_6;
	t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_modifier");
	frame.set("box_modifier", t_6, true);
	if(frame.topLevel) {
	context.setVariable("box_modifier", t_6);
	}
	if(frame.topLevel) {
	context.addExport("box_modifier", t_6);
	}
	var t_7;
	t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_spacing");
	frame.set("box_spacing", t_7, true);
	if(frame.topLevel) {
	context.setVariable("box_spacing", t_7);
	}
	if(frame.topLevel) {
	context.addExport("box_spacing", t_7);
	}
	var t_8;
	t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
	frame.set("override_class", t_8, true);
	if(frame.topLevel) {
	context.setVariable("override_class", t_8);
	}
	if(frame.topLevel) {
	context.addExport("override_class", t_8);
	}
	var t_9;
	t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_heading");
	frame.set("box_heading", t_9, true);
	if(frame.topLevel) {
	context.setVariable("box_heading", t_9);
	}
	if(frame.topLevel) {
	context.addExport("box_heading", t_9);
	}
	var t_10;
	t_10 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_text");
	frame.set("box_text", t_10, true);
	if(frame.topLevel) {
	context.setVariable("box_text", t_10);
	}
	if(frame.topLevel) {
	context.addExport("box_text", t_10);
	}
	;
	}
	output += "\n<div";
	if(runtime.contextOrFrameLookup(context, frame, "id")) {
	output += " id=\"";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
	output += "\"";
	;
	}
	output += "\n  class=\"vf-box";
	if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
	output += " vf-box--is-link";
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "box_spacing")) {
	output += " vf-box--";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_spacing"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "theme")) {
	output += " vf-box-theme--";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "variant")) {
	output += " vf-box--";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "variant"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "box_modifier")) {
	output += " ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_modifier"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
	output += " | ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
	;
	}
	output += "\">\n\n  <h3 class=\"vf-box__heading\">";
	if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
	output += "<a class=\"vf-box__link\" href=\"";
	output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "box_href")?runtime.contextOrFrameLookup(context, frame, "box_href"):"#"), env.opts.autoescape);
	output += "\">";
	;
	}
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_heading"), env.opts.autoescape);
	if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
	output += "</a>";
	;
	}
	output += "</h3>\n  <p class=\"vf-box__text\">";
	output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "box_text")), env.opts.autoescape);
	output += "</p>\n</div>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	/**
	Block Name: Box

	DEPRECATED

	Deprecated in favour of the ACF block.
	Block is hidden from the Gutenberg inserter with `isInsertable` setting.

	*/
	var vfBox = useVFCoreSettings({
	  ver: '1.0.2',
	  name: 'vf/box',
	  title: i18n.__('Box'),
	  isInsertable: false,
	  attributes: {
	    box_heading: {
	      type: 'string'
	    },
	    box_text: {
	      type: 'string'
	    }
	  },
	  example: {
	    attributes: {
	      box_heading: i18n.__('Did You Know?'),
	      box_text: i18n.__('Invasive cancer is the leading cause of death in the developed world and the second leading in the developing world.')
	    }
	  },
	  fields: [{
	    name: 'box_heading',
	    control: 'rich',
	    // default: '',
	    label: '',
	    tag: 'h3',
	    placeholder: i18n.__('Type box heading…')
	  }, {
	    name: 'box_text',
	    control: 'rich',
	    // default: '',
	    label: '',
	    tag: 'p',
	    placeholder: i18n.__('Type box content…')
	  }],
	  styles: [{
	    name: 'default',
	    label: i18n.__('Default'),
	    isDefault: true
	  }, {
	    name: 'factoid',
	    label: i18n.__('Factoid')
	  }, {
	    name: 'inlay',
	    label: i18n.__('Inlayed')
	  }],
	  withHOC: [[withTransientStyle, {
	    key: 'box_modifier',
	    BEM: true
	  }]]
	});

	/**
	Block Name: Breadcrumbs Item
	*/
	var vfBreadcrumbsItem = useVFCoreSettings({
	  name: 'vf/breadcrumbs-item',
	  title: i18n.__('Breadcrumbs Item'),
	  parent: ['vf/breadcrumbs'],
	  isRenderable: false,
	  attributes: {
	    text: {
	      type: 'string',
	      default: i18n.__('Breadcrumb')
	    },
	    url: {
	      type: 'string',
	      default: '/'
	    }
	  },
	  fields: [{
	    name: 'text',
	    control: 'text',
	    label: i18n.__('Text')
	  }, {
	    name: 'url',
	    control: 'url',
	    label: i18n.__('URL')
	  }]
	});

	/**
	 * Precompiled Nunjucks template: vf-breadcrumbs.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-breadcrumbs"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	output += "<nav class=\"vf-breadcrumbs\" aria-label=\"Breadcrumb\">\n  <ul class=\"vf-breadcrumbs__list | vf-list vf-list--inline\">\n";
	frame = frame.push();
	var t_3 = runtime.contextOrFrameLookup(context, frame, "breadcrumbs");
	if(t_3) {t_3 = runtime.fromIterator(t_3);
	var t_2 = t_3.length;
	for(var t_1=0; t_1 < t_3.length; t_1++) {
	var t_4 = t_3[t_1];
	frame.set("breadcrumb", t_4);
	frame.set("loop.index", t_1 + 1);
	frame.set("loop.index0", t_1);
	frame.set("loop.revindex", t_2 - t_1);
	frame.set("loop.revindex0", t_2 - t_1 - 1);
	frame.set("loop.first", t_1 === 0);
	frame.set("loop.last", t_1 === t_2 - 1);
	frame.set("loop.length", t_2);
	output += "    <li class=\"vf-breadcrumbs__item\">\n";
	if(runtime.memberLookup((t_4),"breadcrumb_href")) {
	output += "      <a href=\"";
	output += runtime.suppressValue(runtime.memberLookup((t_4),"breadcrumb_href"), env.opts.autoescape);
	output += "\" class=\"vf-breadcrumbs__link\">";
	output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
	output += "</a>\n";
	;
	}
	else {
	output += "      ";
	output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
	output += "\n";
	;
	}
	output += "    </li>\n";
	;
	}
	}
	frame = frame.pop();
	output += "  </ul>\n</nav>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	function ownKeys$7(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$6(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$7(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$7(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var withBreadcrumbsItems = function withBreadcrumbsItems(Edit) {
	  return function (props) {
	    var transient = _objectSpread$6({}, props.transient || {});

	    transient.breadcrumbs = [];

	    if (Array.isArray(transient.innerBlocks)) {
	      transient.innerBlocks.forEach(function (block) {
	        if (block.name === 'vf/breadcrumbs-item') {
	          transient.breadcrumbs.push({
	            text: block.attributes.text,
	            url: block.attributes.url,
	            // Nunjucks template changed from `url`?
	            breadcrumb_href: block.attributes.url
	          });
	        }
	      });
	    }

	    return Edit(_objectSpread$6(_objectSpread$6({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var vfBreadcrumbs = useVFCoreSettings({
	  name: 'vf/breadcrumbs',
	  title: i18n.__('Breadcrumbs'),
	  allowedBlocks: ['vf/breadcrumbs-item'],
	  withHOC: [[withBreadcrumbsItems], [withTransientInnerBlocks]]
	});

	/**
	 * Block transforms for: `vf/button`
	 */
	var fromCore$1 = function fromCore() {
	  return {
	    type: 'block',
	    blocks: ['core/button'],
	    transform: function transform(attributes) {
	      var url = attributes.url,
	          text = attributes.text,
	          className = attributes.className;
	      var outline = /\-outline/.test(className) ? 1 : 0;
	      return blocks.createBlock('vf/button', {
	        text: text,
	        outline: outline,
	        href: url
	      });
	    }
	  };
	};

	/**
	 * Precompiled Nunjucks template: vf-button.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-button"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	if(runtime.contextOrFrameLookup(context, frame, "context")) {
	output += "\n";
	var t_1;
	t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"button_href");
	frame.set("button_href", t_1, true);
	if(frame.topLevel) {
	context.setVariable("button_href", t_1);
	}
	if(frame.topLevel) {
	context.addExport("button_href", t_1);
	}
	var t_2;
	t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
	frame.set("theme", t_2, true);
	if(frame.topLevel) {
	context.setVariable("theme", t_2);
	}
	if(frame.topLevel) {
	context.addExport("theme", t_2);
	}
	var t_3;
	t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
	frame.set("id", t_3, true);
	if(frame.topLevel) {
	context.setVariable("id", t_3);
	}
	if(frame.topLevel) {
	context.addExport("id", t_3);
	}
	var t_4;
	t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
	frame.set("text", t_4, true);
	if(frame.topLevel) {
	context.setVariable("text", t_4);
	}
	if(frame.topLevel) {
	context.addExport("text", t_4);
	}
	var t_5;
	t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"style");
	frame.set("style", t_5, true);
	if(frame.topLevel) {
	context.setVariable("style", t_5);
	}
	if(frame.topLevel) {
	context.addExport("style", t_5);
	}
	var t_6;
	t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"size");
	frame.set("size", t_6, true);
	if(frame.topLevel) {
	context.setVariable("size", t_6);
	}
	if(frame.topLevel) {
	context.addExport("size", t_6);
	}
	var t_7;
	t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
	frame.set("override_class", t_7, true);
	if(frame.topLevel) {
	context.setVariable("override_class", t_7);
	}
	if(frame.topLevel) {
	context.addExport("override_class", t_7);
	}
	var t_8;
	t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"html");
	frame.set("html", t_8, true);
	if(frame.topLevel) {
	context.setVariable("html", t_8);
	}
	if(frame.topLevel) {
	context.addExport("html", t_8);
	}
	var t_9;
	t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
	frame.set("text", t_9, true);
	if(frame.topLevel) {
	context.setVariable("text", t_9);
	}
	if(frame.topLevel) {
	context.addExport("text", t_9);
	}
	output += "\n";
	;
	}
	output += "\n";
	output += runtime.suppressValue(env.getExtension("spaceless")["run"](context,function(cb) {
	if(!cb) { cb = function(err) { if(err) { throw err; }};}
	var t_10 = "";t_10 += "\n";
	if((runtime.contextOrFrameLookup(context, frame, "button_href")) || (runtime.contextOrFrameLookup(context, frame, "href"))) {
	var t_11;
	t_11 = "a";
	frame.set("tags", t_11, true);
	if(frame.topLevel) {
	context.setVariable("tags", t_11);
	}
	if(frame.topLevel) {
	context.addExport("tags", t_11);
	}
	;
	}
	else {
	var t_12;
	t_12 = "button";
	frame.set("tags", t_12, true);
	if(frame.topLevel) {
	context.setVariable("tags", t_12);
	}
	if(frame.topLevel) {
	context.addExport("tags", t_12);
	}
	;
	}
	t_10 += "\n";
	t_10 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "theme")) {
	var t_13;
	t_13 = runtime.contextOrFrameLookup(context, frame, "theme");
	frame.set("theme_class", t_13, true);
	if(frame.topLevel) {
	context.setVariable("theme_class", t_13);
	}
	if(frame.topLevel) {
	context.addExport("theme_class", t_13);
	}
	;
	}
	t_10 += "\n<";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
	t_10 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
	t_10 += " href=\"";
	t_10 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "button_href")) || (runtime.contextOrFrameLookup(context, frame, "href")), env.opts.autoescape);
	t_10 += "\"";
	;
	}
	t_10 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "id")) {
	t_10 += " id=\"";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
	t_10 += "\"";
	;
	}
	t_10 += "\nclass=\"vf-button";
	t_10 += "\n";
	if(runtime.contextOrFrameLookup(context, frame, "theme_class")) {
	t_10 += " vf-button--";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme_class"), env.opts.autoescape);
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "style")) {
	frame = frame.push();
	var t_16 = runtime.contextOrFrameLookup(context, frame, "style");
	if(t_16) {t_16 = runtime.fromIterator(t_16);
	var t_15 = t_16.length;
	for(var t_14=0; t_14 < t_16.length; t_14++) {
	var t_17 = t_16[t_14];
	frame.set("styles", t_17);
	frame.set("loop.index", t_14 + 1);
	frame.set("loop.index0", t_14);
	frame.set("loop.revindex", t_15 - t_14);
	frame.set("loop.revindex0", t_15 - t_14 - 1);
	frame.set("loop.first", t_14 === 0);
	frame.set("loop.last", t_14 === t_15 - 1);
	frame.set("loop.length", t_15);
	t_10 += " vf-button--";
	t_10 += runtime.suppressValue(t_17, env.opts.autoescape);
	;
	}
	}
	frame = frame.pop();
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "size")) {
	t_10 += "  vf-button--";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "size"), env.opts.autoescape);
	t_10 += "\n";
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
	t_10 += " | ";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
	;
	}
	t_10 += "\">";
	t_10 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "html")?env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "html")):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
	t_10 += "</";
	t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
	t_10 += ">\n";
	cb(null, t_10);
	;
	return t_10;
	}
	,null), true && env.opts.autoescape);
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	function ownKeys$8(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$7(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$8(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$8(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

	var withBEMModifiers$1 = function withBEMModifiers(Edit) {
	  return function (props) {
	    var transient = _objectSpread$7({}, props.transient || {});

	    transient.style = [];

	    if (props.attributes.outline) {
	      transient.style.push('outline');
	    }

	    if (props.attributes.rounded) {
	      transient.style.push('rounded');
	    }

	    if (props.attributes.pill) {
	      transient.style.push('pill');
	    }

	    return Edit(_objectSpread$7(_objectSpread$7({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var vfButton = useVFCoreSettings({
	  name: 'vf/button',
	  title: i18n.__('Button'),
	  attributes: {
	    href: {
	      type: 'string',
	      default: '/'
	    },
	    text: {
	      type: 'string',
	      default: i18n.__('Button')
	    },
	    theme: {
	      type: 'string',
	      default: 'primary'
	    },
	    size: {
	      type: 'string',
	      default: ''
	    },
	    outline: {
	      type: 'integer'
	    },
	    rounded: {
	      type: 'integer'
	    },
	    pill: {
	      type: 'integer'
	    }
	  },
	  fields: [{
	    name: 'text',
	    control: 'text',
	    label: i18n.__('Label')
	  }, {
	    name: 'href',
	    control: 'url',
	    label: i18n.__('URL')
	  }, {
	    name: 'size',
	    control: 'select',
	    label: i18n.__('Size'),
	    inspector: true,
	    options: [{
	      label: i18n.__('Small'),
	      value: 'sm'
	    }, {
	      label: i18n.__('Large'),
	      value: 'lg'
	    }]
	  }, {
	    name: 'outline',
	    control: 'true_false',
	    label: i18n.__('Outline'),
	    inspector: true
	  }, {
	    name: 'rounded',
	    control: 'true_false',
	    label: i18n.__('Rounded'),
	    inspector: true
	  }, {
	    name: 'pill',
	    control: 'true_false',
	    label: i18n.__('Pill'),
	    inspector: true
	  }],
	  styles: [{
	    name: 'primary',
	    label: i18n.__('Primary'),
	    isDefault: true
	  }, {
	    name: 'secondary',
	    label: i18n.__('Secondary')
	  }, {
	    name: 'tertiary',
	    label: i18n.__('Tertiary')
	  }],
	  transforms: {
	    from: [fromCore$1()]
	  },
	  withHOC: [[withBEMModifiers$1], [withTransientAttributeMap, [{
	    from: 'href',
	    to: 'button_href'
	  }]], [withTransientStyle, {
	    key: 'theme'
	  }]]
	});

	function ownKeys$9(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$8(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$9(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$9(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults = useVFDefaults();
	var ver = '1.0.0';

	var settings = _objectSpread$8(_objectSpread$8({}, defaults), {}, {
	  name: 'vf/cluster',
	  title: i18n.__('VF Cluster'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  attributes: _objectSpread$8(_objectSpread$8({}, defaults.attributes), {}, {
	    alignment: {
	      type: 'string',
	      default: 'center'
	    },
	    spacing: {
	      type: 'string',
	      default: 'small'
	    },
	    customSpacing: {
	      type: 'number',
	      default: 0
	    }
	  })
	});

	var Cluster = function Cluster(props) {
	  var _props$attributes = props.attributes,
	      alignment = _props$attributes.alignment,
	      spacing = _props$attributes.spacing,
	      customSpacing = _props$attributes.customSpacing;
	  var classes = ['vf-cluster'];

	  if (spacing === 'medium') {
	    classes.push('vf-cluster--600');
	  } else if (spacing === 'large') {
	    classes.push('vf-cluster--800');
	  } else if (spacing !== 'custom') {
	    classes.push('vf-cluster--400');
	  }

	  var styles = {};
	  styles['--vf-cluster__item--flex'] = '25% 1 0';

	  if (spacing === 'custom') {
	    styles['--vf-cluster-margin'] = "".concat(customSpacing, "px");
	  }

	  if (alignment === 'start') {
	    styles['--vf-cluster-alignment'] = 'flex-start';
	  } else if (alignment === 'end') {
	    styles['--vf-cluster-alignment'] = 'flex-end';
	  } else if (alignment === 'stretch') {
	    styles['--vf-cluster-alignment'] = 'stretch';
	  } else {
	    styles['--vf-cluster-alignment'] = 'center';
	  }

	  return wp.element.createElement("div", {
	    "data-ver": props.isEdit ? ver : null,
	    className: classes.join(' '),
	    style: styles
	  }, wp.element.createElement("div", {
	    className: "vf-cluster__inner"
	  }, props.children));
	};

	settings.save = function (props) {
	  return wp.element.createElement(Cluster, props, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
	};

	settings.edit = function (props) {
	  if (ver !== props.attributes.ver) {
	    props.setAttributes({
	      ver: ver
	    });
	  }

	  var clientId = props.clientId;
	  var _props$attributes2 = props.attributes,
	      alignment = _props$attributes2.alignment,
	      spacing = _props$attributes2.spacing;
	  var onSpacing = React.useCallback(function (name, value) {
	    props.setAttributes(defineProperty$4({}, name, value));

	    if (value !== 'custom') {
	      props.setAttributes({
	        customSpacing: 0
	      });
	    }
	  }, [clientId]); // Inspector controls

	  var fields = [{
	    name: 'alignment',
	    label: i18n.__('Alignment'),
	    control: 'select',
	    options: [{
	      label: i18n.__('Stretch'),
	      value: 'stretch'
	    }, {
	      label: i18n.__('Start'),
	      value: 'start'
	    }, {
	      label: i18n.__('Center'),
	      value: 'center'
	    }, {
	      label: i18n.__('End'),
	      value: 'end'
	    }]
	  }, {
	    name: 'spacing',
	    label: i18n.__('Spacing'),
	    control: 'select',
	    options: [{
	      label: i18n.__('Small'),
	      value: 'small'
	    }, {
	      label: i18n.__('Medium'),
	      value: 'medium'
	    }, {
	      label: i18n.__('Large'),
	      value: 'large'
	    }, {
	      label: i18n.__('Custom'),
	      value: 'custom'
	    }],
	    onChange: onSpacing
	  }];

	  if (spacing === 'custom') {
	    fields.push({
	      name: 'customSpacing',
	      label: i18n.__('Custom spacing'),
	      control: 'range',
	      allowReset: true,
	      min: 0,
	      max: 100
	    });
	  } // Return inner blocks and inspector controls


	  return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Settings'),
	    initialOpen: true
	  }, wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	    fields: fields
	  })))), wp.element.createElement(Cluster, _extends_1({}, props, {
	    isEdit: true
	  }), wp.element.createElement(blockEditor.InnerBlocks, {
	    renderAppender: function renderAppender() {
	      return wp.element.createElement(blockEditor.InnerBlocks.ButtonBlockAppender, null);
	    }
	  })));
	};

	/**
	 * Block transforms for: `vf/divider`
	 */
	var fromCore$2 = function fromCore() {
	  return {
	    type: 'block',
	    blocks: ['core/separator'],
	    transform: function transform(attributes) {
	      return blocks.createBlock('vf/divider');
	    }
	  };
	};

	/**
	 * Precompiled Nunjucks template: vf-divider.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-divider"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	if(runtime.contextOrFrameLookup(context, frame, "context")) {
	var t_1;
	t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
	frame.set("id", t_1, true);
	if(frame.topLevel) {
	context.setVariable("id", t_1);
	}
	if(frame.topLevel) {
	context.addExport("id", t_1);
	}
	var t_2;
	t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
	frame.set("override_class", t_2, true);
	if(frame.topLevel) {
	context.setVariable("override_class", t_2);
	}
	if(frame.topLevel) {
	context.addExport("override_class", t_2);
	}
	;
	}
	output += "\n<hr\n";
	if(runtime.contextOrFrameLookup(context, frame, "id")) {
	output += " id=\"";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
	output += "\"";
	;
	}
	output += "class=\"vf-divider";
	if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
	output += " | ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
	;
	}
	output += "\"";
	if(runtime.contextOrFrameLookup(context, frame, "context_margin__inline")) {
	output += " style=\"--context-margin--inline: ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "context_margin__inline"), env.opts.autoescape);
	output += ";\"";
	;
	}
	output += ">\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	/**
	Block Name: Divider
	*/
	var vfDivider = useVFCoreSettings({
	  name: 'vf/divider',
	  title: i18n.__('Divider'),
	  attributes: {},
	  transforms: {
	    from: [fromCore$2()]
	  }
	});

	/**
	 * Precompiled Nunjucks template: vf-embed.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-embed"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	output += "<div\n  class=\"vf-embed";
	if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_16x9") == true) {
	output += " vf-embed--16x9";
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_4x3") == true) {
	output += " vf-embed--4x3";
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
	output += " vf-embed--custom-ratio";
	;
	}
	output += "\"\n\n  style=\"";
	if(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width")) {
	output += "--vf-embed-max-width: ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width"), env.opts.autoescape);
	output += ";\n";
	;
	}
	if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
	output += "    --vf-embed-custom-ratio-x: ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_X"), env.opts.autoescape);
	output += ";\n    --vf-embed-custom-ratio-y: ";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_Y"), env.opts.autoescape);
	output += ";";
	;
	}
	output += "\"\n>";
	output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embedded_content"), env.opts.autoescape);
	output += "</div>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	function ownKeys$a(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$9(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$a(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$a(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var RATIOS = {
	  '2:1': {
	    label: i18n.__('(2:1) Cinema'),
	    ratio: '2:1',
	    width: 640,
	    height: 320
	  },
	  '16:9': {
	    label: i18n.__('(16:9) Widescreen'),
	    ratio: '16:9',
	    width: 640,
	    height: 360
	  },
	  '4:3': {
	    label: i18n.__('(4:3) Standard'),
	    ratio: '4:3',
	    width: 640,
	    height: 480
	  },
	  '1:1': {
	    label: i18n.__('(1:1) Square'),
	    ratio: '1:1',
	    width: 640,
	    height: 640
	  }
	};

	var withRatioAttributes = function withRatioAttributes(Edit) {
	  return function (props) {
	    var transient = _objectSpread$9({}, props.transient || {});

	    var _props$attributes = props.attributes,
	        ratio = _props$attributes.ratio,
	        width = _props$attributes.width,
	        height = _props$attributes.height,
	        maxWidth = _props$attributes.maxWidth;

	    if (ratio && ratio in RATIOS) {
	      width = RATIOS[ratio].width;
	      height = RATIOS[ratio].height;
	      props.setAttributes({
	        width: width,
	        height: height
	      });
	    }

	    if (isNaN(width)) {
	      width = RATIOS['16:9'].width;
	      props.setAttributes({
	        width: width
	      });
	    }

	    if (isNaN(height)) {
	      height = RATIOS['16:9'].height;
	      props.setAttributes({
	        height: height
	      });
	    }

	    transient.vf_embed_variant_custom = true;
	    transient.vf_embed_custom_ratio_X = width;
	    transient.vf_embed_custom_ratio_Y = height;

	    if (maxWidth > 0) {
	      transient.vf_embed_max_width = "".concat(maxWidth, "px");
	    } else {
	      transient.vf_embed_max_width = '100%';
	    }

	    transient.vf_embedded_content = '';

	    if (transient.src) {
	      transient.vf_embedded_content = "<iframe width=\"".concat(width, "\" height=\"").concat(height, "\" src=\"").concat(transient.src, "\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>");
	    }

	    return Edit(_objectSpread$9(_objectSpread$9({}, props), {}, {
	      transient: transient
	    }));
	  };
	};

	var vfEmbed = useVFCoreSettings({
	  name: 'vf/embed',
	  title: i18n.__('Embed'),
	  attributes: {
	    url: {
	      type: 'string'
	    },
	    responsive: {
	      type: 'integer',
	      default: 1
	    },
	    ratio: {
	      type: 'string',
	      default: '16:9'
	    },
	    width: {
	      type: 'integer'
	    },
	    height: {
	      type: 'integer'
	    },
	    maxWidth: {
	      type: 'string',
	      default: 0
	    }
	  },
	  fields: [{
	    name: 'url',
	    control: 'url',
	    label: i18n.__('URL'),
	    disableSuggestions: true
	  }, {
	    name: 'ratio',
	    control: 'select',
	    label: i18n.__('Preset Ratio'),
	    inspector: true,
	    options: Object.keys(RATIOS).map(function (key) {
	      return {
	        label: RATIOS[key].label,
	        value: key
	      };
	    })
	  }, {
	    name: 'width',
	    control: 'number',
	    label: i18n.__('Width'),
	    help: i18n.__('Deselect preset to set a custom width.'),
	    inspector: true,
	    max: 1920,
	    min: 320
	  }, {
	    name: 'height',
	    control: 'number',
	    label: i18n.__('Height'),
	    help: i18n.__('Deselect preset to set a custom height.'),
	    inspector: true,
	    max: 1080,
	    min: 180
	  }, {
	    name: 'maxWidth',
	    control: 'number',
	    label: i18n.__('Maximum Width'),
	    help: i18n.__('Restrict embed resize to this width.'),
	    inspector: true,
	    max: 1920,
	    min: 0
	  }],
	  withHOC: [[withRatioAttributes], [withTransientAttributeMap, [{
	    from: 'url',
	    to: 'src'
	  }]]]
	});

	var floor$2 = Math.floor;

	// `Number.isInteger` method implementation
	// https://tc39.github.io/ecma262/#sec-number.isinteger
	var isInteger = function isInteger(it) {
	  return !isObject(it) && isFinite(it) && floor$2(it) === it;
	};

	// `Number.isInteger` method
	// https://tc39.github.io/ecma262/#sec-number.isinteger
	_export({ target: 'Number', stat: true }, {
	  isInteger: isInteger
	});

	function ownKeys$b(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$a(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$b(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$b(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$1 = useVFDefaults();

	var settings$1 = _objectSpread$a(_objectSpread$a({}, defaults$1), {}, {
	  name: 'vf/grid-column',
	  title: i18n.__('Grid Column'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  parent: ['vf/grid', 'vf/embl-grid'],
	  supports: _objectSpread$a(_objectSpread$a({}, defaults$1.supports), {}, {
	    inserter: false,
	    lightBlockWrapper: true
	  }),
	  attributes: _objectSpread$a(_objectSpread$a({}, defaults$1.attributes), {}, {
	    span: {
	      type: 'integer',
	      default: 1
	    }
	  })
	});

	settings$1.save = function (props) {
	  var span = props.attributes.span;
	  var classes = [];

	  if (Number.isInteger(span) && span > 1) {
	    classes.push("vf-grid__col--span-".concat(span));
	  }

	  var rootAttr = {};

	  if (classes.length) {
	    rootAttr.className = classes.join(' ');
	  }

	  return wp.element.createElement("div", rootAttr, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
	};

	settings$1.edit = function (props) {
	  var clientId = props.clientId;
	  var span = props.attributes.span;

	  var _useDispatch = data$1.useDispatch('core/block-editor'),
	      updateBlockAttributes = _useDispatch.updateBlockAttributes;

	  var _useSelect = data$1.useSelect(function (select) {
	    var _select = select('core/block-editor'),
	        getBlockName = _select.getBlockName,
	        getBlockOrder = _select.getBlockOrder,
	        getBlockRootClientId = _select.getBlockRootClientId;

	    var rootClientId = getBlockRootClientId(clientId);
	    var hasChildBlocks = getBlockOrder(clientId).length > 0;
	    var hasSpanSupport = getBlockName(rootClientId) === 'vf/grid';
	    return {
	      rootClientId: rootClientId,
	      hasChildBlocks: hasChildBlocks,
	      hasSpanSupport: hasSpanSupport
	    };
	  }, [clientId]),
	      hasChildBlocks = _useSelect.hasChildBlocks,
	      hasSpanSupport = _useSelect.hasSpanSupport,
	      rootClientId = _useSelect.rootClientId;

	  React.useEffect(function () {
	    if (!hasSpanSupport && span !== 1) {
	      props.setAttributes({
	        span: 1
	      });
	    }
	  }, [clientId]);
	  var onSpanChange = React.useCallback(function (value) {
	    if (span !== value) {
	      props.setAttributes({
	        span: value
	      });
	      updateBlockAttributes(rootClientId, {
	        dirty: Date.now()
	      });
	    }
	  }, [span, clientId, rootClientId]);
	  var rootAttr = {};
	  var classes = [];

	  if (hasSpanSupport) {
	    if (Number.isInteger(span) && span > 1) {
	      classes.push("vf-grid__col--span-".concat(span));
	    }
	  }

	  if (classes.length) {
	    rootAttr.className = classes.join(' ');
	  }

	  return wp.element.createElement(React__default['default'].Fragment, null, hasSpanSupport && wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Advanced Settings'),
	    initialOpen: true
	  }, wp.element.createElement(components.RangeControl, {
	    label: i18n.__('Column span'),
	    help: i18n.__('Columns may be merged to fit.'),
	    value: Number.isInteger(span) ? span : 1,
	    onChange: onSpanChange,
	    allowReset: true,
	    step: 1,
	    min: 1,
	    max: 6
	  }))), wp.element.createElement(blockEditor.__experimentalBlock.div, rootAttr, wp.element.createElement(blockEditor.InnerBlocks, {
	    templateLock: false,
	    renderAppender: hasChildBlocks ? undefined : function () {
	      return wp.element.createElement(blockEditor.InnerBlocks.ButtonBlockAppender, null);
	    }
	  })));
	};

	function ownKeys$c(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$b(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$c(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$c(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	// New columns are appended to match minimum
	// End columns are merged to match maximum

	var fromColumns = function fromColumns(fromBlock, toBlock, min, max) {
	  return {
	    type: 'block',
	    blocks: [fromBlock],
	    // Match function (ignore initial placeholder state)
	    isMatch: function isMatch(attributes) {
	      return attributes.placeholder !== 1;
	    },
	    // Transform function
	    transform: function transform(attributes, innerBlocks) {
	      // Map column props
	      var innerProps = innerBlocks.map(function (block) {
	        return {
	          attributes: _objectSpread$b({}, block.attributes),
	          innerBlocks: toConsumableArray(block.innerBlocks)
	        };
	      }); // Fill empty props to match min number of columns

	      while (innerProps.length < min) {
	        innerProps.push({});
	      } // Merge end props to match max number of columns


	      while (innerProps.length > max) {
	        var _innerProps$innerBloc;

	        var mergeProps = innerProps.pop();

	        (_innerProps$innerBloc = innerProps[innerProps.length - 1].innerBlocks).push.apply(_innerProps$innerBloc, toConsumableArray(mergeProps.innerBlocks));
	      } // Return new grid block with inner columns


	      return blocks.createBlock(toBlock, {
	        columns: innerProps.length
	      }, innerProps.map(function (props) {
	        return blocks.createBlock('vf/grid-column', props.attributes || {}, props.innerBlocks || []);
	      }));
	    }
	  };
	};

	function ownKeys$d(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$c(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$d(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$d(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$2 = useVFDefaults();
	var ver$1 = '1.1.0';
	var MIN_COLUMNS = 2;
	var MAX_COLUMNS = 4;

	var settings$2 = _objectSpread$c(_objectSpread$c({}, defaults$2), {}, {
	  name: 'vf/embl-grid',
	  title: i18n.__('EMBL Grid'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  supports: _objectSpread$c(_objectSpread$c({}, defaults$2.supports), {}, {
	    lightBlockWrapper: true
	  }),
	  attributes: _objectSpread$c(_objectSpread$c({}, defaults$2.attributes), {}, {
	    placeholder: {
	      type: 'integer',
	      default: 0
	    },
	    columns: {
	      type: 'integer',
	      default: 0
	    },
	    sidebar: {
	      type: 'integer',
	      default: 0
	    },
	    centered: {
	      type: 'integer',
	      default: 0
	    }
	  })
	});

	settings$2.save = function (props) {
	  var _props$attributes = props.attributes,
	      placeholder = _props$attributes.placeholder,
	      sidebar = _props$attributes.sidebar,
	      centered = _props$attributes.centered;

	  if (placeholder === 1) {
	    return null;
	  }

	  var className = 'embl-grid';

	  if (!!sidebar) {
	    className = "".concat(className, " embl-grid--has-sidebar");
	  }

	  if (!!centered) {
	    className = "".concat(className, " embl-grid--has-centered-content");
	  }

	  return wp.element.createElement("div", {
	    className: className
	  }, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
	};

	settings$2.edit = function (props) {
	  if (ver$1 !== props.attributes.ver) {
	    props.setAttributes({
	      ver: ver$1
	    });
	  }

	  var clientId = props.clientId;
	  var _props$attributes2 = props.attributes,
	      columns = _props$attributes2.columns,
	      centered = _props$attributes2.centered,
	      sidebar = _props$attributes2.sidebar,
	      placeholder = _props$attributes2.placeholder; // Turn on setup placeholder if no columns are defined

	  React.useEffect(function () {
	    if (columns === 0) {
	      props.setAttributes({
	        placeholder: 1
	      });
	    }
	  }, [clientId]);

	  var _useDispatch = data$1.useDispatch('core/block-editor'),
	      replaceInnerBlocks = _useDispatch.replaceInnerBlocks;

	  var _useSelect = data$1.useSelect(function (select) {
	    var _select = select('core/block-editor'),
	        getBlocks = _select.getBlocks; // Remove columns by merging their inner blocks


	    var removeColumns = function removeColumns(newColumns) {
	      var innerColumns = getBlocks(clientId);
	      var mergeBlocks = [];

	      for (var i = newColumns - 1; i < innerColumns.length; i++) {
	        mergeBlocks.push.apply(mergeBlocks, toConsumableArray(innerColumns[i].innerBlocks));
	      }

	      replaceInnerBlocks(innerColumns[newColumns - 1].clientId, mergeBlocks, false);
	      replaceInnerBlocks(clientId, getBlocks(clientId).slice(0, newColumns), false);
	    }; // Append new columns


	    var addColumns = function addColumns(newColumns) {
	      var innerColumns = getBlocks(clientId);

	      while (innerColumns.length < newColumns) {
	        innerColumns.push(blocks.createBlock('vf/grid-column', {}, []));
	      }

	      replaceInnerBlocks(clientId, innerColumns, false);
	    };

	    var setColumns = function setColumns(newColumns) {
	      var innerColumns = getBlocks(clientId);

	      if (newColumns < innerColumns.length) {
	        removeColumns(newColumns);
	      }

	      if (newColumns > innerColumns.length) {
	        addColumns(newColumns);
	      }

	      props.setAttributes({
	        columns: newColumns,
	        placeholder: 0
	      });

	      if (newColumns !== 3) {
	        props.setAttributes({
	          sidebar: 0,
	          centered: 0
	        });
	      }
	    };

	    return {
	      setColumns: setColumns
	    };
	  }, [clientId]),
	      setColumns = _useSelect.setColumns; // Toggle attribute `onChange` callback


	  var setToggle = React.useCallback(function (name, value) {
	    value = value ? 1 : 0;
	    props.setAttributes(defineProperty$4({
	      sidebar: 0,
	      centered: 0
	    }, name, value));

	    if (value) {
	      setColumns(3);
	    }
	  }); // Setup placeholder fields

	  var fields = [{
	    control: 'columns',
	    min: MIN_COLUMNS,
	    max: MAX_COLUMNS,
	    value: columns,
	    onChange: setColumns
	  }, {
	    label: i18n.__('With Sidebar'),
	    control: 'toggle',
	    name: 'sidebar',
	    onChange: setToggle
	  }, {
	    label: i18n.__('Centered Content'),
	    control: 'toggle',
	    name: 'centered',
	    onChange: setToggle
	  }]; // Return setup placeholder

	  if (placeholder === 1) {
	    return wp.element.createElement(blockEditor.__experimentalBlock.div, {
	      className: "vf-block vf-block--placeholder"
	    }, wp.element.createElement(components.Placeholder, {
	      label: i18n.__('EMBL Grid'),
	      icon: 'admin-generic'
	    }, wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	      fields: fields
	    }))));
	  } // Amend fields for inspector


	  fields[0].help = i18n.__('Content may be reorganised when columns are reduced.');
	  fields[1].help = i18n.__('3 column only.');
	  fields[2].help = fields[1].help;
	  var className = 'embl-grid';

	  if (!!sidebar) {
	    className = "".concat(className, " embl-grid--has-sidebar");
	  }

	  if (!!centered) {
	    className = "".concat(className, " embl-grid--has-centered-content");
	  } // Return inner blocks and inspector controls


	  return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Settings'),
	    initialOpen: true
	  }, wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	    fields: fields
	  })))), wp.element.createElement(blockEditor.__experimentalBlock.div, {
	    className: className,
	    "data-ver": ver$1,
	    "data-embl": true,
	    "data-sidebar": sidebar,
	    "data-centered": centered
	  }, wp.element.createElement(blockEditor.InnerBlocks, {
	    allowedBlocks: ['vf/grid-column'],
	    template: Array(columns).fill(['vf/grid-column']),
	    templateLock: "all"
	  })));
	}; // Block transforms


	settings$2.transforms = {
	  from: [fromColumns('core/columns', 'vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS), fromColumns('vf/grid', 'vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS)]
	};

	function ownKeys$e(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$d(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$e(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$e(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$3 = useVFDefaults();
	var MIN_COLUMNS$1 = 1;
	var MAX_COLUMNS$1 = 6;

	var settings$3 = _objectSpread$d(_objectSpread$d({}, defaults$3), {}, {
	  name: 'vf/grid',
	  title: i18n.__('VF Grid'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  supports: _objectSpread$d(_objectSpread$d({}, defaults$3.supports), {}, {
	    lightBlockWrapper: true
	  }),
	  attributes: _objectSpread$d(_objectSpread$d({}, defaults$3.attributes), {}, {
	    placeholder: {
	      type: 'integer',
	      default: 0
	    },
	    columns: {
	      type: 'integer',
	      default: 0
	    },
	    dirty: {
	      type: 'integer',
	      default: 0
	    }
	  })
	});

	settings$3.save = function (props) {
	  var _props$attributes = props.attributes,
	      columns = _props$attributes.columns,
	      placeholder = _props$attributes.placeholder;

	  if (placeholder === 1) {
	    return null;
	  }

	  var className = "vf-grid | vf-grid__col-".concat(columns);
	  return wp.element.createElement("div", {
	    className: className
	  }, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
	};

	settings$3.edit = function (props) {
	  var clientId = props.clientId;
	  var _props$attributes2 = props.attributes,
	      dirty = _props$attributes2.dirty,
	      columns = _props$attributes2.columns,
	      placeholder = _props$attributes2.placeholder; // Turn on setup placeholder if no columns are defined

	  React.useEffect(function () {
	    if (columns === 0) {
	      props.setAttributes({
	        placeholder: 1
	      });
	    }
	  }, [clientId]);

	  var _useDispatch = data$1.useDispatch('core/block-editor'),
	      replaceInnerBlocks = _useDispatch.replaceInnerBlocks;

	  var _useSelect = data$1.useSelect(function (select) {
	    var _select = select('core/block-editor'),
	        getBlocks = _select.getBlocks,
	        getBlockAttributes = _select.getBlockAttributes; // Return total number of columns accounting for spans


	    var countSpans = function countSpans(blocks) {
	      var count = 0;
	      blocks.forEach(function (block) {
	        var span = block.attributes.span;

	        if (Number.isInteger(span) && span > 0) {
	          count += span;
	        } else {
	          count++;
	        }
	      });
	      return count;
	    }; // Append new columns


	    var addColumns = function addColumns(maxSpans) {
	      var innerColumns = getBlocks(clientId);

	      while (countSpans(innerColumns) < maxSpans) {
	        innerColumns.push(blocks.createBlock('vf/grid-column', {}, []));
	      }

	      replaceInnerBlocks(clientId, innerColumns, false);
	    }; // Remove columns by merging their inner blocks


	    var removeColumns = function removeColumns(maxSpans) {
	      var innerColumns = getBlocks(clientId);
	      var mergeBlocks = [];

	      while (innerColumns.length > 1 && countSpans(innerColumns) > maxSpans) {
	        mergeBlocks = mergeBlocks.concat(innerColumns.pop().innerBlocks);
	      }

	      replaceInnerBlocks(innerColumns[innerColumns.length - 1].clientId, mergeBlocks.concat(innerColumns[innerColumns.length - 1].innerBlocks), false);
	      replaceInnerBlocks(clientId, getBlocks(clientId).slice(0, innerColumns.length), false);
	    };

	    var setColumns = function setColumns(newColumns) {
	      props.setAttributes({
	        columns: newColumns,
	        placeholder: 0
	      });
	      var innerColumns = getBlocks(clientId);
	      var count = countSpans(innerColumns);

	      if (newColumns < count) {
	        removeColumns(newColumns);
	      }

	      if (newColumns > count) {
	        addColumns(newColumns);
	      }
	    };

	    var updateColumns = function updateColumns() {
	      var _getBlockAttributes = getBlockAttributes(clientId),
	          columns = _getBlockAttributes.columns;

	      setColumns(columns);
	      props.setAttributes({
	        dirty: 0
	      });
	    };

	    return {
	      setColumns: setColumns,
	      updateColumns: updateColumns
	    };
	  }, [clientId]),
	      setColumns = _useSelect.setColumns,
	      updateColumns = _useSelect.updateColumns;

	  React.useEffect(function () {
	    if (dirty > 0) {
	      updateColumns();
	    }
	  }, [dirty]);

	  var GridControl = function GridControl(props) {
	    return wp.element.createElement(ColumnsControl, _extends_1({
	      value: columns,
	      min: MIN_COLUMNS$1,
	      max: MAX_COLUMNS$1,
	      onChange: React.useCallback(function (value) {
	        return setColumns(value);
	      })
	    }, props));
	  }; // Return setup placeholder


	  if (placeholder === 1) {
	    return wp.element.createElement(blockEditor.__experimentalBlock.div, {
	      className: "vf-block vf-block--placeholder"
	    }, wp.element.createElement(components.Placeholder, {
	      label: i18n.__('VF Grid'),
	      icon: 'admin-generic'
	    }, wp.element.createElement(GridControl, null)));
	  }

	  var className = "vf-grid | vf-grid__col-".concat(columns);

	  var styles = defineProperty$4({}, '--block-columns', columns); // Return inner blocks and inspector controls


	  return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Advanced Settings'),
	    initialOpen: true
	  }, wp.element.createElement(GridControl, {
	    help: i18n.__('Content may be reorganised when columns are reduced.')
	  }))), wp.element.createElement(blockEditor.__experimentalBlock.div, {
	    className: className,
	    style: styles
	  }, wp.element.createElement(blockEditor.InnerBlocks, {
	    allowedBlocks: ['vf/grid-column'],
	    templateLock: "all"
	  })));
	}; // Block transforms


	settings$3.transforms = {
	  from: [fromColumns('core/columns', 'vf/grid', MIN_COLUMNS$1, MAX_COLUMNS$1), fromColumns('vf/embl-grid', 'vf/grid', MIN_COLUMNS$1, MAX_COLUMNS$1)]
	};

	/**
	 * Block transforms for: `vf/lede`
	 */
	var fromCore$3 = function fromCore() {
	  return {
	    type: 'block',
	    blocks: ['core/heading', 'core/paragraph'],
	    transform: function transform(attributes) {
	      return blocks.createBlock('vf/lede', {
	        text: attributes.content
	      });
	    }
	  };
	};

	/**
	 * Precompiled Nunjucks template: vf-lede.njk
	 */
	(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-lede"] = (function() {
	function root(env, context, frame, runtime, cb) {
	var lineno = 0;
	var colno = 0;
	var output = "";
	try {
	var parentTemplate = null;
	output += "<p class=\"vf-lede\">";
	output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "vf_lede_text")), env.opts.autoescape);
	output += "</p>\n";
	if(parentTemplate) {
	parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
	} else {
	cb(null, output);
	}
	;
	} catch (e) {
	  cb(runtime.handleError(e, lineno, colno));
	}
	}
	return {
	root: root
	};

	})();
	})();

	/**
	Block Name: Lede
	*/
	var vfLede = useVFCoreSettings({
	  name: 'vf/lede',
	  title: i18n.__('Lede'),
	  attributes: {
	    text: {
	      type: 'string'
	    }
	  },
	  fields: [{
	    name: 'text',
	    control: 'rich',
	    label: '',
	    tag: 'h1'
	  }],
	  transforms: {
	    from: [fromCore$3()]
	  },
	  withHOC: [[withTransientAttributeMap, [{
	    from: 'text',
	    to: 'vf_lede_text'
	  }]]]
	});

	function ownKeys$f(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$e(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$f(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$f(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$4 = useVFDefaults();
	var ver$2 = '1.0.0';

	var settings$4 = _objectSpread$e(_objectSpread$e({}, defaults$4), {}, {
	  name: 'vf/tabs-section',
	  title: i18n.__('VF Tab Section'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  parent: ['vf/tabs'],
	  supports: _objectSpread$e(_objectSpread$e({}, defaults$4.supports), {}, {
	    inserter: false
	  }),
	  attributes: _objectSpread$e(_objectSpread$e({}, defaults$4.attributes), {}, {
	    id: {
	      type: 'string',
	      default: ''
	    },
	    label: {
	      type: 'string',
	      default: ''
	    },
	    unlabelled: {
	      type: 'integer',
	      default: 0
	    }
	  })
	});

	settings$4.save = function (props) {
	  var _props$attributes = props.attributes,
	      id = _props$attributes.id,
	      label = _props$attributes.label,
	      unlabelled = _props$attributes.unlabelled;
	  var attr = {
	    className: "vf-tabs__section"
	  };

	  if (id !== '') {
	    attr.id = "vf-tabs__section-".concat(id);
	  }

	  var heading = {};

	  if (unlabelled === 1) {
	    heading.className = 'vf-u-sr-only';
	  }

	  return wp.element.createElement("section", attr, wp.element.createElement("h2", heading, label), wp.element.createElement(blockEditor.InnerBlocks.Content, null));
	};

	settings$4.edit = function (props) {
	  if (ver$2 !== props.attributes.ver) {
	    props.setAttributes({
	      ver: ver$2
	    });
	  }

	  var clientId = props.clientId;
	  var _props$attributes2 = props.attributes,
	      id = _props$attributes2.id,
	      label = _props$attributes2.label,
	      unlabelled = _props$attributes2.unlabelled;

	  var _useDispatch = data$1.useDispatch('core/block-editor'),
	      removeBlock = _useDispatch.removeBlock,
	      updateBlockAttributes = _useDispatch.updateBlockAttributes;

	  var _useSelect = data$1.useSelect(function (select) {
	    var _select = select('core/block-editor'),
	        getBlockOrder = _select.getBlockOrder,
	        getBlockRootClientId = _select.getBlockRootClientId;

	    var rootClientId = getBlockRootClientId(clientId);
	    var parentBlockOrder = getBlockOrder(rootClientId);
	    return {
	      tabOrder: parentBlockOrder.indexOf(clientId) + 1,
	      updateTabs: function updateTabs() {
	        updateBlockAttributes(rootClientId, {
	          dirty: Date.now()
	        });
	      }
	    };
	  }, [clientId]),
	      tabOrder = _useSelect.tabOrder,
	      updateTabs = _useSelect.updateTabs; // Default to the `clientId` for the ID attribute


	  React.useEffect(function () {
	    if (id === '') {
	      props.setAttributes({
	        id: clientId
	      });
	    }
	  }, [id]); // Default to "Tab [N]" for the tab heading

	  React.useEffect(function () {
	    if (label === '') {
	      props.setAttributes({
	        label: i18n.__("Tab ".concat(tabOrder))
	      });
	    }
	  }, [label]); // Flag the parent tabs block as "dirty" if any attributes change

	  React.useEffect(function () {
	    updateTabs();
	  }, [id, label, tabOrder]); // Callback for inspector changes to update attributes
	  // Flags the parent tabs block as "dirty"

	  var onChange = React.useCallback(function (name, value) {
	    if (name === 'id') {
	      value = value.replace(/[\s\./]+/g, '-').replace(/[^\w-]+/g, '').toLowerCase().trim();
	    }

	    props.setAttributes(defineProperty$4({}, name, value));
	  }, [clientId]); // Inspector controls

	  var fields = [{
	    name: 'label',
	    control: 'text',
	    label: i18n.__('Tab Label'),
	    onChange: onChange
	  }, {
	    name: 'unlabelled',
	    control: 'toggle',
	    label: i18n.__('Hide Heading'),
	    onChange: onChange
	  }, {
	    name: 'id',
	    control: 'text',
	    label: i18n.__('Anchor ID'),
	    onChange: onChange
	  }, {
	    control: 'button',
	    label: i18n.__('Delete Tab'),
	    isSecondary: true,
	    isDestructive: true,
	    onClick: function onClick() {
	      removeBlock(clientId, false);
	    }
	  }];
	  return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Settings'),
	    initialOpen: true
	  }, wp.element.createElement(VFBlockFields, _extends_1({}, props, {
	    fields: fields
	  })))), wp.element.createElement("div", {
	    className: "vf-tabs__section"
	  }, unlabelled ? false : wp.element.createElement("h2", null, label), wp.element.createElement(blockEditor.InnerBlocks, null)));
	};

	function ownKeys$g(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$f(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$g(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$g(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$5 = useVFDefaults();
	var ver$3 = '1.0.0';

	var settings$5 = _objectSpread$f(_objectSpread$f({}, defaults$5), {}, {
	  name: 'vf/tabs',
	  title: i18n.__('VF Tabs'),
	  category: 'vf/core',
	  description: i18n.__('Visual Framework (core)'),
	  attributes: _objectSpread$f(_objectSpread$f({}, defaults$5.attributes), {}, {
	    dirty: {
	      type: 'integer',
	      default: 0
	    },
	    tabs: {
	      type: 'array',
	      default: []
	    }
	  })
	});

	settings$5.save = function (props) {
	  return wp.element.createElement("div", {
	    className: "vf-tabs"
	  }, wp.element.createElement("ul", {
	    className: "vf-tabs__list",
	    "data-vf-js-tabs": true
	  }, props.attributes.tabs.map(function (tab, i) {
	    return wp.element.createElement("li", {
	      key: i + tab.id,
	      className: "vf-tabs__item"
	    }, wp.element.createElement("a", {
	      className: "vf-tabs__link",
	      href: "#vf-tabs__section-".concat(tab.id)
	    }, tab.label));
	  })), wp.element.createElement("div", {
	    className: "vf-tabs-content",
	    "data-vf-js-tabs-content": true
	  }, wp.element.createElement(blockEditor.InnerBlocks.Content, null)));
	};

	settings$5.edit = function (props) {
	  if (ver$3 !== props.attributes.ver) {
	    props.setAttributes({
	      ver: ver$3
	    });
	  }

	  var clientId = props.clientId;
	  var _props$attributes = props.attributes,
	      dirty = _props$attributes.dirty,
	      tabs = _props$attributes.tabs;

	  var _useDispatch = data$1.useDispatch('core/block-editor'),
	      replaceInnerBlocks = _useDispatch.replaceInnerBlocks,
	      selectBlock = _useDispatch.selectBlock;

	  var _useSelect = data$1.useSelect(function (select) {
	    var _select = select('core/block-editor'),
	        getBlockOrder = _select.getBlockOrder,
	        getBlocks = _select.getBlocks;

	    var getTabs = function getTabs() {
	      return getBlocks(clientId);
	    };

	    var getTabsOrder = function getTabsOrder() {
	      return getBlockOrder(clientId);
	    };

	    var appendTab = function appendTab() {
	      var innerTabs = getTabs();
	      innerTabs.push(blocks.createBlock('vf/tabs-section', {}, []));
	      replaceInnerBlocks(clientId, innerTabs, false);
	      selectBlock(innerTabs.slice(-1)[0].clientId);
	    };

	    var updateTabs = function updateTabs() {
	      var innerTabs = getTabs();
	      var newTabs = [];
	      innerTabs.forEach(function (block) {
	        var _block$attributes = block.attributes,
	            id = _block$attributes.id,
	            label = _block$attributes.label;
	        newTabs.push({
	          id: id,
	          label: label
	        });
	      });
	      props.setAttributes({
	        dirty: 0,
	        tabs: newTabs
	      });
	    };

	    return {
	      appendTab: appendTab,
	      getTabs: getTabs,
	      getTabsOrder: getTabsOrder,
	      updateTabs: updateTabs
	    };
	  }, [clientId]),
	      appendTab = _useSelect.appendTab,
	      getTabs = _useSelect.getTabs,
	      getTabsOrder = _useSelect.getTabsOrder,
	      updateTabs = _useSelect.updateTabs;

	  var tabsOrder = getTabsOrder(); // Callback to switch tabs using the tab list interface

	  var selectTab = React.useCallback(function (index) {
	    if (index < tabsOrder.length) {
	      selectBlock(tabsOrder[index]);
	    }
	  }, [tabsOrder]); // Flag as "dirty" if the tabs and inner blocks do not match

	  React.useEffect(function () {
	    if (dirty === 0) {
	      if (Object.keys(tabs).length !== getTabs().length) {
	        props.setAttributes({
	          dirty: Date.now()
	        });
	      }
	    }
	  }, [getTabs().length]); // Update attributes if the block is flagged as "dirty"

	  React.useEffect(function () {
	    if (dirty > 0) {
	      updateTabs();
	    }
	  }, [dirty]); // Inspector controls

	  var fields = [{
	    control: 'button',
	    label: i18n.__('Add Tab'),
	    isSecondary: true,
	    icon: 'insert',
	    onClick: function onClick() {
	      appendTab();
	    }
	  }]; // Return inner blocks and inspector controls

	  return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
	    title: i18n.__('Settings'),
	    initialOpen: true
	  }, wp.element.createElement(VFBlockFields, {
	    fields: fields
	  }))), wp.element.createElement("div", {
	    className: "vf-tabs",
	    "data-ver": ver$3
	  }, wp.element.createElement("ul", {
	    className: "vf-tabs__list"
	  }, tabs.map(function (tab, i) {
	    return wp.element.createElement("li", {
	      key: i + tab.id,
	      className: "vf-tabs__item"
	    }, wp.element.createElement("a", {
	      className: "vf-tabs__link",
	      onClick: function onClick() {
	        return selectTab(i);
	      }
	    }, tab.label));
	  }), wp.element.createElement("li", {
	    className: "vf-tabs__item"
	  }, wp.element.createElement(components.Button, _extends_1({}, fields[0], {
	    isTertiary: true,
	    isSecondary: false
	  }), wp.element.createElement("span", null, fields[0].label)))), wp.element.createElement(blockEditor.InnerBlocks, {
	    allowedBlocks: ['vf/tabs-section'],
	    template: Array(1).fill(['vf/tabs-section'])
	  })));
	};

	var nativePromiseConstructor = global_1.Promise;

	var getOwnPropertyDescriptor$3 = objectGetOwnPropertyDescriptor.f;

	var macrotask = task.set;


	var MutationObserver = global_1.MutationObserver || global_1.WebKitMutationObserver;
	var process$3 = global_1.process;
	var Promise$1 = global_1.Promise;
	var IS_NODE = classofRaw(process$3) == 'process';
	// Node.js 11 shows ExperimentalWarning on getting `queueMicrotask`
	var queueMicrotaskDescriptor = getOwnPropertyDescriptor$3(global_1, 'queueMicrotask');
	var queueMicrotask = queueMicrotaskDescriptor && queueMicrotaskDescriptor.value;

	var flush, head, last, notify, toggle, node, promise, then;

	// modern engines have queueMicrotask method
	if (!queueMicrotask) {
	  flush = function () {
	    var parent, fn;
	    if (IS_NODE && (parent = process$3.domain)) parent.exit();
	    while (head) {
	      fn = head.fn;
	      head = head.next;
	      try {
	        fn();
	      } catch (error) {
	        if (head) notify();
	        else last = undefined;
	        throw error;
	      }
	    } last = undefined;
	    if (parent) parent.enter();
	  };

	  // Node.js
	  if (IS_NODE) {
	    notify = function () {
	      process$3.nextTick(flush);
	    };
	  // browsers with MutationObserver, except iOS - https://github.com/zloirock/core-js/issues/339
	  } else if (MutationObserver && !engineIsIos) {
	    toggle = true;
	    node = document.createTextNode('');
	    new MutationObserver(flush).observe(node, { characterData: true });
	    notify = function () {
	      node.data = toggle = !toggle;
	    };
	  // environments with maybe non-completely correct, but existent Promise
	  } else if (Promise$1 && Promise$1.resolve) {
	    // Promise.resolve without an argument throws an error in LG WebOS 2
	    promise = Promise$1.resolve(undefined);
	    then = promise.then;
	    notify = function () {
	      then.call(promise, flush);
	    };
	  // for other environments - macrotask based on:
	  // - setImmediate
	  // - MessageChannel
	  // - window.postMessag
	  // - onreadystatechange
	  // - setTimeout
	  } else {
	    notify = function () {
	      // strange IE + webpack dev server bug - use .call(global)
	      macrotask.call(global_1, flush);
	    };
	  }
	}

	var microtask = queueMicrotask || function (fn) {
	  var task = { fn: fn, next: undefined };
	  if (last) last.next = task;
	  if (!head) {
	    head = task;
	    notify();
	  } last = task;
	};

	var PromiseCapability = function (C) {
	  var resolve, reject;
	  this.promise = new C(function ($$resolve, $$reject) {
	    if (resolve !== undefined || reject !== undefined) throw TypeError('Bad Promise constructor');
	    resolve = $$resolve;
	    reject = $$reject;
	  });
	  this.resolve = aFunction$1(resolve);
	  this.reject = aFunction$1(reject);
	};

	// 25.4.1.5 NewPromiseCapability(C)
	var f$7 = function (C) {
	  return new PromiseCapability(C);
	};

	var newPromiseCapability = {
		f: f$7
	};

	var promiseResolve = function (C, x) {
	  anObject(C);
	  if (isObject(x) && x.constructor === C) return x;
	  var promiseCapability = newPromiseCapability.f(C);
	  var resolve = promiseCapability.resolve;
	  resolve(x);
	  return promiseCapability.promise;
	};

	var hostReportErrors = function (a, b) {
	  var console = global_1.console;
	  if (console && console.error) {
	    arguments.length === 1 ? console.error(a) : console.error(a, b);
	  }
	};

	var perform = function (exec) {
	  try {
	    return { error: false, value: exec() };
	  } catch (error) {
	    return { error: true, value: error };
	  }
	};

	var task$1 = task.set;










	var SPECIES$6 = wellKnownSymbol('species');
	var PROMISE = 'Promise';
	var getInternalState$3 = internalState.get;
	var setInternalState$5 = internalState.set;
	var getInternalPromiseState = internalState.getterFor(PROMISE);
	var PromiseConstructor = nativePromiseConstructor;
	var TypeError$1 = global_1.TypeError;
	var document$2 = global_1.document;
	var process$4 = global_1.process;
	var $fetch = getBuiltIn('fetch');
	var newPromiseCapability$1 = newPromiseCapability.f;
	var newGenericPromiseCapability = newPromiseCapability$1;
	var IS_NODE$1 = classofRaw(process$4) == 'process';
	var DISPATCH_EVENT = !!(document$2 && document$2.createEvent && global_1.dispatchEvent);
	var UNHANDLED_REJECTION = 'unhandledrejection';
	var REJECTION_HANDLED = 'rejectionhandled';
	var PENDING = 0;
	var FULFILLED = 1;
	var REJECTED = 2;
	var HANDLED = 1;
	var UNHANDLED = 2;
	var Internal, OwnPromiseCapability, PromiseWrapper, nativeThen;

	var FORCED$5 = isForced_1(PROMISE, function () {
	  var GLOBAL_CORE_JS_PROMISE = inspectSource(PromiseConstructor) !== String(PromiseConstructor);
	  if (!GLOBAL_CORE_JS_PROMISE) {
	    // V8 6.6 (Node 10 and Chrome 66) have a bug with resolving custom thenables
	    // https://bugs.chromium.org/p/chromium/issues/detail?id=830565
	    // We can't detect it synchronously, so just check versions
	    if (engineV8Version === 66) return true;
	    // Unhandled rejections tracking support, NodeJS Promise without it fails @@species test
	    if (!IS_NODE$1 && typeof PromiseRejectionEvent != 'function') return true;
	  }
	  // We can't use @@species feature detection in V8 since it causes
	  // deoptimization and performance degradation
	  // https://github.com/zloirock/core-js/issues/679
	  if (engineV8Version >= 51 && /native code/.test(PromiseConstructor)) return false;
	  // Detect correctness of subclassing with @@species support
	  var promise = PromiseConstructor.resolve(1);
	  var FakePromise = function (exec) {
	    exec(function () { /* empty */ }, function () { /* empty */ });
	  };
	  var constructor = promise.constructor = {};
	  constructor[SPECIES$6] = FakePromise;
	  return !(promise.then(function () { /* empty */ }) instanceof FakePromise);
	});

	var INCORRECT_ITERATION$1 = FORCED$5 || !checkCorrectnessOfIteration(function (iterable) {
	  PromiseConstructor.all(iterable)['catch'](function () { /* empty */ });
	});

	// helpers
	var isThenable = function (it) {
	  var then;
	  return isObject(it) && typeof (then = it.then) == 'function' ? then : false;
	};

	var notify$1 = function (promise, state, isReject) {
	  if (state.notified) return;
	  state.notified = true;
	  var chain = state.reactions;
	  microtask(function () {
	    var value = state.value;
	    var ok = state.state == FULFILLED;
	    var index = 0;
	    // variable length - can't use forEach
	    while (chain.length > index) {
	      var reaction = chain[index++];
	      var handler = ok ? reaction.ok : reaction.fail;
	      var resolve = reaction.resolve;
	      var reject = reaction.reject;
	      var domain = reaction.domain;
	      var result, then, exited;
	      try {
	        if (handler) {
	          if (!ok) {
	            if (state.rejection === UNHANDLED) onHandleUnhandled(promise, state);
	            state.rejection = HANDLED;
	          }
	          if (handler === true) result = value;
	          else {
	            if (domain) domain.enter();
	            result = handler(value); // can throw
	            if (domain) {
	              domain.exit();
	              exited = true;
	            }
	          }
	          if (result === reaction.promise) {
	            reject(TypeError$1('Promise-chain cycle'));
	          } else if (then = isThenable(result)) {
	            then.call(result, resolve, reject);
	          } else resolve(result);
	        } else reject(value);
	      } catch (error) {
	        if (domain && !exited) domain.exit();
	        reject(error);
	      }
	    }
	    state.reactions = [];
	    state.notified = false;
	    if (isReject && !state.rejection) onUnhandled(promise, state);
	  });
	};

	var dispatchEvent = function (name, promise, reason) {
	  var event, handler;
	  if (DISPATCH_EVENT) {
	    event = document$2.createEvent('Event');
	    event.promise = promise;
	    event.reason = reason;
	    event.initEvent(name, false, true);
	    global_1.dispatchEvent(event);
	  } else event = { promise: promise, reason: reason };
	  if (handler = global_1['on' + name]) handler(event);
	  else if (name === UNHANDLED_REJECTION) hostReportErrors('Unhandled promise rejection', reason);
	};

	var onUnhandled = function (promise, state) {
	  task$1.call(global_1, function () {
	    var value = state.value;
	    var IS_UNHANDLED = isUnhandled(state);
	    var result;
	    if (IS_UNHANDLED) {
	      result = perform(function () {
	        if (IS_NODE$1) {
	          process$4.emit('unhandledRejection', value, promise);
	        } else dispatchEvent(UNHANDLED_REJECTION, promise, value);
	      });
	      // Browsers should not trigger `rejectionHandled` event if it was handled here, NodeJS - should
	      state.rejection = IS_NODE$1 || isUnhandled(state) ? UNHANDLED : HANDLED;
	      if (result.error) throw result.value;
	    }
	  });
	};

	var isUnhandled = function (state) {
	  return state.rejection !== HANDLED && !state.parent;
	};

	var onHandleUnhandled = function (promise, state) {
	  task$1.call(global_1, function () {
	    if (IS_NODE$1) {
	      process$4.emit('rejectionHandled', promise);
	    } else dispatchEvent(REJECTION_HANDLED, promise, state.value);
	  });
	};

	var bind = function (fn, promise, state, unwrap) {
	  return function (value) {
	    fn(promise, state, value, unwrap);
	  };
	};

	var internalReject = function (promise, state, value, unwrap) {
	  if (state.done) return;
	  state.done = true;
	  if (unwrap) state = unwrap;
	  state.value = value;
	  state.state = REJECTED;
	  notify$1(promise, state, true);
	};

	var internalResolve = function (promise, state, value, unwrap) {
	  if (state.done) return;
	  state.done = true;
	  if (unwrap) state = unwrap;
	  try {
	    if (promise === value) throw TypeError$1("Promise can't be resolved itself");
	    var then = isThenable(value);
	    if (then) {
	      microtask(function () {
	        var wrapper = { done: false };
	        try {
	          then.call(value,
	            bind(internalResolve, promise, wrapper, state),
	            bind(internalReject, promise, wrapper, state)
	          );
	        } catch (error) {
	          internalReject(promise, wrapper, error, state);
	        }
	      });
	    } else {
	      state.value = value;
	      state.state = FULFILLED;
	      notify$1(promise, state, false);
	    }
	  } catch (error) {
	    internalReject(promise, { done: false }, error, state);
	  }
	};

	// constructor polyfill
	if (FORCED$5) {
	  // 25.4.3.1 Promise(executor)
	  PromiseConstructor = function Promise(executor) {
	    anInstance(this, PromiseConstructor, PROMISE);
	    aFunction$1(executor);
	    Internal.call(this);
	    var state = getInternalState$3(this);
	    try {
	      executor(bind(internalResolve, this, state), bind(internalReject, this, state));
	    } catch (error) {
	      internalReject(this, state, error);
	    }
	  };
	  // eslint-disable-next-line no-unused-vars
	  Internal = function Promise(executor) {
	    setInternalState$5(this, {
	      type: PROMISE,
	      done: false,
	      notified: false,
	      parent: false,
	      reactions: [],
	      rejection: false,
	      state: PENDING,
	      value: undefined
	    });
	  };
	  Internal.prototype = redefineAll(PromiseConstructor.prototype, {
	    // `Promise.prototype.then` method
	    // https://tc39.github.io/ecma262/#sec-promise.prototype.then
	    then: function then(onFulfilled, onRejected) {
	      var state = getInternalPromiseState(this);
	      var reaction = newPromiseCapability$1(speciesConstructor(this, PromiseConstructor));
	      reaction.ok = typeof onFulfilled == 'function' ? onFulfilled : true;
	      reaction.fail = typeof onRejected == 'function' && onRejected;
	      reaction.domain = IS_NODE$1 ? process$4.domain : undefined;
	      state.parent = true;
	      state.reactions.push(reaction);
	      if (state.state != PENDING) notify$1(this, state, false);
	      return reaction.promise;
	    },
	    // `Promise.prototype.catch` method
	    // https://tc39.github.io/ecma262/#sec-promise.prototype.catch
	    'catch': function (onRejected) {
	      return this.then(undefined, onRejected);
	    }
	  });
	  OwnPromiseCapability = function () {
	    var promise = new Internal();
	    var state = getInternalState$3(promise);
	    this.promise = promise;
	    this.resolve = bind(internalResolve, promise, state);
	    this.reject = bind(internalReject, promise, state);
	  };
	  newPromiseCapability.f = newPromiseCapability$1 = function (C) {
	    return C === PromiseConstructor || C === PromiseWrapper
	      ? new OwnPromiseCapability(C)
	      : newGenericPromiseCapability(C);
	  };

	  if ( typeof nativePromiseConstructor == 'function') {
	    nativeThen = nativePromiseConstructor.prototype.then;

	    // wrap native Promise#then for native async functions
	    redefine(nativePromiseConstructor.prototype, 'then', function then(onFulfilled, onRejected) {
	      var that = this;
	      return new PromiseConstructor(function (resolve, reject) {
	        nativeThen.call(that, resolve, reject);
	      }).then(onFulfilled, onRejected);
	    // https://github.com/zloirock/core-js/issues/640
	    }, { unsafe: true });

	    // wrap fetch result
	    if (typeof $fetch == 'function') _export({ global: true, enumerable: true, forced: true }, {
	      // eslint-disable-next-line no-unused-vars
	      fetch: function fetch(input /* , init */) {
	        return promiseResolve(PromiseConstructor, $fetch.apply(global_1, arguments));
	      }
	    });
	  }
	}

	_export({ global: true, wrap: true, forced: FORCED$5 }, {
	  Promise: PromiseConstructor
	});

	setToStringTag(PromiseConstructor, PROMISE, false);
	setSpecies(PROMISE);

	PromiseWrapper = getBuiltIn(PROMISE);

	// statics
	_export({ target: PROMISE, stat: true, forced: FORCED$5 }, {
	  // `Promise.reject` method
	  // https://tc39.github.io/ecma262/#sec-promise.reject
	  reject: function reject(r) {
	    var capability = newPromiseCapability$1(this);
	    capability.reject.call(undefined, r);
	    return capability.promise;
	  }
	});

	_export({ target: PROMISE, stat: true, forced:  FORCED$5 }, {
	  // `Promise.resolve` method
	  // https://tc39.github.io/ecma262/#sec-promise.resolve
	  resolve: function resolve(x) {
	    return promiseResolve( this, x);
	  }
	});

	_export({ target: PROMISE, stat: true, forced: INCORRECT_ITERATION$1 }, {
	  // `Promise.all` method
	  // https://tc39.github.io/ecma262/#sec-promise.all
	  all: function all(iterable) {
	    var C = this;
	    var capability = newPromiseCapability$1(C);
	    var resolve = capability.resolve;
	    var reject = capability.reject;
	    var result = perform(function () {
	      var $promiseResolve = aFunction$1(C.resolve);
	      var values = [];
	      var counter = 0;
	      var remaining = 1;
	      iterate_1(iterable, function (promise) {
	        var index = counter++;
	        var alreadyCalled = false;
	        values.push(undefined);
	        remaining++;
	        $promiseResolve.call(C, promise).then(function (value) {
	          if (alreadyCalled) return;
	          alreadyCalled = true;
	          values[index] = value;
	          --remaining || resolve(values);
	        }, reject);
	      });
	      --remaining || resolve(values);
	    });
	    if (result.error) reject(result.value);
	    return capability.promise;
	  },
	  // `Promise.race` method
	  // https://tc39.github.io/ecma262/#sec-promise.race
	  race: function race(iterable) {
	    var C = this;
	    var capability = newPromiseCapability$1(C);
	    var reject = capability.reject;
	    var result = perform(function () {
	      var $promiseResolve = aFunction$1(C.resolve);
	      iterate_1(iterable, function (promise) {
	        $promiseResolve.call(C, promise).then(capability.resolve, reject);
	      });
	    });
	    if (result.error) reject(result.value);
	    return capability.promise;
	  }
	});

	function ownKeys$h(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread$g(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys$h(Object(source), true).forEach(function (key) { defineProperty$4(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys$h(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }
	var defaults$6 = useVFDefaults();
	var renderStore$1 = {};

	var Edit = function Edit(props) {
	  var _useState = React.useState(acf.uniqid('block_')),
	      _useState2 = slicedToArray(_useState, 1),
	      acfId = _useState2[0];

	  var _useState3 = React.useState(true),
	      _useState4 = slicedToArray(_useState3, 2),
	      isFetching = _useState4[0],
	      setFetching = _useState4[1];

	  var _useState5 = React.useState(true),
	      _useState6 = slicedToArray(_useState5, 2),
	      isLoading = _useState6[0],
	      setLoading = _useState6[1];

	  var _useState7 = React.useState(''),
	      _useState8 = slicedToArray(_useState7, 2),
	      render = _useState8[0],
	      setRender = _useState8[1];

	  var _useState9 = React.useState(null),
	      _useState10 = slicedToArray(_useState9, 2),
	      script = _useState10[0],
	      setScript = _useState10[1];

	  var ref = React.useRef(null);
	  var clientId = props.clientId;
	  var onMessage = React.useCallback(function (ev) {
	    var id = ev.data.id;

	    if (id && id.includes(acfId)) {
	      clearTimeout(window["".concat(id, "_onMessage")]);
	      window["".concat(id, "_onMessage")] = setTimeout(function () {
	        window.removeEventListener('message', onMessage);
	        setLoading(false);
	      }, 100);
	    }
	  }, [clientId]);
	  React.useEffect(function () {
	    setLoading(true);
	    setFetching(true);
	    window.removeEventListener('message', onMessage);
	    window.addEventListener('message', onMessage);

	    var fetch = /*#__PURE__*/function () {
	      var _ref = asyncToGenerator( /*#__PURE__*/regenerator.mark(function _callee() {
	        var render, fields, renderHash, response, html, _script;

	        return regenerator.wrap(function _callee$(_context) {
	          while (1) {
	            switch (_context.prev = _context.next) {
	              case 0:
	                fields = _objectSpread$g({
	                  is_plugin: 1
	                }, props.transient.fields);
	                renderHash = useHashsum(fields);

	                if (!renderStore$1.hasOwnProperty(renderHash)) {
	                  _context.next = 8;
	                  break;
	                }

	                _context.next = 5;
	                return new Promise(function (resolve) {
	                  return setTimeout(function () {
	                    resolve(renderStore$1[renderHash]);
	                  }, 1);
	                });

	              case 5:
	                render = _context.sent;
	                _context.next = 12;
	                break;

	              case 8:
	                _context.next = 10;
	                return wp.ajax.post('acf/ajax/fetch-block', {
	                  query: {
	                    preview: true
	                  },
	                  nonce: acf.get('nonce'),
	                  post_id: acf.get('post_id'),
	                  block: JSON.stringify({
	                    id: acfId,
	                    name: props.attributes.ref,
	                    data: fields,
	                    align: '',
	                    mode: 'preview'
	                  })
	                });

	              case 10:
	                response = _context.sent;

	                if (response && response.preview) {
	                  render = response.preview;
	                  renderStore$1[renderHash] = render;
	                }

	              case 12:
	                if (render) {
	                  html = render.split(/<script[^>]*?>/)[0];
	                  _script = render.match(/<script(?:(?!>)[\s\S])*?>([\s\S]*)<\/script>/m);
	                  setScript(Array.isArray(_script) ? _script[1] : null);
	                  setRender(html);
	                  setFetching(false);
	                }

	              case 13:
	              case "end":
	                return _context.stop();
	            }
	          }
	        }, _callee);
	      }));

	      return function fetch() {
	        return _ref.apply(this, arguments);
	      };
	    }();

	    fetch();
	  }, [clientId, props.attributes.__acfUpdate]);
	  React.useEffect(function () {
	    if (isFetching) {
	      return;
	    }

	    ref.current.innerHTML = render;

	    if (script) {
	      var el = document.createElement('script');
	      el.type = 'text/javascript';
	      el.innerHTML = script;
	      ref.current.appendChild(el);
	    }
	  }, [isFetching]); // add DOM attributes for styling

	  var rootAttrs = {
	    className: "vf-block ".concat(props.className),
	    'data-ver': props.attributes.ver,
	    'data-name': props.name,
	    'data-editing': false,
	    'data-loading': isLoading,
	    style: {}
	  };

	  if (isLoading) {
	    rootAttrs.style.minHeight = '100px';
	  }

	  var viewStyle = {};

	  if (isLoading) {
	    viewStyle.visibility = 'hidden';
	  }

	  return wp.element.createElement("div", rootAttrs, isLoading && wp.element.createElement(components.Spinner, null), wp.element.createElement("div", {
	    ref: ref,
	    style: viewStyle,
	    className: "vf-block__view"
	  }));
	};

	var withACFUpdates = function withACFUpdates(Edit) {
	  var transient = {
	    fields: {}
	  };
	  return function (props) {
	    var clientId = props.clientId;
	    React.useEffect(function () {
	      if (hooks.hasAction('vf_plugin_acf_update', 'vf_plugin')) {
	        return;
	      }

	      hooks.addAction('vf_plugin_acf_update', 'vf_plugin', function (data) {
	        transient.fields[data.name] = data.value;
	        props.setAttributes({
	          __acfUpdate: Date.now()
	        });
	      });
	    }, [clientId]);
	    return Edit(_objectSpread$g(_objectSpread$g({}, props), {}, {
	      transient: _objectSpread$g(_objectSpread$g({}, props.transient || {}), transient)
	    }));
	  };
	};
	var vfPlugin = _objectSpread$g(_objectSpread$g({}, defaults$6), {}, {
	  name: 'vf/plugin',
	  title: i18n.__('Preview'),
	  category: 'vf/wp',
	  description: '',
	  attributes: _objectSpread$g(_objectSpread$g({}, defaults$6.attributes), {}, {
	    ref: {
	      type: 'string'
	    }
	  }),
	  supports: _objectSpread$g(_objectSpread$g({}, defaults$6.supports), {}, {
	    inserter: false,
	    reusable: false
	  }),
	  edit: withACFUpdates(Edit),
	  save: function save() {
	    return null;
	  }
	});

	var _useVFGutenberg = useVFGutenberg(),
	    coreOptin = _useVFGutenberg.coreOptin; // Register VF Core blocks


	if (parseInt(coreOptin) === 1) {
	  var coreBlocks = [//Tabs
	  settings$4, settings$5, // Grid
	  settings$1, settings$2, settings$3, // Inner Blocks
	  settings, // Elements
	  vfBadge, vfBlockquote, vfButton, vfDivider, // Blocks
	  vfActivityItem, vfActivityList, vfBox, vfBreadcrumbsItem, vfBreadcrumbs, vfLede, vfEmbed];
	  coreBlocks.forEach(function (settings) {
	    return blocks.registerBlockType(settings.name, settings);
	  });
	} // Register experimental preview block
	blocks.registerBlockType('vf/plugin', vfPlugin); // Handle iframe preview resizing globally
	// TODO: remove necessity from `useVFIFrame`

	window.addEventListener('message', function (_ref) {
	  var data = _ref.data;

	  if (data !== Object(data) || !/^vfwp_/.test(data.id)) {
	    return;
	  }

	  var iframe = document.getElementById(data.id);

	  if (!iframe || !iframe.vfActive) {
	    return;
	  }

	  window.requestAnimationFrame(function () {
	    iframe.style.height = "".concat(data.height, "px");
	  });
	});

})));
