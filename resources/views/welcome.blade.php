<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Future</title>
    <style>
        
        .dark {
        background-color:rgb(0, 0, 0) !important;
        color: #ffffff !important;
        }

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
    <div class="min-h-screen flex flex-col items-center justify-center px-6">
        <button id="darkModeToggle" class="absolute top-5 right-5 p-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-black rounded-lg shadow-md">
            🌚
        </button>

        <h1 class="text-5xl font-extrabold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-500 animate-pulse" style="text-align:center;">
            Welcome to the Uzbekistan Weightlifting  <br>Federation U-Entry System!
        </h1>

        <p class="text-lg text-center max-w-xl opacity-80">
        This platform is designed to streamline athlete management, making it easier to register, track, and manage weightlifters across Uzbekistan. With powerful tools like real-time data entry, region-based filtering, and seamless export options, we ensure a smooth and efficient experience for coaches, administrators, and federation officials.        </p>

        <div class="mt-8 space-x-4">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg transition-all">
                Get Started
            </a>
        </div>

        
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleDarkMode = document.getElementById("darkModeToggle");
        const body = document.body;

        // Check user's previous preference and set icon accordingly
        if (localStorage.getItem("darkMode") === "enabled") {
            body.classList.add("dark");
            toggleDarkMode.innerHTML = "🌚"; // Set to dark mode emoji
        } else {
            toggleDarkMode.innerHTML = "🌝"; // Set to light mode emoji
        }

        toggleDarkMode.addEventListener("click", function () {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                localStorage.setItem("darkMode", "enabled");
                toggleDarkMode.innerHTML = "🌚";
            } else {
                localStorage.setItem("darkMode", "disabled");
                toggleDarkMode.innerHTML = "🌝";
            }
        });
    });
</script>

    
</body>
</html>
