/**
 * External dependencies
 */
import {
	take,
	clone,
	uniq,
	map,
	difference,
	each,
	identity,
	some,
} from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __, _n, sprintf } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { withInstanceId } from '@wordpress/compose';
import {
	BACKSPACE,
	ENTER,
	UP,
	DOWN,
	LEFT,
	RIGHT,
	SPACE,
	DELETE,
	ESCAPE,
} from '@wordpress/keycodes';
import isShallowEqual from '@wordpress/is-shallow-equal';
import { withSpokenMessages, BaseControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import Token from './token';
import TokenInput from './token-input';
import SuggestionsList from './suggestions-list';

const initialState = {
	incompleteTokenValue: '',
	inputOffsetFromEnd: 0,
	isActive: false,
	isExpanded: false,
	selectedSuggestionIndex: -1,
	selectedSuggestionScroll: false,
};

class TokenMultiSelectControl extends Component {
	constructor() {
		super( ...arguments );
		this.state = initialState;
		this.onKeyDown = this.onKeyDown.bind( this );
		this.onKeyPress = this.onKeyPress.bind( this );
		this.onFocus = this.onFocus.bind( this );
		this.onClick = this.onClick.bind( this );
		this.onBlur = this.onBlur.bind( this );
		this.deleteTokenBeforeInput = this.deleteTokenBeforeInput.bind( this );
		this.deleteTokenAfterInput = this.deleteTokenAfterInput.bind( this );
		this.addCurrentToken = this.addCurrentToken.bind( this );
		this.onContainerTouched = this.onContainerTouched.bind( this );
		this.renderToken = this.renderToken.bind( this );
		this.onTokenClickRemove = this.onTokenClickRemove.bind( this );
		this.onSuggestionHovered = this.onSuggestionHovered.bind( this );
		this.onSuggestionSelected = this.onSuggestionSelected.bind( this );
		this.onInputChange = this.onInputChange.bind( this );
		this.bindInput = this.bindInput.bind( this );
		this.bindTokensAndInput = this.bindTokensAndInput.bind( this );
		this.updateSuggestions = this.updateSuggestions.bind( this );
		this.addNewTokens = this.addNewTokens.bind( this );
		this.getValueFromLabel = this.getValueFromLabel.bind( this );
		this.getLabelFromValue = this.getLabelFromValue.bind( this );
	}

	componentDidUpdate( prevProps ) {
		// Make sure to focus the input when the isActive state is true.
		if ( this.state.isActive && ! this.input.hasFocus() ) {
			this.input.focus();
		}

		const { options, value } = this.props;
		const suggestionsDidUpdate = ! isShallowEqual(
			options,
			prevProps.options
		);
		if ( suggestionsDidUpdate || value !== prevProps.value ) {
			this.updateSuggestions( suggestionsDidUpdate );
		}
	}

	static getDerivedStateFromProps( props, state ) {
		if ( ! props.disabled || ! state.isActive ) {
			return null;
		}

		return {
			isActive: false,
			incompleteTokenValue: '',
		};
	}

	bindInput( ref ) {
		this.input = ref;
	}

	bindTokensAndInput( ref ) {
		this.tokensAndInput = ref;
	}

	onFocus( event ) {
		// If focus is on the input or on the container, set the isActive state to true.
		if ( this.input.hasFocus() || event.target === this.tokensAndInput ) {
			this.setState( { isActive: true /* , isExpanded: true */ } );
		} else {
			/*
			 * Otherwise, focus is on one of the token "remove" buttons and we
			 * set the isActive state to false to prevent the input to be
			 * re-focused, see componentDidUpdate().
			 */
			this.setState( { isActive: false } );
		}

		if ( 'function' === typeof this.props.onFocus ) {
			this.props.onFocus( event );
		}
	}
	onClick( event ) {
		// If focus is on the input or on the container, set the isActive state to true.
		// don't open if we clicked a suggestion
		if (
			! event.target.classList.contains(
				'components-form-token-field__suggestion'
			)
		) {
			this.setState( { isExpanded: true, isActive: true } );
		}
	}

	onBlur() {
		if ( this.inputHasValidToken() ) {
			this.setState( { isActive: false, isExpanded: false } );
		} else {
			this.setState( initialState );
		}
	}

	onKeyDown( event ) {
		let preventDefault = false;

		switch ( event.keyCode ) {
			case BACKSPACE:
				preventDefault = this.handleDeleteKey(
					this.deleteTokenBeforeInput
				);
				break;
			case ENTER:
				preventDefault = this.addCurrentToken();
				break;
			case LEFT:
				preventDefault = this.handleLeftArrowKey();
				break;
			case UP:
				preventDefault = this.handleUpArrowKey();
				break;
			case RIGHT:
				preventDefault = this.handleRightArrowKey();
				break;
			case DOWN:
				preventDefault = this.handleDownArrowKey();
				break;
			case DELETE:
				preventDefault = this.handleDeleteKey(
					this.deleteTokenAfterInput
				);
				break;
			case SPACE:
				if ( this.props.tokenizeOnSpace ) {
					preventDefault = this.addCurrentToken();
				}
				break;
			case ESCAPE:
				preventDefault = this.handleEscapeKey( event );
				event.stopPropagation();
				break;
			default:
				break;
		}

		if ( preventDefault ) {
			event.preventDefault();
		}
	}

	onKeyPress( event ) {
		if ( ! this.state.isExpanded ) {
			this.setState( { isExpanded: true } );
		}
	}

	onContainerTouched( event ) {
		// Prevent clicking/touching the tokensAndInput container from blurring
		// the input and adding the current token.
		if ( event.target === this.tokensAndInput && this.state.isActive ) {
			event.preventDefault();
		}
		//this.setState( { isExpanded: true } );
	}

	onTokenClickRemove( event ) {
		this.deleteToken( event.value );
		this.input.focus();
	}

	onSuggestionHovered( suggestion ) {
		const index = this.getMatchingSuggestions().indexOf( suggestion );

		if ( index >= 0 ) {
			this.setState( {
				selectedSuggestionIndex: index,
				selectedSuggestionScroll: false,
			} );
		}
	}

	onSuggestionSelected( suggestion ) {
		this.addNewToken( suggestion );
	}

	onInputChange( event ) {
		const tokenValue = event.value;
		this.setState(
			{ incompleteTokenValue: tokenValue },
			this.updateSuggestions
		);

		this.props.onInputChange( tokenValue );
	}

	handleDeleteKey( deleteToken ) {
		let preventDefault = false;
		if ( this.input.hasFocus() && this.isInputEmpty() ) {
			deleteToken();
			preventDefault = true;
		}

		return preventDefault;
	}

	handleLeftArrowKey() {
		let preventDefault = false;
		if ( this.isInputEmpty() ) {
			this.moveInputBeforePreviousToken();
			preventDefault = true;
		}

		return preventDefault;
	}

	handleRightArrowKey() {
		let preventDefault = false;
		if ( this.isInputEmpty() ) {
			this.moveInputAfterNextToken();
			preventDefault = true;
		}

		return preventDefault;
	}
	getOptionsLabels( options ) {
		return options.map( ( option ) => {
			return option.label;
		} );
	}
	getValueFromLabel( optionLabel ) {
		const foundOption = this.props.options.find(
			( option ) =>
				option.label.toLocaleLowerCase() ===
				optionLabel.toLocaleLowerCase()
		);
		if ( foundOption ) {
			return foundOption.value;
		}
		return null;
	}
	getLabelFromValue( optionValue ) {
		const foundOption = this.props.options.find(
			( option ) => option.value === optionValue
		);
		if ( foundOption ) {
			return foundOption.label;
		}
		return null;
	}
	getOptionsValues( options ) {
		return options.map( ( option ) => {
			return option.value;
		} );
	}
	handleUpArrowKey() {
		this.setState( ( state, props ) => ( {
			selectedSuggestionIndex:
				( state.selectedSuggestionIndex === 0
					? this.getMatchingSuggestions(
							state.incompleteTokenValue,
							this.getOptionsLabels( props.options ),
							props.value,
							props.maxSuggestions,
							props.saveTransform
					  ).length
					: state.selectedSuggestionIndex ) - 1,
			selectedSuggestionScroll: true,
		} ) );

		return true; // preventDefault
	}

	handleDownArrowKey() {
		this.setState( ( state, props ) => ( {
			selectedSuggestionIndex:
				( state.selectedSuggestionIndex + 1 ) %
				this.getMatchingSuggestions(
					state.incompleteTokenValue,
					this.getOptionsLabels( props.options ),
					props.value,
					props.maxSuggestions,
					props.saveTransform
				).length,
			selectedSuggestionScroll: true,
			isExpanded: true,
		} ) );

		return true; // preventDefault
	}

	handleEscapeKey( event ) {
		this.setState( {
			incompleteTokenValue: event.target.value,
			isExpanded: false,
			selectedSuggestionIndex: -1,
			selectedSuggestionScroll: false,
		} );
		return true; // preventDefault
	}

	moveInputToIndex( index ) {
		this.setState( ( state, props ) => ( {
			inputOffsetFromEnd: props.value.length - Math.max( index, -1 ) - 1,
		} ) );
	}

	moveInputBeforePreviousToken() {
		this.setState( ( state, props ) => ( {
			inputOffsetFromEnd: Math.min(
				state.inputOffsetFromEnd + 1,
				props.value.length
			),
		} ) );
	}

	moveInputAfterNextToken() {
		this.setState( ( state ) => ( {
			inputOffsetFromEnd: Math.max( state.inputOffsetFromEnd - 1, 0 ),
		} ) );
	}

	deleteTokenBeforeInput() {
		const index = this.getIndexOfInput() - 1;

		if ( index > -1 ) {
			this.deleteToken( this.props.value[ index ] );
		}
	}

	deleteTokenAfterInput() {
		const index = this.getIndexOfInput();

		if ( index < this.props.value.length ) {
			this.deleteToken( this.props.value[ index ] );
			// update input offset since it's the offset from the last token
			this.moveInputToIndex( index );
		}
	}

	addCurrentToken() {
		let preventDefault = false;
		const selectedSuggestion = this.getSelectedSuggestion();
		if ( selectedSuggestion ) {
			this.addNewToken( selectedSuggestion );
			preventDefault = true;
		} else if ( this.inputHasValidToken() ) {
			this.addNewToken( this.state.incompleteTokenValue );
			preventDefault = true;
		}
		return preventDefault;
	}

	addNewTokens( tokens ) {
		const tokensToAdd = uniq(
			tokens
				.map( this.props.saveTransform )
				.filter( Boolean )
				.filter( ( token ) => ! this.valueContainsToken( token ) )
		);

		if ( tokensToAdd.length > 0 ) {
			const tokenValuesToAdd = tokensToAdd.map( ( tokenLabel ) => {
				return this.getValueFromLabel( tokenLabel );
			} );

			let newValue = clone( this.props.value );
			newValue.splice.apply(
				newValue,
				[ this.getIndexOfInput(), 0 ].concat( tokenValuesToAdd )
			);
			// now remove duplicates if required
			newValue = [ ...new Set( newValue ) ];
			this.props.onChange( newValue );
		}
	}

	addNewToken( token ) {
		this.addNewTokens( [ token ] );
		this.props.speak( this.props.messages.added, 'assertive' );

		this.setState( {
			incompleteTokenValue: '',
			selectedSuggestionIndex: -1,
			selectedSuggestionScroll: false,
			isExpanded: false,
		} );

		if ( this.state.isActive ) {
			this.input.focus();
		}
	}

	deleteToken( token ) {
		const newTokens = this.props.value.filter( ( item ) => {
			return this.getTokenValue( item ) !== this.getTokenValue( token );
		} );
		this.props.onChange( newTokens );
		this.props.speak( this.props.messages.removed, 'assertive' );
	}

	getTokenValue( token ) {
		if ( token && token.value ) {
			return token.value;
		}

		return token;
	}

	getMatchingSuggestions(
		searchValue = this.state.incompleteTokenValue,
		suggestions = this.getOptionsLabels( this.props.options ),
		value = this.props.value,
		maxSuggestions = this.props.maxSuggestions,
		saveTransform = this.props.saveTransform
	) {
		let match = saveTransform( searchValue );
		const startsWithMatch = [];
		const containsMatch = [];

		const activeLabels = value.map( ( optionValue ) => {
			return this.getLabelFromValue( optionValue );
		} );

		if ( match.length > 0 ) {
			match = match.toLocaleLowerCase();
			each( suggestions, ( suggestion ) => {
				const index = suggestion.toLocaleLowerCase().indexOf( match );
				if ( value.indexOf( suggestion ) === -1 ) {
					if ( index === 0 ) {
						startsWithMatch.push( suggestion );
					} else if ( index > 0 ) {
						containsMatch.push( suggestion );
					}
				}
			} );
			suggestions = startsWithMatch.concat( containsMatch );
		}
		// remove selected labels from suggestions
		suggestions = difference( suggestions, activeLabels );

		return take( suggestions, maxSuggestions );
	}

	getSelectedSuggestion() {
		if ( this.state.selectedSuggestionIndex !== -1 ) {
			return this.getMatchingSuggestions()[
				this.state.selectedSuggestionIndex
			];
		}
	}

	valueContainsToken( token ) {
		return some( this.props.value, ( item ) => {
			return this.getTokenValue( token ) === this.getTokenValue( item );
		} );
	}

	getIndexOfInput() {
		return this.props.value.length - this.state.inputOffsetFromEnd;
	}

	isInputEmpty() {
		return this.state.incompleteTokenValue.length === 0;
	}

	inputHasValidToken() {
		const incompleteTokenValue = this.state.incompleteTokenValue;
		let foundMatch = false;
		if ( incompleteTokenValue && incompleteTokenValue.length > 0 ) {
			this.props.options.forEach( ( option ) => {
				if (
					option.label.trim().toLocaleLowerCase() ===
					incompleteTokenValue.trim().toLocaleLowerCase()
				) {
					foundMatch = true;
					// return true; //not working?
				}
			} );
		}
		return foundMatch;
	}

	updateSuggestions( resetSelectedSuggestion = true ) {
		const { incompleteTokenValue } = this.state;

		const inputHasMinimumChars = true; //incompleteTokenValue.trim().length > 1;
		const matchingSuggestions = this.getMatchingSuggestions(
			incompleteTokenValue
		);
		const hasMatchingSuggestions = matchingSuggestions.length > 0;
		const newState = {
			// isExpanded: inputHasMinimumChars && hasMatchingSuggestions,
		};
		if ( resetSelectedSuggestion ) {
			newState.selectedSuggestionIndex = -1;
			newState.selectedSuggestionScroll = false;
		}

		this.setState( newState );

		if ( inputHasMinimumChars ) {
			const { debouncedSpeak } = this.props;

			const message = hasMatchingSuggestions
				? sprintf(
						/* translators: %d: number of results. */
						_n(
							'%d result found, use up and down arrow keys to navigate.',
							'%d results found, use up and down arrow keys to navigate.',
							matchingSuggestions.length
						),
						matchingSuggestions.length
				  )
				: __( 'No results.' );

			debouncedSpeak( message, 'assertive' );
		}
	}

	renderTokensAndInput() {
		const components = map( this.props.value, this.renderToken );
		components.splice( this.getIndexOfInput(), 0, this.renderInput() );

		return components;
	}

	renderToken( token, index, tokens ) {
		const value = this.getTokenValue( token );
		const label = this.getLabelFromValue( value ); //todo - optimize
		const status = token.status ? token.status : undefined;
		const termPosition = index + 1;
		const termsCount = tokens.length;

		return (
			<Token
				key={ 'token-' + value }
				value={ value }
				label={ label }
				status={ status }
				title={ token.title }
				displayTransform={ this.props.displayTransform }
				onClickRemove={ this.onTokenClickRemove }
				isBorderless={ token.isBorderless || this.props.isBorderless }
				onMouseEnter={ token.onMouseEnter }
				onMouseLeave={ token.onMouseLeave }
				disabled={ 'error' !== status && this.props.disabled }
				messages={ this.props.messages }
				termsCount={ termsCount }
				termPosition={ termPosition }
			/>
		);
	}

	renderInput() {
		const {
			autoCapitalize,
			autoComplete,
			maxLength,
			value,
			instanceId,
		} = this.props;

		let props = {
			instanceId,
			autoCapitalize,
			autoComplete,
			ref: this.bindInput,
			key: 'input',
			disabled: this.props.disabled,
			value: this.state.incompleteTokenValue,
			onBlur: this.onBlur,
			isExpanded: this.state.isExpanded,
			selectedSuggestionIndex: this.state.selectedSuggestionIndex,
		};

		if ( ! ( maxLength && value.length >= maxLength ) ) {
			props = { ...props, onChange: this.onInputChange };
		}

		return <TokenInput { ...props } />;
	}

	render() {
		const {
			disabled,
			label = __( 'Add item' ),
			instanceId,
			className,
		} = this.props;
		const { isExpanded } = this.state;
		const classes = classnames(
			className,
			'components-form-token-field__input-container',
			{
				'is-active': this.state.isActive,
				'is-disabled': disabled,
			}
		);

		let tokenFieldProps = {
			className: 'components-form-token-field directorist-gb-multiselect',
			tabIndex: '-1',
		};
		const matchingSuggestions = this.getMatchingSuggestions();

		if ( ! disabled ) {
			tokenFieldProps = Object.assign( {}, tokenFieldProps, {
				onKeyDown: this.onKeyDown,
				onKeyPress: this.onKeyPress,
				onFocus: this.onFocus,
				onClick: this.onClick,
			} );
		}

		// Disable reason: There is no appropriate role which describes the
		// input container intended accessible usability.
		// TODO: Refactor click detection to use blur to stop propagation.
		/* eslint-disable jsx-a11y/no-static-element-interactions */
		return (
			<div { ...tokenFieldProps }>
				<label
					htmlFor={ `components-form-token-input-${ instanceId }` }
					className="components-form-token-field__label"
					style={{fontSize: '13px'}}
				>
					{ label }
				</label>
				<div
					ref={ this.bindTokensAndInput }
					className={ classes }
					tabIndex="-1"
					onMouseDown={ this.onContainerTouched }
					onTouchStart={ this.onContainerTouched }
				>
					{ this.renderTokensAndInput() }
					{ isExpanded && (
						<SuggestionsList
							instanceId={ instanceId }
							match={ this.props.saveTransform(
								this.state.incompleteTokenValue
							) }
							displayTransform={ this.props.displayTransform }
							suggestions={ matchingSuggestions }
							selectedIndex={ this.state.selectedSuggestionIndex }
							scrollIntoView={
								this.state.selectedSuggestionScroll
							}
							onHover={ this.onSuggestionHovered }
							onSelect={ this.onSuggestionSelected }
						/>
					) }
				</div>
			</div>
		);
		/* eslint-enable jsx-a11y/no-static-element-interactions */
	}
}

TokenMultiSelectControl.defaultProps = {
	options: Object.freeze( [] ),
	maxSuggestions: 100,
	value: Object.freeze( [] ),
	displayTransform: identity,
	saveTransform: ( token ) => ( token ? token.trim() : '' ),
	onChange: () => {},
	onInputChange: () => {},
	isBorderless: false,
	disabled: false,
	tokenizeOnSpace: false,
	messages: {
		added: __( 'Item added.' ),
		removed: __( 'Item removed.' ),
		remove: __( 'Remove item' ),
	},
};

export default withSpokenMessages( withInstanceId( TokenMultiSelectControl ) );
