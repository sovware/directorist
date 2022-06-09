const IconPicker = (args) => {
    return {
        value: '',
        iconType: 'solid',
        container: null,
        onSelect: null,
        icons: null,
        selectedIcon: null,

        init: function () {
            this.container = (typeof args.container !== 'undefined') ? args.container : this.container;
            this.selectedIcon = (typeof args.selectedIcon !== 'undefined') ? args.selectedIcon : this.selectedIcon;
            this.onSelect = (typeof args.onSelect !== 'undefined') ? args.onSelect : this.onSelect;
            this.icons = (typeof args.icons !== 'undefined') ? args.icons : this.icons;
            this.value = (typeof args.value === 'string') ? args.value : this.value;

            if (!this.container) {
                return;
            }

            this.renderIcons();
            this.attachEvents();
        },

        renderIcons() {
            let markup = '';
            let _this = this;
            const iconButtons = document.querySelectorAll('.font-icon-btn');
            markup += `<div class="cptm-form-group icon-picker-selector">
                            <label for="">Icon</label>
                            <input
                            type="text"
                            placeholder="Click to select icon"
                            class="cptm-form-control"
                            />
                        </div>
                        <div class="icon-picker">
                            <div class="icon-picker__inner">
                            <a href="#" class="icon-picker__close"
                                ><span class="fa-solid fa-xmark"></span
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
                            `;
            for (const iconButton of iconButtons) {
                iconButton.addEventListener('click', function (event) {
                    const iconKey = event.target.getAttribute('data-icon-key');
                    const iconGroupKey = event.target.getAttribute('data-group-key');
                    const iconType = event.target.getAttribute('data-icon-type').split(',');
                    const fullIcon = _this.getFullIcon(iconKey, iconGroupKey, iconType[0]);
                    markup += `
                    <div class="icon-picker__preview">
                        <span class="icon-picker__preview-icon ${fullIcon}"></span>
                        <span class="icon-picker__preview-info">
                            <span class="icon-picker__icon-name">${iconKey}</span>
                            <span class="icon-picker__action">Remove</span>
                        </span>
                    </div>
                    `;
                });
            }
            for (const iconGroupKey of Object.keys(this.icons)) {
                markup += `<div class="icon-picker__content"><div class="icons-group ${iconGroupKey}">`;
                markup += `<h4>${this.icons[ iconGroupKey ].label}</h4>`;
                markup += `<div class="icons-group-icons">`;
                for (const icon of this.icons[iconGroupKey].icons) {
                    const fullIcon = this.getFullIcon(icon.key, iconGroupKey, icon.types[0]);
                    const buttonClass = (this.value === fullIcon) ? 'cptm-btn-primary' : 'cptm-btn-secondery';
                    markup += `
                        <button class="font-icon-btn cptm-btn ${buttonClass} ${fullIcon}" data-group-key="${iconGroupKey}" data-icon-key="${icon.key}" data-icon-type="${[icon.types]}"></button>
                    `;
                }
                markup += `</div>`;
                markup += `</div></div></div></div>`;
                markup += `<button class="cptm-btn cptm-btn-primary icon-picker__done-btn">Done</button></div>`
            }
            this.container.innerHTML = markup;
        },

        attachEvents() {
            const iconButtons = document.querySelectorAll('.font-icon-btn');
            const self = this;

            for (const iconButton of iconButtons) {
                iconButton.addEventListener('click', function (event) {
                    const iconGroupKey = event.target.getAttribute('data-group-key');
                    const iconKey = event.target.getAttribute('data-icon-key');
                    const iconType = event.target.getAttribute('data-icon-type').split(',');
                    const icon = self.getFullIcon(iconKey, iconGroupKey, iconType[0]);
                    self.value = icon;
                    self.renderIcons();
                    self.attachEvents();
                    if (typeof self.onSelect === 'function') {
                        self.onSelect(icon);
                    }
                    searchIcon();
                });
            }

            /* Icon picker modal */
            const iconPicker = document.querySelector('.icon-picker');

            function openModal() {
                iconPicker.classList.add('icon-picker-visible');
            }

            function closeModal() {
                iconPicker.classList.remove('icon-picker-visible');
            }
            document.querySelector('.icon-picker-selector input').addEventListener('click', () => {
                //self.renderIcons();
                openModal();
            });
            document.querySelector('.icon-picker__done-btn').addEventListener('click', closeModal);
            document.querySelector('.icon-picker__close').addEventListener('click', closeModal)
            document.body.addEventListener('click', (e) => {
                if (!e.target.closest('.icon-picker__inner') && !e.target.closest('.icon-picker-selector') && !e.target.closest('.font-icon-btn')) {
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