<?php

namespace App\Http\Controllers\AdminApi;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OwnerCoupon;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\AdminRequests\CompanyRequest;
use App\Http\Requests\AdminRequests\UpdateCompanyRequest;
use App\Http\Resources\AdminResources\CompanyResource;
use App\Traits\CheckPermission;

class CompanyController extends BaseController
{
    use CheckPermission;

/**
 * @OA\Get(
 *      path="/api/admin/companies",
 *      operationId="1-get-company",
 *      tags={"companies"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Get list of company",
 *      description="Returns list of company",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="not found"
 *      ),
 * )
 */
    public function index()
    {
        $this->canDo('view-coupon-owners');
        
        $companies = Company::paginate(10);

        return $this->sendResponse(CompanyResource::collection($companies), __('messages.companySucessRetrieved'));
    }

/**
 * @OA\Post(
 *   path="/api/admin/companies",
 *   tags={"companies"},
 *   security={
 *      {"bearer_token":{}},
 *   },
 *   summary="Create company",
 *   operationId="2-create-company-data",
 *   @OA\RequestBody(
 *      required=true,
 *      description="Pass company data",
 *      @OA\JsonContent(
 *         required={"name", "description","email", "address" , "phone_number" , "long" , "lat" , "social_media" , "active"},
 *         @OA\Property(property="name", type="string", example="String"),
 *         @OA\Property(property="ar_name", type="string", example="تجربة"),
 *         @OA\Property(property="description", type="text", example="hello"),
 *         @OA\Property(property="ar_description", type="string", example="تجربة"),
 *         @OA\Property(property="email", type="string", example="lolo123@gmail.com"),
 *         @OA\Property(property="address", type="text" , example="bab_toma"),
 *         @OA\Property(property="ar_address", type="string", example="تجربة"),
 *         @OA\Property(property="phone_number", type="string" , example="0940568491"),
 *         @OA\Property(property="active", type="boolean" , example=1),
 *         @OA\Property(property="social_media", type="longText" , example="insta"),
 *      ),
 *   ),
 *   @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *         mediaType="application/json",
 *      )
 *   ),
 *   @OA\Response(
 *      response=401,
 *      description="Unauthenticated"
 *   ),
 *   @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 *   @OA\Response(
 *      response=404,
 *      description="Not Found"
 *   ),
 *   @OA\Response(
 *      response=403,
 *      description="Forbidden"
 *   )
 * )
 */



    public function store(CompanyRequest $request)
    {
        $this->canDo('add-coupon-owner');

        $company = Company::create($request->all());

        return $this->sendResponse(new CompanyResource($company), __('messages.companySucessCreate'));
    }

/**
 * @OA\Get(
 *      path="/api/admin/companies/{id}",
 *      operationId="3-get-company",
 *      tags={"companies"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Get company",
 *      description="Returns company information",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the company",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found"
 *      ),
 * )
 */
    public function show($id)
    {
        $this->canDo('show-coupon-owner');

        $company = Company::find($id);
        if (is_null($company)) {
            return $this->sendError(__('messages.companyNotFound'));
        }

    //return $this->sendResponse(new CompanyResource($company), 'company retrieved successfully.');


    return $this->sendResponse(new CompanyResource($company),__('messages.companySucessRetrieved'));
    }




/**
 * @OA\Put(
 *     path="/api/admin/companies/{id}",
 *     tags={"companies"},
 *     security={
 *         {"bearer_token":{}},
 *     },
 *     summary="company Update",
 *     operationId="4-Update-full-company",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         description="Update User data",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="String"),
 *             @OA\Property(property="ar_name", type="string", example="تجربة"),
 *             @OA\Property(property="description", type="text", example="hello"),
 *             @OA\Property(property="ar_description", type="string", example="تجربة"),
 *             @OA\Property(property="email", type="string", example="lolo123@gmail.com"),
 *             @OA\Property(property="address", type="text" , example="bab_toma"),
 *             @OA\Property(property="ar_address", type="string", example="تجربة"),
 *             @OA\Property(property="phone_number", type="string" , example="0940568491"),
 *             @OA\Property(property="active", type="boolean" , example=true),
 *             @OA\Property(property="social_media", type="longText" , example="insta")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     )
 * )
 */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $this->canDo('edit-coupon-owner');

        $company = Company::find($id);
        if (is_null($company)) {
            return $this->sendError(__('messages.companyNotFound'));
        }

        $company->update($request->all());

        return $this->sendResponse(new CompanyResource($company), __('messages.companySucessUpdated'));
    }



/**
 * @OA\Delete(
 *      path="/api/admin/companies/{id}",
 *      operationId="5-remove-company",
 *      tags={"companies"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Delete company",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json"
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request",
 *          @OA\MediaType(
 *              mediaType="application/json"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\MediaType(
 *              mediaType="application/json"
 *          )
 *      ),
 * )
 */
    public function destroy($id)
    {
        $this->canDo('delete-coupon-owner');

        $company = Company::find($id);
        if (is_null($company)) {
            return $this->sendError(__('messages.companyNotFound'));
        }

        try {

            $company->delete();

            return $this->sendResponse([], __('messages.companySucessDeleted'));
        } catch (\Throwable $e) {
            return $this->sendError(__('messages.companycanNotDeleted'));

        }
    }
}
