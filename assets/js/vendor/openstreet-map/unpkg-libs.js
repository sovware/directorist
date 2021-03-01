/**
 * Created by user on 29-May-19.
 */
/**
 * Copyright (c) 2017 Boris Seang
 * Manage Libs Versions
 * ISC License
 */
// Adapted from Rollup
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined'
        ? factory(exports)
        : typeof define === 'function' && define.amd
            ? define(['exports'], factory)
            : (factory((global.manageLibsVersions = {})));
}(this, (function (exports) {
    'use strict';

    /**
     * Create a new Bundle that contains a set of interdependent Libraries.
     * BundleOptions is an Object with the following expected keys:
     * - name? <String> mandatory in case BundleOptions is passed as first argument.
     * - libs? <Array<LibSpec|LibOptions>>
     * @param bundleName <String|BundleOptions>
     * @param options? <BundleOptions>
     * @constructor
     */
    function LibsBundle(bundleName, options) {
        if (typeof bundleName !== 'string') {
            options = bundleName;
            bundleName = options.name;
        }
        this.name = bundleName;
        this.libs = [];
        this.options = options = options || {};

        var self = this,
            libsData = options.libs || [];

        libsData.forEach(function (libData) {
            self.addLib(libData);
        });
    }

    /**
     * Add a Library into the current Bundle.
     * @param libName <string|Library|LibOptions>
     * @param options? <LibOptions>
     * @returns {LibSpec}
     */
    LibsBundle.prototype.addLib = function (libName, options) {
        var libSpec = (libName instanceof LibSpec)
            ? libName
            : new LibSpec(libName, options);

        libSpec.bundle = this;
        this.libs.push(libSpec);

        return libSpec;
    };

    /**
     * Get the named Library.
     * @param libName <String>
     * @returns {LibSpec|undefined}
     */
    LibsBundle.prototype.getLib = function (libName) {
        var libSpecs = this.libs,
            lib_i = 0;

        for (; lib_i < libSpecs.length; lib_i += 1) {
            if (libSpecs[lib_i].name === libName) {
                return libSpecs[lib_i];
            }
        }

        console.warn('Could not find requested library "' + libName + '"');
    };

    /**
     * Get the named Version of the named Library.
     * @param libName <String>
     * @param versionName <String>
     */
    LibsBundle.prototype.getLibVersion = function (libName, versionName) {
        var libSpec = this.getLib(libName);

        if (libSpec) {
            return libSpec.getVersionFallback(versionName);
        }

        console.warn('Could not find requested library "' + libName + '" and version "' + versionName + '"');
    };

    /**
     * Make Radio Inputs for the list of named Library Versions.
     * @param libName <String>
     * @param versionsNames <String[]>
     * @param fragment? <Node|DocumentFragment>
     * @returns {undefined|Node|DocumentFragment}
     */
    LibsBundle.prototype.makeLibVersionsRadios = function (libName, versionsNames, fragment) {
        var libSpec = this.getLib(libName);

        if (libSpec) {
            fragment = libSpec.makeVersionsRadios(versionsNames, fragment);
        }

        return fragment;
    };

    /**
     * For each Bundle Library, execute fillPlaceholders() method.
     * Look for placeholders with `data-manage-lib` attribute which value is
     * equal to the Library name, and insert Radio Input for each Version
     * specified in `data-manage-versions` attribute.
     */
    LibsBundle.prototype.fillPlaceholders = function () {
        this.libs.forEach(function (libSpec) {
            libSpec.fillPlaceholders();
        });
    };

    /**
     * Make and insert Radio Inputs for the list of named Library Versions,
     * after the script Element where this method is called.
     * @param libName <String>
     * @param versionsNames <String[]>
     * @param fragment? <Node|DocumentFragment>
     */
    LibsBundle.prototype.insertLibVersionsRadioHere = function (libName, versionsNames, fragment) {
        fragment = this.makeLibVersionsRadios(libName, versionsNames, fragment);
        _insertFragmentHere(fragment);
    };

    /**
     * Retrieve the user selected (checked) Radio Inputs, in order to determine
     * which Library Versions are requested.
     * @returns {{[Library.name]: Version.name}}
     */
    LibsBundle.prototype.readSelectedVersionsNames = function () {
        var selectedVersionsNames = {};

        this.libs.forEach(function (libSpec) {
            selectedVersionsNames[libSpec.name] = libSpec.readSelectedVersionName();
        });

        return selectedVersionsNames;
    };

    /**
     * Extract the list of AssetSpecs for the requested Library Versions,
     * and select (check) the corresponding Radio Inputs.
     * Used typically to display the requested Libraries Versions and prepare the list
     * of assets to be loaded (with loadJsCss external library).
     * @param libVersionsDict <{[Library.name]: Version.name}>
     * @returns {Array<AssetSpec>}
     */
    LibsBundle.prototype.getAndSelectVersionsAssetsList = function (libVersionsDict) {
        var libSpecs = this.libs,
            lib_i = 0,
            assetsLoadList = [],
            libSpec, versionName, versionSpec;

        for (; lib_i < libSpecs.length; lib_i += 1) {
            libSpec = libSpecs[lib_i];
            versionName = libVersionsDict[libSpec.name];
            versionSpec = libSpec.getAndSelectVersionSpec(versionName);
            assetsLoadList = assetsLoadList.concat(versionSpec.assets);
        }

        return assetsLoadList;
    };


    /**
     * Create a new Library that contains a set of Versions.
     * LibOptions is an Object with the following expected keys:
     * - name? <String> mandatory if LibOptions is passed as first argument.
     * - mandatory? <Boolean> if true, one of the Versions must be set as defaultVersion.
     * - versions? <Array<VersionSpec|VersionOptions>>
     * @param libName <String|LibOptions>
     * @param options? <LibOptions>
     * @constructor
     */
    function LibSpec(libName, options) {
        if (typeof libName !== 'string') {
            options = libName;
            libName = options.name;
        }
        this.name = libName;
        this.options = options = options || {};
        this.versions = [];
        this.mandatory = options.mandatory;

        var self = this,
            versionsData = options.versions || [];

        versionsData.forEach(function (versionData) {
            self.addVersion(versionData);
        });
    }

    /**
     * Add a Version into the current Library.
     * @param versionName <String|VersionOptions>
     * @param options? <VersionOptions>
     * @returns {VersionSpec}
     */
    LibSpec.prototype.addVersion = function (versionName, options) {
        var versionSpec = (versionName instanceof VersionSpec)
            ? versionName
            : new VersionSpec(versionName, options);

        versionSpec.libSpec = this;
        this.versions.push(versionSpec);

        return versionSpec;
    };

    LibSpec.prototype.makeVersionsRadios = function (versionsNames, fragment) {
        var self = this,
            afterFirst = false;

        versionsNames.forEach(function (versionName) {
            var versionSpec = self.getVersion(versionName);

            if (versionSpec) {
                if (afterFirst) {
                    if (fragment) {
                        fragment.appendChild(document.createElement('br'));
                    }
                } else {
                    afterFirst = true;
                }
                fragment = versionSpec.makeRadio(fragment);
            }
        });

        return fragment;
    };

    /**
     * Look for placeholders in the document where `data-manage-lib` attribute
     * value is equal to the current Library name, retrieve the Versions names
     * specified in the `data-manage-versions` attribute, then create their
     * Radio Input and insert them in those placeholders.
     * `data-manage-versions` is a list of Version names, separated by at least
     * one or a combination of: white space, comma (,), semi-colon (;).
     */
    LibSpec.prototype.fillPlaceholders = function () {
        var self = this,
            selector = '[data-manage-lib="' + this.name + '"]';

        document.querySelectorAll(selector).forEach(function (placeholder) {
            var versionsNames = placeholder.dataset.manageVersions;

            versionsNames = versionsNames.split(/[ ,;]+/);

            placeholder.appendChild(self.makeVersionsRadios(versionsNames));
        });
    };

    /**
     * Retrieve the user selected (checked) Radio Input, in order to determine
     * which Version of the current Library is requested.
     */
    LibSpec.prototype.readSelectedVersionName = function () {
        var inputName = _makeLibInputGroupName(this);

        return _getSelectedRadioValue(inputName);
    };

    /**
     * Get the named Version, and also select (check) the associated Radio Input.
     * Used typically to display the requested Version and prepare the list of
     * assets to be loaded.
     * @param versionName <String>
     * @returns {VersionSpec}
     */
    LibSpec.prototype.getAndSelectVersionSpec = function (versionName) {
        var versionSpec = this.getVersionFallback(versionName);

        if (versionSpec) {
            versionSpec.selectRadio();
            return versionSpec;
        }

        this.selectRadioNone();

        // If no version spec found, return a dummy one.
        return {
            assets: []
        };
    };

    /**
     * Make a Radio Input that correspond to no Version.
     * Should not be used when the Library is set as mandatory.
     * @param fragment? <Node|DocumentFragment>
     * @returns {Node|DocumentFragment}
     */
    LibSpec.prototype.makeRadioNone = function (fragment) {
        fragment = fragment || document.createDocumentFragment();

        var attrs = this.mandatory ? _makeDisabledVersionInputAttributes() : null;

        _makeRadioInputAndLabel(this, '(none)', fragment, attrs);

        this.radioNoneElement = fragment.lastChild.previousSibling;

        return fragment;
    };

    /**
     * Select (check) the Radio Input that corresponds to no Version.
     */
    LibSpec.prototype.selectRadioNone = function () {
        if (this.radioNoneElement) {
            this.radioNoneElement.checked = true;
        }
    };

    /**
     * Get the named Version, or the default one if the Library is set as
     * mandatory and no or an incorrect version name is passed.
     * @param versionName <String>
     * @returns {Version|undefined}
     */
    LibSpec.prototype.getVersionFallback = function (versionName) {
        // If no version string is provided.
        if (typeof versionName !== 'string' || versionName === '(none)') {
            // If library is configured as mandatory, load the default version.
            if (this.mandatory) {
                console.warn('No version requested for mandatory library "' + this.name + '".');
                return this.getDefaultVersion();
            }
            return;
        }

        // Load the requested version.
        var libVersionSpec = this.getVersion(versionName);

        if (libVersionSpec) {
            return libVersionSpec;
        } else {
            console.warn('Requested library "' + this.name + '" with version "' + versionName + '", but not found in configuration.');
            return this.getDefaultVersion();
        }
    };

    /**
     * Get the named Version exactly.
     * If no match is found, returns undefined.
     * @param versionName <String>
     * @returns {Version|undefined}
     */
    LibSpec.prototype.getVersion = function (versionName) {
        var versionSpecs = this.versions || [],
            version_i = 0;

        for (; version_i < versionSpecs.length; version_i += 1) {
            if (versionSpecs[version_i].name === versionName) {
                return versionSpecs[version_i];
            }
        }
    };

    /**
     * Get the default Version.
     * In case several Versions are set as defaultVersion, the first one is returned.
     * @returns {Version|undefined}
     */
    LibSpec.prototype.getDefaultVersion = function () {
        var libDefaultVersionSpec = this._getDefaultVersion();

        if (libDefaultVersionSpec) {
            console.log('Using default configuration (version: "' + libDefaultVersionSpec.name + '").');

            var onDefault = this.options.onDefault;

            if (onDefault && typeof onDefault === 'function') {
                onDefault.call(this);
            }
            return libDefaultVersionSpec;
        } else {
            console.warn('No default configuration specified.');

            var onError = this.options.onError;

            if (onError && typeof onError === 'function') {
                onError.call(this);
            }
        }
    };

    LibSpec.prototype._getDefaultVersion = function () {
        var versionSpecs = this.versions || [],
            version_i = 0;

        for (; version_i < versionSpecs.length; version_i += 1) {
            if (versionSpecs[version_i].defaultVersion) {
                return versionSpecs[version_i];
            }
        }
    };


    /**
     * Create a Version that contains Assets.
     * VersionOptions is an Object with the following expected keys:
     * - name? <String> mandatory in case VersionOptions is passed as first argument.
     * - defaultVersion? <Boolean> if true, this Version is used as the Library default Version.
     * - disabled? <Boolean> if true, the built Radio Input will be disabled. defaultVersion should not be true.
     * - assets? <Array<AssetSpec|AssetOption>>
     * @param versionName <String|VersionOptions>
     * @param options? <VersionOptions>
     * @constructor
     */
    function VersionSpec(versionName, options) {
        if (typeof versionName !== 'string') {
            options = versionName;
            versionName = options.name;
        }
        this.name = versionName;
        this.assets = [];
        this.options = options = options || {};
        this.defaultVersion = options.defaultVersion;
        this.disabled = options.disabled;

        var self = this,
            assetsData = options.assets || [];

        assetsData.forEach(function (assetData) {
            self.addAsset(assetData);
        });
    }

    /**
     * Add an Asset to the current Version.
     * @param assetSpec <AssetSpec|AssetOptions>
     * @returns {Version}
     */
    VersionSpec.prototype.addAsset = function (assetSpec) {
        var assetSpec = (assetSpec instanceof AssetSpec)
            ? assetSpec
            : new AssetSpec(assetSpec);

        this.assets.push(assetSpec);

        return this;
    };

    /**
     * Test the availability of each Asset in the current Version.
     * If automaticallyEnableOrDisable is true, it will enable the associated
     * Radio Input if all Assets are found. If any is missing, it will disable
     * it and flag the Version as non default.
     * @param automaticallyEnableOrDisable <Boolean>
     * @returns {Promise}
     */
    VersionSpec.prototype.checkAssetsAvailability = function (automaticallyEnableOrDisable) {
        var checks = [],
            self = this;

        this.assets.forEach(function (assetSpec) {
            checks.push(_checkFileAvailability(assetSpec.path));
        });

        var all = Promise.all(checks);

        if (automaticallyEnableOrDisable) {
            all
                .then(function () {
                    self.setDisabled(false);
                })
                .catch(function () {
                    self.defaultVersion = false;
                    self.setDisabled(true);
                });
        }

        return all;
    };

    /**
     * Disable the Version associated Radio Input, typically when the Version is not available
     * or incompatible with other Libraries.
     * @param disabled? <Boolean> if true, disables the associated Radio Input element.
     */
    VersionSpec.prototype.setDisabled = function (disabled) {
        disabled = !!disabled;
        this.disabled = disabled;

        var radio = this.radioElement;

        if (radio) {
            _disableElement(radio, disabled);

            var label = radio.nextSibling;

            if (label) {
                _disableElement(label, disabled);
            }
        }
    };

    /**
     * Make a Radio Input associated with the current Library Version.
     * @param fragment? <Node|DocumentFragment>
     * @returns {Node|DocumentFragment}
     */
    VersionSpec.prototype.makeRadio = function (fragment) {
        fragment = fragment || document.createDocumentFragment();

        var attrs = this.disabled ? _makeDisabledVersionInputAttributes() : null;

        _makeRadioInputAndLabel(this.libSpec, this.name, fragment, attrs);

        this.radioElement = fragment.lastChild.previousSibling;

        return fragment;
    };

    /**
     * Select (check) the Radio Input associated with the current Library Version.
     * Used typically to display the requested Version.
     */
    VersionSpec.prototype.selectRadio = function () {
        if (this.radioElement) {
            this.radioElement.checked = true;
        }
    };


    /**
     * Create an Asset that represents a Resource to be loaded.
     * AssetOptions is an Object with the following expected keys:
     * - type <'script'|'stylesheet'> type of Resource (JS or CSS).
     * - path <String> URL from where to load the Resource.
     * - attrs <{[attributeName]: attributeValue}> dictionary of
     *      attributes to apply to the Resource HTML Tag (typically
     *      for "integrity" and "crossorigin").
     * @param spec <AssetOptions>
     * @constructor
     */
    function AssetSpec(spec) {
        this.type = spec.type;
        this.path = spec.path;
        this.attrs = spec.attrs || {};
    }

    function _insertFragmentHere(fragment) {
        var currentScriptParent = document.currentScript.parentNode;
        currentScriptParent.appendChild(fragment);
    }

    function _makeLibInputGroupName(libSpec) {
        return '_manageLibsVersions_' + libSpec.bundle.name + '_' + libSpec.name;
    }

    function _getSelectedRadioValue(radioName) {
        var radios = document.getElementsByName(radioName),
            i = 0;

        for (; i < radios.length; i += 1) {
            if (radios[i].checked) {
                return radios[i].value;
            }
        }
    }

    function _makeRadioInputAndLabel(libSpec, versionName, fragment, attrs) {
        var groupName = _makeLibInputGroupName(libSpec);

        var input = document.createElement('input');
        input.type = 'radio';
        input.name = groupName;
        input.value = versionName;
        input.id = groupName + versionName;
        if (attrs) {
            _setAttributes(input, attrs);
        }
        fragment.appendChild(input);

        var label = document.createElement('label');
        label.setAttribute('for', input.id);
        label.innerText = versionName;
        if (attrs) {
            _setAttributes(label, attrs);
        }
        fragment.appendChild(label);

        return fragment;
    }


    /**
     * Utility function to easily make AssetOptions out of a pathTemplate
     * and a versionName (typically for CDN URL that includes version, like
     * for unpkg, cdnjs or jsDelivr).
     * Type "stylesheet" (CSS).
     * @param pathTemplate <String> including "{{VESION}}" placeholder.
     * @param version <String> will replace the "{{VESION}}" placeholder in the above template.
     * @param sri? <String> Sub Resource Integrity hash
     */
    function makeStylesheet(pathTemplate, version, sri) {
        return _makeAssetSpec('stylesheet', pathTemplate, version, sri);
    }

    /**
     * Utility function to easily make AssetOptions out of a pathTemplate
     * and a versionName (typically for CDN URL that includes version, like
     * for unpkg, cdnjs or jsDelivr).
     * Type "script" (JS).
     * @param pathTemplate <String> including "{{VESION}}" placeholder.
     * @param version <String> will replace the "{{VESION}}" placeholder in the above template.
     * @param sri? <String> Sub Resource Integrity hash
     */
    function makeScript(pathTemplate, version, sri) {
        return _makeAssetSpec('script', pathTemplate, version, sri);
    }

    function _makeAssetSpec(type, pathTemplate, version, sri) {
        var assetSpec = {
            type: type,
            path: _makePath(pathTemplate, version)
        };

        return _addSri(assetSpec, sri);
    }

    function _makePath(pathTemplate, version) {
        return _replaceAll(pathTemplate, '{{VERSION}}', version)
    }

    function _addSri(assetSpec, integrity, crossorigin) {
        assetSpec.attrs = assetSpec.attrs || {};

        if (integrity) {
            assetSpec.attrs.integrity = integrity;
            assetSpec.attrs.crossorigin = crossorigin || '';
        }

        return assetSpec;
    }

    function _replaceAll(target, search, replacement) {
        return target.split(search).join(replacement);
    }

    function _setAttributes(element, attributes) {
        for (var attributeName in attributes) {
            if (attributes.hasOwnProperty(attributeName)) {
                element.setAttribute(attributeName, attributes[attributeName]);
            }
        }
    }

    function _addClassName(element, className, add) {
        if (element) {
            if (typeof add === 'undefined') {
                add = true;
            }
            element.classList[add ? 'add' : 'remove'](className);
        }
    }

    function _makeDisabledVersionInputAttributes() {
        return {
            disabled: 'disabled',
            class: 'version-disabled',
            title: 'This version is disabled'
        }
    }

    function _disableElement(element, disable) {
        if (element) {
            if (typeof disable === 'undefined') {
                disable = true;
            }
            disable = !!disable;
            element.disabled = disable;
            _addClassName(element, 'version-disabled', disable);
            element.setAttribute('title', disable ? 'This version is disabled' : '');
        }
    }

    function _checkFileAvailability(filePath) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();

            xhr.open('HEAD', filePath);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        resolve();
                    } else {
                        // xhr.status === 404 => file not found.
                        reject(xhr.status);
                    }
                }
            };
            xhr.send();
        });
    }


    exports.Bundle = LibsBundle;
    exports.Lib = LibSpec;
    exports.Version = VersionSpec;
    exports.Asset = AssetSpec;

    exports.makeScript = makeScript;
    exports.makeStylesheet = makeStylesheet;
})));
