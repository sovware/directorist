<template>
    <div>
        <div class="cptm-tab-content-header">
            <sub-navigation :navLists="navList" v-model="active_sub_nav"/>
            <a
                href="#"
                class="directorist-form-doc__watch-tutorial"
                v-if="currentVideo"
                @click.prevent="openVideoPopup"
            >
                <svg
                xmlns="http://www.w3.org/2000/svg"
                width="14"
                height="14"
                viewBox="0 0 14 14"
                fill="none"
                >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M3.94256 2.33333H7.14074C7.6103 2.33332 7.99785 2.33331 8.31355 2.35911C8.64143 2.3859 8.94285 2.44339 9.22596 2.58765C9.665 2.81135 10.022 3.16831 10.2457 3.60735C10.3899 3.89046 10.4474 4.19187 10.4742 4.51976C10.4981 4.81257 10.4999 5.16718 10.5 5.59171L11.6396 4.45212C11.7511 4.34058 11.8607 4.23096 11.9567 4.15052C12.0424 4.07876 12.223 3.93485 12.473 3.91517C12.7522 3.8932 13.0251 4.00622 13.207 4.21921C13.3699 4.40993 13.3958 4.63932 13.4056 4.75068C13.4167 4.87549 13.4167 5.03051 13.4166 5.18822V8.81177C13.4167 8.96948 13.4167 9.1245 13.4056 9.24931C13.3958 9.36067 13.3699 9.59006 13.207 9.78078C13.0251 9.99377 12.7522 10.1068 12.473 10.0848C12.223 10.0651 12.0424 9.92123 11.9567 9.84947C11.8607 9.76904 11.7511 9.65941 11.6396 9.54787L10.5 8.40828C10.4999 8.83281 10.4981 9.18742 10.4742 9.48023C10.4474 9.80812 10.3899 10.1095 10.2457 10.3926C10.022 10.8317 9.665 11.1886 9.22596 11.4123C8.94285 11.5566 8.64144 11.6141 8.31355 11.6409C7.99784 11.6667 7.6103 11.6667 7.14072 11.6667H3.94257C3.473 11.6667 3.08545 11.6667 2.76975 11.6409C2.44186 11.6141 2.14045 11.5566 1.85734 11.4123C1.41829 11.1886 1.06134 10.8317 0.837632 10.3926C0.693379 10.1095 0.635883 9.80812 0.609093 9.48023C0.5833 9.16453 0.583306 8.77699 0.583313 8.30742V5.69257C0.583306 5.22301 0.5833 4.83546 0.609093 4.51976C0.635883 4.19187 0.693379 3.89046 0.837632 3.60735C1.06134 3.16831 1.41829 2.81135 1.85734 2.58765C2.14045 2.44339 2.44186 2.3859 2.76975 2.35911C3.08545 2.33331 3.47299 2.33332 3.94256 2.33333ZM9.33331 5.71666C9.33331 5.21699 9.33286 4.87732 9.31141 4.61477C9.29051 4.35903 9.25264 4.22824 9.20615 4.13701C9.0943 3.91748 8.91582 3.73901 8.6963 3.62715C8.60507 3.58067 8.47428 3.5428 8.21854 3.5219C7.95599 3.50045 7.61632 3.5 7.11665 3.5H3.96665C3.46698 3.5 3.1273 3.50045 2.86475 3.5219C2.60901 3.5428 2.47822 3.58067 2.38699 3.62715C2.16747 3.73901 1.98899 3.91748 1.87714 4.13701C1.83065 4.22824 1.79278 4.35903 1.77189 4.61477C1.75043 4.87732 1.74998 5.21699 1.74998 5.71666V8.28333C1.74998 8.783 1.75043 9.12267 1.77189 9.38522C1.79278 9.64097 1.83065 9.77175 1.87714 9.86298C1.98899 10.0825 2.16747 10.261 2.38699 10.3728C2.47822 10.4193 2.60901 10.4572 2.86475 10.4781C3.1273 10.4995 3.46698 10.5 3.96665 10.5H7.11665C7.61632 10.5 7.95599 10.4995 8.21854 10.4781C8.47428 10.4572 8.60507 10.4193 8.6963 10.3728C8.91582 10.261 9.0943 10.0825 9.20615 9.86298C9.25264 9.77175 9.29051 9.64097 9.31141 9.38522C9.33286 9.12267 9.33331 8.783 9.33331 8.28333V5.71666ZM10.7416 7L12.25 8.50837V5.49162L10.7416 7Z"
                    fill="currentColor"
                />
                </svg>
                {{currentVideo.button_text}}
            </a>
        </div>

        <div class="cptm-tab-content-body">
            <template v-for="( sub_tab, sub_tab_index ) in subNavigation">
                <div class="cptm-tab-sub-content-item" :key="sub_tab_index" 
                    v-if="( active_sub_nav === sub_tab_index ) ? true : false"
                    :class="{ active: ( active_sub_nav === sub_tab_index ) ? true : false }"
                >
                    <sections-module v-bind="sub_tab" />
                </div>
            </template>
            
        </div>

        <!-- Video Popup Modal -->
        <form-builder-widget-video-component
            v-if="currentVideo"
            :video="currentVideo"
            :videoOpened="showVideo"
            @close-video="closeVideoPopup"
        />
    </div>
</template>

<script>
import helpers from '../mixins/helpers';

export default {
    'name': 'submenu-module',
    mixins: [ helpers ],

    props: {
        submenu: {
            type: Object
        },
    },

    // computed
    computed: {
        subNavigation() {
            if ( ! this.submenu && typeof this.submenu !== 'object' ) { return []; }

            let sub_navigation = [];
            
            for ( let submenu_key in this.submenu ) {
                let submenu = this.submenu[ submenu_key ];

                if ( typeof submenu.label !== 'string' ) {
                    continue;
                }

                if (  ! submenu.sections && typeof submenu.sections !== 'object' ) {
                    continue;
                }

                if (  Array.isArray( submenu.sections ) ) {
                    continue;
                }

                sub_navigation.push( submenu );
            }

            return sub_navigation;
        },

        navList() {
            if ( ! this.subNavigation && typeof this.subNavigation !== 'object' ) {
                return [];
            }

            return [ ...this.subNavigation ].map( item => {
                return item.label;
            });
        },

        currentVideo() {
            const activeSubMenu = this.subNavigation[this.active_sub_nav];
            return activeSubMenu?.video || null;
        }
    },

    methods: {
        // Open the video popup
        openVideoPopup() {
            this.showVideo = true;
        },

        // Close the video popup
        closeVideoPopup() {
            this.showVideo = false;
        },
    },

    data() {
        return {
            active_sub_nav: 0,
            showVideo: false,
        }
    },


}
</script>