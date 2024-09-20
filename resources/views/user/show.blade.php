@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Profile</h2>

    <!-- Display user information -->
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ $user->role }}</td> <!-- Adjust this if it's a relationship -->
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $user->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $user->updated_at }}</td>
        </tr>
    </table>

    <!-- <a href="{{ route('transactions.create') }}" class="btn btn-primary">Create Transaction</a> -->
</div>
@endsection
