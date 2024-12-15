<x-dashboard>

@section("page_title")
    Create Quiz
@endsection

    <form method="POST" enctype="multipart/form-data" action="{{route("quiz.store")}}" class="container-fluid d-flex flex-wrap  justify-content-between mt-5 bg-white p-5 rounded-2">
        @csrf

      <div class="my-3 d-flex flex-column text-center border-bottom pb-2 col-md-12">
          <label class="form-label mx-5">Quiz Type</label>

        <div>
            <div class="form-check form-check-inline my-2">
                <input class="form-check-input" type="radio" name="quiz_type" id="inlineRadio2" value="once_attempt" {{ old('quiz_type') == 'once_attempt' ? 'checked' : '' }}>
                <label class="form-check-label" for="inlineRadio2">one attempt</label>
            </div>
            <div class="form-check form-check-inline me-5 my-2">
                <input class="form-check-input" type="radio" name="quiz_type" id="inlineRadio1" value="multiple_attempts" {{ old('quiz_type') == 'multiple_attempts' ? 'checked' : '' }}>
                <label class="form-check-label" for="inlineRadio1">multiple attempts</label>
            </div>
        </div>
      </div>

        <div class="mb-3 col-md-5">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Quiz Title"
                   value="{{ old('title') }}" autofocus>
        </div>
        <div class="mb-3 col-md-5">
            <label class="form-label">Quiz Picture (optional)</label>
            <input type="file" class="form-control" name="image">
        </div>
        <div class="mb-3 col-md-5">
            <label class="form-label">Topic</label>
            <select type="text" class="form-control" name="topic_id">
                @if(count($topics))
                    @foreach($topics as $topic)
                        <option value="{{$topic["id"]}}" class="topic" {{ old('topic_id') == $topic['id'] ? 'selected' : '' }}>{{ucwords($topic["name"])}}</option>
                    @endforeach
                @endif

            </select>
        </div>
        <div class="mb-3 col-md-5">
            <label class="form-label">Time Limit</label>
            <input type="number" class="form-control" name="time_limit" min="0" value="{{old("time_limit","")}}">
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label">Description</label>
           <textarea name="description" rows="3" placeholder="Enter Quiz Description" class="form-control">{{old("description","")}}</textarea>
        </div>


    <div class="container border-top" >
        <label class=" fs-3 text-primary mt-3 ">Create Questions</label>

    </div>
    <div id="questions-container" class="col-md-12">
        @foreach (old('questions', []) as $index => $question)
            <div class="py-3 my-3 col-md-12 border-bottom question">
                <div class="question-title fw-bolder my-1 fs-4 d-flex justify-content-between">
                    <span>Question <span class="question-number">{{ $index + 1 }}</span>:</span>
                    <span>
                    <button type="button" style="background: none; border: none; cursor: pointer;" class="delete-question-btn">
                        <i class="fa-regular fa-trash-can text-danger"></i>
                    </button>
                </span>
                </div>
                <div class="form-group mx-2 my-2 col-md-7 d-flex flex-wrap">
                    <label class="fw-bold col-md-2 my-1">Question Type</label>
                    <select name="questions[{{ $index }}][type]" class="form-control col-md-3 question-type">
                        <option value="true_false" {{ $question['type'] === 'true_false' ? 'selected' : '' }}>True/False</option>
                        <option value="multiple_choice" {{ $question['type'] === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                    </select>
                </div>
                <div class="form-group mx-2 my-2 col-md-12">
                    <label class="fw-bold col-md-5">Question Text</label>
                    <input class="form-control col-md-12" name="questions[{{ $index }}][text]" value="{{ $question['text'] }}">
                </div>
                <div class="form-group mx-2 my-2 col-md-12">
                    <label class="fw-bold col-md-5">Question Picture (optional)</label>
                    <input type="file" class="form-control" name="questions[{{ $index }}][image]">
                </div>
                <div class="form-group mx-2 my-2">
                    <label class="fw-bold my-1">Question Options</label>
                    <div id="options">
                        @if ($question['type'] === 'true_false')
                            <div class="form-group">
                                <input type="hidden" name="questions[{{ $index }}][options][]" value="True">
                                <input type="hidden" name="questions[{{ $index }}][options][]" value="False">
                            </div>
                            <div class="form-group my-2">
                                <label>Correct Option</label>
                                <select class="form-control" name="questions[{{ $index }}][is_correct_number]">
                                    <option value="1" {{ $question['is_correct_number'] == 1 ? 'selected' : '' }}>True</option>
                                    <option value="2" {{ $question['is_correct_number'] == 2 ? 'selected' : '' }}>False</option>
                                </select>
                            </div>
                        @elseif ($question['type'] === 'multiple_choice')
                            @foreach ($question['options'] as $optionIndex => $option)
                                <div class="form-group my-2">
                                    <label>Option {{ $optionIndex + 1 }}:</label>
                                    <input type="text" name="questions[{{ $index }}][options][]" class="form-control" value="{{ $option }}">
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label>Correct Option</label>
                                <input type="number" min="1" max="4" class="form-control" name="questions[{{ $index }}][is_correct_number]" value="{{ $question['is_correct_number'] }}">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
<div class="col-md-12">
    <button type="button" id="add-question-btn" class="btn btn-primary my-1">Add Question</button>

</div>
        <input type="hidden" value="{{auth()->user()->id}}" name="created_by">

        <button type="submit" class="btn btn-primary mt-3 form-control" id="create_quiz">Create</button>
    </form>
@include("layouts.questions")


    @section("js_files")
        <script src="{{asset("assets/js/add_questions.js")}}"></script>
{{--        <script src="{{asset("assets/js/quiz-form-validation.js")}}"></script>--}}

        <script>
            // document.addEventListener("DOMContentLoaded",addQuestion);
        </script>
        <script>
            let topics=document.getElementsByClassName("topic");
            if(topics.length===0){
                let create_quiz_btn=document.getElementById("create_quiz");
                let topic_id=document.getElementById("topic_id");
                create_quiz_btn.disabled=true;
                topic_id.disabled=true;
            }
        </script>
    @endsection
</x-dashboard>
