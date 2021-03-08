export default {
    view: {
        type: 'string',
        default: 'grid'
    },
    orderby: {
        type: 'string',
        default: 'date'
    },
    order: {
        type: 'string',
        default: 'desc'
    },
    loc_per_page: {
        type: 'number',
        default: 100
    },
    columns: {
        type: 'number',
        default: 3
    },
    slug: {
        type: 'string',
        default: ''
    },
    logged_in_user_only: {
        type: 'boolean',
        default: false
    },
    redirect_page_url: {
        type: 'string',
        default: ''
    },
    directory_type: {
        type: 'array',
        default: []
    },
    default_directory_type: {
        type: 'number',
        default: 0
    }
};
