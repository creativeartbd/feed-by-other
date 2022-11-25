<template>
  <b-tabs 
    vertical
    content-class="col-12 col-md-9 mt-1 mt-md-0"
    pills
    nav-wrapper-class="col-md-3 col-12"
    id="profile-tabs">
    <!-- GENERAL -->
    <b-tab title="General" active>
       <!-- title -->
      <template #title>
        <feather-icon
          icon="UserIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">General</span>
      </template>
      <project-settings-general/>
    </b-tab>
    <b-tab icon-pack="feather" icon="icon-info" title="Data settings">
       <!-- title -->
      <template #title>
        <feather-icon
          icon="UserIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Settings</span>
      </template>
      <project-settings-info/>
    </b-tab>
    <b-tab icon-pack="feather" icon="icon-link-2" title="Product Import">
       <!-- title -->
      <template #title>
        <feather-icon
          icon="UserIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Import</span>
      </template>
      <product-import/>
    </b-tab>
  </b-tabs>
</template>

<script>
import {http} from '@/services/requests'
import ProjectSettingsGeneral from './ProjectSettingsGeneral.vue'
import ProjectSettingsInfo from './ProjectSettingsInfo.vue'
import ProductImport from './ProductImport.vue'
import { BTabs, BTab, BCardText } from 'bootstrap-vue'

export default {
  components: {
    ProjectSettingsGeneral,
    ProjectSettingsInfo,
    ProductImport,
    BTabs,
    BTab,
    BCardText
  },
  data () {
    return {
      project:{},
      options: {}
    }
  },
  computed: {
    isSmallerScreen () {
      return this.$store.state.windowWidth < 768
    }
  },

  methods:{
    getProject() {
      http.get('projects/'+localStorage.getItem('selectedProjectId')).then(response => {
        console.log(response.data, 12212)
        this.project = response.data
      }).catch(error => {

      });
    }
  },

  mounted () {
    this.getProject()
  },

   beforeCreate() {
    this.$http.get('/projects/data').then(res => { this.options = res.data })
  }

}
</script>

<style lang="scss">
#profile-tabs {
  .b-tabs--content {
    padding: 0;
  }
}
</style>
