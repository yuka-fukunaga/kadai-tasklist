<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ログインしているかどうかをチェックして、ログイン中の場合、ログインしている人のtasksを取得
        
        
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // ログインしてなかったら、welcomeか、ログインしてください内容のviewを表示
        
         return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', [
            'task' => $task,
        ]);
        
        /*
        if (\Auth::id() === $task->user_id) {
            return view('tasks.create', [
            'task' => $task,
        ]);
        
        }else{
        
            return redirect('/');
        }
        */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',   
            'content' => 'required|max:191',
        ]);
        
        $task = new Task;
        $task->user_id = \Auth::id();
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        return redirect('/');
        
        $request->user()->tasks()->create([
            'content' => $request->content,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
        ]);
        
        }else{
        
            return redirect('/');
        }

       
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

       if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
        ]);
        
        }else{
        
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:10',   
            'content' => 'required|max:191',
        ]);
        
        $task = Task::find($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        
         if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        return redirect('/');
    }
}
