/**
 * External dependencies
 */
/* eslint-disable @wordpress/no-unsafe-wp-apis */
import clsx from 'clsx';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { commentAuthorAvatar } from '@wordpress/icons';
import {
	Button,
	PanelBody,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import {
	AlignmentControl,
	BlockControls,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
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

const ACCOUNT_ICON_PRESETS = [
	{ label: __( 'User', 'directorist' ), value: 'las la-user' },
	{ label: __( 'Account', 'directorist' ), value: 'las la-user-circle' },
	{ label: __( 'Sign in', 'directorist' ), value: 'las la-sign-in-alt' },
	{ label: __( 'Menu', 'directorist' ), value: 'las la-bars' },
];

export default function Edit( { attributes, setAttributes, className } ) {
	const {
		textAlign,
		placeholder,
		style,
		text,
		width,
		showDashboardMenu,
		styleDisplay,
		iconSource,
		iconUrl,
		iconSize,
		iconColor,
		iconPosition,
		iconGap,
		accessibleLabel,
		loggedInDisplay,
		avatarSource,
		avatarId,
		avatarUrl,
		avatarAlt,
		avatarSize,
		avatarRadius,
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
	const triggerDisplay = styleDisplay || ( text ? 'text' : 'icon' );
	const showIcon = 'text' !== triggerDisplay;
	const showText = 'icon' !== triggerDisplay;
	const icon = showIcon ? (
		<ModalIconPreview
			source={ iconSource }
			url={ iconUrl }
			size={ iconSize }
			color={ iconColor }
			fallback={ commentAuthorAvatar }
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
							'directorist-modal-trigger--icon-only':
								'icon' === triggerDisplay,
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
								placeholder || __( 'Account', 'directorist' )
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
				<PanelBody title={ __( 'Logged-out trigger', 'directorist' ) }>
					<SelectControl
						label={ __( 'Display', 'directorist' ) }
						value={ triggerDisplay }
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
						title={ __( 'Logged-out icon', 'directorist' ) }
						initialOpen={ false }
					>
						<ModalIconControls
							attributes={ attributes }
							setAttributes={ setAttributes }
							presets={ ACCOUNT_ICON_PRESETS }
						/>
					</PanelBody>
				) }

				<PanelBody
					title={ __( 'Logged-in author', 'directorist' ) }
					initialOpen={ false }
				>
					<SelectControl
						label={ __( 'Display', 'directorist' ) }
						value={ loggedInDisplay }
						options={ [
							{
								label: __( 'Avatar', 'directorist' ),
								value: 'avatar',
							},
							{
								label: __(
									'Avatar and display name',
									'directorist'
								),
								value: 'avatar_and_name',
							},
							{
								label: __( 'Icon', 'directorist' ),
								value: 'icon',
							},
							{
								label: __(
									'Icon and display name',
									'directorist'
								),
								value: 'icon_and_name',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { loggedInDisplay: value } )
						}
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						checked={ showDashboardMenu }
						label={ __( 'Enable dashboard menu', 'directorist' ) }
						onChange={ ( value ) =>
							setAttributes( { showDashboardMenu: value } )
						}
					/>

					{ loggedInDisplay.startsWith( 'avatar' ) && (
						<>
							<SelectControl
								label={ __( 'Avatar source', 'directorist' ) }
								value={ avatarSource }
								options={ [
									{
										label: __(
											'Current user',
											'directorist'
										),
										value: 'user',
									},
									{
										label: __(
											'Media Library',
											'directorist'
										),
										value: 'image',
									},
								] }
								onChange={ ( value ) =>
									setAttributes( { avatarSource: value } )
								}
								__nextHasNoMarginBottom
							/>
							{ 'image' === avatarSource && (
								<>
									<MediaUploadCheck>
										<MediaUpload
											onSelect={ ( media ) =>
												setAttributes( {
													avatarId: media.id,
													avatarUrl: media.url,
													avatarAlt: media.alt || '',
												} )
											}
											allowedTypes={ [ 'image' ] }
											value={ avatarId }
											render={ ( { open } ) => (
												<Button
													variant="secondary"
													onClick={ open }
												>
													{ avatarUrl
														? __(
																'Replace avatar',
																'directorist'
														  )
														: __(
																'Choose avatar',
																'directorist'
														  ) }
												</Button>
											) }
										/>
									</MediaUploadCheck>
									<TextControl
										label={ __(
											'Alternative text',
											'directorist'
										) }
										value={ avatarAlt }
										onChange={ ( value ) =>
											setAttributes( {
												avatarAlt: value,
											} )
										}
									/>
								</>
							) }
							<RangeControl
								label={ __( 'Avatar size', 'directorist' ) }
								value={ avatarSize }
								min={ 20 }
								max={ 120 }
								onChange={ ( value ) =>
									setAttributes( { avatarSize: value } )
								}
							/>
							<RangeControl
								label={ __(
									'Avatar roundness',
									'directorist'
								) }
								value={ avatarRadius }
								min={ 0 }
								max={ 50 }
								onChange={ ( value ) =>
									setAttributes( { avatarRadius: value } )
								}
							/>
						</>
					) }

					{ loggedInDisplay.startsWith( 'icon' ) && (
						<ModalIconControls
							attributes={ attributes }
							setAttributes={ setAttributes }
							prefix="author"
							presets={ ACCOUNT_ICON_PRESETS }
							showPosition={ false }
						/>
					) }
				</PanelBody>
			</InspectorControls>
		</>
	);
}
