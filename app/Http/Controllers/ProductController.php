<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class ProductController extends Controller
{
    public function index(Request $request): object
    {
        $data = Products::with(['brand', 'model', 'model.capacity', 'type'])
            ->paginate($request->itemsPerPage ?: 15);

        $data->setCollection($data->getCollection()->transform(function($item, $index) use($request, $data) {
            $item->number = $index + ($data->currentPage() - 1) * $request->itemsPerPage + 1;
            return $item;
        }));

        return $this->response(true, $data);
    }

    public function batchUpload(Request $request): object
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file'
        ]);

        if($validator->fails()) {
            return $this->response(false, '', $validator->errors(), 400);
        }

        try {
            (new ProductsImport)->queue($request->file('file'), null, Excel::XLSX);

            return $this->response(true, 'Uploaded Successfully');
        } catch(Exception $ex) {
            return $this->response(false, '', $ex->getMessage(), 500);
        }
    }

    private function response(bool $status, $data, string $message = '', int $code = 200)
    {
        if($status) {
            $response = [
                'status' => $status,
                'data' => $data,
                'code' => $code
            ];
        } else {
            $response = [
                'status' => $status,
                'message' => $message,
                'code' => $code
            ];
        }

        return response()->json($response);
    }
}
