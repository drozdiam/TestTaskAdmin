document.addEventListener("DOMContentLoaded", function () {
    function createTableRows(data) {
        let tbody = document.getElementById("dataTableBody");

        data.forEach(function (item) {
            let row = document.createElement("tr");
            row.setAttribute("data-id", item.id);

            let idCell = document.createElement("td");
            idCell.textContent = item.id;
            row.appendChild(idCell);

            let nameCell = document.createElement("td");
            nameCell.textContent = item.name;
            row.appendChild(nameCell);

            let checkboxCell = document.createElement("td");
            let checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.checked = item.active === 1;
            checkbox.className = "js-switch";
            checkbox.setAttribute("data-size", "small");
            checkboxCell.appendChild(checkbox);
            row.appendChild(checkboxCell);

            let actionCell = document.createElement("td");
            let editButton = document.createElement("button");
            editButton.textContent = "Редактировать";
            editButton.className = "btn btn-warning btn-sm";
            editButton.setAttribute("data-bs-toggle", "modal");
            editButton.setAttribute("data-bs-target", "#editUserModal");
            editButton.addEventListener("click", function () {
                openEditModal(item);
            });
            actionCell.appendChild(editButton);

            let deleteButton = document.createElement("button");
            deleteButton.textContent = "Удалить";
            deleteButton.className = "btn btn-danger btn-sm";
            deleteButton.addEventListener("click", function () {
                let confirmed = confirm("Вы уверены, что хотите удалить этого пользователя?");
                if (confirmed) {
                    deleteUser(item.id);
                }
            });
            actionCell.appendChild(deleteButton);

            row.appendChild(actionCell);

            tbody.appendChild(row);

            let switchery = new Switchery(checkbox, {
                size: "small"
            });

            checkbox.addEventListener("change", function () {
                const isChecked = checkbox.checked;
                console.log("Состояние чекбокса изменено: " + (isChecked ? "активен" : "неактивен"));
                sendToggleStatus(item.id, isChecked, item.name);
                if (isChecked) {
                    console.log("Чекбокс включен!");
                } else {
                    console.log("Чекбокс выключен!");
                }
            });
        });

        let sortableTable = new Sortable(document.querySelector('#sortableTable tbody'), {
            animation: 150,
            handle: 'td',
            onEnd: function (evt) {
                updateOrder();
            }
        });
    }

    function openEditModal(user) {
        let editModal = document.getElementById("editUserModal");
        let editNameInput = document.getElementById("editName");
        let editEmailInput = document.getElementById("editEmail");
        let editPasswordInput = document.getElementById("editPassword");
        let updateUserButton = document.getElementById("updateUserButton");

        editNameInput.value = user.name;
        editEmailInput.value = user.email;
        editPasswordInput.value = "";

        let modal = new bootstrap.Modal(editModal, {
            backdrop: 'static',
            keyboard: true
        });
        modal.show();

        updateUserButton.addEventListener("click", function () {
            let newName = editNameInput.value;
            let newEmail = editEmailInput.value;
            let newPassword = editPasswordInput.value;
            updateUser(user.id, newName, newEmail, newPassword);
            modal.hide();
        });
    }

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

    function deleteUser(userId) {
        const url = 'http://127.0.0.1:8000/api/users/' + userId;

        fetch(url, {
            method: 'DELETE',
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Сетевой запрос не удался');
                }
                if (response.status === 204) {
                    console.log('Пользователь успешно удален.');
                    const row = document.querySelector('tr[data-id="' + userId + '"]');
                    if (row) {
                        row.remove();
                        refreshTable();
                    }
                } else {
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
                refreshTable();
            })
            .catch((error) => {
                console.error('Произошла ошибка при отправке запроса на сервер:', error);
            });
    }

    document.getElementById("saveUserButton").addEventListener("click", function () {
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const active = true;

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
                let addModal = document.getElementById('addModal');
                addModal.classList.remove('show');
                addModal.style.display = 'none';
                document.getElementById("name").value = "";
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
                refreshTable();
            })
            .catch((error) => {
                console.error('Произошла ошибка при отправке запроса на сервер:', error);
            });
    });

    function updateOrder() {
        const tbody = document.getElementById("dataTableBody");
        const rows = tbody.getElementsByTagName("tr");

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
                const tbody = document.getElementById("dataTableBody");
                tbody.innerHTML = "";
                createTableRows(data.data);
            })
            .catch((error) => {
                console.error('Произошла ошибка при получении данных с сервера:', error);
            });
    }

    refreshTable();
});
