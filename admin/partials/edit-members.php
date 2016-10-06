<?php
/**
 * Provide a admin area view for the pluginediting the members
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
<h1>Personenliste <?php echo $this->list->name ?>
</h1>
<p>
<?php
echo '<table class="wp-list-table widefat fixed striped">';
if (isset($this->members)) {
  foreach($this->members->members as $member) {
    echo '<tr class="listitem">';
    echo '<td>';
    if ($member->picture) {
      echo '<img src="https://www.addressix.com/~' . $member->addressixid . '/Profile/tn30_' . $member->picture . '">';
    }
    echo '</td><td>';
    ?><div class="title">
	     <a href="<?php echo add_query_arg('member_id', $member->addressixid); ?>"><?php if ($member->firstname) echo $member->firstname; ?> <?php if ($member->surname) echo $member->surname; else echo 'Kein Titel';?></a></div>
<?php
																			      echo '</td><td>';
    if ($member->rolename) {
      echo $member->rolename;
    }
    echo '</td></tr>';
  }
}
echo '</table></div>';


?>