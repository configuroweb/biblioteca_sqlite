<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `book_list` where book_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="book-form">
        <input type="hidden" name="id" value="<?php echo isset($book_id) ? $book_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="isbn" class="control-label">ISBN</label>
                        <input type="text" pattern="[0-9]+{13}" maxLength="13" minlength="13" name="isbn" autofocus id="isbn" required class="form-control form-control-sm rounded-0" value="<?php echo isset($isbn) ? $isbn : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">Título</label>
                        <input type="text" name="title" autofocus id="title" required class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Descripción</label>
                        <textarea name="description" id="description" cols="30" rows="4" required class="form-control rounded-0"><?php echo isset($description) ? $description : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="author" class="control-label">Autores</label>
                        <textarea name="author" id="author" cols="30" rows="4" required class="form-control rounded-0"><?php echo isset($author) ? $author : '' ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id" class="control-label">Categoría</label>
                        <select name="category_id" id="category_id" class="form-select form-select-sm rounded-0" required>
                            <option <?php echo (!isset($category_id)) ? 'selected' : '' ?> disabled>Selecciona Categoría</option>
                            <?php
                            $category_qry = $conn->query("SELECT * FROM category_list where `status` = 1 ".(isset($category_id) ? " or category_id ='{$category_id}'" : "")." order by `name` asc");
                            while($row= $category_qry->fetchArray()):
                            ?>
                                <option value="<?php echo $row['category_id'] ?>" <?php echo (isset($category_id) && $category_id == $row['category_id'] ) ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        
                        <?php
                            $sub_arr = array();
                            $sub_qry = $conn->query("SELECT * FROM sub_category_list where `status` = 1 ".(isset($sub_category_id) ? " or sub_category_id ='{$sub_category_id}'" : "")." order by `name` asc");
                            while($row= $sub_qry->fetchArray()):
                                $sub_arr[$row['category_id']][$row['sub_category_id']] = $row['name'];
                            endwhile; 
                        ?>
                        <label for="sub_category_id" class="control-label">Sub Categoría</label>
                        <select name="sub_category_id" id="sub_category_id" class="form-select form-select-sm rounded-0" required>
                            <option <?php echo (!isset($sub_category_id)) ? 'selected' : '' ?> disabled>Select Categoría</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="thumbnail" class="control-label">Miniatura</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control form-control-sm rounded-0" accept="image/png, image/jpeg, image/jpg" required>
                        <?php if(isset($book_id)): ?>
                            <small class="text-info"><i>Cargar solo si deseas actualizar la miniatura del libro.</i></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="img" class="control-label">Imágenes</label>
                        <input type="file" name="img[]" class="form-control form-control-sm rounded-0" id="img" required multiple accept="image/png, image/jpeg, image/jpg" required>
                        <?php if(isset($book_id)): ?>
                            <small class="text-info"><i>Cargar solo si deseas agregar imágenes de libros.</i></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="status" class="control-label">Estado</label>
                        <select name="status" id="status" class="form-select form-select-sm rounded-0">
                            <option value="1" <?php echo (isset($status) && $status == 1 ) ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?php echo (isset($status) && $status == 0 ) ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var sub_cat = $.parseJSON('<?php echo json_encode($sub_arr) ?>');
    $(function(){
        $('#category_id').change(function(){
            var cid = $(this).val()
            $('#sub_category_id').html('')
            if(!!sub_cat[cid]){
                Object.keys(sub_cat[cid]).map(k=>{
                    var opt =$("<option>")
                    opt.attr("value",k)
                    opt.text(sub_cat[cid][k])
                    if("<?php echo isset($sub_category_id)? $sub_category_id : '' ?>" == k)
                    option.attr('selected',true)
                    $('#sub_category_id').append(opt)
                })
            }
        })
        $('#book-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_book',
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
                        if("<?php echo isset($book_id) ?>" != 1)
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