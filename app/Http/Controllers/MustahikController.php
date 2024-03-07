<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\MustahikDataTable;
use App\Models\Mustahik;
use App\Helpers\AuthHelper;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
 
class MustahikController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MustahikDataTable $dataTable)
    {
        $pageTitle = trans('global-message.list_form_title',['form' => trans('Mustahik')] );
        $auth_user = AuthHelper::authSession();
        $assets = ['data-table'];
        $headerAction = '<a href="'.route('mustahik.create').'" class="btn btn-sm btn-primary" role="button">Add Mustahik</a>';
        return $dataTable->render('global.datatable', compact('pageTitle','auth_user','assets', 'headerAction'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('status',1)->get()->pluck('title', 'id');

        return view('mustahik.form', compact('roles'));
    }

}
  