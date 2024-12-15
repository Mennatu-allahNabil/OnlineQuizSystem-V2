<x-app-layout>
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 35%;
            min-width: 300px;
            max-width: 90%;
        }

        @media (max-width: 1259px) {
            .modal-content {
                width: 90%;
            }
        }
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px;
        }
    </style>
    <div class="container my-5">
        <h1 class="text-center mb-4">Quizzes on <span class="fw-bolder">{{ ucwords($topic->name) }}</span></h1>
        <div class="row">
            @forelse($quizzes as $quiz)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm m-2" style="height:20em;overflow:hidden;">
                        <div class="imgquiz">
                            @if($quiz->image)
                                <img src="{{ asset('upload_images/' . $quiz->image) }}" class="card-img-top" alt="{{ $quiz->title }}">
                            @else
                                <img src="https://images.pexels.com/photos/207756/pexels-photo-207756.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img-top" alt="{{ $quiz->title }}">
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('quiz.show', $quiz->id) }}" class="text-dark text-decoration-none">{{ $quiz->title }}</a>
                            </h5>
                            <p class="card-text">{{ $quiz->description }}</p>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="openQuizModal(
                                '{{ route('quiz.show', $quiz->id) }}',
                                '{{ $quiz->title }}',
                                '{{ $quiz->description }}',
                                '{{ $quiz->time_limit }}',
                                '{{ $quiz->quiz_type }}',
                                '{{ $quiz->questions->count() }}',
                                '{{ $quiz->topic->name ?? 'No Topic' }}'
                                )">Start Quiz</a>
                                <div>
                                    <div class="btn btn-warning text-dark" title="{{$quiz->time_limit ? 'with time limit' : 'with no time limit'}}">
                                        @if ($quiz->time_limit)
                                            <i class="fa-solid fa-stopwatch fs-6 py-1"></i>
                                        @else
                                            <span class="fa-stack fa-xs">
                                            <i class="fa-solid fa-stopwatch fa-stack-1x fs-6"></i>
                                            <i class="fa-solid fa-slash fa-stack-1x" style="color:red; font-size: 1em;"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="btn btn-warning text-dark" title="{{$quiz->quiz_type == 'multiple_attempts' ? 'multiple attempts can be taken' : 'can be taken once only'}}">
                                        @if ($quiz->quiz_type == "multiple_attempts")
                                            <i class="fa fa-repeat fs-6 py-1 d-flex align-items-center"></i>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="27" fill="currentColor" class="bi bi-1-circle" viewBox="0 0 16 16">
                                                <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="d-flex justify-content-center align-items-center p-4 " style="background-color:rgba(255, 255, 212, 0.389)">
                    No {{ ucwords($topic->name) }} quizzes available!
                </div>
            @endforelse
        </div>
        <div class="w-100 d-flex justify-content-center mt-5">
            {{ $quizzes->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="quizModal" class="modal">
            <div class="modal-content">
                <h4>Start Quiz</h4>
                <table class="table">
                    <tr>
                        <th>Title:</th>
                        <td><span id="quizTitle"></span></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td><span id="quizDescription"></span></td>
                    </tr>
                    <tr>
                        <th>Topic:</th>
                        <td><span id="quizTopic" class="text-capitalize"></span></td>
                    </tr>
                    <tr>
                        <th>Time Limit:</th>
                        <td><span id="quizTimeLimit"></span></td>
                    </tr>
                    <tr>
                        <th>Attempt Type:</th>
                        <td><span id="quizAttemptType"></span></td>
                    </tr>
                    <tr>
                        <th>Number of Questions:</th>
                        <td><span id="quizQuestionsCount"></span></td>
                    </tr>
                </table>

                <div class="d-flex justify-content-evenly">
                    <button onclick="startQuiz()" class="btn btn-success">Start Quiz</button>
                    <button onclick="closeModal()" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // function openQuizModal(url, title, description, timeLimit, attemptType, questionsCount) {
        //     // Set the quiz details in the modal
        //     document.getElementById('quizTitle').innerText = title;
        //     document.getElementById('quizDescription').innerText = description;
        //     document.getElementById('quizTimeLimit').innerText = timeLimit!=0 ? `${timeLimit} minutes` : 'No time limit';
        //     document.getElementById('quizAttemptType').innerText = attemptType === 'multiple_attempts' ? 'Multiple attempts allowed' : 'One attempt only';
        //     document.getElementById('quizQuestionsCount').innerText = questionsCount;
        //
        //     // Store the quiz URL to start the quiz later
        //     window.quizUrl = url;
        //
        //     // Show the modal
        //     document.getElementById('quizModal').style.display = 'flex';
        // }
        function openQuizModal(url, title, description, timeLimit, attemptType, questionsCount, topicName) {
            document.getElementById('quizTitle').innerText = title;
            document.getElementById('quizDescription').innerText = description;
            document.getElementById('quizTimeLimit').innerText = timeLimit != 0 ? `${timeLimit} minutes` : 'No time limit';
            document.getElementById('quizAttemptType').innerText = attemptType === 'multiple_attempts' ? 'Multiple attempts allowed' : 'One attempt only';
            document.getElementById('quizQuestionsCount').innerText = questionsCount;
            document.getElementById('quizTopic').innerText = topicName;

            window.quizUrl = url;

            document.getElementById('quizModal').style.display = 'flex';
        }

        function startQuiz() {
            // Redirect to the quiz page when the user clicks "Start Quiz"
            window.location.href = window.quizUrl;
        }

        function closeModal() {
            // Close the modal when the user clicks "Cancel"
            document.getElementById('quizModal').style.display = 'none';
        }
    </script>
</x-app-layout>
