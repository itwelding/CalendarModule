<template>
	<div class="w-full h-full bg-[#EFFFEB] border-l-[14px] border-[#9EE78C] rounded-l-md min-h-[100px]" :class="`row-span-${span} row-start-${rowStart} col-start-${colStart}`">		
    <div class="w-full h-full border-y border-slate-200 p-6 overflow-hidden">
      <h3 class="text-base">{{service}} - {{carBrand + ' ' + carModel}}</h3> 
      <p class="text-sm leading-6 text-slate-600 overflow-hidden">
        {{note}}
      </p>
    </div>
	</div>
</template>


<script>
export default {
	props: {
    colStart: {      
      default: 1,
    },
    rowStart: {      
      required: true
    },
    span: {      
      required: true
    },
		service: {
      type: String,
      required: true,
    },
    carBrand: {
      type: String,
      required: true,
    },
    carModel: {
      type: String,
      required: true,
    },
    note: {
      type: String,
      required: true,
    },  
	},
};
</script>