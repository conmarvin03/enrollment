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
      
        <a href="{{route('students')}}" style="float: right;" class="btn btn-outline-primary  ml-2">go back</a>
  
        {{-- <form action="{{ route('enrollnxtsem') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post') 
            <input type="submit" class="btn btn-primary" style="float: right;" value="Enroll">
        </form>
   --}}
    
        

                        </div>
                 
                          @php
                          $groupedGrades = $grades->groupBy(function ($item) {
                              return $item->year . '-' . $item->semester;
                          });
                          $accordionId = 'accordionExample';
                      @endphp
                      
                      <div class="accordion" id="{{ $accordionId }}">
                          @php $index = 0; @endphp
                          @foreach ($groupedGrades as $key => $group)
                              @php
                                  $parts = explode('-', $key);
                                  $year = $parts[0];
                                  $semester = $parts[1];
                                  $semLabel = $semester == 1 ? '1st Semester' : ($semester == 2 ? '2nd Semester' : 'Summer');
                                  $collapseId = 'collapse' . $index;
                                  $headingId = 'heading' . $index;
                              @endphp
                      
                              <div class="accordion-item">
                                  <h2 class="accordion-header" id="{{ $headingId }}">
                                      <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                          Year: {{ $year }} - {{ $semLabel }}
                                      </button>
                                   
                                  </h2> 
                                  <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="{{ $headingId }}" data-bs-parent="#{{ $accordionId }}">
                                      <div class="accordion-body">
                            
                                        <form action="" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary m-3">COR</button>
                                        </form>
                             
                                    
                                        <table class="table-responsive text-center display table table-bordered border-success">
                                              <thead class="text-center">
                                                  <tr>
                                                      <th class="text-center">Code</th>
                                                      <th class="text-center">Course</th>
                                                      <th class="text-center">Grade</th>
                                                      <th class="text-center">Remarks</th>
                                                      <th class="text-center">Teacher</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  @foreach ($group as $grade)
                                                      <tr>
                                                          <td class="text-left">{{ $grade->subject }}</td>
                                                          <td class="text-left" width="650">{{ $grade->course }}</td>
                                                          <td class="text-left">{{ number_format($grade->grade, 2) }}</td>
                                                          <td class="text-left">{{ $grade->remark }}</td>
                                                          <td class="text-left">{{ $grade->teacher_name }}</td>
                                                      </tr>
                                                  @endforeach
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>  
                              @php $index++; @endphp
                          @endforeach
                             
                      </div>
                      
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

