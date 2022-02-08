(function( $ ) {
	$( window ).on( 'load', function() {
		var popup_enable = custom_params.popup_enable;
		if(popup_enable == 'yes') {
	  		var cs_country = getCookie('cs_country');
	  		var cs_country_url = getCookie('cs_country_url');

	  		if(!cs_country) {
				displayCSPopup();
	  		} else if(cs_country_url) {
	  			displayCSPopup();
				$('.cs-loader').show();
				window.location.href = cs_country_url;
			}			
		}
      	$(".cs_country").chosen({disable_search_threshold: 10, width: "100%"});
      	$('#country-selector-popup').submit(function(e) {
      		e.preventDefault();
      		$('.cs-loader').show();
      		$('.cs-country-wrapper .cs-error').remove();
      		var redirect_country = $('select[name="cs_country"] option:selected').val();
      		var redirect_url = $('select[name="cs_country"] option:selected').data('redirect-url');

      		if(redirect_country == "") {
      			$('.cs-country-wrapper').append('<span class="cs-error">Country is required</span>');
      			$('.cs-loader').show();
      			return;
      		}
      		setCookie('cs_country', redirect_country, 1);
      		setCookie('cs_country_url', redirect_url, 1);
      		if(redirect_url) {
      			window.location.href = redirect_url;
      		} else {
      			var magnificPopup = $.magnificPopup.instance;
      			magnificPopup.close();
      		}
      		$('.cs-loader').show();
      	});

      	function displayCSPopup() {
			$.magnificPopup.open({
			    items: {
			        src: '#country-selector-popup' 
			    },
			    type: 'inline',
			    modal: true,
			    callbacks: {
	                beforeClose: function () {
	                    return false;
	                },
	                close: function () {
	                    return false;
	                },
	            }
	      	});
      	}
      	function setCookie(name,value,days) {
      	    var expires = "";
      	    if (days) {
      	        var date = new Date();
      	        date.setTime(date.getTime() + (days*24*60*60*1000));
      	        expires = "; expires=" + date.toUTCString();
      	    }
      	    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
      	}
      	function getCookie(name) {
      	    var nameEQ = name + "=";
      	    var ca = document.cookie.split(';');
      	    for(var i=0;i < ca.length;i++) {
      	        var c = ca[i];
      	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
      	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      	    }
      	    return null;
      	}
      	function eraseCookie(name) {   
      	    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      	}
	});
})( jQuery );
