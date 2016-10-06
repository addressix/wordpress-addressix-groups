<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.meworla.com
 * @since      1.0.0
 *
 * @package    Sitzig
 * @subpackage Sitzig/admin/partials
 */
?>
<div class="wrap">
    <h1><?php echo $this->member->firstname . ' ' . $this->member->surname; ?>
</h1>
<?php
if (0 && $this->member->picture && $this->member->picture>'') {
      echo '<img src="https://www.addressix.com/~' . $this->member->addressixid . '/Profile/tn30_' . $this->member->picture . '">';
    }
?>
<h2>In Gruppe: <?php echo $this->list->name ?></h2>
    <?php if ($_GET['m']) {
    $msg = '';
    switch($_GET['m']) {
    case 1:
      $msg = 'Person erfolgreich geändert';      
      break;
    case 2:
      $msg = 'Fehler: Konnte Datum nicht berechnen';
      break;
    case 11:
      $msg = 'Person erfolgreich erstellt';      
      break;
    case 401:
    case 403:
      $msg = 'Keine Berechtigung';
      break;
    default:
      $msg = 'Es ist ein Fehler aufgetreten: ' . $_GET['m'];
      break;
    }
    echo '<div id="message" class="updated fade"><p><strong>' . $msg . '</strong></p></div>';
  }
?>
<form id="person" name="person" method="post" class="person" action="admin-post.php">
    <?php wp_nonce_field('listmember_verify') ?>
    <input type="hidden" name="action" value="addressixgroup_member_edit">
    <input type="hidden" name="listmembers_id" value="<?php echo $this->list->addressixgroupid; ?>">
    <input type="hidden" name="member_id" value="<?php echo $this->member->addressixid; ?>">
<div id="post-body" class="columns-2">
<div id="post-body-content">
    
<h3>Adressen</h3>
<p>Welche Adressen sollen angezeigt werden?
<table class="wp-list-table widefat fixed striped">
<?php 
  // which addresses are public
    if (is_array($this->member->viewaddresses)) {
      $shownaddresses = array_flip($this->member->viewaddresses);
    } else {
      $shownaddresses = array();
    }

foreach($this->member->addresses as $address) {
  echo '<tr><td>';
  echo '<input type="checkbox" name="showaddress_' . $address->addressid . '"';
  if (isset($shownaddresses[$address->addressid])) {
    echo ' checked';
  }  
  echo '></td><td>';
  if ($address->line1 > '') {
    echo $address->line1 . "<br>";
  }
  if ($address->line2 > '') {
    echo $address->line2 . "<br>";
  }
  if ($address->street > '') {
    echo $address->street . ' ' . $address->streetno . "<br>";
  }
  if ($address->pobox > '') {
    echo $address->pobox . "<br>";
  }
  echo $address->zip . ' ' . $address->city;
  echo '</td></tr>';
}

echo '</table><table class="wp-list-table widefat fixed striped">';

foreach($this->member->emails as $address) {
  echo '<tr><td>';
  echo '<input type="checkbox" name="showaddress_' . $address->addressid . '"';
  if (isset($shownaddresses[$address->addressid])) {
    echo ' checked';
  }  
  echo '></td><td>';  
  echo $address->email;
  echo '</td></tr>';
}
?>
</table>

    <?php submit_button(); ?>

</div><!-- .post-body-content -->
<div id="postbox-container-1" class="postbox-container">
</div>
</div><!-- .post-body -->
</form>