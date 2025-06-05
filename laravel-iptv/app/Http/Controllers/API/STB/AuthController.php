<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\STB\LoginRequest;
use App\Http\Requests\API\STB\RegisterRequest as STBRegisterRequest;
use App\Interfaces\API\STB\IAuthRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

/**
* @group API AuthController
*
* Auth.
*/
class AuthController extends Controller
{   
    private $authRepository;

    public function __construct(IAuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
        // $this->middleware('permission:user.index|user.create|user.update|user.delete');
    }

    /**
     * @authenticated
     * @apiResource status=201 App\Http\Resources\API\STB\Auth\StbRegisterResource
     * @apiResourceModel App\Models\General\Body\Devices\Device
     * @apiResourceAdditional room_number="105" result=success message="STB has been registered"
     */
    public function stb_register(STBRegisterRequest $request)
    {   
        try {
            return $data = $this->authRepository->stb_register($request);
            // $token = $user->createToken('user_token')->plainTextToken;

            // return response()->json(['data' => $data], 200);

        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    public function stb_time()
    {
        try {
            $data = $this->authRepository->stb_time();

            return response()->json(['data' => $data, 'result' => 'success'], 200);

        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

     /**
     * @authenticated
     * @apiResource status=201 App\Http\Resources\API\STB\Auth\StbLoginResource
     * @apiResourceModel App\Models\User
     * @apiResourceAdditional token="24|fDeOD181eJOROGKvGxdDMSnEC8A6fwduMqVAeROcace50f17" result=success message="Successfully login!"
     */
    public function login(LoginRequest $request)
    {
        try {
            // dd($request['data']);
            return $this->authRepository->stb_authenticateDevice($request);
            
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

     /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceAdditional result=success
     */
    public function logout(Request $request)
    {   
        try {
            // return $this->repository->logoutUser($request->input('user_id'));

            $user = $user = User::findOrFail($request->input('id'));

            $user->tokens()->delete();

            return response()->json('User logged out!', 200);
            
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceAdditional result=success
     */
    public function checkToken(Request $request)
    {   
        try {
            return $this->authRepository->checkToken($request);
            
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

}
