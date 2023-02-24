@extends('layouts.apps')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of Employees</div>

                <div class="card-body">
                    <a href="#" class="btn btn-sm btn-warning mb-2" href="javascript:void(0)" id="createNewEmployee">Add Employee</a>
                    <table id="tbl_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="employeeForm" name="employeeForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone_nr" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone_nr" name="phone_nr" placeholder="e.g, +61 491 570 156" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" placeholder="e.g, jack.smith@gmx.com" value="" maxlength="50" required="">
                        </div>
                    </div>


                    <div class="col-sm-offset-2 col-sm-10" style="margin-top: 10px;">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tbl_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    orderable: false,
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false,
                },
                {
                    data: 'address',
                    name: 'address',
                    orderable: false,
                },
                {
                    data: 'phone_nr',
                    name: 'phone_nr',
                    orderable: false,
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                },
            ]
        });

        $('#createNewEmployee').click(function() {
            $('#saveBtn').val("create-employee");
            $('#id').val('');
            $('#employeeForm').trigger("reset");
            $('#modelHeading').html("Create New employee");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editEmployee', function() {
            var id = $(this).data('id');
            $.get("{{ route('employees.index') }}" + '/' + id + '/edit', function(data) {
                $('#modelHeading').html("Edit employee");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#address').val(data.address);
                $('#phone_nr').val(data.phone_nr);
                $('#email').val(data.email);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#employeeForm').serialize(),
                url: "{{ route('employees.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#employeeForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });


        $('body').on('click', '.deleteEmployee', function() {
            var id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");
            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employees.store') }}" + '/' + id,
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            } else {
                return false;
            }
        });


    });
</script>
@endpush

</html>