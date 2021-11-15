<div class="container-fluid">
    <form action="" id="update-status">
        <input type="hidden" name="borrowed_id" value="<?php echo $_GET['id'] ?>">
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select type="text" name="status" class="form-select form-select-sm" required>
                <option value="0" <?php echo $_GET['status'] == 0 ? "selected" : '' ?>>Pending</option>
                <option value="1" <?php echo $_GET['status'] == 1 ? "selected" : '' ?>>Confirmed</option>
                <option value="2" <?php echo $_GET['status'] == 2 ? "selected" : '' ?>>Picked Up</option>
                <option value="3" <?php echo $_GET['status'] == 3 ? "selected" : '' ?>>Returned</option>
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
                url:'../Actions.php?a=update_borrowed_status',
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
                        setTimeout(() => {
                            _this.closest('.modal').modal('hide')
                            if(resp.return_status == 1)
                                new_stat = '<span class="badge bg-primary"><small>Confirmed</small></span>';
                            else if(resp.return_status == 2)
                                new_stat = '<span class="badge bg-success"><small>Delivered</small></span>';
                            else if(resp.return_status == 3)
                                new_stat = '<span class="badge bg-danger"><small>Cancelled</small></span>';
                            else
                                new_stat = '<span class="badge bg-dark text-light"><small>Pending</small></span>';
                            $('#status').html(new_stat)
                            $('.update_status').attr('data-status',resp.return_status)
                            $('#uni_modal').on('hide.bs.modal',function(){
                                location.reload()
                            })
                            _this.closest('.modal').find('button').attr('disabled',false)

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
