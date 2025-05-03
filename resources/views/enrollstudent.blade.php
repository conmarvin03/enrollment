<head>
    <title>Enrollment- Programs</title>
  </head>
<x-app-layout>
    <x-slot name="header">
        @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    html: `{!! nl2br(session("error")) !!}`,
                });
            });
        </script>
        @endif
        
        @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                });
            });
        </script>
        @endif
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       
        <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Enrollment</h3>
                    
       

            </div>

            <form method="POST" action="{{ route('grade.enroll') }}" >
                @csrf
    
            <div class="container p-4">
            <input type="text" name="section" placeholder="Enter Section" class="form-control border-secondary" required>
            <select name="kldID" id="programDropdown" required class="form-control border-secondary mt-2">
                <option value="">Select Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->kldID }}">
                        {{ $student->lName . ', ' . $student->fName . ' ' . $student->mName }}
                    </option>
                @endforeach
            </select>
            
            <!-- Curriculum Dropdown (to be populated after selecting a student) -->
            <select name="curriculum" id="curriculumDropdown" required class="form-control border-secondary mt-2">
                <option value="">Select Curriculum</option>
            </select>

            <button type="button" id="publishGradesBtn" class="btn btn-dark mt-2" style="float: right; margin-left: 10px;">
                Enroll Student
            </button>
        </form>
            <button type="submit" name="action_type" value="publish" id="realPublishSubmit" style="display: none;"></button>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
            document.getElementById('publishGradesBtn').addEventListener('click', function (e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Once submitted, all grades will be final and can no longer be edited.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, publish them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('realPublishSubmit').click();
                    }
                });
            });
            </script>
            </div>
          </div>
  


</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Listen for change event on the programDropdown (student selection)
    $('#programDropdown').on('change', function() {
        var selectedStudentID = $(this).val(); // Get the selected student's kldID

        if (selectedStudentID) {
            // Make an AJAX request to fetch the curriculum data based on the student's kldID
            $.ajax({
                url: '/get-curriculum/' + selectedStudentID, // URL to the route created earlier
                method: 'GET',
                success: function(data) {
                    // Clear the current options in the curriculumDropdown
                    $('#curriculumDropdown').empty();
                    
                    // Add a default "Select Curriculum" option
                    $('#curriculumDropdown').append('<option value="">Select Curriculum</option>');

                    // Loop through the data and add options to the curriculumDropdown
                    $.each(data, function(index, curriculum) {
                        $('#curriculumDropdown').append(
                            '<option value="' + curriculum.courseCode + '">' + curriculum.courseCode + ' - ' + curriculum.course + '</option>'
                        );
                    });
                },
                error: function() {
                    // Handle error, maybe show an alert
                    alert("Error fetching curriculum data.");
                }
            });
        } else {
            // Clear the curriculum dropdown if no student is selected
            $('#curriculumDropdown').empty();
            $('#curriculumDropdown').append('<option value="">Select Curriculum</option>');
        }
    });
</script>

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
layout: {
    topStart: {
     
    }
}
});
</script>

