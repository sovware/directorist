<template>
    <div class="atbdp-cptm-tab-item listings-page-layout cptm-tab-content tab-full-width" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-title-area cptm-mb-60">
            <h3 class="cptm-title">Customize the preview card</h3>
            <p>Need help? Read the documentation or open a ticket in our helpdesk.</p>
        </div>

        <div class="cptm-builder-section">
            <div class="cptm-widgets-area">
                <div class="cptm-widgets-container">
                    <div class="cptm-widgets-header">
                        <ul class="cptm-widget-nav">
                            <li class="cptm-widget-nav-item active">Widgets</li>
                            <!-- <li class="cptm-widget-nav-item">Edit</li> -->
                            <li class="cptm-widget-nav-item">Global</li>
                        </ul>
                    </div>

                    <div class="cptm-widgets-body">
                        <ul class="cptm-widgets-list">
                            <li class="cptm-widgets-list-item">
                                <h4 class="widget-group-title">General</h4>
                                <ul class="cptm-widgets-sub-list"> 
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                    
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>

                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="cptm-widgets-list-item">
                                <h4 class="widget-group-title">General</h4>
                                <ul class="cptm-widgets-sub-list"> 
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                    
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>

                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                </ul>
                            </li>

                            <li class="cptm-widgets-list-item">
                                <h4 class="widget-group-title">General</h4>
                                <ul class="cptm-widgets-sub-list"> 
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                    
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>

                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="cptm-widgets-list-item">
                                <h4 class="widget-group-title">General</h4>
                                <ul class="cptm-widgets-sub-list"> 
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                    
                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>

                                    <li class="cptm-widgets-sub-list-item">
                                        <span class="widget-icon">
                                            <span class="fa fa-bars"></span>
                                        </span>
                                        <span class="widget-label">Button</span>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

                        
            <!-- cptm-preview-area -->
            <div class="cptm-preview-area">
                <div class="cptm-card-preview-widget">
                    <!-- cptm-listing-card-preview-header -->
                    <div class="cptm-listing-card-preview-header">
                        <div class="cptm-card-preview-thumbnail">
                            <div class="cptm-card-preview-thumbnail-overlay">
                                <div class="cptm-card-preview-top-left">
                                    <div class="cptm-card-preview-top-left-placeholder cptm-placeholder-blcok">
                                        <p class="cptm-placeholder-label">Top Left</p>
                                    </div>
                                </div>
                                
                                <div class="cptm-card-preview-top-rignt">
                                    <div class="cptm-card-preview-top-rignt-placeholder cptm-placeholder-blcok">
                                        <p class="cptm-placeholder-label hide">Top Rignt</p>
                                        
                                        <div class="cptm-widget-badge">Badge Text 1</div>
                                        <!-- <div class="cptm-widget-badge">Badge Text 2</div> -->
                                    </div>
                                </div>

                                <div class="cptm-card-preview-bottom-left">
                                    <div class="cptm-card-preview-bottom-left-placeholder cptm-placeholder-blcok">
                                        <p class="cptm-placeholder-label">Bottom Left</p>
                                    </div>
                                </div>

                                <div class="cptm-card-preview-bottom-rignt">
                                    <div class="cptm-card-preview-bottom-rignt-placeholder cptm-placeholder-blcok">
                                        <p class="cptm-placeholder-label">Bottom Rignt</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- cptm-listing-card-preview-body -->
                    <div class="cptm-listing-card-preview-body">
                        <div class="cptm-listing-card-preview-body-placeholder cptm-placeholder-blcok">
                            <p class="cptm-placeholder-label">Body Contents</p>
                        </div>
                    </div>

                    <!-- cptm-listing-card-preview-footer -->
                    <div class="cptm-listing-card-preview-footer">
                        <div class="cptm-listing-card-preview-footer-left-placeholder cptm-placeholder-blcok">
                            <p class="cptm-placeholder-label">Footer Left</p>
                        </div>
                        <div class="cptm-listing-card-preview-footer-right-placeholder cptm-placeholder-blcok">
                            <p class="cptm-placeholder-label">Footer Right</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../mixins/helpers';

export default {
    name: 'listings-card-layout',
    props: ['index'],
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState([
            'active_nav_index',
        ])
    },

    data() {
        return {
            widget_list: {
                badge: '<div class="cptm-widget-badge">Badge Text 1</div>',
                button: {
                    'label': 'Button'
                },
                description: {
                    'label': 'Button'
                },
                view_count: {
                    'meta_key': 'view_count'
                },
            },

            top_left_widgets: [
                'widget_name',
                'widget_name',
                'widget_name',
            ],
        }
    },
}
</script>