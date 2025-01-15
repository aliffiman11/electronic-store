<x-filament-panels::page>

<style>

    /* Custom Button */
.custom-button {
    background-color: #4F46E5; /* Indigo 500 */
    color: #FFFFFF;           /* White text */
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 5px;
    border: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.custom-button:hover {
    background-color: #4338CA; /* Indigo 600 */
    transform: scale(1.05);    /* Slightly enlarge on hover */
}

/* Dark Mode Label */
label {
    font-size: 0.875rem; /* Match text-sm size */
    color: #4A5568;      /* Gray 700 for light mode */
}

.dark label {
    color: #A0AEC0;      /* Gray 300 for dark mode */
}

</style>

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Name
                </label>
                <input type="text" id="name" wire:model.defer="name" 
                    class="form-input w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') 
                <span class="text-red-600 dark:text-red-400">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>
                <input type="email" id="email" wire:model.defer="email" 
                    class="form-input w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                @error('email') 
                <span class="text-red-600 dark:text-red-400">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    New Password (optional)
                </label>
                <input type="password" id="password" wire:model.defer="password" 
                    class="form-input w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500">
                @error('password') 
                <span class="text-red-600 dark:text-red-400">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        <!-- Add margin-top for space between password and button -->
        <div class="mt-6">
            <button type="submit" class="custom-button">
                Save Changes
            </button>
        </div>
    </form>
</x-filament-panels::page>
