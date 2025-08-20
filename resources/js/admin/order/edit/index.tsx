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
import { addQueryArgs } from '@wordpress/url';
import React from "react";

/**
 * Internal dependencies
 */
import styled from "styled-components";
import Badge from "../../badge.tsx";
import Card from "../../card.tsx";
import { formatDate, getUser } from "../../helper/utils.ts";
import ElementorIcon from "../../icons/elementorIcon.tsx";
import { useGetId } from "../hook/useGetId.ts";
import Refund from "./refund.tsx";

const SingleOrderContainer = styled.div``;
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
  const allRefundRoute = useMemo(
    () => (orderId ? (addQueryArgs('/directorist/admin/refunds', { order_id: orderId }) as string) : ''),
    [orderId],
  );

  registerValuesStore({
    name: "directorist/single-order",
    path: singleOrderRoute,
  });

  const { data, isResolved } = useValuesStoreData({
    name: "directorist/single-order",
    path: singleOrderRoute,
  });
  
  registerValuesStore({
    name: "directorist/order-refund",
    path: allRefundRoute,
  });
  
  const { data: refundData, isResolved: allRefundResolved } = useValuesStoreData({
    name: "directorist/order-refund",
    path: allRefundRoute,
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
    <SingleOrderContainer>
      <Card title="Order Details" icon={<ElementorIcon />}>
        {/* UserInfo */}
        <InfoCard>
          <InfoIcon></InfoIcon>
          <InfoContent>
            <h4 className="directorist-label">{user?.display_name}</h4>
            <p>{user?.email}</p>
            <p>User id: {user?.id}</p>
          </InfoContent>
        </InfoCard>
        {/* Listing Details */}
        <InfoCard>
          <InfoIcon></InfoIcon>
          <InfoContent>
            <p>Listing id: {order?.listing_id}</p>
            <p>Plan type: {order?.plan_type} Purchase</p>
            <p>Amount: {order?.amount}</p>
            <p>Coupon discount: {order?.coupon_discount}</p>
          </InfoContent>
        </InfoCard>
        <Badge
          variant={
            order?.status === "pending"
              ? "warning"
              : order?.status === "completed"
                ? "success"
                : "error"
          }
        >
          {order?.status}
        </Badge>
        <p>Payment method: {order?.payment?.method}</p>
        <p>Final amount: {order?.final_amount}</p>
      </Card>
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

      {/* Refund Summary */}
      <Card title="Refund Management" icon={<ElementorIcon />}>
      <Refund order={order} refunds={refundData} />
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
      </Card>
    </SingleOrderContainer>
  );
}
