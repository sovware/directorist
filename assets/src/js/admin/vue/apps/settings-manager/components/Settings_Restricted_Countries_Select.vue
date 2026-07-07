<template>
	<div
		ref="root"
		class="cptm-restricted-countries-select"
		:class="{ 'cptm-restricted-countries-select--open': isOpen }"
	>
		<label :for="searchId" class="cptm-restricted-countries-select__label">
			{{ labelText }}
		</label>
		<p
			v-if="descriptionText"
			class="cptm-restricted-countries-select__description"
		>
			{{ descriptionText }}
		</p>

		<div
			class="cptm-restricted-countries-select__control"
			@click="focusSearch"
		>
			<div class="cptm-restricted-countries-select__chips">
				<span
					v-for="selectedOption in selectedOptions"
					:key="selectedOption.value"
					class="cptm-restricted-countries-select__chip"
					:class="{
						'cptm-restricted-countries-select__chip--unknown':
							!selectedOption.exists,
					}"
				>
					<span class="cptm-restricted-countries-select__chip-text">
						{{ selectedOption.label }}
					</span>
					<button
						type="button"
						class="cptm-restricted-countries-select__chip-remove"
						:aria-label="`Remove ${selectedOption.label}`"
						@click.stop="removeValue(selectedOption.value)"
					>
						&times;
					</button>
				</span>

				<input
					:id="searchId"
					ref="search"
					v-model="searchTerm"
					type="text"
					class="cptm-restricted-countries-select__search"
					placeholder="Search countries"
					autocomplete="off"
					@focus="openDropdown"
					@keydown.backspace="handleBackspace"
					@keydown.esc.prevent="closeDropdown"
					@keydown.down.prevent="openDropdown"
				/>
			</div>
		</div>

		<div
			v-if="isOpen"
			class="cptm-restricted-countries-select__dropdown"
			role="listbox"
			:aria-labelledby="searchId"
		>
			<button
				v-for="option in filteredOptions"
				:key="option.value"
				type="button"
				class="cptm-restricted-countries-select__option"
				:class="{
					'cptm-restricted-countries-select__option--selected':
						isSelected(option.value),
				}"
				role="option"
				:aria-selected="isSelected(option.value) ? 'true' : 'false'"
				@mousedown.prevent
				@click="toggleOption(option.value)"
			>
				<span class="cptm-restricted-countries-select__option-label">
					{{ option.label }}
				</span>
				<span class="cptm-restricted-countries-select__option-code">
					{{ option.value }}
				</span>
				<span
					class="cptm-restricted-countries-select__option-check"
					aria-hidden="true"
				></span>
			</button>

			<div
				v-if="!filteredOptions.length"
				class="cptm-restricted-countries-select__empty"
			>
				No countries found.
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'settings-restricted-countries-select',

	props: {
		field: {
			type: Object,
			default: () => ({}),
		},
		fieldKey: {
			type: String,
			required: true,
		},
	},

	data() {
		return {
			debouncedSearchTerm: '',
			isOpen: false,
			searchTerm: '',
			searchDebounceTimer: null,
		};
	},

	computed: {
		searchId() {
			return `settings-${this.fieldKey}-search`;
		},

		labelText() {
			return this.field.label || 'Selected countries';
		},

		descriptionText() {
			return this.field.description || '';
		},

		normalizedOptions() {
			const options = this.field.options;

			if (Array.isArray(options)) {
				return options.map(this.normalizeOption);
			}

			if (options && typeof options === 'object') {
				return Object.keys(options).map((key) =>
					this.normalizeOption({
						value: key,
						label: options[key],
					})
				);
			}

			return [];
		},

		optionMap() {
			return this.normalizedOptions.reduce((map, option) => {
				map[option.value] = option;
				return map;
			}, {});
		},

		optionValues() {
			return this.normalizedOptions.map((option) => option.value);
		},

		currentValue() {
			return this.normalizeValue(this.field.value);
		},

		selectedOptions() {
			return this.currentValue.map((value) => {
				const normalizedValue = String(value);
				const option = this.optionMap[normalizedValue];

				if (option) {
					return {
						...option,
						exists: true,
					};
				}

				return {
					value: normalizedValue,
					label: normalizedValue,
					exists: false,
				};
			});
		},

		selectedKnownValues() {
			return this.currentValue
				.map((value) => String(value))
				.filter((value) => this.optionValues.includes(value));
		},

		preservedUnknownValues() {
			return this.currentValue
				.map((value) => String(value))
				.filter((value) => !this.optionValues.includes(value));
		},

		filteredOptions() {
			const query = this.debouncedSearchTerm.trim().toLowerCase();

			if (!query) {
				return this.normalizedOptions;
			}

			return this.normalizedOptions
				.map((option) => ({
					...option,
					searchRank: this.getSearchRank(option, query),
				}))
				.filter((option) => option.searchRank !== null)
				.sort((optionA, optionB) => {
					if (optionA.searchRank !== optionB.searchRank) {
						return optionA.searchRank - optionB.searchRank;
					}

					return optionA.label.localeCompare(optionB.label);
				})
				.slice(0, 50);
		},
	},

	watch: {
		searchTerm(value) {
			this.queueSearchTerm(value);
		},
	},

	mounted() {
		document.addEventListener('mousedown', this.handleDocumentMouseDown);
	},

	beforeDestroy() {
		document.removeEventListener('mousedown', this.handleDocumentMouseDown);
		this.clearSearchDebounce();
	},

	methods: {
		clearSearchDebounce() {
			if (!this.searchDebounceTimer) {
				return;
			}

			clearTimeout(this.searchDebounceTimer);
			this.searchDebounceTimer = null;
		},

		queueSearchTerm(value) {
			const normalizedValue = String(value || '').trim();

			this.clearSearchDebounce();

			if (!normalizedValue) {
				this.debouncedSearchTerm = '';
				return;
			}

			this.searchDebounceTimer = setTimeout(() => {
				this.debouncedSearchTerm = normalizedValue;
				this.searchDebounceTimer = null;
			}, 120);
		},

		normalizeOption(option = {}) {
			const value =
				typeof option.value !== 'undefined' ? option.value : option.label || '';
			const label =
				typeof option.label !== 'undefined' ? option.label : value;

			return {
				value: String(value),
				label: String(label),
			};
		},

		getSearchRank(option, query) {
			const label = option.label.toLowerCase();
			const value = option.value.toLowerCase();
			const words = label.split(/[\s,()/-]+/).filter(Boolean);

			if (label === query || value === query) {
				return 1;
			}

			if (label.startsWith(query)) {
				return 2;
			}

			if (words.some((word) => word.startsWith(query))) {
				return 3;
			}

			if (label.includes(query) || value.includes(query)) {
				return 4;
			}

			return null;
		},

		normalizeValue(value) {
			if (Array.isArray(value)) {
				return value.map((item) => String(item));
			}

			if (value === null || typeof value === 'undefined' || value === '') {
				return [];
			}

			return [String(value)];
		},

		emitValue(values) {
			this.$emit('update-field', {
				fieldKey: this.fieldKey,
				value: values,
			});
		},

		openDropdown() {
			this.isOpen = true;
		},

		closeDropdown() {
			this.isOpen = false;
		},

		focusSearch() {
			this.openDropdown();
			this.$nextTick(() => {
				if (this.$refs.search) {
					this.$refs.search.focus();
				}
			});
		},

		isSelected(value) {
			return this.currentValue.map((item) => String(item)).includes(String(value));
		},

		toggleOption(value) {
			const normalizedValue = String(value);
			let knownValues = [...this.selectedKnownValues];

			if (knownValues.includes(normalizedValue)) {
				knownValues = knownValues.filter((item) => item !== normalizedValue);
			} else {
				knownValues.push(normalizedValue);
			}

			this.emitValue([...this.preservedUnknownValues, ...knownValues]);
			this.searchTerm = '';
			this.focusSearch();
		},

		removeValue(value) {
			const normalizedValue = String(value);
			this.emitValue(
				this.currentValue.filter((item) => String(item) !== normalizedValue)
			);
			this.focusSearch();
		},

		handleBackspace() {
			if (this.searchTerm || !this.selectedOptions.length) {
				return;
			}

			const lastSelected = this.selectedOptions[this.selectedOptions.length - 1];
			this.removeValue(lastSelected.value);
		},

		handleDocumentMouseDown(event) {
			if (!this.$refs.root || this.$refs.root.contains(event.target)) {
				return;
			}

			this.closeDropdown();
		},
	},
};
</script>
