import React from "react";
import Card from "../../card";
import { InfoHead, InfoList, SubscriptionAction } from "./style.tsx";
type DetailsProps = {
    order?: any;
};

export default function Subscription({ order }: DetailsProps) {
    return(
        <Card 
        title="Subscription"
            footer={ (
            <SubscriptionAction>
                <a href="#" className="directorist-external-link">Subscription details</a>
            </SubscriptionAction>
        ) }
        >
            <InfoHead>
              <div className="directorist-subscription-label">
                <span className="directorist-plan-name">Order ID: {order?.id}</span>
              </div>
            </InfoHead>
            <InfoList className="directorist-has-border">
                <li>
                  <span>Start Date:</span>
                  <span>Mall of America</span>
                </li>
                <li>
                  <span>Plan expires on: </span>
                  <span>Featured Listing</span>
                </li>
                <li>
                  <span>Usage Stats: </span>
                  <span><span className="directorist-plan-usage-count">10</span>/24</span>
                </li>
            </InfoList>
          </Card>
    )
}