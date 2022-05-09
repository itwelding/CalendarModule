<template>
  <div class="page-left-calendar-sub overflow-y-auto w-full relative">
    <div class="grid grid-cols-week-header h-32 items-center content-center pb-6">
      <div></div>
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay()[0]}}</span><span class="text-slate-400">{{getSelectedDay()[1]}}</span></div>
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(1)[0]}}</span><span class="text-slate-400">{{getSelectedDay(1)[1]}}</span></div>
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(2)[0]}}</span><span class="text-slate-400">{{getSelectedDay(2)[1]}}</span></div>      
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(3)[0]}}</span><span class="text-slate-400">{{getSelectedDay(3)[1]}}</span></div>
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(4)[0]}}</span><span class="text-slate-400">{{getSelectedDay(4)[1]}}</span></div>
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(5)[0]}}</span><span class="text-slate-400">{{getSelectedDay(5)[1]}}</span></div>      
      <div class="flex flex-col items-center justify-center gap-3"><span>{{getSelectedDay(6)[0]}}</span><span class="text-slate-400">{{getSelectedDay(6)[1]}}</span></div>      
    </div>

    <div v-for="(hour,index) in hours" :key="index" class="grid grid-cols-calendar-day w-full">
      <div class="mt-[-10px] text-sm tabular-nums">{{hour}}</div>      
      <div class="pl-10 border-t border-slate-300 h-[100px]">
      </div>      
    </div>
    
    <div class="absolute z-10 top-32 left-24 right-0 bottom-0 grid grid-cols-7 grid-rows-22">      
      <CalendarDayEvent v-for="item in sortedVisits" 
        :key="item.visit.id"
        :rowStart="item.row_start"
        :colStart="item.col_start"
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
    selectedDate: String,
  },

  data() {    
    return {      
      hours: ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00'],
    }
  },

  methods: {
    /**
     * Returns array with week day name and selected date
     * 
     * @param {int} offset Default=0, selected=monday + offset=1 => returns tuesday
     * @return {string[2]} Array with pl week day and date DD.MM 
     */
    getSelectedDay: function(offset=0) {
      if(this.selectedDate=='') {
        return [
          'Dzień',
          'DD.MM'
        ]
      }
      const days = ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'];
      let arr = this.selectedDate.split('-');
      let day = new Date(arr[0], arr[1]-1, arr[2]);      
      day.setDate(day.getDate() + offset);
      let dayName = days[day.getDay()];
      return [
        dayName,
        `${day.getDate()}.${day.getMonth()+1}`
      ]
    },
  },

  computed: {    
    sortedVisits: function() {
      let arr=[];
      let row_start = 0;
      let col_start = 1;
      let span = 0;
      let time_start = '';
      let time_end = '';
      let tmpArr = [];      

      let visit_date = '';
      let day = new Date(this.selectedDate);
      let day1 = day.getTime();
      let day2 = day.setDate(day.getDate()+1);
      let day3 = day.setDate(day.getDate()+1);
      let day4 = day.setDate(day.getDate()+1);
      let day5 = day.setDate(day.getDate()+1);
      let day6 = day.setDate(day.getDate()+1);
      let day7 = day.setDate(day.getDate()+1);

      for(let i=0; i<this.visits.length; i++) {
        visit_date =  new Date(this.visits[i].visit.date).getTime();
        if(visit_date == day1) {
          col_start = 1;
        }
        else if (visit_date == day2) {
          col_start = 2;
        }
        else if (visit_date == day3) {
          col_start = 3;
        }
        else if (visit_date == day4) {
          col_start = 4;
        }
        else if (visit_date == day5) {
          col_start = 5;
        }
        else if (visit_date == day6) {
          col_start = 6;
        }
        else if (visit_date == day7) {
          col_start = 7;
        }

        time_start = this.visits[i].visit.time_start;
        tmpArr = time_start.split(':');        
        time_start = `${tmpArr[0]}:${tmpArr[1]}`;

        time_end = this.visits[i].visit.time_end;
        tmpArr = time_end.split(':');        
        time_end = `${tmpArr[0]}:${tmpArr[1]}`;

        for (let j=0; j<this.hours.length; j++) {
          if(this.hours[j] == time_start) {
            row_start = j+1;
          }
          if(this.hours[j] == time_end) {
            span = j-row_start+1;
          }
        }

        arr.push({
          'col_start': col_start,
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
  .grid-cols-week-header {
    grid-template-columns: 90px repeat(7, minmax(0,1fr));
  }
</style>