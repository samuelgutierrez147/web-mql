(function ($) {
    "use strict";
	qodefCore.shortcodes.etchy_core_outline_text = {};
	qodefCore.shortcodes.etchy_core_outline_text.qodefAppear = qodef.qodefAppear;
	
	$(document).ready(function () {
		qodefOutlineText.init();
	});
	
	var qodefOutlineText = {
		init: function () {
			this.holder = $('.qodef-outline-text');
			
			if (this.holder.length) {
				this.holder.each(function () {
					var $thisHolder = $(this),
						svgDots = '<svg class="qodef-svg-dots" xmlns="http://www.w3.org/2000/svg" width="298.479" height="218.638">' +
							'<g class="qodef-svg-dots-outer" fill="#EA5D3D" fill-rule="evenodd" clip-rule="evenodd">' +
							'<g class="qodef-svg-dots-inner-one">' +
							'<path class="qodef-svg-dots-front" d="M36.507 72.837c.805.082 1.613.16 2.418.241.881.411.984.645 1.448 1.437v.718c-1.02 1.262-2.741 3.539-5.317 2.397-3.168-1.412.03-4.047 1.451-4.793z"/>' +
							'<path class="qodef-svg-dots-back" d="M173.587 98.716c2.728.005 4.803 1.122 5.805 2.876-1.182 2.309-8.701 5.731-8.947.478 1.016-1.503 1.78-2.236 3.142-3.354z"/>' +
							'<path class="qodef-svg-dots-front" d="M44 180.656l3.146.48c2.052 2.568.718 5.022-3.39 4.794-.979-.473-1.449-.686-1.931-1.68.082-.801.161-1.596.241-2.396.647-.398 1.291-.795 1.934-1.198z"/>' +
							'<path class="qodef-svg-dots-back" d="M89.696 100.633c1.888.264 2.966.932 3.867 2.154l-.24.959c-.717 1.534-2.44 2.524-4.593 2.635-.567-.398-1.131-.798-1.693-1.196.014-2.139.63-2.842 1.45-4.073.403-.162.804-.322 1.209-.479z"/>' +
							'<path class="qodef-svg-dots-front" d="M141.917 134.896c1.878.229 2.797.829 2.901 2.871-.937 1.86-2.104 2.563-4.109 3.358-.932-.698-1.514-1.201-2.178-2.159.586-2.381 1.45-3.025 3.386-4.07z"/>' +
							'<path class="qodef-svg-dots-back" d="M113.631 139.446c2.029.051 2.9.661 4.108 1.438v.24c-.958 1.576-2.948 4.359-5.801 3.116-.909-.46-.999-.805-1.451-1.68.442-2.162 1.451-2.24 3.144-3.114z"/>' +
							'<path class="qodef-svg-dots-front" d="M125.237 180.656c2.081.528 3.671.988 4.831 3.114-.159.322-.317.64-.481.961-2.583 1.362-5.963 1.667-7.494-.961.79-1.803 1.574-2.117 3.144-3.114z"/>' +
							'<path class="qodef-svg-dots-front" d="M172.619 166.762c3.119-.14 4.194.583 5.082 2.635-.247.48-.485.957-.732 1.438-1.805.324-3.111.446-4.835 0-.237-.398-.474-.801-.721-1.198.163-.56.318-1.117.483-1.676.238-.403.484-.801.723-1.199z"/>' +
							'<path class="qodef-svg-dots-front" d="M292.536 133.696c2.84-.069 3.974.641 5.565 1.679.255.857.559 1.292.239 2.155-.979 2.057-3.669 2.322-6.041 1.678-1.036-.719-1.446-1.379-2.177-2.396.353-1.15.633-1.767 1.443-2.878.332-.078.651-.155.971-.238z"/>' +
							'<path class="qodef-svg-dots-front" d="M246.361 102.55c3.061-.106 4.292.779 5.317 2.635v.48c-1.017 2.185-2.863 3.798-6.041 3.112-.402-.479-.812-.958-1.214-1.437-.354-1.216-.058-2.267.246-3.355.566-.478 1.125-.955 1.692-1.435z"/>' +
							'<path class="qodef-svg-dots-back" d="M264.732 72.837c3.062-.117 4.408.48 5.804 1.919-.082.717-.165 1.435-.236 2.156-3.071 1.871-9.056 2.679-7.497-2.878.641-.398 1.29-.798 1.929-1.197z"/>' +
							'<path class="qodef-svg-dots-front" d="M284.804 41.69c2.511-.058 3.899.496 5.319 1.438v.24c-.065 1.983-.617 2.049-1.453 3.113-1.511.426-3.226 1.692-4.835 1.199-1.224-.225-1.019-.329-1.692-.958-.08-3.229.945-3.697 2.661-5.032z"/>' +
							'<path class="qodef-svg-dots-back" d="M220.731 44.805c1.569.281 1.815.781 2.66 1.676-.082.641-.156 1.279-.237 1.92-1.199.927-3.251 2.681-5.564 1.675-.322-.161-.643-.319-.971-.479.082-.558.166-1.118.246-1.676.796-1.973 1.987-2.215 3.866-3.116z"/>' +
							'</g>' +
							'<g class="qodef-svg-dots-inner-two">' +
							'<path class="qodef-svg-dots-front" d="M172.865 211.566c1.641.165 2.734.496 3.384 1.675.499.828.468 1.585.246 2.64-.486.477-.97.957-1.453 1.438-3.382.512-5.739-.667-4.835-4.314.483-.321.968-.641 1.453-.961.394-.157.803-.317 1.205-.478z"/>' +
							'<path class="qodef-svg-dots-front" d="M185.196 32.346c3.142-.035 4.055 1.106 5.318 2.875-.162.32-.327.638-.484.959-.779 1.604-4.646 3.341-7.257 2.156-.819-.387-.805-.39-1.207-1.197-.08-.639-.164-1.279-.237-1.917.977-1.916 1.946-1.904 3.867-2.876z"/>' +
							'<path class="qodef-svg-dots-back" d="M146.271 0c1.769.067 2.383.493 3.383 1.199v.24c.096 3.185-1.546 5.212-4.591 5.27-.484-.319-.97-.637-1.452-.958-1.089-3.371.092-4.573 2.66-5.751z"/>' +
							'<path class="qodef-svg-dots-front" d="M143.367 66.129c2.248-.083 3.363.209 4.596.958l.24 2.397c-2.164 3.738-5.619 4.198-7.252-.239.677-1.438 1.287-2.159 2.416-3.116z"/> ' +
							'<path class="qodef-svg-dots-back" d="M86.069 35.701c2.219.084 3.095.749 4.351 1.677v1.197c-1.182 1.953-3.219 3.515-6.283 2.874l-1.453-1.197c-.081-.719-.16-1.439-.239-2.154.996-1.471 1.934-1.578 3.624-2.397z"/>' +
							'<path class="qodef-svg-dots-back" d="M36.988 15.335c2.283.02 3.647.82 4.353 2.394v1.439c-.981 2.257-3.891 2.683-6.527 1.917l-.966-.959c.08-.798.161-1.599.243-2.397.67-1.44 1.59-1.61 2.897-2.394z"/> ' +
							'<path class="qodef-svg-dots-front" d="M132.487 213.241c2.841-.104 4.071.373 5.563 1.44.08.239.158.48.24.719-.16.559-.321 1.117-.484 1.675-1.352.586-3.151 1.982-4.834 1.439-1.39-.151-1.886-.455-2.418-1.439-1.694-2.023.633-3.164 1.933-3.834z"/>' +
							'<path class="qodef-svg-dots-back" d="M2.659 136.573c2.622.468 3.792 1.122 5.078 2.873v.237c-1.212 1.644-2.421 2.397-5.318 2.397-1.496-.729-1.797-1.271-2.419-2.873.403-.719.805-1.441 1.208-2.158.484-.158.968-.315 1.451-.476z"/>' +
							'</g>' +
							'</g>' +
							'</svg>';
					
					$thisHolder.find('.qodef-custom-styles').append(svgDots);
					qodefOutlineText.animateDots($('#qodef-page-wrapper'));
					
				});
			}
		},
		
		animateDots : function ($holder){
			
			$holder.mousemove(function(event) {
				
				var tMember = $(this);
				var mouseX = event.pageX,
					mouseY = event.pageY;
				
				function qodefFoliageMove() {
					var items = tMember;
					var $currentItem;
					
					for (var i = 0, j = items.length; i < j; i++) {
						$currentItem = $(items[i]);
						
						var tFront = $currentItem.find('.qodef-svg-dots-back');
						var tBack = $currentItem.find('.qodef-svg-dots-front');
						
						/*var x = mouseX/50;
						var y = (mouseY - $currentItem.offset().top)/25;*/
						
						var x = mouseX/500;
						var y = (mouseY - $currentItem.offset().top)/250;
						
						/*var transformation = 'translate3D(' + x*.75 + 'px, '+ y * 1.75 + 'px, 0px)';
						var transformationBack = 'translate3D(' + -x*.35 + 'px, '+ -y*1.5 + 'px, 0px)';*/
						var transformation = 'translate3D(' + x*7.7 + 'px, '+ y * 1.75 + 'px, 0px)';
						var transformationBack = 'translate3D(' + -x*5.3 + 'px, '+ -y*1.5 + 'px, 0px)';
						
						tFront.css({
							'transform': transformation
						});
						tBack.css({
							'transform': transformationBack
						});
					}
				}
				
				qodefFoliageMove();
				
			});
		}
	};
	
	qodefCore.shortcodes.etchy_core_outline_text.qodefOutlineTextn = qodefOutlineText;
})(jQuery);