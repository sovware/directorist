/**
 * WordPress dependencies
 */
import { Fill, SelectControl } from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { Button } from '@wpmvc/components';
import { registerValuesStore, useValuesStoreData } from '@wpmvc/data';
import styled from 'styled-components';

/**
 * Internal dependencies
 */
import { getUser } from '@/admin/helper/utils';
import { useGetId } from '@/admin/hooks/useGetId';
import AngleLeftIcon from '@/admin/icons/AngleLeftIcon';
import AngleRightIcon from '@/admin/icons/AngleRightIcon';
import CustomerInfo from './customer-info';
import ListingDetails from './listing-details';
import OrderDetails from './order-details';
import PaymentLog from './payment-log';
import Refund from './refund';
import Subscription from './subscription';

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
	.directorist-single-plan-logo{
		line-height: 0;
	}
`;

const HeaderBreadcrumb = styled.div`
	display: flex;
	align-items: center;
	gap: 8px;
	ul {
		display: flex;
		align-items: center;
		gap: 8px;
		list-style: none;
		padding: 0;
		margin: 0;
		li{
			display: flex;
			align-items: center;
			margin-bottom: 0;
			svg{
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
	border-radius: 2px;
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
`;

type EditProps = {
	order?: any;
};

export default function OrderEdit({}: EditProps) {
	const [loading, setLoading] = useState(true);
	const orderId = useGetId();

	const singleOrderRoute = useMemo(
		() => (orderId ? `/directorist/admin/orders/${orderId}` : ''),
		[orderId]
	);

	registerValuesStore({
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
		}
	}, [isOrderResolved, loading]);

	const user = getUser(order?.user);
	const dateFormatOptions = {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
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
							<SelectControl
								options={[
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
								]}
							/>
						</StatusSelection>
						<Button variant="primary">
							{__('Save Order', 'directorist')}
						</Button>
					</HeaderAction>
				</SingleOrderHeader>
			</Fill>
			<SingleOrderContainer>
				<ContainerLeft>
					<OrderDetails order={order} />
					{order && <Refund order={order} />}
					{order && <Subscription order={order} />}
				</ContainerLeft>
				<ContainerRight>
					<CustomerInfo order={order} />
					<ListingDetails order={order} />
					<PaymentLog order={order} />
				</ContainerRight>
			</SingleOrderContainer>
		</>
	);
}
