   <!-- Profile Section -->
   <div class="myProfile row my-4">
       <div class="col">
           @php
           $photo = Auth::user()->employee->photo ?? null;
           $isExternal = $photo && Str::startsWith($photo, ['http://', 'https://']);
           $defaultPhoto = asset('images/default-avatar.png');
           @endphp

           <div class="card d-flex flex-row align-items-center p-3">
               <img src="{{ $photo ? ($isExternal ? $photo : asset('storage/attachments/employee_photos/' . $photo)) : $defaultPhoto }}"
                   alt="User Avatar"
                   width="96"
                   height="96"
                   class="me-4">

               <div>
                   <h4 class="card-title mb-1">
                       {{ Auth::user()->employee->empFname ?? '' }}
                       {{ Auth::user()->employee->empMname ?? '' }}
                       {{ Auth::user()->employee->empLname ?? '' }}
                   </h4>
                   <p class="card-text">Employee</p>
                   <p class="card-text">Google Account Linked</p>
                   <a href="#" class="btn btn-link">Update Profile</a>
               </div>
           </div>

       </div>
   </div>