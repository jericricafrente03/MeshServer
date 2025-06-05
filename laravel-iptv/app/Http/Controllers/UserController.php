<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Interfaces\IUserHistoryLogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ApiKey;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller

{    
    private const PATH_EMP_AVATAR = 'upload/userprofile';
    protected $logHistoriesRepo;
    protected $activityName;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
    }

    public function generatekey()
    {
        // $user = auth()->check() ? Auth::user() : redirect()->route('login');

        // $apiKey = ApiKey::create([
        //     'key' => Str::random(40), // Generate a random API key
        //     'user_name' =>  strtolower(str_replace(' ', '', $user->name)),
        //     'user_id' => $user->id, // Associate the key with the user
           
        // ]);
    
        // return response()->json(
        //     ['api_key' => $apiKey->key,'user_id' => $apiKey->user_id]); 
    }

    public function security_profile()
    {
        $user = Auth::user();

        $breadCrumb = ['Profile', 'Security'];
        return view('/profile/userProfile/security', compact('user', 'breadCrumb'));
    }

    public function update_password(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            //code...
            $input = $request->all();   
            $oldData = User::where('id', $user->id)->first();
            $user = User::where('id', $user->id)->first();
            $helper = new Helper;
            $validation = Validator::make($input, [ 
                'password_confirmation' => 'required',
                'current_password' => 'required|current_password',
                'password' => [ ($input['password'] != '')?
                                Password::min(8)
                                    ->mixedCase()
                                    ->numbers()
                                    ->symbols():'','confirmed','required'],
            ]);
            if ($validation->passes()){
                       
                $user->password = Hash::make($input['password']);
                $user->save();

                $oldDataArray = $oldData->toArray();
                $newDataArray = $user->toArray();

                // dd(($input['password'] != $input['current_password'])?true:false);
                if ($input['password'] != $input['current_password']) {
                    $oldDataArray['password'] = 'old entry';
                    $newDataArray['password'] = 'new entry';
                }
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, 'security password');
                
                DB::commit();

                return redirect()->back()->with('success', 'success')->with('success_body', 'Password Updated!');
            }
            else{
                return back()->withErrors($validation);  
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in UserController.updateProfile.'
            ]);
        }
        
    }

    public function profile_view()
    {
        $user = Auth::user();
        
        $viewPath = '/profile/userProfile/index';
    
        return view($viewPath, compact('user'));
    }

    public function update_avatar(Request $request)
    {
        if($request->ajax()){

            try {
                DB::beginTransaction();
                try {   
                    $data = json_decode($request->data);   
                    $oldData = User::findOrFail($data->id);              
                    $user = User::findOrFail($data->id);
                    if ($request->file('image')) {
                        $file = $request->file('image');
                        @unlink(public_path(self::PATH_EMP_AVATAR.'/'.$user->image));
                        $fileName = date('YmdHi').'_'.$file->getClientOriginalName();
                        $file->move(public_path(self::PATH_EMP_AVATAR), $fileName);
                        $user->image = $fileName;
                    }
                    $save = $user->save();

                    $this->logHistoriesRepo->update($oldData->toArray(), $user->toArray(), 'profile avatar');

                    if(!$save) {
                        throw "Not saved";
                    }

                    DB::commit();

                    return json_encode([
                        'data'=> $user,
                        'status'=>'success',
                        'message'=>'Record has been updated.'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack(); 
                    return response()->json([
                        'error' => $e->getMessage(),
                        'message' => 'Something went wrong in UserController.update_avatar.db_transaction.'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in UserController.update_avatar.'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    } 

}


