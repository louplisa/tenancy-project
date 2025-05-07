<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Création d'un tenant") }}
        </h2>
    </x-slot>
    <form class="vstack gap-2"
          action="{{ route('tenants.store') }}"
          method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="row">
            <div class="col vstack gap-2" style="flex: 100">
                <div class="row">
                    <div class="col row">
                        <div class="m-3">
                            <label for="tenant_id">Tenant</label>
                            <input type="text" name="tenant_id" class="@error('tenant_id') is-invalid @enderror form-control">
                            @error('tenant_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="m-3">
                            <label for="domain">Domaine</label>
                            <input type="text" name="domain" disabled value="localhost" class="form-control">
                        </div>
                        <div class="m-3">
                            <button class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
