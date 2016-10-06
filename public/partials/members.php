<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.meworla.com
 * @since      1.0.0
 *
 * @package    Addressixgroups
 * @subpackage Addressixgroups/public/partials
 */
?>

<div class="groupmemberlist">
<?php 
    $seen = array();
foreach($this->members->members as $member) { 
  if (isset($seen[$member->addressixid])) {
    continue;
  }
  $seen[$member->addressixid] = 1;
?>
<div class="personentry">

					  <div class="personimg"><?php if (isset($member->picture)) { 
      printf('<img src="https://www.addressix.com/~%d/Profile/tn60_%s">', $member->addressixid, $member->picture);
    } ?>
</div>
<div class="entry">
<h1><?php echo $member->firstname . ' ' . $member->surname?></h1>
<div class="function">
					   <?php 
					    if (isset($member->webview)) {
					      echo $member->webview->funktion;
					    }
					    else if (isset($member->rolename)) {
					     echo $member->rolename;
					   }					   
					   ?></div>
<div class="address">
    <?php ;
						    if ($member->addresses) {
						    foreach($member->addresses as $address) {
						      $lines = array();
						      if ($address->line1>'') {
							$lines[] = $address->line1;
						      }
						      if ($address->line2>'') {
							$lines[] = $address->line2;
						      }
						      if ($address->street>'') {
							$lines[] = trim($address->street . ' ' . $address->streetno);						      }
						      if ($address->pobox>'') {
							$lines[] = $address->pobox;
						      }						      if ($address->city>'') {
							$lines[] = trim($address->zip . ' ' . $address->city);						      }
						      
						    
						      printf('%s<br>', implode(', ',$lines));
						    }
						    }
    ?>
					   <?php if (isset($member->emails))
					       foreach($member->emails as $email) {	  printf('<a href="mailto:%s">%s</a><br>',
		 $email->email, $email->email);
					       }
    ?>
</div></div>

</div>
    <?php } ?>
</div>