/**
 * External dependencies
 */
import clsx from 'clsx';

function removeAnchorTag( value ) {
	// To do: Refactor this to use rich text's removeFormat instead.
	return value.toString().replace( /<\/?a[^>]*>/g, '' );
}

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState, useRef } from '@wordpress/element';
import {
	Button,
	ButtonGroup,
	PanelBody,
	ToggleControl,
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
	store as blockEditorStore,
	useBlockEditingMode,
} from '@wordpress/block-editor';
import { isKeyboardEvent, ENTER } from '@wordpress/keycodes';
import {
	createBlock,
	cloneBlock,
	getDefaultBlockName,
} from '@wordpress/blocks';
import { useMergeRefs, useRefEffect } from '@wordpress/compose';
import { useSelect, useDispatch } from '@wordpress/data';

function useEnter( props ) {
	const { replaceBlocks, selectionChange } = useDispatch( blockEditorStore );
	const { getBlock, getBlockRootClientId, getBlockIndex } =
		useSelect( blockEditorStore );
	const propsRef = useRef( props );
	propsRef.current = props;
	return useRefEffect( ( element ) => {
		function onKeyDown( event ) {
			if ( event.defaultPrevented || event.keyCode !== ENTER ) {
				return;
			}
			const { content, clientId } = propsRef.current;
			if ( content.length ) {
				return;
			}
			event.preventDefault();
			const topParentListBlock = getBlock(
				getBlockRootClientId( clientId )
			);
			const blockIndex = getBlockIndex( clientId );
			const head = cloneBlock( {
				...topParentListBlock,
				innerBlocks: topParentListBlock.innerBlocks.slice(
					0,
					blockIndex
				),
			} );
			const middle = createBlock( getDefaultBlockName() );
			const after = topParentListBlock.innerBlocks.slice(
				blockIndex + 1
			);
			const tail = after.length
				? [
						cloneBlock( {
							...topParentListBlock,
							innerBlocks: after,
						} ),
				  ]
				: [];
			replaceBlocks(
				topParentListBlock.clientId,
				[ head, middle, ...tail ],
				1
			);
			// We manually change the selection here because we are replacing
			// a different block than the selected one.
			selectionChange( middle.clientId );
		}

		element.addEventListener( 'keydown', onKeyDown );
		return () => {
			element.removeEventListener( 'keydown', onKeyDown );
		};
	}, [] );
}

function WidthPanel( { selectedWidth, setAttributes } ) {
	function handleChange( newWidth ) {
		// Check if we are toggling the width off
		const width = selectedWidth === newWidth ? undefined : newWidth;

		// Update attributes.
		setAttributes( { width } );
	}

	return (
		<ButtonGroup aria-label={ __( 'Button width' ) }>
			{ [ 25, 50, 75, 100 ].map( ( widthValue ) => {
				return (
					<Button
						key={ widthValue }
						size="small"
						variant={
							widthValue === selectedWidth ? 'primary' : undefined
						}
						onClick={ () => handleChange( widthValue ) }
					>
						{ widthValue }%
					</Button>
				);
			} ) }
		</ButtonGroup>
	);
}

function ButtonEdit( props ) {
	const {
		attributes,
		setAttributes,
		className,
		onReplace,
		mergeBlocks,
		clientId,
	} = props;
	const { textAlign, placeholder, style, text, width, showDashboardMenu } =
		attributes;

	function onKeyDown( event ) {
		if ( isKeyboardEvent.primary( event, 'k' ) ) {
			startEditing( event );
		} else if ( isKeyboardEvent.primaryShift( event, 'k' ) ) {
			unlink();
			richTextRef.current?.focus();
		}
	}

	// Use internal state instead of a ref to make sure that the component
	// re-renders when the popover's anchor updates.
	const [ popoverAnchor, setPopoverAnchor ] = useState( null );

	const borderProps = useBorderProps( attributes );
	const colorProps = useColorProps( attributes );
	const spacingProps = useSpacingProps( attributes );
	const shadowProps = useShadowProps( attributes );
	const ref = useRef();
	const richTextRef = useRef();
	const blockProps = useBlockProps( {
		ref: useMergeRefs( [ setPopoverAnchor, ref ] ),
		onKeyDown,
	} );
	const blockEditingMode = useBlockEditingMode();

	function startEditing( event ) {
		event.preventDefault();
	}

	function unlink() {
		setAttributes( {
			url: undefined,
			linkTarget: undefined,
			rel: undefined,
		} );
	}

	const useEnterRef = useEnter( { content: text, clientId } );
	const mergedRef = useMergeRefs( [ useEnterRef, richTextRef ] );

	return (
		<>
			<div
				{ ...blockProps }
				className={ clsx( blockProps.className, {
					[ `has-custom-width wp-block-button__width-${ width }` ]:
						width,
					[ `has-custom-font-size` ]: blockProps.style.fontSize,
				} ) }
			>
				<RichText
					ref={ mergedRef }
					aria-label={ __( 'Button text' ) }
					placeholder={ placeholder || __( 'Add text…' ) }
					value={ text }
					onChange={ ( value ) =>
						setAttributes( {
							text: removeAnchorTag( value ),
						} )
					}
					withoutInteractiveFormatting
					className={ clsx(
						className,
						'wp-block-button__link',
						colorProps.className,
						borderProps.className,
						{
							[ `has-text-align-${ textAlign }` ]: textAlign,
							'no-border-radius': style?.border?.radius === 0,
						},
						__experimentalGetElementClassName( 'button' )
					) }
					style={ {
						...borderProps.style,
						...colorProps.style,
						...spacingProps.style,
						...shadowProps.style,
					} }
					onReplace={ onReplace }
					onMerge={ mergeBlocks }
					identifier="text"
				/>
			</div>

			<BlockControls group="block">
				{ blockEditingMode === 'default' && (
					<AlignmentControl
						value={ textAlign }
						onChange={ ( nextAlign ) => {
							setAttributes( { textAlign: nextAlign } );
						} }
					/>
				) }
			</BlockControls>

			<InspectorControls>
				<PanelBody title={ __( 'Settings' ) }>
					<ToggleControl
						checked={ showDashboardMenu }
						label={ __(
							'Enable dropdown menu',
							'directorist-account-block'
						) }
						onChange={ () =>
							setAttributes( {
								showDashboardMenu: ! showDashboardMenu,
							} )
						}
					/>

					<WidthPanel
						selectedWidth={ width }
						setAttributes={ setAttributes }
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);
}

export default ButtonEdit;
