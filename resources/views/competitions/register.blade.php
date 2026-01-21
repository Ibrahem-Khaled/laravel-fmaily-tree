<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل في المسابقة - {{ $competition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                {{ $competition->title }}
            </h1>
            @if($competition->description)
                <p class="text-gray-600">{{ $competition->description }}</p>
            @endif
            <div class="mt-4 flex justify-center gap-4 text-sm">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    <i class="fas fa-gamepad mr-1"></i>{{ $competition->game_type }}
                </span>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    <i class="fas fa-users mr-1"></i>{{ $competition->team_size }} عضو
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('competitions.register.store', $competition->registration_token) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">
                    الاسم <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">
                    رقم الهاتف <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">
                    البريد الإلكتروني (اختياري)
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="join_existing_team" name="join_existing_team" value="1" 
                        onchange="toggleTeamSelection()" {{ old('join_existing_team') ? 'checked' : '' }}>
                    <label class="form-check-label" for="join_existing_team">
                        الانضمام لفريق موجود
                    </label>
                </div>

                <div id="new_team_section">
                    <label class="block text-gray-700 font-bold mb-2">
                        اسم الفريق <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="team_name" id="team_name" value="{{ old('team_name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div id="existing_team_section" style="display: none;">
                    <label class="block text-gray-700 font-bold mb-2">
                        اختر الفريق
                    </label>
                    <select name="existing_team_id" id="existing_team_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">— اختر الفريق —</option>
                        @foreach($competition->teams()->where('is_complete', false)->get() as $team)
                            <option value="{{ $team->id }}" {{ old('existing_team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ $team->members->count() }}/{{ $competition->team_size }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i>تسجيل
            </button>
        </form>
    </div>

    <script>
        function toggleTeamSelection() {
            const joinExisting = document.getElementById('join_existing_team').checked;
            const newTeamSection = document.getElementById('new_team_section');
            const existingTeamSection = document.getElementById('existing_team_section');
            const teamNameInput = document.getElementById('team_name');
            const existingTeamSelect = document.getElementById('existing_team_id');

            if (joinExisting) {
                newTeamSection.style.display = 'none';
                existingTeamSection.style.display = 'block';
                teamNameInput.removeAttribute('required');
                existingTeamSelect.setAttribute('required', 'required');
            } else {
                newTeamSection.style.display = 'block';
                existingTeamSection.style.display = 'none';
                teamNameInput.setAttribute('required', 'required');
                existingTeamSelect.removeAttribute('required');
            }
        }

        // Initialize on page load
        toggleTeamSelection();
    </script>
</body>
</html>
