<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the AI service.
    | You can switch between different AI providers here.
    |
    */

    // Enable/disable AI features globally
    'enabled' => env('AI_ENABLED', true),

    // AI Provider: placeholder, openai, anthropic
    'provider' => env('AI_PROVIDER', 'placeholder'),

    // Provider-specific configurations
    'providers' => [
        'placeholder' => [
            'name' => 'Placeholder AI',
            'description' => 'Demo AI service with sample responses',
        ],
        
        'openai' => [
            'name' => 'OpenAI',
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => 2000,
            'temperature' => 0.7,
        ],
        
        'anthropic' => [
            'name' => 'Anthropic Claude',
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-opus-20240229'),
            'max_tokens' => 2000,
        ],
    ],

    // Default settings for content generation
    'defaults' => [
        'tone' => 'professional',
        'length' => 'medium',
        'industry' => 'general',
    ],

    // Prompt templates
    'prompts' => [
        'content' => [
            'system' => 'You are an expert email marketing copywriter.',
        ],
        'subject' => [
            'system' => 'You are an expert in crafting compelling email subject lines.',
        ],
    ],
];
