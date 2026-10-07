<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .response-box { white-space: pre-wrap; font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; margin-top: 0.5rem;}
    </style>
</head>
<body class="bg-gray-100 p-8 font-sans">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-600">Interfaz de Pruebas API Usuarios</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Obtener 10 usuarios -->
            <div class="border p-4 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-2">1. Obtener Top 10 Usuarios</h2>
                <button onclick="getUsers()" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded w-full">Obtener Usuarios</button>
                <div id="res-users" class="response-box hidden"></div>
            </div>

            <!-- Crear Usuario -->
            <div class="border p-4 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-2">2. Crear Usuario</h2>
                <form id="createForm" onsubmit="createUser(event)">
                    <input type="text" id="c-name" placeholder="Nombre" class="w-full mb-2 p-2 border rounded" required>
                    <input type="email" id="c-email" placeholder="Email" class="w-full mb-2 p-2 border rounded" required>
                    <input type="password" id="c-password" placeholder="Contraseña" class="w-full mb-2 p-2 border rounded" required>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded w-full">Crear Usuario</button>
                </form>
                <div id="res-create" class="response-box hidden"></div>
            </div>

            <!-- Login -->
            <div class="border p-4 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-2">3. Login</h2>
                <form id="loginForm" onsubmit="loginUser(event)">
                    <input type="email" id="l-email" placeholder="Email" class="w-full mb-2 p-2 border rounded" required>
                    <input type="password" id="l-password" placeholder="Contraseña" class="w-full mb-2 p-2 border rounded" required>
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded w-full">Iniciar Sesión</button>
                </form>
                <div id="res-login" class="response-box hidden"></div>
            </div>

            <!-- Actualizar Nombre -->
            <div class="border p-4 rounded-lg bg-gray-50">
                <h2 class="text-xl font-semibold mb-2">4. Actualizar Nombre</h2>
                <form id="updateForm" onsubmit="updateName(event)">
                    <input type="text" id="u-token" placeholder="Token de Sesión" class="w-full mb-2 p-2 border rounded" required>
                    <input type="text" id="u-name" placeholder="Nuevo Nombre" class="w-full mb-2 p-2 border rounded" required>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded w-full">Actualizar Nombre</button>
                </form>
                <div id="res-update" class="response-box hidden"></div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = '/api';

        function showResponse(elementId, data) {
            const el = document.getElementById(elementId);
            el.innerHTML = JSON.stringify(data, null, 2);
            el.classList.remove('hidden');
        }

        async function getUsers() {
            try {
                const response = await fetch(`${baseUrl}/users`);
                const data = await response.json();
                showResponse('res-users', data);
            } catch (err) {
                showResponse('res-users', {error: err.message});
            }
        }

        async function createUser(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('c-name').value,
                email: document.getElementById('c-email').value,
                password: document.getElementById('c-password').value,
            };
            try {
                const response = await fetch(`${baseUrl}/users`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                showResponse('res-create', data);
            } catch (err) {
                showResponse('res-create', {error: err.message});
            }
        }

        async function loginUser(e) {
            e.preventDefault();
            const payload = {
                email: document.getElementById('l-email').value,
                password: document.getElementById('l-password').value,
            };
            try {
                const response = await fetch(`${baseUrl}/login`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                showResponse('res-login', data);
                if (data.success && data.data) {
                    document.getElementById('u-token').value = data.data; // Autocompletar token
                }
            } catch (err) {
                showResponse('res-login', {error: err.message});
            }
        }

        async function updateName(e) {
            e.preventDefault();
            const payload = {
                token: document.getElementById('u-token').value,
                name: document.getElementById('u-name').value,
            };
            try {
                const response = await fetch(`${baseUrl}/users/update-name`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                showResponse('res-update', data);
            } catch (err) {
                showResponse('res-update', {error: err.message});
            }
        }
    </script>
</body>
</html>
