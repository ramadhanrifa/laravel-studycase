@extends('layouts.template')

@section('content')

@if(Session::get('deleted'))
<div class="alert alert-warning">{{ Session::get('deleted') }}</div>
@endif

@if(Session::get('success'))
<div class="alert alert-success">{{ Session::get('success') }}</div>
@endif

<div class="d-flex justify-content-end"><a href="{{ route('user.create') }}" class="btn btn-secondary me-3 " aria-current="page">Buat akun</a>
</div>


<table class="table table-striped table-bordered table-hover mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-center">Aksi</th>
        </tr> 
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($user as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item['name'] }}</td>
            <td>{{ $item['email'] }}</td>
            <td>{{ $item['role'] }}</td>
            <td class="d-flex justify-content-center">
    <a href="{{ route('user.edit', $item['id']) }}" class="btn btn-primary">Edit</a>
    <button class="btn btn-danger" onclick="openDeleteConfirmation({{ $item['id'] }})">Hapus</button>
    <form id="delete-form-{{ $item['id'] }}" action="{{ route('user.delete', $item['id']) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</td>

        </tr>
        @endforeach
    </tbody>

</table>

@endsection

@push('script')

<script>
       function openDeleteConfirmation(userId) {
        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'), {
            backdrop: 'static',
            keyboard: false
        });

        modal.show();

        document.getElementById('confirmDeleteButton').onclick = function() {
            modal.hide();
            document.getElementById('delete-form-' + userId).submit();
        };
    }

</script>

@endpush