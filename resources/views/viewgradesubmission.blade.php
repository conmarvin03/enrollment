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
                           
                    </div>  
                    
                            <div class="row">
                                <div class="col-8">
                           
                                </div>
                                <div class="col-4">
                                 </div></div>
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
                       @endforeach 
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

