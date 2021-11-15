<?php
session_start();
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT b.*, c.name as category, s.name as sub_category FROM `book_list` b inner join sub_category_list s on b.sub_category_id = s.sub_category_id inner join category_list c on s.category_id = c.category_id where b.book_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
$thumbnail = 'uploads/thumbnails/'.$book_id.'.png';
$scan = scandir('uploads/images/'.$book_id.'/');
unset($scan[0]);
unset($scan[1]);
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>

<div class="container-fluid" id="book-details">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-12">
                        <img src="<?php echo $thumbnail ?>" id="selected-image" alt="Img" class="display-image image-fluid border-dark border">
                    </div>
                </div>
                <div class="d-flex flex-nowrap w-100 overflow-auto my-2">
                    <div class="col-auto m-1">
                        <a href="javascript:void(0)" class="select-img border border-dark d-block">
                            <img src="<?php echo $thumbnail ?>" alt="Img" class="display-select-image img-fluid" />
                        </a>
                    </div>
                    <?php 
                        foreach($scan as $img):
                    ?>
                    <div class="col-auto m-1 img-item">
                        <a href="javascript:void(0)" class="select-img border border-dark d-block">
                            <img src="<?php echo 'uploads/images/'.$book_id.'/'.$img ?>" alt="Img" class="display-select-image img-fluid" />
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-info fw-bold"><larger>ISBN: <?php echo $isbn ?></larger></div>
                <div class="fs-4"><?php echo $title ?></div>
                <div><span class="text-muted">Escrito por: </span><?php echo $author ?></div>
                <div class="lh-1">
                        <small><i><span class="text-muted">Categoría: </span><?php echo $category ?></i></small><br>
                        <small><i><span class="text-muted">Sub Categoría: </span><?php echo $sub_category ?></i></small>
                    </div>
                <p class="py-3"><?php echo str_replace("\n\r","<br/>",$description) ?></p>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row justify-content-end">
        <?php if(!isset($_GET['order_id'])): ?>
            <div class="col-auto mx-1">
                <div class="btn btn btn-primary btn-sm rounded-0" id="add_to_cart" type="button">Agregar al Carrito</div>
            </div>
            <?php endif; ?>
            <div class="col-1">
                <div class="btn btn btn-dark btn-sm rounded-0" type="button" data-bs-dismiss="modal">Cerrar</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.select-img').click(function(){
            var imgPath = $(this).find('img').attr('src')
            $('#selected-image').attr('src',imgPath)
        })
        if('<?php echo isset($_GET['order_id']) ?>' == 1){
            $('#uni_modal').on('hidden.bs.modal',function(){
                if($('#uni_modal #book-details').length > 0)
                uni_modal('Detalles de orden',"view_order.php?id=<?php echo isset($_GET['order_id'])? $_GET['order_id'] : '' ?>",'large')
            })
        }
        $('#add_to_cart').click(function(){
            $('#uni_modal button').attr('disabled',true)
            if('<?php echo isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0 ?>' == 1){
                $.ajax({
                    url:"Actions.php?a=add_to_cart",
                    method:"POST",
                    data:{book_id:'<?php echo $book_id ?>'},
                    dataType:'JSON',
                    error:err=>{
                        alert("Ocurrió un error")
                        $('#uni_modal button').attr('disabled',false)
                    },
                    success:function(resp){
                        if(resp.status == "success"){
                            $('#cart_count').text(resp.cart_count)
                            alert("Libro agregado al carrito");
                        }else if(resp.status == "failed" && !!resp.msg){
                            alert(resp.msg)
                        
                        }else{
                        alert("Ocurrió un error")
                        }
                        $('#uni_modal button').attr('disabled',false)
                    }
                })
            }else{
                uni_modal('Por favor ingrese las credenciales de usuario',"login.php")
            }
        })
    })
   
</script>