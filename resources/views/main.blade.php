<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>API Documentation - Clinic Backend</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 2rem;
            background: #181a1b;
            color: #f1f1f1;
        }

        h1 {
            color: #00e1ff;
            letter-spacing: 1px;
        }

        h2 {
            color: #ffb347;
            margin-top: 2rem;
        }

        code,
        pre {
            background: #23272b;
            color: #00e1ff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 1em;
        }

        .endpoint {
            margin-bottom: 1.5rem;
        }

        .method {
            font-weight: bold;
            color: #00ff99;
        }

        .auth {
            color: #ffb347;
            font-weight: bold;
        }

        ul {
            margin-bottom: 2rem;
        }

        li {
            margin-bottom: 0.5rem;
        }

        hr {
            border: 0;
            border-top: 1px solid #333;
            margin: 2rem 0;
        }

        .note {
            background: #23272b;
            color: #ffb347;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 2rem;
            font-size: 1.05em;
        }

        a {
            color: #00e1ff;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Clinic Backend API Documentation</h1>
    <p>Base URL: <code>{{ env('APP_URL') . '/api' }}</code></p>

    <h2>Authentication</h2>
    <ul>
        <li><span class="method">POST</span> <code>/login</code> — Login user</li>
        <li><span class="method">POST</span> <code>/login/google</code> — Login with Google</li>
        <li><span class="method">POST</span> <code>/logout</code> <span class="auth">(Authentication Required)</span> —
            Logout user
        </li>
    </ul>
    <h2>Call Room (Agora)</h2>
    <ul>
        <li><span class="method">POST</span> <code>/agora/generate-token</code> <span class="auth">(Authentication Required)</span> — Create/generate token for call room</li>
        <li><span class="method">GET</span> <code>/agora/{user_id}/call-rooms</code> <span class="auth">(Authentication Required)</span> — Get all call rooms for user</li>
        <li><span class="method">PUT</span> <code>/agora/{id}/call-rooms/{status}</code> <span class="auth">(Authentication Required)</span> — Update call room status</li>
    </ul>

    <h2>User</h2>
    <ul>
        <li><span class="method">POST</span> <code>/user</code> — Register user</li>
        <li><span class="method">POST</span> <code>/user/check</code> <span class="auth">(Authentication
                Required)</span> — Check
            user</li>
        <li><span class="method">GET</span> <code>/user{email}</code> — Get user by email</li>
        <li><span class="method">PUT</span> <code>/user/googleid/{id}</code> — Update Google ID</li>
        <li><span class="method">PUT</span> <code>/user/{id}</code> — Update user</li>
        <li><span class="method">POST</span> <code>/users/{user}/update-token</code> — Update OneSignal token</li>
    </ul>

    <h2>Doctor</h2>
    <ul>
        <li><span class="method">GET</span> <code>/clinic/doctor</code> <span class="auth">(Authentication
                Required)</span> —
            List all doctors</li>
        <li><span class="method">POST</span> <code>/clinic/doctor</code> <span class="auth">(Authentication
                Required)</span> —
            Add doctor</li>
        <li><span class="method">PUT</span> <code>/clinic/doctor/{user}</code> <span class="auth">(Authentication
                Required)</span> — Update doctor</li>
        <li><span class="method">DELETE</span> <code>/clinic/doctor/{user}</code> <span class="auth">(Authentication
                Required)</span> — Delete doctor</li>
        <li><span class="method">GET</span> <code>/clinic/doctor/active</code> — Get active doctors</li>
        <li><span class="method">GET</span> <code>/doctor/search/</code> <span class="auth">(Authentication
                Required)</span> —
            Search doctor</li>
        <li><span class="method">GET</span> <code>/doctor/clinic/{clinic_id}</code> <span class="auth">(Authentication
                Required)</span> — Get doctor by clinic</li>
        <li><span class="method">GET</span> <code>/doctor/specialist/{specialist_id}</code> <span
                class="auth">(Authentication Required)</span> — Get doctor by specialist</li>
        <li><span class="method">GET</span> <code>/doctor/{id}</code> <span class="auth">(Authentication
                Required)</span> — Get
            doctor by ID</li>
    </ul>

    <h2>Chat Room</h2>
    <ul>
        <li><span class="method">GET</span> <code>/{user}/chat-rooms</code> <span class="auth">(Authentication
                Required)</span> —
            Get chat rooms for user</li>
    </ul>

    <h2>Order</h2>
    <ul>
        <li><span class="method">GET</span> <code>/orders</code> <span class="auth">(Authentication Required)</span> —
            List all
            orders</li>
        <li><span class="method">POST</span> <code>/orders</code> <span class="auth">(Authentication Required)</span>
            — Create
            order</li>
        <li><span class="method">GET</span> <code>/orders/patient/{patient_id}</code> <span
                class="auth">(Authentication Required)</span> — Order history by patient</li>
        <li><span class="method">GET</span> <code>/orders/doctor/{doctor_id}</code> <span class="auth">(Authentication
                Required)</span> — Order history by doctor</li>
        <li><span class="method">GET</span> <code>/orders/doctor/{doctor_id}/{service}/{status_service}</code> <span
                class="auth">(Authentication Required)</span> — Order by doctor query</li>
        <li><span class="method">GET</span> <code>/orders/clinic/{clinic_id}</code> <span class="auth">(Authentication
                Required)</span> — Order history by clinic</li>
        <li><span class="method">POST</span> <code>/orders/xendit-callback</code> — Xendit callback</li>
    </ul>

    <h2>Notification</h2>
    <ul>
        <li><span class="method">POST</span> <code>/notification/send</code> <span class="auth">(Authentication
                Required)</span>
            — Send notification</li>
    </ul>

    <h2>Specialist & Clinic</h2>
    <ul>
        <li><span class="method">GET</span> <code>/specialists</code> — List all specialists</li>
        <li><span class="method">GET</span> <code>/clinic/{id}</code> <span class="auth">(Authentication
                Required)</span> — Get
            clinic by ID</li>
        <li><span class="method">GET</span> <code>/clinic/doctors/{clinic_id}</code> <span
                class="auth">(Authentication Required)</span> — Get doctors by clinic</li>
    </ul>

    <div class="note">
        <b>Note:</b> Endpoint dengan label <span class="auth">(Authentication Required)</span> membutuhkan autentikasi
        token
        Sanctum.
    </div>
</body>

</html>
