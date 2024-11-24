<x-app-layout>
    <div class="flex justify-center p-8 bg-gray-100 min-h-screen">
        <!-- Card Container -->
        <div class="bg-white rounded-lg shadow-md max-w-5xl w-full flex flex-col md:flex-row overflow-hidden">

          <!-- Left Section: Image -->
          <div  class="p-8 md:w-1/2 space-y-4">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900">About Us</h2>
                <p class="text-gray-700 mt-2">
                    Purple Look's full-service salon is prepared in every way to make you look amazingly beautiful. We are a salon for everyone.
                </p>
              </div>

              <!-- Left-Aligned Paragraphs -->
              <p class="text-gray-600 text-left">
                Purple Look Hair Salon and Spa has been assisting clients to look and feel their very best since 2018. We have a talented team that takes great pride in providing friendly customer service and unique styles that let each client shine.
              </p>


              <!-- Call-to-Action Button -->
              <div class="text-center md:text-left">
                <button class="px-6 py-2 mt-4 text-white bg-gray-800 rounded-md hover:bg-gray-700">Get in Touch</button>
              </div>
          </div>

          <!-- Right Section: Text Content -->
          <div class="md:w-11/12 w-lvw"  >
            <!-- Centered Heading and Introductory Paragraph -->

            <img src="{{ asset('images/about3.jpg')}}" alt="Team working together" class="w-lvw h-full object-cover">


          </div>
        </div>
      </div>

    <div class="py-28 px-32">
        <div class="px-10 py-5 h-auto rounded overflow-hidden shadow-lg bg-white">
            <div class="px-6 py-4">
                <div class="text-center font-extrabold text-2xl mb-2">OUR TEAM</div>
                <p class="text-center text-gray-700 text-base">
                  Our talented staff's on Purple Look Salon and Spa
                </p>
              </div>
              <div class="sm:col-span-4 flex flex-col flex-wrap gap-2  md:flex-row mt-3 pb-7 h-max bg-gray-50">


            @if($employees->isNotEmpty())
                @foreach($employees as $employee)
                    @if(!$employee->is_hidden)


                    <div class="max-w-sm py-0 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="h-auto max-w-xs rounded-t-sm" src="{{ asset('storage/'. $employee->image)}}" alt="Employee Photo" />
                        </a>
                        <div class="p-5">

                                <h4 class="mb-2 text-2xl text-left font-bold tracking-tight text-gray-900 dark:text-white"> {{$employee->first_name}} {{$employee->last_name}}</h4>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{$employee->position}}</p>
                        </div>
                    </div>

                    @endif
                @endforeach
            @else
                <div class="flex justify-center">
                    <div class="bg-white shadow-md rounded my-6">
                        <table class="text-left w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">
                                        No Team Found
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>
</x-app-layout>
