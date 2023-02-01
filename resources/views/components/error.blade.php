 @props(['field'])

 @error($field)
     <p class="mt-3 text-sm text-red-700">
         {{ $message }}
     </p>
 @enderror
