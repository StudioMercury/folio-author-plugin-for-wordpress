<div class="gumby settings-page">
<div id="dialog" title="Dialog Window"><p></p></div>

<div id="wpbody-content" aria-label="Main content" tabindex="0">
    <div class="wrap row">

    <div id="dps-message">
		<div class="message">
             <h2>Folio Producer Settings</h2>
		</div>
	</div>
	
	<form action="options.php" method="post" enctype="multipart/form-data">
        <?php settings_fields( 'dps-settings' ); ?>
        <?php do_settings_sections( DPSFolioAuthor::PREFIX . 'settings' ); ?>
        <?php submit_button(); ?>
    </form>

    </div>
</div>
</div>


