<h3>Bienvenid@ a tu Sistema de Libería ConfiguroWeb</h3>
<hr>
<div class="col-12">
    <div class="row gx-3 row-cols-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-primary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Total de Categorías</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $category = $conn->query("SELECT count(category_id) as `count` FROM `category_list` where `status` = 1 ")->fetchArray()['count'];
                                echo $category > 0 ? number_format($category) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-warning"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Total Sub Categorías</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                 $sub_category = $conn->query("SELECT count(sub_category_id) as `count` FROM `sub_category_list` where `status` = 1 ")->fetchArray()['count'];
                                 echo $sub_category > 0 ? number_format($sub_category) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-book-open fs-3 text-success"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Libros</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $book = $conn->query("SELECT count(book_id) as `count` FROM `book_list` where `status` =1 ")->fetchArray()['count'];
                                echo $book > 0 ? number_format($book) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-users fs-3 text-dark"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Usuarios</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $users = $conn->query("SELECT count(user_id) as `count` FROM `user_list` where `status` = 1 ")->fetchArray()['count'];
                                echo $users > 0 ? number_format($users) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>