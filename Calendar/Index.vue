<template>
  <Layout mainHeader="Kalendarz">
    <Head title="Kalendarz" />
    <!-- Calendar Desktop -->
    <div class="w-full page-height rounded-xl overflow-hidden flex gap-10">     
      <!-- Calendar-left -->
      <div :class="newVisitShow ? 'hidden' : 'lg:block'" class="hidden w-2/3 h-full relative">
        <TabGroup>
          <!-- Calendar-nav -->
          <TabList class="px-6 pb-4 flex items-center justify-between">
            <svg width="13" height="22" viewBox="0 0 13 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 1L2 11L12 21" stroke="#4D6182" stroke-width="2" stroke-linecap="round"/></svg>
            <div class="flex items-center justify-center gap-3">
              <Tab v-slot="{ selected }" as="template"><button :class="selected ? 'bg-[#3079F1] text-white' : ''" class="rounded-md w-20 h-9"> Dzień </button></Tab>
              <Tab v-slot="{ selected }" as="template"><button :class="selected ? 'bg-[#3079F1] text-white' : ''" class="rounded-md w-20 h-9"> 3 dni </button></Tab>
              <Tab v-slot="{ selected }" as="template"><button :class="selected ? 'bg-[#3079F1] text-white' : ''" class="rounded-md w-20 h-9"> Tydzień </button></Tab>
            </div>
            <svg width="13" height="22" viewBox="0 0 13 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 21L11 11L1 1" stroke="#4D6182" stroke-width="2" stroke-linecap="round"/></svg>
          </TabList>          
          <!-- Start of white page -->
          <TabPanels class="bg-white w-full left-calendar-height rounded-xl p-6">
            <TabPanel>
              <CalendarDay :visits="visitsFiltered"/>
            </TabPanel>
            <TabPanel>
              <CalendarThreeDays :visits="visitsFilteredThreeDays" :selectedDate="selectedDate"/>
            </TabPanel>
            <TabPanel>
              <CalendarWeek :visits="visitsFilteredWeek" :selectedDate="selectedDate"/>
            </TabPanel>            
          </TabPanels>
        </TabGroup> 
        <!-- Plus button -->
        <button @click="newVisitShow = !newVisitShow" class="hidden lg:flex absolute z-20 bottom-6 right-6 w-14 h-14 rounded-full bg-[#3079F1] items-center justify-center">
          <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.2549 20.962V1.03699" stroke="white" stroke-width="2" stroke-linecap="round"/><path d="M1.29277 10.999L21.2178 10.999" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
      </div>

      <!-- New Visit Window -->
      <NewVisit :clients="clients" :cars="cars" :services="services" :newVisitShow="newVisitShow" @closeNewVisitWindow="newVisitShow=false" />

      <!-- MOBILE:Plus button  -->
      <button @click="newVisitShow = !newVisitShow" class="sm:hidden fixed -top-12 -right-14 rounded-full h-32 w-32 shadow-lg p-10 bg-white z-20 flex justify-center items-center">
        <svg class="relative top-6 right-5" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.35696 7.21956V1.35512C9.35669 1.13293 9.26853 0.91992 9.11181 0.76281C8.95508 0.6057 8.7426 0.517319 8.52096 0.517053C8.40942 0.510329 8.29773 0.5274 8.19327 0.567138C8.0888 0.606876 7.99393 0.668378 7.91492 0.747582C7.83591 0.826786 7.77456 0.921891 7.73492 1.02662C7.69528 1.13134 7.67825 1.24331 7.68496 1.35512V7.21956H1.83596C1.72451 7.21285 1.61292 7.2299 1.50852 7.26958C1.40413 7.30925 1.30931 7.37067 1.23031 7.44976C1.15132 7.52885 1.08995 7.62383 1.05024 7.72844C1.01054 7.83304 0.9934 7.94489 0.99996 8.05662C0.993252 8.16843 1.01028 8.2804 1.04992 8.38512C1.08956 8.48985 1.15091 8.58495 1.22992 8.66416C1.30893 8.74336 1.4038 8.80486 1.50827 8.8446C1.61274 8.88434 1.72443 8.90141 1.83596 8.89469H7.68596V14.7591C7.68622 14.9813 7.77439 15.1943 7.93111 15.3514C8.08783 15.5085 8.30032 15.5969 8.52196 15.5972C8.63349 15.6039 8.74518 15.5868 8.84965 15.5471C8.95412 15.5074 9.04899 15.4459 9.128 15.3667C9.20701 15.2875 9.26836 15.1924 9.308 15.0876C9.34764 14.9829 9.36467 14.8709 9.35796 14.7591V8.89469H15.208C15.4296 8.89442 15.6421 8.80604 15.7988 8.64893C15.9555 8.49182 16.0437 8.27881 16.044 8.05662C16.0507 7.94481 16.0336 7.83285 15.994 7.72812C15.9544 7.6234 15.893 7.52829 15.814 7.44909C15.735 7.36988 15.6401 7.30838 15.5356 7.26864C15.4312 7.22891 15.3195 7.21183 15.208 7.21856L9.35696 7.21956Z" fill="#253959"/>
        </svg>
      </button>
      <!-- MOBILE:left top button  -->
      <button class="sm:hidden fixed -top-12 -left-14 rounded-full h-32 w-32 shadow-lg p-10 bg-white z-20 flex justify-center items-center">
        <svg class="relative top-6 left-5" width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2.61538 3.92308H15.6923C16.386 3.92308 17.0512 4.19863 17.5417 4.68911C18.0321 5.17958 18.3077 5.84482 18.3077 6.53846V10.4615C18.3077 11.1552 18.0321 11.8204 17.5417 12.3109C17.0512 12.8014 16.386 13.0769 15.6923 13.0769H2.61538C1.92174 13.0769 1.25651 12.8014 0.766028 12.3109C0.275549 11.8204 0 11.1552 0 10.4615V6.53846C0 5.84482 0.275549 5.17958 0.766028 4.68911C1.25651 4.19863 1.92174 3.92308 2.61538 3.92308ZM2.61538 5.23077C2.26856 5.23077 1.93595 5.36854 1.69071 5.61378C1.44547 5.85902 1.30769 6.19164 1.30769 6.53846V10.4615C1.30769 10.8084 1.44547 11.141 1.69071 11.3862C1.93595 11.6315 2.26856 11.7692 2.61538 11.7692H15.6923C16.0391 11.7692 16.3717 11.6315 16.617 11.3862C16.8622 11.141 17 10.8084 17 10.4615V6.53846C17 6.19164 16.8622 5.85902 16.617 5.61378C16.3717 5.36854 16.0391 5.23077 15.6923 5.23077H2.61538ZM0 0.653846C0 0.480435 0.0688871 0.314127 0.191507 0.191507C0.314127 0.0688871 0.480435 0 0.653846 0H17.6538C17.8273 0 17.9936 0.0688871 18.1162 0.191507C18.2388 0.314127 18.3077 0.480435 18.3077 0.653846C18.3077 0.827257 18.2388 0.993565 18.1162 1.11619C17.9936 1.23881 17.8273 1.30769 17.6538 1.30769H0.653846C0.480435 1.30769 0.314127 1.23881 0.191507 1.11619C0.0688871 0.993565 0 0.827257 0 0.653846ZM0 16.3462C0 16.1727 0.0688871 16.0064 0.191507 15.8838C0.314127 15.7612 0.480435 15.6923 0.653846 15.6923H17.6538C17.8273 15.6923 17.9936 15.7612 18.1162 15.8838C18.2388 16.0064 18.3077 16.1727 18.3077 16.3462C18.3077 16.5196 18.2388 16.6859 18.1162 16.8085C17.9936 16.9311 17.8273 17 17.6538 17H0.653846C0.480435 17 0.314127 16.9311 0.191507 16.8085C0.0688871 16.6859 0 16.5196 0 16.3462Z" fill="#253959"/>
        </svg>
      </button>

      <!-- Calendar-right -->
      <div class="w-full lg:w-1/3 h-full bg-white pt-10 lg:pt-0 sm:p-6 rounded-xl">
        <CalendarRight :visits="visits" :visitsFiltered="visitsFiltered" @dateChange="(newDate) => {selectedDate = newDate}"/>
      </div> 
    </div>
  </Layout>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Icon from '@/Shared/Icon'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import CalendarDay from '@/Pages/Calendar/CalendarDay'
import CalendarThreeDays from '@/Pages/Calendar/CalendarThreeDays'
import CalendarWeek from '@/Pages/Calendar/CalendarWeek'
import CalendarRight from '@/Pages/Calendar/CalendarRight'
import NewVisit from '@/Pages/Calendar/NewVisit'

export default {
  components: {
    Layout,
    Head,
    Icon,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
    CalendarDay,
    CalendarThreeDays,
    CalendarWeek,
    CalendarRight,
    NewVisit,
  },

  props: {
    visits: Object,
    services: Object,
    clients: Object,
    cars: Object,
  },

  data() {
    const day = new Date().getDate();
    const month = new Date().getMonth();
    const year = new Date().getFullYear();
    return {
      newVisitShow: false,
      selectedDate: `${year}-${month+1}-${day}`,
      visitsFiltered: [],
      visitsFilteredThreeDays: [],
      visitsFilteredWeek: [],
    }
  },

  watch: {
    // Getting new date from CalendarRight component
    // and updating visits array by filtering it with new date
    selectedDate: function(val) {
      let arr = val.split('-');
      let day = new Date(arr[0], arr[1]-1, arr[2]);
      let day1 = day.getTime();
      let day2 = day.setDate(day.getDate()+1);
      let day3 = day.setDate(day.getDate()+1);
      let day4 = day.setDate(day.getDate()+1);
      let day5 = day.setDate(day.getDate()+1);
      let day6 = day.setDate(day.getDate()+1);
      let day7 = day.setDate(day.getDate()+1);    

      this.visitsFiltered = this.visits.filter((item) => {
        let arrVisit = item.visit.date.split('-');
        let visitDay = new Date(arrVisit[0], arrVisit[1]-1, arrVisit[2]).getTime();
        if (visitDay == day1) {
          return item;
        } 
        else {
          return false;
        }         
      });

      this.visitsFilteredThreeDays = this.visits.filter((item) => {
        let arrVisit = item.visit.date.split('-');
        let visitDay = new Date(arrVisit[0], arrVisit[1]-1, arrVisit[2]).getTime();
        if (visitDay == day1 || visitDay == day2 || visitDay == day3) {
          return item;
        } 
        else {
          return false;
        }         
      });

      this.visitsFilteredWeek = this.visits.filter((item) => {
        let arrVisit = item.visit.date.split('-');
        let visitDay = new Date(arrVisit[0], arrVisit[1]-1, arrVisit[2]).getTime();
        if (visitDay == day1 || visitDay == day2 || visitDay == day3 || visitDay == day4 || visitDay == day5 || visitDay == day6 || visitDay == day7) {
          return item;
        } 
        else {
          return false;
        }         
      });
    }
  },
}
</script>


<style scoped>
  @media (min-width: 768px) {
    .page-height {
      height: calc(100vh - 112px);
    }   
    .left-calendar-height {
      height: calc(100% - 52px);
    }    
  }
</style>