<?php
    defined( 'ABSPATH' ) || exit;

    /**
     * @var string $instruction
     */
?>
<div class="directorist-payment-table directorist-table-responsive directorist-mb-30">
    <table class="directorist-table">
        <thead>
            <tr>
                <th colspan="2"><?php esc_html_e( 'Payment Instruction', 'directorist' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <?php echo wp_kses_post( $instruction ); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>