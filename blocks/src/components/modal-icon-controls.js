/**
 * WordPress dependencies
 */
/* global directoristBlockConfig */
import { __ } from '@wordpress/i18n';
import {
	Button,
	ButtonGroup,
	ColorPalette,
	RangeControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

export function ModalIconPreview( {
	source,
	url,
	iconClass,
	size = 24,
	color,
	fallback,
} ) {
	if ( 'image' === source && url ) {
		return (
			<img
				className="directorist-modal-trigger__custom-icon"
				src={ url }
				alt=""
				style={ { width: size, height: size } }
			/>
		);
	}

	const [ prefix, name ] = ( iconClass || '' ).trim().split( /\s+/ );
	const cleanName = name ? name.replace( /^(?:fa|la|uil|uis)-/, '' ) : '';
	let iconPath = '';

	if ( cleanName && [ 'fas', 'far', 'fab' ].includes( prefix ) ) {
		const directory = {
			fas: 'solid',
			far: 'regular',
			fab: 'brands',
		}[ prefix ];
		iconPath = `font-awesome/svgs/${ directory }/${ cleanName }.svg`;
	} else if ( cleanName && [ 'las', 'lar', 'lab' ].includes( prefix ) ) {
		iconPath = `line-awesome/svgs/${ cleanName }${
			'las' === prefix ? '-solid' : ''
		}.svg`;
	} else if ( cleanName && [ 'uil', 'uis' ].includes( prefix ) ) {
		iconPath = `unicons/svgs/${
			'uis' === prefix ? 'solid' : 'line'
		}/${ cleanName }.svg`;
	}

	if ( iconPath && directoristBlockConfig?.iconUrl ) {
		return (
			<span
				className="directorist-modal-trigger__editor-icon directorist-modal-trigger__editor-icon--directorist"
				style={ {
					width: size,
					height: size,
					backgroundColor: color || 'currentColor',
					WebkitMaskImage: `url(${ directoristBlockConfig.iconUrl }${ iconPath })`,
					maskImage: `url(${ directoristBlockConfig.iconUrl }${ iconPath })`,
				} }
				aria-hidden="true"
			/>
		);
	}

	return (
		<span
			className="directorist-modal-trigger__editor-icon"
			style={ { width: size, height: size, color } }
			aria-hidden="true"
		>
			{ fallback }
		</span>
	);
}

export function ModalIconControls( {
	attributes,
	setAttributes,
	prefix = '',
	presets,
	showPosition = true,
} ) {
	const key = ( name ) =>
		prefix
			? `${ prefix }${ name }`
			: `${ name.charAt( 0 ).toLowerCase() }${ name.slice( 1 ) }`;
	const source = attributes[ key( 'IconSource' ) ] || 'library';
	const iconClass = attributes[ key( 'IconClass' ) ] || presets[ 0 ].value;
	const iconUrl = attributes[ key( 'IconUrl' ) ] || '';

	return (
		<>
			<SelectControl
				label={ __( 'Icon source', 'directorist' ) }
				value={ source }
				options={ [
					{
						label: __( 'Directorist icon', 'directorist' ),
						value: 'library',
					},
					{
						label: __( 'Media Library', 'directorist' ),
						value: 'image',
					},
				] }
				onChange={ ( value ) =>
					setAttributes( { [ key( 'IconSource' ) ]: value } )
				}
				__nextHasNoMarginBottom
			/>

			{ 'library' === source && (
				<>
					<p className="components-base-control__label">
						{ __( 'Quick select', 'directorist' ) }
					</p>
					<ButtonGroup className="directorist-modal-icon-presets">
						{ presets.map( ( preset ) => (
							<Button
								key={ preset.value }
								size="small"
								variant={
									preset.value === iconClass
										? 'primary'
										: 'secondary'
								}
								onClick={ () =>
									setAttributes( {
										[ key( 'IconClass' ) ]: preset.value,
									} )
								}
							>
								{ preset.label }
							</Button>
						) ) }
					</ButtonGroup>
					<TextControl
						label={ __( 'Custom icon class', 'directorist' ) }
						value={ iconClass }
						help={ __(
							'Use a supported Font Awesome or Line Awesome class, for example "las la-star".',
							'directorist'
						) }
						onChange={ ( value ) =>
							setAttributes( { [ key( 'IconClass' ) ]: value } )
						}
					/>
				</>
			) }

			{ 'image' === source && (
				<>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) =>
								setAttributes( {
									[ key( 'IconId' ) ]: media.id,
									[ key( 'IconUrl' ) ]: media.url,
								} )
							}
							allowedTypes={ [ 'image' ] }
							value={ attributes[ key( 'IconId' ) ] }
							render={ ( { open } ) => (
								<Button variant="secondary" onClick={ open }>
									{ iconUrl
										? __( 'Replace icon', 'directorist' )
										: __( 'Choose icon', 'directorist' ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>
					{ iconUrl && (
						<Button
							isDestructive
							variant="link"
							onClick={ () =>
								setAttributes( {
									[ key( 'IconId' ) ]: 0,
									[ key( 'IconUrl' ) ]: '',
								} )
							}
						>
							{ __( 'Remove icon', 'directorist' ) }
						</Button>
					) }
				</>
			) }

			<RangeControl
				label={ __( 'Icon size', 'directorist' ) }
				value={ attributes[ key( 'IconSize' ) ] || 24 }
				min={ 12 }
				max={ 96 }
				onChange={ ( value ) =>
					setAttributes( { [ key( 'IconSize' ) ]: value } )
				}
			/>
			<p className="components-base-control__label">
				{ __( 'Icon color', 'directorist' ) }
			</p>
			<ColorPalette
				value={ attributes[ key( 'IconColor' ) ] }
				onChange={ ( value ) =>
					setAttributes( { [ key( 'IconColor' ) ]: value || '' } )
				}
				clearable
			/>
			{ showPosition && (
				<>
					<SelectControl
						label={ __( 'Icon position', 'directorist' ) }
						value={
							attributes[ key( 'IconPosition' ) ] || 'before'
						}
						options={ [
							{
								label: __( 'Before text', 'directorist' ),
								value: 'before',
							},
							{
								label: __( 'After text', 'directorist' ),
								value: 'after',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( {
								[ key( 'IconPosition' ) ]: value,
							} )
						}
						__nextHasNoMarginBottom
					/>
					<RangeControl
						label={ __( 'Icon spacing', 'directorist' ) }
						value={ attributes[ key( 'IconGap' ) ] ?? 8 }
						min={ 0 }
						max={ 48 }
						onChange={ ( value ) =>
							setAttributes( { [ key( 'IconGap' ) ]: value } )
						}
					/>
				</>
			) }
		</>
	);
}
