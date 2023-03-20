jQuery( document ).ready(function($) {

	if($('.woocommerce-checkout').length) {
		jQuery(function($){
			$("#order_review").on("submit", function(s){
		        $("#place_order").attr("disabled", "disabled");
		        s.submit();
		    });
		});

		jQuery(function($){
		    $("#qpaypro_ccnum").on("keydown", function(s){  
				if( document.getElementById("qpaypro_ccnum").value.indexOf("4") == 0){
					document.getElementById("qpaypro_visaencuotas").style.visibility = "visible";
					document.getElementById("name_visa_cuotas").style.visibility = "visible";
				}else{
					document.getElementById("qpaypro_visaencuotas").style.visibility = "hidden";
					document.getElementById("name_visa_cuotas").style.visibility = "hidden";
				}});
			$("#qpaypro_ccnum").on("keyup", function(s){
				if( document.getElementById("qpaypro_ccnum").value.indexOf("4") == 0){
					document.getElementById("qpaypro_visaencuotas").style.visibility = "visible";
					document.getElementById("name_visa_cuotas").style.visibility = "visible";
				}else{
					document.getElementById("qpaypro_visaencuotas").style.visibility = "hidden";
					document.getElementById("name_visa_cuotas").style.visibility = "hidden";
				}});
		});

	}



});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImN1c3RvbS5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJhcHAubWluLmpzIiwic291cmNlc0NvbnRlbnQiOlsialF1ZXJ5KCBkb2N1bWVudCApLnJlYWR5KGZ1bmN0aW9uKCQpIHtcclxuXHJcblx0aWYoJCgnLndvb2NvbW1lcmNlLWNoZWNrb3V0JykubGVuZ3RoKSB7XHJcblx0XHRqUXVlcnkoZnVuY3Rpb24oJCl7XHJcblx0XHRcdCQoXCIjb3JkZXJfcmV2aWV3XCIpLm9uKFwic3VibWl0XCIsIGZ1bmN0aW9uKHMpe1xyXG5cdFx0ICAgICAgICAkKFwiI3BsYWNlX29yZGVyXCIpLmF0dHIoXCJkaXNhYmxlZFwiLCBcImRpc2FibGVkXCIpO1xyXG5cdFx0ICAgICAgICBzLnN1Ym1pdCgpO1xyXG5cdFx0ICAgIH0pO1xyXG5cdFx0fSk7XHJcblxyXG5cdFx0alF1ZXJ5KGZ1bmN0aW9uKCQpe1xyXG5cdFx0ICAgICQoXCIjcXBheXByb19jY251bVwiKS5vbihcImtleWRvd25cIiwgZnVuY3Rpb24ocyl7ICBcclxuXHRcdFx0XHRpZiggZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJxcGF5cHJvX2NjbnVtXCIpLnZhbHVlLmluZGV4T2YoXCI0XCIpID09IDApe1xyXG5cdFx0XHRcdFx0ZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJxcGF5cHJvX3Zpc2FlbmN1b3Rhc1wiKS5zdHlsZS52aXNpYmlsaXR5ID0gXCJ2aXNpYmxlXCI7XHJcblx0XHRcdFx0XHRkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcIm5hbWVfdmlzYV9jdW90YXNcIikuc3R5bGUudmlzaWJpbGl0eSA9IFwidmlzaWJsZVwiO1xyXG5cdFx0XHRcdH1lbHNle1xyXG5cdFx0XHRcdFx0ZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJxcGF5cHJvX3Zpc2FlbmN1b3Rhc1wiKS5zdHlsZS52aXNpYmlsaXR5ID0gXCJoaWRkZW5cIjtcclxuXHRcdFx0XHRcdGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwibmFtZV92aXNhX2N1b3Rhc1wiKS5zdHlsZS52aXNpYmlsaXR5ID0gXCJoaWRkZW5cIjtcclxuXHRcdFx0XHR9fSk7XHJcblx0XHRcdCQoXCIjcXBheXByb19jY251bVwiKS5vbihcImtleXVwXCIsIGZ1bmN0aW9uKHMpe1xyXG5cdFx0XHRcdGlmKCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInFwYXlwcm9fY2NudW1cIikudmFsdWUuaW5kZXhPZihcIjRcIikgPT0gMCl7XHJcblx0XHRcdFx0XHRkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInFwYXlwcm9fdmlzYWVuY3VvdGFzXCIpLnN0eWxlLnZpc2liaWxpdHkgPSBcInZpc2libGVcIjtcclxuXHRcdFx0XHRcdGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwibmFtZV92aXNhX2N1b3Rhc1wiKS5zdHlsZS52aXNpYmlsaXR5ID0gXCJ2aXNpYmxlXCI7XHJcblx0XHRcdFx0fWVsc2V7XHJcblx0XHRcdFx0XHRkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInFwYXlwcm9fdmlzYWVuY3VvdGFzXCIpLnN0eWxlLnZpc2liaWxpdHkgPSBcImhpZGRlblwiO1xyXG5cdFx0XHRcdFx0ZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJuYW1lX3Zpc2FfY3VvdGFzXCIpLnN0eWxlLnZpc2liaWxpdHkgPSBcImhpZGRlblwiO1xyXG5cdFx0XHRcdH19KTtcclxuXHRcdH0pO1xyXG5cclxuXHR9XHJcblxyXG5cclxuXHJcbn0pOyJdfQ==