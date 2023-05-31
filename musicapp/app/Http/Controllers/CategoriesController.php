<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Validator,Redirect,Response;
use DateTime;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index()
    {
        $loginUser = Auth::user();
        if ($loginUser) {
            return view('categories.index');
        }
        return Redirect('login');
    }

    public function get(Request $request)
    {
        $data = Categories::query()->where('is_deleted', '=', 0);
        return datatables()::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->escapeColumns([])
            ->make(true);die();
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $category = Categories::where('id', $id)->update(['status' => $status]);

        if ($category) {
            $result = ['status' => true, 'message' => __('Status changed successfully.'), 'data' => []];
        } else {
            $result = ['status' => false, 'message' => __('Error in saving data.'), 'data' => []];
        }
        return response()->json($result);exit();
    }

    public function addupdate(Request $request)
    {
        if ($request->ajax()) {
            $rules = array(
                'category_name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($request->id)],
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result = ['status' => false, 'error' => $validator->errors()];
            } else {
                $succssmsg = 'Category created successfully.';
                if ($request->id) {
                    $model = Categories::where('id', $request->id)->first();
                    if ($model) {
                        $category = $model;
                        $succssmsg = 'Category updated successfully.';
                    } else {
                        $result = ['status' => false, 'message' => 'Invalid request.', 'data' => []];
                        return response()->json($result);
                    }
                } else {
                    $category = new Categories;
                    $category->status = '1';
                }

                $category->category_name = ($request->category_name) ? $request->category_name : NULL;
                $category->updated_at = Carbon::now();

                if($category->save()) {
                    $result = ['status' => true, 'message' => $succssmsg, 'data' => []];
                } else {
                    $result = ['status' => false, 'message' => 'Error in saving data.', 'data' => []];
                }
            }
        } else {
            $result = ['status' => false, 'message' => 'Invalid request.', 'data' => []];
        }
        return response()->json($result);
    }

    public function detail(Request $request)
    {
        $result = ['status' => false, 'message' => ""];

        if ($request->ajax()) {
            $category = Categories::find($request->id);
            $result = ['status' => true, 'message' => '', 'data' => $category];  
        }
        return response()->json($result);exit();
    }

    public function delete(Request $request)
    {
        $catgeory = Categories::where('id', $request->id)->update(array('status' => '0', 'is_deleted' => '1'));

        if ($catgeory) {
            $result = ['status' => true, 'message' => 'Category deleted successfully.'];
        } else {
            $result = ['status' => false, 'message' => 'Error in saving data.'];
        }
        return response()->json($result);
    }
}
