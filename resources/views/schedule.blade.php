<head><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Enrollment- Schedule</title>
    <link rel="icon" type="image/x-icon" href="/sss.png"> 
  </head>
<x-app-layout>
    <x-slot name="header">
        @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            });
        </script>
    @endif
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule') }}
        </h2>
    </x-slot>

    <div class="py-8">  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
       <div class=" row">
     
        <form action="{{ route('import.schedule') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-5">
                    
                    <button type="submit"  class="btn btn-success" style="float: right;">Import</button>
                     <p class="h3"> Import Schedule </p>
                     <input type="file" name="file" required class="form-control">
      
                    </div>
                <div class="col-5">
                    
                </div>
                <div class="col-2">
                  
                </div>
            </div>
            <hr >
        </form>
            </div>
          
        </div>
    </div>
    <div class="container-fluid">
    <div class="row">
    <div class="col-sm-2 bg-gray-950">
    </div>
    
    <div class="col-sm-8">
    <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
        <thead class="text-center">
         <tr>

            <th class="text-center">Teacher</th>
            <th class="text-center">Course</th>
            <th class="text-center">Program</th>
            <th class="text-center">Year and Section</th>
            <th class="text-center">Day</th>
            <th class="text-center">Time</th>
            <th class="text-center">Room</th>
            <th class="text-center">Semester</th>
         </tr>
        </thead>
        <tbody>
   
            @foreach ($gradesubmission as $Gradesubmissions)
            <tr>
                
                <td class="text-center" ><b>{{$Gradesubmissions->teacher_name}}</b></td>
            <td class="text-center" ><b>{{$Gradesubmissions->coursecode}}</b></td>
            <td class="text-center" ><b>{{ $Gradesubmissions->acc}}</b></td>
            <td class="text-center" ><b>{{$Gradesubmissions->year.' - '.$Gradesubmissions->section}}</b></td>
        <td class="text-center" ><b>{{$Gradesubmissions->day}}</b></td>
        <td class="text-center" ><b>{{$Gradesubmissions->timestart.' - '.$Gradesubmissions->timeend}}</b></td>
        <td class="text-center" ><b>{{$Gradesubmissions->room}}</b></td>
        <td class="text-center" ><b>   <?php if($Gradesubmissions->semester==1){ ?>
            <h1>1st Semester</h1>
            <?php }else if($Gradesubmissions->semester==2){ ?> 
                <h1>2nd Semester</h1>
           
            <?php }else if($Gradesubmissions->semester==3) {  ?>
                <h1>Summer</h1>
           
                <?php }?></b></td>
            </tr>
            @endforeach   
        </tbody>
        </table>
    </div>
    <div>
    </div>
    </div>
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
        order: [[0, 'desc']],  // This will order the first column (index 0) in descending order by default
        layout: {
            topStart: {
                // Add any additional layout customizations if needed
            }
        }
    });
</script>