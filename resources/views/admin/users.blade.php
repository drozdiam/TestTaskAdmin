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

        <!-- Модальное окно для добавления пользователя -->
        <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-primary" id="saveUserButton" data-bs-dismiss="modal">Сохранить
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно для редактирования пользователя -->
        <div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

                        // Создание ячейки для Имени
                        var nameCell = document.createElement("td");
                        nameCell.textContent = item.name;
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
                        editButton.setAttribute("data-bs-toggle", "modal");
                        editButton.setAttribute("data-bs-target", "#editUserModal"); // Указываем модальное окно для редактирования
                        editButton.addEventListener("click", function () {
                            openEditModal(item);
                        });
                        actionCell.appendChild(editButton);

                        var deleteButton = document.createElement("button");
                        deleteButton.textContent = "Удалить";
                        deleteButton.className = "btn btn-danger btn-sm";
                        deleteButton.addEventListener("click", function () {
                            var confirmed = confirm("Вы уверены, что хотите удалить этого пользователя?");
                            if (confirmed) {
                                deleteUser(item.id);
                            }
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
                            console.log("Состояние чекбокса изменено: " + (isChecked ? "активен" : "неактивен"));

                            // Отправляем состояние чекбокса и имя на сервер
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

                // Функция для открытия модального окна редактирования
                function openEditModal(user) {
                    var editModal = document.getElementById("editUserModal");
                    var editNameInput = document.getElementById("editName");
                    var editEmailInput = document.getElementById("editEmail");
                    var editPasswordInput = document.getElementById("editPassword");
                    var updateUserButton = document.getElementById("updateUserButton");

                    // Заполните поля ввода модального окна данными пользователя
                    editNameInput.value = user.name;
                    editEmailInput.value = user.email;
                    editPasswordInput.value = ""; // Оставьте это поле пустым или заполните по необходимости

                    // Откройте модальное окно редактирования с использованием Bootstrap
                    var modal = new bootstrap.Modal(editModal, {
                        backdrop: 'static',
                        keyboard: true
                    });
                    modal.show();

                    // Добавьте обработчик события для кнопки "Обновить"
                    updateUserButton.addEventListener("click", function () {
                        // Получите новые значения из полей ввода
                        var newName = editNameInput.value;
                        var newEmail = editEmailInput.value;
                        var newPassword = editPasswordInput.value;

                        // Выполните обновление пользователя с новыми данными на сервере
                        updateUser(user.id, newName, newEmail, newPassword);

                        // Закройте модальное окно редактирования
                        modal.hide();
                    });
                }

            // Функция для отправки состояния чекбокса и имени на сервер
            function sendToggleStatus(userId, status, name) {
                const url = 'http://127.0.0.1:8000/api/users/' + userId;

                fetch(url, {
                    method: 'POST',
                    body: JSON.stringify({ _method: "PUT", active: status, name: name }),
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Сетевой запрос не удался');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Ответ сервера:', data);
                    })
                    .catch((error) => {
                        console.error('Произошла ошибка при отправке запроса на сервер:', error);
                    });
            }

            // Функция для удаления пользователя по ID
            function deleteUser(userId) {
                const url = 'http://127.0.0.1:8000/api/users/' + userId;

                fetch(url, {
                    method: 'DELETE',
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Сетевой запрос не удался');
                        }
                        // Проверяем, что ответ не пустой
                        if (response.status === 204) {
                            // Успешное удаление без содержимого
                            console.log('Пользователь успешно удален.');
                            // Удалить строку из таблицы
                            const row = document.querySelector('tr[data-id="' + userId + '"]');
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
                        console.error('Произошла ошибка при удалении пользователя:', error);
                    });
            }
            function updateUser(userId, newName, newEmail, newPassword) {
                const url = 'http://127.0.0.1:8000/api/users/' + userId;

                fetch(url, {
                    method: 'POST',
                    body: JSON.stringify({
                        _method: "PUT",
                        name: newName,
                        email: newEmail,
                        password: newPassword
                    }),
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Сетевой запрос не удался');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Ответ сервера:', data);
                        // Обновите таблицу после успешного обновления
                        refreshTable();
                    })
                    .catch((error) => {
                        console.error('Произошла ошибка при отправке запроса на сервер:', error);
                    });
            }

            // Функция для добавления пользователя
            document.getElementById("saveUserButton").addEventListener("click", function () {
                const name = document.getElementById("name").value;
                const email = document.getElementById("email").value;
                const password = document.getElementById("password").value;
                const active = true; // Устанавливаем active в значение true

                const url = 'http://127.0.0.1:8000/api/users';

                fetch(url, {
                    method: 'POST',
                    body: JSON.stringify({ name: name, email: email, password: password, active: active }),
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Сетевой запрос не удался');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Ответ сервера:', data);
                        // Закрыть модальное окно после успешного добавления
                        var addModal = document.getElementById('addModal');

// Закрываем модальное окно
                        addModal.classList.remove('show');
                        addModal.style.display = 'none';

// Очищаем поля ввода
                        document.getElementById("name").value = "";
                        document.getElementById("email").value = "";
                        document.getElementById("password").value = "";
                        // После успешного добавления, обновить таблицу
                        refreshTable();
                    })
                    .catch((error) => {
                        console.error('Произошла ошибка при отправке запроса на сервер:', error);
                    });
            });

            // Функция для обновления порядка на сервере
            function updateOrder() {
                const tbody = document.getElementById("dataTableBody");
                const rows = tbody.getElementsByTagName("tr");

                // Пройдемся по каждой строке и отправим на сервер ее новый порядковый номер
                Array.from(rows).forEach(function (row, index) {
                    const userId = row.getAttribute("data-id");
                    const url = 'http://127.0.0.1:8000/api/users/' + userId;

                    fetch(url, {
                        method: 'POST',
                        body: JSON.stringify({ _method: "PUT", order: index + 1 }),
                        headers: {
                            'Content-Type': 'application/json; charset=UTF-8',
                        },
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Сетевой запрос не удался');
                            }
                            return response.json();
                        })
                        .then((data) => {
                            console.log('Ответ сервера:', data);
                        })
                        .catch((error) => {
                            console.error('Произошла ошибка при отправке запроса на сервер:', error);
                        });
                });
            }

            // Функция для обновления таблицы
            function refreshTable() {
                const url = 'http://127.0.0.1:8000/api/users';

                fetch(url)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Сетевой запрос не удался');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Данные с сервера:', data);
                        // Очистим таблицу
                        const tbody = document.getElementById("dataTableBody");
                        tbody.innerHTML = "";
                        // Создадим строки таблицы с новыми данными
                        createTableRows(data.data);
                    })
                    .catch((error) => {
                        console.error('Произошла ошибка при получении данных с сервера:', error);
                    });
            }

            // Вызовем функцию обновления таблицы при загрузке страницы
            refreshTable();
        });
    </script>

@endsection
