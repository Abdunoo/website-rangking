<template>
  <header
    class="fixed w-full top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f0f2f4] px-10 py-3 backdrop-blur-md ">
    <div class="flex items-center gap-4 text-[#111418]">
      <div class="size-4">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
        </svg>
      </div>
      <h2 class="text-[#111418] text-lg font-bold leading-tight tracking-[-0.015em]">Websites</h2>
    </div>
    <div class="flex flex-1 justify-end gap-8">
      <label class="flex flex-col min-w-40 !h-10 max-w-64">
        <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
          <div
            class="text-[#637588] flex border-none bg-[#f0f2f4] items-center justify-center pl-4 rounded-l-xl border-r-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
              viewBox="0 0 256 256">
              <path
                d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z">
              </path>
            </svg>
          </div>
          <input placeholder="Search"
            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] focus:outline-0 focus:ring-0 border-none bg-[#f0f2f4] focus:border-none h-full placeholder:text-[#637588] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
            value="" />
        </div>
      </label>
      <div class="flex gap-2">
        <button
          class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-[#f0f2f4] text-[#111418] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
          <div class="text-[#111418]">
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
              viewBox="0 0 256 256">
              <path
                d="M207.58,63.84C186.85,53.48,159.33,48,128,48S69.15,53.48,48.42,63.84,16,88.78,16,104v48c0,15.22,11.82,29.85,32.42,40.16S96.67,208,128,208s58.85-5.48,79.58-15.84S240,167.22,240,152V104C240,88.78,228.18,74.15,207.58,63.84ZM128,64c62.64,0,96,23.23,96,40s-33.36,40-96,40-96-23.23-96-40S65.36,64,128,64Zm-8,95.86v32c-19-.62-35-3.42-48-7.49V153.05A203.43,203.43,0,0,0,120,159.86Zm16,0a203.43,203.43,0,0,0,48-6.81v31.31c-13,4.07-29,6.87-48,7.49ZM32,152V133.53a82.88,82.88,0,0,0,16.42,10.63c2.43,1.21,5,2.35,7.58,3.43V178C40.17,170.16,32,160.29,32,152Zm168,26V147.59c2.61-1.08,5.15-2.22,7.58-3.43A82.88,82.88,0,0,0,224,133.53V152C224,160.29,215.83,170.16,200,178Z">
              </path>
            </svg>
          </div>
        </button>
      </div>
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
        style='background-image: url("https://cdn.usegalileo.ai/stability/770ef02c-67a6-4814-b5eb-30c958e92f7f.png");'>
      </div>
    </div>
  </header>
</template>


<script>
export default {
  name: 'Header',
}

</script>