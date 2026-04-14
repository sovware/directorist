<?php

namespace Directorist\DTO\Payment;

defined( "ABSPATH" ) || exit;;

use Directorist\Helpers\DateTime;

class DTO extends \Directorist\Utils\DTO {
    private int $id;

    private int $order_id;

    private float $amount;

    private string $currency;

    private string $status;

    private ?string $transaction_id;

    private string $method;

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
     * Get the value of order_id
     *
     * @return int
     */
    public function get_order_id(): int {
        return $this->order_id;
    }

    /**
     * Set the value of order_id
     *
     * @param int $order_id 
     *
     * @return self
     */
    public function set_order_id( int $order_id ): self {
        $this->order_id = $order_id;

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
     * Get the value of transaction_id
     *
     * @return ?string
     */
    public function get_transaction_id(): ?string {
        return $this->transaction_id;
    }

    /**
     * Set the value of transaction_id
     *
     * @param ?string $transaction_id 
     *
     * @return self
     */
    public function set_transaction_id( ?string $transaction_id ): self {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    /**
     * Get the value of method
     *
     * @return string
     */
    public function get_method(): string {
        return $this->method;
    }

    /**
     * Set the value of method
     *
     * @param string $method 
     *
     * @return self
     */
    public function set_method( string $method ): self {
        $this->method = $method;

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