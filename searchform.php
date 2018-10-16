<form role="search" method="get" class="search-form form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text sr-only">Search for:</span>
		<input type="search" class="search-field" placeholder="Search …" value="<?php echo (isset($_GET['s']) ? esc_html($_GET['s']) : ''); ?>" name="s" title="Search for:" />
	</label>
	<button class="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>