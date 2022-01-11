import lazyCheckCore from "./lazy-check-core";
import utility from "./utility";

const lazyCheck = function(userArgs) {
  const _defaultArgs = {
    modalTitle: '',
    containerClass: '',
    showMorelabel: 'Show More',
    showMoreLinkClass: '',
    ajax: {
      url: '',
      maxInitItems: 5,
      getPreselectedItemsID: () => [],
      data: params => params,
      processResults: ( response ) => response,
      template: ( item, headers ) => {
        return `<div class="lazy-check-item-wrap">
          <input type="checkbox" name="field[]" value="" id="${item.randomID}">
          <label for="${item.randomID}">${item.name}</label>
        </div>`;
      },
      loadingIndicator: 'Loading...',
      loadMoreText: 'Load more',
    },
  };

  this.args = lazyCheckCore.parseArgs(userArgs, _defaultArgs);

  /**
   * Init
   *
   * @returns void
   */
  this.init = () => {
    if (!this.args.containerClass) return;

    const rootElements = document.querySelectorAll(
      "." + this.args.containerClass
    );

    if (!rootElements.length) {
      utility.sendDebugLog("Container Found", rootElement);
      return;
    }

    // Enable Lazy Checks to each root element
    for (const elm of rootElements) {
      lazyCheckCore.enableLazyChecks(elm, this.args);
    }
  };
};

export default lazyCheck;
