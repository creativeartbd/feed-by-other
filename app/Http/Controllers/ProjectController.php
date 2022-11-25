<?php
namespace App\Http\Controllers;


use App\Models\Helper;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\MysqlServer;
use App\Models\MongodbServer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id)
    {
        
        $get_projects = DB::table('projects')
        ->leftJoin('countries', 'projects.country_id', '=', 'countries.id_country')
        ->leftJoin('currencies', 'projects.currency_id', '=', 'currencies.id_currency')
        ->leftJoin('timezones', 'projects.timezone_id', '=', 'timezones.id_timezone')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->orderBy('projects.id_project', 'desc')
        ->where('project_users.id_user', '=', $id)
        ->get();

        return response()->json($get_projects, 200);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function select($id)
    {
        $projects = Project::get();
        foreach ($projects as $project) {
            Project::where('id_project', $project->id_project)->update(['is_selected' => 0]);
        }

        $project = Project::where('id_project', $id)->update(['is_selected' => 1]);
        return response()->json($project, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($token, $user_id)
    {

        // Check if the project belongs to user 
        $has_project = DB::table('projects')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('project_users.id_user', '=', $user_id)
        ->where('projects.token', '=', $token)
        ->first(['projects.id_project', 'name', 'website', 'country_id', 'currency_id', 'timezone_id', 'id_mysql_server', 'id_mongodb_server', 'token']);

        return response()->json($has_project, 200);
    }

    public function search( $keyword ) {

        $project = Project::where('name', 'LIKE', "%{$keyword}%")
        ->get();
        return response()->json($project, 200);
    }

    public function store(Request $request)
    {

        // get random mysql and mondodb server dettails
        $mysql_server = DB::table('mysql_servers')->where('open','=', 1)->inRandomOrder()->get()->first();
        $mongodb_server = DB::table('mongodb_servers')->where('open','=', 1)->inRandomOrder()->get()->first();

        // Create project
        $project = Project::create([
            'name' => $request->name,
            'website' => $request->website,
            'country_id' => $request->country,
            'currency_id' => $request->currency,
            'timezone_id' => $request->timezone,
            'token' => Helper::randomToken(), 
            'id_mysql_server' =>   $mysql_server->id_mysql_server,
            'id_mongodb_server' => $mongodb_server->id_mongodb_server
        ]);

        // if project has been created 
        if($project) {
            // Add user project
            $project_users = DB::table('project_users')->insert([
                'id_project'    =>  $project->id_project, // last insert id
                'id_user'   =>  $request->user_id,
                'date_add'  =>  now()
            ]);
            // If user project has been added
            if($project_users) {

                //$conn = new \mysqli($mysql_server->mysql_host_ip, $mysql_server->mysql_user, $mysql_server->mysql_password, $mysql_server->mysql_db_name);
                // Add project to different database
                $add_project = DB::connection($mysql_server->mysql_db_name)->table('projects')->insert([
                    'id_project'    =>  $project->id_project,
                    'name'  =>  $request->name, 
                    'website'   =>  $request->website, 
                    'country_id'    =>  $request->country, 
                    'currency_id'   =>  $request->currency, 
                    'timezone_id'   =>  $request->timezone,
                    'is_selected'   =>  0, 
                    'token' =>  $project->token, 
                    'created_at'    =>  now(),
                ]);

                // If project has been created
                if($add_project) {
                    return response()->json([
                        'message'   =>  'success'
                    ], 200);
                }
            }
        }
    }

    public function update(Request $request, $token, $user_id)
    {
        // Need to add user_id condition too !! 

        $project = Project::where([
            'token' => $token,
            ])->update([
            'name' => $request->name,
            'country_id' => $request->country,
            'currency_id' => $request->currency,
            'timezone_id' => $request->timezone,
            'website' => $request->website,
        ]);

        return response()->json([
            'success'   =>   $project,
            'name'  =>  $request->name,
            'token'  =>  $token,
        ], 200);
    }

    public function delete ($id) {
        $project = Project::find( $id );
        $project->delete();
        return true;
    }

}
