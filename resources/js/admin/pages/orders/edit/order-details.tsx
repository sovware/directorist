import Badge from '@/admin/components/badge';
import Card from '@/admin/components/card';
import { displayPrice } from '@/admin/helper/payment';
import { formatDate } from '@/admin/helper/utils';
import { __ } from '@wordpress/i18n';
import { InfoHead, InfoList } from './style';

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
						className='directorist-badge'
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
					<span>{__('Listing', 'directorist')}</span>
					<span>{order?.listing_title}</span>
				</li>
				<li>
					<span>{__('Payment Method', 'directorist')}</span>
					<span>{order?.payment_method}</span>
				</li>
				<li>
					<span>{__('Amount', 'directorist')}</span>
					<span> {displayPrice(order?.amount, order?.currency)}</span>
				</li>
				<li>
					<span>{__('Coupon Discount', 'directorist')}</span>
					<span className='directorist-list-coupon'>
						<Badge variant='success' className='directorist-badge'>{order?.coupon_code}</Badge>
						-{displayPrice(order?.coupon_discount || 0, order?.currency)}
					</span>
				</li>
				<li>
					<span>{__('Coupon Discount Type', 'directorist')}</span>
					<span>{order?.coupon_discount_type}</span>
				</li>
				<li className="directorist-list-highlight">
					<span>{__('Final Amount', 'directorist')}</span>
					<span>${order?.final_amount}</span>
				</li>
			</InfoList>
		</Card>
	);
}
