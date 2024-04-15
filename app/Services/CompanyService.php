<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    /**
     * Display a listing of the companies
     * 
     * @param Request $request
     * @return \Illuminate\Pagination\Paginator
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'updated_at');
        $sortOrder = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);

        if ($request->isMethod('post')) {
            $this->setFilter($request);
        }

        $companies = Company::with('address')
                            ->select('company_name', 'trade_name', 'corporate_registry_number', 'companies.updated_at');
        $companies = $this->applyFilters($companies, session('company_filter'));
        $companies->orderBy($sortField, $sortOrder);

        $companies = $companies->paginate($perPage);

        return $companies;
    }

    /**
     * Apply filters to the list of companies
     * 
     * @param \Illuminate\Database\Eloquent\Builder|null.
     * @param array|null $filters .
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    private function applyFilters(?object $companies, ?array $filters): ?object
    {
        if (!empty($filters)) {
            $companies->when(!empty($filters['zip_code']), function($query) use ($filters) {
                $query->whereHas('address', function($subQuery) use ($filters) {
                    $subQuery->where('zip_code', $filters['zip_code']);
                });
            });
        }
        
        return $companies;
    }

    /**
     * Set filter for company listing.
     *
     * @param Request $request.
     * @return void
    */
    private function setFilter(Request $request): void
    {
        $filter = [
            'zip_code' => $request->input('zip_code', null)
    ];

        session(['company_filter' => $filter]);
    }

    public function store(Request $request): ?object
    {
        try {
            DB::beginTransaction();

            $company = Company::create([
                'company_name' => $request->input('company_name'),
                'trade_name' => $request->input('trade_name'),
                'corporate_registry_number' => $request->input('corporate_registry_number')
            ]);

            $this->storeCompanyAddress($request, $company);

            DB::commit();

            return $company;
        } catch (\Throwable $th) {
            DB::rollBack();

            Helper::log_message($th, 'companies', 'error');

            return null;
        }
    }

    private function storeCompanyAddress(Request $request, object $company)
    {
        $post = $request->all();

        try {
            DB::beginTransaction();

            unset($post['companyAddress_idComponent']['X']);

            foreach($post['companyAddress_idComponent'] as $key => $value) {
                $companyAddress = $company->addressRelationship()->create([
                    'zip_code' => $post['companyAddress_zip_code'][$key],
                    'address' => $post['companyAddress_address'][$key],
                    'number_address' => $post['companyAddress_number'][$key],
                    'complement_address' => $post['companyAddress_complement'][$key],
                    'neighborhood' => $post['companyAddress_neighborhood'][$key],
                    'state' => $post['companyAddress_state'][$key],
                    'city' => $post['companyAddress_city'][$key]
                ]);

                foreach ($post['companyAddress_'][$key] as $typeAddress => $data) {
                    $companyAddress->typeRelationship()->create([
                        'type_address_id' => $post['companyAddress_typeAddress'][$key][$typeAddress]
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Helper::log_message($th, 'companies', 'error');

            return null;
        }
    }
}