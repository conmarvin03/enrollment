<head>
    <title>Enrollment- Programs</title><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
<x-app-layout>
    <x-slot name="header">
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session("success") }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
        @endif
        
        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d33'
            });
        </script>
        @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php 
            $user = Auth::user();
            $idsss = Auth::user()->role;
            if($idsss=="")
            {

            }else{?>
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
                                   
                                    @foreach ($Gradesubmissions as $Gradesubmissions)
                                    <form action="{{route('updategrades',['Gradesubmissions'=> $Gradesubmissions])}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put') 
                                    <label>Grade Name:</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="gName"  value=" {{ $Gradesubmissions->gradeName }}" placeholder="Enter Grade Name">
                                    <input type="text" class="form-control w-100 border-secondary" name="id" readonly  value=" {{ $Gradesubmissions->id }}" style="display: none;" placeholder="Enter Grade Name">
                                 
                                     
                                     
                                    </div>
                                    <div class="col-4">
                                        <label>Section Format Eg. (BSIS101)</label>
                                        <input type="text" class="form-control w-100 border-secondary" readonly value=" {{ $Gradesubmissions->section }}" name="section" required placeholder="Enter Last Name">
                                    </div>
                                    <div class="col-4">
                                        <label style="">Subject</label>
                                        <input type="text" class="form-control w-100 border-secondary" readonly value=" {{ $Gradesubmissions->coursecode}}" name="coursecode"  required placeholder="Enter Last Name">
                                  
                                        <select name="coursecode" style="display:none;" class="form-control w-100 form-control-broder border-secondary">
                                        <option value="">Select Course Code</option>
                                        @foreach ($subjects as $subjects)
                                       
                                            <option <?php if($subjects->courseCode==$Gradesubmissions->coursecode){?> selected <?php }?> value="{{$subjects->courseCode}}">{{$subjects->courseCode.'-'.$subjects->course}}</option>
                                                @endforeach   
                                    </select>

                                    @endforeach
                                </div>
                                <div class="col-4 mt-2">
                                    <label>Room</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="room" value="{{$Gradesubmissions->room}}" required placeholder="Enter room">
                                </div>
                                
                                <div class="col-4 mt-2">
                                    <label>Time Start</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="timestart" value="{{$Gradesubmissions->timestart}}" required placeholder="Time Start">
                                </div>
                                
                                <div class="col-4 mt-2">
                                    <label>Time End</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="timeend"  value="{{$Gradesubmissions->timeend}}" required placeholder="Time End">
                                </div>
                                </div>
                                <div class="row">
                                
                        <div class="col-4">
                            <label>Day</label>
                            <select class="form-control w-100 border-secondary"  required name="day">
                                <option value="">Select Day</option>
                                <option <?php if($Gradesubmissions->day=="Monday"){?> selected<?php }?>>Monday</option>
                                <option <?php if($Gradesubmissions->day=="Tuesday"){?> selected<?php }?>>Tuesday</option>
                                <option <?php if($Gradesubmissions->day=="Wednesday"){?> selected<?php }?>>Wednesday</option>
                                <option <?php if($Gradesubmissions->day=="Thursday"){?> selected<?php }?>>Thursday</option>
                                <option <?php if($Gradesubmissions->day=="Friday"){?> selected<?php }?>>Friday</option>
                                <option <?php if($Gradesubmissions->day=="Saturday"){?> selected<?php }?>>Saturday</option>
                                <option <?php if($Gradesubmissions->day=="Sunday"){?> selected<?php }?>>Sunday</option>
                            </select>
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
                  <div class="col-4">  
                    <form action="{{ route('importGrades') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                  <input type="file"  class="p-3"  class="form-control" name="file" required >
                  <input type="text" value="{{$Gradesubmissions->id}}" name="ids" style="display: none;">
                  <input type="text" value="{{$Gradesubmissions->section}}" name="section" style="display: none;" >
                  <input type="text" value="{{$Gradesubmissions->year}}" name="year" style="display: none;">
                  <input type="text" value="{{$Gradesubmissions->semester}}" name="semester" style="display: none;">
                  <input type="text" value="{{$Gradesubmissions->coursecode}}" name="subject" style="display: none;">
                       
                  <input type="text" value="{{$Gradesubmissions->tID}}"  name="tID" style="display: none;" >
                       
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
                  <?php }?>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-2">
                <div class="row">
                    <div class="col">  
                        <form action="{{ route('editgrades.bulk', ['id' => $Gradesubmissions->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                <div class="card card-success">
                    <div class="card-header">
                        
                    <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Submitted Grades</h3>
                    <button type="button" id="publishGradesBtn" class="btn btn-light" style="float: right; margin-left: 10px;">
                        Publish Grades
                    </button>
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
                    <!-- Edit Button -->
                    <button type="submit" name="action_type" value="edit" class="btn btn-primary" style="float: right;">
                        Update Grades
                    </button>
                    </div>
                    @if(session('grades'))
                    <h3>Imported Grades</h3>
                   
                @endif

               <table id="example" class="table-responsive text-center display table table-striped table-hover table-bordered border-success">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">KLD No.</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gradesStudent as $index => $gradesStudent)
                            <tr>
                                <td class="text-center"><b>{{ $gradesStudent->kldID }}</b></td>
                                <td class="text-center"><b>{{ $gradesStudent->fName.' '.$gradesStudent->mName.' '.$gradesStudent->lName }}</b></td>
                     
                
                                <td class="text-center">
                                    <input type="hidden" name="ids[]" value="{{ $gradesStudent->id }}">
                                    <select name="grades[]" class="form-control w-100">
                                        <option value="0">--</option>
                                        @foreach ([1.0, 1.25, 1.5, 1.75, 2.0, 2.25, 2.5, 2.75, 3.0, 5.0] as $grade)
                                            <option value="{{ $grade }}" @selected($gradesStudent->grade == $grade)>{{ number_format($grade, 2) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                
                                <td class="text-center"><b>{{ $gradesStudent->remark ?? '--' }}</b></td>
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

