import React from "react";
import styled from "styled-components";

const RefundTable = styled.div``;
const RefundSummary = styled.div``;

type OrderLike = {
    refunded_amount?: number;
    final_amount?: number;
    [key: string]: any;
};

type RefundsLike = {
    items?: Record<string, any>[] | Record<string, any> | undefined;
    total?: number;
    is_loading?: boolean;
    [key: string]: any;
};

interface RefundProps {
    order?: OrderLike;
    refunds?: RefundsLike;
}

const layouts = {
	table: {
		styles: {},
		primaryField: 'id',
	},
};

export default function Refund({ order, refunds }: RefundProps) {
    const generateColumns = () => {
        const items = refunds?.items;
        return Object.keys(items).map((key) => ({
            key,
            id: key,
            label: key.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase()),
        }));
    };

    console.log('ee',generateColumns());
    

    const refunded = Number(order?.refunded_amount || 0);
    const finalAmount = Number(order?.final_amount || 0);
    const availableToRefund = Math.max(finalAmount - refunded, 0);

    return (
        <>
            <RefundSummary>
                <p>Amount already refunded: {refunded}</p>
                <p>Available to Refund: {availableToRefund}</p>
            </RefundSummary>
            <RefundTable>
                {/* <Table
                    items={Array.isArray(refunds?.items) ? refunds?.items : (refunds?.items ? [refunds?.items] : [])}
                    total={refunds?.total || 0}
                    layoutType={ 'table' }
                    // isLoading={refunds?.is_loading}
                    fields={generateColumns}
                >
                </Table> */}
            </RefundTable>
        </>
    );
}