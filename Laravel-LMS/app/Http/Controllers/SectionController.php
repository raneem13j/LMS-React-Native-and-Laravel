<?php

namespace App\Http\Controllers;
use App\Models\Section;
use App\Models\Level;
use App\Models\LevelSection;
use App\Models\UserLevelSection;
use App\Models\UserLMS;
use Illuminate\Http\Request;
use Illuminate\support\facades\Log;
use Illuminate\support\facades\DB;



class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Section::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sectionName' => 'required',
            'levelIds' => 'array', // Ensure levels IDs are provided in an array format
            'levelIds.*' => 'exists:levels,id', // Ensure each level ID exists in the levels table
            'capacity' => 'integer|nullable', // Add a capacity field that is optional and must be an integer
        ]);
    
        $section = Section::create($request->only('sectionName')); // Create the new section using only the sectionName field from the request
        $section->levels()->attach($request->input('levelIds'),['capacity' => $request->input('capacity')]);// Associate the sections with the new level using the attach method, with the capacity field set to the provided value
        return $section; // Return the newly created section with its associated levels
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Section::with('levels')->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $section = Section::find($id);
        $section->update($request->all());
        return $section;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Section::destroy($id);
    }

    //git a list of student in certain level and section
    public function showListStudent($levelName, $sectionName)
    {
        $students = [];
        $level = Level::where('levelName', $levelName)->firstOrFail();
        
        $section = Section::where('sectionName', $sectionName)->firstOrFail();
        $levelSection = LevelSection::where('level_id', $level->id)->where('section_id', $section->id)->first();
        $user_level_section = UserLevelSection::where('levelSection_id',$levelSection->id)->get();
        foreach($user_level_section as $each){
            $student = UserLMS::where('id',$each->student_id)->first();
            array_push($students,$student);
        }

        
        return response()->json($students);

    }
//     public function showListStudent($levelName, $sectionName)
// {
//     $students = DB::table('user_l_m_s')
//                     ->join('user_level_sections', 'user_l_m_s.id', '=', 'user_level_sections.student_id')
//                     ->join('level_sections', 'user_level_sections.levelSection_id', '=', 'level_sections.id')
//                     ->join('levels', 'level_sections.level_id', '=', 'levels.id')
//                     ->join('sections', 'level_sections.section_id', '=', 'sections.id')
//                     ->where('levels.levelName', '=', $levelName)
//                     ->where('sections.sectionName', '=', $sectionName)
//                     ->select('user_l_m_s.*')
//                     ->get();

//     return response()->json($students);
// }

    public function showListTeacher($levelName, $sectionName)
    {
        $teachers = [];
        $level = Level::where('levelName', $levelName)->firstOrFail();
        
        $section = Section::where('sectionName', $sectionName)->firstOrFail();
        $levelSection = LevelSection::where('level_id', $level->id)->where('section_id', $section->id)->first();
        $user_level_section = UserLevelSection::where('levelSection_id',$levelSection->id)->get();
        foreach($user_level_section as $each){
            $teacher = UserLMS::where('id',$each->teacher_id)->first();
            array_push($teachers,$teacher);
        }

        
        return response()->json($teachers);

    }
    
    

}
