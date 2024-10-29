<?php
/**
 * Widget Markup.
 *
 * NOTE: The $aitt_titles variable contains all of the post titles.
 *
 * @since 0.1.0
 *
 * @package AITT
 */

// Set the list titles style to display: none if no titles present.
$aitt_list_titles_style = empty( $aitt_titles ) ? 'display: none' : '';

// Intro text.
$aitt_intro_text  = __( 'Quickly save blog post titles for inspiration and later use, without clogging up your database with lots of draft posts! ', 'aitt' );
$aitt_intro_text .= __( 'Click the <span class="dashicons dashicons-welcome-write-blog"></span> icon next to a title to start writing once you\'re ready ', 'aitt' );
$aitt_intro_text .= __( 'or click the <span class="dashicons dashicons-trash"></span> icon to forget it entirely. Happy blogging!', 'aitt' );
?>

<div id="aitt" class="aitt">

	<div class="aitt-add-titles">

		<p class="aitt-add-titles--intro">
			<?php echo wp_kses_post( $aitt_intro_text ); ?>
		</p>

		<fieldset class="aitt-add-titles--wrap input-text-wrap">

			<label class="aitt-add-titles--label" for="js-aitt-add-titles--text-field">
				<?php esc_html_e( 'Add New Post Title', 'aitt' ); ?>
			</label>

			<input
			id="js-aitt-add-titles--text-field"
			class="aitt-add-titles--text-field"
			type="text"
			name="aitt_post_title"
			placeholder="<?php esc_attr_e( 'e.g. Your Next Great Blog Post', 'aitt' ); ?>"
			autocomplete="off">

			<label class="aitt-add-titles--label" for="js-aitt-add-titles--checkbox-field">

				<input
				id="js-aitt-add-titles--checkbox-field"
				class="aitt-add-titles--checkbox-field"
				type="checkbox"
				name="aitt_post_private">
				<?php esc_html_e( 'Keep this title hidden from other site users?', 'aitt' ); ?>

			</label>

		</fieldset>

		<button id="js-aitt-add-titles--submit" class="aitt-add-titles--submit button button-primary">
			<?php esc_html_e( 'Save Title', 'aitt' ); ?>
		</button>

		<p id="js-aitt-add-titles--msg-error" class="aitt-add-titles--msg aitt-add-titles--msg-error"></p>

		<p id="js-aitt-add-titles--msg-success" class="aitt-add-titles--msg aitt-add-titles--msg-success"></p>

	</div>

	<div id="js-aitt-list-titles" class="aitt-list-titles" style="<?php echo esc_attr( $aitt_list_titles_style ); ?>">

		<p class="aitt-list-titles--description">
			<?php esc_html_e( 'Saved Post Titles', 'aitt' ); ?>
		</p>

		<hr class="aitt-list-titles--divider"/>

		<ul id="js-aitt-list-titles--list" class="aitt-list-titles--list">
			<?php echo wp_kses_post( $aitt_titles ); ?>
		</ul>

	</div>

</div>
