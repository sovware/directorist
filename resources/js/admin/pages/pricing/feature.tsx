import Card from '@/admin/components/card';
import Layout from './layout';

interface FeatureProps {
	attributes?: any;
	setAttributes?: any;
	validationErrors?: Record<string, string[]>;
	validateField?: (
		fieldName: string,
		value: any,
		validationRules: any
	) => { isValid: boolean; errors: string[] };
}

export default function Feature({
	attributes,
	setAttributes,
	validationErrors = {},
	validateField,
}: FeatureProps) {
	const renderLeftContent = () => {
		return (
			<>
				<Card title="Feature" />
			</>
		);
	};

	const renderRightContent = () => {
		return (
			<>
				<Card title="Feature" />
			</>
		);
	};

	return (
		<Layout
			views={{
				leftContent: renderLeftContent(),
				rightContent: renderRightContent(),
			}}
		/>
	);
}
