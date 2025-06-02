<?php

namespace Directorist\App\DTO\Order;

defined( "ABSPATH" ) || exit;

class DTO extends \Directorist\WpMVC\DTO\DTO {
    private int $id;

    private int $user_id;

    private ?int $listing_id = null;

    private string $order_type;

    private float $amount;

    private string $currency;

    private float $final_amount;

    private string $order_status;

    private string $expires_at;

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
     * Get the value of order_type
     *
     * @return string
     */
    public function get_order_type(): string {
        return $this->order_type;
    }

    /**
     * Set the value of order_type
     *
     * @param string $order_type 
     *
     * @return self
     */
    public function set_order_type( string $order_type ): self {
        $this->order_type = $order_type;

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
     * Get the value of order_status
     *
     * @return string
     */
    public function get_order_status(): string {
        return $this->order_status;
    }

    /**
     * Set the value of order_status
     *
     * @param string $order_status 
     *
     * @return self
     */
    public function set_order_status( string $order_status ): self {
        $this->order_status = $order_status;

        return $this;
    }

    /**
     * Get the value of expires_at
     *
     * @return string
     */
    public function get_expires_at(): string {
        return $this->expires_at;
    }

    /**
     * Set the value of expires_at
     *
     * @param string $expires_at 
     *
     * @return self
     */
    public function set_expires_at( string $expires_at ): self {
        $this->expires_at = $expires_at;

        return $this;
    }
}