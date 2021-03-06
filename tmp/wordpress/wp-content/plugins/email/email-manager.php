<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-EMail 2.07										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Manages Your E-Mail Logs													|
|	- wp-content/plugins/email/email-manager.php							|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage EMail
if(!current_user_can('manage_email')) {
	die('Access Denied');
}


### E-Mail Variables
$base_name = plugin_basename('email/email-manager.php');
$base_page = 'admin.php?page='.$base_name;
$email_page = intval($_GET['emailpage']);
$email_sortby = trim($_GET['by']);
$email_sortby_text = '';
$email_sortorder = trim($_GET['order']);
$email_sortorder_text = '';
$email_log_perpage = intval($_GET['perpage']);
$email_sort_url = '';


### Form Sorting URL
if(!empty($email_sortby)) {
	$email_sort_url .= '&amp;by='.$email_sortby;
}
if(!empty($email_sortorder)) {
	$email_sort_url .= '&amp;order='.$email_sortorder;
}
if(!empty($email_log_perpage)) {
	$email_sort_url .= '&amp;perpage='.$email_log_perpage;
}


### Get Order By
switch($email_sortby) {
	case 'id':
		$email_sortby = 'email_id';
		$email_sortby_text = 'ID';
		break;
	case 'fromname':
		$email_sortby = 'email_yourname';
		$email_sortby_text = 'From Name';
		break;
	case 'fromemail':
		$email_sortby = 'email_youremail';
		$email_sortby_text = 'From E-Mail';
		break;
	case 'toname':
		$email_sortby = 'email_friendname';
		$email_sortby_text = 'To Name';
		break;
	case 'toemail':
		$email_sortby = 'email_friendemail';
		$email_sortby_text = 'To E-Mail';
		break;
	case 'postid':
		$email_sortby = 'email_postid';
		$email_sortby_text = 'Post ID';
		break;
	case 'posttitle':
		$email_sortby = 'email_posttitle';
		$email_sortby_text = 'Post Title';
		break;
	case 'ip':
		$email_sortby = 'email_ip';
		$email_sortby_text = 'IP';
		break;
	case 'host':
		$email_sortby = 'email_host';
		$email_sortby_text = 'Host';
		break;
	case 'status':
		$email_sortby = 'email_status';
		$email_sortby_text = 'Status';
		break;
	case 'date':
	default:
		$email_sortby = 'email_timestamp';
		$email_sortby_text = 'Date';
}


### Get Sort Order
switch($email_sortorder) {
	case 'asc':
		$email_sortorder = 'ASC';
		$email_sortorder_text = 'Ascending';
		break;
	case 'desc':
	default:
		$email_sortorder = 'DESC';
		$email_sortorder_text = 'Descending';
}


### Form Processing 
if(!empty($_POST['delete_logs'])) {
	if(trim($_POST['delete_logs_yes']) == 'yes') {
		$delete_logs = $wpdb->query("DELETE FROM $wpdb->email");
		if($delete_logs) {
			$text = '<font color="green">All E-Mail Logs Have Been Deleted.</font>';
		} else {
			$text = '<font color="red">An Error Has Occured While Deleting All E-Mail Logs.</font>';
		}
	}
}


### Get E-Mail Logs Data
$total_email_success = $wpdb->get_var("SELECT COUNT(email_id) FROM $wpdb->email WHERE email_status = '".__('Success')."'");
$total_email_failed = $wpdb->get_var("SELECT COUNT(email_id) FROM $wpdb->email WHERE email_status = '".__('Failed')."'");
$total_email = $total_email_success+$total_email_failed;


### Checking $email_page and $offset
if(empty($email_page) || $email_page == 0) { $email_page = 1; }
if(empty($offset)) { $offset = 0; }
if(empty($email_log_perpage) || $email_log_perpage == 0) { $email_log_perpage = 20; }


### Determin $offset
$offset = ($email_page-1) * $email_log_perpage;


### Determine Max Number Of Polls To Display On Page
if(($offset + $email_log_perpage) > $total_email) { 
	$max_on_page = $total_email; 
} else { 
	$max_on_page = ($offset + $email_log_perpage); 
}


### Determine Number Of Polls To Display On Page
if (($offset + 1) > ($total_email)) { 
	$display_on_page = $total_email; 
} else { 
	$display_on_page = ($offset + 1); 
}


### Determing Total Amount Of Pages
$total_pages = ceil($total_email / $email_log_perpage);


### Get The Logs
$email_logs = $wpdb->get_results("SELECT * FROM $wpdb->email ORDER BY $email_sortby $email_sortorder LIMIT $offset, $email_log_perpage");
?>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<!-- Manage E-Mail -->
<div class="wrap">
	<h2><?php _e('E-Mail Logs'); ?></h2>
	<p><?php _e('Displaying'); ?> <strong><?php echo $display_on_page;?></strong> <?php _e('To'); ?> <strong><?php echo $max_on_page; ?></strong> <?php _e('Of'); ?> <strong><?php echo $total_email; ?></strong> <?php _e('E-Mail Logs'); ?></p>
	<p><?php _e('Sorted By'); ?> <strong><?php echo $email_sortby_text;?></strong> <?php _e('In'); ?> <strong><?php echo $email_sortorder_text;?></strong> <?php _e('Order'); ?></p>
	<table width="100%"  border="0" cellspacing="3" cellpadding="3">
	<tr>
		<th width="5%"><?php _e('ID'); ?></th>
		<th width="17%"><?php _e('From'); ?></th>
		<th width="17%"><?php _e('To'); ?></th>
		<th width="17%"><?php _e('Date / Time'); ?></th>
		<th width="17%"><?php _e('IP / Host'); ?></th>
		<th width="17%"><?php _e('Post Title'); ?></th>
		<th width="10%"><?php _e('Status'); ?></th>
	</tr>
	<?php
		if($email_logs) {
			$i = 0;
			foreach($email_logs as $email_log) {
				if($i%2 == 0) {
					$style = 'style=\'background-color: #eee\'';
				}  else {
					$style = 'style=\'background-color: none\'';
				}
				$email_id = intval($email_log->email_id);
				$email_yourname = stripslashes($email_log->email_yourname);
				$email_youremail = stripslashes($email_log->email_youremail);
				$email_friendname = stripslashes($email_log->email_friendname);
				$email_friendemail = stripslashes($email_log->email_friendemail);
				$email_postid = intval($email_log->email_postid);
				$email_posttitle = stripslashes($email_log->email_posttitle);
				$email_date = gmdate("jS F Y", $email_log->email_timestamp);
				$email_time = gmdate("H:i", $email_log->email_timestamp);
				$email_ip = $email_log->email_ip;
				$email_host = $email_log->email_host;
				$email_status = stripslashes($email_log->email_status);
				echo "<tr $style>\n";
				echo "<td>$email_id</td>\n";
				echo "<td>$email_yourname<br />$email_youremail</td>\n";
				echo "<td>$email_friendname<br />$email_friendemail</td>\n";
				echo "<td>$email_date<br />$email_time</td>\n";
				echo "<td>$email_ip<br />$email_host</td>\n";
				echo "<td>$email_posttitle</td>\n";
				echo "<td>$email_status</td>\n";
				echo '</tr>';
				$i++;
			}
		} else {
			echo '<tr><td colspan="7" align="center"><strong>'.__('No E-Mail Logs Found').'</strong></td></tr>';
		}
	?>
	</table>
		<!-- <Paging> -->
		<?php
			if($total_pages > 1) {
		?>
		<br />
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td align="left" width="50%">
					<?php
						if($email_page > 1 && ((($email_page*$email_log_perpage)-($email_log_perpage-1)) <= $total_email)) {
							echo '<strong>&laquo;</strong> <a href="'.$base_page.'&amp;emailpage='.($email_page-1).'" title="&laquo; '.__('Previous Page').'">'.__('Previous Page').'</a>';
						} else {
							echo '&nbsp;';
						}
					?>
				</td>
				<td align="right" width="50%">
					<?php
						if($email_page >= 1 && ((($email_page*$email_log_perpage)+1) <=  $total_email)) {
							echo '<a href="'.$base_page.'&amp;emailpage='.($email_page+1).'" title="'.__('Next Page').' &raquo;">'.__('Next Page').'</a> <strong>&raquo;</strong>';
						} else {
							echo '&nbsp;';
						}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<?php _e('Pages'); ?> (<?php echo $total_pages; ?>) :
					<?php
						if ($email_page >= 4) {
							echo '<strong><a href="'.$base_page.'&amp;emailpage=1'.$email_sort_url.$email_sort_url.'" title="'.__('Go to First Page').'">&laquo; '.__('First').'</a></strong> ... ';
						}
						if($email_page > 1) {
							echo ' <strong><a href="'.$base_page.'&amp;emailpage='.($email_page-1).$email_sort_url.'" title="&laquo; '.__('Go to Page').' '.($email_page-1).'">&laquo;</a></strong> ';
						}
						for($i = $email_page - 2 ; $i  <= $email_page +2; $i++) {
							if ($i >= 1 && $i <= $total_pages) {
								if($i == $email_page) {
									echo "<strong>[$i]</strong> ";
								} else {
									echo '<a href="'.$base_page.'&amp;emailpage='.($i).$email_sort_url.'" title="'.__('Page').' '.$i.'">'.$i.'</a> ';
								}
							}
						}
						if($email_page < $total_pages) {
							echo ' <strong><a href="'.$base_page.'&amp;emailpage='.($email_page+1).$email_sort_url.'" title="'.__('Go to Page').' '.($email_page+1).' &raquo;">&raquo;</a></strong> ';
						}
						if (($email_page+2) < $total_pages) {
							echo ' ... <strong><a href="'.$base_page.'&amp;emailpage='.($total_pages).$email_sort_url.'" title="'.__('Go to Last Page').'">'.__('Last').' &raquo;</a></strong>';
						}
					?>
				</td>
			</tr>
		</table>	
		<!-- </Paging> -->
		<?php
			}
		?>
	<br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		<input type="hidden" name="page" value="<?php echo $base_name; ?>" />
		Sort Options:&nbsp;&nbsp;&nbsp;
		<select name="by" size="1">
			<option value="id"<?php if($email_sortby == 'email_id') { echo ' selected="selected"'; }?>>ID</option>
			<option value="fromname"<?php if($email_sortby == 'email_yourname') { echo ' selected="selected"'; }?>>From Name</option>
			<option value="fromemail"<?php if($email_sortby == 'email_youremail') { echo ' selected="selected"'; }?>>From E-Mail</option>
			<option value="toname"<?php if($email_sortby == 'email_friendname') { echo ' selected="selected"'; }?>>To Name</option>
			<option value="toemail"<?php if($email_sortby == 'email_friendemail') { echo ' selected="selected"'; }?>>To E-Mail</option>
			<option value="date"<?php if($email_sortby == 'email_timestamp') { echo ' selected="selected"'; }?>>Date</option>
			<option value="postid"<?php if($email_sortby == 'email_postid') { echo ' selected="selected"'; }?>>Post ID</option>
			<option value="posttitle"<?php if($email_sortby == 'email_posttitle') { echo ' selected="selected"'; }?>>Post Title</option>
			<option value="ip"<?php if($email_sortby == 'email_ip') { echo ' selected="selected"'; }?>>IP</option>
			<option value="host"<?php if($email_sortby == 'email_host') { echo ' selected="selected"'; }?>>Host</option>
			<option value="status"<?php if($email_sortby == 'email_status') { echo ' selected="selected"'; }?>>Status</option>	
		</select>
		&nbsp;&nbsp;&nbsp;
		<select name="order" size="1">
			<option value="asc"<?php if($email_sortorder == 'ASC') { echo ' selected="selected"'; }?>>Ascending</option>
			<option value="desc"<?php if($email_sortorder == 'DESC') { echo ' selected="selected"'; } ?>>Descending</option>
		</select>
		&nbsp;&nbsp;&nbsp;
		<select name="perpage" size="1">
		<?php
			for($i=10; $i <= 100; $i+=10) {
				if($email_log_perpage == $i) {
					echo "<option value=\"$i\" selected=\"selected\">Per Page: $i</option>\n";
				} else {
					echo "<option value=\"$i\">Per Page: $i</option>\n";
				}
			}
		?>
		</select>
		<input type="submit" value="Sort" class="button" />
	</form>
</div>

<!-- E-Mail Stats -->
<div class="wrap">
	<h2><?php _e('E-Mail Logs Stats'); ?></h2>
	<table border="0" cellspacing="3" cellpadding="3">
	<tr>
		<th align="left"><?php _e('Total E-Mails:'); ?></th>
		<td align="left"><?php echo number_format($total_email); ?></td>
	</tr>
	<tr>
		<th align="left"><?php _e('Total E-Mail Sent:'); ?></th>
		<td align="left"><?php echo number_format($total_email_success); ?></td>
	</tr>
	<tr>
		<th align="left"><?php _e('Total E-Mail Failed:'); ?></th>
		<td align="left"><?php echo number_format($total_email_failed); ?></td>
	</tr>
	</table>
</div>

<!-- Delete E-Mail Logs -->
<div class="wrap">
	<h2><?php _e('Delete E-Mail Logs'); ?></h2>
	<div align="center">
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<strong>Are You Sure You Want To Delete All E-Mail Logs?</strong><br /><br />
			<input type="checkbox" name="delete_logs_yes" value="yes" />&nbsp;Yes<br /><br />
			<input type="submit" name="delete_logs" value="Delete" class="button" onclick="return confirm('You Are About To Delete All E-Mail Logs\nThis Action Is Not Reversible.\n\n Choose \'Cancel\' to stop, \'OK\' to delete.')" />
		</form>
	</div>
</div>