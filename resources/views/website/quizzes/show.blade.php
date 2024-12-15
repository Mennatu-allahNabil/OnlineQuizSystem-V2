<x-app-layout>
    <div class="container mt-5">
        <h1 class="text-center mb-4">{{ $quiz->title }}</h1>

        @if ($quiz->time_limit)
            <livewire:counter-component :quizId="$quiz->id" />
        @endif

        <form action="{{ route('quiz.submit') }}" method="POST" id="quizForm">
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
            @csrf

            <div id="question-container">

            </div>

            <!-- Pagination and Submit Section -->
            <div class="pagination-submit-container">
                <div class="pagination-controls d-flex justify-content-between align-items-center mb-3">
                    <button type="button" id="prevBtn" onclick="goToPage(currentPage - 1)" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>

                    <div class="page-indicator text-muted">
                        Page <span id="currentPageDisplay">1</span> of <span id="totalPagesDisplay">1</span>
                    </div>

                    <button type="button" id="nextBtn" onclick="goToPage(currentPage + 1)" class="btn btn-outline-primary px-4">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="text-center pb-4">
                    <button type="submit" class="btn btn-primary btn-lg mt-2" id="submitBtn">Submit Quiz</button>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>

<script>
    const questions = @json($questions);  // Pass questions to JavaScript
    let answers = JSON.parse(localStorage.getItem('quizAnswers')) || {};  // Retrieve saved answers
    const questionsPerPage = 3;
    let currentPage = 1;

    function renderQuestions() {
        const start = (currentPage - 1) * questionsPerPage;
        const end = start + questionsPerPage;
        const currentQuestions = questions.slice(start, end);
        const totalPages = Math.ceil(questions.length / questionsPerPage);

        const questionContainer = document.getElementById("question-container");
        questionContainer.innerHTML = '';  // Clear existing questions

        currentQuestions.forEach((question, questionIndex) => {
            const globalQuestionNumber = start + questionIndex + 1;
            const questionElement = document.createElement('div');
            questionElement.classList.add('col-md-12', 'mb-4');
            questionElement.innerHTML = `
            <div class="card shadow-sm">
                <div class="card-body">
                    ${question.image ? `<div class="mb-3" style="height: 20em;">
                                            <img src="{{ asset('upload_images/${question.image}') }}" class="img-fluid mt-3 h-100 " alt="Question Image">
                                        </div>` : ''}
                    <p class="card-text fw-bold">Q${globalQuestionNumber}. ${question.question_text}</p>
                    ${question.options.map((option, optionIndex) => {
                const optionLetter = String.fromCharCode(97 + optionIndex);  // a, b, c, etc.
                return `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question_${question.id}" id="option_${option.id}" value="${option.id}"
                                ${answers[question.id] === option.id ? 'checked' : ''}
                                onchange="saveAnswer(${question.id}, ${option.id})">
                            <label class="form-check-label" for="option_${option.id}">
                                ${optionLetter}. ${option.option_text}
                            </label>
                        </div>
                    `}).join('')}
                </div>
            </div>
        `;
            questionContainer.appendChild(questionElement);
        });

        // Update page display
        document.getElementById('currentPageDisplay').textContent = currentPage;
        document.getElementById('totalPagesDisplay').textContent = totalPages;

        // Handle Pagination buttons
        document.getElementById('prevBtn').disabled = currentPage === 1;
        document.getElementById('nextBtn').disabled = currentPage === totalPages;
    }

    function goToPage(page) {
        const totalPages = Math.ceil(questions.length / questionsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderQuestions();
    }

    function saveAnswer(questionId, optionId) {
        answers[questionId] = optionId;
        localStorage.setItem('quizAnswers', JSON.stringify(answers));
        updateFormInputs();
    }

    function updateFormInputs() {
        // Remove any existing hidden inputs for answers
        const existingInputs = document.querySelectorAll('.dynamic-answer-input');
        existingInputs.forEach(input => input.remove());

        // Create hidden inputs for all saved answers
        const form = document.getElementById('quizForm');
        Object.entries(answers).forEach(([questionId, optionId]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${questionId}]`;
            input.value = optionId;
            input.classList.add('dynamic-answer-input');
            form.appendChild(input);
        });
    }

    document.getElementById('quizForm').addEventListener('submit', (event) => {
        // Ensure all currently selected answers are added as hidden inputs
        updateFormInputs();
    });

    // Add an event listener to capture answers even if not changed via radio buttons
    document.addEventListener('DOMContentLoaded', () => {
        // Check for pre-existing selections on page load
        questions.forEach(question => {
            const selectedOption = document.querySelector(`input[name="question_${question.id}"]:checked`);
            if (selectedOption && !answers[question.id]) {
                answers[question.id] = parseInt(selectedOption.value);
                localStorage.setItem('quizAnswers', JSON.stringify(answers));
            }
        });

        // Initial render and input update
        renderQuestions();
        updateFormInputs();
    });

    // Clear answers from localStorage when the user successfully submits or exits the page
    document.getElementById('quizForm').addEventListener('submit', () => {
        localStorage.removeItem('quizAnswers');
    });

    window.addEventListener('beforeunload', () => {
        localStorage.removeItem('quizAnswers');
    });
</script>

<style>
    .pagination-controls {
        max-width: 600px;
        margin: 0 auto;
    }
    .page-indicator {
        font-size: 1rem;
    }
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
