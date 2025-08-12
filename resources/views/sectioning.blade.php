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
    
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-2">
                <div class="row">
                    <div class="col">    <form action="{{ route('adminseciton.bulk') }}" method="POST">
                        @csrf
                        @method('PUT')
                <div class="card card-success">
                    <div class="card-header">
                        
                    <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Submitted Grades</h3>
                  
                    <input type="submit" value="Edit All" class="btn btn-info" style="float: right">
                  
                    </div>
                    @if(session('grades'))
                    <h3>Imported Grades</h3>
                   
                @endif

               <table id="example" class="table-responsive text-center display table table-striped table-hover table-bordered border-success">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">Number</th>
                                <th class="text-center">KLD No.</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Course</th>
                                <th class="text-center">Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gradesStudent as $index => $gradesStudent)
                            <tr>
                                <td class="text-center"><b>{{ $gradesStudent->id }}</b></td>
                                <td class="text-center"><b>{{ $gradesStudent->kldID }}</b></td>
                                <td class="text-center"><b>{{ $gradesStudent->fName.' '.$gradesStudent->mName.' '.$gradesStudent->lName }}</b></td>
                                <td class="text-center"><b>{{ $gradesStudent->subject }}</b></td>
                                <td class="text-center">
                                    <input type="hidden" name="ids[]" value="{{ $gradesStudent->id }}">
                                    <select name="sections[]" class="form-control">
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ $i }}" {{ $gradesStudent->section == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                

                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                
                    <div class="text-end mt-3">
                   
                    </div>
                </form>
                
                
                      </div>
    
                </div>

            </div>
        </div>
    </div>
</div>     </div>
</div>
</x-app-layout>

      

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

