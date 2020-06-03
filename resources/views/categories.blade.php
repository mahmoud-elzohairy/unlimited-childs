<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="_token" content="{{csrf_token()}}"/>

    <title>Unlimited Sub Categories Demo</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          crossorigin="anonymous">

</head>
<body>
<div class="container">

    <h3 class="text-center mt-3">Unlimited Sub Categories Demo</h3>
    <div class="row mt-5">
        <div class="col-md-4">

            <div id="loaderDiv" class="text-center" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="form-group">
                <label>Categories list :</label>
                <select class="form-control" name="categories_list" onchange="getSubCategories(this)">
                    <option value="">--- Select Category ---</option>
                    @foreach($parentCategories as $key => $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="subCategoriesDiv"></div>

        </div>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>

<script>

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    });

    var subCategoriesDiv = $('#subCategoriesDiv');
    var loaderDiv = $('#loaderDiv');

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

                        var subCats = `<select class="form-control mt-4" onchange="getSubCategories(this)">`;
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

</script>

</body>
</html>