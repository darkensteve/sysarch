<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Get admin information from session
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Static data for students
$students = [
    [
        'id' => 'STU1001',
        'name' => 'John Smith',
        'email' => 'johnsmith@example.com',
        'year_level' => 'First Year',
        'course' => 'BS Computer Science',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-06-15',
        'sit_in_count' => 15
    ],
    [
        'id' => 'STU1002',
        'name' => 'Maria Johnson',
        'email' => 'maria.johnson@example.com',
        'year_level' => 'Second Year',
        'course' => 'BS Information Technology',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-05-22',
        'sit_in_count' => 12
    ],
    [
        'id' => 'STU1003',
        'name' => 'Robert Williams',
        'email' => 'robert.williams@example.com',
        'year_level' => 'Third Year',
        'course' => 'BS Information Systems',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-07-03',
        'sit_in_count' => 8
    ],
    [
        'id' => 'STU1004',
        'name' => 'Sarah Brown',
        'email' => 'sarah.brown@example.com',
        'year_level' => 'Fourth Year',
        'course' => 'BS Computer Engineering',
        'status' => 'Inactive',
        'status_class' => 'bg-red-100 text-red-800',
        'registered_date' => '2023-04-10',
        'sit_in_count' => 0
    ],
    [
        'id' => 'STU1005',
        'name' => 'Michael Davis',
        'email' => 'michael.davis@example.com',
        'year_level' => 'First Year',
        'course' => 'BS Computer Science',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-08-18',
        'sit_in_count' => 5
    ],
    [
        'id' => 'STU1006',
        'name' => 'Jennifer Wilson',
        'email' => 'jennifer.wilson@example.com',
        'year_level' => 'Second Year',
        'course' => 'BS Information Technology',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-05-11',
        'sit_in_count' => 7
    ],
    [
        'id' => 'STU1007',
        'name' => 'David Martinez',
        'email' => 'david.martinez@example.com',
        'year_level' => 'Third Year',
        'course' => 'BS Computer Engineering',
        'status' => 'Suspended',
        'status_class' => 'bg-yellow-100 text-yellow-800',
        'registered_date' => '2023-06-30',
        'sit_in_count' => 3
    ],
    [
        'id' => 'STU1008',
        'name' => 'Lisa Anderson',
        'email' => 'lisa.anderson@example.com',
        'year_level' => 'First Year',
        'course' => 'BS Information Systems',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-08-05',
        'sit_in_count' => 9
    ],
    [
        'id' => 'STU1009',
        'name' => 'James Taylor',
        'email' => 'james.taylor@example.com',
        'year_level' => 'Fourth Year',
        'course' => 'BS Computer Science',
        'status' => 'Inactive',
        'status_class' => 'bg-red-100 text-red-800',
        'registered_date' => '2023-03-27',
        'sit_in_count' => 2
    ],
    [
        'id' => 'STU1010',
        'name' => 'Mary Thomas',
        'email' => 'mary.thomas@example.com',
        'year_level' => 'Second Year',
        'course' => 'BS Information Technology',
        'status' => 'Active',
        'status_class' => 'bg-green-100 text-green-800',
        'registered_date' => '2023-07-14',
        'sit_in_count' => 11
    ]
];

// Courses list for filter
$courses = [
    'BS Computer Science',
    'BS Information Technology',
    'BS Information Systems',
    'BS Computer Engineering'
];

// Year levels for filter
$year_levels = [
    'First Year',
    'Second Year',
    'Third Year',
    'Fourth Year'
];

// Status options for filter
$status_options = [
    'Active',
    'Inactive',
    'Suspended'
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | Sit-in Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in-up': 'fadeInUp 0.5s ease forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .card-zoom:hover {
            transform: translateY(-5px);
        }
        
        .gradient-bg {
            background: linear-gradient(120deg, #0ea5e9, #6366f1);
        }
        
        .pagination-item {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
        .pagination-item.active {
            background-color: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
        }
        .pagination-item.active:hover {
            background-color: #0284c7;
            color: white;
        }
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .pagination-item:hover {
            border-color: #0ea5e9;
            color: #0ea5e9;
        }
        
        .pagination-item.active {
            background-color: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
        }
        .pagination-item.active:hover {
            background-color: #0284c7;
            color: white;
        }
        
        /* Animation for row highlight */
        @keyframes highlightRow {
            0% { background-color: rgba(79, 70, 229, 0.1); }
            100% { background-color: transparent; }
        }
        
        .highlight-row {
            animation: highlightRow 2s ease-out;
        }
        
        /* Additional utility classes for dark mode */
        .dark\:bg-slate-700 {
            background-color: #334155;
        }
        .dark\:bg-slate-800 {
            background-color: #1e293b;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Notification container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 w-80"></div>
    
    <!-- Student Info Modal -->
    <div id="student-info-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p=0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-3xl p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-user text-primary-500 mr-2"></i>
                        <span id="modal-student-name">Student Information</span>
                    </h3>
                    <button id="close-student-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div id="student-info-content" class="space-y-6">
                    <!-- Content will be dynamically populated -->
                    <div class="animate-pulse space-y-4">
                        <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Student Modal -->
    <div id="edit-student-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-edit text-primary-500 mr-2"></i>
                        Edit Student
                    </h3>
                    <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form id="edit-student-form" class="space-y-4">
                    <input type="hidden" id="edit-student-id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="edit-name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div>
                            <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="edit-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div>
                            <label for="edit-course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                            <select id="edit-course" name="course" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <?php foreach($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="edit-year" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                            <select id="edit-year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <?php foreach($year_levels as $year): ?>
                                <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="edit-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit-status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <?php foreach($status_options as $status): ?>
                                <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="edit-date" class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                            <input type="date" id="edit-date" name="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" id="cancel-edit" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 rounded-lg shadow-sm hover:shadow transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="mb-4">
                    <h3 class="text-xl font-medium text-gray-900">Delete Student Record</h3>
                    <p class="mt-2 text-gray-600">Are you sure you want to delete this student? This action cannot be undone.</p>
                </div>
                
                <div class="p-4 mb-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-3"></i>
                        <p class="text-sm text-gray-700">All related data including sit-in records will also be deleted.</p>
                    </div>
                </div>
                
                <div id="delete-student-info" class="mb-4 text-sm text-gray-600">
                    <!-- Will be populated with student info -->
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button id="cancel-delete" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all">Cancel</button>
                    <button id="confirm-delete" class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white rounded-lg transition-all shadow-sm hover:shadow flex items-center">
                        <i class="fas fa-trash-alt mr-2"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Register New Student Modal -->
    <div id="register-student-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-user-plus text-primary-500 mr-2"></i>
                        Register New Student
                    </h3>
                    <button id="close-register-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form id="register-student-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="student-id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                            <input type="text" id="student-id" name="student_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="e.g., STU1011">
                        </div>
                        
                        <div>
                            <label for="student-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="student-name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Enter student's full name">
                        </div>
                        
                        <div>
                            <label for="student-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="student-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Enter student's email">
                        </div>
                        
                        <div>
                            <label for="student-course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                            <select id="student-course" name="course" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="" selected disabled>Select course...</option>
                                <?php foreach($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="student-year" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                            <select id="student-year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="" selected disabled>Select year level...</option>
                                <?php foreach($year_levels as $year): ?>
                                <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" id="cancel-register" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-lg shadow-sm hover:shadow transition-all">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex h-screen">
        <!-- Sidebar with gradient background -->
        <nav class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 text-white p-4 transition-all duration-300 shadow-xl" id="sidebar">
            <div class="mb-8">
                <h3 class="text-xl font-bold flex items-center space-x-2">
                    <span class="gradient-bg p-2 rounded-lg">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </span>
                    <span>Monitoring System</span>
                </h3>
            </div>
            <div class="flex items-center space-x-3 mb-8 pb-5 border-b border-gray-700/50">
                <div class="relative">
                    <img src="assets/img/admin-avatar.png" alt="Admin" class="w-10 h-10 rounded-full border-2 border-primary-400">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-gray-800"></div>
                </div>
                <div>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($admin_name); ?></p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
            <ul class="space-y-2">
                <!-- Home (Dashboard) -->
                <li>
                    <a href="dashboard.php" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-home w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3 font-medium">Home</span>
                    </a>
                </li>
                <!-- Search -->
                <li>
                    <a href="#" id="sidebar-search-button" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-search w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3">Search</span>
                    </a>
                </li>
                <!-- Students -->
                <li>
                    <a href="admin_student.php" class="flex items-center p-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20">
                        <i class="fas fa-users w-5 h-5 text-primary-300"></i>
                        <span class="ml-3 font-medium">Students</span>
                    </a>
                </li>
                <!-- Sit-in -->
                <li>
                    <a href="sit-in.php" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-chair w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3">Sit-in</span>
                    </a>
                </li>
                <li class="pt-5 mt-5 border-t border-gray-700/50">
                    <a href="logout.php" class="flex items-center p-2.5 rounded-lg text-red-300 hover:bg-red-500/20 hover:text-red-200 transition-all">
                        <i class="fas fa-sign-out-alt w-5 h-5"></i>
                        <span class="ml-3">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="ml-4 text-xl font-semibold text-gray-800">Students Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="relative p-2 rounded-full hover:bg-gray-100 transition-all">
                        <i class="fas fa-bell text-gray-600 text-lg"></i>
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </a>
                    <a href="logout.php" class="flex items-center bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg transition-all shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </header>
            <!-- Content Area -->
            <div class="p-6 space-y-6">
                <!-- Stats and Search Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Total Students -->
                    <div class="bg-white rounded-xl shadow-md p-5 flex items-center justify-between card-zoom transition-all">
                        <div>
                            <p class="text-sm text-gray-500">Total Students</p>
                            <h2 class="text-2xl font-bold text-gray-800"><?php echo count($students); ?></h2>
                            <p class="text-xs text-green-500 flex items-center mt-1">
                                <i class="fas fa-arrow-up mr-1"></i> 12% increase
                            </p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    
                    <!-- Active Students -->
                    <div class="bg-white rounded-xl shadow-md p-5 flex items-center justify-between card-zoom transition-all">
                        <div>
                            <p class="text-sm text-gray-500">Active Students</p>
                            <h2 class="text-2xl font-bold text-gray-800">
                                <?php echo count(array_filter($students, function($student) {
                                    return $student['status'] === 'Active';
                                })); ?>
                            </h2>
                            <p class="text-xs text-green-500 flex items-center mt-1">
                                <i class="fas fa-arrow-up mr-1"></i> 8% increase
                            </p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="bg-white rounded-xl shadow-md p-4 flex items-center">
                        <div class="relative flex-grow">
                            <input id="search-input" type="text" placeholder="Search students..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30 focus:outline-none transition">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <button id="register-student-btn" class="ml-3 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-user-
                            plus mr-2"></i>
                                                        Register
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Filters -->
                                            <div class="bg-white rounded-xl shadow-md p-5">
                                                <div class="flex flex-wrap gap-4">
                                                    <!-- Course Filter -->
                                                    <div class="w-full md:w-auto">
                                                        <label for="course-filter" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                                        <select id="course-filter" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                            <option value="">All Courses</option>
                                                            <?php foreach($courses as $course): ?>
                                                            <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <!-- Year Level Filter -->
                                                    <div class="w-full md:w-auto">
                                                        <label for="year-filter" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                                        <select id="year-filter" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                            <option value="">All Year Levels</option>
                                                            <?php foreach($year_levels as $year): ?>
                                                            <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <!-- Status Filter -->
                                                    <div class="w-full md:w-auto">
                                                        <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                        <select id="status-filter" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                            <option value="">All Status</option>
                                                            <?php foreach($status_options as $status): ?>
                                                            <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <!-- Date Range Filter -->
                                                    <div class="w-full md:w-auto flex-grow">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                                                        <div class="flex items-center space-x-2">
                                                            <input type="date" id="date-from" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                            <span class="text-gray-500">to</span>
                                                            <input type="date" id="date-to" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Clear Filters Button -->
                                                    <div class="w-full md:w-auto flex items-end">
                                                        <button id="clear-filters" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all">
                                                            <i class="fas fa-sync-alt mr-1"></i>
                                                            Clear Filters
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Students Table -->
                                            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                                <div class="overflow-x-auto">
                                                    <table id="students-table" class="w-full">
                                                        <thead class="bg-gray-50 border-b border-gray-200">
                                                            <tr>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered Date</th>
                                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            <?php foreach($students as $student): ?>
                                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($student['id']); ?></td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                                    <div class="flex items-center">
                                                                        <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                                                            <i class="fas fa-user text-gray-500"></i>
                                                                        </div>
                                                                        <?php echo htmlspecialchars($student['name']); ?>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($student['email']); ?></td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($student['course']); ?></td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($student['year_level']); ?></td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $student['status_class']; ?>">
                                                                        <?php echo htmlspecialchars($student['status']); ?>
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($student['registered_date']); ?></td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                                                    <button class="view-student text-blue-600 hover:text-blue-800" data-id="<?php echo htmlspecialchars($student['id']); ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button class="edit-student text-amber-500 hover:text-amber-700" data-id="<?php echo htmlspecialchars($student['id']); ?>">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="delete-student text-red-500 hover:text-red-700" data-id="<?php echo htmlspecialchars($student['id']); ?>">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </main>
                                </div>
                                
                                <script>
                                    // For demonstration purposes - showing modals and notifications
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Sidebar toggle
                                        const sidebarToggle = document.getElementById('sidebar-toggle');
                                        const sidebar = document.getElementById('sidebar');
                                        
                                        sidebarToggle.addEventListener('click', function() {
                                            sidebar.classList.toggle('hidden');
                                        });
                                        
                                        // View student details
                                        document.querySelectorAll('.view-student').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const studentId = this.getAttribute('data-id');
                                                const studentModal = document.getElementById('student-info-modal');
                                                const studentName = document.getElementById('modal-student-name');
                                                const studentContent = document.getElementById('student-info-content');
                                                
                                                // Find the student data from the array
                                                const student = findStudentById(studentId);
                                                
                                                if (student) {
                                                    studentName.textContent = student.name;
                                                    
                                                    // Create the content
                                                    studentContent.innerHTML = `
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Student ID</h4>
                                                                    <p class="text-base">${student.id}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Full Name</h4>
                                                                    <p class="text-base">${student.name}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Email</h4>
                                                                    <p class="text-base">${student.email}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                                                    <p class="text-base">
                                                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${student.status_class}">${student.status}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Course</h4>
                                                                    <p class="text-base">${student.course}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Year Level</h4>
                                                                    <p class="text-base">${student.year_level}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Registration Date</h4>
                                                                    <p class="text-base">${student.registered_date}</p>
                                                                </div>
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-500">Sit-in Count</h4>
                                                                    <p class="text-base">${student.sit_in_count} sessions</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-6 pt-6 border-t border-gray-200">
                                                            <h4 class="text-sm font-medium text-gray-500 mb-3">Student Activity</h4>
                                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-chart-line text-primary-500 mr-2"></i>
                                                                    <span>Recent activity chart will be displayed here</span>
                                                                </div>
                                                                <button class="text-primary-600 hover:text-primary-800 text-sm">View Full History</button>
                                                            </div>
                                                        </div>
                                                    `;
                                                }
                                                
                                                studentModal.classList.remove('hidden');
                                            });
                                        });
                                        
                                        // Close student info modal
                                        document.getElementById('close-student-modal').addEventListener('click', function() {
                                            document.getElementById('student-info-modal').classList.add('hidden');
                                        });
                                        
                                        // Edit student
                                        document.querySelectorAll('.edit-student').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const studentId = this.getAttribute('data-id');
                                                const editModal = document.getElementById('edit-student-modal');
                                                
                                                // Find the student data
                                                const student = findStudentById(studentId);
                                                
                                                if (student) {
                                                    // Populate form with student data
                                                    document.getElementById('edit-student-id').value = student.id;
                                                    document.getElementById('edit-name').value = student.name;
                                                    document.getElementById('edit-email').value = student.email;
                                                    document.getElementById('edit-course').value = student.course;
                                                    document.getElementById('edit-year').value = student.year_level;
                                                    document.getElementById('edit-status').value = student.status;
                                                    document.getElementById('edit-date').value = student.registered_date;
                                                    
                                                    // Show modal
                                                    editModal.classList.remove('hidden');
                                                }
                                            });
                                        });
                                        
                                        // Close edit modal
                                        document.getElementById('close-edit-modal').addEventListener('click', function() {
                                            document.getElementById('edit-student-modal').classList.add('hidden');
                                        });
                                        document.getElementById('cancel-edit').addEventListener('click', function() {
                                            document.getElementById('edit-student-modal').classList.add('hidden');
                                        });
                                        
                                        // Submit edit form
                                        document.getElementById('edit-student-form').addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            const studentId = document.getElementById('edit-student-id').value;
                                            
                                            // In a real app, you would send this data to the server
                                            // For now, just close the modal and show a success message
                                            document.getElementById('edit-student-modal').classList.add('hidden');
                                            
                                            // Show success notification
                                            showNotification('Student information updated successfully!', 'success');
                                        });
                                        
                                        // Delete student
                                        document.querySelectorAll('.delete-student').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const studentId = this.getAttribute('data-id');
                                                const deleteModal = document.getElementById('delete-modal');
                                                const deleteInfo = document.getElementById('delete-student-info');
                                                
                                                // Find the student data
                                                const student = findStudentById(studentId);
                                                
                                                if (student) {
                                                    // Show student info in modal
                                                    deleteInfo.innerHTML = `
                                                        <p><strong>ID:</strong> ${student.id}</p>
                                                        <p><strong>Name:</strong> ${student.name}</p>
                                                        <p><strong>Email:</strong> ${student.email}</p>
                                                    `;
                                                    
                                                    // Store student ID for deletion
                                                    document.getElementById('confirm-delete').setAttribute('data-id', student.id);
                                                    
                                                    // Show modal
                                                    deleteModal.classList.remove('hidden');
                                                }
                                            });
                                        });
                                        
                                        // Close delete modal
                                        document.getElementById('cancel-delete').addEventListener('click', function() {
                                            document.getElementById('delete-modal').classList.add('hidden');
                                        });
                                        
                                        // Confirm delete
                                        document.getElementById('confirm-delete').addEventListener('click', function() {
                                            const studentId = this.getAttribute('data-id');
                                            
                                            // In a real app, you would send a delete request to the server
                                            // For now, just close the modal and show a success message
                                            document.getElementById('delete-modal').classList.add('hidden');
                                            
                                            // Show success notification
                                            showNotification('Student has been deleted successfully.', 'success');
                                        });
                                        
                                        // Register new student
                                        document.getElementById('register-student-btn').addEventListener('click', function() {
                                            document.getElementById('register-student-modal').classList.remove('hidden');
                                        });
                                        
                                        // Close register modal
                                        document.getElementById('close-register-modal').addEventListener('click', function() {
                                            document.getElementById('register-student-modal').classList.add('hidden');
                                        });
                                        document.getElementById('cancel-register').addEventListener('click', function() {
                                            document.getElementById('register-student-modal').classList.add('hidden');
                                        });
                                        
                                        // Submit register form
                                        document.getElementById('register-student-form').addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            
                                            // In a real app, you would send this data to the server
                                            // For now, just close the modal and show a success message
                                            document.getElementById('register-student-modal').classList.add('hidden');
                                            
                                            // Show success notification
                                            showNotification('New student registered successfully!', 'success');
                                        });
                                        
                                        // Filter functionality
                                        document.getElementById('clear-filters').addEventListener('click', function() {
                                            document.getElementById('course-filter').value = '';
                                            document.getElementById('year-filter').value = '';
                                            document.getElementById('status-filter').value = '';
                                            document.getElementById('date-from').value = '';
                                            document.getElementById('date-to').value = '';
                                            document.getElementById('search-input').value = '';
                                            
                                            // Reset table to show all students
                                            // In a real app, you would reload the data
                                            
                                            showNotification('All filters have been cleared.', 'info');
                                        });
                                        
                                        // Helper function to find student by ID
                                        function findStudentById(id) {
                                            // This would typically be a server request
                                            // For now, find it in our static array
                                            return students.find(student => student.id === id);
                                        }
                                        
                                        // Show notification function
                                        function showNotification(message, type = 'info') {
                                            const container = document.getElementById('notification-container');
                                            const colors = {
                                                success: 'bg-green-100 text-green-800 border-green-200',
                                                error: 'bg-red-100 text-red-800 border-red-200',
                                                info: 'bg-blue-100 text-blue-800 border-blue-200',
                                                warning: 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                            };
                                            const icons = {
                                                success: '<i class="fas fa-check-circle"></i>',
                                                error: '<i class="fas fa-exclamation-circle"></i>',
                                                info: '<i class="fas fa-info-circle"></i>',
                                                warning: '<i class="fas fa-exclamation-triangle"></i>'
                                            };
                                            
                                            const notification = document.createElement('div');
                                            notification.className = `mb-3 p-4 rounded-lg shadow-md border ${colors[type]} animate-fade-in-up flex items-center justify-between`;
                                            notification.innerHTML = `
                                                <div class="flex items-center">
                                                    <span class="mr-2">${icons[type]}</span>
                                                    <span>${message}</span>
                                                </div>
                                                <button class="text-gray-500 hover:text-gray-700 focus:outline-none dismiss-notification">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            `;
                                            
                                            container.appendChild(notification);
                                            
                                            // Add event listener to dismiss button
                                            notification.querySelector('.dismiss-notification').addEventListener('click', function() {
                                                notification.remove();
                                            });
                                            
                                            // Automatically remove after 5 seconds
                                            setTimeout(() => {
                                                notification.style.opacity = '0';
                                                setTimeout(() => {
                                                    notification.remove();
                                                }, 300);
                                            }, 5000);
                                        }
                                        
                                        // Global variable for students data
                                        const students = <?php echo json_encode($students); ?>;
                                    });
                                </script>
                            </body>
                        </html>           