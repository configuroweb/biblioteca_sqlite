
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Lista de Libros</h3>
        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Agregar Nuevo</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="10%">
                <col width="20%">
                <col width="25%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Fecha Creación</th>
                    <th class="text-center p-0">Miniatura</th>
                    <th class="text-center p-0">ISBN</th>
                    <th class="text-center p-0">Título</th>
                    <th class="text-center p-0">Estado</th>
                    <th class="text-center p-0">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM `book_list` order by `title` asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                        $thumbnail = '../uploads/thumbnails/'.$row['book_id'].'.png?v='.strtotime($row['date_updated']);
                        $row['date_created'] = new DateTime($row['date_created'], new DateTimeZone(dZone));
                        $row['date_created']->setTimezone(new DateTimeZone(tZone));
                        $row['date_created'] = $row['date_created']->format('Y-m-d H:i');
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['date_created'] ?></td>
                    <td class="py-0 px-1 text-center">
                        <img src="<?php echo $thumbnail ?>" alt="img" class="thumbnail-img border rounded broder-light">
                    </td>
                    <td class="py-0 px-1"><?php echo $row['isbn'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['title'] ?></td>
                    <td class="py-0 px-1 text-center">
                    <?php if($row['status'] == 1): ?>
                        <span class="badge bg-success rounded-pill"><small>Activo</small></span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill"><small>Inactivo</small></span>
                    <?php endif; ?>
                    </td>
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Acción
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['book_id'] ?>' href="javascript:void(0)">Ver</a></li>
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['book_id'] ?>' href="javascript:void(0)">Editar</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['book_id'] ?>' data-name = '<?php echo $row['isbn']." - ".$row['title'] ?>' href="javascript:void(0)">Eliminar</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
               
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('#create_new').click(function(){
            uni_modal('Agregar Nuevo Libro',"manage_book.php",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('Editar Detalles de Libro',"manage_book.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('Detalles de Libro',"view_book.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_data').click(function(){
            _conf("Estás segur@ de eliminar <b>"+$(this).attr('data-name')+"</b> de la lista?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:6 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'../Actions.php?a=delete_transaction',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("Ocurrió un error")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("Ocurrió un error")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>