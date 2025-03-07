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
                            
                            <a href="{{route('students')}}" style="float: right;" class="btn btn-outline-primary">go back</a>
                        <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp; Edit Student</h3>
                        </div>
             
                          </div>
                  <div class="row container-fluid">

                    <p class="h5">Student Information</p>
                    <hr>
                 
                 

                </div>
                <div class="col">
                     <form action="{{route('updatestudent',['id'=> $students->kldID])}}" method="post" enctype="multipart/form-data">
                        @csrf
                            @method('put')
                        <div class="card-body">
                            <p class=" pt-2"> </p>
                            
                        <div class="form-group">
                            {{-- IMPORT EXCEL --}}
                          




                            <div class="row">
                                <div class="col-3">
                                    <?php if($students->img ==  "")
                                    {
                                        ?>
                                        
                                        <img class="profile-user-img" style=" display:inline; height:300px; width:250px; " src="/upload/profilepic/nodp.jpg"  alt="User profile picture">
                                    
                                    <?php }else{?> 
                                        <img class="profile-user-img" style="display:inline; height:300px; width:250px;" src="/{{$students->img}}"  alt="User profile picture">
                                    <?php }?><br>
                                </div>
                                
                                <div class="col-9">
                                    <label class="mt-4">Student Picture: </label>
                                      <input type="file" class="form-control w-100 border-secondary" value="{{$students->fName}}"  name="img" required placeholder="Enter First Name">
                                     
                                </div>
                            </div>
                            <div class="row mt-4">
                            <div class="col-4">
                        
                                <label>First Name</label>
                        <input type="text" class="form-control w-100 border-secondary" value="{{$students->fName}}"  name="fName" required placeholder="Enter First Name">
                            </div>
                            <div class="col-4">
                                
                                <label>Middle Name</label>
                        <input type="text" value="{{$students->mName}}" class="form-control w-100 border-secondar" name="mName"  placeholder="Enter Middle Name">
                            </div>
                            <div class="col-4">
                                
                                <label>Last Name</label>
                                <input type="text" value="{{$students->lName}}" class="form-control w-100 border-secondary  " name="lName" required placeholder="Enter First Name">
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-4"> <label class=" mt-2">Gender</label>
                        <select name="gender" required class="form-control w-100 border-secondary">
                            <option value="">Select Gender</option>
                            <option  <?php if($students->gender=='Male'){?> selected <?php }?>>Male</option>
                            <option <?php if($students->gender=='Female'){?> selected <?php }?>>Female</option>
                           
                        </select>
                    </div>
                    <div class="col-4">
                        <label class=" mt-2">Birthday</label>
                        <input type="date" value="{{$students->bday}}" class="form-control w-100 border-secondary  " name="bday" required placeholder="Enter First Name">
                               
                    </div>
                    <div class="col-4">
                        <label class=" mt-2">KLD ID</label>
                        <input type="text" value="{{$students->kldID}}" class="form-control w-100 border-secondary  " readonly name="kldID" required >
                              
                    </div>
                    <label class=" mt-2">Address</label>
                    <textarea placeholder="Enter Address" class="form-control border-secondary w-75 ml-1" name="address" >{{$students->address}}</textarea>
                <div class="row">
                    <div class="col">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-outline-success m-3" style="float: right;"><i class="fa-solid fa-plus"></i>Edit Student</button>
                    </form>
                    <form>
                        <button type="submit" class="btn btn-outline-danger m-3" style="float: right;"><i class="fa-solid fa-plus"></i> Reset Password </button>
                    </form>
                    </div>  
                </div>
                        
                        </div>
                     
 
                  </div>
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
layout: {
    topStart: {
     
    }
}
});
</script>

