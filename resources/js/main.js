$( document ).ready(function() {
    $('.postDelete').each(function () {
        $(this).on('click',function () {
            if(confirm('Are you sure you want to delete the post?')){
                $('#delete-post-'+this.getAttribute('data-delete-id')).submit();
            }
        });
    });
    $('.gameDelete').each(function () {
        $(this).on('click',function () {
            if(confirm('Are you sure you want to delete the game?')){
                $('#delete-game-'+this.getAttribute('data-delete-id')).submit();
            }
        });
    });

    $('#logout').on('click',function () {
        if(confirm('Are you sure you want to logout?')){
            $('#logout-form').submit();
        }
    });

    $("button[data-id='changeProfileInfo']").click(function(){
        $('#changeProfileInfo').modal('hide');
    });

    if($('#editor').length){
        $('#editor').summernote({
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        // link modal hide
        $('div[aria-label="Insert Link"]').find('.close').eq(0).removeAttr('data-dismiss').attr('id','link-modal-close');
        $("#link-modal-close").click(function(){
            $('div[aria-label="Insert Link"]').modal('hide');
        });
    }
});

