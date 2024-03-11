<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TaskController extends Controller
{
    public function store(TaskStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            // $checkName = (new Task)->checkNameExists(trim(preg_replace('!\s+!', ' ', $request->input('subject'))));
            // if (!empty($checkName)) {
            //     return response()->json(['message' => 'Task Name Already Exist.'], 400);
            // }
            $task = (new Task)->create($request->only(['subject', 'description', 'start_date', 'due_date', 'status', 'priority']));

            foreach ($request->notes as $noteData) {
                $note = $task->notes()->create($noteData);
                if (isset($noteData['attachment'])) {
                    //print_r($noteData['attachment']);exit;
                    foreach ($noteData['attachment'] as $file) {
                        // Decode the base64 string to binary data
                        $imageData = base64_decode($file);

                        // Generate a unique filename for the image
                        $filename = uniqid() . '.jpg';
                        $directory = 'public/uploads/';
                        File::makeDirectory(storage_path('app/' . $directory), 0755, true, true);
                        $tempFilePath = storage_path('app/public/uploads/') . $filename;
                        file_put_contents($tempFilePath, $imageData);
                        $filePath = 'uploads/' . $filename;
                        Storage::disk('public')->put($filePath, file_get_contents($tempFilePath));
                        // Delete the temporary file
                        unlink($tempFilePath);
                        $note->attachments()->create(['attachment' => $filePath]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $msg
            ], 500);
        }
        return response()->json(['message' => 'Task created successfully'], 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        try {
            $tasks = (new Task)->getAll($request);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $msg
            ], 500);
        }

        return response()->json(['tasks' => $tasks], 200);
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'attachments.*' => 'required|file|max:10240', // Maximum file size: 10MB
            ]);
            DB::beginTransaction();
            if ($request->hasFile('attachments')) {
                $paths = [];
                foreach ($request->file('attachments') as $file) {
                    if (!Storage::disk('public')->exists('/uploads' . $file)) {
                        $path = $file->store('public/uploads');
                        $paths[] = $path;
                    } else {
                        $paths[] = 'Skipped: File already exists';
                    }
                }
                DB::commit();
                return response()->json(['paths' => $paths], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $msg
            ], 500);
        }

        return response()->json(['error' => 'No files were uploaded.'], 400);
    }
}
