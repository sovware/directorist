import React from 'react';
import Card from '../../card';
import { InfoBox } from './style.tsx';
type DetailsProps = {
	order?: any;
};

export default function CustomerInfo({ order }: DetailsProps) {
	return (
		<Card title="Customer Information">
			<InfoBox>
				<li className="directorist-infobox-item">
					<span className="directorist-infobox-item-label">
						John Doe
					</span>
					<span className="directorist-infobox-item-text">
						farukahmed78@gmail.com
					</span>
				</li>
				<li className="directorist-infobox-item">
					<span className="directorist-infobox-item-label">
						Phone Number
					</span>
					<span className="directorist-infobox-item-text">
						+880 1771544556
					</span>
				</li>
			</InfoBox>
		</Card>
	);
}
