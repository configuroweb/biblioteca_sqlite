<div class="container-fluid">
    <form action="" id="update-due">
        <input type="hidden" name="borrowed_id" value="<?php echo $_GET['id'] ?>">
        <div class="form-group">
            <label for="due" class="control-label">Date</label>
            <input type="date" class="form-control" name="due_date" required value="<?php echo !is_null($_GET['due']) ?$_GET['due'] : '' ?>">
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#update-due').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.closest('.modal').find('button').attr('disabled',true)
            $.ajax({
                url:'../Actions.php?a=update_borrowed_due',
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
                        _this.closest('.modal').find('button').attr('disabled',false)
                        setTimeout(() => {
                            _this.closest('.modal').modal('hide')
                            $('.return_date').text(resp.due_formatted)
                            $('.update_due').attr('data-due',resp.due)
                            $('#due').html(new_stat)
                            $('#uni_modal').on('hide.bs.modal',function(){
                                location.reload()
                            })

                        }, 2000);
                       
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
