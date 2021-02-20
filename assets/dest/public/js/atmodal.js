/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/modules/atmodal.js":
/*!******************************************!*\
  !*** ./assets/src/js/modules/atmodal.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\assets\\src\\js\\modules\\atmodal.js: Unexpected token (9:1)\n\n\u001b[0m \u001b[90m  7 | \u001b[39m\u001b[90m/* disable-eslint */\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m  8 | \u001b[39m\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m  9 | \u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<\u001b[39m \u001b[33mHEAD\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m    | \u001b[39m \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 10 | \u001b[39m\u001b[33m===\u001b[39m\u001b[33m===\u001b[39m\u001b[33m=\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 11 | \u001b[39m\u001b[36mimport\u001b[39m \u001b[32m'./../../scss/layout/public/atmodal.scss'\u001b[39m\u001b[33m;\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 12 | \u001b[39m\u001b[0m\n    at Object._raise (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:748:17)\n    at Object.raiseWithData (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:741:17)\n    at Object.raise (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:735:17)\n    at Object.unexpected (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9101:16)\n    at Object.jsxParseIdentifier (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4536:12)\n    at Object.jsxParseNamespacedName (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4546:23)\n    at Object.jsxParseElementName (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4557:21)\n    at Object.jsxParseOpeningElementAt (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4644:22)\n    at Object.jsxParseElementAt (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4677:33)\n    at Object.jsxParseElement (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4751:17)\n    at Object.parseExprAtom (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:4758:19)\n    at Object.parseExprSubscripts (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:10150:23)\n    at Object.parseUpdate (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:10130:21)\n    at Object.parseMaybeUnary (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:10119:17)\n    at Object.parseExprOps (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9989:23)\n    at Object.parseMaybeConditional (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9963:23)\n    at Object.parseMaybeAssign (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9926:21)\n    at Object.parseExpressionBase (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9871:23)\n    at allowInAnd (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9865:39)\n    at Object.allowInAnd (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:11541:16)\n    at Object.parseExpression (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:9865:17)\n    at Object.parseStatementContent (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:11807:23)\n    at Object.parseStatement (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:11676:17)\n    at Object.parseBlockOrModuleBlockBody (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:12258:25)\n    at Object.parseBlockBody (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:12249:10)\n    at Object.parseTopLevel (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:11607:10)\n    at Object.parse (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:13415:10)\n    at parse (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\parser\\lib\\index.js:13468:38)\n    at parser (C:\\Users\\aazzt\\OneDrive\\Documents\\Local\\directorist\\app\\public\\wp-content\\plugins\\directorist\\node_modules\\@babel\\core\\lib\\parser\\index.js:54:34)\n    at parser.next (<anonymous>)");

/***/ }),

/***/ 11:
/*!************************************************!*\
  !*** multi ./assets/src/js/modules/atmodal.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/modules/atmodal.js */"./assets/src/js/modules/atmodal.js");


/***/ })

/******/ });
//# sourceMappingURL=atmodal.js.map