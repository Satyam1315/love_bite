<x-app-layout pageTitle="Profile">
    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white border border-orange-100 shadow sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white border border-orange-100 shadow sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-addresses-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white border border-orange-100 shadow sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white border border-orange-100 shadow sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
