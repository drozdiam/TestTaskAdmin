@extends('admin.homeAdmin')

@section('content')
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Добавить категорию</button>

<table id="sortableTable" class="table table-bordered">
    <thead class="thead-dark">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Активен</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody id="dataTableBody">
    </tbody>
</table>


<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Добавить категорию</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="addCategoryName">Название:</label>
                        <input type="text" class="form-control" id="addCategoryName" placeholder="Введите название">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" id="saveAddButton" data-bs-dismiss="modal" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Редактировать категорию</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <div class="form-group .d-none">
                        <label for="editCategoryId"></label>
                        <input type="hidden" class="form-control" id="editCategoryId" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editCategoryName">Название:</label>
                        <input type="text" class="form-control" id="editCategoryName" placeholder="Введите название">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" id="saveEditButton" data-bs-dismiss="modal" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('js/categories.js') }}" defer></script>
@endsection
