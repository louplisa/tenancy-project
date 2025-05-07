<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenants') }}
        </h2>
    </x-slot>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('tenants.create') }}" class="btn btn-primary">Ajouter un tenant</a>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Domaine</th>
            <th class="text-end pe-5">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tenants as $tenant)
            <tr>
                <td>{{ $tenant->id }}</td>
                <td>{{ $tenant->domains->pluck('domain')->first() }}</td>
                <td>
                    <div class="d-flex gap-2 w-100 justify-content-end">
                        <form action="{{ route('tenants.destroy', $tenant) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger">Supprimer</button>
                        </form>

                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-app-layout>
