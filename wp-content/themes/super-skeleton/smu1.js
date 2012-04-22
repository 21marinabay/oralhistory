






jQuery(document).ready(function(){
	  
	jwplayer().onPlaylistItem(function(){ 
		var item2 =  jwplayer().getPlaylistItem().file ;  
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
				item2 = item2.replace("mp4", "srt");
		//		alert("Mp4 COnverted");
			}
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
	});
	
	jQuery("a.seekclick").live("click", function(event){
	event.preventDefault();
	var seekvalue = jQuery(this).attr("id");
	jwplayer().seek(seekvalue);
	jwplayer().pause();
	jwplayer().play();
	
});
	 
    jwplayer().onComplete(function(){ 
	  jQuery().delay(500);
	  jwplayer().playlistNext()  ; 
	  var item3 = jwplayer().getPlaylistItem().file ; 
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
	 jwplayer().playlistPrev()  ; 
	})
 		
});