import styled from "styled-components";

export const UserInfoContainer = styled.div`
	display: flex;
	flex-direction: column;
	gap: 4px;
	padding: 8px 0;
`;

export const UserLink = styled.a`
	text-decoration: none;

	&:hover {
		text-decoration: underline;
	}
`;