@extends('layouts.master')

@section('title', 'Edit Category')

@section('content')
    <h5># Edit Category : {{ $category->name }}</h5>

    <div class="row mt-5">

        <div class="col-md-4">

            <div id="loaderDiv" class="text-center" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div id="successDiv" class="alert alert-success" role="alert" class="text-center" style="display: none;">
                Category updated successfully
            </div>

            <div id="errorDiv" class="alert alert-danger" role="alert" class="text-center" style="display: none;"></div>

            <form id="editCategoryForm">

                <input id="categoryID" type="hidden" value="{{ $category->id }}">

                <a class="btn btn-primary" href="/">All Categories</a>

                <div class="form-group mt-3">
                    <label>Categories list :</label>
                    <select id="mainCategories" class="form-control categories" name="categories_list[]"
                            onchange="getSubCategories(this)">
                        <option value="">--- Select Category ---</option>
                        @foreach($allParentCategoriesList[0] as $key => $cat)
                            <option value="{{ $cat['id'] }}" {{ isset($cat['selected']) && $cat['selected'] == true ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="subCategoriesDiv">

                    @foreach(array_slice($allParentCategoriesList, 1) as $index => $subCat)

                        <select name="categories_list[]" class="form-control categories mt-4"
                                onchange="getSubCategories(this)">
                            <option value="">--- Select Sub Category ---</option>
                            @foreach($subCat as $key => $cat)
                                <option value="{{ $cat['id'] }}" {{ isset($cat['selected']) && $cat['selected'] == true ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                            @endforeach
                        </select>

                    @endforeach

                </div>

                <div class="form-group mt-5">
                    <label>Category Name:</label>
                    <input type="text" class="form-control" name="category_name" value="{{ $category->name }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>

            </form>

        </div>

    </div>
@endsection

@section('externalJs')

    <script>

        var subCategoriesDiv = $('#subCategoriesDiv');
        var loaderDiv = $('#loaderDiv');
        var successDiv = $('#successDiv');
        var errorDiv = $('#errorDiv');

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
        $('#editCategoryForm').submit(function (event) {

            var categoryID = $('#categoryID').val();
            loaderDiv.show();
            successDiv.hide();
            errorDiv.hide();

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
                url: "/update/" + categoryID, // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                cache: false,
                beforeSend: function () {
                },
                success: function (res) {
                    loaderDiv.hide();
                    if (res.status) {
                        successDiv.show();
                    } else {
                        errorDiv.show();
                        errorDiv.html(res.message);
                    }
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
