<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Поставлені задачі</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- Add custom styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
           
        }
        main{
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height:100vh;
            align-items: center;
        }
        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }

        #task-board {
            width: 100%;
            max-width: 90%;
            border-radius: 10px;
            /* padding: 30px; */
            display: flex  ;
	        flex-direction: column;
        }

        #task-summary {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
            text-align: center;
        }
        .add-task-wrapper{
            width: 100%;
            background:#fff;
            margin:1rem auto;
            display: flex;
            justify-content: center;
            position: sticky;
            top:0;
        }
        #addTask{
            width: 100%;
            max-width: 200px;
            padding: 10px;
            margin:1rem 0;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        #task-list {
            display: flex;
            /* flex-direction: column; */
            gap: 1rem;
            width: 100%;
            justify-content: center;
        }
        @media(max-width:768px){
            #task-list{
                flex-direction:column;
            }
        }
        .task-column {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .task-column h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .task-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-card:hover {
            transform: translateY(-5px);
        }

        .task-card h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #333;
        }

        .task-card p {
            font-size: 1rem;
            color: #777;
            margin-bottom: 10px;
        }

        .task-card .due-date {
            font-weight: bold;
            color: #999;
        }

        .task-card .status {
            font-weight: bold;
            color: #f44336;
            text-transform: uppercase;
        }

        .task-card .status.completed {
            color: #4CAF50;
        }

        .task-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .task-actions button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .task-actions button:hover {
            background-color: #0056b3;
        }

       /* Styling for both #task-form and #edit-form */
        #task-form,
        #edit-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 30px;
        }

        #task-form h2,
        #edit-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        #task-form input,
        #task-form textarea,
        #task-form select,
        #task-form button,
        #edit-form input,
        #edit-form textarea,
        #edit-form select,
        #edit-form button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #task-form button:not(.fancybox-close-small),
        #edit-form button:not(.fancybox-close-small) {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        #task-form button:hover:not(.fancybox-close-small),
        #edit-form button:hover:not(.fancybox-close-small) {
            background-color: #218838;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
        .left-side{
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
        }
        .right-side{
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
        }
        .navigation{
            display: flex;
            gap:1rem; 
            padding: 2rem;
        }
    </style>
</head>

<body>
    <main>

        <!-- Include the navigation -->
        @include('layouts.navigation')
        <h1>Поставлені задачі</h1>

        <div id="task-board">
            <div id="task-summary">Виконано задач: <span>{{$total_completed}}/{{$total}}</span></div>
            <div id="no-tasks-message" style="display: none; text-align: center; font-size: 1.2rem; color: #999;">
                    Немає завдань для відображення
                </div>
            <div class="add-task-wrapper">
                <button id="addTask">Створити завдання</button>
            </div>

            <section id="task-list">
                <!-- Not Completed Tasks Column -->
                <div class="task-column" id="not-completed-tasks" style="display: none;">
                </div>
                
                <!-- Completed Tasks Column -->
                <div class="task-column" id="completed-tasks" style="display: none;">
                </div>

              
        
            </section>

            <div id="task-form" style="display: none;">
                <h2>Додати завдання</h2>
                <form id="create-task-form">
                    <label for="task-title">Назва:</label>
                    <input type="text" id="task-title" name="title" required>

                    <label for="task-desc">Опис:</label>
                    <textarea id="task-desc" name="description" required></textarea>

                    <label for="task-date">Дедлайн:</label>
                    <input type="date" id="task-date" name="due_date" required>

                    <label for="task-status">Статус:</label>
                    <select id="task-status" name="is_completed">
                        <option value="0">Не виконано</option>
                        <option value="1">Виконано</option>
                    </select>

                    <button type="submit">Зберігти</button>
                </form>
            </div>
            <div id="edit-form" style="display: none;">
                <h2>Додати завдання</h2>
                <form id="edit-task-form">
                    <label for="task-title">Назва:</label>
                    <input type="text" id="task-title" name="title" required>

                    <label for="task-desc">Опис:</label>
                    <textarea id="task-desc" name="description" required></textarea>

                    <label for="task-date">Дедлайн:</label>
                    <input type="date" id="task-date" name="due_date" required>

                    <label for="task-status">Статус:</label>
                    <select id="task-status" name="is_completed">
                        <option value="0">Не виконано</option>
                        <option value="1">Виконано</option>
                    </select>

                    <button type="submit">Зберігти</button>
                </form>
            </div>
        </div>

        </main>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
        <script>
            $(document).ready(function() {

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                $('[data-fancybox="task-form"]').fancybox();

                $('#addTask').on('click', function() {
                    $.fancybox.open({
                        src  : '#task-form', 
                        type : 'inline',    
                        opts : {
                            afterShow : function() {
                                console.log('Task form opened');
                            }
                        }
                    });
                });

                function loadTasks() {
                    $.ajax({
                        url: '/tasks/get',
                        method: 'GET',
                        success: function(tasks) {
                            let completedTasks = '';
                            let notCompletedTasks = '';

                            if (tasks.length === 0) {
                                $('#task-list').hide(); 
                                $('#no-tasks-message').show();
                                $('#completed-tasks').hide(); 
                                $('#not-completed-tasks').hide(); 
                            } else {
                                $('#task-list').show(); 
                                $('#no-tasks-message').hide(); 
                                $('#completed-tasks').show(); 
                                $('#not-completed-tasks').show(); 
                                
                                tasks.forEach(task => {
                                    let taskCard = `
                                        <div class="task-card">
                                            <div class="left-side">
                                                <p class="due-date">Дедлайн: ${task.due_date}</p>
                                                <h3>${task.title}</h3>
                                                <p>${task.description}</p>
                                            </div>
                                            <div class="right-side">
                                                <p class="status ${task.is_completed ? 'completed' : ''}">
                                                    ${task.is_completed ? 'Виконано' : 'Не виконано'}
                                                </p>
                                                <div class="task-actions">
                                                    <button onclick="editTask(${task.id})">Edit</button>
                                                    <button onclick="deleteTask(${task.id})">Delete</button>
                                                </div>
                                            </div>
                                        </div>`;

                                    if (task.is_completed) {
                                        completedTasks += taskCard;
                                    } else {
                                        notCompletedTasks += taskCard;
                                    }
                                });

                                $('#completed-tasks').html(completedTasks);
                                $('#not-completed-tasks').html(notCompletedTasks);
                                updateTaskSummary(tasks);
                            }
                        }
                    });
                }

                $('#create-task-form').on('submit', function(event) {
                    event.preventDefault();
                    const taskData = $(this).serialize();

                    $.ajax({
                        url: '/tasks/add',
                        method: 'POST',
                        data: taskData,
                        success: function() {
                            loadTasks();
                            $.fancybox.close();
                            $('#create-task-form')[0].reset();
                        }
                    });
                });

                window.deleteTask = function(id) {
                    $.ajax({
                        url: `/tasks/remove/${id}`,
                        method: 'DELETE',
                        success: function() {
                            loadTasks();
                        }
                    });
                }

                window.editTask = function(id) {
    $.ajax({
        url: `/task/get/${id}`,
        method: 'GET',
        success: function(task) {

            $('#edit-task-form #task-title').val(task.title);
            $('#edit-task-form #task-desc').val(task.description);
            $('#edit-task-form #task-date').val(task.due_date);
            $('#edit-task-form #task-status').val(task.is_completed ? 1 : 0);

            $('#edit-task-form').off('submit').on('submit', function(event) {
                event.preventDefault();

                const updatedTaskData = $(this).serialize(); 

                $.ajax({
                    url: `/tasks/update/${id}`,
                    method: 'PUT',
                    data: updatedTaskData, 
                    success: function() {
                        loadTasks();  
                        $.fancybox.close(); 
                    }
                });
            });

            $.fancybox.open({
                src: '#edit-form',
                type: 'inline',
                opts: {
                    afterShow: function() {
                        console.log('Edit form opened');
                    }
                }
            });
        },
        error: function(xhr, status, error) {

            console.log('Error fetching task:', error);
        }
    });
};



                function updateTaskSummary(tasks) {
                    const totalTasks = tasks.length;
                    const completedTasks = tasks.filter(task => task.is_completed).length;
                    $('#task-summary span').text(`${completedTasks}/${totalTasks}`);
                }

                loadTasks(); 
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
        />
    </body>
</html>
