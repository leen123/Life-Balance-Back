<?php


namespace App\Http\Controllers\AdminApi;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequests\EmployeeRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AdminResources\EmployeeResource;
use App\Traits\CheckPermission;

class EmployeeController extends BaseController
{
    use CheckPermission;

      /**
     * @OA\Get(
     *      path="/api/admin/employees",
     *      operationId="1-get-employees",
     *      tags={"employees"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get list of employees",
     *      description="Returns list of employees",
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
        $this->canDo('view-users');

        $employees =Employee::paginate(10);

        return $this->sendResponse(EmployeeResource::collection($employees), __('messages.employeeSucessRetrieved'));
    }

 /**
     * @OA\Post(
     ** path="/api/admin/employees",
     *   tags={"employees"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete employees  ",
     *   operationId="2-create-employees-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass employees data",
     *    @OA\JsonContent(
     *       required={"name", "ar_name","email", "address" , "number" , "company_id" , "ar_address" },
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="email", type="string", example="lolo@gmail.com"),
     *       @OA\Property(property="address", type="string", example="String"),
     *       @OA\Property(property="ar_address", type="string", example="تجربة"),
     *       @OA\Property(property="number", type="integer", example="0940568491"),
     *       @OA\Property(property="company_id", type="number" , example=1),
     *
     *    ),
     *
     * ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)


     *)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        $this->canDo('add-user');

        $employees = Employee::create($request->all());

        return $this->sendResponse(new EmployeeResource($employees), __('messages.employeeSucessAdded'));
    }

    

   /**
     * @OA\Get(
     *      path="/api/admin/employees/{id}",
     *      operationId="3-get-employees",
     *      tags={"employees"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get employees",
     *      description="Returns employees information",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function show($id)
    {
        $this->canDo('show-user');

        $employees = Employee::find($id);
        if (is_null($employees)) {
            return $this->sendError(__('messages.employeeNotFound'));
        } 

        return $this->sendResponse(new EmployeeResource($employees),  __('messages.employeeSucessRetrieved'));
    }

    
    /**
     * @OA\Put(
     ** path="/api/admin/employees/{id}",
     *   tags={"employees"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="employees Update",
     *   operationId="4-Update-full-employees",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *     @OA\RequestBody(
     *    required=false,
     *    description="Update employees data",
     *      @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="ar_name", type="string", example="تجربة"),
     *       @OA\Property(property="email", type="string", example="lolo@gmail.com"),
     *       @OA\Property(property="address", type="string", example="String"),
     *       @OA\Property(property="ar_address", type="string", example="تجربة"),
     *       @OA\Property(property="number", type="integer", example="0940568491"),
     *       @OA\Property(property="company_id", type="number" , example=1),
     *      ),
     * ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function update(EmployeeRequest $request, $id)
    {
        $this->canDo('edit-user');

        $employees = Employee::find($id);
        if (is_null($employees)) {
            return $this->sendError(__('messages.employeeNotFound'));
        } 


        $employees->update($request->all());

        return $this->sendResponse(new EmployeeResource($employees),  __('messages.employeeNotFound'));
    }

    /**
     * @OA\Delete(
     *      path="/api/admin/employees/{id}",
     *      operationId="5-remove-employees",
     *      tags={"employees"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="delete employees",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function destroy($id)
    {
        $this->canDo('delete-user');

        $employees = Employee::find($id);
        if (is_null($employees)) {
            return $this->sendError(__('messages.employeeNotFound'));
        } 


        try {

            $employees->delete();

            return $this->sendResponse([],  __('messages.employeeSucessDeleted'));
        } catch (\Throwable $e) {
            return $this->sendError(__('messages.employeecanNotDeleted'));

        }
    }
}
