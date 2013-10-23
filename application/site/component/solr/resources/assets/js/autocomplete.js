$(document).ready(function() {
    $('.js-search').autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "http://nooku.dev/search.json",
                dataType: "jsonp",
                data: {
                    search: request.term
                },
                success: function( data ) {
                    response( $.map( data.items, function( item ) {
                        return {
                            label: item.data.title,
                            value: item.data.title
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            $('.js-search').val(ui.item.label);
            $('.form-search').submit();
        }
    });
});