<?php
require_once("DBConnection.php");
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
<form action="" id="register-form">
<input type="hidden" name="id">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fullname" class="control-label">Nombre Completo</label>
                    <input type="text" name="fullname" id="fullname" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Correo</label>
                    <input type="email" name="email" id="email" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contact</label>
                    <input type="text" name="contact" id="contact" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <textarea rows="2" name="address" id="address" required class="form-control form-control-sm rounded-0"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">User Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="faculty" value="Faculty" required>
                        <label class="form-check-label" for="faculty">
                            Faculty
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="student" value="Student">
                        <label class="form-check-label" for="student">
                            Student
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="department" class="control-label">Department</label>
                    <input type="department" name="department" id="department" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="level_section" class="control-label">Course/Level/Section</label>
                    <input type="level_section" name="level_section" id="level_section" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="username" class="control-label">Username</label>
                    <input type="username" name="username" id="username" required class="form-control form-control-sm rounded-0" value="">
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input type="password" name="password" id="password" required class="form-control form-control-sm rounded-0" value="">
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary rounded-0 me-1">Create Account</button>
                <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Cancel</button>
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
                url:'Actions.php?a=save_faculty_student',
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
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            uni_modal('Please Enter your Login Credentials',"login.php")
                        }, 2500);
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