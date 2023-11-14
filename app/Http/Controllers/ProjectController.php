<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Project;
use App\Models\School;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //obtendre el proyecto del usuario logueado
        $user = Auth::user();
        // Obtiene el año actual
        $year = date('Y');
        // Realiza una consulta para verificar si el usuario está en un grupo del año actual
        $group = Group::where('groups.year', $year)
            ->where('groups.status', 1)
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        $project_users = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->join('user_group as ug', 'ug.group_id', 'projects.group_id')
            ->where('projects.group_id', $group->id)->get();

        $project = Project::join('profiles as p', 'projects.profile_id', 'p.id')
            ->where('projects.group_id', $group->id)->first();

        $stages = Stage::where("protocol_id", $group->protocol_id)
                                ->where('cycle_id',$group->cycle_id)
                                ->where('school_id',$user->school_id)
                                ->get();


        return view('projects.index', [
            'project_users' => $project_users,
            'project'       => $project,
            'stages'        => $stages,
        ]);
    }
}
