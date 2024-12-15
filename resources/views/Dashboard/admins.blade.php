<x-dashboard>
    @section('page_title', $title)

    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            padding: 10px;
        }
    </style>
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
            width: 30%;
            height: 30%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
        }

        .modal-buttons button {
            margin: 5px;
        }
    </style>

    <div class="mb-5 d-flex flex-column gap-2 flex-wrap">
       <form action="{{route('users.search')}}" method="POST">
        @csrf
        <div class="d-flex mb-3">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchterm">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
       </form>

        @foreach($admins as $admin)
        <div class="card mb-3 text-wrap">
            <div class="d-flex overflow-hidden">

                <!-- Image column -->
                <div class="col-4 d-flex align-items-center">
                    @if($admin->image)
                        <img src="{{ asset('upload_images/' . $admin->image) }}" class="img-fluid rounded-start profile-img" alt="Admin image">
                    @else
                        <img src="{{ asset('upload_images/admins_images/profile.PNG') }}" class="img-fluid rounded-start profile-img" alt="default profile image">
                    @endif
                </div>

                <!-- Admin details column -->
                <div class="col-8 d-flex flex-column">
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-between align-content-center">
                            <h5 class="card-title">{{$admin->name}}</h5>
                            <div class="d-flex gap-4">
                                @if (Auth::user()->role === 'super_admin')
                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        <i class="fa-regular fa-trash-can text-danger fs-5"></i>
                                    </button>
                                </form>
                                @endif

                                @if (Auth::user()->id === $admin->id || Auth::user()->role === 'super_admin')
                                <form action="{{ route('admins.edit', $admin->id) }}" method="GET">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        <i class="fa-regular fa-pen-to-square fs-5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>

                        <p class="card-text">{{$admin->email}}</p>
                        <p class="card-text">{{ucwords(str_replace("_"," ",$admin->role))}}</p>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

        @if ($admins->count() == 0)
        <div class="alert alert-warning mt-5 text-center" role="alert">
            No Admins!
        </div>
        @endif

        <!-- Confirmation Modal -->
        <div id="confirmDeleteModal" class="modal" style="display:none;">
            <div class="modal-content">
                <h5 class="fs-4">Are you sure you want to delete this admin?</h5>
                <p class="fs-5">This action can't be undone.</p>
                <div class="modal-buttons">
                    <button id="confirmDeleteBtn" class="btn btn-danger px-3 py-2 fs-5">Delete</button>
                    <button id="cancelDeleteBtn" class="btn btn-primary px-3 py-2 fs-5">Cancel</button>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the form and modal elements
            const deleteForm = document.querySelector('form[action="{{ route('admins.destroy', $admin->id) }}"]');
            const modal = document.getElementById('confirmDeleteModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

            // Show the modal when the delete button is clicked
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();  // Prevent immediate form submission
                modal.style.display = 'flex'; // Show the modal
            });

            // Confirm deletion
            confirmDeleteBtn.addEventListener('click', function() {
                deleteForm.submit(); // Submit the form after confirmation
            });

            // Cancel deletion
            cancelDeleteBtn.addEventListener('click', function() {
                modal.style.display = 'none'; // Close the modal
            });
        });
    </script>

</x-dashboard>
