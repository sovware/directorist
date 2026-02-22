// import '@wordpress/dataviews/build-style/style.css';
import styled from "styled-components";

export const UserInfoContainer = styled.div`
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 8px 0;
`;

export const UserLink = styled.a`
  color: #3a6fac;
  text-decoration: none;

  &:hover {
    text-decoration: underline;
  }
`;

export const OrderTableContainer = styled.div`
  .dataviews-view-table {
    color: var(--wpmvc-gray-500);
  }
  .dataviews__view-actions,
  .dataviews-filters__container {
    padding: 16px 42px;
  }
  .dataviews__view-actions {
    .components-input-control__container {
      background-color: #f0f0f0;
    }
  }

  .directorist-order-total-amount {
    color: var(--wpmvc-gray-900);
  }
`;

export const ActionsDropdownWrapper = styled.div`
	display: flex;
	justify-content: flex-end;
	white-space: nowrap;

	.components-dropdown-menu__toggle {
		min-width: 32px;
		height: 32px;
		padding: 0;
	}

	.components-button.has-icon {
		padding: 0;
	}

	svg {
		width: 18px;
		height: 18px;
	}
`;