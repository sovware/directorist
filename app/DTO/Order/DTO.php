<?php

namespace Directorist\App\DTO\Order;

defined( "ABSPATH" ) || exit;

use Directorist\App\Helpers\DateTime;

class DTO extends \Directorist\WpMVC\DTO\DTO {
    private int $id;

    private ?int $subscription_id;

    private int $user_id;

    private ?int $listing_id;

    private ?int $plan_id;

    private ?int $is_featured_listing;

    private string $type;

    private float $amount;

    private string $currency;

    private float $final_amount;

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
     * Get the value of plan_id
     *
     * @return ?int
     */
    public function get_plan_id(): ?int {
        return $this->plan_id;
    }

    /**
     * Set the value of plan_id
     *
     * @param ?int $plan_id 
     *
     * @return self
     */
    public function set_plan_id( ?int $plan_id ): self {
        $this->plan_id = $plan_id;

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
     * Get the value of type
     *
     * @return string
     */
    public function get_type(): string {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type 
     *
     * @return self
     */
    public function set_type( string $type ): self {
        $this->type = $type;

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
     * Get the value of final_amount
     *
     * @return float
     */
    public function get_final_amount(): float {
        return $this->final_amount;
    }

    /**
     * Set the value of final_amount
     *
     * @param float $final_amount 
     *
     * @return self
     */
    public function set_final_amount( float $final_amount ): self {
        $this->final_amount = $final_amount;

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