@extends('layouts.master')

@section('title', 'Show All Categories')

@section('content')
    <h5># Show All Categories</h5>

    <div class="row mt-5 mb-5">

        <div class="col-md-12">
            <a class="btn btn-primary" href="/create">Create New Category</a>

            @if($errors->any())
                <div class="alert alert-danger mt-3" role="alert" class="text-center">{{$errors->first()}}</div>
            @endif

            @if(session()->has('successMessage'))
                <div class="alert alert-success mt-3" role="alert"
                     class="text-center">{{session()->get('successMessage')}}</div>
            @endif

            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Parent Category</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($allCategories as $key => $cat)
                        <tr>
                            <th scope="row">{{ $cat->id }}</th>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->parentCategory ? $cat->parentCategory->name : '---' }}</td>
                            <td>

                                <form method="POST" action="/destroy/{{ $cat->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <div class="form-group">
                                        <a class="btn btn-primary" href="/edit/{{ $cat->id }}">Edit</a>
                                        <input type="submit" class="btn btn-danger delete-category" value="Delete">
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $allCategories->links() !!}
            </div>

        </div>

    </div>
@endsection

@section('externalJs')

    <script>
        $('.delete-category').click(function (e) {
            e.preventDefault() // Don't post the form, unless confirmed
            if (confirm('Are you sure?')) {
                // Post the form
                $(e.target).closest('form').submit() // Post the surrounding form
            }
        });
    </script>

@endsection