(function($) {
	if ( typeof MrDevClassic === 'undefined' )
		return;

	if ( MrDevClassic.widgets.length < 1 )
		return;

	tinymce.PluginManager.add( 'MrDev', function( editor, url ) {
		var items = [];
		$.each( MrDevClassic.widgets, function( i, v ) {
			var item = {
				'text' : v.label,
				'body': {
					'type': v.label
				},
				'onclick' : function(){
					editor.insertContent( '[mrdev id="' + v.value + '"]' );
				}
			};
			items.push( item );
		} );

		editor.addButton( 'MrDev', {
			title: MrDevClassic.title,
			type : 'menubutton',
			image : MrDevClassic.image,
			menu : items
		});
	});
})(jQuery);