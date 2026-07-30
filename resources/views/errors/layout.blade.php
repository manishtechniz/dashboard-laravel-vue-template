<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050505;
            --text-color: #f3f4f6;
            --accent-color: #4f46e5;
            --accent-glow: rgba(79, 70, 229, 0.4);
            --gradient-start: #3b82f6;
            --gradient-end: #8b5cf6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .background-fx {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70vw;
            height: 70vw;
            max-width: 700px;
            max-height: 700px;
            background: radial-gradient(circle, var(--accent-glow) 0%, rgba(5, 5, 5, 0) 70%);
            z-index: 0;
            animation: pulse 6s infinite alternate;
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 3rem 2rem;
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 550px;
            width: 90%;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeUp 0.8s ease-out forwards;
        }

        .error-code {
            font-size: clamp(5rem, 15vw, 10rem);
            font-weight: 700;
            line-height: 1;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
            animation: float 4s ease-in-out infinite;
        }

        .title {
            font-size: 2rem;
            font-weight: 500;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .message {
            font-size: 1.1rem;
            font-weight: 300;
            color: #9ca3af;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -10px var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .btn-home::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -10px var(--accent-color);
        }

        .btn-home:hover::before {
            left: 100%;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(0.85);
                opacity: 0.5;
            }

            100% {
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0.9;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }
    </style>
</head>

<body>
    <div class="background-fx"></div>
    <div class="container">
        <div class="error-code">@yield('code')</div>
        <h1 class="title">@yield('heading')</h1>
        <p class="message">@yield('message')</p>

        @php
            $url = "javascript:void()";

            if (request()->is('admin/*')) {
                $url = route('admin.dashboard');
            }
        @endphp

        <a href="{{ $url  }}" class="btn-home">@yield('button', 'Return to Homepage')</a>
    </div>
</body>

</html>