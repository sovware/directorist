import Card from '@/admin/components/card';
import { __ } from '@wordpress/i18n';
import { InfoBox } from './style';

type DetailsProps = {
	order?: any;
};

export default function CustomerInfo({ order }: DetailsProps) {
	const customerName =
		order?.user?.display_name ||
		order?.user?.user_email ||
		__('Guest customer', 'directorist');
	const customerEmail =
		order?.user?.user_email || __('No user account', 'directorist');

	return (
		<Card title="Customer Information">
			<InfoBox>
				<li className="directorist-infobox-item">
					<span className="directorist-infobox-item-label">
						{customerName}
					</span>
					<span className="directorist-infobox-item-text">
						{customerEmail}
					</span>
				</li>
				{order?.user?.phone && (
					<li className="directorist-infobox-item">
						<span className="directorist-infobox-item-label">
							{__('Phone Number', 'directorist')}
						</span>
						<span className="directorist-infobox-item-text">
							{order?.user?.phone}
						</span>
					</li>
				)}
			</InfoBox>
		</Card>
	);
}
