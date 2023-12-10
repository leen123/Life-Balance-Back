<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    use CheckPermission;
 /**
 * @OA\Get(
 *      path="/api/roles",
 *      operationId="getRolesList",
 *      tags={"Roles"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Get list of roles",
 *      description="Returns a paginated list of roles",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(ref="#/components/schemas/Role")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      )
 * )
 */
    public function index()
    {
        $this->canDo('view-roles');

        $roles = Role::paginate(10);

        return response(['data' => $roles], 200);
    }


/**
 * @OA\Post(
 *      path="/api/roles",
 *      operationId="createRole",
 *      tags={"Roles"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Create a new role",
 *      description="Creates a new role with the specified name and permissions",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name", "permissions"},
 *              @OA\Property(property="name", type="string", maxLength=255),
 *              @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Role created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="role", ref="#/components/schemas/Role"),
 *                  @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Validation error"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      )
 * )
 */
    
     /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->canDo('add-role');

        $request->validate([
            'name' => ['max:255', 'unique:roles'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $permissions = $request->permissions;

        $role = Role::create(['name' => $request->name]);

        $role->givePermissionTo($permissions);


        return response()->json(['data' => ['role' => ['id' => $role->id, 'name' => $role->name], 'permissions' => $permissions]],201);
    }

/**
 * @OA\Get(
 *      path="/api/roles/{id}",
 *      operationId="getRoleById",
 *      tags={"Roles"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Get role by ID",
 *      description="Retrieves a role by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          description="Role ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Role retrieved successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="data", ref="#/components/schemas/Role")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Role not found"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      )
 * )
 */
    public function show($id)
    {
        $this->canDo('show-role');

        $role = Role::select('id','name')->with('permissions:id,name')->where('id',$id)->first();
        if (is_null($role)) {
            return $this->sendError('role not found.');
        } 

        return response()->json(['data' => ['role' => $role]],200);

    }

/**
 * @OA\Put(
 *      path="/api/roles/{id}",
 *      operationId="updateRole",
 *      tags={"Roles"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Update role",
 *      description="Updates a role",
 *      @OA\Parameter(
 *          name="id",
 *          description="Role ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/RoleUpdateRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Role updated successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="data", ref="#/components/schemas/Role")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Role not found"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error"
 *      )
 * )
 */
    public function update(Request $request, $id)
    {
        $this->canDo('edit-role');

        $request->validate([
            'name' => ['max:255',  Rule::unique('roles')->ignore($id)],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $permissions = $request->permissions;

        $role = Role::find($id);
        if (is_null($role)) {
            return $this->sendError('role not found.');
        } 

        $role->update(['name' => $request->name]);

        $role->syncPermissions($permissions);


        return response()->json(['data' => ['role' => ['id' => $role->id, 'name' => $role->name], 'permissions' => $permissions]],200);

    }

   /**
 * @OA\Delete(
 *      path="/api/roles/{id}",
 *      operationId="deleteRole",
 *      tags={"Roles"},
 *      security={
 *          {"bearer_token":{}},
 *      },
 *      summary="Delete role",
 *      description="Deletes a role",
 *      @OA\Parameter(
 *          name="id",
 *          description="Role ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Role deleted successfully"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Role not found"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      )
 * )
 */
    public function destroy($id)
    {
        $this->canDo('delete-role');

        $role = Role::find($id);
        if (is_null($role)) {
            return $this->sendError('role not found.');
        } 

        $role->delete();

        return response()->json(['data' => null], 204);

    }
}
