<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProgramController extends Controller
{
    public function index()
    {
        return view('programs.index');
    }

    public function data(Request $request)
    {
        $query = Program::query();

        if ($request->has('sumber_dana') && $request->sumber_dana != '') {
            $query->where('sumber_dana', $request->sumber_dana);
        }

        if ($request->has('keterangan') && $request->keterangan != '') {
            $query->where('keterangan', $request->keterangan);
        }

        return DataTables::of($query)
            ->addColumn('actions', function ($program) {
                return '<button class="btn btn-sm btn-primary edit" data-id="' . $program->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="' . $program->id . '">Delete</button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Program $program)
    {
        return response()->json($program);
    }

    public function store(Request $request)
    {
        $program = Program::create($request->all());
        return response()->json($program);
    }

    public function update(Request $request, Program $program)
    {
        $program->update($request->all());
        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->json(['success' => 'Program deleted successfully']);
    }
}
