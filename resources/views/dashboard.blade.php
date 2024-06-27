<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Program Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mt-5">
                        <div class="mb-3 flex gap-4">
                            <button class="btn btn-success mb-2" id="addProgramBtn">Add Program</button>

                            <div>
                                <label for="sumber_dana"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sumber
                                    Dana:</label>
                                <select id="sumber_dana"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All</option>
                                    <option value="Zakat">Zakat</option>
                                    <option value="Infaq Shodaqoh Terikat">Infaq Shodaqoh Terikat</option>
                                    <option value="Infaq Shodaqoh Tidak Terikat">Infaq Shodaqoh Tidak Terikat</option>
                                </select>
                            </div>
                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Keterangan:</label>
                                <select id="keterangan"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All</option>
                                    <option value="Ada">Ada</option>
                                    <option value="Tidak ada">Tidak ada</option>
                                </select>
                            </div>
                        </div>
                        {{-- <button class="btn btn-success mb-2" id="addProgramBtn">Add Program</button> --}}
                        <table class="table-auto w-full" id="programs-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Sumber Dana</th>
                                    <th class="px-4 py-2">Program</th>
                                    <th class="px-4 py-2">Keterangan</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <dialog id="programModal" class="modal">
                        <div class="modal-box">
                            <h5 class="font-bold text-lg" id="programModalLabel">Add Program</h5>
                            <form id="programForm">
                                <input type="hidden" id="program_id" name="id">
                                <div class="form-group">
                                    <label for="sumber_dana_input">Sumber Dana</label>
                                    <input type="text" class="input input-bordered w-full" id="sumber_dana_input"
                                        name="sumber_dana">
                                </div>
                                <div class="form-group">
                                    <label for="program_input">Program</label>
                                    <input type="text" class="input input-bordered w-full" id="program_input"
                                        name="program">
                                </div>
                                <div class="form-group">
                                    <label for="keterangan_input">Keterangan</label>
                                    <input type="text" class="input input-bordered w-full" id="keterangan_input"
                                        name="keterangan">
                                </div>
                                <div class="modal-action">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-outline" id="closeProgramModal">Close</button>
                                </div>
                            </form>
                        </div>
                    </dialog>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
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
                ],
                dom: 'rt<"bottom"ip>', // Hanya menampilkan tabel dan bagian atas informasi
                paging: false, // Menyembunyikan pagination
                info: false
            });

            $('#sumber_dana, #keterangan').change(function() {
                table.draw();
            });

            $('#addProgramBtn').click(function() {
                $('#programForm').trigger('reset');
                $('#programModalLabel').text('Add Program');
                $('#program_id').val('');
                document.getElementById('programModal').showModal();
            });

            $('#programForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                var programId = $('#program_id').val();
                var url = programId ? `/programs/${programId}` : '/programs';
                var method = programId ? 'put' : 'post';

                axios({
                    method: method,
                    url: url,
                    data: formData
                }).then(response => {
                    document.getElementById('programModal').close();
                    table.ajax.reload();
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
                    document.getElementById('programModal').showModal();
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

            $('#closeProgramModal').click(function() {
                document.getElementById('programModal').close();
            });
        });
    </script>

</x-app-layout>
