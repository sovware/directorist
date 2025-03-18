<?php
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use SplFileObject;

class Listings_CSV_Importer {

	/**
	 * CSV file.
	 *
	 * @var string
	 */
	protected $file = '';

	/**
	 * SplFileObject instance.
	 *
	 * @var \SplFileObject|null
	 */
	protected $file_object = null;

	protected $separator = ',';

	/**
	 * Constructor.
	 *
	 * @param string $file CSV file path.
	 * @param string $separator CSV delimiter or separator.
	 */
	public function __construct( $file, $separator = ',' ) {
		$this->set_file( $file );
		$this->set_separator( $separator );
	}

	public function set_file( $file ) {
		$this->file = $file;
	}

	public function set_separator( $separator = '' ) {
		if ( ! empty( $separator ) ) {
			$this->separator = $separator;
		}
	}

	public function get_file_object() {
		if ( ! $this->has_file() ) {
			return null;
		}

		if ( ! $this->file_object ) {
			$this->file_object = new SplFileObject( $this->file );
			$this->file_object->setCsvControl( $this->separator, '"', '\\' );
		}

		return $this->file_object;
	}

	public function get_total_items() {
		if ( ! $this->has_file() ) {
			return 0;
		}

		// Total items excluding header.
		$this->get_file_object()->seek(PHP_INT_MAX); // Move to last line
		return $this->get_file_object()->key();
	}

	public function get_header() {
		if (! $this->has_file() ) {
			return [];
		}

		$this->get_file_object()->rewind();
		$header_row = $this->get_file_object()->fgetcsv();

		$this->get_file_object()->next();
		$first_row = $this->get_file_object()->fgetcsv();

		return array_combine( $header_row, $first_row );
	}

	public function has_file() {
		return (bool) $this->file;
	}
}
