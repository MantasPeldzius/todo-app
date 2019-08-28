<?php

namespace App\Http\Controllers;

use App\Task;
use App\Log;
// use App\User;
use Illuminate\Http\Request;
// use App\Http\Middleware\Authenticate as Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller {
	
	private $request;
	
	public function __construct(Request $request) {
		$this->request = $request;
		$this->middleware('auth');
	}
	
    public function showAllTasks() {
    	
    	if (!Gate::forUser($this->request->auth)->allows('access-admin')) {
    		return response()->json([
    			'error' => 'Unauthorized.'
    		], 401);
    	}
    	
        return response()->json(Task::all());
    }

    public function showOneTask($id) {
    	
    	if ($this->request->auth->role === 1) {
	    	return response()->json(Task::findOrFail($id));
    	} elseif ($this->request->auth->role === 2) {
    		$task = Task::where('id', '=', $id)->where('user', '=', $this->request->auth->id)->first();
    		
    		if ($task) {

    			$this->log($this->request->auth->id, "Checked own task with id: $id");
    			
    			return response()->json($task, 200);
    		} else {
    			
    			$this->log($this->request->auth->id, "Tried to check task with id: $id");
    			
    			return response()->json([
    				'error' => 'Not Found.'
    			], 404);
    		}
    	}
    	
		return response()->json([
			'error' => 'Not Found.'
		], 404);
    }

    public function create() {
    	
    	if (!Gate::forUser($this->request->auth)->allows('access-user')) {
    		return response()->json([
    			'error' => 'Unauthorized.'
    		], 401);
    	}
    	
    	$this->validate($this->request, [
    		'caption' => 'required|max:255',
    		'text' => 'required|max:65535',
    	]);
    	
    	$task = $this->request->all();
    	$task['user'] = $this->request->auth->id;
    	
    	$task = Task::create($task);
		
    	if ($task) {
    		$this->log($this->request->auth->id, "Created new task with id: {$task->id}");
    	}
    	
    	return response()->json($task, 201);
    }

    public function update($id) {
    	
    	if (!Gate::forUser($this->request->auth)->allows('access-user')) {
    		return response()->json([
    			'error' => 'Unauthorized.'
    		], 401);
    	}

    	$this->validate($this->request, [
    		'caption' => 'required|max:255',
    		'text' => 'required|max:65535',
    	]);
    	
    	$task = Task::where('id', '=', $id)->where('user', '=', $this->request->auth->id)->first();
    	
    	if ($task) {
    		$task->update($this->request->all());
	    	return response()->json($task, 200);
    	} else {
    		return response()->json([
    			'error' => 'Not Found.'
    		], 404);
    	}
    }

    public function delete($id) {
    	
    	if (!Gate::forUser($this->request->auth)->allows('access-admin')) {
    		return response()->json([
    			'error' => 'Unauthorized.'
    		], 401);
    	}
    	
    	Task::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
    
    public function log($user_id, $action) {
    	Log::create([
    		'user_id' => $user_id,
    		'action' => $action,
    	]);
    }
}