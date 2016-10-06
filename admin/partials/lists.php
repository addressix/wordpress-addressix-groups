<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.meworla.com
 * @since      1.0.0
 *
 * @package    addressixgroups
 * @subpackage addressixgroups/admin/partials
 */

?>
<div class="wrap">
<h1>Personenlisten
<a class="page-title-action" href="<?php echo admin_url('admin.php?page=addressixgroups_lists&new=1'); ?>">Neue Liste Erstellen</a>
</h1>
<p>
<?php
echo '<table class="wp-list-table widefat fixed striped">';
if (isset($this->lists)) {
  foreach($this->lists as $list) {
    echo '<tr class="listitem">';
    echo '<td>';
    ?><div class="title">
	     <a href="<?php echo add_query_arg('list_id', $list->addressixgroupid); ?>"><?php if ($list->name) echo $list->name; else echo 'Kein Titel';?></a></div>
<?php
																			      echo '</td><td>';
    echo '<a href="' . add_query_arg('listmembers_id', $list->addressixgroupid) .'">Mitglieder</a>';
    echo '</td></tr>';
  }
}
echo '</table></div>';


?>