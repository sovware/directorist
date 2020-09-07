<template>
    <div class="atbdp-cptm-tab-item listings-page-layout cptm-tab-content tab-full-width" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-title-area">
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
                        </ul>
                    </div>
                </div>
            </div>

            <div class="cptm-preview-area">

            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';

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
}
</script>