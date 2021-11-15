<?php
session_start();
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'books';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_','',$page)) ?> | Biblioteca Escolar</title>
    <link rel="stylesheet" href="Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="DataTables/datatables.min.css">
    <script src="DataTables/datatables.min.js"></script>
    <script src="Font-Awesome-master/js/all.min.js"></script>
    <script src="js/script.js"></script>
    <style>
        html,body{
            height:100%;
            width:100%;
        }
        main{
            height:100%;
            display:flex;
            flex-flow:column;
        }
        #page-container{
            flex: 1 1 auto; 
            overflow:auto;
        }
        #topNavBar{
            flex: 0 1 auto; 
        }
        .thumbnail-img{
            width:50px;
            height:50px;
            margin:2px
        }
        .truncate-1 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        .truncate-3 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .modal-dialog.large {
            width: 80% !important;
            max-width: unset;
        }
        .modal-dialog.mid-large {
            width: 50% !important;
            max-width: unset;
        }
        @media (max-width:720px){
            
            .modal-dialog.large {
                width: 100% !important;
                max-width: unset;
            }
            .modal-dialog.mid-large {
                width: 100% !important;
                max-width: unset;
            }  
        
        }
        .display-select-image{
            width:60px;
            height:60px;
            margin:2px
        }
        img.display-image {
            width: 100%;
            height: 45vh;
            object-fit: cover;
            background: black;
        }
        /* width */
        ::-webkit-scrollbar {
        width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        background: #f1f1f1; 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #555; 
        }
        .img-del-btn{
            right: 2px;
            top: -3px;
        }
        .img-del-btn>.btn{
            font-size: 10px;
            padding: 0px 2px !important;
        }
    </style>
</head>
<body>
    <main>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="#">
            Biblioteca ConfiguroWeb
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == "books" ? "active" :"" ?>" href="./?page=books">Libros</a>
                    </li>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0): ?>

                    <li class="nav-item">
                        <?php 
                         $count = $conn->query("SELECT count(book_id) as total FROM `cart_list` where `user_id` = '{$_SESSION['user_id']}'")->fetchArray()['total'];
                        ?>
                        <a class="nav-link <?php echo $page == "cart" ? "active" :"" ?>" href="./?page=cart">Carrito <span id="cart_count" class="badge badge-pill bg-light text-dark"><?php echo $count > 0 ? $count : 0 ?></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == "borrowed" ? "active" :"" ?>" href="./?page=borrowed">Mis libros prestados</a>
                    </li>
                    <?php endif; ?>
                    
                </ul>
            </div>
            <div>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0): ?>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle bg-transparent  text-light border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Bienvenid@ <?php echo $_SESSION['fullname'] ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="./?page=manage_account">Gestionar Cuenta</a></li>
                    <li><a class="dropdown-item" href="Actions.php?a=faculty_student_logout">Cerrar Sesión</a></li>
                </ul>
            </div>
            <?php else: ?>
                <a href="javascript:void(0)" id="login-btn" class="text-decoration-none">Ingresar</a>              
            <?php endif; ?>

            </div>
        </div>
    </nav>
    <div class="container py-3" id="page-container">
        <?php 
            if(isset($_SESSION['flashdata'])):
        ?>
        <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
        <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
            <?php echo $_SESSION['flashdata']['msg'] ?>
        </div>
        <?php unset($_SESSION['flashdata']) ?>
        <?php endif; ?>
        <?php
            include $page.'.php';
        ?>
    </div>
    </main>
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Guardar</button>
            <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Guardar</button>
            <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <div class="modal-header py-2">
            <h5 class="modal-title">Confirmación</h5>
        </div>
        <div class="modal-body">
            <div id="delete_content"></div>
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm' onclick="">Continuar</button>
            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#register-btn').click(function(){
                uni_modal("Registrar nueva cuenta","register.php","mid-large");
            })
            $('#login-btn').click(function(){
                uni_modal('Por favor ingresa tus credenciales',"login.php")
            })
        })
    </script>
</body>
</html>