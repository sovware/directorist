import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

import { getPlaceholder } from './../functions';
import metadata from './block.json';
import getLogo from './../logo';

const Placeholder = () => getPlaceholder( 'signin' );

registerBlockType( metadata.name, {
	icon: getLogo(),

	transforms: {
		from: [
			{
				type: 'shortcode',
				tag: 'directorist_user_login',
				attributes: {},
			},
			{
				type: 'shortcode',
				tag: 'directorist_custom_registration',
				attributes: {},
			},
		],
	},

	edit( { attributes, setAttributes } ) {
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Sign-In Settings', 'directorist' ) }>
						<TextControl
							label={ __( 'Username Label', 'directorist' ) }
							value={ attributes.signin_username_label }
							onChange={ ( value ) => setAttributes({ signin_username_label: value }) }
						/>
						<TextControl
							label={ __( 'Password Label', 'directorist' ) }
							value={ attributes.password_label }
							onChange={ ( value ) => setAttributes({ password_label: value }) }
						/>
						<TextControl
							label={ __( 'Button Label', 'directorist' ) }
							value={ attributes.signin_button_label }
							onChange={ ( value ) => setAttributes({ signin_button_label: value }) }
						/>
						<TextControl
							label={ __( 'Sign Up Linking Text', 'directorist' ) }
							value={ attributes.signup_linking_text }
							onChange={ ( value ) => setAttributes({ signup_linking_text: value }) }
						/>
						<TextControl
							label={ __( 'Recovery Password Label', 'directorist' ) }
							value={ attributes.recovery_password_label }
							onChange={ ( value ) => setAttributes({ recovery_password_label: value }) }
						/>
					</PanelBody>

					<PanelBody isOpen={false} title={ __( 'Sign-Up Settings', 'directorist' ) }>
						<ToggleControl
							label={ __( 'Enable Sign-Up', 'directorist' ) }
							checked={ attributes.registration }
							onChange={ ( value ) => setAttributes({ registration: value }) }
						/>
						{ attributes.registration ? <Fragment>
							<TextControl
								label={ __( 'Username Label', 'directorist' ) }
								value={ attributes.username_label }
								onChange={ ( value ) => setAttributes({ username_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Show Password Field', 'directorist' ) }
								checked={ attributes.password }
								onChange={ ( value ) => setAttributes({ password: value }) }
							/>
							<TextControl
								label={ __( 'Password Label', 'directorist' ) }
								value={ attributes.password_label }
								onChange={ ( value ) => setAttributes({ password_label: value }) }
							/>
							<TextControl
								label={ __( 'Email Label', 'directorist' ) }
								value={ attributes.email_label }
								onChange={ ( value ) => setAttributes({ email_label: value }) }
							/>
							<TextControl
								label={ __( 'Button Label', 'directorist' ) }
								value={ attributes.signup_button_label }
								onChange={ ( value ) => setAttributes({ signup_button_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Auto Sign In After Sign Up', 'directorist' ) }
								checked={ attributes.signin_after_signup }
								onChange={ ( value ) => setAttributes({ signin_after_signup: value }) }
							/>
							<TextControl
								label={ __( 'Sign Up Redirect URL', 'directorist' ) }
								value={ attributes.signup_redirect_url }
								onChange={ ( value ) => setAttributes({ signup_redirect_url: value }) }
							/>
							<TextControl
								label={ __( 'Sign Up Label', 'directorist' ) }
								value={ attributes.signup_label }
								onChange={ ( value ) => setAttributes({ signup_label: value }) }
							/>
							<TextControl
								label={ __( 'Sign In Message', 'directorist' ) }
								value={ attributes.signin_message }
								onChange={ ( value ) => setAttributes({ signin_message: value }) }
							/>
							<TextControl
								label={ __( 'Sign In Linking Text', 'directorist' ) }
								value={ attributes.signin_linking_text }
								onChange={ ( value ) => setAttributes({ signin_linking_text: value }) }
							/>
						</Fragment> : null }
					</PanelBody>

					{ attributes.registration ? <Fragment>
						<PanelBody title={ __( 'User Role Settings', 'directorist' ) }>
							<ToggleControl
								label={ __( 'User Role', 'directorist' ) }
								checked={ attributes.user_role }
								onChange={ ( value ) => setAttributes({ user_role: value }) }
							/>
							<TextControl
								label={ __( 'Author Role Label', 'directorist' ) }
								value={ attributes.author_role_label }
								onChange={ ( value ) => setAttributes({ author_role_label: value }) }
							/>
							<TextControl
								label={ __( 'User Role Label', 'directorist' ) }
								value={ attributes.user_role_label }
								onChange={ ( value ) => setAttributes({ user_role_label: value }) }
							/>
						</PanelBody>

						<PanelBody title={ __( 'Website Settings', 'directorist' ) }>
							<ToggleControl
								label={ __( 'Show Website Field', 'directorist' ) }
								checked={ attributes.website }
								onChange={ ( value ) => setAttributes({ website: value }) }
							/>
							<TextControl
								label={ __( 'Website Label', 'directorist' ) }
								value={ attributes.website_label }
								onChange={ ( value ) => setAttributes({ website_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Require Website', 'directorist' ) }
								checked={ attributes.website_required }
								onChange={ ( value ) => setAttributes({ website_required: value }) }
							/>
						</PanelBody>

						<PanelBody title={ __( 'Name Fields', 'directorist' ) }>
							<ToggleControl
								label={ __( 'Show First Name Field', 'directorist' ) }
								checked={ attributes.firstname }
								onChange={ ( value ) => setAttributes({ firstname: value }) }
							/>
							<TextControl
								label={ __( 'First Name Label', 'directorist' ) }
								value={ attributes.firstname_label }
								onChange={ ( value ) => setAttributes({ firstname_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Require First Name', 'directorist' ) }
								checked={ attributes.firstname_required }
								onChange={ ( value ) => setAttributes({ firstname_required: value }) }
							/>

							<ToggleControl
								label={ __( 'Show Last Name Field', 'directorist' ) }
								checked={ attributes.lastname }
								onChange={ ( value ) => setAttributes({ lastname: value }) }
							/>
							<TextControl
								label={ __( 'Last Name Label', 'directorist' ) }
								value={ attributes.lastname_label }
								onChange={ ( value ) => setAttributes({ lastname_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Require Last Name', 'directorist' ) }
								checked={ attributes.lastname_required }
								onChange={ ( value ) => setAttributes({ lastname_required: value }) }
							/>
						</PanelBody>

						<PanelBody title={ __( 'Bio Settings', 'directorist' ) }>
							<ToggleControl
								label={ __( 'Show Bio Field', 'directorist' ) }
								checked={ attributes.bio }
								onChange={ ( value ) => setAttributes({ bio: value }) }
							/>
							<TextControl
								label={ __( 'Bio Label', 'directorist' ) }
								value={ attributes.bio_label }
								onChange={ ( value ) => setAttributes({ bio_label: value }) }
							/>
							<ToggleControl
								label={ __( 'Require Bio', 'directorist' ) }
								checked={ attributes.bio_required }
								onChange={ ( value ) => setAttributes({ bio_required: value }) }
							/>
						</PanelBody>

						<PanelBody title={ __( 'Privacy & Terms', 'directorist' ) }>
							<ToggleControl
								label={ __( 'Enable Privacy Agreement', 'directorist' ) }
								checked={ attributes.privacy }
								onChange={ ( value ) => setAttributes({ privacy: value }) }
							/>
							<TextControl
								label={ __( 'Privacy Label', 'directorist' ) }
								value={ attributes.privacy_label }
								onChange={ ( value ) => setAttributes({ privacy_label: value }) }
							/>
							<TextControl
								label={ __( 'Privacy Linking Text', 'directorist' ) }
								value={ attributes.privacy_linking_text }
								onChange={ ( value ) => setAttributes({ privacy_linking_text: value }) }
							/>

							<ToggleControl
								label={ __( 'Enable Terms Agreement', 'directorist' ) }
								checked={ attributes.terms }
								onChange={ ( value ) => setAttributes({ terms: value }) }
							/>
							<TextControl
								label={ __( 'Terms Label', 'directorist' ) }
								value={ attributes.terms_label }
								onChange={ ( value ) => setAttributes({ terms_label: value }) }
							/>
							<TextControl
								label={ __( 'Terms Linking Text', 'directorist' ) }
								value={ attributes.terms_linking_text }
								onChange={ ( value ) => setAttributes({ terms_linking_text: value }) }
							/>
						</PanelBody>
					</Fragment> : null }

					<PanelBody title={ __( 'Recovery Password Settings', 'directorist' ) }>
						<TextControl
							label={ __( 'Recovery Password Linking Text', 'directorist' ) }
							value={ attributes.recovery_password_linking_text }
							onChange={ ( value ) => setAttributes({ recovery_password_linking_text: value }) }
						/>
					</PanelBody>
				</InspectorControls>

				<div
					{ ...useBlockProps( {
						className: 'directorist-content-active directorist-w-100',
					} ) }
				>
					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
						LoadingResponsePlaceholder={ Placeholder }
					/>
				</div>
			</Fragment>
		);
	},
} );
