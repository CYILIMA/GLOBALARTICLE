<x-admin-layout>
    @section('header', 'Edit Team Member')

    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg shadow-sm border transition-all hover:opacity-80" style="background: var(--sidebar-bg); border-color: var(--border-color)">
                <svg class="w-5 h-5" style="color: var(--text-main)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-2xl font-bold" style="color: var(--text-main)">Update Profile: {{ $user->name }}</h3>
                <p class="text-sm" style="color: var(--text-sidebar)">Modify permissions or reset the password for this team member.</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl shadow-sm border p-8 space-y-6 transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold mb-2" style="color: var(--text-main)">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" 
                           style="background: var(--content-bg); border-color: var(--border-color); color: var(--text-main)"
                           placeholder="Enter name...">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold mb-2" style="color: var(--text-main)">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                           class="w-full rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" 
                           style="background: var(--content-bg); border-color: var(--border-color); color: var(--text-main)"
                           placeholder="email@example.com">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-bold mb-2" style="color: var(--text-main)">Role Permissions</label>
                    <select name="role" id="role" required 
                            class="w-full rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                            style="background: var(--content-bg); border-color: var(--border-color); color: var(--text-main)">
                        <option value="author" {{ old('role', $user->role) == 'author' ? 'selected' : '' }}>Author</option>
                        <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="border-t pt-6 transition-colors" style="border-color: var(--border-color)">
                    <h4 class="text-sm font-bold mb-4" style="color: var(--text-main)">Reset Password (Optional)</h4>
                    <p class="text-xs mb-4" style="color: var(--text-sidebar)">Leave both fields empty if you don't want to change the password.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold mb-2" style="color: var(--text-main)">New Password</label>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" 
                                           class="w-full rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                           style="background: var(--content-bg); border-color: var(--border-color); color: var(--text-main); padding-right: 48px; border-width: 1px;">
                                    <button type="button" onclick="togglePassword('password')" 
                                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; cursor: pointer; display: flex; align-items: center;"
                                            class="text-gray-500 hover:text-gray-300 transition-colors">
                                        <svg id="password_eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path id="password_eye_path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold mb-2" style="color: var(--text-main)">Confirm New Password</label>
                                <div style="position: relative;">
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="w-full rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                           style="background: var(--content-bg); border-color: var(--border-color); color: var(--text-main); padding-right: 48px; border-width: 1px;">
                                    <button type="button" onclick="togglePassword('password_confirmation')" 
                                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; cursor: pointer; display: flex; align-items: center;"
                                            class="text-gray-500 hover:text-gray-300 transition-colors">
                                        <svg id="password_confirmation_eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path id="password_confirmation_eye_path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 p-4">
                <a href="{{ route('admin.users.index') }}" class="text-xs uppercase tracking-widest font-bold hover:opacity-80" style="color: var(--text-sidebar)">Cancel</a>
                <button type="submit" class="font-bold py-3 px-10 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 border"
                        style="background: var(--primary); color: #ffffff; border-color: var(--border-color);">
                    Update Account Details
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const isPassword = field.getAttribute('type') === 'password';
            field.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Toggle opacity or color to show active state
            if (isPassword) {
                event.currentTarget.classList.add('text-blue-500');
            } else {
                event.currentTarget.classList.remove('text-blue-500');
            }
        }
    </script>

    <style>
        /* Force dark background for autocomplete */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #000 inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</x-admin-layout>
