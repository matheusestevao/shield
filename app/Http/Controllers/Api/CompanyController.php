<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $this->companyService->index($request);

        $addressCompanies = $companies->map(function($company) {
            $addresses = $company->addresses->map(function($address) {
                $groupedTypes = $address->types->groupBy('type_address_id');
                
                $mappedTypes = $groupedTypes->map(function($types) {
                    return $types->pluck('type_address_id')->toArray();
                });
        
                return [
                    'zip_code' => $address->zip_code,
                    'address' => $address->address,
                    'number_address' => $address->number_address,
                    'complement_address' => $address->complement_address,
                    'neighborhood' => $address->neighborhood,
                    'state' => $address->state,
                    'city' => $address->city,
                    'types' => $mappedTypes,
                ];
            });
        
            return [
                'id' => $company->id,
                'company_name' => $company->company_name,
                'trade_name' => $company->trade_name,
                'corporate_registry_number' => $company->corporate_registry_number,
                'addresses' => $addresses,
            ];
            
        });

        return response()->json($addressCompanies);
    }
}
