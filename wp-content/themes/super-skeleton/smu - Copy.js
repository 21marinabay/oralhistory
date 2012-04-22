

jQuery(document).ready(function(){
	
	// var jwShortcode = jQuery("#jwplayer-1_wrapper").html();
	 
	//alert(jwShortcode);
	 
	 
	 
	 
	 
     //var item1 = jwplayer().getPlaylistItem().file ; 
	 
	 //alert("TEST1");
	 
//	 onclick='alert(jwplayer().getPosition())';#jwplayer-1-div
	

	
	
	

	  jwplayer().onPlaylistItem(function(){ 
	  
		//jwplayer().pause();
	//	jQuery(this).delay(5000);
		//jwplayer().play();
		
		
	   // jwplayer().pause(100);
	 // jQuery("#jwplayer-1-div").click(function() {
	  //jwplayer().onPlaylistItem(function(){
		// alert("OnPlayList");
		    var item2 =  jwplayer().getPlaylistItem().file ; 
			//var captions = $(this).attr('captions.file');

			
		  var name = "amol";
		//  alert(name+item2);
		  //alert(name+captions);
		  
		   
		   item2 = item2.replace(".mp3", ".srt");
		  // item2 = item2.replace(" ", "%20");
		   
		   
		   
	  //  	alert("THIS IS BEFORE AJAX"+item2);
	    	
	    	 jQuery.ajax({
	              type       : "GET",
	              data       : {pageNumber: item2},
	              dataType   : "html",
	                url        : "/wp-content/uploads/fileread.php",
	              beforeSend : function(){
	                },
	                success    : function(data){
	                	
	                	var newcontent = data ;
	                    var emptycontent ="" ;
	              // alert("TRANSCRIPT"+data);
	          		
						jQuery(".transcript").html(newcontent);
	                	
	                	
	                	//alert("DONE"+data);
	                	
	                	
	                },
	                error     : function(jqXHR, textStatus, errorThrown) {
	                    alert(jqXHR + " ::NNNN " + textStatus + " :: " + errorThrown);
	                }
		
		   });
		   
		   
  
	  
	});
	

		jQuery("a.seekclick").live("click", function(event){
	
	event.preventDefault();
	var seekvalue = jQuery(this).attr("id");
	
	jwplayer().seek(seekvalue);
	jwplayer().pause();
	jwplayer().play();
	
	
	
});
	 
     jwplayer().onComplete(function(){ 
	 
	 
	//alert('OnComplete'); 
	 
	// var itemNumber = jwplayer().getPlaylistItem();
	 //alert(itemNumber);
	 
	
	  jQuery().delay(500);
	  
	  jwplayer().playlistNext()  ; 
	  
	  var item3 = jwplayer().getPlaylistItem().file ; 
			

			
		  var name = "NEXT";
		// alert(item3);
		//  alert(name+item3);
		  
		   
		   item3 = item3.replace(".mp3", ".srt");
		  // item2 = item2.replace(" ", "%20");
		   
		   
		   
	    //	alert("NEXT - FOR AJAX CALL"+item3);
	    	
	    	 jQuery.ajax({
	              type       : "GET",
	              data       : {pageNumber: item3},
	              dataType   : "html",
	                url        : "/wp-content/uploads/fileread.php",
	              beforeSend : function(){
	                },
	                success    : function(data1){
	                	
	                	var newcontent1 = data1 ;
	                    var emptycontent = "";
	            //   alert("TRANSCRIPT"+data1);
	                
	               jQuery(".transcript").html(newcontent1);
				
	                	
	                	//alert("DONE"+data);
	                	
	                	
	                },
	                error     : function(jqXHR, textStatus, errorThrown) {
	                    alert(jqXHR + " ::NNNN " + textStatus + " :: " + errorThrown);
	                }
		
	
		  
		
		   });
		   
	 
	  
	 jwplayer().playlistPrev()  ; 
	 
	
	 
	 })

   


	
	
	 		
});
