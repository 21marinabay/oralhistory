
jQuery(document).ready(function(){


	jwplayer().onPlaylistItem(function(){ 
	
		
	
		
		var item2 =  jwplayer().getPlaylistItem().file ;
		desc =  jwplayer().getPlaylistItem().description ;  
		
		desc = "<p><h5>"+desc+"</h5></p>" ;
		jQuery(".interact").html(desc);
		//alert("onplay"+item2);
		
		function getFileExtension(filename)
			{
				var ext = /^.+\.([^.]+)$/.exec(filename);
				return ext == null ? "" : ext[1];
			}
		var extension = getFileExtension(item2);   
		 // alert("THIS IS FILE EXTENSION "+extension);
			if(extension=="mp3"){
				item2 = item2.replace("mp3", "srt");
		//		alert("Mp3 Converted");
			}else{
				item2 = item2.replace("mp4", "srt");
		//		alert("Mp4 COnverted");
			}
			
			//version to get the srt file
			
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
						jQuery(".transcript").html(newcontent);
	               },
	                error     : function(jqXHR, textStatus, errorThrown) {
	                    alert(jqXHR + " ::NNNN " + textStatus + " :: " + errorThrown);
	                }	
		   });
		   
		   //get the bio file here
		   var fullname = "" ;
		  // alert(item2);
		   var length = item2.length ; 
		   var authorname = item2.substr((length-7),3);
		   authorname = authorname.replace("_","");
		   authorname = jQuery.trim(authorname);
		  // alert(authorname);
		   
		   var ext = ".txt" ;
		   var authorFile = authorname+ext ;
		 //  alert("Before Author Ajax "+authorFile);
		   
		   
		    jQuery.ajax({
	              type       : "GET",
	              data       : {authorBioFile: authorFile},
	              dataType   : "html",
	                url        : "/wp-content/uploads/author.php",
	              beforeSend : function(){
	                },
	                success    : function(data){
	                	
	                	var authorcontent = data ;
						
	                    var emptycontent ="" ;
						jQuery(".biofull").html(authorcontent);
	               },
	                error     : function(jqXHR, textStatus, errorThrown) {
	                    alert(jqXHR + " :: AAAA" + textStatus + " :: " + errorThrown);
	                }	
		   });
		   // this needs to be copied
		   
		   
	});
	
	jQuery("a.seekclick").live("click", function(event){
	event.preventDefault();
	var seekvalue = jQuery(this).attr("id");
	//alert(seekvalue);
	jwplayer().seek(seekvalue);
	jwplayer().pause();
	jwplayer().play();
	
});
	 
    jwplayer().onComplete(function(){ 
	
		jQuery.delay(500);
	  
	//  jwplayer().playlistNext()  ; 
	  var item3 = jwplayer().getPlaylistItem().file ; 
	   var desc1 = jwplayer().getPlaylistItem().description ; 
	  desc1 = "<p><h5>"+desc1+"</h5></p>" ;
		jQuery(".interact").html(desc1);
	//	alert(item3);
		
		  	 function getFileExtension(filename)
			{
				var ext = /^.+\.([^.]+)$/.exec(filename);
				return ext == null ? "" : ext[1];
			}
	 
		var extension = getFileExtension(item2);   
			if(extension=="mp3"){
				item2 = item2.replace("mp3", "srt");
			}else{
				item2 = item2.replace("mp4", "srt");
			}
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
	               jQuery(".transcript").html(newcontent1);
	                },
	                error     : function(jqXHR, textStatus, errorThrown) {
	                    alert(jqXHR + " ::NNNN " + textStatus + " :: " + errorThrown);
	                }
					
		   });
		
			//jwplayer().playlistPrev()  ; 


		   
	 
	})
 		
});