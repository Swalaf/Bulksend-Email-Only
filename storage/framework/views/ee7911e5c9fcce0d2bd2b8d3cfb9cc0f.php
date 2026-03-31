<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title><?php echo e($metaTitle ?? 'BulkSend - Professional Email Marketing Platform'); ?></title>
    <meta name="title" content="<?php echo e($metaTitle ?? 'BulkSend - Professional Email Marketing Platform'); ?>">
    <meta name="description" content="<?php echo e($metaDescription ?? 'Send bulk emails with ease. Powerful email marketing platform with advanced analytics, automation, and SMTP management. Start your free trial today.'); ?>">
    <meta name="keywords" content="email marketing, bulk email, email campaign, marketing automation, SMTP service, email sender">
    <meta name="author" content="BulkSend">
    <meta name="robots" content="index, follow">
    
    <!-- OpenGraph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <meta property="og:title" content="<?php echo e($ogTitle ?? 'BulkSend - Professional Email Marketing Platform'); ?>">
    <meta property="og:description" content="<?php echo e($ogDescription ?? 'Send bulk emails with ease. Powerful email marketing platform with advanced analytics, automation, and SMTP management.'); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/og-image.png')); ?>">
    <meta property="og:site_name" content="BulkSend">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(url('/')); ?>">
    <meta property="twitter:title" content="<?php echo e($ogTitle ?? 'BulkSend - Professional Email Marketing Platform'); ?>">
    <meta property="twitter:description" content="<?php echo e($ogDescription ?? 'Send bulk emails with ease. Powerful email marketing platform with advanced analytics, automation, and SMTP management.'); ?>">
    <meta property="twitter:image" content="<?php echo e(asset('images/og-image.png')); ?>">
    
    <!-- Canonical -->
    <link rel="canonical" href="<?php echo e(url('/')); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        accent: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Structured Data - Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "BulkSend",
        "url": "<?php echo e(url('/')); ?>",
        "logo": "<?php echo e(asset('images/logo.svg')); ?>",
        "description": "Professional email marketing platform with advanced analytics and automation",
        "sameAs": [
            "https://twitter.com/bulksend",
            "https://linkedin.com/company/bulksend"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+1-555-123-4567",
            "contactType": "customer service"
        }
    }
    </script>
    
    <!-- Structured Data - FAQ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "How does BulkSend work?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "BulkSend is a cloud-based email marketing platform that allows you to create, send, and track email campaigns. Simply import your contacts, design your email using our drag-and-drop editor, select your SMTP configuration, and send to your entire list. Our platform handles deliverability, tracking, and analytics."
                }
            },
            {
                "@type": "Question",
                "name": "Is BulkSend GDPR compliant?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, BulkSend is fully GDPR compliant. We provide features like double opt-in, unsubscribe links in every email, data export, and account deletion to ensure you can manage your email marketing in compliance with GDPR regulations."
                }
            },
            {
                "@type": "Question",
                "name": "Can I use my own SMTP server?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Absolutely! BulkSend supports custom SMTP configurations. You can connect your own SMTP server or purchase SMTP credits from our verified marketplace. This gives you full control over your email deliverability."
                }
            },
            {
                "@type": "Question",
                "name": "What analytics do you provide?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "We provide comprehensive analytics including open rates, click rates, bounce rates, unsubscribes, spam complaints, and geographic data. You can also track individual link clicks and measure campaign performance over time."
                }
            },
            {
                "@type": "Question",
                "name": "Is there a free trial?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, we offer a 14-day free trial with full access to all features. No credit card required to start. You can send up to 500 emails during the trial period."
                }
            }
        ]
    }
    </script>
    
    <!-- Structured Data - Product/Service -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "BulkSend Email Marketing",
        "description": "Professional email marketing platform with advanced analytics, automation, and SMTP management",
        "brand": {
            "@type": "Brand",
            "name": "BulkSend"
        },
        "offers": {
            "@type": "Offer",
            "price": "9.99",
            "priceCurrency": "USD",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #bae6fd 100%);
        }
        
        .dark .hero-gradient {
            background: linear-gradient(135deg, #082f49 0%, #0c4a6e 50%, #075985 100%);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .pricing-card:hover {
            transform: scale(1.02);
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .blob {
            position: absolute;
            filter: blur(80px);
            opacity: 0.5;
            z-index: -1;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-white bg-white dark:bg-gray-900">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-100 dark:border-gray-800" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold gradient-text">BulkSend</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition font-medium">Features</a>
                    <a href="#how-it-works" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition font-medium">How It Works</a>
                    <a href="#pricing" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition font-medium">Pricing</a>
                    <a href="#testimonials" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition font-medium">Testimonials</a>
                    <a href="#faq" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition font-medium">FAQ</a>
                </div>
                
                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition">Log In</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/25">Get Started Free</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 dark:text-gray-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800" @click.away="mobileMenuOpen = false">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block py-2 text-gray-600 dark:text-gray-300 font-medium">Features</a>
                <a href="#how-it-works" class="block py-2 text-gray-600 dark:text-gray-300 font-medium">How It Works</a>
                <a href="#pricing" class="block py-2 text-gray-600 dark:text-gray-300 font-medium">Pricing</a>
                <a href="#testimonials" class="block py-2 text-gray-600 dark:text-gray-300 font-medium">Testimonials</a>
                <a href="#faq" class="block py-2 text-gray-600 dark:text-gray-300 font-medium">FAQ</a>
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 space-y-3">
                    <a href="<?php echo e(route('login')); ?>" class="block py-2 text-center text-gray-600 dark:text-gray-300 font-medium">Log In</a>
                    <a href="<?php echo e(route('register')); ?>" class="block py-3 text-center bg-primary-600 text-white font-semibold rounded-lg">Get Started Free</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden hero-gradient">
        <!-- Background Blobs -->
        <div class="blob w-96 h-96 bg-primary-300 dark:bg-primary-700 rounded-full top-20 left-0 -translate-x-1/2"></div>
        <div class="blob w-80 h-80 bg-accent-300 dark:bg-accent-700 rounded-full top-40 right-0 translate-x-1/3"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-primary-100 dark:bg-primary-900/30 rounded-full mb-8 animate-float">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium text-primary-700 dark:text-primary-300">Trusted by 10,000+ businesses worldwide</span>
                </div>
                
                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    Send <span class="gradient-text">Bulk Emails</span> That Actually Get Opened
                </h1>
                
                <!-- Subheadline -->
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Powerful email marketing platform with advanced analytics, automation, and SMTP management. 
                    Turn subscribers into customers with professional campaigns.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                    <a href="<?php echo e(route('register')); ?>" class="w-full sm:w-auto px-8 py-4 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition shadow-xl shadow-primary-500/25 text-lg">
                        Start Free Trial
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 transition text-lg">
                        See How It Works
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>14-day free trial</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>No credit card required</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Cancel anytime</span>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="mt-16 relative">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-2 lg:p-4">
                    <img src="https://placehold.co/1200x700/f0f9ff/0ea5e9?text=Dashboard+Preview" alt="BulkSend Dashboard" class="rounded-xl w-full">
                </div>
                <!-- Floating Elements -->
                <div class="absolute -bottom-6 -left-6 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-4 border border-gray-200 dark:border-gray-700 animate-bounce-slow">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">99% Deliverability</p>
                            <p class="text-xs text-gray-500">Industry leading</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-6 -right-6 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-4 border border-gray-200 dark:border-gray-700 animate-bounce-slow" style="animation-delay: 1s;">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-primary-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Real-time Analytics</p>
                            <p class="text-xs text-gray-500">Track every metric</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-32 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase tracking-wider">Features</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-4">Everything You Need to Succeed</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Powerful tools designed to help you create, send, and optimize your email campaigns
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-pen-nib text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Drag & Drop Editor</h3>
                    <p class="text-gray-600 dark:text-gray-300">Create stunning emails with our intuitive drag-and-drop editor. No coding required.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Smart Segmentation</h3>
                    <p class="text-gray-600 dark:text-gray-300">Segment your audience based on behavior, demographics, and engagement.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Advanced Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-300">Track opens, clicks, bounces, and conversions with detailed real-time reports.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-robot text-2xl text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Marketing Automation</h3>
                    <p class="text-gray-600 dark:text-gray-300">Set up automated workflows to nurture leads and engage customers.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-accent-100 dark:bg-accent-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-server text-2xl text-accent-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Custom SMTP</h3>
                    <p class="text-gray-600 dark:text-gray-300">Use your own SMTP server or purchase from our verified marketplace.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Enterprise Security</h3>
                    <p class="text-gray-600 dark:text-gray-300">Bank-level security with GDPR compliance and 99.9% uptime guarantee.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase tracking-wider">How It Works</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-4">Get Started in 3 Simple Steps</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Start sending professional email campaigns in minutes
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-primary-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg shadow-primary-500/30">
                        1
                    </div>
                    <h3 class="text-xl font-bold mb-3">Import Your Contacts</h3>
                    <p class="text-gray-600 dark:text-gray-300">Upload your subscriber list or connect your CRM. We'll help you organize and segment your audience.</p>
                    <div class="hidden md:block absolute top-10 -right-6 lg:-right-12 text-4xl text-gray-300">→</div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-primary-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg shadow-primary-500/30">
                        2
                    </div>
                    <h3 class="text-xl font-bold mb-3">Create Your Campaign</h3>
                    <p class="text-gray-600 dark:text-gray-300">Use our drag-and-drop editor to design beautiful emails. Choose from templates or start from scratch.</p>
                    <div class="hidden md:block absolute top-10 -right-6 lg:-right-12 text-4xl text-gray-300">→</div>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-primary-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg shadow-primary-500/30">
                        3
                    </div>
                    <h3 class="text-xl font-bold mb-3">Send & Analyze</h3>
                    <p class="text-gray-600 dark:text-gray-300">Schedule your campaign and watch the results come in. Optimize based on real-time analytics.</p>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="text-center mt-16">
                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-semibold hover:underline">
                    Start your free trial
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 lg:py-32 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase tracking-wider">Pricing</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Choose the plan that fits your needs. All plans include a 14-day free trial.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter Plan -->
                <div class="pricing-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300">
                    <h3 class="text-xl font-bold mb-2">Starter</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Perfect for small businesses</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold">$9.99</span>
                        <span class="text-gray-500 dark:text-gray-400">/month</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>2,500 emails/month
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>1 user
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Basic analytics
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>5 contact lists
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Email support
                        </li>
                    </ul>
                    <a href="<?php echo e(route('register')); ?>" class="block w-full py-3 text-center border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-600 hover:text-white transition">Get Started</a>
                </div>
                
                <!-- Pro Plan -->
                <div class="pricing-card bg-primary-600 rounded-2xl p-8 shadow-xl border-2 border-primary-500 transform scale-105 relative">
                    <div class="absolute top-0 right-0 bg-accent-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">POPULAR</div>
                    <h3 class="text-xl font-bold mb-2 text-white">Professional</h3>
                    <p class="text-primary-100 text-sm mb-4">Best for growing businesses</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-white">$29.99</span>
                        <span class="text-primary-100">/month</span>
                    </div>
                    <ul class="space-y-3 mb-8 text-white">
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>25,000 emails/month
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>5 users
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>Advanced analytics
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>Unlimited lists
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>Priority support
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-accent-300 mr-3"></i>Custom SMTP
                        </li>
                    </ul>
                    <a href="<?php echo e(route('register')); ?>" class="block w-full py-3 text-center bg-white text-primary-600 font-semibold rounded-xl hover:bg-gray-100 transition">Get Started</a>
                </div>
                
                <!-- Enterprise Plan -->
                <div class="pricing-card bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300">
                    <h3 class="text-xl font-bold mb-2">Enterprise</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">For large organizations</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold">$99.99</span>
                        <span class="text-gray-500 dark:text-gray-400">/month</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Unlimited emails
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Unlimited users
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Custom analytics
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Dedicated IP
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>24/7 phone support
                        </li>
                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-check text-green-500 mr-3"></i>Custom integrations
                        </li>
                    </ul>
                    <a href="<?php echo e(route('register')); ?>" class="block w-full py-3 text-center border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-600 hover:text-white transition">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-4">Loved by 10,000+ Businesses</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    See what our customers have to say about BulkSend
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">"BulkSend has transformed our email marketing. Our open rates increased by 40% and the analytics are incredibly detailed."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center text-primary-600 font-bold">
                            SJ
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold">Sarah Johnson</p>
                            <p class="text-sm text-gray-500">Marketing Director, TechCorp</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">"The best email marketing platform we've used. The automation features save us hours every week."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 font-bold">
                            MC
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold">Michael Chen</p>
                            <p class="text-sm text-gray-500">CEO, GrowthLabs</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">"Outstanding deliverability and customer support. Our campaigns have never performed better."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 font-bold">
                            EW
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold">Emily Williams</p>
                            <p class="text-sm text-gray-500">Founder, E-commerce Plus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 lg:py-32 bg-gray-50 dark:bg-gray-800/50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase tracking-wider">FAQ</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Got questions? We've got answers.
                </p>
            </div>
            
            <div class="space-y-4" x-data="{ active: null }">
                <!-- FAQ 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold">
                        <span>How does BulkSend work?</span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': active === 1 }"></i>
                    </button>
                    <div x-show="active === 1" class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        BulkSend is a cloud-based email marketing platform that allows you to create, send, and track email campaigns. Simply import your contacts, design your email using our drag-and-drop editor, select your SMTP configuration, and send to your entire list. Our platform handles deliverability, tracking, and analytics.
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold">
                        <span>Is BulkSend GDPR compliant?</span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': active === 2 }"></i>
                    </button>
                    <div x-show="active === 2" class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        Yes, BulkSend is fully GDPR compliant. We provide features like double opt-in, unsubscribe links in every email, data export, and account deletion to ensure you can manage your email marketing in compliance with GDPR regulations.
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <button @click="active = active === 3 ? null : 3" class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold">
                        <span>Can I use my own SMTP server?</span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': active === 3 }"></i>
                    </button>
                    <div x-show="active === 3" class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        Absolutely! BulkSend supports custom SMTP configurations. You can connect your own SMTP server or purchase SMTP credits from our verified marketplace. This gives you full control over your email deliverability.
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <button @click="active = active === 4 ? null : 4" class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold">
                        <span>What analytics do you provide?</span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': active === 4 }"></i>
                    </button>
                    <div x-show="active === 4" class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        We provide comprehensive analytics including open rates, click rates, bounce rates, unsubscribes, spam complaints, and geographic data. You can also track individual link clicks and measure campaign performance over time.
                    </div>
                </div>
                
                <!-- FAQ 5 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <button @click="active = active === 5 ? null : 5" class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold">
                        <span>Is there a free trial?</span>
                        <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': active === 5 }"></i>
                    </button>
                    <div x-show="active === 5" class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        Yes, we offer a 14-day free trial with full access to all features. No credit card required to start. You can send up to 500 emails during the trial period.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 lg:py-32 bg-gradient-to-br from-primary-600 to-primary-800 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                Ready to Grow Your Business?
            </h2>
            <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">
                Join thousands of businesses already using BulkSend to engage their audience and drive conversions.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?php echo e(route('register')); ?>" class="w-full sm:w-auto px-10 py-4 bg-white text-primary-600 font-bold rounded-xl hover:bg-gray-100 transition shadow-xl text-lg">
                    Start Free Trial
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#" class="w-full sm:w-auto px-10 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white/10 transition text-lg">
                    Schedule Demo
                </a>
            </div>
            <p class="mt-6 text-primary-100 text-sm">
                No credit card required • 14-day free trial • Cancel anytime
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">BulkSend</span>
                    </div>
                    <p class="mb-6 max-w-md">
                        Professional email marketing platform with advanced analytics, automation, and SMTP management. Turn subscribers into customers.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-primary-400 transition">Features</a></li>
                        <li><a href="#pricing" class="hover:text-primary-400 transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">Integrations</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">API</a></li>
                    </ul>
                </div>
                
                <!-- Company -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-primary-400 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">Careers</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between">
                <p class="text-sm">© 2024 BulkSend. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-sm hover:text-primary-400 transition">Privacy Policy</a>
                    <a href="#" class="text-sm hover:text-primary-400 transition">Terms of Service</a>
                    <a href="#" class="text-sm hover:text-primary-400 transition">GDPR</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Animation Observer -->
    <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
<?php /**PATH /Users/user/Desktop/Bulksend Email Only/resources/views/welcome.blade.php ENDPATH**/ ?>