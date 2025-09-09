import { addAction, applyFilters } from '@wordpress/hooks';
import { Dashboard } from '@wpmvc/dashboard';
import { RouteType } from '@wpmvc/dashboard/build-types/components/dashboard/types';
import { MenuItemsType } from '@wpmvc/dashboard/build-types/components/menu/types';
import styled from 'styled-components';
import validateField from '../controls/custom-field/validation';
import DocIcon from '../icons/DocIcon';
import QuestionCircleIcon from '../icons/QuestionCircleIcon';
// import OrderEdit from './orders/edit';
import Orders from './orders';
import Edit from './pricing';

const actionItems: MenuItemsType = {
	documentation: {
		label: 'Knowledge Base',
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
		icon: <i className="la la-comment"></i>,
	},
};

const ThemeWrapper = styled.div`
	--color-primary-500: #3e62f5;
	--color-gray-900: #141921;
	--color-gray-600: #4d5761;
	--color-gray-500: #747c89;
	--color-gray-300: #d2d6db;
	--color-gray-200: #e5e7eb;

	--color-light: #e7ecee;
`;

export default function App() {
	const fieldValidation = ({
		value,
		field,
		fieldKey,
		attributes,
		errors,
		setErrors,
	}) => {
		if (!field?.validation) return;

		// Use the validation module
		const errorResult = validateField(value, field, attributes);

		const updatedValidationErrors = { ...errors };
		if (errorResult?.errors?.length > 0) {
			updatedValidationErrors[fieldKey] = errorResult?.errors;
		} else {
			updatedValidationErrors[fieldKey] = [];
		}

		setErrors(updatedValidationErrors);
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

	const routes = [
		{
			path: '/',
			element: <Orders />,
			index: true,
		},
		{
			path: '/edit/:id',
			element: <Edit />,
			// element: <OrderEdit />,
		},
	];

	return (
		<ThemeWrapper>
			<Dashboard
				pageTopLevelID="#menu-posts-at_biz_dir"
				rootPaths={[]}
				colors={{
					primary: '#3e62f5',
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
