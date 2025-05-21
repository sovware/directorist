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

	protected $total_items = 0;

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

		if ( $this->total_items ) {
			return $this->total_items;
		}

		$file = $this->get_file_object();
		$file->rewind();

		// Skip header row
		$file->fgetcsv();

		$count = 0;
		while ( ! $file->eof() ) {
			$data = $file->fgetcsv();
			$data = array_filter( $data );
			if ( empty( $data ) ) {
				continue;
			}
			++$count;
		}

		// Reset file pointer
		$file->rewind();

		$this->total_items = $count;

		return $this->total_items;
	}

	public function get_header() {
		if ( ! $this->has_file() ) {
			return [];
		}

		$file = $this->get_file_object();
		$file->rewind();

		$header_record = $file->fgetcsv();
		$first_record  = $file->fgetcsv();
		$header        = array_combine( $header_record, $first_record );

		$file->rewind();

		return $header;
	}

	public function has_file() {
		return (bool) $this->file;
	}
}
