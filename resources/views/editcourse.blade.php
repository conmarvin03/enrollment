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
                            
                            <a href="{{route('program.edit',['program'=> $curriculum->pID])}}" style="float: right;" class="btn btn-outline-primary">go back</a>
                        <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp; Edit Course</h3>
                        </div>
             
                          </div>
                  <div class="row container-fluid">

                    <p class="h5">Program Information</p>
                    <hr>
                 
                 

                </div>
                <div class="col">
                     <form action="{{route('updatecourse',['curriculum'=> $curriculum])}}" method="post">
                        @csrf
                            @method('put')
                        <div class="card-body">
                            <p class=" pt-2"> </p>
                            
                        <div class="form-group">
                            <div class="row">
                            <div class="col-4">
                        <input type="text" class="form-control w-100 border-secondary mt-2" value="{{$curriculum->courseCode}}"  name="cc" required placeholder="Enter Course Code">
                            </div>
                            <div class="col-8">
                        <input type="text" value="{{$curriculum->course}}" class="form-control w-100 border-secondary mt-2" name="c" required placeholder="Enter Course Title">
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                        <select name="type" required class="form-control w-100 border-secondary mt-2">
                            <option value="">Select Course Type</option>
                            <option  <?php if($curriculum->type=='General Education'){?> selected <?php }?>>General Education</option>
                            <option <?php if($curriculum->type=='Core Course'){?> selected <?php }?>>Core Course</option>
                            <option <?php if($curriculum->type=='Professional Course'){?> selected <?php }?>>Professional Course</option>
                            <option <?php if($curriculum->type=='Electives'){?> selected <?php }?>>Electives</option>
                            <option <?php if($curriculum->type=='Thesis'){?> selected <?php }?>>Thesis</option>
                            <option <?php if($curriculum->type=='Practicum'){?> selected <?php }?>>Practicum</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="num" class="form-control w-100  border-secondary mt-2" value="{{$curriculum->unit}}" name="unit" required placeholder="Enter Course Unit/s">
                     
                        
                    </div>
                </div>
                    <div class="row">
                        <div class="col-6">
                            <select name="years" required  class="form-control w-100 form-control-broder border-secondary mt-2">
                                <option value="">Select Year</option>
                                <option value="1" <?php if($curriculum->years=='1'){?> selected <?php }?>>First year</option>
                                <option value="2" <?php if($curriculum->years=='2'){?> selected <?php }?>>Second Year</option>
                                <option value="3" <?php if($curriculum->years=='3'){?> selected <?php }?>>Third Year</option>
                                <option value="4" <?php if($curriculum->years=='4'){?> selected <?php }?>>Fourth Year</option>
                                <option value="5" <?php if($curriculum->years=='5'){?> selected <?php }?>>Fifth Year</option>
                            </select>   
                       
                        </div>
                        <div class="col-6">
                            <select name="semester" required class="form-control w-100 form-control-broder border-secondary mt-2">
                                <option value="">Select Semester</option>
                                <option value="1" <?php if($curriculum->semester=='1'){?> selected <?php }?>>First</option>
                                <option value="2" <?php if($curriculum->semester=='2'){?> selected <?php }?>>Second</option>
                                <option value="3" <?php if($curriculum->semester=='3'){?> selected <?php }?>>Summer</option>

                            </select>
                        
                        </div></div>
                        <div class="row">
                            <div class="col-8">
                        <select name="leclab" required value="{{$curriculum->leclab}}" class="form-control w-100 form-control-broder border-secondary mt-2">
                            <option value="">Lecture or Laboratory</option>
                            <option value="Lecture"  <?php if($curriculum->leclab=='Lecture'){?> selected <?php }?>>Lecture</option>
                            <option value="Laboratory"  <?php if($curriculum->leclab=='Laboratory'){?> selected <?php }?>>Laboratory</option>

                        </select>
                            </div>
                            <div class="col-4">
                        <input type="text" value="{{$curriculum->id}}" name="id"  style="display:none;">
                        <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Edit Course</button>
                            </div></div>
                        </form>
 
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

