import { keyframes } from 'styled-components';
import styled from 'styled-components';

const shimmer = keyframes`
	0%   { background-position: -400px 0; }
	100% { background-position:  400px 0; }
`;

const Bar = styled.div<{
	$w?: string;
	$h?: string;
	$mb?: string;
	$radius?: string;
}>`
	height: ${ ( { $h } ) => $h || '14px' };
	width: ${ ( { $w } ) => $w || '100%' };
	border-radius: ${ ( { $radius } ) => $radius || '4px' };
	margin-bottom: ${ ( { $mb } ) => $mb || '0' };
	flex-shrink: 0;
	background: linear-gradient(
		90deg,
		#e2e8f0 0%,
		#cbd5e1 50%,
		#e2e8f0 100%
	);
	background-size: 800px 100%;
	animation: ${ shimmer } 1.2s ease-in-out infinite;
`;

const Grid = styled.div`
	padding: 30px 48px;
	display: grid;
	grid-template-columns: 2fr 1fr;
	gap: 30px;
`;

const Col = styled.div``;

const CardShell = styled.div`
	background: #fff;
	border: 1px solid #e7ecee;
	border-radius: 8px;
	box-shadow: 0 1px 2px rgba( 16, 24, 40, 0.05 );
	&:not( :last-child ) {
		margin-bottom: 24px;
	}
`;

const CardHead = styled.div`
	padding: 20px 32px;
	border-bottom: 1px solid #e7ecee;
`;

const CardBody = styled.div`
	padding: 24px 32px;
`;

const TitleRow = styled.div`
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 8px;
`;

const InfoRow = styled.div`
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 16px;
	padding: 14px 0;
	border-top: 1px solid #f1f5f9;
`;

function SkeletonInfoRows( { count }: { count: number } ) {
	return (
		<>
			{ Array.from( { length: count } ).map( ( _, i ) => (
				<InfoRow key={ i }>
					<Bar $w="35%" $h="12px" />
					<Bar $w="40%" $h="12px" />
				</InfoRow>
			) ) }
		</>
	);
}

export default function OrderDetailsSkeleton() {
	return (
		<Grid>
			{/* ── Left column ── */}
			<Col>
				{/* Order Details card */}
				<CardShell>
					<CardHead>
						<Bar $w="120px" $h="18px" />
					</CardHead>
					<CardBody>
						<TitleRow>
							<Bar $w="170px" $h="24px" />
							<Bar $w="64px"  $h="24px" $radius="2px" />
						</TitleRow>
						<Bar $w="220px" $h="11px" $mb="4px" />
						<SkeletonInfoRows count={ 5 } />
					</CardBody>
				</CardShell>

				{/* Refund Management card */}
				<CardShell>
					<CardHead>
						<Bar $w="160px" $h="18px" />
					</CardHead>
					<CardBody>
						<Bar $w="50%" $h="13px" $mb="8px" />
						<Bar $w="30%" $h="13px" />
					</CardBody>
				</CardShell>
			</Col>

			{/* ── Right column ── */}
			<Col>
				{/* Customer Information card */}
				<CardShell>
					<CardHead>
						<Bar $w="150px" $h="18px" />
					</CardHead>
					<CardBody>
						<Bar $w="55%" $h="14px" $mb="8px" />
						<Bar $w="80%" $h="12px" $mb="16px" />
						<Bar $w="65%" $h="12px" />
					</CardBody>
				</CardShell>

				{/* Payment Log card */}
				<CardShell>
					<CardHead>
						<Bar $w="100px" $h="18px" />
					</CardHead>
					<CardBody>
						<Bar $w="52px" $h="24px" $radius="2px" $mb="6px" />
						<Bar $w="45%" $h="11px" $mb="6px" />
						<Bar $w="90%" $h="13px" />
					</CardBody>
				</CardShell>
			</Col>
		</Grid>
	);
}
