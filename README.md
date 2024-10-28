# Управвление задачами

- POST api/tasks - добавление задачи
- GET api/tasks - список задач с пагинацией, фильтр finished=true|false, сортировка sort=name|finished_time
- GET api/tasks/{id} - данные по одной задаче
- PUT api/tasks/{id} - полное обновление задачи
- PATCH api/tasks/{id} - частичное обновление задачи
- DELETE api/tasks/{id} - удаление задачи

- Тесты в test/Feature/ApiTest.php
