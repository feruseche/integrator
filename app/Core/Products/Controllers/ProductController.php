<?php

namespace Core\Products\Controllers;

use XBase\TableReader;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Core\Helpers\{ApiResponse, Messages};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use Core\Products\Models\Product;
use Core\Products\Models\ProductUltimate;
use Core\Products\Models\ProductPenultimate;
use Core\Products\Models\ProductUpload;

class ProductController extends Controller
{
    private $model;
    private $errorServer = ['status' => 500, 'message' => 'Error de comunicación con el servidor de la base de datos.', 'data' => ''];
    private $recordsNotFound = ['status' => 400, 'message' => '0 registros encontrados.', 'data' => ''];

    public function __construct()
    {
        $this->model = new Product();
    }

    public function indexProductsFromPostgres()
    {
        try {
            $products = DB::connection('pgsql')
                ->table('products as p')                
                ->select(
                            'p.code as barcode', 
                            'p.description as name',
                            'ps.stock as stock',
                            DB::raw("(pu.maximum_price + (pu.maximum_price * (t.aliquot/100))) as price")
                        )
                ->leftjoin('products_stock as ps', 'ps.product_code', 'p.code')
                ->leftjoin('products_units as pu', 'pu.product_code', 'p.code')
                ->leftjoin('taxes as t', 't.code', 'p.sale_tax')
                ->where('pu.main_unit', true)
                ->where([['ps.stock', '>=', 0],['pu.maximum_price', '>', 0]])
                ->orderby('pu.maximum_price', 'desc')
                ->get();

            $products_ultimate_clean = ProductUltimate::truncate();
            $products_upload_clean = ProductUpload::truncate();

            for($i=0;$i<count($products);$i++) {
                Product::updateOrCreate(
                    ['barcode' => $products[$i]->barcode], 
                    ['name' => $products[$i]->name, 'stock' => $products[$i]->stock, 'price' => $products[$i]->price]
                );
            }

            $procedure = DB::select('CALL integrator()');

            $products_ultimate_clean = ProductUltimate::truncate();

            $products_upload = ProductUpload::all()->toArray();

            $message  = count($products_upload) . ' registros cargados al servidor de Mercarapi.';

            return (new ApiResponse(true, $message, $products_upload))->execute();
            
            $response = Http::post('https://mercarapid.nks-sistemas.net/api/recibir-inventario', [
                'store' => '91bc2ca9-be3c-40c6-9431-2512ab1d7c07',
                'productos' => ["data" => $products_nexus]
            ]);

            return ($response->ok())
                ? response()->json($response->body())
                : response()->json([$this->recordsNotFound]);

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function integratorNexus() {

        $table = new TableReader('articulo.dbf');
        $counter = 0;
        $products_nexus = [];

        while ($record = $table->nextRecord()) {
            if($record->get('existencia')>=2) {

                $simbols = array('[$.', ']');
                $empty   = array('', '');
                $text = $record->get('referencia');
                $price  = str_replace($simbols, $empty, $text);

                $product = [
                    "barcode" => $record->get('codigo'),
                    "name" => $record->get('nombre'),
                    "stock" => $record->get('existencia'),
                    "price" => ($price!='') ? $price : 0,
                ];

                if(json_encode($product)) {
                    array_push($products_nexus, $product);
                    $counter++;
                } 
            }
        }

        //$data = json_encode($products_nexus);
        //$products_ally_clean = Product::truncate();
        $products_ultimate_clean = ProductUltimate::truncate();
        $products_upload_clean = ProductUpload::truncate();

        for($i=0;$i<count($products_nexus);$i++) {
            Product::updateOrCreate(
                ['barcode' => $products_nexus[$i]['barcode']], 
                ['name' => $products_nexus[$i]['name'], 'stock' => $products_nexus[$i]['stock'], 'price' => $products_nexus[$i]['price']]
            );
        }

        $procedure = DB::select('CALL integrator()');

        $products_ultimate_clean = ProductUltimate::truncate();

        $products_upload = ProductUpload::all()->toArray();

        $message  = count($products_upload) . ' registros cargados desde Nexus al servidor de Mercarapi.';

        return (new ApiResponse(true, $message, $products_upload))->execute();

        $response = Http::post('https://mercarapid.nks-sistemas.net/api/recibir-inventario', [
                    'store' => '76669646-d5a2-4dcd-b134-429bc2906c48',
                    'productos' => ["data" => $products_nexus],
                ]);

        return ($response->ok())
                ? response()->json($response->body())
                : response()->json([$this->recordsNotFound]);
    }
}
