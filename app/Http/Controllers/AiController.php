<?php

namespace App\Http\Controllers;

use App\Services\AI\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiController extends Controller
{
    public function __construct(
        private AiService $aiService
    ) {}

    public function status()
    {
        return response()->json([
            'enabled' => $this->aiService->isEnabled(),
            'provider' => $this->aiService->getProviderName(),
        ]);
    }

    public function generateContent(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:3',
            'tone' => 'nullable|in:professional,friendly,casual,urgent,informative',
            'length' => 'nullable|in:short,medium,long',
            'industry' => 'nullable|string',
        ]);

        $result = $this->aiService->generateContent(
            $validated['topic'],
            [
                'tone' => $validated['tone'] ?? 'professional',
                'length' => $validated['length'] ?? 'medium',
                'industry' => $validated['industry'] ?? 'general',
            ]
        );

        return response()->json($result);
    }

    public function generateSubjectLines(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:3',
            'count' => 'nullable|integer|min:1|max:10',
        ]);

        $count = $validated['count'] ?? 5;
        $subjects = $this->aiService->generateSubjectLines($validated['topic'], $count);

        return response()->json([
            'subjects' => $subjects,
        ]);
    }

    public function generateAbTest(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:3',
        ]);

        $variants = $this->aiService->generateAbTestSubjects($validated['topic']);

        return response()->json($variants);
    }

    public function suggestImprovements(Request $request)
    {
        $validated = $request->validate([
            'metrics' => 'required|array',
            'content' => 'nullable|string',
        ]);

        $suggestions = $this->aiService->suggestImprovements($validated);

        return response()->json($suggestions);
    }

    public function generateTemplate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:welcome,newsletter,promotional,announcement,followup',
        ]);

        $template = $this->aiService->generateTemplate($validated['type']);

        return response()->json([
            'template' => $template,
        ]);
    }

    public function analyzePerformance(Request $request)
    {
        $validated = $request->validate([
            'metrics' => 'required|array',
        ]);

        $analysis = $this->aiService->analyzePerformance($validated['metrics']);

        return response()->json($analysis);
    }

    public function rewrite(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:10',
            'tone' => 'required|in:professional,friendly,casual,urgent,humorous',
        ]);

        $result = $this->aiService->rewriteContent(
            $validated['content'],
            $validated['tone']
        );

        return response()->json($result);
    }
}
