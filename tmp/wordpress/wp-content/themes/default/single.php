<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    	<div id="video-player"> 
            
            <?php the_content(); ?>
       
        </div>
        <table id="story-summary">
        	<tr id="story-description">
            	<td>
            
                <h2><?php the_category(' '); ?></h2>
                <h3><?php the_title(); ?></h3>
                <h4>By  <?php the_author(); txfx_other_authors(); ?> </h4>
                <div id="summary-text"><?php the_excerpt(); ?>
                </div>
                
                </td>
            </tr>
            <tr id="next-story">
            <td>
            
            
            <?php if (in_category(12)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/01/the-dancer/">7 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(10)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/02/29/the-officer/">7 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(11)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/02/the-engineers/">8 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(14)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/02/the-volunteers/">8 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(13)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/03/the-faithful/">9 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(23)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/04/the-partygoers/">10 PM &#187</a>
            <?php } ?>
            
            
            <?php if (in_category(25)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/05/the-blogger/">11 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(22)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/03/the-archivist/">9 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(15)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/04/the-ta/">10 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(24)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/05/the-professor/">11 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(27)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/05/the-activists/">12 AM &#187</a>
            <?php } ?>
            
             <?php if (in_category(26)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/06/the-doctors/">12 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(28)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/07/the-driver/">1 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(29)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/07/alan-cubbage/">1 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(4)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/08/core">2 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(16)) { ?> 
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/09/the-musician/">2 PM &#187</a>
            <?php } ?>
            
            
            <?php if (in_category(8)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/11/the-early-birds/">5 AM &#187</a>
            <?php } ?>
            
            
            <?php if (in_category(18)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/10/the-fencers/">4 PM &#187</a>
            <?php } ?>
            
            
            
            
            <?php if (in_category(9)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/08/the-inventors/">2 PM &#187</a>
            <?php } ?>
            
            
            
            <?php if (in_category(19)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/11/the-flyers/">5 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(20)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/01/the-skater/">6 PM &#187</a>
            <?php } ?>
            
            <?php if (in_category(5)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/09/wnur/">3 AM &#187</a>
            <?php } ?>
            
            <?php if (in_category(6)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/10/the-night-owls/">4 AM &#187</a>
            <?php } ?>
            
            	<?php if (in_category(21)) { ?>
            	<span style="font-weight: normal;">Next story</span>
                <br /><a href="http://www.24atnu.com/wordpress/2008/03/01/the-chef/">6 AM &#187</a>
            <?php } ?>
            
            
            
            
            
            
            
            
          
            
            </td>
        	</tr>
        </table>

    
    <div id="social">
        
        	<?php comments_template(); ?>
            
        <div id="more">
        	<div id="more-header">
            	Share this story
            </div>
            <div id="more-body">
            	<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script><style> html .fb_share_link { padding:2px 0 0 20px; height:16px; background:url(/img/facebook_share.jpg) no-repeat top left; }</style><a href="http://www.facebook.com/share.php?u=<url>" onclick="return fbs_click()" target="_blank" border=0 class="fb_share_link">Share on Facebook</a>
                
                <p />
                
                <?php if (function_exists('sharethis_button')) { sharethis_button(); } ?>

            </div>
        </div>
    
    
    	<?php endwhile; else: ?>
	
		<p>Sorry, no posts matched your criteria.</p>
	
<?php endif; ?>
	
	</div>

<?php get_footer(); ?>

    
