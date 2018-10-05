<?php
$scporder_options = get_option('scporder_options');
$scporder_objects = isset($scporder_options['objects']) ? $scporder_options['objects'] : array();
$scporder_tags = isset($scporder_options['tags']) ? $scporder_options['tags'] : array();
?>

<form method="post">
    <?php
    $post_types = 'atbdp_fields';
    ?>
</form>

<script>
    (function ($) {
        jquery(document).read(function () {

            $("#scporder_allcheck_objects").on('click', function () {
                var items = $("#scporder_select_objects input");
                if ($(this).is(':checked'))
                    $(items).prop('checked', true);
                else
                    $(items).prop('checked', false);
            });

            $("#scporder_allcheck_tags").on('click', function () {
                var items = $("#scporder_select_tags input");
                if ($(this).is(':checked'))
                    $(items).prop('checked', true);
                else
                    $(items).prop('checked', false);
            });
        });

    })(jQuery)
</script>