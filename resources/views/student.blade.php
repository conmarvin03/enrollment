<head>
    <title>Enrollment- Students</title>
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
                            <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp;Add Students</h3>
                    
                        </div>
             
                          </div>
                  <div class="row container-fluid">
                    <form action="{{ route('import.excelStudent') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                       <button type="submit" class="btn btn-dark" style="float: right;">Import Excel</button>  <h2 class="lead p-3">Import Excel</h2> <hr class=" w-100">
                  
                    <input type="file"  class="p-3"  class="form-control" name="file" required><br>
                  
                                   </form> 


                    <div class="col">
                    
                        <p class="h5">Students Information</p>
                        <hr>
                        
                        <form action="{{route('addstudents')}}" method="post">
                            @csrf
                                @method('post')
                            <div class="card-body">
                                <p class=" pt-2"> </p>
                                
                            <div class="form-group">
                                <label class="mt-2">Image:</label>
                                <input type="file" class="form=control" name="img" class="mt-3 form-control">
                                <div class="row">
                                    <div class="col-4">
                                        <label>First Name:</label>
                                        <input type="text" class="form-control w-100 border-secondary" name="fName" required placeholder="Enter First Name">
                                    </div>
                                    <div class="col-4">
                                        <label>Last Name:</label>
                                        <input type="text" class="form-control w-100 border-secondary" name="lName" required placeholder="Enter Last Name">
                                    </div>
                                    <div class="col-4">
                                        <label>Middle Name:</label>
                                        <input type="text" class="form-control w-100 border-secondary" name="mName" required placeholder="Enter Middle Name (Optional)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                            <label class="mt-1">KLDID:</label>
                            <input type="text" class="form-control w-100 border-secondary" name="kldid" required placeholder="Enter KLD ID No.">  
                        </div>
                        <div class="col-4">
                            <label class="mt-1">Email:</label>
                            <input type="email" class="form-control w-100 border-secondary" name="email" required placeholder="Enter Email"> 
                        </div>
                        <div class="col-4">
                            
                            <label class="mt-1">Program:</label>
                            <select class="form-control border-secondary" name="pID">
                                <option value=""> Select Program </option>
                                @foreach ($programs as $programs)
                                <option value="{{$programs->id}}">{{$programs->program}} </option>
                              
                                @endforeach
                            </select>
                        </div>
                    </div>
                       
                            <div class="row">
                                <div class="col-8">
                           
                                </div>
                                <div class="col-4">
                           <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Student</button>
                                </div></div>
                            </form>
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
                    <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
                        <thead class="text-center">
                         <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center" width="5">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Program</th>
                            <th class="text-center">Action</th>
                         </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $students)
                            <tr>
                                <td class="text-center" >{{$students->id}}</td>
                                <td class="text-center" >{{$students->name}}</td>
                                <td class="text-center" >{{$students->email}}</td>
                                <td class="text-center" >{{$students->program}}</td>
                                <td><a class="navbar-brand text-dark btn btn-primary" href="{{route('student.edit',['id'=> $students->kldID])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                    <a class="navbar-brand text-dark btn btn-warning" href=""><i class="fa-regular fa-pen-to-square"></i> View Grades</a>
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

