<?php

namespace Directorist\App\DTO\Subscription;

defined( "ABSPATH" ) || exit;

class DTO extends \Directorist\WpMVC\DTO\DTO {
    private int $id;

    private int $order_id;

    private string $status; // 'active', 'cancelled', 'past_due', 'expired'

    private string $started_at;

    private string $current_period_end;

    private string $cancelled_at;

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
     * Get the value of started_at
     *
     * @return string
     */
    public function get_started_at(): string {
        return $this->started_at;
    }

    /**
     * Set the value of started_at
     *
     * @param string $started_at 
     *
     * @return self
     */
    public function set_started_at( string $started_at ): self {
        $this->started_at = $started_at;

        return $this;
    }

    /**
     * Get the value of current_period_end
     *
     * @return string
     */
    public function get_current_period_end(): string {
        return $this->current_period_end;
    }

    /**
     * Set the value of current_period_end
     *
     * @param string $current_period_end 
     *
     * @return self
     */
    public function set_current_period_end( string $current_period_end ): self {
        $this->current_period_end = $current_period_end;

        return $this;
    }

    /**
     * Get the value of cancelled_at
     *
     * @return string
     */
    public function get_cancelled_at(): string {
        return $this->cancelled_at;
    }

    /**
     * Set the value of cancelled_at
     *
     * @param string $cancelled_at 
     *
     * @return self
     */
    public function set_cancelled_at( string $cancelled_at ): self {
        $this->cancelled_at = $cancelled_at;

        return $this;
    }
}