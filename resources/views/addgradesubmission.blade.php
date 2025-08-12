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
    {{-- <div class="py-12">
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
                            <form action="{{route('addgradesubmit')}}" method="post" enctype="multipart/form-data">
                                 @csrf
                                @method('post') 
                                <div class="row">
                              
                                    <div class="col-4">
                                        
                                        <label>Grade Name:</label>
                                        <input type="text" class="form-control w-100 border-secondary" name="gName" required placeholder="Enter First Name">
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col">
                                                <label>Program</label>
                                                <select name="program" id="programDropdown" required class="form-control border-secondary">
                                                    <option value="">Select Program</option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}">{{ $program->acc . '-' . $program->program }}</option>
                                                    @endforeach
                                                </select>
                                    </div>
                                            <div class="col">
                                              
                                                <label>Section</label>
                                                <select name="section" id="sectionDropdown" required class="form-control border-secondary">
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        
                                        <label>Course</label>
                                        <select name="coursecode" id="courseDropdown" required class="form-control border-secondary">
                                            <option value="">Select Course</option>
                                        </select>

                                </div>
                                <div class="col-4 mt-2">
                                    <label>Room</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="room" required placeholder="Enter room">
                                </div>
                                
                                <div class="col-4 mt-2">
                                    <label>Time Start</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="timestart" required placeholder="Time Start">
                                </div>
                                
                                <div class="col-4 mt-2">
                                    <label>Time End</label>
                                    <input type="text" class="form-control w-100 border-secondary" name="timeend" required placeholder="Time End">
                                </div>
                                </div>
                                <div class="row">
                                
                        <div class="col-4">
                            <label>Day</label>
                            <select class="form-control w-100 border-secondary"  required name="day">
                                <option value="">Select Day</option>
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select> --}}
                        {{-- </div>    <div class="col-4">  @foreach ($settings as $settings)
                            <label class="mt-1">Academic year:</label> <h1>{{ $settings->year }}</h1>
                            <input type="text" class="form-control w-100 border-secondary" name="year" style="display:none;" value="{{$settings->year}}" required placeholder="Enter KLD ID No.">  
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
                               
                                    <?php }?>

                            </h1><input type="text" class="form-control w-100 border-secondary" name="semester" style="display:none;" value="{{$settings->semester}}" required placeholder="Enter KLD ID No.">  
                 
                        <input type="text" value="{{ auth()->user()->id }}" style="display:none;" name="tID" >
                        </div>
                        <div class="col-4">
                  
                    </div>
                       
                            <div class="row">
                                <div class="col-8">
                           
                                </div>  @endforeach    --}}
                                {{-- <div class="col-4">
                           <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Schedule</button>
                                </div></div>
                                
                        </div>
                  
                          
                    </div>
                  </div>
           
                    </div>
                 
                  </div>   </form> --}}
                  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       
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
                            <th class="text-center" >Grade Name</th>
                            <th class="text-center">Course</th>
                            <th class="text-center">Program</th>
                            <th class="text-center">Year and Section</th>
                            <th class="text-center">Day</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">Room</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Action</th>
                         </tr>
                        </thead>
                        <tbody>
                        @foreach ($Gradesubmissions as $Gradesubmissions)
                        <tr>
                        <td class="text-center" ><b>{{$Gradesubmissions->gradeName}}</b></td>
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
                                    <td class="text-center" ><b>
                                    <?php if($Gradesubmissions->status==""){ ?>
                                        <h1 class="bg-warning">No grades uploaded yet</h1>
                                    </b></td>
                                    <td><a class="navbar-brand text-dark btn btn-outline-secondary" href="{{route('grades.edit',['Gradesubmissions'=> $Gradesubmissions])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                    </td>
                                        <?php }else if($Gradesubmissions->status=="Initial"){ ?>
                                           
                                            <h1 class="bg-secondary">Initial grades uploaded but not yet published</h1>
                                        </b></td>
                                        <td><a class="navbar-brand text-dark btn btn-outline-secondary" href="{{route('grades.edit',['Gradesubmissions'=> $Gradesubmissions])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                        </td>
                                            <?php }else{ ?>
                                                <h1 class="bg-success">Grades already published</h1>
                                            </b></td>
                                            <td><a class="navbar-brand text-dark btn btn-outline-secondary"   href="{{route('printggs',['Gradesubmissions'=> $Gradesubmissions])}}"><i class="fa-regular fa-pen-to-square"></i> Print Grade Sheet</a>
                                            </td>
                                              
                                                <?php }?>
                                  
                  

                </tr>
                        @endforeach   
                        </tbody>
            
                    </table>  
                      </div>
    
                </div>

            </div>
        </div>
    </div>
</div>     </div>
</div>
</x-app-layout>

      
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#programDropdown').on('change', function () {
        var programId = $(this).val();
        $('#courseDropdown').empty().append('<option value="">Loading...</option>');

        if (programId) {
            $.ajax({
                url: '{{ url("/get-subjects-by-program") }}/' + programId,
                type: 'GET',
                success: function (data) {
                    $('#courseDropdown').empty().append('<option value="">Select Course</option>');
                    $.each(data, function (index, subject) {
                        $('#courseDropdown').append(
                            '<option value="' + subject.courseCode + '">' + subject.courseCode + ' - ' + subject.course + '</option>'
                        );
                    });
                }
            });
            $.ajax({
    url: '{{ url("/get-sections-by-program") }}/' + programId,
    type: 'GET',
    success: function (data) {
        $('#sectionDropdown').empty().append('<option value="">Select Section</option>');
        $.each(data, function (index, section) {
            $('#sectionDropdown').append(
                '<option value="' + section + '">' + section + '</option>'
            );
        });
    }
});

        } else {
            $('#sectionDropdown').html('<option value="">Select Section</option>');
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
        order: [[0, 'desc']],  // This will order the first column (index 0) in descending order by default
        layout: {
            topStart: {
                // Add any additional layout customizations if needed
            }
        }
    });
</script>


