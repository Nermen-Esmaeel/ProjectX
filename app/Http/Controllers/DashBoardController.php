<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function task_status()
    {
        $tasks = Task::all();

        $completedTasks = $tasks->where('status', 'Completed')->count();
        $onHoldTasks = $tasks->where('status', 'OnHold')->count();
        $inProgressTasks = $tasks->where('status', 'OnProgress')->count();
        $pendingTasks = $tasks->where('status', 'Pending')->count();
        $cancelTasks = $tasks->where('status', 'Canceled')->count();

        $totalTasks = $tasks->count();

        $completedPercentage = ($completedTasks / $totalTasks) * 100;
        $onHoldPercentage = ($onHoldTasks / $totalTasks) * 100;
        $inProgressPercentage = ($inProgressTasks / $totalTasks) * 100;
        $pendingPercentage = ($pendingTasks / $totalTasks) * 100;
        $cancelPercentage = ($cancelTasks / $totalTasks) * 100;

        return response()->json([
            'Completed' => $completedPercentage . '%',
            'OnHold' => $onHoldPercentage . '%',
            'OnProgress' => $inProgressPercentage . '%',
            'Pending' => $pendingPercentage . '%',
            'Canceled' => $cancelPercentage . '%',
        ]);
    }



    public function project_status()
    {
        $projects = Project::all();

        $OffTrackProjects = $projects->where('status', 'OffTrack')->count();
        $OnTrackProjects = $projects->where('status', 'OnTrack')->count();
        $CompleteProjects = $projects->where('status', 'Complete')->count();
        $PendingProjects = $projects->where('status', 'Pending')->count();


        $totalProjects = $projects->count();

        $OffTrackPercentage = ($OffTrackProjects / $totalProjects) * 100;
        $OnTrackPercentage = ($OnTrackProjects / $totalProjects) * 100;
        $CompletePercentage = ($CompleteProjects / $totalProjects) * 100;
        $PendingPercentage = ($PendingProjects / $totalProjects) * 100;


        return response()->json([
            'OffTrack' => $OffTrackPercentage . '%',
            'OnTrack' => $OnTrackPercentage . '%',
            'Complete' => $CompletePercentage . '%',
            'Pending' => $PendingPercentage . '%',

        ]);
    }

    public function performance()
    {
        $jan_target = 3;
        $feb_target = 5;
        $mar_target = 2;
        $apr_target = 2;
        $may_target = 4;
        $joun_target = 6;
        $july_target = 5;
        $agus_target = 3;
        $sep_target = 2;
        $oct_target = 4;
        $nov_target = 4;
        $dec_target = 2;

        $monthTargets = [
            1 => $jan_target,
            2 => $feb_target,
            3 => $mar_target,
            4 => $apr_target,
            5 => $may_target,
            6 => $joun_target,
            7 => $july_target,
            8 => $agus_target,
            9 => $sep_target,
            10 => $oct_target,
            11 => $nov_target,
            12 => $dec_target,
        ];

        $currentMonth = date('n');
        // $projects = Project::all();

        $completedProjects = Project::where('status', 'Complete')
            ->whereMonth('end_date', $currentMonth)
            ->count();


        return response()->json([
            'achieved' => $completedProjects,
            'target' => $monthTargets[$currentMonth],

        ]);
    }

    public function work_log($id)

    {
        $completedTasksPercentage = [];

        $user = User::where("id", $id)->first();
        $projects =  $user->projects;

        foreach ($projects as $project) {
            $tasks = $project->tasks;
            $tasks_num =  $tasks->count();
            $completed_tasks = $tasks->where("status", "Completed")->count();

            $completedTasksPercentage[$project->title] = ($tasks_num > 0) ? round((($completed_tasks / $tasks_num) * 100), 2) : 0;
        }
        $sum = array_sum($completedTasksPercentage);

        foreach ($completedTasksPercentage as $key => $value) {
            $completedTasksPercentage[$key] = round($value / $sum * 100, 2);
        }



        return response()->json($completedTasksPercentage);
    }
}
