<?php

namespace Directorist\App\DTO\Payment;

defined( "ABSPATH" ) || exit;;

class DTO extends \Directorist\WpMVC\DTO\DTO {
    private int $id;

    private int $order_id;

    private string $payment_date;

    private float $amount;

    private string $currency;

    private string $payment_status;

    private string $transaction_id;

    private string $payment_method;

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
     * Get the value of payment_date
     *
     * @return string
     */
    public function get_payment_date(): string {
        return $this->payment_date;
    }

    /**
     * Set the value of payment_date
     *
     * @param string $payment_date 
     *
     * @return self
     */
    public function set_payment_date( string $payment_date ): self {
        $this->payment_date = $payment_date;

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
     * Get the value of payment_status
     *
     * @return string
     */
    public function get_payment_status(): string {
        return $this->payment_status;
    }

    /**
     * Set the value of payment_status
     *
     * @param string $payment_status 
     *
     * @return self
     */
    public function set_payment_status( string $payment_status ): self {
        $this->payment_status = $payment_status;

        return $this;
    }

    /**
     * Get the value of transaction_id
     *
     * @return string
     */
    public function get_transaction_id(): string {
        return $this->transaction_id;
    }

    /**
     * Set the value of transaction_id
     *
     * @param string $transaction_id 
     *
     * @return self
     */
    public function set_transaction_id( string $transaction_id ): self {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    /**
     * Get the value of payment_method
     *
     * @return string
     */
    public function get_payment_method(): string {
        return $this->payment_method;
    }

    /**
     * Set the value of payment_method
     *
     * @param string $payment_method 
     *
     * @return self
     */
    public function set_payment_method( string $payment_method ): self {
        $this->payment_method = $payment_method;

        return $this;
    }
}