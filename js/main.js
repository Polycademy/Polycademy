$(function(){

	// Equalise column height for any plurality of rows, just make sure to give a selector that has multiple elements
	function equalise_height(selector){
		var maxHeight = 0;
		$(selector).height("auto").each(function(){ 
			maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight; 
		}).height(maxHeight);
	}
	
	equalise_height(".grid_box");
	equalise_height(".logistics_grid > section > p");
	$(window).resize(function() { 
		equalise_height(".grid_box");
		equalise_height(".logistics_grid > section > p");
	});
	
	/* Liquid Slider (http://liquidslider.kevinbatdorf.com/) */
	$('#mid_slider_template').liquidSlider({
		dynamicTabs: false, //remove the tabs from the slider
		panelTitleSelector: "h3.slide_title", //use the h3 class=slide_title as the title of the slide
		topScrolling:true, //automatically scroll to the slide when triggered by a relevant anchor
		topScrollingExtraPixels: 25, //add 25 pixels to the top when scrolled to the relevant slide panel
		keyboardNavigation: true, //allow left and right keys to scroll the slider
		crossLinks: true, //allow external anchors (not inside the slider) to push or pull to the relevant slide (anchors tags require: data-liquidslider-ref="SLIDER-ID")
		hashLinking: true, //allows for us to use hashes (#) as a link to push or pull slides
		hashCrossLinks: true, //allows us to use cross link's hashes as links to go to specific slides
		hashNames: true, //allows us to use names rather than numbers for the hashes
		hashTitleSelector: "h3.slide_title", //specifies the name of the hash which corresponds to the specific slide
	});
	
	/*twitter popovers*/
	$('.team_popovers').popover({
		trigger: "click",
	});
	
	/* Anchor Slider by Cedric Dugas Http://www.position-absolute.com */
	/*
	$(document).ready(function() {
		$(".jump_to_slider_template").anchorAnimate()
	});

	jQuery.fn.anchorAnimate = function(settings) {
		settings = jQuery.extend({speed : 1100}, settings);
		return this.each(function(){
			var caller = this
			$(caller).click(function (event) {
				event.preventDefault()
				var elementClick = "#slider_template";
				var destination = $(elementClick).offset().top;
				$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination}, settings.speed, function() {
					window.location.hash = elementClick
				});
				return false;
			})
		})
	}
	*/
	
});