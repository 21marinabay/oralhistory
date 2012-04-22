$(document).ready(function() {
    // Initialise the table
    $("#table-1").tableDnD({
	   onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var newsortorder = '';
            for (var i=0; i<rows.length; i++) {
                newsortorder += ((newsortorder=='')?'':',')+rows[i].id;
            }
	        $('#sortinput').val(newsortorder);
	    }
	});
});