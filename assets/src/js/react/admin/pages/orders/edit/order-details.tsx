import Badge from '@/admin/components/badge';
import Card from '@/admin/components/card';
import { displayPrice } from '@/admin/helper/payment';
import { formatDate, getBadgeVariantByStatus } from '@/admin/helper/utils';
import { __, sprintf } from '@wordpress/i18n';
import { InfoHead, InfoList } from './style';

type DetailsProps = {
	order?: any;
};

export default function OrderDetails({ order }: DetailsProps) {
	return (
		<Card title={__('Order Details', 'directorist')}>
			<InfoHead>
				<div className="directorist-order-details-label">
					<span className="directorist-order-id">
						{sprintf(__('Order ID: %s', 'directorist'), order?.id)}
						{order.legacy_id && (
							<span style={{ color: '#6b7280', fontSize: '12px', marginLeft: '8px' }}>
								{sprintf(__('Old ID: #%d', 'directorist'), order.legacy_id)}
							</span>
						)}
					</span>
					<Badge
						className="directorist-badge"
						variant={getBadgeVariantByStatus(order?.status)}
					>
						{order?.status}
					</Badge>
				</div>
				<span className="directorist-order-details-meta">
					{sprintf(
						__('Placed on: %s', 'directorist'),
						formatDate(
							'en-US',
							order?.created_at,
							{
								year: 'numeric',
								month: 'long',
								day: 'numeric',
							},
							true
						)
					)}
				</span>
			</InfoHead>
			<InfoList className="directorist-has-border">
				{order?.listing_id && (
					<li>
						<span>{__('Listing', 'directorist')}</span>
						<span>{order?.listing_title}</span>
					</li>
				)}
				<li>
					<span>{__('Order Type', 'directorist')}</span>
					<span>{order?.order_type}</span>
				</li>
				<li>
					<span>{__('Payment Method', 'directorist')}</span>
					<span>{order?.payment_method}</span>
				</li>
				{order?.transaction_id && (
					<li>
						<span>{__('Transaction ID', 'directorist')}</span>
						<span>{order?.transaction_id}</span>
					</li>
				)}
				<li>
					<span>{__('Amount', 'directorist')}</span>
					<span> {displayPrice(order?.amount, order?.currency)}</span>
				</li>
				<li>
					<span>{__('Sub Total', 'directorist')}</span>
					<span>
						{displayPrice(
							order?.sub_total,
							order?.currency
						)}
					</span>
				</li>
				{order?.discount_amount && (
					<li>
						<span>
							{ order?.discount_label }{ ' ' }
							{ order?.coupon_code ? ( <Badge variant="success" className="directorist-badge">{ __('Coupon:', 'directorist') } {order?.coupon_code}</Badge> ) : '' }
						</span>
						<span>
							-{ displayPrice( order?.discount_amount, order?.currency )}
						</span>
					</li>
				)}
				{order?.tax_amount && (
					<li>
						<span>{ order?.tax_label }</span>
						<span>
							{displayPrice( order?.tax_amount, order?.currency )}
						</span>
					</li>
				)}
				<li className="directorist-list-highlight">
					<span>{__('Total Amount', 'directorist')}</span>
					<span>
						{displayPrice(order?.total_amount, order?.currency)}
					</span>
				</li>
			</InfoList>
		</Card>
	);
}
