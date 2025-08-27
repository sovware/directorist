/**
 * WordPress dependencies
 */
// import { Badge } from '@wordpress/components';
import { useEffect, useMemo, useState } from "@wordpress/element";

/**
 * External dependencies
 */
import { registerValuesStore, useValuesStoreData } from "@wpmvc/data";
// Fallback types for '@wordpress/url' if types are missing at build time
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import React from "react";

/**
 * Internal dependencies
 */
import { Fill } from "@wordpress/components";
import styled from "styled-components";
import Badge from "../../badge.tsx";
import Card from "../../card.tsx";
import { formatDate, getUser } from "../../helper/utils.ts";
import ElementorIcon from "../../icons/elementorIcon.tsx";
import { useGetId } from "../hook/useGetId.ts";
import OrderDetails from "./order-details.tsx";
import Refund from "./refund.tsx";

const SingleOrderContainer = styled.div`
  padding: 30px 48px;
  display: grid;
  grid-template-columns: 2fr 1fr; 
  grid-gap: 30px;
`;

const InfoCard = styled.div``;
const InfoIcon = styled.div``;
const InfoContent = styled.div``;

const PaymentLogContainer = styled.div`
  display: flex;
  flex-direction: column;
  gap: 10px;
`;
const PaymentLogItem = styled.div`
  // display: flex;
  // flex-direction: column;
  // gap: 10px;
`;

const RefundSummary = styled.div``;

const LogDetails = styled.div``;
const LogId = styled.div``;
const RefundTable = styled.div``;
const ContainerLeft = styled.div`

`;

const ContainerRight = styled.div`

`;

type EditProps = {
  order?: any;
};

export default function Edit({  }: EditProps) {
  const [loading, setLoading] = useState(true);
  const orderId = useGetId();

  const singleOrderRoute = useMemo(
    () => (orderId ? (`/directorist/admin/orders/${orderId}`) : ''),
    [orderId],
  );
  
  // const refundRoute = useMemo(
  //   () => (orderId ? (addQueryArgs('/directorist/admin/refunds', { order_id: orderId }) as string) : ''),
  //   [orderId],
  // );

  registerValuesStore({
    name: "directorist/single-order",
    path: singleOrderRoute,
  });

  const { data, isResolved } = useValuesStoreData({
    name: "directorist/single-order",
    path: singleOrderRoute,
  });
  
  const order = data?.order;

  const isOrderResolved = isResolved;

  useEffect(() => {
    if (loading && isOrderResolved) {
      setLoading(false);
    }
  }, [isOrderResolved, loading]);

  const user = getUser(order?.user);
  const dateFormatOptions = {
    year: "numeric",
    month: "long",
    day: "numeric",
  };

  return (
    <>

      <Fill name="wpmvc-header">
            <span>Single order page</span>
      </Fill>
      <SingleOrderContainer>
        <ContainerLeft>
          <OrderDetails order={order} />
          {
            order && 
            <Card title="Refund Management" 
              footer={
                <Refund 
                  order={order}
                  // orderId={orderId}
                  // refunds={refundData} 
                  // isResolved={allRefundResolved} 
                  />
              }
            >
              <>Refunded</>
            </Card>
          }
        </ContainerLeft>
        <ContainerRight>
        <Card title="Payment Log" icon={<ElementorIcon />}>
          <PaymentLogContainer>
            {order?.payments?.map((payment, index) => {
              return (
                <PaymentLogItem key={index}>
                  <LogDetails>
                    <Badge
                      variant={
                        payment?.status === "pending"
                          ? "warning"
                          : payment?.status === "completed"
                            ? "success"
                            : "error"
                      }
                    >
                      {payment?.status}
                    </Badge>
                    <span>
                      {formatDate(
                        "en-US",
                        payment.created_at,
                        {
                          year: "numeric",
                          month: "long",
                          day: "numeric",
                        },
                        true,
                      )}
                    </span>
                  </LogDetails>
                  <LogId></LogId>
                </PaymentLogItem>
              );
            })}
            <PaymentLogItem></PaymentLogItem>
          </PaymentLogContainer>
        </Card>
        </ContainerRight>
        
        

        {/* Refund Summary */}
        
        
          {/* <RefundSummary>
            <p>Amount already refunded: {order?.refunded_amount || 0}</p>
            <p>
              Available to Refund:{" "}
              {order?.final_amount - (order?.refunded_amount || 0)}
            </p>
          </RefundSummary>
          <RefundTable>
            <Table
              items={refundData?.items}
              total={refundData?.total}
              // isLoading={refundData?.is_loading}
              // fields={refundData?.fields}
              // // columns={refundData?.columns}
              // actions={refundData?.actions}
              // search={refundData?.search}
              // sort={refundData?.sort}
            />
          </RefundTable> */}
      </SingleOrderContainer>

    </>
  );
}
