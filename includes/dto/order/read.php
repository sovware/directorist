<?php

namespace Directorist\DTO\Order;

defined( "ABSPATH" ) || exit;

use Directorist\Utils\DTO;

class Read extends DTO {
    private int $page;

    private int $per_page;

    private string $search = '';

    /**
     * Get the value of page
     *
     * @return int
     */
    public function get_page(): int {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @param int $page 
     *
     * @return self
     */
    public function set_page( int $page ): self {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of per_page
     *
     * @return int
     */
    public function get_per_page(): int {
        return $this->per_page;
    }

    /**
     * Set the value of per_page
     *
     * @param int $per_page 
     *
     * @return self
     */
    public function set_per_page( int $per_page ): self {
        $this->per_page = $per_page;

        return $this;
    }

    /**
     * Get the value of search
     *
     * @return string
     */
    public function get_search(): string {
        return $this->search;
    }

    /**
     * Set the value of search
     *
     * @param string $search 
     *
     * @return self
     */
    public function set_search( string $search ): self {
        $this->search = $search;

        return $this;
    }
}