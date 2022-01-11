import lazyChecks from "../../lib/lazy-checks/lazy-check";

window.onload = function() {
  const defaultArgs = {
    modalTitle: "Tags",
    containerClass: "directorist-tags-lazy-checks",
    showMoreToggleClass: "directorist-link",
    ajax: {
      url: atbdp_public_data.rest_url + 'directorist/v1/listings/tags',
      maxInitItems: 4,
      getPreselectedItemsID: () => {
        const urlParams = new URLSearchParams(window.location.search);
        const in_tag    = urlParams.getAll('in_tag[]');

        return ( in_tag instanceof Array ) ? in_tag : [];
      },
      data: params => {
        if ( params.isLoadingPreselectedItems && params.preselectedItemsID.length ) {
          params.page     = 1;
          params.include  = params.preselectedItemsID;
        } else if ( ! params.isLoadingPreselectedItems && params.preselectedItemsID.length ) {
          params.page     = params.page || 1;
          params.per_page = 50;
          params.exclude  = params.preselectedItemsID;
        } else {
          params.page     = params.page || 1;
          params.per_page = 50;
        };

        return params;
      },
      processResults: ( response ) => {
        const currentPage = response.params.page;
        const totalPage = parseInt( response.headers.get('X-WP-TotalPages') );
        response.hasNextPage = ( currentPage >= totalPage ) ? false : true;

        return response;
      },
      template: ( item ) => {
        const urParams = new URLSearchParams(window.location.search);
        let in_tag     = urParams.getAll('in_tag[]');

        const checked = ( in_tag instanceof Array && in_tag.includes( `${item.id}` ) ) ? 'checked' : '';

        return `
        <div class="lazy-check-item-wrap directorist-checkbox directorist-checkbox-primary">
          <input type="checkbox" name="in_tag[]" value="${item.id}" id="${item.randomID}"${checked}>
          <label for="${item.randomID}" class="directorist-checkbox__label">${item.name}</label>
        </div>
        `;
      },
    },
  };

  let filterArgs = null;

  if ( typeof window.directoristLazyTagArgs === 'function' ) {
    filterArgs = window.directoristLazyTagArgs( defaultArgs );
  }

  let args =  defaultArgs;

  if ( filterArgs && typeof filterArgs == 'object' ) {
    args = { ...defaultArgs, ...filterArgs };
    args.ajax = defaultArgs.ajax;

    if ( filterArgs.ajax && typeof filterArgs.ajax == 'object' ) {
      args.ajax = { ...defaultArgs.ajax, ...filterArgs.ajax };
    }
  }

  new lazyChecks( args ).init();
};
