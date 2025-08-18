import { Dashboard } from '@wpmvc/dashboard';
import { MenuItemsType } from '@wpmvc/dashboard/build-types/components/menu/types';
import React from 'react';
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

const orders = [
    {
      id: 1,
      user: {
        name: 'John Doe',
        email: 'john.doe@example.com'
      },
      order_type: 'Claim',
      directory_type: 'Business',
      status: 'paid',
      total_amount: '$29.99',
      payment_method: 'PayPal',
      date: '2024-01-15',
      thumbnail_url: ''
    },
    {
      id: 2,
      user: {
        name: 'Jane Smith',
        email: 'jane.smith@example.com'
      },
      order_type: 'Plan',
      directory_type: 'Restaurant',
      status: 'pending',
      total_amount: '$99.99',
      payment_method: 'Stripe',
      date: '2024-01-14',
      thumbnail_url: ''
    },
    {
      id: 3,
      user: {
        name: 'Mike Johnson',
        email: 'mike.j@example.com'
      },
      order_type: 'Featured Listing',
      directory_type: 'Service',
      status: 'failed',
      total_amount: '$49.99',
      payment_method: 'Credit Card',
      date: '2024-01-13',
      thumbnail_url: ''
    },
    {
      id: 4,
      user: {
        name: 'Sarah Wilson',
        email: 'sarah.w@example.com'
      },
      order_type: 'Claim',
      directory_type: 'Professional',
      status: 'cancelled',
      total_amount: '$29.99',
      payment_method: 'PayPal',
      date: '2024-01-12',
      thumbnail_url: ''
    },
    {
      id: 5,
      user: {
        name: 'David Brown',
        email: 'david.b@example.com'
      },
      order_type: 'Plan',
      directory_type: 'Healthcare',
      status: 'refunded',
      total_amount: '$149.99',
      payment_method: 'Stripe',
      date: '2024-01-11',
      thumbnail_url: ''
    }
  ];

export default function App() {
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