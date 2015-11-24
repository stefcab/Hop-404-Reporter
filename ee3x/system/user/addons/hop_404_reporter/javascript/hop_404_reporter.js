window["Hop404Reporter_cp"]  =
{
	table:null,
	data:null,
	html_data:null,
	
	setup_tables: function() {
		this.table = $('.mainTable');
		this.data = this.table.table('get_current_data').rows;
		this.html_rows = this.table.find('tbody tr');
		this.table_events();
		this.ajax_filter();
	},

	table_events: function() {
		var that = this,
			indicator = $('.searchIndicator');

		this.table.bind('tableupdate', function(evt, res) {
			that.html_rows = $(res.data.html_rows);
			that.data = res.data.rows;
		}).bind('tableload', function() {
			indicator.css('visibility', '');
		})
		.bind('tableupdate', function() {
			indicator.css('visibility', 'hidden');
		});
		
	},

	ajax_filter: function() {
		this.table.table('add_filter', $('#url_filter'));
	},
}
$(function() {
	
});