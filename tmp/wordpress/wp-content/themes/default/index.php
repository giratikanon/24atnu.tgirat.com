
<?php get_header(); ?>


<?php 
$hour = date('G'); 
$hour += 14;
if ($hour > 24) $hour -= 24;
echo "<script type='text/javascript'>var hour = " . $hour . " </script>";
?>

<script type="text/javascript">

	var flashVars="";

	flashVars+="xmlfeed=/flow/340.xml";

	flashVars+="&myBckgrnd=0x000000";

	flashVars+="&myColor=0xFFFFFF";

	flashVars+="&myTextColor=0xAAAAAA";

	flashVars+="&myArrowColor=0xFFFFFF";

	flashVars+="&myScrollColor=0x333333";

	flashVars+="&myAlpha=0.0";

	flashVars+="&Border=square";

	flashVars+="&Tooltip=true";

	flashVars+="&lightBox=false";

	flashVars+="&myStep=110";

	flashVars+="&myOffset=60";

	flashVars+="&scaleDown=75";

	flashVars+="&scaleUp=100";

	flashVars+="&MaskScene=true";

	flashVars+="&MaskWidth=950";

	flashVars+="&shownPicture=";
	
	flashVars+=hour;

	flashVars+="&Scrollbar=permanent";

	RunFlash("/flow/pictureflow-H340.swf", "950", "500", "#000000", "window", "PictureFlow", flashVars);

</script>

<?php get_footer(); ?>
