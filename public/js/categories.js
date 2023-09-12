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
            nameCell.setAttribute("id", "categoryNameCell");
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
            editButton.setAttribute("data-bs-target", "#editCategoryModal");
            editButton.setAttribute("data-bs-toggle", "modal");
            editButton.addEventListener("click", function () {
                editCategory(item.id);
            });
            actionCell.appendChild(editButton);

            let deleteButton = document.createElement("button");
            deleteButton.textContent = "Удалить";
            deleteButton.className = "btn btn-danger btn-sm";
            deleteButton.addEventListener("click", function () {
                deleteCategory(item.id);
            });
            actionCell.appendChild(deleteButton);

            row.appendChild(actionCell);

            tbody.appendChild(row);

            let switchery = new Switchery(checkbox, {
                size: "small"
            });

            checkbox.addEventListener("change", function () {
                const isChecked = checkbox.checked;
                console.log("Switch state changed: " + (isChecked ? "active" : "inactive"));
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

    function deleteCategory(categoryId) {
        let confirmDelete = confirm("Вы уверены, что хотите удалить эту категорию?");

        if (confirmDelete) {
            const url = 'http://127.0.0.1:8000/api/categories/' + categoryId;

            fetch(url, {
                method: 'DELETE',
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    if (response.status === 204) {
                        console.log('Категория успешно удалена.');
                        const row = document.querySelector('tr[data-id="' + categoryId + '"]');
                        if (row) {
                            row.remove();
                            refreshTable();
                        }
                    } else {
                        console.error('Неожиданный ответ от сервера:', response.status);
                    }
                })
                .catch((error) => {
                    console.error('Произошла ошибка при удалении категории:', error);
                });
        }
    }

    function updateOrder() {
        const tbody = document.getElementById("dataTableBody");
        const rows = tbody.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const categoryId = row.getAttribute("data-id");
            const order = i + 1;

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

    function editCategory(categoryId) {
        const categoryData = {
            id: categoryId,
            name: document.getElementById("categoryNameCell").textContent,
            active: true
        };

        document.getElementById("editCategoryId").value = categoryData.id;
        document.getElementById("editCategoryName").value = categoryData.name;

        let editModal = document.getElementById('editCategoryModal');
        if (editModal) {
            editModal.style.display = 'block';
        }

        let saveEditButton = document.getElementById('saveEditButton');
        if (saveEditButton) {
            saveEditButton.addEventListener('click', function () {
                const editedCategoryData = {
                    id: document.getElementById("editCategoryId").value,
                    name: document.getElementById("editCategoryName").value,
                };

                updateCategory(editedCategoryData);
            });
        }
    }

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
                refreshTable();
            })
            .catch((error) => {
                console.error('Произошла ошибка при сохранении категории:', error);
            });
    }

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
                refreshTable();
            })
            .catch((error) => {
                console.error('Произошла ошибка при сохранении категории:', error);
            });
    }

    function loadDataFromServer() {
        fetch("http://127.0.0.1:8000/api/categories")
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function (data) {
                createTableRows(data.data);
            })
            .catch(function (error) {
                console.error("Произошла ошибка при загрузке JSON: " + error.message);
            });
    }

    function refreshTable() {
        let tbody = document.getElementById("dataTableBody");
        tbody.innerHTML = "";
        loadDataFromServer();
    }

    loadDataFromServer();

    let saveAddButton = document.getElementById('saveAddButton');
    if (saveAddButton) {
        saveAddButton.addEventListener('click', function () {
            const newCategoryData = {
                name: document.getElementById("addCategoryName").value,
            };

            saveCategory(newCategoryData);
        });
    }
});
