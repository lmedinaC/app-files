<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFilesRequest;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $request;
            $data['user_id'] = $request->user->id;
            $data = $this->fileService->search($request);
            return $data;
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        try {
            $file = $request->file('document');
            $data['user_id'] = $request->user->id;
            $data = $this->fileService->createByUser($file, $request->user->id);
            return $data;
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function softDestroy(Request $request, $id)
    {
        try {
            $this->fileService->softDestroyByUser($id, $request->user->id);
            return response()->json([
                'message' => "File sof deleted succesfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function hardDestroy(Request $request, $id)
    {
        try {
            $this->fileService->hardDestroyByUser($id, $request->user->id);
            return response()->json([
                'message' => "File hard deleted succesfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleStore(StoreFilesRequest $request)
    {
        try {
            $files = $request->documents;
            $data['user_id'] = $request->user->id;
            $data = $this->fileService->createMultiplesByUser($files, $request->user->id);
            return response()->json([
                'message' => "Files created succesfully"
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    
}
