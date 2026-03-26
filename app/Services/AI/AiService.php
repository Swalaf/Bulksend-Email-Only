<?php

namespace App\Services\AI;

use App\Services\AI\Providers\AiProviderInterface;
use App\Services\AI\Providers\PlaceholderProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiService
{
    private ?AiProviderInterface $provider = null;
    private string $providerName;

    public function __construct()
    {
        $this->providerName = config('ai.provider', 'placeholder');
        $this->initializeProvider();
    }

    private function initializeProvider(): void
    {
        $this->provider = match($this->providerName) {
            'openai' => app(\App\Services\AI\Providers\OpenAiProvider::class),
            'anthropic' => app(\App\Services\AI\Providers\AnthropicProvider::class),
            default => app(PlaceholderProvider::class),
        };
    }

    /**
     * Generate email content based on topic and parameters
     */
    public function generateContent(string $topic, array $options = []): array
    {
        $tone = $options['tone'] ?? 'professional';
        $length = $options['length'] ?? 'medium';
        $industry = $options['industry'] ?? 'general';

        $prompt = $this->buildContentPrompt($topic, $tone, $length, $industry);

        return $this->provider->generate($prompt, [
            'type' => 'content',
            'max_tokens' => $this->getMaxTokens($length),
        ]);
    }

    /**
     * Generate subject line suggestions
     */
    public function generateSubjectLines(string $topic, int $count = 5): array
    {
        $prompt = $this->buildSubjectPrompt($topic, $count);

        $result = $this->provider->generate($prompt, [
            'type' => 'subject_lines',
            'max_tokens' => 500,
        ]);

        // Parse subject lines from response
        return $this->parseSubjectLines($result['content'] ?? '');
    }

    /**
     * Generate A/B test subject lines
     */
    public function generateAbTestSubjects(string $topic): array
    {
        $prompt = $this->buildAbTestPrompt($topic);

        $result = $this->provider->generate($prompt, [
            'type' => 'ab_test',
            'max_tokens' => 400,
        ]);

        return $this->parseAbTestSubjects($result['content'] ?? '');
    }

    /**
     * Suggest improvements for an existing campaign
     */
    public function suggestImprovements(array $campaignData): array
    {
        $prompt = $this->buildImprovementPrompt($campaignData);

        $result = $this->provider->generate($prompt, [
            'type' => 'improvements',
            'max_tokens' => 800,
        ]);

        return $this->parseImprovements($result['content'] ?? '');
    }

    /**
     * Generate email template
     */
    public function generateTemplate(string $type, array $options = []): string
    {
        $prompt = $this->buildTemplatePrompt($type, $options);

        $result = $this->provider->generate($prompt, [
            'type' => 'template',
            'max_tokens' => 2000,
        ]);

        return $result['content'] ?? $this->getDefaultTemplate($type);
    }

    /**
     * Analyze campaign performance and provide insights
     */
    public function analyzePerformance(array $metrics): array
    {
        $prompt = $this->buildAnalysisPrompt($metrics);

        $result = $this->provider->generate($prompt, [
            'type' => 'analysis',
            'max_tokens' => 600,
        ]);

        return $this->parseAnalysis($result['content'] ?? '');
    }

    /**
     * Rewrite content with different tone
     */
    public function rewriteContent(string $content, string $targetTone): array
    {
        $prompt = $this->buildRewritePrompt($content, $targetTone);

        return $this->provider->generate($prompt, [
            'type' => 'rewrite',
            'max_tokens' => 1500,
        ]);
    }

    // Prompt Builders

    private function buildContentPrompt(string $topic, string $tone, string $length, string $industry): string
    {
        $lengthGuide = match($length) {
            'short' => 'Keep it concise, around 100-150 words.',
            'medium' => 'Aim for 200-300 words.',
            'long' => 'Provide detailed content, 400-600 words.',
            default => 'Aim for 200-300 words.',
        };

        return <<<PROMPT
Write a professional email for a {$industry} industry campaign about: {$topic}

Tone: {$tone}
{$lengthGuide}

The email should include:
- A compelling opening
- Main message or value proposition
- Clear call-to-action
- Professional closing

HTML format is acceptable. Don't include subject line.
PROMPT;
    }

    private function buildSubjectPrompt(string $topic, int $count): string
    {
        return <<<PROMPT
Generate {$count} compelling email subject line variations for a campaign about: {$topic}

Return ONLY a numbered list with just the subject lines, no additional text.
Make them varied: some urgent, some curious, some benefit-focused.
PROMPT;
    }

    private function buildAbTestPrompt(string $topic): string
    {
        return <<<PROMPT
Generate 2 A/B test subject line variations for: {$topic}

Format:
A: [first subject line]
B: [second subject line]

Make them distinctly different testing approaches (e.g., length, tone, personalization).
PROMPT;
    }

    private function buildImprovementPrompt(array $data): string
    {
        $metrics = json_encode($data['metrics'] ?? []);
        $content = $data['content'] ?? '';

        return <<<PROMPT
Analyze this email campaign and suggest improvements:

Campaign Metrics: {$metrics}

Email Content:
{$content}

Provide specific, actionable suggestions for:
1. Subject line improvements
2. Content optimization
3. Call-to-action improvements
4. General recommendations
PROMPT;
    }

    private function buildTemplatePrompt(string $type, array $options): string
    {
        return match($type) {
            'welcome' => 'Generate a welcome email template for new subscribers. Include placeholder for subscriber name.',
            'newsletter' => 'Generate a newsletter email template with placeholder for content sections.',
            'promotional' => 'Generate a promotional/sales email template with clear CTA.',
            'announcement' => 'Generate a product/feature announcement email template.',
            'followup' => 'Generate a follow-up email template for leads.',
            default => 'Generate a professional email template.',
        };
    }

    private function buildAnalysisPrompt(array $metrics): string
    {
        $json = json_encode($metrics, JSON_PRETTY_PRINT);

        return <<<PROMPT
Analyze this email campaign performance data and provide insights:

{$json}

Provide:
1. Key findings (2-3 sentences)
2. What's working well
3. Areas for improvement
4. Specific recommendations
PROMPT;
    }

    private function buildRewritePrompt(string $content, string $tone): string
    {
        return <<<PROMPT
Rewrite the following email content with a {$tone} tone:

{$content}

Keep the same message but adjust the language and style.
PROMPT;
    }

    // Response Parsers

    private function parseSubjectLines(string $content): array
    {
        $lines = explode("\n", trim($content));
        $subjects = [];

        foreach ($lines as $line) {
            $line = trim($line);
            // Remove numbering like "1. " or "1) "
            $line = preg_replace('/^\d+[\.\)]\s*/', '', $line);
            if (!empty($line) && strlen($line) > 5) {
                $subjects[] = $line;
            }
        }

        return array_slice($subjects, 0, 5);
    }

    private function parseAbTestSubjects(string $content): array
    {
        $result = [];
        
        if (preg_match('/A:\s*(.+)/i', $content, $matches)) {
            $result['variant_a'] = trim($matches[1]);
        }
        if (preg_match('/B:\s*(.+)/i', $content, $matches)) {
            $result['variant_b'] = trim($matches[1]);
        }

        return $result;
    }

    private function parseImprovements(string $content): array
    {
        $sections = [
            'subject' => [],
            'content' => [],
            'cta' => [],
            'general' => [],
        ];

        // Simple parsing - split by sections
        $currentSection = 'general';
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $lower = strtolower($line);
            if (str_contains($lower, 'subject')) {
                $currentSection = 'subject';
            } elseif (str_contains($lower, 'content')) {
                $currentSection = 'content';
            } elseif (str_contains($lower, 'call') || str_contains($lower, 'cta')) {
                $currentSection = 'cta';
            } elseif (str_contains($lower, 'recommend') || str_contains($lower, 'general')) {
                $currentSection = 'general';
            } else {
                $sections[$currentSection][] = $line;
            }
        }

        return [
            'subject_line' => $sections['subject'],
            'content' => $sections['content'],
            'call_to_action' => $sections['cta'],
            'general' => $sections['general'],
            'raw' => $content,
        ];
    }

    private function parseAnalysis(string $content): array
    {
        return [
            'summary' => $content,
            'raw' => $content,
        ];
    }

    private function getMaxTokens(string $length): int
    {
        return match($length) {
            'short' => 500,
            'medium' => 1000,
            'long' => 2000,
            default => 1000,
        };
    }

    private function getDefaultTemplate(string $type): string
    {
        return match($type) {
            'welcome' => <<<'HTML'
<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #333;">Welcome {{name}}!</h1>
    <p>Thank you for subscribing to our newsletter.</p>
    <p>We're excited to have you on board!</p>
    <a href="#" style="display: inline-block; background: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px;">Get Started</a>
</body>
</html>
HTML,
            default => '<p>Your email content here...</p>',
        };
    }

    /**
     * Check if AI is enabled
     */
    public function isEnabled(): bool
    {
        return config('ai.enabled', true);
    }

    /**
     * Get current provider name
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }
}
