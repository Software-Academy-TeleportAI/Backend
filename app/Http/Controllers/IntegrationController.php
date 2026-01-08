<?php

namespace App\Http\Controllers;

use App\Models\DocumentationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    public function startGeneration(Request $request)
    {
        $request->validate([
            'repo_url' => 'required|url',
            'repo_name' => 'required|string',
            'tehnical' => 'required|boolean',
        ]);

        $job = DocumentationJob::create([
            'user_id' => Auth::id(), 
            'repository_url' => $request->repo_url,
            'repository_name' => $request->repo_name,
            'status' => 'pending'
        ]);

        try {
            Http::timeout(5)->post('http://127.0.0.1:8001/api/start-generation', [
                'job_id' => $job->id,
                'repo_url' => $request->repo_url,
                'github_token' => $request->user()->github_token,
                'tehnical' => $request->tehnical,
                'callback_url' => route('api.webhook.docs_generated') 
            ]);
            
        } catch (\Exception $e) {
            $job->update(['status' => 'failed']);
            return response()->json(['message' => 'AI Service unavailable'], 503);
        }

        return response()->json([
            'message' => 'Generation started',
            'job_id' => $job->id,
            'status' => 'pending'
        ], 202); 
    }

    public function handleWebhook(Request $request)
    {
  
        $validated = $request->validate([
            'job_id' => 'required|exists:documentation_jobs,id', 
            'status' => 'required|string',
            'summary' => 'nullable|string',
            'architecture_diagram' => 'nullable|string',
            'files' => 'nullable|array',
            'readme_suggestion' => 'nullable|string',
        ]);

        $job = DocumentationJob::find($validated['job_id']);


        $job->update([
            'status' => $validated['status'], 
            'result' => [
                'summary' => $validated['summary'] ?? '',
                'architecture_diagram' => $validated['architecture_diagram'] ?? '',
                'files' => $validated['files'] ?? [],
                'readme' => $validated['readme_suggestion'] ?? ''
            ]
        ]);

        return response()->json(['message' => 'Webhook received successfully']);
    }

    public function checkStatus($id)
    {
        $job = DocumentationJob::where('id', $id)
                ->where('user_id', Auth::id()) 
                ->first();

   
        if (!$job) {
            return response()->json(['message' => 'Job not found or access denied'], 404);
        }

        return response()->json([
            'status' => $job->status,
            'result' => $job->result 
        ]);
    }
}