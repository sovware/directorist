import { useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { Table } from "@wpmvc/dashboard";
import { useAttributes } from "@wpmvc/fields";
import { FieldsType } from "@wpmvc/fields/build-types/types/field";
import React from "react";
import styled from "styled-components";
import Badge from "../../badge";
import AngleDownIcon from "../../icons/angleDownIcon";
import AngleUpIcon from "../../icons/AngleUpIcon";

// Register the store outside the component to ensure it's available before any component mounts

const RefundHistoryContainer = styled.div`
    padding: 16px 32px;
    border-top: 1px solid rgba(0, 0, 0, 0.10);
`;
const RefundTable = styled.div`
    >div{
        padding: 0;
    }
`;
const RefundSummary = styled.div``;
const RefundSubmission = styled.div``;
const RefundTableToggle = styled.span``;

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
    const [showRefundTable, setShowRefundTable] = useState(false);
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

//     const refundRoute = useMemo(
//     () => ((addQueryArgs('/directorist/admin/refunds', { order_id: order?.id }) as string)),
//     [order?.id],
//   );
    // registerCrudStore({
    //     name: "directorist/order-refund",
    //     path: refundRoute
    // });
    //const { refresh } = useCrudStore( { name: "directorist/order-refund", path: `/directorist/admin/refunds/${order?.id}` });
    // const {data, isResolved} = useCrudStoreData( {name: 'directorist/order-refund', selector: 'get'} );
    //   console.log(data);
      
    // useEffect(()=>{
    //     refresh({search: searchTerm, order_id: order?.id, page: currentPage, perPage: perPage})
    // },[searchTerm, currentPage, perPage])

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
        <RefundHistoryContainer>
            {/* <RefundSummary>
                <p>Amount already refunded: {data?.total_refunded}</p>
                <p>Available to Refund: {order?.final_amount - data?.total_refunded}</p>
            </RefundSummary> */}
            
            {
                showRefundTable && 
                <RefundTable>
                    <Table
                        heading="Refunds"
                        storeName="directorist/order-refund"
                        path={`/directorist/admin/orders/${order?.id}/refunds`}
                        columns={columns}
                        create= {{
                            title: __("Add Refund", "directorist"),
                            fields:refundFields
                        }}
                    />
                </RefundTable>
            }

            <RefundTableToggle onClick={()=> setShowRefundTable(!showRefundTable)}>
                {showRefundTable ? <><span>Hide Refund History</span><AngleDownIcon /></> : <><span>Refund History</span><AngleUpIcon /></>}
            </RefundTableToggle>
        </RefundHistoryContainer>
    );
}