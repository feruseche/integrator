<?php

namespace Core\Products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Core\Helpers\{ApiResponse, Messages};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class ProductController extends Controller
{

    private $errorServer = ['status' => 500, 'message' => 'Error de comunicación con el servidor de la base de datos.', 'data' => ''];
    private $recordsNotFound = ['status' => 400, 'message' => '0 registros encontrados.', 'data' => ''];

    public function indexProductsFromPostgres()
    {
        try {
            $products = DB::table('products as p')
                ->selectRaw("
                                p.code barcode, p.description name, p.sale_tax,
                                t.aliquot,
                                ps.stock stock,
                                pu.unitary_cost, pu.minimum_price, pu.maximum_price, pu.offer_price, pu.higher_price,
                                (pu.maximum_price + (pu.maximum_price * (t.aliquot/100))) price
                            ")
                ->leftjoin('products_stock as ps', 'ps.product_code', 'p.code')
                ->leftjoin('products_units as pu', 'pu.product_code', 'p.code')
                ->leftjoin('taxes as t', 't.code', 'p.sale_tax')
                ->where('pu.main_unit', true)
                ->where('ps.stock', '>', 0)
                ->where('p.description', 'LIKE', '%ACEITE%')
                ->orderby('pu.maximum_price', 'desc')
                ->paginate(5000)
                ->toArray();

            $message  = $products['total'] . ' ' . Messages::$recordsFound;

            return (new ApiResponse(true, $message, $products))->execute();

/*            $response = Http::post('http://127.0.0.1:8001/api/enviar-inventario', [
                    'uuid' => '91bc2ca9-be3c-40c6-9431-2512ab1d7c07',
                    'productos' => $products,
                ]);*/

/*            return ($response->ok())
                ? response()->json(['status' => 200, 'message' => $message, 'data' => $products])
                : response()->json([$this->recordsNotFound]);
*/
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
