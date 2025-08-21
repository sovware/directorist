import { Table as RefundTable } from "@wpmvc/components";
import React from "react";
import styled from "styled-components";
import Badge from "../../../badge";

const RefundTableContainer = styled.div``;

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

export default function Table({data, isResolved, handleRefresh, searchTerm, currentPage, perPage}){

    return(
        <RefundTableContainer>
            <RefundTable
                items={Array.isArray(data?.items) ? data?.items : (data?.items ? [data?.items] : [])}
                total={data?.total || 0}
                layoutType={'table'}
                isLoading={ !isResolved }
                refresh={handleRefresh}
                actions={[
                    {
                        id: "delete",
                        label: "Delete",
                        callback: (item) => alert(`Deleting order #${item.order_id}`),
                    },
                    ]}
                queryParams={{
                    search: searchTerm,
                    page: currentPage,
                    perPage: perPage,
                    sort: {} 
                }}
                fields={columns}
            >
            </RefundTable>
        </RefundTableContainer>
    )
}