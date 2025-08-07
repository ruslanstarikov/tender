<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Portal - Coming Soon</title>
    @vite('resources/css/app.css')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="text-center max-w-2xl mx-auto">
        <!-- Logo/Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto rounded-full glass-effect flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <h1 class="text-5xl font-bold text-white mb-4">
                Customer Portal
            </h1>
            <h2 class="text-2xl font-light text-white/90 mb-8">
                Coming Soon
            </h2>
            
            <div class="space-y-6 text-white/80">
                <p class="text-lg leading-relaxed">
                    We're building something amazing for our customers. Our new portal will provide you with seamless access to tender requests, project tracking, and direct communication with our team.
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mt-8">
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Tender Requests</h3>
                        <p class="text-sm text-white/70">Submit and track your project requests</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Project Tracking</h3>
                        <p class="text-sm text-white/70">Monitor progress in real-time</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Direct Communication</h3>
                        <p class="text-sm text-white/70">Stay connected with our team</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Animation -->
        <div class="flex justify-center items-center space-x-2">
            <div class="pulse-animation">
                <div class="w-3 h-3 bg-white/60 rounded-full"></div>
            </div>
            <div class="pulse-animation" style="animation-delay: 0.1s;">
                <div class="w-3 h-3 bg-white/60 rounded-full"></div>
            </div>
            <div class="pulse-animation" style="animation-delay: 0.2s;">
                <div class="w-3 h-3 bg-white/60 rounded-full"></div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-white/60 text-sm mt-8">
            Stay tuned for updates. We'll be launching soon!
        </p>
    </div>
</body>
</html>