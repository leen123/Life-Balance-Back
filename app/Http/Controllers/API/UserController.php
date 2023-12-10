<?php

namespace App\Http\Controllers\API;


use App\Enums\UserType;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserSections as UserPoints;
use App\Http\Resources\Journal as JournalResource ;
use App\Journal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



class UserController extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/register",
     *   tags={"Users"},
     *   summary="Register",
     *   operationId="1-register",
     *
     *     @OA\RequestBody(
     *    required=false,
     *    description="Pass User data",
     *      @OA\JsonContent(
     *        @OA\property(property="name", type="string", example="Ibrahim"),
     *        @OA\property(property="userName", type="string", example="ibrahim"),
     *        @OA\property(property="image", type="string", example="image"),
     *        @OA\property(property="email", type="string", example="IbrahimRahme@circlopedia.com"),
     *        @OA\property(property="password", type="string", example="12345678"),
     *        @OA\property(property="ConfirmPassword", type="string", example="12345678"),
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

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $messages = [
            'unique' => 'The :attribute field already taken.',
        ];
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string',
                'userName' => 'required|unique:users|string',
                'email' => 'required|unique:users|email',
                'password' => 'required',
                'ConfirmPassword' => 'required|same:password',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors(), 400);
        }

        $user = new User();

        $user->name = $request->name ;
        $user->userName = $request->userName;
        $user->image = isset($request->image) ? $request->image : " ";
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->ConfirmPassword = bcrypt($request->ConfirmPassword);
        $user->is_active = false;
        $user->plan = 0;
        $user->type = UserType::player;
        $user->save();
        return $this->sendSuccess(__('messages.userSucessregistered'));
    }


    /**
     * @OA\Put(
     ** path="/api/update",
     *   tags={"Users"},
     *   security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update",
     *   operationId="2-Update",
     *
     *     @OA\RequestBody(
     *    required=false,
     *    description="Update User data",
     *      @OA\JsonContent(
     *        @OA\property(property="firstName", type="string", example="Ahmad"),
     *        @OA\property(property="middleName", type="string", example="Abdo"),
     *        @OA\property(property="lastName", type="string", example="As3ad"),
     *        @OA\property(property="userName", type="string", example="Ahmad98"),
     *        @OA\property(property="image", type="string", example="AhmadAbdo.png")
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

    public function updateUser(Request $request)
    {
        $messages = [
            'unique' => 'The :attribute field already taken.',
        ];
        $validator = Validator::make($request->all(),
            [
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'userName' => 'required|unique:users|string',
                'email' => 'required|unique:users|email',
                'password' => 'required|numeric',
                'ConfirmPassword' => 'required|numeric|same:password',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors());
        }
        $id = Helper::user()->id;
        if ($id) {
            $user = User::find($id);
            if (is_null($user)) {
                return $this->sendError(__('messages.userNotFound'));
            }
            $user->firstName = $request->firstName;
            $user->middleName = $request->middleName;
            $user->lastName = $request->lastName;
            $user->name = $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName;
            $user->userName = $request->userName;
            $user->image = isset($request->image) ? $request->image : " ";
            $user->save();
            return $this->sendResponse(new UserResource($user), __('messages.userSucessEdit'));
        }
    }


    /**
     * @OA\PUT(
     ** path="/api/changePassword",
     *   tags={"Users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Update Password ",
     *   operationId="update-Password",
     *
     *   @OA\RequestBody(
     *    description="Pass Password data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="oldPassword", type="number", example=1234567888),
     *       @OA\Property(property="newPassword", type="number", example=1234567888),
     *    ),
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
     **/
    public function changePassword(Request $request)
    {
        $user = User::find($request->id);
        $input = $request->all();
        if (is_null($user)) {
            return $this->sendError(__('messages.userNotFound'));
        }
        $validator = Validator::make($input, [
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError(__('messages.validError'), $validator->errors());
        }
        if (Hash::check($input['oldPassword'], $user->password)) {
            $user->password = bcrypt($input['newPassword']);
            $user->ConfirmPassword = bcrypt($input['newPassword']);
            $user->save();
            return $this->sendResponse(new UserResource($user), __('messages.changingSucessPassword'));
        } else {
            return $this->sendError(__('messages.notMatchPassowrd'));
        }
    }


    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="2-authLogin",
     * tags={"Users"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password","ConfirmPassword"},
     *       @OA\Property(property="email", type="string", format="email", example="IbrahimRahme@circlopedia.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678")
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        if ($request->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError(__('messages.validError'), $validator->errors(), 400);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
//                if($request->user()->hasRole('player')) {

//                if ($request->user()->can('login')) {
                $success['token'] = $user->createToken('2BKkxJjWgc')->accessToken;
                return $this->sendResponse($success, __('messages.userSucesslogin'));
//                } else{
//                    return $this->sendError('no permission.', ['error' => $request->user()->can('login')] , 403);
//                }
            } else {
                return $this->sendError(__('messages.emailOrPasswordUncorrected'), [], 403); //$this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'userName' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError(__('messages.validError'), $validator->errors(), 400);
            }
            if (Auth::attempt(['userName' => $request->userName, 'password' => $request->password])) {
                $user = Auth::user();

//                if ($request->user()->can('login')) {
                $success['token'] = $user->createToken('2BKkxJjWgc')->accessToken;
                return $this->sendResponse($success, __('messages.userSucesslogin'));
//                } else {
//                    return $this->sendError('no permission.', ['error' => 'no permission'], 403);
//                }
            } else {
                return $this->sendError(__('messages.UsernameOrPasswordUncorrected'), [], 403); //$this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        }
    }



    /**
     * @OA\Get(
     *      path="/api/all-user",
     *      operationId="3-getUserList",
     *      tags={"Users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of users",
     *      description="Returns list of users",
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
    public function getAllUser()
    {
        $products = User::all();

        return $this->sendResponse(UserResource::collection($products), __('messages.usersSucessRetrieved'));
    }


    /**
     * @OA\Get(
     *      path="/api/getProfile",
     *      operationId="3-getProfile",
     *      tags={"Users"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get Profile",
     *      description="Returns Profile",
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
    public function getProfile()
    {
        $user = request()->user();

        return $this->sendResponse(new UserResource($user), __('messages.getSucessProfile'));
    }

        /**
     * @OA\Get(
     *      path="/api/getHomeData",
     *      operationId="3-getHomeData",
     *      tags={"Home Page"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get Home Data",
     *      description="Returns Home Data",
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
    public function getHomeData()
    {
        $user = request()->user();

        return $this->sendResponse(new UserPoints($user), __('messages.getSucessProfile'));
    }


        /**
     * @OA\Post(
     ** path="/api/journal",
     *   tags={"Journal"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="journal",
     *   operationId="1-journal",
     *
     *     @OA\RequestBody(
     *    required=false,
     *    description="Pass journal date",
     *      @OA\JsonContent(
     *        @OA\property(property="date", type="string", example="2021-01-01"),
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

    public function getJournalData(Request $request)
    {
        $user = request()->user();

        $input = $request->all();

        $date = !isset($input['date']) ?  Carbon::now() : Carbon::createFromFormat('Y-m-d', $input['date']) ;

        $journals = Journal::where(['user_id' => $user->id])->whereDate('date', '=', $date)->get();


        return $this->sendResponse(JournalResource::collection($journals), __('messages.getSucessjournal'));
    }







}
