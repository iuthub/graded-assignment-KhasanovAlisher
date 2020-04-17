
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class TasksController extends Controller
{
    public function getFirstPage() {
        // $postsRepo = new PostsRepo($session);

        return view('tasks.task', [
            'tasks' => Task::all()
        ]);
    }

    public function edit(Request $req) {

        $this->validate($req, [
            'title' => 'required|regex:/(\w+\s?\b){2,}/'
        ]);

        $task = Task::find($req->input('id'));
        if(Gate::denies('update-post', $task)) {
            return redirect()->route('getFirstPage');
        }
        $task->title = $req->input('title');
        $task->save();

        return redirect()->route('getFirstPage')->with([
            'info'=>'Task '. $req->input('title').' successfully updated!'
        ]);
    }

    public function create(Request $req) {
        $this->validate($req, [
            'title' => 'required|regex:/(\w+\s?\b){2,}/'
        ]);

        $user = Auth::user();
        $task = new Task([
            'title'=> $req->input('title')
            ]);

        $user->tasks()->save($task);

        return redirect()->route('getFirstPage')->with([
            'info'=>'Task '. $req->input('title').' successfully created!'
        ]);
    }

    public function destroy($id) {
        // $postsRepo = new PostsRepo($session);
        // $postsRepo->deletePost($id);
        $task = Task::find($id);
        $taskName = $task->title;

        if(Gate::denies('update-post', $task)) {
            return redirect()->route('getFirstPage');
        }
        $task->delete();

        return redirect()->route('getFirstPage')->with([
            'info'=>'Task '. $taskName.' successfully deleted!'
        ]);
    }

    public function getTaskEdit($id) {
        // $postsRepo = new PostsRepo($session);
        $task = Task::find($id);
        if(Gate::denies('update-post', $task)) {
            return redirect()->route('getFirstPage');
        }
        return view('tasks.edit', [
            'task' => $task
        ]);
    }


}
