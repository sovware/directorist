import React from 'react';
import Badge from '../../badge';
import Card from '../../card';
import { formatDate } from '../../helper/utils.ts';
import { InfoHead, InfoList } from './style.tsx';
type DetailsProps = {
	order?: any;
};

export default function OrderDetails({ order }: DetailsProps) {
	return (
		<Card title="Order Details">
			<InfoHead>
				<div className="directorist-order-details-label">
					<span className="directorist-order-id">
						Order ID: {order?.id}
					</span>
					<Badge
						variant={
							order?.status === 'pending'
								? 'warning'
								: order?.status === 'completed'
									? 'success'
									: 'error'
						}
					>
						{order?.status}
					</Badge>
				</div>
				<span className="directorist-order-details-meta">
					Placed on:{' '}
					{formatDate(
						'en-US',
						order?.created_at,
						{
							year: 'numeric',
							month: 'long',
							day: 'numeric',
						},
						true
					)}
				</span>
			</InfoHead>
			<InfoList className="directorist-has-border">
				<li>
					<span>Listing</span>
					<span>Mall of America</span>
				</li>
				<li>
					<span>Checkout Type:</span>
					<span>Featured Listing</span>
				</li>
				<li>
					<span>Payment Method:</span>
					<span>****1234</span>
				</li>
				<li>
					<span>Amount:</span>
					<span>${order?.amount}</span>
				</li>
				<li>
					<span>Coupon Discount:</span>
					<span>${order?.coupon_discount || 0}</span>
				</li>
				<li className="directorist-list-highlight">
					<span>Final Amount:</span>
					<span>${order?.final_amount}</span>
				</li>
			</InfoList>
		</Card>
	);
}
