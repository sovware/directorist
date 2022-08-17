const IconPicker = (args) => {
    return {
        value: '',
        iconType: 'solid',
        container: null,
        onSelect: null,
        icons: null,
        init: function () {
            let _this = this;
            this.container = (typeof args.container !== 'undefined') ? args.container : this.container;
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
            let markup = '';
            for (const iconGroupKey of Object.keys(this.icons)) {
                markup += `<div class="icons-group ${iconGroupKey}">`;
                markup += `<h4>${this.icons[ iconGroupKey ].label}</h4>`;
                markup += `<div class="icons-group-icons">`;
                for (const icon of this.icons[iconGroupKey].icons) {
                    const fullIcon = this.getFullIcon(icon.key, iconGroupKey, icon.types[0]);
                    const buttonClass = (this.value === fullIcon) ? 'cptm-btn-primary' : 'cptm-btn-secondery';
                    markup += `
                        <button class="font-icon-btn cptm-btn ${buttonClass} ${fullIcon}" data-group-key="${iconGroupKey}" data-icon-key="${icon.key}" data-icon-type="${[icon.types]}"></button>
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
            <div class="icon-picker-selector">
                <div class="icon-picker-selector__icon">
                    <span class="directorist-selected-icon ${this.value}"></span>
                    <input
                    type="text"
                    placeholder="Click to select icon"
                    class="cptm-form-control"
                    value="${this.value}" style="${this.value ? 'padding-left: 38px' : '' }"
                    />
                </div>
                <button class="icon-picker-selector__btn">Change Icon</button>
            </div>
                `;
            this.container.innerHTML = markup;

            let iconPickerWrap = `
            <div class="icon-picker">
            <div class="icon-picker__inner">
                <a href="#" class="icon-picker__close"
                    ><span class="fas fa-times"></span
                ></a>
                <div class="icon-picker__sidebar">
                    <div class="icon-picker__filter">
                        <label for="">Filter By Name</label>
                        <input type="text" placeholder="Search" />
                    </div>
                    <div class="icon-picker__filter">
                        <label for="">Filter By Icon Pack</label>
                        <select>
                            <option value="fontAwesome">Font Awesome</option>
                            <option value="lineAwesome">Line Awesome</option>
                        </select>
                    </div>
                    <div class="icon-picker__preview">
                        <span class="icon-picker__preview-icon ${this.value}"></span>
                        <span class="icon-picker__preview-info">
                            <span class="icon-picker__icon-name">${selectedIcon[1]}</span>
                        </span>
                    </div>
                    <button class="cptm-btn cptm-btn-primary icon-picker__done-btn">Done</button>
                </div>
                <div class="icon-picker__content">
                <div id="iconsWrapperElm" class="iconsWrapperElm">

                </div>
            </div>
        </div>
            `;

            this.container.closest('body').insertAdjacentHTML('beforeend', iconPickerWrap)
        },

        attachEvents() {
            const iconButtons = document.querySelectorAll('.font-icon-btn');
            const self = this;
            let icon;
            //remove active status
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
                    self.container.closest('body').querySelector('.icon-picker__preview-icon').setAttribute('class', `icon-picker__preview-icon ${icon}`);
                    self.container.closest('body').querySelector('.icon-picker__icon-name').innerHTML = iconKey;
                    searchIcon();
                });
            })

            /* Icon picker modal */
            const iconPicker = document.querySelector('.icon-picker');

            function openModal() {
                iconPicker.classList.add('icon-picker-visible');
            }

            function closeModal() {
                iconPicker.classList.remove('icon-picker-visible');
            }
            document.querySelector('.icon-picker-selector .icon-picker-selector__btn').addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
            document.querySelector('.icon-picker__done-btn').addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
                if (typeof icon !== 'undefined') {
                    self.value = icon;
                    if (typeof self.onSelect === 'function') {
                        self.onSelect(icon);
                    }
                    document.querySelector('.icon-picker-selector input').style.paddingLeft = '38px';
                }
                //self.renderIcon();
                //self.attachEvents();
                self.container.closest('body').querySelector('.icon-picker-selector input').value = self.value;
                self.container.closest('body').querySelector('.directorist-selected-icon').setAttribute('class', `directorist-selected-icon ${self.value}`);
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

        getFullIcon(iconKey, iconGroupKey, iconType) {
            let prefix = '';
            if (typeof this.icons[iconGroupKey].iconTypes[iconType] !== 'undefined') {
                prefix = this.icons[iconGroupKey].iconTypes[iconType].key;
            }
            return `${prefix} ${iconKey}`;
        }
    }
};

export {
    IconPicker
};