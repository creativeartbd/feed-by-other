<?php


namespace App\Http\Controllers;


use App\Models\ExternalImport;
use App\Models\Helper;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\MysqlServer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchestra\Parser\Xml\Facade as XmlParser;




class ProductController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function xml(Request  $request)
    {
        $data = ParserGoogleFeed::get_data_from_xml($request->xml_file_url);
        return response()->json($data, 200);
    }

    public function save(Request $request)
    {
        $externalImport = ExternalImport::create([
            'active' => 1,
            'name' => $request['name'],
            'import_type' => $request['import_type'],
            'notify_user' => 1,
            'date_last_import' => Carbon::now()->toDateTimeString(),
            'import_information' => serialize([
                'url' => $request->xml_file_url,
                'encoding' => $request->encoding,
                'auth_data' => [
                    'auth_type' => '',
                    'auth_user' => '',
                    'auth_pass' => ''
                ],
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->image,
            ])
        ]);

        return response()->json($externalImport, 200);
    }


}
