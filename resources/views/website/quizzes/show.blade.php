<x-app-layout>
    <div class="container mt-5">
        <h1 class="text-center mb-4">{{ $quiz->title }}</h1>

        @if ($quiz->time_limit)
            <div class="sticky-lg-top">
                <livewire:counter-component :quizId="$quiz->id" />
            </div>
        @endif

        <form action="{{ route('quiz.submit') }}" method="POST" id="quizForm">
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
            @csrf

            <div id="question-container" class="position-relative">
                @foreach ($questions as $index => $question)
                    <div class="mb-4">
                        <p class="font-weight-bold">{{ $index + 1 }}. {{ $question->question_text }}</p>

                        @foreach ($question->options as $optionIndex => $option)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_{{ $question->id }}" id="option_{{ $option->id }}" value="{{ $option->id }}">
                                <label class="form-check-label" for="option_{{ $option->id }}">
                                    {{ $optionIndex + 1 }}. {{ $option->option_text }} <!-- Numbering options -->
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- Pagination Buttons -->
            <div class="pagination-container text-center my-4">
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" id="prevBtn" onclick="goToPage(currentPage - 1)" class="btn btn-primary">Previous</button>
                    <button type="button" id="nextBtn" onclick="goToPage(currentPage + 1)" class="btn btn-primary px-4">Next</button>
                </div>
                <p id="pageIndicator" class="text-muted mt-2"></p>
            </div>

            <div class="text-center pb-5">
                <button type="submit" class="btn btn-primary btn-lg mt-4" id="submitBtn">Submit Quiz</button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    const questions = @json($questions);
    const questionsPerPage = 3;
    let currentPage = 1;

    // Function to store selected answers in localStorage
    function storeAnswer(questionId, selectedOptionId) {
        let answers = JSON.parse(localStorage.getItem('quizAnswers')) || {};
        answers[questionId] = selectedOptionId;
        localStorage.setItem('quizAnswers', JSON.stringify(answers));
    }

    // Function to retrieve answers from localStorage and preselect the options
    function loadAnswers() {
        const answers = JSON.parse(localStorage.getItem('quizAnswers')) || {};
        Object.keys(answers).forEach(questionId => {
            const selectedOptionId = answers[questionId];
            const radio = document.querySelector(`input[name="question_${questionId}"][value="${selectedOptionId}"]`);
            if (radio) {
                radio.checked = true;
            }
        });
    }

    // Function to render the questions
    function renderQuestions() {
        const start = (currentPage - 1) * questionsPerPage;
        const end = start + questionsPerPage;
        const currentQuestions = questions.slice(start, end);
        const totalPages = Math.ceil(questions.length / questionsPerPage);

        const questionContainer = document.getElementById("question-container");
        questionContainer.innerHTML = '';  // Clear existing questions

        currentQuestions.forEach((question, questionIndex) => {
            const questionElement = document.createElement('div');
            questionElement.classList.add('col-md-12', 'mb-4');
            questionElement.innerHTML = `
                <div class="card shadow-sm">
                    <div class="card-body">
                        ${question.image ? `<div class="mb-3" style="height: 15em;aspect-ratio:3/2;">
                                                <img src="{{ asset('upload_images/${question.image}') }}" class="img-fluid mt-3 h-100" alt="Question Image">
                                            </div>` : ''}
                        <p class="card-text font-weight-bold">${start + questionIndex + 1}. ${question.question_text}</p>
                        ${question.options.map((option, optionIndex) => `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_${question.id}" id="option_${option.id}" value="${option.id}">
                                <label class="form-check-label" for="option_${option.id}">
                                    ${optionIndex + 1}. ${option.option_text} <!-- Numbering options -->
                                </label>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            questionContainer.appendChild(questionElement);
        });

        // Update pagination buttons and page indicator
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageIndicator = document.getElementById('pageIndicator');

        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;

        pageIndicator.textContent = `Page ${currentPage} of ${totalPages}`;

        // Load answers from localStorage and preselect the options
        loadAnswers();
    }

    function goToPage(page) {
        const totalPages = Math.ceil(questions.length / questionsPerPage);
        if (page < 1 || page > totalPages) return;

        currentPage = page;
        renderQuestions();
    }

    // Add event listener to store the answer when a user selects an option
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', (event) => {
            const questionId = event.target.name.split('_')[1];
            const selectedOptionId = event.target.value;
            storeAnswer(questionId, selectedOptionId);
        });
    });

    document.getElementById('quizForm').addEventListener('submit', (event) => {
        event.preventDefault();

        // Submit the form
        event.target.submit();
        localStorage.removeItem('quizAnswers');
    });
    window.addEventListener('beforeunload', () => {
        localStorage.removeItem('quizAnswers');
    });
    window.addEventListener('popstate', () => {
        localStorage.removeItem('quizAnswers');
    });
    const originalPushState = history.pushState;
    history.pushState = function(...args) {
        originalPushState.apply(this, args);
        localStorage.removeItem('quizAnswers');
    };

    const originalReplaceState = history.replaceState;
    history.replaceState = function(...args) {
        originalReplaceState.apply(this, args);
        localStorage.removeItem('quizAnswers');
    };
    document.addEventListener('DOMContentLoaded', () => {
        // if (window.location.href.includes('quiz.submit')) {
            localStorage.removeItem('quizAnswers');
        // }
    });
    // Initial render
    renderQuestions();
</script>
