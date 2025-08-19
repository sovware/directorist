import { addAction } from '@wordpress/hooks';
import { Dashboard } from '@wpmvc/dashboard';
import { MenuItemsType } from '@wpmvc/dashboard/build-types/components/menu/types';
import React from 'react';
import validateField from '../controls/custom-field/validation';
import Edit from './edit';
import OrderTable from './order-table';

const menuItems: MenuItemsType = {
	home: {
		label: 'Orders',
		path: '/',
	},
	subscription: {
		label: 'Subscription',
		path: '/subscription',
	},
};

export default function App() {

  const fieldValidation = ({value, field, fieldKey, attributes, errors, setErrors}) => {
    if (!field?.validation) return;
      
      // Use the validation module
      const errorResult = validateField(value, field, attributes);
      
      const updatedValidationErrors = {...errors};
      if (errorResult?.errors?.length > 0) {
        updatedValidationErrors[fieldKey] = errorResult?.errors;
      } else {
        updatedValidationErrors[fieldKey] = [];
      }
      
      setErrors(updatedValidationErrors);
  }
  
  addAction( 'wpmvc-field-on-blur', 'directorist-form-validation', fieldValidation);
  addAction( 'wpmvc-field-on-change', 'directorist-form-validation', fieldValidation);

	return (
		<Dashboard
			colors={ {
				primary: 'var(--directorist-color-primary)',
			} }
			header={ {
				logo: <>Directorist</>,
				menuItems,
			} }
			routes={ [
				{
					path: '/',
					element: <OrderTable/>,
					index: true,
          // preventTransition: true,
				},
				{
					path: '/edit/:id',
					element: <Edit />,
          // preventTransition: true,
				},
			] }
		></Dashboard>
	);
}