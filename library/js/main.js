$(document).ready(function() {
	$(".myChart").each(function( index ) {
	  //console.log( index + ": " + $( this ).text() );
		//Get context with jQuery - using jQuery's .get() method.
		
		var ctx = $(".myChart").get(index).getContext("2d");
		//This will get the first returned node in the jQuery collection.
		var myNewChart = new Chart(ctx);
		
		var data = [
			{
				value: $(this).data("used"),
				color:"#c92525"
			},
			{
				value : $(this).data("free"),
				color : "#8cc951"
			}
		
		]
		var options =
			{
				segmentStrokeColor : "#1e1e1e",
				segmentStrokeWidth : 2,
			}
		
		new Chart(ctx).Doughnut(data,options);
	});

	$("#content").on("submit", ".ajaxupdate", function(e) { 
		var url = $(this).attr('action');
		var postdata = $(this).serialize();
		alert( postdata );
		$.post( url, postdata, function( data ) {
			$( ".result" ).html( data );
		});
		e.preventDefault();
	})
	
	
	$('#status').on('mouseenter', '.status_button', function() {
		$(this).parent().addClass("active");
	}).on('mouseleave', '.status_button', function() {
		$(this).parent().removeClass("active");
	});
	
	$('#status').on('click', '.status_button', function() {
		$(this).parent().addClass("active");
	}).on('click', '.status_button', function() {
		$(this).parent().removeClass("active");
	});
	
	var piesize = ( $('#status').hasClass('mini') ) ? 120 : 250;
	var barwidth = ( $('#status').hasClass('mini') ) ? 3 : 5;

	$('#status').on('click', 'canvas', function() {
		$(".status_container", $(this).parent()).addClass("active");
	});
	$('#status_capacity').easyPieChart({
    	barColor: function (percent) {
	       return (percent < 75 ? '#A4BB44' : percent < 90 ? '#f0ad4e' : '#cb3935');
	    },
	    trackColor: false,
	    size: piesize,
	    scaleColor: false,
	    trackWidth: barwidth,
	    lineWidth: barwidth,

    });

	$('#status_array').easyPieChart({
    	barColor: function (percent) {
	       return ($('#status_array').hasClass('error') ? '#cb3935' : $('#status_array').hasClass('warning') ? '#f0ad4e' : '#A4BB44');
	    },
	    trackColor: false,
	    size: piesize,
	    scaleColor: false,
	    trackWidth: barwidth,
	    lineWidth: barwidth,

    });

	$('#status_disks').easyPieChart({
    	barColor: function (percent) {
	       return ($('#status_disks').hasClass('error') ? '#cb3935' : $('#status_disks').hasClass('warning') ? '#f0ad4e' : '#A4BB44');
	    },
	    trackColor: false,
	    size: piesize,
	    scaleColor: false,
	    trackWidth: barwidth,
	    lineWidth: barwidth,

    });

    status_array


    $(window).on("scroll", function() {
        var fromTop = $("body").scrollTop();
        $('#sticky-main-nav').toggleClass("show", (fromTop > 200));
    });
	
	
	//$("div.tab_container").css("height", $(this).find("div.showscale").outerHeight()+"px");
	//$( ".tabs" ).tabs();
	$( ".tabs li a" ).on("click", function() { 
		$( ".tabs li" ).removeClass("active");
		$( this ).parent().addClass("active");
		var ind = $(".tabs li a").index(this);
		
		var allbox = $( ".tabs .addontab" );
		var box = $( ".tabs .addontab" ).eq(ind);
		allbox.removeClass("active");
		box.addClass("active"); 
		/*var current = $( ".tabs .addontab .showscale" );
		var newheight = box.outerHeight();
		current.removeClass("showscale");
		current.one('transitionend', function(e) {  
			box.closest("div.tab_container").css("height", newheight+"px");
			box.addClass("showscale"); 
		}); */ 
		if($(this).data("follow") == true) return true;
		else return false;
	});
	
});