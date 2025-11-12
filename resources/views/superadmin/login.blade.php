@extends('layouts.auth')

@section('title', 'Superadmin Login')

@section('content')
<div class="auth-header">
    <h1>Superadmin Login</h1>
    <p>Login to manage tenants and system</p>
</div>

<form method="POST" action="{{ route('superadmin.login.post') }}">
    @csrf
    
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-input" 
            value="{{ old('email') }}" 
            required 
            autofocus
        >
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-input" 
            required
        >
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <div class="checkbox-container">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>
@endsection
