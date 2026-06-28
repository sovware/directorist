<?php

namespace Directorist\DTO\Subscription;

defined( "ABSPATH" ) || exit;

use Directorist\Helpers\DateTime;

class DTO extends \Directorist\Utils\DTO {
    private int $id;

    private int $plan_id;

    private int $user_id;

    private string $status;

    private string $started_at;

    private string $current_period_end;

    private DateTime $cancelled_at;

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
     * Get the value of plan_id
     *
     * @return int
     */
    public function get_plan_id(): int {
        return $this->plan_id;
    }

    /**
     * Set the value of plan_id
     *
     * @param int $plan_id 
     *
     * @return self
     */
    public function set_plan_id( int $plan_id ): self {
        $this->plan_id = $plan_id;

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
     * @return DateTime
     */
    public function get_cancelled_at(): DateTime {
        return $this->cancelled_at;
    }

    /**
     * Set the value of cancelled_at
     *
     * @param DateTime $cancelled_at 
     *
     * @return self
     */
    public function set_cancelled_at( DateTime $cancelled_at ): self {
        $this->cancelled_at = $cancelled_at;

        return $this;
    }
}