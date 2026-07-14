<template>
	<div
		class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap"
	>
		<div
			class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap"
		>
			<span
				class="cptm-widget-badge-icon"
				:class="icon"
				v-if="icon"
			></span>
			<span class="cptm-widget-badge-label" v-if="label">{{
				label
			}}</span>
			<span
				class="cptm-widget-badge-trash"
				@click.stop="$emit('trash')"
				v-if="!readOnly"
			>
				<span class="las la-times"></span>
			</span>
		</div>
	</div>
</template>

<script>
export default {
	name: 'badge-card-widget',
	props: {
		widgetKey: {
			type: String,
		},

		icon: {
			type: String,
			default: '',
		},

		label: {
			type: String,
			default: '',
		},

		options: {
			type: [Object, Array],
			default: () => ({}),
		},

		fields: {
			type: Object,
			default: () => ({}),
		},

		readOnly: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
