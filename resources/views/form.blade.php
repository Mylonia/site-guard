<!DOCTYPE html>
<html>
<head>
    <title>Verification Required</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
        }
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        input[type="password"]:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }
        button {
            width: 100%;
            background: #3182ce;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover {
            background: #2c5282;
        }
        .error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: #2d3748;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="{{ route('site-guard.authenticate') }}">
        @csrf

        <div class="form-group">
            <label for="password">Password:</label>
            <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autofocus
            >
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Access Site</button>
    </form>
</div>
</body>
</html>