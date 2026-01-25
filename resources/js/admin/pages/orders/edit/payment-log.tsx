import Badge from '@/admin/components/badge';
import Card from '@/admin/components/card';
import { formatDate } from '@/admin/helper/utils';
import { LogItem, LogList } from './style';
import { __, sprintf } from '@wordpress/i18n';

type DetailsProps = {
	order?: any;
};

export default function PaymentLog({ order }: DetailsProps) {
	const getMessage = (payment: any) => {
		const method = payment.method.replace('_', ' ').toUpperCase();
		const currency = payment.currency;
		const amount = payment.amount;
		const transaction_id = payment.transaction_id;

		switch (payment.status) {
			case 'paid':
				return sprintf(
					__(
						'Payment of %1$s %2$s was successfully processed via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'pending':
				return sprintf(
					__(
						'Payment of %1$s %2$s is pending confirmation via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'failed':
				return sprintf(
					__(
						'Payment of %1$s %2$s failed via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'cancelled':
				return sprintf(
					__(
						'Payment of %1$s %2$s was cancelled via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'refunded':
				return sprintf(
					__(
						'Payment of %1$s %2$s was refunded via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'unpaid':
				return sprintf(
					__(
						'Payment of %1$s %2$s is unpaid via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			case 'expired':
				return sprintf(
					__(
						'Payment of %1$s %2$s has expired via %3$s. Transaction ID: %4$s',
						'directorist'
					),
					currency,
					amount,
					method,
					transaction_id
				);
			default:
				return sprintf(
					__('Payment status: %s', 'directorist'),
					payment.status
				);
		}
	};

	return (
		<Card title="Payment Log">
			<LogList>
				{order?.payments?.map((payment, index) => {
					return (
						<LogItem key={index}>
							<Badge
								variant={
									payment?.status === 'pending'
										? 'warning'
										: payment?.status === 'paid'
											? 'success'
											: 'error'
								}
							>
								{payment?.status}
							</Badge>
							<span>
								{formatDate(
									'en-US',
									payment.created_at,
									{
										year: 'numeric',
										month: 'long',
										day: 'numeric',
									},
									true
								)}
							</span>
							<p className="directorist-payment-log-description">
								{getMessage(payment)}
							</p>
						</LogItem>
					);
				})}
			</LogList>
		</Card>
	);
}
