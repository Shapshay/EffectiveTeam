<script type="text/javascript">
    var word_list = [
        {WORDS_CLOUD}
    ];
    $(function() {
        $("#words_cloud").jQCloud(word_list,
                {
                    width: 600,
                    height: 400
                });
    });
</script>
<script>
function checkSearchForm(){
    var send = true;
    var word = $('#word').val();
    if(word==''){
        swal("Ошибка заполнения!", "Заполните поле поиска!", "error");
        send = false;
    }
    if(send){
        $('#waitGear').show();
        $('#SearchForm').submit();
    }
}
function CloudSearch(word){
    var send = true;
    if(word==''){
        swal("Ошибка заполнения!", "Заполните поле поиска!", "error");
        send = false;
    }
    if(send){
        $('#waitGear').show();
        $('#word').val(word);
        $('#SearchForm').submit();
    }
}
</script>
