<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
// use Firebase\JWT\ExpiredException;

use Illuminate\Support\Facades\Mail;
use App\ChangePasswordEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Log;

use Illuminate\Support\Facades\Gate;

class UserController extends Controller {
	
	private $request;
	
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	protected function create_jwt(User $user) {
		
		$payload = [
			'iss' => 'jwt-todo-iss',
			'sub' => $user->id,
			'iat' => time(),
			'exp' => time() + 60 * 60,
		];
		
		return JWT::encode($payload, env('JWT_SECRET'));
		
	}
	
	public function login() {
		
		$this->validate($this->request, [
			'user' => 'required',
			'password' => 'required',
		]);
		
		$user = User::where('user', $this->request->input('user'))->first();
		
		if ($user && Hash::check($this->request->input('password'), $user->password)) {
			return response()->json([
				'token' => $this->create_jwt($user)
			], 200);
		} else {
			return response()->json([
				'error' => 'User or Password is wrong.'
			], 400);
		}
		
	}
	
	public function create() {
		
		$this->validate($this->request, [
			'user' => 'required|unique:users|max:255',
			'password' => 'required',
			'email' => 'required|email',
		]);
		
		$user = User::create([
			'user' => $this->request->input('user'),
			'password' => Hash::make($this->request->input('password')),
			'email' => $this->request->input('email'),
		]);
		
		return response()->json($user, 201);
		
	}
	
	public function requestPasswordChange() {
		$this->validate($this->request, [
			'user' => 'required|max:255',
		]);
		
		$user = User::where('user', '=', $this->request->user)->first();

		if ($user) {
			
			$hash = Str::random(32);
			
			$user->update([
				'hash' => $hash,
				'hash_expire' => Carbon::now()->addMinutes(5),
			]);
			
			Mail::to($user->email)->send(new ChangePasswordEmail($hash));
			
			$this->log($user->id, "Requested password change.");
			
// 			return response()->json([
// 				'message' => 'Message sent to your email.',
// 			], 200);
			return view('Message')->with('message', 'Message sent to your email.');
		} else {
// 			return response()->json([
// 				'error' => 'Not Found.'
// 			], 404);
			return view('Message')->with('message', '404: Not Found');
		}
	}
	
	public function passwordChangeForm($hash) {
		
		$user = User::where('hash', '=', $hash)->where('hash_expire', '>=', Carbon::now())->first();
		
		if (!$user) {
			$hash = false;
		}
		
		return view('PasswordChangeForm')->with('hash', $hash);
	}

	public function passwordChange() {
		
		$user = User::where('hash', '=', $this->request->hash)->where('hash_expire', '>=', Carbon::now())->first();
		
		if (!$user) {
			$message = '404: Not Found.';
		} else {
			$user->update([
				'hash' => '',
				'hash_expire' => NULL,
				'password' => Hash::make($this->request->password),
			]);
			$message = 'Password changed.';
			
			$this->log($user->id, "Changed password.");
		}
		
		return view('Message')->with('message', $message);
	}
	
	public function log($user_id, $action) {
		Log::create([
			'user_id' => $user_id,
			'action' => $action,
		]);
	}
	
	public function showLog() {
		
		$this->middleware('auth');
		
		if (!Gate::forUser($this->request->auth)->allows('access-admin')) {
			return response()->json([
				'error' => 'Unauthorized.'
			], 401);
		}
		
		return response()->json(Log::all());
	}
}























