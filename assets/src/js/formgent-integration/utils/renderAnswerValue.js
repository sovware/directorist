/**
 * Utility function to render different types of form answer values
 * @param {Object} answer - The answer object containing field_type, value, and other properties
 * @returns {JSX.Element} - The rendered answer value component
 */
export const renderAnswerValue = (answer) => {
	const {
		field_type,
		value,
		options,
		option_label,
		rating_limit,
		rating_icon,
		children,
	} = answer;

	switch (field_type) {
		case 'name':
		case 'address':
			return (
				<div className="directorist-enquiry-answer-children">
					{children?.map((child) => (
						<div
							key={child.id}
							className="directorist-enquiry-answer-child"
						>
							<h5 className="directorist-enquiry-answer-title-child">
								{child.label || 'Unknown Field'}:
							</h5>
							<div className="directorist-enquiry-answer-value-child">
								{child.value}
							</div>
						</div>
					))}
				</div>
			);

		case 'email':
			return <a href={`mailto:${value}`}>{value}</a>;

		case 'website':
			return (
				<a href={value} target="_blank" rel="noopener noreferrer">
					{value}
				</a>
			);

		case 'file-upload':
			if (Array.isArray(value)) {
				return (
					<div className="directorist-file-uploads">
						{value.map((file, index) => (
							<a
								key={index}
								href={file}
								target="_blank"
								rel="noopener noreferrer"
							>
								{file.split('/').pop()}
							</a>
						))}
					</div>
				);
			}
			return (
				<a href={value} target="_blank" rel="noopener noreferrer">
					{value}
				</a>
			);

		case 'rating':
			const rating = parseInt(value);
			return (
				<div className="directorist-rating-display">
					<span className="directorist-rating-value">
						<strong>{rating}</strong> out of {rating_limit || 5}
					</span>
				</div>
			);

		case 'dropdown':
		case 'single-choice':
			return <span>{option_label || value}</span>;

		case 'multiple-choice':
			if (options && Array.isArray(options)) {
				const selectedValues = JSON.parse(value || '[]');
				const selectedOptions = options.filter((opt) =>
					selectedValues.includes(opt.value)
				);
				return (
					<div className="directorist-multiple-choice">
						{selectedOptions.map((option, index) => (
							<span
								key={index}
								className="directorist-choice-tag"
							>
								{option.label}
							</span>
						))}
					</div>
				);
			}
			return <span>{value}</span>;

		case 'google-map':
			try {
				const mapData = JSON.parse(value);
				return (
					<div className="directorist-map-display">
						<p>
							<strong>Address:</strong> {mapData.address}
						</p>
						<p>
							<strong>Coordinates:</strong> {mapData.map.lat},{' '}
							{mapData.map.lng}
						</p>
					</div>
				);
			} catch (e) {
				return <span>{value}</span>;
			}

		case 'repeater':
			try {
				// Check if value is already an array or needs to be parsed
				const repeaterData = Array.isArray(value)
					? value
					: JSON.parse(value);
				return (
					<div className="directorist-enquiry-answer-repeater">
						<table>
							<thead>
								<tr>
									{Object.entries(repeaterData[0]).map(
										([key, val]) => (
											<th key={key}>
												{val.label || key}
											</th>
										)
									)}
								</tr>
							</thead>
							<tbody>
								{repeaterData.map((item, index) => (
									<tr key={index}>
										{Object.entries(item).map(
											([key, val]) => (
												<td key={key}>{val.value}</td>
											)
										)}
									</tr>
								))}
							</tbody>
						</table>
					</div>
				);
			} catch (e) {
				return <span>Error rendering repeater data</span>;
			}

		case 'digital-signature':
			return (
				<div className="directorist-signature-display">
					<img
						src={value}
						alt="Digital Signature"
						style={{ maxWidth: '200px', border: '1px solid #ccc' }}
					/>
				</div>
			);

		default:
			return <span>{value}</span>;
	}
};
