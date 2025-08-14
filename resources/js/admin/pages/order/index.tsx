// // import "@wordpress/dataviews/build-style/style.css";
// import { __ } from "@wordpress/i18n";
// import { Table } from "@wpmvc/components";
// import React from "react";
// import styled from "styled-components";

// const layouts = {
//   table: {
//     styles: {},
//     primaryField: "id",
//   },
// };

// const OrderContainer = styled.div`
//   margin: 15px;
//   padding: 15px;
//   background-color: #fff;
//   .dataviews-view-table {
//     width: 100%;
//   }
// `;

// const Order = ({ orders }: { orders: any[] }) => {
//   const paginatedItems = orders;
//   const totalItems = orders.length;
//   const handleRefresh = () => {};
//   const currentPage = 1;
//   const perPage = 10;
//   const searchTerm = "";
//   // const layouts = [];

//   return (
//     <OrderContainer>
//       <Table
//         items={paginatedItems}
//         total={totalItems}
//         isLoading={false}
//         // titleField={ 'title' }
//         mediaField={"thumbnail_url"}
//         layoutType={"table"}
//         layout={layouts.table}
//         // layouts={ layouts }
//         refresh={handleRefresh}
//         queryParams={{
//           page: currentPage,
//           perPage: perPage,
//           search: searchTerm,
//           sort: { field: "id", direction: "asc" },
//         }}
//         fields={[
//           {
//             id: "id",
//             label: __("Order ID", "directorist"),
//           },
//           {
//             id: "user",
//             label: __("User", "directorist"),
//           },
//           {
//             id: "order_type",
//             label: __("Order Type", "directorist"),
//           },
//           {
//             id: "directory_type",
//             label: __("Directory Type", "directorist"),
//           },
//           {
//             id: "status",
//             label: __("Status", "directorist"),
//           },
//           {
//             id: "total_amount",
//             label: __("Total Amount", "directorist"),
//           },
//           {
//             id: "payment_method",
//             label: __("Payment Method", "directorist"),
//           },
//           {
//             id: "date",
//             label: __("Date", "directorist"),
//           },
//           {
//             id: "actions",
//             label: __("Actions", "directorist"),
//           },
//         ]}
//       />
//     </OrderContainer>
//   );
// };

// export default Order;

import { __ } from '@wordpress/i18n';
import { Column } from '@wpmvc/components/build-types/gutenberg/table/types';
import { Table } from '@wpmvc/dashboard';
import React from "react";

const columns: Column[] = [
	{
		id: 'id',
		label: __( 'ID' ),
	},
	{
		id: 'title',
		label: __( 'Title' ),
	},
];

export default function Order() {
	return (
		<Table
			heading="Orders"
			path="/directorist/admin/orders"
			columns={ columns }
			//@ts-ignore
			layoutType={ 'table' }
		/>
	);
}