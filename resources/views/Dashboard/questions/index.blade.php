<x-dashboard>
    @section('page_title', 'Questions for Quiz: ' . $quiz->title)

    <form method="POST" enctype="multipart/form-data" action="{{route("quiz.update")}}" class="container-fluid d-flex flex-wrap  justify-content-between mt-5 bg-white p-5 rounded-2">
        @csrf

        <div class="my-3 d-flex flex-column text-center border-bottom pb-2 col-md-12">
            <label class="form-label mx-5">Quiz Type</label>

            <div>
                <div class="form-check form-check-inline my-2">
                    <input class="form-check-input" type="radio" name="quiz_type" id="inlineRadio2" value="once_attempt" {{ $quiz->quiz_type == 'once_attempt' ? 'checked' : '' }}>
                    <label class="form-check-label" for="inlineRadio2">one attempt</label>
                </div>
                <div class="form-check form-check-inline me-5 my-2">
                    <input class="form-check-input" type="radio" name="quiz_type" id="inlineRadio1" value="multiple_attempts" {{ $quiz->quiz_type== 'multiple_attempts' ? 'checked' : '' }}>
                    <label class="form-check-label" for="inlineRadio1">multiple attempts</label>
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-5">
            <div class="mb-3 col-md-12">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="title" placeholder="Enter Quiz Title"
                       value="{{$quiz->title}}" autofocus>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Topic</label>
                <select type="text" class="form-control" name="topic_id">
                    @if(count($topics))
                        @foreach($topics as $topic)
                            <option value="{{$topic["id"]}}" class="topic" {{ $quiz->topic_id == $topic['id'] ? 'selected' : '' }}>{{$topic["name"]}}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
        <div class="mb-3 col-md-5">
           <div class="mb-3 col-md-12">
            <label class="form-label">Quiz Picture (optional)</label>
            <input type="file" class="form-control" name="image">
          </div>
            <div class="col-md-12 mb-3">
                @if(isset($quiz->image))
                    <img src="{{ asset('upload_images/' . $quiz->image) }}" style="max-width: 100px;">
                @else
                    <p class="text-secondary">No Image Is Set For This Quiz</p>
                @endif
            </div>
        </div>
        <div class="mb-3 col-md-5">
            <label class="form-label">Time Limit</label>
            <input type="number" class="form-control" name="time_limit" min="0" value="{{$quiz->time_limit}}">
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" placeholder="Enter Quiz Description" class="form-control">{{$quiz->description}}</textarea>
        </div>


        <input type="hidden" value="{{$quiz->id}}" name="id">

        <div class="container border-top" >
            <label class=" fs-3 text-primary mt-3 ">Add Questions</label>

        </div>
        <div id="questions-container" class="col-md-12">

        </div>
        <div class="col-md-12">
            <button type="button" id="add-question-btn" class="btn btn-primary my-1">Add Question</button>

        </div>

        <button type="submit" class="btn btn-primary mt-3 form-control" id="create_quiz">Update</button>
    </form>



    <div class="container mt-5">
        <h2>Questions List</h2>
        @if ($quiz->questions->count()==0)
            <div class="alert alert-warning mt-5 text-center" role="alert">
                No Questions !
            </div>
        @else
        <table class="table table-bordered">
            @php
                $NumberOfQuestions=0
            @endphp
            <thead>
                <tr>
                    <th>Question No.</th>
                    <th>Question Text</th>
                    <th>Question Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quiz->questions as $question)
                    <tr>
                        <td>{{ ++$NumberOfQuestions }}</td>
                        <td>{{ $question->question_text }}</td>
                        <td>
                            {{$question->question_type=="multiple_choice"?ucwords(str_replace("_"," ",$question->question_type)):implode("/", array_map('ucfirst', explode("_", $question->question_type)))}}
                        </td>

                        <td class="d-flex justify-content-evenly align-items-center">
                            @if(auth()->check() && auth()->user()->role=="super_admin"||auth()->user()->id==$quiz->created_by)

                            <a href="{{ route('questions.edit', [$quiz->id, $question->id]) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                                <form id="deleteForm" action="{{ route('questions.destroy', [$quiz->id, $question->id]) }}" method="POST" style="display:inline-block;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">
                                        <i class="fa-regular fa-trash-can text-danger"></i>
                                    </button>
                                </form>
                            @else
                                <p class="text-secondary">No Action Can Be Took</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@include("layouts.questions")

@section("js_files")
        <script src="{{asset("assets/js/add_questions.js")}}"></script>

            <script>
                function confirmDelete(quizId) {
                    // JavaScript confirmation dialog
                    if (confirm('Are you sure you want to delete this quiz?')) {
                        // If user clicks "OK", submit the form
                        document.getElementById('deleteForm' + quizId).submit();
                    }
                }
            </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Use event delegation to handle delete button clicks
                document.addEventListener('click', function(e) {
                    // Check if the clicked element is a delete button
                    const deleteBtn = e.target.closest('.delete-btn');
                    if (deleteBtn) {
                        e.preventDefault();

                        // Find the parent form of the clicked delete button
                        const deleteForm = deleteBtn.closest('.delete-form');

                        // Create confirmation dialog
                        const confirmDialog = document.createElement('div');
                        confirmDialog.innerHTML = `
                <div class="modal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:1000;">
                    <div class="modal-content d-flex flex-column justify-center align-items-center gap-3 p-5" style="background:white; border-radius:5px; text-align:center; margin:auto; width:25%; min-height:25%">
                        <p class="fs-5">Are you sure you want to delete this question?</p>
                        <p class="fs-6 text-muted">Click "Delete" to confirm</p>
                        <div>
                            <button class="confirm-delete btn btn-danger" style="margin:0 10px; padding:10px 20px;">Delete</button>
                            <button class="cancel-delete btn btn-primary" style="margin:0 10px; padding:10px 20px;">Cancel</button>
                        </div>
                    </div>
                </div>
            `;

                        // Add to body
                        document.body.appendChild(confirmDialog);

                        // Confirm delete button
                        const confirmDeleteBtn = confirmDialog.querySelector('.confirm-delete');
                        confirmDeleteBtn.addEventListener('click', function() {
                            // Submit the associated form
                            deleteForm.submit();
                        });

                        // Cancel delete button
                        const cancelDeleteBtn = confirmDialog.querySelector('.cancel-delete');
                        cancelDeleteBtn.addEventListener('click', function() {
                            // Remove the confirmation dialog
                            document.body.removeChild(confirmDialog);
                        });
                    }
                });
            });
        </script>
@endsection
</x-dashboard>
