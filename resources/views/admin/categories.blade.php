@extends('admin.homeAdmin')

@section('content')
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
    <!-- Здесь будут добавляться строки из JavaScript -->
    </tbody>
</table>

<!-- Кнопка для добавления новой категории -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Добавить категорию</button>

<!-- Модальное окно для добавления категории -->
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

<!-- Модальное окно для редактирования категории -->
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
                    <div class="form-group">
                        <label for="editCategoryId">ID:</label>
                        <input type="text" class="form-control" id="editCategoryId" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editCategoryName">Название:</label>
                        <input type="text" class="form-control" id="editCategoryName" placeholder="Введите название">
                    </div>
                    <div class="form-group">
                        <label for="editCategoryOrder">Порядок:</label>
                        <input type="number" class="form-control" id="editCategoryOrder" placeholder="Введите порядок">
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

<!-- Подключение библиотеки Switchery и JavaScript-кода -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Функция для создания строк таблицы из данных JSON
        function createTableRows(data) {
            var tbody = document.getElementById("dataTableBody");

            data.forEach(function (item) {
                var row = document.createElement("tr");
                row.setAttribute("data-id", item.id); // Устанавливаем data-id для строки

                // Создание ячейки для ID
                var idCell = document.createElement("td");
                idCell.textContent = item.id;
                row.appendChild(idCell);

                // Создание ячейки для Названия
                var nameCell = document.createElement("td");
                nameCell.textContent = item.name;
                nameCell.setAttribute("id", "categoryNameCell");
                row.appendChild(nameCell);

                // Создание ячейки для чекбокса
                var checkboxCell = document.createElement("td");
                var checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.checked = item.active === 1;
                checkbox.className = "js-switch";
                checkbox.setAttribute("data-size", "small");
                checkboxCell.appendChild(checkbox);
                row.appendChild(checkboxCell);

                // Создание ячейки для кнопок "Редактировать" и "Удалить"
                var actionCell = document.createElement("td");
                var editButton = document.createElement("button");
                editButton.textContent = "Редактировать";
                editButton.className = "btn btn-warning btn-sm";
                editButton.setAttribute("data-bs-target", "#editCategoryModal");
                editButton.setAttribute("data-bs-toggle", "modal");
                editButton.addEventListener("click", function () {
                    editCategory(item.id);
                });
                actionCell.appendChild(editButton);

                var deleteButton = document.createElement("button");
                deleteButton.textContent = "Удалить";
                deleteButton.className = "btn btn-danger btn-sm";
                deleteButton.addEventListener("click", function () {
                    deleteCategory(item.id);
                });
                actionCell.appendChild(deleteButton);

                row.appendChild(actionCell);

                tbody.appendChild(row);

                // Инициализация Switchery для каждого чекбокса
                var switchery = new Switchery(checkbox, {
                    size: "small"
                });

                // Обработчик события изменения состояния чекбокса
                checkbox.addEventListener("change", function () {
                    const isChecked = checkbox.checked;
                    console.log("Switch state changed: " + (isChecked ? "active" : "inactive"));

                    // Отправляем состояние чекбокса и name на сервер
                    sendToggleStatus(item.id, isChecked, item.name);

                    // Добавляем сообщение
                    if (isChecked) {
                        console.log("Чекбокс включен!");
                    } else {
                        console.log("Чекбокс выключен!");
                    }
                });
            });

            // Инициализация сортировки таблицы с помощью SortableJS
            var sortableTable = new Sortable(document.querySelector('#sortableTable tbody'), {
                animation: 150, // анимация перемещения
                handle: 'td', // элемент, который будет использоваться для перетаскивания строки
                onEnd: function (evt) {
                    // Обработчик события окончания перемещения строки
                    updateOrder();
                }
            });
        }

        // Функция для отправки состояния чекбокса и name на сервер
        function sendToggleStatus(categoryId, status, name) {
            const url = 'http://127.0.0.1:8000/api/categories/' + categoryId;

            fetch(url, {
                method: 'POST',
                body: JSON.stringify({ _method: "PUT", active: status, name: name }),
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log('Server response:', data);
                })
                .catch((error) => {
                    console.error('Произошла ошибка при отправке запроса на сервер:', error);
                });
        }

        // Функция для удаления категории по ID
        function deleteCategory(categoryId) {
            const url = 'http://127.0.0.1:8000/api/categories/' + categoryId;

            fetch(url, {
                method: 'DELETE',
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // Проверяем, что ответ не пустой
                    if (response.status === 204) {
                        // Успешное удаление без содержимого
                        console.log('Категория успешно удалена.');
                        // Удалить строку из таблицы
                        const row = document.querySelector('tr[data-id="' + categoryId + '"]');
                        if (row) {
                            row.remove();
                            // Обновить таблицу после удаления
                            refreshTable();
                        }
                    } else {
                        // Неожиданный ответ
                        console.error('Неожиданный ответ от сервера:', response.status);
                    }
                })
                .catch((error) => {
                    console.error('Произошла ошибка при удалении категории:', error);
                });
        }

        // Функция для обновления порядка на сервере
        function updateOrder() {
            const tbody = document.getElementById("dataTableBody");
            const rows = tbody.getElementsByTagName("tr");

            // Пройдемся по каждой строке и отправим запрос на сервер для обновления порядка
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const categoryId = row.getAttribute("data-id");
                const order = i + 1; // Новый порядок будет соответствовать текущему индексу + 1

                // Отправим запрос на сервер для обновления порядка категории
                const updateOrderUrl = 'http://127.0.0.1:8000/api/categories/' + categoryId;

                fetch(updateOrderUrl, {
                    method: 'POST',
                    body: JSON.stringify({_method: "PUT", order: order }),
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Порядок обновлен для категории с ID ' + categoryId + '. Ответ сервера:', data);
                    })
                    .catch((error) => {
                        console.error('Произошла ошибка при обновлении порядка:', error);
                    });
            }
        }

        // Функция для редактирования категории
        function editCategory(categoryId) {
            // Получаем данные о категории с сервера (вам нужно реализовать эту логику на сервере)
            const categoryData = {
                id: categoryId, // ID категории
                name: document.getElementById("categoryNameCell").textContent, // Здесь мы получаем значение из ячейки "Название"
                active: true // Активность
            };

            // Заполняем форму редактирования данными о категории
            document.getElementById("editCategoryId").value = categoryData.id;
            document.getElementById("editCategoryName").value = categoryData.name;

            // Открываем модальное окно редактирования
            var editModal = document.getElementById('editCategoryModal');
            if (editModal) {
                editModal.style.display = 'block';
            }

            // Обработчик клика по кнопке "Сохранить изменения"
            var saveEditButton = document.getElementById('saveEditButton');
            if (saveEditButton) {
                saveEditButton.addEventListener('click', function () {
                    const editedCategoryData = {
                        id: document.getElementById("editCategoryId").value,
                        name: document.getElementById("editCategoryName").value,
                    };

                    // Отправляем данные о редактированной категории на сервер
                    updateCategory(editedCategoryData);
                });
            }
        }

// Функция для отправки обновленных данных о категории на сервер
        function updateCategory(categoryData) {
            const url = 'http://127.0.0.1:8000/api/categories/' + categoryData.id;

            fetch(url, {
                method: 'PUT',
                body: JSON.stringify(categoryData),
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log('Категория обновлена. Ответ сервера:', data);
                    // Обновить таблицу после сохранения
                    refreshTable();
                })
                .catch((error) => {
                    console.error('Произошла ошибка при сохранении категории:', error);
                });
        }

            // Функция для сохранения категории на сервере
        function saveCategory(categoryData) {
            const url = 'http://127.0.0.1:8000/api/categories/';

            fetch(url, {
                method: 'POST',
                body: JSON.stringify({ name: categoryData.name }),
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log('Категория обновлена. Ответ сервера:', data);
                    // Обновить таблицу после сохранения
                    refreshTable();
                })
                .catch((error) => {
                    console.error('Произошла ошибка при сохранении категории:', error);
                });
        }

        // Функция для загрузки данных JSON с сервера
        function loadDataFromServer() {
            fetch("http://127.0.0.1:8000/api/categories")
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(function (data) {
                    // Вызываем функцию для создания строк таблицы из данных JSON
                    createTableRows(data.data);
                })
                .catch(function (error) {
                    console.error("Произошла ошибка при загрузке JSON: " + error.message);
                });
        }

        // Функция для обновления таблицы после действий (удаления, добавления, редактирования)
        function refreshTable() {
            // Очищаем таблицу
            var tbody = document.getElementById("dataTableBody");
            tbody.innerHTML = "";
            // Повторно загружаем данные с сервера и пересоздаем строки таблицы
            loadDataFromServer();
        }

        // Загрузка данных при загрузке страницы
        loadDataFromServer();

        // Обработчик клика по кнопке "Сохранить" в модальном окне добавления категории
        var saveAddButton = document.getElementById('saveAddButton');
        if (saveAddButton) {
            saveAddButton.addEventListener('click', function () {
                const newCategoryData = {
                    name: document.getElementById("addCategoryName").value,
                };

                // Отправляем данные о новой категории на сервер
                saveCategory(newCategoryData);

            });
        }
    });
</script>
@endsection
