<template>
	<form @submit.prevent="submitVisit()" :class="newVisitShow ? 'flex' : 'hidden'" class="fixed inset-x-0 top-0 py-28 h-full bottom-0 z-10 w-full lg:z-0 lg:static lg:w-2/3 lg:h-full bg-white p-10 rounded-xl flex-col justify-around overflow-y-auto md:overflow-hidden">
		<div class="w-full lg:border-b border-slate-300 flex flex-col items-center lg:items-start lg:flex-row py-10">

			<div class="flex flex-col items-center gap-3 md:gap-6 w-full md:w-1/3 h-40 md:border-slate-200 md:border-r">
				<h3 class="text-sm font-semibold text-slate-400">Wizyta</h3>
				<!-- Day -->
        <input required class="border-0 border-b border-slate-400" v-model="form.date" type="date">
        <!-- Time range picker -->
        <div class="flex items-center justify-center gap-3">
          <input required class="border-0 border-b border-slate-400" v-model="form.time.start" type="time" step="1800">
          <svg class="w-4 h-4 stroke-current text-gray-600" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          <input required class="border-0 border-b border-slate-400" v-model="form.time.end" type="time" step="1800">
        </div>
			</div>

      <div class="flex flex-col items-center gap-3 md:gap-6 w-full md:w-1/3 h-40 overflow-y-auto md:border-slate-200 md:border-r">
        <h3 class="text-sm font-semibold text-slate-400">Produkt/Usługa</h3>
        <select required v-for="(name,index) in form.services" :key="index" v-model="form.services[index]" class="w-40 text-sm border-0 border-b border-slate-400 py-2 px-6 focus:outline-none focus:ring-0 focus:border-slate-400 text-primary">
          <option selected disabled value="0">Wybierz usługę</option>
          <option v-for="service in services" :key="service.id" :value="service.id">{{service.name+', '+service.term+' dni'}}</option>              
        </select>
        <button @click="form.services.push(0)" type="button" class="flex items-center gap-2 w-50 text-sm
          border-0 border-b border-slate-400 py-2 px-6 focus:outline-none 
          focus:ring-0 focus:border-slate-400 placeholder-primary hover:bg-slate-100">
          <span>Kolejna usługa </span>
          <span class="text-xl">+</span>              
        </button>            
      </div>      

      <div class="flex flex-col items-center gap-3 md:gap-6 w-full md:w-1/3 h-40">
		  	<h3 class="text-sm font-semibold text-slate-400">Klient</h3>
		  	<!-- Client -->
        <select required v-model="form.client" class="w-40 text-sm border-0 border-b border-slate-400 py-2 px-6 focus:outline-none focus:ring-0 focus:border-slate-400 text-primary">
          <option disabled value="0">Wybierz klienta</option>
          <option v-for="(id, name) in clients" :key="id" :value="id">{{name}}</option>
        </select>
        <!-- Car -->
        <select required v-model="form.car" class="w-40 text-sm border-0 border-b border-slate-400 py-2 px-6 focus:outline-none focus:ring-0 focus:border-slate-400 text-primary">
          <option selected disabled value="0">Wybierz pojazd</option>              
          <option v-for="car in clientCars" :key="car.id" :value="car.id">{{car.brand + ' ' + car.model}}</option>
        </select>
		  </div>

		</div>
    
		<div class="flex flex-col items-center gap-6 p-4 lg:p-10 lg:border-b border-slate-300">
      <h3 class="text-slate-400">Notatka</h3>
      <textarea required v-model="form.note" class="w-full h-40 border-0 border-b border-slate-300" placeholder="Zacznij pisać..."></textarea>
    </div>
		<div class="flex flex-col items-center gap-6">
      <h3>Czy na pewno chcesz utworzyć wizytę?</h3>
      <div class="flex gap-6">
        <button type="button" @click="closeWindow()" class="text-sm rounded-sm uppercase border-2 border-slate-400 px-6 py-2 w-40 hover:border-red-600 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 text-red-600">Anuluj</button>                
        <button type="submit" :disabled="form.processing" class="text-sm rounded-sm uppercase border-2 border-slate-400 px-6 py-2 w-40 hover:border-secondary focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary">Zatwierdź</button>
      </div>
    </div>

	</form>
</template>


<script>
export default {
  props: {
    newVisitShow: Boolean,
    services: Object,
    clients: Object,
    cars: Object,
  },

  data() {    
    return {
      windowShow: this.newVisitShow,
      form: this.$inertia.form({
        time: {
          start: '',
          end: '',
        },
        note: null,
        client: 0,
        car: 0,
        date: null,
        services: [0],
      }),      
    }
  },

  computed: {
    clientCars() {
      return this.cars.filter(car => car.client_id == this.form.client);
    },
  },

  methods: {
    closeWindow: function() {
      this.windowShow = false;
      this.$emit('closeNewVisitWindow');
    },

    submitVisit: function() {
      this.form.processing = true;
      this.form.post('/calendar');              
      this.$emit('closeNewVisitWindow');        
      this.form.processing = false;
    },    
  }
};
</script>