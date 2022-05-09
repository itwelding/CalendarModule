<template>
  <div class="page-left-calendar-sub overflow-y-auto w-full pt-32 relative">

    <div v-for="(hour,index) in hours" :key="index" class="grid grid-cols-calendar-day w-full">
      <div class="mt-[-10px] text-sm tabular-nums">{{hour}}</div>      
      <div class="pl-10 border-t border-slate-300 h-[100px]">
      </div>      
    </div>
    
    <div class="absolute z-10 top-32 left-24 right-0 bottom-0 grid grid-cols-1 grid-rows-22">      
      <CalendarDayEvent v-for="item in sortedVisits" 
        :key="item.visit.id"
        :rowStart="item.row_start"
        :span="item.span" 
        :service="(!!item.visit.visit.services[0] && !!item.visit.visit.services[0].name) ? item.visit.visit.services[0].name : 'Brak'" 
        :carBrand="item.visit.car.brand ?? 'Brak'" 
        :carModel="item.visit.car.model ?? 'Brak'" 
        :note="item.visit.visit.note ?? 'Brak'"
      />      
    </div>

  </div>
</template>


<script>
import CalendarDayEvent from '@/Pages/Calendar/CalendarDayEvent'

export default {
  components: {
    CalendarDayEvent,
  },

  props: {
    visits: Object,
  },

  data() {    
    return {      
      hours: ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00'],
    }
  },

  computed: {
    sortedVisits: function() {
      let arr=[];
      let row_start = 0;
      let span = 0;
      let time_start = '';
      let time_end = '';
      let tmpArr = [];      

      for(let i=0; i<this.visits.length; i++) {
        time_start = this.visits[i].visit.time_start;
        tmpArr = time_start.split(':');        
        time_start = `${tmpArr[0]}:${tmpArr[1]}`;

        time_end = this.visits[i].visit.time_end;
        tmpArr = time_end.split(':');        
        time_end = `${tmpArr[0]}:${tmpArr[1]}`;

        console.log(time_start);
        console.log(time_end);  
        for (let j=0; j<this.hours.length; j++) {
          if(this.hours[j] == time_start) {
            row_start = j+1;
          }
          if(this.hours[j] == time_end) {
            span = j-row_start+1;
          }
        }

        arr.push({
          'row_start': row_start,
          'span': span,
          'visit': this.visits[i]
        });
      }
      if(row_start == 0) {
        arr = [];
      }
      return arr;
    }
  }

}
</script>


<style scoped>
  @media (min-width: 768px) {
    .page-left-calendar-sub {
      height: calc(100vh - 212px);
    }    
  }
  /* Track */
  div.page-left-calendar-sub::-webkit-scrollbar-track {
    background: white;
  }
  
  /* Handle */
  div.page-left-calendar-sub::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background: #EBEBEB; 
  }
  .grid-rows-22 {
    grid-template-rows: repeat(22, 100px);
  }
</style>