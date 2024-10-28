<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('finished') === 'true'){
            $condition = "1";
        }
        else if($request->input('finished') === 'false'){
            $condition = "0";
        }
        if ($request->input('sort') === 'title' || $request->input('sort') === 'finished_time'){
            $sort = $request->input('sort');
        }
        else {
            $sort = 'id';
        }
        if ($request->input('dir') === 'asc'){
            $dir = 'ASC';
        }
        else{
            $dir = 'DESC';
        }
        $tasks = Task::select('id', 'title', 'description', 'finished', 'finished_time')->orderBy($sort, $dir);
        if(isset($condition)) {
            $tasks = $tasks->where('finished', $condition);
        }
        
        return response()->json(["data" => $tasks->paginate(5)->withQueryString()], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|min:2',
            'description' => 'nullable|string',
            'finished' => [Rule::in(['0','1',null])],
        ]);
        if($request->input('finished') == '1'){
            $request->validate([
                'finished_time' => 'date',
            ]);
            $time = strtotime($request->input('finished_time'));
            $dateTime = date('Y-m-d H:i:s', $time);
            $request->merge(['finished_time' => $dateTime]);
        }
        else{
            $request->merge(['finished' => '0', 'finished_time' => null]);
        }

        $task = Task::create($request->only('title', 'description', 'finished', 'finished_time'));
        return response()->json(["data" => $task->only('id', 'title', 'description', 'finished', 'finished_time')], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json(["data" => $task->only('id', 'title', 'description', 'finished', 'finished_time')], 200);
    }

    /**
     * Update the specified resource in storage with put method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255|min:2',
            'description' => 'nullable|string',
            'finished' => [Rule::in(['0','1',null])],
        ]);
        if($request->input('finished') == '1'){
            $request->validate([
                'finished_time' => 'date',
            ]);
            $time = strtotime($request->input('finished_time'));
            $dateTime = date('Y-m-d H:i:s', $time);
            $request->merge(['finished_time' => $dateTime]);
        }
        else {
            $request->merge(['finished' => '0', 'finished_time' => null]);
        }
        $task->update($request->only('id', 'title', 'description', 'finished', 'finished_time'));
        
        return response()->json(["data" => $task->only('id', 'title', 'description', 'finished', 'finished_time')], 200);
    }

    /**
     * Update the specified resource in storage with patch method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function updatePatch(Request $request, Task $task){
        $request->validate([
            'title' => 'nullable|max:255|min:2',
            'description' => 'nullable|string',
            'finished' => [Rule::in(['0','1',null])],
            'finished_time' => 'nullable|date',
        ]);
        if($request->input('finished') === '0' || ($request->input('finished') === null && empty($task->finished))){
            $update['finished_time'] = null;
        }
        else if(empty($task->finished_time) && empty($request->input('finished_time')) && $request->input('finished') === '1'){
            return response()->json(['message' => 'Bad request.'], 400);
        }
        else {
            $update['finished_time'] = $request->input('finished_time') ?? $task->finished_time;
        }
        $update['title'] = $request->input('title') ?? $task->title;
        $update['description'] = $request->input('description') ?? $task->description;
        $update['finished'] = $request->input('finished') ?? $task->finished;
        
        $task->update($update);
        return response()->json(["data" => $task->only('id', 'title', 'description', 'finished', 'finished_time')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}

