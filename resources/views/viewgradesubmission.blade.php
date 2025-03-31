<head>
    <title>Enrollment- Programs</title>
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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       
        <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp;Add Grades Submissions</h3>
                    
                        </div>
             
                          </div>
                  <div class="row container-fluid">
                 


                    <div class="col">
                    
                     
                   
                      
                            <div class="card-body">
                                <p class=" pt-2"> </p>
                                
                            <div class="form-group">
                             
                                <div class="row">
                              
                                    <div class="col-4">
                                   
                                    @foreach ($gradessubmissions as $gradessubmissions)
                                    <form action="{{route('edit.grades',['Gradesubmissions'=> $gradessubmissions])}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put') 
                                    <label>Grade Name:</label>
                                     <input type="text" class="form-control w-100 border-secondary" name="gName"  value=" {{ $gradessubmissions->gradeName }}" placeholder="Enter Grade Name">
                                 
                                     
                                     
                                    </div>
                                    <div class="col-4">
                                        <label>Section Format Eg. (BSIS101)</label>
                                        <input type="text" class="form-control w-100 border-secondary"  value=" {{ $gradessubmissions->section }}" name="section" required placeholder="Enter Last Name">
                                    </div>
                                    <div class="col-4">
                                        <label>Subject</label>
                                        <select name="coursecode" required class="form-control w-100 form-control-broder border-secondary">
                                        <option value="">Select Course Code</option>
                                        @foreach ($subjects as $subjects)
                                       
                                            <option <?php if($subjects->courseCode==$gradessubmissions->subject){?> selected <?php }?> value="{{$subjects->courseCode}}">{{$subjects->courseCode.'-'.$subjects->course}}</option>
                                                @endforeach   
                                    </select>

                                    @endforeach
                                </div>
                                </div>
                                <div class="row">
                             
                                <div class="col-4"> @foreach ($settings as $settings)
                            <label class="mt-1">Academic year:</label> <h1>{{ $settings->academicyear }} - {{ $settings->year }}</h1>
                            <input type="text" class="form-control w-100 border-secondary" name="year" style="display:none;" value="{{$settings->academicyear.'-'.$settings->year}}" required placeholder="Enter KLD ID No.">  
                        </div>
                        <div class="col-4">
                            <label class="mt-1">Semester:</label>
                            <h1>

                            <?php if($settings->semester==1){ ?>
                                <h1>First Semester</h1>
                                <?php }else if($settings->semester==2){ ?> 
                                    <h1>Second Semester</h1>
                               
                                <?php }else if($settings->semester==3) {  ?>
                                    <h1>Summer</h1>
                               
                                    <?php }?> </div>
@endforeach
                            </h1><input type="text" class="form-control w-100 border-secondary" name="semester" style="display:none;" value="{{$settings->semester}}" required placeholder="Enter KLD ID No.">  
                 
                        <input type="text" value="{{ auth()->user()->id }}" style="display:none;" name="tID" >
                        <div class="col-4">
                        <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-pen"></i> Edit Student</button>
                        </form>
                    </div>  
                    
                            <div class="row">
                                <div class="col-8">
                           
                                </div>
                                <div class="col-4">
                                 </div></div>
                                 <hr class=" w-100 mt-5">
                                 <div class="row">
                               
                        <h2 class="lead p-3">Import Grades</h2>
                  <div class="col-4">  <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                  <input type="file"  class="p-3"  class="form-control" name="file" required>
                  <input type="text" value="{{$gradessubmissions->id}}" name="ids" >
                       
                  <input type="text" value="{{$gradessubmissions->tID}}"  name="tID" >
                       
                    <button type="submit" class="btn btn-dark" style="float: right;">Import Excel</button> 
                    </form> 
                         
                    </div>
                    <div class="col-8">
                     </div>                   
                        </div>
                              
                        </div>
                  
                      
                    </div>
                  </div>
           
                    </div>
                   
                  </div>  
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-2">
                <div class="row">
                    <div class="col">
                <div class="card card-success">
                    <div class="card-header">
                        
                    <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Submitted Grades</h3>
                  
                    </div>
                    <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
                    <thead class="text-center">
                         <tr>
                            <th class="text-center">Number</th>
                            <th class="text-center" >KLD No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Action</th>
                         </tr>
                        </thead>
                        <tbody>
                        @foreach ($gradesStudent as $gradesStudent)
                        <tr>
                        <td class="text-center" ><b>{{$gradesStudent->id}}</b></td>
                        <td class="text-center" ><b>{{$gradesStudent->kldID}}</b></td>
                        <td class="text-center" ><b>{{$gradesStudent->name}}</b></td>
                        <td class="text-center" ><b>{{$gradesStudent->grade}}</b></td>
                        <td class="text-center" ><b>{{$gradesStudent->remark}}</b></td>
                        <td class="text-center" ><b></b></td>
                        </tr>
                        @endforeach   
                    </table>
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

