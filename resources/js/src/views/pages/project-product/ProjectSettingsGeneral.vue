<template>
  <b-card>
    <!-- Info -->
    <vs-input class="w-full mb-base" label-placeholder="Name" v-model="name"></vs-input>
    <vs-input class="w-full mb-base" label-placeholder="Website" v-model="website"></vs-input>

    <!-- Save & Reset Button -->
    <div class="flex flex-wrap items-center justify-end">
      <b-button class="ml-auto mt-2" v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="updateProjectGeneralData">Save Changes</b-button>
      <b-button class="ml-4 mt-2" type="border" v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="warning">Reset</b-button>
    </div>
  </b-card>
</template>

<script>
import {http} from '@/services/requests'
import {
  BCard, BCardTitle, BCardSubTitle, BCardBody, BCollapse, BButton
} from 'bootstrap-vue'

export default {
  components: {
    BButton,
    BCard,
    BCardTitle,
    BCardSubTitle,
    BCardBody,
    BCollapse
  },
  data () {
    return {
      name: '',
      website: ''
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },

    currentUser () {
      return authentication.currentUser.source._value
    }
  },

  methods:{
    updateProjectGeneralData() {
      http.put('projects/update/'+localStorage.getItem('selectedProjectId'),
        {
          name:this.name,
          website:this.website
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
      http.get('projects/'+localStorage.getItem('selectedProjectId')).then(response => {
        this.name = response.data.name;
        this.website = response.data.website;
      }).catch(error => {

      });
    }
  },

  created () {
    this.getProject();
  },

  mounted () {

  }
}
</script>
