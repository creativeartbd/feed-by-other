<template>
  <vs-card no-shadow>
    <!-- Country -->
    <div class="mt-8">
      <v-select @input="chooseCountry($event)"  placeholder="Country" name="country"  label="country_name"
                class="w-full mb-4 mt-5"   v-model="projects.country" :options="countries"  v-validate="'required'" ></v-select>
      <!-- <span class="text-danger text-sm">{{ errors.first('country') }}</span> -->
    </div>

    <!-- Currency -->
    <div class="mt-8">
      <v-select  placeholder="Currencies" name="currency"   label="currency_name" class="w-full mb-4 mt-5"
                 v-model="projects.currency" :options="currencies"  v-validate="'required'" ></v-select>
      <!-- <span class="text-danger text-sm">{{ errors.first('currency') }}</span> -->
    </div>

    <!-- Timezone -->
    <div class="mt-8">
      <v-select  placeholder="Timezones"   name="timezones"  label="time_zone" class="w-full mb-4 mt-5"
                 v-model="projects.timezone" :options="timezones"  v-validate="'required'" ></v-select>
      <!-- <span class="text-danger text-sm">{{ errors.first('timezone') }}</span> -->
    </div>

    <!-- Save & Reset Button -->
    <div class="flex flex-wrap items-center justify-end">
      <b-button class="ml-auto mt-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="updateProjectInfo">Save Changes</b-button>
      <b-button class="ml-4 mt-2" type="border" v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="warning">Reset</b-button>
    </div>
  </vs-card>
</template>

<script>
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import vSelect from 'vue-select'
import {http} from '@/services/requests'
import {
  BCard, BCardTitle, BCardSubTitle, BCardBody, BCollapse, BButton
} from 'bootstrap-vue'

export default {
  components: {
    flatPickr,
    vSelect,
    BButton,
    BCard,
    BCardTitle,
    BCardSubTitle,
    BCardBody,
    BCollapse
  },
  data () {
    return {
      countries: [],
      currencies: [],
      timezones: [],
      country: '',
      currency: '',
      timezone: '',
      projects: {
        country: '',
        currency: '',
        timezone: '',
      }
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    }
  },
  methods:{
    updateProjectInfo() {
      http.put('projects/update/'+localStorage.getItem('selectedProjectId'), {
        country_id:  this.projects.country.id_country,
        currency_id: this.projects.currency.id_currency,
        timezone_id: this.projects.timezone.id_timezone
      }).then(response => {
        this.$vs.notify({
          text:'Update',
          color:'success',
          iconPack: 'feather',
          icon:'icon-check'
        })
      }).catch(error => {

      });
    },

    getProject() {
      let _this = this;
      http.get('projects/'+localStorage.getItem('selectedProjectId')).then(response => {
        _this.countries.forEach(function(entry) {
          if(entry.id_country == response.data.country_id){
            _this.projects.country = entry
          }
        });

        _this.currencies.forEach(function(entry) {
          if(entry.id_currency == response.data.currency_id){
            _this.projects.currency = entry
          }
        });

        _this.timezones.forEach(function(entry) {
          if(entry.id_timezone == response.data.timezone_id){
            _this.projects.timezone = entry

          }
        });

      }).catch(error => {

      });
    },

    chooseCountry(event) {
      this.getTimezones(event)
      this.getCurrencies(event)
    },

    getCountries() {
      http.get('countries').then(response => {
        this.countries = response.data;
      }).catch(error => {

      });
    },

    getCurrencies(country) {
      let _this = this;
      http.get('currencies').then(response => {
        _this.currencies = response.data;
        response.data.forEach(function(entry) {
          if(entry.state == country.country_name){
            _this.projects.currency = entry
          }
        });
      }).catch(error => {

      });
    },

    getTimezones(country) {
      let _this = this;
      http.get('timezones').then(response => {
        _this.timezones = response.data;
        response.data.forEach(function(entry) {
          if(entry.country_code == country.code){
            _this.projects.timezone = entry

          }
        });
      }).catch(error => {

      });
    },
  },

  mounted () {
    let _this = this;
    this.getTimezones();
    this.getCountries();
    this.getCurrencies();
    setTimeout(function(){
      _this.getProject();
    }, 100);

  }
}
</script>
