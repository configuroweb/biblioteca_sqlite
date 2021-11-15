<h3>Bienvenid@ a tu Biblioteca ConfiguroWeb</h3>
<hr>
<style>
    .prod-item:hover>.card{
        background: rgba(var(--bs-info-rgb),.5)
    }
    
    .prod-item>.card .img-top{
        transition: transform .5s ease;
        width: 100%;
    }
    .prod-item:hover>.card .img-top{
        transform:scale(1.5);
    }
</style>
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-4">
            <h4><b>Categorias</b></h4>
            <hr>
            <div class="list-group">
                <a href="./?category=all" class="list-group-item <?php echo !isset($_GET['category']) || (isset($_GET['category']) && !is_numeric($_GET['category']))? "active" : "" ?>">Todos</a>
                <?php 
                $categories = $conn->query("SELECT * FROM `category_list` where `status` = 1 order by `name` asc");
                while($row=$categories->fetchArray()):
                ?>
                    <a href="./?category=<?php echo $row['category_id'] ?>" class="list-group-item <?php echo isset($_GET['category']) && $_GET['category'] == $row['category_id'] ? "active" : "" ?>"><?php echo $row['name'] ?></a>
                <?php endwhile; ?>
            </div>
            <div class="clearfix my-2"></div>
            <?php if(isset($_GET['category']) && is_numeric($_GET['category'])): ?>
            <h4><b>Sub Categorías</b></h4>
            <hr>
            <div class="list-group">
                <a href="./?category=<?php echo $_GET['category'] ?>" class="list-group-item <?php echo !isset($_GET['sub_category']) || (isset($_GET['sub_category']) && !is_numeric($_GET['sub_category']))? "active" : "" ?>">Todos</a>
                <?php 
                $categories = $conn->query("SELECT * FROM `sub_category_list` where `category_id` ='{$_GET['category']}' and `status` = 1 order by `name` asc");
                while($row=$categories->fetchArray()):
                ?>
                    <a href="./?category=<?php echo $_GET['category'] ?>&sub_category=<?php echo $row['sub_category_id'] ?>" class="list-group-item <?php echo isset($_GET['sub_category']) && $_GET['sub_category'] == $row['sub_category_id'] ? "active" : "" ?>"><?php echo $row['name'] ?></a>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Ingresa tu búsqueda aquí" aria-label="Search Here" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" aria-describedby="btn-search">
                        <button class="btn btn-outline-secondary" type="button" id="btn-search"><i class="fa fa-search"></i> Búsqueda</button>
                    </div>
                </div>
            </div>
            <div class="row gx-3 row-cols-3">
                <?php 
                $where = "";
                if(isset($_GET['category']) && $_GET['category'] != 'all' && is_numeric($_GET['category'])){
                    $where = " and c.`category_id` = '{$_GET['category']}' ";
                }
                if(isset($_GET['sub_category']) && $_GET['sub_category'] != 'all' && is_numeric($_GET['sub_category'])){
                    $where = " and s.`sub_category_id` = '{$_GET['sub_category']}' ";
                }
                if(isset($_GET['search']) && $_GET['search'] != ''){
                    $where = " and (b.`title` LIKE '%{$_GET['search']}%' OR b.`description` LIKE '%{$_GET['search']}%' OR b.`author` LIKE '%{$_GET['search']}%' OR b.`description` LIKE '%{$_GET['search']}%' OR c.`name` LIKE '%{$_GET['search']}%' OR s.`name` LIKE '%{$_GET['search']}%') ";
                }
                $sql = "SELECT b.*,c.name as category,s.name as sub_category FROM `book_list` b inner join `sub_category_list` s on b.sub_category_id = s.sub_category_id inner join `category_list` c on s.category_id = c.category_id where b.`status` = 1 {$where} order by b.`title` asc";
                $qry = $conn->query($sql);
                while($row = $qry->fetchArray()):
                ?>
                <a class="col prod-item text-dark text-decoration-none" href="javascript:void(0)" data-id="<?php echo $row['book_id'] ?>">
                    <div class="card h-100">
                        <div class="h-auto overflow-hidden">
                            <img src="<?php echo "uploads/thumbnails/".$row['book_id'].".png" ?>" alt="IMG" class="img-top">
                        </div>
                        <div class="card-body">
                            <div class="fs-5"><?php echo $row['title'] ?></div>
                            <div class="lh-1">
                                <small><i><?php echo $row['category'] ?></i></small><br>
                                <small><i><?php echo $row['sub_category'] ?></i></small>
                            </div>
                            <p class="m-0 truncate-3"><small><i><?php echo $row['description'] ?></i></small></p>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
            <?php 
            if(!$qry->fetchArray()): ?>
            <center>
                <?php if(isset($_GET['search'])): ?>
                <div class="fs-5"><b><i>No Result for <b>"<?php echo $_GET['search'] ?>"</b> keyword.</i></b></div>
                <?php else: ?>
                <div class="fs-5"><b><i>No Book Listed Yet.</i></b></div>
                <?php endif; ?>
            </center>
            <?php endif;  ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.prod-item').click(function(){
            uni_modal("Detalles de Libro","view_book.php?id="+$(this).attr('data-id'),"mid-large")
        })
        $('#search').keydown(function(e){
            if(e.which == 13){
                e.preventDefault();
                search()
            }

        })
        $('#btn-search').click(function(){
            search()
        })
    })
    function search(){
        var keyword = $('#search').val()
        if(keyword != '')
        location.href = "./?search="+(encodeURI(keyword))
        else
        location.href = "./"
    }
</script>