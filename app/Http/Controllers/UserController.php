<?php

namespace App\Http\Controllers;
use App\User as User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const ERROR_STATUS_CODE = 422;

    public function showBalance()
    {
        $response = User::select('balance')->where('id', (int)request()->get('user', 0))->first();
        if (is_null($response)) {
            $errors['errors'][] = 'User not found';
            $response = response()->json($errors, self::ERROR_STATUS_CODE);
        }
        return $response;
    }

    public function deposit()
    {
        /** @var User $user */
        $user = User::find((int) request()->get('user'));

        $validator = \Validator::make(['balance'=>request()->get('amount')], $user->rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->getMessages()],self::ERROR_STATUS_CODE);
        } else {
            if (is_null($user)) {
                $faker = \Faker\Factory::create();
                $password = Hash::make('test');
                User::create([
                    'id' => (int)request()->get('user'),
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'balance' => (float)request()->get('amount'),
                ]);
            } else {
                $user->update(['balance' => (float)request()->get('amount')]);
            }
            return response('');
        }
    }

    public function withdraw()
    {
        /** @var User $user */
        $user = User::find((int)request()->get('user'));
        $amount = (float)request()->get('amount');

        $validator = \Validator::make(['balance'=>$amount], $user->rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->getMessages()],self::ERROR_STATUS_CODE);
        } else {
            if ($user->balance >= $amount) {
                $user->update(['balance' => $user->balance - $amount]);
            } else {
                $message = \Lang::get('message.insufficient');
                return response()->json(['errors'=>[$message]],self::ERROR_STATUS_CODE);
            }
        }

        return response('');
    }

    public function transfer()
    {
        $from = User::find((int)request()->get('from'));
        $to = User::find((int)request()->get('to'));
        $amount = (float)request()->get('amount');

        $validator = \Validator::make(['balance'=>$amount], $from->rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->getMessages()],self::ERROR_STATUS_CODE);
        } else {
            if ($from->balance >= $amount) {
                $from->update(['balance' => $from->balance - $amount]);
                $to->update(['balance' => $to->balance + $amount]);
            } else {
                $message = \Lang::get('message.insufficient');
                return response()->json(['errors'=>[$message]],self::ERROR_STATUS_CODE);
            }
        }

        return response('');
    }
}