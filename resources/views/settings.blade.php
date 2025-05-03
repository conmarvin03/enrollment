<head>
    <title>Enrollment - Students</title>
</head>

<x-app-layout>
<x-slot name="header">

    {{-- SweetAlert Messages --}}
    @if (session('error'))
    <script>
        let duplicateDetails = @json(session('details'));
        let errorMessage = "Import failed due to duplicate or invalid entries.";
    
        if (duplicateDetails && duplicateDetails.length > 0) {
            errorMessage += "\n\nDuplicate Entries:\n";
            duplicateDetails.forEach(entry => {
                errorMessage += `KLDNum: ${entry.kldnum || 'N/A'}, Email: ${entry.email || 'N/A'} - ${entry.reason}\n`;
            });
        }
    
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage,
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
    @endif

    @if (session('errors'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
    @endif

    <div class="py-12">
    <div class="container">
        <div class="row">

            <!-- Academic Timeline Card -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-calendar-days"></i> Academic Timeline</h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('editsettings') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="academicyear" class="form-label">Academic Year From:</label>
                                    <input type="text" id="academicyear" name="academicyear" value="{{ $settings->academicyear }}" class="form-control" placeholder="e.g. 2024">
                                </div>

                                <div class="col-md-6">
                                    <label for="year" class="form-label">Academic Year To:</label>
                                    <input type="text" id="year" name="year" value="{{ $settings->year }}" class="form-control" placeholder="e.g. 2025">
                                </div>

                                <div class="col-md-6">
                                    <label for="semester" class="form-label">Semester:</label>
                                    <select id="semester" name="semester" class="form-select">
                                        <option value="">Select Semester</option>
                                        <option value="1" {{ $settings->semester == 1 ? 'selected' : '' }}>1st Semester</option>
                                        <option value="2" {{ $settings->semester == 2 ? 'selected' : '' }}>2nd Semester</option>
                                        <option value="3" {{ $settings->semester == 3 ? 'selected' : '' }}>Summer</option>
                                    </select>
                                </div>

                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" id="publishGradesBtn" class="btn btn-success">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Academic Timeline
                                </button>
                                <button type="submit" name="action_type" value="publish" id="realPublishSubmit" style="display:none;"></button>
                            </div>
                        </form>

                        <script>
                        document.getElementById('publishGradesBtn').addEventListener('click', function () {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Once submitted, all grades will be final and can no longer be edited.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, submit!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('realPublishSubmit').click();
                                }
                            });
                        });
                        </script>

                    </div>
                </div>
            </div>

            <!-- Access Settings Card -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-lock"></i> Access Settings</h4>
                    </div>
                    <div class="card-body" style="padding-left: 5%;">

                        <form action="{{ route('editcor') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="form-check form-switch mb-4" style="font-size: 1.5rem;">
                                <input class="form-check-input" type="checkbox" role="switch" id="corSwitch" name="cor" value="1" style="width: 3rem; height: 1.5rem;" {{ $settings->cor ? 'checked' : '' }}>
                                <label class="form-check-label ms-3" for="corSwitch">
                                    COR Download: <strong>{{ $settings->cor ? 'On' : 'Off' }}</strong>
                                </label>
                            </div>

                            <div class="form-check form-switch mb-4" style="font-size: 1.5rem;">
                                <input class="form-check-input" type="checkbox" role="switch" id="enrollmentSwitch" name="enrollment" value="1" style="width: 3rem; height: 1.5rem;" {{ $settings->enrollment ? 'checked' : '' }}>
                                <label class="form-check-label ms-3" for="enrollmentSwitch">
                                    Enrollment: <strong>{{ $settings->enrollment ? 'On' : 'Off' }}</strong>
                                </label>
                            </div>

                            <div class="form-check form-switch mb-4" style="font-size: 1.5rem;">
                                <input class="form-check-input" type="checkbox" role="switch" id="gradesSwitch" name="grade" value="1" style="width: 3rem; height: 1.5rem;" {{ $settings->grades ? 'checked' : '' }}>
                                <label class="form-check-label ms-3" for="gradesSwitch">
                                    Grades: <strong>{{ $settings->grades ? 'On' : 'Off' }}</strong>
                                </label>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary mt-3" style="font-size: 1.2rem; padding: 10px 20px;">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Access Settings
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

</x-slot>
</x-app-layout>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
    new DataTable('#example', {
        layout: { topStart: {} }
    });
</script>
