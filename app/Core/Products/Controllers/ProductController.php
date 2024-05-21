<?php

namespace Core\Products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Core\Helpers\{ApiResponse, Messages};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use Core\Shared\Controllers\{Index, Show, Update};
use Core\Products\Models\{Product, Stock};

class ProductController extends Controller
{
    use Index, Show, Update;

    private $errorServer = ['status' => 500, 'message' => 'Error de comunicación con el servidor de la base de datos.', 'data' => ''];
    private $recordsNotFound = ['status' => 400, 'message' => '0 registros encontrados.', 'data' => ''];

    public function __construct()
    {
        $this->model = new Product();
        $this->stock = new Stock();
    }

    public function indexProductsFromPostgres()
    {
        try {
            $products = DB::table('products as p')
                ->select(
                                'p.code as barcode', 
                                'p.description as name',
                                'ps.stock as stock',
                                'pu.unitary_cost as cost', 
                                DB::raw("(pu.maximum_price + (pu.maximum_price * (t.aliquot/100))) as price")
                        )
                ->leftjoin('products_stock as ps', 'ps.product_code', 'p.code')
                ->leftjoin('products_units as pu', 'pu.product_code', 'p.code')
                ->leftjoin('taxes as t', 't.code', 'p.sale_tax')
                ->where('pu.main_unit', true)
                ->where('ps.stock', '>', 1)
                ->orderby('pu.maximum_price', 'desc')
                ->paginate(5000)
                ->toArray();

            // ->where('p.description', 'LIKE', '%COMPOTA%')

            $message  = $products['total'] . ' ' . Messages::$recordsFound;

            //return (new ApiResponse(true, $message, $products))->execute();

            //$response = Http::post('http://localhost:8001/api/recibir-inventario', [
            $response = Http::post('https://mercarapid.nks-sistemas.net/api/recibir-inventario', [
                    'store' => '91bc2ca9-be3c-40c6-9431-2512ab1d7c07',
                    'productos' => $products,
                ]);

            return ($response->ok())
                ? response()->json($response->body())
                : response()->json([$this->recordsNotFound]);

                //? response()->json(['status' => 200, 'message' => $message, 'data' => $products])

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
