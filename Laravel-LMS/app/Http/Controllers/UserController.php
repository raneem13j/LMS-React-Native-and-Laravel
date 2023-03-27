<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserLMS;

use App\Models\UserLevelSection;
use App\Models\Level;
use App\Models\Section;
use App\Models\Course;
use App\Models\LevelSection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

       
    //Add user
public function addUser(Request $request){
        $user= new UserLMS;

        $request->validate([
        'firstName'=>'required',
        'lastName'=>'required',
        'email'=>'required|unique:users,email',
        'password'=>'required',
        'role'=>'required',
        'phoneNumber'=>'required',
      ]);
        $firstName=$request->input('firstName');
        $lastName=$request->input('lastName');
        $email=$request->input('email');
        // $password=$request->input('password');
         $password= Hash::make($request->password);
        
        $role=$request->input('role');
        $phoneNumber=$request->input('phoneNumber');
        
        $user->firstName=$firstName;
        $user->lastName=$lastName;
        $user->email=$email;
        $user->password=$password;
        $user->role=$role;
        $user->phoneNumber=$phoneNumber;
        

        $user->save();

    $levelName = $request->input('levelName');
    $sectionName = $request->input('sectionName');

    $level = Level::where('levelName', $levelName)->first();
    $section = Section::where('sectionName', $sectionName)->first();
   //Create the user level section record
    $userLevelSection = new UserLevelSection;
    if ($user->role == 'student') {
        $userLevelSection->student_id = $user->id;

        $userLevelSection->levelSection_id = LevelSection::where('level_id',$level->id)->where('section_id',$section->id)->first()->id;

        $userLevelSection->levelSection_id ;
        $userLevelSection->save();
       } 
       
       else if ($user->role == 'teacher') {
        $userLevelSection->teacher_id = $user->id;
        $subject = $request->input('subject');
        $course=Course::where('subject',$subject)->first();
        $userLevelSection->levelSection_id = LevelSection::where('level_id',$level->id)->where('section_id',$section->id)->first()->id;
        $userLevelSection->levelSection_id;
        $userLevelSection->course_id = $course->id;
        $userLevelSection->course_id ;
        $userLevelSection->save();
        
      
       }
    
   $user->save();
    $token=$user->createToken('token')->plainTextToken;
           return response()->json([
            'message'=>'User created successfully!',
            'token'=>$token,
            'levelSection'=>$userLevelSection,
            
        ]);
        
        }


    //get all users
    public function getUser(Request $request){
        $user=DB::table('user_l_m_s')->get();
        
        return response()->json([
            'message'=>$user,
           
        ]);
        if($user==null){
            return response()->json([
                'message'=>'No user exist',
               
            ]);
        }

        }




    public function getUserbyID(Request $request,$id){
        $user=UserLMS::find($id);
        if(! $user){
            return response()->json([
                'message'=>'No user exist',
               
            ]);
        }


        return response()->json([
            'message'=>$user
        ]);


      
        }

    //update user information

    public function updateUser(Request $request, $id){
        // log::info($request);
    $user = UserLMS::find($id);
    $user->firstName = $request->firstName ? $request->firstName : $user->firstName ;
    $user->lastName = $request->lastName ? $request->lastName : $user->lastName;
    $user->password = $request->password ? Hash::make($request->password) : $user->password;
    $user->role = $request->role ? $request->role : $user->role;
    $user->email = $request->email ? $request->email : $user->email;
    $user->phoneNumber = $request->phoneNumber ? $request->phoneNumber : $user->phoneNumber;
    $user->save();

    

    return response()->json([
         'message' => 'User Updated!!',
         'user' => $user,
    ]);
        }


    //delete user
    public function deleteUser(Request $request,$id){
        $user=UserLMS::find($id);
        if(! $user){
            return response()->json([
                'message'=>'No user exist',
               
            ]);
        }

        $user->delete();
        
        return response()->json([
            'message'=>'User Deleted',
        
        ]);

        }



   
    //serarch for user by name

    public function getbyName($firstName){

        $hi= UserLMS::where('firstName','like','%'.$firstName.'%') ;
        if(!$hi){
            return response()->json([
                'message'=>'No user exist',
               
            ]);
        }
            return $hi->get();
    
        }

    //get all the teachers
    public function getTeacher() {
        $role="teacher";
        $users = DB::table('user_l_m_s')->where('role', $role)->get();
        return response()->json([ 'users'=> $users]);
        }


   //get all the students
   public function getStudent() {
    $role="student";
    $users = DB::table('user_l_m_s')->where('role', $role)->get();
    return response()->json([ 'users'=> $users]);
        }



public function logout(Request $request){
    auth()->user()->tokens()->delete();

    return response()->json([ 'message'=> 'Logged out Successfully!!']);
        }



public function login(Request $request){
    $fields=$request->validate(
        [
            'email'=>'required|unique:users,email',
            'password'=>'required'
        ]
        );


       

        //check email
        $user=UserLMS::where('email',$fields['email'])->first();


         // set the role of the user
    

        //check password
    
        if(! $user|| ! Hash::check($fields['password'],$user->password)){
            return response()->json([
                'message'=>'Bad creds'
            ],401);
        }
        $token=$user->createToken('superadmintoken')->plainTextToken;
            
        
        
        return response()->json([
            'message'=>'Loggedin Successfully',
            'token'=>$token,
            'role' => $user->role
        ]);
        }

    
    
    }