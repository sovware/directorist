/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { Fill, Slot } from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { Button, Select } from '@shamim-ahmed/components';
import {
	registerValuesStore,
	useValuesStore,
	useValuesStoreData,
} from '@shamim-ahmed/data';
import styled from 'styled-components';

/**
 * Internal dependencies
 */
import { useGetId } from '@/admin/hooks/useGetId';
import AngleLeftIcon from '@/admin/icons/AngleLeftIcon';
import AngleRightIcon from '@/admin/icons/AngleRightIcon';
import { doAction } from '@wordpress/hooks';
import CustomerInfo from './customer-info';
import OrderDetails from './order-details';
import PaymentLog from './payment-log';
import Refund from './refund';

const SingleOrderContainer = styled.div`
	padding: 30px 48px;
	display: grid;
	grid-template-columns: 2fr 1fr;
	grid-gap: 30px;
	.components-card {
		border-radius: 8px;
		border: 1px solid var(--color-light);
		box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
	}
	.components-card__header {
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
	}
	.components-card__footer {
		border-top: 1px solid rgba(0, 0, 0, 0.1);
	}
`;
const ContainerLeft = styled.div``;

const ContainerRight = styled.div``;
const SingleOrderHeader = styled.div`
	width: 100%;
	display: flex;
	align-items: center;
	justify-content: space-between;
	.directorist-single-plan-logo {
		line-height: 0;
	}
`;

const HeaderBreadcrumb = styled.div`
	display: flex;
	align-items: center;
	gap: 8px;
	.directorist-single-plan-logo {
		display: flex;
		align-items: center;
		justify-content: center;
		line-height: 0;
		width: 32px;
		height: 32px;
		border-radius: 50%;
		margin-right: 10px;
		border: 1px solid rgba(75, 85, 99, 0.1);
		box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
	}
	ul {
		display: flex;
		align-items: center;
		gap: 8px;
		list-style: none;
		padding: 0;
		margin: 0;
		li {
			display: flex;
			align-items: center;
			margin-bottom: 0;
			svg {
				position: relative;
				top: 2px;
			}
		}
	}
`;

const HeaderAction = styled.div`
	display: flex;
	align-items: center;
	gap: 12px;
`;

const StatusSelection = styled.div`
	display: flex;
	align-items: center;
	border-radius: 4px;
	padding: 0 8px 0 12px;
	height: 34px;
	border: 1px solid rgba(20, 25, 33, 0.1);
	span {
		font-size: 13px;
		font-weight: 500;
		color: var(--color-gray-900);
	}
	.components-base-control__field {
		margin-bottom: 0;
	}
	.components-input-control__backdrop {
		display: none;
	}
	.wpmvc__control {
		border: 0 none;
		min-height: 24px;
		background-color: transparent;
		border-radius: 0px;
		&.wpmvc__control--is-focused {
			box-shadow: 0 0;
		}
		.wpmvc__value-container {
			padding-right: 0;
			min-width: 80px;
		}
	}
`;

type EditProps = {
	order?: any;
};

const orderStatusOptions = [
	{
		label: __('Pending', 'directorist'),
		value: 'pending',
	},
	{
		label: __('Re-funded', 'directorist'),
		value: 'refunded',
	},
	{
		label: __('Failed', 'directorist'),
		value: 'failed',
	},
	{
		label: __('Cancelled', 'directorist'),
		value: 'cancelled',
	},
	{
		label: __('Unpaid', 'directorist'),
		value: 'unpaid',
	},
	{
		label: __('Expired', 'directorist'),
		value: 'expired',
	},
	{
		label: __('Paid', 'directorist'),
		value: 'paid',
	},
];

export default function OrderEdit({}: EditProps) {
	const [loading, setLoading] = useState(true);
	const [orderStatus, setOrderStatus] = useState('pending');
	const [isSaving, setIsSaving] = useState(false);
	const orderId = useGetId();

	const singleOrderRoute = useMemo(
		() => (orderId ? `/directorist/v1/admin/orders/${orderId}` : ''),
		[orderId]
	);

	registerValuesStore({
		name: 'directorist/single-order',
		path: singleOrderRoute,
	});

	const { refresh } = useValuesStore({
		name: 'directorist/single-order',
		path: singleOrderRoute,
	});

	const { data, isResolved } = useValuesStoreData({
		name: 'directorist/single-order',
		path: singleOrderRoute,
	});

	const order = data?.order;

	const isOrderResolved = isResolved;

	useEffect(() => {
		if (loading && isOrderResolved) {
			setLoading(false);
			setOrderStatus(order?.status);
		}
	}, [isOrderResolved, loading]);

	const saveOrderStatus = async () => {
		if (!orderId) {
			return;
		}

		setIsSaving(true);

		try {
			await apiFetch({
				path: `directorist/v1/admin/orders/${orderId}/status`,
				method: 'POST',
				data: {
					status: orderStatus,
				},
			});
			doAction('wpmvc-toast', {
				message: __(
					'Order status updated successfully',
					'directorist-pricing-plans'
				),
			});
			refresh();
		} catch (error) {
			console.error('Failed to update order status:', error);
			doAction('wpmvc-toast', {
				type: 'error',
				message: __(
					'Oppps! Something went wrong',
					'directorist-pricing-plans'
				),
			});
		} finally {
			setIsSaving(false);
			refresh();
		}
	};

	return (
		<>
			<Fill name="wpmvc-header">
				<SingleOrderHeader>
					<HeaderBreadcrumb>
						<a href="#" className="directorist-single-plan-logo">
							<AngleLeftIcon />
						</a>
						<ul>
							<li>
								<span>{__('Orders', 'directorist')}</span>
							</li>
							<li>
								<AngleRightIcon />
								<span>{__('View Order', 'directorist')}</span>
							</li>
						</ul>
					</HeaderBreadcrumb>
					<HeaderAction>
						<StatusSelection>
							<span>{__('Order Status:', 'directorist')}</span>
							<Select
								options={orderStatusOptions}
								onChange={(option: any) => {
									setOrderStatus(option);
								}}
								isSearchable={false}
								value={orderStatus}
								isDisabled={isSaving}
							/>
						</StatusSelection>
						<Button
							variant="primary"
							onClick={saveOrderStatus}
							isBusy={isSaving}
							disabled={isSaving}
						>
							{isSaving
								? __('Saving...', 'directorist')
								: __('Save Order', 'directorist')}
						</Button>
					</HeaderAction>
				</SingleOrderHeader>
			</Fill>
			<SingleOrderContainer>
				<ContainerLeft>
					<OrderDetails order={order} />
					{order && <Refund order={order} />}
					<Slot name="directorist-order-refund-after">
						{(fills: any) => {
							if (fills.length) {
								return fills;
							}
						}}
					</Slot>
				</ContainerLeft>
				<ContainerRight>
					<CustomerInfo order={order} />
					<PaymentLog order={order} />
				</ContainerRight>
			</SingleOrderContainer>
		</>
	);
}
