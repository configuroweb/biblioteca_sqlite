<?php
require_once("DBConnection.php");
$qry = $conn->query("SELECT * FROM `user_list` where user_id = '{$_SESSION['user_id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
?>
<h3>Gestionar Cuenta</h3>
<hr>
<div class="col-md-6">
    <form action="" id="user-form">
        <input type="hidden" name="id" value="<?php echo isset($user_id) ? $user_id : '' ?>">
        <div class="form-group">
            <label for="fullname" class="control-label">Nombre Completo</label>
            <input type="text" name="fullname" id="fullname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($fullname) ? $fullname : '' ?>">
        </div>
        <div class="form-group">
            <label for="contact" class="control-label">Contacto</label>
            <input type="text" name="contact" id="contact" required class="form-control form-control-sm rounded-0" value="<?php echo isset($contact) ? $contact : '' ?>">
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Correo</label>
            <input type="text" name="email" id="email" required class="form-control form-control-sm rounded-0" value="<?php echo isset($email) ? $email : '' ?>">
        </div>
        <div class="form-group">
            <label for="address" class="control-label">Dirección</label>
            <textarea name="address" id="address" cols="30" rows="3" class="form-control rounded-0"><?php echo isset($address) ? $address : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="department" class="control-label">Departmento</label>
            <input type="text" name="department" id="department" required class="form-control form-control-sm rounded-0" value="<?php echo isset($department) ? $department : '' ?>">
        </div>
        <?php if($type == 'Student'): ?>
        <div class="form-group">
            <label for="level_section" class="control-label">Curso/Nivel/Sección</label>
            <input type="text" name="level_section" id="level_section" required class="form-control form-control-sm rounded-0" value="<?php echo isset($level_section) ? $level_section : '' ?>">
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username" class="control-label">Usuario</label>
            <input type="text" name="username" id="username" required class="form-control form-control-sm rounded-0" value="<?php echo isset($username) ? $username : '' ?>">
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Nueva Contraseña</label>
            <input type="password" name="password" id="password" class="form-control form-control-sm rounded-0" value="">
        </div>
        <div class="form-group">
            <label for="old_password" class="control-label">Contraseña Anterior</label>
            <input type="password" name="old_password" id="old_password" class="form-control form-control-sm rounded-0" value="">
        </div>
        <div class="form-group">
            <small>Deja el campo de la contraseña en blanco si no desea actualizar su contraseña.</small>
        </div>
        <div class="form-group d-flex w-100 justify-content-end">
            <button class="btn btn-sm btn-primary rounded-0 my-1">Actualizar</button>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#user-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'Actions.php?a=update_credentials_user',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                            location.reload()
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>