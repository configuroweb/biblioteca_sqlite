<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `sub_category_list` where sub_category_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="sub_category-form">
        <input type="hidden" name="id" value="<?php echo isset($sub_category_id) ? $sub_category_id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">Nombre</label>
            <input type="text" name="name" autofocus id="name" required class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : '' ?>">
        </div>
        <div class="form-group">
            <label for="category_id" class="control-label">Categoría</label>
            <select name="category_id" id="category_id" class="form-select form-select-sm rounded-0">
                <option <?php echo (!isset($category_id)) ? 'selected' : '' ?>></option>
                <?php 
                $qry = $conn->query("SELECT * FROM `category_list` order by `name` asc");
                while($row = $qry->fetchArray()):
                ?>
                <option value="<?php echo $row['category_id'] ?>" <?php echo isset($category_id) && $category_id == $row['category_id'] ? "selected" : '' ?>><?php echo $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status" class="control-label">Estado</label>
            <select name="status" id="status" class="form-select form-select-sm rounded-0">
                <option value="1" <?php echo (isset($status) && $status == 1 ) ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?php echo (isset($status) && $status == 0 ) ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#sub_category-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_sub_category',
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
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($sub_category_id) ?>" != 1)
                        _this.get(0).reset();
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