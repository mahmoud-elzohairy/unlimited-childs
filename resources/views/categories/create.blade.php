@extends('layouts.master')

@section('title', 'Create New Category')

@section('content')
    <h5># Create New Category</h5>

    <div class="row mt-5">

        <div class="col-md-4">

            <div id="loaderDiv" class="text-center" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div id="successDiv" class="alert alert-success" role="alert" class="text-center" style="display: none;">
                Category added successfully
            </div>

            <form id="addCategoryForm">

                <a class="btn btn-primary" href="/">All Categories</a>

                <div class="form-group mt-3">
                    <label>Categories list :</label>
                    <select id="mainCategories" class="form-control categories" name="categories_list[]"
                            onchange="getSubCategories(this)">
                        <option value="">--- Select Category ---</option>
                        @foreach($parentCategories as $key => $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="subCategoriesDiv"></div>

                <div class="form-group mt-5">
                    <label>Category Name:</label>
                    <input type="text" class="form-control" name="category_name" placeholder="Enter new category">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>

            </form>

        </div>

    </div>
@endsection

@section('externalJs')

    <script>

        var subCategoriesDiv = $('#subCategoriesDiv');
        var loaderDiv = $('#loaderDiv');
        var successDiv = $('#successDiv');

        function getSubCategories(object) {

            var selectedText = object.options[object.selectedIndex].innerHTML;
            var parentID = $(object).parent().attr('id');
            loaderDiv.show();
            $(object).nextAll().remove();

            if (parentID != 'subCategoriesDiv')
                subCategoriesDiv.empty();

            if (object.value != '' && object.value != null) {

                $.ajax({
                    url: "{{ url('/get-sub-categories?id=') }}" + object.value,
                    method: 'get',
                    success: function (response) {

                        loaderDiv.hide();

                        if (response.data.sub_categories.length > 0) {

                            var subCats = `<select name="categories_list[]" class="form-control categories mt-4" onchange="getSubCategories(this)">`;
                            subCats += `<option value="">--- Select Sub Category ---</option>`;

                            response.data.sub_categories.forEach(function (category) {
                                subCats += `<option value="${category.id}">${category.name}</option>`;
                            });

                            subCats += `</select>`;
                            subCategoriesDiv.append(subCats);
                        }
                    }
                });

            } else {
                loaderDiv.hide();
            }
        }

        // process the form
        $('#addCategoryForm').submit(function (event) {

            loaderDiv.show();
            var categoriesList = [];
            $.each($(".categories option:selected"), function () {
                var val = $(this).val();
                if (val != '')
                    categoriesList.push(val);
            });

            var formData = {
                'name': $('input[name=category_name]').val(),
                'categories_list': JSON.stringify(categoriesList),
            };

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: "{{ url('/store') }}", // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                cache: false,
                beforeSend: function () {
                },
                success: function (res) {
                    loaderDiv.hide();
                    successDiv.show();
                    $(".categories:last").append(`<option value="${res.data.id}">${res.data.name}</option>`);
                },
                error: function (error) {
                    console.log('error:::', error);
                }
            });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    </script>


@endsection
