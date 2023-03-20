<form action="<?php echo esc_url(home_url('/')); ?>" class="sidebar-search-form">
	<input type="search" name="s" placeholder="<?php esc_attr_e('Search...', 'resido'); ?>" value="<?php echo get_search_query(); ?>">
	<button type="submit"><i class="ti-search"></i></button>
</form>