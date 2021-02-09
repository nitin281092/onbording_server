<?php

use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SignInRequest;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;


Route::post('Registration', function (SignUpRequest $request) {
   
    $request["password"] = Hash::make($request["password"]);
       $ms = User::create($request->all());
    return $ms;
});
Route::post('Login', function (SignInRequest $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::once($credentials)) {
        return Auth::user();
    }
    else{
        $error["Error"][0] = "Email or Password is incorrect.";
        return response()->json($error, 401);
    }
});
Route::post('UploadImage', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'image' => 'required|image:jpeg,png,jpg,gif,svg|max:1024',
        'id' => 'required'
    ]);
    if ($validator->fails()) 
        throw new \Illuminate\Validation\ValidationException($validator,response()->json($validator->errors(), 422));
       $user = Auth::loginUsingId($request->id);
       if (!$user) {
        $error["Error"][0] = "User Not found.";
        return response()->json($error, 401);
    }
    
    $image = $request->file('image');
    $image_uploaded_path = $image->store("", ['disk' => 'local']);
    $user->image = $image_uploaded_path;
    $user->Save();
    return $user;
});
Route::post('RemoveImage', function (Request $request) {
    $validator = Validator::make($request->all(), [
       
        'id' => 'required'
    ]);
    if ($validator->fails()) 
        throw new \Illuminate\Validation\ValidationException($validator,response()->json($validator->errors(), 422));
       $user = Auth::loginUsingId($request->id);
       if (!$user) {
        $error["Error"][0] = "User Not found.";
        return response()->json($error, 401);
    }
    
    
    $user->image = null;
    $user->Save();
    return $user;
});

 

