<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
<form action="" id="login-form">
<input type="hidden" name="id">
    <div class="col-12">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="form-group">
                    <label for="username" class="control-label">Usuario</label>
                    <input type="username" name="username" autofocus id="username" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Contraseña</label>
                    <input type="password" name="password" id="password" required class="form-control form-control-sm rounded-0" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary rounded-0 me-1">Ingresar</button>
                <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</form>
</div>
<script>
    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Loging in...')
            $.ajax({
                url:'Actions.php?a=faculty_student_login',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            location.replace('./');
                        }, 2000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>