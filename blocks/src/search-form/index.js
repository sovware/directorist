import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { TypesControl } from '../controls';

import {
	PanelRow,
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

import {
	getAttsForTransform,
	isMultiDirectoryEnabled,
	getPlaceholder,
} from '../functions';
import metadata from './block.json';
import getLogo from '../logo';

const Placeholder = () => getPlaceholder('search');
const SectionTitle = ({ children }) => (
	<div style={{ marginTop: '0px', marginBottom: '8px' }}>
		<h3 style={{ fontSize: '14px', fontWeight: 600, margin: 0 }}>
			{children}
		</h3>
	</div>
);
const Divider = () => (
	<PanelRow>
		<hr
			style={{
				width: '100%',
				border: 0,
				borderTop: '1px solid #ddd',
				margin: '16px 0',
			}}
		/>
	</PanelRow>
);

registerBlockType(metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_search_listing',
				attributes: getAttsForTransform(metadata.attributes),
			},
		],
	},

	edit({ attributes, setAttributes }) {
		const [shouldRender, setShouldRender] = useState(true);

		let {
			show_title_subtitle,
			search_bar_title,
			search_bar_sub_title,
			more_filters_button,
			more_filters_text,
			reset_filters_button,
			apply_filters_button,
			reset_filters_text,
			apply_filters_text,
			more_filters_display,
			logged_in_user_only,
			directory_type,
			default_directory_type,
			show_popular_category,
			align,
			title_align,
			type_nav_display,
			search_button_text,
			category_align,
		} = attributes;

		let oldTypes = directory_type ? directory_type.split(',') : [];

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={__('General', 'directorist')}
						initialOpen={true}
					>
						<SectionTitle>
							{__('Heading area', 'directorist')}
						</SectionTitle>

						<ToggleControl
							label={__('Display Header Section', 'directorist')}
							checked={show_title_subtitle}
							onChange={(newState) =>
								setAttributes({
									show_title_subtitle: newState,
								})
							}
						/>

						{show_title_subtitle ? (
							<TextControl
								label={__('Title', 'directorist')}
								type="text"
								value={search_bar_title}
								onChange={(newState) =>
									setAttributes({
										search_bar_title: newState,
									})
								}
							/>
						) : null}
						{show_title_subtitle ? (
							<TextControl
								label={__('Subtitle', 'directorist')}
								type="text"
								value={search_bar_sub_title}
								onChange={(newState) =>
									setAttributes({
										search_bar_sub_title: newState,
									})
								}
							/>
						) : null}

						<ToggleGroupControl
							label={__(
								'Title & subtitle Alignment',
								'directorist'
							)}
							value={title_align}
							onChange={(value) =>
								setAttributes({ title_align: value })
							}
							isBlock
						>
							<ToggleGroupControlOption
								value="left"
								label={__('Left', 'directorist')}
								aria-label={__('Left', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="center"
								label={__('Center', 'directorist')}
								aria-label={__('Center', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="right"
								label={__('Right', 'directorist')}
								aria-label={__('Right', 'directorist')}
							/>
						</ToggleGroupControl>
						<Divider />

						<SectionTitle>
							{__('Directory Type Area', 'directorist')}
						</SectionTitle>

						{isMultiDirectoryEnabled() ? (
							<TypesControl
								shouldRender={shouldRender}
								selected={oldTypes}
								showDefault={true}
								defaultType={default_directory_type}
								onDefaultChange={(value) =>
									setAttributes({
										default_directory_type: value,
									})
								}
								onChange={(types) => {
									setAttributes({
										directory_type: types.join(','),
									});

									if (types.length === 1) {
										setAttributes({
											default_directory_type: types[0],
										});
									}

									setShouldRender(false);
								}}
							/>
						) : null}

						<ToggleGroupControl
							label={__('Type Alignment', 'directorist')}
							value={align}
							onChange={(value) =>
								setAttributes({ align: value })
							}
							isBlock
						>
							<ToggleGroupControlOption
								value="start"
								label={__('Left', 'directorist')}
								aria-label={__('Left', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="center"
								label={__('Center', 'directorist')}
								aria-label={__('Center', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="end"
								label={__('Right', 'directorist')}
								aria-label={__('Right', 'directorist')}
							/>
						</ToggleGroupControl>

						<ToggleGroupControl
							label={__('Icon Position', 'directorist')}
							value={type_nav_display}
							onChange={(value) =>
								setAttributes({ type_nav_display: value })
							}
							isBlock
						>
							<ToggleGroupControlOption
								value="column"
								label="↑"
								aria-label={__('Default', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="column-reverse"
								label="↓"
								aria-label={__('Column Reverse', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="row"
								label="←"
								aria-label={__('Row', 'directorist')}
							/>
							<ToggleGroupControlOption
								value="row-reverse"
								label="→"
								aria-label={__('Row Reverse', 'directorist')}
							/>
						</ToggleGroupControl>
						<Divider />
						<SectionTitle>
							{__('Search Form', 'directorist')}
						</SectionTitle>

						<TextControl
							label={__('Search Button Label', 'directorist')}
							type="text"
							value={search_button_text}
							onChange={(newState) =>
								setAttributes({
									search_button_text: newState,
								})
							}
						/>

						<ToggleControl
							label={__('Enable Advanced Filters', 'directorist')}
							checked={more_filters_button}
							onChange={(newState) =>
								setAttributes({
									more_filters_button: newState,
								})
							}
						/>

						{more_filters_button ? (
							<ToggleControl
								label={__('Enable Reset Button', 'directorist')}
								checked={reset_filters_button}
								onChange={(newState) =>
									setAttributes({
										reset_filters_button: newState,
									})
								}
							/>
						) : null}

						{more_filters_button ? (
							<ToggleControl
								label={__('Enable Apply Button', 'directorist')}
								checked={apply_filters_button}
								onChange={(newState) =>
									setAttributes({
										apply_filters_button: newState,
									})
								}
							/>
						) : null}

						{more_filters_button ? (
							<TextControl
								label={__('Button Label', 'directorist')}
								type="text"
								value={more_filters_text}
								onChange={(newState) =>
									setAttributes({
										more_filters_text: newState,
									})
								}
							/>
						) : null}

						{more_filters_button && reset_filters_button ? (
							<TextControl
								label={__('Button Label', 'directorist')}
								type="text"
								value={reset_filters_text}
								onChange={(newState) =>
									setAttributes({
										reset_filters_text: newState,
									})
								}
							/>
						) : null}

						{more_filters_button && apply_filters_button ? (
							<TextControl
								label={__('Button Label', 'directorist')}
								type="text"
								value={apply_filters_text}
								onChange={(newState) =>
									setAttributes({
										apply_filters_text: newState,
									})
								}
							/>
						) : null}

						{more_filters_button ? (
							<SelectControl
								label={__('More Filter By', 'directorist')}
								labelPosition="side"
								value={more_filters_display}
								options={[
									{
										label: __('Overlapping', 'directorist'),
										value: 'overlapping',
									},
									{
										label: __('Sliding', 'directorist'),
										value: 'sliding',
									},
									{
										label: __('Always Open', 'directorist'),
										value: 'always_open',
									},
								]}
								onChange={(newState) =>
									setAttributes({
										more_filters_display: newState,
									})
								}
								className="directorist-gb-fixed-control"
							/>
						) : null}

						<Divider />
						<SectionTitle>
							{__('Popular Category', 'directorist')}
						</SectionTitle>

						<ToggleControl
							label={__(
								'Enable Popular Categories',
								'directorist'
							)}
							checked={show_popular_category}
							onChange={(newState) =>
								setAttributes({
									show_popular_category: newState,
								})
							}
						/>

						{show_popular_category ? (
							<ToggleGroupControl
								label={__('Category Alignment', 'directorist')}
								value={category_align}
								onChange={(value) =>
									setAttributes({ category_align: value })
								}
								isBlock
							>
								<ToggleGroupControlOption
									value="left"
									label={__('Left', 'directorist')}
									aria-label={__('Left', 'directorist')}
								/>
								<ToggleGroupControlOption
									value="center"
									label={__('Center', 'directorist')}
									aria-label={__('Center', 'directorist')}
								/>
								<ToggleGroupControlOption
									value="right"
									label={__('Right', 'directorist')}
									aria-label={__('Right', 'directorist')}
								/>
							</ToggleGroupControl>
						) : null}

						<Divider />

						<ToggleControl
							label={__('Enable Authentication', 'directorist')}
							checked={logged_in_user_only}
							onChange={(newState) =>
								setAttributes({ logged_in_user_only: newState })
							}
						/>
					</PanelBody>
				</InspectorControls>

				<div
					{...useBlockProps({
						className:
							'directorist-content-active directorist-w-100',
					})}
				>
					<ServerSideRender
						block={metadata.name}
						attributes={attributes}
						LoadingResponsePlaceholder={Placeholder}
					/>
				</div>
			</Fragment>
		);
	},
});
