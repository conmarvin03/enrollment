<head>
    <title>Enrollment- Programs</title>
  </head>
<style>
    div.ex1 {
  width: 1050px;
  height: 400px;
  overflow: scroll;
}

</style>
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
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
           
                <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <a href="{{route('programs')}}" style="float: right;" class="btn btn-secondary">go back</a>
                        <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp;{{$program->acc.'- '.$program->program}}</h3>
                        </div>
             
                          </div>
                  <div class="row container-fluid">
                    <div class="col-5">
                        <p class="h5">Program Information</p>
                        <hr>
                        <p><b>Number of Courses: </b>{{$noofcourses}}</p>
                        <p><b>Number of Lecture Courses: </b>{{$countlecture}}</p>
                        <p><b>Number of Laboratory Courses: </b>{{$countlaboratory}}</p>
                        <p><b>Total Units:</b>{{$sumUnits}}</p>
                        <form action="{{route('updateprogram', ['program'=>$program])}}" method="post">
                            @csrf
                                @method('put')
                                  <div class="row">
                                    <div class="col">
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="acc" required value="{{$program->acc}}" placeholder=" Course Code">
                                    </div>
                                    <div class="col">
                                    <button type="submit" class="btn btn-primary w-100 mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Update</button>       
                                    </div>
                                  </div>
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="program" required value="{{$program->program}}" placeholder="Enter Course Title">
                            
                        </form>
               
                        <div class="p-3"> <p class="h5">Add Prerequisite</p>
                            <hr>
                            <form action="{{route('addcurriculums')}}" method="post">
                                @csrf
                                    @method('post')
                                    <div class="row">
                                        <div class="col-4">
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="cc" required placeholder="Enter Course Code">
                                        </div>
                                        <div class="col-8">
                                    <input type="text" class="form-control w-100 border-secondary mt-2" name="c" required placeholder="Enter Course Title">
                                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                    <select name="type" required class="form-control w-100 border-secondary mt-2">
                                        <option value="">Select Course Type</option>
                                        <option >General Education</option>
                                        <option>Core Course</option>
                                        <option>Professional Course</option>
                                        <option>Electives</option>
                                        <option>Thesis</option>
                                        <option>Practicum</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="num" class="form-control w-100  border-secondary mt-2" name="unit" required placeholder="Enter Course Unit/s">
                                 
                                    
                                </div></div>
                                <div class="row">
                                    <div class="col-6">
                                        <select name="years" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                            <option value="">Select Year</option>
                                            <option value="1">First year</option>
                                            <option value="2">Second Year</option>
                                            <option value="3">Third Year</option>
                                            <option value="4">Fourth Year</option>
                                            <option value="5">Fifth Year</option>
                                        </select>   
                                   
                                    </div>
                                    <div class="col-6">
                                        <select name="semester" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                            <option value="">Select Semester</option>
                                            <option value="1">First</option>
                                            <option value="2">Second</option>
                                            <option value="3">Summer</option>
            
                                        </select>
                                    
                                    </div></div>
                                    <div class="row">
                                        <div class="col-8">
                                    <select name="leclab" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                        <option value="">Lecture or Laboratory</option>
                                        <option value="Lecture">Lecture</option>
                                        <option value="Laboratory">Laboratory</option>
        
                                    </select>
                                        </div>
                                        <div class="col-4">
                                    <input type="text" value="{{$program->id}}" name="pID"  style="display:none;">
                                    <button type="submit" class="btn btn-primary mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Course</button>
                                    <br>
                                    
                                        </div></div>
                                    </form>
                            
                    </div>
                    </div>
                    <div class="col-7">
                    
                        <p class="h5">Add Prerequisite</p>
                        <hr>
                        <div class="row">
                            <div class="col">
                        <form action="{{route('addprereq')}}" method="post">
                            @csrf
                                @method('post')
                                <select name="coursecode" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                    <option value="">Select Course Code</option>
                                    @foreach ($curriculum as $asd)
                                   
                                        <option  value="{{$asd->id}}">{{$asd->courseCode.'-'.$asd->course}}</option>
                                            @endforeach   
                                </select>
                                </div>
                                <div class="col">
                                <select name="prereq" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                    <option value="">Select Prerequisite Code</option>
                                    @foreach ($curriculum as $asd)
                                   
                                        <option  value="{{$asd->id}}">{{$asd->courseCode.'-'.$asd->course}}</option>
                                            @endforeach   
                                </select>
                            
                                <input type="text" value="{{$asd->pID}}" name="zxc" style="display: none;">
                                </div><div class="col">
                                    <button type="submit" class="btn btn-primary  mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Prerequisite</button>
                               
                                </div></div>
                        </form>
                       
                                
                            <div class="form-group">
                                
                              
                   
                        </div>
                        <div class="ex1">
                        <table  id="example2"  class="table-responsive text-center display table table-striped table-hover table-bordered" >
                            <thead class="text-center">
                             <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Course Code</th>
                                <th class="text-center">Action</th>
                         
                             </tr>
                            </thead>
                            <tbody>
                                @foreach ($pp as $pp)
                                <tr>
                                    <form action="{{route('archiveprereq')}}" method="post">
                                        @csrf
                                            @method('put')
                                    <td class="text-left" >
                                        <input type="text" value="{{$pp->id}}" name="prereqID" style="display: none;">{{$pp->course.' - '.$pp->course1}}</td>
                                    <td class="text-left" >{{$pp->prerequisite.' - '.$pp->prerequisite1}}</td>
                                    <td>  <button type="submit" class="btn btn-danger mt-2" ><i class="fa-solid fa-times"></i></button>       
                                    
                                    </td>   
                                    </form>    
                                </tr>
                             
                                                
                              
                                    @endforeach     
                            </tbody>
                
                        </table> 
                        </div>
                    </div>
                  </div>
           
                
             
                  </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-2">
                <div class="row">
                    <div class="col">
                <div class="card card-success">
                    <div class="card-header">
                        
                    <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Courses</h3>
                    </div>
         
                      </div>
                    
                      <div class="tab-content" id="nav-tabContent">
                          <div>
                            <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
                                <thead class="text-center">
                                 <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center" width="5">Course Code</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Lec/Lab</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Unit/s</th>
                                    <th class="text-center">Year</th>
                                    <th class="text-center">Semester</th>
                                    <th class="text-center">Action</th>
                                 </tr>
                                </thead>
                                <tbody>
                                    @foreach ($curriculum as $curriculum)
                                    <tr>
                                        <td class="text-center" >{{$curriculum->id}}</td>
                                        <td class="text-center" >{{$curriculum->courseCode}}</td>
                                        <td class="text-center" >{{$curriculum->course}}</td>
                                        <td class="text-center" >{{$curriculum->leclab}}</td>
                                        <td class="text-center" >{{$curriculum->type}}</td>
                                        <td class="text-center" >{{$curriculum->unit}}</td>
                                        <td class="text-center" >{{$curriculum->years}}</td>
                                        <td class="text-center" >{{$curriculum->semester}}</td>
                                        <td><a class="navbar-brand text-dark btn btn-outline-secondary" href="{{route('course.edit',['curriculum'=> $curriculum])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                        </td>
                    
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

