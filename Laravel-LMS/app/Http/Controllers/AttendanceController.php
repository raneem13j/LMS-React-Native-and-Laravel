<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceReportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\UserLMS;
use App\Models\Attendance;
use App\Models\User;
use App\Models\UserLevelSection;
use Carbon\Carbon;



class AttendanceController extends Controller
{
    
    public function getAttendanceSection(Request $request,$id){
        $sectionSituation=DB::table('attendances')
        ->join('user_l_m_s','attendances.studentId','=','user_l_m_s.id')
        ->select('user_l_m_s.firstName')
        ->where('attendances.status', '=', 'Absent')
        ->where('attendances.levelSectionId', '=' , $id)
        ->get();
        return response()->json([
            $sectionSituation
        ]);
    }


    public function getAttendanceName(Request $request,$id){
        $studentSituation=DB::table('attendances')
        ->select('attendances.status','attendances.date')
        ->where('attendances.studentId','=',$id)
        ->get();
        return response()->json([
            $studentSituation
        ]);
        


    }


    public function getAttendanceByDate(Request $request,$id){
        $attendanceDate=DB::table('attendances')
        ->join('user_l_m_s','attendances.studentId','=','user_l_m_s.id')
        ->select('user_l_m_s.firstName','attendances.status')
        ->where('attendances.levelSectionId', '=' , $id)
        ->get();
        return response()->json([
            $attendanceDate
        ]);
    }




    public function createOrUpdateAttendance(Request $request)
    {
        $studentLevelSection = UserLevelSection::where('student_id',$request->studentId)->first();
        $level_Section_id = $studentLevelSection->levelSection_id;
        //return response()->json(['s' => $studentLevelSection->levelSection_id]);

        Attendance::updateOrCreate([
            'studentId' => $request->studentId
        ], 
        [
            'status' => $request->status,
            'levelSection_id' => $level_Section_id,
            'date' => date('Y-m-d')
        ]);

        return response()->json([
            'message'=>'attendance created successfully!'
        ]);


    }

    


    public function getAttendance(){
        $data = UserLMS::with('Attendance','Attendance.LevelSection', 'Attendance.LevelSection.Level', 'Attendance.LevelSection.Section')->get();
        return response()->json(AttendanceReportResource::collection($data));
    }

}

