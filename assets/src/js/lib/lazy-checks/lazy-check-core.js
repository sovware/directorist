import utility from './utility';

const lazyCheckCore = {
  args: {},

  parseArgs: function(userArgs, defaultArgs) {
    let args = { ...defaultArgs, ...userArgs };

    // Validate AJAX argument
    // --------------------------
    if ( args.ajax && ! Array.isArray(args.ajax) && typeof args.ajax === 'object' ) {
      args.ajax = { ...defaultArgs.ajax, ...args.ajax };
    } else {
      args.ajax = defaultArgs.ajax;
    }

    if ( typeof args.ajax.maxInitItems !== 'number' ) {
      args.ajax.maxInitItems = parseInt( defaultArgs.ajax.maxInitItems );
    }

    if ( args.ajax.maxInitItems < 1 ) {
      args.ajax.maxInitItems = 1;
    }

    if ( typeof args.ajax.getPreselectedItemsID !== 'function' ) {
      args.ajax.getPreselectedItemsID = defaultArgs.ajax.getPreselectedItemsID;
    }

    if ( typeof args.ajax.data !== 'function' ) {
      args.ajax.data = defaultArgs.ajax.data;
    }

    if ( typeof args.ajax.processResults !== 'function' ) {
      args.ajax.processResults = defaultArgs.ajax.processResults;
    }

    if ( typeof args.ajax.template !== 'function' ) {
      args.ajax.template = defaultArgs.ajax.template;
    }

    if ( typeof args.ajax.loadingIndicator !== 'string' ) {
      args.ajax.loadingIndicator = defaultArgs.ajax.loadingIndicator;
    }

    if ( typeof args.ajax.loadMoreText !== 'string' ) {
      args.ajax.loadMoreText = defaultArgs.ajax.loadMoreText;
    }

    return args;
  },

  enableLazyChecks: async function(rootContainer, args) {
    this.args = args;

    // Attach ID to root element
    const id = utility.generateRandom(100, 999);
    rootContainer.setAttribute('data-lazy-check-root-element-id', id);

    // Attach current page number to root element
    rootContainer.setAttribute('data-lazy-check-current-page', 0);

    // Prepare Root Element
    this.prepareRootElement(id);

    // Add Loading Indicator To Root Container
    this.addLoadingIndicatorToRootContainer( rootContainer );

    // Load initial data
    const initData = await this.fetchData({ id });

    // Remove Loading Indicator From Root Container
    this.removeLoadingIndicatorFromRootContainer( rootContainer );

    // Insert items to the DOM
    if ( initData.success ) {
      this.insertInitItemsToDOM({ rootContainer, items: initData.data.template });
    }
  },

  insertInitItemsToDOM: function({ rootContainer, items }) {
    if ( ! items.length ) {
      return;
    }

    const maxInitItems = this.args.ajax.maxInitItems;
    const rootItems    = items.slice( 0, maxInitItems );
    const modalItems   = items.slice( maxInitItems );

    if ( rootItems.length ) {
      let itemsContainer = rootContainer.querySelector( '.lazy-check-items-container' );

      rootItems.map(item => {
        let itemElementWrap = document.createElement('div');
        itemElementWrap.innerHTML = item;

        const itemElement = itemElementWrap.querySelector( '.lazy-check-item-wrap' );

        if ( itemElement ) {
          itemElement.classList.add('init-item');
          itemsContainer.appendChild( itemElement );
        }
      });
    }

    const hasNextPage = this.hasNextPage( rootContainer );

    if ( modalItems.length || hasNextPage ) {
      this.insertShowMoreLink( rootContainer );
    }

    if ( modalItems.length ) {
      const id = rootContainer.getAttribute('data-lazy-check-root-element-id');
      let modalContainer = document.querySelector(
        `[data-lazy-check-modal-id='${id}']`
      );

      if ( ! modalContainer ) {
        modalContainer = this.insertModal( id );
      }

      const itemsContainer = modalContainer.querySelector( '.lazy-check-modal-fields' );
      modalItems.map(item => {
        let itemElementWrap = document.createElement('div');
        itemElementWrap.innerHTML = item;

        const itemElement = itemElementWrap.querySelector( '.lazy-check-item-wrap' );

        if ( itemElement ) {
          const migratedItemElement = this.migrateInputIDsForModal( itemElement );
          itemsContainer.appendChild( migratedItemElement );
        }
      });
    }
  },

  insertShowMoreLink: function( rootContainer ) {
    let showMoreArea = rootContainer.querySelector(
      '.lazy-check-show-more-area'
    );

    if ( ! showMoreArea ) {
      return;
    }

    // Create Show More Link
    const showMoreLink = document.createElement('a');
    showMoreLink.setAttribute('href', '#');
    showMoreLink.classList = `lazy-check-show-more ${this.args.showMoreLinkClass}`;
    showMoreLink.innerHTML = this.args.showMorelabel;

    showMoreArea.appendChild(showMoreLink);

    // Enable Show More Link
    showMoreLink.addEventListener('click', event =>
      this.showModal(event, rootContainer)
    );
  },

  prepareRootElement: function(id) {
    const rootContainer = this.getRootContainerByID(id);

    let itemsContainer = rootContainer.querySelector(
      '.lazy-check-items-container'
    );

    if (!itemsContainer) {
      itemsContainer = document.createElement('div');
      itemsContainer.classList = 'lazy-check-items-container';

      rootContainer.append(itemsContainer);
    }

    // Add Show More Area
    let showMoreArea = rootContainer.querySelector(
      '.lazy-check-show-more-area'
    );

    if ( ! showMoreArea ) {
      showMoreArea = document.createElement('div');
      showMoreArea.classList = 'lazy-check-show-more-area';

      utility.insertAfter(itemsContainer, showMoreArea);
    }

    showMoreArea.innerHTML = '';

    // Add Feedback Area
    let feedbackArea = rootContainer.querySelector(
      '.lazy-check-feedback'
    );

    if ( ! feedbackArea ) {
      feedbackArea = document.createElement('div');
      feedbackArea.classList = 'lazy-check-feedback';

      itemsContainer.parentNode.insertBefore( feedbackArea, showMoreArea );
    }

    feedbackArea.innerHTML = '';
  },

  showModal: function(event, rootContainer) {
    event.preventDefault();
    const id = rootContainer.getAttribute('data-lazy-check-root-element-id');
    let modalContainer = document.querySelector(
      `[data-lazy-check-modal-id='${id}']`
    );

    if ( ! modalContainer ) {
      modalContainer = this.insertModal(id);
    } else {
      this.updateModal(id);
    }

    const hasNextPage = this.hasNextPage( rootContainer );

    if ( hasNextPage ) {
      this.addLoadMoreButtonToModal( modalContainer );
    } else {
      this.removeLoadMoreButtonFromModal( modalContainer );
    }

    utility.toggleClass(document.querySelector('body'), 'lazy-check-no-scroll');
    utility.toggleClass(modalContainer, 'show');
  },

  migrateInputIDs: function({ field, idConverter }) {
    const clonedField = field.cloneNode(true);

    // Input Fields
    const inputFields = clonedField.getElementsByTagName('input');
    if (inputFields.length) {
      for (const fieldItem of inputFields) {
        let oldID = fieldItem.getAttribute('id');
        let newID = idConverter(oldID);

        fieldItem.setAttribute('id', newID);
      }
    }

    // Labels
    const labels = clonedField.getElementsByTagName('label');
    if (labels.length) {
      for (const label of labels) {
        let oldID = label.getAttribute('for');
        let newID = idConverter(oldID);

        label.setAttribute('for', newID);
      }
    }

    return clonedField;
  },

  migrateInputIDsForModal: function(field) {
    const idConverter = oldID => 'modal-id-' + oldID;
    return this.migrateInputIDs({ field, idConverter });
  },

  migrateInputIDsForRootElement: function(field) {
    const idConverter = oldID => oldID.replace('modal-id-', '');
    return this.migrateInputIDs({ field, idConverter });
  },

  closeModel: function({ modalContainer }) {
    utility.toggleClass(modalContainer, 'show');
    utility.toggleClass(document.querySelector('body'), 'lazy-check-no-scroll');
  },

  clearAllInputs: function({ modalContainer }) {
    const inputs = modalContainer.getElementsByTagName('input');

    if ( ! inputs.length ) {
      return;
    }

    for (const input of inputs) {
      input.checked = false;
    }
  },

  applySelection: function({ id, modalContainer }) {
    const rootContainer = this.getRootContainerByID(id);
    const allCheckedItems = modalContainer.querySelectorAll('input:checked');

    // Reset to init state if no item is checked
    if ( ! allCheckedItems.length ) {
      this.resetToInitState({ modalContainer, rootContainer });
      this.closeModel({ modalContainer });
      return;
    }

    // Apply checked items to root element
    this.applyCheckedItemsToRootElement({
      modalContainer,
      rootContainer,
      allCheckedItems
    });

    this.closeModel({ modalContainer });
  },

  insertModal: function(id) {
    const modalContainer = document.createElement('div');
    modalContainer.className = 'lazy-check-modal';
    modalContainer.setAttribute('data-lazy-check-modal-id', id);
    modalContainer.innerHTML = `
           <div class='lazy-check-modal-content'>
               <div class='lazy-check-modal-header'>
                   <h4 class='lazy-check-modal-header-title'>${this.args.modalTitle} </h4>

                   <span class='lazy-check-modal-close'>
                       <i class='fas fa-times'></i>
                   </span>
               </div>

               <div class='lazy-check-modal-body'>
                   <div class='lazy-check-modal-fields'></div>

                   <div class='lazy-check-modal-fields-controls'></div>

                   <div class='lazy-check-modal-feedback'></div>
               </div>

               <div class='lazy-check-modal-footer'>
                   <div class='lazy-check-modal-actions'>
                       <button type='button' class='lazy-check-btn lazy-check-btn-secondary lazy-check-clear-btn'>Clear all</button>
                       <button type='button' class='lazy-check-btn lazy-check-btn-primary lazy-check-apply-btn'>Apply</button>
                   </div>
               </div>
           </div>
       `;

    const rootContainer = document.querySelector(
      `[data-lazy-check-root-element-id='${id}']`
    );
    const initFields = rootContainer.querySelectorAll(
      '.lazy-check-items-container .lazy-check-item-wrap'
    );

    // Change ID's for input and label
    for (const field of initFields) {
      const migratedField = this.migrateInputIDsForModal(field);
      modalContainer
        .querySelector('.lazy-check-modal-fields')
        .append(migratedField);
    }

    document.body.append(modalContainer);

    // Attach Events
    // ---------------
    // Close Button
    const closeButton = modalContainer.querySelector('.lazy-check-modal-close');
    closeButton.addEventListener('click', event =>
      this.closeModel({ event, id, modalContainer })
    );

    // Clear Button
    const clearButton = modalContainer.querySelector('.lazy-check-clear-btn');
    clearButton.addEventListener('click', event =>
      this.clearAllInputs({ event, id, modalContainer })
    );

    // Apply Button
    const applyButton = modalContainer.querySelector('.lazy-check-apply-btn');
    applyButton.addEventListener('click', event =>
      this.applySelection({ event, id, modalContainer })
    );

    return modalContainer;
  },

  updateModal: function(id) {
    // Prepare data
    const rootContainer = this.getRootContainerByID(id);
    const modalContainer = this.getModalContainerByID(id);

    // Get checked items from root element
    const rootCheckedInputs = rootContainer.querySelectorAll('input:checked');
    const rootCheckedItems = [];

    if ( rootCheckedInputs.length ) {
      for ( const input of rootCheckedInputs ) {
        rootCheckedItems.push( input.parentElement );
      }
    }

    // Sync migrated checked items to modal
    const rootlInputs = rootContainer.querySelectorAll('input');
    for (const rootInput of rootlInputs) {
      const id = rootInput.getAttribute('id');
      const modalInput = modalContainer.querySelector(`#modal-id-${id}`);

      if ( ! modalInput ) {
        return;
      }

      modalInput.checked = rootInput.checked;
    }

    // Sort checked items in modal
    let modalCheckedInputs = modalContainer.querySelectorAll('input:checked');
    modalCheckedInputs = [...modalCheckedInputs].reverse();

    modalCheckedInputs.map(input => {
      modalContainer
        .querySelector('.lazy-check-modal-fields')
        .prepend(input.parentElement);
    });

    // Show hidden items
    let modalInputs = modalContainer.querySelectorAll('input');
    [...modalInputs].map( item => {
      item.parentElement.classList.remove( 'lazy-check-hide' );
      // const newClasses = item.parentElement.className.replace( 'lazy-check-hide', '' );
      // item.parentElement.className = newClasses;
    });
  },

  addLoadMoreButtonToModal: function( modalContainer, force_add ) {
    if ( ! modalContainer ) {
      return;
    }

    const controllArea = modalContainer.querySelector( '.lazy-check-modal-fields-controls' );

    if ( ! controllArea ) {
      return;
    }

    // Remove existed on if has any
    const oldLoadMoreLink = controllArea.querySelector( '.lazy-check-modal-load-more-link' );
    if ( oldLoadMoreLink && ! force_add ) {
      oldLoadMoreLink.remove();
      return;
    }

    controllArea.innerHTML += `
    <a class='lazy-check-modal-load-more-link'>
        <span class='lazy-check-modal-load-more-text'>
            ${this.args.ajax.loadMoreText}
        </span>
    </a>
    `;

    const loadMoreLink = modalContainer.querySelector(
      '.lazy-check-modal-load-more-link'
    );

    loadMoreLink.addEventListener('click', event =>
      this.loadMoreFields({ modalContainer })
    );
  },

  removeLoadMoreButtonFromModal: function( modalContainer ) {
    if ( ! modalContainer ) {
      return;
    }

    const controllArea = modalContainer.querySelector( '.lazy-check-modal-fields-controls' );

    if ( ! controllArea ) {
      return;
    }

    const loadMoreLink = controllArea.querySelector( '.lazy-check-modal-load-more-link' );

    if ( ! loadMoreLink ) {
      return;
    }

    loadMoreLink.remove();
  },

  loadMoreFields: async function({ modalContainer }) {
    const modelID = this.getModalID( modalContainer );

    // Add Loading Indicator
    this.addLoadingIndicatorToModal( modalContainer );

    const nextData = await this.fetchData({ id: modelID, itemIDPrefix: 'modal-id-' });

    // Remove Loading Indicator
    this.removeLoadingIndicatorFromModal( modalContainer );

    if ( ! nextData || ! nextData.success || ! nextData.data.items.length ) {
      return;
    }

    // Insert items to the DOM
    let itemsContainer = modalContainer.querySelector( '.lazy-check-modal-fields' );
    nextData.data.template.map(item => {
      let itemElementWrap = document.createElement('div');
      itemElementWrap.innerHTML = item;

      const itemElement = itemElementWrap.querySelector( '.lazy-check-item-wrap' );

      if ( itemElement ) {
        itemsContainer.appendChild( itemElement );
      }
    });
  },

  applyCheckedItemsToRootElement: function({
    modalContainer,
    rootContainer,
    allCheckedItems
  }) {
    const maxInitItemLength = this.args.ajax.maxInitItems;
    let { checkedItems, checkedIDs } = this.getCheckedItems({
      allCheckedItems,
      maxInitItemLength
    });

    // Add additional field if nessasary
    if (checkedItems.length < maxInitItemLength) {
      checkedItems = this.wrapWithAdditionalItems({
        modalContainer,
        maxInitItemLength,
        checkedItems,
        checkedIDs
      });
    }

    const migratedSelectedItems = checkedItems.map(field => {
      return this.migrateInputIDsForRootElement(field);
    });

    const itemsContainer = rootContainer.querySelector(
      '.lazy-check-items-container'
    );
    itemsContainer.innerHTML = '';

    for (const item of migratedSelectedItems) {
      itemsContainer.append(item);
    }
  },

  getCheckedItems: function({ allCheckedItems, maxInitItemLength }) {
    let checkedItems = [];
    let checkedIDs = [];
    let count = 0;

    for (const item of allCheckedItems) {
      const parent = item.parentElement;
      const id = item.getAttribute('id');

      if ( count >= maxInitItemLength ) {
        parent.classList += ' lazy-check-hide';
      }

      checkedIDs.push(id);
      checkedItems.push(parent);

      count++;
    }

    return { checkedItems, checkedIDs };
  },

  wrapWithAdditionalItems: function({
    modalContainer,
    maxInitItemLength,
    checkedItems,
    checkedIDs
  }) {
    const initItems = modalContainer.querySelectorAll('.init-item');

    if ( ! initItems.length ) {
      return checkedItems;
    }

    let requiredItemLength = maxInitItemLength - checkedItems.length;
    let count = 0;

    for ( const initItem of initItems ) {
      if ( count === requiredItemLength ) {
        break;
      }

      const input = initItem.querySelector('input');
      if ( ! input ) {
        continue;
      }

      const id = input.getAttribute('id');
      if ( checkedIDs.includes(id) ) {
        continue;
      }

      checkedItems.push(initItem);

      count++;
    }

    return checkedItems;
  },

  resetToInitState: function({ modalContainer, rootContainer }) {
    const initItems = modalContainer.querySelectorAll('.init-item');

    if ( ! initItems.length ) {
      return;
    }

    const itemsContainer = rootContainer.querySelector(
      '.lazy-check-items-container'
    );

    itemsContainer.innerHTML = '';

    for (const item of initItems) {
      const idConverter = oldID => oldID.replace('modal-id-', '');
      const migratedField = this.migrateInputIDs({
        field: item,
        idConverter
      });

      itemsContainer.append(migratedField);
    }
  },

  getRootContainerByID: function(id) {
    return document.querySelector(`[data-lazy-check-root-element-id='${id}']`);
  },

  getCurrentPage: function(rootContainer) {
    if ( ! rootContainer ) {
      return 1;
    }

    return parseInt(rootContainer.getAttribute('data-lazy-check-current-page'));
  },

  updateCurrentPage: function(rootContainer, newPageNumber) {
    if ( ! rootContainer ) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-current-page', newPageNumber);
  },

  hasNextPage: function(rootContainer) {
    if ( ! rootContainer) {
      return false;
    }

    return parseInt(rootContainer.getAttribute('data-lazy-check-has-next-page')) ? true : false;
  },

  updateHasNextPageStatus: function(rootContainer, status) {
    if ( ! rootContainer ) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-has-next-page', ( status ) ? 1 : 0);
  },

  getIsLoadingStatus: function(rootContainer) {
    if ( ! rootContainer ) {
      return false;
    }

    const isLoading = rootContainer.getAttribute('data-lazy-check-is-loading');

    return isLoading === '1' || isLoading === true ? true : false;
  },

  updateIsLoadingStatus: function(rootContainer, status) {
    if ( ! rootContainer ) {
      return;
    }

    rootContainer.setAttribute('data-lazy-check-is-loading', ( status ) ? 1 : 0);
  },

  getModalContainerByID: function(id) {
    return document.querySelector(`[data-lazy-check-modal-id='${id}']`);
  },

  getModalID: function( modalContainer ) {
    const id = modalContainer.getAttribute( 'data-lazy-check-modal-id' );

    return parseInt( id );
  },

  addLoadingIndicatorToModal: function( modalContainer ) {
    if ( ! modalContainer ) {
      return;
    }

    const feedbackArea = modalContainer.querySelector( '.lazy-check-modal-feedback' );

    if ( ! feedbackArea ) {
      return;
    }

    feedbackArea.innerHTML = this.args.ajax.loadingIndicator;
  },

  removeLoadingIndicatorFromModal: function( modalContainer ) {
    if ( ! modalContainer ) {
      return;
    }

    const feedbackArea = modalContainer.querySelector( '.lazy-check-modal-feedback' );

    if ( ! feedbackArea ) {
      return;
    }

    feedbackArea.innerHTML = '';
  },

  addLoadingIndicatorToRootContainer: function( rootContainer ) {
    if ( ! rootContainer ) {
      return;
    }

    const feedbackArea = rootContainer.querySelector( '.lazy-check-feedback' );

    if ( ! feedbackArea ) {
      return;
    }

    feedbackArea.innerHTML = this.args.ajax.loadingIndicator;
  },

  removeLoadingIndicatorFromRootContainer: function( rootContainer ) {
    if ( ! rootContainer ) {
      return;
    }

    const feedbackArea = rootContainer.querySelector( '.lazy-check-feedback' );

    if ( ! feedbackArea ) {
      return;
    }

    feedbackArea.innerHTML = '';
  },

  isLoadedPreselectedItems: function ( rootContainer ) {
    const isLoadedPreselectedItems = rootContainer.getAttribute('data-lazy-check-is-loaded-preselected-items');

    return ( isLoadedPreselectedItems === '1' ) ? true : false;
  },

  updateIsLoadedPreselectedItemsStatus: function ( rootContainer, status ) {
    const newStatus = ( status ) ? '1' : '0';
    rootContainer.setAttribute( 'data-lazy-check-is-loaded-preselected-items', newStatus );
  },

  fetchData: async function({ id, itemIDPrefix }) {
    // Response Status
    let responseStatus = new utility.responseStatus();

    // Prepare Data
    const rootContainer = this.getRootContainerByID(id);
    const modalContainer = this.getModalContainerByID( id );

    if ( ! rootContainer ) {
      responseStatus.success = false;
      responseStatus.message = 'Root Container not found';
    }

    // Stop if already loading
    const isLoading = this.getIsLoadingStatus( rootContainer );
    if ( isLoading ) {
      responseStatus.success = false;
      responseStatus.message = 'Please wait...';

      return responseStatus;
    }

    // Update Loading Status
    this.updateIsLoadingStatus(rootContainer, true);

    // Remove Load More Button
    this.removeLoadMoreButtonFromModal( modalContainer );

    const currentPage = this.getCurrentPage(rootContainer);
    const newPage = currentPage + 1;

    // Load Pre Selected Items
    let preselectedItemsID = this.args.ajax.getPreselectedItemsID();
    preselectedItemsID = ( preselectedItemsID instanceof Array ) ? preselectedItemsID : [];

    const isLoadedPreselectedItems = this.isLoadedPreselectedItems( rootContainer );

    let preselectedItems = [];

    if ( preselectedItemsID.length && ! isLoadedPreselectedItems ) {
      const formData = this.args.ajax.data({
        isLoadingPreselectedItems: true,
        preselectedItemsID
      });

      let url = this.args.ajax.url;
      url = url + utility.jsonToQueryString(formData);

      const response = await fetch(url);
      const body     = await response.json();

      if ( response.ok && body instanceof Array ) {
        preselectedItems = body;
        this.updateIsLoadedPreselectedItemsStatus( rootContainer, true );
      }
    }

    // Load General Items
    const formData = this.args.ajax.data({
      page: newPage,
      isLoadingPreselectedItems: false,
      preselectedItemsID
    });

    let url = this.args.ajax.url;
    url = url + utility.jsonToQueryString(formData);

    const response = await fetch(url);
    const headers  = response.headers;
    const body     = await response.json();

    // Validate Response
    if ( ! response.ok ) {
      responseStatus.success = false;
      responseStatus.message = 'Something went wrong';

      return responseStatus;
    }

    // Process Results
    let processResults = this.args.ajax.processResults({
      body,
      headers,
      params: formData,
      hasNextPage: false
    });

    // Validate Process Results
    if ( ! ( processResults && typeof processResults == 'object' ) ) {
      responseStatus.success = false;
      responseStatus.message = 'Something went wrong';

      return responseStatus;
    }

    if ( ! (processResults.body && processResults.body instanceof Array ) ) {
      responseStatus.success = false;
      responseStatus.message = 'Response body must be array';

      return responseStatus;
    }

    // Merge Preselected Items
    if ( preselectedItems.length ) {
      processResults.body = [ ...preselectedItems, ...processResults.body ];
    }

    if ( ! processResults.body.length ) {
      responseStatus.success = false;
      responseStatus.message = 'No result found';

      // Update Has Next Page Status
      this.updateHasNextPageStatus( rootContainer, false );

      // Remove Load More Button
      this.removeLoadMoreButtonFromModal( modalContainer );

      // Remove Loading Indicator
      this.removeLoadingIndicatorFromModal( modalContainer );

      return responseStatus;
    }

    // Process Results
    const templateData = processResults.body.map( item => {
      try {
        const idPrefix = itemIDPrefix ? itemIDPrefix : '';
        item.randomID = idPrefix + utility.generateRandom( 100000, 999999 );

        return this.args.ajax.template( item, headers );
      } catch (error) {
        return '';
      }
    });

    // Update Status
    responseStatus.success = true;
    responseStatus.data = {
      items: processResults.body,
      template: templateData,
    };

    // Update Current Page
    this.updateCurrentPage(rootContainer, newPage);

    // Update Has Next Page Status
    const hasNextPage = ( processResults.hasNextPage ) ? true : false;
    this.updateHasNextPageStatus(rootContainer, hasNextPage);

    // Update Loading Status
    this.updateIsLoadingStatus(rootContainer, false);

    // Toggle Load More Button
    if ( hasNextPage ) {
      this.addLoadMoreButtonToModal( modalContainer );
    } else {
      this.removeLoadMoreButtonFromModal( modalContainer );
    }

    return responseStatus;
  },

};

export default lazyCheckCore;
