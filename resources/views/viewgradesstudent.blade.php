<head>
    <title>Enrollment- Programs</title>
  </head>
<x-app-layout>
    <x-slot name="header">
        @if(session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    html: `{!! nl2br(session("error")) !!}`,
                });
            });
        </script>
        @endif
        
        @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                });
            });
        </script>
        @endif
        
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
       
        <div class="card card-success">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"> <i class="fa-solid fa-list"></i>  &nbsp;&nbsp;Grades</h3>
                    
            <style>
                .flowchart-container {
                  display: flex;
                  flex-wrap: wrap;
                  gap: 1rem;
                  padding: 1rem;
                  width: 100%;
                }
              
                .year-header {
                  font-size: 1rem;
                  font-weight: bold;
                  margin-bottom: 1rem;
                  text-align: center;
                  color: #0074cc;
                }
              
                .semester-label {
                  font-weight: 600;
                  margin-bottom: 0.5rem;
                  color: #555;
                  font-size: 0.9rem;
                }
              
                .subject {
                  background-color: #e3f2fd;
                  border-left: 5px solid #1976d2;
                  padding: 0.5rem;
                  margin-bottom: 0.5rem;
                  border-radius: 4px;
                  font-size: 0.85rem;
                  cursor: help;
                }
              </style>
        

        <a href="{{route('students')}}" style="float: right;" class="btn btn-outline-primary">go back</a>
                      

                        </div>
             
                          </div>
                          @foreach ($id as $id)
                         {{ $id->kldID }}
                         @endforeach   


                         @foreach ($grades as $grades)
                         {{ $grades->subject.'-'.$grades->grade }}
                         <br>
                         @endforeach   
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

