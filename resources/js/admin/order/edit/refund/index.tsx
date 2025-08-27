import { useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { useAttributes } from "@wpmvc/fields";
import { FieldsType } from "@wpmvc/fields/build-types/types/field";
import React from "react";
import styled from "styled-components";
import Badge from "../../../badge";
// import Table from "./table";
import Table from "@wpmvc/dashboard";

// Register the store outside the component to ensure it's available before any component mounts


const RefundTable = styled.div``;
const RefundSummary = styled.div``;
const RefundSubmission = styled.div``;

const columns = [
    { id: "id", label: "Refund ID" },
    { id: "amount", label: "Amount" },
    { id: "created_at", label: "Date" },
    { id: "reason", label: "Reason" },
    { 
        id: "status", 
        label: "Status",
        render: ({item}) => {
            return (
                <Badge
                variant={
                  item?.status === "pending"
                    ? "warning"
                    : item?.status === "completed"
                      ? "success"
                      : "error"
                }
              >
                {item?.status}
              </Badge>
            )
        }

    },
];

const addRefundInitialValues = {
    amount: 0,
    reason: '',
    status: 'pending',
}


export default function Refund({ order }) {
    const [ searchTerm, setSearchTerm ] = useState( '' );
	const [ currentPage, setCurrentPage ] = useState( 1 );
    const [ perPage, setPerPage ] = useState( 10 );
    const [attributes, setAttributes] = useAttributes({ ...addRefundInitialValues });
    const [errors, setErrors] = useAttributes({});

    const refundFields: FieldsType = {
        amount: {
          type: "number",
          label: __("Refund amount", "directorist"),
        },
        reason: {
          type: "text",
          label: __("Reason for refund", "directorist"),
        },
        
        status: {
            type: "select",
            label: __("Status", "directorist"),
            options: [
              { label: __("Pending", "directorist"), value: "pending" },
              { label: __("Paid", "directorist"), value: "paid" },
              { label: __("Failed", "directorist"), value: "failed" },
              { label: __("Cancelled", "directorist"), value: "cancelled" },
              { label: __("Refunded", "directorist"), value: "refunded" },
              { label: __("Unpaid", "directorist"), value: "unpaid" },
              { label: __("Expired", "directorist"), value: "expired" },
            ],
            isMulti: false,
          },
      };

  //   const refundRoute = useMemo(
  //   () => ((addQueryArgs('/directorist/admin/refunds', { order_id: order?.id }) as string)),
  //   [order?.id],
  // );
  //   registerCrudStore({
  //       name: "directorist/order-refund",
  //       path: refundRoute
  //   });
  //   const { refresh } = useCrudStore( { name: "directorist/order-refund", path: `/directorist/admin/orders/${order?.id}/refunds` });
  //   const {data, isResolved} = useCrudStoreData( {name: 'directorist/order-refund', selector: 'get'} );

  //   useEffect(()=>{
  //       refresh({search: searchTerm, order_id: order?.id, page: currentPage, perPage: perPage})
  //   },[searchTerm, currentPage, perPage])

    function handleRefresh(params ){
        setSearchTerm( params.search || '' );
        setCurrentPage( params.page || 1 );
        setPerPage( params.perPage || 10 );
    }

    function handleSubmitRefund(e: React.FormEvent){
        e.preventDefault();
        console.log(attributes);
    }
    
    return (
        <>
            {/* <RefundSummary>
                <p>Amount already refunded: {data?.total_refunded}</p>
                <p>Available to Refund: {order?.final_amount - data?.total_refunded}</p>
            </RefundSummary>
            <AddRefund handleSubmitRefund={handleSubmitRefund} refundFields={refundFields} attributes={attributes} setAttributes={setAttributes} errors={errors} setErrors={setErrors} /> */}
            
            
            <Table
                layoutType="table"
                path={`/directorist/admin/orders/${order?.id}/refunds`}
                columns={columns}
                // storeName="directorist/order-refund"
            />
            
            {/* <Table data={data} isResolved={isResolved} handleRefresh={handleRefresh} searchTerm={searchTerm} currentPage={currentPage} perPage={perPage} /> */}
        </>
    );
}