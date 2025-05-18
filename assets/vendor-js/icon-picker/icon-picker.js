window.IconPicker = function (args) {
    return {
        id: null,
        value: '',
        iconType: 'solid',
        container: null,
        onSelect: null,
        icons: null,
        labels: {
            changeIconButtonLabel: 'Change Icon',
            changeIconButtonPlaceholder: 'Click to select icon',
            filterByNameInputLabel: 'Filter By Name',
            filterByNameInputPlaceholder: 'Search',
            filterByGroupInputLabel: 'Filter By Icon Pack',
            doneButtonLabel: 'Done',
            iconGroupLabels: {},
        },
        init: function () {
            let _this = this;

            const body = document.querySelector('body');
            const count = body.getAttribute('data-directorist-icon-picker-count');
            const id = (count) ? (parseInt(count) + 1) : 1;

            body.setAttribute('data-directorist-icon-picker-count', id);

            this.id = id;
            this.container = (typeof args.container !== 'undefined') ? args.container : this.container;
            this.labels = (typeof args.labels !== 'undefined') ? {
                ...this.labels,
                ...args.labels
            } : this.labels;
            this.onSelect = (typeof args.onSelect !== 'undefined') ? args.onSelect : this.onSelect;
            this.icons = (typeof args.icons !== 'undefined') ? args.icons : this.icons;
            this.value = (typeof args.value === 'string') ? args.value : this.value;

            if (!this.container) {
                return;
            }

            _this.renderMarkup();
            _this.renderIcon();
            _this.attachEvents();

        },
        renderIcon() {
            const iconsGroup = this.container.closest('body').querySelector('#iconsWrapperElm .icons-group');

            if (iconsGroup) {
                return;
            }

            let markup = '';
            for (const iconGroupKey of Object.keys(this.icons)) {
                markup += `<div class="icons-group ${iconGroupKey}">`;
                markup += `<h4>${ this.getIconGroupLabel( iconGroupKey )}</h4>`;
                markup += `<div class="icons-group-icons">`;
                for (const icon of this.icons[iconGroupKey].icons) {
                    const fullIcon = this.getFullIcon(icon.key, iconGroupKey, icon.types[0]);
                    const buttonClass = (this.value === fullIcon) ? 'cptm-btn-primary' : 'cptm-btn-secondery';
                    markup += `
                        <button class="font-icon-btn cptm-btn ${buttonClass}" data-group-key="${iconGroupKey}" data-icon-key="${icon.key}" data-icon-type="${[icon.types]}">
                            <span class="${fullIcon}"></span>
                        </button>
                    `;
                }
                markup += `</div></div>`;
            }
            this.container.closest('body').querySelector('#iconsWrapperElm').innerHTML = markup;
        },
        renderMarkup() {
            let selectedIcon = this.value ? this.value.split(" ") : ['', 'icon-name'];
            let markup = '';
            markup += `
            <div class="icon-picker-selector icon-picker-id-${this.id}" data-icon-picker-id="${this.id}">
                <div class="icon-picker-selector__icon">
                    <span class="directorist-selected-icon ${this.value}"></span>
                    <input
                    type="text"
                    placeholder="${this.labels.changeIconButtonPlaceholder}"
                    class="cptm-form-control"
                    value="${this.value}" style="${this.value ? 'padding-left: 38px' : '' }"
                    />
                    <span class="icon-picker-selector__icon__reset"><span class="fas fa-times"></span
                    ></span>
                </div>
                <button class="icon-picker-selector__btn">${this.labels.changeIconButtonLabel}</button>
            </div>
                `;
            this.container.innerHTML = markup;

            if (document.querySelector('.icon-picker')) {
                return;
            }

            let iconPickerWrap = `
            <div class="icon-picker">
            <div class="icon-picker__inner">
                <a href="#0" class="icon-picker__close"
                    ><span class="fas fa-times"></span
                ></a>
                <div class="icon-picker__sidebar">
                    <div class="icon-picker__filter">
                        <label for="">${this.labels.filterByNameInputLabel}</label>
                        <input type="text" placeholder="${this.labels.filterByNameInputPlaceholder}" />
                    </div>
                    <div class="icon-picker__filter">
                        <label for="">${this.labels.filterByGroupInputLabel}</label>
                        <select class="icon-picker__filter_select">
                            ${this.getIconGroupOptions()}
                        </select>
                    </div>
                    <div class="icon-picker__preview">
                        <span class="icon-picker__preview-icon ${this.value}"></span>
                        <span class="icon-picker__preview-info">
                            <span class="icon-picker__icon-name">${selectedIcon[1]}</span>
                        </span>
                    </div>
                    <button class="cptm-btn cptm-btn-primary icon-picker__done-btn">${this.labels.doneButtonLabel}</button>
                </div>
                <div class="icon-picker__content">
                <div id="iconsWrapperElm" class="iconsWrapperElm">

                </div>
            </div>
        </div>
            `;

            this.container.closest('body').insertAdjacentHTML('beforeend', iconPickerWrap)
        },

        getIconGroupOptions: function () {
            if (!this.icons) {
                return '';
            }

            let options = '';

            for (const iconGroup in this.icons) {
                options += `<option value="${iconGroup}">${this.getIconGroupLabel( iconGroup )}</option>` + "\n";
            }

            return options;
        },

        getIconGroupLabel(iconGroup) {
            const labels = (this.labels.iconGroupLabels && typeof this.labels.iconGroupLabels === 'object') ? this.labels.iconGroupLabels : {};
            return (typeof labels[iconGroup] !== 'undefined') ? labels[iconGroup] : this.icons[iconGroup].label;

        },

        attachEvents() {
            const iconButtons = document.querySelectorAll('.font-icon-btn');
            const self = this;
            let icon;

            // remove active status
            function removeActiveStatus() {
                iconButtons.forEach(elm => {
                    if (elm.classList.contains('cptm-btn-primary')) {
                        elm.classList.remove('cptm-btn-primary');
                    }
                })
            }

            iconButtons.forEach(elm => {
                elm.addEventListener('click', function (event) {
                    event.preventDefault();

                    const iconGroupKey = event.target.getAttribute('data-group-key');
                    const iconKey = event.target.getAttribute('data-icon-key');
                    const iconType = event.target.getAttribute('data-icon-type').split(',');
                    icon = self.getFullIcon(iconKey, iconGroupKey, iconType[0]);

                    removeActiveStatus();

                    elm.classList.add('cptm-btn-primary');

                    const body = self.container.closest('body');

                    if (body) {
                        body.querySelector('.icon-picker__preview-icon').setAttribute('class', `icon-picker__preview-icon ${icon}`);
                        body.querySelector('.icon-picker__icon-name').innerHTML = iconKey;
                    }

                    searchIcon();
                });
            })

            function openModal(event, self) {
                const iconPicker = document.querySelector('.icon-picker');

                if (iconPicker.classList.contains('icon-picker-visible')) {
                    return;
                }

                const id = event.target.closest('.icon-picker-selector').getAttribute('data-icon-picker-id');
                const selectedIconClassList = (self.value) ? self.value.split(' ') : [];

                // Attach Modal ID
                iconPicker.setAttribute('data-icon-picker-id', id);

                // Update Filter Serch Text
                iconPicker.querySelector('.icon-picker__filter input').value = '';

                // Update Filter Select
                const iconFilterSelect = iconPicker.querySelector('.icon-picker__filter_select');
                const iconType = (selectedIconClassList.length) ? selectedIconClassList[0] : '';
                const iconGroup = self.findIconGroupByIconType(iconType);

                if (iconGroup) {
                    iconFilterSelect.value = iconGroup;
                }

                filterIconPack(iconFilterSelect);

                // Update Selected Icon Status
                const selectedIconClasses = (selectedIconClassList.length) ? '.font-icon-btn.' + selectedIconClassList.join('.') : '';
                const selectedIcon = selectedIconClasses !== '' ? iconPicker.querySelector(selectedIconClasses) : '';

                document.querySelectorAll('.font-icon-btn')
                    .forEach(item => item.classList.remove('cptm-btn-primary'));

                if (selectedIcon) {
                    selectedIcon.classList.remove('cptm-btn-secondery');
                    selectedIcon.classList.add('cptm-btn-primary');
                }

                // Update Preview Icon
                const previewContainer = iconPicker.querySelector('.icon-picker__preview');
                previewContainer.querySelector('.icon-picker__preview-icon').setAttribute('class', 'icon-picker__preview-icon ' + self.value);
                previewContainer.querySelector('.icon-picker__icon-name').innerHTML = (selectedIconClassList.length) ? selectedIconClassList[1] : '';

                iconPicker.classList.add('icon-picker-visible');
            }

            function closeModal() {
                const iconPicker = document.querySelector('.icon-picker');
                iconPicker.classList.remove('icon-picker-visible');
            }

            const selectIconButtons = document.querySelectorAll('.icon-picker-selector .icon-picker-selector__btn');

            if (selectIconButtons.length) {
                for (const selectIconButton of selectIconButtons) {
                    selectIconButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        openModal(e, self);
                    });
                }
            }

            const selectIconInputs = document.querySelectorAll('.icon-picker-selector .icon-picker-selector__icon input');

            if (selectIconInputs.length) {
                for (const selectIconInput of selectIconInputs) {
                    selectIconInput.addEventListener('click', (e) => {
                        const inputIcon = selectIconInput.value;

                        if (inputIcon === '') {
                            e.preventDefault(); 
                            // open icon modal
                            openModal(e, self);
                        }
                    });
                    
                }
            }

            const resetBtns = document.querySelectorAll('.icon-picker-selector__icon__reset');
            if (resetBtns.length) {
                for (const resetBtn of resetBtns) {
                    const parent = resetBtn.parentElement;

                    // Select the input sibling of resetBtn
                    const inputSibling = parent.querySelector('input');

                    // Hide the reset button if the input value is empty
                    if (inputSibling && inputSibling.value === "") {
                        resetBtn.style.display = 'none';
                        inputSibling.style.cssText = 'pointer-events: all; cursor: pointer;';
                    }

                    resetBtn.addEventListener('click', (e) => {
                        e.preventDefault();

                        self.value = "";
                        if (typeof self.onSelect === 'function') {
                            self.onSelect('');
                        }

                        // Update the value of the input sibling
                        if (inputSibling) {
                            inputSibling.value = "";
                            
                            inputSibling.style.cssText = 'padding-left: 20px; pointer-events: all; cursor: pointer;';
                        }

                        // Update classname of the directorist-selected-icon
                        parent.querySelector('.directorist-selected-icon').setAttribute('class', `directorist-selected-icon`);

                        resetBtn.style.display = 'none';
                    });
                }
            }


            document.querySelector('.icon-picker__done-btn').addEventListener('click', (e) => {
                e.preventDefault();

                const id = e.target.closest('.icon-picker').getAttribute('data-icon-picker-id');
                const selector = document.querySelector(`.icon-picker-selector.icon-picker-id-${id}`);

                if (parseInt(id) !== self.id) {
                    return;
                }

                self.value = icon;

                if (typeof self.onSelect === 'function') {
                    self.onSelect(icon);
                }

                selector.querySelector('input').value = self.value;
                selector.querySelector('input').style.cssText = 'padding-left: 38px; pointer-events: none; cursor: auto;';
                selector.querySelector('.directorist-selected-icon').setAttribute('class', `directorist-selected-icon ${self.value}`);

                closeModal();

                // Show Reset Button after Icon Select
                if(selector.querySelector('input').value !== ''){
                    selector.querySelector('.icon-picker-selector__icon__reset').style.display = 'block';
                }
            });

            document.querySelector('.icon-picker__close').addEventListener('click', closeModal)
            document.body.addEventListener('click', (e) => {
                if (!e.target.closest('.icon-picker__inner') && !e.target.closest('.icon-picker-selector') && !e.target.closest('.icons-group-icons')) {
                    closeModal();
                }
            });

            /* Searchable input */
            const searchInput = document.querySelector('.icon-picker__filter input');

            function searchIcon() {
                const filter = searchInput.value.toUpperCase();
                const iconBtn = document.querySelectorAll('.font-icon-btn');
                iconBtn.forEach(elm => {
                    const textValue = elm.getAttribute('data-icon-key');
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        elm.style.display = "";
                    } else {
                        elm.style.display = "none";
                    }
                })
            }

            searchInput.addEventListener('keyup', searchIcon);

            /* Default icons pack */
            let iconFilter = document.querySelector('.icon-picker__filter select');
            let faPack = document.querySelector('.icons-group.fontAwesome');
            let laPack = document.querySelector('.icons-group.lineAwesome');

            function filterIconPack(sel) {
                if (sel.value === 'fontAwesome') {
                    faPack.style.display = 'block';
                    laPack.style.display = 'none';
                } else if (sel.value === 'lineAwesome') {
                    laPack.style.display = 'block';
                    faPack.style.display = 'none';
                }
            }

            iconFilter.addEventListener('change', function () {
                filterIconPack(this);
            });

            filterIconPack(iconFilter);
        },

        findIconGroupByIconType: function (type) {
            if (!this.icons) {
                return '';
            }

            for (const iconGroup in this.icons) {
                for (const iconType in this.icons[iconGroup].iconTypes) {
                    if (type !== this.icons[iconGroup].iconTypes[iconType].key) {
                        continue;
                    }

                    return iconGroup;
                }
            }

            return '';
        },

        getFullIcon(iconKey, iconGroupKey, iconType) {
            let prefix = '';
            if (typeof this.icons[iconGroupKey].iconTypes[iconType] !== 'undefined') {
                prefix = this.icons[iconGroupKey].iconTypes[iconType].key;
            }
            return `${prefix} ${iconKey}`;
        }
    }
};