<?php

namespace App\Http\Controllers;

use App\Models\MysqlServer;
use App\Models\FeedMappings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreFeedMappingsRequest;
use App\Http\Requests\UpdateFeedMappingsRequest;
use App\Events\Notifications;

class FeedMappingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFeedMappingsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_mapping(Request $request)
    {

        $feed_mappings  = $request->projectFieldOptions;
        $id_project     = $request->token;
        $safety_info    = serialize($request->safety);
        $feed_file_url  = $request->feed_file_url;
        $is_remove_link_params  = $request->is_remove_link_params;
        $remove_link_params  = $request->remove_link_params;
        
    
        $get_db = DB::table('projects')
        ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
        ->where('projects.token', '=', $id_project)
            ->get('mysql_db_name')->first();

        if ($get_db) {

            $mysql_db_name = $get_db->mysql_db_name;

            if ($mysql_db_name) {

                $date_add = $date_last_processed = now();
                
                // set params value
                if($request->feed_type === 'xml' ) {

                    $params = json_encode(array(
                        'category_delimiter' => $request->category_delimitor,
                        'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                        'is_remove_link_params' => $is_remove_link_params,
                        'update_products_if_found'  =>  $request->update_products_if_found
                    ));

                } elseif($request->feed_type === 'csv' ) {

                    $params = json_encode(array(
                        'column_separator' => $request->column_separator,
                        'csv_has_header' => ($request->csv_has_header ? 1 : 0),
                        'csv_encoding' => $request->encoding,
                        'advanced_option'   => $request->advanced_option,
                        'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                        'is_remove_link_params' => $is_remove_link_params,
                        'update_products_if_found'  =>  $request->update_products_if_found
                    ));

                } else if ( $request->feed_type == 'scrape' ) {

                    $scrape_from = $request->scrape_from;
                    if( $scrape_from === 1 ) {
                        $scrape_from_value = $request->csv_xml_import;
                    } else if ( $scrape_from === 2 ) {
                        $scrape_from_value = '';
                    } else if ( $scrape_from === 3 ) {
                        $scrape_from_value = json_encode( $request->external_sitemap_url );
                    } else if ( $scrape_from === 4 ) {
                        $scrape_from_value = $request->regex_url;
                    } else if ( $scrape_from === 5 ) {
                        $options = [
                            'from_specific_pages_option'    =>  $request->from_specific_pages_option, 
                            'from_specific_pages_input' =>  $request->from_specific_pages_input, 
                            'from_specific_page_url'    =>  $request->from_specific_page_url
                        ];
                        $scrape_from_value = json_encode( $options );
                    }

                    $params = json_encode( array(
                        'use_proxies'   =>  $request->use_proxies, 
                        'import_period'  =>  $request->import_period,
                        'scrape_method' =>  $request->scrape_method, 
                        'scrape_from'   =>  $request->scrape_from,
                        'scrape_from_value' =>  $scrape_from_value,
                        'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                        'is_remove_link_params' => $is_remove_link_params, 
                        'update_products_if_found'  =>  $request->update_products_if_found
                    ) );
                }

                if( $request->feed_type == 'scrape' ) {

                    $feed = DB::connection($mysql_db_name)->table($mysql_db_name . '.feeds')->insertGetId([
                        'project_token'    =>  $id_project,
                        'feed_name'     =>  $request->mapping_name,
                        'feed_type'     =>  $request->feed_type,
                        'import_period' =>  $request->import_period,
                        'encoding'      =>  $request->encoding,
                        'safety_info'   =>  json_encode($request->safety),
                        'scrape_rules'  =>  json_encode($request->mapping),
                        'auth_info'     =>  json_encode($request->auth),  
                        'params'        =>  $params,
                        'date_add'      =>  $date_add,
                        'date_last_processed'   =>  $date_last_processed,
                        'status'          => 1,
                        'scrape_once'   =>  $request->scrape_once
                    ]);

                } else {

                    $feed = DB::connection($mysql_db_name)->table($mysql_db_name . '.feeds')->insertGetId([
                        'project_token'    =>  $id_project,
                        'feed_name'     =>  $request->mapping_name,
                        'feed_url_link' =>  $request->feed_file_url,
                        'feed_type'     =>  $request->feed_type,
                        'import_period' =>  $request->import_period,
                        'encoding'      =>  $request->encoding,
                        'safety_info'   =>  json_encode($request->safety),
                        'auth_info'     =>  json_encode($request->auth),  
                        'params'        =>  $params,
                        'date_add'      =>  $date_add,
                        'date_last_processed'   =>  $date_last_processed,
                        'status'          => 1,
                        'match_by'  =>  $request->match_products_by
                    ]);
                    
                }
                
                $data = [];
                $field_mapping = true;

                if( $request->feed_type !== 'scrape' ) {
                    foreach ($feed_mappings as $mapping) {

                        if($mapping['custom']) {
                            $internal_field_slug = $mapping['custom_field'];
                        } else {
                            $internal_field_slug = isset( $mapping['text'] ) ? $mapping['text']  : '';
                        }

                        
                        $data[] = [
                            'id_feed'       => $feed,
                            'id_project'    => $id_project,
                            'import_field_slug' => $mapping['value'],
                            'internal_field_slug' => $internal_field_slug,
                            'custom_field'  => isset( $mapping['custom'] ) ? $mapping['custom'] : null,
                            'updates'   =>  isset( $mapping['updates'] ) ? $mapping['updates'] : null,
                            'removes'   =>  isset( $mapping['removes'] ) ? $mapping['removes'] : null,
                            'import'        => 1,
                            'date_add'      => now(),
                            'date_upd'      => now(),
                        ];
                    }

                    $field_mapping = DB::connection($mysql_db_name)->table($mysql_db_name . ".feed_mappings")->insert($data);
                }
                

                if ($feed && $field_mapping) {
                    // Dispatch notification event
                    $notification = Notifications::dispatch(
                        [
                            'type'          =>  'add',
                            'db_name'       =>  $mysql_db_name,
                        ],
                        [
                            'id_project'    =>  $id_project,
                            'notification_type'     =>  1,
                            'object_type'   =>  1,
                            'object_id'     =>  $feed,
                            'title'         =>  'Feed Created',
                            'content'       =>  "The feed {$request->mapping_name} was created by {$request->username}",
                            'icon'          =>  'UploadCloudIcon',
                            'id_user'       =>  $request->user_id,
                            'updated_at'    =>  now(),
                        ]
                    );

                    return response()->json( [ 
                        'success'   =>  true, 
                        'feed'  =>  [
                            'feed_type' =>  $request->feed_type,
                            'feed_name' =>  $request->mapping_name,
                            'id_feed'   =>  $feed,
                        ],
                        'notification'  =>  "The feed {$request->mapping_name} was created by {$request->username}",
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'type'   =>  'error',
                'message'   =>  'Opps! Something is wrong. Please contact administrator.'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeedMappings  $feedMappings
     * @return \Illuminate\Http\Response
     */
    public function show_mapping(FeedMappings $feedMappings, $token, $user_id, $id_feed)
    {
        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
            ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
            ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
            ->where('projects.token', '=', $token)
            ->where('project_users.id_user', '=', $user_id)
            ->get('mysql_db_name')->first();

        // If database name found
        if ($get_db_name) {

            $get_mapping_field = DB::connection($get_db_name->mysql_db_name)->table($get_db_name->mysql_db_name . '.feed_mappings')
            ->where('id_feed', '=', $id_feed)
            ->where('id_project', '=', $token)
            ->orderBy('id_feed_mappings', 'asc')
            ->get();

            $get_mapping_log = DB::connection($get_db_name->mysql_db_name)->table($get_db_name->mysql_db_name . '.notifications')
            ->where('object_id', '=', $id_feed)
            ->where('id_project', '=', $token)
            ->orderBy('id_notification', 'desc')
            ->get();

            $get_import_info = DB::connection($get_db_name->mysql_db_name)->table($get_db_name->mysql_db_name . '.feeds')
                ->where('feeds.id_feed', '=', $id_feed)
                ->where('feeds.project_token', '=', $token)
                ->get();
            
            $output = [];
            $output['mapping_field'] = $get_mapping_field;
            $output['mapping_data'] = $get_import_info;
            $output['mapping_log'] = $get_mapping_log;

            // If result found
            if ($get_import_info) {
                return response()->json( $output, 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeedMappings  $feedMappings
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedMappings $feedMappings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFeedMappingsRequest  $request
     * @param  \App\Models\FeedMappings  $feedMappings
     * @return \Illuminate\Http\Response
     */
    public function update_mapping(UpdateFeedMappingsRequest $request, FeedMappings $feedMappings, $token, $user_id, $id_feed)
    {

        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
            ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
            ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
            ->where('projects.token', '=', $token)
            ->where('project_users.id_user', '=', $user_id)
            ->get('mysql_db_name');

        // If database found
        if ($get_db_name) {

            $db_name = $get_db_name[0]->mysql_db_name;
            $mapping_name = $request->mapping_name;
            $feed_file_url = $request->feed_file_url;
            $feed_type = $request->feed_type;
            $encoding = $request->encoding;
            $import_period = $request->import_period;
            $projectFieldOptions = $request->mergedFieldOptions;
            $is_remove_link_params  = $request->is_remove_link_params;
            $remove_link_params  = $request->remove_link_params;
            $safety = json_encode($request->safety);
            $auth = json_encode($request->auth);
            $now = now();

            // set params value
            if($request->feed_type === 'xml' ) {
                $params = json_encode(array(
                    'category_delimiter' => $request->category_delimitor, 
                    'advanced_option'   => $request->advanced_option,
                    'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                    'is_remove_link_params' => $is_remove_link_params,
                    'update_products_if_found'  =>  $request->update_products_if_found
                ));
            } elseif($request->feed_type === 'csv' ) {
                $params = json_encode(array(
                    'column_separator' => $request->column_separator,
                    'csv_has_header' => ($request->csv_has_header ? 1 : 0),
                    'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                    'is_remove_link_params' => $is_remove_link_params,
                    'csv_encoding' => $request->encoding,
                    'advanced_option'   => $request->advanced_option,
                    'update_products_if_found'  =>  $request->update_products_if_found
                ));
            } else if ( $request->feed_type == 'scrape' ) {

                $scrape_from = $request->scrape_from;
                if( $scrape_from === 1 ) {
                    $scrape_from_value = $request->csv_xml_import;
                } else if ( $scrape_from === 2 ) {
                    $scrape_from_value = '';
                } else if ( $scrape_from === 3 ) {
                    $scrape_from_value = json_encode( $request->external_sitemap_url );
                } else if ( $scrape_from === 4 ) {
                    $scrape_from_value = $request->regex_url;
                } else if ( $scrape_from === 5 ) {
                    $options = [
                        'from_specific_pages_option'    =>  $request->from_specific_pages_option, 
                        'from_specific_pages_input' =>  $request->from_specific_pages_input, 
                        'from_specific_page_url'    =>  $request->from_specific_page_url
                    ];
                    $scrape_from_value = json_encode( $options );
                }

                $params = json_encode( array(
                    'use_proxies'   =>  $request->use_proxies, 
                    'import_period'  =>  $request->import_period,
                    'scrape_method' =>  $request->scrape_method, 
                    'scrape_from'   =>  $request->scrape_from,
                    'scrape_from_value' =>  $scrape_from_value,
                    'remove_link_params' => $is_remove_link_params ? $remove_link_params : '',
                    'is_remove_link_params' => $is_remove_link_params,
                    'update_products_if_found'  =>  $request->update_products_if_found
                ) );
            }

            if( $request->feed_type == 'scrape' ) {
                 // Update feed table
                 $update_feed = DB::connection($db_name)->table($db_name . '.feeds')
                 ->where('project_token', $token)
                 ->where('id_feed', $id_feed)
                 ->update([
                    'feed_name'     =>  $mapping_name,
                    'import_period' =>  $request->import_period,
                    'feed_url_link' =>  $feed_file_url,
                    'encoding'      =>  $encoding,
                    'feed_type'     =>  $feed_type,
                    'safety_info'   =>  $safety,
                    'auth_info'     =>  $auth,
                    'params'        =>  $params,
                    'date_upd'      =>  $now,
                    'date_last_processed'   => $now,
                    'match_by'  =>  $request->match_products_by, 
                    'scrape_rules'  =>  json_encode($request->mapping),
                    'scrape_once'   =>  $request->scrape_once
                 ]);

            } else {
                // Update feed table
                $update_feed = DB::connection($db_name)->table($db_name . '.feeds')
                ->where('project_token', $token)
                ->where('id_feed', $id_feed)
                ->update([
                    'feed_name'     =>  $mapping_name,
                    'feed_url_link' =>  $feed_file_url,
                    'encoding'      =>  $encoding,
                    'import_period' =>  $import_period,
                    'feed_type'     =>  $feed_type,
                    'safety_info'   =>  $safety,
                    'auth_info'     =>  $auth,
                    'params'        =>  $params,
                    'date_upd'      =>  $now,
                    'date_last_processed'   => $now,
                    'match_by'  =>  $request->match_products_by
                ]);
            }

            if( $request->feed_type !== 'scrape' ) { 
                $delete_mapping = DB::connection($db_name)->table($db_name . '.feed_mappings')
                ->where('id_feed', '=', $id_feed)
                    ->where('id_project', '=', $token)
                    ->where('internal_field_slug', '!=', 'ts_product_item_wrapper')
                    ->delete();
            }
            
            // now insert 
            $field_mapping = true;
            if( $request->feed_type !== 'scrape' ) { 
                if( count($projectFieldOptions) ) {
                    foreach ($projectFieldOptions as $mapping) {
                        
                        if($mapping['custom']) {
                            $internal_field_slug = $mapping['custom_field'];
                        } else {
                            $internal_field_slug = isset( $mapping['text'] ) ? $mapping['text']  : '';
                        }

                        $data[] = [
                            'id_feed'       => $id_feed,
                            'id_project'    => $token,
                            'import_field_slug' => $mapping['value'],
                            'internal_field_slug' => $internal_field_slug,
                            'custom_field'  => $mapping['custom'],
                            'updates'   =>  $mapping['updates'],
                            'removes'   =>  $mapping['removes'],
                            'import'        => 1,
                            'date_add'      => now(),
                            'date_upd'      => now()
                        ];
                    }
                } else {
                    $data = [];
                } 

                $field_mapping = DB::connection($db_name)->table($db_name . ".feed_mappings")->insert($data);
            }
            

            // update feed Mappings table
            if ($update_feed && $field_mapping) {

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
                        'object_id'     =>  $id_feed,
                        'title'         =>  'Feed Updated',
                        'content'       =>  "The feed {$request->mapping_name} was updated by {$request->username}",
                        'icon'          =>  'CloudLightningIcon',
                        'id_user'       =>  $request->user_id,
                        'updated_at'    =>  now(),
                    ]
                );

                return response()->json([
                    'success'   => true,
                ], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeedMappings  $feedMappings
     * @return \Illuminate\Http\Response
     */
    public function delete_mapping(FeedMappings $feedMappings, $project_token, $user_id, $id_feed)
    {

        // Get the databae name from projects table
        $get_db_name = DB::table('projects')
            ->leftJoin('mysql_servers', 'mysql_servers.id_mysql_server', '=', 'projects.id_mysql_server')
            ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
            ->where('projects.token', '=', $project_token)
            ->where('project_users.id_user', '=', $user_id)
            ->get('mysql_db_name');

        // If database found
        if ($get_db_name) {
            
            $db_name = $get_db_name[0]->mysql_db_name;
            
            // Dekete feed
            $delete_feed = DB::connection($db_name)->table($db_name . '.feeds')
                ->where('id_feed', '=', $id_feed)
                ->where('project_token', '=', $project_token)
                ->delete();
            
                // Delete mapping
            $delete_mapping = DB::connection($db_name)->table($db_name . '.feed_mappings')
                ->where('id_feed', '=', $id_feed)
                ->where('id_project', '=', $project_token)
                ->delete();

            // Delete feed scrape producst
            $delete_feed_scrape_products = DB::connection($db_name)->table($db_name . '.feed_scrape_products')
                ->where('id_feed', '=', $id_feed)
                ->delete();
            
            // Delete feed scrape url to check regex
            $delete_feed_scrape_url_to_check_regex = DB::connection($db_name)->table($db_name . '.feed_scrape_url_to_check_regex')
                ->where('id_feed', '=', $id_feed)
                ->delete();
                
            if ($delete_feed || $delete_mapping) {
                return response()->json($delete_feed, 200);
            }
        }
    }
}
