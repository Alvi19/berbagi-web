<!DOCTYPE html>
<html>

<head>
    <title>Program Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Program Management</h2>
        <div class="mb-3">
            <label for="sumber_dana">Sumber Dana:</label>
            <select id="sumber_dana">
                <option value="">All</option>
                <option value="Zakat">Zakat</option>
                <option value="Infaq Shodaqoh Terikat">Infaq Shodaqoh Terikat</option>
                <option value="Infaq Shodaqoh Tidak Terikat">Infaq Shodaqoh Tidak Terikat</option>
            </select>
            <label for="keterangan">Keterangan:</label>
            <select id="keterangan">
                <option value="">All</option>
                <option value="Ada">Ada</option>
                <option value="Tidak ada">Tidak ada</option>
            </select>
        </div>
        <button class="btn btn-success mb-2" id="addProgramBtn">Add Program</button>
        <table class="table table-bordered" id="programs-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sumber Dana</th>
                    <th>Program</th>
                    <th>Keterangan</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="programModal" tabindex="-1" role="dialog" aria-labelledby="programModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="programModalLabel">Add Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="programForm">
                        <input type="hidden" id="program_id" name="id">
                        <div class="form-group">
                            <label for="sumber_dana_input">Sumber Dana</label>
                            <input type="text" class="form-control" id="sumber_dana_input" name="sumber_dana">
                        </div>
                        <div class="form-group">
                            <label for="program_input">Program</label>
                            <input type="text" class="form-control" id="program_input" name="program">
                        </div>
                        <div class="form-group">
                            <label for="keterangan_input">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan_input" name="keterangan">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script type="text/javascript">
        $(function() {
            var table = $('#programs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('programs.data') }}',
                    data: function(d) {
                        d.sumber_dana = $('#sumber_dana').val();
                        d.keterangan = $('#keterangan').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'sumber_dana',
                        name: 'sumber_dana'
                    },
                    {
                        data: 'program',
                        name: 'program'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#sumber_dana, #keterangan').change(function() {
                table.draw();
            });

            $('#addProgramBtn').click(function() {
                $('#programForm').trigger('reset'); // Reset form fields
                $('#programModalLabel').text('Add Program');
                $('#program_id').val('');
                $('#programModal').modal('show');
            });

            $('#programForm').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting via the browser
                var formData = $(this).serialize();
                var programId = $('#program_id').val();
                var url = programId ? `/programs/${programId}` : '/programs';
                var method = programId ? 'put' : 'post';

                axios({
                    method: method,
                    url: url,
                    data: formData
                }).then(response => {
                    $('#programModal').modal('hide'); // Hide the modal
                    table.ajax.reload(); // Reload the DataTable
                    alert('Program saved successfully');
                }).catch(error => {
                    console.error(error);
                    alert('An error occurred while saving the program');
                });
            });

            $('#programs-table').on('click', '.edit', function() {
                var id = $(this).data('id');

                axios.get(`/programs/${id}`).then(response => {
                    var program = response.data;
                    $('#programModalLabel').text('Edit Program');
                    $('#program_id').val(program.id);
                    $('#sumber_dana_input').val(program.sumber_dana);
                    $('#program_input').val(program.program);
                    $('#keterangan_input').val(program.keterangan);
                    $('#programModal').modal('show');
                }).catch(error => {
                    console.error(error);
                    alert('An error occurred while fetching the program data');
                });
            });

            $('#programs-table').on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure to delete this program?')) {
                    axios.delete(`/programs/${id}`).then(response => {
                        table.ajax.reload();
                        alert('Program deleted successfully');
                    }).catch(error => {
                        console.error(error);
                        alert('An error occurred while deleting the program');
                    });
                }
            });
        });
    </script>

</body>

</html>
