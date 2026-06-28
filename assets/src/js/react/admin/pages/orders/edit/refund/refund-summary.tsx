import { __ } from '@wordpress/i18n';
import ChartCircleIcon from '@/admin/icons/ChartCircleIcon';
import CheckCircleIcon from '@/admin/icons/CheckCircleIcon';
import { RefundSummaryContainer } from './styles';

export default function RefundSummary({
	refundsData,
	availableRefundAmount,
}: {
	refundsData: any;
	availableRefundAmount: any;
}) {
	return (
		<RefundSummaryContainer>
			<div className="directorist-refund-summary-item">
				<span className="directorist-refund-summary-item__icon">
					<CheckCircleIcon />
				</span>
				<span className="directorist-refund-summary-item__content">
					<span className="directorist-refund-summary-item__label">
						{__('Amount re-funded', 'directorist')}
					</span>
					<span className="directorist-refund-summary-item__value">
						${refundsData?.total_refunded ?? 0}
					</span>
				</span>
			</div>
			<div className="directorist-refund-summary-item">
				<span className="directorist-refund-summary-item__icon">
					<ChartCircleIcon />
				</span>
				<span className="directorist-refund-summary-item__content">
					<span className="directorist-refund-summary-item__label">
						{__('Available to re-fund', 'directorist')}
					</span>
					<span className="directorist-refund-summary-item__value">
						${availableRefundAmount}
					</span>
				</span>
			</div>
		</RefundSummaryContainer>
	);
}
