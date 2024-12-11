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
                <!-- Questions will be dynamically rendered here -->
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
    let userAnswers = {};

    function renderQuestions() {
        const start = (currentPage - 1) * questionsPerPage;
        const end = start + questionsPerPage;
        const currentQuestions = questions.slice(start, end);
        const totalPages = Math.ceil(questions.length / questionsPerPage);

        const questionContainer = document.getElementById("question-container");
        questionContainer.innerHTML = '';  // Clear existing questions

        currentQuestions.forEach(question => {
            const questionElement = document.createElement('div');
            questionElement.classList.add('col-md-12', 'mb-4');
            questionElement.innerHTML = `
                <div class="card shadow-sm">
                    <div class="card-body">
                        ${question.image ? `<div class="mb-3" style="height: 15em;aspect-ratio:3/2;">
                                                <img src="{{ asset('upload_images/${question.image}') }}" class="img-fluid mt-3 h-100" alt="Question Image">
                                            </div>` : ''}
                        <p class="card-text font-weight-bold">${question.question_text}</p>
                        ${question.options.map(option => `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_${question.id}" id="option_${option.id}" value="${option.id}"
                                    ${userAnswers[question.id] === option.id ? 'checked' : ''}
                                    onchange="saveAnswer(${question.id}, ${option.id})">
                                <label class="form-check-label" for="option_${option.id}">
                                    ${option.option_text}
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
    }

    function goToPage(page) {
        const totalPages = Math.ceil(questions.length / questionsPerPage);
        if (page < 1 || page > totalPages) return;

        currentPage = page;
        renderQuestions();
    }

    function saveAnswer(questionId, optionId) {
        userAnswers[questionId] = optionId;
    }

    document.getElementById('quizForm').addEventListener('submit', (event) => {
        event.preventDefault();

        // Create hidden input fields for each answer
        Object.entries(userAnswers).forEach(([questionId, optionId]) => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `question_${questionId}`;
            hiddenInput.value = optionId;
            event.target.appendChild(hiddenInput);
        });

        // Submit the form
        event.target.submit();
    });


    // Initial render
    renderQuestions();

</script>

<style>
    /* Remove previous bottom margin styling */
    #question-container {
        min-height: 300px; /* Ensure consistent height */
    }

    .pagination-container {
        position: relative;
        z-index: 10;
    }
</style>
