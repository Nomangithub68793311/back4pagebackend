<?php

namespace App\Http\Controllers;

use App\Models\Account;

use App\Models\Multiple;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allData($id)
    {       
        
	    $post=Account::find($id)->posts()->whereIn('tag', ['free', 'ad'])->get();

        return  response()->json(["success"=> $post]);


    }

    public function multipleData($id)
    {       
        
        try{
            $post=Account::find($id)->multiple;
            return  response()->json(["success"=> $post]);
        }
                

        catch (\Exception $e) {
            return response()->json(["error"=>"didnt work"],422);


            }
    }
    public function preview($id,$idPost)
    {       
        try {
            DB::beginTransaction();
        

            $post = Post::find($idPost); // eloquent creation of data
    
            DB::commit();   
            return  response()->json(["post"=> $post]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }


    }
    public function multiplePreview($id,$idPost)
    {       
        try {
            DB::beginTransaction();
        

            $post = Multiple::find($idPost); // eloquent creation of data
    
            DB::commit();   
            return  response()->json(["post"=> $post]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFree(Request $request,$id)
    {


        $input = $request->only(
            'country','state','city','service','tag',
            'category','title','description','email',
            'phone','age','images'
            
      );   
  
        // return  response()->json(["success"=> $input]);

    

      $validator = Validator::make($input, [
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'service' => 'required',
        'category' => 'required',
        'title' => 'required',
        'description' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'age' => 'required',
        'images' => 'required',
        'tag' => 'required',


        
    ]);

    if($validator->fails()){
        return response()->json(["error"=>'fails']);

    }
    
    // $input['images']  = $request->file('file')->store('products');
    // $post = Post::create($input);
    //     return  response()->json(["success"=> $post]);

        try {
            DB::beginTransaction();
        

            $account=Account::find($id);
            
            $post = Post::create($input); 
            $account->posts()->save($post);
            $post->save();
            
            if (!$post) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            // ->delay(Carbon::now()->addSeconds(5));
            // dispatch( $job);
            return  response()->json(["success"=>true,"id"=>$post->id]);
        }
            catch (\Exception $e) {
            DB::rollback();  

            }
    

    }
    public function storeAd(Request $request ,$id)
    {

        // return  response()->json(["success"=> $request->file('file')]);

        $input = $request->only(
            'country','state','city','service','tag',
            'category','title','description','email',
            'phone','age','images'
            
      );   
  
        // return  response()->json(["success"=> $request->file('file')]);

    

      $validator = Validator::make($input, [
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'service' => 'required',
        'category' => 'required',
        'title' => 'required',
        'description' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'age' => 'required',
        'images' => 'required',
        'tag' => 'required',

        
    ]);

    if($validator->fails()){
        return response()->json(["error"=>'fails']);

    }
    
    // $input['images']  = $request->file('file')->store('products');


        try {
            DB::beginTransaction();
            $account=Account::find($id);
            
            $post = Post::create($input); // eloquent creation of data
            $account->posts()->save($post);
            $post->save();
            
            if (!$post) {
                return response()->json(["error"=>"didnt work"],422);
            } 
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            // if($request->totalBill)
            // {
            // $account->credit=$account->credit-$request->totalBill ;
            // $account->save();
            //  }
            DB::commit();   
            // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            // ->delay(Carbon::now()->addSeconds(5));
            // dispatch( $job);
            return  response()->json(["success"=>true,"id"=>$post->id]);
        }
            catch (\Exception $e) {
            DB::rollback();  

            }
    

    }
    public function storeMultiple(Request $request ,$id)
    {

        // return  response()->json(["success"=> $request->file('file')]);

        $input = $request->only(
            'country','state','city','service','tag',
            'category','title','description','email',
            'phone','age','images'
            
      );   
  
        // return  response()->json(["success"=> $request->file('file')]);

    

      $validator = Validator::make($input, [
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'service' => 'required',
        'category' => 'required',
        'title' => 'required',
        'description' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'age' => 'required',
        'images' => 'required',
        'tag' => 'required',

        
    ]);

    if($validator->fails()){
        return response()->json(["error"=>'fails']);

    }
    


        try {
            DB::beginTransaction();
            $account=Account::find($id);
            $multiple = Multiple::create($input); 
            $account->multiple()->save($multiple);
            $multiple->save();
            foreach ($request->city as $city) {
                $input['city']=$city;
                $account=Account::find($id);
            
                $post = Post::create($input); 
                $account->posts()->save($post);
                $multiple->posts()->save($post);

                $post->save();
                if (!$post) {
                    return response()->json(["error"=>"didnt work"],422);
                } 
              
            }
           
            
            
            
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            // if($request->totalBill)
            // {
            // $account->credit=$account->credit-$request->totalBill ;
            // $account->save();
            //  }
            DB::commit();   
            // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            // ->delay(Carbon::now()->addSeconds(5));
            // dispatch( $job);
        //     $match=[

        //         'preview' => true,
        //         'images' => $request->images,
        //         'preview' => true,
        //         'tag' => $request->tag
                


        //     ];
        // $posts= Account::find($id)->posts()->where($match)->get();
            return  response()->json(["success"=>true,"post"=>$multiple]);
        }
            catch (\Exception $e) {
            DB::rollback();  

            }
    

    }
    /**
     * Display the s pecified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
 public function multipleEdit(Request $request ,$id,$idPost)
     {
 
         // return  response()->json(["success"=> $request->file('file')]);
 
         $input = $request->only(
             'country','state','city','service','tag',
             'category','title','description','email',
             'phone','age','images'
             
       );   
   
         // return  response()->json(["success"=> $request->file('file')]);
 
     
 
       $validator = Validator::make($input, [
         'country' => 'required',
         'state' => 'required',
         'city' => 'required',
         'service' => 'required',
         'category' => 'required',
         'title' => 'required',
         'description' => 'required',
         'email' => 'required',
         'phone' => 'required',
         'age' => 'required',
         'images' => 'required',
         'tag' => 'required',
 
         
     ]);
 
     if($validator->fails()){
         return response()->json(["error"=>'fails']);
 
     }
    
    
 
         try {
              DB::beginTransaction();
             $multiple=Multiple::where('id',$idPost)->update($input);
	      $multiplePosts=Multiple::find($idPost)->posts;
	      foreach($multiplePosts  as $indx => $value) {
                $input['city']=$request->city[$indx];
                $multiple=Post::where('id',$multiplePosts[$indx]->id)->update($input);
            }

             
             DB::commit();   
             return  response()->json(["success"=>"updated successfully"]);
         }
             catch (\Exception $e) {
             DB::rollback();  
             return response()->json(["error"=>"process error"],422);

             }
     
 
     }

    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id,$idPost)
    {
        $input = $request->only(
            'country','state','city','service','tag',
            'category','title','description','email',
            'phone','age','images'
            
      );   

      $validator = Validator::make($input, [
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'service' => 'required',
        'category' => 'required',
        'title' => 'required',
        'description' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'age' => 'required',
        'images' => 'required',
        'tag' => 'required',

        
    ]);

    if($validator->fails()){
        return response()->json(["error"=>'fails']);

    }
    

        try {
            DB::beginTransaction();
        

	    $post=Post::where('id',$idPost)->update($input);
	     $posts=Account::find($id)->posts()->whereIn('tag', ['free', 'ad'])->get();
         if (!$post) {
            return response()->json(["error"=>"didnt work"],422);
        } 
    
            DB::commit();   
                       return  response()->json(["success"=> "Updated Successfully","post"=>$posts]);

        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       

        try {
            DB::beginTransaction();
        

            $post = Post::find($request->postId); // eloquent creation of data

            if($post){
                $post->preview=false;
		$post->save();
		DB::commit();
                return  response()->json(["success"=>"Ad posted Successfilly"]);

            }
            $post = Multiple::find($request->postId);
            $post->preview=false;
            $post->save();
            $ads= Multiple::find($request->postId)->posts()->get();
            foreach ($ads as $ad) {
                $post=Post::find($ad->id);
                $post->preview=false;
                $post->save();
             
            }


            
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            // ->delay(Carbon::now()->addSeconds(5));
            // dispatch( $job);
            return  response()->json(["success"=>"Ad posted Successfilly"]);
        }
            catch (\Exception $e) {
            DB::rollback();  

            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        try {
            DB::beginTransaction();
        

            Post::where('id', $request->idPost)->delete(); // eloquent creation of data
    $post=Account::find($id)->posts()->whereIn('tag', ['free', 'ad'])->get();
            DB::commit();   
            return  response()->json(["success"=> "deleted successfully","post"=>$post]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }


    }

    public function multipleDestroy(Request $request,$id)
    {
        try {
            DB::beginTransaction();


            Multiple::where('id', $request->idPost)->delete(); // eloquent creation of data
            $post=Account::find($id)->multiple;
            DB::commit();
            return  response()->json(["success"=> "deleted successfully","post"=>$post]);
        }
            catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>"didnt work"],422);


            }


    }
}

