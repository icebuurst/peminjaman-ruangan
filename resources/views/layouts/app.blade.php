<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peminjaman Ruangan')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --color-black: #191919;
            --color-grey: #808080;
            --color-light-grey: #b2b2b2;
            --color-white: #ffffff;
            --color-cyan: #1ceff4;
            --color-cyan-dark: #0dd1d6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--color-black) 0%, var(--color-grey) 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--color-cyan);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(28, 239, 244, 0.15);
            color: var(--color-cyan);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }
        
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%);
            color: white;
            border-bottom: 3px solid var(--color-cyan);
            padding: 1.25rem;
            font-weight: 600;
        }
        
        .btn-custom {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--color-cyan) 0%, var(--color-cyan-dark) 100%);
            border: none;
            color: var(--color-black);
            font-weight: 600;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(28, 239, 244, 0.4);
            background: linear-gradient(135deg, var(--color-cyan-dark) 0%, var(--color-cyan) 100%);
        }
        
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            animation: fadeInScale 0.3s ease;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-approved {
            background: rgba(28, 239, 244, 0.2);
            color: var(--color-cyan-dark);
            border: 1px solid var(--color-cyan);
        }
        
        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            animation: slideInRight 0.4s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .table {
            font-size: 0.9rem;
        }
        
        .table th {
            font-weight: 600;
            color: var(--color-grey);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            background: rgba(28, 239, 244, 0.05);
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(28, 239, 244, 0.05);
            transform: scale(1.01);
        }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }
        
        .breadcrumb-custom {
            background: transparent;
            margin: 0;
            padding: 0;
            font-size: 0.9rem;
        }
        
        .breadcrumb-custom .breadcrumb-item {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .breadcrumb-custom .breadcrumb-item.active {
            color: var(--color-cyan);
            font-weight: 600;
        }
        
        .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.5);
            content: "›";
        }
        
        .breadcrumb-custom a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-custom a:hover {
            color: var(--color-cyan);
        }
        
        .profile-dropdown {
            position: relative;
        }
        
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .profile-trigger:hover {
            background: rgba(28, 239, 244, 0.15);
            border-color: var(--color-cyan);
        }
        
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-cyan) 0%, var(--color-cyan-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--color-black);
            font-size: 1.1rem;
        }
        
        .profile-info {
            text-align: left;
        }
        
        .profile-name {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.2;
        }
        
        .profile-role {
            color: var(--color-cyan);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .profile-dropdown.show .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-header-custom {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%);
            color: white;
            border-bottom: 3px solid var(--color-cyan);
        }
        
        .dropdown-header-custom .profile-name {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .dropdown-header-custom .profile-email {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        
        .dropdown-item-custom {
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--color-black);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        .dropdown-item-custom:hover {
            background: rgba(28, 239, 244, 0.1);
            color: var(--color-cyan-dark);
        }
        
        .dropdown-item-custom i {
            width: 20px;
            text-align: center;
        }
        
        .dropdown-divider-custom {
            height: 1px;
            background: #e9ecef;
            margin: 0;
        }
        
        /* Notification Bell Styles */
        .notification-bell-container {
            position: relative;
        }
        
        .notification-trigger {
            position: relative;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .notification-trigger:hover {
            background: rgba(28, 239, 244, 0.15);
            border-color: var(--color-cyan);
        }
        
        .notification-trigger i {
            font-size: 1.25rem;
            color: white;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ff3b3b 0%, #ff6b6b 100%);
            color: white;
            border-radius: 50%;
            min-width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            border: 2px solid var(--color-black);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 380px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            max-height: 500px;
            display: flex;
            flex-direction: column;
        }
        
        .notification-bell-container.show .notification-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .notification-header {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, var(--color-black) 0%, var(--color-grey) 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--color-cyan);
        }
        
        .notification-header h6 {
            font-weight: 700;
            font-size: 1rem;
        }
        
        .notification-count {
            background: var(--color-cyan);
            color: var(--color-black);
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        
        .notification-list {
            overflow-y: auto;
            max-height: 350px;
            flex-grow: 1;
        }
        
        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            gap: 0.75rem;
            background: white;
        }
        
        .notification-item:hover {
            background: rgba(28, 239, 244, 0.05);
        }
        
        .notification-item.unread {
            background: rgba(28, 239, 244, 0.08);
            border-left: 3px solid var(--color-cyan);
        }
        
        .notification-item.unread:hover {
            background: rgba(28, 239, 244, 0.12);
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(28, 239, 244, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .notification-icon i {
            font-size: 1.25rem;
            color: var(--color-cyan-dark);
        }
        
        .notification-content {
            flex-grow: 1;
            min-width: 0;
        }
        
        .notification-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--color-black);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        
        .notification-message {
            color: var(--color-grey);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .notification-time {
            color: var(--color-light-grey);
            font-size: 0.75rem;
        }
        
        .notification-empty {
            padding: 3rem 2rem;
            text-align: center;
            color: var(--color-light-grey);
        }
        
        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .notification-empty p {
            margin: 0;
            font-size: 0.9rem;
        }
        
        .notification-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #f0f0f0;
            background: #f8f9fa;
        }
        
        .btn-mark-all-read {
            width: 100%;
            padding: 0.6rem;
            background: transparent;
            border: 2px solid var(--color-cyan);
            color: var(--color-cyan-dark);
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-mark-all-read:hover {
            background: var(--color-cyan);
            color: white;
        }
        
        .notification-divider {
            height: 1px;
            background: #e9ecef;
            margin: 0;
        }
        
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 2rem;
        }
        
        /* Loading Animation */
        .loading-spinner {
            border: 3px solid var(--color-light-grey);
            border-top: 3px solid var(--color-cyan);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--color-cyan);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-cyan-dark);
        }
        
        /* Modern Confirmation Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(25, 25, 25, 0.8);
            backdrop-filter: blur(8px);
            z-index: 10001;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal-overlay.show {
            display: flex;
            opacity: 1;
        }
        
        .modal-modern {
            background: white;
            border-radius: 20px;
            max-width: 450px;
            width: 90%;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            transform: scale(0.8) translateY(-20px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .modal-overlay.show .modal-modern {
            transform: scale(1) translateY(0);
        }
        
        .modal-icon-container {
            padding: 3rem 2rem 1.5rem;
            text-align: center;
        }
        
        .modal-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            animation: bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .modal-icon-large.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #ef4444;
        }
        
        .modal-icon-large.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
        }
        
        .modal-icon-large.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #10b981;
        }
        
        .modal-icon-large.info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #3b82f6;
        }
        
        .modal-content-modern {
            padding: 0 2rem 2rem;
            text-align: center;
        }
        
        .modal-title-modern {
            font-size: 1.5rem;
            font-weight: 700;
            color: #191919;
            margin-bottom: 0.75rem;
        }
        
        .modal-subtitle-modern {
            font-size: 1rem;
            color: #808080;
            margin-bottom: 1rem;
        }
        
        .modal-item-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .modal-item-info strong {
            color: #191919;
            font-size: 1.1rem;
        }
        
        .modal-warning-text {
            color: #ef4444;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn-modal {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-modal-cancel {
            background: #f1f3f5;
            color: #495057;
        }
        
        .btn-modal-cancel:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .btn-modal-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .btn-modal-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }
        
        .btn-modal-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .btn-modal-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-modal-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .btn-modal-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        }
        
        /* Modern Page Loader - Simple Version */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(25, 25, 25, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        
        .page-loader.loaded {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        
        .loader-content {
            text-align: center;
        }
        
        .spinner-simple {
            width: 50px;
            height: 50px;
            margin: 0 auto 1.5rem;
            border: 3px solid rgba(28, 239, 244, 0.2);
            border-top: 3px solid var(--color-cyan);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loader-text {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        /* Modern Confirmation Modal */
        .modal-modern-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(25, 25, 25, 0.75);
            backdrop-filter: blur(5px);
            z-index: 10001;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal-modern-backdrop.show {
            display: flex;
            opacity: 1;
        }
        
        .modal-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 90%;
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modal-modern-backdrop.show .modal-modern {
            transform: scale(1);
        }
        
        .modal-modern-icon {
            padding: 2rem 2rem 1rem;
            text-align: center;
        }
        
        .modal-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            animation: modalIconPop 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes modalIconPop {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .modal-icon-circle.danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #ef4444;
        }
        
        .modal-icon-circle.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #10b981;
        }
        
        .modal-icon-circle.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
        }
        
        .modal-modern-body {
            padding: 0 2rem 2rem;
            text-align: center;
        }
        
        .modal-modern-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #191919;
            margin-bottom: 1rem;
        }
        
        .modal-modern-message {
            color: #808080;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .modal-modern-detail {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
        }
        
        .modal-modern-detail h6 {
            font-weight: 700;
            color: #191919;
            margin-bottom: 0.5rem;
        }
        
        .modal-modern-detail p {
            color: #808080;
            font-size: 0.9rem;
            margin: 0.25rem 0;
        }
        
        .modal-modern-footer {
            padding: 0 2rem 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .modal-btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .modal-btn-cancel {
            background: #f1f3f5;
            color: #808080;
        }
        
        .modal-btn-cancel:hover {
            background: #e9ecef;
            color: #191919;
        }
        
        .modal-btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .modal-btn-danger:hover {
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
            transform: translateY(-2px);
        }
        
        .modal-btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .modal-btn-success:hover {
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }
        
        /* ========================================
           MOBILE RESPONSIVE STYLES
           ======================================== */
        
        /* Hamburger Menu Button */
        .hamburger-menu {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--color-cyan) 0%, var(--color-cyan-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            box-shadow: 0 4px 15px rgba(28, 239, 244, 0.4);
            transition: all 0.3s ease;
        }
        
        .hamburger-menu:active {
            transform: scale(0.95);
        }
        
        .hamburger-menu span {
            width: 24px;
            height: 3px;
            background: var(--color-black);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .hamburger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(7px, 7px);
        }
        
        .hamburger-menu.active span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }
        
        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
        
        /* Tablet & Mobile Breakpoints */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1000;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .top-navbar {
                left: 0;
                padding: 0 1rem;
            }
            
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
            
            .hamburger-menu {
                display: flex;
            }
            
            /* Hide profile info text on tablet */
            .profile-info {
                display: none !important;
            }
            
            /* Adjust notification dropdown width */
            .notification-dropdown {
                width: 320px;
                right: -10px;
            }
        }
        
        @media (max-width: 768px) {
            .top-navbar {
                height: 60px;
                padding: 0 0.75rem;
            }
            
            .main-content {
                margin-top: 60px;
                padding: 1rem;
            }
            
            /* Smaller cards on mobile */
            .card {
                margin-bottom: 1rem;
            }
            
            /* Stack charts vertically */
            .chart-container {
                margin-bottom: 1.5rem;
            }
            
            /* Responsive table */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
                white-space: nowrap;
            }
            
            /* Notification dropdown full width on mobile */
            .notification-dropdown {
                width: calc(100vw - 20px);
                right: 10px;
                left: auto;
                max-width: 380px;
            }
            
            /* Profile dropdown adjustment */
            .dropdown-menu-custom {
                right: 0;
                min-width: 200px;
            }
            
            /* Smaller profile avatar */
            .profile-avatar {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .notification-trigger {
                width: 40px;
                height: 40px;
            }
            
            .notification-trigger i {
                font-size: 1.1rem;
            }
            
            /* Breadcrumb responsive */
            .breadcrumb-custom {
                font-size: 0.8rem;
            }
            
            /* Modal responsive */
            .modal-modern {
                max-width: calc(100% - 2rem);
                margin: 1rem;
            }
            
            /* Quick actions grid */
            .quick-actions {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 0.75rem;
            }
            
            .quick-action-card {
                padding: 1rem;
            }
            
            .quick-action-card h6 {
                font-size: 0.85rem;
            }
            
            .quick-action-card p {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .hamburger-menu {
                width: 40px;
                height: 40px;
                top: 10px;
                left: 10px;
            }
            
            .hamburger-menu span {
                width: 20px;
                height: 2.5px;
            }
            
            .top-navbar {
                padding: 0 0.5rem 0 3.5rem;
            }
            
            .main-content {
                padding: 0.75rem;
            }
            
            /* Hide breadcrumb on very small screens */
            .breadcrumb-custom {
                display: none;
            }
            
            /* Stack notification items more compactly */
            .notification-item {
                padding: 0.75rem 1rem;
            }
            
            .notification-icon {
                width: 35px;
                height: 35px;
            }
            
            .notification-icon i {
                font-size: 1.1rem;
            }
            
            .notification-title {
                font-size: 0.85rem;
            }
            
            .notification-message {
                font-size: 0.8rem;
            }
            
            /* Compact cards */
            .card {
                border-radius: 10px;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Button sizes */
            .btn {
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
            }
            
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            /* Form elements */
            .form-control,
            .form-select {
                font-size: 0.9rem;
            }
            
            /* Modal on very small screens */
            .modal-modern {
                max-width: calc(100% - 1rem);
                margin: 0.5rem;
            }
            
            .modal-icon-circle {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .modal-modern-title h3 {
                font-size: 1.25rem;
            }
            
            /* Sidebar width on small screens */
            .sidebar {
                width: 260px;
            }
            
            /* Quick actions full width */
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        /* Landscape orientation on mobile */
        @media (max-width: 896px) and (orientation: landscape) {
            .sidebar {
                width: 220px;
            }
            
            .top-navbar {
                height: 50px;
            }
            
            .main-content {
                margin-top: 50px;
            }
        }
        
        /* Print styles */
        @media print {
            .sidebar,
            .top-navbar,
            .hamburger-menu,
            .notification-bell-container,
            .profile-dropdown,
            .btn,
            .modal-overlay {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                margin-top: 0 !important;
            }
        }
        
        /* ========================================
           GLOBAL MOBILE RESPONSIVE FOR ALL PAGES
           ======================================== */
        
        /* Tablet & Mobile Base (≤1024px) */
        @media (max-width: 1024px) {
            .container-fluid {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
            
            h1, h2 {
                font-size: 1.75rem !important;
            }
            
            h3, h4 {
                font-size: 1.25rem !important;
            }
            
            h5, h6 {
                font-size: 1rem !important;
            }
        }
        
        /* Mobile (≤768px) */
        @media (max-width: 768px) {
            /* Container & Spacing */
            .container-fluid {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Typography */
            h1, h2 {
                font-size: 1.5rem !important;
            }
            
            h3, h4 {
                font-size: 1.15rem !important;
            }
            
            h5, h6 {
                font-size: 0.95rem !important;
            }
            
            p, div, span {
                font-size: 0.9rem !important;
            }
            
            small {
                font-size: 0.75rem !important;
            }
            
            /* Cards & Panels */
            .card, .stat-card-modern, .room-card-modern, .chart-card-modern {
                margin-bottom: 1rem !important;
                border-radius: 12px !important;
            }
            
            .card-body, .chart-body-modern {
                padding: 1rem !important;
            }
            
            .card-header, .chart-header-modern {
                padding: 1rem !important;
            }
            
            /* Grid Columns - Auto Responsive */
            .row.g-4 {
                --bs-gutter-x: 1rem !important;
                --bs-gutter-y: 1rem !important;
            }
            
            .col-md-6, .col-lg-6, .col-xl-6,
            .col-md-4, .col-lg-4, .col-xl-4,
            .col-md-3, .col-lg-3, .col-xl-3 {
                width: 100% !important;
                flex: 0 0 100% !important;
            }
            
            /* Tables */
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            table {
                font-size: 0.85rem !important;
            }
            
            th, td {
                padding: 0.75rem !important;
            }
            
            /* Buttons */
            .btn {
                font-size: 0.85rem !important;
                padding: 0.625rem 1.25rem !important;
            }
            
            .btn-sm {
                font-size: 0.75rem !important;
                padding: 0.5rem 1rem !important;
            }
            
            /* Forms */
            .form-control, .form-select {
                font-size: 0.9rem !important;
                padding: 0.625rem 0.875rem !important;
            }
            
            .form-label {
                font-size: 0.85rem !important;
            }
            
            /* Badges */
            .badge {
                font-size: 0.7rem !important;
                padding: 0.35rem 0.75rem !important;
            }
            
            /* Modals */
            .modal-dialog {
                margin: 1rem !important;
            }
            
            .modal-content {
                border-radius: 12px !important;
            }
            
            /* Flex & D-Flex */
            .d-flex.justify-content-between {
                flex-direction: column !important;
                gap: 0.75rem !important;
            }
            
            .d-flex.gap-2, .d-flex.gap-3, .d-flex.gap-4 {
                gap: 0.75rem !important;
            }
            
            /* Images */
            img {
                max-width: 100% !important;
                height: auto !important;
            }
            
            /* Prevent Horizontal Scroll */
            body, html {
                overflow-x: hidden !important;
            }
            
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Small Mobile (≤480px) */
        @media (max-width: 480px) {
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            h1, h2 {
                font-size: 1.25rem !important;
            }
            
            h3, h4 {
                font-size: 1rem !important;
            }
            
            h5, h6 {
                font-size: 0.9rem !important;
            }
            
            .card, .stat-card-modern {
                padding: 0.75rem !important;
                border-radius: 10px !important;
            }
            
            .btn {
                font-size: 0.8rem !important;
                padding: 0.5rem 1rem !important;
            }
            
            table {
                font-size: 0.75rem !important;
            }
            
            th, td {
                padding: 0.5rem !important;
            }
        }
        
        /* Touch Optimization */
        @media (max-width: 768px) {
            /* Better tap targets */
            a, button, .btn, .nav-link {
                min-height: 44px !important;
                min-width: 44px !important;
                touch-action: manipulation !important;
            }
            
            /* Remove hover effects on touch */
            @media (hover: none) {
                *:hover {
                    transform: none !important;
                }
            }
            
            /* Smooth scrolling */
            * {
                -webkit-overflow-scrolling: touch !important;
            }
        }

        /* Extra fixes for very small screens (iPhone/Android phones ~<=420px)
           Ensure sidebar hidden, main content uses full width, and prevent horizontal scroll */
        @media (max-width: 420px) {
            /* Force sidebar hidden by default and ensure show class works */
            .sidebar {
                transform: translateX(-110%) !important;
                left: -320px !important;
                width: 260px !important;
            }

            .sidebar.show {
                transform: translateX(0) !important;
                left: 0 !important;
            }

            /* Main content full width and no left margin */
            .main-content, .container-fluid {
                margin-left: 0 !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .top-navbar {
                left: 0 !important;
                padding-left: 3.5rem !important; /* leave space for hamburger */
            }

            /* Prevent any horizontal overflow */
            html, body {
                overflow-x: hidden !important;
            }

            /* Make sure large cards/tables shrink/flex properly */
            .card, .stat-card-modern, .room-card-modern {
                max-width: 100% !important;
                overflow: hidden !important;
            }

            /* Move sidebar header content right to avoid collision with hamburger */
            .sidebar .mb-4 {
                padding-left: 3.75rem !important; /* leave space for hamburger */
            }

            /* Ensure hamburger sits above sidebar overlay */
            .hamburger-menu {
                z-index: 1201 !important;
            }

            .table-responsive {
                -webkit-overflow-scrolling: touch !important;
            }

            /* Reduce padding for very small screens */
            .btn, .form-control, .form-select {
                font-size: 0.85rem !important;
                padding: 0.5rem 0.75rem !important;
            }
        }
        
        /* Responsive popups, toasts, and notification dropdown tweaks */
        @media (max-width: 420px) {
            /* Make notification dropdown fixed, full-width and scrollable on small screens */
            .notification-dropdown {
                position: fixed !important;
                top: 70px !important; /* below top navbar/hamburger */
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                max-width: calc(100% - 20px) !important;
                height: calc(100vh - 120px) !important;
                border-radius: 12px !important;
                overflow: auto !important;
                z-index: 20000 !important;
                transform: none !important;
+                opacity: 1 !important;
+                visibility: visible !important;
+            }
+
            .notification-list {
                max-height: calc(100vh - 220px) !important;
+                overflow-y: auto !important;
+                -webkit-overflow-scrolling: touch !important;
+            }
+
            .notification-header, .notification-footer {
                position: sticky !important;
+                top: 0 !important;
+                z-index: 20001 !important;
+                background: white !important;
+            }
+
            .notification-trigger {
                z-index: 20002 !important;
+            }
+
            /* SweetAlert2 responsive overrides */
+            .swal2-popup {
+                width: 92% !important;
+                max-width: 420px !important;
+                box-sizing: border-box !important;
+                font-size: 14px !important;
+                padding: 1rem !important;
+                border-radius: 12px !important;
+            }
+
            .swal2-title { font-size: 1.05rem !important; }
+            .swal2-actions { display: flex !important; flex-direction: column !important; gap: 8px !important; }
+            .swal2-styled { min-height: 44px !important; padding: 10px 12px !important; border-radius: 10px !important; }
+
            /* Toastify / custom toasts on very small screens: full-width with margins */
+            .toastify, .toast.custom-toast, .toastify-wrapper {
+                min-width: calc(100vw - 32px) !important;
+                max-width: calc(100vw - 32px) !important;
+                margin: 12px !important;
+                left: 16px !important;
+                right: 16px !important;
+                box-sizing: border-box !important;
+                border-radius: 12px !important;
+            }
+
            /* Ensure built-in bootstrap toast container is full-width-ish */
+            .position-fixed.bottom-0.end-0 {
+                left: 0 !important;
+                right: 0 !important;
+                padding: 12px !important;
+            }
+        }
*** End Patch
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Hamburger Menu Button -->
    <button class="hamburger-menu" id="hamburgerMenu" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    
    <!-- Modern Confirmation Modal -->
    <div class="modal-modern-backdrop" id="confirmModal">
        <div class="modal-modern">
            <div class="modal-modern-icon">
                <div class="modal-icon-circle" id="modalIconCircle">
                    <i class="bi" id="modalIcon"></i>
                </div>
            </div>
            <div class="modal-modern-body">
                <h3 class="modal-modern-title" id="modalTitle"></h3>
                <p class="modal-modern-message" id="modalMessage"></p>
                <div class="modal-modern-detail" id="modalDetail" style="display: none;"></div>
            </div>
            <div class="modal-modern-footer">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmModal()">
                    <i class="bi bi-x-circle"></i>
                    <span>Batal</span>
                </button>
                <button type="button" class="modal-btn" id="modalConfirmBtn">
                    <i class="bi" id="modalConfirmIcon"></i>
                    <span id="modalConfirmText"></span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Simple Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="spinner-simple"></div>
            <div class="loader-text">Loading...</div>
        </div>
    </div>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-4">
            <div class="mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    Peminjaman Ruang
                </h4>
                <small class="opacity-75">{{ ucfirst(Auth::user()->role) }} Panel</small>
            </div>
            
            <hr class="opacity-25 my-3">
            
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door me-2"></i> Dashboard
                </a>
                
                <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                    <i class="bi bi-door-open me-2"></i> 
                    @if(Auth::user()->role === 'admin')
                        Kelola Ruangan
                    @else
                        Daftar Ruangan
                    @endif
                </a>
                
                <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.index') || request()->routeIs('bookings.create') || request()->routeIs('bookings.show') || request()->routeIs('bookings.edit') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> 
                    @if(Auth::user()->role === 'peminjam')
                        Peminjaman Saya
                    @else
                        Kelola Peminjaman
                    @endif
                </a>
                
                <a href="{{ route('jadwal-reguler.index') }}" class="nav-link {{ request()->routeIs('jadwal-reguler.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3 me-2"></i> Jadwal Reguler
                </a>
                
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-2"></i> Kelola User
                </a>
                @endif
                
                @if(Auth::user()->role !== 'peminjam')
                <a href="{{ route('bookings.laporan') }}" class="nav-link {{ request()->routeIs('bookings.laporan') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Laporan Peminjaman
                </a>
                @endif
                
                @if(Auth::user()->role === 'peminjam')
                <a href="{{ route('bookings.create') }}" class="nav-link {{ request()->routeIs('bookings.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle me-2"></i> Ajukan Peminjaman
                </a>
                @endif
            </nav>
        </div>
        
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <div class="notification-bell-container" id="notificationBell">
                    <div class="notification-trigger" onclick="toggleNotificationDropdown()">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6 class="mb-0">Notifikasi</h6>
                            <span class="notification-count" id="notificationCount">0</span>
                        </div>
                        <div class="notification-divider"></div>
                        <div class="notification-list" id="notificationList">
                            <!-- Notifications will be loaded here -->
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p>Tidak ada notifikasi</p>
                            </div>
                        </div>
                        <div class="notification-divider"></div>
                        <div class="notification-footer">
                            <button class="btn-mark-all-read" onclick="markAllNotificationsAsRead()">
                                <i class="bi bi-check-all"></i>
                                Tandai semua dibaca
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-trigger" onclick="toggleProfileDropdown()">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="profile-info d-none d-md-block">
                            <p class="profile-name">{{ Auth::user()->name }}</p>
                            <p class="profile-role">{{ Auth::user()->role }}</p>
                        </div>
                        <i class="bi bi-chevron-down text-white"></i>
                    </div>
                    
                    <div class="dropdown-menu-custom">
                        <div class="dropdown-header-custom">
                            <p class="profile-name">{{ Auth::user()->name }}</p>
                            <p class="profile-email mb-0">{{ Auth::user()->email }}</p>
                            <span class="profile-role">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="dropdown-divider-custom"></div>
                        <a href="{{ route('dashboard') }}" class="dropdown-item-custom">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                        @if(Auth::user()->role === 'peminjam')
                        <a href="{{ route('bookings.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-calendar-check"></i>
                            Peminjaman Saya
                        </a>
                        @endif
                        <div class="dropdown-divider-custom"></div>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item-custom text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Modern Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal" onclick="closeConfirmModal(event)">
        <div class="modal-modern" onclick="event.stopPropagation()">
            <div class="modal-icon-container">
                <div class="modal-icon-large" id="modalIcon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="modal-content-modern">
                <h2 class="modal-title-modern" id="modalTitle">Konfirmasi Aksi</h2>
                <p class="modal-subtitle-modern" id="modalSubtitle">Apakah Anda yakin?</p>
                <div class="modal-item-info" id="modalItemInfo" style="display: none;"></div>
                <p class="modal-warning-text" id="modalWarning" style="display: none;"></p>
                <div class="modal-actions">
                    <button class="btn-modal btn-modal-cancel" onclick="closeConfirmModal()">
                        <i class="bi bi-x-lg"></i>
                        <span id="cancelText">Batal</span>
                    </button>
                    <button class="btn-modal btn-modal-danger" id="confirmButton" onclick="executeConfirmAction()">
                        <i class="bi bi-check-lg"></i>
                        <span id="confirmText">Ya, Lanjutkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- GLightbox for Image Lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    
    <script>
        // Page Loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('loaded');
            }, 500);
        });
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
            
            // Close notification dropdown if open
            const notificationBell = document.getElementById('notificationBell');
            notificationBell.classList.remove('show');
        }
        
        // Notification Bell Toggle
        function toggleNotificationDropdown() {
            const bell = document.getElementById('notificationBell');
            bell.classList.toggle('show');
            
            // Close profile dropdown if open
            const profileDropdown = document.getElementById('profileDropdown');
            profileDropdown.classList.remove('show');
            
            // Load notifications when opening
            if (bell.classList.contains('show')) {
                loadNotifications();
            }
        }
        
        // Load Notifications via AJAX
        function loadNotifications() {
            fetch('{{ route('notifications.index') }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const notificationCount = document.getElementById('notificationCount');
                    
                    if (data.length === 0) {
                        notificationList.innerHTML = `
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p>Tidak ada notifikasi</p>
                            </div>
                        `;
                        notificationCount.textContent = '0';
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                                 onclick="handleNotificationClick(${notification.id}, '${notification.link || '#'}')">
                                <div class="notification-icon">
                                    <i class="bi ${notification.icon || 'bi-info-circle'}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">${notification.title}</div>
                                    <div class="notification-message">${notification.message}</div>
                                    <div class="notification-time">${formatNotificationTime(notification.created_at)}</div>
                                </div>
                            </div>
                        `).join('');
                        
                        notificationCount.textContent = data.length;
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }
        
        // Update Notification Badge
        function updateNotificationBadge() {
            fetch('{{ route('notifications.unreadCount') }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    const count = data.count || 0;
                    
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error updating badge:', error);
                });
        }
        
        // Handle Notification Click
        function handleNotificationClick(notificationId, link) {
            // Mark as read
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(() => {
                // Update badge and list
                updateNotificationBadge();
                loadNotifications();
                
                // Navigate to link if provided
                if (link && link !== '#') {
                    window.location.href = link;
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }
        
        // Mark All Notifications as Read
        function markAllNotificationsAsRead() {
            fetch('{{ route('notifications.markAllAsRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(() => {
                updateNotificationBadge();
                loadNotifications();
                showToast('Semua notifikasi telah ditandai dibaca', 'success');
            })
            .catch(error => {
                console.error('Error marking all as read:', error);
                showToast('Gagal menandai notifikasi', 'error');
            });
        }
        
        // Format Notification Time
        function formatNotificationTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds
            
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
            if (diff < 604800) return `${Math.floor(diff / 86400)} hari lalu`;
            
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined 
            });
        }
        
        // Auto-refresh notifications every 30 seconds
        setInterval(() => {
            updateNotificationBadge();
        }, 30000);
        
        // Initial badge update
        updateNotificationBadge();
        
        // ========================================
        // MOBILE SIDEBAR TOGGLE FUNCTIONS
        // ========================================
        
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerMenu');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            hamburger.classList.toggle('active');
            
            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
        
        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerMenu');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            hamburger.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close sidebar when clicking nav link on mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
            });
        });
        
        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                closeSidebar();
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const trigger = dropdown.querySelector('.profile-trigger');
            const notificationBell = document.getElementById('notificationBell');
            
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
            
            if (!notificationBell.contains(event.target)) {
                notificationBell.classList.remove('show');
            }
        });
        
        // Toast Notification Function - Improved
        function showToast(message, type = 'success') {
            const config = {
                success: {
                    background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    icon: '✓'
                },
                error: {
                    background: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    icon: '✕'
                },
                info: {
                    background: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    icon: 'ℹ'
                },
                warning: {
                    background: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    icon: '⚠'
                }
            };
            
            const settings = config[type] || config.success;
            
            Toastify({
                text: `${settings.icon} ${message}`,
                duration: 4000,
                gravity: "bottom",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: settings.background,
                    borderRadius: "12px",
                    fontWeight: "600",
                    fontSize: "0.95rem",
                    padding: "1rem 1.5rem",
                    boxShadow: "0 8px 25px rgba(0,0,0,0.25)"
                },
            }).showToast();
        }
        
        // Show toast for Laravel session messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        // Modern Confirmation Modal Functions
        let confirmModalCallback = null;
        
        function showConfirmModal(options) {
            const modal = document.getElementById('confirmModal');
            const modalIcon = document.getElementById('modalIcon');
            const title = document.getElementById('modalTitle');
            const subtitle = document.getElementById('modalSubtitle');
            const itemInfo = document.getElementById('modalItemInfo');
            const warning = document.getElementById('modalWarning');
            const confirmBtn = document.getElementById('confirmButton');
            const confirmText = document.getElementById('confirmText');
            const cancelText = document.getElementById('cancelText');
            
            // Set icon and style based on type
            const types = {
                danger: {
                    icon: 'bi-exclamation-triangle-fill',
                    iconClass: 'danger',
                    btnClass: 'btn-modal-danger',
                    confirmText: 'Ya, Hapus',
                    confirmIcon: 'bi-trash-fill'
                },
                success: {
                    icon: 'bi-check-circle-fill',
                    iconClass: 'success',
                    btnClass: 'btn-modal-success',
                    confirmText: 'Ya, Setuju',
                    confirmIcon: 'bi-check-lg'
                },
                warning: {
                    icon: 'bi-exclamation-circle-fill',
                    iconClass: 'warning',
                    btnClass: 'btn-modal-warning',
                    confirmText: 'Ya, Lanjutkan',
                    confirmIcon: 'bi-check-lg'
                },
                info: {
                    icon: 'bi-info-circle-fill',
                    iconClass: 'info',
                    btnClass: 'btn-modal-success',
                    confirmText: 'OK, Mengerti',
                    confirmIcon: 'bi-check-lg'
                }
            };
            
            const type = types[options.type || 'danger'];
            
            // Reset and set icon
            modalIcon.className = 'modal-icon-large ' + type.iconClass;
            modalIcon.querySelector('i').className = 'bi ' + type.icon;
            
            // Reset and set button
            confirmBtn.className = 'btn-modal ' + type.btnClass;
            confirmBtn.querySelector('i').className = 'bi ' + type.confirmIcon;
            
            // Set content
            title.textContent = options.title || 'Konfirmasi Aksi';
            subtitle.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan?';
            confirmText.textContent = options.confirmText || type.confirmText;
            cancelText.textContent = options.cancelText || 'Batal';
            
            // Set item info if provided
            if (options.itemInfo) {
                itemInfo.innerHTML = options.itemInfo;
                itemInfo.style.display = 'block';
            } else {
                itemInfo.style.display = 'none';
            }
            
            // Set warning if provided
            if (options.warning) {
                warning.textContent = options.warning;
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
            
            // Store callback
            confirmModalCallback = options.onConfirm;
            
            // Show modal
            modal.classList.add('show');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
        
        function closeConfirmModal(event) {
            if (event && event.target !== event.currentTarget) {
                return;
            }
            
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('show');
            confirmModalCallback = null;
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
        
        function executeConfirmAction() {
            if (confirmModalCallback) {
                confirmModalCallback();
            }
            closeConfirmModal();
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('confirmModal');
                if (modal.classList.contains('show')) {
                    closeConfirmModal();
                }
            }
        });
        
        // Initialize GLightbox for images
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
        
        // Add loading state to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    
                    // Re-enable after 3 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 3000);
                }
            });
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Fix for modal backdrop issues
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure all modals have proper backdrop behavior
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                // When modal is completely hidden
                modal.addEventListener('hidden.bs.modal', function (event) {
                    console.log('Modal hidden event fired');
                    
                    // Remove ALL backdrops (in case there are multiple)
                    setTimeout(() => {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        console.log('Found backdrops:', backdrops.length);
                        backdrops.forEach(backdrop => {
                            backdrop.remove();
                        });
                        
                        // Restore body state
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 100);
                });

                // Force close modal on ESC key
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const modalInstance = bootstrap.Modal.getInstance(this);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                });
            });

            // Global ESC key handler
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close all open modals
                    const openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(modal => {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                }
            });

            // Click backdrop to close
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal-backdrop')) {
                    const openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(modal => {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                }
            });
        });
        
        // ============================================
        // GLOBAL DELETE CONFIRMATION FUNCTION
        // ============================================
        function confirmDelete(itemName, deleteUrl) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-modern',
                    title: 'swal2-title-modern',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                padding: '2rem',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    
    <style>
        /* SweetAlert2 Modern Styling */
        .swal2-modern {
            border-radius: 16px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
        }
        
        .swal2-title-modern {
            color: #191919 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }
        
        .swal2-popup .swal2-actions {
            gap: 12px !important;
            margin-top: 1.5rem !important;
        }
        
        .swal2-popup .btn {
            padding: 0.75rem 2rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            border: none !important;
            min-width: 120px !important;
        }
        
        .swal2-popup .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
        }
        
        .swal2-popup .btn-danger:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
        }
        
        .swal2-popup .btn-secondary {
            background: #e5e7eb !important;
            color: #374151 !important;
        }
        
        .swal2-popup .btn-secondary:hover {
            background: #d1d5db !important;
            transform: translateY(-2px) !important;
        }
        
        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
    </style>
    
    @yield('scripts')
</body>
</html>
