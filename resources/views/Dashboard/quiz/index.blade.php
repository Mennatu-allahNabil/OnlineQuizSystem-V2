<x-dashboard>
    @section('page_title', 'Manage Quizzes')

    <div class="container mt-5">

        <div class="d-flex justify-content-between">
            <h2>Quizzes List</h2>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        Filter By Topic
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link  href="{{ route('quiz.index') }}">
                        All
                    </x-dropdown-link>
                    @foreach($topics as $topic)
                        <x-dropdown-link  href="{{ route('quizzes.by_topic.admin', $topic->id) }}">
                            {{ $topic->name }}
                        </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </div>
        @if (session('success'))
            <div class="alert alert-success" id="alert-del">
                {{ session('success') }}
            </div>
        @endif
        @if ($quizzes->count()==0)
            <div class="alert alert-warning mt-5 text-center" role="alert">
                No Quizzes !
            </div>
            <form action="{{route('admin.CreateQuiz')}}" method="GET">
                <button type="submit" class="btn btn-primary mt-3 form-control">Create Quiz</button>
            </form>
        @else
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Time Limit</th>
                    <th>Topic</th>
                    <th>Created By</th>
                    <th>Quiz Type</th>
                    <th>Actions</th>
                    <th>Download PDF</th> <!-- New Column for PDF -->
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>{{ $quiz->description }}</td>
                    <td>
                        @if($quiz->time_limit)
                            {{ $quiz->time_limit }} minutes
                        @else
                            None
                        @endif
                    </td>
                    <td>{{ ucwords($quiz->topic->name) }}</td>
                    <td>{{ $quiz->creator["email"] }}</td>
                    <td>{{ ucwords(str_replace("_"," ",$quiz->quiz_type)) }}</td>
                    <td class="d-flex justify-content-evenly px-3 gap-3">
                        <a href="{{ route("quizzes.participants", $quiz->id) }}"> <i class="fa-solid fa-users"></i></a>
                        @if(auth()->check() && (auth()->user()->role == "super_admin" || auth()->user()->id == $quiz->created_by))
                            <a href="{{ route('questions.index', $quiz->id) }}"><i class="fa-regular fa-eye"></i></a>
                            <form action="{{ route('quiz.delete', $quiz->id) }}" method="POST" style="display:inline-block;" id="deleteForm{{ $quiz->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="confirmDelete({{ $quiz->id }})" class="delete-btn">
                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                </button>
                            </form>
                        @else
                            <p class="text-secondary">No Action Can Be Took</p>
                        @endif
                    </td>
                    <!-- PDF Button -->
                    <td>
                        <form action="{{ route('quiz.pdf', $quiz->id) }}" method="GET">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </button>
                        </form>
                    </td>


                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </div>
    @section("js_files")
{{--        <script>--}}
{{--            // function confirmDelete(quizId) {--}}
{{--            //     // JavaScript confirmation dialog--}}
{{--            //     if (confirm('Are you sure you want to delete this quiz?')) {--}}
{{--            //         // If user clicks "OK", submit the form--}}
{{--            //         document.getElementById('deleteForm' + quizId).submit();--}}
{{--            //     }--}}
{{--            // }--}}
{{--        </script>--}}



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select all delete forms
                const deleteForms = document.querySelectorAll('form[id^="deleteForm"]');

                // Attach click events to each delete button
                deleteForms.forEach((deleteForm) => {
                    const deleteBtn = deleteForm.querySelector('.delete-btn');

                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent immediate form submission

                        // Create confirmation dialog
                        const confirmDialog = document.createElement('div');
                        confirmDialog.innerHTML = `
                <div class="modal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:1000;">
                    <div class="modal-content d-flex justify-center align-items-center gap-2 p-5" style="background:white; border-radius:5px; text-align:center;margin: auto;width: 25%;height: 25%">
                        <p class="fs-5">Are you sure you want to delete this quiz?</p>
                        <p class="fs-6">Click "Delete" to confirm</p>
                        <div>
                            <button class="confirm-delete btn-danger btn" style="margin:0 10px; padding:10px 20px; background-color:red; color:white; border:none; border-radius:3px;">Delete</button>
                            <button class="cancel-delete btn-primary btn" style="margin:0 10px; padding:10px 20px; color:white; border:none; border-radius:3px;">Cancel</button>
                        </div>
                    </div>
                </div>
            `;

                        // Add the confirmation dialog to the document body
                        document.body.appendChild(confirmDialog);

                        // Confirm delete button
                        const confirmDeleteBtn = confirmDialog.querySelector('.confirm-delete');
                        confirmDeleteBtn.addEventListener('click', function() {
                            deleteForm.submit(); // Submit the associated delete form
                        });

                        // Cancel delete button
                        const cancelDeleteBtn = confirmDialog.querySelector('.cancel-delete');
                        cancelDeleteBtn.addEventListener('click', function() {
                            document.body.removeChild(confirmDialog); // Remove the modal from the DOM
                        });
                    });
                });
            });

        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('alert-del');
                if (alert) {
                    // Set timeout to hide the alert after 5 seconds (5000 ms)
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 1000); // Remove the element after fade-out
                    }, 10000);
                }
            });
        </script>
    @endsection
</x-dashboard>
