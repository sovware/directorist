import Badge from '@/admin/components/badge';
import Card from '@/admin/components/card';
import { formatDate } from '@/admin/helper/utils';
import { LogItem, LogList } from './style';

type DetailsProps = {
	order?: any;
};

export default function PaymentLog({ order }: DetailsProps) {
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
										: payment?.status === 'completed'
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
								This is dummy text should be replaced by the
								actual text
							</p>
						</LogItem>
					);
				})}
			</LogList>
		</Card>
	);
}
