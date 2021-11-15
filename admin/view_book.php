<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT b.*, c.name as category, s.name as sub_category FROM `book_list` b inner join sub_category_list s on b.sub_category_id = s.sub_category_id inner join category_list c on s.category_id = c.category_id where b.book_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
$thumbnail = '../uploads/thumbnails/'.$book_id.'.png';
$scan = scandir('../uploads/images/'.$book_id.'/');
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
                        <img src="<?php echo $thumbnail ?>" id="selected-image" alt="Img" class="display-image image-fluid border-dark">
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
                    <div class="col-auto m-1 position-relative img-item">
                        <a href="javascript:void(0)" class="select-img border border-dark d-block">
                            <img src="<?php echo '../uploads/images/'.$book_id.'/'.$img ?>" alt="Img" class="display-select-image img-fluid" />
                        </a>
                        <span class="position-absolute img-del-btn"><button class="btn btn-sm btn-danger rounded-0 p-0"  data-path="<?php echo '/uploads/images/'.$book_id.'/'.$img ?>"><i class="fa fa-times" type="button"></i></button></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-info fw-bold"><larger>ISBN: <?php echo $isbn ?></larger></div>
                <div class="fs-4 pb-3"><?php echo $title ?></div>
                <div>Written by: <?php echo $author ?></div>
                <p class="py-3"><?php echo str_replace("\n\r","<br/>",$description) ?></p>
                <div>
                    Estado: 
                    <?php if($status == 1): ?>
                        <span class="badge bg-success rounded-pill"><small>Activo</small></span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill"><small>Inactivo</small></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row justify-content-end">
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
        $('.img-del-btn>.btn').click(function(e){
            e.preventDefault()
            _conf("¿Estás segur@ de eliminar esta imagen de libro?","delete_img",["'"+$(this).attr('data-path')+"'"])
        })
        if('<?php echo isset($_GET['borrowed_id']) ?>' == 1){
            $('#uni_modal').on('hidden.bs.modal',function(){
                if($('#uni_modal #book-details').length > 0)
                uni_modal('Detalles de Prestamo',"view_borrowed.php?id=<?php echo isset($_GET['borrowed_id']) ? $_GET['borrowed_id'] : '' ?>",'large')
            })
        }
    })
    function delete_img($path){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:"../Actions.php?a=delete_img",
            method:"POST",
            data:{path:$path},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert("Ocurrió un error")
            },
            success:function(resp){
                if(resp.status == 'success'){
                    $('.img-del-btn>.btn[data-path="'+$path+'"]').closest('.img-item').remove()
                    $('#confirm_modal').modal('hide')
                }else{
                    console.log(resp)
                    alert("Ocurrió un error")
                }
            $('#confirm_modal button').attr('disabled',false)
            }
        })
    }
</script>