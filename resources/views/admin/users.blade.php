@extends('admin.homeAdmin')

@section('content')

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Добавить пользователя</button>

    <table id="sortableTable" class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Активен</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody id="dataTableBody">
        <!-- Здесь будут добавляться строки из JavaScript -->
        </tbody>
    </table>

    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Добавить пользователя</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="saveUserButton" data-bs-dismiss="modal">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Редактировать пользователя</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <div class="form-group">
                            <label for="editName">Имя</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Пароль</label>
                            <input type="password" class="form-control" id="editPassword" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="updateUserButton">Обновить</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/users.js') }}" defer></script>

@endsection
