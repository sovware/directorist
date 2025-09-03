/**
 * WordPress dependencies
 */
// import { Badge } from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { registerCrudStore, registerValuesStore, useCrudStore, useCrudStoreData, useValuesStoreData } from '@wpmvc/data';
// Fallback types for '@wordpress/url' if types are missing at build time
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import React from 'react';

/**
 * Internal dependencies
 */
import { Fill } from '@wordpress/components';
import { Create } from '@wpmvc/dashboard';
import { FieldsType } from '@wpmvc/fields/build-types/types/field';
import styled from 'styled-components';
import Card from '../../card.tsx';
import { getUser } from '../../helper/utils.ts';
import ChartCircleIcon from '../../icons/ChartCircleIcon.tsx';
import CheckCircleIcon from '../../icons/CheckCircleIcon.tsx';
import { useGetId } from '../hook/useGetId.ts';
import CustomerInfo from './customer-info.tsx';
import ListingDetails from './listing-details.tsx';
import OrderDetails from './order-details.tsx';
import PaymentLog from './payment-log.tsx';
import Refund from './refund.tsx';
import Subscription from './subscription.tsx';

const SingleOrderContainer = styled.div`
	padding: 30px 48px;
	display: grid;
	grid-template-columns: 2fr 1fr;
	grid-gap: 30px;
`;
const ContainerLeft = styled.div``;

const ContainerRight = styled.div``;
const RefundSubmission = styled.div``;
const RefundSummary = styled.div`
	display: flex;
	align-items: center;
	.directorist-refund-summary-item{
		position: relative;
		display: flex;
		align-items: center;
		gap: 8px;
		&:not(:last-child){
			padding-right: 30px;
			margin-right: 30px;
			&::before{
				content: '';
				position: absolute;
				right: 0;
				top: 50%;
				transform: translateY(-50%);
				width: 1px;
				height: 30px;
				background-color: var(--color-gray-300);
			}
		}
	}
	.directorist-refund-summary-item__content{
		display: flex;
		flex-direction: column;
		gap: 5px;
	}
`;

type EditProps = {
	order?: any;
};

export default function Edit({}: EditProps) {
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

	// Register refunds store and load data for summary and table
	registerCrudStore({
		name: 'directorist/order-refund',
		path: `/directorist/admin/orders/${order?.id}/refunds`,
	});

	const { data: refundsData } = useCrudStoreData({
		name: 'directorist/order-refund',
		selector: 'get',
	});

	const { create, resetQueryParamsAndRefresh } = useCrudStore({
		name: 'directorist/order-refund',
		path: `/directorist/admin/orders/${order?.id}/refunds`,
	});

	const refundFields: FieldsType = {
		amount: {
			type: 'number',
			label: __('Refund amount', 'directorist'),
		},
		reason: {
			type: 'text',
			label: __('Reason for refund', 'directorist'),
		},

		status: {
			type: 'select',
			label: __('Status', 'directorist'),
			options: [
				{ label: __('Pending', 'directorist'), value: 'pending' },
				{ label: __('Paid', 'directorist'), value: 'paid' },
				{ label: __('Failed', 'directorist'), value: 'failed' },
				{ label: __('Cancelled', 'directorist'), value: 'cancelled' },
				{ label: __('Refunded', 'directorist'), value: 'refunded' },
				{ label: __('Unpaid', 'directorist'), value: 'unpaid' },
				{ label: __('Expired', 'directorist'), value: 'expired' },
			],
			isMulti: false,
			menuPosition: 'fixed',
		},
	};

	

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
				<span>Single order page</span>
			</Fill>
			<SingleOrderContainer>
				<ContainerLeft>
					<OrderDetails order={order} />
					{order && (
						<Card
							title="Refund Management"
							headerAction={
								<RefundSubmission>
									<Create
										onSubmit={create}
										fields={refundFields}
										addNewLabel={__('Add Refund', 'directorist')}
										title={__('Add Refund', 'directorist')}
										okLabel={__('Create', 'directorist')}
										cancelLabel={__('Cancel', 'directorist')}
										onSuccess={() => resetQueryParamsAndRefresh()}
									/>
								</RefundSubmission>
							}
							footer={<Refund order={order} />}
						>
							<RefundSummary>
								<div className="directorist-refund-summary-item">
									<span className="directorist-refund-summary-item__icon">
										<CheckCircleIcon />
									</span>
									<span className="directorist-refund-summary-item__content">
										<span className="directorist-refund-summary-item__label">{__('Amount re-funded', 'directorist')}</span>
										<span className="directorist-refund-summary-item__value">${refundsData?.total_refunded ?? 0}</span>
									</span>
								</div>
								<div className="directorist-refund-summary-item">
									<span className="directorist-refund-summary-item__icon">
										<ChartCircleIcon />
									</span>
									<span className="directorist-refund-summary-item__content">
										<span className="directorist-refund-summary-item__label">{__('Available to re-fund', 'directorist')}</span>
										<span className="directorist-refund-summary-item__value">${refundsData?.total_refunded ?? 0}</span>
									</span>
								</div>
							</RefundSummary>
						</Card>
					)}
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
