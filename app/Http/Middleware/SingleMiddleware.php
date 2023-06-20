<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Sms;

class SingleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        try {
            // $id= $request->id;
            

            // return response()->json(['message' => $id]);

            // if(!$id){
            // return response()->json(['error' => 'id needed'],422);

            // }

            $token = $request->bearerToken();
            if(!$token ){
                return response()->json(['message' => 'Authorization failed'], 422);

            }
            $tokenParts = explode(".", $token);  
            $tokenHeader = base64_decode($tokenParts[0]);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtHeader = json_decode($tokenHeader);
            $jwtPayload = json_decode($tokenPayload);
        
                $user= Sms::find($jwtPayload->sub);

                if (!$user) {
                    
                    return response()->json(['message' => 'user not found'], 422);
                }
                $request->route()->setParameter('id',  $user->id);
                return $next($request);
            

         }
            catch (Exception $e) {
            
                return response()->json(['message' => 'invalid data'], 422);
            }
    }
}
