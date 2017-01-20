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
</script>
