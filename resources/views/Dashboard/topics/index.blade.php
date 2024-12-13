<x-dashboard>
  @section('page_title', 'Topics')
  <div class=" w-75 container text-center mt-5">
{{--      @if (session('success'))--}}
{{--          <div class="alert alert-success" id="alert-del">--}}
{{--              {{ session('success') }}--}}
{{--          </div>--}}
{{--      @endif--}}
      @if ($topics->count()==0)
          <div class="alert alert-warning mt-5" role="alert">
              No Topics !
          </div>
      @else
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope=" col " class="text-left">Title</th>
          <th scope=" col "></th>
          <th scope=" col "></th>
        </tr>
      </thead>
      <tbody>
        @php
        $countTopics=1;
        @endphp
        @foreach($topics as $topic)
        <tr>
          <th scope="row">{{$countTopics++}}</th>
          <td class="text-left">{{ucwords($topic->name)}}</td>
          <td>
              <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" id="deleteForm{{ $topic->id }}" data-topic-id="{{ $topic->id }}">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="delete-btn" style="background: none; border: none; cursor: pointer;">
                      <i class="fa-regular fa-trash-can text-danger"></i>
                  </button>
              </form>



          </td>
          <td>
            <form action="{{ route('topics.edit', $topic->id) }}" method="GET">
              <button type="submit" style="background: none; border: none; cursor: pointer;">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
            </form>


          </td>
        </tr>
        @endforeach

      </tbody>
    </table>

      @endif
    <form action="{{route('topics.create')}}" method="GET">
      <button type="submit" class="btn btn-primary mt-3 form-control">Add</button>
    </form>
{{--          <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">--}}
{{--              <div class="modal-dialog">--}}
{{--                  <div class="modal-content">--}}
{{--                      <div class="modal-header">--}}
{{--                          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>--}}
{{--                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                      </div>--}}
{{--                      <div class="modal-body">--}}
{{--                          Are you sure you want to delete this topic?--}}
{{--                      </div>--}}
{{--                      <div class="modal-footer">--}}
{{--                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>--}}
{{--                          <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--          </div>--}}

  </div>
    @section("js_files")
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Select all delete forms
                const deleteForms = document.querySelectorAll('form[id^="deleteForm"]');

                // Attach click events to each delete button
                deleteForms.forEach((deleteForm) => {
                    const deleteBtn = deleteForm.querySelector('.delete-btn');

                    deleteBtn.addEventListener('click', function (e) {
                        e.preventDefault(); // Prevent immediate form submission

                        // Create confirmation dialog
                        const confirmDialog = document.createElement('div');
                        confirmDialog.innerHTML = `
                    <div class="modal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:1000;">
                        <div class="modal-content d-flex justify-center align-items-center flex-column p-5" style="background:white; border-radius:5px; text-align:center; margin:auto; width:25%; max-width:90%;">
                            <p class="fs-5">Are you sure you want to delete this topic?</p>
                            <p class="fs-6">Taking this action will lead to all related quiz being deleted.</p>
                            <p class="fs-6">Click "Delete" to confirm</p>
                            <div class="d-flex justify-content-center gap-3 mt-3">
                                <button class="confirm-delete btn btn-danger">Delete</button>
                                <button class="cancel-delete btn btn-primary">Cancel</button>
                            </div>
                        </div>
                    </div>
                `;

                        // Add the confirmation dialog to the document body
                        document.body.appendChild(confirmDialog);

                        // Confirm delete button
                        const confirmDeleteBtn = confirmDialog.querySelector('.confirm-delete');
                        confirmDeleteBtn.addEventListener('click', function () {
                            deleteForm.submit(); // Submit the associated delete form
                        });

                        // Cancel delete button
                        const cancelDeleteBtn = confirmDialog.querySelector('.cancel-delete');
                        cancelDeleteBtn.addEventListener('click', function () {
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
