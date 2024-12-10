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
                    <td>{{ $quiz->creator["name"] }}</td>
                    <td>{{ ucwords(str_replace("_"," ",$quiz->quiz_type)) }}</td>
                    <td class="d-flex justify-content-evenly px-3 gap-3">
                        <a href="{{ route("quizzes.participants", $quiz->id) }}"> <i class="fa-solid fa-users"></i></a>
                        @if(auth()->check() && (auth()->user()->role == "super_admin" || auth()->user()->id == $quiz->created_by))
                            <a href="{{ route('questions.index', $quiz->id) }}"><i class="fa-regular fa-eye"></i></a>
                            <form action="{{ route('quiz.delete', $quiz->id) }}" method="POST" style="display:inline-block;" id="deleteForm{{ $quiz->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $quiz->id }})">
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
    </div>
    @section("js_files")
        <script>
            function confirmDelete(quizId) {
                // JavaScript confirmation dialog
                if (confirm('Are you sure you want to delete this quiz?')) {
                    // If user clicks "OK", submit the form
                    document.getElementById('deleteForm' + quizId).submit();
                }
            }

            // function confirmDelete(quizId) {
            //     // SweetAlert2 confirmation dialog
            //     Swal.fire({
            //         title: 'Are you sure?',
            //         text: 'This action cannot be undone!',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#d33',
            //         cancelButtonColor: '#3085d6',
            //         confirmButtonText: 'Yes, delete it!'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             // If user clicks "Yes", submit the form
            //             document.getElementById('deleteForm' + quizId).submit();
            //         }
            //     });
            // }
        </script>
    @endsection
</x-dashboard>
