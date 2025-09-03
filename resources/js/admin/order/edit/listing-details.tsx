import React from 'react';
import Card from '../../card';
import { InfoList } from './style.tsx';
type DetailsProps = {
	order?: any;
};

export default function ListingDetails({ order }: DetailsProps) {
	return (
		<Card title="Listing Details">
			<InfoList>
				<li>
					<span>Listing Used</span>
					<span>
						<strong>10</strong>/23
					</span>
				</li>
				<li>
					<span>Featured Listing Used</span>
					<span>
						<strong>3</strong>/5
					</span>
				</li>
			</InfoList>
		</Card>
	);
}
