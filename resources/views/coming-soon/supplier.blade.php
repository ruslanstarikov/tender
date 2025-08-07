<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Portal - Coming Soon</title>
    @vite('resources/css/app.css')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V4m8 0h2a2 2 0 012 2v6.5"></path>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <h1 class="text-5xl font-bold text-white mb-4">
                Supplier Portal
            </h1>
            <h2 class="text-2xl font-light text-white/90 mb-8">
                Coming Soon
            </h2>
            
            <div class="space-y-6 text-white/80">
                <p class="text-lg leading-relaxed">
                    We're creating a comprehensive platform for our valued suppliers. Access tender opportunities, manage bids, and collaborate with our team through our upcoming supplier portal.
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mt-8">
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Tender Opportunities</h3>
                        <p class="text-sm text-white/70">Browse and bid on available projects</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Bid Management</h3>
                        <p class="text-sm text-white/70">Submit and track your proposals</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-3 text-white/70">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Collaboration Tools</h3>
                        <p class="text-sm text-white/70">Work seamlessly with our team</p>
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