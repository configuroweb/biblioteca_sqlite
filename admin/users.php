
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Lista de Usuarios</h3>
        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Agregar Nuevo Usuario</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="30%">
                <col width="20%">
                <col width="10%">
                <col width="15%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Nombre</th>
                    <th class="text-center p-0">Info</th>
                    <th class="text-center p-0">Usuario</th>
                    <th class="text-center p-0">Estado</th>
                    <th class="text-center p-0">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM `user_list` order by `fullname` asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['fullname'] ?></td>
                    <td class="py-0 px-1">
                        <div class="fs-6">Correo: <?php echo $row['email'] ?></div>
                        <div class="fs-6">Contacto: <?php echo $row['contact'] ?></div>
                        <div class="fs-6 truncate-1" title="<?php echo $row['address'] ?>">Dirección: <?php echo $row['address'] ?></div>
                    </td>
                    <td class="py-0 px-1"><?php echo $row['username'] ?></td>
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
                            <li><a class="dropdown-item update_status" data-id = '<?php echo $row['user_id'] ?>' href="javascript:void(0)" data-name = '<?php echo $row['fullname'] ?>' data-status = '<?php echo $row['status'] ?>'>Actualizar Estado</a></li>
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['user_id'] ?>' href="javascript:void(0)" data-name = '<?php echo $row['fullname'] ?>' >Ver</a></li>
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['user_id'] ?>' href="javascript:void(0)" data-name = '<?php echo $row['fullname'] ?>' >Editar</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['user_id'] ?>' data-name = '<?php echo $row['fullname'] ?>' href="javascript:void(0)">Eliminar</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
                <?php if(!$qry->fetchArray()): ?>
                    <tr>
                        <th class="text-center p-0" colspan="6">Sin datos que mostrar</th>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('#create_new').click(function(){
            uni_modal('Agregar Nuevo Usuario',"manage_user.php","mid-large")
        })
        $('.update_status').click(function(){
            uni_modal('Actualizar Estado de Usuario '+$(this).attr('data-name')+'',"update_user_status.php?id="+$(this).attr('data-id')+"&status="+$(this).attr('data-status'))
        })
        $('.view_data').click(function(){
            uni_modal('Ver Detalles de Usuario',"view_user.php?id="+$(this).attr('data-id'),"mid-large")
        })
        $('.edit_data').click(function(){
            uni_modal('Editar Detalles de Usuario',"manage_user.php?id="+$(this).attr('data-id'),"mid-large")
        })
        $('.delete_data').click(function(){
            _conf("Estás segur@ de eliminar <b>"+$(this).attr('data-name')+"</b> de la lista?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')

        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:[4,5] }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'../Actions.php?a=delete_user',
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