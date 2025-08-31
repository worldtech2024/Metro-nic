<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\AdminResource;
use App\Mail\AuthMail;
use App\Models\Admin;
use App\Trait\ApiFilterPaginate;
use App\Trait\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthAdminController extends Controller
{
    use ApiFilterPaginate;
    public function register(Request $request)
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'username'   => 'required|string|unique:admins,username',
            'name'       => 'required|string',
            'email'      => 'required|string|email|unique:admins,email',
            'phone'      => 'required|string|unique:admins,phone',
            'password'   => 'required|string|min:8',
            'image'      => 'nullable|image',
            'role'       => 'required|in:admin,control,power,sales',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = Admin::create($data);

        Mail::to($user->email)->send(new AuthMail($user->otp));

        return ApiResponse::sendResponse(
            true,
            'employee Created successfully',
            new AdminResource($user)
        );
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'country_id' => 'sometimes|exists:countries,id',
            'name'       => 'sometimes|string',
            'username'   => 'sometimes|string|unique:admins,username,' . $admin->id,
            'email'      => 'sometimes|email|unique:admins,email,' . $admin->id,
            'image'      => 'nullable|image',
            'phone'      => 'sometimes|string|unique:admins,phone,' . $admin->id,
            'role'       => 'sometimes|in:admin,control,power,sales',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $admin->update($data);

        return ApiResponse::sendResponse(true, 'Admin updated successfully', new AdminResource($admin));

    }
    public function index(Request $request)
    {

        $employee = $this->filterPaginateResource(
            $request,
            Admin::query()->latest(),
            ['name', 'email', 'phone', 'country_id','orders'],
            ['country'],
            AdminResource::class,
            10
        );

        return ApiResponse::sendResponse(true, 'Employees Retrieved Successfully', $employee);

    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username'  => 'required|string|exists:admins,username',
                'password'  => 'required|string|min:8',
                'fcm_token' => 'sometimes|string',
                'type'      => 'required|in:app,dashboard',
            ]);

            $user = Admin::where('username', $request->username)->first();
            if (! $user) {
                return ApiResponse::errorResponse(false, 'Username does not exist');
            }

            if (! Hash::check($request->password, $user->password)) {
                return ApiResponse::errorResponse(false, 'Invalid credentials.');
            }

            if ($request->type === 'app' && $user->role === 'admin' || $user->role === 'controller' || $user->role === 'power') {
                return ApiResponse::errorResponse(false, 'Admins cannot login from app');
            }

            if ($request->type === 'dashboard' && $user->role === 'sales') {
                return ApiResponse::errorResponse(false, 'sales cannot login from dashboard');
            }

            if ($request->filled('fcm_token') && $user->fcm_token !== $request->fcm_token) {
                $user->update(['fcm_token' => $request->fcm_token]);
            }

            $user->tokens()->delete();
            $user["token"] = $user->createToken('Bearer', ['app:all'])->plainTextToken;

            return ApiResponse::sendResponse(true, 'Login Successful!', new AdminResource($user));
        } catch (Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if (! $user) {
                return ApiResponse::errorResponse(false, 'No authenticated user');
            }
            $user->currentAccessToken()->delete();
            return ApiResponse::sendResponse(true, 'Logout Successful!');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'   => 'required|string',
            'email' => 'required|exists:admins,email',
        ]);
        $user = Admin::where('email', $request->email)->first();

        if (! $user) {
            return ApiResponse::errorResponse(false, 'User not found');
        }

        if ($user->otp != $request->otp) {
            return ApiResponse::errorResponse(false, 'Invalid OTP.');
        }

        return ApiResponse::sendResponse(true, 'Email verified successfully');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'exists:admins,email',
                ],
            ]);

            $otp  = rand(1111, 9999);
            $user = Admin::where('email', $request->email)->first();

            if (! $user) {
                return ApiResponse::sendResponse(false, 'User not found');
            }
            $user->update(['otp' => $otp]);
            Mail::to($user->email)->send(new AuthMail($user->otp));

            return ApiResponse::sendResponse(true, 'OTP sent successfully. Please verify to reset your password.', [
                'otp' => $user->otp,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'email'    => [
                    'required',
                    'string',
                    'exists:admins,email',
                ],
                'password' => 'required|string|min:8|confirmed',

            ]);

            $user = Admin::where('email', $request->email)->first();

            if (! $user) {
                return ApiResponse::sendResponse(false, 'User not found');
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return ApiResponse::sendResponse(true, 'Password reset successfully.');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());

        }
    }
    public function profile()
    {
        $user = Auth::user();
        return ApiResponse::sendResponse(true, 'Data Retrieve Successfully', new AdminResource($user));
    }
    public function updateProfile(UpdateProfileRequest $request)
    {

        $user = Auth::user();
        $data = $request->validated();

        if (! isset($data['image']) || $data['image'] === null) {
            unset($data['image']);
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $path = 'uploads/images/admins';

            if (! empty($user->image)) {
                $oldImagePath = str_replace('storage/', '', $user->image);

                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            $uploadedFilePath = $file->store($path, 'public');
            $data['image'] = 'storage/' . $uploadedFilePath;
        }

        if (! isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return ApiResponse::sendResponse(true, 'Data Updated Successfully', new AdminResource($user));
    }

    public function resetCode(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|string|exists:admins,email',
            ]);
            $user = Admin::where('email', $request->email)->first();
            if (! $user) {
                return ApiResponse::errorResponse(false, 'User not found');
            }
            $data['otp'] = rand(1111, 9999);
            $user->update($data);
            Mail::to($user->email)->send(new AuthMail($user->otp));
            return ApiResponse::sendResponse(true, 'Code Resend Successful', [
                'otp' => $user->otp,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }
}
