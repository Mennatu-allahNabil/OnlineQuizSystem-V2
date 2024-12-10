<x-dashboard>
  @section('page_title', 'Topics')
  <div class=" w-75 container text-center mt-5">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope=" col ">Title</th>
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
          <td>{{ucwords($topic->name)}}</td>
          <td>
              <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" id="deleteForm{{ $topic->id }}">
                  @csrf
                  @method('DELETE')
                  <button type="button" style="background: none; border: none; cursor: pointer;" onclick="confirmDelete({{ $topic->id }})">
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
    <form action="{{route('topics.create')}}" method="GET">
      <button type="submit" class="btn btn-primary mt-3 form-control">Add</button>
    </form>
    @if ($topics->count()==0)
    <div class="alert alert-warning mt-5" role="alert">
      No Topics !
    </div>
    @endif
  </div>
    @section("js_files")
        <script>
            function confirmDelete(quizId) {
                if (confirm('Are you sure you want to delete this quiz?')) {
                    document.getElementById('deleteForm' + quizId).submit();
                }
            }
        </script>
    @endsection

</x-dashboard>
