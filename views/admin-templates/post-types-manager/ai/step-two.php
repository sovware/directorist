<h1>Step 2 Select Keyword</h1>
<select name="keywords" id="directorist-ai-keywords">
<option value="">Select Keywords</option>
<?php 
if( ! empty( $list ) ) {
    foreach( $list as $keyword ) { ?>
            <option value="<?php echo esc_attr( $keyword ); ?>"><?php echo esc_html( $keyword ); ?></option>
    <?php }
}
?>
</select>

 
<button class="directorist-ai-directory-submit-step-two">Submit</button>