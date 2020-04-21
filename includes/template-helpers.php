<?php
  function directorist_get_loop_item( $current_fileh, $file ) {
    $file_path = dirname( $current_fileh ) . "/loop/$file.php";
    // echo "<div>$file_path</div>";
    if ( file_exists( $file ) ) { include $file; }
  }