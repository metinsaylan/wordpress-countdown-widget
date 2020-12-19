<div id="ms-options" class="wrap options-page">
	<h2><?php echo esc_html( $title ); ?></h2>

<div class="nav">
	<a class="nav-link" href="https://wpassist.me/plugins/countdown/">Plugin page</a>
	<a class="nav-link" href="https://wpassist.me/plugins/countdown/help/">Usage</a>
	<a class="nav-link" href="https://wpassist.me/docs/countdown-widget-shortcode/">Shortcode</a>
	<a class="nav-link" href="https://wpassist.me/donate/">❤️ Donate</a>
</div>

<div id="notifications">
<?php if ( isset($_GET['message']) && isset($messages[$_GET['message']]) ) { ?>
<div id="message" class="updated fade"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php } ?>
<?php if ( isset($_GET['error']) && isset($errors[$_GET['error']]) ) { ?>
<div id="message" class="error fade"><p><?php echo $errors[$_GET['error']]; ?></p></div>
<?php } ?>
</div><!-- /notifications -->

<div class="options-wrap">
<form method="post">

<?php foreach ($options as $field) {
switch ( $field['type'] ) {

	case 'section': ?>

<div class="section-title-wrap">
	<h3 id="<?php echo sanitize_title( $field['name'] ); ?>" class="section-title"><?php echo $field['label']; ?></h3>
</div>

<?php break;
	case 'open': ?>
<!-- Section [Start] -->
<div class="settings-wrap">

<?php break;
	case 'close': ?>

<div class="submit-wrap">
	<?php submit_button( 'Save Changes', 'primary', 'save', false ); ?> <a href="https://wpassist.me/donate/" target="_blank" class="button-secondary">❤️ <?php _e('Donate'); ?></a> 
</div>

</div><!-- settings-wrap -->
<!-- Section [End] -->
<?php break;

	case 'paragraph': ?>

<div class="ms-options-paragraph clearfix">
	<?php echo $field['desc']; ?>
</div>

<?php
break;

	case 'text': ?>

<div class="ms-options-input ms-options-text clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
 	<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" value="<?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] != "") { echo esc_html(stripslashes($current[ $field['id'] ] ) ); } ?>" />
	<small><?php echo $field['desc']; ?></small>
</div>

<?php
break;

case 'textarea':
?>

<div class="ms-options-input ms-options-textarea clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
 	<textarea name="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" cols="" rows=""><?php if ( $current[ $field['id'] ] != "") { echo stripslashes($current[ $field['id'] ] ); } else { echo $field['std']; } ?></textarea>
 <small><?php echo $field['desc']; ?></small>

 </div>

<?php
break;

case 'htmlarea':
?>

<div class="ms-options-input ms-options-textarea clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#<?php echo $field['id']; ?>').wysiwyg();
});
</script>

 	<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" cols="" rows=""><?php if ( $current[ $field['id'] ] != "") { echo stripslashes($current[ $field['id'] ] ); } else { echo $field['std']; } ?></textarea>
 <small><?php echo $field['desc']; ?></small>

</div>

<?php
break;

case 'select':
?>

<div class="ms-options-input ms-options-select clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>

<select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
<?php foreach ($field['options'] as $key=>$name) { ?>
		<option <?php if ( isset($current[ $field['id'] ]) && $current[ $field['id'] ] == $key) { echo 'selected="selected"'; } ?> value="<?php echo $key;?>"><?php echo $name; ?></option><?php } ?>
</select>

	<small><?php echo $field['desc']; ?></small>
</div>
<?php
break;

case "checkbox":
?>

<div class="ms-options-input ms-options-checkbox clearfix">
	<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>

	<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="on" <?php checked($current[ $field['id'] ], "on") ?> />

	<small><?php echo $field['desc']; ?></small>
 </div>

<?php break;

case 'picker':
?>
	<div id="picker"></div>

<?php break;

}
}
?>

<input type="hidden" name="action" value="save" />
</form>

<div class="danger">
<h3 class="inline">Danger Zone</h3><small>This will remove all your saved settings.</small>
<form method="post">
<?php submit_button( 'Reset Options', 'secondary', 'reset', false ); ?> 
<input type="hidden" name="action" value="reset" /> 
</form>
</div>

</div><!-- /options-wrap -->

<div class="debug">
	<?php if(WP_DEBUG){ ?>
	<h3>Debug information</h3>
	<p>You are seeing this because your WP_DEBUG variable is set to true.</p>
	<pre><?php print_r($current) ?></pre>
	<?php } ?>
</div><!-- /debug-info -->

</div> <!-- /wrap options-page -->
