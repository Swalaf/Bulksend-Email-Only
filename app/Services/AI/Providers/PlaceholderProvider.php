<?php

namespace App\Services\AI\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlaceholderProvider implements AiProviderInterface
{
    public function generate(string $prompt, array $options = []): array
    {
        Log::info('AI Placeholder: Generating content', [
            'type' => $options['type'] ?? 'unknown',
            'prompt_length' => strlen($prompt),
        ]);

        $type = $options['type'] ?? 'content';

        $content = match($type) {
            'content' => $this->generateContent($prompt),
            'subject_lines' => $this->generateSubjectLines(),
            'ab_test' => $this->generateAbTest(),
            'improvements' => $this->generateImprovements(),
            'template' => $this->generateTemplate(),
            'analysis' => $this->generateAnalysis(),
            'rewrite' => $this->generateRewrite($prompt),
            default => $this->generateContent($prompt),
        };

        // Simulate API delay
        usleep(500000); // 0.5 seconds

        return [
            'content' => $content,
            'provider' => 'placeholder',
            'model' => 'demo-v1',
            'usage' => [
                'prompt_tokens' => Str::wordCount($prompt),
                'completion_tokens' => Str::wordCount($content),
            ],
        ];
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'Placeholder AI';
    }

    private function generateContent(string $prompt): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #1f2937;">Hello Subscriber!</h2>
    
    <p>Thank you for your interest in our latest updates. We're excited to share some valuable insights with you.</p>
    
    <h3 style="color: #374151;">Key Highlights</h3>
    <ul>
        <li>New features that improve your workflow</li>
        <li>Tips to maximize your productivity</li>
        <li>Exclusive offers for our subscribers</li>
    </ul>
    
    <p>We believe these updates will help you achieve better results in your work.</p>
    
    <div style="margin: 30px 0;">
        <a href="#" style="display: inline-block; background: #4F46E5; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600;">
            Learn More
        </a>
    </div>
    
    <p>If you have any questions, don't hesitate to reach out. We're here to help!</p>
    
    <hr style="margin-top: 40px; border: none; border-top: 1px solid #e5e7eb;">
    <p style="font-size: 12px; color: #9ca3af;">
        You're receiving this because you subscribed to our newsletter.<br>
        <a href="#" style="color: #6b7280;">Unsubscribe</a>
    </p>
</body>
</html>
HTML;
    }

    private function generateSubjectLines(): string
    {
        $subjects = [
            "You won't believe what we just launched",
            "Your exclusive invitation inside",
            "Quick question about your goals",
            "New features just for you",
            "Don't miss this opportunity",
        ];

        $output = "";
        foreach ($subjects as $index => $subject) {
            $output .= ($index + 1) . ". " . $subject . "\n";
        }

        return $output;
    }

    private function generateAbTest(): string
    {
        return <<<'TEXT'
A: You won't believe what we just launched
B: Your exclusive invitation inside - Open now!
TEXT;
    }

    private function generateImprovements(): string
    {
        return <<<'TEXT'
Subject Line Improvements:
- Try using personalization tokens like {{name}}
- Test urgency words sparingly
- Keep subject lines under 50 characters

Content Optimization:
- Add more white space between paragraphs
- Use bullet points for readability
- Include social proof or testimonials

Call-to-Action Improvements:
- Use action-oriented language
- Make the button stand out with contrasting color
- Create a sense of urgency

General Recommendations:
- A/B test different send times
- Segment your audience for better targeting
- Monitor engagement metrics closely
TEXT;
    }

    private function generateTemplate(): string
    {
        return $this->generateContent('');
    }

    private function generateAnalysis(): string
    {
        return <<<'TEXT'
Key Findings:
Your open rate is above industry average, indicating strong subject line performance. However, click-through rates suggest room for improvement in content engagement.

What's Working:
- Strong subject line performance
- Good list hygiene (low bounce rate)
- Consistent sending schedule

Areas for Improvement:
- Call-to-action could be more prominent
- Consider personalization beyond first name
- Test different content lengths

Recommendations:
1. A/B test button colors and placement
2. Add more targeted content segments
3. Experiment with send times
4. Consider adding video content
TEXT;
    }

    private function generateRewrite(string $prompt): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <p>Here's a professionally rewritten version of your content with improved tone and clarity.</p>
    
    <p>We've refined your message to be more engaging while maintaining your core message.</p>
    
    <hr style="margin-top: 30px; border: none; border-top: 1px solid #e5e7eb;">
    <p style="font-size: 12px; color: #9ca3af;">
        AI-assisted rewrite | Original available on request
    </p>
</body>
</html>
HTML;
    }
}
