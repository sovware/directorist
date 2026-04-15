import { addAction, applyFilters } from '@wordpress/hooks';
import { useEffect } from '@wordpress/element';
import { Dashboard } from '@shamim-ahmed/dashboard';
import { RouteType } from '@shamim-ahmed/dashboard/build-types/components/dashboard/types';
import { MenuItemsType } from '@shamim-ahmed/dashboard/build-types/components/menu/types';
import styled from 'styled-components';
import { FieldValidator } from '../controls/custom-field/validation/FieldValidator';
import { EmailValidator } from '../controls/custom-field/validation/validators/EmailValidator';
import { MaxLengthValidator } from '../controls/custom-field/validation/validators/MaxLengthValidator';
import { MinLengthValidator } from '../controls/custom-field/validation/validators/MinLengthValidator';
import { NumberValidator } from '../controls/custom-field/validation/validators/NumberValidator';
import { RequiredValidator } from '../controls/custom-field/validation/validators/RequiredValidator';
import CommentIcon from '../icons/CommentIcon';
import DocIcon from '../icons/DocIcon';
import QuestionCircleIcon from '../icons/QuestionCircleIcon';
import Orders from './orders';
import OrderEdit from './orders/edit';

// Stable reference — avoids recreating the array on every render which would
// cause useActiveAdminMenu's useLayoutEffect to re-run and re-attach its
// preventDefault handler, overwriting our click override below.
const ROOT_PATHS: string[] = [];

const actionItems: MenuItemsType = {
	documentation: {
		label: 'Documentation',
		path: 'https://directorist.com/documentation/',
		icon: <DocIcon />,
	},
	support: {
		label: 'Help & Support',
		path: 'https://directorist.com/dashboard/#support',
		icon: <QuestionCircleIcon />,
	},
	feedback: {
		path: 'https://directorist.com/dashboard/#feedback',
		icon: <CommentIcon />,
	},
};

const ThemeWrapper = styled.div`
	--color-light: #e7ecee;
`;

export default function App() {
	// const fieldValidation = ({
	// 	value,
	// 	field,
	// 	fieldKey,
	// 	attributes,
	// 	errors,
	// 	setErrors,
	// }: {
	// 	value: any;
	// 	field: any;
	// 	fieldKey: string;
	// 	attributes: Record<string, any>;
	// 	errors: Record<string, string[]>;
	// 	setErrors: (next: Record<string, string[]>) => void;
	// }) => {
	// 	if (!field?.validation) return;

	// 	// Use the validation module
	// 	const errorResult = validateField(value, field, attributes);

	// 	if (errorResult?.errors?.length > 0) {
	// 		setErrors({
	// 			...errors,
	// 			[fieldKey]: errorResult?.errors
	// 		});
	// 	} else {
	// 		setErrors({
	// 			[fieldKey]: []
	// 		});
	// 	}
	// }

	const fieldValidation = ({
		value,
		field,
		fieldKey,
		attributes,
		errors,
		setErrors,
	}: {
		value: any;
		field: any;
		fieldKey: string;
		attributes: Record<string, any>;
		errors: Record<string, string[]>;
		setErrors: (next: Record<string, string[]>) => void;
	}) => {
		if (!field?.validation) return;

		const validatorContext = new FieldValidator();
		let errorResult = null;
		if (
			field.validation.condition &&
			!field.validation.condition(attributes)
		) {
			errorResult = { isValid: true, errors: [] };
		} else {
			Object.entries(field.validation).forEach(([rule, ruleValue]) => {
				switch (rule) {
					case 'required':
						validatorContext.addValidator(new RequiredValidator());
						break;
					case 'email':
						validatorContext.addValidator(new EmailValidator());
						break;
					case 'min_length':
						validatorContext.addValidator(
							new MinLengthValidator(ruleValue)
						);
						break;
					case 'max_length':
						validatorContext.addValidator(
							new MaxLengthValidator(ruleValue)
						);
						break;
					case 'number':
						validatorContext.addValidator(new NumberValidator());
						break;
				}
			});
			errorResult = validatorContext.validate(value, attributes);
		}

		if (errorResult?.errors?.length > 0) {
			setErrors({
				...errors,
				[fieldKey]: errorResult?.errors,
			});
		} else {
			setErrors({
				[fieldKey]: [],
			});
		}
	};
	addAction(
		'wpmvc-field-on-blur',
		'directorist-form-validation',
		fieldValidation
	);
	addAction(
		'wpmvc-field-on-change',
		'directorist-form-validation',
		fieldValidation
	);

	// Bug fix: useActiveAdminMenu (in @wpmvc/admin-sidebar) intercepts the
	// .wp-first-item ("All Listings") click with e.preventDefault() and tries
	// React Router navigation. Since rootPaths=[] there is no valid route to
	// navigate to, so the link silently does nothing.
	// We re-bind the click handler after the Dashboard's useLayoutEffect runs
	// (setTimeout 0 defers to the next macrotask) so it always does a full
	// WordPress page navigation instead.
	useEffect(() => {
		const timer = setTimeout(() => {
			const $ = (window as any).jQuery;
			if (!$) return;
			const $firstItem = $('#menu-posts-at_biz_dir').find('a.wp-first-item');
			const href = $firstItem.attr('href');
			if (href) {
				$firstItem.off('click').on('click', (e: Event) => {
					e.preventDefault();
					window.location.href = href;
				});
			}
		}, 0);
		return () => clearTimeout(timer);
	}, []);

	const routes = [
		{
			path: '/',
			element: <Orders />,
			index: true,
		},
		{
			path: '/edit/:id',
			// element: <Edit />,
			element: <OrderEdit />,
		},
	];

	return (
		<ThemeWrapper>
			<Dashboard
				pageTopLevelID="#menu-posts-at_biz_dir"
				rootPaths={ROOT_PATHS}
				colors={{
					primary: '#3e62f5',
					error: '#D94A4A',
					gray: '#141921',
				}}
				header={{
					logo: (
						<img
							src="https://directorist.com/wp-content/uploads/2020/08/directorist_logo.png"
							alt="Directorist"
							width={116}
						/>
					),
					actionItems,
				}}
				routes={
					applyFilters(
						'directorist_order_routes',
						routes
					) as RouteType[]
				}
			></Dashboard>
		</ThemeWrapper>
	);
}
