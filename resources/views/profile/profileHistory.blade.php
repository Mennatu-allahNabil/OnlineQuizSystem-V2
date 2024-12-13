<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-row justify-content-start gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Performance History') }}
            </h2>
        </div>
    </x-slot>
    <style>
        .bg-gray {
            background-color: #6c757d !important; /* Adjust the shade of gray as needed */
        }
    </style>
    <div class="container text-center " style="padding-top: 30px;">
        <h1 class="text-primary">Your Performance</h1>
        <p>Congratulations! Here are your results:</p>

        <!-- Dropdown for filtering attempts -->
        <div class="mb-4">
            <select id="attemptFilter" class="form-select" onchange="filterTableAndChart()">
                <option value="all">Show All</option>
                <option value="once">Once Attempt</option>
                <option value="multiple">Multiple Attempts</option>
            </select>
        </div>

        @if($userResults->isEmpty())
            <p>No results found for your quizzes.</p>
        @else
            <!-- Smaller Performance Trends Chart -->
            <div class="mb-4">
                <canvas id="performanceChart" width="800" height="600" ></canvas> <!-- Reduced size -->
            </div>

            <!-- Table of Results -->
            <div class="table-responsive shadow-sm p-3 mb-5 bg-body rounded">
                <table class="table table-hover table-striped table-bordered text-center align-middle" id="resultsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Quiz Title</th>
                            <th>Score (%)</th>
                            <th>Attempt Number <i class="fas fa-sync-alt" data-bs-toggle="tooltip" data-bs-placement="top" title="Attempt Number"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userResults->groupBy('quiz_id') as $quizId => $results)

                            @foreach($results as $result)
                            <tr class="animate__animated animate__fadeInUp" data-attempt-type="{{ $results->count() == 1 ? 'once' : 'multiple' }}" data-attempt-number="{{ $result->attempt_number }}" data-quiz-type="{{$result->quiz_type}}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $result->title }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $result->score == 0 ? 'gray' : ($result->score >= 75 ? 'success' : ($result->score >= 50 ? 'warning' : 'danger')) }}"
                                             role="progressbar" style="width: {{ $result->score ? $result->score : 100 }}%;"
                                             aria-valuemin="0" aria-valuemax="100">
                                            {{ $result->score }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $result->attempt_number }}
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script to Render Smaller Performance Trends -->
    <script>
        // Get the results from PHP and format them for the chart
        var quizTitles = @json($userResults->pluck('title'));
        var quizScores = @json($userResults->pluck('score'));

        // Create the performance trend chart
        var ctx = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: quizTitles, // X-axis labels (Quiz Titles)
                datasets: [{
                    label: 'Quiz Scores',
                    data: quizScores, // Y-axis data (Scores)
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2, // Thinner line
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Custom point colors
                    pointRadius: 3 // Smaller points
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // To allow for custom sizing
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100, // Set max to 100 to prevent ticks beyond 100
                        suggestedMax: 103, // Suggest a value slightly above 100 for padding
                        ticks: {
                            stepSize: 5 // Optional: make the ticks increment by 10 for better readability
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide the legend for a cleaner look
                    }
                },
                elements: {
                    line: {
                        tension: 0.4 // Make the line smoother (curve)
                    }
                }
            }
        });

        // Function to filter the table based on attempt type
        function filterTable() {
            var filter = document.getElementById("attemptFilter").value;
            var rows = document.querySelectorAll("#resultsTable tbody tr");

            rows.forEach(function(row) {
                var attemptNumber = parseInt(row.getAttribute('data-attempt-number'), 10);
                var quizType = row.getAttribute('data-quiz-type');
                // console.log(quizType);
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'once' && quizType === 'once_attempt') {
                    row.style.display = '';
                } else if (filter === 'multiple' && quizType === 'multiple_attempts') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

{{--    <script>--}}
{{--            // Get the results from PHP and format them for the chart--}}
{{--            var quizTitles = @json($userResults->pluck('title'));--}}
{{--            var quizScores = @json($userResults->pluck('score'));--}}

{{--            // Pass raw attempt numbers to JavaScript--}}
{{--            var attemptNumbers = @json($userResults->pluck('attempt_number'));--}}

{{--            // Map the attempt numbers to 'once_attempt' or 'multiple_attempts'--}}
{{--            var quizTypes = attemptNumbers.map(function(num) {--}}
{{--            return num === 1 ? 'once_attempt' : 'multiple_attempts';--}}
{{--        });--}}

{{--            // Initialize the chart--}}
{{--            var ctx = document.getElementById('performanceChart').getContext('2d');--}}
{{--            var performanceChart = new Chart(ctx, {--}}
{{--            type: 'line',--}}
{{--            data: {--}}
{{--            labels: quizTitles, // X-axis labels (Quiz Titles)--}}
{{--            datasets: [{--}}
{{--            label: 'Quiz Scores',--}}
{{--            data: quizScores, // Y-axis data (Scores)--}}
{{--            borderColor: 'rgba(75, 192, 192, 1)',--}}
{{--            backgroundColor: 'rgba(75, 192, 192, 0.2)',--}}
{{--            borderWidth: 2,--}}
{{--            pointBackgroundColor: 'rgba(54, 162, 235, 1)',--}}
{{--            pointRadius: 3--}}
{{--        }]--}}
{{--        },--}}
{{--            options: {--}}
{{--            responsive: true,--}}
{{--            maintainAspectRatio: false,--}}
{{--            scales: {--}}
{{--            y: {--}}
{{--            beginAtZero: true,--}}
{{--            max: 100,--}}
{{--            suggestedMax: 103,--}}
{{--            ticks: {--}}
{{--            stepSize: 5--}}
{{--        }--}}
{{--        }--}}
{{--        },--}}
{{--            plugins: {--}}
{{--            legend: {--}}
{{--            display: false--}}
{{--        }--}}
{{--        },--}}
{{--            elements: {--}}
{{--            line: {--}}
{{--            tension: 0.4--}}
{{--        }--}}
{{--        }--}}
{{--        }--}}
{{--        });--}}

{{--            // Function to filter both the table and the chart based on attempt type--}}
{{--            function filterTableAndChart() {--}}
{{--            var filter = document.getElementById("attemptFilter").value;--}}
{{--            var rows = document.querySelectorAll("#resultsTable tbody tr");--}}

{{--            // Filter the table rows based on the selected filter--}}
{{--            rows.forEach(function(row) {--}}
{{--            var quizType = row.getAttribute('data-quiz-type');--}}
{{--            if (filter === 'all') {--}}
{{--            row.style.display = '';--}}
{{--        } else if (filter === 'once' && quizType === 'once_attempt') {--}}
{{--            row.style.display = '';--}}
{{--        } else if (filter === 'multiple' && quizType === 'multiple_attempts') {--}}
{{--            row.style.display = '';--}}
{{--        } else {--}}
{{--            row.style.display = 'none';--}}
{{--        }--}}
{{--        });--}}

{{--            // Filter the chart data--}}
{{--            var filteredTitles = [];--}}
{{--            var filteredScores = [];--}}
{{--            var filteredTypes = [];--}}

{{--            // Loop through the quiz types and apply the filter for the chart--}}
{{--            for (var i = 0; i < quizTypes.length; i++) {--}}
{{--            if (filter === 'all' || (filter === 'once' && quizTypes[i] === 'once_attempt') || (filter === 'multiple' && quizTypes[i] === 'multiple_attempts')) {--}}
{{--            filteredTitles.push(quizTitles[i]);--}}
{{--            filteredScores.push(quizScores[i]);--}}
{{--        }--}}
{{--        }--}}

{{--            // Update the chart with the filtered data--}}
{{--            performanceChart.data.labels = filteredTitles;--}}
{{--            performanceChart.data.datasets[0].data = filteredScores;--}}
{{--            performanceChart.update();--}}
{{--        }--}}

{{--            // Attach the filter function to the dropdown change event--}}
{{--            document.getElementById("attemptFilter").addEventListener("change", filterTableAndChart);--}}

{{--    </script>--}}


</x-app-layout>
