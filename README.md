
# Інструкція по встановленню та налаштуванню
1. **Перейдіть до папки проєкту:**

   Перейдіть до каталогу проєкту:

   ```bash
   cd шлях/до/папки/проєкту
   ```

2. **Встановлення залежностей:**

   Виконайте команду для встановлення всіх необхідних залежностей за допомогою Composer:

   ```bash
   composer install
   ```

3. **Налаштування файлу `.env`:**

   У файлі `.env` необхідно заповнити дані для підключення до бази даних, а також додати токен для Telegram-бота та ID чату:

   ```bash
   DB_HOST=your_database_host
   DB_PORT=your_database_port
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password

   TG_BOT_TOKEN=your_bot_token
   TG_CHAT_ID=your_chat_id
   ```

4. **Міграція бази даних:**

   Запустіть команду для виконання міграції бази даних:

   ```bash
   php artisan migrate
   ```

---

## API

API доступне через наступні маршрути:

1. **Отримати всі задачі:**
   - **Метод:** `GET`
   - **Маршрут:** `/api/tasks/get`
   - **Опис:** Повертає список всіх задач.
   - **Фільтри:**
     - `status` — Фільтрує задачі за статусом виконання.
       - Приклад: `/api/tasks/get?status=1` (повертає виконані задачі)
     - `title` — Фільтрує задачі за заголовком.
       - Приклад: `/api/tasks/get?title=Meeting` (повертає задачі, заголовок яких містить "Meeting")
     - `id` — Фільтрує задачі за унікальним ідентифікатором.
       - Приклад: `/api/tasks/get?id=5` (повертає задачу з ID 5)


2. **Отримати задачу за ID:**
   - **Метод:** `GET`
   - **Маршрут:** `/api/task/get/{id}`
   - **Опис:** Повертає задачу за вказаним ID.


3. **Додати задачу:**
   - **Метод:** `POST`
   - **Маршрут:** `/api/tasks/add`
   - **Опис:** Додає нову задачу.

4. **Оновити задачу:**
   - **Метод:** `PUT`
   - **Маршрут:** `/api/tasks/update/{id}`
   - **Опис:** Оновлює інформацію про задачу за ID.

5. **Видалити задачу:**
   - **Метод:** `DELETE`
   - **Маршрут:** `/api/tasks/remove/{id}`
   - **Опис:** Видаляє задачу за ID.

---

### Авторизація через Basic Auth:

API використовує авторизацію через **Basic Auth**. Для доступу необхідно передати ім'я користувача та пароль у заголовку запиту. Приклад для Google Apps Script (описано нижче):


- **DEMO:**  https://taskmanager.atpslay.org.ua
- **Ім'я користувача:** test
- **Пароль:** test1234567890

---

## Google Apps Script для роботи із задачами

Цей скрипт дозволяє завантажувати задачі з вашого API та оновлювати їх у Google Sheets.

```javascript
function fetchData() {
  var url = "https://taskmanager.atpslay.org.ua/api/tasks/get"; 

  var username = "test";  
  var password = "test1234567890";  
  
  var headers = {
    "Authorization": "Basic " + Utilities.base64Encode(username + ":" + password)
  };

  var options = {
    "method": "get",
    "headers": headers
  };

  var response = UrlFetchApp.fetch(url, options);
  var tasks = JSON.parse(response.getContentText());

  updateSheet(tasks);
}

function updateSheet(tasks) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  
  sheet.clear();

  sheet.appendRow(["ID", "Назва", "Опис", "Дедлайн", "Статус", "Створив", "Created At", "Updated At"]);

  // Записуємо дані в таблицю
  tasks.forEach(function(task) {
    var status = task.is_completed === 1 ? "Виконано" : "Не виконано";  

    sheet.appendRow([
      task.id, 
      task.title, 
      task.description, 
      task.due_date, 
      status,  
      task.user_name, 
      task.created_at, 
      task.updated_at
    ]);
  });
}

function setUpTrigger() {
  ScriptApp.newTrigger("fetchData")
    .timeBased()
    .everyMinutes(1)
    .create();
}
```

### Опис скрипта:
- **`fetchData()`** — робить запит до API для отримання списку задач.
- **`updateSheet(tasks)`** — оновлює таблицю з задачами в Google Sheets.
- **`setUpTrigger()`** — створює таймер для автоматичного оновлення даних кожну хвилину.

---

Після виконання цих кроків застосунок буде готовий до використання, а задачі — синхронізовані з Google Sheets та інтегровані з Телеграм ботом 
