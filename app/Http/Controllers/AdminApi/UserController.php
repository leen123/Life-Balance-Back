<?php

namespace App\Http\Controllers\AdminApi;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\AdminRequests\UpdateUserRequest;
use App\Http\Requests\AdminRequests\UserRequest;
use App\Http\Resources\AdminResources\UserResource;
use App\Traits\CheckPermission;
use App\Traits\Media;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use Media,CheckPermission;

    /**
     * @OA\Get(
     *      path="/api/admin/users",
     *      operationId="1-get-users",
     *      tags={"ads"},
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      summary="Get list of users",
     *      description="Returns list of users",
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
    public function index(Request $request)
    {
        $this->canDo('view-users');

        $users = User::query();

        if ($request->type) {

            $users->where('type', $request->type);
        }

        $users = $users->paginate(10);

        return $this->sendResponse(UserResource::collection($users), __('messages.usersSucessRetrieved'));
    }

    /**
     * @OA\Post(
     ** path="/api/admin/users",
     *   tags={"users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Crete users  ",
     *   operationId="2-create-users-data",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass users data",
     *    @OA\JsonContent(
     *       required={"name", "email","password" , "user_name"  , "ConfirmPassword","image","type"},
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="email", type="string", example="lolo@gmail.com"),
     *       @OA\Property(property="password", type="string", example="12345678pp"),
     *       @OA\Property(property="ConfirmPassword", type="string", example="12345678pp"),
     *       @OA\Property(property="user_name", type="string", example="leen"),
     *       @OA\Property(property="type", type="string" , example="user"),
     *       @OA\property(property="image", type="string", example="Ahmad.png")
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
    public function store(UserRequest $request)
    {
        $this->canDo('add-user');

        $data = $request->all();

        $user = new User();

        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->userName = $data['user_name'];
        $user->password = Hash::make($data['password']);
        $user->ConfirmPassword =  Hash::make($data['password_confirmation']);;
        $user->type = $data['type'];
        $user->image = $data['image'] ?? null;

        $user->save();

        if ($user->type == UserType::systemAdmin) {

            $user->assignRole($request->role);
        }

        return $this->sendResponse(new UserResource($user), __('messages.usersSucessCreated'));
    }

    /**
     * @OA\Get(
     *      path="/api/admin/users/{id}",
     *      operationId="3-get-users",
     *      tags={"users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get users",
     *      description="Returns users information",
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

        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('user not found.');
        } 

        return $this->sendResponse(new UserResource($user), __('messages.usersSucessRetrieved'));
    }
 /**
     * @OA\Put(
     ** path="/api/admin/users/{id}",
     *   tags={"users"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="users Update",
     *   operationId="4-Update-full-users",
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
     *    description="Update users data",
     *      @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="String"),
     *       @OA\Property(property="email", type="string", example="lolo@gmail.com"),
     *       @OA\Property(property="user_name", type="string", example="leen"),
     *       @OA\Property(property="type", type="string" , example="user"),
     *       @OA\property(property="image", type="string", example="Ahmad.png")
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
    
    public function update(UpdateUserRequest $request, $id)
    {
        $this->canDo('edit-user');


        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('user not found.');
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->userName = $request->user_name;
        $user->type = $request->type;
        $user->image = $request->image ?? null;

        if ($request->password) {

            $user->password = Hash::make($request->password);
            $user->ConfirmPassword =  Hash::make($request->password);
        }


        $user->save();


        if ($user->type == UserType::systemAdmin) {

           $user->syncRoles($request->role);
        }

        return $this->sendResponse(new UserResource($user), __('messages.usersSucessCreated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->canDo('delete-user');

        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError(__('messages.employeeNotFound'));
        } 

        try {
            $user->delete();
            return $this->sendResponse([],  __('messages.employeeSucessDeleted'));
        } catch (\Throwable $e) {
            return $this->sendError(__('messages.employeecanNotDeleted'));
        }
    }
}
