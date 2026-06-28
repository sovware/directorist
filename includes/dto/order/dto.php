<?php

namespace Directorist\DTO\Order;

defined( "ABSPATH" ) || exit;

use Directorist\Helpers\DateTime;

class DTO extends \Directorist\Utils\DTO {
    private int $id;

    private ?int $subscription_id;

    private int $user_id;

    private ?int $listing_id;

    private ?int $is_featured_listing;

    private ?string $ref;

    private ?string $ref_type;

    private float $amount;

    private string $currency;

    private ?string $coupon_code;

    private ?float $coupon_discount;

    private ?string $coupon_discount_type; // fixed, percentage

    private string $tax_type; // percent, fixed

    private float $tax_rate;

    private float $sub_total;

    private string $status;

    private DateTime $expires_at;

    private DateTime $created_at;

    private DateTime $updated_at;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function get_id(): int {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id 
     *
     * @return self
     */
    public function set_id( int $id ): self {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of subscription_id
     *
     * @return ?int
     */
    public function get_subscription_id(): ?int {
        return $this->subscription_id;
    }

    /**
     * Set the value of subscription_id
     *
     * @param ?int $subscription_id 
     *
     * @return self
     */
    public function set_subscription_id( ?int $subscription_id ): self {
        $this->subscription_id = $subscription_id;

        return $this;
    }

    /**
     * Get the value of user_id
     *
     * @return int
     */
    public function get_user_id(): int {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @param int $user_id 
     *
     * @return self
     */
    public function set_user_id( int $user_id ): self {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of listing_id
     *
     * @return ?int
     */
    public function get_listing_id(): ?int {
        return $this->listing_id;
    }

    /**
     * Set the value of listing_id
     *
     * @param ?int $listing_id 
     *
     * @return self
     */
    public function set_listing_id( ?int $listing_id ): self {
        $this->listing_id = $listing_id;

        return $this;
    }

    /**
     * Get the value of is_featured_listing
     *
     * @return ?int
     */
    public function get_is_featured_listing(): ?int {
        return $this->is_featured_listing;
    }

    /**
     * Set the value of is_featured_listing
     *
     * @param ?int $is_featured_listing 
     *
     * @return self
     */
    public function set_is_featured_listing( ?int $is_featured_listing ): self {
        $this->is_featured_listing = $is_featured_listing;

        return $this;
    }

    /**
     * Get the value of ref
     *
     * @return ?string
     */
    public function get_ref(): ?string {
        return $this->ref;
    }

    /**
     * Set the value of ref
     *
     * @param ?string $ref
     *
     * @return self
     */
    public function set_ref( ?string $ref ): self {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get the value of ref_type
     *
     * @return ?string
     */
    public function get_ref_type(): ?string {
        return $this->ref_type;
    }

    /**
     * Set the value of ref_type
     *
     * @param ?string $ref_type
     *
     * @return self
     */
    public function set_ref_type( ?string $ref_type ): self {
        $this->ref_type = $ref_type;

        return $this;
    }

    /**
     * Get the value of amount
     *
     * @return float
     */
    public function get_amount(): float {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @param float $amount 
     *
     * @return self
     */
    public function set_amount( float $amount ): self {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of currency
     *
     * @return string
     */
    public function get_currency(): string {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @param string $currency 
     *
     * @return self
     */
    public function set_currency( string $currency ): self {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of coupon_code
     *
     * @return ?string
     */
    public function get_coupon_code(): ?string {
        return $this->coupon_code;
    }

    /**
     * Set the value of coupon_code
     *
     * @param ?string $coupon_code 
     *
     * @return self
     */
    public function set_coupon_code( ?string $coupon_code ): self {
        $this->coupon_code = $coupon_code;

        return $this;
    }

    /**
     * Get the value of coupon_discount
     *
     * @return ?float
     */
    public function get_coupon_discount(): ?float {
        return $this->coupon_discount;
    }

    /**
     * Set the value of coupon_discount
     *
     * @param ?float $coupon_discount 
     *
     * @return self
     */
    public function set_coupon_discount( ?float $coupon_discount ): self {
        $this->coupon_discount = $coupon_discount;

        return $this;
    }

    /**
     * Get the value of coupon_discount_type
     *
     * @return ?string
     */
    public function get_coupon_discount_type(): ?string {
        return $this->coupon_discount_type;
    }

    /**
     * Set the value of coupon_discount_type
     *
     * @param ?string $coupon_discount_type 
     *
     * @return self
     */
    public function set_coupon_discount_type( ?string $coupon_discount_type ): self {
        $this->coupon_discount_type = $coupon_discount_type;

        return $this;
    }

    /**
     * Get the value of tax_type
     *
     * @return string
     */
    public function get_tax_type(): string {
        return $this->tax_type;
    }

    /**
     * Set the value of tax_type
     *
     * @param string $tax_type 
     *
     * @return self
     */
    public function set_tax_type( string $tax_type ): self {
        $this->tax_type = $tax_type;

        return $this;
    }

    /**
     * Get the value of tax_rate
     *
     * @return float
     */
    public function get_tax_rate(): float {
        return $this->tax_rate;
    }

    /**
     * Set the value of tax_rate
     *
     * @param float $tax_rate 
     *
     * @return self
     */
    public function set_tax_rate( float $tax_rate ): self {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    /**
     * Get the value of sub_total
     *
     * @return float
     */
    public function get_sub_total(): float {
        return $this->sub_total;
    }

    /**
     * Set the value of sub_total
     *
     * @param float $sub_total 
     *
     * @return self
     */
    public function set_sub_total( float $sub_total ): self {
        $this->sub_total = $sub_total;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return string
     */
    public function get_status(): string {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param string $status 
     *
     * @return self
     */
    public function set_status( string $status ): self {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of expires_at
     *
     * @return DateTime
     */
    public function get_expires_at(): DateTime {
        return $this->expires_at;
    }

    /**
     * Set the value of expires_at
     *
     * @param DateTime $expires_at 
     *
     * @return self
     */
    public function set_expires_at( DateTime $expires_at ): self {
        $this->expires_at = $expires_at;

        return $this;
    }

    /**
     * Get the value of created_at
     *
     * @return DateTime
     */
    public function get_created_at(): DateTime {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @param DateTime $created_at 
     *
     * @return self
     */
    public function set_created_at( DateTime $created_at ): self {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     *
     * @return DateTime
     */
    public function get_updated_at(): DateTime {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @param DateTime $updated_at 
     *
     * @return self
     */
    public function set_updated_at( DateTime $updated_at ): self {
        $this->updated_at = $updated_at;

        return $this;
    }
}