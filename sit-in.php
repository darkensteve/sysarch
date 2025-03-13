<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Get admin information from session
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Static data for sit-in records (in a real app, this would come from database)
$sit_in_records = [
    [
        'id' => 1,
        'student_id' => 'STU1001',
        'student_name' => 'John Smith',
        'purpose' => 'C Programming',
        'laboratory' => 'Computer Laboratory 1',
        'date' => '2023-10-15',
        'time_in' => '09:15:00',
        'time_out' => '11:30:00',
        'status' => 'Completed',
        'year_level' => 'Second Year'
    ],
    [
        'id' => 2,
        'student_id' => 'STU1002',
        'student_name' => 'Maria Johnson',
        'purpose' => 'Java Programming',
        'laboratory' => 'Computer Laboratory 2',
        'date' => date('Y-m-d'), // Today
        'time_in' => '09:45:00',
        'time_out' => null,
        'status' => 'Active',
        'year_level' => 'Third Year'
    ],
    [
        'id' => 3,
        'student_id' => 'STU1003',
        'student_name' => 'Robert Williams',
        'purpose' => 'PHP Programming',
        'laboratory' => 'Computer Laboratory 3',
        'date' => date('Y-m-d'), // Today
        'time_in' => '10:30:00',
        'time_out' => null,
        'status' => 'Active',
        'year_level' => 'Fourth Year'
    ],
    [
        'id' => 4,
        'student_id' => 'STU1004',
        'student_name' => 'Sarah Brown',
        'purpose' => 'C# Programming',
        'laboratory' => 'Computer Laboratory 1',
        'date' => '2023-10-14',
        'time_in' => '13:15:00',
        'time_out' => '15:45:00',
        'status' => 'Completed',
        'year_level' => 'First Year'
    ],
    [
        'id' => 5,
        'student_id' => 'STU1005',
        'student_name' => 'Michael Davis',
        'purpose' => 'ASP.net Programming',
        'laboratory' => 'Computer Laboratory 4',
        'date' => date('Y-m-d'), // Today
        'time_in' => '08:30:00',
        'time_out' => null,
        'status' => 'Active',
        'year_level' => 'Second Year'
    ],
    [
        'id' => 6,
        'student_id' => 'STU1006',
        'student_name' => 'Jennifer Wilson',
        'purpose' => 'Java Programming',
        'laboratory' => 'Computer Laboratory 2',
        'date' => '2023-10-14',
        'time_in' => '14:00:00',
        'time_out' => '16:30:00',
        'status' => 'Completed',
        'year_level' => 'Third Year'
    ],
    [
        'id' => 7,
        'student_id' => 'STU1007',
        'student_name' => 'David Martinez',
        'purpose' => 'C Programming',
        'laboratory' => 'Computer Laboratory 1',
        'date' => '2023-10-13',
        'time_in' => '10:00:00',
        'time_out' => '12:15:00',
        'status' => 'Completed',
        'year_level' => 'First Year'
    ],
    [
        'id' => 8,
        'student_id' => 'STU1008',
        'student_name' => 'Lisa Taylor',
        'purpose' => 'PHP Programming',
        'laboratory' => 'Computer Laboratory 3',
        'date' => date('Y-m-d'), // Today
        'time_in' => '11:00:00',
        'time_out' => null,
        'status' => 'Active',
        'year_level' => 'Fourth Year'
    ]
];

// Static data for available labs
$laboratories = [
    'Computer Laboratory 1',
    'Computer Laboratory 2',
    'Computer Laboratory 3',
    'Computer Laboratory 4'
];

// Static data for programming purposes
$purposes = [
    'C Programming',
    'Java Programming',
    'PHP Programming',
    'C# Programming',
    'ASP.net Programming'
];

// Get the active and completed counts
$active_count = count(array_filter($sit_in_records, function($record) {
    return $record['status'] === 'Active';
}));

$completed_count = count(array_filter($sit_in_records, function($record) {
    return $record['status'] === 'Completed';
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Management | Sit-in Monitoring System</title>
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
        
        /* Animation delay classes */
        .card-delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }
        .card-delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }
        .card-delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }
        
        /* Animate fade-out class for deletions */
        .animate-fade-out {
            animation: fadeOut 0.3s ease forwards;
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        
        /* Table row hover effect */
        .table-row-hover:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Notification container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 w-80"></div>
    
    <!-- Search Modal -->
    <div id="search-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-search text-primary-500 mr-2"></i>
                        Search Students
                    </h3>
                    <button id="close-search-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form id="search-form" class="space-y-4">
                    <div class="relative">
                        <input type="text" id="search-query" name="query" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                               placeholder="Search for students by name or ID...">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="submit" class="px-4 py-2 text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 rounded-lg shadow-sm hover:shadow transition-all">
                            <i class="fas fa-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Student Sit-In Assignment Modal -->
    <div id="student-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-user-check text-primary-500 mr-2"></i>
                        Student Sit-In Assignment
                    </h3>
                    <button id="close-student-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <!-- Student Info Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Student ID</label>
                                <p id="student-id" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Student Name</label>
                                <p id="student-name" class="font-medium text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sit-In Form -->
                    <form id="sit-in-form" class="space-y-4">
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                            <select id="purpose" name="purpose" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="" selected disabled>Select purpose...</option>
                                <?php foreach($purposes as $purpose): ?>
                                <option value="<?php echo htmlspecialchars($purpose); ?>"><?php echo htmlspecialchars($purpose); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-xs text-red-500 mt-1 hidden" id="purpose-error">Please select a purpose</div>
                        </div>
                        
                        <div>
                            <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory Room</label>
                            <select id="laboratory" name="laboratory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="" selected disabled>Select laboratory...</option>
                                <?php foreach($laboratories as $lab): ?>
                                <option value="<?php echo htmlspecialchars($lab); ?>"><?php echo htmlspecialchars($lab); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-xs text-red-500 mt-1 hidden" id="lab-error">Please select a laboratory</div>
                        </div>
                        
                        <div class="pt-4 flex justify-end space-x-3">
                            <button type="button" id="back-to-search" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all">
                                Back to Search
                            </button>
                            <button type="submit" class="px-4 py-2 text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-lg shadow-sm hover:shadow transition-all">
                                <i class="fas fa-check mr-2"></i>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Record Detail Modal -->
    <div id="record-detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-800 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-bottom bg-white rounded-xl shadow-xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium text-gray-900">
                        <i class="fas fa-clipboard-list text-primary-500 mr-2"></i>
                        Sit-In Record Details
                    </h3>
                    <button id="close-record-detail-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div id="record-detail-content" class="space-y-6">
                    <!-- Content will be dynamically populated -->
                </div>
                
                <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" id="close-detail-btn" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all">
                        Close
                    </button>
                    <button id="checkout-btn" class="hidden px-4 py-2 text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-lg shadow-sm hover:shadow transition-all">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Check-out Student
                    </button>
                </div>
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
                        <i class="fas fa-home w-5 h-5 text-gray-400"></i>
                        <span class="ml-3 font-medium">Home</span>
                    </a>
                </li>
                <!-- Search -->
                <li>
                    <a href="" id="sidebar-search-button" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-search w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3">Search</span>
                    </a>
                </li>
                <!-- Students -->
                <li>
                    <a href="admin_student.php" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-users w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3">Students</span>
                    </a>
                </li>
                <!-- Sit-in -->
                <li>
                    <a href="sit-in.php" class="flex items-center p-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20">
                        <i class="fas fa-chair w-5 h-5 text-primary-300"></i>
                        <span class="ml-3 font-medium">Sit-in</span>
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
                    <button id="search-button" class="p-2 ml-4 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none transition-all flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        <span>Search Students</span>
                    </button>
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
            
            <!-- Content -->
            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                <!-- Header with date - Teal accent -->
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between p-5 bg-teal-50/50 border-l-4 border-teal-500 rounded-lg">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">Sit-in Management</h1>
                        <p class="text-gray-500">
                            Monitor and manage student sit-in activities for all computer laboratories.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-white rounded-lg shadow-sm px-4 py-2 flex items-center">
                        <i class="far fa-calendar-alt text-teal-500 mr-2"></i>
                        <span class="text-gray-600 font-medium"><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
                
                <!-- Stats Cards - Purple accent -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 p-5 bg-purple-50/50 border-l-4 border-purple-500 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 col-span-1 sm:col-span-2 lg:col-span-4 mb-2">Sit-in Statistics</h2>
                    
                    <!-- Total Sit-ins -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-primary-500"></div>
                        <div class="rounded-full bg-primary-50 p-3 mr-4">
                            <i class="fas fa-clipboard-list text-primary-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Records</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo count($sit_in_records); ?></p>
                        </div>
                    </div>
                    
                    <!-- Active Sit-ins -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-green-500"></div>
                        <div class="rounded-full bg-green-50 p-3 mr-4">
                            <i class="fas fa-chair text-green-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Active Sit-ins</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $active_count; ?></p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: <?php echo ($active_count/count($sit_in_records)*100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completed Sit-ins -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-blue-500"></div>
                        <div class="rounded-full bg-blue-50 p-3 mr-4">
                            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Completed</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $completed_count; ?></p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: <?php echo ($completed_count/count($sit_in_records)*100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add New Sit-in -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex flex-col justify-center items-center transition-all border border-gray-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-amber-500"></div>
                        <button id="add-new-btn" class="w-full h-full flex flex-col items-center justify-center cursor-pointer transition-all hover:bg-amber-50/50 rounded-lg">
                            <div class="rounded-full bg-amber-50 p-3 mb-3">
                                <i class="fas fa-plus text-amber-500 text-xl"></i>
                            </div>
                            <h3 class="text-amber-600 font-medium">Add New Sit-in</h3>
                        </button>
                    </div>
                </div>
                
                <!-- Sit-in Records Table - Green accent -->
                <div class="mb-8 p-5 bg-green-50/50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-2 sm:mb-0">Sit-in Records</h2>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Filter Dropdown -->
                            <div class="relative">
                                <button id="filter-dropdown-button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 flex items-center text-gray-700 text-sm">
                                    <i class="fas fa-filter mr-2 text-gray-500"></i>
                                    <span>Filter</span>
                                    <i class="fas fa-chevron-down ml-1 text-gray-500 text-xs"></i>
                                </button>
                                
                                <!-- Filter Dropdown Menu -->
                                <div id="filter-dropdown-menu" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10">
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <select id="filter-status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                <option value="">All</option>
                                                <option value="Active">Active</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="filter-laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                                            <select id="filter-laboratory" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                <option value="">All</option>
                                                <?php foreach($laboratories as $lab): ?>
                                                <option value="<?php echo htmlspecialchars($lab); ?>"><?php echo htmlspecialchars($lab); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="filter-purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                            <select id="filter-purpose" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                                <option value="">All</option>
                                                <?php foreach($purposes as $purpose): ?>
                                                <option value="<?php echo htmlspecialchars($purpose); ?>"><?php echo htmlspecialchars($purpose); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="pt-2 flex justify-between">
                                            <button id="reset-filters" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800">
                                                Reset Filters
                                            </button>
                                            <button id="apply-filters" class="px-3 py-1.5 text-xs font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-md shadow-sm">
                                                Apply Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Search Box for Table -->
                            <div class="relative">
                                <input type="text" id="table-search" placeholder="Search records..." 
                                       class="pl-10 pr-4 py-2 w-full sm:w-64 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Records Table -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student Info
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Purpose
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Laboratory
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Time
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="records-table-body">
                                    <?php foreach($sit_in_records as $record): ?>
                                        <tr class="table-row-hover" data-id="<?php echo $record['id']; ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($record['student_name']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            ID: <?php echo htmlspecialchars($record['student_id']); ?> | <?php echo htmlspecialchars($record['year_level']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($record['purpose']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($record['laboratory']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo date('M j, Y', strtotime($record['date'])); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo date('h:i A', strtotime($record['time_in'])); ?> - 
                                                    <?php echo $record['time_out'] ? date('h:i A', strtotime($record['time_out'])) : 'Present'; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if($record['status'] === 'Active'): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Completed
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button class="view-record text-primary-600 hover:text-primary-900 mr-3" data-id="<?php echo $record['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if($record['status'] === 'Active'): ?>
                                                    <button class="checkout-record text-green-600 hover:text-green-900 mr-3" data-id="<?php echo $record['id']; ?>">
                                                        <i class="fas fa-sign-out-alt"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="delete-record text-red-600 hover:text-red-900" data-id="<?php echo $record['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium"><?php echo count($sit_in_records); ?></span> of <span class="font-medium"><?php echo count($sit_in_records); ?></span> records
                                </div>
                                <div class="flex space-x-1">
                                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        Previous
                                    </button>
                                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Labs Section - Amber accent -->
                <div class="mb-8 p-5 bg-amber-50/50 border-l-4 border-amber-500 rounded-lg">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-700">Computer Laboratories</h2>
                        <button class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg transition-all shadow-sm hover:shadow text-sm">
                            <i class="fas fa-cog mr-2"></i>
                            Manage Labs
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php foreach($laboratories as $index => $lab): ?>
                            <?php
                            // Count active students in this lab
                            $active_students = array_filter($sit_in_records, function($record) use ($lab) {
                                return $record['laboratory'] === $lab && $record['status'] === 'Active';
                            });
                            $active_count = count($active_students);
                            
                            // Define capacity and utilization percentage (for demo)
                            $capacity = 20;
                            $utilization = ($active_count / $capacity) * 100;
                            
                            // Lab status
                            $status = 'Available';
                            $status_color = 'text-green-600';
                            if($utilization >= 100) {
                                $status = 'Full';
                                $status_color = 'text-red-600';
                            } elseif($utilization >= 80) {
                                $status = 'Almost Full';
                                $status_color = 'text-amber-600';
                            }
                            ?>
                            
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-100 overflow-hidden transition-all">
                                <div class="p-5 border-b">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($lab); ?></h3>
                                        <span class="<?php echo $status_color; ?> text-sm font-medium"><?php echo $status; ?></span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-500">Utilization</span>
                                        <span class="text-sm font-medium"><?php echo $active_count; ?>/<?php echo $capacity; ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="
                                            <?php echo $utilization >= 80 ? 'bg-red-500' : 'bg-green-500'; ?> 
                                            h-2 rounded-full" 
                                            style="width: <?php echo $utilization; ?>%">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button class="view-lab w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-all text-sm" data-lab="<?php echo htmlspecialchars($lab); ?>">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Toggle sidebar
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('main');
            
            if(sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                mainContent.classList.add('ml-20');
                
                // Hide text elements
                const textElements = sidebar.querySelectorAll('span, h3, p');
                textElements.forEach(el => {
                    el.classList.add('hidden');
                });
                
                // Center icons
                const icons = sidebar.querySelectorAll('i');
                icons.forEach(icon => {
                    icon.parentElement.classList.add('justify-center');
                });
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContent.classList.remove('ml-20');
                
                // Show text elements
                const textElements = sidebar.querySelectorAll('span, h3, p');
                textElements.forEach(el => {
                    el.classList.remove('hidden');
                });
                
                // Reset icon alignment
                const icons = sidebar.querySelectorAll('i');
                icons.forEach(icon => {
                    icon.parentElement.classList.remove('justify-center');
                });
            }
        });
        
        // Initialize functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Filter dropdown toggle
            const filterDropdownButton = document.getElementById('filter-dropdown-button');
            const filterDropdownMenu = document.getElementById('filter-dropdown-menu');
            
            filterDropdownButton.addEventListener('click', function() {
                filterDropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterDropdownButton.contains(event.target) && !filterDropdownMenu.contains(event.target)) {
                    filterDropdownMenu.classList.add('hidden');
                }
            });
            
            // Reset filters
            document.getElementById('reset-filters').addEventListener('click', function() {
                document.getElementById('filter-status').value = '';
                document.getElementById('filter-laboratory').value = '';
                document.getElementById('filter-purpose').value = '';
                
                // Reset table
                filterTable('', '', '');
                filterDropdownMenu.classList.add('hidden');
            });
            
            // Apply filters
            document.getElementById('apply-filters').addEventListener('click', function() {
                const status = document.getElementById('filter-status').value;
                const laboratory = document.getElementById('filter-laboratory').value;
                const purpose = document.getElementById('filter-purpose').value;
                
                filterTable(status, laboratory, purpose);
                filterDropdownMenu.classList.add('hidden');
            });
            
            // Filter table function
            function filterTable(status, laboratory, purpose) {
                const tableRows = document.querySelectorAll('#records-table-body tr');
                let visibleCount = 0;
                
                tableRows.forEach(row => {
                    const rowStatus = row.querySelector('td:nth-child(5) span').textContent.trim();
                    const rowLaboratory = row.querySelector('td:nth-child(3) div').textContent.trim();
                    const rowPurpose = row.querySelector('td:nth-child(2) div').textContent.trim();
                    
                    const statusMatch = !status || rowStatus === status;
                    const laboratoryMatch = !laboratory || rowLaboratory === laboratory;
                    const purposeMatch = !purpose || rowPurpose === purpose;
                    
                    if (statusMatch && laboratoryMatch && purposeMatch) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                
                // Update showing text
                document.querySelector('.text-gray-700').innerHTML = `
                    Showing <span class="font-medium">${visibleCount > 0 ? '1' : '0'}</span> to <span class="font-medium">${visibleCount}</span> of <span class="font-medium">${tableRows.length}</span> records
                `;
            }
            
            // Table search
            document.getElementById('table-search').addEventListener('input', function(e) {
                const searchValue = e.target.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#records-table-body tr');
                let visibleCount = 0;
                
                tableRows.forEach(row => {
                    const studentName = row.querySelector('td:nth-child(1) .text-gray-900').textContent.toLowerCase();
                    const studentId = row.querySelector('td:nth-child(1) .text-gray-500').textContent.toLowerCase();
                    const purpose = row.querySelector('td:nth-child(2) div').textContent.toLowerCase();
                    const laboratory = row.querySelector('td:nth-child(3) div').textContent.toLowerCase();
                    
                    if (studentName.includes(searchValue) || 
                        studentId.includes(searchValue) || 
                        purpose.includes(searchValue) || 
                        laboratory.includes(searchValue)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                
                // Update showing text
                document.querySelector('.text-gray-700').innerHTML = `
                    Showing <span class="font-medium">${visibleCount > 0 ? '1' : '0'}</span> to <span class="font-medium">${visibleCount}</span> of <span class="font-medium">${tableRows.length}</span> records
                `;
            });
            
            // Search Modal Functionality
            const searchButton = document.getElementById('search-button');
            const sidebarSearchButton = document.getElementById('sidebar-search-button');
            const searchModal = document.getElementById('search-modal');
            const closeSearchModal = document.getElementById('close-search-modal');
            const searchForm = document.getElementById('search-form');
            const studentModal = document.getElementById('student-modal');
            const closeStudentModal = document.getElementById('close-student-modal');
            const backToSearchBtn = document.getElementById('back-to-search');
            const sitInForm = document.getElementById('sit-in-form');
            
            // Function to open search modal
            function openSearchModal() {
                searchModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                setTimeout(() => {
                    document.getElementById('search-query').focus();
                }, 100);
            }
            
            // Show modal when header search button is clicked
            if (searchButton) {
                searchButton.addEventListener('click', openSearchModal);
            }
            
            // Show modal when sidebar search button is clicked
            if (sidebarSearchButton) {
                sidebarSearchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSearchModal();
                });
            }
            
            // Close search modal
            function closeSearchModalFunc() {
                searchModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            // Close student modal
            function closeStudentModalFunc() {
                studentModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                
                // Reset form fields
                document.getElementById('student-id').textContent = '-';
                document.getElementById('student-name').textContent = '-';
                document.getElementById('purpose').value = '';
                document.getElementById('laboratory').value = '';
                document.getElementById('purpose-error').classList.add('hidden');
                document.getElementById('lab-error').classList.add('hidden');
            }
            
            // Close buttons event listeners
            closeSearchModal.addEventListener('click', closeSearchModalFunc);
            closeStudentModal.addEventListener('click', closeStudentModalFunc);
            
            // Close when clicking outside the modal
            searchModal.addEventListener('click', function(e) {
                if (e.target === searchModal) {
                    closeSearchModalFunc();
                }
            });
            
            studentModal.addEventListener('click', function(e) {
                if (e.target === studentModal) {
                    closeStudentModalFunc();
                }
            });
            
            // Close when pressing ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!searchModal.classList.contains('hidden')) {
                        closeSearchModalFunc();
                    }
                    if (!studentModal.classList.contains('hidden')) {
                        closeStudentModalFunc();
                    }
                    if (!document.getElementById('record-detail-modal').classList.contains('hidden')) {
                        closeRecordDetailModal();
                    }
                }
            });
            
            // Back to search button
            backToSearchBtn.addEventListener('click', function() {
                closeStudentModalFunc();
                openSearchModal();
            });
            
            // Handle search form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const query = document.getElementById('search-query').value.trim();
                
                if (!query) {
                    showNotification("Please enter a search query", 'warning');
                    return;
                }
                
                // Simulate a search result (in a real app, this would be an AJAX request)
                // For demo purposes, we'll just show a student based on the query
                let studentName = '';
                let studentId = '';
                
                // Simple demo logic to simulate finding a student
                if (query.toLowerCase().includes('john') || query.toLowerCase().includes('stu1001')) {
                    studentName = 'John Smith';
                    studentId = 'STU1001';
                } else if (query.toLowerCase().includes('maria') || query.toLowerCase().includes('stu1002')) {
                    studentName = 'Maria Johnson';
                    studentId = 'STU1002';
                } else if (query.toLowerCase().includes('robert') || query.toLowerCase().includes('stu1003')) {
                    studentName = 'Robert Williams';
                    studentId = 'STU1003';
                } else if (query.toLowerCase().includes('sarah') || query.toLowerCase().includes('stu1004')) {
                    studentName = 'Sarah Brown';
                    studentId = 'STU1004';
                } else if (query.toLowerCase().includes('michael') || query.toLowerCase().includes('stu1005')) {
                    studentName = 'Michael Davis';
                    studentId = 'STU1005';
                } else {
                    // No student found
                    showNotification(`No student found matching "${query}"`, 'error');
                    return;
                }
                
                // Close search modal
                closeSearchModalFunc();
                
                // Update student details
                document.getElementById('student-id').textContent = studentId;
                document.getElementById('student-name').textContent = studentName;
                
                // Show student modal
                studentModal.classList.remove('hidden');
            });
            
            // Handle sit-in form submission
            sitInForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const studentId = document.getElementById('student-id').textContent;
                const studentName = document.getElementById('student-name').textContent;
                const purpose = document.getElementById('purpose').value;
                const laboratory = document.getElementById('laboratory').value;
                
                // Validate form fields
                let hasError = false;
                if (!purpose) {
                    document.getElementById('purpose-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('purpose-error').classList.add('hidden');
                }
                
                if (!laboratory) {
                    document.getElementById('lab-error').classList.remove('hidden');
                    hasError = true;
                } else {
                    document.getElementById('lab-error').classList.add('hidden');
                }
                
                if (hasError) return;
                
                // In a real app, you would send this data to the server
                // For demo purposes, just show a success message
                closeStudentModalFunc();
                showNotification(`Successfully assigned ${studentName} (${studentId}) to ${laboratory} for ${purpose}`, 'success');
                
                // Add a new row to the table (for demonstration)
                const tableBody = document.getElementById('records-table-body');
                const newRow = document.createElement('tr');
                const newId = Date.now(); // Generate a unique ID
                newRow.className = 'table-row-hover';
                newRow.setAttribute('data-id', newId);
                
                const now = new Date();
                const formattedDate = now.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
                const formattedTime = now.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true});
                
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    ${studentName}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ID: ${studentId} | Unknown Year
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${purpose}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${laboratory}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            ${formattedDate}
                        </div>
                        <div class="text-sm text-gray-500">
                            ${formattedTime} - Present
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="view-record text-primary-600 hover:text-primary-900 mr-3" data-id="${newId}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="checkout-record text-green-600 hover:text-green-900 mr-3" data-id="${newId}">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                        <button class="delete-record text-red-600 hover:text-red-900" data-id="${newId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                // Add at the beginning of the table
                if (tableBody.firstChild) {
                    tableBody.insertBefore(newRow, tableBody.firstChild);
                } else {
                    tableBody.appendChild(newRow);
                }
                
                // Highlight the new row
                newRow.classList.add('bg-green-50');
                setTimeout(() => {
                    newRow.classList.remove('bg-green-50');
                }, 3000);
                
                // Increment active count
                const activeCountElement = document.querySelector('.text-3xl:nth-of-type(2)');
                if (activeCountElement) {
                    const currentCount = parseInt(activeCountElement.textContent);
                    activeCountElement.textContent = currentCount + 1;
                }
                
                // Update showing count
                const showingText = document.querySelector('.text-gray-700');
                if (showingText) {
                    const allRows = tableBody.querySelectorAll('tr').length;
                    showingText.innerHTML = `
                        Showing <span class="font-medium">1</span> to <span class="font-medium">${allRows}</span> of <span class="font-medium">${allRows}</span> records
                    `;
                }
                
                // Add event listeners to the new buttons
                addEventListenersToRecordButtons(newRow);
            });
            // Record Detail Modal
            const recordDetailModal = document.getElementById('record-detail-modal');
            const closeRecordDetailModal = document.getElementById('close-record-detail-modal');
            const closeDetailBtn = document.getElementById('close-detail-btn');
            const recordDetailContent = document.getElementById('record-detail-content');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            // Close record detail modal
            function closeRecordDetailModal() {
                recordDetailModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            closeRecordDetailModal.addEventListener('click', closeRecordDetailModal);
            closeDetailBtn.addEventListener('click', closeRecordDetailModal);
            
            recordDetailModal.addEventListener('click', function(e) {
                if (e.target === recordDetailModal) {
                    closeRecordDetailModal();
                }
            });
            
            // Add event listeners to view, checkout, and delete buttons
            function addEventListenersToRecordButtons(row) {
                const viewButton = row.querySelector('.view-record');
                const checkoutButton = row.querySelector('.checkout-record');
                const deleteButton = row.querySelector('.delete-record');
                
                if (viewButton) {
                    viewButton.addEventListener('click', function() {
                        const recordId = this.getAttribute('data-id');
                        viewRecordDetails(recordId);
                    });
                }
                
                if (checkoutButton) {
                    checkoutButton.addEventListener('click', function() {
                        const recordId = this.getAttribute('data-id');
                        checkoutStudent(recordId);
                    });
                }
                
                if (deleteButton) {
                    deleteButton.addEventListener('click', function() {
                        const recordId = this.getAttribute('data-id');
                        deleteRecord(recordId);
                    });
                }
            }
            
            // Add event listeners to existing buttons
            document.querySelectorAll('#records-table-body tr').forEach(row => {
                addEventListenersToRecordButtons(row);
            });
            
            // View record details
            function viewRecordDetails(recordId) {
                // Find the record from the table
                const recordRow = document.querySelector(`#records-table-body tr[data-id="${recordId}"]`);
                if (!recordRow) return;
                
                const studentName = recordRow.querySelector('td:nth-child(1) .text-gray-900').textContent.trim();
                const studentIdAndYear = recordRow.querySelector('td:nth-child(1) .text-gray-500').textContent.trim();
                const purpose = recordRow.querySelector('td:nth-child(2) div').textContent.trim();
                const laboratory = recordRow.querySelector('td:nth-child(3) div').textContent.trim();
                const date = recordRow.querySelector('td:nth-child(4) .text-gray-900').textContent.trim();
                const time = recordRow.querySelector('td:nth-child(4) .text-gray-500').textContent.trim();
                const status = recordRow.querySelector('td:nth-child(5) span').textContent.trim();
                
                // Split student ID and year level
                const idMatch = studentIdAndYear.match(/ID: (.*?) \| (.*)/);
                const studentId = idMatch ? idMatch[1] : 'Unknown';
                const yearLevel = idMatch ? idMatch[2] : 'Unknown';
                
                // Populate the detail modal
                recordDetailContent.innerHTML = `
                    <div class="bg-primary-50 p-4 rounded-lg mb-6">
                        <h4 class="font-semibold text-lg text-gray-800 mb-1">${studentName}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Student ID</p>
                                <p class="text-sm font-medium text-gray-700">${studentId}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Year Level</p>
                                <p class="text-sm font-medium text-gray-700">${yearLevel}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Purpose</p>
                            <p class="text-sm font-medium text-gray-700">${purpose}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Laboratory</p>
                            <p class="text-sm font-medium text-gray-700">${laboratory}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Date</p>
                            <p class="text-sm font-medium text-gray-700">${date}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Time</p>
                            <p class="text-sm font-medium text-gray-700">${time}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="mt-1">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                ${status}
                            </span>
                        </p>
                    </div>
                `;
                
                // Show/hide checkout button based on status
                if (status === 'Active') {
                    checkoutBtn.classList.remove('hidden');
                    checkoutBtn.setAttribute('data-id', recordId);
                } else {
                    checkoutBtn.classList.add('hidden');
                }
                
                // Show modal
                recordDetailModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                
                // Add event listener for checkout button
                checkoutBtn.addEventListener('click', function() {
                    const recordId = this.getAttribute('data-id');
                    closeRecordDetailModal();
                    checkoutStudent(recordId);
                });
            }
            
            // Checkout student
            function checkoutStudent(recordId) {
                // Find the record
                const recordRow = document.querySelector(`#records-table-body tr[data-id="${recordId}"]`);
                if (!recordRow) return;
                
                const studentName = recordRow.querySelector('td:nth-child(1) .text-gray-900').textContent.trim();
                
                // Update the row to show completed status
                const statusCell = recordRow.querySelector('td:nth-child(5) span');
                statusCell.textContent = 'Completed';
                statusCell.classList.remove('bg-green-100', 'text-green-800');
                statusCell.classList.add('bg-blue-100', 'text-blue-800');
                
                // Update the time out
                const now = new Date();
                const formattedTime = now.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true});
                const timeCell = recordRow.querySelector('td:nth-child(4) .text-gray-500');
                const timeParts = timeCell.textContent.split(' - ');
                timeCell.textContent = timeParts[0] + ' - ' + formattedTime;
                
                // Remove checkout button
                const checkoutButton = recordRow.querySelector('.checkout-record');
                if (checkoutButton) {
                    checkoutButton.remove();
                }
                
                // Update counts
                updateCounts('checkout');
                
                // Show notification
                showNotification(`${studentName} has been checked out successfully`, 'success');
            }
            
            // Delete record
            function deleteRecord(recordId) {
                // Find the record
                const recordRow = document.querySelector(`#records-table-body tr[data-id="${recordId}"]`);
                if (!recordRow) return;
                
                const studentName = recordRow.querySelector('td:nth-child(1) .text-gray-900').textContent.trim();
                
                // Confirm deletion
                if (confirm(`Are you sure you want to delete ${studentName}'s record?`)) {
                    // Animate the row removal
                    recordRow.classList.add('animate-fade-out');
                    
                    // Update counts
                    const status = recordRow.querySelector('td:nth-child(5) span').textContent.trim();
                    updateCounts(status === 'Active' ? 'checkoutAndRemove' : 'remove');
                    
                    // Remove the row after animation completes
                    setTimeout(() => {
                        recordRow.remove();
                        
                        // Update showing text
                        const allRows = document.querySelectorAll('#records-table-body tr').length;
                        const showingText = document.querySelector('.text-gray-700');
                        if (showingText) {
                            showingText.innerHTML = `
                                Showing <span class="font-medium">${allRows > 0 ? '1' : '0'}</span> to <span class="font-medium">${allRows}</span> of <span class="font-medium">${allRows}</span> records
                            `;
                        }
                    }, 300);
                    
                    // Show notification
                    showNotification(`Record for ${studentName} has been deleted`, 'info');
                }
            }
            
            // Update statistics counts
            function updateCounts(action) {
                // Total count element
                const totalCountElement = document.querySelector('.text-3xl:nth-of-type(1)');
                // Active count element
                const activeCountElement = document.querySelector('.text-3xl:nth-of-type(2)');
                // Completed count element
                const completedCountElement = document.querySelector('.text-3xl:nth-of-type(3)');
                
                if (!totalCountElement || !activeCountElement || !completedCountElement) return;
                
                const totalCount = parseInt(totalCountElement.textContent);
                const activeCount = parseInt(activeCountElement.textContent);
                const completedCount = parseInt(completedCountElement.textContent);
                
                if (action === 'checkout') {
                    // Just move one from active to completed
                    activeCountElement.textContent = activeCount - 1;
                    completedCountElement.textContent = completedCount + 1;
                } else if (action === 'remove') {
                    // Remove from total and completed
                    totalCountElement.textContent = totalCount - 1;
                    completedCountElement.textContent = completedCount - 1;
                } else if (action === 'checkoutAndRemove') {
                    // Remove from total and active
                    totalCountElement.textContent = totalCount - 1;
                    activeCountElement.textContent = activeCount - 1;
                }
            }
            
            // Add New Sit-in button
            document.getElementById('add-new-btn').addEventListener('click', function() {
                openSearchModal();
            });
            
            // Show notification function
            function showNotification(message, type = 'info') {
                const notificationContainer = document.getElementById('notification-container');
                const notificationId = 'notification-' + Date.now();
                
                let bgColor, iconClass;
                switch (type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        iconClass = 'fa-check-circle';
                        break;
                    case 'warning':
                        bgColor = 'bg-yellow-500';
                        iconClass = 'fa-exclamation-triangle';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        iconClass = 'fa-times-circle';
                        break;
                    default: // info
                        bgColor = 'bg-blue-500';
                        iconClass = 'fa-info-circle';
                }
                
                const notification = document.createElement('div');
                notification.id = notificationId;
                notification.className = `mb-3 p-4 rounded-lg shadow-lg ${bgColor} text-white flex items-start transform transition-all duration-300 opacity-0 translate-y-4`;
                notification.innerHTML = `
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="flex-grow pr-6">
                        ${message}
                    </div>
                    <button class="flex-shrink-0 text-white focus:outline-none" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                notificationContainer.appendChild(notification);
                
                // Animate entrance
                setTimeout(() => {
                    notification.classList.remove('opacity-0', 'translate-y-4');
                }, 10);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (document.getElementById(notificationId)) {
                        notification.classList.add('opacity-0', 'translate-y-4');
                        setTimeout(() => {
                            if (document.getElementById(notificationId)) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            }
            
            // View Lab details
            const viewLabButtons = document.querySelectorAll('.view-lab');
            viewLabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const labName = this.getAttribute('data-lab');
                    showNotification(`Lab details for ${labName} would be shown here`, 'info');
                    // In a real application, you would show a modal with lab details
                });
            });
        });