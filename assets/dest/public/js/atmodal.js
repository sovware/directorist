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

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: /Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/assets/src/js/modules/atmodal.js: Unexpected token (9:1)\n\n\u001b[0m \u001b[90m  7 |\u001b[39m \u001b[90m/* disable-eslint */\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m  8 |\u001b[39m\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m  9 |\u001b[39m \u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<<\u001b[39m\u001b[33m<\u001b[39m \u001b[33mHEAD\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m    |\u001b[39m  \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 10 |\u001b[39m \u001b[33m===\u001b[39m\u001b[33m===\u001b[39m\u001b[33m=\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 11 |\u001b[39m \u001b[36mimport\u001b[39m \u001b[32m'./../../scss/layout/public/atmodal.scss'\u001b[39m\u001b[33m;\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 12 |\u001b[39m\u001b[0m\n    at Object._raise (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:776:17)\n    at Object.raiseWithData (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:769:17)\n    at Object.raise (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:737:17)\n    at Object.unexpected (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:9264:16)\n    at Object.jsxParseIdentifier (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4769:12)\n    at Object.jsxParseNamespacedName (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4779:23)\n    at Object.jsxParseElementName (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4790:21)\n    at Object.jsxParseOpeningElementAt (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4877:22)\n    at Object.jsxParseElementAt (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4910:33)\n    at Object.jsxParseElement (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4984:17)\n    at Object.parseExprAtom (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:4991:19)\n    at Object.parseExprSubscripts (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10329:23)\n    at Object.parseUpdate (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10309:21)\n    at Object.parseMaybeUnary (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10287:23)\n    at Object.parseExprOps (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10152:23)\n    at Object.parseMaybeConditional (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10126:23)\n    at Object.parseMaybeAssign (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10089:21)\n    at Object.parseExpressionBase (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10034:23)\n    at /Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10028:39\n    at Object.allowInAnd (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11722:16)\n    at Object.parseExpression (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:10028:17)\n    at Object.parseStatementContent (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11988:23)\n    at Object.parseStatement (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11857:17)\n    at Object.parseBlockOrModuleBlockBody (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12439:25)\n    at Object.parseBlockBody (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:12430:10)\n    at Object.parseTopLevel (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:11788:10)\n    at Object.parse (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:13594:10)\n    at parse (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/parser/lib/index.js:13647:38)\n    at parser (/Users/syedgalib/Local Sites/directorist-dev/app/public/wp-content/plugins/directorist/node_modules/@babel/core/lib/parser/index.js:54:34)\n    at parser.next (<anonymous>)");

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