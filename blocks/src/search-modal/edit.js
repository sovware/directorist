/**
 * External dependencies
 */
/* eslint-disable @wordpress/no-unsafe-wp-apis */
import clsx from 'clsx';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { search } from '@wordpress/icons';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import {
	AlignmentControl,
	BlockControls,
	InspectorControls,
	RichText,
	useBlockProps,
	__experimentalUseBorderProps as useBorderProps,
	__experimentalUseColorProps as useColorProps,
	__experimentalGetSpacingClassesAndStyles as useSpacingProps,
	__experimentalGetShadowClassesAndStyles as useShadowProps,
	__experimentalGetElementClassName,
} from '@wordpress/block-editor';

import {
	ModalIconControls,
	ModalIconPreview,
} from '../components/modal-icon-controls';

const SEARCH_ICON_PRESETS = [
	{ label: __( 'Search', 'directorist' ), value: 'fas fa-search' },
	{ label: __( 'Filter', 'directorist' ), value: 'las la-filter' },
	{ label: __( 'Find', 'directorist' ), value: 'las la-search-location' },
	{ label: __( 'Explore', 'directorist' ), value: 'las la-compass' },
];

export default function Edit( { attributes, setAttributes, className } ) {
	const {
		textAlign,
		placeholder,
		style,
		text,
		width,
		styleDisplay,
		iconSource,
		iconUrl,
		iconSize,
		iconColor,
		iconPosition,
		iconGap,
		accessibleLabel,
	} = attributes;
	const borderProps = useBorderProps( attributes );
	const colorProps = useColorProps( attributes );
	const spacingProps = useSpacingProps( attributes );
	const shadowProps = useShadowProps( attributes );
	const blockProps = useBlockProps( {
		className: clsx( className, {
			[ `has-custom-width wp-block-button__width-${ width }` ]: width,
			'has-custom-font-size': style?.typography?.fontSize,
		} ),
	} );
	const showIcon = 'text' !== styleDisplay;
	const showText = 'icon' !== styleDisplay;
	const icon = showIcon ? (
		<ModalIconPreview
			source={ iconSource }
			url={ iconUrl }
			size={ iconSize }
			color={ iconColor }
			fallback={ search }
		/>
	) : null;

	return (
		<>
			<div { ...blockProps }>
				<div
					className={ clsx(
						'wp-block-button__link',
						'directorist-modal-trigger',
						colorProps.className,
						borderProps.className,
						{
							[ `has-text-align-${ textAlign }` ]: textAlign,
							'no-border-radius': style?.border?.radius === 0,
							'directorist-modal-trigger--reverse':
								'after' === iconPosition,
						},
						__experimentalGetElementClassName( 'button' )
					) }
					style={ {
						...borderProps.style,
						...colorProps.style,
						...spacingProps.style,
						...shadowProps.style,
						gap: iconGap,
					} }
					role="button"
					aria-label={ accessibleLabel }
				>
					{ icon }
					{ showText && (
						<RichText
							tagName="span"
							aria-label={ __( 'Button text', 'directorist' ) }
							placeholder={
								placeholder || __( 'Search', 'directorist' )
							}
							value={ text }
							onChange={ ( value ) =>
								setAttributes( { text: value } )
							}
							withoutInteractiveFormatting
						/>
					) }
				</div>
			</div>

			<BlockControls group="block">
				<AlignmentControl
					value={ textAlign }
					onChange={ ( value ) =>
						setAttributes( { textAlign: value } )
					}
				/>
			</BlockControls>

			<InspectorControls>
				<PanelBody title={ __( 'Trigger', 'directorist' ) }>
					<SelectControl
						label={ __( 'Display', 'directorist' ) }
						value={ styleDisplay }
						options={ [
							{
								label: __( 'Icon only', 'directorist' ),
								value: 'icon',
							},
							{
								label: __( 'Text only', 'directorist' ),
								value: 'text',
							},
							{
								label: __( 'Icon and text', 'directorist' ),
								value: 'icon_and_text',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { styleDisplay: value } )
						}
						__nextHasNoMarginBottom
					/>
					<TextControl
						label={ __( 'Accessible label', 'directorist' ) }
						value={ accessibleLabel }
						help={ __(
							'Describes the trigger for screen readers.',
							'directorist'
						) }
						onChange={ ( value ) =>
							setAttributes( { accessibleLabel: value } )
						}
					/>
					<RangeControl
						label={ __( 'Button width', 'directorist' ) }
						value={ width }
						min={ 10 }
						max={ 100 }
						onChange={ ( value ) =>
							setAttributes( { width: value } )
						}
						allowReset
					/>
				</PanelBody>

				{ showIcon && (
					<PanelBody
						title={ __( 'Icon', 'directorist' ) }
						initialOpen={ false }
					>
						<ModalIconControls
							attributes={ attributes }
							setAttributes={ setAttributes }
							presets={ SEARCH_ICON_PRESETS }
						/>
					</PanelBody>
				) }
			</InspectorControls>
		</>
	);
}
