import React from "react";
import styled from "styled-components";
import Badge from "../../badge";
import Card from "../../card";
import { formatDate } from "../../helper/utils.ts";
const InfoHead = styled.div`
        .directorist-order-details-label{
            display: flex;
            align-items: center;
            margin: 0 0 5px;
        }
        .directorist-order-id{
            font-size: 24px;
            font-weight: 400;
            color: var(--color-gray-900);
            margin-right: 10px;
        }
        .directorist-order-details-meta{
        font-size: 12px;
        font-weight: 400;
        color: var(--color-gray-600);
        }
    `;

const InfoList = styled.ul`
  li{
    display: flex;
    justify-content: space-between;
    padding: 14px 0 7px;
    border-top: 1px solid var(--color-gray-200);
    margin: 0;
    &:last-child{
      border-bottom: none;
      padding-bottom: 0;
      span{
        font-size: 14px;
        font-weight: 500;
        color: var(--color-gray-900);
      }
    }
    span{
      font-size: 12px;
      color: var(--color-gray-600);
      font-weight: 500;
    }
  }
`;
type DetailsProps = {
    order?: any;
};

export default function OrderDetails({ order }: DetailsProps) {
    return(
        <Card title="Order Details">
            <InfoHead>
              <div className="directorist-order-details-label">
                <span className="directorist-order-id">Order ID: {order?.id}</span>
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
              </div>
              <span className="directorist-order-details-meta">
                Placed on: {formatDate(
                        "en-US",
                        order?.created_at,
                        {
                          year: "numeric",
                          month: "long",
                          day: "numeric",
                        },
                        true,
                      )}
              </span>
            </InfoHead>
            <InfoList>
                <li>
                  <span>Listing</span>
                  <span>Mall of America</span>
                </li>
                <li>
                  <span>Checkout Type:</span>
                  <span>Featured Listing</span>
                </li>
                <li>
                  <span>Payment Method:</span>
                  <span>****1234</span>
                </li>
                <li>
                  <span>Amount:</span>
                  <span>${order?.amount}</span>
                </li>
                <li>
                  <span>Coupon Discount:</span>
                  <span>${order?.coupon_discount || 0}</span>
                </li>
                <li>
                  <span>Final Amount:</span>
                  <span>${order?.final_amount}</span>
                </li>
            </InfoList>
          </Card>
    )
}