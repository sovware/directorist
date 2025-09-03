/**
 * WordPress dependencies
 */
// import { Badge } from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import {
	registerValuesStore,
	useValuesStoreData
} from '@wpmvc/data';
// Fallback types for '@wordpress/url' if types are missing at build time
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import React from 'react';

/**
 * Internal dependencies
 */
import { Fill } from '@wordpress/components';
import styled from 'styled-components';
import { getUser } from '../../helper/utils.ts';
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
					{order && <Refund order={order} /> }
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
