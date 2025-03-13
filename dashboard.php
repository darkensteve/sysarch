<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Get admin information from session
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Static data for dashboard display
$total_students = 250;
$present_today = 187;
$absent_today = 63;
$late_arrivals = 32;

// Static data for recent activity
$recent_activities = [
    [
        'student_name' => 'John Smith',
        'student_id' => 'STU1001',
        'status' => 'Present',
        'status_class' => 'bg-success',
        'time' => '09:15 AM'
    ],
    [
        'student_name' => 'Maria Johnson',
        'student_id' => 'STU1002',
        'status' => 'Late',
        'status_class' => 'bg-warning',
        'time' => '09:45 AM'
    ],
    [
        'student_name' => 'Robert Williams',
        'student_id' => 'STU1003',
        'status' => 'Present',
        'status_class' => 'bg-success',
        'time' => '08:50 AM'
    ],
    [
        'student_name' => 'Sarah Brown',
        'student_id' => 'STU1004',
        'status' => 'Absent',
        'status_class' => 'bg-danger',
        'time' => '-'
    ],
    [
        'student_name' => 'Michael Davis',
        'student_id' => 'STU1005',
        'status' => 'Present',
        'status_class' => 'bg-success',
        'time' => '08:30 AM'
    ]
];

// Static data for pie charts
$sit_in_purposes = [
    'C Programming' => 75,
    'Java Programming' => 58,
    'C# Programming' => 42,
    'PHP Programming' => 63,
    'ASP.net Programming' => 32
];

$student_year_levels = [
    'First Year' => 85,
    'Second Year' => 72,
    'Third Year' => 54,
    'Fourth Year' => 39
];

// Add sample announcements data
$announcements = [
    [
        'id' => 1,
        'title' => 'System Maintenance Notice',
        'content' => 'The sit-in monitoring system will be undergoing maintenance this weekend. Please expect some downtime from Saturday 10 PM to Sunday 2 AM.',
        'date' => '2023-10-15 09:30:00',
        'author' => 'System Administrator'
    ],
    [
        'id' => 2,
        'title' => 'New Programming Lab Rules',
        'content' => 'Starting next week, all students must register their sit-in requests at least 24 hours in advance. This is to ensure better resource allocation and lab availability.',
        'date' => '2023-10-12 14:45:00',
        'author' => 'Lab Coordinator'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sit-in Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Additional utility classes for dark mode */
        .dark\:bg-slate-700 {
            background-color: #334155;
        }
        .dark\:bg-slate-800 {
            background-color: #1e293b;
        }
        .dark\:border-slate-700 {
            border-color: #334155;
        }
        .dark\:text-white {
            color: #ffffff;
        }
        .dark\:text-slate-400 {
            color: #94a3b8;
        }
        .dark\:text-slate-300 {
            color: #cbd5e1;
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
                                <?php foreach(array_keys($sit_in_purposes) as $purpose): ?>
                                <option value="<?php echo htmlspecialchars($purpose); ?>"><?php echo htmlspecialchars($purpose); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-xs text-red-500 mt-1 hidden" id="purpose-error">Please select a purpose</div>
                        </div>
                        
                        <div>
                            <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratory Room</label>
                            <select id="laboratory" name="laboratory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="" selected disabled>Select laboratory...</option>
                                <option value="Lab 1">Computer Laboratory 1</option>
                                <option value="Lab 2">Computer Laboratory 2</option>
                                <option value="Lab 3">Computer Laboratory 3</option>
                                <option value="Lab 4">Computer Laboratory 4</option>
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
                    <a href="dashboard.php" class="flex items-center p-2.5 rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20">
                        <i class="fas fa-home w-5 h-5 text-primary-300"></i>
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
                    <a href="admin_student.php" class="flex items-center p-2.5 rounded-lg hover:bg-gray-700/50 transition-all">
                        <i class="fas fa-users w-5 h-5 text-gray-400 group-hover:text-white"></i>
                        <span class="ml-3">Students</span>
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
                <!-- Header with date - Blue accent -->
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between p-5 bg-blue-50/50 border-l-4 border-primary-500 rounded-lg">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-1">Dashboard</h1>
                        <p class="text-gray-500">
                            Welcome back, <?php echo htmlspecialchars($admin_name); ?>! Here's what's happening today.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-white rounded-lg shadow-sm px-4 py-2 flex items-center">
                        <i class="far fa-calendar-alt text-primary-500 mr-2"></i>
                        <span class="text-gray-600 font-medium"><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
                
                <!-- Stats Cards with animation - Purple accent -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 p-5 bg-purple-50/50 border-l-4 border-purple-500 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 col-span-1 sm:col-span-2 lg:col-span-3 mb-2">Key Statistics</h2>
                    <!-- Total Students -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-primary-500"></div>
                        <div class="rounded-full bg-primary-50 p-3 mr-4">
                            <i class="fas fa-user-graduate text-primary-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Students</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $total_students; ?></p>
                            <p class="text-xs text-primary-600 mt-1 font-medium">
                                <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                            </p>
                        </div>
                    </div>
                    
                    <!-- Current Sit-In (formerly Present Today) -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-green-500"></div>
                        <div class="rounded-full bg-green-50 p-3 mr-4">
                            <i class="fas fa-chair text-green-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Current Sit-In</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $present_today; ?></p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: <?php echo ($present_today/$total_students*100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Approval (formerly Absent Today) -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 flex items-center transition-all border border-gray-100 card-zoom relative overflow-hidden">
                        <div class="absolute top-0 right-0 h-full w-1 bg-amber-500"></div>
                        <div class="rounded-full bg-amber-50 p-3 mr-4">
                            <i class="fas fa-clock text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Pending Approval</h3>
                            <p class="text-3xl font-bold text-gray-800"><?php echo $absent_today; ?></p>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: <?php echo ($absent_today/$total_students*100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section - Green accent -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 p-5 bg-green-50/50 border-l-4 border-green-500 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 col-span-1 lg:col-span-2 mb-2">Analytics Overview</h2>
                    <!-- Programming Purpose Pie Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                            <h3 class="font-semibold text-lg text-gray-800">Sit-in Programming Purposes</h3>
                            <div class="flex space-x-2">
                                <button class="p-2 rounded-md hover:bg-gray-100 transition-all">
                                    <i class="fas fa-sync-alt text-gray-500"></i>
                                </button>
                                <button class="p-2 rounded-md hover:bg-gray-100 transition-all">
                                    <i class="fas fa-download text-gray-500"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center items-center">
                                <canvas id="purposeChart" height="240"></canvas>
                            </div>
                            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-3">
                                <?php 
                                $colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                                $i = 0;
                                foreach($sit_in_purposes as $purpose => $count): 
                                    $color = $colors[$i % count($colors)];
                                    $i++;
                                ?>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo $color; ?>"></div>
                                    <span class="text-xs text-gray-600"><?php echo $purpose; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Student Year Level Pie Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                            <h3 class="font-semibold text-lg text-gray-800">Student Year Levels</h3>
                            <div class="flex space-x-2">
                                <button class="p-2 rounded-md hover:bg-gray-100 transition-all">
                                    <i class="fas fa-sync-alt text-gray-500"></i>
                                </button>
                                <button class="p-2 rounded-md hover:bg-gray-100 transition-all">
                                    <i class="fas fa-download text-gray-500"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center items-center">
                                <canvas id="yearLevelChart" height="240"></canvas>
                            </div>
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <?php 
                                $yearColors = ['#0EA5E9', '#F97316', '#14B8A6', '#6366F1'];
                                $i = 0;
                                foreach($student_year_levels as $year => $count): 
                                    $color = $yearColors[$i % count($yearColors)];
                                    $i++;
                                ?>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo $color; ?>"></div>
                                    <span class="text-xs text-gray-600"><?php echo $year; ?> (<?php echo $count; ?>)</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement Section - Amber accent -->
                <div class="p-5 bg-amber-50/50 border-l-4 border-amber-500 rounded-lg mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Announcements & Notifications</h2>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-bullhorn text-amber-500"></i>
                                <h3 class="font-semibold text-lg text-gray-800">Announcements</h3>
                                <span class="bg-amber-100 text-amber-600 text-xs px-2 py-0.5 rounded-full"><?php echo count($announcements); ?></span>
                            </div>
                            <div class="flex space-x-2">
                                <button id="new-announcement-btn" class="px-3 py-1 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white transition-all text-sm flex items-center shadow-sm hover:shadow">
                                    <i class="fas fa-plus mr-2"></i> New Announcement
                                </button>
                            </div>
                        </div>

                        <!-- Create/Edit Announcement Form (hidden by default) -->
                        <div id="announcement-form-container" class="p-6 border-b border-gray-200 hidden bg-gray-50/50">
                            <form id="announcement-form" class="space-y-4">
                                <input type="hidden" id="announcement-id" value="">
                                <div>
                                    <label for="announcement-title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" id="announcement-title" name="title" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white text-gray-800 transition-all" placeholder="Enter announcement title">
                                    <div class="text-xs text-red-500 mt-1 hidden" id="title-error">Please enter a title</div>
                                </div>
                                <div>
                                    <label for="announcement-content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    <textarea id="announcement-content" name="content" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white text-gray-800 transition-all" placeholder="Enter announcement content"></textarea>
                                    <div class="text-xs text-red-500 mt-1 hidden" id="content-error">Please enter content</div>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancel-announcement" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white rounded-lg transition-all shadow-sm hover:shadow flex items-center">
                                        <i id="form-action-icon" class="fas fa-plus mr-2"></i>
                                        <span id="form-action-text">Post Announcement</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Announcements List -->
                        <div class="divide-y divide-gray-200" id="announcements-container">
                            <?php if (empty($announcements)): ?>
                                <div class="p-12 text-center text-gray-500 flex flex-col items-center justify-center">
                                    <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                                    <p>No announcements available.</p>
                                    <button id="create-first-announcement" class="mt-4 px-3 py-1.5 text-sm rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200 transition-all">Create your first announcement</button>
                                </div>
                            <?php else: ?>
                                <?php foreach($announcements as $index => $announcement): ?>
                                    <?php 
                                    // Check if announcement is recent (less than 24 hours old)
                                    $isRecent = (time() - strtotime($announcement['date']) < 86400);
                                    ?>
                                    <div class="announcement-item group" data-id="<?php echo $announcement['id']; ?>">
                                        <div class="p-6 hover:bg-gray-50/80 transition-all">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($announcement['title']); ?></h4>
                                                    <?php if($isRecent): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">New</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button class="edit-announcement p-1.5 rounded-md text-gray-500 hover:text-primary-500 hover:bg-gray-100 transition-all" data-id="<?php echo $announcement['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="delete-announcement p-1.5 rounded-md text-gray-500 hover:text-red-500 hover:bg-gray-100 transition-all" data-id="<?php echo $announcement['id']; ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-gray-600 mb-3 whitespace-pre-line"><?php echo htmlspecialchars($announcement['content']); ?></p>
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs text-gray-500 space-y-2 sm:space-y-0">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user-circle mr-1.5"></i>
                                                    <span><?php echo htmlspecialchars($announcement['author']); ?></span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="far fa-clock mr-1.5"></i>
                                                    <span><?php echo date('F j, Y \a\t g:i A', strtotime($announcement['date'])); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- You could add more sections here with different accent colors -->
                
            </div>
        </main>
    </div>
    
    <script>
        // Toggle sidebar with improved animation
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
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Programming Purpose Chart
            const purposeCtx = document.getElementById('purposeChart').getContext('2d');
            const purposeChart = new Chart(purposeCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_keys($sit_in_purposes)); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($sit_in_purposes)); ?>,
                        backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                        borderColor: 'white',
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Year Level Chart
            const yearLevelCtx = document.getElementById('yearLevelChart').getContext('2d');
            const yearLevelChart = new Chart(yearLevelCtx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_keys($student_year_levels)); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($student_year_levels)); ?>,
                        backgroundColor: ['#0EA5E9', '#F97316', '#14B8A6', '#6366F1'],
                        borderColor: 'white',
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });

        // Announcement functionality
        document.addEventListener('DOMContentLoaded', function() {
            // ...existing chart initialization...
            
            // Initialize announcement functionality
            const newAnnouncementBtn = document.getElementById('new-announcement-btn');
            const announcementForm = document.getElementById('announcement-form');
            const cancelAnnouncementBtn = document.getElementById('cancel-announcement');
            const formContainer = document.getElementById('announcement-form-container');
            const announcementId = document.getElementById('announcement-id');
            const announcementTitle = document.getElementById('announcement-title');
            const announcementContent = document.getElementById('announcement-content');
            const formActionText = document.getElementById('form-action-text');
            const formActionIcon = document.getElementById('form-action-icon');
            const createFirstAnnouncementBtn = document.getElementById('create-first-announcement');
            
            // "Create first announcement" button in empty state
            if (createFirstAnnouncementBtn) {
                createFirstAnnouncementBtn.addEventListener('click', function() {
                    resetForm();
                    formContainer.classList.remove('hidden');
                    formActionText.textContent = 'Post Announcement';
                    formActionIcon.className = 'fas fa-plus mr-2';
                });
            }
            
            // Show form when new announcement button is clicked
            newAnnouncementBtn.addEventListener('click', function() {
                resetForm();
                formContainer.classList.remove('hidden');
                formActionText.textContent = 'Post Announcement';
                formActionIcon.className = 'fas fa-plus mr-2';
            });
            
            // Hide form when cancel is clicked
            cancelAnnouncementBtn.addEventListener('click', function() {
                formContainer.classList.add('hidden');
                resetForm();
            });
            
            // Form field validation
            announcementTitle.addEventListener('input', function() {
                if (this.value.trim().length > 0) {
                    document.getElementById('title-error').classList.add('hidden');
                }
            });
            
            announcementContent.addEventListener('input', function() {
                if (this.value.trim().length > 0) {
                    document.getElementById('content-error').classList.add('hidden');
                }
            });
            
            // Edit announcement
            document.querySelectorAll('.edit-announcement').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const announcementItem = document.querySelector(`.announcement-item[data-id="${id}"]`);
                    const title = announcementItem.querySelector('h4').textContent;
                    const content = announcementItem.querySelector('p').textContent;
                    
                    announcementId.value = id;
                    announcementTitle.value = title;
                    announcementContent.value = content;
                    
                    formContainer.classList.remove('hidden');
                    formActionText.textContent = 'Update Announcement';
                    formActionIcon.className = 'fas fa-edit mr-2';
                    
                    // Scroll to form with smooth animation
                    formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
            
            // Delete announcement
            document.querySelectorAll('.delete-announcement').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const announcementItem = document.querySelector(`.announcement-item[data-id="${id}"]`);
                    const title = announcementItem.querySelector('h4').textContent;
                    
                    // Create and show confirmation modal
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 animate-fade-in';
                    modal.innerHTML = `
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-6 animate-slide-in">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-slate-800 dark:text-white">Delete Announcement</h3>
                                <p class="text-slate-600 dark:text-slate-400 mt-1">Are you sure you want to delete this announcement? This action cannot be undone.</p>
                            </div>
                            <div class="p-3 mb-4 bg-slate-100 dark:bg-slate-700/50 rounded-lg">
                                <h4 class="font-medium text-slate-800 dark:text-white text-sm">${title}</h4>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button id="cancel-delete" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg transition-all">Cancel</button>
                                <button id="confirm-delete" class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white rounded-lg transition-all shadow-sm hover:shadow flex items-center">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                    
                    // Handle confirm delete
                    document.getElementById('confirm-delete').addEventListener('click', function() {
                        // Remove the modal with animation
                        modal.classList.add('opacity-0');
                        setTimeout(() => modal.remove(), 300);
                        
                        // In a real app, you would send a delete request to the server
                        // Here we just remove it from the DOM for demo purposes
                        announcementItem.classList.add('animate-fade-out');
                        setTimeout(() => announcementItem.remove(), 300);
                        
                        // Show deletion notification
                        showNotification(`Announcement "${title}" was successfully deleted`, 'error');
                    });
                    
                    // Handle cancel delete
                    document.getElementById('cancel-delete').addEventListener('click', function() {
                        // Remove the modal with animation
                        modal.classList.add('opacity-0');
                        setTimeout(() => modal.remove(), 300);
                    });
                });
            });
            
            // Form submission
            announcementForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = announcementId.value;
                const title = announcementTitle.value.trim();
                const content = announcementContent.value.trim();
                
                // Validate form fields
                let hasError = false;
                if (!title) {
                    document.getElementById('title-error').classList.remove('hidden');
                    hasError = true;
                }
                
                if (!content) {
                    document.getElementById('content-error').classList.remove('hidden');
                    hasError = true;
                }
                
                if (hasError) return;
                
                if (id) {
                    // Update existing announcement
                    const announcementItem = document.querySelector(`.announcement-item[data-id="${id}"]`);
                    announcementItem.querySelector('h4').textContent = title;
                    announcementItem.querySelector('p').textContent = content;
                    
                    // Add highlight effect
                    announcementItem.classList.add('bg-yellow-50', 'dark:bg-yellow-900/10');
                    setTimeout(() => {
                        announcementItem.classList.remove('bg-yellow-50', 'dark:bg-yellow-900/10');
                    }, 2000);
                    
                    // Show update notification
                    showNotification(`Announcement "${title}" was successfully updated`, 'warning');
                } else {
                    // Create new announcement
                    const container = document.getElementById('announcements-container');
                    const newId = Date.now(); // Generate temporary ID
                    
                    // Check if container has empty state message and remove it
                    const emptyState = container.querySelector('.text-center');
                    if (emptyState) {
                        container.innerHTML = '';
                    }
                    
                    // Create new announcement HTML
                    const newAnnouncement = document.createElement('div');
                    newAnnouncement.className = 'announcement-item group';
                    newAnnouncement.setAttribute('data-id', newId);
                    
                    const now = new Date();
                    const formattedDate = now.toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    
                    newAnnouncement.innerHTML = `
                        <div class="p-6 hover:bg-slate-50/80 dark:hover:bg-slate-800/80 transition-all">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-medium text-slate-800 dark:text-white">${title}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">New</span>
                                </div>
                                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="edit-announcement p-1.5 rounded-md text-slate-500 dark:text-slate-400 hover:text-primary-500 dark:hover:text-primary-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" data-id="${newId}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="delete-announcement p-1.5 rounded-md text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" data-id="${newId}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 mb-3 whitespace-pre-line">${content}</p>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs text-slate-500 dark:text-slate-400 space-y-2 sm:space-y-0">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle mr-1.5"></i>
                                    <span><?php echo htmlspecialchars($admin_name); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="far fa-clock mr-1.5"></i>
                                    <span>${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add with animation
                    newAnnouncement.style.opacity = '0';
                    newAnnouncement.style.transform = 'translateY(20px)';
                    
                    // Add to the beginning of the container
                    if (container.firstChild) {
                        container.insertBefore(newAnnouncement, container.firstChild);
                    } else {
                        container.appendChild(newAnnouncement);
                    }
                    
                    // Trigger animation
                    setTimeout(() => {
                        newAnnouncement.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        newAnnouncement.style.opacity = '1';
                        newAnnouncement.style.transform = 'translateY(0)';
                    }, 10);
                    
                    // Add event listeners to new buttons
                    newAnnouncement.querySelector('.edit-announcement').addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const announcementItem = document.querySelector(`.announcement-item[data-id="${id}"]`);
                        const title = announcementItem.querySelector('h4').textContent;
                        const content = announcementItem.querySelector('p').textContent;
                        
                        announcementId.value = id;
                        announcementTitle.value = title;
                        announcementContent.value = content;
                        
                        formContainer.classList.remove('hidden');
                        formActionText.textContent = 'Update Announcement';
                        formActionIcon.className = 'fas fa-edit mr-2';
                        
                        formContainer.scrollIntoView({ behavior: 'smooth' });
                    });
                    
                    newAnnouncement.querySelector('.delete-announcement').addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const announcementItem = document.querySelector(`.announcement-item[data-id="${id}"]`);
                        const title = announcementItem.querySelector('h4').textContent;
                        
                        // Create modal confirmation for delete
                        // ... (Same modal code as above) ...
                    });
                    
                    // Show creation notification
                    showNotification(`New announcement "${title}" was successfully posted`, 'success');
                }
                
                // Hide form and reset
                formContainer.classList.add('hidden');
                resetForm();
            });
            
            function resetForm() {
                announcementId.value = '';
                announcementTitle.value = '';
                announcementContent.value = '';
                document.getElementById('title-error').classList.add('hidden');
                document.getElementById('content-error').classList.add('hidden');
            }
            
            // Notification function
            function showNotification(message, type) {
                const notificationContainer = document.getElementById('notification-container');
                
                // Set colors based on type
                let bgColor, iconClass;
                switch(type) {
                    case 'success':
                        bgColor = 'bg-gradient-to-r from-green-500 to-emerald-500';
                        iconClass = 'fa-check-circle';
                        break;
                    case 'error':
                        bgColor = 'bg-gradient-to-r from-red-500 to-pink-500';
                        iconClass = 'fa-times-circle';
                        break;
                    case 'warning':
                        bgColor = 'bg-gradient-to-r from-amber-500 to-orange-500';
                        iconClass = 'fa-exclamation-circle';
                        break;
                    default:
                        bgColor = 'bg-gradient-to-r from-primary-500 to-secondary-500';
                        iconClass = 'fa-info-circle';
                }
                
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `${bgColor} text-white p-4 mb-3 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-500 ease-in-out translate-x-full opacity-0`;
                
                // Add content to notification
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${iconClass} mr-3"></i>
                        <span>${message}</span>
                    </div>
                    <button class="text-white hover:text-white/70 focus:outline-none transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                // Add notification to container
                notificationContainer.appendChild(notification);
                
                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full', 'opacity-0');
                }, 10);
                
                // Add close event to button
                const closeBtn = notification.querySelector('button');
                closeBtn.addEventListener('click', () => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                });
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 5000);
            }
        });

        // Fix announcement functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure animation classes are properly applied
            setTimeout(() => {
                document.querySelectorAll('.card-delay-1, .card-delay-2, .card-delay-3').forEach(el => {
                    el.classList.add('animate-fade-in-up');
                });
            }, 100);
            
            // Initialize announcement functionality
            const newAnnouncementBtn = document.getElementById('new-announcement-btn');
            const announcementForm = document.getElementById('announcement-form');
            const cancelAnnouncementBtn = document.getElementById('cancel-announcement');
            const formContainer = document.getElementById('announcement-form-container');
            const announcementId = document.getElementById('announcement-id');
            const announcementTitle = document.getElementById('announcement-title');
            const announcementContent = document.getElementById('announcement-content');
            const formActionText = document.getElementById('form-action-text');
            const formActionIcon = document.getElementById('form-action-icon');
            const createFirstAnnouncementBtn = document.getElementById('create-first-announcement');
            
            // ...rest of the announcement functionality code remains unchanged
            // ...existing code...
        });

        // Search Modal Functionality - Fixed
        document.addEventListener('DOMContentLoaded', function() {
            const searchButton = document.getElementById('search-button');
            const sidebarSearchButton = document.getElementById('sidebar-search-button');
            const searchModal = document.getElementById('search-modal');
            const closeSearchModal = document.getElementById('close-search-modal');
            const searchForm = document.getElementById('search-form');
            
            // Function to open modal
            function openSearchModal() {
                searchModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Prevent scrolling behind modal
                
                // Focus on search input after modal is shown
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
            
            // Close modal function
            function closeModal() {
                searchModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            // Close when clicking the close button
            closeSearchModal.addEventListener('click', closeModal);
            
            // Close when clicking outside the modal
            searchModal.addEventListener('click', function(e) {
                if (e.target === searchModal) {
                    closeModal();
                }
            });
            
            // Close when pressing ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                    closeModal();
                }
            });
            
            // Handle search form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const query = document.getElementById('search-query').value.trim();
                
                // For demonstration, show notification with search query
                let searchMessage = `Searching for: "${query}"`;
                
                showNotification(searchMessage, 'info');
                
                // Here you would typically send the search request to the server
                // and process the response
                
                // Close the modal after submission
                closeModal();
            });
        });

        // Search Modal Functionality - Updated with Student Form
        document.addEventListener('DOMContentLoaded', function() {
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
            });
            
            // Field validation handlers
            document.getElementById('purpose').addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('purpose-error').classList.add('hidden');
                }
            });
            
            document.getElementById('laboratory').addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('lab-error').classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
