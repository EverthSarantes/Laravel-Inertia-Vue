<?php

namespace App\Http\Controllers\Api\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\GeneralExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new GeneralExcelExport($request->table_html, $request->file_name), "{$request->file_name}.xlsx");
    }
}
