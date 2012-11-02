/***********************************************************************
*
*  Liquid Slider 0.1
*  Kevin Batdorf
*
*  http://liquidslider.kevinbatdorf.com
*
*  GPL license
*
************************************************************************/

/*jslint bitwise: true, browser: true */
/*global $, jQuery */

// Utility for creating objects in older browsers
if (typeof Object.create !== 'function') {
	Object.create = function (obj) {
		"use strict";
		function F() {}
		F.prototype = obj;
		return new F();
	};
}

(function ($, window, document, undefined) {
	"use strict";
	var Slider = {
		//initialize
		init: function (options, elem) {
			var self = this;

			// Cache the element
			self.elem = elem;
			self.$elem = $(elem);

			$('body').removeClass('no-js');

			// Cache the ID and class. This allows for multiple instances with any ID name supplied
			self.sliderId = '#' + (self.$elem).attr('id');
			self.$sliderId = $(self.sliderId);

			// Set the options
			self.options = $.extend({}, $.fn.liquidSlider.options, options);

			// Variable for the % sign if needed (responsive), otherwise px
			self.pSign =  (self.options.responsive) ? '%' : 'px';

			// Slide animations bad in ie7, so use fade (fixed?)
			if (((navigator.appVersion.indexOf("MSIE 7.") !== -1) || navigator.appVersion.indexOf("MSIE 8.") !== -1)) {
				//self.options.slideEaseFunction = "fade";
				self.dontAnimateHeight = true;
			}

			if (self.options.responsive) { self.determineAnimationType(); }

			// Build the tabs and navigation
			self.build();

			// Add preloader
			if (self.options.preloader) { self.addPreloader(); }

			// Start auto slider
			if (self.options.autoSlide) { self.autoSlide(); }

			self.events();


			if (self.options.preloader & !self.useCSS) { self.removePreloader(); }

			// Disable clicking until fully loaded. Otherwise buggy with css transitions
			if (self.useCSS) { self.clickable = false; }

			$(window).bind("load", function () {
				// Remove preloader from remaining elements
				if (self.options.preloader) {
					$('.liquid-slider-preloader').each(function () {
						$(this).fadeOut(self.options.preloaderFadeOutDuration);
					});
				}
				// Page fully loaded
				self.loaded = true;
				self.clickable = true;
				// This will adjust the slider's height in case of images, etc.
				self.adjustHeight();
				// Adjust the width after load (IE won't otherwise).
				if (self.options.responsive) {self.responsiveEvents(self.loaded); }

				self.configureCSSTransitions();

				self.readyToSlide = true;

			});
		},

		determineAnimationType: function () {
			var self = this,
				animationstring = 'animation',
				keyframeprefix = '',
				domPrefixes = 'Webkit Moz O ms Khtml'.split(' '),
				pfx  = '',
				i = 0;
			// Decide whether or not to use CSS transitions or jQuery
			// https://developer.mozilla.org/en-US/docs/CSS/CSS_animations/Detecting_CSS_animation_support
			self.useCSS = false;

			if (self.elem.style.animationName) { self.useCSS = true; }

			if (self.useCSS === false) {
				for (i = 0; i < domPrefixes.length; i++) {
					if (self.elem.style[domPrefixes[i] + 'AnimationName'] !== undefined) {
						pfx = domPrefixes[i];
						animationstring = pfx + 'Animation';
						keyframeprefix = '-' + pfx.toLowerCase() + '-';
						self.useCSS = true;
						break;
					}
				}
			}
			// Disable CSS transitions if the width is wider than the max
			// Some features are disabled or different when using CSS transitions
			if ($(window).outerWidth() > self.options.useCSSMaxWidth) {self.useCSS = false; }

			// Disable some buggy settings for css transitions(for now)
			// Preloader also works differently
			if (self.useCSS) { self.options.continuous = false; }
		},
		configureCSSTransitions: function () {
			var self = this,
				slideEasing,
				heightEasing;

			self.easing = {
				// Penner equations
				easeOutCubic:	'cubic-bezier(.215,.61,.355,1)',
				easeInOutCubic:	'cubic-bezier(.645,.045,.355,1)',
				easeInCirc:		'cubic-bezier(.6,.04,.98,.335)',
				easeOutCirc:	'cubic-bezier(.075,.82,.165,1)',
				easeInOutCirc:	'cubic-bezier(.785,.135,.15,.86)',
				easeInExpo:		'cubic-bezier(.95,.05,.795,.035)',
				easeOutExpo:	'cubic-bezier(.19,1,.22,1)',
				easeInOutExpo:	'cubic-bezier(1,0,0,1)',
				easeInQuad:		'cubic-bezier(.55,.085,.68,.53)',
				easeOutQuad:	'cubic-bezier(.25,.46,.45,.94)',
				easeInOutQuad:	'cubic-bezier(.455,.03,.515,.955)',
				easeInQuart:	'cubic-bezier(.895,.03,.685,.22)',
				easeOutQuart:	'cubic-bezier(.165,.84,.44,1)',
				easeInOutQuart:	'cubic-bezier(.77,0,.175,1)',
				easeInQuint:	'cubic-bezier(.755,.05,.855,.06)',
				easeOutQuint:	'cubic-bezier(.23,1,.32,1)',
				easeInOutQuint:	'cubic-bezier(.86,0,.07,1)',
				easeInSine:		'cubic-bezier(.47,0,.745,.715)',
				easeOutSine:	'cubic-bezier(.39,.575,.565,1)',
				easeInOutSine:	'cubic-bezier(.445,.05,.55,.95)',
				easeInBack:		'cubic-bezier(.6,-.28,.735,.045)',
				easeOutBack:	'cubic-bezier(.175,.885,.32,1.275)',
				easeInOutBack:	'cubic-bezier(.68,-.55,.265,1.55)'
			};

			// Build a CSS class depending on the type of transition
			if (self.useCSS) {
				slideEasing = 'all ' + self.options.slideEaseDuration + 'ms ' + self.easing[self.options.slideEaseFunction];
				heightEasing = 'all ' + self.options.autoHeightEaseDuration + 'ms ' + self.easing[self.options.autoHeightEaseFunction];

				// Build the width transition rules
				$(self.panelContainer).css({
					'-webkit-transition': slideEasing,
					'-moz-transition': slideEasing,
					'-ms-transition': slideEasing,
					'-o-transition': slideEasing,
					'transition': slideEasing
				});

				// Build the height transition rules
				if (self.options.autoHeight) {
					(self.$sliderId).css({
						'-webkit-transition': heightEasing,
						'-moz-transition': heightEasing,
						'-ms-transition': heightEasing,
						'-o-transition': heightEasing,
						'transition': heightEasing
					});
				}
			}
		},

		addPreloader: function () {
			var self = this;
			if (self.useCSS) {
				$(self.sliderId).append('<div class="liquid-slider-preloader"></div>');
			} else {
				$(self.sliderId + ' .panel-container').children().each(function () {
					$(this).children().append('<div class="liquid-slider-preloader"></div>');
				});
			}
		},

		removePreloader: function () {
			var self = this,
				heightCandidate,
				height = 0;
			// Remove most preloaders (ones without images, etc)
			if (self.options.preloader) {
				$(self.sliderId + ' .panel').children().each(function () {
					// If it has images, get the highest panel and use that until the page is fully loaded
					// Otherwise, the panels with images may be too short.
					if ($(this).find(self.options.preloaderElements).not('.liquid-slider-preloader').length) {
						height = self.getHeighestPanel();
					} else {
						var $this = $(this);
						$this.find('.liquid-slider-preloader').remove();
						if ($this.parent()[0] === $((self.$panelContainer).children()[self.currentTab + ~~self.options.continuous])[0] && self.options.autoHeight) {
							$(self.sliderId).css('height', $((self.$panelContainer).children()[self.currentTab + ~~self.options.continuous]).css('height'));
						}
					}
					return height;
				});
			}
		},

		getHashTags: function (hash) {
			var self = this;
			if (hash && self.options.hashLinking) {
				//set the value as a variable, and remove the #
				self.hashValue = (hash).replace('#', '');
				if (self.options.hashNames) {
					$.each(
						(self.$elem).find(self.options.hashTitleSelector),
						function (n) {
							var $this = $(this).text().replace(/(\s)/g, '-');
							self.hashValue = self.hashValue.replace(self.options.hashTagSeparator, '');
							self.hashValue = self.hashValue.replace(self.options.hashTLD, '');
							if (($this).toLowerCase() === self.hashValue.toLowerCase()) {
								self.hashValue = parseInt(n + 1, 10);
								// Adjust if continuous
								if (self.panelCount && self.options.continuous && self.hashValue === 1) { self.hashValue = self.panelCount - 1; }
								return false;
							}
						}
					);
				}
			}
		},

		updateHashTags: function (tab) {
			var self = this;
			if (self.options.hashLinking) {
					//console.log( ((self.$elem).find(self.options.hashTitleSelector)[self.currentTab] ));
				if (self.options.continuous) {
					if (self.currentTab === self.panelCount - 2) {
						window.location.hash = (self.options.hashNames) ? self.options.hashTagSeparator + $($(self.$elem).find(self.options.hashTitleSelector)[1]).text().replace(/(\s)/g, '-', '-').toLowerCase() + self.options.hashTLD : 1;
					} else if (self.currentTab === -1) {
						window.location.hash = (self.options.hashNames) ? self.options.hashTagSeparator + $($(self.$elem).find(self.options.hashTitleSelector)[self.panelCount - 2]).text().replace(/(\s)/g, '-', '-').toLowerCase() + self.options.hashTLD : self.panelCount - 2;
					} else {
						window.location.hash = (self.options.hashNames) ? self.options.hashTagSeparator + $($(self.$elem).find(self.options.hashTitleSelector)[tab + 1]).text().replace(/(\s)/g, '-', '-').toLowerCase() + self.options.hashTLD : tab + 1;
					}
				} else { window.location.hash = (self.options.hashNames) ? self.options.hashTagSeparator + $($(self.$elem).find(self.options.hashTitleSelector)[tab]).text().replace(/(\s)/g, '-', '-').toLowerCase() + self.options.hashTLD : tab + 1; }
			}
		},

		build: function () {
			var self = this,
				isAbsolute;

			// Grab the current hash tag
			self.getHashTags(window.location.hash);

			// Store current tab
			self.currentTab = (self.hashValue) ? self.hashValue - 1 : self.options.firstPanelToLoad - 1;
			// Store a temp var for callback functions
			self.tabTemp = self.currentTab;

			// Wrap the entire slider (backwards compatible)
			if ((self.$sliderId).parent().attr('class') !== 'liquid-slider-wrapper') {(self.$sliderId).wrap('<div id="' + (self.$elem).attr('id') + '-wrapper" class="liquid-slider-wrapper"></div>'); }
			// Cache the wrapper
			self.$sliderWrap = $(self.sliderId + '-wrapper');

			// Add the .panel class to the individual panels
			$(self.sliderId).children().addClass((self.$elem).attr('id') + '-panel panel');
			self.panelClass = self.sliderId + ' .' + (self.$elem).attr('id') + '-panel';
			self.$panelClass = $(self.panelClass);

			// Wrap all panels in a div, and wrap inner content in a div (not backwards compatible)
			(self.$panelClass).wrapAll('<div class="panel-container"></div>');
			(self.$panelClass).wrapInner('<div class="panel-wrapper"></div>');
			self.panelContainer = (self.$panelClass).parent();
			self.$panelContainer = self.panelContainer;

			// If using fade transition, add the class here and disable other options.
			if (self.options.slideEaseFunction === "fade") {
				(self.$panelClass).addClass('fadeClass');
				self.options.continuous = false;
				$((self.$panelContainer).children()[self.currentTab]).css('display', 'block');
			}

			// Apply starting height to the container
			if (self.options.autoHeight && !self.options.responsive) {
				(self.$sliderId).css('height', $($(self.panelContainer).children()[self.currentTab]).height() + ~~($(self.sliderId + '-wrapper .liquid-nav-right').height()) + self.pSign);
			} else if (!self.options.preloader) {
				(self.$sliderId).css('height', $($(self.panelContainer).children()[self.currentTab]).height());
			}

			// Build navigation tabs
			if (self.options.dynamicTabs) { self.addNavigation(); }

			// Build navigation arrows
			if (self.options.dynamicArrows) { self.addArrows(); }

			// Find cross links (for applying current tabs)
			if (self.options.crossLinks) {
				self.$crosslinks = $('[data-liquidslider-ref*=' + (self.sliderId).split('#')[1] + ']');
			}

			// Create a container width to allow for a smooth float right. Won't calculate arrows if absolute
			isAbsolute = ((self.$leftArrow) && (self.$leftArrow).css('position') === 'absolute') ? 0 : 1;

			self.totalSliderWidth = (self.$sliderId).outerWidth(true) +
				~~($(self.$leftArrow).outerWidth(true)) * isAbsolute +
				~~($(self.$rightArrow).outerWidth(true)) * isAbsolute;

			$((self.$sliderWrap)).css('width', self.totalSliderWidth);
			// Align navigation tabs
			if (self.options.dynamicTabs) { self.alignNavigation(); }

			// Clone panels if continuous is enabled
			if (self.options.continuous) {
				(self.$panelContainer).prepend((self.$panelContainer).children().last().clone());
				(self.$panelContainer).append((self.$panelContainer).children().eq(1).clone());
			}

			// Allow the slider to be clicked
			self.clickable = true;

			// Count the number of panels and get the combined width
			self.panelCount = (self.options.slideEaseFunction === 'fade') ? 1 : $(self.panelClass).length;
			self.panelWidth = $(self.panelClass).outerWidth();
			self.totalWidth = self.panelCount * self.panelWidth;


			// Create a variable for responsive setting
			if (self.options.responsive && !self.useCSS) {
				self.slideWidth = 100;
			} else { self.slideWidth = (self.$sliderId).width(); }

			// Puts the margin at the starting point with no animation. Made for both continuous and firstPanelToLoad features.
			// ~~(self.options.continuous) will equal 1 if true, otherwise 0
			if (self.options.slideEaseFunction !== 'fade' && !self.useCSS) {
				$(self.panelContainer).css('margin-left', (-self.slideWidth * ~~(self.options.continuous)) + (-self.slideWidth * self.currentTab) + self.pSign);
			}

			// Configure the current tab
			self.setCurrent(self.currentTab);

			// Apply the width to the panel container
			$(self.sliderId + ' .panel-container').css('width', self.totalWidth);

			// Make responsive (beta)
			if (self.options.responsive) { self.makeResponsive(); }

			// Apply margin for css3 transitions
			if (self.useCSS) {
				self.panelWidth = $(self.panelClass).outerWidth();
				(self.panelContainer).css({
					'margin-left': '0%'
				});
				$(self.panelContainer).css({
					'transform': 'translate3d(' + ((-self.panelWidth * ~~(self.options.continuous)) + (-self.panelWidth * self.currentTab) + 'px') + ', 0, 0)',
					'-webkit-transform': 'translate3d(' + ((-self.panelWidth * ~~(self.options.continuous)) + (-self.panelWidth * self.currentTab) + 'px') + ', 0, 0)',
					'-moz-transform': 'translate3d(' + ((-self.panelWidth * ~~(self.options.continuous)) + (-self.panelWidth * self.currentTab) + 'px') + ', 0, 0)'

				});
			}
		},

		addNavigation: function () {
			var self = this,
			// The id is assigned here to allow for responsive
				dropDownList,
				dynamicTabsElm = '<' + self.options.navElementTag + ' class="liquid-nav"><ul id="' + (self.$elem).attr('id') + '-nav-ul"></ul></' + self.options.navElementTag + '>',
				selectBoxDefault = (self.options.mobileNavDefaultText) ? '<option disabled="disabled" selected="selected">' + self.options.mobileNavDefaultText + '</option>' : null;
			if (self.options.responsive && self.options.mobileNavigation) {
				dropDownList = '<div class="liquid-slider-select-box"><select id="' + (self.$elem).attr('id') + '-nav-select" name="navigation">' + selectBoxDefault + '</select></div>';
			}

			// Add basic frame
			if (self.options.dynamicTabsPosition === 'bottom') {
				(self.$sliderId).after(dynamicTabsElm);
			} else { (self.$sliderId).before(dynamicTabsElm); }

			// Add responsive navigation
			if (self.options.responsive) {
				self.$sliderNavUl = $(self.sliderId + '-nav-ul');
				(self.$sliderNavUl).before(dropDownList);
			}

			// Add labels
			$.each(
				(self.$elem).find(self.options.panelTitleSelector),
				function (n) {
					$((self.$sliderWrap)).find('.liquid-nav ul').append('<li class="tab' + (n + 1) + '"><a href="#' + (n + 1) + '" title="' + $(this).text() + '">' + $(this).text() + '</a></li>');
				}
			);

			// Adds dropdown navigation for smaller screens if responsive
			if (self.options.responsive && self.options.mobileNavigation) {
				$.each(
					(self.$elem).find(self.options.panelTitleSelector),
					function (n) {
						$((self.$sliderWrap)).find('.liquid-slider-select-box select').append('<option value="tab' + (n + 1) + '">' + $(this).text() + '</option>');
					}
				);
			}
		},

		alignNavigation: function () {
			var self = this,
				arrow = (self.options.dynamicArrowsGraphical) ? '-arrow' : '';

			// Set the alignment, adjusting for margins
			if (self.options.dynamicTabsAlign !== 'center') {
				if (!self.options.responsive) {
					$((self.$sliderWrap)).find('.liquid-nav ul').css(
						'margin-' + self.options.dynamicTabsAlign,
						// Finds the width of the aarows and the margin
						$((self.$sliderWrap)).find(
							'.liquid-nav-' +
								self.options.dynamicTabsAlign +
								arrow
						).outerWidth(true) + parseInt((self.$sliderId).css('margin-' + self.options.dynamicTabsAlign), 10)
					);
				}
				$((self.$sliderWrap)).find('.liquid-nav ul').css('float', self.options.dynamicTabsAlign);
			}
			self.totalNavWidth = $((self.$sliderWrap)).find('.liquid-nav ul').outerWidth(true);

			if (self.options.dynamicTabsAlign === 'center') {
				// Get total width of the navigation tabs and center it
				self.totalNavWidth = 0;
				$((self.$sliderWrap)).find('.liquid-nav li a').each(function () { self.totalNavWidth += $(this).outerWidth(true); });
				if ($.browser.msie) { self.totalNavWidth = self.totalNavWidth + (5); } // Simple IE fix
				$((self.$sliderWrap)).find('.liquid-nav ul').css('width', self.totalNavWidth + 1);
			}
		},

		addArrows: function () {
			var self = this;
			if (self.options.dynamicArrows) {
				(self.$sliderWrap).addClass("arrows");
				if (self.options.dynamicArrowsGraphical) {
					(self.$sliderId).before('<div class="liquid-nav-left-arrow" data-liquidslider-dir="prev" title="Slide left"><a href="#"></a></div>');
					(self.$sliderId).after('<div class="liquid-nav-right-arrow" data-liquidslider-dir="next" title="Slide right"><a href="#"></a></div>');
				} else {
					(self.$sliderId).before('<div class="liquid-nav-left" data-liquidslider-dir="prev" title="Slide left"><a href="#">' + self.options.dynamicArrowLeftText + '</a></div>');
					(self.$sliderId).after('<div class="liquid-nav-right" data-liquidslider-dir="next" title="Slide right"><a href="#">' + self.options.dynamicArrowRightText + '</a></div>');
				}
				// Will hide the arrows on load if on the first or last panel
				if (self.options.hideSideArrows || self.options.hoverArrows || self.options.hideArrowsWhenMobile) {
					self.leftArrow  = self.sliderId + '-wrapper [class^=liquid-nav-left]';
					self.rightArrow = self.sliderId + '-wrapper [class^=liquid-nav-right]';
					self.$leftArrow  = $(self.leftArrow);
					self.$rightArrow = $(self.rightArrow);
					(self.$leftArrow).css({visibility: "hidden", opacity: 0});
					(self.$rightArrow).css({visibility: "hidden", opacity: 0});
				}
				// Add a margin to the top of responsive arrows
				if (self.options.responsive && self.options.dynamicArrows && !self.options.dynamicArrowsGraphical && (self.options.dynamicTabsAlign !== 'center')) {
					(self.$leftArrow).css('margin-' + self.options.dynamicTabsPosition, (self.$sliderNavUl).css('height'));
					(self.$rightArrow).css('margin-' + self.options.dynamicTabsPosition, (self.$sliderNavUl).css('height'));
				}
				// If using the hover arrows, then adjust the transition speed.
				self.options.hideSideArrowsDuration = (self.options.hoverArrows) ? self.options.hoverArrowDuration : self.options.hideSideArrowsDuration;
			}
		},

		hideArrows: function () {
			var self = this;

			// If the tab is 0 or panelCount minus the two continuous clones
			if (self.currentTab === 0 || self.currentTab === (self.panelCount - 2) * ~~(self.options.continuous)) {
				// Fade out the left and make sure the right is faded in (used for on load)
				(self.$leftArrow).fadeOut(self.options.hideSideArrowsDuration, function () {
					$(this).show().css({visibility: "hidden"});
				});
				if ((self.$rightArrow).css('visibility') === 'hidden' && (!self.options.hoverArrows || self.hoverOn)) {
					(self.$rightArrow).css({opacity: 1, visibility: "visible"});
				}
			} else if (self.currentTab === (self.panelCount - (~~(self.options.continuous) * 2) - 1) || self.currentTab === -1) {
				// Fade out the right and make sure the left is faded in (used for on load)
				(self.$rightArrow).fadeOut(self.options.hideSideArrowsDuration, function () {
					$(this).show().css({visibility: "hidden"});
				});
				if ((self.$leftArrow).css('visibility') === 'hidden' && (!self.options.hoverArrows || self.hoverOn)) {
				}
			} else if (!self.options.hoverArrows || self.hoverOn) {
				// Fade in on all other tabs
				if ((self.$leftArrow).css('visibility') === 'hidden') {
					// Duration * 3 because we're using different fade methods (looks similar this way)
					(self.$leftArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDuration * 3);
				}
				if ((self.$rightArrow).css('visibility') === 'hidden') {
					(self.$rightArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDuration * 3);
				}
			}
		},

		makeResponsive: function () {
			var self = this;

			// Adjust widths and add classes to make responsive
			$(self.sliderId + '-wrapper').addClass('liquid-responsive').css({
				'max-width': $(self.sliderId + ' .panel').width(),
				'width': '100%'
			});
			$(self.sliderId + ' .panel-container').css('width', 100 * self.panelCount + self.pSign);
			$(self.sliderId + ' .panel').css('width', 100 / self.panelCount + self.pSign);

			// Set the initial height
			if (!self.options.autoHeight) { (self.$sliderId).css('height', self.getHeighestPanel() + 'px'); }

			// Cache the padding for add/removing arrows
			if (self.options.hideArrowsWhenMobile) {
				self.leftWrapperPadding = $(self.sliderId + '-wrapper').css('padding-left');
				self.rightWrapperPadding = (self.$sliderWrap).css('padding-right');
			}

			if (self.options.dynamicArrows || self.options.dynamicArrowsGraphical) {
				// Add padding to the top equal to the height of the arrows to make room for arrows, if enabled..
				(self.$sliderId).css('padding-top', $(self.sliderId + '-wrapper .liquid-nav-right').css('height'));
			}
			// Set the width to slide
			self.slideWidth = (self.$sliderId).width();
			self.pSign = 'px';

			// Change navigation when the screen size is too small.
			if (self.options.responsive) {self.responsiveEvents(); }

			// Do something when an item is selected from the select box
			$(self.sliderId + '-nav-select').change(function () { self.setCurrent(parseInt($(this).val().split('tab')[1], 10) - 1); });

			// Match the slider margin with the width of the slider (better height transitions)
			$(self.sliderId + '-wrapper').css('width', (self.$sliderId).width());

			// Change navigation if the user resizes the screen.
			if (self.options.responsive) {
				$(window).bind('resize', function() {
					self.responsiveEvents();
				});
			}
		},

		responsiveEvents: function () {
			var self = this,
				mobileNavChangeOver;

			if (self.options.responsive) {
				mobileNavChangeOver = (self.options.mobileUIThreshold || self.totalNavWidth + 10);
				if ((self.$sliderId).outerWidth() < mobileNavChangeOver) {
					if (self.options.mobileNavigation) {
						(self.$sliderNavUl).css('display', 'none');
						$(self.sliderId + '-wrapper .liquid-slider-select-box').css('display', 'block');
						$(self.sliderId + '-nav-select').css('display', 'block');
						// Update the navigation
						if (self.loaded) {$(self.sliderId + '-nav-select').val(self.options.mobileNavDefaultText); }

					}
					if (self.options.hideArrowsWhenMobile && self.options.dynamicArrows) {
						(self.$leftArrow).remove();
						(self.$rightArrow).remove();
					} else if (!self.options.dynamicArrowsGraphical) {
						(self.$leftArrow).css('margin-' + self.options.dynamicTabsPosition, '0');
						(self.$rightArrow).css('margin-' + self.options.dynamicTabsPosition, '0');
					}
				} else {
					if (self.options.mobileNavigation && self.options.dynamicTabs) {
						(self.$sliderNavUl).css('display', 'block');
						$(self.sliderId + '-wrapper .liquid-slider-select-box').css('display', 'none');
						$(self.sliderId + '-nav-select').css('display', 'none');
					}
					if (self.options.hideArrowsWhenMobile && self.options.dynamicArrows && !($(self.leftArrow).length || $(self.rightArrow).length)) {
						self.addArrows();
						self.registerArrows();
					} else if (!self.options.dynamicArrowsGraphical) {
						(self.$leftArrow).css('margin-' + self.options.dynamicTabsPosition, (self.$sliderNavUl).css('height'));
						(self.$rightArrow).css('margin-' + self.options.dynamicTabsPosition, (self.$sliderNavUl).css('height'));
					}
				}
				// While resizing, set the width to 100%
				$(self.sliderId + '-wrapper').css('width', '100%');

				// Set the width to slide
				self.slideWidth = $(self.sliderId).width();

				clearTimeout(self.resizingTimeout);
				self.resizingTimeout = setTimeout(function () {
				// Send to adjust the height after resizing completes
					self.adjustHeight();
					self.transition();
				}, 500);
			}
		},

		registerArrows: function () {
			var self = this;
			// CLick arrows
			if (self.options.dynamicArrows) {
				$((self.$sliderWrap).find('[class^=liquid-nav-]')).on('click', function (e) {

					// These prevent clicking when in continuous mode, which would break it otherwise.
					if (!self.clickable) { return false; }
					self.setCurrent($(this).attr('class').split('-')[2]);
					if (typeof self.options.callbackFunction === 'function') { self.animationCallback(true); }
					return false;
				});
			}
		},

		registerCrossLinks: function () {
			var self = this;
			// Click cross links
			if (self.options.crossLinks) {
				// Re calculate cross links (for applying current tabs)
				self.$crosslinks = $('[data-liquidslider-ref*=' + (self.sliderId).split('#')[1] + ']');
				(self.$crosslinks).on('click', function (e) {
					self.readyToScroll = true; // For scrollTop()

					if (!self.clickable) {return false; }
					// Stop and Play controls
					if (self.options.autoSlideControls) {
						if ($(this).attr('name') === 'stop') {
							$(this).html(self.options.autoSlideStartText).attr('name', 'start');
							self.options.autoSlide = false;
							self.checkAutoSlideStop();
							return false;
						}
						if ($(this).attr('name') === 'start') {
							$(this).html(self.options.autoSlideStopText).attr('name', 'stop');
							self.autoSlideStopped = false;
							self.options.autoSlide = true;
							self.hover();
							self.setCurrent(self.currentTab + 1);
							self.autoSlide();
							return false;
						}
					}
					// Stores the clicked data-liquidslider-ref and checks if it is a # or left or right
					var direction = ($(this).attr('href').split('#')[1]);
					if (direction  === 'left' || direction === 'right') {
						self.setCurrent(direction);
					} else if (self.options.hashCrossLinks) {
						self.getHashTags('#' + direction);
						self.currentTab = self.hashValue - ~~(self.options.continuous);
						self.setCurrent();

					} else {
						self.setCurrent(parseInt(direction - 1, 10));
					}
					self.checkAutoSlideStop();
					if (typeof self.options.callbackFunction === 'function') { self.animationCallback(true); }
					return false;
				});
			}
		},

		events: function () {
			var self = this;

			if (self.options.dynamicArrows) { self.registerArrows(); }
			if (self.options.crossLinks) { self.registerCrossLinks(); }

			// Click tabs
			if (self.options.dynamicTabs) {
				(self.$sliderWrap).find('[class^=liquid-nav] li').on('click', function (e) {

					if (!self.clickable) {return false; }
					self.setCurrent(parseInt($(this).attr('class').split('tab')[1], 10) - 1);
					if (typeof self.options.callbackFunction === 'function') { self.animationCallback(true); }
					return false;
				});
			}

			// Click to stop (or pause) autoslider
			(self.$sliderWrap).find('*').on('click', function (e) {

				self.readyToScroll = true; // For scrollTop()

				// AutoSlide controls.
				self.checkAutoSlideStop();

				if (self.options.autoSlide) {
					if (!self.options.autoSlideStopWhenClicked) {
						self.clickable = true;
					}
				}
				// Stops from speedy clicking for continuous sliding.
				if (self.options.continuous) {clearTimeout(self.continuousTimeout); }
			});

			// Enable Hover Events
			if (self.options.autoSlidePauseOnHover || (self.options.hoverArrows && self.options.dynamicArrows)) {
				self.hoverable = true;
				self.hover();
			}

			// Enable Touch Events
			//if (self.options.swipe) {self.touch(); }

			// Enable Keyboard Events
			if (self.options.keyboardNavigation) {self.keyboard(); }
		},

		hover: function () {
			var self = this;
			// Hover events

			(self.$sliderWrap).hover(
				function () {
					// Hover Arrows
					if (self.options.hoverArrows && self.options.dynamicArrows) {
						self.hoverOn = true;
						(self.$leftArrow).stop(true);
						(self.$rightArrow).stop(true);
						if (self.options.hideSideArrows) {
							self.hideArrows();
						} else {
							(self.$leftArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDurations);
							(self.$rightArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDurations);
						}
					}
					// Pause on Hover
					if (self.options.autoSlidePauseOnHover && self.options.autoSlide) {
						self.dontCallback = true;
						clearTimeout(self.autoslideTimeout);
					}
				},



				function () {
					// Hover Arrows
					if (self.options.hoverArrows && self.options.dynamicArrows) {
						self.hoverOn = false;
						(self.$leftArrow).fadeOut(self.options.hideSideArrowsDuration, function () {
							$(this).show().css({visibility: "hidden"});
						});
						(self.$rightArrow).fadeOut(self.options.hideSideArrowsDuration, function () {
							$(this).show().css({visibility: "hidden"});
						});
					}

					// Pause on Hover
					if (self.options.autoSlidePauseOnHover && self.options.autoSlide && self.clickable) {
						self.dontCallback = false;
						var isAnimating = $('.' + self.panelContainer.attr('class') + ':animated').hasClass(self.panelContainer.attr('class'));
						if (!self.autoSlideStopped && !isAnimating) {
							if (!self.options.autoSlideStopWhenClicked) {
								self.hoverTimeout = setTimeout(function () {
									self.setCurrent(self.options.autoSliderDirection);
									self.autoSlide();
								}, self.options.autoSlideInterval);
							} else {
								self.setCurrent(self.options.autoSliderDirection);
								self.autoSlide();
							}
						}
					}
				}
			);
		},

		touch: function () {
			// Touch Events
			/* Disabled until I can do further device testing
			
			var self = this;
				$(self.sliderId + ' .panel').on('touchstart', function (e) {
					
					var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
					// Starting point
					self.swipeStart = touch.pageX;
					//e.preventDefault();
				});

				$(self.sliderId + ' .panel').on('touchmove', function (e) {

					var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
					if (Math.abs(self.swipeStart - touch.pageX) > self.options.swipeThreshold) {
						e.preventDefault();
					}
				});

				$(self.sliderId + ' .panel').on('touchend', function (e) {
					var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
					if (Math.abs(self.swipeStart - touch.pageX) > self.options.swipeThreshold) {
						//e.preventDefault();
						// If they swipe left, you want the panels to go right, etc
						self.swipeDir = (self.swipeStart > touch.pageX) ? 'right' : 'left';
						self.setCurrent(self.swipeDir);
						self.clickable = false;
						$(this).trigger('click');
					}
					
					self.checkAutoSlideStop();
					if (typeof self.options.callbackFunction === 'function') { self.animationCallback(true); }
				});
			*/
		},

		keyboard: function () {
			// Keyboard Events
			var self = this;
			$(document).keydown(function (event) {
				self.readyToScroll = true; // For scrollTop()
				var key = event.keyCode || event.which;
				if (event.target.type !== 'textarea' && event.target.type !== 'textbox') {
					if (key === self.options.leftKey) {
						self.setCurrent('right');
					}
					if (key === self.options.rightKey) {
						self.setCurrent('left');
					}
					$.each(self.options.panelKeys, function (index, value) {
						if (key === value) {
							self.setCurrent(index - 1);
						}
					});
				}
				self.clickable = false;
				self.checkAutoSlideStop();
				if (typeof self.options.callbackFunction === 'function') { self.animationCallback(true); }
			});
		},

		setCurrent: function (direction) {
			var self = this;
			if (self.clickable) {
				self.clickable = false;
				if (typeof direction === 'number') {
					self.currentTab = direction;
				} else {
					// "left" = -1; "right" = 1;
					self.currentTab += (~~(direction === 'right') || -1);
					// If not continuous, slide back at the last or first panel
					if (!self.options.continuous) {
						self.currentTab = (self.currentTab < 0) ? $(self.panelClass).length - 1 : (self.currentTab % $(self.panelClass).length);
					}
				}
				// This is so the height will match the current panel, ignoring the clones.
				// It also adjusts the count for the "currrent" class that's applied
				//console.log(self.currentTab);
				if (self.options.continuous) {
					self.panelHeightCount = self.currentTab + 1;
					if (self.currentTab === self.panelCount - 2) {
						self.setTab = 0;
					} else if (self.currentTab === -1) {
						self.setTab = self.panelCount - 3;
					} else {
						self.setTab = self.currentTab;
					}
				} else {
					self.panelHeightCount = self.currentTab;
					self.setTab = self.currentTab;
				}
				// Add and remove current class.
				$((self.$sliderWrap)).find('.tab' + (self.setTab + 1) + ' a:first')
					.addClass('current')
					.parent().siblings().children().removeClass('current');

				// Add current class to cross linked Tabs
				if (self.options.crossLinks) {
					(self.$crosslinks).each(function () {
						if (self.options.hashCrossLinks) {
							if ($(this).attr('href') === ('#' + $($(self.panelContainer).children()[(self.setTab + ~~(self.options.continuous))]).find(self.options.panelTitleSelector).text().replace(/(\s)/g, '-').toLowerCase())) {
								$('.currentCrossLink').removeClass('currentCrossLink');
								$(this).addClass('currentCrossLink');
							}
						} else if ($(this).attr('href') === '#' + (self.setTab + 1)) {
							$('.currentCrossLink').removeClass('currentCrossLink');
							$(this).addClass('currentCrossLink');
						}
					});
				}

				// Update the dropdown menu when small.
				if (self.options.responsive && self.options.mobileNavigation && self.loaded) { $(self.sliderId + '-nav-select').val('tab' + (self.setTab + 1)); }

				// Update hash tags
				self.updateHashTags(self.currentTab);

				// Update arrows if side arrows enabled
				if (self.options.hideSideArrows) { self.hideArrows(); }

				// Show arrows if hoverArrows is disabled
				if (!self.options.hoverArrows) {
					(self.$leftArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDuration * 3);
					(self.$rightArrow).css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, self.options.hideSideArrowsDuration * 3);
				}

				this.transition();
			}
		},

		getHeight: function (height) {
			var self = this,
				currentPanelHeight;
			// Cache the original height of the current panel
			currentPanelHeight = height || $($(self.panelContainer).children()[self.panelHeightCount]).css('height').split('px')[0];
			// Create a new height based on the user settings (Beta)
			self.setHeight = (self.options.autoHeightRatio) ?
					(((self.$sliderWrap).outerWidth(true) / (self.options.autoHeightRatio).split(':')[1] * (self.options.autoHeightRatio).split(':')[0])) :
					currentPanelHeight;
			// If the user settings indicate a height too high, use the smaller value
			self.setHeight = (self.setHeight < currentPanelHeight) ? self.setHeight : currentPanelHeight;

			self.setHeight = (self.setHeight < self.options.autoHeightMin) ? self.options.autoHeightMin : self.setHeight;
			if (!self.loaded) {
				// Only run once
				return self.removePreloader();

			}
			return self.setHeight;
		},

		getHeighestPanel: function () {
			var self = this,
				height = 0,
				heightCandidate;
			$(self.sliderId + ' .panel').each(function () {
				heightCandidate = $(this).height();
				height = (heightCandidate > height) ? heightCandidate : height;
			});
			return height;
		},

		adjustHeight: function (easing, duration, height) {
			var self = this;

			// Adjust the height
			if (self.options.autoHeight && self.loaded && (self.useCSS || self.dontAnimateHeight)) {
				// CSS transitions or IE
				$(self.panelContainer).parent().css({
					'height': self.getHeight(height) + 'px'
				});
			} else if (self.options.autoHeight && self.loaded) {
				// jQuery animations
				$(self.panelContainer).parent().animate({
					'height': self.getHeight(height) + 'px'
				}, {
					easing: easing || self.options.autoHeightEaseFunction,
					duration: duration || self.options.autoHeightEaseDuration,
					queue: false
				});
			}
		},

		transition: function () {
			var self = this;
			// Adjust the scroll distance
			if (self.options.topScrolling && !self.useCSS && (self.readyToScroll || self.options.topScrollingOnLoad)) { self.scrollToTheTop(); }

			// Adjust the height
			if (self.options.autoHeight) { self.adjustHeight(); }


			// Transition for fade option
			if (self.options.slideEaseFunction === 'fade') {
				if (self.loaded) {
					$($(self.panelContainer).children()[self.currentTab])
						.fadeTo(self.options.slideEaseDuration, 1.0)
						.siblings().css('display', 'none');
				}
			} else if (self.loaded || !self.useCSS) {
				// Adjust the margin for continuous sliding
				if (self.options.continuous) {
					self.marginLeft = -(self.currentTab * self.slideWidth) - self.slideWidth;
				} else {
					// Otherwise adjust as normal
					self.marginLeft = -(self.currentTab * self.slideWidth);
				}
				// Don't transition on load if the slider has the same margin. This messes up when the
				// user clicks before fully loaded
				if ((self.marginLeft + self.pSign) !== (self.panelContainer).css('margin-left') || (self.marginLeft !== -100)) {
					// Animate the slider
					if (self.useCSS && self.loaded) {
						(self.panelContainer).css({
							'-webkit-transform': 'translate3d(' + self.marginLeft + self.pSign + ', 0, 0)',
							'-moz-transform': 'translate3d(' + self.marginLeft + self.pSign + ', 0, 0)',
							'-ms-transform': 'translate3d(' + self.marginLeft + self.pSign + ', 0, 0)',
							'-o-transform': 'translate3d(' + self.marginLeft + self.pSign + ', 0, 0)',
							'transform': 'translate3d(' + self.marginLeft + self.pSign + ', 0, 0)'
						});
						// Timeout to replace callback function
						setTimeout(function () { self.clickable = true; }, self.options.slideEaseDuration + 50);
					} else {
						(self.panelContainer).animate({
							'margin-left': self.marginLeft + self.pSign
						}, {
							easing: self.options.slideEaseFunction,
							duration: self.options.slideEaseDuration,
							queue: false,
							complete: function () {
								self.continuousSlide();
							}
						});
					}
				}
			}
			if (self.options.responsive) {
				// Match the slider margin with the width of the slider (better height transitions)
				$(self.sliderId + '-wrapper').css('width', (self.$sliderId).outerWidth(true));
			}
		},

		scrollToTheTop: function () {
			var self = this,
				offset = (self.$sliderWrap).offset();
				//minusTabs = (($(self.sliderId + '-nav-ul').height() || 0) + self.options.topScrollingExtraPixels);
				// If the offset is larger than the panel height, scroll up the panel height
			if (offset > self.setHeight) {
				$('html, body').animate({'scrollTop': offset.top - self.options.topScrollingExtraPixels}, self.options.topScrollingDuration);
			} //else { $('html, body').animate({'scrollTop': self.setHeight}, self.options.topScrollingDuration); }
		},

		animationCallback: function (go) {
			var self = this;
			if ((!self.dontCallback || go) && self.clickable) {
				setTimeout(function () {self.options.callbackFunction.call(this); }, self.options.slideEaseDuration + 50);
			}
		},

		autoSlide: function () {
			var self = this;
			// Can't set the autoslide slower than the easing ;-)
			if (self.options.autoSlideInterval < self.options.slideEaseDuration) {
				self.options.autoSlideInterval = (self.options.slideEaseDuration > self.options.autoHeightEaseDuration) ? self.options.slideEaseDuration : self.options.autoHeightEaseDuration;
			}
			//self.clickable = false;
			self.autoslideTimeout = setTimeout(function () {
				// Slide left or right
				self.setCurrent(self.options.autoSliderDirection);
				self.autoSlide();

			}, self.options.autoSlideInterval);
			if (typeof self.options.callbackFunction === 'function' && self.loaded) { self.animationCallback(); }
		},

		checkAutoSlideStop: function () {
			var self = this;
			// If the slider has not stopped, check whether it should stop
			if (!self.autoSlideStopped) {
				if (self.options.autoSlideStopWhenClicked) {
					clearTimeout(self.autoslideTimeout);
					self.autoSlideStopped = true;
					if (self.options.autoSlideControls) {
						$('body').find('[data-liquidslider-ref*=' + (self.sliderId).split('#')[1] + '][name=stop]').html(self.options.autoSlideStartText);
						clearTimeout(self.autoslideTimeout);
					}
				} else {
					self.autoSlide(clearTimeout(self.autoslideTimeout));
					self.clickable = true;
				}
			}
		},

		continuousSlide: function () {
			var self = this;

			if (self.options.continuous) {
				// If on the last panel (the clone of panel 1), set the margin to the original.
				if (self.currentTab === self.panelCount - 2 || self.marginLeft === -((self.slideWidth * self.panelCount) - self.slideWidth)) {
					$(self.panelContainer).css('margin-left', -self.slideWidth + self.pSign);
					self.currentTab = 0;
				} else if (self.currentTab === -1 || self.marginLeft === 0) {
				// If on the first panel the clone of the last panel), set the margin to the original.
					$(self.panelContainer).css('margin-left', -(((self.slideWidth * self.panelCount) - (self.slideWidth * 2))) + self.pSign);
					self.currentTab = (self.panelCount - 3);
				}
				self.clickable = true;
			} else { self.clickable = true; }
		}
	};

	$.fn.liquidSlider = function (options) {
		return this.each(function () {

			var slider = Object.create(Slider);
			slider.init(options, this);

			$.data(this, 'liquidSlider', slider);
		});
	};

	$.fn.liquidSlider.options = {
		autoHeight: true,
		autoHeightMin: 0,
		autoHeightEaseDuration: 1500,
		autoHeightEaseFunction: "easeInOutExpo",
		autoHeightRatio: null, // still in development

		slideEaseDuration: 1500,
		slideEaseFunction: "easeInOutExpo",
		callbackFunction: null,

		autoSlide: false,
		autoSliderDirection: 'right',
		autoSlideInterval: 7000,
		autoSlideControls: false,
		autoSlideStartText: 'Start',
		autoSlideStopText: 'Stop',
		autoSlideStopWhenClicked: true,
		autoSlidePauseOnHover: true,

		continuous: true,

		dynamicArrows: true,
		dynamicArrowsGraphical: true,
		dynamicArrowLeftText: "&#171; left",
		dynamicArrowRightText: "right &#187;",
		hideSideArrows: false,
		hideSideArrowsDuration: 750,
		hoverArrows: true,
		hoverArrowDuration: 250,


		dynamicTabs: true,
		dynamicTabsAlign: "left",
		dynamicTabsPosition: "top",
		firstPanelToLoad: 1,
		panelTitleSelector: "h2.title",
		navElementTag: "div",
		crossLinks: false,

		hashLinking: false,
		hashNames: true,
		hashCrossLinks: false,
		hashTitleSelector: "h2.title",
		hashTagSeparator: '', // suggestion '/'
		hashTLD: '',

		keyboardNavigation: false,
		leftKey: 39,
		rightKey: 37,
		panelKeys: {
			1: 49,
			2: 50,
			3: 51,
			4: 52
		},

		responsive: true,
		mobileNavigation: true,
		mobileNavDefaultText: 'Menu',
		mobileUIThreshold: 0,
		hideArrowsWhenMobile: true,
		useCSSMaxWidth: 1030,

		preloader: true,
		preloaderFadeOutDuration: 250,
		preloaderElements: 'img,video,iframe,object',

		topScrolling: false,
		topScrollingDuration: 1500,
		topScrollingOnLoad: false,
		topScrollingExtraPixels: 0

		//setIeToFade:true (disabled for now)
		//topScrollingOnAutoPlay: null  (potential feature)

		//swipe: true,
		//swipeThreshold: 100
	};

})(jQuery, window, document);