<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy - InfiMal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .rainbow-text {
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #96CEB4, #FFEAA7, #FF6B6B);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 8s ease infinite;
        }
        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.3);
            transform: translateY(-2px);
        }
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .policy-section {
            border-left: 4px solid;
            padding-left: 1.5rem;
            margin: 2rem 0;
        }
        .policy-important {
            background: linear-gradient(135deg, #667eea0d 0%, #764ba20d 100%);
            border-left: 4px solid #667eea;
        }
        .paddle-note {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #0ea5e9;
        }
    </style>
</head>
<body class="bg-white" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold rainbow-text">InfiMal</a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/features" class="text-gray-600 hover:text-gray-900 font-medium transition">Features</a>
                    <a href="/pricing" class="text-gray-600 hover:text-gray-900 font-medium transition">Pricing</a>
                    <a href="/login" class="text-gray-600 hover:text-gray-900 font-medium transition">Login</a>
                    <a href="/register" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover-glow transition-all duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  :d="mobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-4 space-y-4">
                <a href="/features" class="block text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="/pricing" class="block text-gray-600 hover:text-gray-900 font-medium">Pricing</a>
                <a href="/login" class="block text-gray-600 hover:text-gray-900 font-medium">Login</a>
                <a href="/register" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center hover-glow">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Refund Policy Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 fade-in">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Refund <span class="rainbow-text">Policy</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto">
                    Last Updated: January 15, 2024
                </p>
                <div class="glass-card rounded-2xl p-6 mb-8">
                    <p class="text-gray-700 leading-relaxed">
                        At InfiMal, we strive to ensure your complete satisfaction. This policy complies with the requirements of our payment partner, Paddle, and outlines your refund rights.
                    </p>
                </div>
            </div>

            <!-- Paddle Compliance Notice (NEW SECTION) -->
            <div class="paddle-note rounded-xl p-6 mb-8 fade-in">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Paddle Payment Compliance</h3>
                        <p class="text-gray-700">
                            InfiMal uses Paddle.com Market Ltd as its Merchant of Record. Paddle's terms require us to maintain a minimum 14-day refund window policy for digital products.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Important Notice (UPDATED) -->
            <div class="policy-important rounded-xl p-6 mb-8 fade-in">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Important Notice</h3>
                        <p class="text-gray-700 mb-3">
                            You have the right to request a refund within 14 days of your initial purchase without giving any reason.
                        </p>
                        <p class="text-gray-700 text-sm">
                            <strong>For EU/UK Customers:</strong> By requesting immediate access to our digital service, you acknowledge that you lose your statutory right of withdrawal under EU/UK consumer protection law. Once you start using the service, the 14-day cooling-off period no longer applies.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Refund Eligibility (UPDATED) -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Refund Eligibility</h2>
                </div>
                
                <div class="space-y-6">
                    <div class="policy-section border-green-500">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">14-Day Refund Policy</h3>
                        <p class="text-gray-700 mb-4">
                            We offer a 14-day refund policy for all new customers. If you're not satisfied with our service within the first 14 days of your initial subscription, you can request a full refund.
                        </p>
                        <ul class="list-disc pl-5 text-gray-700 space-y-2">
                            <li>Refund requests must be made within <strong>14 days</strong> of initial purchase</li>
                            <li>Applicable to first-time subscriptions (both monthly and annual)</li>
                            <li>Only one refund per customer for the same service</li>
                            <li>Refund will be processed to your original payment method</li>
                        </ul>
                    </div>

                    <div class="policy-section border-blue-500">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Technical Issues</h3>
                        <p class="text-gray-700">
                            If you experience persistent technical issues that prevent you from using our service, please contact our support team first. We'll make every effort to resolve the issue within 48 hours. If unresolved, you may request a refund within the 14-day period.
                        </p>
                    </div>
                </div>
            </div>

            <!-- How to Request a Refund -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">How to Request a Refund</h2>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-200">
                            <div class="text-3xl font-bold text-blue-600 mb-2">1</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Contact Support</h4>
                            <p class="text-gray-600 text-sm">Email us at <strong>support@infimal.site</strong> with "Refund Request" in subject</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl border border-gray-200">
                            <div class="text-3xl font-bold text-blue-600 mb-2">2</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Provide Details</h4>
                            <p class="text-gray-600 text-sm">Include your account email and Paddle invoice number</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl border border-gray-200">
                            <div class="text-3xl font-bold text-blue-600 mb-2">3</div>
                            <h4 class="font-semibold text-gray-900 mb-2">Wait for Processing</h4>
                            <p class="text-gray-600 text-sm">We'll process your request within 2 business days</p>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Required Information:</h4>
                        <ul class="list-disc pl-5 text-gray-700 space-y-1">
                            <li>Account email address registered with InfiMal</li>
                            <li>Paddle invoice number (from your receipt email)</li>
                            <li>Reason for refund request</li>
                            <li>Date of purchase (must be within 14 days)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Non-Refundable Items (UPDATED) -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Non-Refundable After 14 Days</h2>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-gray-700"><strong>Subscriptions after 14 days:</strong> No refunds for services used beyond the 14-day period</p>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-gray-700"><strong>Subscription renewals:</strong> Automatic renewals must be cancelled before next billing date</p>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-gray-700"><strong>Add-on services:</strong> Custom development or additional services</p>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-gray-700"><strong>Terms of Service violations:</strong> Accounts suspended for policy violations</p>
                    </div>
                </div>
            </div>

            <!-- Processing Timeline -->
            <div class="glass-card rounded-2xl p-8 shadow-lg mb-8 fade-in hover-glow transition-all duration-300">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Refund Processing Timeline</h2>
                
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold">1</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Request Submitted</h4>
                            <p class="text-gray-600 text-sm">Within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold">2</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Review & Approval</h4>
                            <p class="text-gray-600 text-sm">2 business days maximum</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold">3</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Refund Processed</h4>
                            <p class="text-gray-600 text-sm">5-10 business days via Paddle</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-green-600 font-semibold">?</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">Funds Received</h4>
                            <p class="text-gray-600 text-sm">Depends on your bank/payment provider</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-700 text-sm">
                        <strong>Note:</strong> Refunds are processed through Paddle.com Market Ltd, our authorized merchant of record. The time it takes for the refund to appear in your account depends on Paddle's processing time and your payment method.
                    </p>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="glass-card rounded-2xl p-8 shadow-lg text-center fade-in hover-glow transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Refund Questions?</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    For refund requests or questions about this policy, contact our support team.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="mailto:support@infimal.site?subject=Refund Request" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-medium hover-glow transition-all duration-300">
                        Request Refund
                    </a>
                    <a href="/contact" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-50 transition">
                        Contact Support
                    </a>
                </div>
                <p class="text-gray-500 text-sm mt-6">
                    For payment-related issues, you may also contact Paddle directly at: <a href="mailto:sellers@paddle.com" class="text-blue-600 hover:underline">sellers@paddle.com</a>
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold rainbow-text mb-4">InfiMal</h3>
                    <p class="text-gray-400">Professional email marketing platform for agencies and businesses.</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/features" class="hover:text-white transition">Features</a></li>
                        <li><a href="/pricing" class="hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms</a></li>
                        <li><a href="/refund" class="text-white font-medium">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 InfiMal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Fade in animation
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        fadeElements.forEach(el => fadeInObserver.observe(el));
    </script>
</body>
</html>
