<!DOCTYPE html>
<html>
<head>
    <title>Laravel Application</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <style>
        .table-borderless tbody tr td, .table-borderless tbody tr th,
        .table-borderless thead tr th {
            border: none;

        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">

        <div class="form-group row add">
            <div class="col-md-8">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter some name required">
                <p class="error text-center alert alert-danger hidden">
                </p>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit" id="add"></button>
            </div>
        </div>

        {{ csrf_field() }}
        <div class="table-responsive text-center">
            <table class="table table-borderless" id="table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}}}</td>
                        <td>{{$item->name}}}}</td>
                        <td>
                            <button class="edit-modal btn btn-info" data-id="{{$item->id}}" data-name="{{$item->name}}">
                                <span class="glyphicon glyphicon-edit">
                                    edit
                                </span>
                            </button>
                            <button class="delete-modal btn btn-danger" data-id="{{$item->id}}"
                                    data-name="{{$item->name}}">
                                <span class="glyphicon glyphicon-trash">
                                    delete
                                </span>
                            </button>
                        </td>
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" class="close">X</button>
                                    </div>
                                    <h4 class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <form action="" class="form-horizontal">
                                        <div class="form-group"><label for="id" class="control-label col-sm-2"> </label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="fid"
                                                                          disabled></div>
                                        </div>
                                        <div class="form-group"><label for="name"
                                                                       class="control-label col-sm-2"></label>
                                            <div class="col-sm-10"><input type="text" class="form-control" id="n">
                                            </div>
                                        </div>

                                        <div class="deleteContent">Are you Sure you want to delete<span
                                                    class="dname"> </span>?<span class="hidden did"></span></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn actionBtn" data-dismiss="modal"><span
                                                        class="glyphicon"
                                                        id="footer_action_button"></span>
                                            </button>

                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><span
                                                        class="glyphicon glyphicon-remove"></span> Close
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
</div>

</body>
</html>
<script>
    $(document).on('click', '.edit-modal', function () {
        $('#footer_action_button').text(" Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('edit');
        $('.modal-title').text('Edit');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        $('#fid').val($(this).data('id'));
        $('#n').val($(this).data('name'));
        $('#myModal').modal('show');
    });

    $(document).on('click', '.delete-modal', function () {
        $('#footer_action_button').text(" Delete");
        $('#footer_action_button').removeClass('glyphicon-check');
        $('#footer_action_button').addClass('glyphicon-trash');
        $('.actionBtn').removeClass('btn-success');
        $('.actionBtn').addClass('btn-danger');
        $('.actionBtn').addClass('delete');
        $('.modal-title').text('Delete');
        $('.did').text($(this).data('id'));
        $('.deleteContent').show();
        $('.form-horizontal').hide();
        $('.dname').html($(this).data('name'));
        $('#myModal').modal('show');
    });

    $('.modal-footer').on('click', '.edit', function () {
        $.ajax({
            type: 'post',
            url: '/editItem',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $("#fid").val(),
                'name': $('#n').val()
            },
            success: function (data) {
                $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "' ><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
            }

        });
    });

    $("#add").click(function () {
        $.ajax({
            type: 'post',
            url: '/addItem',
            data: {
                '_token': $('input[name=_token]').val(),
                'name': $('input[name=name]').val()
            },
            success: function (data) {
                if ((data.errors)) {
                    $('.error').removeClass('hidden');
                    $('.error').text(data.errors.name);
                }
                else {
                    $('.error').addClass('hidden');
                    $('#table').append("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-name='" + data.name + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
                }
            },
        });
        $('#name').val('');
    });


    $('.modal-footer').on('click', '.delete', function() {
        $.ajax({
            type: 'post',
            url: '/deleteItem',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': $('.did').text()
            },
            success: function(data) {
                $('.item' + $('.did').text()).remove();
            }

        });
    });


</script>