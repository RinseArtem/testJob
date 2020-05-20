<div class="modal fade" id="task-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавить задачу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/index/new" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user">Имя пользователя</label>
                        <input type="text" name="user" class="form-control" id="user" placeholder="Имя пользователя">
                    </div>
                    <div class="form-group">
                        <label for="mail">E-Mail</label>
                        <input type="text" name="mail" class="form-control" id="mail" placeholder="example@example.com">
                    </div>
                    <div class="form-group">
                        <label for="desc">Описание</label>
                        <textarea class="form-control" name="description" id="desc" rows="3"></textarea>
                    </div>
                    <div class="form-group form-check status d-none">
                        <input type="checkbox" name="status" class="form-check-input" id="status">
                        <label class="form-check-label" for="status">Выполненно</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<h2>Список задач</h2>
<div class="row">
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"><a href="?by=user">Имя пользователя <?= $sortBy == 'user' ? '<i class="fa fa-sort-amount-'. $way .'" aria-hidden="true"></i>' : '' ?></a></th>
                    <th scope="col"><a href="?by=mail">E-Mail <?= $sortBy == 'mail' ? '<i class="fa fa-sort-amount-'. $way .'" aria-hidden="true"></i>' : '' ?></a></th>
                    <th scope="col">Описание</th>
                    <th scope="col"><a href="?by=status">Статус <?= $sortBy == 'status' ? '<i class="fa fa-sort-amount-'. $way .'" aria-hidden="true"></i>' : '' ?></a></th>
                    <th scope="col" class="no-padding">
                        <a href="#" class="add-btn" title="Добавить" data-toggle="modal" data-target="#task-modal">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($tasks as $task)  { ?>
                    <tr>
                        <th scope="row"><?= $task['id'] ?></th>
                        <td><?= $task['user'] ?></td>
                        <td><?= $task['mail'] ?></td>
                        <td><?= $task['description'] ?></td>
                        <td><?= $task['status'] == 0 ? '<i class="fa fa-times" aria-hidden="true" title="Не выполнено" style="color: red"></i>' : '<i class="fa fa-check" aria-hidden="true" title="Выполненно" style="color: green"></i>' ?></td>
                        <td>
                            <? if (\core\classes\Auth::is()) { ?>
                                <a href="#"
                                   class="edit"
                                   data-task-id="<?= $task['id'] ?>"
                                   data-task-user="<?= $task['user'] ?>"
                                   data-task-mail="<?= $task['mail'] ?>"
                                   data-task-description="<?= $task['description'] ?>"
                                   data-task-status="<?= $task['status'] ?>"
                                   data-toggle="modal"
                                   data-target="#task-modal"
                                >
                                    <i class="fa fa-pencil-square-o" aria-hidden="true" title="Редактировать"></i>
                                </a>
                            <? } ?>
                        </td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>
<nav>
    <ul class="pagination">
        <? for ($i = 1; $i <= $countPages; $i++) { ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>" aria-current="page">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <? } ?>
    </ul>
</nav>

<script>
    $(function () {
        $('#task-modal').on('show.bs.modal', function (e) {
            let link = $(e.relatedTarget);
            let modal = $(this);

            if (link.is('.edit')) {
                let taskId = link.data('task-id');
                let taskUser = link.data('task-user');
                let taskMail = link.data('task-mail');
                let taskDescription = link.data('task-description');
                let taskStatus = link.data('task-status');

                modal.find('h5').text('Редактировать задачу');
                modal.find('form').attr('action', 'index/edit/' + taskId);

                modal.find('[name="user"]').val(taskUser);
                modal.find('[name="mail"]').val(taskMail);
                modal.find('[name="description"]').val(taskDescription);
                modal.find('.status').removeClass('d-none');


                modal.find('[name="status"]').prop('checked', taskStatus === 1);

            } else {
                modal.find('h5').text('Добавить задачу');
                modal.find('form').attr('action', 'index/new/');

                modal.find('.status').addClass('d-none');

                modal.find('[name="user"]').val('');
                modal.find('[name="mail"]').val('');
                modal.find('[name="description"]').val('');
            }

        });
    });
</script>