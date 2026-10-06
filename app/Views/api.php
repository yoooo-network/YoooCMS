<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yooo API Documentation</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        sidebar: '#1e293b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-slate-200 flex flex-col overflow-y-auto shrink-0 shadow-xl z-10">
        <div class="p-6 border-b border-slate-700">
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="bi bi-braces text-blue-500"></i> Yooo API
            </h1>
            <p class="text-sm text-slate-400 mt-1">v1.0 Documentation</p>
        </div>

        <nav class="flex-1 py-4">
            <div class="mb-6">
                <div class="px-6 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Getting Started</div>
                <a href="#introduction" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200" data-target="introduction"><i class="bi bi-info-circle mr-2 text-slate-400"></i>Introduction</a>
                <a href="#authentication-guide" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200" data-target="authentication-guide"><i class="bi bi-shield-lock mr-2 text-slate-400"></i>Authentication</a>
                <a href="#errors" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200" data-target="errors"><i class="bi bi-exclamation-triangle mr-2 text-slate-400"></i>Errors</a>
            </div>

            <div class="mb-6">
                <div class="px-6 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Public Endpoints</div>
                <a href="#get-home" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-home"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Home</a>
                <a href="#get-countries" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-countries"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Countries</a>
                <a href="#get-cities" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-cities"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Cities</a>
                <a href="#get-profiles" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-profiles"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Profiles</a>
                <a href="#get-profile-single" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-profile-single"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Profile</a>
            </div>

            <div class="mb-6">
                <div class="px-6 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Auth Endpoints</div>
                <a href="#post-login" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-login"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Login</a>
                <a href="#post-signup" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-signup"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Sign Up</a>
                <a href="#get-auth-verify" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-auth-verify"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Verify Email</a>
                <a href="#post-auth-resend" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-auth-resend"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Resend Verify</a>
                <a href="#post-auth-forgot" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-auth-forgot"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Forgot Password</a>
                <a href="#post-auth-reset" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-auth-reset"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Reset Password</a>
                <a href="#post-system-cache" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-system-cache"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Clear Cache</a>
            </div>
            
            <div class="mb-6">
                <div class="px-6 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">User Panel</div>
                <a href="#get-dashboard" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-dashboard"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> Dashboard</a>
                <a href="#get-user-profile" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="get-user-profile"><span class="inline-block w-10 text-center text-[10px] font-bold bg-emerald-500 text-white rounded px-1 py-0.5 mr-3">GET</span> User Profile</a>
                <a href="#post-user-profile-update" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-user-profile-update"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Update Profile</a>
                <a href="#post-user-upload-photo" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-user-upload-photo"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Upload Photo</a>
                <a href="#post-change-password" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-change-password"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Change Password</a>
                <a href="#post-delete-account" class="nav-item block px-6 py-2.5 text-sm hover:bg-slate-800 hover:text-white transition-colors duration-200 flex items-center" data-target="post-delete-account"><span class="inline-block w-10 text-center text-[10px] font-bold bg-blue-500 text-white rounded px-1 py-0.5 mr-3">POST</span> Delete Account</a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-10 scroll-smooth bg-slate-50 relative">
        <div class="max-w-4xl mx-auto pb-20 relative">
            
            <!-- Getting Started Sections -->
            <section id="introduction" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-3xl font-bold text-slate-900 mb-4 flex items-center gap-3"><i class="bi bi-info-circle text-blue-600"></i> Introduction</h2>
                <p class="text-slate-600 leading-relaxed mb-4">Welcome to the Yooo API documentation. This API allows you to programmatically access and interact with the Yooo platform. It provides endpoints for user authentication, comprehensive profile management, contacts syncing, and retrieving public data such as user profiles, countries, and cities.</p>
                <p class="text-slate-600 leading-relaxed mb-4">The API is built using RESTful principles and returns standard JSON responses. The base URL for all endpoints is:</p>
                <div class="bg-slate-900 text-slate-300 p-4 rounded-lg font-mono text-sm overflow-x-auto shadow-inner flex items-center justify-between group cursor-pointer hover:bg-slate-800 transition-colors" onclick="navigator.clipboard.writeText('https://api.yooo.app/api/v1'); alert('Copied to clipboard!');">
                    <span>https://api.yooo.app/api/v1</span>
                    <i class="bi bi-clipboard text-slate-400 group-hover:text-white transition-colors" title="Copy to clipboard"></i>
                </div>
                
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-3">Standard Response Format</h3>
                <p class="text-slate-600 leading-relaxed mb-4">All successful requests will return a consistent JSON structure wrapped with a status, a message, and the requested data payload:</p>
                <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success message here",
  "data": {
    // ... payload data
  }
}</pre>
                </div>
            </section>

            <section id="authentication-guide" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-3xl font-bold text-slate-900 mb-4 flex items-center gap-3"><i class="bi bi-shield-lock text-blue-600"></i> Authentication</h2>
                <p class="text-slate-600 leading-relaxed mb-4">Protected API endpoints require authentication via JWT (JSON Web Tokens). You can obtain a token by using the <strong>Login</strong> or <strong>Sign Up</strong> endpoints.</p>
                <p class="text-slate-600 leading-relaxed mb-4">Once you have a token, you must include it in the <code class="bg-slate-200 px-1 py-0.5 rounded text-slate-800">Authorization</code> header of your HTTP requests:</p>
                <div class="bg-slate-900 text-slate-300 p-4 rounded-lg font-mono text-sm overflow-x-auto shadow-inner">
                    Authorization: Bearer YOUR_ACCESS_TOKEN
                </div>
            </section>

            <section id="errors" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-3xl font-bold text-slate-900 mb-4 flex items-center gap-3"><i class="bi bi-exclamation-triangle text-blue-600"></i> Errors</h2>
                <p class="text-slate-600 leading-relaxed mb-4">The API uses standard HTTP status codes to indicate the success or failure of an API request. Failed requests will always return a status of <code class="text-sm bg-slate-200 px-1 py-0.5 rounded">error</code> and a descriptive message.</p>
                
                <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed mb-6">
<pre>{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": "The email field must contain a valid email address.",
    "password": "The password field is required."
  }
}</pre>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white rounded-xl overflow-hidden shadow-sm border border-slate-200">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200 w-32">Code</th>
                                <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-emerald-600">200 / 201</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Success:</strong> The request was successful and data is returned.</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-orange-500">400</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Bad Request:</strong> The request was unacceptable, often due to missing or invalid parameters.</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-red-500">401</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Unauthorized:</strong> No valid API token provided or the token has expired.</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-red-500">403</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Forbidden:</strong> The API token doesn't have permissions to perform the request.</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">404</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Not Found:</strong> The requested resource does not exist.</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-red-700">500</td>
                                <td class="py-3 px-4 text-slate-600"><strong>Server Error:</strong> Something went wrong on the API's end.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="my-10">
                <h1 class="text-4xl font-bold text-slate-800 border-b-4 border-emerald-500 inline-block pb-2">Public Endpoints</h1>
                <p class="text-slate-500 mt-2">Endpoints accessible without authentication.</p>
            </div>

            <!-- PUBLIC ENDPOINTS -->
            <section id="get-home" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Get Home</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve initial home data and recent profiles.</p>
                
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/home</span>
                    </div>
                    <div class="p-6">
                        <div class="italic text-slate-400 text-sm mb-4">No parameters required.</div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Welcome to the Yooo API",
  "data": {
    "message": "Welcome to the Yooo API",
    "recent_profiles": [
      {
        "id": 10,
        "status": "active",
        "name": "Jane Doe",
        "...": "..."
      }
    ]
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-countries" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Get Countries</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve a list of available active countries.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/countries</span>
                    </div>
                    <div class="p-6">
                        <div class="italic text-slate-400 text-sm mb-4">No parameters required.</div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "countries": [
      {
        "id": 1,
        "name": "United States",
        "code": "US"
      }
    ]
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-cities" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Get Cities</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve cities, optionally filtered by country.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/cities</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-funnel text-slate-400"></i> Query Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">country_id</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">integer</td>
                                        <td class="py-3 px-4 text-slate-600">Optional. Filter cities by the given country ID.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "cities": [
      {
        "id": 100,
        "name": "New York",
        "country_id": 1
      }
    ]
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-profiles" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Get Profiles List</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve a list of profiles with extensive filtering options.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/profiles</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-funnel text-slate-400"></i> Query Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">gender</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by gender. Defaults to <code>female</code>.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">country</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by country name.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">city</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by city name.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">membership</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by membership level.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">sexuality</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string|array</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by sexuality. Accepts an array or comma-separated values, for example <code>Homo,Bisexual</code>.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">sexualities</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string|array</td>
                                        <td class="py-3 px-4 text-slate-600">Legacy alias for <code>sexuality</code>; accepts an array or comma-separated values.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">is_verified</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">boolean</td>
                                        <td class="py-3 px-4 text-slate-600">Filter only verified profiles (true/false).</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">services</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string|array</td>
                                        <td class="py-3 px-4 text-slate-600">Filter by provided services. Can be an array or comma-separated string.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">limit</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">integer</td>
                                        <td class="py-3 px-4 text-slate-600">Number of profiles to retrieve. Defaults to <code>24</code>.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "profiles": [
      {
        "id": 1,
        "name": "Jane Doe",
        "gender": "female",
        "is_verified": true,
        "...": "..."
      }
    ]
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-profile-single" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Get Single Profile Details</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve specific details of a single profile by ID.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/profile/{id}</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-link-45deg text-slate-400"></i> Path Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            id
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">integer</td>
                                        <td class="py-3 px-4 text-slate-600">The unique ID of the profile.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "profile": {
      "id": 1,
      "name": "Jane Doe",
      "gender": "female",
      "country": "United States",
      "city": "New York",
      "about": "Hello, this is my profile.",
      "...": "..."
    }
  }
}</pre>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 mt-6 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-exclamation-circle text-red-400"></i> Error Example</span>
                            <span class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded font-mono font-bold">404 Not Found</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "error",
  "message": "Profile not found"
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <div class="my-10 pt-10">
                <h1 class="text-4xl font-bold text-slate-800 border-b-4 border-blue-500 inline-block pb-2">Auth Endpoints</h1>
                <p class="text-slate-500 mt-2">Endpoints related to user authentication and account recovery.</p>
            </div>

            <!-- AUTH ENDPOINTS -->
            <section id="post-login" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Login</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Authenticate a user and receive a JWT token. Requires an active account.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/login</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters <span class="text-sm font-normal text-slate-500 ml-2">(application/json or form-data)</span></h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            email
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The user's registered email address. Must be a valid email.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The user's password.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
      "id": 1,
      "username": "Jane Doe",
      "email": "jane@example.com"
    }
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-signup" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Sign Up</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Register a new user account. Returns an authentication token immediately and triggers a verification email.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/signup</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            name
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Full name of the user. Min 3, max 20 chars.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            email
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Must be a valid, unique email address.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Password for the account. Minimum 8 characters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Account created successfully. Please verify your email.",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "verification_email_sent": true,
    "user": {
      "id": 2,
      "username": "Jane Doe",
      "email": "jane@example.com"
    }
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-auth-verify" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Verify Email</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Verify an email address using the token sent in the welcome email.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/auth/verify-email</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-funnel text-slate-400"></i> Query Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            token
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The verification token sent to the user's email.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Email verified successfully.",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-auth-resend" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Resend Verification</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Resend the email verification token if not already verified. (Cooldown applies).</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/auth/resend-verification</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            email
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The registered email address.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Verification email processed.",
  "data": {
    "sent": true
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-auth-forgot" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Forgot Password</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Request a password reset link to be sent via email.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/auth/forgot-password</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            email
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The registered email address.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "If an account exists for that email, a reset link has been sent.",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-auth-reset" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Reset Password</h2>
                <p class="text-slate-600 leading-relaxed mb-6">Reset the password using the token from the forgot password email.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/auth/reset-password</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters <span class="text-sm font-normal text-slate-500 ml-2">(JSON only)</span></h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            token
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The valid reset token.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">New password. Minimum 8 characters.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            confirm_password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Must match the <code>password</code> parameter exactly.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Password reset successfully.",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>


            <div class="my-10 pt-10">
                <h1 class="text-4xl font-bold text-slate-800 border-b-4 border-purple-500 inline-block pb-2">User Panel Endpoints</h1>
                <p class="text-slate-500 mt-2">Requires a valid JWT Bearer token in the Authorization header. Actions performed here are scoped to the authenticated user.</p>
            </div>

            <!-- USER PANEL ENDPOINTS -->
            <section id="get-dashboard" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2">Dashboard <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve user dashboard overview, including basic user account info and high-level profile statistics (like completion percentage).</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/dashboard</span>
                    </div>
                    <div class="p-6">
                        <div class="italic text-slate-400 text-sm mb-4">No parameters required. Header must contain Bearer Token.</div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "profile": {
      "id": 15,
      "status": "active",
      "is_verified": true,
      "membership": "premium",
      "completion": 85
    }
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="get-user-profile" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2">Get User Profile <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Retrieve the authenticated user's complete, editable profile data. JSON string fields (like images, pricing, services) are automatically decoded into arrays.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded">GET</span>
                        <span class="text-slate-800 font-semibold">/user/profile</span>
                    </div>
                    <div class="p-6">
                        <div class="italic text-slate-400 text-sm mb-4">No parameters required.</div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex justify-between items-center">
                            <span class="flex items-center gap-2"><i class="bi bi-box-arrow-right text-slate-400"></i> Response Example</span>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono font-bold">200 OK</span>
                        </h3>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Success",
  "data": {
    "profile": {
      "id": 15,
      "name": "John Doe",
      "gender": "male",
      "dob": "1990-01-01",
      "location": "Miami, FL",
      "images": ["user_1_12345.webp"],
      "services": ["service1", "service2"]
    }
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-user-profile-update" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2">Update Profile <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Create or update the user's profile. You must send a valid JSON payload. Certain fields (like <code class="text-sm bg-slate-200 px-1 py-0.5 rounded">images</code>, <code class="text-sm bg-slate-200 px-1 py-0.5 rounded">services</code>, etc.) should be sent as JSON arrays.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/user/profile/update</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters <span class="text-sm font-normal text-slate-500 ml-2">(application/json ONLY)</span></h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">name <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span></td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Display name (3-100 chars).</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">gender <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span></td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Must be <code>male</code>, <code>female</code>, or <code>other</code>.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">dob <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span></td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Date of birth in <code>YYYY-MM-DD</code> format.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">location <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span></td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Location text (3-255 chars).</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">images</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">array</td>
                                        <td class="py-3 px-4 text-slate-600">Array of image filenames.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">pricing, services, languages, other_pages, sexuality</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">array/object</td>
                                        <td class="py-3 px-4 text-slate-600">Must be sent as JSON structures. Will be encoded before saving.</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800 italic">other fields...</td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">description, phone, whatsapp, height, weight, ethnicity, etc.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Profile updated successfully",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-user-upload-photo" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2">Upload Photo <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Upload a single profile photo. The server will process it, create WEBP variants, and append the filename to the profile's image list.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/user/profile/upload-photo</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters <span class="text-sm font-normal text-slate-500 ml-2">(multipart/form-data)</span></h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            photo <span class="text-slate-400 font-normal">or</span> image
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">file</td>
                                        <td class="py-3 px-4 text-slate-600">The image file to upload. Allowed types: JPG, PNG, WEBP.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Photo uploaded successfully",
  "data": {
    "image": "user_1_169...webp",
    "images": [
      "user_1_169...webp"
    ]
  }
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-change-password" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2">Change Password <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Change the authenticated user's account password.</p>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 border-b border-slate-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-slate-800 font-semibold">/user/change-password</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2 flex items-center gap-2"><i class="bi bi-box-arrow-in-right text-slate-400"></i> Body Parameters</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Parameter</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Type</th>
                                        <th class="py-3 px-4 font-semibold text-sm text-slate-600 border-b border-slate-200">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            current_password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The user's current password.</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            new_password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">The new password (min 8 chars).</td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            confirm_password
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-2 font-bold uppercase tracking-wide">Required</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500 font-mono text-xs">string</td>
                                        <td class="py-3 px-4 text-slate-600">Must match <code>new_password</code> exactly.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Password updated successfully.",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-delete-account" class="mb-16 pb-10 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-2 text-red-600">Delete Account <i class="bi bi-lock-fill text-slate-400 text-lg" title="Requires Authentication"></i></h2>
                <p class="text-slate-600 leading-relaxed mb-6">Permanently delete the user's account, profile data, and uploaded images.</p>
                <div class="bg-white border border-red-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-red-50 border-b border-red-200 px-5 py-4 flex items-center gap-3 font-mono text-lg">
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">POST</span>
                        <span class="text-red-800 font-semibold">/user/delete-account</span>
                    </div>
                    <div class="p-6">
                        <div class="italic text-slate-400 text-sm mb-4">No parameters required. Authentication token maps to the user.</div>
                        <div class="bg-slate-900 text-slate-300 p-5 rounded-lg font-mono text-sm overflow-x-auto shadow-inner leading-relaxed">
<pre>{
  "status": "success",
  "message": "Your account has been deleted successfully.",
  "data": []
}</pre>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <script>
        // Smooth scrolling and active state logic
        const navItems = document.querySelectorAll('.nav-item');
        const sections = document.querySelectorAll('section[id]');

        // Add click listener for smooth scroll and active class
        navItems.forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);
                
                if(targetElement) {
                    // Update active classes immediately on click
                    updateActiveNav(targetId);
                    
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        function updateActiveNav(activeId) {
             navItems.forEach(nav => {
                const icon = nav.querySelector('i');
                // Reset all
                nav.classList.remove('bg-blue-600', 'text-white', 'font-medium', 'shadow-md');
                nav.classList.add('text-slate-200', 'hover:bg-slate-800');
                if(icon) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-slate-400');
                }
            });
            
            // Set active
            const activeNav = document.querySelector(`.nav-item[data-target="${activeId}"]`);
            if (activeNav) {
                const activeIcon = activeNav.querySelector('i');
                activeNav.classList.remove('text-slate-200', 'hover:bg-slate-800');
                activeNav.classList.add('bg-blue-600', 'text-white', 'font-medium', 'shadow-md');
                if(activeIcon) {
                    activeIcon.classList.remove('text-slate-400');
                    activeIcon.classList.add('text-white');
                }
            }
        }

        // Intersection Observer to update active state on scroll
        const observerOptions = {
            root: document.querySelector('main'),
            rootMargin: '0px 0px -80% 0px', // Trigger tightly
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    updateActiveNav(id);
                }
            });
        }, observerOptions);

        sections.forEach((section) => {
            observer.observe(section);
        });

        // Initialize first item as active
        if(navItems.length > 0) {
            updateActiveNav('introduction');
        }
    </script>
</body>
</html>
