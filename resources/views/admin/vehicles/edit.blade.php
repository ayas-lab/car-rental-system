<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier : ' . $vehicle->brand . ' ' . $vehicle->model) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    @include('admin.vehicles._form', ['vehicle' => $vehicle])

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                           class="px-4 py-2 border rounded-md text-sm">Annuler</a>
                        <button class="px-6 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>