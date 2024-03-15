<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{route('affiche')}}" method="POST" role="form">
                        @csrf
                        <div>
                            <x-input-label for="type" :value="__('Select that election you want to express yourself.')" />
                                <x-select-option :choix="'Type_Election'" :slt="$tab" :title="'Select the election that you want to express yourself.'"/>
                        </div>
                        <x-primary-button class="mt-2">{{ __('Confirm My Choice') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
