<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `user_list` where user_id = '{$_GET['id']}'");
        foreach($qry->fetchArray() as $k => $v){
            $$k = $v;
        }
    }
?>
<div class="container-fluid">
<form action="" id="register-form">
    <input type="hidden" name="id" value="<?php echo isset($user_id)? $user_id : '' ?>">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fullname" class="control-label">Nombre Completo</label>
                    <input type="text" name="fullname" id="fullname" required class="form-control form-control-sm rounded-0" value="<?php echo isset($fullname)? $fullname : '' ?>">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Correo</label>
                    <input type="email" name="email" id="email" required class="form-control form-control-sm rounded-0" value="<?php echo isset($email)? $email : '' ?>">
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contacto</label>
                    <input type="text" name="contact" id="contact" required class="form-control form-control-sm rounded-0" value="<?php echo isset($contact)? $contact : '' ?>">
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Dirección</label>
                    <textarea rows="2" name="address" id="address" required class="form-control form-control-sm rounded-0"><?php echo isset($address)? $address : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Tipo de Usuario</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="faculty" value="Faculty" required <?php echo isset($type) && $type == 'Faculty' ? "checked" : "" ?>>
                        <label class="form-check-label" for="faculty">
                            Faculty
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="student" value="Student" <?php echo isset($type) && $type == 'Student' ? "checked" : "" ?>>
                        <label class="form-check-label" for="student" >
                            Student
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="department" class="control-label">Departmento</label>
                    <input type="department" name="department" id="department" required class="form-control form-control-sm rounded-0" value="<?php echo isset($department)? $department : '' ?>">
                </div>
                <div class="form-group" <?php echo isset($type) && $type == 'Faculty' ? "style='display:none'" : "" ?>>
                    <label for="level_section" class="control-label">Curso/Nivel/Sección</label>
                    <input type="level_section" name="level_section" id="level_section" required class="form-control form-control-sm rounded-0" value="<?php echo isset($level_section)? $level_section : '' ?>">
                </div>
                <div class="form-group">
                    <label for="username" class="control-label">Usuario</label>
                    <input type="username" name="username" id="username" required class="form-control form-control-sm rounded-0" value="<?php echo isset($username)? $username : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Contraseña</label>
                    <input type="password" name="password" id="password" required class="form-control form-control-sm rounded-0" value="">
                    <?php if(isset($password)): ?>
                        <small class="text-info"><i>Déjelo en blanco si no desea actualizar la contraseña.</i></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>
</div>

<script>
    $(function(){
        $('[name="type"]').click(function(){
            var type = $(this).val()
            if(type == 'Faculty'){
                $('#level_section').closest('div').hide()
                $('#level_section').attr("required",false)
            }else{
                $('#level_section').closest('div').show()
                $('#level_section').attr("required",true)
            }
        })
        $('#register-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_user',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("Ocurrió un error")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Guardar')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($user_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Guardar')
                }
            })
        })
    })
</script>