<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TypeAddress;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function __invoke()
    {
        Session::forget('company_filter');

        return redirect()->route('company.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $this->companyService->index($request);
        $totalRecords = $companies->total();

        $sortField = $request->input('sort', 'updated_at');
        $sortOrder = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);

        return view('company.index', 
            compact('companies', 'totalRecords', 'sortField', 'sortOrder', 'perPage')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typesAddress = TypeAddress::all();

        return view('company.create', compact('typeAddress'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $company = $this->companyService->store($request);

        if ($company instanceof Company) {
            return view('company.index')->with('success', 'Empresa Cadastrada com Sucesso');
        }

        return redirect()->back()->with('error', 'Não foi possível cadastrar a empresa. Verifique todos os campos e tente novamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
