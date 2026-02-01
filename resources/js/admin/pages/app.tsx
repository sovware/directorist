import { addAction, applyFilters } from '@wordpress/hooks';
import { Dashboard } from '@wpmvc/dashboard';
import { RouteType } from '@wpmvc/dashboard/build-types/components/dashboard/types';
import { MenuItemsType } from '@wpmvc/dashboard/build-types/components/menu/types';
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
		let errorResult = null
		if(field.validation.condition && !field.validation.condition(attributes)){
			errorResult = { isValid: true, errors: [] };
		}else{
			Object.entries(field.validation).forEach(([rule, ruleValue]) => {
				switch (rule) {
				  case "required":
					validatorContext.addValidator(new RequiredValidator());
					break;
				  case "email":
					validatorContext.addValidator(new EmailValidator());
					break;
				  case "min_length":
					validatorContext.addValidator(new MinLengthValidator(ruleValue));
					break;
				  case "max_length":
					validatorContext.addValidator(new MaxLengthValidator(ruleValue));
					break;
				  case "number":
					validatorContext.addValidator(new NumberValidator());
					break;
				}
			});
			errorResult = validatorContext.validate(value, attributes);
		}
		
		if (errorResult?.errors?.length > 0) {
			setErrors({
				...errors,
				[fieldKey]: errorResult?.errors
			});
		} else {
			setErrors({
				[fieldKey]: []
			});
		}
	}
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
			// element: <Edit />,
			element: <OrderEdit />,
		},
	];

	return (
		<ThemeWrapper>
			<Dashboard
				pageTopLevelID="#menu-posts-at_biz_dir"
				rootPaths={[]}
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
