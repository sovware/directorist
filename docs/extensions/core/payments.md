# Core payment and entitlement context

Read only for checkout, order, refund, renewal, subscription or paid entitlement issues. The installed source determines the active architecture.

Legacy Core: `includes/checkout/class-checkout.php::create_order` (L316), `process_payment` (L383), `complete_free_order` (L429), [includes/gateways/class-gateway.php](../../../includes/gateways/class-gateway.php), [includes/payments/class-order.php](../../../includes/payments/class-order.php). Follow `atbdp_order_created`, order-status/completed hooks and the dynamic `atbdp_process_{$gateway}_payment` emission. Legacy orders use post/meta data including `_listing_id`, `_amount`, `_payment_gateway`, `_payment_status` and `_transaction_id`.

New architecture: [includes/contracts/](../../../includes/contracts/), [includes/dto/](../../../includes/dto/), [includes/enums/](../../../includes/enums/), [includes/repositories/](../../../includes/repositories/), [includes/payment-processors/](../../../includes/payment-processors/), [includes/db-models/](../../../includes/db-models/) and REST controllers. Trace actual processor registration, DTO values, repository writes and event callbacks. Do not transfer legacy order-meta assumptions to new tables or typed DTOs. Optional typed properties can be uninitialized; inspect getters/contracts rather than reading arbitrary properties.

Pricing Plans has legacy and newer package-based source variants. Its selected newer implementation owns plan features, package activation/expiry, quotas, trial eligibility, reassignment, proration and migration. Gateways own remote collection and callbacks. Coupon owns discount rules; Claim/Booking can own the order's business purpose. WooCommerce Pricing Plans follows WooCommerce orders/subscriptions rather than automatically using Directorist's native gateway.

Use one order and trace intended purpose → local request/total → external sandbox transaction → authenticated callback → payment state → order state → package/claim/booking/listing effect. Check duplicate, delayed and failed callbacks. Returning to a success page is not settlement; approving a booking is not payment; creating a plan is not owning an active package.

Follow only the relevant extension topic; do not load every gateway. Sandbox/stub tests must avoid real charges, production subscriptions, real outgoing mail or external client state. Record source/local/client/CI/release evidence separately.
