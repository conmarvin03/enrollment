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
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
           <div class="row">
            <div class="col-3">
            </div>
          
            <div class="card p-2 col-6">
                <div class="card-header">
                  <h3 class="card-title">Add new admin</h3>
                </div>
                <div class="card-body p-4">
                    <x-slot name="logo">
                    </x-slot>
            
                    <x-validation-errors/>
                
                    <form method="POST" action="{{ route('users.store') }}" >
                        @csrf
            
                        <div>
                            <x-label for="name" value="Name" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>
            
                        <div class="mt-4">
                            <x-label for="email" value="Email" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                        </div>
            
                        <div class="mt-4">
                            <x-label for="password" value="Password" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
            
                        <div class="mt-4">
                            <x-label for="password_confirmation" value="Confirm Password" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>
            
                        <div class="mt-4">
                            
                        </div>
            
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                Add User
                            </x-button>
                        </div>
                    </form>
                </div>
                <div class="col-3">
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
    pageLength: 15,

layout: {
    topStart: {
     
    }
}
});
</script>

