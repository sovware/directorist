/**
 * Created by user on 29-May-19.
 */
/**
 * Copyright (c) 2017 Boris Seang
 * Load JS CSS
 * ISC License
 */
// Adapted from Rollup
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined'
        ? factory(exports)
        : typeof define === 'function' && define.amd
            ? define(['exports'], factory)
            : (factory((global.loadJsCss = {})));
}(this, (function (exports) {
    'use strict';

    /**
     * Load the list of resources.
     * ResourceSpec is a JsAssetSpec or a CssAssetSpec with expected following extra key(s):
     * - type <'script'|'stylesheet'> string specifying whether the asset is a (CSS) stylesheet or a (JS) script.
     * OptionsDict is a Dictionary with possible following key(s):
     * - delayScripts <Number|false> duration after which scripts should be loaded (after stylesheets), typically when
     *     script execution depends on stylesheet being already applied.
     * @param resources <ResourceSpec[]>
     * @param options <OptionsDict>
     */
    function loadList(resources, options) {
        options = options || {};

        var i = 0,
            delayScripts = options.delayScripts || false,
            scripts = [],
            resource;

        for (; i < resources.length; i += 1) {
            resource = resources[i];

            switch (resource.type && resource.type.toLowerCase()) {
                case 'js':
                case 'javascript':
                case 'script':
                    if (delayScripts === false) {
                        loadJs(resource);
                    } else {
                        scripts.push(resource);
                    }
                    break;
                case 'css':
                case 'stylesheet':
                    loadCss(resource);
                    break;
                default:
                    console.error('Could not determine type of resource: ' + JSON.stringify(resource));
            }
        }

        if (scripts.length) {
            setTimeout(function () {
                options.delayScripts = false;
                loadList(scripts, options);
            }, delayScripts);
        }
    }

    /**
     * Load a (JavaScript) script asset.
     * options is a JsAssetSpec.
     * JsAssetSpec is an Object with expected following keys:
     * - src <String> URL where to fetch the asset from.
     * - async? <Boolean> if true, the script will be executed in any order; if false, it will be executed in insertion order.
     * - attrs? <AttributesDict> Dictionary of attributes (e.g. to specify SRI - integrity and crossorigin).
     * Adapted from load-script (https://github.com/eldargab/load-script, MIT License).
     * @param options <JsAssetSpec>
     */
    function loadJs(options) {
        var script = document.createElement('script'),
            src = options.src || options.path || options.href,
            cb = options.callback;

        // Use async=false by default to emulate defer and get a more predictable behaviour (i.e. scripts executed in order).
        script.async = !!options.async || false;
        script.src = src;

        // options.attrs can be used for SRI for example (integrity, crossorigin).
        if (options.attrs) {
            _setAttributes(script, options.attrs)
        }

        if (typeof cb === 'function') {
            var onEnd = 'onload' in script ? _stdOnEnd : _ieOnEnd;

            onEnd(script, cb);

            // some good legacy browsers (firefox) fail the 'in' detection above
            // so as a fallback we always set onload
            // old IE will ignore this and new IE will set onload
            if (!script.onload) {
                _stdOnEnd(script, cb);
            }
        }

        _getHead().appendChild(script);
    }

    /**
     * Load a (CSS) stylesheet asset.
     * options is a CssAssetSpec.
     * CssAssetSpec is an Object with expected following keys:
     * - href <String> URL where to fetch the asset from.
     * - attrs? <AttributesDict> Dictionary of attributes (e.g. to specify SRI - integrity and crossorigin).
     * @param options <CssAssetSpec>
     */
    function loadCss(options) {
        var link = document.createElement('link'),
            href = options.href || options.path || options.src,
            cb = options.callback;

        link.rel = 'stylesheet';
        link.href = href;

        // options.attrs can be used for SRI for example (integrity, crossorigin).
        if (options.attrs) {
            _setAttributes(link, options.attrs)
        }

        _getHead().appendChild(link);

        // Workaround the absence of error event on <link> resources.
        if (typeof cb === 'function') {
            var img = document.createElement('img');

            img.onerror = function (e) {
                cb(e, link);
            };
            img.onload = function () {
                cb(null, link);
            };
            img.src = href;
        }
    }

    function _setAttributes(element, attributes) {
        for (var attributeName in attributes) {
            if (attributes.hasOwnProperty(attributeName)) {
                element.setAttribute(attributeName, attributes[attributeName]);
            }
        }
    }


    // Adapted from load-script
    function _stdOnEnd (script, cb) {
        script.onload = function () {
            this.onerror = this.onload = null;
            cb(null, script)
        };
        script.onerror = function () {
            // this.onload = null here is necessary
            // because even IE9 works not like others
            this.onerror = this.onload = null;
            cb(new Error('Failed to load ' + this.src), script)
        }
    }

    // Adapted from load-script
    function _ieOnEnd (script, cb) {
        script.onreadystatechange = function () {
            if (this.readyState !== 'complete' && this.readyState !== 'loaded') {
                return;
            }
            this.onreadystatechange = null;
            cb(null, script) // there is no way to catch loading errors in IE8
        }
    }

    function _getHead() {
        // This library assumes it is loaded in a browser, hence `document` is defined.
        return document.head || document.getElementsByTagName('head')[0];
    }


    // Exports
    exports.js = loadJs;
    exports.css = loadCss;
    exports.list = loadList;
})));
