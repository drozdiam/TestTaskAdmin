@extends('admin.homeAdmin')

@section('content')

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Добавить новую статью</button>
    <table id="sortableTable" class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Текст</th>
            <th>Слаг</th>
            <th>Изображение</th>
            <th>Активен</th>
            <th>Дата создания</th>
            <th>Дата изменения</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody id="dataTableBody">
        </tbody>
    </table>
    <div class="pagination">
        <button type="button" id="prevPageButton" class="btn btn-secondary">Предыдущая страница</button>
        <button type="button" id="nextPageButton" class="btn btn-secondary">Следующая страница</button>
    </div>

    <!-- Добавление статьи -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Добавить статью</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addArticleForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="addArticleSlug">Slug:</label>
                            <input type="text" class="form-control" id="addArticleSlug" placeholder="Введите slug">
                        </div>
                        <div class="form-group">
                            <label for="addArticleName">Название:</label>
                            <input type="text" class="form-control" id="addArticleName" placeholder="Введите название">
                        </div>
                        <div class="form-group">
                            <label for="addArticleCategory">Категория:</label>
                            <input type="text" class="form-control" id="addArticleCategory" placeholder="Введите категорию">
                        </div>
                        <div class="form-group">
                            <label for="addArticleText">Текст:</label>
                            <textarea class="form-control" id="addArticleText" name="addArticleText" placeholder="Введите текст"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="addArticleImage">Изображение:</label>
                            <input type="file" class="form-control-file" id="addArticleImage" accept="image/*">
                        </div>
                        <div class="form-group">
                            <img id="addArticleImagePreview" src="" alt="Предварительное изображение" width="100">
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

    <!-- Редактирования статьи -->
    <div class="modal fade" id="editArticleModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editArticleModalLabel">Редактировать статью</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editArticleForm" enctype="multipart/form-data">
                        <input type="hidden" id="editArticleId" name="editArticleId" value="">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="editArticleSlug">Slug:</label>
                                <input type="text" class="form-control" id="editArticleSlug" placeholder="Введите slug">
                            </div>
                            <label for="editArticleName">Название:</label>
                            <input type="text" class="form-control" id="editArticleName" placeholder="Введите название">
                        </div>
                        <div class="form-group">
                            <label for="editArticleCategory">Категория:</label>
                            <input type="text" class="form-control" id="editArticleCategory" placeholder="Введите категорию">
                        </div>
                        <div class="form-group">
                            <label for="editArticleText">Текст:</label>
                            <textarea class="form-control" id="editArticleText" name="editArticleText" placeholder="Введите текст"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editArticleImage">Изображение:</label>
                            <input type="file" class="form-control-file" id="editArticleImage" accept="image/*">
                        </div>
                        <div class="form-group">
                            <img id="editArticleImagePreview" src="" alt="Предварительное изображение" width="100">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" id="editArticleSaveButton" data-bs-dismiss="modal" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentPage = 1;

            function formatISODate(dateTimeString) {
                const date = new Date(dateTimeString);
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short' };
                return date.toLocaleDateString(undefined, options);
            }

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

                    let categoryCell = document.createElement("td");
                    categoryCell.textContent = item.category_id;
                    row.appendChild(categoryCell);

                    let textCell = document.createElement("td");
                    textCell.innerHTML = item.text;
                    row.appendChild(textCell);

                    let slugCell = document.createElement("td");
                    slugCell.textContent = item.slug;
                    row.appendChild(slugCell);

                    let imageCell = document.createElement("td");
                    let image = document.createElement("img");
                    image.src = "{{ asset('') }}" + item.image;
                    image.width = 50;
                    image.height = 50;
                    imageCell.appendChild(image);
                    row.appendChild(imageCell);

                    let checkboxCell = document.createElement("td");
                    let checkbox = document.createElement("input");
                    checkbox.type = "checkbox";
                    checkbox.checked = item.active === 1;
                    checkbox.className = "js-switch";
                    checkbox.setAttribute("data-size", "small");
                    checkboxCell.appendChild(checkbox);
                    row.appendChild(checkboxCell);

                    let orderCell = document.createElement("td");
                    orderCell.textContent = item.order;
                    orderCell.hidden = true;
                    row.appendChild(orderCell);

                    let createdCell = document.createElement("td");
                    createdCell.textContent = formatISODate(item.created_at); // Форматируем дату создания
                    row.appendChild(createdCell);

                    let updatedCell = document.createElement("td");
                    updatedCell.textContent = formatISODate(item.updated_at); // Форматируем дату изменения
                    row.appendChild(updatedCell);

                    let actionCell = document.createElement("td");
                    let editButton = document.createElement("button");
                    editButton.textContent = "Редактировать";
                    editButton.className = "btn btn-warning btn-sm";
                    editButton.setAttribute("data-bs-target", "#editArticleModal");
                    editButton.setAttribute("data-bs-toggle", "modal");
                    editButton.addEventListener("click", function () {
                        editArticle(item.id, item.name, item.category_id, item.text, item.image, item.active, item.order, item.slug);
                    });
                    actionCell.appendChild(editButton);

                    let deleteButton = document.createElement("button");
                    deleteButton.textContent = "Удалить";
                    deleteButton.className = "btn btn-danger btn-sm";
                    deleteButton.addEventListener("click", function () {
                        deleteArticle(item.id);
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
                        sendToggleStatus(item.id, isChecked, item.name, item.slug, item.category_id);
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
                        updateOrder(); // Вызываем функцию обновления порядка после перемещения строки
                    }
                });
            }

            // Функция для отправки состояния чекбокса и name на сервер
            function sendToggleStatus(articleId, status, name, slug, category_id) {
                const url = 'http://127.0.0.1:8000/api/articles/' + articleId;

                fetch(url, {
                    method: 'POST',
                    body: JSON.stringify({ _method: "PUT", active: status, name: name, category_id: category_id }),
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
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }

            // Функция для получения и обновления данных из сервера
            function updateData(page) {
                fetch('http://127.0.0.1:8000/api/articles?page=' + page)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        // Очищаем таблицу перед обновлением
                        let tbody = document.getElementById("dataTableBody");
                        while (tbody.firstChild) {
                            tbody.removeChild(tbody.firstChild);
                        }

                        // Создаем строки таблицы с данными из JSON
                        createTableRows(data.data);

                        // Обновляем текущую страницу
                        currentPage = page;
                    })
                    .catch(function (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }

            function updateOrder(page) {
                const tbody = document.getElementById("dataTableBody");
                const rows = tbody.getElementsByTagName("tr");

                const firstRow = rows[0];
                const firstOrderCell = firstRow.querySelector("td:nth-child(8)");
                const initialOrder = parseInt(firstOrderCell.textContent, 10);

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const articleId = row.getAttribute("data-id");
                    const orderCell = row.querySelector("td:nth-child(8)");

                    const order = i + initialOrder;

                    const updateOrderUrl = 'http://127.0.0.1:8000/api/articles/' + articleId;

                    fetch(updateOrderUrl, {
                        method: 'POST',
                        body: JSON.stringify({ _method: "PUT", order: order }),
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
                            // orderCell.textContent = order;
                        })
                        .catch((error) => {
                            console.error('Произошла ошибка при обновлении порядка:', error);
                        });
                }
            }

            tinymce.init({
                selector: '#editArticleText',
                plugins: 'autoresize',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                autoresize_bottom_margin: 20,
                height: 300,
                formats: {
                    bold: { inline: 'b' },
                    italic: { inline: 'i' },
                    underline: { inline: 'u' },
                },
                style_formats: [
                    { title: 'Заголовок 1', format: 'h1' },
                    { title: 'Заголовок 2', format: 'h2' },
                ],
            });

            function editArticle(id, name, category_id, text, image, active, order, slug) {
                document.getElementById("editArticleId").value = id;
                document.getElementById("editArticleSlug").value = slug;
                document.getElementById("editArticleName").value = name;
                document.getElementById("editArticleCategory").value = category_id;
                document.getElementById("editArticleText").value = text;

                let imageInput = document.getElementById("editArticleImage");
                let imagePreview = document.getElementById("editArticleImagePreview");

                if (image) {
                    imagePreview.src = "{{ asset('') }}" + image;
                } else {
                    imagePreview.src = "";
                }

                let checkbox = document.querySelector("#editArticleModal .js-switch");
                if (checkbox) {
                    checkbox.checked = active === 1;
                }
                tinymce.get("editArticleText").setContent(text);

                let saveButton = document.getElementById("editArticleSaveButton");
                saveButton.addEventListener("click", function () {
                    saveEditedArticle();
                });
            }

            function saveEditedArticle() {
                let id = document.getElementById("editArticleId").value;
                let name = document.getElementById("editArticleName").value;
                let category_id = document.getElementById("editArticleCategory").value;
                let text = tinymce.get("editArticleText").getContent();
                let imageInput = document.getElementById("editArticleImage");
                let image = imageInput.files[0];
                let slug = document.getElementById("editArticleSlug").value;

                let formData = new FormData();
                formData.append("_method", "PUT");
                formData.append("name", name);
                formData.append("category_id", category_id);
                formData.append("text", text);
                formData.append("slug", slug);
                if (image) {
                    formData.append("image", image);
                }

                let options = {
                    method: 'POST',
                    body: formData,
                    headers: {},
                };

                let url = 'http://127.0.0.1:8000/api/articles/' + id;

                fetch(url, options)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        updateData(currentPage);
                    })
                    .catch((error) => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }

            function changePage(page) {
                updateData(page);
            }

            document.getElementById("prevPageButton").addEventListener("click", function () {
                if (currentPage > 1) {
                    changePage(currentPage - 1);
                }
            });

            document.getElementById("nextPageButton").addEventListener("click", function () {
                changePage(currentPage + 1);
            });

            let addArticleImageInput = document.getElementById("addArticleImage");
            let addArticleImagePreview = document.getElementById("addArticleImagePreview");

            addArticleImageInput.addEventListener("change", function () {
                let file = addArticleImageInput.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        addArticleImagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    addArticleImagePreview.src = "";
                }
            });

            let editArticleImageInput = document.getElementById("editArticleImage");
            editArticleImageInput.addEventListener("change", function () {
                let editArticleImagePreview = document.getElementById("editArticleImagePreview");
                let file = editArticleImageInput.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        editArticleImagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    editArticleImagePreview.src = "";
                }
            });

            updateData(currentPage);

            tinymce.init({
                selector: '#addArticleText',
                plugins: 'autoresize',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                autoresize_bottom_margin: 20,
                height: 300,
                formats: {
                    bold: { inline: 'b' },
                    italic: { inline: 'i' },
                    underline: { inline: 'u' },
                },
                style_formats: [
                    { title: 'Заголовок 1', format: 'h1' },
                    { title: 'Заголовок 2', format: 'h2' },
                ],
            });

            document.getElementById("saveAddButton").addEventListener("click", function () {
                saveNewArticle();
            });

            function saveNewArticle() {
                let slug = document.getElementById("addArticleSlug").value;
                let name = document.getElementById("addArticleName").value;
                let category_id = document.getElementById("addArticleCategory").value;
                let text = tinymce.get("addArticleText").getContent();
                let imageInput = document.getElementById("addArticleImage");
                let image = imageInput.files[0];

                let formData = new FormData();
                formData.append("slug", slug);
                formData.append("name", name);
                formData.append("category_id", category_id);
                formData.append("text", text);
                if (image) {
                    formData.append("image", image);
                }

                let options = {
                    method: 'POST',
                    body: formData,
                    headers: {},
                };

                let url = 'http://127.0.0.1:8000/api/articles';

                fetch(url, options)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        updateData(currentPage);
                    })
                    .catch((error) => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }
        });
    </script>


@endsection
