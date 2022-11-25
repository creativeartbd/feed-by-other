<?php

namespace App\Http\Controllers;

use App\Models\Feeds;
use App\Models\MysqlServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreFeedsRequest;
use App\Http\Requests\UpdateFeedsRequest;
use App\Events\Notifications;

class FeedsController extends Controller
{

    public function import_mapping( Request $request ) {
        $feed_mappings = $request->projectFieldOptions;
        foreach( $feed_mappings as $mapping ) {
            echo $mapping['value'];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFeedsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_import(Request $request)
    {

        $feed_url_token = md5($request->feed_file_url);
        $project_id = $request->token;
        $name = $request->name;
        $feed_url = $request->feed_file_url;
        $auth_info = serialize($request->auth_info);
        $feed_type = isset( $request->feed_type ) ? $request->feed_type : '';
        $now = now();

        $import = DB::table("import_fields_mapping_processings")
        ->where('id_project', $project_id)
        ->where('feed_url_token', $feed_url_token)->get()->first();

        if( ! empty( $import ) ) {
            if ($import->status == 'ready') {
                return response([
                    'status'        =>  $import->status,
                    'field_names'   =>  unserialize( $import->field_names ),
                    'field_extra_info'  =>  $import->field_extra_info,
                    'id_mapping_processing' =>  $import->id_mapping_processing,
                    'id_project'  =>  $import->id_project,
                    'feed_url_token'    =>  $import->feed_url_token
                ], 200 ) ;
            }
            return response()->json([
                'status' => $import->status,
            ] , 200 ) ;
        } else {

            // set params value
            if($feed_type === 'xml' ) {
                $params = json_encode(array(
                    'category_delimiter' => $request->category_delimitor
                ));
            } elseif($feed_type === 'csv' ) {
                $params = json_encode(array(
                    'column_separator' => $request->column_separator,
                    'csv_has_header' => ($request->csv_has_header ? 1 : 0),
                    'csv_encoding' => $request->encoding,
                    'advanced_option'   => $request->advanced_option,
                ));
            }
    
            $insert = DB::table('import_fields_mapping_processings')->insert([
                'id_project'        =>  $project_id,
                'feed_url'          =>  $feed_url,
                'feed_url_token'    =>  $feed_url_token,
                'status'            =>  'pending',
                'date_add'          =>  $now,
                'params'            =>  $params,
                'date_processed'    =>  $now,
                'feed_type'         =>  $feed_type,
                'auth_info'         =>  $auth_info,
            ]);

            return response()->json(
                ['status'    =>  'pending'],
                200
            ); 
        }
    }

    public function refresh_feed( Request $request ) {

        // Get the request data 
        $user_id = $request->user_id; 
        $feed_file_url = $request->feed_file_url; 
        $project_token = $request->project_token;
        $old_feed_token = $request->old_feed_token;

        if(!$old_feed_token) {
            $old_feed_token = md5($feed_file_url);
        }

        // Check if that user has that project
        $has_project = DB::table('projects')
        ->leftjoin('project_users', 'projects.id_project', '=', 'project_users.id_project')
        ->where('projects.token', '=', $project_token);

        if($has_project) {
            $feed_url_token = $old_feed_token;
            // Now Delete the imported fields
            $delete_field_mapping = DB::table('import_fields_mapping_processings')
            ->where('feed_url_token', '=', $feed_url_token)
            ->delete();

            if($delete_field_mapping) {
                return response()->json([
                    'success'   =>  true
                ], 200);
            } else {
                return response()->json([
                    'success'   =>  false
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function get_import($token, $user_id)
    {

        $get_db = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('project_users.id_user', '=', $user_id)
        ->where('projects.token', '=', $token)
        ->get(['mysql_db_name'])->first();

        if ($get_db) {
         
            $mysql_db_name = $get_db->mysql_db_name;

            if ($mysql_db_name) {

                $output = [];
                // Get data from feeds table
                $get_import = DB::connection($mysql_db_name)->table($mysql_db_name . '.feeds')
                ->where('project_token', $token)
                ->orderBy('id_feed', 'desc')
                ->get(['feed_type', 'id_feed', 'feed_name', 'status', 'running', 'item_count']);

                if( $get_import ) {
                    // get last notificatoin
                    foreach( $get_import as $k => $import ) {
                        $import->last_notification = DB::connection($mysql_db_name)->table($mysql_db_name . '.notifications')
                        ->where('object_id', $import->id_feed)
                        ->orderBy('id_notification', 'desc')
                        ->limit(1)
                        ->get(['content']);
                        $output[] = $import;
                    }

                    // output response 
                    return response()->json([
                        'success'   =>  true,
                        'output'    =>  $output
                    ], 200);

                } else {
                    return response()->json([
                        'success'   =>  true,
                        'message'   =>  'No project is found'
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'success'   =>  false,
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function edit(Feeds $feeds)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFeedsRequest  $request
     * @param  \App\Models\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function update_feed_status(Request $request, Feeds $feeds, $token, $user_id, $feed_id)
    {
        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->leftJoin('users', 'users.id', '=', 'project_users.id_user')
        ->where('projects.token', '=', $token)
            ->where('project_users.id_user', '=', $user_id)
            ->get(['mysql_db_name', 'users.name'])->first();

        // dd($get_db_name);

        if ($get_db_name) {
            
            if ($request->status) {
                $status = 1;
                $status_text = 'Enabled';
            } else {
                $status = 0;
                $status_text = 'Disabled';
            }
            
            $db_name = $get_db_name->mysql_db_name;
            $update_status = DB::connection($db_name)->table($db_name . '.feeds')
            ->where('project_token', $token)
                ->where('id_feed', $feed_id)
                ->update([
                    'status'  =>  $status
                ]);

            // Add Notification 

            if ($update_status) {

                // Dispatch notification event
                Notifications::dispatch(
                    [
                        'type'          =>  'add',
                        'db_name'       =>  $db_name,
                    ],
                    [
                        'id_project'    =>  $token,
                        'notification_type'     =>  1,
                        'object_type'   =>  1,
                        'object_id'     =>  $feed_id,
                        'title'         =>  'Feed ' . $status_text,
                        'content'       =>  "The feed {$request->mapping_name} was {$status_text}  by {$get_db_name->name}",
                        'icon'          =>  'UploadCloudIcon',
                        'id_user'       =>  $user_id,
                        'updated_at'    =>  now(),
                    ]
                );

                return response()->json($update_status, 200);
            }            
        }
    }


    public function running(Request $request, Feeds $feeds, $token, $user_id, $feed_id)
    {
        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('projects.token', '=', $token)
            ->where('project_users.id_user', '=', $user_id)
            ->first(['mysql_db_name']);

        if ($get_db_name) {
            $db_name = $get_db_name->mysql_db_name;
            $run_now = DB::connection($db_name)->table($db_name . '.feeds')
                ->where('project_token', $token)
                ->where('id_feed', $feed_id)
                ->get(['running'])->first();
            if ($run_now) {
                return response()->json($run_now, 200);
            }            
        }
    }
  
    public function update_running(Request $request, Feeds $feeds, $token, $user_id, $feed_id)
    {
        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('projects.token', '=', $token)
            ->where('project_users.id_user', '=', $user_id)
            ->first('mysql_db_name');

        if ($get_db_name) {
            
            $db_name = $get_db_name->mysql_db_name;
            $update_running = DB::connection($db_name)->table($db_name . '.feeds')
                ->where('project_token', $token)
                ->where('id_feed', $feed_id)
                ->update([
                    'date_last_processed'  =>  '1900-01-01 00:0:00', 
                    'running'  => 1
                ]);
            
            // Update feed scrape url to check regex table
            $update_feed_scrape_url_to_check_regex = DB::connection($db_name)->table($db_name . '.feed_scrape_url_to_check_regex')
            ->where('id_feed', $feed_id)
            ->update([
                'next_process_time' => '1980-01-01 12:00:00'
            ]);

            // Update feed_scrape_products table
            $update_feed_scrape_products = DB::connection($db_name)->table($db_name . '.feed_scrape_products')
            ->where('id_feed', $feed_id)
            ->update([
                'next_process_time' => '1980-01-01 12:00:00'
            ]);

            if ($update_running) {
                return response()->json($update_running, 200);
            }            
        }
    }

    public function get_xml_csv( Request $request, Feeds $feeds, $user_id, $project_token ) {
        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('projects.token', '=', $project_token)
            ->where('project_users.id_user', '=', $user_id)
            ->get('mysql_db_name')->first();
        if($get_db_name) {
            $db_name = $get_db_name->mysql_db_name;
            $get_xml_csv = DB::connection($db_name)->table($db_name.'.feeds')
            ->where('project_token', $project_token)
            ->get(['feed_name', 'feed_type', 'id_feed']);
            if($get_xml_csv) {
                return response()->json($get_xml_csv, 200);
            } else {
                return false;
            }
        }
    }
}
