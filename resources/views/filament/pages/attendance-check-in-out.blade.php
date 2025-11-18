@extends('filament::page')

@section('content')
    <div class="filament-page" style="max-width: 640px; margin: auto;">
        <h2 class="filament-heading">Attendance - Check in / Check out</h2>

        <div style="display: flex; gap: 1rem;">
            <form method="POST" action="{{ route('hr.attendance.check-in') }}">
                @csrf
                <button class="filament-button filament-button-primary" type="submit">Check In</button>
            </form>

            <form method="POST" action="{{ route('hr.attendance.check-out') }}">
                @csrf
                <button class="filament-button filament-button-danger" type="submit">Check Out</button>
            </form>
        </div>
    </div>
@endsection
