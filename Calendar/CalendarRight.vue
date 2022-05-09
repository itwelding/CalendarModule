<template>
	<div class="h-full flex flex-col pt-6">
    <DatePicker
      class="max-w-full"
      v-model="selectedDate"      
      :model-config="{
        type: 'string',
        mask: 'YYYY-MM-DD',
      }"
      :masks="{ L: 'YYYY-MM-DD' }"
      :attributes="attributes"
      disable-page-swipe
      is-expanded
      locale="pl"
    >
    </DatePicker>
    <!-- Panel with visits -->
    <div class="flex flex-col w-full calendar-panel-height">
      <h3 class="px-8 pb-4 text-sm text-slate-400">Nadchodzące</h3>
      <div id="calendar-right-scroll" class="overflow-y-auto px-6">      
        <div v-for="item in visitsFiltered" :key="item.visit.id" class="py-4 px-2 w-full border-b border-slate-300 flex gap-6">
          <div class="flex flex-col justify-center gap-2 text-sm">
            <time :datetime="item.visit.time_start" class="tabular-nums">{{visitTimeInPanel(item.visit.time_start)}}</time>
            <time :datetime="item.visit.date" class="tabular-nums">{{visitDateInPanel(item.visit.date)}}</time>
          </div>
          <div class="flex items-center justify-center">
            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_145_2145)"><path d="M9.26814 12.5781C10.9957 12.5781 12.3961 11.1794 12.3961 9.45408C12.3961 7.72874 10.9957 6.33008 9.26814 6.33008C7.54059 6.33008 6.14014 7.72874 6.14014 9.45408C6.14014 11.1794 7.54059 12.5781 9.26814 12.5781Z" fill="#3A90F6"/></g><defs><filter id="filter0_d_145_2145" x="0.140137" y="0.330078" width="18.2559" height="18.248" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset/><feGaussianBlur stdDeviation="3"/><feColorMatrix type="matrix" values="0 0 0 0 0.156863 0 0 0 0 0.454902 0 0 0 0 0.807843 0 0 0 1 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_145_2145"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_145_2145" result="shape"/></filter></defs></svg>
          </div>
          <div class="flex flex-col items-start justify-center gap-2"> 
            <span v-for="service in item.visit.services" :key="service.id">              
              {{(!!service && !!service.name) ? service.name : 'Brak' }} - {{item.car.brand ?? 'Brak'}} {{item.car.model ?? 'Brak'}}
            </span>
            <span class="text-sm text-slate-400">{{item.visit.note ?? 'Brak'}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import 'v-calendar/dist/style.css';
import { DatePicker } from '@/../../node_modules/v-calendar';

export default {
  components: {
    DatePicker,
  },

  props: {
    visits: Object,
    visitsFiltered: Object,
  },

  created() {
    for(let i = 0; i < this.visits.length; i++) {
      this.attributes[0].dates.push(this.visits[i].visit.date);
    }
  },

  data() {
    const month = new Date().getMonth();
    const year = new Date().getFullYear();
    return {
      selectedDate: new Date(),
      masks: {
        weekdays: 'W',
      },
      attributes: [
        {
          key: 1,      
          dot: true, 
          dot: "gray",              
          dates: [],
        },        
      ],
    };
  },

  methods: {      
    visitDateInPanel: function(date) {
      const day = new Date().getDay();
      const month = new Date().getMonth();
      const year = new Date().getFullYear();
      let today = `${year}-${month}-${day}`;
      let tommorow = `${year}-${month}-${day+1}`;
      if(date == today) {
        return "Dzisiaj";
      }
      else if(date == tommorow) {
        return "Jutro";
      }
      else {
        let arr = date.split('-');        
        return `${arr[2]}.${arr[1]}`;
      }
    },

    visitTimeInPanel: function(time) {
      let arr = time.split(':');        
      return `${arr[0]}:${arr[1]}`;
    }
  },

  watch: {
    selectedDate: function(val) {
      this.$emit('dateChange', val);
    }
  }
};
</script>


<style>
.calendar-panel-height {
  height: calc(100vh - 600px);
}

.vc-container {
  border: 0;
}

.vc-day {
  padding: 2px;
}

.vc-header {
  padding-bottom: 10px;
}

.vc-title {
  font-size: 18px !important;
  color: #2E2E2E !important;
  font-weight: 300 !important;
  line-height: 28px !important;
  text-transform: capitalize;
}

.vc-arrow > .vc-svg-icon {
  color: #253959;
}

.vc-day-content {
  font-size: 18px !important;
  width: 38px !important;
  height: 50px !important;
  border-radius: 6px;
  font-weight: normal;
  font-size: 14px;
  line-height: 22px;
  color: #2E2E2E;
}

.vc-day-content:hover {
  background: rgba(37, 57, 89, 0.08) !important;
}

.vc-weekday {
  font-size: 12px;
  color: #BFBFBF;
  font-weight: 500;
}

.weekday-position-7 > span {
  color: #FF2359 !important;
}

.vc-dot {
  width: 8px !important;
  height: 8px !important;
  background-color: #C0CADA !important;
  margin-bottom: 4px !important;
}

#calendar-right-scroll::-webkit-scrollbar-track {
  background: white;
}
 
/* Handle */
#calendar-right-scroll::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: #EBEBEB; 
}

@media (max-width: 768px) {
  .vc-arrows-container {
    justify-content: space-evenly;
  }
}
</style>
