<div class="container-fluid">
    <form action="" id="update-status">
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <div class="form-group">
            <label for="status" class="control-label">Estado</label>
            <select type="text" name="status" class="form-select form-select-sm" required>
                <option value="0" <?php echo $_GET['status'] == 0 ? "selected" : '' ?>>Inactivo</option>
                <option value="1" <?php echo $_GET['status'] == 1 ? "selected" : '' ?>>Activo</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#update-status').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.closest('.modal').find('button').attr('disabled',true)
            $.ajax({
                url:'../Actions.php?a=update_user_status',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.closest('.modal').find('button').attr('disabled',false)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        location.reload()
                    }else{
                        _el.addClass('alert alert-danger')
                        _this.closest('.modal').find('button').attr('disabled',false)
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                }
            })
        })
    })
</script>
