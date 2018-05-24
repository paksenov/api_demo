## Сущности ##

### Пользователи (Users)

* users.id
*  users.email
*  users.fio
*  users.role (1- клиент, 2 - исполнитель)
*  users.created_at - дата создания пользователя (автоматически в момент добавления) 
* users.updated_at - дата обновления пользователя (автоматически в момент обновления)  

Обязательные поля 

* при создании: email, fio, role 
* при обновлении: id, email, fio, role 

### Задачи (Tasks).

Задачи при создании закрепляется за клиентом. Для упрощения данной сущности в ней есть только еще дно поле name (название задачи)  

* tasks.id 
* tasks.name 
* tasks.user_id (user.id, role = 1 (клиент)
*  tasks.created_at - дата создания задачи (автоматически в момент добавления)  

Обязательные поля 

* при создании : name, user_id

  Перед созданием, указанные: 

* user_id проверяется на существование пользователя
 * user_id, что пользователь является клиентом (role = 1) 

 Не назначенные задачи можно удалять, предусмотрен соответствующий метод. 

### Назначенные задачи (AssignTasks)

Связь задач клиентов с исполнителями. 

*   assigned_tasks.id 
* assigned_tasks.task_id
*  assigned_tasks.user_id (user.id, role = 2 (исполнитель)
*  assigned_tasks.comment
*  assigned_tasks.created_at - дата назначения задачи исполнителю (автоматически в момент добавления)  

Обязательные поля 

* при создании : task_id, user_id  

Перед созданием, указанные: 

* user_id проверяется на существование пользователя
 * user_id, что пользователь является исполнителем (role = 2)
 * task_id на существование задачи

## API 

Список методов и их назначения. Более подробно с параметрами вызовов они описаны в postman-е

https://documenter.getpostman.com/view/812173/apidemo/2SMWyb

### /Users

* GET /Users - список пользователей
* GET /Users/{id:int} - пользователь по id
* GET /Users/clients - список клиентов
* GET /Users/clients - список исполнителей
* GET /Users/search?email={email:string} - поиск пользователя по email
* POST /Users - создание нового пользователя
* PUT /Users - обновление существующего пользователя 

### /Tasks

* GET /Tasks - список задач
* GET /Tasks/user/{id:int} - задачи клиента
* POST /Tasks - создание задачи
* DELETE /Tasks - удаление задачи

### /AssignTasks

* GET /AssignTasks - назначенные исполнителям задачи
* GET /AssignTasks{id:int} - назначенные, указанному исполнителю, задачи
* POST /AssignTasks - назначение задачи исполнителю
* DELETE /AssignTasks - удаление назначенной задачи