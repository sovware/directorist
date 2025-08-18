/**
 * WordPress dependencies
 */
import { Icon } from '@wordpress/components';
import { useCopyToClipboard } from '@wordpress/compose';
import { useState } from '@wordpress/element';
import { check, copy } from '@wordpress/icons';
import React from "react";

/**
 * Internal dependencies
 */
import { ActionIcon, ClipboardCopyWrapper, ClipboardDescription, ClipboardLabel } from './styles';
import { ClipboardProps } from './types';

/**
 * A reusable clipboard copy component that displays a visual feedback when text is copied.
 */

export default function Clipboard( {
	field
}: ClipboardProps ) {
	const {
		text,
		label,
		description,
	className = '',
	timeout = 2000,
	onCopied,
	} = field;
	const [ isCopied, setIsCopied ] = useState( false );
	const copyRef = useCopyToClipboard( text, () => {
		setIsCopied( true );
		onCopied?.();
		setTimeout( () => setIsCopied( false ), timeout );
	} );

	return (
		<>
			<ClipboardLabel>{ label }</ClipboardLabel>
			<ClipboardDescription>{ description }</ClipboardDescription>
		
			<ClipboardCopyWrapper className={ `${ className }` }>
				<span>{ text }</span>
				<ActionIcon ref={ copyRef }>
					{ isCopied ? <Icon icon={ check } /> : <Icon icon={ copy } /> }
				</ActionIcon>
			</ClipboardCopyWrapper>
		</>
	);
}
