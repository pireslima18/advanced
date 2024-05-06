$(document).ready(function(){
    $('.modelCreateButton').click(function ($e){
        $e.preventDefault();
        $.get($(this).attr('href'), function(data) {
            $('#modalCreate').modal('show').find('#modalCreateContent').html(data)
        });
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4>' + $(this).attr('title') + '</h4>';
        return false;
    });

    // $('.modelEditButton').click(function ($e){
    //     $e.preventDefault();
    //     $.get($(this).attr('href'), function(data) {
    //         $('#modalCreate').modal('show').find('#modalCreateContent').html(data)
    //     });
    //     document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4>' + $(this).attr('title') + '</h4>';
    //     return false;
    // });
});