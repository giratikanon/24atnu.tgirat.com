/*****************************************************************
 *
 * flashShadowboxInjector-mootools.js - a mootools injector for pictureflow
 *
 * @author      Guy Katz <http://www.xwave.co.il/>
 * @copyright   2008 Guy Katz
 * @license     free for any type of use as long as you keep this comments section in tact
 * @version     v0.1 - 2008.14.02 - initial release
 *
 * Usage:
 * include the following declarations in your HTML page in order to use pictureflow with mootools:
 * <script type="text/javascript" src="javascript/mootools/mootools-release-1.11.js"></script>
 * <script type="text/javascript" src="javascript/mootools/shadowbox-mootools.js"></script>
 * <script type="text/javascript" src="javascript/mootools/flashShadowboxInjector-mootools.js"></script>
 * <script type="text/javascript" src="javascript/shadowbox.js"></script>
 *
 * <script type="text/javascript">
 * 	window.addEvent('domready', function(){
 *	  Shadowbox.init();
 *	});
 * </script>
 *
 * NOTE: 
 * shadowbox-mootools.js is a mootools shadowbox adapter distributed with the shadowbox component <http://mjijackson.com/shadowbox/>
 * mootools-release-1.11.js is the latest mootools v1.1 JavaScript library <http://www.mootools.net/>
 *
 * Credits:
 * - osamwal <http://www.yaelle.com> for picture flow
 * - Michael J. I. Jackson <http://mjijackson.com/> for shadowbox
 * - Bramus! <http://www.bram.us/> for FlashLightBoxInjector
 *
 *****************************************************************/
	
	var flashShadowboxInjector = new Class({
			
		initialize : function() {
			var objBody = document.getElementsByTagName("body").item(0);
			var objContainer = document.createElement("div");
			objContainer.setAttribute('id','flashShadowboxInjectionBox');
			objContainer.style.display = 'none';
			objBody.appendChild(objContainer);	
			
		},
		
		reset : function() {
			$('flashShadowboxInjectionBox').setHTML("");
		},
		
		appendElement : function(link, title, id, rel) {
			
			var hasChildren = $('flashShadowboxInjectionBox').hasChild();
			var bottomInject =  new Element('a', {
											'id': id,
											'rel': rel,
											'title': title,
											'href': link
										});
			bottomInject.setHTML(link);	
			if(hasChildren){
				$(bottomInject).injectAfter($('flashShadowboxInjectionBox').getLast());
			}else{
				$(bottomInject).injectInside($('flashShadowboxInjectionBox'));
			}

		},
		
		prependElement : function(link, title, id, rel) {
			var topInject =  new Element('a', {
										'id': id,
										'rel': rel,
										'title': title,
										'href': link
									});
			topInject.setHTML(link);	

			$(topInject).injectTop('flashShadowboxInjectionBox');

		},
		
		updateImageList : function() {
			Shadowbox.setup();	
		},
		
		start : function(url,rel,id) {
			var FlashClick={
				href:	 url,
				rel:	 rel,
				tagName: "A",
				id:		 id
			};
			Shadowbox.trigger(FlashClick);
		}
		
	});

	function initFlashShadowboxInjector() { 
		myflashShadowboxInjector = new flashShadowboxInjector(); 
	}

	window.addEvent('load', function(){  
	  initFlashShadowboxInjector();
	});