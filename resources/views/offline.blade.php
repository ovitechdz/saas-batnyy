<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="OviTech Offline Mode">
    <title>Offline — OviTech Enterprise</title>
    
    <style>
        /* Minimal inline styles since we might not have app.css cached */
        :root {
            --bg-base: #05070B;
            --bg-card: #0F172A;
            --primary: #10B981;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .icon {
            width: 64px;
            height: 64px;
            color: #EF4444; /* Red color for offline */
            margin-bottom: 20px;
        }
        h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 800;
        }
        p {
            margin: 0 0 30px 0;
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.5;
        }
        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            width: 100%;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Lucide Wifi-off icon inline -->
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon" style="margin: 0 auto 20px auto;">
            <line x1="2" y1="2" x2="22" y2="22"></line>
            <path d="M8.5 16.5a5 5 0 0 1 7 0"></path>
            <path d="M2 8.82a15 15 0 0 1 4.17-2.65"></path>
            <path d="M10.66 5c4.01-.36 8.14.9 11.34 3.82"></path>
            <circle cx="12" cy="20" r="1"></circle>
        </svg>
        
        <h1>You are offline</h1>
        <p>It looks like you've lost your internet connection. Please check your network and try again.</p>
        
        <button onclick="window.location.reload()">Try Again</button>
    </div>
</body>
</html>
