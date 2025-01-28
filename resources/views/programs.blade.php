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
            <div class="accordion pt-4" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne ">
                    <button class="accordion-button bg-success accordion-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <i class="fa-solid fa-plus"></i>  &nbsp;&nbsp;Add Program
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="{{route('addprograms')}}" method="post" enctype="multipart/form-data">
                            @csrf
                                @method('post')
                            <div class="card-body">
                                <p class=" pt-2"> </p>
                                
                            <div class="form-group">
                                
                            <input type="text" class="form-control w-50 form-control-border border-success" name="acc" required placeholder="Enter Accronymn">
                            <input type="text" class="form-control w-100 form-control-border border-success" name="program" required placeholder="Enter Program">
                            <button type="submit" class="btn btn-outline-success mt-2" style="float: right;"><i class="fa-solid fa-plus"></i> Add Program</button>
                           
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
                        
                    <h3 class="card-title"> <i class="fa-solid fa-user"></i>  &nbsp;&nbsp;Program Information</h3>
                    </div>
         
                      </div>
                  
                   
        <table  id="example"  class="table-responsive text-center display table table-striped table-hover table-bordered border-success" >
            <thead class="text-center">
             <tr>
                <th class="text-center">ID</th>
                <th class="text-center" width="5">Acc</th>
             <th class="text-center">Program</th>
             <th class="text-center">Action</th>
             </tr>
            </thead>
            <tbody>
                @foreach ($program as $program)
                <tr>
                    <td class="text-center" ><b>{{$program->id}}</b></td>
                    <td class="text-center" ><b>{{$program->acc}}</b></td>
                    <td class="text-center" ><b>{{$program->program}}</b></td>
                    <td><a class="navbar-brand text-dark btn btn-outline-secondary" href="{{route('program.edit',['program'=> $program])}}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
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

