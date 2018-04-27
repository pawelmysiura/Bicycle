$(document).ready(function(){

    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function(){
        var onStar = parseInt($(this).data('value')); // The star currently mouse on

        // Now highlight all the stars that's not after the current hovered star
        $(this).parent().children('li.star').each(function(e){
            if (e < onStar) {
                $(this).addClass('starHover');
            }
            else {
                $(this).removeClass('starHover');
            }
        });

    }).on('mouseout', function(){
        $(this).parent().children('li.star').each(function(e){
            $(this).removeClass('starHover');
        });
    });


    /* 2. Action to perform on click */
    $('#stars li').on('click', function() {
        var onStar = parseInt($(this).data('value')); // The star currently selected
        var stars = $(this).parent().children('li.star');

        for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('starSelected');
        }

        for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('starSelected');
        }

        var rating = $.parseJSON(onStar);
        console.log(rating);
        $.ajax({
            type: 'POST',
            url: "{{ path('panel_map_rating') }}",
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(rating),
            dataType: 'json',
            success: function(response) {
                console.log(response);
            }
        });
    })

});