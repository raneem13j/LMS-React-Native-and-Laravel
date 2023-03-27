<?php

namespace App\Http\Controllers;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($sectionId = null)
    {
        if ($sectionId) {
            $levels = Section::findOrFail($sectionId)->levels()->with('sections')->get();
        } else {
            $levels = Level::with('sections')->get();
        }
        return $levels;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'levelName' => 'required',
            'sectionIds' => 'array', // Ensure section IDs are provided in an array format
            'sectionIds.*' => 'exists:sections,id', // Ensure each section ID exists in the sections table
            'capacity' => 'integer|nullable', // Add a capacity field that is optional and must be an integer
        ]);
    
        $level = Level::create($request->only('levelName')); // Create the new level using only the levelName field from the request
    
        $level->sections()->attach($request->input('sectionIds'), ['capacity' => $request->input('capacity')]); // Associate the sections with the new level using the attach method, with the capacity field set to the provided value
    
        return $level; // Return the newly created level with its associated sections
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Level::with('sections')->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $level = Level::find($id);
        $level->update($request->all());
        return $level;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Level::destroy($id);
    }
}
