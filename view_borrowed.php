<?php 
require_once('./DBConnection.php');
$sql = "SELECT o.*,c.fullname FROM `borrowed_list` o inner join `user_list` c on o.user_id = c.user_id  where `borrowed_id` = '{$_GET['id']}'";
$qry = $conn->query($sql);
foreach($qry->fetchArray() as $k => $v){
    $$k = $v;
}
$date_created = new DateTime($date_created, new DateTimeZone(dZone));
$date_created->setTimezone(new DateTimeZone(tZone));
$date_created = $date_created->format('Y-m-d H:i');
if($due_date != null){
    $due_date = new DateTime($due_date, new DateTimeZone(dZone));
    $due_date->setTimezone(new DateTimeZone(tZone));
    $due_date = $due_date->format('Y-m-d H:i');
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2 d-flex">
                    <label for="" class="col-auto pe-1">Código de Transacción: </label>
                    <div class="col-auto flex-grow-1"><?php echo $transaction_code ?></div>
                </div>
                <div class="mb-2 d-flex">
                    <label for="" class="col-auto pe-1">Estado: </label>
                    <div class="col-auto flex-grow-1">
                        <span id="status">
                        <?php if($status == 1): ?>
                            <span class="badge bg-primary rounded-pill"><small>Confirmado</small></span>
                        <?php elseif($status == 2): ?>
                            <span class="badge bg-warning rounded-pill"><small>Prestado</small></span>
                        <?php elseif($status == 3): ?>
                            <span class="badge bg-success rounded-pill"><small>Devuelto</small></span>
                        <?php else: ?>
                            <span class="badge bg-dark text-light rounded-pill"><small>Pendiente</small></span>
                        <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2 d-flex">
                    <label for="" class="col-auto pe-1">Fecha Creación: </label>
                    <div class="col-auto flex-grow-1"><?php echo date("Y-m-d h:i A",strtotime($date_created)) ?></div>
                </div>
                <div class="mb-2 d-flex">
                    <label for="" class="col-auto pe-1">Fecha de vencimiento: </label>
                    <div class="col-auto flex-grow-1"><?php echo !is_null($due_date) ? date("D d, Y",strtotime($due_date)) : "N/A" ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bborroweded table-hover">
                    <colgroup>
                            <col width="15%">
                            <col width="85%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="py-0 px-1 text-center">Imagen</th>
                            <th class="py-0 px-1">Información</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $qry = $conn->query("SELECT bi.*,b.title,b.description,b.isbn,b.author,s.name as sub_category,cc.name as category FROM `borrowed_items` bi inner join `book_list` b on bi.book_id = b.book_id inner join `sub_category_list` s on b.sub_category_id = s.category_id inner join `category_list` cc on s.category_id = cc.category_id where bi.`borrowed_id` = '{$borrowed_id}'");
                        $total = 0;
                        while($row=$qry->fetchArray()):
                        ?>
                        <tr>
                            <td class="text-center align-middle">
                                <img src="<?php echo './uploads/thumbnails/'.$row['book_id'].'.png' ?>" alt="" class="img-fluid bborrowed bborrowed-dark" height="75px" width="75px">
                            </td>
                            <td>
                                <div class="w-100 d-flex">
                                    <div class="col-auto flex-grow-1">
                                        <div><a class="fs-5 text-decoration-none view_product" href="javascript:void(0)" data-id="<?php echo $row['book_id'] ?>"><b><?php echo $row['title'] ?></b></a>
                                        </div>
                                        <div class='lh-1'>
                                            <small><i><span class="text-muted">ISBN: </span><?php echo $row['isbn'] ?></i></small><br>
                                            <small><i><span class="text-muted">Autor(@s): </span><?php echo $row['author'] ?></i></small><br>
                                            <small><i><span class="text-muted">Categoría: </span><?php echo $row['category'] ?></i></small><br>
                                            <small><i><span class="text-muted">Sub Categoría: </span><?php echo $row['sub_category'] ?></i></small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <?php endwhile; ?>
                        </tr>
                    </tbody>
                </table>
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
        $('.view_product').click(function(){
            uni_modal('Detalles de Producto',"view_product.php?borrowed_id=<?php echo $borrowed_id ?>&id="+$(this).attr('data-id'),"mid-large")
        })
    })
</script>