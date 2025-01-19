<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    public function index(Request $request)
    {

        $tasks = Task::query();
    
        if ($request->has('status')) {
            $tasks->where('is_completed', $request->status);
        }
    
        if ($request->has('title')) {
            $tasks->where('title', 'like', '%' . $request->title . '%');
        }
    
        if ($request->has('id')) {
            $tasks->where('id', $request->id);
        }
    
        $tasks = $tasks->get();

        $data = [
            'tasks' => $tasks,
            'total' => $tasks->count(),
            'total_completed' => $tasks->where('is_completed', true)->count(),
        ];
    
        return view('tasks.list', $data);
    }

    public function get(Request $request)
    {
        $tasks = Task::query();

        if ($request->has('status')) {
            $tasks->where('is_completed', $request->status);
        }

        if ($request->has('title')) {
            $tasks->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('id')) {
            $tasks->where('id', $request->id);
        }

        $tasks = $tasks->get();

        return response()->json($tasks);
    }


    public function getById($id){

        $task = Task::findOrFail($id);

        return response()->json($task);
    }

    public function store(Request $request)
    {

        $userId = Auth::id(); 
        $taskData = $request->all();
        $taskData['user_id'] = $userId;
        
        $task = Task::create($taskData);
    
        if ($task) {
            $this->sendTelegramNotification($task);
        }
    
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    private function sendTelegramNotification(Task $task)
    {

        $user = User::find($task->user_id);

        $username = $user ? $user->username : 'Unknown User'; 

        $botToken = env('TG_BOT_TOKEN');
        $chatId = env('TG_CHAT_ID');
        $message = "Нова задача: " . $task->title;
        $message .= "\nОпис: " . $task->description;
        $message .= "\nСтатус: " . ($task->is_completed ? 'Виконано' : 'Не виконано');
        $message .= "\nДeдлайн: " . $task->due_date;
        $message .= "\nСтворив: " . $username;  

        $url = "https://api.telegram.org/bot$botToken/sendMessage";

        $postData = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    
}
