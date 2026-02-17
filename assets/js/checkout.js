/******/ (function () {
	// webpackBootstrap
	/******/ var __webpack_modules__ = {
		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/OverloadYield.js':
			/*!*******************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/OverloadYield.js ***!
  \*******************************************************************************************************/
			/***/ function (module) {
				function _OverloadYield(e, d) {
					((this.v = e), (this.k = d));
				}
				((module.exports = _OverloadYield),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js':
			/*!**************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js ***!
  \**************************************************************************************************************/
			/***/ function (
				__unused_webpack___webpack_module__,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ _asyncToGenerator;
						},
						/* harmony export */
					}
				);
				function asyncGeneratorStep(n, t, e, r, o, a, c) {
					try {
						var i = n[a](c),
							u = i.value;
					} catch (n) {
						return void e(n);
					}
					i.done ? t(u) : Promise.resolve(u).then(r, o);
				}
				function _asyncToGenerator(n) {
					return function () {
						var t = this,
							e = arguments;
						return new Promise(function (r, o) {
							var a = n.apply(t, e);
							function _next(n) {
								asyncGeneratorStep(
									a,
									r,
									o,
									_next,
									_throw,
									'next',
									n
								);
							}
							function _throw(n) {
								asyncGeneratorStep(
									a,
									r,
									o,
									_next,
									_throw,
									'throw',
									n
								);
							}
							_next(void 0);
						});
					};
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regenerator.js':
			/*!*****************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regenerator.js ***!
  \*****************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var regeneratorDefine = __webpack_require__(
					/*! ./regeneratorDefine.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorDefine.js'
				);
				function _regenerator() {
					/*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */
					var e,
						t,
						r = 'function' == typeof Symbol ? Symbol : {},
						n = r.iterator || '@@iterator',
						o = r.toStringTag || '@@toStringTag';
					function i(r, n, o, i) {
						var c =
								n && n.prototype instanceof Generator
									? n
									: Generator,
							u = Object.create(c.prototype);
						return (
							regeneratorDefine(
								u,
								'_invoke',
								(function (r, n, o) {
									var i,
										c,
										u,
										f = 0,
										p = o || [],
										y = !1,
										G = {
											p: 0,
											n: 0,
											v: e,
											a: d,
											f: d.bind(e, 4),
											d: function d(t, r) {
												return (
													(i = t),
													(c = 0),
													(u = e),
													(G.n = r),
													a
												);
											},
										};
									function d(r, n) {
										for (
											c = r, u = n, t = 0;
											!y && f && !o && t < p.length;
											t++
										) {
											var o,
												i = p[t],
												d = G.p,
												l = i[2];
											r > 3
												? (o = l === n) &&
													((u =
														i[
															(c = i[4])
																? 5
																: ((c = 3), 3)
														]),
													(i[4] = i[5] = e))
												: i[0] <= d &&
													((o = r < 2 && d < i[1])
														? ((c = 0),
															(G.v = n),
															(G.n = i[1]))
														: d < l &&
															(o =
																r < 3 ||
																i[0] > n ||
																n > l) &&
															((i[4] = r),
															(i[5] = n),
															(G.n = l),
															(c = 0)));
										}
										if (o || r > 1) return a;
										throw ((y = !0), n);
									}
									return function (o, p, l) {
										if (f > 1)
											throw TypeError(
												'Generator is already running'
											);
										for (
											y && 1 === p && d(p, l),
												c = p,
												u = l;
											(t = c < 2 ? e : u) || !y;

										) {
											i ||
												(c
													? c < 3
														? (c > 1 && (G.n = -1),
															d(c, u))
														: (G.n = u)
													: (G.v = u));
											try {
												if (((f = 2), i)) {
													if (
														(c || (o = 'next'),
														(t = i[o]))
													) {
														if (!(t = t.call(i, u)))
															throw TypeError(
																'iterator result is not an object'
															);
														if (!t.done) return t;
														((u = t.value),
															c < 2 && (c = 0));
													} else
														(1 === c &&
															(t = i['return']) &&
															t.call(i),
															c < 2 &&
																((u = TypeError(
																	"The iterator does not provide a '" +
																		o +
																		"' method"
																)),
																(c = 1)));
													i = e;
												} else if (
													(t = (y = G.n < 0)
														? u
														: r.call(n, G)) !== a
												)
													break;
											} catch (t) {
												((i = e), (c = 1), (u = t));
											} finally {
												f = 1;
											}
										}
										return {
											value: t,
											done: y,
										};
									};
								})(r, o, i),
								!0
							),
							u
						);
					}
					var a = {};
					function Generator() {}
					function GeneratorFunction() {}
					function GeneratorFunctionPrototype() {}
					t = Object.getPrototypeOf;
					var c = [][n]
							? t(t([][n]()))
							: (regeneratorDefine((t = {}), n, function () {
									return this;
								}),
								t),
						u =
							(GeneratorFunctionPrototype.prototype =
							Generator.prototype =
								Object.create(c));
					function f(e) {
						return (
							Object.setPrototypeOf
								? Object.setPrototypeOf(
										e,
										GeneratorFunctionPrototype
									)
								: ((e.__proto__ = GeneratorFunctionPrototype),
									regeneratorDefine(
										e,
										o,
										'GeneratorFunction'
									)),
							(e.prototype = Object.create(u)),
							e
						);
					}
					return (
						(GeneratorFunction.prototype =
							GeneratorFunctionPrototype),
						regeneratorDefine(
							u,
							'constructor',
							GeneratorFunctionPrototype
						),
						regeneratorDefine(
							GeneratorFunctionPrototype,
							'constructor',
							GeneratorFunction
						),
						(GeneratorFunction.displayName = 'GeneratorFunction'),
						regeneratorDefine(
							GeneratorFunctionPrototype,
							o,
							'GeneratorFunction'
						),
						regeneratorDefine(u),
						regeneratorDefine(u, o, 'Generator'),
						regeneratorDefine(u, n, function () {
							return this;
						}),
						regeneratorDefine(u, 'toString', function () {
							return '[object Generator]';
						}),
						((module.exports = _regenerator =
							function _regenerator() {
								return {
									w: i,
									m: f,
								};
							}),
						(module.exports.__esModule = true),
						(module.exports['default'] = module.exports))()
					);
				}
				((module.exports = _regenerator),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsync.js':
			/*!**********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsync.js ***!
  \**********************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var regeneratorAsyncGen = __webpack_require__(
					/*! ./regeneratorAsyncGen.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncGen.js'
				);
				function _regeneratorAsync(n, e, r, t, o) {
					var a = regeneratorAsyncGen(n, e, r, t, o);
					return a.next().then(function (n) {
						return n.done ? n.value : a.next();
					});
				}
				((module.exports = _regeneratorAsync),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncGen.js':
			/*!*************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncGen.js ***!
  \*************************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var regenerator = __webpack_require__(
					/*! ./regenerator.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regenerator.js'
				);
				var regeneratorAsyncIterator = __webpack_require__(
					/*! ./regeneratorAsyncIterator.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncIterator.js'
				);
				function _regeneratorAsyncGen(r, e, t, o, n) {
					return new regeneratorAsyncIterator(
						regenerator().w(r, e, t, o),
						n || Promise
					);
				}
				((module.exports = _regeneratorAsyncGen),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncIterator.js':
			/*!******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncIterator.js ***!
  \******************************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var OverloadYield = __webpack_require__(
					/*! ./OverloadYield.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/OverloadYield.js'
				);
				var regeneratorDefine = __webpack_require__(
					/*! ./regeneratorDefine.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorDefine.js'
				);
				function AsyncIterator(t, e) {
					function n(r, o, i, f) {
						try {
							var c = t[r](o),
								u = c.value;
							return u instanceof OverloadYield
								? e.resolve(u.v).then(
										function (t) {
											n('next', t, i, f);
										},
										function (t) {
											n('throw', t, i, f);
										}
									)
								: e.resolve(u).then(
										function (t) {
											((c.value = t), i(c));
										},
										function (t) {
											return n('throw', t, i, f);
										}
									);
						} catch (t) {
							f(t);
						}
					}
					var r;
					(this.next ||
						(regeneratorDefine(AsyncIterator.prototype),
						regeneratorDefine(
							AsyncIterator.prototype,
							('function' == typeof Symbol &&
								Symbol.asyncIterator) ||
								'@asyncIterator',
							function () {
								return this;
							}
						)),
						regeneratorDefine(
							this,
							'_invoke',
							function (t, o, i) {
								function f() {
									return new e(function (e, r) {
										n(t, i, e, r);
									});
								}
								return (r = r ? r.then(f, f) : f());
							},
							!0
						));
				}
				((module.exports = AsyncIterator),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorDefine.js':
			/*!***********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorDefine.js ***!
  \***********************************************************************************************************/
			/***/ function (module) {
				function _regeneratorDefine(e, r, n, t) {
					var i = Object.defineProperty;
					try {
						i({}, '', {});
					} catch (e) {
						i = 0;
					}
					((module.exports = _regeneratorDefine =
						function regeneratorDefine(e, r, n, t) {
							function o(r, n) {
								_regeneratorDefine(e, r, function (e) {
									return this._invoke(r, n, e);
								});
							}
							r
								? i
									? i(e, r, {
											value: n,
											enumerable: !t,
											configurable: !t,
											writable: !t,
										})
									: (e[r] = n)
								: (o('next', 0), o('throw', 1), o('return', 2));
						}),
						(module.exports.__esModule = true),
						(module.exports['default'] = module.exports),
						_regeneratorDefine(e, r, n, t));
				}
				((module.exports = _regeneratorDefine),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorKeys.js':
			/*!*********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorKeys.js ***!
  \*********************************************************************************************************/
			/***/ function (module) {
				function _regeneratorKeys(e) {
					var n = Object(e),
						r = [];
					for (var t in n) r.unshift(t);
					return function e() {
						for (; r.length; )
							if ((t = r.pop()) in n)
								return ((e.value = t), (e.done = !1), e);
						return ((e.done = !0), e);
					};
				}
				((module.exports = _regeneratorKeys),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorRuntime.js':
			/*!************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorRuntime.js ***!
  \************************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var OverloadYield = __webpack_require__(
					/*! ./OverloadYield.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/OverloadYield.js'
				);
				var regenerator = __webpack_require__(
					/*! ./regenerator.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regenerator.js'
				);
				var regeneratorAsync = __webpack_require__(
					/*! ./regeneratorAsync.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsync.js'
				);
				var regeneratorAsyncGen = __webpack_require__(
					/*! ./regeneratorAsyncGen.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncGen.js'
				);
				var regeneratorAsyncIterator = __webpack_require__(
					/*! ./regeneratorAsyncIterator.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorAsyncIterator.js'
				);
				var regeneratorKeys = __webpack_require__(
					/*! ./regeneratorKeys.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorKeys.js'
				);
				var regeneratorValues = __webpack_require__(
					/*! ./regeneratorValues.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorValues.js'
				);
				function _regeneratorRuntime() {
					'use strict';

					var r = regenerator(),
						e = r.m(_regeneratorRuntime),
						t = (
							Object.getPrototypeOf
								? Object.getPrototypeOf(e)
								: e.__proto__
						).constructor;
					function n(r) {
						var e = 'function' == typeof r && r.constructor;
						return (
							!!e &&
							(e === t ||
								'GeneratorFunction' ===
									(e.displayName || e.name))
						);
					}
					var o = {
						throw: 1,
						return: 2,
						break: 3,
						continue: 3,
					};
					function a(r) {
						var e, t;
						return function (n) {
							(e ||
								((e = {
									stop: function stop() {
										return t(n.a, 2);
									},
									catch: function _catch() {
										return n.v;
									},
									abrupt: function abrupt(r, e) {
										return t(n.a, o[r], e);
									},
									delegateYield: function delegateYield(
										r,
										o,
										a
									) {
										return (
											(e.resultName = o),
											t(n.d, regeneratorValues(r), a)
										);
									},
									finish: function finish(r) {
										return t(n.f, r);
									},
								}),
								(t = function t(r, _t, o) {
									((n.p = e.prev), (n.n = e.next));
									try {
										return r(_t, o);
									} finally {
										e.next = n.n;
									}
								})),
								e.resultName &&
									((e[e.resultName] = n.v),
									(e.resultName = void 0)),
								(e.sent = n.v),
								(e.next = n.n));
							try {
								return r.call(this, e);
							} finally {
								((n.p = e.prev), (n.n = e.next));
							}
						};
					}
					return ((module.exports = _regeneratorRuntime =
						function _regeneratorRuntime() {
							return {
								wrap: function wrap(e, t, n, o) {
									return r.w(a(e), t, n, o && o.reverse());
								},
								isGeneratorFunction: n,
								mark: r.m,
								awrap: function awrap(r, e) {
									return new OverloadYield(r, e);
								},
								AsyncIterator: regeneratorAsyncIterator,
								async: function async(r, e, t, o, u) {
									return (
										n(e)
											? regeneratorAsyncGen
											: regeneratorAsync
									)(a(r), e, t, o, u);
								},
								keys: regeneratorKeys,
								values: regeneratorValues,
							};
						}),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports))();
				}
				((module.exports = _regeneratorRuntime),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorValues.js':
			/*!***********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorValues.js ***!
  \***********************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				var _typeof = __webpack_require__(
					/*! ./typeof.js */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/typeof.js'
				)['default'];
				function _regeneratorValues(e) {
					if (null != e) {
						var t =
								e[
									('function' == typeof Symbol &&
										Symbol.iterator) ||
										'@@iterator'
								],
							r = 0;
						if (t) return t.call(e);
						if ('function' == typeof e.next) return e;
						if (!isNaN(e.length))
							return {
								next: function next() {
									return (
										e && r >= e.length && (e = void 0),
										{
											value: e && e[r++],
											done: !e,
										}
									);
								},
							};
					}
					throw new TypeError(_typeof(e) + ' is not iterable');
				}
				((module.exports = _regeneratorValues),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/typeof.js':
			/*!************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/typeof.js ***!
  \************************************************************************************************/
			/***/ function (module) {
				function _typeof(o) {
					'@babel/helpers - typeof';

					return (
						(module.exports = _typeof =
							'function' == typeof Symbol &&
							'symbol' == typeof Symbol.iterator
								? function (o) {
										return typeof o;
									}
								: function (o) {
										return o &&
											'function' == typeof Symbol &&
											o.constructor === Symbol &&
											o !== Symbol.prototype
											? 'symbol'
											: typeof o;
									}),
						(module.exports.__esModule = true),
						(module.exports['default'] = module.exports),
						_typeof(o)
					);
				}
				((module.exports = _typeof),
					(module.exports.__esModule = true),
					(module.exports['default'] = module.exports));

				/***/
			},

		/***/ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/regenerator/index.js':
			/*!***************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/regenerator/index.js ***!
  \***************************************************************************************************/
			/***/ function (
				module,
				__unused_webpack_exports,
				__webpack_require__
			) {
				// TODO(Babel 8): Remove this file.

				var runtime = __webpack_require__(
					/*! ../helpers/regeneratorRuntime */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/regeneratorRuntime.js'
				)();
				module.exports = runtime;

				// Copied from https://github.com/facebook/regenerator/blob/main/packages/runtime/runtime.js#L736=
				try {
					regeneratorRuntime = runtime;
				} catch (accidentalStrictMode) {
					if (typeof globalThis === 'object') {
						globalThis.regeneratorRuntime = runtime;
					} else {
						Function('r', 'regeneratorRuntime = r')(runtime);
					}
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@tannin+compile@1.1.0/node_modules/@tannin/compile/index.js':
			/*!****************************************************************************************!*\
  !*** ./node_modules/.pnpm/@tannin+compile@1.1.0/node_modules/@tannin/compile/index.js ***!
  \****************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ compile;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _tannin_postfix__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! @tannin/postfix */ './node_modules/.pnpm/@tannin+postfix@1.1.0/node_modules/@tannin/postfix/index.js'
					);
				/* harmony import */ var _tannin_evaluate__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! @tannin/evaluate */ './node_modules/.pnpm/@tannin+evaluate@1.2.0/node_modules/@tannin/evaluate/index.js'
					);

				/**
				 * Given a C expression, returns a function which can be called to evaluate its
				 * result.
				 *
				 * @example
				 *
				 * ```js
				 * import compile from '@tannin/compile';
				 *
				 * const evaluate = compile( 'n > 1' );
				 *
				 * evaluate( { n: 2 } );
				 * // ⇒ true
				 * ```
				 *
				 * @param {string} expression C expression.
				 *
				 * @return {(variables?:{[variable:string]:*})=>*} Compiled evaluator.
				 */
				function compile(expression) {
					var terms = (0,
					_tannin_postfix__WEBPACK_IMPORTED_MODULE_0__['default'])(
						expression
					);

					return function (variables) {
						return (0,
						_tannin_evaluate__WEBPACK_IMPORTED_MODULE_1__[
							'default'
						])(terms, variables);
					};
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@tannin+evaluate@1.2.0/node_modules/@tannin/evaluate/index.js':
			/*!******************************************************************************************!*\
  !*** ./node_modules/.pnpm/@tannin+evaluate@1.2.0/node_modules/@tannin/evaluate/index.js ***!
  \******************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ evaluate;
						},
						/* harmony export */
					}
				);
				/**
				 * Operator callback functions.
				 *
				 * @type {Object}
				 */
				var OPERATORS = {
					'!': function (a) {
						return !a;
					},
					'*': function (a, b) {
						return a * b;
					},
					'/': function (a, b) {
						return a / b;
					},
					'%': function (a, b) {
						return a % b;
					},
					'+': function (a, b) {
						return a + b;
					},
					'-': function (a, b) {
						return a - b;
					},
					'<': function (a, b) {
						return a < b;
					},
					'<=': function (a, b) {
						return a <= b;
					},
					'>': function (a, b) {
						return a > b;
					},
					'>=': function (a, b) {
						return a >= b;
					},
					'==': function (a, b) {
						return a === b;
					},
					'!=': function (a, b) {
						return a !== b;
					},
					'&&': function (a, b) {
						return a && b;
					},
					'||': function (a, b) {
						return a || b;
					},
					'?:': function (a, b, c) {
						if (a) {
							throw b;
						}

						return c;
					},
				};

				/**
				 * Given an array of postfix terms and operand variables, returns the result of
				 * the postfix evaluation.
				 *
				 * @example
				 *
				 * ```js
				 * import evaluate from '@tannin/evaluate';
				 *
				 * // 3 + 4 * 5 / 6 ⇒ '3 4 5 * 6 / +'
				 * const terms = [ '3', '4', '5', '*', '6', '/', '+' ];
				 *
				 * evaluate( terms, {} );
				 * // ⇒ 6.333333333333334
				 * ```
				 *
				 * @param {string[]} postfix   Postfix terms.
				 * @param {Object}   variables Operand variables.
				 *
				 * @return {*} Result of evaluation.
				 */
				function evaluate(postfix, variables) {
					var stack = [],
						i,
						j,
						args,
						getOperatorResult,
						term,
						value;

					for (i = 0; i < postfix.length; i++) {
						term = postfix[i];

						getOperatorResult = OPERATORS[term];
						if (getOperatorResult) {
							// Pop from stack by number of function arguments.
							j = getOperatorResult.length;
							args = Array(j);
							while (j--) {
								args[j] = stack.pop();
							}

							try {
								value = getOperatorResult.apply(null, args);
							} catch (earlyReturn) {
								return earlyReturn;
							}
						} else if (variables.hasOwnProperty(term)) {
							value = variables[term];
						} else {
							value = +term;
						}

						stack.push(value);
					}

					return stack[0];
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@tannin+plural-forms@1.1.0/node_modules/@tannin/plural-forms/index.js':
			/*!**************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@tannin+plural-forms@1.1.0/node_modules/@tannin/plural-forms/index.js ***!
  \**************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ pluralForms;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _tannin_compile__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! @tannin/compile */ './node_modules/.pnpm/@tannin+compile@1.1.0/node_modules/@tannin/compile/index.js'
					);

				/**
				 * Given a C expression, returns a function which, when called with a value,
				 * evaluates the result with the value assumed to be the "n" variable of the
				 * expression. The result will be coerced to its numeric equivalent.
				 *
				 * @param {string} expression C expression.
				 *
				 * @return {Function} Evaluator function.
				 */
				function pluralForms(expression) {
					var evaluate = (0,
					_tannin_compile__WEBPACK_IMPORTED_MODULE_0__['default'])(
						expression
					);

					return function (n) {
						return +evaluate({ n: n });
					};
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@tannin+postfix@1.1.0/node_modules/@tannin/postfix/index.js':
			/*!****************************************************************************************!*\
  !*** ./node_modules/.pnpm/@tannin+postfix@1.1.0/node_modules/@tannin/postfix/index.js ***!
  \****************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ postfix;
						},
						/* harmony export */
					}
				);
				var PRECEDENCE, OPENERS, TERMINATORS, PATTERN;

				/**
				 * Operator precedence mapping.
				 *
				 * @type {Object}
				 */
				PRECEDENCE = {
					'(': 9,
					'!': 8,
					'*': 7,
					'/': 7,
					'%': 7,
					'+': 6,
					'-': 6,
					'<': 5,
					'<=': 5,
					'>': 5,
					'>=': 5,
					'==': 4,
					'!=': 4,
					'&&': 3,
					'||': 2,
					'?': 1,
					'?:': 1,
				};

				/**
				 * Characters which signal pair opening, to be terminated by terminators.
				 *
				 * @type {string[]}
				 */
				OPENERS = ['(', '?'];

				/**
				 * Characters which signal pair termination, the value an array with the
				 * opener as its first member. The second member is an optional operator
				 * replacement to push to the stack.
				 *
				 * @type {string[]}
				 */
				TERMINATORS = {
					')': ['('],
					':': ['?', '?:'],
				};

				/**
				 * Pattern matching operators and openers.
				 *
				 * @type {RegExp}
				 */
				PATTERN =
					/<=|>=|==|!=|&&|\|\||\?:|\(|!|\*|\/|%|\+|-|<|>|\?|\)|:/;

				/**
				 * Given a C expression, returns the equivalent postfix (Reverse Polish)
				 * notation terms as an array.
				 *
				 * If a postfix string is desired, simply `.join( ' ' )` the result.
				 *
				 * @example
				 *
				 * ```js
				 * import postfix from '@tannin/postfix';
				 *
				 * postfix( 'n > 1' );
				 * // ⇒ [ 'n', '1', '>' ]
				 * ```
				 *
				 * @param {string} expression C expression.
				 *
				 * @return {string[]} Postfix terms.
				 */
				function postfix(expression) {
					var terms = [],
						stack = [],
						match,
						operator,
						term,
						element;

					while ((match = expression.match(PATTERN))) {
						operator = match[0];

						// Term is the string preceding the operator match. It may contain
						// whitespace, and may be empty (if operator is at beginning).
						term = expression.substr(0, match.index).trim();
						if (term) {
							terms.push(term);
						}

						while ((element = stack.pop())) {
							if (TERMINATORS[operator]) {
								if (TERMINATORS[operator][0] === element) {
									// Substitution works here under assumption that because
									// the assigned operator will no longer be a terminator, it
									// will be pushed to the stack during the condition below.
									operator =
										TERMINATORS[operator][1] || operator;
									break;
								}
							} else if (
								OPENERS.indexOf(element) >= 0 ||
								PRECEDENCE[element] < PRECEDENCE[operator]
							) {
								// Push to stack if either an opener or when pop reveals an
								// element of lower precedence.
								stack.push(element);
								break;
							}

							// For each popped from stack, push to terms.
							terms.push(element);
						}

						if (!TERMINATORS[operator]) {
							stack.push(operator);
						}

						// Slice matched fragment from expression to continue match.
						expression = expression.substr(
							match.index + operator.length
						);
					}

					// Push remainder of operand, if exists, to terms.
					expression = expression.trim();
					if (expression) {
						terms.push(expression);
					}

					// Pop remaining items from stack into terms.
					return terms.concat(stack.reverse());
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@tannin+sprintf@1.3.3/node_modules/@tannin/sprintf/src/index.js':
			/*!********************************************************************************************!*\
  !*** ./node_modules/.pnpm/@tannin+sprintf@1.3.3/node_modules/@tannin/sprintf/src/index.js ***!
  \********************************************************************************************/
			/***/ function (
				__unused_webpack___webpack_module__,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ sprintf;
						},
						/* harmony export */
					}
				);
				/**
				 * Regular expression matching format placeholder syntax.
				 *
				 * The pattern for matching named arguments is a naive and incomplete matcher
				 * against valid JavaScript identifier names.
				 *
				 * via Mathias Bynens:
				 *
				 * >An identifier must start with $, _, or any character in the Unicode
				 * >categories “Uppercase letter (Lu)”, “Lowercase letter (Ll)”, “Titlecase
				 * >letter (Lt)”, “Modifier letter (Lm)”, “Other letter (Lo)”, or “Letter
				 * >number (Nl)”.
				 * >
				 * >The rest of the string can contain the same characters, plus any U+200C zero
				 * >width non-joiner characters, U+200D zero width joiner characters, and
				 * >characters in the Unicode categories “Non-spacing mark (Mn)”, “Spacing
				 * >combining mark (Mc)”, “Decimal digit number (Nd)”, or “Connector
				 * >punctuation (Pc)”.
				 *
				 * If browser support is constrained to those supporting ES2015, this could be
				 * made more accurate using the `u` flag:
				 *
				 * ```
				 * /^[$_\p{L}\p{Nl}][$_\p{L}\p{Nl}\u200C\u200D\p{Mn}\p{Mc}\p{Nd}\p{Pc}]*$/u;
				 * ```
				 *
				 * @see http://www.pixelbeat.org/programming/gcc/format_specs.html
				 * @see https://mathiasbynens.be/notes/javascript-identifiers#valid-identifier-names
				 *
				 * @type {RegExp}
				 */
				var PATTERN =
					/%(((\d+)\$)|(\(([$_a-zA-Z][$_a-zA-Z0-9]*)\)))?[ +0#-]*\d*(\.(\d+|\*))?(ll|[lhqL])?([cduxXefgsp%])/g;
				//               ▲         ▲                    ▲       ▲  ▲            ▲           ▲ type
				//               │         │                    │       │  │            └ Length (unsupported)
				//               │         │                    │       │  └ Precision / max width
				//               │         │                    │       └ Min width (unsupported)
				//               │         │                    └ Flags (unsupported)
				//               └ Index   └ Name (for named arguments)
				/**
				 * Given a format string, returns string with arguments interpolatation.
				 * Arguments can either be provided directly via function arguments spread, or
				 * with an array as the second argument.
				 *
				 * @see https://en.wikipedia.org/wiki/Printf_format_string
				 *
				 * @example
				 *
				 * ```js
				 * import sprintf from '@tannin/sprintf';
				 *
				 * sprintf( 'Hello %s!', 'world' );
				 * // ⇒ 'Hello world!'
				 * ```
				 * @template {string} T
				 * @overload
				 * @param {T} string - string printf format string
				 * @param {...import('../types').SprintfArgs<T>} args - arguments to interpolate
				 *
				 * @return {string} Formatted string.
				 */

				/**
				 * Given a format string, returns string with arguments interpolatation.
				 * Arguments can either be provided directly via function arguments spread, or
				 * with an array as the second argument.
				 *
				 * @see https://en.wikipedia.org/wiki/Printf_format_string
				 *
				 * @example
				 *
				 * ```js
				 * import sprintf from '@tannin/sprintf';
				 *
				 * sprintf( 'Hello %s!', 'world' );
				 * // ⇒ 'Hello world!'
				 * ```
				 * @template {string} T
				 * @overload
				 * @param {T} string - string printf format string
				 * @param {import('../types').SprintfArgs<T>} args - arguments to interpolate
				 *
				 * @return {string} Formatted string.
				 */

				/**
				 * Given a format string, returns string with arguments interpolatation.
				 * Arguments can either be provided directly via function arguments spread, or
				 * with an array as the second argument.
				 *
				 * @see https://en.wikipedia.org/wiki/Printf_format_string
				 *
				 * @example
				 *
				 * ```js
				 * import sprintf from '@tannin/sprintf';
				 *
				 * sprintf( 'Hello %s!', 'world' );
				 * // ⇒ 'Hello world!'
				 * ```
				 * @template {string} T
				 * @param {T} string - string printf format string
				 * @param {...import('../types').SprintfArgs<T>} args - arguments to interpolate
				 *
				 * @return {string} Formatted string.
				 */
				function sprintf(string, ...args) {
					var i = 0;
					if (Array.isArray(args[0])) {
						args =
							/** @type {import('../types').SprintfArgs<T>[]} */ (
								/** @type {unknown} */ args[0]
							);
					}

					return string.replace(PATTERN, function () {
						var index,
							// name needs to be documented as `string | undefined` else value will have tpye unknown.
							/**
							 * Name of the argument to substitute, if any.
							 *
							 * @type {string | undefined}
							 */
							name,
							precision,
							type,
							value;

						index = arguments[3];
						name = arguments[5];
						precision = arguments[7];
						type = arguments[9];

						// There's no placeholder substitution in the explicit "%", meaning it
						// is not necessary to increment argument index.
						if (type === '%') {
							return '%';
						}

						// Asterisk precision determined by peeking / shifting next argument.
						if (precision === '*') {
							precision = args[i];
							i++;
						}

						if (name === undefined) {
							// If not a positional argument, use counter value.
							if (index === undefined) {
								index = i + 1;
							}

							i++;

							// Positional argument.
							value = args[index - 1];
						} else if (
							args[0] &&
							typeof args[0] === 'object' &&
							args[0].hasOwnProperty(name)
						) {
							// If it's a named argument, use name.
							value = args[0][name];
						}

						// Parse as type.
						if (type === 'f') {
							value = parseFloat(value) || 0;
						} else if (type === 'd') {
							value = parseInt(value) || 0;
						}

						// Apply precision.
						if (precision !== undefined) {
							if (type === 'f') {
								value = value.toFixed(precision);
							} else if (type === 's') {
								value = value.substr(0, precision);
							}
						}

						// To avoid "undefined" concatenation, return empty string if no
						// placeholder substitution can be performed.
						return value !== undefined && value !== null
							? value
							: '';
					});
				}

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createAddHook.js':
			/*!****************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createAddHook.js ***!
  \****************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony import */ var _validateNamespace__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./validateNamespace */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js'
					);
				/* harmony import */ var _validateHookName__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! ./validateHookName */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateHookName.js'
					);
				/**
				 * Internal dependencies
				 */

				/**
				 *
				 * Adds the hook to the appropriate hooks container.
				 */

				/**
				 * Returns a function which, when invoked, will add a hook.
				 *
				 * @param hooks    Hooks instance.
				 * @param storeKey
				 *
				 * @return  Function that adds a new hook.
				 */
				function createAddHook(hooks, storeKey) {
					return function addHook(
						hookName,
						namespace,
						callback,
						priority = 10
					) {
						const hooksStore = hooks[storeKey];
						if (
							!(0,
							_validateHookName__WEBPACK_IMPORTED_MODULE_1__[
								'default'
							])(hookName)
						) {
							return;
						}
						if (
							!(0,
							_validateNamespace__WEBPACK_IMPORTED_MODULE_0__[
								'default'
							])(namespace)
						) {
							return;
						}
						if ('function' !== typeof callback) {
							// eslint-disable-next-line no-console
							console.error(
								'The hook callback must be a function.'
							);
							return;
						}

						// Validate numeric priority
						if ('number' !== typeof priority) {
							// eslint-disable-next-line no-console
							console.error(
								'If specified, the hook priority must be a number.'
							);
							return;
						}
						const handler = {
							callback,
							priority,
							namespace,
						};
						if (hooksStore[hookName]) {
							// Find the correct insert index of the new hook.
							const handlers = hooksStore[hookName].handlers;
							let i;
							for (i = handlers.length; i > 0; i--) {
								if (priority >= handlers[i - 1].priority) {
									break;
								}
							}
							if (i === handlers.length) {
								// If append, operate via direct assignment.
								handlers[i] = handler;
							} else {
								// Otherwise, insert before index via splice.
								handlers.splice(i, 0, handler);
							}

							// We may also be currently executing this hook.  If the callback
							// we're adding would come after the current callback, there's no
							// problem; otherwise we need to increase the execution index of
							// any other runs by 1 to account for the added element.
							hooksStore.__current.forEach((hookInfo) => {
								if (
									hookInfo.name === hookName &&
									hookInfo.currentIndex >= i
								) {
									hookInfo.currentIndex++;
								}
							});
						} else {
							// This is the first hook of its type.
							hooksStore[hookName] = {
								handlers: [handler],
								runs: 0,
							};
						}
						if (hookName !== 'hookAdded') {
							hooks.doAction(
								'hookAdded',
								hookName,
								namespace,
								callback,
								priority
							);
						}
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createAddHook;
				//# sourceMappingURL=createAddHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js':
			/*!********************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js ***!
  \********************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Internal dependencies
				 */

				/**
				 * Returns a function which, when invoked, will return the name of the
				 * currently running hook, or `null` if no hook of the given type is currently
				 * running.
				 *
				 * @param hooks    Hooks instance.
				 * @param storeKey
				 *
				 * @return Function that returns the current hook name or null.
				 */
				function createCurrentHook(hooks, storeKey) {
					return function currentHook() {
						var _currentArray$at$name;
						const hooksStore = hooks[storeKey];
						const currentArray = Array.from(hooksStore.__current);
						return (_currentArray$at$name =
							currentArray.at(-1)?.name) !== null &&
							_currentArray$at$name !== void 0
							? _currentArray$at$name
							: null;
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createCurrentHook;
				//# sourceMappingURL=createCurrentHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDidHook.js':
			/*!****************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDidHook.js ***!
  \****************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony import */ var _validateHookName__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./validateHookName */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateHookName.js'
					);
				/**
				 * Internal dependencies
				 */

				/**
				 *
				 * Returns the number of times an action has been fired.
				 *
				 */

				/**
				 * Returns a function which, when invoked, will return the number of times a
				 * hook has been called.
				 *
				 * @param hooks    Hooks instance.
				 * @param storeKey
				 *
				 * @return  Function that returns a hook's call count.
				 */
				function createDidHook(hooks, storeKey) {
					return function didHook(hookName) {
						const hooksStore = hooks[storeKey];
						if (
							!(0,
							_validateHookName__WEBPACK_IMPORTED_MODULE_0__[
								'default'
							])(hookName)
						) {
							return;
						}
						return hooksStore[hookName] && hooksStore[hookName].runs
							? hooksStore[hookName].runs
							: 0;
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createDidHook;
				//# sourceMappingURL=createDidHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js':
			/*!******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js ***!
  \******************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Internal dependencies
				 */

				/**
				 * Returns whether a hook is currently being executed.
				 *
				 */

				/**
				 * Returns a function which, when invoked, will return whether a hook is
				 * currently being executed.
				 *
				 * @param hooks    Hooks instance.
				 * @param storeKey
				 *
				 * @return Function that returns whether a hook is currently
				 *                     being executed.
				 */
				function createDoingHook(hooks, storeKey) {
					return function doingHook(hookName) {
						const hooksStore = hooks[storeKey];

						// If the hookName was not passed, check for any current hook.
						if ('undefined' === typeof hookName) {
							return hooksStore.__current.size > 0;
						}

						// Find if the `hookName` hook is in `__current`.
						return Array.from(hooksStore.__current).some(
							(hook) => hook.name === hookName
						);
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createDoingHook;
				//# sourceMappingURL=createDoingHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHasHook.js':
			/*!****************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHasHook.js ***!
  \****************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Internal dependencies
				 */

				/**
				 *
				 * Returns whether any handlers are attached for the given hookName and optional namespace.
				 */

				/**
				 * Returns a function which, when invoked, will return whether any handlers are
				 * attached to a particular hook.
				 *
				 * @param hooks    Hooks instance.
				 * @param storeKey
				 *
				 * @return  Function that returns whether any handlers are
				 *                   attached to a particular hook and optional namespace.
				 */
				function createHasHook(hooks, storeKey) {
					return function hasHook(hookName, namespace) {
						const hooksStore = hooks[storeKey];

						// Use the namespace if provided.
						if ('undefined' !== typeof namespace) {
							return (
								hookName in hooksStore &&
								hooksStore[hookName].handlers.some(
									(hook) => hook.namespace === namespace
								)
							);
						}
						return hookName in hooksStore;
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createHasHook;
				//# sourceMappingURL=createHasHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHooks.js':
			/*!**************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHooks.js ***!
  \**************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ _Hooks: function () {
							return /* binding */ _Hooks;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _createAddHook__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./createAddHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createAddHook.js'
					);
				/* harmony import */ var _createRemoveHook__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! ./createRemoveHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js'
					);
				/* harmony import */ var _createHasHook__WEBPACK_IMPORTED_MODULE_2__ =
					__webpack_require__(
						/*! ./createHasHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHasHook.js'
					);
				/* harmony import */ var _createRunHook__WEBPACK_IMPORTED_MODULE_3__ =
					__webpack_require__(
						/*! ./createRunHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRunHook.js'
					);
				/* harmony import */ var _createCurrentHook__WEBPACK_IMPORTED_MODULE_4__ =
					__webpack_require__(
						/*! ./createCurrentHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createCurrentHook.js'
					);
				/* harmony import */ var _createDoingHook__WEBPACK_IMPORTED_MODULE_5__ =
					__webpack_require__(
						/*! ./createDoingHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDoingHook.js'
					);
				/* harmony import */ var _createDidHook__WEBPACK_IMPORTED_MODULE_6__ =
					__webpack_require__(
						/*! ./createDidHook */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createDidHook.js'
					);
				/**
				 * Internal dependencies
				 */

				/**
				 * Internal class for constructing hooks. Use `createHooks()` function
				 *
				 * Note, it is necessary to expose this class to make its type public.
				 *
				 * @private
				 */
				class _Hooks {
					constructor() {
						this.actions = Object.create(null);
						this.actions.__current = new Set();
						this.filters = Object.create(null);
						this.filters.__current = new Set();
						this.addAction = (0,
						_createAddHook__WEBPACK_IMPORTED_MODULE_0__['default'])(
							this,
							'actions'
						);
						this.addFilter = (0,
						_createAddHook__WEBPACK_IMPORTED_MODULE_0__['default'])(
							this,
							'filters'
						);
						this.removeAction = (0,
						_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[
							'default'
						])(this, 'actions');
						this.removeFilter = (0,
						_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[
							'default'
						])(this, 'filters');
						this.hasAction = (0,
						_createHasHook__WEBPACK_IMPORTED_MODULE_2__['default'])(
							this,
							'actions'
						);
						this.hasFilter = (0,
						_createHasHook__WEBPACK_IMPORTED_MODULE_2__['default'])(
							this,
							'filters'
						);
						this.removeAllActions = (0,
						_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[
							'default'
						])(this, 'actions', true);
						this.removeAllFilters = (0,
						_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[
							'default'
						])(this, 'filters', true);
						this.doAction = (0,
						_createRunHook__WEBPACK_IMPORTED_MODULE_3__['default'])(
							this,
							'actions',
							false,
							false
						);
						this.doActionAsync = (0,
						_createRunHook__WEBPACK_IMPORTED_MODULE_3__['default'])(
							this,
							'actions',
							false,
							true
						);
						this.applyFilters = (0,
						_createRunHook__WEBPACK_IMPORTED_MODULE_3__['default'])(
							this,
							'filters',
							true,
							false
						);
						this.applyFiltersAsync = (0,
						_createRunHook__WEBPACK_IMPORTED_MODULE_3__['default'])(
							this,
							'filters',
							true,
							true
						);
						this.currentAction = (0,
						_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[
							'default'
						])(this, 'actions');
						this.currentFilter = (0,
						_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[
							'default'
						])(this, 'filters');
						this.doingAction = (0,
						_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[
							'default'
						])(this, 'actions');
						this.doingFilter = (0,
						_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[
							'default'
						])(this, 'filters');
						this.didAction = (0,
						_createDidHook__WEBPACK_IMPORTED_MODULE_6__['default'])(
							this,
							'actions'
						);
						this.didFilter = (0,
						_createDidHook__WEBPACK_IMPORTED_MODULE_6__['default'])(
							this,
							'filters'
						);
					}
				}
				/**
				 * Returns an instance of the hooks object.
				 *
				 * @return A Hooks instance.
				 */
				function createHooks() {
					return new _Hooks();
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createHooks;
				//# sourceMappingURL=createHooks.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js':
			/*!*******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRemoveHook.js ***!
  \*******************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony import */ var _validateNamespace__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./validateNamespace */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js'
					);
				/* harmony import */ var _validateHookName__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! ./validateHookName */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateHookName.js'
					);
				/**
				 * Internal dependencies
				 */

				/**
				 * Removes the specified callback (or all callbacks) from the hook with a given hookName
				 * and namespace.
				 */

				/**
				 * Returns a function which, when invoked, will remove a specified hook or all
				 * hooks by the given name.
				 *
				 * @param hooks             Hooks instance.
				 * @param storeKey
				 * @param [removeAll=false] Whether to remove all callbacks for a hookName,
				 *                          without regard to namespace. Used to create
				 *                          `removeAll*` functions.
				 *
				 * @return Function that removes hooks.
				 */
				function createRemoveHook(hooks, storeKey, removeAll = false) {
					return function removeHook(hookName, namespace) {
						const hooksStore = hooks[storeKey];
						if (
							!(0,
							_validateHookName__WEBPACK_IMPORTED_MODULE_1__[
								'default'
							])(hookName)
						) {
							return;
						}
						if (
							!removeAll &&
							!(0,
							_validateNamespace__WEBPACK_IMPORTED_MODULE_0__[
								'default'
							])(namespace)
						) {
							return;
						}

						// Bail if no hooks exist by this name.
						if (!hooksStore[hookName]) {
							return 0;
						}
						let handlersRemoved = 0;
						if (removeAll) {
							handlersRemoved =
								hooksStore[hookName].handlers.length;
							hooksStore[hookName] = {
								runs: hooksStore[hookName].runs,
								handlers: [],
							};
						} else {
							// Try to find the specified callback to remove.
							const handlers = hooksStore[hookName].handlers;
							for (let i = handlers.length - 1; i >= 0; i--) {
								if (handlers[i].namespace === namespace) {
									handlers.splice(i, 1);
									handlersRemoved++;
									// This callback may also be part of a hook that is
									// currently executing.  If the callback we're removing
									// comes after the current callback, there's no problem;
									// otherwise we need to decrease the execution index of any
									// other runs by 1 to account for the removed element.
									hooksStore.__current.forEach((hookInfo) => {
										if (
											hookInfo.name === hookName &&
											hookInfo.currentIndex >= i
										) {
											hookInfo.currentIndex--;
										}
									});
								}
							}
						}
						if (hookName !== 'hookRemoved') {
							hooks.doAction('hookRemoved', hookName, namespace);
						}
						return handlersRemoved;
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createRemoveHook;
				//# sourceMappingURL=createRemoveHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRunHook.js':
			/*!****************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createRunHook.js ***!
  \****************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Internal dependencies
				 */

				/**
				 * Returns a function which, when invoked, will execute all callbacks
				 * registered to a hook of the specified type, optionally returning the final
				 * value of the call chain.
				 *
				 * @param hooks          Hooks instance.
				 * @param storeKey
				 * @param returnFirstArg Whether each hook callback is expected to return its first argument.
				 * @param async          Whether the hook callback should be run asynchronously
				 *
				 * @return Function that runs hook callbacks.
				 */
				function createRunHook(hooks, storeKey, returnFirstArg, async) {
					return function runHook(hookName, ...args) {
						const hooksStore = hooks[storeKey];
						if (!hooksStore[hookName]) {
							hooksStore[hookName] = {
								handlers: [],
								runs: 0,
							};
						}
						hooksStore[hookName].runs++;
						const handlers = hooksStore[hookName].handlers;

						// The following code is stripped from production builds.
						if (true) {
							// Handle any 'all' hooks registered.
							if ('hookAdded' !== hookName && hooksStore.all) {
								handlers.push(...hooksStore.all.handlers);
							}
						}
						if (!handlers || !handlers.length) {
							return returnFirstArg ? args[0] : undefined;
						}
						const hookInfo = {
							name: hookName,
							currentIndex: 0,
						};
						async function asyncRunner() {
							try {
								hooksStore.__current.add(hookInfo);
								let result = returnFirstArg
									? args[0]
									: undefined;
								while (
									hookInfo.currentIndex < handlers.length
								) {
									const handler =
										handlers[hookInfo.currentIndex];
									result = await handler.callback.apply(
										null,
										args
									);
									if (returnFirstArg) {
										args[0] = result;
									}
									hookInfo.currentIndex++;
								}
								return returnFirstArg ? result : undefined;
							} finally {
								hooksStore.__current.delete(hookInfo);
							}
						}
						function syncRunner() {
							try {
								hooksStore.__current.add(hookInfo);
								let result = returnFirstArg
									? args[0]
									: undefined;
								while (
									hookInfo.currentIndex < handlers.length
								) {
									const handler =
										handlers[hookInfo.currentIndex];
									result = handler.callback.apply(null, args);
									if (returnFirstArg) {
										args[0] = result;
									}
									hookInfo.currentIndex++;
								}
								return returnFirstArg ? result : undefined;
							} finally {
								hooksStore.__current.delete(hookInfo);
							}
						}
						return (async ? asyncRunner : syncRunner)();
					};
				}
				/* harmony default export */ __webpack_exports__['default'] =
					createRunHook;
				//# sourceMappingURL=createRunHook.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/index.js':
			/*!********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/index.js ***!
  \********************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ actions: function () {
							return /* binding */ actions;
						},
						/* harmony export */ addAction: function () {
							return /* binding */ addAction;
						},
						/* harmony export */ addFilter: function () {
							return /* binding */ addFilter;
						},
						/* harmony export */ applyFilters: function () {
							return /* binding */ applyFilters;
						},
						/* harmony export */ applyFiltersAsync: function () {
							return /* binding */ applyFiltersAsync;
						},
						/* harmony export */ createHooks: function () {
							return /* reexport safe */ _createHooks__WEBPACK_IMPORTED_MODULE_0__[
								'default'
							];
						},
						/* harmony export */ currentAction: function () {
							return /* binding */ currentAction;
						},
						/* harmony export */ currentFilter: function () {
							return /* binding */ currentFilter;
						},
						/* harmony export */ defaultHooks: function () {
							return /* binding */ defaultHooks;
						},
						/* harmony export */ didAction: function () {
							return /* binding */ didAction;
						},
						/* harmony export */ didFilter: function () {
							return /* binding */ didFilter;
						},
						/* harmony export */ doAction: function () {
							return /* binding */ doAction;
						},
						/* harmony export */ doActionAsync: function () {
							return /* binding */ doActionAsync;
						},
						/* harmony export */ doingAction: function () {
							return /* binding */ doingAction;
						},
						/* harmony export */ doingFilter: function () {
							return /* binding */ doingFilter;
						},
						/* harmony export */ filters: function () {
							return /* binding */ filters;
						},
						/* harmony export */ hasAction: function () {
							return /* binding */ hasAction;
						},
						/* harmony export */ hasFilter: function () {
							return /* binding */ hasFilter;
						},
						/* harmony export */ removeAction: function () {
							return /* binding */ removeAction;
						},
						/* harmony export */ removeAllActions: function () {
							return /* binding */ removeAllActions;
						},
						/* harmony export */ removeAllFilters: function () {
							return /* binding */ removeAllFilters;
						},
						/* harmony export */ removeFilter: function () {
							return /* binding */ removeFilter;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _createHooks__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./createHooks */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/createHooks.js'
					);
				/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! ./types */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/types.js'
					);
				/**
				 * Internal dependencies
				 */

				const defaultHooks = (0,
				_createHooks__WEBPACK_IMPORTED_MODULE_0__['default'])();
				const {
					addAction,
					addFilter,
					removeAction,
					removeFilter,
					hasAction,
					hasFilter,
					removeAllActions,
					removeAllFilters,
					doAction,
					doActionAsync,
					applyFilters,
					applyFiltersAsync,
					currentAction,
					currentFilter,
					doingAction,
					doingFilter,
					didAction,
					didFilter,
					actions,
					filters,
				} = defaultHooks;

				//# sourceMappingURL=index.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/types.js':
			/*!********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/types.js ***!
  \********************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);

				//# sourceMappingURL=types.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateHookName.js':
			/*!*******************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateHookName.js ***!
  \*******************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Validate a hookName string.
				 *
				 * @param hookName The hook name to validate. Should be a non empty string containing
				 *                 only numbers, letters, dashes, periods and underscores. Also,
				 *                 the hook name cannot begin with `__`.
				 *
				 * @return Whether the hook name is valid.
				 */
				function validateHookName(hookName) {
					if ('string' !== typeof hookName || '' === hookName) {
						// eslint-disable-next-line no-console
						console.error(
							'The hook name must be a non-empty string.'
						);
						return false;
					}
					if (/^__/.test(hookName)) {
						// eslint-disable-next-line no-console
						console.error('The hook name cannot begin with `__`.');
						return false;
					}
					if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {
						// eslint-disable-next-line no-console
						console.error(
							'The hook name can only contain numbers, letters, dashes, periods and underscores.'
						);
						return false;
					}
					return true;
				}
				/* harmony default export */ __webpack_exports__['default'] =
					validateHookName;
				//# sourceMappingURL=validateHookName.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js':
			/*!********************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/validateNamespace.js ***!
  \********************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/**
				 * Validate a namespace string.
				 *
				 * @param namespace The namespace to validate - should take the form
				 *                  `vendor/plugin/function`.
				 *
				 * @return Whether the namespace is valid.
				 */
				function validateNamespace(namespace) {
					if ('string' !== typeof namespace || '' === namespace) {
						// eslint-disable-next-line no-console
						console.error(
							'The namespace must be a non-empty string.'
						);
						return false;
					}
					if (!/^[a-zA-Z][a-zA-Z0-9_.\-\/]*$/.test(namespace)) {
						// eslint-disable-next-line no-console
						console.error(
							'The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.'
						);
						return false;
					}
					return true;
				}
				/* harmony default export */ __webpack_exports__['default'] =
					validateNamespace;
				//# sourceMappingURL=validateNamespace.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/create-i18n.js':
			/*!***********************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/create-i18n.js ***!
  \***********************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ createI18n: function () {
							return /* binding */ createI18n;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var tannin__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! tannin */ './node_modules/.pnpm/tannin@1.2.0/node_modules/tannin/index.js'
					);
				/**
				 * External dependencies
				 */

				/**
				 * Internal dependencies
				 */

				/**
				 * WordPress dependencies
				 */

				/**
				 * Default locale data to use for Tannin domain when not otherwise provided.
				 * Assumes an English plural forms expression.
				 */
				const DEFAULT_LOCALE_DATA = {
					'': {
						plural_forms(n) {
							return n === 1 ? 0 : 1;
						},
					},
				};

				/*
				 * Regular expression that matches i18n hooks like `i18n.gettext`, `i18n.ngettext`,
				 * `i18n.gettext_domain` or `i18n.ngettext_with_context` or `i18n.has_translation`.
				 */
				const I18N_HOOK_REGEXP =
					/^i18n\.(n?gettext|has_translation)(_|$)/;

				/**
				 * Create an i18n instance
				 *
				 * @param [initialData]   Locale data configuration.
				 * @param [initialDomain] Domain for which configuration applies.
				 * @param [hooks]         Hooks implementation.
				 *
				 * @return I18n instance.
				 */
				const createI18n = (initialData, initialDomain, hooks) => {
					/**
					 * The underlying instance of Tannin to which exported functions interface.
					 */
					const tannin = new tannin__WEBPACK_IMPORTED_MODULE_0__[
						'default'
					]({});
					const listeners = new Set();
					const notifyListeners = () => {
						listeners.forEach((listener) => listener());
					};

					/**
					 * Subscribe to changes of locale data.
					 *
					 * @param callback Subscription callback.
					 * @return Unsubscribe callback.
					 */
					const subscribe = (callback) => {
						listeners.add(callback);
						return () => listeners.delete(callback);
					};
					const getLocaleData = (domain = 'default') =>
						tannin.data[domain];

					/**
					 * @param [data]
					 * @param [domain]
					 */
					const doSetLocaleData = (data, domain = 'default') => {
						tannin.data[domain] = {
							...tannin.data[domain],
							...data,
						};

						// Populate default domain configuration (supported locale date which omits
						// a plural forms expression).
						tannin.data[domain][''] = {
							...DEFAULT_LOCALE_DATA[''],
							...tannin.data[domain]?.[''],
						};

						// Clean up cached plural forms functions cache as it might be updated.
						delete tannin.pluralForms[domain];
					};
					const setLocaleData = (data, domain) => {
						doSetLocaleData(data, domain);
						notifyListeners();
					};
					const addLocaleData = (data, domain = 'default') => {
						tannin.data[domain] = {
							...tannin.data[domain],
							...data,
							// Populate default domain configuration (supported locale date which omits
							// a plural forms expression).
							'': {
								...DEFAULT_LOCALE_DATA[''],
								...tannin.data[domain]?.[''],
								...data?.[''],
							},
						};

						// Clean up cached plural forms functions cache as it might be updated.
						delete tannin.pluralForms[domain];
						notifyListeners();
					};
					const resetLocaleData = (data, domain) => {
						// Reset all current Tannin locale data.
						tannin.data = {};

						// Reset cached plural forms functions cache.
						tannin.pluralForms = {};
						setLocaleData(data, domain);
					};

					/**
					 * Wrapper for Tannin's `dcnpgettext`. Populates default locale data if not
					 * otherwise previously assigned.
					 *
					 * @param domain   Domain to retrieve the translated text.
					 * @param context  Context information for the translators.
					 * @param single   Text to translate if non-plural. Used as
					 *                 fallback return value on a caught error.
					 * @param [plural] The text to be used if the number is
					 *                 plural.
					 * @param [number] The number to compare against to use
					 *                 either the singular or plural form.
					 *
					 * @return The translated string.
					 */
					const dcnpgettext = (
						domain = 'default',
						context,
						single,
						plural,
						number
					) => {
						if (!tannin.data[domain]) {
							// Use `doSetLocaleData` to set silently, without notifying listeners.
							doSetLocaleData(undefined, domain);
						}
						return tannin.dcnpgettext(
							domain,
							context,
							single,
							plural,
							number
						);
					};
					const getFilterDomain = (domain) => domain || 'default';
					const __ = (text, domain) => {
						let translation = dcnpgettext(domain, undefined, text);
						if (!hooks) {
							return translation;
						}

						/**
						 * Filters text with its translation.
						 *
						 * @param translation Translated text.
						 * @param text        Text to translate.
						 * @param domain      Text domain. Unique identifier for retrieving translated strings.
						 */
						translation = hooks.applyFilters(
							'i18n.gettext',
							translation,
							text,
							domain
						);
						return hooks.applyFilters(
							'i18n.gettext_' + getFilterDomain(domain),
							translation,
							text,
							domain
						);
					};
					const _x = (text, context, domain) => {
						let translation = dcnpgettext(domain, context, text);
						if (!hooks) {
							return translation;
						}

						/**
						 * Filters text with its translation based on context information.
						 *
						 * @param translation Translated text.
						 * @param text        Text to translate.
						 * @param context     Context information for the translators.
						 * @param domain      Text domain. Unique identifier for retrieving translated strings.
						 */
						translation = hooks.applyFilters(
							'i18n.gettext_with_context',
							translation,
							text,
							context,
							domain
						);
						return hooks.applyFilters(
							'i18n.gettext_with_context_' +
								getFilterDomain(domain),
							translation,
							text,
							context,
							domain
						);
					};
					const _n = (single, plural, number, domain) => {
						let translation = dcnpgettext(
							domain,
							undefined,
							single,
							plural,
							number
						);
						if (!hooks) {
							return translation;
						}

						/**
						 * Filters the singular or plural form of a string.
						 *
						 * @param translation Translated text.
						 * @param single      The text to be used if the number is singular.
						 * @param plural      The text to be used if the number is plural.
						 * @param number      The number to compare against to use either the singular or plural form.
						 * @param domain      Text domain. Unique identifier for retrieving translated strings.
						 */
						translation = hooks.applyFilters(
							'i18n.ngettext',
							translation,
							single,
							plural,
							number,
							domain
						);
						return hooks.applyFilters(
							'i18n.ngettext_' + getFilterDomain(domain),
							translation,
							single,
							plural,
							number,
							domain
						);
					};
					const _nx = (single, plural, number, context, domain) => {
						let translation = dcnpgettext(
							domain,
							context,
							single,
							plural,
							number
						);
						if (!hooks) {
							return translation;
						}

						/**
						 * Filters the singular or plural form of a string with gettext context.
						 *
						 * @param translation Translated text.
						 * @param single      The text to be used if the number is singular.
						 * @param plural      The text to be used if the number is plural.
						 * @param number      The number to compare against to use either the singular or plural form.
						 * @param context     Context information for the translators.
						 * @param domain      Text domain. Unique identifier for retrieving translated strings.
						 */
						translation = hooks.applyFilters(
							'i18n.ngettext_with_context',
							translation,
							single,
							plural,
							number,
							context,
							domain
						);
						return hooks.applyFilters(
							'i18n.ngettext_with_context_' +
								getFilterDomain(domain),
							translation,
							single,
							plural,
							number,
							context,
							domain
						);
					};
					const isRTL = () => {
						return 'rtl' === _x('ltr', 'text direction');
					};
					const hasTranslation = (single, context, domain) => {
						const key = context
							? context + '\u0004' + single
							: single;
						let result =
							!!tannin.data?.[
								domain !== null && domain !== void 0
									? domain
									: 'default'
							]?.[key];
						if (hooks) {
							/**
							 * Filters the presence of a translation in the locale data.
							 *
							 * @param hasTranslation Whether the translation is present or not..
							 * @param single         The singular form of the translated text (used as key in locale data)
							 * @param context        Context information for the translators.
							 * @param domain         Text domain. Unique identifier for retrieving translated strings.
							 */
							result = hooks.applyFilters(
								'i18n.has_translation',
								result,
								single,
								context,
								domain
							);
							result = hooks.applyFilters(
								'i18n.has_translation_' +
									getFilterDomain(domain),
								result,
								single,
								context,
								domain
							);
						}
						return result;
					};
					if (initialData) {
						setLocaleData(initialData, initialDomain);
					}
					if (hooks) {
						/**
						 * @param hookName
						 */
						const onHookAddedOrRemoved = (hookName) => {
							if (I18N_HOOK_REGEXP.test(hookName)) {
								notifyListeners();
							}
						};
						hooks.addAction(
							'hookAdded',
							'core/i18n',
							onHookAddedOrRemoved
						);
						hooks.addAction(
							'hookRemoved',
							'core/i18n',
							onHookAddedOrRemoved
						);
					}
					return {
						getLocaleData,
						setLocaleData,
						addLocaleData,
						resetLocaleData,
						subscribe,
						__,
						_x,
						_n,
						_nx,
						isRTL,
						hasTranslation,
					};
				};
				//# sourceMappingURL=create-i18n.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/default-i18n.js':
			/*!************************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/default-i18n.js ***!
  \************************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ __: function () {
							return /* binding */ __;
						},
						/* harmony export */ _n: function () {
							return /* binding */ _n;
						},
						/* harmony export */ _nx: function () {
							return /* binding */ _nx;
						},
						/* harmony export */ _x: function () {
							return /* binding */ _x;
						},
						/* harmony export */ getLocaleData: function () {
							return /* binding */ getLocaleData;
						},
						/* harmony export */ hasTranslation: function () {
							return /* binding */ hasTranslation;
						},
						/* harmony export */ isRTL: function () {
							return /* binding */ isRTL;
						},
						/* harmony export */ resetLocaleData: function () {
							return /* binding */ resetLocaleData;
						},
						/* harmony export */ setLocaleData: function () {
							return /* binding */ setLocaleData;
						},
						/* harmony export */ subscribe: function () {
							return /* binding */ subscribe;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _create_i18n__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./create-i18n */ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/create-i18n.js'
					);
				/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! @wordpress/hooks */ './node_modules/.pnpm/@wordpress+hooks@4.29.0/node_modules/@wordpress/hooks/build-module/index.js'
					);
				/**
				 * Internal dependencies
				 */

				/**
				 * WordPress dependencies
				 */

				const i18n = (0,
				_create_i18n__WEBPACK_IMPORTED_MODULE_0__.createI18n)(
					undefined,
					undefined,
					_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__.defaultHooks
				);

				/**
				 * Default, singleton instance of `I18n`.
				 */
				/* harmony default export */ __webpack_exports__['default'] =
					i18n;

				/*
				 * Comments in this file are duplicated from ./i18n due to
				 * https://github.com/WordPress/gutenberg/pull/20318#issuecomment-590837722
				 */

				/**
				 * Returns locale data by domain in a Jed-formatted JSON object shape.
				 *
				 * @see http://messageformat.github.io/Jed/
				 *
				 * @param { string | undefined } [domain] Domain for which to get the data.
				 * @return { LocaleData } Locale data.
				 */
				const getLocaleData = i18n.getLocaleData.bind(i18n);

				/**
				 * Merges locale data into the Tannin instance by domain. Accepts data in a
				 * Jed-formatted JSON object shape.
				 *
				 * @see http://messageformat.github.io/Jed/
				 *
				 * @param {LocaleData }        [data]   Locale data configuration.
				 * @param {string | undefined} [domain] Domain for which configuration applies.
				 */
				const setLocaleData = i18n.setLocaleData.bind(i18n);

				/**
				 * Resets all current Tannin instance locale data and sets the specified
				 * locale data for the domain. Accepts data in a Jed-formatted JSON object shape.
				 *
				 * @see http://messageformat.github.io/Jed/
				 *
				 * @param {LocaleData}         [data]   Locale data configuration.
				 * @param {string | undefined} [domain] Domain for which configuration applies.
				 */
				const resetLocaleData = i18n.resetLocaleData.bind(i18n);

				/**
				 * Subscribes to changes of locale data
				 *
				 * @param {SubscribeCallback} callback Subscription callback
				 * @return {UnsubscribeCallback} Unsubscribe callback
				 */
				const subscribe = i18n.subscribe.bind(i18n);

				/**
				 * Retrieve the translation of text.
				 *
				 * @see https://developer.wordpress.org/reference/functions/__/
				 *
				 * @template {string} Text
				 *
				 * @param {Text}               text   Text to translate.
				 * @param {string | undefined} domain Domain to retrieve the translated text.
				 *
				 * @return {TranslatableText<Text>} Translated text.
				 */
				const __ = i18n.__.bind(i18n);

				/**
				 * Retrieve translated string with gettext context.
				 *
				 * @see https://developer.wordpress.org/reference/functions/_x/
				 *
				 * @template {string} Text
				 *
				 * @param {Text}               text    Text to translate.
				 * @param {string}             context Context information for the translators.
				 * @param {string | undefined} domain  Domain to retrieve the translated text.
				 *
				 * @return {TranslatableText<Text>} Translated context string without pipe.
				 */
				const _x = i18n._x.bind(i18n);

				/**
				 * Translates and retrieves the singular or plural form based on the supplied
				 * number.
				 *
				 * @see https://developer.wordpress.org/reference/functions/_n/
				 *
				 * @template {string} Single
				 * @template {string} Plural
				 *
				 * @param {Single}             single The text to be used if the number is singular.
				 * @param {Plural}             plural The text to be used if the number is plural.
				 * @param {number}             number The number to compare against to use either the
				 *                                    singular or plural form.
				 * @param {string | undefined} domain Domain to retrieve the translated text.
				 *
				 * @return {TranslatableText<Single | Plural>} The translated singular or plural form.
				 */
				const _n = i18n._n.bind(i18n);

				/**
				 * Translates and retrieves the singular or plural form based on the supplied
				 * number, with gettext context.
				 *
				 * @see https://developer.wordpress.org/reference/functions/_nx/
				 *
				 * @template {string} Single
				 * @template {string} Plural
				 * @param {Single}             single   The text to be used if the number is singular.
				 *
				 * @param {Single}             single   The text to be used if the number is singular.
				 * @param {Plural}             plural   The text to be used if the number is plural.
				 * @param {number}             number   The number to compare against to use either the
				 *                                      singular or plural form.
				 * @param {string}             context  Context information for the translators.
				 * @param {string | undefined} [domain] Domain to retrieve the translated text.
				 *
				 * @return {TranslatableText<Single | Plural>} The translated singular or plural form.
				 */
				const _nx = i18n._nx.bind(i18n);

				/**
				 * Check if current locale is RTL.
				 *
				 * **RTL (Right To Left)** is a locale property indicating that text is written from right to left.
				 * For example, the `he` locale (for Hebrew) specifies right-to-left. Arabic (ar) is another common
				 * language written RTL. The opposite of RTL, LTR (Left To Right) is used in other languages,
				 * including English (`en`, `en-US`, `en-GB`, etc.), Spanish (`es`), and French (`fr`).
				 *
				 * @return {boolean} Whether locale is RTL.
				 */
				const isRTL = i18n.isRTL.bind(i18n);

				/**
				 * Check if there is a translation for a given string (in singular form).
				 *
				 * @param {string} single  Singular form of the string to look up.
				 * @param {string} context Context information for the translators.
				 * @param {string} domain  Domain to retrieve the translated text.
				 *
				 * @return {boolean} Whether the translation exists or not.
				 */
				const hasTranslation = i18n.hasTranslation.bind(i18n);
				//# sourceMappingURL=default-i18n.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/index.js':
			/*!*****************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/index.js ***!
  \*****************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ __: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.__;
						},
						/* harmony export */ _n: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__._n;
						},
						/* harmony export */ _nx: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__._nx;
						},
						/* harmony export */ _x: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__._x;
						},
						/* harmony export */ createI18n: function () {
							return /* reexport safe */ _create_i18n__WEBPACK_IMPORTED_MODULE_1__.createI18n;
						},
						/* harmony export */ defaultI18n: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__[
								'default'
							];
						},
						/* harmony export */ getLocaleData: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.getLocaleData;
						},
						/* harmony export */ hasTranslation: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.hasTranslation;
						},
						/* harmony export */ isRTL: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.isRTL;
						},
						/* harmony export */ resetLocaleData: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.resetLocaleData;
						},
						/* harmony export */ setLocaleData: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.setLocaleData;
						},
						/* harmony export */ sprintf: function () {
							return /* reexport safe */ _sprintf__WEBPACK_IMPORTED_MODULE_0__.sprintf;
						},
						/* harmony export */ subscribe: function () {
							return /* reexport safe */ _default_i18n__WEBPACK_IMPORTED_MODULE_2__.subscribe;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _sprintf__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! ./sprintf */ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/sprintf.js'
					);
				/* harmony import */ var _create_i18n__WEBPACK_IMPORTED_MODULE_1__ =
					__webpack_require__(
						/*! ./create-i18n */ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/create-i18n.js'
					);
				/* harmony import */ var _default_i18n__WEBPACK_IMPORTED_MODULE_2__ =
					__webpack_require__(
						/*! ./default-i18n */ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/default-i18n.js'
					);

				//# sourceMappingURL=index.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/sprintf.js':
			/*!*******************************************************************************************************!*\
  !*** ./node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/sprintf.js ***!
  \*******************************************************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ sprintf: function () {
							return /* binding */ sprintf;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _tannin_sprintf__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! @tannin/sprintf */ './node_modules/.pnpm/@tannin+sprintf@1.3.3/node_modules/@tannin/sprintf/src/index.js'
					);
				/**
				 * External dependencies
				 */
				// Disable reason: `eslint-plugin-import` doesn't support `exports` (https://github.com/import-js/eslint-plugin-import/issues/1810)
				// eslint-disable-next-line import/no-unresolved

				/**
				 * Internal dependencies
				 */

				/**
				 * Returns a formatted string.
				 *
				 * @param format The format of the string to generate.
				 * @param args   Arguments to apply to the format.
				 *
				 * @see https://www.npmjs.com/package/@tannin/sprintf
				 *
				 * @return The formatted string.
				 */
				function sprintf(format, ...args) {
					return (0,
					_tannin_sprintf__WEBPACK_IMPORTED_MODULE_0__['default'])(
						format,
						...args
					);
				}
				//# sourceMappingURL=sprintf.js.map

				/***/
			},

		/***/ './node_modules/.pnpm/tannin@1.2.0/node_modules/tannin/index.js':
			/*!**********************************************************************!*\
  !*** ./node_modules/.pnpm/tannin@1.2.0/node_modules/tannin/index.js ***!
  \**********************************************************************/
			/***/ function (
				__unused_webpack_module,
				__webpack_exports__,
				__webpack_require__
			) {
				'use strict';
				__webpack_require__.r(__webpack_exports__);
				/* harmony export */ __webpack_require__.d(
					__webpack_exports__,
					{
						/* harmony export */ default: function () {
							return /* binding */ Tannin;
						},
						/* harmony export */
					}
				);
				/* harmony import */ var _tannin_plural_forms__WEBPACK_IMPORTED_MODULE_0__ =
					__webpack_require__(
						/*! @tannin/plural-forms */ './node_modules/.pnpm/@tannin+plural-forms@1.1.0/node_modules/@tannin/plural-forms/index.js'
					);

				/**
				 * Tannin constructor options.
				 *
				 * @typedef {Object} TanninOptions
				 *
				 * @property {string}   [contextDelimiter] Joiner in string lookup with context.
				 * @property {Function} [onMissingKey]     Callback to invoke when key missing.
				 */

				/**
				 * Domain metadata.
				 *
				 * @typedef {Object} TanninDomainMetadata
				 *
				 * @property {string}            [domain]       Domain name.
				 * @property {string}            [lang]         Language code.
				 * @property {(string|Function)} [plural_forms] Plural forms expression or
				 *                                              function evaluator.
				 */

				/**
				 * Domain translation pair respectively representing the singular and plural
				 * translation.
				 *
				 * @typedef {[string,string]} TanninTranslation
				 */

				/**
				 * Locale data domain. The key is used as reference for lookup, the value an
				 * array of two string entries respectively representing the singular and plural
				 * translation.
				 *
				 * @typedef {{[key:string]:TanninDomainMetadata|TanninTranslation,'':TanninDomainMetadata|TanninTranslation}} TanninLocaleDomain
				 */

				/**
				 * Jed-formatted locale data.
				 *
				 * @see http://messageformat.github.io/Jed/
				 *
				 * @typedef {{[domain:string]:TanninLocaleDomain}} TanninLocaleData
				 */

				/**
				 * Default Tannin constructor options.
				 *
				 * @type {TanninOptions}
				 */
				var DEFAULT_OPTIONS = {
					contextDelimiter: '\u0004',
					onMissingKey: null,
				};

				/**
				 * Given a specific locale data's config `plural_forms` value, returns the
				 * expression.
				 *
				 * @example
				 *
				 * ```
				 * getPluralExpression( 'nplurals=2; plural=(n != 1);' ) === '(n != 1)'
				 * ```
				 *
				 * @param {string} pf Locale data plural forms.
				 *
				 * @return {string} Plural forms expression.
				 */
				function getPluralExpression(pf) {
					var parts, i, part;

					parts = pf.split(';');

					for (i = 0; i < parts.length; i++) {
						part = parts[i].trim();
						if (part.indexOf('plural=') === 0) {
							return part.substr(7);
						}
					}
				}

				/**
				 * Tannin constructor.
				 *
				 * @class
				 *
				 * @param {TanninLocaleData} data      Jed-formatted locale data.
				 * @param {TanninOptions}    [options] Tannin options.
				 */
				function Tannin(data, options) {
					var key;

					/**
					 * Jed-formatted locale data.
					 *
					 * @name Tannin#data
					 * @type {TanninLocaleData}
					 */
					this.data = data;

					/**
					 * Plural forms function cache, keyed by plural forms string.
					 *
					 * @name Tannin#pluralForms
					 * @type {Object<string,Function>}
					 */
					this.pluralForms = {};

					/**
					 * Effective options for instance, including defaults.
					 *
					 * @name Tannin#options
					 * @type {TanninOptions}
					 */
					this.options = {};

					for (key in DEFAULT_OPTIONS) {
						this.options[key] =
							options !== undefined && key in options
								? options[key]
								: DEFAULT_OPTIONS[key];
					}
				}

				/**
				 * Returns the plural form index for the given domain and value.
				 *
				 * @param {string} domain Domain on which to calculate plural form.
				 * @param {number} n      Value for which plural form is to be calculated.
				 *
				 * @return {number} Plural form index.
				 */
				Tannin.prototype.getPluralForm = function (domain, n) {
					var getPluralForm = this.pluralForms[domain],
						config,
						plural,
						pf;

					if (!getPluralForm) {
						config = this.data[domain][''];

						pf =
							config['Plural-Forms'] ||
							config['plural-forms'] ||
							// Ignore reason: As known, there's no way to document the empty
							// string property on a key to guarantee this as metadata.
							// @ts-ignore
							config.plural_forms;

						if (typeof pf !== 'function') {
							plural = getPluralExpression(
								config['Plural-Forms'] ||
									config['plural-forms'] ||
									// Ignore reason: As known, there's no way to document the empty
									// string property on a key to guarantee this as metadata.
									// @ts-ignore
									config.plural_forms
							);

							pf = (0,
							_tannin_plural_forms__WEBPACK_IMPORTED_MODULE_0__[
								'default'
							])(plural);
						}

						getPluralForm = this.pluralForms[domain] = pf;
					}

					return getPluralForm(n);
				};

				/**
				 * Translate a string.
				 *
				 * @param {string}      domain   Translation domain.
				 * @param {string|void} context  Context distinguishing terms of the same name.
				 * @param {string}      singular Primary key for translation lookup.
				 * @param {string=}     plural   Fallback value used for non-zero plural
				 *                               form index.
				 * @param {number=}     n        Value to use in calculating plural form.
				 *
				 * @return {string} Translated string.
				 */
				Tannin.prototype.dcnpgettext = function (
					domain,
					context,
					singular,
					plural,
					n
				) {
					var index, key, entry;

					if (n === undefined) {
						// Default to singular.
						index = 0;
					} else {
						// Find index by evaluating plural form for value.
						index = this.getPluralForm(domain, n);
					}

					key = singular;

					// If provided, context is prepended to key with delimiter.
					if (context) {
						key =
							context + this.options.contextDelimiter + singular;
					}

					entry = this.data[domain][key];

					// Verify not only that entry exists, but that the intended index is within
					// range and non-empty.
					if (entry && entry[index]) {
						return entry[index];
					}

					if (this.options.onMissingKey) {
						this.options.onMissingKey(singular, domain);
					}

					// If entry not found, fall back to singular vs. plural with zero index
					// representing the singular value.
					return index === 0 ? singular : plural;
				};

				/***/
			},

		/******/
	};
	/************************************************************************/
	/******/ // The module cache
	/******/ var __webpack_module_cache__ = {};
	/******/
	/******/ // The require function
	/******/ function __webpack_require__(moduleId) {
		/******/ // Check if module is in cache
		/******/ var cachedModule = __webpack_module_cache__[moduleId];
		/******/ if (cachedModule !== undefined) {
			/******/ return cachedModule.exports;
			/******/
		}
		/******/ // Create a new module (and put it into the cache)
		/******/ var module = (__webpack_module_cache__[moduleId] = {
			/******/ // no module.id needed
			/******/ // no module.loaded needed
			/******/ exports: {},
			/******/
		});
		/******/
		/******/ // Execute the module function
		/******/ __webpack_modules__[moduleId](
			module,
			module.exports,
			__webpack_require__
		);
		/******/
		/******/ // Return the exports of the module
		/******/ return module.exports;
		/******/
	}
	/******/
	/************************************************************************/
	/******/ /* webpack/runtime/compat get default export */
	/******/ !(function () {
		/******/ // getDefaultExport function for compatibility with non-harmony modules
		/******/ __webpack_require__.n = function (module) {
			/******/ var getter =
				module && module.__esModule
					? /******/ function () {
							return module['default'];
						}
					: /******/ function () {
							return module;
						};
			/******/ __webpack_require__.d(getter, { a: getter });
			/******/ return getter;
			/******/
		};
		/******/
	})();
	/******/
	/******/ /* webpack/runtime/define property getters */
	/******/ !(function () {
		/******/ // define getter functions for harmony exports
		/******/ __webpack_require__.d = function (exports, definition) {
			/******/ for (var key in definition) {
				/******/ if (
					__webpack_require__.o(definition, key) &&
					!__webpack_require__.o(exports, key)
				) {
					/******/ Object.defineProperty(exports, key, {
						enumerable: true,
						get: definition[key],
					});
					/******/
				}
				/******/
			}
			/******/
		};
		/******/
	})();
	/******/
	/******/ /* webpack/runtime/hasOwnProperty shorthand */
	/******/ !(function () {
		/******/ __webpack_require__.o = function (obj, prop) {
			return Object.prototype.hasOwnProperty.call(obj, prop);
		};
		/******/
	})();
	/******/
	/******/ /* webpack/runtime/make namespace object */
	/******/ !(function () {
		/******/ // define __esModule on exports
		/******/ __webpack_require__.r = function (exports) {
			/******/ if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
				/******/ Object.defineProperty(exports, Symbol.toStringTag, {
					value: 'Module',
				});
				/******/
			}
			/******/ Object.defineProperty(exports, '__esModule', {
				value: true,
			});
			/******/
		};
		/******/
	})();
	/******/
	/************************************************************************/
	var __webpack_exports__ = {};
	// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
	!(function () {
		'use strict';
		/*!******************************************!*\
  !*** ./assets/src/js/public/checkout.js ***!
  \******************************************/
		__webpack_require__.r(__webpack_exports__);
		/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__ =
			__webpack_require__(
				/*! @babel/runtime/helpers/asyncToGenerator */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js'
			);
		/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__ =
			__webpack_require__(
				/*! @babel/runtime/regenerator */ './node_modules/.pnpm/@babel+runtime@7.28.3/node_modules/@babel/runtime/regenerator/index.js'
			);
		/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default =
			/*#__PURE__*/ __webpack_require__.n(
				_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1__
			);
		/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ =
			__webpack_require__(
				/*! @wordpress/i18n */ './node_modules/.pnpm/@wordpress+i18n@6.2.0/node_modules/@wordpress/i18n/build-module/index.js'
			);

		(function ($) {
			window.addEventListener('load', function () {
				// Update checkout pricing on product item change
				var checkout_price_item = $('.atbdp-checkout-price-item');
				checkout_price_item.on('change', function () {
					var checkout_net_price_area = $(
						'#atbdp_checkout_total_amount'
					);
					var checkout_net_hidden_price_area = $(
						'#atbdp_checkout_total_amount_hidden'
					);
					var pricing_statement =
						get_pricing_statement(checkout_price_item);
					checkout_net_price_area.html(
						get_currency_format(pricing_statement.total_price)
					);
					checkout_net_hidden_price_area.val(
						pricing_statement.total_price
					);
					update_payment_methods(pricing_statement);
				});

				// get_pricing_statement
				function get_pricing_statement(price_item_elm) {
					var total_price = 0;
					var total_product = 0;
					price_item_elm.each(function (index) {
						var price_item = price_item_elm[index];
						var price = price_item.value;
						price = isNaN(price_item.value) ? 0 : Number(price);
						if ($(price_item).is(':checked')) {
							total_price = total_price + price;
							total_product++;
						}
					});
					return {
						total_product: total_product,
						total_price: total_price,
					};
				}

				// update_payment_methods
				function update_payment_methods(pricing_statement) {
					if (!pricing_statement.total_product) {
						$(
							'#directorist_payment_gateways, #atbdp_checkout_submit_btn'
						).hide();
						return;
					}
					if (pricing_statement.total_price > 0) {
						$('#directorist_payment_gateways').show();
						$('#atbdp_checkout_submit_btn')
							.val(directorist.payNow)
							.show();
						$('#atbdp_checkout_submit_btn_label').val(
							directorist.payNow
						);
					} else {
						$('#directorist_payment_gateways').hide();
						$('#atbdp_checkout_submit_btn')
							.val(directorist.completeSubmission)
							.show();
						$('#atbdp_checkout_submit_btn_label').val(
							directorist.completeSubmission
						);
					}
				}

				// Helpers
				// --------------------
				// get_currency_format
				function get_currency_format(number) {
					number = number.toFixed(2);
					number = number_with_commas(number);
					return number;
				}

				// number_with_commas
				function number_with_commas(number) {
					return number
						.toString()
						.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
				}
				function get_error_message(error) {
					var message = '';
					if (error.message) {
						message = error.message;
					} else if (error.messages) {
						var messages = Object.values(error.messages);
						if (messages.length > 0) {
							message = messages[0][0];
						}
					}
					return message;
				}
				$('#atbdp-checkout-form').on(
					'submit',
					/*#__PURE__*/ (function () {
						var _ref = (0,
						_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_0__[
							'default'
						])(
							/*#__PURE__*/ _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default().mark(
								function _callee(e) {
									var submitBtn,
										btnText,
										btnSpinner,
										originalText,
										formData,
										data,
										response,
										message,
										_t;
									return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_1___default().wrap(
										function (_context) {
											while (1)
												switch (
													(_context.prev =
														_context.next)
												) {
													case 0:
														e.preventDefault();

														// Show loading state
														submitBtn = $(
															'#atbdp_checkout_submit_btn'
														);
														btnText =
															submitBtn.find(
																'.directorist-btn-text'
															);
														btnSpinner =
															submitBtn.find(
																'.directorist-btn-spinner'
															);
														originalText =
															btnText.text();
														submitBtn.prop(
															'disabled',
															true
														);
														btnText.text(
															submitBtn.data(
																'loading-text'
															)
														);
														btnSpinner.show();
														formData = new FormData(
															this
														);
														data =
															Object.fromEntries(
																formData
															);
														_context.prev = 1;
														_context.next = 2;
														return wp.apiFetch({
															path: '/directorist/checkout',
															method: 'POST',
															data: data,
														});
													case 2:
														response =
															_context.sent;
														if (
															response.redirect_url
														) {
															window.location.href =
																response.redirect_url;
														}
														_context.next = 4;
														break;
													case 3:
														_context.prev = 3;
														_t =
															_context['catch'](
																1
															);
														message =
															get_error_message(
																_t
															);
														setTimeout(function () {
															wp.hooks.doAction(
																'directorist-toast',
																{
																	message:
																		message ||
																		(0,
																		_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)(
																			'An error occurred',
																			'directorist'
																		),
																	type: 'error',
																}
															);
														}, 500);
														console.log(
															'Error message:',
															_t
														);

														// Reset loading state on error
														submitBtn.prop(
															'disabled',
															false
														);
														btnText.text(
															originalText
														);
														btnSpinner.hide();
													case 4:
													case 'end':
														return _context.stop();
												}
										},
										_callee,
										this,
										[[1, 3]]
									);
								}
							)
						);
						return function (_x) {
							return _ref.apply(this, arguments);
						};
					})()
				);
			});
		})(jQuery);
	})();
	/******/
})();
//# sourceMappingURL=checkout.js.map
