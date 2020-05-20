<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="resources/css/font-awesome.min.css" />
    <link rel="stylesheet" href="resources/css/main.css" />
    <title><?= $pageTitle ?></title>

</head>
<body>
<div class="modal" tabindex="-1" role="dialog" id="auth-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Вход</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="profile/login" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" name="login" class="form-control" id="login" required>
                        <label for="password">Пароль</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Вход</button>
                </div>
            </form>
        </div>
    </div>
</div>


<nav class="navbar navbar-light bg-light shadow">
    <span class="navbar-brand mb-0 h1">Тестовое задание</span>

    <? if (\core\classes\Auth::is()) { ?>
        <a href="profile/logout" class="btn btn-outline-success" >Выход</a>
    <? } else { ?>
        <a href="#" class="btn btn-outline-success" data-toggle="modal" data-target="#auth-modal">Вход</a>
    <? } ?>
</nav>

<div class="container-fluid">

    <? foreach (\core\classes\Notice::get() as $notice) { ?>
        <div class="alert alert-<?=$notice['type'] ?> >" role="alert">
            <?=$notice['msg'] ?>
        </div>
    <? } \core\classes\Notice::clear() ?>



    <? require_once 'modules/' . $module . '/views/' . $view . 'View.php' ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>