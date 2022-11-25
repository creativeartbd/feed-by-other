<?php
namespace App\Http\Controllers;

use MongoDB\Client;
use MongoDB\Database;
use Illuminate\Http\Request;
use App\Models\Import;

use Jenssegers\Mongodb\Connection;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Collection;
use Jenssegers\Mongodb\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use MongoDB\BSON\Regex;


class ImportController extends Controller
{   

    protected $col; 

    private function set_connection( $projectToken, $userId ) {

        if( empty( $projectToken )  && empty( $userId ) ) { return response()->json( false, 200 ); }

        // Get the mongodb database info 
        $get_db_name = DB::table('projects')
        ->leftJoin('mongodb_servers', 'mongodb_servers.id_mongodb_server', '=', 'projects.id_mongodb_server')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where([
            ['projects.token', '=', $projectToken],
            ['project_users.id_user', '=', $userId]
        ])->get(['mongodb_ip', 'mongodb_port', 'mongodb_user', 'mongodb_password', 'mongodb_db_name']);
        
        // If mongodb info found
        if( count($get_db_name) ) {
        
            $mongodb_user = $get_db_name[0]->mongodb_user;
            $mongodb_password = urlencode($get_db_name[0]->mongodb_password);
            $mongodb_ip = $get_db_name[0]->mongodb_ip;
            $mongodb_db_name = $get_db_name[0]->mongodb_db_name;
            $mongodb_port = $get_db_name[0]->mongodb_port;

            // Set the mongodb connection on the run time
            Config::set("database.connections.mongodb", [
                'driver'    =>  'mongodb',
                'dsn'   =>     "mongodb://{$mongodb_user}:{$mongodb_password}@{$mongodb_ip}:{$mongodb_port}/?authSource={$mongodb_db_name}",
                //'dsn'   =>  'mongodb://marketplus_pr:.-(m%3E%5E_%25B.M%23*1015T4D%7B8QPnx%2C!((%2C@1101.235.229.71:57019/?authSource=projects',
                'database'  =>  'projects'
            ]);
        } else {
            return response()->json( false, 200 );
        }
    }

    public function get_products_key_value( $projectToken, $userId ) {

        $this->set_connection( $projectToken, $userId );
        // Get the project Id
        $get_project_id =  DB::connection('mysql')->table('projects')->where('token', $projectToken )->get(['id_project'])->first();

        $get_project_id = DB::table('projects')
        ->leftJoin('currencies', 'currencies.id_currency', '=', 'projects.currency_id')
        ->where('projects.token', '=', $projectToken)
        ->first(['id_project', 'country_code']);

        if( $get_project_id ) {

            $collection = 'products_' . $get_project_id->id_project;
            $currency_code = $get_project_id->country_code;
            $this->col = $collection;
            //$collection = 'products_102';

            $get_product_keys = DB::connection('mongodb')->collection( $collection )->raw( function ( $collection ) {
                return $collection->aggregate(  
                    array(
                        array(
                            '$group' => array (
                                '_id' => NULL,
                                'array' => array (
                                    '$mergeObjects' => '$$ROOT'
                                )
                            ),
                        ),
                        array(
                            '$project' => array (
                                'keys' => array(
                                    '$map' => array(
                                        'input' => array(
                                            '$objectToArray' => '$array'
                                        ),
                                        'in' => '$$this.k'
                                    )
                                )
                            ),
                        ),
                    )
                );
            })->toArray();

            if( count( $get_product_keys ) ) {

                $get_product_keys  = $get_product_keys[0]['keys'];
                unset($get_product_keys[0]); // We don't need this _id key of mongtodb database
                 // Get all products
                $get_products = DB::connection('mongodb')->collection( $collection  )->paginate(100);

                $get_saved_columns = DB::table('project_users')
                ->where('id_project',  $get_project_id->id_project )
                ->where('id_user', $userId)
                ->first()->params;

                if( $get_products ) {
                    return response()->json([
                        'product_keys'  =>  $get_product_keys,
                        'product_value' =>  $get_products, 
                        'currency_code' =>  $currency_code, 
                        'project_id'    =>  $get_project_id->id_project,
                        'saved_columns' =>  $get_saved_columns
                    ], 200);
                }
            }
        }
    }

    public function get_more_product( Request $request, $projectToken, $userId ) {

        $this->set_connection( $projectToken, $userId );

        // Get the project Id
        $get_project_id =  DB::connection('mysql')->table('projects')->where('token', $projectToken )->get(['id_project'])->first();

        $collection = 'products_' . $get_project_id->id_project;
        //$collection = 'products_102';

        $get_products = DB::connection('mongodb')->collection( $collection  )->paginate(100);

        if( $get_products ) {
            return response()->json( $get_products, 200);
        }
    }

    public function search_product( Request $request ) {

        // $filtered = array_filter( $request->all());
        // $trimmed_array = array_map('trim', $filtered);
        $search = $request->only('search');
        $project_token = $request->projectToken;
        $user_id = (int) $request->userId;

        $whereLike = $whereIn = $where = $orWhere = $whereBetween = $whereBoolean = [];
    
        foreach( $search as $k => $v ) {
            foreach( $v as $searching ) {

                $operator       = isset( $searching['operator'] ) ? $searching['operator'] : '';
                $search_field   = isset( $searching['search_field'] ) ? $searching['search_field'] : '';
                $search_query   = isset( $searching['search_query'] ) ? $searching['search_query'] : '';
                $search_to      = isset( $searching['search_to'] ) ? $searching['search_to'] : '';
                $is_boolean     = false;

                if( is_numeric( $search_query ) )  {
                    $search_query = (int) $search_query;
                } else if( trim( strtolower( $search_query ) ) == 'false' || trim( strtolower( $search_query ) ) == 'true' ) {
                    $is_boolean = true;
                    if( trim( strtolower( $search_query ) ) == 'false' ) {
                        $is_true        = true;
                    } else {
                        $is_true        = false;
                    }
                }

                if( !empty( $search_query ) ) {
                    if( !empty( $operator ) ) {
                        if( $operator === 'contain' ) {

                            $operator = 'LIKE';
                            $search_query = '%' . $search_query . '%';
                            if( $is_boolean ) {
                                if( $is_true ) {
                                    $search_query = 0;
                                } else {
                                    $search_query = $search_query;
                                }
                                $whereBoolean[] = [ $search_field, $search_query ];
                            } else {
                                $where[] = [ $search_field, $operator, $search_query ];
                            }

                        } elseif( $operator === 'notcontain' ) {

                            $operator = 'NOT LIKE';
                            $search_query = '%' . $search_query . '%';
                            if( $is_boolean ) {
                                if( $is_true ) {
                                    $is_true = false;
                                    $search_query = 0;
                                } else {
                                    $is_true = true;
                                    $search_query = $search_query;
                                }
                                $whereBoolean[] = [ $search_field, $search_query ];
                            } else {
                                $where[] = [ $search_field, $operator, $search_query ];
                            }
                            
                        } elseif( $operator === 'greater' ) {

                            $operator = '>';
                            $where[] = [ $search_field, $operator, $search_query ];
                            
                        } elseif( $operator === 'smaller' ) {

                            $where[] = [ $search_field, $operator, $search_query ];
                            $operator = '<';

                        } elseif( $operator === 'interval' ) {    
                                                    
                            $operator = '';
                            $whereBetween[] = [ $search_field, [ $search_query, (int) $search_to] ];
                        }                        
                    }
                }
            }
        }

        // Log::info( $whereLike );
        // Log::info( $whereIn );
        // Log::info( $where );
        // Log::info( $orWhere );
        // Log::info( $whereBetween );
        // Log::info( $whereBoolean );

        $this->set_connection( $project_token, $user_id );

        $get_project_id =  DB::connection('mysql')->table('projects')->where('token', $project_token )->get(['id_project'])->first();
        $collection = 'products_' . $get_project_id->id_project;
        //$collection = 'products_102';

        $search = DB::connection('mongodb')->collection( $collection );
        
        if( count( $whereBetween ) ) {
            foreach( $whereBetween as $arr ) {
                $field = $arr[0];
                $betweenFrom = $arr[1][0];
                $betweenTo = $arr[1][1];
                $search->whereBetween( $field, [ $betweenFrom, $betweenTo ] );
            }
        }
        
        if( count( $whereBoolean ) ) {
            foreach( $whereBoolean as $arr ) {
                if( $is_true ) {
                    $search->where( $arr[0], (bool) false );
                } else {
                    $search->where( $arr[0], (bool) true );
                }            
            }
        }

        if( count( $where ) ) {
            foreach( $where as $arr ) {
                $key = $arr[0];
                $operator = $arr[1];
                $value = $arr[2];
                $search->Where($key, $operator, $value);
            }
        }

        $search = $search->paginate(100);
        return response()->json( [ 'product_value' => $search ], 200);
    }

    public function save_columns( Request $request) {

        $columns = $request->input('columns');
        $project_token = $request->input('project_token');
        $user_id = $request->input('user_id');
        $project_id = $request->input('project_id');

        // check fields are empty
        if( count( $columns ) === 0 || empty( $project_token ) || empty( $user_id ) || empty( $project_id ) ) return;

        // Check if user has this porject
        $user_has_project = DB::table('projects')
        ->leftJoin('project_users', 'project_users.id_project', '=', 'projects.id_project')
        ->where('projects.token', '=', $project_token)
        ->where('project_users.id_user', '=', $user_id)->exists();

        if( $user_has_project ) {
            // Update params columns of project users table
            $update_users_params = DB::table('project_users')
            ->where('id_user', $user_id)
            ->where('id_project', $project_id)
            ->update([
                'params'    =>  json_encode( $columns )
            ]);

            // If updated
            if( $update_users_params ) {
                return response()->json([
                    $update_users_params
                ], 200);
            }
        }
    }

    public function get_products_by_sort( Request $request ) {
    
        $filtered = array_filter( $request->all());
        $where = [];
        foreach( $filtered as $k => $v ) {
            if( $k !== 'page' && $k !== 'projectToken' && $k !== 'userId' && $k !== 'sort' ) {
                $where[] = [$k, 'LIKE', '%'.$v.'%' ];
            }            
        }

        $sort = isset( $request->all()['sort'] ) ?  $request->all()['sort'] : [];

        // Log::info( $filtered );
        // Log::info( $sort );

        if( count( $sort ) ) {

            $sort_key =  array_keys( $sort )[0];
            $sort_value = $sort[ $sort_key ];

            $projectToken = $request->input('projectToken');
            $userId = $request->input('userId');

            $this->set_connection( $projectToken, $userId );

            $get_project_id =  DB::connection('mysql')->table('projects')->where('token', $projectToken )->get(['id_project'])->first();
            $collection = 'products_' . $get_project_id->id_project;
            //$collection = 'products_102';

            $search = DB::connection('mongodb')->collection( $collection )->where($where)->orderBy( $sort_key, $sort_value )->paginate(100);

            return response()->json( [ 'product_value' => $search ], 200);

        }
    }
}
