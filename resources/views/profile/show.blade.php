<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My Profile</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-bold">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border rounded w-full p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-bold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border rounded w-full p-2">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-bold">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="border rounded w-full p-2">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="border rounded w-full p-2">
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
