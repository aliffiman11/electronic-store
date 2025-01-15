@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<style>
    /* Responsive Outer Container */
    .outer-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: #ffffff;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .header-section h2 {
        font-size: 2rem;
        font-weight: bold;
    }

    .header-section p {
        font-size: 1rem;
        margin: 0;
    }

    /* Card Styling */
    .profile-card {
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Grid */
    @media (max-width: 768px) {
        .profile-card {
            margin-bottom: 1.5rem;
        }
    }

    /* Form Styles */
    .profile-form label {
        font-weight: bold;
        margin-bottom: 0.5rem;
        display: block;
    }

    .profile-form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
    }

    .profile-form button {
        background-color: #6a11cb;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .profile-form button:hover {
        background-color: #4d0ca0;
    }
</style>

<!-- Outer Container -->
<div class="outer-container">
    <!-- Header Section -->
    <div class="header-section">
        <h2>Profile</h2>
        <p>Manage your profile, update your password, and review your account settings.</p>
    </div>

    <!-- Responsive Grid -->
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <!-- Update Profile Information -->
        <div class="col">
            <div class="profile-card">
                <h4 class="fw-bold mb-3">Update Profile Information</h4>
                <div class="profile-form">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col">
            <div class="profile-card">
                <h4 class="fw-bold mb-3">Change Password</h4>
                <div class="profile-form">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete User Account -->
        <div class="col">
            <div class="profile-card">
                <h4 class="fw-bold mb-3">Delete Account</h4>
                <div class="profile-form">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
